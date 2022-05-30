<?php
namespace com\softcoatl\cfdi\utils\pac;

class ProdigiaCancelationWrapper extends Cancelation {

    /** @var Cancelation */
    protected $cancelation;
    protected $rfcEmisor;
    protected $rfcReceptor;
    protected $total;
    
    public function __construct(Cancelation $cancelation) {
        $this->cancelation = $cancelation;
    }

    public function getFolioSustitucion() {
        return $this->cancelation->getFolioSustitucion();
    }

    public function getMotivo() {
        return $this->cancelation->getMotivo();
    }

    public function getUuid() {
        return $this->cancelation->getUuid();
    }
    
    public function getRfcEmisor() {
        return $this->rfcEmisor;
    }

    public function getRfcReceptor() {
        return $this->rfcReceptor;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setUuid($uuid) {
        $this->cancelation->setUuid($uuid);
    }

    public function setMotivo($motivo) {
        $this->cancelation->setMotivo($motivo);
    }

    public function setFolioSustitucion($folioSustitucion) {
        $this->cancelation->setFolioSustitucion($folioSustitucion);
    }

    public function setRfcEmisor($rfcEmisor) {
        $this->rfcEmisor = $rfcEmisor;
    }

    public function setRfcReceptor($rfcReceptor) {
        $this->rfcReceptor = $rfcReceptor;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function requireSustitution() {
        $this->cancelation->requireSustitution();
    }

    public function getCancelationString() {
        return $this->cancelation->getUuid() . "|"  
                . $this->rfcReceptor . "|" 
                . $this->rfcEmisor . "|" 
                . $this->total . "|" 
                . $this->cancelation->getMotivo() . "|"
                . $this->cancelation->getFolioSustitucion();
    }
}
