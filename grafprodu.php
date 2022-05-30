<?php

  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");

  $link=conectarse();

  $capt=$_REQUEST[usuario];

  $FechaI   =   $_REQUEST[FecI];

  $FechaF   =   $_REQUEST[FecF];  

  $filtro3 = $_REQUEST[Sucursal];

  $filtro7 = $_REQUEST[Depto];

  $filtro5 = $_REQUEST[Institucion];

if($filtro3<>'*'){
    $filtro4="and ot.suc='$filtro3'";
 }else{
    $filtro4=" ";
 }

 if($filtro5<>'*'){
  $filtro6="and ot.institucion='$filtro5'";
 }else{
  $filtro6=" ";
 }

if($filtro7<>'*'){
    $filtro8="and est.depto='$filtro7'";
 }else{
    $filtro8=" ";
 }

if($capt=='SIN_REGISTRO'){
    $capt='';
}else{
    $capt=$capt;
}


 

 //************  D A T O S   D E   G R A F I C A  *************//

    $FecT=$FechaI;
    while($FecT<=$FechaF){
        
        
        $AtencionA1="SELECT COUNT(otd.usrest) as AtencionT, ot.fecha as fechaot from ot
         INNER JOIN otd on otd.orden=ot.orden 
         WHERE otd.usrest='$capt' AND ot.fecha= '$FecT' GROUP BY ot.fecha ORDER BY ot.fecha;";
        
        $QSql = mysql_query($AtencionA1);
        $re1 = mysql_fetch_array($QSql);
        
    
        $ProcesoA1="SELECT COUNT(otd.proceso) as ProcesoT, ot.fecha as fechaot from ot
         INNER JOIN otd on otd.orden=ot.orden 
         WHERE otd.proceso='$capt' AND ot.fecha= '$FecT' GROUP BY ot.fecha ORDER BY ot.fecha;";
        $QSql1 = mysql_query($ProcesoA1);
        $re2 = mysql_fetch_array($QSql1);
        
        
        $CapturaA1="SELECT COUNT(otd.capturo) as CapturoT, ot.fecha as fechaot from ot
         INNER JOIN otd on otd.orden=ot.orden 
         WHERE otd.capturo='$capt' AND ot.fecha= '$FecT' GROUP BY ot.fecha ORDER BY ot.fecha;";
        $QSql2 = mysql_query($CapturaA1);
        $re3 = mysql_fetch_array($QSql2);
        
        


        $AtencionT=$re1[AtencionT];
        $ProcesoT=$re2[ProcesoT];
        $CapturoT=$re3[CapturoT];
        
        if(!isset($AtencionT)){
            $AtencionT=0;
        }else{
            $AtencionT=$AtencionT;
        }
        
        
        if(!isset($ProcesoT)){
            $ProcesoT=0;
        }else{
            $ProcesoT=$ProcesoT;
        }
        
        
        if(!isset($CapturoT)){
            $CapturoT=0;
        }else{
            $CapturoT=$CapturoT;
        }
        
        
        
            $sig .= "" .$AtencionT. ",";
            $sig1.= "" .$ProcesoT. ",";
            $sig2.= "" .$CapturoT. ",";
            $ds .= "'" . $FecT . "',";
            
            $FecT = date('Y-m-d', strtotime("$FecT + 1 day"));
        }




require ("config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Grafica ::..</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js"></script>
        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
        <script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>    
        <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
    <?php




         ?>

        <h2 style="color: #5c6773" align="center"> <i style="color:#58D68D" class="fa fa-bar-chart fa-1x" aria-hidden="true"></i> Grafica Productividad General <?= $FechaI ?> al dia <?= $FechaF ?></h2>
        <table width="100%" style="border-radius: 2px 2px 2px 2px;"><tr bgcolor="#CCD1D1"><td>
        <canvas id="myChart" width="1000" height="200"></canvas></td></tr></table>
        <br><br>

        <?php
          
          echo "<table align='center' width='70%' border='0' cellspacing='1' cellpadding='0'>";
          echo "<tr>";
          echo "<td align='CENTER' bgcolor='#808B96'>Dia</td>";
          echo "<td align='CENTER' bgcolor='#808B96'>Atencion</td>";
          echo "<td align='CENTER' bgcolor='#808B96'>Proceso</td>";
          echo "<td align='CENTER' bgcolor='#808B96'>Captura</td></tr>";

          $nRng =0;
          $sumA=0;
          $sumP=0;
          $sumC=0;

        $FecT1=$FechaI;
        while($FecT1<=$FechaF){
          
            $AtencionA2="SELECT COUNT(otd.usrest) as AtencionT1, ot.fecha as fechaot from ot
             INNER JOIN otd on otd.orden=ot.orden 
             WHERE otd.usrest='$capt' AND ot.fecha= '$FecT1' GROUP BY ot.fecha ORDER BY ot.fecha;";
            
            $QSqla = mysql_query($AtencionA2);
            $rea = mysql_fetch_array($QSqla);
            
            
            $ProcesoA2="SELECT COUNT(otd.proceso) as ProcesoT1, ot.fecha as fechaot from ot
            INNER JOIN otd on otd.orden=ot.orden 
            WHERE otd.proceso='$capt' AND ot.fecha= '$FecT1' GROUP BY ot.fecha ORDER BY ot.fecha;";
            $QSqlp = mysql_query($ProcesoA2);
            $rep = mysql_fetch_array($QSqlp);
            
            
            $CapturaA2="SELECT COUNT(otd.capturo) as CapturoT1, ot.fecha as fechaot from ot
            INNER JOIN otd on otd.orden=ot.orden 
            WHERE otd.capturo='$capt' AND ot.fecha= '$FecT1' GROUP BY ot.fecha ORDER BY ot.fecha;";
            $QSqlc = mysql_query($CapturaA2);
            $rec = mysql_fetch_array($QSqlc);

            $AtencionT1=$rea[AtencionT1];
            $ProcesoT1=$rep[ProcesoT1];
            $CapturoT1=$rec[CapturoT1];
            
            if(!isset($AtencionT1)){
                $AtencionT1=0;
            }else{
                $AtencionT1=$AtencionT1;
            }
            
            
            if(!isset($ProcesoT1)){
                $ProcesoT1=0;
            }else{
                $ProcesoT1=$ProcesoT1;
            }
            
            
            if(!isset($CapturoT1)){
                $CapturoT1=0;
            }else{
                $CapturoT1=$CapturoT1;
            }
            
          
          
            if (($nRng % 2) > 0) {
              $Fdo = '#FFFFFF';
            } else {
              $Fdo = '#D5D8DC';
            }   
          
          
          echo "<tr bgcolor='$Fdo'>";
          echo "<td align='center'>$FecT1</td>";
          echo "<td align='center'>$AtencionT1</td>";
          echo "<td align='center'>$ProcesoT1</td>";
          echo "<td align='center'>$CapturoT1</td></tr>";

          $nRng ++;   
          
          $FecT1 = date('Y-m-d', strtotime("$FecT1 + 1 day"));

          $sumA=$sumA+$AtencionT1;
          $sumP=$sumP+$ProcesoT1;
          $sumC=$sumC+$CapturoT1;

        }

         echo "<tr>";
         echo "<td align='CENTER' bgcolor='#808B96'>Total</td>";
         echo "<td align='center' bgcolor='#AFF4F1'>$sumA</td>";
         echo "<td align='CENTER' bgcolor='#AFF4F1'>$sumP</td>";
         echo "<td align='CENTER' bgcolor='#AFF4F1'>$sumC</td></tr>";
         echo "</table>";
        
        ?><br><br>

    <script src="./controladores.js"></script>
    <script>
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'line',

            data: {
                datasets: [{
                        label: 'Atencion',
                        data: [<?= $sig?>],

                        backgroundColor: ['rgba(75, 192, 192, 0.4)',],
                        borderColor: ['rgba(75, 192, 192, 1)',],
                        borderWidth: 4
                    },
                    
                    {

                        label: 'Proceso',
                        data: [<?= $sig1?>],

                        backgroundColor: ['rgba(153, 102, 255, 0.4)',],
                        borderColor: ['rgba(153, 102, 255, 1)',],
                        borderWidth: 4
                    },
                    
                    {

                        label: 'Captura',
                        data: [<?= $sig2?>],

                        backgroundColor: ['rgba(255, 159, 64, 0.4)'],
                        borderColor: ['rgba(255, 159, 64, 1)'],
                        borderWidth: 4
                    }],
                    labels: [<?= $ds?>]

            },
               options: {
                scales: {
                    Y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
mysql_close();
?>