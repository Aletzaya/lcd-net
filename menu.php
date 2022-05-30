<?php
#Librerias
session_start();
/*
  if(isset($_REQUEST[Cia])){              // Si trae algo entra y asigna los valores a session;
  $_SESSION['Cia']      = $_REQUEST[Cia];
  $_SESSION['cVarVal']  = 'St';
  }
 */

date_default_timezone_set("America/Mexico_City");
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
require("lib/lib.php");
if ($_REQUEST[op] == 'st') {

    $NumCia = $_SESSION[CIA];
    $NumCias = $_COOKIE[CIA];

    $CpoA = mysql_query("SELECT nombre FROM cia WHERE id='$NumCias'");
    $Cpo = mysql_fetch_array($CpoA);

    //$NombreCia = $Cpo[nombre];

    $NombreCias = array('Administrativo', 'Unidad Texcoco Matriz', 'Unidad H.Futura', 'Unidad Tepexpan', 'Unidad Los reyes', 'Unidad Camarones', 'Unidad San Vicente');

    $NombreCia = $NombreCias[$NumCias];


    $_SESSION["Usr"] = array($_COOKIE[USERNAME], $_COOKIE[CIA], $NombreCia, $check['level'], $check['team'], "", "Admon");
    //0=Usr,1=Sucursal,2=Nombre de la cia,3=Nivel,Grupo o sucursal

    $_SESSION[cVarVal] = '';
    AgregaBitacoraEventos($_COOKIE[USERNAME], "Login exitoso!", "login", date("Y-m-d H:m:s"), 1, "Login Exitoso", "menu.php");
    //header("Location: menu.php");
}

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

$link = conectarse();
$Titulo = "Menu de inicio";
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
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        if ($Gusr === "GERARDO" || $Gusr === "FAZ" || $Gusr === "NAZARIO" || $Gusr === "SANDRA") {
            $dt = date("Y-m-d");
            $Sql = "SELECT emp.nombre,emp.cia,cia.nombre nomcia,fechas.fecha,fechas.observaciones,now() hoy,fechas.tipo,"
                    . "IF(MONTH(fechas.fecha) <= MONTH('" . $dt . "'),DAY(fechas.fecha) - DAY('" . $dt . "'),2) dias "
                    . "FROM emp LEFT JOIN cia ON emp.cia = cia.id "
                    . "LEFT JOIN fechas ON emp.id = fechas.empleado "
                    . "WHERE (MONTH(fechas.fecha) >= MONTH('" . $dt . "')) AND "
                    . "(MONTH(fechas.fecha) <= MONTH(DATE_ADD('" . $dt . "',INTERVAL 3 DAY))) AND "
                    . "(DAY(fechas.fecha) >= DAY('" . $dt . "')) ORDER BY fechas.fecha ASC LIMIT 10";
            //echo $Sql;
            $Emp = mysql_query($Sql);
            $c = 1;
            ?> 
            <table class="letrap">
                <?php
                while ($rs = mysql_fetch_array($Emp)) {

                    if ($rs["dias"] <= 3) {
                        $Hoy = "Hoy ";
                        $OtDia = "Faltan " . $rs["dias"] . " dias ";
                        $txt = $rs["dias"] == 0 ? $Hoy : $OtDia;
                        $estilo = $rs["dias"] == 0 ? 'style="color:red;" ' : "";
                        $Cumple = '<i class="fa fa-birthday-cake fa-3x" aria-hidden="true" style="color:#52BE80"></i> Cumpleaños    ';
                        $Aniv = '<i class="fa fa-star fa-3x" aria-hidden="true" style="color:#52BE80"></i> Aniversario ';
                        $Otro = '<i class="fa fa-flag fa-3x" aria-hidden="true" style="color:#52BE80"></i> Recordatorio ';
                        if ($rs["tipo"] === "Cumple") {
                            $Icono = $Cumple;
                        } else if ($rs["tipo"] === "Aniversario") {
                            $Icono = $Aniv;
                        } else {
                            $Icono = $Otro;
                        }
                        ?>
                        <tr style="height: 50px;">
                            <td <?= $estilo ?>>

                                <?= $Icono . $txt ?> <strong><?= $rs["nombre"] ?></strong> <?= $rs["fecha"] ?>
                                <?= $rs["observaciones"] ?>

                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        <?php } ?>

        <?php include_once ("./messengerByFacebook.php"); ?>
    </body>
    <script src="./controladores.js?var=1.2"></script>
</html>
<?php

function CalculaEdad($Fecha) {
    $Fcha = new DateTime($Fecha);
    $hoy = new DateTime();
    $edad1 = $hoy->diff($Fcha);
    $return = $edad1->days;
    return $return;
}

mysql_close();
