<?php
 require("../lib/lib.php");  $link=conectarse();
  
	$sql = "SELECT credencial, nombre FROM emp";
	$result = mysql_query($sql, $link);



//$htmlcontent = getHeadNews($db);

$doc_title = "Test para Froy";
$doc_subject = "Prueba de impresion unicode";
$doc_keywords = "keywords para la busqueda en el PDF";



require_once('config/lang/eng.php');
require_once('tcpdf.php');

//create new PDF document (document units are set by default to millimeters)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->setLanguageArray($l); //set language items

//initialize document
$pdf->AliasNbPages();

$pdf->AddPage();

// set barcode
$pdf->SetBarcode(date("Y-m-d H:i:s", time()));

// output some HTML code



while ( $row = mysql_fetch_array($result) ) {
	$pdf->writeHTML('No.cuenta: '.$row[0].' Nombre! '.$row[1], true, 0);
}



// output some content
$pdf->SetFont("vera", "BI", 20);
//$pdf->Cell(0,10,"TEST Bold-Italic Cell",1,1,'C');

// output some UTF-8 test content
//$pdf->AddPage();
$pdf->SetFont("FreeSerif", "", 12);
//$utf8text = file_get_contents("utf8test.txt", false); // get utf-8 text form file
//$pdf->SetFillColor(230, 240, 255, true);
$pdf->Write(5,$utf8text, '', 1);


//Close and output PDF document
$pdf->Output();

//============================================================+
// END OF FILE                                                 
//============================================================+


?>