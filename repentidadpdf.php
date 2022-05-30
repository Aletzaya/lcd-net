<?php

  session_start();
  include_once ("auth.php");

  include_once ("authconfig.php");
  
  include_once ("check.php");

  require("lib/lib.php");
date_default_timezone_set("America/Mexico_City");

  require ("config.php");	

  $link   = conectarse();

  $FechaI = $_REQUEST[FechaI];

  $FechaF = $_REQUEST[FechaF];
  
  $Institucion    = $_REQUEST[Institucion];
  
  $Sucursal    = $_REQUEST[Sucursal];
  
  $Det=$_REQUEST[Det];
  
  $Ref=$_REQUEST[Ref];
  
  
  
$doc_title    = "Imprimir Reporte de Entidad";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');





    if($Ref=="MD"){
        $referencia=" and ot.medico='MD'";
    }elseif($Ref=="AQ"){
          $referencia=" and ot.medico='AQ'";
    }else{
          $referencia="";
    }
  
    if (!isset($FechaI)){
  
        $FechaF=date("Y-m-d");
  
        $FechaI=date("Y-m-")."01";
  
    }
    
    $lU    = mysql_query("DELETE FROM rep");
    
    if($Det<>'Si'){
        
        if($Sucursal == "*"){
      
            if($Institucion == "*" OR $Institucion == ''){
             
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
                          $referencia
                          GROUP BY cli.estado,cli.municipio");
                                  
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF $Ref";
                          
            }else{
          
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente AND ot.institucion='$Institucion'
                          $referencia
                          GROUP BY cli.estado,cli.municipio");
             
               $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
               $cIns  = mysql_fetch_array($cInsA);
          
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Ins: $cIns[alias] $Ref";
          
            }
            
        }else{
            
            if($Institucion == "*" OR $Institucion==''){
             
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente and ot.suc='$Sucursal'
                          $referencia
                          GROUP BY cli.estado,cli.municipio");
                          
               $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
               $cSuc  = mysql_fetch_array($cSucA);
      
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF, Suc: $Sucursal - $cSuc[alias] $Ref";
                          
            }else{
          
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
                          AND ot.institucion='$Institucion' and ot.suc='$Sucursal' $referencia
                          GROUP BY cli.estado,cli.municipio");
             
               $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
               $cIns  = mysql_fetch_array($cInsA);
               
               $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
               $cSuc  = mysql_fetch_array($cSucA);
      
          
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias], Suc: $Sucursal - $cSuc[alias] $Ref";
          
            }
        }
  
    }else{
        
        if($Sucursal == "*"){
      
            if($Institucion == "*" OR $Institucion == ''){
             
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente $referencia
                          GROUP BY cli.estado,cli.municipio,cli.colonia");
                                  
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF $Ref";
                          
            }else{
          
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente AND ot.institucion='$Institucion'
                          $referencia
                          GROUP BY cli.estado,cli.municipio,cli.colonia");
             
               $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
               $cIns  = mysql_fetch_array($cInsA);
          
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias]  $Ref";
          
            }
            
        }else{
            
            if($Institucion == "*" OR $Institucion==''){
             
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente and ot.suc='$Sucursal'
                          $referencia
                          GROUP BY cli.estado,cli.municipio,cli.colonia");
                          
               $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
               $cSuc  = mysql_fetch_array($cSucA);
      
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF, Suc: $Sucursal - $cSuc[alias]  $Ref";
                          
            }else{
          
               $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
                          FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
                          AND ot.institucion='$Institucion' and ot.suc='$Sucursal' $referencia
                          GROUP BY cli.estado,cli.municipio,cli.colonia");
             
               $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
               $cIns  = mysql_fetch_array($cInsA);
               
               $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
               $cSuc  = mysql_fetch_array($cSucA);
      
          
               $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias], Suc.: $Sucursal - $cSuc[alias]  $Ref";
          
            }
        }
    
    }
    
  
    require ("config.php");

         // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2,$Recepcionista,$FecI,$FecF,$cSuc,$Ref;

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

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.'</td></tr></table><br>', false, 0);



    $this->SetFont('Helvetica', 'BI', 8);

    $this->writeHTML('<br><br><br><table align="center" width="1150" border="1" cellspacing="1" cellpadding="0">
    <tr>
    <td  bgcolor="#808B96">Estado</td>
    <td  bgcolor="#808B96">Municipio</td>
    <td  bgcolor="#808B96">Colonia</td>
    <td  bgcolor="#808B96">Cantidad</td>
    <td  bgcolor="#808B96">Importe</td></tr></table>', false, 0);
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
define ("PDF_MARGIN_TOP", 40);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 12);

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




//headymenu($Titulo,0);

$Ini = 0+substr($FechaI,5,2);
$Fin = 0+substr($FechaF,5,2);


if($Det<>'Si'){

    
    while($rg = mysql_fetch_array($OtA)){
  
        $Edo  = str_replace(" ","!",$rg[estado]);                      //Remplazo la comita p'k mande todo el string
        $Mpio = str_replace(" ","!",$rg[municipio]);                      //Remplazo la comita p'k mande todo el string
  
        if( ($nRng % 2) > 0 ){
            $Fdo='#FFFFFF';
          }
            else{
                $Fdo='#CDCDFA';
          }   


        $pdf->SetFont('Helvetica', '', 7, '', 'false');
        
            $html='<table border="0" width="1150">
            <tr bgcolor='.$Fdo.'>
            <td>'.$rg[estado].'</td>
            <td>'.$rg[municipio].'</td>
            <td align="right">'.$rg[cantidad].'</td>
            <td align="right">'.number_format($rg[importotal],"2").'</td></tr></table>';
            $pdf->writeHTML($html,true,false,true,false,'');
  
        $nCnt  += $rg[cantidad];
        $nImpt  += $rg[importotal];
        $nRng++;
          
    }

      $html='<table border="0" width="1150">
      <tr>
      <td></td>
      <td bgcolor="#808B96">Total ---> </td>
      <td bgcolor="#808B96" align="right">'.number_format($nCnt,"0").'</td>
      <td bgcolor="#808B96" align="right">'.number_format($nImpt,"2").'</td></tr></table>';
      $pdf->writeHTML($html,true,false,true,false,'');



}else{



  
    while($rg = mysql_fetch_array($OtA)){
  
        $Edo  = str_replace(" ","!",$rg[estado]);                      //Remplazo la comita p'k mande todo el string
        $Mpio = str_replace(" ","!",$rg[municipio]);                      //Remplazo la comita p'k mande todo el string
        $Col  = str_replace(" ","!",$rg[colonia]);                      //Remplazo la comita p'k mande todo el string
  
       if( ($nRng % 2) > 0 ){
            $Fdo='#FFFFFF';
          }
            else{
                $Fdo='#CDCDFA';
          }   
        
        $pdf->SetFont('Helvetica', '', 7, '', 'false');
        $html='<br><table border="0" width="1150">
              <tr bgcolor='.$Fdo.'>
              <td>'.$rg[estado].'</td>
              <td>'.$rg[municipio].'</td>
              <td>'.$rg[colonia].'</td>
              <td align="right"> '.$rg[cantidad].'</td>
              <td align="right">'.number_format($rg[importotal],"2").'</td></tr></table>';
              $pdf->writeHTML($html,true,false,true,false,'');
      
  
        $nCnt  += $rg[cantidad];
        $nImpt  += $rg[importotal];
        $nRng++;
      
 }

        $html='<br><table border="0" width="1150">
          <tr>
          <td> </td>
          <td> </td>
          <td bgcolor="#808B96"> Total ---> </td>
          <td bgcolor="#808B96" align="right">'.number_format($nCnt,"0").'</td>
          <td bgcolor="#808B96" align="right">'.number_format($nImpt,"2").'</td></tr></table>';
          $pdf->writeHTML($html,true,false,true,false,'');
  

}

  


ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>