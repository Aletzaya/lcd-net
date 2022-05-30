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
$validacampo = $_REQUEST[validacampo];
$campoval = $_REQUEST[campoval];

require './Services/CapturaResultadoService.php';

require ("config.php");          //Parametros de colores;

$Suc = $He["suc"];

$veces = $Cpo["numveces"];

$sucnombre = $aSucursal[$Suc];

$Sexo='';

if ($Cpo["sexo"]=='M') {
    $Sexo = "Masculino";
} elseif ($Cpo["sexo"]=='F') {
    $Sexo = "Femenino";
} else {
    $Sexo = "";
}

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
} elseif ($alterno == 4) {
    $tabla = 'elealtpdf4';
}

if($validacampo=='Si'){

    $Fechaval = date("Y-m-d H:i:s");
            
    $sql = mysql_query("INSERT INTO logresul (usr,accion,tabla,fecha,orden,estudio,elemento) VALUES ('$Gusr','Validada $campoval','resul','$Fechaval','$busca','$estudio','$campoval')");

}

  //*************************//

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

//****** Comparación de valores ***********//

$ElX = mysql_query("SELECT * FROM resul WHERE estudio='$estudio' and orden='$busca' ORDER BY elemento");

$numreg = mysql_num_rows($ElX);

if($numreg>0){

    while ($Elex = mysql_fetch_array($ElX)) {

        $Ressulele = $Elex[elemento];
    
        $tipos = mysql_query("SELECT tipo,calculo FROM $tabla WHERE estudio='$estudio' and id='$Ressulele' ");
        $tipo = mysql_fetch_array($tipos);
    
        if($tipo[tipo]=='c'){
            $Ressul = $Elex[c];
        }elseif($tipo[tipo]=='d'){
            $Ressul = $Elex[d];
        }elseif($tipo[tipo]=='n'){
            $Ressul = $Elex[n];
        }elseif($tipo[tipo]=='l'){
            $Ressul = $Elex[l];
        }elseif($tipo[tipo]=='t'){
            $Ressul = $Elex[t];
        }else{
            $Ressul = 'No';
        }
    
        $Resultadocap = $_REQUEST[$Ressulele];
    
        $Resultadodif=0;
        $Campo="";
    
        if($Ressul<>'No'){
            if($Ressul<>$Resultadocap){
                $Resultadodif++;
            }else{
                $Resultadodif=$Resultadodif;
            }
        }
 
        if($Resultadodif<>0 and $tipo[calculo]<>"Si"){
            $Fechaedita = date("Y-m-d H:i:s");
    
            $sql = mysql_query("INSERT INTO logresul (usr,accion,tabla,fecha,orden,estudio,elemento) VALUES ('$Gusr','Modifica $Resultadocap $Campo','resul','$Fechaedita','$busca','$estudio','$Ressulele')");
        }
    }
    
    $Capturaini='No';

}else{
    
    $Capturaini='Si';

}

//******** Captura nueva ****************//

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

            $Resultado= trim($Resultado," ");

        } else {

            $Campo = 't';
        }

        $lUp = mysql_query("INSERT INTO resul (orden,estudio,elemento,$Campo) 
                       VALUES
                       ('$busca','$estudio','$Ele[id]','$Resultado')");

        if($Capturaini==='Si'){
        
            $Ressulele = $Ele[id];  
            
            $Resultadocap = $_REQUEST[$Ressulele];

            if($Resultadocap<>''){

                $Fechaedita = date("Y-m-d H:i:s");
            
                $sql = mysql_query("INSERT INTO logresul (usr,accion,tabla,fecha,orden,estudio,elemento) VALUES ('$Gusr','Captura $Resultadocap $Campo','resul','$Fechaedita','$busca','$estudio','$Ressulele')");

            }
            
        }
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

    if($_REQUEST[Guardar] == "Guardar"){

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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Captura</title><link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
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
    <?php
    $nomcliente = ucwords(strtolower(substr($Cpo[nombrecli],0,50)));
    ?>
    <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
        <tr>
            <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: <?= $Cpo["institucion"] ?> - <?= $busca ?> - <?= $estudio . " .- " . ucwords(strtolower($Est[descripcion])) ?> ::..
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
                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm">
                            <strong>Institucion : </strong><br>
                            <div align="center"><?= $Cpo["institucion"] . " - " . $Cpo["nombrei"] ?></div>
                        </td>
                        <?php
                        $urgente1='';
                        $urgente2='';
                        if($Cpo["servicio"]=='Urgente'){
                            $colorserv='red';
                            $urgente1='<strong>';
                            $urgente2='</strong>';
                        }
                        ?>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='servicio') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Servicio : 
                                </strong><br>
                                <select name='Servicio' class="cinput">
                                    <option value="Ordinario">Ordinario</option>
                                    <option value="Urgente">Urgente</option>
                                    <option value="Express">Express</option>
                                    <option value="Hospitalizado">Hospitalizado</option>
                                    <option value="Nocturno">Nocturno</option>
                                    <option value='<?= $Cpo["servicio"] ?>' selected><?= $Cpo["servicio"] ?></option>
                                </select>
                            <?php
                            }else{
                            ?>
                                <strong>
                                 Servicio : 
                                </strong><br>
                                <div align="center"><?= $urgente1 ?><font color='<?= $colorserv ?>'>
                                <?= $Cpo["servicio"] ?> </font> <?= $urgente2 ?></div>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <strong>Sucursal : </strong><br>
                            <div align="center"><?= $Suc ?> - <?= $sucnombre ?></div>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <strong>Capturó : </strong><br>
                            <div align="center"><?= $Cpo["recepcionista"] ?></div>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <strong>Fech. Orden : </strong><br>
                            <div align="center"><?= $Cpo["fecha"] . " " . $Cpo["hora"] ?></div>
                        </td>
                    </tr>
                    <tr style="height: 30px" class="letrap">
                        <td width='28%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='nombre') {

                                $clinombreD = "SELECT cli.apellidop,cli.apellidom,cli.nombre,cli.nombrec
                                    FROM cli
                                    WHERE cli.cliente=$Cpo[cliente] limit 1;";
                                    $clinombre = mysql_query($clinombreD);

                                $nombrecli = mysql_fetch_array($clinombre);
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Paciente : 
                                </strong><br>
                                <table align="center">
                                    <tr class="content2">
                                        <td>
                                            <input type='text' class='cinput' name='Apellidop' value='<?= $nombrecli["apellidop"] ?>'></input>
                                        </td>
                                        <td>
                                            <input type='text' class='cinput' name='Apellidom' value='<?= $nombrecli["apellidom"] ?>'></input>
                                        </td>
                                        <td>
                                            <input type='text' class='cinput' name='Nombre' value='<?= $nombrecli["nombre"] ?>'></input>
                                        </td>                                    
                                    </tr>
                                </table>
                            <?php
                            }else{
                            ?>
                                <?php
                                if ($veces<='2') {
                                ?> 
                                    <strong>
                                     Paciente : 
                                    </strong><br>
                                    <div align="center"><strong><?= $Cpo["cliente"] . " - " . $nomcliente ?></strong></div>
                                <?php
                                }else{
                                ?>
                                    <strong>
                                     Paciente : 
                                    </strong><br>
                                    <div align="center"><strong><?= $Cpo["cliente"] . " - " . $nomcliente ?></strong></div>
                                <?php
                                }
                                ?>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='sexo') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Genero : 
                                </strong><br>
                                <select name='Sexo' class="cinput">
                                    <?php
                                    if($Cpo["sexo"]=='M'){
                                    ?>
                                        <option value="F">Femenino</option>
                                    <?php
                                    }elseif($Cpo["sexo"]=='F'){
                                    ?>
                                        <option value="M">Masculino</option>
                                    <?php
                                    }else{
                                    ?>
                                        <option value="F">Femenino</option>
                                        <option value="M">Masculino</option>
                                    <?php
                                    }
                                    ?>
                                    <option value='<?= $Cpo["sexo"] ?>' selected><?= $Sexo ?></option>
                                </select>
                            <?php
                            }else{
                            ?>
                                <strong>
                                 Genero : 
                                </strong><br>
                                <div align="center"><?= $Sexo ?></div>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='fechanac' or $cambio=='edad') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Fech. Nacim. : 
                                </strong><br>
                                <input type='date' class='letrap' name='fechan' id="Fechan" value ='<?= $Cpo["fechan"] ?>'/>
                            <?php
                            }else{
                            ?>
                                <?php
                                if ($veces<='2') {
                                ?> 
                                    <strong>
                                     Fech. Nacim. : 
                                    </strong><br>
                                    <div align="center"><?= $Cpo["fechan"] ?></div>
                                <?php
                                }else{
                                ?>
                                    <strong>
                                     Fech. Nacim. : 
                                    </strong><br>
                                    <div align="center"><?= $Cpo["fechan"] ?></div>
                                <?php
                                }
                                ?>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='edad') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Edad : 
                                </strong><br>
                                <input type='number' style="width: 50px;" class='cinput'  name='Edad' id="Edad"></input>
                                <input type="button" name="Calcular" id="Calcular" value="Calcula" class="letrap"></input>
                            <?php
                            }else{
                            ?>
                                <strong>
                                 Edad : 
                                </strong><br>
                                <div align="center"><?= $anos . " Años " . $meses . " Meses" ?></div>
                            <?php
                            }
                            ?>
                        </td>
                        <td width='18%' class="ssbm" align="lefth">
                            <?php
                            if ($cambio=='fecha') {
                            ?> 
                                <strong>
                                <i class="fa fa-square fa-lg" style="color:RED;" aria-hidden="true">
                                </i><a>
                                 Fech. Entrega : 
                                </strong><br>
                                <input type='datetime-local' class='letrap' name='fecha' value ='<?= $Cpo["fechae"] . "T" . $Cpo["horae"] ?>'/>
                            <?php
                            }else{
                            ?>
                                <strong>
                                 Fech. Entrega : 
                                </strong><br>
                                <div align="center"><?= $Cpo["fechae"] . " " . $Cpo["horae"] ?></div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    if($Cpo["medico"]=='MD'){
                        $nommedico = ucwords(strtolower(substr($Cpo[medicon],0,50)));
                    }else{
                        $nommedico = ucwords(strtolower(substr($Cpo[nombremed],0,50)));
                    }
                    ?>
                    <tr style="height: 30px" class="letrap"  bgcolor='#f1f1f1'>
                        <td width='28%'align="lefth" class="ssbm">
                            <strong>Médico : </strong><br>
                            <div align="center"><?= $Cpo["medico"] . " - " . $nommedico ?></div>
                        </td>
                        <td width='5%' valign='top' align="center" class="ssbm2" colspan="3"><?= $idprocedencia ?> &nbsp; &nbsp; <?= $idprocedencia2 ?></a> &nbsp; &nbsp;<?= $idprocedencia3 ?> &nbsp; &nbsp; <?= $idprocedencia4 ?> &nbsp; &nbsp; <?= $idprocedencia5 ?> &nbsp; &nbsp;  &nbsp; &nbsp; </td>
                        <td width='28%'align="center" class="ssbm2">
                        </td>
                    </tr>

                    <tr style="height: 30px" class="letrap">
                        <td width='28%'align="lefth" class="ssbm" colspan="3">
                            <strong>Obs. Grales. : </strong><br>
                            <div align="center"><?= $Cpo["observaciones"] ?></div>
                        </td>
                        <?php
                            $Obsest = mysql_query("SELECT otd.obsest FROM otd WHERE estudio='$estudio' AND orden='$busca'");
                            $Obse = mysql_fetch_array($Obsest);
                        ?>
                        <td width='28%'align="lefth" class="ssbm" colspan="2">
                            <strong>Obs. Indv. : </strong><br>
                            <div align="center"><?= $Obse["obsest"] ?></div>
                        </td>
                    </tr>
                    </tr>
                </table> 
            </td>
        </tr>      
    </table>  

    <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="5" style="background-color: #2c8e3c" width='100%' class='letratitulo' align='center'>
                    ..:: Captura de resultados ::..
                </td>
            </tr>
            <tr>
                <td align="center" colspan="5"> 
                    <?php
                    $EleA = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' ORDER BY id");

                    $OtdA = mysql_query("SELECT otd.status,otd.capturo,otd.tres,otd.usrvalida,otd.fechavalida,otd.impest,otd.impres,otd.fr,otd.creapdf FROM otd WHERE estudio='$estudio' AND orden='$busca'");
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
                <td align="center" width="20%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=0&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Estandar</a>
                    <?php
                    if ($_REQUEST[alterno] == 0) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?>
                </td>
                <td align="center"  width="20%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=1&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Alternativa</a>
                    <?php
                    if ($_REQUEST[alterno] == 1) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?> 
                </td>
                <td align="center"  width="20%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=2&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Alternativa 2</a>
                    <?php
                    if ($_REQUEST[alterno] == 2) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?>
                </td>
                <td align="center"  width="20%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=3&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Alternativa 3</a>
                    <?php
                    if ($_REQUEST[alterno] == 3) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?>
                </td>
                <td align="center"  width="20%">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?alterno=4&busca=<?= $busca ?>&estudio=<?= $estudio ?>">Captura Alternativa 4</a>
                    <?php
                    if ($_REQUEST[alterno] == 4) {
                        echo "<i class='fa fa-check' aria-hidden='true' style='color:#82E0AA'></i>";
                    }
                    ?>
                </td>
            </tr>
        </table>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <?php
            $Validaciocero=0;
            echo "<form name='form1' method='post' action='estdeptocvalcppdf.php?busca=$busca&estudio=$estudio&alterno=$alterno'>";

            while ($Ele = mysql_fetch_array($EleA)) {

                $VlrA = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[id]'");
                $Vlr = mysql_fetch_array($VlrA);

                $Modif = mysql_query("SELECT usr,fecha,accion FROM logresul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[id]' order by id desc limit 1");
                $Mod = mysql_fetch_array($Modif);

                $Conmodf = mysql_query("SELECT count(*) as contad FROM logresul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[id]'");
                $Conmod = mysql_fetch_array($Conmodf);

                $Campo = $Ele[id];

                if (($nRng % 2) > 0) {
                    $Fdo = 'FFFFFF';
                } else {
                    $Fdo = "EAECEE";
                }    //El resto de la division;


                if ($Otd[capturo] <> '') {

                    if ($Ele[tipo] == 'n') {

                            if($Vlr[n] < $Ele[min]  OR $Vlr[n] > $Ele[max]){
                                $Fdo='D98880';
                                $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>";
                                $TFr=$TFr+1;

                                $Tcap = substr($Mod[accion],0,8);
                                if($Tcap=='Validada'){
                                    $Confirma = "<b><i class='fa fa-check-square-o fa-lg' style='color:green;' aria-hidden='true'></i><b>";
                                }else{
                                    $Confirma = "<a href='estdeptocvalcppdf.php?busca=$busca&estudio=$estudio&alterno=$alterno&validacampo=Si&campoval=$Campo'><b><i class='fa fa-ban fa-lg' style='color:black;' aria-hidden='true'></i><b></a>";
                                    $Validaciocero++;
                                }
                        
                            }else{
                                if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                                $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                                $Confirma = "";
                            }                            

                    }elseif ($Ele[tipo] == 'c') {

                        if ($Ele[valref] == 'Si') {

                            $comparatxt=strcasecmp($Vlr[c],$Ele[vtexto]);

                            if($comparatxt<>0){
                                $Fdo='D98880';
                                $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>";
                                $TFr=$TFr+1;
        
                                $Tcap = substr($Mod[accion],0,8);
                                if($Tcap=='Validada'){
                                    $Confirma = "<b><i class='fa fa-check-square-o fa-lg' style='color:green;' aria-hidden='true'></i><b>";
                                }else{
                                    $Confirma = "<a href='estdeptocvalcppdf.php?busca=$busca&estudio=$estudio&alterno=$alterno&validacampo=Si&campoval=$Campo'><b><i class='fa fa-ban fa-lg' style='color:black;' aria-hidden='true'></i><b></a>";
                                    $Validaciocero++;
                                }
                           
                            }else{

                                if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                                $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                                $Confirma = "";

                            }

                        } else {
            
                            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                            $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                            $Confirma = "";

                        }


                    }elseif ($Ele[tipo] == 'l') {

                        if ($Ele[valref] == 'Si') {

                            if ($Vlr[l] == 'N') {
                                $logico = 'Negativo';
                            } elseif ($Vlr[l] == 'S') {
                                $logico = 'Positivo';
                            } else {
                                $logico = '** En Proceso';
                            }

                            if($logico<>$Ele[vlogico]){
                                $Fdo='D98880';
                                $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>";
                                $TFr=$TFr+1;
        
                                $Tcap = substr($Mod[accion],0,8);
                                if($Tcap=='Validada'){
                                    $Confirma = "<b><i class='fa fa-check-square-o fa-lg' style='color:green;' aria-hidden='true'></i><b>";
                                }else{
                                    $Confirma = "<a href='estdeptocvalcppdf.php?busca=$busca&estudio=$estudio&alterno=$alterno&validacampo=Si&campoval=$Campo'><b><i class='fa fa-ban fa-lg' style='color:black;' aria-hidden='true'></i><b></a>";
                                    $Validaciocero++;
                                }
                           
                            }else{

                                if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                                $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                                $Confirma = "";

                            }

                        } else {
            
                            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                            $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                            $Confirma = "";

                        }

                    }                 
                }else{

                        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                        $Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
                        $Confirma = "";
                }

                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                if ($Ele[tipo] == "e") {

                    $Alineacion = $Ele[alineacion];

                    echo "<td align='center' colspan='8' style='background-color: #aeb5ee;color: #000;'><b><font size='2'>";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</b></font></td>";
                } elseif ($Ele[tipo] == "s") {

                    echo "<td align='center' colspan='8' style='background-color: #e8eeae;color: #000;'><b><font size='2'>";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</b></font></td>";
                } elseif ($Ele[tipo] == "v") {

                    echo "<td align='center' colspan='8' style='background-color: #FFFFFF;color: #FFFFFF;'>";

                    echo " &nbsp; ";

                    echo "</td>";
                } elseif ($Ele[tipo] == "z") {

                echo "<tr height='21' style='background-color: #566573 ;color: #000;''>
                <td align='center' class='letratitulo'><font size='1'><b>Elementos</b></font></td>
                <td align='center' class='letratitulo' colspan='3'><font size='1'><b>Resultados</b></font></td>
                <td align='center' class='letratitulo'><font size='1'><b>Valores de Referencia</b></font></td>
                <td align='center' class='letratitulo'><font size='1'><b>Fecha</b></font></td>
                <td align='center' class='letratitulo'><font size='1'><b>Usuario</b></font></td>
                <td align='center' class='letratitulo'><font size='1'><b>Movimiento</b></font></td></tr>";
                
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

                    echo "<td align='center' class='letrap'>$Confirma</td>";

                    echo "<td align='center' class='letrap'></td>";

                    echo "<td align='center' class='letrap'> " . $Ele[vlogico] . "</td>";

                } elseif ($Ele[tipo] == "d") {

                    echo "<td align='right' class='letrap'> ";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</td>";

                    echo "<td align='center' colspan='2'><input class='letrap' name='$Campo' value ='$Vlr[d]' type='text' size='11' ></td>";
                    
                    echo "<td align='center' class='letrap'>$Confirma</td>";

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

                        echo "<td align='center' class='letrap'> $calculo </td><td align='center' class='letrap'> $Confirma</td><td align='center' class='letrap'>  $Ele[unidad]</td>";

                        echo "<td align='center' class='letrap'> " . number_format($Ele[min], '2') . " - " . number_format($Ele[max], '2') . "</td>";
                    } else {

                        $calculo = $Vlr[n];

                        echo "<td align='right' class='letrap'> ";

                        echo "$Ele[descripcion] &nbsp; ";

                        echo "</td>";

                        echo "<td align='center' class='letrap'><input name='$Campo' value ='$calculo' type='text' size='10' class='letrap'></td><td align='center' class='letrap'> $Confirma</td><td align='center' class='letrap'>$Ele[unidad]</td>";

                        echo "<td align='center' class='letrap'> " . number_format($Ele[min], '2') . " - " . number_format($Ele[max], '2') . "</td>";
                    }
                } elseif ($Ele[tipo] == "c") {

                    echo "<td align='right' class='letrap'> ";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</td><td align='center'class='letrap'> ";

                    echo "<input name='$Campo' class='letrap' value ='$Vlr[c]' type='text' size='25'>";

                    echo "</td>";

                    echo "<td align='center' class='letrap'> $Confirma</td>";

                    echo "<td align='center' class='letrap'></td>";

                    echo "<td align='center' class='letrap'> " . $Ele[vtexto] . "</td>";
                } else {

                    echo "<td align='right' class='letrap'> ";

                    echo "$Ele[descripcion] &nbsp; ";

                    echo "</td><td colspan='3' align='center' class='letrap'> ";

                    echo "<TEXTAREA class='letrap' NAME='$Campo' cols='70' rows='3' >$Vlr[t]</TEXTAREA></td><td></td>";
                }

                if($Ele[tipo] == "c" or $Ele[tipo] == "n" or $Ele[tipo] == "l" or $Ele[tipo] == "d" or $Ele[tipo] == "t"){

                    $Tcap = substr($Mod[accion],0,8);

                    echo "<td align='center' class='letrap'><font size='1'>" . $Mod[fecha] . "</font></td>";
                    echo "<td align='center' class='letrap'><font size='1'>" . $Mod[usr] . "</font></td>";
                    echo "<td align='center' class='letrap'><font size='1'>" . $Tcap . "</font></td>";

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
            echo "<br><input style='color: #000000; background-color: #7FB3D5' class='letrap' type='submit' name='Guardar' value='Guardar'></input></td>";
            echo "<td align='center' colspan='3' class='cMsj'><br><b> $Msj </b></td>";
           // echo "<td align='center' colspan='3'><br><input style='color: #000000; background-color: #F1948A' class='letrap' type='submit' name='Validar' value='Validar'></input> &nbsp &nbsp &nbsp <font color='red'><b> <--- Validar para imprimir --- </b></font></td></tr>";


            if ($Otd[capturo] <> '') {

                if ($Otd[status] == 'TERMINADA') {

                        echo "<td align='center' colspan='3'><br><input style='color: #000000; background-color: #F1948A' class='letrap' type='submit' name='Validar' value='Validar'></input> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <a href=javascript:wingral2('resultapdf.php?clnk=$clnk&Orden=$busca&Estudio=$estudio&Depto=TERMINADA&op=im&alterno=$alterno')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td></tr>";
            
                }else{

                    if($Validaciocero==0){
                        echo "<td align='center' colspan='3'><br><input style='color: #000000; background-color: #F1948A' class='letrap' type='submit' name='Validar' value='Validar'></input> &nbsp &nbsp &nbsp <font color='red'><b> <--- Validar para imprimir --- </b></font></td></tr>";
                     }
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

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='100%' class='letratitulo' align='center'>
                    ..:: Archivos Complementarios ::..
                </td>
            </tr>
        </table>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td align="center" width="80%" height="300px" valign="top">

                <iframe id="Historial"
                    title="Historial del Cliente"
                    width="100%"
                    height="100%"
                    src="historialcliente.php?cliente=<?=$Ot[cliente]?>">
                </iframe>

                </td>
            </tr>
        </table>
        <br><br>
    </body>
</html>
<?php
mysql_close();
?>
