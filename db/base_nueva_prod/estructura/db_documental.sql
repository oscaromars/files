--
-- Base de datos: `db_asgard`
--
DROP SCHEMA IF EXISTS `db_documental` ;
CREATE SCHEMA IF NOT EXISTS `db_documental` DEFAULT CHARACTER SET utf8;
USE `db_documental`;

-- GRANT ALL PRIVILEGES ON `db_documental`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

CREATE TABLE `departamento` (
    `dep_id` bigint(20) not null auto_increment primary key,
    `dep_cod` varchar(20) NOT NULL DEFAULT '',
    `dep_nombre` varchar(200) NOT NULL,
    `dep_estado` varchar(1) not null,
    `dep_usuario_ingreso` bigint(20) null,
    `dep_usuario_modifica` bigint(20) null,   
    `dep_fecha_creacion` timestamp not null default current_timestamp,
    `dep_fecha_modificacion` timestamp null default null,
    `dep_estado_logico` varchar(1) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `clase` (
    `cla_id` bigint(20) not null auto_increment primary key,
    `cla_cod` varchar(5) NOT NULL DEFAULT '',
    `cla_nombre` varchar(200) NOT NULL,
    `cla_estado` varchar(1) not null,
    `cla_usuario_ingreso` bigint(20) null,
    `cla_usuario_modifica` bigint(20) null,   
    `cla_fecha_creacion` timestamp not null default current_timestamp,
    `cla_fecha_modificacion` timestamp null default null,
    `cla_estado_logico` varchar(1) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `serie` (
    `ser_id` bigint(20) not null auto_increment primary key,
    `cla_id` bigint(20) not null,
    `ser_cod` varchar(5) NOT NULL DEFAULT '',
    `ser_nombre` varchar(200) NOT NULL,
    `ser_estado` varchar(1) not null,
    `ser_usuario_ingreso` bigint(20) null,
    `ser_usuario_modifica` bigint(20) null,   
    `ser_fecha_creacion` timestamp not null default current_timestamp,
    `ser_fecha_modificacion` timestamp null default null,
    `ser_estado_logico` varchar(1) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `subserie` (
    `sub_id` bigint(20) not null auto_increment primary key,
    `ser_id` bigint(20) not null,
    `sub_cod` varchar(50) NOT NULL DEFAULT '',
    `sub_cod_total` varchar(50) NOT NULL DEFAULT '',
    `sub_nombre` varchar(200) NOT NULL,
    `sub_estado` varchar(1) not null,
    `sub_usuario_ingreso` bigint(20) null,
    `sub_usuario_modifica` bigint(20) null,   
    `sub_fecha_creacion` timestamp not null default current_timestamp,
    `sub_fecha_modificacion` timestamp null default null,
    `sub_estado_logico` varchar(1) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `documento` (
    `doc_id` bigint(20) not null auto_increment primary key,
    `cla_id` bigint(20) not null,
    `ser_id` bigint(20) not null,
    `sub_id` bigint(20) not null,
    `doc_uni_departamento` varchar(150) NOT NULL,
    `doc_macroproceso` varchar(150) NOT NULL,
    `doc_proceso` varchar(150) NOT NULL,
    `doc_secuencia` varchar(20) NOT NULL DEFAULT '',
    `doc_cod_documento` varchar(20) NOT NULL DEFAULT '',
    `doc_fecha_produccion` varchar(20) NOT NULL DEFAULT '',
    `doc_pro_documental` varchar(250) NOT NULL,
    `doc_desc_informacion` text NOT NULL,
    `doc_tipo_informacion` varchar(100) NOT NULL,
    `doc_ubicacion` varchar(200) NOT NULL,
    `doc_clasificacion` varchar(100) NOT NULL,
    `doc_info_publica` varchar(100) NOT NULL,
    `doc_estado_documento` varchar(50) NOT NULL,
    `doc_observaciones` text NOT NULL,
    `doc_plaz_gestion` varchar(50) NOT NULL,
    `doc_plaz_central` varchar(50) NOT NULL,
    `doc_plaz_intermedio` varchar(50) NOT NULL,
    `doc_base_legal` varchar(50) NOT NULL,
    `doc_disp_eliminacion` varchar(50) NOT NULL,
    `doc_disp_conservacion` varchar(50) NOT NULL,
    `doc_tec_muestreo` varchar(50) NOT NULL,
    `doc_tec_conservacion` varchar(50) NOT NULL,
    `doc_cons_gestion` varchar(50) NOT NULL,
    `doc_cons_central` varchar(50) NOT NULL,
    `doc_cod_lomo` varchar(50) NOT NULL,
    `doc_usuario_ingreso` bigint(20) null,
    `doc_usuario_modifica` bigint(20) null,   
    `doc_fecha_creacion` timestamp not null default current_timestamp,
    `doc_fecha_modificacion` timestamp null default null,
    `doc_estado_logico` varchar(1) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;