<?php
#Librerias
session_start();

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST[busca])) {

    if ($_REQUEST[busca] == ini) {

        $Pos = strrpos($_REQUEST[Ret], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST[Ret] . '&';
        } else {
            if ($_REQUEST[Ret] <> '') {
                $Retornar = $_REQUEST[Ret] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'depd.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    
    } elseif ($_REQUEST["busca"] == "NUEVO") {  

        $_SESSION["OnToy"] = array('NUEVO', '', 'depd.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    
    } elseif ($_REQUEST[busca] <> '') {

        $_SESSION['OnToy'][0] = $_REQUEST[busca];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST[pagina])) {
    $_SESSION['OnToy'][1] = $_REQUEST[pagina];
}
if (isset($_REQUEST[orden])) {
    $_SESSION['OnToy'][2] = $_REQUEST[orden];
}
if (isset($_REQUEST[Sort])) {
    $_SESSION['OnToy'][3] = $_REQUEST[Sort];
}
if (isset($_REQUEST[Ret])) {
    $_SESSION['OnToy'][4] = $_REQUEST[Ret];
}

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$RetSelec = $_SESSION[OnToy][4];
$Id = 61;
$Cat='Subdepto';

$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$op = $_REQUEST[op];
$deptos = $_REQUEST[deptos];
$Obsr = $_REQUEST["Msj"];
$Fecha  = date("Y-m-d H:i:s");

if($op=='Elim'){                    //Elimina rg

    $sqlB = "SELECT * FROM depd WHERE id=$_REQUEST[Id]";
    $cSqlb = mysql_query($sqlB);
    $sqlb = mysql_fetch_array($cSqlb);
    
    $lUp  = "DELETE FROM depd WHERE id='$_REQUEST[Id]' limit 1;";

    if (!mysql_query($lUp)) {
        $Msj = "Error en sintaxis MYSQL " . $sql;
    } else {
        $Msj = "Registro Eliminado con Exito";
        $sqlD = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Cat Subdepartamento/Elimina Subepto $_REQUEST[Id] - $sqlb[nombre]','Subdepto','$Fecha','$deptos')";
        mysql_query($sqlD);
        header("Location: deptod.php?busca=ini&Msj=$Msj&deptos=$deptos");
    }

}

if ($_REQUEST["bt"] == "Actualizar") {

    $sql = "UPDATE depd SET subdepto='$_REQUEST[Subdepto]', nombre='$_REQUEST[Nombre]' WHERE id='$_REQUEST[Id]' limit 1;";

    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis MYSQL " . $sql;
    } else {
        $Msj = "Registro Actualizado con Exito";
        $sqlD = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Cat Departamento/Actualiza Subdepto $_REQUEST[Id] - $_REQUEST[Nombre]','Subdepto','$Fecha','$deptos')";
        mysql_query($sqlD);
        header("Location: deptod.php?Msj=$Msj&deptos=$deptos");
    }

}elseif($_REQUEST["bt"] == "Agregar"){

    $sql = "INSERT INTO depd (departamento,subdepto,nombre) VALUES ('$deptos','$_REQUEST[Subdepto]','$_REQUEST[Nombre]') limit 1;";

    if (mysql_query($sql)) {

        $Msj = "¡Registro ingresado con exito!";

        $cId = mysql_insert_id();

        $sqlD = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Cat Departamento/Agrega nuevo Subdepto $cId - $_REQUEST[Nombre]','Subdepto','$Fecha','$deptos')";

        mysql_query($sqlD);

        header("Location: deptod.php?busca=ini&Msj=$Msj&deptos=$deptos");

    } else {

        $Msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
        header("Location: deptod.php?busca=ini&Msj=$Msj&Error=SI");
    }

}elseif($_REQUEST["bt"] == "Cancelar"){

    $op='';
    $busca='';

}

$deptoA = mysql_query("SELECT * FROM dep WHERE departamento=$deptos");
$depto = mysql_fetch_array($deptoA);

$Nombre=$depto[departamento] .' - '.$depto[nombre];

if( $busca == '' or $busca == 'NUEVO'){

    $cSql = "SELECT $Qry[campos],depd.departamento FROM $Qry[froms] WHERE  id >= 0 AND departamento=$deptos"; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos],depd.departamento FROM $Qry[froms] WHERE departamento='$busca'";

}else{
    
    $cSql = "SELECT $Qry[campos],depd.departamento FROM $Qry[froms] WHERE $BusInt";

}

$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Catalogo de Subdepartamentos</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>
    <script type="text/javascript">
        $(document).ready(function () {
            var observaciones = "<?= $Obsr ?>";
            if (observaciones != "") {
                Swal.fire({
                    title: observaciones,
                    position: "top-right",
                    icon: "info",
                    toast: true,
                    timer: 1500
                })
            }
        });
    </script>
    <?php
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu, $Gusr);
    ?>
    <script src="./controladores.js"></script>
<?php
    echo '<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tr style="background-color: #2c8e3c">';
    echo '<td class="letratitulo" align="center" colspan="2">..:: Departamento: '. $Nombre .' ::..</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td height="380" valign="top">';

    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

    $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
    $res = mysql_query($sql);

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . '.php?deptos=$deptos';     #
    //$uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;
        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Id=$rg[id]&deptos=$deptos&op=editar'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";

        Display($aCps, $aDat, $rg);

        echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro&nbsp;$rg[id]?','$_SERVER[PHP_SELF]?Id=$rg[id]&op=Elim&deptos=$deptos');><i class='fa fa-trash fa-2x' aria-hidden='true'></i> </a></td>";

        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    echo '<table border="0" aling="center">';
        echo '<tr>';
            echo '<td><a href="depto.php?busca=ini" class="content5" ><i class="fa fa-reply fa-1x" aria-hidden="true"></i><font size="1"> Regresar </font></a></td>';
            echo "<td align='center'><a class='edit' href=javascript:winuni('logsubdepto.php?deptos=$deptos')><i class='fa fa-file-code-o fa-1x' aria-hidden='true'></i><font size='1'> Log Subdepartamento </font></a></td>";
        echo '</tr>';
    echo '</table>';

    
    echo '<table border="0" aling="center">';
    echo '<tr>';
    echo '</tr>';
    echo '</table>';


    PonPaginacionesdep(true);           #-------------------pon los No.de paginas-------------------    

    $sqlA = "SELECT * FROM depd WHERE id=$_REQUEST[Id]";
    $cSql = mysql_query($sqlA);
    $sql = mysql_fetch_array($cSql);
    if ($op == 'editar' or $busca=='NUEVO') {
        ?>
        <br>
        <table width='98%' align='center' border='0' cellpadding='0' cellspacing='0' bgcolor='#2471A3' style='border-collapse: collapse; border: 1px solid #999;'>  
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                    <tr>
                      <td> 
                        &nbsp; 
                        <a class="Inpt" style="color:white"><b> Id: </b><?= $sql[id] ?> </a>
                        &nbsp;

                        <?php
                        if ($busca=='NUEVO') {
                        ?>
                            <a class="Inpt" style="color:white"><b>Sub-Depto: </b>
                                <input type='text' class='cinput' style="width: 200px" autofocus name='Subdepto' value='<?= $sql[subdepto] ?>'>
                            </a>
                        <?php
                        }else{
                        ?>
                            <a class="Inpt" style="color:white"><b>Sub-Depto: </b>
                                <input type='text' class='cinput' style="width: 200px"  name='Subdepto' value='<?= $sql[subdepto] ?>'>
                            </a>
                        <?php
                        }
                        ?>

                        &nbsp; 
                        <a class="Inpt" style="color:white"><b>Nombre: </b>
                            <input type='text' class='cinput'  style="width: 300px" name='Nombre' value='<?= $sql[nombre] ?>'>
                        </a>


                        &nbsp; 
                        <?php
                        if ($busca=='NUEVO') {
                        ?>
                            <input class="letrap" type="submit" value='Agregar' name='bt'></input>
                        <?php
                        }else{
                        ?>
                            <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                        <?php
                        }
                        ?>
                        &nbsp; 
                        <input class="letrap" type="submit" value='Cancelar' name='bt'></input>
                        <input type="hidden" value="<?= $_REQUEST[Id] ?>" name="Id"></input>
                        <input type="hidden" value="<?= $deptos ?>" name="deptos"></input>
                    </td>
                </tr>
            </form>
        </table>
        <?php
    }

    CuadroInferior4($busca);
    echo '</body>';
    ?>
</html>
<?php
mysql_close();
?>
