<?php

/*
 * PACService
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace cfdi33 {
    interface PACService {

        public function timbraComprobante($xmlCFDI);
        public function getTimbre($xmlCFDI);    
        public function cancelaComprobante($rfc, $uuid, $pass);
    }//PACService
    
}