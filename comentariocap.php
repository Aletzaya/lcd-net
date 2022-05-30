<?php
session_start();

date_default_timezone_set("America/Mexico_City");

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
$Fecha = ("Y-m-d h:m:s");

$cc = "SELECT obsest FROM otd WHERE orden = $_REQUEST[busca] AND estudio = '$_REQUEST[Est]'";
//echo $cc;
$Sql = mysql_query($cc);
$rs = mysql_fetch_array($Sql);

if ($_REQUEST[Boton] === "Actualiza") {

    $obs = $rs[obsest];

    $obs = $obs.' '.$_REQUEST[Observaciones];

    $Up = mysql_query("UPDATE otd SET obsest='$obs',usrobsest='$Gusr',fechobsest='$Fecha'
	            WHERE orden='$_REQUEST[busca]' and estudio='$_REQUEST[Est]'");

    AgregaBitacoraEventos2($Gusr, '/Toma de muestras/Modif. Obser. '.$Estudio, "otd", $Fechaest, $busca, $Msj, "capturaresultadod.php");

    $cc = "SELECT obsest FROM otd WHERE orden = $_REQUEST[busca] AND estudio = '$_REQUEST[Est]'";
    //echo $cc;
    $Sql = mysql_query($cc);
    $rs = mysql_fetch_array($Sql);

}

$cc2 = "SELECT descripcion FROM est WHERE estudio = '$_REQUEST[Est]'";
//echo $cc;
$Sql2 = mysql_query($cc2);
$rs2 = mysql_fetch_array($Sql2);
require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Observaciones por estudio ::..</title>
            <script type='text/javascript' src='ckeditor/ckeditor.js'></script>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <table class="letrap" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="background-image:url(images/BarraVdeGrad.jpg)"></td>
                <td style="background-image:url(images/BarraVdeGrad.jpg)">
                    <h3 style="font-family: cursive;color: #2C3E50;" align="center" > <?= $_REQUEST[Est]?> - <?= $rs2[descripcion]?></h3>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center"> 
                    <form name='form1' method='get' action='comentariocap.php'>
                        <br>
                        <strong>Observaciones : </strong><?= $rs["obsest"] ?>
                        <br>
                        <br>
                        <TEXTAREA NAME='Observaciones' class="letrap" cols='50' rows='3'></TEXTAREA>
                    <input type="hidden" name="busca" value="<?= $_REQUEST[busca] ?>"></input>
                    <input type="hidden" name="Est" value="<?= $_REQUEST[Est] ?>"></input>
                    <br>
                    <br>
                    <input type="submit" name="Boton" value="Actualiza"></input>
                    </form>
                </td>
            </tr>
        </table>
        <br>
    </body>
</html>
<?php
mysql_close();
?>
