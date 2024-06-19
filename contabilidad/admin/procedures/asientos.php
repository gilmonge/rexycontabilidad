<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
	/** Inicializaciones */
		@session_start();
		include_once('../../core/variables_globales.php');
		include_once('../../core/quick_function.php');
		$Quick_function = new Quick_function;
    /** Inicializaciones */
    
	/** Verifica si esta logueado */
        $eslogueado=$Quick_function->es_logueado();
        if($eslogueado != true){ header('Location: ../'); }
    /** Verifica si esta logueado */

    /* Establece las urls de redireccion */
        $uri=$Quick_function->TraerParametro("URI");
        $url_principal=$uri."admin/asientos.php";
        //$url_edit=$uri."admin/tipo-cuenta-editar.php";
    /* Establece las urls de redireccion */
    
    /* Incluye las librerias */
        require('class_libraries/asientos.php');
        $asientos = new asientos;
    /* Incluye las librerias */

    /* Decide el comportamiento del procedimiento */
        $formaction = (isset($_POST['formaction']))? $_POST['formaction'] : $formaction=$Quick_function->decryptlabel($_GET['formaction']);
    /* Decide el comportamiento del procedimiento */
    
    /* Decision de que metodo usar */
        switch($formaction){
            case "create_DB":
                $asientos->agregaDB($url_principal, $Quick_function, $_POST);
            break;
            case "update_DB":
                $asientos->actualizarDB($url_principal, $Quick_function, $_POST);
            break;
            case "list_lines_DB":
                $asientos->listar_lineasDB($Quick_function, $_POST);
            break;
            case "reverse_DB":
                $asientos->reversar_asientoDB($Quick_function, $_POST);
            break;
            default:
                header('Location: '.$uri);
            break;
        }
    /* Decision de que metodo usar */
    
?>