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
$lBd = false;
require("fileupload-class.php");
$path = "estudios/";
if ($busca == $cId) {
    $lBd = true;
}


// En este caso acepta todo, pero podemos filtrar que tipos de archivos queremos
$acceptable_file_types = "";


// Si no se le da una extension pone por default: ".jpg" or ".txt"
$default_extension = "";

// MODO: Si se intenta subir un archivo con el mismo nombre a:
// $path directory
// HAY OPCIONES:
//   1 = modo de sobreescritura
//   2 = crea un nuevo archivo con extension incremental
//   3 = no hace nada si existe (mayor proteccion)

$mode = 2;


if (isset($_REQUEST['submitted']) AND $lBd) {

// Crea un nueva instancia de clase
    $my_uploader = new uploader($_POST['language']);

// OPCIONAL: Tamano maxino de archivos en bytes
    $my_uploader->max_filesize(3000000);
//$my_uploader->max_filesize(4194304);
// OPCIONAL: Si se suben imagenes puedes poner el ancho y el alto en pixeles 
    $my_uploader->max_image_size(2500, 2500); // max_image_size($width, $height)
// Sube el archivo

    if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {

        $my_uploader->save_file($path, $mode);
    }

    if ($my_uploader->error) {
        echo $my_uploader->error . "<br><br>\n";
    } else {

// Imprime el contenido del array (donde se almacenan los datos del archivo)...
//print_r($my_uploader->file);

        $cNombreFile = $my_uploader->file['name'];
        $Size = $my_uploader->file['size'];
        $NombreOri = $my_uploader->file['raw_name'];
        $Usr2 = $_COOKIE['USERNAME'];
        $Fechasub = date("Y-m-d H:i:s");

        $lUp = mysql_query("INSERT INTO estudiospdf (id,archivo,usr,fechasub) VALUES ('$busca','$cNombreFile','$Usr2','$Fechasub')");

        /*
          $fp = fopen($path . $my_uploader->file['name'], "r");

          while(!feof($fp)) {

          $line = fgets($fp, 255);

          echo $line;

          }

          if ($fp) { fclose($fp); }

          }
         */
    }
}
if ($_REQUEST[Boton] == "Aceptar" OR $_REQUEST[Boton] == "Aplicar") {  //Guarda el Movto de Notas
    $Fecha = date("Y-m-d");
    $Hora = date("H:i");

    $lUp = mysql_query("UPDATE otd SET texto = '$_REQUEST[Texto]',status='TERMINADA', letra = '$_REQUEST[Letra]',
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

if ($_REQUEST[Boton2] == "Aceptar" OR $_REQUEST[Boton2] == "Aplicar") {  //Guarda el Movto de Notas
    $Fecha = date("Y-m-d");
    $Hora = date("H:i");

    $lUp = mysql_query("UPDATE otd SET texto2 = '$_REQUEST[Texto]'
     		 WHERE orden='$busca' AND estudio='$estudio' limit 1");
}

$cc = "SELECT descripcion,proceso,formato,respradiologia,dobleinterpreta FROM est WHERE estudio='$estudio'";
$EstA = mysql_query($cc);
$Est = mysql_fetch_array($EstA);
$OtA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,ot.observaciones,
    inst.nombre,cli.sexo,cli.nombrec,cli.fechan,med.nombrec as nombremed,otd.texto,otd.letra,
    otd.medico as medicores,otd.texto2
    FROM ot,inst,cli,med,otd
    WHERE  ot.orden='$busca' AND ot.cliente=cli.cliente 
    AND ot.institucion = inst.institucion AND ot.medico=med.medico AND otd.orden=ot.orden AND otd.estudio='$estudio'");

$Ot = mysql_fetch_array($OtA);
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Documento sin título</title>
            <?php require ("./config_add.php"); ?>
	    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    </head>
    <body>
        <table width="100%" border="0">
            <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                <td align="center" class="letratitulo">
                    <?= $estudio . " .- " . ucwords(strtolower($Est[descripcion])) ?>
                </td>
            </tr>
            <tr>
                <td align="center" class="letrap">
                    <b>No.Orden :</b> <?= $busca ?>  <b>Fecha :</b> <?= $Ot[fecha] ?> <b>Hora : </b> <?= $Ot[hora] ?>
                    <b> Fec/Ent :</b> <?= $Ot[fechae] ?> <b>Tpo/Serv :</b> <?= $Ot[servicio] ?>
                </td>
            </tr>
            <tr>
                <td align="center" class="letrap">
                    <b>Paciente :</b> <?= ucwords(strtolower($Ot[nombrec])) ?> <b>Inst :</b> <?= $Ot[nombre] ?> <b>Edad :</b> <?= $Fecha - $Ot[fechan] ?> Años
                    <b>Sexo :</b> <?= $Ot[sexo] ?> 
                </td>
            </tr>
            <tr>
                <td align="center" class="letrap">
                    <b>Medico :</b> <?= ucwords(strtolower($Ot[nombremed])) ?>
                </td>
            </tr>
        </table>
        <br></br>
        <table border='0' align='center' width='90%' border='1' cellpadding='1' cellspacing='0' >
            <?php
            if (isset($Ot[diagmedico])) {
                ?>
                <tr class="letrap">
                    <td width="30%" align="right">
                        <b>Diagnostico medico : </b>
                    </td>
                    <td><?php echo $Ot[diagmedico]; ?> &nbsp; </td>
                </tr>
                <?php
            }
            if (isset($Ot[observaciones])) {
                ?>
                <tr class="letrap">
                    <td width="20%" align="right">
                        <b>Observaciones : </b>
                    </td>
                    <td><?php echo $Ot[observaciones]; ?> &nbsp; </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        $cc = "SELECT cue.pregunta,otpre.nota,cue.id,cue.tipo "
                . "FROM otpre,cue "
                . "WHERE otpre.orden='$busca' AND otpre.estudio='$estudio' AND cue.id=otpre.pregunta";
        //echo $cc;
        $OtpreA = mysql_query($cc);
        if (mysql_fetch_array($OtpreA)) {
            ?>
            <form name='frm' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <table width="100%" border="0">
                    <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                        <td align="center" class="letratitulo" colspan="2">
                            Pre - analiticos
                        </td>
                    </tr>
                    <?php
                    $OtpreA = mysql_query($cc);
                    while ($rg = mysql_fetch_array($OtpreA)) {
                        ?>
                        <tr class="letrap">
                            <td width="50%">
                                <b><?= $rg[pregunta] ?></b>
                            </td>
                            <td>
                                <?php
                                $Campo = "Nota" . ltrim($Sec);
                                if ($rg[tipo] === "Si/No") {
                                    ?>
                                    <select  class="letrap" name="<?= $Campo ?>">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                        <option selected><?= $rg[nota] ?></option>
                                    </select>
                                    <?php
                                } elseif ($rg[tipo] == "Fecha") {
                                    ?>
                                    <textarea name='<?= $Campo ?>' value ='<?= $rg[nota] ?>' type='text' rows="3" cols="45"  class="letrap"></textarea>
                                    <?php
                                } else {
                                    ?>
                                    <textarea name='<?= $Campo ?>' value ='<?= $rg[nota] ?>' type='text' rows="3" cols="45" class="letrap" ></textarea>
                                    <?php
                                }
                                ?>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td>
                            <input type="button" name="guarda" value="Guarda" class="letrap"></input>
                        </td>
                    </tr>
                </table>
            </form>
            <?php
        }
        ?>
        <form name='form0' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table width="100%" border="0">
                <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                    <td align="center" class="letratitulo" colspan="2">
                        Resultados
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        <?php
                        $Ot[texto] === '' ? $Formato = $Est[respradiologia] : $Formato = $Ot[texto];
                        ?>
                        <textarea name='Texto' type='text' rows="3" cols="80" class="letrap" ><?= $Formato ?></textarea>
                    </td>
                </tr>
		<script>
                    CKEDITOR.replace('Texto');
                </script>
                <tr  class="letrap">
                    <td>
                        <b>Tamaño  de letra : </b>
                        <select name="Letra"  class="letrap">
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="<?= $Ot[letra] ?>" selected><?= $Ot[letra] ?></option>
                        </select>

                        <b>Realizó Estudio : 
                            <?php
                            $MedA = mysql_query("SELECT nombre,id FROM pertec");
                            echo "<select name='Medico' class='letrap'>";
                            while ($Med = mysql_fetch_array($MedA)) {
                                echo "<option value='$Med[id]' >$Med[nombre]</option>";
                                if ($Ot[medicores] == $Med[id]) {
                                    $Dsp1 = $Med[nombre];
                                }
                            }
                            echo "<option selected='$Ot[medicores]'>$Dsp1</option>";
                            echo "</select> &nbsp; ";
                            ?>                    
                        </b>
                        <br></br>
                        <input type='submit' class="letrap" name='Boton' value='Aceptar'></input>
                        <input type='submit' class="letrap" name='Boton' value='Aplicar'></input>
                        <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                        <input type="hidden" name="alterno" value="<?= $alterno ?>"></input>
                        <input type="hidden" name="estudio" value="<?= $estudio ?>"></input>
                        <a href=javascript:wingral('pdfradiologia.php?busca=<?= $busca ?>&Estudio=<?= $estudio ?>')><i class="fa fa-file-pdf-o fa-2x" style="color: #FF2222" aria-hidden="true" title="Vista de Impresion"></i></a> 
                        <i class="fa fa-print fa-2x" aria-hidden="true" onClick="window.print()" alt="Imprimir" style="color: #595959"></i>
                        <br></br>
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if ($Est[dobleinterpreta] == 'S') {
            ?>
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <table border="0">
                    <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 30px;'>
                        <td align="center" class="letratitulo" colspan="2">
                            Siguiente Interpretacion
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            if ($Ot[texto2] == '') {
                                $Formato2 = $Est[respradiologia];
                            } else {
                                $Formato2 = $Ot[texto2];
                            }
                            ?>

                            <textarea name="Texto2" rows="20" cols="80" style="width: 100%"><?= $Formato2 ?></textarea>
                            <input type="hidden" name="estudio" value="<?= $estudio ?>"></input>
                            <input type="hidden" name="Depto" value="<?= $Depto ?>"></input>
                            <input type="hidden" name="op" value="gu"> </input>
                            <div class="letrap">
                                Tamaño de letra:
                                <select name='Letra' class="letrap">
                                    <option value='7'>7</option>
                                    <option value='8'>8</option>
                                    <option value='9'>9</option>
                                    <option value='10'>10</option>
                                    <option value='11'>11</option>
                                    <option value='12'>12</option>
                                    <option value='<?= $Ot[letra] ?>' selected><?= $Ot[letra] ?></option>
                                </select> Medico firmante: 
                                <?php
                                $MedA = mysql_query("SELECT nombre,id
					  FROM medi
					  ");

                                echo "<select class='letrap' name='Medico'>";

                                while ($Med = mysql_fetch_array($MedA)) {
                                    echo "<option value='$Med[id]'>$Med[nombre]</option>";
                                    if ($Ot[medicores] == $Med[id]) {
                                        $Dsp1 = $Med[nombre];
                                    }
                                }
                                echo "<option selected='$Ot[medicores]'>$Dsp1</option>";
                                echo "</select> &nbsp; ";
                                ?>
                                <input type='submit' class="letrap" name="Boton" value='Aceptar'/>
                                <input type='submit' class="letrap" name="Boton" value='Aplicar'/>
                                <a href=javascript:winuni('pdfradiologiadental.php?busca=<?= $busca ?>&Estudio=<?= $estudio ?>')><i class="fa fa-file-pdf-o" aria-hidden="true" title="Vista de Impresion"></i></a> &nbsp; 
                                <i class="fa fa-print" aria-hidden="true" onClick="window.print()" alt="Imprimir" ></i>
                                <input type='hidden' name='pagina' value='<?= $pagina ?>' />
                                <input type='hidden' name='busca' value='<?= $busca ?>' />
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
            <?php
        }
        ?>
        <table align="center" width="98%" border="0">
            <tr>
                <td align="left">
                    <a class="elim" href=javascript:wineti('displayestudioslcd.php?busca=<?= $busca ?>')><i class='fa fa-eye fa-2x' aria-hidden='true'  style='color:#2E86C1'></i> Visualiza imagenes</a>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <div>
                        <table class="letrap" width="80%">
                            <tr>
                                <td>
                                    <b>
                                        Archivo
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        PDF
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        Elim
                                    </b>
                                </td>
                            </tr>

                            <?php
                            $cc = "SELECT * FROM estudiospdf WHERE id='$busca' and usrelim=''";
                            //echo $cc;
                            $ImgA = mysql_query($cc);
                            while ($row = mysql_fetch_array($ImgA)) {
                                $Pdf = $row[archivo];
                                echo "<tr><td>$Pdf";
                                echo "</td>";
                                echo "<td>";
                                echo "<a href=javascript:winuni('enviafile2.php?busca=$Pdf')><i class='fa fa-file-pdf-o fa-lg' style='color:#FF0000;' title='$Pdf' aria-hidden='true'></i></a> &nbsp; &nbsp; ";
                                echo "</td>";
                                echo "<td>";
                                echo "<a href='capturaresword.php?archivo=$Pdf&id=$busca&busca=$busca&estudio=$estudio' onclick='return confirm(\"Desea eliminar el archivo?\")'><i class='fa fa-window-close fa-lg' style='color:#FF0000;' title='Elimina_$Pdf' aria-hidden='true'></i></a> &nbsp; &nbsp;";
                                echo "</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <form enctype='multipart/form-data' action='capturaresword.php?busca=$busca&estudio=$estudio' method='POST'>
                        <input type='hidden' name='submitted' value='true'></input>	
                        <input class='letrap' name='<?= $upload_file_name ?>' type='file'></input>
                        <input class='letrap' type='submit' value='Subir archivo'></input>
                        <input type='hidden' name='cId' value='<?= $cId ?>'></input> 
                    </form>
                </td>
            </tr>
        </table>
        <table width="90%" border="0" align="center">
            <tr style='background-color: #ABB2B9 ;border-collapse: collapse; border: 1px solid #bbb;height: 22px;'>
                <td align="center" class="letratitulo">
                    No. de Orden
                </td>
                <td align="center" class="letratitulo">
                    Fecha
                </td>
                <td align="center" class="letratitulo">
                    Estudio
                </td>
                <td align="center" class="letratitulo">
                    Descripcion
                </td>
                <td align="center" class="letratitulo">
                    Resultado
                </td>
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
                echo "<td> $reg[fecha]</font></td>";
                echo "<td> $reg[orden]</font></td>";
                echo "<td> $reg[estudio]</font></td>";
                echo "<td> $reg[descripcion]</font></td>";

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
        </table>
    </body>
</html>
<?php
mysql_close();
?>
