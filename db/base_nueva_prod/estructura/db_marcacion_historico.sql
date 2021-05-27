-- 
-- Base de datos: `db_marcacion_historico`
-- 

DROP SCHEMA IF EXISTS `db_marcacion_historico`;
CREATE SCHEMA IF NOT EXISTS `db_marcacion_historico` DEFAULT CHARACTER SET utf8 ;

-- GRANT ALL PRIVILEGES ON `db_marcacion_historico`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*'; -- Ubuntu
-- GRANT ALL PRIVILEGES ON `db_marcacion_historico`.* TO 'uteg'@'localhost';  -- centos

USE `db_marcacion_historico` ;

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `periodo_academico_historial`
-- 
create table if not exists `periodo_academico_historial` (
  `pahi_id` bigint(20) not null auto_increment primary key, 
  `saca_id` bigint(20) null,
  `baca_id` bigint(20) null,
  `pahi_anio_academico` varchar(50) not null,
  `pahi_activo` varchar(1) not null,
  `pahi_fecha_inicio` timestamp null default null,
  `pahi_fecha_fin` timestamp null default null,
  `pahi_usuario_ingreso` bigint(20) not null,
  `pahi_usuario_modifica` bigint(20)  null,  
  `pahi_estado` varchar(1) not null,
  `pahi_fecha_creacion` timestamp not null default current_timestamp,
  `pahi_fecha_modificacion` timestamp null default null,
  `pahi_estado_logico` varchar(1) not null  
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `horario_asignatura_periodo_historial`
-- --------------------------------------------------------
create table if not exists `horario_asignatura_periodo_historial` (
  `haph_id` bigint(20) not null auto_increment primary key,   
  `asi_id` bigint(20) not null,
  `pahi_id` bigint(20) not null,
  `pro_id` bigint(20) not null,
  `uaca_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `dia_id` bigint(20) not null,
  `haph_fecha_clase` timestamp null default null,
  `haph_hora_entrada` varchar(10) not null,
  `haph_hora_salida` varchar(10) not null,
  `haph_estado` varchar(1) not null,
  `haph_fecha_creacion` timestamp not null default current_timestamp,
  `haph_fecha_modificacion` timestamp null default null,
  `haph_estado_logico` varchar(1) not null,
  foreign key (pahi_id) references `periodo_academico_historial`(pahi_id)  
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `registro_marcacion`
-- --------------------------------------------------------
create table if not exists `registro_marcacion_historial` (
  `rmhi_id` bigint(20) not null auto_increment primary key,  
  `haph_id` bigint(20) not null,
  `rmhi_fecha_hora_entrada` timestamp null,    
  `rmhi_fecha_hora_salida` timestamp null,      
  `rmhi_estado` varchar(1) not null,
  `rmhi_fecha_creacion` timestamp not null default current_timestamp,
  `rmhi_fecha_modificacion` timestamp null default null,
  `rmhi_estado_logico` varchar(1) not null,
  -- foreign key (pro_id) references `profesor`(pro_id),
  foreign key (haph_id) references `horario_asignatura_periodo_historial`(haph_id)
);