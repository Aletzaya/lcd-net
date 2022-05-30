<?php

  session_start();
    
  require("lib/lib.php");

  $link     = conectarse();  

  #Variables comunes;
  $Titulo = "Registro de envio de resultados por correo";
  $busca  = $_REQUEST[busca]; 

  $Gfont = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='2'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";

  require ("config.php");							//Parametros de colores;

echo "<html>";

echo "<head>";
echo " <meta charset='UTF-8'>";
echo "<title>$Titulo</title><link href='estilos.css?var=1.1' rel='stylesheet' type='text/css'/>";

echo "</head>";

echo "<body bgcolor='#FFFFFF'>";

     	echo "<table width='100%' border='0'>";    //Encabezado
   	echo "<tr>";
    echo "<th align='center' height='26'>$Gfont Fecha</th>";
   	echo "<th align='center' height='26'>$Gfont Usuario</th>";
    echo "<th align='center' height='26'>$Gfont Mail/Pac</th>";
    echo "<th align='center' height='26'>$Gfont Mail/Med</th>";
    echo "<th align='center' height='26'>$Gfont Mail/Inst</th>";
    echo "<th align='center' height='26'>$Gfont Estudio</th>";
		echo "</tr>";

      $PgsA   = mysql_query("SELECT * FROM logenvio WHERE orden='$busca' order by fecha desc"); 

      while($rg=mysql_fetch_array($PgsA)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'>$Gfont $rg[fecha]</td>";
           echo "<td align='center'>$Gfont $rg[usr]</td>";
           echo "<td align='center'>$Gfont $rg[emailp]</td>";
           echo "<td align='center'>$Gfont $rg[emailm]</td>";
           echo "<td align='center'>$Gfont $rg[emaili]</td>";
           echo "<td align='center'>$Gfont $rg[estudio]</td>";
           echo "</tr>";

           $nRng++;

      }

  echo "<div align='center'>$Gfont <b> $nRng Envio de resultados de la orden: $busca <b></div><br>";
	
   echo "</table>";     

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>