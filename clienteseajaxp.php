<?php

session_start();
require("lib/lib.php");
$link = conectarse();
$search = $_POST['search'];

if (!empty($search)) {
    $query = "SELECT * FROM med WHERE buscador LIKE ('%$search%');";

    $cc = mysql_query($query);
    if (!$cc) {
        die('Query Error' . mysql_error());
    }
    $json = array();
    while ($sql = mysql_fetch_array($cc)) {
        $json[] = array(
            'buscador' => $sql[buscador]
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
if ($_REQUEST[op] == "dts") {
    $query = "INSERT INTO med (nombre,apellidop) VALUES ('$_POST[nombre]','$_POST[apellidop]');";
    if (!mysql_query($query)) {
        die('Query Error' . mysql_error());
    } else {
        echo "EXITO";
    }
}
if ($_REQUEST[op] == "ini") {
    $query = "SELECT * FROM med ORDER BY id DESC LIMIT 5;";
    $cc = mysql_query($query);
    if (!$cc) {
        die('Query Error' . mysql_error());
    }
    $json = array();
    while ($sql = mysql_fetch_array($cc)) {
        $json[] = array(
            'id' => $sql[id],
            'nombre' => $sql[nombre],
            'apellidop' => $sql[apellidop]
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
if ($_REQUEST[op] == "dt") {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM med WHERE id = $id";
        if(!$rs = mysql_query($query)){
            die('Query Error' . mysql_error());
        }
        echo "Registro Eliminado con exito";
    }
}
if($_REQUEST[op] == "edt"){
    $id = $_POST['id'];
    $query = "SELECT * FROM med  WHERE id = $id";
    if(!$rs = mysql_query($query)){
        die('Query Error' . mysql_error());
    }
    $json = array();
    while($row = mysql_fetch_array($rs)){
        $json[] = array(
            'id' => $row[id],
            'nombre' => $row['nombre'],
            'apellidop' => $row['apellidop']
        );
    }
    $jsonstring = json_encode($json[0]);
    
    echo $jsonstring;
}
if($_REQUEST[op] == "edit"){
    echo "ENTRO";
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellidop = $_POST['apellidop'];
    $query = "UPDATE med SET nombre='$nombre',apellidop='$apellidop' WHERE id = $id;";
    if(!$rs = mysql_query($query)){
        die('Query Error' . mysql_error());
    }
    echo "TAREA EXITOSA";
}
?>