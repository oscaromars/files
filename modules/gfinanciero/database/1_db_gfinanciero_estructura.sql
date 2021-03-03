
-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: db_gfinanciero
-- ------------------------------------------------------
-- Server version	5.6.40

DROP SCHEMA IF EXISTS `db_gfinanciero`;
CREATE SCHEMA IF NOT EXISTS `db_gfinanciero` DEFAULT CHARACTER SET utf8 ;
USE `db_gfinanciero` ;

-- GRANT ALL PRIVILEGES ON `db_gfinanciero`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ADM_ITEMS`
--

DROP TABLE IF EXISTS `ADM_ITEMS`;
CREATE TABLE IF NOT EXISTS `ADM_ITEMS` (
  `IDS_ADM` bigint NOT NULL AUTO_INCREMENT,
  `COD_CEN` varchar(20) DEFAULT NULL,
  `COD_ART` varchar(20) DEFAULT NULL,
  `NUM_BLO` int DEFAULT NULL,
  `NUM_SEM` varchar(2) DEFAULT NULL,
  `NUM_CUO` varchar(2) DEFAULT NULL,
  `TIP_PER` varchar(20) DEFAULT NULL,
  `FREG_INI` date DEFAULT NULL,
  `FREG_FIN` date DEFAULT NULL,
  `FPER_INI` date DEFAULT NULL,
  `FPER_FIN` date DEFAULT NULL,
  `VALOR_P` decimal(14,4) DEFAULT NULL,
  `OBS_GEN` text,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_ADM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AT0001`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AT0002`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AT0003`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AT0004`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AT0005`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AT0010`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0001_2020`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0001_2021`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0002_2020`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0002_2021`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0003_2020`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0003_2021`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA001_2020`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA001_2021`
--



--
-- Estructura de tabla para la tabla `CATA0011_2020`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0011_2021`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0012_2020`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0012_2021`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0013_2020`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATA0013_2021`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATALOGO`
--

DROP TABLE IF EXISTS `CATALOGO`;
CREATE TABLE IF NOT EXISTS `CATALOGO` (
  `COD_CTA` varchar(12) NOT NULL DEFAULT '',
  `COD_PAD` varchar(12) DEFAULT NULL,
  `CTA_SEA` varchar(12) DEFAULT NULL,
  `NOM_CTA` varchar(120) DEFAULT NULL,
  `TIP_CTA` varchar(2) DEFAULT NULL,
  `TIP_ELE` varchar(30) DEFAULT NULL,
  `TIP_REG` varchar(30) DEFAULT NULL,
  `CAT_SDB` decimal(15,2) DEFAULT NULL,
  `CAT_SHB` decimal(15,2) DEFAULT NULL,
  `CAT_D00` decimal(15,2) DEFAULT NULL,
  `CAT_H00` decimal(15,2) DEFAULT NULL,
  `CAT_D01` decimal(15,2) DEFAULT NULL,
  `CAT_H01` decimal(15,2) DEFAULT NULL,
  `CAT_D02` decimal(15,2) DEFAULT NULL,
  `CAT_H02` decimal(15,2) DEFAULT NULL,
  `CAT_D03` decimal(15,2) DEFAULT NULL,
  `CAT_H03` decimal(15,2) DEFAULT NULL,
  `CAT_D04` decimal(15,2) DEFAULT NULL,
  `CAT_H04` decimal(15,2) DEFAULT NULL,
  `CAT_D05` decimal(15,2) DEFAULT NULL,
  `CAT_H05` decimal(15,2) DEFAULT NULL,
  `CAT_D06` decimal(15,2) DEFAULT NULL,
  `CAT_H06` decimal(15,2) DEFAULT NULL,
  `CAT_D07` decimal(15,2) DEFAULT NULL,
  `CAT_H07` decimal(15,2) DEFAULT NULL,
  `CAT_D08` decimal(15,2) DEFAULT NULL,
  `CAT_H08` decimal(15,2) DEFAULT NULL,
  `CAT_D09` decimal(15,2) DEFAULT NULL,
  `CAT_H09` decimal(15,2) DEFAULT NULL,
  `CAT_D10` decimal(15,2) DEFAULT NULL,
  `CAT_H10` decimal(15,2) DEFAULT NULL,
  `CAT_D11` decimal(15,2) DEFAULT NULL,
  `CAT_H11` decimal(15,2) DEFAULT NULL,
  `CAT_D12` decimal(15,2) DEFAULT NULL,
  `CAT_H12` decimal(15,2) DEFAULT NULL,
  `ESTATUS` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_CTA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATALOGO_2020`
--

DROP TABLE IF EXISTS `CATALOGO_2020`;
CREATE TABLE IF NOT EXISTS `CATALOGO_2020` (
  `COD_CTA` varchar(12) NOT NULL DEFAULT '',
  `COD_PAD` varchar(12) DEFAULT NULL,
  `CTA_SEA` varchar(12) DEFAULT NULL,
  `NOM_CTA` varchar(120) DEFAULT NULL,
  `TIP_CTA` varchar(2) DEFAULT NULL,
  `TIP_ELE` varchar(30) DEFAULT NULL,
  `TIP_REG` varchar(30) DEFAULT NULL,
  `CAT_SDB` decimal(15,2) DEFAULT NULL,
  `CAT_SHB` decimal(15,2) DEFAULT NULL,
  `CAT_D00` decimal(15,2) DEFAULT NULL,
  `CAT_H00` decimal(15,2) DEFAULT NULL,
  `CAT_D01` decimal(15,2) DEFAULT NULL,
  `CAT_H01` decimal(15,2) DEFAULT NULL,
  `CAT_D02` decimal(15,2) DEFAULT NULL,
  `CAT_H02` decimal(15,2) DEFAULT NULL,
  `CAT_D03` decimal(15,2) DEFAULT NULL,
  `CAT_H03` decimal(15,2) DEFAULT NULL,
  `CAT_D04` decimal(15,2) DEFAULT NULL,
  `CAT_H04` decimal(15,2) DEFAULT NULL,
  `CAT_D05` decimal(15,2) DEFAULT NULL,
  `CAT_H05` decimal(15,2) DEFAULT NULL,
  `CAT_D06` decimal(15,2) DEFAULT NULL,
  `CAT_H06` decimal(15,2) DEFAULT NULL,
  `CAT_D07` decimal(15,2) DEFAULT NULL,
  `CAT_H07` decimal(15,2) DEFAULT NULL,
  `CAT_D08` decimal(15,2) DEFAULT NULL,
  `CAT_H08` decimal(15,2) DEFAULT NULL,
  `CAT_D09` decimal(15,2) DEFAULT NULL,
  `CAT_H09` decimal(15,2) DEFAULT NULL,
  `CAT_D10` decimal(15,2) DEFAULT NULL,
  `CAT_H10` decimal(15,2) DEFAULT NULL,
  `CAT_D11` decimal(15,2) DEFAULT NULL,
  `CAT_H11` decimal(15,2) DEFAULT NULL,
  `CAT_D12` decimal(15,2) DEFAULT NULL,
  `CAT_H12` decimal(15,2) DEFAULT NULL,
  `ESTATUS` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATALOGO_2021`
--

DROP TABLE IF EXISTS `CATALOGO_2021`;
CREATE TABLE IF NOT EXISTS `CATALOGO_2021` (
  `COD_CTA` varchar(12) NOT NULL DEFAULT '',
  `COD_PAD` varchar(12) DEFAULT NULL,
  `CTA_SEA` varchar(12) DEFAULT NULL,
  `NOM_CTA` varchar(120) DEFAULT NULL,
  `TIP_CTA` varchar(2) DEFAULT NULL,
  `TIP_ELE` varchar(30) DEFAULT NULL,
  `TIP_REG` varchar(30) DEFAULT NULL,
  `CAT_SDB` decimal(15,2) DEFAULT NULL,
  `CAT_SHB` decimal(15,2) DEFAULT NULL,
  `CAT_D00` decimal(15,2) DEFAULT NULL,
  `CAT_H00` decimal(15,2) DEFAULT NULL,
  `CAT_D01` decimal(15,2) DEFAULT NULL,
  `CAT_H01` decimal(15,2) DEFAULT NULL,
  `CAT_D02` decimal(15,2) DEFAULT NULL,
  `CAT_H02` decimal(15,2) DEFAULT NULL,
  `CAT_D03` decimal(15,2) DEFAULT NULL,
  `CAT_H03` decimal(15,2) DEFAULT NULL,
  `CAT_D04` decimal(15,2) DEFAULT NULL,
  `CAT_H04` decimal(15,2) DEFAULT NULL,
  `CAT_D05` decimal(15,2) DEFAULT NULL,
  `CAT_H05` decimal(15,2) DEFAULT NULL,
  `CAT_D06` decimal(15,2) DEFAULT NULL,
  `CAT_H06` decimal(15,2) DEFAULT NULL,
  `CAT_D07` decimal(15,2) DEFAULT NULL,
  `CAT_H07` decimal(15,2) DEFAULT NULL,
  `CAT_D08` decimal(15,2) DEFAULT NULL,
  `CAT_H08` decimal(15,2) DEFAULT NULL,
  `CAT_D09` decimal(15,2) DEFAULT NULL,
  `CAT_H09` decimal(15,2) DEFAULT NULL,
  `CAT_D10` decimal(15,2) DEFAULT NULL,
  `CAT_H10` decimal(15,2) DEFAULT NULL,
  `CAT_D11` decimal(15,2) DEFAULT NULL,
  `CAT_H11` decimal(15,2) DEFAULT NULL,
  `CAT_D12` decimal(15,2) DEFAULT NULL,
  `CAT_H12` decimal(15,2) DEFAULT NULL,
  `ESTATUS` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CAT_ARANCEL`
--

DROP TABLE IF EXISTS `CAT_ARANCEL`;
CREATE TABLE IF NOT EXISTS `CAT_ARANCEL` (
  `COD_CAT` varchar(1) NOT NULL,
  `NOM_CAT` varchar(10) DEFAULT NULL,
  `VAL_ARA` decimal(14,2) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_CAT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0000`
--

DROP TABLE IF EXISTS `CB0000`;
CREATE TABLE IF NOT EXISTS `CB0000` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `NUM_ING` varchar(10) DEFAULT NULL,
  `NUM_EGR` varchar(10) DEFAULT NULL,
  `NUM_DEP` varchar(10) DEFAULT NULL,
  `F_I_PER` varchar(10) DEFAULT NULL,
  `F_F_PER` date DEFAULT NULL,
  `F_U_ACT` date DEFAULT NULL,
  `NUM_RET` varchar(10) DEFAULT NULL,
  `CUE_CAJ` varchar(9) DEFAULT NULL,
  `NUM_POS` varchar(10) DEFAULT NULL,
  `NUM_DEB` varchar(10) DEFAULT NULL,
  `NUM_S_R` varchar(7) DEFAULT NULL,
  `NUM_A_R` varchar(10) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0001`
--

DROP TABLE IF EXISTS `CB0001`;
CREATE TABLE IF NOT EXISTS `CB0001` (
  `IDS_BAN` bigint NOT NULL AUTO_INCREMENT,
  `NOM_BAN` varchar(50) DEFAULT NULL,
  `NOMEN_B` varchar(3) DEFAULT '',
  `COD_BAN` varchar(10) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_BAN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0001A`
--

DROP TABLE IF EXISTS `CB0001A`;
CREATE TABLE IF NOT EXISTS `CB0001A` (
  `IDS_BAU` bigint NOT NULL AUTO_INCREMENT,
  `IDS_BAN` bigint DEFAULT NULL,
  `NOM_BAU` varchar(50) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_BAU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0001B`
--

DROP TABLE IF EXISTS `CB0001B`;
CREATE TABLE IF NOT EXISTS `CB0001B` (
  `IDS_TBN` bigint NOT NULL AUTO_INCREMENT,
  `IDS_BAN` bigint NOT NULL,
  `IDS_TAC` bigint NOT NULL,
  `NOMBRE` varchar(100) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_TBN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0001P`
--

DROP TABLE IF EXISTS `CB0001P`;
CREATE TABLE IF NOT EXISTS `CB0001P` (
  `IDS_PPG` bigint NOT NULL AUTO_INCREMENT,
  `NOM_PPG` varchar(50) DEFAULT NULL,
  `NUM_PLZ` int DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_PPG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0001T`
--

DROP TABLE IF EXISTS `CB0001T`;
CREATE TABLE IF NOT EXISTS `CB0001T` (
  `IDS_TAC` bigint NOT NULL AUTO_INCREMENT,
  `NOM_TAC` varchar(50) DEFAULT NULL,
  `COD_TAC` varchar(2) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_TAC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0002`
--

DROP TABLE IF EXISTS `CB0002`;
CREATE TABLE IF NOT EXISTS `CB0002` (
  `COD_BAN` varchar(10) NOT NULL DEFAULT '',
  `IDS_BAN` bigint DEFAULT NULL,
  `NOM_BAN` varchar(40) DEFAULT NULL,
  `T_CTA_B` varchar(1) DEFAULT NULL,
  `N_CTA_B` varchar(20) NOT NULL DEFAULT '',
  `N_CHE_B` varchar(10) DEFAULT NULL,
  `TITULAR` varchar(30) DEFAULT NULL,
  `CTO_BAN` varchar(30) DEFAULT NULL,
  `TLF_CTO` decimal(10,0) DEFAULT NULL,
  `N_MOV_B` decimal(5,0) DEFAULT NULL,
  `V_DEP_B` decimal(14,2) DEFAULT NULL,
  `V_RET_B` decimal(14,2) DEFAULT NULL,
  `V_SAL_B` decimal(14,2) DEFAULT NULL,
  `V_SAL_I` decimal(14,2) DEFAULT NULL,
  `F_U_ACT` date DEFAULT NULL,
  `CMES_01` varchar(1) DEFAULT NULL,
  `CMES_02` varchar(1) DEFAULT NULL,
  `CMES_03` varchar(1) DEFAULT NULL,
  `CMES_04` varchar(1) DEFAULT NULL,
  `CMES_05` varchar(1) DEFAULT NULL,
  `CMES_06` varchar(1) DEFAULT NULL,
  `CMES_07` varchar(1) DEFAULT NULL,
  `CMES_08` varchar(1) DEFAULT NULL,
  `CMES_09` varchar(1) DEFAULT NULL,
  `CMES_10` varchar(1) DEFAULT NULL,
  `CMES_11` varchar(1) DEFAULT NULL,
  `CMES_12` varchar(1) DEFAULT NULL,
  `CTA_CON` varchar(12) DEFAULT NULL,
  `CHE_ORD` varchar(5) DEFAULT NULL,
  `CHE_VAL` varchar(5) DEFAULT NULL,
  `CHE_L01` varchar(5) DEFAULT NULL,
  `CHE_L02` varchar(5) DEFAULT NULL,
  `CHE_CIU` varchar(5) DEFAULT NULL,
  `CHE_FEC` varchar(5) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `MOS_SALD` varchar(1) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `COD_PTO` varchar(3) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BAN`,`N_CTA_B`),
  KEY `CB02_CTA_CON_FK` (`CTA_CON`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0003`
--

DROP TABLE IF EXISTS `CB0003`;
CREATE TABLE IF NOT EXISTS `CB0003` (
  `COD_BAN` varchar(10) NOT NULL DEFAULT '',
  `N_CTA_B` varchar(20) NOT NULL DEFAULT '',
  `NUM_CHQ` decimal(5,0) DEFAULT NULL,
  `N_I_CHQ` decimal(8,0) DEFAULT NULL,
  `N_F_CHQ` decimal(8,0) DEFAULT NULL,
  `N_CHQ_P` decimal(8,0) DEFAULT NULL,
  `CHQ_Y_C` decimal(5,0) DEFAULT NULL,
  `CHQ_N_C` decimal(5,0) DEFAULT NULL,
  `F_U_ACT` date DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BAN`,`N_CTA_B`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0004`
--

DROP TABLE IF EXISTS `CB0004`;
CREATE TABLE IF NOT EXISTS `CB0004` (
  `COD_BAN` varchar(10) NOT NULL DEFAULT '',
  `N_CTA_B` varchar(20) NOT NULL DEFAULT '',
  `C_TRA_E` varchar(2) NOT NULL DEFAULT '',
  `NUMEROD` varchar(10) NOT NULL DEFAULT '',
  `T_TRA_E` varchar(1) DEFAULT NULL,
  `FECHA_T` date DEFAULT NULL,
  `NUM_DEP` varchar(10) DEFAULT NULL,
  `NUM_EGR` varchar(10) NOT NULL DEFAULT '',
  `FEC_ORI` date DEFAULT NULL,
  `VALOR_T` decimal(14,2) DEFAULT NULL,
  `I_TRA_E` varchar(1) DEFAULT NULL,
  `IND_ACT` varchar(1) DEFAULT NULL,
  `TIP_TRA` decimal(1,0) DEFAULT NULL,
  `NOTA_01` varchar(70) DEFAULT NULL,
  `NOTA_02` varchar(70) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `COD_PTO` varchar(3) DEFAULT NULL,
  `FEC_CON` date DEFAULT NULL,
  `IND_CON` varchar(1) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BAN`,`N_CTA_B`,`C_TRA_E`,`NUMEROD`,`NUM_EGR`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0005`
--

DROP TABLE IF EXISTS `CB0005`;
CREATE TABLE IF NOT EXISTS `CB0005` (
  `COD_BAN` varchar(10) NOT NULL DEFAULT '',
  `N_CTA_B` varchar(20) NOT NULL DEFAULT '',
  `C_TRA_E` varchar(2) DEFAULT NULL,
  `NUM_ANO` decimal(4,0) NOT NULL DEFAULT '0',
  `NUM_MES` decimal(2,0) NOT NULL DEFAULT '0',
  `NUM_DIA` decimal(2,0) DEFAULT NULL,
  `N_TRA_E` decimal(5,0) DEFAULT NULL,
  `V_TRA_E` decimal(14,2) NOT NULL DEFAULT '0.00',
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `FEC_SAL` timestamp NOT NULL DEFAULT current_timestamp,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BAN`,`N_CTA_B`,`FEC_SAL`,`V_TRA_E`,`NUM_ANO`,`NUM_MES`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0006`
--

DROP TABLE IF EXISTS `CB0006`;
CREATE TABLE IF NOT EXISTS `CB0006` (
  `COD_BAN` varchar(10) DEFAULT NULL,
  `N_CTA_B` varchar(20) DEFAULT NULL,
  `NUM_ANO` decimal(4,0) DEFAULT NULL,
  `NUM_MES` decimal(2,0) DEFAULT NULL,
  `SALDO_B` decimal(14,2) DEFAULT NULL,
  `F_U_ACT` date DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0007`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0010`
--

DROP TABLE IF EXISTS `CB0010`;
CREATE TABLE IF NOT EXISTS `CB0010` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `TIP_ING` decimal(2,0) DEFAULT NULL,
  `NUM_ING` varchar(10) NOT NULL DEFAULT '',
  `FEC_ING` date DEFAULT NULL,
  `DEP_ING` varchar(10) DEFAULT NULL,
  `NUM_PAP` varchar(10) DEFAULT NULL,
  `F_D_DEP` date DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_COB` varchar(10) DEFAULT NULL,
  `RAZ_ING` varchar(70) DEFAULT NULL,
  `TRA_ING` varchar(2) DEFAULT NULL,
  `TOT_COB` decimal(10,2) DEFAULT NULL,
  `TOT_R_F` decimal(10,2) DEFAULT NULL,
  `TOT_R_I` decimal(10,2) DEFAULT NULL,
  `TOT_CHE` decimal(10,2) DEFAULT NULL,
  `TOT_COM` decimal(10,2) DEFAULT NULL,
  `COD_BAN` varchar(20) DEFAULT NULL,
  `N_CTA_B` varchar(20) DEFAULT NULL,
  `NCH_ING` varchar(10) DEFAULT NULL,
  `F_V_ING` date DEFAULT NULL,
  `STA_ING` varchar(1) DEFAULT NULL,
  `ANU_ING` varchar(1) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `REC_COB` varchar(10) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_ING`),
  KEY `CB10_COD_CLI_FK` (`COD_CLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0011`
--

DROP TABLE IF EXISTS `CB0011`;
CREATE TABLE IF NOT EXISTS `CB0011` (
  `IDS_RET` bigint NOT NULL AUTO_INCREMENT,
  `IDS_REC` bigint NOT NULL,
  `TIP_REC` varchar(2) DEFAULT NULL,
  `FEC_RET` timestamp NULL DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `DIR_RET` text,
  `COD_VEN` varchar(10) DEFAULT NULL,
  `IDS_BAN` bigint DEFAULT NULL,
  `NUM_CHE` bigint DEFAULT NULL,
  `VAL_EST` decimal(12,2) DEFAULT NULL,
  `VAL_CHE` decimal(12,2) DEFAULT NULL,
  `OBS_DET` text,
  `OBS_GEN` text,
  `FEC_REC` timestamp NULL DEFAULT NULL,
  `FEC_ENT` timestamp NULL DEFAULT NULL,
  `EST_ENT` varchar(1) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_RET`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CB0020`
--

DROP TABLE IF EXISTS `CB0020`;
CREATE TABLE IF NOT EXISTS `CB0020` (
  `TIP_EGR` decimal(1,0) DEFAULT NULL,
  `COD_EGR` varchar(2) NOT NULL DEFAULT '',
  `NUM_EGR` varchar(10) NOT NULL DEFAULT '',
  `FEC_EGR` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `RAZ_EGR` varchar(60) DEFAULT NULL,
  `VAL_EGR` decimal(14,2) DEFAULT NULL,
  `COD_BAN` varchar(20) DEFAULT NULL,
  `N_CTA_B` varchar(20) DEFAULT NULL,
  `COD_BAN1` varchar(10) DEFAULT NULL,
  `N_CTA_B1` varchar(20) DEFAULT NULL,
  `NUM_CHE` varchar(10) DEFAULT NULL,
  `FEC_VEN` date DEFAULT NULL,
  `STA_EGR` varchar(1) DEFAULT NULL,
  `ANU_EGR` varchar(1) DEFAULT NULL,
  `CON_EGR` varchar(50) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `COD_PTO` varchar(3) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_EGR`,`COD_EGR`) USING BTREE,
  KEY `CB20_COD_PRO_FK` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC00H2`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0000`
--

DROP TABLE IF EXISTS `CC0000`;
CREATE TABLE IF NOT EXISTS `CC0000` (
  `NUM_DOC` varchar(10) DEFAULT NULL,
  `COD_PTO` varchar(3) DEFAULT NULL,
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `I_COB_I` varchar(1) DEFAULT NULL,
  `INTERES` decimal(5,2) DEFAULT NULL,
  `I_PAG_C` varchar(1) DEFAULT NULL,
  `P_COM_C` decimal(5,2) DEFAULT NULL,
  `D_ENT_D` decimal(4,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0002`
--

DROP TABLE IF EXISTS `CC0002`;
CREATE TABLE IF NOT EXISTS `CC0002` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) NOT NULL DEFAULT '',
  `TIP_NOF` varchar(2) NOT NULL DEFAULT '',
  `NUM_NOF` varchar(10) NOT NULL DEFAULT '',
  `COD_CLI` varchar(10) DEFAULT NULL,
  `C_TRA_E` varchar(2) NOT NULL DEFAULT '',
  `NUM_DOC` varchar(10) NOT NULL DEFAULT '',
  `F_SUS_D` date DEFAULT NULL,
  `F_VEN_D` date DEFAULT NULL,
  `DIA_PLZ` int DEFAULT '0',
  `VALOR_D` decimal(14,2) DEFAULT NULL,
  `F_PAG_D` date DEFAULT NULL,
  `VALOR_C` decimal(14,2) DEFAULT NULL,
  `VAL_DEV` decimal(14,2) DEFAULT NULL,
  `RET_FUE` decimal(12,2) DEFAULT NULL,
  `RET_IVA` decimal(12,2) DEFAULT NULL,
  `NUM_RET` varchar(10) DEFAULT NULL,
  `VAL_IVA` decimal(14,2) DEFAULT NULL,
  `CANCELA` varchar(1) DEFAULT NULL,
  `COD_COB` varchar(10) DEFAULT NULL,
  `IDS_BAN` bigint DEFAULT NULL,
  `C_BANCO` varchar(15) DEFAULT NULL,
  `REF_BAN` varchar(60) DEFAULT NULL,
  `U_FIS_D` varchar(10) DEFAULT NULL,
  `TRA_ORI` varchar(2) DEFAULT NULL,
  `DOC_ORI` varchar(10) DEFAULT NULL,
  `TRAMITE` varchar(1) DEFAULT NULL,
  `JURISTA` varchar(10) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `EST_DEP` varchar(1) DEFAULT NULL,
  `CHE_DEP` varchar(12) DEFAULT NULL,
  `FEC_DEP` date DEFAULT NULL,
  `IDS_CEC` bigint DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `OBS_DOC` varchar(50) DEFAULT NULL,
  `FEC_P_P` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_CAJ`,`TIP_NOF`,`NUM_NOF`,`C_TRA_E`,`NUM_DOC`),
  KEY `CC02_NUM_FAC_FK` (`COD_PTO`,`COD_CAJ`,`TIP_NOF`,`NUM_NOF`),
  KEY `CC02_COD_CLI_FK` (`COD_CLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 36864 kB; (`COD_CLI`) REFER `utimpor2010/MG0031';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0010`
--

DROP TABLE IF EXISTS `CC0010`;
CREATE TABLE IF NOT EXISTS `CC0010` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `INGRESO` varchar(10) NOT NULL DEFAULT '',
  `F_BOL_C` date DEFAULT NULL,
  `COD_COB` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `NUM_PAG` varchar(10) DEFAULT NULL,
  `NOTA_01` varchar(60) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`INGRESO`),
  KEY `FK_CC0010_1` (`COD_CLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0011`
--

DROP TABLE IF EXISTS `CC0011`;
CREATE TABLE IF NOT EXISTS `CC0011` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `NUM_ING` varchar(10) NOT NULL DEFAULT '',
  `TIP_NOF` varchar(2) NOT NULL DEFAULT '',
  `NUM_NOF` varchar(10) NOT NULL DEFAULT '',
  `C_TRA_E` varchar(3) NOT NULL,
  `NUM_DOC` varchar(10) NOT NULL DEFAULT '',
  `F_BOL_C` date DEFAULT NULL,
  `COD_COB` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `NUM_RET` varchar(10) DEFAULT NULL,
  `VALOR_C` decimal(10,2) DEFAULT NULL,
  `VALOR_I` decimal(10,2) DEFAULT NULL,
  `POR_R_F` decimal(5,2) DEFAULT NULL,
  `VAL_R_F` decimal(10,2) DEFAULT NULL,
  `POR_R_I` decimal(5,2) DEFAULT NULL,
  `VAL_R_I` decimal(10,2) DEFAULT NULL,
  `TRA_PAG` varchar(3) DEFAULT NULL,
  `NUM_CHE` varchar(8) DEFAULT NULL,
  `NOM_BCO` varchar(20) DEFAULT NULL,
  `VAL_CHE` decimal(10,2) DEFAULT '0.00',
  `IND_UPD` varchar(1) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `IDS_BAU` bigint DEFAULT NULL,
  `NUM_AUT` varchar(20) DEFAULT NULL,
  `IDS_BAN` bigint DEFAULT NULL,
  `IDS_PPG` bigint DEFAULT NULL,
  `NUM_TAR` varchar(20) DEFAULT NULL,
  `NUM_LOT` varchar(20) DEFAULT NULL,
  `NUM_VOU` varchar(20) DEFAULT NULL,
  `NUM_CTB` varchar(20) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_ING`,`TIP_NOF`,`NUM_NOF`,`C_TRA_E`,`NUM_DOC`) USING BTREE,
  KEY `NUM_NOF_FK` (`COD_PTO`,`TIP_NOF`,`NUM_NOF`,`C_TRA_E`,`NUM_DOC`),
  KEY `FK_CC0011_2` (`COD_CLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0050`
--

DROP TABLE IF EXISTS `CC0050`;
CREATE TABLE IF NOT EXISTS `CC0050` (
  `COD_POR` varchar(2) DEFAULT NULL,
  `POR_DES` decimal(10,2) DEFAULT NULL,
  `POR_HAS` decimal(10,2) DEFAULT NULL,
  `POR_PAG` decimal(5,2) DEFAULT NULL,
  `POR_DIS` decimal(5,2) DEFAULT NULL,
  `FEC_ACT` date DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0051`
--

DROP TABLE IF EXISTS `CC0051`;
CREATE TABLE IF NOT EXISTS `CC0051` (
  `COD_PUN` varchar(2) DEFAULT NULL,
  `VAL_PUN` decimal(14,2) DEFAULT NULL,
  `NUM_CUE` decimal(4,0) DEFAULT NULL,
  `FEC_ACT` date DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0052`
--

DROP TABLE IF EXISTS `CC0052`;
CREATE TABLE IF NOT EXISTS `CC0052` (
  `COD_BON` varchar(2) NOT NULL DEFAULT '',
  `POR_BON` decimal(5,2) DEFAULT NULL,
  `VAL_BON` decimal(14,2) DEFAULT NULL,
  `FEC_ACT` date DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BON`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0055`
--

DROP TABLE IF EXISTS `CC0055`;
CREATE TABLE IF NOT EXISTS `CC0055` (
  `COD_CAM` varchar(2) DEFAULT NULL,
  `NOM_CAM` varchar(15) DEFAULT NULL,
  `VAL_CAM` decimal(14,2) DEFAULT NULL,
  `COD_EJE` varchar(10) DEFAULT NULL,
  `FEC_ACT` date DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0060`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CC0062`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CD0001`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CO0050`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CO0052`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CO0055`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CO0061`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `COSCENTRO`
--

DROP TABLE IF EXISTS `COSCENTRO`;
CREATE TABLE IF NOT EXISTS `COSCENTRO` (
  `COD_CEN` varchar(20) NOT NULL,
  `NOM_CEN` varchar(100) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_CEN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `COSSUBCEN`
--

DROP TABLE IF EXISTS `COSSUBCEN`;
CREATE TABLE IF NOT EXISTS `COSSUBCEN` (
  `COD_SCEN` varchar(15) NOT NULL,
  `COD_CEN` varchar(10) NOT NULL,
  `NOM_SCEN` varchar(100) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_SCEN`),
  KEY `COSSUBCEN_ibfk_1` (`COD_CEN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `COSTOPRO`
--

DROP TABLE IF EXISTS `COSTOPRO`;
CREATE TABLE IF NOT EXISTS `COSTOPRO` (
  `COD_PRO` varchar(10) NOT NULL DEFAULT '',
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `NOM_ART` varchar(60) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `P_LISTA` decimal(14,2) DEFAULT NULL,
  `P_COSTO` decimal(14,2) DEFAULT NULL,
  `P_ANTER` decimal(14,2) DEFAULT NULL,
  `FEC_PRE` date DEFAULT NULL,
  `REG_ASO` decimal(1,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PRO`,`COD_ART`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CP00H1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CP0000`
--

DROP TABLE IF EXISTS `CP0000`;
CREATE TABLE IF NOT EXISTS `CP0000` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `I_COB_I` varchar(1) DEFAULT NULL,
  `INTERES` decimal(5,2) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CP0002`
--

DROP TABLE IF EXISTS `CP0002`;
CREATE TABLE IF NOT EXISTS `CP0002` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT '',
  `TIP_PED` varchar(2) NOT NULL DEFAULT '',
  `NUM_PED` varchar(10) NOT NULL DEFAULT '',
  `COD_PRO` varchar(10) DEFAULT NULL,
  `C_TRA_E` varchar(2) NOT NULL,
  `NUM_DOC` varchar(10) NOT NULL,
  `F_SUS_D` date NOT NULL,
  `F_VEN_D` date DEFAULT NULL,
  `VALOR_D` decimal(14,2) DEFAULT NULL,
  `F_PAG_D` date DEFAULT NULL,
  `VALOR_C` decimal(14,2) DEFAULT NULL,
  `VAL_DEV` decimal(14,2) DEFAULT NULL,
  `N_PER_R` varchar(30) DEFAULT NULL,
  `U_FIS_D` varchar(1) DEFAULT NULL,
  `CANCELA` varchar(1) DEFAULT NULL,
  `COD_BAN` varchar(10) DEFAULT NULL,
  `N_CTA_B` varchar(20) DEFAULT NULL,
  `NUM_EGR` varchar(10) DEFAULT NULL,
  `TRA_ORI` varchar(2) DEFAULT NULL,
  `DOC_ORI` varchar(10) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT '',
  `EQUIPO` varchar(15) DEFAULT '',
  `LIN_N01` varchar(100) DEFAULT NULL,
  `COD_CTA` varchar(10) DEFAULT '',
  `VALOR_F` decimal(14,2) DEFAULT NULL,
  `VALOR_I` decimal(14,2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_PED`,`NUM_PED`,`C_TRA_E`,`NUM_DOC`,`F_SUS_D`) USING BTREE,
  KEY `FK_CP0002_1` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CP0010`
--

DROP TABLE IF EXISTS `CP0010`;
CREATE TABLE IF NOT EXISTS `CP0010` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `NUM_PAG` varchar(10) DEFAULT NULL,
  `F_PAG_D` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `N_PER_R` varchar(30) DEFAULT NULL,
  `IND_PAG` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  KEY `FK_CP0010_1` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CP0011`
--

DROP TABLE IF EXISTS `CP0011`;
CREATE TABLE IF NOT EXISTS `CP0011` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `NUM_EGR` varchar(10) NOT NULL DEFAULT '',
  `COD_EGR` varchar(2) NOT NULL DEFAULT '',
  `NUM_PAG` varchar(10) DEFAULT NULL,
  `F_PAG_D` date DEFAULT NULL,
  `TIP_PED` varchar(2) NOT NULL DEFAULT '',
  `NUM_PED` varchar(10) NOT NULL DEFAULT '',
  `COD_PRO` varchar(10) DEFAULT NULL,
  `C_TRA_E` varchar(2) NOT NULL DEFAULT '',
  `NUM_DOC` varchar(10) NOT NULL DEFAULT '',
  `VALOR_C` decimal(14,2) DEFAULT NULL,
  `VALOR_I` decimal(14,2) DEFAULT NULL,
  `COD_BAN` varchar(10) DEFAULT NULL,
  `N_CTA_B` varchar(20) DEFAULT NULL,
  `N_DOC_B` varchar(10) DEFAULT NULL,
  `FEC_VTO` date DEFAULT NULL,
  `IND_PAG` varchar(1) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_EGR`,`TIP_PED`,`NUM_PED`,`C_TRA_E`,`NUM_DOC`,`COD_EGR`) USING BTREE,
  KEY `FK_CP0011_2` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CPE_FUN`
--

DROP TABLE IF EXISTS `CPE_FUN`;
CREATE TABLE IF NOT EXISTS `CPE_FUN` (
  `COD_CPE` varchar(3) NOT NULL,
  `NOM_CPE` varchar(50) DEFAULT NULL,
  `TIP_CPE` varchar(1) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_CPE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CUOTAMAR`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CUOTAVEN`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EJESEMA`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ERRORS`
--

DROP TABLE IF EXISTS `ERRORS`;
CREATE TABLE IF NOT EXISTS `ERRORS` (
  `COD_MEN` varchar(3) DEFAULT NULL,
  `NOM_MEN` varchar(60) DEFAULT NULL,
  `HEL_MEN` varchar(3) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `FORMAPAGO`
--

DROP TABLE IF EXISTS `FORMAPAGO`;
CREATE TABLE IF NOT EXISTS `FORMAPAGO` (
  `IdForma` bigint NOT NULL AUTO_INCREMENT,
  `FormaPago` varchar(100) DEFAULT NULL,
  `Codigo` varchar(2) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaFin` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IdForma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0001`
--

DROP TABLE IF EXISTS `IG0001`;
CREATE TABLE IF NOT EXISTS `IG0001` (
  `COD_LIN` varchar(3) NOT NULL DEFAULT '',
  `NOM_LIN` varchar(60) NOT NULL,
  `FEC_LIN` date NOT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_LIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0002`
--

DROP TABLE IF EXISTS `IG0002`;
CREATE TABLE IF NOT EXISTS `IG0002` (
  `COD_TIP` varchar(3) NOT NULL DEFAULT '',
  `NOM_TIP` varchar(60) DEFAULT NULL,
  `FEC_TIP` date DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_TIP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0003`
--

DROP TABLE IF EXISTS `IG0003`;
CREATE TABLE IF NOT EXISTS `IG0003` (
  `COD_MAR` varchar(3) NOT NULL DEFAULT '',
  `NOM_MAR` varchar(30) DEFAULT NULL,
  `FEC_MAR` date DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `REG_SEL` varchar(1) DEFAULT '0',
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_MAR`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0004`
--

DROP TABLE IF EXISTS `IG0004`;
CREATE TABLE IF NOT EXISTS `IG0004` (
  `COD_MOD` varchar(40) NOT NULL,
  `ART_N01` varchar(20) DEFAULT NULL,
  `ART_N02` varchar(20) DEFAULT NULL,
  `ART_N03` varchar(20) DEFAULT NULL,
  `ART_N04` varchar(20) DEFAULT NULL,
  `OBSERVA` varchar(40) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_MOD`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0006`
--

DROP TABLE IF EXISTS `IG0006`;
CREATE TABLE IF NOT EXISTS `IG0006` (
  `COD_GRU` varchar(2) NOT NULL DEFAULT '',
  `NOM_GRU` varchar(30) DEFAULT NULL,
  `F_U_VAR` date DEFAULT NULL,
  `PRE_COS` decimal(8,2) DEFAULT NULL,
  `POR_N01` decimal(8,2) DEFAULT NULL,
  `POR_N02` decimal(8,2) DEFAULT NULL,
  `POR_N03` decimal(8,2) DEFAULT NULL,
  `POR_N04` decimal(8,2) DEFAULT NULL,
  `TIP_VAR` varchar(1) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_GRU`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0007`
--

DROP TABLE IF EXISTS `IG0007`;
CREATE TABLE IF NOT EXISTS `IG0007` (
  `IDS` bigint NOT NULL AUTO_INCREMENT,
  `TIP_CON` varchar(2) DEFAULT NULL,
  `ANO_MES` varchar(7) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `NOM_MAR` varchar(30) DEFAULT NULL,
  `COST_ANT` decimal(12,4) DEFAULT NULL,
  `COST_ACT` decimal(12,4) DEFAULT NULL,
  `COST_DIF` decimal(12,4) DEFAULT NULL,
  `COSTO_T` decimal(12,4) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0020`
--

DROP TABLE IF EXISTS `IG0020`;
CREATE TABLE IF NOT EXISTS `IG0020` (
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `F_A_INV` date DEFAULT NULL,
  `DES_NAT` varchar(60) DEFAULT NULL,
  `DES_COM` varchar(60) DEFAULT NULL,
  `NUM_REF` varchar(10) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `COD_GRU` varchar(3) DEFAULT NULL,
  `AUX_N01` varchar(10) DEFAULT NULL,
  `AUX_N02` varchar(10) DEFAULT NULL,
  `AUX_N03` varchar(10) DEFAULT NULL,
  `AUX_N04` varchar(9) DEFAULT NULL,
  `UBI_FIS` varchar(20) DEFAULT NULL,
  `COD_PAI` varchar(2) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `COD_P_A` varchar(17) DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `EXI_MAX` decimal(8,2) DEFAULT NULL,
  `EXI_MIN` decimal(8,2) DEFAULT NULL,
  `EXI_COM` decimal(8,2) DEFAULT NULL,
  `EXI_TOT` decimal(8,2) DEFAULT NULL,
  `EXI_BOD` decimal(8,2) DEFAULT NULL,
  `DIF_FIS` decimal(8,2) DEFAULT NULL,
  `DIF_BOD` decimal(8,2) DEFAULT NULL,
  `I_I_UNI` decimal(8,2) DEFAULT NULL,
  `I_I_COS` decimal(12,4) DEFAULT NULL,
  `I_F_UNI` decimal(8,2) DEFAULT NULL,
  `I_B_UNI` decimal(8,2) DEFAULT NULL,
  `I_F_COS` decimal(12,4) DEFAULT NULL,
  `P_LISTA` decimal(12,4) DEFAULT NULL,
  `P_PROME` decimal(12,4) DEFAULT NULL,
  `P_COSTO` decimal(12,4) DEFAULT NULL,
  `F_LIS_N` date DEFAULT NULL,
  `F_COS_N` date DEFAULT NULL,
  `P_L_ANT` decimal(12,4) DEFAULT NULL,
  `P_C_ANT` decimal(12,4) DEFAULT NULL,
  `P_P_ANT` decimal(12,4) DEFAULT NULL,
  `F_LIS_V` date DEFAULT NULL,
  `F_COS_V` date DEFAULT NULL,
  `P_VENTA` decimal(12,4) DEFAULT NULL COMMENT 'Solo Oficomput',
  `PAUX_01` decimal(12,4) DEFAULT NULL,
  `PAUX_02` decimal(12,4) DEFAULT NULL,
  `PAUX_03` decimal(12,4) DEFAULT NULL,
  `F_VEN_N` date DEFAULT NULL,
  `P_V_ANT` decimal(12,4) DEFAULT NULL,
  `RAUX_01` decimal(12,4) DEFAULT NULL,
  `RAUX_02` decimal(12,4) DEFAULT NULL,
  `RAUX_03` decimal(12,4) DEFAULT NULL,
  `F_VEN_V` date DEFAULT NULL,
  `T_UI_AC` decimal(8,2) DEFAULT NULL,
  `DEM_ACT` decimal(8,2) DEFAULT NULL,
  `T_UE_AC` decimal(8,2) DEFAULT NULL,
  `T_UR_AC` decimal(8,2) DEFAULT NULL,
  `T_URCAC` decimal(8,2) DEFAULT NULL,
  `T_RC_AC` decimal(8,2) DEFAULT NULL,
  `NUM_PED` varchar(10) DEFAULT NULL,
  `T_REPOS` decimal(4,0) DEFAULT NULL,
  `EXI_M01` decimal(8,2) DEFAULT '0.00',
  `P_C_M01` decimal(10,2) DEFAULT NULL,
  `EXI_M02` decimal(8,2) DEFAULT '0.00',
  `P_C_M02` decimal(10,2) DEFAULT NULL,
  `EXI_M03` decimal(8,2) DEFAULT '0.00',
  `P_C_M03` decimal(10,2) DEFAULT NULL,
  `EXI_M04` decimal(8,2) DEFAULT '0.00',
  `P_C_M04` decimal(10,2) DEFAULT NULL,
  `EXI_M05` decimal(8,2) DEFAULT '0.00',
  `P_C_M05` decimal(10,2) DEFAULT NULL,
  `EXI_M06` decimal(8,2) DEFAULT '0.00',
  `P_C_M06` decimal(10,2) DEFAULT NULL,
  `EXI_M07` decimal(8,2) DEFAULT '0.00',
  `P_C_M07` decimal(10,2) DEFAULT NULL,
  `EXI_M08` decimal(8,2) DEFAULT '0.00',
  `P_C_M08` decimal(10,2) DEFAULT NULL,
  `EXI_M09` decimal(8,2) DEFAULT '0.00',
  `P_C_M09` decimal(10,2) DEFAULT NULL,
  `EXI_M10` decimal(8,2) DEFAULT '0.00',
  `P_C_M10` decimal(10,2) DEFAULT NULL,
  `EXI_M11` decimal(8,2) DEFAULT '0.00',
  `P_C_M11` decimal(10,2) DEFAULT NULL,
  `EXI_M12` decimal(8,2) DEFAULT '0.00',
  `P_C_M12` decimal(10,2) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `CANT_01` decimal(8,2) DEFAULT NULL,
  `CANT_02` decimal(8,2) DEFAULT NULL,
  `CANT_03` decimal(8,2) DEFAULT NULL,
  `CANT_04` decimal(8,2) DEFAULT NULL,
  `COD_MED` varchar(2) DEFAULT NULL,
  `FAC_CON` decimal(8,2) DEFAULT NULL,
  `FAC_BUL` decimal(8,2) DEFAULT NULL,
  `POR_N01` decimal(5,2) DEFAULT NULL,
  `POR_N02` decimal(5,2) DEFAULT NULL,
  `POR_N03` decimal(5,2) DEFAULT NULL,
  `POR_N04` decimal(5,2) DEFAULT NULL,
  `LIS_DIS` varchar(1) DEFAULT '0',
  `LIS_CAN` int DEFAULT '0',
  `P_VDANT` decimal(14,4) DEFAULT '0.0000',
  `P_VDIS1` decimal(14,4) DEFAULT '0.0000',
  `P_VDIS2` decimal(14,4) DEFAULT '0.0000',
  `PV_PROMO` decimal(14,4) DEFAULT '0.0000',
  `P_VDREAL` decimal(14,4) DEFAULT '0.0000',
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `F_C_ART` date DEFAULT NULL,
  `F_E_ART` date DEFAULT NULL,
  `I_M_DES` decimal(1,0) DEFAULT NULL,
  `IMG_ART` mediumblob,
  `P_IMPOR` decimal(12,4) DEFAULT NULL,
  `CAR_ART` varchar(400) DEFAULT NULL,
  `COS_M_P` decimal(14,4) DEFAULT NULL,
  `COS_M_O` decimal(14,4) DEFAULT NULL,
  `COS_IND` decimal(14,4) DEFAULT NULL,
  `CTI_M_P` varchar(10) DEFAULT NULL,
  `CTI_M_O` varchar(10) DEFAULT NULL,
  `CTI_IND` varchar(10) DEFAULT NULL,
  `CTC_M_P` varchar(10) DEFAULT NULL,
  `CTC_M_O` varchar(10) DEFAULT NULL,
  `CTC_IND` varchar(10) DEFAULT NULL,
  `ART_WEB` varchar(1) DEFAULT NULL,
  `PRO_VTA` decimal(8,2) DEFAULT NULL COMMENT 'Promedio de Venta',
  `PRO_IMP` varchar(1) DEFAULT NULL,
  `ESTADO_RES` bit(1) DEFAULT NULL,
  `COD_VEN_RES` varchar(10) DEFAULT NULL,
  `FEC_LIM_RES` date DEFAULT NULL,
  `LIS_PROM` text,
  `IDS_CAT` bigint DEFAULT NULL,
  `NUM_CRE` int DEFAULT NULL,
  `HOR_SEM` int DEFAULT NULL,
  `TIP_PRO` varchar(1) DEFAULT NULL,
  `COD_SGRU` varchar(3) DEFAULT NULL,
  `NOM_GRU` varchar(100) DEFAULT NULL,
  `NOM_SGRU` varchar(100) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_ART`),
  KEY `COD_LIN_FK` (`COD_LIN`),
  KEY `COD_TIP_FK` (`COD_TIP`),
  KEY `COD_MAR_FK` (`COD_MAR`),
  KEY `COD_PRO_FK` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0020C`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0020F`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0021`
--

DROP TABLE IF EXISTS `IG0021`;
CREATE TABLE IF NOT EXISTS `IG0021` (
  `COD_BOD` varchar(2) NOT NULL DEFAULT '',
  `NOM_BOD` varchar(30) DEFAULT NULL,
  `DIR_BOD` varchar(40) DEFAULT NULL,
  `COD_PAI` varchar(2) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `TEL_N01` varchar(9) DEFAULT NULL,
  `TEL_N02` varchar(9) DEFAULT NULL,
  `NUM_FAX` varchar(20) DEFAULT NULL,
  `CORRE_E` varchar(30) DEFAULT NULL,
  `COD_RES` varchar(10) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `NUM_ING` varchar(10) DEFAULT '',
  `NUM_EGR` varchar(10) DEFAULT '',
  `COD_PTO` varchar(3) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BOD`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0022`
--

DROP TABLE IF EXISTS `IG0022`;
CREATE TABLE IF NOT EXISTS `IG0022` (
  `COD_BOD` varchar(2) NOT NULL DEFAULT '',
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `I_I_UNI` decimal(10,0) DEFAULT NULL,
  `I_I_COS` decimal(10,2) DEFAULT NULL,
  `T_UI_AC` decimal(10,2) DEFAULT NULL,
  `T_IC_AC` decimal(14,2) DEFAULT NULL,
  `T_UE_AC` decimal(10,2) DEFAULT NULL,
  `T_EC_AC` decimal(14,2) DEFAULT NULL,
  `T_UR_AC` decimal(10,2) DEFAULT NULL,
  `T_RC_AC` decimal(14,2) DEFAULT NULL,
  `UBI_FIS` varchar(20) DEFAULT NULL,
  `S_I_FIS` varchar(1) DEFAULT NULL,
  `F_I_FIS` date DEFAULT NULL,
  `EXI_COM` decimal(10,2) DEFAULT NULL,
  `EXI_TOT` decimal(10,2) DEFAULT NULL,
  `P_COSTO` decimal(14,4) DEFAULT NULL,
  `EXI_M01` decimal(8,0) DEFAULT NULL,
  `EXI_M02` decimal(8,0) DEFAULT NULL,
  `EXI_M03` decimal(8,0) DEFAULT NULL,
  `EXI_M04` decimal(8,0) DEFAULT NULL,
  `EXI_M05` decimal(8,0) DEFAULT NULL,
  `EXI_M06` decimal(8,0) DEFAULT NULL,
  `EXI_M07` decimal(8,0) DEFAULT NULL,
  `EXI_M08` decimal(8,0) DEFAULT NULL,
  `EXI_M09` decimal(8,0) DEFAULT NULL,
  `EXI_M10` decimal(8,0) DEFAULT NULL,
  `EXI_M11` decimal(8,0) DEFAULT NULL,
  `EXI_M12` decimal(8,0) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `USU_DES` varchar(30) DEFAULT NULL,
  `P_LISTA` decimal(14,4) DEFAULT NULL,
  `DET_COM` varchar(500) DEFAULT NULL,
  `EXI_TEM` decimal(10,2) DEFAULT NULL,
  `EST_ACT` varchar(1) DEFAULT NULL,
  `EXI_ANT` decimal(10,2) DEFAULT NULL,
  `EXI_HIS` decimal(10,2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BOD`,`COD_ART`),
  KEY `COD_ART_FK` (`COD_ART`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0025`
--

DROP TABLE IF EXISTS `IG0025`;
CREATE TABLE IF NOT EXISTS `IG0025` (
  `COD_BOD` varchar(2) NOT NULL DEFAULT '',
  `NUM_ING` varchar(10) NOT NULL DEFAULT '',
  `FEC_ING` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `NOM_PRO` varchar(70) DEFAULT NULL,
  `T_I_ING` decimal(5,0) DEFAULT NULL,
  `T_P_ING` decimal(8,0) DEFAULT NULL,
  `TOT_COS` decimal(14,2) DEFAULT NULL,
  `LIN_N01` varchar(120) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `TIP_ING` varchar(2) NOT NULL DEFAULT '',
  `TIP_B_T` varchar(2) DEFAULT '',
  `NUM_B_T` varchar(10) DEFAULT '',
  `COD_B_T` varchar(2) DEFAULT '',
  `LIN_N02` varchar(150) DEFAULT NULL,
  `LIN_N03` varchar(150) DEFAULT NULL,
  `LIN_N04` varchar(150) DEFAULT NULL,
  `LIN_N05` varchar(150) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BOD`,`NUM_ING`,`TIP_ING`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0026`
--

DROP TABLE IF EXISTS `IG0026`;
CREATE TABLE IF NOT EXISTS `IG0026` (
  `COD_BOD` varchar(2) NOT NULL DEFAULT '',
  `NUM_ING` varchar(10) NOT NULL DEFAULT '',
  `FEC_ING` date DEFAULT NULL,
  `COD_PRO` varchar(10) NOT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `CAN_ANT` decimal(10,2) DEFAULT NULL,
  `CAN_PED` decimal(10,2) DEFAULT NULL,
  `CAN_DEV` decimal(10,2) DEFAULT NULL,
  `C_ANTER` decimal(14,2) DEFAULT NULL,
  `P_COSTO` decimal(14,2) DEFAULT NULL,
  `P_LISTA` decimal(14,2) DEFAULT NULL,
  `T_COSTO` decimal(14,2) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `TIP_ING` varchar(2) NOT NULL DEFAULT '',
  `TIP_B_T` varchar(2) DEFAULT '',
  `NUM_B_T` varchar(10) DEFAULT '',
  `COD_B_T` varchar(2) DEFAULT '',
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BOD`,`NUM_ING`,`TIP_ING`,`COD_ART`,`COD_PRO`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0027`
--

DROP TABLE IF EXISTS `IG0027`;
CREATE TABLE IF NOT EXISTS `IG0027` (
  `COD_BOD` varchar(2) NOT NULL DEFAULT '',
  `NUM_EGR` varchar(10) NOT NULL,
  `NUM_B_T` varchar(10) DEFAULT NULL,
  `FEC_EGR` date DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `NOM_CLI` varchar(70) DEFAULT NULL,
  `T_I_EGR` decimal(5,0) DEFAULT NULL,
  `T_P_EGR` decimal(8,0) DEFAULT NULL,
  `TOT_COS` decimal(12,2) DEFAULT NULL,
  `LIN_N01` varchar(150) DEFAULT NULL,
  `LIN_N02` varchar(150) DEFAULT NULL,
  `LIN_N03` varchar(150) DEFAULT NULL,
  `LIN_N04` varchar(150) DEFAULT NULL,
  `LIN_N05` varchar(150) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `TIP_EGR` varchar(2) NOT NULL DEFAULT '',
  `TIP_B_T` varchar(2) DEFAULT '',
  `COD_B_T` varchar(2) DEFAULT '',
  `GUI_REM` varchar(10) DEFAULT NULL,
  `NUM_ORT` varchar(10) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BOD`,`NUM_EGR`,`TIP_EGR`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0028`
--

DROP TABLE IF EXISTS `IG0028`;
CREATE TABLE IF NOT EXISTS `IG0028` (
  `COD_BOD` varchar(2) NOT NULL DEFAULT '',
  `NUM_EGR` varchar(10) NOT NULL,
  `FEC_EGR` date DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `CAN_PED` decimal(10,2) DEFAULT NULL,
  `CAN_DEV` decimal(10,2) DEFAULT NULL,
  `CAN_FAC` decimal(10,2) DEFAULT NULL,
  `P_COSTO` decimal(14,2) DEFAULT NULL,
  `P_LISTA` decimal(14,2) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `TIP_EGR` varchar(2) NOT NULL DEFAULT '',
  `COD_B_T` varchar(2) DEFAULT NULL,
  `TIP_B_T` varchar(2) DEFAULT NULL,
  `NUM_B_T` varchar(10) DEFAULT NULL,
  `NUM_ORT` varchar(10) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_BOD`,`NUM_EGR`,`TIP_EGR`,`COD_ART`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0040`
--

DROP TABLE IF EXISTS `IG0040`;
CREATE TABLE IF NOT EXISTS `IG0040` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `NUM_COT` varchar(10) NOT NULL DEFAULT '',
  `FEC_COT` date DEFAULT NULL,
  `TIP_NOF` varchar(2) DEFAULT NULL,
  `NUM_NOF` varchar(10) DEFAULT NULL,
  `FEC_VTA` date DEFAULT NULL,
  `ATIENDE` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `NOM_CLI` varchar(100) DEFAULT NULL,
  `DIR_CLI` varchar(100) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `PRO_VTA` decimal(12,2) DEFAULT '0.00',
  `VAL_BRU` decimal(14,2) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(14,2) DEFAULT NULL,
  `POR_RET` decimal(5,2) DEFAULT NULL,
  `VAL_RET` decimal(14,2) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,2) DEFAULT NULL,
  `VAL_FLE` decimal(14,2) DEFAULT NULL,
  `VAR_CON` varchar(20) DEFAULT NULL,
  `VAL_VAR` decimal(14,2) DEFAULT NULL,
  `BAS_IVA` decimal(12,2) DEFAULT NULL,
  `BAS_IV0` decimal(12,2) DEFAULT NULL,
  `VAL_NET` decimal(14,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `T_I_COT` decimal(5,0) DEFAULT NULL,
  `T_P_COT` decimal(8,2) DEFAULT NULL,
  `CON_PAG` varchar(60) DEFAULT NULL,
  `DOFERTA` varchar(60) DEFAULT NULL,
  `REL_NOF` varchar(1) DEFAULT NULL,
  `LIN_N01` varchar(500) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `POR_REN` decimal(5,2) DEFAULT NULL,
  `COM_TER` decimal(12,2) DEFAULT NULL,
  `T_ENTRE` varchar(20) DEFAULT NULL,
  `TEL_FAX` varchar(30) DEFAULT NULL,
  `NOM_CTO` varchar(100) DEFAULT NULL,
  `COSTO_MODI` tinyint(1) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_COT`),
  KEY `COD_CLI_FK` (`COD_CLI`),
  KEY `ATIENDE_FK` (`ATIENDE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0040C`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0040P`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0041`
--

DROP TABLE IF EXISTS `IG0041`;
CREATE TABLE IF NOT EXISTS `IG0041` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `NUM_COT` varchar(10) NOT NULL DEFAULT '',
  `FEC_COT` date DEFAULT NULL,
  `TIP_NOF` varchar(2) DEFAULT NULL,
  `NUM_NOF` varchar(10) DEFAULT NULL,
  `FEC_VTA` date DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `COD_EMP` varchar(10) DEFAULT NULL,
  `IND_DES` varchar(1) DEFAULT NULL,
  `NOM_ART` varchar(60) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `CAN_PED` decimal(8,2) DEFAULT NULL,
  `CAN_DES` decimal(8,2) DEFAULT NULL,
  `IND_PRE` varchar(1) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `P_PROME` decimal(14,4) DEFAULT NULL,
  `P_COSTO` decimal(14,4) DEFAULT NULL,
  `T_COSTO` decimal(14,4) DEFAULT NULL,
  `P_VENTA` decimal(14,4) DEFAULT NULL,
  `T_VENTA` decimal(14,4) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(14,4) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `P_REAL` decimal(14,4) DEFAULT NULL,
  `SECUENCIA` int UNSIGNED DEFAULT NULL,
  `EXI_TOT` decimal(8,2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_COT`,`COD_ART`) USING BTREE,
  KEY `IG41_COD_CLI_FK` (`COD_CLI`),
  KEY `IG41_COD_ART_FK` (`COD_ART`),
  KEY `IG41_ATIENDE_FK` (`COD_EMP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0041C`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0041P`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0042`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0045`
--

DROP TABLE IF EXISTS `IG0045`;
CREATE TABLE IF NOT EXISTS `IG0045` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `TIP_GUI` varchar(2) NOT NULL,
  `NUM_GUI` varchar(10) NOT NULL DEFAULT '',
  `FEC_GUI` date DEFAULT NULL,
  `SER_NOF` varchar(7) DEFAULT NULL,
  `TIP_NOF` varchar(2) DEFAULT NULL,
  `NUM_NOF` varchar(10) DEFAULT NULL,
  `FEC_VTA` date DEFAULT NULL,
  `FEC_I_T` date DEFAULT NULL,
  `FEC_T_T` date DEFAULT NULL,
  `MOT_TRA` decimal(1,0) DEFAULT NULL,
  `PUN_PAR` varchar(90) DEFAULT NULL,
  `PUN_LLE` varchar(150) NOT NULL,
  `FEC_PAR` date DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `NOM_CLI` varchar(40) DEFAULT NULL,
  `CED_RUC` varchar(15) DEFAULT NULL,
  `COD_TRA` varchar(10) DEFAULT NULL,
  `NOM_TRA` varchar(40) DEFAULT NULL,
  `C_R_TRA` varchar(15) DEFAULT NULL,
  `LIN_N01` varchar(120) DEFAULT NULL,
  `LIN_N02` varchar(40) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `PLK_TRA` varchar(10) DEFAULT NULL,
  `ATIENDE` varchar(10) DEFAULT NULL,
  `ENV_DOC` bigint DEFAULT '0',
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_GUI`,`COD_PTO`,`TIP_GUI`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0046`
--

DROP TABLE IF EXISTS `IG0046`;
CREATE TABLE IF NOT EXISTS `IG0046` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT '',
  `TIP_GUI` varchar(2) NOT NULL,
  `NUM_GUI` varchar(10) NOT NULL DEFAULT '',
  `FEC_GUI` date DEFAULT NULL,
  `TIP_NOF` varchar(2) DEFAULT NULL,
  `NUM_NOF` varchar(10) DEFAULT NULL,
  `FEC_VTA` date DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `IND_DES` varchar(1) DEFAULT NULL,
  `NOM_ART` varchar(60) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `CAN_DES` decimal(8,0) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_GUI`,`COD_PTO`,`COD_ART`,`TIP_GUI`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0050`
--

DROP TABLE IF EXISTS `IG0050`;
CREATE TABLE IF NOT EXISTS `IG0050` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT '',
  `TIP_PED` varchar(2) NOT NULL DEFAULT '',
  `NUM_PED` varchar(10) NOT NULL DEFAULT '',
  `FEC_PED` date DEFAULT NULL,
  `ORD_PED` varchar(10) DEFAULT NULL,
  `F_O_PED` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `NOM_PRO` varchar(100) DEFAULT NULL,
  `DIR_PRO` varchar(40) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `I_C_PAR` varchar(1) DEFAULT NULL,
  `N_S_PRO` varchar(7) DEFAULT NULL,
  `N_F_PRO` varchar(9) DEFAULT NULL,
  `F_F_PRO` date DEFAULT NULL,
  `COD_SUS` varchar(2) DEFAULT NULL,
  `COD_I_P` varchar(2) DEFAULT NULL,
  `FEC_CAD` varchar(10) DEFAULT NULL,
  `T_I_PED` decimal(5,0) DEFAULT NULL,
  `T_P_PED` decimal(8,0) DEFAULT NULL,
  `VAL_BRU` decimal(12,2) DEFAULT NULL,
  `POR_DE0` decimal(5,2) DEFAULT NULL,
  `VAL_DE0` decimal(12,2) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(12,2) DEFAULT NULL,
  `BAS_IV0` decimal(12,2) DEFAULT NULL,
  `BAS_IVA` decimal(12,2) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(12,2) DEFAULT NULL,
  `VAL_FLE` decimal(12,2) DEFAULT NULL,
  `VAR_CON` varchar(20) DEFAULT NULL,
  `VAL_VAR` decimal(12,2) DEFAULT NULL,
  `VAL_NET` decimal(12,2) DEFAULT NULL,
  `VAL_PAG` decimal(12,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `CON_PAG` varchar(15) DEFAULT NULL,
  `DOFERTA` varchar(15) DEFAULT NULL,
  `LIN_N01` varchar(40) DEFAULT NULL,
  `TIP_RET` varchar(3) DEFAULT NULL,
  `DOC_RET` varchar(2) DEFAULT '',
  `NUM_RET` varchar(10) DEFAULT NULL,
  `POR_RET` decimal(6,2) DEFAULT NULL,
  `VAL_RET` decimal(12,2) DEFAULT NULL,
  `RET_ASU` varchar(1) DEFAULT '0',
  `P_R_IVA` decimal(6,2) DEFAULT NULL,
  `V_R_IVA` decimal(12,2) DEFAULT NULL,
  `NUM_S_R` varchar(7) DEFAULT NULL,
  `NUM_A_R` varchar(10) DEFAULT NULL,
  `FEC_RET` date DEFAULT NULL,
  `BAS_ICE` decimal(12,2) DEFAULT NULL,
  `POR_ICE` decimal(7,2) DEFAULT NULL,
  `VAL_ICE` decimal(12,2) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `N_A_PRO` varchar(50) DEFAULT NULL,
  `DET_RET` varchar(60) DEFAULT NULL,
  `FEC_VEN` date DEFAULT NULL,
  `ENV_DOC` bigint DEFAULT '0',
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_PED`,`NUM_PED`),
  KEY `IG50_COD_PRO_FK` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0051`
--

DROP TABLE IF EXISTS `IG0051`;
CREATE TABLE IF NOT EXISTS `IG0051` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT '',
  `TIP_PED` varchar(2) NOT NULL DEFAULT '',
  `NUM_PED` varchar(10) NOT NULL DEFAULT '',
  `FEC_PED` date DEFAULT NULL,
  `N_P_REL` varchar(8) DEFAULT NULL,
  `F_P_REL` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `IND_DES` varchar(1) DEFAULT NULL,
  `NOM_ART` varchar(60) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `EXI_ANT` decimal(8,2) DEFAULT NULL,
  `EXI_ACT` decimal(8,2) DEFAULT NULL,
  `CAN_PED` decimal(8,2) DEFAULT NULL,
  `CAN_DEV` decimal(8,2) DEFAULT NULL,
  `IND_PRE` varchar(1) DEFAULT NULL,
  `PRE_UNI` decimal(14,4) DEFAULT NULL,
  `P_PROME` decimal(14,4) DEFAULT NULL,
  `P_COSTO` decimal(14,4) DEFAULT NULL,
  `T_COSTO` decimal(14,4) DEFAULT NULL,
  `P_VENTA` decimal(14,4) DEFAULT NULL,
  `T_VENTA` decimal(14,4) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(14,4) DEFAULT NULL,
  `P_P_ANT` decimal(14,4) DEFAULT NULL,
  `COS_ANT` decimal(14,4) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,4) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `FAC_COS` decimal(14,2) DEFAULT NULL,
  `P_C_ANT` decimal(14,4) DEFAULT NULL,
  `PRO_VTA` decimal(8,2) DEFAULT NULL COMMENT 'Promedio de Venta',
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_PED`,`NUM_PED`,`COD_ART`),
  KEY `FK_IG0051_2` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0052`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0054`
--

DROP TABLE IF EXISTS `IG0054`;
CREATE TABLE IF NOT EXISTS `IG0054` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT '',
  `TIP_PED` varchar(2) NOT NULL DEFAULT '',
  `NUM_PED` varchar(10) NOT NULL DEFAULT '',
  `FEC_PED` date DEFAULT NULL,
  `ORD_PED` varchar(8) DEFAULT NULL,
  `F_O_PED` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `NOM_PRO` varchar(40) DEFAULT NULL,
  `DIR_PRO` varchar(80) DEFAULT NULL,
  `I_C_PAR` varchar(1) DEFAULT NULL,
  `N_S_PRO` varchar(7) DEFAULT NULL,
  `N_F_PRO` varchar(9) DEFAULT NULL,
  `F_F_PRO` date DEFAULT NULL,
  `COD_SUS` varchar(2) DEFAULT NULL,
  `COD_I_P` varchar(2) DEFAULT NULL,
  `FEC_CAD` varchar(10) DEFAULT NULL,
  `BAS_NOG` decimal(12,2) DEFAULT NULL,
  `BAS_IV0` decimal(12,2) DEFAULT NULL,
  `BAS_IVA` decimal(12,2) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(12,2) DEFAULT NULL,
  `VAL_NET` decimal(12,2) DEFAULT NULL,
  `BAS_ICE` decimal(12,2) DEFAULT NULL,
  `POR_ICE` decimal(7,2) DEFAULT NULL,
  `VAL_ICE` decimal(12,2) DEFAULT NULL,
  `VAL_PAG` decimal(12,2) DEFAULT NULL,
  `TIP_RET` varchar(3) DEFAULT NULL,
  `BAS_RET` decimal(12,2) DEFAULT NULL,
  `POR_RET` decimal(6,2) DEFAULT NULL,
  `VAL_RET` decimal(12,2) DEFAULT NULL,
  `TIP_RE1` varchar(3) DEFAULT NULL,
  `BAS_RE1` decimal(12,2) DEFAULT NULL,
  `POR_RE1` decimal(6,2) DEFAULT NULL,
  `RET_ASU` varchar(1) DEFAULT '0',
  `VAL_RE1` decimal(12,2) DEFAULT NULL,
  `TIP_REI` varchar(3) DEFAULT NULL,
  `P_R_IVA` decimal(6,2) DEFAULT NULL,
  `V_R_IVA` decimal(12,2) DEFAULT NULL,
  `DOC_RET` varchar(2) DEFAULT '',
  `NUM_RET` varchar(10) DEFAULT NULL,
  `NUM_S_R` varchar(7) DEFAULT NULL,
  `NUM_A_R` varchar(10) DEFAULT NULL,
  `FEC_RET` date DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `CON_PAG` varchar(15) DEFAULT NULL,
  `DOFERTA` varchar(15) DEFAULT NULL,
  `LIN_N01` varchar(500) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `VAL_ANT` decimal(12,2) DEFAULT NULL,
  `N_A_PRO` varchar(50) DEFAULT NULL,
  `DET_RET` varchar(60) DEFAULT NULL,
  `FEC_VEN` date DEFAULT NULL,
  `ENV_DOC` bigint DEFAULT '0',
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_PED`,`NUM_PED`),
  KEY `IG54_COD_PRO_FK` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0055`
--

DROP TABLE IF EXISTS `IG0055`;
CREATE TABLE IF NOT EXISTS `IG0055` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `TIP_DEV` varchar(2) NOT NULL DEFAULT '',
  `NUM_DEV` varchar(10) NOT NULL DEFAULT '',
  `FEC_DEV` date DEFAULT NULL,
  `COD_MOT` varchar(2) DEFAULT NULL,
  `TIP_PED` varchar(2) DEFAULT NULL,
  `NUM_PED` varchar(10) DEFAULT NULL,
  `FEC_PED` date DEFAULT NULL,
  `C_TRA_E` varchar(2) DEFAULT NULL,
  `NUM_DOC` varchar(10) DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `VAL_BRU` decimal(14,2) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(14,2) DEFAULT NULL,
  `BAS_IVA` decimal(14,2) DEFAULT NULL,
  `BAS_IV0` decimal(14,2) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,2) DEFAULT NULL,
  `VAL_NET` decimal(14,2) DEFAULT NULL,
  `POR_RET` decimal(5,2) DEFAULT NULL,
  `VAL_RET` decimal(14,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `T_I_DEV` decimal(5,0) DEFAULT NULL,
  `T_P_DEV` decimal(8,2) DEFAULT NULL,
  `CON_PAG` varchar(40) DEFAULT NULL,
  `DOFERTA` varchar(40) DEFAULT NULL,
  `LIN_N01` varchar(120) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `ENV_DOC` bigint DEFAULT '0',
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_DEV`,`NUM_DEV`),
  KEY `FK_IG0055_1` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0056`
--

DROP TABLE IF EXISTS `IG0056`;
CREATE TABLE IF NOT EXISTS `IG0056` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `TIP_DEV` varchar(2) NOT NULL DEFAULT '',
  `NUM_DEV` varchar(10) NOT NULL DEFAULT '',
  `FEC_DEV` date DEFAULT NULL,
  `COD_MOT` varchar(2) DEFAULT NULL,
  `TIP_PED` varchar(2) DEFAULT NULL,
  `NUM_PED` varchar(10) DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `CAN_DEV` decimal(8,0) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `P_COSTO` decimal(14,4) DEFAULT NULL,
  `T_COSTO` decimal(14,4) DEFAULT NULL,
  `P_VENTA` decimal(14,4) DEFAULT NULL,
  `T_VENTA` decimal(14,4) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(14,4) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `NOM_ART` varchar(100) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_DEV`,`NUM_DEV`,`COD_ART`) USING BTREE,
  KEY `IG56_COD_ART_FK` (`COD_ART`),
  KEY `FK_IG0056_3` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0057`
--

DROP TABLE IF EXISTS `IG0057`;
CREATE TABLE IF NOT EXISTS `IG0057` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `TIP_IMP` varchar(2) NOT NULL DEFAULT '',
  `NUM_IMP` varchar(10) NOT NULL DEFAULT '',
  `FEC_IMP` date DEFAULT NULL,
  `NUM_ORD` varchar(10) DEFAULT NULL,
  `FEC_ORD` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `NOM_PRO` varchar(100) DEFAULT NULL,
  `DIR_PRO` varchar(100) DEFAULT NULL,
  `COD_PAI` varchar(3) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `NUM_DAU` varchar(20) DEFAULT NULL,
  `FEC_LIQ` date DEFAULT NULL,
  `N_F_PRO` varchar(8) DEFAULT NULL,
  `F_F_PRO` date DEFAULT NULL,
  `COS_MER` decimal(12,2) DEFAULT NULL,
  `FLE_MER` decimal(12,2) DEFAULT NULL,
  `SEG_MER` decimal(12,2) DEFAULT NULL,
  `GAS_MER` decimal(12,2) DEFAULT NULL,
  `T_I_PED` decimal(5,0) DEFAULT NULL,
  `T_P_PED` decimal(8,0) DEFAULT NULL,
  `BAS_IV0` decimal(12,2) DEFAULT NULL,
  `BAS_IVA` decimal(12,2) DEFAULT NULL,
  `VAL_IVA` decimal(12,2) DEFAULT NULL,
  `VAL_NET` decimal(12,2) DEFAULT NULL,
  `VAL_PAG` decimal(12,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `CON_PAG` varchar(15) DEFAULT NULL,
  `LIN_N01` varchar(40) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `TOT_FOB` decimal(12,2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_IMP`,`NUM_IMP`),
  KEY `IG0057_COD_PRO_FK` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0057C`
--

DROP TABLE IF EXISTS `IG0057C`;
CREATE TABLE IF NOT EXISTS `IG0057C` (
  `COD_PTO` varchar(3) DEFAULT NULL,
  `TIP_IMP` varchar(2) DEFAULT NULL,
  `NUM_IMP` varchar(10) DEFAULT NULL,
  `DES_COS` varchar(20) DEFAULT NULL,
  `NUM_DOC` varchar(20) DEFAULT NULL,
  `VAL_COS` decimal(12,2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  KEY `IG0057C_FK` (`COD_PTO`,`TIP_IMP`,`NUM_IMP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0057HC`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0057NP`
--

DROP TABLE IF EXISTS `IG0057NP`;
CREATE TABLE IF NOT EXISTS `IG0057NP` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `TIP_ORD` varchar(2) NOT NULL DEFAULT '',
  `NUM_ORD` varchar(10) NOT NULL DEFAULT '',
  `FEC_ORD` date DEFAULT NULL,
  `FEC_LLE` date DEFAULT NULL,
  `NUM_IMP` varchar(10) DEFAULT NULL,
  `FEC_IMP` date DEFAULT NULL,
  `COD_PRO` varchar(10) DEFAULT NULL,
  `NOM_PRO` varchar(100) DEFAULT NULL,
  `DIR_PRO` varchar(100) DEFAULT NULL,
  `COD_PAI` varchar(3) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `NUM_DAU` varchar(20) DEFAULT NULL,
  `FEC_LIQ` date DEFAULT NULL,
  `N_F_PRO` varchar(8) DEFAULT NULL,
  `F_F_PRO` date DEFAULT NULL,
  `COS_MER` decimal(12,2) DEFAULT NULL,
  `FLE_MER` decimal(12,2) DEFAULT NULL,
  `SEG_MER` decimal(12,2) DEFAULT NULL,
  `GAS_MER` decimal(12,2) DEFAULT NULL,
  `T_I_PED` decimal(5,0) DEFAULT NULL,
  `T_P_PED` decimal(8,0) DEFAULT NULL,
  `BAS_IV0` decimal(12,2) DEFAULT NULL,
  `BAS_IVA` decimal(12,2) DEFAULT NULL,
  `VAL_IVA` decimal(12,2) DEFAULT NULL,
  `VAL_NET` decimal(12,2) DEFAULT NULL,
  `LIN_N01` varchar(40) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `VAL_PAG` decimal(12,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `CON_PAG` varchar(15) DEFAULT NULL,
  `TOT_FOB` decimal(12,2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_ORD`,`NUM_ORD`),
  KEY `IG0057NP_COD_PRO_FK` (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0058`
--

DROP TABLE IF EXISTS `IG0058`;
CREATE TABLE IF NOT EXISTS `IG0058` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `TIP_IMP` varchar(2) NOT NULL DEFAULT '',
  `NUM_IMP` varchar(10) NOT NULL DEFAULT '',
  `FEC_IMP` date DEFAULT NULL,
  `NUM_ORD` varchar(10) DEFAULT NULL,
  `FEC_ORD` date DEFAULT NULL,
  `COD_PRO` varchar(10) NOT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `NOM_ART` varchar(60) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `CAN_PED` decimal(8,2) DEFAULT NULL,
  `CAN_DEV` decimal(8,2) DEFAULT NULL,
  `P_IMPOR` decimal(12,4) DEFAULT NULL,
  `P_PROME` decimal(12,4) DEFAULT NULL,
  `P_COSTO` decimal(12,4) DEFAULT NULL,
  `T_COSTO` decimal(12,4) DEFAULT NULL,
  `EXI_ACT` decimal(8,2) DEFAULT NULL,
  `EXI_ANT` decimal(8,2) DEFAULT NULL,
  `P_P_ANT` decimal(12,4) DEFAULT NULL,
  `COS_ANT` decimal(12,4) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,4) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `FAC_COS` decimal(3,2) DEFAULT NULL,
  `SECUENCIA` decimal(3,0) DEFAULT NULL,
  `TOT_FOB` decimal(12,4) DEFAULT NULL,
  `P_I_ANT` decimal(12,4) DEFAULT NULL,
  `COS_REF` decimal(12,4) DEFAULT NULL,
  `N_F_PRO` varchar(20) DEFAULT NULL,
  `F_F_PRO` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_IMP`,`NUM_IMP`,`COD_ART`,`COD_PRO`) USING BTREE,
  KEY `IG0058_COD_PRO_FK` (`COD_PRO`),
  KEY `IG0058_COD_ART_FK` (`COD_ART`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0058HC`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0058NP`
--

DROP TABLE IF EXISTS `IG0058NP`;
CREATE TABLE IF NOT EXISTS `IG0058NP` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `TIP_ORD` varchar(2) NOT NULL DEFAULT '',
  `NUM_ORD` varchar(10) NOT NULL DEFAULT '',
  `FEC_ORD` date DEFAULT NULL,
  `FEC_LLE` date DEFAULT NULL,
  `NUM_IMP` varchar(10) DEFAULT NULL,
  `FEC_IMP` date DEFAULT NULL,
  `COD_PRO` varchar(10) NOT NULL DEFAULT '',
  `COD_BOD` varchar(2) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `NOM_ART` varchar(60) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `CAN_PED` decimal(8,2) DEFAULT NULL,
  `P_IMPOR` decimal(12,4) DEFAULT NULL,
  `P_COSTO` decimal(12,4) DEFAULT NULL,
  `T_COSTO` decimal(12,4) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `SECUENCIA` decimal(3,0) DEFAULT NULL,
  `P_PROME` decimal(12,4) DEFAULT NULL,
  `EXI_ACT` decimal(8,2) DEFAULT NULL,
  `EXI_ANT` decimal(8,2) DEFAULT NULL,
  `P_P_ANT` decimal(12,4) DEFAULT NULL,
  `COS_ANT` decimal(12,4) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,4) DEFAULT NULL,
  `TOT_FOB` decimal(12,4) DEFAULT NULL,
  `P_I_ANT` decimal(12,4) DEFAULT NULL,
  `N_F_PRO` varchar(20) DEFAULT NULL,
  `F_F_PRO` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_ORD`,`NUM_ORD`,`COD_ART`,`COD_PRO`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0060`
--

DROP TABLE IF EXISTS `IG0060`;
CREATE TABLE IF NOT EXISTS `IG0060` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `TIP_DEV` varchar(2) NOT NULL DEFAULT '',
  `NUM_DEV` varchar(10) NOT NULL DEFAULT '',
  `FEC_DEV` date DEFAULT NULL,
  `TIP_EMI` varchar(1) DEFAULT 'F',
  `COD_MOT` varchar(2) DEFAULT NULL,
  `EST_EMI` varchar(7) DEFAULT '001-001',
  `TIP_NOF` varchar(2) DEFAULT NULL,
  `NUM_NOF` varchar(10) DEFAULT NULL,
  `FEC_NOF` date DEFAULT NULL,
  `C_TRA_E` varchar(2) DEFAULT NULL,
  `NUM_DOC` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `VAL_BRU` decimal(14,2) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(14,2) DEFAULT NULL,
  `BAS_IVA` decimal(14,2) DEFAULT NULL,
  `BAS_IV0` decimal(14,2) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,2) DEFAULT NULL,
  `VAL_NET` decimal(14,2) DEFAULT NULL,
  `POR_RET` decimal(5,2) DEFAULT NULL,
  `VAL_RET` decimal(14,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `T_I_DEV` decimal(5,0) DEFAULT NULL,
  `T_P_DEV` decimal(8,2) DEFAULT NULL,
  `CON_PAG` varchar(40) DEFAULT NULL,
  `DOFERTA` varchar(40) DEFAULT NULL,
  `LIN_N01` varchar(120) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `ATIENDE` varchar(10) DEFAULT NULL,
  `ENV_DOC` bigint DEFAULT '0',
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_DEV`,`NUM_DEV`),
  KEY `IG60_COD_MOT_FK` (`COD_MOT`),
  KEY `IG60_NUM_NOF_FK` (`COD_PTO`,`COD_CAJ`,`TIP_NOF`,`NUM_NOF`),
  KEY `IG60_NUM_DOC_FK` (`COD_PTO`,`TIP_NOF`,`NUM_NOF`,`C_TRA_E`,`NUM_DOC`),
  KEY `FK_IG0060_3` (`COD_CLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IG0061`
--

DROP TABLE IF EXISTS `IG0061`;
CREATE TABLE IF NOT EXISTS `IG0061` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `TIP_DEV` varchar(2) NOT NULL DEFAULT '',
  `NUM_DEV` varchar(10) NOT NULL DEFAULT '',
  `FEC_DEV` date DEFAULT NULL,
  `COD_MOT` varchar(2) DEFAULT NULL,
  `TIP_NOF` varchar(2) DEFAULT NULL,
  `NUM_NOF` varchar(10) DEFAULT NULL,
  `NUM_PED` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `NOM_ART` varchar(60) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `CAN_DEV` decimal(8,0) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `P_COSTO` decimal(14,4) DEFAULT NULL,
  `T_COSTO` decimal(14,4) DEFAULT NULL,
  `P_VENTA` decimal(14,4) DEFAULT NULL,
  `T_VENTA` decimal(14,4) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `VAL_DES` decimal(14,4) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`TIP_DEV`,`NUM_DEV`,`COD_ART`) USING BTREE,
  KEY `IG61_COD_ART_FK` (`COD_ART`),
  KEY `FK_IG0061_3` (`COD_CLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `IGANUL`
--

DROP TABLE IF EXISTS `IGANUL`;
CREATE TABLE IF NOT EXISTS `IGANUL` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) DEFAULT NULL,
  `NUM_ANU` varchar(10) NOT NULL DEFAULT '',
  `FEC_ANU` date DEFAULT NULL,
  `TIP_NOF` varchar(2) DEFAULT NULL,
  `NUM_NOF` varchar(10) DEFAULT NULL,
  `FEC_VTA` date DEFAULT NULL,
  `ATIENDE` varchar(2) DEFAULT NULL,
  `OBSERVA` varchar(60) DEFAULT NULL,
  `NUE_FAC` varchar(2) DEFAULT NULL,
  `IDS_DOC` bigint DEFAULT '0',
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`NUM_ANU`),
  KEY `IDX_TIP_PED_NUM_PED_FEC_VTA` (`TIP_NOF`,`NUM_NOF`,`FEC_VTA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LB0001`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LB0002`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LB0004`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LB0005`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0014`
--

DROP TABLE IF EXISTS `MG0014`;
CREATE TABLE IF NOT EXISTS `MG0014` (
  `C_I_OCG` varchar(2) NOT NULL DEFAULT '',
  `COD_OCG` varchar(3) NOT NULL, -- 01 => Pais   02 => Provincia    03 => Ciudad
  `NOM_OCG` varchar(200) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`C_I_OCG`,`COD_OCG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0015`
--

DROP TABLE IF EXISTS `MG0015`;
CREATE TABLE IF NOT EXISTS `MG0015` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `NOM_PTO` varchar(30) DEFAULT NULL,
  `COD_PAI` varchar(2) DEFAULT NULL,
  `COD_EST` varchar(2) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `DIR_PTO` varchar(30) DEFAULT NULL,
  `TEL_N01` varchar(10) DEFAULT NULL,
  `TEL_N02` varchar(10) DEFAULT NULL,
  `NUM_FAX` varchar(10) DEFAULT NULL,
  `CORRE_E` varchar(30) DEFAULT NULL,
  `CORRE_CT` varchar(60) DEFAULT NULL,
  `COD_RES` varchar(10) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `FEC_PTO` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0016`
--

DROP TABLE IF EXISTS `MG0016`;
CREATE TABLE IF NOT EXISTS `MG0016` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) NOT NULL DEFAULT '',
  `NOM_CAJ` varchar(30) DEFAULT NULL,
  `UBI_CAJ` varchar(30) DEFAULT NULL,
  `COD_RES` varchar(10) DEFAULT NULL,
  `AUT_CAJ` varchar(1) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `COB_CAJ` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `CAJ_FEC` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_CAJ`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0017`
--

DROP TABLE IF EXISTS `MG0017`;
CREATE TABLE IF NOT EXISTS `MG0017` (
  `COD_DES` varchar(2) NOT NULL DEFAULT '',
  `NOM_DES` varchar(30) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_DES`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0020`
--

DROP TABLE IF EXISTS `MG0020`;
CREATE TABLE IF NOT EXISTS `MG0020` (
  `COD_MED` varchar(2) NOT NULL DEFAULT '',
  `N_A_MED` varchar(4) DEFAULT NULL,
  `NOM_MED` varchar(30) DEFAULT NULL,
  `FAC_CON` decimal(12,2) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_MED`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0021`
--

DROP TABLE IF EXISTS `MG0021`;
CREATE TABLE IF NOT EXISTS `MG0021` (
  `COD_DIV` varchar(2) NOT NULL DEFAULT '',
  `NOM_DIV` varchar(30) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_DIV`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0022`
--


-- --------------------------------------------------------



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0024`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0025`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0026`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0030`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0031`
--

DROP TABLE IF EXISTS `MG0031`;
CREATE TABLE IF NOT EXISTS `MG0031` (
  `COD_CLI` varchar(10) NOT NULL DEFAULT '',
  `CED_RUC` varchar(15) DEFAULT NULL,
  `COD_SCEN` varchar(15) DEFAULT NULL,
  `FAC_CLI` varchar(15) DEFAULT NULL,
  `NOM_CLI` varchar(100) DEFAULT NULL,
  `FEC_CLI` date DEFAULT NULL,
  `COD_PAI` varchar(2) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `DIR_CLI` varchar(100) DEFAULT NULL,
  `TEL_N01` varchar(80) DEFAULT NULL,
  `TEL_N02` varchar(50) DEFAULT NULL,
  `NUM_FAX` varchar(50) DEFAULT NULL,
  `CORRE_E` varchar(100) DEFAULT NULL,
  `TIP_CLI` varchar(2) DEFAULT NULL,
  `TIP_EST` varchar(2) DEFAULT NULL,
  `COD_CPE` varchar(3) DEFAULT NULL,
  `C_TRA_E` varchar(3) DEFAULT NULL,
  `LIM_CRE` decimal(12,2) DEFAULT NULL,
  `MAX_CRE` decimal(12,2) DEFAULT '0.00',
  `LIM_DIA` decimal(3,0) DEFAULT NULL,
  `MAX_DIA` int DEFAULT '0',
  `COD_FOR` varchar(2) DEFAULT '1',
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `CTA_BAN` varchar(20) DEFAULT NULL,
  `COD_CTA` varchar(12) DEFAULT NULL,
  `COD_VEN` varchar(10) DEFAULT NULL,
  `NOM_CTO` varchar(40) DEFAULT NULL,
  `TEL_CTO` varchar(10) DEFAULT NULL,
  `ACT_CLI` varchar(40) DEFAULT NULL,
  `NOM_GER` varchar(100) DEFAULT NULL,
  `NOM_SEC` varchar(25) DEFAULT NULL,
  `OBSER01` varchar(900) DEFAULT NULL,
  `OBSER02` varchar(900) DEFAULT NULL,
  `OBSER03` text,
  `N_U_PAG` varchar(10) DEFAULT NULL,
  `N_S_PRO` varchar(7) DEFAULT NULL,
  `N_A_PRO` varchar(10) DEFAULT NULL,
  `FEC_CAD` varchar(7) DEFAULT NULL,
  `COD_I_C` varchar(2) DEFAULT NULL,
  `NUM_MAT` bigint DEFAULT NULL,
  `COD_CAT` varchar(1) DEFAULT '',
  `IDS_CAR` bigint DEFAULT NULL,
  `IDS_MOD` bigint DEFAULT NULL,
  `IDS_REP` bigint DEFAULT NULL,
  `EMAIL_U` varchar(60) DEFAULT NULL,
  `EST_CLI` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `IMG_CLI` mediumblob,
  `CTA_IVA` varchar(12) DEFAULT NULL,
  `P_R_IVA` decimal(5,2) DEFAULT NULL,
  `NUM_CRE` varchar(10) DEFAULT NULL,
  `TIP_PRE` varchar(1) DEFAULT NULL,
  `LUG_DES` varchar(30) DEFAULT NULL,
  `NOM_ACC` varchar(100) DEFAULT NULL,
  `DIA_GRA` decimal(3,0) DEFAULT NULL,
  `CLI_DIS` varchar(1) DEFAULT NULL,
  `EST_FAC` varchar(1) DEFAULT NULL,
  `REG_ASO` bigint DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_CLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0031F`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0031R`
--

DROP TABLE IF EXISTS `MG0031R`;
CREATE TABLE IF NOT EXISTS `MG0031R` (
  `ID_EMC` bigint NOT NULL AUTO_INCREMENT,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `NOM_EMC` varchar(100) DEFAULT NULL,
  `CRE_EMC` decimal(12,4) DEFAULT NULL,
  `TIE_CRE` int DEFAULT NULL,
  `TEL_EMC` varchar(20) DEFAULT NULL,
  `TIE_COM` int DEFAULT NULL,
  `CHE_PRO` varchar(1) DEFAULT NULL,
  `PER_INF` varchar(50) DEFAULT NULL,
  `COD_VER` varchar(10) DEFAULT NULL,
  `FEC_VER` datetime DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`ID_EMC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0031_AUX`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0032`
--

DROP TABLE IF EXISTS `MG0032`;
CREATE TABLE IF NOT EXISTS `MG0032` (
  `COD_PRO` varchar(10) NOT NULL DEFAULT '',
  `CED_RUC` varchar(15) DEFAULT NULL,
  `NOM_PRO` varchar(70) DEFAULT NULL,
  `FEC_PRO` date DEFAULT NULL,
  `COD_PAI` varchar(2) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `DIR_PRO` varchar(100) DEFAULT NULL,
  `TEL_N01` varchar(50) DEFAULT NULL,
  `TEL_N02` varchar(50) DEFAULT NULL,
  `NUM_FAX` varchar(50) DEFAULT NULL,
  `CORRE_E` varchar(100) DEFAULT NULL,
  `TIP_PRO` varchar(2) DEFAULT NULL,
  `C_TRA_E` varchar(2) DEFAULT NULL,
  `LIM_CRE` decimal(12,2) DEFAULT NULL,
  `LIM_DIA` decimal(3,0) DEFAULT NULL,
  `POR_DES` decimal(5,2) DEFAULT NULL,
  `CTA_BAN` varchar(20) DEFAULT NULL,
  `COD_CTA` varchar(12) DEFAULT NULL,
  `COD_VEN` varchar(10) DEFAULT NULL,
  `NOM_CTO` varchar(40) DEFAULT NULL,
  `TEL_CTO` varchar(10) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `ACT_PRO` varchar(80) DEFAULT NULL,
  `NOM_GER` varchar(25) DEFAULT NULL,
  `NOM_SEC` varchar(25) DEFAULT NULL,
  `OBSER01` varchar(500) DEFAULT NULL,
  `N_U_PAG` varchar(10) DEFAULT NULL,
  `N_S_PRO` varchar(7) DEFAULT NULL,
  `N_A_PRO` varchar(10) DEFAULT NULL,
  `FEC_CAD` varchar(10) DEFAULT NULL,
  `COD_I_P` varchar(2) DEFAULT NULL,
  `TIP_SER` varchar(3) DEFAULT NULL,
  `REG_ASO` varchar(20) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_PRO` varchar(1) DEFAULT NULL,
  `CTA_IVA` varchar(12) DEFAULT NULL,
  `P_R_IVA` decimal(5,2) DEFAULT NULL,
  `IMG_PRO` mediumblob,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PRO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0032F`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0033`
--

DROP TABLE IF EXISTS `MG0033`;
CREATE TABLE IF NOT EXISTS `MG0033` (
  `COD_EMP` varchar(10) NOT NULL DEFAULT '',
  `CED_RUC` varchar(15) DEFAULT NULL,
  `NOM_EMP` varchar(70) DEFAULT NULL,
  `FEC_EMP` date DEFAULT NULL,
  `FEC_NAC` date DEFAULT NULL,
  `COD_VEN` varchar(10) DEFAULT NULL,
  `USU_ACC` varchar(10) DEFAULT NULL,
  `IDS_DEP` bigint DEFAULT NULL,
  `COD_PAI` varchar(2) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `DIR_EMP` varchar(60) DEFAULT NULL,
  `TEL_N01` varchar(10) DEFAULT NULL,
  `TEL_N02` varchar(10) DEFAULT NULL,
  `EXT_EMP` varchar(10) DEFAULT NULL,
  `NUM_FAX` varchar(10) DEFAULT NULL,
  `CORRE_E` varchar(30) DEFAULT NULL,
  `TIP_EMP` varchar(2) DEFAULT NULL,
  `COD_CTA` varchar(12) DEFAULT NULL,
  `OBSER01` varchar(120) DEFAULT NULL,
  `CUO_SEM` decimal(12,2) DEFAULT '0.00',
  `CUO_MES` decimal(12,2) DEFAULT '0.00',
  `CUO_SHP` decimal(12,2) DEFAULT '0.00',
  `CUO_MHP` decimal(12,2) DEFAULT '0.00',
  `CUO_COB` decimal(12,2) DEFAULT '0.00',
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `IMG_EMP` mediumblob,
  `EST_PER` varchar(1) DEFAULT NULL,
  `EST_CVAR` varchar(1) DEFAULT '0',
  `SUP_VTA` varchar(10) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_EMP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0033CT`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0033F`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0040`
--

DROP TABLE IF EXISTS `MG0040`;
CREATE TABLE IF NOT EXISTS `MG0040` (
  `C_TRA_E` varchar(3) NOT NULL,
  `N_TRA_E` varchar(30) DEFAULT NULL,
  `T_TRA_E` varchar(1) DEFAULT NULL, -- tipo de transaccion si va en el Debe "D" o en el Haber "H"
  `NUM_T_E` decimal(5,0) DEFAULT NULL, -- Numero de transaccion. Siempre sera cero
  `V_TRA_E` decimal(14,2) DEFAULT NULL, -- valor de la transaccion. Siempre sera cero
  `P_TRA_E` decimal(5,2) DEFAULT NULL, -- pasivo de la transaccion. Siempre sera cero
  `F_U_ACT` date DEFAULT NULL, -- fecha de modificacion
  `CON_T_E` varchar(10) DEFAULT NULL, -- contador de la transaccion. Siempre NULL o cero
  `TIP_PAG` varchar(1) DEFAULT '0', -- tipo de pago. Es como estado Logico. 1 => se presenta y 0 => se presenta.
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`C_TRA_E`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0041`
--

DROP TABLE IF EXISTS `MG0041`;
CREATE TABLE IF NOT EXISTS `MG0041` (
  `COD_MOT` varchar(2) NOT NULL DEFAULT '',
  `NOM_MOT` varchar(30) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_MOT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MG0050`
--

--
-- Estructura de tabla para la tabla `MG0051`
--

DROP TABLE IF EXISTS `MG0051`;
CREATE TABLE IF NOT EXISTS `MG0051` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) NOT NULL DEFAULT '',
  `FEC_TRA` date DEFAULT NULL,
  `TRA_ORI` varchar(2) NOT NULL DEFAULT '',
  `DOC_ORI` varchar(10) DEFAULT NULL,
  `C_TRA_E` varchar(2) NOT NULL DEFAULT '',
  `NUM_DOC` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_DES` varchar(10) DEFAULT NULL,
  `FEC_VEN` date DEFAULT NULL,
  `COD_BCO` varchar(10) DEFAULT NULL,
  `N_CTA_B` varchar(20) DEFAULT NULL,
  `NOM_GIR` varchar(30) DEFAULT NULL,
  `NOM_BEN` varchar(30) DEFAULT NULL,
  `VAL_TRA` decimal(14,2) DEFAULT NULL,
  `VAL_REC` decimal(14,2) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `TIP_CTA` varchar(1) DEFAULT NULL,
  `HAB_DOC` varchar(1) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NC010101`
--

DROP TABLE IF EXISTS `NC010101`;
CREATE TABLE IF NOT EXISTS `NC010101` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) NOT NULL DEFAULT '',
  `TIP_NOF` varchar(2) NOT NULL DEFAULT '',
  `NUM_NOF` varchar(10) NOT NULL DEFAULT '',
  `FEC_VTA` date DEFAULT NULL,
  `NUM_COT` varchar(10) DEFAULT NULL,
  `FEC_COT` date DEFAULT NULL,
  `ATIENDE` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `CED_RUC` varchar(15) DEFAULT NULL,
  `NOM_CLI` varchar(90) DEFAULT NULL,
  `DIR_CLI` varchar(60) DEFAULT NULL,
  `COD_CIU` varchar(2) DEFAULT NULL,
  `COD_PAI` varchar(2) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `VAL_BRU` decimal(14,2) DEFAULT NULL,
  `POR_DES` decimal(7,4) DEFAULT NULL,
  `VAL_DES` decimal(14,2) DEFAULT NULL,
  `VAL_FLE` decimal(14,2) DEFAULT NULL,
  `BAS_IVA` decimal(14,2) DEFAULT NULL,
  `BAS_IV0` decimal(14,2) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,2) DEFAULT NULL,
  `VAL_NET` decimal(14,2) DEFAULT NULL,
  `POR_R_F` decimal(5,2) DEFAULT NULL,
  `VAL_R_F` decimal(14,2) DEFAULT NULL,
  `POR_R_I` decimal(5,2) DEFAULT NULL,
  `VAL_R_I` decimal(14,2) DEFAULT NULL,
  `T_ITEMS` decimal(5,0) DEFAULT NULL,
  `T_P_NOF` decimal(8,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `CON_PAG` varchar(20) DEFAULT NULL,
  `DOFERTA` varchar(20) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `LIN_N01` varchar(100) DEFAULT NULL,
  `GUI_REM` varchar(100) DEFAULT NULL,
  `ORD_PED` varchar(30) DEFAULT NULL,
  `FEC_ENT` timestamp NULL DEFAULT NULL,
  `FEC_DES` timestamp NULL DEFAULT NULL,
  `EST_ENT` varchar(1) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `POR_REN` decimal(5,2) DEFAULT NULL,
  `COM_TER` decimal(12,2) DEFAULT NULL,
  `FEC_VEN` date DEFAULT NULL,
  `NOT_ENT` varchar(100) DEFAULT NULL,
  `NUM_ANU` varchar(10) DEFAULT NULL,
  `TIP_FAC` varchar(2) DEFAULT NULL,
  `NUM_FAC` varchar(10) DEFAULT NULL,
  `FEC_FAC` date DEFAULT NULL,
  `VAL_COS` decimal(12,2) DEFAULT NULL,
  `LIM_CRE` decimal(12,2) DEFAULT NULL,
  `SAL_CAR` decimal(12,2) DEFAULT NULL,
  `DIA_VEN` decimal(12,2) DEFAULT NULL,
  `NOM_CON` varchar(100) DEFAULT NULL,
  `FEC_I_T` date DEFAULT NULL,
  `FEC_T_T` date DEFAULT NULL,
  `MOT_TRA` decimal(1,0) DEFAULT NULL,
  `PUN_PAR` varchar(90) DEFAULT NULL,
  `PUN_LLE` varchar(90) DEFAULT NULL,
  `FEC_PAR` date DEFAULT NULL,
  `COD_TRA` varchar(10) DEFAULT NULL,
  `NOM_TRA` varchar(40) DEFAULT NULL,
  `C_R_TRA` varchar(15) DEFAULT NULL,
  `LIN_N02` varchar(120) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_CAJ`,`TIP_NOF`,`NUM_NOF`),
  KEY `VC01_COD_CLI_FK` (`COD_CLI`),
  KEY `VC01_ATIENDE_FK` (`ATIENDE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ND010101`
--

DROP TABLE IF EXISTS `ND010101`;
CREATE TABLE IF NOT EXISTS `ND010101` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) NOT NULL DEFAULT '',
  `TIP_NOF` varchar(2) NOT NULL DEFAULT '',
  `NUM_NOF` varchar(10) NOT NULL DEFAULT '',
  `FEC_VTA` date DEFAULT NULL,
  `NUM_COT` varchar(10) DEFAULT NULL,
  `ATIENDE` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `IND_DES` varchar(1) DEFAULT NULL,
  `NOM_ART` varchar(60) DEFAULT NULL,
  `EXI_TOT` decimal(10,2) DEFAULT NULL,
  `EXI_COM` decimal(10,2) DEFAULT NULL,
  `CAN_DES` decimal(10,2) DEFAULT NULL,
  `CAN_DEV` decimal(10,2) DEFAULT NULL,
  `P_COSTO` decimal(14,4) DEFAULT NULL,
  `T_COSTO` decimal(14,4) DEFAULT NULL,
  `IND_PRE` varchar(1) DEFAULT NULL,
  `P_LISTA` decimal(14,4) DEFAULT NULL,
  `P_VENTA` decimal(14,4) DEFAULT NULL,
  `T_VENTA` decimal(14,4) DEFAULT NULL,
  `POR_DES` decimal(7,4) DEFAULT NULL,
  `VAL_DES` decimal(14,4) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `VAL_IVA` decimal(12,4) DEFAULT NULL,
  `P_NETOS` decimal(12,4) DEFAULT NULL,
  `T_NETOS` decimal(12,4) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `P_REAL` decimal(12,4) DEFAULT NULL,
  `SECUENCIA` int UNSIGNED DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_CAJ`,`TIP_NOF`,`NUM_NOF`,`COD_ART`) USING BTREE,
  KEY `VD01_COD_CLI_FK` (`COD_CLI`),
  KEY `VD01_COD_ART_FK` (`COD_ART`),
  KEY `VD01_ATIENDE_FK` (`ATIENDE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NOT_FAC`
--

DROP TABLE IF EXISTS `NOT_FAC`;
CREATE TABLE IF NOT EXISTS `NOT_FAC` (
  `COD_PTO` varchar(3) NOT NULL,
  `COD_CAJ` varchar(3) NOT NULL,
  `TIP_NOF` varchar(2) NOT NULL DEFAULT '',
  `NUM_NOF` varchar(10) NOT NULL DEFAULT '',
  `NOM_NOF` varchar(30) DEFAULT NULL,
  `EDOC_TIP_DOC` varchar(2) DEFAULT '',
  `EDOC_EST` varchar(1) DEFAULT '0',
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `FEC_NOF` date DEFAULT NULL,
  `HOR_NOF` varchar(8) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `TIP_DOC` varchar(2) DEFAULT NULL,
  `C_ITEMS` decimal(3,0) DEFAULT NULL,
  `CTA_IVA` varchar(9) DEFAULT '',
  `NUM_INI` varchar(10) DEFAULT NULL,
  `NUM_FIN` varchar(10) DEFAULT NULL,
  `SEC_AUT` varchar(1) DEFAULT '',
  `NUM_SER` varchar(10) DEFAULT NULL,
  `NUM_AUT` varchar(10) DEFAULT NULL,
  `FEC_CAD` varchar(5) DEFAULT NULL,
  `INC_IVA` varchar(1) DEFAULT NULL,
  `DOC_AUT` varchar(1) DEFAULT NULL,
  `FEC_AUT` varchar(10) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_CAJ`,`TIP_NOF`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OB0054`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OB0057`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OT0001`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OT0001P`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OT0003`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OT0003P`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OT0004`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OT0004P`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `OT0005`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RE0001`
--

DROP TABLE IF EXISTS `RE0001`;
CREATE TABLE IF NOT EXISTS `RE0001` (
  `IDS_REN` bigint NOT NULL AUTO_INCREMENT,
  `TIP_NOF` varchar(2) NOT NULL,
  `NUM_NOF` varchar(10) NOT NULL,
  `FEC_REN` date DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `DETALLE` varchar(100) DEFAULT NULL,
  `TOT_DOC` decimal(14,4) DEFAULT NULL,
  `TOT_ANT` decimal(14,4) DEFAULT NULL,
  `OBS_GEN` text,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_REN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RE0002`
--

DROP TABLE IF EXISTS `RE0002`;
CREATE TABLE IF NOT EXISTS `RE0002` (
  `IDS_DRE` bigint NOT NULL AUTO_INCREMENT,
  `IDS_REN` bigint NOT NULL,
  `TIP_NOF` varchar(2) NOT NULL,
  `NUM_NOF` varchar(10) NOT NULL,
  `C_TRA_E` varchar(2) NOT NULL,
  `NUM_DOC` varchar(10) NOT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `VALOR_D` decimal(12,4) DEFAULT NULL,
  `F_SUS_D` date DEFAULT NULL,
  `F_VEN_D` date DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_DRE`),
  KEY `IDS_REN` (`IDS_REN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `REGISTRO`
--

DROP TABLE IF EXISTS `REGISTRO`;
CREATE TABLE IF NOT EXISTS `REGISTRO` (
  `NUM_REG` int NOT NULL AUTO_INCREMENT,
  `COD_TIP` varchar(2) DEFAULT NULL,
  `NUM_TRA` varchar(10) DEFAULT NULL,
  `COD_PTO` varchar(3) DEFAULT NULL,
  `COD_CEN` varchar(15) DEFAULT NULL,
  `COD_SUB` varchar(15) DEFAULT NULL,
  `FEC_TRA` date DEFAULT NULL,
  `COD_CTA` varchar(12) DEFAULT NULL,
  `NOM_CTA` varchar(120) DEFAULT NULL,
  `DET_TRA` varchar(93) DEFAULT NULL,
  `VAL_DEB` double(12,2) DEFAULT NULL,
  `VAL_HAB` double(12,2) DEFAULT NULL,
  `ESTATUS` varchar(1) DEFAULT NULL,
  `CED_RUC` varchar(13) DEFAULT NULL,
  `COD_URS` varchar(10) DEFAULT NULL,
  `COD_AUX` varchar(12) DEFAULT NULL,
  `FEC_SIS` date NOT NULL,
  `HOR_SIS` varchar(10) NOT NULL,
  `USUARIO` varchar(250) NOT NULL,
  `EQUIPO` varchar(15) NOT NULL,
  `C_TRA_E` varchar(2) DEFAULT NULL,
  `NUM_DOC` varchar(10) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_REG`),
  KEY `COD_CTA_FK2` (`COD_CTA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `REGISTRO00`
--

DROP TABLE IF EXISTS `REGISTRO00`;
CREATE TABLE IF NOT EXISTS `REGISTRO00` (
  `NUM_REG` int NOT NULL AUTO_INCREMENT,
  `COD_TIP` varchar(2) DEFAULT NULL,
  `NUM_TRA` varchar(10) DEFAULT NULL,
  `COD_PTO` varchar(3) DEFAULT NULL,
  `COD_CEN` varchar(3) DEFAULT NULL,
  `COD_SUB` varchar(2) DEFAULT NULL,
  `FEC_TRA` date DEFAULT NULL,
  `COD_CTA` varchar(12) DEFAULT NULL,
  `NOM_CTA` varchar(120) DEFAULT NULL,
  `DET_TRA` varchar(93) DEFAULT NULL,
  `VAL_DEB` double(12,2) DEFAULT NULL,
  `VAL_HAB` double(12,2) DEFAULT NULL,
  `ESTATUS` varchar(1) DEFAULT NULL,
  `CED_RUC` varchar(13) DEFAULT NULL,
  `COD_URS` varchar(10) DEFAULT NULL,
  `COD_AUX` varchar(12) DEFAULT NULL,
  `FEC_SIS` date NOT NULL,
  `HOR_SIS` varchar(10) NOT NULL,
  `USUARIO` varchar(250) NOT NULL,
  `EQUIPO` varchar(15) NOT NULL,
  `C_TRA_E` varchar(2) DEFAULT NULL,
  `NUM_DOC` varchar(10) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`NUM_REG`),
  KEY `FK_cod_cta_ant_1` (`COD_CTA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRIANUL`
--

DROP TABLE IF EXISTS `SRIANUL`;
CREATE TABLE IF NOT EXISTS `SRIANUL` (
  `TIPOCOMPROBANTE` varchar(2) DEFAULT NULL,
  `ESTABLECIMIENTO` varchar(3) DEFAULT NULL,
  `PUNTOEMISION` varchar(3) DEFAULT NULL,
  `SECUENCIALINICIO` varchar(10) DEFAULT NULL,
  `SECUENCIALFIN` varchar(10) DEFAULT NULL,
  `AUTORIZACION` varchar(50) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRICOMPAUT`
--

DROP TABLE IF EXISTS `SRICOMPAUT`;
CREATE TABLE IF NOT EXISTS `SRICOMPAUT` (
  `IDS_CAU` bigint NOT NULL AUTO_INCREMENT,
  `COD_CAU` varchar(3) DEFAULT NULL,
  `NOM_CAU` varchar(200) DEFAULT NULL,
  `IND_REP` varchar(2) DEFAULT NULL,
  `FEC_CRE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FEC_MOD` timestamp NULL DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`IDS_CAU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRICOMPRA`
--

DROP TABLE IF EXISTS `SRICOMPRA`;
CREATE TABLE IF NOT EXISTS `SRICOMPRA` (
  `CODSUSTENTO` varchar(2) DEFAULT NULL,
  `TPIDPROV` varchar(2) DEFAULT NULL,
  `IDPROV` varchar(13) DEFAULT NULL,
  `TIPOCOMPROBANTE` varchar(2) DEFAULT NULL,
  `PARTEREL` varchar(2) DEFAULT NULL,
  `FECHAREGISTRO` date DEFAULT NULL,
  `ESTABLECIMIENTO` varchar(3) DEFAULT NULL,
  `PUNTOEMISION` varchar(3) DEFAULT NULL,
  `SECUENCIAL` varchar(10) DEFAULT NULL,
  `FECHAEMISION` date DEFAULT NULL,
  `AUTORIZACION` varchar(10) DEFAULT NULL,
  `BASENOGRAIVA` decimal(12,2) DEFAULT NULL,
  `BASEIMPONIBLE` decimal(12,2) DEFAULT NULL,
  `BASEIMPGRAV` decimal(12,2) DEFAULT NULL,
  `BASEIMPEXE` decimal(12,2) DEFAULT '0.00',
  `MONTOICE` decimal(12,2) DEFAULT NULL,
  `MONTOIVA` decimal(12,2) DEFAULT NULL,
  `VALRETBIEN10` decimal(12,2) DEFAULT '0.00',
  `VALRETSERV20` decimal(12,2) DEFAULT '0.00',
  `VALORRETBIENES` decimal(12,2) DEFAULT NULL,
  `VALRETSERV50` decimal(12,2) DEFAULT '0.00',
  `VALORRETSERVICIOS` decimal(12,2) DEFAULT NULL,
  `VALRETSERV100` decimal(12,2) DEFAULT NULL,
  `TOTBASESIMPREEMB` decimal(12,2) DEFAULT '0.00',
  `CODRETAIR` varchar(5) DEFAULT NULL,
  `BASEIMPAIR` decimal(12,2) DEFAULT NULL,
  `PORCENTAJEAIR` decimal(5,2) DEFAULT NULL,
  `VALRETAIR` decimal(12,2) DEFAULT NULL,
  `CODRETAIR1` varchar(5) DEFAULT NULL,
  `BASEIMPAIR1` decimal(12,2) DEFAULT NULL,
  `PORCENTAJEAIR1` decimal(5,2) DEFAULT NULL,
  `VALRETAIR1` decimal(12,2) DEFAULT NULL,
  `ESTABRETENCION1` varchar(3) DEFAULT NULL,
  `PTOEMIRETENCION1` varchar(3) DEFAULT NULL,
  `SECRETENCION1` varchar(9) DEFAULT NULL,
  `AUTRETENCION1` varchar(10) DEFAULT NULL,
  `FECHAEMIRET1` date DEFAULT NULL,
  `DOCMODIFICADO` varchar(2) DEFAULT NULL,
  `ESTABMODIFICADO` varchar(3) DEFAULT NULL,
  `PTOEMIMODIFICADO` varchar(3) DEFAULT NULL,
  `SECMODIFICADO` varchar(7) DEFAULT NULL,
  `AUTMODIFICADO` varchar(10) DEFAULT NULL,
  `PAGOLOCEXT` varchar(2) DEFAULT NULL,
  `PAISEFECPAGO` varchar(3) DEFAULT NULL,
  `APLICCONVDOBTRIB` varchar(2) DEFAULT NULL,
  `PAGEXTSUJRETNORLEG` varchar(2) DEFAULT NULL,
  `FORMAPAGO` varchar(2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRINFORMA`
--

DROP TABLE IF EXISTS `SRINFORMA`;
CREATE TABLE IF NOT EXISTS `SRINFORMA` (
  `NUMERORUC` varchar(13) DEFAULT NULL,
  `RAZONSOCIAL` varchar(60) DEFAULT NULL,
  `DIRECCIONM` varchar(60) DEFAULT NULL,
  `TELEFONO` varchar(9) DEFAULT NULL,
  `FAX` varchar(9) DEFAULT NULL,
  `EMAIL` varchar(60) DEFAULT NULL,
  `TPIDREPRE` varchar(1) DEFAULT NULL,
  `IDREPRE` varchar(13) DEFAULT NULL,
  `RUCCONTADOR` varchar(13) DEFAULT NULL,
  `ANIO` varchar(4) DEFAULT NULL,
  `MES` varchar(2) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRITIPEMI`
--

DROP TABLE IF EXISTS `SRITIPEMI`;
CREATE TABLE IF NOT EXISTS `SRITIPEMI` (
  `Ids` bigint NOT NULL AUTO_INCREMENT,
  `Tipo` varchar(60) DEFAULT NULL,
  `Codigo` varchar(1) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaFin` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`Ids`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRITIPRE`
--

DROP TABLE IF EXISTS `SRITIPRE`;
CREATE TABLE IF NOT EXISTS `SRITIPRE` (
  `CODIGO` varchar(4) NOT NULL DEFAULT '',
  `COD_ANE` varchar(4) DEFAULT NULL,
  `DESCRI` varchar(75) DEFAULT NULL,
  `VALPOR` decimal(8,2) DEFAULT NULL,
  `CUENTA` varchar(15) DEFAULT NULL,
  `CUEN_V` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`CODIGO`),
  KEY `CUENTA_FK3` (`CUENTA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRITIPSU`
--

DROP TABLE IF EXISTS `SRITIPSU`;
CREATE TABLE IF NOT EXISTS `SRITIPSU` (
  `COD_SUS` varchar(2) NOT NULL,
  `DES_SUS` varchar(75) DEFAULT NULL,
  `TIP_COM` varchar(40) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_SUS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SRIVENTAS`
--

DROP TABLE IF EXISTS `SRIVENTAS`;
CREATE TABLE IF NOT EXISTS `SRIVENTAS` (
  `TPIDCLIENTE` varchar(2) DEFAULT NULL,
  `IDCLIENTE` varchar(13) DEFAULT NULL,
  `PARTERELVTAS` varchar(2) DEFAULT 'NO',
  `CODIGODOCUMENTO` varchar(2) DEFAULT NULL,
  `TIPOCOMPROBANTE` varchar(2) DEFAULT NULL,
  `TIPOEM` varchar(1) DEFAULT NULL,
  `NUMEROCOMPROBANTES` varchar(12) DEFAULT NULL,
  `BASENOGRAIVA` decimal(12,2) DEFAULT NULL,
  `BASEIMPONIBLE` decimal(12,2) DEFAULT NULL,
  `BASEIMPGRAV` decimal(12,2) DEFAULT NULL,
  `MONTOIVA` decimal(12,2) DEFAULT NULL,
  `MONTOICE` decimal(12,2) DEFAULT '0.00',
  `VALORRETIVA` decimal(12,2) DEFAULT NULL,
  `VALORRETRENTA` decimal(12,2) DEFAULT NULL,
  `FORMAPAGO` varchar(2) DEFAULT '',
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1' -- estado 1 => operativo / 0 => eliminado
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TG0001`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TG0002`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TG0003`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TG0004`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TG0005`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TG0006`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIPOS`
--

DROP TABLE IF EXISTS `TIPOS`;
CREATE TABLE IF NOT EXISTS `TIPOS` (
  `COD_TIP` varchar(2) NOT NULL DEFAULT '',
  `NOM_TIP` varchar(30) NOT NULL,
  `CONTADOR` varchar(10) DEFAULT NULL,
  `FEC_SIS` date NOT NULL,
  `HOR_SIS` varchar(10) NOT NULL,
  `USUARIO` varchar(250) NOT NULL,
  `EQUIPO` varchar(15) NOT NULL,
  PRIMARY KEY (`COD_TIP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIP_CON`
--

DROP TABLE IF EXISTS `TIP_CON`;
CREATE TABLE IF NOT EXISTS `TIP_CON` (
  `COD_CON` varchar(2) NOT NULL DEFAULT '',
  `NOM_CON` varchar(30) DEFAULT NULL,
  `FEC_CON` date DEFAULT NULL,
  `POR_R_F` decimal(5,2) DEFAULT NULL,
  `POR_R_I` decimal(5,2) DEFAULT NULL,
  `GRA_IVA` decimal(1,0) DEFAULT NULL,
  `REG_ASO` decimal(5,0) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_CON`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TONERS`
--

DROP TABLE IF EXISTS `TONERS`;
CREATE TABLE IF NOT EXISTS `TONERS` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_MAR` varchar(3) NOT NULL DEFAULT '',
  `COD_PRO` varchar(10) NOT NULL DEFAULT '',
  `NOM_PRO` varchar(50) DEFAULT NULL,
  `V_COSTO` decimal(12,4) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `NUM_FAC` varchar(10) DEFAULT NULL,
  `TOT_TIN` decimal(12,2) DEFAULT NULL,
  `TOT_TON` decimal(12,2) DEFAULT NULL,
  `TOT_RES` decimal(12,2) DEFAULT NULL,
  `INDICE` decimal(3,0) NOT NULL,
  `FEC_FAC` date DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_MAR`,`COD_PRO`,`INDICE`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TR0001`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TR0002`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TR0003`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TR0010`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TR0011`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TR0012`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TS0001`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TS0005`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VC010101`
--

DROP TABLE IF EXISTS `VC010101`;
CREATE TABLE IF NOT EXISTS `VC010101` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) NOT NULL DEFAULT '',
  `TIP_NOF` varchar(2) NOT NULL DEFAULT '',
  `NUM_NOF` varchar(10) NOT NULL DEFAULT '',
  `FEC_VTA` date DEFAULT NULL,
  `TIP_EMI` varchar(1) DEFAULT 'F',
  `NUM_COT` varchar(10) DEFAULT NULL,
  `FEC_COT` date DEFAULT NULL,
  `ATIENDE` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `FAC_CLI` varchar(10) DEFAULT NULL,
  `CED_RUC` varchar(15) DEFAULT NULL,
  `NOM_CLI` varchar(95) DEFAULT NULL,
  `DIR_CLI` varchar(90) DEFAULT NULL,
  `COD_CIU` varchar(3) DEFAULT NULL,
  `COD_PAI` varchar(3) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `PRO_VTA` decimal(12,2) DEFAULT '0.00',
  `VAL_BRU` decimal(14,2) DEFAULT NULL,
  `POR_DES` decimal(7,4) DEFAULT NULL,
  `VAL_DES` decimal(14,2) DEFAULT NULL,
  `VAL_FLE` decimal(14,2) DEFAULT NULL,
  `BAS_IVA` decimal(14,2) DEFAULT NULL,
  `BAS_IV0` decimal(14,2) DEFAULT NULL,
  `POR_IVA` decimal(5,2) DEFAULT NULL,
  `VAL_IVA` decimal(14,2) DEFAULT NULL,
  `VAL_NET` decimal(14,2) DEFAULT NULL,
  `POR_R_F` decimal(5,2) DEFAULT NULL,
  `VAL_R_F` decimal(14,2) DEFAULT NULL,
  `POR_R_I` decimal(5,2) DEFAULT NULL,
  `VAL_R_I` decimal(14,2) DEFAULT NULL,
  `VAL_COS` decimal(14,2) DEFAULT NULL,
  `T_ITEMS` decimal(5,0) DEFAULT NULL,
  `T_P_NOF` decimal(8,2) DEFAULT NULL,
  `FOR_PAG` varchar(2) DEFAULT NULL,
  `FOR_PAG_SRI` varchar(2) DEFAULT NULL,
  `PAG_PLZ` int DEFAULT '0',
  `PAG_TMP` varchar(10) DEFAULT NULL,
  `CON_PAG` varchar(20) DEFAULT NULL,
  `DOFERTA` varchar(20) DEFAULT NULL,
  `COD_DIV` varchar(2) DEFAULT NULL,
  `V_COTIZ` decimal(10,2) DEFAULT NULL,
  `LIN_N01` varchar(100) DEFAULT NULL,
  `GUI_REM` varchar(10) DEFAULT NULL,
  `ORD_PED` varchar(30) DEFAULT NULL,
  `FEC_ENT` timestamp NULL DEFAULT NULL,
  `FEC_DES` timestamp NULL DEFAULT NULL,
  `EST_ENT` varchar(1) DEFAULT NULL,
  `IND_UPD` varchar(1) DEFAULT NULL,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `POR_REN` decimal(5,2) DEFAULT NULL,
  `COM_TER` decimal(12,2) DEFAULT NULL,
  `FEC_VEN` date DEFAULT NULL,
  `NOT_ENT` varchar(100) DEFAULT NULL,
  `NUM_ANU` varchar(10) DEFAULT NULL,
  `TIP_ENT` varchar(2) DEFAULT NULL,
  `LUG_DES` varchar(60) DEFAULT NULL,
  `LIM_CRE` decimal(12,2) DEFAULT NULL,
  `SAL_CAR` decimal(12,2) DEFAULT NULL,
  `DIA_VEN` decimal(12,2) DEFAULT NULL,
  `NOM_CTO` varchar(100) DEFAULT NULL,
  `BAS_IV0_I` decimal(12,2) DEFAULT '0.00',
  `BAS_IVA_I` decimal(12,2) DEFAULT '0.00',
  `ENV_DOC` bigint DEFAULT '0',
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `NOTITA` text,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_CAJ`,`TIP_NOF`,`NUM_NOF`),
  KEY `VC01_COD_CLI_FK` (`COD_CLI`),
  KEY `VC01_ATIENDE_FK` (`ATIENDE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VD010101`
--

DROP TABLE IF EXISTS `VD010101`;
CREATE TABLE IF NOT EXISTS `VD010101` (
  `COD_PTO` varchar(3) NOT NULL DEFAULT '',
  `COD_CAJ` varchar(3) NOT NULL DEFAULT '',
  `TIP_NOF` varchar(2) NOT NULL DEFAULT '',
  `NUM_NOF` varchar(10) NOT NULL DEFAULT '',
  `FEC_VTA` date DEFAULT NULL,
  `NUM_COT` varchar(10) DEFAULT NULL,
  `ATIENDE` varchar(10) DEFAULT NULL,
  `COD_CLI` varchar(10) DEFAULT NULL,
  `COD_BOD` varchar(2) DEFAULT NULL,
  `COD_ART` varchar(20) NOT NULL DEFAULT '',
  `IND_DES` varchar(1) DEFAULT NULL,
  `NOM_ART` varchar(60) DEFAULT NULL,
  `EXI_TOT` decimal(10,2) DEFAULT NULL,
  `EXI_COM` decimal(10,2) DEFAULT NULL,
  `CAN_DES` decimal(10,2) DEFAULT NULL,
  `CAN_DEV` decimal(10,2) DEFAULT NULL,
  `P_COSTO` decimal(14,4) DEFAULT NULL,
  `T_COSTO` decimal(14,4) DEFAULT NULL,
  `IND_PRE` varchar(1) DEFAULT NULL,
  `P_LISTA` decimal(14,4) DEFAULT NULL,
  `P_VENTA` decimal(14,4) DEFAULT NULL,
  `T_VENTA` decimal(14,4) DEFAULT NULL,
  `POR_DES` decimal(7,4) DEFAULT NULL,
  `VAL_DES` decimal(14,4) DEFAULT NULL,
  `I_M_IVA` decimal(1,0) DEFAULT NULL,
  `VAL_IVA` decimal(12,4) DEFAULT NULL,
  `P_NETOS` decimal(12,4) DEFAULT NULL,
  `T_NETOS` decimal(12,4) DEFAULT NULL,
  `COD_LIN` varchar(3) DEFAULT NULL,
  `COD_TIP` varchar(3) DEFAULT NULL,
  `COD_MAR` varchar(3) DEFAULT NULL,
  `IND_EST` varchar(1) DEFAULT NULL,
  `REF_PRO` text,
  `FEC_SIS` date DEFAULT NULL,
  `HOR_SIS` varchar(10) DEFAULT NULL,
  `USUARIO` varchar(250) DEFAULT NULL,
  `EQUIPO` varchar(15) DEFAULT NULL,
  `P_REAL` decimal(12,4) DEFAULT NULL,
  `SECUENCIA` int UNSIGNED DEFAULT NULL,
  `EST_LOG` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => activo / 0 => inactivo
  `EST_DEL` VARCHAR(1) NOT NULL DEFAULT '1', -- estado 1 => operativo / 0 => eliminado
  PRIMARY KEY (`COD_PTO`,`COD_CAJ`,`TIP_NOF`,`NUM_NOF`,`COD_ART`) USING BTREE,
  KEY `VD01_COD_CLI_FK` (`COD_CLI`),
  KEY `VD01_COD_ART_FK` (`COD_ART`),
  KEY `VD01_ATIENDE_FK` (`ATIENDE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VDETALLE`
--



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VENSEMA`
--


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_item`
--
CREATE TABLE IF NOT EXISTS `TIPO_ITEM` (
  `TITE_ID` BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `TITE_NOMBRE` VARCHAR(200) NULL,
  `TITE_USUARIO_INGRESO` BIGINT(20) NULL,
  `TITE_USUARIO_MODIFICA` BIGINT(20) NULL,
  `TITE_ESTADO` VARCHAR(1) NULL,
  `TITE_EQUIPO` VARCHAR(15) NULL,
  `TITE_FECHA_CREACION` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `TITE_FECHA_MODIFICACION` TIMESTAMP NULL,
  `TITE_ESTADO_LOGICO` VARCHAR(1) NULL
);
