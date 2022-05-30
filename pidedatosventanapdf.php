<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

$Menu = $_REQUEST[Menu];

$SqlA = mysql_query("SELECT orden FROM ot WHERE ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'");

require("lib/lib.php");

$link = conectarse();


$FechaI = $_REQUEST[FechaIni];
$FechaF = $_REQUEST[FechaFin];
$Fecha = date("Y-m-d");


$HoraIni=$_REQUEST[HoraIni];
$HoraFin=$_REQUEST[HoraFin];


$SucursalDe = $_REQUEST["DeSucursal"] == "*" ? "" : "and ot.suc=".$_REQUEST["DeSucursal"];
$SucursalD = $_REQUEST["DeSucursal"];

if($SucursalD==1){
    $SucursalD = "Matriz";
}elseif($SucursalD==2){
    $SucursalD = "OHF";
}elseif($SucursalD==3){
    $SucursalD = "Tepexpan";
}elseif($SucursalD==4){
    $SucursalD = "Reyes";
}elseif($SucursalD==5){
    $SucursalD = "Camarones";
}elseif($SucursalD==6){
    $SucursalD = "San Vicente";
}

$SucursalPara = $_REQUEST["ParaSucursal"] == "*" ? "" : "and maqdet.mint=".$_REQUEST["ParaSucursal"];
$SucursalP = $_REQUEST["ParaSucursal"];

if($SucursalP==1){
    $SucursalP = "Matriz";
}elseif($SucursalP==2){
    $SucursalP = "OHF";
}elseif($SucursalP==3){
    $SucursalP = "Tepexpan";
}elseif($SucursalP==4){
    $SucursalP = "Reyes";
}elseif($SucursalP==5){
    $SucursalP = "Camarones";
}elseif($SucursalP==6){
    $SucursalP = "San Vicente";
}

require ("config.php");
$doc_title    = "Relacion de Ordenes de trabajo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');


    $Titulo = "Relacion de Envio de muestras del $FechaI al $FechaF";

    $cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mint,maqdet.fenv,maqdet.henv,maqdet.obsenv,ot.orden as ord,"
            . "ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion "
            . "FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio "
            . "AND ot.cliente = cli.cliente AND maqdet.fenv>='" . $_REQUEST["FechaIni"] . "' AND maqdet.fenv <='" . $_REQUEST["FechaFin"] . "' "
            . "AND maqdet.henv >='" . $_REQUEST["HoraIni"] . "' AND maqdet.henv <='" . $_REQUEST["HoraFin"] . "' "
            . "$SucursalDe $SucursalPara ORDER BY maqdet.orden";
    //echo $cSql;
    $Qry = mysql_query($cSql);
    
    class MYPDF extends TCPDF {

        //Page header
        function Header() {
            global $Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2,$SucursalP,$SucursalD;
    
    
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
        
        $this->SetFont('helvetica', '', 9);
    
        $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Sucursal de:'.$SucursalD.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Para Sucursal:'.$SucursalP.'</td></tr></table>', false, 0);

        $this->SetFont('Helvetica', 'BI', 8);
    
        $this->writeHTML('<br><br><table align="center" width="1150" border="0" cellspacing="1" cellpadding="0"><tr>
        <td align="center" bgcolor="#808B96" width="50">Suc</td>
        <td align="center" bgcolor="#808B96" width="100">Orden</td>
        <td align="center" bgcolor="#808B96" width="300">Paciente</td>
        <td align="center" bgcolor="#808B96" width="120">Estudios</td>
        <td align="center" bgcolor="#808B96" width="300">Descripcion</td>
        <td align="center" bgcolor="#808B96" width="230">Observaciones</td>
        <td align="center" bgcolor="#808B96" width="50">Para</td>
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
    
    
    $Estudios=0;

    while($rg=mysql_fetch_array($Qry)) {

        if( ($nRngg % 2) > 0 ){
            $Fdo='#FFFFFF';
          }
            else{
                $Fdo='#CDCDFA';
          }  

        $pdf->SetFont('Helvetica', '', 7, '', 'false');

        $str = $rg[nombrec];
        $str1 = strtoupper($str);

        $html='<table align="center" width="1150" border="1" cellspacing="1" cellpadding="0">
       <tr bgcolor='.$Fdo.'>
       <td align="center" width="50">'.$rg[suc].'</td>
       <td align="center" width="100">'.$rg[institucion].' - '.$rg[orden].'</td>
       <td align="left"   width="300">  '.$str1.'</td>
       <td align="center" width="120">'.$rg[estudio].'</td>
       <td align="center" width="300">'.$rg[descripcion].'</td>
       <td align="center" width="230">'.$rg[obsenv].'</td>
       <td align="center" width="50">'.$rg[mint].'</td>
       </tr></table>';
      
       $pdf->writeHTML($html,false,false,true,false,'');
        $Estudios++;
        $nRngg++;
    }
    

    $html='<table align="center"  border="1" cellspacing="1" cellpadding="0">
       <tr>
       <td align="center" width="1150" >No. de estudios: '.$Estudios.'</td></tr></table>';
       $pdf->writeHTML($html,false,false,true,false,'');



       $html='<br><br><table align="center" border="0">
        <tr>
        <td align="center">Elaborado por:</td>
        <td align="center">________________________</td>
        <td align="center">Mensajero:</td>
        <td align="center">_______________________</td>
        <td align="center">Recibido por:</td>
        <td align="center">________________________</td></tr></table>';
     $pdf->writeHTML($html,false,false,true,false,'');


 $html='<br><br><table align="center"  border="0">
<tr>
<td align="center">Hora de salida:</td>
<td align="center">_________________________</td>
<td align="center" width="250"> </td> 
<td align="center">Hora de Entrega:</td>
<td align="center">_________________________</td></tr></table>';
$pdf->writeHTML($html,false,false,true,false,'');


$html='<br><br><table align="center" border="1" cellspacing="0" cellpadding="0">
<tr>
<td align="left" height="75px">Observaciones:</td></tr></table>';
$pdf->writeHTML($html,false,false,true,false,'');






    ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>