<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=$check['uname'];
  $Team=$check['team'];

  $Titulo="Datos de Medico";

  $link=conectarse();

  $tamPag=10;

  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30

//$htmlcontent = getHeadNews($db);

$doc_title    = "Medico";
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
define ("PDF_HEADER_TITLE", "Medico");

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

$reg = mysql_fetch_array(mysql_query("SELECT * FROM med WHERE med.id='$busca'"));

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Datos Personales</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%";><b> Clave: </b> '. $reg[medico].'</td><td width="650" align="center"><b> Nombre: </b>'. $reg[nombrec].'</td><td width="300" align="left"><b> Fecha de nacimiento: </b>'. $reg[fechanac].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

//$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1000" line-height:"50%";><b>Direccion particular: </b> '. $reg[dirparticular].', '. $reg[locparticular].', '. $reg[munparticular].'.</td></tr></table><br>';

//$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> Tel. Particular: </b>'. $reg[telparticular].'</td><td width="380" align="left"><b> Tel. Celular: </b>'. $reg[telcelular].'</td><td width="380" align="left"></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> E-mail: </b> '. $reg[mail].'</td><td width="380" align="left"><b> R.F.C.: </b> '. $reg[rfc].'</td><td width="380" align="left"></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> Especialidad: </b> '. $reg[ especialidad].'</td><td width="380" align="left"><b> Subespecialidad: </b>'. $reg[subespecialidad].'</td><td width="380" align="left"><b> Cedula: </b>'. $reg[cedula].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Datos Institucionales</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');
//****************************************************************/////////

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1000" line-height:"50%";><b>Direccion del consultorio: </b> '. $reg[dirconsultorio].', '. $reg[locconsultorio].', '. $reg[munconsultorio].'. <b> Codigo Postal: </b>'. $reg[codigo].'</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> Tel. Consultorio: </b> '. $reg[telconsultorio].'</td><td width="380" align="left"></td><td width="380" align="left"></td></tr></table><br>';

$zonas = mysql_fetch_array(mysql_query("SELECT * FROM zns WHERE zns.zona='$reg[zona]'"));

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> Zona: </b> '. $zonas[descripcion].'</td><td width="380" align="left"><b> Envio de resultados por e-mail: </b>'. $reg[enviomail].'</td><td width="380" align="left"><b> Promotor Asignado: </b>'. $reg[promotorasig].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> Tel. Institucion: </b> '. $reg[telinstitucion].'</td><td width="380" align="left"><b> Dias de consulta: </b>'. $reg[diasconsulta].'</td><td width="380" align="left"><b> Hras.de consulta: </b>'. $reg[hraconsulta].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> Hras.de visita: </b> '. $reg[hravisita].'</td><td width="380" align="left"><b> % Comision: </b>'. $reg[comision].'</td><td width="380" align="left"><b> Clasificacion: </b>'. $reg[clasificacion].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$Inst = mysql_fetch_array(mysql_query("SELECT * FROM inst WHERE inst.institucion='$reg[institucion]'"));
$Instp = mysql_fetch_array(mysql_query("SELECT * FROM inst WHERE inst.institucion='$reg[institucionp]'"));
$Ruta = mysql_fetch_array(mysql_query("SELECT * FROM ruta WHERE ruta.id='$reg[ruta]'"));

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="380" line-height:"50%";><b> Inst.: </b> '. $Inst[nombre].'</td><td width="380" align="left"><b> Inst. de pago: </b>'. $Instp[nombre].'</td><td width="380" align="left"><b> Ruta: </b>'. $Ruta[descripcion].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $servicio=$reg[servicio];
 $servicio=nl2br($servicio); //respeta salto de linea
 $servicio=ucfirst($servicio); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" line-height:"50%";><b> Caracteristicas del servicio: </b></td><td width="850" style="text-align:justify;">'.utf8_encode($servicio).'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $refubicacion=$reg[refubicacion];
 $refubicacion=nl2br($refubicacion); //respeta salto de linea
 $refubicacion=ucfirst($refubicacion); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" line-height:"50%";><b> Referencia de Ubicacion: </b></td><td width="850" style="text-align:justify;">'.utf8_encode($refubicacion).'</td></tr></table><hr><br>';

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

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="300" line-height:"50%";><b> Status: </b> '. $reg[status].'</td><td width="400" align="left"></td><td width="300" align="left"></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%";><b> Usuario Alta: </b> '. $reg[usr].'</td><td width="400" align="right"><b> Fecha Alta: </b>'. $reg[fecha].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%";><b> Usuario Modifica: </b> '. $reg[usrmod].'</td><td width="400" align="right"><b> Fecha Modifica: </b>'. $reg[fecmod].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

ob_end_clean();

$pdf->Output();

mysql_close();
?>

