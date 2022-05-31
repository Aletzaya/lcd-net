<?php
/*
 * Pagos10
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */
namespace com\softcoatl\cfdi\complemento\pagos;

require_once ("Pagos.php");

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;
use com\softcoatl\cfdi\complemento\pagos\Pagos;

class Pagos10 implements CFDIElement, Pagos {

    private $Version = "1.0";
    /** @var Pagos10\Pago[] */
    private $Pago = array();

    public function getVersion() {
        return $this->Version;
    }

    public function getPago(): array {
        return $this->Pago;
    }

    public function getTotales(): ?Pagos\Totales {
        return null;
    }

    public function setVersion($version) {
        $this->Version = $version;
    }

    public function setPago(array $Pago) {
        $this->Pago = $Pago;
    }

    public function addPago(Pagos10\Pago $pago) {
        $this->Pago[] = $pago;
    }

    public function setTotales(?Pagos\Totales $totales) {
        
    }

    public function asXML($root) {

        if ($root->ownerDocument->documentElement
                && empty($root->ownerDocument->documentElement->attributes->getNamedItem("xmlns:pago10"))) {
            $root->ownerDocument->documentElement->setAttribute("xmlns:pago10", "http://www.sat.gob.mx/Pagos");
            $root->ownerDocument->documentElement->setAttribute("xsi:schemaLocation", 
                            $root->ownerDocument->documentElement->getAttribute("xsi:schemaLocation") 
                        .   " http://www.sat.gob.mx/Pagos http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos10.xsd");
        }
        $Pagos = $root->ownerDocument->createElement("pago10:Pagos");
        $Pagos->setAttribute("Version", $this->Version);
        if (!empty($this->Pago)) {
            foreach ($this->Pago as $pago) {
                $Pagos->appendChild($pago->asXML($root));
            }
        }
        return $Pagos;
    }

    public static function parse($DOMPagos) {

        if (strpos($DOMPagos->nodeName, ':Pagos')) {
            $Pagos = new Pagos10();
            $Pagos->setVersion($DOMPagos->getAttribute('Version'));

            for ($i=0; $i<$DOMPagos->childNodes->length; $i++) {
                $node = $DOMPagos->childNodes->item($i);
                if (strpos($node->nodeName, ':Pago')!==false) {

                    $Pago = new Pagos10\Pago();
                    Reflection::setAttributes($Pago, $node);
                    for ($j=0; $j<$node->childNodes->length; $j++) {

                        $nodeP = $node->childNodes->item($j);
                        if (strpos($nodeP->nodeName, ':DoctoRelacionado')!==false) {

                            $DoctoRelacionado = new Pagos10\Pago\DoctoRelacionado();
                            Reflection::setAttributes($DoctoRelacionado, $nodeP);
                            $Pago->addDoctoRelacionado($DoctoRelacionado);
                        } else
                        if (strpos($nodeP->nodeName, ':Impuestos')!==false) {

                            $Impuestos = new Pagos10\Pago\Impuestos();
                            Reflection::setAttributes($Impuestos, $nodeP);
                            for($j=0; $j<$nodeP->childNodes->length; $j++) {

                                $nodeI = $nodeP->childNodes->item($j);
                                if (strpos($nodeP->nodeName, ':Retenciones')!==false) {

                                    $Retenciones = new Pagos10\Pago\Impuestos\Retenciones();
                                    for($k=0; $j<$nodeI->childNodes->length; $k++) {
                                        $nodeR = $nodeI->childNodes->item($k);
                                        if (strpos($nodeR->nodeName, ':Retencion')!==false) {
                                            $Retencion = new Pagos10\Pago\Impuestos\Retenciones\Retencion();
                                            Reflection::setAttributes($Retencion, $nodeR);
                                            $Retenciones->addRetencion($Retencion);
                                        }
                                    }
                                    $Impuestos->setRetenciones($Retenciones);
                                } else 
                                if (strpos($nodeP->nodeName, ':Traslados')!==false) {

                                    $Traslados = new Pagos10\Pago\Impuestos\Traslados();
                                    for($k=0; $j<$nodeI->childNodes->length; $k++) {
                                        $nodeT = $nodeI->childNodes->item($k);
                                        if (strpos($nodeT->nodeName, ':Traslado')!==false) {
                                            $Traslado = new Pagos10\Pago\Impuestos\Traslados\Traslado();
                                            Reflection::setAttributes($Traslado, $nodeT);
                                            $Traslados->addTraslado($Traslado);
                                        }
                                    }
                                }
                            }
                            $Pago->addImpuestos($Impuestos);
                        }
                    }
                    $Pagos->addPago($Pago);
                }
            }
            return $Pagos;
        }
        return false;
    }
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos10;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\complemento\pagos\Pagos;

class Pago implements CFDIElement, Pagos\Pago {

    /** @var Pago\DoctoRelacionado[] */
    private $DoctoRelacionado = array();
    /** @var Pago\Impuestos */
    private $Impuestos;

    private $FechaPago;
    private $FormaDePagoP;
    private $MonedaP;
    private $TipoCambioP;
    private $Monto;
    private $NumOperacion;
    private $RfcEmisorCtaOrd;
    private $NomBancoOrdExt;
    private $CtaOrdenante;
    private $RfcEmisorCtaBen;
    private $CtaBeneficiario;
    private $TipoCadPago;
    private $CertPago;
    private $CadPago;
    private $SelloPago;

    public function getDoctoRelacionado(): array {
        return $this->DoctoRelacionado;
    }

    public function getImpuestos(): Pago\Impuestos {
        return $this->Impuestos;
    }

    public function getImpuestosP(): Pagos\Pago\ImpuestosP {
        return $this->getImpuestos();
    }

    public function getFechaPago() {
        return $this->FechaPago;
    }

    public function getFormaDePagoP() {
        return $this->FormaDePagoP;
    }

    public function getMonedaP() {
        return $this->MonedaP;
    }

    public function getTipoCambioP() {
        return $this->TipoCambioP;
    }

    public function getMonto() {
        return $this->Monto;
    }

    public function getNumOperacion() {
        return $this->NumOperacion;
    }

    public function getRfcEmisorCtaOrd() {
        return $this->RfcEmisorCtaOrd;
    }

    public function getNomBancoOrdExt() {
        return $this->NomBancoOrdExt;
    }

    public function getCtaOrdenante() {
        return $this->CtaOrdenante;
    }

    public function getRfcEmisorCtaBen() {
        return $this->RfcEmisorCtaBen;
    }

    public function getCtaBeneficiario() {
        return $this->CtaBeneficiario;
    }

    public function getTipoCadPago() {
        return $this->TipoCadPago;
    }

    public function getCertPago() {
        return $this->CertPago;
    }

    public function getCadPago() {
        return $this->CadPago;
    }

    public function getSelloPago() {
        return $this->SelloPago;
    }

    public function setDoctoRelacionado(array $DoctoRelacionado) {
        $this->DoctoRelacionado = $DoctoRelacionado;
    }

    public function addDoctoRelacionado(Pago\DoctoRelacionado $DoctoRelacionado) {
        $this->DoctoRelacionado[] = $DoctoRelacionado;
    }

    public function setImpuestos(Pago\Impuestos $Impuestos) {
        $this->Impuestos = $Impuestos;
    }

    public function setImpuestosP(Pagos\Pago\ImpuestosP $ImpuestosP) {
        $this->setImpuestos($ImpuestosP);
    }

    public function setFechaPago($FechaPago) {
        $this->FechaPago = $FechaPago;
    }

    public function setFormaDePagoP($FormaDePagoP) {
        $this->FormaDePagoP = $FormaDePagoP;
    }

    public function setMonedaP($MonedaP) {
        $this->MonedaP = $MonedaP;
    }

    public function setTipoCambioP($TipoCambioP) {
        $this->TipoCambioP = $TipoCambioP;
    }

    public function setMonto($Monto) {
        $this->Monto = $Monto;
    }

    public function setNumOperacion($NumOperacion) {
        $this->NumOperacion = $NumOperacion;
    }

    public function setRfcEmisorCtaOrd($RfcEmisorCtaOrd) {
        $this->RfcEmisorCtaOrd = $RfcEmisorCtaOrd;
    }

    public function setNomBancoOrdExt($NomBancoOrdExt) {
        $this->NomBancoOrdExt = $NomBancoOrdExt;
    }

    public function setCtaOrdenante($CtaOrdenante) {
        $this->CtaOrdenante = $CtaOrdenante;
    }

    public function setRfcEmisorCtaBen($RfcEmisorCtaBen) {
        $this->RfcEmisorCtaBen = $RfcEmisorCtaBen;
    }

    public function setCtaBeneficiario($CtaBeneficiario) {
        $this->CtaBeneficiario = $CtaBeneficiario;
    }

    public function setTipoCadPago($TipoCadPago) {
        $this->TipoCadPago = $TipoCadPago;
    }

    public function setCertPago($CertPago) {
        $this->CertPago = $CertPago;
    }

    public function setCadPago($CadPago) {
        $this->CadPago = $CadPago;
    }

    public function setSelloPago($SelloPago) {
        $this->SelloPago = $SelloPago;
    }

    private function getVarArray() {
        return array_filter(get_object_vars($this), 
            function ($val) { 
                return !is_array($val) 
                    && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val));
            });
    }

    public function asXML($root) {

        $Pago = $root->ownerDocument->createElement("pago10:Pago");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Pago->setAttribute($attr, $value);
        }

        if ($this->DoctoRelacionado !== NULL) {
            foreach ($this->DoctoRelacionado as $docto) {
                $Pago->appendChild($docto->asXML($root));
            }
        }

        return $Pago;
    }
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos10\Pago;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\complemento\pagos\Pagos;

class DoctoRelacionado implements CFDIElement, Pagos\Pago\DoctoRelacionado {

    private $IdDocumento;
    private $Serie;
    private $Folio;
    private $MonedaDR;
    private $TipoCambioDR;
    private $MetodoDePagoDR;
    private $NumParcialidad;
    private $ImpSaldoAnt;
    private $ImpPagado;
    private $ImpSaldoInsoluto;

    public function getImpuestosDR(): ?Pagos\Pago\DoctoRelacionado\ImpuestosDR {
        return null;
    }

    public function getIdDocumento() {
        return $this->IdDocumento;
    }

    public function getSerie() {
        return $this->Serie;
    }

    public function getFolio() {
        return $this->Folio;
    }

    public function getMonedaDR() {
        return $this->MonedaDR;
    }

    public function getEquivalenciaDR() {
        return $this->getTipoCambioDR();
    }

    public function getTipoCambioDR() {
        return $this->TipoCambioDR;
    }

    public function getMetodoDePagoDR() {
        return $this->MetodoDePagoDR;
    }

    public function getNumParcialidad() {
        return $this->NumParcialidad;
    }

    public function getImpSaldoAnt() {
        return $this->ImpSaldoAnt;
    }

    public function getImpPagado() {
        return $this->ImpPagado;
    }

    public function getImpSaldoInsoluto() {
        return $this->ImpSaldoInsoluto;
    }

    public function getObjetoImpDR() {
        return null;
    }

    public function setImpuestosDR(?Pagos\Pago\DoctoRelacionado\ImpuestosDR $ImpuestosDR) {
        
    }

    public function setIdDocumento($IdDocumento) {
        $this->IdDocumento = $IdDocumento;
    }

    public function setSerie($Serie) {
        $this->Serie = $Serie;
    }

    public function setFolio($Folio) {
        $this->Folio = $Folio;
    }

    public function setMonedaDR($MonedaDR) {
        $this->MonedaDR = $MonedaDR;
    }

    public function setTipoCambioDR($TipoCambioDR) {
        $this->TipoCambioDR = $TipoCambioDR;
    }

    public function setEquivalenciaDR($EquivalenciaDR) {
        $this->setTipoCambioDR($TipoCambioDR);
    }

    public function setMetodoDePagoDR($MetodoDePagoDR) {
        $this->MetodoDePagoDR = $MetodoDePagoDR;
    }

    public function setNumParcialidad($NumParcialidad) {
        $this->NumParcialidad = $NumParcialidad;
    }

    public function setImpSaldoAnt($ImpSaldoAnt) {
        $this->ImpSaldoAnt = $ImpSaldoAnt;
    }

    public function setImpPagado($ImpPagado) {
        $this->ImpPagado = $ImpPagado;
    }

    public function setImpSaldoInsoluto($ImpSaldoInsoluto) {
        $this->ImpSaldoInsoluto = $ImpSaldoInsoluto;
    }

    public function setObjetoImpDR($ObjetoImpDR) {
        
    }

    public function asXML($root) {

        $DoctoRelacionado = $root->ownerDocument->createElement("pago10:DoctoRelacionado");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr=>$value) {
            $DoctoRelacionado->setAttribute($attr, $value);
        }
        return $DoctoRelacionado;
    }
}

class Impuestos implements CFDIElement, Pagos\Pago\ImpuestosP {

    /** @var Impuestos\Retenciones */
    private $Retenciones;
    /** @var Impuestos\Traslados */
    private $Traslados;

    private $TotalImpuestosRetenidos;
    private $TotalImpuestosTrasladados;
    
    public function getRetenciones() {
        return $this->Retenciones;
    }

    public function getRetencionesP() {
        return $this->getRetenciones();
    }

    public function getTraslados() {
        return $this->Traslados;
    }

    public function getTrasladosP() {
        return $this->getTraslados();
    }

    public function getTotalImpuestosRetenidos() {
        return $this->TotalImpuestosRetenidos;
    }

    public function getTotalImpuestosTrasladados() {
        return $this->TotalImpuestosTrasladados;
    }

    public function setRetenciones(Impuestos\Retenciones $Retenciones) {
        $this->Retenciones = $Retenciones;
    }

    public function setRetencionesP(Pagos\Pago\ImpuestosP\RetencionesP $RetencionesP) {
        $this->setRetenciones($RetencionesP);
    }

    public function setTraslados(Impuestos\Traslados $Traslados) {
        $this->Traslados = $Traslados;
    }

    public function setTrasladosP(Pagos\Pago\ImpuestosP\TrasladosP $TrasladosP) {
        $this->setTraslados($TrasladosP);
    }

    public function setTotalImpuestosRetenidos($TotalImpuestosRetenidos) {
        $this->TotalImpuestosRetenidos = $TotalImpuestosRetenidos;
    }

    public function setTotalImpuestosTrasladados($TotalImpuestosTrasladados) {
        $this->TotalImpuestosTrasladados = $TotalImpuestosTrasladados;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Impuestos\Retenciones)
                                && !($val instanceof Impuestos\Traslados);
        }); 
    }


    public function asXML($root) {
        $Impuestos = $rot->ownerDocument->createElement("pagos10:Impuestos");
        $ov = $this->getVarArray();
        foreach ($ov as $key=>$value) {
            $Impuestos->setAttribute($key, $value);
        }
        if (!empty($this->Retenciones) && !empty($this->Retenciones->getRetencion())) {
            $Impuestos->appendChild($this->Retenciones->asXML($root));
        }
        if (!empty($this->Traslados) && !empty($this->Traslados->getTraslado())) {
            $Impuestos->appendChild($this->Traslados->asXML($root));
        }
        return $Impuestos;
    }
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos10\Pago\Impuestos;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\complemento\pagos\Pagos;

class Retenciones implements CFDIElement, Pagos\Pago\ImpuestosP\RetencionesP {

    /** @var Retenciones\Retencion[]*/
    private $Retencion = array();

    public function getRetencion(): array {
        return $this->Retencion;
    }

    public function getRetencionP(): array {
        return $this->getRetencion();
    }

    public function setRetencion(array $Retencion) {
        $this->Retencion= $Retencion;
    }

    public function setRetencionP(array $RetencionP) {
        $this->setRetencion($RetencionP);
    }

    public function addRetencion(Retenciones\Retencion $Retencion) {
        $this->Retencion[] = $Retencion;
    }

    public function asXML($root) {

        $Retenciones = $root->ownerDocument->createElement("pagos10:Retenciones");
        foreach ($this->Retencion as $Retencion) {
            $Retenciones->appendChild($Retencion->asXML($root));
        }
        return $Retenciones;
    }
}

class Traslados implements CFDIElement, Pagos\Pago\ImpuestosP\TrasladosP {

    /** @var Traslados\Traslado[]*/
    private $Traslado = array();

    public function getTraslado(): array {
        return $this->Traslado;
    }

    public function getTrasladoP(): array {
        return $this->getTraslado();
    }

    public function setTraslado(array $Traslado) {
        $this->Traslado = $Traslado;
    }

    public function setTrasladoP(array $TrasladoP) {
        $this->setTraslado($TrasladoP);
    }

    public function addTraslado(Traslados\Traslado $Traslado) {
        $this->Traslado[] = $Traslado;
    }

    public function asXML($root) {

        $Traslados = $root->ownerDocument->createElement("pagos10:Retenciones");
        foreach ($this->Traslado as $Traslado) {
            $Traslados->appendChild($Traslado->asXML($root));
        }
        return $Traslados;
    }
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos10\Pago\Impuestos\Retenciones;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\complemento\pagos\Pagos;

class Retencion implements CFDIElement, Pagos\Pago\ImpuestosP\RetencionesP\RetencionP {

    private $Impuesto;
    private $Importe;

    public function getImpuesto() {
        return $this->Impuesto;
    }

    public function getImpuestoP() {
        return $this->getImpuesto();
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function getImporteP() {
        return $this->getImporte();
    }

    public function setImpuesto($Impuesto) {
        $this->Impuesto = $Impuesto;
    }

    public function setImpuestoP($ImpuestoP) {
        $this->setImpuesto($ImpuestoP);
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function setImporteP($ImporteP) {
        $this->setImporte($ImporteP);
    }

    public function asXML($root) {
        $Retencion = $root->ownerDocument->createElement("pagos10:Retencion");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $Retencion->setAttribute($key, $value);
        }
        return $Retencion;
    }
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos10\Pago\Impuestos\Traslados;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\complemento\pagos\Pagos;

class Traslado implements CFDIElement, Pagos\Pago\ImpuestosP\TrasladosP\TrasladoP {

    private $Impuesto;
    private $TipoFactor;
    private $TasaOCuota;
    private $Importe;

    public function getImpuesto() {
        return $this->Impuesto;
    }

    public function getImpuestoP() {
        return $this->getImpuesto();
    }

    public function getTipoFactor() {
        return $this->TipoFactor;
    }

    public function getTipoFactorP() {
        return $this->getTipoFactor();
    }

    public function getTasaOCuota() {
        return $this->TasaOCuota;
    }

    public function getTasaOCuotaP() {
        return $this->getTasaOCuota();
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function getImporteP() {
        return $this->getImporte();
    }

    public function setImpuesto($Impuesto) {
        $this->Impuesto = $Impuesto;
    }

    public function setImpuestoP($ImpuestoP) {
        $this->setImpuesto($ImpuestoP);
    }

    public function setTipoFactor($TipoFactor) {
        $this->TipoFactor = $TipoFactor;
    }

    public function setTipoFactorP($TipoFactorP) {
        $this->setTipoFactor($TipoFactorP);
    }

    public function setTasaOCuota($TasaOCuota) {
        $this->TasaOCuota = $TasaOCuota;
    }

    public function setTasaOCuotaP($TasaOCuotaP) {
        $this->setTasaOCuota($TasaOCuotaP);
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function setImporteP($ImporteP) {
        $this->setImporte($ImporteP);
    }

    public function asXML($root) {
        $Traslado = $root->ownerDocument->createElement("pagos10:Retencion");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $Traslado->setAttribute($key, $value);
        }
        return $Traslado;
    }
}
