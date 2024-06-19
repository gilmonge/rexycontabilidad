<?php
	/**************************************************
		Sistema de contabilidad
		Desarrollador: Rexy Studios
		Año de creación: 2020
		Última modificación del archivo: 21-04-2020
	**************************************************/
	class ConexionDB{
		
		public function conexion(){
			include_once('variables_globales.php');
			try {
				$conector = new PDO("mysql:dbname=".NOMBRE_BD.";host=".HOST_BD, USUARIO_BD, CONTRASENA_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES  'UTF8'"));
				$conector->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
			} catch (PDOException $e) {
				echo 'Falló la conexión: ' . $e->getMessage();
			}
			return $conector;
		}
	}
?>
