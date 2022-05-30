<?php
#Librerias
session_start();
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

if ($_REQUEST[op] == 'st') {

    $NumCia = $_SESSION[CIA];

    $CpoA = mysql_query("SELECT nombre FROM cia WHERE id='$NumCia'");
    $Cpo = mysql_fetch_array($CpoA);

    $NombreCia = $Cpo[nombre];

    $_SESSION["Usr"] = array($_COOKIE[USERNAME], $_COOKIE[CIA], $NombreCia, $check['level'], $check['team'], "", "Admon");
    //0=Usr,1=Sucursal,2=Nombre de la cia,3=Nivel,Grupo o sucursal

    $_SESSION[cVarVal] = '';

    header("Location: menu.php");
}

if (isset($_REQUEST[Mnu])) {
    $_SESSION[Usr][5] = $_REQUEST[Mnu];
}

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

if ($_REQUEST[Mnu] == "") {
    header("Location: menu.php?Mnu=1");
}
require("lib/lib.php");
$link = conectarse();
$Titulo = "Menu de inicio";
require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>..:: MENU ::..</title>
        <link href="estilos.css?ver=1.1" rel="stylesheet" type="text/css" />
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
    <?php
    encabezados();

    menuprueba($Gmenu,$Gusr);
    ?> 
    </body>
    <script src="controladores.js"></script>
</html>
<?php
mysql_close();
?>

