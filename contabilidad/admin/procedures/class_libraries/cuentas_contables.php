<?php
    class cuentas_contables{
        function agregaDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(
                    ':id'               => $Quick_function->Topnumber('id', TABLA_CUENTAS_CONTABLES)+1,
                    ':id_tipo_cuenta'   => (isset($Data['id_cuenta_financiero']))  ? strip_tags(trim($Data["id_cuenta_financiero"]))  : 0,
                    ':id_padre'         => (isset($Data['id_cuenta_control']))     ? strip_tags(trim($Data["id_cuenta_control"]))     : 0,
                    ':codigo_cuenta'    => (isset($Data['codigo']))                ? strip_tags(trim($Data["codigo"]))                : '',
                    ':nombre'           => (isset($Data['nombre']))                ? strip_tags(trim($Data["nombre"]))                : '',
                    ':saldoinicial'     => (isset($Data['monto_inicial']) && $Data['monto_inicial'] != '')         ? $Data["monto_inicial"]                           : 0,
                    ':naturaleza'       => (isset($Data['naturaleza']))            ? strip_tags(trim($Data["naturaleza"]))            : '',
                    ':id_moneda'        => (isset($Data['id_moneda']))             ? strip_tags(trim($Data["id_moneda"]))             : '',
                    ':comentario'       => (isset($Data['comentario']))            ? strip_tags(trim($Data["comentario"]))            : '',
                    ':usuario'          => $Quick_function->datos_administrador()['id'],
                );

                $datos[':id_padre'] = ($datos[':id_padre'] < 0 )? 0: $datos[':id_padre'];
                /* ':saldoactual'      => (isset($Data['monto_inicial']))         ? $Data["monto_inicial"]                           : 0, */
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $sql="  INSERT INTO ".TABLA_CUENTAS_CONTABLES." SET
                            `id`                = :id,
                            `id_tipo_cuenta`    = :id_tipo_cuenta,
                            `id_padre`          = :id_padre,
                            `codigo_cuenta`     = :codigo_cuenta,
                            `nombre`            = :nombre,
                            `saldoinicial`      = :saldoinicial,
                            `saldoactual`       = 0,
                            `naturaleza`        = :naturaleza,
                            `id_moneda`         = :id_moneda,
                            `comentario`        = :comentario,
                            `usuario`           = :usuario
                ";
            /* Declara el SQL */
    
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Analiza si el monto es mayor a 0 */
                if($datos[':saldoinicial'] > 0){ /* Genera el asiento */
                    
                    /* Incluye las librerias */
                        require('class_libraries/asientos.php');
                        $asientos = new asientos;
                    /* Incluye las librerias */

                    /* Crea la lineas del asiento */
                        $debe = $haber = [];

                        if($datos[':naturaleza'] == 1){ /* Es deudora +  - */
                            $debe = array(
                                "tercero"       => 0,
                                "tipo_origen"   => 1,
                                "id_cuenta"     => $datos[':id'],
                                "id_item"       => 0,
                                "tipo_linea"    => 1,
                                "monto"         => $datos[':saldoinicial'],
                                
                            );
                            $haber = array(
                                "tercero"       => 0,
                                "tipo_origen"   => 1,
                                "id_cuenta"     => 10,
                                "id_item"       => 0,
                                "tipo_linea"    => 2,
                                "monto"         => $datos[':saldoinicial'],
                                
                            );
                        }
                        else if($datos[':naturaleza'] == 2){ /* Es acredora -  + */
                            $debe = array(
                                "tercero"       => 0,
                                "tipo_origen"   => 1,
                                "id_cuenta"     => 10,
                                "id_item"       => 0,
                                "tipo_linea"    => 1,
                                "monto"         => $datos[':saldoinicial'],
                                
                            );
                            $haber = array(
                                "tercero"       => 0,
                                "tipo_origen"   => 1,
                                "id_cuenta"     => $datos[':id'],
                                "id_item"       => 0,
                                "tipo_linea"    => 2,
                                "monto"         => $datos[':saldoinicial'],
                            );
                        }
                    /* Crea la lineas del asiento */
                    
                    $Data = array(
                        "fecha"          => date("Y-m-d"),
                        "referencia"     => '',
                        "id_moneda"      => $datos[':id_moneda'],
                        "comentario"     => 'Apertura de cuenta: '.$datos[':nombre'].', '.$datos[':comentario'],
                        "lineas_asiento" => json_encode(array(
                            "debe"  => array($debe),
                            "haber" => array($haber),
                        )),
                    );
                    
                    $asientos->agregaDB($url_list, $Quick_function, $Data, false);
                }
            /* Analiza si el monto es mayor a 0 */
    
            /* Redirecciona */
                header('Location: '.$url_list);
            /* Redirecciona */
        }
        
        function editarDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(
                    ':id'               => (isset($Data['id_cuenta']))             ? strip_tags(trim($Data["id_cuenta"]))             : 0,
                    ':id_tipo_cuenta'   => (isset($Data['id_cuenta_financiero']))  ? strip_tags(trim($Data["id_cuenta_financiero"]))  : 0,
                    ':id_padre'         => (isset($Data['id_cuenta_control']))     ? strip_tags(trim($Data["id_cuenta_control"]))     : 0,
                    ':codigo_cuenta'    => (isset($Data['codigo']))                ? strip_tags(trim($Data["codigo"]))                : '',
                    ':nombre'           => (isset($Data['nombre']))                ? strip_tags(trim($Data["nombre"]))                : '',
                    ':naturaleza'       => (isset($Data['naturaleza']))            ? strip_tags(trim($Data["naturaleza"]))            : '',
                    ':comentario'       => (isset($Data['comentario']))            ? strip_tags(trim($Data["comentario"]))            : '',
                );
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_CUENTAS_CONTABLES = TABLA_CUENTAS_CONTABLES;
                $sql="  UPDATE $TABLA_CUENTAS_CONTABLES SET 
                            `id_tipo_cuenta`    = :id_tipo_cuenta,
                            `id_padre`          = :id_padre,
                            `codigo_cuenta`     = :codigo_cuenta,
                            `nombre`            = :nombre,
                            `naturaleza`        = :naturaleza,
                            `comentario`        = :comentario
                        WHERE
                            `id`                = :id
                ";
            /* Declara el SQL */
    
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
            
            /* Redirecciona */
                header('Location: '.$url_list);
            /* Redirecciona */
        }
    
        function activarDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(':id' => (isset($Data['id_cuenta'])) ? strip_tags(trim($Data["id_cuenta"])) : 0,);
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_CUENTAS_CONTABLES = TABLA_CUENTAS_CONTABLES;
                $sql="UPDATE $TABLA_CUENTAS_CONTABLES SET activo = !activo WHERE id = :id";
            /* Declara el SQL */
            
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Redirecciona */
                header('Location: '.$url_list);
            /* Redirecciona */
        }
    
        function borrarDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(':id' => (isset($Data['id_cuenta'])) ? strip_tags(trim($Data["id_cuenta"])) : 0,);
            /* Inicia los datos de la DB */
    
            /* Consulta a ver si lo desactiva */
                /* Declara el SQL de consulta */
                    $TABLA_CUENTAS_CONTABLES = TABLA_CUENTAS_CONTABLES;
                    $sql="SELECT * FROM $TABLA_CUENTAS_CONTABLES WHERE id = :id";
                /* Declara el SQL de consulta */
                
                /* Ejecuta el query */
                    $cta_info = $Quick_function->SQLDatos_CA($sql, $datos);
                    $cta_info = $cta_info->fetch();
                    $activo = ($cta_info['borrado'] == 0)? 0: $cta_info['activo'];
                /* Ejecuta el query */
    
                $datos[':activo'] = $activo;
            /* Consulta a ver si lo desactiva */
    
            /* Declara el SQL */
                $sql="UPDATE $TABLA_CUENTAS_CONTABLES SET borrado = !borrado , activo = :activo WHERE id = :id";
            /* Declara el SQL */
            
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Redirecciona */
                header('Location: '.$url_list);
            /* Redirecciona */
        }
    }
?>