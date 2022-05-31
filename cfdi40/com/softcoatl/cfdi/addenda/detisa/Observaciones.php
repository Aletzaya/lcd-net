<?php
/*
 * Observaciones
 * cfdi®
 * © 2018, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */
namespace com\softcoatl\cfdi\addenda\detisa;

use com\softcoatl\cfdi\CFDIElement;

class Observaciones implements CFDIElement {

    /** @var Observaciones\Observacion[] */
    private $Observaciones = array();

    function getObservaciones() {
        return $this->Observaciones;
    }

    public function setObservaciones(array $Observaciones) {
        $this->Observaciones = $Observaciones;
    }

    function addObservaciones(Observaciones\Observacion $observacion) {
        $this->Observaciones[] = $observacion;
    }

    public function asXML($root) {
        $Observaciones = $root->ownerDocument->createElement("deti:Observaciones");
        $Observaciones->setAttribute("xmlns:deti", "http://detisa.omicrom/");
        $Observaciones->setAttribute("xsi:schemaLocation", "http://detisa.omicrom/ https://www.detisa.com.mx/detifac/detisa.xsd");
        if (!empty($this->Observaciones)) {
            foreach ($this->Observaciones as $observacion) {
                $Observaciones->appendChild($observacion->asXML($root));
            }
        }
        return $Observaciones;
    }

    public static function parse($DOMElement) {
        $Observaciones = new Observaciones();
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, ':Observaciones')!==false) {
                $Observaciones->addObservaciones(new Observaciones\Observacion($node->getAttribute("Descripcion")));
            }
        }
        return $Observaciones;
    }
}

namespace com\softcoatl\cfdi\addenda\detisa\Observaciones;

use com\softcoatl\cfdi\CFDIElement;

class Observacion implements CFDIElement {

    private $Descripcion;

    function __construct($descripcion) {
        $this->Descripcion = $descripcion;
    }

    function getDescripcion() {
        return $this->Descripcion;
    }

    function setDescripcion($descripcion) {
        $this->Descripcion = $descripcion;
    }

    public function asXML($root) {
        $Observacion = $root->ownerDocument->createElement("deti:Observacion");
        $Observacion->setAttribute("Descripcion", $this->Descripcion);
        return $Observacion;
    }
}
