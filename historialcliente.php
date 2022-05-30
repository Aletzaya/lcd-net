<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
$Cliente=$_REQUEST[cliente];

require ("config.php");          //Parametros de colores;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
        <title>Historial del Cliente</title>
        <?php require ("./config_add.php"); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
        <tr style="background-color: #2c8e3c">
            <td class='letratitulo'align="center" colspan="2">
                .:: Estudios Realizados ::.
            </td>
        </tr>

        <tr>
            <td colspan="2" align="center">
                <table width="98%" style="margin-top: 10px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                    <tr class="letrap" align="center">
                        <td><b>Fecha</b></td>
                        <td><b>No. de Orden</b></td>
                        <td><b>Estudio</b></td>
                        <td><b>Descripcion</b></td>
                        <td><b>Resultado</b></td>
                    </tr>
                    <?php
                    $OtdA = mysql_query("SELECT ot.institucion,ot.orden, ot.fecha,otd.estudio,est.descripcion,est.depto,otd.alterno,otd.capturo,otd.status
                        FROM ot,otd LEFT JOIN est ON otd.estudio=est.estudio
                        WHERE ot.cliente = '$Cliente' AND ot.orden=otd.orden order by ot.orden desc");

                    while ($reg = mysql_fetch_array($OtdA)) {

                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;

                        $clnk = strtolower($reg[estudio]);

                        echo "<tr class='letrap' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        //echo "<td align='center'><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
                        echo "<td align='center'>$reg[fecha]</font></td>";
                        echo "<td align='center'>$reg[institucion] - $reg[orden]</font></td>";
                        echo "<td>$reg[estudio]</font></td>";
                        echo "<td>$reg[descripcion]</font></td>";

                        if ($reg[capturo] <> '') {
                            if ($reg[depto] <> 2) {
                                if($reg[status]=='TERMINADA'){
                                     echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral2('resultapdf.php?cInk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&alterno=$reg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                }else{
                                    echo "<td class='Seleccionar' align='center'><font size='1' color='red'><b>Valid_Pend<b></font></td>";
                                }
                                //echo "<td align='center'><a class='pg' href=javascript:wingral2('estdeptoimp.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$reg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                            } else {
                                echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral2('pdfradiologia.php?busca=$reg[orden]&Estudio=$reg[estudio]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                            }
                        } else {
                            echo "<td align='center'>-</td>";
                        }
                            echo "</tr>";
                            $nRng++;
                        }//fin while
                        ?>
                    </tr>
                </table>
                <br/>
            </td>
        </tr>
    </table>
    <br>
</body>
</html>
