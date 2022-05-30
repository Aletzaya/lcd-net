<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $level       = $check['level'];

  $Recepcionista= "*";
	$Recepcionista='*';
  
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

  $Institucion=$_REQUEST[Institucion];

  $Institucion1=$_REQUEST[Institucion];

  $Fecha=$_REQUEST[FechaI];

  $Fechai=$_REQUEST[FechaI];

  $Fechaf=$_REQUEST[FechaF];

  $Suc                  =       $_COOKIE['TEAM'];        //Sucursal 

  require ("config.php");


$doc_title    = "Relacion de Ordenes de trabajo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
//require_once('tcpdf2/tcpdf_include.php');

$InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="";
		$Sucursal2= " * - Todas ";
	}else{
	
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


	if($_REQUEST[Institucion]=='*'){

   		if($Recepcionista=="*"){
			
	        if ($Sucursal <> '') {
				
				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
						   cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
						   FROM cja,ot
						   WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND ot.orden=cja.orden AND ($Sucursal) 
						   ORDER BY ot.institucion,ot.orden,cja.id";
					
					$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND ($Sucursal)";

			}else{

				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
						   cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
						   FROM cja,ot
						   WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND ot.orden=cja.orden 
						   ORDER BY ot.institucion,ot.orden,cja.id";
					
					$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf'";
					
			}

	   }else{
		   
   	  if ($Sucursal <> '') {
				

				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				FROM cja,ot
				WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND ($Sucursal)
				order by ot.institucion,ot.orden,cja.id";
	
				$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista' AND ($Sucursal)";
				
			}else{
	
				$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				FROM cja,ot
				WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden order by ot.institucion,ot.orden,cja.id";
	
				$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista'";
			
			}

	   }
	}else{

	   if($Recepcionista=="*"){
		   
	     if ($Sucursal <> '') {
			   
			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND institucion='$Institucion' AND ($Sucursal)";

			   
		   }else{

			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND institucion='$Institucion'";
			  
		   }

	   }else{
		   
		  if ($Sucursal <> '') {
			  
			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by cja.orden,cja.id";
		
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista' AND institucion='$Institucion' AND ($Sucursal)";
			  
		  }else{

			  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by cja.orden,cja.id";
	
	
			  $OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and fecha<='$Fechaf' AND recepcionista='$Recepcionista' AND institucion='$Institucion'";
		  
		  }

	   }

	}


if($level==9 or $level==99 or $level==7){


	$UpA=mysql_query($cSql,$link);

}

$OtNumA=mysql_query($OtNum,$link);

$Ordenes=mysql_fetch_array($OtNumA);

$Hora=date("h:i:s");


$Titulo= "Corte de Caja del $Fechai &nbsp; de : $Fechaf &nbsp; Sucursal: $Sucursal2 ";


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

    $this->writeHTML('<br><br><br><table align="center"  border="1" cellspacing="1" cellpadding="0">
    <tr>
    <td align="CENTER" width="40" bgcolor="#808B96">Inst.</td>
    <td align="CENTER" width="160" bgcolor="#808B96"> Nombre</td>
    <td align="CENTER" bgcolor="#808B96">No Ordenes</td>
    <td align="CENTER" bgcolor="#808B96" >Ventas</td>
    <td align="CENTER" bgcolor="#808B96">( Efectivo )</td>
    <td align="CENTER" bgcolor="#808B96">( Recup. Efec. )</td>
    <td align="CENTER" bgcolor="#808B96">Total Efec.</td>
    <td align="CENTER" bgcolor="#808B96">( Banc. )</td>
    <td align="CENTER" bgcolor="#808B96">( Recup. Banc. )</td>
    <td align="CENTER" bgcolor="#808B96">Total Bancos</td>
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



//***********   D A T O S   ***********

if($_REQUEST[Institucion]=='*'){
    $sql=$cSql;
$res=mysql_query($sql,$link);
$FechaAux=strtotime($Fecha);
$nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
$FechaAnt=date("Y-m-d",$nDias);
$TotalIngreso=0;
    $Repetido2=0;
    $Orden1=0;
    $Ini=1;
    $Tarjeta=0;
    $Efectivo=0;
    $Cheque=0;
    $Transferencia=0;
    $TransferenciaR=0;
    $TarjetaR=0;
    $EfectivoR=0;
    $ChequeR=0;
    $status=="Pagada";
    $contaorden=0;

while($registro=mysql_fetch_array($res)) {
 //$InstO=$registro[institucion];
        $AbnA=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha <= '$FechaAnt'",$link);
        $Abonado=mysql_fetch_array($AbnA);
        $AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND id<>'$registro[0]'",$link);
        $AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
        $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
        $Dto=mysql_fetch_array($DtoA);              
        $Horaorden1=substr($registro[horaorden],0,5);
        $Horaordenc=$registro[3];
        $Adeudo1=($registro[6]-($Abonado[0] + $registro[4] + $AbonadoD[0] ));
      if ($Adeudo1<=1){
             if ($registro[4]==$registro[6]){
                $status="Pagado";
                $Ordenventa=$Dto[1];
                $Ordendesc=$Dto[0];
                $Ordenventot=$Dto[1]-$Dto[0];
                $Recupera=0;
                $Ingreso1=$registro[4];	
            }else{
                if ($Orden1==$registro[orden]){
                    $Horaordenc3=$Horaordenc-$Horaordenc2;
                    if ($Horaordenc3>=5){	
                        $status="Recup. Inmed.";
                        $Ordenventa=0;
                        $Ordendesc=0;
                        $Ordenventot=0;
                        $Recupera=0;
                        $Ingreso1=$registro[4];
                        $Adeudo1=0;
                        //$Recupera3=$registro[4];
                    }else{
                        $status="Pagado";
                        $Ordenventa=0;
                        $Ordendesc=0;
                        $Ordenventot=0;
                        $Recupera=0;
                        $Ingreso1=$registro[4];
                        $Adeudo1=0;
                    }
                }else{
                    if ($registro[1]==$registro[10]){	
                        if ($Horaorden1<=$HoraI){
                            $status="Recuperac.";
                            $Ordenventa=0;
                            $Ordendesc=0;
                            $Ordenventot=0;
                            $Recupera=$registro[4];
                            $Ingreso1=0;
                        }else{
                            $status="Pagado";
                            $Ordenventa=$Dto[1];
                            $Ordendesc=$Dto[0];
                            $Ordenventot=$Dto[1]-$Dto[0];
                            $Recupera=0;
                            $Ingreso1=$registro[4];	
                        }	
                    }else{	
                        $status="Recuperac.";
                        $Ordenventa=0;
                        $Ordendesc=0;
                        $Ordenventot=0;
                        $Recupera=$registro[4];
                        $Ingreso1=0;	
                        $Recupera3=$registro[4];
                    }
                }
            }
        }else{
                if ($Orden1==$registro[orden]){	
                    if ($Abonado[0]<>0){	
                        $status="Abono";
                        $Ordenventa=$Dto[1];
                        $Ordendesc=$Dto[0];
                        $Ordenventot=0;
                        $Recupera=$registro[4];
                        $Ingreso1=0;	
                    }else{
                        $status="Recuperac.";
                        $Ordenventa=0;
                        $Ordendesc=0;
                        $Ordenventot=0;
                        $Recupera=$registro[4];
                        $Ingreso1=0;
                        $Adeudo1=0;
                    }	
                }else{
                    if ($Abonado[0]<>0){	
                        $status="Recuperac.";
                        $Ordenventa=0;
                        $Ordendesc=0;
                        $Ordenventot=0;
                        $Recupera=$registro[4];
                        $Ingreso1=0;
                        $Adeudo1=0;
                        $Recupera3=$registro[4];
                    }else{
                        $status="C / Adeudo";
                        $Abonos=$Abonado[0];
                        $Ordenventa=$Dto[1];
                        $Ordendesc=$Dto[0];
                        $Ordenventot=$Dto[1]-$Dto[0];
                        $Recupera=0;
                        $Ingreso1=$registro[4];
                    }
                }					
        }

         if($InstO<>$registro[institucion]){
              if($Ini==1){

                    $contaorden=$contaorden+1;
                    $Recupera2=$Recupera2+$Recupera;
                     $TotAdeudo=$TotAdeudo+$Adeudo1;
                     $Importe=$Importe+$Ordenventa-$Abonos;
                     $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
                     $Totdia=$TotalOrd;
                     $TotalIngreso=$TotalIngreso+$registro[4];
                     $Descuento=$Descuento+$Ordendesc;
                     $Ingreso=$Ingreso+$Ingreso1;
                     $IngTot=$Ingreso+$Recupera2;
                     $Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
                     $Recupera3=0;
                     $Abonos=0;
                     $Ini=$Ini+1;
                     $InstO=$registro[institucion];
                     $instnumero=$registro[institucion];

                    if ($Orden1==$registro[orden] or $Fechai>$registro[fecha]){
                            $no=$no+1;
                    }else{
                            $no=$no;
                    }

                }else{
             
                    $InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$instnumero'");
                    $NomB    = mysql_fetch_array($InstB);
                    $instnombre=$NomB[nombre];
                    $contaorden2=$contaorden-$no;
                    //$contaorden2=$contaorden;
                    $no=0;
                    if (($nRng % 2) > 0) {
                        $Fdo = '#FFFFFF';      
                    } else {
                        $Fdo = '"#a2b2de"';
                    }   //El resto de la division;
                    $nRng++;

                    $pdf->SetFont('Helvetica', '', 7, '', 'false');

                    $html='<table border="0"> 
                    <tr bgcolor='.$Fdo.'>
                    <td align="CENTER" width="40">'.$instnumero.'</td>
                    <td align="left" width="160">'.$instnombre.'</td>
                    <td align="center">'.$contaorden2.'</td>
                    <td align="right">'.number_format($Totdia,"2").'</td>
                    <td align="right">'.number_format($Efectivo,"2").'</td>
                    <td align="right">'.number_format($EfectivoR,"2").'</td>
                    <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td>
                    <td align="right">'.number_format($Tarjeta+$Cheque+$Transferencia,"2").'</td>
                    <td align="right">'.number_format($TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
                    <td align="right">'.number_format($Tarjeta+$Cheque+$Transferencia+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
                    <td align="right">'.number_format($IngTot,'2').'</td>
                    <td align="right">'.number_format($IngTot-$Totdia,'2').'</td></tr></table>';

                    $pdf->writeHTML($html,true,false,true,false,'');

                    $Totgraldia=$Totgraldia+$Totdia;
                    $Totgraladeudo=$Totgraladeudo+$TotAdeudo;
                    $Totgralingreso=$Totgralingreso+$Ingreso;
                    $Totgralrecupera=$Totgralrecupera+$Recupera2;
                    $Totgralingtot=$Totgralingtot+$IngTot;
                    $Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
                    $Grantotalgral=$Grantotalgral+$Totalgral;
                    $Totalefectivo=$Totalefectivo+$Efectivo;
                    $TotalefectivoR=$TotalefectivoR+$EfectivoR;
                    $Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
                    $Totaltarjeta=$Totaltarjeta+$Tarjeta;
                    $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
                    $Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;
                    $Totalcheque=$Totalcheque+$Cheque;
                    $TotalchequeR=$TotalchequeR+$ChequeR;
                    $Totaltranferencia=$Totaltranferencia+$Transferencia;
                    $TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
                    $Graltotalcheque=$Graltotalcheque+$Gralcheque;
                    $Totalgral1=$Totalgral1+$Gral1;
                    $Totalgral2=$Totalgral2+$Gral2;
                    $Totalgral3=$Totalgral3+$Gral3;

                    $Recupera2=0;
                    $TotAdeudo=0;
                    $Importe=0;
                    $TotalOrd=0;
                    $Totdia=0;
                    $TotalIngreso=0;
                    $Descuento=0;
                    $Ingreso=0;
                    $IngTot=0;
                    $Saldo=0;
                    $Recupera3=0;
                    $Abonos=0;
                    $contaorden=0;
                    $Tarjeta=0;
                    $Efectivo=0;
                    $Cheque=0;
                    $TarjetaR=0;
                    $EfectivoR=0;
                    $Transferencia=0;
                    $TransferenciaR=0;
                    $ChequeR=0;
                    $status=="Pagada";
                                          
                    $contaorden=$contaorden+1;
                    $Recupera2=$Recupera2+$Recupera;
                    $TotAdeudo=$TotAdeudo+$Adeudo1;
                    $Importe=$Importe+$Ordenventa-$Abonos;
                    $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
                    $Totdia=$TotalOrd;
                    $TotalIngreso=$TotalIngreso+$registro[4];
                    $Descuento=$Descuento+$Ordendesc;
                    $Ingreso=$Ingreso+$Ingreso1;
                    $IngTot=$Ingreso+$Recupera2;
                    $Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
                    $Recupera3=0;
                    $Abonos=0;
                    $Ini=$Ini+1;
                    $InstO=$registro[institucion];
                    $instnumero=$registro[institucion];
                    $contaordenT=$contaordenT+$contaorden2;
                    if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
                        $no=$no+1;
                    }else{
                        $no=$no+0;
                    }
                }
            
        }else{
                 if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
                    $no=$no+1;
                }else{
                    $no=$no+0;
                }

                $Recupera2=$Recupera2+$Recupera;
                $TotAdeudo=$TotAdeudo+$Adeudo1;
                $Importe=$Importe+$Ordenventa-$Abonos;
                $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
                $Totdia=$TotalOrd;
                $TotalIngreso=$TotalIngreso+$registro[4];
                $Descuento=$Descuento+$Ordendesc;
                $Ingreso=$Ingreso+$Ingreso1;
                $IngTot=$Ingreso+$Recupera2;
                $Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
                $Recupera3=0;
                $Abonos=0;
                $contaorden=$contaorden+1;
                $Ini=$Ini+1;
        }
        $Orden1=$registro[orden];
        $Horaordenc2=$Horaordenc;
        if( $status == "Recuperac." ){
                if($registro[tpago]=="Cheque"){
                        $ChequeR=$ChequeR+$registro[4];
                }elseif($registro[tpago]=="Tarjeta"){
                        $TarjetaR=$TarjetaR+$registro[4];
                }elseif($registro[tpago]=="Transferencia"){
                        $TransferenciaR=$TransferenciaR+$registro[4];
                }else{
                        $EfectivoR=$EfectivoR+$registro[4];
                }             
        }else{
                if($registro[tpago]=="Cheque"){
                      $Cheque=$Cheque+$registro[4];
                }elseif($registro[tpago]=="Tarjeta"){
                      $Tarjeta=$Tarjeta+$registro[4];
                }elseif($registro[tpago]=="Transferencia"){
                      $Transferencia=$Transferencia+$registro[4];
                }else{
                      $Efectivo=$Efectivo+$registro[4];
                }
        }

}//fin while



            $InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$instnumero'");
            $NomB    = mysql_fetch_array($InstB);
            $instnombre=$NomB[nombre];
            $contaorden2=$contaorden;	

            if (($nRng % 2) > 0) {
                $Fdo = '#FFFFFF';      
            } else {
                $Fdo = '"#a2b2de"';
            }   //El resto de la division;
            
            $nRng++;
              $contaordenT=$contaordenT+$contaorden2;



              $html='<table border="0"> 
              <tr bgcolor='.$Fdo.'>
              <td align="CENTER" width="40">'.$instnumero.'</td>
              <td align="left" width="160">'.$instnombre.'</td>
              <td align="center">'.$contaorden2.'</td>
              <td align="right">'.number_format($Totdia,"2").'</td>
              <td align="right">'.number_format($Efectivo,"2").'</td>
              <td align="right">'.number_format($EfectivoR,"2").'</td>
              <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td>
              <td align="right">'.number_format($Tarjeta+$Cheque+$Transferencia,"2").'</td>
              <td align="right">'.number_format($TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
              <td align="right">'.number_format($Tarjeta+$Cheque+$Transferencia+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
              <td align="right">'.number_format($IngTot,'2').'</td>
              <td align="right">'.number_format($IngTot-$Totdia,'2').'</td></tr></table>';

              $pdf->writeHTML($html,true,false,true,false,'');


           

//**********  T O T A L      G E N E R A L   *************

             $Totgraldia=$Totgraldia+$Totdia;
             $Totgraladeudo=$Totgraladeudo+$TotAdeudo;
             $Totgralingreso=$Totgralingreso+$Ingreso;
             $Totgralrecupera=$Totgralrecupera+$Recupera2;
             $Totgralingtot=$Totgralingtot+$IngTot;
             $Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
             $Totgraltransferencias=$Totgraltransferencias+$Transferencias;
             $Grantotalgral=$Grantotalgral+$Totalgral;
             $Totalefectivo=$Totalefectivo+$Efectivo;
             $TotalefectivoR=$TotalefectivoR+$EfectivoR;
             $Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
             $Totaltarjeta=$Totaltarjeta+$Tarjeta;
             $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
             $Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;

             $Totalcheque=$Totalcheque+$Cheque;
             $TotalchequeR=$TotalchequeR+$ChequeR;
             $Graltotalcheque=$Graltotalcheque+$Gralcheque;

             $Totaltranferencia=$Totaltranferencia+$Transferencia;
             $TotaltranferenciaR=$TotaltranferenciaR+$TransferenciaR;
             $Graltotaltranferencia=$Graltotaltranferencia+$Graltransferencia;

             $Totalgral1=$Totalgral1+$Gral1;
             $Totalgral2=$Totalgral2+$Gral2;
              $Totalgral3=$Totalgral3+$Gral3;



            $html='<table border="0"> 
            <tr bgcolor="#CCCCCC">	
            <td align="CENTER" width="40"></td>
            <td align="right" width="160" >TOTALES GENERALES:</td>
            <td align="center">'.$contaordenT.'</td>
            <td align="right">'.number_format($Totgraldia,"2").'</td>
            <td align="right">'.number_format($Totalefectivo,"2").'</td>
            <td align="right">'.number_format($TotalefectivoR,"2").'</td>
            <td align="right">'.number_format($Totalefectivo+$TotalefectivoR,"2").'</td>
            <td align="right">'.number_format($Totaltarjeta+$Totaltranferencia+$TotalCheque,"2").'</td>
            <td align="right">'.number_format($TotaltarjetaR+$TotaltranferenciaR+$TotalChequeR,"2").'</td>
            <td align="right">'.number_format($Totaltarjeta+$Totaltranferencia+$TotalCheque+$TotaltarjetaR+$TotaltranferenciaR+$TotalChequeR,"2").'</td>
            <td align="right">'.number_format($Totgralingtot,'2').'</td>
            <td align="right">'.number_format($Totgraldia-$Totgralingtot,'2').'</td></tr></table>';
            $pdf->writeHTML($html,true,false,true,false,'');



        }else{
            $sql=$cSql;
            $res=mysql_query($sql,$link);
            $FechaAux=strtotime($Fecha);
            $nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
            $FechaAnt=date("Y-m-d",$nDias);
            $TotalIngreso=0;
                    $Repetido2=0;
                    $Orden1=0;
                    $Tarjeta=0;
                    $Efectivo=0;
                    $Cheque=0;
                    $TarjetaR=0;
                    $EfectivoR=0;
                    $Transferencia=0;
                    $TransferenciaR=0;
                    $ChequeR=0;
                    $status=="Pagada";
            while($registro=mysql_fetch_array($res)) {
                            $AbnA=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha <= '$FechaAnt'",$link);
                            $Abonado=mysql_fetch_array($AbnA);
                            $AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND cja.fecha>='$Fechai' and cja.fecha<='$Fechaf' AND id<>'$registro[0]'",$link);
                            $AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
                            $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
                            $Dto=mysql_fetch_array($DtoA);              
                            $Horaorden1=substr($registro[horaorden],0,5);
                            $Horaordenc=$registro[3];
                            $Adeudo1=($registro[6]-($Abonado[0] + $registro[4] + $AbonadoD[0] ));
    
                            $InstO=$registro[institucion];
                            $instnumero=$registro[institucion];
    
                            if ($Adeudo1<=1){
                                    if ($registro[4]==$registro[6]){
                                            $status="Pagado";
                                            $Ordenventa=$Dto[1];
                                            $Ordendesc=$Dto[0];
                                            $Ordenventot=$Dto[1]-$Dto[0];
                                            $Recupera=0;
                                            $Ingreso1=$registro[4];	
                                    }else{
                                            if ($Orden1==$registro[orden]){
                                                    $Horaordenc3=$Horaordenc-$Horaordenc2;
                                                    if ($Horaordenc3>=5){	
                                                        $status="Recup. Inmed.";
                                                        $Ordenventa=0;
                                                        $Ordendesc=0;
                                                        $Ordenventot=0;
                                                        $Recupera=0;
                                                        $Ingreso1=$registro[4];
                                                        $Adeudo1=0;
                                                        //$Recupera3=$registro[4];
                                                    }else{
                                                        $status="Pagado";
                                                        $Ordenventa=0;
                                                        $Ordendesc=0;
                                                        $Ordenventot=0;
                                                        $Recupera=0;
                                                        $Ingreso1=$registro[4];
                                                        $Adeudo1=0;
                                                    }
                                            }else{
                                                    if ($registro[1]==$registro[10]){	
                                                        if ($Horaorden1<=$HoraI){
                                                            $status="Recuperac.";
                                                            $Ordenventa=0;
                                                            $Ordendesc=0;
                                                            $Ordenventot=0;
                                                            $Recupera=$registro[4];
                                                            $Ingreso1=0;
                                                        }else{
                                                            $status="Pagado";
                                                            $Ordenventa=$Dto[1];
                                                            $Ordendesc=$Dto[0];
                                                            $Ordenventot=$Dto[1]-$Dto[0];
                                                            $Recupera=0;
                                                            $Ingreso1=$registro[4];	
                                                        }	
                                                    }else{	
                                                        $status="Recuperac.";
                                                        $Ordenventa=0;
                                                        $Ordendesc=0;
                                                        $Ordenventot=0;
                                                        $Recupera=$registro[4];
                                                        $Ingreso1=0;	
                                                        $Recupera3=$registro[4];
                                                    }
                                            }
                                    }
                            }else{
                                if ($Orden1==$registro[orden]){	
                                    if ($Abonado[0]<>0){	
                                        $status="Abono";
                                        $Ordenventa=$Dto[1];
                                        $Ordendesc=$Dto[0];
                                        $Ordenventot=0;
                                        $Recupera=$registro[4];
                                        $Ingreso1=0;	
                                    }else{
                                        $status="Recuperac.";
                                        $Ordenventa=0;
                                        $Ordendesc=0;
                                        $Ordenventot=0;
                                        $Recupera=$registro[4];
                                        $Ingreso1=0;
                                        
                                    }	
                                }else{
                                    if ($Abonado[0]<>0){	
                                        $status="Recuperac.";
                                        $Ordenventa=0;
                                        $Ordendesc=0;
                                        $Ordenventot=0;
                                        $Recupera=$registro[4];
                                        $Ingreso1=0;
                                        $Adeudo1=0;
                                        $Recupera3=$registro[4];
                                    }else{
                                        $status="C / Adeudo";
                                        $Abonos=$Abonado[0];
                                        $Ordenventa=$Dto[1];
                                        $Ordendesc=$Dto[0];
                                        $Ordenventot=$Dto[1]-$Dto[0];
                                        $Recupera=0;
                                        $Ingreso1=$registro[4];
                                    }
                                }					
                            }
                            $Orden1=$registro[orden];
                            $Horaordenc2=$Horaordenc;
                            if( $status == "Recuperac." ){
                                    if($registro[tpago]=="Cheque"){
                                        $ChequeR=$ChequeR+$registro[4];
                                    }elseif($registro[tpago]=="Tarjeta"){
                                        $TarjetaR=$TarjetaR+$registro[4];
                                    }elseif($registro[tpago]=="Transferencia"){
                                        $TransferenciaR=$TransferenciaR+$registro[4];
                                    }else{
                                        $EfectivoR=$EfectivoR+$registro[4];
                                    }             
                    }else{
                                    if($registro[tpago]=="Cheque"){
                                        $Cheque=$Cheque+$registro[4];
                                    }elseif($registro[tpago]=="Tarjeta"){
                                        $Tarjeta=$Tarjeta+$registro[4];
                                    }elseif($registro[tpago]=="Transferencia"){
                                        $Transferencia=$Transferencia+$registro[4];
                                    }else{
                                        $Efectivo=$Efectivo+$registro[4];
                                    }
                  }
    
                            $Recupera2=$Recupera2+$Recupera;
                            $TotAdeudo=$TotAdeudo+$Adeudo1;
                            $Importe=$Importe+$Ordenventa-$Abonos;
                            $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
                            $Totdia=$TotalOrd;
                            $TotalIngreso=$TotalIngreso+$registro[4];
                            $Descuento=$Descuento+$Ordendesc;
                            $Ingreso=$Ingreso+$Ingreso1;
                            $IngTot=$Ingreso+$Recupera2;
                            $Saldo=$Saldo+($registro[6]-($Abonado[0] + $registro[4]));
                            $Recupera3=0;
                            $Abonos=0;
    
            }//fin while
    
                    $InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$instnumero'");
                    $NomB    = mysql_fetch_array($InstB);
                    $instnombre=$NomB[nombre];
                    $contaorden2=$contaorden;	
    
                    if (($nRng % 2) > 0) {
                        $Fdo = '#FFFFFF';      
                    } else {
                        $Fdo = '"#a2b2de"';
                    }   //El resto de la division;
                    $nRng++;


                    $contaordenT=$contaordenT+$contaorden2;
                    $pdf->SetFont('Helvetica', '', 7, '', 'false');


                    $html='<table border="0"> 
                    <tr bgcolor='.$Fdo.'>
                    <td align="CENTER" width="40">'.$instnumero.'</td>
        
                    <td align="left" width="160">'.$instnombre.'</td>
                    <td align="center">'.$Ordenes[0].'</td>
                    <td align="right">'.number_format($Totdia,"2").'</td>
                    <td align="right">'.number_format($Efectivo,"2").'</td>
                    <td align="right">'.number_format($EfectivoR,"2").'</td>
                    <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td>
                    <td align="right">'.number_format($Tarjeta+$Cheque+$Transferencia,"2").'</td>
                    <td align="right">'.number_format($TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
                    <td align="right">'.number_format($Tarjeta+$Cheque+$Transferencia+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
                    <td align="right">'.number_format($IngTot,"2").'</td>
                    <td align="right">'.number_format($IngTot-$Totdia,"2").'</td></tr></table>';
               
                    $pdf->writeHTML($html,true,false,true,false,'');

    

}//fin if
//inicio del while para adeudos, recuperaciones y descuentos



ob_end_clean();
//Close and output PDF document
$pdf->Output();


mysql_close();
?>
