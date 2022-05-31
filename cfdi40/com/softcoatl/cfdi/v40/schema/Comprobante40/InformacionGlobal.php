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

use com\softcoatl\cfdi\CFDIElement;

class InformacionGlobal implements CFDIElement {

    private $Periodicidad;
    private $Meses;
    private $Anio;

    public function getPeriodicidad() {
        return $this->Periodicidad;
    }

    public function getMeses() {
        return $this->Meses;
    }

    public function getAnio() {
        return $this->Anio;
    }

    public function setPeriodicidad($Periodicidad) {
        $this->Periodicidad = $Periodicidad;
    }

    public function setMeses($Meses) {
        $this->Meses = $Meses;
    }

    public function setAnio($Anio) {
        $this->Anio = $Anio;
    }

    public function asXML($root) {

        $InformacionGlobal = $root->ownerDocument->createElement("cfdi:InformacionGlobal");
        $InformacionGlobal->setAttribute("Periodicidad", $this->Periodicidad);
        $InformacionGlobal->setAttribute("Meses", $this->Meses);
        $InformacionGlobal->setAttribute("Año", $this->Anio);
        return $InformacionGlobal;
    }

    public static function parse($DOMInformacionGlobal) {
        $InformacionGlobal = new InformacionGlobal();
        $InformacionGlobal->setPeriodicidad($DOMInformacionGlobal->getAttribute("Periodicidad"));
        $InformacionGlobal->setMeses($DOMInformacionGlobal->getAttribute("Meses"));
        $InformacionGlobal->setAnio($DOMInformacionGlobal->getAttribute("Año"));
        return $InformacionGlobal;
    }
}

