<?php

  session_start();

  require("lib/importeletras.php");

  $Titulo="Relacion de movimientos por producto";

  require("lib/lib.php");

  $link=conectarse();

  $clave     =   $_REQUEST[clave];
  $busca     =   $_REQUEST[busca];

  $Usr=$HTTP_SESSION_VARS['usuario_login'];
  
  $NomA=mysql_query("select * from invl where invl.id='$busca'",$link);

  $Nom=mysql_fetch_array($NomA);

  $cSql="select id,clave,cantidad,costo from eld where eld.idproducto='$busca'";

  require ("config.php");

  ?>
  <html>
  
  <head>
  <meta charset="UTF-8">
  <title>Movimientos</title>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
          <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>

<?php

echo "<table width='100%' height='80' border='0'>";    //Encabezado
echo "<tr><td width='26%' height='76'>";
echo "<p align=='left'><img src='lib/DuranNvoBk.png' width='187' height='70'></p>";
echo "</td>";
echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de movimientos por producto</p>";
echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'><b>$clave -  $Nom[descripcion]</b></p>";
echo "</td></tr></table>";
echo "<hr>";
        
  //echo $cSql;
  if(!$res=mysql_query($cSql,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados ò hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='comisiones.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{

        //$registro=mysql_fetch_array($res);

    echo "<table width='100%' height='80' border='1'>";   
    echo "<tr><td>";

        echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr>";
		    echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Id de Compra</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Fecha</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Cantidad</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Costo</th>";
        echo "<tr>";

        $ContadorTEst=1;

        while ($registro=mysql_fetch_array($res)){

          if( ($nRng2 % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CCCCCC';}    //El resto de la division;
          $nRng2++;

          $EncA=mysql_query("select * from el where el.id='$registro[id]'",$link);

          $Enc=mysql_fetch_array($EncA);

          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[id]."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$Enc[fecha]."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[cantidad]."</font></td>";
          echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($registro[costo],'2')."</font></td>";
        }

    }
  echo "</td></tr>";
  echo '</table>';

  echo '</table>';

  echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";

    ?>
</body>
</html>
<?php
mysql_close();
?>