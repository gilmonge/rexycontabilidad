<?php
    class terceros{
        function agregaDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(
                    ':id'                   => $Quick_function->Topnumber('id', TABLA_TERCEROS)+1,
                    ':tipo_identificacion'  => (isset($Data['tipo_identificacion']))  ? strip_tags(trim($Data["tipo_identificacion"]))   : 0,
                    ':identificacion'       => (isset($Data['identificacion']))  ? strip_tags(trim($Data["identificacion"]))   : 0,
                    ':nombre'               => (isset($Data['nombre']))? strip_tags(trim($Data["nombre"])) : 0,
                    ':apellido'             => (isset($Data['apellido']))? strip_tags(trim($Data["apellido"])) : '',
                    ':correo'               => (isset($Data['correo'])) ? strip_tags(trim($Data["correo"]))  : '',
                    ':telefono'             => (isset($Data['telefono'])) ? strip_tags(trim($Data["telefono"]))  : '',
                    ':direccion'            => (isset($Data['direccion']))  ? strip_tags(trim($Data["direccion"]))   : '',
                    ':clasificacion'        => (isset($Data['clasificacion']))  ? strip_tags(trim($Data["clasificacion"]))   : '',
                );
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $sql="  INSERT INTO ".TABLA_TERCEROS." SET
                            `id`                    = :id,
                            `tipo_identificacion`   = :tipo_identificacion,
                            `identificacion`        = :identificacion,
                            `nombre`                = :nombre,
                            `apellido`              = :apellido,
                            `correo`                = :correo,
                            `telefono`              = :telefono,
                            `direccion`             = :direccion,
                            `clasificacion`         = :clasificacion
                ";
            /* Declara el SQL */
    
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Redirecciona */
                $url_list .= ($Data['origen'] == 1)? 'proveedores.php': 'clientes.php';
                header('Location: '.$url_list);
            /* Redirecciona */
        }
        
        function editarDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(
                    ':id'                   => (isset($Data['id_tercero']))            ? strip_tags(trim($Data["id_tercero"]))     : 0,
                    ':tipo_identificacion'  => (isset($Data['tipo_identificacion']))   ? strip_tags(trim($Data["tipo_identificacion"]))   : 0,
                    ':identificacion'       => (isset($Data['identificacion']))        ? strip_tags(trim($Data["identificacion"]))   : 0,
                    ':nombre'               => (isset($Data['nombre']))                ? strip_tags(trim($Data["nombre"])) : 0,
                    ':apellido'             => (isset($Data['apellido']))              ? strip_tags(trim($Data["apellido"])) : '',
                    ':correo'               => (isset($Data['correo']))                ? strip_tags(trim($Data["correo"]))  : '',
                    ':telefono'             => (isset($Data['telefono']))              ? strip_tags(trim($Data["telefono"]))  : '',
                    ':direccion'            => (isset($Data['direccion']))             ? strip_tags(trim($Data["direccion"]))   : '',
                    ':clasificacion'        => (isset($Data['clasificacion']))         ? strip_tags(trim($Data["clasificacion"]))   : '',
                );
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_TERCEROS = TABLA_TERCEROS;
                $sql="  UPDATE $TABLA_TERCEROS SET 
                            `tipo_identificacion`   = :tipo_identificacion,
                            `identificacion`        = :identificacion,
                            `nombre`                = :nombre,
                            `apellido`              = :apellido,
                            `correo`                = :correo,
                            `telefono`              = :telefono,
                            `direccion`             = :direccion,
                            `clasificacion`         = :clasificacion
                        WHERE
                            `id`               = :id
                ";
            /* Declara el SQL */
    
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Redirecciona */
                $url_list .= ($Data['origen'] == 1)? 'proveedores.php': 'clientes.php';
                header('Location: '.$url_list);
            /* Redirecciona */
        }
    
        function activarDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(':id' => (isset($Data['id_tercero'])) ? strip_tags(trim($Data["id_tercero"])) : 0,);
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_TERCEROS = TABLA_TERCEROS;
                $sql="UPDATE $TABLA_TERCEROS SET activo = !activo WHERE id = :id";
            /* Declara el SQL */
            
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Redirecciona */
                $url_list .= ($Data['origen'] == 1)? 'proveedores.php': 'clientes.php';
                header('Location: '.$url_list);
            /* Redirecciona */
        }
    
        function borrarDB($url_list, $Quick_function, $Data){
            /* Inicia los datos de la DB */
                $datos  = array(':id' => (isset($Data['id_tercero'])) ? strip_tags(trim($Data["id_tercero"])) : 0,);
            /* Inicia los datos de la DB */
    
            /* Declara el SQL */
                $TABLA_TERCEROS = TABLA_TERCEROS;
                $sql="UPDATE $TABLA_TERCEROS SET borrado = !borrado WHERE id = :id";
            /* Declara el SQL */
            
            /* Ejecuta el query */
                $Quick_function->SQLDatos_CA($sql, $datos);
            /* Ejecuta el query */
    
            /* Redirecciona */
                $url_list .= ($Data['origen'] == 1)? 'proveedores.php': 'clientes.php';
                header('Location: '.$url_list);
            /* Redirecciona */
        }
    }
?>