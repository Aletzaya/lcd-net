<?php

  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  


  
  require("lib/lib.php");

  require ("config.php");	

  $link=conectarse();


  $OrdenDef="";            //Orden de la tabla por default
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta p�gina.

  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];
  $Institucion=$_REQUEST[Institucion];
  $Medico=$_REQUEST[Medico];
  $Status=$_REQUEST[Status];
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
  $Promotor=$_REQUEST[Promotor];


  $doc_title    = "Imprimir Relacion de pagos de comisiones";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');

    if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
        header ("Location: $redir?error_login=5");
        exit;
     }
   
     $Usr=$HTTP_SESSION_VARS['usuario_login'];
     
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
       
     if($Promotor <> '*'){
         if ($Sucursal <> '*') {
             if($Institucion=="*"){
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }
            }else{
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }
            }
         }else{
             if($Institucion=="*"){
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }
            }else{
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' and med.promotorasig='$Promotor' group by otd.orden
                   order by med.zona, ot.medico";
                }
            }
         }
   
     }else{
         if ($Sucursal <> '*') {
             if($Institucion=="*"){
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
                   order by med.zona, ot.medico";
                }
            }else{
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' AND ($Sucursal) group by otd.orden
                   order by med.zona, ot.medico";
                }
            }
         }else{
             if($Institucion=="*"){
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
                   order by med.zona, ot.medico";
                }
            }else{
                if($Medico=="*"){
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
                   order by med.zona, ot.medico";
                }else{
                   $cSql="select ot.medico,med.nombrec,ot.orden,sum(otd.precio*(1-(otd.descuento/100))) as importe,
                   ot.institucion,otd.descuento,count(*),sum(otd.precio*(1-(otd.descuento/100)))*(if(est.comision>=1,est.comision,med.comision)/100) as comisiones,med.zona as Zonas,med.promotorasig,ot.suc from ot,otd,med,zns,est where ot.fecha>='$FecI' and ot.fecha<='$FecF'
                   and ot.orden=otd.orden and ot.medico=med.medico and med.comision > 0 and ot.institucion='$Institucion' and ot.medico='$Medico' and zns.zona=med.zona and otd.estudio=est.estudio and otd.estudio<>'INF-AB' and otd.estudio<>'TOMCOV' group by otd.orden
                   order by med.zona, ot.medico";
                }
            }
         }
     }
     ?>
     <table width="100%" border="0">
       <tr> 
         <td width="18%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
           </div></td>
           <td width='64%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p><br>
           <?php echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]</p>";?><br>
       </tr>
     </table>
     <font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
     <?php
   
   
   
   
   $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
   $NomI    = mysql_fetch_array($InstA);

     // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
        global $Institucion,$Sucursal,$sucursalt,$sucursal1,$sucursal2,$sucursal3,$sucursal4,$sucursal5,$Titulo,$Sucursal2,$Recepcionista,$FecI,$FecF;

    $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
    $NomI    = mysql_fetch_array($InstA);

    $Fecha   = date("Y-m-d");
    $Hora=date("H:i");
    $Titulo="Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF";
    // Logo
    $image_file2 = 'lib/DuranNvoBk.png';
    $this->Image($image_file2, 8, 5, 65, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    $this->SetFont('helvetica', 'B', 11);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800"></td></tr><tr><td width="30"></td><td width="800">Laboratorio Clínico Duran</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">Institucion: '.$Institucion.'</td></tr></table>', false, 0);

    $this->SetFont('helvetica', '', 9);

    $this->writeHTML('<table border="0" width="900"><tr><td width="30"></td><td width="800">'.$Titulo.' Sucursal:'.$Sucursal2.'</td></tr></table><br>', false, 0);



    $this->SetFont('Helvetica', 'BI', 8);

    $this->writeHTML('<br><br><table align="center" width="1150" border="1" cellspacing="1" cellpadding="0">
    <tr>
    <td align="center" bgcolor="#808B96"  width="90">Zona</td>
    <td align="center" bgcolor="#808B96"  width="90" >Ins</td>
    <td align="center" bgcolor="#808B96"  width="90">Suc</td>
    <td align="center" bgcolor="#808B96"  width="120">Promotor</td>
    <td align="center" bgcolor="#808B96"  width="100"> Med</td>
    <td align="center" bgcolor="#808B96"  width="300"> Nombre</td>
    <td align="center" bgcolor="#808B96"  width="90">No.Ords</td>
    <td align="center" bgcolor="#808B96"  width="90">No.Ests</td>
    <td align="center" bgcolor="#808B96"  width="90">Importe</td>
    <td align="center" bgcolor="#808B96"  width="90">Comision</td>
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


if(!$res=mysql_query($cSql,$link)){
/* echo "<div align='center'>";
   echo "<font face='verdana' size='2'>No se encontraron resultados � hay un error en el filtro</font>";
   echo "<p align='center'><font face='verdana' size='-2'><a href='comisiones.php?op=br'>";
   echo "Recarga y/� limpia.</a></font>";
   echo "</div>";
   */
}else{

   $registro=mysql_fetch_array($res);
   $Inst=$registro[institucion];
   $Promotor=$registro[promotorasig];
   $Sucursal=$registro[suc];
   $Medico=$registro[medico];
   $Nombre=$registro[nombrec];
   $Importe=$registro[importe];
   $Comision+=$registro[comisiones];
   $Estudios+=$registro[6];
   $Ordenes=1;
   $Zona=$registro[Zonas];
   $Zona1=$registro[Zonas];
   $SMedico=0;
   
   while($registro=mysql_fetch_array($res)) {
           $Zona1=$Zona;
           
           if( ($nRngg % 2) > 0 ){
            $Fdo='#FFFFFF';
          }
            else{
                $Fdo='#CDCDFA';
          }   

       
        if( $registro[medico] == $Medico ){
            $Importe+=$registro[importe];
            /*if($registro[descuento]==0){*/$Comision+=$registro[comisiones];/*}*/
            $Estudios+=$registro[6];
            $Ordenes+=1;
        }else{
            if($Medico == 'AQ' ){
               $AQComision+=$Comision;
               $AQEstudios+=$Estudios;
               $AQImporte+=$Importe;
               $AQOrdenes+=$Ordenes;
            }elseif($Medico == 'MD' ){
               $MDComision+=$Comision;
               $MDEstudios+=$Estudios;
               $MDImporte+=$Importe;
               $MDOrdenes+=$Ordenes;
            }else{
               $DIVComision+=$Comision;
               $DIVEstudios+=$Estudios;
               $DIVImporte+=$Importe;
               $DIVOrdenes+=$Ordenes;
            }

 
$pdf->SetFont('Helvetica', '', 7, '', 'false');

            $html='<table border="0" width="1150">
            <tr bgcolor='.$Fdo.'>
           <td width="90" align="center">'.$Zona.'</td>
           <td width="90" align="center">'.$Inst.'</td>
           <td width="90" align="center">'.$Sucursal.'</td>
           <td width="120" >'.$Promotor.'</td>
           <td width="100">'.$Medico.'</td>
           <td width="300">'.$Nombre.'</td>
           <td width="90" align="center">'.$Ordenes.'</td>
           <td width="90" align="center">'.number_format($Estudios).'</td>
           <td width="90" align="right">'.number_format($Importe,"2").'</td>
           <td width="90" align="right">'.number_format($Comision,"2").'</td></tr></table>';
           $nRngg ++;
           
           $pdf->writeHTML($html,true,false,true,false,'');

        



           $TComision+=$Comision;
           $TEstudios+=$Estudios;
           $TImporte+=$Importe;
           $TOrdenes+=$Ordenes;

           $SComision+=$Comision;
           $SMedico=$SMedico+1;
           $SEstudios+=$Estudios;
           $SImporte+=$Importe;
           $SOrdenes+=$Ordenes;

           $Inst=$registro[institucion];
           $Sucursal=$registro[suc];
           $Promotor=$registro[promotorasig];
           $Medico=$registro[medico];
           $Nombre=$registro[nombrec];
           $Importe=$registro[importe];
           $Comision=0;
           /*if($registro[descuento]==0){*/$Comision=$registro[comisiones];/*}*/
           $Estudios=$registro[6];
           $Ordenes=1;
           $Zona=$registro[Zonas];

        
        }



        if ( $registro[Zonas] <> $Zona1 ){


            $html='<table border="0">
            <tr>
            <td width="90">&nbsp;</td>
            <td width="90">&nbsp;</td>
            <td width="90">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td width="100" bgcolor="#808B96">No. Med.:&nbsp;'.$SMedico.'</td>
            <td width="300" bgcolor="#808B96" align="right">&nbsp;S u b t o t a l :</td>
            <td width="90" bgcolor="#808B96" align="center">'.$SOrdenes.'</td>
            <td width="90" bgcolor="#808B96" align="center">'.number_format($SEstudios).'</td>
            <td width="90" bgcolor="#808B96" align="right"> $&nbsp;'.number_format($SImporte,"2").'</td>
            <td width="90" bgcolor="#808B96" align="right"> $&nbsp;'.number_format($SComision,"2").'</td></tr></table>';
            
            $pdf->writeHTML($html,true,false,true,false,'');


           $TMedico+=$SMedico;				
           $SComision=0;
            $SMedico=0;
           $SEstudios=0;
           $SImporte=0;
           $SOrdenes=0;

            
        }

    }

    $TComision+=$Comision;
    $TEstudios+=$Estudios;
    $TImporte+=$Importe;
    $TOrdenes+=$Ordenes;

    $SComision+=$Comision;
    $SMedico=$SMedico+1;
    $SEstudios+=$Estudios;
    $SImporte+=$Importe;
    $SOrdenes+=$Ordenes;
      $TMedico+=$SMedico;		
    
    if($Medico == 'AQ' ){
       $AQComision+=$Comision;
       $AQEstudios+=$Estudios;
       $AQImporte+=$Importe;
       $AQOrdenes+=$Ordenes;
    }elseif($Medico == 'MD' ){
       $MDComision+=$Comision;
       $MDEstudios+=$Estudios;
       $MDImporte+=$Importe;
       $MDOrdenes+=$Ordenes;
    }else{
       $DIVComision+=$Comision;
       $DIVEstudios+=$Estudios;
       $DIVImporte+=$Importe;
       $DIVOrdenes+=$Ordenes;
    }

            $html='<table border="0" width="1150">
            <tr bgcolor='.$Fdo.'>
           <td width="90" align="center">'.$Zona.'</td>
           <td width="90" align="center">'.$Inst.'</td>
           <td width="90" align="center">'.$Sucursal.'</td>
           <td width="120" >'.$Promotor.'</td>
           <td width="100">'.$Medico.'</td>
           <td width="300">'.$Nombre.'</td>
           <td width="90" align="center">'.$Ordenes.'</td>
           <td width="90" align="center">'.number_format($Estudios).'</td>
           <td width="90" align="right">'.number_format($Importe,"2").'</td>
           <td width="90" align="right">'.number_format($Comision,"2").'</td></tr></table>';
           $nRngg ++;
           
           $pdf->writeHTML($html,true,false,true,false,'');



            $html='<table border="0">
            <tr>
            <td width="90">&nbsp;</td>
            <td width="90">&nbsp;</td>
            <td width="90">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td width="100" bgcolor="#808B96">No. Med.:&nbsp;'.$SMedico.'</td>
            <td width="300" bgcolor="#808B96" align="right">&nbsp;S u b t o t a l :</td>
            <td width="90" bgcolor="#808B96" align="center">'.$SOrdenes.'</td>
            <td width="90" bgcolor="#808B96" align="center">'.number_format($SEstudios).'</td>
            <td width="90" bgcolor="#808B96" align="right"> $&nbsp;'.number_format($SImporte,"2").'</td>
            <td width="90" bgcolor="#808B96" align="right"> $&nbsp;'.number_format($SComision,"2").'</td></tr></table>';
            
            $pdf->writeHTML($html,true,false,true,false,'');



            $html='<br><br><table border="0">  

            <tr>

            <td width="90">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td bgcolor="#CCCCCC" width="100">AQ</td>
            <td bgcolor="#CCCCCC" width="300" align="center">A QUIEN CORRESPONDA</td>
            <td bgcolor="#CCCCCC" width="90"  align="right">'.$AQOrdenes.'</td>
            <td bgcolor="#CCCCCC" width="90"  align="right">'.number_format($AQEstudios).'</td>
            <td bgcolor="#CCCCCC" width="90"  align="right"> $&nbsp;'.number_format($AQImporte,"2").'</td>
            <td bgcolor="#CCCCCC" width="90"  align="right"> $&nbsp;'.number_format($AQComision,"2").'</td></tr></table>';
            $pdf->writeHTML($html,true,false,true,false,'');



            $html='<table border="0">  
            <tr>

            <td width="90">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td  bgcolor="#CCCCCC" width="100">MD</td>
            <td  bgcolor="#CCCCCC" width="300" align="center">MEDICO DIVERSO</td>
            <td  bgcolor="#CCCCCC" width="90" align="right">'.$MDOrdenes.'</td>
            <td  bgcolor="#CCCCCC" width="90" align="right">'.number_format($MDEstudios).'</td>
            <td  bgcolor="#CCCCCC" width="90" align="right"> $&nbsp;'.number_format($MDImporte,"2").'</td>
            <td  bgcolor="#CCCCCC" width="90" align="right"> $&nbsp;'.number_format($MDComision,"2").'</td></tr></table>';
            $pdf->writeHTML($html,true,false,true,false,'');



            $html='<table border="0">  
            <tr>

            <td width="90">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td bgcolor="#CCCCCC" width="100">MR</td>
            <td bgcolor="#CCCCCC" width="300"align="center">MEDICO REFERIDO</td>
            <td bgcolor="#CCCCCC" width="90"align="right">'.$DIVOrdenes.'</td>
            <td bgcolor="#CCCCCC" width="90"align="right">'.number_format($DIVEstudios).'</td>
            <td bgcolor="#CCCCCC" width="90"align="right">$&nbsp;'.number_format($DIVImporte,"2").'</td>
            <td bgcolor="#CCCCCC" width="90"align="right">$&nbsp;'.number_format($DIVComision,"2").'</td></tr></table>';
            $pdf->writeHTML($html,true,false,true,false,'');


            $html='<table border="0">  
            <tr>

            <td width="90">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td bgcolor="#CCCCCC" width="100">Tot. Med.:&nbsp;'.$TMedico.'</td>
            <td bgcolor="#CCCCCC" width="300" align="right">&nbsp;T o t a l :</td>
            <td bgcolor="#CCCCCC" width="90" align="right">'.$TOrdenes.'</td>
            <td bgcolor="#CCCCCC" width="90" align="right">'.number_format($TEstudios).'</td>
            <td bgcolor="#CCCCCC" width="90" align="right">$&nbsp;'.number_format($TImporte,"2").'</td>
            <td bgcolor="#CCCCCC" width="90" align="right">$&nbsp;'.number_format($TComision,"2").'</td></tr></table>';
            $pdf->writeHTML($html,true,false,true,false,'');


}
//fin while







ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>
<?php
mysql_close();
?>
