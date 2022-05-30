<?php

session_start();
include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");


require("lib/lib.php");
date_default_timezone_set("America/Mexico_City");


$link = conectarse();
$Orden = $_REQUEST[Orden];
$Cliente = $_REQUEST[cliente];
$mailpac = $_REQUEST[mailpac];
$mailmed = $_REQUEST[mailmed];
$mailinst = $_REQUEST[mailinst];
$mailotro = $_REQUEST[mailotro];
$Op = $_REQUEST[Op];
$Msj = $_REQUEST[Msj];
$Estudio = $_REQUEST[Estudio];
$alterno = $_REQUEST[alterno];
$Reg = $_REQUEST[Reg];
$Archivo = $_REQUEST[Archivo];
$Usr = $check['uname'];
$Fechaest = date("Y-m-d H:i:s");


if ($_REQUEST[Reg] <> '') {

    $ImagenesB = mysql_fetch_array(mysql_query("SELECT * FROM envimg WHERE envimg.orden='$Orden' and envimg.reg='$Reg' and envimg.archivo='$Archivo'"));

    if ($_REQUEST[Reg] == $ImagenesB[reg]) {


        $cSqlE = mysql_query("DELETE FROM envimg WHERE envimg.orden='$Orden' and envimg.reg='$Reg' and envimg.archivo='$Archivo' limit 1");
    } else {

        if ($_REQUEST[Reg] == 'Todoimg') {

            $cSqlE = mysql_query("DELETE FROM envimg WHERE envimg.orden='$Orden'");

            $ImgB = mysql_query("SELECT archivo,idnvo FROM estudiospdf WHERE id='$Orden' and usrelim=''");

            $Reg2 = 1;

            while ($rowb = mysql_fetch_array($ImgB)) {

                $lUp = mysql_query("INSERT INTO envimg (orden,reg,archivo) VALUES ('$Orden','$Reg2','$rowb[archivo]')");

                $Reg2++;
            }
        } elseif ($_REQUEST[Reg] == 'Todoimgquitar') {

            $cSqlE = mysql_query("DELETE FROM envimg WHERE envimg.orden='$Orden'");
        } else {

            $lUp = mysql_query("INSERT INTO envimg (orden,reg,archivo) VALUES ('$Orden','$Reg','$Archivo')");
        }
    }
}

//************ PDF

if ($_REQUEST[Reg3] <> '') {

    $ImagenesB = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Archivo'"));

    if ($_REQUEST[Archivo] == $ImagenesB[archivo]) {

        $cSqlE = mysql_query("DELETE FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Archivo' limit 1");

        unlink("estudiospdf/" . $Archivo);
    } else {

        if ($_REQUEST[Reg3] == 'Todoarch') {

            $cSqlE = mysql_query("DELETE FROM enviarc WHERE enviarc.orden='$Orden'");

            $Sql = mysql_query("SELECT * FROM otd WHERE otd.orden='$Orden' and otd.status='TERMINADA'");

            $Reg2 = 1;

            while ($rowb = mysql_fetch_array($Sql)) {

                $Rarchivo = $Orden . "_" . $rowb[estudio] . ".pdf";

                $lUp = mysql_query("INSERT INTO enviarc (orden,reg,archivo) VALUES ('$Orden','$Reg2','$Rarchivo')");

                $Reg2++;
            }
        } elseif ($_REQUEST[Reg3] == 'Todoarchquitar') {

            $cSqlE = mysql_query("DELETE FROM enviarc WHERE enviarc.orden='$Orden'");

            $Sql = mysql_query("SELECT * FROM otd WHERE otd.orden='$Orden' and otd.status='TERMINADA'");

            while ($rowb = mysql_fetch_array($Sql)) {

                $Rarchivo = $Orden . "_" . $rowb[estudio] . ".pdf";

                unlink("estudiospdf/" . $Rarchivo);
            }
        } else { //////////////////*****************   OJO
            $lUp = mysql_query("INSERT INTO enviarc (orden,reg,archivo,estudio) VALUES ('$Orden','1','$Archivo','$_REQUEST[Estudio]')");

            $Sql = mysql_query("SELECT depto FROM est WHERE est.estudio='$_REQUEST[Estudio]'");

            $rowc = mysql_fetch_array($Sql);

            if ($rowc[depto] <> 2) {

                if($_REQUEST[Institucion]=='259'){
                    header("Location: resultapdfenvioadjmc.php?Orden=$Orden&Estudio=$_REQUEST[Estudio]&alterno=$_REQUEST[alterno]");

                }else{
                    header("Location: resultapdfenvioadj.php?Orden=$Orden&Estudio=$_REQUEST[Estudio]&alterno=$_REQUEST[alterno]");
                }

            } else {

                header("Location: resultapdfenvioadjrx.php?Orden=$Orden&Estudio=$_REQUEST[Estudio]&alterno=$_REQUEST[alterno]");
            }
        }
    }
}


$Gfont = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='1'>";
$Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";

switch ($Op) {
    case 'eps':
        $Up = mysql_query("UPDATE ot SET entemailpac='1'
				WHERE orden='$Orden'");
        break;
    case 'epn':
        $Up = mysql_query("UPDATE ot SET entemailpac='0'
				WHERE orden='$Orden'");
        break;

    case 'ems':
        $Up = mysql_query("UPDATE ot SET entemailmed='1'
				WHERE orden='$Orden'");
        break;
    case 'emn':
        $Up = mysql_query("UPDATE ot SET entemailmed='0'
				WHERE orden='$Orden'");
        break;

    case 'eis':
        $Up = mysql_query("UPDATE ot SET entemailinst='1'
				WHERE orden='$Orden'");
        break;
    case 'ein':
        $Up = mysql_query("UPDATE ot SET entemailinst='0'
				WHERE orden='$Orden'");
        break;

    case 'actpac':
        $Up = mysql_query("UPDATE cli SET cli.mail='$mailpac'
				  WHERE cli.cliente='$Cliente'");
        break;

//    case 'Envio':
//        break;
}
require ("config.php");          //Parametros de colores;

?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Envio de Resultados</title><link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body bgcolor='#FFFFFF' onload='cFocus()'>   

        <font size='3'><p align='center'><b>Entrega de resultados E-mail</b></p>
            <?php
            $HeA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.entemailpac,ot.entemailmed,
  ot.entemailinst
  FROM ot,cli
  WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

            $He = mysql_fetch_array($HeA);
            ?>
            <div align='center'> 
                <font size='+2'><?= $He[nombrec];?></font>
            </div>
            <div align='center'> 
                Fecha: <?= $He[fecha] ?> Hra: <?= $He[hora] ?> Fecha de entrega: <?= $He[fechae] ?>  No.ORDEN: <?= $He[institucion] ?> - <?= $Orden ?>
            </div> 
            <table align='center' width='60%' border='0' cellspacing='0' cellpadding='1'>
                <tr height='25' bgcolor='#ABB2B9'>
                    <td align='center'><b>&nbsp; </b></td>
                    <td align='center'><a class='sbmenu'><b>E-mail </b></a></td>
                    <td align='center'><b>Actualiza </b></td>
                    <td align='center'><b>Envio </b></td> 
                </tr>
                <?php
                $OtdC = mysql_query("SELECT cli.cliente,cli.nombrec,cli.mail
			  FROM ot,cli
			  WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

                $rgp = mysql_fetch_array($OtdC);

                $OtdM = mysql_query("SELECT med.nombrec,med.mail
			  FROM ot,med
			  WHERE ot.orden='$Orden' AND ot.medico=med.medico");

                $rgm = mysql_fetch_array($OtdM);

                $OtdI = mysql_query("SELECT inst.nombre,inst.mail
			  FROM ot,inst
			  WHERE ot.orden='$Orden' AND ot.institucion=inst.institucion");

                $rgi = mysql_fetch_array($OtdI);
                ?>
                <tr class='letrap' style="height: 30px;">
                    <form name='form2' method='post' action='entregamail2.php?Orden=<?= $Orden ?>&Op=actpac'>
                        <td align='left'>
                            Paciente: <input type='hidden' class='letrap' name='cliente' value='<?= $rgp[cliente] ?>'>
                        </td>
                        <td align='center'>
                            <input name='mailpac' value='<?= $rgp[mail] ?>' type='email' size='60'>
                        </td>
                        <td align='center'>
                            <INPUT TYPE='SUBMIT' class='letrap' name='Actualiza' value='Actualiza'>
                        </td>
                    </form>
                    <?php
                    if ($He[entemailpac] == '1') {
                        echo "<td align='center'><a href='entregamail2.php?Orden=$Orden&Op=epn'><i class='fa fa-check-circle' aria-hidden='true' style='color:#2ECC71'></i></a></td>";
                    } else {
                        echo "<td align='center'><a class='edit' href='entregamail2.php?Orden=$Orden&Op=eps'>NO</a></td>";
                    }
                    ?>
                </tr>
                <tr class='letrap' style="height: 30px;">
                    <td align='left'>Medico:</td>
                    <td align='center'><?= $rgm[mail] ?></td>
                    <td align='center'></td>
                    <?php
                    if ($He[entemailmed] == '1') {
                        echo "<td align='center'><a href='entregamail2.php?Orden=$Orden&Op=emn'><i class='fa fa-check-circle' aria-hidden='true' style='color:#2ECC71'></i></a></td>";
                    } else {
                        echo "<td align='center'><a class='edit' href='entregamail2.php?Orden=$Orden&Op=ems'>NO</a></td>";
                    }
                    ?>

                </tr>
                <tr class='letrap' style="height: 30px;">
                    <td align='left'>Institucion:</td>
                    <td align='center'><?= $rgi[mail] ?></td>
                    <td align='center'></td>
                    <?php
                    if ($He[entemailinst] == '1') {
                        echo "<td align='center'><a href='entregamail2.php?Orden=$Orden&Op=ein'><i class='fa fa-check-circle' aria-hidden='true' style='color:#2ECC71'></i></a></td>";
                    } else {
                        echo "<td align='center'><a class='edit' href='entregamail2.php?Orden=$Orden&Op=eis'>NO</a></td>";
                    }
                    ?>
                </tr>   
            </table>
            <p></p>
            <?php
            $Sql = mysql_query("SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
  otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
  otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno,otd.fechaest,otd.fr,otd.usrvalida,otd.lugar
  FROM ot,est,otd,cli
  WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Orden'");
            ?>

            <table align='center' width='68%' border='1' cellspacing='0' cellpadding='0'>
                <tr height='25' bgcolor='#CCCCCC' class='letrap'>
                    <td align='center'>Estudio</td>
                    <td align='center'>Descripcion</td>
                    <td align='center'>Adjuntar</font></td>
                    <td align='center'>Resultado</td>   
                    <td align='center'>Envio</td>
                    <td align='center'>Fecha/hora</td>
                    <td align='center'>Envio</td>
                </tr> 
                <?php
                $nRng=0;
                $nRng2=0;
                while ($rg = mysql_fetch_array($Sql)) {

                    $clnk = strtolower($rg[estudio]);
                    $Estudio2 = strtoupper($rg[estudio]);

                    $Lugar = $rg[lugar];

                    if (($nRng % 2) > 0) {
                        $Fdo = 'FFFFFF';
                    } else {
                        $Fdo = $Gfdogrid;
                    }    //El resto de la division;

                    echo "<tr class='letrap' height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";
                    echo "<td>$Gfont $rg[estudio]</td>";
                    echo "<td>$Gfont $rg[descripcion]</td>";

                    $Rarchivo = $Orden . "_" . $Estudio2 . ".pdf";

                    if ($rg[status] == 'TERMINADA' and $rg[usrvalida] <> '') {


                        if ($rg[depto] <> 2) {
                            //********* adjuntar
                            $ImagenesD = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Rarchivo'"));

                            if (isset($ImagenesD[archivo])) {

                                $Seleccionado2 = "<i class='fa fa-check-circle' aria-hidden='true' style='color:#2ECC71'></i>";
                            } else {

                                $Seleccionado2 = "Adjuntar";
                            }

                            echo "<td align='center'><a class='edit' href='entregamail2.php?Orden=$Orden&Reg3=A&Archivo=$Rarchivo&alterno=$rg[alterno]&Estudio=$rg[estudio]&Institucion=$rg[institucion]'>".$Seleccionado2."<font></a></td>";

                            //********* 
                            if($rg[institucion]=='259'){

                                echo "<td align='center'><a href=javascript:winmed('resultapdfmc.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]');><font size='1'><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true'></i></font></a></td>";


                            }else{

                                echo "<td align='center'><a href=javascript:winmed('resultapdf3.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]');><font size='1'><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true'></i></font></a></td>";

                            }
                        } else {
                            //********* adjuntar
                            $ImagenesD = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Rarchivo'"));

                            if (isset($ImagenesD[archivo])) {

                                $Seleccionado2 = "<i class='fa fa-check-circle' aria-hidden='true' style='color:#2ECC71'></i>";
                            } else {

                                $Seleccionado2 = "Adjuntar";
                            }

                            echo "<td align='center'><a class='edit' href='entregamail2.php?Orden=$Orden&Reg3=A&Archivo=$Rarchivo&Estudio=$rg[estudio]'>".$Seleccionado2."<font></a></td>";

                            //********* 

                                echo "<td align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' title='VistaPre'></i></a></td> ";
                        }
                    } else {

                        if ($rg[depto] <> 2) {

                            echo "<td align='center'>-</td>";

                            echo "<td align='center'>-</td>";
                        } else {

                            if ($rg[status] == 'TERMINADA') {
                                //********* adjuntar
                                $ImagenesD = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Rarchivo'"));

                                if (isset($ImagenesD[archivo])) {

                                    $Seleccionado2 = "<i class='fa fa-check-circle' aria-hidden='true' style='color:#2ECC71'></i>";
                                } else {

                                    $Seleccionado2 = "Adjuntar";
                                }

                                echo "<td align='center'><a class='edit' href='entregamail2.php?Orden=$Orden&Reg3=A&Archivo=$Rarchivo&Estudio=$rg[estudio]'>$Seleccionado2<font></a></td>";

                                //********* 
                                echo "<td align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' title='Vista preliminar'></i></a></td> ";
                            } else {

                                echo "<td align='center'>-</td>";
                                echo "<td align='center'>-</td>";
                            }
                        }

//        echo "<td align='center'>-</td>";
                    }

                    $reenviado = 'Enviar';
                    $cons = "SELECT * FROM logenvio WHERE logenvio.orden='$Orden' and logenvio.estudio='$rg[estudio]' order by id desc";
                    $reg = mysql_query($cons);
                    if (!$regenvio = mysql_fetch_array($reg)) {

                        $reenviado = 'Enviar';
                    } else {

                        $reenviado = 'Reenviar';
                        $nRng2++;
                    }

                    if ($rg[status] == 'TERMINADA') {

                        if ($rg[depto] == 2) {

                            echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='resultapdfenvio3.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&alterno=$rg[alterno]&correo=$rgp[mail]&correom=$rgm[mail]&correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'>$reenviado</a></td>";
                        } else {


                          if($He[institucion]=='259'){

                            echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='resultapdfenviomc.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&alterno=$rg[alterno]&correo=$rgp[mail]&correom=$rgm[mail]&correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'>$reenviado</a></td>";


                          }else{

                            echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='resultapdfenvio2.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&alterno=$rg[alterno]&correo=$rgp[mail]&correom=$rgm[mail]&correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'>$reenviado</a></td>";
                         
                          }

                        }

                    }else{
                        echo "<td align='center'>-</td>";

                    }
                    echo "<td align='center'>$Gfont2 $regenvio[fecha]</td>";
                    echo "<td align='center'>$Gfont2 $regenvio[usr]</td>";
                    echo "</tr>";
                    $nRng++;
                }

                $Difestudios    =   $nRng - $nRng2;

                if($Op == 'Envio'){


                    if($Difestudios == '0'){

                        $Up = mysql_query("UPDATE ot SET ot.stenvmail='ENVIADO' WHERE ot.orden=$Orden");

                    }else{

                        $Up = mysql_query("UPDATE ot SET ot.stenvmail='PARCIAL' WHERE ot.orden=$Orden");

                    }
                }
                ?>
            </table> 
            <table align='center' width='68%' border='0' cellspacing='0' cellpadding='1'>
                <tr height='25' border='0'>
                    <td align='center'></td>
                    <td align='center'></td>
                    <td align='center'> 
                        <a class='edit' href='enviocorreoadj.php?Orden=<?= $Orden ?>&Correo=<?= $rgp[mail] ?>&Correom=<?= $rgm[mail] ?>&Correoi=<?= $rgi[mail] ?>&entemailpac=<?= $He[entemailpac] ?>&entemailmed=<?= $He[entemailmed] ?>&entemailinst=<?= $He[entemailinst] ?>'>
                            <font size='2'><b>Envio de resultados Adjuntos</b></font>
                        </a>
                    </td>
                    <td align='center'></td> 
                    <td align='center'></td>
                    <td align='center'></td>
                    <td align='center'></td>
                </tr>
            </table>
            <div align='center'>
                <font color='#990000' size='+2'><b><?= $Msj ?></b></font>
            </div>
            <p></p>
            <div align='center'>
                <font size='4'><b>Imagenes de los Estudios</b></font>
            </div>
            <table width='90%' border='0' align='center' cellpadding='2' cellspacing='1' bgcolor='#FFFFFF'>
                <tr align='center'><td>
                        <font size='2'><a class='edit' href=javascript:wingral('displayestudioslcdimgenv.php?busca=<?= $Orden ?>')>Visor de Imagenes</a></font>
                    </td></tr>
            </table> 
            <table width='90%' border='0' align='center' cellpadding='2' cellspacing='1' bgcolor='#FFFFFF'>
                <tr align='left'><td>
                        <?php
                        if ($_REQUEST[Reg] <> 'Todoimg') {

                            echo "<font size='2'><a class='edit' href='entregamail2.php?Orden=$Orden&Reg=Todoimg'>Adjuntar todas las imagenes</a></font>";
                        } else {

                            echo "<font size='2'><a href='entregamail2.php?Orden=$Orden&Reg=Todoimgquitar' class='edit'>Quitar todas las imagenes</a></font>";
                        }
                        ?>
                    </td></tr>
            </table> 
            <table width='90%' border='0' align='center' cellpadding='2' cellspacing='1' bgcolor='#FFFFFF' style='border-collapse: collapse; border: 1px solid #999;'>
                <tr>
                    <?php
                    $R = 0;
                    $ImgA = mysql_query("SELECT archivo,idnvo FROM estudiospdf WHERE id='$Orden' and usrelim=''");

                    while ($row = mysql_fetch_array($ImgA)) {
                        $Pos = strrpos($row[archivo], ".");
                        $cExt = strtoupper(substr($row[archivo], $Pos + 1, 4));
                        $foto = $row[archivo];
                        $R++;

                        $ImagenesC = mysql_fetch_array(mysql_query("SELECT * FROM envimg WHERE envimg.orden='$Orden' and envimg.reg='$R'"));

                        if (isset($ImagenesC[reg])) {

                            $Seleccionado = "<i class='fa fa-check-circle' aria-hidden='true' style='color:#2ECC71'></i>";
                        } else {

                            $Seleccionado = " ";
                        }

                        if (($R % 2) > 0) {
                            $Fdo = $Gbarra;
                        }    //El resto de la division;

                        if ($cExt == 'PDF') {

                            echo "<td align='center' onMouseOver=this.style.backgroundColor='$Fdo';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#FFFFFF';><a class='pg' href='entregamail2.php?Orden=$Orden&Reg=$R&Archivo=$row[archivo]'><i class='fa fa-file-pdf-o fa-2x' aria-hidden='true' title='Vista preliminar'>".$Seleccionado."<br>$Gfont $R - $row[archivo] <font></i></a></td>";
                        } else {

                            echo "<td align='center' onMouseOver=this.style.backgroundColor='$Fdo';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#FFFFFF';><a class='pg' href='entregamail2.php?Orden=$Orden&Reg=$R&Archivo=$row[archivo]'><IMG SRC='http://lcd-system.com/lcd/estudios/$foto' border='0' width='70'>".$Seleccionado."<br>$Gfont $R - $row[archivo] <font></a></td>";
                        }


                        if ($R == 5 or $R == 10 or $R == 15 or $R == 20 or $R == 25 or $R == 30 or $R == 35 or $R == 40 or $R == 45 or $R == 50 or $R == 55 or $R == 60 or $R == 65 or $R == 70 or $R == 75 or $R == 80 or $R == 85 or $R == 90 or $R == 95 or $R == 100 or $R == 105 or $R == 110 or $R == 115 or $R == 120 or $R == 125 or $R == 130 or $R == 135 or $R == 140 or $R == 145 or $R == 150 or $R == 155 or $R == 160 or $R == 165 or $R == 170 or $R == 175 or $R == 180 or $R == 185 or $R == 190 or $R == 195 or $R == 200 or $R == 205 or $R == 210 or $R == 215 or $R == 220 or $R == 225 or $R == 230 or $R == 235 or $R == 240 or $R == 245 or $R == 250 or $R == 255 or $R == 260 or $R == 265 or $R == 270 or $R == 275 or $R == 280 or $R == 285 or $R == 290 or $R == 295) {
                            echo "</tr>";
                            echo "<tr>";
                        }
                    }
                    ?>
                </tr>
            </table>
            <table width='90%' border='0' align='center'>
                <tr align='center'>
                    <td>
                        <a class='edit' href='enviocorreoimg.php?Orden=<?= $Orden ?>&Correo=<?= $rgp[mail] ?>&Correom=<?= $rgm[mail] ?>&Correoi=<?= $rgi[mail] ?>&entemailpac=<?= $He[entemailpac] ?>&entemailmed=<?= $He[entemailmed] ?>&entemailinst=<?= $He[entemailinst] ?>'>
                            <font size='2'><b>Envio de Imagenes</b></font>
                        </a>
                    </td>
                </tr>
                <tr align='right'>
                    <td>
                        <a class='edit' href='enviocorreotodo.php?Orden=<?= $Orden ?>&Correo=<?= $rgp[mail] ?>&Correom=<?= $rgm[mail] ?>&Correoi=<?= $rgi[mail] ?>&entemailpac=<?= $He[entemailpac] ?>&entemailmed=<?= $He[entemailmed] ?>&entemailinst=<?= $He[entemailinst] ?>'><font size='3'><b>Envio de Archivos Adjuntos</b></font></a>
                    </td></tr>
            </table>                            

            <div align='center'>
                <a href=javascript:winmed2('logenvio.php?busca=<?= $Orden ?>') class='edit'><font size='2'> *** Envios ***</a>
            </div>

    </body>
</html>
<?php
mysql_close();
?> 