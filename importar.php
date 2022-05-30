<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
require("lib/lib.php");
$link = conectarse();

require ("config.php");       //Parametros de colores;

$busca = $_REQUEST[busca];    #Campo por el cual se ordena	
$Cia = $_SESSION["Usr"][1];
$File = "pagos/" . $Cia . "nomina.txt";
$Titulo = "Catalogo de empleados";
$op = $_REQUEST[op];
$Id = 2;                            //Numero de query dentro de la base de datos
$Fecha = date('Y-m-d');

#Leo la nomina; 
$QryA = mysql_query("SELECT fechaf,numero FROM nom WHERE id=$busca", $link);
$Qry = mysql_fetch_array($QryA);

$cCiaA = mysql_query("SELECT cuenta FROM cia WHERE id=$Cia", $link);
$cCia = mysql_fetch_array($cCiaA);


$Sql = "SELECT nomf.cuenta,emp.nombre,emp.cuenta cntemp,nomf.sueldo,emp.id,
  nomf.septimo,nomf.produccion,nomf.asistencia,nomf.impfallas,nomf.impretardos,nomf.ispt,
  nomf.otrosegr
  FROM nomf,emp 
  WHERE nomf.cuenta=emp.id AND nomf.id='$busca' AND emp.cuenta<>' '
  ORDER BY emp.departamento, emp.nombre";
//echo $Sql;
$RgA = mysql_query($Sql);


$salida = "";
$salida .= "<table>";
$salida .= "<thead> <th>ID</th> <th>Nombre</th><th>Cuenta</th><th>Sueldo</th><th>Septimo</th></thead>";
while ($rg = mysql_fetch_array($RgA)) {
    $salida .= "<tr> <td>" . $rg["cuenta"] . "</td> <td>" . $rg["nombre"] . "</td><td>" . $rg["cntemp"] . "</td><td>" . $rg["sueldo"] . "</td>"
            . "<td>" . $rg["septimo"] . "</td></tr>";
}
$salida .= "</table>";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=usuarios_" . time() . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
echo $salida;

mysql_close();
?>
