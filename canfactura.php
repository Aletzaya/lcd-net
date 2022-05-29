<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fecha = date("Y-m-d H:m:s");

#Variables comunes;
$CpoA = mysql_query("SELECT fc.fecha,fc.cliente,fc.cantidad,fc.iva,fc.ieps,fc.importe,
  	     clif.rfc,clif.nombre,fc.uuid,clif.direccion,clif.colonia,clif.municipio,clif.codigo
  	     FROM clif,fc WHERE fc.id='$busca' AND fc.cliente=clif.id");

$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
} else {
    $Estado = $Cpo[estado];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Cancelacion de Factura</title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) . " Id.- " . $Cpo[cliente] ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Datos principales ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt"><strong>Cliente : </strong></td>
                                <td class="Inpt"><?= $Cpo[nombre] ?></td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt"><strong>Dirección : </strong></td>
                                <td class="Inpt"><?= $Cpo[direccion] ?></td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt"><strong>Colonia : </strong></td>
                                <td class="Inpt"><?= $Cpo[colonia] ?></td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt"><strong>Municipio : </strong></td>
                                <td class="Inpt"><?= $Cpo[municipio] ?></td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt"><strong>Codigo Postal : </strong></td>
                                <td class="Inpt"><?= $Cpo[codigo] ?></td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt"><strong>Rfc : </strong></td>
                                <td class="Inpt"><?= $Cpo[rfc] ?></td>
                            </tr>
                        </form>
                    </table>  
                </td>
                <td valign='top' width='45%'>
                    <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center">
                                    .:: Contactos ::.
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" >
                                    <table align="center" cellpadding="3" cellspacing="2" width="97%" border='0'>
                                        <tr>
                                            <td height="5px" colspan="4">
                                            </td>
                                        </tr>
                                        <tr bgcolor="#A7C2FC" class="letrap" align="center">
                                            <td height="22px"><b>Producto</b></td>
                                            <td><b>Descripcion</b></td>
                                            <td><b>Cantidad</b></td>
                                            <td><b>Precio</b></td>
                                            <td><b>Importe</b></td>
                                        </tr>   
                                        <?php
                                        $sql = "SELECT fcd.estudio,est.descripcion,fcd.cantidad,fcd.precio,fcd.iva,
                                            fcd.importe
                                            FROM fcd LEFT JOIN est ON fcd.estudio=est.estudio
                                            WHERE fcd.id='$busca'";
                                        //echo $sql;
                                        $result3 = mysql_query($sql);
                                        $num = 0;
                                        while ($cSql = mysql_fetch_array($result3)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = '#FFFFFF';
                                            } else {
                                                $Fdo = '#D5D8DC';
                                            }
                                            ?>
                                            <tr class="letrap" bgcolor="<?= $Fdo ?>">
                                                <td><?= $cSql[estudio] ?></td>
                                                <td><?= $cSql[descripcion] ?></td>
                                                <td align="right">
                                                    <?= number_format($cSql[cantidad], 2) ?>
                                                </td>
                                                <td  align="right">
                                                    <?= number_format($cSql[precio], 2) ?>
                                                </td>
                                                <td  align="right">
                                                    <?= number_format($cSql[importe], 2) ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $SumaCantidad = $SumaCantidad + $cSql[cantidad];
                                            $sumP = $sumP + $cSql[precio];
                                            $sumPi = $sumPi + $cSql[importe];
                                            $nRng++;
                                        }
                                        ?>
                                        <tr class="letrap">
                                            <td align="right" colspan="2"><b>Totales: ---> </b></td>
                                            <td align="right"><b>$  <?= number_format($SumaCantidad, 2) ?></b></td>
                                            <td align="right"><b>$  <?= number_format($sumP, 2) ?></b></td>
                                            <td align="right"><b>$  <?= number_format($sumPi, 2) ?></b></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>           
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #FF0000">
                            <td class='letratitulo'align="center">
                                .:: Cancelación de facturas ::.
                            </td>
                        </tr>
                        <tr>
                            <td height="50px" class="letrap" align="center">
                                <strong>Password : </strong> 
                                <input type="password" name="Password" value="" size="10" placeholder="*******" class="letrap" style="width: 200px"></input>
                                <input type='hidden' name='busca' value='<?= $busca ?>'></input>
                                <input type='submit' class="letrap" name='Boton' value='CANCELAR FACTURA'></input>
                            </td>
                        </tr>
                    </table>
                    <br></br>
                    <a href="facturas.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                </td>
            </tr>      
        </table>  
    </body>
    <script src="./controladores.js"></script>
</html>
<?php
mysql_close();
?>
