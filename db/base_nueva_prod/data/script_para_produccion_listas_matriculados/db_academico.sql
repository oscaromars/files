/*Alter table db_academico.distributivo_academico drop foreign key distributivo_academico_ibfk_1;
Alter table db_academico.distributivo_academico drop foreign key distributivo_academico_ibfk_2;
Alter table db_academico.distributivo_academico drop foreign key distributivo_academico_ibfk_3;
show create table db_academico.distributivo_academico;
drop table db_academico.`distributivo_academico`;
*/

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `distributivo_academico`
-- 
create table if not exists db_academico.`distributivo_academico` (
  `daca_id` bigint(20) not null auto_increment primary key, 
  `paca_id` bigint(20) null,
  `ppro_id` bigint(20) null,
  `asi_id` bigint(20) not null,
  `pro_id` bigint(20) not null,  
  `uaca_id` bigint(20) not null,  
  `mod_id` bigint(20) not null,  
  `daca_jornada` varchar(1) not null,  
  `daca_horario` varchar(10) not null,  
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
  foreign key (ppro_id) references `promocion_programa`(ppro_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `distributivo_academico_estudiante` 
-- --------------------------------------------------------
create table if not exists db_academico.`distributivo_academico_estudiante` (
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
create table if not exists db_academico.`estudiante_periodo_pago` (
  `eppa_id` bigint(20) not null auto_increment primary key,   
  `paca_id` bigint(20) not null,
  `est_id` bigint(20) not null,    
  `eppa_estado_pago` varchar(1) not null,
  `eppa_fecha_registro` timestamp null default null,
  `eppa_usuario_ingreso` bigint(20) DEFAULT NULL,
  `eppa_usuario_modifica` bigint(20) DEFAULT NULL,
  `eppa_estado` varchar(1) not null,
  `eppa_fecha_creacion` timestamp not null default current_timestamp,
  `eppa_fecha_modificacion` timestamp null default null,
  `eppa_estado_logico` varchar(1) not null,  
  foreign key (paca_id) REFERENCES `periodo_academico`(paca_id),
  foreign key (est_id) REFERENCES `estudiante`(est_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `distributivo_academico_horario` 
-- --------------------------------------------------------
create table if not exists db_academico.`distributivo_academico_horario` (
  `daho_id` bigint(20) not null auto_increment primary key,   
  `uaca_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `daho_jornada` varchar(1) not null,
  `daho_horario` varchar(10) not null,
  `daho_hora_inicio` varchar(10) null,
  `daho_hora_fin` varchar(10) null,
  `daho_lunes` varchar(1) null,
  `daho_martes` varchar(1) null,
  `daho_miercoles` varchar(1) null,
  `daho_jueves` varchar(1) null,
  `daho_viernes` varchar(1) null,
  `daho_sabado` varchar(1) null,
  `daho_domingo` varchar(1) null,
  `daho_estado` varchar(1) not null,
  `daho_fecha_creacion` timestamp not null default current_timestamp,
  `daho_fecha_modificacion` timestamp null default null,
  `daho_estado_logico` varchar(1) not null,  
  foreign key (uaca_id) references `unidad_academica`(uaca_id), 
  foreign key (mod_id) references `modalidad`(mod_id)
);

