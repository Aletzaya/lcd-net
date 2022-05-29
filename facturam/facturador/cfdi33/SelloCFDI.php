<?php

/*
 * SelloCFDI
 * cfdi®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace cfdi33 {
    class SelloCFDI {

        private $keyData;
        private $NoCertificado;
        private $Certificado;

        function __construct() {

            $this->keyData = openssl_pkey_get_private(file_get_contents('certificado/key.pem'));

            $cerContent = file_get_contents('certificado/cer.pem');
            $certificado = openssl_x509_parse($cerContent, TRUE);
            //echo print_r($certificado, TRUE) . '<br/>';
            $this->NoCertificado = strtoupper($this->hex2dec($this->bcdechex($certificado['serialNumber'])));
            $cerLines = explode("\n", $cerContent);
            foreach ($cerLines as $cerLine) {
                if (strstr($cerLine,"END CERTIFICATE")==FALSE && strstr($cerLine,"BEGIN CERTIFICATE")==FALSE) {
                    $this->Certificado .= trim($cerLine);
                }                
            }
        }

        function readB64File($filePath) {

            $fd = fopen($filePath, 'rb');
            $size = filesize($filePath);
            $cont = fread($fd, $size);
            fclose($fd);
            return base64_encode($cont);
        }

        /**
         * 
         * @param \DOMDocument $Comprobante
         */
        public static function getOriginalBytes($Comprobante) {

            $cfdi = new \DOMDocument("1.0","UTF-8");
            $cfdi->loadXML($Comprobante);

            $xsl = new \DOMDocument("1.0", "UTF-8");
            $xsl->load("xslt/cadenaoriginal_3_3.xslt");

            $proc = new \XSLTProcessor();
            $proc->importStyleSheet($xsl); 

            $cadena_original = $proc->transformToXML($cfdi);

            return $cadena_original;
        }//getOriginalBytes

        /**
         * 
         * @param \DOMDocument $TimbreFiscalDigital
         */
        public static function getTFDOriginalBytes($TimbreFiscalDigital) {

            $cfdi = new \DOMDocument("1.0","UTF-8");
            $cfdi->loadXML($TimbreFiscalDigital);

            $xsl = new \DOMDocument("1.0", "UTF-8");
            $xsl->load("xslt/cadenaoriginal_TFD_1_1.xslt");

            $proc = new \XSLTProcessor();
            $proc->importStyleSheet($xsl); 

            $cadena_original = $proc->transformToXML($cfdi);

            return $cadena_original;
        }//getTDFOriginalBytes

        private function bcdechex($dec) {

            $hex = '';

            do {    
                $last = bcmod($dec, 16);
                $hex = dechex($last).$hex;
                $dec = bcdiv(bcsub($dec, $last), 16);
            } while($dec>0);

            return $hex;
        }

        private function hex2dec($hex) {

            $dec = '';
            $i = -2;

            do {
                $dec .= chr(hexdec(substr($hex, $i+=2, 2)));
            } while ($i<strlen($hex));

            return $dec;
        } 
        /**
         * 
         * @param Comprobante $Comprobante
         */
        public function sellaComprobante($Comprobante) {

            $Comprobante->setNoCertificado($this->NoCertificado);
            $Comprobante->setCertificado($this->Certificado);

            $originalBytes = SelloCFDI::getOriginalBytes($Comprobante->asXML()->saveXML());
            $signature = '';
            openssl_sign($originalBytes, $signature, $this->keyData, "sha256WithRSAEncryption");

            $Comprobante->setSello(base64_encode($signature));
        }
    }
}