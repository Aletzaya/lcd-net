<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];
setcookie("BuscaEquipo", $busca);
$cSql = "SELECT * FROM InvlXml";
$CpoA = mysql_query($cSql);
$Cpo = mysql_fetch_array($CpoA);

$cSql2 = "SELECT SUM(cantidad) scnt FROM InvlXmld";
$CpoA2 = mysql_query($cSql2);
$Cpo2 = mysql_fetch_array($CpoA2);
error_log("ENTRA");
if ($_REQUEST["op"] === "Ingresa") {
    $TT = $Cpo["Total"] - $Cpo["SubTotal"];
    $InsertInvXmld = "INSERT INTO el (fecha,hora,proveedor,concepto,documento,descuento,importe,iva,cantidad,status,depto,usr,almacen,"
            . "importepag,statuse,factrem) VALUES ('" . date("Y-m-d") . "','" . date("H:m") . "','0','Compras','" . $Cpo["folioXml"] . "'"
            . ",'0','" . $Cpo["Total"] . "','" . $TT . "','" . $Cpo2["scnt"] . "','ABIERTA','Laboratorio','" . $Cpo["usr"] . "',"
            . "'invgral','0.00','Pendiente','Factura');";
    if (mysql_query($InsertInvXmld)) {
        $Id = mysql_insert_id();
        $SelectIngreso = "select * FROM InvlXmld;";
        $Rst = mysql_query($SelectIngreso);
        while ($dtsr = mysql_fetch_array($Rst)) {
            $SDetalle = "SELECT * FROM invl WHERE id = (SELECT idInvl FROM invlClaves WHERE descripcion = '" . $dtsr["descripcion"] . "' LIMIT 1)";
            $cDetalle = mysql_query($SDetalle);
            $RstDetalle = mysql_fetch_array($cDetalle);
            echo "ENTRA";
            $InsertDetalle = "INSERT INTO eld  (id,idproducto,clave,cantidad,precio,costo,iva,civa) "
                    . "VALUES ('$Id','" . $dtsr["id_producto"] . "','" . $RstDetalle["clave"] . "','" . 
                    $dtsr["cantidad"] . "','" . ($dtsr["cantidad"] * $dtsr["valorUnitario"]) * 1.16 . "'"
                    . ",'" . $dtsr["valorUnitario"] . "','" . ($dtsr["cantidad"] * $dtsr["valorUnitario"]) * 0.16 . "','1');";
            echo $InsertDetalle;
            mysql_query($InsertDetalle);
        }
    }
    mysql_query("truncate InvlXmld;");
    mysql_query("truncate InvlXml;");
}

require ("config.php");          //Parametros de colores;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle de equipo</title>
            <?php require ("./config_add.php"); ?>
            <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
            <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
        <?php ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Captura de XML para el invantario ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='margin-top: 15px;border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="4">
                                    ..:: Guias del equipo ::..
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <?php
                                    $Slt = "SELECT * FROM InvlXml;";
                                    $Tlt = mysql_query($Slt);
                                    $rst = mysql_fetch_array($Tlt);
                                    ?>
                                    <table align="center" width="96%" style="margin: 2%;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr>
                                            <td class="letrap" colspan="6">
                                                Folio : <?= $rst["folioXml"] ?><br></br>
                                                Total : <?= $rst["Total"] ?><br></br>
                                                SubTotal : <?= $rst["SubTotal"] ?><br></br>
                                                Fecha : <?= $rst["fecha"] ?><br></br>
                                                Archivo : <?= $rst["nombreArchivo"] ?><br></br>
                                                Usr: <?= $rst["usr"] ?><br></br>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="margin: 8px;border-radius: 15px;">
                                        <tr bgcolor="#3498DB" class="letrap">
                                            <td><b>&nbsp; Id Producto</b></td>
                                            <td><b>&nbsp; Descripcion</b></td>
                                            <td><b>&nbsp; Cantidad</b></td>
                                            <td><b>&nbsp; Importe</b></td>
                                            <td><b>&nbsp; Valor Unitario</b></td>
                                            <td><b>&nbsp; Unidad</b></td>
                                        </tr>

                                        <?php
                                        $sql = "SELECT * FROM InvlXmld";
                                        $PgsA = mysql_query($sql);
                                        $Sts = true;
                                        while ($rg = mysql_fetch_array($PgsA)) {

                                            $Fdo = (($nRng % 2) > 0) ? "" : $Gfdogrid;
                                            $Color = $rg["id_producto"] == 9999 ? "#E6B0AA" : "#ABEBC6";
                                            if ($rg["id_producto"] == 9999) {
                                                $Sts = false;
                                            }
                                            ?>
                                            <tr style="height: 25px;" bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td align="right" bgcolor="<?= $Color ?>"><?= $rg["id_producto"] ?></td>
                                                <td><?= $rg["descripcion"] ?></td>
                                                <td><?= $rg["cantidad"] ?></td>
                                                <td align="right"><?= $rg["importe"] ?></td>
                                                <td align="right"><?= $rg["valorUnitario"] ?></td>
                                                <td><?= $rg["unidad"] ?></td>
                                            </tr>
                                            <?php
                                            $nRng++;
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        </form>
                    </table> 
                </td>
                <td valign='top' width='45%'>         
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='margin-top: 15px;border-collapse: collapse; border: 1px solid #999;'>  
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center">
                                ..:: Subir archivo XML ::..
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="padding: 15px;">
                                    <form class="dropzone" id="mydrop" action="XmlInvl.php">
                                        <div class="fallback">
                                            <input type="file" name="file" multiple id="file"></input>
                                        </div>
                                    </form>
                                </div>
                                <script type="text/javascript">
                                    var dropzone = new Dropzone("#file", {
                                        url: 'XmlInvl.php'
                                    });
                                </script>
                            </td>
                        </tr>
                    </table>
                    <?php
                    if ($Sts) {
                        ?>

                        <table style="border-radius: 15px;background-color: #58D68D;margin: 25px;width: 50%">
                            <tr height="50px;">
                                <td>
                                    <a href="subirXmlInvl.php?op=Ingresa" class="edit">Subir productos a Inventario de Laboratorio <i style="color: green;"class="fa fa-check-square" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </table>
                        <?php
                    }
                    ?>
                </td>
                <td valign='top' width="22%">
                    <a href="equipos.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
                </td>
            </tr>
        </table>

    </body>

</html>
