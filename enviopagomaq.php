<?php
#Librerias
session_start();

//include_once ("auth.php");
//include_once ("authconfig.php");
//include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

if (isset($_REQUEST["busca"])) {

    if ($_REQUEST["busca"] === "ini") {

        $Pos = strrpos($_REQUEST["Ret"], "?"); //Buscon si en lo k se va a regresar trae ya un valor predef

        if ($Pos > 0) {
            $Retornar = $_REQUEST["Ret"] . '&';
        } else {
            if ($_REQUEST["Ret"] <> '') {
                $Retornar = $_REQUEST["Ret"] . '?';
            }
        }

        $_SESSION["OnToy"] = array('', '', 'generapago.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
$pagina = $_SESSION["OnToy"][1];
$OrdenDef = $_SESSION["OnToy"][2];
$Sort = $_SESSION["OnToy"][3];
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
$Fecha = date("Y-m-d H:m:s");
#Variables comunes;
$Titulo = "Medicos";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 58;             //Numero de query dentro de la base de datos
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
            $BusInt = " $OrdenDef LIKE  '%$P[$i]%' ";
        } else {
            $BusInt = $BusInt . " AND $OrdenDef like '%$P[$i]%' ";
        }
    }
} else {
    $BusInt = $OrdenDef . " LIKE '%" . $busca . "%'";
}

if ($_REQUEST["Op"] === "Delete") {
    $Dt = "DELETE FROM generapago WHERE id = $busca LIMIT 1;";
    if (mysql_query($Dt)) {
        $Msj = "!Registro eliminado con exito!";
        AgregaBitacoraEventos($Gusr, '/Administracion/Evios Generados Elimina registro ' . $busca, "generapago", $Fecha, "ini", $Msj, $_SERVER[PHP_SELF]);
    }
    echo $Dt . " " . mysql_error();
}

#Armo el query segun los campos tomados de qrys;
$cSql = "SELECT $Qry[campos] FROM $Qry[froms] WHERE generapago.proveedor=mql.id and generapago.cancel='' AND $BusInt $Qry[filtro]";
//echo $cSql;
$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array();    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array("RepGen", "-", "-", "RepCot", "-", "-", "Eliminar");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Envios ::..</title>
            <?php require ("./config_add.php"); ?>
    </head>

    <?php
    echo '<body topmargin="1">';
    encabezados();
    menu($Gmenu, $Gusr);

    //submenu();
//Tabla contenedor de brighs
    echo '<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tr>';
    echo '<td height="380" valign="top">';

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
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';

    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

        Display($aCps, $aDat, $rg);

        $Inst1 = "SELECT instruccion from generapago WHERE generapago.id=$rg[id]";

        $Instruct = mysql_query($Inst1);
        $Instruct1 = mysql_fetch_array($Instruct);
        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('repenvioext.php?$Instruct1[instruccion]');><i class='fa fa-list-alt fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('repenvioextadm.php?$Instruct1[instruccion]');><i class='fa fa-list-alt fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:confirmar('Seguro_de_elimiar_el_registro_$rg[id]','enviopagomaq.php?busca=$rg[id]&Op=Delete');><i class='fa fa-trash fa-2x' aria-hidden='true'></i></a></td>";

        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

    CuadroInferior($busca);


    echo '</body>';
    ?>
    <script src="./controladores.js"></script>

</html>
<?php
mysql_close();
?>
