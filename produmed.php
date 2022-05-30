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

$CpoA = mysql_query("");

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Productividad</title>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js"></script>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
                <tr>
                    <td class='letratitulo' align="center" valign='top' width="45%">
                        <table width='99%' align='center' border='1' cellpadding='5' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo' align="center" colspan="3">..:: Datos Estadisticos ::..</td>
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Usg_Convencional


                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Total de Estudios
                                </td></b>
                                </tr>  

                                <?php

                                $nRng=$Op=$Ob=$Fs=$Al=$Sr=$Bj=0;
                                $OpTotal=$Opm=$Opf=$Opt=$Opr=$Opc=$Ops=0;
                                $ObTotal=$Obm=$Obf=$Obt=$Obr=$Obc=$Obs=0;
                                $FsTotal=$Fsm=$Fsf=$Fst=$Fsr=$Fsc=$Fss=0;
                                //$AlTotal=$Alm=$Alf=$Alt=$Alr=$Alc=$Als=$Al11=$Al12=$Al13=0;
                                $BjTotal=$Bjm=$Bjf=$Bjt=$Bjr=$Bjc=$Bjs=0;
                                $SrTotal=$Srm=$Srf=$Srt=$Srr=$Src=$Srs=0;

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

                                        }

                                        $Sr++;
                                        break;
                                    }

                                    $nRng++;
                                    $OpTotal=$Opm+$Opf+$Opt+$Opr+$Opc+$Ops;
                                    $ObTotal=$Obm+$Obf+$Obt+$Obr+$Obc+$Obs;
                                    $FsTotal=$Fsm+$Fsf+$Fst+$Fsr+$Fsc+$Fss;
                                    //$AlTotal=$Alm+$Alf+$Alt+$Alr+$Alc+$Als+$Al11+$Al12+$Al13;
                                    $BjTotal=$Bjm+$Bjf+$Bjt+$Bjr+$Bjc+$Bjs;
                                    $SrTotal=$Srm+$Srf+$Srt+$Srr+$Src+$Srs;

                                    $Totalm=$Opm+$Obm+$Fsm+$Bjm+$Srm;
                                    $Totalf=$Opf+$Obf+$Fsf+$Bjf+$Srf;
                                    $Totalt=$Opt+$Obt+$Fst+$Bjt+$Srt;
                                    $Totalr=$Opr+$Obr+$Fsr+$Bjr+$Srr;
                                    $Totalc=$Opc+$Obc+$Fsc+$Bjc+$Src;
                                    $Totals=$Ops+$Obs+$Fss+$Bjs+$Srs;

                                    $Totaltt=$Totalm+$Totalf+$Totalt+$Totalr+$Totalc+$Totals;
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
                                </tr>  

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#F39C12;' aria-hidden='true'></i> Observacion
                                </td>
                                <td align="center">
                                <?= $Ob ?>
                                <?php $Obp=number_format($Obp,2); ?>
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
                                </tr>

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i> Baja
                                </td>
                                <td align="center">
                                <?= $Bj ?>
                                <?php $Bjp=number_format($Bjp,2); ?>
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
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Tot_Gral
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Total ?>
                                </td></b>
                                </tr>  
                            </tr>  
                        </table>
                        <br>
                        <table width='98%' align='center' border='1' cellpadding='5' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo' align="center" colspan="11">..::Estadistica X Dia Medicos ::..</td>
                                </tr>

                                <tr class="letrap">

                                <td align="center" <?= $BgColor ?>><b>
                                Usg_Convencional=  <?= $Total ?>

                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Total
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
                                </tr>  

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#239B56;' aria-hidden='true'></i> Optimo
                                </td>
                                <td align="center">
                                <?= $OpTotal ?>
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
                            
                                </tr>  

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#F39C12;' aria-hidden='true'></i> Observacion
                                </td>
                                <td align="center">
                                <?= $ObTotal ?>
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
                            
                                </tr>

                                <tr class="letrap" <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#E74C3C;' aria-hidden='true'></i> Fuera de Servicio
                                </td>
                                <td align="center">
                                <?= $FsTotal ?>
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
                                </tr>

                                <tr class="letrap"  <?= $BgColor ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i> Baja
                                </td>
                                <td align="center">
                                <?= $BjTotal ?>
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
                          
                                </tr>

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#922B21;' aria-hidden='true'></i> S/R
                                </td>
                                <td align="center">
                                <?= $SrTotal ?>
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
                          
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Tot_Gral
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= $Totaltt ?>
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

                                </tr>  
                            </tr>  
                        </table>    
                    </td>
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