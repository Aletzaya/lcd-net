<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $level       = $check['level'];

  $Usr= $Recepcionista=$_REQUEST[Recepcionista];
  
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

  $Fecha=$_REQUEST[Fecha];

  $HoraI=$_REQUEST[HoraI];

  $HoraF=$_REQUEST[HoraF];
  
  $Completo=$_REQUEST[Completo];
  
  if ($Completo=='Dia_Completo'){
	  
 	 $HoraI='00:00';

  	 $HoraF='23:59';
  }

  $Suc                  =       $_COOKIE['TEAM'];        //Sucursal 

  $OtNumA=mysql_query($OtNum,$link);
  
  $Ordenes=mysql_fetch_array($OtNumA);
  
  $Hora=date("h:i:s");




  
$doc_title    = "Relacion de Ordenes de trabajo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');


    require ("config.php");

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
              $Sucursal2= $Sucursal2 . "Camarones - ";
              if($Sucursal==""){
                  $Sucursal= $Sucursal . " ot.suc=6";
              }else{
                  $Sucursal= $Sucursal . " OR ot.suc=6";
              }
          }
      }
  
  if($_REQUEST[Institucion]=='CONTA'){
  
       if($Recepcionista=="*"){
  
          if ($Sucursal <> '*') {
              
              $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND ($Sucursal) order by ot.orden,cja.id";
      
              $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND ($Sucursal)";
              
              
          }else{
  
              $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' order by ot.orden,cja.id";
      
              $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85'";
              
          }
          
      }else{
          
          if ($Sucursal <> '*') {
  
              $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND cja.usuario='$Recepcionista' AND ($Sucursal) order by ot.orden,cja.id";
      
              $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85'  AND recepcionista='$Recepcionista' AND ($Sucursal)";
  
          }else{
  
              $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73' AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85' AND cja.usuario='$Recepcionista' order by ot.orden,cja.id";
      
              $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion>='55' AND institucion<>'56' AND institucion<>'57' AND institucion<>'61' AND institucion<>'62' AND institucion<>'64' AND institucion<>'65' AND institucion<>'66' AND institucion<>'67' AND institucion<>'68' AND institucion<>'69' AND institucion<>'70' AND institucion<>'71' AND institucion<>'73'  AND institucion<>'75' AND institucion<>'76' AND institucion<>'77' AND institucion<>'78' AND institucion<>'79' AND institucion<>'80' AND institucion<>'81' AND institucion<>'83' AND institucion<='85'  AND recepcionista='$Recepcionista'";
          
          }
          
      }
  }else{
  
      if($_REQUEST[Institucion]=='*'){
  
             if($Recepcionista=="*"){
              
              if ($Sucursal <> '*') {
                  
                  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
                             cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
                             FROM cja,ot
                             WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND ($Sucursal) 
                             ORDER BY ot.institucion,ot.orden";
                      
                      $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND ($Sucursal)";
  
              }else{
  
                  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,
                             cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
                             FROM cja,ot
                             WHERE cja.fecha='$Fecha' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' 
                             ORDER BY ot.institucion,ot.orden";
                      
                      $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha'";
                      
              }
  
         }else{
             
                 if ($Sucursal <> '*') {
                  
                  //$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.suc='$Suc' order by ot.orden,cja.id";
                  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
                  FROM cja,ot
                  WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND ($Sucursal)
                  order by ot.institucion,ot.orden";
      
                  //$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND cja.suc='$Suc'";
                  $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND ($Sucursal)";
                  
              }else{
      
                  //$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.suc='$Suc' order by ot.orden,cja.id";
                  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden
                  FROM cja,ot
                  WHERE cja.fecha='$Fecha' AND cja.usuario='$Recepcionista' AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' 
                  order by ot.institucion,ot.orden";
      
                  //$OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND cja.suc='$Suc'";
                  $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista'";
              
              }
  
         }
      }else{
  
         if($Recepcionista=="*"){
             
             if ($Sucursal <> '*') {
                 
                $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by ot.orden,cja.id";
          
                $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion='$Institucion' AND ($Sucursal)";
  
                 
             }else{
  
                $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by ot.orden,cja.id";
          
                $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND institucion='$Institucion'";
                
             }
  
         }else{
             
            if ($Sucursal <> '*') {
                
                $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' AND ($Sucursal) order by ot.orden,cja.id";
      
                //$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,inst.alias,ot.fecha,ot.hora as horaorden FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND ot.institucion=inst.institucion  AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' order by ot.orden";
      
                $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND institucion='$Institucion' AND ($Sucursal)";
                
            }else{
  
                $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,ot.institucion,ot.fecha,ot.hora as horaorden FROM cja,ot WHERE cja.fecha='$Fecha' AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' AND cja.usuario='$_REQUEST[Recepcionista]' AND cja.orden=ot.orden AND ot.institucion='$Institucion' order by ot.orden,cja.id";
      
                //$cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe,ot.status,cja.usuario,inst.alias,ot.fecha,ot.hora as horaorden FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND ot.institucion=inst.institucion  AND ot.orden=cja.orden AND cja.hora >='$HoraI' AND cja.hora <='$HoraF' order by ot.orden";
      
                $OtNum="SELECT count(orden) FROM ot WHERE fecha='$Fecha' AND recepcionista='$Recepcionista' AND institucion='$Institucion'";
            
            }
  
         }
  
      }
  
  }
  

  
  //$InsA=mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'",$link);
  //$Ins=mysql_fetch_array($InsA);
  
  //echo $cSql;
  if($level==9 or $level==7){
  
  
      $UpA=mysql_query($cSql,$link);
  
  }
  
  $Titulo = "Corte de Caja del $Fecha de $HoraI a $HoraF ";
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

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.' Sucursal:'.$Sucursal2.'hrs.</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Recepcionista: '.$Recepcionista.'</td></tr></table>', false, 0);

    $this->SetFont('Helvetica', 'BI', 8);

    $this->writeHTML('<br><br><table align="center" width="1250" border="1" cellspacing="1" cellpadding="0">
    <tr>
    <td align="center" bgcolor="#808B96" width="75">#Recibo</td>
    <td align="center" bgcolor="#808B96" width="95">No.Ord</td>
    <td align="center" bgcolor="#808B96" width="115">Recepcionista</td>
    <td align="center" bgcolor="#808B96" width="85">Fecha</td>
    <td align="center" bgcolor="#808B96" width="65">Hora</td>
    <td align="center" bgcolor="#808B96" width="80">Status O.</td>
    <td align="center" bgcolor="#808B96" width="85">Venta</td>
    <td align="center" bgcolor="#808B96" width="85">Descuento</td>
    <td align="center" bgcolor="#808B96" width="85">Total Vta</td>
    <td align="center" bgcolor="#808B96" width="70">Recup.</td>
    <td align="center" bgcolor="#808B96" width="80">Ingreso</td>
    <td align="center" bgcolor="#808B96" width="80">Ing.Total</td>
    <td align="center" bgcolor="#808B96" width="75">Adeudo</td>
    <td align="center" bgcolor="#808B96" width="90">Tpo.pago</td>
    </tr></table>', false, 0);
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







//***** INICIA REPORTE ***//



if(!$res=mysql_fetch_array($UpA)){
    //echo "<div align='center'>";
    //echo "<font face='verdana' size='2'>No se encontraron resultados</font>";
    //echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=1'>";
    //echo "Regresar</a></font>";
     //echo "</div>";
 }else{
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
                  $AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha = '$Fecha' AND id<>'$registro[0]'",$link);
                  $AbonadoD=mysql_fetch_array($AbnD); //Algun abono de la misma orden y el mismo dia             
                  $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]'",$link);
                  $Dto=mysql_fetch_array($DtoA);              
                  $Horaorden1=substr($registro[horaorden],0,5);
                  $Horaordenc=$registro[3];
                  $Adeudo1=($registro[6]-($Abonado[0] + $registro[4] + $AbonadoD[0] ));


                    if( ($nRngg % 2) > 0 ){
                        $Fdo='#FFFFFF';
                      }
                        else{
                            $Fdo='#CDCDFA';
                      }   
  
                   


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

                    $pdf->SetFont('Helvetica', '', 7, '', 'false');

                    $html='<table border="0">
                    <tr bgcolor='.$Fdo.'>
                    <td align="CENTER" width="75" >'.$registro[id].'</td>
                    <td align="CENTER" width="95" >'.$registro[institucion].' - '.$registro[orden].'</td>
                    <td align="CENTER" width="115" >'.$registro[8].'</td>
                    <td align="CENTER" width="85" >'.$registro[fecha].'</td>
                    <td align="CENTER" width="65" >'.substr($registro[3],0,5).'</td>
                    <td align="CENTER" width="80" >'.$status.'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventa,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordendesc,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventot,"2").'</td>
                    <td align="right"  width="70" >'.number_format($Recupera,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Ingreso1,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Recupera+$Ingreso1,"2").'</td>
                    <td align="right"  width="75" >'.number_format($Adeudo1,"2").'</td>
                    <td align="center" width="90" >'.$registro[5].'</td></tr></table>';
                   
                    $contaorden=$contaorden+1;
                    

                     $pdf->writeHTML($html,true,false,true,false,'');

                 



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



                         if ($Orden1==$registro[orden] or $Fecha<>$registro[fecha]){
                            $no=$no+1;
                        }else{
                            $no=$no+0;
                        }


                    }else{

                        $html='<table  border="0" >
                        <tr>
                        <td width="75"></td>
                        <td width="95"></td>
                        <td width="115"></td>
                        <td width="85"></td>
                        <td width="65"></td>
                        <td width="80"></td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($Importe,'2').'</td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($Descuento,'2').'</td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($TotalOrd,'2').'</td>
                        <td align="right" width="70" bgcolor="#d5d6dd">'.number_format($Recupera2,'2').'</td>
                        <td align="right" width="80" bgcolor="#d5d6dd">'.number_format($Ingreso,'2').'</td>
                        <td align="right" width="80" bgcolor="#d5d6dd">'.number_format($IngTot,'2').'</td>
                        <td align="right" width="75" bgcolor="#d5d6dd">'.number_format($TotAdeudo,'2').'</td>
                        <td></td width="90"></tr></table>';

                        $pdf->writeHTML($html,true,false,true,false,'');

                        $contaorden2=$contaorden-$no;


 
                   
                   
                    $no=0;
                    
                   $Tarjetas=$Tarjeta+$TarjetaR;
                   $Transferencias=$Transferencia+$TransferenciaR;
                   $Cheques=$Cheque+$ChequeR;
                   $Totalgral=$IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR);


                   $Gralefectivo=$Efectivo+$EfectivoR;
                   $Graltarjeta=$Tarjeta+$TarjetaR;
                   $Gralcheque=$Cheque+$ChequeR;
                   $Graltrans=$Transferencia+$TransferenciaR;
                   $Gral1=$Efectivo+$Tarjeta+$Cheque+$Transferencia;
                   $Gral2=$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;
                   $Gral3=$Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;

                    $html='<table width="150%" border="0" align="center">
                    <tr>
                    <td bgcolor="#CCCCCC" width="60%">ORDENES ABIERTAS : '.$contaorden2.'</td></tr>
                    
                    </table><table width="150%" border="0" align="center">
                    <tr>
                    <td width="10%"></td><td width="70%"><table width="85%" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#FFFFFF">

                    <tr bordercolor="#CCCCCC">
                    <td  width="100%" align="right">VENTAS TOTALES :</td>
                    <td  width="70%" align="right">'.number_format($Totdia,'2').'</td></tr>
                    
                    <tr bordercolor="#CCCCCC">
                    <td align="right">ADEUDOS :</td>
                    <td width="70%" align="right">'.number_format($TotAdeudo,'2').'</td></tr>

                    <tr bordercolor="#CCCCCC">
                    <td align="right">INGRESO DEL DIA :</td>
                    <td width="70%" align="right">'.number_format($Ingreso,'2').'</td></tr>

                    <tr bordercolor="#CCCCCC">
                    <td align="right">RECUPERACIONES :</td>
                    <td width="70%" align="right">'.number_format($Recupera2,'2').'</td></tr>
                   
                    

                    <tr bordercolor="#CCCCCC">
                    <td bgcolor="#CCCCCC" align="right">TOTAL :</td>
                    <td bgcolor="#CCCCCC" width="70%" align="right">'.number_format($IngTot,'2').'</td></tr>
                    

                    
          
                    <tr bordercolor="#CCCCCC">
                    <td align="right">TARJETA :</td>
                    <td width="70%" align="right">'.number_format($Tarjeta+$TarjetaR,'2').'</td></tr>

                   
                    <tr bordercolor="#CCCCCC">
                    <td align="right">TRANSFERENCIA :</td>
                    <td width="70%" align="right">'.number_format($Transferencia+$TransferenciaR,'2').'</td></tr>

                    

                   <tr bordercolor="#CCCCCC">
                   <td align="right">CHEQUE :</td>
                   <td width="70%" align="right">'.number_format($Cheque+$ChequeR,'2').'</td></tr>
                
                    <tr bordercolor="#CCCCCC">
                    <td bgcolor="#CCCCCC" align="right">TOTAL GRAL.:</td>
                    <td bgcolor="#CCCCCC" width="70%" align="right">'.number_format($IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR),'2').'</td></tr></table></td>                  
                  

                    

                   <td><table width="90%" align="center" border="1" align="center" bordercolor="#FFFFFF">
                   
                   <tr bordercolor="#CCCCCC">
                   <td bgcolor="#CCCCCC" align="center">TIPO PAGO</td>
                   <td bgcolor="#CCCCCC" align="center">DEL DIA</td>
                   <td bgcolor="#CCCCCC" align="center">RECUPERACION</td>
                   <td bgcolor="#CCCCCC" align="center">TOTAL</td></tr>

                  

                   

                    <tr bordercolor="#CCCCCC">
                    <td align="center">Efectivo</td>
                    <td align="right">'.number_format($Efectivo,"2").'</td>
                    <td align="right">'.number_format($EfectivoR,"2").'</td>
                    <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td></tr>


                    

                   <tr bordercolor="#CCCCCC">
                   <td align="center">Tarjeta</td>
                   <td align="right">'.number_format($Tarjeta,"2").'</td>
                   <td align="right">'.number_format($TarjetaR,"2").'</td>
                   <td align="right">'.number_format($Tarjeta+$TarjetaR,"2").'</td></tr>


                    


                   <tr bordercolor="#CCCCCC">
                   <td align="center">Cheque</td>
                   <td align="right">'.number_format($Cheque,"2").'</td>
                   <td align="right">'.number_format($ChequeR,"2").'</td>
                   <td align="right">'.number_format($Cheque+$ChequeR,"2").'</td></tr>



                   <tr bordercolor="#CCCCCC">
                   <td align="center">Transferencia</td>
                   <td align="right">'.number_format($Transferencia,"2").'</td>
                   <td align="right">'.number_format($TransferenciaR,"2").'</td>
                   <td align="right">'.number_format($Transferencia+$TransferenciaR,"2").'</td></tr>
             

                   <tr bordercolor="#CCCC+'.$TransferenciaRCC.'">
                   <td>&nbsp;</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,"2").'</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td></tr></table></td></tr></table>';
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
                    // $InstO=$registro[institucion];
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
                                    
            
                    $html='<table align="center" width="1250" border="0" cellspacing="1" cellpadding="0">
                    <tr></tr>
					<tr></tr>
					<tr></tr>
                    <tr>
                    <td align="center" bgcolor="#808B96" width="75">#Recibo</td>
                    <td align="center" bgcolor="#808B96" width="95">No.Ord</td>
                    <td align="center" bgcolor="#808B96" width="115">Recepcionista</td>
                    <td align="center" bgcolor="#808B96" width="85">Fecha</td>
                    <td align="center" bgcolor="#808B96" width="65">Hora</td>
                    <td align="center" bgcolor="#808B96" width="80">Status O.</td>
                    <td align="center" bgcolor="#808B96" width="85">Venta</td>
                    <td align="center" bgcolor="#808B96" width="85">Descuento</td>
                    <td align="center" bgcolor="#808B96" width="85">Total Vta</td>
                    <td align="center" bgcolor="#808B96" width="70">Recup.</td>
                    <td align="center" bgcolor="#808B96" width="80">Ingreso</td>
                    <td align="center" bgcolor="#808B96" width="80">Ing.Total</td>
                    <td align="center" bgcolor="#808B96" width="75">Adeudo</td>
                    <td align="center" bgcolor="#808B96" width="90">Tpo.pago</td></tr>

                    <tr bgcolor='.$Fdo.'>
                    <td align="CENTER" width="75" >'.$registro[id].'</td>
                    <td align="CENTER" width="95" >'.$registro[institucion].' - '.$registro[orden].'</td>
                    <td align="CENTER" width="115" >'.$registro[8].'</td>
                    <td align="CENTER" width="85" >'.$registro[fecha].'</td>
                    <td align="CENTER" width="65" >'.substr($registro[3],0,5).'</td>
                    <td align="CENTER" width="80" >'.$status.'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventa,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordendesc,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventot,"2").'</td>
                    <td align="right"  width="70" >'.number_format($Recupera,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Ingreso1,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Recupera+$Ingreso1,"2").'</td>
                    <td align="right"  width="75" >'.number_format($Adeudo1,"2").'</td>
                    <td align="center" width="90" >'.$registro[5].'</td></tr></table>';
                    $contaorden=$contaorden+1;
                   

                     $pdf->writeHTML($html,true,false,true,false,'');

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


                   $html='<table border="0" >
                    <tr bgcolor='.$Fdo.'>
                    <td align="CENTER" width="75" >'.$registro[id].'</td>
                    <td align="CENTER" width="95" >'.$registro[institucion].' - '.$registro[orden].'</td>
                    <td align="CENTER" width="115" >'.$registro[8].'</td>
                    <td align="CENTER" width="85" >'.$registro[fecha].'</td>
                    <td align="CENTER" width="65" >'.substr($registro[3],0,5).'</td>
                    <td align="CENTER" width="80" >'.$status.'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventa,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordendesc,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventot,"2").'</td>
                    <td align="right"  width="70" >'.number_format($Recupera,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Ingreso1,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Recupera+$Ingreso1,"2").'</td>
                    <td align="right"  width="75" >'.number_format($Adeudo1,"2").'</td>
                    <td align="center" width="90" >'.$registro[5].'</td></tr></table>';

                     $pdf->writeHTML($html,true,false,true,false,'');

            
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
        $nRngg++;
    }//fin while
    

    
                        $html='<table  border="0" >
                        <tr>
                        <td width="75"></td>
                        <td width="95"></td>
                        <td width="115"></td>
                        <td width="85"></td>
                        <td width="65"></td>
                        <td width="80"></td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($Importe,'2').'</td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($Descuento,'2').'</td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($TotalOrd,'2').'</td>
                        <td align="right" width="70" bgcolor="#d5d6dd">'.number_format($Recupera2,'2').'</td>
                        <td align="right" width="80" bgcolor="#d5d6dd">'.number_format($Ingreso,'2').'</td>
                        <td align="right" width="80" bgcolor="#d5d6dd">'.number_format($IngTot,'2').'</td>
                        <td align="right" width="75" bgcolor="#d5d6dd">'.number_format($TotAdeudo,'2').'</td>
                        <td></td width="90"></tr></table>';

                        $pdf->writeHTML($html,true,false,true,false,'');

                        $contaorden2=$contaorden-$no;




                    $no=0;
                    
                   $Tarjetas=$Tarjeta+$TarjetaR;
                   $Transferencias=$Transferencia+$TransferenciaR;
                   $Cheques=$Cheque+$ChequeR;
                   $Totalgral=$IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR);


                   $Gralefectivo=$Efectivo+$EfectivoR;
                   $Graltarjeta=$Tarjeta+$TarjetaR;
                   $Gralcheque=$Cheque+$ChequeR;
                   $Graltrans=$Transferencia+$TransferenciaR;
                   $Gral1=$Efectivo+$Tarjeta+$Cheque+$Transferencia;
                   $Gral2=$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;
                   $Gral3=$Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR;

                    $html='<table width="150%" border="0" align="center">
                    <tr>
                    <td bgcolor="#CCCCCC" width="60%">ORDENES ABIERTAS : '.$contaorden2.'</td></tr>
                    
                    </table><table width="150%" border="0" align="center">
                    <tr>
                    <td width="10%"></td><td width="70%"><table width="85%" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#FFFFFF">

                    <tr bordercolor="#CCCCCC">
                    <td  width="100%" align="right">VENTAS TOTALES :</td>
                    <td  width="70%" align="right">'.number_format($Totdia,'2').'</td></tr>
                    
                    <tr bordercolor="#CCCCCC">
                    <td align="right">ADEUDOS :</td>
                    <td width="70%" align="right">'.number_format($TotAdeudo,'2').'</td></tr>

                    <tr bordercolor="#CCCCCC">
                    <td align="right">INGRESO DEL DIA :</td>
                    <td width="70%" align="right">'.number_format($Ingreso,'2').'</td></tr>

                    <tr bordercolor="#CCCCCC">
                    <td align="right">RECUPERACIONES :</td>
                    <td width="70%" align="right">'.number_format($Recupera2,'2').'</td></tr>
                   
                    

                    <tr bordercolor="#CCCCCC">
                    <td bgcolor="#CCCCCC" align="right">TOTAL :</td>
                    <td bgcolor="#CCCCCC" width="70%" align="right">'.number_format($IngTot,'2').'</td></tr>
                    

                    
          
                    <tr bordercolor="#CCCCCC">
                    <td align="right">TARJETA :</td>
                    <td width="70%" align="right">'.number_format($Tarjeta+$TarjetaR,'2').'</td></tr>

                   
                    <tr bordercolor="#CCCCCC">
                    <td align="right">TRANSFERENCIA :</td>
                    <td width="70%" align="right">'.number_format($Transferencia+$TransferenciaR,'2').'</td></tr>

                    

                   <tr bordercolor="#CCCCCC">
                   <td align="right">CHEQUE :</td>
                   <td width="70%" align="right">'.number_format($Cheque+$ChequeR,'2').'</td></tr>
                
                    <tr bordercolor="#CCCCCC">
                    <td bgcolor="#CCCCCC" align="right">TOTAL GRAL.:</td>
                    <td bgcolor="#CCCCCC" width="70%" align="right">'.number_format($IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR),'2').'</td></tr></table></td>                  
                  

                    

                   <td><table width="90%" align="center" border="1" align="center" bordercolor="#FFFFFF">
                   
                   <tr bordercolor="#CCCCCC">
                   <td bgcolor="#CCCCCC" align="center">TIPO PAGO</td>
                   <td bgcolor="#CCCCCC" align="center">DEL DIA</td>
                   <td bgcolor="#CCCCCC" align="center">RECUPERACION</td>
                   <td bgcolor="#CCCCCC" align="center">TOTAL</td></tr>

                  

                   

                    <tr bordercolor="#CCCCCC">
                    <td align="center">Efectivo</td>
                    <td align="right">'.number_format($Efectivo,"2").'</td>
                    <td align="right">'.number_format($EfectivoR,"2").'</td>
                    <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td></tr>


                    

                   <tr bordercolor="#CCCCCC">
                   <td align="center">Tarjeta</td>
                   <td align="right">'.number_format($Tarjeta,"2").'</td>
                   <td align="right">'.number_format($TarjetaR,"2").'</td>
                   <td align="right">'.number_format($Tarjeta+$TarjetaR,"2").'</td></tr>


                    


                   <tr bordercolor="#CCCCCC">
                   <td align="center">Cheque</td>
                   <td align="right">'.number_format($Cheque,"2").'</td>
                   <td align="right">'.number_format($ChequeR,"2").'</td>
                   <td align="right">'.number_format($Cheque+$ChequeR,"2").'</td></tr>



                   <tr bordercolor="#CCCCCC">
                   <td align="center">Transferencia</td>
                   <td align="right">'.number_format($Transferencia,"2").'</td>
                   <td align="right">'.number_format($TransferenciaR,"2").'</td>
                   <td align="right">'.number_format($Transferencia+$TransferenciaR,"2").'</td></tr>
             

                   <tr bordercolor="#CCCC+'.$TransferenciaRCC.'">
                   <td>&nbsp;</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,"2").'</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td></tr></table></td></tr></table>';
                   $pdf->writeHTML($html,true,false,true,false,'');


    



//**********  G A S T O S  *************

    if($Usr=='*'){

        $GastosA ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
        . "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
        . "FROM dpag_ref "
        . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
        . "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
        . "WHERE"
        . " date(dpag_ref.fechapago)='$Fecha' "
        . "AND dpag_ref.cancelada=0";


    }else{

        $GastosA ="SELECT dpag_ref.id,cptpagod.referencia,dpag_ref.fechapago,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.tipopago,dpag_ref.usr,"
        . "dpag_ref.fechapago,dpag_ref.recibe,cpagos.concepto,dpag_ref.hospi,dpag_ref.autoriza,dpag_ref.concept,cptpagod.cuenta,dpag_ref.id,dpag_ref.suc "
        . "FROM dpag_ref "
        . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id "
        . "LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
        . "WHERE"
        . " date(dpag_ref.fechapago)='$Fecha' "
        . "AND dpag_ref.usr LIKE '%$Usr%' "
        . "AND dpag_ref.cancelada=0";

    }


    //echo $cSql;
    $Gastos = mysql_query($GastosA);




    $html='<table width="150%" border="1" align="center">
    <tr>
    <td bgcolor="#92f890" width="68%" align="center">GASTOS:</td></tr></table><br>
    <table align="center" border="0">                                          
     <tr>
     <th width="40" align="center">Suc</th>
     <th width="60" align="center">Id</th>
     <th width="200" align="center">Referencia</th>
     <th width="90" align="center">Cuenta</th>
     <th width="90" align="center">Fecha</th>
     <th width="80" align="center">Tipo P.</th>
     <th width="100" align="center">Recibe</th>
     <th width="100" align="center">Autoriza</th>
     <th width="90" align="center">Usr</th>
     <th width="70" align="center">Hospi</th>
     <th width="150" align="center">Concepto</th>
     <th width="100" align="center">Importe</th></tr></table>';
     $pdf->writeHTML($html,true,false,true,false,'');

 while($Gasto=mysql_fetch_array($Gastos)){          

        if( ($nRngg % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#CDCDFA';}    //El resto de la division;
        
       $html='<table  border="0" align="center">
       <tr bgcolor='.$Fdo.'>
       <td width="40"  align="center">'.$Gasto[suc].'</td>
       <td width="60"  align="center">'.$Gasto[id].'</td> 
       <td width="200" align="left">'.$Gasto[referencia].'</td>
       <td width="90" align="right">'.$Gasto[cuenta].'</td>
       <td width="90" align="right">'.$Gasto[fechapago].' &nbsp; </td>
       <td width="80" align="left">'.$Gasto[concepto].' &nbsp; </td>
       <td width="100" align="left">'.ucwords($Gasto[recibe]).' &nbsp; </td>
       <td width="100"  align="left">'.$Gasto[autoriza].' &nbsp; </td>
       <td width="90"  align="left">'.$Gasto[usr].' &nbsp; </td>
       <td width="70"  align="left">'.$Gasto[hospi].' &nbsp; </td>
       <td width="150"  align="left">'.$Gasto[concept].' &nbsp;</td>
       <td width="100" align="right">'.number_format($Gasto[monto],2).' &nbsp;</td></tr></table>';
       $pdf->writeHTML($html,true,false,true,false,'');

        $nImporteg+= $Gasto[monto];              
        $nRngg ++;                        
  }     
  
  $html='<table  border="0" align="center"> 
  <tr>
  <td width="40" align="left"> </td>
  <td width="60" align="left"> </td>
  <td width="200" align="left"> </td>
  <td width="90" align="left"> </td>
  <td width="90" align="left"> </td>
  <td width="80" align="left"> </td>
  <td width="100" align="left"> </td>
  <td width="100" lign="left"> </td>
  <td width="90" align="left"> </td>
  <td width="70" align="left"> </td>
  <td width="150" align="right" bgcolor="#CCCCCC"> Total </td>	
  <td width="100" align="right" bgcolor="#CCCCCC">'.number_format($nImporteg,2).'</td></tr></table>';
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

                          $html='<table width="150%" border="0" align="center">
                          <tr>
                          <td bgcolor="#CCCCCC" width="60%">TOTAL ORDENES ABIERTAS :  '.number_format($Ordenes[0],"0").' </td></tr></table>
                          
                          <table width="150%" border="0" align="center">
                          <tr>
                          <td width="10%"></td><td width="70%"><table width="85%" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#FFFFFF">
      
                          <tr bordercolor="#CCCCCC">
                          <td  width="100%" align="right">VENTAS TOTALES :</td>                        
                          <td  width="70%" align="right">'.number_format($Totgraldia,'2').'</td></tr>
       

                         <tr bordercolor="#CCCCCC">
                         <td  align="right">ADEUDOS GENERALES:</td>
                         <td  width="70%" align="right">'.number_format($Totgraladeudo,'2').'</td></tr>



                        <tr bordercolor="#CCCCCC">
                        <td align="right" >INGRESO DEL DIA GENERAL:</td>
                        <td width="70%" align="right">'.number_format($Totgralingreso,'2').'</td></tr>

                       <tr bordercolor="#CCCCCC">
                       <td align="right">RECUPERACIONES GENERALES:</td>
                       <td  width="70%" align="right">'.number_format($Totgralrecupera,'2').'</td></tr>

                       <tr bordercolor="#CCCCCC">
                       <td bgcolor="#CCCCCC" align="right">TOTAL GENERALES:</td>
                       <td width="70%" bgcolor="#CCCCCC" align="right">'.number_format($Totgralingtot,'2').'</td></tr>
                       

                       <tr bordercolor="#CCCCCC">
                       <td align="right">TARJETA GENERAL:</td>
                       <td width="70%" align="right">'.number_format($Totgraltarjetas,'2').'</td></tr>
                      

                       <tr bordercolor="#CCCCCC">
                       <td align="right">TRANSFERENCIA GENERAL:</td>
                       <td width="70%" align="right">'.number_format($Totaltranferencia+$TotaltranferenciaR,'2').'</td></tr>
                       
                       <tr bordercolor="#CCCCCC">
                       <td align="right">CHEQUE GENERAL:</td>
                       <td width="70%" align="right">'.number_format($Totalcheque+$TotalchequeR,'2').'</td></tr>
                       

                       <tr bordercolor="#CCCCCC">
                       <td bgcolor="#CCCCCC" align="right">EFECTIVO GRAL.:</td>
                       <td width="70%"bgcolor="#CCCCCC" align="right">'.number_format($Grantotalgral,'2').'</td></tr>


                       <tr bordercolor="#FFF">
                       <td bgcolor="#FFF" align="right">COSTOS GRAL.:</td>
                       <td width="70%" bgcolor="#FFF" align="right">'.number_format($nImporteg,'2').'</td></tr>

                       
                       <tr bordercolor="#CCCCCC">
                       <td bgcolor="#CCCCCC" align="right">TOTAL A ENTREGAR:</td>
                       <td width="70%" bgcolor="#CCCCCC" align="right">'.number_format($Grantotalgral-$nImporteg,'2').'</td></tr></table></td>
                      






                       <td><table width="120%" align="center" border="1" align="center" bordercolor="#FFFFFF">
                       <tr bordercolor="#CCCCCC">
                        <td bgcolor="#CCCCCC" align="center">TIPO PAGO GENERAL</td>
                        <td bgcolor="#CCCCCC" align="center">DEL DIA GENERAL</td>
                        <td bgcolor="#CCCCCC" align="center">RECUPERACION GENERAL</td>
                        <td bgcolor="#CCCCCC" align="center">GRAN TOTAL GENERAL</td></tr>
 





                        <tr bordercolor="#CCCCCC">
                        <td align="center">Efectivo</td>
                        <td align="right"> '.number_format($Totalefectivo,"2").'</td>
                        <td align="right"> '.number_format($TotalefectivoR,"2").'</td>
                        <td align="right"> '.number_format($Graltotalefectivo,"2").'</td></tr>

                    



                        <tr bordercolor="#CCCCCC">
                        <td align="center">Tarjeta</td>
                        <td align="right">'.number_format($Totaltarjeta,"2").'</td>
                        <td align="right">'.number_format($TotaltarjetaR,"2").'</td>
                        <td align="right">'.number_format($Graltotaltarjeta,"2").'</td></tr>
                    


                        <tr bordercolor="#CCCCCC">
                        <td align="center">Cheque</td>
                        <td align="right">'.number_format($Totalcheque,"2").'</td>
                        <td align="right">'.number_format($TotalchequeR,"2").'</td>
                        <td align="right">'.number_format($Graltotalcheque,"2").'</td></tr>
                    

                        <tr bordercolor="#CCCCCC">
                        <td align="center">Transferencias</td>
                        <td align="right">'.number_format($Totaltranferencia,"2").'</td>
                        <td align="right">'.number_format($TotaltranferenciaR,"2").'</td>
                        <td align="right">'.number_format($Totaltranferencia+$TotaltranferenciaR,"2").'</td></tr>
                    

                        <tr bordercolor="CCCCCC">
                        <td>&nbsp;</td>
                        <td  bgcolor="#CCCCCC" align="right">'.number_format($Totalgral1,"2").'</td>
                        <td  bgcolor="#CCCCCC" align="right">'.number_format($Totalgral2,"2").'</td>
                        <td  bgcolor="#CCCCCC" align="right">'.number_format($Totalgral3,"2").'</td></tr></table></td></tr></table>';
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

/*
    echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='15'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>#Recibo</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No.Ord</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recepcionista</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Status O.</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Venta</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Descuento</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Total Vta</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Recup.</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Ingreso</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Ing.Total</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudo</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Tpo.pago</font></th>";
    echo "<tr><td colspan='15'><hr noshade></td></tr>";

*/



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
          $AbnD=mysql_query("SELECT sum(importe) FROM cja WHERE orden='$registro[orden]' AND fecha = '$Fecha' AND id<>'$registro[0]'",$link);
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
      


                     $html='<table border="0">
                    <tr bgcolor='.$Fdo.'>
                    <td align="CENTER" width="75" >'.$registro[id].'</td>
                    <td align="CENTER" width="95" >'.$registro[institucion].' - '.$registro[orden].'</td>
                    <td align="CENTER" width="115" >'.$registro[8].'</td>
                    <td align="CENTER" width="85" >'.$registro[fecha].'</td>
                    <td align="CENTER" width="65" >'.substr($registro[3],0,5).'</td>
                    <td align="CENTER" width="80" >'.$status.'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventa,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordendesc,"2").'</td>
                    <td align="right"  width="85" >'.number_format($Ordenventot,"2").'</td>
                    <td align="right"  width="70" >'.number_format($Recupera,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Ingreso1,"2").'</td>
                    <td align="right"  width="80" >'.number_format($Recupera+$Ingreso1,"2").'</td>
                    <td align="right"  width="75" >'.number_format($Adeudo1,"2").'</td>
                    <td align="center" width="90" >'.$registro[5].'</td></tr></table>';

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
  

                        $html='<table  border="0" >
                        <tr>
                        <td width="75"></td>
                        <td width="95"></td>
                        <td width="115"></td>
                        <td width="85"></td>
                        <td width="65"></td>
                        <td width="80"></td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($Importe,'2').'</td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($Descuento,'2').'</td>
                        <td align="right" width="85" bgcolor="#d5d6dd" >'.number_format($TotalOrd,'2').'</td>
                        <td align="right" width="70" bgcolor="#d5d6dd">'.number_format($Recupera2,'2').'</td>
                        <td align="right" width="80" bgcolor="#d5d6dd">'.number_format($Ingreso,'2').'</td>
                        <td align="right" width="80" bgcolor="#d5d6dd">'.number_format($IngTot,'2').'</td>
                        <td align="right" width="75" bgcolor="#d5d6dd">'.number_format($TotAdeudo,'2').'</td>
                        <td></td width="90"></tr></table>';

                        $pdf->writeHTML($html,true,false,true,false,'');
 


                    $html='<table width="150%" border="0" align="center">
                    <tr>
                    <td bgcolor="#CCCCCC" width="60%">ORDENES ABIERTAS : '.number_format($Ordenes[0],"0").'</td></tr>
                    
                    </table><table width="150%" border="0" align="center">
                    <tr>
                    <td width="10%"></td><td width="70%"><table width="85%" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#FFFFFF">

                    <tr bordercolor="#CCCCCC">
                    <td  width="100%" align="right">VENTAS TOTALES :</td>
                    <td  width="70%" align="right">'.number_format($Totdia,'2').'</td></tr>
                    
                    <tr bordercolor="#CCCCCC">
                    <td align="right">ADEUDOS :</td>
                    <td width="70%" align="right">'.number_format($TotAdeudo,'2').'</td></tr>

                    <tr bordercolor="#CCCCCC">
                    <td align="right">INGRESO DEL DIA :</td>
                    <td width="70%" align="right">'.number_format($Ingreso,'2').'</td></tr>

                    <tr bordercolor="#CCCCCC">
                    <td align="right">RECUPERACIONES :</td>
                    <td width="70%" align="right">'.number_format($Recupera2,'2').'</td></tr>
                   
                    

                    <tr bordercolor="#CCCCCC">
                    <td bgcolor="#CCCCCC" align="right">TOTAL :</td>
                    <td bgcolor="#CCCCCC" width="70%" align="right">'.number_format($IngTot,'2').'</td></tr>
                    

                    
          
                    <tr bordercolor="#CCCCCC">
                    <td align="right">TARJETA :</td>
                    <td width="70%" align="right">'.number_format($Tarjeta+$TarjetaR,'2').'</td></tr>

                   
                    <tr bordercolor="#CCCCCC">
                    <td align="right">TRANSFERENCIA :</td>
                    <td width="70%" align="right">'.number_format($Transferencia+$TransferenciaR,'2').'</td></tr>

                    

                   <tr bordercolor="#CCCCCC">
                   <td align="right">CHEQUE :</td>
                   <td width="70%" align="right">'.number_format($Cheque+$ChequeR,'2').'</td></tr>
                
                    <tr bordercolor="#CCCCCC">
                    <td bgcolor="#CCCCCC" align="right">TOTAL GRAL.:</td>
                    <td bgcolor="#CCCCCC" width="70%" align="right">'.number_format($IngTot-($Tarjeta+$TarjetaR+$Transferencia+$TransferenciaR+$Cheque+$ChequeR),'2').'</td></tr></table></td>                  
                  

                   <td><table width="90%" align="center" border="1" align="center" bordercolor="#FFFFFF">
                   
                   <tr bordercolor="#CCCCCC">
                   <td bgcolor="#CCCCCC" align="center">TIPO PAGO</td>
                   <td bgcolor="#CCCCCC" align="center">DEL DIA</td>
                   <td bgcolor="#CCCCCC" align="center">RECUPERACION</td>
                   <td bgcolor="#CCCCCC" align="center">TOTAL</td></tr>


                    <tr bordercolor="#CCCCCC">
                    <td align="center">Efectivo</td>
                    <td align="right">'.number_format($Efectivo,"2").'</td>
                    <td align="right">'.number_format($EfectivoR,"2").'</td>
                    <td align="right">'.number_format($Efectivo+$EfectivoR,"2").'</td></tr>


                   <tr bordercolor="#CCCCCC">
                   <td align="center">Tarjeta</td>
                   <td align="right">'.number_format($Tarjeta,"2").'</td>
                   <td align="right">'.number_format($TarjetaR,"2").'</td>
                   <td align="right">'.number_format($Tarjeta+$TarjetaR,"2").'</td></tr>



                   <tr bordercolor="#CCCCCC">
                   <td align="center">Cheque</td>
                   <td align="right">'.number_format($Cheque,"2").'</td>
                   <td align="right">'.number_format($ChequeR,"2").'</td>
                   <td align="right">'.number_format($Cheque+$ChequeR,"2").'</td></tr>


                   <tr bordercolor="#CCCCCC">
                   <td align="center">Transferencia</td>
                   <td align="right">'.number_format($Transferencia,"2").'</td>
                   <td align="right">'.number_format($TransferenciaR,"2").'</td>
                   <td align="right">'.number_format($Transferencia+$TransferenciaR,"2").'</td></tr>
             

                   <tr bordercolor="#CCCC+'.$TransferenciaRCC.'">
                   <td>&nbsp;</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia,"2").'</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td>
                   <td  bgcolor="#CCCCCC" align="right">'.number_format($Efectivo+$Tarjeta+$Cheque+$Transferencia+$EfectivoR+$TarjetaR+$ChequeR+$TransferenciaR,"2").'</td></tr></table></td></tr></table>';
                   $pdf->writeHTML($html,true,false,true,false,'');

    
    
 }
 
}//fin if
//inicio del while para adeudos, recuperaciones y descuentos


ob_end_clean();
//Close and output PDF document
$pdf->Output();

mysql_close();
?>
