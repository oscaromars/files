--
-- Base de datos: `db_inscripcion`
--

DROP SCHEMA IF EXISTS `db_inscripcion`;
CREATE SCHEMA IF NOT EXISTS `db_inscripcion` DEFAULT CHARACTER SET utf8 ;
USE `db_inscripcion` ;


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `inscripcion_grado`
--
CREATE TABLE `inscripcion_grado` (
  `igra_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint DEFAULT NULL,
  `uaca_id` bigint NOT NULL,
  `eaca_id` bigint NOT NULL,
  `mod_id` bigint NOT NULL,
  `paca_id` bigint NOT NULL,
  `igra_cedula` varchar(15) NOT NULL,
  `igra_metodo_ingreso` bigint DEFAULT NULL,
  `igra_ruta_doc_titulo` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_dni` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_certvota` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_foto` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_comprobantepago` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_recordacademico` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_certificado` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_syllabus` varchar(200) DEFAULT NULL,
  `igra_ruta_doc_homologacion` varchar(200) DEFAULT NULL,
  `igra_mensaje1` varchar(1) DEFAULT NULL,
  `igra_mensaje2` varchar(1) DEFAULT NULL,
  `igra_estado` varchar(1) NOT NULL,
  `igra_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `igra_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `igra_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`igra_id`)
);

CREATE TABLE `inscripcion_posgrado` (
  `ipos_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint NOT NULL,
  `uaca_id` bigint NOT NULL,
  `eaca_id` bigint NOT NULL,
  `mod_id` bigint NOT NULL,
  `ipos_año` varchar(50) DEFAULT NULL,
  `ipos_cedula` varchar(50) DEFAULT NULL,
  `ipos_tipo_financiamiento` varchar(200) DEFAULT NULL,
  `ipos_metodo_ingreso` bigint DEFAULT NULL,
  `ipos_ruta_doc_foto` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_dni` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_certvota` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_titulo` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_comprobantepago` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_recordacademico` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_senescyt` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_hojadevida` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_cartarecomendacion` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_certificadolaboral` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_certificadoingles` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_otrorecord` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_certificadonosancion` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_syllabus` varchar(200) DEFAULT NULL,
  `ipos_ruta_doc_homologacion` varchar(200) DEFAULT NULL,
  `ipos_mensaje1` varchar(1) DEFAULT NULL,
  `ipos_mensaje2` varchar(1) DEFAULT NULL,
  `ipos_estado` varchar(1) NOT NULL,
  `ipos_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ipos_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ipos_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`ipos_id`)
);

CREATE TABLE `informacion_laboral` (
  `ilab_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint DEFAULT NULL,
  `ilab_empresa` varchar(200) DEFAULT NULL,
  `ilab_cargo` varchar(200) DEFAULT NULL,
  `ilab_telefono_emp` varchar(10) DEFAULT NULL,
  `ilab_prov_emp` bigint DEFAULT NULL,
  `ilab_ciu_emp` bigint DEFAULT NULL,
  `ilab_parroquia` varchar(200) DEFAULT NULL,
  `ilab_direccion_emp` varchar(200) DEFAULT NULL,
  `ilab_añoingreso_emp` varchar(200) DEFAULT NULL,
  `ilab_correo_emp` varchar(200) DEFAULT NULL,
  `ilab_cat_ocupacional` varchar(200) DEFAULT NULL,
  `ilab_estado` varchar(1) NOT NULL,
  `ilab_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ilab_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ilab_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`ilab_id`)
);

CREATE TABLE `info_estudiante_investigacion` (
  `iein_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint NOT NULL,
  `iein_articulos_investigacion` varchar(500) DEFAULT NULL,
  `iein_area_investigacion` varchar(500) DEFAULT NULL,
  `iein_estado` varchar(1) NOT NULL,
  `iein_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `iein_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `iein_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`iein_id`)
);

CREATE TABLE `info_docencia_estudiante` (
  `ides_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint NOT NULL,
  `ides_año_docencia` varchar(100) DEFAULT NULL,
  `ides_area_docencia` varchar(300) DEFAULT NULL,
  `ides_estado` varchar(1) NOT NULL,
  `ides_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ides_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ides_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`ides_id`)
);


CREATE TABLE `info_discapacidad_est` (
  `ides_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint NOT NULL,
  `tdis_id` bigint NOT NULL,
  `ides_porcentaje` varchar(3) DEFAULT NULL,
  `ides_estado` varchar(1) NOT NULL,
  `ides_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ides_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ides_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`ides_id`)
);

CREATE TABLE `estudiante_instruccion` (
  `eins_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint DEFAULT NULL,
  `eins_titulo3ernivel` varchar(200) DEFAULT NULL,
  `eins_institucion3ernivel` varchar(150) DEFAULT NULL,
  `eins_añogrado3ernivel` varchar(50) DEFAULT NULL,
  `eins_titulo4tonivel` varchar(200) DEFAULT NULL,
  `eins_institucion4tonivel` varchar(150) DEFAULT NULL,
  `eins_añogrado4tonivel` varchar(50) DEFAULT NULL,
  `eins_estado` varchar(1) NOT NULL,
  `eins_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `eins_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `eins_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`eins_id`)
);

CREATE TABLE `estudiante_idiomas` (
  `eidi_id` bigint NOT NULL AUTO_INCREMENT,
  `per_id` bigint NOT NULL,
  `idi_id` bigint NOT NULL,
  `nidi_id` bigint NOT NULL,
  `eidi_nombre_idioma` varchar(200) DEFAULT NULL,
  `eidi_estado` varchar(1) NOT NULL,
  `eidi_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `eidi_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `eidi_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`eidi_id`)
);