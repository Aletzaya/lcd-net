<?php

namespace com\detisa\cfdi\factory;

use com\softcoatl\cfdi\complemento\pagos\Pagos;

interface PagosFactoryIface {

    public function createPagos(): Pagos;
    public function createPagosTotales(array $rs): ?Pagos\Totales;
    public function createPagosPago(array $rs): Pagos\Pago;
    public function createPagosPagoDoctoRelacionado(array $rs): Pagos\Pago\DoctoRelacionado;
    public function createPagosPagoImpuestosP(): Pagos\Pago\ImpuestosP;
    public function createPagosPagoImpuestosPRetencionesP(): Pagos\Pago\ImpuestosP\RetencionesP;
    public function createPagosPagoImpuestosPTrasladosP(): Pagos\Pago\ImpuestosP\TrasladosP;
    public function createPagosPagoImpuestosPRetencionesPRetencionP(): Pagos\Pago\ImpuestosP\RetencionesP\RetencionP;
    public function createPagosPagoImpuestosPTrasladosPPTrasladoP(): Pagos\Pago\ImpuestosP\TrasladosP\TrasladoP;
    public function createPagosPagoDoctoRelacionadoImpuestosDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR;
    public function createPagosPagoDoctoRelacionadoImpuestosDRRetencionesDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\RetencionesDR;
    public function createPagosPagoDoctoRelacionadoImpuestosDRTrasladosDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\TrasladosDR;
    public function createPagosPagoDoctoRelacionadoImpuestosDRRetencionesDRRetencionDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\RetencionesDR\RetencionDR;
    public function createPagosPagoDoctoRelacionadoImpuestosDRTrasladosDRTrasladoDR(): Pagos\Pago\DoctoRelacionado\ImpuestosDR\TrasladosDR;
}
