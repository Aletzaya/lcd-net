<?php

/*
 * CFDIElement
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

namespace cfdi33 {

    interface CFDIElement {

        public function asJsonArray();
        public function asXML($root);
    }

}