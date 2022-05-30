<?php
#Librerias
session_start();
date_default_timezone_set("America/Mexico_City");
require("lib/lib.php");

$link = conectarse();

#Variables comunes;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$estudio = $_REQUEST["estudio"];
$busca = $_REQUEST["busca"];


$link = conectarse();
$pagina =$_REQUEST["pagina"];
$Institucion =$_REQUEST["Institucion"];
$Orden = $_REQUEST["Orden"];
$Cliente = $_REQUEST["cliente"];
$mailpac = $_REQUEST["mailpac"];
$mailmed = $_REQUEST["mailmed"];
$mailinst = $_REQUEST["mailinst"];
$mailotro = $_REQUEST["mailotro"];
$Op = $_REQUEST["Op"];
$Msj = $_REQUEST["Msj"];
$Estudio = $_REQUEST["Estudio"];
$alterno = $_REQUEST["alterno"];
$Reg = $_REQUEST["Reg"];
$Archivo = $_REQUEST["Archivo"];
$Usr = $check['uname'];
$FechaI =  $_REQUEST["FechaI"];        
$FechaF =  $_REQUEST["FechaF"]; 

$Fechaest = date("Y-m-d H:i:s");


if ($Orden > 0) {
    $sql = "SELECT * FROM ot WHERE orden = $Orden";
    $cSql = mysql_query($sql);
    if ($cc = mysql_fetch_array($cSql)) {

        $Sql = "INSERT INTO entrega_resultados (orden,detalle,fecha) "
                . "VALUES ('" . $Orden . "','Ingresan a ver resultados web inst','" . date("Y-m-d H:i:s") . "');";
        mysql_query($Sql);
    }
}

if (isset($_REQUEST["Archivo"])) {
    $cc = "INSERT INTO ingresos (cpu) VALUES ('$_SERVER[REMOTE_ADDR]');";
    mysql_query($cc);


    $img = $_REQUEST[Archivo];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($img));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($img));
    ob_clean();
    flush();
    readfile($img);
}
$HeA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.entemailpac,ot.entemailmed,
  ot.entemailinst
  FROM ot,cli
  WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

$He = mysql_fetch_array($HeA);
require ("config.php");


$Status = "SELECT ot.institucion, ot.pagada, cli.cliente, inst.condiciones
FROM cli, ot
LEFT JOIN inst ON ot.institucion = inst.institucion
WHERE ot.cliente = cli.cliente
AND ot.orden=$Orden";
$cStts = mysql_query($Status);
$cStatus = mysql_fetch_array($cStts);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Documento sin título</title><link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
            <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
            <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                <style type="text/css" >
                    body {
                        background-color: #F6F6F6;
                    }
                </style>
                </head>
                <body bgcolor='#FFFFFF' onload='cFocus()'>   

                    <?php
                    if ($cStatus[pagada] === "Si" OR $cStatus[condiciones] === "CREDITO") {
                        ?>
                        <table >
                            <tr style="height: 85px;">
                                <td>

                                </td>
                            </tr>
                        </table>
                        <table width="100%" style='border-collapse: collapse; border: 1px solid #5D6D7E;position:relative;'>
                            <tr bgcolor="#00518C">
                                <td style="width: 280px;">
                                    <img src='images/DuranNvo.png' title='Vista preliminar' border='0' >
                                </td>
                                <td>
                                    <table align="center" border="0" width="100%">
                                        <tr>
                                            <td colspan="2" style="width:1000px" align="center">
                                                <h1><p style="color:#D5D8DC;font-family:courier,arial,helvética;">Entrega de resultados</p></h1>
                                            </td>
                                            <td align="right" style="width:250px">
                                                <a href="indexcli.php"><i style="color:#EC7063" class="fa fa-window-close fa-2x" aria-hidden="true"></i></a>
                                            </td>
                                            <td style="width:25px"></td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <p style="color:#D5D8DC;font-family:courier,arial,helvética;">
                                                    <b>Paciente:</b> <?= ucwords(strtolower($He[nombrec])) ?>    
                                                </p>     
                                            </td>
                                            <td>
                                                <p style="color:#D5D8DC;font-family:courier,arial,helvética;">
                                                    <b>Orden no.</b> <?= $Orden ?>
                                                </p>
                                            </td>
                                            <td colspan="2">
                                                <p style="color:#D5D8DC;font-family:courier,arial,helvética;">
                                                    <b>Fecha de estudio:</b> <?= $He[fecha] ?>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table><tr style="height: 20px;"><td></td></tr></table>
                        <?php
                        $Sql = mysql_query("SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
  otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
  otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno,otd.fechaest,otd.fr,otd.usrvalida,otd.lugar
  FROM ot,est,otd,cli
  WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Orden'");

//$Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja FROM otd,est WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");

                        echo "<table align='center' width='68%' border='0' cellspacing='0' cellpadding='0' style='border-collapse: collapse; border: 1px solid #fff;position:relative;'>";
                        echo "<tr height='25' bgcolor='#CCCCCC' class='letrap'>";
                        echo "<td align='center'><b>Estudio</b></td>";
                        echo "<td align='center'><b>Descripcion</b></td>";
                        echo "<td align='center'><b>Descarga PDF</b></td>";
                        echo "<td align='center'><b>Img</b></td>";
                        echo "</tr>";

                        while ($rg = mysql_fetch_array($Sql)) {

                            $clnk = strtolower($rg[estudio]);
                            $Estudio2 = strtoupper($rg[estudio]);

                            $Lugar = $rg[lugar];

                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = $Gfdogrid;
                            }    //El resto de la division;

                            echo "<tr class='letrap' height='30' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";
                            echo "<td><b> &nbsp;  $rg[estudio]</b></td>";
                            echo "<td>$rg[descripcion]</td>";
                            $Rarchivo = $Orden . "_" . $Estudio2 . ".pdf";

                            if ($rg[capturo] <> '') {

                                if ($rg[depto] <> 2) {
    
                                    if ($rg[status] == 'TERMINADA' and $rg[usrvalida] <> '') {

                                        if($Institucion=='259'){

                                            echo "<td class='Seleccionar' align='center'><a href=javascript:wingral('resultapdfmc.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></a></td>";
                                            
                                        }else{

                                            echo "<td class='Seleccionar' align='center'><a href=javascript:wingral('resultapdf3.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></a></td>";

                                        }
    
                                    }else{
    
                                        echo "<td align='center'><a class='edit'><i class='fa fa-spinner fa-spin fa-2x fa-fw' style='color:#5D6D7E;'></i>En proceso</a></td>";
    
                                    }
    
                                }else{
                                    echo "<td align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o fa-2x' style='color:#0000FF' aria-hidden='true'></i></a></td> ";

                                //  echo "<td  align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> ";
    
                                }
                                    
                            } else {
    
                                echo "<td align='center'><a class='edit'><i class='fa fa-spinner fa-spin fa-2x fa-fw' style='color:#5D6D7E;'></i>En proceso</a></td>";
    
                            }

                            $ImgA = mysql_query("SELECT archivo FROM estudiospdf WHERE id='$rg[orden]' and usrelim=''");

                            $Img = mysql_fetch_array($ImgA);

                            if ($Img[archivo] <> '') {
                                echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('displayestudioslcdimgenv.php?op=1&busca=$rg[orden]&estudio=$rg[estudio]')><i class='fa fa-search fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                            } else {
                                echo "<td class='Seleccionar' align='center'> - </td>";
                            }

                            echo "</tr>";
                            $nRng++;
                        }

                        echo "</table>";
                        ?>
                        <table><tr><td><a href="entregaonlineInstitucion.php?busca=<?= $_REQUEST[busca] ?>&Institucion=<?=$Institucion?>&FechaI=<?=$FechaI?>&FechaF=<?=$FechaF?>&pagina=<?=$pagina?>" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a></td></tr></table>
                        <?php
                        echo "</table>";

                        echo "<br>";

                        echo "<br>";
                    } else {
                        ?>
                        <table >
                            <tr style="height: 75px;">
                                <td>

                                </td>
                            </tr>
                        </table>
                        <table width="100%" style='border-collapse: collapse; border: 1px solid #5D6D7E;position:relative;'>
                            <tr bgcolor="#00518C">
                                <td style="width: 280px;">
                                    <img src='images/DuranNvo.png' title='Vista preliminar' border='0' >
                                </td>
                                <td>
                                    <table align="center" border="0" width="100%">
                                        <tr>
                                            <td colspan="2" style="width:1000px" align="center">
                                                <h1><p style="color:#D5D8DC;font-family:courier,arial,helvética;">Entrega de resultados</p></h1>
                                            </td>
                                            <td align="right" style="width:250px">
                                                <a href="indexcli.php"><i style="color:#EC7063" class="fa fa-window-close fa-2x" aria-hidden="true"></i></a>
                                            </td>
                                            <td style="width:25px"></td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <p style="color:#D5D8DC;font-family:courier,arial,helvética;">
                                                    <b>Paciente:</b> <?= ucwords(strtolower($He[nombrec])) ?>    
                                                </p>     
                                            </td>
                                            <td>
                                                <p style="color:#D5D8DC;font-family:courier,arial,helvética;">
                                                    <b>Orden no.</b> <?= $Orden ?>
                                                </p>
                                            </td>
                                            <td colspan="2">
                                                <p style="color:#D5D8DC;font-family:courier,arial,helvética;">
                                                    <b>Fecha de estudio:</b> <?= $He[fecha] ?>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table align="center">
                            <tr>
                                <td>
                                    <p style="color:#FF0000;font-family:courier,arial,helvética;">
                                        Los archivos pdf y radiografias no pueden ser visualizador al tener adeudo.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <a href="entregaonlineInstitucion.php?busca=<?= $_REQUEST[busca] ?>&Institucion=<?=$Institucion?>&FechaI=<?=$FechaI?>&FechaF=<?=$FechaF?>&pagina=<?=$pagina?>" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a></td></tr></table>
                                </td>
                            </tr>
                        </table>
                        <table><tr style="height: 20px;"><td></td></tr></table>
                        <?php
                        $Sql = mysql_query("SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
  otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
  otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno,otd.fechaest,otd.fr,otd.usrvalida,otd.lugar
  FROM ot,est,otd,cli
  WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Orden'");

//$Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja FROM otd,est WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");

                        echo "<table align='center' width='68%' border='0' cellspacing='0' cellpadding='0' style='border-collapse: collapse; border: 1px solid #fff;position:relative;'>";
                        echo "<tr height='25' bgcolor='#CCCCCC' class='letrap'>";
                        echo "<td align='center'><b>Estudio</b></td>";
                        echo "<td align='center'><b>Descripcion</b></td>";
                        echo "<td align='center'><b>Descarga PDF</b></td>";
                        echo "</tr>";

                        while ($rg = mysql_fetch_array($Sql)) {

                            $clnk = strtolower($rg[estudio]);
                            $Estudio2 = strtoupper($rg[estudio]);

                            $Lugar = $rg[lugar];

                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = $Gfdogrid;
                            }    //El resto de la division;

                            echo "<tr class='letrap' height='30' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";
                            echo "<td><b> &nbsp;  $rg[estudio]</b></td>";
                            echo "<td>$rg[descripcion]</td>";
                            $Rarchivo = $Orden . "_" . $Estudio2 . ".pdf";


                       
                            if ($rg[status] <> '' and $rg[usrvalida] <> '') {
                                if ($rg[depto] <> 2) {
                                    echo "<td align='center'><a><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></a></td>";
                                } else {
                                    echo "<td align='center'><a><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></a></td> ";
                                }
                            } else {
                                if ($rg[depto] <> 2) {
                                    echo "<td align='center'><a class='edit'><i class='fa fa-spinner fa-spin fa-2x fa-fw' style='color:#5D6D7E;'></i>En proceso</a></td>";
                                } else {

                                    
                                    if ($rg[status] == 'TERMINADA') {
                                        echo "<td align='center'><a><i class='fa fa-file-pdf-o fa-2x' style='color:#FF0000' aria-hidden='true'></i></a></td> ";
                                    } else {
                                        echo "<td align='center'><a class='edit'><i class='fa fa-spinner fa-spin fa-2x fa-fw' style='color:#5D6D7E;'></i>En proceso</a></td>";
                                    }
                                }
                            }


                            echo "</tr>";
                            $nRng++;
                        }

                        echo "</table>";

                        echo "</table>";

                        echo "<br>";
                    }

                    echo "</body>";

                    echo "</html>";

                    mysql_close();
                    ?> 