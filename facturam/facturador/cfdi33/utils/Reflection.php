<?php

/*
 * Reflection
 * cfdi®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace com\softcoatl;

class Reflection {

    /**
     * 
     * @param Object $Object
     * @param DOMElement $node
     */
    public static function setAttributes($Object, $node) {

        for ($i=0; $i<$node->attributes->length; $i++) {
            $attr = $node->attributes->item($i)->nodeName;
            $setter = "set" . $attr;
            if (method_exists($Object, $setter)) {
                $Object->{$setter}($node->getAttribute($attr));
            }
        }
    }
}
