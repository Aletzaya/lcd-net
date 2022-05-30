<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=strtoupper ($check['uname']);

  $Team=$check['team'];

  $Titulo="Datos de Institucion";

  $link=conectarse();

  $tamPag=10;

  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30

//$htmlcontent = getHeadNews($db);

$doc_title    = "Institucion";
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

if($Team=='2'){
  $Team='LCD - OHF';
  $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 320, Texcoco Mexico, Col. Centro 56100.                            Tel. (01595) 95 4 45 86');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='3'){
    $Team='LCD - TPX';
  $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Av Morelos No. 3, Tepexpan Acolman, Col. Centro 55885.                                          Tel. (01594) 95 7 42 97,  95 7 38 82');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='4'){
  $Team='LCD - RYS';
  $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz Col. Centro 56400.             Tel. (01 55) 58 55 60 24, 58 58 43 07');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='5'){
  $Team='LCD - CAM';
  $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Guanabana No. 35, Esq. Calz. Camarones,Col. Nueva Santa Maria CP 02800             Tels: 53 41 66 50 - 53 42 48 77');//define ("PDF_HEADER_LOGO", "logo_example.png");
}else{
  $Team='LCD - Matriz';
  $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 108, Texcoco Mexico, Col. Centro 56100.                                 Tel. (01595) 95 4 11 40, 95 4 62 96');//define ("PDF_HEADER_LOGO", "logo_example.png");
}

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Estudio");

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

$reg = mysql_fetch_array(mysql_query("SELECT * FROM inst WHERE inst.institucion='$busca'"));

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Datos Basicos</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> Num: </b> '. $reg[institucion].'</td><td width="700" align="center"><b> Razon Social: </b>'. $reg[nombre].'</td><td width="300" align="right"><b> Alias: </b>'. $reg[alias].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1000" line-height:"50%";><b>Direccion: </b> '. $reg[direccion].', '. $reg[localidad].', '. $reg[municipio].'. <b> Codigo Postal: </b>'. $reg[codigo].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%";><b> Telefono: </b> '. $reg[telefono].'</td><td width="300" align="center"><b> Director: </b>'. $reg[director].'</td><td width="300" align="right"><b> Subdirector: </b>'. $reg[subdirector].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="300" line-height:"50%";><b> E-mail: </b> '. $reg[mail].'</td><td width="400" align="center"><b> Referencia: </b>'. $reg[referencia].'</td><td width="200" align="right"></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Datos Institucionales</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="300" line-height:"50%";><b> Lista de Precios: </b> '. $reg[lista].'</td><td width="400" align="left"><b> Envio de resultados por e-mail: </b>'. $reg[enviomail].'</td><td width="300" align="left"><b> Promotor Asignado: </b>'. $reg[promotorasig].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="300" line-height:"50%";><b> Otro dato: </b> '. $reg[otro].'</td><td width="400" align="left"><b> Todos los estudios: </b>'. $reg[todo].'</td><td width="300" align="left"><b> Envio de resultados: </b>'. $reg[envio].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $servicio=$reg[servicio];
 $servicio=nl2br($servicio); //respeta salto de linea
 $servicio=ucfirst($servicio); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" line-height:"50%";><b> Caracteristicas del servicio: </b></td><td width="850" style="text-align:justify;">'.utf8_encode($servicio).'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $msjadministrativo=$reg[msjadministrativo];
 $msjadministrativo=nl2br($msjadministrativo); //respeta salto de linea
 $msjadministrativo=ucfirst($msjadministrativo); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" line-height:"50%";><b> Mensaje Administrativo: </b></td><td width="850" style="text-align:justify;">'.utf8_encode($msjadministrativo).'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Datos de cobranza</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="300" line-height:"50%";><b>   Condiciones de pago: </b> '. $reg[condiciones].'</td><td width="400" align="left"><b> Descuento Institucional: </b>'. $reg[descuento].' % </td><td width="300" align="left"><b> Encargado de la Institucion: </b>'. $reg[encargado].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="300" line-height:"50%";><b> Contacto: </b> '. $reg[contacto].'</td><td width="400" align="left"><b> Telefono de contacto: </b>'. $reg[telcontacto].'</td><td width="300" align="left"><b> E-mail del Contacto: </b>'. $reg[mailcontacto].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="300" line-height:"50%";><b> RFC: </b> '. $reg[rfc].'</td><td width="400" align="left"></td><td width="300" align="left"></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $responsable=$reg[responsable];
 $responsable=nl2br($responsable); //respeta salto de linea
 $responsable=ucfirst($responsable); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" line-height:"50%";><b> Responsable de cobranza: </b></td><td width="850" style="text-align:justify;">'.utf8_encode($responsable).'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $administrativa=$reg[administrativa];
 $administrativa=nl2br($administrativa); //respeta salto de linea
 $administrativa=ucfirst($administrativa); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" line-height:"50%";><b> Caracteristicas Administrativas: </b></td><td width="850" style="text-align:justify;">'.utf8_encode($administrativa).'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Otros Datos</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

 $observaciones=$reg[observaciones];
 $observaciones=nl2br($observaciones); //respeta salto de linea
 $observaciones=ucfirst($observaciones); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" line-height:"50%";><b> Observaciones: </b></td><td width="850" style="text-align:justify;">'.utf8_encode($observaciones).'</td></tr></table><hr><br><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%";><b> Usuario Alta: </b> '. $reg[usralta].'</td><td width="400" align="right"><b> Fecha Alta: </b>'. $reg[fechalta].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%";><b> Usuario Modifica: </b> '. $reg[usrmod].'</td><td width="400" align="right"><b> Fecha Modifica: </b>'. $reg[fechamod].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

ob_end_clean();

$pdf->Output();

mysql_close();
?>
