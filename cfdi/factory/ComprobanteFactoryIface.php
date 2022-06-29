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

use com\softcoatl\cfdi\Comprobante;

interface ComprobanteFactoryIface {

    public function createComprobante(array $rs);
    public function createComprobanteCfdiRelacionados();
    public function createComprobanteCfdiRelacionadosCfdiRelacionado(array $rs);
    public function createComprobanteEmisor(array $rs);
    public function createComprobanteReceptor(array $rs);
    public function createComprobanteReceptorGenerico(array $rs);
    public function createComprobanteConceptos();
    public function createComprobanteConceptosConcepto(array $rs);
    public function createComprobanteConceptosConceptoImpuestos();
    public function createComprobanteConceptosConceptoImpuestosTraslados();
    public function createComprobanteConceptosConceptoImpuestosTrasladosTraslado(array $rs);
    public function createComprobanteConceptosConceptoImpuestosRetenciones();
    public function createComprobanteConceptosConceptoImpuestosRetencionesRetencion(array $rs);
    public function createComprobanteImpuestos();
    public function createComprobanteImpuestosTraslados();
    public function createComprobanteImpuestosTrasladosTraslado(array $rs);
    public function createComprobanteImpuestosRetenciones();
    public function createComprobanteImpuestosRetencionesRetencion(array $rs);
    public function createComprobanteComplemento();
    public function createComprobanteAddenda();
}
