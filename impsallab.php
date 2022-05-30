<?php

  session_start();
  require("lib/lib.php");
  $link   = conectarse();

  $busca  = $_REQUEST[busca];
  
  $Titulo = "Remision";

  //$cSqlD="select sd.id,sd.fecha,sd.cliente,sd.kilos,sd.bolsas,sd.ubi,sd.status,cli.alias from sd left join cli on sd.proveedor=cli.id where sd.id = '$busca'";
  $HeA = mysql_query("SELECT sl.id,sl.fecha,sl.hora,sl.concepto,sl.cantidad,sl.importe,sl.recibio
         FROM sl
         WHERE sl.id = '$busca'");

  $He=mysql_fetch_array($HeA);

  require ("config.php");

  require_once("importeletras.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

  //headymenu($Titulo);

  echo "<body>";

  echo "<div align='left'>$Gfont &nbsp; &nbsp; &nbsp;  <font size='+1'>$Gcia</font></font></div><br>";

  echo "<table align='center' width='95%' border='0' >";

  echo "<tr>";
  echo "<td>$Gfont <b> No.Salida: $He[id]</b></td>";
  echo "<td align='right'>$Gfont Fecha: $He[fecha]</td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont Concepto: $He[concepto]</td>";
  echo "<td align='right'>$Gfont Cantidad: ".number_format($He[cantidad],"2")."</td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont Recibio: $He[recibio] &nbsp; &nbsp; </td>";
  echo "<td align='right'>$Gfont  </td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont  </td>";
  echo "<td align='right'>$Gfont Costo: $ ".number_format($He[importe],"2")." </td>";
  echo "</tr>";
  
  echo "</table>";
    
  //echo "<hr>";

  $Sql = "SELECT sld.clave, sld.cantidad, sld.costo, invl.descripcion
         FROM sld, invl
         WHERE sld.id = '$busca' AND sld.clave = invl.clave
         ORDER BY sld.clave";

  $Rolls = $Peso = $Defectos = 0;
  $res   = mysql_query($Sql);

  echo "<table align='center' width='98%' border='0'>";
  echo "<tr>";
  echo "<th>$Gfont Producto</th>";
  echo "<th>$Gfont  Descripcion</th>";
  echo "<th>$Gfont  Cantidad</th>";
  echo "<th>$Gfont  Costo</th>";
  echo "<th>$Gfont  &nbsp; Importe</th>";
  echo "</tr>";
  
  
  while( $rg = mysql_fetch_array($res) ){
	   echo "<tr>";
  		echo "<td>$Gfont $rg[clave] </td>";
  		echo "<td>$Gfont $rg[descripcion] </td>";
  		echo "<td align='right'>$Gfont $rg[cantidad]</td>";
  		echo "<td align='right'>$Gfont ".number_format($rg[costo],"2")."</td>";
  		echo "<td align='right'>$Gfont ".number_format($rg[costo]*$rg[cantidad],"2")."</td>";
  		echo "</tr>";
  		$Costo += $rg[costo]*$rg[cantidad];                  				
  }                

  echo "</table>";
  

  echo "<p>&nbsp;</p>";
    
  $Imp = impletras($He[importe],'pesos');
  
  echo "<div align='left'>$Gfont &nbsp; &nbsp;  $Imp</div>";

  while($nRng < 30){
   
     echo "<div>&nbsp;</div>";
	  $nRng++;	  
  
  }

  echo "<div align='center'>________________________________</div>";
  echo "<div align='center'>Firma de recibido</div>";
  echo "<div align='center'>$He[recibio]</div>";
    
  echo "<table align='center' width='95%' border='0' >";

  echo "<tr>";
  echo "<td width='65%'>$Gfont </td>";
  echo "<td align='right'>$Gfont Costo: $</td>";
  echo "<td align='right'>$Gfont ".number_format($Costo,"2")." &nbsp; </td>";
  echo "</tr>";
  
  echo "</table>";
    
  //echo "<p align='center'>$Gfont <b>Total --------> No.rollos: $He[rollos] &nbsp; &nbsp; &nbsp; &nbsp; Peso: &nbsp; ";

  //echo number_format($He[cantidad],"2")."</b></p>";

   
  echo "<br>";

  echo "<form name='form1' method='post' action='impsallab.php?busca=$busca'>";

     echo "<input type='submit' name='Imprimir' value='Imprime' onClick='print()'>";

  echo "</form>";
    
  //echo "<input type='submit' name='Imprimir' value='Imprime' onCLick='print()'>";

echo "</body>";

echo "</html>";

mysql_close();


?>