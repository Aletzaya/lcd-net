<?php

/*
 * Comprobante40DAO
 * GlobalFAE®
 * © 2018, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since feb 2018
 */

namespace com\detisa\cfdi\factory;

require_once ("cfdi/com/softcoatl/cfdi/v40/schema/Comprobante40.php");
require_once ("ComprobanteFactoryIface.php");

use com\softcoatl\cfdi\v40\schema\Comprobante40;
use com\softcoatl\cfdi\Comprobante;
use com\softcoatl\cfdi\CImpuestos;

class Comprobante40Factory implements ComprobanteFactoryIface {

    public function createComprobante(array $rs) {
        $comprobante = new Comprobante40();
        $comprobante->setFolio($rs["Folio"]);
        $comprobante->setSerie($rs["Serie"]);
        $comprobante->setFecha($rs["Fecha"]);
        $comprobante->setTipoDeComprobante($rs["TipoDeComprobante"]);
        $comprobante->setVersion($rs["Version"]);
        $comprobante->setFormaPago($rs["FormaPago"]);
        $comprobante->setMetodoPago($rs["MetodoPago"]);
        $comprobante->setCondicionesDePago($rs["CondicionesDePago"]);
        $comprobante->setMoneda($rs["Moneda"]);
        if ($rs["Moneda"] === "MXN") {
            $comprobante->setTipoCambio("1");
        } else if ($rs["Moneda"] !== "XXX") {
            $comprobante->setTipoCambio($rs["TipoCambio"]);
        }
        $comprobante->setSubTotal($rs["SubTotal"]);
        $comprobante->setTotal($rs["Total"]);
        $comprobante->setExportacion("01");
        $comprobante->setLugarExpedicion($rs["LugarExpedicion"]);

        return $comprobante;
    }

    public function createComprobanteCfdiRelacionados() {
        return new Comprobante40\CfdiRelacionados();
    }

    public function createComprobanteCfdiRelacionadosCfdiRelacionado(array $rs) {
        $cfdiRelacionado = new Comprobante40\CfdiRelacionados\CfdiRelacionado();
        $cfdiRelacionado->setUUID($rs["uuid_relacionado"]);
        return $cfdiRelacionado;
    }

    public function createComprobanteEmisor(array $rs) {

        $emisor = new Comprobante40\Emisor();
        $emisor->setNombre($rs["Nombre"]);
        $emisor->setRfc($rs["Rfc"]);
        $emisor->setRegimenFiscal($rs["RegimenFiscal"]);
        return $emisor;
    }

    public function createComprobanteReceptor(array $rs) {

        $receptor = new Comprobante40\Receptor();
        $receptor->setNombre($rs["Nombre"]);
        $receptor->setRfc($rs["Rfc"]);
        $receptor->setRegimenFiscalReceptor($rs["RegimenFiscalReceptor"]);
        $receptor->setDomicilioFiscalReceptor($rs["DomicilioFiscalReceptor"]);
        $receptor->setUsoCFDI($rs["UsoCFDI"]);
        return $receptor;
    }

    public function createComprobanteReceptorGenerico(array $rs) {

        $receptor = new Comprobante40\Receptor();
        $receptor->setNombre("PUBLICO EN GENERAL");
        $receptor->setDomicilioFiscalReceptor($rs["DomicilioFiscalReceptor"]);
        $receptor->setRegimenFiscalReceptor("616");
        $receptor->setRfc("XAXX010101000");
        $receptor->setUsoCFDI("S01");
        return $receptor;
    }

    public function createComprobanteConceptos() {

        return new Comprobante40\Conceptos();
    }

    public function createComprobanteConceptosConcepto(array $rs) {

        $concepto = new Comprobante40\Conceptos\Concepto();
        $concepto->setClaveProdServ($rs["ClaveProdServ"]);
        $concepto->setClaveUnidad($rs["ClaveUnidad"]);
        $concepto->setDescripcion($rs["Descripcion"]);
        $concepto->setImporte(number_format($rs["ValorUnitario"], 2, ".", ""));
        $concepto->setCantidad($rs["Cantidad"]);
        $concepto->setNoIdentificacion($rs["NoIdentificacion"]);
        $concepto->setValorUnitario(number_format($rs["ValorUnitario"], 4, ".", ""));
        $concepto->setObjetoImp("02");
        if ($rs["Descuento"] > 0) {
            $concepto->setDescuento(number_format($rs["Descuento"], 2, ".", ""));
        }
        return $concepto;
    }

    public function createComprobanteConceptosConceptoImpuestos() {
        return new Comprobante40\Conceptos\Concepto\Impuestos();
    }

    public function createComprobanteConceptosConceptoImpuestosTraslados() {
        return new Comprobante40\Conceptos\Concepto\Impuestos\Traslados();
    }

    public function createComprobanteConceptosConceptoImpuestosTrasladosTraslado(array $rs) {

        $traslado = new Comprobante40\Conceptos\Concepto\Impuestos\Traslados\Traslado();
        $traslado->setBase(number_format($rs["Base"], 2, ".", ""));
        $traslado->setImpuesto($rs["Impuesto"]);
        $traslado->setTipoFactor($rs["TipoFactor"]);
        if ($rs["TipoFactor"] !== "Exento") {
            $traslado->setTasaOCuota($rs["TasaOCuota"]);
            $traslado->setImporte(number_format($rs["Importe"], 2, ".", ""));
        }
        return $traslado;
    }

    public function createComprobanteConceptosConceptoImpuestosRetenciones() {
        return new Comprobante40\Conceptos\Concepto\Impuestos\Retenciones();
    }

    public function createComprobanteConceptosConceptoImpuestosRetencionesRetencion(array $rs) {

        $retencion = new Comprobante40\Conceptos\Concepto\Impuestos\Retenciones\Retencion();
        $retencion->setBase(number_format($rs["Base"], 2, ".", ""));
        $retencion->setImpuesto($rs["Impuesto"]);
        $retencion->setTipoFactor($rs["TipoFactor"]);
        $retencion->setTasaOCuota($rs["TasaOCuota"]);
        $retencion->setImporte(number_format($rs["Importe"], 2, ".", ""));
        return $retencion;
    }

    public function createComprobanteImpuestos() {
        return new Comprobante40\Impuestos();
    }

    public function createComprobanteImpuestosTraslados() {
        return new Comprobante40\Impuestos\Traslados();
    }

    public function createComprobanteImpuestosTrasladosTraslado(array $rs) {

        $traslado = new Comprobante40\Impuestos\Traslados\Traslado();
        $traslado->setBase(number_format($rs["Base"], 2, ".", ""));
        $traslado->setImpuesto($rs["Impuesto"]);
        $traslado->setTipoFactor($rs["TipoFactor"]);
        if ($rs["TipoFactor"] !== "Exento") {
            $traslado->setImporte(number_format($rs["Importe"], 2, ".", ""));
            $traslado->setTasaOCuota($rs["TasaOCuota"]);
        }
        return $traslado;
    }

    public function createComprobanteImpuestosRetenciones() {
        return new Comprobante40\Impuestos\Retenciones();
    }

    public function createComprobanteImpuestosRetencionesRetencion(array $rs) {

        $retencion = new Comprobante40\Impuestos\Retenciones\Retencion();
        $retencion->setImporte(number_format($rs["Importe"], 2, ".", ""));
        $retencion->setImpuesto($rs["Impuesto"]);
        return $retencion;
    }

    public function createComprobanteAddenda() {
        return new Comprobante40\Addenda();
    }

    public function createComprobanteComplemento() {
        return new Comprobante40\Complemento();
    }

}
