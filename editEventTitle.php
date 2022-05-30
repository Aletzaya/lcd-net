<?php
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
// Conexion a la base de datos
require_once('bdd.php');

$usr = $_SESSION[Usr][0];

if (isset($_POST['delete']) && isset($_POST['id'])){
	
	
	$id = $_POST['id'];
	
	$sql = "DELETE FROM events WHERE id = $id";
	$query = $bdd->prepare( $sql );
	if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Erreur prepare');
	}
	$res = $query->execute();
	if ($res == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}
	
}elseif (isset($_POST['title']) && isset($_POST['color']) && isset($_POST['id']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['descripcion']) && isset($_POST['usr'])){
	
	$id = $_POST['id'];
	$title = $_POST['title'];
	$color = $_POST['color'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$descripcion = $_POST['descripcion'];
	//$usr = $_POST['usr'];
	
	$sql = "UPDATE events SET  title = '$title', color = '$color', start = '$start', end = '$end', descripcion = '$descripcion', usr = '$usr' WHERE id = $id ";

	
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
header('Location: calendar.php');

mysql_close();
?>
