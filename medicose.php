<?php require ("./Services/MedicoseServices.php"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Medicos - Info. Personal</title>
        <?php require ("./config_add.php"); ?>
    </head>
    <body>
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
        <script src="./controladores.js"></script>
    <?php
        ?>
        <section>
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <table class="PrincipalTable">    
                    <tr>
                        <td colspan="3" class="Title">..:: Informacion Principal de <?= ucwords(strtolower($Cpo[nombrec])) ?> ::..</td>
                    </tr>
                    <tr id="Info">
                        <td>
                            <table class="SubTablas">  
                                <tr><td colspan="2">..:: Detalle de Medico ::..</td></tr>
                                <tr>
                                    <td>Medico : </td>
                                    <td><?= $Cpo[nombrec] ?> Id : <?= $Cpo[id] ?></td>
                                </tr>
                                <tr>
                                    <td>Apellido paterno :</td>
                                    <td><input type='text'  name='Apellidop' value='<?= $Apellidop ?>' /></td>
                                </tr>
                                <tr>
                                    <td>Apellido materno : </td>
                                    <td class="Inpt"><input type='text' name='Apellidom' value='<?= $Apellidom ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Nombre : </td>
                                    <td class="Inpt"><input type='text' name='Nombre' value='<?= $Nombre ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Clave Medico : </td>
                                    <td class="Inpt"><input type='text' name='Medico' value='<?= $Medico ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Fecha de Alta : </td>
                                    <td>
                                        <input type='date' name='Fechaa' value='<?= $Cpo["fechaa"] ?>' disabled/> 
                                        Usr Alta: <?= $Cpo["usr"] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fecha de Nacimiento : </td>
                                    <td><input type='date' name='Fechanac' value='<?= $Fechanac ?>'/></td>
                                </tr>
                                <tr>
                                    <td>RFC :</td>
                                    <td><input type='text' name='Rfc' value='<?= $Rfc ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Cedula :</td>
                                    <td><input type='text' name='Cedula' value='<?= $Cedula ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Especialidad : </td>
                                    <td><input type='text' name='Especialidad' value='<?= $Especialidad ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Subespecialidad :</td>
                                    <td><input type='text'  name='Subespecialidad' value='<?= $Subespecialidad ?>'/></td>
                                </tr>
                                <tr><td colspan="2"></td></tr>
                            </table>  
                        </td>
                        <td>
                            <table class="SubTablas">
                                <tr><td colspan="2">.:: Contacto ::.</td></tr>
                                <tr>
                                    <td>Estado :</td>
                                    <td>
                                        <select name='Estado'>
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
                                        </select>
                                        <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                        &nbsp; <input type='submit' name='est' value='Enviar Estado'></input>
                                    </td>
                                </tr>
                                <?php
                                if ($_REQUEST["Munconsultorio"] <> '') {
                                    $Munconsultorio = $_REQUEST["Munconsultorio"];
                                } else {
                                    $Munconsultorio = $Cpo["munconsultorio"];
                                }

                                $MpioA = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");
                                ?>
                                <tr>
                                    <td>Municipio :</td>
                                    <td>
                                        <select name='Munconsultorio'>
                                            <?php
                                            while ($Mpio = mysql_fetch_array($MpioA)) {
                                                echo "<option value='$Mpio[municipio]'>$Mpio[municipio]</option>";
                                            }
                                            echo "<option selected value='$Munconsultorio'>$Munconsultorio</option>";
                                            ?>
                                        </select>
                                        &nbsp; <input type='submit' name='Mun' value='Enviar Municipio'></input>
                                    </td>
                                </tr>
                                <?php
                                if ($_REQUEST["Locconsultorio"] <> '') {
                                    $Locconsultorio = $_REQUEST["Locconsultorio"];
                                } else {
                                    $Locconsultorio = $Cpo["locconsultorio"];
                                }

                                $LocalidA = mysql_query("SELECT colonia FROM estados WHERE estado = '$Estado' and municipio='$Munconsultorio' GROUP BY colonia ORDER BY colonia");
                                ?>
                                <tr>
                                    <td>Colonia :</td>
                                    <td>
                                        <select name='Locconsultorio'>
                                            <?php
                                            while ($Localid = mysql_fetch_array($LocalidA)) {
                                                echo "<option value='$Localid[colonia]'>$Localid[colonia]</option>";
                                            }
                                            echo "<option selected value='$Locconsultorio'>$Locconsultorio</option>";
                                            ?>
                                        </select>
                                        &nbsp; <input type='submit' name='Mun' value='Enviar Colonia'></input>
                                    </td>
                                </tr>
                                <?php
                                if ($Cpo["codigo"] <> '') {
                                    $Cp = $Cpo["codigo"];
                                } else {
                                    $Cp = $_REQUEST["Codigo"];
                                }
                                if ($_REQUEST[Mun] == "Enviar Municipio") {
                                    $MpioA = mysql_query("SELECT colonia,codigo FROM estados WHERE municipio = '$Munconsultorio' GROUP BY municipio ORDER BY colonia");
                                    $Mpio = mysql_fetch_array($MpioA);
                                    $Cp = $Mpio[codigo];
                                } else {
                                    $MpioA = mysql_query("SELECT colonia,codigo FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY colonia");
                                }
                                ?>
                                <tr>
                                    <td>Codigo postal :</td>
                                    <td><input type='text' name='Codigo' value='<?= $Cp ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Direccion :</td>
                                    <td><input type='text' name='Dirconsultorio' value='<?= $Dirconsultorio ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Telefono :</td>
                                    <td><input type='text' name='Telconsultorio' value='<?= $Telconsultorio ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Celular :</td>
                                    <td><input type='text' name='Telcelular' value='<?= $Telcelular ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Correo Electronico :</td>
                                    <td><input type='text' name='Mail' value='<?= $Mail ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Ubicacion : </td>
                                    <td><textarea name='Refubicacion' type='text' rows='3' cols='45'><?= $Refubicacion ?></textarea></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <?php
                                        if ($busca == "NUEVO") {
                                            ?>
                                            <input class="letrap" type="submit" value='NUEVO' name='bt'></input>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="submit" value='Actualizar' name='bt'></input>
                                            <input type="hidden" value="<?= $busca ?>" name="busca"></input>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <?php TablaDeLogs("/Catalogos/Medicos/Info. Personal", $busca); ?>
                        </td>
                        <td>
                            <?php
                            SbmenuMed();
                            ?>
                        </td>
                    </tr>      
                </table>
            </form>
        </section>
    </body>
    <?php footer(); ?>
</html>
<?php mysql_close(); ?>

