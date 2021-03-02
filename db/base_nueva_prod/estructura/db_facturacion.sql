-- Base de Datos 

DROP SCHEMA IF EXISTS `db_facturacion` ;
CREATE SCHEMA IF NOT EXISTS `db_facturacion` default CHARACTER SET utf8 ;
USE `db_facturacion` ;

-- GRANT ALL PRIVILEGES ON `db_facturacion`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `categoria`
--
create table if not exists `categoria`(
`cat_id` bigint(20) not null auto_increment primary key,
`cat_nombre` varchar(200) not null,
`cat_descripcion` varchar(500) not null,
`cat_usu_ingreso` bigint(20) not null,
`cat_usu_modifica` bigint(20) null,
`cat_estado` varchar(1) not null,
`cat_fecha_creacion` timestamp not null default current_timestamp,
`cat_fecha_modificacion` timestamp null default null,
`cat_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `sub_categoria`
--
create table if not exists `sub_categoria`(
`scat_id` bigint(20) not null auto_increment primary key,
`cat_id` bigint(20) not null,
`scat_nombre` varchar(200) not null,
`scat_descripcion` varchar(500) not null,
`scat_usu_ingreso` bigint(20) not null,
`scat_usu_modifica` bigint(20) null,
`scat_estado` varchar(1) not null,
`scat_fecha_creacion` timestamp not null default current_timestamp,
`scat_fecha_modificacion` timestamp null default null,
`scat_estado_logico` varchar(1) not null,
foreign key (cat_id) references `categoria`(cat_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `item`
--
create table if not exists `item` (
  `ite_id` bigint(20) not null auto_increment primary key,  
  `scat_id` bigint(20) not null,    
  `ite_nombre` varchar(200) not null,
  `ite_descripcion` varchar(500) not null,  
  `ite_codigo` varchar(05) not null,
  `ite_ruta_imagen` varchar(500) null,  
  `ite_usu_ingreso` bigint(20) not null,
  `ite_usu_modifica` bigint(20) null,
  `ite_estado` varchar(1) not null,
  `ite_fecha_creacion` timestamp not null default current_timestamp,
  `ite_fecha_modificacion` timestamp null default null,
  `ite_estado_logico` varchar(1) not null,  
  foreign key (scat_id) references `sub_categoria`(scat_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `item_precio`
--
create table if not exists `item_precio` (
  `ipre_id` bigint(20) not null auto_increment primary key,
  `ite_id` bigint(20) not null,  
  `ipre_precio` double default null,    
  `ipre_porcentaje_iva` double default null,   
  `ipre_valor_minimo` double default null,    
  `ipre_porcentaje_minimo` double default null,
  `ipre_fecha_inicio`  timestamp null,
  `ipre_fecha_fin`  timestamp null, 
  `ipre_estado_precio` varchar(1) not null,
  `ipre_usu_ingreso` bigint(20) not null,
  `ipre_usu_modifica` bigint(20) null,
  `ipre_estado` varchar(1) not null,
  `ipre_fecha_creacion` timestamp not null default current_timestamp,
  `ipre_fecha_modificacion` timestamp null default null,
  `ipre_estado_logico` varchar(1) not null, 
  foreign key (ite_id) references `item`(ite_id) 
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `historial_item_precio`
--
create table if not exists `historial_item_precio` (
  `hipr_id` bigint(20) not null auto_increment primary key,
  `ite_id` bigint(20) not null,
  `hipr_precio` double default null,
  `hipr_porcentaje_iva` double default null,
  `hipr_fecha_inicio`  timestamp null,
  `hipr_fecha_fin`  timestamp null, 
  `hipr_valor_minimo` double null, 
  `hipr_porcentaje_minimo` double null,
  `hipr_usu_transaccion` bigint(20) not null,
  `hipr_estado` varchar(1) not null,
  `hipr_fecha_creacion` timestamp not null default current_timestamp,
  `hipr_fecha_modificacion` timestamp null default null,
  `hipr_estado_logico` varchar(1) not null, 
  foreign key (ite_id) references `item`(ite_id) 
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `item_metodo_nivel`
--
create table if not exists `item_metodo_unidad` (
  `imni_id` bigint(20) not null primary key,  
  `ite_id`  bigint(20) not null,  
  `ming_id` bigint(20) null,  
  `uaca_id` bigint(20) null,
  `mod_id` bigint(20) null,
  `mest_id` bigint(20) null,
  `imni_usu_ingreso` bigint(20) not null,
  `imni_usu_modifica` bigint(20) null,
  `imni_estado` varchar(1) not null,
  `imni_fecha_creacion` timestamp not null default current_timestamp,
  `imni_fecha_modificacion` timestamp null default null,
  `imni_estado_logico` varchar(1) not null, 
  foreign key (ite_id) references `item`(ite_id)  
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `persona_solicitud`
--
create table if not exists `persona_beneficiaria` (
 `pben_id` bigint(20) not null auto_increment primary key,
 `pben_nombre` varchar(250) default null,
 `pben_apellido` varchar(250) default null, 
 `pben_cedula` varchar(15) not null,
 `pben_ruc` varchar(15) default null,
 `pben_pasaporte` varchar(50) default null, 
 `pben_celular` varchar(50) default null,
 `pben_correo` varchar(250) default null, 
 `pben_estado` varchar(1) not null,
 `pben_fecha_creacion` timestamp not null default current_timestamp,
 `pben_fecha_modificacion` timestamp null default null,
 `pben_estado_logico` varchar(1) not null 
); 
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `solicitud_boton_pago`
-- 
create table if not exists `solicitud_boton_pago` (
  `sbpa_id` bigint(20) not null auto_increment primary key,    
  `pben_id` bigint(20) null,  
  `sbpa_fecha_solicitud` timestamp null,
  `sbpa_estado` varchar(1) not null,
  `sbpa_fecha_creacion` timestamp not null default current_timestamp,
  `sbpa_fecha_modificacion` timestamp null default null, 
  `sbpa_estado_logico` varchar(1) not null,  
   foreign key (pben_id) references `persona_beneficiaria` (pben_id)
) ;

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `detalle_solicitud_general`
--
create table if not exists `detalle_solicitud_boton_pago` (
  `dsbp_id` bigint(20) not null auto_increment primary key,    
  `sbpa_id` bigint(20) not null,    
  `ite_id` bigint(20) not null,  
  `dsbp_cantidad` int not null,    
  `dsbp_precio` double not null,    
  `dsbp_valor_iva` double not null, 
  `dsbp_valor_total` double not null, 
  `dsbp_estado` varchar(1) not null,
  `dsbp_fecha_creacion` timestamp not null default current_timestamp,
  `dsbp_fecha_modificacion` timestamp null default null, 
  `dsbp_estado_logico` varchar(1) not null,  
  foreign key (sbpa_id) references `solicitud_boton_pago` (sbpa_id),
  foreign key (ite_id) references `item` (ite_id)
) ;

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `orden_pago`
--
create table if not exists `orden_pago` (
  `opag_id` bigint(20) not null auto_increment primary key,  
  `sins_id` bigint(20) null, 
  `sbpa_id` bigint(20) null,   
  `doc_id` bigint(20) null,   
  `opag_subtotal` double not null,
  `opag_iva` double not null,  
  `opag_total` double not null,
  `opag_valor_pagado` double default null,
  `opag_fecha_generacion` timestamp null,
  `opag_estado_pago` varchar(1) null,
  `opag_fecha_pago_total` timestamp null default null,
  `opag_observacion` varchar(200) default null,
  `opag_usu_ingreso` bigint(20) not null,  
  `opag_usu_modifica` bigint(20) null,
  `opag_estado` varchar(1) not null,
  `opag_fecha_creacion` timestamp not null default current_timestamp,
  `opag_fecha_modificacion` timestamp null default null, 
  `opag_estado_logico` varchar(1) not null,
  foreign key (sbpa_id) references `solicitud_boton_pago` (sbpa_id)
) ;



-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `desglose_pago`
--
create table if not exists `desglose_pago` (
  `dpag_id` bigint(20) not null auto_increment primary key,  
  `opag_id` bigint(20) not null,  
  `ite_id` bigint(20) null,     
  `dpag_subtotal` double not null,
  `dpag_iva` double not null,  
  `dpag_total` double not null,
  `dpag_fecha_ini_vigencia` timestamp null default null,
  `dpag_fecha_fin_vigencia` timestamp null default null,
  `dpag_estado_pago` varchar(1) null,  
  `dpag_usu_ingreso` bigint(20) not null,  
  `dpag_usu_modifica` bigint(20) null,
  `dpag_estado` varchar(1) not null,
  `dpag_fecha_creacion` timestamp not null default current_timestamp,
  `dpag_fecha_modificacion` timestamp null default null, 
  `dpag_estado_logico` varchar(1) not null,
  foreign key (opag_id) references `orden_pago` (opag_id)
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `forma_pago`
--
create table if not exists `forma_pago` (
  `fpag_id` bigint(20) not null auto_increment primary key,
  `fpag_nombre` varchar(200) not null,
  `fpag_descripcion` varchar(500) not null,
  `fpag_distintivo` varchar(1) null, 
  `fpag_usu_ingreso` bigint(20) not null,  
  `fpag_usu_modifica` bigint(20) null,
  `fpag_estado` varchar(1) not null,
  `fpag_fecha_creacion` timestamp not null default current_timestamp,
  `fpag_fecha_modificacion` timestamp null default null,
  `fpag_estado_logico` varchar(1) null
) ;

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `registro_pago`
--
create table if not exists `registro_pago` (
  `rpag_id` bigint(20) not null auto_increment primary key,  
  `dpag_id` bigint(20) not null,
  `fpag_id` bigint(20) not null, 
  `rpag_valor` double not null,
  `rpag_fecha_pago` timestamp null default null,
  `rpag_imagen` varchar(100) null,  
  `rpag_observacion` text null,
  `rpag_revisado` varchar(2) not null,
  `rpag_resultado` varchar(2) null,
  `rpag_num_transaccion` varchar(50) not null,    
  `rpag_fecha_transaccion` timestamp null, 
  `rpag_usuario_transaccion` bigint(20) not null,
  `rpag_codigo_autorizacion` varchar(10) null,
  `rpag_estado` varchar(1) not null,   
  `rpag_fecha_creacion` timestamp not null default current_timestamp,
  `rpag_fecha_modificacion` timestamp null default null,
  `rpag_estado_logico` varchar(1) not null,
  foreign key (dpag_id) references `desglose_pago` (dpag_id),
  foreign key (fpag_id) references `forma_pago` (fpag_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_pago_factura`
--
create table if not exists `registro_pago_factura` (
  `rpfa_id` bigint(20) not null auto_increment primary key,     
  `fpag_id` bigint(20) null,
  `sins_id` bigint(20) null,  
  `rpfa_num_solicitud` varchar(10) null,
  `rpfa_valor_documento` double not null,
  `rpfa_fecha_documento` timestamp null default null,
  `rpfa_numero_documento` varchar(50) not null,
  `rpfa_imagen` varchar(100) null,  
  `rpfa_observacion` text null,
  `rpfa_revisado` varchar(2) not null,     
  `rpfa_fecha_transaccion` timestamp null,
  `rpfa_usuario_transaccion` bigint(20) not null,
  `rpfa_codigo_autorizacion` varchar(10) null,
  `rpfa_estado` varchar(1) not null,   
  `rpfa_fecha_creacion` timestamp not null default current_timestamp,
  `rpfa_fecha_modificacion` timestamp null default null,
  `rpfa_estado_logico` varchar(1) not null,
  foreign key (fpag_id) references `forma_pago` (fpag_id)
);


-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `info_carga_prepago`
--
create table if not exists `info_carga_prepago` (
  `icpr_id` bigint(20) not null auto_increment primary key,
  `opag_id` bigint(20) not null,
  `fpag_id` bigint(20) not null, 
  `icpr_valor` double not null,
  `icpr_valor_pagado` double null,
  `icpr_fecha_registro` timestamp null default null,
  `icpr_fecha_pago` timestamp null default null,
  `icpr_imagen` varchar(100) null,  
  `icpr_observacion` text null,
  `icpr_revisado` varchar(2) not null,
  `icpr_resultado` varchar(2) null,
  `icpr_num_transaccion` varchar(50) not null,
  `icpr_fecha_transaccion` timestamp null, 
  `icpr_usuario_transaccion` bigint(20) null,
  `icpr_estado` varchar(1) not null,
  `icpr_fecha_creacion` timestamp not null default current_timestamp,
  `icpr_fecha_modificacion` timestamp null default null,
  `icpr_estado_logico` varchar(1) not null,
  foreign key (opag_id) references `orden_pago` (opag_id),
  foreign key (fpag_id) references `forma_pago` (fpag_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_documento`
--
create table if not exists `tipo_documento` (
  `tdoc_id` bigint(20) not null auto_increment primary key,  
  `tdoc_nombre` varchar(50) not null,    
  `tdoc_usuario_ingreso` bigint(20) not null,
  `tdoc_usuario_modifica` bigint(20) null,
  `tdoc_estado` varchar(1) default null,
  `tdoc_fecha_creacion` timestamp not null default current_timestamp,
  `tdoc_fecha_modificacion` timestamp null default null,
  `tdoc_estado_logico` varchar(1) default null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `documento`
--
create table if not exists `documento` (
  `doc_id` bigint(20) not null auto_increment primary key,  
  `tdoc_id` bigint(20) not null,
  `sbpa_id` bigint(20) not null, 
  `doc_cedula` varchar(15) null,
  `doc_pasaporte` varchar(15) null,
  `doc_ruc` varchar(15) null,
  `doc_tipo_dni` bigint(1) null,
  `doc_nombres_cliente` varchar(250) not null,    
  `doc_direccion` varchar(500) default null,
  `doc_telefono` varchar(50) default null, 
  `doc_correo` varchar(50) default null, 
  `doc_valor` double not null, 
  `doc_pagado` varchar(1) not null, 
  `doc_fecha_pago` timestamp null, 
  `doc_usuario_transaccion` bigint(20) null,  
  `doc_estado` varchar(1) default null,
  `doc_fecha_creacion` timestamp not null default current_timestamp,
  `doc_fecha_modificacion` timestamp null default null,
  `doc_estado_logico` varchar(1) default null,
   foreign key (sbpa_id) references `solicitud_boton_pago` (sbpa_id)
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_comprobante`
--
create table if not exists `detalle_documento` (
  `ddoc_id` bigint(20) not null auto_increment primary key,  
  `doc_id` bigint(20) not null,  
  `ite_id` bigint(20) not null,  
  -- `sins_id` bigint(20) null,
  `ddoc_cantidad` int not null,    
  `ddoc_precio` double not null,   
  `ddoc_valor_iva` double not null, 
  `ddoc_valor_total` double not null, 
  `ddoc_usuario_transaccion` bigint(20) not null,  
  `ddoc_estado` varchar(1) default null,
  `ddoc_fecha_creacion` timestamp not null default current_timestamp,
  `ddoc_fecha_modificacion` timestamp null default null,
  `ddoc_estado_logico` varchar(1) default null,
  foreign key (doc_id) references `documento` (doc_id),
  foreign key (ite_id) references `item` (ite_id)
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `transaccion_botonpago_BP`
--
create table if not exists `transaccion_botonpago_BP` (
  `tbbp_id` bigint(20) not null auto_increment primary key,
  `opag_id` bigint(20) not null,
  `tbbp_num_tarjeta` varchar(25) null,   
  `tbbp_codigo_autorizacion` varchar(25) null,    
  `tbbp_resultado_autorizacion` varchar(45) null,
  `tbbp_codigo_error` varchar(25) null,   
  `tbbp_error_mensaje` varchar(100) null,   
  `tbbp_estado` varchar(1) default null,
  `tbbp_fecha_creacion` timestamp not null default current_timestamp,
  `tbbp_fecha_modificacion` timestamp null default null,
  `tbbp_estado_logico` varchar(1) default null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_factura`
--
create table if not exists `info_factura` (
  `ifac_id` bigint(20) not null auto_increment primary key,
  `opag_id` bigint(20) not null,
  `ifac_numero_dni` varchar(15) not null,   
  `ifac_tipo_dni` int not null,    
  `ifac_nombres` varchar(60) not null,
  `ifac_apellidos` varchar(60) not null,   
  `ifac_direccion` varchar(200) not null,   
  `ifac_correo` varchar(50) null,   
  `ifac_telefono` varchar(30) null,   
  `ifac_estado` varchar(1) default null,
  `ifac_fecha_creacion` timestamp not null default current_timestamp,
  `ifac_fecha_modificacion` timestamp null default null,
  `ifac_estado_logico` varchar(1) default null,
  foreign key (opag_id) references `orden_pago` (opag_id)
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `orden_pago_anulada`
--
create table if not exists `orden_pago_anulada`(
`opan_id` bigint(20) not null auto_increment primary key,
`opag_id` bigint(20) not null,
`sins_id` bigint(20) not null,
`opan_observacion` varchar(200) not null,
`opan_usu_anula` bigint(20) not null,
`opan_estado` varchar(1) not null,
`opan_fecha_creacion` timestamp not null default current_timestamp,
`opan_fecha_modificacion` timestamp null default null,
`opan_estado_logico` varchar(1) not null,
foreign key (opag_id) references `orden_pago`(opag_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `descuento_item`
-- --------------------------------------------------------
create table if not exists `descuento_item` (
  `dite_id` bigint(20) not null auto_increment primary key,  
  `ite_id` bigint(20) not null,   
  `dite_usu_creacion` bigint(20) default null,
  `dite_usu_modificacion` bigint(20) default null,
  `dite_estado` varchar(1) default null,
  `dite_fecha_creacion` timestamp not null default current_timestamp,
  `dite_fecha_modificacion` timestamp null default null,
  `dite_estado_logico` varchar(1) default null,
  foreign key (ite_id) references `item`(ite_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_descuento_item`
-- --------------------------------------------------------
create table if not exists `detalle_descuento_item` (
`ddit_id` bigint(20) not null auto_increment primary key,
`dite_id` bigint(20) not null,  
`ddit_descripcion` varchar(100) null,   
`ddit_tipo_beneficio` varchar(1) null, -- P:= Descuento, V:= Valor
`ddit_porcentaje` double  null, -- valor porcentaje.
`ddit_valor` double  null, -- valor en dolares.
`ddit_finicio`  timestamp null,
`ddit_ffin`  timestamp null, 
`ddit_estado_descuento`  varchar(1) not null, -- 'A'=activo, 'I'= inactivo
`ddit_usu_creacion` bigint(20) default null,
`ddit_usu_modificacion` bigint(20) default null,
`ddit_estado`  varchar(1) not null,
`ddit_fecha_creacion` timestamp not null default current_timestamp,
`ddit_fecha_modificacion` timestamp null default null,
`ddit_estado_logico` varchar(1) default null,
foreign key (dite_id) references `descuento_item`(dite_id)
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `historial_descuento_item`
-- -------------------------------------------------------------
create table if not exists `historial_descuento_item` (
  `hdit_id` bigint(20) not null auto_increment primary key,  
  `ddit_id` bigint(20) not null,
  `dite_id` bigint(20) not null,
  `hdit_porcentaje` double null,
  `hdit_tipo_beneficio` varchar(1) null, -- P:= Descuento, V:= Valor
  `hdit_valor` double null, 
  `hdit_descripcion` varchar(100) null,
  `hdit_fecha_inicio`  timestamp null,
  `hdit_fecha_fin`  timestamp null, 
  `hdit_estado_descuento`  varchar(1) not null,
  `hdit_usu_transaccion` bigint(20) not null,
  `hdit_estado` varchar(1) not null,
  `hdit_fecha_creacion` timestamp not null default current_timestamp,
  `hdit_fecha_modificacion` timestamp null default null,
  `hdit_estado_logico` varchar(1) not null,   
  foreign key (dite_id) references `descuento_item`(dite_id),
  foreign key (ddit_id) references `detalle_descuento_item`(ddit_id)
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `solicitud_descuento`
-- -------------------------------------------------------------
create table if not exists `solicitud_descuento` (
  `sdes_id` bigint(20) not null auto_increment primary key,  
  `sins_id` bigint(20) not null,
  `ddit_id` bigint(20) not null,
  `sdes_precio` double not null, -- precio
  `sdes_porcentaje` double null, -- porcentaje
  `sdes_valor` double null,
  `sdes_estado` varchar(1) not null,
  `sdes_fecha_creacion` timestamp not null default current_timestamp,
  `sdes_fecha_modificacion` timestamp null default null,
  `sdes_estado_logico` varchar(1) not null,   
  foreign key (ddit_id) references `detalle_descuento_item`(ddit_id)
);
-- -------------------------------------------------------------
--
-- Estructura de tabla para generar secuencia
-- -------------------------------------------------------------
create table if not exists `secuencias` (
 `emp_id` bigint(20) not null,#Empresa
 `estab_id` bigint(20) not null,#Establecimiento
 `pemis_id` bigint(20) not null,#Punto de Emision
 `secu_tipo_doc` varchar(3) not null,#Tipo de Documento
 `secuencia` varchar(10) not null default '0000000000',#Secuencia del documento
 `secu_nombre` varchar(200) not null,#Tipo de Documento
 `edoc_tipo` varchar(2) default null,#Tipo de Documento Electronico segun SRI
 `edoc_estado` varchar(1) default '0',#Estado para Generar documento Electronico
 `secu_cant_items` int(2) default null, #Cantidad Lineas por documentos
 `secu_cuenta_iva` varchar(12) default null ,
 `secu_grupo_modulo` varchar(3) default null ,#GRUPO MODULO DOCUEMNTO 
 `secu_estado` varchar(1) not null ,
 `secu_fecha_creacion` timestamp not null default current_timestamp,
 `secu_fecha_modificacion` timestamp null default null,
 `secu_estado_logico` varchar(1) not null,
 primary key(`emp_id`,`estab_id`,`pemis_id`,`secu_tipo_doc`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `item_parametro`
-- -------------------------------------------------------------
create table if not exists `item_parametro` (
  `ipar_id` bigint(20) not null auto_increment primary key,  
  `uaca_id` bigint(20) not null,  
  `mod_id` bigint(20) not null,    
  `ipar_ite_inscripcion` bigint(20) null,  
  `ipar_ite_matriculacion` bigint(20) null,  
  `ipar_estado` varchar(1) not null,
  `ipar_fecha_creacion` timestamp not null default current_timestamp,
  `ipar_fecha_modificacion` timestamp null default null,
  `ipar_estado_logico` varchar(1) not null  
);


-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `otros_item_metodo_nivel`
-- -------------------------------------------------------------
create table if not exists `otros_item_metodo_nivel` (
  `oimn_id` bigint(20) not null primary key,  
  `ite_id`  bigint(20) not null,  
  `ming_id` bigint(20) null,  
  `uaca_id` bigint(20) null,
  `mod_id` bigint(20) null, 
  `oimn_usu_ingreso` bigint(20) not null,
  `oimn_usu_modifica` bigint(20) null,
  `oimn_estado` varchar(1) not null,
  `oimn_fecha_creacion` timestamp not null default current_timestamp,
  `oimn_fecha_modificacion` timestamp null default null,
  `oimn_estado_logico` varchar(1) not null, 
  foreign key (ite_id) references `item`(ite_id)  
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `pagos_contrato_programa`
-- -------------------------------------------------------------
create table if not exists `pagos_contrato_programa` (
  `pcpr_id` bigint(20) not null auto_increment primary key,  
  `adm_id`  bigint(20) not null,  
  `cemp_id` bigint(20) null,  
  `pcpr_archivo` varchar(100) not null,  
  `pcpr_usu_ingreso` bigint(20) not null,
  `pcpr_usu_modifica` bigint(20) null,
  `pcpr_estado` varchar(1) not null,
  `pcpr_fecha_creacion` timestamp not null default current_timestamp,
  `pcpr_fecha_modificacion` timestamp null default null,
  `pcpr_estado_logico` varchar(1) not null  
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `rubro_pago_docencia`
-- -------------------------------------------------------------
create table if not exists `rubro_pago_docencia` (
  `rpdo_id` bigint(20) not null primary key,  
  `uaca_id`  bigint(20) not null,  
  `mod_id` bigint(20) not null,  
  `rpdo_tipo_titulo` varchar(1) not null, /* 'M' Maestría, 'D' Doctorado */
  `rpdo_tarifa_hora` double not null,
  `rpdo_usu_ingreso` bigint(20) not null,
  `rpdo_usu_modifica` bigint(20) null,
  `rpdo_estado` varchar(1) not null,
  `rpdo_fecha_creacion` timestamp not null default current_timestamp,
  `rpdo_fecha_modificacion` timestamp null default null,
  `rpdo_estado_logico` varchar(1) not null  
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `factura_docente`
-- -------------------------------------------------------------
create table if not exists `factura_docente` (
  `fdoc_id` bigint(20) not null primary key,
  `daca_id`  bigint(20) not null,
  `pro_id`  bigint(20) not null,  
  `rpdo_id` bigint(20) not null,
  `fdoc_archivo` varchar(100) not null,
  `fdoc_valor_factura` double not null,
  `fdoc_valor_pagado` double not null,
  `fdoc_fecha_registro` timestamp null default null,
  `fdoc_fecha_aprueba_rechaza` timestamp null default null,
  `fdoc_fecha_pago` timestamp null default null,
  `fdoc_estado_factura` varchar(1) not null,  /* '1' Pendiente, '2' Aprobada, '3' Rechazada, '4' Pagada */
  `fdoc_observacion_factura` varchar(1) null, 
  `fdoc_usu_ingreso` bigint(20) not null,
  `fdoc_usu_aprueba_rechaza` bigint(20) null,
  `fdoc_usu_pago` bigint(20) null,
  `fdoc_estado` varchar(1) not null,
  `fdoc_fecha_creacion` timestamp not null default current_timestamp,
  `fdoc_fecha_modificacion` timestamp null default null,
  `fdoc_estado_logico` varchar(1) not null,
   foreign key (rpdo_id) references `rubro_pago_docencia`(rpdo_id)
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `pagos_factura_estudiante`
-- -------------------------------------------------------------
create table if not exists `pagos_factura_estudiante` (
  `pfes_id` bigint(20) not null auto_increment  primary key,
  `est_id`  bigint(20) not null,
  `pfes_referencia` varchar(50) null, 
  `fpag_id` bigint(20) not null,
  `pfes_valor_pago` double not null,
  `pfes_fecha_pago` timestamp null default null,  
  `pfes_observacion` varchar(500) null, 
  `pfes_archivo_pago` varchar(200) not null,
  `pfes_fecha_registro` timestamp null default null,
  `pfes_usu_ingreso` bigint(20) not null,    
  `pfes_estado` varchar(1) not null,
  `pfes_fecha_creacion` timestamp not null default current_timestamp,
  `pfes_fecha_modificacion` timestamp null default null,
  `pfes_estado_logico` varchar(1) not null,
   foreign key (fpag_id) references `forma_pago`(fpag_id)
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_pagos_factura`
-- -------------------------------------------------------------
create table if not exists `detalle_pagos_factura` (
  `dpfa_id` bigint(20) not null auto_increment primary key,
  `pfes_id` bigint(20) not null,  
  `dpfa_tipo_factura` varchar(05) not null, 
  `dpfa_factura` varchar(50) not null, 
  `dpfa_descripcion_factura` varchar(500) not null,
  `dpfa_valor_factura` double not null,
  `dpfa_fecha_factura` timestamp null default null,  
  `dpfa_saldo_factura` double not null,
  `dpfa_num_cuota` varchar(10) not null,
  `dpfa_valor_cuota` double null,
  `dpfa_fecha_vence_cuota` timestamp null default null,  
  `dpfa_estado_pago` varchar(1) not null,  /* '1' Pendiente, '2' Aprobada, '3' Rechazada*/
  `dpfa_estado_financiero` varchar(1) null,  /* 'N' Pendiente, 'C' Cancelado */
  `dpfa_observacion_rechazo` varchar(500) null,
  `dpfa_observacion_reverso` varchar(500) null, 
  `dpfa_fecha_aprueba_rechaza` timestamp null default null,  
  `dpfa_usu_aprueba_rechaza` bigint(20) null,
  `dpfa_fecha_registro` timestamp null default null,
  `dpfa_usu_ingreso` bigint(20) not null,    
  `dpfa_usu_modifica` bigint(20) null, 
  `dpfa_estado` varchar(1) not null,
  `dpfa_fecha_creacion` timestamp not null default current_timestamp,
  `dpfa_fecha_modificacion` timestamp null default null,
  `dpfa_estado_logico` varchar(1) not null
);

/* tabla temporal */
create table db_facturacion.tmp_facturas_aprobadas
(dpfa_id		 bigint(20) not null,
 dpfa_tipo_factura  	 varchar(05) not null, 
 dpfa_factura		 varchar(50) not null, 
 dpfa_num_cuota     	 varchar(10) not null,
 identificacion     	 varchar(13) not null,
 dpfa_estado_financiero  varchar(1) not null
);

-- -------------------------------------------------------------
--
-- Estructura de tabla para la tabla `carga_cartera`
-- -------------------------------------------------------------
create table db_facturacion.carga_cartera(
  `ccar_id` bigint(20) not null auto_increment primary key,
  `ccar_punto` varchar(05) null,  
  `ccar_caja` varchar(05) null,   
  `est_id` bigint(20) not null,   
  `ccar_tipo_documento` varchar(03) not null, -- FE factura  
  `ccar_numero_documento` varchar(30) not null, 
  `ccar_documento_identidad` varchar(10) null,
  `ccar_forma_pago` varchar(03) not null, -- CR credito, EF efectivo
  `ccar_num_cuota` varchar(10) not null,
  `ccar_fecha_factura` timestamp null default null,
  `ccar_fecha_vencepago` timestamp null default null,
  `ccar_dias_plazo` bigint(20) null,  
  `ccar_valor_cuota` double null, -- SI es efectivo mismo valor factura
  `ccar_valor_factura` double null,
  `ccar_fecha_pago` timestamp null default null,
  `ccar_retencion_fuente` double null, 
  `ccar_retencion_iva` double null,
  `ccar_numero_retencion` varchar(100) null, 
  `ccar_valor_iva` varchar(03) null,
  `ccar_estado_cancela` varchar(3) not null, -- C CANCELADO, N Pendiente
  `ccar_codigo_cobrador` bigint(20) null, 
  `ccar_fecha_aprueba_rechaza` timestamp null default null,  
  `ccar_usu_aprueba_rechaza` bigint(20) null,  
  `ccar_usu_ingreso` bigint(20) not null,    
  `ccar_usu_modifica` bigint(20) null, 
  `ccar_estado` varchar(1) not null,
  `ccar_fecha_creacion` timestamp not null default current_timestamp,
  `ccar_fecha_modificacion` timestamp null default null,
  `ccar_estado_logico` varchar(1) not null
  );