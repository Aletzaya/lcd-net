<?php

/*
 * CfdiRelacionados
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

namespace cfdi33\Comprobante {

    require_once ('CFDIElement.php');

    class CfdiRelacionados implements \cfdi33\CFDIElement {

        private $CfdiRelacionado = array();
        private $TipoRelacion;

        function getCfdiRelacionado() {
            return $this->CfdiRelacionado;
        }

        function getTipoRelacion() {
            return $this->TipoRelacion;
        }

        function addCfdiRelacionado($CfdiRelacionado) {
            array_push($this->CfdiRelacionado, $CfdiRelacionado);
        }

        function setTipoRelacion($TipoRelacion) {
            $this->TipoRelacion = $TipoRelacion;
        }

        private function getVarArray() {

            return array_filter(get_object_vars($this), 
                                        function ($val) { 
                                            return !is_array($val) && !empty($val);                    
                                        });
        }

        public function asJsonArray() {
            $ov = $this->getVarArray();
            $cfdiRelacionados = array();

            /* @var $relacionado CfdiRelacionado */
            foreach ($this->CfdiRelacionado as $relacionado) {
                $cfdiRelacionados[] = $relacionado->asJsonArray();
            }

            $ov["CfdiRelacionado"] = $cfdiRelacionados;

            return $ov;
        }//CfdiRelacionados::asJsonArray

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {

            $CfdiRelacionados = $root->ownerDocument->createElement("cfdi:CfdiRelacionados");
            $CfdiRelacionados->setAttribute("TipoRelacion", $this->TipoRelacion);
            /* @var $cfdiRelacionado \cfdi33\CFDIElement */
            foreach ($this->CfdiRelacionado as $cfdiRelacionado) {
                $CfdiRelacionados->appendChild($cfdiRelacionado->asXML($root));
            }
            return $CfdiRelacionados;
        }

        /**
         * 
         * @param \DOMElement $DOMCfdiRelacionados
         * @param \cfdi33\Comprobante $Comprobante
         */
        public static function parse($DOMCfdiRelacionados) {

            $CfdiRelacionados = new cfdi33\Comprobante\CfdiRelacionados();
            $CfdiRelacionados->setTipoRelacion($DOMCfdiRelacionados->getAttribute("TipoRelacion"));

            /* @var $relacionado DOMElement */
            foreach ($DOMCfdiRelacionados->childNodes as $relacionado) {
                $CfdiRelacionado = new cfdi33\Comprobante\CfdiRelacionados\CfdiRelacionado();
                $CfdiRelacionado->setUUID($relacionado->getAttribute("UUID"));
                $CfdiRelacionados->addCfdiRelacionado($CfdiRelacionado);
            }

            return $CfdiRelacionados;
        }

    }//cfdi33\Comprobante\CfdiRelacionados

}//cfdi33\Comprobante

namespace cfdi33\Comprobante\CfdiRelacionados {

    class CfdiRelacionado implements \cfdi33\CFDIElement {

        private $UUID;

        function getUUID() {
            return $this->UUID;
        }

        function setUUID($UUID) {
            $this->UUID = $UUID;
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
            $CfdiRelacionado = $root->ownerDocument->createElement("cfdi:CfdiRelacionado");
            $CfdiRelacionado->setAttribute("UUID", $this->UUID);
            return $CfdiRelacionado;
        }

    }//cfdi33\Comprobante\CfdiRelacionados\CfdiRelacionado

}//cfdi33\Comprobante\CfdiRelacionados