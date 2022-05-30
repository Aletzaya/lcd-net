<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
require ("config.php");          //Parametros de colores;

$link = conectarse();

$route = "../lcd-net/fotoeqp/";

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

    $cSql = "SELECT * from equipos_img where id_equipo='$busca'";
    $CpoA = mysql_query($cSql, $link);
    $Cpo = mysql_fetch_array($CpoA);

    if($Cpo[id_equipo]==$busca){

        $archivo = $Cpo[nombre_archivo];
        unlink("../lcd-net/fotoeqp/$archivo");
        
        $sql = "UPDATE equipos_img set nombre_archivo='$archivos' where id_equipo='$busca' limit 1;";
        if (!mysql_query($sql)) {
            error_log("Error Sql " . $sql);
        } else {
            error_log("Exito en descarga");
            $Msj = "¡Foto Equipo Cargado con exito!";
            AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Foto Equipo Cargado", "eqp", $Fecha, $busca, $Msj, "equipose.php");
        }

    }else{

        $sql = "INSERT INTO equipos_img (id_equipo,nombre_archivo) VALUES ('$busca','$archivos')";
        if (!mysql_query($sql)) {
            error_log("Error Sql " . $sql);
        } else {
            error_log("Exito en descarga");
            $Msj = "¡Foto Equipo Cargado con exito!";
            AgregaBitacoraEventos($Gusr, "/Admin/Equipos/Foto Equipo Cargado", "eqp", $Fecha, $busca, $Msj, "equipose.php");
        }

    }

} else {
    error_log("El archivo " . $_FILES['file']['tmp_name'] . " hablar con soporte");
}
//}
?>