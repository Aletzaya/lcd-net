<?php
  session_start();

  require("lib/lib.php");

  $link = conectarse();

  $Usr=$check['uname'];

  $Team=$check['team'];

  $estudio  = $_REQUEST[estudio];

  $CpoA   = mysql_query("SELECT * FROM est WHERE estudio='$estudio'");
  
  $Cpo    = mysql_fetch_array($CpoA);
  
  $Titulo =  "<br>Informacion Importante del Estudio: ".$estudio." - ".$Cpo[descripcion];

 require ("config.php");

$doc_title    = "Informacion de Estudio";
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

$image_file2 = 'lib/DuranNvoBk.png';


define ("PDF_PAGE_FORMAT", "A4");

if($Team=='2'){
    $Team='LCD - OHF';
    $pdf->SetHeaderData('DuranNvoBk.png', '42', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 320, Texcoco Mexico, Col. Centro 56100.                                 Tel. (01595) 95 4 45 86');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='3'){
      $Team='LCD - TPX';
    $pdf->SetHeaderData('DuranNvoBk.png', '42', '    Laboratorio Clinico Duran S.A. de C.V.','    Av Morelos No. 3, Tepexpan Acolman, Col. Centro 55885.                                                Tel. (01594) 95 7 42 97,  95 7 38 82');//define ("PDF_HEADER_LOGO", "logo_example.png");
}elseif($Team=='4'){
    $Team='LCD - RYS';
    $pdf->SetHeaderData('lib/DuranNvoBk.png', '42', '    Laboratorio Clinico Duran S.A. de C.V.','    Carr. Federal Mexico-Puebla No. 128, Los Reyes La Paz Col. Centro 56400.                   Tel. (01 55) 58 55 60 24, 58 58 43 07');//define ("PDF_HEADER_LOGO", "logo_example.png");
}else{
    $Team='LCD - Matriz';
    $pdf->SetHeaderData('DuranNvoBk.png', '42', '    Laboratorio Clinico Duran S.A. de C.V.','     Fray Pedro de Gante No. 108, Texcoco Mexico, Col. Centro 56100.                                  Tel. (01595) 95 4 11 40, 95 4 62 96');//define ("PDF_HEADER_LOGO", "logo_example.png");
}
?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body>

<?php 
    
//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Informacion de Estudio");

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

$pdf->SetFont('Helvetica', 'BI', 12, '', 'false');

$pdf->writeHTML("<div>$Titulo</div>", true, 0,false,false,'C');

$pdf->SetFont('Helvetica', '', 10, '', 'false');

 $Objetivo=$Cpo[objetivo];
 $Objetivo=nl2br($Objetivo); //respeta salto de linea
 $Objetivo=ucfirst($Objetivo); //Coloca la primer letra en mayuscula en un parrafo
 $Objetivo=$Objetivo; //Coloca la primer letra en mayuscula en un parrafo

 $Condiciones=$Cpo[condiciones];
 $Condiciones=nl2br($Condiciones); //respeta salto de linea
 $Condiciones=ucfirst($Condiciones); //Coloca la primer letra en mayuscula en un parrafo
 $Condiciones=$Condiciones; //Coloca la primer letra en mayuscula en un parrafo

 $Contenido=$Cpo[contenido];
 $Contenido=nl2br($Contenido); //respeta salto de linea
 $Contenido=ucfirst($Contenido); //Coloca la primer letra en mayuscula en un parrafo
 $Contenido=$Contenido; //Coloca la primer letra en mayuscula en un parrafo

 $Observaciones=$Cpo[observaciones];
 $Observaciones=nl2br($Observaciones); //respeta salto de linea
 $Observaciones=ucfirst($Observaciones); //Coloca la primer letra en mayuscula en un parrafo
 $Observaciones=$Observaciones; //Coloca la primer letra en mayuscula en un parrafo

if($Cpo[entord]==0){
  $Tiempoentrega='Mismo Dia';
}else{
  $Tiempoentrega=$Cpo[entord].' Dia(s)';
}

$pdf->writeHTML("<table width='170%' border='1'><hr><tr><td width='170'><b>Objetivo: </b> </td><td style=text-align:justify;>".$Objetivo."</td></tr><hr><tr><td width='170'><b>Condiciones: </b> </td><td style=text-align:justify;>".$Condiciones."</td></tr><hr><tr><td width='170'><b>Contenido: </b> </td><td style=text-align:justify;>".$Contenido."</td></tr><br><hr><tr><td width='170'><b>Observaciones: </b> </td><td style=text-align:justify;>".$Observaciones."</td></tr><hr><tr><td width='170'><b>Tiempo de entrega: </b> </td><td style=text-align:justify;>".$Tiempoentrega."</td></tr></table>");

$pdf->writeHTML($html,true,false,true,false,'');


ob_end_clean();
//Close and output PDF document
$pdf->Output();

/*
    echo "<br><p align='center'>$Gfont <b><font size='+1'>$Titulo</a></b></font></p>";    
    
    echo "<table width='100%' border='1'>";

    echo "<tr>";
  
      echo "<td>$Gfont"; 
	
            cTable('100%','0');

            cInput("<b>Objetivo: </b>","Text","40","Observaciones","left",$Cpo[objetivo],"40",false,true,'');
      		echo "<td>$Gfont</td>"; 
            cInput("<b>Condiciones: </b>","Text","40","Condiciones","left",$Cpo[condiciones],"40",false,true,'');
      		echo "<td>$Gfont</td>"; 
            cInput("<b>Contenido: </b>","Text","40","Contenido","left",$Cpo[contenido],"40",false,true,'');
			echo "<td>$Gfont</td>"; 
            cInput("<b>Observaciones: </b>","Text","40","Observaciones","left",$Cpo[observaciones],"40",false,true,'');

            cTableCie();
            
           echo "<p align='center'><b><a class='pg' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></b></p>";
            
      
      echo "</td>";
    echo "</tr>";
    echo "</table>";
    
echo "</body>";

echo "</html>";
*/
?>