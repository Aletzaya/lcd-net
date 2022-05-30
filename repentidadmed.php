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

  date_default_timezone_set("America/Mexico_City");

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

  $FechaI = $_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
  }

  $FechaF = $_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }
  
  if ($FechaI>$FechaF){
    echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }

  $Institucion   = $_REQUEST[Institucion];

  if (!isset($_REQUEST[Institucion])){
    $Institucion = '*';
  }else{
    $Institucion    = $_REQUEST[Institucion];       
  }

  if (!isset($_REQUEST[filtro3])){
    $filtro3 = '*';
  }else{
    $filtro3    = $_REQUEST[filtro3];       
  }

 if($filtro3=='*'){
   $Sucursal=' ';
 }elseif($filtro3==0){
   $Sucursal='and ot.suc=0';
 }elseif($filtro3==1){
   $Sucursal='and ot.suc=1';
 }elseif($filtro3==2){
   $Sucursal='and ot.suc=2';
 }elseif($filtro3==3){
   $Sucursal='and ot.suc=3';
 }elseif($filtro3==4){
   $Sucursal='and ot.suc=4';
 }elseif($filtro3==5){
   $Sucursal='and ot.suc=5';
 }elseif($filtro3==6){
   $Sucursal='and ot.suc=6';
 }

if($_REQUEST[Boton] == Resumen){

  if($Institucion == "*" OR $Institucion==''){
   
     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico $Sucursal
     GROUP BY med.estado,med.munconsultorio");
                    
     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF";
     $Resumen='Resumen';
          
  }else{

     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico AND ot.institucion='$Institucion' $Sucursal
     GROUP BY med.estado,med.munconsultorio");
   
     $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Ins'");
     $cIns  = mysql_fetch_array($cInsA);

     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF Institucion: $cIns[alias]";
     $Resumen='Resumen';

  }

}else{

  if($Institucion == "*" OR $Institucion==''){
   
     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, med.locconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico $Sucursal
     GROUP BY med.estado,med.munconsultorio, med.locconsultorio");
                    
     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF";
          
  }else{

     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, med.locconsultorio, count(*) as cantidad, sum(importe) as importe
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico AND ot.institucion='$Institucion' $Sucursal
     GROUP BY med.estado,med.munconsultorio, med.locconsultorio");
   
     $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Ins'");
     $cIns  = mysql_fetch_array($cInsA);

     $Titulo = "Medicos por entidad federativa del $FechaI al $FechaF Institucion: $cIns[alias]";

  }

}


  require ("config.php");




  
  ?>

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  
	<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title>Medicos por entidad federativa ::..</title>
		<link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
  
		<link href="estilos.css" rel="stylesheet" type="text/css"/>
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
		<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>
		<link rel='icon' href='favicon.ico' type='image/x-icon' />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
  
	  </head>
	<?php
	  echo '<body topmargin="1">';
	  encabezados();
	  menu($Gmenu, $Gusr);
	  ?>
	  <script src="./controladores.js"></script>

  <?php

?>

      <table width="100%" border="0">
  <tr> 
    <td align="center" width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
        <?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "$Titulo"; ?>&nbsp;</div></td>
  </tr>
</table>

<?php

    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

  $Ini = 0+substr($FechaI,5,2);
  $Fin = 0+substr($FechaF,5,2);
  
  echo "<table width='99%' align='center' border=0 cellpadding=2 cellspacing=0 bgcolor='#FFFFFF'>";
  echo "<tr bgcolor='#a2b2de'>"; 
  echo "<td>$Gfon <b>Estado</b></td>";
  echo "<td>$Gfon <b>Municipio</b></td>";
  echo "<td>$Gfon <b>Colonia</b></td>";
  echo "<td align='center'>$Gfon <b>Cantidad</b></td>";
  echo "<td align='center'>$Gfon <b>Importe</b></td>";
  echo "</tr>";

  
  while($rg = mysql_fetch_array($OtA)){

      if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
    echo "<td>$Gfon $rg[estado]</td>";
		echo "<td>$Gfon $rg[munconsultorio]</td>";
		echo "<td>$Gfon $rg[locconsultorio]</td>";
    $municipio=base64_encode($rg[munconsultorio]);
    $localidad=base64_encode($rg[locconsultorio]);
    $estado=base64_encode($rg[estado]);
		echo "<td align='right'><a href=javascript:winuni('repentidadmedet.php?FechaI=$FechaI&FechaF=$FechaF&Institucion=$Institucion&sucursal=$filtro3&estado=$estado&munconsultorio=$municipio&locconsultorio=$localidad&Resumen=$Resumen')>$Gfon $rg[cantidad]</a></td>";
    echo "<td align='right'>$Gfon ". number_format($rg[importe],"2")."</td>";
		echo "</tr>";
      $importet += $rg[importe];
      $nCnt  += $rg[cantidad];
      $nRng++;
		
  }

  echo "<tr>";
  echo "<td> </td>";
  echo "<td> </td>";
  echo "<td>$Gfon <b>Total ---> </b></td>";
  echo "<td align='right'>$Gfon <b>".number_format($nCnt,"0")."</b></td>";
  echo "<td align='right'>$Gfon <b>".number_format($importet,"2")."</b></td>";
  echo "</tr>";

  echo "</table>";

  echo "<table align='center' width='98%' border='0'>";

  echo "<tr><td width='8%'><font face='verdana' size='-2'><a href='menu.php'><i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font></a>";

  echo "</td><td width='82%'> ";

  echo "<form name='form1' method='post' action='repentidadmed.php'>";

  echo "$Gfont <b> Suc: </b></font>";
  echo "<select size='1' name='filtro3' class='Estilo10'>";

  $SucA=mysql_query("SELECT id,alias FROM cia order by id");
  echo "<option value='*'>Todos*</option>";
  while($Suc=mysql_fetch_array($SucA)){
    echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
    if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
    if($filtro3=='*'){$DesSuc=' Todos';}
  }

  echo "<option selected value='$filtro3'>$Gfont $filtro3 $DesSuc</option></p>";      
  echo "</select>";
  echo"</b>";


  echo "$Gfont &nbsp; &nbsp; <b> Instit: </b></font>";
  echo "<SELECT name='Institucion'>";
  
  $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
  echo "<option value='*'>*, todas</option>"; 
  while($Ins=mysql_fetch_array($InsA)){
        echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";  
        if($Ins[institucion]==$filtro3){$DesInst=$Ins[alias];}
        if($Institucion=='*'){$DesInst=' Todas';}
  }      
  echo "<option selected value='$Institucion'>$Gfont $Institucion $DesInst</option>";   
  echo "</select> &nbsp; ";

  echo "$Gfont &nbsp; &nbsp; <b> Fech Ini: </b><input type='text' name='FechaI' value='$FechaI' size='9' maxlength='10'>";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

  echo "$Gfont &nbsp; &nbsp; <b> Fech Final: </b><input type='text' name='FechaF' value='$FechaF' size='9' maxlength='10'> ";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
 
  echo "$Gfont &nbsp; &nbsp; <input type='submit' name='Boton' value='Enviar'>"; 

  if($_REQUEST[Boton] == Resumen){

      echo "$Gfont &nbsp; &nbsp; <input type='submit' name='Boton' value='Detalle'>"; 

  }else{

    echo "$Gfont &nbsp; &nbsp; <input type='submit' name='Boton' value='Resumen'>"; 

  }



echo "</font></td><td>";

$filtr2="FechaI=$FechaI&FechaF=$FechaF&filtro3=$filtro3&institucion=$Institucion&resumen=$Resumen";

echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('repentidadmedpdf1.php?Status2=Exportar&$filtr2')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";

echo "</tr></table>";

mysql_close();

?>

</body>

</html>