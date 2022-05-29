<?php

session_start();
require("lib/lib.php");
$link = conectarse();
$Gusr = $_SESSION[Usr][0];
$Vta = $_SESSION[cVarVal][0];
$str = $_GET['q'];
$k = $_GET['k'];
$sql = "SELECT id,nombre FROM emp WHERE nombre LIKE ('%$str%');";
$resultado = mysql_query($sql);
error_log($sql);
$datos = array();
$n = 1;
while ($row = mysql_fetch_array($resultado)) {
    $datos[$n] = $row['id'] . " " . $row['nombre'];
    $n++;
}

echo json_encode($datos);
