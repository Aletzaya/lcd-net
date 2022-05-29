<?php

/*
 * SifeiService
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace cfdi33;

require_once ("cfdi33/utils/SOAPClient.php");
require_once ("cfdi33/SelloCFDI.php");
require_once ("PACService.php");

class SifeiService implements PACService {

    /* @var $PAC SifeiPACWrapper */
    private $PAC;
    private $error;

    function __construct($PAC) {
        $this->PAC = $PAC;
    }

    function getPAC() {
        return $this->PAC;
    }

    function getError() {
        return $this->error;
    }
    
    function setPAC($PAC) {
        $this->PAC = $PAC;
    }

    private function zip($xmlCFDI) {

        $file = tempnam("tmp", "zip");
        trigger_error("Zipping into file " . $file);   
        $zip = new \ZipArchive();
        if ($zip->open($file, \ZipArchive::OVERWRITE)) {
            $zip->addFromString('.xml', $xmlCFDI);
            $zip->close();

            $contents = file_get_contents($file);

            return $contents;
        }

        return FALSE;
    }//zip

    private function unzip($xmlCFDIZipped) {

        $file = tempnam("tmp", "zip");
        trigger_error("Unzipping into file " . $file);   
        file_put_contents($file, $xmlCFDIZipped);
        $zip = new \ZipArchive();
        if ($zip->open($file)) {
            $zip->renameIndex(0, '.xml');
            $cfdiTimbrado = $zip->getFromIndex(0);
            $zip->close();
            return $cfdiTimbrado;
        }

        return FALSE;
    }//unzip

    public function timbraComprobante($xmlCFDI) {

        $zipped = $this->zip($xmlCFDI);
        $b64Zipped = base64_encode($zipped);
        trigger_error($b64Zipped);
        $params = array(
            "Usuario" => $this->PAC->getUser(),
            "Password" => $this->PAC->getPassword(),
            "archivoXMLZip" => $b64Zipped,
            "Serie" => $this->PAC->getSerie(),
            "IdEquipo" => $this->PAC->getIdEquipo()  
        );

        trigger_error("CFDI33_2 :: " . print_r($params, TRUE));
        /* @var $wsClient nusoap_client */
        $wsClient = \com\softcoatl\SOAPClient::getClient($this->PAC->getUrl());

        try {
            $wsResponse = $wsClient->call("getCFDI", $params, "http://MApeados/");
            trigger_error("CFDI33_2 :: " . print_r($wsResponse, TRUE));
            trigger_error("CFDI33_2 :: " . $wsClient->debug_str);
            $wsError = $wsClient->getError();
            if ($wsError) {
                if ($wsResponse['detail']['SifeiException']['codigo'] == '307') {
                    return $this->getTimbre($xmlCFDI);
                } else {
                    echo $wsClient->debug_str;
                    $this->error = $wsResponse['detail']['SifeiException']['error'];
                    return FALSE;
                }
            } else {
                return $this->unzip(base64_decode($wsResponse['return']));
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return FALSE;
    }//timbraComprobante

    public function getTimbre($xmlCFDI) {

        $cfdi = new \DOMDocument("1.0","UTF-8");
        $cfdi->loadXML($xmlCFDI);

        $originalBytes = SelloCFDI::getOriginalBytes($xmlCFDI);
        $digestion = sha1($originalBytes);

        "<br/>Cadena Original::<br/>" . $originalBytes . "</br>";
        $params = array(
            "rfc" => $this->PAC->getUser(),
            "pass" => $this->PAC->getPassword(),
            "hash" => $digestion
        );

        /* @var $wsClient nusoap_client */
        $wsClient = \com\softcoatl\SOAPClient::getClient($this->PAC->getUrl());

        try {
            $wsResponse = $wsClient->call("getXML", $params, "http://MApeados/");
            "<br/>SOAP Trace::<br/>" . $wsClient->debug_str . "</br>";
            $wsError = $wsClient->getError();
            if ($wsError) {
                $this->error = $wsResponse['detail']['SifeiException']['error'];
                return FALSE;
            } else {
                return $wsResponse['return'];
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return FALSE;
    }//getTimbre

    /**
     * 
     * @param String $rfc   RFC del Emisor
     * @param String $uuid  UUID del Comprobante
     * @param String $pass  Password del archivo PFX
     * @return boolean
     */
    public function cancelaComprobante($rfc, $uuid, $pass) {

        $params = array(
            "usuarioSIFEI" => $this->PAC->getUser(),
            "passUser" => $this->PAC->getPassword(),
            "rfc" => $rfc,
            "pfx" => base64_encode(file_get_contents('certificado/file.pfx')),
            "passPFX" => $pass,
            "UUIDS"=> array($uuid)
        );

        /* @var $wsClient nusoap_client */
        $wsClient = \com\softcoatl\SOAPClient::getClient($this->PAC->getUrl());

        try {
            $wsResponse = $wsClient->call("cancelaCFDI", $params, "http://MApeados/");
            "<br/>SOAP Trace::<br/>" . $wsClient->debug_str . "</br>";
            $wsError = $wsClient->getError();
            if ($wsError) {
                $this->error = $wsResponse['detail']['SifeiException']['error'];
                return FALSE;
            } else {
                return $wsResponse['return'];
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return FALSE;
    }//cancelaComprobante

}//SifeiService
