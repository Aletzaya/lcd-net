<?php
$Usuario = getSessionUsuario();
$islaVO = getCorteActual();
$estacion = getEstacion();
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar <?= def_siderbar_color ?> elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" onclick="location.reload();">
        <img src="../dist/img/user10-.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $Usuario->getNombre() ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image"><a><i class="icon fa fa-lg fa-clock"></i></a></div>
            <div class="info"><a>Corte #<?= $islaVO->getCorte() . " (" . $islaVO->getStatus() . ")"?></a></div>
        </div>       
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link" href="menu.php"><i class="nav-icon fas fa-desktop"></i>
                        <p>Visor de Posiciones</p></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=javascript:wingral("VisorLogs","visorLogs.php");><i class="nav-icon fas fa-desktop"></i>
                        <p>Visor de Logs</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="remisiones.php?criteria=ini"><i class="nav-icon fas fa-fire-alt"></i>
                        <p>Despachos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cortes.php?criteria=ini"><i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Cortes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php?criteria=ini"><i class="nav-icon fas fa-user-circle"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pagos.php?criteria=ini"><i class="nav-icon fas fa-donate"></i>
                        <p>Pagos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="productos.php?criteria=ini"><i class="nav-icon fas fa-box"></i>
                        <p>Productos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="clientes.php?criteria=ini"><i class="nav-icon fas fa-users"></i>
                        <p>Clientes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mensajes.php?criteria=ini"><i class="nav-icon fas fa-envelope"></i>
                        <p>Mensajes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bitacoras.php?criteria=ini"><i class="nav-icon fas fa-book"></i>
                        <p>Bitácora</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>