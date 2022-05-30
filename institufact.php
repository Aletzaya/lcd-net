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
            if ($_REQUEST[Ret] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'inst.institucion', 'Asc', $Retornar, "*");   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
if (isset($_REQUEST["Filtro"])) {
    $_SESSION["OnToy"][5] = $_REQUEST["Filtro"];
}


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca = $_SESSION["OnToy"][0];
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
$Filtro = $_SESSION["OnToy"][5];
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


#Variables comunes;
$Titulo = "Medicos";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 59;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 2) {
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
    $BusInt = $OrdenDef . " LIKE '%" . $busca . "%'";
}

#Armo el query segun los campos tomados de qrys;
if ($Filtro === "*") {
    $cSql = "SELECT $Qry[campos] FROM inst WHERE $BusInt $Qry[filtro]";
} else {
    $cSql = "SELECT $Qry[campos] FROM inst WHERE condiciones = '$Filtro' AND  $BusInt $Qry[filtro]";
}
//echo $Filtro;
//echo $cSql;

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array();    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];
$Obsr = $_REQUEST["Msj"];
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Fac.Insti ::..</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <table width="100%" class="letrap">
            <tr>
                <td>
                    <?php $Filtro === "*" ? $Resultado = "Todos" : $Resultado = $Filtro; ?>
                    Busqueda a <?= strtolower($Resultado) ?>
                </td>
                <td align="right">
                    <form name='form' method='post' action='institufact.php'>
                        Condiciones :
                        <select id="Filtro" name='Filtro' class='letrap'>
                            <option value='*'>Todos*</option>
                            <option value='CONTADO'>Contado</option>
                            <option value='CREDITO'>Credito</option>
                            <option selected value="<?= $Filtro ?>"><?= $Filtro ?></option>
                        </select>
                        <input type="submit" name="Boton" value="Filtra" class="letrap"></input>
                    </form>
                </td>
            </tr>
        </table>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="380" valign="top">
                    <?php
                    PonEncabezado();

                    $res = mysql_query($cSql);

                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

                    if ($busca <> '') {
                        $sql = $cSql . " AND " . $orden . " LIKE ('%" . $busca . "%') ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    } else {
                        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    }
                    //echo $sql;

                    $res = mysql_query($sql);

                    $Pos = strrpos($_SERVER[PHP_SELF], ".");
                    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
                    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

                    while ($rg = mysql_fetch_array($res)) {

                        ($nRng % 2) > 0 ? $Fdo = 'FFFFFF' : $Fdo = $Gfdogrid;

                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                        echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[institucion]'><i class='fa fa-money fa-2x' aria-hidden='true' style='color:green;'></i></a></td>";

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

        CuadroInferior($busca);
        ?>
    </body>
    <script src="./controladores.js"></script>
</html>
<?php
mysql_close();
?>
