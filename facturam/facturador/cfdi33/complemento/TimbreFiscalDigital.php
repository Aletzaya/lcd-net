<?php

/*
 * TimbreFiscalDigital
 * cfdi®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace cfdi33\complemento {

    require_once ('cfdi33/CFDIElement.php');

    class TimbreFiscalDigital implements \cfdi33\CFDIElement {

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

        public function asJsonArray() {
            $ov = array_filter(get_object_vars($this));
            return array("TimbreFiscalDigital"=>$ov);
        }//asJsonArray

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMElement
         */
        public function asXML($root) {

            $root->setAttribute("xmlns:tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
            /* @var $TimbreFiscalDigital \DOMElement */
            $TimbreFiscalDigital = $root->ownerDocument->createElement("tfd:TimbreFiscalDigital");

            $TimbreFiscalDigital->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd");
            $TimbreFiscalDigital->setAttribute("Version", $this->Version);
            $TimbreFiscalDigital->setAttribute("UUID", $this->getUUID());
            $TimbreFiscalDigital->setAttribute("FechaTimbrado", $this->FechaTimbrado);
            $TimbreFiscalDigital->setAttribute("RfcProvCertif", $this->RfcProvCertif);
            $TimbreFiscalDigital->setAttribute("SelloCFD", $this->SelloCFD);
            $TimbreFiscalDigital->setAttribute("NoCertificadoSAT", $this->NoCertificadoSAT);
            $TimbreFiscalDigital->setAttribute("SelloSAT", $this->SelloSAT);
            $TimbreFiscalDigital->setAttribute("Leyenda", $this->Leyenda);

            return $TimbreFiscalDigital;            
        }//asXML

        /**
         * 
         * @param type $DOMTimbreFiscalDigital
         * @return \cfdi33\complemento\TimbreFiscalDigital
         */
        public static function parse($DOMTimbreFiscalDigital) {
            
            $TimbreFiscalDigital = new TimbreFiscalDigital();
            \com\softcoatl\Reflection::setAttributes($TimbreFiscalDigital, $DOMTimbreFiscalDigital);
            return $TimbreFiscalDigital;
        }
    }//TimbreFiscalDigital

}