-- Base de Datos 

DROP SCHEMA IF EXISTS `db_inventario` ;
CREATE SCHEMA IF NOT EXISTS `db_inventario` default CHARACTER SET utf8 ;
USE `db_inventario`;

-- GRANT ALL PRIVILEGES ON `db_inventario`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_bien`
--

/* UTEG */

create table if not exists `tipo_bien` (
 `tbie_id` bigint(20) not null auto_increment primary key,    
 `tbie_descripcion` varchar(200) not null, 
 `tbie_estado` varchar(1) not null,
 `tbie_fecha_creacion` timestamp not null default current_timestamp,
 `tbie_fecha_modificacion` timestamp null default null,
 `tbie_estado_logico` varchar(1) not null
 );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `categoria`
--
create table if not exists `categoria` (
 `cat_id` bigint(20) not null auto_increment primary key,    
 `tbie_id` bigint(20) not null,
 `cat_descripcion` varchar(200) not null, 
 `cat_estado` varchar(1) not null,
 `cat_fecha_creacion` timestamp not null default current_timestamp,
 `cat_fecha_modificacion` timestamp null default null,
 `cat_estado_logico` varchar(1) not null,
 foreign key (tbie_id) references `tipo_bien`(tbie_id)
 );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empresa_inventario`
--
create table if not exists `empresa_inventario` (
 `einv_id` bigint(20) not null auto_increment primary key,    
 `einv_descripcion` varchar(200) not null, 
 `einv_estado` varchar(1) not null,
 `einv_fecha_creacion` timestamp not null default current_timestamp,
 `einv_fecha_modificacion` timestamp null default null,
 `einv_estado_logico` varchar(1) not null
 );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `espacio_departamento`
--
create table if not exists `espacio_departamento` (
 `edep_id` bigint(20) not null auto_increment primary key,  
 `dep_id` bigint(20) not null,
 `edi_id` bigint(20) not null,
 `edep_descripcion` varchar(200) not null, 
 `edep_estado` varchar(1) not null,
 `edep_fecha_creacion` timestamp not null default current_timestamp,
 `edep_fecha_modificacion` timestamp null default null,
 `edep_estado_logico` varchar(1) not null
 );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `activo_fijo`
--
create table if not exists `activo_fijo` (
 `afij_id` bigint(20) not null auto_increment primary key,
 `einv_id` bigint(20) not null, 
 `are_id` bigint(20) null,
 `edep_id` bigint(20) null,
 `cat_id` bigint(20) not null,
 `afij_secuencia` integer not null,
 `afij_codigo` varchar(50) not null,  
 `afij_custodio` varchar(200) not null,
 `afij_descripcion` varchar(1000) null,
 `afij_marca` varchar(100) null,
 `afij_modelo` varchar(100) null,
 `afij_num_serie` varchar(100) null, 
 `afij_cantidad` integer null, 
 `afij_ram` varchar(100) null, 
 `afij_disco_hdd` varchar(100) null, 
 `afij_disco_ssd` varchar(100) null, 
 `afij_procesador` varchar(100) null, 
 `afij_ip` varchar(100) null, 
 `afij_estado` varchar(1) not null,
 `afij_fecha_creacion` timestamp not null default current_timestamp,
 `afij_fecha_modificacion` timestamp null default null,
 `afij_estado_logico` varchar(1) not null,
 foreign key (cat_id) references `categoria`(cat_id),
 foreign key (einv_id) references `empresa_inventario`(einv_id),
 foreign key (edep_id) references `espacio_departamento`(edep_id)
 );



