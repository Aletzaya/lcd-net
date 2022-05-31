<?php
/*
 * ValidadorCFDI
 * cfdi®
 * © 2018, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since dic 2017
 */
namespace com\softcoatl\cfdi;

class ValidadorCFDI {

    public static function validate($xml, $schema) {
        libxml_use_internal_errors(true);

        $errorString = "";
        $dom = new \DOMDocument("1.0", "UTF-8");
        $dom->loadXML($xml);

        // La validación no considera las addendas
        $nodes = $dom->documentElement->childNodes;
        for ($i = 0; $i < $nodes->length; $i++) {
            if ("cfdi:Addenda" == $nodes->item($i)->nodeName) {
                $node = $nodes->item($i);
                $node->parentNode->removeChild($node);
            }
        }

        if(!$dom->schemaValidate($schema)) {
            $errors = libxml_get_errors();
            foreach ($errors as $key => $error) {
                $errorString .= $error->message;
                $errorString .= " ";
            }
            return $errorString;
        }
        error_log("Datos válidos");
        return true;
    }
}
