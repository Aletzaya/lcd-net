<?php
/*
 * Conceptos
 * CFDI versión 4.0
 * CFDI®
 * © 2022, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jan 2022
 */
namespace com\softcoatl\cfdi\v40\schema\Comprobante40;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;
use com\softcoatl\cfdi\Comprobante;

class Impuestos implements CFDIElement, Comprobante\Impuestos {

    /** @var Impuestos\Retenciones */
    private $Retenciones;
    /** @var Impuestos\Traslados */
    private $Traslados;

    private $TotalImpuestosRetenidos;
    private $TotalImpuestosTrasladados;

    public function getRetenciones(): ?Comprobante\Impuestos\Retenciones {
        return $this->Retenciones;
    }

    public function getTraslados(): ?Comprobante\Impuestos\Traslados {
        return $this->Traslados;
    }

    public function getTotalImpuestosRetenidos() {
        return $this->TotalImpuestosRetenidos;
    }

    public function getTotalImpuestosTrasladados() {
        return $this->TotalImpuestosTrasladados;
    }

    public function setRetenciones(Comprobante\Impuestos\Retenciones $Retenciones) {
        $this->Retenciones = $Retenciones;
    }

    public function setTraslados(Comprobante\Impuestos\Traslados $Traslados) {
        $this->Traslados = $Traslados;
    }

    public function setTotalImpuestosRetenidos($TotalImpuestosRetenidos) {
        $this->TotalImpuestosRetenidos = $TotalImpuestosRetenidos;
    }

    public function setTotalImpuestosTrasladados($TotalImpuestosTrasladados) {
        $this->TotalImpuestosTrasladados = $TotalImpuestosTrasladados;
    }

    private function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !empty($val)
                                && !($val instanceof Impuestos\Traslados)
                                && !($val instanceof Impuestos\Retenciones);                    
        });
    }

    public function asXML($root) {

        $Impuestos = $root->ownerDocument->createElement("cfdi:Impuestos");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Impuestos->setAttribute($attr, $value);
        }

        if (!empty($this->Retenciones) && !empty($this->Retenciones->getRetencion())) {
            $Impuestos->appendChild($this->Retenciones->asXML($root));
        }

        if (!empty($this->Traslados) && !empty($this->Traslados->getTraslado())) {
            $Impuestos->appendChild($this->Traslados->asXML($root));
        }

        return $Impuestos;

    }

    public static function parse($DOMImpuestos) {

        $Impuestos = new Impuestos();
        $Impuestos->setTotalImpuestosRetenidos($DOMImpuestos->getAttribute("TotalImpuestosRetenidos"));
        $Impuestos->setTotalImpuestosTrasladados($DOMImpuestos->getAttribute("TotalImpuestosTrasladados"));

        for ($i=0; $i<$DOMImpuestos->childNodes->length; $i++) {
            /* @var $node DOMElement */
            $node = $DOMImpuestos->childNodes->item($i);
            if (strpos($node->nodeName, 'cfdi:Traslados')!==false) {

                $Traslados = new Impuestos\Traslados();
                for ($j=0; $j<$node->childNodes->length; $j++) {

                    $nodeT = $node->childNodes->item($j);
                    if (strpos($nodeT->nodeName, 'cfdi:Traslado')!==false) {
                        $Traslado = new Impuestos\Traslados\Traslado();
                        Reflection::setAttributes($Traslado, $nodeT);
                        $Traslados->addTraslado($Traslado);
                    }
                }
                $Impuestos->setTraslados($Traslados);
            } else if (strpos($node->nodeName, 'cfdi:Retenciones')!==false) {

                $Retenciones = new Impuestos\Retenciones();
                for ($j=0; $j<$node->childNodes->length; $j++) {
                    
                    $nodeR = $node->childNodes->item($j);
                    if (strpos($nodeR->nodeName, 'cfdi:Retencion')!==false) {
                        $Retencion = new Impuestos\Retenciones\Retencion();
                        Reflection::setAttributes($Retencion, $nodeR);
                        $Retenciones->addRetencion($Retencion);
                    }
                }
                $Impuestos->setRetenciones($Retenciones);
            }
        }
        return $Impuestos;
    }
}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Impuestos;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class Retenciones implements CFDIElement, Comprobante\Impuestos\Retenciones {

    /** @var Retenciones\Retencion[] */
    private $Retencion = array();

    public function getRetencion(): array {
        return $this->Retencion;
    }

    public function setRetencion(array $Retencion) {
        $this->Retencion = $Retencion;
    }

    public function addRetencion(Retenciones\Retencion $Retencion) {
        $this->Retencion[] = $Retencion;
    }

    public function asXML($root) {

        $Retenciones = $root->ownerDocument->createElement("cfdi:Retenciones");
        foreach ($this->Retencion as $retencion) {
            $Retenciones->appendChild($retencion->asXML($root));
        }
        return $Retenciones;
    }
}

class Traslados implements CFDIElement, Comprobante\Impuestos\Traslados {

    /** @var Traslados\Traslado[] */
    private $Traslado = array();

    public function getTraslado(): array {
        return $this->Traslado;
    }

    public function setTraslado(array $Traslado) {
        $this->Traslado = $Traslado;
    }

    public function addTraslado(Traslados\Traslado $Traslado) {
        $this->Traslado[] = $Traslado;
    }

    public function asXML($root) {

        $Traslados = $root->ownerDocument->createElement("cfdi:Traslados");
        foreach ($this->Traslado as $traslado) {
            $Traslados->appendChild($traslado->asXML($root));
        }
        return $Traslados;
    }    
}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Impuestos\Traslados;

use com\softcoatl\cfdi\CFDIElement;

class Traslado implements CFDIElement {

    private $Base;
    private $Impuesto;
    private $TipoFactor;
    private $TasaOCuota;
    private $Importe;

    public function getBase() {
        return $this->Base;
    }

    public function getImpuesto() {
        return $this->Impuesto;
    }

    public function getTipoFactor() {
        return $this->TipoFactor;
    }

    public function getTasaOCuota() {
        return $this->TasaOCuota;
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function setBase($Base) {
        $this->Base = $Base;
    }

    public function setImpuesto($Impuesto) {
        $this->Impuesto = $Impuesto;
    }

    public function setTipoFactor($TipoFactor) {
        $this->TipoFactor = $TipoFactor;
    }

    public function setTasaOCuota($TasaOCuota) {
        $this->TasaOCuota = $TasaOCuota;
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function asXML($root) {

        $Traslado = $root->ownerDocument->createElement("cfdi:Traslado");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr=>$value) {
            $Traslado->setAttribute($attr, $value);
        }
        return $Traslado;
    }
}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Impuestos\Retenciones;

use com\softcoatl\cfdi\CFDIElement;

class Retencion implements CFDIElement {

    private $Impuesto;
    private $Importe;

    public function getImpuesto() {
        return $this->Impuesto;
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function setImpuesto($Impuesto) {
        $this->Impuesto = $Impuesto;
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function asXML($root) {

        $Retencion = $root->ownerDocument->createElement("cfdi:Retencion");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr=>$value) {
            $Retencion->setAttribute($attr, $value);
        }
        return $Retencion;
    }
}
