<?php

session_start();

require("lib/lib.php");

$link=conectarse();

date_default_timezone_set("America/Mexico_City");

$Fecha=date("Y-m-d");

$Hora=date("H:i");

$FechaI = $_REQUEST[FechaI];

if (!isset($FechaI)){
    $FechaI = date("Y-m-d");
}

$FechaF = $_REQUEST[FechaF];

if (!isset($FechaF)){
    $FechaF = date("Y-m-d");
}

if ($FechaI>$FechaF){
  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
}

$Institucion   = $_REQUEST[institucion];

$Resumen =$_REQUEST[resumen];

if (!isset($_REQUEST[institucion])){
  $Institucion = '*';
}else{
  $Institucion    = $_REQUEST[institucion];       
}

if (!isset($_REQUEST[filtro3])){
  $filtro3 = '*';
}else{
  $filtro3    = $_REQUEST[filtro3];       
}

if($filtro3=='*'){
 $Sucursal=' ';
}elseif($filtro3==0){
 $Sucursal='and ot.suc=0';
}elseif($filtro3==1){
 $Sucursal='and ot.suc=1';
}elseif($filtro3==2){
 $Sucursal='and ot.suc=2';
}elseif($filtro3==3){
 $Sucursal='and ot.suc=3';
}elseif($filtro3==4){
 $Sucursal='and ot.suc=4';
}elseif($filtro3==5){
 $Sucursal='and ot.suc=5';
}elseif($filtro3==6){
 $Sucursal='and ot.suc=6';
}

//  $lU    = mysql_query("DELETE FROM rep");
if($Resumen == Resumen){

  if($Institucion == "*" OR $Institucion==''){
   
     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico $Sucursal
     GROUP BY med.estado,med.munconsultorio");
                    
     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF";
     $Resumen='Resumen';
          
  }else{

     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico AND ot.institucion='$Institucion' $Sucursal
     GROUP BY med.estado,med.munconsultorio");
   
     $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Ins'");
     $cIns  = mysql_fetch_array($cInsA);

     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF Institucion: $cIns[alias]";
     $Resumen='Resumen';

  }

}else{

  if($Institucion == "*" OR $Institucion==''){
   
     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, med.locconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico $Sucursal
     GROUP BY med.estado,med.munconsultorio, med.locconsultorio");
                    
     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF";
          
  }else{

     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, med.locconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico AND ot.institucion='$Institucion' $Sucursal
     GROUP BY med.estado,med.munconsultorio, med.locconsultorio");
   
     $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Ins'");
     $cIns  = mysql_fetch_array($cInsA);

     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF Institucion: $cIns[alias]";

  }

}


$SucA=mysql_query("SELECT id,alias FROM cia order by id");
echo "<option value='*'>Todos*</option>";
while($Suc=mysql_fetch_array($SucA)){
  echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
  if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
  if($filtro3=='*'){$DesSuc=' Todos';}
}



$doc_title    = "Imprimir Medicos por Entidad ";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');




 $Titulo1 = "Medicos por entidad federativa del $FechaI al $FechaF";




// ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2;

    $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
    $NomI    = mysql_fetch_array($InstA);

    $Fecha   = date("Y-m-d");
    $Hora=date("H:i");

    // Logo
    $image_file2 = 'lib/DuranNvoBk.png';
    $this->Image($image_file2, 8, 5, 65, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    $this->SetFont('helvetica', 'B', 11);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800"></td></tr><tr><td width="30"></td><td width="800">Laboratorio Clínico Duran</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Fecha/Hora: '.$Fecha.' - '. $Hora.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.'</td></tr></table>', false, 0);



    }

    // Page footer
    function Footer() {

        // Position at 15 mm from bottom
        $this->SetY(-10);
       // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

// set document information 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "letter");

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 35);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Resultado".$Estudio);

//set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

$pdf->setLanguageArray($l); //set language items
//initialize document
$pdf->AliasNbPages();

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

$lBd=false;


$pdf->SetFont('Helvetica', '', 7, '', 'false');

$output = '<table align=" center" width="100%" border="0" cellspacing="4" cellpadding="3">
  <tr bgcolor="#a2b2de">
  <td><b>Estado</b></td>
  <td><b>Municipio</b></td>
  <td><b>Colonia</b></td>
  <td align="center"><b>Cantidad</b></td>
  <td align="center"><b>Importe</b></td>
  </tr>';

 
  while($rg = mysql_fetch_array($OtA)){


    if (($nRng % 2) > 0) {
        $Fdo = '#FFFFFF';
    } else {
        $Fdo = '#D5D8DC';
    }
   //El resto de la division;

      $output .= '<tr bgcolor='.$Fdo.' >
		<td>'.$rg[estado].'</td>
		<td>'.$rg[munconsultorio].'</td>
		<td>'.$rg[locconsultorio].'</td>

		<td align="right">'.$rg[cantidad].'</td>
    <td align="right">'. number_format($rg[importe],"2").'</td>
	  </tr>';

    $importet += $rg[importe];
    $nCnt  += $rg[cantidad];
    $nRng++;
		
  }
  
  $output .= '<tr bgcolor="#a2b2de">
  <td> </td>
  <td> </td>
  <td><b>Total ---> </b></td>
  <td align="right"><b>'.number_format($nCnt,"0").'</b></td>
  <td align="right"><b>'.number_format($importet,"2").'</b></td>

  </tr></table>';

  $pdf->writeHTML($output,false,false,true,false,'C');

            
ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>

