--
-- Base de datos: `db_academico`
--

DROP SCHEMA IF EXISTS `db_academico`;
CREATE SCHEMA IF NOT EXISTS `db_academico` DEFAULT CHARACTER SET utf8 ;
USE `db_academico` ;


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `area_conocimiento`
--
create table if not exists `area_conocimiento` (
  `acon_id` bigint(20) not null auto_increment primary key,
  `acon_nombre` varchar(300) not null,
  `acon_descripcion` varchar(500) not null,
  `acon_usuario_ingreso` bigint(20) not null,
  `acon_usuario_modifica` bigint(20)  null,
  `acon_estado` varchar(1) not null,
  `acon_fecha_creacion` timestamp not null default current_timestamp,
  `acon_fecha_modificacion` timestamp null default null,
  `acon_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `subarea_conocimiento`
--
create table if not exists `subarea_conocimiento` (
  `scon_id` bigint(20) not null auto_increment primary key,
  `acon_id` bigint(20) not null,
  `scon_nombre` varchar(300) not null,
  `scon_descripcion` varchar(500) not null,
  `scon_usuario_ingreso` bigint(20) not null,
  `scon_usuario_modifica` bigint(20)  null,
  `scon_estado` varchar(1) not null,
  `scon_fecha_creacion` timestamp not null default current_timestamp,
  `scon_fecha_modificacion` timestamp null default null,
  `scon_estado_logico` varchar(1) not null,
  foreign key (acon_id) references `area_conocimiento`(acon_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `nivel_instruccion`
--
create table if not exists `nivel_instruccion` (
 `nins_id` bigint(20) not null auto_increment primary key,
 `nins_nombre` varchar(250) default null,
 `nins_descripcion` varchar(500) default null,
 `nins_estado` varchar(1) not null,
 `nins_fecha_creacion` timestamp not null default current_timestamp,
 `nins_fecha_modificacion` timestamp null default null,
 `nins_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_institucion_aca`
--
create table if not exists `tipo_institucion_aca` (
 `tiac_id` bigint(20) not null auto_increment primary key,
 `tiac_nombre` varchar(250) default null,
 `tiac_descripcion` varchar(500) default null,
 `tiac_estado` varchar(1) not null,
 `tiac_fecha_creacion` timestamp not null default current_timestamp,
 `tiac_fecha_modificacion` timestamp null default null,
 `tiac_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `tipo_estudio_academico`
-- 1.- Carrera, 2.- Programa
create table if not exists `tipo_estudio_academico` (
  `teac_id` bigint(20) not null auto_increment primary key,
  `teac_nombre` varchar(300) not null,
  `teac_descripcion` varchar(500) not null,
  `teac_usuario_ingreso` bigint(20) not null,
  `teac_usuario_modifica` bigint(20)  null,
  `teac_estado` varchar(1) not null,
  `teac_fecha_creacion` timestamp not null default current_timestamp,
  `teac_fecha_modificacion` timestamp null default null,
  `teac_estado_logico` varchar(1) not null
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `unidad_academica`
--
create table if not exists `unidad_academica` (
 `uaca_id` bigint(20) not null auto_increment primary key,
 `uaca_nombre` varchar(300) not null,
 `uaca_nomenclatura` varchar(3) null,
 `uaca_descripcion` varchar(500) not null,
 `uaca_usuario_ingreso` bigint(20) not null,
 `uaca_usuario_modifica` bigint(20)  null,
 `uaca_estado` varchar(1) not null,
 `uaca_inscripcion` varchar(1) not null, -- 1 para formulario de inscripcion, 0 no muestra
 `uaca_fecha_creacion` timestamp not null default current_timestamp,
 `uaca_fecha_modificacion` timestamp null default null,
 `uaca_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `modalidad`
-- 1 distancia, 2 presencial, 3 semipresencial, 4 online

create table if not exists `modalidad` (
  `mod_id` bigint(20) not null auto_increment primary key,
  `mod_nombre` varchar(300) not null,
  `mod_descripcion` varchar(500) not null,
  `mod_usuario_ingreso` bigint(20) not null,
  `mod_usuario_modifica` bigint(20)  null,
  `mod_estado` varchar(1) not null,
  `mod_fecha_creacion` timestamp not null default current_timestamp,
  `mod_fecha_modificacion` timestamp null default null,
  `mod_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `estudio_academico`
--
create table if not exists `estudio_academico` (
  `eaca_id` bigint(20) not null auto_increment primary key,
  `teac_id` bigint(20) not null,
  `eaca_codigo` varchar(20) null,
  `eaca_nombre` varchar(300) not null,
  `eaca_descripcion` varchar(500) not null,
  `eaca_alias` varchar(300) null,
  `eaca_alias_resumen` varchar(30) null,
  `eaca_usuario_ingreso` bigint(20) not null,
  `eaca_usuario_modifica` bigint(20)  null,
  `eaca_estado` varchar(1) not null,
  `eaca_fecha_creacion` timestamp not null default current_timestamp,
  `eaca_fecha_modificacion` timestamp null default null,
  `eaca_estado_logico` varchar(1) not null,
  foreign key (teac_id) references `tipo_estudio_academico`(teac_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `asignatura`
--
create table if not exists `asignatura` (
  `asi_id` bigint(20) not null auto_increment primary key,
  `scon_id` bigint(20) not null,
  `uaca_id` bigint(20) not null,
  `asi_nombre` varchar(300) not null,
  `asi_alias` varchar(300) not null,
  `asi_descripcion` varchar(500) not null,
  `asi_usuario_ingreso` bigint(20) not null,
  `asi_usuario_modifica` bigint(20)  null,
  `asi_estado` varchar(1) not null,
  `asi_fecha_creacion` timestamp not null default current_timestamp,
  `asi_fecha_modificacion` timestamp null default null,
  `asi_estado_logico` varchar(1) not null,
  foreign key (scon_id) references `subarea_conocimiento`(scon_id),
  foreign key (uaca_id) references `unidad_academica`(uaca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `modalidad_estudio_unidad`
--
create table if not exists `modalidad_estudio_unidad` (
 `meun_id` bigint(20) not null auto_increment primary key,
 `uaca_id` bigint(20) not null, -- id de unidad academica
 `mod_id` bigint(20) not null, -- id modalidad
 `eaca_id` bigint(20) not null, -- id de estudio academico
 `emp_id` bigint(20) not null, -- id de empresa
 `meun_usuario_ingreso` bigint(20) not null,
 `meun_usuario_modifica` bigint(20)  null,
 `meun_estado` varchar(1) not null,
 `meun_fecha_creacion` timestamp not null default current_timestamp,
 `meun_fecha_modificacion` timestamp null default null,
 `meun_estado_logico` varchar(1) not null,
 foreign key (uaca_id) references `unidad_academica`(uaca_id),
 foreign key (mod_id) references `modalidad`(mod_id),
 foreign key (eaca_id) references `estudio_academico`(eaca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `unidad_estudio`
--
create table if not exists `unidad_estudio` (
 `uest_id` bigint(20) not null auto_increment primary key,
 `uest_nombre` varchar(300) not null,
 `uest_descripcion` varchar(500) not null,
 `uest_usuario_ingreso` bigint(20) not null,
 `uest_usuario_modifica` bigint(20)  null,
 `uest_estado` varchar(1) not null,
 `uest_fecha_creacion` timestamp not null default current_timestamp,
 `uest_fecha_modificacion` timestamp null default null,
 `uest_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `nivel_estudio`
--
create table if not exists `nivel_estudio` (
  `nest_id` bigint(20) not null auto_increment primary key,
  `nest_nombre` varchar(150) default null,
  `nest_descripcion` varchar(500) default null,
  `nest_usuario_ingreso` bigint(20) not null,
  `nest_usuario_modifica` bigint(20)  null,
  `nest_estado` varchar(1) not null,
  `nest_fecha_creacion` timestamp not null default current_timestamp,
  `nest_fecha_modificacion` timestamp null default null,
  `nest_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `formacion_malla_academica`
--
create table if not exists `formacion_malla_academica` (
  `fmac_id` bigint(20) not null auto_increment primary key,
  `fmac_codigo` varchar(5) not null,
  `fmac_nombre` varchar(200) not null,
  `fmac_descripcion` varchar(500) not null,
  `fmac_usuario_ingreso` bigint(20) not null,
  `fmac_usuario_modifica` bigint(20)  null,
  `fmac_estado` varchar(1) not null,
  `fmac_fecha_creacion` timestamp not null default current_timestamp,
  `fmac_fecha_modificacion` timestamp null default null,
  `fmac_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `malla_academica`
--

create table if not exists `malla_academica` (
  `maca_id` bigint(20) not null auto_increment primary key,
  `maca_tipo` varchar(1) null,  -- 1= método de ingreso, 2 = carrera.
  `maca_codigo` varchar(50) null,
  `maca_nombre` varchar(300) not null,
  `maca_fecha_vigencia_inicio` timestamp null default null,
  `maca_fecha_vigencia_fin` timestamp null default null,
  `maca_usuario_ingreso` bigint(20) not null,
  `maca_usuario_modifica` bigint(20)  null,
  `maca_estado` varchar(1) not null,
  `maca_fecha_creacion` timestamp not null default current_timestamp,
  `maca_fecha_modificacion` timestamp null default null,
  `maca_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `malla_academica_detalle`
--
create table if not exists `malla_academica_detalle` (
  `made_id` bigint(20) not null auto_increment primary key,
  `maca_id` bigint(20) not null,
  `asi_id` bigint(20) not null,
  `made_semestre` int not null,
  `uest_id` bigint(20) not null,
  `nest_id` bigint(20) not null,
  `fmac_id` bigint(20) not null,
  `made_asi_requisito` bigint(20) null,
  `made_codigo_asignatura` varchar(300) not null,
  `made_hora` integer(04) null,
  `made_credito` integer(2) null,
  `made_horas_docencia` integer(4) null,
  `made_horas_otros` integer(4) null,
  `made_usuario_ingreso` bigint(20) not null,
  `made_usuario_modifica` bigint(20)  null,
  `made_estado` varchar(1) not null,
  `made_fecha_creacion` timestamp not null default current_timestamp,
  `made_fecha_modificacion` timestamp null default null,
  `made_estado_logico` varchar(1) not null,
  foreign key (maca_id) references `malla_academica`(maca_id),
  foreign key (asi_id) references `asignatura`(asi_id),
  foreign key (uest_id) references `unidad_estudio`(uest_id),
  foreign key (nest_id) references `nivel_estudio`(nest_id),
  foreign key (fmac_id) references `formacion_malla_academica`(fmac_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `modulo_estudio`
--
create table if not exists `modulo_estudio` (
  `mest_id` bigint(20) not null auto_increment primary key,
  `uaca_id` bigint(20) not null,
  `mod_id` bigint(20) not null, -- id modalidad
  `mest_codigo` varchar(20) not null,
  `mest_nombre` varchar(300) not null,
  `mest_descripcion` varchar(300) not null,
  `mest_alias` varchar(300) not null,
  `mest_usuario_ingreso` bigint(20) not null,
  `mest_usuario_modifica` bigint(20)  null,
  `mest_estado` varchar(1) not null,
  `mest_fecha_creacion` timestamp not null default current_timestamp,
  `mest_fecha_modificacion` timestamp null default null,
  `mest_estado_logico` varchar(1) not null,
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (mod_id) references `modalidad`(mod_id)

);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `semestre`
--
create table if not exists `semestre_academico` (
  `saca_id` bigint(20) not null auto_increment primary key,
  `saca_nombre` varchar(300) not null,
  `saca_intensivo` varchar(300) not null,
  `saca_descripcion` varchar(300) not null,
  `saca_anio` integer(4) not null,
  `saca_fecha_registro` timestamp null default null,
  `saca_usuario_ingreso` bigint(20) not null,
  `saca_usuario_modifica` bigint(20)  null,
  `saca_estado` varchar(1) not null,
  `saca_fecha_creacion` timestamp not null default current_timestamp,
  `saca_fecha_modificacion` timestamp null default null,
  `saca_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `bloque`
--
create table if not exists `bloque_academico` (
  `baca_id` bigint(20) not null auto_increment primary key,
  `baca_nombre` varchar(300) not null,
  `baca_descripcion` varchar(300) not null,
  `baca_anio` integer(4) not null,
  `baca_usuario_ingreso` bigint(20) not null,
  `baca_usuario_modifica` bigint(20)  null,
  `baca_estado` varchar(1) not null,
  `baca_fecha_creacion` timestamp not null default current_timestamp,
  `baca_fecha_modificacion` timestamp null default null,
  `baca_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `periodo_academico`
--
create table if not exists `periodo_academico` (
  `paca_id` bigint(20) not null auto_increment primary key,
  `saca_id` bigint(20) null,
  `baca_id` bigint(20) null,
  `paca_activo` varchar(1) not null,
  `paca_fecha_inicio` timestamp null default null,
  `paca_fecha_fin` timestamp null default null,
  `paca_usuario_ingreso` bigint(20) not null,
  `paca_usuario_modifica` bigint(20)  null,
  `paca_estado` varchar(1) not null,
  `paca_fecha_creacion` timestamp not null default current_timestamp,
  `paca_fecha_modificacion` timestamp null default null,
  `paca_estado_logico` varchar(1) not null,
  foreign key (saca_id) references `semestre_academico`(saca_id),
  foreign key (baca_id) references `bloque_academico`(baca_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `estudiante`
--
create table if not exists `estudiante` (
  `est_id` bigint(20) not null auto_increment primary key,
  `per_id` bigint(20) not null,
  `est_matricula` varchar(20) null,
  `est_categoria` varchar(2) null,
  `est_fecha_ingreso` timestamp null default null,
  `est_usuario_ingreso` bigint(20) not null,
  `est_usuario_modifica` bigint(20)  null,
  `est_estado` varchar(1) not null,
  `est_fecha_creacion` timestamp not null default current_timestamp,
  `est_fecha_modificacion` timestamp null default null,
  `est_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `dedicacion_docente`
--
create table if not exists `dedicacion_docente` (
 `ddoc_id` bigint(20) not null auto_increment primary key,
 `ddoc_nombre` varchar(100) default null,
 `ddoc_estado` varchar(1) not null,
 `ddoc_fecha_creacion` timestamp not null default current_timestamp,
 `ddoc_fecha_modificacion` timestamp null default null,
 `ddoc_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor`
--
create table if not exists `profesor` (
  `pro_id` bigint(20) not null auto_increment primary key,
  `per_id` bigint(20) not null,
  `pro_fecha_contratacion` timestamp null default null,
  `pro_fecha_terminacion` timestamp null default null,
  `pro_cv` varchar(255) null,
  `pro_usuario_ingreso` bigint(20) not null,
  `pro_usuario_modifica` bigint(20)  null,
  `pro_estado` varchar(1) not null,
  `pro_fecha_creacion` timestamp not null default current_timestamp,
  `pro_fecha_modificacion` timestamp null default null,
  `pro_estado_logico` varchar(1) not null,
  `ddoc_id` bigint(20) null,
  `pro_num_contrato` varchar(45) null

);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_instruccion`
--
create table if not exists `profesor_instruccion` (
  `pins_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `nins_id` bigint(20) not null,
  `pins_institucion` varchar(150) null,
  `pins_especializacion` varchar(150) null,
  `pins_titulo` varchar(200) not null,
  `pins_senescyt` varchar(50) null,
  `pins_usuario_ingreso` bigint(20) not null,
  `pins_usuario_modifica` bigint(20)  null,
  `pins_estado` varchar(1) not null,
  `pins_fecha_creacion` timestamp not null default current_timestamp,
  `pins_fecha_modificacion` timestamp null default null,
  `pins_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (nins_id) references `nivel_instruccion`(nins_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_exp_doc`
--
create table if not exists `profesor_exp_doc` (
  `pedo_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `ins_id` bigint(20) not null,
  `pedo_fecha_inicio` timestamp null default null,
  `pedo_fecha_fin` timestamp null default null,
  `pedo_denominacion` varchar(100) not null,
  `pedo_asignaturas` varchar(5000) not null,
  `pedo_usuario_ingreso` bigint(20) not null,
  `pedo_usuario_modifica` bigint(20)  null,
  `pedo_estado` varchar(1) not null,
  `pedo_fecha_creacion` timestamp not null default current_timestamp,
  `pedo_fecha_modificacion` timestamp null default null,
  `pedo_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_exp_prof`
--
create table if not exists `profesor_exp_prof` (
  `pepr_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pepr_fecha_inicio` timestamp null default null,
  `pepr_fecha_fin` timestamp null default null,
  `pepr_organizacion` varchar(200) not null,
  `pepr_denominacion` varchar(100) not null,
  `pepr_funciones` varchar(5000) not null,
  `pepr_usuario_ingreso` bigint(20) not null,
  `pepr_usuario_modifica` bigint(20)  null,
  `pepr_estado` varchar(1) not null,
  `pepr_fecha_creacion` timestamp not null default current_timestamp,
  `pepr_fecha_modificacion` timestamp null default null,
  `pepr_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_idiomas`
--
create table if not exists `profesor_idiomas` (
  `pidi_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `idi_id` bigint(20) not null,
  `pidi_nivel_escrito` varchar(200) not null,
  `pidi_nivel_oral` varchar(100) not null,
  `pidi_certificado` varchar(200) not null,
  `pidi_institucion` varchar(200) not null,
  `pidi_usuario_ingreso` bigint(20) not null,
  `pidi_usuario_modifica` bigint(20)  null,
  `pidi_estado` varchar(1) not null,
  `pidi_fecha_creacion` timestamp not null default current_timestamp,
  `pidi_fecha_modificacion` timestamp null default null,
  `pidi_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_investigacion`
--
create table if not exists `profesor_investigacion` (
  `pinv_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pinv_proyecto` varchar(200) not null,
  `pinv_ambito` varchar(100) not null,
  `pinv_responsabilidad` varchar(200) not null,
  `pinv_entidad` varchar(200) not null,
  `pinv_anio` varchar(4) not null,
  `pinv_duracion` varchar(20) not null,
  `pinv_usuario_ingreso` bigint(20) not null,
  `pinv_usuario_modifica` bigint(20)  null,
  `pinv_estado` varchar(1) not null,
  `pinv_fecha_creacion` timestamp not null default current_timestamp,
  `pinv_fecha_modificacion` timestamp null default null,
  `pinv_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_capacitacion`
--
create table if not exists `profesor_capacitacion` (
  `pcap_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pcap_evento` varchar(200) not null,
  `pcap_institucion` varchar(200) not null,
  `pcap_anio` varchar(4) not null,
  `pcap_tipo` varchar(200) not null,
  `pcap_duracion` varchar(20) not null,
  `pcap_usuario_ingreso` bigint(20) not null,
  `pcap_usuario_modifica` bigint(20)  null,
  `pcap_estado` varchar(1) not null,
  `pcap_fecha_creacion` timestamp not null default current_timestamp,
  `pcap_fecha_modificacion` timestamp null default null,
  `pcap_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_conferencia`
--
create table if not exists `profesor_conferencia` (
  `pcon_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pcon_evento` varchar(200) not null,
  `pcon_institucion` varchar(200) not null,
  `pcon_anio` varchar(4) not null,
  `pcon_ponencia` varchar(200) not null,
  `pcon_usuario_ingreso` bigint(20) not null,
  `pcon_usuario_modifica` bigint(20)  null,
  `pcon_estado` varchar(1) not null,
  `pcon_fecha_creacion` timestamp not null default current_timestamp,
  `pcon_fecha_modificacion` timestamp null default null,
  `pcon_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_publicacion`
--
create table if not exists `profesor_publicacion` (
  `ppub_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `tpub_id` bigint(20) null,
  `ppub_produccion` varchar(100) not null,
  `ppub_titulo` varchar(200) not null,
  `ppub_editorial` varchar(50) not null,
  `ppub_isbn` varchar(50) not null,
  `ppub_autoria` varchar(200) not null,
  `ppub_usuario_ingreso` bigint(20) not null,
  `ppub_usuario_modifica` bigint(20)  null,
  `ppub_estado` varchar(1) not null,
  `ppub_fecha_creacion` timestamp not null default current_timestamp,
  `ppub_fecha_modificacion` timestamp null default null,
  `ppub_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_coordinacion`
--
create table if not exists `profesor_coordinacion` (
  `pcoo_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pcoo_alumno` varchar(100) not null,
  `pcoo_programa` varchar(1000) not null,
  `pcoo_academico` varchar(1000) not null,
  `pcoo_institucion` varchar(200) not null,
  `ins_id` bigint(20)  null,
  `pcoo_anio` varchar(4) not null,
  `pcoo_usuario_ingreso` bigint(20) not null,
  `pcoo_usuario_modifica` bigint(20)  null,
  `pcoo_estado` varchar(1) not null,
  `pcoo_fecha_creacion` timestamp not null default current_timestamp,
  `pcoo_fecha_modificacion` timestamp null default null,
  `pcoo_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_evaluacion`
--
create table if not exists `profesor_evaluacion` (
  `peva_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `peva_periodo` varchar(100) not null,
  `peva_institucion` varchar(100) not null,
  `peva_evaluacion` varchar(100) not null,
  `peva_usuario_ingreso` bigint(20) not null,
  `peva_usuario_modifica` bigint(20)  null,
  `peva_estado` varchar(1) not null,
  `peva_fecha_creacion` timestamp not null default current_timestamp,
  `peva_fecha_modificacion` timestamp null default null,
  `peva_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);


-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_referencia`
--
create table if not exists `profesor_referencia` (
  `pref_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pref_contacto` varchar(100) not null,
  `pref_relacion_cargo` varchar(100) not null,
  `pref_organizacion` varchar(100) not null,
  `pref_numero` varchar(100) not null,
  `pref_usuario_ingreso` bigint(20) not null,
  `pref_usuario_modifica` bigint(20)  null,
  `pref_estado` varchar(1) not null,
  `pref_fecha_creacion` timestamp not null default current_timestamp,
  `pref_fecha_modificacion` timestamp null default null,
  `pref_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `docente_estudios`
--
create table if not exists `docente_estudios` (
 `dest_id` bigint(20) not null auto_increment primary key,
 `dest_observacion` text default null,
 `dest_estado` varchar(1) not null,
 `dest_fecha_creacion` timestamp not null default current_timestamp,
 `dest_fecha_modificacion` timestamp null default null,
 `dest_estado_logico` varchar(1) not null
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `promocion_programa`
-- --------------------------------------------------------
create table if not exists `promocion_programa` (
 `ppro_id` bigint(20) not null auto_increment primary key,
 `ppro_anio` varchar(4) not null,
 `ppro_mes` varchar(02) not null,
 `ppro_codigo` varchar(20) not null,
 `uaca_id` bigint(20) not null,
 `mod_id` bigint(20) not null,
 `eaca_id` bigint(20) not null,
 `ppro_num_paralelo` integer(2) not null,
 `ppro_cupo` integer(3) not null,
 `ppro_usuario_ingresa` bigint(20) null,
 `ppro_estado` varchar(1) not null,
 `ppro_fecha_creacion` timestamp not null default current_timestamp,
 `ppro_usuario_modifica` bigint(20) null,
 `ppro_fecha_modificacion` timestamp null default null,
 `ppro_estado_logico` varchar(1) not null,
 foreign key (uaca_id) references `unidad_academica`(uaca_id),
 foreign key (mod_id) references `modalidad`(mod_id),
 foreign key (eaca_id) references `estudio_academico`(eaca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `paralelo_promocion_programa`
-- --------------------------------------------------------
create table if not exists `paralelo_promocion_programa` (
 `pppr_id` bigint(20) not null auto_increment primary key,
 `ppro_id` bigint(20) not null,
 `pppr_cupo` integer(3) not null,
 `pppr_cupo_actual` integer(3) null,
 `pppr_descripcion` varchar(100) null,
 `pppr_usuario_ingresa` bigint(20) null,
 `pppr_estado` varchar(1) not null,
 `pppr_fecha_creacion` timestamp not null default current_timestamp,
 `pppr_usuario_modifica` bigint(20) null,
 `pppr_fecha_modificacion` timestamp null default null,
 `pppr_estado_logico` varchar(1) not null,
 foreign key (ppro_id) references `promocion_programa`(ppro_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `planificacion_academica_malla`
--
create table if not exists `planificacion_academica_malla` (
  `pama_id` bigint(20) not null auto_increment primary key,
  `paca_id` bigint(20) null,
  `ppro_id` bigint(20) null,
  `maca_id` bigint(20) not null,
  `pama_fecha_registro` timestamp null default null,
  `pama_usuario_ingreso` bigint(20) not null,
  `pama_usuario_modifica` bigint(20)  null,
  `pama_estado` varchar(1) not null,
  `pama_fecha_creacion` timestamp not null default current_timestamp,
  `pama_fecha_modificacion` timestamp null default null,
  `pama_estado_logico` varchar(1) not null,
  foreign key (paca_id) references `periodo_academico`(paca_id),
  foreign key (ppro_id) references `promocion_programa`(ppro_id),
  foreign key (maca_id) references `malla_academica`(maca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `planifica_academic_malla_det`
--
create table if not exists `planifica_academic_malla_det` (
  `pamd_id` bigint(20) not null auto_increment primary key,
  `pama_id` bigint(20) null,
  `made_id` bigint(20) not null,
  `pamd_usuario_ingreso` bigint(20) not null,
  `pamd_usuario_modifica` bigint(20) null,
  `pamd_estado` varchar(1) not null,
  `pamd_fecha_creacion` timestamp not null default current_timestamp,
  `pamd_fecha_modificacion` timestamp null default null,
  `pamd_estado_logico` varchar(1) not null,
  foreign key (pama_id) references `planificacion_academica_malla`(pama_id),
  foreign key (made_id) references `malla_academica_detalle`(made_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `paralelo_planificacion`
--
create table if not exists `paralelo_planificacion` (
  `ppla_id` bigint(20) not null auto_increment primary key,
  `pamd_id` bigint(20) not null,
  `pppr_id` bigint(20) null,
  `ppla_nombre` varchar(300) not null,
  `ppla_num_cupo` int not null,
  `ppla_usuario_ingreso` bigint(20) not null,
  `ppla_usuario_modifica` bigint(20)  null,
  `ppla_estado` varchar(1) not null,
  `ppla_fecha_creacion` timestamp not null default current_timestamp,
  `ppla_fecha_modificacion` timestamp null default null,
  `ppla_estado_logico` varchar(1) not null,
  foreign key (pamd_id) references `planifica_academic_malla_det`(pamd_id),
  foreign key (pppr_id) references `paralelo_promocion_programa`(pppr_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo_academico_horario`
-- --------------------------------------------------------
create table if not exists `distributivo_academico_horario` (
  `daho_id` bigint(20) not null auto_increment primary key,
  `uaca_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `eaca_id` bigint(20) null,
  `daho_jornada` varchar(1) not null,
  `daho_descripcion` varchar(1000) null, -- aqui esta el dia lunes, martes, etc.
  `daho_horario` varchar(10) null,
  `daho_total_horas` integer(2) null,
  `daho_estado` varchar(1) not null,
  `daho_fecha_creacion` timestamp not null default current_timestamp,
  `daho_fecha_modificacion` timestamp null default null,
  `daho_estado_logico` varchar(1) not null,
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (mod_id) references `modalidad`(mod_id),
  foreign key (eaca_id) references `estudio_academico`(eaca_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_distributivo`
--
create table if not exists `tipo_distributivo` (
 `tdis_id` bigint(20) not null auto_increment primary key,
 `tdis_nombre` varchar(250) default null,
 `tdis_estado` varchar(1) not null,
 `tdis_fecha_creacion` timestamp not null default current_timestamp,
 `tdis_fecha_modificacion` timestamp null default null,
 `tdis_estado_logico` varchar(1) not null
);


create table if not exists db_academico.`distributivo_horario_paralelo` (
  `dhpa_id` bigint(20) not null auto_increment primary key,
  `daho_id` bigint(20) not null,
  `dhpa_grupo` varchar(2) null,
  `dhpa_paralelo` varchar(10) not null,
  `dhpa_usuario_ingreso` bigint(20) not null,
  `dhpa_usuario_modifica` bigint(20) null,
  `dhpa_estado` varchar(1) not null,
  `dhpa_fecha_creacion` timestamp not null default current_timestamp,
  `dhpa_fecha_modificacion` timestamp null default null,
  `dhpa_estado_logico` varchar(1) not null,
  foreign key (daho_id) references `distributivo_academico_horario`(daho_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo_academico`
--
create table if not exists `distributivo_academico` (
  `daca_id` bigint(20) not null auto_increment primary key,
  `paca_id` bigint(20) null,
  `tdis_id` bigint(20) null, -- tipo_distributivo
  `asi_id` bigint(20) null,
  `pro_id` bigint(20) not null,
  `uaca_id` bigint(20) null,
  `mod_id` bigint(20) null,
  `daho_id` bigint(20) null, -- distributivo_academico_horario para sacar los dias
  `dhpa_id` bigint(20) null, -- parelo segun horario
  `daca_num_estudiantes_online` integer(3) null,
  `daca_fecha_registro` timestamp null default null,
  `daca_usuario_ingreso` bigint(20) not null,
  `daca_usuario_modifica` bigint(20)  null,
  `daca_estado` varchar(1) not null,
  `daca_fecha_creacion` timestamp not null default current_timestamp,
  `daca_fecha_modificacion` timestamp null default null,
  `daca_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (paca_id) references `periodo_academico`(paca_id),
  foreign key (asi_id) references `asignatura`(asi_id),
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (mod_id) references `modalidad`(mod_id),
  foreign key (daho_id) references `distributivo_academico_horario`(daho_id),
  foreign key (tdis_id) references `tipo_distributivo`(tdis_id),
  foreign key (dhpa_id) references `distributivo_horario_paralelo`(dhpa_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo_horario`
--
/*create table if not exists `distributivo_horario` (
  `dhor_id` bigint(20) not null auto_increment primary key,
  `ppla_id` bigint(20) null,
  `dhor_usuario_ingreso` bigint(20) not null,
  `dhor_usuario_modifica` bigint(20)  null,
  `dhor_estado` varchar(1) not null,
  `dhor_fecha_creacion` timestamp not null default current_timestamp,
  `dhor_fecha_modificacion` timestamp null default null,
  `dhor_estado_logico` varchar(1) not null,
  foreign key (ppla_id) references `paralelo_planificacion`(ppla_id)
);*/

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo_horario_det`
--
create table if not exists `distributivo_horario_det` (
  `dhde_id` bigint(20) not null auto_increment primary key,
  `daho_id` bigint(20) not null,
  `dia_id` bigint(20) not null,
  `dhde_hora_inicio` varchar(10) not null,
  `dhde_hora_fin` varchar(10) not null,
  `dhde_usuario_ingreso` bigint(20) not null,
  `dhde_usuario_modifica` bigint(20) null,
  `dhde_estado` varchar(1) not null,
  `dhde_fecha_creacion` timestamp not null default current_timestamp,
  `dhde_fecha_modificacion` timestamp null default null,
  `dhde_estado_logico` varchar(1) not null,
  foreign key (daho_id) references `distributivo_academico_horario`(daho_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `marcacion_detalle_horario`
-- --------------------------------------------------------
/*create table if not exists `marcacion_detalle_horario` (
  `mdho_id` bigint(20) not null auto_increment primary key,
  `dhde_id` bigint(20) not null,
  `mdho_fecha_hora_entrada` timestamp null,
  `mdho_fecha_hora_salida` timestamp null,
  `mdho_direccion_ip` varchar(20) not null,
  `mdho_usuario_ingreso` bigint(20) not null,
  `mdho_usuario_modifica` bigint(20) null,
  `mdho_estado` varchar(1) not null,
  `mdho_fecha_creacion` timestamp not null default current_timestamp,
  `mdho_fecha_modificacion` timestamp null default null,
  `mdho_estado_logico` varchar(1) not null,
  foreign key (dhde_id) references `distributivo_horario_det`(dhde_id)
);*/

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `matriculacion`
--
create table if not exists `matriculacion` (
  `mat_id` bigint(20) not null auto_increment primary key,
  `pama_id` bigint(20) not null,
  `adm_id` bigint(20) null,
  `est_id` bigint(20) not null,
  `mat_fecha_matriculacion` timestamp null default null,
  `mat_usuario_ingreso` bigint(20) not null,
  `mat_usuario_modifica` bigint(20)  null,
  `mat_estado` varchar(1) not null,
  `mat_fecha_creacion` timestamp not null default current_timestamp,
  `mat_fecha_modificacion` timestamp null default null,
  `mat_estado_logico` varchar(1) not null,
  foreign key (pama_id) references `planificacion_academica_malla`(pama_id),
  foreign key (est_id) references `estudiante`(est_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_matriculacion`
--
create table if not exists `detalle_matriculacion` (
  `dmat_id` bigint(20) not null auto_increment primary key,
  `mat_id` bigint(20) not null,
  `ppla_id` bigint(20) not null,
  `dmat_usuario_ingreso` bigint(20) not null,
  `dmat_usuario_modifica` bigint(20)  null,
  `dmat_estado` varchar(1) not null,
  `dmat_fecha_creacion` timestamp not null default current_timestamp,
  `dmat_fecha_modificacion` timestamp null default null,
  `dmat_estado_logico` varchar(1) not null,
  foreign key (mat_id) references `matriculacion`(mat_id),
  foreign key (ppla_id) references `paralelo_planificacion`(ppla_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `estudio_acad_area_con`
-- --------------------------------------------------------
create table if not exists `estudio_acad_area_con` (
  `eaac_id` bigint(20) not null auto_increment primary key,
  `eaca_id` bigint(20) null,
  `mest_id` bigint(20) null,
  `acon_id` bigint(20) not null,
  `eaac_estado` varchar(1) not null,
  `eaac_fecha_creacion` timestamp not null default current_timestamp,
  `eaac_fecha_modificacion` timestamp null default null,
  `eaac_estado_logico` varchar(1) not null,
  foreign key (eaca_id) references `estudio_academico`(eaca_id),
  foreign key (mest_id) references `modulo_estudio`(mest_id),
  foreign key (acon_id) references `area_conocimiento`(acon_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `horario_asignatura_periodo`  ELIMINAR DESPUÉS DE IMPLEMENTAR LO NUEVO en detalle_horario
-- --------------------------------------------------------
create table if not exists `horario_asignatura_periodo` (
  `hape_id` bigint(20) not null auto_increment primary key,
  `asi_id` bigint(20) not null,
  `paca_id` bigint(20) null,
  `ppro_id` bigint(20) null,
  `daca_id` bigint(20) null,
  `pro_id` bigint(20) not null,
  `uaca_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `dia_id` bigint(20) not null,
  `hape_fecha_clase` timestamp null default null,
  `hape_hora_entrada` varchar(10) not null,
  `hape_hora_salida` varchar(10) not null,
  `hape_estado` varchar(1) not null,
  `hape_fecha_creacion` timestamp not null default current_timestamp,
  `hape_fecha_modificacion` timestamp null default null,
  `hape_estado_logico` varchar(1) not null,
  foreign key (asi_id) references `asignatura`(asi_id),
  foreign key (paca_id) references `periodo_academico`(paca_id),
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (mod_id) references `modalidad`(mod_id),
  foreign key (daca_id) REFERENCES `distributivo_academico`(daca_id),
  foreign key (ppro_id) REFERENCES `promocion_programa`(ppro_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_marcacion` ELIMINAR DESPUÉS DE IMPLEMENTAR LO NUEVO en marcacion_detalle_horario
-- --------------------------------------------------------
create table if not exists `registro_marcacion` (
  `rmar_id` bigint(20) not null auto_increment primary key,
  `rmar_tipo` varchar(1) not null, -- 'E' entrada clases 'S' salida de clases
  `pro_id` bigint(20) not null,
  `hape_id` bigint(20) not null,
  `rmar_fecha_hora_entrada` timestamp null,
  `rmar_fecha_hora_salida` timestamp null,
  `rmar_direccion_ip` varchar(20) not null,
  `rmar_direccion_ipsalida` varchar(20) null,
  `usu_id` bigint(20) not null,
  `rmar_idingreso`  bigint(20) null,
  `rmar_estado` varchar(1) not null,
  `rmar_fecha_creacion` timestamp not null default current_timestamp,
  `rmar_fecha_modificacion` timestamp null default null,
  `rmar_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (hape_id) references `horario_asignatura_periodo`(hape_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `horario_asignatura_periodo_tmp`  ELIMINAR DESPUÉS DE IMPLEMENTAR LO NUEVO en detalle_horario
-- --------------------------------------------------------
create table if not exists `horario_asignatura_periodo_tmp` (
  `hapt_id` bigint(20) not null auto_increment primary key,
  `asi_id` bigint(20) null,
  `paca_id` bigint(20) null,
  `pro_id` bigint(20) null,
  `uaca_id` bigint(20) null,
  `mod_id` bigint(20) null,
  `dia_id` bigint(20) null,
  `hapt_fecha_clase` varchar(10) null,
  `hapt_hora_entrada` varchar(10) null,
  `hapt_hora_salida` varchar(10) null,
  `usu_id` bigint(20) null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `otro_estudio_academico`
-- --------------------------------------------------------
CREATE TABLE `otro_estudio_academico` (
  `oeac_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `oeac_nombre` varchar(300) NOT NULL,
  `oeac_descripcion` varchar(500) NOT NULL,
  `uaca_id` bigint(20) NOT NULL,
  `mod_id` bigint(20) NOT NULL,
  `oeac_estado` varchar(1) NOT NULL,
  `oeac_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oeac_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `oeac_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`oeac_id`),
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (mod_id) references `modalidad`(mod_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `documento_aceptacion`
-- --------------------------------------------------------
create table if not exists `documento_aceptacion` (
 `dace_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `dadj_id` bigint(20) not null,
 `dace_archivo` varchar(500) not null,
 `dace_observacion` varchar(500) null,
 `dace_fecha_maxima_aprobacion` timestamp null,
 `dace_estado_aprobacion` varchar(1) not null,
 `dace_usuario_ingreso` bigint(20) null,
 `dace_usuario_modifica` bigint(20) null,
 `dace_estado` varchar(1) not null,
 `dace_fecha_creacion` timestamp not null default current_timestamp,
 `dace_fecha_modificacion` timestamp null default null,
 `dace_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `observaciones_documento_aceptacion`
-- --------------------------------------------------------
create table if not exists `observaciones_documento_aceptacion` (
 `odac_id` bigint(20) not null auto_increment primary key,
 `odac_descripcion` varchar(500) not null,
 `odac_usuario_ingreso` bigint(20) null,
 `odac_usuario_modifica` bigint(20) null,
 `odac_estado` varchar(1) not null,
 `odac_fecha_creacion` timestamp not null default current_timestamp,
 `odac_fecha_modificacion` timestamp null default null,
 `odac_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `observaciones_documento_aceptacion`
-- --------------------------------------------------------
create table if not exists `observaciones_por_documento_aceptacion` (
 `opda_id` bigint(20) not null auto_increment primary key,
 `odac_id` bigint(20) not null,
 `dace_id` bigint(20) not null,
 `opda_usuario_ingreso` bigint(20) null,
 `opda_usuario_modifica` bigint(20) null,
 `opda_estado` varchar(1) not null,
 `opda_fecha_creacion` timestamp not null default current_timestamp,
 `opda_fecha_modificacion` timestamp null default null,
 `opda_estado_logico` varchar(1) not null,
 foreign key (odac_id) references `observaciones_documento_aceptacion`(odac_id),
 foreign key (dace_id) references `documento_aceptacion`(dace_id)
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `matriculacion_programa_inscrito`
-- --------------------------------------------------------
create table if not exists `matriculacion_programa_inscrito` (
 `mpin_id` bigint(20) not null auto_increment primary key,
 -- `ppro_id` bigint(20) not null,
 `pppr_id` bigint(20) not null,
 `adm_id` bigint(20) not null,
 `est_id` bigint(20) not null,
 `mpin_fecha_matriculacion` timestamp not null,
 `mpin_ficha` varchar(1) null, -- 'S', 'N'
 `mpin_fecha_registro_ficha` timestamp null,
 `mpin_usuario_ingresa` bigint(20) null,
 `mpin_estado` varchar(1) not null,
 `mpin_fecha_creacion` timestamp not null default current_timestamp,
 `mpin_usuario_modifica` bigint(20) null,
 `mpin_fecha_modificacion` timestamp null default null,
 `mpin_estado_logico` varchar(1) not null,
 -- foreign key (ppro_id) references `promocion_programa`(ppro_id),
 foreign key (pppr_id) references `paralelo_promocion_programa`(pppr_id),
 foreign key (est_id) references `estudiante`(est_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_marcacion_generada`
-- --------------------------------------------------------
create table if not exists registro_marcacion_generada (
  `rmtm_id` bigint(20) not null auto_increment primary key,
  `hape_id` bigint(20) not null,
  `paca_id` bigint(20) not null,
  `uaca_id` bigint(20) not null,
  `mod_id` bigint(20)  null,
  `rmtm_fecha_transaccion` timestamp null default null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo`
--
create table if not exists `distributivo` (
 `dis_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `asi_id` bigint(20) not null,
 `dis_descripcion` varchar(100) null,
 `saca_id` bigint(20) not null,
 `ddoc_id` bigint(20) not null,
 `dis_declarado` varchar(1) not null,
 `dis_estado` varchar(1) not null,
 `dis_fecha_creacion` timestamp not null default current_timestamp,
 `dis_fecha_modificacion` timestamp null default null,
 `dis_estado_logico` varchar(1) not null,
 foreign key (saca_id) references `semestre_academico`(saca_id),
 foreign key (pro_id) references `profesor`(pro_id),
 foreign key (asi_id) references `asignatura`(asi_id),
 foreign key (ddoc_id) references `dedicacion_docente`(ddoc_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_evaluacion`
--
create table if not exists `tipo_evaluacion` (
 `teva_id` bigint(20) not null auto_increment primary key,
 `teva_nombre` varchar(250) default null,
 `teva_estado` varchar(1) not null,
 `teva_fecha_creacion` timestamp not null default current_timestamp,
 `teva_fecha_modificacion` timestamp null default null,
 `teva_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `resumen_evaluacion_docente`
--
create table if not exists `resumen_evaluacion_docente` (
 `redo_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `saca_id` bigint(20) not null,
 `teva_id` bigint(20) not null,
 `redo_cant_horas` double default null,
 `redo_puntaje_evaluacion` double default null,
 `redo_estado` varchar(1) not null,
 `redo_fecha_creacion` timestamp not null default current_timestamp,
 `redo_fecha_modificacion` timestamp null default null,
 `redo_estado_logico` varchar(1) not null,
 foreign key (saca_id) references `semestre_academico`(saca_id),
 foreign key (pro_id) references `profesor`(pro_id),
 foreign key (teva_id) references `tipo_evaluacion`(teva_id)
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `resumen_resultado_evaluacion`
--
create table if not exists `resumen_resultado_evaluacion` (
 `rreva_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `saca_id` bigint(20) not null,
 `rreva_evaluacion_completa` varchar(1) not null, -- 1 completa o 0 incompleta
 `rreva_total_hora` double default null,
 `rreva_total_evaluacion` double default null,
 `rreva_estado` varchar(1) not null,
 `rreva_fecha_creacion` timestamp not null default current_timestamp,
 `rreva_fecha_modificacion` timestamp null default null,
 `rreva_estado_logico` varchar(1) not null,
 foreign key (saca_id) references `semestre_academico`(saca_id),
 foreign key (pro_id) references `profesor`(pro_id)
);



-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo_carga_horaria`
--
create table if not exists `distributivo_carga_horaria` (
 `dcho_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `tdis_id` bigint(20) not null,
 `saca_id` bigint(20) not null,
 `dcho_horas` int default null,
 `dcho_estado` varchar(1) not null,
 `dcho_fecha_creacion` timestamp not null default current_timestamp,
 `dcho_fecha_modificacion` timestamp null default null,
 `dcho_estado_logico` varchar(1) not null,
 foreign key (saca_id) references `semestre_academico`(saca_id),
 foreign key (pro_id) references `profesor`(pro_id),
 foreign key (tdis_id) references `tipo_distributivo`(tdis_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `control_data`
-- --------------------------------------------------------
create table if not exists `control_catedra` (
  `ccat_id` bigint(20) not null auto_increment primary key,
  `hape_id` bigint(20) not null,
  `eaca_id` bigint(20) null,
  `ccat_fecha_registro` timestamp null,
  `ccat_titulo_unidad` varchar(500) not null,
  `ccat_tema` varchar(2000) not null,
  `ccat_trabajo_autopractico` varchar(2000) not null,
  `ccat_logro_aprendizaje` varchar(2000) not null,
  `ccat_observacion` varchar(2000) null,
  `ccat_direccion_ip` varchar(20) null,
  `usu_id` bigint(20) not null,
  `ccat_estado` varchar(1) not null,
  `ccat_fecha_creacion` timestamp not null default current_timestamp,
  `ccat_fecha_modificacion` timestamp null default null,
  `ccat_estado_logico` varchar(1) not null,
  foreign key (hape_id) references `horario_asignatura_periodo`(hape_id),
  foreign key (eaca_id) references `estudio_academico`(eaca_id)
);

--
-- Estructura de tabla para la tabla `actividad_evaluacion`
-- --------------------------------------------------------
create table if not exists `actividad_evaluacion` (
  `aeva_id` bigint(20) not null auto_increment primary key,
  `aeva_descripcion` varchar(500) not null,
  `aeva_nombre` varchar(500) not null,
  `aeva_estado` varchar(1) not null,
  `aeva_fecha_creacion` timestamp not null default current_timestamp,
  `aeva_fecha_modificacion` timestamp null default null,
  `aeva_estado_logico` varchar(1) not null
);

--
-- Estructura de tabla para la tabla `valor_desarrollo`
-- --------------------------------------------------------
create table if not exists `valor_desarrollo` (
  `vdes_id` bigint(20) not null auto_increment primary key,
  `vdes_descripcion` varchar(500) not null,
  `vdes_nombre` varchar(500) not null,
  `vdes_estado` varchar(1) not null,
  `vdes_fecha_creacion` timestamp not null default current_timestamp,
  `vdes_fecha_modificacion` timestamp null default null,
  `vdes_estado_logico` varchar(1) not null
);

--
-- Estructura de tabla para la tabla `detalle_catedra_actividad`
-- --------------------------------------------------------
create table if not exists `detalle_catedra_actividad` (
  `dcac_id` bigint(20) not null auto_increment primary key,
  `ccat_id` bigint(20) not null,
  `aeva_id` bigint(20) not null,
  `aeva_otro` varchar(1000) null,
  `dcac_estado` varchar(1) not null,
  `dcac_fecha_creacion` timestamp not null default current_timestamp,
  `dcac_fecha_modificacion` timestamp null default null,
  `dcac_estado_logico` varchar(1) not null,
  foreign key (ccat_id) references `control_catedra`(ccat_id)
);

--
-- Estructura de tabla para la tabla `detalle_valor_desarrollo`
-- --------------------------------------------------------
create table if not exists `detalle_valor_desarrollo` (
  `dvde_id` bigint(20) not null auto_increment primary key,
  `ccat_id` bigint(20) not null,
  `vdes_id` bigint(20) not null,
  `vdes_otro`  varchar(1000) null,
  `dvde_estado` varchar(1) not null,
  `dvde_fecha_creacion` timestamp not null default current_timestamp,
  `dvde_fecha_modificacion` timestamp null default null,
  `dvde_estado_logico` varchar(1) not null,
  foreign key (ccat_id) references `control_catedra`(ccat_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `estudiante_carrera_programa
--
create table if not exists db_academico.`estudiante_carrera_programa` (
  `ecpr_id` bigint(20) not null auto_increment primary key,
  `est_id` bigint(20) not null,
  `meun_id` bigint(20) not null,
  `ecpr_fecha_registro` timestamp null default null,
  `ecpr_usuario_ingreso` bigint(20) not null,
  `ecpr_usuario_modifica` bigint(20)  null,
  `ecpr_estado` varchar(1) not null,
  `ecpr_fecha_creacion` timestamp not null default current_timestamp,
  `ecpr_fecha_modificacion` timestamp null default null,
  `ecpr_estado_logico` varchar(1) not null,
  foreign key (est_id) references `estudiante`(est_id),
  foreign key (meun_id) references `modalidad_estudio_unidad`(meun_id)
);


-- -------------------------------- TABLAS DE PROCESO DE REGISTRO EN LINEA -------------------------------------

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `planificacion`
-- --------------------------------------------------------
create table if not exists `planificacion` (
 `pla_id` bigint(20) not null auto_increment primary key,
 `mod_id` bigint(20) not null,
 `per_id` bigint(20) not null, -- persona que sube la informacion
 `pla_fecha_inicio` timestamp not null,
 `pla_fecha_fin` timestamp not null,
 `pla_periodo_academico` varchar(100) null,
 `pla_path` text null,
 `pla_estado` varchar(1) not null,
 `pla_fecha_creacion` timestamp not null default current_timestamp,
 `pla_usuario_modifica` bigint(20) null,
 `pla_fecha_modificacion` timestamp null default null,
 `pla_estado_logico` varchar(1) not null,
 foreign key (mod_id) references `modalidad`(mod_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_configuracion`
-- --------------------------------------------------------
create table if not exists `registro_configuracion` (
 `rco_id` bigint(20) not null auto_increment primary key,
 `pla_id` bigint(20) not null,
 `rco_fecha_inicio` timestamp null default null,
 `rco_fecha_fin` timestamp null default null,

 `rco_fecha_ini_aplicacion` timestamp null default null,
 `rco_fecha_fin_aplicacion` timestamp null default null,
 `rco_fecha_ini_registro` timestamp null default null,
 `rco_fecha_fin_registro` timestamp null default null,
 `rco_fecha_ini_periodoextra` timestamp null default null,
 `rco_fecha_fin_periodoextra` timestamp null default null,
 `rco_fecha_ini_clases` timestamp null default null,
 `rco_fecha_fin_clases` timestamp null default null,
 `rco_fecha_ini_examenes` timestamp null default null,
 `rco_fecha_fin_examenes` timestamp null default null,

 `rco_num_bloques` int not null,
 `rco_estado` varchar(1) not null,
 `rco_fecha_creacion` timestamp not null default current_timestamp,
 `rco_usuario_modifica` bigint(20) null,
 `rco_fecha_modificacion` timestamp null default null,
 `rco_estado_logico` varchar(1) not null,
 foreign key (pla_id) references `planificacion`(pla_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `planificacion_estudiante`
-- --------------------------------------------------------
create table if not exists `planificacion_estudiante` (
 `pes_id` bigint(20) not null auto_increment primary key,
 `pla_id` bigint(20) not null,
 `per_id` bigint(20) not null, -- id de la persona/estudiante
 `pes_jornada` varchar(3) null,
 `pes_cod_carrera` varchar(20) null,
 `pes_carrera` varchar(100) null,
 `pes_dni` varchar(15) null,
 `pes_nombres` varchar(200) null,
 `pes_egresado` varchar(1) null,
 `pes_tutoria_nombre` varchar(100) null,
 `pes_tutoria_cod` varchar(20) null,
 `pes_cod_malla`varchar(50) null,
 `pes_mat_b1_h1_nombre` varchar(100) null,
 `pes_mat_b1_h1_cod` varchar(20) null,
 `pes_mod_b1_h1` varchar(2) null,
 `pes_jor_b1_h1` varchar(20) null,
 `pes_mat_b1_h2_nombre` varchar(100) null,
 `pes_mat_b1_h2_cod` varchar(20) null,
 `pes_mod_b1_h2` varchar(2) null,
 `pes_jor_b1_h2` varchar(20) null,
 `pes_mat_b1_h3_nombre` varchar(100) null,
 `pes_mat_b1_h3_cod` varchar(20) null,
 `pes_mod_b1_h3` varchar(2) null,
 `pes_jor_b1_h3` varchar(20) null,
 `pes_mat_b1_h4_nombre` varchar(100) null,
 `pes_mat_b1_h4_cod` varchar(20) null,
 `pes_mod_b1_h4` varchar(2) null,
 `pes_jor_b1_h4` varchar(20) null,
 `pes_mat_b1_h5_nombre` varchar(100) null,
 `pes_mat_b1_h5_cod` varchar(20) null,
 `pes_mod_b1_h5` varchar(2) null,
 `pes_jor_b1_h5` varchar(20) null,
 `pes_mat_b1_h6_nombre` varchar(100) null,
 `pes_mat_b1_h6_cod` varchar(20) null,
 `pes_mod_b1_h6` varchar(2) null,
 `pes_jor_b1_h6` varchar(20) null,
 `pes_mat_b2_h1_nombre` varchar(100) null,
 `pes_mat_b2_h1_cod` varchar(20) null,
 `pes_mod_b2_h1` varchar(2) null,
 `pes_jor_b2_h1` varchar(20) null,
 `pes_mat_b2_h2_nombre` varchar(100) null,
 `pes_mat_b2_h2_cod` varchar(20) null,
 `pes_mod_b2_h2` varchar(2) null,
 `pes_jor_b2_h2` varchar(20) null,
 `pes_mat_b2_h3_nombre` varchar(100) null,
 `pes_mat_b2_h3_cod` varchar(20) null,
 `pes_mod_b2_h3` varchar(2) null,
 `pes_jor_b2_h3` varchar(20) null,
 `pes_mat_b2_h4_nombre` varchar(100) null,
 `pes_mat_b2_h4_cod` varchar(20) null,
 `pes_mod_b2_h4` varchar(2) null,
 `pes_jor_b2_h4` varchar(20) null,
 `pes_mat_b2_h5_nombre` varchar(100) null,
 `pes_mat_b2_h5_cod` varchar(20) null,
 `pes_mod_b2_h5` varchar(2) null,
 `pes_jor_b2_h5` varchar(20) null,
 `pes_mat_b2_h6_nombre` varchar(100) null,
 `pes_mat_b2_h6_cod` varchar(20) null,
 `pes_mod_b2_h6` varchar(2) null,
 `pes_jor_b2_h6` varchar(20) null,
 `pes_estado` varchar(1) not null,
 `pes_fecha_creacion` timestamp not null default current_timestamp,
 `pes_usuario_modifica` bigint(20) null,
 `pes_fecha_modificacion` timestamp null default null,
 `pes_estado_logico` varchar(1) not null,
 foreign key (pla_id) references `planificacion`(pla_id)
);

create table if not exists `registro_pago_matricula` (
  `rpm_id` bigint(20) not null auto_increment primary key,
  `per_id` bigint(20) not null,
  `pla_id` bigint(20) not null,
  `rpm_archivo` text not null,
  `rpm_estado_aprobacion` varchar(1) not null default "0",  /** 0->No revisado, 1->Aprobado, 2->Rechazado */
  `rpm_estado_generado` varchar(1) not null default "0", /**0->No generado, 1->Generado */
  `rpm_hoja_matriculacion` varchar(200) null,
  `rpm_usuario_apruebareprueba` bigint(20) null,
  `rpm_fecha_transaccion`  timestamp null default null,
  `rpm_observacion` varchar(1000) null,
  `rpm_estado` varchar(1) not null,
  `rpm_fecha_creacion` timestamp not null default current_timestamp,
  `rpm_usuario_modifica` bigint(20) null,
  `rpm_fecha_modificacion` timestamp null default null,
  `rpm_estado_logico` varchar(1) not null,
  foreign key (pla_id) references `planificacion`(pla_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_online`
-- --------------------------------------------------------
create table if not exists `registro_online` (
 `ron_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `pes_id` bigint(20) not null,
 `ron_num_orden` bigint(20) not null,
 `ron_fecha_registro` timestamp not null,
 `ron_anio` varchar(4) null, -- anio actual
 `ron_semestre` varchar(1) null, -- este parametro se debe obtener del numero del semestre en curso
 `ron_modalidad` varchar(80) null, -- modalidad que se obtiene de las tablas planificacion_estudiante -> planificacion -> modalidad
 `ron_carrera` varchar(500) null, -- carrera se debe obtener de la tabla planificacion_estudiante
 `ron_categoria_est` varchar(2) null, -- categoria del estudiante que se debe obtener de la base de datos utegsiga
 `ron_valor_arancel` decimal(10,2) DEFAULT NULL, -- valor que se obtiene por la categoria del estudiante
 `ron_valor_matricula` decimal(10,2) DEFAULT NULL,
 `ron_valor_gastos_adm` decimal(10,2) DEFAULT NULL,
 `ron_valor_gastos_pendientes` decimal(10,2) DEFAULT NULL,
 `ron_valor_aso_estudiante` decimal(10,2) DEFAULT NULL,
 `ron_estado_registro` varchar(1) not null, -- 1 registrado, 0 aun no registrado
 `ron_estado` varchar(1) not null,
 `ron_fecha_creacion` timestamp not null default current_timestamp,
 `ron_usuario_modifica` bigint(20) null,
 `ron_fecha_modificacion` timestamp null default null,
 `ron_estado_logico` varchar(1) not null,
 foreign key (pes_id) references `planificacion_estudiante`(pes_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_online_item`
-- --------------------------------------------------------
create table if not exists `registro_online_item` (
 `roi_id` bigint(20) not null auto_increment primary key,
 `ron_id` bigint(20) not null,
 `roi_materia_cod` varchar(50) null,
 `roi_materia_nombre` varchar(100) null,
 `roi_creditos` varchar(4) null,
 `roi_costo` decimal(10,2) DEFAULT NULL,
 `roi_bloque` varchar(4) DEFAULT null,
 `roi_hora` varchar(4) DEFAULT null,
 `roi_estado` varchar(1) not null,
 `roi_fecha_creacion` timestamp not null default current_timestamp,
 `roi_usuario_modifica` bigint(20) null,
 `roi_fecha_modificacion` timestamp null default null,
 `roi_estado_logico` varchar(1) not null,
 foreign key (ron_id) references `registro_online`(ron_id)
);

CREATE TABLE `registro_adicional_materias` (
  `rama_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ron_id` bigint(20) NOT NULL,
  `per_id` bigint(20) NOT NULL,
  `pla_id` bigint(20) NOT NULL,
  `paca_id` bigint(20) NOT NULL,
  `rpm_id` bigint(20) DEFAULT NULL,
  `roi_id_1` bigint(20) DEFAULT NULL,
  `roi_id_2` bigint(20) DEFAULT NULL,
  `roi_id_3` bigint(20) DEFAULT NULL,
  `roi_id_4` bigint(20) DEFAULT NULL,
  `roi_id_5` bigint(20) DEFAULT NULL,
  `roi_id_6` bigint(20) DEFAULT NULL,
  `rama_estado` varchar(1) NOT NULL,
  `rama_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rama_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `rama_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`rama_id`),
  KEY `ron_id` (`ron_id`),
  CONSTRAINT `registro_adicional_materias_ibfk_1` FOREIGN KEY (`ron_id`) REFERENCES `registro_online` (`ron_id`)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `registro_online_cuota`
-- --------------------------------------------------------
create table if not exists `registro_online_cuota` (
 `roc_id` bigint(20) not null auto_increment primary key,
 `ron_id` bigint(20) not null,
 `roc_num_cuota` varchar(50) null,
 `roc_vencimiento` varchar(100) null,
 `roc_porcentaje` varchar(10) null,
 `roc_costo` decimal(10,2) DEFAULT NULL,
 `roc_estado` varchar(1) not null,
 `roc_fecha_creacion` timestamp not null default current_timestamp,
 `roc_usuario_modifica` bigint(20) null,
 `roc_fecha_modificacion` timestamp null default null,
 `roc_estado_logico` varchar(1) not null,
 foreign key (ron_id) references `registro_online`(ron_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `modalidad_centro_costo`
-- --------------------------------------------------------
create table if not exists `modalidad_centro_costo` (
 `mcco_id` bigint(20) not null auto_increment primary key,
 `mod_id` bigint(20) not null,
 `mcco_code` varchar(50) null,
 `mcco_estado` varchar(1) not null,
 `mcco_fecha_creacion` timestamp not null default current_timestamp,
 `mcco_usuario_modifica` bigint(20) null,
 `mcco_fecha_modificacion` timestamp null default null,
 `mcco_estado_logico` varchar(1) not null,
 foreign key (mod_id) references `modalidad`(mod_id)
);

/* Especies */

CREATE TABLE `responsable_especie` (
  `resp_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `resp_nombre` varchar(300) NOT NULL,
  `resp_titulo` varchar(300) NOT NULL,
  `resp_cargo` varchar(500) NOT NULL,
  `uaca_id` bigint(20) NOT NULL,
  `mod_id` bigint(20) NOT NULL,
  `resp_usuario_ingreso` bigint(20) DEFAULT NULL,
  `resp_usuario_modifica` bigint(20) DEFAULT NULL,
  `resp_estado` varchar(1) NOT NULL,
  `resp_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resp_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `resp_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`resp_id`)
);

CREATE TABLE `tramite` (
  `tra_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uaca_id` bigint(20) NOT NULL,
  `tra_nombre` varchar(300) NOT NULL,
  `tra_nomenclatura` varchar(3) NOT NULL,
  `tra_descripcion` varchar(500) DEFAULT NULL,
  `tra_usuario_ingreso` bigint(20) DEFAULT NULL,
  `tra_usuario_modifica` bigint(20) DEFAULT NULL,
  `tra_estado` varchar(1) NOT NULL,
  `tra_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tra_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `tra_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`tra_id`)
);


CREATE TABLE `especies` (
  `esp_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tra_id` bigint(20) NOT NULL,
  `esp_codigo` int(3) NOT NULL,
  `esp_rubro` varchar(300) NOT NULL,
  `esp_valor` decimal(12,2) DEFAULT NULL,
  `esp_emision_certificado` varchar(2) DEFAULT NULL,
  `esp_departamento` varchar(100) DEFAULT NULL,
  `esp_dia_vigencia` int(2) DEFAULT NULL,
  `esp_numero` varchar(10) DEFAULT NULL,
  `esp_usuario_ingreso` bigint(20) DEFAULT NULL,
  `esp_usuario_modifica` bigint(20) DEFAULT NULL,
  `esp_estado` varchar(1) NOT NULL,
  `esp_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `esp_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `esp_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`esp_id`),
  KEY `tra_id` (`tra_id`),
  CONSTRAINT `especies_ibfk_1` FOREIGN KEY (`tra_id`) REFERENCES `tramite` (`tra_id`)
);



CREATE TABLE `cabecera_solicitud` (
  `csol_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empid` bigint(20) NOT NULL,
  `est_id` bigint(20) NOT NULL,
  `uaca_id` bigint(20) NOT NULL,
  `mod_id` bigint(20) NOT NULL,
  `fpag_id` bigint(20) NOT NULL,
  `csol_total` decimal(12,2) DEFAULT NULL,
  `csol_observacion` text,
  `csol_fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `csol_fecha_caducidad` date DEFAULT NULL,
  `csol_estado_aprobacion` varchar(1) DEFAULT NULL,
  `csol_ruta_archivo_pago` varchar(500) DEFAULT NULL,
  `csol_usuario_ingreso` bigint(20) DEFAULT NULL,
  `csol_usuario_modifica` bigint(20) DEFAULT NULL,
  `csol_estado` varchar(1) DEFAULT NULL,
  `csol_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `csol_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `csol_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`csol_id`),
  KEY `mod_id` (`mod_id`),
  KEY `uaca_id` (`uaca_id`),
  KEY `est_id` (`est_id`),
  CONSTRAINT `cabecera_solicitud_ibfk_1` FOREIGN KEY (`mod_id`) REFERENCES `modalidad` (`mod_id`),
  CONSTRAINT `cabecera_solicitud_ibfk_2` FOREIGN KEY (`uaca_id`) REFERENCES `unidad_academica` (`uaca_id`),
  CONSTRAINT `cabecera_solicitud_ibfk_3` FOREIGN KEY (`est_id`) REFERENCES `estudiante` (`est_id`)
);



CREATE TABLE `detalle_solicitud` (
  `dsol_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `csol_id` bigint(20) NOT NULL,
  `tra_id` bigint(20) NOT NULL,
  `esp_id` bigint(20) NOT NULL,
  `est_id` bigint(20) NOT NULL,
  `dsol_cantidad` decimal(12,2) DEFAULT NULL,
  `dsol_valor` decimal(12,2) DEFAULT NULL,
  `dsol_total` decimal(12,2) DEFAULT NULL,
  `dsol_observacion` text,
  `dsol_archivo_extra` varchar(500) DEFAULT NULL,
  `dsol_usuario_ingreso` bigint(20) NOT NULL,
  `dsol_usuario_modifica` bigint(20) DEFAULT NULL,
  `dsol_estado` varchar(1) DEFAULT NULL,
  `dsol_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dsol_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `dsol_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`dsol_id`),
  KEY `csol_id` (`csol_id`),
  KEY `tra_id` (`tra_id`),
  KEY `esp_id` (`esp_id`),
  KEY `est_id` (`est_id`),
  CONSTRAINT `detalle_solicitud_ibfk_1` FOREIGN KEY (`csol_id`) REFERENCES `cabecera_solicitud` (`csol_id`),
  CONSTRAINT `detalle_solicitud_ibfk_2` FOREIGN KEY (`tra_id`) REFERENCES `tramite` (`tra_id`),
  CONSTRAINT `detalle_solicitud_ibfk_3` FOREIGN KEY (`esp_id`) REFERENCES `especies` (`esp_id`),
  CONSTRAINT `detalle_solicitud_ibfk_4` FOREIGN KEY (`est_id`) REFERENCES `estudiante` (`est_id`)
);

CREATE TABLE `especies_generadas` (
  `egen_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dsol_id` bigint(20) NOT NULL,
  `empid` bigint(20) NOT NULL,
  `est_id` bigint(20) NOT NULL,
  `resp_id` bigint(20) NOT NULL,
  `tra_id` bigint(20) NOT NULL,
  `esp_id` bigint(20) NOT NULL,
  `uaca_id` bigint(20) NOT NULL,
  `mod_id` bigint(20) NOT NULL,
  `fpag_id` bigint(20) NOT NULL,
  `egen_certificado` varchar(2) DEFAULT NULL,
  `egen_numero_solicitud` varchar(10) DEFAULT NULL,
  `egen_observacion` text,
  `egen_fecha_solicitud` timestamp NULL DEFAULT NULL,
  `egen_fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `egen_fecha_caducidad` date DEFAULT NULL,
  `egen_estado_aprobacion` varchar(1) DEFAULT NULL,
  `egen_ruta_archivo_pago` varchar(500) DEFAULT NULL,
  `egen_usuario_ingreso` bigint(20) DEFAULT NULL,
  `egen_usuario_modifica` bigint(20) DEFAULT NULL,
  `egen_estado` varchar(1) DEFAULT NULL,
  `egen_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `egen_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `egen_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`egen_id`),
  KEY `tra_id` (`tra_id`),
  KEY `esp_id` (`esp_id`),
  KEY `mod_id` (`mod_id`),
  KEY `uaca_id` (`uaca_id`),
  KEY `dsol_id` (`dsol_id`),
  KEY `resp_id` (`resp_id`),
  KEY `est_id` (`est_id`),
  CONSTRAINT `especies_generadas_ibfk_1` FOREIGN KEY (`tra_id`) REFERENCES `tramite` (`tra_id`),
  CONSTRAINT `especies_generadas_ibfk_2` FOREIGN KEY (`esp_id`) REFERENCES `especies` (`esp_id`),
  CONSTRAINT `especies_generadas_ibfk_3` FOREIGN KEY (`mod_id`) REFERENCES `modalidad` (`mod_id`),
  CONSTRAINT `especies_generadas_ibfk_4` FOREIGN KEY (`uaca_id`) REFERENCES `unidad_academica` (`uaca_id`),
  CONSTRAINT `especies_generadas_ibfk_5` FOREIGN KEY (`dsol_id`) REFERENCES `detalle_solicitud` (`dsol_id`),
  CONSTRAINT `especies_generadas_ibfk_6` FOREIGN KEY (`resp_id`) REFERENCES `responsable_especie` (`resp_id`),
  CONSTRAINT `especies_generadas_ibfk_7` FOREIGN KEY (`est_id`) REFERENCES `estudiante` (`est_id`)
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo_academico_estudiante`
-- --------------------------------------------------------
create table if not exists `distributivo_academico_estudiante` (
  `daes_id` bigint(20) not null auto_increment primary key,
  `daca_id` bigint(20) not null,
  `est_id` bigint(20) not null,
  `daes_fecha_registro` timestamp null default null,
  `daes_estado` varchar(1) not null,
  `daes_fecha_creacion` timestamp not null default current_timestamp,
  `daes_fecha_modificacion` timestamp null default null,
  `daes_estado_logico` varchar(1) not null,
  foreign key (daca_id) REFERENCES `distributivo_academico`(daca_id),
  foreign key (est_id) REFERENCES `estudiante`(est_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `estudiante_periodo_pago`
-- --------------------------------------------------------
create table if not exists `estudiante_periodo_pago` (
  `eppa_id` bigint(20) not null auto_increment primary key,
  `paca_id` bigint(20) null,
  `ppro_id` bigint(20) null,
  `est_id` bigint(20) not null,
  `eppa_estado_pago` varchar(1) not null,
  `eppa_fecha_registro` timestamp null default null,
  `eppa_usuario_ingreso` bigint(20) DEFAULT NULL,
  `eppa_usuario_modifica` bigint(20) DEFAULT NULL,
  `eppa_estado` varchar(1) not null,
  `eppa_fecha_creacion` timestamp not null default current_timestamp,
  `eppa_fecha_modificacion` timestamp null default null,
  `eppa_estado_logico` varchar(1) not null,
  foreign key (est_id) REFERENCES `estudiante`(est_id)
);
-- alter table db_academico.estudiante_periodo_pago add ppro_id bigint null after paca_id;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `certificados_generadas`
-- --------------------------------------------------------

CREATE TABLE `certificados_generadas` (
  `cgen_id` bigint(20) not null auto_increment primary key,
  `egen_id` bigint(20) not null,
  `cgen_codigo` varchar(100) not null,
  `cgen_observacion` text,
  `cgen_fecha_codigo_generado` timestamp null DEFAULT NULL,
  `cgen_fecha_certificado_subido` timestamp null DEFAULT NULL,
  `cgen_fecha_caducidad` date DEFAULT NULL,
  `cgen_ruta_archivo_pdf` varchar(500) DEFAULT NULL,
  `cgen_estado_certificado` varchar(1) NOT NULL, -- null pendiente, 1 codigo generado, 2 certificado generado
  `cgen_observacion_autorizacion` varchar(500) NULL,
  `cgen_observacion_detalle_aut` varchar(500) NULL,
  `cgen_fecha_autorizacion` timestamp null DEFAULT NULL,
  `cgen_usuario_autorizacion` bigint(20) DEFAULT NULL,
  `cgen_usuario_ingreso` bigint(20) DEFAULT NULL,
  `cgen_usuario_modifica` bigint(20) DEFAULT NULL,
  `cgen_estado` varchar(1) NOT NULL,
  `cgen_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cgen_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `cgen_estado_logico` varchar(1) NOT NULL,
  foreign key (egen_id) references `especies_generadas`(egen_id),
  unique cgen_codigo(cgen_codigo)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `diploma`
-- --------------------------------------------------------
create table if not exists `diploma` (
  `dip_id` bigint(20) not null auto_increment primary key,
  `dip_codigo` varchar(100) null,
  `dip_nombres` varchar(100) not null,
  `dip_apellidos` varchar(100) not null,
  `dip_cedula` varchar(10) null,
  `dip_carrera` varchar(150) null,
  `dip_modalidad` varchar(50) null,
  `dip_programa` varchar(200) null,
  `dip_fecha_inicio` timestamp null DEFAULT NULL,
  `dip_fecha_fin` timestamp null DEFAULT NULL,
  `dip_horas` varchar(100) null,
  `dip_descargado` varchar(1) null,
  `dip_estado` varchar(1) not null,
  `dip_fecha_creacion` timestamp not null default current_timestamp,
  `dip_fecha_modificacion` timestamp null default null,
  `dip_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `malla_unidad_modalidad`
-- --------------------------------------------------------
create table db_academico.malla_unidad_modalidad
(`mumo_id` bigint(20) not null auto_increment primary key,
 `maca_id` bigint(20) not null,
 `meun_id` bigint(20) not null,
 `mumo_estado` varchar(1) not null,
 `mumo_fecha_creacion` timestamp not null default current_timestamp,
 `mumo_fecha_modificacion` timestamp null default null,
 `mumo_estado_logico` varchar(1) not null,
  foreign key (maca_id) references `malla_academica`(maca_id),
  foreign key (meun_id) references `modalidad_estudio_unidad`(meun_id)
);


create table if not exists `distributivo_cabecera` (
  `dcab_id` bigint(20) not null auto_increment primary key,
  `paca_id` bigint(20) null,
  `pro_id` bigint(20) not null,
  `dcab_estado_revision` varchar(1) null,
  `dcab_observacion_revision` varchar(1000) null,
  `dcab_fecha_revision` timestamp null default null,
  `dcab_usuario_revision` bigint(20) null,
  `dcab_fecha_registro` timestamp null default null,
  `dcab_usuario_ingreso` bigint(20) not null,
  `dcab_usuario_modifica` bigint(20) null,
  `dcab_estado` varchar(1) not null,
  `dcab_fecha_creacion` timestamp not null default current_timestamp,
  `dcab_fecha_modificacion` timestamp null default null,
  `dcab_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (paca_id) references `periodo_academico`(paca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `configuracion_tipo_distributivo`
--
create table if not exists db_academico.`configuracion_tipo_distributivo` (
 `ctdi_id` bigint(20) not null auto_increment primary key,
 `tdis_id` bigint(20) not null,
 `uaca_id` bigint(20) null,
 `mod_id` bigint(20) null,
 `ctdi_horas_inicio` integer(3) null,
 `ctdi_horas_fin` integer(3) null,
 `ctdi_estado_vigencia` varchar(1) not null,
 `ctdi_horas_semanal` integer(3) not null,
 `ctdi_estado` varchar(1) not null,
 `ctdi_fecha_creacion` timestamp not null default current_timestamp,
 `ctdi_fecha_modificacion` timestamp null default null,
 `ctdi_estado_logico` varchar(1) not null,
 foreign key (tdis_id) references `tipo_distributivo`(tdis_id),
 foreign key (uaca_id) references `unidad_academica`(uaca_id),
 foreign key (mod_id) references `modalidad`(mod_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para cargar imagen de `cronograma`
--
create table if not exists `cronograma` (
  `cro_id` bigint(20) not null auto_increment primary key,
  `uaca_id` bigint(20) not null,
  `paca_id` bigint(20) not null,
  `cro_archivo` varchar(500) not null,
  `cro_descripcion` varchar(500) null,
  `cro_usuario_ingreso` bigint(20) not null,
  `cro_usuario_modifica` bigint(20)  null,
  `cro_estado` varchar(1) not null,
  `cro_fecha_creacion` timestamp not null default current_timestamp,
  `cro_fecha_modificacion` timestamp null default null,
  `cro_estado_logico` varchar(1) not null,
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (paca_id) references `periodo_academico`(paca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para reglamentos e instructivos`
--
create table if not exists `reglamento` (
  `reg_id` bigint(20) not null auto_increment primary key,
  `emp_id` bigint(20) not null,
  `reg_nombre` varchar(100) not null,
  `reg_descripcion` varchar(500) null,
  `reg_archivo` varchar(500) not null,
  `reg_usuario_ingreso` bigint(20) not null,
  `reg_usuario_modifica` bigint(20)  null,
  `reg_estado` varchar(1) not null,
  `reg_fecha_creacion` timestamp not null default current_timestamp,
  `reg_fecha_modificacion` timestamp null default null,
  `reg_estado_logico` varchar(1) not null

);

-- --------------------------------------------------------
--
-- Estructura de tabla para comparacion usuarios educativa y asgard
--
create table if not exists `usuario_educativa` (
  `uedu_id` bigint(20) not null auto_increment primary key,
  `per_id` bigint(20) null,
  `est_id` bigint(20) null,
  `uedu_usuario` varchar(100) not null,
  `uedu_nombres` varchar(100) default null,
  `uedu_apellidos` varchar(100) default null,
  `uedu_cedula` varchar(15) default null,
  `uedu_matricula`varchar(20) default null,
  `uedu_correo` varchar(250) default null,
  `uedu_usuario_ingreso` bigint(20) not null,
  `uedu_usuario_modifica` bigint(20)  null,
  `uedu_estado` varchar(1) not null,
  `uedu_fecha_creacion` timestamp not null default current_timestamp,
  `uedu_fecha_modificacion` timestamp null default null,
  `uedu_estado_logico` varchar(1) not null

);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `curso_educativa`
-- --------------------------------------------------------
create table if not exists `curso_educativa` (
 `cedu_id` bigint(20) not null auto_increment primary key,
 `paca_id` bigint(20) not null,
 `cedu_asi_id` bigint(20) not null,
 `cedu_asi_nombre` varchar(500) not null,
 `cedu_usuario_ingreso` bigint(20) not null,
 `cedu_usuario_modifica` bigint(20) null,
 `cedu_estado` varchar(1) not null,
 `cedu_fecha_creacion` timestamp not null default current_timestamp,
 `cedu_fecha_modificacion` timestamp null default null,
 `cedu_estado_logico` varchar(1) not null,
 foreign key (paca_id) references `periodo_academico`(paca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `curso_educativa_unidad`
-- --------------------------------------------------------
create table if not exists `curso_educativa_unidad` (
 `ceuni_id` bigint(20) not null auto_increment primary key,
 `cedu_id` bigint(20) not null,
 `ceuni_codigo_unidad` bigint(20) not null,
 `ceuni_descripcion_unidad` varchar(500) not null,
 `ceuni_usuario_ingreso` bigint(20) not null,
 `ceuni_usuario_modifica` bigint(20) null,
 `ceuni_fecha_inicio` timestamp null default null,
 `ceuni_fecha_fin` timestamp null default null,
 `ceuni_estado` varchar(1) not null,
 `ceuni_fecha_creacion` timestamp not null default current_timestamp,
 `ceuni_fecha_modificacion` timestamp null default null,
 `ceuni_estado_logico` varchar(1) not null,
 foreign key (cedu_id) references `curso_educativa`(cedu_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `curso_educativa_estudiante`
-- --------------------------------------------------------
create table if not exists `curso_educativa_estudiante` (
 `ceest_id` bigint(20) not null auto_increment primary key,
 `cedu_id` bigint(20) not null,
 `ceuni_id` bigint(20) not null,
 `est_id` bigint(20) not null,
 `ceest_codigo_evaluacion` varchar(20),
 `ceest_descripcion_evaluacion` varchar(500),
 `ceest_estado_bloqueo` varchar(1) not null, -- B bloqueado, A aceptado
 `ceest_usuario_ingreso` bigint(20) not null,
 `ceest_usuario_modifica` bigint(20) null,
 `ceest_estado` varchar(1) not null,
 `ceest_fecha_creacion` timestamp not null default current_timestamp,
 `ceest_fecha_modificacion` timestamp null default null,
 `ceest_estado_logico` varchar(1) not null,
 foreign key (est_id) references `estudiante`(est_id),
 foreign key (cedu_id) references `curso_educativa`(cedu_id),
 foreign key (ceuni_id) references `curso_educativa_unidad`(ceuni_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `curso_educativa_estudiante_historial`
-- --------------------------------------------------------
create table if not exists `curso_educativa_estudiante_historial` (
    `ceeh_id` bigint(20) not null auto_increment primary key,
    `ceest_id` bigint(20) not null,
    `ceeh_estado_pago` varchar(20) not null,
    `ceeh_est_bloqueo_anterior` varchar(20) not null,
    `ceeh_est_bloqueo` varchar(20) not null,
    `ceeh_unidad` varchar(20) NULL,
    `ceeh_usuario_creacion` bigint(20) NOT NULL,
    `ceeh_estado` varchar(1) not null,
    `ceeh_fecha_creacion` timestamp not null default current_timestamp,
    `ceeh_fecha_modificacion` timestamp null default null,
    `ceeh_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `curso_educativa_distributivo`
-- --------------------------------------------------------
create table if not exists `curso_educativa_distributivo` (
 `cedi_id` bigint(20) not null auto_increment primary key,
 `cedu_id` bigint(20) not null,
 `daca_id` bigint(20) not null,
 `cedi_usuario_ingreso` bigint(20) not null,
 `cedi_usuario_modifica` bigint(20) null,
 `cedi_estado` varchar(1) not null,
 `cedi_fecha_creacion` timestamp not null default current_timestamp,
 `cedi_fecha_modificacion` timestamp null default null,
 `cedi_estado_logico` varchar(1) not null,
 foreign key (cedu_id) references `curso_educativa`(cedu_id),
 foreign key (daca_id) references `distributivo_academico`(daca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `componente`
-- --------------------------------------------------------
create table if not exists `componente` (
  `com_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `com_nombre` varchar(100) NOT NULL,
  `com_descripcion` varchar(100) NOT NULL,
  `com_estado` varchar(1) NOT NULL,
  `com_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `com_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `com_estado_logico` varchar(1) NOT NULL
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `actividad`
-- --------------------------------------------------------
create table if not exists `actividad` (
  `act_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `act_nombre` varchar(100) NOT NULL,
  `act_descripcion` varchar(100) NOT NULL,
  `act_estado` varchar(1) NOT NULL,
  `act_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `act_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `act_estado_logico` varchar(1) NOT NULL
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `esquema_calificacion`
-- --------------------------------------------------------
create table if not exists `esquema_calificacion` (
  `ecal_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `ecal_nombre` varchar(100) NOT NULL,
  `ecal_descripcion` varchar(100) NOT NULL,
  `ecal_estado` varchar(1) NOT NULL,
  `ecal_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ecal_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ecal_estado_logico` varchar(1) NOT NULL
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `componente_unidad`
-- --------------------------------------------------------
create table if not exists `componente_unidad` (
  `cuni_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `com_id` bigint(20) NOT NULL,
  `uaca_id` bigint(20) NOT NULL,
  `mod_id` bigint(20) NOT NULL,
  `ecal_id` bigint(20),
  `cuni_calificacion` double NOT NULL,
  `cuni_estado` varchar(1) NOT NULL,
  `cuni_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cuni_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `cuni_estado_logico` varchar(1) NOT NULL,
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (com_id) references `componente`(com_id),
  foreign key (mod_id) references `modalidad`(mod_id),
  foreign key (ecal_id) references `esquema_calificacion`(ecal_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `componente_unidad_actividad`
-- --------------------------------------------------------
create table if not exists `componente_unidad_actividad` (
  `cuac_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `cuni_id` bigint(20) NOT NULL,
  `act_id` bigint(20) NOT NULL,
  `cuac_estado` varchar(1) NOT NULL,
  `cuac_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cuac_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `cuac_estado_logico` varchar(1) NOT NULL,
  foreign key (cuni_id) references `componente_unidad`(cuni_id),
  foreign key (act_id) references `actividad`(act_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `esquema_calificacion_unidad`
-- --------------------------------------------------------
create table if not exists `esquema_calificacion_unidad` (
  `ecun_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `ecal_id` bigint(20) NOT NULL,
  `uaca_id` bigint(20) NOT NULL,
  `ecun_estado` varchar(1) NOT NULL,
  `ecun_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ecun_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ecun_estado_logico` varchar(1) NOT NULL,
  foreign key (uaca_id) references `unidad_academica`(uaca_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `asistencia_esquema_unidad`
-- --------------------------------------------------------
create table if not exists `asistencia_esquema_unidad` (
  `aeun_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `ecun_id` bigint(20) NOT NULL,
  `aeun_cantidad` integer NOT NULL,
  `aeun_estado` varchar(1) NOT NULL,
  `aeun_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aeun_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `aeun_estado_logico` varchar(1) NOT NULL,
  foreign key (ecun_id) references `esquema_calificacion_unidad`(ecun_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `cabecera_calificacion`
-- --------------------------------------------------------
create table if not exists `cabecera_calificacion` (
  `ccal_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `paca_id` bigint(20) NOT NULL,
  `est_id` bigint(20) NOT NULL,
  `pro_id` bigint(20) NOT NULL,
  `asi_id` bigint(20) NOT NULL,
  `ecun_id` bigint(20) NOT NULL,
  `ccal_calificacion` double NULL,
  `ccal_estado` varchar(1) NOT NULL,
  `ccal_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ccal_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ccal_estado_logico` varchar(1) NOT NULL,
  foreign key (paca_id) references `periodo_academico`(paca_id),
  foreign key (est_id) references `estudiante`(est_id),
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (asi_id) references `asignatura`(asi_id),
  foreign key (ecun_id) references `esquema_calificacion_unidad`(ecun_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_calificacion`
-- --------------------------------------------------------
create table if not exists `detalle_calificacion` (
  `dcal_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `ccal_id` bigint(20) NOT NULL,
  `cuni_id` bigint(20) NOT NULL,
  `dcal_calificacion` double NULL,
  `dcal_usuario_creacion` bigint(20) NOT NULL,
  `dcal_usuario_modificacion` bigint(20) NULL,
  `dcal_estado` varchar(1) NOT NULL,
  `dcal_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dcal_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `dcal_estado_logico` varchar(1) NOT NULL,
  foreign key (ccal_id) references `cabecera_calificacion`(ccal_id),
  foreign key (cuni_id) references `componente_unidad`(cuni_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `cabecera_asistencia`
-- --------------------------------------------------------
create table if not exists `cabecera_asistencia` (
  `casi_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `paca_id` bigint(20) NOT NULL,
  `est_id` bigint(20) NOT NULL,
  `pro_id` bigint(20) NOT NULL,
  `asi_id` bigint(20) NOT NULL,
  `aeun_id` bigint(20) NOT NULL,
  `casi_cant_total` integer NULL,
  `casi_porc_total` double NULL,
  `casi_estado` varchar(1) NOT NULL,
  `casi_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `casi_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `casi_estado_logico` varchar(1) NOT NULL,
  foreign key (paca_id) references `periodo_academico`(paca_id),
  foreign key (est_id) references `estudiante`(est_id),
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (asi_id) references `asignatura`(asi_id),
  foreign key (aeun_id) references `asistencia_esquema_unidad`(aeun_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_asistencia`
-- --------------------------------------------------------
create table if not exists `detalle_asistencia` (
  `dasi_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `casi_id` bigint(20) NOT NULL,
  `ecal_id` bigint DEFAULT NULL,
  `dasi_tipo` varchar(2) NOT NULL,
  `dasi_cantidad` integer NOT NULL,
  `dasi_usuario_creacion` bigint(20) NOT NULL,
  `dasi_usuario_modificacion` bigint(20) NULL,
  `dasi_estado` varchar(1) NOT NULL,
  `dasi_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dasi_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `dasi_estado_logico` varchar(1) NOT NULL,
  foreign key (casi_id) references `cabecera_asistencia`(casi_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `resumen_calificacion`
-- --------------------------------------------------------
create table if not exists `resumen_calificacion` (
  `rcal_id` bigint(20) NOT NULL AUTO_INCREMENT primary key,
  `paca_id` bigint(20) NOT NULL,
  `uaca_id` bigint(20) NOT NULL,
  `eaca_id` bigint(20) NOT NULL,
  `est_id` bigint(20) NOT NULL,
  `asi_id` bigint(20) NOT NULL,
  `rcal_promedio` double NOT NULL,
  `rcal_asistencia` double NOT NULL,
  `rcal_aprobado` varchar(1) NOT NULL,  /** 0->No aprobado, 1->Aprobado */
  `rcal_usuario_creacion` bigint(20) NOT NULL,
  `rcal_usuario_modificacion` bigint(20) NULL,
  `rcal_estado` varchar(1) NOT NULL,
  `rcal_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rcal_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `rcal_estado_logico` varchar(1) NOT NULL,
  foreign key (paca_id) references `periodo_academico`(paca_id),
  foreign key (uaca_id) references `unidad_academica`(uaca_id),
  foreign key (eaca_id) references `estudio_academico`(eaca_id),
  foreign key (est_id) references `estudiante`(est_id),
  foreign key (asi_id) references `asignatura`(asi_id)
);

create view componente_columna as (
      select comp.com_id as id, coun.uaca_id as uaca_id,
             case when comp.com_id  = 1 then 0   end as 'Asíncrona',
             case when comp.com_id  = 2 then 0   end as 'Síncrona',
             case when comp.com_id  = 3 then 0   end as 'Cuestionarios',
             case when comp.com_id  = 4 then 0   end as 'Autónoma',
             case when comp.com_id  = 5 then 0   end as 'Evaluación',
             case when comp.com_id  = 6 then 0   end as 'Examen',
             case when comp.com_id  = 7 then 0   end as 'Talleres',
             case when comp.com_id  = 8 then 0   end as 'Deberes',
             case when comp.com_id  = 9 then 0   end as 'Aporte'
             from db_academico.componente_unidad coun
             INNER JOIN db_academico.componente comp ON comp.com_id = coun.com_id
       -- WHERE coun.uaca_id = 3
  );

create table if not exists `fechas_vencimiento_pago` (
  `fvpa_id` int(11) NOT NULL AUTO_INCREMENT,
  `saca_id` bigint(20) DEFAULT NULL,
  `fvpa_cuota` int(11) DEFAULT NULL,
  `fvpa_fecha_vencimiento` datetime DEFAULT NULL,
  `fvpa_estado` bigint(20) DEFAULT NULL,
  `fvpa_periodo_academico` bigint(20) DEFAULT NULL,
  `fvpa_bloque` varchar(2) DEFAULT NULL,
  `fvpa_fecha_creacion` datetime DEFAULT NULL,
  `fvpa_usuario_modificacion` varchar(45) DEFAULT NULL,
  `fvpa_fecha_modificacion` datetime DEFAULT NULL,
  `fvpa_estado_logico` bigint(20) DEFAULT '1',
  PRIMARY KEY (`fvpa_id`)
);

CREATE TABLE `malla_academico_estudiante` (
  `maes_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `per_id` bigint(20) DEFAULT NULL,
  `made_id` bigint(20) DEFAULT NULL,
  `maca_id` bigint(20) DEFAULT NULL,
  `asi_id` bigint(20) DEFAULT NULL,
  `maes_usuario_ingreso` bigint(20) DEFAULT NULL,
  `maes_usuario_modifica` bigint(20) DEFAULT NULL,
  `maes_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `maes_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `maes_estado` varchar(1) NOT NULL,
  `maes_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`maes_id`),
  KEY `MADE_ID` (`made_id`),
  KEY `PER_ID` (`per_id`),
  KEY `MACA_ID` (`maca_id`),
  KEY `ASI_ID` (`asi_id`)
);

CREATE TABLE `promedio_malla_academico` (
  `pmac_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `maes_id` bigint(20) DEFAULT NULL,
  `enac_id` bigint(20) DEFAULT NULL,
  `pmac_nota` float DEFAULT NULL,
  `pmac_usuario_ingreso` bigint(20) DEFAULT NULL,
  `pmac_usuario_modifica` bigint(20) DEFAULT NULL,
  `pmac_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pmac_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `pmac_estado` bigint(20) DEFAULT NULL,
  `pmac_estado_logico` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`pmac_id`),
  KEY `estac_id` (`enac_id`),
  CONSTRAINT `promedio_malla_academico_ibfk_1` FOREIGN KEY (`enac_id`) REFERENCES `estado_nota_academico` (`enac_id`)
);


create table if not exists `horarios_semestre` (
  `hose_id` bigint(20) not null auto_increment primary key,
  `saca_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `uaca_id` bigint(20) not null,
  `hose_usuario_ingreso` bigint(20) not null,
  `hose_usuario_modifica` bigint(20)  null,  
  `hose_estado` varchar(1) not null,
  `hose_fecha_creacion` timestamp not null default current_timestamp,
  `hose_fecha_modificacion` timestamp null default null,
  `hose_estado_logico` varchar(1) not null
);


create table if not exists `horarios_semestre_detalle` (
  `hosd_id` bigint(20) not null auto_increment primary key,
  `hose_id` bigint(20) not null,
  `hosd_grupo` bigint(20) not null,
  `hosd_bloque` bigint(20) not null,
  `hosd_hora` bigint(20) not null,
  `hosd_asi_id` bigint(20) not null,
  `hosd_usuario_ingreso` bigint(20) not null,
  `hosd_usuario_modifica` bigint(20)  null,  
  `hosd_estado` varchar(1) not null,
  `hosd_fecha_creacion` timestamp not null default current_timestamp,
  `hosd_fecha_modificacion` timestamp null default null,
  `hosd_estado_logico` varchar(1) not null,
    foreign key (hose_id) references `horarios_semestre`(hose_id)
);

create table if not exists `paralelos_alumno` (
  `paal_id` bigint(20) not null auto_increment primary key,
  `hosd_id` bigint(20) not null,
  -- `mpp_id` bigint(20) not null,  -- getout
  `paal_cantidad` bigint(20) not null,
  `paal_usuario_ingreso` bigint(20) not null,
  `paal_usuario_modifica` bigint(20)  null,  
  `paal_estado` varchar(1) not null,
  `paal_fecha_creacion` timestamp not null default current_timestamp,
  `paal_fecha_modificacion` timestamp null default null,
  `paal_estado_logico` varchar(1) not null,
    foreign key (hosd_id) references `horarios_semestre_detalle`(hosd_id)
);

-- MODIFICACIONES PARA GENERACION PARALELO

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b1_h1_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b1_h1_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b1_h2_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b1_h2_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b1_h3_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b1_h3_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b1_h4_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b1_h4_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b1_h5_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b1_h5_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b1_h6_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b1_h6_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b2_h1_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b2_h1_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b2_h2_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b2_h2_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b2_h3_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b2_h3_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b2_h4_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b2_h4_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b2_h5_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b2_h5_cod;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_mat_b2_h6_mpp BIGINT(20) NULL DEFAULT NULL AFTER pes_mat_b2_h6_cod;

ALTER TABLE db_academico.materia_paralelo_periodo ADD 
daho_id BIGINT(20) NULL DEFAULT NULL AFTER paca_id;

ALTER TABLE db_academico.planificacion_estudiante ADD 
pes_semestre BIGINT(20) NULL DEFAULT NULL AFTER pes_carrera;

- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `materias_siiga`
--
create table if not exists `materias_siiga` (
  `masi_id` bigint(20) not null auto_increment primary key,
  `pla_id` bigint(20) not null,
  `asi_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `maca_id` bigint(20) not null,
  `uaca_id` bigint(20) not null,
  `bloq_id` bigint(20) not null,
  `siiga_paralelo` bigint(20) not null, --
  `siiga_nalumnos` bigint(20) not null,
  `siiga_materia` bigint(20) not null,
  `siiga_modalidad` bigint(20) not null,
  `siiga_docente` bigint(20) not null,
  `siiga_periodolectivo` bigint(20) not null,
  `masi_cantidad` bigint(20) not null,
  `masi_usuario_ingreso` bigint(20) not null,
  `masi_usuario_modifica` bigint(20)  null,
  `masi_estado` varchar(1) not null,
  `masi_fecha_creacion` timestamp not null default current_timestamp,
  `masi_fecha_modificacion` timestamp null default null,
  `masi_estado_logico` varchar(1) not null,
);

- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `paralelos_siiga`
--
create table if not exists `paralelos_siiga` (
  `masi_id` bigint(20) not null auto_increment primary key,
  `pla_id` bigint(20) not null,
  `asi_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `maca_id` bigint(20) not null,
  `uaca_id` bigint(20) not null,
  `bloq_id` bigint(20) not null,
  `mpp_id` bigint(20) not null,
  `siiga_paralelo` bigint(20) not null, --
  `siiga_nalumnos` bigint(20) not null,
  `siiga_materia` bigint(20) not null,
  `siiga_modalidad` bigint(20) not null,
  `siiga_docente` bigint(20) not null,
  `siiga_periodolectivo` bigint(20) not null,
  `siiga_unidad` bigint(20) not null,
  `siiga_periodo_nombre` varchar(64) not null,
  `siiga_unidad_nombre` varchar(64) not null,
  `siiga_categoria_nombre` varchar(64) not null,
  `siiga_modalidad_nombre` varchar(64) not null,
  `masi_cantidad` bigint(20) not null,
  `masi_usuario_ingreso` bigint(20) not null,
  `masi_usuario_modifica` bigint(20)  null,
  `masi_estado` varchar(1) not null,
  `masi_fecha_creacion` timestamp not null default current_timestamp,
  `masi_fecha_modificacion` timestamp null default null,
  `masi_estado_logico` varchar(1) not null,
);
 