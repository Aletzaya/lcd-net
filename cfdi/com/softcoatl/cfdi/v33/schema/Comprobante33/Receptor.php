<?php
/*
 * Receptor
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */
namespace com\softcoatl\cfdi\v33\schema\Comprobante33;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;
use com\softcoatl\cfdi\Comprobante;

class Receptor implements CFDIElement, Comprobante\Receptor {

    private $Rfc;
    private $Nombre;
    private $ResidenciaFiscal;
    private $NumRegIdTrib;
    private $UsoCFDI;

    public function getRfc() {
        return $this->Rfc;
    }

    public function getNombre() {
        return $this->Nombre;
    }

    public function getResidenciaFiscal() {
        return $this->ResidenciaFiscal;
    }

    public function getNumRegIdTrib() {
        return $this->NumRegIdTrib;
    }

    public function getUsoCFDI() {
        return $this->UsoCFDI;
    }

    public function setRfc($Rfc) {
        $this->Rfc = $Rfc;
    }

    public function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    public function setResidenciaFiscal($ResidenciaFiscal) {
        $this->ResidenciaFiscal = $ResidenciaFiscal;
    }

    public function setNumRegIdTrib($NumRegIdTrib) {
        $this->NumRegIdTrib = $NumRegIdTrib;
    }

    public function setUsoCFDI($UsoCFDI) {
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
