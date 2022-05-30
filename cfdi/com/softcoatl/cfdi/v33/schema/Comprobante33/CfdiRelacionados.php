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
namespace com\softcoatl\cfdi\v33\schema\Comprobante33;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class CfdiRelacionados implements CFDIElement, Comprobante\CfdiRelacionados {

    private $CfdiRelacionado = array();
    private $TipoRelacion;

    function getCfdiRelacionado(): array {
        return $this->CfdiRelacionado;
    }

    function getTipoRelacion() {
        return $this->TipoRelacion;
    }

    public function setCfdiRelacionado(array $CfdiRelacionado) {
        $this->CfdiRelacionado = $CfdiRelacionado;
    }

    function addCfdiRelacionado($CfdiRelacionado) {
        $this->CfdiRelacionado[] = $CfdiRelacionado;
    }

    function setTipoRelacion($TipoRelacion) {
        $this->TipoRelacion = $TipoRelacion;
    }

    /**
     * 
     * @param \DOMElement $root
     * @return \DOMNode
     */
    public function asXML($root) {

        $CfdiRelacionados = $root->ownerDocument->createElement("cfdi:CfdiRelacionados");
        $CfdiRelacionados->setAttribute("TipoRelacion", $this->TipoRelacion);
        /* @var $cfdiRelacionado CFDIElement */
        foreach ($this->CfdiRelacionado as $cfdiRelacionado) {
            $CfdiRelacionados->appendChild($cfdiRelacionado->asXML($root));
        }
        return $CfdiRelacionados;
    }

    /**
     * 
     * @param \DOMElement $DOMCfdiRelacionados
     */
    public static function parse($DOMCfdiRelacionados) {

        $CfdiRelacionados = new CfdiRelacionados();
        $CfdiRelacionados->setTipoRelacion($DOMCfdiRelacionados->getAttribute("TipoRelacion"));

        /* @var $node DOMElement */
        for ($i=0; $i<$DOMCfdiRelacionados->childNodes->length; $i++) {

            $node = $DOMCfdiRelacionados->childNodes->item($i);
            if (strpos($node->nodeName, 'cfdi:CfdiRelacionado')!==false) {
                $CfdiRelacionado = new CfdiRelacionados\CfdiRelacionado();
                $CfdiRelacionado->setUUID($node->getAttribute("UUID"));
                $CfdiRelacionados->addCfdiRelacionado($CfdiRelacionado);
            }
        }

        return $CfdiRelacionados;
    }

}//CfdiRelacionados

namespace com\softcoatl\cfdi\v33\schema\Comprobante33\CfdiRelacionados;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class CfdiRelacionado implements CFDIElement, Comprobante\CfdiRelacionados\CfdiRelacionado {

    private $UUID;

    function getUUID() {
        return $this->UUID;
    }

    function setUUID($UUID) {
        $this->UUID = $UUID;
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

}//CfdiRelacionado
