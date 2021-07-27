DROP SCHEMA IF EXISTS `db_bienestar` ;
CREATE SCHEMA IF NOT EXISTS `db_bienestar` DEFAULT CHARACTER SET utf8 ;
USE `db_bienestar` ;

--
-- Table structure for table `criterio_cabecera`
--

DROP TABLE IF EXISTS `criterio_cabecera`;
CREATE TABLE `criterio_cabecera` (
  `crtcab_id` bigint NOT NULL,
  `crtcab_descripcion` varchar(45) NOT NULL,
  `crtcab_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `crtcab_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `crtcab_estado` varchar(1) NOT NULL,
  `crtcab_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`crtcab_id`)
);

--
-- Table structure for table `criterio_detalle`
--

DROP TABLE IF EXISTS `criterio_detalle`;
CREATE TABLE `criterio_detalle` (
  `crtdet_id` bigint NOT NULL,
  `crtcab_id` bigint NOT NULL,
  `fsec_id` bigint NOT NULL,
  `crtdet_descripcion` varchar(80) NOT NULL,
  `crtdet_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `crtdet_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `crtdet_estado` varchar(1) NOT NULL,
  `crtdet_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`crtdet_id`),
  KEY `fk_criterio_detalle_1_idx` (`crtcab_id`),
  KEY `fk_criterio_detalle_2_idx` (`fsec_id`),
  CONSTRAINT `fk_criterio_detalle_1` FOREIGN KEY (`crtcab_id`) REFERENCES `criterio_cabecera` (`crtcab_id`),
  CONSTRAINT `fk_criterio_detalle_2` FOREIGN KEY (`fsec_id`) REFERENCES `formulario_seccion` (`fsec_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);

--
-- Table structure for table `documento_adjuntar`
--

DROP TABLE IF EXISTS `documento_adjuntar`;
CREATE TABLE `documento_adjuntar` (
  `dadj_id` bigint NOT NULL AUTO_INCREMENT,
  `dadj_nombre` varchar(300) NOT NULL,
  `dadj_descripcion` varchar(500) NOT NULL,
  `dadj_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dadj_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `dadj_estado` varchar(1) NOT NULL,
  `dadj_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`dadj_id`)
);

--
-- Table structure for table `documentos_formulario_estudiante`
--

DROP TABLE IF EXISTS `documentos_formulario_estudiante`;
CREATE TABLE `documentos_formulario_estudiante` (
  `dfes_id` bigint NOT NULL,
  `fest_id` bigint NOT NULL,
  `dfes_archivo` varchar(500) NOT NULL,
  `dfes_usuario_ingreso` bigint DEFAULT NULL,
  `dfes_usuario_modifica` bigint DEFAULT NULL,
  `dfes_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dfes_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `dfes_estado` varchar(1) NOT NULL,
  `dfes_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`dfes_id`),
  KEY `fk_documentos_formulario_estudiante_1_idx` (`fest_id`),
  CONSTRAINT `fk_documentos_formulario_estudiante_1` FOREIGN KEY (`fest_id`) REFERENCES `formulario_estudiante` (`fest_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);

--
-- Table structure for table `formulario_condiciones_ponderaciones`
--

DROP TABLE IF EXISTS `formulario_condiciones_ponderaciones`;
CREATE TABLE `formulario_condiciones_ponderaciones` (
  `fcpo_id` bigint NOT NULL,
  `crtdet_id` bigint NOT NULL,
  `fcpo_condicion` varchar(100) NOT NULL,
  `fcpo_ponderacion` double NOT NULL,
  `fcpo_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fcpo_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `fcpo_estado` varchar(1) NOT NULL,
  `fcpo_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`fcpo_id`),
  KEY `fk_formulario_condiciones_ponderaciones_1_idx` (`crtdet_id`),
  CONSTRAINT `fk_formulario_condiciones_ponderaciones_1` FOREIGN KEY (`crtdet_id`) REFERENCES `criterio_detalle` (`crtdet_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);

--
-- Table structure for table `formulario_estudiante`
--

DROP TABLE IF EXISTS `formulario_estudiante`;
CREATE TABLE `formulario_estudiante` (
  `fest_id` bigint NOT NULL,
  `est_id` bigint NOT NULL,
  `per_id` bigint NOT NULL,
  `fest_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fest_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `fest_estado` varchar(1) NOT NULL,
  `fest_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`fest_id`),
  KEY `fk_formulario_estudiante_1_idx` (`est_id`),
  KEY `fk_formulario_estudiante_2_idx` (`per_id`),
  CONSTRAINT `fk_formulario_estudiante_1` FOREIGN KEY (`est_id`) REFERENCES `db_academico`.`estudiante` (`est_id`),
  CONSTRAINT `fk_formulario_estudiante_2` FOREIGN KEY (`per_id`) REFERENCES `db_asgard`.`persona` (`per_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);

--
-- Table structure for table `formulario_estudiante_campo`
--

DROP TABLE IF EXISTS `formulario_estudiante_campo`;
CREATE TABLE `formulario_estudiante_campo` (
  `feca_id` int NOT NULL,
  `fest_id` bigint NOT NULL,
  `fscam_id` bigint NOT NULL,
  `feca_campo_valor` varchar(100) NOT NULL,
  `feca_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `feca_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `feca_estado` varchar(1) NOT NULL,
  `feca_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`feca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `formulario_estudiante_criterio`
--

DROP TABLE IF EXISTS `formulario_estudiante_criterio`;
CREATE TABLE `formulario_estudiante_criterio` (
  `fecr_id` int NOT NULL,
  `fest_id` bigint NOT NULL,
  `crtdet_id` bigint NOT NULL,
  `fcpo_id` bigint NOT NULL,
  `fecr_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecr_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `fecr_estado` varchar(1) NOT NULL,
  `fecr_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`fecr_id`)
);

--
-- Table structure for table `formulario_familiares_discapacitados`
--

DROP TABLE IF EXISTS `formulario_familiares_discapacitados`;
CREATE TABLE `formulario_familiares_discapacitados` (
  `fdis_id` bigint NOT NULL,
  `fest_id` bigint NOT NULL,
  `fcpo_id` bigint NOT NULL,
  `fdis_cedula` varchar(10) NOT NULL,
  `fdis_nombres` varchar(80) NOT NULL,
  `fdis_apellidos` varchar(80) NOT NULL,
  `fdis_numero_conadis` varchar(45) NOT NULL,
  `fdis_tipo_discapacidad` varchar(45) NOT NULL,
  `fdis_porcentaje_discapacidad` double NOT NULL,
  `fdis_descripcion_discapacidad` varchar(200) NOT NULL,
  `fdis_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fdis_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `fdis_estado` varchar(1) NOT NULL,
  `fdis_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`fdis_id`),
  KEY `fk_formulario_familiares_discapacitados_1_idx` (`fest_id`),
  KEY `fk_formulario_familiares_discapacitados_2_idx` (`fcpo_id`),
  CONSTRAINT `fk_formulario_familiares_discapacitados_1` FOREIGN KEY (`fest_id`) REFERENCES `formulario_estudiante` (`fest_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_formulario_familiares_discapacitados_2` FOREIGN KEY (`fcpo_id`) REFERENCES `formulario_condiciones_ponderaciones` (`fcpo_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);

--
-- Table structure for table `formulario_seccion`
--

DROP TABLE IF EXISTS `formulario_seccion`;
CREATE TABLE `formulario_seccion` (
  `fsec_id` bigint NOT NULL,
  `fsec_descripcion` varchar(45) NOT NULL,
  `fsec_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fsec_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `fsec_estado` varchar(1) NOT NULL,
  `fsec_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`fsec_id`)
);

--
-- Table structure for table `formulario_seccion_campo`
--

DROP TABLE IF EXISTS `formulario_seccion_campo`;
CREATE TABLE `formulario_seccion_campo` (
  `fscam_id` int NOT NULL,
  `fsec_id` bigint NOT NULL,
  `fscam_nombre` varchar(80) NOT NULL,
  `fscam_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fscam_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `fscam_estado` varchar(1) NOT NULL,
  `fscam_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`fscam_id`),
  KEY `fk_formulario_seccion_campo_1_idx` (`fsec_id`),
  CONSTRAINT `fk_formulario_seccion_campo_1` FOREIGN KEY (`fsec_id`) REFERENCES `formulario_seccion` (`fsec_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
);