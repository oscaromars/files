--
-- Base de datos: `db_asgard`
--
DROP SCHEMA IF EXISTS `db_asgard` ;
CREATE SCHEMA IF NOT EXISTS `db_asgard` DEFAULT CHARACTER SET utf8 ;
USE `db_asgard`;

-- GRANT ALL PRIVILEGES ON `db_asgard`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `accion`
--
create table if not exists `accion` (
 `acc_id` bigint(20) not null auto_increment primary key,
 `acc_nombre` varchar(250) default null,
 `acc_url_accion` varchar(250) default null,
 `acc_tipo` varchar(250) default null,
 `acc_descripcion` varchar(500) default null,
 `acc_lang_file` varchar(250) default null,
 `acc_dir_imagen` varchar(250) default null,
 `acc_estado` varchar(1) not null,
 `acc_fecha_creacion` timestamp not null default current_timestamp,
 `acc_fecha_modificacion` timestamp null default null,
 `acc_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_parentesco`
--
create table if not exists `tipo_parentesco` (
 `tpar_id` bigint(20) not null auto_increment primary key,
 `tpar_nombre` varchar(200) default null,
 `tpar_descripcion` varchar(500) default null,
 `tpar_grado` varchar(1) default null,
 `tpar_observacion` varchar(500) default null,
 `tpar_estado` varchar(1) not null,
 `tpar_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `tpar_fecha_actualizacion` timestamp null default null,
 `tpar_estado_logico` varchar(1) not null
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_password`
--
create table if not exists `tipo_password` (
 `tpas_id` bigint(20) not null auto_increment primary key,
 `tpas_descripcion` varchar(500) default null,
 `tpas_validacion` varchar(200) default null,
 `tpas_observacion` varchar(500) default null,
 `tpas_estado` varchar(1) not null,
 `tpas_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `tpas_fecha_actualizacion` timestamp null default null,
 `tpas_estado_logico` varchar(1) not null
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_persona`
--
create table if not exists `tipo_persona` (
 `tper_id` bigint(20) not null auto_increment primary key,
 `tper_nombre` varchar(250) default null,
 `tper_estado` varchar(1) not null,
 `tper_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `tper_fecha_modificacion` timestamp null default null,
 `tper_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_sangre`
--
create table if not exists `tipo_sangre` (
 `tsan_id` bigint(20) not null auto_increment primary key,
 `tsan_nombre` varchar(250) default null,
 `tsan_descripcion` varchar(250) default null,
 `tsan_observacion` varchar(500) default null,
 `tsan_estado` varchar(1) not null,
 `tsan_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `tsan_fecha_actualizacion` timestamp null default null,
 `tsan_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `etnia`
--
create table if not exists `etnia` (
 `etn_id` bigint(20) not null auto_increment primary key,
 `etn_nombre` varchar(250) default null,
 `etn_descripcion` varchar(250) default null,
 `etn_observacion` varchar(500) default null,
 `etn_estado` varchar(1) not null,
 `etn_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `etn_fecha_actualizacion` timestamp null default null,
 `etn_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `estado_civil`
--
create table if not exists `estado_civil` (
 `eciv_id` bigint(20) not null auto_increment primary key,
 `eciv_nombre` varchar(250) default null,
 `eciv_descripcion` varchar(250) default null,
 `eciv_observacion` varchar(500) default null,
 `eciv_estado` varchar(1) not null,
 `eciv_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `eciv_fecha_actualizacion` timestamp null default null,
 `eciv_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `aplicacion`
--
create table if not exists `aplicacion` (
 `apl_id` bigint(20) not null auto_increment primary key,
 `apl_nombre` varchar(250) default null,
 `apl_tipo` varchar(100) default null,
 `apl_estado` varchar(1) not null,
 `apl_fecha_creacion` timestamp not null default current_timestamp,
 `apl_fecha_modificacion` timestamp null default null,
 `apl_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `continente`
--
create table if not exists `continente` (
  `cont_id` bigint(20) not null auto_increment primary key, 
  `cont_nombre` varchar(300) not null,
  `cont_descripcion` varchar(500) not null,
  `cont_usuario_ingreso` bigint(20) not null,
  `cont_usuario_modifica` bigint(20)  null,    
  `cont_estado` varchar(1) not null,
  `cont_fecha_creacion` timestamp not null default current_timestamp,
  `cont_fecha_modificacion` timestamp null default null,
  `cont_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `pais`
--
create table if not exists `pais` (
 `pai_id` bigint(20) not null auto_increment primary key,
 `cont_id` bigint(20) null,
 `pai_nombre` varchar(250) default null,
 `pai_capital` varchar(250) default null,
 `pai_iso2` varchar(2) default null,
 `pai_iso3` varchar(3) default null,
 `pai_codigo_fono` varchar(10) default null,
 `pai_descripcion` varchar(500) default null,
 `pai_nacionalidad` varchar(200) default null,
 `pai_usuario_ingreso` bigint(20) null,
 `pai_usuario_modifica` bigint(20) null,    
 `pai_estado` varchar(1) not null,
 `pai_fecha_creacion` timestamp not null default current_timestamp,
 `pai_fecha_modificacion` timestamp null default null,
 `pai_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `provincia`
--
create table if not exists `provincia` (
 `pro_id` bigint(20) not null auto_increment primary key,
 `pai_id` bigint(20) not null,
 `pro_nombre` varchar(250) default null,
 `pro_capital` varchar(250) default null,
 `pro_descripcion` varchar(500) default null,
 `pro_estado` varchar(1) not null,
 `pro_fecha_creacion` timestamp not null default current_timestamp,
 `pro_fecha_modificacion` timestamp null default null,
 `pro_estado_logico` varchar(1) not null,
 foreign key (pai_id) references `pais`(pai_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `canton`
--
create table if not exists `canton` (
 `can_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `can_nombre` varchar(250) default null,
 `can_descripcion` varchar(500) default null,
 `can_estado` varchar(1) not null,
 `can_fecha_creacion` timestamp not null default current_timestamp,
 `can_fecha_modificacion` timestamp null default null,
 `can_estado_logico` varchar(1) not null,
 foreign key (pro_id) references `provincia`(pro_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `persona`
--
create table if not exists `persona` (
 `per_id` bigint(20) not null auto_increment primary key,
 `per_pri_nombre` varchar(250) default null,
 `per_seg_nombre` varchar(250) default null,
 `per_pri_apellido` varchar(250) default null, 
 `per_seg_apellido` varchar(250) default null,
 `per_cedula` varchar(15) not null,
 `per_ruc` varchar(15) default null,
 `per_pasaporte` varchar(50) default null,
 `etn_id` bigint(20) default null,
 `eciv_id` bigint(20) default null,
 `per_genero` varchar(1) default null,
 `per_nacionalidad` varchar(250) default null,
 `pai_id_nacimiento` bigint(20) default null,
 `pro_id_nacimiento` bigint(20) default null,
 `can_id_nacimiento` bigint(20) default null,
 `per_nac_ecuatoriano` varchar(1) default null,
 `per_fecha_nacimiento` date default null,
 `per_celular` varchar(50) default null,
 `per_correo` varchar(250) default null,
 `per_foto` varchar(500) default null,
 `tsan_id` bigint(20) default null,

 `per_domicilio_sector` varchar(250) default null,
 `per_domicilio_cpri` varchar(500) default null,
 `per_domicilio_csec` varchar(500) default null,
 `per_domicilio_num` varchar(100) default null,
 `per_domicilio_ref` varchar(500) default null,
 `per_domicilio_telefono` varchar(50) default null,
 `per_domicilio_celular2` varchar(50) default null,
 `pai_id_domicilio` bigint(20) default null, 
 `pro_id_domicilio` bigint(20) default null, 
 `can_id_domicilio` bigint(20) default null,
  
 `per_trabajo_nombre` varchar(250) default null,
 `per_trabajo_direccion` varchar(500) default null,
 `per_trabajo_telefono` varchar(50) default null, 
 `per_trabajo_ext` varchar(50) default null,
 `pai_id_trabajo` bigint(20) default null, 
 `pro_id_trabajo` bigint(20) default null,
 `can_id_trabajo` bigint(20) default null,

 `per_usuario_ingresa` bigint(20) default null,
 `per_usuario_modifica` bigint(20) default null,
 `per_estado` varchar(1) not null,
 `per_fecha_creacion` timestamp not null default current_timestamp,
 `per_fecha_modificacion` timestamp null default null,
 `per_estado_logico` varchar(1) not null,
 foreign key (pai_id_nacimiento) references `pais`(pai_id), 
 foreign key (pro_id_nacimiento) references `provincia`(pro_id),

 foreign key (pai_id_domicilio) references `pais`(pai_id),
 foreign key (pro_id_domicilio) references `provincia`(pro_id), 
 foreign key (can_id_domicilio) references `canton`(can_id), 

 foreign key (pai_id_trabajo) references `pais`(pai_id),
 foreign key (pro_id_trabajo) references `provincia`(pro_id), 
 foreign key (can_id_trabajo) references `canton`(can_id), 

 foreign key (etn_id) references `etnia`(etn_id), 
 foreign key (tsan_id) references `tipo_sangre`(tsan_id),
 unique per_cedula(per_cedula) 
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `persona_contacto`
--
create table if not exists `persona_contacto` (
 `pcon_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) default null, 
 `pcon_nombre` varchar(250) default null,
 `tpar_id` bigint(20) default null,
 `pcon_direccion` varchar(500) default null,
 `pcon_telefono` varchar(50) default null,
 `pcon_celular` varchar(50) default null,
 `pcon_estado` varchar(1) not null,
 `pcon_fecha_creacion` timestamp not null default current_timestamp,
 `pcon_fecha_modificacion` timestamp null default null,
 `pcon_estado_logico` varchar(1) not null, 
 foreign key (per_id) references `persona`(per_id),
 foreign key (tpar_id) references `tipo_parentesco`(tpar_id) 
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_empresa`
--
create table if not exists `tipo_empresa` (
 `temp_id` bigint(20) not null auto_increment primary key,
 `temp_nombre` varchar(250) default null,
 `temp_descripcion` varchar(500) default null,
 `temp_estado` varchar(1) not null,
 `temp_fecha_creacion` timestamp not null default current_timestamp,
 `temp_fecha_modificacion` timestamp null default null,
 `temp_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empresa`
--
CREATE TABLE IF NOT EXISTS `empresa` (
  `emp_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `temp_id` bigint(20) NOT NULL,
  `emp_razon_social` varchar(200) DEFAULT NULL,
  `emp_nombre_comercial` varchar(200) NOT NULL,
  `emp_alias` varchar(200) DEFAULT NULL,
  `emp_ruc` varchar(20) DEFAULT NULL,
  `emp_dominio` varchar(100) DEFAULT NULL,
  `emp_imap_domain` varchar(200) DEFAULT NULL,
  `emp_imap_port` varchar(20) DEFAULT NULL,
  `emp_imap_user` varchar(100) DEFAULT NULL,
  `emp_imap_pass` varchar(200) DEFAULT NULL,
  `pai_id` bigint(20) NULL,
  `pro_id` bigint(20) NULL,
  `can_id` bigint(20) NULL,
  `emp_direccion` varchar(45) DEFAULT NULL,
  `emp_direccion1` varchar(100) NULL,
  `emp_telefono` varchar(50) DEFAULT NULL,
  `emp_codigo_postal` varchar(10) NULL,
  `emp_descripcion` varchar(50) DEFAULT NULL,
  `emp_estado` varchar(1) DEFAULT NULL,
  `emp_fecha_creacion` timestamp NULL DEFAULT NULL,
  `emp_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `emp_estado_logico` varchar(1) DEFAULT NULL,
  FOREIGN KEY (temp_id) REFERENCES `tipo_empresa`(temp_id)
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empresa_persona`
--
CREATE TABLE IF NOT EXISTS `empresa_persona` (
  `eper_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_id` bigint(20) NOT NULL,
  `per_id` bigint(20) NOT NULL,
  `eper_estado` varchar(1) NOT NULL,
  `eper_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `eper_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `eper_estado_logico` varchar(1) NOT NULL,
  FOREIGN KEY (emp_id) REFERENCES `empresa`(emp_id)
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuario`
--
create table if not exists `usuario` (
 `usu_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `usu_user` varchar(250) default null,
 `usu_sha` text,
 `usu_password` varchar(500) default null,
 `usu_time_pass` timestamp null default null,
 `usu_session` text,
 `usu_last_login` timestamp null default null,
 `usu_link_activo` text default null,
 `usu_upreg` varchar(1) not null default 1, -- campo de validacion para cambio de contrasena  0=> oblicacion cambio de clave 1=> no se requiere cambio de clave
 `usu_estado` varchar(1) not null,
 `usu_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `usu_fecha_modificacion` timestamp null default null,
 `usu_estado_logico` varchar(1) not null,
 foreign key (per_id) references `persona`(per_id)
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuario_correo`
--
create table if not exists `usuario_correo` (
 `ucor_id` bigint(20) not null auto_increment primary key,
 `usu_id` bigint(20) not null,
 `ucor_user` varchar(250) default null,
 `ucor_password` varchar(500) default null,
 `ucor_time_pass` timestamp null default null,
 `ucor_sha` text,
 `ucor_session` text,
 `ucor_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `ucor_fecha_modificacion` timestamp null default null,
 `ucor_estado_logico` varchar(1) not null,
 foreign key (usu_id) references `usuario`(usu_id)
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `idioma`
--
create table if not exists `idioma` (
 `idi_id` bigint(20) not null auto_increment primary key,
 `idi_nombre` varchar(250) default null,
 `idi_tipo` varchar(250) default null,
 `idi_estado` varchar(1) not null,
 `idi_fecha_creacion` timestamp not null default current_timestamp,
 `idi_fecha_actualizacion` timestamp null default null,
 `idi_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `plantilla`
--
create table if not exists `plantilla` (
 `pla_id` bigint(20) not null auto_increment primary key,
 `pla_nombre` varchar(250) default null,
 `pla_tipo` varchar(250) default null,
 `pla_estado` varchar(1) not null,
 `pla_fecha_creacion` timestamp not null default current_timestamp,
 `pla_fecha_actualizacion` timestamp null default null,
 `pla_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `configuracion_cuenta`
--
create table if not exists `configuracion_cuenta` (
 `ccue_id` bigint(20) not null auto_increment primary key,
 `usu_id` bigint(20) not null,
 `idi_id` bigint(20) not null,
 `pla_id` bigint(20) not null,
 `ccue_descripcion` varchar(500) default null,
 `ccue_observacion` varchar(500) default null,
 `ccue_fecha_creacion` timestamp not null default current_timestamp,
 `ccue_fecha_modificacion` timestamp null default null,
 `ccue_estado_logico` varchar(1) not null,
 foreign key (usu_id) references `usuario`(usu_id),
 foreign key (idi_id) references `idioma`(idi_id),
 foreign key (pla_id) references `plantilla`(pla_id) 
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `configuracion_seguridad`
--
create table if not exists `configuracion_seguridad` (
 `cseg_id` bigint(20) not null auto_increment primary key,
 `tpas_id` bigint(20) not null,
 `cseg_long_pass` varchar(250) default null,
 `cseg_expiracion` int(20) default null,
 `cseg_descripcion` varchar(500) default null,
 `cseg_observacion` varchar(500) default null,
 `cseg_estado` varchar(1) not null,
 `cseg_fecha_creacion` timestamp not null default current_timestamp,
 `cseg_fecha_actualizacion` timestamp null default null,
 `cseg_estado_logico` varchar(1) not null,
 foreign key (tpas_id) references `tipo_password`(tpas_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `modulo`
--
create table if not exists `modulo` (
 `mod_id` bigint(20) not null auto_increment primary key,
 `apl_id` bigint(20) not null,
 `mod_nombre` varchar(250) default null,
 `mod_tipo` varchar(250) default null,
 `mod_dir_imagen` varchar(250) default null,
 `mod_url` varchar(250) default null,
 `mod_orden` bigint(2) default null,
 `mod_lang_file` varchar(250) default null,
 `mod_estado_visible` varchar(1) not null default '1',
 `mod_estado` varchar(1) not null,
 `mod_fecha_creacion` timestamp not null default current_timestamp,
 `mod_fecha_actualizacion` timestamp null default null,
 `mod_estado_logico` varchar(1) not null,
 foreign key (apl_id) references `aplicacion`(apl_id) 
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `objeto_modulo`
--
create table if not exists `objeto_modulo` (
 `omod_id` bigint(20) not null auto_increment primary key,
 `mod_id` bigint(20) not null,
 `omod_padre_id` bigint(20) default null,
 `omod_nombre` varchar(250) default null,
 `omod_tipo` varchar(250) default null,
 `omod_tipo_boton` varchar(1) default null,
 `omod_accion` varchar(250) default null,
 `omod_function` varchar(250) default null,
 `omod_dir_imagen` varchar(250) default null,
 `omod_entidad` varchar(250) default null,
 `omod_orden` bigint(2) default null,
 `omod_estado_visible` varchar(1) default null,
 `omod_lang_file` varchar(250) default null,
 `omod_estado` varchar(1) not null,
 `omod_fecha_creacion` timestamp not null default current_timestamp,
 `omod_fecha_actualizacion` timestamp null default null,
 `omod_estado_logico` varchar(1) not null,
 foreign key (mod_id) references `modulo`(mod_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `grupo`
--
create table if not exists `grupo` (
 `gru_id` bigint(20) not null auto_increment primary key,
 `cseg_id` bigint(20) NOT NULL,
 `gru_nombre` varchar(250) default null,
 `gru_descripcion` varchar(500) default null,
 `gru_observacion` varchar(500) default null,
 `gru_estado` varchar(1) not null,
 `gru_fecha_creacion` timestamp not null default current_timestamp,
 `gru_fecha_actualizacion` timestamp null default null,
 `gru_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `rol`
--
create table if not exists `rol` (
 `rol_id` bigint(20) not null auto_increment primary key,
 `rol_nombre` varchar(250) default null,
 `rol_descripcion` varchar(500) default null,
 `rol_estado` varchar(1) not null,
 `rol_fecha_creacion` timestamp not null default current_timestamp,
 `rol_fecha_actualizacion` timestamp null default null,
 `rol_estado_logico` varchar(1) not null
);
-- -------------------------------------------------------
--
-- Estructura de tabla para la tabla `grup_rol`
--
create table if not exists `grup_rol` (
 `grol_id` bigint(20) not null auto_increment primary key,
 `eper_id` bigint(20) null default null,
 `gru_id` bigint(20) not null,
 `rol_id` bigint(20) not null,
 `grol_estado` varchar(1) not null,
 `grol_fecha_creacion` timestamp not null default current_timestamp,
 `grol_fecha_modificacion` timestamp null default null,
 `grol_estado_logico` varchar(1) not null,
 foreign key (gru_id) references `grupo`(gru_id),
 foreign key (rol_id) references `rol`(rol_id),
 foreign key (eper_id) references `empresa_persona`(eper_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `grup_obmo`
--
create table if not exists `grup_obmo` (
 `gmod_id` bigint(20) not null auto_increment primary key,
 `gru_id` bigint(20) not null,
 `omod_id` bigint(20) not null,
 `gmod_estado` varchar(1) not null,
 `gmod_fecha_creacion` timestamp not null default current_timestamp,
 `gmod_fecha_modificacion` timestamp null default null,
 `gmod_estado_logico` varchar(1) not null,
 foreign key (gru_id) references `grupo`(gru_id),
 foreign key (omod_id) references `objeto_modulo`(omod_id) 
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `grup_obmo_grup_rol`
--
create table if not exists `grup_obmo_grup_rol` (
 `gogr_id` bigint(20) not null auto_increment primary key,
 `grol_id` bigint(20) not null,
 `gmod_id` bigint(20) not null,
 `gogr_estado` varchar(1) not null,
 `gogr_fecha_creacion` timestamp not null default current_timestamp,
 `gogr_fecha_modificacion` timestamp null default null,
 `gogr_estado_logico` varchar(1) not null,
 foreign key (grol_id) references `grup_rol`(grol_id),
 foreign key (gmod_id) references `grup_obmo`(gmod_id) 

);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `obmo_acci`
--
create table if not exists `obmo_acci` (
 `oacc_id` bigint(20) not null auto_increment primary key,
 `omod_id` bigint(20) not null,
 `acc_id` bigint(20) not null,
 `oacc_tipo_boton` varchar(1) default null,
 `oacc_cont_accion` varchar(250) default null,
 `oacc_function` varchar(250) default null,
`oacc_estado` varchar(1) not null,
 `oacc_fecha_creacion` timestamp not null default current_timestamp,
 `oacc_fecha_modificacion` timestamp null default null,
 `oacc_estado_logico` varchar(1) not null,
 foreign key (omod_id) references `objeto_modulo`(omod_id),
 foreign key (acc_id) references `accion`(acc_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `grup_obmo_grup_rol_obmo_acci`
--
create table if not exists `grup_obmo_grup_rol_obmo_acci` (
 `ggoa_id` bigint(20) not null auto_increment primary key,
 `gogr_id` bigint(20) not null,
 `oacc_id` bigint(20) not null,
 `ggoa_estado` varchar(1) not null,
 `ggoa_fecha_creacion` timestamp not null default current_timestamp,
 `ggoa_fecha_modificacion` timestamp null default null,
 `ggoa_estado_logico` varchar(1) not null,
 foreign key (gogr_id) references `grup_obmo_grup_rol`(gogr_id),
 foreign key (oacc_id) references `obmo_acci`(oacc_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_operacion`
--
create table if not exists `registro_operacion` (
 `rope_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `usu_id` bigint(20) not null,
 `apl_id` bigint(20) not null,
 `acc_id` bigint(20) not null,
 `mod_id` bigint(20) not null,
 `omod_id` bigint(20) not null,
 `rope_persona_name` varchar(250) default null,
 `rope_aplicacion` varchar(20) default null,
 `rope_rol` varchar(250) default null,
 `rope_grupo` varchar(250) default null,
 `rope_modulo` varchar(500) default null,
 `rope_obj_modulo` varchar(500) default null,
 `rope_ip_terminal` varchar(500) default null,
 `rope_fecha_ingreso` timestamp null default null,
 `rope_fecha_salida` timestamp null default null,
 `rope_data_enviada` text null default null,
 `rope_data_recibida` text null default null,
 `rope_url` text null default null,
 `rope_estado` varchar(1) not null,
 `rope_fecha_creacion` timestamp not null default current_timestamp,
 `rope_fecha_modificacion` timestamp null default null,
 `rope_estado_logico` varchar(1) not null
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usua_grol_eper`
--
CREATE TABLE IF NOT EXISTS `usua_grol_eper` (
  `ugep_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `eper_id` bigint(20) NULL DEFAULT NULL,
  `usu_id` bigint(20) NOT NULL,
  `grol_id` bigint(20) NOT NULL,
  `ugep_estado` varchar(1) NOT NULL,
  `ugep_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ugep_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ugep_estado_logico` varchar(1) NOT NULL,
  FOREIGN KEY (eper_id) REFERENCES `empresa_persona`(eper_id),
  FOREIGN KEY (usu_id) REFERENCES `usuario`(usu_id),
  FOREIGN KEY (grol_id) REFERENCES `grup_rol`(grol_id)
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `user_passreset`
--
create table if not exists `user_passreset` (
`upas_id` bigint(20) not null primary key auto_increment,
`usu_id` bigint(20) not null,
`upas_token` varchar(500) collate utf8_unicode_ci default null,
`upas_remote_ip_inactivo` varchar(20) collate utf8_unicode_ci default null,
`upas_remote_ip_activo` varchar(20) collate utf8_unicode_ci default null,
`upas_link` varchar(500) collate utf8_unicode_ci default null,
`upas_fecha_inicio` timestamp null default null,
`upas_fecha_fin` timestamp null default null,
`upas_estado_activo` varchar(1) not null,
`upas_fecha_creacion` timestamp null default null,
`upas_fecha_modificacion` timestamp null default null,
`upas_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_discapacidad`
--
create table if not exists `tipo_discapacidad` (
 `tdis_id` bigint(20) not null auto_increment primary key,
 `tdis_nombre` varchar(250) default null,
 `tdis_descripcion` varchar(500) default null,
 `tdis_estado` varchar(1) not null,
 `tdis_fecha_creacion` timestamp not null default current_timestamp,
 `tdis_fecha_modificacion` timestamp null default null,
 `tdis_estado_logico` varchar(1) not null
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_identificacion`
--
create table if not exists `tipo_identificacion` (
  `tide_id` bigint(20) not null auto_increment primary key,
  `tide_nombre` varchar(300) not null,  
  `tide_descripcion` varchar(500) not null, 
  `tide_numero_caracteres` varchar(3) null,   
  `tide_estado` varchar(1) not null,
  `tide_fecha_creacion` timestamp not null default current_timestamp,
  `tide_fecha_modificacion` timestamp null default null,
  `tide_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `otra_etnia`
--
create table if not exists `otra_etnia` (
 `oetn_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `oetn_nombre` varchar(250) default null,
 `oetn_estado` varchar(1) not null,
 `oetn_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `oetn_fecha_modificacion` timestamp null default null,
 `oetn_estado_logico` varchar(1) not null
) ;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `dash`
--
create table if not exists `dash` (
  `dash_id` bigint(20) not null auto_increment primary key,
  `dash_title` varchar(300) not null,  
  `dash_detail` varchar(500) not null, 
  `dash_link` varchar(250) null,  
  `dash_target` varchar(250) null, 
  `dash_estado` varchar(1) not null,
  `dash_fecha_creacion` timestamp not null default current_timestamp,
  `dash_fecha_modificacion` timestamp null default null,
  `dash_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `dash_item`
--
create table if not exists `dash_item` (
  `dite_id` bigint(20) not null auto_increment primary key,
  `dash_id` bigint(20) not null,
  `dite_title` varchar(300) not null,  
  `dite_detail` varchar(500) not null, 
  `dite_link` varchar(250) null,  
  `dite_target` varchar(250) null, 
  `dite_estado` varchar(1) not null,
  `dite_fecha_creacion` timestamp not null default current_timestamp,
  `dite_fecha_modificacion` timestamp null default null,
  `dite_estado_logico` varchar(1) not null,
  FOREIGN KEY (dash_id) REFERENCES `dash`(dash_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empresa_correo`
--
CREATE TABLE IF NOT EXISTS `empresa_correo` (
  `ecor_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_id` bigint(20) NOT NULL,
  `ecor_correo` varchar(50) NOT NULL,
  `ecor_estado` varchar(1) DEFAULT NULL,
  `ecor_fecha_creacion` timestamp NULL DEFAULT NULL,
  `ecor_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ecor_estado_logico` varchar(1) DEFAULT NULL,
  FOREIGN KEY (emp_id) REFERENCES `empresa`(emp_id)
) ;