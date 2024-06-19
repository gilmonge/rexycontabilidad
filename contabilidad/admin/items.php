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

    /* Trae el listado de impuestos */
        $IMPUESTOS = json_decode($Quick_function->TraerParametro('IMPUESTOS'), TRUE);
    /* Trae el listado de impuestos */

    /* Trae el listado de cuentas contables */
        $CTAS_CONTABLES = $Quick_function->traer_ctas_contables();
        $CTAS_CONTABLES_ACTIVAS = $Quick_function->traer_ctas_contables_activas();
    /* Trae el listado de cuentas contables */
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
                                    <h5 class="mb-2">Listado de items del sistema</h5>
                                </div>
                                <div class="col-12 col-md-6 mt-4 mt-md-0 text-md-right">
                                    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#AgregarDato" onclick="establecer_agregar()">
                                        Agregar Item
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
                                    <th scope="col" class="text-center">ID</th>
                                    <th scope="col" class="text-center">Nombre</th>
                                    <th scope="col" class="text-center">Tipo</th>
                                    <th scope="col" class="text-center">Cta contable</th>
                                    <th scope="col" class="text-center">Monto</th>
                                    <th scope="col" class="text-center">Impuesto</th>
                                    <th scope="col" class="text-center">Estado</th>
                                    <th scope="col" class="text-center">Editar</th>
                                    <th scope="col" class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="rexy-cuerpo-tabla">
                                <?php
                                    /* Trae el listado de los items */
                                        $TABLA_ITEMS    = TABLA_ITEMS;
                                        $TABLA_MONEDAS  = TABLA_MONEDAS;
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
                                                        I.id,
                                                        I.id_cuenta,
                                                        I.id_moneda,
                                                        I.nombre_item,
                                                        I.monto_base,
                                                        I.tipo_item,
                                                        I.id_impuesto,
                                                        I.comentario,
                                                        I.activo,
                                                        I.borrado,
    
                                                        M.codificacion
    
                                                    FROM $TABLA_ITEMS AS I
                                                        INNER JOIN $TABLA_MONEDAS AS M
                                                            ON M.id = I.id_moneda
                                                    WHERE $WHERE
                                        ";
                                        $listado_items= $Quick_function->SQLDatos_SA($select);
                                        while ($row = $listado_items->fetch()) {
                                            $impuesto_item = $IMPUESTOS[$row['id_impuesto']]['porcentaje'].'%';
                                            $tipo_item = ($row['tipo_item'] == 1)? 'Servicio' : 'Producto';
                                            $activo_item = ($row['activo'] == 1)? 'Activo' : 'Inactivo';
                                            $cta_contable = $cta_contable_completo = '';
                                            
                                            foreach ($CTAS_CONTABLES as $value) {
                                                if($value['id'] == $row['id_cuenta']){
                                                    $cta_contable           = $value["nombre"];
                                                    $cta_contable_completo  = $value["codigo"].' - '.$value["nombre"];
                                                }
                                            }
                                            $informacion = htmlentities(json_encode($row));
        
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
                                                    <th class="text-center" scope="row">'.$row['id'].'</th>
                                                    <td class="text-center">
                                                        <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.$row['comentario'].'">
                                                            '.$row['nombre_item'].'
                                                        </button>
                                                    </td>
                                                    <td class="text-center">'.$tipo_item.'</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.$cta_contable_completo.'">
                                                            '.$cta_contable.'
                                                        </button>
                                                    </td>
                                                    <td class="text-center">'.$Quick_function->Money__Format($row['monto_base'], $row['codificacion']).'</td>
                                                    <td class="text-center">'.$impuesto_item.'</td>
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
            <form id="form_item" action="procedures/items.php" method="post">
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
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Nombre del item</small>
                                    <input type="text" class="form-control" placeholder="Nombre del item" name="nombre_item" id="nombre_item">
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Cuenta contable</small>
                                    <select name="id_cuenta" id="id_cuenta"  class="selectpicker" data-live-search="true">
                                        <?php 
                                            foreach ($CTAS_CONTABLES_ACTIVAS as $value) {
                                                $disabled   = ($value['posee_hijo'] == 1)? 'disabled': '';
                                                $id         = ($value['posee_hijo'] == 1)?  '': $value['id'];
                                                echo "<option $disabled value='$id'>".$value["codigo"].' - '.$value["nombre"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
        
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
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Impuesto</small>
                                    <select name="id_impuesto" id="id_impuesto"  class="selectpicker" data-live-search="true">
                                        <?php 
                                            foreach ($IMPUESTOS as $value) {
                                                echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Tipo de item</small>
                                    <select name="tipo_item" id="tipo_item"  class="selectpicker" data-live-search="true">
                                        <option value="1">Servicio</option>
                                        <option value="2">Producto</option>
                                    </select>
                                </div>
        
                                <div class="form-group">
                                    <small class="form-text text-dark"><span class="asteriscos">* </span>Monto base del item</small>
                                    <input type="text" name="monto_base" id="monto_base" class="form-control" placeholder="Monto base del item">
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
                    <input type="hidden" id="formaction_item" name="formaction" value="create_DB">
                    <input type="hidden" id="id_item_edit" name="id_item" value="">
                </div>
            </form>
        </div>

        <div class="modal fade" id="acciones_item" tabindex="-1" role="dialog" aria-hidden="true">
            <form id="form_item" action="procedures/items.php" method="post">
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
                    <input type="hidden" id="formaction_item_accion" name="formaction" value="">
                    <input type="hidden" id="id_item_accion" name="id_item" value="">
                </div>
            </form>
        </div>

        <?php include_once("template/libs.php") ?>
        <?php include_once("template/footer.php") ?>

        
        <script type="text/javascript">
            function establecer_agregar() {
                $("#form_item")         .trigger("reset")
                $("#formaction_item")   .val("create_DB")
                $("#id_item_edit")      .val("")
                $("#texto_Modal_metodo")      .html("Nuevo item")
                refrescar_selectpicker()
            }
            
            function establecer_editar(informacion) {
                informacion = JSON.parse(informacion)
                $("#form_item").trigger("reset")
                $("#formaction_item").val("edit_DB")
                
                $("#texto_Modal_metodo")      .html(`Modificar ${informacion.nombre_item}`)
                $("#id_item_edit")  .val(informacion.id)
                $("#nombre_item")   .val(informacion.nombre_item)
                $("#id_cuenta")     .val(informacion.id_cuenta)
                $("#id_moneda")     .val(informacion.id_moneda)
                $("#id_impuesto")   .val(informacion.id_impuesto)
                $("#tipo_item")     .val(informacion.tipo_item)
                $("#monto_base")    .val(informacion.monto_base)
                $("#comentario")    .val(informacion.comentario)
                refrescar_selectpicker()
            }

            function activarInactivar(informacion) {
                informacion = JSON.parse(informacion)
                $("#texto_accion").html('Activar / Inactivar item')
                $("#formaction_item_accion").val('activate_DB')
                $("#id_item_accion").val(informacion.id)

                mensaje = (informacion.activo == 1)? `
                    Desea inactivar el item ${informacion.nombre_item}
                `: `
                    Desea activar el item ${informacion.nombre_item}
                `;
                
                $('#AI_Mensaje_confirmacion').html(mensaje)

                $('#acciones_item').modal('show')
            }

            function eliminar(informacion) {
                informacion = JSON.parse(informacion)
                $("#formaction_item_accion").val('deleted_DB')
                $("#id_item_accion").val(informacion.id)

                mensaje = (informacion.borrado == 1)? `
                    Desea recuperar el item ${informacion.nombre_item}
                `: `
                    Desea borrar el item ${informacion.nombre_item}
                `;

                texto = (informacion.borrado == 1)? `
                    Recuperar item
                `: `
                    Borrar el item
                `;
                
                $("#texto_accion").html(texto)
                $('#AI_Mensaje_confirmacion').html(mensaje)

                $('#acciones_item').modal('show')
            }

            $(document).ready(function () {
                crear_dataTable("listado")
            })
        </script>
    </body>

</html>