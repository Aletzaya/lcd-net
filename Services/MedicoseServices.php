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
$Return = "medicose.php";
$Msj = $_REQUEST["Msj"];

if ($_REQUEST["bt"] == "NUEVO") {
    $sql = "SELECT medico FROM med;";
    $cSQL = mysql_query($sql);
    $repetido = TRUE;
    while ($Rs = mysql_fetch_array($cSQL)) {
        if ($Rs["medico"] === $_REQUEST["Medico"]) {
            $Msj = "Medico " . $_REQUEST["Medico"] . " ya esta ingresado en el sistema verificar bien su nueva clave.";
            header("Location: medicose.php?busca=NUEVO&Msj=$Msj&Error=SI");
            $repetido = FALSE;
        }
    }
    if ($repetido) {
        $nombrec = $_REQUEST["Apellidop"] . " " . $_REQUEST["Apellidom"] . " " . $_REQUEST["Nombre"];
        $sql = "INSERT INTO med (apellidop,apellidom,nombre,nombrec,fechaa,fechanac,rfc,cedula,"
                . "especialidad,subespecialidad,medico,buscador,estado,munconsultorio,locconsultorio,"
                . "codigo,dirconsultorio,telconsultorio,telcelular,mail,refubicacion,usr,status,servicio,observaciones,"
                . "ruta,m01,m02,m03,m04,m05,m06,m07,m08,m09,m10,m11,m12,institucionp,edocons,codigosis,"
                . "fecmod,usrmod,clasificacion,promotorasig) "
                . "VALUES ('$_REQUEST[Apellidop]','$_REQUEST[Apellidom]','$_REQUEST[Nombre]','$nombrec','$Fechamod',"
                . "'$_REQUEST[Fechanac]','$_REQUEST[Rfc]','$_REQUEST[Cedula]','$_REQUEST[Especialidad]',"
                . "'$_REQUEST[Subespecialidad]','$_REQUEST[Medico]','','$_REQUEST[Estado]','$_REQUEST[Munconsultorio]',"
                . "'$_REQUEST[Locconsultorio]','$_REQUEST[Codigo]','$_REQUEST[Dirconsultorio]',"
                . "'$_REQUEST[Telconsultorio]','$_REQUEST[Telcelular]','$_REQUEST[Mail]','$_REQUEST[Refubicacion]',"
                . "'$Gusr','Activo','','',0,0,0,0,0,0,0,0,0,0,0,0,0,'','','',now(),'','','');";

        if (mysql_query($sql)) {
            $Msj = "¡Registro ingresado con exito!";
            $cId = mysql_insert_id();
            $bs = $cId . " " . $nombrec . "" . $_REQUEST["Medico"];
            $sql = "UPDATE med SET buscador='$bs' WHERE id=$cId";
            if (mysql_query($sql)) {
                AgregaBitacoraEventos($Gusr, "/Catalogos/Medicos/Info. Personal Agrega nuevo registro", "med", $Fecha, $cId, $Msj, $Return);
            } else {
                $msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
                header("Location: medicose.php?busca=NUEVO&Msj=$msj&Error=SI");
            }
        } else {
            $msj = "Error en sintaxis MYSQL : $sql ->" . mysql_error();
            header("Location: medicose.php?busca=NUEVO&Msj=$msj&Error=SI");
        }
    }
} elseif ($_REQUEST["bt"] == "Actualizar") {
    $nombrec = $_REQUEST["Apellidop"] . " " . $_REQUEST["Apellidom"] . " " . $_REQUEST["Nombre"];
    $sql = "UPDATE med SET apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',nombre='$_REQUEST[Nombre]'"
            . ",fecmod='$Fechamod',fechanac='$_REQUEST[Fechanac]',rfc='$_REQUEST[Rfc]',cedula='$_REQUEST[Cedula]'"
            . ",especialidad='$_REQUEST[Especialidad]',subespecialidad='$_REQUEST[Subespecialidad]',nombrec='$nombrec'"
            . ",medico='$_REQUEST[Medico]',estado='$_REQUEST[Estado]',munconsultorio='$_REQUEST[Munconsultorio]',"
            . "locconsultorio='$_REQUEST[Locconsultorio]',codigo='$_REQUEST[Codigo]',"
            . "dirconsultorio='$_REQUEST[Dirconsultorio]',telconsultorio='$_REQUEST[Telconsultorio]',"
            . "telcelular='$_REQUEST[Telcelular]',mail='$_REQUEST[Mail]',refubicacion='$_REQUEST[Refubicacion]',"
            . "usrmod='$Gusr' WHERE id=$busca";
    if (mysql_query($sql)) {
        $Msj = "Registro actualizado con Exito!";
        AgregaBitacoraEventos($Gusr, "/Catalogos/Medicos/Info. Personal Edita detalle", "med", $Fecha, $busca, $Msj, $Return);
    } else {
        $msj = mysql_error();
        header("Location: medicose.php?busca=$busca&Msj=$Msj&Error=SI");
    }
}

$CpoA = mysql_query("SELECT * FROM med WHERE id = '$busca'");
$Cpo = mysql_fetch_array($CpoA);
if ($_REQUEST["Estado"] <> '') {
    $Estado = $_REQUEST["Estado"];
    $Apellidop = $_REQUEST["Apellidop"];
    $Apellidom = $_REQUEST["Apellidom"];
    $Nombre = $_REQUEST["Nombre"];
    $Medico = $_REQUEST["Medico"];
    $Fechanac = $_REQUEST["Fechanac"];
    $Dirconsultorio = $_REQUEST["Dirconsultorio"];
    $Telconsultorio = $_REQUEST["Telconsultorio"];
    $Telcelular = $_REQUEST["Telcelular"];
    $Mail = $_REQUEST["Mail"];
    $Refubicacion = $_REQUEST["Refubicacion"];
    $Cedula = $_REQUEST["Cedula"];
    $Rfc = $_REQUEST["Rfc"];
    $Especialidad = $_REQUEST["Especialidad"];
    $Subespecialidad = $_REQUEST["Subespecialidad"];
    if ($busca == "NUEVO") {
        $busca = "NUEVO";
    } else {
        $busca = $busca;
    }
} else {
    $Estado = $Cpo["estado"];
    $Apellidop = $Cpo["apellidop"];
    $Apellidom = $Cpo["apellidom"];
    $Nombre = $Cpo["nombre"];
    $Medico = $Cpo["medico"];
    $Fechanac = $Cpo["fechanac"];
    $Dirconsultorio = $Cpo["dirconsultorio"];
    $Telconsultorio = $Cpo["telconsultorio"];
    $Telcelular = $Cpo["telcelular"];
    $Mail = $Cpo["mail"];
    $Refubicacion = $Cpo["refubicacion"];
    $Rfc = $Cpo["rfc"];
    $Cedula = $Cpo["cedula"];
    $Especialidad = $Cpo["especialidad"];
    $Subespecialidad = $Cpo["subespecialidad"];
}