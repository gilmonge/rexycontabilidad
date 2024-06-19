<?php
    class items{
        function agregaDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(
                    ':id'           => $Quick_function->Topnumber('id', TABLA_ITEMS)+1,
                    ':id_cuenta'    => (isset($Data['id_cuenta']))  ? strip_tags(trim($Data["id_cuenta"]))   : 0,
                    ':id_moneda'    => (isset($Data['id_moneda']))  ? strip_tags(trim($Data["id_moneda"]))   : 0,
                    ':id_impuesto'  => (isset($Data['id_impuesto']))? strip_tags(trim($Data["id_impuesto"])) : 0,
                    ':nombre_item'  => (isset($Data['nombre_item']))? strip_tags(trim($Data["nombre_item"])) : '',
                    ':monto_base'   => (isset($Data['monto_base'])) ? strip_tags(trim($Data["monto_base"]))  : '',
                    ':comentario'   => (isset($Data['comentario'])) ? strip_tags(trim($Data["comentario"]))  : '',
                    ':tipo_item'    => (isset($Data['tipo_item']))  ? strip_tags(trim($Data["tipo_item"]))   : '',
                );
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $sql="  INSERT INTO ".TABLA_ITEMS." SET 
                            `id`               = :id,
                            `id_cuenta`        = :id_cuenta,
                            `id_moneda`        = :id_moneda,
                            `id_impuesto`      = :id_impuesto,
                            `nombre_item`      = :nombre_item,
                            `monto_base`       = :monto_base,
                            `comentario`       = :comentario,
                            `tipo_item`        = :tipo_item
                ";
            /* Declara el SQL */
    
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Redirecciona */
                header('Location: '.$url_list);
            /* Redirecciona */
        }
        
        function editarDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(
                    ':id'           => (isset($Data['id_item']))    ? strip_tags(trim($Data["id_item"]))     : 0,
                    ':id_cuenta'    => (isset($Data['id_cuenta']))  ? strip_tags(trim($Data["id_cuenta"]))   : 0,
                    ':id_moneda'    => (isset($Data['id_moneda']))  ? strip_tags(trim($Data["id_moneda"]))   : 0,
                    ':id_impuesto'  => (isset($Data['id_impuesto']))? strip_tags(trim($Data["id_impuesto"])) : 0,
                    ':nombre_item'  => (isset($Data['nombre_item']))? strip_tags(trim($Data["nombre_item"])) : '',
                    ':monto_base'   => (isset($Data['monto_base'])) ? strip_tags(trim($Data["monto_base"]))  : '',
                    ':comentario'   => (isset($Data['comentario'])) ? strip_tags(trim($Data["comentario"]))  : '',
                    ':tipo_item'    => (isset($Data['tipo_item']))  ? strip_tags(trim($Data["tipo_item"]))   : '',
                );
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_ITEMS = TABLA_ITEMS;
                $sql="  UPDATE $TABLA_ITEMS SET 
                            `id_cuenta`        = :id_cuenta,
                            `id_moneda`        = :id_moneda,
                            `id_impuesto`      = :id_impuesto,
                            `nombre_item`      = :nombre_item,
                            `monto_base`       = :monto_base,
                            `comentario`       = :comentario,
                            `tipo_item`        = :tipo_item
                        WHERE
                            `id`               = :id
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
                $datos  = array(':id' => (isset($Data['id_item'])) ? strip_tags(trim($Data["id_item"])) : 0,);
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_ITEMS = TABLA_ITEMS;
                $sql="UPDATE $TABLA_ITEMS SET activo = !activo WHERE id = :id";
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
                $datos  = array(':id' => (isset($Data['id_item'])) ? strip_tags(trim($Data["id_item"])) : 0,);
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_ITEMS = TABLA_ITEMS;
                $sql="UPDATE $TABLA_ITEMS SET borrado = !borrado WHERE id = :id";
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