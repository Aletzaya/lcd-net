<?php

/*
 * ClientesVO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

class ClientesVO {

    private $id;
    private $nombre;
    private $direccion;
    private $colonia;
    private $municipio;
    private $alias;
    private $telefono;
    private $activo;
    private $contacto;
    private $observaciones;
    private $tipodepago;
    private $limite;
    private $rfc;
    private $codigo;
    private $correo;
    private $numeroext;
    private $numeroint;
    private $enviarcorreo;
    private $cuentaban;
    private $estado;
    private $formadepago;
    private $correo2;
    private $puntos;
    private $cia;
    private $desgloseIEPS;
    private $ncc;

    private function nvl($value) {
        return $value === NULL ? "" : $value;
    }
    function getId() {
        return $this->nvl($this->id);
    }

    function getNombre() {
        return $this->nvl($this->nombre);
    }

    function getDireccion() {
        return $this->nvl($this->direccion);
    }

    function getColonia() {
        return $this->nvl($this->colonia);
    }

    function getMunicipio() {
        return $this->nvl($this->municipio);
    }

    function getAlias() {
        return $this->nvl($this->alias);
    }

    function getTelefono() {
        return $this->nvl($this->telefono);
    }

    function getActivo() {
        return $this->nvl($this->activo);
    }

    function getContacto() {
        return $this->nvl($this->contacto);
    }

    function getObservaciones() {
        return $this->nvl($this->observaciones);
    }

    function getTipodepago() {
        return $this->nvl($this->tipodepago);
    }

    function getLimite() {
        return $this->nvl($this->limite);
    }

    function getRfc() {
        return $this->nvl($this->rfc);
    }

    function getCodigo() {
        return $this->nvl($this->codigo);
    }

    function getCorreo() {
        return $this->nvl($this->correo);
    }

    function getNumeroext() {
        return $this->nvl($this->numeroext);
    }

    function getNumeroint() {
        return $this->nvl($this->numeroint);
    }

    function getEnviarcorreo() {
        return $this->nvl($this->enviarcorreo);
    }

    function getCuentaban() {
        return $this->nvl($this->cuentaban);
    }

    function getEstado() {
        return $this->nvl($this->estado);
    }

    function getFormadepago() {
        return $this->nvl($this->formadepago);
    }

    function getCorreo2() {
        return $this->nvl($this->correo2);
    }

    function getPuntos() {
        return $this->nvl($this->puntos);
    }

    function getCia() {
        return $this->nvl($this->cia);
    }

    function getDesgloseIEPS() {
        return $this->nvl($this->desgloseIEPS);
    }

    function getNcc() {
        return $this->nvl($this->ncc);
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setColonia($colonia) {
        $this->colonia = $colonia;
    }

    function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    function setAlias($alias) {
        $this->alias = $alias;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function setContacto($contacto) {
        $this->contacto = $contacto;
    }

    function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
    }

    function setTipodepago($tipodepago) {
        $this->tipodepago = $tipodepago;
    }

    function setLimite($limite) {
        $this->limite = $limite;
    }

    function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setNumeroext($numeroext) {
        $this->numeroext = $numeroext;
    }

    function setNumeroint($numeroint) {
        $this->numeroint = $numeroint;
    }

    function setEnviarcorreo($enviarcorreo) {
        $this->enviarcorreo = $enviarcorreo;
    }

    function setCuentaban($cuentaban) {
        $this->cuentaban = $cuentaban;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFormadepago($formadepago) {
        $this->formadepago = $formadepago;
    }

    function setCorreo2($correo2) {
        $this->correo2 = $correo2;
    }

    function setPuntos($puntos) {
        $this->puntos = $puntos;
    }

    function setCia($cia) {
        $this->cia = $cia;
    }

    function setDesgloseIEPS($desgloseIEPS) {
        $this->desgloseIEPS = $desgloseIEPS;
    }

    function setNcc($ncc) {
        $this->ncc = $ncc;
    }

    /**
     * Parses paramaeters array into ClientesV= object
     * @param array $queryParameters
     * @return ClientesVO
     */
    public static function parse($queryParameters) {
        $cliente = new ClientesVO();
        $cliente->setId($queryParameters['Cliente']);
        $cliente->setNombre($queryParameters['Nombre']);
        $cliente->setAlias($queryParameters['Alias']);
        $cliente->setRfc($queryParameters['Rfc']);
        $cliente->setDireccion($queryParameters['Direccion']);
        $cliente->setNumeroext($queryParameters['Numeroext']);
        $cliente->setNumeroint($queryParameters['Numeroint']);
        $cliente->setColonia($queryParameters['Colonia']);
        $cliente->setMunicipio($queryParameters['Municipio']);
        $cliente->setEstado($queryParameters['Estado']);
        $cliente->setCodigo($queryParameters['Codigo']);
        $cliente->setContacto($queryParameters['Contacto']);
        $cliente->setTelefono($queryParameters['Telefono']);
        $cliente->setCorreo($queryParameters['Correo']);
        $cliente->setEnviarcorreo($queryParameters['Enviarcorreo']);
        $cliente->setCuentaban($queryParameters['Cuentaban']);
        $cliente->setFormadepago($queryParameters['Formadepago']);
        $cliente->setTipodepago($queryParameters['Tipodepago']);
        $cliente->setLimite($queryParameters['Limite']);
        $cliente->setDesgloseIEPS($queryParameters['DesgloseIeps']);
        $cliente->setNcc($queryParameters['Ncc']);
        error_log($cliente);
        return $cliente;
    }//parse

    /**
     * Overrides toString function
     * @return String
     */
    public function __toString() {
        return "ClientesVO={id=".$this->id
                    . ",cia=".$this->cia
                    . ",nombre=".$this->nombre
                    . ",alias=".$this->alias
                    . ",rfc=".$this->rfc
                    . ",direccion=".$this->direccion
                    . ",numeroext=".$this->numeroext
                    . ",numeroint=".$this->numeroint
                    . ",colonia=".$this->colonia
                    . ",municipio=".$this->municipio
                    . ",estado=".$this->estado
                    . ",contacto=".$this->contacto
                    . ",telefono=".$this->telefono
                    . ",correo=".$this->correo
                    . ",correo2=".$this->correo2
                    . ",enviarcorreo=".$this->enviarcorreo
                    . ",cuentaban=".$this->cuentaban
                    . ",formadepago=".$this->formadepago
                    . ",tipodepago=".$this->tipodepago
                    . ",limite=".$this->limite
                    . ",codigo=".$this->codigo
                    . ",puntos=".$this->puntos
                    . ",desgloseIEPS=".$this->desgloseIEPS
                    . ",ncc=".$this->ncc
                    . ",observaciones=".$this->observaciones
                    . ",activo=".$this->activo."}";
    }//__toString
}//ClienteVO
