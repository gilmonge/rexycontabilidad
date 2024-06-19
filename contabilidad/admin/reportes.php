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
    
	/** moneda */
		$moneda = $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_MONEDAS." WHERE id = 2 "); $moneda=$moneda->fetch();
		$compra = $moneda['compra'];
		$venta = $moneda['venta'];
    /** moneda */
    
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
                                <a href="#" class="d-block h5 mt-3">Asientos</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-12">
                                        <a href="rpt-asientos.php" class="btn btn-sm btn-block btn-primary">Ir al reporte</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Pérdidas y ganancias</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-12">
                                        <a href="rpt-perdidas-ganancias.php" class="btn btn-sm btn-block btn-primary">Ir al reporte</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Balance general</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-12">
                                        <!-- <a href="asientos.php" class="btn btn-sm btn-block btn-primary">Ir al reporte</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Balance comprobación</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-12">
                                        <!-- <a href="asientos.php" class="btn btn-sm btn-block btn-primary">Ir al reporte</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Sumas Cuentas Contables</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-12">
                                        <!-- <a href="asientos.php" class="btn btn-sm btn-block btn-primary">Ir al reporte</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 px-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" class="d-block h5 mt-3">Sumas y Saldos</a>
                                <div class="row align-items-center mt-4">
                                    <div class="col-12">
                                        <!-- <a href="asientos.php" class="btn btn-sm btn-block btn-primary">Ir al reporte</a> -->
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