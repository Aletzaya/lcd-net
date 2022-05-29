<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d");
$Msj = $_REQUEST["Msj"];
$Fechaest  = date("Y-m-d H:i:s");
$Hora      = date("H:i:s");
$Estudio   = $_REQUEST[Estudio];
$Estudio2   = $_REQUEST[Estudio];
$status    = $_REQUEST[status];
$muestras    = $_REQUEST[muestras];
$nmuestras    = $_REQUEST[nmuestras];
$Recoleccion = $_REQUEST[Recoleccion];
$Op = $_REQUEST[Op];
$cambio = $_REQUEST[cambio];
$fechaot = $_REQUEST[fecha];
$fechan = $_REQUEST[fechan];
$sexocli = $_REQUEST[Sexo];
$serviciot = $_REQUEST[Servicio];
$Apellidop = $_REQUEST[Apellidop];
$Apellidom = $_REQUEST[Apellidom];
$Nombre = $_REQUEST[Nombre];

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

//****************** Cambios ***************//

if ($_REQUEST["Boton"] === "Cambiar") {

    if($cambio=='fecha'){
        $fechacambio=substr($fechaot, 0,10);
        $horacambio=substr($fechaot, 11,5);
        if($Cpo["fecha"]<=$fechacambio){
            if($Cpo["fecha"]==$fechacambio and $Cpo["hora"]>$horacambio){
                $Msj='Hora no puede ser anterior a la Hora de la captura';
                $Error='SI';
            }else{

                $Up2  = mysql_query("UPDATE ot SET fechae='$fechacambio', horae='$horacambio'
                WHERE orden='$busca' limit 1;"); 

                $Msj='Cambio de Fecha y Hora Correcta '.$fechacambio.' - '.$horacambio;
                $cambio = '';

                AgregaBitacoraEventos($Gusr, '/Toma de muestras/Cambio de Fecha-Hora Entrega '.$fechacambio.' - '.$horacambio, "ot", $Fechaest, $busca, $Msj, "capturaresultadod.php");

            }

        }else{
            $Msj='Fecha no puede ser anterior a la fecha de la captura';
            $Error='SI';
        }
    }elseif($cambio=='sexo'){

        $Up2  = mysql_query("UPDATE cli SET sexo='$sexocli'
        WHERE cliente=$Cpo[cliente] limit 1;"); 

        $Msj='Cambio de Genero del cliente con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Toma de muestras/Cambio de Genero '.$sexocli, "ot", $Fechaest, $busca, $Msj, "capturaresultadod.php");

    }elseif($cambio=='servicio'){

        $Up2  = mysql_query("UPDATE ot SET servicio='$serviciot'
        WHERE orden='$busca' limit 1;"); 

        $Msj='Cambio de Servicio con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Toma de muestras/Cambio de Servicio '.$serviciot, "ot", $Fechaest, $busca, $Msj, "capturaresultadod.php");

    }elseif($cambio=='nombre'){

        $Nombrec= $Apellidop.' '.$Apellidom.' '.$Nombre;

        $Up2  = mysql_query("UPDATE cli SET apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',nombrec='$Nombrec'
        WHERE cliente=$Cpo[cliente] limit 1;"); 

        $Msj='Cambio de Nombre paciente con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Toma de muestras/Cambio de Nombre paciente ', "ot", $Fechaest, $busca, $Msj, "capturaresultadod.php");

    }elseif($cambio=='fechanac' or $cambio=='edad'){

        $Up2  = mysql_query("UPDATE cli SET fechan='$fechan'
        WHERE cliente=$Cpo[cliente] limit 1;"); 

        $Msj='Cambio de Fecha de Nac del cliente con Exito';
        $cambio = '';

        AgregaBitacoraEventos($Gusr, '/Toma de muestras/Cambio de Fecha de Nac ', "ot", $Fechaest, $busca, $Msj, "capturaresultadod.php");

    }

}elseif ($_REQUEST["Boton"] === "Cancelar") {

        $cambio = '';

}
//****************** recoleccion ***************//

    if($Op==1){
        
        if($_REQUEST[regis]=='1'){

            $Up2  = mysql_query("UPDATE otd SET recoleccionest='$Recoleccion'
                WHERE orden='$busca' and estudio='$Estudio' limit 1"); 

        }else{

            $Up2  = mysql_query("UPDATE otd SET recoleccionest='$Recoleccion'
                WHERE orden='$busca'"); 
        }

    }elseif($Op==2){

        if($_REQUEST[regis]=='1'){

            $Up2  = mysql_query("UPDATE otd SET recoleccionest='$Recoleccion'
                WHERE orden='$busca' AND usrest=''"); 

        }else{

            $Up2  = mysql_query("UPDATE otd SET recoleccionest='$Recoleccion'
                WHERE orden='$busca' AND usrest=''"); 
        }
    }

  //*************************//

  if($_REQUEST[op]=='1'){
      if($_REQUEST[regis]=='1'){
            $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Gusr', statustom='$status', status='$status' WHERE orden='$busca' AND estudio='$Estudio' limit 1");

            $OtdA  = mysql_query("SELECT dos,lugar,estudio FROM otd WHERE orden='$busca' AND estudio='$Estudio'");
             
                while ($Otd  = mysql_fetch_array($OtdA)){    
                 $Est  = $Otd[estudio];  
                    if(substr($Otd[dos],0,4)=='0000'){     
                        if($Otd[lugar] <= '3'){          
                            $lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
                             WHERE orden='$busca' and estudio='$Estudio' limit 1");                     
                        }else{           
                          $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
                             WHERE orden='$busca' AND estudio='$Estudio' limit 1");           
                        }
                    }

                    $SqlC="SELECT *
                    FROM maqdet
                    WHERE maqdet.orden='$busca' AND maqdet.estudio='$Est'";

                    $resC=mysql_query($SqlC,$link);

                    $registro4=mysql_fetch_array($resC);

                    if (empty($registro4)) {

                        $lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
                        VALUES
                        ('$busca','$Est','$Suc','$Fecha','$Hora','$Gusr')");
                    }else{
                        $lUp    = mysql_query("UPDATE maqdet SET orden='$busca',estudio='$Est',mint='$Suc',fenv='$Fecha',henv='$Hora',usrenv='$Gusr' WHERE maqdet.orden='$busca' AND maqdet.estudio='$Est' limit 1");
                    }   
                }

            $Msj='Regist de Estudio '.$Estudio.' con exito';

            AgregaBitacoraEventos2($Gusr, '/Toma de muestras/Regist de Est '.$Estudio.' '.$status.'-'.$Recoleccion, "otd", $Fechaest, $busca, $Msj, "capturaresultadod.php");

      }else{

            $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Gusr', statustom='$status', status='$status'
              WHERE orden='$busca' AND estudio='$Estudio' limit 1");

            $Msj='Regist de Estudio '.$Estudio.' con exito';

            AgregaBitacoraEventos2($Gusr, '/Toma de muestras/Regist de Est '.$Estudio.' '.$status.'-'.$Recoleccion, "otd", $Fechaest, $busca, $Msj, "capturaresultadod.php");

      }

       $NumA1  = mysql_query("SELECT otd.estudio 
       FROM otd 
       WHERE otd.orden='$busca' AND otd.statustom='PENDIENTE'");
       
       $NumA2  = mysql_query("SELECT otd.estudio 
       FROM otd 
       WHERE otd.orden='$busca' AND otd.statustom=''");

        if(mysql_num_rows($NumA1)>=1){
              $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$busca' limit 1");
        }else{ 
             if(mysql_num_rows($NumA2)==0){
                $lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$busca' limit 1");
             }else{ 
                $lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$busca' limit 1");
             } 
         } 

  }elseif($_REQUEST[op]=='2'){
      if($_REQUEST[regis]=='1'){

            $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Gusr', statustom='$status', status='$status'
              WHERE orden='$busca' AND usrest=''");
                      
            $OtdA  = mysql_query("SELECT dos,lugar,estudio,usrest FROM otd WHERE orden='$busca'");
             
            while ($Otd  = mysql_fetch_array($OtdA)){    
                 $Est  = $Otd[estudio];  
                    if(substr($Otd[dos],0,4)=='0000'){     
                        if($Otd[lugar] <= '3'){          
                            $lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
                             WHERE orden='$busca' AND usrest=''");                     
                        }else{           
                          $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
                             WHERE orden='$busca' AND usrest=''");           
                        }
                    } 

                    $SqlC="SELECT *
                    FROM maqdet
                    WHERE maqdet.orden='$busca' AND maqdet.estudio='$Est'";

                    $resC=mysql_query($SqlC,$link);

                    $registro4=mysql_fetch_array($resC);

                    if (empty($registro4)) {

                        $lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
                        VALUES
                        ('$busca','$Est','$Suc','$Fecha','$Hora','$Gusr')");
                    }else{
                        $lUp    = mysql_query("UPDATE maqdet SET orden='$busca',estudio='$Est',mint='$Suc',fenv='$Fecha',henv='$Hora',usrenv='$Gusr' WHERE maqdet.orden='$busca' AND maqdet.estudio='$Est' limit 1");
                    }
                    
            }

        }else{

            $OtdD  = mysql_query("SELECT orden as contarot FROM otd WHERE orden='$busca' AND usrest=''");

            if(mysql_num_rows($OtdD)>=1){

                $Msj='Regist de Estudios con exito';

                AgregaBitacoraEventos2($Gusr, '/Toma de muestras/Regist de Est '.$status, "otd", $Fechaest, $busca, $Msj, "capturaresultadod.php");
            }

            $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Gusr', statustom='$status', status='$status'
              WHERE orden='$busca' AND usrest=''");
        }
      
        $NumA1  = mysql_query("SELECT otd.estudio 
        FROM otd 
        WHERE otd.orden='$busca' AND otd.statustom='PENDIENTE'");

        $NumA2  = mysql_query("SELECT otd.estudio 
        FROM otd 
        WHERE otd.orden='$busca' AND otd.statustom=' '");

        if(mysql_num_rows($NumA1)>=1){
              $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$busca'");
        }else{ 
             if(mysql_num_rows($NumA2)==0){
                $lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$busca'");
             }else{ 
                $lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$busca'");
             } 
         } 

  }elseif($_REQUEST[op]=='3'){

    if ($He["idprocedencia"] == $_REQUEST[opc]) {

        $opc     = '';

    }else{

        $opc     = $_REQUEST[opc];

    }

    $lUp=mysql_query("UPDATE ot set idprocedencia = '$opc' WHERE orden='$busca' limit 1");

    $Msj='Cambio de Tipo Paciente con exito';

    AgregaBitacoraEventos($Gusr, '/Toma de muestras/Regist Tipo Paciente ', "ot", $Fechaest, $busca, $Msj, "capturaresultadod.php");

  }elseif($_REQUEST[op]=='4'){

    $interpreta     = $_REQUEST[interpreta];

    $lUp=mysql_query("UPDATE otd set interpretacion = '$interpreta' WHERE orden='$busca' AND estudio='$Estudio' limit 1");

    $Msj='Cambio de interpretacion con exito';

    AgregaBitacoraEventos2($Gusr, '/Toma de muestras/Regist interpretacion ', "ot", $Fechaest, $busca, $Msj, "capturaresultadod.php");
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
  
  //*************************//

require ("config.php");          //Parametros de colores;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Detalle de Toma de Muestra</title>
    <?php require ("./config_add.php"); ?>
</head>
<body topmargin="1">
    <?php
    $nomcliente = ucwords(strtolower(substr($Cpo[nombrecli],0,50)));
    ?>
    <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
        <tr>
            <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Toma de Muestra Orden: <?= $Cpo["institucion"] ?> - <?= $busca ?> - <?= $nomcliente ?> ::..
            </td>
        </tr>
        <tr>
            <td valign='top' align='center' height='130' width='100%'>
                <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 1px solid #999;'>  
                    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
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
                                <a href='capturaresultadod.php?busca=<?=$busca?>&cambio=servicio'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
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
                                    <a href='capturaresultadod.php?busca=<?=$busca?>&cambio=nombre'>
                                    <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                    </i><a>
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
                                <a href='capturaresultadod.php?busca=<?=$busca?>&cambio=sexo'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
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
                                    <a href='capturaresultadod.php?busca=<?=$busca?>&cambio=fechanac'>
                                    <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                    </i><a>
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
                                <a href='capturaresultadod.php?busca=<?=$busca?>&cambio=edad'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
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
                                <a href='capturaresultadod.php?busca=<?=$busca?>&cambio=fecha'>
                                <i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true">
                                </i><a>
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
                        <td width='5%' valign='top' align="center" class="ssbm2" colspan="3"><a href="capturaresultadod.php?busca=<?=$busca?>&op=3&opc=ambulancia"><?= $idprocedencia ?></a> &nbsp; &nbsp; <a href="capturaresultadod.php?busca=<?=$busca?>&op=3&opc=silla"> <?= $idprocedencia2 ?></a> &nbsp; &nbsp;<a href="capturaresultadod.php?busca=<?=$busca?>&op=3&opc=terceraedad"><?= $idprocedencia3 ?></a> &nbsp; &nbsp; <a href="capturaresultadod.php?busca=<?=$busca?>&op=3&opc=problemasv"><?= $idprocedencia4 ?></a> &nbsp; &nbsp; <a href="capturaresultadod.php?busca=<?=$busca?>&op=3&opc=bebe"><?= $idprocedencia5 ?></a> &nbsp; &nbsp;  &nbsp; &nbsp; </td>
                        <td width='28%'align="center" class="ssbm2">
                            <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                            <input type="hidden" name="cambio" value="<?= $cambio ?>"></input>
                            <?php
                            if($cambio<>''){
                            ?>
                                <input class="letrap" type="submit" name="Boton" value="Cambiar"></input>
                                &nbsp;
                                <input class="letrap" type="submit" name="Boton" value="Cancelar"></input>
                            <?php                               
                            }
                            ?>
                        </td>
                    </tr>
                    </form>
                </table> 
            </td>
        </tr>      
    </table>  

    <table width='97%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>

        <tr bgcolor="#2c8e3c">
            <td class='letratitulo' align="center" colspan="11">..:: Detalle de Estudios ::..</td>
        </tr>
        <tr style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
            <td class="letrap" align="center" rowspan="2">
                <strong>Etiq</strong>
            </td>
            <td class="letrap" align="center" rowspan="2">
                <strong>Obs</strong>
            </td>
            <td class="letrap" colspan="2" align="center" rowspan="2">
                <strong>Estudios</strong>
            </td>
            <td class="letrap" align="center" rowspan="2">
                <strong>Interp</strong>
            </td>
            <td class="letrap" align="center" rowspan="2">
                <strong><a class='edit' href='capturaresultadod.php?busca=<?=$busca?>&op=2&Op=2&regis=1&status=TOMA/REALIZ'>Toma/Realiz</a></strong>
            </td>
            <td class="letrap" align="center" colspan="3">
                <strong>Recoleccion</strong>
            </td>
            <td class="letrap" align="center" rowspan="2">
                <strong><a class='edit' href='capturaresultadod.php?busca=<?=$busca?>&Op=2&op=2&status=PENDIENTE'>Pendiente</a></strong>
            </td>
            <td class="letrap" align="center" rowspan="2">
                <strong>No Conf</strong>
            </td>
        </tr>    
        <tr style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">

            <td class="letrap" align="center">
                <strong><a class='edit' href='capturaresultadod.php?busca=<?=$busca?>&Recoleccion=Interna&status=RECOLECCION&op=2&Op=2'>Interna</a></strong>
            </td>            
            <td class="letrap" align="center">
                <strong><a class='edit' href='capturaresultadod.php?busca=<?=$busca?>&Recoleccion=ExternaInstitucional&status=RECOLECCION&op=2&Op=2'>Institucion</a></strong>
            </td>            
            <td class="letrap" align="center">
                <strong><a class='edit' href='capturaresultadod.php?busca=<?=$busca?>&Recoleccion=Remitida&status=RECOLECCION&op=2&Op=2'>Remitida</a></strong>
            </td>

        </tr> 
        <?php
        $sql = "SELECT otd.estudio,otd.status,est.descripcion,otd.precio,otd.descuento,est.muestras,otd.etiquetas,otd.capturo,est.depto,otd.recibeencaja,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,otd.statustom,otd.usrvalida,otd.obsest,otd.usrest,otd.fechaest,otd.etiquetas,otd.alterno,otd.creapdf,est.subdepto,est.indmuestras,otd.nmuestras,otd.recoleccionest,otd.interpretacion,otd.noconformidad
            FROM otd,est
            WHERE otd.estudio=est.estudio AND otd.orden='$busca'";
            $result3 = mysql_query($sql);
        while ($cSql = mysql_fetch_array($result3)) {

            if (($nRng % 2) > 0) { $Fdo = 'FFFFFF';  } else { $Fdo = $Gfdogrid; }    //El resto de la division;

            if($cSql[depto]==2){ 

                if($cSql[interpretacion]=='Si'){  

                    $Interpreta = '<a href="capturaresultadod.php?busca='.$busca.'&op=4&Estudio='.$cSql[estudio].'&interpreta=No"><i class="fa fa-check-circle fa-lg" style="color:green" aria-hidden="true"></i></a>';
                }else{

                    $Interpreta = '<a href="capturaresultadod.php?busca='.$busca.'&op=4&Estudio='.$cSql[estudio].'&interpreta=Si"><i class="fa fa-times-circle fa-lg" aria-hidden="true" style="color:#DC7633"></i></a>';
                }

            }else{

                $Interpreta = '';

            } 
         
            if($cSql[statustom]=='TOMA/REALIZ'){  

                $Toma = $cSql[usrest];
                $FechaToma = $cSql[fechaest];

            }else{

                $Toma = '<a href="capturaresultadod.php?busca='.$busca.'&op=1&Estudio='.$cSql[estudio].'&regis=1&status=TOMA/REALIZ&muestras=1"><i class="fa fa-minus-circle fa-lg" style="color:#CD5C5C;" aria-hidden="true"></i></a>';
                $FechaToma = '';

            }  

            if($cSql[statustom]=='PENDIENTE'){  

                $Pendiente = $cSql[usrest];
                $FechaPendiente = $cSql[fechaest];

            }else{

                $Pendiente = '<a href="capturaresultadod.php?busca='.$busca.'&op=1&Estudio='.$cSql[estudio].'&status=PENDIENTE&muestras=1"><i class="fa fa-minus-circle fa-lg" style="color:#EC2E2E;" aria-hidden="true"></i></a>';
                $FechaPendiente = '';

            }  

            $Interna = '<a href="capturaresultadod.php?busca='.$busca.'&op=1&Estudio='.$cSql[estudio].'&regis=1&status=RECOLECCION&Recoleccion=Interna&Op=1"><i class="fa fa-minus-circle fa-lg" style="color:#FA8072;" aria-hidden="true"></i></a>';
            $FechaInterna = '';

            $Externa = '<a href="capturaresultadod.php?busca='.$busca.'&op=1&Estudio='.$cSql[estudio].'&regis=1&status=RECOLECCION&Recoleccion=ExternaInstitucional&Op=1"><i class="fa fa-minus-circle fa-lg" style="color:#FA8072;" aria-hidden="true"></i></a>';
            $FechaExterna = '';

            $Remitida = '<a href="capturaresultadod.php?busca='.$busca.'&op=1&Estudio='.$cSql[estudio].'&regis=1&status=RECOLECCION&Recoleccion=Remitida&Op=1"><i class="fa fa-minus-circle fa-lg" style="color:#FA8072;" aria-hidden="true"></i></a>';
            $FechaRemitida = '';


            if($cSql[statustom]=='RECOLECCION'){  

                if($cSql[recoleccionest]=='Interna'){  
                    
                    $Interna = $cSql[usrest];
                    $FechaInterna = $cSql[fechaest];

                }elseif($cSql[recoleccionest]=='ExternaInstitucional'){
                    
                    $Externa = $cSql[usrest];
                    $FechaExterna = $cSql[fechaest];

                }elseif($cSql[recoleccionest]=='Remitida'){
                    
                    $Remitida = $cSql[usrest];
                    $FechaRemitida = $cSql[fechaest];

                }

            }  

            if($cSql[noconformidad]<>''){    

                $noconformidad = '<a href=javascript:winuni("noconformidad.php?busca='.$busca.'&Estudio='.$cSql[estudio].'")><i class="fa fa-times-circle fa-lg" style="color:#CD5C5C;" aria-hidden="true"></i></a>';
            }else{
                $noconformidad = '<a href=javascript:winuni("noconformidad.php?busca='.$busca.'&Estudio='.$cSql[estudio].'")><i class="fa fa-square fa-lg" style="color:GREEN;" aria-hidden="true"></i></a>';
            }  

        ?>

        <tr bgcolor='<?=$Fdo?>' onMouseOver=this.style.backgroundColor='<?=$Gbarra?>';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?=$Fdo?>';>

            <td class="letrap" align='center'>
                <a href=javascript:winmin('impeti.php?op=1&busca=<?=$busca?>&Est=<?=$cSql[estudio]?>')><i class='fa fa-print fa-2x' aria-hidden='true' style='color:#16A085'></i></a>
            </td>
            <td class="letrap" align='center'>
                <?php
                    if($cSql[obsest]<>''){ 
                ?>
                    <a href=javascript:wineti('comentariocap.php?busca=<?=$busca?>&Est=<?=$cSql[estudio]?>')><i class="fa fa-commenting fa-2x" aria-hidden="true"  style="color:#2471A3"></i></a>
                <?php
                    }else{
                ?>
                    <a href=javascript:wineti('comentariocap.php?busca=<?=$busca?>&Est=<?=$cSql[estudio]?>')><i class="fa fa-commenting fa-2x" aria-hidden="true"  style="color:#52BE80"></i></a>

                <?php
                    }
                ?>
            </td>
            <td class="content2" colspan="2" style="height: 25px;">
                &nbsp; &nbsp; <strong>
                <?= $cSql[estudio] ?> - 
                <?= $cSql[descripcion] ?>
                </strong>
            </td>

            <td class="letrap" align='center'>
                <?= $Interpreta ?> 
            </td>

            <td class="letrap" align='center'>
                <font size="1"><?= $Toma ?> <br>
                <?= $FechaToma ?></font>
            </td>
            <td class="letrap" align='center'>
                <font size="1"><?= $Interna ?> <br>
                <?= $FechaInterna ?></font>
            </td>
            <td class="letrap" align='center'>
                <font size="1"><?= $Externa ?> <br>
                <?= $FechaExterna ?></font>
            </td>            
            <td class="letrap" align='center'>
                <font size="1"><?= $Remitida ?> <br>
                <?= $FechaRemitida ?></font>
            </td>
            <td class="letrap" align='center'>
                <font size="1"><?= $Pendiente ?> <br>
                <?= $FechaPendiente ?></font>
            </td>
            <td class="letrap" align='center'>
                <?= $noconformidad ?>
            </td>
        </tr>
        <?php
        $nRng++;
        }
        ?>
    </table>

    <table width='95%' align='center' border='0' cellpadding='1' cellspacing='2' style='border-collapse: collapse; border: 0px solid #999;'>  
        <tr>
            <td class='letratitulo'align="left" colspan="7">
                <a href=javascript:winmin('impeti2.php?op=3&busca=<?=$busca?>')><i class='fa fa-print fa-2x' aria-hidden='true' style='color:#1C2833'></i></a>
            </td>
        </tr>   
    </table> 


<table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    

    <tr>
        <td valign='top' align='center' width='55%'>
            <table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <tr style="background-color: #2c8e3c">
                    <td class='letratitulo'align="center" colspan="2">
                        ..:: Observaciones ::..
                    </td>
                </tr>
                <tr style="height: 30px">

                    <td class="Inpt">
                        <b>Observación :</b>
                        <br>
                        <div align="center"><?= $Cpo[observaciones] ?></div>
                        <br>
                    </td>
                </tr>
                <tr style="height: 30px">
                    <td class="Inpt">
                        <b>Agregar Observaciones :</b>
                        <br>
                        <div align="center"><TEXTAREA name='Observaciones' cols='60' rows='4' class="letrap"></TEXTAREA></div>
                    </td>
                </tr>
                <tr style="height: 20px;" align="center">
                    <td colspan="2">
                        <input class="letrap" type="submit" name="Boton" value="Guardar"></input>
                        <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                    </td>
                </tr>
                </form>
            </table> 
        </td>
        <td valign='top' align='center' width='45%'>
                <?php
                TablaDeLogs("/Toma de muestras/", $busca);
                ?>
        </td>
    </tr>
</table> 

    <br>
    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2'> 
        <tr align="letf"> 
            <td valign='top' width="22%">
                <a class='elim' href='javascript:window.close()'><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
            </td>
        </tr>
    </table> 
        
</body>

<script type="text/javascript">
    $(document).ready(function () {
        var busca = "<?= $busca ?>";

        $("#Calcular").click(function () {
            const st = new Date();
            var a = st.getFullYear();
            var c = st.getMonth();
            var b = $("#Edad").val();
            if (c < 10 && c !== 0) {
                c = 0 + "" + c;
            } else if (c == 0) {
                c = 0 + "" + 1;
            }
            var year = a - b;
            var fechan = year + "-" + c + "-01";

            $("#Fechan").val(fechan);

        });
    });
</script>

</html>

<?php
mysql_close();
?>