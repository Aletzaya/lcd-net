<?php

session_start();


include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


require("lib/lib.php");

require ("config.php");	

$link=conectarse();

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $Institucion=$_REQUEST[Institucion];

  $FechaI=$_REQUEST[FechaI];
  $FechaF=$_REQUEST[FechaF];

  $Titulo=$_REQUEST[Titulo];

  $cAdeudo=$_REQUEST[cAdeudo];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

$doc_title    = "Relacion de Ordenes de trabajo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	//require_once('tcpdf2/tcpdf_include.php');


    if(strlen($Institucion)>0){
        $NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
        $Nombre=mysql_fetch_array($NomA);
           $Titulo="Relacion de Pagos de Ordenes de trabajo del $FechaI al $FechaF Institucion : $Institucion $Nombre[0]";
        $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, ot.institucion, ot.recepcionista, ot.hora, ot.servicio, ot.importe
        FROM ot, cli 
        WHERE ot.cliente = cli.cliente and ot.fecha>='$FechaI' and
        ot.fecha <='$FechaF' and ot.institucion='$Institucion'
        order by ot.orden";
    }else{
           $Titulo="Relacion de Pagos Ordenes de trabajo del $FechaI al $FechaF";
        $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, ot.institucion, ot.recepcionista, ot.hora, ot.servicio, ot.importe
        FROM ot, cli 
        WHERE ot.cliente = cli.cliente and ot.fecha>='$FechaI' and
        ot.fecha <='$FechaF'
        order by ot.orden";
    }
    
    $UpA=mysql_query($cSql,$link);

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

    $this->SetFont('helvetica', '', 9);
 
    $this->writeHTML('<br><br><br><table align="center" width="1150" border="1" cellspacing="1" cellpadding="0"><tr><td align="center" bgcolor="#808B96" width="120">Orden</td><td align="center" bgcolor="#808B96" width="350">Paciente</td><td align="center" bgcolor="#808B96" width="150">Fecha Captura</td><td align="center" bgcolor="#808B96" width="150">Hora Captura</td><td align="center" bgcolor="#808B96" width="150">Importe</td><td align="center" bgcolor="#808B96" width="100">Abono(s)</td><td align="center" bgcolor="#808B96" width="150">Capturo</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

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

$pdf->SetFont('Helvetica', '', 9, '', 'false');



$pdf->writeHTML($html,true,false,true,false,'');


if ($cAdeudo == "S") {
        
    while ($rg = mysql_fetch_array($UpA)) {
        $cSqlB = mysql_query("select sum(importe) from cja where cja.orden=$rg[orden]", $link);
        $SqlS2 = mysql_fetch_array($cSqlB);
        $Diferencia = $rg[importe] - $SqlS2[0];
        if ($Diferencia > 1) {
            $EstA = mysql_query("SELECT estudio FROM otd WHERE orden='$rg[orden]'");
            $Estudios = "";
            while ($Est = mysql_fetch_array($EstA)) {
                if ($Estudios == '') {
                    $Estudios = "(" . $Est[estudio];
                } else {
                    $Estudios = $Estudios . ", " . $Est[estudio];
                }
            }
            $Rec = $rg[recepcionista];

            $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
            <tr>
            <td align="CENTER" width="120">'.$rg[institucion].'&nbsp;-&nbsp;'.$rg[orden].'</td>
            <td align="left" width="350"> &nbsp;'.$rg[2].'</td>
            <td align="CENTER" width="150">'.$rg[fecha].'</td>
            <td align="CENTER" width="150">'.$rg[hora].'&nbsp;</td>
            <td align="right" width="150">' . number_format($rg[importe], '2') . '</td>
            <td width="100"></td>
            <td align="CENTER" width="150">'.$Rec.'</td>
            </tr>
            </table>';
            $pdf->writeHTML($html,true,false,true,false,'');
 
            $cSqlA = mysql_query("select * from cja where cja.orden=$rg[orden]", $link);
            while ($SqlS = mysql_fetch_array($cSqlA)) {

                $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
                <tr>
                <td width="120"></td>
                <td align="right" width="350">Id.:&nbsp; '.$SqlS[id].'&nbsp;-->&nbsp;</td>
                <td align="center" width="150">'.$SqlS[fecha].'</td>
                <td align="center" width="150">'.$SqlS[hora].'</td>
                <td width="150"></td>
                <td align="right" width="100">' . number_format($SqlS[importe], '2') . '&nbsp;</td>
                <td align="center" width="150">'.$SqlS[usuario].'</td>
                </tr>
                </table>';
                 $pdf->writeHTML($html,true,false,true,false,'');
 

                $TotalA+=$SqlS[importe];
                $Movimientos+=1;
            }

            $Adeudo = $rg[importe] - $TotalA;
            $TAdeudo+=$Adeudo;
            $Timporte+=$rg[importe];
            $TotalTA+=$TotalA;
            $Ordenes+=1;

            if ($Adeudo > 1) {
                $MensajeA = "* * * A D E U D O * * * ";
                $Ctadeudo+=1;
            } else {
                $MensajeA = " ";
                if ($Adeudo < -1) {
                    $MensajeA = "* * * - - - > ";
                }
            }

            $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
            <tr>
            <td align="center" width="620"><font color="#FF0000">'.$MensajeA.'</font></td>            
            <td align="right" width="150" bgcolor="#808B96">Total: $</td>
            <td align="right" width="150" bgcolor="#808B96">' . number_format($rg[importe], '2') . '</td>
            <td align="right" width="100"bgcolor="#808B96">' . number_format($TotalA, '2') . '</td>
            <td align="right" width="150"bgcolor="#808B96">'. number_format($Adeudo, '2') . '</td>
            </tr>
            </table>___________________________________________________________________________________________________________________<br>';
                $pdf->writeHTML($html,true,false,true,false,'');
            $TotalA = 0;
        }
    }

}else {

    while ($rg = mysql_fetch_array($UpA)) {
        $Rec = $rg[recepcionista];


        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
        <tr>
        <td align="CENTER" width="120">'.$rg[institucion].'&nbsp;-&nbsp;'.$rg[orden].'</td>
        <td align="left" width="350"> &nbsp;'.$rg[2].'</td>
        <td align="CENTER" width="150">'.$rg[fecha].'</td>
        <td align="CENTER" width="150">'.$rg[hora].'&nbsp;</td>
        <td align="right" width="150">' . number_format($rg[importe], '2') . '</td>
        <td width="100"></td>
        <td align="CENTER"width="150">'.$Rec.'</td>
        </tr>
        </table>';
    $pdf->writeHTML($html,true,false,true,false,'');

        
        $cSqlA = mysql_query("select * from cja where cja.orden=$rg[orden]", $link);
        while ($SqlS = mysql_fetch_array($cSqlA)) {



            $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
            <tr>
            <td width="120"></td>
            <td align="right" width="350">Id.:&nbsp; '.$SqlS[id].'&nbsp;-->&nbsp;</td>
            <td align="center" width="150">'.$SqlS[fecha].'</td>
            <td align="center" width="150">'.$SqlS[hora].'</td>
            <td width="150"></td>
            <td align="right" width="100">' . number_format($SqlS[importe], '2') . '&nbsp;</td>
            <td align="center" width="150">'.$SqlS[usuario].'</td>
            </tr>
            </table>';
$pdf->writeHTML($html,true,false,true,false,'');


            $TotalA+=$SqlS[importe];
            $Movimientos+=1;
        }
        $Adeudo = $rg[importe] - $TotalA;
        $TAdeudo+=$Adeudo;
        $Timporte+=$rg[importe];
        $TotalTA+=$TotalA;
        $Ordenes+=1;

        if ($Adeudo > 1) {
            $MensajeA = "* * * A D E U D O * * * ";
            $Ctadeudo+=1;
        } else {
            $MensajeA = " ";
            if ($Adeudo < -1) {
                $MensajeA = "* * * - - - > ";
            }
        }



        $html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
        <tr>
        <td align="center" width="620"><font color="#FF0000">'.$MensajeA.'</font></td>            
        <td align="right" width="150" bgcolor="#808B96">Total: $</td>
        <td align="right" width="150" bgcolor="#808B96">' . number_format($rg[importe], '2') . '</td>
        <td align="right" width="100"bgcolor="#808B96">' . number_format($TotalA, '2') . '</td>
        <td align="right" width="150"bgcolor="#808B96">'. number_format($Adeudo, '2') . '</td>
        </tr>
        </table>___________________________________________________________________________________________________________________<br>';
    $pdf->writeHTML($html,true,false,true,false,'');
        $TotalA = 0;
       
    }
}

$html='<table align="center" width="1150" border="0" cellspacing="1" cellpadding="0">
     <tr>
     <td align="right" width="150" bgcolor="#808B96">No. Ordenes : '.$Ordenes.'</td>
     <td align="right" width="180" bgcolor="#808B96">No. Abono(s) : '.$Movimientos.'</td>
     <td align="right" width="180" bgcolor="#808B96">No. Adeudo(s) : '.$Ctadeudo.'</td>
     <td align="right" width="200" bgcolor="#808B96">GRAN TOTAL --> $</td>
  	 <td align="right" width="160" bgcolor="#808B96">'.number_format($Timporte,'2').'</td>
	 <td align="right" width="150" bgcolor="#808B96">'.number_format($TotalTA,'2').'</td>
  	 <td align="right" width="150" bgcolor="#808B96">'.number_format($TAdeudo,'2').'</td>
     </tr>
     </table>';
     $pdf->writeHTML($html,true,false,true,false,'');



ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>