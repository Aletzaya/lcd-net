<?php
/*
 * Complemento
 * CFDI versión 4.0
 * CFDI®
 * © 2022, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jan 2022
 */
namespace com\softcoatl\cfdi\v33\schema\Comprobante33;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class Complemento implements CFDIElement, Comprobante\Complemento {

    /** @var */
    private $any = array();

    public function getAny(): array {
        return $this->any;
    }

    public function addAny(CFDIElement $any) {
        $this->any[] = $any;
    }

    public function asXML($root) {
        
    }
}
