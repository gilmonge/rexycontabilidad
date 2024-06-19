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

    /* Trae el listado de las monedas del sistema */
        $par= $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_MONEDAS);
        $listado_monedas=[];

        while ($row = $par->fetch()) {
            $listado_monedas[] = $row;
        }
    /* Trae el listado de las monedas del sistema */

    /* Trae el listado de las monedas del sistema */
        $par= $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_ADMINISTRADORES);
        $listado_administradores=[];

        while ($row = $par->fetch()) {
            $listado_administradores[] = $row;
        }
    /* Trae el listado de las monedas del sistema */

    /* Trae el listado de impuestos */
        $IMPUESTOS = json_decode($Quick_function->TraerParametro('IMPUESTOS'), TRUE);
    /* Trae el listado de impuestos */

    /* Trae las cuentas mayores */
        $CTA_MAYORES = json_decode($Quick_function->TraerParametro('CTA_MAYORES'), TRUE);
    /* Trae las cuentas mayores */
    
    /* Trae el listado de cuentas */
        $ctas_contables = $Quick_function->traer_ctas_contables();
    /* Trae el listado de cuentas */
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

                    <div class="card shadow-lg border-0 rexy-card-nuevo" style="max-width: 100%;">
                        <div class="card-body px-5 py-4 text-center text-md-left">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-2">Listado de cuentas contables del sistema</h5>
                                </div>
                                <div class="col-12 col-md-6 mt-4 mt-md-0 text-md-right">
                                    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#AgregarDato" onclick="establecer_agregar()">
                                        Agregar cuenta
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-dark dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filtrar</button>
                                        <div class="dropdown-menu dropdown-menu-dark bg-dark">
                                            <form method="post">
                                                <button class="dropdown-item">Todo</button>
                                                <input type="hidden" name="filtro" value="3">
                                            </form>
                                            <div class="dropdown-divider"></div>
                                            <form method="post">
                                                <button class="dropdown-item">Activos</button>
                                                <input type="hidden" name="filtro" value="0">
                                            </form>
                                            <form method="post">
                                                <button class="dropdown-item">Inactivos</button>
                                                <input type="hidden" name="filtro" value="1">
                                            </form>
                                            <form method="post">
                                                <button class="dropdown-item">Eliminados</button>
                                                <input type="hidden" name="filtro" value="2">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 overflowX">
                        <table class="table" id="listado">
                            <thead class="rexy-encabezado-tabla">
                                <tr>
                                    <th scope="col" class="text-justify">Código</th>
                                    <th scope="col" class="text-justify">Cuenta contable</th>
                                    <th scope="col" class="text-center">Saldo actual</th>
                                    <th scope="col" class="text-center">Naturaleza</th>
                                    <th scope="col" class="text-center">Información</th>
                                    <th scope="col" class="text-center">Estado</th>
                                    <th scope="col" class="text-center">Editar</th>
                                    <th scope="col" class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="rexy-cuerpo-tabla">
                                <?php
                                    /* Trae el listado de los items */
                                        /* echo "<pre>".print_r($ctas_contables, true)."</pre>"; */
                                        foreach ($ctas_contables as $key => $row) {
                                            $padre = ($row['id_padre'] > 0)? 'class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$ctas_contables[$row['id_padre']]['nombre'].'"': 'class="btn rexy-info"';
    
                                            if(isset($row['naturaleza'])){ /* Es cuenta ingresada manual */
                                                $activo_item_info = $activo_item = ($row['activo'] == 1)? 'Activo' : 'Inactivo';
                                                $activo_item_info = ($row['borrado'] == 1)? 'Borrado' : $activo_item_info;
    
                                                /* Realiza el filtro segun el estado de la cuenta */
                                                    $mostrar = true;
                                                    if((!isset($_POST['filtro'])) || (isset($_POST['filtro']) && $_POST['filtro'] == 0)){ /* Activo */
                                                        $mostrar = ($row['activo'] == 1 && $row['borrado'] == 0)? true : false;
                                                    }
                                                    else if(isset($_POST['filtro']) && $_POST['filtro'] == 1){ /* Inactivo */
                                                        $mostrar = ($row['activo'] == 0 && $row['borrado'] == 0)? true : false;
                                                    }
                                                    else if(isset($_POST['filtro']) && $_POST['filtro'] == 2){ /* Borrado */
                                                        $mostrar = ($row['borrado'] == 1)? true : false;
                                                    }
                                                /* Realiza el filtro segun el estado de la cuenta */
                                                
                                                $informacion = htmlentities(json_encode($row));
    
                                                /* Establece el boton de activar */
                                                    $btn = ''; $btn_desactivar = true;
                                                    if($row['activo'] == 1){
                                                        if($row['posee_hijo'] != 0){ /* posee hijos */
                                                            /* revisa si los hijos estan activado los hijos */
                                                                $cta_hijos= $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_CUENTAS_CONTABLES. " WHERE id_padre = ".$row['id']);
                                                                $desactivados = false;
    
                                                                while ($row_hijo = $cta_hijos->fetch()) {
                                                                    $desactivados = ($row_hijo['activo'] == 0 || $row_hijo['borrado'] == 1 )? true : false;
                                                                }
                                                            /* revisa si los hijos estan activado los hijos */
    
                                                            if(!$desactivados){
                                                                $btn_desactivar = false;
                                                            }
                                                        }
                                                        
                                                        $mensaje = (in_array($row['id'], [1,2,3,4,5,6,7,8,9,10]))? 'No se puede desactivar, ya que es una cuenta principal del sistema' : 'No se puede desactivar, ya que posee sub cuentas activos';
    
                                                        $btn = ($btn_desactivar && !in_array($row['id'], [1,2,3,4,5,6,7,8,9,10]))? '
                                                            <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.$activo_item.'" onclick="activarInactivar(\''.$informacion.'\')">
                                                                <i class="far fa-eye"></i> 
                                                            </button>
                                                        ' : '
                                                            <button type="button" class="btn text-underline--dashed" data-toggle="tooltip" data-placement="bottom" title="'.$mensaje.'">
                                                                <i class="far fa-eye"></i>
                                                            </button>
                                                        ';
                                                    }
                                                    else{
                                                        if($row['id_padre'] > 0){
                                                            /* revisa si el padre esta activo */
                                                                $cta_padre= $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_CUENTAS_CONTABLES." WHERE id = ".$row['id_padre']);
                                                                $activados = false;
    
                                                                $row_padre = $cta_padre->fetch();
                                                                $activados = ($row_padre['activo'] == 1 && $row_padre['borrado'] == 0 )? true : false;
                                                            /* revisa si el padre esta activo */
    
                                                            if(!$activados){
                                                                /* padre esta desactivado, no permite activar */
                                                                $btn_desactivar = false;
                                                            }
    
                                                        }
                                                        $btn = ($btn_desactivar && !in_array($row['id'], [1,2,3,4,5,6,7,8,9,10]))? '
                                                            <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.$activo_item.'" onclick="activarInactivar(\''.$informacion.'\')">
                                                                <i class="far fa-eye-slash"></i>
                                                            </button>
                                                        ': '
                                                            <button type="button" class="btn text-underline--dashed" data-toggle="tooltip" data-placement="bottom" title="No se puede activar, ya que la cuenta mayor esta inactivo o eliminado">
                                                                <i class="far fa-eye-slash"></i>
                                                            </button>
                                                        ';
                                                    }
    
                                                /* Establece el boton de activar */
    
                                                /* Establece el boton de borrar */
                                                    $btn_deleted = ''; $btn_eliminar = true;
                                                    if($row['borrado'] == 1){
                                                        if($row['id_padre'] > 0){
                                                            /* revisa si el padre esta activo */
                                                                $cta_padre= $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_CUENTAS_CONTABLES." WHERE id = ".$row['id_padre']);
                                                                $activados = false;
    
                                                                $row_padre = $cta_padre->fetch();
                                                                $activados = ($row_padre['activo'] == 1 && $row_padre['borrado'] == 0 )? true : false;
                                                            /* revisa si el padre esta activo */
    
                                                            if(!$activados){
                                                                /* padre esta borrado, no permite recuperar */
                                                                $btn_desactivar = false;
                                                            }
                                                        }
    
                                                        $btn_deleted = ($btn_desactivar && !in_array($row['id'], [1,2,3,4,5,6,7,8,9,10]))? '
                                                            <button type="button" class="btn btn-info btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Recuperar" onclick="eliminar(\''.$informacion.'\')">
                                                                <span class="btn-inner--icon">
                                                                    <i class="fas fa-recycle"></i>
                                                                </span>
                                                            </button>
                                                        ': '
                                                            <button type="button" class="btn btn-default btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="No se puede recuperar, ya que la cuenta mayor esta inactivo o eliminado">
                                                                <span class="btn-inner--icon">
                                                                    <i class="fas fa-recycle"></i>
                                                                </span>
                                                            </button>
                                                        ';
                                                    }
                                                    else{
                                                        if($row['posee_hijo'] != 0){
                                                            /* revisa si los hijos estan eliminados */
                                                                $cta_hijos= $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_CUENTAS_CONTABLES. " WHERE id_padre = ".$row['id']);
                                                                $eliminado = false;
    
                                                                while ($row_hijo = $cta_hijos->fetch()) {
                                                                    $eliminado = ($row_hijo['activo'] == 0 || $row_hijo['borrado'] == 1 )? true : false;
                                                                }
                                                            /* revisa si los hijos estan eliminados */
                                                            if(!$desactivados){
                                                                $btn_eliminar = false;
                                                            }
                                                        }
    
                                                        $mensaje = (in_array($row['id'], [1,2,3,4,5,6,7,8,9,10]))? 'No se puede eliminar, ya que es una cuenta principal del sistema' : 'No se puede eliminar, ya que posee sub cuentas activos';
    
                                                        $btn_deleted = ($btn_eliminar && !in_array($row['id'], [1,2,3,4,5,6,7,8,9,10]))? '
                                                            <button type="button" class="btn btn-danger btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Eliminar" onclick="eliminar(\''.$informacion.'\')">
                                                                <span class="btn-inner--icon">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </span>
                                                            </button>
                                                        ': '
                                                            <button type="button" class="btn btn-default btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="'.$mensaje.'">
                                                                <span class="btn-inner--icon">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </span>
                                                            </button>
                                                        ';
                                                    }
                                                /* Establece el boton de borrar */
    
                                                /* Trae codigo de moneda de la cuenta */
                                                    $codificacion_moneda = '';
                                                    $nombre_moneda = '';
                                                    foreach ($listado_monedas as $key => $value) {
                                                        if($value['id'] == $row['id_moneda']){ $codificacion_moneda = $value['codificacion']; $nombre_moneda = $value['nombre']; }
                                                    }
                                                /* Trae codigo de moneda de la cuenta */
    
                                                /* Trae el usuario que creó la cuenta */
                                                    $usuario = '';
                                                    if($row['usuario'] == 0){ $usuario = 'Creado por el sistema'; }
                                                    else{
                                                        foreach ($listado_administradores as $key => $value) {
                                                            if($value['id'] == $row['usuario']){ $usuario = $value['nombre'].' '.$value['apellido']; }
                                                        }
                                                    }
                                                /* Trae el usuario que creó la cuenta */
    
                                                /* Trae el tipo de cuenta */
                                                    $Tipo_cuenta = '';
                                                    foreach ($CTA_MAYORES as $key => $value) {
                                                        if($value['id'] == $row['id_tipo_cuenta']){ $Tipo_cuenta = $value['nombre']; }
                                                    }
                                                /* Trae el tipo de cuenta */
    
                                                /* Trae la cuenta padre */
                                                    $Cta_padre = 'No posee';
                                                    foreach ($ctas_contables as $key => $value) {
                                                        if(isset($value['naturaleza'])){
                                                            if($value['id'] == $row['id_padre']){ $Cta_padre = $value['nombre']; }
                                                        }
                                                    }
                                                /* Trae la cuenta padre */
    
                                                /* Analiza la naturaleza */
                                                    $naturaleza = ($row['naturaleza'] == 1)? 'Deudor': 'Acreedor';
                                                /* Analiza la naturaleza */
    
                                                /* Se establece la información a mostrar */
                                                    $informacion_basica = array(
                                                        "id_tipo_cuenta"    => $Tipo_cuenta,
                                                        "id_padre"          => $Cta_padre,
                                                        "codigo_cuenta"     => $row['codigo_cuenta'],
                                                        "nombre"            => $row['nombre'],
                                                        "saldoinicial"      => $Quick_function->Money__Format( $row['saldoinicial'], $codificacion_moneda ),
                                                        "saldoactual"       => $Quick_function->Money__Format( $row['saldoactual'], $codificacion_moneda ),
                                                        "naturaleza"        => $naturaleza,
                                                        "id_moneda"         => $nombre_moneda,
                                                        "comentario"        => $row['comentario'],
                                                        "activo"            => $activo_item_info,
                                                        "usuario"           => $usuario,
                                                        "fecha_creacion"    => $Quick_function->fecha($row['fecha_creacion']),
                                                        "codigo"            => $row['codigo'],
                                                        "posee_hijo"        => ($row['posee_hijo'] == 0)? 'No posee': 'Si posee',
                                                    );
                                                    
                                                    $informacion_basica = htmlentities(json_encode($informacion_basica));
                                                /* Se establece la información a mostrar */
    
                                                /* Establece el boton de editar */
                                                    $btn_edit = (!in_array($row['id'], [1,2,3,4,5,6,7,8,9,10]))? '
                                                        <button type="button" class="btn btn-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#AgregarDato" onclick="establecer_editar(\''.$informacion.'\')">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </span>
                                                        </button>
                                                    ': '
                                                        <button type="button" class="btn text-underline--dashed" data-toggle="tooltip" data-placement="bottom" title="No se puede editar, ya que es una cuenta principal del sistema">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    ';
                                                /* Establece el boton de editar */
    
                                                if($mostrar){
                                                    echo '
                                                        <tr>
                                                            <th class="text-justify" scope="row">'.$row['codigo'].'</th>
                                                            <td class="text-justify">
                                                                <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'.$row['comentario'].'">
                                                                    '.$row['nombre'].'
                                                                </button>
                                                            </td>
                                                            <td class="text-center">'.$Quick_function->Money__Format( $row['saldoactual'], $codificacion_moneda ).'</td>
                                                            <td class="text-center">'.$naturaleza.'</td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-outline-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#informacion_cuenta" onclick="mostrar_informacion(\''.$informacion_basica.'\')">
                                                                    <span class="btn-inner--icon">
                                                                        <i class="fas fa-info"></i>
                                                                    </span>
                                                                </button>
                                                            </td>
                                                            <td class="text-center">
                                                                '.$btn.'
                                                            </td>
                                                            <td class="text-center">
                                                                '.$btn_edit.'
                                                            </td>
                                                            <td class="text-center">
                                                                '.$btn_deleted.'
                                                            </td>
                                                        </tr>
                                                    ';
    
                                                }
    
                                            } else { /* Es cuenta inicial del sistema */
                                                echo '
                                                    <tr class="rexy-tr-dark">
                                                        <th class="text-justify" scope="row">'.$row['codigo'].'</th>
                                                        <td class="text-justify" colspan="8">
                                                            '.$row['nombre'].'
                                                        </td>
                                                    </tr>
                                                ';
                                            }
                                        }
                                    /* Trae el listado de los items */
    
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>


        <!-- Modal -->
        <div class="modal fade" id="AgregarDato" tabindex="-1" role="dialog" aria-hidden="true">
            <form id="form_cuenta" action="procedures/cuentas_contables.php" method="post">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="texto_Modal_metodo"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Nombre de la cuenta</small>
                                    <input name="nombre" id="nombre" type="text" class="form-control" placeholder="Nombre de la cuenta">
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Grupo financiero</small>
                                    <select name="id_cuenta_financiero" id="id_cuenta_financiero"  class="selectpicker" data-live-search="true">
                                        <?php
                                            $listado_bloqueados = [];
                                            foreach ($Quick_function->traer_ctas_contables(0, '' , false) as $value) {
                                                $disabled   = ($value['posee_hijo'] == 1)? 'disabled': '';
                                                $id         = ($value['posee_hijo'] == 1)?  '': $value['id'];
                                                if($value['posee_hijo'] == 1){ $listado_bloqueados[] = $value['id']; }
                                                echo "<option value='$id' $disabled>".$value["codigo"].' - '.$value["nombre"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Cuenta control / detalle</small>
                                    <select name="id_cuenta_control" id="id_cuenta_control"  class="selectpicker" data-live-search="true">
                                        <?php 
                                            foreach ($Quick_function->traer_ctas_contables() as $value) {
                                                $disabled   = '';
                                                if($value['posee_hijo'] == 1){
                                                    if(isset($value['mayor']) && in_array($value['id'], $listado_bloqueados)){
                                                        $disabled   = 'disabled';
                                                    }
                                                };

                                                
                                                $id         =  $value['id'];
                                                echo "<option value='$id' $disabled>".$value["codigo"].' - '.$value["nombre"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Código de cuenta (último dígito)</small>
                                    <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Código de cuenta (último dígito)">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Moneda</small>
                                    <select name="id_moneda" id="id_moneda"  class="selectpicker" data-live-search="true">
                                        <?php 
                                            foreach ($listado_monedas as $value) {
                                                echo '<option $disabled value="'.$value["id"].'">'.$value["simbolo"].' - '.$value["nombre"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <small class="form-text text-dark">Monto inicial</small>
                                    <input type="text" name="monto_inicial" id="monto_inicial" class="form-control" placeholder="Monto inicial">
                                </div>

                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Naturaleza</small>
                                    <select name="naturaleza" id="naturaleza"  class="selectpicker" data-live-search="true">
                                        <option value="1">Deudor</option>
                                        <option value="2">Acreedor</option>
                                    </select>
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark">Comentario (alternativo)</small>
                                    <textarea class="form-control" name="comentario" id="comentario" placeholder="Comentario (alternativo)" rows="3" resize="none"></textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-dark">Guardar</button>
                        </div>
                    </div>
                    <input type="hidden" id="formaction_cuenta" name="formaction" value="create_DB">
                    <input type="hidden" id="id_cuenta_edit" name="id_cuenta" value="">
                </div>
            </form>
        </div>

        <div class="modal fade" id="acciones_cuenta" tabindex="-1" role="dialog" aria-hidden="true">
            <form id="form_cuenta_activar" action="procedures/cuentas_contables.php" method="post">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="texto_accion"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <p id="AI_Mensaje_confirmacion"></p>
                            </div>
    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-dark">Guardar</button>
                        </div>
                    </div>
                    <input type="hidden" id="formaction_cuenta_accion" name="formaction" value="">
                    <input type="hidden" id="id_cuenta_accion" name="id_cuenta" value="">
                </div>
            </form>
        </div>

        <div class="modal fade" id="informacion_cuenta" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="texto_accion">Información de la cuenta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="rexy-letra-estandar">Nombre de la cuenta:</th>
                                        <td><span id="info_nombre" class="badge badge-light rexy-letra-estandar"></span></td>

                                        <th scope="row" class="rexy-letra-estandar">Monto actual:</th>
                                        <td><span id="info_saldoactual" class="badge badge-light rexy-letra-estandar"></span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="rexy-letra-estandar">Grupo financiero:</th>
                                        <td><span id="info_id_tipo_cuenta" class="badge badge-light rexy-letra-estandar"></span></td>

                                        <th scope="row" class="rexy-letra-estandar">Naturaleza:</th>
                                        <td><span id="info_naturaleza" class="badge badge-light rexy-letra-estandar"></span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="rexy-letra-estandar">Cuenta control / detalle:</th>
                                        <td><span id="info_id_padre" class="badge badge-light rexy-letra-estandar"></span></td>

                                        <th scope="row" class="rexy-letra-estandar">Comentario (alternativo):</th>
                                        <td><span id="info_comentario" class="badge badge-light rexy-letra-estandar"></span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="rexy-letra-estandar">Código de cuenta:</th>
                                        <td><span id="info_codigo" class="badge badge-light rexy-letra-estandar"></span></td>

                                        <th scope="row" class="rexy-letra-estandar">Posee subcuentas:</th>
                                        <td><span id="info_posee_hijo" class="badge badge-light rexy-letra-estandar"></span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="rexy-letra-estandar">Código de cuenta (último dígito):</th>
                                        <td><span id="info_codigo_cuenta" class="badge badge-light rexy-letra-estandar"></span></td>

                                        <th scope="row" class="rexy-letra-estandar">Fecha creación:</th>
                                        <td><span id="info_fecha_creacion" class="badge badge-light rexy-letra-estandar"></span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="rexy-letra-estandar">Moneda:</th>
                                        <td><span id="info_id_moneda" class="badge badge-light rexy-letra-estandar"></span></td>

                                        <th scope="row" class="rexy-letra-estandar">Usuario que lo creó:</th>
                                        <td><span id="info_usuario" class="badge badge-light rexy-letra-estandar"></span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="rexy-letra-estandar">Monto inicial:</th>
                                        <td><span id="info_saldoinicial" class="badge badge-light rexy-letra-estandar"></span></td>

                                        <th scope="row">Estado:</th>
                                        <td><span id="info_activo" class="badge badge-light rexy-letra-estandar"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once("template/libs.php") ?>
        <?php include_once("template/footer.php") ?>

        
        <script type="text/javascript">
            function establecer_agregar() {
                $("#form_cuenta")           .trigger("reset")
                $("#formaction_cuenta")     .val("create_DB")
                $("#id_cuenta_edit")        .val("")
                $("#id_moneda")             .prop("disabled", false)
                $("#monto_inicial")         .prop("disabled", false)
                $("#texto_Modal_metodo")    .html("Nueva cuenta contable")
                refrescar_selectpicker()
            }
            
            function establecer_editar(informacion) {
                informacion = JSON.parse(informacion)
                $("#form_cuenta").trigger("reset")
                $("#formaction_cuenta").val("edit_DB")
                $("#id_cuenta_edit")  .val(informacion.id)

                $("#texto_Modal_metodo").html(`Modificar cuenta contable ${informacion.nombre}`)

                $("#nombre")                .val(informacion.nombre)
                $("#id_cuenta_financiero")  .val(informacion.id_tipo_cuenta)
                $("#id_cuenta_control")     .val(informacion.id_padre)
                $("#codigo")                .val(informacion.codigo_cuenta)
                $("#id_moneda")             .val(informacion.id_moneda)     .prop("disabled", true)
                $("#monto_inicial")         .val(informacion.saldoinicial)  .prop("disabled", true)
                $("#naturaleza")            .val(informacion.naturaleza)
                $("#comentario")            .val(informacion.comentario)

                refrescar_selectpicker()
            }

            function mostrar_informacion(informacion) {
                informacion = JSON.parse(informacion)
                
                $('#info_id_tipo_cuenta')   .html(informacion.id_tipo_cuenta)
                $('#info_id_padre')         .html(informacion.id_padre)
                $('#info_codigo_cuenta')    .html(informacion.codigo_cuenta)
                $('#info_nombre')           .html(informacion.nombre)
                $('#info_saldoinicial')     .html(informacion.saldoinicial)
                $('#info_saldoactual')      .html(informacion.saldoactual)
                $('#info_naturaleza')       .html(informacion.naturaleza)
                $('#info_id_moneda')        .html(informacion.id_moneda)
                $('#info_comentario')       .html(informacion.comentario)
                $('#info_activo')           .html(informacion.activo)
                $('#info_usuario')          .html(informacion.usuario)
                $('#info_fecha_creacion')   .html(informacion.fecha_creacion)
                $('#info_codigo')           .html(informacion.codigo)
                $('#info_posee_hijo')       .html(informacion.posee_hijo)
            }

            function activarInactivar(informacion) {
                informacion = JSON.parse(informacion)
                $("#texto_accion").html('Activar / Inactivar cuenta contable')
                $("#formaction_cuenta_accion").val('activate_DB')
                $("#id_cuenta_accion").val(informacion.id)

                mensaje = (informacion.activo == 1)? `
                    Desea inactivar la cuenta ${informacion.nombre}
                `: `
                    Desea activar la cuenta ${informacion.nombre}
                `;
                
                $('#AI_Mensaje_confirmacion').html(mensaje)

                $('#acciones_cuenta').modal('show')
            }

            function eliminar(informacion) {
                informacion = JSON.parse(informacion)
                $("#formaction_cuenta_accion").val('deleted_DB')
                $("#id_cuenta_accion").val(informacion.id)

                mensaje = (informacion.borrado == 1)? `
                    Desea recuperar la cuenta ${informacion.nombre}
                `: `
                    Desea borrar la cuenta ${informacion.nombre}
                `;

                texto = (informacion.borrado == 1)? `
                    Recuperar cuenta
                `: `
                    Borrar cuenta
                `;
                
                $("#texto_accion").html(texto)
                $('#AI_Mensaje_confirmacion').html(mensaje)

                $('#acciones_cuenta').modal('show')
            }
        </script>
    </body>

</html>