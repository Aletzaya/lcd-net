<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
// Conexion a la base de datos
require_once('bdd.php');
require("lib/lib.php");

$id = $_POST['Event'][0];
$start = $_POST['Event'][1];
$Gusr = $_SESSION["Usr"][0];
$Fecha = date("Y-m-d H:i:s");

$sql = "UPDATE calendario SET inicia = '$start' WHERE id = $id ";
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
    $Msj = "Cambio de fecha con exito";
    AgregaAgendaEventos2($Gusr, '/Agenda/Cambio de fecha Evento ', "calendario", $Fecha, "$id", $Msj, "calendarioV2.php");
    die('OK');
}
?>
<?php
mysql_close();
?>
