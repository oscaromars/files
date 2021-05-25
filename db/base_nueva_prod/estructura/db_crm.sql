--
-- Base de datos: `db_crm`
--
DROP SCHEMA IF EXISTS `db_crm` ;
CREATE SCHEMA IF NOT EXISTS `db_crm` DEFAULT CHARACTER SET utf8 ;
USE `db_crm` ;

-- GRANT ALL PRIVILEGES ON `db_crm`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

create table if not exists `temporal_contactos` (
  `id` bigint(20) not null auto_increment primary key,
  `id_contacto` varchar(300) not null,
  `fecha_registro` varchar(300) not null,  
  `unidad_academica` varchar(300) not null,  
  `canal_contacto` varchar(300) not null,  
  `ultima_modalidad_interes` varchar(300) not null,  
  `ultima_carrera_interes` varchar(300) not null,  
  `medio_contacto_solicitado` varchar(300) not null,  
  `horario_contacto_solicitado` varchar(300) not null,  
  `nombre` varchar(300) not null,  
  `telefono` varchar(300) not null,  
  `telefono_ext` varchar(300) not null,  
  `correo` varchar(300) not null,  
  `ciudad` varchar(300) not null,  
  `pais` varchar(300) not null,  
  `ultimo_estado` varchar(300) not null,  
  `carrera_interes` varchar(300) not null,  
  `modalidad` varchar(300) not null,  
  `tipo_cliente` varchar(300) not null,  
  `agente_atencion` varchar(300) not null,  
  `fecha_atencion` varchar(300) not null,  
  `tipo_oportunidad` varchar(300) not null,  
  `estado_contacto` varchar(300) not null,  
  `estado_oportunidad` varchar(300) not null,  
  `fecha_siguiente_atencion` varchar(300) not null,  
  `hora_siguiente_atencion` varchar(300) not null,  
  `fecha_tentativa_pago` varchar(300) not null,
  `observacion` text null,
  `motivo_oportunidad_perdida` text null,
  `otra_universidad` varchar(300) not null,
  `tipo_observacion` varchar(300) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `tipo_oportunidad_venta`
--
create table if not exists `tipo_oportunidad_venta` (
 `tove_id` bigint(20) not null auto_increment primary key,
 `uaca_id` bigint(20) not null,
 `tove_nombre` varchar(300) not null,
 `tove_descripcion` varchar(500) not null,
 `tove_estado` varchar(1) not null,
 `tove_fecha_creacion` timestamp not null default current_timestamp,
 `tove_fecha_modificacion` timestamp null default null,
 `tove_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `tipo_carrera`
--
create table if not exists `tipo_carrera` (
 `tcar_id` bigint(20) not null auto_increment primary key,
 `tcar_nombre` varchar(250) default null,
 `tcar_descripcion` varchar(500) default null,
 `tcar_estado` varchar(1) not null,
 `tcar_fecha_creacion` timestamp not null default current_timestamp,
 `tcar_fecha_modificacion` timestamp null default null,
 `tcar_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `persona_temporal`
--
create table if not exists `persona_temporal` (
 `tper_id` int(11) not null auto_increment primary key,
 `tper_dni` varchar(100) default null,
 `tper_tipo_dni` varchar(100) default null,
 `tper_genero` varchar(100) default null,
 `tper_nombres` varchar(100) default null,
 `tper_apellidos` varchar(100) default null,
 `tper_correo` varchar(100) default null,
 `tper_direccion` varchar(100) default null,
 `tper_ciudad` varchar(100) default null,
 `tper_provincia` varchar(100) default null,
 `tper_pais` varchar(100) default null,
 `tper_telefono` varchar(100) default null,
 `tper_celular` varchar(100) default null,
 `tper_unidad_academica` varchar(1000) default null,
 `tper_modalidad` varchar(100) default null,
 `tper_carrera` varchar(100) default null,
 `tper_programa` varchar(1000) default null,
 `tper_curso` varchar(1000) default null,
 `tper_canal_contacto` varchar(100) default null,
 `tper_estado` varchar(100) default null,
 `tper_observacion` varchar(100) default null,
 `tper_observacion2` varchar(100) default null,
 `tper_estado_actualizado` varchar(100) default null,
 `tper_fecha_creacion` timestamp not null default current_timestamp,
 `tper_fecha_modificacion` timestamp null default null,
 `tper_estado_logico` varchar(1) default null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `conocimiento_canal`
--
create table if not exists `conocimiento_canal` (
 `ccan_id` bigint(20) not null auto_increment primary key,
 `ccan_nombre` varchar(300) not null,
 `ccan_descripcion` varchar(500) not null,
 `ccan_conocimiento` varchar(2) default null,
 `ccan_canal` varchar(2) default null,
 `ccan_estado` varchar(1) not null,
 `ccan_fecha_creacion` timestamp not null default current_timestamp,
 `ccan_fecha_modificacion` timestamp null default null,
 `ccan_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `oportunidad_perdida`
--
create table if not exists `oportunidad_perdida` (
 `oper_id` bigint(20) not null auto_increment primary key,
 `oper_nombre` varchar(300) not null,
 `oper_descripcion` varchar(500) not null,
 `oper_estado` varchar(1) not null,
 `oper_fecha_creacion` timestamp not null default current_timestamp,
 `oper_fecha_modificacion` timestamp null default null,
 `oper_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `otro_estudio_academico`
--
create table if not exists `otro_estudio_academico` (
 `oeac_id` bigint(20) not null auto_increment primary key,
 `oeac_nombre` varchar(300) not null,
 `oeac_descripcion` varchar(500) not null,
 `uaca_id` bigint(20) not null,
 `moda_id` bigint(20) not null,
 `oeac_estado` varchar(1) not null,
 `oeac_fecha_creacion` timestamp not null default current_timestamp,
 `oeac_fecha_modificacion` timestamp null default null,
 `oeac_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `estado_cliente`
--
create table if not exists `estado_contacto` (
 `econ_id` bigint(20) not null auto_increment primary key,
 `econ_nombre` varchar(300) not null,
 `econ_descripcion` varchar(500) not null,
 `econ_estado` varchar(1) not null,
 `econ_fecha_creacion` timestamp not null default current_timestamp,
 `econ_fecha_modificacion` timestamp null default null,
 `econ_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `conocimiento_servicio`
--
create table if not exists `conocimiento_servicio` (
 `cser_id` bigint(20) not null auto_increment primary key,
 `cser_nombre` varchar(300) not null, 
 `cser_descripcion` varchar(500) not null, 
 `cser_estado` varchar(1) not null,
 `cser_fecha_creacion` timestamp not null default current_timestamp,
 `cser_fecha_modificacion` timestamp null default null,
 `cser_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla `persona_gestion_tmp`
-- 
create table if not exists `persona_gestion_tmp`(
 `pgest_id` bigint(20) not null auto_increment primary key,
 `pgest_nombre` varchar(250) default null,
 `pgest_carr_nombre` varchar(100) default null, 
 `pgest_contacto` varchar(100) default null, 
 `pgest_horario` varchar(50) default null, 
 `pgest_modalidad` varchar(100) default null,
 `pgest_unidad_academica` varchar(100) default null,
 `pgest_numero` varchar(20) default null, 
 `pgest_correo` varchar(100) default null,
 `pgest_comentario` varchar(1000) default null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `estado_oportunidad`
--
create table if not exists `estado_oportunidad` (
 `eopo_id` bigint(20) not null auto_increment primary key,
 `eopo_nombre` varchar(300) not null,
 `eopo_descripcion` varchar(500) not null,
 `eopo_tipo` varchar(1) null,
 `eopo_estado` varchar(1) not null,
 `eopo_fecha_creacion` timestamp not null default current_timestamp,
 `eopo_fecha_modificacion` timestamp null default null,
 `eopo_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `persona_gestion`
--
create table if not exists `persona_gestion` (
 `pges_id` bigint(20) not null auto_increment primary key,
 `pges_codigo` varchar(07) null,
 `tper_id` bigint(20) not null,
 `cser_id` bigint(20) null,
 `car_id` bigint(20) null,
 `etn_id` bigint(20) default null,
 `eciv_id` bigint(20) default null,
 `pai_id_nacimiento` bigint(20) default null,
 `pro_id_nacimiento` bigint(20) default null,
 `can_id_nacimiento` bigint(20) default null,
 `tsan_id` bigint(20) default null,
 `pai_id_domicilio` bigint(20) default null,
 `pro_id_domicilio` bigint(20) default null,
 `can_id_domicilio` bigint(20) default null,
 `pai_id_trabajo` bigint(20) default null,
 `pro_id_trabajo` bigint(20) default null,
 `can_id_trabajo` bigint(20) default null,
 `econ_id` bigint(20) not null,
 `ccan_id` bigint(20) not null,
 `pges_pri_nombre` varchar(250) default null,
 `pges_seg_nombre` varchar(250) default null,
 `pges_pri_apellido` varchar(250) default null,
 `pges_seg_apellido` varchar(250) default null,
 `pges_razon_social` varchar(500) default null,
 `pges_cedula` varchar(15) default null,
 `pges_ruc` varchar(15) default null,
 `pges_pasaporte` varchar(50) default null,
 `pges_contacto_empresa` varchar(500) default null,
 `pges_num_empleados` varchar(5) default null,
 `pges_telefono_empresa` varchar(10) default null,
 `pges_direccion_empresa` varchar(500) default null,
 `pges_cargo` varchar(100) default null, 
 `pges_genero` varchar(1) default null,
 `pges_nacionalidad` varchar(250) default null, 
 `pges_nac_ecuatoriano` varchar(1) default null,
 `pges_fecha_nacimiento` date default null,
 `pges_celular` varchar(50) default null,
 `pges_correo` varchar(250) default null,
 `pges_foto` varchar(500) default null,
 `pges_domicilio_sector` varchar(250) default null,
 `pges_domicilio_cpri` varchar(500) default null,
 `pges_domicilio_csec` varchar(500) default null,
 `pges_domicilio_num` varchar(100) default null,
 `pges_domicilio_ref` varchar(500) default null,
 `pges_domicilio_telefono` varchar(50) default null,
 `pges_domicilio_celular2` varchar(50) default null,
 `pges_trabajo_nombre` varchar(250) default null,
 `pges_trabajo_direccion` varchar(500) default null,
 `pges_trabajo_telefono` varchar(50) default null,
 `pges_trabajo_ext` varchar(50) default null,
 `pges_estado_contacto` varchar(1) not null,
 `pges_usuario_ingreso` bigint(20) not null,
 `pges_usuario_modifica` bigint(20)  null, 
 `pges_estado` varchar(1) not null,
 `pges_fecha_creacion` timestamp not null default current_timestamp,
 `pges_fecha_modificacion` timestamp null default null,
 `pges_estado_logico` varchar(1) not null,
  foreign key (cser_id) references `conocimiento_servicio`(cser_id),
  foreign key (econ_id) references `estado_contacto`(econ_id),
  foreign key (ccan_id) references `conocimiento_canal`(ccan_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla `persona_gestion_contacto`
--
create table if not exists `persona_gestion_contacto` (
 `pgco_id` bigint(20) not null auto_increment primary key,
 `pges_id` bigint(20) not null,
 `pgco_primer_nombre` varchar(100) not null,
 `pgco_segundo_nombre` varchar(100) null,
 `pgco_primer_apellido` varchar(100) not null,
 `pgco_segundo_apellido` varchar(100) null,
 `pgco_correo` varchar(100) null,
 `pgco_telefono` varchar(50) null,
 `pgco_celular` varchar(50) null,
 `pai_id_contacto` bigint(20) not null,
 `pro_id_contacto` bigint(20) default null,
 `can_id_contacto` bigint(20) default null,
 `pgco_estado` varchar(1) not null,
 `pgco_fecha_creacion` timestamp not null default current_timestamp,
 `pgco_fecha_modificacion` timestamp null default null,
 `pgco_estado_logico` varchar(1) not null,
  foreign key (pges_id) references `persona_gestion`(pges_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla `tipo_sub_carrera`
--
create table if not exists `tipo_sub_carrera` (
 `tsca_id` bigint(20) not null auto_increment primary key,
 `tcar_id` bigint(20) not null,
 `tsca_nombre` varchar(250) default null,
 `tsca_descripcion` varchar(500) default null,
 `tsca_estado` varchar(1) not null,
 `tsca_fecha_creacion` timestamp not null default current_timestamp,
 `tsca_fecha_modificacion` timestamp null default null,
 `tsca_estado_logico` varchar(1) not null,
 foreign key (tcar_id) references `tipo_carrera`(tcar_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla `personal_admision`
--
create table if not exists `personal_admision` (
 `padm_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `grol_id` bigint(20) not null,
 `padm_codigo` varchar(10) default null,
 `padm_tipo_asignacion` varchar(1) not null,
 `padm_estado` varchar(1) not null,
 `padm_fecha_creacion` timestamp not null default current_timestamp,
 `padm_fecha_modificacion` timestamp null default null,
 `padm_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `personal_nivel_modalidad`
--
create table if not exists `personal_nivel_modalidad` (
 `pnmo_id` bigint(20) not null auto_increment primary key,
 `padm_id` bigint(20) not null,
 `uaca_id` bigint(20) null,
 `mod_id` bigint(20) null,
 `emp_id` bigint(20) not null,
 `pnmo_estado` varchar(1) not null,
 `pnmo_fecha_creacion` timestamp not null default current_timestamp,
 `pnmo_fecha_modificacion` timestamp null default null,
 `pnmo_estado_logico` varchar(1) not null,
 foreign key (padm_id) references `personal_admision`(padm_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla `oportunidad`
--
create table if not exists `oportunidad` (
 `opo_id` bigint(20) not null auto_increment primary key,
 `opo_codigo` varchar(250) default null,
 `emp_id` bigint(20) not null,
 `pges_id` bigint(20) not null, 
 `mest_id` bigint(20) null,
 `eaca_id` bigint(20) null,
 `uaca_id` bigint(20) not null,
 `mod_id` bigint(20) not null,
 `tove_id` bigint(20) null,
 `tsca_id` bigint(20) default null,
 `ccan_id` bigint(20) not null, 
 `oper_id` bigint(20) default null,
 `ins_id` bigint(20) default null,
 `padm_id` bigint(20) default null,
 `eopo_id` bigint(20) not null,
 `oeac_id` bigint(20) default null,
 `ccan_id_medio_contacto` bigint(20) default null,
 `opo_hora_ini_contacto` varchar(5) null default null,
 `opo_hora_fin_contacto` varchar(5) null default null,
 `opo_fecha_registro` timestamp null default null,
 `opo_estado_cierre` varchar(1) default null,
 `opo_fecha_ult_estado` timestamp null default null,
 `opo_usuario` bigint(20) not null,
 `opo_usuario_modif` bigint(20) default null,
 `opo_estado` varchar(1) not null,
 `opo_fecha_creacion` timestamp not null default current_timestamp,
 `opo_fecha_modificacion` timestamp null default null,
 `opo_estado_logico` varchar(1) not null, 
 foreign key (tsca_id) references `tipo_sub_carrera`(tsca_id),
 foreign key (ccan_id) references `conocimiento_canal`(ccan_id),
 foreign key (oper_id) references `oportunidad_perdida`(oper_id),
 foreign key (padm_id) references `personal_admision`(padm_id),
 foreign key (eopo_id) references `estado_oportunidad`(eopo_id),
 foreign key (pges_id) references `persona_gestion`(pges_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla `observacion_actividades`
--
create table if not exists `observacion_actividades` (
 `oact_id` bigint(20) not null auto_increment primary key,
 `oact_nombre` varchar(250) default null,
 `oact_descripcion` varchar(500) default null,
 `oact_estado` varchar(1) not null,
 `oact_usuario` bigint(20) not null,
 `oact_usuario_modif` bigint(20) default null,
 `oact_fecha_creacion` timestamp not null default current_timestamp,
 `oact_fecha_modificacion` timestamp null default null,
 `oact_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `bitacora_actividades`
--
create table if not exists `bitacora_actividades` (
 `bact_id` bigint(20) not null auto_increment primary key,
 `opo_id` bigint(20) not null,
 `usu_id` bigint(20) default null,
 `padm_id` bigint(20) default null,
 `eopo_id` bigint(20) not null,
 `oact_id` bigint(20) not null,
 `bact_fecha_registro` timestamp null default null,
 `bact_descripcion` varchar(1000) null,
 `bact_fecha_proxima_atencion` timestamp null default null, 
 `bact_estado` varchar(1) not null,
 `bact_usuario` bigint(20) not null,
 `bact_usuario_modif` bigint(20) default null,
 `bact_fecha_creacion` timestamp not null default current_timestamp,
 `bact_fecha_modificacion` timestamp null default null,
 `bact_estado_logico` varchar(1) not null,
 foreign key (opo_id) references `oportunidad`(opo_id),
 foreign key (eopo_id) references `estado_oportunidad`(eopo_id),
 foreign key (padm_id) references `personal_admision`(padm_id),
 foreign key (oact_id) references `observacion_actividades`(oact_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla `bitacora_actividades_tmp`
--
create table if not exists `bitacora_actividades_tmp` (
 `bact_id` bigint(20) not null auto_increment primary key,
 `opo_id` bigint(20) null,
 `usu_id` bigint(20) default null,
 `padm_id` bigint(20) default null,
 `eopo_id` bigint(20) null,
 `oact_id` bigint(20) null,
 `bact_fecha_registro` varchar(20) null,
 `bact_descripcion` varchar(1000) null,
 `bact_fecha_proxima_atencion` varchar(20) null,
 `oper_id` bigint(20) null
);

-- ---------------------------------------------------------
--
-- Estructura de tabla `bitacora_actividades_noprocesadas`
--
create table if not exists db_crm.`bitacora_actividades_noprocesadas` (
 `bano_id` bigint(20) not null auto_increment primary key,
 `bano_unidad` varchar(100) null,
 `bano_modalidad` varchar(100) null,
 `bano_carrera` varchar(500) null,
 `bano_nombre` varchar(200) null,
 `bano_telefono` varchar(50) null,
 `bano_correo` varchar(100) null,
 `bano_contacto` varchar(100) null,
 `eopo_id` bigint(20) null,
 `oact_id` bigint(20) null,
 `bano_fecha_registro` varchar(20) null,
 `bano_descripcion` varchar(1000) null,
 `bano_fecha_proxima_atencion` varchar(20) null,
 `oper_id` bigint(20) null,
 `usu_id` bigint(20) default null,
 `bano_tipoarchivo` varchar(1) null, /* '1': archivo gestiona actividades, '2': archivo crea contactos diferentes a leads */
 `bano_novedad` varchar(1000) null,
 `bano_fecha_creacion` timestamp not null default current_timestamp
);


-- --------------------------------------------------------
--
-- Estructura de tabla `bitacora_seguimiento`
--
create table if not exists `bitacora_seguimiento` (
 `bseg_id` bigint(20) not null auto_increment primary key,
 `bseg_nombre` varchar(300) not null, 
 `bseg_descripcion` varchar(500) not null, 
 `bseg_estado` varchar(1) not null,
 `bseg_fecha_creacion` timestamp not null default current_timestamp,
 `bseg_fecha_modificacion` timestamp null default null,
 `bseg_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `actividad_seguimiento`
--
create table if not exists `actividad_seguimiento` (
 `aseg_id` bigint(20) not null auto_increment primary key,
 `bseg_id` bigint(20) not null,
 `bact_id` bigint(20) not null,
 `aseg_estado` varchar(1) not null,
 `aseg_fecha_creacion` timestamp not null default current_timestamp,
 `aseg_fecha_modificacion` timestamp null default null,
 `aseg_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla `grupo_introductotio`
--
create table if not exists db_crm.`grupo_introductorio` (
 `gint_id` bigint(20) not null auto_increment primary key,
 `gint_nombre` varchar(250) default null,
 `gint_descripcion` varchar(500) default null,
 `gint_estado` varchar(1) not null,
 `gint_usuario` bigint(20) not null,
 `gint_usuario_modif` bigint(20) default null,
 `gint_fecha_creacion` timestamp not null default current_timestamp,
 `gint_fecha_modificacion` timestamp null default null,
 `gint_estado_logico` varchar(1) not null
);


-- ---------------------------------------------------------
--
-- Estructura de tabla `inscrito_maestria`
--
create table if not exists db_crm.`inscrito_maestria` (
 `imae_id` bigint(20) not null auto_increment primary key,
 `cemp_id` bigint(20) null, 
 `gint_id` bigint(20) not null,
 `pai_id` bigint(20) default null,
 `pro_id` bigint(20) default null,
 `can_id` bigint(20) default null,
 `uaca_id` bigint(20)  not null,
 `mod_id` bigint(20)  not null,
 `eaca_id` bigint(20)  not null, 
 `imae_tipo_documento` varchar(2) null, /* '1': cedula, '2': ruc, '3': pasaporte */
 `imae_documento` varchar(50) default null,
 `imae_primer_nombre` varchar(100) not null,
 `imae_segundo_nombre` varchar(100) null,
 `imae_primer_apellido` varchar(100) not null,
 `imae_segundo_apellido` varchar(100) null,  
 `imae_revisar_urgente` varchar(100) null,  
 `imae_cumple_requisito` varchar(2) null, /* '1': Si, '2': No */
 `imae_agente` bigint(20) null,
 `imae_fecha_inscripcion` varchar(20) null, 
 `imae_fecha_pago` varchar(20) null, 
 `imae_pago_inscripcion` double default null,    
 `imae_valor_maestria` double default null,    
 `fpag_id` bigint(20) null,
 `imae_estado_pago` varchar(2) null,  /* '1':Pagado, '2': No Pagado, '3': Pagado Totalidad Maestria */
 `imae_convenios` varchar(100) null, 
 `imae_matricula` varchar(15) null,
 `imae_titulo` varchar(500) null,
 `ins_id` bigint(20) null,
 `imae_correo` varchar(50) null,
 `imae_celular` varchar(50) null,
 `imae_convencional` varchar(50) null, 
 `imae_ocupacion` varchar(100) null, 
 `imae_usuario` bigint(20) not null,
 `imae_usuario_modif` bigint(20) default null,
 `imae_estado` varchar(1) not null,
 `imae_fecha_creacion` timestamp not null default current_timestamp,
 `imae_fecha_modificacion` timestamp null default null,
 `imae_estado_logico` varchar(1) not null, 
 
  foreign key (gint_id) references `grupo_introductorio`(gint_id)     
);
