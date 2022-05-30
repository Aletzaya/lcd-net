<?php

session_start();

date_default_timezone_set("America/Mexico_City");

require ("config.php");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$Orden = $_REQUEST[Orden];
$Orden2 = $_REQUEST[Orden];
$Estudio = $_REQUEST[Estudio];
$Entregapac = $_REQUEST[Entregapac];
$Recibepac = $_REQUEST[Recibepac];
$Obsentrega = $_REQUEST[Obsentrega];
$Op = $_REQUEST[Op];
$Usr = $check['uname'];
$Fechaest = date("Y-m-d H:i:s");

if (strlen($Orden) > 4 AND strlen($Estudio) > 0) {

    $Fecha = date("Y-m-d");

    $Hora = date("H:i");

    $NumA = mysql_query("SELECT otd.estudio FROM otd WHERE otd.orden='$Orden' AND mid(otd.seis,1,4)='0000'");

    $OtdA = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega,otd.cinco,otd.recibeencaja,ot.encaja FROM ot,cli,otd WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio'");

    if ($Op == '2') {

        $OtdA3 = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega FROM ot,cli,otd WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");

        if ($Otd = mysql_fetch_array($OtdA3)) {

            if (substr($Otd[seis], 0, 4)) { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
                if ($Otd[obsentrega] = '') {

                    $Up = mysql_query("UPDATE otd SET obsentrega='$Obsentrega' WHERE orden='$Orden' and obsentrega=''");
                }
            }
        }
    } elseif ($Op == '1' and $Estudio == 'TODOS') {

        $OtdA2 = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega,
			otd.cinco,otd.recibeencaja
			FROM ot,cli,otd 
			WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");

        if ($Otd = mysql_fetch_array($OtdA2)) {

            if (substr($Otd[seis], 0, 4)) { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
                if ($Otd[entregapac] = ' ') {

                    $Up = mysql_query("UPDATE otd SET seis = '$Fecha $Hora', lugar='6',entregapac='$Entregapac',otd.recibepac='$Recibepac',obsentrega='$Obsentrega'
							WHERE orden='$Orden' and entregapac=''");

                    $lUp = mysql_query("UPDATE ot SET status='Entregada' WHERE orden='$Orden'");

                    $Msj = "Estudio actualizado con exito!!!";

                    if ($Otd[recibeencaja] = ' ') {

                        $Up = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Entregapac' 
								WHERE orden='$Orden' and recibeencaja=' '");

                        $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");
                    }
                }
            } else {

                $Up = mysql_query("UPDATE otd SET lugar = '6', entregapac='$Entregapac', recibepac='$Recibepac' ,obsentrega='$Obsentrega' WHERE orden='$Orden'");
                $Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
            }
        }
    } elseif ($Op == '3') {

        if ($Otd = mysql_fetch_array($OtdA)) {

            if (substr($Otd[seis], 0, 4) == '0000') { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
                $Up = mysql_query("UPDATE otd SET seis = '$Fecha $Hora', lugar='6',entregapac='$Entregapac',otd.recibepac='$Recibepac',otd.obsentrega='$Obsentrega' 
					   WHERE orden='$Orden' AND estudio='$Estudio'");

                $Msj = "Estudio actualizado con exito!!!";

                if (substr($Otd[cinco], 0, 4) == '0000') { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
                    $Up = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Entregapac' 
						WHERE orden='$Orden' AND estudio='$Estudio'");

                    $Msj = "Estudio actualizado con exito!!!";
                }
            } else {

                $Up = mysql_query("UPDATE otd SET lugar = '6', entregapac='$Entregapac',otd.recibepac='$Recibepac',otd.obsentrega='$Obsentrega'
							 WHERE orden='$Orden' AND estudio='$Estudio'");
                $Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
            }
        }

        if (mysql_num_rows($NumA) == 1) {

            $lUp = mysql_query("UPDATE ot SET status='Entregada' WHERE orden='$Orden'");
        }
    } elseif ($Op == 'res') {

        $Up = mysql_query("UPDATE otd SET seis = '0000-00-00 00:00:00', lugar='6',recibepac='', entregapac='', obsentrega=''
			   WHERE orden='$Orden' AND estudio='$Estudio'");

        $Msj = 'Entrega de Est Paciente $Estudio RESTAURADA';

        $lUp2 = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
			('$Fechaest','$Usr','Entrega al Pac RESTAURADA Est: $Estudio Ot: $Orden')");

        $lUp3 = mysql_query("UPDATE ot SET status='TERMINADA' WHERE orden='$Orden'");

        if ($Otd[encaja] == 'No') { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
            $lUp4 = mysql_query("UPDATE ot SET encaja='No' WHERE orden='$Orden'");
        } else {

            $lUp4 = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");
        }
    }

}elseif($Op=='ed'){
		
		$Up  = mysql_query("UPDATE otd SET seis = '0000-00-00 00:00:00', lugar='5',recibepac='', entregapac='', obsentrega=''
		   WHERE orden='$Orden' AND estudio='$Estudio'"); 
		$Msj = 'Entrega de Est Paciente $Estudio RESTAURADA';
 	    $lUp2  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
		('$Fechaest','$Usr','Entrega al Pac RESTAURADA Est: $Estudio Ot: $Orden')");
 	    $lUp3 = mysql_query("UPDATE ot SET status='TERMINADA' WHERE orden='$Orden'");
}


$Gfont = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='1'>";
$Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";

echo "<html>";

echo "<head>";
echo "<meta charset='UTF-8'>";
//echo "<title>$Titulo</title>";

?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Entrega de Resultados Pacientes</title><link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
<?php

?>

<script language="JavaScript1.2">

    function cFocus() {

        document.form1.Obsentrega.focus();

    }

</script>

<?php
echo "<body bgcolor='#FFFFFF' onload='cFocus()'>";

echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValCampos();'>";

echo "<font size='2'><p align='center'><b>Informe de Estudios entregados al Paciente</b></p>";

$aLug = array('Etiqueta', 'Etiqueta', 'Proceso', 'Captura', 'Impresion', 'Recepcion', 'Entregado');

$HeA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.observaciones,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entmos,ot.entmosf,ot.entmost,ot.entmoslr,ot.entmoscms,ot.entmosch,ot.tentregamost,ot.tentregamed,ot.tentregainst,ot.entwhatpac,ot.entwhatmed FROM ot,cli
    WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

$He = mysql_fetch_array($HeA);


if ($He[entemailpac] == '1' or $He[entemailmed] == '1' or $He[entemailinst] == '1' or $He[entwhatpac] == '1' or $He[entwhatmed] == '1') {
    if ($He[entemailpac] == '1') {
        $HeC = mysql_query("SELECT cli.mail FROM ot,cli
                 WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

        $Hecli = mysql_fetch_array($HeC);

        $correop = " <b>Paciente: </b>" . $Hecli[mail];
    } else {
        $correop = "";
    }

    if ($He[entemailmed] == '1') {
        $Hem = mysql_query("SELECT med.mail FROM ot,med
                 WHERE ot.orden='$Orden' AND ot.medico=med.medico");

        $Hemed = mysql_fetch_array($Hem);

        $correom = " - <b>Medico: </b>" . $Hemed[mail];
    } else {
        $correom = "";
    }

    if ($He[entemailinst] == '1') {

        $Hei = mysql_query("SELECT inst.mail FROM ot,inst
                 WHERE ot.orden='$Orden' AND ot.institucion=inst.institucion");

        $Heinst = mysql_fetch_array($Hei);

        $correoi = " - <b>Institucion: </b>" . $Heinst[mail];
    } else {
        $correoi = "";
    }

    if($He[entwhatpac] == '1'){
        $Digentregawp='WhatsApp Paciente';
    }else{
        $Digentregawp='';
    }

    if($He[entwhatmed] == '1'){
        $Digentregawm='WhatsApp Medico';
    }else{
        $Digentregawm='';
    }

    $correo = " <i class='fa fa-envelope-o fa-2x' aria-hidden='true'></i> Entrega Digital  -->" . $correop . "  " . $correom . "  " . $correoi . "  " . $Digentregawp . "  " . $Digentregawm;
} else {
    $correo = " ";
}

echo "<div align='center'> &nbsp; <font size='+2'>" .ucwords(strtolower($He[nombrec])) . "</font></div>";
echo "<div align='center'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden </b></div>";

//******** ENTREGA ***********//

if($He[entmos]=='1'){
    $Sucentregam='Matriz';
}
if($He[entmosf]=='1'){
    $Sucentregaf='H.Futura';
}
if($He[entmost]=='1'){
    $Sucentregat='Tepexpan';
}
if($He[entmoslr]=='1'){
    $Sucentregalr='Reyes';
}
if($He[entmoscms]=='1'){
    $Sucentregacms='Camarones';
}
if($He[entmosch]=='1'){
    $Sucentregach='Sn Vicente';
}

$Sucentrega="<i class='fa fa-hospital-o fa-2x' aria-hidden='true'></i>  Entrega en Sucursal  -->" .' '. $Sucentregam .' '. $Sucentregaf .' '. $Sucentregat .' '. $Sucentregalr .' '. $Sucentregacms .' '. $Sucentregach;


if($He[tentregamost]=='1'){
    $PMIentregap='Paciente';
}
if($He[tentregamed]=='1'){
    $PMIentregam='Medico';
}
if($He[tentregainst]=='1'){
    $PMIentregai='Institucion';
}

$PMIentrega="<i class='fa fa-file-pdf-o fa-2x' aria-hidden='true'></i>Entrega a  -->" .' '. $PMIentregap .' '. $PMIentregam .' '. $PMIentregai;
/*
if($He[entemailpac]==1){
    $Digentregaep='Mail Paciente';
}
if($He[entemailmed]==1){
    $Digentregaem='Mail Medico';
}
if($He[entemailinst]==1){
    $Digentregaei='Mail Institucion';
}
if($He[entwhatpac]==1){
    $Digentregawp='WhatsApp Paciente';
}
if($He[entwhatmed]==1){
    $Digentregawm='WhatsApp Medico';
}

$Digentrega=$Digentregaep .' '. $Digentregaem .' '. $Digentregaei .' '. $Digentregawp .' '. $Digentregawm;
*/
//echo "<div align='letf'>Entrega Solicitada en Sucursal:<b> $Sucentrega </b></div>";

//echo "<div align='letf'>Entrega Solicitada al:<b> $PMIentrega </b></div>";

//echo "<div align='letf'>Entrega Solicitada Digital a:<b> $Digentrega </b></div>";


//******** ENTREGA ***********//


$Sql = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega
		 		  FROM otd,est 
                  WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");

echo "<table align='center' width='99%' border='0' cellspacing='0' cellpadding='0'>";
echo "<tr height='25' bgcolor='#CCCCCC'>";
echo "<td align='center'>$Gfont <b> Estudio </b></td>";
echo "<td align='center'>$Gfont <b> Descripcion </b></td>";
echo "<td align='center'>$Gfont <b> &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Paciente&Obsentrega=$Obsentrega'>Paciente </b></a></td>";
echo "<td align='center'>$Gfont <b> &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Familiar&Obsentrega=$Obsentrega'>Familiar</b></a></td>";
echo "<td align='center'>$Gfont <b> &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Clinic/Inst&Obsentrega=$Obsentrega'>Clinic/Inst</b></a></td>";
echo "<td align='center'>$Gfont <b> &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Otro&Obsentrega=$Obsentrega'>Otro </b> </a></td>";
echo "<td align='center'>$Gfont <b> &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Op=2&Entregapac=$Entregapac&Estudio=TODOS&Obsentrega=$Obsentrega'>Observaciones</b> </a></td>";
echo "<td align='center'>$Gfont <b> Fecha/hora </b></td>";
echo "<td align='center'>$Gfont <b> Entrego </b></td>";
if ($Usr == 'nazario' or $Usr == 'MARYLIN') {
    echo "<td align='center'>$Gfont <b> Edita </b></td>";
    echo "<td align='center'>&nbsp;</td>";
}
echo "</tr>";

while ($rg = mysql_fetch_array($Sql)) {

    $Lugar = $rg[lugar];

    if (($nRng % 2) > 0) {
        $Fdo = 'FFFFFF';
    } else {
        $Fdo = $Gfdogrid;
    }    //El resto de la division;

    echo "<tr class='letrap' height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";
    echo "<td>$Gfont $rg[estudio]</td>";
    echo "<td>$Gfont $rg[descripcion]</td>";
    if ($rg[recibepac] == 'Paciente') {
        echo "<td align='center'><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></td>";
    } else {
        if ($rg[recibepac] == '') {
            echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Paciente&Obsentrega=$Obsentrega&Op=3'>Paciente</a></td>";
        } else {
            echo "<td align='center'>$Gfont &nbsp; - </td>";
        }
    }
    if ($rg[recibepac] == 'Familiar') {
        echo "<td align='center'><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></td>";
    } else {
        if ($rg[recibepac] == '') {
            echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Familiar&Obsentrega=$Obsentrega&Op=3'>Familiar</a></td>";
        } else {
            echo "<td align='center'>$Gfont &nbsp; - </td>";
        }
    }
    if ($rg[recibepac] == 'Clinic/Inst') {
        echo "<td align='center'><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></td>";
    } else {
        if ($rg[recibepac] == '') {
            echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Clinic/Inst&Obsentrega=$Obsentrega&Op=3'>Clinic/Inst</a></td>";
        } else {
            echo "<td align='center'>$Gfont &nbsp; - </td>";
        }
    }
    if ($rg[recibepac] == 'Otro') {
        echo "<td align='center'><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></td>";
    } else {
        if ($rg[recibepac] == '') {
            echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Otro&Obsentrega=$Obsentrega&Op=3'>Otro</a></td>";
        } else {
            echo "<td align='center'>$Gfont &nbsp; - </td>";
        }
    }

    if ($rg[obsentrega] <> '') {
        echo "<td align='center'>$Gfont $rg[obsentrega] <i class='fa fa-check' style='color:green;' aria-hidden='true'></i></td>";
    } else {
        if ($Obsentrega <> '') {
            if ($rg[recibepac] == '') {
                $Recibepac2 = '';
                $Entregapac2 = $Entregapac;
            } else {
                $Recibepac2 = $rg[recibepac];
                $Entregapac2 = $rg[entregapac];
            }
            echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Recibepac=$Recibepac2&Entregapac=$Entregapac2&Obsentrega=$Obsentrega&Op=3'>$Obsentrega</a></td>";
        } else {
            echo "<td align='center'>$Gfont $rg[obsentrega] </td>";
        }
    }
    echo "<td>$Gfont $rg[seis]</td>";
    echo "<td>$Gfont $rg[entregapac]</td>";
    if ($Usr == 'nazario' or $Usr == 'MARYLIN' or $Usr == 'Andrea') {
        echo "<td align='center'>$Gfont &nbsp; </td>";
        //		echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Op=ed'>***EDITA</a></td>";
        echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Op=res'>***RESTAURAR</a></td>";
    }
    echo "</tr>";
    $nRng++;
}

echo "</table>";

//echo "<form name='form' method='post' action='ordenpac.php?'> ";
echo "<a class='letrap'>Observaciones : </a>";
echo "<input class='textos' class='texto' name='Obsentrega' type='text' size='20'> &nbsp; ";
echo "<input  class='letrap' type='submit' name='boton' value='OK'>";
echo "<div align='center'>$Gfont &nbsp; </div>";
echo "<div align='center'><strong> <font color='#F000000' Size=2>RECUERDA PONER PRIMERO LAS OBSERVACIONES Y DESPUES ELEGIR LA OPCION DE ENTREGA </strong></div> </font>";

echo "<br />";
echo "<div align='left'><font size='2'> &nbsp; <b>Observaciones: $He[observaciones]</b></font></div>";
echo "<br />";
echo "<div align='left'><font size='2'> " . $correo . "</b></font></div>";
echo "<div align='left'><font size='2'> " . $Sucentrega . "</b></font></div>";
echo "<div align='left'><font size='2'> " . $PMIentrega . "</b></font></div>";

//echo "</form>";
while ($nRng <= 5) {
    echo "<div align='center'>$Gfont &nbsp; </div>";
    $nRng++;
}

echo "$Gfont <a class='edit' href='javascript:window.close()'><i class='fa fa-times fa-2x' style='color:red;' aria-hidden='true'></i> Cerrar</a> &nbsp; &nbsp; ";
echo "Entrega a paciente: <input type='TEXT' name='Entregapac' readonly='readonly' size='10' value='$Entregapac'> ";
echo "&nbsp No.orden: <input type='TEXT' name='Orden' size='4' value=$Orden> &nbsp; &nbsp; ";
echo "<input class='letrap' type='submit' name='boton' value='Enviar'>";

echo "</form>";



echo "</body>";

echo "</html>";

mysql_close();
?> 