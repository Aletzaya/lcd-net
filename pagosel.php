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
$msj = $_REQUEST["Msj"];

#Variables comunes;
$CpoA = mysql_query("SELECT * FROM el WHERE id = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
if ($_REQUEST["Estado"] <> '') {
    $Estado = $_REQUEST["Estado"];
} else {
    $Estado = $Cpo["estado"];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Instituciones - General</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body>
        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <tr style="background-color: #2c8e3c">
                    <td class='letratitulo'align="center" colspan="3">
                        ..:: Detalle de pago ::..
                    </td>
                    <?php
                    $HeA = mysql_query("SELECT * from el where id='$busca'");

                    $He = mysql_fetch_array($HeA);
                    ?>
                </tr>
                <tr style="height: 30px" class="letrap">
                    <td align="center"><strong>Id Compra : </strong><?= $He["id"] ?></td>
                    <td align="center"><strong>Fecha Compra : </strong><?= $He["fecha"] . " " . $He["hora"] ?></td>
                    <td align="center"><strong>Proveedor : </strong><?= $He["proveedor"] ?></td>
                </tr>
                <tr style="height: 30px" class="letrap">
                    <td align="center"><strong>Concepto/Documento : </strong><?= $He["documento"] ?></td>
                    <td align="center"><strong>Cantidad/Importe : </strong><?= $He["importe"] ?></td>
                    <td align="center"><strong>Status Economico : </strong><?= $He["status"] ?></td>
                </tr>
                <tr style="height: 30px" class="letrap">
                    <td align="center"><strong>Importe pagado : </strong><?= $He["importepag"] ?></td>
                    <td align="center" colspan="2"><strong>Almacen : </strong><?= $He["almacen"] ?></td>
                </tr>
            </form>
        </table>
        <table width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
            <tr><td colspan="8" height="2px"></td></tr>
            <tr bgcolor="#5499C7" height='25px'>
                <td align='center' class='letratitulo'>Ed</td>
                <td align='center' class='letratitulo'>Id</td>
                <td align='center' class='letratitulo'>Fecha</td>
                <td align='center' class='letratitulo'>Importe</td>
                <td align='center' class='letratitulo'>Tipo de pago</td>
                <td align='center' class='letratitulo'>Documento</td>
                <td align='center' class='letratitulo'>Usuario</td>
                <td align='center' class='letratitulo'>Fecha de captura</td>
                <td align='center' class='letratitulo'>-</td>
            </tr>
            <?php
            $cSql = mysql_query("SELECT * FROM elpagos WHERE idcompra='$busca' order by id Desc");
            while ($rg = mysql_fetch_array($cSql)) {
                if (($nRng % 2) > 0) {
                    $Fdo = 'FFFFFF';
                } else {
                    $Fdo = 'EBEDEF';
                }
                ?>
                <tr bgcolor="#<?= $Fdo ?>" height='25px'>
                    <td align='center'><?= $rg[idproducto] ?></td>
                    <td align='center'><?= $rg[clave] ?></td>
                    <td align='center'><?= $rg[descripcion] ?></td>
                    <td align='center'><?= $rg[cantidad] ?></td>
                    <td align='center'><?= $rg[costo] ?></td>
                    <td align='center'><?= $rg[civa] ?></td>
                    <td align='center'><?= $rg[importe] ?></td>
                    <td align='center'><a href="entlabd.php?busca=<?= $busca ?>&op=delete&Id=<?= $rg[idnvo] ?>" class="edit"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
                    <td align='center'><?= $rg[importe] ?></td>
                </tr> 
                <?php
                $nRng++;
            }
            ?>
            <tr><td colspan="7" height="2px"></td></tr>
        </table>
        <?php
        if ($op == 'ed') {
            $Cp = "SELECT * FROM elpagos WHERE id='$_REQUEST[cId]'";
            echo $Cp;
            $CpoA = mysql_query($Cp);
            $rg = mysql_fetch_array($CpoA);
        }
        ?>
        <table width="100%" class="letrap">
            <tr>
                <td height="20px">Fecha de pago</td><td>Importe</td><td>Tipo de Pago</td><td>Documento de pago</td>
            </tr>
            <tr>
                <td><input type="date" name='fecha' value ='<?= $rg[fecha] ?>' class="letrap" required></input></td>
                <td><input type='number' step='any' name='importe' value='<?= $importes ?>' size='15' class="letrap" required></input></td>
                <td>
                    <select size='1' name='tpago' class='letrap'>";
                        <option value='Efectivo'> Efectivo </option>
                        <option value='Tarjeta'> Tarjeta </option>
                        <option value='Cheque'> Cheque </option>
                        <option value='Transferencia'> Transferencia </option>
                        <option selected value='<?= $rg[tpago] ?>'> <font size='-1'><?= $rg[tpago] ?></option>
                    </select>
                </td>
                <td><input type='text' name='doctopago' value='<?= $rg[doctopago] ?>' size='30'></input></td>
            </tr>
            <tr>
                <td height="50px"colspan="4">Observaciones 
                    <textarea type='text' name='observaciones' value='<?= $rg[observaciones] ?>' cols='70' rows='2'></textarea>
                </td>
            </tr>
        </table>
    </body>
</html>
<?php
mysql_close();
