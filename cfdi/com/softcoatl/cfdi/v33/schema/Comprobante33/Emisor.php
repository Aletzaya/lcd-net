<?php
/*
 * Emisor
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

class Emisor implements CFDIElement, Comprobante\Emisor {

    private $Rfc;
    private $Nombre;
    private $RegimenFiscal;

    public function getRfc() {
        return $this->Rfc;
    }

    public function getNombre() {
        return $this->Nombre;
    }

    public function getRegimenFiscal() {
        return $this->RegimenFiscal;
    }

    public function setRfc($Rfc) {
        $this->Rfc = $Rfc;
    }

    public function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    public function setRegimenFiscal($RegimenFiscal) {
        $this->RegimenFiscal = $RegimenFiscal;
    }

    public function asXML($root) {
        $Emisor = $root->ownerDocument->createElement("cfdi:Emisor");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr => $value) {
            $Emisor->setAttribute($attr, $value);
        }
        return $Emisor;
    }

    public static function parse($DOMEmisor) {

        $Emisor = new Emisor();
        Reflection::setAttributes($Emisor, $DOMEmisor);
        return $Emisor;
    }
}
