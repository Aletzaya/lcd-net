<?php
session_start();
require("lib/lib.php");

$link = conectarse();
$str = $_GET['q'];
$Op = $_GET['op'];

if ($Op == "paciente") {
    $sql = "SELECT id,nombrec FROM cli WHERE nombrec LIKE ('%$str%');";
    $cSql = mysql_query($sql);
    $datos = array();
    while ($row = mysql_fetch_array($cSql)) {
        $datos[] = $row['nombrec'];
    }
}

if ($Op == "med") {
    $sql = "SELECT nombrec FROM med WHERE nombrec LIKE ('%$str%');";
    error_log($sql);
    $cSql = mysql_query($sql);
    $datos = array();
    while ($row = mysql_fetch_array($cSql)) {
        $datos[] = $row['nombrec'];
    }
}
echo json_encode($datos);
