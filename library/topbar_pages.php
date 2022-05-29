<?php
global $hideIcon;
$hide = !empty($hideIcon) ? true : false;
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md <?= def_navbar_color ?>">
    <div class="container">
        <a href="#" class="navbar-brand" onclick="location.reload();"> 
            <?php if (!$hide) : ?>
                <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <?php endif; ?>
            <span class="brand-text font-weight-light">Admin V2</span>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Catálogos</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a href="facturacion.php?criteria=ini" class="dropdown-item">Facturacion</a></li>
                        <li><a href="aditivos.php?criteria=ini" class="dropdown-item">Aditivos</a></li>
                        <li><a href="tanques_h.php?criteria=ini" class="dropdown-item">Tanques_h</a></li>
                        <li><a href="cargas.php?criteria=ini" class="dropdown-item">Cargas</a></li>
                        <li><a href="capturas.php?criteria=ini" class="dropdown-item">Captura de pipas</a></li>
                        <li><a href="enviosPemex.php?criteria=ini" class="dropdown-item">Archivos Pemex y Cre</a></li>
                        <li><a href="terminales.php?criteria=ini" class="dropdown-item">Terminales</a></li>
                        <li><a href="comandos.php?criteria=ini" class="dropdown-item">Comandos</a></li>
                        <li><a href="tableros.php?criteria=ini" class="dropdown-item">Tableros</a></li>
                        <li><a href="vales.php?criteria=ini" class="dropdown-item">Vale de combustible</a></li>
                        <li class="dropdown-divider"></li>
                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu dropdown-hover">
                            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Volumetricos</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                                <li><a tabindex="-1" href="volumetricos.php?criteria=ini" class="dropdown-item">Generar archivos</a></li>
                            </ul>
                        </li>
                        <!-- End Level two -->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Configuración</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a href="combustibles.php?criteria=ini" class="dropdown-item">Combustibles</a></li>
                        <li><a href="dispensarios.php?criteria=ini" class="dropdown-item">Dispensarios</a></li>
                        <li><a href="tanques.php?criteria=ini" class="dropdown-item">Tanques</a></li>
                        <li><a href="islas.php?criteria=ini" class="dropdown-item">Islas</a></li>
                        <li><a href="menus.php?criteria=ini" class="dropdown-item">Menus</a></li>
                        <li><a href="turnos.php?criteria=ini" class="dropdown-item">Turnos</a></li>
                        <li><a href="correos.php?criteria=ini" class="dropdown-item">Correos</a></li>
                        <li><a href="query.php?criteria=ini" class="dropdown-item">Querys</a></li>
                        <li><a href="archivos.php?criteria=ini" class="dropdown-item">Archivos</a></li>
                        <li><a href="patrones.php?criteria=ini" class="dropdown-item">Patrones</a></li>
                        <li><a href="lisvalores.php?criteria=ini" class="dropdown-item">Lista de valores</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Parámetros</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a href="parametros.php" class="dropdown-item">Parametros</a></li>
                        <li><a href="variables.php?criteria=ini" class="dropdown-item">Variables</a></li>
                        <li><a href="variablesCorp.php?criteria=ini" class="dropdown-item">Variables Corporativo</a></li>
                        <li><a href="servicios.php?criteria=ini" class="dropdown-item">Servicios</a></li>
                        <li class="dropdown-divider"></li>
                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu dropdown-hover">
                            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Arch. y Facturación</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">                      
                                <li><a tabindex="-1" href="atransmision.php" class="dropdown-item">Archivos de transmisión</a></li>
                                <li><a href="afacturacion.php" class="dropdown-item">Archivos de facturación</a></li>
                                <li><a href="proveedor_pac.php?criteria=ini" class="dropdown-item">Proveedores PAC</a></li>
                            </ul>
                        </li>
                        <!-- End Level two -->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Servicios</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a href="visorServicios.php?criteria=ini" class="dropdown-item">Servicios</a></li>
                        <li><a href=javascript:confirmar("Realmente&nbsp;deseas&nbsp;reiniciar&nbsp;el&nbsp;servidor?","accionesYactualizaciones.php?op=Si");" class="dropdown-item">Reboot</a></li>
                        <li><a href="accionesYactualizaciones.php?op=wiz" class="dropdown-item">Reiniciar wiznet</a></li>
                        <li><a href="accionesYactualizaciones.php?op=desbloquear" class="dropdown-item">Desbloquear posiciones</a></li>
                        <li><a href="accionesYactualizaciones.php?op=bloquear" class="dropdown-item">Bloquear posiciones</a></li>
                        <li><a href="accionesYactualizaciones.php?op=datetime" class="dropdown-item">Enviar Fecha-Hora</a></li>
                        <li class="dropdown-divider"></li>               
                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu dropdown-hover">
                            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><i class="icon fab fa-lg fa-github-alt"></i> GitHub</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">                      
                                <li><a tabindex="-1" href="accionesYactualizaciones.php?op=githubA" class="dropdown-item"><i class="icon fa fa-lg fa-sync-alt"></i> Sincronizar</a></li>
                            </ul>
                        </li>
                        <!-- End Level two -->
                        <li class="dropdown-divider"></li>               
                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu dropdown-hover">
                            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><i class="icon fa fa-lg fa-cloud-upload-alt"></i> Repositorio</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">                      
                                <li><a tabindex="-1" href="repo_local.php" class="dropdown-item"><i class="icon fa fa-lg fa-file-upload"></i> Local</a></li>
                                <li><a href="repo_remoto.php" class="dropdown-item"><i class="icon fa fa-lg fa-file-upload"></i> Remoto</a></li>
                                <li><a href="repo_sql_subir.php" class="dropdown-item"><i class="icon fa fa-lg fa-upload"></i> Subir SQL's</a></li>
                            </ul>
                        </li>
                        <!-- End Level two -->
                        <li class="dropdown-divider"></li>
                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu dropdown-hover">
                            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><i class="icon fa fa-lg fa-cloud-download-alt"></i> Actualizaciones</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">                      
                                <li><a tabindex="-1" href="accionesYactualizaciones.php" class="dropdown-item">Descargar actualizaciones</a></li>
                                <li><a href="respaldos.php?op=srv" class="dropdown-item">Descargar respaldos USB</a></li>
                                <li><a href="respaldoEpsilon.php?op=srve" class="dropdown-item">Restaura respaldo Epsilon</a></li>
                                <li><a href="sincronizaEpsilon.php?op=srvoe" class="dropdown-item">Sincroniza Omicrom-Epsilon</a></li>
                                <li><a href="clonarEpsilon.php?op=srvce" class="dropdown-item">Clona imagen Epsilon</a></li>
                            </ul>
                        </li>
                        <!-- End Level two -->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Consultas</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a href="consultas.php?criteria=ini" class="dropdown-item">Ejecutar SQL</a></li>
                        <li><a href="consultasFavoritos.php?criteria=ini" class="dropdown-item">Favoritos SQL</a></li>
                        <!-- End Level two -->
                        <li class="dropdown-divider"></li>               
                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu dropdown-hover">
                            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><i class="icon fa fa-lg fa-file-invoice"></i> Reportes</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">                      
                                <li><a tabindex="-1" href=javascript:winuni("ReporteFacturacion","reports_facturacion.php?criteria=ini"); class="dropdown-item">Facturacion</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">        
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>-->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-parametros" data-identificador="1">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-slide="true" href="../logout.php" role="button">
                        <i class="fas fa-lg fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- /.navbar -->