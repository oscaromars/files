
-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: financiero
-- ------------------------------------------------------
-- Server version	5.6.40

DROP SCHEMA IF EXISTS `db_financiero`;
CREATE SCHEMA IF NOT EXISTS `db_financiero` DEFAULT CHARACTER SET utf8 ;
USE `db_financiero` ;

-- GRANT ALL PRIVILEGES ON `db_financiero`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- `cat_id` bigint(20) not null auto_increment primary key,
-- Table structure for table `CATALOGO`
--

DROP TABLE IF EXISTS `catalogo`;
CREATE TABLE `catalogo` (  
  `cat_cod_cta` varchar(12) not null primary key,
  `cat_cod_pad` varchar(12) default null,
  `cat_nom_cta` varchar(120) default null,
  `cat_tip_cta` varchar(2) default null,
  `cat_tip_ele` varchar(30) default null,
  `cat_tip_reg` varchar(30) default null,
  `cat_sdb` decimal(15,2) default null,
  `cat_shb` decimal(15,2) default null,
  `cat_d00` decimal(15,2) default null,
  `cat_h00` decimal(15,2) default null,
  `cat_d01` decimal(15,2) default null,
  `cat_h01` decimal(15,2) default null,
  `cat_d02` decimal(15,2) default null,
  `cat_h02` decimal(15,2) default null,
  `cat_d03` decimal(15,2) default null,
  `cat_h03` decimal(15,2) default null,
  `cat_d04` decimal(15,2) default null,
  `cat_h04` decimal(15,2) default null,
  `cat_d05` decimal(15,2) default null,
  `cat_h05` decimal(15,2) default null,
  `cat_d06` decimal(15,2) default null,
  `cat_h06` decimal(15,2) default null,
  `cat_d07` decimal(15,2) default null,
  `cat_h07` decimal(15,2) default null,
  `cat_d08` decimal(15,2) default null,
  `cat_h08` decimal(15,2) default null,
  `cat_d09` decimal(15,2) default null,
  `cat_h09` decimal(15,2) default null,
  `cat_d10` decimal(15,2) default null,
  `cat_h10` decimal(15,2) default null,
  `cat_d11` decimal(15,2) default null,
  `cat_h11` decimal(15,2) default null,
  `cat_d12` decimal(15,2) default null,
  `cat_h12` decimal(15,2) default null,
  `estado` varchar(1) DEFAULT NULL,
  `usuario_creacion` varchar(45) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL,
  `equipo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- `cat_id` bigint(20) not null auto_increment primary key,
-- Table structure for table `CATALOGO`
--

DROP TABLE IF EXISTS `catalogo_2019`;
CREATE TABLE `catalogo_2019` (  
  `cat_cod_cta` varchar(12) not null primary key,
  `cat_cod_pad` varchar(12) default null,
  `cat_nom_cta` varchar(120) default null,
  `cat_tip_cta` varchar(2) default null,
  `cat_tip_ele` varchar(30) default null,
  `cat_tip_reg` varchar(30) default null,
  `cat_sdb` decimal(15,2) default null,
  `cat_shb` decimal(15,2) default null,
  `cat_d00` decimal(15,2) default null,
  `cat_h00` decimal(15,2) default null,
  `cat_d01` decimal(15,2) default null,
  `cat_h01` decimal(15,2) default null,
  `cat_d02` decimal(15,2) default null,
  `cat_h02` decimal(15,2) default null,
  `cat_d03` decimal(15,2) default null,
  `cat_h03` decimal(15,2) default null,
  `cat_d04` decimal(15,2) default null,
  `cat_h04` decimal(15,2) default null,
  `cat_d05` decimal(15,2) default null,
  `cat_h05` decimal(15,2) default null,
  `cat_d06` decimal(15,2) default null,
  `cat_h06` decimal(15,2) default null,
  `cat_d07` decimal(15,2) default null,
  `cat_h07` decimal(15,2) default null,
  `cat_d08` decimal(15,2) default null,
  `cat_h08` decimal(15,2) default null,
  `cat_d09` decimal(15,2) default null,
  `cat_h09` decimal(15,2) default null,
  `cat_d10` decimal(15,2) default null,
  `cat_h10` decimal(15,2) default null,
  `cat_d11` decimal(15,2) default null,
  `cat_h11` decimal(15,2) default null,
  `cat_d12` decimal(15,2) default null,
  `cat_h12` decimal(15,2) default null,
  `estado` varchar(1) DEFAULT NULL,
  `usuario_creacion` varchar(45) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL,
  `equipo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `entidad_bancaria`
--

DROP TABLE IF EXISTS `entidad_bancaria`; -- CB0001
CREATE TABLE `entidad_bancaria` (
  `eban_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eban_nombre` varchar(50) DEFAULT NULL,
  `eban_nomenclatura` varchar(3) DEFAULT '',
  `estado` varchar(1) DEFAULT NULL,
  `usuario_creacion` varchar(45) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL,
  `equipo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`eban_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Table structure for table `tarjeta_credito`
--

DROP TABLE IF EXISTS `tarjeta_credito`;-- CB0001T
CREATE TABLE `tarjeta_credito` (
  `tcre_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tcre_nombre` varchar(50) DEFAULT NULL,
  `tcre_codigo` varchar(2) DEFAULT NULL,
  `estado` varchar(1) DEFAULT NULL,
  `usuario_creacion` varchar(45) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL,
  `equipo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`tcre_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Table structure for table `cuenta_bancarias`
--

DROP TABLE IF EXISTS `cuenta_bancarias`;-- CB0002
CREATE TABLE `cuenta_bancarias` (
  `cban_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cban_codigo` varchar(10) NOT NULL DEFAULT '',
  `cban_nombre` varchar(40) DEFAULT NULL,
  `cban_tipo_cuenta` varchar(1) DEFAULT NULL,
  `cban_numero_cuenta` varchar(20) NOT NULL DEFAULT '',
  `cban_numero_cheque` varchar(10) DEFAULT NULL,
  `cban_titular` varchar(30) DEFAULT NULL,
  `cban_contacto` varchar(30) DEFAULT NULL,
  `cban_tel_contacto` decimal(10,0) DEFAULT NULL,
  `cban_n_mov_b` decimal(5,0) default null,
  `v_dep_b` decimal(14,2) default null,
  `v_ret_b` decimal(14,2) default null,
  `v_sal_b` decimal(14,2) default null,
  `v_sal_i` decimal(14,2) default null,
  `f_u_act` date default null,
  `cmes_01` varchar(1) default null,
  `cmes_02` varchar(1) default null,
  `cmes_03` varchar(1) default null,
  `cmes_04` varchar(1) default null,
  `cmes_05` varchar(1) default null,
  `cmes_06` varchar(1) default null,
  `cmes_07` varchar(1) default null,
  `cmes_08` varchar(1) default null,
  `cmes_09` varchar(1) default null,
  `cmes_10` varchar(1) default null,
  `cmes_11` varchar(1) default null,
  `cmes_12` varchar(1) default null,
  `cta_con` varchar(12) default null,
  `che_ord` varchar(5) default null,
  `che_val` varchar(5) default null,
  `che_l01` varchar(5) default null,
  `che_l02` varchar(5) default null,
  `che_ciu` varchar(5) default null,
  `che_fec` varchar(5) default null,
  `cod_div` varchar(2) default null,
  `mos_sald` varchar(1) default null,
  `estado` varchar(1) DEFAULT NULL,
  `usuario_creacion` varchar(45) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL,
  `equipo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cban_id`,`cban_codigo`,`cban_numero_cuenta`),
  KEY `CB02_CTA_CON_FK` (`CTA_CON`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- TABLAS DE BOTON DE PAGO
DROP TABLE IF EXISTS `vpos_request`;
CREATE TABLE `vpos_request` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT AUTO_INCREMENT PRIMARY KEY,
  `reference` bigint(20) NOT NULL,
  `ordenPago` varchar(20) DEFAULT NULL,
  `tipo_orden` varchar(20) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `currency` varchar(5) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `session` varchar(50) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `returnUrl` varchar(200) DEFAULT NULL,
  `expiration` timestamp NULL DEFAULT NULL,
  `document` varchar(50) DEFAULT NULL,
  `documentType` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `json_request` text DEFAULT NULL,
  `finish_transaccion` int NULL DEFAULT 0,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `vpos_response`;
CREATE TABLE `vpos_response` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT AUTO_INCREMENT PRIMARY KEY,
  `reference` bigint(20) NOT NULL, 
  `requestId` varchar(20) DEFAULT NULL,
  `ordenPago` varchar(20) DEFAULT NULL,
  `tipo_orden` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `reason` varchar(10) DEFAULT NULL,
  `message` varchar(200) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `processUrl` varchar(200) DEFAULT NULL,
  `json_response` text DEFAULT NULL,
  `finish_transaccion` int NULL DEFAULT 0,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `vpos_info_response`;
CREATE TABLE `vpos_info_response` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT AUTO_INCREMENT PRIMARY KEY,
  `reference` bigint(20) NOT NULL,
  `requestId` varchar(20) DEFAULT NULL,
  `ordenPago` varchar(20) DEFAULT NULL,
  `tipo_orden` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `reason` varchar(10) DEFAULT NULL,
  `message` varchar(200) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_reason` varchar(10) DEFAULT NULL,
  `payment_message` varchar(200) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `internalReference` varchar(50) DEFAULT NULL,
  `paymenMethod` varchar(100) DEFAULT NULL,
  `paymentMethodName` varchar(50) DEFAULT NULL,
  `issuerName` varchar(100) DEFAULT NULL,
  `autorization` varchar(100) DEFAULT NULL,
  `receipt` varchar(50) DEFAULT NULL,
  `json_info` text DEFAULT NULL,
  `finish_transaccion` int NULL DEFAULT 0,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `estado_logico` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
