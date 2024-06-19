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

    /* Trae los asientos */
        function listar_asientos(){
            global $db, $Mes_ingles, $Mes_espanol, $Dias_ingles, $Dias_espanol, $asientos, $desde, $hasta, $Quick_function;

            $asiento_lineas = '';

            if($desde != '' && $hasta != ''){
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
                while ($row = $listado_items->fetch()) {
                    
                    /* Fecha indicada manualmente */
                        $fecha = date('d-m-Y',strtotime($row['fecha']));
                        $fecha = strftime("%d/%b/%y", strtotime(date('m/d/Y', strtotime($fecha))));
                        $fecha = str_replace($Mes_ingles, $Mes_espanol, $fecha);
                        $fecha = str_replace($Dias_ingles, $Dias_espanol, $fecha);
                    /* Fecha indicada manualmente */
                        
                    /* Texto de procedencia de asiento */
                        $procedencia = '';
                        $procedencia    = ($row['procedencia'] == 1)? "Asientos contables" : $procedencia;
                    /* Texto de procedencia de asiento */

                    /* Se establece la información a mostrar */
                        $comentario     = ($row['comentario'] != '')? $row['comentario'] : "No aplica";

                        $informacion_basica = array(
                            "num_asiento"       => $row['numero_asiento'],
                            "activo"            => $row['activo'],
                            "fecha"             => $fecha,
                            "fecha_editar"      => date('d-m-Y', strtotime($row['fecha'])),
                            "entrada"           => $row['referencia_documento'],
                            "nombre_moneda"     => $Quick_function->Money__Format($row['tipo_cambio'], $row['codificacion']),
                            "procedencia"       => $procedencia,
                            "comentario"        => $comentario,
                        );
                    /* Se establece la información a mostrar */

                    $asiento = $asientos->listar_lineasDB($Quick_function, $row, true);
                    $asiento_lineas_tpm = $Cliente_Proveedor = $Cuenta = $Debito = $Credito = '';

                    $Total_debe = $Total_haber = $contador = 0;

                    /* {
                        "cantidad":2,
                        "lineas":{
                            "debe":[
                                {"cuenta_item":"1-1-3-1 - forex","monto":"\u20a12\u00a0000,00","tercero":null}
                            ],
                            "haber":[
                                {"cuenta_item":"1-1-1 - Caja y banco","monto":"\u20a12\u00a0000,00","tercero":null}
                            ]
                        }
                    }
                     */
                    foreach ($asiento['lineas'] as $key => $value) {
                        foreach ($value as $dato) {
                            $contador++;
                            if($contador == 1){
                                $Cliente_Proveedor  .= (($dato['tercero'] == '-')? '' : $dato['tercero'] );
                                $Cuenta             .= $dato['cuenta_item'];
                                $Debito             .= (($key == 'debe')    ? $dato['monto'] : '-' );
                                $Credito            .= (($key == 'haber')   ? $dato['monto'] : '-' );

                                if($key == 'debe'){      $Total_debe  += $dato['monto_base']; }
                                elseif($key == 'haber'){ $Total_haber += $dato['monto_base']; }
                            }
                            else{
                                $Cliente_Proveedor_tpm  = (($dato['tercero'] == '-')? '' : $dato['tercero'] );
                                $Cuenta_tpm             = $dato['cuenta_item'];
                                $Debito_tpm             = (($key == 'debe')    ? $dato['monto'] : '-' );
                                $Credito_tpm            = (($key == 'haber')   ? $dato['monto'] : '-' );
                                
                                if($key == 'debe'){      $Total_debe  += $dato['monto_base']; }
                                elseif($key == 'haber'){ $Total_haber += $dato['monto_base']; }

                                $asiento_lineas_tpm .= '
                                    <tr style="line-height: 0.6cm !important;">
                                        <td style="width: 5.5cm  !important;">'.$Cliente_Proveedor_tpm.'</td>
                                        <td style="width: 5.5cm  !important;">'.$Cuenta_tpm.'</td>
                                        <td style="width: 1.85cm !important;">'.$Debito_tpm.'</td>
                                        <td style="width: 1.85cm !important;">'.$Credito_tpm.'</td>
                                    </tr>
                                ';
                            }
                        }
                    }
                    $contador++;
                    $asiento_lineas .= '
                        <tr style="line-height: 0.6cm !important;">
                            <td style="width: 1.7cm  !important; text-align: center;" rowspan="'.$contador.'">'.$informacion_basica['num_asiento'].'</td>
                            <td style="width: 1.7cm  !important; text-align: center;" rowspan="'.$contador.'">'.$informacion_basica['fecha'].'</td>
                            <td style="width: 1.9cm  !important;" rowspan="'.$contador.'">'.$informacion_basica['entrada'].'</td>
                            <td style="width: 8.6cm  !important;" rowspan="'.$contador.'"><p>'.$informacion_basica['comentario'].'<br/><b>Procedencia:</b> '.$procedencia.'</p></td>

                            <td style="width: 5.5cm  !important;">'.$Cliente_Proveedor.'</td>
                            <td style="width: 5.5cm  !important;">'.$Cuenta.'</td>
                            <td style="width: 1.85cm !important;">'.$Debito.'</td>
                            <td style="width: 1.85cm !important;">'.$Credito.'</td>
                        </tr>
                        '.$asiento_lineas_tpm.'
                        <tr style="line-height: 0.6cm !important;">
                            <td style="width: 5.5cm  !important;"></td>
                            <td style="width: 5.5cm  !important; border-top:1px solid #000; text-align: right;"><b>Total</b></td>
                            <td style="width: 1.85cm !important; border-top:1px solid #000;">'.number_format($Total_debe, 2 ).'</td>
                            <td style="width: 1.85cm !important; border-top:1px solid #000;">'.number_format($Total_haber, 2 ).'</td>
                        </tr>
                        <tr style="line-height: 0.1cm !important;">
                            <td style="width: 1.7cm  !important; border-bottom:1px solid #000; text-align: center;"></td>
                            <td style="width: 1.5cm  !important; border-bottom:1px solid #000; text-align: center;"></td>
                            <td style="width: 1.9cm  !important; border-bottom:1px solid #000;"></td>
                            <td style="width: 8.8cm  !important; border-bottom:1px solid #000;"></td>

                            <td style="width: 5.5cm  !important; border-bottom:1px solid #000;"></td>
                            <td style="width: 5.5cm  !important; border-bottom:1px solid #000;"></td>
                            <td style="width: 1.85cm !important; border-bottom:1px solid #000;"></td>
                            <td style="width: 1.85cm !important; border-bottom:1px solid #000;"></td>
                        </tr>
                    ';
                }
            }
            return $asiento_lineas;
        }
    /* Trae los asientos */

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
                                    <h5 class="mb-2">Reportes de asientos contables</h5>
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
                                Debe seleccionar las fechas entre la que desea filtrar los asientos
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
                                <thead class="rexy-encabezado-tabla">
                                    <tr style="line-height: 0.6cm !important;">
                                        <th style="width: 1.7cm  !important; border-bottom:1px solid #000; text-align: center;"><b>Asiento #</b></th>
                                        <th style="width: 1.7cm  !important; border-bottom:1px solid #000; text-align: center;"><b>Fecha</b></th>
                                        <th style="width: 1.9cm  !important; border-bottom:1px solid #000; text-align: center;"><b>Referencia</b></th>
                                        <th style="width: 8.6cm  !important; border-bottom:1px solid #000; text-align: center;"><b>Descripción</b></th>

                                        <th style="width: 5.5cm  !important; border-bottom:1px solid #000; text-align: center;"><b>Cliente / Proveedor</b></th>
                                        <th style="width: 5.5cm  !important; border-bottom:1px solid #000; text-align: center;"><b>Cuenta</b></th>
                                        <th style="width: 1.85cm !important; border-bottom:1px solid #000; text-align: center;"><b>Débito</b></th>
                                        <th style="width: 1.85cm !important; border-bottom:1px solid #000; text-align: center;"><b>Crédito</b></th>
                                    </tr>
                                </thead>
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