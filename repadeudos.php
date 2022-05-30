<?php

session_start();


include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");


require("lib/lib.php");

require ("config.php");	

$link=conectarse();

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $Institucion=$_REQUEST[Institucion];

  $FechaI=$_REQUEST[FechaI];
  $FechaF=$_REQUEST[FechaF];

  $Titulo=$_REQUEST[Titulo];

  $cAdeudo=$_REQUEST[cAdeudo];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

?>
<html>
<head>
<meta charset="UTF-8">
<title>Adeudos</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>



<?php
if(strlen($Institucion)>0){
	$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
	$Nombre=mysql_fetch_array($NomA);
   	$Titulo="Relacion de Pagos de Ordenes de trabajo del $FechaI al $FechaF Institucion : $Institucion $Nombre[0]";
    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, ot.institucion, ot.recepcionista, ot.hora, ot.servicio, ot.importe
	FROM ot, cli 
	WHERE ot.cliente = cli.cliente and ot.fecha>='$FechaI' and
	ot.fecha <='$FechaF' and ot.institucion='$Institucion'
	order by ot.orden";
}else{
   	$Titulo="Relacion de Pagos Ordenes de trabajo del $FechaI al $FechaF";
    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, ot.institucion, ot.recepcionista, ot.hora, ot.servicio, ot.importe
	FROM ot, cli 
	WHERE ot.cliente = cli.cliente and ot.fecha>='$FechaI' and
	ot.fecha <='$FechaF'
	order by ot.orden";
}

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="lib/DuranNvoBk.png" width="187" height="70"> 
      </div></td>
    <td width="100%"><div class="letrap"><strong>Laboratorio Clinico Duran</strong><br>
        <?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "$Titulo"; ?>&nbsp;</div></td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha Captura</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Hora Captura</font></th>";		
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Abono(s)</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Capturo</font></th>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    if ($cAdeudo == "S") {
        
    while ($rg = mysql_fetch_array($UpA)) {
        $cSqlB = mysql_query("select sum(importe) from cja where cja.orden=$rg[orden]", $link);
        $SqlS2 = mysql_fetch_array($cSqlB);
        $Diferencia = $rg[importe] - $SqlS2[0];
        if ($Diferencia > 1) {
            $EstA = mysql_query("SELECT estudio FROM otd WHERE orden='$rg[orden]'");
            $Estudios = "";
            while ($Est = mysql_fetch_array($EstA)) {
                if ($Estudios == '') {
                    $Estudios = "(" . $Est[estudio];
                } else {
                    $Estudios = $Estudios . ", " . $Est[estudio];
                }
            }
            $Rec = $rg[recepcionista];

            echo "<tr>";
            echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2] $Estudios" . ")" . "</th>";

            echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[fecha]</font></th>";
            echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[hora]&nbsp;</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[importe], '2') . "</font></th>";
            echo "<th></th>";
            echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$Rec</font></th>";
            echo "</tr>";
            $cSqlA = mysql_query("select * from cja where cja.orden=$rg[orden]", $link);
            while ($SqlS = mysql_fetch_array($cSqlA)) {
                echo "<tr>";
                echo "<th>";
                echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Id.:&nbsp; $SqlS[id]&nbsp;-->&nbsp;</font></th>";
                echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$SqlS[fecha]</font></th>";
                echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$SqlS[hora]</font></th>";
                echo "<th>";
                echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($SqlS[importe], '2') . "&nbsp;</font></th>";
                echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$SqlS[usuario]</font></th>";
                echo "</tr>";
                $TotalA+=$SqlS[importe];
                $Movimientos+=1;
            }
            $Adeudo = $rg[importe] - $TotalA;
            $TAdeudo+=$Adeudo;
            $Timporte+=$rg[importe];
            $TotalTA+=$TotalA;
            $Ordenes+=1;

            if ($Adeudo > 1) {
                $MensajeA = "* * * A D E U D O * * * ";
                $Ctadeudo+=1;
            } else {
                $MensajeA = " ";
                if ($Adeudo < -1) {
                    $MensajeA = "* * * - - - > ";
                }
            }

            echo "<tr>";
            echo "<th>";
            echo "<th align='right'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'>$MensajeA</font></th>";
            echo "<th>";
            echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total: $</font></th>";
            echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[importe], '2') . "</font></th>";
            echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($TotalA, '2') . "</font></th>";
            echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Adeudo, '2') . "</font></th>";
            echo "</tr>";
            echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
            $TotalA = 0;
        }
    }
} else {

    while ($rg = mysql_fetch_array($UpA)) {
        $Rec = $rg[recepcionista];
        echo "<tr>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
        echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2]</font></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[fecha]</font></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[hora]</font></th>";
        echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[importe], '2') . "</font></th>";
        echo "<th></th>";
        echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$Rec</font></th>";
        echo "</tr>";
        $cSqlA = mysql_query("select * from cja where cja.orden=$rg[orden]", $link);
        while ($SqlS = mysql_fetch_array($cSqlA)) {
            echo "<tr>";
            echo "<th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Id.:&nbsp; $SqlS[id]&nbsp;-->&nbsp;</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$SqlS[fecha]</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$SqlS[hora]</font></th>";
            echo "<th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($SqlS[importe], '2') . "&nbsp;</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$SqlS[usuario]</font></th>";
            echo "</tr>";
            $TotalA+=$SqlS[importe];
            $Movimientos+=1;
        }
        $Adeudo = $rg[importe] - $TotalA;
        $TAdeudo+=$Adeudo;
        $Timporte+=$rg[importe];
        $TotalTA+=$TotalA;
        $Ordenes+=1;

        if ($Adeudo > 1) {
            $MensajeA = "* * * A D E U D O * * * ";
            $Ctadeudo+=1;
        } else {
            $MensajeA = " ";
            if ($Adeudo < -1) {
                $MensajeA = "* * * - - - > ";
            }
        }

        echo "<tr>";
        echo "<th>";
        echo "<th align='right'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'>$MensajeA</font></th>";
        echo "<th>";
        echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total: $</font></th>";
        echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[importe], '2') . "</font></th>";
        echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($TotalA, '2') . "</font></th>";
        echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Adeudo, '2') . "</font></th>";
        echo "</tr>";
        echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
        $TotalA = 0;
    }
}
echo "<tr>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Abono(s) : $Movimientos</font></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Adeudo(s) : $Ctadeudo</font></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>GRAN TOTAL --> $</font></th>";
  	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Timporte,'2')."</font></th>";
	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TotalTA,'2')."</font></th>";
  	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($TAdeudo,'2')."</font></th>";
     echo "</tr>"; 

    $FechaI=$_REQUEST[FechaI];
	$FechaF=$_REQUEST[FechaF];

    echo "</table>";

    echo "<div align='center'>";
    echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?Menu=5&fechas=1&FechaI=$FecI&FechaF=$FecF'>";
    echo "<i class='fa fa-reply fa-3x' aria-hidden='true'></i> Regresar </a></font>";
    echo "</div>";
    
    echo "<div align='center'>";
    echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('repadeudospdf.php?FechaI=$FechaI&FechaF=$FechaF&Institucion=$Institucion&cAdeudo=$cAdeudo')><i class='fa fa-print fa-3x' aria-hidden='true'></i></a></td>";



    
    
    
     echo "</form>";
     echo "</div>";
	 
?>
</body>
</html>

<?php
mysql_close();
?>

