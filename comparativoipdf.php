<?php
 function fetch_data()  
 {  
  session_start();

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");


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
	  echo '<script language="javascript">alert("Fechas 1 incorrectas... Verifique");</script>'; 
  }

  $FechaI2	=	$_REQUEST[FechaI2];

  if (!isset($FechaI2)){
      $FechaI2 = date("Y-m-d",strtotime($FechaI2."- 1 year"));
  }

  $FechaF2	=	$_REQUEST[FechaF2];

  if (!isset($FechaF2)){
      $FechaF2 = date("Y-m-d",strtotime($FechaF2."- 1 year"));
  }

  if ($FechaI2>$FechaF2){
	  echo '<script language="javascript">alert("Fechas 2 incorrectas... Verifique");</script>'; 
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
 	$filtro6="and ot.institucion='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and est.depto='$filtro7'";
 }else{
	$filtro8=" ";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and inst.condiciones='$filtro9'";
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

  $Titulo = "Demanda Comparativa por Institucion";

	$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(DISTINCT ot.orden) as cant2, est.depto, ot.institucion
	FROM otd, est, ot
	WHERE otd.estudio = est.estudio and ot.fecha>='$FechaI' and ot.fecha<='$FechaF' and ot.orden=otd.orden $filtro4 $filtro8
	GROUP BY ot.institucion Order BY otd.estudio";

	$OtA  = mysql_query($cSql,$link);

	$cSql2="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(DISTINCT ot.orden) as cant2, est.depto, ot.institucion
	FROM otd, est, ot
	WHERE otd.estudio = est.estudio and ot.fecha>='$FechaI2' and ot.fecha<='$FechaF2' and ot.orden=otd.orden $filtro4 $filtro8
	GROUP BY ot.institucion Order BY otd.estudio";

	$OtA2 = mysql_query($cSql2,$link);

 // $est2="SELECT inst.institucion,inst.alias FROM inst where inst.status='ACTIVO' ORDER BY inst.institucion ASC";
  $est2="SELECT inst.institucion,inst.alias FROM inst ORDER BY inst.institucion ASC";

  	$est3  = mysql_query($est2,$link);

  require ("config.php");

  
//********** DATOS    **********//


$Gfon = '<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#444444">';
$Gfont = '<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#444444">';
$Gfon2 = '<font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="#000000">';

$output = '<table align="center" width="100%" border="0">
<tr bgcolor="#a2b2de">
<td align="center" WIDTH="230" colspan="4"></td>
<td align="center" colspan="3"> <b>De '.$FechaI.' al '.$FechaF.'</b></td>
<td align="center" colspan="3"> <b>De '.$FechaI2.' al '.$FechaF2.'</b></td>
<td align="center" colspan="3"> <b>Diferencia</b></td></tr>

<tr bgcolor="#a2b2de">
<td align="center" WIDTH="60" colspan="2"> <b>Institucion</b></td>
<td align="center"  WIDTH="170" colspan="2"> <b>Descripcion</b></td>
<td align="center"> <b>Ordenes</b></td>
<td align="center"> <b>Cant</b></td>
<td align="center"> <b>Importe</b></td>
<td align="center"> <b>Ordenes</b></td>
<td align="center"> <b>Cant</b></td>
<td align="center"> <b>Importe</b></td>
<td align="center"> <b>Cant/Ord</b></td>
<td align="center"> <b>Cantidad</b></td>
<td align="center"> <b>Importe</b></td></tr>';

    $canti1=0;

	$importotal1=0;

	$estudio1='';


	$canti2=0;

	$importotal2=0;

	$estudio2='';


    

  	while ($reg1 = mysql_fetch_array($est3)) {


  		while ($reg2 = mysql_fetch_array($OtA)) {

  			$institucion = $reg2[institucion];

  			if($reg1[institucion]==$institucion){

  				$estudio1=$reg2[estudio];

  				$canti1=$reg2[cant];

  				$cantit1=$cantit1+$reg2[cant];

          $canti3=$reg2[cant2];

          $cantit3=$cantit3+$reg2[cant2];


  				$importotal1=$reg2[precio]-$reg2[descuento];

  				$importotalt1=$importotalt1+$importotal1;

  			}
  		}
    

  		while ($reg3 = mysql_fetch_array($OtA2)) {

  			$institucions = $reg3[institucion];

  			if($reg1[institucion]==$institucions){

  				$estudio2=$reg3[estudio];

  				$canti2=$reg3[cant];

  				$cantit2=$cantit2+$reg3[cant];

          $canti4=$reg3[cant2];

          $cantit4=$cantit4+$reg3[cant2];


  				$importotal2=$reg3[precio]-$reg3[descuento];

  				$importotalt2=$importotalt2+$importotal2;

  			}
        }


  		 if($canti1<>0 or $canti2<>0){

  			if( ($nRng % 2) > 0 ){
                  $Fdo='FFFFFF';
                }
                  else{
                      $Fdo=$Gfdogrid;
                }    //El resto de la division;

                      $difcant=$canti1-$canti2;
                      $difcant2=$canti3-$canti4;
  		              $difimport=$importotal1-$importotal2;


          $output .= '<tr bgcolor='.$Fdo.'>
               <td align="center"  WIDTH="60" colspan="2" >'.$reg1[institucion].'</td>
              <td align="left"  WIDTH="170" colspan="2">'.$reg1[alias].'</td>
              <td align="center">'.$canti3.'</td>
              <td align="center">'.$canti1.'</td>
              <td align="right">'.number_format($importotal1,'2').'</td>
              <td align="center">'.$canti4.'</td>
              <td align"center">'.$canti2.'</td>
              <td align="right">'.number_format($importotal2,'2').'</td>
              <td  align="center">'.$difcant2.'</td>
              <td  align="center">'.$difcant.'</td>
               <td align="right">'.number_format($difimport,'2').'</td></tr>';
  
              $nRng++;
          } 

          $estudio1='';
          $canti1=0;
          $canti3=0;
              $importotal1=0;
      
              $estudio2='';
          $canti2=0;
          $canti4=0;
              $importotal2=0;
      
              mysql_data_seek($OtA, 0);
              mysql_data_seek($OtA2, 0);
        }

            $cantit5=$cantit1-$cantit2;
          $cantit6=$cantit3-$cantit4;
            $importotalt3=$importotalt1-$importotalt2;

            $output .= '<tr bgcolor="#a2b2de">
            <td align="center" WIDTH="60" colspan="2"></td>
            <td align="right"  WIDTH="170"colspan="2"><b>Totales --------> </b></td>
            <td align="center"><b>'.$cantit3.'</b></td>
            <td align="center"><b>'.$cantit1.'</b></td>
            <td align="right"><b>'.number_format($importotalt1,'2').'</b></td>
            <td align="center"><b>'.$cantit4.'</b></td>
            <td align="center"><b>'.$cantit2.'</b></td>
            <td align="right"><b>'.number_format($importotalt2,'2').'</b></td>
            <td align="center"><b>'.$cantit6.'</b></td>
            <td align="center"><b>'.$cantit5.'</b></td>
            <td align="right"><b>'.number_format($importotalt3,'2').'</b></td></tr>';
            
            

            $output .= '</table>';   
            return $output; 
    }
        



$Usr=$check['uname'];
$doc_title    = '$Titulo';
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

        $this->writeHTML('<table width="100" border="0"><tr><th width="78" height="15"></th><th width="610"><font size="10"><b>Laboratorio Clinico Duran S.A. de C.V.</b></font></th></tr><tr><th width="78" height="15"></th><th width="610">Fray Pedro de Gante Norte No 108 Texcoco de Mora Cp.56100</th></tr><tr><th width="78" height="18">&nbsp;</th><th width="610"><b>Demanda Comparativa por Institucion &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fecha/Hora:  &nbsp; &nbsp; '.$Fecha.'</b></th></tr></table>', false, 0);

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
      define ("PDF_HEADER_TITLE", "Demanda Comparativa por Institucion");

   //   $content = '<table width="750" border="1" cellpadding="0" cellspacing="0" align="center">';  
      $content .= fetch_data();  
   //   $content .= '</table>';  
      $obj_pdf->writeHTML($content); 

      ob_end_clean(); 

      $obj_pdf->Output('comparativo.pdf', 'I');  
 

mysql_close();
?>
