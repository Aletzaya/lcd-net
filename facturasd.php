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

$CpoA = mysql_query("SELECT fc.fecha,fc.cliente,fc.cantidad,fc.iva,fc.importe,fc.status,
            clif.rfc,clif.nombre,fc.uuid,clif.correo,fc.usr,fc.observaciones,clif.correoadm
  	    FROM clif,fc WHERE fc.id='$busca' AND fc.cliente=clif.id");

$Cpo = mysql_fetch_array($CpoA);

$Correoadm=$Cpo[correoadm];

$Pdf = "fae/archivos/" . $Cpo[uuid] . ".pdf";

$Xml = "fae/archivos/" . $Cpo[uuid] . ".xml";
if ($busca == 'NUEVO') {

    header("Location: clientesf.php?pagina=0&Sort=Asc&orden=clif.id&busca=");
} elseif ($op == 9) {


    $SmtpA = mysql_query("SELECT * FROM smtp WHERE smtpvalido = 2 ORDER BY id");

    $Smtp = mysql_fetch_array($SmtpA);

    try {
        //*******************Envio de correo;
        //		Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        //Tell PHPMailer to use SMTP
        $mail->IsSMTP();
        //Enable SMTP debugging
        //0 = off (for production use)
        //1 = client messages
        //2 = client and server messages
        $mail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'error_log';

        //Set the hostname of the mail server
        $mail->Host = $Smtp[smtpname];

        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = $Smtp[smtpport];

        //Whether to use SMTP authentication
        //$mail->SMTPSecure = 'ssl';

        $mail->SMTPAuth = true;

        error_log("********************************************\n" . $Smtp[smtpuser] . "::" . $Smtp[smtploginpass] . "\n");
        //Username to use for SMTP authentication
        $mail->Username = $Smtp[smtpuser];

        //Password to use for SMTP authentication
        $mail->Password = $Smtp[smtploginpass];

        //Set the subject line
        $mail->Subject = "Factura electronica";


        $mail->Body = "Estimado cliente, le estamos enviando por este medio la factura electronica y al mismo tiempo nos reiteramos a sus ordenes para cualquier aclaracion al respecto, gracias por su preferencia.<br> ";

        //Replace the plain text body with one created manually
        $mail->AltBody = 'Envio de cfdi';

        //Set who the message is to be sent from
        //$mail->SetFrom('facturacion@detisa.com.mx', 'detisa.com.mx');
        $mail->SetFrom('atencionaclientes@dulab.com.mx', 'Laboratorio Clinico Duran, factura electronica');

        //Set who the message is to be sent to
        $mail->AddAddress($_REQUEST[Correo], 'Servicios');

        if($Correoadm<>''){
            $mail->AddAddress($Correoadm, 'Administracion');
        }


        /*         * *************************************
         */



        $sql = "SELECT 
                  pdf_format, cfdi_xml, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@nombre') name32, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@Nombre') name33, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@rfc') rfc32, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@Rfc') rfc33, 
                  uuid FROM facturas WHERE id_fc_fk = $busca ";
        $result = mysql_query($sql);
        $myrowsel = mysql_fetch_array($result);
        $receptor = $myrowsel['name'] . " (" . $myrowsel['rfc'] . ")";
        // Read attachments
        $mail->AddStringAttachment($myrowsel['pdf_format'], $Cpo['uuid'] . ".pdf", "base64", "application/pdf");
        $mail->AddStringAttachment($myrowsel['cfdi_xml'], $Cpo['uuid'] . ".xml", "base64", "application/xml");

        $mail->ContentType = 'multipart/mixed';
        //Set the subject line
        $mail->Subject = "Envío de Factura Electrónica " . $Cpo['uuid'];

        //Send the message, check for errors
        if (!$mail->Send()) {

            $Msj = "Mailer Error: " . $mail->ErrorInfo;
        } else {

            $Msj = "..::Sus archivos Xml y Pdf han sido enviados con exito::..";
            $Status = 1;
        }
    } catch (phpmailerException $e) {
        error_log($e->errorMessage());
        $Msj = $e->errorMessage();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $Msj = $e->getMessage();
    }

    header("Location: facturasd.php?Msj=$Msj&busca=$busca&Status=$Status");
} elseif ($_REQUEST[Boton] == 'Agregar') {

    $CiaA = mysql_query("SELECT iva FROM cia LIMIT 1");
    $Cia = mysql_fetch_array($CiaA);

    $result = mysql_query("SELECT precio,descuento,estudio FROM otd WHERE orden='$_REQUEST[Orden]'");
    while ($rg = mysql_fetch_array($result)) {

        $Precio = round($rg[precio] * (1 - ($rg[descuento] / 100)), 2);

        $PrecioU = round($Precio / (1 + ($Cia[iva] / 100)), 2);

        $lUp = mysql_query("INSERT INTO fcd 
                       (id,estudio,precio,descuento,orden,iva,cantidad,importe)
                       VALUES 
                       ('$busca','$rg[estudio]','$PrecioU','0','$_REQUEST[Orden]','$Cia[iva]','1','$Precio')");
    }
    $Status = 1;
    Totaliza($busca);
    $Msj = "Registros ingresados con exito!";
    header("Location: facturasd.php?Msj=$Msj&busca=$busca&Status=$Status");
}



$Titulo = "Edita factura [$busca]";


$lBd = false;

require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>.:: Envio Factura ::.</title>
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

        <script language="JavaScript1.2">

            $(document).ready(function () {
                var status = <?= $StatusMensaje ?>;
                var msj = "<?= $Msj ?>";
                if (status = 1) {
                    Swal.fire({
                        title: "Exito",
                        text: msj,
                        position: "top-right",
                        icon: "success",
                        timer: 3500,
                        timerProgressBar: true,
                        toast: true
                    })
                }
            });
            function cFocus() {
                document.form1.Nombre.focus();
            }
            function SiElimina() {
                if (confirm("ATENCION! Desea dar de Baja este registro?")) {
                    return(true);
                } else {
                    document.form1.busca.value = "NUEVO";
                    return(false);
                }
            }


            function Completo() {
                var lRt;
                lRt = true;
                if (document.form1.Nombre.value == "") {
                    lRt = false;
                }
                if (!lRt) {
                    alert("Faltan datos por llenar, favor de verificar");
                    return false;
                }
                return true;
            }

            function Mayusculas(cCampo) {
                if (cCampo == 'Nombre') {
                    document.form1.Nombre.value = document.form1.Nombre.value.toUpperCase();
                }
            }
        </script>


        <table width="100%" border="0">
            <tr><td align="center" colspan="2">
                    <table width='99%' bgcolor="#2c8e3c" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr><td width="15%" align="center">
                                <img src="lib/DuranNvoBk.png" width="230" height="90"></img>
                            </td><td valign="bottom" align="center"><h2 style="color: #ffffff;">Envio de factura No. # <?= $busca ?> </h2></td>
                            <td valign="bottom"><h2 style="color: #ffffff;">Status : <?= $Cpo[status] ?></h2></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width='50%' valign="top">
                    <table width='98%' bgcolor="#fff" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr height='25px' bgcolor='#2c8e3c'><td colspan="2" align='center' class='letratitulo'>Informacion Principal</td></tr>
                        <tr height='25px'><td align='right'><strong>ID: </strong></td><td><?= $busca ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Fecha: </strong></td><td><?= $Cpo[fecha] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Cliente: </strong></td><td><?= $Cpo[cliente] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Cantidad: </strong></td><td><?= $Cpo[cantidad] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Iva: </strong></td><td><?= $Cpo[iva] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Importe: </strong></td><td><?= $Cpo[importe] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Cliente: </strong></td><td><?= $Cpo[nombre] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Rfc: </strong></td><td><?= $Cpo[rfc] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Folio fiscal: </strong></td><td><?= $Cpo[uuid] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Observaciones: </strong></td><td><?= $Cpo[observaciones] ?></td></tr>
                        <tr height='25px'><td align='right'><strong>Usr: </strong></td><td><?= $Cpo[usr] ?></td></tr>
                        <tr height='25px' bgcolor='#D5D8DC'><td align='right'><strong>Para su verificacion fisca: </td><td>https://verificacfdi.facturaelectronica.sat.gob.mx</td></tr>
                    </table>
                </td>
                <td align='center' valign="top">
                    <?php
                    $cSqlD = "SELECT fcd.orden,fcd.estudio,est.descripcion,fcd.precio,fcd.descuento,fcd.idnvo,fcd.cantidad
                        FROM fcd,est
                        WHERE fcd.estudio=est.estudio AND fcd.id='$busca'";
                    $resutl = mysql_query($cSqlD);
                    ?>
                    <table width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr bgcolor="#2c8e3c" height='25px'>
                            <td align='center' class='letratitulo'>Orden</td>
                            <td align='center' class='letratitulo'>Estudio</td>
                            <td align='center' class='letratitulo'>Descripción</td>
                            <td align='center' class='letratitulo'>Cnt</td>
                            <td align='center' class='letratitulo'>Precio</td>
                            <td align='center' class='letratitulo'>%Dto</td>
                            <td align='center' class='letratitulo'>Importe</td>
                        </tr>
                        <?php
                        while ($rst = mysql_fetch_array($resutl)) {
                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = 'EBEDEF';
                            }
                            ?>
                            <tr bgcolor="#<?= $Fdo ?>" height='25px'>
                                <td align='center'><?= $rst[orden] ?></td>
                                <td align='center'><?= $rst[estudio] ?></td>
                                <td align='center'><?= $rst[descripcion] ?></td>
                                <td align='center'><?= $rst[cantidad] ?></td>
                                <td align='center'><?= $rst[precio] ?></td>
                                <td align='center'><?= $rst[descuento] ?></td>
                                <td align='center'><?= $rst[precio] ?></td>
                            </tr> 
                            <?php
                            $nRng++;
                        }
                        ?>
                    </table>
                    <?php
                    if ($Cpo[status] === "Abierta") {
                        ?>
                        <br></br>
                        <table bgcolor="#fff" width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr bgcolor="#2c8e3c">
                                <td height="25px" class='letratitulo' align="center">Agrega por orden de trabajo </td>
                            </tr>
                            <tr height='25px'>
                                <td align="center" height="45px" class="letrap">
                                    <form  name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                                        No. de Orden
                                        <input class='Input' type='text' name='Orden' value='' size='6'></input>
                                        <input class='letrap' type='submit' name='Boton' value='Agregar'></input>
                                        <input type='hidden' name='busca' value='<?= $busca ?>'/>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <?php
                    }
                    ?>
                    <br></br>
                    <table bgcolor="#fff" width='95%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr bgcolor="#2c8e3c">
                            <td height="25px" class='letratitulo' align="center"> Ingrese el email para enviar PDF y XML <i class="fa fa-envelope fa-bg" aria-hidden="true" style="color:#ffffff"></i></td>
                        </tr>
                        <tr height='25px'>
                            <td align="center" height="45px">
                                <form  name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                                    <input class='letrap' type='text' name='Correo' value='<?= $Cpo[correo] ?>' size='30' class='texto_tablas' onBlur='ValidaCorreo(this.value)'/>
                                    <input type='submit' class='letrap' name='Boton' value='Enviar correo' class='Botones'/>
                                    <input type='hidden' name='busca' value='<?= $busca ?>'/>
                                    <input type='hidden' name='op' value='9'/>
                                </form>
                            </td>
                        </tr>
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
        $Total = $Importe+$Iva;
        //$Ddd[ImporteTotal];
    }

    //$nImporte = $Total - ($Iva + $Ieps);	//Con esto lo obligo a que me cuadre;
    $lUp = mysql_query("UPDATE fc SET cantidad=$Cnt,importe = $Importe, iva=$Iva, total= $Total WHERE id=$busca");
}
mysql_close();
?>
