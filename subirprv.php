<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
require ("config.php");          //Parametros de colores;

$link = conectarse();

$route = "doctosprv/";

$route = $route . basename($_FILES['file']['name']);
$archivos = basename($_FILES['file']['name']);

//Obtenemos algunos datos necesarios sobre el archivo
$tipo = $_FILES['file']['type'];
$tamano = $_FILES['file']['size'];
$temp = $_FILES['file']['tmp_name'];

    if (move_uploaded_file($_FILES['file']['tmp_name'], $route)) {
        $busca = $_COOKIE['NOOT'];
        $Usr2 = $_COOKIE['USERNAME'];
        $Fechasub = date("Y-m-d H:i:s");
        $cNombreFile = $archivos;
        $sql = "INSERT INTO doctosprv (id,archivo,usr,fechasub) VALUES ('$busca','$cNombreFile','$Usr2','$Fechasub')";
        if(!mysql_query($sql)){
            error_log("Error Sql " . $sql);
        }else{
            error_log("Exito en descarga");
        }
    }else{
        error_log("El archivo " . $_FILES['file']['tmp_name'] . " hablar con soporte");
    }

?>