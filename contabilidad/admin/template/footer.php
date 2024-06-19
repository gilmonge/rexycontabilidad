<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
    /* Pie del pagina que es comun para el sistema */
?>
        <footer class="position-relative fixed-bottom" id="footer-main">
            <div class="footer pt-lg-7 footer-dark bg-dark">
                <div class="shape-container shape-line shape-position-top shape-orientation-inverse">
                    <svg width="2560px" height="100px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" viewBox="0 0 2560 100" style="enable-background:new 0 0 2560 100" xml:space="preserve" class="fill-section-secondary"><polygon points="2560 0 2560 100 0 100"></polygon></svg>
                </div>
                <div class="container pt-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="row align-items-center">
                                <div class="col-lg-10">
                                    <h5 class="text-secondary mb-2" id="frase_dicho"></h5>
                                </div>
                                <div class="col-lg-2 text-lg-right mt-4 mt-lg-0">
                                    <p class="lead mb-0 text-white opacity-8" id="frase_autor"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="divider divider-fade divider-dark my-5">
                    <div class="row align-items-center justify-content-md-between pb-4">
                        <div class="col-md-6">
                            <div class="copyright text-sm font-weight-bold text-center text-md-left">
                                © 2020 - <?php echo date("Y"); ?> <a href="https://rexystudios.com" class="font-weight-bold" target="_blank">Rexy Studios</a>. Todos los derechos reservados.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <ul class="nav justify-content-center justify-content-md-end mt-3 mt-md-0">
                                <li class="nav-item"><a class="nav-link" href="#">Terms</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">Privacy</a></li>
                                <li class="nav-item"><a class="nav-link" href="librerias.php">Librerías usadas</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div class="modal fade" id="msg_modal" tabindex="-1" role="dialog" aria-labelledby="modal_5" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="py-3 text-center">
                            <i id="msg_signo" class="far fa-4x"></i>
                            <h5 id="msg_titulo" class="heading h4 mt-4"></h5>
                            <p id="msg_texto"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="msg_boton" type="button" class="btn btn-sm btn-white" data-dismiss="modal"></button>
                        <span id="msg_btn_extra"></span>
                    </div>
                </div>
            </div>
        </div>

        
	    <div id="loading-request" class="cover-load" style="display:none;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>
        
        <script type="text/javascript">
            $(document).ready(function () {
                crear_selectpicker()
            })
        </script>