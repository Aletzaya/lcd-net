<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Fecha = date("Y-m-d H:m:s");
$Msj = $_REQUEST["Msj"];

if ($_REQUEST["bt"] === "Actualizar") {

    $Sql = "UPDATE el SET fecha = '$_REQUEST[Fecha]', hora = '$_REQUEST[Hora]', proveedor = $_REQUEST[Proveedor],"
                . "concepto = '$_REQUEST[Concepto]',factrem = '$_REQUEST[Factrem]' ,documento = '$_REQUEST[Documento]',"
                . "almacen = '$_REQUEST[Almacen]', depto = '$_REQUEST[Depto]', status = '$_REQUEST[Status]', usr='$Gusr' "
                . "WHERE id = $busca;";
        echo $Sql;


    if (mysql_query($Sql)) {

        $sql = mysql_query("SELECT status FROM el WHERE id = $busca");
        $Result = mysql_fetch_array($sql);

        if ($Result["status"] == "CERRADA") {

            $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Admin/Compras/Datos Registro Cerrado','el',now(),$busca);";  

            echo $Sql;

            $ProdB  = mysql_query("SELECT * FROM el WHERE id = $busca");
            $Prodb=mysql_fetch_array($ProdB);

            $ProdA  = mysql_query("SELECT * FROM eld WHERE id = $busca ");

            while($Prod=mysql_fetch_array($ProdA)){

              $InvA     = mysql_query("SELECT iva,pzasmedida FROM invl WHERE id = $Prod[idproducto]");
              $Inv      = mysql_fetch_array($InvA);
              
              $Up = mysql_query("UPDATE invl SET existencia = ($Prod[cantidad]*$Inv[pzasmedida])+ existencia, $Prodb[almacen] = ($Prod[cantidad]*$Inv[pzasmedida])+ $Prodb[almacen], costoant = costo, costo = $Prod[costo] WHERE id = $Prod[idproducto] LIMIT 1");
              
            }  

        } else {

            $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Admin/Compras/Datos Registro Actualizado','el',now(),$busca);";  

            echo $Sql;

        }

        if (mysql_query($Sql)) {

            header("Location: entlabe.php?busca=$busca&Msj=Actualizado con Exito");
        
        }

    }


} elseif ($_REQUEST["bt"] === "Abrir") {

    $Sql = "UPDATE el SET status = 'ABIERTA' "
                . " WHERE id = $busca;";
        echo $Sql;

    if (mysql_query($Sql)) {

      $ProdB  = mysql_query("SELECT * FROM el WHERE id=$busca");
      $Prodb=mysql_fetch_array($ProdB);

      $ProdA  = mysql_query("SELECT * FROM eld WHERE id=$busca");

      while($Prod=mysql_fetch_array($ProdA)){

          $InvA     = mysql_query("SELECT iva,pzasmedida FROM invl WHERE id=$Prod[idproducto]");
          $Inv      = mysql_fetch_array($InvA);
          
          $Up = mysql_query("UPDATE invl SET existencia = existencia - ($Prod[cantidad]*$Inv[pzasmedida]), $Prodb[almacen] = $Prodb[almacen] - ($Prod[cantidad]*$Inv[pzasmedida]) WHERE id=$Prod[idproducto] LIMIT 1");
         
      }

        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
            . "('$Gusr','/Admin/Compras/Datos Registro Abierto','el',now(),$busca);";  
        
        echo $Sql;

        if (mysql_query($Sql)) {

            header("Location: entlabe.php?busca=$busca&Msj=Abierta con Exito");
        
        }


    }

} elseif ($_REQUEST["bt"] === "Nuevo") {

    $Sql = "INSERT INTO el (fecha,hora,proveedor,concepto,documento,status,depto,usr,almacen,factrem) "
            . "VALUES ('$_REQUEST[Fecha]','$_REQUEST[Hora]','$_REQUEST[Proveedor]','$_REQUEST[Concepto]','$_REQUEST[Documento]',"
            . "'ABIERTA','$_REQUEST[Depto]','$Gusr','$_REQUEST[Almacen]','$_REQUEST[Factrem]');";

    if (mysql_query($Sql)) {
        $Id = mysql_insert_id();
        $Sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES "
                . "('$Gusr','/Admin/Compras/Datos Principales Crea Registro','el',now(),$Id);";
        echo $Sql;
        if (mysql_query($Sql)) {

            header("Location: entlabd.php?busca=$Id&Msj=Creado con Exito");
        }
    }
}

#Variables comunes;
$CpoA = mysql_query("SELECT * FROM el WHERE id = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
if ($_REQUEST["Estado"] <> '') {
    $Estado = $_REQUEST["Estado"];
} else {
    $Estado = $Cpo["estado"];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Instituciones - General</title>
            <?php require ("./config_add.php"); ?>
            <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
            <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
            <link rel='icon' href='favicon.ico' type='image/x-icon' />
            <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>

        <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
            <tr>
                <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                    ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                </td>
            </tr>
            <tr>
                <td valign='top' align='center' height='440' width='45%'>
                    <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Datos principales ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    No. de entrada : 
                                </td>
                                <td class="Inpt">
                                    <?= $Cpo[id] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Fecha : 
                                </td>
                                <td class="Inpt">
                                    <?php
                                    $_REQUEST["busca"] === "NUEVO" ? $date = date("Y-m-d") : $date = $Cpo[fecha];
                                    ?>
                                    <input type='date' class='cinput'  name='Fecha' value='<?= $date ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Hora : 
                                </td>
                                <td class="Inpt">
                                    <?php
                                    $_REQUEST["busca"] === "NUEVO" ? $date = date("H:i") : $date = $Cpo[hora];
                                    ?>
                                    <input type='time' class='cinput'  name='Hora' value='<?= $date ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Proveedor : 
                                </td>
                                <td class="Inpt">
                                    <select name='Proveedor' class="cinput">
                                        <?php
                                        $HilA = mysql_query("select id,alias from prv order by id ");
                                        while ($Hil = mysql_fetch_array($HilA)) {
                                            ?>
                                            <option value="<?= $Hil[id] ?>"><?= $Hil["id"] . $Hil["alias"] ?></option>
                                            <?php
                                            if ($Cpo["proveedor"] == $Hil["id"]) {
                                                $cDes = $Hil["alias"];
                                            }
                                        }
                                        ?>
                                        <option selected value=<?= $Cpo["proveedor"] ?>><?= $cDes ?></option>";
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Concepto : 
                                </td>
                                <td class="Inpt">
                                    <select name='Concepto' class="cinput">";
                                        <option value='Inventario inicial'>Inventario inicial</option>
                                        <option value='Compras'>Compras</option>
                                        <option value='Devoluciones'>Devoluciones</option>
                                        <option value='Ajuste'>Ajuste</option>
                                        <option value='Mantenimiento'>Mantenimiento</option>
                                        <option selected value="<?= $Cpo[concepto] ?>"><?= $Cpo[concepto] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Tipo : 
                                </td>
                                <td class="Inpt">
                                    <select name='Factrem' class="cinput">
                                        <option value='Factura'>Factura</option>
                                        <option value='Remision'>Remision</option>
                                        <option value='Nota'>Nota</option>
                                        <option value='S/Factura'>S/Factura</option>
                                        <option selected value="<?= $Cpo[factrem] ?>"><?= $Cpo[factrem] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    No.Fac ó Docto : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Documento' value='<?= $Cpo[documento] ?>'/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Cantidad : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Cantidad' value='<?= $Cpo[cantidad] ?>' disabled/>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Importe : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' style="width: 50px" class='cinput'  name='Importe' value='<?= $Cpo[importe] ?>' disabled />
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Almacen : 
                                </td>
                                <td class="Inpt">
                                    <select name='Almacen' class="cinput">";
                                        <option value='invgral'>General</option>
                                        <option value='invmatriz'>Matriz</option>
                                        <option value='invtepex'>Tepexpan</option>
                                        <option value='invhf'>HF</option>
                                        <option value='invgralreyes'>Gral.Reyes</option>
                                        <option value='invreyes'>Reyes</option>
                                        <?php
                                        if ($Cpo[almacen] == 'invgral') {
                                            $Almacenes = 'General';
                                        } elseif ($Cpo[almacen] == 'invmatriz') {
                                            $Almacenes = 'Matriz';
                                        } elseif ($Cpo[almacen] == 'invtepex') {
                                            $Almacenes = 'Tepexpan';
                                        } elseif ($Cpo[almacen] == 'invhf') {
                                            $Almacenes = 'HF';
                                        } elseif ($Cpo[almacen] == 'invgralreyes') {
                                            $Almacenes = 'Gral.Reyes';
                                        } elseif ($Cpo[almacen] == 'invreyes') {
                                            $Almacenes = 'Reyes';
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[almacen] ?>"><?= $Almacenes ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Departamento : 
                                </td>
                                <td>
                                    <select name='Depto' class="cinput">";
                                        <option value='Mixto'>Mixto</option>
                                        <option value='Admvo'>Admvo</option>
                                        <option value='Laboratorio'>Laboratorio</option>
                                        <option value='Rayos X'>Rayos X</option>
                                        <option value='Ultrasonido'>Ultrasonido</option>			 
                                        <option selected value="<?= $Cpo[depto] ?>"><?= $Cpo[depto] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Status : 
                                </td>
                                    <?php
                                    if ($Cpo["status"] == "CERRADA") {
                                        $bloqueados='disabled';
                                    }
                                    ?>
                                <td>
                                    <select name='Status' class="cinput" <?= $bloqueados ?>>
                                        <option value='ABIERTA'>ABIERTA</option>
                                        <option value='CERRADA'>CERRADA</option>
                                        <option selected value='<?= $Cpo[status] ?>'><?= $Cpo[status] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php
                                    if ($_REQUEST["busca"] === "NUEVO") {
                                        ?>
                                        <input class="letrap" type="submit" value='Nuevo' name='bt'></input>
                                        <?php
                                    } elseif ($Cpo["status"] == "ABIERTA") {
                                        ?>
                                        <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                        <?php
                                    } elseif ($Cpo["status"] == "CERRADA" and $Gusr=="NAZARIO") {
                                        ?>
                                        <input class="letrap" type="submit" value='Abrir' name='bt'></input>
                                        <?php
                                    }
                                    ?>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                </td>
                            </tr>
                        </form>
                    </table>  
                </td>
                <td valign='top' width='45%'>         
                    <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                        <tr style="background-color: #2c8e3c">
                            <td class='letratitulo'align="center" colspan="2">
                                .:: Modificaciones ::.
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table align="center" width="95%" style="border:#000 1px solid;border-color: #999; border-radius: .5em;" border="0">
                                    <tr class="letrap">
                                        <td><b>&nbsp; Id</b></td>
                                        <td><b>&nbsp; Fecha</b></td>
                                        <td><b>&nbsp; Usuario</b></td>
                                        <td><b>&nbsp; Accion</b></td>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Admin/Compras/Datos%') 
                                                AND cliente=$busca ORDER BY id DESC LIMIT 6;";
//echo $sql;
                                    $PgsA = mysql_query($sql);
                                    while ($rg = mysql_fetch_array($PgsA)) {
                                        if (($nRng % 2) > 0) {
                                            $Fdo = 'FFFFFF';
                                        } else {
                                            $Fdo = $Gfdogrid;
                                        }
                                        ?>
                                        <tr bgcolor="<?= $Fdo ?>" class="letrap">
                                            <td>
                                                <b>&nbsp;<?= $rg[id] ?></b>
                                            </td>
                                            <td align="center">
                                                &nbsp;<?= $rg[fecha] ?>
                                            </td>
                                            <td>
                                                <?= $rg[usr] ?>
                                            </td>
                                            <td>
                                                <?= $rg[accion] ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $nRng++;
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign='top' width="22%">
                    <a href="entlab.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                </td>
            </tr>      
        </table>  
    </body>
</html>
<?php
mysql_close();
?>
