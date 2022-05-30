<?php
session_start();

if (!isset($_REQUEST[Depto])) {
    $Depto = $_SESSION['cVarVal'];
} else {
    $_SESSION['cVarVal'] = $_REQUEST[Depto];
    $Depto = $_REQUEST[Depto];
}

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

$tamPag = 15;

$op = $_REQUEST[op];

$team = $_COOKIE['TEAM'];

$Usr = $check['uname'];

$busca = $_REQUEST[busca];
$pagina = $_REQUEST[pagina];
$alterno = $_REQUEST[alterno];
$Msj = "";
$Usr = $_SESSION[Usr][0];
$Fecha = date("Y-m-d");
$Hora = date("H:i");

//$Depto=$_REQUEST[Depto];

$Titulo = "Impresion de resultados ";
$OrdenDef = "ot.orden";            //Orden de la tabla por default

$DepA = mysql_query("SELECT * FROM dep");
$SubA = mysql_query("SELECT subdepto,nombre FROM depd");

if ($op == 'im') {

    //Graba quien genera la impresion y manda direcciona la impresion;		  
    $cLnk = $_REQUEST[clnk] . '.php';
    $Ord = $_REQUEST[Orden];
    $Est = $_REQUEST[Estudio];

    $OtdA = mysql_query("SELECT cuatro,lugar FROM otd WHERE orden='$Ord' AND estudio='$Est' LIMIT 1");
    $Otd = mysql_fetch_array($OtdA);

//      if(substr($Otd[cuatro],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;

    $Fecha = date("Y-m-d");
    $Hora = date("H:i");

    if ($Otd[lugar] <= '5') {

        $Up = mysql_query("UPDATE otd set impres = '$Fecha $Hora', lugar='5', impest='$Usr'
	                WHERE orden='$Ord' AND estudio='$Est'");
    } else {

        $Up = mysql_query("UPDATE otd set impres = '$Fecha $Hora', impest='$Usr' 
	               WHERE orden='$busca' AND estudio='$Est'");
    }

//		}

    $NumA2 = mysql_query("SELECT otd.estudio,otd.impres
	   FROM otd 
	   WHERE otd.orden='$Ord' AND otd.impres='0000-00-00 00:00:00'");

    if (mysql_num_rows($NumA2) == 0) {
        $lUp = mysql_query("UPDATE ot SET impreso='Si' WHERE orden='$Ord'");
    } else {
        $lUp = mysql_query("UPDATE ot SET impreso='No' WHERE orden='$Ord'");
    }

    $Fecha = date("Y-m-d");
    $Hora = date("H:i");
    mysql_query("INSERT INTO bit (fecha,hora,usr,accion,elemento)
         VALUES
         ('$Fecha','$Hora','$Usr','$Titulo.$Est','$Ord')");

    if ($alterno == 0) {
        header("Location: informes/$cLnk?Orden=$Ord&Estudio=$Est&team=$team&usr=$Usr");
    } else {
        if ($alterno == 1) {
            header("Location: informesalt/$cLnk?Orden=$Ord&Estudio=$Est&team=$team&usr=$Usr");
        } else {
            header("Location: informesalt2/$cLnk?Orden=$Ord&Estudio=$Est&team=$team&usr=$Usr");
        }
    }
} elseif (strlen($Depto) > 0) {

    if (strlen($busca) > 0) {
        $SqlA = "SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,otd.status,ot.institucion,otd.alterno
        FROM ot,est,otd,cli 
        WHERE ot.orden=otd.orden AND est.depto='$Depto' AND ot.cliente=cli.cliente AND otd.estudio=est.estudio 
              AND otd.status='TERMINADA' AND ot.status<>'Entregada' AND otd.orden='$busca'";
    } else {
        $SqlA = "SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,otd.status,ot.institucion,otd.alterno
        FROM ot,est,otd,cli 
        WHERE ot.orden=otd.orden AND est.depto='$Depto' AND ot.cliente=cli.cliente AND otd.estudio=est.estudio 
              AND otd.status='TERMINADA' AND ot.status<>'Entregada'";
    }
}

$Edit = array(" &nbsp; &nbsp; &nbsp; ", "Edit", "Paciente", "Est", "Descripcion", "Inst", "Orden", "Fecha", "Hora", "Status", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-");

require ("config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    <meta charset="UTF-8">
        <title><?php echo $Titulo; ?></title>
    </head>
    <script language="JavaScript1.2">

        function ValSuma() {
            var lRt;
            lRt = true;
            if (document.form3.SumaCampo.value == "CAMPOS") {
                lRt = false;
            }
            if (!lRt) {
                alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
                return false;
            }
            return true;
        }

        function Mayusculas(cCampo) {
            if (cCampo == 'Recibio') {
                document.form1.Recibio.value = document.form1.Recibio.value.toUpperCase();
            }
        }

        function WinRes(url) {
            window.open(url, "WinRes", "scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=900,height=500,left=30,top=80")
        }

    </script>

    <body bgcolor="#FFFFFF">

        <?php
        headymenu($Titulo, 1);

        echo "$Gfont";
        echo "<form name='form1' method='post' action='estdeptoimp.php'>";
        echo " &nbsp; Departamento :  ";
        echo "<select name='Depto'>";
        while ($dep = mysql_fetch_array($DepA)) {
            echo "<option value='$dep[0]'>$dep[1]</option>";
            if ($dep[0] == $Depto) {
                $Def = $dep[1];
            }
        }
        echo "<option selected value='$Depto'>$Def</option>";
        echo "</select>";
        echo " &nbsp; No.Orden : ";

        echo "<input type='text' name='busca' size='6' maxlength='6'>";

        echo " &nbsp; &nbsp; <input type='submit' name='Submit' value='Ok'>";
        if ($Depto == 'TERMINADA') {
            $Msj = "ORDENES PENDIENTES DE IMPRESION";
        }
        echo "<div align='center'><font color='#990000' size='3'>$Msj</font></div>";
        echo "</form></font>";

        //echo $SqlA." ORDER BY ".$OrdenDef;

        if ($res = mysql_query($SqlA . " ORDER BY " . $OrdenDef, $link)) {

            CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

            if ($limitInf < 0) {
                $limitInf = 0;
            }

            $sql = $SqlA . $cWhe . " ORDER BY " . $orden . " ASC LIMIT " . $limitInf . "," . $tamPag;

            $res = mysql_query($sql, $link);

            PonEncabezado();         #---------------------Encabezado del browse----------------------

            while ($rg = mysql_fetch_array($res)) {

                $clnk = strtolower($rg[4]);

                if (($nRng % 2) > 0) {
                    $Fdo = 'FFFFFF';
                } else {
                    $Fdo = $Gfdogrid;
                }    //El resto de la division;

                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                if ($Depto <> 2) {

                    echo "<td align='center'><a class='pg' href=javascript:WinRes('estdeptoimp.php?clnk=$clnk&Orden=$rg[1]&Estudio=$rg[4]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
                    //echo "<td align='center'><a class='pg' href=javascript:WinRes('informes/$clnk.php?Orden=$rg[1]&Estudio=$rg[4]&pagina=$pagina&Depto=TERMINADA')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
                } else {

                    echo "<td align='center'><a class='pg' href=javascript:WinRes('impword.php?Orden=$rg[1]&Estudio=$rg[4]&pagina=$pagina')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
                }

                echo "<td align='center'><a class='pg' href=javascript:WinRes('capturaresdiag.php?busca=$rg[1]&estudio=$rg[4]&pagina=$pagina&Depto=TERMINADA')> &nbsp; <img src='lib/edit.png' alt='Edita reg' border='0'> </td>";

                echo "<td>$Gfont " . substr($rg[0], 0, 28) . "</font></td>";
                /*
                  if($Depto=='TERMINADA'){
                  $ImpEst=$rg[4].".php";
                  echo "<td><a href=javascript:WinRes('informes/$rg[4].php?Orden=$rg[1]&Estudio=$rg[4]&pagina=$pagina&Depto=$Depto')><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#0066FF'><b>Imp</b></font></a></td>";
                  }else{
                  echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#0066FF'><b> - </b></font></td>";
                  }
                 */
                echo "<td>$Gfont $rg[4] </font></td>";
                //echo "<td><a href=capturaresdiag.php?busca=$rg[1]&estudio=$rg[4]&pagina=$pagina&Depto=$Depto><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#0066FF'><b>$rg[4]</b></font></a></td>";
                echo "<td>$Gfont $rg[5]</font></td>";
                echo "<td>$Gfont $rg[7]</font></td>";
                echo "<td>$Gfont $rg[1]</font></td>";
                echo "<td>$Gfont $rg[2]</font></td>";
                echo "<td>$Gfont $rg[3]</font></td>";
                echo "<td>$Gfont $rg[6]</font></td>";
                echo "</tr>";
                $nRng++;
            }//fin while
            echo "</table>";

            PonPaginacion(false);      #-------------------pon los No.de paginas-------------------
            //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------
        }//fin if

        mysql_close();
        ?>

    </body>

</html>
<?php
mysql_close();
?>
