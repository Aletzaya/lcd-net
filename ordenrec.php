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
$Estudio = $_REQUEST[Estudio];
$Recibeencaja = $_REQUEST[Recibeencaja];
$Op = $_REQUEST[Op];
$Usr = $check['uname'];
$Fechaest = date("Y-m-d H:i:s");

if (strlen($Orden) > 4 AND strlen($Estudio) > 0) {

    $Fecha = date("Y-m-d");

    $Hora = date("H:i");

    $NumA = mysql_query("SELECT otd.estudio 
	           FROM otd 
	           WHERE otd.orden='$Orden' AND mid(otd.cinco,1,4)='0000'");

    $OtdA = mysql_query("SELECT cli.nombrec,otd.lugar,otd.cinco,otd.recibeencaja
	           FROM ot,cli,otd 
	           WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio'");

    if ($Op == '1' and $Estudio == 'TODOS') {

        $OtdA2 = mysql_query("SELECT cli.nombrec,otd.lugar,otd.cinco,otd.recibeencaja
		   FROM ot,cli,otd 
		   WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");

        if ($Otd = mysql_fetch_array($OtdA2)) {

            if (substr($Otd[cinco], 0, 4)) { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
                if ($Otd[recibeencaja] = ' ') {

                    $Up = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Recibeencaja' 
							WHERE orden='$Orden' and recibeencaja=' '");

                    $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");


                    $Msj = "Estudio actualizado con exito!!!";
                }
            } else {

                $Up = mysql_query("UPDATE otd SET lugar = '6', recibeencaja='$Recibeencaja' WHERE orden='$Orden'");
                $Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
            }
        }

    } elseif ($Op == '2') {

        if ($Otd = mysql_fetch_array($OtdA)) {

            if (substr($Otd[cinco], 0, 4) == '0000') { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
                $Up = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Recibeencaja' 
	               WHERE orden='$Orden' AND estudio='$Estudio'");

                $Msj = "Estudio actualizado con exito!!!";
            } else {

                $Up = mysql_query("UPDATE otd SET lugar = '6', recibeencaja='$Recibeencaja' WHERE orden='$Orden' AND estudio='$Estudio'");
                $Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
            }
        }

        if (mysql_num_rows($NumA) == 1) {
            $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");
        }else{
            $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");
        }
    } elseif ($Op == 'res') {

        $Up = mysql_query("UPDATE otd SET cinco = '0000-00-00 00:00:00', lugar='5',recibeencaja='' 
		   WHERE orden='$Orden' AND estudio='$Estudio'");
        $Msj = 'Entrega de Estudio $Estudio RESTAURADA';
        $lUp2 = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
		('$Fechaest','$Usr','Entrega RESTAURADA Est: $Estudio Ot: $Orden')");
        $lUp3 = mysql_query("UPDATE ot SET encaja='No',status='En Recepc Parc' WHERE orden='$Orden'");
    }

    
    $NumA = mysql_query("SELECT otd.estudio 
               FROM otd 
               WHERE otd.orden='$Orden' AND mid(otd.cinco,1,4)='0000'");

    if(mysql_num_rows($NumA)==0){
      $lUp = mysql_query("UPDATE ot SET status='En Recepcion' WHERE orden='$Orden'");
    }else{
      $lUp = mysql_query("UPDATE ot SET status='En Recepc Parc' WHERE orden='$Orden'");
    } 

} else {
    $Msj = 'El estudio: $Estudio de la Orden: $Orden NO existe';
}

$Gfont = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='2'>";
$Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";

echo "<html>";

echo "<head>";
echo " <meta charset='UTF-8'>";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Entrega de Resultados Recepcion</title><link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
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
echo "</head>";
?>

<script language="JavaScript1.2">

    function cFocus() {

        document.form1.Orden.focus();

    }

</script>

<?php
echo "<body bgcolor='#FFFFFF' onload='cFocus()'>";

echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValCampos();'>";

echo "$Gfont <p align='center'><b>Informe de Estudios entregados a Recepcion</b></p>";

$aLug = array('Etiqueta', 'Etiqueta', 'Proceso', 'Captura', 'Impresion', 'Recepcion', 'Entregado');

$HeA = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.observaciones,ot.entemailpac,ot.entemailmed,ot.entemailinst FROM ot,cli
                 WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

$He = mysql_fetch_array($HeA);

if ($He[entemailpac] == '1' or $He[entemailmed] == '1' or $He[entemailinst] == '1') {
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
    $correo = " <i class='fa fa-envelope-o fa-2x' aria-hidden='true'></i> Enviar por correo  -->" . $correop . "  " . $correom . "  " . $correoi;
} else {
    $correo = " ";
}
echo "<div align='center'> &nbsp; <font size='+2'> " . ucwords(strtolower($He[nombrec])) . "</font></div>";
echo "<div align='center'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";

$Sql = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja FROM otd,est 
                  WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");

echo "<table align='center' width='99%' border='0' cellspacing='0' cellpadding='0'>";
echo "<tr height='25' bgcolor='#CCCCCC'>";
echo "<td align='center'>$Gfont2 Estudio</td>";
echo "<td align='center'>$Gfont2 Descripcion</td>";
echo "<td align='center'>$Gfont2 Lugar</td>";
echo "<td align='center'>$Gfont2 Recepcion</td>";
echo "<td align='center'>$Gfont2 Fecha/hora</td>";
echo "<td align='center'>$Gfont2 Recibio</td>";
if ($Usr == 'nazario' or $Usr == 'MARYLIN') {
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
    echo "<td> $rg[estudio]</td>";
    echo "<td> $rg[descripcion]</td>";
    echo "<td align='left'> &nbsp; $aLug[$Lugar]</td>";
    if ($Lugar == '6') {
        echo "<td align='center'><i class='fa fa-check' style='color:green;' aria-hidden='true'></i></td>";
    } else {
        echo "<td align='center'><a class='edit' href='ordenrec.php?Orden=$Orden&Estudio=$rg[estudio]&Recibeencaja=$Recibeencaja&Op=2'>Entregar</a></td>";
    }
    echo "<td> $rg[cinco]</td>";
    echo "<td> $rg[recibeencaja]</td>";

    if ($Usr == 'nazario' or $Usr == 'MARYLIN' or $Usr == 'Andrea') {
        echo "<td align='center'> &nbsp; <a class='pg' href='ordenrec.php?Orden=$Orden&Estudio=$rg[estudio]&Op=res'>***RESTAURAR</a></td>";
    }

    echo "</tr>";
    $nRng++;
}
echo "</table>";
echo "<br />";
echo "<div align='left'> &nbsp; <b>Observaciones: $He[observaciones]</b></div>";
echo "<br />";
echo "<div align='left'> " . $correo . "</b></div>";

while ($nRng <= 4) {
    echo "<div align='center'>$Gfont &nbsp; </div>";
    $nRng++;
}

echo "$Gfont <a class='elim' href='javascript:window.close()'><i class='fa fa-times fa-2x' style='color:red;' aria-hidden='true'></i>Cerrar</a> &nbsp; &nbsp; ";
echo "Recibe en caja: <input type='TEXT' name='Recibeencaja' readonly='readonly' size='10' value='$Recibeencaja'> ";
echo "&nbsp No.orden: <input type='TEXT' name='Orden' size='4' value=''> &nbsp; &nbsp; ";
echo "<input class='letrap' type='submit' name='boton' value='Enviar'>";

echo "<td align='center'>$Gfont &nbsp; <a class='edit' href='ordenrec.php?Orden=$Orden&Op=1&Recibeencaja=$Recibeencaja&Estudio=TODOS'>Entregar Todo</a></td>";

echo "</form>";

echo "</body>";

echo "</html>";

mysql_close();
?> 