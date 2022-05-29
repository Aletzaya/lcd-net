<?php

use com\softcoatl\utils as utils;

$select = "SELECT * FROM servicios WHERE nombre = '" . PROJECT_NAME . "'";
$Version = utils\IConnection::execSql($select);
$Estacion = getEstacion();
?>
<!-- /.content-wrapper -->
<footer class="main-footer text-sm">    
    <div><?= $Estacion->getCia() ?>. Sucursal: <strong><?= $Estacion->getEstacion() ?> - <?= $Estacion->getIdfae() ?></strong></div>
    <div>
        <strong>Copyright &copy; 2014-2021 Deti Desarrollo y Transferencia de Informática SA de CV.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <strong>Versión</strong> <?= VERSION ?>
        </div>
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->


<div class="modal fade" id="modal-parametros">
    <div class="modal-dialog modal-lg">
        <form name="formParametros" id="formParametros" method="post" action="">
            <div class="modal-content  bg-dark">
                <div class="modal-header">
                    <h4 class="modal-title">Informacion de la estación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">                                    
                    <div class="form-group row override_form_group">
                        <div class="col-sm-4"><label for="Cia">Datos de la estación:</label></div>
                        <div class="col-sm-8"><input type="text" class="form-control override_form_control" name="Cia" id="ParamCia" disabled=""></div>
                    </div>
                    <div class="form-group row override_form_group">
                        <div class="col-sm-4"><label for="Rfc">Rfc:</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control override_form_control" name="Rfc" id="ParamRfc" disabled=""></div>
                        <div class="col-sm-2"><label for="IdFae">Id FAE:</label></div>
                        <div class="col-sm-2"><input type="number" class="form-control override_form_control" name="IdFae" id="ParamIdFae" disabled="" min="0"></div>
                    </div>
                    <div class="form-group row override_form_group">
                        <div class="col-sm-4"><label for="Estacion">Sucursal:</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control override_form_control" name="Estacion" id="ParamEstacion" disabled=""></div>
                        <div class="col-sm-2"><label for="Numestacion"># Estación:</label></div>
                        <div class="col-sm-2"><input type="text" class="form-control override_form_control" name="Numestacion" id="ParamNumestacion" disabled=""></div>
                    </div>
                    <div class="form-group row override_form_group">
                        <div class="col-sm-4"><label for="Direccion">Direccion:</label></div>
                        <div class="col-sm-8"><input type="text" class="form-control override_form_control" name="Direccion" id="ParamDireccion" disabled=""></div>
                    </div>
                    <div class="form-group row override_form_group">
                        <div class="col-sm-4"><label for="Numeroext">Numero exterior e interior:</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control override_form_control" name="Numeroext" id="ParamNumeroext" disabled=""></div>
                        <div class="col-sm-4"><input type="text" class="form-control override_form_control" name="Numeroint" id="ParamNumeroint" disabled=""></div>
                    </div>                                            
                    <div class="form-group row override_form_group">
                        <div class="col-sm-4"><label for="Colonia">Colonia:</label></div>
                        <div class="col-sm-6"><input type="text" class="form-control override_form_control" name="Colonia" id="ParamColonia" disabled=""></div>
                        <div class="col-sm-2"><input type="text" class="form-control override_form_control" name="Codigo" id="ParamCodigo" disabled=""></div>
                    </div>
                    <div class="form-group row override_form_group">
                        <div class="col-sm-4"><label for="Ciudad">Ciudad:</label></div>
                        <div class="col-sm-4"><input type="text" class="form-control override_form_control" name="Ciudad" id="ParamCiudad" disabled=""></div>
                        <div class="col-sm-4"><input type="text" class="form-control override_form_control" name="Estado" id="ParamEstado" disabled=""></div>
                    </div>                    
                </div>
            </div>
            <!-- /.modal-content -->
        </form>
    </div>
</div>
