<?php

  session_start();
  include_once ("auth.php");

  include_once ("authconfig.php");
  
  include_once ("check.php");

  require("lib/lib.php");
date_default_timezone_set("America/Mexico_City");

  require ("config.php");	

  $link   = conectarse();


  

$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];


  $FechaI = $_REQUEST[FechaI];

  $FechaF = $_REQUEST[FechaF];

  $FecI=$FechaI;
  $FecF=$FechaF;
  
  $Institucion    = $_REQUEST[Institucion];
  
  $Sucursal    = $_REQUEST[Sucursal];
  
  $Det=$_REQUEST[Det];
  
  $Ref=$_REQUEST[Ref];
  



  ?>

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  
	<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
	<title>Reporte de Entidad</title>
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



  if($Ref=="MD"){
	  $referencia=" and ot.medico='MD'";
  }elseif($Ref=="AQ"){
  	  $referencia=" and ot.medico='AQ'";
  }else{
  	  $referencia="";
  }

  if (!isset($FechaI)){

      $FechaF=date("Y-m-d");

      $FechaI=date("Y-m-")."01";

  }
  
  $lU    = mysql_query("DELETE FROM rep");
  
  if($Det<>'Si'){
	  
	  if($Sucursal == "*"){
	
		  if($Institucion == "*" OR $Institucion == ''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
						$referencia
						GROUP BY cli.estado,cli.municipio");
								
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente AND ot.institucion='$Institucion'
						$referencia
						GROUP BY cli.estado,cli.municipio");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Ins: $cIns[alias] $Ref";
		
		  }
		  
	  }else{
		  
		  if($Institucion == "*" OR $Institucion==''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente and ot.suc='$Sucursal'
						$referencia
						GROUP BY cli.estado,cli.municipio");
						
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF, Suc: $Sucursa l - $cSuc[alias] $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
						AND ot.institucion='$Institucion' and ot.suc='$Sucursal' $referencia
						GROUP BY cli.estado,cli.municipio");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
			 
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias], Suc: $Sucursal - $cSuc[alias] $Ref";
		
		  }
 	 }

  }else{
	  
	  if($Sucursal == "*"){
	
		  if($Institucion == "*" OR $Institucion == ''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente $referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
								
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente AND ot.institucion='$Institucion'
						$referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias]  $Ref";
		
		  }
		  
	  }else{
		  
		  if($Institucion == "*" OR $Institucion==''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente and ot.suc='$Sucursal'
						$referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
						
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF, Suc: $Sucursal - $cSuc[alias]  $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
						AND ot.institucion='$Institucion' and ot.suc='$Sucursal' $referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
			 
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias], Suc.: $Sucursal - $cSuc[alias]  $Ref";
		
		  }
 	 }
  
  }
  
  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#ffffff'> &nbsp; ";

  require ("config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<meta charset="UTF-8">

<table width="100%" border="0">
          <tr> 

            <td align="center" width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
                <?php echo "$Titulo"; ?>&nbsp;</div></td>
          </tr>
        </table>

</head>

        <link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="jquery-ui/jquery-ui.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>

<?php



  echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

  //headymenu($Titulo,0);

  $Ini = 0+substr($FechaI,5,2);
  $Fin = 0+substr($FechaF,5,2);


  if($Det<>'Si'){

	  echo "<br>";
	
	  echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=2 bgcolor='#FFFFFF'>";
	  echo "<td>$Gfont Estado</td>";
	  echo "<td>$Gfont Municipio</td>";
	  echo "<td>$Gfont Cantidad</td>";
	  echo "<td>$Gfont Importe</td>";
	  echo "</tr>";
	
	  
	  while($rg = mysql_fetch_array($OtA)){
	
		  $Edo  = str_replace(" ","!",$rg[estado]);                      //Remplazo la comita p'k mande todo el string
		  $Mpio = str_replace(" ","!",$rg[municipio]);                      //Remplazo la comita p'k mande todo el string
	
		  if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
	
		  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
			//echo "<td align='center'><a href=javascript:winuni('repentdet.php?FechaI=$FechaI&FechaF=$FechaF&Estado=$rg[estado]&Municipio=$rg[municipio]&Colonia=$rg[colonia]')><img src='lib/browse.png' border=0></a></td>";
			echo "<td>$Gfont $rg[estado]</td>";
			echo "<td>$Gfont $rg[municipio]</td>";
			echo "<td align='right'>$Gfont $rg[cantidad]</td>";
			echo "<td align='right'>$Gfont ".number_format($rg[importotal],"2")."</td>";
			echo "</tr>";
	
		  $nCnt  += $rg[cantidad];
		  $nImpt  += $rg[importotal];
		  $nRng++;
			
	  }
	
	  echo "<tr>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont Total ---> </td>";
	  echo "<td align='right'>$Gfont ".number_format($nCnt,"0")."</td>";
	  echo "<td align='right'>$Gfont ".number_format($nImpt,"2")."</td>";
	  echo "</tr>";
	
	  echo "</table>";
	  
  }else{

	  echo "<br>";
	
	  echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=2 bgcolor='#FFFFFF'>";
	  echo "<tr height='21' background='lib/bartit.gif' >"; 
	  echo "<td align='center'>$Gfont Detalle</td>";
	  echo "<td>$Gfont Estado</td>";
	  echo "<td>$Gfont Municipio</td>";
	  echo "<td>$Gfont Colonia</td>";
	  echo "<td>$Gfont Cantidad</td>";
	  echo "<td>$Gfont Importe</td>";
	  echo "</tr>";
	
	  
	  while($rg = mysql_fetch_array($OtA)){
	
		  $Edo  = str_replace(" ","!",$rg[estado]);                      //Remplazo la comita p'k mande todo el string
		  $Mpio = str_replace(" ","!",$rg[municipio]);                      //Remplazo la comita p'k mande todo el string
		  $Col  = str_replace(" ","!",$rg[colonia]);                      //Remplazo la comita p'k mande todo el string
	
		  if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
	
		  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
			echo "<td align='center'><a class='edit' href=javascript:winuni('repentdet.php?FecI=$FechaI&FecF=$FechaF&Col=$Col&Mpio=$Mpio&Edo=$Edo&Suc=$Sucursal&Institucion=$Institucion')><i class='fa fa-address-card fa-lg' aria-hidden='true'></i></a></td>";
			echo "<td>$Gfont $rg[estado]</td>";
			echo "<td>$Gfont $rg[municipio]</td>";
			echo "<td>$Gfont $rg[colonia]</td>";
			echo "<td align='right'>$Gfont $rg[cantidad]</td>";
			echo "<td align='right'>$Gfont ".number_format($rg[importotal],"2")."</td>";
			echo "</tr>";
	
		  $nCnt  += $rg[cantidad];
		  $nImpt  += $rg[importotal];
		  $nRng++;
			
	  }
	
	  echo "<tr>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont Total ---> </td>";
	  echo "<td align='right'>$Gfont ".number_format($nCnt,"0")."</td>";
	  echo "<td align='right'>$Gfont ".number_format($nImpt,"2")."</td>";
	  echo "</tr>";
	
	  echo "</table>";
  }

  
  echo "<table align='center' width='98%' border='0'>";

  echo "</td><td>$Gfont ";

  echo "<form name='form1' method='post' action='repentidad1.php'>";

  echo "&nbsp;&nbsp;&nbsp; Suc: $Sucursal";
  echo "<SELECT name='Sucursal'>";
  $SucA = mysql_query("SELECT id,alias FROM cia ORDER BY id");
  while($Suc=mysql_fetch_array($SucA)){
        echo "<option value='$Suc[0]'>$Suc[1]</option>";  
		  if($Sucursal == "*"){
			  $alias="* Todas";
		  }else{
			  $alias=$cSuc[alias];
		  }
  }      
  echo "<option value='*'>*, todas</option>";
  echo "<option selected value='$Sucursal'> $alias</option>"; 
  echo "</select> &nbsp; ";

  echo "Inst: $Institucion";
  echo "<SELECT name='Institucion'>";
  $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
  while($Ins=mysql_fetch_array($InsA)){
        echo "<option value='$Ins[0]'>$Ins[alias]</option>"; 
		  if($Institucion == "*"){
  			  $aliasinst="* Todas";
		  }else{
			  $aliasinst=$cIns[alias];
		  } 
  }      
  echo "<option value='*'>*, todas</option>"; 
  echo "<option selected value='$Institucion'> $aliasinst</option>";  
  echo "</select> &nbsp; ";

  echo " Fech Ini: <input type='text' name='FechaI' value='$FechaI' size='9' maxlength='10'>";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

  echo " &nbsp;&nbsp; Fech Fin: <input type='text' name='FechaF' value='$FechaF' size='9' maxlength='10'> ";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
  
 echo " &nbsp;&nbsp; Det:";
 echo "<select name='Det'>";
 echo "<option value='Si'>Si</option>";
 echo "<option value='No'>No</option>";
 echo "<option selected value='$Det'>$Det</option>";
 echo "</select> &nbsp;";


 echo " &nbsp;&nbsp; Ref:";
 echo "<select name='Ref'>";
 echo "<option value='MD'>MD</option>";
 echo "<option value='AQ'>AQ</option>";
  echo "<option value='*'>* Todos</option>";
 echo "<option selected value='$Ref'>$Ref</option>";
 echo "</select> &nbsp;&nbsp;";
 
 echo "<input type='submit' name='Boton' value='Enviar'>"; 

echo "</font></td><td>";

echo "</form>";

echo "</tr></table>";


echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='menu.php'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";

echo "<div align='center'>";
echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('repentidadpdf.php?Sucursal=$Sucursal&Institucion=$Institucion&FechaI=$FecI&FechaF=$FecF&Det=$Det&Ref=$Ref')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";

mysql_close();

?>

</body>

</html>