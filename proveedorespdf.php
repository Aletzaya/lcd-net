<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];
  $Team=$check['team'];

  $Titulo="Datos de Proveedor";

  $link=conectarse();

  $tamPag=10;

  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30

//$htmlcontent = getHeadNews($db);

$doc_title    = "Proveedores";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
//require_once('tcpdf2/tcpdf_include.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true,'UTF-8',false);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "A4");

$Team='LCD - Matriz';
$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','                                                                                                                                                 Catalogo de Proveedores');//define ("PDF_HEADER_LOGO", "logo_example.png");


//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Proveedor");

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

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

$reg = mysql_fetch_array(mysql_query("SELECT * FROM prv WHERE prv.id='$busca'"));

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Datos Basicos</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%";><b> Cuenta: </b> '. $reg[id].'</td><td width="600" align="center"><b> Nombre: </b>'. $reg[nombre].'</td><td width="330" align="right"><b> Alias: </b>'. $reg[alias].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1100" line-height:"50%";><b>Direccion: </b> '. $reg[direccion].', '. $reg[colonia].', '. $reg[ciudad].'. <b> Codigo Postal: </b>'. $reg[codigo].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="700" line-height:"50%";><b> Telefono: </b> '. $reg[telefono].'</td><td width="430" align="right"><b> Contacto: </b>'. $reg[nota].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%";><b> Dias de Credito: </b> '. $reg[dias].'</td><td width="300" line-height:"50%";><b> Responsable de proveedor: </b> '. $reg[respprv].'</td></tr></table><hr><br><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%";><b> Usuario Alta: </b> '. $reg[usralta].'</td><td width="400" align="right"><b> Fecha Alta: </b>'. $reg[fechalta].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%";><b> Usuario Modifica: </b> '. $reg[usrmod].'</td><td width="400" align="right"><b> Fecha Modifica: </b>'. $reg[fechamod].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

ob_end_clean();

$pdf->Output();

mysql_close();
?>

