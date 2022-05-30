<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=strtoupper ($check['uname']);

  $Titulo="Cotizacion";

  $link=conectarse();

  $tamPag=10;

  //$Vta=$_REQUEST[Vta];

  if(!isset($_REQUEST[Vta])){$Vta=$_SESSION['Venta_ot'];}else{

     $Vta=$_REQUEST[Vta];

     $_SESSION['Venta_ot']=$_REQUEST[Vta];


  } #En caso k venga del cat.de clientes(cliventas) y desde ahi manda la clave


  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30


  $cSql="select * from ctd where id='$busca' ";

//$htmlcontent = getHeadNews($db);

$doc_title    = "Formato cotizacion";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
//require_once('tcpdf2/tcpdf_include.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true,'UTF-8',false);
// set document information

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "A4");

$OtA=mysql_query("SELECT * from ct where id='$busca'",$link);
$Ot=mysql_fetch_array($OtA);

$Team=$Ot[suc];

$lUpA=mysql_query("SELECT ctd.estudio,est.descripcion,ctd.precio,ctd.descuento,est.condiciones,est.entord from ctd,est where ctd.id='$busca' and ctd.estudio=est.estudio",$link);    #Checo k bno halla estudios capturados

if($Team=='1' or $Team=='0'){
    $Team='LCD - Matriz';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 108, Texcoco Mexico, Col. Centro 56100.                                 Tel. (01595) 95 4 11 40, 95 4 62 96');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='2'){
    $Team='LCD - OHF';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 320, Texcoco Mexico, Col. Centro 56100.                                Tel. (01595) 95 4 45 86');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='3'){
      $Team='LCD - TPX';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Av Morelos No. 3, Tepexpan Acolman, Col. Centro 55885.                                                Tel. (01594) 95 7 42 97,  95 7 38 82');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='4'){
    $Team='LCD - RYS';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz Col. Centro 56400.                   Tel. (01 55) 58 55 60 24, 58 58 43 07');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='5'){
    $Team='LCD - CAM';
    $pdf->SetHeaderData('DuranNvoBk.png', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Guanabana No. 35, Esq. Calz. Camarones,Col. Nueva Santa Maria CP 02800                Tels: 53 41 66 50 - 53 42 48 77');//define ("PDF_HEADER_LOGO", "logo_example.png");
}


//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 25);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Cotizacion");

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

$lBd=false;

$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

$html='<br><table border="0" cellspacing="0" cellpadding="0"><tr><td width="250" style="text-align:justify;">
<font size="11" color="red">Cotización: '.$busca.'</font></td><td width="200" style="text-align:justify;"><font size="9">Fecha: '.$Fecha.'</font></td><td width="150" style="text-align:justify;"><font size="9">Hora: '.$hora.'</font></td><td width="250" style="text-align:justify;"><font size="9">Atendio: '.$Ot[recepcionista].'</font></td><td width="300" style="text-align:justify;"><font size="9">Sucursal: '.$Team.'</font></td></tr></table><br><br>';

$pdf->writeHTML($html,false,false,true,false,'');

//$pdf->writeHTML("<div><font size='11'> Cotizacion: $busca </font>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fecha : $Fecha &nbsp; &nbsp; &nbsp; &nbsp; Hora : $hora  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Atendio: $Usr &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Sucursal: $Team <div><br>", true, 0,false,false,'C');

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

// set barcode
//$pdf->SetBarcode(date("Y-m-d H:i:s", time()));
$pdf->SetBarcode("$Usr - $Team");


$nRn = 1;

$html='<table width="90%" border="0" align="center" cellpadding="0" cellspacing="2">
<tr>
<td width="100" bgcolor="#225c87" color="#ffffff">#Servicio</td>
<td width="150" bgcolor="#225c87" color="#ffffff">Estudio</td>
<td width="450" bgcolor="#225c87" color="#ffffff">Descripcion</td>
<td width="150" bgcolor="#225c87" color="#ffffff">Precio</td>
<td width="100" bgcolor="#225c87" color="#ffffff">Descto.</td>
<td width="180" bgcolor="#225c87" color="#ffffff">Importe</td></tr>';



while ($row = mysql_fetch_array($lUpA)) {

    if (($nRn % 2) > 0) {
        $Fdo = '#FFFFFF';
    } else {
        $Fdo = '#D5D8DC';
    }

    $pdf->SetFont('Helvetica', '', 10, '', 'false');

  $html.='<tr bgcolor='.$Fdo.'  >
  <td width="100" align="center" height="50"  >'.$nRn.'</td>
  <td width="150" align="left">'.$row[estudio].'</td>
  <td width="450" align="left">'.$row[descripcion].'</td>
  <td width="150" align="righ">'.number_format($row[precio],"2").'</td>
  <td width="100" align="righ">'.number_format($row[descuento]).' %</td>
  <td width="180" align="righ">'.number_format($row[precio]-($row[precio]*$row[descuento]/100),"2").'</td>
  </tr>';




  $totalg+=$row[precio];
  $totald+=$row[descuento];
  $total+=$row[precio]-($row[precio]*$row[descuento]/100);
  $nRn++;   

}

$html.='
<tr>
<td width="100" align="center" ></td>
<td width="150" align="left"></td>
<td width="450" align="ringh"><b>Total</b></td>
<td width="150" align="righ"><b>'.number_format($totalg,"2").'</b></td>
<td width="100" align="righ"><b>-</b></td>
<td width="180" align="righ"><b>'.number_format($total,"2").'</b></td></tr></table><br></br><br></br><br></br>';
$pdf->writeHTML($html,false,false,true,false,'');




$html='<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center">
<tr align="center">
  <td colspan="2"><div><b>Entrega de resultados</b></div></td>
</tr><tr>
  <td width="100%" height="51">- Muestras recibidas antes de las 12:00 hrs. se entregar&aacute;n los resultados a partir de las 3:00 P.M.</td>
  <td width="100%" height="51">- Muestras recibidas despues de las 7:00 P.M. se entregar&aacute;n los resultados el d&iacute;a siguiente.</td>
</tr><tr>
  <td width="100%" height="51">- En caso de <strong>urgencia</strong> el personal de recepci&oacute;n le indicar&aacute; el tiempo de entrega.</td>
  <td width="100%" height="51">- Considerar en el tiempo de entrega unicamente d&iacute;as h&aacute;biles.</td>
</tr></table>';

$pdf->writeHTML($html,false,0,false,false,'C'); 

  $pdf->writeHTML("</table>
<p><span>* </span><span>Estudios que incluyen descuento sobre su precio normal </span></p>",true, 0,true,false,'C');

ob_end_clean();
//Close and output PDF document
$pdf->Output();

mysql_close();
?>
