-- 
-- Base de datos: `db_repositorio`
-- 

DROP SCHEMA IF EXISTS `db_repositorio`;
CREATE SCHEMA IF NOT EXISTS `db_repositorio` DEFAULT CHARACTER SET utf8 ;
USE `db_repositorio` ;

-- GRANT ALL PRIVILEGES ON `db_repositorio`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*'; -- Ubuntu
-- GRANT ALL PRIVILEGES ON `db_repositorio`.* TO 'uteg'@'localhost';  -- centos

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `modelo`
-- 
create table if not exists `modelo` (
  `mod_id` bigint(20) not null auto_increment primary key,  
  `mod_codificacion` varchar(100) null,
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
-- Estructura de tabla para la tabla `funcion`
-- 
create table if not exists `funcion` (
  `fun_id` bigint(20) not null auto_increment primary key,
  `mod_id` bigint(20) not null,  
  `fun_codificacion` varchar(100) null,
  `fun_nombre` varchar(300) not null,
  `fun_descripcion` varchar(500) not null,  
  `fun_usuario_ingreso` bigint(20) not null,
  `fun_usuario_modifica` bigint(20)  null,  
  `fun_estado` varchar(1) not null,
  `fun_fecha_creacion` timestamp not null default current_timestamp,
  `fun_fecha_modificacion` timestamp null default null,
  `fun_estado_logico` varchar(1) not null,

  foreign key (mod_id) references `modelo`(mod_id)
  
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `componente`
-- 
create table if not exists `componente` (
  `com_id` bigint(20) not null auto_increment primary key,
  `com_codificacion` varchar(100) null,  
  `com_nombre` varchar(300) not null,
  `com_descripcion` varchar(500) not null,
  `com_usuario_ingreso` bigint(20) not null,
  `com_usuario_modifica` bigint(20)  null,  
  `com_estado` varchar(1) not null,
  `com_fecha_creacion` timestamp not null default current_timestamp,
  `com_fecha_modificacion` timestamp null default null,
  `com_estado_logico` varchar(1) not null  
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `estandar`
-- 
create table if not exists `estandar` (
  `est_id` bigint(20) not null auto_increment primary key,
  `com_id` bigint(20) null, 
  `fun_id` bigint(20) not null,
  `est_codificacion` varchar(100) null, 
  `est_nombre` varchar(300) not null,
  `est_descripcion` varchar(500) not null,
  `est_usuario_ingreso` bigint(20) not null,
  `est_usuario_modifica` bigint(20)  null,  
  `est_estado` varchar(1) not null,
  `est_fecha_creacion` timestamp not null default current_timestamp,
  `est_fecha_modificacion` timestamp null default null,
  `est_estado_logico` varchar(1) not null,

  foreign key (com_id) references `componente`(com_id),
  foreign key (fun_id) references `funcion`(fun_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `documento_repositorio`
--
create table if not exists `documento_repositorio`(
`dre_id` bigint(20) not null auto_increment primary key,
`est_id` bigint(20) not null,
`dre_tipo` bigint(20) null, -- 1 publico , 2 -- privado
`dre_codificacion` varchar(100) not null,
`dre_ruta` varchar(200) not null,
`dre_imagen` varchar(100) not null,
`dre_descripcion` varchar(1000) null,
`dre_usu_ingresa` bigint(20) not null,
`dre_usu_modifica` bigint(20) null,
`dre_estado` varchar(1) not null,
`dre_fecha_archivo` timestamp null default null,
`dre_fecha_creacion` timestamp not null default current_timestamp,
`dre_fecha_modificacion` timestamp null default null,
`dre_estado_logico` varchar(1) not null,

foreign key (est_id) references `estandar`(est_id)
);