<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

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

require("lib/lib.php");
$link = conectarse();
$Titulo = "Menu de inicio";
require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>..:: Messenger ::..</title>

        <?php require ("./config_add.php"); ?>
        <link href = "css/estilo_nube.css" rel = "stylesheet" type = "text/css"/>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?> 

        <?php include_once ("./messengerByFacebook.php"); ?>
        <a target="_blanck"href="https://api.whatsapp.com/send?phone=5562558518&text=Hola!&nbsp;Como&nbsp;estas?"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
    </body>
    <script src="./controladores.js"></script>
</html>