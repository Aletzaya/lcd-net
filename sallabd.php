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

if ($_REQUEST["op"] === "delete") {
    if (mysql_query("DELETE FROM sld WHERE idnvo = $_REQUEST[Id]")) {
        Totaliza($busca);
        header("Location: sallabd.php?busca=$busca&Msj=Exito");
    }
} elseif ($_REQUEST["Guardar"] === "Guardar" & $_REQUEST["Cantidad"] > 0) {

    $InvA = mysql_query("SELECT costo,existencia FROM invl WHERE clave='$_REQUEST[Clave]'");
    $Inv = mysql_fetch_array($InvA);

    $Sql = "INSERT INTO sld (id,clave,cantidad,costo) 
                 VALUES 
                 ('$busca','$_REQUEST[Clave]',$_REQUEST[Cantidad],'$Inv[costo]')";
    echo $Sql;
    $Up = mysql_query($Sql);

    $Clave = "";

    Totaliza($busca);
    header("Location: sallabd.php?busca=$busca&Msj=Exito");
}

#Variables comunes;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=18");
$Qry = mysql_fetch_array($QryA);
$CpoA = mysql_query("SELECT $Qry[campos],sld.idnvo FROM sld LEFT JOIN invl ON sld.clave = invl.clave WHERE sld.id = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle de Salida no. <?= $busca ?></title>
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

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='90%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="5">
                                    ..:: Detalle de entrada a Laboratorio ::..
                                </td>
                                <?php
                                $HeSql = "SELECT sl.id, sl.fecha, sl.hora, sl.cantidad, sl.importe, sl.status,
                                    sl.recibio, sl.concepto, sl.almacen
                                    FROM sl
                                    WHERE sl.id = '$busca' ";
                                //echo $HeSql;
                                $HeA = mysql_query($HeSql);

                                $He = mysql_fetch_array($HeA);
                                ?>
                            </tr>
                            <tr style="height: 30px" class="letrap">
                                <td align="center"><strong>No.Salida : </strong><?= $He["id"] ?></td>
                                <td align="center"><strong>Fecha : </strong><?= $He["fecha"] ?></td>
                                <td align="center"><strong>Hora : </strong><?= $He["hora"] ?></td>
                                <td align="center"><strong>Concepto : </strong><?= $He["concepto"] ?></td>
                            </tr>
                            <tr style="height: 30px" class="letrap">
                                <td align="center"><strong>Recibio : </strong><?= $He["recibio"] ?></td>
                                <td align="center"><strong>Cantidad : </strong><?= $He["cantidad"] ?></td>
                                <td align="center"><strong>Importe : </strong><?= round($He["importe"], 2) ?></td>
                                <td align="center"></td>
                            </tr>

                        </form>
                    </table>
                    <table width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr><td colspan="8" height="2px"></td></tr>
                        <tr bgcolor="#5499C7" height='25px'>
                            <td align='center' class='letratitulo'>Producto</td>
                            <td align='center' class='letratitulo'>Descripción</td>
                            <td align='center' class='letratitulo'>Cantidad</td>
                            <td align='center' class='letratitulo'>Costo</td>
                            <td align='center' class='letratitulo'>Importe</td>
                            <td align='center' class='letratitulo'>Existencia</td>
                            <td align='center' class='letratitulo'>Status</td>
                            <td align='center' class='letratitulo'>Eliminar</td>
                        </tr>
                        <?php
                        $QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=18");
                        $Qry = mysql_fetch_array($QryA);
                        $Sql = "SELECT $Qry[campos],sld.idnvo FROM sld LEFT JOIN invl ON sld.clave = invl.clave  WHERE sld.id = $busca";
                        //echo $Sql;
                        $resutl = mysql_query($Sql);
                        while ($rst = mysql_fetch_array($resutl)) {
                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = 'EBEDEF';
                            }
                            $cantidad2 = $rst[existencia] - $rst[cantidad];

                            if ($rst[existencia] <= '0') {
                                $staped = 'Faltante';
                                $colorletra = '#FF0000';
                            } else {
                                if ($cantidad2 >= '1') {
                                    $staped = 'Disponible';
                                    $colorletra = '#666600';
                                } else {
                                    if ($cantidad2 == '0') {
                                        $staped = 'Ultima Pza';
                                        $colorletra = '#FF3300';
                                    } else {
                                        $staped = 'Incompleto';
                                        $colorletra = '#FF0000';
                                    }
                                }
                            }
                            ?>
                            <tr bgcolor="#<?= $Fdo ?>" height='25px'>
                                <td align='center'><?= $rst[clave] ?></td>
                                <td align='center'><?= $rst[descripcion] ?></td>
                                <td align='center'><?= $rst[cantidad] ?></td>
                                <td align='center'><?= $rst[costo] ?></td>
                                <td align='center'><?= round($rst[import], 2) ?></td>
                                <td align='center'><?= $rst[existencia] ?></td>
                                <td align='center' bgcolor="<?= $colorletra ?>"><strong><?= $staped ?></strong></td>
                                <?php if ($He["status"] == "ABIERTA") { ?>
                                    <td align='center'><a href="<?= $_SERVER[PHP_SELF] ?>?busca=<?= $busca ?>&op=delete&Id=<?= $rst[idnvo] ?>" class="edit"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
                                    <?php
                                } else {
                                    echo "<td></td>";
                                }
                                ?>
                            </tr> 
                            <?php
                            $nRng++;
                            $sumCantidad += $rst["cantidad"];
                            $sumCosto += $rst["costo"];
                            $sumImporte += $rst["import"];
                            $sumExistencia += $rst["existencia"];
                        }
                        ?>
                        <tr bgcolor="#C3C5C7">
                            <td height="20px"colspan="2" align="right"><strong>Totales :</strong></td>
                            <td align="center"><strong><?= $sumCantidad ?></strong></td>
                            <td align="center"><strong><?= $sumCosto ?></strong></td>
                            <td align="center"><strong><?= round($sumImporte, 2) ?></strong></td>
                            <td align="center"><strong><?= $sumExistencia ?></strong></td>
                            <td align="center" colspan="2"></td>
                            <td></td>
                        </tr>
                    </table>
                    <?php if ($He["status"] == "ABIERTA") { ?>
                        <form name='form1' method='get' action="<?php $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                            <table align="right" width="40%" id="TablaBusqueda">
                                <tr align="left" class="letrap">
                                    <td width="15px"></td>
                                    <td>Producto : <input type='text' name='Clave' size='20' value='<?= $_REQUEST[nvoId] ?>' class="letrap"></input>
                                        <a href='invlab2.php?busca=ini&orden=invl.descripcion&miBusca=<?= $busca ?>'><i class="fa fa-search fa-2x" aria-hidden="true"></i></a></td>
                                    <td>Cnt : <input type='text' name='Cantidad' size='4' value='' class="letrap" required></input></td>
                                    <td><input type="submit" name="Guardar" value="Guardar" class="letrap"></input></td>
                                    <input type='hidden' name='Idproducto' value='<?= $Idproducto ?>'></input>
                                    <input type='hidden' name='Almacen' value='<?= $He[almacen] ?>'></input>
                                    <input type='hidden' name='busca' value='<?= $busca ?>'></input>
                                    <input type='hidden' name='ralId' value='<?= $_REQUEST[ralId] ?>'></input>
                                </tr>
                                <tr><td colspan="5" height="50px"></td></tr>
                            </table>
                        </form>
                    <?php } ?>
                </td>

                <td valign='top' width="22%">
                    <a href="sallab.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                    <a class="cMsj">
                        <?= $msj ?>
                    </a>
                </td>
            </tr>      
        </table>  
    </body>
    <script src="./controladores.js"></script>
    <script type="text/javascript">
    </script>
</html>
<?php

function Totaliza($busca) {

    $TotA = mysql_query("SELECT sum(cantidad) cantidad,sum(cantidad*costo) sum1 FROM pedidod WHERE id=$busca");
    $Tot = mysql_fetch_array($TotA);
    $Cnt = $Tot["cantidad"] * 1;
    $Imp = $Tot["sum1"] * 1;

    $Up = mysql_query("UPDATE pedido SET cantidad=$Cnt,importe=$Imp WHERE id=$busca");
}

mysql_close();
