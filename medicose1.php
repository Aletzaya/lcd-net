<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link = conectarse();
$busca = $_REQUEST[busca];
//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
#Saco los valores de las sessiones los cuales no cambian;
$Gusr = $_SESSION[Usr][0];
$Gcia = $_SESSION[Usr][1];
$Gnomcia = $_SESSION[Usr][2];
$Gnivel = $_SESSION[Usr][3];
$Gteam = $_SESSION[Usr][4];
$Gmenu = $_SESSION[Usr][5];
$Fecha = date("Y-m-d H:m:s");
$Fechamod = date("Y-m-d");
#Variables comunes;
$msj = $_REQUEST[msj];
$Titulo = "Ordenes de estudio";
if ($_REQUEST[bt] == "NUEVO") {
    echo "ENTRA";
    $sql = "SELECT medico FROM med;";
    $cSQL = mysql_query($sql);
    $repetido = TRUE;
    while ($Rs = mysql_fetch_array($cSQL)) {
        if ($Rs["medico"] === $_REQUEST[Medico]) {
            $Msj = "Medico $_REQUEST[Medico] ya esta ingresado en el sistema verificar bien su nueva clave.";
            header("Location: medicos.php?Msj=$Msj");
            $repetido = FALSE;
        }
    }
    if ($repetido) {

        $nombrec = $_REQUEST[Apellidop] . " " . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];

        $sql = "INSERT INTO med (apellidop,apellidom,nombre,nombrec,fechaa,fechanac,rfc,cedula,especialidad,subespecialidad,medico,buscador,estado,munconsultorio,locconsultorio,codigo,dirconsultorio,telconsultorio,telcelular,mail,refubicacion,usr) VALUES ('$_REQUEST[Apellidop]','$_REQUEST[Apellidom]','$_REQUEST[Nombre]','$nombrec','$Fechamod','$_REQUEST[Fechanac]','$_REQUEST[Rfc]','$_REQUEST[Cedula]','$_REQUEST[Especialidad]','$_REQUEST[Subespecialidad]','$_REQUEST[Medico]','','$_REQUEST[Estado]','$_REQUEST[Munconsultorio]','$_REQUEST[Locconsultorio]','$_REQUEST[Codigo]','$_REQUEST[Dirconsultorio]','$_REQUEST[Telconsultorio]','$_REQUEST[Telcelular]','$_REQUEST[Mail]','$_REQUEST[Refubicacion]','$Gusr');";

        echo $sql;
        if (mysql_query($sql)) {
            $msj = "¡Registro ingresado con exito!";
            $cId = mysql_insert_id();
            $bs = $cId . " " . $nombrec . "" . $_REQUEST[Medico];
            $sql = "UPDATE med SET buscador='$bs' WHERE id=$cId";
            if (mysql_query($sql)) {
                header("Location: medicose1.php?busca=$cId&msj=$msj");
            } else {
                $msj = "Error en sintaxis MYSQL : $sql";
            }
        } else {
            $msj = "Error en sintaxis MYSQL : $sql";
        }
    }
} elseif ($_REQUEST[bt] == "Actualizar") {
    $nombrec = $_REQUEST[Apellidop] . " " . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];

    $sql = "UPDATE med SET apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',nombre='$_REQUEST[Nombre]',fecmod='$Fechamod',fechanac='$_REQUEST[Fechanac]',rfc='$_REQUEST[Rfc]',cedula='$_REQUEST[Cedula]',especialidad='$_REQUEST[Especialidad]',subespecialidad='$_REQUEST[Subespecialidad]',nombrec='$nombrec',medico='$_REQUEST[Medico]',estado='$_REQUEST[Estado]',munconsultorio='$_REQUEST[Munconsultorio]',locconsultorio='$_REQUEST[Locconsultorio]',codigo='$_REQUEST[Codigo]',dirconsultorio='$_REQUEST[Dirconsultorio]',telconsultorio='$_REQUEST[Telconsultorio]',telcelular='$_REQUEST[Telcelular]',mail='$_REQUEST[Mail]',refubicacion='$_REQUEST[Refubicacion]',usrmod='$Gusr' WHERE id=$busca";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Medicos/Info. Personal/Actualiza Detalle del medico'
            ,'med','$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $msj = "Cambio ejecutado con exito";
    header("Location: medicose1.php?busca=$busca&msj=$msj");
}
$Msj = $_REQUEST[Msj];
$CpoA = mysql_query("SELECT * FROM med WHERE id = '$busca'");
$Cpo = mysql_fetch_array($CpoA);

if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
    $Apellidop = $_REQUEST[Apellidop];
    $Apellidom = $_REQUEST[Apellidom];
    $Nombre = $_REQUEST[Nombre];
    $Medico = $_REQUEST[Medico];
    $Fechanac = $_REQUEST[Fechanac];
    //$Fechaa = $_REQUEST[Fechaa];
    $Dirconsultorio = $_REQUEST[Dirconsultorio];
    $Telconsultorio = $_REQUEST[Telconsultorio];
    $Telcelular = $_REQUEST[Telcelular];
    $Mail = $_REQUEST[Mail];
    $Refubicacion = $_REQUEST[Refubicacion];
    $Cedula = $_REQUEST[Cedula];
    $Rfc = $_REQUEST[Rfc];
    $Especialidad = $_REQUEST[Especialidad];
    $Subespecialidad = $_REQUEST[Subespecialidad];
    if($busca == "NUEVO"){
            $busca = "NUEVO";
    }else{
            $busca = $busca;
    }
} else {
    $Estado = $Cpo[estado];
    $Apellidop = $Cpo[apellidop];
    $Apellidom = $Cpo[apellidom];
    $Nombre = $Cpo[nombre];
    $Medico = $Cpo[medico];
    $Fechanac = $Cpo[fechanac];
    //$Fechaa = $Cpo[fechaa];
    $Dirconsultorio = $Cpo[dirconsultorio];
    $Telconsultorio = $Cpo[telconsultorio];
    $Telcelular = $Cpo[telcelular];
    $Mail = $Cpo[mail];
    $Refubicacion = $Cpo[refubicacion];
    $Rfc = $Cpo[rfc];
    $Cedula = $Cpo[cedula];
    $Especialidad = $Cpo[especialidad];
    $Subespecialidad = $Cpo[subespecialidad];
}

require ("config.php");          //Parametros de colores;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Medicos - Info. Personal</title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombrec]))?> ::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='440' width='45%'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Detalle de Medico ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Medico : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombrec' value='<?= $Cpo[nombrec] ?>' MAXLENGTH='60' size='35' disabled> Id : <?= $Cpo[id] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Apellido paterno : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Apellidop' value='<?= $Apellidop ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Apellido materno : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Apellidom' value='<?= $Apellidom ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Nombre : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombre' value='<?= $Nombre ?>' MAXLENGTH='30'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Clave Medico : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Medico' value='<?= $Medico ?>' MAXLENGTH='30' >
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Fecha de Alta : 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput' name='Fechaa' value='<?= $Cpo[fechaa] ?>' disabled = 'true'> Usr Alta: <?= $Cpo[usr] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Fecha de Nacimiento : 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput' name='Fechanac' value='<?= $Fechanac ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    RFC : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Rfc' value='<?= $Rfc ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Cedula : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Cedula' value='<?= $Cedula ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Especialidad : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Especialidad' value='<?= $Especialidad ?>'>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Subespecialidad : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Subespecialidad' value='<?= $Subespecialidad ?>'>
                                </td>
                            </tr>
                        </table>  
                    </td>
                    <td valign='top' width='45%'>
                            <table width='99%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>
                                <tr style="background-color: #2c8e3c">
                                    <td class='letratitulo'align="center" colspan="2">
                                        .:: Contacto ::.
                                    </td>
                                </tr>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Estado : 
                                    </td>
                                    <td class="Inpt">
                                        <SELECT class='cinput' name='Estado'>
                                            <option value='Aguascalientes'>Aguascalientes</option>
                                            <option value='Baja California'>Baja California</option>
                                            <option value='Campeche'>Campeche</option>
                                            <option value='Chiapas'>Chiapas</option>
                                            <option value='Chihuahua'>Chihuahua</option>
                                            <option value='Coahuila'>Coahuila</option>
                                            <option value='Colima'>Colima</option>
                                            <option value='Distrito Federal'>Distrito Federal</option>
                                            <option value='Durango'>Durango</option>
                                            <option value='Guanajuato'>Guanajuato</option>
                                            <option value='Guerrero'>Guerrero</option>
                                            <option value='Hidalgo'>Hidalgo</option>
                                            <option value='Jalisco'>Jalisco</option>
                                            <option value='Estado de Mexico'>Estado de Mexico</option>
                                            <option value='Michoacan'>Michoacan</option>
                                            <option value='Morelos'>Morelos</option>
                                            <option value='Nayarit'>Nayarit</option>
                                            <option value='Nuevo Leon'>Nuevo Leon</option>
                                            <option value='Oaxaca'>Oaxaca</option>
                                            <option value='Queretaro'>Queretaro</option>
                                            <option value='Quintana Roo'>Quintana Roo</option>
                                            <option value='San Luis Potosi'>San Luis Potosi</option>
                                            <option value='Sinaloa'>Sinaloa</option>
                                            <option value='Sonora'>Sonora</option>
                                            <option value='Tabasco'>Tabasco</option>
                                            <option value='Tlaxcala'>Tlaxcala</option>
                                            <option value='Veracruz'>Veracruz</option>
                                            <option selected value='<?= $Estado ?>'><?= $Estado ?></option>
                                            <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                            &nbsp; <input class='Inpt' type='submit' name='est' value='Enviar Estado'></input>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                                if ($_REQUEST[Munconsultorio] <> '') {
                                    $Munconsultorio = $_REQUEST[Munconsultorio];
                                } else {
                                    $Munconsultorio = $Cpo[munconsultorio];
                                }

                                $MpioA = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");
                                ?>
                                <tr>
                                    <td align="right" class="Inpt">
                                        Municipio : 
                                    </td>
                                    <td class="Inpt">
                                        <SELECT class='cinput' name='Munconsultorio'>
                                            <?php
                                            while ($Mpio = mysql_fetch_array($MpioA)) {
                                                echo "<option class='cinput' value='$Mpio[municipio]'>$Mpio[municipio]</option>";
                                            }
                                            echo "<option class='cinput' selected value='$Munconsultorio'>$Munconsultorio</option>";
                                            ?>
                                            &nbsp; <input class='Inpt' type='submit' name='Mun' value='Enviar Municipio'></input>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                                if ($_REQUEST[Locconsultorio] <> '') {
                                    $Locconsultorio = $_REQUEST[Locconsultorio];
                                } else {
                                    $Locconsultorio = $Cpo[locconsultorio];
                                }

                                $LocalidA = mysql_query("SELECT colonia FROM estados WHERE estado = '$Estado' and municipio='$Munconsultorio' GROUP BY colonia ORDER BY colonia");
                                ?>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Colonia : 
                                    </td>
                                    <td class="Inpt">
                                        <SELECT class='cinput' name='Locconsultorio'>
                                            <?php
                                            while ($Localid = mysql_fetch_array($LocalidA)) {
                                                echo "<option class='cinput' value='$Localid[colonia]'>$Localid[colonia]</option>";
                                            }
                                            echo "<option class='cinput' selected value='$Locconsultorio'>$Locconsultorio</option>";
                                            ?>
                                            &nbsp; <input class='Inpt' type='submit' name='Mun' value='Enviar Colonia'></input>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                                if ($Cpo[codigo] <> '') {
                                    $Cp = $Cpo[codigo];
                                } else {
                                    $Cp = $_REQUEST[Codigo];
                                }
                                if ($_REQUEST[Mun] == "Enviar Municipio") {
                                    $MpioA = mysql_query("SELECT colonia,codigo FROM estados WHERE municipio = '$Munconsultorio' GROUP BY municipio ORDER BY colonia");
                                    $Mpio = mysql_fetch_array($MpioA);
                                    $Cp = $Mpio[codigo];
                                } else {
                                    $MpioA = mysql_query("SELECT colonia,codigo FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY colonia");
                                }
                                ?>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Codigo postal : 
                                    </td>
                                    <td class="Inpt">
                                        <input type='text' style="width: 60px" class='cinput'  name='Codigo' value='<?= $Cp ?>'>
                                    </td>
                                </tr>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Direccion : 
                                    </td>
                                    <td class="Inpt">
                                        <input type='text' class='cinput' style="width: 300px" name='Dirconsultorio' value='<?= $Dirconsultorio ?>'>
                                    </td>
                                </tr>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Telefono : 
                                    </td>
                                    <td class="Inpt">
                                        <input type='text' style="width: 90px" class='cinput'  name='Telconsultorio' value='<?= $Telconsultorio ?>'>
                                    </td>
                                </tr>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Celular : 
                                    </td>
                                    <td class="Inpt">
                                        <input type='text' style="width: 90px" class='cinput' name='Telcelular' value='<?= $Telcelular ?>'>
                                    </td>
                                </tr>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Correo Electronico : 
                                    </td>
                                    <td class="Inpt">
                                        <input type='text' class='cinput' size='30' name='Mail' value='<?= $Mail ?>'>
                                    </td>
                                </tr>
                                <tr style="height: 30px">
                                    <td align="right" class="Inpt">
                                        Ubicacion : 
                                    </td>
                                    <td class="Inpt">
                                        <textarea class='cinput' name='Refubicacion' type='text' rows='3' cols='45'><?= $Refubicacion ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="35px" align="center" colspan="2">
                                        <?php
                                        if ($busca == "NUEVO") {
                                            ?>
                                            <input class="letrap" type="submit" value='NUEVO' name='bt'></input>
                                            <?php
                                        } else {
                                            ?>
                                            <input class="letrap" type="submit" value='Actualizar' name='bt'></input>
                                            <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </form>
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
                                            <td>
                                                <b>&nbsp; Id</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Fecha</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Usuario</b>
                                            </td>
                                            <td>
                                                <b>&nbsp; Accion</b>
                                            </td>
                                        </tr>
                                        <?php
                                        $sql = "SELECT * FROM log 
                                                WHERE accion like ('/Catalogos/Medicos/Info. Personal%') 
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
                        <?php
                        SbmenuMed1();
                        ?>
                        <a class="cMsj">
                            <?= $msj ?>
                        </a>
                    </td>
                </tr>      
            </table>
    </body>
</html>