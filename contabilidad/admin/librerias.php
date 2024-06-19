<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
	/** Inicializaciones */
		@session_start();
		include_once('../core/variables_globales.php');
		include_once('../core/quick_function.php');
		$Quick_function = new Quick_function;
    /** Inicializaciones */
    
	/** Verifica si esta logueado */
        $eslogueado=$Quick_function->es_logueado();
		if($eslogueado!=true){ header('Location: ../'); }
	/** Verifica si esta logueado */
	
	$referenciaArchivos= $Quick_function->TraerParametro('URI');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php include_once("template/head.php") ?>
    </head>

    <body>
        <?php include_once("template/header.php") ?>

        <section class="slice py-5 margin-header bg-section-secondary">
            
            <div class="container">
                <div class="row row-grid align-items-center">

                    <div class="col-xl-4 col-md-6">
                        <div class="card mb-3">
                            <!-- Card body -->
                            <div class="card-body pt-0">

                                <!-- App logo + status -->
                                <div class="d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <div class="icon icon-sm">
                                            <img alt="Image placeholder" src="../img/libs/quick.svg" class="svg-inject" style="width: 70px; max-height: auto;">

                                        </div>
                                    </div>
                                    <div class="text-right">
                                        
                                        <button type="button" class="btn btn-xs btn-neutral btn-icon">
                                            <span class="btn-inner--icon"><i data-feather="plus"></i></span>
                                            <a href="https://webpixels.io/themes/quick-website-ui-kit" target="_blank" class="btn-inner--text ml-1 text-white">Ir a la página</a>
                                        </button>
                                        
                                    </div>
                                </div>

                                <!-- App title -->
                                <div class="mt-3">
                                    <h6 class="mb-0 text-white">Quick</h6>
                                    <p class="mb-0 text-sm text-muted"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card mb-3">
                            <!-- Card body -->
                            <div class="card-body pt-0">

                                <!-- App logo + status -->
                                <div class="d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <div class="icon icon-sm">
                                            <img alt="Image placeholder" src="../img/libs/bootstrap-stack.png" class="svg-inject" style="width: 50px; height: auto;">

                                        </div>
                                    </div>
                                    <div class="text-right">
                                        
                                        <button type="button" class="btn btn-xs btn-neutral btn-icon">
                                            <span class="btn-inner--icon"><i data-feather="plus"></i></span>
                                            <a href="https://getbootstrap.com/" target="_blank" class="btn-inner--text ml-1 text-white">Ir a la página</a>
                                        </button>
                                        
                                    </div>
                                </div>

                                <!-- App title -->
                                <div class="mt-3">
                                    <h6 class="mb-0 text-white">Bootstrap 4</h6>
                                    <p class="mb-0 text-sm text-muted"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card mb-3">
                            <!-- Card body -->
                            <div class="card-body pt-0">

                                <!-- App logo + status -->
                                <div class="d-flex justify-content-between align-items-center p-2">
                                    <div>
                                        <div class="icon icon-sm">
                                            <img alt="Image placeholder" src="../img/libs/jquery.png" class="svg-inject" style="width: 50px; height: auto;">

                                        </div>
                                    </div>
                                    <div class="text-right">
                                        
                                        <button type="button" class="btn btn-xs btn-neutral btn-icon">
                                            <span class="btn-inner--icon"><i data-feather="plus"></i></span>
                                            <a href="https://jquery.com/" target="_blank" class="btn-inner--text ml-1 text-white">Ir a la página</a>
                                        </button>
                                        
                                    </div>
                                </div>

                                <!-- App title -->
                                <div class="mt-3">
                                    <h6 class="mb-0 text-white">JQuery</h6>
                                    <p class="mb-0 text-sm text-muted"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </section>

        <?php include_once("template/libs.php") ?>
        <?php include_once("template/footer.php") ?>
    </body>

</html>