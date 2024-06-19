<?php 
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
    /* contiene las libs js general del sistema */
?>

        <!-- Service Worker -->
        <script>
            if ( navigator.serviceWorker ) {
                navigator.serviceWorker.register('<?php echo $referenciaArchivos; ?>sw.js');
            }
        </script>

        <!-- Core JS  -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/0265b153d4.js" ></script>
        
        <!-- Quick JS -->
        <script src="<?php echo $referenciaArchivos; ?>assets/libs/quick-website/js/quick-website.js"></script>

        <!-- Bootstrap select -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

        <!-- Data Tables -->
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

        <!-- Bootstrap date time picker -->
        <script src="<?php echo $referenciaArchivos; ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo $referenciaArchivos; ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datepicker.es.min.js"></script>

        <!-- Formvalidation -->
        <script src="<?php echo $referenciaArchivos; ?>assets/libs/formvalidation-v1.5.0/dist/js/FormValidation.min.js"></script>
        <script src="<?php echo $referenciaArchivos; ?>assets/libs/formvalidation-v1.5.0/dist/js/plugins/Bootstrap.min.js"></script>
        
        <!-- Rexy Studio JS -->
        <script src="<?php echo $referenciaArchivos; ?>assets/rexy/js/rexy.js"></script>
        <script src="<?php echo $referenciaArchivos; ?>assets/rexy/js/validaciones.js"></script>
        
        <script>
            $(document).ready(function () {
                obtener_frase("<?php echo $referenciaArchivos; ?>")
            })
        </script>