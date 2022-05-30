<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST[busca])) {
    if ($_REQUEST[busca] == ini) {
        $Pos = strrpos($_REQUEST[Ret], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef
        if ($Pos > 0) {
            $Retornar = $_REQUEST[Ret] . '&';
        } else {
            if ($_REQUEST[Ret] <> '') {
                $Retornar = $_REQUEST[Ret] . '?';
            }
        }
        $_SESSION["OnToy"] = array('', '', 'ot.orden', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST[pagina])) {
    $_SESSION['OnToy'][1] = $_REQUEST[pagina];
}
if (isset($_REQUEST[orden])) {
    $_SESSION['OnToy'][2] = $_REQUEST[orden];
}
if (isset($_REQUEST[Sort])) {
    $_SESSION['OnToy'][3] = $_REQUEST[Sort];
}
if (isset($_REQUEST[Ret])) {
    $_SESSION['OnToy'][4] = $_REQUEST[Ret];
}
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION[OnToy][0];
$pagina = $_SESSION[OnToy][1];
$orden = $_SESSION[OnToy][2];
$Sort = $_SESSION[OnToy][3];
$RetSelec = $_SESSION[OnToy][4];
$Id = 63;
$FechaT = date("Y-m-d h:i:s");
$Fecha = date("Y-m-d");
$FechaH = date("h:i:s");
$Orden2 = $_REQUEST[Orden2];
$Estudio = $_REQUEST[Estudio];
if ($_REQUEST[Op] == 'Rec') {

    $SqlC = "SELECT * FROM maqdet WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio'";

    $resC = mysql_query($SqlC);

    $registro3 = mysql_fetch_array($resC);


    if (empty($registro3)) {

        $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,frec,hrec,usrrec)
		VALUES
		('$Orden2','$Estudio','$Fecha','$FechaH','$Gusr')");
    } else {

        $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frec='$Fecha',hrec='$Hora',usrrec='$Gusr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio' limit 1");
    }
}if ($_REQUEST[Op] == '1') {
    if ($_REQUEST[Regis] == '1') {
        $Up = mysql_query("UPDATE otd SET fechaest='$FechaT', usrest='$Gusr', statustom='TOMA/REALIZ', status='TOMA/REALIZ'
					  WHERE orden='$Orden2' AND estudio='$Estudio'");

        $OtdA = mysql_query("SELECT dos,lugar,estudio FROM otd WHERE orden='$Orden2' AND estudio='$Estudio'");

        while ($Otd = mysql_fetch_array($OtdA)) {
            $Est = $Otd[estudio];
            if (substr($Otd[dos], 0, 4) == '0000') {
                if ($Otd[lugar] <= '3') {
                    $lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$FechaT' 
							 WHERE orden='$Orden2' and estudio='$Estudio' limit 1");
                } else {
                    $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$FechaT' 
							 WHERE orden='$Orden2' AND estudio='$Estudio' limit 1");
                }
            }

            $SqlC = "SELECT * FROM maqdet WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Est'";

            $resC = mysql_query($SqlC, $link);

            $registro4 = mysql_fetch_array($resC);

            if (empty($registro4)) {

                $lUp = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
						VALUES
						('$Orden2','$Est','$_REQUEST[Suc]','$Fecha','$Hora','$Usr')");
            } else {
                $lUp = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Est',mint='$_REQUEST[Suc]',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Est' limit 1");
            }
        }
    } else {
        $Up = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$statustom', status='$statustom'
	          WHERE orden='$Orden2' AND estudio='$Estudio'");
    }

    $NumA1 = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$Orden2' AND otd.statustom='PENDIENTE'");

    $NumA2 = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$Orden2' AND otd.statustom=' '");

    if (mysql_num_rows($NumA1) >= 1) {
        $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$Orden2'");
    } else {
        if (mysql_num_rows($NumA2) == 0) {
            $lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$Orden2'");
        } else {
            $lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$Orden2'");
        }
    }
}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

#Armo el query segun los campos tomados de qrys;

if ($_REQUEST[busca] !== "ini") {
    $cSql = "SELECT $Qry[campos],ot.cliente,otd.usrest,otd.statustom,otd.alterno,otd.estudio,est.depto,otd.capturo,otd.fr,otd.creapdf,"
            . "otd.usrvalida,est.depto,ot.entemailpac,ot.entemailmed,ot.entemailinst FROM $Qry[froms] "
            . "WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND ot.orden=otd.orden AND ot.orden='$_REQUEST[Orden]'";
}
$aCps = SPLIT(",", $Qry[campos]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("Cap", "-", "-", "Etiq", "-", "-", "Imp", "-", "-", "Img", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry[edi]);     //Arreglo donde llena el grid de datos
$aDer = array("Msj", "-", "-", "Tom/Real", "-", "-", "Proc", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Estudios por departamento ::..</title>
        <link href="estilos.css?v=1.3" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>

    <?php
    echo '<body topmargin="1">';
    encabezados();
    if ($_REQUEST[Mnu] == "") {
        $Gmenu = 2;
        menu($Gmenu,$Gusr);
    } else {
        menu($Gmenu,$Gusr);
    }
    //submenu();
//Tabla contenedor de brighs
    ?>
        <script src="./controladores.js"></script>

    <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
        <table>
            <tr class="letrap">
                <td>
                    Numero de Orden: 
                    <input type="text" name="Orden" value="<?= $_REQUEST[Orden] ?>" class="letrap"></input>
                    <input type="submit" name="Busca" value="busca" class="letrap"></input>
                </td>
            </tr>
        </table>
    </form>
    <?php
    echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tr>';
    echo '<td valign="top">';

    PonEncabezado();
    $res = mysql_query($cSql);

    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

    $res = mysql_query($cSql);

    while ($rg = mysql_fetch_array($res)) {
        $clnk = strtolower($rg[estudio]);
        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;
        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

        $SqlB = "SELECT *
		FROM maqdet
		WHERE maqdet.orden='$rg[orden]' AND maqdet.estudio='$rg[estudio]'";

        $resB = mysql_query($SqlB);

        $registro2 = mysql_fetch_array($resB);


        if ($rg[depto] <> 2) {
            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('estdeptocvalcppdf.php?busca=$rg[orden]&estudio=$rg[estudio]&alterno=$rg[alterno]')><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
        } else {
            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('estdeptoc.php?busca=$rg[orden]&estudio=$rg[estudio]&alterno=$rg[alterno]')><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true' style='color:#E74C3C'></i></a></td>";
        }

        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('impeti.php?op=1&busca=$rg[orden]&Est=$rg[estudio]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";



        if ($rg[capturo] <> '' and $rg[fr] == '0') {

            if ($rg[depto] <> 2) {
                if ($rg[creapdf] == 'pdf') {
                    echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('resultapdf.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                } else {
                    echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('resultapdf.php?cInk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-print fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
                }
            } else {
                echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> ";
            }
        } else {
            if ($rg[capturo] <> '' and $rg[usrvalida] <> '') {
                if ($rg[depto] <> 2) {
                    if ($rg[creapdf] == 'pdf') {
                        echo "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#FF0000'></i></a></td>";
                    } else {
                        echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$rg[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
                    }
                } else {
                    echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$rg[orden]&Estudio=$rg[estudio]')><i class='fa fa-file-pdf-o' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> ";
                }
            } else {
                echo "<td align='center'>-</td>";
            }
        }
        $ImgA = mysql_query("SELECT archivo FROM estudiospdf WHERE id='$rg[orden]' and usrelim=''");

        $Img = mysql_fetch_array($ImgA);
        if ($Img[archivo] <> '') {
            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('displayestudioslcdimg.php?op=1&busca=$rg[orden]&estudio=$rg[estudio]')><i class='fa fa-search fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
        } else {
            echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('displayestudioslcdimg.php?op=1&busca=$rg[orden]&estudio=$rg[estudio]')><i class='fa fa-upload fa-lg' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
        }
        Display($aCps, $aDat, $rg);

        if ($rg[entemailpac] == '1' or $rg[entemailmed] == '1' or $rg[entemailinst] == '1') {
            echo "<td align='center'><a class='pg' href=javascript:wingral('entregamail2.php?Orden=$rg[orden]')><i class='fa fa-envelope' aria-hidden='true' style='color:#2E86C1'></i></a></td>";
        } else {
            echo "<td align='center'>&nbsp;</a></td>";
        }

        if ($rg[statustom] == 'TOMA/REALIZ') {
            echo "<td align='center'>$Gfont <font size='1'><b>" . ucwords(strtolower($rg[usrest])) . "</b></font></td>";
        } elseif ($rg[statustom] == 'PENDIENTE') {
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Op=1&Estudio=$rg[estudio]&Regis=1'><i class='fa fa-exclamation-triangle fa-lg' aria-hidden='true' style='color:#F4D03F'></i></a></td>";
        } else {
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Op=1&Estudio=$rg[estudio]&Regis=1'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
        }
        if (isset($registro2[usrrec])) {
            echo "<td align='center'>$Gfont <font size='1'><b>" . ucwords(strtolower($registro2[usrrec])) . "</b></font></td>";
        } else {
            echo "<td class='Seleccionar' align='center'><a class='edit' href='$_SERVER[PHP_SELF]?Orden2=$rg[orden]&Estudio=$rg[estudio]&Op=Rec'><i class='fa fa-times-circle fa-lg' aria-hidden='true' style='color:#DC7633'></i></a></td>";
        }
        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    echo "<table> <td><tr><a class='cMsj'>";
    echo $_REQUEST[msj];
    echo "</a></tr></td></table>";
    PonPaginacion(false);           #-------------------pon los No.de paginas-------------------    
    //CuadroInferior($busca);
    echo '</body>';
    ?>
</html>
<?php
mysql_close();
?>
