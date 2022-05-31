<?php

/*
 * FacturaDAO Objeto DAO.
 * Recupera la información referente a la factura con fc.id = $folio
 * Crea un objeto de tipo Comprobante y los nodos requeridos.
 * La información vaciada en Comprobante se encuentra contenida en las tablas cia, cli, fc, fcd.
 * Este módulo está escrito de acuerdo a la estructura de base de datos, reglas y definiciones del sistema Detifac®, sistema de emisión de comprobantes,
 * y cumple con las especificaciones definidas por la autoridad tributaria SAT.
 * 
 * Detisa®
 * © 2018, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since ene 2018
 */
require_once("softcoatl/SoftcoatlHTTP.php");
require_once("config/config.php");
require_once("FacturasVO.php");

use com\softcoatl\utils as utils;

class FacturasDAO {

    /**
     * 
     * @param type $uuid
     * @return \com\detisa\detifac\FacturasVO
     */
    public static function getFactura($uuid) {

        $connection = utils\IConnection::getConnection();
        $sql = "SELECT * FROM facturas WHERE uuid = '" . $uuid . "'";
        if (($rs = $connection->query($sql))
                && ($row=$rs->fetch_array())) {
            return FacturasVO::parse($row);
        } else {
            error_log("No existe en BD");
        }
        return null;
    }//getFactura

    /**
     * insertFactura Crea el registro en facturas.
     * @param String $id id del registro en fc
     * @param \cfdi33\Comprobante $Comprobante Objeto Comprobante
     * @param String XML timbrado por el SAT
     * @param String $clavePAC Clave del PAC usado para certificar el CFDI
     * @return boolean
     */
    public static function insertFactura($id, $tipo, $Comprobante, $xml, $clavePAC) {

        $connection = utils\IConnection::getConnection();
        $sql = "INSERT INTO facturas (id_fc_fk, origen, version, fecha_emision, fecha_timbrado, cfdi_xml, clave_pac, emisor, receptor, uuid)"
                . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE "
                . "cfdi_xml = VALUES( cfdi_xml ), "
                . "emisor = VALUES( emisor ), "
                . "receptor = VALUES( receptor ), "
                . "fecha_emision = VALUES( fecha_emision ), "
                . "fecha_timbrado = VALUES( fecha_timbrado )";

        if (($stmt = $connection->prepare($sql))) {
            if ($stmt->bind_param("iissssssss",
                    $id,
                    $tipo,
                    $Comprobante->getVersion(),
                    $Comprobante->getFecha(),
                    $Comprobante->getTimbreFiscalDigital()->getFechaTimbrado(),
                    $xml,
                    $clavePAC,
                    $Comprobante->getEmisor()->getRfc(),
                    $Comprobante->getReceptor()->getRfc(),
                    $Comprobante->getTimbreFiscalDigital()->getUUID())
                && $stmt->execute()) {
                    return true;
            } else {
                throw new \Exception($stmt->error);
            }
        } else {
            throw new \Exception($connection->error);
        }
    }//insertFactura

    /**
     * insertFactura Crea el registro en facturas.
     * @param \cfdi33\Comprobante $comprobante Objeto Comprobante
     * @return boolean
     */
    public static function insertComprobante($comprobante) {

        $connection = utils\IConnection::getConnection();
        $insert = "INSERT INTO facturas (id_fc_fk, origen, version, fecha_emision, fecha_timbrado, cfdi_xml, emisor, receptor, uuid)"
                . "SELECT id, LAST_INSERT_ID(tipo), ?, ?, ?, ?, ?, ?, uuid FROM ( SELECT id, tipo, uuid FROM fc WHERE uuid = ? UNION ALL SELECT id, 5, uuid FROM pagosfac WHERE uuid = ? ) T "
                . "ON DUPLICATE KEY UPDATE origen = VALUES(origen), version = VALUES(version), fecha_emision = VALUES(fecha_emision), fecha_timbrado = VALUES(fecha_timbrado), emisor = VALUES(emisor), receptor = VALUES(receptor), cfdi_xml = VALUES(cfdi_xml)";

        if (($stmt = $connection->prepare($insert))) {
            if ($stmt->bind_param("ssssssss",
                    $comprobante->getVersion(),
                    $comprobante->getFecha(),
                    $comprobante->getTimbreFiscalDigital()->getFechaTimbrado(),
                    $comprobante->asXML()->saveXML(),
                    $comprobante->getEmisor()->getRfc(),
                    $comprobante->getReceptor()->getRfc(),
                    $comprobante->getTimbreFiscalDigital()->getUUID(),
                    $comprobante->getTimbreFiscalDigital()->getUUID())
                && $stmt->execute()) {
                    return $connection->insert_id;
            } else {
                throw new \Exception($stmt->error);
            }
        } else {
            throw new \Exception($connection->error);
        }
    }//insertFactura


    /**
     * updateRelacion Actualiza el UUID de los folios relacionados
     * @param int $id
     * @param int $tipo
     * @param string $uuid
     * @return boolean
     * @throws \Exception
     */
    public static function updateRelacion($id, $tipo, $uuid) {

        $connection = utils\IConnection::getConnection();
        $sql = "UPDATE relacion_cfdi SET uuid = ? WHERE id = ? AND origen = ?";

        if (($stmt = $connection->prepare($sql))) {
            if ($stmt->bind_param("sii", $uuid, $id, $tipo)
                && $stmt->execute()) {
                    return true;
            } else {
                throw new \Exception($stmt->error);
            }
        } else {
            throw new \Exception($connection->error);
        }
    }//insertFactura

    /**
     * 
     * @param String $uuid UUID del comprobante en la tabla facturas
     * @param type $acuse XML con el acuse de cancelación
     * @return type
     */
    public static function guardaAcuse($uuid, $acuse) {

        $connection = utils\IConnection::getConnection();
        $sql = "UPDATE facturas SET acuse_cancelacion = ? , fecha_cancelacion = TIMESTAMP(SUBSTR(ExtractValue(?, '/Acuse/@Fecha'), 1, 19)) WHERE uuid = ? ";
        error_log("Cancelando ". $uuid);
        if (($stmt = $connection->prepare($sql))) {
            if ($stmt->bind_param("sss", $acuse, $acuse, $uuid)
                    && $stmt->execute()) {
                return true;
            } else {
                error_log($stmt->error);
                throw new \Exception($stmt->error);
            }
        }
        error_log($connection->error);
        throw new \Exception($connection->error);
    }//guardaAcuse

}//FacturasDAO
