<?php

require_once ("pdf/PDFTransformer.php");
require_once ("pdf/PDFTransformerRP.php");

/**
 * Description of PDFGenerator
 *
 * @author rolando
 */
class PDFGenerator {
   
    /**
     * 
     * @param \cfdi33\Comprobante $factura
     * @param int $tipo
     */
    public static function generate($factura, $tipo, $rfc, $addrEmisor = NULL, $addrReceptor = NULL) {

        error_log("Generando PDF para " . $factura->getTimbreFiscalDigital()->getUUID());
        if ($factura->getVersion()=="3.3") {
            switch ($tipo) {
            case 2:
                $cTipo = 'Recibo de Arrendamiento'; break;
            case 4:
                $cTipo = 'Nota de Crédito'; break;
            case 3:
                $cTipo = 'Recibo de Honorarios'; break;
            default:
                $cTipo = 'Factura'; break;
            }

            $logoName = "img/logo.png";

            return $tipo != 5 ? 
                    PDFTransformer::getPDF($factura, $cTipo, "S", file_get_contents($logoName), "./", $addrEmisor, $addrReceptor):
                    PDFTransformerRP::getPDF($factura, "Recibo de Pago", "S", file_get_contents($logoName), "./", $addrEmisor, $addrEmisor);
        }

        return false;
    }
}
