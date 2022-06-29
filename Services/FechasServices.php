<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
require ("config.php");
$link = conectarse();
$busca = $_REQUEST["busca"];

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:m:s");
$Fechamod = date("Y-m-d");
$Titulo = "Ordenes de estudio";
$Return = "fechase.php";
$Msj = $_REQUEST["Msj"];

if ($_REQUEST["Boton"] === "Agregar") {
    $emp = explode(" ", $_REQUEST["Empleado"]);
    $Sql = "INSERT INTO fechas (fecha,observaciones,empleado,tipo) "
            . "VALUES ('" . $_REQUEST["Fecha"] . "','" . $_REQUEST["Observaciones"] . "','" . $emp[0] . "','" . $_REQUEST["Tipo"] . "');";
    if (mysql_query($Sql)) {
        $Id = mysql_insert_id();
        $Bitacora = "/R.Humanos/Fechas Importantes/Agrega una nueva fecha";
        $Msj = "Registro agregado con exito!";
        AgregaBitacoraEventos($Gusr, $Bitacora, "fechas", $Fecha, $Id, $Msj, $Return);
    }
} else if ($_REQUEST["Boton"] === "Actualiza") {
    $emp = explode(" ", $_REQUEST["Empleado"]);
    $Sql = "UPDATE fechas SET fecha = '" . $_REQUEST["Fecha"] . "',observaciones='" . $_REQUEST["Observaciones"] . "',"
            . "empleado = '" . $emp[0] . "',tipo='" . $_REQUEST["Tipo"] . "' WHERE id = " . $busca;
    echo $Sql;
    if (mysql_query($Sql)) {
        $Bitacora = "/R.Humanos/Fechas Importantes/Edita";
        $Msj = "Registro actualizado con exito!";
        AgregaBitacoraEventos($Gusr, $Bitacora, "fechas", $Fecha, $busca, $Msj, $Return);
    }
}

$Sql = "SELECT fecha,fechas.observaciones,empleado,nombre,tipo FROM fechas "
        . "LEFT JOIN emp ON fechas.empleado = emp.id WHERE fechas.id = " . $busca;

$cSql = mysql_fetch_array(mysql_query($Sql));
