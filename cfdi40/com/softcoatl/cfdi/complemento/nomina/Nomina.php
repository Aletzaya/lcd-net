<?php
/*
 * Nomina
 * cfdi®
 * © 2018, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2018
 */
namespace com\softcoatl\cfdi\complemento\nomina;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\utils\Reflection;

class Nomina implements CFDIElement {

    /** @var Nomina\Emisor */
    private $Emisor;
    /** @var Nomina\Receptor */
    private $Receptor;
    /** @var Nomina\Percepciones */
    private $Percepciones;
    /** @var Nomina\Deducciones */
    private $Deducciones;
    /** @var Nomina\OtrosPagos */
    private $OtrosPagos;
    /** @var Nomina\Incapacidades */
    private $Incapacidades;
    private $Version = "1.2";
    private $TipoNomina;
    private $FechaPago;
    private $FechaInicialPago;
    private $FechaFinalPago;
    private $NumDiasPagados;
    private $TotalPercepciones;
    private $TotalDeducciones;
    private $TotalOtrosPagos;

    function getEmisor() {
        return $this->Emisor;
    }

    function getReceptor() {
        return $this->Receptor;
    }

    function getPercepciones() {
        return $this->Percepciones;
    }

    function getDeducciones() {
        return $this->Deducciones;
    }

    function getOtrosPagos() {
        return $this->OtrosPagos;
    }

    function getIncapacidades() {
        return $this->Incapacidades;
    }

    function getVersion() {
        return $this->Version;
    }

    function getTipoNomina() {
        return $this->TipoNomina;
    }

    function getFechaPago() {
        return $this->FechaPago;
    }

    function getFechaInicialPago() {
        return $this->FechaInicialPago;
    }

    function getFechaFinalPago() {
        return $this->FechaFinalPago;
    }

    function getNumDiasPagados() {
        return $this->NumDiasPagados;
    }

    function getTotalPercepciones() {
        return $this->TotalPercepciones;
    }

    function getTotalDeducciones() {
        return $this->TotalDeducciones;
    }

    function getTotalOtrosPagos() {
        return $this->TotalOtrosPagos;
    }

    function setEmisor(Nomina\Emisor $Emisor) {
        $this->Emisor = $Emisor;
    }

    function setReceptor(Nomina\Receptor $Receptor) {
        $this->Receptor = $Receptor;
    }

    function setPercepciones(Nomina\Percepciones $Percepciones) {
        $this->Percepciones = $Percepciones;
    }

    function setDeducciones(Nomina\Deducciones $Deducciones) {
        $this->Deducciones = $Deducciones;
    }

    function setOtrosPagos(Nomina\OtrosPagos $OtrosPagos) {
        $this->OtrosPagos = $OtrosPagos;
    }

    function setIncapacidades(Nomina\Incapacidades $Incapacidades) {
        $this->Incapacidades = $Incapacidades;
    }

    function setVersion($Version) {
        $this->Version = $Version;
    }

    function setTipoNomina($TipoNomina) {
        $this->TipoNomina = $TipoNomina;
    }

    function setFechaPago($FechaPago) {
        $this->FechaPago = $FechaPago;
    }

    function setFechaInicialPago($FechaInicialPago) {
        $this->FechaInicialPago = $FechaInicialPago;
    }

    function setFechaFinalPago($FechaFinalPago) {
        $this->FechaFinalPago = $FechaFinalPago;
    }

    function setNumDiasPagados($NumDiasPagados) {
        $this->NumDiasPagados = $NumDiasPagados;
    }

    function setTotalPercepciones($TotalPercepciones) {
        $this->TotalPercepciones = $TotalPercepciones;
    }

    function setTotalDeducciones($TotalDeducciones ){
        $this->TotalDeducciones = $TotalDeducciones;
    }

    function setTotalOtrosPagos($TotalOtrosPagos) {
        $this->TotalOtrosPagos = $TotalOtrosPagos;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Nomina\Emisor)
                                && !($val instanceof Nomina\Receptor)
                                && !($val instanceof Nomina\Percepciones)
                                && !($val instanceof Nomina\Deducciones)
                                && !($val instanceof Nomina\OtrosPagos)
                                && !($val instanceof Nomina\Incapacidades);
        }); 
    }

    public function asXML($root) {

        if ($root->ownerDocument->documentElement
                && empty($root->ownerDocument->documentElement->attributes->getNamedItem("xmlns:nomina12"))) {
            $root->ownerDocument->documentElement->setAttribute("xmlns:pago10", "http://www.sat.gob.mx/nomina12");
            $root->ownerDocument->documentElement->setAttribute("xsi:schemaLocation", 
                            $root->ownerDocument->documentElement->getAttribute("xsi:schemaLocation") 
                        .   " http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd");
        }
        $Nomina = $root->ownerDocument->createElement("nomina12:Nomina");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Nomina->setAttribute($attr, $value);
        }

        if (!empty($this->Emisor)) {
            $Nomina->appendChild($this->Emisor->asXML($root));
        }
        if (!empty($this->Receptor)) {
            $Nomina->appendChild($this->Receptor->asXML($root));
        }
        if (!empty($this->Percepciones)) {
            $Nomina->appendChild($this->Percepciones->asXML($root));
        }
        if (!empty($this->Deducciones)) {
            $Nomina->appendChild($this->Deducciones->asXML($root));
        }
        if ($this->OtrosPagos) {
            $Nomina->appendChild($this->OtrosPagos->asXML($root));
        }
        if ($this->Incapacidades) {
            $Nomina->appendChild($this->Incapacidades->asXML($root));
        }
        return $Nomina;
    }

    public static function parse($DOMElement) {
        if (strpos($DOMElement->nodeName, ':Nomina')) {
            $Nomina = new Nomina();
            Reflection::setValues($Nomina, $DOMElement);
            for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
                $node = $DOMElement->childNodes->item($i);
                if (strpos($node->nodeName, ":Emisor")!==false) {
                    $Nomina->setEmisor(Nomina\Emisor::parse($node));
                } else if (strpos($node->nodeName, ":Receptor")!==false) {
                    $Nomina->setReceptor(Nomina\Receptor::parse($node));
                } else if (strpos($node->nodeName, ":Percepciones")!==false) {
                    $Nomina->setPercepciones(Nomina\Percepciones::parse($node));
                } else if (strpos($node->nodeName, ":Deducciones")!==false) {
                    $Nomina->setDeducciones(Nomina\Deducciones::parse($node));
                } else if (strpos($node->nodeName, ":OtrosPagos")!==false) {
                    $Nomina->setOtrosPagos(Nomina\OtrosPagos::parse($node));
                } else if (strpos($node->nodeName, ":Incapacidades")!==false) {
                    $Nomina->setIncapacidades(Nomina\Incapacidades::parse($node));
                }
            }
            return $Nomina;
        }
        return false;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Emisor implements CFDIElement {

    /** @var Emisor\EntidadSNCF */
    private $EntidadSNCF;
    private $Curp;
    private $RegistroPatronal;
    private $RfcPatronOrigen;

    function getEntidadSNCF() {
        return $this->EntidadSNCF;
    }

    function getCurp() {
        return $this->Curp;
    }

    function getRegistroPatronal() {
        return $this->RegistroPatronal;
    }

    function getRfcPatronOrigen() {
        return $this->RfcPatronOrigen;
    }

    function setEntidadSNCF(Emisor\EntidadSNCF $EntidadSNCF) {
        $this->EntidadSNCF = $EntidadSNCF;
    }

    function setCurp($Curp) {
        $this->Curp = $Curp;
    }

    function setRegistroPatronal($RegistroPatronal) {
        $this->RegistroPatronal = $RegistroPatronal;
    }

    function setRfcPatronOrigen($RfcPatronOrigen) {
        $this->RfcPatronOrigen = $RfcPatronOrigen;
    }

    public function hasEntidadSNCF() {
        return $this->EntidadSNCF!==null;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Emisor\EntidadSNCF);
        }); 
    }

    public function asXML($document = null) {
        $Emisor = $root->ownerDocument->createElement("nomina12:Emisor");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Emisor->setAttribute($attr, $value);
        }
        if (!empty($this->EntidadSNCF)) {
            $Emisor->appendChild($this->EntidadSNCF->asXML($root));
        }
        return $Emisor;
    }

    public static function parse($DOMElement) {
        $Emisor = new Emisor();
        Reflection::setValues($Emisor, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->chilNodes->item($i);
            if (strpos($node->nodeName, ':EntidadSNCF')!==false) {
                $Emisor->setEntidadSNCF(Emisor\EntidadSNCF::parse($node));
            }
        }
        return $Emisor;
    }
}

class Receptor implements CFDIElement {

    /** @var Receptor\SubContratacion[] */
    private $SubContratacion = array();
    private $Curp;
    private $NumSeguridadSocial;
    private $FechaInicioRelLaboral;
    private $Antigüedad;
    private $TipoContrato;
    private $Sindicalizado;
    private $TipoJornada;
    private $TipoRegimen;
    private $NumEmpleado;
    private $Departamento;
    private $Puesto;
    private $RiesgoPuesto;
    private $PeriodicidadPago;
    private $Banco;
    private $CuentaBancaria;
    private $SalarioBaseCotApor;
    private $SalarioDiarioIntegrado;
    private $ClaveEntFed;

    function getSubContratacion() {
        return $this->SubContratacion;
    }

    function getCurp() {
        return $this->Curp;
    }

    function getNumSeguridadSocial() {
        return $this->NumSeguridadSocial;
    }

    function getFechaInicioRelLaboral() {
        return $this->FechaInicioRelLaboral;
    }

    function getAntigüedad() {
        return $this->Antigüedad;
    }

    function getTipoContrato() {
        return $this->TipoContrato;
    }

    function getSindicalizado() {
        return $this->Sindicalizado;
    }

    function getTipoJornada() {
        return $this->TipoJornada;
    }

    function getTipoRegimen() {
        return $this->TipoRegimen;
    }

    function getNumEmpleado() {
        return $this->NumEmpleado;
    }

    function getDepartamento() {
        return $this->Departamento;
    }

    function getPuesto() {
        return $this->Puesto;
    }

    function getRiesgoPuesto() {
        return $this->RiesgoPuesto;
    }

    function getPeriodicidadPago() {
        return $this->PeriodicidadPago;
    }

    function getBanco() {
        return $this->Banco;
    }

    function getCuentaBancaria() {
        return $this->CuentaBancaria;
    }

    function getSalarioBaseCotApor() {
        return $this->SalarioBaseCotApor;
    }

    function getSalarioDiarioIntegrado() {
        return $this->SalarioDiarioIntegrado;
    }

    function getClaveEntFed() {
        return $this->ClaveEntFed;
    }

    public function setSubContratacion(array $SubContratacion) {
        $this->SubContratacion = $SubContratacion;
    }

    function addSubContratacion(Receptor\SubContratacion $SubContratacion) {
        $this->SubContratacion[] = $SubContratacion;
    }

    function setCurp($Curp) {
        $this->Curp = $Curp;
    }

    function setNumSeguridadSocial($NumSeguridadSocial) {
        $this->NumSeguridadSocial = $NumSeguridadSocial;
    }

    function setFechaInicioRelLaboral($FechaInicioRelLaboral) {
        $this->FechaInicioRelLaboral = $FechaInicioRelLaboral;
    }

    function setAntigüedad($Antigüedad) {
        $this->Antigüedad = $Antigüedad;
    }

    function setTipoContrato($TipoContrato) {
        $this->TipoContrato = $TipoContrato;
    }

    function setSindicalizado($Sindicalizado) {
        $this->Sindicalizado = $Sindicalizado;
    }

    function setTipoJornada($TipoJornada) {
        $this->TipoJornada = $TipoJornada;
    }

    function setTipoRegimen($TipoRegimen) {
        $this->TipoRegimen = $TipoRegimen;
    }

    function setNumEmpleado($NumEmpleado) {
        $this->NumEmpleado = $NumEmpleado;
    }

    function setDepartamento($Departamento) {
        $this->Departamento = $Departamento;
    }

    function setPuesto($Puesto) {
        $this->Puesto = $Puesto;
    }

    function setRiesgoPuesto($RiesgoPuesto) {
        $this->RiesgoPuesto = $RiesgoPuesto;
    }

    function setPeriodicidadPago($PeriodicidadPago) {
        $this->PeriodicidadPago = $PeriodicidadPago;
    }

    function setBanco($Banco) {
        $this->Banco = $Banco;
    }

    function setCuentaBancaria($CuentaBancaria) {
        $this->CuentaBancaria = $CuentaBancaria;
    }

    function setSalarioBaseCotApor($SalarioBaseCotApor) {
        $this->SalarioBaseCotApor = $SalarioBaseCotApor;
    }

    function setSalarioDiarioIntegrado($SalarioDiarioIntegrado) {
        $this->SalarioDiarioIntegrado = $SalarioDiarioIntegrado;
    }

    function setClaveEntFed($ClaveEntFed) {
        $this->ClaveEntFed = $ClaveEntFed;
    }

    public function hasSubContratacion() {
        return !empty($this->SubContratacion);               
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val));
        }); 
    }

    public function asXML($root) {
        $Receptor = $root->ownerDocument->createElement("nomina12:Receptor");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Receptor->setAttribute($attr, $value);
        }
        if ($this->hasSubContratacion()) {
            foreach ($this->SubContratacion as $SubContratacion) {
                $Receptor->appendChild($SubContratacion->asXML($root));
            }
        }
        return $Receptor;
    }

    public static function parse($DOMElement) {
        $Receptor = new Receptor();
        Reflection::setValues($Receptor, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->chilNodes->item($i);
            if (strpos($node->nodeName, ':SubContratacion')!==false) {
                $Receptor->addSubContratacion(Receptor\SubContratacion::parse($node));
            }
        }
        return $Receptor;
    }
}

class Percepciones implements CFDIElement {

    /** @var Percepciones\Percepcion[] */
    private $Percepcion = array();
    /** @var Percepciones\JubilacionPensionRetiro */
    private $JubilacionPensionRetiro;
    /** @var Percepciones\SeparacionIndemnizacion */
    private $SeparacionIndemnizacion;
    private $TotalSueldos;
    private $TotalSeparacionIndemnizacion;
    private $TotalJubilacionPensionRetiro;
    private $TotalGravado;
    private $TotalExento;

    function getPercepcion() {
        return $this->Percepcion;
    }

    function getJubilacionPensionRetiro() {
        return $this->JubilacionPensionRetiro;
    }

    function getSeparacionIndemnizacion() {
        return $this->SeparacionIndemnizacion;
    }

    function getTotalSueldos() {
        return $this->TotalSueldos;
    }

    function getTotalSeparacionIndemnizacion() {
        return $this->TotalSeparacionIndemnizacion;
    }

    function getTotalJubilacionPensionRetiro() {
        return $this->TotalJubilacionPensionRetiro;
    }

    function getTotalGravado() {
        return $this->TotalGravado;
    }

    function getTotalExento() {
        return $this->TotalExento;
    }

    public function setPercepcion(array $Percepcion) {
        $this->Percepcion = $Percepcion;
    }

    function addPercepcion(Percepciones\Percepcion $Percepcion) {
        $this->Percepcion[] = $Percepcion;
    }

    function setJubilacionPensionRetiro(Percepciones\JubilacionPensionRetiro $JubilacionPensionRetiro) {
        $this->JubilacionPensionRetiro = $JubilacionPensionRetiro;
    }

    function setSeparacionIndemnizacion(Percepciones\SeparacionIndemnizacion $SeparacionIndemnizacion) {
        $this->SeparacionIndemnizacion = $SeparacionIndemnizacion;
    }

    function setTotalSueldos($TotalSueldos) {
        $this->TotalSueldos = $TotalSueldos;
    }

    function setTotalSeparacionIndemnizacion($TotalSeparacionIndemnizacion) {
        $this->TotalSeparacionIndemnizacion = $TotalSeparacionIndemnizacion;
    }

    function setTotalJubilacionPensionRetiro($TotalJubilacionPensionRetiro) {
        $this->TotalJubilacionPensionRetiro = $TotalJubilacionPensionRetiro;
    }

    function setTotalGravado($TotalGravado) {
        $this->TotalGravado = $TotalGravado;
    }

    function setTotalExento($TotalExento) {
        $this->TotalExento = $TotalExento;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Percepciones\JubilacionPensionRetiro)
                                && !($val instanceof Percepciones\SeparacionIndemnizacion);
        });
    }

    public function asXML($root) {
        $Percepciones = $root->ownerDocument->createElement("nomina12:Percepciones");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Percepciones->setAttribute($attr, $value);
        }
        if (!empty($this->Percepcion)) {
            foreach ($this->Percepcion as $percepcion) {
                $Percepciones->appendChild($percepcion->asXML($root));
            }
        }
        if (!empty($this->JubilacionPensionRetiro)) {
            $Percepciones->appendChild($this->JubilacionPensionRetiro->asXML($root));
        }
        if (!empty($this->SeparacionIndemnizacion)) {
            $Percepciones->appendChild($this->SeparacionIndemnizacion->asXML($root));
        }
        return $Percepciones;
    }

    public static function parse($DOMElement) {
        $Percepciones = new Percepciones();
        Reflection::setValues($Percepciones, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, ":Percepcion")!==false) {
                $Percepciones->addPercepcion(Percepciones\Percepcion::parse($node));
            } else if (strpos($node->nodeName, ":JubilacionPensionRetiro")!==false) {
                $Percepciones->setJubilacionPensionRetiro(Percepciones\JubilacionPensionRetiro::parse($node));
            } else if (strpos($node->nodeName, ":SeparacionIndemnizacion")!==false) {
                $Percepciones->setSeparacionIndemnizacion(Percepciones\SeparacionIndemnizacion::parse($node));
            }
        }
        return $Percepciones;
    }
}

class Deducciones implements CFDIElement {

    /** @var Deducciones\Deduccion[] */
    private $Deduccion = array();
    private $TotalOtrasDeducciones;
    private $TotalImpuestosRetenidos;

    function getDeduccion() {
        return $this->Deduccion;
    }

    function getTotalOtrasDeducciones() {
        return $this->TotalOtrasDeducciones;
    }

    function getTotalImpuestosRetenidos() {
        return $this->TotalImpuestosRetenidos;
    }

    function setDeduccion(array $Deduccion) {
        $this->Deduccion = $Deduccion;
    }

    function addDeduccion(Deducciones\Deduccion $Deduccion) {
        $this->Deduccion[] = $Deduccion;
    }

    function setTotalOtrasDeducciones($TotalOtrasDeducciones) {
        $this->TotalOtrasDeducciones = $TotalOtrasDeducciones;
    }

    function setTotalImpuestosRetenidos($TotalImpuestosRetenidos) {
        $this->TotalImpuestosRetenidos = $TotalImpuestosRetenidos;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val));
        });
    }

    public function asXML($root) {
        $Deducciones = $root->ownerDocument->createElement("nomina12:Deducciones");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Deducciones->setAttribute($attr, $value);
        }
        if (!empty($this->Deduccion)) {
            foreach ($this->Deduccion as $deduccion) {
                $Deducciones->appendChild($deduccion->asXML($root));
            }
        }
        return $Deducciones;
    }

    public static function parse($DOMElement) {
        $Deducciones = new Deducciones();
        Reflection::setValues($Deducciones, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->chilNodes->item($i);
            if (strpos($node->nodeName, ':Deduccion')!==false) {
                $Deducciones->addDeduccion(Deducciones\Deduccion::parse($node));
            }
        }
    }
}

class OtrosPagos implements CFDIElement {

    /** @var OtrosPagos\OtroPago[] */
    private $OtroPago = array();

    function getOtroPago() {
        return $this->OtroPago;
    }

    public function setOtroPago(array $OtroPago) {
        $this->OtroPago = $OtroPago;
    }

    function addOtroPago(OtrosPagos\OtroPago $otroPago) {
        $this->OtroPago [] = $otroPago;
    }

    public function asXML($root) {
        $OtrosPagos = $root->ownerDocument->createElement("nomina12:OtrosPagos");
        if (!empty($this->OtroPago)) {
            foreach ($this->OtroPago as $otroPagoItem) {
                $OtrosPagos->appendChild($otroPagoItem->asXML($root));
            }
        }
        return $OtrosPagos;
    }

    public static function parse($DOMElement) {
        $OtrosPagos = new OtrosPagos();
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->chilNodes->item($i);
            if (strpos($node->nodeName, ':OtroPago')!==false) {
                $OtrosPagos->addOtroPago(OtrosPagos\OtroPago::parse($node));
            }
        }
        return $OtrosPagos;
    }
}

class Incapacidades implements CFDIElement {

    /** @var Incapacidades\Incapacidad[] */
    private $Incapacidad = array();

    function getIncapacidad() {
        return $this->Incapacidad;
    }

    public function setIncapacidad(array $Incapacidad) {
        $this->Incapacidad = $Incapacidad;
    }

    function addIncapacidad(Incapacidades\Incapacidad $Incapacidad) {
        $this->Incapacidad[] = $Incapacidad;
    }

    public function asXML($root) {
        $Incapacidades = $root->ownerDocument->createElement("nomina12:Incapacidades");
        if (!empty($this->Incapacidad)) {
            foreach ($this->Incapacidad as $incapacidadItem) {
                $Incapacidades->appendChild($incapacidadItem->asXML($document));
            }
        }
        return $Incapacidades;
    }

    public static function parse($DOMElement) {
        $Incapacidades = new Incapacidades();
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->chilNodes->item($i);
            if (strpos($node->nodeName, ':Incapacidad')!==false) {
                $Incapacidades->addIncapacidad(Incapacidades\Incapacidad::parse($node));
            }
        }
        return $Incapacidades;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\Deducciones;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Deduccion implements CFDIElement {

    private $TipoDeduccion;
    private $Clave;
    private $Concepto;
    private $Importe;

    function getTipoDeduccion() {
        return $this->TipoDeduccion;
    }

    function getClave() {
        return $this->Clave;
    }

    function getConcepto() {
        return $this->Concepto;
    }

    function getImporte() {
        return $this->Importe;
    }

    function setTipoDeduccion($TipoDeduccion) {
        $this->TipoDeduccion = $TipoDeduccion;
    }

    function setClave($Clave) {
        $this->Clave = $Clave;
    }

    function setConcepto($Concepto) {
        $this->Concepto = $Concepto;
    }

    function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function asXML($root) {
        $Deduccion = $root->ownerDocument->createElement("nomina12:Deduccion");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $attr=>$value) {
            $Deduccion->setAttribute($attr, $value);
        }
        return $Deduccion;
    }
    
    public static function parse($DOMElement) {
        $Deduccion = new Deduccion();
        Reflection::setAttributes($Deduccion, $DOMElement);
        return $Deduccion;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\Emisor;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class EntidadSNCF implements CFDIElement {

    private $OrigenRecurso;
    private $MontoRecursoPropio;
    
    function getOrigenRecurso() {
        return $this->OrigenRecurso;
    }

    function getMontoRecursoPropio() {
        return $this->MontoRecursoPropio;
    }

    function setOrigenRecurso($OrigenRecurso) {
        $this->OrigenRecurso = $OrigenRecurso;
    }

    function setMontoRecursoPropio($MontoRecursoPropio) {
        $this->MontoRecursoPropio = $MontoRecursoPropio;
    }

    public function asXML($root) {
        $EntidadSNCF = $root->ownerDocument->createElement("nomina12:EntidadSNCF");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $EntidadSNCF->setAttribute($key, $value);
        }
        return $EntidadSNCF;
    }
    
    public static function parse($DOMElement) {
        $EntidadSNCF = new EntidadSNCF();
        Reflection::setAttributes($EntidadSNCF, $DOMElement);
        return $EntidadSNCF;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\Incapacidades;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Incapacidad implements CFDIElement {
    
    private $DiasIncapacidad;
    private $TipoIncapacidad;
    private $ImporteMonetario;
    
    function getDiasIncapacidad() {
        return $this->DiasIncapacidad;
    }

    function getTipoIncapacidad() {
        return $this->TipoIncapacidad;
    }

    function getImporteMonetario() {
        return $this->ImporteMonetario;
    }

    function setDiasIncapacidad($DiasIncapacidad) {
        $this->DiasIncapacidad = $DiasIncapacidad;
    }

    function setTipoIncapacidad($TipoIncapacidad) {
        $this->TipoIncapacidad = $TipoIncapacidad;
    }

    function setImporteMonetario($ImporteMonetario) {
        $this->ImporteMonetario = $ImporteMonetario;
    }

    public function asXML($root) {
        $Incapacidad = $root->ownerDocument->createElement("nomina12:Incapcacidad");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $Incapacidad->setAttribute($key, $value);
        }
        return $Incapacidad;
    }

    public static function parse($DOMElement) {
        $Incapacidad = new Incapacidad();
        Reflection::setAttributes($Incapacidad, $DOMElement);
        return $Incapacidad;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\OtrosPagos;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class OtroPago implements CFDIElement {

    /** @var OtroPago\SubsidioAlEmpleo */
    private $SubsidioAlEmpleo;
    /** @var OtroPago\CompensacionSaldosAFavor */
    private $CompensacionSaldosAFavor;
    private $TipoOtroPago;
    private $Clave;
    private $Concepto;
    private $Importe;
    
    function getSubsidioAlEmpleo() {
        return $this->SubsidioAlEmpleo;
    }

    function getCompensacionSaldosAFavor() {
        return $this->CompensacionSaldosAFavor;
    }

    function getTipoOtroPago() {
        return $this->TipoOtroPago;
    }

    function getClave() {
        return $this->Clave;
    }

    function getConcepto() {
        return $this->Concepto;
    }

    function getImporte() {
        return $this->Importe;
    }

    function setSubsidioAlEmpleo(OtroPago\SubsidioAlEmpleo $SubsidioAlEmpleo) {
        $this->SubsidioAlEmpleo = $SubsidioAlEmpleo;
    }

    function setCompensacionSaldosAFavor(OtroPago\CompensacionSaldosAFavor $CompensacionSaldosAFavor) {
        $this->CompensacionSaldosAFavor = $CompensacionSaldosAFavor;
    }

    function setTipoOtroPago($TipoOtroPago) {
        $this->TipoOtroPago = $TipoOtroPago;
    }

    function setClave($Clave) {
        $this->Clave = $Clave;
    }

    function setConcepto($Concepto) {
        $this->Concepto = $Concepto;
    }

    function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function hasSubsidioAlEmpleo() {
        return $this->SubsidioAlEmpleo!==null
                && $this->SubsidioAlEmpleo->isValid();
    }
    
    public function hasCompensacionSaldosAFavor() {
        return $this->CompensacionSaldosAFavor!==null
                && $this->CompensacionSaldosAFavor->isValid();
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof OtroPago\SubsidioAlEmpleo)
                                && !($val instanceof OtroPago\CompensacionSaldosAFavor);
        });
    }

    public function asXML($root) {
        $OtroPago = $root->ownerDocument->createElement("nomina12:OtroPago");
        $ov = $this->getVarArray();
        foreach ($ov as $key=>$value) {
            $OtroPago->setAttribute($key, $value);
        }
        if (!empty($this->SubsidioAlEmpleo)) {
            $OtroPago->appendChild($this->SubsidioAlEmpleo->asXML($root));
        }
        if (!empty($this->CompensacionSaldosAFavor)) {
            $OtroPago->appendChild($this->CompensacionSaldosAFavor->asXML($root));
        }
        return $OtroPago;
    }

    public static function parse($DOMElement) {
        $OtroPago = new OtroPago();
        Reflection::setValues($OtroPago, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, ":SubsidioAlEmpleo")!==false) {
                $OtroPago->setSubsidioAlEmpleo(OtroPago\SubsidioAlEmpleo::parse($node));
            } else if (strpos($node->nodeName, ":CompensacionSaldosAFavor")!==false) {
                $OtroPago->setCompensacionSaldosAFavor(OtroPago\CompensacionSaldosAFavor::parse($node));
            }
        }
        return $OtroPago;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\OtrosPagos\OtroPago;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class CompensacionSaldosAFavor implements CFDIElement {

    private $SaldoAFavor;
    private $Año;
    private $RemanenteSalFav;

    function getSaldoAFavor() {
        return $this->SaldoAFavor;
    }

    function getAño() {
        return $this->Año;
    }

    function getRemanenteSalFav() {
        return $this->RemanenteSalFav;
    }

    function setSaldoAFavor($SaldoAFavor) {
        $this->SaldoAFavor = $SaldoAFavor;
    }

    function setAño($Año) {
        $this->Año = $Año;
    }

    function setRemanenteSalFav($RemanenteSalFav) {
        $this->RemanenteSalFav = $RemanenteSalFav;
    }

    public function asXML($root) {
        $CompensacionSaldosAFavor = $root->ownerDocument->createElement("nomina12:CompensacionesSaldosAFavor");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $CompensacionSaldosAFavor->setAttreibute($key, $value);
        }
        return $CompensacionSaldosAFavor;
    }
    
    public static function parse($DOMElement) {
        $CompensacionSaldosAFavor = new CompensacionSaldosAFavor();
        Reflection::setAttributes($CompensacionSaldosAFavor, $DOMElement);
        return $CompensacionSaldosAFavor;
    }
}

class SubsidioAlEmpleo implements CFDIElement {

    private $SubsidioCausado;

    function getSubsidioCausado() {
        return $this->SubsidioCausado;
    }

    function setSubsidioCausado($SubsidioCausado) {
        $this->SubsidioCausado = $SubsidioCausado;
    }

    public function asXML($root) {
        $SubsidioAlEmpleo = $root->ownerDocument->createElement("nomina12:SubsidiosAlEmpleo");
        $SubsidioAlEmpleo->setAttribute("SubsidioCausado", $this->SubsidioCausado);
        return $SubsidioAlEmpleo;
    }
    
    public static function parse($DOMElement) {
        $SubsidioAlEmpleo = new SubsidioAlEmpleo();
        $SubsidioAlEmpleo->setSubsidioCausado($DOMElement->getAttribute("SubsidioCausado"));
        return $SubsidioAlEmpleo;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\Percepciones;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class JubilacionPensionRetiro implements CFDIElement {

    private $TotalUnaExhibicion;
    private $TotalParcialidad;
    private $MontoDiario;
    private $IngresoAcumulable;
    private $IngresoNoAcumulable;
    
    function getTotalUnaExhibicion() {
        return $this->TotalUnaExhibicion;
    }

    function getTotalParcialidad() {
        return $this->TotalParcialidad;
    }

    function getMontoDiario() {
        return $this->MontoDiario;
    }

    function getIngresoAcumulable() {
        return $this->IngresoAcumulable;
    }

    function getIngresoNoAcumulable() {
        return $this->IngresoNoAcumulable;
    }

    function setTotalUnaExhibicion($TotalUnaExhibicion) {
        $this->TotalUnaExhibicion = $TotalUnaExhibicion;
    }

    function setTotalParcialidad($RotalParcialidad) {
        $this->TotalParcialidad = $RotalParcialidad;
    }

    function setMontoDiario($MontoDiario) {
        $this->MontoDiario = $MontoDiario;
    }

    function setIngresoAcumulable($IngresoAcumulable) {
        $this->IngresoAcumulable = $IngresoAcumulable;
    }

    function setIngresoNoAcumulable($IngresoNoAcumulable) {
        $this->IngresoNoAcumulable = $IngresoNoAcumulable;
    }

    public function asXML($root) {
        $JubilacionPensionRetiro = $root->ownerDocument->createElement("nomina12:JubilacionPensionRetiro");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $JubilacionPensionRetiro->setAttribute($key, $value);
        }
        return $JubilacionPensionRetiro;
    }
    
    public static function parse($DOMElement) {
        $JubilacionPensionRetiro = new JubilacionPensionRetiro();
        Reflection::setAttributes($JubilacionPensionRetiro, $DOMElement);
        return $JubilacionPensionRetiro;
    }
}

class Percepcion implements CFDIElement {

    /** @var Percepcion\AccionesOTitulos */
    private $AccionesOTitulos;
    /** @var Percepcion\HorasExtra[] */
    private $HorasExtra = array();
    private $TipoPercepcion;
    private $Clave;
    private $Concepto;
    private $ImporteGravado;
    private $ImporteExento;

    function getAccionesOTitulos() {
        return $this->AccionesOTitulos;
    }

    function getHorasExtra() {
        return $this->HorasExtra;
    }

    function getTipoPercepcion() {
        return $this->TipoPercepcion;
    }

    function getClave() {
        return $this->Clave;
    }

    function getConcepto() {
        return $this->Concepto;
    }

    function getImporteGravado() {
        return $this->ImporteGravado;
    }

    function getImporteExento() {
        return $this->ImporteExento;
    }

    function setAccionesOTitulos(Percepcion\AccionesOTitulos $AccionesOTitulos) {
        $this->AccionesOTitulos = $AccionesOTitulos;
    }

    public function setHorasExtra(array $HorasExtra) {
        $this->HorasExtra = $HorasExtra;
    }

    function addHorasExtra(Percepcion\HorasExtra $HorasExtra) {
        $this->HorasExtra[] = $HorasExtra;
    }

    function setTipoPercepcion($TipoPercepcion) {
        $this->TipoPercepcion = $TipoPercepcion;
    }

    function setClave($Clave) {
        $this->Clave = $Clave;
    }

    function setConcepto($Concepto) {
        $this->Concepto = $Concepto;
    }

    function setImporteGravado($ImporteGravado) {
        $this->ImporteGravado = $ImporteGravado;
    }

    function setImporteExento($ImporteExento) {
        $this->ImporteExento = $ImporteExento;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                && !($val instanceof Percepcion\HorasExtra);
        });
    }

    public function asXML($root) {
        $Percepcion = $root->ownerDocument->createElement("nomina12:Percepcion");
        $ov = $this->getVarArray();
        foreach ($ov as $key=>$value) {
            $Percepcion->setAttribute($key, $value);
        }
        if (!empty($this->HorasExtra)) {
            foreach ($this->HorasExtra as $horasExtraItem) {
                $Percepcion->appendChild($horasExtraItem->asXML($document));
            }
        }
        return $Percepcion;
    }

    protected function parseDOM($DOMElement) {
        $Percepcion = new Percepcion();
        Reflection::setValues($Percepcion, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $DOMNode = $DOMElement->childNodes->item($i);
            if (strpos($DOMNode->nodeName, ":HorasExtra")!==false) {
                $Percepcion->addHorasExtra(Percepcion\HorasExtra::parse($DOMNode));
            }
        }
        return $Percepcion;
    }
}

class SeparacionIndemnizacion implements CFDIElement {

    private $TotalPagado;
    private $NumAñosServicio;
    private $UltimoSueldoMensOrd;
    private $IngresoAcumulable;
    private $IngresoNoAcumulable;

    function getTotalPagado() {
        return $this->TotalPagado;
    }

    function getNumAñosServicio() {
        return $this->NumAñosServicio;
    }

    function getUltimoSueldoMensOrd() {
        return $this->UltimoSueldoMensOrd;
    }

    function getIngresoAcumulable() {
        return $this->IngresoAcumulable;
    }

    function getIngresoNoAcumulable() {
        return $this->IngresoNoAcumulable;
    }

    function setTotalPagado($TotalPagado) {
        $this->TotalPagado = $TotalPagado;
    }

    function setNumAñosServicio($NumAñosServicio) {
        $this->NumAñosServicio = $NumAñosServicio;
    }

    function setUltimoSueldoMensOrd($UltimoSueldoMensOrd) {
        $this->UltimoSueldoMensOrd = $UltimoSueldoMensOrd;
    }

    function setIngresoAcumulable($IngresoAcumulable) {
        $this->IngresoAcumulable = $IngresoAcumulable;
    }

    function setIngresoNoAcumulable($IngresoNoAcumulable) {
        $this->IngresoNoAcumulable = $IngresoNoAcumulable;
    }

    public function asXML($root) {
        $SeparacionIndeminizacion = $root->ownerDocument->createElement("nomina12:SeparacionIndemnizacion");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $SeparacionIndeminizacion->setAttribute($key, $value);
        }
        return $SeparacionIndeminizacion;
    }
    
    public static function parse($DOMElement) {
        $SeparacionIndemnizacion = new SeparacionIndemnizacion();
        Reflection::setAttributes($SeparacionIndemnizacion, $DOMElement);
        return $SeparacionIndemnizacion;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\Receptor;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class SubContratacion implements CFDIElement {

    private $RfcLabora;
    private $PorcentajeTiempo;
    
    function getRfcLabora() {
        return $this->RfcLabora;
    }

    function getPorcentajeTiempo() {
        return $this->PorcentajeTiempo;
    }

    function setRfcLabora($RfcLabora) {
        $this->RfcLabora = $RfcLabora;
    }

    function setPorcentajeTiempo($PorcentajeTiempo) {
        $this->PorcentajeTiempo = $PorcentajeTiempo;
    }

    public function asXML($root) {
        $SubContratacion = $root->ownerDocument->createElement("nomina12:SubContratacion");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $SubContratacion->setAttribute($key, $value);
        }
        return $SubContratacion;
    }

    public static function parse($DOMElement) {
        $SubContratacion = new SubContratacion();
        Reflection::setAttributes($SubContratacion, $DOMElement);
        return $SubContratacion;
    }
}

namespace com\softcoatl\cfdi\complemento\nomina\Nomina\Percepciones\Percepcion;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class HorasExtra implements CFDIElement {
    
    private $Dias;
    private $TipoHoras;
    private $HorasExtra;
    private $ImportePagado;

    function getDias() {
        return $this->Dias;
    }

    function getTipoHoras() {
        return $this->TipoHoras;
    }

    function getHorasExtra() {
        return $this->HorasExtra;
    }

    function getImportePagado() {
        return $this->ImportePagado;
    }

    function setDias($Dias) {
        $this->Dias = $Dias;
    }

    function setTipoHoras($TipoHoras) {
        $this->TipoHoras = $TipoHoras;
    }

    function setHorasExtra($HorasExtra) {
        $this->HorasExtra = $HorasExtra;
    }

    function setImportePagado($ImportePagado) {
        $this->ImportePagado = $ImportePagado;
    }

    public function asXML($root) {
        $HorasExtra = $root->ownerDocument->createElement("nomina12:HorasExtra");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $HorasExtra->setAttribute($key, $value);
        }
        return $HorasExtra;
    }
    
    public static function parse($DOMElement) {
        $HorasExtra = new HorasExtra();
        Reflection::setAttributes($HorasExtra, $DOMElement);
        return $HorasExtra;
    }
}

class AccionesOTitulos implements CFDIElement {

    private $ValorMercado;
    private $PrecioAlOtorgarse;

    function getValorMercado() {
        return $this->ValorMercado;
    }

    function getPrecioAlOtorgarse() {
        return $this->PrecioAlOtorgarse;
    }

    function setValorMercado($ValorMercado) {
        $this->ValorMercado = $ValorMercado;
    }

    function setPrecioAlOtorgarse($PrecioAlOtorgarse) {
        $this->PrecioAlOtorgarse = $PrecioAlOtorgarse;
    }
    
    public function asXML($root) {
        $AccionesOTitulos = $root->ownerDocument->createElement("nomina12:AccionesOTitulos");
        $ov = array_filter(get_object_vars($this));
        foreach ($ov as $key=>$value) {
            $AccionesOTitulos->setAttribute($key, $value);
        }
        return $AccionesOTitulos;
    }
    
    public static function parse($DOMElement) {
        $AccionesOTitulos = new AccionesOTitulos();
        Reflection::setAttributes($AccionesOTitulos, $DOMElement);
        return $AccionesOTitulos;
    }
}
