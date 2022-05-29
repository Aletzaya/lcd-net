<?php

/*
 * OrderOfMagnitude
 * cfdi®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace com\softcoatl;

class OrderOfMagnitude {

    public static function get($number, $base) {
        $orderOfMagnitude = 0;
        for ($i = 10; $number/$i>1; $i*=$base) {
            $orderOfMagnitude++;
        }
        return $orderOfMagnitude;
    }
}
