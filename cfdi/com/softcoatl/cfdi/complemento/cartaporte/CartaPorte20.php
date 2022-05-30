<?php
/*
 * CartaPorte20
 * CFDI®
 * © 2021, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 2.0
 * @since nov 2021
 */
namespace com\softcoatl\cfdi\complemento\cartaporte;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class CartaPorte20 implements CFDIElement {

    /** @var CartaPorte20\Ubicaciones */
    private $Ubicaciones;
    /** @var CartaPorte20\Mercancias */
    private $Mercancias;
    /** @var CartaPorte20\FiguraTransporte */
    private $FiguraTransporte;
    private $Version = "2.0";
    private $TranspInternac = "No";
    private $EntradaSalidaMerc;
    private $PaisOrigenDestino;
    private $ViaEntradaSalida;
    private $TotalDistRec;

    public function getUbicaciones() {
        return $this->Ubicaciones;
    }

    public function getMercancias() {
        return $this->Mercancias;
    }

    public function getFiguraTransporte() {
        return $this->FiguraTransporte;
    }

    public function getVersion() {
        return $this->Version;
    }

    public function getTranspInternac() {
        return $this->TranspInternac;
    }

    public function getEntradaSalidaMerc() {
        return $this->EntradaSalidaMerc;
    }

    public function getPaisOrigenDestino() {
        return $this->PaisOrigenDestino;
    }

    public function getViaEntradaSalida() {
        return $this->ViaEntradaSalida;
    }

    public function getTotalDistRec() {
        return $this->TotalDistRec;
    }

    public function setUbicaciones(CartaPorte20\Ubicaciones $Ubicaciones) {
        $this->Ubicaciones = $Ubicaciones;
    }

    public function setMercancias(CartaPorte20\Mercancias $Mercancias) {
        $this->Mercancias = $Mercancias;
    }

    public function setFiguraTransporte(CartaPorte20\FiguraTransporte $FiguraTransporte) {
        $this->FiguraTransporte = $FiguraTransporte;
    }

    public function setVersion($version) {
        $this->Version = $version;
    }

    public function setTranspInternac($TranspInternac) {
        $this->TranspInternac = $TranspInternac;
    }

    public function setEntradaSalidaMerc($EntradaSalidaMerc) {
        $this->EntradaSalidaMerc = $EntradaSalidaMerc;
    }

    public function setPaisOrigenDestino($PaisOrigenDestino) {
        $this->PaisOrigenDestino = $PaisOrigenDestino;
    }

    public function setViaEntradaSalida($ViaEntradaSalida) {
        $this->ViaEntradaSalida = $ViaEntradaSalida;
    }

    public function setTotalDistRec($TotalDistRec) {
        $this->TotalDistRec = $TotalDistRec;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val))
                                && !($val instanceof CartaPorte20\Ubicaciones)
                                && !($val instanceof CartaPorte20\Mercancias)
                                && !($val instanceof CartaPorte20\FiguraTransporte);
        });
    }

    public function asXML($root) {

        if ($root->ownerDocument->documentElement
                && empty($root->ownerDocument->documentElement->attributes->getNamedItem("xmlns:cartaporte20"))) {
            $root->ownerDocument->documentElement->setAttribute("xmlns:cartaporte20", "http://www.sat.gob.mx/CartaPorte20");
            $root->ownerDocument->documentElement->setAttribute("xsi:schemaLocation", 
                            $root->ownerDocument->documentElement->getAttribute("xsi:schemaLocation") 
                        .   " http://www.sat.gob.mx/CartaPorte20 http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte20.xsd");
        }
        $CartaPorte = $root->ownerDocument->createElement("cartaporte20:CartaPorte");

        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $CartaPorte->setAttribute($attr, $value);
        }

        if (!empty($this->Ubicaciones)) {
            $CartaPorte->appendChild($this->Ubicaciones->asXML($root));
        }
        if (!empty($this->Mercancias)) {
            $CartaPorte->appendChild($this->Mercancias->asXML($root));
        }
        if (!empty($this->FiguraTransporte)) {
            $CartaPorte->appendChild($this->FiguraTransporte->asXML($root));
        }
        return $CartaPorte;
    }

    public static function parse($DOMElement) {

        if (strpos($DOMElement->nodeName, "cartaporte20:CartaPorte")!==false) {
            $CartaPorte = new CartaPorte20();
            Reflection::setAttributes($CartaPorte, $DOMElement);
            for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
                $node = $DOMElement->childNodes->item($i);
                if (strpos($node->nodeName, "cartaporte20:Ubicaciones")!==false) {
                    $CartaPorte->setUbicaciones(CartaPorte20\Ubicaciones::parse($node));
                } else 
                if (strpos($node->nodeName, "cartaporte20:Mercancias")!==false) {
                    $CartaPorte->setMercancias(CartaPorte20\Mercancias::parse($node));
                } else 
                if (strpos($node->nodeName, "cartaporte20:FiguraTransporte")!==false) {
                    $CartaPorte->setFiguraTransporte(CartaPorte20\FiguraTransporte::parse($node));
                }
            }
            return $CartaPorte;
        }
        return false;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Ubicaciones implements CFDIElement {

    /** @var Ubicaciones\Ubicacion[] */
    private $Ubicacion = array();

    public function getUbicacion() {
        return $this->Ubicacion;
    }

    public function setUbicacion(array $Ubicacion) {
        $this->Ubicacion = $Ubicacion;
    }

    public function addUbicacion(Ubicaciones\Ubicacion $Ubicacion) {
        $this->Ubicacion[] = $Ubicacion;
    }

    public function asXML($root) {
        $Ubicaciones = $root->ownerDocument->createElement("cartaporte20:Ubicaciones");
        foreach ($this->Ubicacion as $Ubicacion) {
            $Ubicaciones->appendChild($Ubicacion->asXML($root));
        }
        return $Ubicaciones;
    }

    public static function parse($DOMElement = null) {
        $Ubicaciones = new Ubicaciones();
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:Ubicacion")!==false) {
                $Ubicaciones->addUbicacion(Ubicaciones\Ubicacion::parse($node));
            }
        }
        return $Ubicaciones;
    }
}

class Mercancias implements CFDIElement {

    /** @var Mercancias\Mercancia[] */
    private $Mercancia = array();
    /** @var Mercancias\Autotransporte */
    private $Autotransporte;
    /** @var Mercancias\TransporteMaritimo */
    private $TransporteMaritimo;
    /** @var Mercancias\TransporteAereo */
    private $TransporteAereo;
    /** @var Mercancias\TransporteFerroviario */
    private $TransporteFerroviario;
    private $PesoBrutoTotal;
    private $UnidadPeso;
    private $PesoNetoTotal;
    private $NumTotalMercancias;
    private $CargoPorTasacion;

    public function getMercancia() {
        return $this->Mercancia;
    }

    public function getAutotransporte() {
        return $this->Autotransporte;
    }

    public function getTransporteMaritimo() {
        return $this->TransporteMaritimo;
    }

    public function getTransporteAereo() {
        return $this->TransporteAereo;
    }

    public function getTransporteFerroviario() {
        return $this->TransporteFerroviario;
    }

    public function getPesoBrutoTotal() {
        return $this->PesoBrutoTotal;
    }

    public function getUnidadPeso() {
        return $this->UnidadPeso;
    }

    public function getPesoNetoTotal() {
        return $this->PesoNetoTotal;
    }

    public function getNumTotalMercancias() {
        return $this->NumTotalMercancias;
    }

    public function getCargoPorTasacion() {
        return $this->CargoPorTasacion;
    }

    public function setMercancia(array $Mercancia) {
        $this->Mercancia = $Mercancia;
    }

    public function addMercancia(Mercancias\Mercancia $Mercancia) {
        $this->Mercancia[] = $Mercancia;
    }

    public function setAutotransporte(Mercancias\Autotransporte $Autotransporte) {
        $this->Autotransporte = $Autotransporte;
    }

    public function setTransporteMaritimo(Mercancias\TransporteMaritimo $TransporteMaritimo) {
        $this->TransporteMaritimo = $TransporteMaritimo;
    }

    public function setTransporteAereo(Mercancias\TransporteAereo $TransporteAereo) {
        $this->TransporteAereo = $TransporteAereo;
    }

    public function setTransporteFerroviario(Mercancias\TransporteFerroviario $TransporteFerroviario) {
        $this->TransporteFerroviario = $TransporteFerroviario;
    }

    public function setPesoBrutoTotal($PesoBrutoTotal) {
        $this->PesoBrutoTotal = $PesoBrutoTotal;
    }

    public function setUnidadPeso($UnidadPeso) {
        $this->UnidadPeso = $UnidadPeso;
    }

    public function setPesoNetoTotal($PesoNetoTotal) {
        $this->PesoNetoTotal = $PesoNetoTotal;
    }

    public function setNumTotalMercancias($NumTotalMercancias) {
        $this->NumTotalMercancias = $NumTotalMercancias;
    }

    public function setCargoPorTasacion($CargoPorTasacion) {
        $this->CargoPorTasacion = $CargoPorTasacion;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val))
                                && !($val instanceof Mercancias\Autotransporte)
                                && !($val instanceof Mercancias\TransporteMaritimo)
                                && !($val instanceof Mercancias\TransporteAereo)
                                && !($val instanceof Mercancias\TransporteFerroviario);
        });
    }

    public function asXML($root) {
        $Mercancias = $root->ownerDocument->createElement("cartaporte20:Mercancias");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Mercancias->setAttribute($attr, $value);
        }
        foreach ($this->Mercancia as $Mercancia) {
            $Mercancias->appendChild($Mercancia->asXML($root));
        }
        if (!empty($this->Autotransporte)) {
            $Mercancias->appendChild($this->Autotransporte->asXML($root));
        }
        if (!empty($this->TransporteMaritimo)) {
            $Mercancias->appendChild($this->TransporteMaritimo->asXML($root));
        }
        if (!empty($this->TransporteAereo)) {
            $Mercancias->appendChild($this->TransporteAereo->asXML($root));
        }
        if (!empty($this->TransporteFerroviario)) {
            $Mercancias->appendChild($this->TransporteFerroviario->asXML($root));
        }
        
        return $Mercancias;
    }

    public static function parse($DOMElement = null) {
        $Mercancias = new Mercancias();
        Reflection::setAttributes($Mercancias, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:Mercancia")!==false) {
                $Mercancias->addMercancia(Mercancias\Mercancia::parse($node));
            } else 
            if (strpos($node->nodeName, "cartaporte20:Autotransporte")!==false) {
                $Mercancias->setAutotransporte(Mercancias\Autotransporte::parse($node));
            } else 
            if (strpos($node->nodeName, "cartaporte20:TransporteMaritimo")!==false) {
                $Mercancias->setTransporteMaritimo(Mercancias\TransporteMaritimo::parse($node));
            } else 
            if (strpos($node->nodeName, "cartaporte20:TransporteAereo")!==false) {
                $Mercancias->setTransporteAereo(Mercancias\TransporteAereo::parse($node));
            } else 
            if (strpos($node->nodeName, "cartaporte20:TransporteFerroviario")!==false) {
                $Mercancias->setTransporteFerroviario(Mercancias\TransporteFerroviario::parse($node));
            }
        }
        return $Mercancias;
    }
}

class FiguraTransporte implements CFDIElement {

    /** @var FigurasTransporte\TiposFigura[] */
    private $TiposFigura = array();

    public function getTiposFigura() {
        return $this->TiposFigura;
    }

    public function setTiposFigura(array $TiposFigura) {
        $this->TiposFigura = $TiposFigura;
    }

    public function addTiposFigura(FigurasTransporte\TiposFigura $TiposFigura) {
        $this->TiposFigura[] = $TiposFigura;
    }

    public function asXML($root) {
        $FiguraTransporte = $root->ownerDocument->createElement("cartaporte20:FiguraTransporte");
        foreach ($this->TiposFigura as $TiposFigura) {
            $FiguraTransporte->appendChild($TiposFigura->asXML($root));
        }
        return $FiguraTransporte;        
    }

    public static function parse($DOMElement = null) {
        $FiguraTransporte = new FiguraTransporte();
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:TiposFigura")!==false) {
                $FiguraTransporte->addTiposFigura(FigurasTransporte\TiposFigura::parse($node));
            }
        }
        return $FiguraTransporte;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Ubicaciones;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Ubicacion implements CFDIElement{

    /** @var Ubicacion\Domicilio */
    private $Domicilio;

    private $TipoUbicacion;
    private $IdUbicacion;
    private $RFCRemitenteDestinatario;
    private $NombreRemitenteDestinatario;
    private $NumRegIdTrib;
    private $ResidenciaFiscal;
    private $NumEstacion;
    private $NombreEstacion;
    private $NavegacionTrafico;
    private $FechaHoraSalidaLlegada;
    private $TipoEstacion;
    private $DistanciaRecorrida;

    public function getDomicilio() {
        return $this->Domicilio;
    }

    public function getTipoUbicacion() {
        return $this->TipoUbicacion;
    }

    public function getIdUbicacion() {
        return $this->IdUbicacion;
    }

    public function getRFCRemitenteDestinatario() {
        return $this->RFCRemitenteDestinatario;
    }

    public function getNombreRemitenteDestinatario() {
        return $this->NombreRemitenteDestinatario;
    }

    public function getNumRegIdTrib() {
        return $this->NumRegIdTrib;
    }

    public function getResidenciaFiscal() {
        return $this->ResidenciaFiscal;
    }

    public function getNumEstacion() {
        return $this->NumEstacion;
    }

    public function getNombreEstacion() {
        return $this->NombreEstacion;
    }

    public function getNavegacionTrafico() {
        return $this->NavegacionTrafico;
    }

    public function getFechaHoraSalidaLlegada() {
        return $this->FechaHoraSalidaLlegada;
    }

    public function getTipoEstacion() {
        return $this->TipoEstacion;
    }

    public function getDistanciaRecorrida() {
        return $this->DistanciaRecorrida;
    }

    public function setDomicilio(Ubicacion\Domicilio $Domicilio) {
        $this->Domicilio = $Domicilio;
    }

    public function setTipoUbicacion($TipoUbicacion) {
        $this->TipoUbicacion = $TipoUbicacion;
    }

    public function setIdUbicacion($IdUbicacion) {
        $this->IdUbicacion = $IdUbicacion;
    }

    public function setRFCRemitenteDestinatario($RFCRemitenteDestinatario) {
        $this->RFCRemitenteDestinatario = $RFCRemitenteDestinatario;
    }

    public function setNombreRemitenteDestinatario($NombreRemitenteDestinatario) {
        $this->NombreRemitenteDestinatario = $NombreRemitenteDestinatario;
    }

    public function setNumRegIdTrib($NumRegIdTrib) {
        $this->NumRegIdTrib = $NumRegIdTrib;
    }

    public function setResidenciaFiscal($ResidenciaFiscal) {
        $this->ResidenciaFiscal = $ResidenciaFiscal;
    }

    public function setNumEstacion($NumEstacion) {
        $this->NumEstacion = $NumEstacion;
    }

    public function setNombreEstacion($NombreEstacion) {
        $this->NombreEstacion = $NombreEstacion;
    }

    public function setNavegacionTrafico($NavegacionTrafico) {
        $this->NavegacionTrafico = $NavegacionTrafico;
    }

    public function setFechaHoraSalidaLlegada($FechaHoraSalidaLlegada) {
        $this->FechaHoraSalidaLlegada = $FechaHoraSalidaLlegada;
    }

    public function setTipoEstacion($TipoEstacion) {
        $this->TipoEstacion = $TipoEstacion;
    }

    public function setDistanciaRecorrida($DistanciaRecorrida) {
        $this->DistanciaRecorrida = $DistanciaRecorrida;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val))
                                && !($val instanceof Ubicacion\Domicilio);
        });
    }

    public function asXML($root) {
        $Ubicacion = $root->ownerDocument->createElement("cartaporte20:Ubicacion");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Ubicacion->setAttribute($attr, $value);
        }
        if (!empty($this->Domicilio)) {
            $Ubicacion->appendChild($this->Domicilio->asXML($root));
        }
        return $Ubicacion;
    }

    public static function parse($DOMElement = null) {
        $Ubicacion = new Ubicacion();
        Reflection::setAttributes($Ubicacion, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:Domicilio")!==false) {
                $Ubicacion->setDomicilio(Ubicacion\Domicilio::parse($node));
            }
        }
        return $Ubicacion;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Ubicaciones\Ubicacion;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Domicilio implements CFDIElement {

    private $Calle;
    private $NumeroExterior;
    private $NumeroInterior;
    private $Colonia;
    private $Localidad;
    private $Referencia;
    private $Municipio;
    private $Estado;
    private $Pais;
    private $CodigoPostal;

    public function getCalle() {
        return $this->Calle;
    }

    public function getNumeroExterior() {
        return $this->NumeroExterior;
    }

    public function getNumeroInterior() {
        return $this->NumeroInterior;
    }

    public function getColonia() {
        return $this->Colonia;
    }

    public function getLocalidad() {
        return $this->Localidad;
    }

    public function getReferencia() {
        return $this->Referencia;
    }

    public function getMunicipio() {
        return $this->Municipio;
    }

    public function getEstado() {
        return $this->Estado;
    }

    public function getPais() {
        return $this->Pais;
    }

    public function getCodigoPostal() {
        return $this->CodigoPostal;
    }

    public function setCalle($Calle) {
        $this->Calle = $Calle;
    }

    public function setNumeroExterior($NumeroExterior) {
        $this->NumeroExterior = $NumeroExterior;
    }

    public function setNumeroInterior($NumeroInterior) {
        $this->NumeroInterior = $NumeroInterior;
    }

    public function setColonia($Colonia) {
        $this->Colonia = $Colonia;
    }

    public function setLocalidad($Localidad) {
        $this->Localidad = $Localidad;
    }

    public function setReferencia($Referencia) {
        $this->Referencia = $Referencia;
    }

    public function setMunicipio($Municipio) {
        $this->Municipio = $Municipio;
    }

    public function setEstado($Estado) {
        $this->Estado = $Estado;
    }

    public function setPais($Pais) {
        $this->Pais = $Pais;
    }

    public function setCodigoPostal($CodigoPostal) {
        $this->CodigoPostal = $CodigoPostal;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root){
        $Domicilio = $root->ownerDocument->createElement("cartaporte20:Domicilio");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Domicilio->setAttribute($attr, $value);
        }
        return $Domicilio;
    }

    public static function parse($DOMElement = null) {
        $Domicilio = new Domicilio();
        Reflection::setAttributes($Domicilio, $DOMElement);
        return $Domicilio;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Mercancias;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Mercancia implements CFDIElement {

    /** @var Mercancia\Pedimentos[] */
    private $Pedimentos = array();
    /** @var Mercancia\GuiasIdentificacion[] */
    private $GuiasIdentificacion = array();
    /** @var Mercancia\CantidadTransporta[] */
    private $CantidadTransporta;
    /** @var Mercancia\DetalleMercancia */
    private $DetalleMercancia;
    private $BienesTransp;
    private $ClaveSTCC;
    private $Descripcion;
    private $Cantidad;
    private $ClaveUnidad;
    private $Unidad;
    private $Dimensiones;
    private $MaterialPeligroso;
    private $CveMaterialPeligroso;
    private $Embalaje;
    private $DescripEmbalaje;
    private $PesoEnKg;
    private $ValorMercancia;
    private $Moneda;
    private $FraccionArancelaria;
    private $UUIDComercioExt;

    public function getPedimentos() {
        return $this->Pedimentos;
    }

    public function getGuiasIdentificacion() {
        return $this->GuiasIdentificacion;
    }

    public function getCantidadTransporta() {
        return $this->CantidadTransporta;
    }

    public function getDetalleMercancia() {
        return $this->DetalleMercancia;
    }

    public function getBienesTransp() {
        return $this->BienesTransp;
    }

    public function getClaveSTCC() {
        return $this->ClaveSTCC;
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function getCantidad() {
        return $this->Cantidad;
    }

    public function getClaveUnidad() {
        return $this->ClaveUnidad;
    }

    public function getUnidad() {
        return $this->Unidad;
    }

    public function getDimensiones() {
        return $this->Dimensiones;
    }

    public function getMaterialPeligroso() {
        return $this->MaterialPeligroso;
    }

    public function getCveMaterialPeligroso() {
        return $this->CveMaterialPeligroso;
    }

    public function getEmbalaje() {
        return $this->Embalaje;
    }

    public function getDescripEmbalaje() {
        return $this->DescripEmbalaje;
    }

    public function getPesoEnKg() {
        return $this->PesoEnKg;
    }

    public function getValorMercancia() {
        return $this->ValorMercancia;
    }

    public function getMoneda() {
        return $this->Moneda;
    }

    public function getFraccionArancelaria() {
        return $this->FraccionArancelaria;
    }

    public function getUuidComercioExt() {
        return $this->UUIDComercioExt;
    }

    public function setPedimentos(array $Pedimentos) {
        $this->Pedimentos = $Pedimentos;
    }

    public function addPedimentos(Mercancia\Pedimentos $Pedimentos) {
        $this->Pedimentos[] = $Pedimentos;
    }

    public function setGuiasIdentificacion(array $GuiasIdentificacion) {
        $this->GuiasIdentificacion = $GuiasIdentificacion;
    }

    public function addGuiasIdentificacion(Mercancia\GuiasIdentificacion $GuiasIdentificacion) {
        $this->GuiasIdentificacion[] = $GuiasIdentificacion;
    }

    public function setCantidadTransporta($CantidadTransporta) {
        $this->CantidadTransporta = $CantidadTransporta;
    }

    public function addCantidadTransporta(Mercancia\CantidadTransporta $CantidadTransporta) {
        $this->CantidadTransporta[] = $CantidadTransporta;
    }

    public function setDetalleMercancia(Mercancia\DetalleMercancia $DetalleMercancia) {
        $this->DetalleMercancia = $DetalleMercancia;
    }

    public function setBienesTransp($BienesTransp) {
        $this->BienesTransp = $BienesTransp;
    }

    public function setClaveSTCC($ClaveSTCC) {
        $this->ClaveSTCC = $ClaveSTCC;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    public function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    public function setClaveUnidad($ClaveUnidad) {
        $this->ClaveUnidad = $ClaveUnidad;
    }

    public function setUnidad($Unidad) {
        $this->Unidad = $Unidad;
    }

    public function setDimensiones($Dimensiones) {
        $this->Dimensiones = $Dimensiones;
    }

    public function setMaterialPeligroso($MaterialPeligroso) {
        $this->MaterialPeligroso = $MaterialPeligroso;
    }

    public function setCveMaterialPeligroso($CveMaterialPeligroso) {
        $this->CveMaterialPeligroso = $CveMaterialPeligroso;
    }

    public function setEmbalaje($Embalaje) {
        $this->Embalaje = $Embalaje;
    }

    public function setDescripEmbalaje($DescripEmbalaje) {
        $this->DescripEmbalaje = $DescripEmbalaje;
    }

    public function setPesoEnKg($PesoEnKg) {
        $this->PesoEnKg = $PesoEnKg;
    }

    public function setValorMercancia($ValorMercancia) {
        $this->ValorMercancia = $ValorMercancia;
    }

    public function setMoneda($Moneda) {
        $this->Moneda = $Moneda;
    }

    public function setFraccionArancelaria($FraccionArancelaria) {
        $this->FraccionArancelaria = $FraccionArancelaria;
    }

    public function setUuidComercioExt($UUIDComercioExt) {
        $this->UUIDComercioExt = $UUIDComercioExt;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val))
                                && !($val instanceof Mercancia\DetalleMercancia);
        });
    }

    public function asXML($root) {
        $Mercancia = $root->ownerDocument->createElement("cartaporte20:Mercancia");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Mercancia->setAttribute($attr, $value);
        }
        if (!empty($this->Pedimentos)) {
            foreach ($this->Pedimentos as $Pedimento) {
                $Mercancia->appendChild($Pedimento->asXML($root));
            }
        }
        if (!empty($this->GuiasIdentificacion)) {
            foreach ($this->GuiasIdentificacion as $guiasIdentificacion) {
                $Mercancia->appendChild($guiasIdentificacion->asXML($root));
            }
        }
        if (!empty($this->CantidadTransporta)) {
            foreach ($this->CantidadTransporta as $CantidadTransporta) {
                $Mercancia->appendChild($CantidadTransporta->asXML($root));
            }
        }
        if (!empty($this->DetalleMercancia)) {
            $Mercancia->appendChild($this->DetalleMercancia->asXML($root));
        }
        return $Mercancia;
    }

    public static function parse($DOMElement = null) {
        $Mercancia = new Mercancia();
        Reflection::setAttributes($Mercancia, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:Pedimentos")!==false) {
                $Mercancia->addPedimentos(Mercancia\Pedimentos::parse($node));
            } else 
            if (strpos($node->nodeName, "cartaporte20:GuiasIdentificacion")!==false) {
                $Mercancia->addGuiasIdentificacion(Mercancia\GuiasIdentificacion::parse($node));
            } else
            if (strpos($node->nodeName, "cartaporte20:CantudadTransporta")!==false) {
                $Mercancia->addCantidadTransporta(Mercancia\CantidadTransporta::parse($node));
            } else
            if (strpos($node->nodeName, "cartaporte20:DetalleMercancia")!==false) {
                $Mercancia->setDetalleMercancia(Mercancia\DetalleMercancia::parse($node));
            }
        }
        return $Mercancia;
    }
}

class Autotransporte implements CFDIElement {

    /** @var Autotransporte\IdentificacionVehicular */
    private $IdentificacionVehicular;
    /** @var Autotransporte\Seguros */
    private $Seguros;
    /** @var Autotransporte\Remolques */
    private $Remolques;
    private $PermSCT;
    private $NumPermisoSCT;

    public function getIdentificacionVehicular() {
        return $this->IdentificacionVehicular;
    }

    public function getSeguros() {
        return $this->Seguros;
    }

    public function getRemolques() {
        return $this->Remolques;
    }

    public function getPermSCT() {
        return $this->PermSCT;
    }

    public function getNumPermisoSCT() {
        return $this->NumPermisoSCT;
    }

    public function setIdentificacionVehicular(Autotransporte\IdentificacionVehicular $IdentificacionVehicular) {
        $this->IdentificacionVehicular = $IdentificacionVehicular;
    }

    public function setSeguros(Autotransporte\Seguros $Seguros) {
        $this->Seguros = $Seguros;
    }

    public function setRemolques(Autotransporte\Remolques $Remolques) {
        $this->Remolques = $Remolques;
    }

    public function setPermSCT($PermSCT) {
        $this->PermSCT = $PermSCT;
    }

    public function setNumPermisoSCT($NumPermisoSCT) {
        $this->NumPermisoSCT = $NumPermisoSCT;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val))
                                && !($val instanceof Autotransporte\IdentificacionVehicular)
                                && !($val instanceof Autotransporte\Seguros)
                                && !($val instanceof Autotransporte\Remolques);
        });
    }

    public function asXML($root) {
        $Autotransporte = $root->ownerDocument->createElement("cartaporte20:Autotransporte");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Autotransporte->setAttribute($attr, $value);
        }
        if (!empty($this->IdentificacionVehicular)) {
            $Autotransporte->appendChild($this->IdentificacionVehicular->asXML($root));
        }
        if (!empty($this->Seguros)) {
            $Autotransporte->appendChild($this->Seguros->asXML($root));
        }
        if (!empty($this->Remolques)) {
            $Autotransporte->appendChild(($this->Remolques->asXML($root)));
        }
        return $Autotransporte;
    }
    
    public static function parse($DOMElement = null) {
        $Autotransporte = new Autotransporte();
        Reflection::setAttributes($Autotransporte, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:IdentificacionVehicular")!==false) {
                $Autotransporte->setIdentificacionVehicular(Autotransporte\IdentificacionVehicular::parse($node));
            } else
            if (strpos($node->nodeName, "cartaporte20:Seguros")!==false) {
                $Autotransporte->setSeguros(Autotransporte\Seguros::parse($node));
            } else
            if (strpos($node->nodeName, "cartaporte20:Remolques")!==false) {
                $Autotransporte->setRemolques(Autotransporte\Remolques::parse($node));
            }
        }
        return $Autotransporte;
    }
}

class TransporteMaritimo implements CFDIElement {

    /** @var TransporteMaritimo\Contenedor[] */
    private $Contenedor;
    private $PermSCT;
    private $NumPermisoSCT;
    private $NombreAseg;
    private $NumPolizaSeguro;
    private $TipoEmbarcacion;
    private $Matricula;
    private $NumeroOMI;
    private $AnioEmbarcacion;
    private $NombreEmbarc;
    private $NacionalidadEmbarc;
    private $UnidadesDeArqBruto;
    private $TipoCarga;
    private $NumCertITC;
    private $Eslora;
    private $Manga;
    private $Calado;
    private $LineaNaviera;
    private $NombreAgenteNaviero;
    private $NumAutorizacionNaviero;
    private $NumViaje;
    private $NumConocEmbarc;
    
    public function getContenedor() {
        return $this->Contenedor;
    }

    public function getPermSCT() {
        return $this->PermSCT;
    }

    public function getNumPermisoSCT() {
        return $this->NumPermisoSCT;
    }

    public function getNombreAseg() {
        return $this->NombreAseg;
    }

    public function getNumPolizaSeguro() {
        return $this->NumPolizaSeguro;
    }

    public function getTipoEmbarcacion() {
        return $this->TipoEmbarcacion;
    }

    public function getMatricula() {
        return $this->Matricula;
    }

    public function getNumeroOMI() {
        return $this->NumeroOMI;
    }

    public function getAnioEmbarcacion() {
        return $this->AnioEmbarcacion;
    }

    public function getNombreEmbarc() {
        return $this->NombreEmbarc;
    }

    public function getNacionalidadEmbarc() {
        return $this->NacionalidadEmbarc;
    }

    public function getUnidadesDeArqBruto() {
        return $this->UnidadesDeArqBruto;
    }

    public function getTipoCarga() {
        return $this->TipoCarga;
    }

    public function getNumCertITC() {
        return $this->NumCertITC;
    }

    public function getEslora() {
        return $this->Eslora;
    }

    public function getManga() {
        return $this->Manga;
    }

    public function getCalado() {
        return $this->Calado;
    }

    public function getLineaNaviera() {
        return $this->LineaNaviera;
    }

    public function getNombreAgenteNaviero() {
        return $this->NombreAgenteNaviero;
    }

    public function getNumAutorizacionNaviero() {
        return $this->NumAutorizacionNaviero;
    }

    public function getNumViaje() {
        return $this->NumViaje;
    }

    public function getNumConocEmbarc() {
        return $this->NumConocEmbarc;
    }

    public function setContenedor(TransporteMaritimo\Contenedor $Contenedor) {
        $this->Contenedor = $Contenedor;
    }

    public function addContenedor(array $Contenedor) {
        $this->Contenedor[] = $Contenedor;
    }

    public function setPermSCT($PermSCT) {
        $this->PermSCT = $PermSCT;
    }

    public function setNumPermisoSCT($NumPermisoSCT) {
        $this->NumPermisoSCT = $NumPermisoSCT;
    }

    public function setNombreAseg($NombreAseg) {
        $this->NombreAseg = $NombreAseg;
    }

    public function setNumPolizaSeguro($NumPolizaSeguro) {
        $this->NumPolizaSeguro = $NumPolizaSeguro;
    }

    public function setTipoEmbarcacion($TipoEmbarcacion) {
        $this->TipoEmbarcacion = $TipoEmbarcacion;
    }

    public function setMatricula($Matricula) {
        $this->Matricula = $Matricula;
    }

    public function setNumeroOMI($NumeroOMI) {
        $this->NumeroOMI = $NumeroOMI;
    }

    public function setAnioEmbarcacion($AnioEmbarcacion) {
        $this->AnioEmbarcacion = $AnioEmbarcacion;
    }

    public function setNombreEmbarc($NombreEmbarc) {
        $this->NombreEmbarc = $NombreEmbarc;
    }

    public function setNacionalidadEmbarc($NacionalidadEmbarc) {
        $this->NacionalidadEmbarc = $NacionalidadEmbarc;
    }

    public function setUnidadesDeArqBruto($UnidadesDeArqBruto) {
        $this->UnidadesDeArqBruto = $UnidadesDeArqBruto;
    }

    public function setTipoCarga($TipoCarga) {
        $this->TipoCarga = $TipoCarga;
    }

    public function setNumCertITC($NumCertITC) {
        $this->NumCertITC = $NumCertITC;
    }

    public function setEslora($Eslora) {
        $this->Eslora = $Eslora;
    }

    public function setManga($Manga) {
        $this->Manga = $Manga;
    }

    public function setCalado($Calado) {
        $this->Calado = $Calado;
    }

    public function setLineaNaviera($LineaNaviera) {
        $this->LineaNaviera = $LineaNaviera;
    }

    public function setNombreAgenteNaviero($NombreAgenteNaviero) {
        $this->NombreAgenteNaviero = $NombreAgenteNaviero;
    }

    public function setNumAutorizacionNaviero($NumAutorizacionNaviero) {
        $this->NumAutorizacionNaviero = $NumAutorizacionNaviero;
    }

    public function setNumViaje($NumViaje) {
        $this->NumViaje = $NumViaje;
    }

    public function setNumConocEmbarc($NumConocEmbarc) {
        $this->NumConocEmbarc = $NumConocEmbarc;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val));
        });
    }

    public function asXML($root) {
        $TransporteMatitimo = $root->ownerDocument->createElement("cartaporte20:TransporteMaritimo");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $TransporteMatitimo->setAttribute($attr, $value);
        }
        if (!empty($this->Contenedor)) {
            foreach ($this->Contenedor as $contenedor) {
                $TransporteMatitimo->appendChild($contenedor->asXML($root));
            }
        }
        return $TransporteMatitimo;
    }
    
    public static function parse($DOMElement = null) {
        $TransporteMaritimo = new TransporteMaritimo();
        Reflection::setAttributes($TransporteMaritimo, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:Contenedor")!==false) {
                $TransporteMaritimo->addContenedor(TransporteMaritimo\Contenedor::parse($node));
            }
        }
        return $TransporteMaritimo;
    }
}

class TransporteAereo implements CFDIElement {

    private $PermSCT;
    private $NumPermisoSCT;
    private $MatriculaAeronave;
    private $NombreAseg;
    private $NumPolizaSeguro;
    private $NumeroGuia;
    private $LugarContrato;
    private $CodigoTransportista;
    private $RFCEmbarcador;
    private $NumRegIdTribEmbarc;
    private $ResidenciaFiscalEmbarc;
    private $NombreEmbarcador;

    public function getPermSCT() {
        return $this->PermSCT;
    }

    public function getNumPermisoSCT() {
        return $this->NumPermisoSCT;
    }

    public function getMatriculaAeronave() {
        return $this->MatriculaAeronave;
    }

    public function getNombreAseg() {
        return $this->NombreAseg;
    }

    public function getNumPolizaSeguro() {
        return $this->NumPolizaSeguro;
    }

    public function getNumeroGuia() {
        return $this->NumeroGuia;
    }

    public function getLugarContrato() {
        return $this->LugarContrato;
    }

    public function getCodigoTransportista() {
        return $this->CodigoTransportista;
    }

    public function getRFCEmbarcador() {
        return $this->RFCEmbarcador;
    }

    public function getNumRegIdTribEmbarc() {
        return $this->NumRegIdTribEmbarc;
    }

    public function getResidenciaFiscalEmbarc() {
        return $this->ResidenciaFiscalEmbarc;
    }

    public function getNombreEmbarcador() {
        return $this->NombreEmbarcador;
    }

    public function setPermSCT($PermSCT) {
        $this->PermSCT = $PermSCT;
    }

    public function setNumPermisoSCT($NumPermisoSCT) {
        $this->NumPermisoSCT = $NumPermisoSCT;
    }

    public function setMatriculaAeronave($MatriculaAeronave) {
        $this->MatriculaAeronave = $MatriculaAeronave;
    }

    public function setNombreAseg($NombreAseg) {
        $this->NombreAseg = $NombreAseg;
    }

    public function setNumPolizaSeguro($NumPolizaSeguro) {
        $this->NumPolizaSeguro = $NumPolizaSeguro;
    }

    public function setNumeroGuia($NumeroGuia) {
        $this->NumeroGuia = $NumeroGuia;
    }

    public function setLugarContrato($LugarContrato) {
        $this->LugarContrato = $LugarContrato;
    }

    public function setCodigoTransportista($CodigoTransportista) {
        $this->CodigoTransportista = $CodigoTransportista;
    }

    public function setRFCEmbarcador($RFCEmbarcador) {
        $this->RFCEmbarcador = $RFCEmbarcador;
    }

    public function setNumRegIdTribEmbarc($NumRegIdTribEmbarc) {
        $this->NumRegIdTribEmbarc = $NumRegIdTribEmbarc;
    }

    public function setResidenciaFiscalEmbarc($ResidenciaFiscalEmbarc) {
        $this->ResidenciaFiscalEmbarc = $ResidenciaFiscalEmbarc;
    }

    public function setNombreEmbarcador($NombreEmbarcador) {
        $this->NombreEmbarcador = $NombreEmbarcador;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val));
        });
    }

    public function asXML($root) {
        $TransporteAereo = $root->ownerDocument->createElement("cartaporte20:TransporteAereo");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $TransporteAereo->setAttribute($attr, $value);
        }
        return $TransporteAereo;
    }

    public static function parse($DOMElement = null) {
        $TransporteAereo = new TransporteAereo();
        Reflection::setAttributes($TransporteAereo, $DOMElement);
        return $TransporteAereo;
    }
}

class TransporteFerroviario implements CFDIElement {

    /** @var TransporteFerroviario\DerechosDePaso[] */
    private $DerechosDePaso = array();
    /** @var TransporteFerroviario\Carro[] */
    private $Carro = array();
    private $TipoDeServicio;
    private $TipoDeTrafico;
    private $NombreAseg;
    private $NumPolizaSeguro;

    public function getDerechosDePaso() {
        return $this->DerechosDePaso;
    }

    public function getCarro() {
        return $this->Carro;
    }

    public function getTipoDeServicio() {
        return $this->TipoDeServicio;
    }

    public function getTipoDeTrafico() {
        return $this->TipoDeTrafico;
    }

    public function getNombreAseg() {
        return $this->NombreAseg;
    }

    public function getNumPolizaSeguro() {
        return $this->NumPolizaSeguro;
    }

    public function setDerechosDePaso(array $DerechosDePaso) {
        $this->DerechosDePaso = $DerechosDePaso;
    }

    public function addDerechosDePaso(TransporteFerroviario\DerechosDePaso $DerechosDePaso) {
        $this->DerechosDePaso[] = $DerechosDePaso;
    }

    public function setCarro(array $carro) {
        $this->Carro = $carro;
    }

    public function addCarro(TransporteFerroviario\Carro $Carro) {
        $this->Carro[] = $Carro;
    }

    public function setTipoDeServicio($TipoDeServicio) {
        $this->TipoDeServicio = $TipoDeServicio;
    }

    public function setTipoDeTrafico($TipoDeTrafico) {
        $this->TipoDeTrafico = $TipoDeTrafico;
    }

    public function setNombreAseg($NombreAseg) {
        $this->NombreAseg = $NombreAseg;
    }

    public function setNumPolizaSeguro($NumPolizaSeguro) {
        $this->NumPolizaSeguro = $NumPolizaSeguro;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val));
        });
    }

    public function asXML($root) {
        $TransporteFerroviario = $root->ownerDocument->createElement("cartaporte20:TransporteFerroviario");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $TransporteFerroviario->setAttribute($attr, $value);
        }
        if (!empty($this->DerechosDePaso)) {
            foreach ($this->DerechosDePaso as $DerechosDePaso) {
                $TransporteFerroviario->appendChild($DerechosDePaso->asXML($root));
            }
        }
        if (!empty($this->Carro)) {
            foreach ($this->Carro as $Carro) {
                $TransporteFerroviario->appendChild($Carro->asXML($root));
            }
        }
        return $TransporteFerroviario;
    }
    
    public static function parse($DOMElement = null) {
        $TransporteFerroviario = new TransporteFerroviario();
        Reflection::setAttributes($TransporteFerroviario, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:DerechosDePaso")!==false) {
                $TransporteFerroviario->addDerechosDePaso(TransporteFerroviario\DerechosDePaso::parse($node));
            } else
            if (strpos($node->nodeName, "cartaporte20:Carro")!==false) {
                $TransporteFerroviario->addCarro(TransporteFerroviario\Carro::parse($node));
            }
        }
        return $TransporteFerroviario;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Mercancias\Mercancia;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Pedimentos implements CFDIElement {

    private $Pedimento;

    public function getPedimento() {
        return $this->Pedimento;
    }

    public function setPedimento($Pedimento) {
        $this->Pedimento = $Pedimento;
    }

    public function asXML($root) {
        $Pedimentos = $root->ownerDocument->createElement("cartaporte20:Pedimentos");
        $Pedimentos->setAttribute("pedimento", $this->Pedimento);
        return $Pedimentos;
    }

    public static function parse($DOMElement = null) {
        $Pedimentos = new Pedimentos();
        $Pedimentos->setPedimento($DOMElement->getAttribute("pedimento"));
        return $Pedimentos;
    }
}

class GuiasIdentificacion implements CFDIElement {

    private $NumeroGuiaIdentificacion;
    private $DescripGuiaIdentificacion;
    private $PesoGuiaIdentificacion;

    public function getNumeroGuiaIdentificacion() {
        return $this->NumeroGuiaIdentificacion;
    }

    public function getDescripGuiaIdentificacion() {
        return $this->DescripGuiaIdentificacion;
    }

    public function getPesoGuiaIdentificacion() {
        return $this->PesoGuiaIdentificacion;
    }

    public function setNumeroGuiaIdentificacion($NumeroGuiaIdentificacion) {
        $this->NumeroGuiaIdentificacion = $NumeroGuiaIdentificacion;
    }

    public function setDescripGuiaIdentificacion($DescripGuiaIdentificacion) {
        $this->DescripGuiaIdentificacion = $DescripGuiaIdentificacion;
    }

    public function setPesoGuiaIdentificacion($PesoGuiaIdentificacion) {
        $this->PesoGuiaIdentificacion = $PesoGuiaIdentificacion;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $GuiasIdentificacion = $root->ownerDocument->createElement("cartaporte20:GuiasIdentificacion");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $GuiasIdentificacion->setAttribute($attr, $value);
        }
        return $GuiasIdentificacion;
    }
    
    public static function parse($DOMElement = null) {
        $GuiasIdentificacion = new GuiasIdentificacion();
        Reflection::setAttributes($GuiasIdentificacion, $DOMElement);
        return $GuiasIdentificacion;
    }
}

class DetalleMercancia implements CFDIElement {

    private $UnidadPesoMerc;
    private $PesoBruto;
    private $PesoNeto;
    private $PesoTara;
    private $NumPiezas;

    public function getUnidadPesoMerc() {
        return $this->UnidadPesoMerc;
    }

    public function getPesoBruto() {
        return $this->PesoBruto;
    }

    public function getPesoNeto() {
        return $this->PesoNeto;
    }

    public function getPesoTara() {
        return $this->PesoTara;
    }

    public function getNumPiezas() {
        return $this->NumPiezas;
    }

    public function setUnidadPesoMerc($UnidadPesoMerc) {
        $this->UnidadPesoMerc = $UnidadPesoMerc;
    }

    public function setPesoBruto($PesoBruto) {
        $this->PesoBruto = $PesoBruto;
    }

    public function setPesoNeto($PesoNeto) {
        $this->PesoNeto = $PesoNeto;
    }

    public function setPesoTara($PesoTara) {
        $this->PesoTara = $PesoTara;
    }

    public function setNumPiezas($NumPiezas) {
        $this->NumPiezas = $NumPiezas;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $DetalleMercancia = $root->ownerDocument->createElement("cartaporte20:DetalleMercancia");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $DetalleMercancia->setAttribute($attr, $value);
        }
        return $DetalleMercancia;
    }
    
    public static function parse($DOMElement = null) {
        $DetalleMercancia = new DetalleMercancia();
        Reflection::setAttributes($DetalleMercancia, $DOMElement);
        return $DetalleMercancia;
    }
}

class CantidadTransporta implements CFDIElement {

    private $Cantidad;
    private $IdOrigen;
    private $IdDestino;
    private $CvesTransporte;

    public function getCantidad() {
        return $this->Cantidad;
    }

    public function getIdOrigen() {
        return $this->IdOrigen;
    }

    public function getIdDestino() {
        return $this->IdDestino;
    }

    public function getCvesTransporte() {
        return $this->CvesTransporte;
    }

    public function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    public function setIdOrigen($IdOrigen) {
        $this->IdOrigen = $IdOrigen;
    }

    public function setIdDestino($IdDestino) {
        $this->IdDestino = $IdDestino;
    }

    public function setCvesTransporte($CvesTransporte) {
        $this->CvesTransporte = $CvesTransporte;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $CantidadTransporta = $root->ownerDocument->createElement("cartaporte20:CantidadTransporta");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $CantidadTransporta->setAttribute($attr, $value);
        }
        return $CantidadTransporta;
    }
    
    public static function parse($DOMElement = null) {
        $CantidadTransporta = new CantidadTransporta();
        Reflection::setAttributes($CantidadTransporta, $DOMElement);
        return $CantidadTransporta;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Mercancias\Autotransporte;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class IdentificacionVehicular implements CFDIElement {

    private $ConfigVehicular;
    private $PlacaVM;
    private $AnioModeloVM;

    public function getConfigVehicular() {
        return $this->ConfigVehicular;
    }

    public function getPlacaVM() {
        return $this->PlacaVM;
    }

    public function getAnioModeloVM() {
        return $this->AnioModeloVM;
    }

    public function setConfigVehicular($ConfigVehicular) {
        $this->ConfigVehicular = $ConfigVehicular;
    }

    public function setPlacaVM($PlacaVM) {
        $this->PlacaVM = $PlacaVM;
    }

    public function setAnioModeloVM($AnioModeloVM) {
        $this->AnioModeloVM = $AnioModeloVM;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $IdentificacionVehicular = $root->ownerDocument->createElement("cartaporte20:IdentificacionVehicular");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $IdentificacionVehicular->setAttribute($attr, $value);
        }
        return $IdentificacionVehicular;
    }
    
    public static function parse($DOMElement = null) {
        $IdentificacionVehicular = new IdentificacionVehicular();
        Reflection::setAttributes($IdentificacionVehicular, $DOMElement);
        return $IdentificacionVehicular;
    }
}

class Seguros implements CFDIElement {

    private $AseguraRespCivil;
    private $PolizaRespCivil;
    private $AseguraMedAmbiente;
    private $PolizaMedAmbiente;
    private $AseguraCarga;
    private $PolizaCarga;
    private $PrimaSeguro;

    public function getAseguraRespCivil() {
        return $this->AseguraRespCivil;
    }

    public function getPolizaRespCivil() {
        return $this->PolizaRespCivil;
    }

    public function getAseguraMedAmbiente() {
        return $this->AseguraMedAmbiente;
    }

    public function getPolizaMedAmbiente() {
        return $this->PolizaMedAmbiente;
    }

    public function getAseguraCarga() {
        return $this->AseguraCarga;
    }

    public function getPolizaCarga() {
        return $this->PolizaCarga;
    }

    public function getPrimaSeguro() {
        return $this->PrimaSeguro;
    }

    public function setAseguraRespCivil($AseguraRespCivil) {
        $this->AseguraRespCivil = $AseguraRespCivil;
    }

    public function setPolizaRespCivil($PolizaRespCivil) {
        $this->PolizaRespCivil = $PolizaRespCivil;
    }

    public function setAseguraMedAmbiente($AseguraMedAmbiente) {
        $this->AseguraMedAmbiente = $AseguraMedAmbiente;
    }

    public function setPolizaMedAmbiente($PolizaMedAmbiente) {
        $this->PolizaMedAmbiente = $PolizaMedAmbiente;
    }

    public function setAseguraCarga($AseguraCarga) {
        $this->AseguraCarga = $AseguraCarga;
    }

    public function setPolizaCarga($PolizaCarga) {
        $this->PolizaCarga = $PolizaCarga;
    }

    public function setPrimaSeguro($PrimaSeguro) {
        $this->PrimaSeguro = $PrimaSeguro;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $Seguros = $root->ownerDocument->createElement("cartaporte20:Seguros");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Seguros->setAttribute($attr, $value);
        }
        return $Seguros;
    }
    
    public static function parse($DOMElement = null) {
        $Seguros = new Seguros();
        Reflection::setAttributes($Seguros, $DOMElement);
        return $Seguros;
    }
}

class Remolques implements CFDIElement {

    /** @var Remolques\Remolque[] */
    private $Remolque = array();

    public function getRemolque() {
        return $this->Remolque;
    }

    public function setRemolque(array $Remolque) {
        $this->Remolque = $Remolque;
    }

    public function addRemolque(Remolques\Remolque $Remolque) {
        $this->Remolque[] = $Remolque;
    }

    public function asXML($root) {
        $Remolques = $root->ownerDocument->createElement("cartaporte20:Remolques");
        if (!empty($this->Remolque)) {
            foreach ($this->Remolque as $Remolque)
            $Remolques->appendChild($Remolque->asXML ($root));
        }
        return $Remolques;
    }
    
    public static function parse($DOMElement = null) {
        $Remolques = new Remolques();
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:Remolque")!==false) {
                $Remolques->addRemolque(Remolques\Remolque::parse($node));
            }
        }
        return $Remolques;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Mercancias\Autotransporte\Remolques;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Remolque implements CFDIElement {

    private $SubTipoRem;
    private $Placa;

    public function getSubTipoRem() {
        return $this->SubTipoRem;
    }

    public function getPlaca() {
        return $this->Placa;
    }

    public function setSubTipoRem($SubTipoRem) {
        $this->SubTipoRem = $SubTipoRem;
    }

    public function setPlaca($Placa) {
        $this->Placa = $Placa;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $Remolque = $root->ownerDocument->createElement("cartaporte20:Remolque");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Remolque->setAttribute($attr, $value);
        }
        return $Remolque;
    }
    
    public static function parse($DOMElement = null) {
        $Remolque = new Remolque();
        Reflection::setAttributes($Remolque, $DOMElement);
        return $Remolque;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\FigurasTransporte;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class TiposFigura implements CFDIElement {

    /** @var TiposFigura\PartesTransporte[] */
    private $PartesTransporte = array();
    /** @var TiposFigura\Domicilio */
    private $Domicilio;
    private $TipoFigura;
    private $RFCFigura;
    private $NumLicencia;
    private $NombreFigura;
    private $NumRegIdTribFigura;
    private $ResidenciaFiscalFigura;

    public function getPartesTransporte() {
        return $this->PartesTransporte;
    }

    public function getDomicilio() {
        return $this->Domicilio;
    }

    public function getTipoFigura() {
        return $this->TipoFigura;
    }

    public function getRFCFigura() {
        return $this->RFCFigura;
    }

    public function getNumLicencia() {
        return $this->NumLicencia;
    }

    public function getNombreFigura() {
        return $this->NombreFigura;
    }

    public function getNumRegIdTribFigura() {
        return $this->NumRegIdTribFigura;
    }

    public function getResidenciaFiscalFigura() {
        return $this->ResidenciaFiscalFigura;
    }

    public function setPartesTransporte(array $PartesTransporte) {
        $this->PartesTransporte = $PartesTransporte;
    }

    public function addPartesTransporte(TiposFigura\PartesTransporte $PartesTransporte) {
        $this->PartesTransporte[] = $PartesTransporte;
    }

    public function setDomicilio(TiposFigura\Domicilio $Domicilio) {
        $this->Domicilio = $Domicilio;
    }

    public function setTipoFigura($TipoFigura) {
        $this->TipoFigura = $TipoFigura;
    }

    public function setRFCFigura($RFCFigura) {
        $this->RFCFigura = $RFCFigura;
    }

    public function setNumLicencia($NumLicencia) {
        $this->NumLicencia = $NumLicencia;
    }

    public function setNombreFigura($NombreFigura) {
        $this->NombreFigura = $NombreFigura;
    }

    public function setNumRegIdTribFigura($NumRegIdTribFigura) {
        $this->NumRegIdTribFigura = $NumRegIdTribFigura;
    }

    public function setResidenciaFiscalFigura($ResidenciaFiscalFigura) {
        $this->ResidenciaFiscalFigura = $ResidenciaFiscalFigura;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return 
                                !is_array($val) 
                                && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val))
                                && !($val instanceof TiposFigura\Domicilio);
                    });
    }

    public function asXML($root) {
        $TiposFigura = $root->ownerDocument->createElement("cartaporte20:TiposFigura");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $TiposFigura->setAttribute($attr, $value);
        }
        if (!empty($this->PartesTransporte)) {
            foreach ($this->PartesTransporte as $PartesTransporte) {
                $TiposFigura->appendChild($PartesTransporte->asXML($root));
            }
        }
        if (!empty($this->Domicilio)) {
            $TiposFigura->appendChild($this->Domicilio->asXML($root));
        }
        return $TiposFigura;
    }
    
    public static function parse($DOMElement = null) {
        $TiposFigura = new TiposFigura();
        Reflection::setAttributes($TiposFigura, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:PartesTransporte")!==false) {
                $TiposFigura->addPartesTransporte(TiposFigura\PartesTransporte::parse($node));
            } else
            if (strpos($node->nodeName, "cartaporte20:Domicilio")!==false) {
                $TiposFigura->setDomicilio(TiposFigura\Domicilio::parse($node));
            }
        }
        return $TiposFigura;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\FigurasTransporte\TiposFigura;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class PartesTransporte implements CFDIElement {

    private $ParteTransporte;

    public function getParteTransporte() {
        return $this->ParteTransporte;
    }

    public function setParteTransporte($ParteTransporte) {
        $this->ParteTransporte = $ParteTransporte;
    }

    public function asXML($root) {
        $PartesTransporte = $root->ownerDocument->createElement("cartaporte20:PartesTransporte");
        if (!empty($this->ParteTransporte)) {
            $PartesTransporte->setAttribute("ParteTransporte", $this->ParteTransporte);
        }
        return $PartesTransporte;
    }
    
    public static function parse($DOMElement = null) {
        $PartesTransporte = new PartesTransporte();
        $PartesTransporte->setParteTransporte($DOMElement->getAttribute("ParteTransporte"));
        return $PartesTransporte;
    }
}

class Domicilio implements CFDIElement {

    private $Calle;
    private $NumeroExterior;
    private $NumeroInterior;
    private $Colonia;
    private $Localidad;
    private $Referencia;
    private $Municipio;
    private $Estado;
    private $Pais;
    private $CodigoPostal;

    public function getCalle() {
        return $this->Calle;
    }

    public function getNumeroExterior() {
        return $this->NumeroExterior;
    }

    public function getNumeroInterior() {
        return $this->NumeroInterior;
    }

    public function getColonia() {
        return $this->Colonia;
    }

    public function getLocalidad() {
        return $this->Localidad;
    }

    public function getReferencia() {
        return $this->Referencia;
    }

    public function getMunicipio() {
        return $this->Municipio;
    }

    public function getEstado() {
        return $this->Estado;
    }

    public function getPais() {
        return $this->Pais;
    }

    public function getCodigoPostal() {
        return $this->CodigoPostal;
    }

    public function setCalle($Calle) {
        $this->Calle = $Calle;
    }

    public function setNumeroExterior($NumeroExterior) {
        $this->NumeroExterior = $NumeroExterior;
    }

    public function setNumeroInterior($NumeroInterior) {
        $this->NumeroInterior = $NumeroInterior;
    }

    public function setColonia($Colonia) {
        $this->Colonia = $Colonia;
    }

    public function setLocalidad($Localidad) {
        $this->Localidad = $Localidad;
    }

    public function setReferencia($Referencia) {
        $this->Referencia = $Referencia;
    }

    public function setMunicipio($Municipio) {
        $this->Municipio = $Municipio;
    }

    public function setEstado($Estado) {
        $this->Estado = $Estado;
    }

    public function setPais($Pais) {
        $this->Pais = $Pais;
    }

    public function setCodigoPostal($CodigoPostal) {
        $this->CodigoPostal = $CodigoPostal;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $Domicilio = $root->ownerDocument->createElement("cartaporte20:Domicilio");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Domicilio->setAttribute($attr, $value);
        }
        return $Domicilio;
    }
    
    public static function parse($DOMElement = null) {
        $Domicilio = new Domicilio();
        Reflection::setAttributes($Domicilio, $DOMElement);
        return $Domicilio;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Mercancias\TransporteFerroviario;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class DerechosDePaso implements CFDIElement {

    private $TipoDerechoDePaso;
    private $KilometrajePagado;

    public function getTipoDerechoDePaso() {
        return $this->TipoDerechoDePaso;
    }

    public function getKilometrajePagado() {
        return $this->KilometrajePagado;
    }

    public function setTipoDerechoDePaso($TipoDerechoDePaso) {
        $this->TipoDerechoDePaso = $TipoDerechoDePaso;
    }

    public function setKilometrajePagado($KilometrajePagado) {
        $this->KilometrajePagado = $KilometrajePagado;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $DerechosDePaso = $root->ownerDocument->createElement("cartaporte20:DerechosDePaso");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $DerechosDePaso->setAttribute($attr, $value);
        }
        return $DerechosDePaso;
    }
    
    public static function parse($DOMElement = null) {
        $DerechosDePaso = new DerechosDePaso();
        Reflection::setAttributes($DerechosDePaso, $DOMElement);
        return $DerechosDePaso;
    }
}

class Carro implements CFDIElement {

    /** @var Carro\Contenedor[] */
    private $Contenedor = array();
    private $TipoCarro;
    private $MatriculaCarro;
    private $GuiaCarro;
    private $ToneladasNetasCarro;

    public function getContenedor() {
        return $this->Contenedor;
    }

    public function getTipoCarro() {
        return $this->TipoCarro;
    }

    public function getMatriculaCarro() {
        return $this->MatriculaCarro;
    }

    public function getGuiaCarro() {
        return $this->GuiaCarro;
    }

    public function getToneladasNetasCarro() {
        return $this->ToneladasNetasCarro;
    }

    public function setContenedor(array $Contenedor) {
        $this->Contenedor = $Contenedor;
    }

    public function addContenedor(Carro\Contenedor $Contenedor) {
        $this->Contenedor[] = $Contenedor;
    }

    public function setTipoCarro($TipoCarro) {
        $this->TipoCarro = $TipoCarro;
    }

    public function setMatriculaCarro($MatriculaCarro) {
        $this->MatriculaCarro = $MatriculaCarro;
    }

    public function setGuiaCarro($GuiaCarro) {
        $this->GuiaCarro = $GuiaCarro;
    }

    public function setToneladasNetasCarro($ToneladasNetasCarro) {
        $this->ToneladasNetasCarro = $ToneladasNetasCarro;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return !is_array($val)
                                        && ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); 
                    });
    }

    public function asXML($root) {
        $Carro = $root->ownerDocument->createElement("cartaporte20:Carro");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Carro->setAttribute($attr, $value);
        }
        if (!empty($this->Contenedor)) {
            foreach ($this->Contenedor as $Contenedor) {
                $Carro->appendChild($Contenedor->asXML($root));
            }
        }
        return $Carro;
    }
    
    public static function parse($DOMElement = null) {
        $Carro = new Carro();
        Reflection::setAttributes($Carro, $DOMElement);
        for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
            $node = $DOMElement->childNodes->item($i);
            if (strpos($node->nodeName, "cartaporte20:Contenedor")!==false) {
                $Carro->addContenedor(Carro\Contenedor::parse($node));
            }
        }
        return $Carro;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Mercancias\TransporteFerroviario\Carro;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Contenedor implements CFDIElement {

    private $TipoContenedor;
    private $PesoContenedorVacio;
    private $PesoNetoMercancia;

    public function getTipoContenedor() {
        return $this->TipoContenedor;
    }

    public function getPesoContenedorVacio() {
        return $this->PesoContenedorVacio;
    }

    public function getPesoNetoMercancia() {
        return $this->PesoNetoMercancia;
    }

    public function setTipoContenedor($TipoContenedor) {
        $this->TipoContenedor = $TipoContenedor;
    }

    public function setPesoContenedorVacio($PesoContenedorVacio) {
        $this->PesoContenedorVacio = $PesoContenedorVacio;
    }

    public function setPesoNetoMercancia($PesoNetoMercancia) {
        $this->PesoNetoMercancia = $PesoNetoMercancia;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $Contenedor = $root->ownerDocument->createElement("cartaporte20:Contenedor");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Contenedor->setAttribute($attr, $value);
        }
        return $Contenedor;
    }
    
    public static function parse($DOMElement = null) {
        $Contenedor = new Contenedor();
        Reflection::setAttributes($Contenedor, $DOMElement);
        return $Contenedor;
    }
}

namespace com\softcoatl\cfdi\complemento\cartaporte\CartaPorte20\Mercancias\TransporteMaritimo;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class Contenedor implements CFDIElement {

    private $MatriculaContenedor;
    private $TipoContenedor;
    private $NumPrecinto;

    public function getMatriculaContenedor() {
        return $this->MatriculaContenedor;
    }

    public function getTipoContenedor() {
        return $this->TipoContenedor;
    }

    public function getNumPrecinto() {
        return $this->NumPrecinto;
    }

    public function setMatriculaContenedor($MatriculaContenedor) {
        $this->MatriculaContenedor = $MatriculaContenedor;
    }

    public function setTipoContenedor($TipoContenedor) {
        $this->TipoContenedor = $TipoContenedor;
    }

    public function setNumPrecinto($NumPrecinto) {
        $this->NumPrecinto = $NumPrecinto;
    }

    public function getVarArray() {
        return array_filter(get_object_vars($this), 
                    function ($val) { return ($val === '0' || $val === 0 || $val === 0.0 || !empty($val)); });
    }

    public function asXML($root) {
        $Contenedor = $root->ownerDocument->createElement("cartaporte20:Contenedor");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
           $Contenedor->setAttribute($attr, $value);
        }
        return $Contenedor;
    }
    
    public static function parse($DOMElement = null) {
        $Contenedor = new Contenedor();
        Reflection::setAttributes($Contenedor, $DOMElement);
        return $Contenedor;
    }
}
