<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

  $link=conectarse();

  $Institucion=$_REQUEST[Institucion];

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
  if($filtro<>'MR'){
    $filtro2="and ot.medico='$filtro'";
  }else{
    $filtro2="and (ot.medico<>'AQ' and ot.medico<>'MD')";
  }
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
 }elseif($filtro3==5){
   $Sucursal='sucursal5';
 }elseif($filtro3==6){
   $Sucursal='sucursal6';
 }


 $doc_title    = "Relacion de Ordenes de trabajo";
 $doc_subject  = "recibos unicode";
 $doc_keywords = "keywords para la busqueda en el PDF";
 require_once('tcpdf/config/lang/eng.php');
 require_once('tcpdf/tcpdf.php');
 //require_once('tcpdf2/tcpdf_include.php');

 
  $Titulo = "Productividad de Recepcion del $FechaI al $FechaF";

  $cOtA="select ot.recepcionista,date_format(ot.fecha,'%Y-%m') as fecha,count(*),
  ot.suc,ot.institucion,sum(ot.importe) as importe from ot
  WHERE ot.fecha Between '$FechaI' And '$FechaF' $filtro2 $filtro4 $filtro6
  GROUP BY ot.recepcionista,date_format(ot.fecha,'%Y-%m') order by ot.recepcionista, date_format(ot.fecha,'%Y-%m')";

  $OtA  = mysql_query($cOtA,$link);

  $Mes  = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
  $tImp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

  require ("config.php");

  //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
  $Usr=$check['uname'];
  $doc_title    = "Productividad Recepcion";
  $doc_subject  = "recibos unicode";
  $doc_keywords = "keywords para la busqueda en el PDF";
  
    require_once('tcpdf/config/lang/eng.php');
    require_once('tcpdf/tcpdf.php');
  
  //create new PDF document (document units are set by default to millimeters)
  //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
  // Extend the TCPDF class to create custom Header and Footer
  
  $Fecha        = date('Y-m-d H:i');
  
  // ********************  E N C A B E Z A D O  ****************
  
  
  class MYPDF extends TCPDF {
  
      //Page header
      function Header() {
        global $FechaI,$FechaF,$Fecha;
          // Logo
          //$image_file = K_PATH_IMAGES.'logo_example.jpg';
          //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
          $image_file2 = 'lib/DuranNvoBk.png';
          $this->Image($image_file2, 8, 5, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
  
  
          $this->SetFont('helvetica', '', 9);
  
  
          $this->writeHTML('<table width="100" border="0"><tr><th width="78" height="15"></th>
          <th width="610"><font size="10"><b>Laboratorio Clinico Duran S.A. de C.V.</b></font></th></tr>
          <tr>
          <th width="78" height="15"></th>
          <th width="610">Fray Pedro de Gante Norte No 108 Texcoco de Mora Cp.56100</th>
          </tr>
          <tr>
          <th width="78" height="18">&nbsp;</th>
          <th width="800"><b>Productividad de Recepcion del '.$FechaI.' al '.$FechaF.'</b></th></tr></table>', false, 0);
  
  
    $Mes  = array("","ENE","FEB","MZO","ABR","MAY","JUN","JUL","AGO","SEPT","OCT","NOV","DIC");
    $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
    $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
  
    $Tit1 = '<br><br><br><table border="1" width="99.2%"><tr bgcolor="#a2b2de" align="center"><th height="30"><b>RECEPCIONISTA</b></th>';
    $Tit4 = '<table border="1" width="99.2%"><tr bgcolor="#a2b2de"><th align="center"></th>';


	for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
	    if (substr($i, 4, 2) == '13') {
	        $i = $i + 88;
	    }
	    $x = substr($i, 4, 2) * 1;
	    $Tit2 = $Tit2 . '<th colspan="2"><b>'.$Mes[$x].'</b></th>';
        $Tit5 = $Tit5. '<th align="center"><b>Ots</b></th><th align="center"><b>IMPORTE</b></th>';

		$Cmes+=1;
	}

	$Tit3 = '<th align="center" colspan="2"><b>TOTAL</b></th><th align="center" colspan="2"><b>PROMEDIO</b></th></tr></table>';
    $Tit6 = '<th align="center"><b>Ots</b></th><th align="center"><b>IMPORTE</b></th><th align="center"><b>Ots</b></th><th align="center"><b>IMPORTE</b></th></tr></table>';


$tbl = <<<EOD
$Tit1
$Tit2
$Tit3
$Tit4 
$Tit5
$Tit6
EOD;
  
  $this->SetFont('helvetica', '', 7);
  
  $this->writeHTML($tbl, false, 0);
  
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
  
  
  // ******************** F I N     E N C A B E Z A D O  ****************
  
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
  define ("PDF_MARGIN_TOP", 33.9);
  define ("PDF_MARGIN_BOTTOM", 18);
  // Tamaño de la letra;
  define ("PDF_FONT_SIZE_MAIN", 11);
  
  //Titulo que va en el encabezado del archivo pdf;
  define ("PDF_HEADER_TITLE", "Reporte");
  
  //set margins
  $pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
  //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setHeaderFont(Array('helvetica', '', 8));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  //$pdf->setPrintHeader(false);
  //$pdf->setPrintFooter(false);
  
  $pdf->setLanguageArray($l); //set language items
  //initialize document
  $pdf->AliasNbPages();
  
  $pdf->AddPage('L','letter'); //Orientacion P-Vertical L-Horizontal, tamaño de pagina
  
  $pdf->SetFont('Helvetica', '', 7, '', 'false');
  
//***********   D A T O S   ***********


$Mes  = array("","ENE","FEB","MZO","ABR","MAY","JUN","JUL","AGO","SEPT","OCT","NOV","DIC");
$Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
$Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);

  $Tit9 = '<table border="1">';
  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
    if (substr($i, 4, 2) == '13') {
        $i = $i + 88;
    }
    $x = substr($i, 4, 2) * 1;
    $Tit2 = $Tit2 . '<th colspan="2"><b>'.$Mes[$x].'</b></th>';
	$Cmes+=1;
}


  $Recep = 'XXX';

  while ($reg = mysql_fetch_array($OtA)) {
      if ($reg[recepcionista] <> $Recep) {

        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
        if ($Recep <> 'XXX') {
              $cTit = '';
              $SubT = 0;
              $SubTimp = 0;
      
              for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                  if (substr($i, 4, 2) == '13') {
                      $i = $i + 88;
        }

                  $x = substr($i, 4, 2) * 1;


                  if($aCnt[$x]==0){
                    $cantidad='';
                  }else{
                    $cantidad=number_format($aCnt[$x],'0');
                  }
                
                
                  if($aImp[$x]==0){
                    $import='';
                  }else{
                    $import=number_format($aImp[$x],'2');
                  }
                
                
                  $cTit = $cTit . '<th align="center">'.$cantidad.'</th>
                  <th align="right">'.$import.'</th>';


                  $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                  $SubT += $aCnt[$x];
                  $GraT += $aCnt[$x];
                  $tImp[$x] = $tImp[$x] + $aImp[$x];
                  $SubTimp += $aImp[$x];
                  $GraTimp += $aImp[$x];
              }
      
      $Promedio= $SubT/$Cmes;
      $Promedioimp= $SubTimp/$Cmes;

            $html1='<tr style="background-color: '.$Fdo.';color: #000;"><th>'.$Recep.'</th>';

            $html3= $cTit . '<th align="center">'.number_format($SubT,'0').'</th>
            <th align="right">'.number_format($SubTimp,'2').'</th>
            <th align="center">'.number_format($Promedio,'0').'</th>
            <th align="right">'.number_format($Promedioimp,'2').' </th></tr>';
      
            $Tit7=$html1 . $html3;
            $Tit8=$Tit8 . $Tit7;


              $VentaT+=$Venta;        
              $Venta = 0;
          }
          $Recep = $reg[recepcionista];

          $aCnt = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"); 
          $aImp = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"); 
    $nRng++;
  
      }
      $Fec = $reg[fecha];
      $Pos = 0 + substr($Fec, 5, 2);
      $aCnt[$Pos] = $reg[2];
      $aImp[$Pos] = $reg[5];
      $Venta+=$reg[importe];
  }

$Tit10 = '</table>';

  
  $cTit = '';
  $SubT = 0;
  $SubTimp = 0;
  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
      if (substr($i, 4, 2) == '13') {
          $i = $i + 88;
      }
  
  $x = substr($i, 4, 2) * 1;

  if($aCnt[$x]==0){
    $cantidad='';
  }else{
    $cantidad=number_format($aCnt[$x],'0');
  }


  if($aImp[$x]==0){
    $import='';
  }else{
    $import=number_format($aImp[$x],'2');
  }


  $cTit = $cTit . '<th align="center">'.$cantidad.'</th>
  <th align="right">'.$import.'</th>';



      $SubT+=$aCnt[$x];
      $GraT+=$aCnt[$x];
      $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
      $SubTimp+=$aImp[$x];
      $GraTimp+=$aImp[$x];
      $tCntimp[$x] = $tImp[$x] + $aImp[$x];
  }
$Promedio= $SubT/$Cmes;
$Promedioimp= $SubTimp/$Cmes;

$html1='<tr><th>'.$Recep.'</th>';

$html3= $cTit . '<th align="center">'.number_format($SubT,'0').'</th>
<th align="right">'.number_format($SubTimp,'2').'</th>
<th align="center">'.number_format($Promedio,'0').'</th>
<th align="right">$'.number_format($Promedioimp,'2').' </th></tr>';

$Tit7=$html1 . $html3;
$Tit8=$Tit8 . $Tit7;


$cTit = '';
  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
      if (substr($i, 4, 2) == '13') {
          $i = $i + 88;
      }

      $x = substr($i, 4, 2) * 1;


    
    
      $cTit = $cTit . '<th align="center">'.number_format($tCnt[$x],'0').'</th>
      <th align="right">$'.number_format($tCntimp[$x],'2').'</th>';
  
    }
$PromedioG= $GraT/$Cmes;
$PromedioGimp= $GraTimp/$Cmes;
$VentaT+=$Venta;


$html1='<tr bgcolor="#a2b2de"><th height="30">TOTALES:'.$nRng.'</th>';


$html3= $cTit . '<th align="center">'.number_format($GraT,'0').'</th>
<th align="right">'.number_format($GraTimp,'2').'</th>
<th align="center">'.number_format($PromedioG,'0').'</th>
<th align="right">$'.number_format($PromedioGimp,'2').' </th></tr>';

$Tit7=$html1 . $html3;
$Tit8=$Tit8 . $Tit7;





$tbl = <<<EOD
$Tit9
$Tit8
$Tit10
EOD;
$pdf->writeHTML($tbl, true, false, false, false, 'C');


// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("'Reporte'.pdf'", 'I');

?>  