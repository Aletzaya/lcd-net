<?php
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
// Conexion a la base de datos
require_once('bdd.php');

$usr = $_SESSION[Usr][0];

if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color']) && isset($_POST['descripcion'])){
	
	$title = $_POST['title'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$color = $_POST['color'];
	$descripcion = $_POST['descripcion'];


	$sql = "INSERT INTO events(title, start, end, color, descripcion, usr) values ('$title', '$start', '$end', '$color', '$descripcion', '$usr')";
	
	echo $sql;
	
	$query = $bdd->prepare( $sql );
	if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Erreur prepare');
	}
	$sth = $query->execute();
	if ($sth == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}

}
header('Location: '.$_SERVER['HTTP_REFERER']);

mysql_close();
?>
