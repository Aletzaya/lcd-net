<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
// Conexion a la base de datos
require_once('bdd.php');


$id = $_POST['Event'][0];
$start = $_POST['Event'][1];
$end = $_POST['Event'][2];
$Fecha = date("Y-m-d H:i:s");
$Gusr = $_SESSION["Usr"][0];

$sql = "UPDATE fechas_equipos SET fecha = '$start' WHERE id = $id ";
error_log($sql);

$query = $bdd->prepare($sql);
if ($query == false) {
    print_r($bdd->errorInfo());
    die('Error');
}
$sth = $query->execute();
if ($sth == false) {
    print_r($query->errorInfo());
    die('Error');
} else {

    $sqlA = "SELECT id_equipo FROM fechas_equipos WHERE id=$id limit 1;";
    $sqlB=mysql_query($sqlA);
    $eqpos = mysql_fetch_array($sqlB);
    $eqpo=$eqpos[id_equipo];

    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Admin/Equipos/Fecha de Mantto. Actualizada','f_equipos','$Fecha','$eqpo')";
    mysql_query($sql);
    
    die('OK');
}
?>
