<?php
/*
 * Conceptos
 * CFDI versión 4.0
 * CFDI®
 * © 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @version 1.0
 * @since jan 2022
 */
namespace com\softcoatl\cfdi\v40\schema\Comprobante40;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;
use com\softcoatl\cfdi\Comprobante;

class Conceptos implements CFDIElement, Comprobante\Conceptos {

    /** @var Conceptos\Concepto[] */
    private $Concepto = array();

    public function getConcepto() {
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

    public static function parse($DOMConceptos) {

        $Conceptos = new Conceptos();
        for ($i=0; $i<$DOMConceptos->childNodes->length; $i++) {

            $node = $DOMConceptos->childNodes->item($i);
            if (strpos($node->nodeName, 'cfdi:Concepto')!==false) {

                $Concepto = new Conceptos\Concepto();
                Reflection::setAttributes($Concepto, $node);
                for ($j=0; $j<$node->childNodes->length; $j++) {

                    $nodeC = $node->childNodes->item($j);
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
                        $Concepto->setImpuestos($Impuestos);
                    } else if (strpos($nodeC->nodeName, 'cfdi:ACuentaDeTerceros')!==false) {
                        $ACuentaDeTerceros = new Conceptos\Concepto\ACuentaTerceros();
                        Reflection::setAttributes($ACuentaDeTerceros, $nodeC);
                        $Concepto->setACuentaTerceros($ACuentaTerceros);
                    } else if (strpos($nodeC->nodeName, 'cfdi:InformacionAduanera')!==false) {
                        $InformacionAduanera = new Conceptos\Concepto\InformacionAduanera();
                        Reflection::setAttributes($InformacionAduanera, $nodeC);
                        $Concepto->addInformacionAduanera($InformacionAduanera);
                    } else if (strpos($nodeC->nodeName, 'cfdi:CuentaPredial')!==false) {
                        $CuentaPredial = new Conceptos\Concepto\CuentaPredial();
                        Reflection::setAttributes($CuentaPredial, $nodeC);
                        $Concepto->addInformacionAduanera($CuentaPredial);
                    } else if (strpos($nodeC->nodeName, 'cfdi:ComplementoConcepto')!==false) {
                        Conceptos::unmarshallComplemento($Concepto, $nodeC);
                    } else if (strpos($nodeC->nodeName, 'cfdi:Parte')!==false) {
                        $Parte = new Conceptos\Concepto\Parte();
                        Reflection::setAttributes($Parte, $nodeC);
                        $Concepto->addParte($Parte);
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
           if (strpos($DOMComplemento->nodeName, ':iedu')) {
               $Concepto->addComplementoConcepto(Conceptos\Concepto\complemento\IEDU::parse($DOMComplemento));
           }
       }
    }
}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Conceptos;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class Concepto implements CFDIElement, Comprobante\Conceptos\Concepto {

    /** @var Concepto\Impuestos  */
    private $Impuestos;
    /** @var Concepto\ACuentaTerceros */
    private $ACuentaTerceros;
    /** @var Concepto\InformacionAduanera[] */
    private $InformacionAduanera = array();
    /** @var Concepto\CuentaPredial[] */
    private $CuentaPredial = array();
    /** @var Concepto\ComplementoConcepto */
    private $ComplementoConcepto;
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
    private $ObjetoImp;

    public function getImpuestos() {
        return $this->Impuestos;
    }

    public function getACuentaTerceros() {
        return $this->ACuentaTerceros;
    }

    public function getInformacionAduanera() {
        return $this->InformacionAduanera;
    }

    public function getCuentaPredial() {
        return $this->CuentaPredial;
    }

    public function getComplementoConcepto() {
        return $this->ComplementoConcepto;
    }

    public function getParte() {
        return $this->Parte;
    }

    public function getClaveProdServ() {
        return $this->ClaveProdServ;
    }

    public function getNoIdentificacion() {
        return $this->NoIdentificacion;
    }

    public function getCantidad() {
        return $this->Cantidad;
    }

    public function getClaveUnidad() {
        return $this->ClaveUnidad;
    }

    public function getUnidad() {
        return $this->Unidad;
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function getValorUnitario() {
        return $this->ValorUnitario;
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function getDescuento() {
        return $this->Descuento;
    }

    public function getObjetoImp() {
        return $this->ObjetoImp;
    }

    public function setImpuestos(Comprobante\Conceptos\Concepto\Impuestos $Impuestos) {
        $this->Impuestos = $Impuestos;
    }

    public function setACuentaTerceros(Concepto\ACuentaTerceros $ACuentaTerceros) {
        $this->ACuentaTerceros = $ACuentaTerceros;
    }

    public function setInformacionAduanera(array $InformacionAduanera) {
        $this->InformacionAduanera = $InformacionAduanera;
    }

    public function addInformacionAduanera(Concepto\InformacionAduanera $InformacionAduanera) {
        $this->InformacionAduanera[] = $InformacionAduanera;
    }

    public function setCuentaPredial(array $CuentaPredial) {
        $this->CuentaPredial = $CuentaPredial;
    }

    public function addCuentaPredial(Concepto\CuentaPredial $CuentaPredial) {
        $this->CuentaPredial[] = $CuentaPredial;
    }

    public function setComplementoConcepto(Concepto\ComplementoConcepto $ComplementoConcepto) {
        $this->ComplementoConcepto = $ComplementoConcepto;
    }

    public function setParte(array $Parte) {
        $this->Parte = $Parte;
    }

    public function addParte(Concepto\Parte $Parte) {
        $this->Parte[] = $Parte;
    }

    public function setClaveProdServ($ClaveProdServ) {
        $this->ClaveProdServ = $ClaveProdServ;
    }

    public function setNoIdentificacion($NoIdentificacion) {
        $this->NoIdentificacion = $NoIdentificacion;
    }

    public function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    public function setClaveUnidad($ClaveUnidad) {
        $this->ClaveUnidad = $ClaveUnidad;
    }

    public function setUnidad($Unidad) {
        $this->Unidad = $Unidad;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    public function setValorUnitario($ValorUnitario) {
        $this->ValorUnitario = $ValorUnitario;
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function setDescuento($Descuento) {
        $this->Descuento = $Descuento;
    }

    public function setObjetoImp($ObjetoImp) {
        $this->ObjetoImp = $ObjetoImp;
    }

    private function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Concepto\ACuentaTerceros)
                                && !($val instanceof Concepto\Impuestos)
                                && !($val instanceof Concepto\ComplementoConcepto);
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

        if ($this->ACuentaTerceros !== NULL) {
            $Concepto->appendChild($this->ACuentaTerceros->asXML($root));
        }

        if (!empty($this->InformacionAduanera)) {

            $informacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
            foreach ($this->InformacionAduanera as $parte) {
                $informacionAduanera->appendChild($parte->asXML($root));
            }

            $Concepto->appendChild($informacionAduanera);
        }

        if (!empty($this->CuentaPredial)) {

            $cuentaPredial = $root->ownerDocument->createElement("cfdi:CuentaPredial");
            /* @var $parte InformacionAduanera */
            foreach ($this->CuentaPredial as $cuenta) {
                $cuentaPredial->appendChild($cuenta->asXML($root));
            }

            $Concepto->appendChild($cuentaPredial);
        }

        if ($this->ComplementoConcepto !== NULL) {
            $Concepto->appendChild($this->ComplementoConcepto->asXML($root));
        }

        if (!empty($this->Parte)) {

            $partes = $root->ownerDocument->createElement("cfdi:Parte");
            /* @var $parte Parte */
            foreach ($this->Parte as $parte) {
                $partes->appendChild($parte->asXML($root));
            }
            $Concepto->appendChild($partes);
        }

        return $Concepto;
    }
}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Conceptos\Concepto;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class ACuentaTerceros implements CFDIElement {

    private $RfcACuentaTerceros;
    private $NombreACuentaTerceros;
    private $RegimenFiscalACuentaTerceros;
    private $DomicilioFiscalACuentaTerceros;

    public function getRfcACuentaTerceros() {
        return $this->RfcACuentaTerceros;
    }

    public function getNombreACuentaTerceros() {
        return $this->NombreACuentaTerceros;
    }

    public function getRegimenFiscalACuentaTerceros() {
        return $this->RegimenFiscalACuentaTerceros;
    }

    public function getDomicilioFiscalACuentaTerceros() {
        return $this->DomicilioFiscalACuentaTerceros;
    }

    public function setRfcACuentaTerceros($RfcACuentaTerceros) {
        $this->RfcACuentaTerceros = $RfcACuentaTerceros;
    }

    public function setNombreACuentaTerceros($NombreACuentaTerceros) {
        $this->NombreACuentaTerceros = $NombreACuentaTerceros;
    }

    public function setRegimenFiscalACuentaTerceros($RegimenFiscalACuentaTerceros) {
        $this->RegimenFiscalACuentaTerceros = $RegimenFiscalACuentaTerceros;
    }

    public function setDomicilioFiscalACuentaTerceros($DomicilioFiscalACuentaTerceros) {
        $this->DomicilioFiscalACuentaTerceros = $DomicilioFiscalACuentaTerceros;
    }

    public function asXML($root) {

        $ACuentaDeTerceros = $root->ownerDocument->createElement("cfdi:ACuentaDeTerceros");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr => $value) {
            $ACuentaDeTerceros->setAttribute($attr, $value);
        }
        return $ACuentaDeTerceros;
    }
}

class ComplementoConcepto implements CFDIElement {

    private $any = array();

    public function getAny() {
        return $this->any;
    }

    public function addAny($any) {
        $this->any[] = $any;
    }

    public function asXML($root) {
        $ComplementoConcepto = $root->ownerDocument->createElement("cfdi:ComplementoConcepto");
        foreach ($this->any as $item) {
            $ComplementoConcepto->appendChild($item->asXML($root));
        }
    }
}

class CuentaPredial implements CFDIElement {

    private $Numero;

    public function asXML($root) {

        $CuentaPredial = $root->ownerDocument->createElement("cfdi:CuentaPredial");
        $CuentaPredial->setAttribute("Numero", $this->Numero);
        return $CuentaPredial;
    }
}

class InformacionAduanera implements CFDIElement {

    private $NumeroPedimento;
    
    public function getNumeroPedimento() {
        return $this->NumeroPedimento;
    }

    public function setNumeroPedimento($NumeroPedimento) {
        $this->NumeroPedimento = $NumeroPedimento;
    }

    public function asXML($root) {
        $InformacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
        $InformacionAduanera->setAttribute("NumeroPedimento", $this->NumeroPedimento);
        return $InformacionAduanera;
    }
}

class Parte implements CFDIElement {

    /** @var Parte\InformacionAduanera */
    private $InformacionAduanera;
    private $ClaveProdServ;
    private $NoIdentificacion;
    private $Cantidad;
    private $Unidad;
    private $Descripcion;
    private $ValorUnitario;
    private $Importe;

    public function getInformacionAduanera() {
        return $this->InformacionAduanera;
    }

    public function getClaveProdServ() {
        return $this->ClaveProdServ;
    }

    public function getNoIdentificacion() {
        return $this->NoIdentificacion;
    }

    public function getCantidad() {
        return $this->Cantidad;
    }

    public function getUnidad() {
        return $this->Unidad;
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function getValorUnitario() {
        return $this->ValorUnitario;
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function setInformacionAduanera(Parte\InformacionAduanera $InformacionAduanera) {
        $this->InformacionAduanera = $InformacionAduanera;
    }

    public function setClaveProdServ($ClaveProdServ) {
        $this->ClaveProdServ = $ClaveProdServ;
    }

    public function setNoIdentificacion($NoIdentificacion) {
        $this->NoIdentificacion = $NoIdentificacion;
    }

    public function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    public function setUnidad($Unidad) {
        $this->Unidad = $Unidad;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    public function setValorUnitario($ValorUnitario) {
        $this->ValorUnitario = $ValorUnitario;
    }

    public function setImporte($Importe) {
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

    public function getTraslados() {
        return $this->Traslados;
    }

    public function getRetenciones() {
        return $this->Retenciones;
    }

    public function setTraslados(Comprobante\Conceptos\Concepto\Impuestos\Traslados $Traslados) {
        $this->Traslados = $Traslados;
    }

    public function setRetenciones(Comprobante\Conceptos\Concepto\Impuestos\Retenciones $Retenciones) {
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

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Conceptos\Concepto\Impuestos;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;

class Retenciones implements CFDIElement, Comprobante\Conceptos\Concepto\Impuestos\Retenciones {

    /** @var Retenciones\Retencion[] */
    private $Retencion= array();

    public function getRetencion() {
        return $this->Retencion;
    }

    public function setRetencion(array $Retencion) {
        $this->Retencion = $Retencion;
    }

    public function addRetencion(Retenciones\Retencion $Retencion) {
        $this->Retencion[] = $Retencion;
    }

    public function asXML($root) {
        $Retencion = $root->ownerDocument->createElement("cfdi:Retenciones");
        /* @var $retencion Retenciones\Retencion */
        foreach ($this->Retencion as $retencion) {
            $Retencion->appendChild($retencion->asXML($root));
        }
        return $Retencion;
    }
}

class Traslados implements CFDIElement, Comprobante\Conceptos\Concepto\Impuestos\Traslados {

    /** @var Traslados\Traslado[] */
    private $Traslado = array();

    public function getTraslado() {
        return $this->Traslado;
    }

    public function setTraslado(array $Traslado) {
        $this->Traslado = $Traslado;
    }

    public function addTraslado(Traslados\Traslado $Traslado) {
        $this->Traslado[] = $Traslado;
    }

    public function asXML($root) {
        $Traslado = $root->ownerDocument->createElement("cfdi:Traslados");
        /* @var $retencion Traslados\Traslado */
        foreach ($this->Traslado as $traslado) {
            $Traslado->appendChild($traslado->asXML($root));
        }
        return $Traslado;
    }
}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Conceptos\Concepto\Impuestos\Retenciones;

use com\softcoatl\cfdi\CFDIElement;

class Retencion implements CFDIElement {

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

        $Retencion = $root->ownerDocument->createElement("cfdi:Retencion");
        $ov = array_filter(get_object_vars($this));

        foreach ($ov as $attr=>$value) {
            $Retencion->setAttribute($attr, $value);
        }

        return $Retencion;
    }

}

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Conceptos\Concepto\Impuestos\Traslados;

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

namespace com\softcoatl\cfdi\v40\schema\Comprobante40\Conceptos\Concepto\Parte;

use com\softcoatl\cfdi\CFDIElement;

class InformacionAduanera implements CFDIElement {

    private $NumeroPedimento;
    
    public function getNumeroPedimento() {
        return $this->NumeroPedimento;
    }

    public function setNumeroPedimento($NumeroPedimento) {
        $this->NumeroPedimento = $NumeroPedimento;
    }

    public function asXML($root) {
        $InformacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
        $InformacionAduanera->setAttribute("NumeroPedimento", $this->NumeroPedimento);
        return $InformacionAduanera;
    }
}