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
$msj = $_REQUEST[msj];
if ($_REQUEST[bt] == "Actualizar") {
    $Nombrec = $_REQUEST[Apellidop] . "" . $_REQUEST[Apellidom] . " " . $_REQUEST[Nombre];
    $sql = "UPDATE cli SET nombrec='$Nombrec',apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',nombre='$_REQUEST[Nombre]'"
            . ",sexo='$_REQUEST[Sexo]', fechan='$_REQUEST[Fechan]' WHERE cliente = $busca;";
    echo $sql;

    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis SQL : " . $sql;
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Clientes/Info. Personal/Edita Detalle del paciente','cli', "
            . "'$Fecha',$busca)";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    }
    $msj = "Cambio ejecutado con exito";
    header("Location: clientesedit.php?busca=$busca&msj=$msj");
} elseif ($_REQUEST[bt] == "Actualiza contacto") {
    $sql = "UPDATE cli SET estado='$_REQUEST[Estado]', municipio = '$_REQUEST[Municipio]', localidad = '$_REQUEST[Localidad]', "
            . "direccion = '$_REQUEST[Direccion]',codigo='$_REQUEST[Codigo]', telefono = '$_REQUEST[Telefono]', celular = '$_REQUEST[Celular]', "
            . "mail = '$_REQUEST[Mail]', refubicacion='$_REQUEST[Refubicacion]' WHERE cliente = $busca;";
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis SQL : " . $sql;
    } else {
        $msj = "Cambio ejecutado con exito";
    }
    $sql = "INSERT INTO log (usr,accion,tabla,fecha,cliente) VALUES ('$Gusr','/Catalogos/Clientes/Info. Personal/Edita el Contacto','cli', "
            . "'$Fecha',$busca)";
    echo $sql;
    if (!mysql_query($sql)) {
        $msj = "Error en sintaxis MYSQL : $sql";
    } else {
        $msj = "Cambio ejecutado con exito";
    }
    header("Location: clientesedit.php?busca=$busca&msj=$msj");
}
if($_REQUEST[Idext]){
    header("Location: ordenescone.php?busca=$_REQUEST[Idext]");
}


#Variables comunes;
$CpoA = mysql_query("SELECT * FROM cli WHERE cliente = $busca");
$Cpo = mysql_fetch_array($CpoA);
require ("config.php");          //Parametros de colores;
if ($_REQUEST[Estado] <> '') {
    $Estado = $_REQUEST[Estado];
} else {
    $Estado = $Cpo[estado];
}

$Fechanac  =  $Cpo[fechan];
$Fecha   = date("Y-m-d");
$array_nacimiento = explode ( "-", $Fechanac ); 
$array_actual = explode ( "-", $Fecha ); 
$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

if ($dias < 0) 
{ 
    --$meses; 

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
    switch ($array_actual[1]) { 
           case 1:     $dias_mes_anterior=31; break; 
           case 2:     $dias_mes_anterior=31; break; 
           case 3:  $dias_mes_anterior=28; break;
//                      if (bisiesto($array_actual[0])) 
//                      { 
//                          $dias_mes_anterior=29; break; 
//                      } else { 
//                          $dias_mes_anterior=28; break; 
//                      } 
           case 4:     $dias_mes_anterior=31; break; 
           case 5:     $dias_mes_anterior=30; break; 
           case 6:     $dias_mes_anterior=31; break; 
           case 7:     $dias_mes_anterior=30; break; 
           case 8:     $dias_mes_anterior=31; break; 
           case 9:     $dias_mes_anterior=31; break; 
           case 10:     $dias_mes_anterior=30; break; 
           case 11:     $dias_mes_anterior=31; break; 
           case 12:     $dias_mes_anterior=30; break; 
    } 

    $dias=$dias + $dias_mes_anterior; 
} 
//
//ajuste de posible negativo en $meses 
if ($meses < 0) 
{ 
    --$anos; 
    $meses=$meses + 12; 
} 

if($anos>='110'){
    $Edad='Verificar Fecha de Nacimiento';
}elseif($anos=='0' and $meses=='0'){
    $Edad=$dias.' Dias';
}elseif($anos=='0' and $meses>='1'){
    $Edad=$meses.' Meses';
}elseif($anos>='1'){
    $Edad=$anos .' A&ntilde;os ';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Clientes - Info. Personal</title>
        <link href="estilos.css?var=1.1" rel="stylesheet" type="text/css"/>
        <link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
        <script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
        <script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link href="jquery-ui/jquery-ui.css" rel="stylesheet"></link>
        <link rel='icon' href='favicon.ico' type='image/x-icon' />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body topmargin="1">
        <?php
       // encabezados();
       // menu($Gmenu,$Gusr);
        ?>
        <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
            <table border='0' width='99%' align='center' cellpadding='1' cellspacing='4'>    
                <tr>
                    <td colspan="3" style="background-color: #2c8e3c" width='80%' class='Subt' align='center'>
                        ..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombre])) . ucwords(strtolower($Cpo[apellidop])) ?> ::..
                    </td>
                </tr>
                <tr>
                    <td valign='top' align='center' height='440' width='45%'>
                        <table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>  
                            <tr style="background-color: #2c8e3c">
                                <td class='letratitulo'align="center" colspan="2">
                                    ..:: Detalle de paciente ::..
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td width='45%' align="right" class="Inpt">
                                    Paciente : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombrec' value='<?= $Cpo[nombrec] ?>' MAXLENGTH='30' disabled> Id : <?= $Cpo[id] ?>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Apellido paterno : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Apellidop' value='<?= $Cpo[apellidop] ?>' MAXLENGTH='30' required></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Apellido materno : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Apellidom' value='<?= $Cpo[apellidom] ?>' MAXLENGTH='30' required></input>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Nombre : 
                                </td>
                                <td class="Inpt">
                                    <input type='text' class='cinput'  name='Nombre' value='<?= $Cpo[nombre] ?>' MAXLENGTH='30' required></input>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" class="Inpt">
                                    Genero : 
                                </td>|
                                <td>
                                    <select name='Sexo' class="cinput">
                                        <option value="F">Femenino</option>
                                        <option value="M">Masculino</option>
                                        <option value='<?= $Cpo[sexo] ?>' selected><?= $Cpo[sexo] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 30px">
                                <td align="right" class="Inpt">
                                    Fecha de Nacimiento : 
                                </td>
                                <td class="Inpt">
                                    <input type='date' class='cinput'  name='Fechan' value='<?= $Cpo[fechan] ?>' required> </input>
                                    Edad : <?= $Edad ?>
                                </td>
                            </tr>
                            <tr>
                                <td height="35px" align="center" colspan="2">
                                    <?php
                                    if ($_REQUEST[rg] == "ord") {
                                        ?>
                                        <input type="hidden" value="<?= $_REQUEST[idext] ?>" name="Idext"></input>
                                        <input type="hidden" value="ord" name="rg"></input>
                                        <?php
                                    }
                                    if ($busca == "NUEVO") {
                                        ?>
                                        <input class="letrap" type="submit" value='NUEVO' name='bt'></input>
                                        <input type="hidden" value="<?= $_REQUEST[org] ?>" name="org"></input>
                                        <input type="hidden" value="<?= $_REQUEST[vta] ?>" name="vta"></input>
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
                            </form>
                        </table>  
                    </td>
                    <td valign='top' width='45%'>
                        <form name='form2' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
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
                                            &nbsp; <input class='Inpt' type='submit' name='est' value='Enviar Estado'></input>
                                        </select>
                                    </td>
                                    <?php
                                    if ($_REQUEST[Municipio] <> '') {
                                        $Municipio = $_REQUEST[Municipio];
                                    } else {
                                        $Municipio = $Cpo[municipio];
                                    }

                                    $MpioA = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");
                                    ?>
                                    <tr>
                                        <td align="right" class="Inpt">
                                            Municipio : 
                                        </td>
                                        <td class="Inpt">
                                            <SELECT class='cinput' name='Municipio'>
                                                <?php
                                                while ($Mpio = mysql_fetch_array($MpioA)) {
                                                    echo "<option class='cinput' value='$Mpio[municipio]'>$Mpio[municipio]</option>";
                                                }
                                                echo "<option class='cinput' selected value='$Municipio'>$Municipio</option>";
                                                ?>
                                                &nbsp; <input class='Inpt' type='submit' name='Mun' value='Enviar Municipio'></input>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr style="height: 30px">
                                        <td align="right" class="Inpt">
                                            Colonia : 
                                        </td>
                                        <td class="Inpt">
                                            <input type='text' style="width: 110px" class='cinput'  name='Localidad' value='<?= $Cpo[colonia] ?>'>
                                        </td>
                                    </tr>
                                    <tr style="height: 30px">
                                        <td align="right" class="Inpt">
                                            Direccion : 
                                        </td>
                                        <td class="Inpt">
                                            <input type='text' class='cinput' style="width: 300px" name='Direccion' value='<?= $Cpo[direccion] ?>'>
                                        </td>
                                    </tr>

                                    <script src="js/jquery-1.8.2.min.js"></script>
                                    <script src="jquery-ui/jquery-ui.min.js"></script>
                                    <script type="text/javascript">
                            $(document).ready(function () {
                                $('#estado').autocomplete({
                                    source: function (request, response) {
                                        $.ajax({
                                            url: "sqlaut.php",
                                            datoType: "json",
                                            data: {q: request.term},
                                            success: function (data) {
                                                response(data);
                                            }
                                        });
                                    },
                                    minLength: 2,
                                    select: function (event, ui) {
                                        alert("Selecciono: " + ui.item.label);
                                    }
                                });
                            });
                                    </script>

                                    <tr style="height: 30px">
                                        <td align="right" class="Inpt">
                                            Codigo Postal : 
                                        </td>
                                        <td class="Inpt">
                                            <input type='number' style="width: 90px" class='cinput'  name='Codigo' value='<?= $Cpo[codigo] ?>'>
                                        </td>
                                    </tr>
                                    <tr style="height: 30px">
                                        <td align="right" class="Inpt">
                                            Tel. Casa : 
                                        </td>
                                        <td class="Inpt">
                                            <input type='number' style="width: 90px" class='cinput'  name='Telefono' value='<?= $Cpo[telefono] ?>'>
                                        </td>
                                    </tr>
                                    <tr style="height: 30px">
                                        <td align="right" class="Inpt">
                                            Celular : 
                                        </td>
                                        <td class="Inpt">
                                            <input type='number' style="width: 90px" class='cinput'  name='Celular' value='<?= $Cpo[celular] ?>'>
                                        </td>
                                    </tr>
                                    <tr style="height: 30px">
                                        <td align="right" class="Inpt">
                                            Correo Electronico : 
                                        </td>
                                        <td class="Inpt">
                                            <input type='text' class='cinput'  name='Mail' value='<?= $Cpo[mail] ?>'>
                                        </td>
                                    </tr>
                                    <tr style="height: 30px">
                                        <td align="right" class="Inpt">
                                            Referencia de Ubicacion : 
                                        </td>
                                        <td class="Inpt">
                                            <textarea class="cinput" name="Refubicacion" type="text" rows="3" cols="45"><?= $Cpo[refubicacion] ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="35px" align="center" colspan="2">
                                            <input class="letrap" type="submit" value='Actualiza contacto' name='bt'></input>
                                            <input type="hidden" value="<?= $busca ?>" name="busca"></input>
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
                                                WHERE accion like ('%/Catalogos/Clientes/Info. Personal%') AND cliente=$busca
                                                ORDER BY id DESC LIMIT 6;";
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
                      //  SbmenuCli();
                        ?>
                        <a class="cMsj">
                            <?= $msj ?>
                        </a>
                    </td>
                </tr>      
            </table>  
    </body>
    <script src="./controladores.js"></script>
</html>