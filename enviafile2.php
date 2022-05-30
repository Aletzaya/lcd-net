<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


$busca      = $_REQUEST[busca];
$busca      = "estudios/" . $busca;

$file = $_GET['file'];
header("Content-disposition: attachment; filename=$busca");
header("Content-type: application/octet-stream");
readfile($busca);

mysql_close();
?>
