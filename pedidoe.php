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

if ($_REQUEST["bt"] === "Actualizar") {

    $Sql = "UPDATE pedido SET concepto='$_REQUEST[Concepto]',recibio='$_REQUEST[Recibio]',"
            . "status = '$_REQUEST[Status]', fecha='$_REQUEST[Fecha]',depto='$_REQUEST[Depto]',unidad='$_REQUEST[Unidad]' "
            . "WHERE id='$busca' limit 1";
    echo $Sql;
    if (mysql_query($Sql)) {
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Admin/Pedidos/Datos Principales','pedido',now(),$busca);";
        if (mysql_query($Sql)) {
            header("Location: pedidoe.php?busca=$busca&Msj=Registro actualizado con exito!");
        }
    }
} else if ($_REQUEST["bt"] === "Nuevo") {
    $Sql = "INSERT INTO pedido (fecha,hora,concepto,recibio,depto,unidad,status) "
            . "VALUES ('$_REQUEST[Fecha]','$_REQUEST[Hora]','$_REQUEST[Concepto]',"
            . "'$_REQUEST[Recibio]','$_REQUEST[Depto]','$_REQUEST[Unidad]','ABIERTA')";
    if (mysql_query($Sql)) {
        $Id = mysql_insert_id();
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Admin/Pedidos/Datos Principales Agrega','pedido',now(),$Id);";
        if (mysql_query($Sql)) {
            header("Location: pedidoe.php?busca=$Id&Msj=Registro actualizado con exito!");
        }
    }
}

#Variables comunes;
$cSql = "SELECT * FROM pedido WHERE id='$busca'";
$CpoA = mysql_query($cSql);
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle de pedido</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
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

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal del pedido no. <?= $busca ?> ::..
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
                                <td width='45%' align="right" class="Inpt">No. de entrada : </td>
                                <td class="Inpt">
                                    <?= $Cpo[id] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Fecha : </td>
                                <td class="Inpt">
                                    <?php
                                    $_REQUEST["busca"] === "NUEVO" ? $date = date("Y-m-d") : $date = $Cpo["fecha"];
                                    ?>
                                    <input type='date' class='cinput'  name='Fecha' value='<?= $date ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Hora : </td>
                                <td class="Inpt">
                                    <?php
                                    $_REQUEST["busca"] === "NUEVO" ? $date = date("H:i") : $date = $Cpo["hora"];
                                    ?>
                                    <input type='time' class='cinput'  name='Hora' value='<?= $date ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Concepto : </td>
                                <td class="Inpt">
                                    <select name='Concepto' class='cinput'>";
                                        <option value='Ajuste'>Ajuste</option>
                                        <option value='Resurtido'>Resurtido</option>
                                        <option selected value='<?= $Cpo[concepto] ?>'><?= $Cpo[concepto] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Solicita : </td>
                                <td class="Inpt"><input type='text' class='cinput'  name='Recibio' value='<?= $Cpo[recibio] ?>'/></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Cantidad : </td>
                                <td><input type='number' class='cinput'  name='Cantidad' value='<?= $Cpo[cantidad] ?>' disabled/></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Importe : </td>
                                <td><input type='number' class='cinput'  name='Importe' value='<?= $Cpo[importe] ?>' disabled/></td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Departamento : </td>
                                <td>
                                    <select name='Depto' class='cinput'>";
                                        <option value='Mixto'>Mixto</option>
                                        <option value='Admvo'>Admvo</option>
                                        <option value='Laboratorio'>Laboratorio</option>
                                        <option value='Rayos X'>Rayos X</option>
                                        <option value='Ultrasonido'>Ultrasonido</option>			 
                                        <option selected value='<?= $Cpo[depto] ?>'><?= $Cpo[depto] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Unidades : </td>
                                <td>
                                    <select name='Unidad' class='cinput'>";
                                        <option value='Matriz'>LCD Matriz</option>
                                        <option value='Tepexpan'>LCD Tepexpan</option>
                                        <option value='OHF'>LCD OHF</option>
                                        <option value='Reyes'>LCD Reyes</option>
                                        <option value='Camarones'>LCD Camarones</option>
                                        <option selected value='<?= $Cpo[unidad] ?>'><?= $Cpo[unidad] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">Status : </td>
                                <td>
                                    <?php $Cpo[status] === "CERRADA" ? $Estado = "disabled='disabled'" : $Estado = ""; ?>
                                    <select name='Status' class="cinput" <?= $Estado ?>>
                                        <option value='ABIERTA'>ABIERTA</option>
                                        <option value='CERRADA'>CERRADA</option>
                                        <option selected value='<?= $Cpo[status] ?>'><?= $Cpo[status] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php if ($_REQUEST["busca"] === "NUEVO") { ?>
                                        <input class="letrap" type="submit" value='Nuevo' name='bt'></input>
                                    <?php } else { ?>
                                        <?php if ($Cpo["status"] === "ABIERTA") { ?>
                                            <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                        <?php } ?>
                                    <?php } ?>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>

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
                                        <td><b>&nbsp; Id</b></td>
                                        <td><b>&nbsp; Fecha</b></td>
                                        <td><b>&nbsp; Usuario</b></td>
                                        <td><b>&nbsp; Accion</b></td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Admin/Pedidos/Datos%') 
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
                    <a href="pedido.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
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

