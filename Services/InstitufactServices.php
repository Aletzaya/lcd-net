<?php

$Tabla = "instfact";

if ($_REQUEST["Boton"] === "Actualiza") {
    $Sql = "UPDATE $Tabla SET fecha = '" . $_REQUEST["Fecha"] . "',factrem = '" . $_REQUEST["Factrem"] . "',"
            . "documento = '" . $_REQUEST["Documento"] . "', c_iva = '" . $_REQUEST["C_iva"] . "',"
            . "tpago='" . $_REQUEST["Tpago"] . "',status='" . $_REQUEST["Status"] . "',doctopago='" . $_REQUEST["Doctopago"] . "',"
            . "observaciones = '" . $_REQUEST["Observaciones"] . "' WHERE id = " . $_REQUEST["buscador"];
    //echo $Sql;
    if (mysql_query($Sql)) {
        $Msj = "Exito al acutalizar registro";
    }
} else if ($_REQUEST["Boton"] === "Nuevo") {
    $Sql = "INSERT INTO " . $Tabla . " (fecha,factrem,documento,c_iva,tpago,status,doctopago,observaciones,inst) "
            . "VALUES ('" . $_REQUEST["Fecha"] . "','" . $_REQUEST["Factrem"] . "','" . $_REQUEST["Documento"] . "',"
            . "'" . $_REQUEST["C_iva"] . "','" . $_REQUEST["Tpago"] . "','" . $_REQUEST["Status"] . "',"
            . "'" . $_REQUEST["Doctopago"] . "','" . $_REQUEST["Observaciones"] . "','" . $_REQUEST["busca"] . "');";

    if (mysql_query($Sql)) {
        $Msj = "Exito al agregar registro";
    }
}
