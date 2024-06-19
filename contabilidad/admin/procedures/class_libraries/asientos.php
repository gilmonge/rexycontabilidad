<?php
    class asientos{
        function agregaDB($url_list, $Quick_function, $Data, $redireccionar = true){
            /* Inicializa las variables */
                $id             = $Quick_function->Topnumber('id', TABLA_ASIENTOS)+1;
                $fecha          = (isset($Data['fecha']))          ? date("Y-m-d", strtotime($Data["fecha"])) : date("Y-m-d");
                $referencia     = (isset($Data['referencia']))     ? strip_tags(trim($Data["referencia"])) : '';
                $id_moneda      = (isset($Data['id_moneda']))      ? strip_tags(trim($Data["id_moneda"])) : 1;
                $comentario     = (isset($Data['comentario']))     ? strip_tags(trim($Data["comentario"])) : '';
                $lineas_asiento = (isset($Data['lineas_asiento'])) ? json_decode($Data["lineas_asiento"], true) : [];
            /* Inicializa las variables */

            /* Trae el tipo de cambio de la moneda seleccionada */
                /* Inicia los datos de la DB */
                    $datos_moneda  = array(':id' => $id_moneda,);
                /* Inicia los datos de la DB */

                /* Declara el SQL */
                    $TABLA_MONEDAS = TABLA_MONEDAS;
                    $sql_moneda="SELECT venta FROM $TABLA_MONEDAS WHERE id = :id LIMIT 1";
                /* Declara el SQL */
                
                /* Ejecuta el query */
                    $par= $Quick_function->SQLDatos_CA($sql_moneda, $datos_moneda);
                    $row = $par->fetch();
                    $tipo_cambio = $row['venta'];
                /* Ejecuta el query */
            /* Trae el tipo de cambio de la moneda seleccionada */

            /* Trae datos del usuario */
                $usuario = $Quick_function->datos_administrador();
            /* Trae datos del usuario */

            /* Guarda encabezado del asiento */
                /* Inicia los datos de la DB */
                    $datos  = array(
                        ':id'                   => $id,
                        ':numero_asiento'       => $id,
                        ':fecha'                => $fecha,
                        ':referencia_documento' => $referencia,
                        ':id_moneda'            => $id_moneda,
                        ':tipo_cambio'          => $tipo_cambio,
                        ':comentario'           => $comentario,
                        ':total_debe'           => 0,
                        ':total_haber'          => 0,
                        ':procedencia'          => 1,
                        ':usuario'              => $usuario['id'],
                        ':activo'               => 1,
                    );
                /* Inicia los datos de la DB */

                /* Declara el SQL */
                    $sql="  INSERT INTO ".TABLA_ASIENTOS." SET 
                                id                      = :id,
                                numero_asiento          = :numero_asiento,
                                fecha                   = :fecha,
                                referencia_documento    = :referencia_documento,
                                id_moneda               = :id_moneda,
                                tipo_cambio             = :tipo_cambio,
                                comentario              = :comentario,
                                total_debe              = :total_debe,
                                total_haber             = :total_haber,
                                procedencia             = :procedencia,
                                usuario                 = :usuario,
                                fecha_creacion          = NOW(),
                                activo                  = :activo
                    ";
                /* Declara el SQL */

                /* Ejecuta el query */
                    $Quick_function->SQLDatos_CA($sql, $datos);
                /* Ejecuta el query */
            /* Guarda encabezado del asiento */

            /* ingresa las lineas del asiento */
                $datos_extra = array(
                    "id"            => $id,
                    "tipo_cambio"   => $tipo_cambio,
                    "id_moneda"     => $id_moneda,
                );
                $total_debe = $total_haber = 0;
                foreach ($lineas_asiento['debe'] as $key => $linea) {
                    $this->agrega_linea($Quick_function, $datos_extra, $linea);
                    $total_debe += $linea["monto"];
                }

                foreach ($lineas_asiento['haber'] as $key => $linea) {
                    $this->agrega_linea($Quick_function, $datos_extra, $linea);
                    $total_haber += $linea["monto"];
                }  
            /* ingresa las lineas del asiento */

            /* actualiza los totales */
                /* Inicia los datos de la DB */
                    $datos  = array(
                        ':id'           => $id,
                        ':total_debe'   => $total_debe,
                        ':total_haber'  => $total_haber
                    );
                /* Inicia los datos de la DB */

                /* Declara el SQL */
                    $TABLA_ASIENTOS = TABLA_ASIENTOS;
                    $sql="  UPDATE $TABLA_ASIENTOS SET 
                                `total_debe`    = :total_debe,
                                `total_haber`   = :total_haber
                            WHERE
                                `id`            = :id
                    ";
                /* Declara el SQL */

                /* Ejecuta el query */
                    $Quick_function->SQLDatos_CA($sql, $datos);
                /* Ejecuta el query */
            /* actualiza los totales */

            /* Redirecciona */
                if($redireccionar){ header('Location: '.$url_list); }
            /* Redirecciona */
        }

        function agrega_linea($Quick_function, $datos_extra, $linea){
            /* Guarda lineas del asiento */
                /* Inicia los datos de la DB */
                    $datos  = array(
                        ':id'                    => $Quick_function->Topnumber('id', TABLA_ASIENTOS_LINEAS)+1, 
                        ':id_asiento'            => $datos_extra['id'], 
                        ':id_tercero'            => (isset($linea['tercero']))      ? strip_tags(trim($linea['tercero'])) : 0, 
                        ':tipo_origen'           => (isset($linea['tipo_origen']))  ? strip_tags(trim($linea['tipo_origen'])) : 0, 
                        ':tipo_linea_asiento'    => (isset($linea['tipo_linea']))   ? strip_tags(trim($linea['tipo_linea'])) : 0, 
                        ':monto'                 => (isset($linea['monto']))        ? strip_tags(trim($linea['monto'])) : 0,
                        ':id_cuenta'             => (isset($linea['id_cuenta']) && $linea['id_cuenta'] != '')   ? strip_tags(trim($linea['id_cuenta'])) : 0, 
                        ':id_item'               => (isset($linea['id_item']) && $linea['id_item'] != '')       ? strip_tags(trim($linea['id_item'])) : 0, 
                    );
                /* Inicia los datos de la DB */

                /* Declara el SQL */
                    $sql="  INSERT INTO ".TABLA_ASIENTOS_LINEAS." SET 
                                id                  = :id,
                                id_asiento          = :id_asiento,
                                id_tercero          = :id_tercero,
                                tipo_origen         = :tipo_origen,
                                id_cuenta           = :id_cuenta,
                                id_item             = :id_item,
                                tipo_linea_asiento  = :tipo_linea_asiento,
                                monto               = :monto
                    ";
                /* Declara el SQL */

                /* Ejecuta el query */
                    $Quick_function->SQLDatos_CA($sql, $datos);
                /* Ejecuta el query */
            /* Guarda lineas del asiento */

            /* Analiza linea para modificar la CTA */
                $CTA_naturaleza = 0; $id_cuenta = 0; $monto_linea = $linea['monto'];
                $TABLA_CUENTAS_CONTABLES = TABLA_CUENTAS_CONTABLES;
                $TABLA_ITEMS = TABLA_ITEMS;
                if($linea['tipo_origen'] == 1){ /* Es cuenta */
                    /* Consulta la informacion de la cuenta */
                        $sql="SELECT * FROM $TABLA_CUENTAS_CONTABLES WHERE id = :id";
                        $cta_info = $Quick_function->SQLDatos_CA($sql, array(":id" => $linea['id_cuenta']));
                        $cta_info = $cta_info->fetch();
                        $CTA_naturaleza = $cta_info['naturaleza'];
                        $id_cuenta      = $linea['id_cuenta'];
                        $CTA_id_moneda  = $cta_info['id_moneda'];
                    /* Consulta la informacion de la cuenta */
                }
                else if($linea['tipo_origen'] == 2){ /* Es item */
                    /* Consulta la informacion del item */
                        $sql="SELECT * FROM $TABLA_ITEMS WHERE id = :id";
                        $item_info = $Quick_function->SQLDatos_CA($sql, array(":id" => $linea['id_item']));
                        $item_info = $item_info->fetch();
                        $id_cuenta = $item_info['id_cuenta'];
                    /* Consulta la informacion del item */
                    
                    /* Consulta la informacion de la cuenta */
                        $sql="SELECT * FROM $TABLA_CUENTAS_CONTABLES WHERE id = :id";
                        $cta_info = $Quick_function->SQLDatos_CA($sql, array(":id" => $id_cuenta));
                        $cta_info = $cta_info->fetch();
                        $CTA_naturaleza = $cta_info['naturaleza'];
                        $CTA_id_moneda  = $cta_info['id_moneda'];
                    /* Consulta la informacion de la cuenta */
                }

                /* Analiza la moneda */
                    if($CTA_id_moneda != $datos_extra['id_moneda']){ /* La moneda es diferente, realiza el calulo segun la moneda de la cuenta */
                        /* Consulta la informacion de la cuenta */
                            $TABLA_MONEDAS = TABLA_MONEDAS;
                            $sql="SELECT * FROM $TABLA_MONEDAS WHERE id = :id";
                            $moneda_info = $Quick_function->SQLDatos_CA($sql, array(":id" => 2));
                            $moneda_info = $moneda_info->fetch();
                            $moneda_venta = $moneda_info['venta'];
                        /* Consulta la informacion de la cuenta */
                        
                        if($datos_extra['id_moneda'] == 1){ /* Es colones */
                            /* De colones a dolares */
                            $monto_linea = $monto_linea / $moneda_venta;
                        } else if($datos_extra['id_moneda'] == 2){ /* Es dolares */
                            /* De dolares a colones */
                            $monto_linea = $monto_linea * $moneda_venta;
                        }
                    }
                /* Analiza la moneda */

                /* Analiza la naturaleza con respecto a el tipo de linea */
                    $sql_cta = '';
                    if($CTA_naturaleza == 1){ /* Deudor */
                        if($linea['tipo_linea'] == 1){ /* debe */
                            /* Aumenta el monto */
                            $operacionMat = "+";
                        }
                        else if($linea['tipo_linea'] == 2){ /* haber */
                            /* Reduce el monto */
                            $operacionMat = "-";
                        }
                    }
                    else if($CTA_naturaleza == 2){ /* Acreedor */
                        if($linea['tipo_linea'] == 1){ /* debe */
                            /* Reduce el monto */
                            $operacionMat = "-";
                        }
                        else if($linea['tipo_linea'] == 2){ /* haber */
                            /* Aumenta el monto */
                            $operacionMat = "+";
                        }
                    }

                    /* Ejecuta el query */
                        $sql_cta = "UPDATE $TABLA_CUENTAS_CONTABLES SET saldoactual = (saldoactual $operacionMat :monto_linea) WHERE id = :id";
                        $Quick_function->SQLDatos_CA($sql_cta, array(
                            ":monto_linea"  => $monto_linea,
                            ":id"           => $id_cuenta,
                        ));
                    /* Ejecuta el query */
                /* Analiza la naturaleza con respecto a el tipo de linea */
            /* Analiza linea para modificar la CTA */
        }

        function listar_lineasDB($Quick_function, $Data, $return = false){
            /* Inicializa las variables */
                $id = (isset($Data['id_asiento'])) ? strip_tags(trim($Data["id_asiento"])) : '';
            /* Inicializa las variables */

            /* Extrae las lineas del asiento */
                $TABLA_ASIENTOS = TABLA_ASIENTOS;
                $TABLA_ASIENTOS_LINEAS = TABLA_ASIENTOS_LINEAS;
                $TABLA_MONEDAS = TABLA_MONEDAS;
                $TABLA_TERCEROS = TABLA_TERCEROS;

                $lineas_asiento = array("debe" => [], "haber" => []);
                $select = " SELECT 
                                AL.id, 
                                AL.id_asiento, 
                                AL.id_tercero, 
                                AL.tipo_origen, 
                                AL.id_cuenta, 
                                AL.id_item, 
                                AL.tipo_linea_asiento, 
                                AL.monto,

                                M.codificacion,

                                CONCAT(TER.nombre, ' ', TER.apellido) AS tercero
                            FROM 
                                $TABLA_ASIENTOS_LINEAS AS AL
                                INNER JOIN $TABLA_ASIENTOS AS TA
                                    ON TA.id = AL.id_asiento
                                INNER JOIN $TABLA_MONEDAS AS M
                                    ON M.id = TA.id_moneda
                                LEFT JOIN $TABLA_TERCEROS AS TER
                                    ON TER.id = AL.id_tercero
                            WHERE 
                                AL.id_asiento = $id
                ";
                $listado_lineas= $Quick_function->SQLDatos_SA($select);
                $response['cantidad'] = $listado_lineas->rowCount();
                while ($row_linea = $listado_lineas->fetch()) {
                    $cuenta_item = '';

                    if($row_linea['tipo_origen'] == 1){ /* cuenta contable */
                        /* Trae el listado de cuentas contables */
                            $CTAS_CONTABLES = $Quick_function->traer_ctas_contables();
                        /* Trae el listado de cuentas contables */
                        foreach ($CTAS_CONTABLES as $key => $cta) {
                            if($cta['id'] == $row_linea['id_cuenta']){
                                $cuenta_item = $cta['codigo'].' - '.$cta['nombre'];
                            }
                        }
                    }
                    elseif($row_linea['tipo_origen'] == 2){ /* Items */
                        /* Trae el listado de los item y lo compara */
                            $listado_items= $Quick_function->SQLDatos_SA("SELECT * FROM ".TABLA_ITEMS_LISTADO." WHERE activo = 1");
                            while ($row_items = $listado_items->fetch()) {
                                if($row_items['id'] == $row_linea['id_item']){
                                    $cuenta_item = $row_items['nombre_item'];
                                }
                            }
                        /* Trae el listado de los item y lo compara */
                    }
                    

                    if($row_linea['tipo_linea_asiento'] == 1){ /* debe */
                        $lineas_asiento['debe'][] = array(
                            "cuenta_item"   => $cuenta_item,
                            "monto_base"    => $row_linea['monto'],
                            "monto"         => $Quick_function->Money__Format($row_linea['monto'], $row_linea['codificacion']),
                            "tercero"       => $row_linea['tercero'],
                        );
                    }
                    else if($row_linea['tipo_linea_asiento'] == 2){ /* haber */
                        $lineas_asiento['haber'][] = array(
                            "cuenta_item"   => $cuenta_item,
                            "monto_base"    => $row_linea['monto'],
                            "monto"         => $Quick_function->Money__Format($row_linea['monto'], $row_linea['codificacion']),
                            "tercero"       => $row_linea['tercero'],
                        );
                    }
                }
            /* Extrae las lineas del asiento */

            $response['lineas'] = $lineas_asiento;
            /* devuelve los datos encontrados */
                if($return){
                    return $response;
                }
                else{
                    header('Content-Type: application/json');
                    echo(json_encode($response));
                }
            /* devuelve los datos encontrados */
        }

        function reversar_asientoDB($Quick_function, $Data){
            /* Inicializa las variables */
                global $url_principal;
                $id         = (isset($Data['id']))         ? strip_tags(trim($Data["id"])) : 0;
                $fecha      = (isset($Data['fecha']))      ? date("Y-m-d", strtotime($Data["fecha"])) : date("Y-m-d");
                $referencia = (isset($Data['referencia'])) ? strip_tags(trim($Data["referencia"])) : '';
                $comentario = (isset($Data['comentario'])) ? strip_tags(trim($Data["comentario"])) : '';
            /* Inicializa las variables */

            /* Anula el asiento seleccionado */
                /* Declara el SQL */
                    $TABLA_ASIENTOS = TABLA_ASIENTOS;
                    $sql="  UPDATE $TABLA_ASIENTOS SET 
                                `activo`    = 0
                            WHERE
                                `id`                = :id
                    ";
                /* Declara el SQL */

                /* Establece los datos */
                    $datos = array(":id" => $id);
                /* Establece los datos */

                /* Ejecuta el query */
                    $Quick_function->SQLDatos_CA($sql, $datos);
                /* Ejecuta el query */
            /* Anula el asiento seleccionado */

            /* Copia la linea de asiento */
                /* Declara el SQL */
                    $TABLA_ASIENTOS = TABLA_ASIENTOS;
                    $sql_informacion="  SELECT * FROM $TABLA_ASIENTOS WHERE `id` = :id ";
                    /* Establece los datos */
                        $datos_informacion = array(":id" => $id);
                    /* Establece los datos */
                /* Declara el SQL */
                $Asiento_a_anular = $Quick_function->SQLDatos_CA($sql_informacion, $datos_informacion);
                $asiento = $Asiento_a_anular->fetch();

                /* Arma los datos del insert */
                    $id_nuevo_asiento = $Quick_function->Topnumber('id', TABLA_ASIENTOS)+1;
                    $datos_anulacion = array(
                        ":id"                   => $id_nuevo_asiento,
                        ":numero_asiento"       => $id_nuevo_asiento,
                        ":fecha"                => $fecha,
                        ":referencia_documento" => $referencia,
                        ":id_moneda"            => $asiento['id_moneda'],
                        ":tipo_cambio"          => $asiento['tipo_cambio'],
                        ":comentario"           => $comentario,
                        ":total_debe"           => $asiento['total_debe'],
                        ":total_haber"          => $asiento['total_haber'],
                        ":procedencia"          => 1,
                        ":usuario"              => $Quick_function->datos_administrador()['id'],
                        ":activo"               => 1,
                    );
                /* Arma los datos del insert */

                /* Declara el SQL */
                    $sql_anulacion="  INSERT INTO ".TABLA_ASIENTOS." SET 
                                id                      = :id,
                                numero_asiento          = :numero_asiento,
                                fecha                   = :fecha,
                                referencia_documento    = :referencia_documento,
                                id_moneda               = :id_moneda,
                                tipo_cambio             = :tipo_cambio,
                                comentario              = :comentario,
                                total_debe              = :total_debe,
                                total_haber             = :total_haber,
                                procedencia             = :procedencia,
                                usuario                 = :usuario,
                                fecha_creacion          = NOW(),
                                activo                  = :activo
                    ";
                /* Declara el SQL */

                /* Ejecuta el query */
                    $Quick_function->SQLDatos_CA($sql_anulacion, $datos_anulacion);
                /* Ejecuta el query */

            /* Copia la linea de asiento */

            /* Invierto las lineas del asiento en el nuevo asiento */
                /* Declara el SQL */
                    $TABLA_ASIENTOS_LINEAS = TABLA_ASIENTOS_LINEAS;
                    $sql="  SELECT * FROM $TABLA_ASIENTOS_LINEAS WHERE `id_asiento` = :id ";
                    /* Establece los datos */
                        $datos = array(":id" => $id);
                    /* Establece los datos */
                /* Declara el SQL */
                $Asiento_a_anular = $Quick_function->SQLDatos_CA($sql, $datos);
            /* Invierto las lineas del asiento en el nuevo asiento */

            /* recorre el listado de lineas */
                while($linea_asiento = $Asiento_a_anular->fetch()){

                    $linea = array(
                        "tercero"       => $linea_asiento['id_tercero'],
                        "tipo_origen"   => $linea_asiento['tipo_origen'],
                        "id_cuenta"     => $linea_asiento['id_cuenta'],
                        "id_item"       => $linea_asiento['id_item'],
                        "tipo_linea"    => ($linea_asiento['tipo_linea_asiento'] == 1)? 2 : 1,
                        "monto"         => $linea_asiento['monto'],
                    );
                    $this->agrega_linea($Quick_function, $id_nuevo_asiento, $linea);
                }
            /* recorre el listado de lineas */
            
            /* Redirecciona */
                header('Location: '.$url_principal);
            /* Redirecciona */
        }

        function actualizarDB($url_list, $Quick_function, $Data){
            /* Guarda encabezado del asiento */
                /* Inicia los datos de la DB */
                    $datos  = array(
                        ':id'                   => (isset($Data['id']))        ? strip_tags(trim($Data["id"])) : 0,
                        ':fecha'                => (isset($Data['fecha']))     ? date("Y-m-d", strtotime($Data["fecha"])) : date("Y-m-d"),
                        ':referencia_documento' => (isset($Data['referencia']))? strip_tags(trim($Data["referencia"])) : '',
                        ':comentario'           => (isset($Data['comentario']))? strip_tags(trim($Data["comentario"])) : '',
                    );
                /* Inicia los datos de la DB */

                /* Declara el SQL */
                    $sql="  UPDATE ".TABLA_ASIENTOS." SET
                                fecha                   = :fecha,
                                referencia_documento    = :referencia_documento,
                                comentario              = :comentario
                            WHERE
                                id                      = :id
                    ";
                /* Declara el SQL */

                /* Ejecuta el query */
                    $Quick_function->SQLDatos_CA($sql, $datos);
                /* Ejecuta el query */
            /* Guarda encabezado del asiento */

            /* Redirecciona */
                header('Location: '.$url_list);
            /* Redirecciona */
        }
        
        
        function listar_lineas_perdidas_gananciasDB($id = 0, $return_array = false){
            global $Quick_function;
            $lineas_asiento = [];
            
            /* Trae el listado de cuentas */
                $ctas_contables = $Quick_function->traer_ctas_contables();
            /* Trae el listado de cuentas */

            /* Extrae las lineas del asiento */
                $TABLA_ASIENTOS = TABLA_ASIENTOS;
                $TABLA_ASIENTOS_LINEAS = TABLA_ASIENTOS_LINEAS;
                $TABLA_MONEDAS = TABLA_MONEDAS;
                
                $sql = "SELECT 
                            AL.id,
                            AL.id_asiento,
                            AL.id_tercero,
                            AL.tipo_origen,
                            AL.id_cuenta,
                            AL.id_item,
                            AL.tipo_linea_asiento,
                            (AL.monto * A.tipo_cambio) AS monto
                        FROM 
                            $TABLA_ASIENTOS_LINEAS AS AL

                            INNER JOIN $TABLA_ASIENTOS AS A
                                ON A.id = AL.id_asiento

                        WHERE 
                            AL.id_asiento = $id
                ";
                $listado_items= $Quick_function->SQLDatos_SA($sql);
                $response['cantidad'] = $listado_items->rowCount();
                while ($row_linea = $listado_items->fetch()) {
                    /* trae la informacion de donde se genero el asiento */
                        $CTA_id = $CTA_nombre = $CTA_referencia = $CTA_tipo_cta = $CTA_tipocuenta = '';
                        
                        if($row_linea['tipo_origen'] == 1){ /* Cuenta contable */
                            $Datos_CTA = [];
                            foreach ($ctas_contables as $key => $value) {
                                if($value['id'] == $row_linea['id_cuenta']){
                                    $Datos_CTA = $ctas_contables[$key];
                                }
                            }
                            $CTA_id         = $Datos_CTA['id'];
                            $CTA_nombre     = $Datos_CTA['nombre'];
                            $CTA_referencia = $Datos_CTA['codigo'];
                            $CTA_tipo_cta   = $Datos_CTA['id_tipo_cuenta'];
                            $CTA_tipocuenta = $Datos_CTA['id_padre'];
                            $CTA_tipoflujo  = $Datos_CTA['naturaleza'];
                        }
                        else if($row_linea['tipo_origen'] == 2){ /* Item */
                            $TABLA_ITEMS = TABLA_ITEMS;
                            $sql = "SELECT * FROM $TABLA_ITEMS WHERE id = ".$row_linea['id_item'];
                            $listado_items= $Quick_function->SQLDatos_SA($sql);
                            $req_info = $listado_items->fetch();
                            
                            $Datos_CTA = [];
                            foreach ($ctas_contables as $key => $value) {
                                if($value['id'] == $req_info['id_cuenta']){
                                    $Datos_CTA = $ctas_contables[$key];
                                }
                            }

                            $CTA_id         = $Datos_CTA['id'];
                            $CTA_nombre     = $Datos_CTA['nombre'];
                            $CTA_referencia = $Datos_CTA['codigo'];
                            $CTA_tipo_cta   = $Datos_CTA['id_tipo_cuenta'];
                            $CTA_tipocuenta = $Datos_CTA['id_padre'];
                            $CTA_tipoflujo  = $Datos_CTA['naturaleza'];
                        }
                    /* trae la informacion de donde se genero el asiento */

                    if(!isset($lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id])){
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['nombre']      = $CTA_nombre;
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['referencia']  = $CTA_referencia;
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipoflujo']   = $CTA_tipoflujo;
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto']       = $row_linea['monto'];
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = $row_linea['tipo_linea_asiento'];
    
                        if($CTA_tipo_cta == -1 || $CTA_tipo_cta == -2 || $CTA_tipo_cta == -3){ /* Es Activo */
                            /* -2 -> Corrientes */
                            /* -3 -> No corrientes */
                            if($row_linea['tipo_linea_asiento'] == 1){ /* debe (suma) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                            }
                            else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (resta) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                            }
                        }
                        
                        else if($CTA_tipo_cta == -4 || $CTA_tipo_cta == -5 || $CTA_tipo_cta == -6){ /* Es Pasivo */
                            /* -5 -> Corrientes */
                            /* -6 -> No corrientes */
                            if($row_linea['tipo_linea_asiento'] == 1){ /* debe (resta) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = 2;
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                            }
                            else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (suma) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = 1;
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                            }
                            /*if($CTA_id != 4){
                                if($row_linea['tipo_linea_asiento'] == 1){ /* debe (resta) * /
                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                                }
                                else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (suma) * /
                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                                }
                            }else{
                            }*/
                        }
                        
                        else if($CTA_tipo_cta == -7){ /* Es Patrimonio */
                            if($row_linea['tipo_linea_asiento'] == 1){ /* debe (resta) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                            }
                            else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (suma) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                            }
                        }
                        
                        else if($CTA_tipo_cta == -8){ /* Es ingreso */
                            if($row_linea['tipo_linea_asiento'] == 1){ /* debe (resta) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                            }
                            else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (suma) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                            }
                        }
    
                        else if($CTA_tipo_cta == -9){ /* Es gastos */
                            if($row_linea['tipo_linea_asiento'] == 1){ /* debe (suma) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                            }
                            else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (resta) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                            }
                        }
                    }else{
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['nombre']      = $CTA_nombre;
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['referencia']  = $CTA_referencia;
                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipoflujo']   = $CTA_tipoflujo;

                        $tipo_asiento_existente = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'];

                        if($CTA_tipo_cta == -1){ /* Es Activo */
                            /* -2 -> Corrientes */
                            /* -3 -> No corrientes */
                            if($row_linea['tipo_linea_asiento'] == 1){ /* debe (suma) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                            }
                            else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (resta) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                            }
                            
                            if($row_linea['tipo_linea_asiento'] == $tipo_asiento_existente){ /* Los tipos son iguales */
                                $monto_sumado = ($lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] + $row_linea['monto']);
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = $row_linea['tipo_linea_asiento'];
                            }
                            else{ /* los tipos son diferentes */
                                if($row_linea['tipo_linea_asiento'] == 1){ /* es debe (debe - haber) */
                                    $debe  = $row_linea['monto'];
                                    $haber = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];

                                    $resultado = $debe - $haber;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                                }
                                else if($row_linea['tipo_linea_asiento'] == 2){ /* es haber (debe - haber) */
                                    $debe  = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
                                    $haber = $row_linea['monto'];
                                    
                                    $resultado = $debe - $haber;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                                }
                            }
                        }
                        
                        else if($CTA_tipo_cta == -4){ /* Es Pasivo */
                            /* -5 -> Corrientes */
                            /* -6 -> No corrientes */
                            if($CTA_id != 4){
                                if($row_linea['tipo_linea_asiento'] == $tipo_asiento_existente){ /* Los tipos son iguales */
                                    $monto_sumado = ($lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] + $row_linea['monto']);
                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = $row_linea['tipo_linea_asiento'];
                                }
                                else{ /* los tipos son diferentes */
                                    if($row_linea['tipo_linea_asiento'] == 1){ /* es debe (haber - debe) */
                                        $debe  = $row_linea['monto'];
                                        $haber = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
    
                                        $resultado = $haber - $debe;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                                    }
                                    else if($row_linea['tipo_linea_asiento'] == 2){ /* es haber (haber - debe) */
                                        $debe  = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
                                        $haber = $row_linea['monto'];
                                        
                                        $resultado = $haber - $debe;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                                    }
                                }
                            }else{
                                if($row_linea['tipo_linea_asiento'] == $tipo_asiento_existente){ /* Los tipos son iguales */
                                    $monto_sumado = ($lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] + $row_linea['monto']);
                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = $row_linea['tipo_linea_asiento'];
                                }
                                else{ /* los tipos son diferentes */
                                    if($row_linea['tipo_linea_asiento'] == 1){ /* es debe (haber - debe) */
                                        $debe  = $row_linea['monto'];
                                        $haber = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
    
                                        $resultado = $haber - $debe;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                                    }
                                    else if($row_linea['tipo_linea_asiento'] == 2){ /* es haber (haber - debe) */
                                        $debe  = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
                                        $haber = $row_linea['monto'];
                                        
                                        $resultado = $haber - $debe;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;
    
                                        $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                                    }
                                }
                            }
                        }
                        
                        else if($CTA_tipo_cta == -7){ /* Es Patrimonio */
                            if($row_linea['tipo_linea_asiento'] == $tipo_asiento_existente){ /* Los tipos son iguales */
                                $monto_sumado = ($lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] + $row_linea['monto']);
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = $row_linea['tipo_linea_asiento'];
                            }
                            else{ /* los tipos son diferentes */
                                if($row_linea['tipo_linea_asiento'] == 1){ /* es debe (haber - debe) */
                                    $debe  = $row_linea['monto'];
                                    $haber = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];

                                    $resultado = $haber - $debe;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                                }
                                else if($row_linea['tipo_linea_asiento'] == 2){ /* es haber (haber - debe) */
                                    $debe  = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
                                    $haber = $row_linea['monto'];
                                    
                                    $resultado = $haber - $debe;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                                }
                            }
                        }
                        
                        else if($CTA_tipo_cta == -8){ /* Es ingreso */
                            if($row_linea['tipo_linea_asiento'] == $tipo_asiento_existente){ /* Los tipos son iguales */
                                $monto_sumado = ($lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] + $row_linea['monto']);
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = $row_linea['tipo_linea_asiento'];
                            }
                            else{ /* los tipos son diferentes */
                                if($row_linea['tipo_linea_asiento'] == 1){ /* es debe (haber - debe) */
                                    $debe  = $row_linea['monto'];
                                    $haber = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];

                                    $resultado = $haber - $debe;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                                }
                                else if($row_linea['tipo_linea_asiento'] == 2){ /* es haber (haber - debe) */
                                    $debe  = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
                                    $haber = $row_linea['monto'];
                                    
                                    $resultado = $haber - $debe;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                                }
                            }
                        }
    
                        else if($CTA_tipo_cta == -9){ /* Es gastos */
                            if($row_linea['tipo_linea_asiento'] == 1){ /* debe (suma) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                            }
                            else if($row_linea['tipo_linea_asiento'] == 2){ /* haber (resta) */
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                            }
                            
                            if($row_linea['tipo_linea_asiento'] == $tipo_asiento_existente){ /* Los tipos son iguales */
                                $monto_sumado = ($lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] + $row_linea['monto']);
                                $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['tipo_linea_asiento'] = $row_linea['tipo_linea_asiento'];
                            }
                            else{ /* los tipos son diferentes */
                                if($row_linea['tipo_linea_asiento'] == 1){ /* es debe (debe - haber) */
                                    $debe  = $row_linea['monto'];
                                    $haber = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];

                                    $resultado = $debe - $haber;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 0;
                                }
                                else if($row_linea['tipo_linea_asiento'] == 2){ /* es haber (debe - haber) */
                                    $debe  = $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'];
                                    $haber = $row_linea['monto'];
                                    
                                    $resultado = $debe - $haber;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['monto'] = $resultado;

                                    $lineas_asiento[$CTA_tipo_cta][$CTA_tipocuenta][$CTA_id]['accion']  = 1;
                                }
                            }
                        }
                            
                    }

                }
            /* Extrae las lineas del asiento */

            $response['lineas'] = $lineas_asiento;
            if($return_array){
                return $lineas_asiento;
            }
            else{
                /* devuelve los datos encontrados */
                    header('Content-Type: application/json');
                    echo(json_encode($response));
                /* devuelve los datos encontrados */
            }
        }
    }
?>