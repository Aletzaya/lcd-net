<?php
/*
 * Receptor
 * CFDI versión 4.0
 * CFDI®
 * © 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @version 1.0
 * @since jan 2022
 */
namespace com\softcoatl\cfdi\v40\schema\Comprobante40;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;
use com\softcoatl\cfdi\Comprobante;

class Receptor implements CFDIElement, Comprobante\Receptor {

    private $Rfc;
    private $Nombre;
    private $DomicilioFiscalReceptor;
    private $ResidenciaFiscal;
    private $NumRegIdTrib;
    private $RegimenFiscalReceptor;
    private $UsoCFDI;

    function getRfc() {
        return $this->Rfc;
    }

    function getNombre() {
        return $this->Nombre;
    }

    public function getDomicilioFiscalReceptor() {
        return $this->DomicilioFiscalReceptor;
    }

    function getResidenciaFiscal() {
        return $this->ResidenciaFiscal;
    }

    function getNumRegIdTrib() {
        return $this->NumRegIdTrib;
    }

    public function getRegimenFiscalReceptor() {
        return $this->RegimenFiscalReceptor;
    }

    function getUsoCFDI() {
        return $this->UsoCFDI;
    }

    function setRfc($Rfc) {
        $this->Rfc = $Rfc;
    }

    function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    public function setDomicilioFiscalReceptor($DomicilioFiscalReceptor) {
        $this->DomicilioFiscalReceptor = $DomicilioFiscalReceptor;
    }

    function setResidenciaFiscal($ResidenciaFiscal) {
        $this->ResidenciaFiscal = $ResidenciaFiscal;
    }

    function setNumRegIdTrib($NumRegIdTrib) {
        $this->NumRegIdTrib = $NumRegIdTrib;
    }

    public function setRegimenFiscalReceptor($RegimenFiscalReceptor) {
        $this->RegimenFiscalReceptor = $RegimenFiscalReceptor;
    }

    function setUsoCFDI($UsoCFDI) {
        $this->UsoCFDI = $UsoCFDI;
    }

    public function asXML($root) {

        $Receptor = $root->ownerDocument->createElement("cfdi:Receptor");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr => $value) {
            $Receptor->setAttribute($attr, $value);
        }
        
        return $Receptor;
    }

    public static function parse($DOMReceptor) {

        $Receptor = new Receptor();
        Reflection::setAttributes($Receptor, $DOMReceptor);
        return $Receptor;
    }
}
