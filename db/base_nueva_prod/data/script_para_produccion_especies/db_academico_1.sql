alter table db_academico.unidad_academica add uaca_nomenclatura varchar(3) after uaca_descripcion;

/* Especies */

CREATE TABLE db_academico.`responsable_especie` (
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

CREATE TABLE db_academico.`tramite` (
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


CREATE TABLE db_academico.`especies` (
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



CREATE TABLE db_academico.`cabecera_solicitud` (
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



CREATE TABLE db_academico.`detalle_solicitud` (
  `dsol_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `csol_id` bigint(20) NOT NULL,
  `tra_id` bigint(20) NOT NULL,
  `esp_id` bigint(20) NOT NULL,
  `est_id` bigint(20) NOT NULL,
  `dsol_cantidad` decimal(12,2) DEFAULT NULL,
  `dsol_valor` decimal(12,2) DEFAULT NULL,
  `dsol_total` decimal(12,2) DEFAULT NULL,
  `dsol_observacion` text,
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

CREATE TABLE db_academico.`especies_generadas` (
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


ALTER TABLE `db_academico`.`unidad_academica` 
ADD COLUMN `uaca_nomenclatura` VARCHAR(5) NULL AFTER `uaca_descripcion`;
