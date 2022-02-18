--
-- Base de datos: `db_captacion`
--
DROP SCHEMA IF EXISTS `db_captacion` ;
CREATE SCHEMA IF NOT EXISTS `db_captacion` DEFAULT CHARACTER SET utf8 ;
USE `db_captacion` ;

-- GRANT ALL PRIVILEGES ON `db_captacion`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `interesado`
--
create table if not exists `interesado` (
 `int_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `int_estado_interesado` varchar(1) null,
 `int_usuario_ingreso` bigint(20) not null,
 `int_usuario_modifica` bigint(20) null,
 `int_estado` varchar(1) not null,
 `int_fecha_creacion` timestamp not null default current_timestamp,
 `int_fecha_modificacion` timestamp null default null,
 `int_estado_logico` varchar(1) not null
);

--
-- Estructura de tabla para la tabla `interesado_empresa`
--
create table if not exists `interesado_empresa` (
 `iemp_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `emp_id` bigint(20) not null,
 `iemp_estado` varchar(1) null,
 `iemp_usuario_ingreso` bigint(20) not null,
 `iemp_usuario_modifica` bigint(20) null,
 `iemp_fecha_creacion` timestamp not null default current_timestamp,
 `iemp_fecha_modificacion` timestamp null default null,
 `iemp_estado_logico` varchar(1) not null,
foreign key (int_id) references `interesado`(int_id)
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `metodo_ingreso`
--
create table if not exists `metodo_ingreso` (
 `ming_id` bigint(20) not null auto_increment primary key,
 `ming_nombre` varchar(300) not null,
 `ming_descripcion` varchar(500) not null,
 `ming_alias` varchar(50) not null,
 `ming_estado` varchar(1) not null,
 `ming_fecha_creacion` timestamp not null default current_timestamp,
 `ming_fecha_modificacion` timestamp null default null,
 `ming_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `nivelint_metodo`
-- El nombre de la tabla hace referencia a la tabla que existía: nivel_interes
-- que fue reemplazada por unidad_academica.
create table if not exists `nivelint_metodo` (
 `nmet_id` bigint(20) not null auto_increment primary key,
 `uaca_id` bigint(20) not null,
 `ming_id` bigint(20) not null,
 `nmet_estado` varchar(1) not null,
 `nmet_fecha_creacion` timestamp not null default current_timestamp,
 `nmet_fecha_modificacion` timestamp null default null,
 `nmet_estado_logico` varchar(1) not null,
 foreign key (ming_id) references `metodo_ingreso`(ming_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `documento_adjuntar`
--
create table if not exists `documento_adjuntar` (
 `dadj_id` bigint(20) not null auto_increment primary key,
 `dadj_nombre` varchar(300) not null,
 `dadj_descripcion` varchar(500) not null,
 `dadj_estado` varchar(1) not null,
 `dadj_fecha_creacion` timestamp not null default current_timestamp,
 `dadj_fecha_modificacion` timestamp null default null,
 `dadj_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `doc_nint_tciudadano`
-- El nombre de la tabla hace referencia a la tabla que existía: nivel_interes
-- que fue reemplazada por unidad_academica.
create table if not exists `doc_nint_tciudadano` (
 `dntc_id` bigint(20) not null auto_increment primary key,
 `tciu_id` bigint(20) not null,
 `uaca_id` bigint(20) not null,
 `dadj_id` bigint(20) not null,
 `dntc_estado` varchar(1) not null,
 `dntc_fecha_creacion` timestamp not null default current_timestamp,
 `dntc_fecha_modificacion` timestamp null default null,
 `dntc_estado_logico` varchar(1) not null,
 foreign key (dadj_id) references `documento_adjuntar`(dadj_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `res_sol_inscripcion`
--
create table if not exists `res_sol_inscripcion` (
 `rsin_id` bigint(20) not null auto_increment primary key,
 `rsin_nombre` varchar(300) not null,
 `rsin_descripcion` varchar(500) not null,
 `rsin_estado` varchar(1) not null,
 `rsin_fecha_creacion` timestamp not null default current_timestamp,
 `rsin_fecha_modificacion` timestamp null default null,
 `rsin_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `convenio_empresa`
--
create table if not exists db_captacion.convenio_empresa (
 `cemp_id` bigint(20) not null auto_increment primary key,
 `cemp_nombre` varchar(500) not null,
 `cemp_estado_empresa` varchar(1) not null,
 `cemp_estado` varchar(1) not null,
 `cemp_fecha_creacion` timestamp not null default current_timestamp,
 `cemp_fecha_modificacion` timestamp null default null,
 `cemp_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `solicitud_inscripcion`
--
create table if not exists `solicitud_inscripcion` (
 `sins_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `uaca_id` bigint(20) null, -- guarda la unidad academica de la bd_academico
 `mod_id` bigint(20)  null,
 `ming_id` bigint(20) null,
 `eaca_id` bigint(20) null, -- guarda el id de carrera y de otros servicios.
 `mest_id` bigint(20) null,
 `emp_id` bigint(20) null,
 `num_solicitud` varchar(10) null,
 `rsin_id` bigint(20) not null,
 `sins_fecha_solicitud` timestamp null default null,
 `sins_fecha_preaprobacion` timestamp null default null,
 `sins_fecha_aprobacion` timestamp null default null,
 `sins_fecha_reprobacion` timestamp null default null,
 `sins_fecha_prenoprobacion` timestamp null default null,
 `sins_preobservacion` varchar(1000) null,
 `sins_observacion` varchar(1000) null,
 `sins_observacion_creasolicitud` varchar(1000) null,
 `sins_observacion_revisa` varchar(1000) null,
 `sins_beca` varchar(1) null,
 `cemp_id` bigint(20) null,
 `sins_usuario_preaprueba` bigint(20) null,
 `sins_usuario_aprueba` bigint(20) null,
 `sins_usuario_ingreso` bigint(20) null,
 `sins_usuario_modifica` bigint(20) null,
 `sins_estado` varchar(1) not null,
 `sins_fecha_creacion` timestamp not null default current_timestamp,
 `sins_fecha_modificacion` timestamp null default null,
 `sins_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id),
 foreign key (rsin_id) references `res_sol_inscripcion`(rsin_id),
 foreign key(cemp_id) references convenio_empresa(cemp_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `solicitudins_documento`
--
create table if not exists `solicitudins_documento` (
 `sdoc_id` bigint(20) not null auto_increment primary key,
 `sins_id` bigint(20) not null,
 `int_id` bigint(20) not null,
 `dadj_id` bigint(20) not null,
 `sdoc_archivo` varchar(500) not null,
 `sdoc_observacion` varchar(500) null,
 `sdoc_usuario_ingreso` bigint(20) null,
 `sdoc_usuario_modifica` bigint(20) null,
 `sdoc_estado` varchar(1) not null,
 `sdoc_fecha_creacion` timestamp not null default current_timestamp,
 `sdoc_fecha_modificacion` timestamp null default null,
 `sdoc_estado_logico` varchar(1) not null,
 foreign key (sins_id) references `solicitud_inscripcion`(sins_id),
 foreign key (int_id) references `interesado`(int_id),
 foreign key (dadj_id) references `documento_adjuntar`(dadj_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `admitido`
--
create table if not exists `admitido` (
 `adm_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `sins_id` bigint(20) null,
 `adm_estado_admitido` varchar(1) null,
 `adm_estado` varchar(1) not null,
 `adm_fecha_creacion` timestamp not null default current_timestamp,
 `adm_fecha_modificacion` timestamp null default null,
 `adm_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id),
 foreign key (sins_id) references `solicitud_inscripcion`(sins_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_familia`
--
create table if not exists `informacion_familia` (
 `ifam_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `nins_padre` bigint(20) not null,
 `nins_madre` bigint(20) not null,
 `ifam_miembro` varchar(2) not null,
 `ifam_salario` varchar(15)  null,
 `ifam_estado` varchar(1)  null,
 `ifam_fecha_creacion` timestamp not null default current_timestamp,
 `ifam_fecha_modificacion` timestamp null default null,
 `ifam_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_enfermedad`
--
create table if not exists `info_enfermedad` (
 `ienf_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `ienf_enfermedad` varchar(1) not null,
 `ienf_tipoenfermedad` varchar(100) null,
 `ienf_archivo` varchar(500) null,
 `ienf_estado` varchar(1) not null,
 `ienf_fecha_creacion` timestamp not null default current_timestamp,
 `ienf_fecha_modificacion` timestamp null default null,
 `ienf_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_familia_enfermedad`
--
create table if not exists `info_familia_enfermedad` (
 `ifen_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `tpar_id` bigint(20) not null,
 `ifen_enfermedad` varchar(1) not null,
 `ifen_tipoenfermedad` varchar(100) null,
 `ifen_archivo` varchar(500) null,
 `ifen_estado` varchar(1) not null,
 `ifen_fecha_creacion` timestamp not null default current_timestamp,
 `ifen_fecha_modificacion` timestamp null default null,
 `ifen_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_discapacidad`
--
create table if not exists `info_discapacidad` (
 `idis_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `tdis_id` bigint(20) not null,
 `idis_discapacidad` varchar(1) not null,
 `idis_porcentaje` varchar(3) null,
 `idis_archivo` varchar(500) null,
 `idis_estado` varchar(1) not null,
 `idis_fecha_creacion` timestamp not null default current_timestamp,
 `idis_fecha_modificacion` timestamp null default null,
 `idis_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_familia_discapacidad`
--
create table if not exists `info_familia_discapacidad` (
 `ifdis_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `tpar_id` bigint(20) not null,
 `tdis_id` bigint(20) not null,
 `ifdi_discapacidad` varchar(1) not null,
 `ifdi_porcentaje` varchar(3) null,
 `ifdi_archivo` varchar(500) null,
 `ifdi_estado` varchar(1) not null,
 `ifdi_fecha_creacion` timestamp not null default current_timestamp,
 `ifdi_fecha_modificacion` timestamp null default null,
 `ifdi_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_academico`
--
create table if not exists `info_academico` (
 `iaca_id` bigint(20) not null auto_increment primary key,
 `int_id` bigint(20) not null,
 `pai_id` bigint(20) default null,
 `pro_id` bigint(20) default null,
 `can_id` bigint(20) default null,
 `tiac_id` bigint(20) null,
 `tnes_id` bigint(20) null,
 `iaca_institucion` varchar(500) null,
 `iaca_titulo` varchar(500) null,
 `iaca_anio_grado` varchar(4) null,
 `iaca_estado` varchar(1) not null,
 `iaca_fecha_creacion` timestamp not null default current_timestamp,
 `iaca_fecha_modificacion` timestamp null default null,
 `iaca_estado_logico` varchar(1) not null,
 foreign key (int_id) references `interesado`(int_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `medio_publicitario`
--
create table if not exists `medio_publicitario` (
 `mpub_id` bigint(20) not null auto_increment primary key,
 `mpub_nombre` varchar(300) not null,
 `mpub_descripcion` varchar(500)not null,
 `mpub_estado` varchar(1) not null,
 `mpub_fecha_creacion` timestamp not null default current_timestamp,
 `mpub_fecha_modificacion` timestamp null default null,
 `mpub_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `consideracion`
--
create table if not exists `solicitud_noaprobada` (
 `snoa_id` bigint(20) not null auto_increment primary key,
 `snoa_nombre` varchar(200) not null,
 `snoa_descripcion` varchar(200) not null,
 `snoa_estado` varchar(1) not null,
 `snoa_fecha_creacion` timestamp not null default current_timestamp,
 `snoa_fecha_modificacion` timestamp null default null,
 `snoa_estado_logico` varchar(1) not null
);

-- -----------------------------------------------------------
-- Estructura de tabla para la tabla `consideracion_documento`
--
create table if not exists `solicitud_noaprobada_documento` (
 `sndo_id` bigint(20) not null auto_increment primary key,
 `snoa_id` bigint(20) not null,
 `dadj_id` bigint(20) not null,
 `sndo_tiponacext` varchar(1) not null,
 `sndo_estado` varchar(1) not null,
 `sndo_fecha_creacion` timestamp not null default current_timestamp,
 `sndo_fecha_modificacion` timestamp null default null,
 `sndo_estado_logico` varchar(1) not null,
 foreign key (dadj_id) references `documento_adjuntar`(dadj_id),
 foreign key (snoa_id) references `solicitud_noaprobada`(snoa_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `solicitud_rechazada`
--
create table if not exists `solicitud_rechazada` (
 `srec_id` bigint(20) not null auto_increment primary key,
 `sins_id` bigint(20) not null,
 `dadj_id` bigint(20) not null,
 `snoa_id` bigint(20) not null,
 `srec_etapa` varchar(1) not null,
 `srec_observacion` varchar(300) not null,
 `usu_id` bigint(20) not null,
 `srec_estado` varchar(1) not null,
 `srec_fecha_creacion` timestamp not null default current_timestamp,
 `srec_fecha_modificacion` timestamp null default null,
 `srec_estado_logico` varchar(1) not null,
 foreign key (sins_id) references `solicitud_inscripcion`(sins_id),
 foreign key (dadj_id) references `documento_adjuntar`(dadj_id),
 foreign key (snoa_id) references `solicitud_noaprobada`(snoa_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `usuarios_inactivos`
--
create table if not exists `usuarios_inactivos` (
 `uina_id` bigint(20) not null auto_increment primary key,
 `usu_id` bigint(20) not null,
 `uina_usu_inactiva` bigint(20) not null,
 `uina_observacion` varchar(1000) not null,
 `uina_estado` varchar(1) not null,
 `uina_fecha_creacion` timestamp not null default current_timestamp,
 `uina_fecha_modificacion` timestamp null default null,
 `uina_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `usuarios_inactivos`
--
create table if not exists `solicitud_datos_factura` (
 `sdfa_id` bigint(20) not null auto_increment primary key,
 `sins_id` bigint(20) not null,
 `sdfa_nombres` varchar(50) not null,
 `sdfa_apellidos` varchar(50) not null,
 `sdfa_tipo_dni` varchar(5) not null,
 `sdfa_dni` varchar(50) not null,
 `sdfa_direccion` varchar(200) not null,
 `sdfa_telefono` varchar(50) not null,
 `sdfa_correo` varchar(80) not null,
 `sdfa_estado` varchar(1) not null,
 `sdfa_fecha_creacion` timestamp not null default current_timestamp,
 `sdfa_fecha_modificacion` timestamp null default null,
 `sdfa_estado_logico` varchar(1) not null,
 foreign key (sins_id) references `solicitud_inscripcion`(sins_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `matriculados_reprobado`
--
create table if not exists `matriculados_reprobado` (
 `mre_id` bigint(20) not null auto_increment primary key,
 `adm_id` bigint(20) not null,
 `pami_id` bigint(20) not null,
 `sins_id` bigint(20) not null,
 `uaca_id` bigint(20) not null,
 `mod_id` bigint(20) not null,
 `eaca_id` bigint(20) not null,
 `mre_usuario_ingreso` bigint(20) not null,
 `mreusuario_modifica` bigint(20)  null,
 `mre_estado_matriculado` bigint(20) null,
 `mre_estado` varchar(1) not null,
 `mre_fecha_creacion` timestamp not null default current_timestamp,
 `mre_fecha_modificacion` timestamp null default null,
 `mre_estado_logico` varchar(1) not null,
 foreign key (adm_id) references `admitido`(adm_id) ,
 foreign key (sins_id) references `solicitud_inscripcion`(sins_id)
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `matriculados_reprobado`
--
create table if not exists `materias_matriculados_reprobado` (
 `mmr_id` bigint(20) not null auto_increment primary key,
 `mre_id` bigint(20) not null,
 `asi_id` bigint(20) not null,
 `mmr_estado_materia` varchar(1) not null,
 `mmr_usuario_ingreso` bigint(20) not null,
 `mmr_usuario_modifica` bigint(20)  null,
 `mmr_estado` varchar(1) not null,
 `mmr_fecha_creacion` timestamp not null default current_timestamp,
 `mmr_fecha_modificacion` timestamp null default null,
 `mmr_estado_logico` varchar(1) not null,
  foreign key (mre_id) references `matriculados_reprobado`(mre_id)
);


/*
    Esta tabla se esta creando para almacenar la informacion guardada en el wizard, debido a que no7
    hay como guardar en las tablas principales, informacion no completa, con la finanlidad de
    evitar incosistencia en la data.
    La data de esta tabla debe eliminarse a traves de un proceso cron, que se debe ejecutar
    de manera periodica.
*/
create table if not exists `temporal_wizard_inscripcion` (
 `twin_id` bigint(20) not null auto_increment primary key,
 `twin_nombre` varchar(1000) not null,
 `twin_apellido` varchar(1000) not null,
 `twin_dni` varchar(1000) not  null,
 `twin_numero` varchar(1000) not  null,
 `twin_correo` varchar(1000) not  null,
 `twin_empresa` varchar(200) null,
 `twin_pais` bigint(20) not null,
 `twin_celular` bigint(20) not null,
 `uaca_id` bigint(20) not null,
 `mod_id` bigint(20) not null,
 `car_id` bigint(20) not null,
 `twin_metodo_ingreso` bigint(20) null,
 `conuteg_id` bigint(20) null,
 `cemp_id` bigint(20) null,
 `ruta_doc_titulo` varchar(200) null,
 `ruta_doc_dni` varchar(200) null,
 `ruta_doc_certvota` varchar(200) null,
 `ruta_doc_foto` varchar(200) null,
 `ruta_doc_certificado` varchar(200) null,
 `ruta_doc_hojavida` varchar(200) null,
 `ruta_doc_aceptacion` varchar(200) null,
 `ruta_doc_pago` varchar(200) null,
 `twin_mensaje1` varchar(1) null,
 `twin_tipo_pago` varchar(1) null,
 `twin_mensaje2` varchar(1) null,
 `twin_estado` varchar(1) not null,
 `twin_fecha_creacion` timestamp not null default current_timestamp,
 `twin_fecha_modificacion` timestamp null default null,
 `twin_estado_logico` varchar(1) not null
);

/*
    Esta tabla se esta creando para almacenar la informacion guardada en el wizard, debido a que no7
    hay como guardar en las tablas principales, informacion no completa, con la finanlidad de
    evitar incosistencia en la data.
    La data de esta tabla debe eliminarse a traves de un proceso cron, que se debe ejecutar
    de manera periodica.
*/

create table if not exists `temporal_wizard_reprobados` (
 `twre_id` bigint(20) not null auto_increment primary key,
 `twre_nombre` varchar(1000) not null,
 `twre_apellido` varchar(1000) not null,
 `twre_dni` varchar(1000) not  null,
 `twre_numero` varchar(1000) not  null,
 `twre_correo` varchar(1000) not  null,
 `twre_pais` bigint(20) not null,
 `twre_celular` bigint(20) not null,
 `uaca_id` bigint(20) not null,
 `mod_id` bigint(20) not null,
 `car_id` bigint(20) not null,
 `twre_metodo_ingreso` bigint(20) null,
 `conuteg_id` bigint(20) null,
 `ruta_doc_titulo` varchar(200) null,
 `ruta_doc_dni` varchar(200) null,
 `ruta_doc_certvota` varchar(200) null,
 `ruta_doc_foto` varchar(200) null,
 `ruta_doc_certificado` varchar(200) null,
 `ruta_doc_hojavida` varchar(200) null,
 `twre_mensaje1` varchar(1) null,
 `twre_mensaje2` varchar(1) null,
 `twre_beca` varchar(1) null,
 `twre_fecha_solicitud` timestamp null,
 `sdes_id` bigint(20) null,
 `ite_id` bigint(20) null,
 `twre_observacion_sol` varchar(200) null,
 `twre_precio_item` varchar(200) null,
 `twre_precio_descuento` varchar(200) null,
 `twre_estado` varchar(1) not null,
 `twre_fecha_creacion` timestamp not null default current_timestamp,
 `twre_fecha_modificacion` timestamp null default null,
 `twre_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `solicitud_inscripcion_modificar`
--
create table if not exists `solicitud_inscripcion_modificar` (
 `sinmo_id` bigint(20) not null auto_increment primary key,
 `sins_id` bigint(20) not null,
 `sinmo_contador` bigint(2) not null,
 `sinmo_usuario_ingreso` bigint(20) not null,
 `sinmo_usuario_modifica` bigint(20)  null,
 `sinmo_estado` varchar(1) not null,
 `sinmo_fecha_creacion` timestamp not null default current_timestamp,
 `sinmo_fecha_modificacion` timestamp null default null,
 `sinmo_estado_logico` varchar(1) not null,
  foreign key (sins_id) references `solicitud_inscripcion`(sins_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `solicitud_inscripcion_saldos`
--
create table if not exists `solicitud_inscripcion_saldos` (
 `sinsa_id` bigint(20) not null auto_increment primary key,
 `sins_id` bigint(20) not null,
 `sinsa_valor_anterior` double not null,
 `sinsa_valor_actual` double not null,
 `sinsa_saldo` double not null,
 `sinsa_estado_saldofavor` varchar(1) null, -- E (Estudiante), U (Uteg)
 `sinsa_estado_saldoconsumido` varchar(1) null, -- P (Pendiente), C (consumido)
 `sinsa_usuario_ingreso` bigint(20) not null,
 `sinsa_usuario_modifica` bigint(20)  null,
 `sinsa_estado` varchar(1) not null,
 `sinsa_fecha_creacion` timestamp not null default current_timestamp,
 `sinsa_fecha_modificacion` timestamp null default null,
 `sinsa_estado_logico` varchar(1) not null,
  foreign key (sins_id) references `solicitud_inscripcion`(sins_id)
);