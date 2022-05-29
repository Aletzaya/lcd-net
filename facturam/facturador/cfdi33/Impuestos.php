<?php

/*
 * Impuestos
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

namespace cfdi33\Comprobante {
    
    require_once ('CFDIElement.php');

    class Impuestos implements \cfdi33\CFDIElement {

        /* @var $Retenciones Impuestos\Retenciones */
        private $Retenciones;
        /* @var $Traslados Impuestos\Traslados */
        private $Traslados;

        private $TotalImpuestosRetenidos;
        private $TotalImpuestosTrasladados;
        
        function getRetenciones() {
            return $this->Retenciones;
        }

        function getTraslados() {
            return $this->Traslados;
        }

        function getTotalImpuestosRetenidos() {
            return $this->TotalImpuestosRetenidos;
        }

        function getTotalImpuestosTrasladados() {
            return $this->TotalImpuestosTrasladados;
        }

        function setRetenciones($Retenciones) {
            $this->Retenciones = $Retenciones;
        }

        function setTraslados($Traslados) {
            $this->Traslados = $Traslados;
        }

        function setTotalImpuestosRetenidos($TotalImpuestosRetenidos) {
            $this->TotalImpuestosRetenidos = $TotalImpuestosRetenidos;
        }

        function setTotalImpuestosTrasladados($TotalImpuestosTrasladados) {
            $this->TotalImpuestosTrasladados = $TotalImpuestosTrasladados;
        }

        private function getVarArray() {
            return array_filter(get_object_vars($this), 
                            function ($val) { 
                                return !empty($val)
                                    && !($val instanceof Impuestos\Traslados)
                                    && !($val instanceof Impuestos\Retenciones);                    
            });
        }
        public function asJsonArray() {

            $ov = $this->getVarArray();

            if ($this->Traslados !== NULL) {
                $traslado = $this->Traslados->getTraslado();
                if (!empty($traslado)) {
                    $ov["Traslados"] = $this->Traslados->asJsonArray(); 
                }
            }
            
            if ($this->Retenciones !== NULL) {
                $retencion = $this->Retenciones->getRetencion();
                if (!empty($retencion)) {
                    $ov["Retenciones"] = $this->Retenciones->asJsonArray();
                }
            }

            return $ov;
        }//Impuestos::asJsonArray

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {

            $Impuestos = $root->ownerDocument->createElement("cfdi:Impuestos");
            $ov = $this->getVarArray();
            foreach ($ov as $attr=>$value) {
                $Impuestos->setAttribute($attr, $value);
            }
            
            if ($this->Traslados !== NULL) {
                $Impuestos->appendChild($this->Traslados->asXML($root));
            }
            
            if ($this->Retenciones !== NULL) {
                $Impuestos->appendChild($this->Retenciones->asXML($root));
            }
            
            return $Impuestos;

        }//asXML

        /**
         * 
         * @param \DOMElement $DOMImpuestos
         * @return \cfdi33\Comprobante\Impuestos
         */
        public static function parse($DOMImpuestos) {
            
            $Impuestos = new Impuestos();
            $Impuestos->setTotalImpuestosRetenidos($DOMImpuestos->getAttribute("TotalImpuestosRetenidos"));
            $Impuestos->setTotalImpuestosTrasladados($DOMImpuestos->getAttribute("TotalImpuestosTrasladados"));

            for ($i=0; $i<$DOMImpuestos->childNodes->length; $i++) {
                /* @var $DOMImpuesto DOMElement */
                $DOMImpuesto = $DOMImpuestos->childNodes->item($i);
                if (strpos($DOMImpuesto->nodeName, ':Traslados')) {
                    $Traslados = new \cfdi33\Comprobante\Impuestos\Traslados();
                    for ($j=0; $j<$DOMImpuesto->childNodes->length; $j++) {
                        $DOMTraslado = $DOMImpuesto->childNodes->item($j);
                        if (strpos($DOMTraslado->nodeName, ':Traslado')) {
                            $Traslado = new \cfdi33\Comprobante\Impuestos\Traslados\Traslado();
                            \com\softcoatl\Reflection::setAttributes($Traslado, $DOMTraslado);
                            $Traslados->addTraslado($Traslado);
                        }
                    }
                    $Impuestos->setTraslados($Traslados);
                } else if (strpos($DOMImpuesto->nodeName, ':Retenciones')) {
                    $Retenciones = new \cfdi33\Comprobante\Impuestos\Retenciones();
                    for ($j=0; $j<$DOMImpuesto->childNodes->length; $j++) {
                        $DOMRetencion = $DOMImpuesto->childNodes->item($j);
                        if (strpos($DOMTraslado->nodeName, ':Retencion')) {
                            $Retencion = new \cfdi33\Comprobante\Impuestos\Retenciones\Retencion();
                            \com\softcoatl\Reflection::setAttributes($Retencion, $DOMRetencion);
                            $Retenciones->addRetencion($Retencion);
                        }
                    }
                    $Impuestos->setRetenciones($Retenciones);
                }
            }
        
            return $Impuestos;
        }
    }//cfdi33\Comprobante\Impuestos
    
}//cfdi33\Comprobante

namespace cfdi33\Comprobante\Impuestos {
    
    class Traslados implements \cfdi33\CFDIElement {

        private $Traslado = array();

        function getTraslado() {
            return $this->Traslado;
        }

        /* @var $Traslado Traslados\Traslado */
        function addTraslado($Traslado) {
            array_push($this->Traslado, $Traslado);
        }

        public function asJsonArray() {
            $traslados = array();

            /* @var $traslado Traslados\Traslado */
            foreach ($this->Traslado as $traslado) {
                $traslados[] = $traslado->asJsonArray();
            }

            return array("Traslado" => $traslados);
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Traslados = $root->ownerDocument->createElement("cfdi:Traslados");
            /* @var $traslado Traslados\Traslado */
            foreach ($this->Traslado as $traslado) {
                $Traslados->appendChild($traslado->asXML($root));
            }
            return $Traslados;
        }

//Traslados::asJsonArray
    }//cfdi33\Comprobante\Impuestos\Traslados

    class Retenciones implements \cfdi33\CFDIElement {

        private $Retencion = array();

        function getRetencion() {
            return $this->Retencion;
        }

        /* @var $Retencion Retenciones\Retencion */
        function addRetencion($Retencion) {
            array_push($this->Retencion, $Retencion);
        }

        public function asJsonArray() {
            $retenciones = array();

            /* @var $retencion Retenciones\Retencion */
            foreach ($this->Retencion as $retencion) {
                $retenciones[] = $retencion->asJsonArray();
            }

            return array("Retencion" => $retenciones);
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Retenciones = $root->ownerDocument->createElement("cfdi:Retenciones");
            /* @var $retencion Retenciones\Retencion */
            foreach ($this->Retencion as $retencion) {
                $Retenciones->appendChild($retencion->asXML($root));
            }
            return $Retenciones;
        }

//Retenciones::asJsonArray

    }//cfdi33\Comprobante\Impuestos\Traslados

}//cfdi33\Impuestos

namespace cfdi33\Comprobante\Impuestos\Traslados {

    class Traslado implements \cfdi33\CFDIElement {

        private $Impuesto;
        private $TipoFactor;
        private $TasaOCuota;
        private $Importe;
        
        function getImpuesto() {
            return $this->Impuesto;
        }

        function getTipoFactor() {
            return $this->TipoFactor;
        }

        function getTasaOCuota() {
            return $this->TasaOCuota;
        }

        function getImporte() {
            return $this->Importe;
        }

        function setImpuesto($Impuesto) {
            $this->Impuesto = $Impuesto;
        }

        function setTipoFactor($TipoFactor) {
            $this->TipoFactor = $TipoFactor;
        }

        function setTasaOCuota($TasaOCuota) {
            $this->TasaOCuota = $TasaOCuota;
        }

        function setImporte($Importe) {
            $this->Importe = $Importe;
        }

        public function asJsonArray() {
            return array_filter(get_object_vars($this));
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Traslado = $root->ownerDocument->createElement("cfdi:Traslado");
            $ov = array_filter(get_object_vars($this));
            foreach ($ov as $attr=>$value) {
                $Traslado->setAttribute($attr, $value);
            }
            return $Traslado;
        }

    }//cfdi33\Comprobante\Impuestos\Traslados

}//cfdi33\Comprobante\Impuestos\Traslados

namespace cfdi33\Comprobante\Impuestos\Retenciones {

    class Retencion implements \cfdi33\CFDIElement {

        private $Impuesto;
        private $Importe;
        
        function getImpuesto() {
            return $this->Impuesto;
        }

        function getImporte() {
            return $this->Importe;
        }

        function setImpuesto($Impuesto) {
            $this->Impuesto = $Impuesto;
        }

        function setImporte($Importe) {
            $this->Importe = $Importe;
        }

        public function asJsonArray() {
            return array_filter(get_object_vars($this));        
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Retencion = $root->ownerDocument->createElement("cfdi:Retencion");
            $ov = array_filter(get_object_vars($this));
            foreach ($ov as $attr=>$value) {
                $Retencion->setAttribute($attr, $value);
            }
            return $Retencion;
        }

    }//cfdi33\Comprobante\Impuestos\Retenciones\Retencion
}//cfdi33\Comprobante\Impuestos\Retenciones
