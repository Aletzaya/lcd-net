<?php
/*
 * CfdiRelacionados
 * CFDI versión 4.0
 * CFDI®
 * © 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @version 1.0
 * @since jan 2022
 */
namespace com\softcoatl\cfdi\v40\schema\Comprobante40;

require_once ("com/softcoatl/cfdi/CFDIElement.php");

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class CfdiRelacionados implements CFDIElement, Comprobante\CfdiRelacionados {

    /** @var CfdiRelacionados\CfdiRelacionado[] */
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

    function addCfdiRelacionado(CfdiRelacionados\CfdiRelacionado $CfdiRelacionado) {
        $this->CfdiRelacionado[] = $CfdiRelacionado;
    }

    function setTipoRelacion($TipoRelacion) {
        $this->TipoRelacion = $TipoRelacion;
    }

    public function asXML($root) {

        $CfdiRelacionados = $root->ownerDocument->createElement("cfdi:CfdiRelacionados");
        $CfdiRelacionados->setAttribute("TipoRelacion", $this->TipoRelacion);
        foreach ($this->CfdiRelacionado as $cfdiRelacionado) {
            $CfdiRelacionados->appendChild($cfdiRelacionado->asXML($root));
        }
        return $CfdiRelacionados;
    }

    public static function parse($DOMElement) {

        $CfdiRelacionados = new CfdiRelacionados();
        $CfdiRelacionados->setTipoRelacion($DOMElement->getAttribute("TipoRelacion"));

        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {

            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, ':CfdiRelacionado')!==false) {
                $CfdiRelacionado = new CfdiRelacionados\CfdiRelacionado();
                $CfdiRelacionado->setUUID($node->getAttribute("UUID"));
                $CfdiRelacionados->addCfdiRelacionado($CfdiRelacionado);
            }
        }

        return $CfdiRelacionados;
    }
}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\CfdiRelacionados;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class CfdiRelacionado implements CFDIElement, Comprobante\CfdiRelacionados\CfdiRelacionado {

    private $UUID;
    
    public function getUUID() {
        return $this->UUID;
    }

    public function setUUID($UUID) {
        $this->UUID = $UUID;
    }

    public function asXML($root) {
        $CfdiRelacionado = $root->ownerDocument->createElement("cfdi:CfdiRelacionado");
        $CfdiRelacionado->setAttribute("UUID", $this->UUID);
        return $CfdiRelacionado;
    }
}