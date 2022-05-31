<?php
/* 
 * INE
 * cfdi®
 * © 2018, Softcoatl
 * http://www.softcoatl.com.mx
 * @author Rolando Esquivel Villafaña
 * @version 1.0
 * @since may 2018
 */
namespace com\softcoatl\cfdi\complemento\ine;

use com\softcoatl\cfdi\CFDIElement;
use com\softcoatl\cfdi\utils\Reflection;

class INE implements CFDIElement {

    /** @var INE\Entidad[] */
    private $Entidad = array();
    private $Version = "1.1";
    private $TipoProceso;
    private $TipoComite;
    private $IdContabilidad;

    function getEntidad() {
        return $this->Entidad;
    }

    function getVersion() {
        return $this->Version;
    }

    function getTipoProceso() {
        return $this->TipoProceso;
    }

    function getTipoComite() {
        return $this->TipoComite;
    }

    function getIdContabilidad() {
        return $this->IdContabilidad;
    }

    function setEntidad(array $entidad) {
        $this->Entidad = $entidad;
    }

    function addEntidad(INE\Entidad $entidad) {
        $this->Entidad[] = $entidad;
    }

    function setVersion($version) {
        $this->Version = $version;
    }

    function setTipoProceso($tipoProceso) {
        $this->TipoProceso = $tipoProceso;
    }

    function setTipoComite($tipoComite) {
        $this->TipoComite = $tipoComite;
    }

    function setIdContabilidad($idContabilidad) {
        $this->IdContabilidad = $idContabilidad;
    }

    public function asXML($root) {

        if ($root->ownerDocument->documentElement
                && empty($root->ownerDocument->documentElement->attributes->getNamedItem("xmlns:ine"))) {
            $root->ownerDocument->documentElement->setAttribute("xmlns:ine", "http://www.sat.gob.mx/ine");
            $root->ownerDocument->documentElement->setAttribute("xsi:schemaLocation", 
                            $root->ownerDocument->documentElement->getAttribute("xsi:schemaLocation") 
                        .   " http://www.sat.gob.mx/ine http://www.sat.gob.mx/sitio_internet/cfd/ine/ine10.xsd");
        }
        $INE = $root->ownerDocument->createElement("ine:INE");

        $ov = array_filter(get_object_vars($this), 
                        function ($val) { 
                            return !is_array($val) && !empty($val);
                        });
        foreach ($ov as $attr=>$value) {
            $INE->setAttribute($attr, $value);
        }
        if (!empty($this->Entidad)) {
            foreach ($this->Entidad as $entidad) {
                $INE->appendChild($entidad->asXML($root));
            }
        }
        return $INE;
    }

    public static function parse($DOMElement) {

        if (strpos($DOMElement->nodeName, ':INE')) {
            $INE = new INE();
            Reflection::setAttributes($INE, $DOMElement);
            for ($i=0; $i<$DOMElement->childNodes->length; $i++) {
                $node = $DOMElement->childNodes->item($i);
                if (strpos($node->nodeName, ':Entidad')!==false) {
                    $Entidad = new INE\Entidad();
                    Reflection::setAttributes($Entidad, $node);
                    for ($j=0; $j<$node->childNodes->length; $j++) {
                        $nodeC = $node->childNodes->item($j);
                        if (strpos($nodeC->nodeName, ':Contabilidad')!==false) {
                            $Contabilidad = new INE\Entidad\Contabilidad();
                            Reflection::setAttributes($Contabilidad, $nodeC);
                            $Entidad->addContabilidad($Contabilidad);
                        }
                    }
                    $INE->addEntidad($Entidad);
                }
            }
            return $INE;
        }
        return false;
    }
}

namespace com\softcoatl\cfdi\complemento\INE;

use com\softcoatl\cfdi\CFDIElement;

class Entidad implements CFDIElement {

    /** @var Entidad\Contabilidad[] */
    private $Contabilidad = array();
    private $ClaveEntidad;
    private $Ambito;

    function getContabilidad() {
        return $this->Contabilidad;
    }

    function getClaveEntidad() {
        return $this->ClaveEntidad;
    }

    function getAmbito() {
        return $this->Ambito;
    }

    public function setContabilidad(array $Contabilidad) {
        $this->Contabilidad = $Contabilidad;
    }

    function addContabilidad(Entidad\Contabilidad $contabilidad) {
        $this->Contabilidad[] = $contabilidad;
    }

    function setClaveEntidad($claveEntidad) {
        $this->ClaveEntidad = $claveEntidad;
    }

    function setAmbito($ambito) {
        $this->Ambito = $ambito;
    }

    public function asXML($root) {
        $Entidad = $root->ownerDocument->createElement("ine:Entidad");
        $ov = $this->getVarArray();
        foreach ($ov as $attr=>$value) {
            $Entidad->setAttribute($attr, $value);
        }

        if (!empty($this->Contabilidad)) {
            foreach ($this->Contabilidad as $contabilida) {
                $Entidad->appendChild($contabilida->asXML($root));
            }
        }
        return $Entidad;
    }
}

namespace com\softcoatl\cfdi\complemento\INE\Entidad;

use com\softcoatl\cfdi\CFDIElement;

class Contabilidad implements CFDIElement {

    private $IdContabilidad;

    function getIdContabilidad() {
        return $this->IdContabilidad;
    }

    function setIdContabilidad($idContabilidad) {
        $this->IdContabilidad = $idContabilidad;
    }

    public function asXML($root) {
        $Contabilidad = $root->ownerDocument->createElement("ine:Contabilidad");
        $Contabilidad->setAttribute("IdContabilidad", $this->IdContabilidad);
        return $Contabilidad;
    }
}