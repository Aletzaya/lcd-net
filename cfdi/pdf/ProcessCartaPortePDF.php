<?php

use com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20;

class ProcessCartaPortePDF {
    
    /** @var CartaPorte20 tapo*/
    private $cartaPorte;
    private $htmlCP;

    public function __construct(CartaPorte20 $cartaPorte) {
        $this->cartaPorte = $cartaPorte;
        $this->htmlCP = 
                "<table cellpadding=\"2px\">"
                . "<thead>"
                . "<tr style=\"background-color: #6BA5D9; color: white; font-weight: bold; font-size: 10; text-align: center;\">"
                .       "<td>Versión</td>"
                .       "<td>Transp Int</td>"
                .       "<td>Entrada/Salida</td>"
                .       "<td>Vía</td>"
                .       "<td>Distancia Recorrida</td>"
                .   "</tr>"
                . "</thead>";
    }

    public function process() {

        $this->htmlCP .= 
                "<tbody>"
                .   "<tr style=\"font-size: 8; text-align: center;\">"
                .       "<td>" . $this->cartaPorte->getVersion() . "</td>"
                .       "<td>" . $this->cartaPorte->getTranspInternac() . "</td>"
                .       "<td>" . $this->cartaPorte->getEntradaSalidaMerc() . "</td>"
                .       "<td>" . $this->cartaPorte->getViaEntradaSalida() . "</td>"
                .       "<td>" . $this->cartaPorte->getTotalDistRec() . "</td>"
                .   "</tr>"
                . "</tbody>";

        return $htmlp;
    }
}
