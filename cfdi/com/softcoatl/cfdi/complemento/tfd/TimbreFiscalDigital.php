<?php
/*
 * TimbreFiscalDigital
 * cfdi®
 * © 2018, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */
namespace com\softcoatl\cfdi\complemento\tfd;

require_once ("cfdi/com/softcoatl/cfdi/CFDIElement.php");
require_once ("cfdi/com/softcoatl/cfdi/utils/Reflection.php");

use com\softcoatl\cfdi\CFDIElement;

class TimbreFiscalDigital implements CFDIElement {

    private $Version = "1.1";
    private $UUID;
    private $FechaTimbrado;
    private $RfcProvCertif;
    private $Leyenda;
    private $SelloCFD;
    private $NoCertificadoSAT;
    private $SelloSAT;

    function getVersion() {
        return $this->Version;
    }

    function getUUID() {
        return $this->UUID;
    }

    function getFechaTimbrado() {
        return $this->FechaTimbrado;
    }

    function getRfcProvCertif() {
        return $this->RfcProvCertif;
    }

    function getLeyenda() {
        return $this->Leyenda;
    }

    function getSelloCFD() {
        return $this->SelloCFD;
    }

    function getNoCertificadoSAT() {
        return $this->NoCertificadoSAT;
    }

    function getSelloSAT() {
        return $this->SelloSAT;
    }

    function setVersion($Version) {
        $this->Version = $Version;
    }

    function setUUID($UUID) {
        $this->UUID = $UUID;
    }

    function setFechaTimbrado($FechaTimbrado) {
        $this->FechaTimbrado = $FechaTimbrado;
    }

    function setRfcProvCertif($RfcProvCertif) {
        $this->RfcProvCertif = $RfcProvCertif;
    }

    function setLeyenda($Leyenda) {
        $this->Leyenda = $Leyenda;
    }

    function setSelloCFD($SelloCFD) {
        $this->SelloCFD = $SelloCFD;
    }

    function setNoCertificadoSAT($NoCertificadoSAT) {
        $this->NoCertificadoSAT = $NoCertificadoSAT;
    }

    function setSelloSAT($SelloSAT) {
        $this->SelloSAT = $SelloSAT;
    }

    public function asXML($root) {

        $TimbreFiscalDigital = $root->ownerDocument->createElement("tfd:TimbreFiscalDigital");
        $TimbreFiscalDigital->setAttribute("xmlns:tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
        $TimbreFiscalDigital->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr=>$value) {
            $TimbreFiscalDigital->setAttribute($attr, $value);
        }
        return $TimbreFiscalDigital;            
    }

    public static function parse($DOMElement) {

        if (strpos($DOMElement->nodeName, 'tfd:TimbreFiscalDigital')!==false) {
            $TimbreFiscalDigital = new TimbreFiscalDigital();
            \com\softcoatl\cfdi\utils\Reflection::setAttributes($TimbreFiscalDigital, $DOMElement);
            return $TimbreFiscalDigital;
        }
        return false;
    }
}
