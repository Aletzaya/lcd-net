<?php
#Librerias
session_start();

require("lib/lib.php");

$link = conectarse();

#Variables comunes;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$estudio = $_REQUEST[estudio];
$busca = $_REQUEST[busca];
$op = $_REQUEST[op];
$Fecha = date("Y-m-d");

if ($_REQUEST[Boton] == "Guardar") {  //Guarda el Movto de Notas
    $Fecha = date("Y-m-d");
    $Hora = date("H:i");

    $lUp = mysql_query("UPDATE otd SET texto = '$_REQUEST[Texto]',status='TERMINADA',
     		 medico = '$_REQUEST[Medico]',tres = '$Fecha $Hora', lugar='4', fechaest = '$Fecha $Hora', statustom = 'TOMA/REALIZ',capturo='$Gusr'
     		 WHERE orden='$busca' AND estudio='$estudio' limit 1");

    $NumA1 = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$busca' AND otd.statustom='PENDIENTE'");

    $NumA2 = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$busca' AND otd.statustom=''");

    if (mysql_num_rows($NumA1) == 0 and mysql_num_rows($NumA2) == 0) {
        $lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$busca'");
    } else {
        if (mysql_num_rows($NumA1) >= 1) {
            $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$busca'");
        } else {
            $lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$busca'");
        }
    }

    $NumA = mysql_query("SELECT otd.estudio 
		   FROM otd 
		   WHERE otd.orden='$busca' AND otd.capturo=''");

    if (mysql_num_rows($NumA) == 0) {
        $lUp = mysql_query("UPDATE ot SET captura='Si' WHERE orden='$busca'");
    }
}

if ($_REQUEST[Boton2] == "Guardar") {  //Guarda el Movto de Notas
    $Fecha = date("Y-m-d");
    $Hora = date("H:i");

    $lUp = mysql_query("UPDATE otd SET texto2 = '$_REQUEST[Texto]'
     		 WHERE orden='$busca' AND estudio='$estudio' limit 1");
}

if ($_REQUEST["op"] === "Descarga") {
    header("Content-disposition: attachment; filename=" . $_REQUEST["name"]);
    header("Content-type: MIME");
    readfile("../lcd/estudios/" . $_REQUEST["name"]);
}

$cc = "SELECT descripcion,proceso,formato,respradiologia,dobleinterpreta FROM est WHERE estudio='$estudio'";
$EstA = mysql_query($cc);
$Est = mysql_fetch_array($EstA);
$OtA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.horae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,ot.observaciones,
    inst.nombre as nombrei,cli.sexo,cli.nombrec,cli.fechan,med.nombrec as nombremed,otd.texto,otd.letra,
    otd.medico as medicores,otd.texto2, ot.institucion, ot.suc, ot.recepcionista, ot.medicon
    FROM ot,inst,cli,med,otd
    WHERE  ot.orden='$busca' AND ot.cliente=cli.cliente 
    AND ot.institucion = inst.institucion AND ot.medico=med.medico AND otd.orden=ot.orden AND otd.estudio='$estudio'");

$Ot = mysql_fetch_array($OtA);

if ($Ot["sexo"]=='M') {
    $Sexo = "Masculino";
} elseif ($Ot["sexo"]=='F') {
    $Sexo = "Femenino";
} else {
    $Sexo = "";
}

$Fechanac = $Ot["fechan"];
$FechaCompleta = date("Y-m-d H:i:s");
$array_nacimiento = explode("-", $Fechanac);
$array_actual = explode("-", $Fecha);
$anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias = $array_actual[2] - $array_nacimiento[2]; // calculamos días 

if ($dias < 0) {
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
    switch ($array_actual[1]) {
        case 1: $dias_mes_anterior = 31;
            break;
        case 2: $dias_mes_anterior = 31;
            break;
        case 3: $dias_mes_anterior = 28;
            break;
        case 4: $dias_mes_anterior = 31;
            break;
        case 5: $dias_mes_anterior = 30;
            break;
        case 6: $dias_mes_anterior = 31;
            break;
        case 7: $dias_mes_anterior = 30;
            break;
        case 8: $dias_mes_anterior = 31;
            break;
        case 9: $dias_mes_anterior = 31;
            break;
        case 10: $dias_mes_anterior = 30;
            break;
        case 11: $dias_mes_anterior = 31;
            break;
        case 12: $dias_mes_anterior = 30;
            break;
    }

    $dias = $dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses 
if ($meses < 0) {
    --$anos;
    $meses = $meses + 12;
}

require ("config.php");          //Parametros de colores;

$Suc=$Ot[suc];

$sucnombre = $aSucursal[$Suc];

  //**********  Iconos de Tipo de  Porcedencia  ***************//

if ($Cpo["idprocedencia"] == 'ambulancia') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}elseif ($Cpo["idprocedencia"] == 'silla') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}elseif ($Cpo["idprocedencia"] == 'terceraedad') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}elseif ($Cpo["idprocedencia"] == 'problemasv') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:RED" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:RED" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
} elseif ($Cpo["idprocedencia"] == 'bebe') {
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:RED" aria-hidden="true"></i>';
}else{
    $idprocedencia='<i class="fa fa-ambulance fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia2='<i class="fa fa-wheelchair fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia3='<i class="fa fa-blind fa-2x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia4='<i class="fa fa-deaf fa-2x" style="color:GREEN" aria-hidden="true"></i><i class="fa fa-eye-slash fa-1x" style="color:GREEN" aria-hidden="true"></i>';
    $idprocedencia5='<i class="fa fa-child fa-2x" style="color:GREEN" aria-hidden="true"></i>';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
            <title>Interpretación de estudio</title>
            <?php require ("./config_add.php"); ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="css/froala_editor.css">
            <link rel="stylesheet" href="css/froala_style.css">
            <link rel="stylesheet" href="css/plugins/code_view.css">
            <link rel="stylesheet" href="css/plugins/draggable.css">
            <link rel="stylesheet" href="css/plugins/colors.css">
            <link rel="stylesheet" href="css/plugins/emoticons.css">
            <link rel="stylesheet" href="css/plugins/image_manager.css">
            <link rel="stylesheet" href="css/plugins/image.css">
            <link rel="stylesheet" href="css/plugins/line_breaker.css">
            <link rel="stylesheet" href="css/plugins/table.css">
            <link rel="stylesheet" href="css/plugins/char_counter.css">
            <link rel="stylesheet" href="css/plugins/video.css">
            <link rel="stylesheet" href="css/plugins/fullscreen.css">
            <link rel="stylesheet" href="css/plugins/file.css">
            <link rel="stylesheet" href="css/plugins/quick_insert.css">
            <link rel="stylesheet" href="css/plugins/help.css">
            <link rel="stylesheet" href="css/third_party/spell_checker.css">
            <link rel="stylesheet" href="css/plugins/special_characters.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
            <link rel="stylesheet" href="css/slider.css">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

            <style>
            .mySlides {
              position: relative;
              width: 800px;
              height: 600px;
            }
            </style>
    </head>
    <body>

    <?php
    $nomcliente = ucwords(strtolower(substr($Ot[nombrec],0,50)));
    ?>
    <table border='0' width='97%' align='center' cellpadding='1' cellspacing='4'>    
        <tr>
            <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Orden: <?= $Ot["institucion"] ?> - <?= $busca ?> - <?= $nomcliente ?> ::..
            </td>
        </tr>
        <tr>
            <td valign='top' align='center' height='130' width='100%'>
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 1px solid #999;'>  
                    <tr style="background-color: #2c8e3c">
                        <td class='letratitulo' align="center" colspan="5">
                            ..:: Datos principales ::..
                        </td>
                    </tr>

        <! –– Primer reglon Intitucion ––>

                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm">
                            <strong>Institucion : </strong><br>
                            <div align="center"><?= $Ot["institucion"] . " - " . $Ot["nombrei"] ?></div>
                        </td>
                        <?php
                        $urgente1='';
                        $urgente2='';
                        if($Ot["servicio"]=='Urgente'){
                            $colorserv='red';
                            $urgente1='<strong>';
                            $urgente2='</strong>';
                        }
                        ?>
                        <td width='18%' class="ssbm" align="lefth">
                                <strong>
                                 Servicio : 
                                </strong><br>
                                <div align="center"><?= $urgente1 ?><font color='<?= $colorserv ?>'>
                                <?= $Ot["servicio"] ?> </font> <?= $urgente2 ?></div>
                        </td>


                        <td width='18%' class="ssbm" align="lefth">
                            <strong>Sucursal : </strong><br>
                            <div align="center"><?= $Suc ?> - <?= $sucnombre ?></div>
                        </td>


                        <td width='18%' class="ssbm" align="lefth">
                            <strong>Capturó : </strong><br>
                            <div align="center"><?= $Ot["recepcionista"] ?></div>
                        </td>

                        <td width='18%' class="ssbm" align="lefth">
                            <strong>Fech. Orden : </strong><br>
                            <div align="center"><?= $Ot["fecha"] . " " . $Ot["hora"] ?></div>
                        </td>
                    </tr>

        <! –– Segundo reglon Paciente ––>

                    <tr style="height: 30px" class="letrap">
                        <td width='28%' class="ssbm" align="lefth">
                            <strong>
                             Paciente : 
                            </strong><br>
                            <div align="center"><strong><?= $Ot["cliente"] . " - " . $nomcliente ?></strong></div>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <strong>
                             Genero : 
                            </strong><br>
                            <div align="center"><?= $Sexo ?></div>
                        </td>

                        <td width='18%' class="ssbm" align="lefth">
                            <strong>
                             Fech. Nacim. : 
                            </strong><br>
                            <div align="center"><?= $Ot["fechan"] ?></div>
                        </td>

                        <td width='18%' class="ssbm" align="lefth">
                            <strong>
                             Edad : 
                            </strong><br>
                            <div align="center"><?= $anos . " Años " . $meses . " Meses" ?></div>
                        </td>

                        <td width='18%' class="ssbm" align="lefth">
                            <strong>
                             Fech. Entrega : 
                            </strong><br>
                            <div align="center"><?= $Ot["fechae"] . " " . $Ot["horae"] ?></div>
                        </td>
                    </tr>

        <! –– Tercer reglon Diagnostico ––>

                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm" colspan="2">
                            <strong>Diagnóstico : </strong><br>
                            <div align="center"><?= $Ot["diagmedico"] ?></div>
                        </td>
                        <td width='18%' align="lefth" class="ssbm" colspan="3">
                             <strong>Observaciones : </strong><br>
                            <div align="center"><?= $Ot["observaciones"] ?></div>
                        </td>
                    </tr>

        <! –– Cuarto reglon Médico ––>

                    <?php
                    if($Cpo["medico"]=='MD'){
                        $nommedico = ucwords(strtolower(substr($Ot[medicon],0,50)));
                    }else{
                        $nommedico = ucwords(strtolower(substr($Ot[nombremed],0,50)));
                    }
                    ?>
                    <tr style="height: 30px" class="letrap">
                        <td width='28%' align="lefth" class="ssbm">
                            <strong>Médico : </strong><br>
                            <div align="center"><?= $Ot["medico"] . " - " . $nommedico ?></div>
                        </td>
                        <td width='5%' valign='top' align="center" class="ssbm2" colspan="3"><?= $idprocedencia ?> &nbsp; &nbsp; <?= $idprocedencia2 ?> &nbsp; &nbsp; <?= $idprocedencia3 ?> &nbsp; &nbsp; <?= $idprocedencia4 ?> &nbsp; &nbsp; <?= $idprocedencia5 ?> &nbsp; &nbsp;  &nbsp; &nbsp; </td>
                        <td width='28%'align="center" class="ssbm2">

                        </td>
                    </tr>
                    </form>
                </table> 
            </td>
        </tr>      
    </table>  

    <! –– Primer Interpretación ––>

    <form name='form0' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
    <table width="97%" border="0" align="center">
        <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
            <td align="center" class="letratitulo" colspan="2">
                ..:: Interpretación --> <?= $estudio . " - " . ucwords(strtolower($Est[descripcion])) ?> ::..

            </td>
        </tr>
        <tr align="center">
            <td>
              <div id="editor">
                <textarea name="Texto" id='edit' style="margin-top: 30px;" placeholder="Type some text">
                <?= $Ot[texto] ?>
                </textarea>
              </div>
            </td>
        </tr>

        <tr  class="letrap">
            <td align="center">
                <b>Medico firmante : 
                    <?php
                    $MedA = mysql_query("SELECT nombre,id FROM medi");
                    echo "<select name='Medico' class='letrap'>";
                    while ($Med = mysql_fetch_array($MedA)) {
                        echo "<option value='$Med[id]' >$Med[nombre]</option>";
                        if ($Ot[medicores] == $Med[id]) {
                            $Dsp1 = $Med[nombre];
                        }
                    }
                    echo "<option selected value='$Ot[medicores]'>$Dsp1</option>";
                    echo "</select> &nbsp; ";
                    ?>                    
                </b>
                <br></br>
                <input type='submit' class="letrap" name='Boton' value='Guardar'></input> &nbsp;  &nbsp; 
                <input type='submit' class="letrap" name='Boton' value='Validar'></input> &nbsp;  &nbsp; 
                <input type="hidden" name="busca" value="<?= $busca ?>"></input> &nbsp; 
                <input type="hidden" name="alterno" value="<?= $alterno ?>"></input> &nbsp; 
                <input type="hidden" name="estudio" value="<?= $estudio ?>"></input> &nbsp; 
                <a href=javascript:winmed('pdfradiologia.php?busca=<?= $busca ?>&Estudio=<?= $estudio ?>')><i class="fa fa-file-pdf-o fa-2x" style="color: #FF2222" aria-hidden="true" title="Vista de Impresion"></i></a>
                <br></br>
            </td>
        </tr>
    </table>
    </form>

    <! –– Segunda Interpretación ––>

    <?php
    if ($Est[dobleinterpreta] == 'S') {
    ?>

        <form name='form0' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
        <table width="97%" border="0" align="center">
            <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                <td align="center" class="letratitulo" colspan="2">
                    ..:: Segunda Interpretación --> <?= $estudio . " - " . ucwords(strtolower($Est[descripcion])) ?> ::..

                </td>
            </tr>
            <tr align="center">
                <td>
                  <div id="editor">
                    <textarea name="Texto2" id='edit' style="margin-top: 30px;" placeholder="Type some text">
                    <?= $Ot[texto2] ?>
                    </textarea>
                  </div>
                </td>
            </tr>

            <tr  class="letrap">
                <td align="center">
                    <input type='submit' class="letrap" name='Boton2' value='Guardar'></input> &nbsp;  &nbsp; 
                    <input type='submit' class="letrap" name='Boton2' value='Validar'></input> &nbsp;  &nbsp; 
                    <input type="hidden" name="busca" value="<?= $busca ?>"></input> &nbsp; 
                    <input type="hidden" name="alterno" value="<?= $alterno ?>"></input> &nbsp; 
                    <input type="hidden" name="estudio" value="<?= $estudio ?>"></input> &nbsp; 
                    <a href=javascript:winmed('pdfradiologia2.php?busca=<?= $busca ?>&Estudio=<?= $estudio ?>')><i class="fa fa-file-pdf-o fa-2x" style="color: #FF2222" aria-hidden="true" title="Vista de Impresion"></i></a>
                    <br></br>
                </td>
            </tr>
        </table>
        </form>

    <?php
    }
    ?>

    <! –– Slider de imagenes ––>

        <table align="center" width="98%" border="0">
            <tr>
                <td align="center" width="10%" valign="middle">
                    <button class="w3-btn w3-blue w3-border w3-round-large" onclick="plusDivs(-1)">❮ Ant</button>
                </td>
                <td align="center" width="80%" valign="top">

                    <div class="w3-content" style="background-color: black;">
                    <?php
                    $cc2 = "SELECT * FROM estudiospdf WHERE id='$busca' and usrelim='' and (archivo like '%png%' or archivo like '%jpg%' or archivo like '%bmp%') order by idnvo";
                    $ImgA2 = mysql_query($cc2);

                    while ($row2 = mysql_fetch_array($ImgA2)) {
                        $Pos = strrpos($row2[archivo], ".");
                        $cExt = strtoupper(substr($row2[archivo], $Pos + 1, 4));
                        $Fotos = $row2[archivo];
                        $Contafoto++;
                        $slide2="slide".$Contafoto;
                    ?>
                          <img class="mySlides" src="../lcd/estudios/<?=$Fotos?>" style="max-width: 70%;height: auto;"/>
                     <?php
                    }
                    ?>      
                    </div>
                    <div class="w3-center">
                        <?php
                        $cc3 = "SELECT * FROM estudiospdf WHERE id='$busca' and usrelim='' and (archivo like '%png%' or archivo like '%jpg%' or archivo like '%bmp%') order by idnvo";
                        $ImgA3 = mysql_query($cc3);

                        while ($row3 = mysql_fetch_array($ImgA3)) {
                            $Pos2 = strrpos($row3[archivo], ".");
                            $cExt2 = strtoupper(substr($row3[archivo], $Pos2 + 1, 4));
                            $Contafotos++;
                        ?>
                            <button class="w3-button w3-tiny demo" onclick="currentDiv(<?=$Contafotos?>)"><?=$Contafotos?></button>
                        <?php
                        }
                        ?>
                    </div>
                </td>
                <td align="center" width="10%" valign="middle">
                    <button class="w3-btn w3-blue w3-border w3-round-large" onclick="plusDivs(1)">Sig ❯</button>
                </td>
            </tr>
        </table>

        <table align="center" width="98%" border="0">
            <tr>
                <td align="center" width="100%">
                    <a class="elim" href=javascript:winmed('displayestudioslcdimg.php?busca=<?= $busca ?>')><i class='fa fa-eye fa-2x' aria-hidden='true'  style='color:#2E86C1'></i> Manejo de imagenes</a>
                </td>
            </tr>
        </table>

    <! –– Log Modificaciones ––>

        <table width="98%" border="0" align="center">
            <tr>
                <td align="center" width="50%" valign="top">
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Modificaciones ::.
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <table width="98%" style="margin-top: 10px;border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                    <tr class="letrap" align="center">
                                        <td><b>&nbsp; Id</b></td>
                                        <td><b>&nbsp; Fecha</b></td>
                                        <td><b>&nbsp; Usuario</b></td>
                                        <td><b>&nbsp; Accion</b></td>
                                    </tr>
                                    <?php
                                    /*
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Admin/Equipos/%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 10;";
                                     
                                    $PgsA = mysql_query($sql);
                                    while ($rg = mysql_fetch_array($PgsA)) {
                                        if (($nRng % 2) > 0) {
                                            $Fdo = 'FFFFFF';
                                        } else {
                                            $Fdo = $Gfdogrid;
                                        }*/
                                        ?>
                                        <tr class="letrap" bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='#CBE3E9';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>
                                            <td>
                                                <b>&nbsp;<?= $rg[id] ?></b>
                                            </td>
                                            <td align="center">
                                                &nbsp;<?= $rg[fecha] ?>
                                            </td>
                                            <td>
                                                <?= $rg[usr] ?>
                                            </td>
                                            <td>
                                                <?= $rg[accion] ?>
                                            </td>
                                        </tr>
                                        <?php
                                        /*
                                        $nRng++;
                                    }*/
                                    ?>
                                </table><br/>
                            </td>
                        </tr>
                    </table>

                </td>
                <td align="center" width="50%" valign="top">

                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Estudios ::.
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
                        $OtdA = mysql_query("SELECT ot.orden, ot.fecha,otd.estudio,est.descripcion,est.depto,otd.alterno,otd.capturo
                        FROM ot,otd LEFT JOIN est ON otd.estudio=est.estudio
                        WHERE ot.cliente = '$Ot[cliente]' AND ot.orden=otd.orden");

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
                            echo "<td align='center'>$reg[orden]</font></td>";
                            echo "<td>$reg[estudio]</font></td>";
                            echo "<td>$reg[descripcion]</font></td>";

                            if ($reg[capturo] <> '') {
                                if ($reg[depto] <> 2) {
                                    echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$reg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                } else {
                                    echo "<td align='center'><a class='pg' href=javascript:wingral('pdfradiologia.php?busca=$reg[orden]&Estudio=$reg[estudio]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                                }
                            } else {
                                echo "<td align='center'>-</td>";
                            }
                            echo "</tr>";
                            $nRng++;
                        }//fin while
                        ?>
                        </tr>
                        </table><br/>
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
        </table>

        <br><br>

    <! –– Librerias js ––>

        <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
        <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.2.7/purify.min.js"></script>

        <script type="text/javascript" src="js/froala_editor.min.js"></script>
        <script type="text/javascript" src="js/plugins/align.min.js"></script>
        <script type="text/javascript" src="js/plugins/char_counter.min.js"></script>
        <script type="text/javascript" src="js/plugins/code_beautifier.min.js"></script>
        <script type="text/javascript" src="js/plugins/colors.min.js"></script>
        <script type="text/javascript" src="js/plugins/draggable.min.js"></script>
        <script type="text/javascript" src="js/plugins/emoticons.min.js"></script>
        <script type="text/javascript" src="js/plugins/entities.min.js"></script>
        <script type="text/javascript" src="js/plugins/file.min.js"></script>
        <script type="text/javascript" src="js/plugins/font_size.min.js"></script>
        <script type="text/javascript" src="js/plugins/font_family.min.js"></script>
        <script type="text/javascript" src="js/plugins/fullscreen.min.js"></script>
        <script type="text/javascript" src="js/plugins/image.min.js"></script>
        <script type="text/javascript" src="js/plugins/image_manager.min.js"></script>
        <script type="text/javascript" src="js/plugins/line_breaker.min.js"></script>
        <script type="text/javascript" src="js/plugins/inline_style.min.js"></script>
        <script type="text/javascript" src="js/plugins/link.min.js"></script>
        <script type="text/javascript" src="js/plugins/lists.min.js"></script>
        <script type="text/javascript" src="js/plugins/paragraph_format.min.js"></script>
        <script type="text/javascript" src="js/plugins/paragraph_style.min.js"></script>
        <script type="text/javascript" src="js/plugins/quick_insert.min.js"></script>
        <script type="text/javascript" src="js/plugins/quote.min.js"></script>
        <script type="text/javascript" src="js/plugins/table.min.js"></script>
        <script type="text/javascript" src="js/plugins/save.min.js"></script>
        <script type="text/javascript" src="js/plugins/url.min.js"></script>
        <script type="text/javascript" src="js/plugins/help.min.js"></script>
       <!-- <script type="text/javascript" src="js/plugins/print.min.js"></script> -->
        <script type="text/javascript" src="js/plugins/special_characters.min.js"></script>
        <script type="text/javascript" src="js/plugins/word_paste.min.js"></script>
        <script type="text/javascript" src="js/languages/es.js"></script>

        <script>
        (function () {
          const editorInstance = new FroalaEditor('#edit', {
            key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
            attribution: false, // to hide "Powered by Froala"
            language: 'es',
            enter: FroalaEditor.ENTER_BR
          })
        })()
        </script>

        <script>

        $(function() {

            $('ul#options li a').click(function() {
             $('ul#options li a').removeClass('selected');
             $(this).addClass('selected');

            var imageName = $(this).attr('alt');

            $('#featured a').attr('src', '../lcd/estudios/' + imageName);

            var chopped = imageName.split('.');
             $('#featured h2').remove();
             $('#featured')
             .prepend('<h2>' + chopped[0] + '</h2>')
             .children('h2')
             .fadeIn(500)
             .fadeto(200, .6);

            });

            $('ul#options li a').click(function() {
             return false;
             });
            });

        </script>

        <script>
        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
          showDivs(slideIndex += n);
        }

        function currentDiv(n) {
          showDivs(slideIndex = n);
        }

        function showDivs(n) {
          var i;
          var x = document.getElementsByClassName("mySlides");
          var dots = document.getElementsByClassName("demo");
          if (n > x.length) {slideIndex = 1}    
          if (n < 1) {slideIndex = x.length}
          for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";  
          }
          for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" w3-red", "");
          }
          x[slideIndex-1].style.display = "block";  
          dots[slideIndex-1].className += " w3-red";
        }

        </script>
    </body>
</html>
<?php
mysql_close();
?>
