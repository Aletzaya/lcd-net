<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST["busca1"])) {

    if ($_REQUEST["busca1"] == "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy1"] = array('', '', 'nomf.id', 'Asc', $Retornar, $_REQUEST["busca"]);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy1'][0] = $_REQUEST[busca];
    }
}
if ($_REQUEST["busca"] == "ini") {

    $_SESSION["OnToy1"] = array('', '', 'nomf.id', 'Asc', $Retornar, $_SESSION['OnToy1'][5]);
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST["pagina"])) {
    $_SESSION['OnToy1'][1] = $_REQUEST["pagina"];
}
if (isset($_REQUEST["orden"])) {
    $_SESSION['OnToy1'][2] = $_REQUEST["orden"];
}
if (isset($_REQUEST["Sort"])) {
    $_SESSION['OnToy1'][3] = $_REQUEST["Sort"];
}
if (isset($_REQUEST["Ret"])) {
    $_SESSION['OnToy1'][4] = $_REQUEST["Ret"];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy1"][0];
$pagina = $_SESSION["OnToy1"][1];
$OrdenDef = $_SESSION["OnToy1"][2];
$Sort = $_SESSION["OnToy1"][3];
$RetSelec = $_SESSION["OnToy1"][4];
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$busca = $_REQUEST["busca"];
$Msj = $_REQUEST["Msj"];
$Id = 32;
$Fecha = date("Y-m-d H:m:s");
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

//Pagina a la que regresa con parametros        
if (strlen($Qry["filtro"]) > 2) {
    $Dsp = 'Filtro activo';
}

$Palabras = str_word_count($busca);  //Dame el numero de palabras
if ($Palabras > 1) {
    $P = str_word_count($busca, 1);          //Metelas en un arreglo
    for ($i = 0; $i < $Palabras; $i++) {
        if (!isset($BusInt)) {
            $BusInt = " $OrdenDef LIKE  '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " AND $OrdenDef like '%$P[$i]%' ";
        }
    }
} else {
    $busca === "ini" ? $BusInt = $OrdenDef . " LIKE '%" . $_SESSION["OnToy1"][5] . "%'" : $BusInt = $OrdenDef . " LIKE '%" . $busca . "%'";
}

if ($_REQUEST["Op"] === "Delete") {
    $SqlD = "DELETE FROM nomf WHERE id = " . $_SESSION["OnToy1"][5] . " AND cuenta = " . $_REQUEST["Elimina"];
    if (mysql_query($SqlD)) {
        $Msj = "Registro eliminado con Exito!";
        $Return = "nominaegr.php";
        AgregaBitacoraEventos($Gusr, "/R.Humanos/Nomina/Egresos/Borra registro de nomina id " . $_REQUEST["Elimina"], "nomf", $Fecha, $_SESSION["OnToy1"][5], $Msj, $Return);
    }
}

#Armo el query segun los campos tomados de qrys;
$cSql = "SELECT $Qry[campos], emp.id 
             FROM $Qry[froms] 
             WHERE nomf.cuenta=emp.id AND nomf.id='" . $_SESSION["OnToy1"][5] . "' AND $BusInt $Qry[filtro]";

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array();    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array(" Borrar ", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle Nomina Ingreso ::..</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <table width="99%"><tr><td align="right"><a href="nomina.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a></td></tr></table>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">
                    <?php
                    PonEncabezado();

                    $res = mysql_query($cSql);

                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

                    if ($busca <> '') {
                        $sql = $cSql . " ORDER BY " . $orden . "  LIMIT " . $limitInf . "," . $tamPag;
                    } else {
                        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    }
                    
                    $res = mysql_query($sql);
                    echo mysql_error();
                    $Pos = strrpos($_SERVER[PHP_SELF], ".");
                    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
                    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

                    while ($rg = mysql_fetch_array($res)) {

                        if (($nRng % 2) > 0) {
                            $Fdo = 'FFFFFF';
                        } else {
                            $Fdo = $Gfdogrid;
                        }    //El resto de la division;
                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                        Display($aCps, $aDat, $rg);

                        echo "<td class='Seleccionar' align='center'><a class='edit' href='nominaegr.php?Elimina=$rg[id]&Op=Delete'><i class='fa fa-trash fa-2x' aria-hidden='true'></i></a></td>";
                        echo "</tr>";

                        $nRng++;
                    }
                    ?>
                </td>
            </tr>
        </table>
        <?php
        PonPaginacion(false);           #-------------------pon los No.de paginas-------------------    
        CuadroInferior($busca);
        ?>

    </body>

    <script src="./controladores.js"></script>

</html>
<?php
mysql_close();
?>

