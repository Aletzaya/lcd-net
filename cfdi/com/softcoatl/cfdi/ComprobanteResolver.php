<?php
namespace com\softcoatl\cfdi;

include_once ('com/softcoatl/cfdi/v33/schema/Comprobante33.php');
include_once ('com/softcoatl/cfdi/v40/schema/Comprobante40.php');
include_once ('Comprobante.php');

class ComprobanteResolver {

    public static function resolve($xml): Comprobante {
        $dom = new \DOMDocument("1.0", "UTF-8");
        $dom->loadXML($xml);
        $version = $dom->documentElement->getAttribute("Version");
        switch ($version) {
            case "4.0": return v40\schema\Comprobante40::parse($xml);
            default : return v33\schema\Comprobante33::parse($xml);
        }
    }
}
