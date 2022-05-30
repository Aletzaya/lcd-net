<?php

  session_start();

  require("lib/importeletras.php");

  require("lib/lib.php");

  $link    = conectarse();

  $OrdenDef     = "";            //Orden de la tabla por default
  $tamPag       = 15;
  $nivel_acceso = 10; // Nivel de acceso para esta p�gina.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $Institucion = $_REQUEST[Institucion];
  $Medico      = $_REQUEST[Medico];
  $Status      = $_REQUEST[Status];
  $PeriodoI    = $_REQUEST[PeriodoI];
  $PeriodoF    = $_REQUEST[PeriodoF];
  $Ruta        = $_REQUEST[Ruta];
  $Importes    = $_REQUEST[Importes];
  

  
  $Titulo  = "Relacion de pago de comisiones del periodo $PeriodoI al $PeriodoF";
  $Titulo1  = "Fray Pedro de Gante Norte No 320";

  if(isset($Ruta)){
  

	  if($Medico=='*'){

   		$cSql = "SELECT cmc.inst,cmc.medico,sum(cmc.importe) as importe,sum(cmc.comision) as comision,
         med.nombrec as nommedico,med.nombrec,count(*) as ordenes,
         sum(numestudios) as estudios
   		FROM cmc,med
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''  
   	   AND (cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4'
	      OR cmc.inst = '20' OR cmc.inst = '74' OR cmc.inst = '10' OR cmc.inst = '50' OR cmc.inst = '60') 
		   GROUP BY cmc.medico
		   ORDER BY cmc.medico, cmc.orden ";
		   
	 }else{	   
   		$cSql = "SELECT cmc.inst,cmc.medico,sum(cmc.importe) as importe,sum(cmc.comision) as comision,
         med.nombrec as nommedico,med.nombrec,count(*) as ordenes,
         sum(numestudios) as estudios
   		FROM cmc,med
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''
   		AND cmc.medico='$Medico' AND (cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4'
	      OR cmc.inst = '20' OR cmc.inst = '74' OR cmc.inst = '50' OR cmc.inst = '60') 
		   GROUP BY cmc.medico
		   ORDER BY cmc.medico, cmc.orden ";

	 }  
	 
  }
  
  $Usr = $HTTP_SESSION_VARS['usuario_login'];   

 require ("config.php");

  

$doc_title    = "Relacion de Pagos";
 $doc_subject  = "recibos unicode";
 $doc_keywords = "keywords para la busqueda en el PDF";
 
 require_once('tcpdf/config/lang/eng.php');
 require_once('tcpdf/tcpdf.php');



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
    $this->Image($image_file2, 8, 5, 48, '', 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);

    $this->SetFont('helvetica', 'B', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800"></td></tr><tr><td width="30"></td><td width="800">Laboratorio Clínico Duran</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Av.Fray Pedro de Gante No. Col Centro Texcoco Edo.de Mex.</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.'</td></tr></table>', false, 0);

/*
    $this->SetFont('helvetica', '', 8);

    $this->writeHTML('<br><br><table align="center"  border="1" cellspacing="1" cellpadding="0">
<tr bgcolor="#a2b2de">
<td align="center" width="40">#</td>
<td align="center" width="120">Medico</td>
<td align="center" width="280">Nombre</td>
<td align="center" width="150">No. Orden</td>
<td align="center" width="150">Estudios</td>'

if($Importes=='Si'){
    '<td align="center" width="150">Importe</td>
    <th align="center"  width="150">Comision</td>';
 }else{
    '<th align="center"  width="300">Firma</td></tr></table>', false, 0);
 }   

*/
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
define ("PDF_MARGIN_TOP", 25);
define ("PDF_MARGIN_BOTTOM", 20);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 20);

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

$rgA = mysql_query($cSql);
$Inst = "";


$pdf->SetFont('Helvetica', '', 8, '', 'false');

$html1 ='<table align="center"  border="1">
<tr bgcolor="#a2b2de">
<td align="center" width="40">#</td>
<td align="center" width="120">Medico</td>
<td align="center" width="430">Nombre</td>
<td align="center" width="90">No. Orden</td>
<td align="center" width="90">Estudios</td>';

if($Importes=='Si'){
  $html1.='<td align="center" width="200">Importe</td>
           <td align="center" width="200">Comision</td></tr></table>';
 }else{
  $html1.='<td align="center"  width="400">Firma</td></tr></table>';
 } 

 $pdf->writeHTML($html1,false,false,true,false,'C');

$nRngg=1;

while ($rg = mysql_fetch_array($rgA)) {

    if( ($nRngg % 2) > 0 ){
        $Fdo='#FFFFFF';
      }
        else{
            $Fdo='#CDCDFA';
      }   

      


    $pdf->SetFont('Helvetica', '', 9, '', 'false');

    $html ='<table align="center" border="0">
    <tr >
    <td align="center" width="40"><br><br> '.$nRngg.' </br></br></td>
    <td align="center"  width="120"><br><br> '.$rg[medico].'</br></br></td>
    <td align="left" width="430"><br><br> '.$rg[nommedico].'</br></br></td>
    <td align="center" width="90"><br><br> '.$rg[ordenes].' </br></br></td>
    <td align="center" width="90"><br><br> '.$rg[estudios].'</br></br></td>';
   
 
    if ($Importes == 'Si') {
        $html.='<td align="right" width="200"><br><br>' . number_format($rg[importe], "2") . '</br></br></td>
         <td align="right" width="200"><br><br>' . number_format($rg[comision], "2") . '</br></br></td></tr></table>';
    } else {
        $html.='<td align="right" width="400"><br><br>_____________________________________</br></br></td></tr>
        </table>';

    }

    $pdf->writeHTML($html,false,false,true,false,'C');
    $nImp += $rg[importe];
    $nCom += $rg[comision];
    $nRngg++;
    $html ='';
 
  }

  $pdf->SetFont('Helvetica', '', 9, '', 'false');

  $html ='<table align="center" border="0">
  <tr>
  <td align="center" width="40"></td>
  <td align="center"  width="120"></td>
  <td align="left" width="430"></td>
  <td align="center" width="90"></td>
  <td align="center" width="90" bgcolor="#808080"><br>Total</br></td>';
 

  if ($Importes == 'Si') {
      $html.='<td align="right" width="200" bgcolor="#808080"><br>' . number_format($nImp, "2") . '</br></td>
       <td align="right" width="200" bgcolor="#808080"><br>' . number_format($nCom, "2") . '</br></td></tr></table>';
  } else {
      $html.='<td align="right" width="400"></td></tr></table>';
  }
  $pdf->writeHTML($html,false,false,true,false,'C');


ob_end_clean();
//Close and output PDF document
$pdf->Output();

mysql_close();
?>

