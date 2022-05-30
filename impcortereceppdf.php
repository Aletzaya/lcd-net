<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();
  
	$Sucursal     =   $_REQUEST[Sucursal];
	$sucursalt = $_REQUEST[sucursalt];
	$sucursal0 = $_REQUEST[sucursal0];
	$sucursal1 = $_REQUEST[sucursal1];
	$sucursal2 = $_REQUEST[sucursal2];
	$sucursal3 = $_REQUEST[sucursal3];
	$sucursal4 = $_REQUEST[sucursal4];
	$sucursal5 = $_REQUEST[sucursal5];
	$sucursal6 = $_REQUEST[sucursal6];

  $Institucion=$_REQUEST[Institucion];

  $Fecha=$_REQUEST[FechaI];

  $Fechai=$_REQUEST[FechaI];

  $Fechaf=$_REQUEST[FechaF];

  require ("config.php");


  $doc_title    = "Imprimir Corte Recepcion ";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

$InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
$NomI    = mysql_fetch_array($InstA);

$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="";
		$Sucursal2= " * - Todas ";
		$and="";
		$parentesisa="";
		$parentesisc="";

	}else{

		$and=" and ";
		$parentesisa="(";
		$parentesisc=")";
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}	

		if($sucursal5=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
			}
		}
		if($sucursal6=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}

if($Institucion=="*"){

	$CondifInst="";

}else{

	$CondifInst="and ot.institucion=$Institucion";

}
				
$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario as recepcionista,ot.fecha as fechaot,ot.hora as horaorden,ot.suc,ot.institucion 
	   FROM cja,ot
	   WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND ot.orden=cja.orden $and $parentesisa $Sucursal $parentesisc $CondifInst
	   ORDER BY recepcionista,cja.orden,cja.id,cja.hora";

$UpA=mysql_query($cSql,$link);

$Hora=date("h:i:s");


$Titulo= "Corte de Caja del $Fechai &nbsp; a &nbsp; $Fechaf &nbsp; Sucursal: $Sucursal2 ";

  // ********************  E N C A B E Z A D O  ****************

  class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2,$Recepcionista;

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

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Institucion: '.$Institucion.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.'hrs.</td></tr></table>', false, 0);

    $this->SetFont('Helvetica', 'BI', 8);

    $this->writeHTML('<br><br><br><table align="center"  width="97%" border="1" cellspacing="1" cellpadding="0">
    <tr>
    <td align="CENTER" width="120" bgcolor="#808B96"> Nombre</td>
    <td align="CENTER" bgcolor="#808B96">No Ordenes</td>
    <td align="CENTER" bgcolor="#808B96" >Ventas</td>
    <td align="CENTER" bgcolor="#808B96">Efectivo</td>
    <td align="CENTER" bgcolor="#808B96">Recup. Efec.</td>
    <td align="CENTER" bgcolor="#808B96">Total Efec.</td>
    <td align="CENTER" bgcolor="#808B96">Tarjeta</td>
    <td align="CENTER" bgcolor="#808B96">Recup. Tarjeta</td>
    <td align="CENTER" bgcolor="#808B96">Total Tarjeta</td>
    <td align="CENTER" bgcolor="#808B96">Transfer</td>
    <td align="CENTER" bgcolor="#808B96">Recup. Transfer</td>
    <td align="CENTER" bgcolor="#808B96">Total Transfer</td>
    <td align="CENTER" bgcolor="#808B96">Ingresos</td>
    <td align="CENTER" bgcolor="#808B96">Adeudos (CXC)</td>
   </tr>
    </table>', false, 0);
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
define ("PDF_MARGIN_TOP", 38);
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


$res=mysql_query($cSql,$link);
		$Ini=1;
		$Tarjeta=0;
		$Efectivo=0;
		$Transferencia=0;
		$TransferenciaR=0;
		$TarjetaR=0;
		$EfectivoR=0;
		$contaorden=0;
		$Recep="";

    while($registro=mysql_fetch_array($res)) {

    	if($Recep<>strtoupper($registro[recepcionista])){

    		if($Ini==1){

					if ($registro[fecha]>$registro[fechaot]){

						if($registro[tpago]=="Efectivo"){
							$EfectivoR=$EfectivoR+$registro[importe];
						}elseif($registro[tpago]=="Tarjeta"){
							$TarjetaR=$TarjetaR+$registro[importe];
						}elseif($registro[tpago]=="Transferencia"){
							$TransferenciaR=$TransferenciaR+$registro[importe];
						}

					}else{

						$RegrecupA=mysql_query("SELECT count(id) as ids FROM cja WHERE orden='$registro[orden]' and id<'$registro[id]' and usuario<>'$registro[recepcionista]'",$link);
						$Regrecup=mysql_fetch_array($RegrecupA);

						if($Regrecup[ids]==0){

							if($registro[tpago]=="Efectivo"){
								$Efectivo=$Efectivo+$registro[importe];
							}elseif($registro[tpago]=="Tarjeta"){
								$Tarjeta=$Tarjeta+$registro[importe];
							}elseif($registro[tpago]=="Transferencia"){
								$Transferencia=$Transferencia+$registro[importe];
							}

							if($Orden2<>$registro[orden]){
								$contaorden++;
								$DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
								$Dto=mysql_fetch_array($DtoA);
								$Ordenventot=$Dto[1]-$Dto[0];
								$Totdia=$Totdia+$Ordenventot;
								$Totgraldia=$Totgraldia+$Ordenventot;
								$Orden2=$registro[orden];
							}

						}else{

								if($registro[tpago]=="Efectivo"){
									$EfectivoR=$EfectivoR+$registro[importe];
								}elseif($registro[tpago]=="Tarjeta"){
									$TarjetaR=$TarjetaR+$registro[importe];
								}elseif($registro[tpago]=="Transferencia"){
									$TransferenciaR=$TransferenciaR+$registro[importe];
								}

						}

					}

					$Ini++;

				}else{
                         if (($nRng % 2) > 0) {
                            $Fdo = '#FFFFFF';      
                        } else {
                            $Fdo = '#a2b2de';
                        }   //El resto de la division;
                        $nRng++;

                        $IngTot=$Efectivo+$EfectivoR+$Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR;

                        $pdf->SetFont('Helvetica', '', 7, '', 'false');

                        $html='<table border="0" width="97%"> 
                        <tr bgcolor='.$Fdo.'>
                        <td align="CENTER" width="120">'.$Recep.'</td>
                        <td align="center">'.$contaorden.'</td>
                        <td align="right">'.number_format($Totdia,"2").'</td>
                        <td align="right">'.number_format($Efectivo,"2").'</td>
                        <td align="right">'.number_format($EfectivoR,"2").'</td>
                        <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td>
                        <td align="right">'.number_format($Tarjeta,"2").'</td>
                        <td align="right">'.number_format($TarjetaR,"2").'</td>
                        <td align="right">'.number_format($Tarjeta+$TarjetaR,"2").'</td>
                        <td align="right">'.number_format($Transferencia,"2").'</td>
                        <td align="right">'.number_format($TransferenciaR,"2").'</td>
                        <td align="right">'.number_format($Transferencia+$TransferenciaR,"2").'</td>
                        <td align="right">'.number_format($IngTot,'2').'</td>
                        <td align="right">'.number_format($IngTot-$Totdia,'2').'</td></tr></table>';
    
                        $pdf->writeHTML($html,true,false,true,false,'');


                        $Totalefectivo=$Totalefectivo+$Efectivo;
                        $TotalefectivoR=$TotalefectivoR+$EfectivoR;
                        $Totaltarjeta=$Totaltarjeta+$Tarjeta;
                        $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
                        $Totaltranferencia=$Totaltranferencia+$Transferencia;
                        $TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
                        $contaordenT=$contaordenT+$contaorden;
    
                        $Tarjeta=0;
                        $Efectivo=0;
                        $Transferencia=0;
                        $TransferenciaR=0;
                        $TarjetaR=0;
                        $EfectivoR=0;
                        $contaorden=0;
                        $Totdia=0;
    
                        if ($registro[fecha]>$registro[fechaot]){
    
                            if($registro[tpago]=="Efectivo"){
                                $EfectivoR=$EfectivoR+$registro[importe];
                            }elseif($registro[tpago]=="Tarjeta"){
                                $TarjetaR=$TarjetaR+$registro[importe];
                            }elseif($registro[tpago]=="Transferencia"){
                                $TransferenciaR=$TransferenciaR+$registro[importe];
                            }
                                
                        }else{
    
                            $RegrecupA=mysql_query("SELECT count(id) as ids FROM cja WHERE orden='$registro[orden]' and id<'$registro[id]' and usuario<>'$registro[recepcionista]'",$link);
                            $Regrecup=mysql_fetch_array($RegrecupA);
    
                            if($Regrecup[ids]==0){
    
                                if($registro[tpago]=="Efectivo"){
                                    $Efectivo=$Efectivo+$registro[importe];
                                }elseif($registro[tpago]=="Tarjeta"){
                                    $Tarjeta=$Tarjeta+$registro[importe];
                                }elseif($registro[tpago]=="Transferencia"){
                                    $Transferencia=$Transferencia+$registro[importe];
                                }
    
                                if($Orden2<>$registro[orden]){
                                    $contaorden++;
                                    $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
                                    $Dto=mysql_fetch_array($DtoA);
                                    $Ordenventot=$Dto[1]-$Dto[0];
                                    $Totdia=$Totdia+$Ordenventot;
                                    $Totgraldia=$Totgraldia+$Ordenventot;
                                    $Orden2=$registro[orden];
                                }
    
                            }else{
    
                                    if($registro[tpago]=="Efectivo"){
                                        $EfectivoR=$EfectivoR+$registro[importe];
                                    }elseif($registro[tpago]=="Tarjeta"){
                                        $TarjetaR=$TarjetaR+$registro[importe];
                                    }elseif($registro[tpago]=="Transferencia"){
                                        $TransferenciaR=$TransferenciaR+$registro[importe];
                                    }
    
                            }
                        }
                    
                    }
    
            }else{
    
                    if ($registro[fecha]>$registro[fechaot]){
    
                        if($registro[tpago]=="Efectivo"){
                            $EfectivoR=$EfectivoR+$registro[importe];
                        }elseif($registro[tpago]=="Tarjeta"){
                            $TarjetaR=$TarjetaR+$registro[importe];
                        }elseif($registro[tpago]=="Transferencia"){
                            $TransferenciaR=$TransferenciaR+$registro[importe];
                        }
                            
                    }else{
    
                            $RegrecupA=mysql_query("SELECT count(id) as ids FROM cja WHERE orden='$registro[orden]' and id<'$registro[id]' and usuario<>'$Recep'",$link);
                            $Regrecup=mysql_fetch_array($RegrecupA);
    
                            if($Regrecup[ids]==0){
    
                                if($registro[tpago]=="Efectivo"){
                                    $Efectivo=$Efectivo+$registro[importe];
                                }elseif($registro[tpago]=="Tarjeta"){
                                    $Tarjeta=$Tarjeta+$registro[importe];
                                }elseif($registro[tpago]=="Transferencia"){
                                    $Transferencia=$Transferencia+$registro[importe];
                                }
    
                                if($Orden2<>$registro[orden]){
                                    $contaorden++;
                                    $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
                                    $Dto=mysql_fetch_array($DtoA);
                                    $Ordenventot=$Dto[1]-$Dto[0];
                                    $Totdia=$Totdia+$Ordenventot;
                                    $Totgraldia=$Totgraldia+$Ordenventot;
                                    $Orden2=$registro[orden];
                                }
    
                            }else{
    
                                    if($registro[tpago]=="Efectivo"){
                                        $EfectivoR=$EfectivoR+$registro[importe];
                                    }elseif($registro[tpago]=="Tarjeta"){
                                        $TarjetaR=$TarjetaR+$registro[importe];
                                    }elseif($registro[tpago]=="Transferencia"){
                                        $TransferenciaR=$TransferenciaR+$registro[importe];
                                    }
    
                            }
    
                    }
    
                    $Ini++;
    
            }
    
                $Recep=strtoupper($registro[recepcionista]);
            }
                            if (($nRng % 2) > 0) {
                            $Fdo = '#FFFFFF';      
                        } else {
                            $Fdo = '#a2b2de';
                        }   //El resto de la division;
                        
                        $nRng++;

                       $IngTot=$Efectivo+$EfectivoR+$Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR;

                        $html='<table border="0" width="97%"> 
                        <tr bgcolor='.$Fdo.'>
                        <td align="CENTER" width="120">'.$Recep.'</td>
                        <td align="center">'.$contaorden.'</td>
                        <td align="right">'.number_format($Totdia,"2").'</td>
                        <td align="right">'.number_format($Efectivo,"2").'</td>
                        <td align="right">'.number_format($EfectivoR,"2").'</td>
                        <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td>
                        <td align="right">'.number_format($Tarjeta,"2").'</td>
                        <td align="right">'.number_format($TarjetaR,"2").'</td>
                        <td align="right">'.number_format($Tarjeta+$TarjetaR,"2").'</td>
                        <td align="right">'.number_format($Transferencia,"2").'</td>
                        <td align="right">'.number_format($TransferenciaR,"2").'</td>
                        <td align="right">'.number_format($Transferencia+$TransferenciaR,"2").'</td>
                        <td align="right">'.number_format($IngTot,'2').'</td>
                        <td align="right">'.number_format($IngTot-$Totdia,'2').'</td></tr></table>';
    
                        $pdf->writeHTML($html,true,false,true,false,'');


                        $Totalefectivo=$Totalefectivo+$Efectivo;
                        $TotalefectivoR=$TotalefectivoR+$EfectivoR;
                        $Totaltarjeta=$Totaltarjeta+$Tarjeta;
                        $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
                        $Totaltranferencia=$Totaltranferencia+$Transferencia;
                        $TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
                        $contaordenT=$contaordenT+$contaorden;
                
                
                    if (($nRng % 2) > 0) {
                        $Fdo = '#FFFFFF';      
                    } else {
                        $Fdo = '#a2b2de';
                    }   //El resto de la division;
                    


                          $html='<table border="0" width="97%"> 
                          <tr bgcolor="#CCCCCC">	
                          <td align="right" width="120" >TOTALES GENERALES:</td>
                          <td align="center">'.$contaordenT.'</td>
                          <td align="right">'.number_format($Totgraldia,"2").'</td>
                          <td align="right">'.number_format($Totalefectivo,"2").'</td>
                          <td align="right">'.number_format($TotalefectivoR,"2").'</td>
                          <td align="right">'.number_format($Totalefectivo+$TotalefectivoR,"2").'</td>
                          <td align="right">'.number_format($Totaltarjeta,"2").'</td>
                          <td align="right">'.number_format($TotaltarjetaR,"2").'</td>
                          <td align="right">'.number_format($Totaltarjeta+$TotaltarjetaR,"2").'</td>
                          <td align="right">'.number_format($Totaltranferencia,"2").'</td>
                          <td align="right">'.number_format($TotaltranferenciaR,"2").'</td>
                          <td align="right">'.number_format($Totaltranferencia+$TotaltranferenciaR,"2").'</td>
                          <td align="right">'.number_format($Totgralingtot,'2').'</td>
                          <td align="right">'.number_format($Totgraldia-$Totgralingtot,'2').'</td></tr></table>';
                          $pdf->writeHTML($html,true,false,true,false,'');
                          
        
ob_end_clean();
//Close and output PDF document
$pdf->Output();

mysql_close();
?>
