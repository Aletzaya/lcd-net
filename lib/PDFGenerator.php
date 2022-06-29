<?php

require_once ("cfdi/pdf/PDFTransformer.php");
//require_once ("pdf/PDFTransformerRP.php");

/**
 * Description of PDFGenerator
 *
 * @author rolando
 */
class PDFGenerator {

    private static function getTipo($tipo) {
        switch ($tipo) {
            case 2: return "Recibo de Arrendamiento";
            case 3: return "Recibo de Honorarios";
            case 4: return "Nota de Crédito";
            case 5: return "Recibo de Pagos";
            case 7: return "Carta Porte";
        }
        return "Factura";
    }

    public static function generate($factura, $tipo, $rfc, $addrEmisor = NULL, $addrReceptor = NULL) {

        if ($factura->getVersion() !== "3.2") {
            error_log("Generando PDF para " . $factura->getTimbreFiscalDigital()->getUUID());
            return (new PDFTransformer())->getPDF($factura, self::getTipo($tipo), "S", '', "./", $addrEmisor, $addrReceptor);
        }

        return false;
    }

}
