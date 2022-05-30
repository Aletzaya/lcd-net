<?php

    session_start();

    require("lib/lib.php");

    $link     = conectarse();

    $FechaI = $_REQUEST[FechaI];

    $FechaF = $_REQUEST[FechaF];

    $Resumen = $_REQUEST[Resumen];

    $Institucion = $_REQUEST[Institucion];

    $sucursal = $_REQUEST[sucursal];

    if($sucursal=='*'){
        $suc="";
    }else{
        $suc="and ot.suc='$sucursal'";
    }

    $munconsultorio = $_REQUEST[munconsultorio];

    $locconsultorio = $_REQUEST[locconsultorio];

    $estado = $_REQUEST[estado];

    $munconsultorio=base64_decode($munconsultorio);

    $locconsultorio=base64_decode($locconsultorio);

    $estado=base64_decode($estado);

    if($Resumen == 'Resumen'){

        $OtA = mysql_query("SELECT med.medico, med.nombrec,med.estado, med.munconsultorio, med.locconsultorio, ot.orden, ot.importe
         FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico and med.munconsultorio='$munconsultorio' and med.estado='$estado' $suc order by med.medico");
    }else{

        $OtA = mysql_query("SELECT med.medico, med.nombrec,med.estado, med.munconsultorio, med.locconsultorio, ot.orden, ot.importe
         FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico and med.munconsultorio='$munconsultorio' and med.locconsultorio='$locconsultorio' and med.estado='$estado' $suc order by med.medico");
    }

  require ("config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Detalle de Medicos por Entidad ::.</title>
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
</head>

<?php 
    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

echo '<body topmargin="1">';

echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
echo "<tr class='letrap' bgcolor='$Gbgsubtitulo' align='center'>";
echo "<td align='center'><b></b></td>";
echo "<td align='center'><b>Clave</b></td>";
echo "<td align='center'><b>Medico</b></td>";
echo "<td align='center'><b>Ordenes</b></td>";
echo "<td align='center'><b>Importe</b></td>";
echo "</tr>";   
$nRng=1;     

    while($registro=mysql_fetch_array($OtA)){

            if (($nRng % 2) > 0) {
                $Fdo = 'FFFFFF';
            } else {
                $Fdo = $Gfdogrid;
            }
            
            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td>$Gfont".$nRng."</td>";
            echo "<td>$Gfont".$registro[medico]."</td>";
            echo "<td>$Gfont". $registro[nombrec]."</td>";
            echo "<td align='center'>$Gfont".$registro[orden]."</td>";
            echo "<td align='right'>$Gfont".number_format($registro[importe],"2")."</td>";
            echo "</tr>";
            $nRng++;
            $importet += $registro[importe];
    }

            echo "<tr class='letrap' bgcolor='$Gbgsubtitulo' align='center'>";
            echo "<td align='center'><b></b></td>";
            echo "<td align='center'><b></b></td>";
            echo "<td align='center'><b></b></td>";
            echo "<td align='right'><b>Total: </b></td>";
            echo "<td align='right'>$Gfont <b>$ ".number_format($importet,"2")."</b></td>";
            echo "</tr>"; 
            echo "</table>";
    echo "<br><a class='letra' href='javascript:window.close()'><img src='lib/regresa.png'>  Regresar </a><br>";

  mysql_close();

  ?>

</body>

</html>