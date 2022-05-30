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

        $_SESSION["OnToy"] = array('', '', 'cli.nombrec', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
    }
}

if (isset($_REQUEST[Venta])) {
    $_SESSION['cVarVal'][0] = $_REQUEST[Venta];
}

$Vta = $_SESSION[cVarVal][0];

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
if (isset($_REQUEST[Mnu])) {
    $_SESSION['Mnu'] = $_REQUEST[Mnu];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];

$Mnu = $_SESSION[Mnu];


$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

$RetSelec = $_SESSION[OnToy][4];                              //Pagina a la que regresa con parametros        
$Retornar = "ordenesnvas.php";                                //Regresar abort  
//echo "El valor de retornar es $RetSelec";

$Usr = $_COOKIE[USERNAME];
$Nivel = $_COOKIE[LEVEL];

#Variables comunes;
$Titulo = "Menu principal";
$op = $_REQUEST[op];
$Msj = $_REQUEST[Msj];
$Id = 5;             //Numero de query dentro de la base de datos
//
//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";
//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";
#Intruccion a realizar si es que mandan algun proceso
if ($op == 'Si') {                    //Elimina rg

    /*
      $ExiA = mysql_query("SELECT id FROM fc WHERE cliente='$_REQUEST[cId]' limit 1");
      $Exi  = mysql_fetch_array($ExiA);

      if ($Exi[id] <> ""){
      $Msj = "No es posible eliminar el cliente, existen facturas registradas";
      }else{
      //$lUp  = mysql_query("DELETE FROM cli WHERE id='$_REQUEST[cId]' limit 1");
      $Msj  = "Registro eliminado";
      }

     */

    $lUp = mysql_query("DELETE FROM cli WHERE id='$_REQUEST[cId]' limit 1");
    $Msj = "Registro eliminado";
    //$Msj = "Opcion deshabilitada";
} elseif ($op == 'rs') {
    $Up = mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id");
    $op = '';
}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 5) {
    $Dsp = 'Filtro activo';
}

$Palabras = str_word_count($busca);  //Dame el numero de palabras
if ($Palabras > 1) {
    $P = str_word_count($busca, 1);          //Metelas en un arreglo
    for ($i = 0; $i < $Palabras; $i++) {
        if (!isset($BusInt)) {
            $BusInt = " $OrdenDef LIKE  '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " AND $OrdenDef like '%$P[$i]%' ";
        }
    }
} else {
    $BusInt = $OrdenDef . " LIKE '%" . $busca . "%'";
}


#Armo el query segun los campos tomados de qrys;
//$cSql   = "SELECT $Qry[campos]  FROM $Qry[froms] LEFT JOIN inst ON cli.institucion=inst.institucion
//           WHERE $BusInt $Qry[filtro]";
require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Documento sin título</title>
        <link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>

    <?php
    echo '<body topmargin="1">';

    encabezados();

    PonTitulo($Titulo);

    menu($Mnu,$Gusr);
    
    ?>
    <script src="./controladores.js"></script>
    <?php
    echo "<p>&nbsp;</p>";

    echo '<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">';
    echo '<tr>';
    echo '<td class="content2" alignt="center">';

    if ($_REQUEST[op] == '1') {

        $Otd = mysql_query("SELECT descuento,razon FROM otdnvas WHERE estudio='$_REQUEST[Estudio]' and usr='$Gusr' AND venta='$Vta'");
        $Ot = mysql_fetch_array($Otd);

        echo '<form id="form1" name="form1" method="get" action="ordenesnvas.php">';

        $EstA = mysql_query("SELECT estudio,descripcion FROM est WHERE estudio='$_REQUEST[Estudio]'");
        $Est = mysql_fetch_array($EstA);

        echo "<table width='45%' border='0' style='border-collapse: collapse; border: 1px solid #999;' align='center'><tr><td bgcolor='#2c8e3c' class='letratitulo' align='center'><a class='sbmenu'>% Descuento - $Est[estudio] $Est[descripcion]</a></td></tr><tr><td align='center'>";

        echo "<div class='content1'><span class='content1'> &nbsp Razon: </span></div>";
        echo "<div><span class='content1'><input name='Razon' type='text' size='60' class='content5' id='Num.Paciente3' value='$Ot[razon]' /></span></div>";
            
        echo "<br />";

        echo "<div class='content1'><span class='content1'> &nbsp Porcentaje: </span></div>";
        echo "<div class='content1'><span class='content1'><input name='Descuento' type='number' class='content5' style='width:70px;' id='Num.Paciente3' step='0.0001' value='$Ot[descuento]' /></span></div>";

        echo "<input type='hidden' name='IdEstudio' value='$_REQUEST[Estudio]'>";
        echo "<input type='hidden' name='op' value='desc1'>";

        Botones();
        echo "</td></tr></table>";
        echo "</form>";
    } elseif ($_REQUEST[op] == '9') {

        $Otd = mysql_query("SELECT descuento FROM otnvas WHERE usr='$Gusr' AND venta='$Vta'");
        $Ot = mysql_fetch_array($Otd);

        echo '<form id="form1" name="form1" method="get" action="ordenesnvas.php">';

        echo "<table width='35%' border='0' style='border-collapse: collapse; border: 1px solid #999;' align='center'><tr><td bgcolor='#2c8e3c' class='letratitulo' align='center'><a class='sbmenu'>% Descuento General</a></td></tr><tr><td align='center'>";

        echo "<div class='content1'><span class='content1'> &nbsp Razon: </span></div>";
        echo "<div><span class='content1'><input name='Razon' type='text' size='60' class='content5' id='Num.Paciente3' value='$Ot[descuento]' /></span></div>";

        echo "<br />";

        echo "<div class='content1'><span class='content1'> &nbsp Porcentaje: </span></div>";
        echo "<div class='content1'><span class='content1'><input name='Descuento' type='number' class='content5' style='width:70px;' id='Num.Paciente3' step='0.0001'></div>";

        echo "<input type='hidden' name='op' value='desc9'>";

        Botones();
        echo "</td></tr></table>";
        echo "</form>";
    }

    echo "</td></tr></table>";

    echo '</body>';
    ?>
<?php
mysql_close();
?>

