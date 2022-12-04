<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST["busca"])) {

    if ($_REQUEST["busca"] == "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $fechaFinaliza = date("Y-m-d");
        $fechaInicia = date("Y-m-d", strtotime($fecha_actual . "- 4 month"));

        $_SESSION["OnToy"] = array('', '', 'fc.id', 'Asc', $Retornar, "", "Todos", $fechaInicia, $fechaFinaliza);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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

if (isset($_REQUEST["OrigenF"])) {
    $_SESSION['OnToy'][6] = $_REQUEST["OrigenF"];
}
if (isset($_REQUEST["FechaI"])) {
    $_SESSION['OnToy'][7] = $_REQUEST["FechaI"];
}
if (isset($_REQUEST["FechaF"])) {
    $_SESSION['OnToy'][8] = $_REQUEST["FechaF"];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$Rfc = $_SESSION["OnToy"][6];
$FechaI = $_SESSION["OnToy"][7];
$FechaF = $_SESSION["OnToy"][8];
$Mnu = $_SESSION["Mnu"];

$RetSelec = $_SESSION["OnToy"][4];                                     //Pagina a la que regresa con parametros        
$Retornar = "<a href=" . $_SESSION["OnToy"][4] . "><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];

$Cat = 'Fac';
#Variables comunes;
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 36;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry["filtro"]) > 2) {
    $Dsp = 'Filtro activo';
}


#Deshago la busqueda por palabras(una busqueda inteligte;

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
if (isset($Rfc)) {

    if ($Rfc == "Diagnostico") {
        $FiltroRFC = " facturas.cfdi_xml like '%DCD160521680%' ";
        $uso = " AND ";
    } else if ($Rfc == "lcd") {
        $FiltroRFC = " facturas.cfdi_xml like '%LCD960909TW5%' ";
        $uso = " AND ";
    } else if ($Rfc == "Todos") {
        $uso = "";
    }
}


if ($busca == '') {

    $cSql = "SELECT $Qry[campos],fc.uuid,fc.status,fc.id, clif.nombre, facturas.emisor FROM $Qry[froms] "
            . "LEFT JOIN clif ON fc.cliente=clif.id "
            . "LEFT JOIN facturas ON fc.id = facturas.id_fc_fk "
            . "WHERE date(fc.fecha) >= date('$FechaI') "
            . "AND date(fecha) <= date('$FechaF') "
            . "$uso $FiltroRFC";

//    $cSql = "SELECT $Qry[campos] FROM $Qry[froms] LEFT JOIN med ON pgs.medico=med.medico WHERE idpgs>= 0"; 
} elseif ($busca < 'a') {

    $cSql = "SELECT $Qry[campos],fc.uuid,fc.status,fc.id, clif.nombre, facturas.emisor FROM $Qry[froms] "
            . "LEFT JOIN clif ON fc.cliente=clif.id "
            . "LEFT JOIN facturas ON fc.id = facturas.id_fc_fk "
            . "WHERE date(fc.fecha) >= date('$FechaI') "
            . "AND date(fecha) <= date('$FechaF') AND "
            . "fc.id='$busca' $uso $FiltroRFC";

    //   $cSql = "SELECT $Qry[campos] FROM $Qry[froms] LEFT JOIN med ON pgs.medico=med.medico WHERE idpgs='$busca'";
} else {

    $cSql = "SELECT $Qry[campos],fc.uuid,fc.status,fc.id, clif.nombre, facturas.emisor FROM $Qry[froms] "
            . "LEFT JOIN clif ON fc.cliente=clif.id "
            . "LEFT JOIN facturas ON fc.id = facturas.id_fc_fk "
            . "WHERE date(fc.fecha) >= date('$FechaI') "
            . "AND date(fecha) <= date('$FechaF') AND "
            . " $BusInt $uso $FiltroRFC";

    //   $cSql = "SELECT $Qry[campos] FROM $Qry[froms] LEFT JOIN med ON pgs.medico=med.medico WHERE $BusInt";
}
//echo $cSql;
#Armo el query segun los campos tomados de qrys;
//$cSql = "SELECT $Qry[campos],fc.uuid,fc.status,fc.id, clif.formadepago as pago FROM $Qry[froms] LEFT JOIN clif ON fc.cliente=clif.id WHERE $BusInt";

if ($_REQUEST["Cliente"] <> '') {

    $Folio = cZeros(IncrementaFolio('fcfolio', $Suc), 5);

    $Fecha = date("Y-m-d H:i:s");
    $Sql = "INSERT INTO fc (cliente,fecha,status,folio,suc,usr,usocfdi) 
                    VALUES ('$_REQUEST[Cliente]','$Fecha','Abierta','$Folio','$Gcia','$Gusr','G03')";

    $lUp = mysql_query($Sql);

    $busca = mysql_insert_id();

    $_SESSION['cVarVal'] = $busca;

    $_SESSION['cVar'] = $busca;  //LO guardo para que cuando se genere la factura y sea la misma le pnga status = cerrada
    header("Location: facturase.php?busca=$busca");
}

if ($op == "download") {
    header("Content-disposition: attachment; filename=" . $_REQUEST["Archivo"] . ".pdf");
    header("Content-type: MIME");
    readfile("/home/omicrom/xml/" . $_REQUEST["Archivo"] . ".pdf");
    echo $myrowsel["pdf_format"];
    exit();
} elseif ($op == "xml") {

    $sql = "SELECT cfdi_xml, uuid FROM facturas WHERE id_fc_fk = $busca";
    $result = mysql_query($sql);
    $rg = mysql_fetch_array($result);
    $xml = $rg["cfdi_xml"];
    error_log("Enviando XML");
    header("Content-Type: application/xml");
    header("Content-Disposition: attachment; filename=" . $rg["uuid"] . ".xml");
    echo $xml;
    exit();
}

//echo $cSql;

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array("Envio", "-", "-", "Det", "-", "-", "Pdf", "-", "-", "Xml", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array("Forma de pago", "-", "-", "Emisor", "-", "-", " ", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Facturas ::..</title>
        <link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="jquery-ui/jquery-ui.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"></link>
    </head>


    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>

        <!--<label for="pacientej" class="letrap">Paciente :</label>
        <input style="width: 250px;" name="PacienteJ" type="text" id="pacientej" placeholder="Nombre del paciente" class="letrap"$
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#pacientej').autocomplete({
                    console.log("HOLA :)");
                    source: function (request, response) {
                        $.ajax({
                            url: "autocomplete.php",
                            datoType: "json",
                            data: {q: request.term, k: "pst"},
                            success: function (data) {
                                response(JSON.parse(data));
                                console.log(JSON.parse(data));
                            }
                        });
                    }
                });
            });
        </script>
        <input type="submit" name="Nuevo" value="Nuevo"></input> -->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#OrigenF").val("<?= $Rfc ?>");
                $("#FechaI").val("<?= $FechaI ?>");
                $("#FechaF").val("<?= $FechaF ?>");
            });
        </script>
        <form name='form0' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table width="100%" class="letrap">
                <tr>
                    <td width="40%">
                        <a href="clientesf.php?busca=ini" class="NvaFact"> Nueva Factura <i style="color:#0071c5" class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </td>
                    <td height="60%"  align="right">
                        Fecha Inicial : <input style="width: 90px;" type="text" name="FechaI" id="FechaI" class="letrap"></input>
                        Fecha Final : <input style="width: 90px;" type="text" name="FechaF" id="FechaF" class="letrap"></input>
                        Facturas: 
                        <select id="OrigenF" name="OrigenF" class="letrap">
                            <option value="lcd">LCD</option>
                            <option value="Diagnostico">Diagnostico</option>
                            <option value="Todos">Todos</option>
                        </select>
                        <input type="submit" class="letrap" name="Buscar" value="Buscar"></input>
                    </td>
                </tr>
            </table>
        </form>

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
                            $rg[status] === "Abierta" || $rg[status] === "Cancelada" ? $Color = "style='color:red'" : $Color = "";
                            $Pdf = $rg[uuid] . ".pdf";
                            $Xml = $rg[uuid] . ".xml";

                            echo "<tr style='height:20px;' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                            echo "<td align='center'><a class='edit' href=javascript:wingral('$uLink?busca=$rg[id]')><i class='fa fa-envelope fa-lg' $Color aria-hidden='true'></i></a></td>";
                            echo "<td align='center'><a class='edit' href='$cLink?busca=$rg[id]'><i class='fa fa-address-card fa-lg' aria-hidden='true'></i></a></td>";

                            if ($rg[status] == 'Timbrada') {
                                echo "<td align='center'><a class='edit' href=javascript:winuni('?busca=$rg[id]&op=download')><i style='color:red' class='fa fa-file-pdf-o fa-lg' aria-hidden='true'></i></a></td>";
                                echo "<td align='center'><a class='edit' href='?busca=$rg[id]&op=xml'><i class='fa fa-file-code-o fa-lg' aria-hidden='true'></i></a></td>";
                            } else {
                                echo "<td align='center' class='edit'><i style='color:red' class='fa fa-times' aria-hidden='true'></i></td>";
                                echo "<td align='center' class='edit'><i style='color:red' class='fa fa-times' aria-hidden='true'></i> </td>";
                            }

                            Display($aCps, $aDat, $rg);

                            $fpA = "SELECT descripcion FROM cfdi33_c_fpago WHERE clave = $rg[pago]";

                            $fp = mysql_query($fpA);
                            $Formpag = mysql_fetch_array($fp);
                            $Formp = $Formpag[descripcion];

                            echo "<td align='left' class='letrap'>$Formp</td>";

                            if ($rg[emisor] == 'LCD960909TW5') {
                                echo "<td align='center' class='letrap'>LCD</td>";
                            } elseif ($rg[emisor] == 'DCD160521680') {
                                echo "<td align='center' class='letrap'>DCD</td>";
                            } else {
                                echo "<td></td>";
                            }

                            if ($rg[status] <> 'Cancelada') {
                                echo "<td align='left'><a class='edit' href='canfactura.php?busca=$rg[id]'>$rg[status]</a></td>";
                            } else {
                                echo "<td align='left'><a class='edit'>$rg[status]</a></td>";
                            }
                            echo "</tr>";
                            $nRng++;
                        }
                        ?>
                    </td>
                </tr>
        </table>
        <?php
        PonPaginacion(false);           #-------------------pon los No.de paginas-------------------    
        CuadroInferior4($busca);
        ?>
    </body>
</html>
<?php
mysql_close();
?>
