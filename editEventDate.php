<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
// Conexion a la base de datos
require_once('bdd.php');

$usr = $_SESSION[Usr][0];

if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){
	
	
	$id = $_POST['Event'][0];
	$start = $_POST['Event'][1];
	$end = $_POST['Event'][2];
	//$usr = $_POST['Event'][3];

	$sql = "UPDATE events SET  start = '$start', end = '$end', usr = '$usr' WHERE id = $id ";

	
	$query = $bdd->prepare( $sql );
	if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Error');
	}
	$sth = $query->execute();
	if ($sth == false) {
	 print_r($query->errorInfo());
	 die ('Error');
	}else{
		die ('OK');
	}

}
//header('Location: '.$_SERVER['HTTP_REFERER']);
//header('Location: calendar.php');
	
mysql_close();
?>
