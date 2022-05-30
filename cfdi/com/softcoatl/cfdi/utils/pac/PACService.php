<?php
/**
 * PACService
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */
namespace com\softcoatl\cfdi\utils\pac;

use com\softcoatl\cfdi\Comprobante;
use com\softcoatl\cfdi\utils\pac\Cancelation;
use com\softcoatl\security\commons\Certificate;

interface PACService {

    public function timbraComprobante(Comprobante $cfdi);
    public function getTimbre(Comprobante $cfdi);
    public function cancelaComprobante($rfcEmisor, Cancelation $cancelation, Certificate $certificate);
    public function getAcuseCancelacion($rfcEmisor, Cancelation $cancelation, Certificate $certificate);
    public function getError();
}
