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
$Fecha = date("Y-m-d H:i:s");
$Fechamod = date("Y-m-d");
$Titulo = "Equipos Venta y Mantenimiento";
$Return = "equiposVyMe.php";
$Msj = $_REQUEST["Msj"];
$st = $_REQUEST["Status"] == "on" ? 1 : 0;
if ($_REQUEST["bt"] == "Nuevo") {
    $sql = "INSERT INTO prbVentaMantenimiento (proveedor,alias,direccion,colonia,codigop,ciudad,rfc,responsable,email,telefono,status,empresa,tpago,condiciones,tproveedor) "
            . "VALUES ('" . $_REQUEST["Proveedor"] . "','" . $_REQUEST["Alias"] . "','" . $_REQUEST["Direccion"] . "','" . $_REQUEST["Colonia"] . "'"
            . ",'" . $_REQUEST["CodigoPostal"] . "','" . $_REQUEST["Ciudad"] . "','" . $_REQUEST["Rfc"] . "','" . $_REQUEST["Responsable"] . "'"
            . ",'" . $_REQUEST["Email"] . "','" . $_REQUEST["Telefono"] . "','$st','" . $_REQUEST["Empresa"] . "','" . $_REQUEST["Tpago"] . "','" . $_REQUEST["Condiciones"] . "','" . $_REQUEST["Tproveedor"] . "');";

    if (mysql_query($sql)) {
        $Msj = "¡Registro ingresado con exito!";
        $cId = mysql_insert_id();
        AgregaBitacoraEventos($Gusr, "/Catalogos/ProveedoresVyM/Ingresa este registro", "prbVM", $Fecha, $cId, $Msj, $Return);
    } else {
        $msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
        header("Location: equiposVyMe.php?busca=NUEVO&Msj=$msj&Error=SI");
    }
} elseif ($_REQUEST["bt"] == "Actualizar") {

    $sql = "UPDATE prbVentaMantenimiento SET proveedor='" . $_REQUEST["Proveedor"] . "',alias='" . $_REQUEST["Alias"] . "',direccion='" . $_REQUEST["Direccion"] . "',"
            . "colonia='" . $_REQUEST["Colonia"] . "',codigop='" . $_REQUEST["CodigoPostal"] . "',ciudad='" . $_REQUEST["Ciudad"] . "',"
            . "rfc='" . $_REQUEST["Rfc"] . "',responsable='" . $_REQUEST["Responsable"] . "',"
            . "email='" . $_REQUEST["Email"] . "',telefono='" . $_REQUEST["Telefono"] . "',"
            . "status='" . $st . "',empresa='" . $_REQUEST["Empresa"] . "',tpago='" . $_REQUEST["Tpago"] . "',condiciones='" . $_REQUEST["Condiciones"] . "',tproveedor='" . $_REQUEST["Tproveedor"] . "' WHERE id=$busca";
    if (mysql_query($sql)) {
        $Msj = "Registro actualizado con Exito!";
        AgregaBitacoraEventos($Gusr, "/Catalogos/ProveedoresVyM/Edita datos", "prbVM", $Fecha, $busca, $Msj, $Return);
    } else {
        $msj = mysql_error() . " : " . $sql;
        header("Location: equiposVyMe.php?busca=$busca&Msj=$sql&Error=SI");
    }
}

$CpoA = mysql_query("SELECT * FROM prbVentaMantenimiento WHERE id = '$busca'");
$Cpo = mysql_fetch_array($CpoA);

if ($_REQUEST["Estado"] <> '') {
    $Proveedor = $_REQUEST["Proveedor"];
    $Alias = $_REQUEST["Alias"];
    $Direccion = $_REQUEST["Direccion"];
    $Colonia = $_REQUEST["Colonia"];
    $CodigoPostal = $_REQUEST["CodigoPostal"];
    $Ciudad = $_REQUEST["Ciudad"];
    $Responable = $_REQUEST["Responsable"];
    $Email = $_REQUEST["Email"];
    $Telefono = $_REQUEST["Telefono"];
    $Status = $_REQUEST["Status"];
    $Rfc = $_REQUEST["Rfc"];
    $Empresa = $_REQUEST["Empresa"];
    $Condiciones = $_REQUEST["Condiciones"];
    $Tpago = $_REQUEST["Tpago"];
    $Tproveedor = $_REQUEST["Tproveedor"];
    if ($busca == "NUEVO") {
        $busca = "NUEVO";
    } else {
        $busca = $busca;
    }
} else {
    $Proveedor = $Cpo["proveedor"];
    $Alias = $Cpo["alias"];
    $Direccion = $Cpo["direccion"];
    $Colonia = $Cpo["colonia"];
    $CodigoPostal = $Cpo["codigop"];
    $Ciudad = $Cpo["ciudad"];
    $Responsable = $Cpo["responsable"];
    $Email = $Cpo["email"];
    $Telefono = $Cpo["telefono"];
    $Status = $Cpo["status"];
    $Rfc = $Cpo["rfc"];
    $Empresa = $Cpo["empresa"];
    $Condiciones = $Cpo["condiciones"];
    $Tpago = $Cpo["tpago"];
    $Tproveedor = $Cpo["tproveedor"];
}