<?php

/*
 * CiaVO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

class CiaVO {

    private $cia;
    private $direccion;
    private $numeroext;
    private $numeroint;
    private $colonia;
    private $ciudad;
    private $estado;
    private $codigo;
    private $telefono;
    private $iva;
    private $rfc;
    private $password;
    private $facclavesat;
    
    public function getCia() {
        return $this->cia;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getNumeroext() {
        return $this->numeroext;
    }

    public function getNumeroint() {
        return $this->numeroint;
    }

    public function getColonia() {
        return $this->colonia;
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getIva() {
        return $this->iva;
    }

    public function getRfc() {
        return $this->rfc;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFacclavesat() {
        return $this->facclavesat;
    }

    public function setCia($cia) {
        $this->cia = $cia;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setNumeroext($numeroext) {
        $this->numeroext = $numeroext;
    }

    public function setNumeroint($numeroint) {
        $this->numeroint = $numeroint;
    }

    public function setColonia($colonia) {
        $this->colonia = $colonia;
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setIva($iva) {
        $this->iva = $iva;
    }

    public function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setFacclavesat($facclavesat) {
        $this->facclavesat = $facclavesat;
    }

    public function __toString() {
        return print_r($this, true);
    }
}
