<?php
#Librerias
session_start();

require("lib/lib.php");

$link = conectarse();

date_default_timezone_set("America/Mexico_City");

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
$alterno = $_REQUEST[alterno];
if ($alterno == 0) {
    $tabla = 'elepdf';
} elseif ($alterno == 1) {
    $tabla = 'elealtpdf';
} elseif ($alterno == 2) {
    $tabla = 'elealtpdf2';
} elseif ($alterno == 3) {
    $tabla = 'elealtpdf3';
}
if ($archivo <> '') {
    $id = $_REQUEST[id];
    unlink("estudios/$archivo");
    $Usrelim = $_COOKIE['USERNAME'];
    $Fechaelim = date("Y-m-d H:i:s");
    $lUp = mysql_query("UPDATE estudiospdf set usrelim='$Usrelim',fechaelim='$Fechaelim' where archivo='$archivo' and id='$id'");
}

require("fileupload-class.php");

$path = "estudios/";

$upload_file_name = "userfile";

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

    // OPCIONAL: Si se suben imagenes puedes poner el ancho y el alto en pixeles 
    $my_uploader->max_image_size(1500, 1800); // max_image_size($width, $height)
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
    }
}

if ($op == 'rs') {  //Registra resultados

    $Msj = "";

    $OtdA = mysql_query("SELECT status, capturo, tres, usrvalida, fechavalida, impest, impres, fr, creapdf FROM otd WHERE estudio='$estudio' AND orden='$busca'");

    $Otd = mysql_fetch_array($OtdA);

    $Fecha = date("Y-m-d");

    $Hora = date("H:i");

    if ($TFr == 0) {
        $fr2 = '0';
    } else {
        $fr2 = '1';
    }

    $lUp = mysql_query("DELETE FROM resul WHERE orden='$busca' AND estudio='$estudio'");

    $EleA = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' and tipo<>'e' and tipo<>'s' and tipo<>'z' and tipo<>'v' ORDER BY id");

    while ($Ele = mysql_fetch_array($EleA)) {

        $Rs = $Ele[id];

        $Resultado = $_REQUEST[$Rs];

        if ($Ele[tipo] == "l") {

            $Campo = 'l';
        } elseif ($Ele[tipo] == "d") {

            $Campo = 'd';
        } elseif ($Ele[tipo] == "n") {

            $Campo = 'n';

            if ($Resultado < $Ele[min] OR $Resultado > $Ele[max]) {

                $TFr = $TFr + 1;
            } else {

                $TFr = $TFr + 0;
            }
        } elseif ($Ele[tipo] == "c") {

            $Campo = 'c';
        } else {

            $Campo = 't';
        }

        $lUp = mysql_query("INSERT INTO resul (orden,estudio,elemento,$Campo) 
                       VALUES
                       ('$busca','$estudio','$Ele[id]','$Resultado')");
    }

    $EleA = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' and tipo<>'e' and tipo<>'s' and tipo<>'z' and tipo<>'v' ORDER BY id");

    while ($Ele = mysql_fetch_array($EleA)) {

        if ($Ele[tipo] == "n") {

            if ($Ele[calculo] == "Si") {

                if ($Ele[idvalor1] == "ID") {

                    $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor1]'");

                    $Vlr2 = mysql_fetch_array($VlrB);

                    $Idvalor1 = $Vlr2[n];
                } else {

                    $Idvalor1 = $Ele[valor1];
                }

                if ($Ele[idvalor2] == "ID") {

                    $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor2]'");

                    $Vlr2 = mysql_fetch_array($VlrB);

                    $Idvalor2 = $Vlr2[n];
                } else {

                    $Idvalor2 = $Ele[valor2];
                }

                if ($Ele[idvalor3] == "ID") {

                    $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor3]'");

                    $Vlr2 = mysql_fetch_array($VlrB);

                    $Idvalor3 = $Vlr2[n];
                } else {

                    $Idvalor3 = $Ele[valor3];
                }

                if ($Ele[idvalor4] == "ID") {

                    $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor4]'");

                    $Vlr2 = mysql_fetch_array($VlrB);

                    $Idvalor4 = $Vlr2[n];
                } else {

                    $Idvalor4 = $Ele[valor4];
                }

                if ($Idvalor1 == 0) {
                    $Idvalor1 = '';
                }

                if ($Idvalor2 == 0) {
                    $Idvalor2 = '';
                }

                if ($Idvalor3 == 0) {
                    $Idvalor3 = '';
                }

                if ($Idvalor4 == 0) {
                    $Idvalor4 = '';
                }


                $calculo2 = $Ele[parentesis1] . $Idvalor1 . $Ele[operador1] . $Idvalor2 . $Ele[parentesis2] . $Ele[operador2] . $Idvalor3 . $Ele[parentesis3] . $Ele[operador3] . $Idvalor4 . $Ele[parentesis4];

                eval("\$calculo3 = $calculo2;");

                $calculo = $calculo3;

                $calculo = number_format($calculo3, 2);

                $Up = mysql_query("UPDATE resul SET n='$calculo' WHERE orden='$busca' and estudio='$estudio' and elemento='$Ele[id]'");
            }
        }
    }

    if($_REQUEST[Aceptar] == "Aceptar"){

        $Msj = "Tus datos han sido guardados con exito!";

        $Up = mysql_query("UPDATE otd SET tres='$Fecha $Hora', status='CAPTURA', capturo='$Gusr', alterno='$alterno', fr='$fr2' WHERE orden='$busca' and estudio='$estudio'");

    }elseif($_REQUEST[Validar] == "Validar"){

        $Msj = "Validacion con exito!";

        $Up = mysql_query("UPDATE otd SET fechavalida='$Fecha $Hora', status='TERMINADA', usrvalida='$Gusr', alterno='$alterno', fr='$fr2', creapdf='pdf' WHERE orden='$busca' and estudio='$estudio'");

        $NumA  = mysql_query("SELECT otd.estudio 
        FROM otd 
        WHERE otd.orden='$busca' AND otd.status='RESUL'");

        if(mysql_num_rows($NumA)==0){
          $lUp = mysql_query("UPDATE ot SET status='En Impresion' WHERE orden='$busca'");
        }else{
          $lUp = mysql_query("UPDATE ot SET status='En Impres Parc' WHERE orden='$busca'");
        } 

    }

}


$EstA = mysql_query("SELECT descripcion,proceso,formato,respradiologia,dobleinterpreta FROM est WHERE estudio='$estudio'");
$Est = mysql_fetch_array($EstA);
$OtA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,ot.observaciones,
    inst.nombre,cli.sexo,cli.nombrec,cli.fechan,med.nombrec as nombremed,otd.texto,otd.letra,
    otd.medico as medicores,otd.texto2
    FROM ot,inst,cli,med,otd
    WHERE  ot.orden='$busca' AND ot.cliente=cli.cliente 
    AND ot.institucion = inst.institucion AND ot.medico=med.medico AND otd.orden=ot.orden AND otd.estudio='$estudio'");

$Ot = mysql_fetch_array($OtA);

if ($Ot[sexo] == 'F') {
    $Sx = "Femenino";
} else {
    $Sx = "Masculino";
}

$anos = $Fecha - $Ot[fechan];
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
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
    </head>
    <body>
        <table width="100%" border="0">
            <tr style='background-color: #2c8e3c ;border-collapse: collapse; border: 1px solid #bbb;height: 20px;'>
                <td align="center" class="letratitulo">
                    <?= $estudio . " .- " . ucwords(strtolower($Est[descripcion])) ?>
                </td>
            </tr>
            <tr style='background-color: #D98880;border-collapse: collapse; border: 1px solid #bbb;height: 7px;'>
                <td align="right" class="letram" colspan="2">
                    Datos Generales &nbsp; 
                </td>
            </tr>
            <tr>
                <td class="letrap">
                    <h2><b><?= $busca . ".- " . ucwords(strtolower($Ot[nombrec])) ?></b></h2>
                </td>
            </tr>
            <tr>
                <td class="letrap">
                    <b>Fecha :</b> <?= $Ot[fecha] ?> <b>Hora : </b> <?= $Ot[hora] ?> <b> Fec/Ent :</b> <?= $Ot[fechae] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    <table width="90%" border="0" align="center">
                        <tr style='background-color: #ABB2B9 ;border-collapse: collapse; border: 1px solid #bbb;height: 22px;'>
                            <td align="center" class="letratitulo">
                                Sexo
                            </td>
                            <td align="center" class="letratitulo">
                                Edad
                            </td>
                            <td align="center" class="letratitulo">
                                Medico
                            </td>
                            <td align="center" class="letratitulo">
                                Servicio
                            </td>
                        </tr>
                        <tr class="letrap">
                            <td>
                                <?= $Sx ?>
                            </td>
                            <td align="center">
                                <?= $anos ?>
                            </td>
                            <td>
                                <?= $Ot[nombremed] ?>
                            </td>
                            <td>
                                <?= $Ot[servicio] ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        <table border='0' align='center' width='90%' border='1' cellpadding='1' cellspacing='0' >
            <tr class="letrap">
                <td width="30%" align="right">
                    <b>Diagnostico medico : </b>
                </td>
                <td><?php echo $Ot[diagmedico]; ?> &nbsp; </td>
            </tr>
            <tr class="letrap">
                <td width="30%" align="right">
                    <b>Observaciones : </b>
                </td>
                <td><?php echo $Ot[observaciones]; ?> &nbsp; </td>
            </tr>
            <tr>
                <td colspan="2" height="10px">
                </td>
            </tr>
        </table>

        <table width="100%" border="0">
            <tr style='background-color: #D98880 ;border-collapse: collapse; border: 1px solid #bbb;height: 7px;'>
                <td align="right" class="letram" colspan="4">
                    Captura de resultados &nbsp; 
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"> 
                    <?php
                    $EleA = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' ORDER BY id");

                    $OtdA = mysql_query("SELECT status,capturo,tres,usrvalida,fechavalida,impest,impres,fr,creapdf FROM otd WHERE estudio='$estudio' AND orden='$busca'");
                    $Otd = mysql_fetch_array($OtdA);


                    if ($Otd[capturo] <> '') {

                        if ($Otd[usrvalida] == '') {

                            echo "<a class='cMsj' align='center'>VALIDACION DE ESTUDIO</a>";
                        } else {

                            echo "<a class='cMsj' align='center'>ACTUALIZACION DE CAPTURA</a>";
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr bgcolor="#566573" class="sbmenu">
                <td align="center" width="25%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=0&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Estandar</a>
                    <?php
                    if ($_REQUEST[alterno] == 0) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?>
                </td>
                <td align="center"  width="25%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=1&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Alternativa</a>
                    <?php
                    if ($_REQUEST[alterno] == 1) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?> 
                </td>
                <td align="center"  width="25%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=2&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Alternativa 2</a>
                    <?php
                    if ($_REQUEST[alterno] == 2) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?>
                </td>
                <td align="center"  width="25%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=3&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Alternativa 3</a>
                    <?php
                    if ($_REQUEST[alterno] == 3) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?>
                </td>
            </tr>
        </table>
        <table border="0" width="80%" align="center">
            <?php
            echo "<form name='form1' method='post' action='estdeptocvalcppdf2.php?alterno=$alterno'>";

            while ($Ele = mysql_fetch_array($EleA)) {

                $VlrA = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[id]'");
                $Vlr = mysql_fetch_array($VlrA);

                $Campo = $Ele[id];

                if (($nRng % 2) > 0) {
                    $Fdo = 'FFFFFF';
                } else {
                    $Fdo = "EAECEE";
                }    //El resto de la division;


                if($Otd[capturo]<>''){
          
                    if($Vlr[n] < $Ele[min]  OR $Vlr[n] > $Ele[max]){
                        $Fdo='D98880';
                        $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>";
                        $TFr=$TFr+1;
                    }else{
                        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                        $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                    }
                }else{
                    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                    $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                }

                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                if ($Ele[tipo] == "e") {

                    $Alineacion = $Ele[alineacion];

                    echo "<td align='center' colspan='4' style='background-color: #aeb5ee;color: #000;'><b><font size='2'>";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</b></font></td><td>";
                } elseif ($Ele[tipo] == "s") {

                    echo "<td align='center' colspan='4' style='background-color: #e8eeae;color: #000;'><b><font size='2'>";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</b></font></td><td>";
                } elseif ($Ele[tipo] == "v") {

                    echo "<td align='center' colspan='4' style='background-color: #FFFFFF;color: #FFFFFF;'>";

                    echo " &nbsp; ";

                    echo "</td><td>";
                } elseif ($Ele[tipo] == "z") {

                    echo "<tr height='21' style='background-color: #566573 ;color: #000;''>
			<td align='center' class='letratitulo'><b>Elementos</b></td>
			<td align='center' class='letratitulo' colspan='2'><b>Resultados</b></td>
			<td align='center' class='letratitulo'><b>Valores de Referencia</b></td></tr>";
                } elseif ($Ele[tipo] == "l") {

                    echo "<td align='right' class='letrap'> ";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</td>";

                    echo "<td align='center'> ";

                    if ($Vlr[l] == 'S') {
                        $cLog = "Positivo";
                    } elseif ($Vlr[l] == 'N') {
                        $cLog = "Negativo";
                    }

                    echo "<SELECT name='$Campo' class='letrap'>";
                    echo "<option value='S'>Positivo</option>";
                    echo "<option value='N'>Negativo</option>";
                    echo "<option SELECTED value='$Vlr[l]'>$cLog</option>";
                    echo "</SELECT>";

                    echo "</td>";

                    echo "<td align='center' class='letrap'>$Gfont </td>";

                    echo "<td align='center' class='letrap'>$Gfont " . $Ele[vlogico] . "</td>";
                } elseif ($Ele[tipo] == "d") {

                    echo "<td align='right' class='letrap'> ";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</td>";

                    echo "<td align='center' colspan='2'><input class='letrap' name='$Campo' value ='$Vlr[d]' type='text' size='11' ></td>";
                } elseif ($Ele[tipo] == "n") {

                    if ($Ele[calculo] == "Si") {

                        if ($Ele[idvalor1] == "ID") {

                            $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor1]'");

                            $Vlr2 = mysql_fetch_array($VlrB);

                            $Idvalor1 = $Vlr2[n];
                        } else {

                            $Idvalor1 = $Ele[valor1];
                        }

                        if ($Ele[idvalor2] == "ID") {

                            $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor2]'");

                            $Vlr2 = mysql_fetch_array($VlrB);

                            $Idvalor2 = $Vlr2[n];
                        } else {

                            $Idvalor2 = $Ele[valor2];
                        }

                        if ($Ele[idvalor3] == "ID") {

                            $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor3]'");

                            $Vlr2 = mysql_fetch_array($VlrB);

                            $Idvalor3 = $Vlr2[n];
                        } else {

                            $Idvalor3 = $Ele[valor3];
                        }

                        if ($Ele[idvalor4] == "ID") {

                            $VlrB = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[valor4]'");

                            $Vlr2 = mysql_fetch_array($VlrB);

                            $Idvalor4 = $Vlr2[n];
                        } else {

                            $Idvalor4 = $Ele[valor4];
                        }

                        if ($Idvalor1 == 0) {
                            $Idvalor1 = '';
                        }

                        if ($Idvalor2 == 0) {
                            $Idvalor2 = '';
                        }

                        if ($Idvalor3 == 0) {
                            $Idvalor3 = '';
                        }

                        if ($Idvalor4 == 0) {
                            $Idvalor4 = '';
                        }


                        $calculo2 = $Ele[parentesis1] . $Idvalor1 . $Ele[operador1] . $Idvalor2 . $Ele[parentesis2] . $Ele[operador2] . $Idvalor3 . $Ele[parentesis3] . $Ele[operador3] . $Idvalor4 . $Ele[parentesis4];


                        //$calculo2="$Idvalor1".$Ele[operador1]."$Idvalor2";

                        eval("\$calculo3 = $calculo2;");

                        $calculo = number_format($calculo3, 2);

                        $Up = mysql_query("UPDATE resul SET n='$calculo3' WHERE orden='$busca' and estudio='$estudio' and elemento='$Ele[id]'");


                        echo "<td align='right' class='letrap'> ";

                        echo "$Ele[descripcion] &nbsp; ";

                        echo "</td>";

                        echo "<td align='center' class='letrap'> $calculo</td><td align='center' class='letrap'>  $Ele[unidad]</td>";

                        echo "<td align='center' class='letrap'> " . number_format($Ele[min], '2') . " - " . number_format($Ele[max], '2') . "</td>";
                    } else {

                        $calculo = $Vlr[n];

                        echo "<td align='right' class='letrap'> ";

                        echo "$Ele[descripcion] &nbsp; ";

                        echo "</td>";

                        echo "<td align='center' class='letrap'><input name='$Campo' value ='$calculo' type='text' size='10' class='letrap'> </td><td align='center' class='letrap'>$Ele[unidad]</td>";

                        echo "<td align='center' class='letrap'> " . number_format($Ele[min], '2') . " - " . number_format($Ele[max], '2') . "</td>";
                    }
                } elseif ($Ele[tipo] == "c") {

                    echo "<td align='right' class='letrap'> ";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</td><td align='center'class='letrap'> ";

                    echo "<input name='$Campo' class='letrap' value ='$Vlr[c]' type='text' size='25'>";

                    echo "</td>";

                    echo "<td align='center' class='letrap'> </td>";

                    echo "<td align='center' class='letrap'> " . $Ele[vtexto] . "</td>";
                } else {

                    echo "<td align='right' class='letrap'> ";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</td><td colspan='3' align='center' class='letrap'> ";

                    echo "<TEXTAREA class='letrap' NAME='$Campo' cols='70' rows='3' >$Vlr[t]</TEXTAREA></td>";
                }

                echo "</tr>";
                $nRng++;
            }
            echo "<tr><td align='right'>";

            echo "<input type='hidden' name='estudio' value=$estudio>";
            echo "<input type='hidden' name='busca' value=$busca>";
            echo "<input type='hidden' name='TFr' value=$TFr>";
            echo "<input type='hidden' name='sucorigen' value=$Ot[suc]>";
            echo "<input type='hidden' name='subdepto' value=$Est[subdepto]>";

            echo "<input type='hidden' name='op' value=rs>"; // Resultdos
/*
            if ($Otd[capturo] <> '' and $Otd[fr] == '1') {

                if ($Otd[usrvalida] == '') {

                    echo "<a class='letrap'>Para confirmar que tu captura es correcta, favor de poner tu clave de acceso($Gusr)</a>";

                    echo "<input type='password' class='letrap' name='Confirmacion' value=''>";
                } else {

                    echo "<a class='letrap'>!!! Actualizacion de captura, favor de poner tu clave de acceso($Gusr)!!!</a>";

                    echo "<input type='password' class='letrap' name='Confirmacion' value=''>";
                }
            } else {

                echo "<a class='letrap'>!!! Actualizacion de captura, favor de poner tu clave de acceso($Gusr)!!!</a>";

                echo "<input type='password' class='letrap' name='Confirmacion' value=''>";
            }
            */  
            echo "<br><input style='color: #000000; background-color: #7FB3D5' class='letrap' type='submit' name='Aceptar' value='Aceptar'></input></td>";
            echo "<td align='center' colspan='2' class='cMsj'><br><b> $Msj </b></td>";

            if ($Otd[capturo] <> '') {

                if ($Otd[status] == 'TERMINADA') {

                    echo "<td align='center'><br><input style='color: #000000; background-color: #F1948A' class='letrap' type='submit' name='Validar' value='Validar'></input> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$busca&Estudio=$estudio&Depto=TERMINADA&op=im&alterno=$alterno')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td></tr>";

                }else{

                    echo "<td align='center'><br><input style='color: #000000; background-color: #F1948A' class='letrap' type='submit' name='Validar' value='Validar'></input> &nbsp &nbsp &nbsp <font color='red'><b> <--- Validar para imprimir --- </b></font></td></tr>";

                }

            }else{
                echo "<td align='center'></td></tr>";
            }
            echo "</form>";
            ?>
        </table>
        <table width="80%">
            <tr class="letrap">
                <td align="center">
                    <b>
                        Captura:
                    </b>
                    <?=
                    $Otd[capturo] ." - ". $Otd[tres]
                    ?>
                </td>
                <td align="center">
                    <b>
                        Valida:
                    </b>
                    <?=
                    $Otd[usrvalida] ." - ". $Otd[fechavalida]
                    ?>
                </td>
                <td align="center">
                    <b>
                        Imprime:
                    </b>
                    <?=
                    $Otd[impest] ." - ". $Otd[impres]
                    ?>
                </td>
            </tr>
        </table>
        <table width="100%" border="0">
            <tr style='background-color: #D98880 ;border-collapse: collapse; border: 1px solid #bbb;height: 7px;'>
                <td align="right" class="letram" colspan="3">
                    Archivos Complementarios &nbsp; 
                </td>
            </tr>
        </table>

        <table width="100%" border="0">
            <tr style='background-color: #D98880 ;border-collapse: collapse; border: 1px solid #bbb;height: 7px;'>
                <td align="right" class="letram" colspan="3">
                    Historial clinico &nbsp; 
                </td>
            </tr>
        </table>

        <table class="letrap" border="0" width="90%" align="center">
            <tr>
                <td>
                    <b>
                        No.Orden
                    </b>
                </td>
                <td>
                    <b>
                        Fecha 
                    </b>
                </td>
                <td>
                    <b>
                        Estudio
                    </b>
                </td>
                <td>
                    <b>
                        Descripcion
                    </b>
                </td>
                <td>
                    <b>
                        Resultado
                    </b>
                </td>
            </tr>
            <?php
            $OtdA = mysql_query("SELECT ot.orden, ot.fecha, otd.estudio, est.descripcion, est.depto,otd.alterno, otd.capturo, otd.usrvalida, otd.fr, otd.creapdf
			FROM ot,otd LEFT JOIN est ON otd.estudio=est.estudio
			WHERE ot.cliente = '$Ot[cliente]' AND ot.orden=otd.orden ORDER by ot.orden desc");

            while ($reg = mysql_fetch_array($OtdA)) {

                if (($nRng % 2) > 0) {
                    $Fdo = 'FFFFFF';
                } else {
                    $Fdo = $Gfdogrid;
                }    //El resto de la division;

                $clnk = strtolower($reg[estudio]);

                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                //echo "<td align='center'><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
                echo "<td>$reg[fecha]</td>";
                echo "<td>$reg[orden]</td>";
                echo "<td>$reg[estudio]</td>";
                echo "<td>$reg[descripcion]</td>";

                if ($reg[capturo] <> '' and $reg[fr] == '0') {
                    if ($reg[depto] <> 2) {
                        if ($reg[creapdf] == 'pdf') {

                            echo "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&alterno=$reg[alterno]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td>";
                        } else {

                            echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$reg[alterno]')><i class='fa fa-print fa-lg' alt='Imprime Resultados' aria-hidden='true'  style='color:#283747'></i></a></td>";
                        }
                    } else {
                        echo "<td align='center'><a class='pg' href=javascript:wingral('pdfradiologia.php?busca=$reg[orden]&Estudio=$reg[estudio]')><i class='fa fa-print fa-lg' alt='Imprime Resultados' aria-hidden='true' style='color:#283747'></i></a></td>";
                    }
                } else {
                    if ($reg[capturo] <> '' and $reg[usrvalida] <> '') {
                        if ($reg[depto] <> 2) {
                            if ($reg[creapdf] == 'pdf') {

                                echo "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&alterno=$reg[alterno]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td>";
                            } else {

                                echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$reg[alterno]')><i class='fa fa-print fa-lg' alt='Imprime Resultados' style='color:#283747' aria-hidden='true'></i></a></td>";
                            }
                        } else {
                            echo "<td align='center'><a class='pg' href=javascript:wingral('pdfradiologia.php?busca=$reg[orden]&Estudio=$reg[estudio]')><i class='fa fa-print fa-lg' alt='Imprime Resultados' style='color:#283747' aria-hidden='true'></i></a></td>";
                        }
                    } else {
                        echo "<td align='center'>-</td>";
                    }
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
