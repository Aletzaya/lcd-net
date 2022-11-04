<?php
set_time_limit(720);

session_start();

require ("config.php");          //Parametros de colores;
require ("lib/lib.php");

require ("CFDIComboBoxes.php");
require_once 'class.phpmailer.php';

require_once ('./Services/TimbradoService.php');
require_once ('cfdi/com/softcoatl/cfdi/ComprobanteResolver.php');
require_once ('cfdi/com/softcoatl/cfdi/SelloCFDI.php');
require_once ('cfdi/com/softcoatl/security/commons/SATCertificates.php');
require_once ('cfdi/com/softcoatl/cfdi/utils/pac/PACServiceFactory.php');
require_once ('cfdi/com/softcoatl/cfdi/utils/pac/PACFactory.php');
require_once ('cfdi/com/softcoatl/cfdi/utils/pac/PAC.php');
require_once ('cfdi/com/softcoatl/cfdi/v40/schema/Comprobante40.php');
//require_once ('cfdi/com/softcoatl/cfdi/ComprobanteResolver.php');

require_once ('data/CiaDAO.php');
require_once ('data/ClientesDAO.php');
require_once ('data/FcDAO.php');
require_once ('data/ProveedorPACDAO.php');
require_once ('data/FacturaDetisa.php');
require_once ('data/FacturaLcd.php');

require_once ("lib/PDFGenerator.php");

use com\softcoatl\cfdi\v40\schema\Comprobante40;

Comprobante40::registerComplemento("com\\softcoatl\\cfdi\\complemento\\tfd\\TimbreFiscalDigital");
Comprobante40::registerAddenda("com\\softcoatl\\cfdi\\addenda\\detisa\\Observaciones");

use com\softcoatl\cfdi\ComprobanteResolver;

$queryParameters = array();
foreach ($_REQUEST as $key => $value) {
    $queryParameters[$key] = $value;
}

if (!empty($queryParameters['busca'])) {
    $_SESSION['cVarVal'] = $queryParameters['busca'];
}

if (!empty($queryParameters['Metododepago'])) {
    $_SESSION['cMDP'] = $queryParameters['Metododepago'];
}

$ciaDAO = new CiaDAO();
$clientesDAO = new ClientesDAO();
$fcDAO = new FcDAO();
$pacDAO = new ProveedorPACDAO();

$ppac = $pacDAO->getActive();
//$pac = com\softcoatl\cfdi\v33\PACFactory::getPAC($ppac->getUrl_webservice(), $ppac->getUsuario(), $ppac->getPassword(), $ppac->getClave_pac());
$pac = com\softcoatl\cfdi\utils\pac\PACFactory::getPAC($ppac->getUrl(), $ppac->getUser(), $ppac->getPassword(), $ppac->getPac());
if ($pac instanceof com\softcoatl\cfdi\v33\SifeiPAC) {
    $pac->setIdEquipo($ppac->getClave_aux());
    $pac->setSerie("");
}

$busca = $_SESSION['cVarVal']; // ID de FC
$sucursal = $_SESSION['cVar'];

$cia = $ciaDAO->retrieveFields($sucursal, "facclavesat, clavesat, facturacion");
$fcVO = $fcDAO->retrieve($busca);
$clienteVO = $clientesDAO->retrieve($fcVO->getCliente());

$link = conectarse();
$Titulo = "Favor de confirmar sus datos";

$lBd = false;
$Msj = $queryParameters['Msj'];

$_SESSION['cVar'] = 0;
$lBd = true;

if ($queryParameters['Boton'] == 'Guardar estos cambios') {

    $cSql = "UPDATE fc JOIN clif ON fc.cliente = clif.id SET fc.usocfdi = '$_REQUEST[Usocfdi]', fc.formadepago='$_REQUEST[Formadepago]',
             fc.metododepago = '$_REQUEST[Metododepago]', fc.observaciones = '$_REQUEST[Observaciones]', clif.enviarcorreo='$_REQUEST[Enviarcorreo]',
             clif.municipio='$_REQUEST[Municipio]', clif.codigo='$_REQUEST[Codigo]',clif.correo = '$_REQUEST[Correo]'    
             WHERE fc.id='$busca'";
    if (!mysql_query($cSql)) {
        $Archivo = 'FC';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    } else {
        $Msj = "Cliente facturador actualizado con Exito!";
        header("Location: genfactura.php?busca=$_REQUEST[busca]&Msj=$Msj");
    }
} elseif ($_REQUEST["Opcion"] === 'Genera') {

    if ($_REQUEST["Certificados"] === "LCD") {
        mysql_query("UPDATE cia SET facturacion='Si' WHERE id=1");
        mysql_query("UPDATE cia SET facturacion='No' WHERE id=100");
    } else {
        mysql_query("UPDATE cia SET facturacion='No' WHERE id=1");
        mysql_query("UPDATE cia SET facturacion='Si' WHERE id=100");
    }

    /*     * ********************************************************************************************************************************************************* */
    //$facturaDetisa = new com\detisa\omicrom\FacturaDetisa($busca);
    $facturaDetisa = new com\detisa\omicrom\FacturaLcd($busca);

    //$facturaDetisa->save("/home/omicrom/xml/prbLcd.xml");
    if (count($facturaDetisa->getComprobante()->getConceptos()->getConcepto()) == 0) {
        die('<div align="center"><p>&nbsp;</p><font color="#99000">Error critico</font><b></b><br>El comprobante no tiene conceptos, no es posible timbrar un comprobante sin conceptos. Favor de verificar.<br><b>' . mysql_error() . '</b><br> favor de dar click en la flecha &nbsp <a class=nombre_cliente href=' . $_SERVER["PHP_SELF"] . '><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

    if ($_REQUEST["Certificados"] === "LCD") {
        $keyfile = "certificado/key.pem";
        $cerfile = "certificado/cer.pem";
    } else {
        $keyfile = "certificado2/key.pem";
        $cerfile = "certificado2/cer.pem";
    }
    
    if (count($facturaDetisa->getComprobante()->getConceptos()->getConcepto()) == 0) {
        $Msj = "El comprobante no tiene conceptos, no es posible timbrar un comprobante sin conceptos. Favor de verificar";
    } else if (!file_exists($keyfile) || !file_exists($cerfile)) {
        $Msj = "No se encontraron los certificados. Favor de notificar a Soporte";
    } else {
        $showMissingCertificates = false;
        if (file_exists($keyfile) && file_exists($cerfile)) {
            $csd = new com\softcoatl\security\commons\Certificate(file_get_contents($cerfile), file_get_contents($keyfile), $cia->getFacclavesat());
            $cer = $csd->getPEMCertificate();
            $key = $csd->getPEMPrivateKey();
            if (empty($cer) || empty($key)) {
                $showMissingCertificates = true;
            } else {
                $now = new DateTime();
                $vigencyDays = $now->diff($csd->validTo())->format('%R%a');
            }

            if (!$csd->isValid()) {
                $Msj = "Los certificados no están vigentes. Favor de notificar a Soporte";
            } else {
                $service = new com\detifac\services\TimbradoService();
                $xmlCFDI = $service->doin($facturaDetisa->getComprobante(), $csd);
                $Msj = $service->getError();
                $facturaDetisa->getComprobante()->asXML()->save("/home/omicrom/xml/prbCP.xml");
                try {
                    if ($xmlCFDI) {
                        $comprobanteTimbrado = (new ComprobanteResolver())->resolve($xmlCFDI);
                        $facturaDetisa->setComprobanteTimbrado($comprobanteTimbrado);

                        $facturaDetisa->setXmlTimbrado($xmlCFDI);
                        $facturaDetisa->update($busca);
                        $facturaDetisa->save("SIFEI");
                        $pdfCFDI = PDFGenerator::generate($comprobanteTimbrado, $Tipo, $Usr);
                        $facturaDetisa->setRepresentacionImpresa($pdfCFDI);
                        $Msj = "Folio timbrado con UUID " . $comprobanteTimbrado->getTimbreFiscalDigital()->getUUID();
                        // Almacena el XML y su representación impresa en disco duro
                        file_put_contents('archivos/' . $comprobanteTimbrado->getTimbreFiscalDigital()->getUUID() . '.xml', $xmlCFDI);
                        file_put_contents('archivos/' . $comprobanteTimbrado->getTimbreFiscalDigital()->getUUID() . '.pdf', $pdfCFDI);
                    }
                    header("Location: facturas40.php?busca=ini&Msj=$Msj");
                } catch (Exception $e) {
                    print_r($e->getMessage());
                    $Msj = "Error : " . $e->getMessage();
                }
            }
        }
    }
    header("Location: facturas40.php?busca=ini&Msj=$Msj");
    /*     * ********************************************************************************************************************************************************* */
}

$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta charset="UTF-8">

        <title>Genera Factura</title>
        <?php require ("config_add.php"); ?>
    </head>
    <body leftmargin='<?= $MagenIzq ?>' topmargin='<?= $MargenAlt ?>' marginwidth='<?= $MargenIzq ?>' marginheight='<?= $MargenAlt ?>'>
        <?php
        encabezados();
        menu($Titulo, $Gusr);
        ?>
        <br>
        <table width='100%' border='0' align='center' cellpadding='2' cellspacing='0' class='textos'>
            <tr>
                <td align='center'>
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>

                        <?php
                        $HeA = mysql_query("SELECT fc.id,fc.cliente,clif.nombre,clif.direccion,clif.rfc,clif.codigo,
         clif.correo,fc.fecha,clif.enviarcorreo,fc.usocfdi,clif.municipio,fc.total,fc.iva,
         fc.formadepago,fc.importe,fc.observaciones,fc.metododepago
         FROM fc LEFT JOIN clif ON fc.cliente=clif.id
         WHERE fc.id='$busca'");

                        $He = mysql_fetch_array($HeA);
//$Nom = utf8_encode($He[nombre]);
                        ?>  
                        <table bgcolor="#D5D8DC" width='60%' border='0' align='center' cellpadding='0' cellspacing='0' class='letrap' style='border:#566573 1px solid;'>
                            <tr>
                                <td colspan="2" align="center" bgcolor="#E59866"><h2>Verificar datos del cliente</h2></td>
                            </tr>
                            <tr style="height: 25px;">
                                <td align='right' width='30%'><small style="color:#FF0000";>*</small> Nombre: </td>
                                <td width='50%'>
                                    <input type='text' name='Nombre' value='<?= $Nom ?>' class='letrap' size='65' onBLur=Mayusculas('Nombre'); disabled>
                                    <strong>No. <?= $He[cliente] ?></strong>
                                </td>
                            </tr>
                            <tr style="height: 25px;">  
                                <td align='right'><small style="color:#FF0000";>*</small> Rfc: </td>
                                <td>
                                    <input type='text' name='Rfc' value='<?= $He[rfc] ?>' class='letrap' size='20' maxlength='13' onBlur='ValidaRfc(this.value)' required>
                                </td>
                            </tr>
                            <tr style="height: 25px;">
                                <td align='right'>Municipio: </td>
                                <td>
                                    <input type='text' name='Municipio' value='<?= $He[municipio] ?>' class='letrap' size='30' onBLur=Mayusculas('Municipio')> 
                                    Cod.postal: 
                                    <input type='text' name='Codigo' value='<?= $He[codigo] ?>' class='letrap' size='5' onBLur=Mayusculas('Codigo')>
                            <tr style="height: 25px;">
                                <td align="right"><small style="color:#FF0000";>*</small> Uso de CFDI: </td><td>
                                    <?php
                                    echo "<select class='letrap' name='Usocfdi'>";
                                    $UsoA = mysql_query("SELECT clave,descripcion FROM cfdi33_c_uso ORDER BY clave");
                                    while ($rg = mysql_fetch_array($UsoA)) {
                                        echo "<option value='$rg[clave]'>" . $rg[clave] . " | " . $rg[descripcion] . "</option>";
                                        if ($He[usocfdi] == $rg[clave]) {
                                            $Display = $rg[descripcion];
                                        }
                                    }
                                    echo "<option value='$He[usocfdi]' selected>$Display</option>";
                                    echo "</select>";
                                    ?>                        
                                </td>
                            </tr>                        
                            <!--<tr>
                                <td align="right" bgcolor="#e1e1e1">CFDI relacionado: </td>
                                <td align="left">
                                    <input type="text" class="letrap" name="Relacioncfdi" id="Relacioncfdi" class="texto_tablas" size="10"/>
                                    En caso de ser necesario</small>
                            <?php //ComboboxTipoRelacion::generate('tiporelacion');       ?>
                                </td>
                            </tr> -->
                            <tr style="height: 25px;">
                                <td align="right"><small style="color:#FF0000";>*</small> M&eacute;todo de pago: &nbsp;</td>
                                <td align="left">
                                    <?php
                                    echo "<select class='letrap' name='Metododepago'>";
                                    $MpagoA = mysql_query("SELECT clave,descripcion FROM cfdi33_c_mpago ORDER BY clave");
                                    while ($rg = mysql_fetch_array($MpagoA)) {
                                        echo "<option value='$rg[clave]'>" . $rg[clave] . " | " . $rg[descripcion] . "</option>";
                                        if ($He[metododepago] == $rg[clave]) {
                                            $Display = $rg[descripcion];
                                        }
                                    }
                                    echo "<option value='$He[metododepago]' selected>$Display</option>";
                                    echo "</select> &nbsp ";
                                    ?>
                                </td>
                            </tr>
                            <tr style="height: 25px;">
                                <td align='right'><small style="color:#FF0000";>*</small> Forma de pago:</td><td>
                                    <select class='letrap' name='Formadepago'>
                                        <?php
                                        $Pagos = mysql_query("SELECT * FROM cpagos ORDER BY clave");
                                        while ($rg = mysql_fetch_array($Pagos)) {
                                            echo "<option value='$rg[clave]'>" . $rg[clave] . " | " . $rg[concepto] . "</option>";
                                            if ($He[formadepago] == $rg[clave]) {
                                                $Display = $rg[concepto];
                                            }
                                        }
                                        ?>
                                        <option value='<?= $He[formadepago] ?>' selected><?= $Display ?></option>
                                    </select>
                                    Observaciones: 
                                    <input type='text' name='Observaciones' value='<?= $He[observaciones] ?>' class='letrap' size='20'>
                                </td></tr>

                            <tr style="height: 25px;"><td align='right'>Correo electronico: &nbsp;</td><td>
                                    <input type='text' name='Correo' value='<?= $He[correo] ?>' class='letrap' size='50'> &nbsp; enviar correo
                                    <?php
                                    if ($He[enviarcorreo] == 'Si') {
                                        echo "<input type='checkbox' class='botonAnimated' name='Enviarcorreo' value='Si' checked>";
                                    } else {
                                        echo "<input type='checkbox'  class='botonAnimated' name='Enviarcorreo' value='Si'>";
                                    }
                                    ?>
                                </td></tr>
                            <tr style="height: 25px;">
                                <td align='center' colspan="2">
                                    <input type='submit' style='background:#618fa9; color:#ffffff;font-weight:bold;' name='Boton' value='Guardar estos cambios'>
                                </td>
                            </tr>
                        </table>
                        <input type='hidden' name='op' value='ag'>
                        <input type='hidden' name='Cliente' value='<?= $He[cliente] ?>'>
                        <table width="100%"><tr><td align="right"><a href='facturas.php' class='content5' ><i class='fa fa-reply fa-2x' aria-hidden='true'></i> Regresar </a></td></tr></table>
                    </form>

                    <?php
                    echo "<table width='90%' border='0' align='center' cellpadding='3' cellspacing='0' class='letrap' style='border:#566573 1px solid; border-radius: .1em;'>";
                    echo "<tr bgcolor='#7DCEA0'><td align='center' colspan='7'><h1 class='letrap'>PRODUCTOS A FACTURAR</h1></td></tr>";
                    echo "<tr bgcolor='#45B39D'>";
                    echo "<th>Producto</th>";
                    echo "<th>Descripcion</th>";
                    echo "<th>Cantidad</th>";
                    echo "<th>Precio</th>";
                    echo "<th>Descuento</th>";
                    echo "<th>Importe</th>";
                    echo "<th>Total</th>";
                    echo "</tr>";

                    $CpoA = mysql_query("SELECT fcd.estudio,est.descripcion,fcd.precio,fcd.iva,
                                 fcd.importe,fcd.descuento,fcd.cantidad
                                 FROM fcd LEFT JOIN est ON fcd.estudio=est.estudio
                                 WHERE fcd.id='$busca' ORDER BY fcd.idnvo");
                    while ($rg = mysql_fetch_array($CpoA)) {
                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;

                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                        echo "<td align='left'> $rg[estudio] </td>";
                        echo "<td align='left'> $rg[descripcion] </td>";
                        echo "<td align='right'> $rg[cantidad] </td>";
                        echo "<td align='right'> " . number_format($rg[precio], "2") . " </td>";
                        echo "<td align='right'> " . number_format($rg[descuento], "2") . " </td>";
                        echo "<td align='right'> " . number_format($rg[cantidad] * ($rg[precio] * (1 - ($rg[descuento] / 100))), "2") . " </td>";
                        echo "<td align='right'> " . number_format(($rg[cantidad] * ($rg[precio] * (1 - ($rg[descuento] / 100)))) * (1 + ($rg["iva"] / 100)), "2") . " </td>";
                        echo "</tr>";
                        $sumPrecio += $rg["precio"];
                        $sumPrecioIva += $rg["precio"] * ($rg["iva"] / 100);
                        $sumConDescuento += $rg["cantidad"] * ($rg["precio"] * (1 - ($rg["descuento"] / 100)));
                        $sumTotal += ($rg["cantidad"] * ($rg["precio"] * (1 - ($rg["descuento"] / 100)))) * (1 + ($rg["iva"] / 100));
                        $nRng++;
                    }

                    echo "</table><br>";
                    ?>
                    <table width='90%' border='0' align='center' cellpadding='1' cellspacing='2' class='letrap' style='border:#566573 1px solid;'>
                        <tr bgcolor='#c1c1c1'>
                            <th align='right' width="70%">Sub-total $ <?= number_format($He[importe], "2") ?></th>
                            <th align='right'>Iva $ <?= number_format($He[iva], "2") ?></th>
                            <th align='right'>Total $ <?= number_format($He[total], "2") ?></th>
                        </tr>
                    </table>
                    <table width='90%' border='0' align='center' cellpadding='1' cellspacing='2' class='letrap' style='border:#566573 1px solid;'>
                        <tr bgcolor='#c1c1c1'>
                            <th align='right' width="70%">Dif $ <?= number_format($sumPrecio, "2") ?></th>
                            <th align='right'>Iva $ <?= number_format($sumPrecioIva, "2") ?></th>
                            <th align='right'>Total $ <?= number_format($sumTotal, "2") ?></th>
                        </tr>
                    </table>
                    <br>
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                        <input type='hidden' name='Correo' value='<?= $He[correo] ?>'>
                        <input type='hidden' name='Enviarcorreo' value='<?= $He[enviarcorreo] ?>'>
                        <input type="hidden" name="Opcion" value="Genera">
                        <p align='center'>
                            <input type='submit' style='background:#618fa9; color:#ffffff;font-weight:bold;' name='Certificados' value='LCD'>
                            <input type='submit' style='background:#618fa9; color:#ffffff;font-weight:bold;' name='Certificados' value='Diagnostico'>
                        </p>
                    </form>
                </td>
            </tr>
        </table>

        <?php
        CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .
        ?>
    </body>

</html>
<script src='./controladores.js'></script>
<?php
mysql_close();

function filetoStringB64($filePath) {

    $fd = fopen($filePath, 'rb');
    $size = filesize($filePath);
    $cont = fread($fd, $size);
    fclose($fd);
    return base64_encode($cont);
}
?>
