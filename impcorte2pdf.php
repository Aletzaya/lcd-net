<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $sucursal  = $_REQUEST[Sucursal];
  $sucursalt = $_REQUEST[sucursalt];
  $sucursal0 = $_REQUEST[sucursal0];
  $sucursal1 = $_REQUEST[sucursal1];
  $sucursal2 = $_REQUEST[sucursal2];
  $sucursal3 = $_REQUEST[sucursal3];
  $sucursal4 = $_REQUEST[sucursal4];

  $Fecha=$_REQUEST[Fechai];

  $Suc  =   $_COOKIE['TEAM'];        //Sucursal 

  $Institucion=$_REQUEST[Institucion];

  $Fechai=$_REQUEST[Fechai];

  $Fechaf=$_REQUEST[Fechaf];

  $Titulo = "Reporte de Ingresos Generales por institucion del $Fechai al $Fechaf";
  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="*";
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
			$Sucursal2= $Sucursal2 . "San Vicente - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=6";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=6";
			}
		}
	}

	if ($Sucursal <> '*') {
		
		$cSql="SELECT cja.id,cja.fecha as cjafecha,cja.orden,cja.hora,cja.importe as cjaimporte,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden,ot.suc
				   FROM cja,ot
				   WHERE cja.fecha>='$Fechai' and cja.fecha <='$Fechaf' AND ot.orden=cja.orden and ($Sucursal) 
				   ORDER BY ot.institucion,ot.orden";
			
			$OtNum="SELECT count(orden) FROM ot WHERE  fecha>='$Fechai' and
                       fecha <='$Fechaf' AND ($Sucursal)";

	}else{

		$cSql="SELECT cja.id,cja.fecha as cjafecha,cja.orden,cja.hora,cja.importe as cjaimporte,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
				   FROM cja,ot
				   WHERE cja.fecha>='$Fechai' and cja.fecha <='$Fechaf' AND ot.orden=cja.orden
				   ORDER BY ot.institucion,ot.orden";
			
			$OtNum="SELECT count(orden) FROM ot WHERE fecha>='$Fechai' and
                       fecha <='$Fechaf'";
			
	}


$UpA=mysql_query($cSql,$link);

$OtNumA=mysql_query($OtNum,$link);

$Ordenes=mysql_fetch_array($OtNumA);

$Hora=date("h:i:s");

  require ("config.php");

  //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
$Usr=$check['uname'];
$doc_title    = "$Titulo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
// Extend the TCPDF class to create custom Header and Footer

// ********************  E N C A B E Z A D O  ****************


class MYPDF extends TCPDF {

    //Page header
    function Header() {
    	global $Fechai,$Fechaf;
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_file2 = 'lib/DuranNvoBk.png';
        $this->Image($image_file2, 8, 5, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', 'B', 12);

        $this->writeHTML('<table width="100"><tr><th width="78"></th><th width="1000"></th></tr><tr><th width="78"></th><th width="1000"></th></tr><tr><th width="78">&nbsp;</th><th width="1000">Reporte de Ingresos Generales por institucion del '.$Fechai.' al '.$Fechaf.'</th></tr></table><hr>', false, 0);

          $Tit1 = '<br><table border="1" width="99.3%"><tr style="background-color: #0d486a; color:#FFF;" align="center">
          <th align="center" width="40%">Inst.</th>
          <th align="center" width="300%">Nombre</th>
          <th align="center" width="88%">Ventas</th>
          <th align="center" width="88%">Adeudos</th>
          <th align="center" width="88%">Ing.Efec.</th>
          <th align="center" width="88%">Ing.Tarj.</th>
          <th align="center" width="88%">Ing.Transfer.</th>
          <th align="center" width="88%">Ing.Total</th>
          <th align="center" width="88%">Recup.Efec.</th>
          <th align="center" width="88%">Recup.Tarj.</th>
          <th align="center" width="88%">Rec.Transf</th>
          <th align="center" width="88%">Recup.Total</th>
          <th align="center" width="88%">Total</th>
          </tr></table>';

$tbl = <<<EOD
$Tit1
EOD;

$this->SetFont('helvetica', '', 8);

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
      $TarjetaR=0;
      $EfectivoR=0;
      $ChequeR=0;
      $TransferenciaR=0;
      $status="";
      $contaorden=0;

      $Tit7 ='<table border="1">';

      while($registro=mysql_fetch_array($res)) {
        //$InstO=$registro[institucion];
             $AbnA=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha <= '$FechaAnt'",$link);
                 $Abonado=mysql_fetch_array($AbnA);
                 $AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' fecha>='$Fechai' and fecha <='$Fechaf' AND id<>'$registro[id]'",$link);
                 $AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
                 $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
                 $Dto=mysql_fetch_array($DtoA);
                    $DtoA2=mysql_query("SELECT importe FROM ot WHERE orden ='$registro[orden]'",$link);
                 $Dto2=mysql_fetch_array($DtoA2);              
           $Horaorden1=substr($registro[horaorden],0,5);
           $Horaordenc=$registro[3];
           $Adeudo1=($registro[importe]-($Abonado[0] + $registro[cjaimporte] + $AbonadoD[0] ));
                 $status="";
           if ($Adeudo1<=1){
              if ($registro[cjaimporte]==$registro[importe]){
               $status="Pagado";
               $Ordenventa=$Dto[1];
               $Ordendesc=$Dto[0];
               //$Ordenventot=$Dto[1]-$Dto[0];
               $Ordenventot=$Dto2[importe];
               $Recupera=0;
               $Ingreso1=$registro[cjaimporte];	
             }else{
               if ($Orden1==$registro[orden]){
                 $Horaordenc3=$Horaordenc-$Horaordenc2;
                 if ($Horaordenc3>=5){	
                   $status="Recup. Inmed.";
                   $Ordenventa=0;
                   $Ordendesc=0;
                   $Ordenventot=0;
                   $Recupera=0;
                   $Ingreso1=$registro[cjaimporte];
                   $Adeudo1=0;
                   //$Recupera3=$registro[cjaimporte];
                 }else{
                   $status="Pagado";
                   $Ordenventa=0;
                   $Ordendesc=0;
                   $Ordenventot=0;
                   $Recupera=0;
                   $Ingreso1=$registro[cjaimporte];
                   $Adeudo1=0;
                 }
               }else{
                 if ($registro[cjafecha]==$registro[fecha]){	
                   if ($Horaorden1<=$HoraI){
                     $status="Recuperac.";
                     $Ordenventa=0;
                     $Ordendesc=0;
                     $Ordenventot=0;
                     $Recupera=$registro[cjaimporte];
                     $Ingreso1=0;
                   }else{
                     $status="Pagado";
                     $Ordenventa=$Dto[1];
                     $Ordendesc=$Dto[0];
                     //$Ordenventot=$Dto[1]-$Dto[0];
                     $Ordenventot=$Dto2[importe];
                     $Recupera=0;
                     $Ingreso1=$registro[cjaimporte];	
                   }	
                 }else{	
                   $status="Recuperac.";
                   $Ordenventa=0;
                   $Ordendesc=0;
                   $Ordenventot=0;
                   $Recupera=$registro[cjaimporte];
                   $Ingreso1=0;	
                   $Recupera3=$registro[cjaimporte];
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
               $Recupera=$registro[cjaimporte];
               $Ingreso1=0;	
             }else{
               $status="Recuperac.";
               $Ordenventa=0;
               $Ordendesc=0;
               $Ordenventot=0;
               $Recupera=$registro[cjaimporte];
               $Ingreso1=0;
               $Adeudo1=0;
             }	
           }else{
             if ($Abonado[0]<>0){	
               $status="Recuperac.";
               $Ordenventa=0;
               $Ordendesc=0;
               $Ordenventot=0;
               $Recupera=$registro[cjaimporte];
               $Ingreso1=0;
               $Adeudo1=0;
               $Recupera3=$registro[cjaimporte];
             }else{
               $status="C / Adeudo";
               $Abonos=$Abonado[0];
               $Ordenventa=$Dto[1];
               $Ordendesc=$Dto[0];
               //$Ordenventot=$Dto[1]-$Dto[0];
               $Ordenventot=$Dto2[importe];

               $Recupera=0;
               $Ingreso1=$registro[cjaimporte];
             }
           }					
         }
        if($InstO<>$registro[institucion]){
          if($Ini==1){

              
             $Recupera2=$Recupera2+$Recupera;
              $TotAdeudo=$TotAdeudo+$Adeudo1;
              $Importe=$Importe+$Ordenventa-$Abonos;
              $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
              $Totdia=$TotalOrd;
              $TotalIngreso=$TotalIngreso+$registro[cjaimporte];
              $Descuento=$Descuento+$Ordendesc;
              $Ingreso=$Ingreso+$Ingreso1;
              $IngTot=$Ingreso+$Recupera2;
              $Saldo=$Saldo+($registro[importe]-($Abonado[0] + $registro[cjaimporte]));
              $Recupera3=0;
              $Abonos=0;
              $Ini=$Ini+1;
              $InstO=$registro[institucion];
              if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
               $no=$no+1;
             }else{
               $no=$no+0;
             }


           }else{


             $InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$InstO'");
               $NomB    = mysql_fetch_array($InstB);

               if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;

           
             
             
             
            
             
            $html1='<tr style="background-color: '.$Fdo.';color: #000;">
            <th align="CENTER" width="40%" height="20">'.$InstO.'</th>
            <th align="left" width="300%" height="20">- '.$NomB[nombre].'</th>
            <th align="right" width="88%" height="20">'.number_format($Totdia,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($TotAdeudo,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($Efectivo,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($Tarjeta,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($Transferencia,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($Efectivo+$Tarjeta+$Transferencia,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($EfectivoR,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($TarjetaR,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($TransferenciaR,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($EfectivoR+$TarjetaR+$TransferenciaR,"2").'</th>
            <th align="right" width="88%" height="20">'.number_format($Efectivo+$Tarjeta+$Transferencia+$EfectivoR+$TarjetaR+$TransferenciaR,"2").'</th>
            </tr>';

              $Tit2 = $Tit2 . $html1;

              $no=0;

							$nRng++;	
							

							$Tarjetas=$Tarjeta+$TarjetaR;
	
							$Totalgral=$IngTot-($Tarjeta+$TarjetaR);

							$Gralefectivo=$Efectivo+$EfectivoR;

							$Graltarjeta=$Tarjeta+$TarjetaR;

							$Gralcheque=$Cheque+$ChequeR;

							$Gral1=$Efectivo+$Tarjeta+$Cheque;
							$Gral2=$EfectivoR+$TarjetaR+$ChequeR;
							$Gral3=$Efectivo+$Tarjeta+$Cheque+$EfectivoR+$TarjetaR+$ChequeR;
							
							$Totgraldia=$Totgraldia+$Totdia;
							 $Totgraladeudo=$Totgraladeudo+$TotAdeudo;
							 $Totgralingreso=$Totgralingreso+$Ingreso;
							 $Totgralrecupera=$Totgralrecupera+$Recupera2;
							 $Totgralingtot=$Totgralingtot+$IngTot;
							 $Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
							 $Grantotalgral=$Grantotalgral+$Totalgral;
							 $Totalefectivo=$Totalefectivo+$Efectivo;
							 $TotalTransferencia=$TotalTransferencia+$Transferencia;
							 $TotalTransferenciaR=$TotalTransferenciaR+$TransferenciaR;
							 $TotalefectivoR=$TotalefectivoR+$EfectivoR;
							 $Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
							 $Totaltarjeta=$Totaltarjeta+$Tarjeta;
							 $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
							 $Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;
							 $Totalcheque=$Totalcheque+$Cheque;
							 $TotalchequeR=$TotalchequeR+$ChequeR;
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
							// $InstO=$registro[institucion];
							$contaorden=0;
							 $Tarjeta=0;
							 $Efectivo=0;
							 $Cheque=0;
							 $Transferencia=0;
							 $TarjetaR=0;
							 $EfectivoR=0;
							 $ChequeR=0;
							 $TransferenciaR=0;
							 $status="";
                             
              //</table>";
              $Recupera2=$Recupera2+$Recupera;
              $TotAdeudo=$TotAdeudo+$Adeudo1;
              $Importe=$Importe+$Ordenventa-$Abonos;
              $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
              $Totdia=$TotalOrd;
              $TotalIngreso=$TotalIngreso+$registro[cjaimporte];
              $Descuento=$Descuento+$Ordendesc;
              $Ingreso=$Ingreso+$Ingreso1;
              $IngTot=$Ingreso+$Recupera2;
              $Saldo=$Saldo+($registro[importe]-($Abonado[0] + $registro[cjaimporte]));
              $Recupera3=0;
              $Abonos=0;
              $Ini=$Ini+1;
              $InstO=$registro[institucion];
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
 
//                <table align="center" width='100%' border='0' cellspacing='1' cellpadding='0'>";
            
          //  </table>";
          $Recupera2=$Recupera2+$Recupera;
          $TotAdeudo=$TotAdeudo+$Adeudo1;
          $Importe=$Importe+$Ordenventa-$Abonos;
          $TotalOrd=$TotalOrd+$Ordenventot-$Abonos;
          $Totdia=$TotalOrd;
          $TotalIngreso=$TotalIngreso+$registro[cjaimporte];
          $Descuento=$Descuento+$Ordendesc;
          $Ingreso=$Ingreso+$Ingreso1;
          $IngTot=$Ingreso+$Recupera2;
          $Saldo=$Saldo+($registro[importe]-($Abonado[0] + $registro[cjaimporte]));
          $Recupera3=0;
          $Abonos=0;
          $contaorden=$contaorden+1;
          //$InstO=$registro[institucion];
          $Ini=$Ini+1;
        //}else{
         //echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
        //}	   
          //$Orden1=$registro[orden];
    
      }
      $Orden1=$registro[orden];
      $Horaordenc2=$Horaordenc;
      if( $status == "Recuperac." ){
                 if($registro[tpago]=="Tarjeta"){
                      $TarjetaR=$TarjetaR+$registro[cjaimporte];
                 }elseif($registro[tpago]=="Cheque"){
                      $ChequeR=$ChequeR+$registro[cjaimporte];
                 }elseif($registro[tpago]=="Transferencia"){
                      $TransferenciaR=$TransferenciaR+$registro[cjaimporte];
                 }elseif($registro[tpago]=="Efectivo"){
                      $EfectivoR=$EfectivoR+$registro[cjaimporte];
                 }          
             }else{
                 if($registro[tpago]=="Cheque"){
                    $Cheque=$Cheque+$registro[cjaimporte];
                 }elseif($registro[tpago]=="Tarjeta"){
                    $Tarjeta=$Tarjeta+$registro[cjaimporte];
                }elseif($registro[tpago]=="Transferencia"){
                    $Transferencia=$Transferencia+$registro[cjaimporte];
                 }elseif($registro[tpago]=="Efectivo"){
                    $Efectivo=$Efectivo+$registro[cjaimporte];
               }

    }

        }//fin while    
    
        $InstB   = mysql_query("SELECT nombre FROM inst WHERE institucion='$InstO'");
        $NomB    = mysql_fetch_array($InstB);


              $html2='<tr style="background-color: '.$Fdo.';color: #000;">
              <th align="CENTER" width="40%" height="20">'.$InstO.'</th>
              <th align="left" width="300%">- '.$NomB[nombre].'</th>
              <th align="right" width="88%">'.number_format($Totdia,"2").'</th>
              <th align="right" width="88%">'.number_format($TotAdeudo,"2").'</th>
              <th align="right" width="88%">'.number_format($Efectivo,"2").'</th>
              <th align="right" width="88%">'.number_format($Tarjeta,"2").'</th>
              <th align="right" width="88%">'.number_format($Transferencia,"2").'</th>
              <th align="right" width="88%">'.number_format($Efectivo+$Tarjeta+$Transferencia,"2").'</th>
              <th align="right" width="88%">'.number_format($EfectivoR,"2").'</th>
              <th align="right" width="88%">'.number_format($TarjetaR,"2").'</th>
              <th align="right" width="88%">'.number_format($EfectivoR+$TarjetaR,"2").'</th>
              <th align="right" width="88%">'.number_format($TransferenciaR,"2").'</th>
              <th align="right" width="88%">'.number_format($EfectivoR+$TarjetaR+$TransferenciaR,"2").'</th>
              </tr>';

              $Tit3 = $html2;

              
              $Totgraldia=$Totgraldia+$Totdia;
              $Totgraladeudo=$Totgraladeudo+$TotAdeudo;
              $Totgralingreso=$Totgralingreso+$Ingreso;
              $Totgralrecupera=$Totgralrecupera+$Recupera2;
              $Totgralingtot=$Totgralingtot+$IngTot;
              $Totgraltarjetas=$Totgraltarjetas+$Tarjetas;
              $Grantotalgral=$Grantotalgral+$Totalgral;
              $Totalefectivo=$Totalefectivo+$Efectivo;
              $TotalTransferencia=$TotalTransferencia+$Transferencia;
              $TotalTransferenciaR=$TotalTransferenciaR+$TransferenciaR;
              $TotalefectivoR=$TotalefectivoR+$EfectivoR;
              $Graltotalefectivo=$Graltotalefectivo+$Gralefectivo;
              $Totaltarjeta=$Totaltarjeta+$Tarjeta;
              $TotaltarjetaR=$TotaltarjetaR+$TarjetaR;
              $Graltotaltarjeta=$Graltotaltarjeta+$Graltarjeta;
              $Totalcheque=$Totalcheque+$Cheque;
              $TotalchequeR=$TotalchequeR+$ChequeR;
              $Graltotalcheque=$Graltotalcheque+$Gralcheque;
              $Totalgral1=$Totalgral1+$Gral1;
              $Totalgral2=$Totalgral2+$Gral2;
               $Totalgral3=$Totalgral3+$Gral3;

              $Tit4 ='<tr style="background-color: #C0C0C0; color:#000;">
              <th align="CENTER" colspan="2" width="170%" height="20"><b>Total de Instituciones '.$nRng.'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($Totgraldia,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($Totgraladeudo,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($Totalefectivo,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($Totaltarjeta,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($TotalTransferencia,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($Totalefectivo+$Totaltarjeta+$TotalTransferencia,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($TotalefectivoR,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($TotaltarjetaR,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($TotalTransferenciaR,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($TotalefectivoR+$TotaltarjetaR+$TotalTransferenciaR,"2").'</b></th>
              <th align="right" width="88%"><b>$ '.number_format($Totalefectivo+$Totaltarjeta+$TotalTransferencia+$TotalefectivoR+$TotaltarjetaR+$TotalTransferenciaR,"2").'</b></th>
              </tr></table><br>';
       




             $Tit5='<table width="100%" border="0" align="center"><tr><th align="center"><table width="80%" border="1" align="center"><tr><th height="25"><b>VENTAS TOTALES GENERALES:</b></th>
             <th align="right"><b> $ '.number_format($Totgraldia,"2").'</b></th>
             </tr><tr style="background-color: #CDCDFA; color:#000;"><th height="25"><b>ADEUDOS GENERALES:</b></th>
             <th align="right"><b> '.number_format($Totgraladeudo,"2").'</b></th></tr><tr><th height="25"><b>INGRESO DEL DIA GENERAL:</b></th>
             <th align="right"><b> $ '.number_format($Totgralingreso,"2").'</b></th>
             </tr><tr style="background-color: #CDCDFA; color:#000;"><th height="25"><b>RECUPERACIONES GENERALES:</b></th>
             <th align="right"><b> '.number_format($Totgralrecupera,"2").'</b></th></tr><tr><th height="25"><b>TOTAL GENERALES:</b></th>
             <th align="right"><b> $ '.number_format($Totgralingtot,"2").'</b></th></tr>
             <tr style="background-color: #CDCDFA; color:#000;"><th height="25"><b>TARJETA GENERAL:</b></th>
             <th align="right"><b> $ '.number_format($Totgraltarjetas,"2").'</b></th></tr><tr>
             <th height="25"><b>GRAN TOTAL GRAL.:</b></th>
             <th align="right"><b> $ '.number_format($Grantotalgral,"2").'</b></th>
             </tr></table></th><th><table><tr style="background-color: #0d486a; color:#FFF;" align="center"><th height="20"><b>TIPO PAGO GENERAL</b></th>
             <th><b>DEL DIA GENERAL</b></th><th><b>RECUPERACION GENERAL</b></th><th><b>GRAN TOTAL GENERAL</b></th></tr><tr><th height="25"><b>Efectivo</b></th>
             <th align="right"><b>'.number_format($Totalefectivo,"2").'</b></th>
             <th align="right"><b>'.number_format($TotalefectivoR,"2").'</b></th>
             <th align="right"><b>'.number_format($TotalefectivoR+$Totalefectivo,"2").'</b></th></tr>
             <tr style="background-color: #CDCDFA;color: #000;">
             <th height="25"><b>Tarjeta</b></th>
             <th align="right"><b>'.number_format($Totaltarjeta,"2").'</b></th>
             <th align="right"><b>'.number_format($TotaltarjetaR,"2").'</b></th>
             <th align="right"><b>'.number_format($TotaltarjetaR+$Totaltarjeta,"2").'</b></th></tr><tr>
             <th height="25"><b>Cheque</b></th><th align="right"><b>'.number_format($Totalcheque,"2").'</b></th>
             <th align="right"><b>'.number_format($TotalchequeR,"2").'</b></th>
             <th align="right"><b>'.number_format($TotalchequeR+$Totalcheque,"2").'</b></th></tr>
             
             <tr style="background-color: #CDCDFA;color: #000;">
             <th height="25"><b>Transferencia</b></th>
             <th align="right"><b>'.number_format($TotalTransferencia,"2").'</b></th>
             <th align="right"><b>'.number_format($TotalTransferenciaR,"2").'</b></th>
             <th align="right"><b>'.number_format($TotalTransferenciaR+$TotalTransferencia,"2").'</b></th></tr>
             
             <tr style="background-color: #C0C0C0; color:#000;">
             <th height="25"></th><th align="right"><b>$ '.number_format($Totgralingreso,"2").'</b></th>
             <th align="right"><b>$ '.number_format($TotalefectivoR+$TotaltarjetaR,"2").'</b></th>
             <th align="right"><b>$ '.number_format($Totgralingreso+$TotalefectivoR+$TotaltarjetaR,"2").'</b></th>
             </tr></table></th></tr></table>';
  }
	
$tbl = <<<EOD
$Tit7
$Tit2
$Tit3
$Tit4
EOD;
$pdf->writeHTML($tbl, true, false, false, false, 'C');

$tbl2 = <<<EOD
$Tit5
EOD;
$pdf->writeHTML($tbl2, true, false, true, false, '');

// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("'Reporte'.pdf'", 'I');

mysql_close();
?>
