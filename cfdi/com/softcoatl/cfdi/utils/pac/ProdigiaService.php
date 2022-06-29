<?php
/*
 * ProdigiaService
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

use com\softcoatl\cfdi\utils\SOAPClient;
use com\softcoatl\cfdi\Comprobante;
use com\softcoatl\security\commons\Certificate;

abstract class ProdigiaCaller {

    /** @var ProdigiaPACWrapper */
    protected $pac;
    protected $parameters;
    /** @var \DOMDocument */
    protected $response;
    protected $soapClient;
    protected $soapException;

    function __construct(ProdigiaPACWrapper $pac) {
        $this->pac = $pac;
        $this->parameters = array(
            "usuario" => $this->pac->getUser(),
            "passwd" => $this->pac->getPassword(),
            "contrato" => $this->pac->getContrato());  
        $this->soapClient = SOAPClient::getClient($this->pac->getUrl());
    }

    public abstract function call();

    protected function invoke($operation) {
        try {
            error_log("Invocando " . $this->pac->getUrl() . "/" . $operation);
            error_log(print_r($this->parameters, true));
            $this->response = new \DOMDocument("1.0", "UTF-8");
            if (($response = $this->soapClient->call($operation, $this->parameters))) {
                error_log(print_r($response, true));
                $this->response->loadXML($response["return"]);
            }
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

    public function soapError() {
        return !empty($this->soapException);
    }

    public function success() {
        return !$this->soapError()
                && $this->status("true");
    }

    public abstract function getStatus();

    public function getErrorCode() {
        return $this->getResponse("codigo");
    }

    public function errorCode($code) {
        return $this->getErrorCode()==$code;
    }

    public function status($status) {
        return $this->getStatus()==$status;
    }

    public function getHTMLError() {
        return empty($this->soapException) ? 
                "<b>ERROR REPORTADO POR EL SAT (" . $this->getErrorCode() . ")<br/>" . $this->getResponse("mensaje") . ".</b><br/>" :
                "<b>ERROR DE PAC</b><br/>" . $this->soapException;
    }

    public function getResponse($field = "data") {
        return $this->response->getElementsByTagName($field) ? $this->response->getElementsByTagName($field)->item(0)->nodeValue :  "";
    }
}

class ProdigiaTimbradoCaller extends ProdigiaCaller {

    private static $operation = "timbrado";

    public function getStatus() {
        return $this->getResponse("timbradoOk");
    }

    public function call() {
        parent::invoke(static::$operation);
        return $this;
    }
}

class ProdigiaCancelCaller extends ProdigiaCaller {

    private static $operation = "cancelar";

    public function getStatus() {
        return $this->getResponse("statusOk");
    }

    public function call() {
        parent::invoke(static::$operation);
        return $this;
    }
}

class ProdigiaAcuseCaller extends ProdigiaCaller {

    private static $operation = "acuseCancelacion";

    public function getStatus() {
        return $this->getResponse("consultaOk");
    }

    public function call() {
        parent::invoke(static::$operation);
        return $this;
    }
}

class ProdigiaService extends BasePACService {

    function __construct($PAC) {
        parent::__construct($PAC);
    }

    public function timbraComprobante(Comprobante $cfdi) {
        $prodigia = (new ProdigiaTimbradoCaller($this->PAC))
                ->setParameter("cfdiXml", $cfdi->asXML()->saveXML())
                ->setParameter("opciones" , ["VERIFICAR_SERIE_FOLIO", "REGRESAR_CON_ERROR_307_XML"])
                ->call();

        if ($prodigia->success()) {

            error_log("Timbrando correctamente");
            return base64_decode($prodigia->getResponse("xmlBase64"));
        } else if ($prodigia->errorCode(307)) {

            error_log("Comprobante previamente timbrado");
            return base64_decode($prodigia->getResponse("xmlBase64"));
        } else {
            
            $this->error = $prodigia->getHTMLError();
        }
        return false;
    }

    public function getTimbre(Comprobante $cfdi) {
        return $this->timbraComprobante($cfdi);
    }

    public function cancelaComprobante($rfcEmisor, Cancelation $cancelation, Certificate $certificate) {

        $prodigia = (new ProdigiaCancelCaller($this->PAC))
                        ->setParameter("rfcEmisor", $rfcEmisor)
                        ->setParameter("arregloUUID", $cancelation->getCancelationString())
                        ->setParameter("cert", $certificate->getBase64Certificate())
                        ->setParameter("key", $certificate->getBase64PrivateKey())
                        ->setParameter("keyPass", $certificate->getPassPhrase())
                        ->call();

        if ($prodigia->success()) {

            $mensaje = $prodigia->getResponse("mensaje");
            if ($prodigia->errorCode(0)) {

                error_log("Se cancelo el Folio: " . $cancelation->getUuid());
                return base64_decode($prodigia->getResponse("acuseCancelBase64"));
            } else if ($prodigia->errorCode(21)) {

                error_log("Se envió solicitud de cancelación al receptor para el folio " + cancelation.getUuid() + ": " + mensaje);
                return "<status>En Proceso de Cancelación</status>";
            } else if ($prodigia->errorCode(202)) {

                error_log("Folio previamente cancelado: " . $cancelation->getUuid());
                return base64_decode($prodigia->getResponse("acuseCancelBase64"));
            }
        } else {

            $this->error = $prodigia->getHTMLError();
        }
        return false;
    }

    public function getAcuseCancelacion($rfcEmisor, Cancelation $cancelation, Certificate $certificate) {

        $prodigia = (new ProdigiaAcuseCaller($this->PAC))
                ->setParameter("uuid", $cancelation->getUuid())
                ->call();

        if ($prodigia->success()) {

                error_log("Acuse recuperado: " . $cancelation->getUuid());
                return base64_decode($prodigia->getResponse("acuseCancelBase64"));
        } else {

            $this->error = $prodigia->getHTMLError();
            error_log("ERROR " . $this->error);
        }
        return false;
    }
}
