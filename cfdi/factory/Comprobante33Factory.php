<?php

/*
 * Comprobante33DAO
 * GlobalFAE®
 * © 2018, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since feb 2018
 */
namespace com\detisa\cfdi\factory;

include_once ("com/softcoatl/cfdi/v33/schema/Comprobante33.php");
require_once ("ComprobanteFactoryIface.php");

use com\softcoatl\cfdi\v33\schema\Comprobante33;
use com\softcoatl\cfdi\Comprobante;

class Comprobante33Factory implements ComprobanteFactoryIface {

    public function createComprobante(array $rs): Comprobante {

        $comprobante = new Comprobante33();
        $comprobante->setFolio($rs["Folio"]);
        $comprobante->setSerie($rs["Serie"]);
        $comprobante->setFecha($rs["Fecha"]);
        $comprobante->setTipoDeComprobante($rs["TipoDeComprobante"]);
        $comprobante->setVersion($rs["Version"]);
        $comprobante->setFormaPago($rs["FormaPago"]);
        $comprobante->setMetodoPago($rs["MetodoPago"]);
        $comprobante->setCondicionesDePago($rs["CondicionesDePago"]);
        $comprobante->setMoneda($rs["Moneda"]);
        if ($rs["Moneda"]==="MXN") {
            $comprobante->setTipoCambio("1");
        } else if ($rs["Moneda"]!=="XXX") {
            $comprobante->setTipoCambio($rs["TipoCambio"]);
        }
        $comprobante->setSubTotal($rs["SubTotal"]);
        $comprobante->setTotal($rs["Total"]);
        $comprobante->setLugarExpedicion($rs["LugarExpedicion"]);
        return $comprobante;
    }

    public function createComprobanteCfdiRelacionados(): \com\softcoatl\cfdi\Comprobante\CfdiRelacionados {
        return new Comprobante33\CfdiRelacionados();
    }

    public function createComprobanteCfdiRelacionadosCfdiRelacionado(array $rs): Comprobante\CfdiRelacionados\CfdiRelacionado {
        $cfdiRelacionado = new Comprobante33\CfdiRelacionados();
        $cfdiRelacionado->setUUID($rs["uuid_relacionado"]);
        return $cfdiRelacionado;
    }

    public function createComprobanteEmisor(array $rs): Comprobante\Emisor {

        $emisor = new Comprobante33\Emisor();
        $emisor->setNombre($rs["Nombre"]);
        $emisor->setRfc($rs["Rfc"]);
        $emisor->setRegimenFiscal($rs["RegimenFiscal"]);
        return $emisor;
    }

    public function createComprobanteReceptor(array $rs): Comprobante\Receptor {

        $receptor = new Comprobante33\Receptor();
        $receptor->setNombre($rs["Nombre"]);
        $receptor->setRfc($rs["Rfc"]);
        $receptor->setUsoCFDI($rs["UsoCFDI"]);
        return $receptor;
    }

    public function createComprobanteReceptorGenerico(array $rs): Comprobante\Receptor {

        $receptor = new Comprobante40\Receptor();
        $receptor->setNombre("PUBLICO EN GENERAL");
        $receptor->setRfc("XAXX010101000");
        $receptor->setUsoCFDI("P01");
        return $receptor;
    }

    public function createComprobanteConceptos(): Comprobante\Conceptos {

        return new Comprobante33\Conceptos();
    }
    
    public function createComprobanteConceptosConcepto(array $rs): Comprobante\Conceptos\Concepto {

        $concepto = new Comprobante33\Conceptos\Concepto();
        $concepto->setClaveProdServ($rs["ClaveProdServ"]);
        $concepto->setClaveUnidad($rs["ClaveUnidad"]);
        $concepto->setDescripcion($rs["Descripcion"]);
        $concepto->setImporte(number_format($rs["Importe"], 2, ".", ""));
        $concepto->setCantidad($rs["Cantidad"]);
        $concepto->setNoIdentificacion($rs["NoIdentificacion"]);
        $concepto->setValorUnitario(number_format($rs["ValorUnitario"], 4, ".", ""));
        if ($rs["Descuento"]>0) {
            $concepto->setDescuento(number_format($rs["Descuento"], 2, ".", ""));
        }
        return $concepto;
    }
    
    public function createComprobanteConceptosConceptoImpuestos(): Comprobante\Conceptos\Concepto\Impuestos {
        return new Comprobante33\Conceptos\Concepto\Impuestos();
    }
    
    public function createComprobanteConceptosConceptoImpuestosTraslados(): Comprobante\Conceptos\Concepto\Impuestos\Traslados {
        return new Comprobante33\Conceptos\Concepto\Impuestos\Traslados();
    }

    public function createComprobanteConceptosConceptoImpuestosTrasladosTraslado(array $rs) {
        
        $traslado =  new Comprobante33\Conceptos\Concepto\Impuestos\Traslados\Traslado();
        $traslado->setBase($rs["Base"]);
        $traslado->setImpuesto($rs["Impuesto"]);
        $traslado->setTipoFactor($rs["TipoFactor"]);
        $traslado->setTasaOCuota($rs["TasaOCuota"]);
        $traslado->setImporte($rs["Importe"]);
        return $traslado;
    }

    public function createComprobanteConceptosConceptoImpuestosRetenciones(): Comprobante\Conceptos\Concepto\Impuestos\Retenciones {
        return new Comprobante33\Conceptos\Concepto\Impuestos\Retenciones();
    }

    public function createComprobanteConceptosConceptoImpuestosRetencionesRetencion(array $rs) {

        $retencion = new Comprobante33\Conceptos\Concepto\Impuestos\Retenciones\Retencion();
        $retencion->setBase(number_format($rs["Base"], 2, ".", ""));
        $retencion->setImpuesto($rs["Impuesto"]);
        $retencion->setTasaOCuota($rs["TasaOCuota"]);
        $retencion->setTipoFactor($rs["TipoFactor"]);
        $retencion->setImporte(number_format($rs["Importe"], 2, ".", ""));
        return $retencion;
    }

    public function createComprobanteImpuestos(): Comprobante\Impuestos {
        return new Comprobante33\Impuestos();
    }
    
    public function createComprobanteImpuestosTraslados(): Comprobante\Impuestos\Traslados {
        return new Comprobante33\Impuestos\Traslados();
    }

    public function createComprobanteImpuestosTrasladosTraslado(array $rs) {

        $traslado = new Comprobante33\Impuestos\Traslados\Traslado();
        $traslado->setImporte(number_format($rs["Importe"], 2, ".", ""));
        $traslado->setImpuesto($rs["Impuesto"]);
        $traslado->setTasaOCuota($rs["TasaOCuota"]);
        $traslado->setTipoFactor($rs["TipoFactor"]);
        return $traslado;
    }
    
    public function createComprobanteImpuestosRetenciones(): Comprobante\Impuestos\Retenciones {
        return new Comprobante33\Impuestos\Retenciones();
    }

    public function createComprobanteImpuestosRetencionesRetencion(array $rs) {

        $retencion = new Comprobante33\Impuestos\Retenciones\Retencion();
        $retencion->setImporte(number_format($rs["Importe"], 2, ".", ""));
        $retencion->setImpuesto($rs["Impuesto"]);
        return $retencion;
    }

    public function createComprobanteAddenda(): Comprobante\Addenda {
        return new Comprobante33\Addenda();
    }

    public function createComprobanteComplemento(): Comprobante\Complemento {
        return new Comprobante33\Complemento();
    }

}
