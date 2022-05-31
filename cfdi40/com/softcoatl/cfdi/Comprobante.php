<?php
/*
 * Comprobante
 * CFDI versión 3.3
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */
namespace com\softcoatl\cfdi;

interface Comprobante {

    public function getVersion();
    public function getLugarExpedicion();

    public function setNoCertificado($noCertificado);
    public function setCertificado($certificado);
    public function setSello($sello);
    public function setDescuento($descuento);
    public function setSubTotal($total);
    public function setTotal($total);

    public function getTimbreFiscalDigital(): ?complemento\tfd\TimbreFiscalDigital;
    public function getOriginalBytes();
    public function getTFDOriginalBytes();
    public function getValidationExpression();
    public function getValidationURL();

    public function getComplemento(): ?Comprobante\Complemento;
    public function getAddenda(): ?Comprobante\Addenda;

    public function getCfdiRelacionados(): array;
    public function getEmisor(): ?Comprobante\Emisor;
    public function getReceptor(): ?Comprobante\Receptor;
    public function getConceptos(): ?Comprobante\Conceptos;
    public function getImpuestos(): ?Comprobante\Impuestos;

    public function setComplemento(Comprobante\Complemento $complemento);
    public function setAddenda(Comprobante\Addenda $addenda);

    public function setCfdiRelacionados(array $cfdiRelacionados);
    public function setEmisor(Comprobante\Emisor $emisor);
    public function setReceptor(Comprobante\Receptor $receptor);
    public function setConceptos(Comprobante\Conceptos $conceptos);
    public function setImpuestos(Comprobante\Impuestos $impuestos);

    public function schemaValidate();

    public function asXML($root): \DOMDocument;
}

namespace com\softcoatl\cfdi\Comprobante;

interface CfdiRelacionados {

    public function getCfdiRelacionado(): array;
    public function getTipoRelacion();
    public function setCfdiRelacionado(array $CfdiRelacionado);
    public function setTipoRelacion($TipoRelacion);
}

interface Emisor {
    
    public function getRfc();
    public function getNombre();
    public function getRegimenFiscal();
    public function setRfc($Rfc);
    public function setNombre($Nombre);
    public function setRegimenFiscal($RegimenFiscal);
}

interface Receptor {

    public function getRfc();
    public function getNombre();
    public function getUsoCFDI();
    public function setRfc($Rfc);
    public function setNombre($Nombre);
    public function setUsoCFDI($UsoCFDI);
}

interface Conceptos {

    public function getConcepto(): array;
    public function setConcepto(array $Concepto);
    public function addConcepto(Conceptos\Concepto $Concepto);
}

interface Impuestos {

    public function getTraslados(): ?Impuestos\Traslados;
    public function getRetenciones(): ?Impuestos\Retenciones;
    public function setTraslados(Impuestos\Traslados $Traslados);
    public function setRetenciones(Impuestos\Retenciones $Retenciones);

    public function getTotalImpuestosRetenidos();
    public function getTotalImpuestosTrasladados();
    public function setTotalImpuestosRetenidos($TotalImpuestosRetenidos);
    public function setTotalImpuestosTrasladados($TotalImpuestosTrasladados);
}

interface Complemento {
    public function getAny(): array;
    public function addAny(\com\softcoatl\cfdi\CFDIElement $any);
}

interface Addenda {
    public function getAny(): array;
    public function addAny(\com\softcoatl\cfdi\CFDIElement $any);
}

namespace com\softcoatl\cfdi\Comprobante\Conceptos;

interface Concepto {

    public function getImpuestos(): ?Concepto\Impuestos;
    public function getClaveProdServ();
    public function getNoIdentificacion();
    public function getCantidad();
    public function getClaveUnidad();
    public function getUnidad();
    public function getDescripcion();
    public function getValorUnitario();
    public function getImporte();
    public function getDescuento();
    
    public function setImpuestos(Concepto\Impuestos $Impuestos);
    public function setClaveProdServ($ClaveProdServ);
    public function setNoIdentificacion($NoIdentificacion);
    public function setCantidad($Cantidad);
    public function setClaveUnidad($ClaveUnidad);
    public function setUnidad($Unidad);
    public function setDescripcion($Descripcion);
    public function setValorUnitario($ValorUnitario);
    public function setImporte($Importe);
    public function setDescuento($Descuento);
}

namespace com\softcoatl\cfdi\Comprobante\CfdiRelacionados;

interface CfdiRelacionado {
    
}

namespace com\softcoatl\cfdi\Comprobante\Conceptos\Concepto;

interface Impuestos {

    public function getTraslados(): ?Impuestos\Traslados;
    public function getRetenciones(): ?Impuestos\Retenciones;
    public function setTraslados(Impuestos\Traslados $Traslados);
    public function setRetenciones(Impuestos\Retenciones $Retenciones);
}

namespace com\softcoatl\cfdi\Comprobante\Conceptos\Concepto\Impuestos;

interface Traslados {
    
    public function getTraslado(): array;
    public function setTraslado(array $Traslado);
}

interface Retenciones {
    
    public function getRetencion(): array;
    public function setRetencion(array $Retencion);
}

namespace com\softcoatl\cfdi\Comprobante\Impuestos;

interface Traslados {
    
    public function getTraslado(): array;
    public function setTraslado(array $Traslado);
}

interface Retenciones {
    
    public function getRetencion(): array;
    public function setRetencion(array $Retencion);
}
