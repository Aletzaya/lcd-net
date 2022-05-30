<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

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

        $_SESSION["OnToy"] = array('', '', 'reganatomica.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
    }
}else{
    if ($_REQUEST[bt] == Cancelar) {

        $Pos = strrpos($_REQUEST[Ret], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST[Ret] . '&';
        } else {
            if ($_REQUEST[Ret] <> '') {
                $Retornar = $_REQUEST[Ret] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'reganatomica.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
if ($_REQUEST[bt] == Cancelar) {
    $op = '';
}else{
    $op = $_REQUEST[op];
}

#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$RetSelec = $_SESSION[OnToy][4];
$Id = 77;
$Cat='reganatomica';
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

//Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION[OnToy][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
//echo "El valor de retornar es $RetSelec";
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$date = date("Y-m-d H:i:s");
$Msj = $_REQUEST["Msj"];

if ($_REQUEST[bt] == "Actualizar") {

    $Sql = "UPDATE reganatomica SET descripcion='$_REQUEST[Descripcion]' WHERE id = $op";

    if (mysql_query($Sql)) {

        $Msj = "Registro ".$op." Actualizado con Exito";
        AgregaBitacoraEventos($Gusr, '/Catalogos/Reganatomica/Registro Actualizado', "reganatomica", $date, $op, $Msj, "reganatomica.php");

    }

    header("Location: reganatomica.php?busca=ini&pagina=$_REQUEST[pagina]&Msj=$Msj");

}elseif($_REQUEST[bt] == "Nuevo") {

    $sql = "INSERT INTO reganatomica (descripcion) VALUES ('$_REQUEST[Descripcion]');";

    if (mysql_query($sql)) {
        $Id = mysql_insert_id();
        $Msj = "¡Registro ingresado con exito!";
        AgregaBitacoraEventos($Gusr, '/Catalogos/Reganatomica/Alta de Registro', "reganatomica", $date, $Id, $Msj, "reganatomica.php");

        header("Location: reganatomica.php?busca=ini&pagina=$_REQUEST[pagina]&Msj=$Msj");

    }else{
            
        echo "<div align='center'>$sql " . mysql_error() . "</div>";
        $Archivo = 'reganatomica';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');

    }

}

if ($_REQUEST[op] == "Si") {

    $Sql = "DELETE FROM reganatomica WHERE id = '$_REQUEST[cId]' limit 1;";

    if (mysql_query($Sql)) {

        $Msj = "Registro ".$_REQUEST[cId]." Eliminado con Exito";
        AgregaBitacoraEventos($Gusr, '/Catalogos/Reganatomica/Registro Eliminado', "reganatomica", $date, $_REQUEST[cId], $Msj, "reganatomica.php");

    }

    header("Location: reganatomica.php?busca=ini&pagina=$_REQUEST[pagina]&Msj=$Msj");

}
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);


$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
 $P=str_word_count($busca,1);          //Metelas en un arreglo
 for ($i = 0; $i < $Palabras; $i++) {
        if(!isset($BusInt)){$BusInt=" reganatomica.descripcion like '%$P[$i]%' ";}else{$BusInt=$BusInt." and reganatomica.descripcion like '%$P[$i]%' ";}
 }
 //$Suc='*';

}else{
        $BusInt=" reganatomica.descripcion like '%$busca%' ";  
// $Suc='*';
}



#Armo el query segun los campos tomados de qrys;


if( $busca == '' or $_REQUEST[busca] == 'NUEVO'){

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE id >= 0"; 

}elseif( $busca < 'a'){

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE id='$busca'";

}else{
    
    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt";

}


$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Catalogo de Regiones Anatomicas ::..</title>
        <?php require ("./config_add.php"); ?>
    </head>

    <?php
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu,$Gusr);
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

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
/*
    if ($busca <> '') {
        $sql = $cSql . " AND " . $orden . " LIKE ('%" . $busca . "%') ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
    } else {
        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
    }
*/

    $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;

    $res = mysql_query($sql);

    $Pos = strrpos($_SERVER[PHP_SELF], ".");
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;
        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?op=$rg[id]&pagina=$_REQUEST[pagina]'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";


        Display($aCps, $aDat, $rg);

            
        echo "<td align='center'><a class='elim' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[id]?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');><i class='fa fa-trash fa-2x' aria-hidden='true'></i> </a></td>";


        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    PonPaginacionesp(true);           #-------------------pon los No.de paginas-------------------    


    $sql = "SELECT * FROM reganatomica WHERE id=$op";

    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);

    if ($op <> '' and $_REQUEST[busca] <> 'NUEVO') {
    ?>
        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 0px solid #999;'>  
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <tr style="background-color: #A9CCE3">
                    <td>
                        &nbsp <a class="Inpt"><b> Id: <?= $sql[id] ?> </b></a> &nbsp
                        &nbsp <a class="Inpt"><b> Descripion: </b> &nbsp
                            <input type='text' class='cinput' style='width: 250px' name='Descripcion' value='<?= $sql[descripcion] ?>'>
                        </a> &nbsp
                        &nbsp <input class="letrap" type="submit" value='Actualizar' name='bt'></input> &nbsp 
                        &nbsp <input class="letrap" type="submit" value='Cancelar' name='bt'></input> &nbsp 
                        <input type="hidden" value="<?= $_REQUEST[pagina] ?>" name="pagina"></input>
                        <input type="hidden" value="<?= $op ?>" name="op"></input>
                    </td>
                </tr>
            </form>
        </table>

    <?php
    }

    if ($busca == NUEVO) {
    ?>
        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 0px solid #999;'>  
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <tr style="background-color: #A9CCE3">
                    <td>
                        &nbsp <a class="Inpt"><b> Id: Nuevo </b></a> &nbsp
                        &nbsp <a class="Inpt"><b> Descripion: </b> &nbsp
                            <input type='text' class='cinput' style='width: 250px' name='Descripcion' value='<?= $sql[descripcion] ?>'>
                        </a> &nbsp
                        &nbsp <input class="letrap" type="submit" value='Nuevo' name='bt'></input> &nbsp 
                        &nbsp <input class="letrap" type="submit" value='Cancelar' name='bt'></input> &nbsp 
                        <input type="hidden" value="<?= $_REQUEST[pagina] ?>" name="pagina"></input>
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

