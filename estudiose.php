<?php

date_default_timezone_set("America/Mexico_City");

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();

#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];

#Variables comunes;
$Titulo = "Ordenes de estudio";

#Variables comunes;
$Titulo = "Detalles de estudios";
$busca = $_REQUEST["busca"];
//$msj = $_REQUEST["Msj"];
$Fecha = date("Y-m-d");
$FechaI = date("Y-m-d");
$FechaF = date("Y-m-d");
$date = date("Y-m-d H:i:s");
$Msj = $_REQUEST["Msj"];
if ($_REQUEST["bt"] == 'Actualizar') {
    $sql = "SELECT bloqbas FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql["bloqbas"] == 'No') {
        $Sql = "UPDATE est SET descripcion = '$_REQUEST[Descripcion]',clavealt='$_REQUEST[Clavealt]',activo='$_REQUEST[Activo]'"
                . ",comision='$_REQUEST[Comision]',subdepto = '$_REQUEST[Subdepto]',depto = '$_REQUEST[Depto]',costo='$_REQUEST[Costo]'"
                . ",base='$_REQUEST[Base]',consentimiento='$_REQUEST[Consentimiento]' "
                . "WHERE estudio='$busca'";
        $cId = mysql_insert_id();

        $cProceso = "Agrega est $cId ";

        if (mysql_query($Sql)) {
            $Msj = "Registro Actualizado con Exito no. " . $busca;
            AgregaBitacoraEventos($Gusr, '/Estudios/DatosBasicos/Actualizacion de detalle', "est", $date, $busca, $Msj, "estudiose.php");
        }
        echo "<div align='center'>$Sql : " . mysql_error() . "</div>";
    } else {
        $Msj = "!Error¡ Bloqueo para editar datos activo";
        header("Location: estudiose.php?busca=$busca&Msj=$Msj&Error=SI");
    }
} elseif ($_REQUEST["bt"] == 'Nuevo') {

    $sql = "SELECT estudio FROM est;";
    $cSQL = mysql_query($sql);
    $repetido = TRUE;
    $Estudionvo=strtoupper($_REQUEST[Estudio]);
    while ($Rs = mysql_fetch_array($cSQL)) {
        if ($Rs[estudio] == $Estudionvo) {
            $Msj = "!El estudio $Estudionvo ya existe en el sistema. Favor de verificar¡";
            header("Location: estudiose.php?busca=Nuevo&Msj=$Msj&Error=SI");
            $repetido = FALSE;
        }
    }
    if ($repetido) {
        $sql = "INSERT INTO est (estudio,clavealt,descripcion,comision,depto,subdepto,activo,costo,base,consentimiento) "
                . "VALUES ('$Estudionvo','$_REQUEST[Clavealt]','$_REQUEST[Descripcion]','$_REQUEST[Comision]','$_REQUEST[Depto]',"
                . "'$_REQUEST[Subdepto]','$_REQUEST[Activo]','$_REQUEST[Costo]','$_REQUEST[Base]','$_REQUEST[Consentimiento]');";

        if (mysql_query($sql)) {
            $Id = mysql_insert_id();
            $Msj = "¡Registro ingresado con exito!";
            AgregaBitacoraEventos($Gusr, '/Estudios/DatosBasicos/Alta de Registro', "est", $date, $Estudionvo, $Msj, "estudiose.php");
            header("Location: estudiose.php?busca=$Estudionvo");

        }else{
            
            echo "<div align='center'>$sql " . mysql_error() . "</div>";
            $Archivo = 'EST';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }

    }

} elseif ($_REQUEST["bt"] == 'Agregar_Sinonimo') {

    $sql = "SELECT bloqbas FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqbas] == 'No') {
        if ($_REQUEST["Sinonimos"] <> '') {
            $Sql = "INSERT INTO ests (descripcion,estudio) VALUES ('$_REQUEST[Sinonimos]','$busca');";
            $pSql = mysql_query($Sql);
            qry($usr, "Agrega sinonimo | Values $_REQUEST[Sinonimos] $busca");
            $Msj = "!Sinonimo agregado con exito¡";
            AgregaBitacoraEventos($Gusr, '/Estudios/DatosBasicos/Agrega Sinonimo', "est", $date, $busca, $Msj, "estudiose.php");
        }else{
            $Msj = "!Error¡ Agrega descripcion del sinonimo";
            header("Location: estudiose.php?busca=$busca&Msj=$Msj&Error=SI");
        }
    } else {
        $Msj = "!Error¡ Bloqueo para editar datos activo";
        header("Location: estudiose.php?busca=$busca&Msj=$Msj&Error=SI");
    }

} elseif ($_REQUEST["dt"] == 'yes') {

    $sql = "SELECT bloqbas FROM est WHERE estudio='$busca';";
    $cSql = mysql_query($sql);
    $sql = mysql_fetch_array($cSql);
    if ($sql[bloqbas] == 'No') {
        $Sql = "DELETE FROM ests WHERE estudio='$busca' AND descripcion='$_REQUEST[Desc]'";
        $pSql = mysql_query($Sql);
        qry($usr, "Elimina sinonimo | Values $_REQUEST[Sinonimos] $busca");
        $Msj = "!Sinonimo eliminado con exito¡";
        AgregaBitacoraEventos($Gusr, '/Estudios/DatosBasicos/Elimina Sinonimo', "est", $date, $busca, $Msj, "estudiose.php");
    } else {
        $Msj = "!Error¡ Bloqueo para editar datos activo";
        header("Location: estudiose.php?busca=$busca&Msj=$Msj&Error=SI");
    }
}

if ($_REQUEST[Op] == "ab") {

    $sql = "UPDATE est SET bloqbas = 'No' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Estudios/DatosBasicos/Abre Datos Basicos','est','$date','$busca');";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    $Msj = "Registro cerrado con exito!";

} elseif ($_REQUEST[Op] == "cr") {

    $sql = "UPDATE est SET bloqbas = 'Si' WHERE estudio='$busca'";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Estudios/DatosBasicos/Cierra Datos Basicos','est','$date','$busca');";
    if (!mysql_query($sql)) {
        echo "Error en sintaxis Mysql " . $sql;
    }
    $Msj = "Registro abierto con exito!";

}

$CpoA = mysql_query("SELECT * FROM est WHERE (estudio= '$busca')");
$Cpo = mysql_fetch_array($CpoA);

if ($Cpo[bloqbas] == 'Si') {
    $bloqueado='disabled';
}else{
    $bloqueado='';
}

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Estudios - Datos Principales</title>
            <?php require ("./config_add.php"); ?>
    </head>
    <body topmargin="1">
        <?php
 //       encabezados();
 //       menu($Gmenu, $Gusr);
        ?>
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Datos Principales ( <?= $busca ?> ) <?= $Cpo[descripcion] ?>::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='440' width='43%'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c"><td class='letratitulo'align="center" colspan="2">..:: Detalle de estudio ::..</td></tr>
                            <tr style="height: 30px" id="QuitaEstudio" class="ssbm">
                                <td width='45%' align="right" class="Inpt">Estudio :</td>
                                <td class="Inpt"><input type='text' class='cinput'  name='Estudio' id="Estudio" value='<?= $Cpo[estudio] ?>' MAXLENGTH='30'></input> clave <a id="critico" style="color:red;">VERIFICAR! Dato critico</a></td>
                            </tr>
                            <tr style="height: 30px" class="ssbm">
                                <td align="right" class="Inpt" >Clave alterna : </td>
                                <td class="Inpt"><input type='text' class='cinput'  name='Clavealt' value='<?= $Cpo[clavealt] ?>' <?= $bloqueado ?>></input></td>
                            </tr>
                            <tr style="height: 30px" class="ssbm">
                                <td align="right" class="Inpt">Descripcion : </td>
                                <td class="Inpt"><input type='text' class='cinput'  name='Descripcion' value='<?= $Cpo[descripcion] ?>' style="width: 300px" <?= $bloqueado ?> MAXLENGTH='60'/></td>
                            </tr>
                            <tr style="height: 30px" class="ssbm">
                                <td align="right" class="Inpt">Comision : </td>
                                <td class="Inpt"><input type='text' class='cinput' name='Comision' value='<?= $Cpo[comision] ?>' <?= $bloqueado ?> style="width: 40px" MAXLENGTH='30'/></td>
                            </tr>
                            <tr style="height: 30px" class="ssbm"><td class='letrap' align='right'>Departamento :</td><td>
                                    <select class='cinput' class='InputMayusculas' name='Depto' id="Depto" <?= $bloqueado ?>>   
                                        <?php
                                        $DepA = mysql_query("SELECT departamento,nombre FROM dep ORDER BY departamento");
                                        while ($Dep = mysql_fetch_array($DepA)) {
                                            echo '<option value=' . $Dep[departamento] . '>' . $Dep[departamento] . ' ' . $Dep[nombre] . '</option>';
                                        }
                                        ?>       

                                    </select> &nbsp; 
                                </td>
                            </tr>
                            <tr style="height: 30px" class="ssbm"><td class='letrap' align='right'>Sub-departamento :</td><td>
                                    <select class='cinput' class='InputMayusculas' name='Subdepto' id="Subdepto" <?= $bloqueado ?>>  
                                        <?php
                                        $SubDepA = mysql_query("SELECT depd.subdepto,dep.nombre FROM dep,depd WHERE dep.departamento=depd.departamento");
                                        while($Sub = mysql_fetch_array($SubDepA)) {
                                            echo '<option value='.$Sub[subdepto].'>' . ucwords(strtolower($Sub[nombre])) .  '&nbsp;'. strtoupper($Sub[subdepto]) . '</option>';
                                        }
                                        ?>
                                    </select> &nbsp; 
                                </td>
                            </tr>

                            <tr style="height: 30px" class="ssbm"><td class='letrap' align='right'>Activo :</td>
                                <td>
                                    <?php
                                    if ($Cpo["activo"] == 'Si') {
                                        $cSi = "checked";
                                    } elseif ($Cpo["activo"] == 'No') {
                                        $cNo = "checked";
                                    }
                                    ?>
                                    <span class='content2'>
                                        <input type='radio' name='Activo' value='Si' <?= $cSi ?> <?= $bloqueado ?>> Si </input>
                                        <input type='radio' name='Activo' value='No' <?= $cNo ?> <?= $bloqueado ?>> No</input>
                                    </span>                 
                                </td>
                            </tr>
                            <tr style="height: 30px" class="ssbm">
                                <td align="right" class="Inpt">
                                    Costo : 
                                </td>
                                <td class="Inpt">
                                    <input type='number' step="any" class='cinput' style="width: 80px" name='Costo' value='<?= $Cpo[costo] ?>' <?= $bloqueado ?>>
                                </td>
                            </tr>
                            <tr style="height: 30px" class="ssbm"><td class='letrap' align='right'>Tipo de Estudio :</td><td>
                                    <select class="letrap" name='Base' <?= $bloqueado ?>>
                                        <option value='Individual'>Individual</option>
                                        <option value='Asociado'>Asociado</option>
                                        <option value='Agrupado'>Agrupado</option>
                                        <option value='Mixto'>Mixto</option>
                                        <option value='Combinado'>Combinado</option>
                                        <option selected value='<?= $Cpo[base] ?>'><?= $Cpo[base] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px" class="ssbm"><td class='letrap' align='right'>Consentimiento Inf :</td><td>
                                    <select class="letrap" name='Consentimiento' <?= $bloqueado ?>>
                                        <option value='Si'>Si</option>
                                        <option value='No'>No</option>
                                        <option selected value='<?= $Cpo[consentimiento] ?>'><?= $Cpo[consentimiento] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="ssbm">
                                <td height="40px" align="center" class="letrap">
                                    <?php
                                    if ($Cpo[bloqbas] == 'Si') {
                                        ?>
                                        <a class="edit" href="estudiose.php?Op=ab&busca=<?= $busca ?>">
                                            Abrir <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color: #CC0000"></i>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <a class="edit" href="estudiose.php?Op=cr&busca=<?= $busca ?>">
                                            Cerrar <i class="fa fa-unlock fa-2x" aria-hidden="true" style="color: green"></i>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td height="35px" align="center">
                                <?php 
                                if ($busca == "Nuevo") {
                                ?>    
                                    <input class="letrap" type="submit" value='Nuevo' name='bt' id="bt"></input>
                                <?php
                                }else{
                                ?>
                                    <?php
                                        if ($Cpo[bloqbas] == 'No') {
                                    ?>
                                    <input class="letrap" type="submit" value='Actualizar' name='bt' id="bt"></input>
                                    <input type="hidden" value="<?= $busca ?>" name="busca"></input>         
                                    <?php
                                        }
                                    ?>
                                <?php
                                }
                                ?>
                                </td>
                            </tr>
                            </form>
                        </table>
                    </td>
                    <td valign='top' width='45%'>
                        <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                            <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                                <tr style="background-color: #2c8e3c"><td class='letratitulo'align="center" colspan="2">.:: Sinonimos ::.</td></tr>
                                <tr class="letrap" style="height: 35px;">
                                    <td colspan="2" class="ssbm">
                                        &nbsp; Sinonimos :
                                        <input type='text' class='cinput'  name='Sinonimos' MAXLENGTH='30' <?= $bloqueado ?>></input>
                                        <input class="letrap" type="submit" value='Agregar_Sinonimo' name='bt' <?= $bloqueado ?>></input>
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                    </td>
                                </tr>
                                <?php
                                $Sql = "SELECT * FROM ests WHERE estudio='$busca' order by id";
                                $cSub = mysql_query($Sql);
                                while ($rg = mysql_fetch_array($cSub)) {
                                    (($nRng % 2) > 0) ? $Fdo = 'FFFFFF' : $Fdo = 'DDE8FF';
                                    ?>
                                    <tr class="letrap" bgcolor='<?= $Fdo ?>' onMouseOver=this.style.backgroundColor='b7e7a7';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='<?= $Fdo ?>';>

                                        <td align="center">
                                            <?=
                                            $rg[descripcion];
                                            ?>
                                        </td>
                                        <?php
                                            if ($Cpo[bloqbas] == 'No') {
                                        ?>
                                            <td>
                                                <a name="dt" value="dtsnm" href="estudiose.php?dt=yes&Desc=<?= $rg[descripcion] ?>&busca=<?= $busca ?>">
                                                    <i class="fa fa-trash-o fa-2x" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        <?php
                                            }else{
                                        ?>
                                            <td>
                                                -
                                            </td>
                                        <?php
                                            }
                                        ?>
                                    </tr>
                                    <?php
                                    $nRng++;
                                }
                                ?>
                            </table>
                        </form>
                        <?php
                        TablaDeLogs("/Estudios/DatosBasicos/", $busca);
                        ?>
                    </td>
                    <td valign = 'top' width = "22%">
                        <?php 
                        if ($busca == "Nuevo") {
                        ?>    
                        <table border='0' width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #fff;position:relative;'>
                            <tr>
                                <td class="ssbm" bgcolor='#6580A2'>
                                    Datos Basicos
                                </td>
                            </tr>
                        </table>
                        <br></br>
                        <table>
                            <tr>
                                <td>
                                    <a href="javascript:window.close()" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                                </td>
                            </tr>
                        </table>
                        <?php
                        }else{
                        ?>
                            <?php
                            $sbmn = 'Basicos';
                            Sbmenu();
                            ?>
                        <?php
                        }
                        ?>
                    </td>
                </tr>   
            </table>  
    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#Estudio").prop("required", true);
            $("#QuitaEstudio").hide();
            if ("<?= $busca ?>" == "Nuevo") {
                $("#Estudio").removeAttr("required");
                $("#bt").val("Nuevo");
                $("#QuitaEstudio").show();
                $("#critico").show();
            }
            $("#Depto").val("<?= $Cpo[depto] ?>");
            $("#Subdepto").val("<?= $Cpo[subdepto] ?>");
        });

    </script>
</html>
<?php
mysql_close();
?>
