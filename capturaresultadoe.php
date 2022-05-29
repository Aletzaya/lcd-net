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

require './Services/CapturaResultadoService.php';
$sucursal = $He["suc"];
require ("config.php");          //Parametros de colores;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle de captura</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Detalle del estudios de laboratorio no. <?= $busca ?> ::..
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
                                <td width='45%' align="right" class="Inpt"><strong>Paciente : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["cliente"] . " .-" . $Cpo["nombrecli"] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Edad : </strong></td>
                                <td class="Inpt">
                                    <?= $anos . " Años " . $meses . " Meses" ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Fecha de Nacimiento : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["fechan"] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Medico : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["nombremed"] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Fecha de Orden : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["fecha"] . " " . $Cpo["hora"] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Fecha de Entrega : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["fechae"] . " " . $Cpo["horae"] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Pagada : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["pagada"] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Institucion : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["institucion"] . " .- " . $Cpo["nombrei"] ?>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Descuento : </strong></td>
                                <td class="Inpt">
                                    <?= $Cpo["descuento"] ?>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Fecha : </strong></td>
                                <td class="Inpt">
                                    <input type="date" name="Fecha" value="<?= $Cpo["fecha"] ?>" class="letrap"></input>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Receta : </strong></td>
                                <td class="Inpt">
                                    <input type="text" name="Receta" value="<?= $Cpo["receta"] ?>" class="letrap"></input>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Clave Medico : </strong></td>
                                <td class="Inpt">
                                    <input type="text" name="Medico" value="<?= $Cpo["medico"] ?>" class="letrap"></input>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Medico MD : </strong></td>
                                <td class="Inpt">
                                    <input type="text" name="Medicon"  value="<?= $Cpo["medicon"] ?>" class="letrap"></input>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Institucion : </strong></td>
                                <td class="Inpt">
                                    <input type="number" name="Institucion"  value="<?= $Cpo["institucion"] ?>" class="letrap"></input>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Diagnostico Medico : </strong></td>
                                <td class="Inpt">
                                    <TEXTAREA NAME='Diagmedico' cols='45' rows='2' class="letrap"><?= $Cpo["diagmedico"] ?></TEXTAREA>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Observaciones : </strong></td>
                                <td class="Inpt">
                                    <TEXTAREA class="letrap" NAME='Observaciones' cols='45' rows='2'><?= $Cpo["observaciones"] ?></TEXTAREA>
                                </td>
                            </tr> 
                            <tr style="height: 30px">
                                <td align="right" class="Inpt"><strong>Password : </strong></td>
                                <td class="Inpt">
                                    <input type="password" name="Password"  class="letrap"></input>
                                </td>
                            </tr>
                            <tr><td colspan="2" align="center">
                                    <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                    <input class="letrap" type="submit" name="Boton" value="Actualiza"></input>
                                </td>
                            </tr>
                        </form>
                    </table>  
                </td>
                <td valign='top' width='45%'>     
                    <table width='95%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Modificaciones ::.
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table align="center" width="95%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                    <tr class="letrap">
                                        <td align="center"><b>&nbsp; Fecha</b></td>
                                        <td><b>&nbsp; Usuario</b></td>
                                        <td><b>&nbsp; Accion</b></td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Operativa/Captura Resultados/%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 25;";
                                    //echo $sql;
                                    $PgsA = mysql_query($sql);
                                    while ($rg = mysql_fetch_array($PgsA)) {
                                        (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = $Gfdogrid;
                                        ?>
                                                <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                    <td align="center">
                                                        &nbsp;<?= $rg[fecha] ?>
                                                    </td>
                                                    <td>
                                                <?= $rg[usr] ?>
                                                    </td>
                                                    <td>
                                                <?= $rg[accion] ?>
                                                    </td>
                                                </tr>
                                        <?php
                                        $nRng++;
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign='top' width="22%">
                    <a href="capturaresultado.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
                </td>
            </tr>      
        </table>  



    </body>
    <script src="./controladores.js"></script>
</html>
<?php
mysql_close();
?>
