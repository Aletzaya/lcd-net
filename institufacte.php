<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fecha = date("Y-m-d H:m:s");
#Variables comunes;
$msj = $_REQUEST[msj];
$Titulo = "Ordenes de estudio";
$Msj = $_REQUEST[Msj];
$StatusMensaje = $_REQUEST[Status];

require_once './Services/InstitufactServices.php';

$Id = 60;             //Numero de query dentro de la base de datos
#Tomo los datos principales campos a editar, tablas y filtros;
$QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
$Qry = mysql_fetch_array($QryA);

if (strlen($Qry[filtro]) > 2) {
    $Dsp = 'Filtro activo';
}

if (is_string($_REQUEST["Filtro"]) && $_REQUEST["Filtro"] != "*" && $_REQUEST["Filtro"] != "") {
    $Filtro = " AND tpago = '" . $_REQUEST["Filtro"] . "' ";
}
if (is_string($_REQUEST["Filtro1"]) && $_REQUEST["Filtro1"] != "*" && $_REQUEST["Filtro1"] != "") {
    $Filtro1 = " AND status = '" . $_REQUEST["Filtro1"] . "' ";
}
$Palabras = str_word_count($busca);  //Dame el numero de palabras
#Armo el query segun los campos tomados de qrys;
$cSql = "SELECT $Qry[campos] FROM instfact WHERE inst='$busca' " . $Filtro . $Filtro1;
$aCps = SPLIT(",", $Qry["campos"]);    // Es necesario para hacer el order by  desde lib;
$aIzq = array(" ", "-", "-");    //Arreglo donde se meten los encabezados; Izquierdos
$aDat = SPLIT(",", $Qry["edi"]);     //Arreglo donde llena el grid de datos
$aDer = array("Img", "-", "-");    //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry["tampag"];
$Obsr = $_REQUEST["Msj"];
require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Medicos - Info. Personal</title>
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
                    <?php $_REQUEST["Filtro"] === "*" ? $Resultado = "Todos" : $Resultado = $_REQUEST["Filtro"]; ?>
                    Busqueda a <?= strtolower($Resultado) ?>
                </td>
                <td align="right">
                    <?php $_REQUEST["Filtro"] === "*" ? $nombre = "Todos" : $nombre = $_REQUEST["Filtro"]; ?>
                    <form name='form' method='post' action='institufacte.php'>
                        Tipo de pago :
                        <select size='1' name='Filtro' class='letrap'>
                            <option value='*'>Todos*</option>
                            <option value='Efectivo'> Efectivo </option>
                            <option value='Tarjeta'> Tarjeta </option>
                            <option value='Cheque'> Cheque </option>
                            <option value='Transferencia'> Transferencia </option>
                            <option selected value='<?= $_REQUEST["Filtro"] ?>'><?= $nombre ?></option>
                        </select>
                        <?php $_REQUEST["Filtro1"] === "*" ? $nombre = "Todos" : $nombre = $_REQUEST["Filtro1"]; ?>
                        Status : 
                        <select size='1' name='Filtro1' class='letrap'>
                            <option value='*'>Todos*</option>
                            <option value='Pendiente'> Pendiente </option>
                            <option value='Pagada'> Pagada </option>
                            <option value='Cancelada'> Cancelada </option>
                            <option selected value='<?= $_REQUEST["Filtro1"] ?>'><?= $nombre ?> </option>     
                        </select>
                        <input type="hidden" name="busca" value="<?= $_REQUEST['busca'] ?>"></input>
                        <input type="submit" name="Boton" value="Filtra" class="letrap"></input>
                    </form>
                </td>
            </tr>
        </table>
        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Detalle de la institucion no. <?= $busca ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='95%'>
                    <table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td height="380" valign="top">
                                <?php
                                PonEncabezado();

                                $res = mysql_query($cSql);

                                CalculaPaginas();        #--------------------Calcual No.paginas-------------------------


                                $sql = $cSql . " LIMIT " . $limitInf . "," . $tamPag;


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
                                    ?>


                                    <td class='Seleccionar' align='center'><a class='edit' href='institufacte.php?busca=<?= $busca ?>&pago=<?= $rg[id] ?>'><i class='fa fa-pencil fa-2x' aria-hidden='true'></i></a></td>

                                    <?php Display($aCps, $aDat, $rg); ?>

                                    <td class='Seleccionar' align='center'><a class='edit' href=javascript:winmed('displayfactinst.php?busca=<?= $rg[id] ?>');><i class='fa fa-download fa-2x' aria-hidden='true'></i></a></td>

                                    <?php
                                    echo "</tr >";
                                    $nRng++;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <?php
                    PonPaginacion(false);           #-------------------pon los No.de paginas-------------------    
                    $CpoA = mysql_query("SELECT * FROM instfact WHERE id = $_REQUEST[pago]");
                    $rs = mysql_fetch_array($CpoA);
                    ?>
                    <br></br>
                    <form  name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValCampos();'>
                        <table bgcolor="#fff" width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr height='20px'>
                                <td valign="middle" align="center" height="45px" class="letrap" valign="middle" width="20%">
                                    Fecha : <input type="text" name="Fecha" value="<?= $rs[fecha] ?>" class="letrap"></input>
                                </td>
                                <td width="27%">
                                    <input type='radio' name='Factrem' value='Factura' required checked></input> #Factura  
                                    <input type='radio' name='Factrem' value='Remision'></input> #Remision 
                                    <input type="text"  name="Documento" value="<?= $rs[documento] ?>" class="letrap" placeholder='Factura o Remision'></input>
                                </td>
                                <td width="12%">
                                    <input type='radio' name='C_iva' value="1" checked></input>  Iva : <?= number_format($rs[iva], "2") ?> 
                                </td>
                                <td width="12%">Total :<?= number_format($rs[total], "2") ?> </td>
                                <td width="20%">
                                    Tipo de pago : 
                                    <select size='1' name='Tpago' class='letrap' required>
                                        <option value=''> Seleccione una opción</option>
                                        <option value='Efectivo'> Efectivo </option>
                                        <option value='Tarjeta'> Tarjeta </option>
                                        <option value='Cheque'> Cheque </option>
                                        <option value='Transferencia'> Transferencia </option>
                                        <option selected value='<?= $rs[tpago] ?>'><?= $rs[tpago] ?></option>     
                                    </select>
                                </td>

                            </tr>
                            <tr height='25px'>
                                <td align="center">
                                    Status :
                                    <select size='1' name='Status' class='letrap'>
                                        <option value='Pendiente'> Pendiente </option>
                                        <option value='Pagada'> Pagada </option>
                                        <option value='Cancelada'> Cancelada </option>
                                        <option selected value='<?= $rs[status] ?>'><?= $rs[status] ?></option>";     
                                    </select>
                                </td>
                                <td>Documento de pago: <input type="text" class="letrap" name="Doctopago" value="<?= $rs[doctopago] ?>"></input></td>
                                <td colspan="3">Observaciones: <input type="text" name="Observaciones" value="<?= $rs[observaciones] ?>" class="letrap" size='80' ></input></td>
                            </tr>
                            <tr height='25px'>
                                <td colspan="5" align="center">
                                    <?php
                                    if ($_REQUEST["pago"] > 0) {
                                        $Tt = "Actualiza";
                                    } else {
                                        $Tt = "Nuevo";
                                    }
                                    ?>
                                    <input class="letrap" type="submit" name="Boton" value="<?= $Tt ?>"></input>
                                    <input type='hidden' name='buscador' value="<?= $_REQUEST[pago] ?>"></input>
                                    <input type='hidden' name='busca' value="<?= $_REQUEST[busca] ?>"></input>
                                    <input type='hidden' name='pago' value="<?= $_REQUEST[pago] ?>"></input>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <table width="95%">
                        <tr>
                            <td align="right">
                                <a href="institufact.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>      
        </table>
    </body>
    <script src="./controladores.js"></script>
</html>    
<?php

function Totaliza($busca) { //busca es idnvo de medt y cVarVal es id de la entrada;
    $CiaA = mysql_query("SELECT iva FROM cia LIMIT 1");
    $Cia = mysql_fetch_array($CiaA);

    $DddA = mysql_query("SELECT 
              round(sum(importe),2) as ImporteTotal,   
              round(sum(precio),2) as PrecioSinIva,
              sum(cantidad) as cantidad 
              FROM fcd WHERE id='$busca' and cantidad > 0");

    $Ddd = mysql_fetch_array($DddA);

    if ($Ddd[0] == 0) {
        $Cnt = 0;
        $Importe = 0;
        $Iva = 0;
        $Total = 0;
    } else {
        $Cnt = $Ddd[cantidad];
        $Importe = $Ddd[PrecioSinIva];
        $Iva = round($Ddd[PrecioSinIva] * ($Cia[iva] / 100), 2);
        $Total = $Ddd[ImporteTotal];
    }

    //$nImporte = $Total - ($Iva + $Ieps);	//Con esto lo obligo a que me cuadre;
    $lUp = mysql_query("UPDATE fc SET cantidad=$Cnt,importe = $Importe, iva=$Iva, total= $Total WHERE id=$busca");
}
mysql_close();
?>
