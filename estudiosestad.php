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
$Titulo = "Ordenes de estudio";

#Variables comunes;
$Titulo = "Detalles de estudios";
$busca = $_REQUEST["busca"];
//$msj = $_REQUEST["Msj"];
$Fecha = date("Y-m-d");
$date = date("Y-m-d H:i:s");
$Msj = $_REQUEST["Msj"];

$DatA = mysql_query("SELECT * FROM estadisticalogico WHERE estudio= '$busca'");
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
    
    $sql1 = "SELECT count(*) as contador, tipo, id, vtexto FROM elepdf WHERE estudio='$busca' and estadistica='Si';";
    $cSql1 =mysql_query($sql1);
    $sql1 = mysql_fetch_array($cSql1);
    $elemento=$sql1[id];
    $vcaracter=strtolower($sql1[vtexto]);

    if($sql1[contador]==1 and $sql1[tipo]=='l'){

        $Lcdselec1 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.l, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.l<>'' and otd.alterno=0 and resul.elemento='$elemento' order by ot.suc,ot.orden";

        $Lcd1 = mysql_query($Lcdselec1);
        $Lcd1a = mysql_query($Lcdselec1);

        $Tipo='Logico';

    }elseif($sql1[contador]==1 and $sql1[tipo]=='c'){

        $Lcdselec1 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.c, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.c<>'' and otd.alterno=0 and resul.elemento='$elemento' order by ot.suc,ot.orden";

        $Lcd1 = mysql_query($Lcdselec1);
        $Lcd1a = mysql_query($Lcdselec1);

        $Tipo='Caracter';

    }

//********** Alterno1 **************//
    
    $sql2 = "SELECT count(*) as contador, tipo, id, vtexto FROM elealtpdf WHERE estudio='$busca' and estadistica='Si';";
    $cSql2 = mysql_query($sql2);
    $sql2 = mysql_fetch_array($cSql2);
    $elemento2=$sql2[id];
    $vcaracter2=strtolower($sql2[vtexto]);

    if($sql2[contador]==1 and $sql2[tipo]=='l'){

        $Lcdselec2 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.l, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.l<>'' and otd.alterno=1 and resul.elemento='$elemento2' order by ot.suc,ot.orden";

        $Lcd2 = mysql_query($Lcdselec2);
        $Lcd2a = mysql_query($Lcdselec2);

        $Tipo2='Logico';

    }elseif($sql2[contador]==1 and $sql2[tipo]=='c'){

        $Lcdselec2 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.c, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.c<>'' and otd.alterno=1 and resul.elemento='$elemento2' order by ot.suc,ot.orden";

        $Lcd2 = mysql_query($Lcdselec2);
        $Lcd2a = mysql_query($Lcdselec2);

        $Tipo2='Caracter';

    }

//********** Alterno2 **************//
    
    $sql3 = "SELECT count(*) as contador, tipo, id, vtexto FROM elealtpdf2 WHERE estudio='$busca' and estadistica='Si';";
    $cSql3 = mysql_query($sql3);
    $sql3 = mysql_fetch_array($cSql3);
    $elemento3=$sql3[id];
    $vcaracter3=strtolower($sql3[vtexto]);

    if($sql3[contador]==1 and $sql3[tipo]=='l'){

        $Lcdselec3 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.l, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.l<>'' and otd.alterno=2 and resul.elemento='$elemento3' order by ot.suc,ot.orden";

        $Lcd3 = mysql_query($Lcdselec3);
        $Lcd3a = mysql_query($Lcdselec3);

        $Tipo3='Logico';

    }elseif($sql3[contador]==1 and $sql3[tipo]=='c'){

        $Lcdselec3 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.c, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.c<>'' and otd.alterno=2 and resul.elemento='$elemento3' order by ot.suc,ot.orden";

        $Lcd3 = mysql_query($Lcdselec3);
        $Lcd3a = mysql_query($Lcdselec3);

        $Tipo3='Caracter';

    }

//********** Alterno3 **************//
    
    $sql4 = "SELECT count(*) as contador, tipo, id, vtexto FROM elealtpdf3 WHERE estudio='$busca' and estadistica='Si';";
    $cSql4 = mysql_query($sql4);
    $sql4 = mysql_fetch_array($cSql4);
    $elemento4=$sql4[id];
    $vcaracter4=strtolower($sql4[vtexto]);

    if($sql4[contador]==1 and $sql4[tipo]=='l'){

        $Lcdselec4 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.l, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.l<>'' and otd.alterno=3 and resul.elemento='$elemento4' order by ot.suc,ot.orden";

        $Lcd4 = mysql_query($Lcdselec4);
        $Lcd4a = mysql_query($Lcdselec4);

        $Tipo4='Logico';

    }elseif($sql4[contador]==1 and $sql4[tipo]=='c'){

        $Lcdselec4 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.c, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.c<>'' and otd.alterno=3 and resul.elemento='$elemento4' order by ot.suc,ot.orden";

        $Lcd4 = mysql_query($Lcdselec4);
        $Lcd4a = mysql_query($Lcdselec4);

        $Tipo4='Caracter';

    }

//********** Alterno4 **************//
    
    $sql5 = "SELECT count(*) as contador, tipo, id, vtexto FROM elealtpdf4 WHERE estudio='$busca' and estadistica='Si';";
    $cSql5 = mysql_query($sql5);
    $sql5 = mysql_fetch_array($cSql5);
    $elemento5=$sql5[id];
    $vcaracter5=strtolower($sql5[vtexto]);

    if($sql5[contador]==1 and $sql5[tipo]=='l'){

        $Lcdselec5 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.l, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.l<>'' and otd.alterno=4 and resul.elemento='$elemento5' order by ot.suc,ot.orden";

        $Lcd5 = mysql_query($Lcdselec5);
        $Lcd5a = mysql_query($Lcdselec5);

        $Tipo5='Logico';

    }elseif($sql5[contador]==1 and $sql5[tipo]=='c'){

        $Lcdselec5 = "SELECT ot.orden, ot.fecha, cli.cliente, cli.sexo, cli.fechan, resul.estudio, resul.c, ot.suc, otd.alterno
           FROM ot, cli, resul, otd
           WHERE ot.cliente = cli.cliente AND ot.orden = resul.orden AND ot.orden = otd.orden and otd.estudio='$busca' AND ot.fecha>='$FechaI' and ot.fecha <='$FechaF' and resul.estudio='$busca' and resul.c<>'' and otd.alterno=4 and resul.elemento='$elemento5' order by ot.suc,ot.orden";

        $Lcd5 = mysql_query($Lcdselec5);
        $Lcd5a = mysql_query($Lcdselec5);

        $Tipo5='Caracter';

    }

    if ($Boton == 'Procesar_Estadistica'){

        $Msj = "Estadistica generada con exito";

        AgregaBitacoraEventos2($Gusr, '/Estudios/Estadistica/Estadistica Generada ', "estadisticalogico", $date, $busca, $Msj, "estudiosestad.php");

    }

}elseif($Boton == 'Guardar_Estadistica') {

    $BorraEst = mysql_query("DELETE FROM estadisticalogico WHERE estudio='$busca'");

    $lUp1   = mysql_query("INSERT INTO estadisticalogico (estudio,fechaini,fechafin) 
          VALUES 
          ('$busca','$FechaI','$FechaF')");

    $Msj = "Estadistica guardada con exito";

    AgregaBitacoraEventos($Gusr, '/Estudios/Estadistica/Estadistica Guardada ', "estadisticalogico", $date, $busca, $Msj, "estudiosestad.php");

}

$CpoA = mysql_query("SELECT * FROM est WHERE (estudio= '$busca')");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Estudios - Estadistica</title>
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
                        ..:: Estadistica ( <?= $busca ?> ) <?= $Cpo[descripcion] ?>::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='100' width='43%'>
                        <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">..:: Estadistica de Estudio ::..</td>
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
                        <?php
//***** Datos Estadisticos *****// 
                        if ($Boton == 'Procesar_Estadistica' or $Boton == 'Inicio') {

                                while ($Lcd = mysql_fetch_array($Lcd1)) {
                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años
                                        
                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo=='Logico'){

                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo=='Caracter'){

                                            if(strtolower($Lcd[c])<>$vcaracter){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                    $nRng++;
                                }

//*****************  alterno 1 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd2)) {

                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo2=='Logico'){

                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo2=='Caracter'){

                                            if(strtolower($Lcd[c])<>$vcaracter2){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter2){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                    $nRng++;
                                }

//*****************  alterno 2 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd3)) {

                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo3=='Logico'){

                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo3=='Caracter'){

                                            if(strtolower($Lcd[c])<>$vcaracter3){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter3){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                    $nRng++;
                                }

//*****************  alterno 3 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd4)) {

                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo4=='Logico'){

                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo4=='Caracter'){

                                            if(strtolower($Lcd[c])<>$vcaracter4){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter4){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                    $nRng++;
                                }

//*****************  alterno 4 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd5)) {

                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo5=='Logico'){

                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo5=='Caracter'){

                                            if(strtolower($Lcd[c])<>$vcaracter5){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter5){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                    $nRng++;
                                }

                        }
                        ?>

                    </td>
                    <td valign='top' width='45%'>
                        <?php
                        TablaDeLogs("/Estudios/Estadistica/", $busca);
                        ?>
                    </td>
                    <td valign = 'top' width = "22%" rowspan="2">
                        <?php
                        $sbmn = 'Estadistica';
                        Sbmenu();
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class='letratitulo' align="center" colspan="2">
                        <table width='99%' align='center' border='0' cellpadding='5' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                <tr style="background-color: #2c8e3c">
                                    <td class='letratitulo' align="center" colspan="12">..:: Datos de Estadistica ::..</td>
                                    </tr>

                                    <tr class="letrap">
                                    <td align="center">
                                    
                                    </td>
                                    <td align="center">
                                    
                                    </td>

                                    <td align="center" colspan="5" bgcolor="#52BE80"><b>
                                    Positivos
                                    </td></b>

                                    <td align="center" colspan="5" bgcolor="#EC7063"><b>
                                    Negativos
                                    </td></b>
                                    </tr> 

                                    <tr class="letrap">
                                    <td align="center" <?= $BgColor ?>><b>
                                    Unidad
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Tot_Unidad
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Hombres +
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Mujeres +
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Adultos +
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Niños +
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Total +
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Hombres -
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Mujeres -
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Adultos -
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Niños -
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    Total -
                                    </td></b>
                                    </tr>  

                                    <tr class="letrap" <?= $BgColor ?>>
                                    <td align="left">
                                     * Matriz
                                    </td>
                                    <td align="center">
                                    <?= $conot1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexoms1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofs1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12pa1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11pn1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conls1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexomn1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofn1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12na1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11nn1 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conln1 ?>
                                    </td>
                                    </tr>  


                                    <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                    <td align="left">
                                     * Futura
                                    </td>
                                    <td align="center">
                                    <?= $conot2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexoms2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofs2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12pa2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11pn2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conls2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexomn2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofn2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12na2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11nn2 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conln2 ?>
                                    </td>
                                    </tr>


                                    <tr class="letrap" <?= $BgColor ?>>
                                    <td align="left">
                                     * Tepexpan
                                    </td>
                                    <td align="center">
                                    <?= $conot3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexoms3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofs3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12pa3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11pn3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conls3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexomn3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofn3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12na3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11nn3 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conln3 ?>
                                    </td>
                                    </tr>


                                    <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                    <td align="left">
                                     * Los Reyes
                                    </td>
                                    <td align="center">
                                    <?= $conot4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexoms4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofs4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12pa4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11pn4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conls4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexomn4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofn4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12na4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11nn4 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conln4 ?>
                                    </td>
                                    </tr>

                                    <tr class="letrap" <?= $BgColor ?>>
                                    <td align="left">
                                     * Camarones
                                    </td>
                                    <td align="center">
                                    <?= $conot5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexoms5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofs5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12pa5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11pn5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conls5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexomn5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofn5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12na5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11nn5 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conln5 ?>
                                    </td>
                                    </tr>

                                    <tr class="letrap" bgcolor='#FFFFFF' <?= $BgColor1 ?>>
                                    <td align="left">
                                     * San Vicente
                                    </td>
                                    <td align="center">
                                    <?= $conot6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexoms6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofs6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12pa6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11pn6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conls6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexomn6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $consexofn6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos12na6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $contaranos11nn6 ?>
                                    </td>
                                    <td align="center">
                                    <?= $conln6 ?>
                                    </td>
                                    </tr>


                                    <?php 
                                    $conott = $conot1+$conot2+$conot3+$conot4+$conot5+$conot6;

                                    $consexomst = $consexoms1+$consexoms2+$consexoms3+$consexoms4+$consexoms5+$consexoms6;

                                    $consexofst = $consexofs1+$consexofs2+$consexofs3+$consexofs4+$consexofs5+$consexofs6;

                                    $contaranos12pat = $contaranos12pa1+$contaranos12pa2+$contaranos12pa3+$contaranos12pa4+$contaranos12pa5+$contaranos12pa6;

                                    $contaranos11pnt = $contaranos11pn1+$contaranos11pn2+$contaranos11pn3+$contaranos11pn4+$contaranos11pn5+$contaranos11pn6;

                                    $conlst = $conls1+$conls2+$conls3+$conls4+$conls5+$conls6;

                                    $conlstpor = number_format(($conlst*100)/$conott,2);
                                    $conls1por = number_format(($conls1*100)/$conot1,2);
                                    $conls2por = number_format(($conls2*100)/$conot2,2);
                                    $conls3por = number_format(($conls3*100)/$conot3,2);
                                    $conls4por = number_format(($conls4*100)/$conot4,2);
                                    $conls5por = number_format(($conls5*100)/$conot5,2);
                                    $conls6por = number_format(($conls6*100)/$conot6,2);

                                    $consexomnt = $consexomn1+$consexomn2+$consexomn3+$consexomn4+$consexomn5+$consexomn6;

                                    $consexofnt = $consexofn1+$consexofn2+$consexofn3+$consexofn4+$consexofn5+$consexofn6;

                                    $contaranos12nat = $contaranos12na1+$contaranos12na2+$contaranos12na3+$contaranos12na4+$contaranos12na5+$contaranos12na6;

                                    $contaranos11nnt = $contaranos11nn1+$contaranos11nn2+$contaranos11nn3+$contaranos11nn4+$contaranos11nn5+$contaranos11nn6;

                                    $conlnt = $conln1+$conln2+$conln3+$conln4+$conln5+$conln6;

                                    $conlntpor = number_format(($conlnt*100)/$conott,2);
                                    $conln1por = number_format(($conln1*100)/$conot1,2);
                                    $conln2por = number_format(($conln2*100)/$conot2,2);
                                    $conln3por = number_format(($conln3*100)/$conot3,2);
                                    $conln4por = number_format(($conln4*100)/$conot4,2);
                                    $conln5por = number_format(($conln5*100)/$conot5,2);
                                    $conln6por = number_format(($conln6*100)/$conot6,2);

                                    ?>

                                    <tr class="letrap">
                                    <td align="center" <?= $BgColor ?>><b>
                                    Tot_Gral
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $conott ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $consexomst ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $consexofst ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $contaranos12pat ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $contaranos11pnt ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $conlst ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $consexomnt ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $consexofnt ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $contaranos12nat ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $contaranos11nnt ?>
                                    </td></b>
                                    <td align="center" <?= $BgColor ?>><b>
                                    <?= $conlnt ?>
                                    </td></b>
                                </tr>  

                        </table>  
                    </td>
                </tr>
            </table>

    </form>

<?php //***** Grafico *****// ?>

            <br>
            <table border='0' width='98%' align='center' cellpadding='1' cellspacing='4'>    
                <tr style="background-color: #2c8e3c">
                    <td class='letratitulo' align="center">..:: Gráfico Estadistico ::..</td>
                    <td class='letratitulo' align="center">..:: Datos Estadisticos ::..</td>
                </tr>
                <tr>
                    <td class='letratitulo' valign='top' align="center" width='50%'>
                        <table border='0' width='98%' align='center' cellpadding='1' cellspacing='4'>  
                            <tr>
                                <td class='letratitulo' align="center" colspan="2">
                                    <canvas id="myChart" width="500" height="100"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart');
                                            var myChart = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Positivo <?= $conlstpor ?>  %  -  <?= $conlst ?> Ots','Negativo <?= $conlntpor ?> %  -  <?= $conlnt ?> Ots'],
                                                    datasets: [{
                                                            label: 'Estadistica General',
                                                            data: [<?= $conlstpor ?>,<?= $conlntpor ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(236, 46, 46, 1)'                                    
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
                            <tr>
                                <td class='letratitulo' align="center" width='20%'>
                                        <canvas id="myChart1"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart1');
                                            var myChart1 = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Positivo <?= $conls1por ?>  %  -  <?= $conls1 ?> Ots','Negativo <?= $conln1por ?> %  -  <?= $conln1 ?> Ots'],
                                                    datasets: [{
                                                            label: 'Estadistica Matriz',
                                                            data: [<?= $conls1por ?>,<?= $conln1por ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(236, 46, 46, 1)'                                    
                                                            ],
                                                            borderWidth: 3
                                                        }]
                                                },
                                                options: {
                                                responsive: false,
                                                plugins: {
                                                  legend: {
                                                    position: 'top',
                                                  },
                                                  title: {
                                                    display: true,
                                                    text: 'Estadistica Matriz'
                                                  }
                                                }
                                              }
                                            });
                                        </script>
                                </td>
                                <td class='letratitulo' align="center" width='80%'>
                                        <canvas id="myChart2"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart2');
                                            var myChart2 = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Positivo <?= $conls2por ?>  %  -  <?= $conls2 ?> Ots','Negativo <?= $conln2por ?> %  -  <?= $conln2 ?> Ots'],
                                                    datasets: [{
                                                            label: 'Estadistica Futura',
                                                            data: [<?= $conls2por ?>,<?= $conln2por ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(236, 46, 46, 1)'                                    
                                                            ],
                                                            borderWidth: 3
                                                        }]
                                                },
                                                options: {
                                                responsive: false,
                                                plugins: {
                                                  legend: {
                                                    position: 'top',
                                                  },
                                                  title: {
                                                    display: true,
                                                    text: 'Estadistica Futura'
                                                  }
                                                }
                                              }
                                            });
                                        </script>
                                </td>
                                </tr>
                            <tr>
                                <td class='letratitulo' align="center" width='20%'>
                                        <canvas id="myChart3"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart3');
                                            var myChart3 = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Positivo <?= $conls3por ?>  %  -  <?= $conls3 ?> Ots','Negativo <?= $conln3por ?> %  -  <?= $conln3 ?> Ots'],
                                                    datasets: [{
                                                            label: 'Estadistica Tepexpan',
                                                            data: [<?= $conls3por ?>,<?= $conln3por ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(236, 46, 46, 1)'                                    
                                                            ],
                                                            borderWidth: 3
                                                        }]
                                                },
                                                options: {
                                                responsive: false,
                                                plugins: {
                                                  legend: {
                                                    position: 'top',
                                                  },
                                                  title: {
                                                    display: true,
                                                    text: 'Estadistica Tepexpan'
                                                  }
                                                }
                                              }
                                            });
                                        </script>
                                </td>
                                <td class='letratitulo' align="center" width='80%'>
                                        <canvas id="myChart4"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart4');
                                            var myChart4 = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Positivo <?= $conls4por ?>  %  -  <?= $conls4 ?> Ots','Negativo <?= $conln4por ?> %  -  <?= $conln4 ?> Ots'],
                                                    datasets: [{
                                                            label: 'Estadistica Los Reyes',
                                                            data: [<?= $conls4por ?>,<?= $conln4por ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(236, 46, 46, 1)'                                    
                                                            ],
                                                            borderWidth: 3
                                                        }]
                                                },
                                                options: {
                                                responsive: false,
                                                plugins: {
                                                  legend: {
                                                    position: 'top',
                                                  },
                                                  title: {
                                                    display: true,
                                                    text: 'Estadistica Los Reyes'
                                                  }
                                                }
                                              }
                                            });
                                        </script>
                                </td>
                                </tr>
                            <tr>
                                <td class='letratitulo' align="center" width='20%'>
                                        <canvas id="myChart5"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart5');
                                            var myChart5 = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Positivo <?= $conls5por ?>  %  -  <?= $conls5 ?> Ots','Negativo <?= $conln5por ?> %  -  <?= $conln5 ?> Ots'],
                                                    datasets: [{
                                                            label: 'Estadistica Camarones',
                                                            data: [<?= $conls5por ?>,<?= $conln5por ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(236, 46, 46, 1)'                                    
                                                            ],
                                                            borderWidth: 3
                                                        }]
                                                },
                                                options: {
                                                responsive: false,
                                                plugins: {
                                                  legend: {
                                                    position: 'top',
                                                  },
                                                  title: {
                                                    display: true,
                                                    text: 'Estadistica Camarones'
                                                  }
                                                }
                                              }
                                            });
                                        </script>
                                </td>
                                <td class='letratitulo' align="center" width='80%'>
                                        <canvas id="myChart6"></canvas>
                                        <script>
                                            var ctx = document.getElementById('myChart6');
                                            var myChart6 = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Positivo <?= $conls6por ?>  %  -  <?= $conls6 ?> Ots','Negativo <?= $conln6por ?> %  -  <?= $conln6 ?> Ots'],
                                                    datasets: [{
                                                            label: 'Estadistica San Vicente',
                                                            data: [<?= $conls6por ?>,<?= $conln6por ?>],
                                                            backgroundColor: [
                                                                'rgba(72, 171, 115, 0.8)',
                                                                'rgba(236, 46, 46, 0.8)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(72, 171, 115, 1)',
                                                                'rgba(236, 46, 46, 1)'                                    
                                                            ],
                                                            borderWidth: 3
                                                        }]
                                                },
                                                options: {
                                                responsive: false,
                                                plugins: {
                                                  legend: {
                                                    position: 'top',
                                                  },
                                                  title: {
                                                    display: true,
                                                    text: 'Estadistica San Vicente'
                                                  }
                                                }
                                              }
                                            });
                                        </script>
                                </td>
                                </tr>
                        </table>
                    </td>
                    <td class='letratitulo'align="center" width='50%'>
                    <?php
//***** Datos Estadisticos *****// 
                        if ($Boton == 'Procesar_Estadistica' or $Boton == 'Inicio') {
                        ?>
                            <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                                <tr>
                                    <td class='letrap' align="center"><b>Folio</b></td>
                                    <td class='letrap' align="center"><b>Fecha</b></td>
                                    <td class='letrap' align="center"><b>No. Pac.</b></td>
                                    <td class='letrap' align="center"><b>Sex</b></td>
                                    <td class='letrap' align="center"><b>Fech Nac</b></td>
                                    <td class='letrap' align="center"><b>Estudio</b></td>
                                    <td class='letrap' align="center"><b>Resultado</b></td>
                                    <td class='letrap' align="center"><b>Suc</b></td>
                                </tr>
                                <?php

                                while ($Lcd = mysql_fetch_array($Lcd1a)) {
                                    (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = 'DDE8FF';
                                ?>
                                    <tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                                    <td align="center">
                                        <?=
                                        $Lcd[orden];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[fecha];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[cliente];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[sexo];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[estudio];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo=='Logico'){

                                            echo $Lcd[l];
                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo=='Caracter'){

                                            echo $Lcd[c];

                                            if(strtolower($Lcd[c])<>$vcaracter){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[suc];
                                        ?>
                                    </td>
                                    </tr>
                                <?php
                                    $nRng++;
                                }
                                ?>

                                <?php
//*****************  alterno 1 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd2a)) {
                                    (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = 'DDE8FF';
                                ?>
                                    <tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                                    <td align="center">
                                        <?=
                                        $Lcd[orden];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[fecha];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[cliente];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[sexo];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[estudio];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo2=='Logico'){

                                            echo $Lcd[l];
                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo2=='Caracter'){

                                            echo $Lcd[c];

                                            if(strtolower($Lcd[c])<>$vcaracter2){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter2){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[suc];
                                        ?>
                                    </td>
                                    </tr>
                                <?php
                                    $nRng++;
                                }
                                ?>


                                <?php
//*****************  alterno 2 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd3a)) {
                                    (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = 'DDE8FF';
                                ?>
                                    <tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                                    <td align="center">
                                        <?=
                                        $Lcd[orden];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[fecha];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[cliente];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[sexo];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[estudio];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo3=='Logico'){

                                            echo $Lcd[l];
                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo3=='Caracter'){

                                            echo $Lcd[c];

                                            if(strtolower($Lcd[c])<>$vcaracter3){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter3){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[suc];
                                        ?>
                                    </td>
                                    </tr>
                                <?php
                                    $nRng++;
                                }
                                ?>


                                <?php
//*****************  alterno 3 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd4a)) {
                                    (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = 'DDE8FF';
                                ?>
                                    <tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                                    <td align="center">
                                        <?=
                                        $Lcd[orden];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[fecha];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[cliente];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[sexo];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[estudio];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo4=='Logico'){

                                            echo $Lcd[l];
                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo4=='Caracter'){

                                            echo $Lcd[c];

                                            if(strtolower($Lcd[c])<>$vcaracter4){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter4){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[suc];
                                        ?>
                                    </td>
                                    </tr>
                                <?php
                                    $nRng++;
                                }
                                ?>

                                <?php
//*****************  alterno 4 ***************//
                                while ($Lcd = mysql_fetch_array($Lcd5a)) {
                                    (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = 'DDE8FF';
                                ?>
                                    <tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>
                                    <td align="center">
                                        <?=
                                        $Lcd[orden];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[fecha];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[cliente];
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[sexo];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?=
                                        $Lcd[fechan];
                                        $Fechanac = $Lcd[fechan];
                                        $array_nacimiento = explode("-", $Fechanac);
                                        $array_actual = explode("-", $Fecha);
                                        $anos = $array_actual[0] - $array_nacimiento[0]; // calculamos años
                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[estudio];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php

                                        if($Lcd[suc]==1){
                                            $conot1++;
                                        }elseif($Lcd[suc]==2){
                                            $conot2++;
                                        }elseif($Lcd[suc]==3){
                                            $conot3++;
                                        }elseif($Lcd[suc]==4){
                                            $conot4++;
                                        }elseif($Lcd[suc]==5){
                                            $conot5++;
                                        }elseif($Lcd[suc]==6){
                                            $conot6++;
                                        }

                                        if($Tipo5=='Logico'){

                                            echo $Lcd[l];
                                            if($Lcd[l]=='S'){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        }                                            

                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        }     

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif($Lcd[l]=='N'){

                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }

                                        }elseif($Tipo5=='Caracter'){

                                            echo $Lcd[c];

                                            if(strtolower($Lcd[c])<>$vcaracter5){

                                                if($Lcd[suc]==1){
                                                    $conls1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conls2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conls3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conls4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conls5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conls6++;
                                                }

                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexoms1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexoms2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexoms3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexoms4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexoms5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexoms6++;
                                                    }

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }

                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofs1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofs2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofs3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofs4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofs5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofs6++;
                                                    } 

                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12pa1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12pa2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12pa3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12pa4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12pa5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12pa6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11pn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11pn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11pn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11pn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11pn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11pn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }elseif(strtolower($Lcd[c])==$vcaracter5){
                                                
                                                if($Lcd[suc]==1){
                                                    $conln1++;
                                                }elseif($Lcd[suc]==2){
                                                    $conln2++;
                                                }elseif($Lcd[suc]==3){
                                                    $conln3++;
                                                }elseif($Lcd[suc]==4){
                                                    $conln4++;
                                                }elseif($Lcd[suc]==5){
                                                    $conln5++;
                                                }elseif($Lcd[suc]==6){
                                                    $conln6++;
                                                } 
                                                
                                                if($Lcd[sexo]=='M'){

                                                    if($Lcd[suc]==1){
                                                        $consexomn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexomn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexomn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexomn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexomn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexomn6++;
                                                    } 
                                                
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 

                                                    }
                                                }elseif($Lcd[sexo]=='F'){

                                                    if($Lcd[suc]==1){
                                                        $consexofn1++;
                                                    }elseif($Lcd[suc]==2){
                                                        $consexofn2++;
                                                    }elseif($Lcd[suc]==3){
                                                        $consexofn3++;
                                                    }elseif($Lcd[suc]==4){
                                                        $consexofn4++;
                                                    }elseif($Lcd[suc]==5){
                                                        $consexofn5++;
                                                    }elseif($Lcd[suc]==6){
                                                        $consexofn6++;
                                                    } 
                                                    
                                                    if ($anos > 12) {

                                                        if($Lcd[suc]==1){
                                                            $contaranos12na1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos12na2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos12na3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos12na4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos12na5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos12na6++;
                                                        } 
                                                        
                                                    }elseif($anos <= 12){

                                                        if($Lcd[suc]==1){
                                                            $contaranos11nn1++;
                                                        }elseif($Lcd[suc]==2){
                                                            $contaranos11nn2++;
                                                        }elseif($Lcd[suc]==3){
                                                            $contaranos11nn3++;
                                                        }elseif($Lcd[suc]==4){
                                                            $contaranos11nn4++;
                                                        }elseif($Lcd[suc]==5){
                                                            $contaranos11nn5++;
                                                        }elseif($Lcd[suc]==6){
                                                            $contaranos11nn6++;
                                                        } 
                                                        
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                    </td>                                
                                    <td align="center">
                                        <?=
                                        $Lcd[suc];
                                        ?>
                                    </td>
                                    </tr>
                                <?php
                                    $nRng++;
                                }
                                ?>

                                </table>  
                        <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
    </body>
</html>
<?php
mysql_close();
?>