<?php require ("./Services/EquiposVyMeServices.php"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Equipos Venta y Mantenimiento</title>
        <meta charset="UTF-8">
        <?php require ("./config_add.php"); ?>
        <script type="text/javascript">
            $(document).ready(function () {
                if ("<?= $Status ?>" == 1) {
                    $("#Status").prop('checked', true);
                }
            });

        </script>
    </head>
    <body>
        <?php
        encabezados();
        menu($Gmenu, $Gusr);
        ?>
            <script src="./controladores.js"></script>

        <section>
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <table class="PrincipalTable">    
                    <tr>
                        <td colspan="3" class="Title">..:: Informacion Principal ::..</td>
                    </tr>
                    <tr id="Info">
                        <td>
                            <table class="SubTablas">  
                                <tr><td colspan="2">..:: Detalle de Proveedor de Venta y Mantenimiento ::..</td></tr>
                                <tr>
                                    <td>Proveedor : </td>
                                    <td><input style="width: 200px;" type='text'  name='Proveedor' value='<?= $Proveedor ?>' /> &nbsp; Id : <?= $Cpo[id] ?></td>
                                </tr>
                                <tr>
                                    <td>Empresa : </td>
                                    <td><input style="width: 200px;" type='text' name='Empresa' value='<?= $Empresa ?>' /></td>
                                </tr>
                                <tr>
                                    <td>Alias :</td>
                                    <td><input type='text'  name='Alias' value='<?= $Alias ?>' /></td>
                                </tr>
                                <tr>
                                    <td>Direccion : </td>
                                    <td class="Inpt"><input style="width: 200px;" type='text' name='Direccion' value='<?= $Direccion ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Colonia : </td>
                                    <td class="Inpt"><input style="width: 200px;" type='text' name='Colonia' value='<?= $Colonia ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Ciudad : </td>
                                    <td><input style="width: 200px;" type='text' name='Ciudad' value='<?= $Cpo["ciudad"] ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Codigo Postal : </td>
                                    <td class="Inpt"><input style="width: 100px;" type='text' name='CodigoPostal' value='<?= $CodigoPostal ?>'/></td>
                                </tr>
                                <tr>
                                    <td>RFC :</td>
                                    <td><input style="width: 100px;" type='text' name='Rfc' value='<?= $Rfc ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Responsable :</td>
                                    <td><input style="width: 200px;" type='text' name='Responsable' value='<?= $Responsable ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Email : </td>
                                    <td><input style="width: 200px;" type='text' name='Email' value='<?= $Email ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Telefonos :</td>
                                    <td><input style="width: 100px;" type='text'  name='Telefono' value='<?= $Telefono ?>'/></td>
                                </tr>
                                <tr>
                                    <td>Tipo de Pago :</td>
                                    <td>
                                        <select name='Tpago' class="cinput">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value='<?= $Tpago ?>' selected><?= $Tpago ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Condiciones de Pago :</td>
                                    <td>
                                        <select name='Condiciones' class="cinput">
                                            <option value="Contado">Contado</option>
                                            <option value="Credito">Credito</option>
                                            <option value='<?= $Condiciones ?>' selected><?= $Condiciones ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tipo de Proveedor :</td>
                                    <td>
                                        <select name='Tproveedor' class="cinput">
                                            <option value="Eq_Rayos_X">Eq_Rayos_X</option>
                                            <option value="Eq_Laboratorio">Eq_Laboratorio</option>
                                            <option value="Electricidad">Electricidad</option>
                                            <option value="Plomeria">Plomeria</option>
                                            <option value="Acabados">Acabados</option>
                                            <option value="Mat_Construc">Mat_Construc</option>
                                            <option value="Carpinteria">Carpinteria</option>
                                            <option value="Recubrimientos">Recubrimientos</option>
                                            <option value="Calefacción">Calefacción</option>
                                            <option value="Serv_Carga_Gas">Serv_Carga_Gas</option>
                                            <option value="Oxigeno_Med">Oxigeno_Med</option>
                                            <option value="Serv_Pipa_Agua">Serv_Pipa_Agua</option>
                                            <option value="Sist_Filtrac_Osmosis">Sist_Filtrac_Osmosis</option>
                                            <option value='<?= $Tproveedor ?>' selected><?= $Tproveedor ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status :</td>
                                    <td><input type='checkbox' class='botonAnimated' name='Status' id="Status"/></td>
                                </tr>
                                <?php $bt = !is_numeric($busca) ? "Nuevo" : "Actualizar"; ?>
                                <tr><td colspan="2"><input type="submit" name="bt" value="<?= $bt ?>"/></td></tr>
                            </table>  
                            <input name="busca" value="<?= $busca ?>" type="hidden"/>
                        </td>
                        <td>
                            <?php TablaDeLogs("/Catalogos/ProveedoresVyM/", $busca); ?>
                            <a href="equiposVyM.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                        </td>
                    </tr>      
                </table>
            </form>
        </section>
    </body>
    <?php footer(); ?>
</html>
<?php mysql_close(); ?>

