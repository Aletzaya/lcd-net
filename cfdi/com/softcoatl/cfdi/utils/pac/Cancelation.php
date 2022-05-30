<?php
namespace com\softcoatl\cfdi\utils\pac;

class Cancelation {

    protected $uuid;
    protected $motivo;
    protected $folioSustitucion;

    public function __construct($uuid, $motivo, $folioSustitucion) {
        $this->uuid = $uuid;
        $this->motivo = $motivo;
        $this->folioSustitucion = $folioSustitucion;
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function getFolioSustitucion() {
        return $this->folioSustitucion;
    }

    public function setUuid($uuid) {
        $this->uuid = $uuid;
    }

    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    public function setFolioSustitucion($folioSustitucion) {
        $this->folioSustitucion = $folioSustitucion;
    }

    public function requireSustitution() {
        return "01"=== $this->motivo;
    }

    public function getCancelationString() {
        return "|" . $this->uuid . "|" . $this->motivo . "|" . ( $this->folioSustitucion? : "" ) . "|";
    }
}
