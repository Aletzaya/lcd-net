<?php

namespace com\softcoatl\cfdi\complemento\pagos;

interface Pagos {

    public function getVersion();
    public function getPago(): array;
    public function getTotales(): ?Pagos\Totales;
    public function setVersion($version);
    public function setPago(array $Pago);
    public function setTotales(?Pagos\Totales $totales);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos;

interface Totales {
    
    public function getTotalRetencionesIVA();
    public function getTotalRetencionesISR();
    public function getTotalRetencionesIEPS();
    public function getTotalTrasladosBaseIVA16();
    public function getTotalTrasladosImpuestoIVA16();
    public function getTotalTrasladosBaseIVA8();
    public function getTotalTrasladosImpuestoIVA8();
    public function getTotalTrasladosBaseIVA0();
    public function getTotalTrasladosImpuestoIVA0();
    public function getTotalTrasladosBaseIVAExento();
    public function getMontoTotalPagos();
        
    public function setTotalRetencionesIVA($TotalRetencionesIVA);
    public function setTotalRetencionesISR($TotalRetencionesISR);
    public function setTotalRetencionesIEPS($TotalRetencionesIEPS);
    public function setTotalTrasladosBaseIVA16($TotalTrasladosBaseIVA16);
    public function setTotalTrasladosImpuestoIVA16($TotalTrasladosImpuestoIVA16);
    public function setTotalTrasladosBaseIVA8($TotalTrasladosBaseIVA8);
    public function setTotalTrasladosImpuestoIVA8($TotalTrasladosImpuestoIVA8);
    public function setTotalTrasladosBaseIVA0($TotalTrasladosBaseIVA0);
    public function setTotalTrasladosImpuestoIVA0($TotalTrasladosImpuestoIVA0);
    public function setTotalTrasladosBaseIVAExento($TotalTrasladosBaseIVAExento);
    public function setMontoTotalPagos($MontoTotalPagos);
}

interface Pago {
    
    public function getDoctoRelacionado(): array;
    public function getImpuestosP(): Pago\ImpuestosP;

    public function setDoctoRelacionado(array $DoctoRelacionado);
    public function setImpuestosP(Pago\ImpuestosP $ImpuestosP);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago;

interface DoctoRelacionado {
    
    public function getImpuestosDR(): ?DoctoRelacionado\ImpuestosDR;
    public function setImpuestosDR(?DoctoRelacionado\ImpuestosDR $ImpuestosDR);
}

interface ImpuestosP {
    
    public function getRetencionesP();
    public function getTrasladosP();

    public function setRetencionesP(ImpuestosP\RetencionesP $RetencionesP);
    public function setTrasladosP(ImpuestosP\TrasladosP $TrasladosP);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago\ImpuestosP;

interface RetencionesP {

    public function getRetencionP(): array;
    public function setRetencionP(array $RetencionP);
}

interface TrasladosP {

    public function getTrasladoP(): array;
    public function setTrasladoP(array $TrasladoP);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago\ImpuestosP\RetencionesP;

interface RetencionP {

    public function getImpuestoP();
    public function getImporteP();

    public function setImpuestoP($ImpuestoP);
    public function setImporteP($ImporteP);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago\ImpuestosP\TrasladosP;

interface TrasladoP {

    public function getImpuestoP();
    public function getTipoFactorP();
    public function getTasaOCuotaP();
    public function getImporteP();

    public function setImpuestoP($ImpuestoP);
    public function setTipoFactorP($TipoFactorP);
    public function setTasaOCuotaP($TasaOCuotaP);
    public function setImporteP($ImporteP);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago\DoctoRelacionado;

interface ImpuestosDR {

    function getRetencionesDR(): ?ImpuestosDR\RetencionesDR;
    function getTrasladosDR(): ?ImpuestosDR\TrasladosDR;
    function setRetencionesDR(?ImpuestosDR\RetencionesDR $RetencionesDR);
    function setTrasladosDR(?ImpuestosDR\TrasladosDR $TrasladosDR);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago\DoctoRelacionado\ImpuestosDR;

interface RetencionesDR {

    public function getRetencionDR(): array;
    public function setRetencionDR(array $RetencionDR);
}

interface TrasladosDR {

    public function getTrasladoDR(): array;
    public function setTrasladoDR(array $TrasladoDR);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago\DoctoRelacionado\ImpuestosDR\RetencionesDR;

interface RetencionDR {

   public function getBaseDR();
   public function getImpuestoDR();
   public function getTipoFactorDR();
   public function getTasaOCuotaDR();
   public function getImporteDR();

   public function setBaseDR($BaseDR);
   public function setImpuestoDR($ImpuestoDR);
   public function setTipoFactorDR($TipoFactorDR);
   public function setTasaOCuotaDR($TasaOCuotaDR);
   public function setImporteDR($ImporteDR);
}

namespace com\softcoatl\cfdi\complemento\pagos\Pagos\Pago\DoctoRelacionado\ImpuestosDR\TrasladosDR;

interface TrasladoDR {

    public function getBaseDR();
    public function getImpuestoDR();
    public function getTipoFactorDR();
    public function getTasaOCuotaDR();
    public function getImporteDR();

    public function setBaseDR($BaseDR);
    public function setImpuestoDR($ImpuestoDR);
    public function setTipoFactorDR($TipoFactorDR);
    public function setTasaOCuotaDR($TasaOCuotaDR);
    public function setImporteDR($ImporteDR);
}