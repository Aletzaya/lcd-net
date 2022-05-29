<?php
  
$fname = "0000D33C-C2F3-42AC-BB4F-0C971DF830E7.xml";
if(!file_exists($fname)){
 die(PHP_EOL . "File not found" . PHP_EOL . PHP_EOL);
}
  
$handle = fopen($fname, "r");
$sData = '';
$usuario = 'test';//"testing@solucionfactible.com";
$password = 'TEST';//"timbrado.SF.16672";
  
while(!feof($handle))
    $sData .= fread($handle, filesize($fname));
fclose($handle);
$b64 = base64_encode($sData);
  
$response = '';

try {
        $client = new SoapClient("https://timbradopruebas.stagefacturador.com/timbrado.asmx?WSDL");
        $params = array('Usuario' => $usuario, 'password' => $password, 'CFDIcliente'=>$b64); //, 'zip'=>False);
        $response = $client->__soapCall('obtenerTimbrado', array('parameters' => $params));
} catch (SoapFault $fault) { 
        echo "SOAPFault: ".$fault->faultcode."-".$fault->faultstring."\n";
}       
        var_dump($response);
        print_r("\n".$response->cancelarComprobanteResult->any);//->obtenerTimbradoResult;
       
?>