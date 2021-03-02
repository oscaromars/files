create table if not exists db_academico.`planificacion` (
 `pla_id` bigint(20) not null auto_increment primary key,
 `mod_id` bigint(20) not null, 
 `per_id` bigint(20) not null, -- persona que sube la informacion
 `pla_fecha_inicio` timestamp not null,
 `pla_fecha_fin` timestamp not null,
 `pla_periodo_academico` varchar(100) null, 
 `pla_path` text not null,
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
create table if not exists db_academico.`registro_configuracion` (
 `rco_id` bigint(20) not null auto_increment primary key,
 `pla_id` bigint(20) not null,
 `rco_fecha_inicio` timestamp not null,
 `rco_fecha_fin` timestamp not null,
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
create table if not exists db_academico.`planificacion_estudiante` (
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
 `pes_mat_b1_h1_nombre` varchar(100) null, 
 `pes_mat_b1_h1_cod` varchar(20) null,
 `pes_mat_b1_h2_nombre` varchar(100) null, 
 `pes_mat_b1_h2_cod` varchar(20) null,
 `pes_mat_b1_h3_nombre` varchar(100) null, 
 `pes_mat_b1_h3_cod` varchar(20) null,
 `pes_mat_b1_h4_nombre` varchar(100) null, 
 `pes_mat_b1_h4_cod` varchar(20) null,
 `pes_mat_b1_h5_nombre` varchar(100) null, 
 `pes_mat_b1_h5_cod` varchar(20) null,
 `pes_mat_b2_h1_nombre` varchar(100) null, 
 `pes_mat_b2_h1_cod` varchar(20) null,
 `pes_mat_b2_h2_nombre` varchar(100) null, 
 `pes_mat_b2_h2_cod` varchar(20) null,
 `pes_mat_b2_h3_nombre` varchar(100) null, 
 `pes_mat_b2_h3_cod` varchar(20) null,
 `pes_mat_b2_h4_nombre` varchar(100) null, 
 `pes_mat_b2_h4_cod` varchar(20) null,
 `pes_mat_b2_h5_nombre` varchar(100) null, 
 `pes_mat_b2_h5_cod` varchar(20) null,
 `pes_estado` varchar(1) not null, 
 `pes_fecha_creacion` timestamp not null default current_timestamp,
 `pes_usuario_modifica` bigint(20) null,
 `pes_fecha_modificacion` timestamp null default null,
 `pes_estado_logico` varchar(1) not null,
 foreign key (pla_id) references `planificacion`(pla_id)
);

create table if not exists db_academico.`registro_pago_matricula` (
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
create table if not exists db_academico.`registro_online` (
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
create table if not exists db_academico.`registro_online_item` (
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

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `registro_online_cuota`
-- --------------------------------------------------------
create table if not exists db_academico.`registro_online_cuota` (
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
create table if not exists db_academico.`modalidad_centro_costo` (
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

alter table db_academico.estudiante add `est_matricula` varchar(20) null after per_id;
alter table db_academico.estudiante add `est_categoria` varchar(2) null after est_matricula;
alter table db_academico.estudiante add `est_fecha_ingreso` timestamp null default null after est_categoria;
