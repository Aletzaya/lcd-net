<?php

/*
 * SifeiPACWrapper
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace cfdi33;

require_once 'PAC.php';

class SifeiPAC extends PAC {
    
    private $Serie;
    private $IdEquipo;

    function __construct($url, $user, $password) {
        parent::__construct($url, $user, $password);
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
