<?php

/*
 * FacturaDetifac
 * Detisa®
 * © 2018, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since ene 2018
 */

namespace com\detisa\detifac;
 
require_once ("com/softcoatl/cfdi/Comprobante.php");
require_once ("com/softcoatl/cfdi/addenda/detisa/Observaciones.php");
require_once ('DocumentoCFDIDetifac.php');
require_once ('FacturaDAO.php');
require_once ('FacturasDAO.php');
require_once ('FcDAO.php');

use com\softcoatl\cfdi\Comprobante;

class FacturaDetifac implements DocumentoCFDIDetifac {

    /* @var $fcDAO \FcDAO */
    private $fcDAO;
    /* @var $facturaDAO FacturaDAO */
    private $facturaDAO;
    /** Comprobante */
    private $comprobante;
    /** Comprobante */
    private $comprobanteTimbrado;
    private $representacionImpresa;
    private $xmlTimbrado;

    function __construct($idFactura, $tipoFactura) {

        $this->facturaDAO = new FacturaDAO($idFactura, $tipoFactura);
        $this->comprobante = $this->facturaDAO->getComprobante();
        $this->fcDAO = new \FcDAO();
    }//constructor

    function getComprobante() {
        return $this->comprobante;
    }

    function setComprobante(Comprobante $comprobante) {
        $this->comprobante = $comprobante;
    }

    function setXml($xml) {
        $this->xml = $xml;
    }

    function setXmlTimbrado($xmlTimbrado) {
        $this->xmlTimbrado = $xmlTimbrado;
    }

    function setComprobanteTimbrado(Comprobante $comprobanteTimbrado) {
        $this->comprobanteTimbrado = $comprobanteTimbrado;
    }

    function setRepresentacionImpresa($representacionImpresa) {
        $this->representacionImpresa = $representacionImpresa;
    }

    function update($id) {
        $this->fcDAO->updateFC($id, $this->comprobanteTimbrado->getTimbreFiscalDigital()->getUUID());
    }

    function cancel($id) {
        $this->fcDAO->cancel($id);
    }

    function cancelST($id) {
        $this->fcDAO->cancelST($id);
    }

    function proceso($id) {
        $this->fcDAO->proceso($id);
    }

    function reactiva($id) {
        $this->fcDAO->reactiva($id);
    }

    function save($id, $tipo, $clavePAC) {
        FacturasDAO::insertFactura($id, $tipo, $this->comprobanteTimbrado, $this->xmlTimbrado, $clavePAC);
        FacturasDAO::updateRelacion($id, $tipo, $this->comprobanteTimbrado->getTimbreFiscalDigital()->getUUID());
    }
    
    public function acuse($uuid, $acuse) {
        FacturasDAO::guardaAcuse($uuid, $acuse);
    }

}//FacturaDetifac
