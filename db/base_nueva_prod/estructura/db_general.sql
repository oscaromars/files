-- Base de Datos 

DROP SCHEMA IF EXISTS `db_general` ;
CREATE SCHEMA IF NOT EXISTS `db_general` default CHARACTER SET utf8 ;
USE `db_general`;

-- GRANT ALL PRIVILEGES ON `db_general`.* TO 'uteg'@'localhost' IDENTIFIED BY 'Utegadmin2016*';
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `persona_correo_institucional`
--
create table if not exists `persona_correo_institucional` (
 `pcin_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `pcin_correo` varchar(250) default null,
 `pcin_estado` varchar(1) not null,
 `pcin_fecha_creacion` timestamp not null default current_timestamp on update current_timestamp,
 `pcin_fecha_modificacion` timestamp null default null,
 `pcin_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `tipo_contacto_general` 
-- --------------------------------------------------------
create table if not exists `tipo_contacto_general` (
 `tcge_id` bigint(20) not null auto_increment primary key,
 `tcge_nombre` varchar(100) default null,  
 `tcge_descripcion` varchar(500) default null, 
 `tcge_telef_contacto` varchar(100) default null,
 `tcge_estado` varchar(1) not null, 
 `tcge_fecha_creacion` timestamp not null default current_timestamp,
 `tcge_fecha_modificacion` timestamp null default null,
 `tcge_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `contacto_general` 
-- --------------------------------------------------------
create table if not exists `contacto_general` (
 `cgen_id` bigint(20) not null auto_increment primary key,
 `tcge_id` bigint(20) not null, -- tipo de contacto, 1.- emergencia, 2.- experiencia laboral, 3.- experiencia docencia
 `per_id` bigint(20) not null, /*referencia de la tabla persona det bd_asgard*/ 
 `cgen_nombre` varchar(50) default null,
 `cgen_apellido` varchar(50) default null,
 `tpar_id` bigint(20) default null, -- Aqui se guarda el id de tipo de parentesco de db_asgard
 `cgen_direccion` varchar(500) default null,
 `cgen_telefono` varchar(50) default null,
 `cgen_celular` varchar(50) default null,
 `cgen_estado` varchar(1) not null,
 `cgen_fecha_creacion` timestamp not null default current_timestamp,
 `cgen_fecha_modificacion` timestamp null default null,
 `cgen_estado_logico` varchar(1) not null,
 foreign key (tcge_id) references `tipo_contacto_general`(tcge_id) 
);

-- --------------------------------------------------------
-- /*(1 => integrantes de familia que viven con profesor, 2 => familiares en la institucion)*/
-- Estructura de tabla para la tabla `tipo_antecedente_familia`
--
create table if not exists `tipo_antecedente_familia` (
  `tafa_id` bigint(20) not null auto_increment primary key,    
  `tafa_nombre` varchar(100) not null,
  `tafa_descripcion` varchar(500) not null,
  `tafa_estado` varchar(1) not null,
  `tafa_fecha_creacion` timestamp not null default current_timestamp,
  `tafa_fecha_modificacion` timestamp null default null,
  `tafa_estado_logico` varchar(1) not null  
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_antecedentes_fam` 
--
create table if not exists `detalle_antecedentes_fam` (
 `dafa_id` bigint(20) not null auto_increment primary key, 
 `per_id` bigint(20) not null, /*referencia de la tabla persona de bs_asgard*/
 `tpar_id` bigint(20) not null,  /*tipo parentesco de asgard ver si estan todos sino aumentar*/
 `tafa_id` bigint(20) not null,
 `ipdi_id` bigint(20)  null default null, /*guarda el id si el familiar tiene discapacidad*/
 `dafa_nombres` varchar(100) default null,
 `dafa_apellidos` varchar(100) default null,
 `dafa_fecha_nacimiento` timestamp null default null,
 `dafa_ocupacion` varchar(50) default null,
 `dafa_genero` varchar(1) default null, 
 `dafa_carga_actual` varchar(1) default null, 
 `dafa_archivo` varchar(500) default null, 
 `dafa_estado` varchar(1) not null, 
 `dafa_fecha_creacion` timestamp not null default current_timestamp,    
 `dafa_fecha_modificacion` timestamp null default null,
 `dafa_estado_logico` varchar(1) not null,
  foreign key (tafa_id) references `tipo_antecedente_familia`(tafa_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `info_per_discapacidad` 
-- --------------------------------------------------------
create table if not exists `info_per_discapacidad` (
 `ipdi_id` bigint(20) not null auto_increment primary key,
 `tdis_id` bigint(20) not null, -- tipo de discapacidad de asgard, determinar si estan todas las de la lista y las que no AUMNETAR
 `per_id` bigint(20) null, /*referencia de la tabla persona de bd_asgard*/ 
 `ipdi_carnet_conadis` varchar(20) default null,
 `ipdi_discapacidad` varchar(1) default null, 
 `ipdi_porcentaje` varchar(3) default null,
 `ipdi_archivo` varchar(500) default null,
 `ipdi_ruta` varchar(500) default null,
 `ipdi_estado` varchar(1) not null, 
 `ipdi_fecha_creacion` timestamp  null default current_timestamp,
 `ipdi_fecha_modificacion` timestamp null default null,
 `ipdi_estado_logico` varchar(1) not null
);

--
-- Estructura de tabla para la tabla `institucion`
--
create table if not exists `institucion` (
  `ins_id` bigint(20) not null auto_increment primary key,  
  `ins_categoria` varchar(1) null,
  `pai_id` bigint(20) default null, /* tabla pais bd_asgard*/
  `pro_id` bigint(20) default null, /* tabla pais bd_asgard*/
  `can_id` bigint(20) default null, /* tabla pais bd_asgard*/
  `ins_nombre` varchar(100) not null,
  `ins_abreviacion` varchar(10) default null,
  `ins_direccion_institucion` varchar(50) default null,
  `ins_telefono_institucion` varchar(50) default null,
  `ins_enlace` varchar(200) default null, 
  `ins_estado` varchar(1) not null,
  `ins_fecha_creacion` timestamp not null default current_timestamp,
  `ins_fecha_modificacion` timestamp null default null,
  `ins_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- /* (1 => educacion superior, 2 => reconocimientos academicos, 3 => idiomas) */
-- Estructura de tabla para la tabla `tipo_curricular`
--
create table if not exists `tipo_curricular` (
  `tcur_id` bigint(20) not null auto_increment primary key,    
  `tcur_nombre` varchar(100) not null,
  `tcur_descripcion` varchar(500) not null,
  `tcur_estado` varchar(1) not null,
  `tcur_fecha_creacion` timestamp not null default current_timestamp,
  `tcur_fecha_modificacion` timestamp null default null,
  `tcur_estado_logico` varchar(1) not null  
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `idioma`
-- Ingles, Frances ,etc. 
create table if not exists `idioma` (
 `idi_id` bigint(20) not null auto_increment primary key,
 `idi_nombre` varchar(250) default null,
 `idi_descripcion` varchar(500) default null,
 `idi_estado` varchar(1) not null,
 `idi_fecha_creacion` timestamp not null default current_timestamp,
 `idi_fecha_modificacion` timestamp null default null,
 `idi_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `criterio_idioma`
-- Hablado, Escrito
create table if not exists `criterio_idioma` (
 `cidi_id` bigint(20) not null auto_increment primary key,
 `cidi_nombre` varchar(500) default null,
 `cidi_descripcion` varchar(500) default null,
 `cidi_estado` varchar(1) not null,
 `cidi_fecha_creacion` timestamp not null default current_timestamp,
 `cidi_fecha_modificacion` timestamp null default null,
 `cidi_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `nivel_idioma`
-- basico, intermedio, avanzado
create table if not exists `nivel_idioma` (
 `nidi_id` bigint(20) not null auto_increment primary key,
 `nidi_descripcion` varchar(500) default null,
 `nidi_estado` varchar(1) not null,
 `nidi_fecha_creacion` timestamp not null default current_timestamp,
 `nidi_fecha_modificacion` timestamp null default null,
 `nidi_estado_logico` varchar(1) not null
) ;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `resultado_x_idioma`
-- --------------------------------------------------------
create table if not exists `resultado_x_idioma` (
  `rxid_id` bigint(20) not null auto_increment primary key, 
  `per_id` bigint(20) not null, /*referencia de la tabla persona de bd_asgard*/ 
  `idi_id` bigint(20)  null,  
  `rxid_otro_idioma` varchar(100) null,  
  `cidi_id` bigint(20) not null,   
  `nidi_id` bigint(20) not null,  
  `rxid_institucion` varchar(200) not null,
  `rxid_documento` varchar(500) not null,
  `rxid_estado` varchar(1) not null,
  `rxid_fecha_creacion` timestamp not null default current_timestamp,
  `rxid_fecha_modificacion` timestamp null default null,
  `rxid_estado_logico` varchar(1) not null,
  foreign key (idi_id) references `idioma`(idi_id),
  foreign key (cidi_id) references `criterio_idioma`(cidi_id),
  foreign key (nidi_id) references `nivel_idioma`(nidi_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_informacion_curricular` 
--
create table if not exists `detalle_informacion_curricular` (
 `dicu_id` bigint(20) not null auto_increment primary key, 
 `per_id` bigint(20) not null, /*referencia de la tabla persona de bs_asgard*/ 
 `dicu_nivel_instruccion` bigint(20) null default null,  /* informacion tabla parametros */
 `acon_id` bigint(20) null, /*área de conocimiento - db_academico*/
 `scon_id` bigint(20) null, /*subárea de conocimiento - db_academico*/
 `ins_id` bigint(20) null default null, /* Insitutcion*/
 `tcur_id` bigint(20) not null, /*Estudios actuales, superios.*/ 
 `dicu_otra_institucion` varchar(100) default null,
 `dicu_titulo` varchar(50) default null, 
 `dicu_fecha_registro` timestamp null default null, 
 `dicu_numero_registro` varchar(50) default null, 
 `dicu_documento` varchar(500) default null, 
 `dicu_estado` varchar(1) not null, 
 `dicu_fecha_creacion` timestamp not null default current_timestamp,
 `dicu_fecha_modificacion` timestamp null default null,
 `dicu_estado_logico` varchar(1) not null, 
 foreign key (tcur_id) references `tipo_curricular`(tcur_id),
 foreign key (ins_id) references `institucion`(ins_id)
 
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `informacion_contacto` 
--
 create table if not exists `informacion_contacto`(
  `icon_id` bigint(20) not null auto_increment primary key, 
  `per_id` bigint(20) not null, /*referencia de la tabla persona de bs_asgard*/  
  `tcge_id` bigint(20) not null, 
  `icon_nombres`varchar(2000) null default null,
  `icon_direccion` varchar(1000) null default null,
  `icon_telefono` varchar(50) default null,
  `icon_celular` varchar(50) default null,
  `icon_correo` varchar(500) default null,
  `icon_cargo` varchar(500) null default null,  
  `icon_estado` varchar(1) not null, 
  `icon_fecha_creacion` timestamp not null default current_timestamp,
  `icon_fecha_modificacion` timestamp null default null,  
  `icon_estado_logico` varchar(1) not null,
  foreign key (tcge_id) references `tipo_contacto_general`(tcge_id) 
  );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_experiencia_laboral` 
--
create table if not exists `detalle_experiencia_laboral` (
 `dela_id` bigint(20) not null auto_increment primary key, 
 `per_id` bigint(20) not null, /*referencia de la tabla persona de bd_asgard*/ 
 `temp_id` bigint(20) not null,  /*referencia de la tabla tipo_empresa de db_asgard*/ 
 `pai_id` bigint(20) not null,  /*referencia de la tabla pais de db_asgard*/ 
 `icon_id` bigint(20) null,
 `dela_empresa` varchar(300) not null, 
 `dela_cargo` varchar(500) not null, 
 `dela_inicio_labores` timestamp null default null,
 `dela_fin_labores` timestamp null default null, 
 `dela_actualidad` varchar(1) not null,
 `dela_estado` varchar(1) not null, 
 `dela_fecha_creacion` timestamp not null default current_timestamp,
 `dela_fecha_modificacion` timestamp null default null,
 `dela_estado_logico` varchar(1) not null,
 foreign key (icon_id) references `informacion_contacto`(icon_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_experiencia_docencia` 
--
 create table if not exists `detalle_experiencia_docencia` (
 `dedo_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null, /*referencia de la tabla persona de bs_asgard*/  
 `ins_id` bigint(20) null,
 `icon_id` bigint(20) null,
 `dedo_otra_institucion` varchar(100) default null, 
 `acon_id` bigint(20) not null, /*área de conocimiento - db_academico*/
 `scon_id` bigint(20) null, /*subárea de conocimiento - db_academico*/
 `dedo_catedra_impartida` varchar(100) null,
 `dedo_tipo_dedicacion` bigint(20) not null,  /* Valor Obtenido tabla parámetros*/
 `dedo_tip_relacion_lab` bigint(20) not null, /* Valor Obtenido tabla parámetros*/
 `dedo_direccion_emp` varchar(100) default null,
 `dedo_telefono_emp` varchar(100) default null,
 `dedo_fecha_inicio` timestamp null default null,
 `dedo_fecha_fin` timestamp null default null, 
 `dedo_actual` varchar(1) not null, 
 `dedo_estado` varchar(1) not null, 
 `dedo_fecha_creacion` timestamp not null default current_timestamp,
 `dedo_fecha_modificacion` timestamp null default null,
 `dedo_estado_logico` varchar(1) not null, 
 foreign key (ins_id) references `institucion`(ins_id),
 foreign key (icon_id) references `informacion_contacto`(icon_id)
); 
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_capacitacion` 
--
create table if not exists `detalle_capacitacion` (
 `dcap_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null, /*referencia de la tabla persona de bs_asgard*/   
 `dcap_tipo_diploma` bigint(20) not null, /*1=Asistencia, 2=Aprobación */
 `dcap_modalidad` bigint(20)not null,  /*1=A distancia, 2=Online*/
 `dcap_tipo_capacitacion` bigint(20) not null,  /*tabla parametros*/
 `dcap_nombre_curso` varchar(100) not null,
 `dcap_institucion_organiza` varchar(50) not null,
 `dcap_duracion` varchar(5) not null,
 `dcap_fecha_inicio` timestamp null default null,
 `dcap_fecha_fin` timestamp null default null,
 `dcap_documento_capacitacion` varchar(500) null,
 `dcap_actual` varchar(1) null,
 `dcap_estado` varchar(1) not null, 
 `dcap_fecha_creacion` timestamp not null default current_timestamp,
 `dcap_fecha_modificacion` timestamp null default null,
 `dcap_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_publicacion` 
--
create table if not exists `tipo_publicacion` (
 `tpub_id` bigint(20) not null auto_increment primary key,
 `tpub_nombre` varchar(100) default null, 
 `tpub_descripcion` varchar(100) default null, 
 `tpub_estado` varchar(1) not null, 
 `tpub_fecha_creacion` timestamp not null default current_timestamp,
 `tpub_fecha_modificacion` timestamp null default null,
 `tpub_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `detalle_publicacion`
--
 create table if not exists `detalle_publicacion` (
  `dpub_id` bigint(20) not null auto_increment primary key,    
  `per_id` bigint(20) not null, /*referencia de la tabla persona de bs_asgard*/ 
  `tpub_id` bigint(20) not null,  
  `dpub_titulo` varchar(100) not null,   
  `dpub_fecha_publicacion` timestamp null default null, 
  `dpub_publicacion` bigint(20) not null, /*Informacion de la tabla de parametros*/
  `dpub_nombre_publicacion` varchar(100) not null, 
  `dpub_numero_isbn` varchar(50) not null,  /*almacena el numero de registro ISSN o ISBN*/
  `dpub_actual` varchar(1) not null, 
  `dpub_url` varchar(2000) null default null,
  `dpub_link_publicacion` varchar(500) null,  
  `dpub_estado` varchar(1) not null,
  `dpub_fecha_creacion` timestamp not null default current_timestamp,
  `dpub_fecha_modificacion` timestamp null default null,
  `dpub_estado_logico` varchar(1) not null,
  foreign key (tpub_id) references `tipo_publicacion`(tpub_id)
    
);
 
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `rol_proyecto` 
--
create table if not exists `rol_proyecto` (
 `rpro_id` bigint(20) not null auto_increment primary key,
 `rpro_nombre` varchar(100) default null, 
 `rpro_descripcion` varchar(100) default null, 
 `rpro_estado` varchar(1) not null, 
 `rpro_fecha_creacion` timestamp not null default current_timestamp,
 `rpro_fecha_modificacion` timestamp null default null,
 `rpro_estado_logico` varchar(1) not null
);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `detalle_investigacion`
--
 create table if not exists `detalle_investigacion` (
  `dinv_id` bigint(20) not null auto_increment primary key,  
  `per_id` bigint(20) not null, /*referencia de la tabla persona de bs_asgard*/  
  `dinv_financiado` varchar(1) not null, 
  `dinv_institucion_financia` varchar(100) null, 
  `dinv_nombre_proyecto` varchar(100) not null, 
  `dinv_rol` bigint(20) not null, /*referencia de la tabla parámetros.*/ 
  `dinv_fecha_inicio` timestamp null default null,
  `dinv_fecha_fin` timestamp null default null,
  `dinv_documento` varchar(500) not null, 
  `dinv_actual` varchar(1) not null, 
  `dinv_estado` varchar(1) not null,
  `dinv_fecha_creacion` timestamp not null default current_timestamp,
  `dinv_fecha_modificacion` timestamp null default null,
  `dinv_estado_logico` varchar(1) not null
);
 -- --------------------------------------------------------
--
-- Estructura de tabla para la parametros
--
create table if not exists `parametros` (
 `par_id` bigint(20) not null auto_increment primary key,
 `par_valor` varchar(100) default null, 
 `par_nombre` varchar(100) default null,  
 `par_codigo` varchar(100) default null,
 `par_descriṕcion` varchar(1000) default null,
 `par_estado` varchar(1) not null,
 `par_fecha_creacion` timestamp not null default current_timestamp,
 `par_modificacion` timestamp null default null,
 `par_estado_logico` varchar(1) not null 
);

create table if not exists `info_tutorias`(
  `itut_id` bigint(20) not null auto_increment primary key,
  `per_id` bigint(20) not null, /*referencia de la tabla persona de db_asgard*/ 
  `pai_id` bigint(20) not null, /* tabla pais bd_asgard*/
  `ins_id` bigint(20) null,  
  `acon_id` bigint(20) null, /*área de conocimiento - db_academico*/
  `itut_tipo_codireccion` bigint(20) not null, /*referencia de la tabla parámetros.*/ 
  `itut_otra_institucion` varchar(1000) default null,
  `itut_nombre` varchar(1000) default null,
  `itut_anio_aprob` varchar(50) not null,  
  `itut_estado` varchar(1) not null,
  `itut_fecha_creacion` timestamp not null default current_timestamp,
  `itut_fecha_modificacion` timestamp null default null,
  `itut_estado_logico` varchar(1) not null 
 ); 

create table if not exists `info_conferencias`(
  `icon_id` bigint(20) not null auto_increment primary key,
  `per_id` bigint(20) not null, /*referencia de la tabla persona de db_asgard*/ 
  `pai_id` bigint(20) not null, /* tabla pais db_asgard*/  
  `icon_institucion` varchar(1000) not null, 
  `acon_id` bigint(20) null, /*área de conocimiento - db_academico*/  
  `icon_nombre_evento` varchar(1000) not null,
  `icon_tipo_participacion` bigint(20) not null, /*referencia de la tabla parámetros.*/ 
  `icon_ponencia` varchar(1000) not null,  
  `icon_archivo` varchar(500) null,
  `icon_estado` varchar(1) not null,
  `icon_fecha_creacion` timestamp not null default current_timestamp,
  `icon_fecha_modificacion` timestamp null default null,
  `icon_estado_logico` varchar(1) not null 
 ); 

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `dia`
-- 
create table if not exists `dia` (
  `dia_id` bigint(20) not null auto_increment primary key, 
  `dia_nombre` varchar(300) not null,
  `dia_descripcion` varchar(300) not null,
  `dia_usuario_ingreso` bigint(20) not null,
  `dia_usuario_modifica` bigint(20)  null,
  `dia_estado` varchar(1) not null,
  `dia_fecha_creacion` timestamp not null default current_timestamp,
  `dia_fecha_modificacion` timestamp null default null,
  `dia_estado_logico` varchar(1) not null  
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `dia`
-- 
create table if not exists `mes` (
  `mes_id` bigint(20) not null auto_increment primary key, 
  `mes_nombre` varchar(300) not null,
  `mes_descripcion` varchar(300) not null,
  `mes_usuario_ingreso` bigint(20) not null,
  `mes_usuario_modifica` bigint(20)  null,
  `mes_estado` varchar(1) not null,
  `mes_fecha_creacion` timestamp not null default current_timestamp,
  `mes_fecha_modificacion` timestamp null default null,
  `mes_estado_logico` varchar(1) not null  
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `agente_inscrito_maestria` 
-- --------------------------------------------------------
create table if not exists `agente_inscrito_maestria` (
 `aima_id` bigint(20) not null auto_increment primary key,
 `aima_nombre` varchar(100) not null,  
 `aima_descripcion` varchar(500) not null,
 `aima_usuario_ingresa` bigint(20) not null,
 `aima_usuario_modif` bigint(20) default null,
 `aima_estado` varchar(1) not null, 
 `aima_fecha_creacion` timestamp not null default current_timestamp,
 `aima_fecha_modificacion` timestamp null default null,
 `aima_estado_logico` varchar(1) not null
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `edificio`
--
create table if not exists `edificio` (
 `edi_id` bigint(20) not null auto_increment primary key,    
 `edi_descripcion` varchar(200) not null, 
 `edi_estado` varchar(1) not null,
 `edi_fecha_creacion` timestamp not null default current_timestamp,
 `edi_fecha_modificacion` timestamp null default null,
 `edi_estado_logico` varchar(1) not null
 );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `departamento`
--
CREATE TABLE if not exists `departamento` (
    `dep_id` bigint(20) not null auto_increment primary key,    
    `dep_nombre` varchar(200) not null,
    `dep_estado` varchar(1) not null,
    `dep_usuario_ingreso` bigint(20) null,
    `dep_usuario_modifica` bigint(20) null,
    `dep_fecha_creacion` timestamp not null default current_timestamp,
    `dep_fecha_modificacion` timestamp null default null,
    `dep_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `area`
--
create table if not exists `area` (
 `are_id` bigint(20) not null auto_increment primary key,   
 `dep_id` bigint(20) not null,   
 `edi_id` bigint(20) not null, 
 `are_cod` varchar(20) null,
 `are_descripcion` varchar(200) not null, 
 `are_estado` varchar(1) not null,
 `are_fecha_creacion` timestamp not null default current_timestamp,
 `are_fecha_modificacion` timestamp null default null,
 `are_estado_logico` varchar(1) not null,
 foreign key (edi_id) references `edificio`(edi_id),
 foreign key (dep_id) references `departamento`(dep_id)
 );