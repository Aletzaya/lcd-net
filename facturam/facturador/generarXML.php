<?php
        //----------Rutas de los archivos necesarios----------
        $fileCer = "certificado/cer.pem"; //Certificado -> cer.pem
        $fileCad = "xslt/cadenaoriginal_3_3.xslt"; //Cadena original -> cadenaoriginal_3_3.xlst
        $fileKey = "certificado/key.pem"; //Key -> key.pem
        //----------------------------------------------------
        //Retiramos saltos de línea, retorno de carro y palabras no necesarias para el certificado
        $certificado = str_replace(array("\n","\r","-----BEGIN CERTIFICATE-----","-----END CERTIFICATE-----"),'',file_get_contents($fileCer));
        //Decodificamos el certificado para obtener el No. Certificado
        $certificadoB64 = base64_decode($certificado);
        //Tenemos que separar las cadenas porque aun mantiene basura
        $auxiliar = explode("*", $certificadoB64);
        //Obtenemos una subcadena desde 15 hasta 20
        $noCertificado = substr($auxiliar[0], 15, 20);
        //Cabeceras para el XML
        $xmlstr = '<?xml version="1.0" encoding="UTF-8"?>
        <cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" 
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
            Certificado="'.$certificado.'" 
            Fecha="2021-04-27T07:07:21" 
            Folio="46831" 
            FormaPago="28" 
            LugarExpedicion="56100" 
            MetodoPago="PUE" 
            Moneda="MXN" 
            NoCertificado="'.$noCertificado.'" 
            Sello="[AQUI_VA_EL_SELLO]" 
            SubTotal="1108.96" 
            TipoCambio="1" 
            TipoDeComprobante="I" 
            Total="1286.40" 
            Version="3.3" 
            xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"
        >
        <cfdi:Emisor 
            Nombre="Laboratorio Clinico Duran S.A. de C.V." 
            RegimenFiscal="601" 
            Rfc="LCD960909TW5" 
        />
        <cfdi:Receptor 
            Nombre="CARLOS JAIR CHAVEZ SALVADOR" 
            Rfc="CASC9906075F2" 
            UsoCFDI="D01" 
        />
        <cfdi:Conceptos>
            <cfdi:Concepto 
                Cantidad="1.0000" 
                ClaveProdServ="85121800" 
                ClaveUnidad="E48" 
                Descripcion="EXAMEN GENERAL DE ORINA" 
                Importe="125.00" 
                NoIdentificacion="EGO" 
                ValorUnitario="125.0000"
            >
                <cfdi:Impuestos>
                    <cfdi:Traslados>
                        <cfdi:Traslado 
                            Base="125.00" 
                            Importe="20.00" 
                            Impuesto="002" 
                            TasaOCuota="0.160000" 
                            TipoFactor="Tasa" 
                        />
                    </cfdi:Traslados>
                </cfdi:Impuestos>
            </cfdi:Concepto>
            <cfdi:Concepto 
                Cantidad="1.0000" 
                ClaveProdServ="85121800" 
                ClaveUnidad="E48" 
                Descripcion="HEMOGLOBINA GLICOSILADA A1C" 
                Importe="82.24" 
                NoIdentificacion="HBG1C" 
                ValorUnitario="82.2400"
            >
                <cfdi:Impuestos>
                    <cfdi:Traslados>
                        <cfdi:Traslado 
                            Base="82.24" 
                            Importe="13.16" 
                            Impuesto="002" 
                            TasaOCuota="0.160000" 
                            TipoFactor="Tasa" 
                        />
                    </cfdi:Traslados>
                </cfdi:Impuestos>
            </cfdi:Concepto>
            <cfdi:Concepto 
                Cantidad="1.0000" 
                ClaveProdServ="85121800" 
                ClaveUnidad="E48" 
                Descripcion="BIOMETRIA HEMATICA" 
                Importe="159.48" 
                NoIdentificacion="BH" 
                ValorUnitario="159.4800"
                >
                    <cfdi:Impuestos>
                        <cfdi:Traslados>
                            <cfdi:Traslado 
                                Base="159.48" 
                                Importe="25.52" 
                                Impuesto="002" 
                                TasaOCuota="0.160000" 
                                TipoFactor="Tasa" 
                            />
                        </cfdi:Traslados>
                        </cfdi:Impuestos>
                        </cfdi:Concepto>
                        <cfdi:Concepto 
                            Cantidad="1.0000" 
                            ClaveProdServ="85121801" 
                            ClaveUnidad="E48" 
                            Descripcion="PERFIL BIOQUIMICO 27" 
                            Importe="742.24" 
                            NoIdentificacion="PBIO27" 
                            ValorUnitario="742.2400"
                        >
                        <cfdi:Impuestos>
                        <cfdi:Traslados>
                        <cfdi:Traslado 
                            Base="742.24" 
                            Importe="118.76" 
                            Impuesto="002" 
                            TasaOCuota="0.160000" 
                            TipoFactor="Tasa" 
                        />
                        </cfdi:Traslados>
                        </cfdi:Impuestos>
                        </cfdi:Concepto>
                        </cfdi:Conceptos>
                        <cfdi:Impuestos TotalImpuestosTrasladados="177.44">
            <cfdi:Traslados>
                <cfdi:Traslado 
                        Importe="177.44" 
                        Impuesto="002" 
                        TasaOCuota="0.160000"
                        TipoFactor="Tasa" 
                    />
                </cfdi:Traslados>
            </cfdi:Impuestos>
        </cfdi:Comprobante>';

        //Convertimos toda la cadena a XML 
        $paso = simplexml_load_string($xmlstr);
        //Generamos un DOMDocument (XML) para poder manipular el XML CFDI 
        $xsl = new DOMDocument("1.0","UTF-8");
        //Cargamos el archivo de la cadena original
        $xsl->load($fileCad);
        //Creamos una variable tipo XSLTProccesor
        $proc = new XSLTProcessor;
        //Cargamos la hoja de estilo $xsl al $proc
        $proc->importStyleSheet($xsl);
        //Tranformamos a XML el CFDI para obtener la cadena original
        $cadenaOriginal = $proc->transformToXML($paso);
        //Obtenemos la llave del archivo key.pem
        $private = openssl_pkey_get_private(file_get_contents($fileKey));
        //Creamos una variable vacia que guardara el sello
        $sig = "";
        //Generamos el sello con la cadena original y el archivo key
        openssl_sign($cadenaOriginal, $sig, $private, OPENSSL_ALGO_SHA256);
        //codificamos el sello a base64
        $sello = base64_encode($sig);
        //echo $sello;
        //Reemplazamos para poder colocar el sello generado
        $xmlstr = str_replace("[AQUI_VA_EL_SELLO]",$sello,$xmlstr);
        echo $xmlstr;
        //Creamos un DOMDocument para poder guardar el XML en un archivo
        //$doc = new DOMDocument();
        //Cargamos la cadena en la variable $doc
        //$doc->loadXML($xmlstr);
        //guardamos todo lo que contenga $doc antes de generar el archivo final
        //$doc->saveXML();
        //Generamos el archivo final colocandole un nombre de ejemplo
        //$doc->save('Ejemplo.xml');
        //echo $xmlstr;
        //$fname = 'A95CD91C-2660-4D0A-87AA-C65032C2DA06.xml'; //"D4E84085-B52F-43B4-A725-58277EB3CDA7.xml";
        //$fname = "Ejemplo.xml";
        //if(!file_exists($fname)){
         //die(PHP_EOL . "File not found" . PHP_EOL . PHP_EOL);
        //}
  
        //$handle = fopen($fname, "r");
        //$sData = '';

        $usuario = "test";
        $password = "TEST";

        //while(!feof($handle))
            //$sData .= fread($handle, filesize($fname));
        //fclose($handle);
        //$b64 = base64_encode($sData);
        
        $response = '';
        try {
                $client = new SoapClient("https://timbradopruebas.stagefacturador.com/timbrado.asmx?WSDL");
                $params = array('Usuario' => $usuario, 'password' => $password, 'CFDIcliente'=>$xmlstr);
                $response = $client->__soapCall('obtenerTimbrado', array('parameters' => $params));
        } catch (SoapFault $fault) { 
                echo "SOAPFault: ".$fault->faultcode."-".$fault->faultstring."\n";
        }
      
        var_dump($response);
        //echo ($response->obtenerTimbradoResult->any);
?>