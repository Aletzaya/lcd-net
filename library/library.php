<?php
set_time_limit(300);
setlocale(LC_ALL, "es_MX.UTF-8");
ini_set("error_log", "/var/log/apache2/error_admin.log");
define("path_output", "/tmp/response.log");
//ini_set("display_errors", "on");
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

define("PROJECT_NAME", "AdminomcV2");
define("VERSION", "2.0.2-beta"); #Fecha de modificación: 2021-03-17
define("FACTENDPOINT", "http://0.0.0.0:9190/GeneradorCFDIsWEB/Facturador?wsdl");

define("def_siderbar_color", "sidebar-dark-primary");
define("def_navbar_color", "navbar-dark");
define("def_accent_color", "accent-navy");
define("def_card_color", "card-dark");

define("DETALLE", "cVarVal");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once(dirname(__DIR__) . "/softcoatl/SoftcoatlHTTP.php");
require_once(dirname(__DIR__) . "/service/ListasCatalogo.php");
require_once(dirname(__DIR__) . "/service/Usuarios.php");
require_once(dirname(__DIR__) . "/data/BitacoraDAO.php");
require_once(dirname(__DIR__) . "/data/AlarmasDAO.php");
require_once(dirname(__DIR__) . "/data/CiaDAO.php");
require_once(dirname(__DIR__) . "/data/IslaDAO.php");
require_once(dirname(__DIR__) . "/data/CtDAO.php");
require_once(dirname(__DIR__) . "/data/VariablescorpDAO.php");
require_once(dirname(__DIR__) . "/data/BasicEnum.php");
require_once(dirname(__DIR__) . "/paginador/Paginador.php");
require_once(dirname(__DIR__) . "/paginador/PaginadorAdmin.php");
require_once(dirname(__DIR__) . "/paginador/AdminomcSession.php");
require_once(dirname(__DIR__) . "/data/poolConnection.php");
require_once(dirname(__DIR__) . "/library/ssp.class.php");

use com\softcoatl\utils as utils;

define("MAIN_TITLE", getTitulo());

/**
 * 
 * @return Session
 */
function initSessionProject() {
    return Session::getInstance(PROJECT_NAME);
}

/**
 * 
 * @global Session $sessionProject
 */
function destroySessionProject() {
    global $sessionProject;
    return $sessionProject->destroy();
}

function encodeOneToOne($input) {
    $base = "KLMNOPQRSTUVWXYZ";
    $encoded = strtoupper($input);
    $return = "";
    for ($i = 0; $i < strlen($encoded); $i++) {
        $idx = hexdec(substr($encoded, $i, 1));
        $return .= substr($base, $idx, 1);
    }
    return $return;
}

/**
 * 
 * @param string $Vlr
 * @param int $nLen
 * @param string $direction
 * @return string
 */
function cZeros($Vlr, $nLen, $direction = "LEFT") {
    for ($i = strlen($Vlr); $i < $nLen; $i = $i + 1) {
        if (stripos(strtoupper($direction), "RIGHT") !== false) {
            $Vlr = $Vlr . "0";
        } else {
            $Vlr = "0" . $Vlr;
        }
    }
    return $Vlr;
}

/**
 * 
 * @param int $status
 * @return string
 */
function statusCFDI($status) {
    $var = "";
    switch ($status) {
        case 0: $var = "Abierto";
            break;
        case 1: $var = "Timbrado";
            break;
        case 2: $var = "Cancelado";
            break;
        case 3: $var = "Cancelado S/T";
            break;
    }
    return $var;
}

function tipoVenta($char) {
    $Dsp = "Normal";
    switch ($char) {
        case "J": $Dsp = 'Jarreo';
            break;
        case "A": $Dsp = 'Auto jarreo';
            break;
        case "N": $Dsp = 'Consignacion';
            break;
        default: $Dsp = "Normal";
            break;
    }
    return $Dsp;
}

/**
 * 
 * @return IslaVO
 */
function getCorteActual() {
    $objectDAO = new IslaDAO();
    $objectVO = $objectDAO->retrieve(1, "isla");
    return $objectVO;
}

/**
 * 
 * @return CiaVO
 */
function getEstacion() {
    $objectDAO = new CiaDAO();
    $objectVO = $objectDAO->retrieve(1);
    return $objectVO;
}

function getTitulo() {
    initSessionProject();
    return "Admin Omicrom V2 | " . utils\HTTPUtils::getSessionValue("SUCURSAL");
}

function menuLateral1($RetSelec = NULL) {
    ?>

    <br/>
    <?php if (!is_null($RetSelec)) { ?>
        <p style="text-align: center;"><a class="regresar" href="<?= $RetSelec ?>">&crarr; Regresar</a></p>
        <?php
    }
}

function PieDePagina() {
    global $db;

    if ($db != null) {
        $db->disconnect();
    }
}

function nuevoEncabezado($Titulo) {
    $ciaDAO = new CiaDAO();
    $ciaVO = $ciaDAO->retrieve(1);
    ?>
    <div id="Encabezado">
        <table aria-hidden="true">
            <tr>
                <td style="width: 15%;"><img src="../dist/img/logo.png" onclick="location.reload();"  title="Recargar página." alt="Logo omicrom"></td>
                <td style="width: 70%">
                    <div class="empresa"><strong><?= $ciaVO->getCia() . " " . $ciaVO->getClavepemex() ?></strong></div>
                    <div>Estacion: <?= $ciaVO->getNumestacion() ?> Sucursal: <strong><?= $ciaVO->getEstacion() ?></strong></div>
                    <div><?= $ciaVO->getDireccion() ?>  No <?= $ciaVO->getNumeroext() ?>  <?= $ciaVO->getColonia() ?>  <?= $ciaVO->getCiudad() ?> </div>
                    <div class="titulo"><?= $Titulo ?></div>
                </td>
                <td style="width: 15%;" onclick="window.close();" class="cerrar oculto">Cerrar</td>                
            </tr>
            <tr><td colspan="3"><hr></td></tr>
        </table>  
    </div>
    <?php
}

function nuevoEncabezadoMini() {
    ?>
    <div id="Encabezado">
        <table aria-hidden="true">
            <tr>
                <td style="width: 15%; margin-left: auto;margin-right: auto;text-align: center;">
                    <img src="../dist/img/logo.png" onclick="location.reload();"  title="Recargar página." alt="Logo omicrom">
                </td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
        </table>  
    </div>
    <?php
}

/**
 * Establece el pie de página de los reportes del sistema.
 * @global type $connection
 */
function topePagina() {

    global $connection, $mysqli;

    $FechaPie = date("Y-m-d H:i:s");

    // Closes both mysqli and mysql connnections
    if ($connection != null) {
        $connection->close();
    }
    if ($mysqli != null) {
        $mysqli->close();
    }
    ?>
    <table style="width: 100%" class="texto_tablas_mini" aria-hidden="true">
        <tr>
            <td align='left'>Desarrollo y transferencia de informatica s.a. de c.v.&nbsp;&nbsp; www.detisa.com.mx</td>
            <td align='right'>Fecha de impresion: <?= $FechaPie ?></td>
        </tr>
    </table>
    <?php
}

function BordeSuperior() {

    global $Titulo, $RetSelec;
    ?>


    <div id="container">

        <div class="degradado">
            <?php menuLateral1($RetSelec); ?>
        </div>
        <div>
            <p class="subtitulos"><?= $Titulo ?></p>
            <?php
        }

        function BordeSuperiorCerrar() {
            ?>
        </div>
    </div>
    <?php
    PieDePagina();
}

function setLinks($idQuery, $add, $nLink = array(), $export = true, $reload = true) {
    $footer .= '<div class="linksDataTables"><form name="formLinks">';
    if ($add) {
        $cLink = substr(utils\HTTPUtils::self(), 0, strrpos(utils\HTTPUtils::self(), ".")) . "e.php?id=NUEVO";
        $footer .= '<a href="' . $cLink . '"><i class="icon fa fa-lg fa-plus-circle"></i> Agregar</a>';
    }
    if (is_array($nLink) && count($nLink)) {
        foreach ($nLink as $key => $value) {
            if (!empty($key) && !empty($value)) {
                $footer .= '<a href="' . $value . '">' . $key . '</a>';
            }
        }
    }
    if ($export) {
        $footer .= '<a href="#"><i class="icon fa fa-lg fa-download"></i> Exportar</a>';
    }
    if ($reload) {
        $footer .= '<a href="#" title="Actualiza la pantalla" onclick="clearDataTable();"><i class="icon fa fa-lg fa-sync"></i> Restablecer</a>';
    }
    $footer .= '<input type="hidden" name="IdQuery" id="IdQuery" value="' . $idQuery . '">';
    $footer .= '</form></div>';
    return $footer;
}

/**
 * Retornara la fecha con el siguiente formato Miércoles 18 de Noviembre de 2020
 * @return string
 */
function getFormatDate() {
    setlocale(LC_ALL, "es_MX.UTF-8");
    return ucfirst(strftime("%A %d de ")) . ucfirst(strftime("%B de %Y"));
}

function incrementaVentas($diferencia, $corte, $posicion, $manguera) {
    global $db;
    $Msj = "Id comprometido: ";


    /* Compruebo las ventas que esten en cero para comenzar por ellas, inserto todo ahi. */
    $params = ArrAY($corte, $posicion, $manguera);
    $sql = "SELECT * FROM rm WHERE corte = ? AND posicion = ? AND manguera = ? AND pesos = 0 AND tipo_venta = 'D' LIMIT 1";
    $result = $db->rawQuery($sql, $params);
    foreach ($result as $rows) {
        $volumen = truncateFloat($diferencia, 4);
        $pesos = truncateFloat($volumen * $rows[precio], 3);
        $pesosP = truncateFloat($pesos * (100 - $rows[factor]) / 100, 3);
        $volumenP = truncateFloat($pesosP / $rows[precio], 4);

        $params = Array($pesos, $volumen, $pesosP, $volumenP, $pesos, $pesos, $rows[id]);
        $query = "UPDATE rm SET pagado = pesos,pesos = ?,volumen = ?,pesosp = ?,volumenp = ?,importe = ?,enviado = 0,pagoreal = ? 
                 WHERE id = ? AND tipo_venta = 'D' LIMIT 1;";
        $db->rawQuery($query, $params);
        if ($rows[procesado] == 1) {
            $params = Array($pesos, $volumen, $rows[id]);
            $query = "UPDATE ventas SET pesos = ?,volumen = ? WHERE id = ? AND tipo_venta = 'D' ";
            $db->rawQuery($query, $params);
        }
        $sql = $query;
        $Msj .= $rows[id];
        $diferencia = 0;
    }
    /* Si lo que retorna la variable es mayor que cero es porque no encontro resultados, por lo tanto entramos 
      en el siguiente condicional que busca las ventas insertadas */
    if ($diferencia !== 0) {
        $sql = "SELECT * FROM rm WHERE corte = ? AND posicion = ? AND manguera = ?
                AND inicio_venta = fin_venta  AND tipo_venta = 'D' AND cliente = 0 AND uuid = '-----' LIMIT 1";
        $result = $db->rawQuery($sql, $params);
        foreach ($result as $rows) {
            $volumen = truncateFloat($rows[volumen] + $diferencia, 4);
            $pesos = truncateFloat($volumen * $rows[precio], 3);
            $pesosP = truncateFloat($pesos * (100 - $rows[factor]) / 100, 3);
            $volumenP = truncateFloat($pesosP / $rows[precio], 4);

            $params = Array($pesos, $volumen, $pesosP, $volumenP, $pesos, $pesos, $rows[id]);
            $query = "UPDATE rm SET pagado = pesos,pesos = ?,volumen = ?,pesosp = ?,volumenp = ?,importe = ?,enviado = 0,pagoreal = ? 
                 WHERE id = ? AND tipo_venta = 'D' LIMIT 1;";
            $db->rawQuery($query, $params);
            if ($rows[procesado] == 1) {
                $params = Array($pesos, $volumen, $rows[id]);
                $query = "UPDATE ventas SET pesos = ?,volumen = ? WHERE id = ? AND tipo_venta = 'D' ";
                $db->rawQuery($query, $params);
            }
            $sql = $query;
            $Msj .= $rows[id];
            $diferencia = 0;
        }
    }
    /* Si no se deja, compruebo en la venta mas pequeña para aumentarla */
    if ($diferencia !== 0) {
        $sql = "SELECT * FROM rm WHERE corte = ? AND posicion = ? AND manguera = ? 
                AND cliente = 0 AND tipo_venta = 'D' AND cliente = 0 AND uuid = '-----' 
                ORDER BY volumen ASC LIMIT 1";
        $result = $db->rawQuery($sql, $params);
        foreach ($result as $rows) {
            $volumen = truncateFloat($rows[volumen] + $diferencia, 4);
            $pesos = truncateFloat($volumen * $rows[precio], 3);
            $pesosP = truncateFloat($pesos * (100 - $rows[factor]) / 100, 3);
            $volumenP = truncateFloat($pesosP / $rows[precio], 4);

            $params = Array($pesos, $volumen, $pesosP, $volumenP, $pesos, $pesos, $rows[id]);
            $query = "UPDATE rm SET pagado = pesos,pesos = ?,volumen = ?,pesosp = ?,volumenp = ?,importe = ?,enviado = 0,pagoreal = ? 
                 WHERE id = ? AND tipo_venta = 'D' LIMIT 1;";
            $db->rawQuery($query, $params);
            if ($rows[procesado] == 1) {
                $params = Array($pesos, $volumen, $rows[id]);
                $query = "UPDATE ventas SET pesos = ?,volumen = ? WHERE id = ? AND tipo_venta = 'D' ";
                $db->rawQuery($query, $params);
            }
            $sql = $query;
            $Msj .= $rows[id];
            $diferencia = 0;
        }
    }

    if ($diferencia == 0) {
        return "Se aumento la venta, " . $Msj;
    } else {
        return "No se encontraron ventas para hacer el ajuste.";
    }
}

function reduceVentas($diferencia, $corte, $posicion, $manguera) {
    global $db;

    $Msj = "Id´s comprometidos: ";

    /* Compruebo las ventas insertadas */
    $params = ArrAY($corte, $posicion, $manguera);
    $sql = "SELECT * FROM rm WHERE corte = ? AND posicion = ? AND manguera = ?
            AND inicio_venta = fin_venta AND tipo_venta = 'D' AND comprobante = 0 AND cliente = 0 AND uuid = '-----'";
    $result = $db->rawQuery($sql, $params);
    $array = procesoUpdateR($result, $diferencia);

    $diferencia = $array['diferencia'];
    $Msj .= $array['Msj'];
    /* Compruebo las ventas que no tienen comprobante y que no esten en cero */
    if ($diferencia !== 0) {
        $sql = "SELECT * FROM rm WHERE corte = ? AND posicion = ? AND manguera = ? 
                AND comprobante=0 AND pesos<>0 AND tipo_venta='D'   AND cliente = 0 AND uuid = '-----'
                ORDER BY volumen ASC";
        $result = $db->rawQuery($sql, $params);
        $array = procesoUpdateR($result, $diferencia);
        $diferencia = $array['diferencia'];
        $Msj .= $array['Msj'];
    }
    /* Si lo que retorna la variable es mayor que cero es porque no encontro resultados, por lo tanto entramos 
      en el siguiente condicional */
    if ($diferencia !== 0) {
        $sql = "SELECT * FROM rm WHERE corte = ? AND posicion = ? AND manguera = ? 
                AND tipo_venta='D'  AND cliente = 0 AND uuid = '-----'
                ORDER BY volumen,comprobante ASC";
        $result = $db->rawQuery($sql, $params);
        $array = procesoUpdateR($result, $diferencia);
        $diferencia = $array['diferencia'];
        $Msj .= $array['Msj'];
    }
    //echo $sql;
    if ($diferencia == 0) {
        return "Se completo el decremento, " . $Msj;
    } else {
        return "No se pudo completar el decremento, " . $Msj;
    }
}

/**
 * 
 * @param type $result "Es el resultado de un query"
 * @param type $diferencia "Es la cantidad que deseamos reducir"
 * @return Array
 */
function procesoUpdateR($result, $diferencia) {
    global $db;

    $Msj = "";

    foreach ($result as $rows) {
        /* Siempre que las ventas encontradas sean menores a la diferencia entrara aqui */

        if ($rows[volumen] < $diferencia) {
            $diferencia -= $rows[volumen];

            $param = Array($rows[id]);
            $sql = "UPDATE rm SET pagado = pesos,pesos = 0,volumen = 0,pesosp = 0,volumenp = 0,importe = 0,enviado = 0,pagoreal = 0 
                    WHERE id = ? AND tipo_venta = 'D'  AND cliente = 0 AND uuid = '-----' LIMIT 1;";
            $db->rawQuery($sql, $param);

            if ($rows[procesado] == 1) {
                $sql = "UPDATE ventas SET pesos = 0,volumen = 0 WHERE id = ? AND tipo_venta = 'D' LIMIT 1;";
                $db->rawQuery($sql, $param);
            }
        } else {/* Si la cantidad ya es muy pequeña se puede concentrar en un solo registro. */
            $volumen = truncateFloat($rows[volumen] - $diferencia, 4);
            $pesos = truncateFloat($volumen * $rows[precio], 3);
            $pesosP = truncateFloat($pesos * (100 - $rows[factor]) / 100, 3);
            $volumenP = truncateFloat($pesosP / $rows[precio], 4);

            $params = Array($pesos, $volumen, $pesosP, $volumenP, $pesos, $pesos, $rows[id]);
            $sql = "UPDATE rm SET pagado = pesos,pesos = ?,volumen = ?,pesosp = ?,volumenp = ?,importe = ?,enviado = 0,pagoreal = ? 
                    WHERE id = ? AND tipo_venta = 'D'  AND cliente = 0 AND uuid = '-----' LIMIT 1;";
            $db->rawQuery($sql, $params);

            if ($rows[procesado] == 1) {
                $params = Array($pesos, $volumen, $rows[id]);
                $sql = "UPDATE ventas SET pesos = ?,volumen = ? WHERE id = ? AND tipo_venta='D' LIMIT 1;";
                $db->rawQuery($sql, $params);
            }
            $diferencia = 0;
        }
        $Msj .= $rows[id] . ",<br/>";
    }

    $respuesta = Array("diferencia" => $diferencia, "Msj" => $Msj);
    return $respuesta;
}

function validaId($id, $posicion) {
    global $db;

    $var = null;
    $params = Array($id, $posicion);
    $sql = "SELECT * FROM ctd WHERE id = ? AND posicion = ?";
    $num_rows = $db->rawquery($sql, $params);
    if (count($num_rows) > 0) {
        $var = $id;
    }
    error_log("var: " . $var);
    return $var;
}

function truncateFloat($number, $digitos) {
    $raiz = 10;
    $multiplicador = pow($raiz, $digitos);
    $resultado = ((int) ($number * $multiplicador)) / $multiplicador;
    return number_format($resultado, $digitos, ".", "");
}

/**
 * Compara una cadena con posibles valores de un array, devolverá TRUE si encuentra alguno de ellos en la cadena.
 * 
 * @param string $string
 * @param array $arrayValues
 * @return boolean
 */
function separateStringToArray($string, $arrayValues = array()) {
    foreach ($arrayValues as $value) {
        if (strpos(strtoupper($string), strtoupper($value)) !== FALSE) {
            return true;
        }
    }
    return false;
}

/**
 * Compara una cadena con posibles valores de un array, devolverá TRUE si encuentra alguno de ellos en la cadena.
 * 
 * @param string $string
 * @param array $arrayValues
 * @return boolean
 */
function stringToArrayCompare($string, $arrayValues = array()) {
    $exists = false;
    $arrayString = array_unique(explode(" ", strtoupper($string)));
    //error_log(print_r($arrayString, TRUE));
    foreach ($arrayString as $value) {
        if (in_array($value, $arrayValues)) {
            $exists = true;
            break;
        }
    }
    return $exists;
}

/**
 * 
 * @param string $host
 * @return array()
 */
function ping($host) {
    $res = $rval = "";
    exec(sprintf("ping -c 1 -W 5 %s", escapeshellarg($host)), $res, $rval);
    //error_log(print_r($res, true));
    return $res;
}

function print_array($title, $array) {

    if (is_array($array)) {

        echo $title . "<br/>" .
        "<pre>";
        print_r($array);
        echo "</pre>" .
        "END " . $title . "<br/>" ;
    } else {
        echo $title . " is not an array.";
    }
}
