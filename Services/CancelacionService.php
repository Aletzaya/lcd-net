<?php
namespace com\detifac\services;

require_once ('com/softcoatl/cfdi/utils/pac/Cancelation.php');
require_once ('com/softcoatl/cfdi/utils/pac/ProdigiaCancelationWrapper.php');
require_once ('com/softcoatl/cfdi/SelloCFDI.php');
require_once ('com/softcoatl/cfdi/utils/pac/PACServiceFactory.php');
require_once ('com/softcoatl/security/commons/SATCertificates.php');
require_once ('data/ProveedorPACDAO.php');
require_once ("Service.php");

use com\softcoatl\cfdi\Comprobante;
use com\softcoatl\cfdi\utils\pac\Cancelation;
use com\softcoatl\cfdi\utils\pac\PACServiceFactory;
use com\detisa\detifac\CancelationDAO;

class CancelationService implements Service {

    private function getCancelation(Comprobante $comprobante): Cancelation {
        error_log("Cargando cancelación para " . $comprobante->getTimbreFiscalDigital()->getUUID());
        return CancelationDAO::getCancelation($comprobante->getTimbreFiscalDigital()->getUUID());
    }

    public function do(...$parameters) {

        $pac = (new \ProveedorPACDAO())->getActive();
        $service = PACServiceFactory::getPACService($pac);
        $acuse = $service->cancelaComprobante($parameters[0]->getEmisor()->getRfc(), $this->getCancelation($parameters[0]), $parameters[1]);
        if (!$acuse) {
            throw new \Exception($service->getError());
        }
        return $acuse;
    }
}
