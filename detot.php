<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  require ("config.php");
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");

  $link      = conectarse();

  $Orden = $_REQUEST["Orden"];
  $Gusr = $_SESSION["Usr"][0];
  $Gcia = $_SESSION["Usr"][1];
  $Gnomcia = $_SESSION["Usr"][2];
  $Gnivel = $_SESSION["Usr"][3];
  $Gteam = $_SESSION["Usr"][4];
  $Gmenu = $_SESSION["Usr"][5];
  $Fecha = date("Y-m-d H:m:s");
  $Msj = $_REQUEST["Msj"];
  $depto     = $_REQUEST[depto];
  $Fechaest  = date("Y-m-d H:i:s");

  if($depto=="1"){
    $depto=" and est.depto=1";
  }elseif($depto=="2"){
    $depto=" and est.depto=2";
  }else{
    $depto=" and est.depto>2";
  }

$HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.observaciones,ot.entemailpac,ot.entemailmed,ot.entemailinst FROM ot,cli
WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

$He   = mysql_fetch_array($HeA);   

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

<table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 0px solid #999;'>
  <tr bgcolor="#2c8e3c">
      <td class='letratitulo' align='center' colspan="6">..:: Detalle de Estudios ::..</i></a></td>
  </tr>
  <tr>
      <td class='letrap'><font size='+2'><?= $He[nombrec] ?></font></td>
  </tr>
  <tr>
      <td class='letrap'>Fecha: <?= $He[fecha] ?> Hra: <?= $He[hora] ?> Fecha de entrega: <?= $He[fechae] ?> &nbsp; <b>No.ORDEN: <?= $He[institucion] ?> - <?= $Orden ?></td>
  </tr>
  </table>
<br>
<?php

$Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja,est.depto FROM otd,est 
WHERE otd.orden='$Orden' AND otd.estudio=est.estudio $depto");


echo "<table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

echo "<tr height='25'>";
echo "<td align='center' class='letrap'><b>Estudio</b></td>";
echo "<td align='center' class='letrap'><b>Descripcion</b></td>";   
echo "<td align='center' class='letrap'><b>Fecha/hora</b></td>";
echo "<td align='center' class='letrap'><b>Recibio</b></td>";  
echo "</tr>";              

while($rg=mysql_fetch_array($Sql)){

if (($nRng % 2) > 0) {    $Fdo = 'FFFFFF';  } else {    $Fdo = $Gfdogrid;  }    //El resto de la division;

  echo "<tr height='25' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
  echo "<td class='letrap'>$rg[estudio]</td>";
  echo "<td class='letrap'>$rg[descripcion]</td>";
  echo "<td class='letrap'>$rg[cinco]</td>";
  echo "<td class='letrap'>$rg[recibeencaja]</td>";
  echo "</tr>";
  $nRng++;

}        
echo "</table>";
echo "<br />";
echo "<div align='left' class='letrap'> &nbsp; <b>Observaciones: $He[observaciones]</b></div>";
echo "<br />";

while($nRng<=3){  
    echo "<div align='center'>$Gfont &nbsp; </div>";
    $nRng++;
}    

mysql_close();

?>   	 

<a class='elim' href='javascript:window.close()'><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>

</body>
  
</html>
  

