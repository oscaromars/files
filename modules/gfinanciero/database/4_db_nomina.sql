
-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: financiero
-- ------------------------------------------------------
-- Server version	5.6.40

USE `db_gfinanciero` ;


-- SET foreign_key_checks = 0;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_contrato`
--
DROP TABLE IF EXISTS `tipo_contrato`;
create table if not exists `tipo_contrato` (
  `tipc_id` bigint(20) not null auto_increment primary key,
  `tipc_nombre` varchar(200) null,
  `tipc_usuario_ingreso` bigint(20) null,
  `tipc_usuario_modifica` bigint(20) null,
  `tipc_estado` varchar(1) null,
  `tipc_fecha_creacion` timestamp null default current_timestamp,
  `tipc_fecha_modificacion` timestamp null,
  `tipc_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_empleado`
--
DROP TABLE IF EXISTS `tipo_empleado`;
create table if not exists `tipo_empleado` (
  `tipe_id` bigint(20) not null auto_increment primary key,
  `tipe_nombre` varchar(200) null,
  `tipe_usuario_ingreso` bigint(20) null,
  `tipe_usuario_modifica` bigint(20) null,
  `tipe_estado` varchar(1) null,
  `tipe_fecha_creacion` timestamp null default current_timestamp,
  `tipe_fecha_modificacion` timestamp null,
  `tipe_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `discapacidad`
--
DROP TABLE IF EXISTS `discapacidad`;
create table if not exists `discapacidad` (
  `dis_id` bigint(20) not null auto_increment primary key,
  `dis_nombre` varchar(200) null,
  `dis_porcentaje` decimal(5,2) null,
  `dis_usuario_ingreso` bigint(20) null,
  `dis_usuario_modifica` bigint(20) null,
  `dis_estado` varchar(1) null,
  `dis_fecha_creacion` timestamp null default current_timestamp,
  `dis_fecha_modificacion` timestamp null,
  `dis_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `departamentos`
--
DROP TABLE IF EXISTS `departamentos`;
create table if not exists `departamentos` (
  `dep_id` bigint(20) not null auto_increment primary key,
  `dep_nombre` varchar(200) null,
  `dep_usuario_ingreso` bigint(20) null,
  `dep_usuario_modifica` bigint(20) null,
  `dep_estado` varchar(1) null,
  `dep_fecha_creacion` timestamp null default current_timestamp,
  `dep_fecha_modificacion` timestamp null,
  `dep_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `sub_departamento`
--
DROP TABLE IF EXISTS `sub_departamento`;
create table if not exists `sub_departamento` (
  `sdep_id` bigint(20) not null auto_increment primary key,
  `dep_id` bigint(20) not null,
  `sdep_nombre` varchar(200) null,
  `sdep_usuario_ingreso` bigint(20) null,
  `sdep_usuario_modifica` bigint(20) null,
  `sdep_estado` varchar(1) null,
  `sdep_fecha_creacion` timestamp null default current_timestamp,
  `sdep_fecha_modificacion` timestamp null,
  `sdep_estado_logico` varchar(1) null,
  foreign key (dep_id) references `departamentos`(dep_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empleado`
--
DROP TABLE IF EXISTS `empleado`;
create table if not exists `empleado` (
  `empl_codigo` varchar(20) not null  primary key,
  `sdep_id` bigint(20) not null,
  `per_id` bigint(20) null,
  `tcon_id` varchar(2) null, -- tipo contribuyente tabla financiero TIP_CON
  `dis_id` bigint(20) null,
  `tipe_id` bigint(20) not null, -- tipo empleado
  `tipc_id` bigint(20) not null, -- tipo contrato
  `empl_ids_ban` bigint(20) null, -- id del banco
  `empl_cod_vendedor` varchar(3) null, -- codigo vendedor de la tabla TIP_CON
  `empl_cedula_ruc` varchar(20) null,
  `empl_nombre` varchar(200) null,
  `empl_apellido` varchar(200) null,
  `empl_fecha_nacimiento` date null,
  `empl_direccion` varchar(200) null,
  `empl_telefono` varchar(20) null,
  `empl_telefono_movil` varchar(20) null,
  `empl_carga_familiar` int(2) null,
  `empl_genero` varchar(1) null,
  `empl_metodo_pago` varchar(3) null,
  `empl_cuenta_bancaria` varchar(45) null,
  `empl_cuenta_contable` varchar(15) null,
  -- `empl_tipo_contribuyente` varchar(15) null,
  `empl_fecha_ingreso` date null,
  `empl_fecha_salida` date null,
  `empl_fecha_seguro_social` date null,
  `empl_estado_civil` varchar(30) null,
  `empl_cuenta_catalogo` varchar(20) null,
  `empl_fondo_reserva` varchar(1) null,
  `empl_decimo_tercero` varchar(1) null,
  `empl_decimo_cuarto` varchar(1) null,
  `empl_paga_sobretiempo` varchar(1) null,
  `empl_ruta_foto` varchar(100) null,
  `empl_ruta_cedula` varchar(100) null,
  `empl_ruta_contrato` varchar(100) null,
  `empl_ruta_aviso_entrada` varchar(100) null,
  `empl_email_notificacion` varchar(100) null,
  `empl_porcentaje_discapacidad` varchar(3) null,
  `empl_usuario_ingreso` bigint(20) null,
  `empl_usuario_modifica` bigint(20) null,
  `empl_estado` varchar(1) null,
  `empl_fecha_creacion` timestamp null default current_timestamp,
  `empl_fecha_modificacion` timestamp null,
  `empl_estado_logico` varchar(1) null,
  foreign key (sdep_id) references `sub_departamento`(sdep_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `cargos`
--
DROP TABLE IF EXISTS `cargos`;
create table if not exists `cargos` (
  `carg_id` bigint(20) not null auto_increment primary key,
  `carg_nombre` varchar(200) null,
  `carg_sueldo` decimal(14,2) null,
  `carg_usuario_ingreso` bigint(20) null,
  `carg_usuario_modifica` bigint(20) null,
  `carg_estado` varchar(1) null,
  `carg_fecha_creacion` timestamp null default current_timestamp,
  `carg_fecha_modificacion` timestamp null,
  `carg_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empleado_cargo`
--
DROP TABLE IF EXISTS `empleado_cargo`;
create table if not exists `empleado_cargo` (
  `ecarg_id` bigint(20) not null auto_increment primary key,
  `empl_codigo` varchar(10) not null,
  `carg_id` bigint(20) not null,
  `sdep_id` bigint(20) not null,
  `ecarg_sueldo` decimal(14,2) null,
  `ecarg_fecha_inicio` date null,
  `ecarg_fecha_fin` date null,
  `ecarg_observacion` text null,
  `ecarg_usuario_ingreso` bigint(20) null,
  `ecarg_usuario_modifica` bigint(20) null,
  `ecarg_estado` varchar(1) null,
  `ecarg_fecha_creacion` timestamp null default current_timestamp,
  `ecarg_fecha_modificacion` timestamp null,
  `ecarg_estado_logico` varchar(1) null,
  foreign key (carg_id) references `cargos`(carg_id),
  foreign key (sdep_id) references `sub_departamento`(sdep_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `rubros`
--
DROP TABLE IF EXISTS `rubros`;
create table if not exists `rubros` (
  `rub_id` bigint(20) not null auto_increment primary key,
  `rub_nombre` varchar(200) null,
  `rub_tipo` varchar(1) null,
  `rub_cuenta_principal` varchar(20) null,
  `rub_cuenta_provisional` varchar(20) null,
  `rub_usuario_ingreso` bigint(20) null,
  `rub_usuario_modifica` bigint(20) null,
  `rub_estado` varchar(1) null,
  `rub_fecha_creacion` timestamp null default current_timestamp,
  `rub_fecha_modificacion` timestamp null,
  `rub_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `vacaciones`
--
DROP TABLE IF EXISTS `vacaciones`;
create table if not exists `vacaciones` (
  `vac_id` bigint(20) not null auto_increment primary key,
  `empl_codigo` varchar(10) not null,
  `vac_dias_solicitados` int(3) null,
  `vac_fecha_inicio` date null,
  `vac_fecha_fin` date null,
  `vac_fecha_retorno_labo` date null,
  `vac_observacion` text null,
  `vac_usuario_ingreso` bigint(20) null,
  `vac_usuario_modifica` bigint(20) null,
  `vac_estado` varchar(1) null,
  `vac_fecha_creacion` timestamp null default current_timestamp,
  `vac_fecha_modificacion` timestamp null,
  `vac_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `horas_extras`
--
DROP TABLE IF EXISTS `horas_extras`;
create table if not exists `horas_extras` (
  `hext_id` bigint(20) not null auto_increment primary key,
  `empl_codigo` varchar(10) not null,
  `hext_numero_50` int(4) null,
  `hext_numero_100` int(4) null,
  `hext_valor_50` decimal(10,2) null,
  `hext_valor_100` decimal(10,2) null,
  `hext_mes` int(2) null,
  `hext_anio` int(4) null,
  `hext_usuario_ingreso` bigint(20) null,
  `hext_usuario_modifica` bigint(20) null,
  `hext_estado` varchar(1) null,
  `hext_fecha_creacion` timestamp null default current_timestamp,
  `hext_fecha_modificacion` timestamp null,
  `hext_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_rol`
--
DROP TABLE IF EXISTS `tipo_rol`;
create table if not exists `tipo_rol` (
  `trol_id` bigint(20) not null auto_increment primary key,
  `trol_nombre` varchar(60) null,
  `trol_numero_horas` int(5) null,
  `trol_porcentaje` decimal(5,2) null,
  `trol_usuario_ingreso` bigint(20) null,
  `trol_usuario_modifica` bigint(20) null,
  `trol_estado` varchar(1) null,
  `trol_fecha_creacion` timestamp null default current_timestamp,
  `trol_fecha_modificacion` timestamp null,
  `trol_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `configuracion_rol`
--
DROP TABLE IF EXISTS `configuracion_rol`;
create table if not exists `configuracion_rol` (
  `crol_id` bigint(20) not null auto_increment primary key,
  -- `trol_id` bigint(20) not null,
  `crol_salario_minimo` decimal(12,2) null,
  `crol_porcentaje_aporte_patronal` decimal(5,2) null,
  `crol_aporte_mensual_quincena` varchar(1) null,
  `crol_porcentaje_iess` decimal(5,2) null,
  `crol_iess_mensual_quincena` varchar(1) null,
  `crol_horas_trabajo` int(5) null,
  `crol_paga_benenficios` varchar(1) null,
  `crol_transporte` decimal(12,2) null,
  `crol_transp_mensual_quincena` varchar(1) null,
  `crol_alimentacion` decimal(12,2) null,
  `crol_alimen_mensul_quincena` varchar(1) null,
  `crol_usuario_ingreso` bigint(20) null,
  `crol_usuario_modifica` bigint(20) null,
  `crol_estado` varchar(1) null,
  `crol_fecha_creacion` timestamp null default current_timestamp,
  `crol_fecha_modificacion` timestamp null,
  `crol_estado_logico` varchar(1) null
  -- foreign key (trol_id) references `tipo_rol`(trol_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `anticipos`
--
DROP TABLE IF EXISTS `anticipos`;
create table if not exists `anticipos` (
  `ant_id` bigint(20) not null auto_increment primary key,
  `cod_pto` varchar(3) not null,
  `empl_codigo` varchar(10) not null,
  `ant_valor_monto` decimal(12,2) null,
  `ant_valor_cancelado` decimal(12,2) null,
  `ant_observacion` text null,
  `ant_fecha_anticipo` date null,
  `ant_fecha_pago` timestamp null,
  `ant_estado_cancelado` varchar(1) null,
  `ant_usuario_ingreso` bigint(20) null,
  `ant_usuario_modifica` bigint(20) null,
  `ant_estado` varchar(1) null,
  `ant_fecha_creacion` timestamp null default current_timestamp,
  `ant_fecha_modificacion` timestamp null,
  `ant_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `prestamos`
--
DROP TABLE IF EXISTS `prestamos`;
create table if not exists `prestamos` (
  `pre_id` bigint(20) not null auto_increment primary key,
  `cod_pto` varchar(3) not null,
  `empl_codigo` varchar(10) not null,
  `pre_observacion` text null,
  `pre_plazo_meses` int(5) null,
  `pre_total_monto` decimal(12,2) null,
  `pre_total_cancelado` decimal(12,2) null,
  `pre_fecha_prestamo` date null,
  `pre_estado_cancelado` varchar(1) null,
  `pre_usuario_ingreso` bigint(20) null,
  `pre_usuario_modifica` bigint(20) null,
  `pre_estado` varchar(1) null,
  `pre_fecha_creacion` timestamp null default current_timestamp,
  `pre_fecha_modificacion` timestamp null,
  `pre_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `prestamos_cuotas`
--
DROP TABLE IF EXISTS `prestamos_cuotas`;
create table if not exists `prestamos_cuotas` (
  `pcuo_id` bigint(20) not null auto_increment primary key,
  `pre_id` bigint(20) not null,
  `pcuo_numero_cuota` int(5) null,
  `pcuo_valor_cuota` decimal(12,2) null,
  `pcuo_fecha_vencimiento` date null,
  `pcuo_fecha_pago` timestamp null,
  `pcuo_estado_cancelacion` varchar(1) null,
  `pcuo_usuario_ingreso` bigint(20) null,
  `pcuo_usuario_modifica` bigint(20) null,
  `pcuo_estado` varchar(1) null,
  `pcuo_fecha_creacion` timestamp null default current_timestamp,
  `pcuo_fecha_modificacion` timestamp null,
  `pcuo_estado_logico` varchar(1) null,
  foreign key (pre_id) references `prestamos`(pre_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `rol_pagos`
--
DROP TABLE IF EXISTS `rol_pagos`;
create table if not exists `rol_pagos` (
  `rpag_id` bigint(20) not null auto_increment primary key,
  `cod_pto` varchar(3) null,
  `empl_codigo` varchar(20) not null,
  `trol_id` bigint(20) not null,
  `rpag_mes` int(2) null,
  `rpag_anio` int(4) null,
  `rpag_dias_laborados` int(3) null,
  `rpag_sueldo_basico` decimal(12,2) null,
  `rpag_sueldo_real` decimal(12,2) null,
  `rpag_bonos_encargo` decimal(12,2) null,
  `rpag_alimentacion` decimal(12,2) null,
  `rpag_transporte` decimal(12,2) null,
  `rpag_nhoras_50` int(4) null,
  `rpag_sobret_50` decimal(12,2) null,
  `rpag_nhoras_100` int(4) null,
  `rpag_sobret_100` decimal(12,2) null,
  `rpag_otros_ingresos` decimal(12,2) null,
  `rpag_total_ingresos` decimal(12,2) null,
  `rpag_aporte_iess` decimal(12,2) null,
  `rpag_prest_quirografarios` decimal(12,2) null,
  `rpag_prest_hipotecarios` decimal(12,2) null,
  `rpag_otros_descuentos` decimal(12,2) null,
  `rpag_desc_prestamos` decimal(12,2) null,
  `rpag_desc_anticipos` decimal(12,2) null,
  `rpag_desc_catering` decimal(12,2) null,
  `rpag_desc_rincon_paisa` decimal(12,2) null,
  `rpag_desc_multas` decimal(12,2) null,
  `rpag_total_recibir` decimal(12,2) null,
  `rpag_valor_patronal` decimal(12,2) null,
  `rpag_valor_vacaciones` decimal(12,2) null,
  `rpag_fondos_reserva` decimal(12,2) null,
  `rpag_impuesto_rentas` decimal(12,2) null,
  `rpag_neto_recibir` decimal(12,2) null,
  `rpag_numero_cuenta_bancaria` varchar(20) null,
  `rpag_metodo_pago` varchar(3) null,
  `rpag_usuario_ingreso` bigint(20) null,
  `rpag_usuario_modifica` bigint(20) null,
  `rpag_estado` varchar(1) null,
  `rpag_fecha_creacion` timestamp null default current_timestamp,
  `rpag_fecha_modificacion` timestamp null,
  `rpag_estado_logico` varchar(1) null,
  foreign key (trol_id) references `tipo_rol`(trol_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_liquidacion`
--
DROP TABLE IF EXISTS `tipo_liquidacion`;
create table if not exists `tipo_liquidacion` (
  `tliq_id` bigint(20) not null auto_increment primary key,
  `tliq_nombre` varchar(200) null,
  `tliq_porcentaje` decimal(5,2) null,
  `tliq_usuario_ingreso` bigint(20) null,
  `tliq_usuario_modifica` bigint(20) null,
  `tliq_estado` varchar(1) null,
  `tliq_fecha_creacion` timestamp null default current_timestamp,
  `tliq_fecha_modificacion` timestamp null,
  `tliq_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `liquidaciones`
--
DROP TABLE IF EXISTS `liquidaciones`;
create table if not exists `liquidaciones` (
  `liq_id` bigint(20) not null auto_increment primary key,
  `empl_codigo` varchar(10) not null,
  `tliq_id` bigint(20) not null,
  `liq_fecha_liquidacion` date,
  `liq_anticipos` decimal(12,2) null,
  `liq_sueldos` decimal(12,2) null,
  `liq_desaucio` decimal(12,2) null,
  `liq_aporte_iess` decimal(12,2) null,
  `liq_meses` int(5) null,
  `liq_anios` int(5) null,
  `liq_vacaciones` decimal(12,2) null,
  `liq_decimo_tercero` decimal(12,2) null,
  `liq_decimo_cuarto` decimal(12,2) null,
  `liq_total` decimal(12,2) null,
  `liq_usuario_ingreso` bigint(20) null,
  `liq_usuario_modifica` bigint(20) null,
  `liq_estado` varchar(1) null,
  `liq_fecha_creacion` timestamp null default current_timestamp,
  `liq_fecha_modificacion` timestamp null,
  `liq_estado_logico` varchar(1) null,
  foreign key (tliq_id) references `tipo_liquidacion`(tliq_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_permiso`
--
DROP TABLE IF EXISTS `tipo_permiso`;
create table if not exists `tipo_permiso` (
  `tper_id` bigint(20) not null auto_increment primary key,
  `tper_nombre` varchar(200) null,
  `tper_usuario_ingreso` bigint(20) null,
  `tper_usuario_modifica` bigint(20) null,
  `tper_estado` varchar(1) null,
  `tper_fecha_creacion` timestamp null default current_timestamp,
  `tper_fecha_modificacion` timestamp null,
  `tper_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `permisos_licencias`
--
DROP TABLE IF EXISTS `permisos_licencias`;
create table if not exists `permisos_licencias` (
  `plic_id` bigint(20) not null auto_increment primary key,
  `empl_codigo` varchar(10) not null,
  `tper_id` bigint(20) not null,
  `plic_dias_solicitados` int(3) null,
  `plic_fecha_inicio` date null,
  `plic_fecha_fin` date null,
  `plic_fecha_retorno_labo` date null,
  `plic_num_horas` int(2) null,
  `plic_observacion` text null,
  `plic_carga_vacaciones` varchar(1) null,
  `plic_usuario_ingreso` bigint(20) null,
  `plic_usuario_modifica` bigint(20) null,
  `plic_estado` varchar(1) null,
  `plic_fecha_creacion` timestamp null default current_timestamp,
  `plic_fecha_modificacion` timestamp null,
  `plic_estado_logico` varchar(1) null,
  foreign key (tper_id) references `tipo_permiso`(tper_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `novedades_multas`
--
DROP TABLE IF EXISTS `novedades_multas`;
create table if not exists `novedades_multas` (
  `nmul_id` bigint(20) not null auto_increment primary key,
  `empl_codigo` varchar(20) not null,
  `nmul_observacion` text null,
  `nmul_valor` decimal(12,2) null,
  `nmul_fecha_pago` timestamp null,
  `nmul_estado_cancelado` varchar(1) null,
  `nmul_usuario_autoriza` bigint(20) null,
  `nmul_usuario_ingreso` bigint(20) null,
  `nmul_usuario_modifica` bigint(20) null,
  `nmul_estado` varchar(1) null,
  `nmul_fecha_creacion` timestamp null default current_timestamp,
  `nmul_fecha_modificacion` timestamp null,
  `nmul_estado_logico` varchar(1) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `rol_pagos_temp`
--
DROP TABLE IF EXISTS `rol_pagos_temp`;
create table if not exists `rol_pagos_temp` (
  `rpag_id` bigint(20) not null auto_increment primary key,
  `cod_pto` varchar(3) null,
  `empl_codigo` varchar(20) not null,
  `trol_id` bigint(20) not null,
  `rpag_mes` int(2) null,
  `rpag_anio` int(4) null,
  `rpag_dias_laborados` int(3) null,
  `rpag_sueldo_basico` decimal(12,2) null,
  `rpag_sueldo_real` decimal(12,2) null,
  `rpag_bonos_encargo` decimal(12,2) null,
  `rpag_alimentacion` decimal(12,2) null,
  `rpag_transporte` decimal(12,2) null,
  `rpag_nhoras_50` int(4) null,
  `rpag_sobret_50` decimal(12,2) null,
  `rpag_nhoras_100` int(4) null,
  `rpag_sobret_100` decimal(12,2) null,
  `rpag_otros_ingresos` decimal(12,2) null,
  `rpag_total_ingresos` decimal(12,2) null,
  `rpag_aporte_iess` decimal(12,2) null,
  `rpag_prest_quirografarios` decimal(12,2) null,
  `rpag_prest_hipotecarios` decimal(12,2) null,
  `rpag_otros_descuentos` decimal(12,2) null,
  `rpag_desc_prestamos` decimal(12,2) null,
  `rpag_desc_anticipos` decimal(12,2) null,
  `rpag_desc_catering` decimal(12,2) null,
  `rpag_desc_rincon_paisa` decimal(12,2) null,
  `rpag_desc_multas` decimal(12,2) null,
  `rpag_total_recibir` decimal(12,2) null,
  `rpag_valor_patronal` decimal(12,2) null,
  `rpag_valor_vacaciones` decimal(12,2) null,
  `rpag_fondos_reserva` decimal(12,2) null,
  `rpag_impuesto_rentas` decimal(12,2) null,
  `rpag_neto_recibir` decimal(12,2) null,
  `rpag_numero_cuenta_bancaria` varchar(20) null,
  `rpag_metodo_pago` varchar(3) null,
  `rpag_usuario_ingreso` bigint(20) null,
  `rpag_usuario_modifica` bigint(20) null,
  `rpag_estado` varchar(1) null,
  `rpag_fecha_creacion` timestamp null default current_timestamp,
  `rpag_fecha_modificacion` timestamp null,
  `rpag_estado_logico` varchar(1) null,
  foreign key (trol_id) references `tipo_rol`(trol_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `subcentro_empleado`
--
DROP TABLE IF EXISTS `subcentro_empleado`;
CREATE TABLE IF NOT EXISTS `subcentro_empleado` (
  `semp_id` BIGINT(20) not null auto_increment primary key,
  `empl_codigo` VARCHAR(20) NOT NULL,
  `cod_scen` VARCHAR(20) NOT NULL,
  `semp_porcentaje` DECIMAL(5,2) NULL,
  `estado` VARCHAR(1) NULL,
  `fecha_creacion` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` TIMESTAMP NULL,
  `estado_logico` VARCHAR(1) NULL,
    FOREIGN KEY (`empl_codigo`) REFERENCES `empleado` (`empl_codigo`),
    FOREIGN KEY (`cod_scen`) REFERENCES `COSSUBCEN` (`cod_scen`)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_temp`
--
DROP TABLE IF EXISTS `registro_temp`;
CREATE TABLE IF NOT EXISTS `registro_temp` (
  `NUM_REG` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `COD_TIP` varchar(2) DEFAULT NULL,
  `NUM_TRA` varchar(10) DEFAULT NULL,
  `COD_PTO` varchar(3) DEFAULT NULL,
  `COD_CEN` varchar(15) DEFAULT NULL,
  `COD_SUB` varchar(15) DEFAULT NULL,
  `FEC_TRA` date DEFAULT NULL,
  `COD_CTA` varchar(12) DEFAULT NULL,
  `NOM_CTA` varchar(120) DEFAULT NULL,
  `DET_TRA` varchar(93) DEFAULT NULL,
  `VAL_DEB` double(12,2) DEFAULT NULL,
  `VAL_HAB` double(12,2) DEFAULT NULL,
  `ESTATUS` varchar(1) DEFAULT NULL,
  `CED_RUC` varchar(13) DEFAULT NULL,
  `COD_AUX` varchar(12) DEFAULT NULL,
  `C_TRA_E` varchar(2) DEFAULT NULL,
  `NUM_DOC` varchar(10) DEFAULT NULL,
  `FEC_SIS` date NOT NULL,
  `HOR_SIS` varchar(10) NOT NULL,
  `USUARIO` varchar(15) NOT NULL,
  `EQUIPO` varchar(15) NOT NULL  
);


-- SET foreign_key_checks = 1;