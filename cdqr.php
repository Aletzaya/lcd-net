<?php
//set it to writable location, a place for temp generated PNG files
//$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'codeqr/';

include "phpqrcode/qrlib.php";    

//ofcourse we need rights to create temp dir
//if (!file_exists($PNG_TEMP_DIR))
 //   mkdir($PNG_TEMP_DIR);

$filename = $PNG_TEMP_DIR.'test.png';

$orden='1075810';
$cliente='329570';
$matrixPointSize = 5;
$errorCorrectionLevel = 'L';
$datos='https://lcd-system.com/lcd-net/entregaonline.php?Orden='.$orden.'&Cliente='.$cliente.'';

$filename = $PNG_WEB_DIR.$orden.'_'.$cliente.'.png';
QRcode::png($datos, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 

echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
?>