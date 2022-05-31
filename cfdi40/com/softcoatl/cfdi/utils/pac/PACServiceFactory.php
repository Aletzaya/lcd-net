<?php

/*
 * PACServiceFactory
 * cfdi®
 * © 2018, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since ene 2018
 */

namespace com\softcoatl\cfdi\utils\pac;

include_once ('SifeiService.php');
include_once ('ProdigiaService.php');

class PACServiceFactory {

    public static function getPACService(PAC $pac) {

        $pacKeyword = $pac->getPac();
        switch ($pacKeyword) {
            case 'SIFEI'    : return new SifeiService($pac); 
            case 'PRODIGIA' : return new ProdigiaService($pac);
        }
        return FALSE;
    }
}
