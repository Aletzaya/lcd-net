<?php
session_start();
require("lib/lib.php");
$link = conectarse();
$Sql = "SELECT ot.medicon,ot.folio,ot.fecpago,ot.servicio,ot.recepcionista,med.nombrec,ot.status,ot.recibio,entmos,entmosf,entmost,entmoslr,"
        . "entmoscms,entmosch,tentregamost,tentregamed,tentregainst FROM ot LEFT JOIN med ON ot.medico=med.medico WHERE orden = " . $_REQUEST["Orden"];
$result = mysql_query($Sql);
if (!$result) {
    echo "ERROR SQL " . $Sql;
}
$dt = mysql_fetch_array($result);
//echo print_r($dt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Avance en Ordenes</title>
            <link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <script src="js/jquery-1.8.2.min.js"></script>
            <script src="jquery-ui/jquery-ui.min.js"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </head>
    <body>
        <table width="95%">
            <tr>
                <td align="right">
                    <a href="concentradoOrdenes.php">Quitar</a>
                </td>
            </tr>
        </table> 
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <strong>Detalle del estudio no. <?= $_REQUEST["Orden"] ?></strong>
                    </button>
                </h2>
                <?php
                $Sql = "SELECT ot.orden,ot.institucion,cli.nombrec,ot.fecha,ot.hora,est.estudio,est.descripcion,ot.cliente,ot.entemailmed,ot.entemailinst,otd.usrest,otd.statustom,otd.alterno,otd.estudio,otd.capturo,otd.fr,otd.creapdf,otd.usrvalida,est.depto,otd.proceso
        FROM ot
        INNER JOIN otd on otd.orden=ot.orden
        INNER JOIN est on est.estudio=otd.estudio
        INNER JOIN cli on cli.cliente=ot.cliente
        WHERE ot.cliente=cli.cliente and ot.orden = " . $_REQUEST["Orden"];
                $result = mysql_query($Sql);
                ?>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <table border="1" style="width: 80%;" align="center">
                            <thead>
                                <tr bgcolor='#5499C7'>
                                    <td class="letra">id</td>
                                    <td class="letra">Estudio</td>
                                    <td class="letra">Hora</td>
                                    <td></td>
                                    <td class="letra">Imp</td>
                                    <td class="letra">SubeImg</td>
                                    <td class="letra">Msj</td>
                                    <td class="letra">Toma/Real</td>
                                    <td class="letra">Proceso</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rg = mysql_fetch_array($result)) {
                                    $clnk = strtolower($rg[estudio]);
                                    if (($nRng % 2) > 0) {
                                        $Fdo = 'FFFFFF';
                                    } else {
                                        $Fdo = "E2E2E2";
                                    }    //El resto de la division;
                                    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                                    echo "<td class='letrap'>$rg[id]</td>";
                                    echo "<td class='letrap'>$rg[estudio] </td><td class='letrap'>$rg[hora]</td>";
                                    echo "<td class='letrap' align='center'><a class='edit' href=javascript:winuni('impeti.php?op=1&busca=$rg[orden]&Est=$rg[estudio]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";

                                    if ($rg[capturo] <> '' and $rg[fr] == '0') {

                                        if ($rg[depto] <> 2) {
                                            if ($rg[creapdf] == 'pdf') {
                                                echo "<td class='letrap' align='right'><a class='edit' href=javascript:winuni('resultapdf.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                            } else {
                                                echo "<td class='letrap' align='right'><a class='edit' href=javascript:winuni('resultapdf.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                            }
                                        } else {
                                            echo "<td align='right'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> ";
                                        }
                                    } else {
                                        if ($rg[capturo] <> '' and $rg[usrvalida] <> '') {
                                            if ($rg[depto] <> 2) {
                                                if ($rg[creapdf] == 'pdf') {
                                                    echo "<td align='right'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#FF0000'></i></a></td>";
                                                } else {
                                                    echo "<td align='right'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$rg[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
                                                }
                                            } else {
                                                echo "<td align='right'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> ";
                                            }
                                        } else {
                                            echo "<td align='right' class='letrap'>-</td>";
                                        }
                                    }

                                    $ImgA = mysql_query("SELECT archivo FROM estudiospdf WHERE id='$rg[orden]' and usrelim=''");

                                    $Img = mysql_fetch_array($ImgA);
                                    if ($Img[archivo] <> '') {
                                        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('displayestudioslcdimg.php?op=1&busca=$rg[orden]&estudio=$rg[estudio]')><i class='fa fa-search fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                    } else {
                                        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('displayestudioslcdimg.php?op=1&busca=$rg[orden]&estudio=$rg[estudio]')><i class='fa fa-upload fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                    }

                                    Display($aCps, $aDat, $rg);

                                    if ($rg[entemailpac] == '1' or $rg[entemailmed] == '1' or $rg[entemailinst] == '1') {
                                        echo "<td align='center'><a class='pg' href=javascript:winmed('entregamail2.php?Orden=$rg[orden]')><i class='fa fa-envelope' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                    } else {
                                        echo "<td align='center'>&nbsp;</a></td>";
                                    }

                                    if ($rg[statustom] == 'TOMA/REALIZ') {
                                        echo "<td align='center'>$Gfont <font size='1'><b>" . ucwords(strtolower($rg[usrest])) . "</b></font></td>";
                                    } elseif ($rg[statustom] == 'PENDIENTE') {
                                        echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Op=1&Estudio=$rg[estudio]&Regis=1'><i class='fa fa-exclamation-triangle fa-lg' aria-hidden='true' style='color:#F4D03F'></i></a></td>";
                                    } else {
                                        echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Op=1&Estudio=$rg[estudio]&Regis=1'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                                    }

                                    if ($rg[proceso] <> '') {
                                        echo "<td align='center'>$Gfont <font size='1'><b>" . ucwords(strtolower($rg[proceso])) . "</b></font></td>";
                                    } else {
                                        echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Estudio2=$rg[estudio]&Op=Rec'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
                                    }

                                    echo "</tr>";

                                    $nRng++;
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <strong>Datos Generales</strong>
                    </button>
                </h2>

                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class = "accordion-body" align="center">
                        <strong>Medico : </strong> <?= $dt["medicon"] ?> <strong> No. Folio :</strong>  <?= $dt["folio"] ?>
                        <strong>Fecha de pago :</strong>  <?= $dt["fecpago"] ?> <strong>Tipo de Servicio : </strong><?= $dt["servicio"] ?><br></br>
                        <strong>Recepcionista : </strong><?= $dt["recepcionista"] ?> <strong>Medico:</strong> <?= $dt["nombrec"] ?>
                        <strong>Status :</strong> <?= $dt["status"] ?> <strong>Recibio : </strong><?= $dt["recibio"] ?><br></br>
                        <strong>Trazabilidad</strong>
                        <?php
                        if ($dt[entmos] == '1') {
                            $Sucentregam = 'Matriz';
                        }
                        if ($dt[entmosf] == '1') {
                            $Sucentregaf = 'H.Futura';
                        }
                        if ($dt[entmost] == '1') {
                            $Sucentregat = 'Tepexpan';
                        }
                        if ($dt[entmoslr] == '1') {
                            $Sucentregalr = 'Reyes';
                        }
                        if ($dt[entmoscms] == '1') {
                            $Sucentregacms = 'Camarones';
                        }
                        if ($dt[entmosch] == '1') {
                            $Sucentregach = 'Sn Vicente';
                        }

                        $Sucentrega = "<br></br><i class='fa fa-hospital-o fa-2x' aria-hidden='true'></i>  Entrega en Sucursal  -->" . ' ' . $Sucentregam . ' ' . $Sucentregaf . ' ' . $Sucentregat . ' ' . $Sucentregalr . ' ' . $Sucentregacms . ' ' . $Sucentregach . " ";


                        if ($dt[tentregamost] == '1') {
                            $PMIentregap = 'Paciente';
                        }
                        if ($dt[tentregamed] == '1') {
                            $PMIentregam = 'Medico';
                        }
                        if ($dt[tentregainst] == '1') {
                            $PMIentregai = 'Institucion';
                        }

                        $PMIentrega = " <br></br><i class='fa fa-file-pdf-o fa-2x' aria-hidden='true'></i> Entrega a  -->" . ' ' . $PMIentregap . ' ' . $PMIentregam . ' ' . $PMIentregai;
                        echo $Sucentrega . $PMIentrega;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
