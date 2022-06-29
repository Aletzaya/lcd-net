<?php

/*
 * ComprobanteDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

namespace com\detisa\omicrom {

    require_once ('mysqlUtils.php');
    require_once ('DocumentoCFDIDetifac.php');
    require_once ('lib/PDFGenerator.php');
    require_once ("cfdi/factory/ComprobanteFactory.php");
    require_once ('cfdi/com/softcoatl/cfdi/Comprobante.php');

    //require_once ('cfdi/com/softcoatl/cfdi/addenda/detisa/Observaciones.php');

    use com\softcoatl\cfdi\Comprobante;
    use com\detisa\cfdi\factory\ComprobanteFactoryIface;
    use com\detisa\cfdi\factory\ComprobanteFactory;

    class FacturaLcdDAO {

        private $folio;
        protected $tipo;
        /* @var $comprobante Comprobante */
        private $comprobante;
        /* @var $mysqlConnection \mysqli */
        private $mysqlConnection;
        protected $factory;
        private $xmlTimbrado;

        function __construct($folio) {

            error_log("***** Cargando CFDI con folio " . $folio . " F4.0  *****");

            $this->folio = $folio;
            $this->factory = ComprobanteFactory::getFactory($folio, "fc");
            $this->mysqlConnection = getConnection();
            $this->buildComprobante();
        }

        protected function buildComprobante() {
            $this->comprobante();
            $this->emisor();
            $this->receptor();
            //$this->cfdiRelacionados();
            $this->conceptos();
            $this->impuestos();
//                    ->observaciones();
        }

//constructor

        public function __destruct() {
            $this->mysqlConnection->close();
        }

        function setXml($xml) {
            $this->xml = $xml;
        }

        function setXmlTimbrado($xmlTimbrado) {
            $this->xmlTimbrado = $xmlTimbrado;
        }

        function getFolio() {
            return $this->folio;
        }

        function getComprobante() {
            return $this->comprobante;
        }

        function setFolio($folio) {
            $this->folio = $folio;
        }

        function setComprobante($comprobante) {
            $this->comprobante = $comprobante;
        }

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function comprobante() {

            $sql = "SELECT 
                    fc.id, 
                    DATE_FORMAT(fc.fecha, '%Y-%m-%dT%H:%h:%i') Fecha, 
                    fc.serie Serie, 
                    fc.folio Folio,
                    fc.formadepago FormaPago, 
                    fc.metododepago MetodoPago,
                    'MXN' Moneda,
                    'I' TipoDeComprobante,
                    '4.0' Version,
                    CAST( fc.total AS DECIMAL( 12, 2 ) )  Total,
                    CAST( fc.importe AS DECIMAL( 12, 2 ) ) SubTotal,
                    cia.codigo LugarExpedicion
                    FROM fc JOIN cia ON cia.id = 1
                    WHERE fc.id = " . $this->folio;

            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_array())) {
                $this->comprobante = $this->factory->createComprobante($rs);
            }
            return $this;
        }

//comprobante

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function emisor() {

            $sql = "SELECT UPPER( cia.nombre ) "
                    . "Nombre , rfc Rfc, clave_regimen RegimenFiscal FROM cia WHERE cia.id in (100,1)";
            error_log($sql);
            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {
                $this->comprobante->setEmisor($this->factory->createComprobanteEmisor($rs));
            }
            return $this;
        }

//retrieve

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function receptor() {

            $sql = "SELECT clif.nombre Nombre, clif.rfc Rfc, fc.usocfdi UsoCFDI,regimenFiscal RegimenFiscalReceptor,"
                    . "codigo DomicilioFiscalReceptor "
                    . " FROM fc JOIN clif ON fc.cliente = clif.id WHERE fc.id = " . $this->folio;

            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {
                $this->comprobante->setReceptor($this->factory->createComprobanteReceptor($rs));
            }
            return $this;
        }

//receptor

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function cfdiRelacionados() {

            $cfdiRelacionados = $this->factory->createComprobanteCfdiRelacionados();


            $sql = "
                SELECT F.id, IFNULL(F.tiporelacion,  '') tiporelacion, IFNULL(R.id,  '') rfolio, IFNULL(R.uuid,  '') ruuid
                FROM fc F
                LEFT JOIN fc R ON R.id = F.relacioncfdi
                WHERE F.id = " . $this->folio;

            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {
                $relacionados[] = $this->factory->createComprobanteCfdiRelacionadosCfdiRelacionado($rs);
                $cfdiRelacionados->setTipoRelacion($rs["tipo_relacion"]);
            }
            if (count($relacionados) > 0) {
                $cfdiRelacionados->setCfdiRelacionado($relacionados);
                $this->comprobante->setCfdiRelacionados([$cfdiRelacionados]);
            }
            return $this;
        }

//cfdiRelacionados

        /**
         * 
         * @throws \com\detisa\omicrom\Exception 
         */
        private function conceptos() {

            $conceptos = $this->factory->createComprobanteConceptos();
            $subTotal = 0.00;
            $sql = "SELECT fcd.estudio NoIdentificacion, 
                   est.descripcion Descripcion, 
                   est.inv_cunidad ClaveUnidad, 
                   est.inv_cproducto ClaveProdServ, 
                   fcd.cantidad Cantidad, 
                   round(fcd.importe,2) Importe, 
                   round(fcd.precio,2) ValorUnitario,
                   fcd.iva/100 base_iva,
                   ROUND( fcd.cantidad * fcd.precio * CAST( fcd.iva /100 AS DECIMAL( 10, 6 ) ), 2 ) tax_iva,
                   CAST( fcd.iva/100 AS DECIMAL( 10, 6 ) ) factoriva,
                   fcd.descuento/100 base_descuento                    
                   FROM fcd, est
                   WHERE fcd.id = " . $this->folio . " AND fcd.estudio = est.estudio";

            $total = 0.00;

            if (($query = $this->mysqlConnection->query($sql))) {

                while (($rs = $query->fetch_assoc())) {
                    $concepto = $this->factory->createComprobanteConceptosConcepto($rs);

                    $subTotal += round($rs['ValorUnitario'], 2);
                    $Total += round($rs['Importe'], 2);
                    $trasladados = array();
                    $retenidos = array();
                    if ($rs["tax_iva"] > 0) {
                        $Imp = number_format(round($rs['tax_iva'], 2), 2, '.', '');
                        $trasladados[] = $this->factory->createComprobanteConceptosConceptoImpuestosTrasladosTraslado([
                            "Base" => $rs["ValorUnitario"],
                            "Impuesto" => "002",
                            "TasaOCuota" => $rs["factoriva"],
                            "TipoFactor" => "Tasa",
                            "Importe" => $Imp]);
                    }
                    $Impuestos = $this->factory->createComprobanteConceptosConceptoImpuestos();

                    if (count($trasladados) > 0) {
                        $traslados = $this->factory->createComprobanteConceptosConceptoImpuestosTraslados();
                        $traslados->setTraslado($trasladados);
                        $Impuestos->setTraslados($traslados);
                    }
                    if (count($retenidos) > 0 || count($trasladados) > 0) {
                        $concepto->setImpuestos($Impuestos);
                    }

                    $conceptos->addConcepto($concepto);
                    $difference = round($this->comprobante->getTotal() - $Total, 2);
                    if (abs($difference) > 0.001) {
                        error_log("There is a difference " . $difference);
                        $this->comprobante->setTotal(number_format($Total, 2, ".", ""));
                        $this->comprobante->setSubTotal(number_format($subTotal, 2, ".", ""));
                    } else {
                        error_log("Equals totals");
                    }
                    $this->comprobante->setConceptos($conceptos);
                }//while
            }
            return $this;
        }

//conceptos

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        //Agrupa la suma de los impuestos por tasa
        private function impuestos() {
            $trasladados = array();
            $retenidos = array();

            $total_traslado = 0.00;
            $sql = "
                SELECT 
                    SUM(round(importe-precio,2)) ValorIva, precio,
                    CAST( fcd.iva /100 AS DECIMAL( 10, 6 ) ) factoriva,
                    SUM( ROUND( fcd.cantidad * fcd.precio * CAST( fcd.iva /100 AS DECIMAL( 10, 6 ) ), 2 ) ) tax_iva
                    FROM fcd, est
                    WHERE fcd.id = " . $this->folio . "
                    AND fcd.estudio = est.estudio
                    GROUP BY factoriva";
            $total_traslado = 0.00;

            if (($query = $this->mysqlConnection->query($sql))) {
                while (($rs = $query->fetch_assoc())) {
                    $trasladados[] = $this->factory->createComprobanteImpuestosTrasladosTraslado([
                        "Importe" => $rs["tax_iva"],
                        "Impuesto" => "002",
                        "TasaOCuota" => $rs["factoriva"],
                        "Base" => $rs["precio"],
                        "TipoFactor" => "Tasa"]);

                    $total_traslado += $rs["tax_iva"];
                }

                $impuestos = $this->factory->createComprobanteImpuestos();

                if (count($trasladados) > 0) {
                    $traslados = $this->factory->createComprobanteImpuestosTraslados();
                    $traslados->setTraslado($trasladados);
                    $impuestos->setTraslados($traslados);
                    $impuestos->setTotalImpuestosTrasladados(number_format($total_traslado, 2, ".", ""));
                }
                $this->comprobante->setImpuestos($impuestos);
                return $this;
            }
        }

//getImpuestosFactura

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function observaciones() {

            $observaciones = new Comprobante\addenda\Observaciones();
            $sql = "
                SELECT fc.observaciones, fcd.orden 
                FROM fc JOIN fcd ON fcd.id = fc.id
                WHERE fc.id = " . $this->folio . " ";

            $observacion = '';
            $tickets = '';
            $i = 0;
            if (($query = $this->mysqlConnection->query($sql))) {
                while (($rs = $query->fetch_assoc())) {
                    $observacion = $rs['observaciones'];
                    $tickets .= ($i++ > 0 ? ', ' : '') . $rs['orden'];
                }
                if ($observacion !== '') {
                    $observaciones->addObservaciones(new Comprobante\addenda\Observaciones\Observacion($observacion));
                }
                if ($tickets !== '') {
                    $observaciones->addObservaciones(new Comprobante\addenda\Observaciones\Observacion('* Correspondiente a la Orden : 
' . $tickets));
                }

                $this->comprobante->addAddenda($observaciones);
            } else {
                error_log($this->mysqlConnection->error);
            }
        }

        public function updateFC($id, $uuid) {

            $sql = "UPDATE fc SET uuid = '" . $uuid . "', status = 'Timbrada' WHERE fc.id = " . $id;
            error_log("UPDATEEEEEEE" . $sql);
            return $this->mysqlConnection->query($sql);
        }

        public function cancelFC($id, $acuse) {

            $sql = "UPDATE fc JOIN facturas ON fc.uuid = facturas.uuid SET fc.status = 'Cancelada', facturas.acuse_cancelacion = ? WHERE fc.id = ?";
            if (($ps = $this->mysqlConnection->prepare($sql)) && $ps->bind_param("si", $acuse, $id) && $ps->execute()) {
                return true;
            }
            return false;
        }

        public function updateRM($id, $uuid) {

            $sql = "UPDATE rm SET uuid = '" . $uuid . "' "
                    . "WHERE id IN (SELECT ticket FROM fcd JOIN inv ON inv.id = fcd.producto AND rubro = 'Combustible' WHERE fcd.id = " . $id . " AND ticket <> 0)";
            return $this->mysqlConnection->query($sql);
        }

        public function updateVTA($id, $uuid) {

            $sql = "UPDATE vtaditivos SET uuid = '" . $uuid . "' "
                    . "WHERE id IN (SELECT ticket FROM fcd JOIN inv ON inv.id = fcd.producto AND rubro = 'Aceites' WHERE fcd.id = " . $id . " AND ticket <> 0)";
            return $this->mysqlConnection->query($sql);
        }

        /**
         * 
         * @param Comprobante $Comprobante
         */
        public function insertFactura($Comprobante, $clavePAC) {

            $sql = "INSERT INTO facturas (cfdi_xml, pdf_format, fecha_emision, fecha_timbrado, clave_pac, id_fc_fk, emisor, receptor, uuid)"
                    . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            error_log("ERRRORRRR" . $sql);
            $pdf = \PDFGenerator::generate($Comprobante, 1, "");
            $DOM = $Comprobante->asXML();
            $xml = $DOM->saveXML();
            error_log("______________-------____________________");

            $stmt = $this->mysqlConnection->prepare($sql);
            error_log(print_r($stmt, true));
            if ($stmt) {
                $stmt->bind_param("sssssssss",
                        $xml,
                        $pdf,
                        $Comprobante->getFecha(),
                        $Comprobante->getTimbreFiscalDigital()->getFechaTimbrado(),
                        $clavePAC,
                        $this->folio,
                        $Comprobante->getEmisor()->getRfc(),
                        $Comprobante->getReceptor()->getRfc(),
                        $Comprobante->getTimbreFiscalDigital()->getUUID());
                if (!$stmt->execute()) {
                    error_log($this->mysqlConnection->error);
                }
            } else {
                error_log("Error insertando factura " . $this->mysqlConnection->error);
            }
        }

    }

    //FacturaDAO
}//com\detisa\omicrom
