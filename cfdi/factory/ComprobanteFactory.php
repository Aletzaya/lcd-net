<?php

/*
 * Comprobante40DAO
 * GlobalFAE®
 * © 2018, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since feb 2018
 */
namespace com\detisa\cfdi\factory;
require_once ('data/mysqlUtils.php');
//require_once ("Comprobante33Factory.php");
require_once ("Comprobante40Factory.php");


class ComprobanteFactory {

    public static function getFactory($folio, $tabla) {
        $sql = "SELECT IFNULL( F.version, P.version ) Version "
            . "FROM " . $tabla . " T JOIN proveedor_pac P ON P.activo = 1 "
            . "LEFT JOIN facturas F ON F.uuid = T.uuid WHERE T.id = " . $folio;
        $mysqlConnection =  getConnection();
//        error_log($sql);
        if (($query = $mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {

            $version = $rs['Version'];
            switch ($version) {
                case "4.0":
                    return new Comprobante40Factory();
                default:
                    return false;
            }
        }
        return null;
    }
}
