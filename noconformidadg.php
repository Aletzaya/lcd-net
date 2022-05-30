<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Usr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

require './Services/CapturaResultadoService.php';

require ("config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Informe de No conformidades ::.</title>
<link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
</head>
<body topmargin="1">
  
    <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
        <tr>
            <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informe de No conformidades de Orden No. <?= $He["institucion"] ?> - <?= $busca ?> ::..
            </td>
        </tr>
        <tr>
            <td valign='top' align='center' height='130' width='100%'>
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 1px solid #999;'>  
                    <tr style="background-color: #2c8e3c">
                        <td class='letratitulo' align="center" colspan="5">
                            ..:: Datos principales ::..
                        </td>
                    </tr>
                    <?php
                    $nomcliente = ucwords(strtolower(substr($Cpo[nombrecli],0,50)));
                    ?>
                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm">
                            <font size="4"><strong><?= $nomcliente ?></strong></font>
                        </td>
                    </tr>
                    <tr style="height: 30px" class="letrap">
                        <td width='28%' align="lefth" class="ssbm">
                            Fecha: <?= $Cpo[fecha] ?> Hra: <?= $Cpo[hora] ?> Fecha de entrega: <?= $Cpo[fechae] ?> &nbsp; <b>No.ORDEN: <?= $Cpo[institucion] ?> - <?= $busca ?>
                        </td>
                    </tr>
                </table>
                <br>
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>

                    <tr bgcolor="#2c8e3c">
                        <td class='letratitulo' align="center" colspan="10">..:: Detalle de Estudios ::..</td>
                    </tr>
                    <tr style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                        <td class="letrap" align="center">
                            <strong>Estudio</strong>
                        </td>
                        <td class="letrap" align="center">
                            <strong>Descripcion</strong>
                        </td>
                        <td class="letrap" colspan="2" align="center">
                            <strong>No Conformidad</strong>
                        </td>
                    </tr>  

                    <?php
                        $Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja,otd.noconformidad,est.depto FROM otd,est WHERE otd.orden='$busca' AND otd.estudio=est.estudio");
                    
                    while($rg=mysql_fetch_array($Sql)){

                      if (($nRng % 2) > 0) { $Fdo = 'FFFFFF';  } else { $Fdo = $Gfdogrid; } 

                    ?>
                      <tr bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='<?=$Gbarra?>';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>
                        <td class="letrap"><?= $rg[estudio] ?></td>
                        <td class="letrap"><?= $rg[descripcion] ?></td>
                        <td class="letrap"><?= $rg[noconformidad] ?></td>
                      </tr>
                      
                    <?php
                      $nRng++;
                    } 

                    ?>

                </table>

            </td>
        </tr>
    </table> 

</body>

</html>

<?php
mysql_close();
?>
 