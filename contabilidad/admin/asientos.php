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
                                    <h5 class="mb-2">Listado de asientos contables</h5>
                                </div>
                                <div class="col-12 col-md-6 mt-4 mt-md-0 text-md-right">
                                    <a type="button" class="btn btn-dark" href="asientos-crear.php">
                                        Agregar nuevo asiento
                                    </a>
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
                                                <button class="dropdown-item">Anulados</button>
                                                <input type="hidden" name="filtro" value="1">
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
                                    <th scope="col" class="text-center">Núm. Asiento</th>
                                    <th scope="col" class="text-center">Fecha</th>
                                    <th scope="col" class="text-center">Referencia</th>
                                    <th scope="col" class="text-center">Monto</th>
                                    <th scope="col" class="text-center">Tipo cambio</th>
    
                                    <th scope="col" class="text-center">Información</th>
                                    <th scope="col" class="text-center">Editar</th>
                                    <th scope="col" class="text-center">Reversar</th>
                                </tr>
                            </thead>
                            <tbody class="rexy-cuerpo-tabla">
                                <?php
                                    /* Trae el listado de los items */
                                        $TABLA_ASIENTOS = TABLA_ASIENTOS;
                                        $TABLA_MONEDAS  = TABLA_MONEDAS;
                                        $TABLA_ADMINISTRADORES  = TABLA_ADMINISTRADORES;
                                        $WHERE = "";
                                        if((!isset($_POST['filtro'])) || (isset($_POST['filtro']) && $_POST['filtro'] == 0)){ /* Activo */
                                            $WHERE= " activo = 1";
                                        }
                                        else if(isset($_POST['filtro']) && $_POST['filtro'] == 1){ /* Anulados */
                                            $WHERE= " activo = 0";
                                        }
                                        else if(isset($_POST['filtro']) && $_POST['filtro'] == 3){ /* Todo */
                                            $WHERE= " 1 ";
                                        }
                                        $select = " SELECT 
                                                        A.id,
                                                        A.numero_asiento,
                                                        A.fecha,
                                                        A.referencia_documento,
                                                        A.id_moneda,
                                                        A.tipo_cambio,
                                                        A.comentario,
                                                        A.total_debe,
                                                        A.total_haber,
                                                        A.procedencia,
                                                        A.usuario,
                                                        A.fecha_creacion,
                                                        A.activo,
                                                        
                                                        M.codificacion,
                                                        M.nombre AS nombre_moneda,
    
                                                        CONCAT(Admi.nombre, ' ', Admi.apellido) AS administrador
                                                    FROM $TABLA_ASIENTOS AS A
                                                        INNER JOIN $TABLA_MONEDAS AS M
                                                            ON M.id = A.id_moneda
                                                        INNER JOIN $TABLA_ADMINISTRADORES AS Admi
                                                            ON Admi.id = A.usuario
                                                    WHERE $WHERE
                                        ";
                                        $listado_items= $Quick_function->SQLDatos_SA($select);
                                        while ($row = $listado_items->fetch()) {
                                            $informacion = htmlentities(json_encode($row));
        
                                            /* Se establece la información a mostrar */
                                                $procedencia    = ($row['procedencia'] == 1)? "Asientos contables" : "";
                                                $comentario     = ($row['comentario'] != '')? $row['comentario'] : "No aplica";
    
                                                $informacion_basica = array(
                                                    "num_asiento"       => $row['id'],
                                                    "activo"            => $row['activo'],
                                                    "fecha"             => $Quick_function->fecha($row['fecha']),
                                                    "fecha_editar"      => date('d-m-Y', strtotime($row['fecha'])),
                                                    "entrada"           => $row['referencia_documento'],
                                                    "nombre_moneda"     => $row['nombre_moneda'].' - '.$Quick_function->Money__Format($row['tipo_cambio']),
                                                    "procedencia"       => $procedencia,
                                                    "comentario"        => $comentario,
                                                    "total_debe"        => $Quick_function->Money__Format($row['total_debe'], $row['codificacion']),
                                                    "total_haber"       => $Quick_function->Money__Format($row['total_haber'], $row['codificacion']),
                                                    "fecha_creacion"    => $Quick_function->fecha($row['fecha_creacion']),
                                                    "administrador"     => $row['administrador'],
                                                );
                                                
                                                $informacion_basica = htmlentities(json_encode($informacion_basica));
                                            /* Se establece la información a mostrar */
    
        
                                            /* Establece el boton de borrar */
                                                $btn_deleted = ($row['activo'] == 1)? '
                                                        <button type="button" class="btn btn-danger btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Reversar" onclick="reversar(\''.$informacion.'\')">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-ban"></i>
                                                            </span>
                                                        </button>
                                                ' : '
                                                        <button type="button" class="btn btn-default btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Reversado">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-ban"></i>
                                                            </span>
                                                        </button>
                                                ';
                                            /* Establece el boton de borrar */
    
                                            /* Establece el boton de editar */
                                                $btn_update = ($row['activo'] == 1)? '
                                                        <button type="button" class="btn btn-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#editar_asiento" onclick="editar_informacion(\''.$informacion_basica.'\', '.$row['id'].')">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </span>
                                                        </button>
                                                ' : '
                                                        <button type="button" class="btn btn-default btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Reversado">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </span>
                                                        </button>
                                                ';
                                            /* Establece el boton de editar */
    
    
                                            $comentario = ($row['comentario'] != '')? $row['comentario'] : "No posee comentario";
                                            echo '
                                                <tr>
                                                    <th class="text-center" scope="row">
                                                        '.$row['numero_asiento'].'
                                                    </th>
                                                    <td class="text-center">
                                                        '.$Quick_function->fecha($row['fecha']).'
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn text-underline--dashed rexy-info" data-toggle="tooltip" data-placement="bottom" title="'.substr($row['comentario'], 0, 300).'">
                                                            '.$row['referencia_documento'].'
                                                        </button>
                                                    </td>
                                                    <td class="text-center">'.$Quick_function->Money__Format($row['total_debe'], $row['codificacion']).'</td>
                                                    <td class="text-center">'.$Quick_function->Money__Format($row['tipo_cambio']).'</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#informacion_asiento" onclick="mostrar_informacion(\''.$informacion_basica.'\', '.$row['id'].')">
                                                            <span class="btn-inner--icon">
                                                                <i class="fas fa-info"></i>
                                                            </span>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        '.$btn_update.'
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
        <div class="modal fade" id="informacion_asiento" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="texto_accion"><span id="estado_asiento"></span>Información asiento # <span id="num_asiento"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-3 mb-2">
                            <span class="rexy-letra-estandar">Fecha del asiento:</span>
                            <span id="fecha" class="badge badge-light rexy-letra-estandar"></span>
                        </div>
                        <div class="col-3 mb-2">
                            <span class="rexy-letra-estandar">Número de entrada:</span>
                            <span id="entrada" class="badge badge-light rexy-letra-estandar"></span>
                        </div>
                        <div class="col-3 mb-2">
                            <span class="rexy-letra-estandar">Moneda:</span>
                            <span id="nombre_moneda" class="badge badge-light rexy-letra-estandar"></span>
                        </div>
                        <div class="col-3 mb-2">
                            <span class="rexy-letra-estandar">Procedencia:</span>
                            <span id="procedencia" class="badge badge-light rexy-letra-estandar"></span>
                        </div>
                        <div class="col-12 mb-2">
                            <span class="rexy-letra-estandar">Comentario:</span>
                            <p id="comentario" class="badge badge-light rexy-letra-estandar" style="white-space: initial !important;"></p>
                        </div>

                        <div id="content_lineas_asiento" class="col-12 mb-2 d-flex justify-content-center">
                            <div class="loader"></div>
                        </div>
                        
                        <div class="col-6 mb-2">
                            <table class="table" id="listado">
                                <tbody class="rexy-cuerpo-tabla">                              
                                    <tr>
                                        <th class="text-justify" scope="row">Total debe</th>
                                        <td id="total_debe" class="text-center">-</td>
                                        <td class="text-center">Total haber</td>
                                        <td id="total_haber" class="text-justify">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-6 mb-2">
                            <div class="col-12 mb-2 text-right">
                                <span class="rexy-letra-estandar">Fecha de creación:</span>
                                <span id="fecha_creacion" class="badge badge-light rexy-letra-estandar"></span>
                            </div>
                            <div class="col-12 mb-2 text-right">
                                <span class="rexy-letra-estandar">Usuario:</span>
                                <span id="administrador" class="badge badge-light rexy-letra-estandar"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editar_asiento" tabindex="-1" role="dialog" aria-hidden="true">
            <form action="procedures/asientos.php" method="post">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="edit_texto_accion">Editar asiento # <span id="edit_num_asiento"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-8 row">
                                <div class="col-6 mb-2">
                                    <span class="rexy-letra-estandar">Fecha del asiento:</span>
                                    <input type="text" class="form-control" placeholder="* Fecha del asiento" name="fecha" id="edit_fecha">
                                </div>
                                <div class="col-6 mb-2">
                                    <span class="rexy-letra-estandar">Número de entrada:</span>
                                    <input type="text" class="form-control" placeholder="Número de entrada" name="referencia" id="edit_entrada">
                                </div>
                            </div>
                            <div class="col-4 row">
                                <div class="col-12 mb-2">
                                    <span class="rexy-letra-estandar">Moneda:</span>
                                    <span id="edit_nombre_moneda" class="badge badge-light rexy-letra-estandar"></span>
                                </div>
                                <div class="col-12 mb-2">
                                    <span class="rexy-letra-estandar">Procedencia:</span>
                                    <span id="edit_procedencia" class="badge badge-light rexy-letra-estandar"></span>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <span class="rexy-letra-estandar">Comentario:</span>
                                <textarea class="form-control" name="comentario" id="edit_comentario" placeholder="Motivo de reversado" rows="1" resize="none" required style="min-height: 100px; max-height: 100px;"></textarea>
                            </div>

                            <div id="edit_content_lineas_asiento" class="col-12 mb-2 d-flex justify-content-center">
                                <div class="loader"></div>
                            </div>
                            
                            <div class="col-6 mb-2">
                                <table class="table" id="edit_listado">
                                    <tbody class="rexy-cuerpo-tabla">                              
                                        <tr>
                                            <th class="text-justify" scope="row">Total debe</th>
                                            <td id="edit_total_debe" class="text-center">-</td>
                                            <td class="text-center">Total haber</td>
                                            <td id="edit_total_haber" class="text-justify">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-6 mb-2">
                                <div class="col-12 mb-2 text-right">
                                    <span class="rexy-letra-estandar">Fecha de creación:</span>
                                    <span id="edit_fecha_creacion" class="badge badge-light rexy-letra-estandar"></span>
                                </div>
                                <div class="col-12 mb-2 text-right">
                                    <span class="rexy-letra-estandar">Usuario:</span>
                                    <span id="edit_administrador" class="badge badge-light rexy-letra-estandar"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-dark">Actualizar</button>
                        </div>
                        <input type="hidden" name="formaction" value="update_DB">
                        <input type="hidden" id="id_editado" name="id" value="">
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal fade" id="confirmacion_reversado" tabindex="-1" role="dialog" aria-hidden="true">
            <form action="procedures/asientos.php" method="post">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="texto_accion">Confirmación de reversado</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row">
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="form-text text-dark">* Fecha del asiento</small>
                                    <input type="text" class="form-control" placeholder="* Fecha del asiento" name="fecha" id="fecha_reversado">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="form-text text-dark">Número de entrada</small>
                                    <input type="text" class="form-control" placeholder="Número de entrada" name="referencia" id="referencia">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <small class="form-text text-dark">* Motivo de reversado</small>
                                    <textarea class="form-control" name="comentario" id="comentario" placeholder="Motivo de reversado" rows="1" resize="none" required style="min-height: 100px; max-height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-dark">Aplicar</button>
                        </div>
                        <input type="hidden" id="formaction_reversado" name="formaction" value="reverse_DB">
                        <input type="hidden" id="id_reversado" name="id" value="">
                    </div>
                </div>
            </form>
        </div>


        <?php include_once("template/libs.php") ?>
        <?php include_once("template/footer.php") ?>

        
        <script type="text/javascript">
            
            function reversar(informacion) {
                json_informacion = JSON.parse(informacion)

                let json_informacion_txt = JSON.stringify(json_informacion)
                json_informacion_txt = json_informacion_txt.replace(/"/g,"'");

                mensaje = `¿Está seguro(a) de reversar el asiento #${json_informacion.id}?`
                lanzar_msg(2, 'Reversar', mensaje, 'Cancelar', `<button type="button" class="btn btn-sm btn-white" onclick="confirmar_reversado(${json_informacion_txt})">Reversar</button>`)
            }

            function mostrar_informacion(informacion, id_asiento){
                informacion = JSON.parse(informacion)
                $('#content_lineas_asiento').html(`<div class="loader"></div>`)
                
                let estado = (informacion.activo == 1)? '' : '<div class="alert alert-modern alert-outline-danger mr-3"><span class="badge badge-danger badge-pill">Estado</span><span class="alert-content"> Reversado</span></div>'

                $('#estado_asiento').html(estado)
                $('#num_asiento').html(informacion.num_asiento)
                $('#fecha').html(informacion.fecha)
                $('#entrada').html(informacion.entrada)
                $('#nombre_moneda').html(informacion.nombre_moneda)
                $('#procedencia').html(informacion.procedencia)
                $('#comentario').html(informacion.comentario)
                $('#total_debe').html(informacion.total_debe)
                $('#total_haber').html(informacion.total_haber)
                $('#fecha_creacion').html(informacion.fecha_creacion)
                $('#administrador').html(informacion.administrador)

                $.post(`procedures/asientos.php`, {formaction: 'list_lines_DB', id_asiento}, function(data){
                    if(data.cantidad > 0){
                        html_debe = html_haber = ''
                        data.lineas.debe.forEach(function (linea, index) {
                            tercero = (linea.tercero == null)? '-' : linea.tercero
                            html_debe = `${html_debe}
                                <tr>
                                    <td scope="col" class="text-justify">${linea.cuenta_item}</td>
                                    <td scope="col" class="text-center">${linea.monto}</td>
                                    <td scope="col" class="text-center">-</td>
                                    <td scope="col" class="text-justify">${tercero}</td>
                                </tr>
                            `
                        })
                        data.lineas.haber.forEach(function (linea, index) {
                            tercero = (linea.tercero == null)? '-' : linea.tercero
                            html_haber = `${html_haber}
                                <tr>
                                    <td scope="col" class="text-justify">${linea.cuenta_item}</td>
                                    <td scope="col" class="text-center">-</td>
                                    <td scope="col" class="text-center">${linea.monto}</td>
                                    <td scope="col" class="text-justify">${tercero}</td>
                                </tr>
                            `
                        })
                        $('#content_lineas_asiento').html(`
                            <table class="table" id="listado">
                                <thead class="rexy-encabezado-tabla">
                                    <tr>
                                        <th scope="col" class="text-justify">Cta Contable / Item</th>
                                        <th scope="col" class="text-center">Debe</th>
                                        <th scope="col" class="text-center">Haber</th>
                                        <th scope="col" class="text-justify">Cliente / Proveedor</th>
                                    </tr>
                                </thead>
                                <tbody id="lineas_asiento_html" class="rexy-cuerpo-tabla">
                                    ${html_debe}
                                    ${html_haber}
                                </tbody>
                            </table>
                        `)
                    }
                    else{
                        $('#content_lineas_asiento').html(`
                            <table class="table" id="listado">
                                <thead class="rexy-encabezado-tabla">
                                    <tr>
                                        <th scope="col" class="text-justify">Cta Contable / Item</th>
                                        <th scope="col" class="text-center">Debe</th>
                                        <th scope="col" class="text-center">Haber</th>
                                        <th scope="col" class="text-justify">Cliente / Proveedor</th>
                                    </tr>
                                </thead>
                                <tbody id="lineas_asiento_html" class="rexy-cuerpo-tabla">
                                    <tr>
                                        <td scope="col" colspan="4" class="text-center">No hay lineas a mostrar</td>
                                    </tr>
                                </tbody>
                            </table>
                        `)
                        
                    }
                })
            }

            function confirmar_reversado(informacion){
                console.log(informacion)

                $("#id_reversado").val(informacion.id)

                $("#msg_modal").modal('hide')
                $("#confirmacion_reversado").modal('show')
            }

            function editar_informacion(informacion, id_asiento){
                informacion = JSON.parse(informacion)
                $('#content_lineas_asiento').html(`<div class="loader"></div>`)
                
                $('#id_editado').val(informacion.num_asiento)
                $('#edit_num_asiento').html(informacion.num_asiento)
                $('#edit_fecha').val(informacion.fecha_editar)
                $('#edit_entrada').val(informacion.entrada)
                $('#edit_nombre_moneda').html(informacion.nombre_moneda)
                $('#edit_procedencia').html(informacion.procedencia)
                $('#edit_comentario').val(informacion.comentario)
                $('#edit_total_debe').html(informacion.total_debe)
                $('#edit_total_haber').html(informacion.total_haber)
                $('#edit_fecha_creacion').html(informacion.fecha_creacion)
                $('#edit_administrador').html(informacion.administrador)

                $('#edit_fecha').datepicker('remove')
                crear_datePicker('edit_fecha')

                $.post(`procedures/asientos.php`, {formaction: 'list_lines_DB', id_asiento}, function(data){
                    if(data.cantidad > 0){
                        html_debe = html_haber = ''
                        data.lineas.debe.forEach(function (linea, index) {
                            tercero = (linea.tercero == null)? '-' : linea.tercero
                            html_debe = `${html_debe}
                                <tr>
                                    <td scope="col" class="text-justify">${linea.cuenta_item}</td>
                                    <td scope="col" class="text-center">${linea.monto}</td>
                                    <td scope="col" class="text-center">-</td>
                                    <td scope="col" class="text-justify">${tercero}</td>
                                </tr>
                            `
                        })
                        data.lineas.haber.forEach(function (linea, index) {
                            tercero = (linea.tercero == null)? '-' : linea.tercero
                            html_haber = `${html_haber}
                                <tr>
                                    <td scope="col" class="text-justify">${linea.cuenta_item}</td>
                                    <td scope="col" class="text-center">-</td>
                                    <td scope="col" class="text-center">${linea.monto}</td>
                                    <td scope="col" class="text-justify">${tercero}</td>
                                </tr>
                            `
                        })
                        $('#edit_content_lineas_asiento').html(`
                            <table class="table" id="listado">
                                <thead class="rexy-encabezado-tabla">
                                    <tr>
                                        <th scope="col" class="text-justify">Cta Contable / Item</th>
                                        <th scope="col" class="text-center">Debe</th>
                                        <th scope="col" class="text-center">Haber</th>
                                        <th scope="col" class="text-justify">Cliente / Proveedor</th>
                                    </tr>
                                </thead>
                                <tbody id="lineas_asiento_html" class="rexy-cuerpo-tabla">
                                    ${html_debe}
                                    ${html_haber}
                                </tbody>
                            </table>
                        `)
                    }
                    else{
                        $('#edit_content_lineas_asiento').html(`
                            <table class="table" id="listado">
                                <thead class="rexy-encabezado-tabla">
                                    <tr>
                                        <th scope="col" class="text-justify">Cta Contable / Item</th>
                                        <th scope="col" class="text-center">Debe</th>
                                        <th scope="col" class="text-center">Haber</th>
                                        <th scope="col" class="text-justify">Cliente / Proveedor</th>
                                    </tr>
                                </thead>
                                <tbody id="lineas_asiento_html" class="rexy-cuerpo-tabla">
                                    <tr>
                                        <td scope="col" colspan="4" class="text-center">No hay lineas a mostrar</td>
                                    </tr>
                                </tbody>
                            </table>
                        `)
                        
                    }
                })
            }

            $(document).ready(function () {
                crear_dataTable("listado")
                crear_datePicker('fecha_reversado')
            })
        </script>
    </body>

</html>