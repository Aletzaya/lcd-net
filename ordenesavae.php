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

$op = $_REQUEST[op];
$Fecha = date("Y-m-d H:m:s");
$busca = $_REQUEST[busca];


if ($_REQUEST[bt] === "Actualizar") {
    $sql = "UPDATE ot SET receta='$_REQUEST[Receta]',fecharec='$_REQUEST[Fecharec]',descuento='$_REQUEST[Descuento]',diagmedico='$_REQUEST[Diagmedico]',"
            . "observaciones='$_REQUEST[Observaciones]',fechae='$_REQUEST[Fechae]',horae='$_REQUEST[Horae]'"
            . " WHERE ot.orden='$busca'";
    if (!mysql_query($sql)) {
        $Msj = "Error de sintaxis mysql" . $sql;
    }
} elseif ($_REQUEST[bt] === "Cambiar") {
    header("Location: clientese.php?busca=$_REQUEST[IdExt]&rg=ord&idext=$busca");
}






if ($op == "download") {

    $sql = "SELECT facturas.pdf_format, facturas.uuid FROM facturas WHERE facturas.id_fc_fk = $busca";

    error_log($sql);
    $result = mysql_query($sql);
    while ($myrowsel = mysql_fetch_array($result)) {
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename='$myrowsel[uuid].pdf'");
        echo $myrowsel[pdf_format];
        exit();
    }
} elseif ($op == "xml") {

    $sql = "SELECT facturas.cfdi_xml, facturas.uuid FROM facturas WHERE facturas.id_fc_fk = $busca";

    error_log($sql);
    $result = mysql_query($sql);
    $rg = mysql_fetch_array($result);
    $fn = $rg[cfdi_xml];
    header("Content-Disposition: attachment; filename=\"" . $fn . "\";");
    header('Content-Type: text/xml');
    readfile($fn);    /*
      while ($myrowsel = mysql_fetch_array($result)) {
      header("Content-Type: application/xml");
      header("Content-Disposition: attachment; filename='$myrowsel[uuid].xml'");
      echo $myrowsel[cfdi_xml];
      exit();
      }
     */
}
#Variables comunes;
// $sCpo = "SELECT ot.cliente,ot.fecha,ot.hora,ot.receta,ot.fecharec,ot.diagmedico,ot.observaciones,ot.servicio,ot.pagada,cli.nombrec,ot.medico,med.nombrec as nombremedico,ot.orden,ot.fechae,ot.importe,ot.recepcionista,ot.suc,ot.entfec,ot.recibio,ot.horae,ot.descuento FROM ot,cli LEFT JOIN med ON ot.medico=med.id WHERE ot.orden='$busca' AND ot.medico=med.id";

$sCpo = "SELECT ot.orden,ot.fecha,ot.fecharec,ot.hora,ot.fechae,ot.cliente idex,cli.nombrec as nombrecli,ot.importe,ot.descuento,ot.receta,ot.recepcionista,
    ot.ubicacion,ot.institucion,ot.medico,med.nombrec as nombremedico,ot.status,ot.recibio,ot.institucion,ot.diagmedico,cli.telefono,ot.status,
    ot.pagada,ot.observaciones,ot.horae,cli.fechan,ot.suc,ot.recepcionista,ot.idprocedencia,ot.responsableco,ot.servicio,cli.sexo,cli.numveces
    FROM ot,cli,med
    WHERE ot.cliente=cli.cliente AND ot.medico=med.medico AND ot.orden='$busca'";


$CpoA = mysql_query($sCpo);
$Cpo = mysql_fetch_array($CpoA);

if ($Cpo[sexo] === 'F') {
    $sexo = "Femenino";
} else {
    $sexo = "Masculino";
}
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
            <title>Recepción - Consulta OT's</title>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
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
                    ..:: Informacion Principal de: <?= $Cpo[orden] . ' ' . ucwords(strtolower($Cpo[nombrecli])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="4">
                                ..:: Datos personales ::..
                            </td>
                        </tr>
                        <tr><td height="7px" colspan="4"></td></tr>
                        <tr class="letrap" style="height: 30px">
                            <td><b>Nombre :</b> <?= $Cpo[nombrecli] ?> </td><td><b> Sexo : </b> <?= $sexo ?> </td>
                            <td><b> Telefono : </b> <?= $Cpo[telefono] ?> </td><td>No. Visitas :<a class='content1' href=javascript:winuni('repots.php?busca=<?= $Cpo[idex] ?>')><?= $Cpo[numveces]; ?></a></td>
                        </tr>
                        <tr><td height="7px" colspan="4"></td></tr>
                        <tr class="letrap"><td colspan="1"><b>Recepcionista :</b> <?= $Cpo[recepcionista] ?> </td><td><b>Status : </b><?= $Cpo[status] ?> </td><td colspan="2"><b> Descuento : </b><?= $Cpo[descuento] ?></td></tr>
                        <tr><td height="7px" colspan="4"></td></tr>
                    </table>
                    <br></br>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="9">
                                ..:: Facturado ::..
                            </td>
                        </tr>
                        <tr class="letrap" style="height: 30px">
                            <td><b>Orden</b></td>
                            <td align='center' ><b>pdf </b></td>
                            <td align='center'><b>xml </b></td>
                            <td><b>Fecha de orden</b></td>
                            <td><b>Folio</b></td>
                            <td><b>Cliente<b></td>
                                        <td colspan='2' aling='center'><b>Envios<b></td>
                                                    </tr>

                                                    <?php
                                                    $sql = "SELECT ot.orden, ot.fecha, fcd.id FROM ot LEFT JOIN fcd ON fcd.orden = ot.orden "
                                                            . "WHERE ot.orden = $Cpo[orden]";
                                                    $cSql = mysql_query($sql);

//echo $sql;
                                                    ?>

                                                    <?php
                                                    while ($cc = mysql_fetch_array($cSql)) {

                                                        $cSqlH = "SELECT clif.nombre
                            FROM clif,fc
                            WHERE clif.id=fc.cliente and fc.id='$cc[id]'";

                                                        $HeA = mysql_query($cSqlH);
                                                        $He = mysql_fetch_array($HeA);

                                                        if (is_numeric($cc[id])) {
                                                            $nvo = $cc[id];

                                                            if ($factura <> $cc[id]) {


                                                                if (($nRng % 2) > 0) {
                                                                    $Fdo = '#FFFFFF';
                                                                } else {
                                                                    $Fdo = '#D5D8DC';
                                                                }
                                                                if (is_numeric($cc[id])) {
                                                                    $nvo = $cc[id];
                                                                } else {
                                                                    $nvo = "<i class='fa fa-times fa-gb' style='color:red;' aria-hidden='true'></i>";
                                                                }
                                                                ?>
                                                                <tr class="letrap" bgcolor="<?= $Fdo ?>">
                                                                    <td><?= $cc[orden] ?></td>
                                                                    <td align='center'><a href=javascript:winuni('?busca=<?= $nvo ?>&op=download')><img src='lib/Pdf.gif' alt='Imprime copia pdf' border='0'></a></td>
                                                                    <td align='center'><a href=javascript:winuni('?busca=<?= $nvo ?>&op=xml')><i class="fa fa-file-excel-o" alt='Imprime copia pdf' border='0'></a></i></td>
                                                                    <td><?= $cc[fecha] ?></td>
                                                                    <td><?= $nvo ?></td>
                                                                    <td><?= $He[nombre] ?></td>
                                                                    <td><a class='content1' href=javascript:winuni('facturase.php?busca=<?= $cc[id] ?>')><b>Correo</a></td>
                                                                    <td><a class='content1' href=javascript:winuni('facturase1.php?busca=<?= $cc[id] ?>')><b>Correo Anterior</a></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <?php
                                                        $factura = $cc[id];
                                                        $nRng++;
                                                    }
                                                    ?> 

                                                    <tr style="background-color:  #2c8e3c">
                                                        <td class='letratitulo'align="center" colspan="9">
                                                            ..:: Historial Facturado ::..
                                                        </td>
                                                    </tr>
                                                    <tr class="letrap" style="height: 30px">

                                                        <td><b>Orden</b></td>
                                                        <td><b></b></td>
                                                        <td><b></b></td>
                                                        <td><b>Fecha de orden</b></td>
                                                        <td><b></b></td>
                                                        <td><b>Folio</b></td>
                                                        <td colspan='2' align='center'><b> - </b></td>
                                                    </tr>
                                                    <?php
                                                    /*
                                                      $sql = "SELECT ot.orden, ot.fecha, fcd.id "
                                                      . "FROM ot "
                                                      . "LEFT JOIN fcd ON fcd.orden = ot.orden "
                                                      . "LEFT JOIN fc ON fcd.id = fc.id "
                                                      . "WHERE ot.orden in "
                                                      . "(SELECT orden FROM ot WHERE cliente = "
                                                      . "(SELECT cliente FROM ot WHERE ot.orden = $Cpo[orden]))";
                                                      $cSql = mysql_query($sql);
                                                     */
//echo $sql;
                                                    ?>

                                                    <?php
                                                    while ($cc = mysql_fetch_array($cSql)) {
                                                        if (is_numeric($cc[id])) {
                                                            $nvo = $cc[id];
                                                        } else {
                                                            $nvo = "<i class='fa fa-times fa-bg' style='color:red;' aria-hidden='true'></i>";
                                                        }
                                                        if (($nRng % 2) > 0) {
                                                            $Fdo = '#FFFFFF';
                                                        } else {
                                                            $Fdo = '#D5D8DC';
                                                        }
                                                        ?>
                                                        <tr class="letrap" bgcolor="<?= $Fdo ?>">
                                                            <td><?= $cc[orden] ?></td>
                                                            <td><b></b></td>
                                                            <td><b></b></td>
                                                            <td><?= $cc[fecha] ?></td>
                                                            <td><b></b></td>
                                                            <td><?= $nvo ?></td>
                                                        </tr>
                                                        <?php
                                                        $nRng++;
                                                    }
                                                    ?>
                                                    </table>




                                                    <?php
                                                    if ($Cpo[observaciones] !== "") {
                                                        ?>
                                                        <br></br>
                                                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                                            <tr style="background-color: #2c8e3c">
                                                                <td class='letratitulo'align="center">
                                                                    ..:: Observaciones ::..
                                                                </td>
                                                            </tr>
                                                            <tr class="letrap" style="height: 30px">
                                                                <td>
                                                                    <b>Observaciones .-</b> <?= $Cpo[observaciones] ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <?php
                                                    }
                                                    ?>
                                                    <br></br>
                                                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                                        <tr style="background-color: #2c8e3c">
                                                            <td class='letratitulo'align="center" colspan="2">
                                                                ..:: Ingresos a resultados en linea ::..
                                                            </td>
                                                        </tr>
                                                        <tr class="letrap" style="height: 30px">
                                                            <td><b>Fecha</b></td>
                                                            <td><b>Detalle</b></td>
                                                        </tr>
                                                        <?php
                                                        $Sql = mysql_query("SELECT * FROM entrega_resultados WHERE orden =" . $busca);

                                                        while ($cc = mysql_fetch_array($Sql)) {
                                                            ?>
                                                            <tr class="letrap">
                                                                <td><?= $cc["fecha"] ?></td>
                                                                <td><?= $cc["detalle"] ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table>
                                                    </td>
                                                    <td valign='top' width='45%'>
                                                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                                                            <tr style="background-color: #2c8e3c">
                                                                <td class='letratitulo'align="center" colspan="1">
                                                                    ..:: Detalle ::..
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <table align="center" cellpadding="3" cellspacing="2" width="97%" border='0'>
                                                                        <tr>
                                                                            <td height="5px" colspan="4">
                                                                            </td>
                                                                        </tr>
                                                                        <tr bgcolor="#A7C2FC" class="letrap" align="center">
                                                                            <td height="22px"><b>Estudio</b></td>
                                                                            <td><b>Precio</b></td>
                                                                            <td><b>Descto %</b></td>
                                                                            <td><b>Importe</b></td>
                                                                            <td><b>Envio</b></td>
                                                                            <td><b>Lugar</b></td>
                                                                        </tr>   
                                                                        <?php
                                                                        $aLug = array('Etiqueta', 'Etiqueta', 'Proceso', 'Captura', 'Impresion', 'Recepcion', 'Entregado');
                                                                        $sql = "SELECT est.descripcion,otd.estudio,otd.precio,otd.status,otd.descuento,otd.lugar,"
                                                                                . "otd.precio-(otd.precio*otd.descuento)/100 as importe,otd.recibeencaja "
                                                                                . "FROM otd INNER JOIN est ON otd.estudio = est.estudio  WHERE orden='$busca'";
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
                                                                                <td>
                                                                                    <?= $cSql[descripcion] ?>
                                                                                </td>
                                                                                <td align="right">
                                                                                    <?= number_format($cSql[precio], 2) ?>
                                                                                </td>
                                                                                <td align="center">
                                                                                    <?= $cSql[descuento] ?>
                                                                                </td>
                                                                                <td  align="right">
                                                                                    <?= number_format($cSql[importe], 2) ?>
                                                                                </td>
                                                                                <?php
                                                                                if ($cSql[recibeencaja] === "") {
                                                                                    ?>
                                                                                    <td align="center"><i class="fa fa-times fa-2x" style="color:red;" aria-hidden="true"></i></td>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <td  align="center">
                                                                                        <?= $cSql[recibeencaja] ?>
                                                                                    </td>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <td  align="center">
                                                                                    <?= $aLug[$cSql[lugar]] ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                            $sumP = $sumP + $cSql[precio];
                                                                            $sumPi = $sumPi + $cSql[importe];
                                                                            $nRng++;
                                                                        }
                                                                        ?>
                                                                        <tr class="letrap">
                                                                            <td align="right"><b>Totales: ---> </
                                                                                    b></td>
                                                                            <td align="right"><b>
                                                                                    $  <?= number_format($sumP, 2) ?>
                                                                                </b></td>
                                                                            <td></td>
                                                                            <td align="right"><b>
                                                                                    $  <?= number_format($sumPi, 2) ?>
                                                                                </b></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <br></br>
                                                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                                            <tr style="background-color: #2c8e3c">
                                                                <td class='letratitulo'align="center" colspan="5">
                                                                    ..:: Status de pago ::..
                                                                </td>
                                                            </tr>
                                                            <tr class="letrap"  style="height: 30px">
                                                                <td colspan="2" align="center"><b>Importe</b></td>
                                                                <td colspan="2" align="center"><b>Abonado</b></td>
                                                                <td align="center"><b>Saldo</b></td>
                                                            </tr>
                                                            <?php
                                                            $cSqlA = mysql_query("select sum(importe) from cja where orden='$busca'");
                                                            $SqlS = mysql_fetch_array($cSqlA);

                                                            $cSqlH = "select ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,ot.medico,med.nombrec from ot,cli,med where ot.cliente=cli.cliente and ot.medico=med.medico and ot.orden='$busca'";

                                                            $HeA = mysql_query($cSqlH);
                                                            $He = mysql_fetch_array($HeA);

                                                            $Abonos = $He[importe] - $Sqls[0];
                                                            $saldo = $He[importe] - $SqlS[0];
                                                            ?>
                                                            <tr class="letrap">
                                                                <td align="center" colspan="2"><?= number_format($He[importe], '2') ?></td>
                                                                <td align="center" colspan="2"><?= number_format($SqlS[0], '2') ?></td>
                                                                <?php
                                                                if ($saldo == 0) {
                                                                    ?>
                                                                    <td align="center">Pagado <i class="fa fa-check-square-o fa-2x" style="color:green;" aria-hidden="true"></i></td>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <td align="center"><?= number_format($He[importe] - $SqlS[0], '2') ?> <i class="fa fa-minus-square-o fa-2x" style="color:red;" aria-hidden="true"></i></td>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr style="background-color:  #2c8e3c">
                                                                <td class='letratitulo'align="center" colspan="5">
                                                                    ..:: Pagos ::..
                                                                </td>
                                                            </tr>
                                                            <tr class="letrap" style="height: 30px">
                                                                <td align="center"><b>Fecha</b></td>
                                                                <td align="center"><b>Hora</b></td>
                                                                <td align="center"><b>Importe</b></td>
                                                                <td align="center"><b>Tipo de pago</b></td>
                                                                <td align="center"><b>Usr</b></td>
                                                            </tr>
                                                            <?php
                                                            $cSqlH = "SELECT importe,fecha,hora,tpago,usuario FROM cja WHERE orden='$busca'";
                                                            $HeA = mysql_query($cSqlH);
                                                            while ($He = mysql_fetch_array($HeA)) {
                                                                ?>
                                                                <tr class="letrap">
                                                                    <td align="center"><?= $He[fecha] ?></td>
                                                                    <td align="center"><?= $He[hora] ?></td>
                                                                    <td align="center"><?= number_format($He[importe]) ?></td>
                                                                    <td align="center"><?= $He[tpago] ?></td>
                                                                    <td align="center"><?= $He[usuario] ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </table>
                                                        <br></br>
                                                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                                            <tr style="background-color: #2c8e3c">
                                                                <td class='letratitulo'align="center" colspan="4">
                                                                    ..:: Entregada ::..
                                                                </td>
                                                            </tr>
                                                            <tr class="letrap" style="height: 30px">
                                                                <td><b>Estudio</b></td>
                                                                <td><b>Descripcion</b></td>
                                                                <td><b>Fecha/Hora</b></td>
                                                                <td><b>Usr</b></td>
                                                            </tr>
                                                            <?php
                                                            $Sql = mysql_query("SELECT ot.fecha,ot.hora,otd.estudio,est.descripcion FROM ot,est,otd"
                                                                    . " WHERE ot.orden=otd.orden AND otd.estudio=est.estudio AND otd.orden='$busca'");
//echo $sql;

                                                            while ($cc = mysql_fetch_array($Sql)) {
                                                                $cons = "SELECT * FROM logenvio WHERE logenvio.orden='$busca' and logenvio.estudio='$cc[estudio]'";
                                                                //echo $cons;
                                                                $reg = mysql_query($cons);
                                                                $rst = mysql_fetch_array($reg);

                                                                if (isset($rst[usr])) {
                                                                    $usr1 = $rst[usr];
                                                                } else {
                                                                    $usr1 = "<i class='fa fa-times fa-2x' style='color:red;' aria-hidden='true'></i>";
                                                                }
                                                                ?>
                                                                <tr class="letrap">
                                                                    <td><?= $cc[estudio] ?></td>
                                                                    <td><?= $cc[descripcion] ?></td>
                                                                    <td><?= $rst[fecha] . " " . $rst[hora] ?></td>
                                                                    <td><?= $usr1 ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </table>

                                                        <br></br>
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <a href="ordenesava.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                                                                </td>
                                                            </tr>
                                                        </table> 
                                                    </td>
                                                    </tr>      
                                                    </table> 
                                                    </body>
                                                    </html>
                                                    <?php
                                                    mysql_close();
                                                    ?>

