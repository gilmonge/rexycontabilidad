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

        <section class="slice pt-5 margin-header bg-section-secondary">
            <div class="container">
                <div class="row">

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Cuentas contables</a>
                                <p class="text-sm text-muted">Listado de cuentas contables</p>
                                <div class="row align-items-center mt-4">
                                    <div class="col-6">
                                        <a href="#" class="btn btn-sm btn-primary">Ir al módulo</a>
                                    </div>
                                    <div class="col-6 text-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Items</a>
                                <p class="text-sm text-muted">Listado de productos o servicios</p>
                                <div class="row align-items-center mt-4">
                                    <div class="col-6">
                                        <a href="#" class="btn btn-sm btn-primary">Ir al módulo</a>
                                    </div>
                                    <div class="col-6 text-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Asientos contables</a>
                                <p class="text-sm text-muted">Listado de movimientos</p>
                                <div class="row align-items-center mt-4">
                                    <div class="col-6">
                                        <a href="#" class="btn btn-sm btn-primary">Ir al módulo</a>
                                    </div>
                                    <div class="col-6 text-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Tipo cambio venta</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-6"></div>
                                    <div class="col-6 text-right">
                                        <span class="h6 font-weight-bolder text-warning">C230,85</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Tipo cambio compra</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-6"></div>
                                    <div class="col-6 text-right">
                                        <span class="h6 font-weight-bolder text-warning">C230,85</span>
                                    </div>
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