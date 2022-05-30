<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Usr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Estudio      = $_REQUEST["Estudio"];
$noconformidad = $_REQUEST["noconformidad"];
$Tnoconformidad = $_REQUEST["Tnoconformidad"];
$boton        = $_REQUEST["boton"];
$Fechano  = date("Y-m-d H:i:s");

require ("config.php");

if($Tnoconformidad==1){
  $Tnoconformidad2='1 Insufic. Disponibilidad del Pac.';
}elseif($Tnoconformidad==2){
  $Tnoconformidad2='2 Falta de capacitacion';
}elseif($Tnoconformidad==3){
  $Tnoconformidad2='3 Falta de comunicacion';
}elseif($Tnoconformidad==4){
  $Tnoconformidad2='4 Errores';
}elseif($Tnoconformidad==5){
  $Tnoconformidad2='5 Accidentes';
}elseif($Tnoconformidad==6){
  $Tnoconformidad2='Muestra Insufic / Inadecuada';
}elseif($Tnoconformidad==7){
  $Tnoconformidad2='Preparacion Incorrecta';
}elseif($Tnoconformidad==8){
  $Tnoconformidad2='Otro';
}

$Tnoconformidad3=$Tnoconformidad2.' : - '.$noconformidad;

if($boton=='Enviar'){
	
	$Up  = mysql_query("UPDATE otd SET noconformidad='$Tnoconformidad3',usrno='$Usr',fechano='$Fechano'
            WHERE orden='$busca' and estudio='$Estudio'"); 

    $Ups  = mysql_query("UPDATE ot SET noconformidad='Si' WHERE orden='$busca'"); 

}elseif($boton=='Borrar'){

    $Up  = mysql_query("UPDATE otd SET noconformidad='',usrno='',fechano=''
          WHERE orden='$busca' and estudio='$Estudio'"); 

    $Ups  = mysql_query("UPDATE ot SET noconformidad='No' WHERE orden='$busca'"); 

}
  
if(strlen($busca)>4 AND strlen($Estudio)>0){

    $Fecha = date("Y-m-d");

    $Hora  = date("H:i");

    $OtdA  = mysql_query("SELECT cli.nombrec as nombrecli,otd.noconformidad,otd.estudio,otd.orden,ot.institucion,est.descripcion,otd.fechano,otd.usrno
           FROM ot,cli,otd,est
           WHERE ot.orden='$busca' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio' AND otd.estudio=est.estudio");

    $Otd   = mysql_fetch_array($OtdA);    
}    		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: No conformidades ::.</title>
<link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
</head>
<body topmargin="1">

<form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>">
  
    <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
        <tr>
            <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: No conformidades ::..
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
                    <?php
                        $nomcliente = ucwords(strtolower(substr($Otd[nombrecli],0,50)));
                    ?>
                    <tr style="height: 30px" class="letrap" bgcolor='#f1f1f1'>
                        <td width='28%' align="lefth" class="ssbm">
                            <font size="3"><strong><?= $nomcliente ?></strong></font>
                        </td>
                    </tr>
                    <tr style="height: 30px" class="letrap">
                        <td width='28%' align="lefth" class="ssbm">
                            <font size="2"><strong><?= $Estudio ?> - <?= $Otd[descripcion] ?></strong></font>
                        </td>
                    </tr>
                </table>
                <br>
                <table width='98%' align='center' border='1' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>

                    <tr bgcolor="#2c8e3c">
                        <td class='letratitulo' align="center" colspan="10">..:: Detalle de Estudios ::..</td>
                    </tr>
                    <tr style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                        <td class="letrap" align="center">
                            <strong>Tipo de No conformidad</strong>
                        </td>
                        <td class="letrap" align="center">
                            <strong>No Conformidad</strong>
                        </td>
                    </tr> 

                    <tr style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                        <td class="letrap" align="center">
                        <select name='Tnoconformidad' class="letrap">
                            <option value='1'>Insufic. Disponibilidad del Pac.</option>
                            <option value='2'>Falta de capacitacion</option>
                            <option value='3'>Falta de comunicacion</option>
                            <option value='4'>Errores</option>
                            <option value='5'>Accidentes</option>
                            <option value='6'>Muestra Insufic / Inadecuada</option>
                            <option value='7'>Preparacion Incorrecta</option>
                            <option value='8'>Otro</option>
                            <?php
                                if($Tnoconformidad==1){
                                    $Tnoconformidad2='Insufic. Disponibilidad del Pac.';
                                }elseif($Tnoconformidad==2){
                                    $Tnoconformidad2='Falta de capacitacion';
                                }elseif($Tnoconformidad==3){
                                    $Tnoconformidad2='Falta de comunicacion';
                                }elseif($Tnoconformidad==4){
                                    $Tnoconformidad2='Errores';
                                }elseif($Tnoconformidad==5){
                                    $Tnoconformidad2='Accidentes';
                                }elseif($Tnoconformidad==6){
                                    $Tnoconformidad2='Muestra Insufic / Inadecuada';
                                }elseif($Tnoconformidad==7){
                                    $Tnoconformidad2='Preparacion Incorrecta';
                                }elseif($Tnoconformidad==8){
                                    $Tnoconformidad2='Otro';
                                }
                            ?>

                            <option selected value='1'><?= $Tnoconformidad2 ?></option>
                        </select>
                        </td>
                        
                        <td class="letrap" align="center">
                            <input type='TEXT' name='noconformidad' size='50' value='<?= $Otd[noconformidad] ?>'>
                            <input type='hidden' name='busca' value=<?= $busca ?>>
                            <input type='hidden' name='Estudio' value=<?= $Otd[estudio] ?>>
                            <input type='submit' name='boton' value='Enviar'>

                            <?php
                              if($Usr=='NAZARIO'){
                            ?>
                                <input type='submit' name='boton' value='Borrar'>
                            <?php
                              }
                            ?>

                        </td>
                    </tr>
                    <tr style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                        <td class="letrap" align="center">
                            <strong>Fecha</strong>
                        </td>
                        <td class="letrap" align="center">
                            <strong>Usuario</strong>
                        </td>
                    </tr> 
                    <tr style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                        <td class="letrap" align="center"><?= $Otd[fechano] ?></td>
                        <td class="letrap" align="center"><?= $Otd[usrno] ?></td>
                    </tr> 
                </table>
            </td>
        </tr>
    </table>
</form>


<?php  
/*
echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

echo "<body bgcolor='#FFFFFF' onload='cFocus()'>";          
  
  echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

		echo "$Gfont <p align='center'><b>Observaciones por Estudio</b></p>";
		
    echo "<div align='left'><font size='+2'> Orden: &nbsp; $Otd[institucion] - $Orden &nbsp; $Otd[nombrec]</font></div>";

    echo "<div align='center'><font size='+1' color='blue'>$Otd[estudio] - &nbsp; $Otd[descripcion]</font></div>";

        echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

        echo "<tr height='25' bgcolor='#CCCCCC'>";
        echo "<td align='center'>$Gfont2 Tipo de No conformidad</td>";
        echo "<td align='center'>$Gfont2 Observacion</td>";
        echo "<td align='center'>$Gfont2 Fecha</td>";
        echo "<td align='center'>$Gfont2 Usr</td>";
        echo "</tr>";      

       if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";   

            $Tnoconformidad = substr($Otd[noconformidad], 0);                    

            echo "<td>";
            echo "<select name='Tnoconformidad'>";
            echo "<option value='1'>Insufic. Disponibilidad del Pac.</option>";
            echo "<option value='2'>Falta de capacitacion</option>";
            echo "<option value='3'>Falta de comunicacion</option>";
            echo "<option value='4'>Errores</option>";
            echo "<option value='5'>Accidentes</option>";
            echo "<option value='6'>Muestra Insufic / Inadecuada</option>";
            echo "<option value='7'>Preparacion Incorrecta</option>";
            echo "<option value='8'>Otro</option>";
            if($Tnoconformidad==1){
                $Tnoconformidad2='Insufic. Disponibilidad del Pac.';
            }elseif($Tnoconformidad==2){
                $Tnoconformidad2='Falta de capacitacion';
            }elseif($Tnoconformidad==3){
                $Tnoconformidad2='Falta de comunicacion';
            }elseif($Tnoconformidad==4){
                $Tnoconformidad2='Errores';
            }elseif($Tnoconformidad==5){
                $Tnoconformidad2='Accidentes';
            }elseif($Tnoconformidad==6){
                $Tnoconformidad2='Muestra Insufic / Inadecuada';
            }elseif($Tnoconformidad==7){
                $Tnoconformidad2='Preparacion Incorrecta';
            }elseif($Tnoconformidad==8){
                $Tnoconformidad2='Otro';
            }
            echo "<option selected value='1'> $Tnoconformidad2 </option></p>";
            echo "</select></td>";

    			$noconformidad = $_REQUEST[noconformidad];
       		echo "<td><input type='TEXT' name='noconformidad' size='70' value='$Otd[noconformidad]'>";
      		echo "<input type='hidden' name='Orden' value=$Orden>";
      		echo "<input type='hidden' name='Estudio' value=$Otd[estudio]>";
          echo "&nbsp; &nbsp; <input type='submit' name='boton' value='Enviar'>";
          if($Usr=='NAZARIO'){
            echo "&nbsp; &nbsp; &nbsp; <input type='submit' name='boton' value='Borrar'>";
          }
          echo "</td><td>$Gfont $Otd[fechano]</td>";
          echo "<td>$Gfont $Otd[usrno]</td>";
          echo "</tr>";
        }            	   
  		
        echo "</table>";
          
        while($nRng<=6){  
            echo "<div align='center'>$Gfont &nbsp; </div>";
            $nRng++;
        }    
echo "</form>";         
  	                
*/

mysql_close();
?>   

</body>
  
</html>
  
