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

namespace com\softcoatl\cfdi\utils\pac;

require_once ("cfdi/com/softcoatl/cfdi/utils/SOAPClient.php");
require_once ("BasePACService.php");

use com\softcoatl\cfdi\Comprobante;
use com\softcoatl\cfdi\utils\SOAPClient;
use com\softcoatl\security\commons\Certificate;

class SifeiException {

    private $sifeiException;

    function __construct($sifeiExcetion) {
        $this->sifeiException = $sifeiExcetion;
    }

    function exists() {
        return !empty($this->sifeiException);
    }
    function is($code) {
        return $this->code()==$code;
    }
    function code() {
        return $this->sifeiException['codigo'];
    }
    function error() {
        return $this->sifeiException['error'];
    }
    function message() {
        return $this->sifeiException['mensaje'];
    }
    function toHTML() {
        return "<b>ERROR REPORTADO POR EL SAT (" . $this->code() . ")<br/>" . $this->error() . ".</b><br>" . $this->message();
    }
}

abstract class SifeiCaller {

    public static $ns = "http://MApeados/";

    protected $pac;
    protected $parameters;
    protected $response;
    /** @var \nusoap_client*/
    protected $soapClient;
    protected $soapException;
    protected $sifeException;

    function __construct($pac) {
        $this->pac = $pac;
        $this->parameters = array();
    }

    public abstract function call();

    protected function invoke($operation) {
        try {
            error_log("Invocando " . $this->pac->getUrl() . "/" . $operation);
            $this->response = $this->soapClient->call($operation, $this->parameters, static::$ns);
            error_log(print_r($this->soapClient->request, true));
            error_log(print_r($this->soapClient->response, true));
            $this->sifeException = new SifeiException($this->response['detail']['SifeiException']);
            $this->soapException = $this->soapClient->error_str;
        } catch (\Exception $e) {
            $this->soapException = $e->getMessage();
        }

        return $this;
    }

    public function setParameter($key, $value) {

        $this->parameters[$key] = $value;
        return $this;
    }

    public function sifeiError() {
        return $this->sifeException->exists();
    }

    public function soapError() {
        return !empty($this->soapException);
    }

    public function success() {
        return !$this->sifeiError()
                && !$this->soapError();
    }

    public function errorCode($code) {
        return $this->sifeException->is($code);
    }

    public function getErrorCode() {
        return $this->sifeException->code();
    }

    public function getHTMLError() {
        return $this->sifeiError() ? 
                $this->sifeException->toHTML() : 
                "<b>ERROR<br/>" . explode("<br>", $this->soapException)[0];
    }

    public function getResponse($field = "return") {
        return $this->response[$field];
    }
}

class SifeiGetCFDICaller extends SifeiCaller {

    private static $operation = "getCFDI";

    function __construct($pac) {
        parent::__construct($pac);
        $this
            ->setParameter("Usuario", $this->pac->getUser())
            ->setParameter("Password", $this->pac->getPassword())
            ->setParameter("Serie", $this->pac->getSerie())
            ->setParameter("IdEquipo", $this->pac->getIdEquipo());  
        $this->soapClient = SOAPClient::getClient($this->pac->getUrl());
    }

    public function call() {
        parent::invoke(static::$operation);
        return $this;
    }
}

class SifeiGetXMLCaller extends SifeiCaller {

    private static $operation = "getXML";

    function __construct($pac) {
        parent::__construct($pac);
        $this
            ->setParameter("rfc", $this->pac->getUser())
            ->setParameter("pass", $this->pac->getPassword());  
        $this->soapClient = SOAPClient::getClient($this->pac->getUrl());
    }

    public function call() {
        parent::invoke(static::$operation);
        return $this;
    }
}

class SifeiCancelaCFDICaller extends SifeiCaller {
    
    private static $operation = "cancelaCFDI";

    function __construct($pac) {
        parent::__construct($pac);
        $this
            ->setParameter("usuarioSIFEI", $this->pac->getUser())
            ->setParameter("passwordSifei", $this->pac->getPassword());  
        $this->soapClient = SOAPClient::getClient($this->pac->getUrlCancelacion());
    }

    public function call() {
        parent::invoke(static::$operation);
        return $this;
    }
}

class SifeiCfdiRelacionadoCaller extends SifeiCaller {
    
    private static $operation = "cfdiRelacionado";

    function __construct($pac) {
        parent::__construct($pac);
        $this
            ->setParameter("usuarioSIFEI", $this->pac->getUser())
            ->setParameter("passwordSifei", $this->pac->getPassword());  
        $this->soapClient = SOAPClient::getClient($this->pac->getUrl());
    }

    public function call() {
        parent::invoke(static::$operation);
        return $this;
    }
}

class SifeiService extends BasePACService {

    function __construct(SifeiPACWrapper $PAC) {
        parent::__construct($PAC);
    }

    private function zip($xmlCFDI) {

        $file = tempnam("tmp", "zip");
        error_log("Zipping into file " . $file);   
        $zip = new \ZipArchive();
        if ($zip->open($file, \ZipArchive::OVERWRITE)) {

            $zip->addFromString('.xml', $xmlCFDI);
            $zip->close();

            $contents = file_get_contents($file);

            return $contents;
        }

        return false;
    }

    private function unzip($xmlCFDIZipped) {

        $file = tempnam("tmp", "zip");
        error_log("Unzipping into file " . $file);   
        file_put_contents($file, $xmlCFDIZipped);
        $zip = new \ZipArchive();
        if ($zip->open($file)) {

            $zip->renameIndex(0, '.xml');
            $cfdiTimbrado = $zip->getFromIndex(0);
            $zip->close();
            return $cfdiTimbrado;
        }

        return false;
    }

    private function appendTFD($cfdi, $timbre) {
        try {
            $xmlDocument = new \DOMDocument("1.0", "UTF-8");
            $tfdDocument = new \DOMDocument("1.0", "UTF-8");
            $xmlDocument->loadXML($cfdi);
            $tfdDocument->loadXML($timbre);
            $tfdAddopted = $xmlDocument->importNode($tfdDocument->documentElement);
            $nodes = $xmlDocument->documentElement->childNodes;
            for ($i = 0; $i < $nodes->length; $i++) {
                if ("cfdi:Complemento" === $nodes->item($i)->nodeName) {
                    $nodes->item($i)->appendChild($tfdAddopted);
                    return $xmlDocument->saveXML();
                }
            }

            $complemento = $xmlDocument->createElement("cfdi:Complemento");
            $complemento->appendChild($tfdAddopted);
            $xmlDocument->documentElement->appendChild($complemento);
            return $xmlDocument->saveXML();
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
        }
    }

    public function timbraComprobante(Comprobante $cfdi) {

        $sifei = (new SifeiGetCFDICaller($this->PAC))
                ->setParameter("archivoXMLZip", base64_encode($this->zip($cfdi->asXML()->saveXML())))
                ->call();

        if ($sifei->success()) {

            error_log("Timbrando correctamente");
            return $this->unzip(base64_decode($sifei->getResponse()));
        } else if ($sifei->errorCode(307)) {
            if (($timbre = $this->getTimbre($cfdi))!==false) {
                error_log("Comprobante previamente timbrado.");
                return $this->appendTFD($cfdi->asXML()->saveXML(), $timbre);
            }
        } else {
            $this->error = $sifei->getHTMLError();
        }

        return false;
    }

    public function getTimbre(Comprobante $cfdi) {

        $sifei = (new SifeiGetXMLCaller($this->PAC))
                ->setParameter("hash", hash("sha256", $cfdi->getOriginalBytes()))
                ->call();
        if ($sifei->success()) {

            error_log("Timbre recuperado");
            return $sifei->getResponse();
        } else {
            $this->error = $sifei->getHTMLError();
        }

        return false;
    }//getTimbre

    public function cancelaComprobante($rfcEmisor, Cancelation $cancelation, Certificate $certificate) {

        error_log($cancelation->getCancelationString());
        $sifei = (new SifeiCancelaCFDICaller($this->PAC))
                ->setParameter("rfcEmisor", $rfcEmisor)
                ->setParameter("pfx", $certificate->getBase64KeyStore())
                ->setParameter("passwordPfx", $certificate->getPassPhrase())
                ->setParameter("uuids", array($cancelation->getCancelationString()))
                ->call();
        if ($sifei->success()) {

            error_log("Comprobante ". $cancelation->getCancelationString() . " cancelado");
            return $sifei->getResponse();
        } else {

            $this->error = $sifei->getHTMLError();            
            error_log("Error cancelando " . $this->error);
        }

        return false;
    }

    public function getAcuseCancelacion($rfcEmisor, Cancelation $cancelation, Certificate $certificate) {
        $this->cancelaComprobante($rfcEmisor, $cancelation, $certificate);
    }   
}
