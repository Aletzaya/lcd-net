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


$Fechai = $_REQUEST[FechaIni];
$Fechaf = $_REQUEST[FechaFin];

$HoraI=$_REQUEST[HoraIni];
$HoraF=$_REQUEST[HoraFin];

$Titulo = $_REQUEST[Titulo];


$Generapago = $_REQUEST[generapago];

$Fecha = date("Y-m-d");

$Hora = date("H:i");

//$Fechai = date("Y-m-d H:i");

$SucursalD = $_REQUEST[DeSucursal];

if ($SucursalD == "*") {
    $SucursalDe = "";
} else {
    $SucursalDe = "and ot.suc=$SucursalD";
}

$SucursalP = $_REQUEST[Proveedor];

if ($SucursalP == "*") {
    $SucursalPara = "";
} else {
    $SucursalPara = "and maqdet.mext='$SucursalP'";
}

$personal = $_REQUEST[Personal];

if ($personal == "*") {
    $personale = "";
} else {
    $personale = "and maqdet.usrenvext='$personal'";
}

if ($Generapago == 'Si') {
    $SucursalP = $SucursalP === "*" ? 0 : $SucursalP;
    $Inst = 'FecI=' . $Fechai . '&FecF=' . $Fechaf . '&HoraI=' . $HoraI . '&HoraF=' . $HoraF . '&SucursalDe=' . $SucursalD . '&Proveedor=' . $SucursalP . '&personal=' . $personal;
    $Sql = "INSERT INTO generapago (instruccion,fecha,usr,cancel,proveedor)
      VALUES
      ('$Inst','" . $_REQUEST["FecI"] . "','$Gusr','','$SucursalP')";
    //echo $Sql;
    if (!$rs = mysql_query($Sql)) {
        echo "Error en SQL :" . $Sql . " Error .- " . mysql_error();
    }
}

require ("config.php");
$doc_title    = "Envio Externo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');


$Titulo = "Relacion de Envio de muestras Externo del $Fechai al $Fechaf";

$cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,maqdet.usrenvext "
. "FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio "
. "AND ot.cliente = cli.cliente and maqdet.fenvext>='" . $_REQUEST["FechaFin"] . "' and "
. "maqdet.fenvext <='" . $_REQUEST["FechaFin"] . "' AND maqdet.henvext >='" . $_REQUEST["HoraIni"] . "' "
. "AND maqdet.henvext <='" . $_REQUEST["HoraFin"] . "' $SucursalDe $SucursalPara $personale "
. "order by maqdet.orden";
//echo $cSql;
$Qry = mysql_query($cSql);


require("lib/lib.php");

$link = conectarse();


$FechaI = date("Y-m-d");
$FechaF = date("Y-m-d");
$Fecha = date("Y-m-d");

require ("config.php");
    
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
    
        $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Sucursal de:'.$SucursalD.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Para Provedor:'.$SucursalP.'</td></tr></table>', false, 0);

        $this->SetFont('Helvetica', 'BI', 8);
    
        $this->writeHTML('<br><br><table align="center" width="1150" border="0" cellspacing="1" cellpadding="0"><tr>
        <td align="center" bgcolor="#E1E1E1" width="50">Suc</td>
        <td align="center" bgcolor="#E1E1E1" width="100">Orden</td>
        <td align="center" bgcolor="#E1E1E1" width="300">Paciente</td>
        <td align="center" bgcolor="#E1E1E1" width="120">Estudios</td>
        <td align="center" bgcolor="#E1E1E1" width="280">Descripcion</td>
        <td align="center" bgcolor="#E1E1E1" width="200">Observaciones</td>
        <td align="center" bgcolor="#E1E1E1" width="50">Para</td>
        <td align="center" bgcolor="#E1E1E1" width="80">Usr</td>

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

    while ($rs = mysql_fetch_array($Qry)) {

        if( ($nRng % 1) > 0 ){
            $Fdo='#FFFFFF';
          }else{
            $Fdo='#E1E1E1';
          }  

        $pdf->SetFont('Helvetica', '', 7, '', 'false');

    
        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
       <tr bgcolor='.$Fdo.'>
       <td align="center" width="50">'.$rs[suc].'</td>
       <td align="center" width="100">'.$rs[institucion].' - '.$rs[orden].'</td>
       <td align="left"   width="300">  '.$rs[nombrec].'</td>
       <td align="center" width="120">'.$rs[estudio].'</td>
       <td align="center" width="280">'.$rs[descripcion].'</td>
       <td align="center" width="200">'.$rs[obsenv].'</td>
       <td align="center" width="50">'.$rs[alias].'</td>
       <td align="center" width="80">'.$rs["usrenvext"].'</td>
       </tr></table>';
      
       $pdf->writeHTML($html,true,false,true,false,'');

        $sumCajaMSob += $rs["ImporteCaja"] - $rs["Sobrante"];
        $sumCaja += $rs["ImporteCaja"];
        $sumEfectivo += $rs["CjaImporteEfectivo"];
        $sumTarjeta += $rs["CjaImporteTarjeta"];
        $sumTranferencia += $rs["CjaImporteTransferencia"];
        $nRng++;
        $Estudios++;
                }

    

    $html='<table align="center"  border="1" cellspacing="1" cellpadding="0">
       <tr>
       <td align="center" width="1180" >No. de estudios: '.$Estudios.'</td></tr></table>';
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


$html='<br><br><table align="center" border="1" cellspacing="0" cellpadding="0">
<tr>
<td align="left" height="75px">Observaciones:</td></tr></table>';
$pdf->writeHTML($html,false,false,true,false,'');






    ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>