<?php
/*
 * DocumentoCFDIDetifac
 * detifac®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace com\detisa\detifac;

use com\softcoatl\cfdi\Comprobante;

interface DocumentoCFDIDetifac {

    function getComprobante();
    function setComprobante(Comprobante $comprobante);
    function setComprobanteTimbrado(Comprobante $comprobanteTimbrado);
    function setRepresentacionImpresa($representacionImpresa);
    function update($id);
    function save($id, $tipo, $clavePAC);
    function cancel($id);
    function acuse($id, $acuse);
}//DocumentoC
