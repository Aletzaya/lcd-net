<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
require ("config.php");          //Parametros de colores;

$link = conectarse();

$route = "../lcd-net/imageEmp/";

$route = $route . basename($_FILES['file']['name']);
$archivos = basename($_FILES['file']['name']);

//Obtenemos algunos datos necesarios sobre el archivo
$tipo = $_FILES['file']['type'];
$tamano = $_FILES['file']['size'];
$temp = $_FILES['file']['tmp_name'];

if (move_uploaded_file($_FILES['file']['tmp_name'], $route)) {
    $busca = $_COOKIE['BuscaEmp'];
    $sql = "INSERT INTO imageEmp (id_empleado,nombreArchivo,alias) VALUES ('$busca','$archivos','')";
    error_log($sql);
    if (!mysql_query($sql)) {
        error_log("Error Sql " . $sql . " _ " . mysql_error());
    } else {
        error_log("Exito en descarga");
    }
} else {
    error_log("El archivo " . $_FILES['file']['tmp_name'] . " hablar con soporte");
}
//}
?>