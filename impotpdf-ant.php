<?php
  // Es una copia de clientes solo s cambia las url a clienteseord
  //session_start();

  date_default_timezone_set("America/Mexico_City");
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
  $Usr=$check['uname'];
  $busca=$_REQUEST[busca];
  $ingreso=$_REQUEST[ingreso];
  $reimp=$_REQUEST[reimp];

$doc_title    = "Imprimir Recibo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";
//include("lib/kaplib.php");
//$link=conectarse();

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf8_encode', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "A4");

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 18);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Recibo de Pago");

//set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


$pdf->setLanguageArray($l); //set language items
//initialize document
$pdf->AliasNbPages();

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

// set barcode
//$pdf->SetBarcode(date("Y-m-d H:i:s", time()));
//$pdf->SetBarcode("Laboratorio Clinico Duran");

$pdf->SetFont('Helvetica', 'BI', 8, '', 'false');

$CiaA   =   mysql_query("SELECT direccion FROM cia WHERE id='$Suc'",$link);
$Cia    =   mysql_fetch_array($CiaA);

$cSqlO  =   "SELECT otd.estudio,est.descripcion,otd.precio,otd.descuento from otd,est 
             WHERE otd.estudio=est.estudio and otd.orden='$busca'";
$OtdA   =   mysql_query($cSqlO,$link);
$Otd    =   mysql_fetch_array($OtdA);

$CjaA   =   mysql_query("SELECT sum(importe) from cja where orden='$busca'",$link);
$Cja    =   mysql_fetch_array($CjaA);

$IngA   =   mysql_query("SELECT importe from cja where id='$ingreso'",$link);
$Ing    =   mysql_fetch_array($IngA);

$OtA    =   mysql_query("SELECT ot.cliente,ot.medico,ot.medicon,ot.fecha,ot.institucion,ot.fechae,ot.importe,inst.nombre,ot.servicio,ot.recepcionista,ot.hora,ot.folio,ot.horae,ot.suc
            from ot,inst 
            where inst.institucion=ot.institucion and ot.orden='$busca'",$link);
$Ot     =   mysql_fetch_array($OtA);

$CliA   =   mysql_query("SELECT * from cli where cliente='$Ot[cliente]'",$link);
$Cli    =   mysql_fetch_array($CliA);

$MedA   =   mysql_query("SELECT nombrec from med where medico='$Ot[medico]'",$link);
$Med    =   mysql_fetch_array($MedA);

$Team=$Ot[suc];

if($Team=='2'){
    $Team='OHF';
    //$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 320, Texcoco Mexico, Col. Centro 56100.                            Tel. (01595) 95 4 45 86');//define ("PDF_HEADER_LOGO", "logo_example.png");
    $domicilio="Fray Pedro de Gante No. 320, Texcoco Mexico";
    $domicilio2="Fray Pedro de Gante No. 320,";
    $domicilio3="Col. Centro, Texcoco Edo. de Mex. ";
    $telefonos="Tels: (01595) 95 4 45 86";
    $Horario1="Lunes a Viernes: 07:00 a 20:00 hrs.";
    $Horario2="Sabado: 07:00 a 16:00 hrs.";
    $Horario3=" ";
    $Entrega1="Lunes a Sabado a partir de 15:00 hrs. del";
    $Entrega2="dia prometido y posterior a la fecha de";
    $Entrega3="entrega en horario normal";
}elseif($Team=='3'){
      $Team='TPX';
    //$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Av Morelos No. 3, Tepexpan Acolman, Col. Centro 55885.                                          Tel. (01594) 95 7 42 97,  95 7 38 82');//define ("PDF_HEADER_LOGO", "logo_example.png");
    $domicilio="Av Morelos No. 3, Tepexpan Acolman";
    $domicilio2="Av Morelos No. 3, Tepexpan Acolman,";
    $domicilio3="Col. Centro 55885";
    $telefonos="Tels: (01 594) 95 7 42 97 - 95 7 38 82";
    $Horario1="Lunes a Viernes: 07:00 a 19:00 hrs.";
    $Horario2="Sabado: 07:00 a 15:00 hrs.";
    $Horario3=" ";
    $Entrega1="Lunes a Viernes a partir de 15:00 hrs. del";
    $Entrega2="dia prometido, estudios realizados el dia sabado se entrega el dia lunes.";
    $Entrega3="";
}elseif($Team=='4'){
    $Team='RYS';
    //$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz Col. Centro 56400.             Tel. (01 55) 58 55 60 24, 58 58 43 07');//define ("PDF_HEADER_LOGO", "logo_example.png");
    $domicilio="Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz";
    $domicilio2="Carr. Federal Mexico-Puebla No. 128";
    $domicilio3="Los Reyes La Paz, Col. Centro 56400";
    $telefonos="Tels: (01 55) 58 55 60 24 - 58 58 43 07";
    $Horario1="Lunes a Viernes: 07:00 a 20:00 hrs.";
    $Horario2="Sabado: 07:00 a 16:00 hrs.";
    $Horario3=" ";
    $Entrega1="Lunes a Sabado a partir de 15:00 hrs. del";
    $Entrega2="dia prometido y posterior a la fecha de";
    $Entrega3="entrega en horario normal";
}elseif($Team=='5'){
    $Team='CAM';
    //$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','    Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz Col. Centro 56400.             Tel. (01 55) 58 55 60 24, 58 58 43 07');//define ("PDF_HEADER_LOGO", "logo_example.png");
    $domicilio="Guanabana No. 35, Esq. Calz. Camarones ";
    $domicilio2="Guanabana 35, Esq. Calz. Camarones,";
    $domicilio3="Col. Nueva Santa Maria CP 02800";
    $telefonos="Tels: 53 41 66 50 - 53 42 48 77";
    $Horario1="Lunes a Viernes: 07:00 a 20:00 hrs.";
    $Horario2="Sabado: 07:00 a 16:00 hrs.";
    $Horario3=" ";
    $Entrega1="Lunes a Sabado a partir de 15:00 hrs. del";
    $Entrega2="dia prometido y posterior a la fecha de";
    $Entrega3="entrega en horario normal";

  }elseif($Team=='6'){
    $Team='SVC';
    $domicilio="Carr. Federal Mexico-Texcoco km 28, Chicoloapan ";
    $domicilio2="Carr. Federal Mexico-Texcoco km 28";
    $domicilio3="Col. Revolucion, Chicoloapan, C.P 56390";
    $telefonos="Tels: 55 58 86 36 13 - 55 28 39 46 67";
    $Horario1="Lunes a Viernes: 07:00 a 20:00 hrs.";
    $Horario2="Sabado: 07:00 a 17:00 hrs.";
    $Horario3="Domingo: 07:00 a 14:00 hrs. ";
    $Entrega1="Lunes a Sabado a partir de 15:00 hrs. del";
    $Entrega2="dia prometido y posterior a la fecha de";
    $Entrega3="entrega en horario normal";

    
}else{
    $Team='MTRZ';
    //$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 108, Texcoco Mexico, Col. Centro 56100.                            Tel. (01595) 95 4 11 40, 95 4 62 96');//define ("PDF_HEADER_LOGO", "logo_example.png");
    $domicilio="Fray Pedro de Gante No. 108, Texcoco Mexico";
    $domicilio2="Fray Pedro de Gante No. 108,";
    $domicilio3="Col. Centro, Texcoco, Edo. de Mex.";
    $telefonos="Tels: (01 595) 95 4 11 40  -  95 4 62 96";
    $Horario1="Lunes a Viernes: 07:00 a 21:00 hrs.";
    $Horario2="Sabado: 07:00 a 16:00 hrs.";
    $Horario3="Domingo: 08:00 a 14:00 hrs.";
    $Entrega1="Lunes a Sabado a partir de 15:00 hrs. del";
    $Entrega2="dia prometido y posterior a la fecha de";
    $Entrega3="entrega en horario normal";
}

$Fecha=date("Y-m-d");

$Hora=date("H:i");
//     $Hora1 = date("H:i");
//     $Hora2 = strtotime("-60 min",strtotime($Hora1));
//    $Hora  = date("H:i",$Hora2);

$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

if ($reimp==1){
	$reimpresion=" R e i m  p r e s i o n ";
}else{
	$reimpresion=" ";
}

$Mes       = substr($Fecha,5,2)*1;

$FechaLet  = " a ".substr($Fecha,8,2)." de ".$aMes[$Mes]." del ".substr($Fecha,0,4);

$Hora2		 = substr($Ot[hora],0,5);

$servicio = substr($Ot[servicio],0,28);

if($servicio=='Urgente'){
  $colorletra = "#fc1507";
  $colorletra2 = "#fc1507";
}else{
  $colorletra = "#17202a";
  $colorletra2 = "#17202a";
}

$fechae=$Ot[fechae];
$horae=substr($Ot[horae],0,5);

$pdf->SetFont('Helvetica', 'BI', 10, '', 'false');

$html = '<br><br><table border="0">
  <tr>
    <td width="150"></td>
    <td align="right" width="600">
      <b>Fecha Entrega: '.$fechae.'  Hra: '.$horae.'</b>
     </td>
  </tr>    
  </table>';

$pdf->writeHTML($html,false,false,true,false,'C');

$pdf->SetFont('Helvetica', 'I', 8, '', 'false');

$html = '<br><table border="0">
  <tr>
    <td width="90"></td>
    <td width="655" align="right">'.$domicilio.' '.$FechaLet.' - '.$Hora.'
     </td>
  </tr>   
</table><br>';

$pdf->writeHTML($html,false,false,true,false,'C');

$pdf->SetFont('Helvetica', 'B', 8.5, '', 'false');

$institucion = $Ot[institucion];
$numcliente =$Ot[0];
//$nomcliente =utf8_encode(substr($Cli[nombrec],0,50));
$nomcliente =strtoupper(substr($Cli[nombrec],0,50));
if($Ot[medico]=='MD'){
  $medico = $Ot[medico]." - DR. ".$Ot[medicon];
}else{
  $medico = $Ot[medico]." - DR. ".$Med[nombrec];
}

//********** Codigo QR ***********//

$PNG_WEB_DIR = 'codeqr/';

include "phpqrcode/qrlib.php";

$orden = $busca;
$cliente = $numcliente;
$matrixPointSize = 5;
$errorCorrectionLevel = 'L';
$datos = 'https://lcd-system.com/lcd-net/entregaonline.php?Orden='.$orden.'&Cliente='.$cliente.'';
$filename = $PNG_WEB_DIR . $orden . '_' . $cliente . '.png';

QRcode::png($datos, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>'; 
//********** Codigo QR ***********//

$html = '<table border="0">
  <tr>
    <td align="left" width="130">'.$institucion.' - <span style="background-color: rgb(204, 205, 200);">'.$busca.'</span></td>
    <td align="left" width="700"><span style="background-color: rgb(204, 205, 200);">'.$numcliente.'</span> - '.$nomcliente.'</td>
  <tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html = '<table border="0">
  </tr>    
    <td align="left" width="850">'.$medico.'</td>
  </tr>    
</table>';

$pdf->writeHTML($html,false,false,true,false,'');

$pdf->SetFont('Helvetica', 'BI', 7, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="5"><tr><td width="90" bgcolor="#225c87" color="#ffffff">Clave</td><td width="320" bgcolor="#225c87" color="#ffffff">Estudios</td><td width="120" bgcolor="#225c87" color="#ffffff">Precio</td><td width="100" bgcolor="#225c87" color="#ffffff">Movto.</td><td width="120" bgcolor="#225c87" color="#ffffff">Importe</td></tr></table>';

$pdf->writeHTML($html,false,false,false,false,'C');

//---------------- FIN ENCABEZADO ------------------

$clave1=$Otd[0];
$estudio1=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio1=" ";} else{$precio1='$ '.number_format ($Otd[2],"2");}

if($Otd[3]=='0' or $Otd[0]==''){
  $descto1=" ";
}elseif($Otd[3]<='0'){
  $descto1='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto1='Desc. '.number_format ($Otd[3],"0").'%';
}

if($Otd[2]=='0' or $Otd[0]==''){$importe1=" ";} else{$importe1='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$sprecio1=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');

if($clave1<>''){
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30">'.$clave1.'</td><td width="320">'.$estudio1.'</td><td width="120" align="right">'.$precio1.'&nbsp;</td><td width="100" align="center"><font size="8">'.$descto1.'</font></td><td width="120" align="right">'.$importe1.'&nbsp;</td><td width="400" align="center"><span style="background-color: rgb(204, 205, 200);"><b>&nbsp;'.$Team.' - '.$institucion.' - '.$busca.'&nbsp;</b></span></td></tr></table>';
  }else{
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center"  height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="400" align="center"><span style="background-color: rgb(204, 205, 200);"><b>&nbsp;'.$Team.' - '.$institucion.' - '.$busca.'&nbsp;</b></span></td></tr></table>';
  }

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN PRIMER RENGLON No ORDEN ------------------

$clave2=$Otd[0];
$estudio2=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio2=" ";} else{$precio2='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto2=" ";
}elseif($Otd[3]<='0'){
  $descto2='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto2='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe2=" ";} else{$importe2='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$sprecio2=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave2<>''){
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" bgcolor="#DAF7A6" height="30" style="vertical-align:bottom">'.$clave2.'</td><td width="320" bgcolor="#DAF7A6">'.$estudio2.'</td><td width="120" align="right" bgcolor="#DAF7A6">'.$precio2.'&nbsp;</td><td width="100" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto2.'</font></td><td width="120" align="right" bgcolor="#DAF7A6">'.$importe2.'&nbsp;</td><td width="40"></td><td width="200" align="left">Fecha: '.$Fecha.'</td><td width="230" align="left">Hora: '.$Hora.'</td></tr></table>';
  }else{
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="200" align="left">Fecha: '.$Fecha.'</td><td width="230" align="left">Hora: '.$Hora.'</td></tr></table>';    
  }

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN SEGUNDO RENGLON ------------------

$clave3=$Otd[0];
$estudio3=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio3=" ";} else{$precio3='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto3=" ";
}elseif($Otd[3]<='0'){
  $descto3='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto3='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe3=" ";} else{$importe3='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$sprecio3=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave3<>''){
  $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30">'.$clave3.'</td><td width="320">'.$estudio3.'</td><td width="120" align="right">'.$precio3.'&nbsp;</td><td width="100" align="center"><font size="8">'.$descto3.'</font></td><td width="120" align="right">'.$importe3.'&nbsp;</td><td width="40"></td><td width="200" align="left"><b>Entrega: '.$fechae.'</b></td><td width="230" align="left"><b>A partir de: '.$horae.'</b></td></tr></table>';
}else{
  $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="200" align="left"><b>Entrega: '.$fechae.'</b></td><td width="230" align="left"><b>A partir de: '.$horae.'</b></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN TERCER RENGLON ------------------

$clave4=$Otd[0];
$estudio4=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio4=" ";} else{$precio4='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto4=" ";
}elseif($Otd[3]<='0'){
  $descto4='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto4='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe4=" ";} else{$importe4='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$sprecio4=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave4<>''){
  $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" bgcolor="#DAF7A6" height="30">'.$clave4.'</td><td width="320" bgcolor="#DAF7A6">'.$estudio4.'</td><td width="120" align="right" bgcolor="#DAF7A6">'.$precio4.'&nbsp;</td><td width="100" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto4.'</font></td><td width="120" align="right" bgcolor="#DAF7A6">'.$importe4.'&nbsp;</td><td width="40"></td><td width="430" align="left"><font size="7">'.$numcliente.' - '.$nomcliente.'</font></td></tr></table>';
}else{
  $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="400" align="left"><font size="7">'.$numcliente.' - '.$nomcliente.'</font></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN CUARTO RENGLON ------------------


$clave5=$Otd[0];
$estudio5=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio5=" ";} else{$precio5='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto5=" ";
}elseif($Otd[3]<='0'){
  $descto5='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto5='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe5=" ";} else{$importe5='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$sprecio5=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave5<>''){
  $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30">'.$clave5.'</td><td width="320">'.$estudio5.'</td><td width="120" align="right">'.$precio5.'&nbsp;</td><td width="100" align="center"><font size="8">'.$descto5.'</font></td><td width="120" align="right">'.$importe5.'&nbsp;</td><td width="40"></td><td width="430" align="left"><font size="7">'.$medico.'</font></td></tr></table>';
}else{
  $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="430" align="left"><font size="7">'.$medico.'</font></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN QUINTO RENGLON ------------------

$clave6=$Otd[0];
$estudio6=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio6=" ";} else{$precio6='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto6=" ";
}elseif($Otd[3]<='0'){
  $descto6='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto6='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe6=" ";} else{$importe6='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$recepcionista=$Ot[recepcionista];
$sprecio6=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave6<>''){
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" bgcolor="#DAF7A6" height="30">'.$clave6.'</td><td width="320" bgcolor="#DAF7A6">'.$estudio6.'</td><td width="120" align="right" bgcolor="#DAF7A6">'.$precio6.'&nbsp;</td><td width="100" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto6.'</font></td><td width="120" align="right" bgcolor="#DAF7A6">'.$importe6.'&nbsp;</td><td width="40"></td><td width="430" align="left">Capturo: '.$recepcionista.'</td></tr></table>';
  }else{
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="430" align="left">Capturo: '.$recepcionista.'</td></tr></table>';
  }
 
 $pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN SEXTO RENGLON ------------------

$clave7=$Otd[0];
$estudio7=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio7=" ";} else{$precio7='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto7=" ";
}elseif($Otd[3]<='0'){
  $descto7='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto7='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe7=" ";} else{$importe7='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$sprecio7=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave7<>''){
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30">'.$clave7.'</td><td width="320">'.$estudio7.'</td><td width="120" align="right">'.$precio7.'&nbsp;</td><td width="100" align="center"><font size="8">'.$descto7.'</font></td><td width="120" align="right">'.$importe7.'&nbsp;</td><td width="40"></td><td width="180" align="left"><font size="5"><b> F 0AC RE-04/00 </b></font></td><td width="180" align="right"><font size="10" color='.$colorletra.'><b> '.$servicio.' </b></font></td></tr></table>';
  }else{
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="180" align="left"><font size="5"><b> F 0AC RE-04/00 </b></font></td><td width="180" align="right"><font size="10" color='.$colorletra.'><b> '.$servicio.' </b></font></td></tr></table>';
  }

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN SEPTIMO RENGLON ------------------

$clave8=$Otd[0];
$estudio8=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio8=" ";} else{$precio8='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto8=" ";
}elseif($Otd[3]<='0'){
  $descto8='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto8='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe8=" ";} else{$importe8='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$recepcionista=$Ot[recepcionista];
$sprecio8=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave8<>''){
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" bgcolor="#DAF7A6" height="30">'.$clave8.'</td><td width="320" bgcolor="#DAF7A6">'.$estudio8.'</td><td width="120" align="right" bgcolor="#DAF7A6">'.$precio8.'&nbsp;</td><td width="100" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto8.'</font></td><td width="120" align="right" bgcolor="#DAF7A6">'.$importe8.'&nbsp;</td><td width="40"></td><td width="90" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Clave</b></i></font></td><td width="90" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Precio</b></i></font></td><td width="90" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Movto.</b></i></font></td><td width="90" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Importe</b></i></font></td></tr></table>';
  }else{
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="80" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Clave</b></i></font></td><td width="90" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Precio</b></i></font></td><td width="100" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Movto.</b></i></font></td><td width="90" align="left" bgcolor="#225c87" color="#ffffff" align="center"><font size="7"><b><i>Importe</b></i></font></td></tr></table>';
  }

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN OCTAVO RENGLON ------------------

$clave9=$Otd[0];
$estudio9=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio9=" ";} else{$precio9='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto9=" ";
}elseif($Otd[3]<='0'){
  $descto9='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto9='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe9=" ";} else{$importe9='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$sprecio9=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave9<>''){
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30">'.$clave9.'</td><td width="320">'.$estudio9.'</td><td width="120" align="right">'.$precio9.'&nbsp;</td><td width="100" align="center"><font size="8">'.$descto9.'</font></td><td width="120" align="right">'.$importe9.'&nbsp;</td><td width="40"></td><td width="80" align="left" align="center"><font size="7">'.$clave1.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio1.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto1.'</font></td><td width="90" align="right"><font size="8">'.$importe1.'&nbsp;</font></td></tr></table>';
  }else{
    $pdf->SetFont('Helvetica', '', 8, '', 'false');

    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td><td width="80" align="left" align="center"><font size="7">'.$clave1.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio1.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto1.'</font></td><td width="90" align="right"><font size="8">'.$importe1.'&nbsp;</font></td></tr></table>';
  }

$pdf->writeHTML($html,false,false,false,false,'');

$Otd=mysql_fetch_array($OtdA);

//---------------- FIN NOVENO RENGLON ------------------

$clave10=$Otd[0];
$estudio10=substr($Otd[1],0,28);
if($Otd[2]=='0' or $Otd[0]==''){$precio10=" ";} else{$precio10='$ '.number_format ($Otd[2],"2");}
if($Otd[3]=='0' or $Otd[0]==''){
  $descto10=" ";
}elseif($Otd[3]<='0'){
  $descto10='Urg. + '.number_format (($Otd[3]-$Otd[3])-$Otd[3],"0").'%';
}else{
  $descto10='Desc. '.number_format ($Otd[3],"0").'%';
}
if($Otd[2]=='0' or $Otd[0]==''){$importe10=" ";} else{$importe10='$ '.number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
$recepcionista=$Ot[recepcionista];
$sprecio10=$Otd[2];

$pdf->SetFont('Helvetica', '', 7.5, '', 'false');


if($clave10<>''){
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" bgcolor="#DAF7A6" height="30">'.$clave10.'</td><td width="320" bgcolor="#DAF7A6">'.$estudio10.'</td><td width="120" align="right" bgcolor="#DAF7A6">'.$precio10.'&nbsp;</td><td width="100" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto10.'</font></td><td width="120" align="right" bgcolor="#DAF7A6">'.$importe10.'&nbsp;</td><td width="40"></td>';
      
      if($clave2<>''){
      
        $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave2.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio2.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto2.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe2.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }

  }else{
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="90" align="center" height="30"></td><td width="320"></td><td width="120" align="right"></td><td width="100" align="center"></td><td width="120" align="right"></td><td width="40"></td>';
      
      if($clave2<>''){
      
        $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave2.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio2.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto2.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe2.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }
  }

$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN DECIMO RENGLON ------------------

$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="530" align="center" height="30">Estimado Cliente ponemos a su disposición la entrega de sus resultados</td><td width="220" align="center" rowspan="5"><img src="'.$filename.'" width="120" height="120" /></td><td width="40"></td>';

if($clave3<>''){

  $html.='<td width="80" align="left" align="center"><font size="7">'.$clave3.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio3.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto3.'</font></td><td width="90" align="right"><font size="8">'.$importe3.'&nbsp;</font></td></tr></table>';
}else{

  $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN ONCEAVO RENGLON ------------------

$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="530" align="center" height="30">de forma digital, solo tendrá que escanear el Codigo QR que se</td><td width="220" align="center"></td><td width="40"></td>';

if($clave4<>''){

  $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave4.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio4.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto4.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe4.'&nbsp;</font></td></tr></table>';
}else{

  $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN DOCEAVO RENGLON ------------------


$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="530" align="center" height="30">encuentra  en su comprobante de cobro y el enlace lo dirigirá a</td><td width="220" align="center"></td><td width="40"></td>';

if($clave5<>''){

  $html.='<td width="80" align="left" align="center"><font size="7">'.$clave5.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio5.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto5.'</font></td><td width="90" align="right"><font size="8">'.$importe5.'&nbsp;</font></td></tr></table>';
}else{

  $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 13VO RENGLON ------------------


$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="530" align="center" height="30">nuestra plataforma.</td><td width="220" align="center"></td><td width="40"></td>';

if($clave6<>''){

  $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave6.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio6.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto6.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe6.'&nbsp;</font></td></tr></table>';
}else{

  $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 14VO RENGLON ------------------


$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="530" align="center" height="30">* Para tener acceso su recibo deberá estar pagado en su totalidad.</td><td width="220" align="center"></td><td width="40"></td>';

if($clave7<>''){

  $html.='<td width="80" align="left" align="center"><font size="7">'.$clave7.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio7.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto7.'</font></td><td width="90" align="right"><font size="8">'.$importe7.'&nbsp;</font></td></tr></table>';
}else{

  $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
}

$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 15VO RENGLON ------------------
$pdf->SetFont('Helvetica', '', 8.5, '', 'false');

  $importotal='$ '.number_format($Ot[6],"2");
  $subimportotal=$sprecio1+$sprecio2+$sprecio3+$sprecio4+$sprecio5+$sprecio6+$sprecio7+$sprecio8+$sprecio9+$sprecio10+$sprecio11+$sprecio12+$sprecio13+$sprecio14+$sprecio15;
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="200" align="left"><strong>Observaciones:</strong></td><td width="120" align="left"><font size="10" color='.$colorletra2.'><b> '.$servicio.' </b></font></td><td width="120" align="right" bgcolor="#DAF7A6"><b>SubTotal:</b></td><td width="90" align="right" bgcolor="#DAF7A6"><b>$ '.number_format($subimportotal,"2").'</b></td><td width="120" align="right" bgcolor="#DAF7A6"><b>Total:</b></td><td width="100" align="right" bgcolor="#DAF7A6"><b>'.$importotal.'</b></td><td width="40"></td>';
      

if($clave8<>''){

  $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave8.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio8.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto8.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe8.'&nbsp;</font></td></tr></table>';
}else{

  $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
}


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 16VO RENGLON ------------------

  $acuenta='$ '.number_format($Cja[0],"2");

    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left">Si requiere factura favor de proporcionar su R.F.C.</td><td width="40"></td><td width="150" align="right"><b>A cuenta:</b></td><td width="110" align="right"><b>'.$acuenta.'</b></td><td width="40"></td>';
      
      if($clave9<>''){
      
        $html.='<td width="80" align="left" align="center"><font size="7">'.$clave9.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio9.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto9.'</font></td><td width="90" align="right"><font size="8">'.$importe9.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 17VO RENGLON ------------------

  $resta='$ '.number_format($Ot[6]-$Cja[0],"2");
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left">Verifique que los datos de su recibo sean correctos</td><td width="40"></td><td width="150" align="right" bgcolor="#DAF7A6"><b>Resta:</b></td><td width="110" align="right" bgcolor="#DAF7A6"><b>'.$resta.'</b></td><td width="40"></td>';
      
      if($clave10<>''){
      
        $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave10.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio10.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto10.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe10.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 18VO RENGLON ------------------

    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="center" height="30" rowspan="6"><img src="Promociones.jpg" width="400" height="250" /></td><td width="300" align="center"><font size="7">Atendio: '.$recepcionista.'</font></td><td width="40"></td>';

//    $html='<table border="0" cellspacing="0" cellpadding="5"><tr><td width="450" align="left" height="30"></td><td width="100"></td><td width="140" align="right"><b></b></td><td width="100" align="right"><b></b></td><td width="40"></td>';
      
      if($clave11<>''){
      
        $html.='<td width="80" align="left" align="center"><font size="7">'.$clave11.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio11.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto11.'</font></td><td width="90" align="right"><font size="8">'.$importe11.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 19VO RENGLON ------------------

    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left" height="30"></td><td width="300" align="center" rowspan="6"><font size="8">'.$domicilio2.'<br />'.$domicilio3.'<br />'.$telefonos.'</font></td><td width="40"></td>';

      
      if($clave12<>''){
      
        $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave12.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio12.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto12.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe12.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 20VO RENGLON ------------------


 //   $html='<table border="0" cellspacing="0" cellpadding="5"><tr><td width="450" align="left" height="30"></td><td width="340" align="center"><font size="8">'.$domicilio3.'</font></td><td width="40"></td>';

    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left"></td><td width="300" align="center"><font size="8"></font></td><td width="40"></td>';

      
      if($clave13<>''){
      
        $html.='<td width="80" align="left" align="center"><font size="7">'.$clave13.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio13.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto13.'</font></td><td width="90" align="right"><font size="8">'.$importe13.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 21VO RENGLON ------------------


//    $html='<table border="0" cellspacing="0" cellpadding="5"><tr><td width="450" align="left" height="30"></td><td width="340" align="center"><font size="8">'.$telefonos.'</font></td><td width="40"></td>';

    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left"></td><td width="300" align="center"><font size="8"><b>Horario de Atencion</b></font></td><td width="40"></td>';

      
      if($clave14<>''){
      
        $html.='<td width="80" align="left" align="center" bgcolor="#DAF7A6"><font size="7">'.$clave14.'</font></td><td width="90" align="left" align="right" bgcolor="#DAF7A6"><font size="8">'.$precio14.'&nbsp;</font></td><td width="100" align="left" align="center" bgcolor="#DAF7A6"><font size="8">'.$descto14.'</font></td><td width="90" align="right" bgcolor="#DAF7A6"><font size="8">'.$importe14.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 22VO RENGLON ------------------


 //   $html='<table border="0" cellspacing="0" cellpadding="5"><tr><td width="450" align="left" height="30"></td><td width="340" align="center"><font size="8"></font></td><td width="40"></td>';
    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left"></td><td width="300" align="center" rowspan="6"><font size="8">'.$Horario1.' <br />'.$Horario2.'<br />'.$Horario3.'</font></td><td width="40"></td>';

      
      if($clave15<>''){
      
        $html.='<td width="80" align="left" align="center"><font size="7">'.$clave15.'</font></td><td width="90" align="left" align="right"><font size="8">'.$precio15.'&nbsp;</font></td><td width="100" align="left" align="center"><font size="8">'.$descto15.'</font></td><td width="90" align="right"><font size="8">'.$importe15.'&nbsp;</font></td></tr></table>';
      }else{

        $html.='<td width="80" align="left" align="center"><font size="7"></font></td><td width="90" align="left" align="right"><font size="8"></font></td><td width="100" align="left" align="center"><font size="8"></font></td><td width="90" align="right"><font size="8"></font></td></tr></table>';
      }


$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 22VO RENGLON ------------------


    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left" height="30"></td><td width="300" align="center"><font size="8"></font></td><td width="40"></td>';
            
    $html.='<td width="180" align="left" bgcolor="#DAF7A6" align="right" height="30"><b>Importe Total: </b></td><td width="180" bgcolor="#DAF7A6" align="right"><b>'.$importotal.'&nbsp;</b></td></tr></table>';



$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 22VO RENGLON ------------------

    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left"></td><td width="300" align="center"><font size="8"><b>Horario de Entrega</b></font></td><td width="40"></td>';
            
    $html.='<td width="180" align="left" align="right" height="30"><b>A Cuenta: </b></td><td width="180" align="right"><b>'.$acuenta.'&nbsp;</b></td></tr></table>';



$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 23VO RENGLON ------------------


    $html='<table border="0" cellspacing="0" cellpadding="3"><tr><td width="450" align="left" height="30"></td><td width="300" align="center" rowspan="6"><font size="8">'.$Entrega1.' <br />'.$Entrega2.'<br />'.$Entrega3.'</font></td><td width="40"></td>';
            
    $html.='<td width="180" align="left" bgcolor="#DAF7A6" align="right" height="30"><b>Resta: </b></td><td width="180" bgcolor="#DAF7A6" align="right"><b>'.$resta.'&nbsp;</b></td></tr></table>';



$pdf->writeHTML($html,false,false,false,false,'');

//---------------- FIN 23VO RENGLON ------------------

ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>