<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $Usr = $_COOKIE[USERNAME];

  $FecI=$_REQUEST[FecI];

  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $HoraI=$_REQUEST[HoraI];

  $HoraF=$_REQUEST[HoraF];

  $Titulo=$_REQUEST[Titulo];
  
  $Fecha=date("Y-m-d");

  $Fecha2=substr($Fecha, 0, 10);

  $fch3=explode("-",$Fecha2);
  $tfecha3=$fch3[2]."-".$fch3[1]."-".$fch3[0];

  $Hora=date("H:i");
  
  $SucursalD   = $_REQUEST[SucursalDe];

  if($SucursalD=="*"){
    $SucursalDe="";
  }else{
    $SucursalDe="and ot.suc=$SucursalD";
  }
  
  $SucursalP = $_REQUEST[Proveedor];

  if($SucursalP=="*"){
    $SucursalPara="";
  }else{
    $SucursalPara="and maqdet.mext='$SucursalP'";
  }

  $personal = $_REQUEST[personal];

  if($personal=="*"){
    $personale="";
  }else{
    $personale="and maqdet.usrenvext='$personal'";
  }

$Titulo = "Hoja de envio de Estudios - Orthin";

$cSql="SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,ot.medico,ot.medicon,cli.cliente,cli.apellidop,cli.apellidom,cli.nombre,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,est.clavealt,maqdet.usrenvext FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.fenvext>='$FecI' and maqdet.fenvext <='$FecF' AND maqdet.henvext >='$HoraI' AND maqdet.henvext <='$HoraF' $SucursalDe $SucursalPara $personale order by maqdet.orden";

$UpA=mysql_query($cSql,$link);


$cSql2="SELECT * FROM authuser WHERE uname='$Usr'";

$UpA2=mysql_query($cSql2,$link);

$rg2=mysql_fetch_array($UpA2);

if($rg2[team]=='4'){
  $Sucur='20364';
}else{
  $Sucur='21134';
}

require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<script language="JavaScript1.2">
function Ventana(url){
   window.open(url,"venord","width=750,height=500,left=40,top=50,scrollbars=yes,location=no,dependent=yes,resizable=yes")
}

</script>

<title><?php echo $Titulo;?></title>

<?php 
    header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename=nombre_archivo.xls'); 
?>


</head>


<body bgcolor="#FFFFFF">


    <?php

//********************  E N C A B E Z A D O  ****************

        echo "<table border='0'><tr><td width='200'><img src='lib/logoorthin.jpg' width='55'></td><td width='600'></td></tr><tr><td width='200'>&nbsp;</td><td width='600'></td></tr><tr><td width='200'>&nbsp;</td><td width='650' align='center' colspan='9'><font size='5'><b>Solicitud de Estudios por Base</b></font></td></tr><tr><td width='200'>&nbsp;</td><td width='600'></td></tr></table><hr>";

        echo "<br><table width='100' border='0'><tr><td width='400'></td><td width='300'><b>Nombre y Clave de laboratorio: </td><td width='400'>Laboratorio Clinico Duran S.A. de C.V. </b></td><td width='100'><b>Fecha: </b></td><td width='300'> ".$tfecha3."</td></tr></table>";

        echo "<br><table border='1' width='98%' bgcolor='#d2b4de'><tr align='center'><th width='80' rowspan='2'><font size='2'><b><br />APELLIDO <br />PATERNO</b></font></th><th width='80' rowspan='2'><font size='2'><b><br />APELLIDO<br />MATERNO</b></th><th width='140' rowspan='2'><font size='2'><b><br /><br />NOMBRE (S)</b></th><th width='100'><font size='2'><b><br />FECHA DE NACIMIMENTO</b></th><th width='50'><font size='2'><b><br />SEXO <br /></b></th><th width='100' rowspan='2'><font size='2'><b><br /><br />ID CONSECUTIVO</b></th><th width='100'><font size='2'><b><br />FECHA DE <br /> SOLICITUD</b></th><th width='120' rowspan='2'><font size='2'><b><br />CENTRO DE <br /> PROCESAMIENTO</b></th><th width='15' rowspan='2'><font size='2'><b><br /><br />Uso Lab 1</b></th><th width='60' rowspan='2'><font size='2'><b><br />CLAVE DE <br /> CLIENTE</b></th><th width='15' rowspan='2'><font size='2'><b><br /><br />Uso Lab 2</b></th><th width='15' rowspan='2'><font size='2'><b><br /><br />Uso Lab 3</b></th><th width='15' rowspan='2'><font size='2'><b>Uso Lab 4</b></th><th width='15' rowspan='2'><font size='2'><b><br /><br />Uso Lab 5</b></th><th width='200' rowspan='2'><font size='2'><b><br />MEDICO</b></th><th width='60' rowspan='2'><font size='2'><b><br />ID PACIENTE</b></th><th width='70'><font size='2'><b><br />FECHA DE TOMA <br /> DE MUESTRA</b></th><th width='70'><font size='2'><b><br />HORA DE TOMA <br /> DE MUESTRA</b></th><th width='150' rowspan='2'><font size='2'><b><br />OBSERVACIONES</b></th><th width='70' rowspan='2'><font size='2'><b><br />CLAVE (S) DE <br /> ESTUDIO (S)</b></th></tr>";  

       echo "<tr align='center'><th width='100'><font size='2'><b><br />(dd/mm/aaaa) </b></th><th width='50'><font size='2'><b><br />(M/F)</b></th><th width='100'><font size='2'><b><br />(dd/mm/aaaa)</b></th><th width='70'><font size='2'><b><br />(dd/mm/aaaa)</b></th><th width='70'><font size='2'><b><br />(hh:mm)</b></th></tr></table>";  


//***********   D A T O S   ***********

$Estudios=1;

echo "<table border='1' width='98%'>";

while($rg=mysql_fetch_array($UpA)) {

  if( ($Estudios % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#e3f2ca';}

//    $nommed=$rg2[nombrec];

  if($rg[medico]=='MD'){

    $nommed=$rg[medicon];

  }else{

      $cSql2="SELECT * FROM med WHERE med.medico='$rg[medico]'";

      $UpA2=mysql_query($cSql2,$link);

      $rg2=mysql_fetch_array($UpA2);  

      $nommed=$rg2[nombrec];

  }


  $cSql3="SELECT * FROM est WHERE est.estudio='$rg[estudio]'";

  $UpA3=mysql_query($cSql3,$link);

  $rg3=mysql_fetch_array($UpA3);  

  $cSql4="SELECT fechaest FROM otd WHERE otd.estudio='$rg[estudio]' and otd.orden='$rg[ord]'";

  $UpA4=mysql_query($cSql4,$link);

  $rg4=mysql_fetch_array($UpA4);  

  $ftoma=substr($rg4[fechaest], 0, 10);

  $fch2=explode("-",$ftoma);
  $tfecha2=$fch2[2]."-".$fch2[1]."-".$fch2[0];

  $htoma=substr($rg4[fechaest], 11, 5);

  $fch=explode("-",$rg[fechan]);
  $tfecha=$fch[2]."-".$fch[1]."-".$fch[0];

  echo "<tr align='center'><th width='80'><font size='2'><br />".utf8_encode(strtoupper($rg[apellidop]))."</th><th width='80'><font size='2'><br />".utf8_encode(strtoupper($rg[apellidom]))."</th><th width='140'><font size='2'><br />".utf8_encode(strtoupper($rg[nombre]))."</th><th width='100'><font size='2'><br />".$tfecha."</th><th width='50'><font size='2'><br />".$rg[sexo]."</th><th width='100'  bgcolor='#d2b4de'><br /></th><th width='100' bgcolor='#d2b4de'><br /></th><th width='120'  bgcolor='#d2b4de'><br /></th><th width='15' bgcolor='#d2b4de'><br /></th><th width='60'><font size='2'><br />".$Sucur."</th><th width='15' bgcolor='#d2b4de'><br /></th><th width='15' bgcolor='#d2b4de'><br /></th><th width='15' bgcolor='#d2b4de'><br /></th><th width='15' bgcolor='#d2b4de'><br /></th><th width='200'><font size='2'><br />".$nommed."</th><th width='60'><b><br /></b></th><th width='70'><font size='2'><br />".$tfecha2."</th><th width='70'><font size='2'><br />".$htoma."</th><th width='150'><font size='2'><br />".$rg[descripcion]."</th><th width='70'><font size='2'><br />".$rg[clavealt]."</th></tr>"; 

  $Estudios++;

}

echo "</table>";

mysql_close();
?>

</body>

</html>