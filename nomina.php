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

        $_SESSION["OnToy"] = array('', '', 'nom.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
    } elseif ($_REQUEST[busca] <> '') {
        $_SESSION['OnToy'][0] = $_REQUEST[busca];
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
$RetSelec = $_SESSION["OnToy"][4];
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Id = 27;

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
    $BusInt = $OrdenDef . " LIKE '%" . $busca . "%'";
}

if ($_REQUEST["op"] === "Excel") {
    $Sql = "SELECT nomf.cuenta,emp.nombre,emp.cuenta,nomf.sueldo,emp.id,
  nomf.septimo,nomf.produccion,nomf.asistencia,nomf.impfallas,nomf.impretardos,nomf.ispt,
  nomf.otrosegr
  FROM nomf,emp 
  WHERE nomf.cuenta=emp.id AND nomf.id='$busca' AND emp.cuenta<>' '
  ORDER BY emp.departamento, emp.nombre";
    echo $Sql;
    $RgA = mysql_query($Sql);


    $salida = "";
    $salida .= "<table>";
    $salida .= "<thead> <th>ID</th> <th>Nombre</th><th>Sueldo</th></thead>";
    while ($rg = mysql_fetch_array($RgA)) {
        $salida .= "<tr> <td>" . $rg["cuenta"] . "</td> <td>" . $rg["nombre"] . "</td><td>" . $rg["sueldo"] . "</td></tr>";
    }
    $salida .= "</table>";
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=usuarios_" . time() . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $salida;
}

#Armo el query segun los campos tomados de qrys;
$cSql = "SELECT $Qry[campos],id,cia FROM $Qry[froms] WHERE $BusInt $Qry[filtro]";

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-","Faltas","","", "Id", "-", "-","Cia","","");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array(" Ingresos ", "-", "-", " Egresos ", "-", "-", " Totales ", "-", "-", " Recibos ", "-", "-", " Exp ", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Catalogo de Nomina ::..</title>
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
    $cLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'e.php';     #
    $uLink = substr($_SERVER[PHP_SELF], 0, $Pos) . 'd.php';     #

    while ($rg = mysql_fetch_array($res)) {

        if (($nRng % 2) > 0) {
            $Fdo = 'FFFFFF';
        } else {
            $Fdo = $Gfdogrid;
        }    //El resto de la division;
        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='#b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

        echo "<td class='Seleccionar' align='center'><a class='edit' href='$cLink?busca=$rg[id]'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winuni('capturafaltas.php?Nomina=$rg[nomina]&Cianvo=$rg[cia]');><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='letrap'>$rg[id]</a></td>";
        echo "<td class='Seleccionar' align='center'><a class='letrap'>$rg[cia]</a></td>";
        Display($aCps, $aDat, $rg);

        echo "<td class='Seleccionar' align='center'><a class='edit' href='nominaing.php?busca=$rg[id]&busca1=ini'><i class='fa fa-list-alt fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href='nominaegr.php?busca=$rg[id]&busca1=ini'><i class='fa fa-list-alt fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:wingral('lista2.php?busca=$rg[id]');><i class='fa fa-print fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href=javascript:winmed('pdfrecibos.php?busca=$rg[id]');><i class='fa fa-print fa-2x' aria-hidden='true'></i></a></td>";
        echo "<td class='Seleccionar' align='center'><a class='edit' href='esperar.php?busca=$rg[id]&op=Excel'><i class='fa fa-cloud-download fa-2x' aria-hidden='true'></i></a></td>";

        echo "</tr>";

        $nRng++;
    }

    echo "</table>";
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------    

    $sql = "SELECT zona,descripcion,poblacion FROM zns WHERE zona=$_REQUEST[op]";

    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($_REQUEST[op] <> '') {
        ?>
        <table>
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <td>
                    <tr>
                        <a class="Inpt"><b>Id:</b><?= $sql[zona] ?> </a>
                        <a class="Inpt"><b>Descripion: </b>
                            <input type='text' class='cinput'  name='Descripcion' value='<?= $sql[descripcion] ?>'>
                        </a>
                        <a class="Inpt"><b>Poblacion: </b>
                            <input type='number' class='cinput'  name='Poblacion' value='<?= $sql[poblacion] ?>'>
                        </a>
                        <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                        <input type="hidden" value="<?= $_REQUEST[pagina] ?>" name="pagina"></input>
                        <input type="hidden" value="<?= $_REQUEST[op] ?>" name="op"></input>
                    </tr>
                </td>
            </form>
        </table>

        <?php
    }
    echo "<table> <td><tr><a class='cMsj'>";
    echo $_REQUEST[msj];
    echo "</a></tr></td></table>";
    CuadroInferior($busca);


    echo '</body>';
    ?>
    <script src="./controladores.js"></script>

</html>
<?php
mysql_close();
?>

