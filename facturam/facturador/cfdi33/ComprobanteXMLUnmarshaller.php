<?php

/*
 * ComprobanteXMLUnmarshaller
 * cfdi®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

class ComprobanteXMLUnmarshaller {
    
    public function unmarshal($DOMCfdi) {
        
        $document = new \DOMDocument("1.0","UTF-8");
        $document->loadXML($DOMCfdi);

        if ($document->hasChildNodes()) {
            /* @var $cfdi DOMElement */
            $cfdi = $document->firstChild;
            $Comprobante  = new cfdi33\Comprobante();
            ComprobanteXMLUnmarshaller::fillAttributes($Comprobante, $cfdi);
            
            for ($i=0; $i<$cfdi->childNodes->length; $i++) {
                $node = $cfdi->childNodes->item($i);
                if (strpos($node->nodeName==':CfdiRelacionados')) {
                    $Comprobante->setCfdiRelacionados($this->unmarshallCfdiRelacionados($node));
                }
                else if (strpos($node->nodeName==':Emisor')) {
                    $Comprobante->setEmisor($this->unmarshallEmisor($node));
                }
                else if (strpos($node->nodeName==':Receptor')) {
                    $Comprobante->setReceptor($this->unmarshallReceptor($node));
                }
                else if (strpos($node->nodeName==':Conceptos')) {
                    $Comprobante->setConceptos(\cfdi33\Comprobante\Conceptos::parse($node));
                }
                else if (strpos($node->nodeName==':Impuestos')) {
                    $Comprobante->setImpuestos(\cfdi33\Comprobante\Impuestos::parse($node));
                }
                else if (strpos($node->nodeName==':Complemento')) {
                    $this->unmarshallComplemento($Comprobante, $node);
                }
                else if (strpos($node->nodeName==':Addenda')) {
                    $this->unmarshallAddenda($Comprobante, $node);
                }
            }
        }
    }

    /**
     * 
     * @param \cfdi33\Comprobante $Comprobante
     * @param DOMElement $DOMComplementos
     */
    private function unmarshallComplemento($Comprobante, $DOMComplementos) {

        for ($i=0; $i<$DOMComplementos->childNodes->length; $i++) {
            /* @var $DOMComplemento DOMElement */
            $DOMComplemento = $DOMComplementos->childNodes->item($i);
            if (strpos($DOMComplemento->nodeName, ':TimbreFiscalDigital')) {
                
                $Comprobante->addComplemento(\cfdi33\complemento\TimbreFiscalDigital::parse($DOMComplemento));
            } else if (strpos($DOMComplemento->nodeName, ':Pagos')) {
                
                $Comprobante->addComplemento(\cfdi33\complemento\Pagos::parse($DOMComplemento));
            }
        }
    }

    /**
     * 
     * @param \cfdi33\Comprobante $Comprobante
     * @param DOMElement $DOMAddendas
     */
    private function unmarshallAddenda($Comprobante, $DOMAddendas) {
        
        for ($i=0; $i<$DOMAddendas->childNodes->length; $i++) {
            /* @var $DOMAddenda DOMElement */
            $DOMAddenda = $DOMAddenda->childNodes->item($i);
            if (strpos($DOMAddenda->nodeName, '')) {
                $Comprobante->addAddenda(Observaciones::parse($DOMAddenda));
            }
        }
    }
}//ComprobanteXMLUnmarshaller
