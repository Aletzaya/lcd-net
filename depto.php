<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST["busca"])) {

    if ($_REQUEST["busca"] == "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'dep.departamento', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    
    } elseif ($_REQUEST["busca"] == "NUEVO") {  

        $_SESSION["OnToy"] = array('NUEVO', '', 'dep.departamento', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)

    } elseif ($_REQUEST["busca"] <> '') {

        $_SESSION['OnToy'][0] = $_REQUEST["busca"];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST["pagina"])) {
    $_SESSION['OnToy'][1] = $_REQUEST["pagina"];
}
if (isset($_REQUEST["orden"])) {
    $_SESSION['OnToy'][2] = $_REQUEST["orden"];
}
if (isset($_REQUEST["Sort"])) {
    $_SESSION['OnToy'][3] = $_REQUEST["Sort"];
}
if (isset($_REQUEST["Ret"])) {
    $_SESSION['OnToy'][4] = $_REQUEST["Ret"];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$busca = strtolower($busca);
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$RetSelec = $_SESSION["OnToy"][4];
$Id = 11;
$Cat='Depto';
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

//Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION["OnToy"][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  

$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$op=$_REQUEST["op"];
$Fecha  = date("Y-m-d H:i:s");

if($op=='Elim'){                    //Elimina rg

    $sqlA = "SELECT count(*) as contar FROM depd WHERE departamento=$_REQUEST[Id]";
    $cSql = mysql_query($sqlA);
    $sql = mysql_fetch_array($cSql);

    if($sql[contar]==0){

        $sqlB = "SELECT * FROM dep WHERE departamento=$_REQUEST[Id]";
        $cSqlb = mysql_query($sqlB);
        $sqlb = mysql_fetch_array($cSqlb);
        
        $lUp  = "DELETE FROM dep WHERE departamento='$_REQUEST[Id]' limit 1;";

        if (!mysql_query($lUp)) {
            $Msj = "Error en sintaxis MYSQL " . $sql;
        } else {
            $Msj = "Registro Eliminado con Exito";
            $sqlD = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Cat Departamento/Elimina Depto $sqlb[nombre]','Depto','$Fecha','$_REQUEST[Id]')";
            mysql_query($sqlD);
            header("Location: depto.php?busca=ini&Msj=$Msj");
        }

    }else{
        
        $Msj = "Registro Contiene informacion no se puede Eliminar";

        header("Location: depto.php?busca=ini&Msj=$Msj&Error=SI");

    }

}

if ($_REQUEST["bt"] == "Actualizar") {

    $sql = "UPDATE dep set nombre='$_REQUEST[Nombre]' WHERE departamento = $_REQUEST[deptos]";

    if (!mysql_query($sql)) {
        $Msj = "Error en sintaxis MYSQL " . $sql;
    } else {
        $Msj = "Registro Actualizado con Exito";
        $sqlD = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Cat Departamento/Actualiza Depto $_REQUEST[Nombre]','Depto','$Fecha','$_REQUEST[deptos]')";
        mysql_query($sqlD);
        header("Location: depto.php?busca=ini&Msj=$Msj");
    }

}elseif($_REQUEST["bt"] == "Agregar"){

    $sql = "INSERT INTO dep (nombre) VALUES ('$_REQUEST[Nombre]') limit 1;";

    if (mysql_query($sql)) {

        $Msj = "??Registro agregado con exito!";

        $cId = mysql_insert_id();

        $sqlD = "INSERT INTO logcat (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Cat Departamento/Agrega nuevo Depto $_REQUEST[Nombre]','Depto','$Fecha','$cId')";

        mysql_query($sqlD);

        header("Location: depto.php?busca=ini&Msj=$Msj");

    } else {

        $Msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
        header("Location: depto.php?busca=ini&Msj=$Msj&Error=SI");
    }

}elseif($_REQUEST["bt"] == "Cancelar"){

    $op='';
    $busca='';

}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" dep.nombre like '%$P[$i]%' ";}else{$BusInt=$BusInt." and dep.nombre like '%$P[$i]%' ";}
 }

}else{
        $BusInt=" dep.nombre like '%$busca%' ";  
}

#Armo el query segun los campos tomados de qrys;

if( $busca == '' or $busca == 'nuevo'){

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE departamento>= 0"; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE departamento='$busca'";

}else{
    
    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt";

}

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-", "<b>Sub-Depto</b>", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];
$Obsr = $_REQUEST["Msj"];
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Catalogo de Departamentos ::..</title>
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
    //submenu();
//Tabla contenedor de brighs
    echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tr>';
    echo '<td height="380" valign="top">';

    PonEncabezado();

    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------

    $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;

    $res = mysql_query($sql);

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . '.php';     #
    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;
        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?busca=ini&op=Editar&deptos=$rg[departamento]'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href='$uLink?busca=ini&deptos=$rg[departamento]&pagina=$_REQUEST[pagina]'><i class='fa fa-bars fa-2x' aria-hidden='true'></i></a></td>";

        Display($aCps, $aDat, $rg);

        echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[departamento]?','$_SERVER[PHP_SELF]?Id=$rg[departamento]&op=Elim');><i class='fa fa-trash fa-2x' aria-hidden='true'></i> </a></td>";

        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    echo '<table border="0" aling="center">';
        echo '<tr>';
            echo "<td align='center'><a class='edit' href=javascript:winuni('logdepto.php')><i class='fa fa-file-code-o fa-1x' aria-hidden='true'></i><font size='1'> Log Departamento </font></a></td>";
        echo '</tr>';
    echo '</table>';

    PonPaginacionesp(true);           #-------------------pon los No.de paginas-------------------    

    $sql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE departamento=$_REQUEST[deptos]";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($op=='Editar' or $busca=='nuevo') {
        ?>
        <br />
        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#5499C7' style='border-collapse: collapse; border: 1px solid #999;'>  
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <tr>
                <td>
                    <a class="Inpt" style="color:#F1F1F1;"><b>Id : </b><?= $sql[departamento] ?> </a>
                    &nbsp; 
                    <?php
                    if ($busca=='nuevo') {
                    ?>
                        <a class="Inpt" style="color:#F1F1F1;"><b>Nombre: </b>
                            <input type='text' style="width: 250px;text-align: left;" class='cinput' autofocus name='Nombre' value='<?= $sql[nombre] ?>'>
                        </a>
                    <?php
                    }else{
                    ?>
                        <a class="Inpt" style="color:#F1F1F1;"><b>Nombre: </b>
                            <input type='text' style="width: 250px;text-align: left;" class='cinput'  name='Nombre' value='<?= $sql[nombre] ?>'>
                        </a>
                    <?php
                    }
                    ?>

                    &nbsp; 
                    <?php
                    if ($busca=='nuevo') {
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
                    <input type="hidden" value="<?= $_REQUEST[pagina] ?>" name="pagina"></input>
                    <input type="hidden" value="<?= $_REQUEST[op] ?>" name="op"></input>
                    <input type="hidden" value="<?= $_REQUEST[deptos] ?>" name="deptos"></input>
                </td>
            </tr>
            </form>
        </table>
    <?php }
    ?>
    <a class="cMsj">
        <?= $msj ?>
    </a>
    <?php
    CuadroInferior4($busca);
    echo '</body>';
    ?>

</html>
<?php
mysql_close();
?>
