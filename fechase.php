<?php require ("./Services/FechasServices.php"); ?>
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
        <section>
            <form name='form1' method='get' action="<?= $_SERVER['PHP_SELF'] ?>" onSubmit='return ValidaCampos();'>
                <table class="PrincipalTable">    
                    <tr>
                        <td colspan="3" class="Title">..:: Informacion Principal ::..</td>
                    </tr>
                    <tr id="Info">
                        <td>
                            <table class="SubTablas">  
                                <tr><td colspan="2">..:: Detalle ::..</td></tr>
                                <tr>
                                    <td>Fecha :</td>
                                    <td><input type='date' name='Fecha' id="Fecha"/></td>
                                </tr>
                                <tr>
                                    <td>Observaciones : </td>
                                    <td><textarea id="Observaciones" name="Observaciones" rows="2" cols="80"></textarea></td>
                                </tr>
                                <tr>
                                    <td>Empleado :</td>
                                    <td>
                                        <input name="Empleado" id="Empleado" type="text" placeholder="Nombre del empleado"></input>
                                        <script src="js/jquery-1.8.2.min.js"></script>
                                        <script src="jquery-ui/jquery-ui.min.js"></script>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                $('#Empleado').autocomplete({
                                                    source: function (request, response) {
                                                        $.ajax({
                                                            url: "autocompleteempleado.php",
                                                            datoType: "json",
                                                            data: {q: request.term, k: "pst"},
                                                            success: function (data) {
                                                                response(JSON.parse(data));
                                                                console.log(JSON.parse(data));
                                                            }
                                                        });
                                                    },
                                                    minLength: 1
                                                });
                                            });
                                        </script>
                                        <?=$cSql["nombre"]?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tipo :</td>
                                    <td>
                                        <select name="Tipo" id="Tipo" class="letrap">
                                            <option value="Cumple">Cumpleaños</option>
                                            <option value="Aniversario">Aniversario</option>
                                            <option value="Otros">Otros</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                                $Value = is_numeric($busca) ? "Actualiza" : "Agregar";
                                ?>
                                <tr>
                                    <td>
                                        <input type="submit" name="Boton" value="<?= $Value ?>"/>
                                        <input type="hidden" name="busca" value="<?= $busca ?>"/>

                                    </td>
                                </tr>
                                <tr><td colspan="2"></td></tr>
                            </table>  
                        </td>
                        <td>
                            <?php TablaDeLogs("/R.Humanos/Fechas Importantes/", $busca); ?>
                            <a href="fechas.php" class="content5" ><i class="fa fa-reply fa-2x" aria-hidden="true"></i> Regresar </a>
                        </td>
                    </tr>      
                </table>
            </form>
        </section>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#Fecha").val("<?= $cSql[fecha] ?>");
                $("#Observaciones").val("<?= $cSql[observaciones] ?>");
                $("#Empleado").val("<?= $cSql[empleado] ?>");
                $("#Tipo").val("<?=$cSql[tipo]?>");
            });
        </script>
    </body>
    <?php footer(); ?>
    <script src="./controladores.js"></script>
</html>
<?php mysql_close(); ?>

