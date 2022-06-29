<?php

namespace com\detisa\cfdi\factory;

require_once ("com/softcoatl/cfdi/complemento/pagos/Pagos20.php");
require_once ("PagosFactoryIface.php");

use com\softcoatl\cfdi\complemento\pagos\Pagos;
use com\softcoatl\cfdi\complemento\pagos\Pagos20;

class Pagos20Factory implements PagosFactoryIface {

    public function createPagos(): Pagos {
        return new \com\softcoatl\cfdi\complemento\pagos\Pagos20();
    }

    public function createPagosTotales(array $rs): ?Pagos\Totales {
        $totales = new Pagos20\Totales();
        $totales->setMontoTotalPagos($rs["MontoTotalPagos"]);
        return $totales;
    }

    public function createPagosPago(array $rs): Pagos\Pago {
        $pago = new Pagos20\Pago();
        $pago->setMonto(number_format($rs["Monto"], 2, ".", ""));
        $pago->setMonedaP($rs["MonedaP"]);
        $pago->setTipoCambioP($pago->getMonedaP()!=="MXN" ? $rs["TipoCambioP"] : "1");
        $pago->setFormaDePagoP($rs["FormaDePagoP"]);
        $pago->setFechaPago($rs["FechaPago"]);
        $pago->setNumOperacion($rs["NumOperacion"]);
        $pago->setCtaBeneficiario($rs["CtaBeneficiario"]);
        $pago->setCtaOrdenante($rs["CtaBeneficiario"]);
        $pago->setRfcEmisorCtaBen($rs["RfcEmisorCtaBen"]);
        $pago->setRfcEmisorCtaOrd($rs["RfcEmisorCtaOrd"]);
        return $pago;
    }

    public function createPagosPagoDoctoRelacionado(array $rs): Pagos\Pago\DoctoRelacionado {
        $doctoRelacionado = new Pagos20\Pago\DoctoRelacionado();
        $doctoRelacionado->setFolio($rs['Folio']);
        $doctoRelacionado->setIdDocumento($rs['IdDocumento']);
        $doctoRelacionado->setImpPagado(number_format($rs['ImpPagado'], 2, '.', ''));
        $doctoRelacionado->setImpSaldoAnt(number_format($rs['ImpSaldoAnt'], 2, '.', ''));
        $doctoRelacionado->setImpSaldoInsoluto(number_format($rs['ImpSaldoInsoluto'], 2, '.', ''));
        $doctoRelacionado->setMonedaDR($rs['MonedaDR']);
        if ($rs['MonedaP']!==$rs['MonedaDR']) {
            $doctoRelacionado->setEquivalenciaDR($rs['EquivalenciaDR']);
        }
        $doctoRelacionado->setMetodoDePagoDR("PPD");
        $doctoRelacionado->setNumParcialidad($rs['NumParcialidad']);
        return $doctoRelacionado;
    }

    public function createPagosPagoImpuestosP(): Pagos\Pago\ImpuestosP {
        
    }

    public function createPagosPagoImpuestosPRetencionesP(): Pagos\Pago\ImpuestosP\RetencionesP {
        
    }

    public function createPagosPagoImpuestosPTrasladosP(): Pagos\Pago\ImpuestosP\TrasladosP {
        
    }

    public function createPagosPagoImpuestosPRetencionesPRetencionP(): Pagos\Pago\ImpuestosP\RetencionesP\RetencionP {
        
    }

    public function createPagosPagoImpuestosPTrasladosPPTrasladoP(): Pagos\Pago\ImpuestosP\TrasladosP\TrasladoP {
        
    }

    public function createPagosPagoDoctoRelacionadoImpuestosDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR {
        
    }

    public function createPagosPagoDoctoRelacionadoImpuestosDRRetencionesDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\RetencionesDR {
        
    }

    public function createPagosPagoDoctoRelacionadoImpuestosDRTrasladosDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\TrasladosDR {
        
    }

    public function createPagosPagoDoctoRelacionadoImpuestosDRRetencionesDRRetencionDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\RetencionesDR\RetencionDR {
        
    }

    public function createPagosPagoDoctoRelacionadoImpuestosDRTrasladosDRTrasladoDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\TrasladosDR {
        
    }
}
