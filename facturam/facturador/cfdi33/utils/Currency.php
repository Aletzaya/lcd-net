<?php

/*
 * Currency
 * cfdi®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace com\softcoatl;

/**
 * Description of Currency
 *
 * @author Rolando Esquivel
 */
class Currency {

    private $pluralCurrency;
    private $singularCurrency;

    function __construct($pluralCurrency, $singularCurrency) {
        $this->pluralCurrency = $pluralCurrency;
        $this->singularCurrency = $singularCurrency;
    }

    function getPluralCurrency() {
        return $this->pluralCurrency;
    }

    function getSingularCurrency() {
        return $this->singularCurrency;
    }

    function setPluralCurrency($pluralCurrency) {
        $this->pluralCurrency = $pluralCurrency;
    }

    function setSingularCurrency($singularCurrency) {
        $this->singularCurrency = $singularCurrency;
    }    
}//Currency
