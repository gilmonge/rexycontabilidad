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

    /* Trae el listado de tipos identificacion */
        $TIPOS_IDENTIFICACION = json_decode($Quick_function->TraerParametro('TIPOS_IDENTIFICACION'), TRUE);
    /* Trae el listado de tipos identificacion */
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
                                    <h5 class="mb-2">Listado de proveedores del sistema</h5>
                                </div>
                                <div class="col-12 col-md-6 mt-4 mt-md-0 text-md-right">
                                    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#AgregarDato" onclick="establecer_agregar()">
                                        Agregar Proveedor
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
                                    <th scope="col" class="text-center">Identificación</th>
                                    <th scope="col" class="text-center">Nombre</th>
                                    <th scope="col" class="text-center">Correo</th>
                                    <th scope="col" class="text-center">Teléfono</th>
                                    <th scope="col" class="text-center">Información</th>
                                    <th scope="col" class="text-center">Estado</th>
                                    <th scope="col" class="text-center">Editar</th>
                                    <th scope="col" class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="rexy-cuerpo-tabla">
                                <?php
                                    /* Trae el listado de los items */
                                        $TABLA_TERCEROS    = TABLA_TERCEROS;
                                        $WHERE = "";
                                        if((!isset($_POST['filtro'])) || (isset($_POST['filtro']) && $_POST['filtro'] == 0)){ /* Activo */
                                            $WHERE= " activo = 1 AND borrado = 0";
                                        }
                                        else if(isset($_POST['filtro']) && $_POST['filtro'] == 1){ /* Inactivo */
                                            $WHERE= " activo = 0 AND borrado = 0";
                                        }
                                        else if(isset($_POST['filtro']) && $_POST['filtro'] == 2){ /* Borrado */
                                            $WHERE= " borrado = 1 ";
                                        }
                                        else if(isset($_POST['filtro']) && $_POST['filtro'] == 3){ /* Todo */
                                            $WHERE= " 1 ";
                                        }
                                        $select = " SELECT
                                                        T.id,
                                                        T.tipo_identificacion,
                                                        T.identificacion,
                                                        T.nombre,
                                                        T.apellido,
                                                        T.correo,
                                                        T.telefono,
                                                        T.direccion,
                                                        T.clasificacion,
                                                        T.activo,
                                                        T.borrado
    
                                                    FROM $TABLA_TERCEROS AS T
                                                    WHERE 
                                                        $WHERE
                                                        AND T.clasificacion IN (1, 3)
                                        ";
                                        $listado_items= $Quick_function->SQLDatos_SA($select);
                                        while ($row = $listado_items->fetch()) {
                                            $activo_item_info = $activo_item = ($row['activo'] == 1)? 'Activo' : 'Inactivo';
                                            $activo_item_info = ($row['borrado'] == 1)? 'Borrado' : $activo_item_info;
                                            
                                            $informacion = htmlentities(json_encode($row));
    
                                            /* Analiza el tipo de identificacion */
                                                $tipo_identificacion_lbl = '';
                                                foreach ($TIPOS_IDENTIFICACION as $key => $value) {
                                                    $tipo_identificacion_lbl = ($value['id'] == $row['tipo_identificacion'])? $value['nombre']: $tipo_identificacion_lbl;
                                                }
                                            /* Analiza el tipo de identificacion */
    
                                            /* Analiza el tipo de identificacion */
                                                $clasificacion_lbl = '';
                                            
                                                $clasificacion_lbl = ($row['clasificacion'] == "1")? 'Proveedor' : $clasificacion_lbl;
                                                $clasificacion_lbl = ($row['clasificacion'] == "2")? 'Cliente' : $clasificacion_lbl;
                                                $clasificacion_lbl = ($row['clasificacion'] == "3")? 'Proveedor / Cliente' : $clasificacion_lbl;
                                            /* Analiza el tipo de identificacion */
                                            
                                            /* Se establece la información a mostrar */
                                                $informacion_basica = array(
                                                    "tipo_identificacion"   => $tipo_identificacion_lbl,
                                                    "identificacion"        => $row['identificacion'],
                                                    "nombre"                => $row['nombre'].' '.$row['apellido'],
                                                    "correo"                => $row['correo'],
                                                    "telefono"              => $row['telefono'],
                                                    "direccion"             => $row['direccion'],
                                                    "clasificacion"         => $clasificacion_lbl,
                                                    "estado"                => $activo_item_info,
                                                );
                                                
                                                $informacion_basica = htmlentities(json_encode($informacion_basica));
                                            /* Se establece la información a mostrar */
        
                                            /* Establece el boton de activar */
                                                $btn = '';
                                                if($row['activo'] == 1){
                                                    $btn = '
                                                            <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.$activo_item.'" onclick="activarInactivar(\''.$informacion.'\')">
                                                                <i class="far fa-eye"></i>
                                                            </button>
                                                    ';
                                                }
                                                else{
                                                    $btn = '
                                                            <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.$activo_item.'" onclick="activarInactivar(\''.$informacion.'\')">
                                                                <i class="far fa-eye-slash"></i>
                                                            </button>
                                                    ';
                                                }
                                            /* Establece el boton de activar */
        
                                            /* Establece el boton de borrar */
                                                $btn_deleted = '';
                                                if($row['borrado'] == 1){
                                                    $btn_deleted = '
                                                            <button type="button" class="btn btn-info btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Recuperar" onclick="eliminar(\''.$informacion.'\')">
                                                                <span class="btn-inner--icon">
                                                                    <i class="fas fa-recycle"></i>
                                                                </span>
                                                            </button>
                                                    ';
                                                }
                                                else{
                                                    $btn_deleted = '
                                                            <button type="button" class="btn btn-danger btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Eliminar" onclick="eliminar(\''.$informacion.'\')">
                                                                <span class="btn-inner--icon">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </span>
                                                            </button>
                                                    ';
                                                }
                                            /* Establece el boton de borrar */
                                            echo '
                                                <tr>
                                                    <td class="text-center">
                                                        <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.$tipo_identificacion_lbl.'">
                                                            '.$row['identificacion'].'
                                                        </button>
                                                    </td>
                                                    <th class="text-center" scope="row">'.$row['nombre'].' '.$row['apellido'].'</th>
                                                    <th class="text-center" scope="row"><a href="mailto:'.$row['correo'].'">'.$row['correo'].'</a></th>
                                                    <th class="text-center" scope="row"><a href="tel:'.$row['telefono'].'">'.$row['telefono'].'</a></th>
    
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#informacion_tercero" onclick="mostrar_informacion(\''.$informacion_basica.'\')">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-info"></i>
                                                            </span>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        '.$btn.'
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#AgregarDato" onclick="establecer_editar(\''.$informacion.'\')">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </span>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        '.$btn_deleted.'
                                                    </td>
                                                </tr>
                                            ';
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
            <form id="formTercero" action="procedures/terceros.php" method="post">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="texto_Modal_metodo">Nuevo item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Tipo de identificación</small>
                                    <select name="tipo_identificacion" id="tipo_identificacion"  class="selectpicker" data-live-search="true">
                                        <?php
                                            foreach ($TIPOS_IDENTIFICACION as $key => $value) {
                                                echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Identificación</small>
                                    <input type="text" class="form-control" placeholder="Identificación" name="identificacion" id="identificacion">
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Nombre</small>
                                    <input type="text" class="form-control" placeholder="Nombre" name="nombre" id="nombre">
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Apellido</small>
                                    <input type="text" class="form-control" placeholder="Apellido" name="apellido" id="apellido">
                                </div>
                                
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Correo</small>
                                    <input type="text" class="form-control" placeholder="Correo" name="correo" id="correo">
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Teléfono</small>
                                    <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono">
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark">Dirección (alternativo)</small>
                                    <textarea class="form-control" name="direccion" id="direccion" placeholder="Dirección (alternativo)" rows="3" resize="none"></textarea>
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Clasificación</small>
                                    <select name="clasificacion" id="clasificacion"  class="selectpicker" data-live-search="true">
                                        <option value="1">Proveedor</option>
                                        <!-- <option value="2">Cliente</option> -->
                                        <option value="3">Cliente / Proveedor</option>
                                    </select>
                                </div>
                            </div>
    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-dark">Guardar</button>
                        </div>
                    </div>
                    <input type="hidden" id="formaction_tercero" name="formaction" value="create_DB">
                    <input type="hidden" id="id_tercero_edit" name="id_tercero" value="">
                    <input type="hidden" name="origen" value="1">
                </div>
            </form>
        </div>

        <div class="modal fade" id="acciones_tercero" tabindex="-1" role="dialog" aria-hidden="true">
            <form id="form_item" action="procedures/terceros.php" method="post">
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
                    <input type="hidden" id="formaction_tercero_accion" name="formaction" value="">
                    <input type="hidden" id="id_tercero_accion" name="id_tercero" value="">
                    <input type="hidden" name="origen" value="1">
                </div>
            </form>
        </div>
        
        <div class="modal fade" id="informacion_tercero" tabindex="-1" role="dialog" aria-hidden="true">
            <form id="form_cuenta" action="procedures/cuentas_contables.php" method="post">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="texto_accion">Información del proveedor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="rexy-letra-estandar">Tipo identificación:</th>
                                            <td><span id="info_tipo_identificacion" class="badge badge-light rexy-letra-estandar"></span></td>

                                            <th scope="row" class="rexy-letra-estandar">Teléfono:</th>
                                            <td><span id="info_telefono" class="badge badge-light rexy-letra-estandar"></span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="rexy-letra-estandar">Identificación:</th>
                                            <td><span id="info_identificacion" class="badge badge-light rexy-letra-estandar"></span></td>

                                            <th scope="row" class="rexy-letra-estandar">Dirección:</th>
                                            <td><span id="info_direccion" class="badge badge-light rexy-letra-estandar"></span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="rexy-letra-estandar">Nombre:</th>
                                            <td><span id="info_nombre" class="badge badge-light rexy-letra-estandar"></span></td>

                                            <th scope="row" class="rexy-letra-estandar">Clasificación:</th>
                                            <td><span id="info_clasificacion" class="badge badge-light rexy-letra-estandar"></span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="rexy-letra-estandar">Correo:</th>
                                            <td><span id="info_correo" class="badge badge-light rexy-letra-estandar"></span></td>

                                            <th scope="row" class="rexy-letra-estandar">Estado:</th>
                                            <td><span id="info_estado" class="badge badge-light rexy-letra-estandar"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php include_once("template/libs.php") ?>
        <?php include_once("template/footer.php") ?>

        
        <script type="text/javascript">
            function establecer_agregar() {
                $("#form_item")             .trigger("reset")
                $("#formaction_tercero")    .val("create_DB")
                $("#id_item_edit")          .val("")
                $("#texto_Modal_metodo")    .html("Nuevo item")
                refrescar_selectpicker()
            }
            
            function establecer_editar(informacion) {
                informacion = JSON.parse(informacion)
                $("#form_item").trigger("reset")
                $("#formaction_tercero").val("edit_DB")
                
                $("#texto_Modal_metodo")    .html(`Modificar ${informacion.nombre}`)
                $("#id_tercero_edit")       .val(informacion.id)

                $("#tipo_identificacion")   .val(informacion.tipo_identificacion)
                $("#identificacion")        .val(informacion.identificacion)
                $("#nombre")                .val(informacion.nombre)
                $("#apellido")              .val(informacion.apellido)
                $("#correo")                .val(informacion.correo)
                $("#telefono")              .val(informacion.telefono)
                $("#direccion")             .val(informacion.direccion)
                $("#clasificacion")         .val(informacion.clasificacion)

                refrescar_selectpicker()
            }

            function activarInactivar(informacion) {
                informacion = JSON.parse(informacion)
                $("#texto_accion").html('Activar / Inactivar item')
                $("#formaction_tercero_accion").val('activate_DB')
                $("#id_tercero_accion").val(informacion.id)

                mensaje = (informacion.activo == 1)? `
                    Desea inactivar el proveedor ${informacion.nombre}
                `: `
                    Desea activar el proveedor ${informacion.nombre}
                `;
                
                $('#AI_Mensaje_confirmacion').html(mensaje)

                $('#acciones_tercero').modal('show')
            }

            function eliminar(informacion) {
                informacion = JSON.parse(informacion)
                $("#formaction_tercero_accion").val('deleted_DB')
                $("#id_tercero_accion").val(informacion.id)

                mensaje = (informacion.borrado == 1)? `
                    Desea recuperar el proveedor ${informacion.nombre}
                `: `
                    Desea borrar el proveedor ${informacion.nombre}
                `;

                texto = (informacion.borrado == 1)? `
                    Recuperar proveedor
                `: `
                    Borrar el proveedor
                `;
                
                $("#texto_accion").html(texto)
                $('#AI_Mensaje_confirmacion').html(mensaje)

                $('#acciones_tercero').modal('show')
            }

            function mostrar_informacion(informacion) {
                informacion = JSON.parse(informacion)
                $('#info_tipo_identificacion')  .html(informacion.tipo_identificacion)
                $('#info_identificacion')       .html(informacion.identificacion)
                $('#info_nombre')               .html(informacion.nombre)
                $('#info_correo')               .html(informacion.correo)
                $('#info_telefono')             .html(informacion.telefono)
                $('#info_direccion')            .html(informacion.direccion)
                $('#info_clasificacion')        .html(informacion.clasificacion)
                $('#info_estado')               .html(informacion.estado)
            }

            $(document).ready(function () {
                crear_dataTable("listado")
            })
        </script>
    </body>

</html>