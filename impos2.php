<?php
  // Es una copia de clientes solo s cambia las url a clienteseord
  session_start();

  date_default_timezone_set("America/Mexico_City");

$Usr = $_SESSION[Usr][0];
$Suc = $_SESSION[Usr][4];

  if(!isset($_REQUEST[Vta])){$Vta=$_SESSION['Venta_ot'];}else{

    $Vta=$_REQUEST[Vta];

    $_SESSION['Venta_ot']=$_REQUEST[Vta];


 } 
  $busca=$_REQUEST[busca];

require ("config.php");
  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<title>Orden de servicio a domicilio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
include("lib/lib.php");
$link=conectarse();

$CiaA   =   mysql_query("SELECT direccion FROM cia WHERE id='$Suc'",$link);
$Cia    =   mysql_fetch_array($CiaA);


$OtA  = mysql_query("SELECT otnvas.inst,otnvas.lista,otnvas.cliente,otnvas.medico,otnvas.receta,otnvas.fechar,
        inst.nombre as nombreinst
        FROM otnvas
        LEFT JOIN inst ON otnvas.inst=inst.institucion
        WHERE otnvas.venta='$Vta' and otnvas.usr='$Usr'");
$Ot     =   mysql_fetch_array($OtA);

$CliA   =   mysql_query("SELECT * FROM cli WHERE cliente='$Ot[cliente]'",$link);
$Cli    =   mysql_fetch_array($CliA);


$Fecha  = date("Y-m-d");

$Mes    = substr($Fecha,5,2)*1;
$Hora   = date("H:i");

$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

$FechaLet  = " &nbsp a ".substr($Fecha,8,2)." de ".$aMes[$Mes]." del ".substr($Fecha,0,4) . " &nbsp; &nbsp; ". $Hora; 

echo "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
echo "<tr>";
echo "<td width='5%' align='left' valign='center'>";

    echo "<img src='lib/DuranNvoBk.png'>";

echo "</td><td>";
    echo "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
    echo "<tr><td align='center'>$Gfont ";
        echo "<b>Orden de Servicio a domicilio";
    echo "</td></tr>";
    echo "<tr><td align='center'>$Gfont ";
        echo "$FechaLet";
    echo "</td></tr>";
    echo "<tr><td align='center'>$Gfont ";
        echo "Institucion: " . $Ot[institucion] . " " . $Ot[nombreinst];
    echo "</td></tr>";
    echo "</table>";
echo "</td></tr>";
echo "</table><hr>$Gfont ";

$Anos          = $Fecha - $Cli[fechan];

echo "<div align='center'><b>Nombre del paciente:</b> ";
echo $Cli[nombrec] . " &nbsp &nbsp &nbsp &nbsp   <b>Edad:</b> " . $Anos . "&nbsp &nbsp &nbsp &nbsp  <b>Sexo:</b> " . $Cli[sexo];
echo "</div>";

echo "<div align='center'><b>Direccion:</b> $Cli[direccion] &nbsp; <b>Colonia:</b> $Cli[colonia]</div>";
echo "<div align='center'><b>Mpio.:</b>$Cli[municipio] &nbsp; <b>Referencia de ubicacion:</b> $Cli[refubicacion]</div><br>";

echo "<table width='90%' border='0' align='center' cellpadding='0' cellspacing='2'>";
echo "<tr height='23'>";
echo "<th>$Gfont #Servicio</th>";
echo "<th>$Gfont Estudio</th>";
echo "<th>$Gfont Descripcion</th>";
echo "<th>$Gfont Precio</th>";
echo "<th>$Gfont Dto</th>";
echo "<th>$Gfont Importe</th>";
echo "</tr>";

$OtdA = mysql_query("SELECT otdnvas.estudio,otdnvas.precio,otdnvas.descuento,est.depto,est.descripcion
            FROM otdnvas,est
            WHERE otdnvas.usr='$Usr' and otdnvas.venta='$Vta' and otdnvas.estudio=est.estudio");    #Checo k bno halla estudios capturados

while ($row = mysql_fetch_array($OtdA)) {
    $nRn++;    
   
    echo "<tr>";
    echo "<td align='center'>$Gfont $nRn</td>";
    echo "<td align='center'>$Gfont $row[estudio]</td>";
    echo "<td align='left'>$Gfont $row[descripcion]</td>";
    echo "<td align='right'>$Gfont ".number_format($row[precio],"2")." &nbsp; </td>";
    echo "<td align='right'>$Gfont ".number_format($row[descuento],"2")." &nbsp; </td>";
    echo "<td align='right'>$Gfont ".number_format($row[precio]-($row[precio]*$row[descuento]/100),"2")." &nbsp; </td>";
    echo "</tr>";    
    $Importe=$row[precio]-($row[precio]*$row[descuento]/100);
    $TImporte+=$Importe;
}

while($nRn <= 9){
    
  echo "<tr><td align='center'>$Gfont ---</td><td>$Gfont </td><td>$Gfont </td><td>$Gfont </td><td>$Gfont </td><td>$Gfont </td></tr>";
  $nRn++;      
    
}
echo "<tr><td align='center'>$Gfont ---</td><td>$Gfont </td><td>$Gfont </td><td>$Gfont Total: </td><td>$Gfont </td><td align='right'>$Gfont $ ".number_format($TImporte,"2")." &nbsp; </td></tr>";
echo "</table>$Gfont "; 

echo "<p> &nbsp; &nbsp; Lugar de la visita: ______________________________________________________________________________________________________________________</p>";

echo "<p>&nbsp; &nbsp; Referencia: ___________________________________________________________________________________________________________________________</p>";

echo "<p>&nbsp; &nbsp; Observaciones: ____________________________________________________________________________________________________________________________</p>";

echo "<p align='center'><b>Tipo de servicio:</b> &nbsp;  ___express &nbsp;&nbsp; ___urgente &nbsp;&nbsp; ___ordinario &nbsp;&nbsp; &nbsp; ";
echo "<b>Fecha de entrega:</b> _____________________ &nbsp; <b>Hora:</b> ____________</p>";
echo "<p align='center'><b>Entrega de resultados:</b> &nbsp;  ___ correo &nbsp; ___ mostrador &nbsp; ___ domicilio</p>";

echo "<br><p>&nbsp; &nbsp; Realiza servicio:  _____________________________________________";
echo "Realiza proceso:  _________________________________________</p>";
echo "<p>&nbsp; &nbsp; Vehiculo: ______________________________________________";
echo "Fecha:  _____________________________________</p>";

echo "</body>";

echo "</html>";

mysql_close();
?>
