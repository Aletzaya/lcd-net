<?php

/*
 * FacturaDetisa
 * detifac®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */

namespace com\detisa\omicrom {

    require_once ('cfdi/com/softcoatl/cfdi/Comprobante.php');
    require_once ('FacturaLcdDAO.php');
    require_once ('mysqlUtils.php');

    class FacturaLcd {
        /* @var $facturaDAO FacturaDAO */

        private $facturaDAO;
        /* @var $comprobante \com\softcoatl\cfdi\v40\schema\Comprobante40 */
        private $comprobante;
        /* @var $comprobanteTimbrado \com\softcoatl\cfdi\v40\schema\Comprobante40 */
        private $comprobanteTimbrado;
        private $xmlTimbrado;
        private $representacionImpresa;
        private $idFactura;

        function __construct($idFactura) {
            $this->idFactura = $idFactura;
            $this->facturaDAO = new FacturaLcdDAO($idFactura);
            $this->comprobante = $this->facturaDAO->getComprobante();
        }

//constructor

        /**
         * 
         * @return \cfdi33\Comprobante
         */
        function getComprobante() {
            return $this->comprobante;
        }

        function setComprobante($comprobante) {
            $this->comprobante = $comprobante;
        }

        function setComprobanteTimbrado($comprobanteTimbrado) {
            $this->comprobanteTimbrado = $comprobanteTimbrado;
        }

        function setRepresentacionImpresa($representacionImpresa) {
            $this->representacionImpresa = $representacionImpresa;
        }

        function setXmlTimbrado($xmlTimbrado) {
            $this->xmlTimbrado = $xmlTimbrado;
        }

        function update() {
            $this->facturaDAO->updateFC($this->idFactura, $this->comprobanteTimbrado->getTimbreFiscalDigital()->getUUID());
        }

        function save($clavePAC) {
            $this->facturaDAO->insertFactura($this->comprobanteTimbrado, $clavePAC);
        }

        function cancel($id, $acuse) {
            $this->facturaDAO->cancelFC($id, $acuse);
        }

    }

    //FacturaDetisa
}//com\detisa\omicrom