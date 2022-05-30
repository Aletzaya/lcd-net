<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];
  $Team=$check['team'];

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


  $cSql="select estudio,descripcion,precio,descuento from otdnvas where usr='$Usr' and venta='$Vta' ";

//$htmlcontent = getHeadNews($db);

$doc_title    = "Formato";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');
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

if($Team=='2'){
    $Team='LCD - OHF';
    $pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 320, Texcoco Mexico, Col. Centro 56100.                            Tel. (01595) 95 4 45 86');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='3'){
      $Team='LCD - TPX';
    $pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Av Morelos No. 3, Tepexpan Acolman, Col. Centro 55885.                                          Tel. (01594) 95 7 42 97,  95 7 38 82');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='4'){
    $Team='LCD - RYS';
    $pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz Col. Centro 56400.             Tel. (01 55) 58 55 60 24, 58 58 43 07');//define ("PDF_HEADER_LOGO", "logo_example.png");
}else{
    $Team='LCD - Matriz';
    $pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 108, Texcoco Mexico, Col. Centro 56100.                            Tel. (01595) 95 4 11 40, 95 4 62 96');//define ("PDF_HEADER_LOGO", "logo_example.png");
}


//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
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

$OtA=mysql_query("SELECT * from otnvas where usr='$Usr' and venta='$Vta'",$link);
$Ot=mysql_fetch_array($OtA);

$lUpA=mysql_query("SELECT otdnvas.estudio,est.descripcion,otdnvas.precio,otdnvas.descuento,est.condiciones,est.entord from otdnvas,est where otdnvas.usr='$Usr' and otdnvas.venta='$Vta' and otdnvas.estudio=est.estudio",$link);    #Checo k bno halla estudios capturados

$lBd=false;

$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

$pdf->writeHTML("<div> Fecha : $Fecha &nbsp; &nbsp; &nbsp; &nbsp; Hora : $hora  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Atendio: $Usr &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Sucursal: $Team </div><br>", true, 0,false,false,'C');

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

// set barcode
//$pdf->SetBarcode(date("Y-m-d H:i:s", time()));
$pdf->SetBarcode("$Usr - $Team");

$html='<table border="0" cellspacing="1" cellpadding="1"><tr><td width="450" bgcolor="#225c87" color="#ffffff">Est. - Descripcion</td><td width="150" bgcolor="#225c87" color="#ffffff">Entrega</td><td width="180" bgcolor="#225c87" color="#ffffff">Precio</td><td width="140" bgcolor="#225c87" color="#ffffff">Descto.</td><td width="180" bgcolor="#225c87" color="#ffffff">Importe</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'C');

while($reg=mysql_fetch_array($lUpA)){
	if($reg[entord]<=1){
		$DIA="Mismo";
	}else{
		$DIA=$reg[entord];
	}
	
	if($reg[descuento]==0){
		$Marca=" ";
	}else{
		$Marca="*";
	}

// $condiciones2=strtolower($reg[condiciones]); //todo en minuscula
 $condiciones2=$reg[condiciones];
 $condiciones2=nl2br($condiciones2); //respeta salto de linea
 $condiciones2=ucfirst($condiciones2); //Coloca la primer letra en mayuscula en un parrafo
/*utf8_encode($condiciones2);
$codigo= array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&uuml;","&ntilde;");
$cambiar = array("á","é","í","ó","ú","ü","ñ");
$condiciones2 = str_replace($codigo, $cambiar, $condiciones2);
//$condiciones2 = strtolower($condiciones2);
*/
  $pdf->SetFont('Helvetica', '', 9, '', 'false');


  $html='<table border="1" cellspacing="0" cellpadding="0"><tr><td width="450" line-height:"50%";><b>'.$reg[estudio].'</b> - '. $reg[descripcion].'</td><td width="150" align="center">'.$DIA.' Dia(s)</td><td width="180" align="right">$ '.number_format($reg[precio],'2').'&nbsp;</td><td width="140" align="right">$ '.number_format($reg[precio]*($reg[descuento]/100),'2').'&nbsp;</td><td width="180" align="right"><b>$ '.number_format($reg[precio]-($reg[precio]*($reg[descuento]/100)),'2').' '.$Marca.'</b></td></tr><tr><td width="1100" bgcolor="#DAF7A6" style="text-align:center;"><b>Condiciones</b></td></tr><tr><td width="1100" style="text-align:justify;"><font size="11">'.$condiciones2.'</font></td></tr></table><br><br>';

  $pdf->writeHTML($html,false,false,true,false,'');

	 $nDesc+=$reg[precio]*($reg[descuento]/100);
     $nImp+=$reg[precio];
}

  $pdf->SetFont('Helvetica', '', 10, '', 'false');

  $html='<table border="0" cellspacing="0" cellpadding="0"><tr><td width="450" line-height:"50%";></td><td width="150" align="center"><b>Total:</b></td><td width="180" align="right"><b>$ '.number_format($nImp,'2').'</b>&nbsp;</td><td width="140" align="right"><b>$ '.number_format($nDesc,'2').'</b>&nbsp;</td><td width="180" align="right"><b>$ '.number_format($nImp-$nDesc,'2').'</b></td></tr></table><br>';

  $pdf->writeHTML($html,false,false,true,false,'');


  $html='<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center">
  <tr align="center">
    <td colspan="2"><div>Estimado cliente le sugerimos seguir las indicaciones arriba citadas para obtener resultados mas precisos.</div></td>
  </tr><tr>
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

 /* 

<form action="ordenesnvas.php" method="get" name="manda">
  <table width='100%' height="25" border='0' align="center" cellpadding="0" cellspacing="0">
    <tr><td align='right'>
        <div align="right">
          <input type="submit" name="Original" value="Cotizacion" onClick="print()">    
          <input type="hidden" name="op" value="br">
        </div></td></tr></table>
*/ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>
<?php
mysql_close();
?>
