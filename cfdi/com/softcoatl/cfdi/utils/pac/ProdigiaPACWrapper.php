<?php

/*
 * ProdigiaPACWrapper
 * Clase wrapper del PAC con datos adicionales para SIFEI (PAC)
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace com\softcoatl\cfdi\utils\pac;

require_once 'PAC.php';

class ProdigiaPACWrapper extends PAC {

    private $contrato; // Número de contrato

    function __construct($url, $user, $password, $pac) {
        parent::__construct($url, $user, $password, $pac);
    }

    public function getContrato() {
        return $this->contrato;
    }

    public function setContrato($contrato) {
        $this->contrato = $contrato;
    }
}
