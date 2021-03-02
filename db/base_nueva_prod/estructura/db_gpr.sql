-- 
-- Base de datos: `db_gpr`
-- 

DROP SCHEMA IF EXISTS `db_gpr`;
CREATE SCHEMA IF NOT EXISTS `db_gpr` DEFAULT CHARACTER SET utf8 ;
USE `db_gpr` ;

-- GRANT ALL PRIVILEGES ON `db_gpr`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `umbral`
-- 
create table if not exists `umbral` (
  `umb_id` bigint(20) not null auto_increment primary key,
  `umb_nombre` varchar(300) not null,
  `umb_descripcion` varchar(500) not null,
  `umb_color` varchar(10) not null,
  `umb_per_inicio` varchar(10) not null,
  `umb_per_fin` varchar(10) not null,
  `umb_usuario_ingreso` bigint(20) not null,
  `umb_usuario_modifica` bigint(20)  null,  
  `umb_estado` varchar(1) not null,
  `umb_fecha_creacion` timestamp not null default current_timestamp,
  `umb_fecha_modificacion` timestamp null default null,
  `umb_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `tipo_configuracion`
-- 
create table if not exists `tipo_configuracion` (
  `tcon_id` bigint(20) not null auto_increment primary key,
  `tcon_nombre` varchar(300) not null,
  `tcon_descripcion` varchar(500) not null,
  `tcon_usuario_ingreso` bigint(20) not null,
  `tcon_usuario_modifica` bigint(20)  null,  
  `tcon_estado` varchar(1) not null,
  `tcon_fecha_creacion` timestamp not null default current_timestamp,
  `tcon_fecha_modificacion` timestamp null default null,
  `tcon_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `categoria_bsc`
-- 
create table if not exists `categoria_bsc` (
  `cbsc_id` bigint(20) not null auto_increment primary key,
  `cbsc_nombre` varchar(300) not null,
  `cbsc_descripcion` varchar(500) not null,
  `cbsc_usuario_ingreso` bigint(20) not null,
  `cbsc_usuario_modifica` bigint(20)  null,  
  `cbsc_estado` varchar(1) not null,
  `cbsc_fecha_creacion` timestamp not null default current_timestamp,
  `cbsc_fecha_modificacion` timestamp null default null,
  `cbsc_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `enfoque`
-- 
create table if not exists `enfoque` (
  `enf_id` bigint(20) not null auto_increment primary key,
  `enf_nombre` varchar(300) not null,
  `enf_descripcion` varchar(500) not null,
  `enf_usuario_ingreso` bigint(20) not null,
  `enf_usuario_modifica` bigint(20)  null,  
  `enf_estado` varchar(1) not null,
  `enf_fecha_creacion` timestamp not null default current_timestamp,
  `enf_fecha_modificacion` timestamp null default null,
  `enf_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `tipo_proyecto`
-- 
create table if not exists `tipo_proyecto` (
  `tpro_id` bigint(20) not null auto_increment primary key,
  `tpro_nombre` varchar(300) not null,
  `tpro_descripcion` varchar(500) not null,
  `tpro_usuario_ingreso` bigint(20) not null,
  `tpro_usuario_modifica` bigint(20)  null,  
  `tpro_estado` varchar(1) not null,
  `tpro_fecha_creacion` timestamp not null default current_timestamp,
  `tpro_fecha_modificacion` timestamp null default null,
  `tpro_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `unidad_medida`
-- 
create table if not exists `unidad_medida` (
  `umed_id` bigint(20) not null auto_increment primary key,
  `umed_nombre` varchar(300) not null,
  `umed_descripcion` varchar(500) not null,
  `umed_usuario_ingreso` bigint(20) not null,
  `umed_usuario_modifica` bigint(20)  null,  
  `umed_estado` varchar(1) not null,
  `umed_fecha_creacion` timestamp not null default current_timestamp,
  `umed_fecha_modificacion` timestamp null default null,
  `umed_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `jerarquia_indicador`
-- 
create table if not exists `jerarquia_indicador` (
  `jind_id` bigint(20) not null auto_increment primary key,
  `jind_nombre` varchar(300) not null,
  `jind_descripcion` varchar(500) not null,
  `jind_usuario_ingreso` bigint(20) not null,
  `jind_usuario_modifica` bigint(20)  null,  
  `jind_estado` varchar(1) not null,
  `jind_fecha_creacion` timestamp not null default current_timestamp,
  `jind_fecha_modificacion` timestamp null default null,
  `jind_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `patron_indicador`
-- 
create table if not exists `patron_indicador` (
  `pind_id` bigint(20) not null auto_increment primary key,
  `pind_nombre` varchar(300) not null,
  `pind_descripcion` varchar(500) not null,
  `pind_usuario_ingreso` bigint(20) not null,
  `pind_usuario_modifica` bigint(20)  null,  
  `pind_estado` varchar(1) not null,
  `pind_fecha_creacion` timestamp not null default current_timestamp,
  `pind_fecha_modificacion` timestamp null default null,
  `pind_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `comportamiento_indicador`
-- 
create table if not exists `comportamiento_indicador` (
  `cind_id` bigint(20) not null auto_increment primary key,
  `cind_nombre` varchar(300) not null,
  `cind_descripcion` varchar(500) not null,
  `cind_usuario_ingreso` bigint(20) not null,
  `cind_usuario_modifica` bigint(20)  null,  
  `cind_estado` varchar(1) not null,
  `cind_fecha_creacion` timestamp not null default current_timestamp,
  `cind_fecha_modificacion` timestamp null default null,
  `cind_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `frecuencia_indicador`
-- 
create table if not exists `frecuencia_indicador` (
  `find_id` bigint(20) not null auto_increment primary key,
  `find_nombre` varchar(300) not null,
  `find_denominador` int not null,
  `find_items` varchar(300) not null,
  `find_descripcion` varchar(500) not null,
  `find_usuario_ingreso` bigint(20) not null,
  `find_usuario_modifica` bigint(20)  null,  
  `find_estado` varchar(1) not null,
  `find_fecha_creacion` timestamp not null default current_timestamp,
  `find_fecha_modificacion` timestamp null default null,
  `find_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `tipo_agrupacion`
-- 
create table if not exists `tipo_agrupacion` (
  `tagr_id` bigint(20) not null auto_increment primary key,
  `tagr_nombre` varchar(300) not null,
  `tagr_descripcion` varchar(500) not null,
  `tagr_usuario_ingreso` bigint(20) not null,
  `tagr_usuario_modifica` bigint(20)  null,  
  `tagr_estado` varchar(1) not null,
  `tagr_fecha_creacion` timestamp not null default current_timestamp,
  `tagr_fecha_modificacion` timestamp null default null,
  `tagr_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `tipo_meta`
-- 
create table if not exists `tipo_meta` (
  `tmet_id` bigint(20) not null auto_increment primary key,
  `tmet_nombre` varchar(300) not null,
  `tmet_descripcion` varchar(500) not null,
  `tmet_usuario_ingreso` bigint(20) not null,
  `tmet_usuario_modifica` bigint(20)  null,  
  `tmet_estado` varchar(1) not null,
  `tmet_fecha_creacion` timestamp not null default current_timestamp,
  `tmet_fecha_modificacion` timestamp null default null,
  `tmet_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `tipo_unidad`
-- 
create table if not exists `tipo_unidad` (
  `tuni_id` bigint(20) not null auto_increment primary key,
  `tuni_nombre` varchar(300) not null,
  `tuni_descripcion` varchar(500) not null,
  `tuni_alias` varchar(10) not null,
  `tuni_usuario_ingreso` bigint(20) not null,
  `tuni_usuario_modifica` bigint(20)  null,  
  `tuni_estado` varchar(1) not null,
  `tuni_fecha_creacion` timestamp not null default current_timestamp,
  `tuni_fecha_modificacion` timestamp null default null,
  `tuni_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `nivel`
-- 
create table if not exists `nivel` (
  `niv_id` bigint(20) not null auto_increment primary key,
  `niv_nombre` varchar(300) not null,
  `niv_descripcion` varchar(500) not null,
  `niv_usuario_ingreso` bigint(20) not null,
  `niv_usuario_modifica` bigint(20)  null,  
  `niv_estado` varchar(1) not null,
  `niv_fecha_creacion` timestamp not null default current_timestamp,
  `niv_fecha_modificacion` timestamp null default null,
  `niv_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `categoria`
-- 
create table if not exists `categoria` (
  `cat_id` bigint(20) not null auto_increment primary key,
  `cat_nombre` varchar(300) not null,
  `cat_descripcion` varchar(500) not null,
  `cat_usuario_ingreso` bigint(20) not null,
  `cat_usuario_modifica` bigint(20)  null,  
  `cat_estado` varchar(1) not null,
  `cat_fecha_creacion` timestamp not null default current_timestamp,
  `cat_fecha_modificacion` timestamp null default null,
  `cat_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `entidad`
-- 
create table if not exists `entidad` (
  `ent_id` bigint(20) not null auto_increment primary key,
  `cat_id` bigint(20) not null,
  `emp_id` bigint(20) not null,
  `ent_nombre` varchar(300) not null,
  `ent_descripcion` varchar(500) not null,
  `ent_usuario_ingreso` bigint(20) not null,
  `ent_usuario_modifica` bigint(20)  null,  
  `ent_estado` varchar(1) not null,
  `ent_fecha_creacion` timestamp not null default current_timestamp,
  `ent_fecha_modificacion` timestamp null default null,
  `ent_estado_logico` varchar(1) not null,
  foreign key (cat_id) references `categoria`(cat_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `unidad_gpr`
-- 
create table if not exists `unidad_gpr` (
  `ugpr_id` bigint(20) not null auto_increment primary key,
  `ent_id` bigint(20) not null,
  `tuni_id` bigint(20) not null,
  `ugpr_nombre` varchar(300) not null,
  `ugpr_descripcion` varchar(500) not null,
  `ugpr_usuario_ingreso` bigint(20) not null,
  `ugpr_usuario_modifica` bigint(20)  null,  
  `ugpr_estado` varchar(1) not null,
  `ugpr_fecha_creacion` timestamp not null default current_timestamp,
  `ugpr_fecha_modificacion` timestamp null default null,
  `ugpr_estado_logico` varchar(1) not null,
  foreign key (ent_id) references `entidad`(ent_id),
  foreign key (tuni_id) references `tipo_unidad`(tuni_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `subunidad_gpr`
-- 
create table if not exists `subunidad_gpr` (
  `sgpr_id` bigint(20) not null auto_increment primary key,
  `ugpr_id` bigint(20) not null,
  `sgpr_nombre` varchar(300) not null,
  `sgpr_descripcion` varchar(500) not null,
  `sgpr_usuario_ingreso` bigint(20) not null,
  `sgpr_usuario_modifica` bigint(20)  null,  
  `sgpr_estado` varchar(1) not null,
  `sgpr_fecha_creacion` timestamp not null default current_timestamp,
  `sgpr_fecha_modificacion` timestamp null default null,
  `sgpr_estado_logico` varchar(1) not null,
  foreign key (ugpr_id) references `unidad_gpr`(ugpr_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `responsable_unidad`
-- 
create table if not exists `responsable_unidad` (
  `runi_id` bigint(20) not null auto_increment primary key,
  `ugpr_id` bigint(20) not null,
  `usu_id` bigint(20) not null,
  `emp_id` bigint(20) not null,
  `niv_id` bigint(20) not null,
  `runi_isadmin` varchar(1) not null default '0',
  `runi_usuario_ingreso` bigint(20) not null,
  `runi_usuario_modifica` bigint(20)  null,  
  `runi_estado` varchar(1) not null,
  `runi_fecha_creacion` timestamp not null default current_timestamp,
  `runi_fecha_modificacion` timestamp null default null,
  `runi_estado_logico` varchar(1) not null,
  foreign key (ugpr_id) references `unidad_gpr`(ugpr_id),
  foreign key (niv_id) references `nivel`(niv_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `planificacion_pedi`
-- 
create table if not exists `planificacion_pedi` (
  `pped_id` bigint(20) not null auto_increment primary key,
  `ent_id` bigint(20) not null,
  `pped_nombre` varchar(300) not null,
  `pped_descripcion` varchar(500) not null,
  `pped_fecha_inicio` timestamp null default null,
  `pped_fecha_fin` timestamp null default null,
  `pped_fecha_actualizacion` timestamp null default null,  
  `pped_estado_cierre` varchar(1) not null default '0',
  `pped_usuario_ingreso` bigint(20) not null,
  `pped_usuario_modifica` bigint(20)  null,  
  `pped_estado` varchar(1) not null,
  `pped_fecha_creacion` timestamp not null default current_timestamp,
  `pped_fecha_modificacion` timestamp null default null,
  `pped_estado_logico` varchar(1) not null,
  foreign key (ent_id) references `entidad`(ent_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `objetivo_estrategico`
-- 
create table if not exists `objetivo_estrategico` (
  `oest_id` bigint(20) not null auto_increment primary key,
  `pped_id` bigint(20) not null,
  `enf_id` bigint(20) not null,
  `cbsc_id` bigint(20) not null,
  `oest_nombre` varchar(300) not null,
  `oest_descripcion` varchar(500) not null,
  `oest_fecha_actualizacion` timestamp null default null,
  `oest_usuario_ingreso` bigint(20) not null,
  `oest_usuario_modifica` bigint(20)  null,  
  `oest_estado` varchar(1) not null,
  `oest_fecha_creacion` timestamp not null default current_timestamp,
  `oest_fecha_modificacion` timestamp null default null,
  `oest_estado_logico` varchar(1) not null,
  foreign key (pped_id) references `planificacion_pedi`(pped_id),
  foreign key (enf_id) references `enfoque`(enf_id),
  foreign key (cbsc_id) references `categoria_bsc`(cbsc_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `objetivo_especifico`
-- 
create table if not exists `objetivo_especifico` (
  `oesp_id` bigint(20) not null auto_increment primary key,
  `oest_id` bigint(20) not null,
  `ugpr_id` bigint(20) not null,
  `oesp_nombre` varchar(300) not null,
  `oesp_descripcion` varchar(500) not null,
  `oesp_usuario_ingreso` bigint(20) not null,
  `oesp_usuario_modifica` bigint(20)  null,  
  `oesp_estado` varchar(1) not null,
  `oesp_fecha_creacion` timestamp not null default current_timestamp,
  `oesp_fecha_modificacion` timestamp null default null,
  `oesp_estado_logico` varchar(1) not null,
  foreign key (oest_id) references `objetivo_estrategico`(oest_id),
  foreign key (ugpr_id) references `unidad_gpr`(ugpr_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `planificacion_poa`
-- 
create table if not exists `planificacion_poa` (
  `ppoa_id` bigint(20) not null auto_increment primary key,
  `pped_id` bigint(20) not null,
  `ppoa_nombre` varchar(300) not null,
  `ppoa_descripcion` varchar(500) not null,
  `ppoa_fecha_inicio` timestamp null default null,
  `ppoa_fecha_fin` timestamp null default null,
  `ppoa_fecha_actualizacion` timestamp null default null,  
  `ppoa_usuario_ingreso` bigint(20) not null,
  `ppoa_usuario_modifica` bigint(20)  null,  
  `ppoa_estado_cierre` varchar(1) not null default '0',
  `ppoa_estado` varchar(1) not null,
  `ppoa_fecha_creacion` timestamp not null default current_timestamp,
  `ppoa_fecha_modificacion` timestamp null default null,
  `ppoa_estado_logico` varchar(1) not null,
  foreign key (pped_id) references `planificacion_pedi`(pped_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `objetivo_operativo`
-- 
create table if not exists `objetivo_operativo` (
  `oope_id` bigint(20) not null auto_increment primary key,
  `ppoa_id` bigint(20) not null,
  `oesp_id` bigint(20) not null,
  `ugpr_id` bigint(20) not null,
  `oope_nombre` varchar(300) not null,
  `oope_descripcion` varchar(500) not null,
  `oope_fecha_actualizacion` timestamp null default null,  
  `oope_usuario_ingreso` bigint(20) not null,
  `oope_usuario_modifica` bigint(20)  null,  
  `oope_estado` varchar(1) not null,
  `oope_fecha_creacion` timestamp not null default current_timestamp,
  `oope_fecha_modificacion` timestamp null default null,
  `oope_estado_logico` varchar(1) not null,
  foreign key (ppoa_id) references `planificacion_poa`(ppoa_id),
  foreign key (ugpr_id) references `unidad_gpr`(ugpr_id),
  foreign key (oesp_id) references `objetivo_especifico`(oesp_id)
);
-- -------------------------------------------------------- 
-- 
-- Estructura de tabla para la tabla `estrategia_objestr`
-- 
create table if not exists `estrategia_objestr` (
  `eoet_id` bigint(20) not null auto_increment primary key,
  `oest_id` bigint(20) not null,
  `eoet_nombre` varchar(300) not null,
  `eoet_descripcion` varchar(500) not null,
  `eoet_usuario_ingreso` bigint(20) not null,
  `eoet_usuario_modifica` bigint(20)  null,  
  `eoet_estado` varchar(1) not null,
  `eoet_fecha_creacion` timestamp not null default current_timestamp,
  `eoet_fecha_modificacion` timestamp null default null,
  `eoet_estado_logico` varchar(1) not null,
   foreign key (oest_id) references `objetivo_estrategico`(oest_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `estrategia_objespe`
-- 
create table if not exists `estrategia_objespe` (
  `eoep_id` bigint(20) not null auto_increment primary key,
  `oesp_id` bigint(20) not null,
  `eoep_nombre` varchar(300) not null,
  `eoep_descripcion` varchar(500) not null,
  `eoep_usuario_ingreso` bigint(20) not null,
  `eoep_usuario_modifica` bigint(20)  null,  
  `eoep_estado` varchar(1) not null,
  `eoep_fecha_creacion` timestamp not null default current_timestamp,
  `eoep_fecha_modificacion` timestamp null default null,
  `eoep_estado_logico` varchar(1) not null,
   foreign key (oesp_id) references `objetivo_especifico`(oesp_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `indicador`
-- 
create table if not exists `indicador` (
  `ind_id` bigint(20) not null auto_increment primary key,
  `oope_id` bigint(20) not null,
  `tcon_id` bigint(20) not null,
  `pind_id` bigint(20) not null,
  `umed_id` bigint(20) not null,
  `ugpr_id` bigint(20) not null,
  `jind_id` bigint(20) not null,
  `cind_id` bigint(20) not null,
  `tmet_id` bigint(20) not null,
  `find_id` bigint(20) not null,
  `tagr_id` bigint(20) null,
  `ind_nombre` varchar(300) not null,
  `ind_descripcion` text not null,
  `ind_linea_base` varchar(10) null,
  `ind_meta` varchar(10) null,
  `ind_fuente_informe` varchar(200) not null,
  `ind_metodo_calculo` varchar(200) not null,
  `ind_fraccional` varchar(1) not null default '0',
  `ind_agrupamiento` varchar(1) not null default '0',
  `ind_fecha_inicio` timestamp null default null,
  `ind_fecha_fin` timestamp null default null,
  `ind_usuario_ingreso` bigint(20) not null,
  `ind_usuario_modifica` bigint(20)  null,  
  `ind_estado` varchar(1) not null,
  `ind_fecha_creacion` timestamp not null default current_timestamp,
  `ind_fecha_modificacion` timestamp null default null,
  `ind_estado_logico` varchar(1) not null,
  foreign key (oope_id) references `objetivo_operativo`(oope_id),
  foreign key (tcon_id) references `tipo_configuracion`(tcon_id),
  foreign key (pind_id) references `patron_indicador`(pind_id),
  foreign key (umed_id) references `unidad_medida`(umed_id),
  foreign key (ugpr_id) references `unidad_gpr`(ugpr_id),
  foreign key (jind_id) references `jerarquia_indicador`(jind_id),
  foreign key (cind_id) references `comportamiento_indicador`(cind_id),
  foreign key (tmet_id) references `tipo_meta`(tmet_id),
  foreign key (find_id) references `frecuencia_indicador`(find_id),
  foreign key (tagr_id) references `tipo_agrupacion`(tagr_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `meta_indicador`
-- 
create table if not exists `meta_indicador` (
  `mind_id` bigint(20) not null auto_increment primary key,
  `ind_id` bigint(20) not null,
  `mind_nombre` varchar(300) not null,
  `mind_descripcion` varchar(500) null default NULL,
  `mind_meta` varchar(10) null default NULL,
  `mind_numerador` varchar(10) null default NULL,
  `mind_denominador` varchar(10) null default NULL,
  `mind_resultado` varchar(10) null default NULL,
  `mind_avance` varchar(10) null default NULL,
  `mind_meta_cerrada` varchar(1) null default '0',
  `mind_fecha_inicio` timestamp null default null,
  `mind_fecha_fin` timestamp null default null,
  `mind_usuario_ingreso` bigint(20) not null,
  `mind_usuario_modifica` bigint(20) null,  
  `mind_estado` varchar(1) not null,
  `mind_fecha_creacion` timestamp not null default current_timestamp,
  `mind_fecha_modificacion` timestamp null default null,
  `mind_estado_logico` varchar(1) not null,
   foreign key (ind_id) references `indicador`(ind_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `meta_seguimiento`
-- 
create table if not exists `meta_seguimiento` (
  `mseg_id` bigint(20) not null auto_increment primary key,
  `mind_id` bigint(20) not null,
  `mseg_nombre` varchar(300) not null,
  `mseg_descripcion` varchar(500) null default NULL,
  `mseg_comentario` text null default NULL,
  `mseg_meta` varchar(10) null default NULL,
  `mseg_numerador` varchar(10) null default NULL,
  `mseg_denominador` varchar(10) null default NULL,
  `mseg_resultado` varchar(10) null default NULL,
  `mseg_avance` varchar(10) null default NULL,
  `mseg_periodo_cerrado` varchar(1) null default '0',
  `mseg_fecha_inicio` timestamp null default null,
  `mseg_fecha_fin` timestamp null default null,
  `mseg_usuario_ingreso` bigint(20) not null,
  `mseg_usuario_modifica` bigint(20)  null,  
  `mseg_estado` varchar(1) not null,
  `mseg_fecha_creacion` timestamp not null default current_timestamp,
  `mseg_fecha_modificacion` timestamp null default null,
  `mseg_estado_logico` varchar(1) not null,
   foreign key (mind_id) references `meta_indicador`(mind_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `meta_anexo`
-- 
create table if not exists `meta_anexo` (
  `mane_id` bigint(20) not null auto_increment primary key,
  `mseg_id` bigint(20) not null,
  `mane_nombre` varchar(300) not null,
  `mane_descripcion` varchar(500) not null,
  `mane_ruta` varchar(500) not null,
  `mane_usuario_ingreso` bigint(20) not null,
  `mane_usuario_modifica` bigint(20)  null,  
  `mane_estado` varchar(1) not null,
  `mane_fecha_creacion` timestamp not null default current_timestamp,
  `mane_fecha_modificacion` timestamp null default null,
  `mane_estado_logico` varchar(1) not null,
   foreign key (mseg_id) references `meta_seguimiento`(mseg_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `proyecto`
-- 
create table if not exists `proyecto` (
  `pro_id` bigint(20) not null auto_increment primary key,
  `tpro_id` bigint(20) not null,
  `ugpr_id` bigint(20) not null,
  `oope_id` bigint(20) not null,
  `pro_nombre` varchar(300) not null,
  `pro_descripcion` varchar(500) not null,
  `pro_restricciones` varchar(500) not null,
  `pro_fecha_inicio` timestamp not null,
  `pro_fecha_fin` timestamp not null default current_timestamp,
  `pro_presupuesto` double(12,4) not null,
  `pro_cerrado` varchar(1) null default '0',
  `pro_razon_cambio` text null default NULL,
  `pro_usuario_ingreso` bigint(20) not null,
  `pro_usuario_modifica` bigint(20)  null,  
  `pro_estado` varchar(1) not null,
  `pro_fecha_creacion` timestamp not null default current_timestamp,
  `pro_fecha_modificacion` timestamp null default null,
  `pro_estado_logico` varchar(1) not null,
  foreign key (tpro_id) references `tipo_proyecto`(tpro_id),
  foreign key (ugpr_id) references `unidad_gpr`(ugpr_id),
  foreign key (oope_id) references `objetivo_operativo`(oope_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `hito`
-- 
create table if not exists `hito` (
  `hito_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `hito_nombre` varchar(300) not null,
  `hito_descripcion` varchar(500) not null,
  `hito_fecha_compromiso` timestamp not null,
  `hito_fecha_real` timestamp null,
  `hito_presupuesto` double(12,4) not null,
  `hito_cumplido` varchar(1) not null default '0', -- si se ha cumplido o no la subactvidad
  `hito_peso` varchar(10) not null, -- El peso es el porcentaje de trabajo para la actividad
  `hito_progreso` varchar(10) not null, --  Es el progreso actual de la subactividad ingresada
  `hito_cumplimiento_poa` varchar(10) not null, -- Es el porcentaje de cumplimiento poa
  `hito_cerrado` varchar(1) null default '0',
  `hito_usuario_ingreso` bigint(20) not null,
  `hito_usuario_modifica` bigint(20)  null,  
  `hito_estado` varchar(1) not null,
  `hito_fecha_creacion` timestamp not null default current_timestamp,
  `hito_fecha_modificacion` timestamp null default null,
  `hito_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `proyecto`(pro_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `hito_seguimiento`
-- 
create table if not exists `hito_seguimiento` (
  `hseg_id` bigint(20) not null auto_increment primary key,
  `hito_id` bigint(20) not null,
  `hseg_nombre` varchar(300) not null,
  `hseg_descripcion` varchar(500) not null,
  `hseg_fecha_compromiso` timestamp not null,
  `hseg_fecha_real` timestamp null,
  `hseg_presupuesto` double(12,4) not null,
  `hseg_cumplido` varchar(1) not null default '0', -- si se ha cumplido o no la subactvidad
  `hseg_peso` varchar(10) not null, -- El peso es el porcentaje de trabajo para la actividad
  `hseg_progreso` varchar(10) not null, --  Es el progreso actual de la subactividad
  `hseg_cumplimiento_poa` varchar(10) not null, -- Es el porcentaje de cumplimiento poa
  `hseg_cerrado` varchar(1) null default '0',
  `hseg_usuario_ingreso` bigint(20) not null,
  `hseg_usuario_modifica` bigint(20)  null,  
  `hseg_estado` varchar(1) not null,
  `hseg_fecha_creacion` timestamp not null default current_timestamp,
  `hseg_fecha_modificacion` timestamp null default null,
  `hseg_estado_logico` varchar(1) not null,
  foreign key (hito_id) references `hito`(hito_id)
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `hito_anexo`
-- 
create table if not exists `hito_anexo` (
  `hane_id` bigint(20) not null auto_increment primary key,
  `hseg_id` bigint(20) not null,
  `hane_nombre` varchar(300) not null,
  `hane_descripcion` varchar(500) not null,
  `hane_ruta` varchar(500) not null,
  `hane_usuario_ingreso` bigint(20) not null,
  `hane_usuario_modifica` bigint(20)  null,  
  `hane_estado` varchar(1) not null,
  `hane_fecha_creacion` timestamp not null default current_timestamp,
  `hane_fecha_modificacion` timestamp null default null,
  `hane_estado_logico` varchar(1) not null,
   foreign key (hseg_id) references `hito_seguimiento`(hseg_id)
);