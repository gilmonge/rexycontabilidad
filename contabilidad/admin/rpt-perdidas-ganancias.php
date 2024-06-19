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
    
	$Mes_ingles  = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $Mes_espanol = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    
	$Dias_ingles  = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	$Dias_espanol = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');

    /* Da formato a los HTML */
        function format_html($datos){
            global $Quick_function;
            $w1 = (isset($datos['w1']))? $datos['w1']: '';
            $w2 = (isset($datos['w2']))? $datos['w2']: '';
            $titulo = (isset($datos['titulo']))? $datos['titulo']: '';
            
            $monto_1 = (isset($datos['monto_1']) && $datos['monto_1'] != '')? $Quick_function->Money__Format($datos['monto_1']): '';

            $monto_2 = (isset($datos['monto_2']) && $datos['monto_2'] != '')? $Quick_function->Money__Format($datos['monto_2']): '';
            $linea_monto_2 = (isset($datos['monto_2']))? "border-top:1px solid #000;": '';
            
            $monto_3 = (isset($datos['monto_3']) && $datos['monto_3'] != '')? $Quick_function->Money__Format($datos['monto_3']): '';
            $linea_monto_3 = (isset($datos['monto_3']))? "border-bottom:1px solid #000;": '';

            $monto_4 = (isset($datos['monto_4']) && $datos['monto_4'] != '')? $Quick_function->Money__Format($datos['monto_4']): '';
            $linea_monto_4 = (isset($datos['monto_4']))? "border-bottom:1px solid #000;": '';
            $html = '
                <tr style="line-height: 0.6cm !important;">
                    <td style="width: '.$w1.' !important; text-align: justify;"></td>
                    <td style="width: '.$w2.' !important; text-align: justify;">'.$titulo.'</td>
                    <td style="width: 2.5cm   !important; text-align: right; '.$linea_monto_2.'">'.$monto_1.'</td>
                    <td style="width: 2.5cm   !important; text-align: right;"><b>'.$monto_2.'</b></td>
                    <td style="width: 2.5cm   !important; text-align: right; '.$linea_monto_3.'"><b>'.$monto_3.'</b></td>
                    <td style="width: 2.5cm   !important; text-align: right; '.$linea_monto_4.'"><b>'.$monto_4.'</b></td>
                </tr>
            ';
            return $html;
        }
    /* Da formato a los HTML */

    /* Trae los asientos */
        function listar_asientos(){
            global $db, $Mes_ingles, $Mes_espanol, $Dias_ingles, $Dias_espanol, $asientos, $desde, $hasta, $Quick_function;

            $asiento_lineas = '';

            if($desde != '' && $hasta != ''){
                /* Trae el listado de cuentas */
                    $ctas_contables = $Quick_function->traer_ctas_contables();
                /* Trae el listado de cuentas */
                $TABLA_ASIENTOS = TABLA_ASIENTOS;
                $TABLA_MONEDAS  = TABLA_MONEDAS;
                
                $select = " SELECT 
                                A.id,
                                A.id AS id_asiento,
                                A.numero_asiento,
                                A.fecha,
                                A.referencia_documento,
                                A.tipo_cambio,
                                A.comentario,
                                A.procedencia,
                                A.activo,
                                
                                M.codificacion
                            FROM $TABLA_ASIENTOS AS A
                                INNER JOIN $TABLA_MONEDAS AS M
                                    ON M.id = A.id_moneda
                            WHERE 
                                fecha       >= '$desde' 
                                AND fecha   <= '$hasta'
                ";
                $listado_items= $Quick_function->SQLDatos_SA($select);
                $lineas_estado = [];
                while ($row = $listado_items->fetch()) {
                    $lineas = $asientos->listar_lineas_perdidas_gananciasDB($row['id'], true);
                    
                    foreach ($lineas as $key => $value) { /* key indica si es ingreso o gasto */
                        foreach ($value as $subkey => $dato) { /* subkey indica el subtipo de ingreso o gasto */
                            foreach ($dato as $cta_key => $asiento) { /* cta_key indica el id de la cuenta */
                                $nombre = $asiento['nombre'];
                                $monto  = $asiento['monto'];
                                $accion = $asiento['accion']; /* 0 resta, 1 suma */
                                $monto_historico = (isset($lineas_estado[$key][$subkey][$cta_key]['monto']))? $lineas_estado[$key][$subkey][$cta_key]['monto']: 0;

                                $nuevo_monto = ($accion == 1)? ($monto_historico + $monto) : ($monto_historico - $monto);
                                
                                $lineas_estado[$key][$subkey][$cta_key]['nombre']   = $nombre;
                                $lineas_estado[$key][$subkey][$cta_key]['monto']    = $nuevo_monto;
                            }
                        }
                    }
                }

                /* Estructura el html */
                    $total_ingresos = $utilidades_del_periodo = 0;
                    $total_costos = $total_gastos = 0;
                    $total_Marketing = 
                    $total_Estructura = 
                    $total_Salarios = 
                    $total_Impuestos = 
                    $total_Financieros = 0;
                /* Estructura el html */

                if(isset($lineas_estado[-8]) && (count($lineas_estado[-8]) > 0)){ /* Ingresos */
                    $asiento_lineas .= format_html(array(
                        "w1"      => "2cm",
                        "w2"      => "8cm",
                        "titulo"  => "<b>Ingresos</b>",
                    ));
                    
                    foreach ($lineas_estado[-8] as $keyIngresos => $valueIngresos) {
                        if($keyIngresos == 0){ /* No tienen subClasificacion solo se muestran */
                            foreach ($valueIngresos as $keyDato => $valueDato) {
                                if($valueDato['monto'] > 0){
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $valueDato['nombre'],
                                        "monto_1" => $valueDato['monto'],
                                    ));
                                    $total_ingresos += $valueDato['monto'];
                                }
                            }
                        }
                        else{ /* Poseen subClasificacion por lo que debe de tomar en cuenta su cuenta padre */
                            foreach ($ctas_contables as $key => $value) {
                                if ($value['id'] == $keyGastos) {
                                    $CuentaCTA = $value;
                                }
                            }
                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>".$CuentaCTA['nombre']."</b>",
                            ));
                            $total_ingresos_subcuenta = 0;

                            foreach ($valueGastos as $keyDato => $valueDato) {
                                if($valueDato['monto'] > 0){
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $valueDato['nombre'],
                                        "monto_1" => $valueDato['monto'],
                                    ));
                                    $total_ingresos_subcuenta += $valueDato['monto'];
                                }
                            }
                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>Total ".$CuentaCTA['nombre']."</b>",
                                "monto_2" => $total_ingresos_subcuenta,
                            ));
                            $total_ingresos += $total_ingresos_subcuenta;
                        }
                    }

                    $asiento_lineas .= format_html(array(
                        "w1"      => "2cm",
                        "w2"      => "8cm",
                        "titulo"  => "<b>Total ingresos</b>",
                        "monto_3" => $total_ingresos,
                    ));
                }
                if(isset($lineas_estado[-9]) && (count($lineas_estado[-9]) > 0)){ /* Gastos */
                    $asiento_lineas .= format_html(array(
                        "w1"      => "2cm",
                        "w2"      => "8cm",
                        "titulo"  => "<b>Gastos</b>",
                    ));

                    foreach ($lineas_estado[-9] as $keyGastos => $valueGastos) {
                        if($keyGastos == 0){ /* No tienen subClasificacion solo se muestran */
                            foreach ($valueIngresos as $keyDato => $valueDato) {
                                if($valueDato['monto'] > 0){
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $valueDato['nombre'],
                                        "monto_1" => $valueDato['monto'],
                                    ));
                                    $total_gastos += $valueDato['monto'];
                                }
                            }
                        }
                        else{ /* Poseen subClasificacion por lo que debe de tomar en cuenta su cuenta padre */
                            foreach ($ctas_contables as $key => $value) {
                                if ($value['id'] == $keyGastos) {
                                    $CuentaCTA = $value;
                                }
                            }
                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>".$CuentaCTA['nombre']."</b>",
                            ));
                            $total_gastos_subcuenta = 0;
                            
                            foreach ($valueGastos as $keyDato => $valueDato) {
                                if($valueDato['monto'] > 0){
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $valueDato['nombre'],
                                        "monto_1" => $valueDato['monto'],
                                    ));
                                    $total_gastos_subcuenta += $valueDato['monto'];
                                }
                            }
                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>Total ".$CuentaCTA['nombre']."</b>",
                                "monto_2" => $total_gastos_subcuenta,
                            ));
                            $total_gastos += $total_gastos_subcuenta;
                        }
                    }

                    $asiento_lineas .= format_html(array(
                        "w1"      => "2cm",
                        "w2"      => "8cm",
                        "titulo"  => "<b>Total gastos</b>",
                        "monto_3" => $total_gastos,
                    ));
                }
                
                $utilidades_del_periodo = $total_ingresos - $total_gastos;
                
                $asiento_lineas .= format_html(array(
                    "w1"      => "1.5cm",
                    "w2"      => "8.5cm",
                    "titulo"  => "<b>Utilidades del Periodo</b>",
                    "monto_4" => $utilidades_del_periodo,
                ));
            }
            return $asiento_lineas;
        }
    /* Trae los asientos */

    function basura(){
                /* Estructura el html */

                    if(isset($lineas_estado[-8]) && (count($lineas_estado[-8]) > 0)){ /* Ingresos */
                        if(isset($lineas_estado[-8])){
                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>Ingresos</b>",
                            ));
                            
                            $total_ingresos_ganancias = 0;
                            foreach ($lineas_estado[-8] as $lineas) {
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "3cm",
                                    "w2"      => "7cm",
                                    "titulo"  => $lineas['nombre'],
                                    "monto_1" => $lineas['monto'],
                                ));
                                $total_ingresos_ganancias += $lineas['monto'];
                                $total_ingresos += $lineas['monto'];
                            }
                            /* if(isset($lineas_estado[-8][1])){
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Ingresos o ganancias</b>",
                                ));
                                
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Total ingresos o ganancias</b>",
                                    "monto_2" => $total_ingresos_ganancias,
                                ));
                            } */
                            
                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>Total ingresos</b>",
                                "monto_3" => $total_ingresos,
                            ));
                        }
                    } 
                    if(isset($lineas_estado[-9]) && (count($lineas_estado[-9]) > 0)){ /* Gastos */
                        if(
                            isset($lineas_estado[5][1]) || /* Marketing */
                            isset($lineas_estado[5][2]) || /* Estructura */
                            isset($lineas_estado[5][3]) || /* Salarios */
                            isset($lineas_estado[5][4]) || /* Impuestos */
                            isset($lineas_estado[5][5])    /* Financieros */
                        ){
                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>Gastos</b>",
                            ));

                            if((isset($lineas_estado[5][1]) && (count($lineas_estado[5][1]) > 0))){
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Marketing</b>",
                                ));
                                $total_gastos_marketing = 0;
                                foreach ($lineas_estado[5][1] as $lineas) {
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $lineas['nombre'],
                                        "monto_1" => $lineas['monto'],
                                    ));
                                    $total_gastos_marketing += $lineas['monto'];
                                    $total_gastos += $lineas['monto'];
                                }
                                
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Total marketing</b>",
                                    "monto_2" => $total_gastos_marketing,
                                ));
                            }

                            if((isset($lineas_estado[5][2]) && (count($lineas_estado[5][2]) > 0))){
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Estructura</b>",
                                ));
                                $total_gastos_estructura = 0;
                                foreach ($lineas_estado[5][2] as $lineas) {
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $lineas['nombre'],
                                        "monto_1" => $lineas['monto'],
                                    ));
                                    $total_gastos_estructura += $lineas['monto'];
                                    $total_gastos += $lineas['monto'];
                                }
                                
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Total estructura</b>",
                                    "monto_2" => $total_gastos_estructura,
                                ));
                            }

                            if((isset($lineas_estado[5][3]) && (count($lineas_estado[5][3]) > 0))){
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Salarios</b>",
                                ));
                                $total_gastos_salarios = 0;
                                foreach ($lineas_estado[5][3] as $lineas) {
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $lineas['nombre'],
                                        "monto_1" => $lineas['monto'],
                                    ));
                                    $total_gastos_salarios += $lineas['monto'];
                                    $total_gastos += $lineas['monto'];
                                }
                                
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Total salarios</b>",
                                    "monto_2" => $total_gastos_salarios,
                                ));
                            }

                            if((isset($lineas_estado[5][4]) && (count($lineas_estado[5][4]) > 0))){
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Impuestos</b>",
                                ));
                                $total_gastos_impuestos = 0;
                                foreach ($lineas_estado[5][4] as $lineas) {
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $lineas['nombre'],
                                        "monto_1" => $lineas['monto'],
                                    ));
                                    $total_gastos_impuestos += $lineas['monto'];
                                    $total_gastos += $lineas['monto'];
                                }
                                
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Total impuestos</b>",
                                    "monto_2" => $total_gastos_impuestos,
                                ));
                            }

                            if((isset($lineas_estado[5][5]) && (count($lineas_estado[5][5]) > 0))){
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Financieros</b>",
                                ));
                                $total_gastos_financieros = 0;
                                foreach ($lineas_estado[5][5] as $lineas) {
                                    $asiento_lineas .= format_html(array(
                                        "w1"      => "3cm",
                                        "w2"      => "7cm",
                                        "titulo"  => $lineas['nombre'],
                                        "monto_1" => $lineas['monto'],
                                    ));
                                    $total_gastos_financieros += $lineas['monto'];
                                    $total_gastos += $lineas['monto'];
                                }
                                
                                $asiento_lineas .= format_html(array(
                                    "w1"      => "2.5cm",
                                    "w2"      => "7.5cm",
                                    "titulo"  => "<b>Total financieros</b>",
                                    "monto_2" => $total_gastos_financieros,
                                ));
                            }

                            $asiento_lineas .= format_html(array(
                                "w1"      => "2cm",
                                "w2"      => "8cm",
                                "titulo"  => "<b>Total gastos</b>",
                                "monto_3" => $total_gastos,
                            ));
                        }
                    }
                /* Estructura el html */

    }

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
                                <div class="col-md-4">
                                    <h5 class="mb-2">Reportes de pérdidas y ganancias</h5>
                                </div>
                                <div class="col-12 col-md-8 mt-4 mt-md-0 text-md-right">
                                    <form method="get">
                                        <div class="dropdown">
                                            <div class="form-group">
                                                <small class="form-text text-dark">* Desde</small>
                                                <input type="text" class="form-control" placeholder="* Desde" name="d" id="desde" value="<?php echo (isset($_GET['d']) && $_GET['d'] != '')? date('d-m-Y', strtotime($_GET['d'])): ''; ?>" >
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <div class="form-group">
                                                <small class="form-text text-dark">* Hasta</small>
                                                <input type="text" class="form-control" placeholder="* Hasta" name="h" id="hasta" value="<?php echo (isset($_GET['h']) && $_GET['h'] != '')? date('d-m-Y', strtotime($_GET['h'])): ''; ?>" >
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-dark">
                                            Filtrar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 overflowX" style="min-height: 150px;">
                        <?php 
                            if(
                                (!isset($_GET['d']) || $_GET['d'] == '') && 
                                (!isset($_GET['h']) || $_GET['h'] == '')
                            ) {
                        ?>
                            <div class="alert alert-secondary text-primary" role="alert">
                                Debe seleccionar las fechas entre la que desea filtrar el reporte
                            </div>
                        <?php } else {
                            /* Incluye las librerias */
                                require('procedures/class_libraries/asientos.php');
                                $asientos = new asientos;
                            /* Incluye las librerias */

                            /* Fechas limites */
                                $desde = (isset($_GET['d']) && is_string($_GET['d']) )? date('Y-m-d',strtotime(strip_tags(trim($_GET['d'])))) : '';
                                $hasta = (isset($_GET['h']) && is_string($_GET['h']) )? date('Y-m-d',strtotime(strip_tags(trim($_GET['h'])))) : '';
                            /* Fechas limites */
                            
                            $asientos_info = listar_asientos();
                            ?>
                            <table style="width: 100% !important; border-top:1px solid #000; border-bottom:1px solid #000;">
                                <tbody class="rexy-cuerpo-tabla">
                                    <?php echo $asientos_info; ?>
                                </tbody>
                            </table>
                        <?php }?>
                    </div>
                </div>
            </div>
        </section>

        <?php include_once("template/libs.php") ?>
        <?php include_once("template/footer.php") ?>

        
        <script type="text/javascript">

            $(document).ready(function () {
                crear_datePicker('desde')
                crear_datePicker('hasta')
            })
        </script>
    </body>

</html>