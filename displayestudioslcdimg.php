<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
setcookie("NOOT", $busca);

$Usr = $check['uname'];
$cId = $_REQUEST[busca];

$estudio = $_REQUEST[estudio];
$archivo = $_REQUEST[archivo];
$lBd = false;

#Variables comunes;
$Titulo = " Detalle de estudios";

require ("config.php");          //Parametros de colores;

if ($archivo <> '') {
    $id = $_REQUEST[id];
    unlink("estudios/$archivo");
    $Usrelim = $_COOKIE['USERNAME'];
    $Fechaelim = date("Y-m-d H:i:s");
    $lUp = mysql_query("UPDATE estudiospdf set usrelim='$Usrelim',fechaelim='$Fechaelim' where archivo='$archivo' and id='$id'");

    $Msj='Eliminacion de Imagenes con Exito';

    AgregaBitacoraEventos2($Usrelim, '/Imagenes/Eliminacion de Imagenes', "estudiospdf", $Fechaelim, $busca, $Msj, "displayestudioslcdimg.php");
}

$HeA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion
  FROM ot,cli
  WHERE ot.orden='$busca' AND ot.cliente=cli.cliente");

$He = mysql_fetch_array($HeA);
?>

<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<meta charset="UTF-8">
    <head>
        <title>Carga de Imagenes</title>
        <?php require ("./config_add.php"); ?>
        <link rel="stylesheet" type="text/css" href="css/dropzone.css">
        <script type="text/javascript" src="js/dropzone.js"></script>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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
        <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#f1f1f1" style="border-collapse: collapse; border: 1px solid #999;">
            <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                <td align="center" class="titulo" colspan="3"><font color="#FFFFFF"><b> Carga de Imagenes </b></td>
            </tr>
            <tr>
                <td width="60%">
                <form class="dropzone" id="mydrop" action="subir.php">
                    <div class="fallback">
                        <input type="file" name="file" multiple id="file">
                    </div>
                </form>
                <script type="text/javascript">
                    var dropzone = new Dropzone("#file", {
                        url: 'subir.php'
                    });
                </script>
                </td>
                <td width="40%" valign="top">
                    <iframe id="ListadoImagenes"
                        title="Listado del Imagenes"
                        width="100%"
                        height="100%"
                        src="listadoimagenes.php?busca=<?=$busca?>">
                    </iframe>
                </td>
            </tr>
        </table>
        <div align='center'>
            <font size='+2'>
            <?= ucwords(strtolower(substr($He[nombrec],0,50))) ?>
            </font>
        </div>
        <div align='center'> <b>No.ORDEN: <?= $He["institucion"] . ".- " . $busca ?></b></div>
        <br>

    <! –– Slider de imagenes ––>

        <table align="center" width="98%" border="0">
            <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                <td align='center' class='titulo' colspan='3'>
                    <font color='#FFFFCC'><b> ..:: Imagenes cargadas ::.. </b>
                </td>
            </tr>
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
                            $Fotos3 = $row3[archivo];
                            $Contafotos++;
                        ?>
                            <button class="w3-button w3-tiny demo" onclick="currentDiv(<?=$Contafotos?>)"><?=$Contafotos?> - <?=$Fotos3?></button>
                        <?php
                        }
                        ?>
                    </div>
                </td>
                <td align="center" width="10%" valign="middle">
                    <button class="w3-btn w3-blue w3-border w3-round-large" onclick="plusDivs(1)">Sig ❯</button>
                </td>
            </tr>
            <tr>
                <td align="center">

                </td>
            </tr>
        </table>

        <table width='95%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;' align="center">
            <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                <td align='center' class='titulo' colspan='3'>
                    <font color='#FFFFCC'><b> ..:: Documentos cargados ::.. </b>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    <table width='70%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;' align="center">
                        <?php
                        $DocA = mysql_query("SELECT * FROM estudiospdf WHERE id='$busca' and usrelim='' and (archivo like '%pdf%' or archivo like '%docx%' or archivo like '%xlsx%') order by idnvo");
                        while ($row = mysql_fetch_array($DocA)) {
                            $Pos = strrpos($row[archivo], ".");
                            $cExt = strtoupper(substr($row[archivo], $Pos + 1, 4));
                            $foto = $row[archivo];
                            if ($cExt == 'PDF') {
                                echo "<tr align='center'><td align='center'><iframe src='../lcd/estudios/$foto#view=FitH' type='application/pdf' style='width:100%; height:100%; border: none;min-height:750px;' /></iframe></td></tr>";

                            } elseif ($cExt == 'DOCX') {

                                echo "<tr align='center'><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=https://lcd-system.com/lcd/estudios/$foto' style='width:100%; height:100%; border: none;min-height:750px;'></iframe></td></tr>";

                            } elseif ($cExt == 'XLSX') {

                                echo "<tr align='center'><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=https://lcd-system.com/lcd/estudios/$foto' style='width:100%; height:100%; border: none;min-height:750px;'></iframe></td></tr>";

                            }

                            echo "<tr class='letrap'><td bgcolor='#2980b9' align='center'><font size='2' color='#FFFFCC'>" . ucfirst(strtolower($row[archivo])) . "  &nbsp; &nbsp; Usuario:  ".ucfirst(strtolower($row[usr]))." &nbsp; &nbsp; Fecha/Hora: ".$row[fechasub]." &nbsp; &nbsp; <a class='edit' href='displayestudioslcdimg.php?archivo=$foto&id=$busca&busca=$busca' onclick='return confirm(\"Desea eliminar el archivo?\")'><font color='#FFFFCC'><i class='fa fa-trash fa-lg' aria-hidden='true' style='color: white;'> </i></font></a> Elim</td></tr>";

                            echo "<tr align='center'><td align='center'> &nbsp; &nbsp; </td></tr>";
                        }
                        ?>
                    </table>
                </td>
            </tr>
        </table>

        <br>

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
                                    
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Imagenes/%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 10;";
                                     
                                    $PgsA = mysql_query($sql);
                                    while ($rg = mysql_fetch_array($PgsA)) {
                                        if (($nRng % 2) > 0) {
                                            $Fdo = 'FFFFFF';
                                        } else {
                                            $Fdo = $Gfdogrid;
                                        }
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
                                        $nRng++;
                                    }
                                    ?>
                                </table><br/>
                            </td>
                        </tr>
                    </table>

                </td>
                <td align="center" width="50%" valign="top">

                </td>
            </tr>
        </table>

        <br>

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
