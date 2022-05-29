<?php

/*
 * EmisorVO
 * cfdi33®
 * © 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

namespace cfdi33\data;

class EmisorVO implements CFDIElement {

    private $Rfc;
    private $Nombre;
    private $RegimenFiscal;
    
    function getRfc() {
        return $this->Rfc;
    }

    function getNombre() {
        return $this->Nombre;
    }

    function getRegimenFiscal() {
        return $this->RegimenFiscal;
    }

    function setRfc($Rfc) {
        $this->Rfc = $Rfc;
    }

    function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    function setRegimenFiscal($RegimenFiscal) {
        $this->RegimenFiscal = $RegimenFiscal;
    }

    public function asJsonArray() {
        return array_filter(get_object_vars($this));        
    }
}//EmisorVO
