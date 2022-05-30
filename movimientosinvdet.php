<?php

  session_start();

  $Titulo="Relacion de movimientos por producto";

  require("lib/lib.php");

  $link=conectarse();

  $clave     =   $_REQUEST[clave];
  $busca     =   $_REQUEST[busca];
  $suc     =   $_REQUEST[suc];

  $FechaI = $_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI=date("Y-m-")."01";
  }

  $FechaF = $_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
    echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }


  if($suc=='*'){
    $sucinvt=" ";
  }else{
    $sucinvt="and invldet.suc='$suc'";
  }
  
  $NomA=mysql_query("select * from invldet where invldet.idproducto='$busca' $sucinvt and date_format(invldet.fecha,'%Y-%m-%d')>='$FechaI' and date_format(invldet.fecha,'%Y-%m-%d')<='$FechaF' ORDER BY invldet.id DESC ",$link);

  $cSql=mysql_query("select descripcion from invl where invl.clave='$clave'",$link);

  $Sql=mysql_fetch_array($cSql);

  require ("config.php");

  ?>
  <html>
  
  <head>
  <meta charset="UTF-8">
  <title>Movimiento de inventario</title>
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
echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'><b>$clave -  $Sql[descripcion]</b></p>";
echo "</td></tr></table>";
echo "<hr>";
        
echo "<table align='center' width='100%' border='0'>";

echo "<form name='form' method='post' action='movimientosinvdet.php?busca=$busca&suc=$suc&FechaI=$FechaI&FechaF=$FechaF'>";

echo "<tr>";
echo "<td>&nbsp; $Gfont <font size='1'><b> DE: $FechaI </b></font><input type='hidden' readonly='readonly' name='FechaI' size='10' value ='$FechaI' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)></td>";

echo "<td>&nbsp; $Gfont <font size='1'><b> A: $FechaF </b></font><input type='hidden' readonly='readonly' name='FechaF' size='10' value ='$FechaF' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></td>";
echo "</tr>";

echo "</form>";
echo '</table>';

    echo "<table width='100%' height='80' border='1'>";   
    echo "<tr><td>";

        echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Fecha</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Estudio</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Cantidad</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Usuario</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Orden</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Suc.Realizada</th>";
        echo "<th with='10%' align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Suc.Origen</th>";
        echo "<tr>";

        $ContadorTEst=1;

        while ($registro=mysql_fetch_array($NomA)){

          if( ($nRng2 % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CCCCCC';}    //El resto de la division;
          $nRng2++;

            if($registro[suc]==1 or $registro[suc]==0){

              $Almacencap='Matriz';

            }elseif($registro[suc]==2){

              $Almacencap='Futura';

            }elseif($registro[suc]==3){

              $Almacencap='Tepexpan';

            }elseif($registro[suc]==4){

              $Almacencap='Los Reyes';

            }elseif($registro[suc]==5){

              $Almacencap='Camarones';

            }elseif($registro[suc]==6){

              $Almacencap='San Vicente';

            }


            if($registro[sucorigen]==1 or $registro[sucorigen]==0){

              $Almacenrea='Matriz';

            }elseif($registro[sucorigen]==2){

              $Almacenrea='Futura';

            }elseif($registro[sucorigen]==3){

              $Almacenrea='Tepexpan';

            }elseif($registro[sucorigen]==4){

              $Almacenrea='Los Reyes';

            }elseif($registro[sucorigen]==5){

              $Almacenrea='Camarones';

            }elseif($registro[sucorigen]==6){

              $Almacenrea='San Vicente';

            }


          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[fecha]."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[estudio]."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[cantidad]."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[usr]."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[orden]."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$Almacencap."</font></td>";
          echo "<td align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$Almacenrea."</font></td>";
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