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

        $_SESSION["OnToy"] = array('', '', 'invl.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
$regresa = $_REQUEST["regresa"];


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
$Titulo = "Estudios";
$op = $_REQUEST["op"];
$Msj = $_REQUEST["Msj"];
$Id = 69;             //Numero de query dentro de la base de datos
//
//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";
//$Retornar  = "[ <a href='gamasdd.php?orden=cap.clave'>Regresar</a> ] ";
#Intruccion a realizar si es que mandan algun proceso
if ($op == 'Si') {                    //Elimina rg
    $ExiA = mysql_query("SELECT idestudio FROM otd WHERE idestudio='$_REQUEST[cId]' limit 1");
    $Exi = mysql_fetch_array($ExiA);

    if ($Exi[idestudio] <> "") {
        $Msj = "No es posible eliminar el estudio, ya que existen ot's que hacen referencia a este estudio";
    } else {
        $lUp = mysql_query("DELETE FROM est WHERE id='$_REQUEST[cId]' limit 1");
        $Msj = "Registro eliminado";
    }
}

#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 10) {
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

$cSql = "SELECT $Qry[campos],invl.id FROM $Qry[froms] WHERE $BusInt $Qry[filtro] and invl.status='Activo'";

//echo $cSql;

$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;

$aIzq = array(" ", "-", "-");      //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array("", "", "", "", "", "");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Catalogo de Estudios</title>
        <link href="estilos.css" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.0" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>

    <body topmargin="1">

        <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">'
            <tr>
                <td height="380" valign="top">
                    <?php
                    PonEncabezado();

                    $res = mysql_query($cSql);

                    CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

                    if ($_REQUEST[bsca] <> '') {
                        $sql = $cSql . " AND clave LIKE ('%$_REQUEST[bsca]%') OR descripcion LIKE ('%$_REQUEST[bsca]%') ";
                    } else {
                        $sql = $cSql . " ORDER BY " . $orden . " $Sort LIMIT " . $limitInf . "," . $tamPag;
                    }

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

                        echo "<td class='Seleccionar' align='center'><a class='edit' href='transfere.php?busca=$_REQUEST[regresa]&Regresa=$rg[clave]'><i class='fa fa-reply-all fa-2x' aria-hidden='true'></i></a></td>";

                        Display($aCps, $aDat, $rg);

                        echo "</tr>";

                        $nRng++;
                    }
                    ?>
                </td>
            </tr>
        </table>
        <?php
        PonPaginacionDif(false);           #-------------------pon los No.de paginas-------------------    
        ?>
        <table>
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <tr><td><p class="letrap">Busca : 
                            <input type="text" name="bsca" class="letrap"></input> 
                            <input type="submit" name="Buscanvo" class="letrap"></input>
                            <input type="hidden" name="regresa" value="<?= $_REQUEST[regresa] ?>"></input>
                        </p></td>
                </tr>
            </form>
        </table>
    </body>

    <script src="./controladores.js"></script>

</html>
<?php
mysql_close();
?>

