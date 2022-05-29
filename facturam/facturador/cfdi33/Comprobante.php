<?php

/*
 * Comprobante
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

namespace cfdi33 {

    require_once ('CFDIElement.php');
    require_once ('cfdi33/utils/Reflection.php');
    require_once ('Emisor.php');
    require_once ('Receptor.php');
    require_once ('Conceptos.php');
    require_once ('CfdiRelacionados.php');
    require_once ('Impuestos.php');
    require_once ('cfdi33/complemento/TimbreFiscalDigital.php');

    class Comprobante implements CFDIElement {

        /* @var $CfdiRelacionados Comprobante\CfdiRelacionados */
        private $CfdiRelacionados;
        /* @var $Emisor Comprobante\Emisor */
        private $Emisor;
        /* @var $Receptor Comprobante\Receptor */
        private $Receptor;
        /* @var $Conceptos Comprobante\Conceptos */
        private $Conceptos;
        /* @var $Impuestos Comprobante\Impuestos */
        private $Impuestos;

        private $Complemento = array();
        private $Addenda = array();

        private $Version;
        private $Serie;
        private $Folio;
        private $Fecha;
        private $Sello;
        private $FormaPago;
        private $NoCertificado;
        private $Certificado;
        private $CondicionesDePago;
        private $SubTotal;
        private $Descuento;
        private $Moneda;
        private $TipoCambio;
        private $Total;
        private $TipoDeComprobante;
        private $MetodoPago;
        private $LugarExpedicion;
        private $Confirmacion;

        /** 
         * 
         * @return Comprobante\CfdiRelacionados
         */
        function getCfdiRelacionados() {
            return $this->CfdiRelacionados;
        }

        /**
         * 
         * @return Comprobante\Emisor
         */
        function getEmisor() {
            return $this->Emisor;
        }

        /**
         * 
         * @return Comprobante\Receptor
         */
        function getReceptor() {
            return $this->Receptor;
        }

        /**
         * 
         * @return Comprobante\Conceptos
         */
        function getConceptos() {
            return $this->Conceptos;
        }

        /** 
         * 
         * @return Comprobante\Impuestos
         */
        function getImpuestos() {
            return $this->Impuestos;
        }

        function getComplemento() {
            return $this->Complemento;
        }

        function getAddenda() {
            return $this->Addenda;
        }

        function getVersion() {
            return $this->Version;
        }

        function getSerie() {
            return $this->Serie;
        }

        function getFolio() {
            return $this->Folio;
        }

        function getFecha() {
            return $this->Fecha;
        }

        function getSello() {
            return $this->Sello;
        }

        function getFormaPago() {
            return $this->FormaPago;
        }

        function getNoCertificado() {
            return $this->NoCertificado;
        }

        function getCertificado() {
            return $this->Certificado;
        }

        function getCondicionesDePago() {
            return $this->CondicionesDePago;
        }

        function getSubTotal() {
            return $this->SubTotal;
        }

        function getDescuento() {
            return $this->Descuento;
        }

        function getMoneda() {
            return $this->Moneda;
        }

        function getTipoCambio() {
            return $this->TipoCambio;
        }

        function getTotal() {
            return $this->Total;
        }

        function getTipoDeComprobante() {
            return $this->TipoDeComprobante;
        }

        function getMetodoPago() {
            return $this->MetodoPago;
        }

        function getLugarExpedicion() {
            return $this->LugarExpedicion;
        }

        function getConfirmacion() {
            return $this->Confirmacion;
        }

        function setCfdiRelacionados($CfdiRelacionados) {
            $this->CfdiRelacionados = $CfdiRelacionados;
        }

        function setEmisor($Emisor) {
            $this->Emisor = $Emisor;
        }

        function setReceptor($Receptor) {
            $this->Receptor = $Receptor;
        }

        function setConceptos($Conceptos) {
            $this->Conceptos = $Conceptos;
        }

        function setImpuestos($Impuestos) {
            $this->Impuestos = $Impuestos;
        }

        /* @var $Complemento CFDIElement */
        function addComplemento($Complemento) {
            array_push($this->Complemento, $Complemento);
        }

        /* @var $Addenda CFDIElement */
        function addAddenda($Addenda) {
            array_push($this->Addenda, $Addenda);
        }

        function setVersion($Version) {
            $this->Version = $Version;
        }

        function setSerie($Serie) {
            $this->Serie = $Serie;
        }

        function setFolio($Folio) {
            $this->Folio = $Folio;
        }

        function setFecha($Fecha) {
            $this->Fecha = $Fecha;
        }

        function setSello($Sello) {
            $this->Sello = $Sello;
        }

        function setFormaPago($FormaPago) {
            $this->FormaPago = $FormaPago;
        }

        function setNoCertificado($NoCertificado) {
            $this->NoCertificado = $NoCertificado;
        }

        function setCertificado($Certificado) {
            $this->Certificado = $Certificado;
        }

        function setCondicionesDePago($CondicionesDePago) {
            $this->CondicionesDePago = $CondicionesDePago;
        }

        function setSubTotal($SubTotal) {
            $this->SubTotal = $SubTotal;
        }

        function setDescuento($Descuento) {
            $this->Descuento = $Descuento;
        }

        function setMoneda($Moneda) {
            $this->Moneda = $Moneda;
        }

        function setTipoCambio($TipoCambio) {
            $this->TipoCambio = $TipoCambio;
        }

        function setTotal($Total) {
            $this->Total = $Total;
        }

        function setTipoDeComprobante($TipoDeComprobante) {
            $this->TipoDeComprobante = $TipoDeComprobante;
        }

        function setMetodoPago($MetodoPago) {
            $this->MetodoPago = $MetodoPago;
        }

        function setLugarExpedicion($LugarExpedicion) {
            $this->LugarExpedicion = $LugarExpedicion;
        }

        function setConfirmacion($Confirmacion) {
            $this->Confirmacion = $Confirmacion;
        }

        /**
         * 
         * @return complemento\TimbreFiscalDigital|boolean
         */
        function getTimbreFiscalDigital() {
            
            if ($this->Complemento!=NULL) {
                foreach ($this->Complemento as $Complemento) {
                    if ($Complemento instanceof complemento\TimbreFiscalDigital) {
                        return $Complemento;
                    }
                }
            }
            
            return FALSE;
        }

        public function getValidationURL() {
            return "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx" 
                . "?Id=" . ($this->getTimbreFiscalDigital()==null ? "" : $this->getTimbreFiscalDigital()->getUUID())
                . "&Re=" . $this->Emisor->getRfc()
                . "&Rr=" . $this->Receptor->getRfc()
                . "&Tt=" . $this->getTotal()
                . "&Fe=" . substr($this->getSello(), strlen($this->getSello())-8, 8);
        }

        private function getVarArray() {
            return array_filter(get_object_vars($this), 
                            function ($val) {
                                return !is_array($val) 
                                    && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                    && !($val instanceof Comprobante\Emisor)
                                    && !($val instanceof Comprobante\Receptor)
                                    && !($val instanceof Comprobante\Conceptos)
                                    && !($val instanceof Comprobante\CfdiRelacionados)
                                    && !($val instanceof Comprobante\Impuestos);
            });
        }
        public function asJsonArray() {
            $ov = $this->getVarArray();

            $ov["Emisor"] = $this->Emisor->asJsonArray();
            $ov["Receptor"] = $this->Receptor->asJsonArray();

            if ($this->CfdiRelacionados !== NULL) {
                $cfdis = $this->CfdiRelacionados->getCfdiRelacionado();
                if (!empty($cfdis)) {
                    $ov["CfdiRelacionados"] = $this->CfdiRelacionados->asJsonArray();
                }
            }

            if ($this->Impuestos !== NULL) {
                $ov["Impuestos"] = $this->Impuestos->asJsonArray();
            }

            $ov["Conceptos"] = $this->Conceptos->asJsonArray();

            if (!empty($this->Addenda)) {
                $addendas = array();
                /* @var $addenda CFDIElement */
                foreach ($this->Addenda as $addenda) {
                    $addendas[] = $addenda->asJsonArray();
                }
                $ov["Addenda"] = $addendas;
            }

            if (!empty($this->Complemento)) {
                $complementos = array();
                /* @var $complemento CFDIElement */
                foreach ($this->Complemento as $complemento) {
                    $complementos[] = $complemento->asJsonArray();
                }
                $ov["Complemento"] = $complementos;
            }

            return $ov;
        }//Comprobante::asJsonArray

        /**
         * 
         * @param \DOMDocument $root
         * @return \DOMDocument
         */
        public function asXML($root= NULL) {

            /* @var $Comprobante \DOMElement */
            $document = new \DOMDocument("1.0", "UTF-8");
            $Comprobante = $document->createElement("cfdi:Comprobante");
            $document->appendChild($Comprobante);

            $Comprobante->setAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd/3");
            $Comprobante->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
            $Comprobante->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd");

            $ov = $this->getVarArray();
            foreach ($ov as $attr=>$value) {
               $Comprobante->setAttribute($attr, $value);
            }

            if ($this->CfdiRelacionados !== NULL) {
                $cfdis = $this->CfdiRelacionados->getCfdiRelacionado();
                if (!empty($cfdis)) {
                    $Comprobante->appendChild($this->CfdiRelacionados->asXML($Comprobante));
               }
            }

            $Comprobante->appendChild($this->Emisor->asXML($Comprobante));
            $Comprobante->appendChild($this->Receptor->asXML($Comprobante));

            $Comprobante->appendChild($this->Conceptos->asXML($Comprobante));

            if ($this->Impuestos !== NULL) {
                $Comprobante->appendChild($this->Impuestos->asXML($Comprobante));
            }

            if (!empty($this->Complemento)) {
                $complementos = $document->createElement("cfdi:Complemento");
                /* @var $addenda CFDIElement */
                foreach ($this->Complemento as $complemento) {
                    $complementos->appendChild($complemento->asXML($Comprobante));
                }
                $Comprobante->appendChild($complementos);
            }

            if (!empty($this->Addenda)) {
                $addendas = $document->createElement("cfdi:Addenda");
                /* @var $addenda CFDIElement */
                foreach ($this->Addenda as $addenda) {
                    $addendas->appendChild($addenda->asXML($Comprobante));
                }
                $Comprobante->appendChild($addendas);
            }

            return $document;
        }

        /**
         * 
         * @param String $DOMCfdi
         * @param type $Parent
         * @return \cfdi33\cfdi33\Comprobante
         */
        public static function parse($DOMCfdi) {

            $document = new \DOMDocument("1.0","UTF-8");
            $document->loadXML($DOMCfdi);

            if ($document->hasChildNodes()) {
                /* @var $cfdi DOMElement */
                $cfdi = $document->firstChild;
                $Comprobante  = new Comprobante();
                \com\softcoatl\Reflection::setAttributes($Comprobante, $cfdi);

                for ($i=0; $i<$cfdi->childNodes->length; $i++) {
                    $node = $cfdi->childNodes->item($i);

                    if (strpos($node->nodeName, ':CfdiRelacionados')) {
                        $Comprobante->setCfdiRelacionados(Comprobante\CfdiRelacionados::parse($node));
                    }
                    else if (strpos($node->nodeName, ':Emisor')) {
                        $Comprobante->setEmisor(Comprobante\Emisor::parse($node));
                    }
                    else if (strpos($node->nodeName, ':Receptor')) {
                        $Comprobante->setReceptor(Comprobante\Receptor::parse($node));
                    }
                    else if (strpos($node->nodeName, ':Conceptos')) {
                        $Comprobante->setConceptos(Comprobante\Conceptos::parse($node));
                    }
                    else if (strpos($node->nodeName, ':Impuestos')) {
                        $Comprobante->setImpuestos(Comprobante\Impuestos::parse($node));
                    }
                    else if (strpos($node->nodeName, ':Complemento')) {
                        Comprobante::unmarshallComplemento($Comprobante, $node);
                    }
                    else if (strpos($node->nodeName, ':Addenda')) {
                        Comprobante::unmarshallAddenda($Comprobante, $node);
                    }
                }
            }
            
            return $Comprobante;
        }

        /**
         * 
         * @param \cfdi33\Comprobante $Comprobante
         * @param DOMElement $DOMComplementos
         */
        private static function unmarshallComplemento($Comprobante, $DOMComplementos) {

            for ($i=0; $i<$DOMComplementos->childNodes->length; $i++) {
                /* @var $DOMComplemento DOMElement */
                $DOMComplemento = $DOMComplementos->childNodes->item($i);
                if (strpos($DOMComplemento->nodeName, ':TimbreFiscalDigital')) {

                    $Comprobante->addComplemento(\cfdi33\complemento\TimbreFiscalDigital::parse($DOMComplemento));
                } else if (strpos($DOMComplemento->nodeName, ':Pagos')) {

                    $Comprobante->addComplemento(\cfdi33\complemento\Pagos::parse($DOMComplemento));
                }
            }
        }

        /**
         * 
         * @param \cfdi33\Comprobante $Comprobante
         * @param DOMElement $DOMAddendas
         */
        private static function unmarshallAddenda($Comprobante, $DOMAddendas) {

            for ($i=0; $i<$DOMAddendas->childNodes->length; $i++) {
                /* @var $DOMAddenda DOMElement */
                $DOMAddenda = $DOMAddendas->childNodes->item($i);
                if (strpos($DOMAddenda->nodeName, ':Observaciones')) {
                    $Comprobante->addAddenda(\Observaciones::parse($DOMAddenda));
                }
            }
        }
    }//cfdi33\Comprobante

}//cfdi33
