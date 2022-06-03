<?php   
 function fetch_data()  
 {  
  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $FechaI = $_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
  }

  $FechaF = $_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
    echo '<script language="javascript">alert("Fechas 1 incorrectas... Verifique");</script>'; 
  }

  $FechaI2  = $_REQUEST[FechaI2];

  if (!isset($FechaI2)){
      $FechaI2 = date("Y-m-d",strtotime($FechaI2."- 1 year"));
  }

  $FechaF2  = $_REQUEST[FechaF2];

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

  $Status2 = $_REQUEST[Status2];


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

  $Titulo = "Demanda Comparativa por Estudio";

  $cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(ot.orden), est.depto,est.subdepto 
  FROM otd, est, ot
  WHERE otd.estudio = est.estudio and ot.fecha>='$FechaI' and ot.fecha<='$FechaF' and ot.orden=otd.orden $filtro4 $filtro6 $filtro8
  GROUP BY est.subdepto  Order BY est.subdepto ";

  $OtA  = mysql_query($cSql,$link);

  $cSql2="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(ot.orden), est.depto,est.subdepto 
  FROM otd, est, ot
  WHERE otd.estudio = est.estudio and ot.fecha>='$FechaI2' and ot.fecha<='$FechaF2' and ot.orden=otd.orden $filtro4 $filtro6 $filtro8
  GROUP BY est.subdepto  Order BY est.subdepto ";

  $OtA2 = mysql_query($cSql2,$link);

  $est2="SELECT est.depto,est.subdepto FROM est where est.activo='Si' ORDER BY est.depto,est.subdepto ASC";

    $est3  = mysql_query($est2,$link);

  require ("config.php");

//********** DATOS    **********//

    $Gfon = '<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#444444">';
    $Gfont = '<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#444444">';
    $Gfon2 = '<font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="#000000">';

    $output = '<table align="center" width="100%" border="0"><tr bgcolor="#a2b2de"><td align="center" colspan="2">$Gfon </td><td align="center" colspan="2">$Gfon <b>De $FechaI al $FechaF</b></td><td align="center" colspan="2">$Gfon <b>De $FechaI2 al $FechaF2</b></td><td align="center" colspan="2">$Gfon <b>Diferencia</b></td></tr><tr bgcolor="#a2b2de"><td align="center" colspan="2">$Gfon <b>Subdepartamento</b></td><td align="center">$Gfon <b>Cant</b></td><td align="center">$Gfon <b>Importe</b></td><td align="center">$Gfon <b>Cant</b></td><td align="center">$Gfon <b>Importe</b></td><td align="center">$Gfon <b>Cantidad</b></td><td align="center">$Gfon <b>Importe</b></td></tr></table>';

        return $output;  

  }  

$Usr=$check['uname'];
$doc_title    = "$Titulo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');

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
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_file2 = 'lib/logo.jpg';
        $this->Image($image_file2, 8, 5, 25, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', '', 9);

        $this->writeHTML('<table width="100" border="0"><tr><th width="78" height="15"></th><th width="610"><font size="10"><b>Operadora Hospital Futura S.A. de C.V.</b></font></th></tr><tr><th width="78" height="15"></th><th width="610">Fray Pedro de Gante Norte No 320 Texcoco de Mora Cp.56100</th></tr><tr><th width="78" height="15">&nbsp;</th><th width="610"><b>Corte de Caja General del '.$FechaI.' al '.$FechaF.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fecha/Hora:  &nbsp; &nbsp; '.$Fecha.'</b></th></tr></table>', false, 0);

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
      define ("PDF_HEADER_TITLE", "Corte de Caja");

   //   $content = '<table width="750" border="1" cellpadding="0" cellspacing="0" align="center">';  
      $content .= fetch_data();  
   //   $content .= '</table>';  
      $obj_pdf->writeHTML($content); 
      ob_end_clean(); 
      $obj_pdf->Output('Comparativo_resumen.pdf', 'I');  
 
 ?>  
