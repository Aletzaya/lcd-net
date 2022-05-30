<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require("lib/lib.php");
$link = conectarse();
$op = $_REQUEST[op];

if ($op === "buscar") {

    $sql = "SELECT ot.orden,ot.cliente,ot.status,otd.capturo,est.depto,otd.fr,otd.creapdf,otd.usrvalida "
            . "FROM ot,est,otd "
            . "WHERE ot.orden=otd.orden AND otd.estudio=est.estudio AND ot.orden=otd.orden AND ot.orden='$_POST[folio]' AND ot.cliente='$_POST[cliente]'";
    $cc = mysql_query($sql);
    $json = array();
    while ($rg = mysql_fetch_array($cc)) {
        echo $rg[fr];
        if ($rg[capturo] <> '' and $rg[fr] == '0') {
            if ($rg[depto] <> 2) {
                if ($rg[creapdf] == 'pdf') {
                    $json[] = array(
                        'folio' => $rg[orden],
                        'str' => "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('resultapdf.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>"
                    );
                } else {
                    $json[] = array(
                        'folio' => $rg[orden],
                        'str' => "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('resultapdf.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>"
                    );
                }
            } else {
                $json[] = array(
                    'folio' => $rg[orden],
                    'str' => "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> "
                );
            }
        } else {
            if ($rg[capturo] <> '' and $rg[usrvalida] <> '') {
                if ($rg[depto] <> 2) {
                    if ($rg[creapdf] == 'pdf') {
                        $json[] = array(
                            'folio' => $rg[orden],
                            'str' => "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#FF0000'></i></a></td>"
                        );
                    } else {
                        $json[] = array(
                            'folio' => $rg[orden],
                            'str' => "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$rg[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>"
                        );
                    }
                } else {
                    $json[] = array(
                        'folio' => $rg[orden],
                        'str' => "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> "
                    );
                }
            } else {
                $json[] = array(
                    'folio' => $rg[orden],
                    'str' => "<td align='center'>-</td>"
                );
            }
        }
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

mysql_close();
?>
