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

if ($_REQUEST["Boton"] === "Nuevo" && $_REQUEST["NombrePrv"] !== "") {
    $Insert = "INSERT INTO invlClaves (idInvl,descripcion) VALUES ('$busca','" . $_REQUEST["NombrePrv"] . "')";
    mysql_query($Insert);
} else if ($_REQUEST["op"] === "Delete") {
    $Delete = "DELETE FROM invlClaves WHERE id = '" . $_REQUEST["idNvo"] . "'";
    mysql_query($Delete);
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
            <script src="./controladores.js"></script>
            <?php ?>
            <table width="100%" border="0">
                <tr>
                    <td width='50%' valign="top">
                        <table width='98%' bgcolor="#F2F2F2" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr height='25px' bgcolor='#2c8e3c'><td colspan="3" align='center' class='letratitulo'>Informacion Principal</td></tr>
                            <tr height="25px">
                                <td align="right" style="width:40%;">Nombre dado por el proveedor: </td>
                                <td><input style="width: 95%" class="letrap" type="text" name="NombrePrv"></td>
                                <td><input type="submit" name="Boton" value="Nuevo"></td>
                            </tr>
                        </table>
                        <a href="repinv.php" class="content5" > <i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                    </td>
                    <td align='center' valign="top">
                        <table width='98%' bgcolor="#F2F2F2" align='center' cellpadding='0' class='letrap' cellspacing='0' style='border:#CCC 1px solid;border-color: #999; border-radius: .5em;'>
                            <tr height='25px' bgcolor='#2c8e3c'><td colspan="3" align='center' class='letratitulo'>Nombres asociados a este producto</td></tr>
                            <?php
                            $Buscamos = "SELECT descripcion,id FROM invlClaves WHERE idInvl = '$busca';";
                            $SqlB = mysql_query($Buscamos);
                            $b = 1;
                            while ($dts = mysql_fetch_array($SqlB)) {
                                ?>
                                <tr style="height: 25px;">
                                    <td align="right"><?= $b ?></td>
                                    <td align="center"><?= $dts["descripcion"] ?></td>
                                    <td><a href="repinvd.php?busca=<?= $busca ?>&op=Delete&idNvo=<?= $dts["id"] ?>"><i class='fa fa-trash fa-2x' aria-hidden='true'></i></a></td>
                                </tr>
                                <?php
                                $b++;
                            }
                            ?>
                        </table>
                    </td>
                </tr>
                <tr height="20px">
                    <td colspan="2" align="center">
                        <?php $_REQUEST["busca"] === "NUEVO" ? $is = "NUEVO" : $is = "Actualiza"; ?>
                        <input type="submit" name="Boton" value="<?= $is ?>" class="letrap"> 
                        <input type="hidden" name='busca' value="<?= $busca ?>">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
<?php
mysql_close();
