<?php
/*
 * Conceptos
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */
namespace com\softcoatl\cfdi\v33\schema\Comprobante33;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;
use com\softcoatl\cfdi\Comprobante;

class Conceptos implements CFDIElement, Comprobante\Conceptos {

    public static $ARR_COMPLEMENTO = array();

    /** @var Conceptos\Concepto[] */
    private $Concepto = array();

    public static function registerComplemento($complemento) {
        self::$ARR_COMPLEMENTO[] = $complemento;
    }
    
    public function getConcepto(): array {
        return $this->Concepto;
    }

    public function setConcepto(array $Concepto) {
        $this->Concepto = $Concepto;
    }

    public function addConcepto(Comprobante\Conceptos\Concepto $Concepto) {
        $this->Concepto[] = $Concepto;
    }

    public function asXML($root) {

        $Conceptos = $root->ownerDocument->createElement("cfdi:Conceptos");
        foreach ($this->Concepto as $concepto) {
            $Conceptos->appendChild($concepto->asXML($root));
        }
        return $Conceptos;
    }

    public static function parse(\DOMNode $DOMConceptos) {

        $Conceptos = new Conceptos();
        for ($i=0; $i<$DOMConceptos->childNodes->length; $i++) {

            $node = $DOMConceptos->childNodes->item($i);
            if (strpos($node->nodeName, 'cfdi:Concepto')!==false) {

                $Concepto = new Conceptos\Concepto();
                Reflection::setAttributes($Concepto, $node);
                if ($node->hasChildNodes()) {

                    for ($j=0; $j<$node->childNodes->length; $j++) {

                        $nodeC = $node->childNodes->item($j);
                        if (strpos($nodeC->nodeName, 'cfdi:ComplementoConcepto')!==false) {
                            Conceptos::unmarshallComplemento($Concepto, $node);
                        }
                        else 
                        if (strpos($nodeC->nodeName, 'cfdi:Impuestos')!==false) {

                            $Impuestos = new Conceptos\Concepto\Impuestos();
                            for ($k=0; $k<$nodeC->childNodes->length; $k++) {

                                $nodeI = $nodeC->childNodes->item($k);
                                if (strpos($nodeI->nodeName, 'cfdi:Traslados')!==false) {

                                    $Traslados = new Conceptos\Concepto\Impuestos\Traslados();
                                    for ($l=0; $l<$nodeI->childNodes->length; $l++) {

                                        $nodeT = $nodeI->childNodes->item($l);
                                        if (strpos($nodeT->nodeName, 'cfdi:Traslado')!==false) {

                                            $Traslado = new Conceptos\Concepto\Impuestos\Traslados\Traslado();
                                            Reflection::setAttributes($Traslado, $nodeT);
                                            $Traslados->addTraslado($Traslado);
                                        }
                                    }
                                    $Impuestos->setTraslados($Traslados);
                                } else if (strpos($nodeI->nodeName, 'cfdi:Retenciones')!==false) {

                                    $Retenciones = new Conceptos\Concepto\Impuestos\Retenciones();
                                    for ($l=0; $l<$nodeI->childNodes->length; $l++) {

                                        $nodeR = $nodeI->childNodes->item($l);
                                        if (strpos($nodeR->nodeName, 'cfdi:Retencion')!==false) {

                                            $Retencion = new Conceptos\Concepto\Impuestos\Retenciones\Retencion();
                                            Reflection::setAttributes($Retencion, $nodeR);
                                            $Retenciones->addRetencion($Retencion);
                                        }
                                    }
                                    $Impuestos->setRetenciones($Retenciones);
                                }
                            }
                        }
                    }
                }
                $Conceptos->addConcepto($Concepto);
            }
        }
        return $Conceptos;
    }

    private static function unmarshallComplemento($Concepto, $DOMComplementos) {

        for ($i=0; $i<$DOMComplementos->childNodes->length; $i++) {
            $DOMComplemento = $DOMComplementos->childNodes->item($i);
            foreach (self::$ARR_COMPLEMENTO as $complemento) {
                $parsed = $complemento::parse($DOMComplemento);
                if ($parsed != false) {
                    $Concepto->addComplementoConcepto($parsed);
                }
            }
        }
    }
}

namespace com\softcoatl\cfdi\v33\schema\Comprobante33\Conceptos;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class Concepto implements CFDIElement, Comprobante\Conceptos\Concepto {

    /** @var Concepto\Impuestos */ 
    private $Impuestos;
    /** @var Concepto\InformacionAduanera[] */
    private $InformacionAduanera = array();
    private $CuentaPredial;
    /** @var CFDIElement[] */
    private $ComplementoConcepto = array();
    /** @var Concepto\Parte[] */
    private $Parte = array();
    private $ClaveProdServ;
    private $NoIdentificacion;
    private $Cantidad;
    private $ClaveUnidad;
    private $Unidad;
    private $Descripcion;
    private $ValorUnitario;
    private $Importe;
    private $Descuento;

    function getImpuestos(): ?Comprobante\Conceptos\Concepto\Impuestos {
        return $this->Impuestos;
    }

    function getInformacionAduanera() {
        return $this->InformacionAduanera;
    }

    function getCuentaPredial() {
        return $this->CuentaPredial;
    }

    function getComplementoConcepto() {
        return $this->ComplementoConcepto;
    }

    function getParte() {
        return $this->Parte;
    }

    function getClaveProdServ() {
        return $this->ClaveProdServ;
    }

    function getNoIdentificacion() {
        return $this->NoIdentificacion;
    }

    function getCantidad() {
        return $this->Cantidad;
    }

    function getClaveUnidad() {
        return $this->ClaveUnidad;
    }

    function getUnidad() {
        return $this->Unidad;
    }

    function getDescripcion() {
        return $this->Descripcion;
    }

    function getValorUnitario() {
        return $this->ValorUnitario;
    }

    function getImporte() {
        return $this->Importe;
    }

    function getDescuento() {
        return $this->Descuento;
    }

    function setImpuestos(Comprobante\Conceptos\Concepto\Impuestos $Impuestos) {
        $this->Impuestos = $Impuestos;
    }

    public function setInformacionAduanera(array $InformacionAduanera) {
        $this->InformacionAduanera = $InformacionAduanera;
    }

    function addInformacionAduanera(Concepto\InformacionAduanera $InformacionAduanera) {
        $this->InformacionAduanera[] = $InformacionAduanera;
    }

    function setCuentaPredial($CuentaPredial) {
        $this->CuentaPredial = $CuentaPredial;
    }

    public function setComplementoConcepto(array $ComplementoConcepto) {
        $this->ComplementoConcepto = $ComplementoConcepto;
    }

    function addComplementoConcepto(CFDIElement $ComplementoConcepto) {
        $this->ComplementoConcepto[] = $ComplementoConcepto;
    }

    function setParte(array $Parte) {
        $this->Parte = $Parte;
    }

    function addParte(Concepto\Parte $Parte) {
        $this->Parte[] = $Parte;
    }

    function setClaveProdServ($ClaveProdServ) {
        $this->ClaveProdServ = $ClaveProdServ;
    }

    function setNoIdentificacion($NoIdentificacion) {
        $this->NoIdentificacion = $NoIdentificacion;
    }

    function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    function setClaveUnidad($ClaveUnidad) {
        $this->ClaveUnidad = $ClaveUnidad;
    }

    function setUnidad($Unidad) {
        $this->Unidad = $Unidad;
    }

    function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    function setValorUnitario($ValorUnitario) {
        $this->ValorUnitario = $ValorUnitario;
    }

    function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    function setDescuento($Descuento) {
        $this->Descuento = $Descuento;
    }

    private function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Concepto\Impuestos);                    
        }); 
    }

    public function asXML($root) {

        $Concepto = $root->ownerDocument->createElement("cfdi:Concepto");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Concepto->setAttribute($attr, $value);
        }

        if ($this->Impuestos !== NULL) {
            $Concepto->appendChild($this->Impuestos->asXML($root));
        }

        if (!empty($this->InformacionAduanera)) {

            $informacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
            foreach ($this->InformacionAduanera as $parte) {
                $informacionAduanera->appendChild($parte->asXML($root));
            }

            $Concepto->appendChild($informacionAduanera);
        }

        if (!empty($this->Parte)) {

            $partes = $root->ownerDocument->createElement("cfdi:Parte");
            foreach ($this->Parte as $parte) {
                $partes->appendChild($parte->asXML($root));
            }
            $Concepto->appendChild($partes);
        }

        return $Concepto;
    }
}

namespace com\softcoatl\cfdi\v33\schema\Comprobante33\Conceptos\Concepto;
    
use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class InformacionAduanera implements CFDIElement {

    private $NumeroPedimento;

    function getNumeroPedimento() {
        return $this->NumeroPedimento;
    }

    function setNumeroPedimento($NumeroPedimento) {
        $this->NumeroPedimento = $NumeroPedimento;
    }

    public function asXML($root) {

        $InformacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
        $InformacionAduanera->setAttribute("NumeroPedimento", $this->NumeroPedimento);
        return $InformacionAduanera;
    }
}

class Parte implements CFDIElement {

    /** @var Parte\InformacionAduanera[] */
    private $InformacionAduanera = array();
    private $ClaveProdServ;
    private $NoIdentificacion;
    private $Cantidad;
    private $Unidad;
    private $Descripcion;
    private $ValorUnitario;
    private $Importe;

    function getInformacionAduanera() {
        return $this->InformacionAduanera;
    }

    function getClaveProdServ() {
        return $this->ClaveProdServ;
    }

    function getNoIdentificacion() {
        return $this->NoIdentificacion;
    }

    function getCantidad() {
        return $this->Cantidad;
    }

    function getUnidad() {
        return $this->Unidad;
    }

    function getDescripcion() {
        return $this->Descripcion;
    }

    function getValorUnitario() {
        return $this->ValorUnitario;
    }

    function getImporte() {
        return $this->Importe;
    }

    public function setInformacionAduanera(array $InformacionAduanera) {
        $this->InformacionAduanera = $InformacionAduanera;
    }

    function addInformacionAduanera(Parte\InformacionAduanera $InformacionAduanera) {
        $this->InformacionAduanera[] = $InformacionAduanera;
    }

    function setClaveProdServ($ClaveProdServ) {
        $this->ClaveProdServ = $ClaveProdServ;
    }

    function setNoIdentificacion($NoIdentificacion) {
        $this->NoIdentificacion = $NoIdentificacion;
    }

    function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    function setUnidad($Unidad) {
        $this->Unidad = $Unidad;
    }

    function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    function setValorUnitario($ValorUnitario) {
        $this->ValorUnitario = $ValorUnitario;
    }

    function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    private function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val));
                        });
    }

    public function asXML($root) {

        $Parte = $root->ownerDocument->createElement("cfdi:Parte");
        $ov = $this->getVarArray();

        foreach ($ov as $attr=>$value) {
            $Parte->setAttribute($attr, $value);
        }

        if (!empty($this->InformacionAduanera)) {

            $informacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
            /* @var $parte InformacionAduanera */
            foreach ($this->InformacionAduanera as $parte) {
                $informacionAduanera->appendChild($parte->asXML($root));
            }
            $Parte->appendChild($informacionAduanera);
        }

        return $Parte;
    }
}

class Impuestos implements CFDIElement, Comprobante\Conceptos\Concepto\Impuestos {

    /** @var Impuestos\Traslados */
    private $Traslados;
    /** @var Impuestos\Retenciones */
    private $Retenciones;

    function getTraslados(): ?Comprobante\Conceptos\Concepto\Impuestos\Traslados {
        return $this->Traslados;
    }

    function getRetenciones(): ?Comprobante\Conceptos\Concepto\Impuestos\Retenciones {
        return $this->Retenciones;
    }

    function setTraslados(Comprobante\Conceptos\Concepto\Impuestos\Traslados $Traslados) {
        $this->Traslados = $Traslados;
    }

    function setRetenciones(Comprobante\Conceptos\Concepto\Impuestos\Retenciones $Retenciones) {
        $this->Retenciones = $Retenciones;
    }

    public function asXML($root) {

        $Impuestos = $root->ownerDocument->createElement("cfdi:Impuestos");
        if (!empty($this->Traslados) && !empty($this->Traslados->getTraslado())) {
            $Impuestos->appendChild($this->Traslados->asXML($root));
        }            
        if (!empty($this->Retenciones) && !empty($this->Retenciones->getRetencion())) {
            $Impuestos->appendChild($this->Retenciones->asXML($root));
        }
        return $Impuestos;
    }
}

namespace com\softcoatl\cfdi\v33\schema\Comprobante33\Conceptos\Concepto\Impuestos;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class Traslados implements CFDIElement, Comprobante\Conceptos\Concepto\Impuestos\Traslados {

    /** @var Traslados\Traslado[] */
    private $Traslado = array();
        

    function getTraslado(): array {
        return $this->Traslado;
    }

    public function setTraslado(array $Traslado) {
        $this->Traslado = $Traslado;
    }

    function addTraslado(Traslados\Traslado $Traslado) {
        $this->Traslado[] = $Traslado;
    }

    public function asXML($root) {

        $Traslado = $root->ownerDocument->createElement("cfdi:Traslados");
        foreach ($this->Traslado as $tax) {
            $Traslado->appendChild($tax->asXML($root));
        }
        return $Traslado;
    }

}

class Retenciones implements CFDIElement, Comprobante\Conceptos\Concepto\Impuestos\Retenciones {

    /** @var Retenciones\Retencion[] */
    private $Retencion = array();

    function getRetencion(): array {
        return $this->Retencion;
    }

    public function setRetencion(array $Retencion) {
        $this->Retencion = $Retencion;
    }

    function addRetencion(Retenciones\Retencion $Retencion) {
        $this->Retencion[] = $Retencion;
    }

    public function asXML($root) {

        $Retencion = $root->ownerDocument->createElement("cfdi:Retenciones");
        foreach ($this->Retencion as $tax) {
            $Retencion->appendChild($tax->asXML($root));
        }
        return $Retencion;
    }

}

namespace com\softcoatl\cfdi\v33\schema\Comprobante33\Conceptos\Concepto\Impuestos\Traslados;
    
use com\softcoatl\cfdi\CFDIElement;

class Traslado implements CFDIElement {

    private $Base;
    private $Impuesto;
    private $TipoFactor;
    private $TasaOCuota;
    private $Importe;

    function getBase() {
        return $this->Base;
    }

    function getImpuesto() {
        return $this->Impuesto;
    }

    function getTipoFactor() {
        return $this->TipoFactor;
    }

    function getTasaOCuota() {
        return $this->TasaOCuota;
    }

    function getImporte() {
        return $this->Importe;
    }

    function setBase($Base) {
        $this->Base = $Base;
    }

    function setImpuesto($Impuesto) {
        $this->Impuesto = $Impuesto;
    }

    function setTipoFactor($TipoFactor) {
        $this->TipoFactor = $TipoFactor;
    }

    function setTasaOCuota($TasaOCuota) {
        $this->TasaOCuota = $TasaOCuota;
    }

    function setImporte($Importe) {
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

namespace com\softcoatl\cfdi\v33\schema\Comprobante33\Conceptos\Concepto\Impuestos\Retenciones;
    
use com\softcoatl\cfdi\CFDIElement;

class Retencion implements CFDIElement {

    private $Base;
    private $Impuesto;
    private $TipoFactor;
    private $TasaOCuota;
    private $Importe;

    function getBase() {
        return $this->Base;
    }

    function getImpuesto() {
        return $this->Impuesto;
    }

    function getTipoFactor() {
        return $this->TipoFactor;
    }

    function getTasaOCuota() {
        return $this->TasaOCuota;
    }

    function getImporte() {
        return $this->Importe;
    }

    function setBase($Base) {
        $this->Base = $Base;
    }

    function setImpuesto($Impuesto) {
        $this->Impuesto = $Impuesto;
    }

    function setTipoFactor($TipoFactor) {
        $this->TipoFactor = $TipoFactor;
    }

    function setTasaOCuota($TasaOCuota) {
        $this->TasaOCuota = $TasaOCuota;
    }

    function setImporte($Importe) {
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
