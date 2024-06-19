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

    /* Trae el listado de Clientes / Proveedores */
        $TABLA_TERCEROS    = TABLA_TERCEROS;
        $clientes_proveedores = [];
        $select = " SELECT
                        T.id,
                        CONCAT(T.nombre, ' ',T.apellido) AS nombre,
                        T.clasificacion

                    FROM $TABLA_TERCEROS AS T
                    WHERE 
                        T.activo = 1
                        AND T.borrado = 0
                    ORDER BY T.clasificacion ASC
        ";
        $listado_items= $Quick_function->SQLDatos_SA($select);
        while ($row = $listado_items->fetch()) {
            $clientes_proveedores[] = $row;
        }
    /* Trae el listado de Clientes / Proveedores */

    /* Trae el listado de los items */
        $ITEMS_LISTADO = [];
        $TABLA_ITEMS_LISTADO = TABLA_ITEMS_LISTADO;
        $TABLA_CUENTAS_CONTABLES = TABLA_CUENTAS_CONTABLES;
        $select = " SELECT 
                        I.id,
                        I.nombre_item,
                        I.monto_base,
                        I.activo,
                        I.id_cuenta,
                        I.naturaleza
                    FROM $TABLA_ITEMS_LISTADO AS I
                        INNER JOIN $TABLA_CUENTAS_CONTABLES CC
                            ON CC.id = I.id_cuenta
                    WHERE 
                        I.activo = 1
                        AND CC.activo = 1

        ";
        $listado_items= $Quick_function->SQLDatos_SA($select);
        while ($row = $listado_items->fetch()) {
            $ITEMS_LISTADO[] = $row;
        }
    /* Trae el listado de los items */
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
                <form id="form_asiento" action="procedures/asientos.php" method="post">
                    <div class="row">

                        <div class="card shadow-lg border-0 rexy-card-nuevo" style="max-width: 100%;">
                            <div class="card-body px-5 py-4 text-center text-md-left">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h5 class="mb-2">Agregar asiento</h5>
                                    </div>
                                    <div class="col-12 col-md-6 mt-4 mt-md-0 text-md-right"></div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-lg border-0 rexy-card-nuevo" style="max-width: 100%;">
                            <div class="card-body px-5 py-4 text-center text-md-left">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <h5>Encabezado del asiento</h5>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <small class="form-text text-dark">* Fecha del asiento</small>
                                            <input type="text" class="form-control" placeholder="* Fecha del asiento" name="fecha" id="fecha">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <small class="form-text text-dark">Número de entrada</small>
                                            <input type="text" class="form-control" placeholder="Número de entrada" name="referencia" id="referencia">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <?php 
                                                $tc = 0; $listado_monedas_lbl = '';
                                                foreach ($listado_monedas as $value) {
                                                    $tc = ($value["id"] == 2)? $value["venta"] : $tc;
                                                    $listado_monedas_lbl .= '<option $disabled value="'.$value["id"].'">'.$value["simbolo"].' - '.$value["nombre"].'</option>';
                                                }
                                            ?>
                                            <small class="form-text text-dark col-4">* Moneda</small>
                                            <small class="form-text text-dark col-8 text-right">Tipo cambio: <?php echo $tc; ?></small>
                                            <select name="id_moneda" id="id_moneda"  class="selectpicker col-12" data-live-search="true">
                                                <?php echo $listado_monedas_lbl; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <small class="form-text text-dark">Comentario (alternativo)</small>
                                            <textarea class="form-control" name="comentario" id="comentario" placeholder="Comentario (alternativo)" rows="1" resize="none"></textarea>
                                        </div>
                
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-lg border-0 rexy-card-nuevo" style="max-width: 100%;">
                            <div class="card-body px-5 py-4 text-center text-md-left">
                                <div class="row align-items-center">
                                    
                                    <div class="col-md-12">
                                        <h5>Línea de asiento</h5>
                                    </div>

                                    <div class="col-md-6 text-center mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="ctack" name="tipo_origen" class="custom-control-input" value="1" onchange="validar_origen()" checked>
                                            <label class="custom-control-label" for="ctack">Cuenta contable</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="itemck" name="tipo_origen" class="custom-control-input" value="2" onchange="validar_origen()">
                                            <label class="custom-control-label" for="itemck">Item</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 text-center mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="debeck" name="tipo_linea" class="custom-control-input" value="1" checked>
                                            <label class="custom-control-label" for="debeck">Debe</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="haberck" name="tipo_linea" class="custom-control-input" value="2">
                                            <label class="custom-control-label" for="haberck">Haber</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 text-center">
                                        <hr/>
                                    </div>

                                    <div id="content_cta" class="col-md-4">
                                        <div class="form-group">
                                            <small class="form-text text-dark">Cuenta contable</small>
                                            <select name="id_cuenta" id="id_cuenta" class="selectpicker" data-live-search="true">
                                                <?php 
                                                    foreach ($CTAS_CONTABLES_ACTIVAS as $value) {
                                                        $disabled   = ($value['posee_hijo'] == 1)? 'disabled': '';
                                                        $id         = ($value['posee_hijo'] == 1)?  '': $value['id'];
                                                        echo "<option $disabled value='$id'>".$value["codigo"].' - '.$value["nombre"].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="content_item" class="col-md-4" style="display:none;">
                                        <div class="form-group">
                                            <small class="form-text text-dark">Item asociado</small>
                                            <select name="id_item" id="id_item" class="selectpicker" data-live-search="true" onchange="analiza_item()">
                                                <option value="0">Seleccione un item</option>
                                                <?php 
                                                    foreach ($ITEMS_LISTADO as $value) {
                                                        echo "<option value='".$value["id"]."'>".$value["nombre_item"].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <small class="form-text text-dark">Monto de la línea</small>
                                            <input type="text" class="form-control" placeholder="Monto de la línea" name="monto" id="monto">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <small class="form-text text-dark">Cliente / Proveedor (opcional)</small>
                                            <select name="tercero" id="tercero" class="selectpicker" data-live-search="true">
                                                <option value="0">No posee</option>
                                                <?php 
                                                    foreach ($clientes_proveedores as $value) {
                                                        $clasificacion = ($value["clasificacion"] == "1")? 'Proveedor' : '';
                                                        $clasificacion = ($value["clasificacion"] == "2")? 'Cliente' : $clasificacion;
                                                        $clasificacion = ($value["clasificacion"] == "3")? 'Proveedor / Cliente' : $clasificacion;
                                                        echo '<option $disabled value="'.$value["id"].'">'.$value["nombre"].' - ('.$clasificacion.')</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-right">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-dark" onclick="agregar_linea()">Agregar línea</button>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <hr/>
                                    </div>

                                    <div class="col-12 overflowX">
                                        <table class="table" id="listado">
                                            <thead class="rexy-encabezado-tabla">
                                                <tr>
                                                    <th scope="col" class="text-justify">Cta Contable / Item</th>
                                                    <th scope="col" class="text-center">Debe</th>
                                                    <th scope="col" class="text-center">Haber</th>
                                                    <th scope="col" class="text-justify">Cliente / Proveedor</th>
                                                    <th scope="col" class="text-center">Editar</th>
                                                    <th scope="col" class="text-center">Borrar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lineas_asiento_html" class="rexy-cuerpo-tabla"></tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="col-md-12 text-center">
                                        <hr/>
                                    </div>
                                    
                                    <div class="col-md-6" >
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

                                </div>
                            </div>
                        </div>

                        <div class="card shadow-lg border-0 rexy-card-nuevo" style="display:none;">
                            <div class="card-body px-5 py-4 text-center text-md-left">
                                <div class="row">
                                    <textarea name="lineas_asiento" id="lineas_asiento" style="width: 100%;" rows="10">{"debe":[], "haber":[]}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-lg border-0 rexy-card-nuevo" style="max-width: 100%;">
                            <div class="card-body px-5 py-4 text-center text-md-left">
                                <div class="row">
                                    <div class="col-12 modal-footer" style="border-top: 0 !important;">
                                        <a type="button" class="btn btn-secondary" href="asientos.php">Cerrar</a>
                                        <button id="guardar_asiento" type="submit" class="btn btn-dark">Guardar asiento</button>
                                    </div>
                                    <input type="hidden" id="formaction_item" name="formaction" value="create_DB">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="editar_linea" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="texto_Modal_metodo">Editar línea asiento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        
                        <div class="col-md-6 text-center mb-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="ctack_edit" name="tipo_origen_edit" class="custom-control-input" value="1" onchange="validar_origen_edit()" checked>
                                <label class="custom-control-label" for="ctack_edit">Cuenta contable</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="itemck_edit" name="tipo_origen_edit" class="custom-control-input" value="2" onchange="validar_origen_edit()">
                                <label class="custom-control-label" for="itemck_edit">Item</label>
                            </div>
                        </div>

                        <div class="col-md-6 text-center mb-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="debeck_edit" name="tipo_linea_edit" class="custom-control-input" value="1">
                                <label class="custom-control-label" for="debeck_edit">Debe</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="haberck_edit" name="tipo_linea_edit" class="custom-control-input" value="2">
                                <label class="custom-control-label" for="haberck_edit">Haber</label>
                            </div>
                        </div>
                        
                        <div class="col-md-12 text-center">
                            <hr/>
                        </div>

                        <div id="content_cta_edit" class="col-md-4">
                            <div class="form-group">
                                <small class="form-text text-dark">Cuenta contable</small>
                                <select name="id_cuenta_edit" id="id_cuenta_edit" class="selectpicker" data-live-search="true">
                                    <?php 
                                        foreach ($CTAS_CONTABLES_ACTIVAS as $value) {
                                            $disabled   = ($value['posee_hijo'] == 1)? 'disabled': '';
                                            $id         = ($value['posee_hijo'] == 1)?  '': $value['id'];
                                            echo "<option $disabled value='$id'>".$value["codigo"].' - '.$value["nombre"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div id="content_item_edit" class="col-md-4" style="display:none;">
                            <div class="form-group">
                                <small class="form-text text-dark">Item asociado</small>
                                <select name="id_item_edit" id="id_item_edit" class="selectpicker" data-live-search="true" onchange="analiza_item_edit()">
                                    <option value="">Seleccione un item</option>
                                    <?php 
                                        foreach ($ITEMS_LISTADO as $value) {
                                            echo "<option value='".$value["id"]."'>".$value["nombre_item"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <small class="form-text text-dark">Monto de la línea</small>
                                <input type="text" class="form-control" placeholder="Monto de la línea" name="monto_edit" id="monto_edit">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <small class="form-text text-dark">Cliente / Proveedor (opcional)</small>
                                <select name="tercero_edit" id="tercero_edit" class="selectpicker" data-live-search="true">
                                    <option value="0">No posee</option>
                                    <?php 
                                        foreach ($clientes_proveedores as $value) {
                                            $clasificacion = ($value["clasificacion"] == "1")? 'Proveedor' : '';
                                            $clasificacion = ($value["clasificacion"] == "2")? 'Cliente' : $clasificacion;
                                            $clasificacion = ($value["clasificacion"] == "3")? 'Proveedor / Cliente' : $clasificacion;
                                            echo '<option $disabled value="'.$value["id"].'">'.$value["nombre"].' - ('.$clasificacion.')</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button onclick="guardar_edicion()" type="submit" class="btn btn-dark">Guardar</button>
                    </div>
                </div>
                <input type="hidden" id="index_edit" name="index_edit" value="">
                <input type="hidden" id="tipo_linea_original_edit" name="tipo_linea_original_edit" value="">
            </div>
        </div>

        <?php include_once("template/libs.php") ?>
        <?php include_once("template/footer.php") ?>

        
        <script type="text/javascript">
            function analiza_item() {
                let listado_items = <?php
                    $ITEMS_LISTADO_temp = [];
                    foreach ($ITEMS_LISTADO as $value) {
                        $ITEMS_LISTADO_temp[] = array(
                            "id"            => $value["id"],
                            "monto_base"    => $value["monto_base"],
                            "naturaleza"    => $value["naturaleza"],
                        );
                    }
                    echo json_encode($ITEMS_LISTADO_temp);
                ?>;
                
                id_item = $('#id_item').val()

                if(id_item != ''){
                    listado_items.forEach(function (item) {
                        if(item.id == id_item){
                            $('#monto').val(item.monto_base)
                            if(item.naturaleza == 1){ $('#debeck').prop('checked', true) }
                            else if(item.naturaleza == 2){ $('#haberck').prop('checked', true) }
                        }
                    })
                }
                else{
                    $('#monto').val(0)
                    $('#debeck').prop('checked', true)
                }
            }

            function analiza_item_edit() {
                let listado_items = <?php
                    $ITEMS_LISTADO_temp = [];
                    foreach ($ITEMS_LISTADO as $value) {
                        $ITEMS_LISTADO_temp[] = array(
                            "id"            => $value["id"],
                            "monto_base"    => $value["monto_base"],
                            "naturaleza"    => $value["naturaleza"],
                        );
                    }
                    echo json_encode($ITEMS_LISTADO_temp);
                ?>;
                
                id_item = $('#id_item_edit').val()

                if(id_item != ''){
                    listado_items.forEach(function (item) {
                        if(item.id == id_item){
                            $('#monto_edit').val(item.monto_base)
                            if(item.naturaleza == 1){ $('#debeck_edit').prop('checked', true) }
                            else if(item.naturaleza == 2){ $('#haberck_edit').prop('checked', true) }
                        }
                    })
                }
                else{
                    $('#monto_edit').val(0)
                    $('#debeck_edit').prop('checked', true)
                }
            }

            function validar_origen(){
                let tipo_origen = $("input[name='tipo_origen']:checked").val()
                $("#content_cta").hide()
                $("#content_item").hide()
                if(tipo_origen == 1){ $("#content_cta").show() }
                else if(tipo_origen == 2){ $("#content_item").show() }
                $('#monto').val(0)
                $('#debeck').prop('checked', true)
            }

            function validar_origen_edit(){
                let tipo_origen = $("input[name='tipo_origen_edit']:checked").val()
                $("#content_cta_edit").hide()
                $("#content_item_edit").hide()
                if(tipo_origen == 1){ $("#content_cta_edit").show() }
                else if(tipo_origen == 2){ $("#content_item_edit").show() }
                $('#monto_edit').val(0)
                $('#debeck_edit').prop('checked', true)
            }

            function agregar_linea() {
                let lineas_asiento   = JSON.parse($("#lineas_asiento").val())

                let tipo_origen     = $("input[name='tipo_origen']:checked").val()
                let tipo_linea      = $("input[name='tipo_linea']:checked").val()

                let id_cuenta       = $("#id_cuenta").val()
                let id_cuenta_lbl   = $("#id_cuenta option:selected").text()

                let id_item         = $("#id_item").val()
                let id_item_lbl     = $("#id_item option:selected").text()

                let tercero         = $("#tercero").val()
                let tercero_lbl     = $("#tercero option:selected").text()
                
                let monto           = $("#monto").val()
                let agrega          = true
                let mensaje         = ''

                /* valida monto */
                    if(monto <= 0){
                        mensaje = 'Debe introducir un monto válido'
                        agrega = false
                    }
                /* valida monto */

                /* valida tipo origen */
                    if(tipo_origen == 2){
                        if(id_item == ''){
                            mensaje = 'Debe seleccionar un item válido'
                            agrega = false
                        }
                        id_cuenta_lbl = ''
                    }
                    else{
                        id_item_lbl = ''
                    }
                /* valida tipo origen */

                if(tercero == 0){
                    tercero_lbl = '-'
                }

                /* Agrega la linea */
                    if(agrega){
                        if(tipo_linea == 1){ /* debe */
                            lineas_asiento.debe.push({
                                tipo_origen,
                                tipo_linea,
                                id_cuenta,
                                id_cuenta_lbl,
                                id_item,
                                id_item_lbl,
                                monto,
                                tercero,
                                tercero_lbl
                            })
                        }
                        else if(tipo_linea == 2){ /* haber */
                            lineas_asiento.haber.push({
                                tipo_origen,
                                tipo_linea,
                                id_cuenta,
                                id_cuenta_lbl,
                                id_item,
                                id_item_lbl,
                                monto,
                                tercero,
                                tercero_lbl
                            })
                        }
                        $("#lineas_asiento").val(JSON.stringify(lineas_asiento))
                    }
                    else{
                        lanzar_msg(2, 'Error', mensaje, boton = 'Regresar')
                    }
                /* Agrega la linea */
                mostrar_lineas()
            }

            function mostrar_lineas() {
                let lineas_asiento   = JSON.parse($("#lineas_asiento").val())
                let lineas_html = ''

                total_debe = total_haber = 0

                lineas_asiento.debe.forEach(function (linea, index) {
                    let monto = Number(linea.monto)
                    monto = addCommas(monto.toFixed(2))

                    let cta_item = (linea.tipo_origen == 1)? linea.id_cuenta_lbl: linea.id_item_lbl

                    lineas_html = `${lineas_html}
                        <tr>
                            <th class="text-justify" scope="row">${cta_item}</th>
                            <td class="text-center">${monto}</td>
                            <td class="text-center">-</td>
                            <td class="text-justify">${linea.tercero_lbl}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#AgregarDato" onclick="editar(${index}, 1)">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Eliminar" onclick="eliminar(${index}, 1)">
                                    <span class="btn-inner--icon">
                                        <i class="far fa-trash-alt"></i>
                                    </span>
                                </button>
                            </td>
                        </tr>
                    `
                    total_debe += Number(linea.monto)
                })
                lineas_asiento.haber.forEach(function (linea, index) {
                    let monto = Number(linea.monto)
                    monto = addCommas(monto.toFixed(2))
                    
                    let cta_item = (linea.tipo_origen == 1)? linea.id_cuenta_lbl: linea.id_item_lbl

                    lineas_html = `${lineas_html}
                        <tr>
                            <th class="text-justify" scope="row">${cta_item}</th>
                            <td class="text-center">-</td>
                            <td class="text-center">${monto}</td>
                            <td class="text-justify">${linea.tercero_lbl}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-dark btn-icon-only btn-sm" data-toggle="modal" data-target="#AgregarDato" onclick="editar(${index}, 2)">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-icon-only btn-sm" data-toggle="tooltip" data-placement="bottom" title="Eliminar" onclick="eliminar(${index}, 2)">
                                    <span class="btn-inner--icon">
                                        <i class="far fa-trash-alt"></i>
                                    </span>
                                </button>
                            </td>
                        </tr>
                    `
                    total_haber += Number(linea.monto)
                })
                $('#lineas_asiento_html').html(lineas_html)
                $('#total_debe').html(addCommas(total_debe.toFixed(2)))
                $('#total_haber').html(addCommas(total_haber.toFixed(2)))
            }

            function editar(index, tipo) {
                let lineas_asiento  = JSON.parse($("#lineas_asiento").val())
                let linea           = []

                if(tipo == 1){ /* el tipo es debe */
                    linea = lineas_asiento.debe[index]
                    $("#debeck_edit").prop("checked", true)
                }
                else if(tipo == 2){ /* el tipo es haber */
                    linea = lineas_asiento.haber[index]
                    $("#haberck_edit").prop("checked", true)
                }

                $('#content_cta_edit').hide()
                $('#content_item_edit').hide()
                if(linea.tipo_origen == 1){
                    $('#content_cta_edit').show()
                    $("#ctack_edit").prop("checked", true)
                }else if(linea.tipo_origen == 2){
                    $('#content_item_edit').show()
                    $("#itemck_edit").prop("checked", true)
                }

                $("#id_cuenta_edit").val(linea.id_cuenta)
                $("#id_item_edit").val(linea.id_item)
                $("#tercero_edit").val(linea.tercero)
                $("#monto_edit").val(linea.monto)

                $("#index_edit").val(index)
                $("#tipo_linea_original_edit").val(linea.tipo_linea)

                refrescar_selectpicker()
                $('#editar_linea').modal('show')
            }

            function eliminar(index, tipo) {
                let lineas_asiento   = JSON.parse($("#lineas_asiento").val())
                if(tipo == 1){ /* el tipo es debe */
                    lineas_asiento.debe.splice(index, 1)
                }
                else if(tipo == 2){ /* el tipo es haber */
                    lineas_asiento.haber.splice(index, 1)
                }
                $("#lineas_asiento").val(JSON.stringify(lineas_asiento))
                mostrar_lineas()
            }

            function guardar_edicion(){
                let lineas_asiento   = JSON.parse($("#lineas_asiento").val())

                let tipo_origen     = $("input[name='tipo_origen_edit']:checked").val()
                let tipo_linea      = $("input[name='tipo_linea_edit']:checked").val()

                let id_cuenta       = $("#id_cuenta_edit").val()
                let id_cuenta_lbl   = $("#id_cuenta_edit option:selected").text()

                let id_item         = $("#id_item_edit").val()
                let id_item_lbl     = $("#id_item_edit option:selected").text()

                let tercero         = $("#tercero_edit").val()
                let tercero_lbl     = $("#tercero_edit option:selected").text()
                
                let monto           = $("#monto_edit").val()
                let agrega          = true
                let mensaje         = ''

                let index_edit                  = $("#index_edit").val()
                let tipo_linea_original_edit    = $("#tipo_linea_original_edit").val()
                let id_item_edit                = $("#id_item_edit").val()
                /* valida monto */
                    if(monto <= 0){
                        mensaje = 'Debe introducir un monto válido'
                        agrega = false
                    }
                /* valida monto */

                /* valida tipo origen */
                    if(tipo_origen == 2){
                        /* alert(`${id_item} ${id_item_lbl} `) */
                        if(id_item == ''){
                            mensaje = 'Debe seleccionar un item válido'
                            agrega = false
                        }
                        id_cuenta_lbl = ''
                    }
                    else{
                        id_item_lbl = ''
                    }
                /* valida tipo origen */

                if(tercero == 0){
                    tercero_lbl = '-'
                }

                /* Agrega la linea */
                    if(agrega){

                        if(tipo_linea_original_edit == tipo_linea){ /* es el mismo se debe actualizar */
                            if(tipo_linea == 1){ /* debe */
                                lineas_asiento.debe[index_edit] = {
                                    tipo_origen,
                                    tipo_linea,
                                    id_cuenta,
                                    id_cuenta_lbl,
                                    id_item,
                                    id_item_lbl,
                                    monto,
                                    tercero,
                                    tercero_lbl
                                }
                            }
                            else if(tipo_linea == 2){ /* haber */
                                lineas_asiento.haber[index_edit] = {
                                    tipo_origen,
                                    tipo_linea,
                                    id_cuenta,
                                    id_cuenta_lbl,
                                    id_item,
                                    id_item_lbl,
                                    monto,
                                    tercero,
                                    tercero_lbl
                                }
                            }
                            $("#lineas_asiento").val(JSON.stringify(lineas_asiento))/*  */

                        }
                        else if(tipo_linea_original_edit != tipo_linea){ /* se cambio entre tipo de cuentas */

                            if(tipo_linea_original_edit == 1){ /* el tipo es debe */
                                lineas_asiento.debe.splice(index_edit, 1)
                            }
                            else if(tipo_linea_original_edit == 2){ /* el tipo es haber */
                                lineas_asiento.haber.splice(index_edit, 1)
                            }
                            
                            if(tipo_linea == 1){ /* debe */
                                lineas_asiento.debe.push({
                                    tipo_origen,
                                    tipo_linea,
                                    id_cuenta,
                                    id_cuenta_lbl,
                                    id_item,
                                    id_item_lbl,
                                    monto,
                                    tercero,
                                    tercero_lbl
                                })
                            }
                            else if(tipo_linea == 2){ /* haber */
                                lineas_asiento.haber.push({
                                    tipo_origen,
                                    tipo_linea,
                                    id_cuenta,
                                    id_cuenta_lbl,
                                    id_item,
                                    id_item_lbl,
                                    monto,
                                    tercero,
                                    tercero_lbl
                                })
                            }
                            $("#lineas_asiento").val(JSON.stringify(lineas_asiento))
                        }
                    }
                    else{
                        lanzar_msg(2, 'Error', mensaje, boton = 'Regresar')
                    }
                /* Agrega la linea */
                mostrar_lineas()
                $('#editar_linea').modal('hide')
            }

            $("#guardar_asiento").click(function(e) {
                let guardar = true
                let mensaje = ''

                let fecha           = $("#fecha").val()
                let lineas_asiento  = JSON.parse($("#lineas_asiento").val())
                
                if(fecha == ''){ 
                    guardar = false 
                    mensaje = 'Todos los campos son requeridos.'
                }

                if(
                    lineas_asiento.debe.length == 0 &&
                    lineas_asiento.haber.length == 0
                ){ /* No hay lineas */
                    guardar = false 
                    mensaje = 'El asiento no se puede guardar sin lineas.'
                }
                else if(lineas_asiento.debe.length == 0){ /* no hay lineas de debe */
                    guardar = false 
                    mensaje = 'El asiento no se puede guardar sin lineas de debe.'
                }
                else if( lineas_asiento.haber.length == 0){ /* no hay lineas de haber */
                    guardar = false 
                    mensaje = 'El asiento no se puede guardar sin lineas de haber.'
                }
                else{ /* si hay lineas, procede a revisar que cierre */
                    let total_debe = 0
                    let total_haber = 0

                    lineas_asiento.debe.forEach(function (linea, index) {
                        total_debe += Number(linea.monto)
                        total_debe = Number(total_debe.toFixed(2))
                    })
                    lineas_asiento.haber.forEach(function (linea, index) {
                        total_haber += Number(linea.monto)
                        total_haber = Number(total_haber.toFixed(2))
                    })

                    console.log(`total_debe != total_haber => ${total_debe} != ${total_haber}`)

                    if(total_debe != total_haber){
                        guardar = false 
                        mensaje = 'Asiento no cuadrado, verifique los montos de debe y haber.'
                    }
                }
                
                if(guardar){
                    $('#loading-request').show();
                }
                else{
                    lanzar_msg(2, 'Error', mensaje, boton = 'Regresar')
                    e.preventDefault();
                }
            })

            $(document).ready(function () {
                crear_datePicker('fecha')
                mostrar_lineas()
            })
        </script>
    </body>

</html>