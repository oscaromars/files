--
-- Base de datos: `db_edoc`
--
DROP SCHEMA IF EXISTS `db_edoc`;
CREATE SCHEMA IF NOT EXISTS `db_edoc` DEFAULT CHARACTER SET utf8;
USE `db_edoc` ;

-- GRANT ALL PRIVILEGES ON `db_edoc`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- --------------------------------------------------------

--
-- Table structure for table `persona`
--
DROP TABLE IF EXISTS `persona`;
CREATE TABLE  `persona` (
  `per_id` bigint(20) not null auto_increment,
  `per_tip_cruc` bigint(3) default null,
  `per_ced_ruc` varchar(15) default null,
  `per_nombre` varchar(100) default null,
  `per_apellido` varchar(100) default null,
  `per_genero` varchar(1) default null,
  `per_fec_nacimiento` date default null,
  `per_est_log` varchar(1) default null,
  `per_fec_cre` timestamp null default null,
  `per_fec_mod` timestamp null default null,
  primary key (`per_id`)
) engine=innodb auto_increment=0 default charset=latin1;
--
-- Table structure for table `usuario`
--
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE  `usuario` (
  `usu_id` bigint(20) not null auto_increment,
  `per_id` bigint(20) not null,
  `usu_nombre` varchar(100) default null,
  `usu_password` varchar(50) default null,
  `usu_alias` varchar(60) default null,
  `usu_correo` varchar(100) default null,
  `usu_est_log` varchar(1) default null,
  `usu_fec_cre` timestamp null default current_timestamp,
  `usu_fec_mod` timestamp null default null,
  primary key (`usu_id`),
  key `fk_usuario_persona` (`per_id`),
  constraint `fk_usuario_persona` foreign key (`per_id`) references `persona` (`per_id`) on delete no action on update no action
) engine=innodb auto_increment=0 default charset=latin1;



--
-- Estructura de tabla para la tabla `NubeLote`
--

DROP TABLE IF EXISTS `NubeLote`;
CREATE TABLE `NubeLote`
(
  `Id` int(10) NOT NULL,
  `IdLote` varchar(50) NOT NULL PRIMARY KEY,
  `TipoLote` varchar(50) DEFAULT NULL,
  `FechaEmision` datetime DEFAULT NULL,
  `UsuarioCreador` varchar(50) DEFAULT NULL,
  `ClaveAcceso` varchar(50) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeMensajeError`
--

DROP TABLE IF EXISTS `NubeMensajeError`;
CREATE TABLE `NubeMensajeError`
(
  `Id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `IdFactura` bigint(20) DEFAULT NULL,
  `IdRetencion` bigint(20) DEFAULT NULL,
  `IdNotaCredito` bigint(20) DEFAULT NULL,
  `IdNotaDebito` bigint(20) DEFAULT NULL,
  `IdGuiaRemision` bigint(20) DEFAULT NULL,
  `Identificador` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `TipoMensaje` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Mensaje` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `InformacionAdicional` blob,
  `FechaError` timestamp NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeFactura`
--

DROP TABLE IF EXISTS `NubeFactura`;
CREATE TABLE `NubeFactura`
(
  `IdFactura` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `FechaAutorizacion` datetime DEFAULT NULL,
  `Ambiente` int(1) DEFAULT NULL,
  `TipoEmision` int(1) DEFAULT NULL,
  `RazonSocial` varchar(300) DEFAULT NULL,
  `NombreComercial` varchar(300) DEFAULT NULL,
  `Ruc` varchar(20) DEFAULT NULL,
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `CodigoDocumento` varchar(2) DEFAULT NULL,
  `Establecimiento` varchar(3) DEFAULT NULL,
  `PuntoEmision` varchar(3) DEFAULT NULL,
  `Secuencial` varchar(15) DEFAULT NULL,
  `DireccionMatriz` varchar(300) DEFAULT NULL,
  `FechaEmision` datetime DEFAULT NULL,
  `DireccionEstablecimiento` varchar(300) DEFAULT NULL,
  `ContribuyenteEspecial` varchar(10) DEFAULT NULL,
  `ObligadoContabilidad` varchar(2) DEFAULT NULL,
  `TipoIdentificacionComprador` varchar(2) DEFAULT NULL,
  `GuiaRemision` varchar(20) DEFAULT NULL,
  `RazonSocialComprador` varchar(300) DEFAULT NULL,
  `IdentificacionComprador` varchar(13) DEFAULT NULL,
  `TotalSinImpuesto` decimal(14,4) DEFAULT NULL,
  `TotalDescuento` decimal(14,4) DEFAULT NULL,
  `Propina` decimal(14,4) DEFAULT NULL,
  `ImporteTotal` decimal(14,4) DEFAULT NULL,
  `Moneda` varchar(15) DEFAULT NULL,
  `UsuarioCreador` varchar(100) DEFAULT NULL,
  `EmailResponsable` varchar(100) DEFAULT NULL,
  `EstadoDocumento` varchar(25) DEFAULT NULL,
  `DescripcionError` blob,
  `CodigoError` varchar(10) DEFAULT NULL,
  `DirectorioDocumento` varchar(100) DEFAULT NULL,
  `NombreDocumento` varchar(100) DEFAULT NULL,
  `GeneradoXls` int(4) DEFAULT NULL,
  `SecuencialERP` varchar(10) DEFAULT NULL,
  `CodigoTransaccionERP` varchar(3) DEFAULT NULL,
  `Estado` int(10) DEFAULT NULL,
  `EstadoEnv` int(10) DEFAULT '2',
  `FechaCarga` datetime DEFAULT NULL,
  `FechaAnula` datetime DEFAULT NULL,
  `IdLote` bigint(20) DEFAULT NULL,
  `IdRad` bigint(20) DEFAULT '0',
  `USU_ID` bigint(20) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeGuiaRemision`
--

DROP TABLE IF EXISTS `NubeGuiaRemision`;
CREATE TABLE `NubeGuiaRemision`
(
  `IdGuiaRemision` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `FechaAutorizacion` datetime DEFAULT NULL,
  `Ambiente` int(1) DEFAULT NULL,
  `TipoEmision` int(1) DEFAULT NULL,
  `RazonSocial` varchar(300) DEFAULT NULL,
  `NombreComercial` varchar(300) DEFAULT NULL,
  `Ruc` varchar(20) DEFAULT NULL,
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `CodigoDocumento` varchar(2) DEFAULT NULL,
  `Establecimiento` varchar(3) DEFAULT NULL,
  `PuntoEmision` varchar(3) DEFAULT NULL,
  `Secuencial` varchar(15) DEFAULT NULL,
  `DireccionMatriz` varchar(300) DEFAULT NULL,
  `DireccionEstablecimiento` varchar(300) DEFAULT NULL,
  `DireccionPartida` varchar(300) DEFAULT NULL,
  `RazonSocialTransportista` varchar(300) DEFAULT NULL,
  `TipoIdentificacionTransportista` varchar(2) DEFAULT NULL,
  `IdentificacionTransportista` varchar(20) DEFAULT NULL,
  `Rise` varchar(40) DEFAULT NULL,
  `ObligadoContabilidad` varchar(2) DEFAULT NULL,
  `ContribuyenteEspecial` varchar(10) DEFAULT NULL,
  `FechaInicioTransporte` datetime DEFAULT NULL,
  `FechaFinTransporte` datetime DEFAULT NULL,
  `Placa` varchar(20) DEFAULT NULL,
  `UsuarioCreador` varchar(50) DEFAULT NULL,
  `EmailResponsable` varchar(100) DEFAULT NULL,
  `EstadoDocumento` varchar(25) DEFAULT NULL,
  `DescripcionError` blob,
  `CodigoError` varchar(10) DEFAULT NULL,
  `DirectorioDocumento` varchar(100) DEFAULT NULL,
  `NombreDocumento` varchar(100) DEFAULT NULL,
  `GeneradoXls` tinyint(4) DEFAULT NULL,
  `CodigoTransaccionERP` varchar(3) DEFAULT NULL,
  `SecuencialERP` varchar(10) DEFAULT NULL,
  `Estado` int(10) DEFAULT NULL,
  `EstadoEnv` int(10) DEFAULT '2',
  `IdLote` varchar(50) DEFAULT NULL,
  `IdRad` bigint(20) DEFAULT '0',
  `FechaEmisionErp` datetime DEFAULT NULL,
  `FechaCarga` datetime DEFAULT NULL,
  `FechaAnula` datetime DEFAULT NULL,
  `USU_ID` bigint(20) DEFAULT NULL
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeNotaCredito`
--

DROP TABLE IF EXISTS `NubeNotaCredito`;
CREATE TABLE `NubeNotaCredito`
(
  `IdNotaCredito` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `FechaAutorizacion` datetime DEFAULT NULL,
  `Ambiente` int(10) DEFAULT NULL,
  `TipoEmision` int(10) DEFAULT NULL,
  `RazonSocial` varchar(300) DEFAULT NULL,
  `NombreComercial` varchar(300) DEFAULT NULL,
  `Ruc` varchar(13) DEFAULT NULL,
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `CodigoDocumento` varchar(2) DEFAULT NULL,
  `Establecimiento` varchar(3) DEFAULT NULL,
  `PuntoEmision` varchar(3) DEFAULT NULL,
  `Secuencial` varchar(15) DEFAULT NULL,
  `DireccionMatriz` varchar(300) DEFAULT NULL,
  `FechaEmision` datetime DEFAULT NULL,
  `DireccionEstablecimiento` varchar(300) DEFAULT NULL,
  `ContribuyenteEspecial` varchar(10) DEFAULT NULL,
  `ObligadoContabilidad` varchar(2) DEFAULT NULL,
  `TipoIdentificacionComprador` varchar(2) DEFAULT NULL,
  `RazonSocialComprador` varchar(300) DEFAULT NULL,
  `IdentificacionComprador` varchar(13) DEFAULT NULL,
  `Rise` varchar(40) DEFAULT NULL,
  `CodDocModificado` varchar(2) DEFAULT NULL,
  `NumDocModificado` varchar(20) DEFAULT NULL,
  `FechaEmisionDocModificado` datetime DEFAULT NULL,
  `TotalSinImpuesto` decimal(19,4) DEFAULT NULL,
  `ValorModificacion` decimal(19,4) DEFAULT NULL,
  `MotivoModificacion` varchar(300) DEFAULT NULL,
  `Moneda` varchar(10) DEFAULT NULL,
  `UsuarioCreador` varchar(300) DEFAULT NULL,
  `EmailResponsable` varchar(100) DEFAULT NULL,
  `EstadoDocumento` varchar(25) DEFAULT NULL,
  `DescripcionError` blob,
  `CodigoError` varchar(10) DEFAULT NULL,
  `DirectorioDocumento` varchar(100) DEFAULT NULL,
  `NombreDocumento` varchar(100) DEFAULT NULL,
  `GeneradoXls` tinyint(4) DEFAULT NULL,
  `SecuencialERP` varchar(30) DEFAULT NULL,
  `Estado` int(10) DEFAULT NULL,
  `EstadoEnv` int(10) DEFAULT '2',
  `IdLote` varchar(50) DEFAULT NULL,
  `FechaCarga` datetime DEFAULT NULL,
  `FechaAnula` datetime DEFAULT NULL,
  `CodigoTransaccionERP` varchar(20) DEFAULT NULL,
  `USU_ID` bigint(20) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeNotaDebito`
--

DROP TABLE IF EXISTS `NubeNotaDebito`;
CREATE TABLE `NubeNotaDebito`
(
  `IdNotaDebito` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `FechaAutorizacion` datetime DEFAULT NULL,
  `Ambiente` int(10) DEFAULT NULL,
  `TipoEmision` int(10) DEFAULT NULL,
  `RazonSocial` varchar(300) DEFAULT NULL,
  `NombreComercial` varchar(300) DEFAULT NULL,
  `Ruc` varchar(13) DEFAULT NULL,
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `CodigoDocumento` varchar(2) DEFAULT NULL,
  `Establecimiento` varchar(3) DEFAULT NULL,
  `PuntoEmision` varchar(3) DEFAULT NULL,
  `Secuencial` varchar(15) DEFAULT NULL,
  `DireccionMatriz` varchar(300) DEFAULT NULL,
  `FechaEmision` datetime DEFAULT NULL,
  `DireccionEstablecimiento` varchar(300) DEFAULT NULL,
  `ContribuyenteEspecial` varchar(10) DEFAULT NULL,
  `ObligadoContabilidad` varchar(2) DEFAULT NULL,
  `TipoIdentificacionComprador` varchar(2) DEFAULT NULL,
  `RazonSocialComprador` varchar(300) DEFAULT NULL,
  `IdentificacionComprador` varchar(13) DEFAULT NULL,
  `Rise` varchar(40) DEFAULT NULL,
  `CodDocModificado` varchar(2) DEFAULT NULL,
  `NumDocModificado` varchar(20) DEFAULT NULL,
  `FechaEmisionDocModificado` datetime DEFAULT NULL,
  `TotalSinImpuesto` decimal(19,4) DEFAULT NULL,
  `ValorTotal` decimal(19,4) DEFAULT NULL,
  `UsuarioCreador` varchar(300) DEFAULT NULL,
  `EmailResponsable` varchar(100) DEFAULT NULL,
  `EstadoDocumento` varchar(25) DEFAULT NULL,
  `DescripcionError` varchar(300) DEFAULT NULL,
  `CodigoError` varchar(10) DEFAULT NULL,
  `DirectorioDocumento` varchar(100) DEFAULT NULL,
  `NombreDocumento` varchar(100) DEFAULT NULL,
  `GeneradoXls` tinyint(4) DEFAULT NULL,
  `SecuencialERP` varchar(30) DEFAULT NULL,
  `Estado` int(10) DEFAULT NULL,
  `EstadoEnv` int(10) DEFAULT '2',
  `FechaCarga` datetime DEFAULT NULL,
  `FechaAnula` datetime DEFAULT NULL,
  `IdLote` varchar(50) DEFAULT NULL,
  `USU_ID` bigint(20) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeRetencion`
--

DROP TABLE IF EXISTS `NubeRetencion`;
CREATE TABLE `NubeRetencion`
(
  `IdRetencion` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `AutorizacionSRI` varchar(50) DEFAULT NULL,
  `FechaAutorizacion` datetime DEFAULT NULL,
  `Ambiente` int(10) DEFAULT NULL,
  `TipoEmision` int(10) DEFAULT NULL,
  `RazonSocial` varchar(300) DEFAULT NULL,
  `NombreComercial` varchar(300) DEFAULT NULL,
  `Ruc` varchar(13) DEFAULT NULL,
  `ClaveAcceso` varchar(50) DEFAULT NULL,
  `CodigoDocumento` varchar(2) DEFAULT NULL,
  `PuntoEmision` varchar(3) DEFAULT NULL,
  `Establecimiento` varchar(3) DEFAULT NULL,
  `Secuencial` varchar(15) DEFAULT NULL,
  `DireccionMatriz` varchar(300) DEFAULT NULL,
  `FechaEmision` datetime DEFAULT NULL,
  `DireccionEstablecimiento` varchar(300) DEFAULT NULL,
  `ContribuyenteEspecial` varchar(10) DEFAULT NULL,
  `ObligadoContabilidad` varchar(2) DEFAULT NULL,
  `TipoIdentificacionSujetoRetenido` varchar(2) DEFAULT NULL,
  `IdentificacionSujetoRetenido` varchar(20) DEFAULT NULL,
  `RazonSocialSujetoRetenido` varchar(300) DEFAULT NULL,
  `PeriodoFiscal` varchar(10) DEFAULT NULL,
  `TotalRetencion` decimal(19,4) DEFAULT NULL,
  `UsuarioCreador` varchar(50) DEFAULT NULL,
  `EmailResponsable` varchar(100) DEFAULT NULL,
  `EstadoDocumento` varchar(25) DEFAULT NULL,
  `DescripcionError` blob,
  `CodigoError` varchar(10) DEFAULT NULL,
  `DirectorioDocumento` varchar(100) DEFAULT NULL,
  `NombreDocumento` varchar(100) DEFAULT NULL,
  `GeneradoXls` tinyint(4) DEFAULT NULL,
  `SecuencialERP` varchar(10) DEFAULT NULL,
  `CodigoTransaccionERP` varchar(20) DEFAULT NULL,
  `DocSustentoERP` varchar(20) DEFAULT NULL,
  `Estado` int(10) DEFAULT NULL,
  `EstadoEnv` int(10) DEFAULT '2',
  `FechaCarga` datetime DEFAULT NULL,
  `FechaAnula` datetime DEFAULT NULL,
  `IdLote` varchar(50) DEFAULT NULL,
  `IdRad` bigint(20) DEFAULT '0',
  `USU_ID` bigint(20) DEFAULT NULL
);

DROP TABLE IF EXISTS `empresa`;
CREATE TABLE `empresa` (
  `emp_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_ruc` varchar(15) DEFAULT NULL,
  `emp_razonsocial` varchar(300) DEFAULT NULL,
  `emp_nom_comercial` varchar(300) DEFAULT NULL,
  `emp_ambiente` varchar(1) DEFAULT NULL,
  `emp_tipo_emision` varchar(1) DEFAULT NULL,
  `emp_direccion_matriz` varchar(300) DEFAULT NULL,
  `emp_obliga_contabilidad` varchar(2) DEFAULT NULL,
  `emp_contri_especial` varchar(5) DEFAULT NULL,
  `emp_telefono` varchar(20) DEFAULT NULL,
  `emp_fax` varchar(20) DEFAULT NULL,
  `emp_email` varchar(45) DEFAULT NULL,
  `emp_email_digital` varchar(60) DEFAULT NULL,
  `emp_email_conta` varchar(60) DEFAULT NULL,
  `emp_moneda` varchar(10) DEFAULT NULL,
  `emp_website` varchar(45) DEFAULT NULL,
  `emp_logo` varchar(100) DEFAULT NULL,
  `usuario` varchar(60) DEFAULT NULL,
  `emp_est_log` varchar(1) DEFAULT NULL,
  `emp_fec_cre` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `emp_fec_mod` timestamp NULL DEFAULT NULL
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysdiagrams`
--

DROP TABLE IF EXISTS `sysdiagrams`;
CREATE TABLE `sysdiagrams`
(
  `diagram_id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `principal_id` int(10) NOT NULL,
  `version` int(10) DEFAULT NULL,
  `definition` varbinary(100) DEFAULT NULL,
  UNIQUE (`principal_id`,`name`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VSFormaPago`
--

DROP TABLE IF EXISTS `VSFormaPago`;
CREATE TABLE `VSFormaPago`
(
  `IdForma` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `FormaPago` varchar(100) DEFAULT NULL,
  `Codigo` varchar(2) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaFin` date DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VSImpuesto`
--

DROP TABLE IF EXISTS `VSImpuesto`;
CREATE TABLE `VSImpuesto`
(
  `Idimpuesto` int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Impuesto` varchar(80) DEFAULT NULL,
  `Codigo` varchar(2) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VSValidacion`
--

DROP TABLE IF EXISTS `VSValidacion`;
CREATE TABLE `VSValidacion`
(
  `Idvalidacion` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Validacion` text,
  `Codigo` varchar(2) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDetalleNotaCredito`
--

DROP TABLE IF EXISTS `NubeDetalleNotaCredito`;
CREATE TABLE `NubeDetalleNotaCredito`
(
  `IdDetalleNotaCredito` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `CodigoPrincipal` varchar(25) DEFAULT NULL,
  `CodigoAuxiliar` varchar(25) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `Cantidad` int(10) DEFAULT NULL,
  `PrecioUnitario` decimal(19,4) DEFAULT NULL,
  `Descuento` decimal(19,4) DEFAULT NULL,
  `PrecioTotalSinImpuesto` decimal(19,4) DEFAULT NULL,
  `IdNotaCredito` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdNotaCredito`) REFERENCES `NubeNotaCredito`(`IdNotaCredito`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDetalleFactura`
--

DROP TABLE IF EXISTS `NubeDetalleFactura`;
CREATE TABLE `NubeDetalleFactura`
(
  `IdDetalleFactura` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `CodigoPrincipal` varchar(25) DEFAULT NULL,
  `CodigoAuxiliar` varchar(25) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `Cantidad` decimal(19,4) DEFAULT NULL,
  `PrecioUnitario` decimal(19,4) DEFAULT NULL,
  `Descuento` decimal(19,4) DEFAULT NULL,
  `PrecioTotalSinImpuesto` decimal(19,4) DEFAULT NULL,
  `IdFactura` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdFactura`) REFERENCES `NubeFactura`(`IdFactura`)
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeGuiaRemisionDestinatario`
--

DROP TABLE IF EXISTS `NubeGuiaRemisionDestinatario`;
CREATE TABLE `NubeGuiaRemisionDestinatario`
(
  `IdGuiaRemisionDestinatario` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `IdentificacionDestinatario` varchar(13) DEFAULT NULL,
  `RazonSocialDestinatario` varchar(300) DEFAULT NULL,
  `DirDestinatario` varchar(300) DEFAULT NULL,
  `MotivoTraslado` varchar(300) DEFAULT NULL,
  `DocAduaneroUnico` varchar(20) DEFAULT NULL,
  `CodEstabDestino` varchar(3) DEFAULT NULL,
  `Ruta` varchar(300) DEFAULT NULL,
  `CodDocSustento` varchar(2) DEFAULT NULL,
  `NumDocSustento` varchar(20) DEFAULT NULL,
  `NumAutDocSustento` varchar(50) DEFAULT NULL,
  `FechaEmisionDocSustento` date DEFAULT NULL,
  `IdGuiaRemision` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdGuiaRemision`) REFERENCES `NubeGuiaRemision`(`IdGuiaRemision`)
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeGuiaRemisionDetalle`
--

DROP TABLE IF EXISTS `NubeGuiaRemisionDetalle`;
CREATE TABLE `NubeGuiaRemisionDetalle`
(
  `IdGuiaRemisionDetalle` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `CodigoInterno` varchar(25) DEFAULT NULL,
  `CodigoAdicional` varchar(25) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `Cantidad` decimal(19,4) DEFAULT NULL,
  `IdGuiaRemisionDestinatario` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdGuiaRemisionDestinatario`) REFERENCES `NubeGuiaRemisionDestinatario`(`IdGuiaRemisionDestinatario`)
);


--
-- Definition of table `NubeDatoAdicionalDetalleFactura`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalDetalleFactura`;
CREATE TABLE `NubeDatoAdicionalDetalleFactura` (
  `IdDatoAdicionalDetalleFactura` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdDetalleFactura` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdDetalleFactura`) REFERENCES `NubeDetalleFactura` (`IdDetalleFactura`)
);

--
-- Definition of table `NubeDatoAdicionalDetalleNotaCredito`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalDetalleNotaCredito`;
CREATE TABLE `NubeDatoAdicionalDetalleNotaCredito`
(
  `IdDatoAdicionalDetalleNotaCredito` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdDetalleNotaCredito` bigint(20) DEFAULT NULL,
  FOREIGN KEY(`IdDetalleNotaCredito`) REFERENCES `NubeDetalleNotaCredito`(`IdDetalleNotaCredito`)
);

--
-- Definition of table `NubeDatoAdicionalFactura`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalFactura`;
CREATE TABLE `NubeDatoAdicionalFactura`
(
  `IdDatoAdicionalFactura` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdFactura` bigint(20) DEFAULT NULL,
  FOREIGN KEY(`IdFactura`) REFERENCES `NubeFactura`(`IdFactura`) 
);

--
-- Definition of table `NubeDatoAdicionalGuiaRemision`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalGuiaRemision`;
CREATE TABLE `NubeDatoAdicionalGuiaRemision`
(
  `IdDatoAdicionalGuiaRemision` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdGuiaRemision` bigint(20) DEFAULT NULL,
  FOREIGN KEY(`IdGuiaRemision`) REFERENCES `NubeGuiaRemision`(`IdGuiaRemision`) 
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDatoAdicionalGuiaRemisionDetalle`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalGuiaRemisionDetalle`;
CREATE TABLE `NubeDatoAdicionalGuiaRemisionDetalle`
(
  `IdDatoAdicionalGuiaRemisionDetalle` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdGuiaRemisionDetalle` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdGuiaRemisionDetalle`) REFERENCES `NubeGuiaRemisionDetalle`(`IdGuiaRemisionDetalle`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDatoAdicionalNotaCredito`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalNotaCredito`;
CREATE TABLE `NubeDatoAdicionalNotaCredito`
(
  `IdDatoAdicionalNotaCredito` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdNotaCredito` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdNotaCredito`) REFERENCES `NubeNotaCredito`(`IdNotaCredito`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDatoAdicionalNotaDebito`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalNotaDebito`;
CREATE TABLE `NubeDatoAdicionalNotaDebito`
(
  `IdDatoAdicionalNotaDebito` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdNotaDebito` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdNotaDebito`) REFERENCES `NubeNotaDebito`(`IdNotaDebito`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDatoAdicionalRetencion`
--

DROP TABLE IF EXISTS `NubeDatoAdicionalRetencion`;
CREATE TABLE `NubeDatoAdicionalRetencion`
(
  `IdDatoAdicionalRetencion` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  `Nombre` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `IdRetencion` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdRetencion`) REFERENCES `NubeRetencion`(`IdRetencion`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDetalleFacturaImpuesto`
--

DROP TABLE IF EXISTS `NubeDetalleFacturaImpuesto`;
CREATE TABLE `NubeDetalleFacturaImpuesto`
(
  `IdDetalleFacturaImpuesto` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Codigo` int(10) DEFAULT NULL,
  `CodigoPorcentaje` int(10) DEFAULT NULL,
  `BaseImponible` decimal(19,4) DEFAULT NULL,
  `Tarifa` decimal(19,4) DEFAULT NULL,
  `Valor` decimal(19,4) DEFAULT NULL,
  `IdDetalleFactura` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdDetalleFactura`) REFERENCES `NubeDetalleFactura`(`IdDetalleFactura`)
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDetalleNotaCreditoImpuesto`
--

DROP TABLE IF EXISTS `NubeDetalleNotaCreditoImpuesto`;
CREATE TABLE `NubeDetalleNotaCreditoImpuesto`
(
  `IdDetalleNotaCreditoImpuesto` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Codigo` int(10) DEFAULT NULL,
  `CodigoPorcentaje` int(10) DEFAULT NULL,
  `BaseImponible` decimal(19,4) DEFAULT NULL,
  `Tarifa` decimal(19,4) DEFAULT NULL,
  `Valor` decimal(19,4) DEFAULT NULL,
  `IdDetalleNotaCredito` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdDetalleNotaCredito`) REFERENCES `NubeDetalleNotaCredito`(`IdDetalleNotaCredito`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeDetalleRetencion`
--

DROP TABLE IF EXISTS `NubeDetalleRetencion`;
CREATE TABLE `NubeDetalleRetencion`
(
  `IdDetalleRetencion` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Codigo` int(10) DEFAULT NULL,
  `CodigoRetencion` varchar(5) DEFAULT NULL,
  `BaseImponible` decimal(19,4) DEFAULT NULL,
  `PorcentajeRetener` decimal(19,4) DEFAULT NULL,
  `ValorRetenido` decimal(19,4) DEFAULT NULL,
  `CodDocRetener` varchar(2) DEFAULT NULL,
  `NumDocRetener` varchar(20) DEFAULT NULL,
  `FechaEmisionDocRetener` datetime DEFAULT NULL,
  `IdRetencion` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdRetencion`) REFERENCES `NubeRetencion`(`IdRetencion`)
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeFacturaFormaPago`
--

DROP TABLE IF EXISTS `NubeFacturaFormaPago`;
CREATE TABLE `NubeFacturaFormaPago`
(
  `IdFormaPagoFact` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `IdForma` bigint(20) NOT NULL,
  `IdFactura` bigint(20) NOT NULL,
  `FormaPago` varchar(2) DEFAULT NULL,
  `Total` decimal(14,4) DEFAULT NULL,
  `Plazo` int(5) DEFAULT NULL,
  `UnidadTiempo` varchar(10) DEFAULT NULL,
  FOREIGN KEY (`IdFactura`) REFERENCES `NubeFactura`(`IdFactura`),
  FOREIGN KEY (`IdForma`) REFERENCES `VSFormaPago`(`IdForma`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeFacturaImpuesto`
--

DROP TABLE IF EXISTS `NubeFacturaImpuesto`;
CREATE TABLE `NubeFacturaImpuesto`
(
  `IdFacturaImpuesto` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Codigo` int(10) DEFAULT NULL,
  `CodigoPorcentaje` int(10) DEFAULT NULL,
  `BaseImponible` decimal(19,4) DEFAULT NULL,
  `Tarifa` decimal(19,4) DEFAULT NULL,
  `Valor` decimal(19,4) DEFAULT NULL,
  `IdFactura` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdFactura`) REFERENCES `NubeFactura`(`IdFactura`)
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeNotaCreditoImpuesto`
--

DROP TABLE IF EXISTS `NubeNotaCreditoImpuesto`;
CREATE TABLE `NubeNotaCreditoImpuesto`
(
  `IdNotaCreditoImpuesto` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Codigo` int(10) DEFAULT NULL,
  `CodigoPorcentaje` int(10) DEFAULT NULL,
  `BaseImponible` decimal(19,4) DEFAULT NULL,
  `Tarifa` decimal(19,4) DEFAULT NULL,
  `Valor` decimal(19,4) DEFAULT NULL,
  `IdNotaCredito` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdNotaCredito`) REFERENCES `NubeNotaCredito`(`IdNotaCredito`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeNotaDebitoImpuesto`
--

DROP TABLE IF EXISTS `NubeNotaDebitoImpuesto`;
CREATE TABLE `NubeNotaDebitoImpuesto`
(
  `IdNotaDebitoImpuesto` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Codigo` int(10) DEFAULT NULL,
  `CodigoPorcentaje` int(10) DEFAULT NULL,
  `BaseImponible` decimal(19,4) DEFAULT NULL,
  `Tarifa` decimal(19,4) DEFAULT NULL,
  `Valor` decimal(19,4) DEFAULT NULL,
  `IdNotaDebito` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdNotaDebito`) REFERENCES `NubeNotaDebito`(`IdNotaDebito`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NubeNotaDebitoMotivos`
--

DROP TABLE IF EXISTS `NubeNotaDebitoMotivos`;
CREATE TABLE `NubeNotaDebitoMotivos`
(
  `IdNotaDebitoMotivo` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Razon` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `Valor` decimal(19,4) DEFAULT NULL,
  `IdNotaDebito` bigint(20) DEFAULT NULL,
  FOREIGN KEY (`IdNotaDebito`) REFERENCES `NubeNotaDebito`(`IdNotaDebito`)
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VSImpuestoRetencion`
--

DROP TABLE IF EXISTS `VSImpuestoRetencion`;
CREATE TABLE `VSImpuestoRetencion`
(
  `Idimpreten` int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Idimpuesto` int(20) NOT NULL,
  `Impuesto` varchar(60) DEFAULT NULL,
  `Codigo` varchar(2) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  FOREIGN KEY (`Idimpuesto`) REFERENCES `VSImpuesto`(`Idimpuesto`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VSRetencion`
--

DROP TABLE IF EXISTS `VSRetencion`;
CREATE TABLE `VSRetencion`
(
  `Idretencion` int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Idimpreten` int(20) NOT NULL,
  `Retencion` text,
  `Porcentaje` decimal(5,2) DEFAULT '0.00',
  `Codigo` varchar(10) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  FOREIGN KEY (`Idimpreten`) REFERENCES `VSImpuestoRetencion`(`Idimpreten`)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VSTarifa`
--

DROP TABLE IF EXISTS `VSTarifa`;
CREATE TABLE `VSTarifa`
(
  `Idtarifa` int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Idimpuesto` int(20) NOT NULL,
  `Codigo` varchar(5) DEFAULT NULL,
  `Tarifa` text,
  `Porcentaje` decimal(5,2) DEFAULT NULL,
  `Grupo` varchar(2) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  FOREIGN KEY (`Idimpuesto`) REFERENCES `VSImpuesto` (`Idimpuesto`)
);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VSValidacion_Mensajes`
--

DROP TABLE IF EXISTS `VSValidacion_Mensajes`;
CREATE TABLE `VSValidacion_Mensajes`
(
  `Idvalmen` int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Idvalidacion` int(10) NOT NULL,
  `Codigo` int(3) DEFAULT NULL,
  `Descripcion` text,
  `Solucion` text,
  `Estado` varchar(1) DEFAULT NULL,
  FOREIGN KEY (`Idvalidacion`) REFERENCES `VSValidacion` (`Idvalidacion`)
);


--
-- Table structure for table `VSDirectorio`
--

DROP TABLE IF EXISTS `VSDirectorio`;
CREATE TABLE `VSDirectorio` (
  `IdDirectorio` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_id` bigint(20) NOT NULL,
  `TipoDocumento` varchar(3) DEFAULT NULL,
  `Descripcion` varchar(100) DEFAULT NULL,
  `Ruta` varchar(100) DEFAULT NULL,
  `UsuarioCreacion` int(10) DEFAULT NULL,
  `FechaCreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Estado` varchar(1) DEFAULT NULL,
  FOREIGN KEY (`emp_id`) REFERENCES `empresa` (`emp_id`)
);

--
-- Table structure for table `VSFirmaDigital`
--

DROP TABLE IF EXISTS `VSFirmaDigital`;
CREATE TABLE `VSFirmaDigital` (
  `Id` int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_id` bigint(20) NOT NULL,
  `Clave` varchar(100) DEFAULT NULL,
  `RutaFile` varchar(100) DEFAULT NULL,
  `RutaFileCrt` varchar(100) DEFAULT NULL,
  `FechaCaducidad` date DEFAULT NULL,
  `EmpresaCertificadora` varchar(100) DEFAULT NULL,
  `SeaDocXml` varchar(100) DEFAULT NULL,
  `Wdsl_local` varchar(100) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  `UsuarioCreacion` int(10) DEFAULT NULL,
  `FechaCreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaModificacion` timestamp NULL DEFAULT NULL,
  FOREIGN KEY (`emp_id`)  REFERENCES `empresa` (`emp_id`)
);

--
-- Table structure for table `VSServiciosSRI`
--

DROP TABLE IF EXISTS `VSServiciosSRI`;
CREATE TABLE `VSServiciosSRI` (
  `Id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_id` bigint(20) NOT NULL,
  `Ambiente` varchar(1) DEFAULT NULL,
  `Recepcion` varchar(200) DEFAULT NULL,
  `Autorizacion` varchar(200) DEFAULT NULL,
  `RecepcionLote` varchar(100) DEFAULT NULL,
  `TiempoRespuesta` int(10) DEFAULT '0',
  `TiempoSincronizacion` int(10) DEFAULT '0',
  `UsuarioCreacion` varchar(45) DEFAULT NULL,
  `FechaCreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaModificacion` timestamp NULL DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  FOREIGN KEY (`emp_id`)  REFERENCES `empresa` (`emp_id`)
);

--
-- Table structure for table `VSServidorCorreo`
--

DROP TABLE IF EXISTS `VSServidorCorreo`;
CREATE TABLE `VSServidorCorreo` (
  `Id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_id` bigint(20) NOT NULL,
  `Mail` varchar(100) DEFAULT NULL,
  `NombreMostrar` varchar(200) DEFAULT NULL,
  `Asunto` text,
  `Cuerpo` text,
  `EsHtml` varchar(1) DEFAULT NULL,
  `Clave` varchar(25) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `SMTPServidor` varchar(100) DEFAULT NULL,
  `SMTPPuerto` int(10) DEFAULT NULL,
  `TiempoRespuesta` int(10) DEFAULT NULL,
  `TiempoEspera` int(10) DEFAULT NULL,
  `ServidorAcuse` text,
  `ActivarAcuse` int(1) DEFAULT NULL,
  `CCO` varchar(100) DEFAULT NULL,
  `UsuarioCreacion` int(10) DEFAULT NULL,
  `FechaCreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaModificacion` timestamp NULL DEFAULT NULL,
  `Estado` varchar(1) DEFAULT NULL,
  FOREIGN KEY (`emp_id`) REFERENCES `empresa` (`emp_id`)
);

--
-- Table structure for table `establecimiento`
--

DROP TABLE IF EXISTS `establecimiento`;
CREATE TABLE `establecimiento` (
  `est_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emp_id` bigint(20) NOT NULL,
  `est_numero` varchar(3) DEFAULT NULL,
  `est_nombre` varchar(300) DEFAULT NULL,
  `est_direccion` varchar(300) DEFAULT NULL,
  `est_telefono` varchar(45) DEFAULT NULL,
  `est_log` varchar(1) DEFAULT NULL,
  `fec_cre` timestamp NULL DEFAULT NULL,
  `fec_mod` timestamp NULL DEFAULT NULL,
  FOREIGN KEY (`emp_id`) REFERENCES `empresa` (`emp_id`)
);

--
-- Table structure for table `punto_emision`
--

DROP TABLE IF EXISTS `punto_emision`;
CREATE TABLE `punto_emision` (
  `pemi_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `est_id` bigint(20) NOT NULL,
  `pemi_numero` varchar(3) DEFAULT NULL,
  `pemi_nombre` varchar(300) DEFAULT NULL,
  `est_log` varchar(1) DEFAULT NULL,
  `fec_cre` timestamp NULL DEFAULT NULL,
  `fec_mod` timestamp NULL DEFAULT NULL,
  FOREIGN KEY (`est_id`) REFERENCES `establecimiento` (`est_id`)
);
