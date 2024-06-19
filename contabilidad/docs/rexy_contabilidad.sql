-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.7.3-MariaDB-1:10.7.3+maria~focal - mariadb.org binary distribution
-- SO del servidor:              debian-linux-gnu
-- HeidiSQL Versión:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para rexy_contabilidad
CREATE DATABASE IF NOT EXISTS `rexy_contabilidad` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `rexy_contabilidad`;

-- Volcando estructura para tabla rexy_contabilidad.rexy_asientos
CREATE TABLE IF NOT EXISTS `rexy_asientos` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `numero_asiento` int(9) NOT NULL,
  `fecha` date NOT NULL,
  `referencia_documento` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Bancaria o factura',
  `id_moneda` int(9) NOT NULL,
  `tipo_cambio` decimal(5,2) NOT NULL,
  `comentario` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_debe` decimal(12,2) NOT NULL,
  `total_haber` decimal(12,2) NOT NULL,
  `procedencia` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1:asientos',
  `usuario` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro',
  `activo` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1: habilitado, 0: anulado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla rexy_contabilidad.rexy_asientos: ~4 rows (aproximadamente)
INSERT INTO `rexy_asientos` (`id`, `numero_asiento`, `fecha`, `referencia_documento`, `id_moneda`, `tipo_cambio`, `comentario`, `total_debe`, `total_haber`, `procedencia`, `usuario`, `fecha_creacion`, `activo`) VALUES
	(1, 1, '2020-06-16', '0', 1, 1.00, 'Compra pollo', 4000.00, 5000.00, '1', 1, '2020-06-17 02:51:54', '0'),
	(2, 2, '2020-06-17', '35', 1, 1.00, 'Error de montos, no cuadra', 4000.00, 5000.00, '1', 1, '2020-06-17 02:53:20', '1'),
	(3, 3, '2020-06-16', '', 1, 1.00, '', 30000.00, 1000.00, '1', 1, '2020-06-17 02:55:58', '1'),
	(4, 4, '2020-06-18', '', 1, 1.00, '', 1000.00, 1000.00, '1', 1, '2020-06-19 03:08:28', '1');

-- Volcando estructura para tabla rexy_contabilidad.rexy_asientos_lineas
CREATE TABLE IF NOT EXISTS `rexy_asientos_lineas` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `id_asiento` int(9) NOT NULL,
  `id_tercero` int(9) NOT NULL DEFAULT 0,
  `tipo_origen` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1:cuenta, 2:item',
  `id_cuenta` int(9) NOT NULL COMMENT 'Lo trae del item',
  `id_item` int(9) NOT NULL,
  `tipo_linea_asiento` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1: debe, 2:haber',
  `monto` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla rexy_contabilidad.rexy_asientos_lineas: ~9 rows (aproximadamente)
INSERT INTO `rexy_asientos_lineas` (`id`, `id_asiento`, `id_tercero`, `tipo_origen`, `id_cuenta`, `id_item`, `tipo_linea_asiento`, `monto`) VALUES
	(1, 1, 0, '1', 9, 4, '1', 4000.00),
	(2, 1, 3, '2', 10, 4, '2', 5000.00),
	(3, 2, 0, '1', 9, 4, '2', 4000.00),
	(4, 2, 3, '2', 10, 4, '1', 5000.00),
	(5, 3, 2, '1', 14, 0, '1', 15000.00),
	(6, 3, 0, '2', 14, 4, '1', 15000.00),
	(7, 3, 0, '2', 14, 4, '2', 1000.00),
	(8, 4, 0, '1', 10, 0, '1', 1000.00),
	(9, 4, 0, '1', 10, 0, '2', 1000.00);

-- Volcando estructura para tabla rexy_contabilidad.rexy_cuentas_contables
CREATE TABLE IF NOT EXISTS `rexy_cuentas_contables` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `id_tipo_cuenta` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_padre` int(9) NOT NULL,
  `codigo_cuenta` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldoinicial` decimal(12,2) NOT NULL,
  `saldoactual` decimal(12,2) NOT NULL,
  `naturaleza` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1: deudor(debito - debe), 2: acreedor(credito - haber)',
  `id_moneda` int(9) NOT NULL,
  `comentario` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` int(11) NOT NULL COMMENT 'Usuario que lo registro',
  `fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `activo` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1:habilitado, 0: inhabilitado',
  `borrado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:no tiene, 1:si tiene',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla rexy_contabilidad.rexy_cuentas_contables: ~14 rows (aproximadamente)
INSERT INTO `rexy_cuentas_contables` (`id`, `id_tipo_cuenta`, `id_padre`, `codigo_cuenta`, `nombre`, `saldoinicial`, `saldoactual`, `naturaleza`, `id_moneda`, `comentario`, `usuario`, `fecha_creacion`, `activo`, `borrado`) VALUES
	(1, '-2', 0, '1', 'Caja y banco', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(2, '-2', 0, '2', 'Cuentas por cobrar', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(3, '-2', 0, '3', 'Inversiones', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(4, '-5', 0, '1', 'Cuentas por pagar', 0.00, 0.00, '2', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(5, '-5', 0, '2', 'Impuestos', 0.00, 0.00, '2', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(6, '-7', 0, '1', 'Capital social', 0.00, 0.00, '2', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(7, '-7', 0, '2', 'Utilidad', 0.00, 0.00, '2', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(8, '-8', 0, '1', 'Ingresos por servicios', 0.00, 0.00, '2', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(9, '-9', 0, '1', 'Gastos operativos', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(10, '-2', 1, '1', 'Bancos', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-09', '1', '0'),
	(11, '-2', 10, '1', 'Banco Nacional Gil', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-09', '0', '0'),
	(12, '-2', 11, '1', 'Dolares', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-10', '1', '1'),
	(13, '-2', 11, '2', 'Colones', 0.00, 0.00, '1', 1, 'Cta Inicial', 0, '2020-04-10', '0', '0'),
	(14, '-2', 2, '51', 'Prueba kris 2', 0.00, 0.00, '1', 2, 'Prueba borrar 5', 1, '2020-04-21', '0', '0');

-- Volcando estructura para tabla rexy_contabilidad.rexy_items
CREATE TABLE IF NOT EXISTS `rexy_items` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `id_cuenta` int(9) NOT NULL,
  `id_moneda` int(9) NOT NULL,
  `id_impuesto` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_item` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto_base` decimal(12,2) NOT NULL,
  `comentario` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_item` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1:servicio,2:producto',
  `activo` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `borrado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla rexy_contabilidad.rexy_items: ~4 rows (aproximadamente)
INSERT INTO `rexy_items` (`id`, `id_cuenta`, `id_moneda`, `id_impuesto`, `nombre_item`, `monto_base`, `comentario`, `tipo_item`, `activo`, `borrado`, `fecha_creacion`) VALUES
	(1, 13, 1, '3', 'Prueba 5', 5000.00, 'Prueba 4', '2', '1', '1', '2020-04-27 22:03:11'),
	(2, 12, 2, '8', 'Prueba', 100.00, 'Prueba', '1', '0', '0', '2020-04-27 22:03:11'),
	(3, 10, 1, '6', 'Prueba 3', 3500.00, 'prueba eliminado', '2', '1', '0', '2020-04-27 22:03:11'),
	(4, 14, 1, '8', 'Salario Quincena', 190000.00, 'Kris 2', '2', '1', '0', '2020-06-16 20:14:36');

-- Volcando estructura para tabla rexy_contabilidad.rexy_monedas
CREATE TABLE IF NOT EXISTS `rexy_monedas` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `simbolo` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `compra` decimal(5,2) NOT NULL DEFAULT 0.00,
  `venta` decimal(5,2) NOT NULL DEFAULT 0.00,
  `codificacion` char(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- Volcando datos para la tabla rexy_contabilidad.rexy_monedas: 2 rows
/*!40000 ALTER TABLE `rexy_monedas` DISABLE KEYS */;
INSERT INTO `rexy_monedas` (`id`, `nombre`, `simbolo`, `compra`, `venta`, `codificacion`) VALUES
	(1, 'Colón', '₡', 1.00, 1.00, 'es-CR'),
	(2, 'Dolar', '$', 580.52, 570.32, 'en-US');
/*!40000 ALTER TABLE `rexy_monedas` ENABLE KEYS */;

-- Volcando estructura para tabla rexy_contabilidad.rexy_parametros
CREATE TABLE IF NOT EXISTS `rexy_parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identificador` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `administrador` int(9) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `identificador` (`identificador`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla rexy_contabilidad.rexy_parametros: 9 rows
/*!40000 ALTER TABLE `rexy_parametros` DISABLE KEYS */;
INSERT INTO `rexy_parametros` (`id`, `identificador`, `descripcion`, `valor`, `administrador`) VALUES
	(1, 'URI', 'Localización del sistema', 'http://localhost:8080/', 1),
	(2, 'rootadmin', 'Localización de la carpeta', 'inicio', 1),
	(3, 'URL', 'Link del sistema', 'http://localhost:8080/', 1),
	(4, 'login_time', 'Limite de tiempo de login en minutos', '15', 1),
	(5, 'password_length', 'Tamaño de la contraseña generada', '15', 1),
	(6, 'login_path', 'Donde se guardará la Cookie', '/', 1),
	(7, 'CTA_MAYORES', 'Cuentas padre del sistema', '{"-1": {"id": -1,"padre": 0,"nombre": "Activos","codigo": "1"},"-2": {"id": -2,"padre": -1,"nombre": "Corrientes","codigo": "1"},"-3": {"id": -3,"padre": -1,"nombre": "No corrientes","codigo": "2"},"-4": {"id": -4,"padre": 0,"nombre": "Pasivos","codigo": "2"},"-5": {"id": -5,"padre": -4,"nombre": "Corrientes","codigo": "1"},"-6": {"id": -6,"padre": -4,"nombre": "No corrientes","codigo": "2"},"-7": {"id": -7,"padre": 0,"nombre": "Patrimonio","codigo": "3"},"-8": {"id": -8,"padre": 0,"nombre": "Ingresos","codigo": "4"},"-9": {"id": -9,"padre": 0,"nombre": "Gastos","codigo": "5"}}', 0),
	(8, 'IMPUESTOS', 'Listados de impuestos', '{"1": {"id":"1","num_hacienda":"01","nombre":"Tarifa 0% (Exento)","porcentaje":"0"},"2": {"id":"2","num_hacienda":"02","nombre":"Tarifa reducida 1% ","porcentaje":"1"},"3": {"id":"3","num_hacienda":"03","nombre":"Tarifa reducida 2% ","porcentaje":"2"},"4": {"id":"4","num_hacienda":"04","nombre":"Tarifa reducida 4% ","porcentaje":"4"},"5": {"id":"5","num_hacienda":"05","nombre":"Transitorio 0% ","porcentaje":"0"},"6": {"id":"6","num_hacienda":"06","nombre":"Transitorio 4% ","porcentaje":"1"},"7": {"id":"7","num_hacienda":"07","nombre":"Transitorio 8%","porcentaje":"8"},"8": {"id":"8","num_hacienda":"08","nombre":"Tarifa general 13% ","porcentaje":"13"}}', 1),
	(9, 'TIPOS_IDENTIFICACION', 'Listados de tipos de identificacion', '{"1": {"id":"1","num_hacienda":"01","nombre":"Cédula Física"},"2": {"id":"2","num_hacienda":"02","nombre":"Cédula Jurídica"},"3": {"id":"3","num_hacienda":"03","nombre":"DIMEX"},"4": {"id":"4","num_hacienda":"04","nombre":"NITE"}}', 1);
/*!40000 ALTER TABLE `rexy_parametros` ENABLE KEYS */;

-- Volcando estructura para tabla rexy_contabilidad.rexy_roles_usuario
CREATE TABLE IF NOT EXISTS `rexy_roles_usuario` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `nombre_rol` (`nombre_rol`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla rexy_contabilidad.rexy_roles_usuario: 1 rows
/*!40000 ALTER TABLE `rexy_roles_usuario` DISABLE KEYS */;
INSERT INTO `rexy_roles_usuario` (`id`, `nombre_rol`) VALUES
	(1, 'Super Administrador');
/*!40000 ALTER TABLE `rexy_roles_usuario` ENABLE KEYS */;

-- Volcando estructura para tabla rexy_contabilidad.rexy_roles_usuario_permisos
CREATE TABLE IF NOT EXISTS `rexy_roles_usuario_permisos` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `id_rol` int(9) NOT NULL,
  `id_menu_admin` int(9) NOT NULL,
  `visualizar` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agregar` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editar` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `borrar` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla rexy_contabilidad.rexy_roles_usuario_permisos: 35 rows
/*!40000 ALTER TABLE `rexy_roles_usuario_permisos` DISABLE KEYS */;
INSERT INTO `rexy_roles_usuario_permisos` (`id`, `id_rol`, `id_menu_admin`, `visualizar`, `agregar`, `editar`, `borrar`) VALUES
	(1, 1, 1, '1', '1', '1', '1'),
	(5, 2, 4, '1', '0', '0', '0'),
	(4, 2, 1, '1', '0', '0', '0'),
	(3, 1, 3, '1', '1', '1', '1'),
	(2, 1, 2, '1', '1', '1', '1'),
	(6, 1, 4, '1', '1', '1', '1'),
	(25, 1, 13, '1', '1', '1', '1'),
	(14, 3, 11, '1', '1', '1', '1'),
	(24, 1, 12, '1', '1', '1', '1'),
	(12, 1, 9, '1', '1', '1', '1'),
	(23, 1, 11, '1', '1', '1', '1'),
	(15, 3, 12, '1', '1', '1', '1'),
	(16, 3, 13, '1', '1', '1', '1'),
	(17, 3, 14, '1', '1', '1', '1'),
	(18, 3, 6, '1', '0', '0', '0'),
	(19, 3, 15, '1', '0', '0', '0'),
	(26, 1, 10, '1', '1', '1', '1'),
	(21, 3, 16, '1', '1', '1', '1'),
	(27, 1, 14, '1', '1', '1', '1'),
	(28, 1, 15, '1', '1', '1', '1'),
	(29, 1, 16, '1', '1', '1', '1'),
	(30, 1, 17, '1', '1', '1', '1'),
	(31, 1, 18, '1', '1', '1', '1'),
	(32, 1, 19, '1', '1', '1', '1'),
	(33, 1, 20, '1', '1', '1', '1'),
	(34, 1, 21, '1', '1', '1', '1'),
	(35, 1, 22, '1', '1', '1', '1'),
	(36, 1, 23, '1', '1', '1', '1'),
	(37, 1, 24, '1', '1', '1', '1'),
	(38, 1, 25, '1', '1', '1', '1'),
	(39, 1, 26, '1', '1', '1', '1'),
	(40, 1, 27, '1', '1', '1', '1'),
	(41, 1, 28, '1', '1', '1', '1'),
	(42, 1, 29, '1', '1', '1', '1'),
	(43, 1, 30, '1', '1', '1', '1');
/*!40000 ALTER TABLE `rexy_roles_usuario_permisos` ENABLE KEYS */;

-- Volcando estructura para tabla rexy_contabilidad.rexy_roles_usuario_scripts
CREATE TABLE IF NOT EXISTS `rexy_roles_usuario_scripts` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `script` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscripts` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Scripts derivados del padre',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `script` (`script`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla rexy_contabilidad.rexy_roles_usuario_scripts: 26 rows
/*!40000 ALTER TABLE `rexy_roles_usuario_scripts` DISABLE KEYS */;
INSERT INTO `rexy_roles_usuario_scripts` (`id`, `nombre`, `script`, `subscripts`) VALUES
	(1, 'Usuarios Administradores', 'administrator.php', 'administrator-editar.php,administrator-crear.php'),
	(2, 'Permisos de Usuario', 'permisos.php', 'permisos-editar.php,permisos-crear.php'),
	(3, 'Roles de Usuario', 'roles-usuario.php', 'roles-usuario-permisos.php,roles-usuario-editar.php,roles-usuario-crear.php'),
	(4, 'Parametros Generales', 'parametros.php', 'parametros-crear.php,parametros-editar.php'),
	(9, 'Biblioteca General de Medios', 'biblioteca-general.php', ''),
	(12, 'Monedas', 'monedas.php', 'monedas-crear.php,monedas-editar.php'),
	(13, 'Histórico tipo cambio', 'historico.php', 'historico-crear.php,historico-editar.php'),
	(11, 'Cuentas', 'cuentas.php', 'cuentas-editar.php,cuentas-crear.php,cuentas-lineas.php'),
	(10, 'Tipos de Cuentas', 'tipo-cuenta.php', 'tipo-cuenta-crear.php,tipo-cuenta-editar.php'),
	(14, 'Perfil', 'administrator-perfil.php', 'administrator-perfil.php'),
	(15, 'Factura de ingresos', 'factura-ingreso.php', 'factura-ingreso-crear.php; factura-ingreso-editar.php'),
	(16, 'Medios de Pago', 'medio-pago.php', 'medio-pago-crear.php; medio-pago-editar.php'),
	(17, 'Emisores', 'emisores.php', 'emisores-crear.php; emisores-editar.php'),
	(18, 'Proveedores', 'proveedores.php', 'proveedores-editar.php; proveedores-crear.php'),
	(19, 'Clientes', 'clientes.php', 'clientes-crear.php; clientes-editar.php'),
	(20, 'Impuestos', 'impuesto.php', 'impuesto-editar.php;impuesto-crear.php'),
	(21, 'Periodo de pago', 'periodo-pago.php', 'periodo-pago-crear.php,periodo-pago-editar.php'),
	(22, 'Tipos de documentos', 'tipos-documentos.php', 'tipos-documentos.php'),
	(23, 'Tipos de referencias', 'tipos-referencias.php', 'tipos-referencias.php'),
	(24, 'Factura de gastos', 'factura-gastos.php', 'factura-gastos-crear.php; factura-gastos-editar.php'),
	(25, 'Historico monedas', 'historico-monedas.php', 'historico-monedas.php'),
	(26, 'Asientos', 'asientos.php', 'asientos-crear.php,asientos-editar.php'),
	(27, 'Anulacion de Asientos', 'anulacion-asientos.php', 'anulacion-asientos-crear.php; anulacion-asientos-editar.php'),
	(28, 'Pagos recibidos', 'pagos-recibidos.php', 'pagos-recibidos-crear.php,pagos-recibidos-editar.php'),
	(29, 'Pagos realizados', 'pagos-realizados.php', 'pagos-realizados-crear.php,pagos-realizados-editar.php'),
	(30, 'Estados bancarios', 'estados-bancarios.php', 'estados-bancarios-add.php');
/*!40000 ALTER TABLE `rexy_roles_usuario_scripts` ENABLE KEYS */;

-- Volcando estructura para tabla rexy_contabilidad.rexy_terceros
CREATE TABLE IF NOT EXISTS `rexy_terceros` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `tipo_identificacion` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT 'Son del listado de tipos de identificacion',
  `identificacion` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clasificacion` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `borrado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla rexy_contabilidad.rexy_terceros: ~3 rows (aproximadamente)
INSERT INTO `rexy_terceros` (`id`, `tipo_identificacion`, `identificacion`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`, `clasificacion`, `activo`, `borrado`, `fecha_creacion`) VALUES
	(1, '1', '305550789', 'Gilberth', 'Monge Gonzalez', 'gilmonge@gmail.com', '25252525', 'Prueba direccion', '3', '1', '0', '2020-04-27 22:33:16'),
	(2, '1', '305220888', 'Usuario', 'Prueba', 'kmoras@prueba.com', '87878484', 'Juan viñas', '1', '1', '0', '2020-06-16 20:18:25'),
	(3, '1', '303330333', 'Probando', 'Nuevo', 'pnuevo@hahas.com', '87848785', 'sdfsdfs', '1', '1', '0', '2020-06-16 20:23:36');

-- Volcando estructura para tabla rexy_contabilidad.rexy_usuarios
CREATE TABLE IF NOT EXISTS `rexy_usuarios` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrasena` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `rol` int(9) NOT NULL DEFAULT 100,
  `estado` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=activado, 0=inactivo',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `usuario` (`usuario`),
  FULLTEXT KEY `correo` (`correo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla rexy_contabilidad.rexy_usuarios: 2 rows
/*!40000 ALTER TABLE `rexy_usuarios` DISABLE KEYS */;
INSERT INTO `rexy_usuarios` (`id`, `usuario`, `correo`, `nombre`, `apellido`, `contrasena`, `codigo`, `fecha`, `rol`, `estado`) VALUES
	(1, 'gmongeconta', 'gmongecontabilidad@mailinator.com', 'Gilberth', 'Monge González', '0daf097e43de8adf2b30190d989c10064542f41c', '4985328271053', '2018-03-27', 1, '1'),
	(2, 'prueba2', 'prueba@mailinator.com', 'prueba', 'pruebas', 'c5a6e065dee82fb30cc83df115a9d601eabf9b70', '1198888089', '2019-06-23', 1, '1');
/*!40000 ALTER TABLE `rexy_usuarios` ENABLE KEYS */;

-- Volcando estructura para tabla rexy_contabilidad.rexy_usuarios_conectados
CREATE TABLE IF NOT EXISTS `rexy_usuarios_conectados` (
  `usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora_acceso` datetime NOT NULL,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abierto` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Si es 1 indica que se debe iniciar la session automático ',
  `codigo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'identificador para identificar la maquina que se utiliza'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla rexy_contabilidad.rexy_usuarios_conectados: 1 rows
/*!40000 ALTER TABLE `rexy_usuarios_conectados` DISABLE KEYS */;
INSERT INTO `rexy_usuarios_conectados` (`usuario`, `hora_acceso`, `ip`, `abierto`, `codigo`) VALUES
	('gmongeconta', '2024-06-18 00:49:47', '172.21.0.1', '0', '7371527885462');
/*!40000 ALTER TABLE `rexy_usuarios_conectados` ENABLE KEYS */;

-- Volcando estructura para vista rexy_contabilidad.vs_listado_items
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vs_listado_items` (
	`id` INT(9) NOT NULL,
	`nombre_item` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`monto_base` DECIMAL(12,2) NOT NULL,
	`activo` INT(1) NOT NULL,
	`id_cuenta` INT(9) NOT NULL,
	`naturaleza` CHAR(1) NOT NULL COMMENT '1: deudor(debito - debe), 2: acreedor(credito - haber)' COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Volcando estructura para vista rexy_contabilidad.vs_permisos_roles
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vs_permisos_roles` (
	`ID` INT(9) NOT NULL,
	`Rol` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`IDRol` INT(9) NOT NULL,
	`Permiso` VARCHAR(60) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`Visualizar` CHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`Agregar` CHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`Editar` CHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`Borrar` CHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vs_listado_items`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vs_listado_items` AS select `i`.`id` AS `id`,`i`.`nombre_item` AS `nombre_item`,`i`.`monto_base` AS `monto_base`,if(`i`.`activo` = 1,if(`i`.`borrado` = 1,0,1),0) AS `activo`,`i`.`id_cuenta` AS `id_cuenta`,`cc`.`naturaleza` AS `naturaleza` from (`rexy_items` `i` join `rexy_cuentas_contables` `cc` on(`cc`.`id` = `i`.`id_cuenta`));

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vs_permisos_roles`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vs_permisos_roles` AS select `rexy_roles_usuario_permisos`.`id` AS `ID`,`rexy_roles_usuario`.`nombre_rol` AS `Rol`,`rexy_roles_usuario`.`id` AS `IDRol`,`rexy_roles_usuario_scripts`.`nombre` AS `Permiso`,`rexy_roles_usuario_permisos`.`visualizar` AS `Visualizar`,`rexy_roles_usuario_permisos`.`agregar` AS `Agregar`,`rexy_roles_usuario_permisos`.`editar` AS `Editar`,`rexy_roles_usuario_permisos`.`borrar` AS `Borrar` from ((`rexy_roles_usuario_permisos` join `rexy_roles_usuario_scripts` on(`rexy_roles_usuario_scripts`.`id` = `rexy_roles_usuario_permisos`.`id_menu_admin`)) join `rexy_roles_usuario` on(`rexy_roles_usuario`.`id` = `rexy_roles_usuario_permisos`.`id_rol`));

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
