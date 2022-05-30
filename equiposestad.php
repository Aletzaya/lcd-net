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
$busca = $_REQUEST["busca"];
//$msj = $_REQUEST["Msj"];
$Fecha = date("Y-m-d");
$date = date("Y-m-d H:i:s");
$Msj = $_REQUEST["Msj"];

$DatA = mysql_query("SELECT * FROM estadisticaequipo WHERE equipo= '$busca'");
$Dat = mysql_fetch_array($DatA);

$Boton=$_REQUEST["bt"];

if (!isset($Boton)){
  $Boton = "Inicio";
}

if ($Boton == 'Procesar_Estadistica' or $Boton == 'Guardar_Estadistica') {

    $FechaI   =   $_REQUEST[FechaI];

    if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
    }

    $FechaF   =   $_REQUEST[FechaF];

    if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
    }

    if ($FechaI>$FechaF){
      echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
    }

}elseif ($Boton == 'Inicio'){

    $FechaI   =   $Dat[fechaini];

    $FechaF   =   $Dat[fechafin];

}

$BgColor = " onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='DDE8FF'";

$BgColor1 = " onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='FFFFFF'";

if ($Boton == 'Procesar_Estadistica' or $Boton == 'Inicio') {
 
    $EqpA = "SELECT *
       FROM regeq
       WHERE id_eq='$busca' AND fechaeven >= '$FechaI' and fechaeven <= '$FechaF' order by id desc";

    $Eqp = mysql_query($EqpA);
    $Eqp2 = mysql_query($EqpA);

}elseif($Boton == 'Guardar_Estadistica') {

    $BorraEst = mysql_query("DELETE FROM estadisticaequipo WHERE equipo='$busca'");

    $lUp1   = mysql_query("INSERT INTO estadisticaequipo (equipo,fechaini,fechafin) 
          VALUES 
          ('$busca','$FechaI','$FechaF')");

    $Msj = "Estadistica guardada con exito";

    AgregaBitacoraEventos($Gusr, '/Equipos/Estadistica/Estadistica Guardada ', "estadisticaequipo", $date, $busca, $Msj, "equiposestad.php");

}

$CpoA = mysql_query("SELECT * FROM eqp WHERE (id= '$busca')");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Equipos - Estadistica</title>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js"></script>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
 //       encabezados();
 //       menu($Gmenu, $Gusr);
        ?>
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Estadistica ( <?= $busca ?> ) <?= $Cpo[nombre] ?> ::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='50' width='43%'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">..:: Estadistica de Equipo ::..</td>
                            </tr>
                            <tr style="height: 30px" class="ssbm">
                                <td align='center' width='50%' class="letrap">Fecha Inicial:
                                    <input type='date' class='letrap'  name='FechaI' <?= $bloqueado ?> value ='<?= $FechaI ?>'/>
                                </td>
                                <td align='center' width='50%' class="letrap">Fecha Final:
                                    <input type='date' class='letrap'  name='FechaF' <?= $bloqueado ?> value ='<?= $FechaF ?>'/>
                                </td>
                            </tr>

                            <tr style="height: 30px" class="ssbm">
                                <td align='center' width='50%' class="letrap" colspan="2">
                                    Ultima Estadistica Generada:
                                </td> 
                            </tr> 
                            <tr style="height: 30px" class="ssbm">
                                <td align='center' width='50%' class="letrap">
                                    Fecha Ini: <?= $Dat[fechaini] ?>
                                </td> 
                                <td align='center' width='50%' class="letrap">
                                    Fecha Fin: <?= $Dat[fechafin] ?>
                                </td>    
                            </tr>  
                            <tr style="height: 30px" class="ssbm">

                                <?php
                                if ($_REQUEST["bt"] == 'Procesar_Estadistica') {
                                ?>

                                    <td align='center' width='50%' class="letrap">
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>         
                                        <input class="letrap" type="submit" value='Procesar_Estadistica' name='bt' <?= $bloqueado ?>></input>     
                                    </td>  
                                    <td align='center' width='50%' class="letrap">
                                        <input class="letrap" type="submit" value='Guardar_Estadistica' name='bt' <?= $bloqueado ?>></input>                           
                                    </td>

                                <?php
                                }else{
                                ?>
                                    <td align='center' width='50%' class="letrap" colspan="2">
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>         
                                        <input class="letrap" type="submit" value='Procesar_Estadistica' name='bt' <?= $bloqueado ?>></input>     
                                    </td>  

                                <?php
                                }
                                ?>
                            </tr>             
                        </table> 
                    </td>
                    <td valign='top' width='45%'>
                        <?php
                        TablaDeLogs("/Equipos/Estadistica/", $busca);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class='letratitulo' align="center" valign='top'>
                        <table width='99%' align='center' border='0' cellpadding='5' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo' align="center" colspan="3">..:: Datos de Estadistica ::..</td>
                                </tr>

                                <tr class="letrap">
                                <td align="center" <?= $BgColor ?>><b>
                                Status
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                Dias
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                %
                                </td></b>
                                </tr>  

                                <?php
                                $nRng=$Op=$Ob=$Fs=$Al=$Sr=0;
                                while ($Eq = mysql_fetch_array($Eqp)) {
               
                                    if($nRng>=1){
                                        $date1 = new DateTime($fechaant);
                                        $date2 = new DateTime($Eq[fechaeven]);
                                        $diff = $date1->diff($date2);
                                    }else{
                                        $date1 = new DateTime($FechaF);
                                        $date2 = new DateTime($Eq[fechaeven]);
                                        $diff = $date1->diff($date2);
                                    }

                                    switch($Eq[status]) {
                                    case Optimo:
                                        $Op2=$diff->days;
                                        $Op=$Op+$Op2;
                                        break;
                                    case Observacion:
                                        $Ob2=$diff->days;
                                        $Ob=$Ob+$Ob2;
                                        break;
                                    case Fuera_de_Servicio:
                                        $Fs2=$diff->days;
                                        $Fs=$Fs+$Fs2;
                                        break;
                                    case Almacenado:
                                        $Al2=$diff->days;
                                        $Al=$Al+$Al2;
                                        break;
                                    case Baja:
                                        $Bj2=$diff->days;
                                        $Bj=$Bj+$Bj2;
                                        break;
                                    }

                                    $fechaant=$Eq[fechaeven];

                                    $nRng++;
                                }

                                $EqpB = "SELECT *
                                   FROM regeq
                                   WHERE id_eq='$busca' AND fechaeven < '$FechaI' order by id desc limit 1;";

                                $Eqp3 = mysql_query($EqpB);

                                $Eq3 = mysql_fetch_array($Eqp3);

                                $date1b = new DateTime($fechaant);
                                $date2b = new DateTime($FechaI);
                                $diff = $date1b->diff($date2b);

                                if($Eq3[status]<>''){

                                    switch($Eq3[status]) {
                                    case Optimo:
                                        $Op3=$diff->days;
                                        $Op=$Op+$Op3;
                                        break;
                                    case Observacion:
                                        $Ob3=$diff->days;
                                        $Ob=$Ob+$Ob3;
                                        break;
                                    case Fuera_de_Servicio:
                                        $Fs3=$diff->days;
                                        $Fs=$Fs+$Fs3;
                                        break;
                                    case Almacenado:
                                        $Al3=$diff->days;
                                        $Al=$Al+$Al3;
                                        break;
                                    case Baja:
                                        $Bj3=$diff->days;
                                        $Bj=$Bj+$Bj3;
                                        break;
                                    }

                                }else{

                                    $Sr3=$diff->days;
                                    $Sr=$Sr3;

                                }

                                    $datef = new DateTime($FechaF);
                                    $datei = new DateTime($FechaI);
                                    $diff = $datef->diff($datei);
                                    $DiasTot=$diff->days;
                                    //$DiasTot++;
                                    $Opp=($Op*100)/$DiasTot;
                                    $Obp=($Ob*100)/$DiasTot;
                                    $Fsp=($Fs*100)/$DiasTot;
                                    $Alp=($Al*100)/$DiasTot;
                                    $Bjp=($Bj*100)/$DiasTot;
                                    $Srp=($Sr*100)/$DiasTot;
                                    $Totp=$Opp+$Obp+$Fsp+$Alp+$Bjp+$Srp;
                                    $Extr=$Op3+$Ob3+$Fs3+$Al3+$Bj3+$Sr3;
                                    $Total=$Op+$Ob+$Fs+$Al+$Bj+$Sr;
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

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#85929E;' aria-hidden='true'></i> Almacen
                                </td>
                                <td align="center">
                                <?= $Al ?>
                                <?php $Alp=number_format($Alp,2); ?>
                                </td>
                                <td align="center">
                                <?= $Alp ?>
                                </td>
                                </tr>

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                 <i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i> S/R
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
                                <?= $DiasTot ?>
                                </td></b>
                                <td align="center" <?= $BgColor ?>><b>
                                <?= number_format($Totp,2) ?>
                                </td></b>
                                </tr>  
<!--
                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="left">
                                <?= $Eq3[id] ?>
                                </td>
                                <td align="center">
                                <?= $Eq3[fechaeven] ?>
                                </td>
                                <td align="center">
                                <?= $Eq3[status] ?>
                                </td>
                                </tr>
-->
                            </tr>  
                        </table>  
                        <br>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr>
                                <!-- <td class='letrap' align="center" width="8"><b>Rng</b></td> -->
                                <td class='letrap' align="center" width="8"><b>Id</b></td>
                                <td class='letrap' align="center" width="18"><b>Fecha</b></td>
                                <td class='letrap' align="center" width="56"><b>Observacion</b></td>
                                <td class='letrap' align="center" width="10"><b>Usr</b></td>
                                <td class='letrap' align="center" width="8"><b>Sts</b></td>
                                <td class='letrap' align="center" width="8"><b>Dias</b></td>
                            </tr>
                            <?php
                            $nRng2=0;
                            while ($Eq2 = mysql_fetch_array($Eqp2)) {
                                (($nRng2 % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = 'DDE8FF';

                                switch($Eq2[status]) {
                                case Optimo:
                                    $Sts="<i class='fa fa-square fa-lg' style='color:#239B56;' aria-hidden='true'></i>";
                                    break;
                                case Observacion:
                                    $Sts="<i class='fa fa-square fa-lg' style='color:#F39C12;' aria-hidden='true'></i>";
                                    break;
                                case Fuera_de_Servicio:
                                    $Sts="<i class='fa fa-square fa-lg' style='color:#E74C3C;' aria-hidden='true'></i>";
                                    break;
                                case Almacenado:
                                    $Sts="<i class='fa fa-square fa-lg' style='color:#85929E;' aria-hidden='true'></i>";
                                    break;
                                case Baja:
                                    $Sts="<i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i>";
                                    break;
                                }

                                if($nRng2>=1){
                                    $date1 = new DateTime($fechaant);
                                    $date2 = new DateTime($Eq2[fechaeven]);
                                    $diff = $date1->diff($date2);
                                }else{
                                    $date1 = new DateTime($FechaF);
                                    $date2 = new DateTime($Eq2[fechaeven]);
                                    $diff = $date1->diff($date2);
                                }
                                $fechaant=$Eq2[fechaeven];
                            ?>
                                <tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                                    <!--
                                <td align="center">
                                    <?=
                                    $nRng2;
                                    ?>
                                </td>  
                            -->
                                <td align="center">
                                    <?=
                                    $Eq2[id];
                                    ?>
                                </td>   
                                <td align="center">
                                    <?=
                                    $Eq2[fechaeven];
                                    ?>
                                </td>    
                                <td align="left">
                                    <?=
                                    $Eq2[observaciones];
                                    ?>
                                </td>  
                                <td align="center">
                                    <?=
                                    $Eq2[usr];
                                    ?>
                                </td>    
                                <td align="center">
                                    <?=
                                    $Sts;
                                    ?>
                                </td>
                                <td align="center">
                                    <?=
                                    $diff->days;
                                    ?>
                                </td>  
                                </tr>
                                <?php
                                    $nRng2++;
                                }
                                ?>
                                
                                <?php
                                switch($Eq3[status]) {
                                case Optimo:
                                    $Sts2="<i class='fa fa-square fa-lg' style='color:#239B56;' aria-hidden='true'></i>";
                                    break;
                                case Observacion:
                                    $Sts2="<i class='fa fa-square fa-lg' style='color:#F39C12;' aria-hidden='true'></i>";
                                    break;
                                case Fuera_de_Servicio:
                                    $Sts2="<i class='fa fa-square fa-lg' style='color:#E74C3C;' aria-hidden='true'></i>";
                                    break;
                                case Almacenado:
                                    $Sts2="<i class='fa fa-square fa-lg' style='color:#85929E;' aria-hidden='true'></i>";
                                    break;
                                case Baja:
                                    $Sts2="<i class='fa fa-square fa-lg' style='color:#000000;' aria-hidden='true'></i>";
                                    break;
                                }
                                ?>

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="center">
                                </td>
                                <td align="center">
                                <?= $Eq3[fechaeven] ?>
                                </td>
                                <td align="center">
                                </td>
                                <td align="center">
                                </td>
                                <td align="center">
                                <?= $Sts2 ?>
                                </td>
                                <td align="center">
                                <?= $Extr ?>
                                </td>
                                </tr>

                                <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                <td align="right" colspan="3">
                                    <b>Eventos -->   <?= $nRng2 ?></b>
                                </td>
                                <td align="right" colspan="2">
                                    <b>Total Dias --></b>
                                </td>
                                <td align="center">
                                <b><?= $Total ?></b>
                                </td>
                                </tr>
                        </table> 
                    </td>
                    <td class='letratitulo' align="center" valign='top'>
                        <table border='0' width='90%' align='center' cellpadding='1' cellspacing='4'>    
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
                                                    labels: ['Optimo <?= $Opp ?>  %  -  <?= $Op ?> Dias','Observacion <?= $Obp ?> %  -  <?= $Ob ?> Dias','Fuera_Serv <?= $Fsp ?>  %  -  <?= $Fs ?> Dias','Almacen <?= $Alp ?>  %  -  <?= $Al ?> Dias','S/R <?= $Srp ?>  %  -  <?= $Sr ?> Dias'],
                                                    datasets: [{
                                                            label: 'Estadistica General',
                                                            data: [<?= $Opp ?>,<?= $Obp ?>,<?= $Fsp ?>,<?= $Alp ?>,<?= $Srp ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(243, 156, 18, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)',
                                                                'rgba(133, 146, 158, 0.8)',
                                                                'rgba(23, 32, 42, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(243, 156, 18, 1)',
                                                                'rgba(236, 46, 46, 1)',
                                                                'rgba(133, 146, 158, 1)',
                                                                'rgba(23, 32, 42, 1)'                                    
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
    </form>
    </body>
</html>
<?php
mysql_close();
?>