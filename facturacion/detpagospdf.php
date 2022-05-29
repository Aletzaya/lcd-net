<?php

  session_start();


  session_start();


  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  
  require("lib/lib.php");
  
  
  require ("config.php");	

  $link=conectarse();
  
  $FechaI    = $_REQUEST[FechaI];
  $FechaF    = $_REQUEST[FechaF];
  $Pagos     = $_REQUEST[Pagos];
  $Usr       = $_REQUEST[Usr];
  $Fpago     = $_REQUEST[Fpago];
  
  $Titulo  = "Pagos del $FechaI al $FechaF";   
  $Fecha=date("Y-m-d H:i");
  ?>
  <html>
  
  <head>
  <meta charset="UTF-8">
  <title>Sistema de Laboratorio clinico</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>
  
  
  
  <?php

  $Sucursal     =   $_REQUEST[Sucursal];
  //$Sucursal     =   $Sucursal[0];
  $sucursalt = $_REQUEST[sucursalt];
  $sucursal0 = $_REQUEST[sucursal0];
  $sucursal1 = $_REQUEST[sucursal1];
  $sucursal2 = $_REQUEST[sucursal2];
  $sucursal3 = $_REQUEST[sucursal3];
  $sucursal4 = $_REQUEST[sucursal4];
  $sucursal5 = $_REQUEST[sucursal5];
  $sucursal6 = $_REQUEST[sucursal6];

    $Sucursal= "";
  
  if($sucursalt=="1"){  
  
    $Sucursal="dpag_ref.suc<>9";
    $Sucursal2= " * - Todas ";
  }else{
  
    if($sucursal0=="1"){  
      $Sucursal= " dpag_ref.suc=0";
      $Sucursal2= "Administracion - ";
    }
    
    if($sucursal1=="1"){ 
      $Sucursal2= $Sucursal2 . "Matriz - "; 
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=1";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=1";
      }
    }
    
    if($sucursal2=="1"){
      $Sucursal2= $Sucursal2 . "Hospital Futura - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=2";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=2";
      }
    }
    
    if($sucursal3=="1"){
      $Sucursal2= $Sucursal2 . "Tepexpan - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=3";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=3";
      }
    }
    
    if($sucursal4=="1"){
      $Sucursal2= $Sucursal2 . "Los Reyes - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=4";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=4";
      }
    }    

    if($sucursal5=="1"){
      $Sucursal2= $Sucursal2 . "Camrones - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=5";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=5";
      }
    }
    if($sucursal6=="1"){
      $Sucursal2= $Sucursal2 . "San Vicente - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " dpag_ref.suc=6";
      }else{
        $Sucursal= $Sucursal . " OR dpag_ref.suc=6";
      }
    }
  }
  $doc_title    = "Relacion de Ordenes de trabajo";
  $doc_subject  = "recibos unicode";
  $doc_keywords = "keywords para la busqueda en el PDF";
  
  require_once('tcpdf/config/lang/eng.php');
  require_once('tcpdf/tcpdf.php');


  $cSql ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
  . "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
  . "FROM dpag_ref "
  . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
  . "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
  . "WHERE cptpagod.referencia LIKE '%$Pagos%' "
  . "AND date(dpag_ref.fechapago)>='$FechaI' "
  . "AND date(dpag_ref.fechapago)<='$FechaF' "
  . "AND dpag_ref.usr LIKE '%$Usr%' "
  . "AND dpag_ref.tipopago LIKE '%$Fpago%'"
  . "AND dpag_ref.cancelada LIKE '%$_REQUEST[Cancelado]%'"
  . "AND ($Sucursal)";

//echo $cSql;
$sql = mysql_query($cSql);


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

    $this->SetFont('Helvetica', 'BI', 8);

    $this->writeHTML('<br><br><br><table align="center" width="2150" border="1" cellspacing="1" cellpadding="0">
    <tr>
    <td align="center" bgcolor="#808B96" width="40">Suc</td>
    <td align="center" bgcolor="#808B96" width="60">Id</td>
    <td align="center" bgcolor="#808B96" width="150">Referencia</td>
    <td align="center" bgcolor="#808B96" width="100">Cuenta</td>
    <td align="center" bgcolor="#808B96" width="80">Fecha</td>
    <td align="center" bgcolor="#808B96" width="140">Recibe</td>
    <td align="center" bgcolor="#808B96" width="140">Autoriza</td>
    <td align="center" bgcolor="#808B96" width="140">Usr</td>
    <td align="center" bgcolor="#808B96" width="50">Hospi</td>
    <td align="center" bgcolor="#808B96" width="140">Concepto</td>
    <td align="center" bgcolor="#808B96" width="140">Importe</td>
    </tr></table><br>', false, 0);




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


while($rg=mysql_fetch_array($sql)){          



  while($rg = mysql_fetch_array($OtA)){

    if( ($nRng % 2) > 0 ){
        $Fdo='FFFFFF';
      }
        else{
            $Fdo=$Gfdogrid;
      }    //El resto de la division;

            $pdf->SetFont('Helvetica', '', 7, '', 'false');


            $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
            <tr bgcolor='.$Fdo.'>
            <td width="40"> '. $rg[suc] .'</td> 
            <td width="60"> '. $rg[id] .'</td> 
            <td width="150"> '. $rg[referencia].'</td>
            <td width="100">'. $rg[cuenta].'</td>
            <td width="80">'.$rg[fechapago].' </td>	
            <td width="140"> '.$rg[concepto].' </td>
            <td width="140"> '.ucwords($rg[recibe]).'</td>
            <td width="140"> '.$rg[autoriza].'</td>
            <td width="50">'.$rg[usr].' </td>
            <td width="140"> '.$rg[concept] .'</td>
            <td width="140">'.number_format($rg[monto],2).'</td>	
            </tr></table>';

            $pdf->writeHTML($html,false,false,true,false,'C');

            $nImporte+= $rg[monto];              
            $nRng ++;        
            
            
            
      }     
 
    $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
    <tr bgcolor='.$Fdo.'>
    <td width="40"></td>  
     <td width="60"></td>  
     <td width="150"></td>
     <td width="100"></td>
     <td width="80"></td>
     <td width="140"></td>
     <td width="140"></td>
     <td width="140"></td>
     <td width="50"></td>	
     <td width="140" bgcolor="#808B96" >Total</td>	
     <td width="140" bgcolor="#808B96">'.number_format($nImporte,2).'</td>
     </tr></table>';

     $pdf->writeHTML($html,false,false,true,false,'C');





ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>