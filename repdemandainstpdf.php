<?php

  session_start();

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
 	$Suc2="and cja.suc='$filtro3'";
 }else{
	$filtro4=" ";
	$Suc2=" ";
 }

 if($filtro5<>'*'){
 	$filtro6="and inst.institucion='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and inst.status='$filtro7'";
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


   $Titulo = "Demanda de afluencia de pacientes por institucion del $FechaI al $FechaF";

    $cOtA="SELECT inst.institucion,inst.nombre,date_format(ot.fecha,'%Y-%m') as fecha,count(*),inst.condiciones,inst.promotorasig,inst.status,inst.condiciones,ot.suc,sum(ot.importe) as importe
	FROM ot
	INNER JOIN inst on inst.institucion=ot.institucion
	WHERE ot.institucion=inst.institucion $filtro4 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF' 
	GROUP BY ot.institucion,date_format(ot.fecha,'%Y-%m') order by ot.institucion, date_format(ot.fecha,'%Y-%m')";

	$OtA  = mysql_query($cOtA,$link);
/*
	$vta= "SELECT cja.fecha,sum(cja.importe),cja.orden,ot.orden,ot.institucion 
	from cja
	INNER JOIN ot on cja.orden=ot.orden
	where cja.orden=ot.orden $Suc2 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF' 
	GROUP BY ot.institucion,date_format(ot.fecha,'%Y-%m') order by ot.institucion, date_format(ot.fecha,'%Y-%m')";

	$vtaA  = mysql_query($vta,$link);

	$vtas = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

    while ($vta2 = mysql_fetch_array($vtaA)) {
	        $vtas[$x]= $vta2[1];
			$x+=1;
    }
*/

  $Mes  = array("","Ene","Feb","Mzo","Abr","May","Jun","Jul","Agos","Sept","Oct","Nov","Dic");

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
  $tCntot = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

  require ("config.php");
  
  //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
$Usr=$check['uname'];
$doc_title    = "Imprimir Demanda de Pac/Ins";
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


        $this->writeHTML('<table width="100" border="0"><tr><th width="78" height="15"></th><th width="610"><font size="10"><b>Laboratorio Clinico Duran S.A. de C.V.</b></font></th></tr><tr><th width="78" height="15"></th><th width="610">Fray Pedro de Gante Norte No 108 Texcoco de Mora Cp.56100</th></tr><tr><th width="78" height="18">&nbsp;</th><th width="800"><b>Demanda de afluencia de pacientes por medico del '.$FechaI.' al '.$FechaF.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fecha/Hora: '.$Fecha.'</b></th></tr></table>', false, 0);


	$Mes  = array("","ENE","FEB","MZO","ABR","MAY","JUN","JUL","AGO","SEPT","OCT","NOV","DIC");
	$Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
	$Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);

	//$Tit1 = '<table width="99.2%" border="1"><tr><th height="30">Inst</th><th>Nombre</th><th>Condiciones</th><th>Promotor</th>';
	$Tit1 = '<br><br><br><table border="1" width="99.2%"><tr bgcolor="#a2b2de" align="center"><th height="30"><b>INST</b></th><th><b>NOMBRE</b></th><th><b>CONDIC.</b></th><th><b>PROMOTOR</b></th>';


	for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
	    if (substr($i, 4, 2) == '13') {
	        $i = $i + 88;
	    }
	    $x = substr($i, 4, 2) * 1;
	    $Tit2 = $Tit2 . '<th colspan="2"><b>'.$Mes[$x].'</b></th>';
		$Cmes+=1;
	}

	//$Tit3 = '<th>Total</th><th>Prom</th><th>Ingreso</th><th>Dif</th></tr></table>';
	//$Tit3 = '<th colspan="2">Total</th><th colspan="2">Prom</th><th>Ingreso</th><th>Dif</th></tr></table>';
	$Tit3 = '<th colspan="2"><b>TOTAL</b></th><th colspan="2"><b>PROMED.</b></th><th><b>INGRESO</b></th><th><b>DIF</b></th></tr></table>';


$tbl = <<<EOD
$Tit1
$Tit2
$Tit3
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
define ("PDF_MARGIN_TOP", 32);
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

$Tit7 = '<table border="1">';

for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
    if (substr($i, 4, 2) == '13') {
        $i = $i + 88;
    }
    $x = substr($i, 4, 2) * 1;
    $Tit2 = $Tit2 . '<th colspan="2"><b>'.$Mes[$x].'</b></th>';
	$Cmes+=1;
}

$Tit3 = '<th colspan="2"><b>TOTAL</b></th><th colspan="2"><b>PROMED.</b></th><th><b>INGRESO</b></th><th><b>DIF</b></th></tr>';





$Inst = 'XXX';

    while ($reg = mysql_fetch_array($OtA)) {
		

        if ($reg[institucion] <> $Inst) {

			if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#e3f2ca';}
            if ($Inst <> 'XXX') {
                $cTit = '';
                $SubT = 0;
                $SubTot = 0;				
                for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                    if (substr($i, 4, 2) == '13') {
                        $i = $i + 88;
					}

					
                    $x = substr($i, 4, 2) * 1;

                    $cTit = $cTit . '<th align="center">'.number_format($aCntot[$x],"0").'</th>
                    <th align="right">'.number_format($aCnt[$x],"2").'</th>';


                    $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                    $tCntot[$x] = $tCntot[$x] + $aCntot[$x];
                    $SubT += $aCnt[$x];
                    $SubTot += $aCntot[$x];
                    $GraT += $aCnt[$x];
                    $GraTot += $aCntot[$x];
                }
				
				$Promedio= $SubT/$Cmes;
				$Promedioot= $SubTot/$Cmes;
/*
				$cOtB= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
				WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m') Between '$Ini' And '$Fin'";

				$OtB  = mysql_fetch_array(mysql_query($cOtB,$link));

				$vta= "select cja.fecha,sum(cja.importe),cja.orden,ot.orden,ot.institucion from cja,ot where cja.orden=ot.orden and ot.institucion='$Inst' and cja.fecha Between '$FechaI' And '$FechaF'";

				$vtaA  = mysql_fetch_array(mysql_query($vta,$link));

				$Venta=$vtaA[1];
*/
                $html1='<tr style="background-color: '.$Fdo.';color: #000;"><th>'.$Inst.'</th><th>'.$Nombre.'</th><th>'.$Esp.'</th><th>'.$Promotor.'</th>';


                $html3= $cTit . '<th align="center">'.number_format($SubTot,"0").'</th>
                <th align="right">'.number_format($SubT,"2").'</th>
                <th align="center">'.number_format($Promedioot,"0").'</th>
                <th align="right">'.number_format($Promedio,"2").' </th>
                <th align="right">'.number_format($Venta,"2").'</th>
                <th align="right">'.number_format($SubT-$Venta,"2").'</th></tr>';

                $Tit4=$html1 . $html3;
		
				$Tit5=$Tit5 . $Tit4;

                if($Promotor=='Promotor_A'){

                    $PromotorA1 += $SubTot;
                    $PromotorA2 += $SubTot;
   
                }elseif($Promotor=='Promotor_B'){
                    
                    $PromotorB1 += $SubTot;
                    $PromotorB2 += $SubTot;
   
                }elseif($Promotor=='Promotor_C'){
                    
                    $PromotorC1 += $SubTot;
                    $PromotorC2 += $SubTot;
                    
                }elseif($Promotor=='Promotor_D'){
                    
                    $PromotorD1 += $SubTot;
                    $PromotorD2 += $SubTot;
   
                }
				$VentaT+=$Venta;				
				$Venta = 0;
				$Tvisit+=$OtB[1];
            }

            $Inst = $reg[institucion];
            $Esp = $reg[condiciones]; 
            $Nombre = $reg[nombre];
            $Status = $reg[status];
            $Promotor = $reg[promotorasig];
            $clasificacion = $reg[clasificacion];
		    $ZnaA2=mysql_fetch_array(mysql_query("SELECT zona,descripcion FROM zns where zns.zona='$reg[zona]'"));
            $zona = $ZnaA2[descripcion];
            $aCnt = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");	
            $aCntot = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");	
			$nRng++;

        }
        $Fec = $reg[fecha];
        $Pos = 0 + substr($Fec, 5, 2);
        $aCnt[$Pos] = $reg[importe];
        $aCntot[$Pos] = $reg[3];
		$Venta+=$reg[importe];

    }

$Tit6 = '</table>';

    $cTit = '';
    $SubT = 0;
    $SubTot = 0;
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
/*
		$cOtC= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
		WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m')='$i'";

		$OtC  = mysql_fetch_array(mysql_query($cOtC,$link));
		
		if($OtC[1]==0){
			$vist=$Fdo;
		}else{
			$vist='#debcff';
		}
*/		
		$x = substr($i, 4, 2) * 1;

        $cTit = $cTit . '<th align="center">'.number_format($aCntot[$x],"0").'</th><th align="right">'.number_format($aCnt[$x],"2").'</th>';

        $SubT+=$aCnt[$x];
        $GraT+=$aCnt[$x];
		$tCnt[$x] = $tCnt[$x] + $aCnt[$x];
		$SubTot+=$aCntot[$x];
        $GraTot+=$aCntot[$x];
		$tCntot[$x] = $tCntot[$x] + $aCntot[$x];
    }
	$Promedio= $SubT/$Cmes;
	$Promedioot= $SubTot/$Cmes;

/*
	$cOtB= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
	WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m') Between '$Ini' And '$Fin'";

	//if( ($nRng % 2) > 2 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;


    $OtB  = mysql_fetch_array(mysql_query($cOtB,$link));

*/
    $html1='<tr><th>'.$Inst.'</th><th>'.$Nombre.'</th><th>'.$Esp.'</th><th>'.$Promotor.'</th>';



    $html3= $cTit .'<th align="center">'.number_format($SubTot,"0").'</th>
    <th align="right">'.number_format($SubT,"2").'</th>
    <th align="center">'.number_format($Promedioot,"0").'</th>
    <th align="right">'.number_format($Promedio,"2").' </th>
    <th align="right">'.number_format($Venta,"2").'</th>
    <th align="right">'.number_format($SubT-$Venta,"2").'</th></tr>';

    $Tit4=$html1 . $html3;

	$Tit5=$Tit5 . $Tit4;

	$cTit='';

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
		
        $cTit = $cTit . '<th align="center"><b>'.number_format($tCntot[$x],"0").'</b></th><th align="right"><b>'.number_format($tCnt[$x],"2").'</b></th>';
    }
	$PromedioG= $GraT/$Cmes;
	$PromedioGot= $GraTot/$Cmes;
	$VentaT+=$Venta;

	$html1='<tr bgcolor="#a2b2de"><th height="30"><b>TOTALES:</b></th><th colspan="3"><b>INSTITUCIONES --> '.$nRng.'</b></th>';

	$html3= $cTit . '<th align="center"><b>'.number_format($GraTot,"0").'</b></th>
    <th align="right"><b>'.number_format($GraT,"2").'</b></th>
    <th align="center"><b>'.number_format($PromedioGot/$GraTot,"0").'</b></th>
    <th align="right"><b>'.number_format($PromedioG/$GraT,"2").'</b></th>
    <th align="right"><b>'.number_format($VentaT,"2").'</b></th>
    <th align="right"><b>'.number_format($GraT-$VentaT,"2").'</b></th></tr>';

	$Tit4=$html1 . $html3;

	$Tit5=$Tit5 . $Tit4;

$tbl = <<<EOD
$Tit7
$Tit5
$Tit6
EOD;
$pdf->writeHTML($tbl, true, false, false, false, 'C');

// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("'Reporte'.pdf'", 'I');

mysql_close();
?>

