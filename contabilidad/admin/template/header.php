<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
    /* Header / menu general para el sistema */
?>
        <header class=" fixed-top" id="header-main">
            <nav class="navbar navbar-main navbar-expand-lg navbar-dark bg-dark" id="navbar-main">
                <div class="container">
                    <a class="navbar-brand" href="index.php"> 
                        <img alt="Rexy Studios" title="Rexy Studios" class="rexy-logo" src="../img/logo-blanco.png" id="navbar-logo"> 
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse navbar-collapse-overlay" id="navbar-main-collapse">
                        <div class="position-relative">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <ul class="navbar-nav ml-lg-auto">
                            <li class="nav-item nav-item-spaced dropdown dropdown-animate" data-toggle="hover"><a class="nav-link" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">Contabilidad</a>
                                <div class="dropdown-menu dropdown-menu-xl p-0">
                                    <div class="row no-gutters">
                                        <div class="col-12 col-lg-6 order-lg-1">
                                            <div class="dropdown-body">
                                                <h6 class="dropdown-header">Contable</h6>

                                                <div class="list-group list-group-flush">
                                                    <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                        <div class="media d-flex"><span class="h6"></span>
                                                            <div class="media-body ml-2"><a href="cuentas_contables.php" class="d-block h6 mb-0">Cuentas contables</a> <small class="text-sm text-muted mb-0">Listado de cuentas contables</small></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="list-group list-group-flush">
                                                    <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                        <div class="media d-flex"><span class="h6"></span>
                                                            <div class="media-body ml-2"><a href="asientos.php" class="d-block h6 mb-0">Asientos contables</a> <small class="text-sm text-muted mb-0">Listado de movimientos</small></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="list-group list-group-flush">
                                                    <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                        <div class="media d-flex"><span class="h6"></span>
                                                            <div class="media-body ml-2"><a href="reportes.php" class="d-block h6 mb-0">Reportes</a> <small class="text-sm text-muted mb-0">Reportes del sistema</small></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 order-lg-2 mt-4 mt-lg-0">
                                            <div class="dropdown-body dropdown-body-right bg-dropdown-secondary h-100">
                                                <h6 class="dropdown-header">Elementos del sistema</h6>
                                                <div class="list-group list-group-flush">
                                                    <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                        <div class="media d-flex"><span class="h6"></span>
                                                            <div class="media-body ml-2">
                                                                <a href="items.php" class="d-block h6 mb-0">Items</a> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                        <div class="media d-flex"><span class="h6"></span>
                                                            <div class="media-body ml-2">
                                                                <a href="clientes.php" class="d-block h6 mb-0">Clientes</a> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                        <div class="media d-flex"><span class="h6"></span>
                                                            <div class="media-body ml-2">
                                                                <a href="proveedores.php" class="d-block h6 mb-0">Proveedores</a> 
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item nav-item-spaced dropdown dropdown-animate" data-toggle="hover"><a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Perfil</a>
                                <div class="dropdown-menu dropdown-menu-md p-0">
                                    <div class="list-group list-group-flush px-lg-4">
                                        <form action="../core/nologin-kernel.php" method="get">
                                            <button type="submit" class="list-group-item list-group-item-action" role="button">
                                                <div class="d-flex">
                                                    <span class="h6">
                                                        <i class="fas fa-door-open"></i>
                                                    </span>
                                                    <div class="ml-3">
                                                        <h6 class="heading mb-0">Cerrar sesión</h6>
                                                    </div>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <!--li class="nav-item nav-item-spaced dropdown dropdown-animate" data-toggle="hover"><a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blog</a>
                                <div class="dropdown-menu dropdown-menu-md p-0">
                                    <div class="list-group list-group-flush px-lg-4">
                                        <a href="../../docs/index.html" class="list-group-item list-group-item-action" role="button">
                                            <div class="d-flex">
                                                <span class="h6">
                                                    <i class="fas fa-code"></i>
                                                </span>
                                                <div class="ml-3">
                                                    <h6 class="heading mb-0">Documentation</h6><small class="text-sm">Quick setup and build tools</small>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="py-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <a href="../../docs/getting-started/quick-start.html" class="dropdown-item">Quick Start</a> 
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="../../docs/getting-started/build-tools.html" class="dropdown-item">Build Tools</a> 
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="../../docs/getting-started/customization.html" class="dropdown-item">Customization</a>
                                                </div>
                                                <div class="col-sm-6"> 
                                                    <a href="../../docs/getting-started/file-structure.html" class="dropdown-item">File Structure</a> 
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="../../docs/components/alerts.html" class="dropdown-item">Components</a>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="../../docs/styleguide/icons.html" class="dropdown-item">Icons</a>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="../../docs/styleguide/svg-icons.html" class="dropdown-item">SVG Icons</a>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="../../docs/styleguide/illustrations.html" class="dropdown-item">Illustrations</a>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="../../docs/plugins/animate.html" class="dropdown-item">Plugins</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush px-lg-4">
                                        <li class="dropdown dropdown-animate dropdown-submenu" data-toggle="hover">
                                            <a href="#" class="list-group-item list-group-item-action dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <div class="media d-flex">
                                                    <figure style="width:20px">
                                                        <i class="far fa-clipboard"></i>
                                                    </figure>
                                                    <div class="media-body ml-2">
                                                        <h6 class="heading mb-0">Boards</h6>
                                                        <p class="mb-0">Account management made cool.</p>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="../../pages/boards/overview.html">Overview </a>
                                                <a class="dropdown-item" href="../../pages/boards/projects.html">Projects </a>
                                                <a class="dropdown-item" href="../../pages/boards/tasks.html">Tasks </a>
                                                <a class="dropdown-item" href="../../pages/boards/integrations.html">Integrations</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li-->
                            
                        </ul>
                        <ul class="navbar-nav align-items-lg-center d-none d-lg-flex ml-lg-auto"></ul>
                    </div>
                </div>
            </nav>
        </header>