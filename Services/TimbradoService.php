<?php

namespace com\detifac\services;

require_once ('cfdi/com/softcoatl/cfdi/SelloCFDI.php');
require_once ('cfdi/com/softcoatl/cfdi/utils/pac/PACServiceFactory.php');
require_once ('cfdi/com/softcoatl/security/commons/SATCertificates.php');
require_once ('data/ProveedorPACDAO.php');
require_once ("Service.php");

use com\softcoatl\security\commons\Certificate;
use com\softcoatl\cfdi\Comprobante;
use com\softcoatl\cfdi\SelloCFDI;
use com\softcoatl\cfdi\utils\pac\PACServiceFactory;

class TimbradoService implements Service {

    private $error;

    private function sellaComprobante(Comprobante $comprobante, Certificate $certificate) {

        $sello = new SelloCFDI($certificate);
        $sello->sellaComprobante($comprobante);
        error_log("Timbrando comprobante " . $comprobante->asXML()->saveXML());
        $validate = $comprobante->schemaValidate();
        if ($validate !== true) {
            error_log($validate);
            //throw new \Exception(str_replace("\n", "</br>", $validate));
        }
        return $comprobante;
    }

    public function doin(...$parameters) {
        $pac = (new \ProveedorPACDAO())->getActive();
        $service = PACServiceFactory::getPACService($pac);
        $xml = $service->timbraComprobante($this->sellaComprobante($parameters[0], $parameters[1]));
        if ($xml === false) {
            //throw new \Exception($service->getError());
            $this->error = $service->getError();
            return false;
        }

        error_log($xml);
        return $xml;
    }

    public function getError() {
        return $this->error;
    }

}
