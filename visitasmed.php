<?php

  session_start();
    
  require("lib/lib.php");

  $link     = conectarse();  

  #Variables comunes;
  $Titulo = "Registro de visitas";
  $Med  = $_REQUEST[Med];	
  $FechaI	=	$_REQUEST[FechaI];
  $FechaF	=	$_REQUEST[FechaF];
  
  require ("config.php");							//Parametros de colores;
  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
  $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
  $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>$Titulo</title>";
echo "<link href='estilos.css' rel='stylesheet' type='text/css'/>";
echo "<link href='//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>";
echo "</head>";

echo "<body bgcolor='#FFFFFF'>";

$registro   = mysql_fetch_array(mysql_query("SELECT medico,nombrec as nommedico FROM med WHERE medico='$Med'")); 

echo "<table width='100%' height='80' border='0'>";    //Encabezado
echo "<tr><td width='26%' height='76'>";
echo "<p align=='left'><img src='lib/DuranNvoBk.png' width='187' height='70'></p>";
echo "</td>";
echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de Visitas</p>";
echo "</td></tr></table>";
echo "<p><strong><font size='1' face='Arial, Helvetica, sans-serif'>Medico: $Med - $registro[nommedico] </strong></p>";

echo "<hr>";
echo "<table width='100%' border='0'>";    //Encabezado
echo "<tr>";
echo "<th align='center' height='26'>$Gfont Movto</th>";
echo "<th align='center' height='26'>$Gfont Periodo</th>";
echo "<th align='center' height='26'>$Gfont Fec/vis</th>";
echo "<th height='26'>$Gfont Comentario</th>";
echo "<th height='26'>$Gfont Comision</th>";
echo "</tr>";

      $PgsA   = mysql_query("SELECT periodo,fecha,nota,importe,movto FROM pgs WHERE pgs.medico='$Med' and date_format(pgs.fecha,'%Y%m') Between '$FechaI' And '$FechaF'"); 

      while($rg=mysql_fetch_array($PgsA)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'>$Gfont $rg[movto]</td>";
           echo "<td align='center'>$Gfont $rg[periodo]</td>";
           echo "<td align='center'>$Gfont $rg[fecha]</td>";
           echo "<td align='left' width='500px'>$Gfont $rg[nota]</td>";
           echo "<td align='right'>$Gfont".number_format($rg[importe],"2")."</td>";
           echo "</tr>";

           $nRng++;

      }

	
	
   echo "</table>";     

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>