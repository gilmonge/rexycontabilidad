<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
	/** Inicializaciones */
		@session_start();
		include_once('core/variables_globales.php');
		include_once('core/quick_function.php');
		$Quick_function = new Quick_function;
    /** Inicializaciones */
    
	/** Verifica si esta logueado */
        $eslogueado=$Quick_function->es_logueado();
		if($eslogueado==true){ header('Location: admin/'); }
	/** Verifica si esta logueado */
	
	$referenciaArchivos = $Quick_function->TraerParametro('URI');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contabilidad - Rexy Studios</title>
        <!-- Favicon -->
        <link rel="icon" href="<?php echo $referenciaArchivos; ?>img/favicon.png?v0.0.6" type="image/png">
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/0265b153d4.js" ></script>
        <!-- Quick CSS -->
        <link rel="stylesheet" href="<?php echo $referenciaArchivos; ?>assets/libs/quick-website/css/quick-website.css" id="stylesheet">
        <!-- Rexy Studios CSS -->
        <link rel="stylesheet" href="<?php echo $referenciaArchivos; ?>assets/rexy/css/rexy.css?v0.0.5" id="stylesheet">

    </head>

    <body>

        <section>
            
            <div class="bg-primary position-absolute h-100 top-0 left-0 zindex-100 col-lg-6 col-xl-6 zindex-100 d-none d-lg-flex flex-column justify-content-end" data-bg-size="cover" data-bg-position="center">
                <!-- Cover image -->
                <img src="img/extras/login.jpg" alt="Image" class="img-as-bg">
                <!-- Overlay text -->
                <div class="row position-relative zindex-110 p-5" style="background: rgba(0,0,0,.3);">
                    <div class="col-md-8 text-center mx-auto">
                        <h5 class="h5 text-white mt-3" id="frase_dicho"></h5>
                        <p class="text-white opacity-8" id="frase_autor"></p>
                    </div>
                </div>
            </div>
            <div class="container-fluid d-flex flex-column">
                <div class="row align-items-center justify-content-center justify-content-lg-end min-vh-100">
                    <div class="col-sm-7 col-lg-6 col-xl-6 py-6 py-md-0">
                        <div class="row justify-content-center">
                            <div class="col-11 col-lg-10 col-xl-6">
                                <div>
                                    <div class="mb-5">
                                        <h6 class="h3 mb-1">Bienvenido!</h6>
                                        <p class="text-muted mb-0">
                                            Ingresa para disfrutar.
                                        </p>
                                    </div>
                                    <form method="post" action="core/nologin-kernel.php">
                                        <div class="form-group">
                                            <label class="form-control-label">Usuario</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Usuario" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <label class="form-control-label">Contraseña</label>
                                                </div>
                                                <div class="mb-2">
                                                    <a href="recuperar.php" class="small text-muted text-underline--dashed border-primary" data-toggle="password-text" data-target="#input-password">Recuperar contraseña</a>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                </div>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" autocomplete="off">
                                            </div>
                                            <div class="input-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2" name="remember" autocomplete="off">
                                                    <label class="custom-control-label" for="customCheck2">Recordarme</label>
                                                </div>
                                            </div>
                                            <?php if(DEBUG != "DEVELOP") { ?>
                                            <div class="input-group">
                                                <div class="g-recaptcha" data-sitekey="<?php echo KEY_PUBLICO; ?>"></div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <button type="submit" class="btn btn-block btn-primary">Ingresar</button>
                                        <input type="hidden" name="formaction" value="login">
                                    </form>
                                    <div class="py-3 text-center">
                                        <span class="text-xs text-uppercase"><hr/></span>
                                    </div>
                                    <!-- Alternative login -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a href="#" class="btn btn-block btn-neutral btn-icon mb-3 mb-sm-0">
                                                <img src="img/logo.png" alt="Rexy Studios" class="w-100">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="modal fade right" id="frameModalerror" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-side modal-top-right" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p id="mensaje_notificaciones"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Service Worker -->
        <script>
            if ( navigator.serviceWorker ) {
                navigator.serviceWorker.register('<?php echo $referenciaArchivos; ?>sw.js');
            }
        </script>

        <!-- Google Recaptcha -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <!-- Core JS  -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Quick JS -->
        <script src="<?php echo $referenciaArchivos; ?>assets/libs/quick-website/js/quick-website.js"></script>

        <!-- Rexy Studio JS -->
        <script src="<?php echo $referenciaArchivos; ?>assets/rexy/js/rexy.js"></script>
        
        <script>
            <?php if(isset($_GET['error'])) {
                $mensaje_notificaciones = "";
                if($_GET['error']==1){      $mensaje_notificaciones = "Recuerde el Recaptcha"; }
                else if($_GET['error']==2){ $mensaje_notificaciones = "Error al ingresar"; }
                else if($_GET['error']==3){ $mensaje_notificaciones = "Error al ingresar"; }
                else if($_GET['error']==4){ $mensaje_notificaciones = "Error al ingresar"; }
                else if($_GET['error']==5){ $mensaje_notificaciones = "Error al ingresar"; }
                else if($_GET['error']==6){ $mensaje_notificaciones = "Se ha registrado correctamente, un correo fue enviado con la información"; }
                    
                echo'
                    $("#mensaje_notificaciones").html("'.$mensaje_notificaciones.'");
                    $("#frameModalerror").modal("show");
                ';
            } ?>

            $(document).ready(function () {
                obtener_frase()
            })
            
        </script>
    </body>

</html>