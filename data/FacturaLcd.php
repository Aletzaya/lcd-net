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

    require_once ('cfdi33/Comprobante.php');
    require_once ('FacturaDAO.php');
    require_once ('mysqlUtils.php');

    class FacturaDetisa {
        /* @var $facturaDAO FacturaDAO */

        private $facturaDAO;
        /* @var $comprobante \cfdi33\Comprobante */
        private $comprobante;
        /* @var $comprobanteTimbrado \cfdi33\Comprobante */
        private $comprobanteTimbrado;

        function __construct($idFactura) {

            $this->facturaDAO = new FacturaDAO($idFactura);
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

        function update() {

            $this->facturaDAO->updateFC($this->comprobanteTimbrado->getFolio(), $this->comprobanteTimbrado->getTimbreFiscalDigital()->getUUID());
            $this->facturaDAO->updateRM($this->comprobanteTimbrado->getFolio(), $this->comprobanteTimbrado->getTimbreFiscalDigital()->getUUID());
            $this->facturaDAO->updateVTA($this->comprobanteTimbrado->getFolio(), $this->comprobanteTimbrado->getTimbreFiscalDigital()->getUUID());
        }

        function save($clavePAC) {
            error_log("Comprobante Timbrado " . print_r($this->comprobanteTimbrado, TRUE));
            trigger_error("Comprobante Timbrado " . print_r($this->comprobanteTimbrado, TRUE));
            $this->facturaDAO->insertFactura($this->comprobanteTimbrado, $clavePAC);
        }

        function cancel($id, $acuse) {
            $this->facturaDAO->cancelFC($id, $acuse);
        }

    }

    //FacturaDetisa
}//com\detisa\omicrom