<?php
session_start();

require("lib/lib.php");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

$link = conectarse();
$busca = $_REQUEST["busca"];
$Gusr = $_SESSION["Usr"][0];
$Gcia = $_SESSION["Usr"][1];
$Gnomcia = $_SESSION["Usr"][2];
$Gnivel = $_SESSION["Usr"][3];
$Gteam = $_SESSION["Usr"][4];
$Gmenu = $_SESSION["Usr"][5];
$Tabla = "invl";
$Obsr = $_REQUEST["Msj"];
$Titulo = "Detalle por producto";

$Usr = $check['uname'];

if ($_REQUEST["Boton"] === "Actualiza") {
    $existencia = $_REQUEST[Invgral] + $_REQUEST[Invmatriz] + $_REQUEST[Invtepex] + $_REQUEST[Invhf] + $_REQUEST[Invreyes] + $_REQUEST[Invgralreyes];

    $Update = "UPDATE $Tabla SET clave='$_REQUEST[Clave]',descripcion='$_REQUEST[Descripcion]',"
            . "presentacion='$_REQUEST[Presentacion]',pzasmedida='$_REQUEST[Pzasmedida]',marca='$_REQUEST[Marca]',iva='$_REQUEST[Iva]',"
            . "depto='$Dep[0]',subdepto='$_REQUEST[Subdepto]',uso='$_REQUEST[Uso]',min='$_REQUEST[Min]',max='$_REQUEST[Max]',"
            . "status='$_REQUEST[Status]',presentacionp='$_REQUEST[Presentacionp]',npruebas='$_REQUEST[Npruebas]',"
            . "suc1='$_REQUEST[Suc1]',suc2='$_REQUEST[Suc2]',suc3='$_REQUEST[Suc3]',suc4='$_REQUEST[Suc4]',suc5 = '$_REQUEST[Suc5]',suc6 = '$_REQUEST[Suc6]',metodo='$_REQUEST[Metodo]',"
            . "proveedor1='$_REQUEST[Proveedor1]',proveedor2='$_REQUEST[Proveedor2]',proveedor3='$_REQUEST[Proveedor3]',"
            . "referencia='$_REQUEST[Referencia]',invgral='$_REQUEST[Invgral]',invmatriz='$_REQUEST[Invmatriz]',invtepex='$_REQUEST[Invtepex]',"
            . "invhf='$_REQUEST[Invhf]',invreyes='$_REQUEST[Invreyes]',invgralreyes='$_REQUEST[Invgralreyes]',"
            . "existencia='$existencia',pertenece='$_REQUEST[Pertenece]',ctrl='$_REQUEST[ctrl]' WHERE id='$busca' limit 1";
    echo $Update;
    if (mysql_query($Update)) {
        $Msj = "Registro actualizado con Exito!";
        header("Location: invlabe2.php?busca=$busca&Msj=$Msj");
    }
}
$cSql = "SELECT * FROM $Tabla WHERE id='$busca'";

$CpoA = mysql_query($cSql);
$Cpo = mysql_fetch_array($CpoA);

if ($busca == 'NUEVO') {
    $lAg = true;
}
//$lAg=$_REQUEST[Apellidop]<>$Cpo[apellidop];


$Fecha = date("Y-m-d");

require ("config.php");
?>
<html>

    <head>
        <meta charset="UTF-8">
        <title><?php echo $Titulo; ?></title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    </head>

    <body onLoad="cFocus1()">
        <script type="text/javascript">
            $(document).ready(function () {
                var observaciones = "<?= $Obsr ?>";
                if (observaciones != "") {
                    Swal.fire({
                        title: observaciones,
                        position: "top-right",
                        icon: "success",
                        toast: true,
                        timer: 1500
                    })
                }
            });
            if ("<?= $Pdf ?>") {
                window.open("impotpdf-ant.php?busca=<?= $Arch ?>", "ventana1", "width=800,height=1000,scrollbars=NO")
            }
            if ("<?= $Pdfc ?>") {
                window.open("imppdfcoti.php?busca=<?= $Arch ?>", "ventana1", "width=800,height=1000,scrollbars=NO")
            }

            document.getElementById("#estudio").focus();


        </script>
        <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

        <SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <?php
            encabezados();
            menu($Gmenu, $Gusr);
            ?>
            <table width="100%" border="0">
                <tr>
                    <td width='50%' valign="top">
                        <table width='98%' bgcolor="#F2F2F2" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr height='25px' bgcolor='#2c8e3c'><td colspan="2" align='center' class='letratitulo'>Informacion Principal</td></tr>
                            <tr height='25px'><td align='right'>Clave : </td><td><input type="text" name="Clave" value="<?= $Cpo["clave"] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Descripcion: </td><td><input type="text" name="Descripcion" value="<?= $Cpo["descripcion"] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Marca: </td><td><input type="text" name="Marca" value="<?= $Cpo["marca"] ?>" class="letrap"></td></tr>
                            <tr height='25px'>
                                <td align='right'>Presentacion individual:  </td><td>
                                    <SELECT class="letrap" name='Presentacion'>
                                        <option value='Piezas'>Piezas</option>
                                        <option value='Cajas'>Cajas</option>
                                        <option value='Paquetes'>Paquetes</option>
                                        <option value='Bolsas'>Bolsas</option>
                                        <option value='Kilos'>Kilos</option>
                                        <option value='Litros'>Litros</option>
                                        <option value='Frasco'>Frasco</option>
                                        <option selected value='<?= $Cpo[presentacion] ?>'><?= $Cpo[presentacion] ?></option>
                                    </SELECT>
                                </td>
                            </tr>
                            <tr height='25px'><td align='right'>Pzas. por precentacion: </td><td><input type="text" name="Pzasmedida" value="<?= $Cpo[pzasmedida] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Precentacion del producto: </td><td><input type="text" name="Presentacionp" value="<?= $Cpo[presentacionp] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>No. de pruebas: </td><td><input type="text" name="Npruebas" value="<?= $Cpo[npruebas] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>% de IVA: </td><td><input type="text" name="Iva" value="<?= $Cpo[iva] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Existencia: </td><td><input type="text" name="Existencia" value="<?= $Cpo[existencia] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Costo: </td><td><input type="text" name="Costo" value="<?= $Cpo[costo] ?>" class="letrap"></td></tr>
                            <?php $costoPieza = ($Cpo[costo] * (($Cpo[iva] / 100) + 1)) / $Cpo[npruebas]; ?>
                            <tr height='25px'><td align='right'>Costo por Pieza : </td><td><input type="text" name="Costopza" value="<?= $costoPieza ?>" class="letrap"></td></tr>
                            <?php
                            $DepSql = mysql_query("SELECT dep.nombre FROM dep,depd WHERE depd.subdepto='$Cpo[subdepto]' and depd.departamento=dep.departamento and '$Cpo[subdepto]'<>'' ");
                            $ResDep = mysql_fetch_array($DepSql);
                            ?>
                            <tr height='25px'><td align='right'>Departamento: </td><td><input type="text" name="Depto" value="<?= $ResDep[nombre] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Sub-departamento</td><td align='left'>
                                    <?php
                                    $cSub = mysql_query("SELECT depd.subdepto,dep.nombre FROM dep,depd WHERE dep.departamento=depd.departamento");
                                    echo "<select class='letrap' name='Subdepto'>";
                                    while ($dep = mysql_fetch_array($cSub)) {
                                        echo "<option value='$dep[subdepto]'>$dep[nombre]: $dep[subdepto]</option>";
                                    }
                                    echo "<option SELECTed value='$Cpo[subdepto]'>$Cpo[subdepto]</option>";
                                    echo "</select>";
                                    ?>
                                </td>
                            </tr>
                            <tr height='25px' bgcolor='#F2F2F2'><td align='right'>Uso: </td><td><input type="text" name="Uso" value="<?= $Cpo[uso] ?>" class="letrap"></td></tr>
                            <tr height="10px">
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                    <td align='center' valign="top">
                        <table width='98%' bgcolor="#F2F2F2" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr height='25px' bgcolor='#2c8e3c'><td colspan="2" align='center' class='letratitulo'>Informacion Principal</td></tr>
                            <tr height='25px'><td align='right'>Exist. General : </td><td><input type="text" name="Invgral" value="<?= $Cpo[invgral] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Exist. Matriz: </td><td><input type="text" name="Invmatriz" value="<?= $Cpo[invmatriz] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Exist. Tepexpan: </td><td><input type="text" name="Invtepex" value="<?= $Cpo[invtepex] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Exist. Futura </td><td><input type="text" name="Invhf" value="<?= $Cpo[invhf] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Exist.Gral.Reyes: </td><td><input type="text" name="Invgralreyes" value="<?= $Cpo[invgralreyes] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Exist. Reyes: </td><td><input type="text" name="Invreyes" value="<?= $Cpo[invreyes] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Exist. General: </td><td><input type="text" name="Existencia" value="<?= $Cpo[existencia] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Minimo: </td><td><input type="text" name="Min" value="<?= $Cpo[min] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Maximo: </td><td><input type="text" name="Max" value="<?= $Cpo[max] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Metodo: </td><td><input type="text" name="Metodo" value="<?= $Cpo[metodo] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Proveedor 1: </td><td><input type="text" name="Proveedor1" value="<?= $Cpo[proveedor1] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Proveedor 2: </td><td><input type="text" name="Proveedor2" value="<?= $Cpo[proveedor2] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Proveedor 3: </td><td><input type="text" name="Proveedor3" value="<?= $Cpo[proveedor3] ?>" class="letrap"></td></tr>
                            <tr height='25px'><td align='right'>Sucursales: </td>
                                <td>
                                    <select class="letrap" size='1' name='Suc1' class='Estilo10'>
                                        <?php
                                        $SucA1 = mysql_query("SELECT id,alias FROM cia order by id");
                                        while ($Suc1 = mysql_fetch_array($SucA1)) {
                                            echo "<option value=$Suc1[id]> $Suc1[id]&nbsp;$Suc1[alias]</option>";
                                            if ($Suc1["id"] == $Cpo["suc1"]) {
                                                $DesSuc1 = $Suc1["alias"];
                                            }
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo["suc1"] ?>"> <font size='-1'><?= $Cpo[suc1] . $DesSuc1 ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td></td>
                                <td>
                                    <select class="letrap" size='1' name='Suc2' class='Estilo10'>
                                        <?php
                                        $SucA2 = mysql_query("SELECT id,alias FROM cia order by id");
                                        while ($Suc2 = mysql_fetch_array($SucA2)) {
                                            echo "<option value=$Suc2[id]> $Suc2[id]&nbsp;$Suc2[alias]</option>";
                                            if ($Suc2[id] == $Cpo[suc2]) {
                                                $DesSuc2 = $Suc2[alias];
                                            }
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[suc2] ?>"><font size='-1'><?= $Cpo[suc2] . $DesSuc2 ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td></td>
                                <td>
                                    <select class="letrap" size='1' name='Suc3' class='Estilo10'><
                                        <?php
                                        $SucA3 = mysql_query("SELECT id,alias FROM cia order by id");
                                        while ($Suc3 = mysql_fetch_array($SucA3)) {
                                            echo "<option value=$Suc3[id]> $Suc3[id]&nbsp;$Suc3[alias]</option>";
                                            if ($Suc3["id"] == $Cpo["suc3"]) {
                                                $DesSuc3 = $Suc3["alias"];
                                            }
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[suc3] ?>"><font size='-1'><?= $Cpo["suc3"] . $DesSuc3 ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td></td>
                                <td>
                                    <select class="letrap" size='1' name='Suc4' class='Estilo10'>
                                        <?php
                                        $SucA4 = mysql_query("SELECT id,alias FROM cia order by id");
                                        while ($Suc4 = mysql_fetch_array($SucA4)) {
                                            echo "<option value=$Suc4[id]> $Suc4[id]&nbsp;$Suc4[alias]</option>";
                                            if ($Suc4[id] == $Cpo[suc4]) {
                                                $DesSuc4 = $Suc4[alias];
                                            }
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[suc4] ?>"><font size='-1'><?= $Cpo[suc4] . $DesSuc4 ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td></td>
                                <td>
                                    <select class="letrap" size='1' name='Suc5' class='Estilo10'>
                                        <?php
                                        $SucA5 = mysql_query("SELECT id,alias FROM cia order by id");
                                        while ($Suc = mysql_fetch_array($SucA5)) {
                                            echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
                                            if ($Suc[id] == $Cpo[suc5]) {
                                                $DesSuc5 = $Suc[alias];
                                            }
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[suc5] ?>"><font size='-1'><?= $Cpo[suc5] . $DesSuc5 ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td></td>
                                <td>
                                    <select class="letrap" size='1' name='Suc6' class='Estilo10'>
                                        <?php
                                        $SucA6 = mysql_query("SELECT id,alias FROM cia order by id");
                                        while ($Suc = mysql_fetch_array($SucA6)) {
                                            echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
                                            if ($Suc[id] == $Cpo[suc6]) {
                                                $DesSuc6 = $Suc[alias];
                                            }
                                        }
                                        ?>
                                        <option selected value="<?= $Cpo[suc6] ?>"><font size='-1'><?= $Cpo[suc6] . $DesSuc6 ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td align='right'>Almacen Principal : </td>
                                <td>
                                    <select class="letrap" name='Pertenece'>
                                        <option value='General'>General</option>
                                        <option value='Reyes'>Reyes</option>
                                        <option selected value="<?= $Cpo["pertenece"] ?>"><?= $Cpo["pertenece"] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td align='right'>Status : </td>
                                <td>
                                    <select class="letrap" name='Status'>
                                        <option value='Activo'>Activo</option>
                                        <option value='Inactivo'>Inactivo</option>
                                        <option selected value="<?= $Cpo["status"] ?>"><?= $Cpo["status"] ?></option>";
                                    </select>
                                </td>
                            </tr>
                            <tr height='25px'>
                                <td align='right'>Control Inventario : </td>
                                <td>
                                    <select class="letrap" name='ctrl'>
                                        <option value='Pendiente'>Pendiente</option>
                                        <option value='Revision'>Revision</option>
                                        <option value='Verificado'>Verificado</option>
                                        <option selected value="<?= $Cpo["ctrl"] ?>"><?= $Cpo["ctrl"] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr height="10px">
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr height="20px">
                    <td colspan="2" align="center">
                        <input type="submit" name="Boton" value="Actualiza" class="letrap"> 
                        <input type="hidden" name='busca' value="<?= $busca ?>">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
<script src="./controladores.js"></script>
<?php
mysql_close();
