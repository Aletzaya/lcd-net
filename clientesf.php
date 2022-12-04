<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST[busca])) {

    if ($_REQUEST["busca"] == "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'clif.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST["busca"] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST["busca"];
    }
}

//Captura los valores que trae y metelos al array
if (isset($_REQUEST["pagina"])) {
    $_SESSION['OnToy'][1] = $_REQUEST["pagina"];
}
if (isset($_REQUEST["orden"])) {
    $_SESSION['OnToy'][2] = $_REQUEST["orden"];
}
if (isset($_REQUEST["Sort"])) {
    $_SESSION['OnToy'][3] = $_REQUEST["Sort"];
}
if (isset($_REQUEST["Ret"])) {
    $_SESSION['OnToy'][4] = $_REQUEST["Ret"];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$busca = strtolower($busca);
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$Mnu = $_SESSION["Mnu"];
$Cat = 'Clientesf';

$RetSelec = $_SESSION["OnToy"][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION["OnToy"][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

#Variables comunes;
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 37;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry["filtro"]) > 2) {
    $Dsp = 'Filtro activo';
}




$Palabras = str_word_count($busca);  //Dame el numero de palabras
if ($Palabras > 1) {
    $P = str_word_count($busca, 1);          //Metelas en un arreglo
    for ($i = 0; $i < $Palabras; $i++) {
        if (!isset($BusInt)) {
            $BusInt = " clif.nombre like '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " and clif.nombre like '%$P[$i]%' ";
        }
    }
    //$Suc='*';
} else {
    $BusInt = " clif.nombre like '%$busca%' ";
// $Suc='*';
}

#Armo el query segun los campos tomados de qrys;


if ($busca == '') {

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE id>= 0";
} elseif ($busca < 'a') {

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE id='$busca'";
} else {

    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt";
}



if ($op == "download") {

    $sql = "SELECT facturas.pdf_format, facturas.uuid FROM facturas WHERE facturas.id_fc_fk = $busca";

    error_log($sql);
    $result = mysql_query($sql);
    while ($myrowsel = mysql_fetch_array($result)) {
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename='$myrowsel[uuid].pdf'");
        echo $myrowsel[pdf_format];
        exit();
    }
} elseif ($op == "xml") {

    $sql = "SELECT facturas.cfdi_xml, facturas.uuid FROM facturas WHERE facturas.id_fc_fk = $busca";

    error_log($sql);
    $result = mysql_query($sql);
    $rg = mysql_fetch_array($result);
    $fn = $rg[cfdi_xml];
    header("Content-Disposition: attachment; filename=" . $fn . ";");
    header('Content-Type: text/xml');
    readfile($fn);
    while ($myrowsel = mysql_fetch_array($result)) {
        header("Content-Type: application/xml");
        header("Content-Disposition: attachment; filename='$myrowsel[uuid].xml'");
        echo $myrowsel[cfdi_xml];
        exit();
    }
}

//echo $cSql;

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("P/Facturar", "-", "-", "Edita", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array();    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Clientes para facturar ::..</title>
            <?php require ("./config_add.php"); ?>            
    </head>
    <script language="JavaScript1.2">
        $(document).ready(function () {
            var CerrarOrden = 0;
            $(".seleeccionar").click(function (e) {
                if (CerrarOrden > 0) {
                    e.preventDefault();
                    window.alert("Tu petición ha sido enviada, por favor esperé ... ");
                    return false;
                }
                CerrarOrden = 1;
                return true;
            });
        });
        $(document).ready(function () {
            var AbrirTransf = 0;
            $(".seleeccionar").click(function (e) {
                if (AbrirTransf > 0) {
                    e.preventDefault();
                    window.alert("Tu petición ha sido enviada, por favor esperé ... ");
                    return false;
                }
                AbrirTransf = 1;
                return true;
            });
        });
    </script>

    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <tr>
                    <td height="380" valign="top">
                        <?php
                        $res = mysql_query($cSql);
                        PonEncabezado();
                        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------


                        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;

                        $res = mysql_query($sql);

                        $Pos = strrpos($_SERVER[PHP_SELF], ".");
                        $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
                        $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

                        while ($rg = mysql_fetch_array($res)) {
                            if (($nRng % 2) > 0) {
                                $Fdo = 'FFFFFF';
                            } else {
                                $Fdo = $Gfdogrid;
                            }    //El resto de la division;
                            $rg[status] === "Cancelada" ? $Fdo = "F1948A" : $Fdo = $Fdo;
                            $Pdf = $rg[uuid] . ".pdf";
                            $Xml = $rg[uuid] . ".xml";

                            echo "<tr style='height:20px;' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                            echo "<td align='center'><a class='edit seleeccionar' href='facturas40.php?busca=$rg[id]&Cliente=$rg[id]'>Seleccionar</a></td>";
                            echo "<td class='Seleccionar' align='center'><a class='edit' href='clientesfe.php?busca=$rg[id]'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";


                            Display($aCps, $aDat, $rg);

                            echo "</tr>";
                            $nRng++;
                        }
                        ?>
                    </td>
                </tr>
        </table>
        <?php
        PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    
        CuadroInferior4($busca);
        ?>
    </body>
</html>
<?php
mysql_close();
?>
