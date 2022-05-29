<?php

/*
 * Pagos
 * common®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

include ('CFDIElement.php');

namespace cfdi33\complemento {

    class Pagos implements \cfdi33\CFDIElement {

        private $Version = '1.0';
        private $Pagos = array();

        function getVersion() {
            return $this->Version;
        }

        function getPagos() {
            return $this->Pagos;
        }

        function setVersion($version) {
            $this->Version = $version;
        }

        function addPagos($pago) {
            array_push($this->Pagos, $pago);
        }

        public function asJsonArray() {
            $ov = array_filter(get_object_vars($this), 
                            function ($val) { 
                                return !is_array($val) && !empty($val);                    
                            });
            $pagos = array();

            /* @var $pago DoctoRelacionado */
            foreach ($this->Pagos as $pago) {
                $pagos[] = $pago->asJsonArray();
            }

            $ov["Pago"] = $pagos;

            return $ov;
        }//Pagos::asArray
    }//cfdi33\complemento\Pago
}//namespace

namespace cfdi33\complemento\Pagos {
    class Pago implements \cfdi33\CFDIElement {

        private $DoctoRelacionado = array();
        private $Impuestos= array();

        private $FechaPago;
        private $FormaDePagoP;
        private $MonedaP;
        private $TipoCambioP;
        private $Monto;
        private $NumOperacion;
        private $RfcEmisorCtaOrd;
        private $NomBancoOrdExt;
        private $CtaOrdenante;
        private $RfcEmisorCtaBen;
        private $CtaBeneficiario;
        private $TipoCadPago;
        private $CertPago;
        private $CadPago;
        private $SelloPago;

        function getDoctoRelacionado() {
            return $this->DoctoRelacionado;
        }

        function getImpuestos() {
            return $this->Impuestos;
        }

        function getFechaPago() {
            return $this->FechaPago;
        }

        function getFormaDePagoP() {
            return $this->FormaDePagoP;
        }

        function getMonedaP() {
            return $this->MonedaP;
        }

        function getTipoCambioP() {
            return $this->TipoCambioP;
        }

        function getMonto() {
            return $this->Monto;
        }

        function getNumOperacion() {
            return $this->NumOperacion;
        }

        function getRfcEmisorCtaOrd() {
            return $this->RfcEmisorCtaOrd;
        }

        function getNomBancoOrdExt() {
            return $this->NomBancoOrdExt;
        }

        function getCtaOrdenante() {
            return $this->CtaOrdenante;
        }

        function getRfcEmisorCtaBen() {
            return $this->RfcEmisorCtaBen;
        }

        function getCtaBeneficiario() {
            return $this->CtaBeneficiario;
        }

        function getTipoCadPago() {
            return $this->TipoCadPago;
        }

        function getCertPago() {
            return $this->CertPago;
        }

        function getCadPago() {
            return $this->CadPago;
        }

        function getSelloPago() {
            return $this->SelloPago;
        }

        function addDoctoRelacionado($DoctoRelacionado) {
            array_push($this->DoctoRelacionado, $DoctoRelacionado);
        }

        function addImpuestos($Impuestos) {
            array_push($this->Impuestos, $Impuestos);
        }

        function setFechaPago($FechaPago) {
            $this->FechaPago = $FechaPago;
        }

        function setFormaDePagoP($FormaDePagoP) {
            $this->FormaDePagoP = $FormaDePagoP;
        }

        function setMonedaP($MonedaP) {
            $this->MonedaP = $MonedaP;
        }

        function setTipoCambioP($TipoCambioP) {
            $this->TipoCambioP = $TipoCambioP;
        }

        function setMonto($Monto) {
            $this->Monto = $Monto;
        }

        function setNumOperacion($NumOperacion) {
            $this->NumOperacion = $NumOperacion;
        }

        function setRfcEmisorCtaOrd($RfcEmisorCtaOrd) {
            $this->RfcEmisorCtaOrd = $RfcEmisorCtaOrd;
        }

        function setNomBancoOrdExt($NomBancoOrdExt) {
            $this->NomBancoOrdExt = $NomBancoOrdExt;
        }

        function setCtaOrdenante($CtaOrdenante) {
            $this->CtaOrdenante = $CtaOrdenante;
        }

        function setRfcEmisorCtaBen($RfcEmisorCtaBen) {
            $this->RfcEmisorCtaBen = $RfcEmisorCtaBen;
        }

        function setCtaBeneficiario($CtaBeneficiario) {
            $this->CtaBeneficiario = $CtaBeneficiario;
        }

        function setTipoCadPago($TipoCadPago) {
            $this->TipoCadPago = $TipoCadPago;
        }

        function setCertPago($CertPago) {
            $this->CertPago = $CertPago;
        }

        function setCadPago($CadPago) {
            $this->CadPago = $CadPago;
        }

        function setSelloPago($SelloPago) {
            $this->SelloPago = $SelloPago;
        }

        public function asJsonArray() {
            $ov = array_filter(get_object_vars($this), 
                            function ($val) { 
                                return !is_array($val) && !empty($val);                    
                            });
            $doctoRelacionado = array();

            /* @var $docto DoctoRelacionado */
            foreach ($this->DoctoRelacionado as $docto) {
                $doctoRelacionado[] = $docto->asJsonArray();
            }

            $ov["DoctoRelacionado"] = $doctoRelacionado;

            return $ov;
        }//Pago::asArray
    }//Pago
}//cfdi33\complemento\Pagos

namespace cfdi33\complemento\Pagos\Pago {
    class DoctoRelacionado implements \cfdi33\CFDIElement {

        private $IdDocumento;
        private $Serie;
        private $Folio;
        private $MonedaDR;
        private $TipoCambioDR;
        private $MetodoDePagoDR;
        private $NumParcialidad;
        private $ImpSaldoAnt;
        private $ImpPagado;
        private $ImpSaldoInsoluto;

        function getIdDocumento() {
            return $this->IdDocumento;
        }

        function getSerie() {
            return $this->Serie;
        }

        function getFolio() {
            return $this->Folio;
        }

        function getMonedaDR() {
            return $this->MonedaDR;
        }

        function getTipoCambioDR() {
            return $this->TipoCambioDR;
        }

        function getMetodoDePagoDR() {
            return $this->MetodoDePagoDR;
        }

        function getNumParcialidad() {
            return $this->NumParcialidad;
        }

        function getImpSaldoAnt() {
            return $this->ImpSaldoAnt;
        }

        function getImpPagado() {
            return $this->ImpPagado;
        }

        function getImpSaldoInsoluto() {
            return $this->ImpSaldoInsoluto;
        }

        function setIdDocumento($IdDocumento) {
            $this->IdDocumento = $IdDocumento;
        }

        function setSerie($Serie) {
            $this->Serie = $Serie;
        }

        function setFolio($Folio) {
            $this->Folio = $Folio;
        }

        function setMonedaDR($MonedaDR) {
            $this->MonedaDR = $MonedaDR;
        }

        function setTipoCambioDR($TipoCambioDR) {
            $this->TipoCambioDR = $TipoCambioDR;
        }

        function setMetodoDePagoDR($MetodoDePagoDR) {
            $this->MetodoDePagoDR = $MetodoDePagoDR;
        }

        function setNumParcialidad($NumParcialidad) {
            $this->NumParcialidad = $NumParcialidad;
        }

        function setImpSaldoAnt($ImpSaldoAnt) {
            $this->ImpSaldoAnt = $ImpSaldoAnt;
        }

        function setImpPagado($ImpPagado) {
            $this->ImpPagado = $ImpPagado;
        }

        function setImpSaldoInsoluto($ImpSaldoInsoluto) {
            $this->ImpSaldoInsoluto = $ImpSaldoInsoluto;
        }

        public function asJsonArray() {
            return array_filter(get_object_vars($this));
        }//DoctoRelacionado::asArray

    }//DoctoRelacionado
}//cfdi33\complemento\Pagos\Pago