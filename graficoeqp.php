<?php

date_default_timezone_set("America/Mexico_City");

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Variables comunes;
$Fecha = date("Y-m-d");
$date = date("Y-m-d H:i:s");
$Msj = $_REQUEST["Msj"];

$CpoA = mysql_query("SELECT * FROM eqp");

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Equipos - Gráfico</title>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js"></script>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Grafica de equipos ::..
                    </td>
                </tr>
                <tr>
                    <td class='letratitulo' align="center" valign='top' width="45%">
                        <table width='99%' align='center' border='0' cellpadding='5' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo' align="center" colspan="3">..:: Datos de Estadistica ::..</td>
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Status
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                No. Eqp
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                %
                                </td></b>
                                </tr>  

                                <?php

                                $nRng=$Op=$Ob=$Fs=$Al=$Sr=$Bj=0;
                                $OpTotal=$Opm=$Opf=$Opt=$Opr=$Opc=$Ops=$Op11=$Op12=$Op13=0;
                                $ObTotal=$Obm=$Obf=$Obt=$Obr=$Obc=$Obs=$Ob11=$Ob12=$Ob13=0;
                                $FsTotal=$Fsm=$Fsf=$Fst=$Fsr=$Fsc=$Fss=$Fs11=$Fs12=$Fs13=0;
                                //$AlTotal=$Alm=$Alf=$Alt=$Alr=$Alc=$Als=$Al11=$Al12=$Al13=0;
                                $BjTotal=$Bjm=$Bjf=$Bjt=$Bjr=$Bjc=$Bjs=$Bj11=$Bj12=$Bj13=0;
                                $SrTotal=$Srm=$Srf=$Srt=$Srr=$Src=$Srs=$Sr11=$Sr12=$Sr13=0;

                                while ($Cpo = mysql_fetch_array($CpoA)) {

                                    switch($Cpo[status]) {
                                    case Optimo:
                                        
                                        switch($Cpo[sucursal]) {
                                        case 1:   
                                            $Opm++;
                                            break;
                                        case 2:  
                                            $Opf++;
                                            break;
                                        case 3:  
                                            $Opt++;
                                            break;
                                        case 4:  
                                            $Opr++;
                                            break;
                                        case 5:  
                                            $Opc++;
                                            break;
                                        case 6:  
                                            $Ops++;
                                            break;
                                        case 11:  
                                            $Op11++;
                                            break;
                                        case 12:  
                                            $Op12++;
                                            break;
                                        case 13:  
                                            $Op13++;
                                            break;
                                        }
                                        
                                        $Op++;
                                        break;
                                    case Observacion:
                                        
                                        switch($Cpo[sucursal]) {
                                        case 1:   
                                            $Obm++;
                                            break;
                                        case 2:  
                                            $Obf++;
                                            break;
                                        case 3:  
                                            $Obt++;
                                            break;
                                        case 4:  
                                            $Obr++;
                                            break;
                                        case 5:  
                                            $Obc++;
                                            break;
                                        case 6:  
                                            $Obs++;
                                            break;
                                        case 11:  
                                            $Ob11++;
                                            break;
                                        case 12:  
                                            $Ob12++;
                                            break;
                                        case 13:  
                                            $Ob13++;
                                            break;
                                        }
                                        
                                        $Ob++;
                                        break;
                                    case Fuera_de_Servicio:
                                        
                                        switch($Cpo[sucursal]) {
                                        case 1:   
                                            $Fsm++;
                                            break;
                                        case 2:  
                                            $Fsf++;
                                            break;
                                        case 3:  
                                            $Fst++;
                                            break;
                                        case 4:  
                                            $Fsr++;
                                            break;
                                        case 5:  
                                            $Fsc++;
                                            break;
                                        case 6:  
                                            $Fss++;
                                            break;
                                        case 11:  
                                            $Fs11++;
                                            break;
                                        case 12:  
                                            $Fs12++;
                                            break;
                                        case 13:  
                                            $Fs13++;
                                            break;
                                        }
                                        
                                        $Fs++;
                                        break;
                                    case Baja:

                                        switch($Cpo[sucursal]) {
                                        case 1:   
                                            $Bjm++;
                                            break;
                                        case 2:  
                                            $Bjf++;
                                            break;
                                        case 3:  
                                            $Bjt++;
                                            break;
                                        case 4:  
                                            $Bjr++;
                                            break;
                                        case 5:  
                                            $Bjc++;
                                            break;
                                        case 6:  
                                            $Bjs++;
                                            break;
                                        case 11:  
                                            $Bj11++;
                                            break;
                                        case 12:  
                                            $Bj12++;
                                            break;
                                        case 13:  
                                            $Bj13++;
                                            break;
                                        default:  
                                            $Bjm++;
                                            break;
                                        }
                                        
                                        $Bj++;
                                        break;
                                    default:

                                        switch($Cpo[sucursal]) {
                                        case 1:   
                                            $Srm++;
                                            break;
                                        case 2:  
                                            $Srf++;
                                            break;
                                        case 3:  
                                            $Srt++;
                                            break;
                                        case 4:  
                                            $Srr++;
                                            break;
                                        case 5:  
                                            $Src++;
                                            break;
                                        case 6:  
                                            $Srs++;
                                            break;
                                        case 11:  
                                            $Sr11++;
                                            break;
                                        case 12:  
                                            $Sr12++;
                                            break;
                                        case 13:  
                                            $Sr13++;
                                            break;
                                        default:  
                                            $Srm++;
                                            break;
                                        }

                                        $Sr++;
                                        break;
                                    }

                                    $nRng++;
                                    $OpTotal=$Opm+$Opf+$Opt+$Opr+$Opc+$Ops+$Op11+$Op12+$Op13;
                                    $ObTotal=$Obm+$Obf+$Obt+$Obr+$Obc+$Obs+$Ob11+$Ob12+$Ob13;
                                    $FsTotal=$Fsm+$Fsf+$Fst+$Fsr+$Fsc+$Fss+$Fs11+$Fs12+$Fs13;
                                    //$AlTotal=$Alm+$Alf+$Alt+$Alr+$Alc+$Als+$Al11+$Al12+$Al13;
                                    $BjTotal=$Bjm+$Bjf+$Bjt+$Bjr+$Bjc+$Bjs+$Bj11+$Bj12+$Bj13;
                                    $SrTotal=$Srm+$Srf+$Srt+$Srr+$Src+$Srs+$Sr11+$Sr12+$Sr13;

                                    $Totalm=$Opm+$Obm+$Fsm+$Bjm+$Srm;
                                    $Totalf=$Opf+$Obf+$Fsf+$Bjf+$Srf;
                                    $Totalt=$Opt+$Obt+$Fst+$Bjt+$Srt;
                                    $Totalr=$Opr+$Obr+$Fsr+$Bjr+$Srr;
                                    $Totalc=$Opc+$Obc+$Fsc+$Bjc+$Src;
                                    $Totals=$Ops+$Obs+$Fss+$Bjs+$Srs;
                                    $Total11=$Op11+$Ob11+$Fs11+$Bj11+$Sr11;
                                    $Total12=$Op12+$Ob12+$Fs12+$Bj12+$Sr12;
                                    $Total13=$Op13+$Ob13+$Fs13+$Bj13+$Sr13;

                                    $Totaltt=$Totalm+$Totalf+$Totalt+$Totalr+$Totalc+$Totals+$Total11+$Total12+$Total13;
                                }

                                $Opp=($Op*100)/$nRng;
                                $Obp=($Ob*100)/$nRng;
                                $Fsp=($Fs*100)/$nRng;
                                //$Alp=($Al*100)/$nRng;
                                $Bjp=($Bj*100)/$nRng;
                                $Srp=($Sr*100)/$nRng;
                                $Totp=$Opp+$Obp+$Fsp+$Bjp+$Srp;
                                $Extr=$Op3+$Ob3+$Fs3+$Bj3+$Sr3;
                                $Total=$Op+$Ob+$Fs+$Bj+$Sr;

                                ?>

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#239B56;' aria-hidden='true'></i> Optimo
                                </td>
                                <td align="center">
                                <?= $Op ?>
                                <?php $Opp=number_format($Opp,2); ?>
                                </td>
                                <td align="center">
                                <?= $Opp ?>
                                </td>
                                </tr>  

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#F39C12;' aria-hidden='true'></i> Observacion
                                </td>
                                <td align="center">
                                <?= $Ob ?>
                                <?php $Obp=number_format($Obp,2); ?>
                                </td>
                                <td align="center">
                                <?= $Obp ?>
                                </td>
                                </tr>

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#E74C3C;' aria-hidden='true'></i> Fuera de Servicio
                                </td>
                                <td align="center">
                                <?= $Fs ?>
                                <?php $Fsp=number_format($Fsp,2); ?>
                                </td>
                                <td align="center">
                                <?= $Fsp ?>
                                </td>
                                </tr>

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i> Baja
                                </td>
                                <td align="center">
                                <?= $Bj ?>
                                <?php $Bjp=number_format($Bjp,2); ?>
                                </td>
                                <td align="center">
                                <?= $Bjp ?>
                                </td>
                                </tr>

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#922B21;' aria-hidden='true'></i> S/R
                                </td>
                                <td align="center">
                                <?= $Sr ?>
                                <?php $Srp=number_format($Srp,2); ?>
                                </td>
                                <td align="center">
                                <?= $Srp ?>
                                </td>
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Tot_Gral
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Total ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= number_format($Totp,2) ?>
                                </td></b>
                                </tr>  
                            </tr>  
                        </table>
                        <br>
                        <table width='99%' align='center' border='0' cellpadding='5' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo' align="center" colspan="11">..:: Datos de Estadistica X Sucursal ::..</td>
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Status
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Mtrz
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Ohf
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Tpx
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Rys
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Cam
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Svc
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Ohf-Tr
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Ohf-Ur
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Ohf-Hp
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Total
                                </td></b>
                                </tr>  

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#239B56;' aria-hidden='true'></i> Optimo
                                </td>
                                <td align="center">
                                <?= $Opm ?>
                                </td>
                                <td align="center">
                                <?= $Opf ?>
                                </td>
                                <td align="center">
                                <?= $Opt ?>
                                </td>
                                <td align="center">
                                <?= $Opr ?>
                                </td>
                                <td align="center">
                                <?= $Opc ?>
                                </td>
                                <td align="center">
                                <?= $Ops ?>
                                </td>
                                <td align="center">
                                <?= $Op11 ?>
                                </td>
                                <td align="center">
                                <?= $Op12 ?>
                                </td>
                                <td align="center">
                                <?= $Op13 ?>
                                </td>
                                <td align="center">
                                <?= $OpTotal ?>
                                </td>                            
                                </tr>  

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#F39C12;' aria-hidden='true'></i> Observacion
                                </td>
                                <td align="center">
                                <?= $Obm ?>
                                </td>
                                <td align="center">
                                <?= $Obf ?>
                                </td>
                                <td align="center">
                                <?= $Obt ?>
                                </td>
                                <td align="center">
                                <?= $Obr ?>
                                </td>
                                <td align="center">
                                <?= $Obc ?>
                                </td>
                                <td align="center">
                                <?= $Obs ?>
                                </td>
                                <td align="center">
                                <?= $Ob11 ?>
                                </td>
                                <td align="center">
                                <?= $Ob12 ?>
                                </td>
                                <td align="center">
                                <?= $Ob13 ?>
                                </td>
                                <td align="center">
                                <?= $ObTotal ?>
                                </td>
                                </tr>

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#E74C3C;' aria-hidden='true'></i> Fuera de Servicio
                                </td>
                                <td align="center">
                                <?= $Fsm ?>
                                </td>
                                <td align="center">
                                <?= $Fsf ?>
                                </td>
                                <td align="center">
                                <?= $Fst ?>
                                </td>
                                <td align="center">
                                <?= $Fsr ?>
                                </td>
                                <td align="center">
                                <?= $Fsc ?>
                                </td>
                                <td align="center">
                                <?= $Fss ?>
                                </td>
                                <td align="center">
                                <?= $Fs11 ?>
                                </td>
                                <td align="center">
                                <?= $Fs12 ?>
                                </td>
                                <td align="center">
                                <?= $Fs13 ?>
                                </td>
                                <td align="center">
                                <?= $FsTotal ?>
                                </td>
                                </tr>

                                <tr class="letrap"  <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i> Baja
                                </td>
                                <td align="center">
                                <?= $Bjm ?>
                                </td>
                                <td align="center">
                                <?= $Bjf ?>
                                </td>
                                <td align="center">
                                <?= $Bjt ?>
                                </td>
                                <td align="center">
                                <?= $Bjr ?>
                                </td>
                                <td align="center">
                                <?= $Bjc ?>
                                </td>
                                <td align="center">
                                <?= $Bjs ?>
                                </td>
                                <td align="center">
                                <?= $Bj11 ?>
                                </td>
                                <td align="center">
                                <?= $Bj12 ?>
                                </td>
                                <td align="center">
                                <?= $Bj13 ?>
                                </td>
                                <td align="center">
                                <?= $BjTotal ?>
                                </td>
                                </tr>

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#922B21;' aria-hidden='true'></i> S/R
                                </td>
                                <td align="center">
                                <?= $Srm ?>
                                </td>
                                <td align="center">
                                <?= $Srf ?>
                                </td>
                                <td align="center">
                                <?= $Srt ?>
                                </td>
                                <td align="center">
                                <?= $Srr ?>
                                </td>
                                <td align="center">
                                <?= $Src ?>
                                </td>
                                <td align="center">
                                <?= $Srs ?>
                                </td>
                                <td align="center">
                                <?= $Sr11 ?>
                                </td>
                                <td align="center">
                                <?= $Sr12 ?>
                                </td>
                                <td align="center">
                                <?= $Sr13 ?>
                                </td>
                                <td align="center">
                                <?= $SrTotal ?>
                                </td>
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Tot_Gral
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totalm ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totalf ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totalt ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totalr ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totalc ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totals ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Total11 ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Total12 ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Total13 ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totaltt ?>
                                </td></b>
                                </tr>  
                            </tr>  
                        </table>    
                    </td>
                    <td class='letratitulo' align="center" valign='top'>
                        <table border='0' width='95%' align='center' cellpadding='1' cellspacing='2'>    
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo' align="center">..:: Gráfico Estadistico ::..</td>
                            </tr>
                            <tr>
                                <td class='letratitulo' valign='top' align="center" width='50%'>
                                    <canvas id="myChart" width="50" height="5"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart');
                                            var myChart = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Optimo <?= $Opp ?>  %  -  <?= $Op ?> Eqp','Observacion <?= $Obp ?> %  -  <?= $Ob ?> Eqp','Fuera_Serv <?= $Fsp ?>  %  -  <?= $Fs ?> Eqp','Baja <?= $Bjp ?>  %  -  <?= $Bj ?> Eqp','S/R <?= $Srp ?>  %  -  <?= $Sr ?> Eqp'],
                                                    datasets: [{
                                                            label: 'Estadistica General',
                                                            data: [<?= $Opp ?>,<?= $Obp ?>,<?= $Fsp ?>,<?= $Bjp ?>,<?= $Srp ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(243, 156, 18, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)',
                                                                'rgba(23, 32, 42, 0.8)',
                                                                'rgba(146, 43, 33, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(243, 156, 18, 1)',
                                                                'rgba(236, 46, 46, 1)',
                                                                'rgba(23, 32, 42, 1)',
                                                                'rgba(146, 43, 33, 1)'
                                                            ],
                                                            borderWidth: 3
                                                        }]
                                                },
                                                options: {
                                                responsive: true,
                                                plugins: {
                                                  legend: {
                                                    position: 'top',
                                                  },
                                                  title: {
                                                    display: true,
                                                    text: 'Estadistica General'
                                                  }
                                                }
                                              }
                                            });
                                        </script>
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