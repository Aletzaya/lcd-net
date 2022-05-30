<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $Institucion  = $_REQUEST[Institucion];

  $Depto = $_REQUEST[depto];
  
  $Suc 			=   $_REQUEST[Suc];

  $FechaI		=	$_REQUEST[FecI];

  $FechaF		=	$_REQUEST[FecF];

  $Capt   = $_REQUEST[capt];

  $Etapa   = $_REQUEST[etapa];

 if($Capt=='SIN_REGISTRO'){
 	$Capt="";
 }else{
  $Capt=$Capt;
 }

 if($Suc<>'*'){
  $filtro4="and ot.suc='$Suc'";
 }else{
  $filtro4=" ";
 }

 if($Institucion<>'*'){
  $filtro6="and ot.institucion='$Institucion'";
 }else{
  $filtro6=" ";
 }

  if($Depto<>'*'){
  $filtro8="and est.depto='$Depto'";
 }else{
  $filtro8=" ";
 }

if($Etapa=='Atencion'){
  $filtro10="otd.usrest";
 }elseif($Etapa=='Captura'){
  $filtro10="otd.capturo";
 }elseif($Etapa=='Impresion'){
  $filtro10="otd.impest";
 }elseif($Etapa=='Proceso'){
  $filtro10="maqdet.usrrec";
 }

//  $Titulo=$_REQUEST[Titulo];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
          <meta charset="UTF-8">
              <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
              <title>Detalle de equipo</title>
              <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
              <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
              <link rel='icon' href='favicon.ico' type='image/x-icon' />
              <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      </head>
<body>

<?php

    $cSql="SELECT otd.capturo,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,est.subdepto,count(distinct ot.orden),sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
     from otd,ot,est
    	WHERE otd.estudio = est.estudio and otd.capturo='$Capt' and ot.orden=otd.orden and ot.fecha Between '$FechaI' And '$FechaF' $filtro4 $filtro6 $filtro8
    	GROUP BY est.depto, est.subdepto, otd.estudio";

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr>
    <td><div align='center'>
        <font size="4" face="Arial, Helvetica, sans-serif"><strong>Laboratorio Clinico Duran</strong></font><br>
        <font size="2"><?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Demanda de estudios del $FechaI al $FechaF Sucursal: $Suc Institucion: $Institucion - $NomI[nombre] - Usuario: $Capt</p>";?>
        <font size="2"><?php // echo "$Titulo"; ?>
        </div>
    </td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php

    echo "<table align='center' width='90%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Depto</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Subdepto</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Estudio</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>#Estudios</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Importe</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Descuentos</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Total</font></th>";

    $Subtotal=0;
		$Total=0;
		$Descuentos=0;
		$Noveces=0;
		$subdep=" ";
		$orden=0;
		$nordenes=0;
    $preciose=0;


      while($registro=mysql_fetch_array($UpA)) {
        if($subdep==$registro[6]){
          $departamento1=$departamento;
          $subdepartamento1=$subdepartamento;
          $estudio1=$estudio;
        }else{
          $departamento=$registro[7];
          $subdepartamento=$registro[8];
          $estudio=$registro[6];
          if($Noveces<>0){
              echo "<tr>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$departamento1</font></td>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$subdepartamento1</font></td>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$estudio1</font></td>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces2)."</strong></font></td>";
              echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($preciose,'2')."</strong></font></td>";
              echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($descuentose,'2')."</strong></font></td>";
              echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($preciose-$descuentose,'2')."</strong></font></td>";
              echo "</tr>";
                 $Noveces3=$Noveces3+$Noveces2;
                 $Nordenes3=$Nordenes3+$Nordenes2;
                 $Descuentos3=$Descuentos3+$descuentose;
                 $Subtotal3=$Subtotal3+$preciose;
                 $Total3=$Total3+($preciose-$descuentose);
                 $Noveces2=0;
                 $Nordenes2=0;
                 $Descuentos2=0;
                 $Subtotal2=0;
                 $Total2=0;
                 $departamento1=$departamento;
                 $subdepartamento1=$subdepartamento;
                 $preciose=0;
                 $descuentose=0;
          }
        }

          $departamento1=$departamento;
          $subdepartamento1=$subdepartamento;
          $estudio1=$estudio;

      ?>
</font></font>

<font size="1" face="Arial, Helvetica, sans-serif">
<?php
             $Noveces2=$Noveces2+$registro[2];
             $Nordenes2=$Nordenes2+$registro[9];
             $Descuentos2=$Descuentos2+$registro[5];
             $Subtotal2=$Subtotal2+$registro[4];
             $Total2=$Total2+($registro[4]-$registro[5]);

             $Noveces=$Noveces2+$registro[2];
             $Nordenes=$Nordenes2+$registro[9];
             $Descuentos=$Descuentos2+$registro[5];
             $Subtotal=$Subtotal2+$registro[4];
             $Total=$Total2+($registro[4]-$registro[5]);
             $Cuenta=$Cuenta+$registro[9];
             $subdep=$registro[6];

            $preciose=$preciose+$registro[precios];
            $descuentose=$descuentose+$registro[descuentos];

    
        }//fin while
             $Noveces3=$Noveces3+$Noveces2;
             $Nordenes3=$Nordenes3+$Nordenes2;
             $Descuentos3=$Descuentos3+$descuentose;
             $Subtotal3=$Subtotal3+$preciose;
             $Total3=$Total3+($preciose-$descuentose);

        echo "<tr>";
    echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$departamento1</font></td>";
    echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$subdepartamento1</font></td>";
    echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$estudio1</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces2)."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($preciose,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($descuentose,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($preciose-$descuentose,'2')."</strong></font></td>";

        echo "</tr>";

        echo "<tr>";
        echo "<td align='CENTER' bgcolor='#000000'></td>";
        echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'> No. Estudios : </font></td>";
        echo "<td align='CENTER' bgcolor='#000000'></td>";
        echo "<td align='center' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Noveces3)."</strong></font></td>";
        echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Subtotal3,'2')."</strong></font></td>";
        echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Descuentos3,'2')."</strong></font></td>";
        echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Total3,'2')."</strong></font></td>";
        echo "</tr>";

        echo "</table>";
        echo "<br>";

?>
</font>
<div align="left">
<form name="form1" method="post">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>