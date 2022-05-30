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
$Msj = $_REQUEST[Msj];
$Titulo = "Ordenes de estudio";
$Msj = $_REQUEST[Msj];
$StatusMensaje = $_REQUEST[Status];
if ($_REQUEST["Boton"] == "Enviar") {

    $CiaA = mysql_query("SELECT password,iva FROM cia WHERE id='1'");
    $Cia = mysql_fetch_array($CiaA);

    $Clave = md5($_REQUEST["Password"]);


    if ($Cia[0] == $Clave) {

        $FecI = $_REQUEST[FecI];
        $FecF = $_REQUEST[FecF];
        $Depto = $_REQUEST[Depto];
        $Institucion = $_REQUEST[Institucion];

        if ($Depto == '' OR $Depto == '*') {
            $cc = "SELECT otd.estudio, otd.precio,otd.descuento 
                FROM otd, est, ot
                WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF'
                AND ot.institucion='$Institucion' AND ot.orden=otd.orden
                AND  otd.estudio = est.estudio  
                ";
            $result = mysql_query($cc);
        } else {
            $cc = "SELECT otd.estudio, otd.precio,otd.descuento 
                FROM otd, est, ot
                WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF' AND ot.institucion='$Institucion'
                AND ot.orden=otd.orden AND  otd.estudio = est.estudio AND est.depto='$Depto'  
                ";
            $result = mysql_query($cc);
        }
        echo $cc;

        while ($rg = mysql_fetch_array($result)) {

            $Precio = round($rg[precio] * (1 - ($rg[descuento] / 100)), 2);

            $PrecioU = round($Precio / (1 + ($Cia[iva] / 100)), 2);

            $lUp = mysql_query("INSERT INTO fcd 
                           (id,estudio,precio,descuento,orden,iva,cantidad,importe)
                           VALUES 
                           ('$busca','$rg[estudio]','$PrecioU','0','99999','$Cia[iva]',
                           '1','$Precio')");
        }
        Totaliza($busca);
        //header("Location: facturase.php?Msj=$Msj&busca=$busca&Status=$Status");
    }
} elseif ($_REQUEST[Boton] === "Agregar") {

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
    header("Location: facturase.php?Msj=$Msj&busca=$busca&Status=$Status");
} elseif ($_REQUEST[op] === "delete") {
    $sql = "DELETE FROM fcd WHERE idnvo = " . $_REQUEST["Id"] . ";";
    if (mysql_query($sql)) {
        $Msj = "Registro eliminado";
        $Status = 1;
        Totaliza($busca);
        header("Location: facturase.php?Msj=$Msj&busca=$busca&Status=$Status");
    }
} elseif ($_REQUEST[Boton] == 'Descto' AND $_REQUEST[Descuento] > 0) {
    $Select = "SELECT * FROM fcd WHERE id = $busca";
    $dt = mysql_query($Select);
    while ($rs = mysql_fetch_array($dt)) {
        $Importe = $rs["importe"] * (1 - ($_REQUEST["Descuento"] / 100));
        $Precio = $Importe / 1.16;
        $Update = "UPDATE fcd SET precio ='$Precio',importe='$Importe',descuento='$_REQUEST[Descuento]' WHERE idnvo='$rs[idnvo]' LIMIT 1";
        $exist = false;
        if ($rs["descuento"] > 0) {
            $Status = "SI";
            $Msj .= " No se puede actualizar " . $rs["idnvo"] . " ya contiene descuento por facturación";
        } else {
            mysql_query($Update);
            $exist = true;
        }
    }
    //$lUp = mysql_query("INSERT INTO fcd (id,estudio,precio,cantidad,iva,orden)
    //        VALUES ('$busca','descuen','$_REQUEST[Descuento]','-1','0','99999')");
    Totaliza($busca);
    if ($exist) {
        $Msj = "!Descuento agregado con exito!" . $Msj;
    } else {
        $Msj = "ERROR!!!" . $Msj;
    }

    header("Location: facturase.php?Msj=$Msj&busca=$busca&Status=$Status");
} elseif ($_REQUEST[Boton] == 'Enviar') {

    $CiaA = mysql_query("SELECT password,iva FROM cia WHERE id='1'");
    $Cia = mysql_fetch_array($CiaA);

    $Clave = md5($_REQUEST[Password]);


    if ($Cia[0] == $Clave) {

        $FecI = $_REQUEST[FecI];
        $FecF = $_REQUEST[FecF];
        $Depto = $_REQUEST[Depto];
        $Institucion = $_REQUEST[Institucion];

        if ($Depto == '' OR $Depto == '*') {
            $result = mysql_query("SELECT otd.estudio, otd.precio,otd.descuento 
                FROM otd, est, ot
                WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF'
                AND ot.institucion='$Institucion' AND ot.orden=otd.orden
                AND  otd.estudio = est.estudio  
                ");
        } else {
            $result = mysql_query("SELECT otd.estudio, otd.precio,otd.descuento 
                FROM otd, est, ot
                WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF' AND ot.institucion='$Institucion'
                AND ot.orden=otd.orden AND  otd.estudio = est.estudio AND est.depto='$Depto'  
                ");
        }

        while ($rg = mysql_fetch_array($result)) {

            $Precio = round($rg[precio] * (1 - ($rg[descuento] / 100)), 2);

            $PrecioU = round($Precio / (1 + ($Cia[iva] / 100)), 2);

            $lUp = mysql_query("INSERT INTO fcd 
                           (id,estudio,precio,descuento,orden,iva,cantidad,importe)
                           VALUES 
                           ('$busca','$rg[estudio]','$PrecioU','0','99999','$Cia[iva]',
                           '1','$Precio')");
        }

        Totaliza($busca);
    } else {

        $Msj = 'Error: password ' . $Clave . " vs " . md5($_REQUEST[Password]);
    }
} elseif ($_REQUEST["Boton"] === "Desc") {
    $Select = "SELECT * FROM fcd WHERE idnvo = $_REQUEST[Idnvo]";
    $dt = mysql_query($Select);
    $rs = mysql_fetch_array($dt);
    if ($rs["descuento"] == 0) {

        $Importe = $rs["importe"] * (1 - ($_REQUEST["DescuentoEstudio"] / 100));
        $Precio = $Importe / 1.16;
        $Update = "UPDATE fcd SET precio ='$Precio',importe='$Importe',descuento='" . $_REQUEST["DescuentoEstudio"] . "' "
                . "WHERE idnvo='" . $_REQUEST["Idnvo"] . "' LIMIT 1";
        echo $Update;
        mysql_query($Update);
        Totaliza($busca);
        $Msj = "¡Descuento registrado con Exito!";
        header("Location: facturase.php?Msj=$Msj&busca=$busca&Status=$Status");
    } else {
        $Status = "SI";
        $Msj = "Error el producto ya contiene un descuento por facturación, borrar y volver a dar nuevo descuento";
        header("Location: facturase.php?Msj=$Msj&busca=$busca&Status=$Status");
    }
}

$cSqlH = "SELECT fc.id,fc.folio,fc.fecha,clif.nombre,clif.rfc,fc.cantidad,fc.importe,fc.iva,fc.status
            FROM fc LEFT JOIN clif ON fc.cliente=clif.id
            WHERE fc.id='$busca'";
$HeA = mysql_query($cSqlH);
$He = mysql_fetch_array($HeA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle Factura</title>

            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Detalle de la factura no. <?= $busca ?> ::..
                </td>
            </tr>
            <tr>
                <td>
                    <table align='center' width='92%' cellpadding='0' cellspacing='1' border='0'>
                        <tr class="letrasubt">
                            <td><strong>No.factura:</strong> <?= $He[folio] ?></td>
                            <td><strong>Cliente:</strong> <?= ucwords($He[nombre]) ?> </td>
                            <td align='right'><strong>No.estudios:</strong> <?= $He[cantidad] ?></td>
                        </tr>
                        <tr class="letrasubt">
                            <td><strong>Fecha:</strong> <?= $He["fecha"] ?> </td>
                            <td>
                                <strong>Status:</strong> <?= $He[status] ?>
                            </td>
                            <td align='right'>
                                <strong>Importe:</strong> $ <?= number_format($He["importe"], "2") ?> 
                                <strong>Iva:</strong> $ <?= number_format($He["iva"], "2") ?> 
                                <strong>Total:</strong> $ <?= number_format($He["importe"] + $He["iva"], "2") ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='95%'>
                    <?php
                    $cSqlD = "SELECT fcd.orden,fcd.estudio,est.descripcion,fcd.precio,fcd.descuento,fcd.idnvo,fcd.cantidad,fcd.idnvo,fcd.importe 
                        FROM fcd,est
                        WHERE fcd.estudio=est.estudio AND fcd.id='$busca'";
                    $resutl = mysql_query($cSqlD);
                    ?>
                    <table width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr><td colspan = "9" height = "2px"></td></tr>
                        <tr bgcolor = "#5499C7" height = '25px'>
                            <td align = 'center' class = 'letratitulo'>Orden</td>
                            <td align = 'center' class = 'letratitulo'>Estudio</td>
                            <td align = 'center' class = 'letratitulo'>Descripción</td>
                            <td align = 'center' class = 'letratitulo'>Cnt</td>
                            <td align = 'center' class = 'letratitulo'>Precio</td>
                            <td align = 'center' class = 'letratitulo'>%Dto Ot's</td>
                            <td align = 'center' class = 'letratitulo'>%Dto</td>
                            <td align = 'center' class = 'letratitulo'>Importe</td>
                            <td align = 'center' class = 'letratitulo'>Eliminar</td>
                            <td align = 'center' class = 'letratitulo'> Descuento</td>
                        </tr>
                        <?php
                        while ($rst = mysql_fetch_array($resutl)) {
                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = 'EBEDEF';
                            }
                            $Sql2 = "SELECT descuento FROM otd WHERE orden = " . $rst["orden"] . " AND estudio = '" . $rst["estudio"] . "'";
                            
                            $s2 = mysql_query($Sql2);
                            $tt = mysql_fetch_array($s2);
                            ?>
                            <tr bgcolor="#<?= $Fdo ?>" height='25px'>
                                <td align='center'><?= $rst[orden] ?></td>
                                <td align='center'><?= $rst[estudio] ?></td>
                                <td align='center'><?= $rst[descripcion] ?></td>
                                <td align='center'><?= $rst[cantidad] ?></td>
                                <td align='center'><?= $rst[precio] ?></td>
                                <td align='center'><?= $tt[descuento] ?></td>
                                <td align='center'><?= $rst[descuento] ?></td>
                                <td align='center'><?= $rst[importe] ?></td>
                                <?php
                                if ($He["status"] === "Timbrada") {
                                    echo "<td></td><td></td>";
                                } else {
                                    ?>
                                    <td align='center'><a href="facturase.php?busca=<?= $busca ?>&op=delete&Id=<?= $rst[idnvo] ?>" class="edit"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
                                    <td width="30px">
                                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                                            <input style="width:40px;" type="number" name="DescuentoEstudio" class="letrap"></input>
                                            <input type="submit" name="Boton" value="Desc" class="letrap"></input>
                                            <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                            <input type="hidden" name="Idnvo" value="<?= $rst[idnvo] ?>"></input>
                                        </form>
                                    </td>
                                <?php }
                                ?>
                            </tr> 
                            <?php
                            $nRng++;
                        }
                        ?>
                        <tr><td colspan="7" height="2px"></td></tr>
                    </table>
                    <br></br>
                    <table bgcolor="#fff" width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr height='25px'>
                            <td  valign="middle" align="left" height="45px" class="letrap" valign="middle" width="20%">
                                <form  name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                                    &nbsp; &nbsp; No. de Orden :
                                    <input class='Input' type='text' name='Orden' size='6'></input>
                                    <input class='letrap' type='submit' name='Boton' value='Agregar'></input>
                                    <input type='hidden' name='busca' value='<?= $busca ?>'/>
                                </form>
                            </td>
                            <td valign="middle" >
                                <form  name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                                    <?php
                                    if ($He[importe] > 0 && $He[status] === "Abierta") {
                                        ?>
                                        <input type='hidden' name='busca' value='<?= $busca ?>'/>
                                        <a class='edit' href='genfactura.php?busca=<?= $busca ?>&Certificados=LCD'>GENERAR LA FACTURA <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i></a> 
                                        <?php
                                    }
                                    ?>
                                </form>
                            </td>
                            <td>
                                <form name='descuento' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                                    <input type='number' style="width: 80px;" name='Descuento' value='' size='8' max="99"></input>
                                    <input class="letrap" type='submit' name='Boton' value='Descto'></input>
                                    <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                </form>
                            </td>
                            <td valign="middle" align="right"><a href="facturas.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a></td><td width="1%"></td>
                        </tr>
                        <tr height='25px'>
                            <td valign="bottom" align="left" colspan="4" width="70%">
                                <form  name='Frommx' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                                    &nbsp; &nbsp; Fec.I:  <input type='date' class='letrap' name='FecI' size='9' value ='<?= $FecI ?>'></input>
                                    Fec.F: <input type='date' class='letrap' name='FecF' size='9' value ='<?= $FecF ?>'></input>
                                    Institucion:
                                    <select class='letrap' name='Institucion'>
                                        <option value='*'> *  T o d o s </option>
                                        <?php
                                        $InsA = mysql_query("SELECT institucion,nombre FROM  inst");
                                        while ($Ins = mysql_fetch_array($InsA)) {
                                            echo "<option value='$Ins[institucion]'>" . ucwords(strtolower(substr($Ins[nombre], 0, 30))) . "</option>";
                                        }
                                        ?>
                                        <option selected value=''>Selecciona la institucion</option>
                                    </select>
                                    Departamento:
                                    <select class='letrap' name='Depto'>
                                        <option value=''> *  T o d o s </option>
                                        <?php
                                        $Depto = mysql_query("SELECT departamento,nombre FROM dep");
                                        while ($Depto1 = mysql_fetch_array($Depto)) {
                                            echo "<option value='$Depto1[departamento]'>" . ucwords(strtolower($Depto1[nombre])) . "</option>";
                                        }
                                        ?>
                                        <option selected value=''>Departamento</option>
                                    </select>

                                    Passw: 
                                    <input class='letrap' type='password' name='Password' value='' size='12' placeholder="*****"></input>
                                    <input TYPE='SUBMIT' class='letrap' name='Boton' value='Enviar'></input>
                                    <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>      
        </table>
    </body>
    <script src="./controladores.js"></script>
</html>    
<?php

function Totaliza($busca) { //busca es idnvo de medt y cVarVal es id de la entrada;
    $DddA = mysql_query("SELECT sum(round(fcd.precio,2)) PrecioSinIva,
        sum(round(fcd.precio,2)) + SUM(ROUND( fcd.cantidad * fcd.precio * CAST( fcd.iva /100 AS DECIMAL( 10, 6 ) ), 2 )) total, 
        SUM(ROUND( fcd.cantidad * fcd.precio * CAST( fcd.iva /100 AS DECIMAL( 10, 6 ) ), 2 )) iva,
        sum(cantidad) cantidad FROM fcd, est WHERE fcd.id = '$busca' AND fcd.cantidad > 0 AND fcd.estudio = est.estudio");

    $Ddd = mysql_fetch_array($DddA);

    if ($Ddd[0] == 0) {
        $Cnt = 0;
        $Importe = 0;
        $Iva = 0;
        $Total = 0;
    } else {

        $Cnt = $Ddd[cantidad];
        $Importe = $Ddd[PrecioSinIva];
        $Iva = $Ddd[iva];
        $Total = $Ddd[total];
    }
    $lUp = mysql_query("UPDATE fc SET cantidad=$Cnt,importe = $Importe, iva=$Iva, total= $Total WHERE id=$busca");
}

mysql_close();
?>
