<?php
session_start();

require("lib/lib.php");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

$link = conectarse();

//Librerias necesarias en en la carpeta de OMICROM
require_once 'nusoap.php';
require_once 'class.phpmailer.php';
//NOTA: es necesario copiar la libreria class.smtp.php a la misma carpeta

$busca = $_REQUEST[busca];
$Msj = $_REQUEST[Msj];
$op = $_REQUEST[op];
$StatusMensaje = $_REQUEST[Status];

$CpoA = mysql_query("SELECT dpag_ref.id,dpag_ref.concept,dpag_ref.autoriza,dpag_ref.hospi,cptpagod.referencia,cptpagod.id idref,"
        . "cpagos.id idpagos,cpagos.clave,cpagos.concepto,dpag_ref.fecha,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.orden_h,"
        . "dpag_ref.tipopago,dpag_ref.cerrada,dpag_ref.fechapago,dpag_ref.recibe,dpag_ref.id_ref FROM dpag_ref "
        . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
        . "WHERE dpag_ref.id='$busca' ORDER BY id");

$Cpo = mysql_fetch_array($CpoA);

$Titulo = "Edita factura [$busca]";

$lBd = false;

require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>.:: Sistema LCD-NET ::.</title>
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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>

    <title>

        <?php echo $Titulo; ?>
    </title>

    <body onload='cFocus()'>
        <table width="100%" border="0">
            <tr><td align="center" colspan="2">
                    <table width='99%' bgcolor="#2c8e3c" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr><td width="15%" align="center">
                                <img src="lib/DuranNvoBk.png" width="230" height="90"></img>
                            </td><td valign="bottom" align="center"><h2 style="color: #ffffff;">Pago de <?= $Cpo["autoriza"] ?> No. <?= $busca ?></h2></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <table width='98%' bgcolor="#fff" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr height='25px' bgcolor='#2c8e3c'><td colspan="2" align='center' class='letratitulo' width="50%">Informacion Principal</td></tr>
                        <tr height='25px'><td align='right'><strong>Referencia del pago : </strong></td><td><?= $Cpo["referencia"] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Fecha de pago : </strong></td><td><?= $Cpo["fecha"] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Referencia a laboratorio : </strong></td><td><?= $Cpo["hospi"] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Recibe : </strong></td><td><?= $Cpo["recibe"] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Autoriza : </strong></td><td><?= $Cpo["autoriza"] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Pago: </strong></td><td><?= $Cpo["concepto"] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Importe: </strong></td><td><?= number_format($Cpo["monto"],2) ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Observaciones: </strong></td><td><?= $Cpo["observaciones"] ?></td></tr>
                        <tr height='65px' valign="bottom" bgcolor='#D5D8DC'>
                            <td align="center">___________________________ <br></br>Autoriza : <?= $Cpo["autoriza"] ?></td>
                            <td align="center">___________________________ <br></br>Recibe : <?= $Cpo["recibe"] ?></td>
                        </tr>
                        <tr><td colspan="2" align="right"><i style="border: 10px;" class='fa fa-print fa-2x' onClick="window.print()" aria-hidden='true'></i></td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
<?php

function Totaliza($busca) { //busca es idnvo de medt y cVarVal es id de la entrada;
    $CiaA = mysql_query("SELECT iva FROM cia LIMIT 1");
    $Cia = mysql_fetch_array($CiaA);

    $DddA = mysql_query("SELECT 
              round(sum(importe),2) as ImporteTotal,   
              round(sum(precio),2) as PrecioSinIva,
              sum(cantidad) as cantidad 
              FROM fcd WHERE id='$busca' and cantidad > 0");

    $Ddd = mysql_fetch_array($DddA);

    if ($Ddd[0] == 0) {
        $Cnt = 0;
        $Importe = 0;
        $Iva = 0;
        $Total = 0;
    } else {
        $Cnt = $Ddd[cantidad];
        $Importe = $Ddd[PrecioSinIva];
        $Iva = round($Ddd[PrecioSinIva] * ($Cia[iva] / 100), 2);
        $Total = $Ddd[ImporteTotal];
    }

    //$nImporte = $Total - ($Iva + $Ieps);	//Con esto lo obligo a que me cuadre;
    $lUp = mysql_query("UPDATE fc SET cantidad=$Cnt,importe = $Importe, iva=$Iva, total= $Total WHERE id=$busca");
}
mysql_close();
?>
