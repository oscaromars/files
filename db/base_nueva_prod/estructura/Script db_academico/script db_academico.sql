-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: localhost    Database: db_academico
-- ------------------------------------------------------
-- Server version	5.6.47

/* !40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/* !40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/* !40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/* !50503 SET NAMES utf8 */;
/* !40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/* !40103 SET TIME_ZONE='+00:00' */;
/* !40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/* !40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/* !40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/* !40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cancelacion_registro_online`
--

DROP TABLE IF EXISTS db_academico.`cancelacion_registro_online`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`cancelacion_registro_online` (
  `cron_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ron_id` bigint(20) NOT NULL,
  `per_id` bigint(20) NOT NULL,
  `pla_id` bigint(20) NOT NULL,
  `paca_id` bigint(20) NOT NULL,
  `rpm_id` bigint(20) DEFAULT NULL,
  `cron_estado_cancelacion` varchar(1) NOT NULL DEFAULT '0',
  `cron_aprueba` bigint(20) DEFAULT NULL,
  `cron_confirma` bigint(20) DEFAULT NULL,
  `cron_estado` varchar(1) NOT NULL,
  `cron_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cron_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `cron_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`cron_id`),
  KEY `ron_id` (`ron_id`),
  CONSTRAINT `cancelacion_registro_online_ibfk_1` FOREIGN KEY (`ron_id`) REFERENCES `registro_online` (`ron_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cancelacion_registro_online_item`
--

DROP TABLE IF EXISTS db_academico.`cancelacion_registro_online_item`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`cancelacion_registro_online_item` (
  `croi_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cron_id` bigint(20) NOT NULL,
  `roi_id` bigint(20) NOT NULL,
  `croi_estado` varchar(1) NOT NULL,
  `croi_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `croi_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `croi_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`croi_id`),
  KEY `cron_id` (`cron_id`),
  KEY `roi_id` (`roi_id`),
  CONSTRAINT `cancelacion_registro_online_item_ibfk_1` FOREIGN KEY (`cron_id`) REFERENCES `cancelacion_registro_online` (`cron_id`),
  CONSTRAINT `cancelacion_registro_online_item_ibfk_2` FOREIGN KEY (`roi_id`) REFERENCES `registro_online_item` (`roi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estado_nota_academico`
--

DROP TABLE IF EXISTS db_academico.`estado_nota_academico`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`estado_nota_academico` (
  `enac_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `enac_asig_estado` varchar(40) NOT NULL,
  `enac_usuario_ingreso` bigint(20) NOT NULL,
  `enac_usuario_modifica` bigint(20) DEFAULT NULL,
  `enac_estado` varchar(1) NOT NULL,
  `enac_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enac_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `enac_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`enac_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fechas_vencimiento_pago`
--

DROP TABLE IF EXISTS db_academico.`fechas_vencimiento_pago`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`fechas_vencimiento_pago` (
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gasto_administrativo`
--

DROP TABLE IF EXISTS db_academico.`gasto_administrativo`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`gasto_administrativo` (
  `gadm_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uaca_id` bigint(20) NOT NULL,
  `mod_id` bigint(20) NOT NULL,
  `gadm_bloque` varchar(5) NOT NULL,
  `gadm_gastos_varios` double DEFAULT NULL,
  `gadm_asociacion` double DEFAULT NULL,
  `gadm_fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gadm_fecha_fin` timestamp NULL DEFAULT NULL,
  `gadm_estado_activo` varchar(1) DEFAULT NULL,
  `gadm_usuario_creacion` bigint(20) NOT NULL,
  `gadm_usuario_modificacion` bigint(20) DEFAULT NULL,
  `gadm_estado` varchar(1) NOT NULL,
  `gadm_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gadm_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `gadm_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`gadm_id`),
  KEY `uaca_id` (`uaca_id`),
  KEY `mod_id` (`mod_id`),
  CONSTRAINT `gasto_administrativo_ibfk_1` FOREIGN KEY (`uaca_id`) REFERENCES `unidad_academica` (`uaca_id`),
  CONSTRAINT `gasto_administrativo_ibfk_2` FOREIGN KEY (`mod_id`) REFERENCES `modalidad` (`mod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historico_siga_prueba`
--

DROP TABLE IF EXISTS db_academico.`historico_siga_prueba`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`historico_siga_prueba` (
  `hspr_id` int(11) NOT NULL AUTO_INCREMENT,
  `per_id` int(11) DEFAULT NULL,
  `id_est_siga` int(11) DEFAULT NULL,
  `cedula` varchar(45) DEFAULT NULL,
  `per_apellido` varchar(100) DEFAULT NULL,
  `per_apellido_segundo` varchar(100) DEFAULT NULL,
  `per_nombre` varchar(100) DEFAULT NULL,
  `per_nombre_segundo` varchar(100) DEFAULT NULL,
  `hspr_carrera` varchar(100) DEFAULT NULL,
  `hspr_modalidad` varchar(1) DEFAULT NULL,
  `hspr_categoria` varchar(1) DEFAULT NULL,
  `id_materia` int(11) DEFAULT NULL,
  `hspr_creditos` int(11) DEFAULT NULL,
  `hspr_materia` varchar(100) DEFAULT NULL,
  `hspr_nota` float DEFAULT NULL,
  `id_periodo` int(11) DEFAULT NULL,
  `hspr_periodo` varchar(100) DEFAULT NULL,
  `n_vez` int(11) DEFAULT NULL,
  `cod_legal` varchar(100) DEFAULT NULL,
  `hspr_aprobada` varchar(100) DEFAULT NULL,
  `bloque_academico` varchar(300) DEFAULT NULL,
  `asi_id` bigint(20) DEFAULT NULL,
  `maca_id` bigint(20) DEFAULT NULL,
  `mod_id` bigint(20) DEFAULT NULL,
  `eaca_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`hspr_id`),
  KEY `PER_ID` (`per_id`),
  KEY `MACA_ID` (`maca_id`),
  KEY `ASI_ID` (`asi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=508777 DEFAULT CHARSET=latin1;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `malla_academico_estudiante`
--

DROP TABLE IF EXISTS db_academico.`malla_academico_estudiante`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`malla_academico_estudiante` (
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
) ENGINE=InnoDB AUTO_INCREMENT=196606 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planificar_periodo_academico`
--

DROP TABLE IF EXISTS db_academico.`planificar_periodo_academico`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`planificar_periodo_academico` (
  `peac_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `maes_id` bigint(20) DEFAULT NULL,
  `paca_id` bigint(20) DEFAULT NULL,
  `peac_usuario_ingreso` bigint(20) DEFAULT NULL,
  `peac_usuario_modifica` bigint(20) DEFAULT NULL,
  `peac_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `peac_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `peac_estado` varchar(1) DEFAULT NULL,
  `peac_estado_logico` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`peac_id`)
) ENGINE=InnoDB AUTO_INCREMENT=595 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `programa_costo_credito`
--

DROP TABLE IF EXISTS db_academico.`programa_costo_credito`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`programa_costo_credito` (
  `pccr_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eaca_id` bigint(20) NOT NULL,
  `mod_id` bigint(20) NOT NULL,
  `maca_id` bigint(20) DEFAULT NULL,
  `pccr_categoria` varchar(45) DEFAULT NULL,
  `pccr_creditos` int(11) NOT NULL,
  `pccr_costo_credito` decimal(10,7) DEFAULT NULL,
  `pccr_costo_graduacion` decimal(10,2) DEFAULT NULL,
  `pccr_costo_carrera` decimal(10,2) DEFAULT NULL,
  `pccr_horas_credito` int(11) NOT NULL,
  `pccr_anios_duracion` int(11) DEFAULT NULL,
  `pccr_titulo` varchar(250) NOT NULL,
  `pccr_estado` varchar(1) NOT NULL,
  `pccr_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pccr_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `pccr_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`pccr_id`),
  KEY `eaca_id` (`eaca_id`),
  KEY `mod_id` (`mod_id`),
  CONSTRAINT `programa_costo_credito_ibfk_1` FOREIGN KEY (`eaca_id`) REFERENCES `estudio_academico` (`eaca_id`),
  CONSTRAINT `programa_costo_credito_ibfk_2` FOREIGN KEY (`mod_id`) REFERENCES `modalidad` (`mod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1351 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `promedio_malla_academico`
--

DROP TABLE IF EXISTS db_academico.`promedio_malla_academico`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`promedio_malla_academico` (
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
) ENGINE=InnoDB AUTO_INCREMENT=196606 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_adicional_materias`
--

DROP TABLE IF EXISTS db_academico.`registro_adicional_materias`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`registro_adicional_materias` (
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
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_configuracion`
--

DROP TABLE IF EXISTS db_academico.`registro_configuracion`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`registro_configuracion` (
  `rco_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pla_id` bigint(20) NOT NULL,
  `rco_fecha_inicio` timestamp NULL DEFAULT NULL,
  `rco_fecha_fin` timestamp NULL DEFAULT NULL,
  `rco_fecha_ini_aplicacion` timestamp NULL DEFAULT NULL,
  `rco_fecha_fin_aplicacion` timestamp NULL DEFAULT NULL,
  `rco_fecha_ini_registro` timestamp NULL DEFAULT NULL,
  `rco_fecha_fin_registro` timestamp NULL DEFAULT NULL,
  `rco_fecha_ini_periodoextra` timestamp NULL DEFAULT NULL,
  `rco_fecha_fin_periodoextra` timestamp NULL DEFAULT NULL,
  `rco_fecha_ini_clases` timestamp NULL DEFAULT NULL,
  `rco_fecha_fin_clases` timestamp NULL DEFAULT NULL,
  `rco_fecha_ini_examenes` timestamp NULL DEFAULT NULL,
  `rco_fecha_fin_examenes` timestamp NULL DEFAULT NULL,
  `rco_num_bloques` int(11) NOT NULL,
  `rco_estado` varchar(1) NOT NULL,
  `rco_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rco_usuario_modifica` bigint(20) DEFAULT NULL,
  `rco_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `rco_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`rco_id`),
  KEY `pla_id` (`pla_id`),
  CONSTRAINT `registro_configuracion_ibfk_1` FOREIGN KEY (`pla_id`) REFERENCES `planificacion` (`pla_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_online`
--

DROP TABLE IF EXISTS db_academico.`registro_online`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`registro_online` (
  `ron_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `per_id` bigint(20) NOT NULL,
  `pes_id` bigint(20) NOT NULL,
  `ron_num_orden` bigint(20) NOT NULL,
  `ron_fecha_registro` timestamp NULL DEFAULT NULL,
  `ron_anio` varchar(4) DEFAULT NULL,
  `ron_semestre` varchar(1) DEFAULT NULL,
  `ron_modalidad` varchar(80) DEFAULT NULL,
  `ron_carrera` varchar(500) DEFAULT NULL,
  `ron_categoria_est` varchar(2) DEFAULT NULL,
  `ron_valor_arancel` decimal(10,2) DEFAULT NULL,
  `ron_valor_matricula` decimal(10,2) DEFAULT NULL,
  `ron_valor_gastos_adm` decimal(10,2) DEFAULT NULL,
  `ron_valor_gastos_pendientes` decimal(10,2) DEFAULT NULL,
  `ron_valor_aso_estudiante` decimal(10,2) DEFAULT NULL,
  `ron_estado_registro` varchar(1) NOT NULL,
  `ron_estado_cancelacion` varchar(1) NOT NULL DEFAULT '0',
  `ron_estado` varchar(1) NOT NULL,
  `ron_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ron_usuario_modifica` bigint(20) DEFAULT NULL,
  `ron_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `ron_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`ron_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_online_cuota`
--

DROP TABLE IF EXISTS db_academico.`registro_online_cuota`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`registro_online_cuota` (
  `roc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ron_id` bigint(20) NOT NULL,
  `rpm_id` bigint(20) NOT NULL,
  `fpe_id` varchar(45) DEFAULT NULL,
  `roc_num_cuota` varchar(50) DEFAULT NULL,
  `roc_vencimiento` varchar(100) DEFAULT NULL,
  `roc_porcentaje` varchar(10) DEFAULT NULL,
  `roc_costo` decimal(10,2) DEFAULT NULL,
  `roc_estado` varchar(1) NOT NULL,
  `roc_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `roc_usuario_modifica` bigint(20) DEFAULT NULL,
  `roc_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `roc_estado_logico` varchar(1) NOT NULL,
  `roc_estado_pago` varchar(1) NOT NULL,
  PRIMARY KEY (`roc_id`),
  KEY `ron_id` (`ron_id`),
  KEY `rpm_id` (`rpm_id`),
  CONSTRAINT `registro_online_cuota_ibfk_1` FOREIGN KEY (`ron_id`) REFERENCES `registro_online` (`ron_id`),
  CONSTRAINT `registro_online_cuota_ibfk_2` FOREIGN KEY (`rpm_id`) REFERENCES `registro_pago_matricula` (`rpm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=394 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_online_item`
--

DROP TABLE IF EXISTS db_academico.`registro_online_item`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`registro_online_item` (
  `roi_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ron_id` bigint(20) NOT NULL,
  `roi_materia_cod` varchar(50) DEFAULT NULL,
  `roi_materia_nombre` varchar(100) DEFAULT NULL,
  `roi_creditos` varchar(4) DEFAULT NULL,
  `roi_costo` decimal(10,2) DEFAULT NULL,
  `roi_bloque` varchar(4) DEFAULT NULL,
  `roi_hora` varchar(4) DEFAULT NULL,
  `roi_estado` varchar(1) NOT NULL,
  `roi_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `roi_usuario_modifica` bigint(20) DEFAULT NULL,
  `roi_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `roi_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`roi_id`),
  KEY `ron_id` (`ron_id`),
  CONSTRAINT `registro_online_item_ibfk_1` FOREIGN KEY (`ron_id`) REFERENCES `registro_online` (`ron_id`)
) ENGINE=InnoDB AUTO_INCREMENT=538 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_pago_matricula`
--

DROP TABLE IF EXISTS db_academico.`registro_pago_matricula`;
/* !40101 SET @saved_cs_client     = @@character_set_client */;
/* !50503 SET character_set_client = utf8mb4 */;
CREATE TABLE db_academico.`registro_pago_matricula` (
  `rpm_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `per_id` bigint(20) NOT NULL,
  `pla_id` bigint(20) NOT NULL,
  `ron_id` bigint(20) DEFAULT NULL,
  `fpag_id` bigint(20) DEFAULT NULL,
  `rpm_archivo` text NOT NULL,
  `rpm_estado_aprobacion` varchar(1) NOT NULL DEFAULT '0',
  `rpm_estado_generado` varchar(1) NOT NULL DEFAULT '0',
  `rpm_acepta_terminos` varchar(1) NOT NULL DEFAULT '0',
  `rpm_tipo_pago` varchar(1) DEFAULT NULL,
  `rpm_hoja_matriculacion` varchar(200) DEFAULT NULL,
  `rpm_usuario_apruebareprueba` bigint(20) DEFAULT NULL,
  `rpm_fecha_transaccion` timestamp NULL DEFAULT NULL,
  `rpm_total` decimal(10,2) DEFAULT NULL,
  `rpm_interes` decimal(10,2) DEFAULT NULL,
  `rpm_financiamiento` decimal(10,2) DEFAULT NULL,
  `rpm_observacion` varchar(1000) DEFAULT NULL,
  `rpm_estado` varchar(1) NOT NULL,
  `rpm_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rpm_usuario_modifica` bigint(20) DEFAULT NULL,
  `rpm_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `rpm_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`rpm_id`),
  KEY `pla_id` (`pla_id`),
  CONSTRAINT `registro_pago_matricula_ibfk_2` FOREIGN KEY (`pla_id`) REFERENCES `planificacion` (`pla_id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8;
/* !40101 SET character_set_client = @saved_cs_client */;
/* !40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/* !40101 SET SQL_MODE=@OLD_SQL_MODE */;
/* !40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/* !40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/* !40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/* !40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/* !40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/* !40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-01  9:59:09
