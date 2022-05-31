<?php
/*
 * FacturasVO
 * detifac®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

include_once ('softcoatl/SoftcoatlHTTP.php');

use com\softcoatl\utils as utils;
use com\softcoatl\cfdi\v33\schema\Comprobante;

class FacturasVO extends utils\BaseVO {

    private $id;
    private $version;
    private $fecha_emision;
    private $fecha_timbrado;
    private $fecha_cancelacion;
    private $cfdi_xml;
    private $pdf_format;
    private $clave_pac;
    private $emisor;
    private $receptor;
    private $uuid;
    private $acuse;

    /* @var $comprobante \cfdi33\Comprobante */
    private $comprobante;

    function getId($default = "") {
        return parent::uempty($this->id, $default);
    }

    function getVersion($default = "3.3") {
        return parent::uempty($this->version, $default);
    }

    function getFecha_emision($default = "") {
        return parent::uempty($this->fecha_emision, $default);
    }

    function getFecha_timbrado($default = "") {
        return parent::uempty($this->fecha_timbrado, $default);
    }

    function getFecha_cancelacion($default = "") {
        return parent::uempty($this->fecha_cancelacion, $default);
    }

    function getCfdi_xml($default = "") {
        return parent::uempty($this->cfdi_xml, $default);
    }

    function getPdf_format($default = null) {
        return parent::uempty($this->pdf_format, $default);
    }

    function getClave_pac($default = "") {
        return parent::uempty($this->clave_pac, $default);
    }

    function getEmisor($default = "") {
        return parent::uempty($this->emisor, $default);
    }

    function getReceptor($default = "") {
        return parent::uempty($this->receptor, $default);
    }

    function getUuid($default = "") {
        return parent::uempty($this->uuid, $default);
    }

    function getAcuse($default = null) {
        return parent::uempty($this->acuse, $default);
    }

    /**
     * 
     * @return \Comprobante
     */
    function getComprobante() {
        return $this->comprobante;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setVersion($version) {
        $this->version = $version;
    }

    function setFecha_emision($fecha_emision) {
        $this->fecha_emision = $fecha_emision;
    }

    function setFecha_timbrado($fecha_timbrado) {
        $this->fecha_timbrado = $fecha_timbrado;
    }

    function setFecha_cancelacion($fecha_cancelacion) {
        $this->fecha_cancelacion = $fecha_cancelacion;
    }

    function setCfdi_xml($cfdi_xml) {
        $this->cfdi_xml = $cfdi_xml;
    }

    function setPdf_format($pdf_format) {
        $this->pdf_format = $pdf_format;
    }

    function setClave_pac($clave_pac) {
        $this->clave_pac = $clave_pac;
    }

    function setEmisor($emisor) {
        $this->emisor = $emisor;
    }

    function setReceptor($receptor) {
        $this->receptor = $receptor;
    }

    function setUuid($uuid) {
        $this->uuid = $uuid;
    }

    function setAcuse($acuse) {
        $this->acuse = $acuse;
    }

    function setComprobante($comprobante) {
        $this->comprobante = $comprobante;
    }

    public static function parse($array) {

        $factura = new FacturasVO();
        if (!empty($array)) {
            $factura->setId(parent::uempty($array['id_fc_fk']));
            $factura->setVersion(parent::uempty($array['version']));
            $factura->setFecha_emision(parent::uempty($array['fecha_emision']));
            $factura->setFecha_timbrado(parent::uempty($array['fecha_timbrado']));
            $factura->setFecha_cancelacion(parent::uempty($array['fecha_cancelacion']));
            $factura->setCfdi_xml(parent::uempty($array['cfdi_xml']));
            $factura->setPdf_format(parent::uempty($array['pdf_format']));
            $factura->setClave_pac(parent::uempty($array['clave_pac']));
            $factura->setEmisor(parent::uempty($array['emisor']));
            $factura->setReceptor(parent::uempty($array['receptor']));
            $factura->setUuid(parent::uempty($array['uuid']));
            $factura->setAcuse(parent::uempty($array['acuse_cancelacion']));
            $factura->setComprobante(Comprobante::parse($array['cfdi_xml']));
        }
        return $factura;
    }
}
