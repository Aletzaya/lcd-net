<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=$check['uname'];
  $Team=$check['team'];

  $Titulo="Datos de Estudio";

  $link=conectarse();

  $tamPag=10;

  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30

//$htmlcontent = getHeadNews($db);

$doc_title    = "Estudio";
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

if($Team=='1' or $Team=='0'){
    $Team='LCD - Matriz';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 108, Texcoco Mexico, Col. Centro 56100.                                 Tel. 59 59 54 11 40, 59 59 54 62 96');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='2'){
    $Team='LCD - OHF';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 320, Texcoco Mexico, Col. Centro 56100.                            Tel. 59 59 54 45 86');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='3'){
      $Team='LCD - TPX';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Av Morelos No. 3, Tepexpan Acolman, Col. Centro 55885.                                          Tel. 59 49 57 42 97,  59 49 57 38 82');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='4'){
    $Team='LCD - RYS';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz Col. Centro 56400.             Tel. 55 58 55 60 24, 58 58 43 07');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='5'){
    $Team='LCD - CAM';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Guanabana No. 35, Esq. Calz. Camarones,Col. Nueva Santa Maria CP 02800             Tels: 53 41 66 50 - 53 42 48 77');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='6'){
    $Team='LCD - SVC';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Carr. Federal Mexico-Texcoco km 28, Col. Revolucion, Chicoloapan, C.P 56390.           Tel. 55 58 86 36 13 - 55 28 39 46 67');//define ("PDF_HEADER_LOGO", "logo_example.png");
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

$reg=mysql_fetch_array(mysql_query("SELECT * from est where est.estudio='$busca'",$link));   

$Dep=mysql_fetch_array(mysql_query("SELECT * FROM dep WHERE dep.departamento=$reg[depto]"));

$result=mysql_query("SELECT descripcion FROM ests WHERE estudio='$busca'");

$producto=mysql_query("SELECT estd.estudio,estd.producto,estd.cantidad,invl.descripcion,invl.clave,estd.suc FROM estd,invl WHERE estd.estudio='$busca' and estd.producto=invl.clave order by estd.suc");

$proc_a_realizar=mysql_query("SELECT * FROM proc_a_realizar WHERE estudio='$busca'");

$contenido=mysql_query("SELECT conest.id,conest.estudio,conest.descripcion FROM conest WHERE conest.estudio='$busca'");

$proestex=mysql_fetch_array(mysql_query("SELECT * FROM procestex WHERE estudio='$busca' limit 1"));

$proestpx=mysql_fetch_array(mysql_query("SELECT * FROM procestep WHERE estudio='$busca' limit 1"));

$proesthf=mysql_fetch_array(mysql_query("SELECT * FROM proceshf WHERE estudio='$busca' limit 1"));

$proestrys=mysql_fetch_array(mysql_query("SELECT * FROM procesrys WHERE estudio='$busca' limit 1"));

$proestcam=mysql_fetch_array(mysql_query("SELECT * FROM procescam WHERE estudio='$busca' limit 1"));

$proestsvc=mysql_fetch_array(mysql_query("SELECT * FROM processvc WHERE estudio='$busca' limit 1"));


$lBd=false;


/*** B A S I C O S ***/

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Datos Basicos</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%";><b> Clave: </b> '. $reg[estudio].'</td><td width="500" align="center"><b> Descripción: </b>'. $reg[descripcion].'</td><td width="300" align="right"><b> Clave Alt: </b>'. $reg[clavealt].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="500" line-height:"50%";><b>Departamento: </b> '. $Dep[nombre].'</td><td width="500" align="left"><b> Subdepartamento: </b>'. $reg[subdepto].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="500" line-height:"50%";><b>Sinónimos: </b></td></tr></table>';

$pdf->writeHTML($html,false,false,true,false,'');

while ($row=mysql_fetch_array($result)){

    $html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="140" line-height:"50%";></td><td width="500" align="left">'. $row[0].'</td></tr></table><br>';

    $pdf->writeHTML($html,false,false,true,false,'');

}

if($reg[activo]=='Si'){ $statusa='Activo'; }else{ $statusa='Inactivo'; }

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="500" line-height:"50%";><b>Status: </b> '. $statusa.'</td><td width="350"><b>Tipo de estudio: </b> '. $reg[base].'</td><td width="300"><b>Consentim. Informado: </b> '. $reg[consentimiento].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');


/*** E Q U I P O S ***/

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Equipos por Unidad </td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4"><b> LCD - TX </b></td><td width="346" align="center" bgcolor="#cfecb0"><b> LCD - TPX </b></td><td width="346" align="center" bgcolor="#b8b6f0"><b> LCD - HF </b></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Equipo: </b></td><td width="346" align="center" bgcolor="#cbd6f0">'.$proestex[equipo].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestpx[equipo].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proesthf[equipo].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Técnica: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestex[tecnica].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestpx[tecnica].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proesthf[tecnica].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Externo 1: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestex[maquila1].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestpx[maquila1].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proesthf[maquila1].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Externo 2: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestex[maquila2].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestpx[maquila2].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proesthf[maquila2].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Externo 3: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestex[maquila3].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestpx[maquila3].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proesthf[maquila3].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');


if($proestex[mixtolcd]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imglcdtex='TEX <img src="images/OKShield.png"/>';
}else{
  $imglcdtex='';
}

if($proestpx[mixtolcd]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imglcdtpx='TEX <img src="images/OKShield.png"/>';
}else{
  $imglcdtpx='';
}

if($proesthf[mixtolcd]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imglcdhf='TEX <img src="images/OKShield.png"/>';
}else{
  $imglcdhf='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Mixto: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imglcdtex.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imglcdtpx.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imglcdhf.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proestex[mixtotpx]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgtpxtex='TPX <img src="images/OKShield.png"/>';
}else{
  $imgtpxtex='';
}

if($proestpx[mixtotpx]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgtpxtpx='TPX <img src="images/OKShield.png"/>';
}else{
  $imgtpxtpx='';
}

if($proesthf[mixtotpx]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgtpxhf='TPX <img src="images/OKShield.png"/>';
}else{
  $imtpxdhf='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imgtpxtex.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imgtpxtpx.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imgtpxhf.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proestex[mixtohf]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imghftex='HF <img src="images/OKShield.png"/>';
}else{
  $imghftex='';
}

if($proestpx[mixtohf]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imghftpx='HF <img src="images/OKShield.png"/>';
}else{
  $imghftpx='';
}

if($proesthf[mixtohf]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imghfhf='HF <img src="images/OKShield.png"/>';
}else{
  $imghfhf='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imghftex.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imghftpx.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imghfhf.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proestex[mixtorys]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgrystex='RYS <img src="images/OKShield.png"/>';
}else{
  $imgrystex='';
}

if($proestpx[mixtorys]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgrystpx='RYS <img src="images/OKShield.png"/>';
}else{
  $imgrystpx='';
}

if($proesthf[mixtorys]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgryshf='RYS <img src="images/OKShield.png"/>';
}else{
  $imgryshf='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imgrystex.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imgrystpx.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imgryshf.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proestex[mixtomaq]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgexttex='EXT <img src="images/OKShield.png"/>';
}else{
  $imgexttex='';
}

if($proestpx[mixtomaq]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgexttpx='EXT <img src="images/OKShield.png"/>';
}else{
  $imgexttpx='';
}

if($proesthf[mixtomaq]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgexthf='EXT <img src="images/OKShield.png"/>';
}else{
  $imgexthf='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imgexttex.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imgexttpx.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imgexthf.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

//**************************************

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140"> </td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4"><b> LCD - RYS </b></td><td width="346" align="center" bgcolor="#cfecb0"><b> LCD - CAM </b></td><td width="346" align="center" bgcolor="#b8b6f0"><b> LCD - SVC </b></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Equipo: </b></td><td width="346" align="center" bgcolor="#cbd6f0">'.$proestrys[equipo].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestcam[equipo].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proestsvc[equipo].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Técnica: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestrys[tecnica].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestcam[tecnica].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proestsvc[tecnica].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Externo 1: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestrys[maquila1].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proescam[maquila1].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proessvc[maquila1].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Externo 2: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestrys[maquila2].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proescam[maquila2].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proessvc[maquila2].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Externo 3: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$proestrys[maquila3].'</td><td width="346" align="center" bgcolor="#cfecb0">'.$proestpx[maquila3].'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$proesthf[maquila3].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');


if($proesrys[mixtolcd]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imglcdrys='TEX <img src="images/OKShield.png"/>';
}else{
  $imglcdrys='';
}

if($proescam[mixtolcd]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imglcdcam='TEX <img src="images/OKShield.png"/>';
}else{
  $imglcdcam='';
}

if($proestsvc[mixtolcd]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imglcdsvc='TEX <img src="images/OKShield.png"/>';
}else{
  $imglcdsvc='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" bgcolor="#d0d3d4" line-height:"50%";><b> Mixto: </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imglcdrys.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imglcdcam.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imglcdsvc.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proesrys[mixtotpx]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgtpxrys='TPX <img src="images/OKShield.png"/>';
}else{
  $imgtpxrys='';
}

if($proescam[mixtotpx]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgtpxcam='TPX <img src="images/OKShield.png"/>';
}else{
  $imgtpxcam='';
}

if($proestsvc[mixtotpx]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgtpxsvc='TPX <img src="images/OKShield.png"/>';
}else{
  $imtpxdsvc='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imgtpxrys.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imgtpxcam.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imgtpxsvc.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proestrys[mixtohf]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imghfrys='HF <img src="images/OKShield.png"/>';
}else{
  $imghfrys='';
}

if($proestcam[mixtohf]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imghfrys='HF <img src="images/OKShield.png"/>';
}else{
  $imghfcam='';
}

if($proestsvc[mixtohf]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imghfsvc='HF <img src="images/OKShield.png"/>';
}else{
  $imghfsvc='';
}


$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imghfrys.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imghfrys.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imghfsvc.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proestrys[mixtorys]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgrysrys='RYS <img src="images/OKShield.png"/>';
}else{
  $imgrysrys='';
}

if($proestcam[mixtorys]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgryscam='RYS <img src="images/OKShield.png"/>';
}else{
  $imgryscam='';
}

if($proestsvc[mixtorys]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgryssvc='RYS <img src="images/OKShield.png"/>';
}else{
  $imgryssvc='';
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imgrysrys.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imgryscam.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imgryssvc.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($proestrys[mixtomaq]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgextrys='EXT <img src="images/OKShield.png"/>';
}else{
  $imgextrys='';
}

if($proestcam[mixtomaq]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgextcam='EXT <img src="images/OKShield.png"/>';
}else{
  $imgextcam='';
}

if($proestsvc[mixtomaq]=='1'){
 // $img='<img src="images/OKShield.png" width="420" height="270" />';
 $imgextsvc='EXT <img src="images/OKShield.png"/>';
}else{
  $imgextsvc='';
}


$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="100" line-height:"50%";><b> </b></td><td width="346" align="center" bgcolor="#cbd6f4">'.$imgextrys.'</td><td width="346" align="center" bgcolor="#cfecb0">'.$imgextcam.'</td><td width="346" align="center" bgcolor="#b8b6f0">'.$imgextsvc.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');



/*** M U E S T R A S ***/

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Muestras </td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b>Proceso a realizar:</b></td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

while ($row2=mysql_fetch_array($proc_a_realizar)){

    $html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";></td><td width="500" align="left">'. $row2[proceso].'</td></tr></table><br>';

    $pdf->writeHTML($html,false,false,true,false,'');

}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b>Realización:</b></td><td width="100" align="left">'. $reg[tiempoestd].' Dia(s)</td><td width="300" align="center">'. $reg[tiempoesth].' Hr(s).</td><td width="170" align="left"><b>Ordinaria:</b></td><td width="100" align="center">'. $reg[entord].' Dia(s)</td><td width="300" align="center">'. $reg[entordh].' Hr(s).</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b>Hospitalizado:</b></td><td width="100" align="left">'. $reg[enthosd].' Dia(s)</td><td width="300" align="center">'. $reg[enthos].' Hr(s).</td><td width="170" align="left"><b>Urgente:</b></td><td width="100" align="center">'. $reg[enturgd].' Dia(s)</td><td width="300" align="center">'. $reg[enturg].' Hr(s).</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$pdf->AddPage();

$html='<br><table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b>Producto:</b></td></tr></table>';

$pdf->writeHTML($html,false,false,true,false,'');

$encabezadosuc='';

while ($row3=mysql_fetch_array($producto)){

    $pdf->SetFont('Helvetica', '', 8, '', 'false');

    if($encabezadosuc<>$row3[suc]){

      if($row3[suc]=='1'){
          $encabezadosuc2='LCD-TX';
      }elseif($row3[suc]=='2'){
          $encabezadosuc2='LCD-TXP';
      }elseif($row3[suc]=='3'){
          $encabezadosuc2='LCD-OHF';
      }elseif($row3[suc]=='4'){
          $encabezadosuc2='LCD-RYS';
      }elseif($row3[suc]=='5'){
          $encabezadosuc2='LCD-CAM';
      }elseif($row3[suc]=='6'){
          $encabezadosuc2='LCD-SVC';
      }

      $html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1100" align="center" bgcolor="#cbd6f4"><b>Sucursal '. $encabezadosuc2.'</b></td></tr></table><br>';

      $pdf->writeHTML($html,false,false,true,false,'');

    }

    $pdf->SetFont('Helvetica', '', 7, '', 'false');

    $html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";></td><td width="200" align="left" bgcolor="#cfecb0">'. $row3[producto].'</td><td width="500" align="left" bgcolor="#cfecb0">'. $row3[descripcion].'</td><td width="100" align="center" bgcolor="#cfecb0">'. $row3[cantidad].'</td></tr></table><br>';

    $pdf->writeHTML($html,false,false,true,false,'');

    $encabezadosuc=$row3[suc];

}

 $producto_entregar=$reg[producto_entregar];
 $producto_entregar=nl2br($producto_entregar); //respeta salto de linea
 $producto_entregar=ucfirst($producto_entregar); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Productos a Entregar: </b></td><td width="950" style="text-align:justify;">'.$producto_entregar.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

if($reg[dobleinterpreta]=='S'){
  $doble_interpreta="2";
}elseif($reg[dobleinterpreta]=='N'){
  $doble_interpreta="1";
}else{
  $doble_interpreta="";
}

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b>Interpretaciones:</b></td><td width="100" align="left">'. $doble_interpreta.'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  C O N T E N I D O  ***/

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Contenido </td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b>Contenido de Est:</b></td></tr></table>';

$pdf->writeHTML($html,false,false,true,false,'');

while ($row4=mysql_fetch_array($contenido)){

    $html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";></td><td width="500" align="left">'. $row4[descripcion].'</td></tr></table><br>';

    $pdf->writeHTML($html,false,false,true,false,'');

}

/***  G E N E R A L  ***/

$pdf->AddPage();

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Descripción General </td></tr></table>';

 $objetivo=$reg[objetivo];
 $objetivo=nl2br($objetivo); //respeta salto de linea
 $objetivo=ucfirst($objetivo); //Coloca la primer letra en mayuscula en un parrafo

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Objetivo: </b></td><td width="950" style="text-align:justify;">'.$objetivo.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $condiciones=$reg[condiciones];
 $condiciones=nl2br($condiciones); //respeta salto de linea
 $condiciones=ucfirst($condiciones); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Condiciones: </b></td><td width="950" style="text-align:justify;">'.$condiciones.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $contenido=$reg[contenido];
 $contenido=nl2br($contenido); //respeta salto de linea
 $contenido=ucfirst($contenido); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Contenido: </b></td><td width="950" style="text-align:justify;">'.$contenido.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $observaciones=$reg[observaciones];
 $observaciones=nl2br($observaciones); //respeta salto de linea
 $observaciones=ucfirst($observaciones); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Observaciones: </b></td><td width="950" style="text-align:justify;">'.$observaciones.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $respradiologia=$reg[respradiologia];
 $respradiologia=nl2br($respradiologia); //respeta salto de linea
 $respradiologia=ucfirst($respradiologia); //Coloca la primer letra en mayuscula en un parrafo

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Posibles respuesta para radiología e imagen: </b></td><td width="950" style="text-align:justify;">'.$respradiologia.'</td></tr></table><br><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  A T E N C I O N    A    C L I E N T E S    /    P R O M O C I O N   ***/

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Atención a Clientes / Promoción </td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Ventajas Competitivas: </b></td><td width="950" style="text-align:justify;">'.$reg[ventajas].'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Promoción General / Estudios Sugeridos: </b></td><td width="950" style="text-align:justify;">'.$reg[promogral].'</td></tr></table><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  O T R A S   I N D I C A C I O N E S  T E X C O C O  ***/

$pdf->AddPage();

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Otras Indicaciones / Unidad Texcoco</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

 $estructura=$proestex[estructura];
 $estructura=nl2br($estructura); //respeta salto de linea
 $estructura=ucfirst($estructura); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Estructura a valorar: </b></td><td width="950" style="text-align:justify;">'.$estructura.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $matyeq=$proestex[matyeq];
 $matyeq=nl2br($matyeq); //respeta salto de linea
 $matyeq=ucfirst($matyeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Material Necesario: </b></td><td width="950" style="text-align:justify;">'.$matyeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $preparacion=$proestex[preparacion];
 $preparacion=nl2br($preparacion); //respeta salto de linea
 $preparacion=ucfirst($preparacion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Preparacion del paciente: </b></td><td width="950" style="text-align:justify;">'.$preparacion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $posicion=$proestex[posicion];
 $posicion=nl2br($posicion); //respeta salto de linea
 $posicion=ucfirst($posicion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Posicion del paciente: </b></td><td width="950" style="text-align:justify;">'.$posicion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $tecnicaeq=$proestex[tecnicaeq];
 $tecnicaeq=nl2br($tecnicaeq); //respeta salto de linea
 $tecnicaeq=ucfirst($tecnicaeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Tecnica Sugerida: </b></td><td width="950" style="text-align:justify;">'.$tecnicaeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $postadq=$proestex[postadq];
 $postadq=nl2br($postadq); //respeta salto de linea
 $postadq=ucfirst($postadq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Post adquisicion de estudio: </b></td><td width="950" style="text-align:justify;">'.$postadq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  O T R A S   I N D I C A C I O N E S  T E P E X P A N  ***/

$pdf->AddPage();

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Otras Indicaciones / Unidad Tepexpan</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

 $estructura=$proestpx[estructura];
 $estructura=nl2br($estructura); //respeta salto de linea
 $estructura=ucfirst($estructura); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Estructura a valorar: </b></td><td width="950" style="text-align:justify;">'.$estructura.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $matyeq=$proestpx[matyeq];
 $matyeq=nl2br($matyeq); //respeta salto de linea
 $matyeq=ucfirst($matyeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Material Necesario: </b></td><td width="950" style="text-align:justify;">'.$matyeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $preparacion=$proestpx[preparacion];
 $preparacion=nl2br($preparacion); //respeta salto de linea
 $preparacion=ucfirst($preparacion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Preparacion del paciente: </b></td><td width="950" style="text-align:justify;">'.$preparacion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $posicion=$proestpx[posicion];
 $posicion=nl2br($posicion); //respeta salto de linea
 $posicion=ucfirst($posicion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Posicion del paciente: </b></td><td width="950" style="text-align:justify;">'.$posicion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $tecnicaeq=$proestpx[tecnicaeq];
 $tecnicaeq=nl2br($tecnicaeq); //respeta salto de linea
 $tecnicaeq=ucfirst($tecnicaeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Tecnica Sugerida: </b></td><td width="950" style="text-align:justify;">'.$tecnicaeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $postadq=$proestpx[postadq];
 $postadq=nl2br($postadq); //respeta salto de linea
 $postadq=ucfirst($postadq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Post adquisicion de estudio: </b></td><td width="950" style="text-align:justify;">'.$postadq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  O T R A S   I N D I C A C I O N E S  F U T U R A  ***/

$pdf->AddPage();

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Otras Indicaciones / Unidad Hospital Futura</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

 $estructura=$proesthf[estructura];
 $estructura=nl2br($estructura); //respeta salto de linea
 $estructura=ucfirst($estructura); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Estructura a valorar: </b></td><td width="950" style="text-align:justify;">'.$estructura.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $matyeq=$proesthf[matyeq];
 $matyeq=nl2br($matyeq); //respeta salto de linea
 $matyeq=ucfirst($matyeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Material Necesario: </b></td><td width="950" style="text-align:justify;">'.$matyeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $preparacion=$proesthf[preparacion];
 $preparacion=nl2br($preparacion); //respeta salto de linea
 $preparacion=ucfirst($preparacion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Preparacion del paciente: </b></td><td width="950" style="text-align:justify;">'.$preparacion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $posicion=$proesthf[posicion];
 $posicion=nl2br($posicion); //respeta salto de linea
 $posicion=ucfirst($posicion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Posicion del paciente: </b></td><td width="950" style="text-align:justify;">'.$posicion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $tecnicaeq=$proesthf[tecnicaeq];
 $tecnicaeq=nl2br($tecnicaeq); //respeta salto de linea
 $tecnicaeq=ucfirst($tecnicaeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Tecnica Sugerida: </b></td><td width="950" style="text-align:justify;">'.$tecnicaeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $postadq=$proesthf[postadq];
 $postadq=nl2br($postadq); //respeta salto de linea
 $postadq=ucfirst($postadq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Post adquisicion de estudio: </b></td><td width="950" style="text-align:justify;">'.$postadq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  O T R A S   I N D I C A C I O N E S  L O S   R E Y E S   ***/

$pdf->AddPage();

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Otras Indicaciones / Unidad Los Reyes</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

 $estructura=$proestrys[estructura];
 $estructura=nl2br($estructura); //respeta salto de linea
 $estructura=ucfirst($estructura); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Estructura a valorar: </b></td><td width="950" style="text-align:justify;">'.$estructura.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $matyeq=$proestrys[matyeq];
 $matyeq=nl2br($matyeq); //respeta salto de linea
 $matyeq=ucfirst($matyeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Material Necesario: </b></td><td width="950" style="text-align:justify;">'.$matyeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $preparacion=$proestrys[preparacion];
 $preparacion=nl2br($preparacion); //respeta salto de linea
 $preparacion=ucfirst($preparacion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Preparacion del paciente: </b></td><td width="950" style="text-align:justify;">'.$preparacion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $posicion=$proestrys[posicion];
 $posicion=nl2br($posicion); //respeta salto de linea
 $posicion=ucfirst($posicion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Posicion del paciente: </b></td><td width="950" style="text-align:justify;">'.$posicion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $tecnicaeq=$proestrys[tecnicaeq];
 $tecnicaeq=nl2br($tecnicaeq); //respeta salto de linea
 $tecnicaeq=ucfirst($tecnicaeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Tecnica Sugerida: </b></td><td width="950" style="text-align:justify;">'.$tecnicaeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $postadq=$proestrys[postadq];
 $postadq=nl2br($postadq); //respeta salto de linea
 $postadq=ucfirst($postadq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Post adquisicion de estudio: </b></td><td width="950" style="text-align:justify;">'.$postadq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  O T R A S   I N D I C A C I O N E S  C A M A R O N E S   ***/

$pdf->AddPage();

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Otras Indicaciones / Unidad Camarones </td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

 $estructura=$proestcam[estructura];
 $estructura=nl2br($estructura); //respeta salto de linea
 $estructura=ucfirst($estructura); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Estructura a valorar: </b></td><td width="950" style="text-align:justify;">'.$estructura.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $matyeq=$proestcam[matyeq];
 $matyeq=nl2br($matyeq); //respeta salto de linea
 $matyeq=ucfirst($matyeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Material Necesario: </b></td><td width="950" style="text-align:justify;">'.$matyeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $preparacion=$proestcam[preparacion];
 $preparacion=nl2br($preparacion); //respeta salto de linea
 $preparacion=ucfirst($preparacion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Preparacion del paciente: </b></td><td width="950" style="text-align:justify;">'.$preparacion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $posicion=$proestcam[posicion];
 $posicion=nl2br($posicion); //respeta salto de linea
 $posicion=ucfirst($posicion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Posicion del paciente: </b></td><td width="950" style="text-align:justify;">'.$posicion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $tecnicaeq=$proestcam[tecnicaeq];
 $tecnicaeq=nl2br($tecnicaeq); //respeta salto de linea
 $tecnicaeq=ucfirst($tecnicaeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Tecnica Sugerida: </b></td><td width="950" style="text-align:justify;">'.$tecnicaeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $postadq=$proestcam[postadq];
 $postadq=nl2br($postadq); //respeta salto de linea
 $postadq=ucfirst($postadq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Post adquisicion de estudio: </b></td><td width="950" style="text-align:justify;">'.$postadq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

/***  O T R A S   I N D I C A C I O N E S  S A N   V I C E N T E   C H I C O L O A P A N  ***/

$pdf->AddPage();

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="1140" bgcolor="#225c87" color="#ffffff"> * Otras Indicaciones / Unidad San Vicente Chicoloapan </td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

 $estructura=$proestsvc[estructura];
 $estructura=nl2br($estructura); //respeta salto de linea
 $estructura=ucfirst($estructura); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Estructura a valorar: </b></td><td width="950" style="text-align:justify;">'.$estructura.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $matyeq=$proestsvc[matyeq];
 $matyeq=nl2br($matyeq); //respeta salto de linea
 $matyeq=ucfirst($matyeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Material Necesario: </b></td><td width="950" style="text-align:justify;">'.$matyeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $preparacion=$proestsvc[preparacion];
 $preparacion=nl2br($preparacion); //respeta salto de linea
 $preparacion=ucfirst($preparacion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Preparacion del paciente: </b></td><td width="950" style="text-align:justify;">'.$preparacion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $posicion=$proestsvc[posicion];
 $posicion=nl2br($posicion); //respeta salto de linea
 $posicion=ucfirst($posicion); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Posicion del paciente: </b></td><td width="950" style="text-align:justify;">'.$posicion.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $tecnicaeq=$proestsvc[tecnicaeq];
 $tecnicaeq=nl2br($tecnicaeq); //respeta salto de linea
 $tecnicaeq=ucfirst($tecnicaeq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Tecnica Sugerida: </b></td><td width="950" style="text-align:justify;">'.$tecnicaeq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

 $postadq=$proestsvc[postadq];
 $postadq=nl2br($postadq); //respeta salto de linea
 $postadq=ucfirst($postadq); //Coloca la primer letra en mayuscula en un parrafo

$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="170" line-height:"50%";><b> Post adquisicion de estudio: </b></td><td width="950" style="text-align:justify;">'.$postadq.'</td></tr></table><hr><br>';

$pdf->writeHTML($html,false,false,true,false,'');

ob_end_clean();

$pdf->Output();

mysql_close();
?>
