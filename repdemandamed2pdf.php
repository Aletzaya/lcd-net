<?php
 function fetch_data()  
 {  
session_start();

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require ("config.php");	

require("lib/lib.php");

$link=conectarse();

  $FechaI	=	$_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
  }

  $FechaF	=	$_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
	  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }
  
  if (!isset($_REQUEST[filtro])){
      $filtro = '*';
  }else{
	  $filtro    = $_REQUEST[filtro];       
  }

  if (!isset($_REQUEST[filtro3])){
      $filtro3 = '*';
  }else{
	  $filtro3    = $_REQUEST[filtro3];       
  }

  if (!isset($_REQUEST[filtro5])){
      $filtro5 = '*';
  }else{
	  $filtro5    = $_REQUEST[filtro5];       
  }

  if (!isset($_REQUEST[filtro7])){
      $filtro7 = '*';
  }else{
	  $filtro7    = $_REQUEST[filtro7];       
  }

  if (!isset($_REQUEST[filtro9])){
      $filtro9 = '*';
  }else{
	  $filtro9    = $_REQUEST[filtro9];       
  }

 if($filtro<>'*'){
 	$filtro2="and med.clasificacion='$filtro'";
 }else{
	$filtro2=" ";
 }

 if($filtro3<>'*'){
 	$filtro4="and ot.suc='$filtro3'";
 }else{
	$filtro4=" ";
 }

 if($filtro5<>'*'){
 	$filtro6="and med.promotorasig='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and med.zona='$filtro7'";
 }else{
	$filtro8=" ";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and med.status='$filtro9'";
 }else{
	$filtro10=" ";
 }


 if($filtro3=='*'){
	 $Sucursal='sucursalt';
 }elseif($filtro3==0){
 	 $Sucursal='sucursal0';
 }elseif($filtro3==1){
 	 $Sucursal='sucursal1';
 }elseif($filtro3==2){
 	 $Sucursal='sucursal2';
 }elseif($filtro3==3){
 	 $Sucursal='sucursal3';
 }elseif($filtro3==4){
 	 $Sucursal='sucursal4';
 }

  $Titulo = "Demanda de afluencia de pacientes por medico del $FechaI al $FechaF";

	$cOtA="select med.medico,med.nombrec,date_format(ot.fecha,'%Y-%m') as fecha,count(*),med.especialidad,med.promotorasig,
	med.clasificacion,med.zona,med.status,ot.suc,sum(ot.importe) as importe from ot,med
	WHERE ot.medico=med.medico $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF'
	GROUP BY ot.medico,date_format(ot.fecha,'%Y-%m') order by ot.medico, date_format(ot.fecha,'%Y-%m')";

  $OtA  = mysql_query($cOtA,$link);

  $Mes  = array("","Ene","Feb","Mzo","Abr","May","Jun","Jul","Agos","Sept","Oct","Nov","Dic");

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

  require ("config.php");


  
  
  $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
  $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
  $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
  $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";


  $output = '<table align="center" border="0">
  <tr bgcolor="#a2b2de">
  <td align="center"  width="60">Medico</td>
  <td align="center"  width="200">Nombre</td>
  <td align="center"  width="100">Especialidad</td>
  <td align="center"  width="70"> Promotor</td>';

  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
      if (substr($i, 4, 2) == "13") {
          $i = $i + 88;
      }
      $x = substr($i, 4, 2) * 1;
      $output = $output.'<td align="center"  width="30">'.$Mes[$x].'</td>';
      $Cmes+=1;
  }

$output.='<td align="center" width="30">Total</td>
<td align="center" width="30">Prom</td>
  <td align="center" width="70">Venta</td>
  <td align="center"  width="40">Clsf</td>
  <td align="center" width="80">Zona</td></tr>';



  $Med = "XXX";
  while ($reg = mysql_fetch_array($OtA)) {
      if ($reg[medico] <> $Med) {

          if( ($nRng % 2) > 0 ){
              $Fdo='FFFFFF';}
              else{
                  $Fdo=$Gfdogrid;
                }    //El resto de la division;
          if ($Med <> 'XXX') {
              $output1 = '';
              $SubT = 0;
              
              for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                  if (substr($i, 4, 2) == '13') {
                      $i = $i + 88;
                  }

                  
                  $x = substr($i, 4, 2) * 1;

                  $output1= $output1.'<td align="center"  width="30">'.number_format($aCnt[$x],'0').'</td>';

                  $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                  $SubT += $aCnt[$x];
                  $GraT += $aCnt[$x];
              }
              
              $Promedio= $SubT/$Cmes;
              
              $cOtB= "select medico,count(*),date_format(pgs.fecha,'%Y%m') from pgs
              WHERE pgs.medico='$Med' and date_format(pgs.fecha,'%Y%m') Between '$Ini' And '$Fin'";
              
              $OtB  = mysql_fetch_array(mysql_query($cOtB,$link));

              $output = $output.'<tr bgcolor='.$Fdo.'>
              <td align="center"  width="60">'.$Med.'</td>
              <td  align="left" width="150">'.$Nombre.'</td>
              <td aling="rigth"  width="50">'.$Status.'</td>
              <td  align="left"width="100">'. $Esp.'</td>
              <td  width="70">'.$Promotor.'</td>';
             
              $output = $output. $output1. '<td align="center"  width="30">'.number_format($SubT,'0').'</td>
              <td align="center"  width="30">'.number_format($Promedio,'2').'</td>
              <td align="right"  width="70">'.number_format($Venta,'2').'</td>
              <td align="center"  width="40">'. $clasificacion.'</td>
              <td align="left"  width="80">'.$zona.'</td></tr>';
              $VentaT+=$Venta;				
              $Venta = 0;
              $Tvisit+=$OtB[1];
          }




          
          $Med = $reg[medico];
          $Esp = $reg[especialidad]; 
          $Nombre = $reg[nombrec];
          $Status = $reg[status];
          $Promotor = $reg[promotorasig];
          $clasificacion = $reg[clasificacion];
          $ZnaA2=mysql_fetch_array(mysql_query("SELECT zona,descripcion FROM zns where zns.zona=$reg[zona]"));
          $zona = $ZnaA2[descripcion];
          $aCnt = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");	
          $nRng++;
        }
        $Fec = $reg[fecha];
        $Pos = 0 + substr($Fec, 5, 2);
        $aCnt[$Pos] = $reg[3];
		$Venta+=$reg[importe];
    }



    	
    $output1 = '';
    $SubT = 0;

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
		$cOtC= "select medico,count(*),date_format(pgs.fecha,'%Y%m') from pgs
		WHERE pgs.medico='$Med' and date_format(pgs.fecha,'%Y%m')='$i'";
		
		$OtC  = mysql_fetch_array(mysql_query($cOtC,$link));
		
        $x = substr($i, 4, 2) * 1;

        $output1= $output1.'<td align="center"  width="30">'.number_format($aCnt[$x],'0').'</td>';

        $SubT+=$aCnt[$x];
        $GraT+=$aCnt[$x];
		$tCnt[$x] = $tCnt[$x] + $aCnt[$x];
    }
	$Promedio= $SubT/$Cmes;
				
	$cOtB= "select medico,count(*) from pgs
	WHERE pgs.medico='$Med' and pgs.fecha Between '$FechaI' And '$FechaF'";

	if( ($nRng % 2) > 2 ){
        $Fdo='FFFFFF';
    }
    else{
        $Fdo=$Gfdogrid;
    
    }    //El resto de la division;
	$OtB  = mysql_fetch_array(mysql_query($cOtB,$link));



    $output = $output.'<tr bgcolor='.$Fdo.'>
    <td align="center"  width="60">'.$Med.'</td>
    <td  align="left"width="150" >'.$Nombre.'</td>
    <td aling="rigth"  width="50">'.$Status.'</td>
    <td  align="left"width="100">'. $Esp.'</td>
    <td  width="70">'.$Promotor.'</td>';

    $output = $output. $output1. '<td align="center"  width="30">'.number_format($SubT,'0').'</td>
    <td align="center"  width="30">'.number_format($Promedio,'2').'</td>
    <td align="right"  width="70">'.number_format($Venta,'2').'</td>
    <td align="center"  width="40">'. $clasificacion.'</td>
    <td align="left"  width="80">'.$zona.'</td></tr>';



	$Tvisit+=$OtB[1];
    $output1 = '';
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }

        $x = substr($i, 4, 2) * 1;
		
       $output1= $output1. '<td align="center" width="30">'.number_format($tCnt[$x],'0').'</td>';
    }

	$PromedioG= $GraT/$Cmes;
	$VentaT+=$Venta;


    $output = $output.'<tr bgcolor="#a2b2de" aling="center">
    <td  width="60"><b> &nbsp; Totales: </b></td>
    <td align="left" width="200" ><b>&nbsp; Medicos --> '.$nRng.'</b></td>
	<td width="100"></td>
    <td width="70" >&nbsp;</td>';

    $output = $output. $output1.'
    <td align="center" width="30">'.number_format($GraT,'0').'</td>
	<td align="center" width="30">'.number_format($PromedioG/$GraT,'2').'</td>
    <td align="right" width="70">'.number_format($VentaT,'2').'</td>
	<td align="right" width="40"></td>
	<td align="right" width="80"></td></tr>';




    $output = $output. '</table>';  
  return $output; 
}








$Usr=$check['uname'];
$doc_title    = "Imprimir demanda pac/med";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

// ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

//Page header
function Header() {
global $FechaI,$FechaF;

$FechaI       = $_REQUEST[FechaI];
$FechaF       = $_REQUEST[FechaF];
$Fecha        = date('Y-m-d H:i');
// Logo
//$image_file = K_PATH_IMAGES.'logo_example.jpg';
$image_file2 = 'lib/DuranNvoBk.png';
$this->Image($image_file2, 8, 5, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

$this->SetFont('helvetica', '', 9);

$this->writeHTML('<table width="100" border="0"><tr><th width="78" height="15"></th><th width="610"><font size="10"><b>Laboratorio Clinico Duran S.A. de C.V.</b></font></th></tr><tr><th width="78" height="15"></th><th width="610">Fray Pedro de Gante Norte No 108 Texcoco de Mora Cp.56100</th></tr><tr><th width="78" height="18">&nbsp;</th><th width="610"><b>Demanda de afluencia de pacientes por medico del '.$FechaI.' al '.$FechaF.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fecha/Hora: '.$Fecha.'</b></th></tr></table>', false, 0);

}

// Page footer
function Footer() {
// Position at 15 mm from bottom
$this->SetY(-15);
// Set font
$this->SetFont('helvetica', 'I', 8);
// Page number
$this->Cell(0, -35, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

}
}

$obj_pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
$obj_pdf->SetCreator(PDF_CREATOR);  
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
$obj_pdf->SetDefaultMonospacedFont('helvetica');  
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '25', PDF_MARGIN_RIGHT);  
$obj_pdf->SetAutoPageBreak(TRUE, 22);  
$obj_pdf->SetFont('helvetica', '', 8);  
$obj_pdf->AddPage('L','letter'); 
define ("PDF_MARGIN_TOP", 10);
define ("PDF_MARGIN_BOTTOM", 18); 
define ("PDF_HEADER_TITLE", "Demanda Comparativa por Estudio");

//   $content = '<table width="750" border="1" cellpadding="0" cellspacing="0" align="center">';  
$content .= fetch_data();  
//   $content .= '</table>';  
$obj_pdf->writeHTML($content); 

ob_end_clean(); 

$obj_pdf->Output('comparativo.pdf', 'I');  

?>  
