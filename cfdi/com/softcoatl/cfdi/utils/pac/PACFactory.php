<?php

/*
 * PACFactory
 * cfdi®
 * © 2018, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since ene 2018
 */

namespace com\softcoatl\cfdi\utils\pac;

include_once ('PAC.php');
include_once ('SifeiPACWrapper.php');
include_once ('ProdigiaPACWrapper.php');

class PACFactory {
    
    public static function getPAC($url, $user, $password, $pac) {

        switch ($pac) {
            case 'SIFEI' : return new SifeiPACWrapper($url, $user, $password, $pac);
            case 'PRODIGIA' : return new ProdigiaPACWrapper($url, $user, $password, $pac);
            default : return new PAC($url, $user, $password, $pac);
        }
    }//getPAC
}//PACFactory
