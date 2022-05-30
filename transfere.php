<?php
session_start();

require("lib/lib.php");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

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

        $_SESSION["OnToy"] = array('', '', 'trans.id', 'Asc', $Retornar);   //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)
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
$busca = $_REQUEST["busca"];

if ($_REQUEST["Boton"] == "Aceptar") {        //Para agregar uno nuevo
    if ($busca == "NUEVO") {
        $Sql = "INSERT INTO trans (fecha,hora,origen,destino,usrorin,usrdest,status)  VALUES  ('$_REQUEST[Fecha]','$_REQUEST[Hora]','$_REQUEST[Origen]','$_REQUEST[Destino]', '$Gusr','$_REQUEST[Usrdest]','ABIERTA')";
        $lUp = mysql_query($Sql);
        $Id = mysql_insert_id();
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Admin/Transferencias/Datos Principales Creacion','trans',now(),$Id);";
        if (mysql_query($Sql)) {
            header("Location: transfere.php?busca=$Id&Msj=Registro actualizado con exito!");
        }
    } else {
        $lUp = mysql_query("UPDATE trans SET origen='$_REQUEST[Origen]',destino='$_REQUEST[Destino]',usrdest='$_REQUEST[Usrdest]',status='$_REQUEST[Status]' WHERE id='$busca' limit 1");
        $lBd = true;
        $cSql = "SELECT * FROM trans WHERE id='$busca'";
        $CpoA = mysql_query($cSql);
        $Cpo = mysql_fetch_array($CpoA);
        if ($_REQUEST["Status"] == 'CERRADA') {
            $ProdB = mysql_query("SELECT * FROM trans WHERE id='$busca'");
            $Prodb = mysql_fetch_array($ProdB);
            $ProdA = mysql_query("SELECT * FROM transd WHERE id='$busca'");
            while ($Prod = mysql_fetch_array($ProdA)) {
                $InvA = mysql_query("SELECT costo FROM invl WHERE clave='$Prod[clave]'");
                $Inv = mysql_fetch_array($InvA);
                $Up = mysql_query("UPDATE invl SET $Prodb[origen] = $Prodb[origen] - $Prod[cantidad],costo = '$Inv[costo]',$Prodb[destino] = $Prodb[destino] + $Prod[cantidad] WHERE clave='$Prod[clave]' LIMIT 1");
            }
            header("Location: transfer.php?busca=ini");
        } elseif ($Cpo[status] <> 'ABIERTA' and $_REQUEST[Status] == 'ABIERTA') {
            $ProdB = mysql_query("SELECT * FROM trans WHERE id='$busca'");
            $Prodb = mysql_fetch_array($ProdB);
            $ProdA = mysql_query("SELECT * FROM transd WHERE id='$busca'");
            while ($Prod = mysql_fetch_array($ProdA)) {
                $Up = mysql_query("UPDATE invl SET $Prodb[origen] = $Prodb[origen] + $Prod[cantidad],$Prodb[destino] = $Prodb[destino] - $Prod[cantidad] WHERE clave='$Prod[clave]' LIMIT 1");
            }
            header("Location: transfer.php?busca=ini");
        }
    }
}elseif ($_REQUEST["Boton"] == "Abrir") {        //Para agregar uno nuevo

    $lUp = mysql_query("UPDATE trans SET status='ABIERTA' WHERE id='$busca' limit 1");
    $lBd = true;

    $ProdB = mysql_query("SELECT * FROM trans WHERE id='$busca'");
    $Prodb = mysql_fetch_array($ProdB);
    $ProdA = mysql_query("SELECT * FROM transd WHERE id='$busca'");

    while ($Prod = mysql_fetch_array($ProdA)) {
        $Up = mysql_query("UPDATE invl SET $Prodb[origen] = $Prodb[origen] + $Prod[cantidad],$Prodb[destino] = $Prodb[destino] - $Prod[cantidad] WHERE clave='$Prod[clave]' LIMIT 1");
    }

    header("Location: transfer.php?busca=ini");

}

if ($_REQUEST["Boton"] === "Agregar" && $_REQUEST["Cantidad"] > 0) {
    $Sql = "SELECT costo FROM invl WHERE clave='$_REQUEST[Producto]'";
    $InvA = mysql_query($Sql);
    $Inv = mysql_fetch_array($InvA);
    $Sql = "INSERT INTO transd (id,clave,cantidad,costo) 
                 VALUES 
                 ('$busca','$_REQUEST[Producto]',$_REQUEST[Cantidad],'$Inv[costo]')";

    if (mysql_query($Sql)) {
        $Clave = "";
        Totaliza($busca);
    }
}
if ($_REQUEST["op"] === 'Si') {
    $cId = $_REQUEST["cId"];
    $Sql = "SELECT clave,cantidad FROM transd WHERE idnvo=$cId LIMIT 1";
    $CntA = mysql_query($Sql);
    $Cnt = mysql_fetch_array($CntA);
    $Sql = "DELETE FROM transd WHERE idnvo=$cId LIMIT 1";
    echo $cSql;
    $Up = mysql_query($Sql);
    Totaliza($busca);
    $Msj = "Registro eliminado!";
}

$busca = $_REQUEST["busca"];
$Msj = $_REQUEST["Msj"];
$op = $_REQUEST["op"];
$StatusMensaje = $_REQUEST["Status"];

$CpoA = mysql_query("SELECT * FROM trans WHERE id='$busca'");

$Cpo = mysql_fetch_array($CpoA);
$Obsr = $_REQUEST["msj"];


$Titulo = "Edita factura [$busca]";

require ("config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Detalle de transferencia ::.</title>
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

    </head>

    <title>

        <?php echo $Titulo; ?>
    </title>
    <?php
    encabezados();
    menu($Gmenu, $Gusr);
    ?>
    <script src="./controladores.js"></script>
<?php
    ?>
    <body onload='cFocus()'>
        <script type="text/javascript">
            $(document).ready(function () {
                var observaciones = "<?= $Obsr ?>";
                if (observaciones !== "") {
                    Swal.fire({
                        title: observaciones,
                        position: "top-right",
                        icon: "success",
                        timer: 1500,
                        toast: true,
                        showConfirmButton: false
                    })
                }
            });
        </script>
        <table width="100%" border="0">
            <td width='50%' valign="top">
                <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                    <table width='98%' bgcolor="#FFF" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                        <tr height='25px' bgcolor='#2c8e3c' ><td colspan="2" align='center' class='letratitulo'>Informacion Principal</td></tr>
                        <tr height='25px'><td align='right' width="50%"><strong>No. de transferencia : </strong></td><td><?= $busca ?></td></tr>
                        <?php
                        $_REQUEST["busca"] === "NUEVO" ? $fecha = date("Y-m-d") : $fecha = $Cpo["fecha"];
                        $_REQUEST["busca"] === "NUEVO" ? $hora = date("H:i") : $hora = $Cpo["hora"];
                        ?>
                        <tr height='25px'><td align='right'><strong>Fecha : </strong></td><td><input type="date" name="Fecha" value="<?= $fecha ?>" class="letrap"></input></td></tr>
                        <tr height='25px'><td align='right'><strong>Hora : </strong></td><td><input type="time" name="Hora" value="<?= $hora ?>" class="letrap"></input></td></tr>
                        <tr height='25px'><td align='right'><strong>De : </strong></td>
                            <td>
                                <select name='Origen' class="letrap" >
                                    <option value='invgral'>General</option>
                                    <option value='invmatriz'>Matriz</option>
                                    <option value='invtepex'>Tepexpan</option>
                                    <option value='invhf'>HF</option>
                                    <option value='invgralreyes'>GralReyes</option>
                                    <option value='invreyes'>Reyes</option>
                                    <option value='invcam'>Camarones</option>
                                    <option value='invsnv'>San Vicente</option>
                                    <option selected value="<?= $Cpo[origen] ?>"><?= $Cpo[origen] ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr height='25px'><td align='right'><strong>Para : </strong></td>
                            <td>
                                <select name="Destino" class="letrap" >
                                    <option value='invgral'>General</option>
                                    <option value='invmatriz'>Matriz</option>
                                    <option value='invtepex'>Tepexpan</option>
                                    <option value='invhf'>HF</option>
                                    <option value='invgralreyes'>GralReyes</option>
                                    <option value='invreyes'>Reyes</option>
                                    <option value='invcam'>Camarones</option>
                                    <option value='invsnv'>San Vicente</option>
                                    <option selected value="<?= $Cpo[destino] ?>"><?= $Cpo[destino] ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr height='25px'><td align='right'><strong>Recibe : </strong></td><td><input name="Usrdest" value="<?= $Cpo[usrdest] ?>" class="letrap"></input></td></tr>
                        <tr height='25px'><td align='right'><strong>Importe : </strong></td><td><input name="Costo" value="<?= number_format($Cpo[costo], 2) ?>" class="letrap"></input></td></tr>
                        <tr height='25px'><td align='right'><strong>Status: </strong></td><td>
                                <select class="letrap" name='Status'>
                                    <option value='ABIERTA'>ABIERTA</option>
                                    <option value='CERRADA'>CERRADA</option>
                                    <option selected value='<?= $Cpo[status] ?>'><?= $Cpo["status"] ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr id="QuitaBoton"><td height="35px" colspan="2" align="center">
                                <input type='submit' class="letrap" name='Boton' value='Aceptar'/>
                                <input type='hidden' name='busca' value="<?= $busca ?>" />
                            </td>
                        </tr>
                        <?php
                        if ($Cpo["status"] == "CERRADA" and $Gusr=="NAZARIO") {
                        ?>
                            <tr>
                                <td height="35px" colspan="2" align="center">
                                    <input class="letrap" type="submit" value='Abrir' name='Boton'></input>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </form>
            </td>
            <td align='center' valign="top">
                <table  width='98%' bgcolor="#fff" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                    <tr height='25px' bgcolor='#2c8e3c' ><td align='center' class='letratitulo'>Detalle</td></tr>
                    <tr><td height="20px"></td></tr>
                    <tr>
                        <td height="40px">

                            <table id="QuitaDetalle" width='98%' align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                                <tr><td colspan="8" height="2px"></td></tr>
                                <tr bgcolor="#5499C7" height='25px'>
                                    <td align='center' class='letratitulo'>Producto</td>
                                    <td align='center' class='letratitulo'>Descripcion</td>
                                    <td align='center' class='letratitulo'>Cantidad</td>
                                    <td align='center' class='letratitulo'>Costo</td>
                                    <td align='center' class='letratitulo'>Importe</td>
                                    <td align='center' class='letratitulo'>Elim</td>
                                </tr>
                                <?php
                                $Qry = mysql_query("SELECT transd.clave,invl.descripcion,transd.cantidad,transd.costo,transd.costo*transd.cantidad as import,transd.idnvo 
    FROM transd LEFT JOIN invl ON transd.clave = invl.clave WHERE transd.id = $busca");
                                while ($rst = mysql_fetch_array($Qry)) {
                                    if (($nRng % 2) > 0) {
                                        $Fdo = 'FFFFFF';
                                    } else {
                                        $Fdo = 'EBEDEF';
                                    }
                                    ?>
                                    <tr bgcolor="#<?= $Fdo ?>" height='25px'>
                                        <td align='center'><?= $rst[clave] ?></td>
                                        <td align='center'><?= $rst[descripcion] ?></td>
                                        <td align='center'><?= $rst[cantidad] ?></td>
                                        <td align='center'><?= $rst[costo] ?></td>
                                        <td align='center'><?= $rst[import] ?></td>
                                        <td align='center'><a  href="<?= $_SERVER[PHP_SELF] ?>?cId=<?= $rst[idnvo] ?>&op=Si&Destino=<?= $He[destino] ?>&Origen=<?= $He[origen] ?>&busca=<?= $busca ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                                    </tr> 
                                    <?php
                                    $nRng++;
                                }
                                ?>
                                <tr><td colspan="7" height="2px"></td></tr>
                            </table>
                            <table id="QuitaDetalleAgrega" width="100%">
                                <tr height="30px" valign="bottom">
                                    <td class="letrap">
                                        <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                                            Producto : <input name="Producto" class="letrap" type="text" value="<?= $_REQUEST[Regresa] ?>"></input> 
                                            <a href='obteninv.php?busca=ini&regresa=<?= $busca ?>' class="edit"><i class='fa fa-search fa-2x' aria-hidden='true'></i></a>
                                            Cnt : <input name="Cantidad" class="letrap" type="number"></input> 
                                            <input type="submit" name="Boton" value="Agregar" class="letrap"></input>
                                            <input type="hidden" name="busca" value="<?= $busca ?>"></input>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%">
                                <tr>
                                    <td align="right">
                                        <a href="transfer.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            </tr>
        </table>
    </body>
    <script type="text/javascript">
        var dt = "<?= $Cpo[status] ?>";
        if (dt === "CERRADA") {
            $("#QuitaBoton").hide();
            $("#QuitaDetalleAgrega").hide();
        }
        if ("<?= $busca ?>" === "NUEVO") {
            $("#QuitaDetalle").hide();
            $("#QuitaDetalleAgrega").hide();
        }
    </script>
</html>
<?php

function Totaliza($busca) {


    $TotA = mysql_query("SELECT sum(cantidad) as cantidad,sum(cantidad*costo) as cantcost,sum(costo*cantidad) FROM transd WHERE id=$busca");
    $Tot = mysql_fetch_array($TotA);
    $Cnt = $Tot["cantidad"] * 1;
    $Imp = $Tot["cantcost"] * 1;
    //$Iva   = $Tot[2]*1;

    $Up = mysql_query("UPDATE trans SET cant=$Cnt,costo=$Imp WHERE id=$busca");
}

mysql_close();
?>

