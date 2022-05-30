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
$Fecha = date("Y-m-d H:m:i");
#Variables comunes;
$Titulo = "Ordenes de estudio";
if ($_REQUEST["bt"] == "Actualizar") {
    $Sql = "UPDATE dpag_ref SET id_ref = '$_REQUEST[Referencia]',tipopago='$_REQUEST[Tipopago]', fechapago = '$_REQUEST[Fechapago]', hospi = '$_REQUEST[Hospi]',"
            . "orden_h = $_REQUEST[Orden_h], concept = '$_REQUEST[Concept]', recibe = '$_REQUEST[Recibe]', autoriza = '$_REQUEST[Autoriza]',"
            . "monto = '$_REQUEST[Monto]', observaciones = '$_REQUEST[Observaciones]' WHERE id=$busca";
    if (!mysql_query($Sql)) {
        $Msj = "Error en sql " . $Sql;
        echo $Msj;
    }
    $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Gastos/Detalle de Gastos','dpag_ref',now(),$busca);";
    if (!mysql_query($Sql)) {
        $Msj = "Error en sql " . $Sql;
        echo $Msj;
    }
        header("Location: ctpagos.php?busca=ini");

}elseif ($_REQUEST["bt"] == "NUEVO") {

    $Sql = "INSERT INTO dpag_ref (id_ref,orden_h,fecha,observaciones,monto,tipopago,fechapago,recibe,concept,hospi,autoriza,usr,suc) VALUES ('$_REQUEST[Referencia]','$_REQUEST[Orden_h]','$Fecha','$_REQUEST[Observaciones]','$_REQUEST[Monto]','$_REQUEST[Tipopago]','$_REQUEST[Fechapago]','$_REQUEST[Recibe]','$_REQUEST[Concept]','$_REQUEST[Hospi]','$_REQUEST[Autoriza]','$Gusr','$Gcia')";

    if (!mysql_query($Sql)) {
        $Msj = "Error en sql " . $Sql;
        echo $Msj;
    }
    $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Gastos/Detalle de Gastos','dpag_ref',now(),$busca);";
    if (!mysql_query($Sql)) {
        $Msj = "Error en sql " . $Sql;
        echo $Msj;
    }

    header("Location: ctpagos.php?busca=ini");

}elseif ($_REQUEST["bt"] == "Cerrar") {

    $cSql = "UPDATE dpag_ref SET cerrada='1' WHERE id='$busca' limit 1";
    
    if (!mysql_query($cSql)) {
        $Archivo = 'CLI';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

    header("Location: ctpagos.php?busca=ini");   
        
}

$Msj = $_REQUEST[Msj];
$CpoA = mysql_query("SELECT dpag_ref.id,dpag_ref.concept,dpag_ref.autoriza,dpag_ref.hospi,cptpagod.referencia,cptpagod.id idref,dpag_ref.modifica, "
        . "cpagos.id idpagos,cpagos.clave,cpagos.concepto,dpag_ref.fecha,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.orden_h,"
        . "dpag_ref.tipopago,dpag_ref.cerrada,dpag_ref.fechapago,dpag_ref.recibe,dpag_ref.id_ref FROM dpag_ref "
        . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
        . "WHERE dpag_ref.id='$busca' ORDER BY id");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Detalle de gastos</title>
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
        <script src="./controladores.js"></script>
    <?php
        ?>
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Informacion Principal del gasto no. <?= $busca ?> ::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='440' width='45%'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c"><td class='letratitulo'align="center" colspan="2">..:: Detalle de Pago ::..</td></tr>
                            <tr style="height: 30px"><td width='45%' align="right" class="Inpt">Id : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombrec' value='<?= $busca ?>' MAXLENGTH='30' disabled></input>
                                </td>
                            </tr>

                            <tr style="height: 30px"><td align="right" class="Inpt">Fecha de pago : </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Fechapago' value='<?= $Cpo["fechapago"] ?>' ></input>
                                </td>
                            </tr>
                            <tr style="height: 30px"><td align="right" class="Inpt">Tipo de pago : </td>
                                <td class="Inpt">
                                    <select name="Referencia" class="letrap">
                                        <?php
                                        $CliA = mysql_query("SELECT id,referencia,cuenta FROM cptpagod  where status='Activo' ORDER BY id");
                                        while ($Cli = mysql_fetch_array($CliA)) {
                                            echo "<option value='$Cli[id]'>$Cli[referencia]| " . $Cli[cuenta] . "</option>";
                                            if($Cli[id]==$Cpo[id_ref]){
                                                $referencia=$Cli[referencia]. " | " . $Cli[cuenta];
                                            } 
                                        }
                                        echo "<option selected value='$Cpo[id_ref]'>$referencia</option>";
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px"><td align="right" class="Inpt">Referencia a laboratorio : </td>
                                <td class="Inpt">
                                    <select name="Hospi" class="letrap">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                        <option selected value="<?= $Cpo[hospi] ?>"><?= $Cpo["hospi"] ?>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px"><td width='45%' align="right" class="Inpt">No. de orden del paciente : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Orden_h' value='<?= $Cpo["orden_h"] ?>' MAXLENGTH='30' ></input>
                                </td>
                            </tr>
                            <tr style="height: 30px"><td align="right" class="Inpt">Concepto de laboratorio : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Concept' value='<?= $Cpo["concept"] ?>'></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Quien recibe : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Recibe' value='<?= $Cpo["recibe"] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Quien autoriza : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Autoriza' value='<?= $Cpo["autoriza"] ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Forma de pago : </td>
                                <td class="Inpt">
                                    <select name='Tipopago' class="letrap   ">
                                        <?php
                                        $CliAs = mysql_query("SELECT * FROM cpagos ORDER BY id");
                                        while ($Clis = mysql_fetch_array($CliAs)) {
                                            echo "<option value='$Clis[id]'>$Clis[concepto]| " . $Clis[clave] . "</option>";
                                            if($Clis[id]==$Cpo[idpagos]){
                                                $cps=$Clis[concepto]. " | " . $Clis[clave];
                                            }
                                        }
                                        echo "<option selected value='$Cpo[idpagos]'>$cps</option>";
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Importe : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Monto' value='<?= $Cpo["monto"] ?>'></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Observaciones : </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Observaciones' value='<?= $Cpo["observaciones"] ?>'></input>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php
                                    if ($busca == "NUEVO") {
                                        ?>
                                        <input class="letrap" type="submit" value='NUEVO' name='bt'></input>
                                    <?php
                                    } else {
                                        if ($Cpo[cerrada] == "1") {
                                    ?>
                                            <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                        <?php
                                        } else {
                                        ?>
                                            <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                            <input class="letrap" type="submit" value='Cerrar' name='bt'></input>
                                            <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                        <?php
                                        }
                                        ?>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            </form>
                        </table>  
                    </td>
                    <td valign='top' width='45%'>                        
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    .:: Modificaciones ::.
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <table align="center" width="95%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                        <tr class="letrap">
                                            <td>
                                                <b>&nbsp; Id</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Fecha</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Usuario</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Accion</b>
                                            </td>
                                        </tr>
                                        <?php
                                        $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Catalogos/Gastos/Deta%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 6;";
                                        //echo $sql;
                                        $PgsA = mysql_query($sql);
                                        while ($rg = mysql_fetch_array($PgsA)) {
                                            if (($nRng % 2) > 0) {
                                                $Fdo = 'FFFFFF';
                                            } else {
                                                $Fdo = $Gfdogrid;
                                            }
                                            ?>
                                            <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                                <td>
                                                    <b>&nbsp;<?= $rg[id] ?></b>
                                                </td>
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
                        <a href="ctpagos.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                        <a class="cMsj">
                            <?= $msj ?>
                        </a>
                    </td>
                </tr>      
            </table>
    </body>
</html>
<?php
mysql_close();
?>
