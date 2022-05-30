<?php
#Librerias
session_start();
/*
  if(isset($_REQUEST[Cia])){              // Si trae algo entra y asigna los valores a session;
  $_SESSION['Cia']      = $_REQUEST[Cia];
  $_SESSION['cVarVal']  = 'St';
  }
 */

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
$Mnu = 4;
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$busca=$_REQUEST[busca];
if ($busca === "Lunes") {
    $dia="lcd_ln.gz";
}elseif($busca == "Martes"){
    $dia="lcd_mr.gz";
}elseif($busca == "Miercoles"){
    $dia="lcd_mie.gz";
}elseif($busca == "Jueves"){
    $dia="lcd_jue.gz";
}elseif($busca == "Viernes"){
    $dia="lcd_vie.gz";
}elseif($busca == "Sabado"){
    $dia="lcd_sab.gz";
}elseif($busca == "Domingo"){
    $dia="lcd_dom.gz";
}

if ($_REQUEST[op] === "Descarga") {
    $dr = "/home/froylan/Backup_Service/";
    $fileName = basename($dia);
    $filePath = $dr . $fileName;
    if (!empty($fileName) && file_exists($filePath)) {
        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        headeer("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        // Read the file
        readfile($filePath);
        exit;
    } else {
        $msj= "No se encuentra Archivo";
    }
}
require("lib/lib.php");

$link = conectarse();

$Titulo = "Respaldos de LCD";

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

    <?php
    echo '<body topmargin="1">';

    encabezados();

    menu($Gmenu,$Gusr);

    echo '</body>';
    ?> 
    <br></br>
    <table width="100%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;">
        <tr align="center">
            <td colspan="7" class="letraex">Respaldos</td>
        </tr>
        <tr align="center">
            <td>
                <a href="respaldoslcd.php?busca=Lunes&op=Descarga"class="edit">Lunes <i class="fa fa-database fa-2x" style="color: #566573" aria-hidden="true"></i></a>
            </td>
            <td>
                <a href="respaldoslcd.php?busca=Martes&op=Descarga" class="edit">Martes  <i class="fa fa-database fa-2x" style="color: #566573" aria-hidden="true"></i></a>
            </td>
            <td>
                <a href="respaldoslcd.php?busca=Miercoles&op=Descarga" class="edit">Miercoles  <i class="fa fa-database fa-2x" style="color: #566573" aria-hidden="true"></i></a>
            </td>
            <td>
                <a href="respaldoslcd.php?busca=Jueves&op=Descarga" class="edit">Jueves  <i class="fa fa-database fa-2x" style="color: #566573" aria-hidden="true"></i></a>
            </td>
            <td>
                <a href="respaldoslcd.php?busca=Viernes&op=Descarga" class="edit">Viernes  <i class="fa fa-database fa-2x" style="color: #566573" aria-hidden="true"></i></a>
            </td>
            <td>
                <a href="respaldoslcd.php?busca=Sabado&op=Descarga" class="edit">Sabado <i class="fa fa-database fa-2x" style="color: #566573" aria-hidden="true"></i></a>
            </td>
            <td>
                <a href="respaldoslcd.php?busca=Domingo&op=Descarga" class="edit">Domingo <i class="fa fa-database fa-2x" style="color: #566573" aria-hidden="true"></i></a>  
            </td>
        </tr>
    </table>    
    <script src="./controladores.js"></script>
</html>