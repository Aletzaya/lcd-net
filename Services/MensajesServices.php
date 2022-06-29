<?php

$Fecha = date("Y-m-d");
$Hora = date("H:m:s");
if ($_REQUEST["Para"] <> "*" AND $_REQUEST["Para"] <> "") {

    $Sql = "INSERT INTO msj (para,de,fecha,hora,titulo,nota,tipo,bd) 
                      VALUES ('$_REQUEST[Para]','$Gusr','$Fecha','$Hora','$_REQUEST[Titulo]','$_REQUEST[MensajeNuevo]','1',0)";
    if (!mysql_query($Sql)) {
        $Msj = "Error en SQL:" . $Sql;
    } else {
        $Msj = "Mensaje enviado con Exito";
    }

    if ($_REQUEST["Cc"] <> '') {
        $Sql = "INSERT INTO msj 
                         (para,de,fecha,hora,titulo,nota,tipo,bd) 
                         VALUES
                         ('$_REQUEST[Cc]','$Gusr','$Fecha','$Hora','$_REQUEST[Titulo]','Cc: $_REQUEST[MensajeNuevo]','1',0)";
        if (!mysql_query($Sql)) {
            $Msj = "Error en SQL:" . $Sql;
        } else {
            $Msj = "Mensaje enviado con Exito";
        }
    }
} else if ($_REQUEST["Para"] === "*") {

    $ParaA = mysql_query("SELECT uname FROM authuser WHERE uname<>'$Usr' AND level >='5'");
    while ($rg = mysql_fetch_array($ParaA)) {
        $Pra = $rg[0];
        $Sql = "INSERT INTO msj (para,de,fecha,hora,titulo,nota,tipo,bd) 
                         VALUES
                         ('$Pra','$Gusr','$Fecha','$Hora','$_REQUEST[Titulo]','$_REQUEST[MensajeNuevo]','1',0)";
        if (!mysql_query($Sql)) {
            $Msj = "Error en SQL:" . $Sql;
        } else {
            $Msj = "Mensaje enviado con Exito";
        }
    }
}

if (is_numeric($_REQUEST["busca"])) {
    $Sql = "UPDATE msj SET bd = 1 WHERE id = " . $_REQUEST[busca];
    if (!mysql_query($Sql)) {
        $Msj = "Error en SQL:" . $Sql;
    } else {
        $Msj = "Mensaje macado como ABIERTO";
    }
}