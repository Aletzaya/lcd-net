<?php

session_start();

require("lib/lib.php");
$link=conectarse();


$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

  $FechaI	=	$_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
  }

  $FechaF	=	$_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
	  echo '<script language="javascript">alert("Fechas 1 incorrectas... Verifique");</script>'; 
  }

  $FechaI2	=	$_REQUEST[FechaI2];

  if (!isset($FechaI2)){
      $FechaI2 = date("Y-m-d",strtotime($FechaI2."- 1 year"));
  }

  $FechaF2  = $_REQUEST[FechaF2];

  if (!isset($FechaF2)){
      $FechaF2 = date("Y-m-d",strtotime($FechaF2."- 1 year"));
  }

  if ($FechaI2>$FechaF2){
	  echo '<script language="javascript">alert("Fechas 2 incorrectas... Verifique");</script>'; 
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
 }else{
	$filtro4=" ";
 }

 if($filtro5<>'*'){
 	$filtro6="and ot.institucion='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and est.depto='$filtro7'";
 }else{
	$filtro8=" ";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and inst.condiciones='$filtro9'";
 }else{
	$filtro10=" ";
 }

  $Status2 = $_REQUEST[Status2];


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

  $Titulo = "Demanda Comparativa por Estudio";



  $cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(DISTINCT ot.orden) as cant2, est.depto,est.subdepto
  FROM ot
  INNER JOIN otd on ot.orden=otd.orden
  INNER JOIN est on otd.estudio = est.estudio
  WHERE ot.fecha Between '$FechaI' And '$FechaF' $filtro4 $filtro6 $filtro8
  GROUP BY est.subdepto  Order BY est.subdepto";

  $OtA  = mysql_query($cSql,$link);


  $cSql2="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(DISTINCT ot.orden) as cant2, est.depto,est.subdepto
  FROM ot
  INNER JOIN otd on ot.orden=otd.orden
  INNER JOIN est on otd.estudio = est.estudio
  WHERE ot.fecha Between '$FechaI2' And '$FechaF2' $filtro4 $filtro6 $filtro8
  GROUP BY est.subdepto  Order BY est.subdepto";
  
  $OtA2 = mysql_query($cSql2,$link);

	$est2="SELECT est.depto,est.subdepto FROM est where est.activo='Si' ORDER BY est.depto,est.subdepto ASC";

  	$est3  = mysql_query($est2,$link);

  require ("config.php");



  ?>

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  
	<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title>Comparativa por Estudio Resumen</title>
		<link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
  
		<link href="estilos.css" rel="stylesheet" type="text/css"/>
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
		<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>
		<link rel='icon' href='favicon.ico' type='image/x-icon' />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    <script language="JavaScript1.2">
function Ventana(url){
   window.open(url,"venord","width=750,height=500,left=40,top=50,scrollbars=yes,location=no,dependent=yes,resizable=yes")
}

</script>

<title>
<?php




?></title>

<?php   if($Status2=='Exportar'){
    header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename=nombre_archivo.xls'); 
  }
?>
</head>

<body bgcolor="#FFFFFF">

	<?php
	  echo '<body topmargin="1">';
	  encabezados();
	  menu($Gmenu, $Gusr);
	  ?>
	  <script src="./controladores.js"></script>
    <h2 align='center' style="color: #5c6773"><?= $Titulo ?></h2>

  <?php

    echo "<table align='center' width='100%' border='0'>";

	echo "<form name='form' method='post' action='comparativor.php'>";

	echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
		
	echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";


	echo "$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI2' size='10' value ='$FechaI2'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI2,'yyyy-mm-dd',this)>";
		
	echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF2' size='10' value ='$FechaF2'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF2,'yyyy-mm-dd',this)>";
	

	echo "$Gfont<b><font size='1'>Suc </b></font>";
	echo "<select size='1' name='filtro3' class='Estilo10'>";

	$SucA=mysql_query("SELECT id,alias FROM cia order by id");
  echo "<option value='*'>Todos*</option>";
	while($Suc=mysql_fetch_array($SucA)){
		echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
		if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
    if($filtro3=='*'){$DesSuc=' Todos';}
	}

	
  echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";		  
	echo "</select>";
	echo"</b>";

  echo "$Gfont<b><font size='1'>Inst </b></font>";

  echo "<select size='1' name='filtro5' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
  $InsA = mysql_query("SELECT institucion,alias FROM inst order by institucion");
  while($Ins=mysql_fetch_array($InsA)){
        echo "<option value=$Ins[institucion]>$Ins[institucion]&nbsp;$Ins[alias]</option>";
        if($Ins[institucion]==$filtro5){$DesInst=$Ins[alias];}
        if($filtro5=='*'){$DesInst=' Todos';}
  }   
  echo "<option selected value='$filtro5'>$Gfont <font size='-1'>$filtro5 $DesInst</option>";  
  echo "</select>";
  echo"</b>";
  
  echo "$Gfont<b><font size='1'>Depto</b></font>";

  echo "<select size='1' name='filtro7' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
   $Depto=mysql_query("select departamento,nombre from dep",$link);
  while ($Depto1=mysql_fetch_array($Depto)){
        echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        if($Depto1[departamento]==$filtro7){$Desdepto=$Depto1[nombre];}
        if($filtro7=='*'){$Desdepto=' Todos';}
  }   
  echo "<option selected value='$filtro7'>$Gfont <font size='-1'>$filtro7 $Desdepto</option>";  
  echo "</select>";
  echo"</b>";


//  echo "&nbsp; <input type='hidden' name='filtro3' value='$filtro3'>";

  echo "&nbsp; <input type='SUBMIT' value='Ok'>";

  echo"</b></td>";
  echo "</form>";

  echo"<tr><td colspan='9'>&nbsp</td></tr>";

    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

    $Tit = "<tr bgcolor='#a2b2de'>
    <td align='center' colspan='2'>$Gfon </td>
    <td align='center' colspan='2'>$Gfon <b>De $FechaI al $FechaF</b></td>
    <td align='center' colspan='2'>$Gfon <b>De $FechaI2 al $FechaF2</b></td>
    <td align='center' colspan='3'>$Gfon <b>Diferencia</b></td>
    </tr>
    <tr bgcolor='#a2b2de'>
    <td align='center' colspan='2'>$Gfon <b>Subdepartamento</b></td>
	  <td align='center'>$Gfon <b>Cant</b></td>
    <td align='center'>$Gfon <b>Importe</b></td>
    <td align='center'>$Gfon <b>Cant</b></td>
    <td align='center'>$Gfon <b>Importe</b></td>
	  <td align='center'>$Gfon <b>Cantidad</b></td>
    <td align='center'>$Gfon <b>Importe</b></td>
    <td align='center'>$Gfon <b> % Dif Ing</b></td>

    </tr>";

    echo $Tit;

	$canti1=0;

	$importotal1=0;

	$estudio1='';


	$canti2=0;

	$importotal2=0;

	$estudio2='';

  $subdepto2='';

  $depto2='';

    //echo "<table width='100%' align='center' border='0'>";

  	while ($reg1 = mysql_fetch_array($est3)) {

      if($reg1[depto]<>$depto2){

        if($depto2==1){

          $Deptos=mysql_query("select departamento,nombre from dep where departamento=$reg1[depto]",$link);

          $Deptos1 = mysql_fetch_array($Deptos);

          $difimportdepto=$cantitdepto1-$cantitdepto2;
          $PorDift=-(100-($cantitdepto1/$cantitdepto2)*100);

         echo "<tr bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';><td align='right' colspan='2'>$Gfont <b>Totales----></b></td>
         <td align='center'>$Gfont <b>".number_format($cantitdepto1,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto1t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($cantitdepto2,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto2t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($difimportdepto,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto1t-$importotaldepto2t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($PorDift,'2')." % </b></font></td></tr>";

         $difimportdepto=$cantitdepto1=$cantitdepto2=$importotaldepto1t=$importotaldepto2t=0;

          echo "<tr><td align='center' colspan='8'> &nbsp; </td></tr>";

          echo "<tr bgcolor='#a2b2de' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#a2b2de';>
          <td align='center' colspan='9'>$Gfont <b>$Deptos1[nombre]</b></td></tr>";


        }elseif($depto2==0){

          $Deptos=mysql_query("select departamento,nombre from dep where departamento=$reg1[depto]",$link);

          $Deptos1 = mysql_fetch_array($Deptos);

         echo "<tr bgcolor='#a2b2de' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#a2b2de';>
         <td align='center' colspan='9'>$Gfont <b>$Deptos1[nombre]</b></td></tr>";

        }else{

          $Deptos=mysql_query("select departamento,nombre from dep where departamento=$reg1[depto]",$link);

          $Deptos1 = mysql_fetch_array($Deptos);

          $difimportdepto=$cantitdepto1-$cantitdepto2;

          $PorDift=-(100-($cantitdepto1/$cantitdepto2)*100);

         echo "<tr bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>
         <td align='right' colspan='2'>$Gfont <b>Totales----></b></td>
         <td align='center'>$Gfont <b>".number_format($cantitdepto1,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto1t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($cantitdepto2,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto2t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($difimportdepto,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto1t-$importotaldepto2t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($PorDift,'2')." % </b></font></td></tr>";

         $difimportdepto=$cantitdepto1=$cantitdepto2=$importotaldepto1t=$importotaldepto2t=0;
          echo "<tr><td align='center' colspan='9'> &nbsp; </td></tr>";

          echo "<tr bgcolor='#a2b2de' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#a2b2de';>
          <td align='center' colspan='9'>$Gfont <b>$Deptos1[nombre]</b></td></tr>";

        }

      }else{

      if($reg1[subdepto]<>$subdepto2){

  		while ($reg2 = mysql_fetch_array($OtA)) {

  			$estudio = $reg2[subdepto];

  			if($reg1[subdepto]==$estudio){

  				$estudio1=$reg2[subdepto];

  				$canti1=$reg2[cant];

  				$cantit1=$cantit1+$reg2[cant];

          $canti3=$reg2[cant2];

          $cantit3=$cantit3+$reg2[cant2];

  				$importotal1=$reg2[precio]-$reg2[descuento];

  				$importotalt1=$importotalt1+$importotal1;

          //Depto
  
          $cantidepto1=$reg2[cant];

          $cantitdepto1+=$reg2[cant];

          $importotaldepto1=$reg2[precio]-$reg2[descuento];


          $importotaldepto1t=$importotaldepto1t+$importotaldepto1;

  			}
  		}

  		while ($reg3 = mysql_fetch_array($OtA2)) {

  			$estudios = $reg3[subdepto];

  			if($reg1[subdepto]==$estudios){

  				$estudio2=$reg3[subdepto];

  				$canti2=$reg3[cant];

  				$cantit2=$cantit2+$reg3[cant];

          $canti4=$reg3[cant2];

          $cantit4=$cantit4+$reg3[cant2];

  				$importotal2=$reg3[precio]-$reg3[descuento];

  				$importotalt2=$importotalt2+$importotal2;


          //Depto
    
          $cantidepto2=$reg3[cant];

          $cantitdepto2+=$reg3[cant];

          $importotaldepto2=$reg3[precio]-$reg3[descuento];

          $importotaldepto2t=$importotaldepto2t+$importotaldepto2;

  			}
  		}


  		if($canti1<>0 or $canti2<>0){

  			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

  			$difcant=$canti1-$canti2;
        $difcant2=$canti3-$canti4;
  			$difimport=$importotal1-$importotal2;


        if($importotal1==0){
          $PorDif=-$importotal2*.1;
          $PorDif3="";
          $PorDif2="Dif -";
        }else{
          if($importotal2==0){
            $PorDif=($importotal1*.1);
            $PorDif3="";
            $PorDif2="Dif +";
          }else{
            $PorDif=-(100-(($importotal1/$importotal2)*100));
            $PorDif3=-(100-(($importotal1/$importotal2)*100));
            $PorDif2="";
          }
        }

        if($PorDif>=0){
          $colordif='green';
        }else{
          $colordif='red';
        }

  			echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
  			<td colspan='2'>$Gfont $reg1[subdepto]</font></td>
        <td align='center'>$Gfont ".number_format($canti1,'0')."<font size='1'></font></td>
  			<td align='right'>$Gfont ".number_format($importotal1,'2')."</font></td>
        <td align='center'>$Gfont ".number_format($canti2,'0')."</font></td>
        <td align='right'>$Gfont ".number_format($importotal2,'2')."</font></td>
        <td align='center'>$Gfont ".number_format($difcant,'0')."</font></td>
        <td align='right'>$Gfont ".number_format($difimport,'2')."</font></td>
        <td align='center'>$Gfont <font color=$colordif>$PorDif2 ".number_format($PorDif3,'2')." % </font></td></tr>";

  			$nRng++;

		  }

  		$estudio1='';
      $canti1=0;
      $canti3=0;
        $importotal1=0;

  		$estudio2='';
  		$canti2=0;
  		$importotal2=0;

  		mysql_data_seek($OtA, 0);
  		mysql_data_seek($OtA2, 0);

      $subdepto2=$reg1[subdepto];
  	}

  }
        $depto2=$reg1[depto];
}

          $difimportdepto=$cantitdepto1-$cantitdepto2;
          $PorDift=-(100-($cantitdepto1/$cantitdepto2)*100);

         echo "<tr bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>
         <td align='right' colspan='2'>$Gfont <b>Totales----></b></td>
         <td align='center'>$Gfont <b>".number_format($cantitdepto1,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto1t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($cantitdepto2,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto2t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($difimportdepto,'0')."</b></td>
         <td align='right'>$Gfont <b>".number_format($importotaldepto1t-$importotaldepto2t,'2')."</b></td>
         <td align='center'>$Gfont <b>".number_format($PorDift,'2')." % </b></font></td></tr>";


        echo "<tr><td align='center' colspan='9'> &nbsp; </td></tr>";

  	$cantit3=$cantit1-$cantit2;
  	$importotalt3=$importotalt1-$importotalt2;
    $PorDift=-(100-($importotalt1/$importotalt2)*100);

    $Tot = "<tr bgcolor='#a2b2de'><td align='right' colspan='2'>$Gfon <b>Totales Generales --> </b></td>
    <td align='center'>$Gfon <b>".number_format($cantit1,'0')."</b></td>
    <td align='right'>$Gfon <b>$ ".number_format($importotalt1,'2')."</b></td>
    <td align='center'>$Gfon <b>".number_format($cantit2,'0')."</b></td>
    <td align='right'>$Gfon <b>$ ".number_format($importotalt2,'2')."</b></td>
    <td align='center'>$Gfon <b>".number_format($cantit3,'0')."</b></td>
    <td align='right'>$Gfon <b>$ ".number_format($importotalt3,'2')."</b></td>
    <td align='center'>$Gfont <b>".number_format($PorDift,'2')." % </b></font></td></tr>";

    echo $Tot;

    echo "</table>";


    $filtr2="FechaI=$FechaI&FechaF=$FechaF&FechaI2=$FechaI2&FechaF2=$FechaF2&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9";

    mysql_close();


    echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";


echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('comparativorpdf.php?Status2=Exportar&$filtr2')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";
echo " &nbsp;  &nbsp;  &nbsp; <a class='edit' href='comparativor.php?Status2=Exportar&$filtr2'><img src='excel.png' height='30px' width='40px' /></a>";  

?>

</body>

</html>