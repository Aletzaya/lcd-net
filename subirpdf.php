<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
require ("config.php");          //Parametros de colores;

$link = conectarse();

$route = "../lcd-net/manualespdf/";

$route = $route . basename($_FILES['file']['name']);
$archivos = basename($_FILES['file']['name']);
$Gusr = $_SESSION["Usr"][0];
$Fecha = date("Y-m-d H:i:s");

//Obtenemos algunos datos necesarios sobre el archivo
$tipo = $_FILES['file']['type'];
$tamano = $_FILES['file']['size'];
$temp = $_FILES['file']['tmp_name'];

if (move_uploaded_file($_FILES['file']['tmp_name'], $route)) {
    $busca = $_COOKIE['BuscaEquipo'];
    $idres = $_REQUEST["idres"];
    $origen = $_REQUEST["origen"];
    
    $sql = "INSERT INTO equipos_pdf (id_equipo,nombre_archivo,origen,id_respuesta,usr,fecha) VALUES ('$busca','$archivos','$origen','$idres','$Gusr','$Fecha')";
    if (!mysql_query($sql)) {
        error_log("Error Sql " . $sql);
    } else {
        error_log("Exito en descarga");
        $Msj = "¡Archivo Guardado con exito!";
        AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Archivo Guardado", "eqp", $Fecha, $busca, $Msj, "equipose.php");
    }
} else {
    error_log("El archivo " . $_FILES['file']['tmp_name'] . " hablar con soporte");
}
//}
?>