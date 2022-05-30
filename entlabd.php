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

if ($_REQUEST["bt"] === "Actualizar") {
    $sql = mysql_query("SELECT status FROM el WHERE id = $busca");
    $Result = mysql_fetch_array($sql);
    if ($Result["status"] != "CERRADA") {
        $Sql = "UPDATE el SET fecha = '$_REQUEST[Fecha]', hora = '$_REQUEST[Hora]', proveedor = $_REQUEST[Proveedor],"
                . "concepto = '$_REQUEST[Concepto]',factrem = '$_REQUEST[Factrem]' ,documento = '$_REQUEST[Documento]',"
                . "almacen = '$_REQUEST[Almacen]', depto = '$_REQUEST[Depto]', status = '$_REQUEST[Status]' "
                . "WHERE id = $busca;";
        echo $Sql;
        if (mysql_query($Sql)) {
            $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                    . "('$Gusr','/Admin/Compras/Datos Principales','el',now(),$busca);";
            if (mysql_query($Sql)) {
                header("Location: entlabd.php?busca=$busca&Msj=Exito");
            }
        }
    }
} else if ($_REQUEST["op"] === "delete") {
    if (mysql_query("DELETE FROM eld WHERE idnvo = $_REQUEST[Id]")) {
        header("Location: entlabd.php?busca=$busca&Msj=Exito");
    }
} elseif ($_REQUEST["Guardar"] === "Guardar" & $_REQUEST["Cantidad"] > 0 & $_REQUEST["Costo"] > 0) {


    $InvA = mysql_query("SELECT iva,pzasmedida FROM invl WHERE Id='" . $_REQUEST["Clave"] . "'");
    $Inv = mysql_fetch_array($InvA);

    if ($_REQUEST["c_iva"] == 1) {
        $Precio = $_REQUEST["Cantidad"] * ($_REQUEST["Costo"] * 1.16);
        $IvaA = $_REQUEST["Cantidad"] * ($_REQUEST["Costo"] * .16);
    } else {
        $Precio = $_REQUEST["Cantidad"] * $_REQUEST["Costo"];
        $IvaA = 0;
    }

    $Up = mysql_query("INSERT INTO eld (id,idproducto,clave,cantidad,costo,iva,civa,precio) "
            . "VALUES ('$busca','" . $_REQUEST["ralId"] . "','" . $_REQUEST["Clave"] . "'," . $_REQUEST["Cantidad"] . "," . $_REQUEST["Costo"] . ","
            . "'$IvaA','" . $_REQUEST["c_iva"] . "','$Precio')");

    $TotA = mysql_query("SELECT sum(cantidad) cantidad,sum(precio) precio,sum(iva) iva FROM eld WHERE id=$busca");
    $Tot = mysql_fetch_array($TotA);
    $Cnt = $Tot["cantidad"];
    $Imp = $Tot["precio"];
    $Iva = $Tot["iva"];

    $Up = mysql_query("UPDATE el SET cantidad=$Cnt,importe=$Imp,iva=$Iva WHERE id=$busca");
    $Clave = "";
    $Idproducto = "";
}

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
            <?php require ("./config_add.php"); ?>
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
                                $HeSql = "SELECT el.id, el.fecha, el.hora, el.cantidad, el.importe, el.status,"
                                        . "el.documento, el.concepto, el.iva, el.almacen, el.usr FROM el WHERE el.id = '$busca' ";

                                $HeA = mysql_query($HeSql);

                                $He = mysql_fetch_array($HeA);
                                ?>
                            </tr>
                            <tr style="height: 30px" class="letrap">
                                <td align="center"><strong>Almacen : </strong><?= $He["almacen"] ?></td>
                                <td align="center"><strong>No.Entrada : </strong><?= $He["id"] ?></td>
                                <td align="center"><strong>Fecha : </strong><?= $He["fecha"] ?></td>
                                <td align="center"><strong>Hora : </strong><?= $He["hora"] ?></td>
                                <td align="center"><strong>Documentos : </strong><?= $He["documento"] ?></td>
                            </tr>
                            <tr style="height: 30px" class="letrap">
                                <td align="center"><strong>Usuario : </strong><?= $He["usr"] ?></td>
                                <td align="center"><strong>Conceptos : </strong><?= $He["concepto"] ?></td>
                                <td align="center"><strong>Cantidad : </strong><?= $He["cantidad"] ?></td>
                                <td align="center"><strong>Importe : </strong><?= $He["importe"] ?></td>
                                <td align="center"><strong>Status : </strong><?= $He["status"] ?> </td>
                            </tr>
                        </form>
                    </table>
                    <table width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr><td colspan="8" height="2px"></td></tr>
                        <tr bgcolor="#5499C7" height='25px'>
                            <td align='center' class='letratitulo'>IdProducto</td>
                            <td align='center' class='letratitulo'>Producto</td>
                            <td align='center' class='letratitulo'>Descripción</td>
                            <td align='center' class='letratitulo'>Cantidad</td>
                            <td align='center' class='letratitulo'>Costo</td>
                            <td align='center' class='letratitulo'>IVA</td>
                            <td align='center' class='letratitulo'>Importe</td>
                            <td align='center' class='letratitulo'>Eliminar</td>
                        </tr>
                        <?php
                        $QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=16");
                        $Qry = mysql_fetch_array($QryA);
                        $Sql = "SELECT $Qry[campos],eld.idnvo,eld.idproducto,eld.civa FROM eld LEFT JOIN invl ON eld.clave = invl.clave WHERE eld.id = $busca";
                        //echo $Sql;
                        $resutl = mysql_query($Sql);
                        while ($rst = mysql_fetch_array($resutl)) {
                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = 'EBEDEF';
                            }
                            ?>
                            <tr bgcolor="#<?= $Fdo ?>" height='25px'>
                                <td align='center'><?= $rst[idproducto] ?></td>
                                <td align='center'><?= $rst[clave] ?></td>
                                <td align='center'><?= $rst[descripcion] ?></td>
                                <td align='center'><?= $rst[cantidad] ?></td>
                                <td align='center'><?= $rst[costo] ?></td>
                                <td align='center'><?= $rst[civa] ?></td>
                                <td align='center'><?= $rst[importe] ?></td>
                                <?php if ($He["status"] == "ABIERTA") { ?>
                                    <td align='center'><a href="entlabd.php?busca=<?= $busca ?>&op=delete&Id=<?= $rst[idnvo] ?>" class="edit"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
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
                            $sumIva += $rst["civa"];
                            $sumImporte += $rst["importe"];
                        }
                        ?>
                        <tr bgcolor="#C3C5C7">
                            <td height="20px"colspan="3" align="right"><strong>Totales :</strong></td>
                            <td align="center"><strong><?= $sumCantidad ?></strong></td>
                            <td align="center"><strong><?= $sumCosto ?></strong></td>
                            <td align="center"><strong><?= $sumIva ?></strong></td>
                            <td align="center"><strong><?= $sumImporte ?></strong></td>
                            <td></td>
                        </tr>
                    </table>
                    <?php if ($He["status"] == "ABIERTA") { ?>
                        <form name='form1' method='get' action="<?php $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                            <table align="lefth" width="70%" id="TablaBusqueda">
                                <tr align="left" class="letrap">
                                    <td>Producto : <input type='text' name='Clave' size='20' value='<?= $_REQUEST[nvoId] ?>' class="letrap"></input>
                                        <a href='invlab1.php?busca=ini&orden=invl.descripcion&miBusca=<?= $busca ?>'><i class="fa fa-search fa-2x" aria-hidden="true"></i></a></td>
                                    <td>Cnt : <input type='text' name='Cantidad' size='4' value='' class="letrap" required></input></td>
                                    <td>Costo : $ <input type='text' name='Costo' size='7' value='' class="letrap" required></input></td>
                                    <td>IVA : <input type='checkbox' name='c_iva' value='1' class="letrap"></input></td>
                                    <td><input type="submit" name="Guardar" value="Guardar" class="letrap"></input></td>
                                    <input type='hidden' name='Idproducto' value='<?= $Idproducto ?>'></input>
                                    <input type='hidden' name='Almacen' value='<?= $He[almacen] ?>'></input>
                                    <input type='hidden' name='busca' value='<?= $busca ?>'></input>
                                    <input type='hidden' name='ralId' value='<?= $_REQUEST[ralId] ?>'></input>
                                </tr>
                                <tr><td colspan="5" height="50px"></td></tr>
                            </table>
                        </form>s
                    <?php } ?>
                </td>

                <td valign='top' width="22%">
                    <a href="entlab.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                </td>
            </tr>      
        </table>  
    </body>
    <script type="text/javascript">
    </script>
</html>
<?php
mysql_close();
