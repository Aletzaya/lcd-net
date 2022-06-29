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

require_once ("softcoatl/SoftcoatlHTTP.php");
require_once ("Pagos10Factory.php");
require_once ("Pagos20Factory.php");

use \detifac\IConnection;

class PagosFactory {

    public static function getFactory($folio) {
        $sql = "SELECT IFNULL( F.version, P.version ) Version "
            . "FROM pagosfac T JOIN proveedor_pac P ON P.activo = 1 "
            . "LEFT JOIN facturas F ON F.uuid = T.uuid WHERE T.id = " . $folio;
        $mysqlConnection = IConnection::getConnection();
        error_log($sql);
        if (($query = $mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {

            $version = $rs['Version'];
            switch ($version) {
                case "4.0":
                    return new Pagos20Factory();
                default:
                    return new Pagos10Factory();
            }
        }
        return null;
    }
}
