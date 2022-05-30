<?php

  session_start();
  include_once ("auth.php");

  include_once ("authconfig.php");
  
  include_once ("check.php");

  require("lib/lib.php");

  $link   = conectarse();

  $FechaI = $_REQUEST[FecI];

  $FechaF = $_REQUEST[FecF];
  
  $Ins    = $_REQUEST[Institucion];
  
  $Suc    = $_REQUEST[Suc];
  
  $Edo    = str_replace("!"," ",$_REQUEST[Edo]);                      //Remplazo la comita p'k mande todo el string
  $Col    = str_replace("!"," ",$_REQUEST[Col]);                      //Remplazo la comita p'k mande todo el string
  $Mpio   = str_replace("!"," ",$_REQUEST[Mpio]);                      //Remplazo la comita p'k mande todo el string
  
  ?>
<html>
    <head>
    <meta charset="UTF-8">
        <title>Sistema de Laboratorio clinico</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body>

<?php

  if($Ins=="*" or $Ins==''){
	  if($Suc=="*" or $Suc==''){  
		  $OtA  = mysql_query("SELECT cli.cliente,cli.nombrec,cli.direccion,cli.colonia,cli.municipio,ot.orden,cli.usr,ot.medico
					 FROM cli, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.cliente=cli.cliente 
					 AND cli.estado = '$Edo' AND cli.municipio='$Mpio' AND cli.colonia='$Col'
					 ");
	  }else{
		  $OtA  = mysql_query("SELECT cli.cliente,cli.nombrec,cli.direccion,cli.colonia,cli.municipio,ot.orden,cli.usr,ot.medico
					 FROM cli, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.cliente=cli.cliente 
					 AND cli.estado = '$Edo' AND cli.municipio='$Mpio' AND cli.colonia='$Col' and ot.suc='$Suc'
					 ");
	  }
  }else{
	  if($Suc=="*" or $Suc==''){  
		  $OtA  = mysql_query("SELECT cli.cliente,cli.nombrec,cli.direccion,cli.colonia,cli.municipio,ot.orden,cli.usr,ot.medico
					 FROM cli, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.cliente=cli.cliente 
					 AND cli.estado = '$Edo' AND cli.municipio='$Mpio' AND cli.colonia='$Col' and ot.institucion='$Ins'
					 ");
	  }else{
		  $OtA  = mysql_query("SELECT cli.cliente,cli.nombrec,cli.direccion,cli.colonia,cli.municipio,ot.orden,cli.usr,ot.medico
					 FROM cli, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.cliente=cli.cliente 
					 AND cli.estado = '$Edo' AND cli.municipio='$Mpio' AND cli.colonia='$Col' and ot.suc='$Suc' and ot.institucion='$Ins'
					 ");
	  }
  }
          			    

 $Titulo = "Detalle Edo: $Edo Col: $Col del $FechaI al $FechaF";
			    
  
  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#ffffff'> &nbsp; ";

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
<meta charset="UTF-8">
<title><?php echo $Titulo;?></title>

</head>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php

  echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

  //headymenu($Titulo,0);

  $Ini = 0+substr($FechaI,5,2);
  $Fin = 0+substr($FechaF,5,2);
  
  echo "<br>";

  echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=2 bgcolor='#FFFFFF'>";
  echo "<tr height='21' background='lib/bartit.gif' >"; 
  echo "<td align='center'>$Gfont <font size='1'><b> Cuenta</td>";
  echo "<td>$Gfont <font size='1'><b> Nombre</td>";
  echo "<td>$Gfont <font size='1'><b> Direccion</td>";
  echo "<td>$Gfont <font size='1'><b> Colonia</td>";
  echo "<td>$Gfont <font size='1'><b> Municipio</td>";
  echo "<td>$Gfont <font size='1'><b> Orden</td>";
  echo "<td>$Gfont <font size='1'><b> Med</td>";
  echo "<td>$Gfont <font size='1'><b> Usr</td>";
  echo "</tr>";

  
  while($rg = mysql_fetch_array($OtA)){

      if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		//echo "<td align='center'><a href=javascript:winuni('repentdet.php?FechaI=$FechaI&FechaF=$FechaF&Estado=$rg[estado]&Municipio=$rg[municipio]&Colonia=$rg[colonia]')><img src='lib/browse.png' border=0></a></td>";
		echo "<td>$Gfont <font size='1'>$rg[cliente]</td>";
		echo "<td>$Gfont <font size='1'><a class='vt' href=javascript:winuni('Upcliente.php?busca=$rg[cliente]') title='Click para actualizar sus datos'>".$rg[nombrec]."</a></td>";
		echo "<td>$Gfont <font size='1'>$rg[direccion]</td>";
		echo "<td>$Gfont <font size='1'>$rg[colonia]</td>";
		echo "<td>$Gfont <font size='1'>$rg[municipio]</td>";
		echo "<td>$Gfont <font size='1'>$rg[orden]</td>";
		echo "<td>$Gfont <font size='1'>$rg[medico]</td>";
		echo "<td>$Gfont <font size='1'>$rg[usr]</td>";
		echo "</tr>";

      $nCnt  += $rg[cantidad];
      $nRng++;
		
  }

  echo "<tr>";
  echo "<td>$Gfont </td>";
  echo "<td>$Gfont </td>";
  echo "<td>$Gfont </td>";
  echo "<td>$Gfont <font size='1'><b>Total ---> </td>";
  echo "<td align='right'>$Gfont <font size='1'><b>".number_format($nRng,"0")."</td>";
  echo "</tr>";

  echo "</table>";

  echo "<table align='center' width='98%' border='0'>";


echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='javascript:window.close()'>";
echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
echo "</div>";


  echo "</td><td>$Gfont ";

echo "</tr></table>";

mysql_close();

?>

</body>

</html>