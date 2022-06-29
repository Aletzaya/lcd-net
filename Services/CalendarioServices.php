<?php
#Librerias
session_start();
require("lib/lib.php");
require ("config.php");          //Parametros de colores;
$link = conectarse();
$Msj = $_REQUEST[Msj];
$busca = $_REQUEST[busca];

if ($_REQUEST[bt] == "Agregar") {
    $Ff = $_REQUEST[Finaliza] . " 23:59:59";
    $sql = "INSERT INTO calendario (titulo,inicia,finaliza,display,constrain) VALUES ('$_REQUEST[Titulo]','$_REQUEST[Inicia]','$Ff','$_REQUEST[Importancia]','$_REQUEST[Constrain]');";

    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis " . $sql;
    }
    $Msj = "Registro agregado con exito";
    header("Location: calendario.php?busca=ini&Msj=$Msj");
}
if ($busca === "Admi") {
    $Pb = "SELECT titulo,inicia,finaliza,display FROM calendario WHERE id >= 0 ORDER BY id DESC";
    $cCpo = mysql_query($Pb);
    While ($Cpo = mysql_fetch_array($cCpo)) {
        $Stg = $Stg . " { title : \"$Cpo[titulo]\", start : \"$Cpo[inicia]\", end : \"$Cpo[finaliza]\",color : \"$Cpo[display]\"},";
    }
    $Titulo = "Administración";
}elseif($busca === "Matris"){
    $Pb = "SELECT titulo,inicia,finaliza,display FROM calendario WHERE id >= 0 AND sucursal='1'  ORDER BY id DESC";
    $cCpo = mysql_query($Pb);
    While ($Cpo = mysql_fetch_array($cCpo)) {
        $Stg = $Stg . " { title : \"$Cpo[titulo]\", start : \"$Cpo[inicia]\", end : \"$Cpo[finaliza]\",color : \"$Cpo[display]\"},";
    }
    $Titulo = "Matriz";
}elseif($busca === "Ohf"){
    $Pb = "SELECT titulo,inicia,finaliza,display FROM calendario WHERE id >= 0 AND sucursal='2' ORDER BY id DESC";
    $cCpo = mysql_query($Pb);
    While ($Cpo = mysql_fetch_array($cCpo)) {
        $Stg = $Stg . " { title : \"$Cpo[titulo]\", start : \"$Cpo[inicia]\", end : \"$Cpo[finaliza]\",color : \"$Cpo[display]\"},";
    }
    $Titulo = "Futura";
}elseif($busca === "Tpx"){
    $Pb = "SELECT titulo,inicia,finaliza,display FROM calendario WHERE id >= 0 AND sucursal='3' ORDER BY id DESC";
    $cCpo = mysql_query($Pb);
    While ($Cpo = mysql_fetch_array($cCpo)) {
        $Stg = $Stg . " { title : \"$Cpo[titulo]\", start : \"$Cpo[inicia]\", end : \"$Cpo[finaliza]\",color : \"$Cpo[display]\"},";
    }
    $Titulo = "Tepexpan";
}elseif($busca === "Reyes"){
    $Pb = "SELECT titulo,inicia,finaliza,display FROM calendario WHERE id >= 0 AND sucursal='4' ORDER BY id DESC";
    $cCpo = mysql_query($Pb);
    While ($Cpo = mysql_fetch_array($cCpo)) {
        $Stg = $Stg . " { title : \"$Cpo[titulo]\", start : \"$Cpo[inicia]\", end : \"$Cpo[finaliza]\",color : \"$Cpo[display]\"},";
    }
    $Titulo = "Los Reyes";
}elseif($busca === "Camarones"){
    $Pb = "SELECT titulo,inicia,finaliza,display FROM calendario WHERE id >= 0 AND sucursal='5' ORDER BY id DESC";
    $cCpo = mysql_query($Pb);
    While ($Cpo = mysql_fetch_array($cCpo)) {
        $Stg = $Stg . " { title : \"$Cpo[titulo]\", start : \"$Cpo[inicia]\", end : \"$Cpo[finaliza]\",color : \"$Cpo[display]\"},";
    }
    $Titulo = "Camarones";
}elseif($busca === "Snvi"){
    $Pb = "SELECT titulo,inicia,finaliza,display FROM calendario WHERE id >= 0 AND sucursal='6' ORDER BY id DESC";
    $cCpo = mysql_query($Pb);
    While ($Cpo = mysql_fetch_array($cCpo)) {
        $Stg = $Stg . " { title : \"$Cpo[titulo]\", start : \"$Cpo[inicia]\", end : \"$Cpo[finaliza]\",color : \"$Cpo[display]\"},";
    }
    $Titulo = "San Vicente";
}
$Stg = substr($Stg, 0, -1);