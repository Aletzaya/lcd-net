<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
require ("config.php");          //Parametros de colores;

$link = conectarse();
$Gusr = $_SESSION["Usr"][0];

$route = "../lcd-net/XmlInvl/";
error_log(print_r($_FILES['file']['name'], true));
$route = $route . basename($_FILES['file']['name']);
$archivos = basename($_FILES['file']['name']);

//Obtenemos algunos datos necesarios sobre el archivo
$tipo = $_FILES['file']['type'];
$tamano = $_FILES['file']['size'];
$temp = $_FILES['file']['tmp_name'];
$IdXml = "";
if (move_uploaded_file($_FILES['file']['tmp_name'], $route)) {
    $nombreA = "/home/u938386532/public_html/lcd-net/XmlInvl/" . $_FILES["file"]["name"];
    error_log("Cargando1 $nombreA");
    $carga_xml = simplexml_load_file($nombreA); //Obtenemos los datos del xml agregados
    error_log(print_r($carga_xml, true));
    $ns = $carga_xml->getNamespaces(true);
    $carga_xml->registerXPathNamespace('c', $ns['cfdi']);
    $carga_xml->registerXPathNamespace('t', $ns['tfd']);
    error_log("HOLAAA");
    mysql_query("truncate table InvlXml;");
    mysql_query("truncate table InvlXmld;");
    error_log("ANTES DE ENTRAR" . mysql_error());
    error_log(print_r($carga_xml->xpath('//cfdi:Comprobante'), true));
    foreach ($carga_xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {

        $Folio = $cfdiComprobante['Folio'];
        $Importe = $cfdiComprobante['Total'];
        $SubTotal = $cfdiComprobante['SubTotal'];
        $Insert = "INSERT INTO InvlXml (folioXml,Total,SubTotal,fecha,usr,nombreArchivo) "
                . "VALUES ('$Folio','$Importe','$SubTotal',now(),'$Gusr','" . $_FILES["file"]["name"] . "');";
        error_log($Insert);
        if (mysql_query($Insert)) {
            $IdXml = mysql_insert_id();
        }
        error_log(mysql_error());
    }
    if (is_numeric($IdXml)) {
        foreach ($carga_xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto) {

            $Select = "SELECT idInvl FROM invlClaves WHERE descripcion = '" . $Concepto["Descripcion"] . "';";
            error_log($Select);
            $SelectMysql = mysql_query($Select);
            $Cc = mysql_fetch_array($SelectMysql);
            $IdInvlNvo = is_numeric($Cc["idInvl"]) ? $Cc["idInvl"] : 9999;
            $InsertDetalle = "INSERT INTO InvlXmld (id_xml_fk,id_producto,descripcion,cantidad,unidad,importe,valorUnitario) "
                    . "VALUES ('$IdXml','" . $IdInvlNvo . "','" . $Concepto["Descripcion"] . "','" . $Concepto['Cantidad'] . "',"
                    . "'" . $Concepto['Unidad'] . "','" . $Concepto["Importe"] . "','" . $Concepto["ValorUnitario"] . "');";
            mysql_query($InsertDetalle);
        }
    }
} else {
    error_log("El archivo " . $_FILES['file']['tmp_name'] . " hablar con soporte");
}
//}
?>