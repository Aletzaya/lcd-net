<?php

/*
 * SifeiPACWrapper
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

class SifeiPACWrapper extends PAC {

    private $Serie; // Serie de la licencia
    private $IdEquipo; // ID del equipo


    function __construct($url, $user, $password, $pac) {
        parent::__construct($url, $user, $password, $pac);
    }

    function getSerie() {
        return $this->Serie;
    }

    function getIdEquipo() {
        return $this->IdEquipo;
    }

    function setSerie($Serie) {
        $this->Serie = $Serie;
    }

    function setIdEquipo($IdEquipo) {
        $this->IdEquipo = $IdEquipo;
    }
}
