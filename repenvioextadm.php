<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
$link = conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$FecI = $_REQUEST["FecI"];

$FecF = $_REQUEST["FecF"];

$Fechai = $FecI;

$Fechaf = $FecF;

$HoraI = $_REQUEST["HoraI"];

$HoraF = $_REQUEST["HoraF"];

$Titulo = $_REQUEST["Titulo"];

$Generapago = $_REQUEST["generapago"];

$Fecha = date("Y-m-d");

$Hora = date("H:i");

$Fechai = date("Y-m-d H:i");
$SucursalD = $_REQUEST["SucursalDe"];

if ($SucursalD == "*") {
    $SucursalDe = "";
} else {
    $SucursalDe = "and ot.suc=$SucursalD";
}

$SucursalP = $_REQUEST["Proveedor"];

if ($SucursalP == "*") {
    $SucursalPara = "";
} else {
    $SucursalPara = "and maqdet.mext='$SucursalP'";
}

$personal = $_REQUEST["personal"];

if ($personal == "*") {
    $personale = "";
} else {
    $personale = "and maqdet.usrenvext='$personal'";
}

if ($Generapago == 'Si') {

    $Inst = 'FecI=' . $FecI . '&FecF=' . $FecF . '&HoraI=' . $HoraI . '&HoraF=' . $HoraF . '&SucursalDe=' . $SucursalD . '&Proveedor=' . $SucursalP . '&personal=' . $personal;

    $lUp = mysql_query("INSERT INTO generapago (instruccion,fecha,usr,cancel,proveedor)
      VALUES
      ('$Inst','$Fechai','$Usr','','$SucursalP')");
}

$Titulo = "Menu de inicio";
require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Mensajeria LCD-NET ::..</title>
            <script type='text/javascript' src='ckeditor/ckeditor.js'></script>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="30%" style="background-image:url(images/BarraVdeGrad.jpg)"><img src="lib/DuranNvoBk.png" width="230" height="90"></img></td>
                <td style="background-image:url(images/BarraVdeGrad.jpg)">
                    <h3 style="font-family: cursive;color: #2C3E50;" align="center" >Reporte de envios LCD-NET</h3>
                </td>
            </tr>
            <?php
            $cSql = "SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,maqdet.usrenvext,est.costo
FROM maqdet, ot, cli, est
WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.fenvext>='$FecI' and
maqdet.fenvext <='$FecF' AND maqdet.henvext >='$HoraI' AND maqdet.henvext <='$HoraF' $SucursalDe $SucursalPara $personale
order by maqdet.orden";

            $UpA = mysql_query($cSql, $link);

            echo "<br><table align='center' width='95%' border='0' class='letrap' cellspacing='1' cellpadding='0'>";
            echo "<tr bgcolor='#a2b2de' height='20'>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Suc</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Orden</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Paciente</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Estudios</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Descripcion</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Observaciones</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Para</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Usr</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Costo</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Precio</b></font></td>";
            echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Dif</b></font></td>";
            echo "</tr>";
            while ($rg = mysql_fetch_array($UpA)) {

                $MqlA2 = mysql_query("select * from mql where mql.id=$rg[mext]", $link);
                $Mql2 = mysql_fetch_array($MqlA2);

                if ($rg[costo] == 0) {
                    $costo = '';
                } else {
                    $costo = $rg[costo];
                }

                $Ord2 = mysql_query("select precio,descuento from otd where otd.orden=$rg[orden] and otd.estudio='$rg[estudio]'", $link);
                $Ord = mysql_fetch_array($Ord2);
                $precio = $Ord[precio] - (($Ord[precio] * $Ord[descuento]) / 100);

                if (($nRng % 2) > 0) {
                    $Fdo = 'FFFFFF';
                } else {
                    $Fdo = $Gfdogrid;
                }    //El resto de la division;

                if (($precio - $rg[costo]) <= 0) {
                    $color = 'red';
                } else {
                    $color = 'black';
                }

                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo'; height='20'>";
                echo "<td align='center'>$rg[suc]</td>";
                echo "<td align='center'>$rg[institucion] - $rg[orden]</td>";
                echo "<td align='left'>$rg[nombrec]</td>";
                echo "<td align='center'>$rg[estudio]</td>";
                echo "<td align='center'>$rg[descripcion]</td>";
                echo "<td align='center'>$rg[obsenv]</td>";
                echo "<td align='center'>$Mql2[alias]</td>";
                echo "<td align='center'>$rg[usrenvext]</td>";
                echo "<td align='right'>" . number_format($costo, '2') . "</td>";
                echo "<td align='right'>" . number_format($precio, '2') . "</td>";
                echo "<td align='right'>" . number_format($precio - $rg[costo], '2') . "</td>";
                echo "</tr>";
                $costoT += $rg[costo];
                $precioT += $precio;
                $difT += $precio - $rg[costo];
                $Estudios++;
                $nRng++;
            }
            echo "<tr bgcolor='#a2b2de' height='20'><td align='center' colspan='8'><font size='1' face='Arial, Helvetica, sans-serif'><b>No. de estudios: $Estudios</b></font></td><td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>" . number_format($costoT, '2') . "</b></font></td><td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>" . number_format($precioT, '2') . "</b></font></td><td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>" . number_format($difT, '2') . "</b></font></td></tr>";
            echo "</table>";

            echo "<div align='center'>";
            echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=28&fechas=1&FecI=$FecI&FecF=$FecF'>";
            echo "Regresar</a></font>";
            echo "</div>";
            echo "<div align='left'>";
            $FecI = $_REQUEST[FecI];
            $FecF = $_REQUEST[FecF];

            echo "<form name='form1' method='post' action='pidedatos.php?cRep=28&fechas=1&FecI=$FecI&FecF=$FecF'>";
            echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
            echo "</form>";
            echo "</div>";
            ?>


    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#Tabla2").hide();
            $("#Tabla3").hide();
            var i = "<?= $_REQUEST[busca] ?>";
            var op = "<?= $_REQUEST[op] ?>";
            if (i > 0) {
                $("#Tabla1").hide();
                $("#Tabla2").show();
            } else if (op == "NM") {
                $("#Tabla1").hide();
                $("#Tabla2").hide();
                $("#Tabla3").show();
            }

            CKEDITOR.replace('Nota');
            CKEDITOR.replace('MensajeNuevo');
        });
    </script>                 
    <script src="./controladores.js"></script>
</html>