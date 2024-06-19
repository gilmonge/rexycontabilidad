<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
    /* Contiene el head comun de la aplicacion */
    $referenciaArchivos = $Quick_function->TraerParametro("URI");
?>        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contabilidad - Rexy Studios</title>
        <!-- Favicon -->
        <link rel="icon" href="<?php echo $referenciaArchivos; ?>img/favicon.png?v0.0.7" type="image/png">

        <!-- Quick CSS -->
        <link rel="stylesheet" href="<?php echo $referenciaArchivos; ?>assets/libs/quick-website/css/quick-website.css" id="stylesheet">

        <!-- Bootstrap select -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css" id="stylesheet">

        <!-- Data Tables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" id="stylesheet">

        <!-- Bootstrap date time picker -->
        <link rel="stylesheet" href="<?php echo $referenciaArchivos; ?>assets/libs/bootstrap-datetimepicker/css/datepicker3.min.css" id="stylesheet">

        <!-- Formvalidation -->
        <link rel="stylesheet" href="<?php echo $referenciaArchivos; ?>assets/libs/formvalidation-v1.5.0/dist/css/formValidation.min.css" id="stylesheet">
        
        <!-- Rexy Studios CSS -->
        <link rel="stylesheet" href="<?php echo $referenciaArchivos; ?>assets/rexy/css/rexy.css?v0.0.5" id="stylesheet">