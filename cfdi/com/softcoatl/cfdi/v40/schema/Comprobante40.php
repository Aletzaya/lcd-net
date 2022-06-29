<?php
/*
 * Comprobante40
 * CFDI versión 4.0
 * CFDI®
 * © 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @version 1.0
 * @since jan 2022
 */
namespace com\softcoatl\cfdi\v40\schema;

require_once ('cfdi/com/softcoatl/cfdi/complemento/tfd/TimbreFiscalDigital.php');
require_once ("cfdi/com/softcoatl/cfdi/Comprobante.php");
require_once ("cfdi/com/softcoatl/cfdi/CFDIElement.php");
require_once ("cfdi/com/softcoatl/cfdi/Comprobante.php");
require_once ("cfdi/com/softcoatl/cfdi/ValidadorCFDI.php");
require_once ("cfdi/com/softcoatl/cfdi/utils/Reflection.php");
require_once (dirname(__FILE__)."/Comprobante40/InformacionGlobal.php");
require_once (dirname(__FILE__)."/Comprobante40/CfdiRelacionados.php");
require_once (dirname(__FILE__)."/Comprobante40/Emisor.php");
require_once (dirname(__FILE__)."/Comprobante40/Receptor.php");
require_once (dirname(__FILE__)."/Comprobante40/Conceptos.php");
require_once (dirname(__FILE__)."/Comprobante40/Impuestos.php");
require_once (dirname(__FILE__)."/Comprobante40/Complemento.php");
require_once (dirname(__FILE__)."/Comprobante40/Addenda.php");

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\Comprobante;
use com\softcoatl\cfdi\utils\Reflection;
use com\softcoatl\cfdi\ValidadorCFDI;

class Comprobante40 implements CFDIElement, Comprobante {

    public static $ARR_COMPLEMENTO = array();
    public static $ARR_ADDENDA = array();

    /** @var Comprobante40\InformacionGlobal */
    private $InformacionGlobal;
    /** @var Comprobante40\CfdiRelacionados[] */
    private $CfdiRelacionados = array();
    /** @var Comprobante40\Emisor */
    private $Emisor;
    /** @var Comprobante40\Receptor */
    private $Receptor;
    /** @var Comprobante40\Conceptos */
    private $Conceptos;
    /** @var Comprobante40\Impuestos */
    private $Impuestos;

    /** @var Comprobante40\Complemento */
    private $Complemento;
    /** @var Comprobante40\Addenda */
    private $Addenda;

    private $Version = "4.0";
    private $Serie;
    private $Folio;
    private $Fecha;
    private $Sello;
    private $FormaPago;
    private $NoCertificado;
    private $Certificado;
    private $CondicionesDePago;
    private $SubTotal;
    private $Descuento;
    private $Moneda;
    private $TipoCambio;
    private $Total;
    private $TipoDeComprobante;
    private $Exportacion;
    private $MetodoPago;
    private $LugarExpedicion;
    private $Confirmacion;

    public static function getARR_COMPLEMENTO() {
        return self::$ARR_COMPLEMENTO;
    }

    public static function getARR_ADDENDA() {
        return self::$ARR_ADDENDA;
    }

    public function getInformacionGlobal() {
        return $this->InformacionGlobal;
    }

    public function getCfdiRelacionados() {
        return $this->CfdiRelacionados;
    }

    public function getEmisor() {
        return $this->Emisor;
    }

    public function getReceptor() {
        return $this->Receptor;
    }

    public function getConceptos() {
        return $this->Conceptos;
    }

    public function getImpuestos() {
        return $this->Impuestos;
    }

    public function getComplemento() {
        return $this->Complemento;
    }

    public function getAddenda() {
        return $this->Addenda;
    }

    public function getVersion() {
        return $this->Version;
    }

    public function getSerie() {
        return $this->Serie;
    }

    public function getFolio() {
        return $this->Folio;
    }

    public function getFecha() {
        return $this->Fecha;
    }

    public function getSello() {
        return $this->Sello;
    }

    public function getFormaPago() {
        return $this->FormaPago;
    }

    public function getNoCertificado() {
        return $this->NoCertificado;
    }

    public function getCertificado() {
        return $this->Certificado;
    }

    public function getCondicionesDePago() {
        return $this->CondicionesDePago;
    }

    public function getSubTotal() {
        return $this->SubTotal;
    }

    public function getDescuento() {
        return $this->Descuento;
    }

    public function getMoneda() {
        return $this->Moneda;
    }

    public function getTipoCambio() {
        return $this->TipoCambio;
    }

    public function getTotal() {
        return $this->Total;
    }

    public function getTipoDeComprobante() {
        return $this->TipoDeComprobante;
    }

    public function getExportacion() {
        return $this->Exportacion;
    }

    public function getMetodoPago() {
        return $this->MetodoPago;
    }

    public function getLugarExpedicion() {
        return $this->LugarExpedicion;
    }

    public function getConfirmacion() {
        return $this->Confirmacion;
    }

    public static function setARR_COMPLEMENTO($ARR_COMPLEMENTO) {
        self::$ARR_COMPLEMENTO = $ARR_COMPLEMENTO;
    }

    public static function setARR_ADDENDA($ARR_ADDENDA) {
        self::$ARR_ADDENDA = $ARR_ADDENDA;
    }

    public function setInformacionGlobal(Comprobante40\InformacionGlobal $InformacionGlobal) {
        $this->InformacionGlobal = $InformacionGlobal;
    }

    public function setCfdiRelacionados(array $CfdiRelacionados) {
        $this->CfdiRelacionados = $CfdiRelacionados;
    }

    public function addCfdiRelacionados(Comprobante\CfdiRelacionados $CfdiRelacionados) {
        $this->CfdiRelacionados[] = $CfdiRelacionados;
    }

    public function setEmisor(Comprobante\Emisor $Emisor) {
        $this->Emisor = $Emisor;
    }

    public function setReceptor(Comprobante\Receptor $Receptor) {
        $this->Receptor = $Receptor;
    }

    public function setConceptos(Comprobante\Conceptos $Conceptos) {
        $this->Conceptos = $Conceptos;
    }

    public function setImpuestos(Comprobante\Impuestos $Impuestos) {
        $this->Impuestos = $Impuestos;
    }

    public function setComplemento(Comprobante\Complemento $Complemento) {
        $this->Complemento = $Complemento;
    }

    public function setAddenda(Comprobante\Addenda $Addenda) {
        $this->Addenda = $Addenda;
    }

    public function setVersion($Version) {
        $this->Version = $Version;
    }

    public function setSerie($Serie) {
        $this->Serie = $Serie;
    }

    public function setFolio($Folio) {
        $this->Folio = $Folio;
    }

    public function setFecha($Fecha) {
        $this->Fecha = $Fecha;
    }

    public function setSello($Sello) {
        $this->Sello = $Sello;
    }

    public function setFormaPago($FormaPago) {
        $this->FormaPago = $FormaPago;
    }

    public function setNoCertificado($NoCertificado) {
        $this->NoCertificado = $NoCertificado;
    }

    public function setCertificado($Certificado) {
        $this->Certificado = $Certificado;
    }

    public function setCondicionesDePago($CondicionesDePago) {
        $this->CondicionesDePago = $CondicionesDePago;
    }

    public function setSubTotal($SubTotal) {
        $this->SubTotal = $SubTotal;
    }

    public function setDescuento($Descuento) {
        $this->Descuento = $Descuento;
    }

    public function setMoneda($Moneda) {
        $this->Moneda = $Moneda;
    }

    public function setTipoCambio($TipoCambio) {
        $this->TipoCambio = $TipoCambio;
    }

    public function setTotal($Total) {
        $this->Total = $Total;
    }

    public function setTipoDeComprobante($TipoDeComprobante) {
        $this->TipoDeComprobante = $TipoDeComprobante;
    }

    public function setExportacion($Exportacion) {
        $this->Exportacion = $Exportacion;
    }

    public function setMetodoPago($MetodoPago) {
        $this->MetodoPago = $MetodoPago;
    }

    public function setLugarExpedicion($LugarExpedicion) {
        $this->LugarExpedicion = $LugarExpedicion;
    }

    public function setConfirmacion($Confirmacion) {
        $this->Confirmacion = $Confirmacion;
    }

    public static function registerComplemento($complemento) {
        self::$ARR_COMPLEMENTO[] = $complemento;
    }
    
    public static function registerAddenda($addenda) {
        self::$ARR_ADDENDA[] = $addenda;
    }

    public function getTimbreFiscalDigital() {
        if (!empty($this->Complemento)) {
            foreach ($this->Complemento->getAny() as $Complemento) {
                if ($Complemento instanceof \com\softcoatl\cfdi\complemento\tfd\TimbreFiscalDigital) {
                    return $Complemento;
                }
            }
        }

        return false;
    }

    public function schemaValidate() {
        $validador = new ValidadorCFDI();
        return $validador->validate($this->asXML()->saveXML(), dirname(__FILE__) . "/xsd/cfd/cfdv40.xsd");        
    }

    public function getOriginalBytes() {

        $xml = $this->asXML()->saveXML();

        $cfdi = new \DOMDocument("1.0","UTF-8");
        $cfdi->loadXML($xml);

        $xsl = new \DOMDocument("1.0", "UTF-8");
        $xsl->load(dirname(__FILE__) . "/xslt/cadenaoriginal_4_0.xslt");

        $proc = new \XSLTProcessor();
        $proc->importStyleSheet($xsl); 

        return $proc->transformToXML($cfdi);
    }

    public function getTFDOriginalBytes() {

        $xsl = new \DOMDocument("1.0", "UTF-8");
        $xsl->load(dirname(__FILE__) . "/xslt/cadenaoriginal_TFD_1_1.xslt");
        $proc = new \XSLTProcessor();
        $proc->importStyleSheet($xsl); 

        $doc = new \DOMDocument("1.0", "UTF-8");
        $tfd = $this->getTimbreFiscalDigital()->asXML($doc->createElement("aux"));
        $doc->appendChild($tfd);

        $cfdi = new \DOMDocument("1.0", "UTF-8");
        $cfdi->loadXML($doc->saveXML());

        return $proc->transformToXML($cfdi);
    }

    public function getValidationExpression() {
        $tfc = $this->getTimbreFiscalDigital();
        return $tfc ? implode("&", [
                "id=" . $tfc->getUUID(),
                "re=" . $this->Emisor->getRfc(),
                "rr=" . $this->Receptor->getRfc(),
                "tt=" . number_format($this->Total, 2, '.', ''),
                "fe=" . substr($this->Sello, - 8)]) : "";
    }

    public function getValidationURL() {
        return "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?" 
            . $this->getValidationExpression();
    }

    private function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) {
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Comprobante40\InformacionGlobal)
                                && !($val instanceof Comprobante40\Emisor)
                                && !($val instanceof Comprobante40\Receptor)
                                && !($val instanceof Comprobante40\Conceptos)
                                && !($val instanceof Comprobante40\CfdiRelacionados)
                                && !($val instanceof Comprobante40\Impuestos)
                                && !($val instanceof Comprobante40\Complemento)
                                && !($val instanceof Comprobante40\Addenda);
        });
    }

    private function removeEmptyNodes(\DOMDocument $DOMDocument) {
        $xpath = new \DOMXPath($DOMDocument);
        foreach( $xpath->query("//*[count(@*) = 0 and count(child::*) = 0]") as $node ) {
            $node->parentNode->removeChild($node);
        }
        return $DOMDocument;
    }

    public function asXML($root= null) {

        $document = new \DOMDocument("1.0", "UTF-8");
        $Comprobante = $document->createElement("cfdi:Comprobante");
        $document->appendChild($Comprobante);

        $Comprobante->setAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd/4");
        $Comprobante->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $Comprobante->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd");

        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Comprobante->setAttribute($attr, $value);
        }

        if ($this->InformacionGlobal !== NULL) {
            $Comprobante->appendChild($this->InformacionGlobal->asXML($Comprobante));
        }

        if ($this->CfdiRelacionados !== NULL) {
            foreach ($this->CfdiRelacionados as $CfdiRelacionados) {
                $Comprobante->appendChild($CfdiRelacionados->asXML($Comprobante));
           }
        }

        $Comprobante->appendChild($this->Emisor->asXML($Comprobante));
        $Comprobante->appendChild($this->Receptor->asXML($Comprobante));
        $Comprobante->appendChild($this->Conceptos->asXML($Comprobante));

        if ($this->Impuestos !== NULL) {
            $Comprobante->appendChild($this->Impuestos->asXML($Comprobante));
        }

        if (!empty($this->Complemento) && !empty($this->Complemento->getAny())) {
            $complementos = $document->createElement("cfdi:Complemento");
            foreach ($this->Complemento->getAny() as $complemento) {
                $complementos->appendChild($complemento->asXML($Comprobante));
            }
            $Comprobante->appendChild($complementos);
        }

        if (!empty($this->Addenda) && !empty($this->Addenda->getAny())) {
            $addendas = $document->createElement("cfdi:Addenda");
            foreach ($this->Addenda->getAny() as $addenda) {
                $addendas->appendChild($addenda->asXML($Comprobante));
            }
            $Comprobante->appendChild($addendas);
        }

        return $this->removeEmptyNodes($document);
    }

    public static function parse($DOMCfdi) {

        $document = new \DOMDocument("1.0","UTF-8");
        $document->loadXML($DOMCfdi);

        if ($document->hasChildNodes()) {
            $cfdi = $document->firstChild;
            $Comprobante  = new Comprobante40();
            Reflection::setAttributes($Comprobante, $cfdi);

            for ($i=0; $i<$cfdi->childNodes->length; $i++) {
                $node = $cfdi->childNodes->item($i);
                if (strpos($node->nodeName, 'cfdi:InformacionGlobal')!==false) {
                    $Comprobante->setInformacionGlobal(Comprobante40\InformacionGlobal::parse($node));
                } else 
                if (strpos($node->nodeName, 'cfdi:CfdiRelacionados')!==false) {
                    $Comprobante->addCfdiRelacionados(Comprobante40\CfdiRelacionados::parse($node));
                } else 
                if (strpos($node->nodeName, 'cfdi:Emisor')!==false) {
                    $Comprobante->setEmisor(Comprobante40\Emisor::parse($node));
                } else 
                if (strpos($node->nodeName, 'cfdi:Receptor')!==false) {
                    $Comprobante->setReceptor(Comprobante40\Receptor::parse($node));
                } else 
                if (strpos($node->nodeName, 'cfdi:Conceptos')!==false) {
                    $Comprobante->setConceptos(Comprobante40\Conceptos::parse($node));
                } else 
                if (strpos($node->nodeName, 'cfdi:Impuestos')!==false) {
                    $Comprobante->setImpuestos(Comprobante40\Impuestos::parse($node));
                } else 
                if (strpos($node->nodeName, 'cfdi:Complemento')!==false) {
                    Comprobante40::unmarshallComplemento($Comprobante, $node);
                } else 
                if (strpos($node->nodeName, 'cfdi:Addenda')!==false) {
                    Comprobante40::unmarshallAddenda($Comprobante, $node);
                }
            }
        }
        return $Comprobante;
    }

    private static function unmarshallComplemento(Comprobante40 $Comprobante, \DOMElement $element) {

        $Complemento = new Comprobante40\Complemento();
        for ($i=0; $i<$element->childNodes->length; $i++) {
            $node = $element->childNodes->item($i);
            foreach (self::$ARR_COMPLEMENTO as $class) {
                if (class_exists($class) && ($parsed = $class::parse($node))) {
                    $Complemento->addAny($parsed);
                }
            }
        }
        $Comprobante->setComplemento($Complemento);
    }

    private static function unmarshallAddenda(Comprobante40 $Comprobante, \DOMElement $element) {

        $Addenda = new Comprobante40\Addenda();
        for ($i=0; $i<$element->childNodes->length; $i++) {
            $node = $element->childNodes->item($i);
            foreach (self::$ARR_ADDENDA as $class) {
                if (class_exists($class) && ($parsed = $class::parse($node))) {
                    $Addenda->addAny($parsed);
                }
            }
        }
        $Comprobante->setAddenda($Addenda);
    }
}
