<?php
#Librerias
session_start();
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];
$Cia = $_SESSION["Usr"][1];
if ($_REQUEST["Nomina"]) {
    $_SESSION['Nomina'] = $_REQUEST["Nomina"];
}
$Nomina = $_SESSION['Nomina'];

if (isset($_REQUEST["pagina"])) {
    $_SESSION['pagina'] = $_REQUEST["pagina"];
}
if (isset($_REQUEST["Sort"])) {
    $_SESSION['Sort'] = $_REQUEST["Sort"];
}        #Orden Asc o Desc
if (isset($_REQUEST["orden"])) {
    $_SESSION['orden'] = $_REQUEST["orden"];
}    #Campo por el cual se ordena	
#Saco los valores de las sessiones los cuales normalmente no cambian;
$pagina = $_SESSION["pagina"];   //Ojo saco pagina de la session
$Sort = $_SESSION["Sort"];         //Orden ascendente o descendente
$OrdenDef = $_SESSION["orden"];        //Orden de la tabla por default

$cSql = "SELECT emp.id,emp.nombre,emp.faltas,emp.horasext,emp.cobertura,emp.primavac,emp.otrosing,emp.otrosegr,
   depn.nombre as dep,diastrab,emp.festivos
	FROM emp,depn
	WHERE emp.cia='$_REQUEST[Cianvo]' AND emp.status='Activo' AND emp.nomina='$Nomina' AND emp.departamento = depn.id
   ORDER BY emp.departamento,emp.nombre";
//echo $cSql;
#Intruccion a realizar si es que mandan algun proceso
if ($_REQUEST["Boton"] == 'Guarda cambios') {        //Para agregar uno nuevo
    $ini = ($pagina - 1) * 10;

    $res = mysql_query($cSql);

    while ($rg = mysql_fetch_array($res)) {

        $Faltas = 'f' . $rg[id];  //Variables de paso para cada registro;
        $Horas = 'h' . $rg[id];
        $Cobertura = 'c' . $rg[id];
        $Primavac = 'p' . $rg[id];
        $Otrosing = 'i' . $rg[id];
        $Otrosegr = 'e' . $rg[id];
        $Diastrab = 'd' . $rg[id];
        $Festivos = 'l' . $rg[id];     //festivos laborados;
        $up = "UPDATE emp SET faltas='$_REQUEST[$Faltas]',horasext='$_REQUEST[$Horas]',
 			  			 cobertura='$_REQUEST[$Cobertura]',primavac='$_REQUEST[$Primavac]',otrosing='$_REQUEST[$Otrosing]',
 			          otrosegr='$_REQUEST[$Otrosegr]',diastrab='$_REQUEST[$Diastrab]',festivos='$_REQUEST[$Festivos]'
 			          WHERE id=$rg[id] limit 1";
        //echo $up;
        $lUp = mysql_query($up);

        //echo " Var $nFaltas valor: $_REQUEST[$nFaltas]";
    }

    $pagina = $pagina + 1;

    //echo "<script language='javascript'>setTimeout('self.close();',100)</script>";
}


require ("config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

    <head>

        <title><?php echo $Titulo; ?></title>
        <?php require ("./config_add.php"); ?>
    </head>

    <body bgcolor="#FFFFFF">

        <?php
        echo "<form name='form1' method='get' action='$_SERVER[PHP_SELF]'>";
        echo "<table class='letrap' align='center' width='98%' border='0' cellpadding=0 cellspacing=2>";
        echo "<tr>";
        echo "<th height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>Dep</font></th>";
        if ($OrdenDef == 'emp.id') {   //Es el campo por el cual esta en este momento ordenado;
            if ($Sort == 'Asc') {
                $Srt = 'Desc';
                $iImg = 'asc.png';
            } else {
                $Srt = 'Asc';
                $iImg = 'des.png';
            }
            echo "<th height='25' bgcolor='#63a9f6'><a class='edit' href='" . $_SERVER["PHP_SELF"] . "?Sort=$Srt'> &nbsp; Id &nbsp; <img src='lib/$iImg' border='0'> </a></th>";
        } else {
            echo "<th height='25' bgcolor='#63a9f6'><a class='edit' href='" . $_SERVER["PHP_SELF"] . "?orden=emp.id&Sort=Asc'> Id </a></th>";
        }

        //echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'> Id </font></th>";
        if ($OrdenDef == 'emp.nombre') {   //Es el campo por el cual esta en este momento ordenado;
            if ($Sort == 'Asc') {
                $Srt = 'Desc';
                $iImg = 'asc.png';
            } else {
                $Srt = 'Asc';
                $iImg = 'des.png';
            }
            echo "<th height='25' bgcolor='#63a9f6'><a class='edit' href='" . $_SERVER["PHP_SELF"] . "?Sort=$Srt'> &nbsp; N o m b r e &nbsp; <img src='lib/$iImg' border='0'> </a></th>";
        } else {
            echo "<th height='25' bgcolor='#63a9f6'><a class='edit' href='" . $_SERVER["PHP_SELF"] . "?orden=emp.nombre&Sort=Asc'> &nbsp; N o m b r e </a></th>";
        }
        //echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'> Nombre </font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>Dias/t</font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>Falts</font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>Festivs</font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>O.Ing</font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>Hrs.ext</font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>Cobert</font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>Prima/Vac</font></th>";
        echo "<th  height='25' bgcolor='#63a9f6'>$Gfont <font color='ffffff'>O.egresos</font></th>";
        echo "</tr>";

        $res = mysql_query($cSql);
        $Rgs = mysql_num_rows($res);

        $NumPags = ceil($Rgs / 10);

        $ini = ($pagina - 1) * 10;

        $sql = $cSql . " $Sort";
        //echo $sql;
        $res = mysql_query($sql);

        $nRen = 0;

        while ($registro = mysql_fetch_array($res)) {
            $Fdo = (($nRng % 2) > 0) ? 'FFFFFF' : $Gfdogrid;
            $Faltas = 'f' . $registro[id];  //Variables de paso para cada registro;
            $Horas = 'h' . $registro[id];
            $Cobertura = 'c' . $registro[id];
            $Primavac = 'p' . $registro[id];
            $Otrosing = 'i' . $registro[id];
            $Otrosegr = 'e' . $registro[id];
            $Diastrab = 'd' . $registro[id];
            $Festivos = 'l' . $registro[id];

            echo "<tr onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo'; bgcolor='$Fdo'>";
            echo "<td>$Gfont " . substr($registro[dep], 0, 3) . " </font></td>";
            echo "<td>$Gfont $registro[0] </font></td>";
            echo "<td>$Gfont " . substr($registro[1], 0, 25) . " </font></td>";
            echo "<td align='center'><input type='text' name='$Diastrab' value='$registro[diastrab]' size='2'></td>";
            echo "<td align='center'><input type='text' name='$Faltas' value='$registro[faltas]' size='2'></td>";
            echo "<td align='center'><input type='text' name='$Festivos' value='$registro[festivos]' size='2'></td>";
            echo "<td align='center'><input type='text' name='$Otrosing' value='$registro[otrosing]' size='4'></td>";
            echo "<td align='center'><input type='text' name='$Horas' value='$registro[horasext]' size='5'></td>";
            echo "<td align='center'><input type='text' name='$Cobertura' value='$registro[cobertura]' size='5'></td>";
            echo "<td align='center'><input type='text' name='$Primavac' value='$registro[primavac]' size='5'></td>";
            echo "<td align='center'><input type='text' name='$Otrosegr' value='$registro[otrosegr]' size='4'></td>";
            echo "</tr>";
            $nRng++;
        }

        cTableCie();

        echo "<div align='right' class='letrap'>$Gfont No.Regs.: $Rgs &nbsp; &nbsp; &nbsp; </div><hr>";

        echo "<p align='center'>";
        echo " &nbsp; &nbsp; &nbsp; &nbsp;  ";
        echo "<input type='hidden' name='Cianvo' value='$_REQUEST[Cianvo]'>";
        echo "<input type='submit' name='Boton' value='Guarda cambios'>";
        echo "</p>";

        echo "<div align='center'><a class='letrap' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></div>";

        //Botones();

        echo "</form>";

        echo "</body>";

        echo "</html>";

        mysql_close();
        ?>