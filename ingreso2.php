<?php
session_start();

date_default_timezone_set("America/Mexico_City");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

$Usr = $_SESSION[Usr][0];
$Suc = $_SESSION[Usr][1];

$busca = $_REQUEST[busca];
$Ingreso = $_REQUEST[Ingreso];
$Tpago = $_REQUEST[Tpago];
$op = $_REQUEST[op];
$Titulo = "I N G R E S O S / C A J A";
$Fecha = date("Y-m-d");
$Tabla = "cja";

if ($op == "Ab") {        //Para agregar uno nuevo
    if ($Ingreso > 0) {
        $Fecha = date("Y-m-d");
        $Hora = date("H:i");
//	 	$Hora1 = date("H:i");         
//    	 	$Hora2 = strtotime("-60 min",strtotime($Hora1));
//		$Hora  = date("H:i",$Hora2);

        $OtA = mysql_query("select importe,ubicacion from ot where orden='$busca'", $link);
        $Ot = mysql_fetch_array($OtA);
        $cSqlA = mysql_query("select sum(importe) from cja where orden='$busca'", $link);
        $Abonado = mysql_fetch_array($cSqlA);

        if (($Abonado[0] + $Ingreso + .5) >= $Ot[0]) {
            $lUp = mysql_query("update ot Set pagada='Si',fecpago='$Fecha' where orden='$busca'", $link);
        }

        $lUp = mysql_query("insert into $Tabla (orden,importe,fecha,hora,usuario,tpago,suc) VALUES ('$busca','$Ingreso','$Fecha','$Hora','$Usr','$Tpago','$Suc')", $link);
    }

    //header("Location: ordenesd.php?busca=$busca&pagina=$pagina");
    echo "<script lenguaje=\"JavaScript\">window.close();</script>";

    $CjaA = mysql_query("SELECT MAX(id) AS id from cja where orden='$busca'", $link);
    $Cja = mysql_fetch_array($CjaA);

    echo "<script>window.open('impotpdf.php?busca=$_REQUEST[busca]&ingreso=$Cja[id]','_blank','height=800,width=1000');</script>";
}

require ("config.php");       //Parametros de colores;

echo "<html>";

echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>$Titulo</title>";
?>

<link href="estilos.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
<link rel='icon' href='favicon.ico' type='image/x-icon' />
<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>


<?php
echo "</head>";
?>

<script language="JavaScript1.2">

    function Valido() {
        if (document.form1.Ingreso.value > <?php echo $Abonos; ?>) {
            alert("Revise la Cantidad a Abonar")
            return false
        } else {
            if (document.form1.Ingreso.value < 0) {
                alert("Revise la Cantidad a Abonar")
                return false
            } else {
                return true
            }
        }
    }

    function cFocus() {
        document.form1.Ingreso.focus();
    }


</script>

<?php
echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

//  headymenu($Titulo,0);

$cSqlA = mysql_query("select sum(importe) from cja where orden='$busca'");
$SqlS = mysql_fetch_array($cSqlA);

$cSqlH = "select ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,ot.medico,med.nombrec from ot,cli,med where ot.cliente=cli.cliente and ot.medico=med.medico and ot.orden='$busca'";

$HeA = mysql_query($cSqlH);
$He = mysql_fetch_array($HeA);

$Abonos = $He[importe] - $Sqls[0];

echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";

echo "<p align='center'><font size='+2' face='Arial, Helvetica, sans-serif'>Registro de pago de clientes</font></p>";

echo "</tr><tr>";
echo "</tr><tr>";

echo "<tr>";
echo "<td><font size='2' face='Arial, Helvetica, sans-serif'> No.Orden:  $busca  &nbsp; Cliente: $He[4]</font></td>";
//echo "<td>$Gfont <font color='#ffffff'> Cliente:  $He[cliente] $He[4]</td>";
//echo "<td>$Gfont <font color='#ffffff'> &nbsp; Fecha:  $He[fecha]  &nbsp; Fecha/entrega:  $He[fechae]</td>";
echo "</tr><tr bgcolor ='#618fa9'>";
//echo "<td>$Gfont Medico: $He[medico] $He[9]</td>";
echo "<td>$Gfont <font color='#ffffff'> Importe: $ " . number_format($He[importe], '2') . "&nbsp; &nbsp; Abonado: $ " . number_format($SqlS[0], '2') . "</td>";
echo "<td>$Gfont <font color='#ffffff'> Saldo: $ " . number_format($He[importe] - $SqlS[0], '2') . "</td></tr>";
echo "<tr>";
//echo "<td>$Gfont </td>";
//echo "</tr><tr>";
echo "</table>$Gfont ";

echo "<p>&nbsp;</p>";

echo "<div align='center'>";

echo "<form name='form1' method='post' action='ingreso2.php?busca=$busca&pagina=$pagina&op=Ab'>";

$Saldo = $He[importe] - $SqlS[0];

echo "<div align='center' class='letrap'>Tipo de pago :";
echo "<select class='letrap' name='Tpago'>";
echo "<option value='Efectivo'>Efectivo</option>";
echo "<option value='Tarjeta'>Tarjeta</option>";
echo "<option value='Cheque'>Cheque</option>";
echo "<option value='Credito'>Credito</option>";
echo "<option value='Nomina'>Nomina</option>";
echo "<option value='Transferencia'>Transferencia</option>";
echo "<option selected value ='Efectivo'>Efectivo</option>";
echo "</select>";
echo "</div>";
echo "<p>&nbsp;</p>";
echo "<div align='center' class='letrap'>Su pago por $";
echo "<input  class='letrap' name='Ingreso' value='$Saldo' type='text' size='5'>";
echo "<input class='letrap' type='submit' name='Submit' value='Enter'> &nbsp; &nbsp; ";
echo "</div>";

echo "<p>&nbsp;</p>";

//	  echo "<a href='ordenesd.php?busca=$busca&pagina=$pagina'><img src='lib/regresa.jpg' border='0'></a>";
echo "</form>";

$sql = "SELECT ot.fecha fot,cja.fecha,cja.usuario,cja.tpago,cja.importe,cja.hora,"
        . "fc1.total,fc1.id "
        . "FROM `ot` "
        . "LEFT JOIN cja ON ot.orden=cja.orden "
        . "LEFT JOIN "
        . "(SELECT fc.total,fcd.id,fcd.orden FROM fc LEFT JOIN fcd ON fc.id = fcd.id WHERE fcd.orden= $busca ORDER BY fc.id DESC limit 1 ) fc1 "
        . "ON fc1.orden=ot.orden "
        . "WHERE ot.orden = $busca;";

$rsPagos = mysql_query($sql);
?>

<table width="95%" class="letrap">
    <tr bgcolor ='#618fa9'>
        <td><strong><font color='#ffffff'>Fecha Orden</font></strong></td>
        <td><strong><font color='#ffffff'>Fecha Pago</font></strong></td>
        <td><strong><font color='#ffffff'>Usuario</font></strong></td>
        <td><strong><font color='#ffffff'>Tipo Pago</font></strong></td>
        <td><strong><font color='#ffffff'>Importe Pagado</font></strong></td>
        <td><strong><font color='#ffffff'>Importe Faltante Factura</font></strong></td>
        <td><strong><font color='#ffffff'>Id Factura</font></strong></td>
    </tr>
    <?php
    while ($rg = mysql_fetch_array($rsPagos)) {
        ($rg["tota"] - $rg["importe"]) > 0 ? $num = 0 : $num = $rg["total"] - $rg["importe"] ;
        ?>
        <tr>
            <td><?= $rg["fot"] ?></td>
            <td><?= $rg["fecha"] . " " . $rg["hora"] ?></td>
            <td><?= $rg["usuario"] ?></td>
            <td><?= $rg["tpago"] ?></td>
            <td><?= number_format($rg["importe"], 2) ?></td>
            <td><?= number_format($num, 2) ?></td>
            <td><?= $rg["id"] ?></td>
        </tr>
        <?php
    }
    ?>
</table>
<?php
echo "</body>";

echo "</html>";

mysql_close();
?>
