<?php

session_start();
require("lib/lib.php");
$link = conectarse();
$Gusr = $_SESSION[Usr][0];
$Vta = $_SESSION[cVarVal][0];
$str = $_GET['q'];
$k = $_GET['k'];
$sql = "SELECT id,proveedor,alias FROM prbVentaMantenimiento WHERE proveedor LIKE ('%$str%');";
$resultado = mysql_query($sql);

$datos = array();
$n = 1;
while ($row = mysql_fetch_array($resultado)) {
    $datos[$n] = $row['id'] . " " . $row['proveedor']. " " . $row['alias'];
    $n++;
}

echo json_encode($datos);
