<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");
  date_default_timezone_set("America/Mexico_City");



  $Usr    = $_COOKIE['USERNAME'];


  $link=conectarse();


  if (isset($_REQUEST[Venta])) {
    $_SESSION['cVarVal'][0] = $_REQUEST[Venta];
}

if(!isset($_REQUEST['Sucent'])){$Sucent=$suc2;}else{$Sucent=$_REQUEST['Sucent'];} //Sucursal de entrega

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Team = $_SESSION[Usr][4];


  $tamPag=10;

  if(!isset($_REQUEST[Vta])){$Vta=$_SESSION['Venta_ot'];}else{

    $Vta=$_REQUEST[Vta];

    $_SESSION['Venta_ot']=$_REQUEST[Vta];


 }

  $busca=$_REQUEST[busca];

  

if($Team=='2'){
  $lugar = 'Texcoco de Mora';
}elseif($Team=='3'){
  $lugar = 'Tepexpan, Acolman';
}elseif($Team=='4'){
    $lugar='Los reyes, La Paz';
}elseif($Team=='5'){
    $lugar='Azcapozalco, CDMX';
}else{
    $lugar = 'Texcoco de Mora';
}

$Fecha  = date("Y-m-d");

$Mes    = substr($Fecha,5,2)*1;
$Hora   = date("H:i");

$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


$FechaLet  = $lugar. " a ".substr($Fecha,8,2)." de ".$aMes[$Mes]." del ".substr($Fecha,0,4) . " &nbsp; &nbsp; ". $Hora; 

$OtA  = mysql_query("SELECT otnvas.inst,otnvas.lista,otnvas.cliente,otnvas.medico,otnvas.receta,otnvas.fechar,
        inst.nombre as nombreinst
        FROM otnvas
        LEFT JOIN inst ON otnvas.inst=inst.institucion
        WHERE otnvas.venta='$Vta' and otnvas.usr='$Usr'");

$Ot     =   mysql_fetch_array($OtA);
$CliA   =   mysql_query("SELECT * FROM cli WHERE cliente='$Ot[cliente]'",$link);
$Cli    =   mysql_fetch_array($CliA);


$Fechanac  =  $Cli[fechan];
$Fecha   = date("Y-m-d");
$array_nacimiento = explode ( "-", $Fechanac ); 
$array_actual = explode ( "-", $Fecha ); 
$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

if ($dias < 0) 
{ 
    --$meses; 

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
    switch ($array_actual[1]) { 
           case 1:     $dias_mes_anterior=31; break; 
           case 2:     $dias_mes_anterior=31; break; 
           case 3:  $dias_mes_anterior=28; break;
//                      if (bisiesto($array_actual[0])) 
//                      { 
//                          $dias_mes_anterior=29; break; 
//                      } else { 
//                          $dias_mes_anterior=28; break; 
//                      } 
           case 4:     $dias_mes_anterior=31; break; 
           case 5:     $dias_mes_anterior=30; break; 
           case 6:     $dias_mes_anterior=31; break; 
           case 7:     $dias_mes_anterior=30; break; 
           case 8:     $dias_mes_anterior=31; break; 
           case 9:     $dias_mes_anterior=31; break; 
           case 10:     $dias_mes_anterior=30; break; 
           case 11:     $dias_mes_anterior=31; break; 
           case 12:     $dias_mes_anterior=30; break; 
    } 

    $dias=$dias + $dias_mes_anterior; 
} 
//
//ajuste de posible negativo en $meses 
if ($meses < 0) 
{ 
    --$anos; 
    $meses=$meses + 12; 
} 

if($anos>='110'){
    $Edad='Verificar Fecha de Nacimiento';
}elseif($anos=='0' and $meses=='0'){
    $Edad=$dias.' Dias';
}elseif($anos=='0' and $meses>='1'){
    $Edad=$meses.' Meses';
}elseif($anos>='1'){
    $Edad=$anos .' A&ntilde;os ';
}




$doc_title    = "Orden de Servicio a domicilio";
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



$html='<br><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr><td align="center"><font size="12" color="red">Orden de Servicio a domicilio </font></td></tr><br></br>
<tr><td align="center">'.$FechaLet.'</td></tr><br></br>
<tr><td align="center">Institucion: '. $Ot[institucion] . ' ' . $Ot[nombreinst].'</td></tr>
</table><br>';
$pdf->writeHTML($html,false,false,true,false,'');



$html='<br><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td><font size="9" color="black">Nombre del paciente:'.$Cli[nombrec].' </font></td><td><font size="9" color="black">Colonia: '.$Cli[colonia].'</font></td></tr>   
<tr><td><font size="9" color="black">Edad: '. $Edad . ' </font></td> <td><font size="9" color="black">Mpio.:'.$Cli[municipio].' </font></td></tr>  
<tr><td><font size="9" color="black">Sexo:'. $Cli[sexo].'</font></td> <td><font size="9" color="black">Referencia de ubicacion:'.$Cli[refubicacion].'</font></td></tr>
<tr><td><font size="9" color="black">Direccion: '.$Cli[direccion].' </font></td></tr></table></br><br></br><br></br>';

$pdf->writeHTML($html,false,false,true,false,'');


//$pdf->writeHTML("<div><font size='11'> Cotizacion: $busca </font>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fecha : $Fecha &nbsp; &nbsp; &nbsp; &nbsp; Hora : $hora  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Atendio: $Usr &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Sucursal: $Team <div><br>", true, 0,false,false,'C');
$pdf->SetFont('Helvetica', 'BI', 9, '', 'false');

// set barcode
//$pdf->SetBarcode(date("Y-m-d H:i:s", time()));
$pdf->SetBarcode("$Usr - $Team");

$html='<table width="90%" border="0" align="center" cellpadding="0" cellspacing="2"><tr>
<td width="100" bgcolor="#225c87" color="#ffffff">#Servicio</td><td width="150" bgcolor="#225c87" color="#ffffff">Estudio</td><td width="450" bgcolor="#225c87" color="#ffffff">Descripcion</td><td width="150" bgcolor="#225c87" color="#ffffff">Precio</td><td width="100" bgcolor="#225c87" color="#ffffff">Descto.</td><td width="180" bgcolor="#225c87" color="#ffffff">Importe</td></tr>';

$OtdA = mysql_query("SELECT otdnvas.estudio,otdnvas.precio,otdnvas.descuento,est.depto,est.descripcion
            FROM otdnvas,est
            WHERE otdnvas.usr='$Usr' and otdnvas.venta='$Vta' and otdnvas.estudio=est.estudio");    #Checo k bno halla estudios capturados



while ($row = mysql_fetch_array($OtdA)) {
  $nRn++;   

  $html.='<tr>
  <td width="100">'.$nRn.'</td>
  <td width="150">'.$row[estudio].'</td>
  <td width="450">'.$row[descripcion].'</td>
  <td width="150">'.number_format($row[precio],"2").'</td>
  <td width="100">'.number_format($row[descuento],"2").' </td>
  <td width="100">'.number_format($row[precio]-($row[precio]*$row[descuento]/100),"2").'</td>
  </tr>';

  $total+=$row[precio]-($row[precio]*$row[descuento]/100);
}

  $html.='<tr><td colspan="12" >TOTAL  $'.number_format($total,"2").'</td></tr></table><br></br>';


$pdf->writeHTML($html,false,false,true,false,'');


$pdf->SetFont('Helvetica', '', 9, '', 'false');

 $html='<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td><font size="9" color="black">Lugar de la visita:____________________________________________________________________________________</font></td></tr><br></br>
<tr><td><font size="9" color="black">Referencia:________________________________________________________________________________________</font></td><br></br></tr><br></br>
<tr><td><font size="9" color="black">Observaciones:_____________________________________________________________________________________</font></td></tr></table></br>

<p align="">Tipo de servicio:</b> &nbsp;  ___express &nbsp;&nbsp; ___urgente &nbsp;&nbsp; ___ordinario &nbsp;&nbsp;
<p>Fecha de entrega:</b> _____________________ &nbsp; <b>Hora:</b> ____________</p>
<p align="right"><b>Entrega de resultados:</b> &nbsp;  ___ correo &nbsp; ___ mostrador &nbsp; ___ domicilio</p>
<br><p>&nbsp;Realiza servicio:_____________________________________________
"Realiza proceso:  ___________________________________</p>
<p> Vehiculo: ___________________________"Fecha:  ____________________________</p>

  <div align="right"><input type="submit" name="Original" value="Imprimir" onClick="print()">
   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>';
  
 $pdf->writeHTML($html,false,0,false,false,''); 



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

mysql_close();
?>
