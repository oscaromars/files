-- DB_ACADEMICO
-- PARA EL PASE SE DEBE LIMPIAR TABLA DE horario_asignatura_periodo, horario_asignatura_periodo_tmp,
-- registro_marcacion, registro_marcacion_generada,MALLA ACADEMICA Y ASIGNATURAS
-- YA QUE LA DATA DE ASIGNATURA ES DIFERENTE, SOLO LA DATA LA ESTRUCTURA ES LA MISMA

use db_academico;

-- MALLA ACADEMICA ES NUEVA ESTRUCTURA Y MALLA_ACADEMICA_DETALLE Y NUEVA DATA TAMBIEN

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `promocion_programa`
-- --------------------------------------------------------
create table if not exists `promocion_programa` (
 `ppro_id` bigint(20) not null auto_increment primary key,
 `ppro_anio` varchar(4) not null, 
 `ppro_mes` varchar(02) not null, 
 `ppro_codigo` varchar(20) not null,
 `uaca_id` bigint(20) not null,
 `mod_id` bigint(20) not null,
 `eaca_id` bigint(20) not null,
 `ppro_num_paralelo` integer(2) not null,
 `ppro_cupo` integer(3) not null,
 `ppro_usuario_ingresa` bigint(20) null,
 `ppro_estado` varchar(1) not null, 
 `ppro_fecha_creacion` timestamp not null default current_timestamp,
 `ppro_usuario_modifica` bigint(20) null,
 `ppro_fecha_modificacion` timestamp null default null,
 `ppro_estado_logico` varchar(1) not null,
 foreign key (uaca_id) references `unidad_academica`(uaca_id),
 foreign key (mod_id) references `modalidad`(mod_id),
 foreign key (eaca_id) references `estudio_academico`(eaca_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `planificacion_academica_malla`
-- 
create table if not exists `planificacion_academica_malla` (
  `pama_id` bigint(20) not null auto_increment primary key,   
  `paca_id` bigint(20) null,  
  `ppro_id` bigint(20) null,  
  `maca_id` bigint(20) not null,  
  `pama_fecha_registro` timestamp null default null,
  `pama_usuario_ingreso` bigint(20) not null,
  `pama_usuario_modifica` bigint(20)  null,
  `pama_estado` varchar(1) not null,
  `pama_fecha_creacion` timestamp not null default current_timestamp,
  `pama_fecha_modificacion` timestamp null default null,
  `pama_estado_logico` varchar(1) not null,
  foreign key (paca_id) references `periodo_academico`(paca_id),
  foreign key (ppro_id) references `promocion_programa`(ppro_id),
  foreign key (maca_id) references `malla_academica`(maca_id)  
);


-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `matriculacion`
-- 

ALTER TABLE `db_academico`.`matriculacion` 
DROP FOREIGN KEY `matriculacion_ibfk_1`;
ALTER TABLE `db_academico`.`matriculacion` 
DROP INDEX `peac_id` ;

ALTER TABLE db_academico.`matriculacion` DROP `peac_id`;
ALTER TABLE db_academico.`matriculacion` DROP `sins_id`;

ALTER TABLE db_academico.`matriculacion`
ADD COLUMN `pama_id` bigint(20) AFTER `mat_id`;

-- crea foreing key

ALTER TABLE db_academico.`matriculacion` ADD FOREIGN KEY (pama_id) 
REFERENCES `planificacion_academica_malla`(pama_id);


-- ----------------------------------------------
--  ELIMINAR distributivo_academico
--  ELIMINAR FOREING KEY

DROP TABLE distributivo_academico; 

-- ----------------------------------------------
--  ELIMINAR planificacion_estudio_academico
--  ELIMINAR FOREING KEY

ALTER TABLE `db_academico`.`planificacion_estudio_academico` 
DROP FOREIGN KEY `planificacion_estudio_academico_ibfk_5`,
DROP FOREIGN KEY `planificacion_estudio_academico_ibfk_4`,
DROP FOREIGN KEY `planificacion_estudio_academico_ibfk_3`,
DROP FOREIGN KEY `planificacion_estudio_academico_ibfk_2`,
DROP FOREIGN KEY `planificacion_estudio_academico_ibfk_1`;
ALTER TABLE `db_academico`.`planificacion_estudio_academico` 
DROP INDEX `pami_id` ,
DROP INDEX `maca_id` ,
DROP INDEX `paca_id` ,
DROP INDEX `mod_id` ,
DROP INDEX `uaca_id` ;

-- BORRAR planificacion_estudio_academico
DROP TABLE planificacion_estudio_academico; 

-- --------------------------------------------------------
-- 
-- tabla `malla_academica_detalle`
--

-- Borrar los forening keys

ALTER TABLE `db_academico`.`malla_academica_detalle` 
DROP FOREIGN KEY `malla_academica_detalle_ibfk_5`,
DROP FOREIGN KEY `malla_academica_detalle_ibfk_4`,
DROP FOREIGN KEY `malla_academica_detalle_ibfk_3`,
DROP FOREIGN KEY `malla_academica_detalle_ibfk_2`,
DROP FOREIGN KEY `malla_academica_detalle_ibfk_1`;
ALTER TABLE `db_academico`.`malla_academica_detalle` 
DROP INDEX `fmac_id` ,
DROP INDEX `nest_id` ,
DROP INDEX `uest_id` ,
DROP INDEX `asi_id` ,
DROP INDEX `maca_id` ;

-- borrar las data

DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='1';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='2';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='3';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='4';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='5';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='6';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='7';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='8';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='9';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='10';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='11';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='12';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='13';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='14';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='15';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='16';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='17';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='18';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='19';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='20';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='21';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='22';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='23';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='24';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='25';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='26';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='27';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='28';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='29';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='30';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='31';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='32';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='33';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='34';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='35';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='36';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='37';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='38';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='39';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='40';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='41';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='42';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='43';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='44';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='45';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='46';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='47';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='48';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='49';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='50';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='51';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='52';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='53';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='54';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='55';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='56';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='57';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='58';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='59';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='60';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='61';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='62';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='63';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='64';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='65';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='66';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='67';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='68';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='69';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='70';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='71';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='72';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='73';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='74';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='75';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='76';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='77';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='78';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='79';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='80';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='81';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='82';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='83';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='84';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='85';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='86';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='87';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='88';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='89';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='90';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='91';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='92';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='93';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='94';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='95';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='96';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='97';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='98';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='99';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='100';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='101';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='102';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='103';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='104';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='105';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='106';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='107';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='108';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='109';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='110';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='111';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='112';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='113';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='114';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='115';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='116';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='117';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='118';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='119';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='120';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='121';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='122';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='123';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='124';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='125';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='126';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='127';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='128';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='129';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='130';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='131';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='132';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='133';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='134';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='135';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='136';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='137';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='138';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='139';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='140';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='141';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='142';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='143';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='144';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='145';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='146';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='147';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='148';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='149';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='150';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='151';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='152';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='153';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='154';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='155';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='156';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='157';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='158';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='159';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='160';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='161';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='162';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='163';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='164';
DELETE FROM `db_academico`.`malla_academica_detalle` WHERE `made_id`='165';

ALTER TABLE db_academico.`malla_academica_detalle`
ADD COLUMN `made_hora` integer(04) AFTER `made_codigo_asignatura`,
ADD COLUMN `made_credito` integer(2) AFTER `made_hora`;

-- crea foreing key

ALTER TABLE `malla_academica_detalle` ADD FOREIGN KEY (maca_id) REFERENCES `malla_academica`(maca_id);
ALTER TABLE `malla_academica_detalle` ADD FOREIGN KEY (asi_id) REFERENCES `asignatura`(asi_id);
ALTER TABLE `malla_academica_detalle` ADD FOREIGN KEY (uest_id) REFERENCES `unidad_estudio`(uest_id);
ALTER TABLE `malla_academica_detalle` ADD FOREIGN KEY (nest_id) REFERENCES `nivel_estudio`(nest_id);
ALTER TABLE `malla_academica_detalle` ADD FOREIGN KEY (fmac_id) REFERENCES `formacion_malla_academica`(fmac_id);

ALTER TABLE `db_academico`.`malla_academica_detalle` 
AUTO_INCREMENT = 1 ;

-- ----------------------------------------------
-- --------------------------------------------------------
-- 
-- tabla `malla_academica`
--

--  ELIMINAR FOREING KEY malla_academica

ALTER TABLE `db_academico`.`malla_academica` 
DROP FOREIGN KEY `malla_academica_ibfk_3`,
DROP FOREIGN KEY `malla_academica_ibfk_2`,
DROP FOREIGN KEY `malla_academica_ibfk_1`;
ALTER TABLE `db_academico`.`malla_academica` 
DROP INDEX `mod_id` ,
DROP INDEX `uaca_id` ,
DROP INDEX `eaca_id` ;

ALTER TABLE db_academico.`malla_academica`
ADD COLUMN `meun_id` bigint(20) AFTER `maca_id`,
ADD COLUMN `maca_codigo` varchar(50) AFTER `maca_tipo`;  

ALTER TABLE `malla_academica` ADD FOREIGN KEY (meun_id) REFERENCES `modalidad_estudio_unidad`(meun_id);

-- --------------------------------------------------------
-- 
-- tabla `semestre_academico`
--

ALTER TABLE db_academico.`semestre_academico`
ADD COLUMN `saca_anio` integer(4) AFTER `saca_descripcion`;


-- -- ------------------------ ------------------------------
--
-- Volcado de datos para la tabla `semestre`
-- 
INSERT INTO `semestre_academico` (`saca_id`, `saca_nombre`, `saca_descripcion`, `saca_anio`, `saca_fecha_registro`, `saca_usuario_ingreso`, `saca_usuario_modifica`, `saca_estado`, `saca_estado_logico`) VALUES 
(3, 'Abril - Agosto', 'Abril - Agosto', 2017, NULL, '1', '1', '1', '1'),
(4, 'Octubre - Febrero', 'Octubre - Febrero', 2018, NULL, '1', '1', '1', '1'),
(5, 'Abril - Agosto', 'Abril - Agosto', 2018, NULL, '1', '1', '1', '1'),
(6, 'Octubre - Febrero', 'Octubre - Febrero', 2020, NULL, '1', '1', '1', '1');

-- ingresa anio a los id 1 y 2 

update db_academico.`semestre_academico` 
set saca_anio = '2019' where saca_id > 0 and saca_id < 3

-- --------------------------------------------------------------------
-- 
-- tabla `bloque_academico`
--

ALTER TABLE db_academico.`bloque_academico`
ADD COLUMN `baca_anio` integer(4) AFTER `baca_descripcion`;

update db_academico.`bloque_academico`
set baca_anio = '2019' where baca_id > 0 and baca_id < 5

-- VERIFICAR SI SE DEBE BORRAR LOS ID DEL 5 AL 16 Y VOLVER A PONER EL SECUENCIAL 
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='5';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='6';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='7';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='8';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='9';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='10';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='11';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='12';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='13';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='14';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='15';
DELETE FROM `db_academico`.`bloque_academico` WHERE `baca_id`='16';

ALTER TABLE `db_academico`.`bloque_academico` 
AUTO_INCREMENT = 5 ;


-- ---------------------------------------------------------------------
-- 
-- tabla `periodo_academico`
--
--  eliminar paca_anio_academico

ALTER TABLE db_academico.`periodo_academico` DROP `paca_anio_academico`; 


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `dedicacion_docente`
--
create table if not exists `dedicacion_docente` (
 `ddoc_id` bigint(20) not null auto_increment primary key,
 `ddoc_nombre` varchar(100) default null,
 `ddoc_estado` varchar(1) not null,
 `ddoc_fecha_creacion` timestamp not null default current_timestamp,
 `ddoc_fecha_modificacion` timestamp null default null,
 `ddoc_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
--
-- Volcado de datos para la tabla `dedicacion_docente`
--
INSERT INTO `dedicacion_docente` (`ddoc_id`, `ddoc_nombre`, `ddoc_estado`, `ddoc_fecha_creacion`, `ddoc_fecha_modificacion`, `ddoc_estado_logico`) VALUES
(1, 'Tiempo Completo', '1', '2019-11-18 12:03:06',NULL, '1'),
(2, 'Tiempo Parcial', '1', '2019-11-18 12:036:06',NULL, '1'),
(3, 'Medio Tiempo', '1', '2019-11-18 12:036:06',NULL, '1');

-- --------------------------------------------------------------------
-- 
-- tabla `profesor`
--
-- crear 2 campos

ALTER TABLE db_academico.`profesor`
ADD COLUMN `pro_fecha_contratacion` timestamp NULL AFTER `per_id`,
ADD COLUMN `pro_fecha_terminacion` timestamp NULL AFTER `pro_fecha_contratacion`;



-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_instruccion`
-- 
create table if not exists `profesor_instruccion` (
  `pins_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null, 
  `nins_id` bigint(20) not null,
  `pins_institucion` varchar(150) null,
  `pins_especializacion` varchar(150) null,
  `pins_titulo` varchar(200) not null,
  `pins_senescyt` varchar(50) null,
  `pins_usuario_ingreso` bigint(20) not null,
  `pins_usuario_modifica` bigint(20)  null,
  `pins_estado` varchar(1) not null,
  `pins_fecha_creacion` timestamp not null default current_timestamp,
  `pins_fecha_modificacion` timestamp null default null,
  `pins_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (nins_id) references `nivel_instruccion`(nins_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_exp_doc`
-- 
create table if not exists `profesor_exp_doc` (
  `pedo_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `ins_id` bigint(20) not null,
  `pedo_fecha_inicio` timestamp null default null,
  `pedo_fecha_fin` timestamp null default null,
  `pedo_denominacion` varchar(100) not null,
  `pedo_asignaturas` varchar(200) not null,
  `pedo_usuario_ingreso` bigint(20) not null,
  `pedo_usuario_modifica` bigint(20)  null,
  `pedo_estado` varchar(1) not null,
  `pedo_fecha_creacion` timestamp not null default current_timestamp,
  `pedo_fecha_modificacion` timestamp null default null,
  `pedo_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_exp_prof`
-- 
create table if not exists `profesor_exp_prof` (
  `pepr_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pepr_fecha_inicio` timestamp null default null,
  `pepr_fecha_fin` timestamp null default null,
  `pepr_organizacion` varchar(200) not null,
  `pepr_denominacion` varchar(100) not null,
  `pepr_funciones` varchar(200) not null,
  `pepr_usuario_ingreso` bigint(20) not null,
  `pepr_usuario_modifica` bigint(20)  null,
  `pepr_estado` varchar(1) not null,
  `pepr_fecha_creacion` timestamp not null default current_timestamp,
  `pepr_fecha_modificacion` timestamp null default null,
  `pepr_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_idiomas`
-- 
create table if not exists `profesor_idiomas` (
  `pidi_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `idi_id` bigint(20) not null,
  `pidi_nivel_escrito` varchar(200) not null,
  `pidi_nivel_oral` varchar(100) not null,
  `pidi_certificado` varchar(200) not null,
  `pidi_institucion` varchar(200) not null,
  `pidi_usuario_ingreso` bigint(20) not null,
  `pidi_usuario_modifica` bigint(20)  null,
  `pidi_estado` varchar(1) not null,
  `pidi_fecha_creacion` timestamp not null default current_timestamp,
  `pidi_fecha_modificacion` timestamp null default null,
  `pidi_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_investigacion`
-- 
create table if not exists `profesor_investigacion` (
  `pinv_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pinv_proyecto` varchar(200) not null,
  `pinv_ambito` varchar(100) not null,
  `pinv_responsabilidad` varchar(200) not null,
  `pinv_entidad` varchar(200) not null,
  `pinv_anio` varchar(4) not null,
  `pinv_duracion` varchar(20) not null,
  `pinv_usuario_ingreso` bigint(20) not null,
  `pinv_usuario_modifica` bigint(20)  null,
  `pinv_estado` varchar(1) not null,
  `pinv_fecha_creacion` timestamp not null default current_timestamp,
  `pinv_fecha_modificacion` timestamp null default null,
  `pinv_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_capacitacion`
-- 
create table if not exists `profesor_capacitacion` (
  `pcap_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pcap_evento` varchar(200) not null,
  `pcap_institucion` varchar(200) not null,
  `pcap_anio` varchar(4) not null,
  `pcap_tipo` varchar(200) not null,
  `pcap_duracion` varchar(20) not null,
  `pcap_usuario_ingreso` bigint(20) not null,
  `pcap_usuario_modifica` bigint(20)  null,
  `pcap_estado` varchar(1) not null,
  `pcap_fecha_creacion` timestamp not null default current_timestamp,
  `pcap_fecha_modificacion` timestamp null default null,
  `pcap_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_conferencia`
-- 
create table if not exists `profesor_conferencia` (
  `pcon_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pcon_evento` varchar(200) not null,
  `pcon_institucion` varchar(200) not null,
  `pcon_anio` varchar(4) not null,
  `pcon_ponencia` varchar(200) not null,
  `pcon_usuario_ingreso` bigint(20) not null,
  `pcon_usuario_modifica` bigint(20)  null,
  `pcon_estado` varchar(1) not null,
  `pcon_fecha_creacion` timestamp not null default current_timestamp,
  `pcon_fecha_modificacion` timestamp null default null,
  `pcon_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_publicacion`
-- 
create table if not exists `profesor_publicacion` (
  `ppub_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `ppub_produccion` varchar(100) not null,
  `ppub_titulo` varchar(200) not null,
  `ppub_editorial` varchar(50) not null,
  `ppub_isbn` varchar(50) not null,
  `ppub_autoria` varchar(200) not null,
  `ppub_usuario_ingreso` bigint(20) not null,
  `ppub_usuario_modifica` bigint(20)  null,
  `ppub_estado` varchar(1) not null,
  `ppub_fecha_creacion` timestamp not null default current_timestamp,
  `ppub_fecha_modificacion` timestamp null default null,
  `ppub_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_coordinacion`
-- 
create table if not exists `profesor_coordinacion` (
  `pcoo_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pcoo_alumno` varchar(100) not null,
  `pcoo_programa` varchar(100) not null,
  `pcoo_academico` varchar(100) not null,
  `pcoo_institucion` varchar(200) not null,
  `pcoo_anio` varchar(4) not null,
  `pcoo_usuario_ingreso` bigint(20) not null,
  `pcoo_usuario_modifica` bigint(20)  null,
  `pcoo_estado` varchar(1) not null,
  `pcoo_fecha_creacion` timestamp not null default current_timestamp,
  `pcoo_fecha_modificacion` timestamp null default null,
  `pcoo_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_evaluacion`
-- 
create table if not exists `profesor_evaluacion` (
  `peva_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `peva_periodo` varchar(100) not null,
  `peva_institucion` varchar(100) not null,
  `peva_evaluacion` varchar(100) not null,
  `peva_usuario_ingreso` bigint(20) not null,
  `peva_usuario_modifica` bigint(20)  null,
  `peva_estado` varchar(1) not null,
  `peva_fecha_creacion` timestamp not null default current_timestamp,
  `peva_fecha_modificacion` timestamp null default null,
  `peva_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesor_referencia`
-- 
create table if not exists `profesor_referencia` (
  `pref_id` bigint(20) not null auto_increment primary key,
  `pro_id` bigint(20) not null,
  `pref_contacto` varchar(100) not null,
  `pref_relacion_cargo` varchar(100) not null,
  `pref_organizacion` varchar(100) not null,
  `pref_numero` varchar(100) not null,
  `pref_usuario_ingreso` bigint(20) not null,
  `pref_usuario_modifica` bigint(20)  null,
  `pref_estado` varchar(1) not null,
  `pref_fecha_creacion` timestamp not null default current_timestamp,
  `pref_fecha_modificacion` timestamp null default null,
  `pref_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `docente_estudios`
--
create table if not exists `docente_estudios` (
 `dest_id` bigint(20) not null auto_increment primary key,
 `dest_observacion` text default null,
 `dest_estado` varchar(1) not null,
 `dest_fecha_creacion` timestamp not null default current_timestamp,
 `dest_fecha_modificacion` timestamp null default null,
 `dest_estado_logico` varchar(1) not null 
);

-- -----------------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `paralelo_promocion_programa`
-- ----------------------------------------------------------------
create table if not exists `paralelo_promocion_programa` (
 `pppr_id` bigint(20) not null auto_increment primary key,
 `ppro_id` bigint(20) not null, 
 `pppr_cupo` integer(3) not null, 
 `pppr_cupo_actual` integer(3) null,  
 `pppr_descripcion` varchar(100) null,  
 `pppr_usuario_ingresa` bigint(20) null,
 `pppr_estado` varchar(1) not null, 
 `pppr_fecha_creacion` timestamp not null default current_timestamp,
 `pppr_usuario_modifica` bigint(20) null,
 `pppr_fecha_modificacion` timestamp null default null,
 `pppr_estado_logico` varchar(1) not null,
 foreign key (ppro_id) references `promocion_programa`(ppro_id) 
);

-- ----------------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `planifica_academic_malla_det`
-- 
create table if not exists `planifica_academic_malla_det` (
  `pamd_id` bigint(20) not null auto_increment primary key,   
  `pama_id` bigint(20) null,    
  `made_id` bigint(20) not null,
  `pamd_usuario_ingreso` bigint(20) not null,
  `pamd_usuario_modifica` bigint(20) null,
  `pamd_estado` varchar(1) not null,
  `pamd_fecha_creacion` timestamp not null default current_timestamp,
  `pamd_fecha_modificacion` timestamp null default null,
  `pamd_estado_logico` varchar(1) not null,
  foreign key (pama_id) references `planificacion_academica_malla`(pama_id),
  foreign key (made_id) references `malla_academica_detalle`(made_id) 
);

-- --------------------------------------------------------
--  
-- Estructura de tabla para la tabla `paralelo_planificacion`
-- 
create table if not exists `paralelo_planificacion` (
  `ppla_id` bigint(20) not null auto_increment primary key,   
  `pamd_id` bigint(20) not null,
  `pppr_id` bigint(20) null, 
  `ppla_nombre` varchar(300) not null,
  `ppla_num_cupo` int not null,
  `ppla_usuario_ingreso` bigint(20) not null,
  `ppla_usuario_modifica` bigint(20)  null,   
  `ppla_estado` varchar(1) not null,
  `ppla_fecha_creacion` timestamp not null default current_timestamp,
  `ppla_fecha_modificacion` timestamp null default null,
  `ppla_estado_logico` varchar(1) not null,  
  foreign key (pamd_id) references `planifica_academic_malla_det`(pamd_id),
  foreign key (pppr_id) references `paralelo_promocion_programa`(pppr_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `distributivo_academico`
-- 
create table if not exists `distributivo_academico` (
  `daca_id` bigint(20) not null auto_increment primary key, 
  `pamd_id` bigint(20) not null,
  `pro_id` bigint(20) not null,  
  `ppla_id` bigint(20) not null,  
  `daca_fecha_registro` timestamp null default null,
  `daca_usuario_ingreso` bigint(20) not null,
  `daca_usuario_modifica` bigint(20)  null,
  `daca_estado` varchar(1) not null,
  `daca_fecha_creacion` timestamp not null default current_timestamp,
  `daca_fecha_modificacion` timestamp null default null,
  `daca_estado_logico` varchar(1) not null,  
  foreign key (pamd_id) references `planifica_academic_malla_det`(pamd_id),
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (ppla_id) references `paralelo_planificacion`(ppla_id)
);

-- ---------------------------------------------------------------------
-- 
-- tabla `distributivo_horario`
--
--  eliminar dia_id, dhor_hora_inicio, dhor_hora_fin, dhor_descripcion, dhor_fecha_registro

ALTER TABLE db_academico.`distributivo_horario` DROP `dia_id`; 
ALTER TABLE db_academico.`distributivo_horario` DROP `dhor_hora_inicio`;
ALTER TABLE db_academico.`distributivo_horario` DROP `dhor_hora_fin`;
ALTER TABLE db_academico.`distributivo_horario` DROP `dhor_descripcion`;
ALTER TABLE db_academico.`distributivo_horario` DROP `dhor_fecha_registro`;

ALTER TABLE db_academico.`distributivo_horario`
ADD COLUMN `ppla_id` bigint(20) AFTER `dhor_id`;

-- crea foreing key 

ALTER TABLE `distributivo_horario` ADD FOREIGN KEY (ppla_id) REFERENCES `paralelo_planificacion`(ppla_id);
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `distributivo_horario_det`

create table if not exists `distributivo_horario_det` (
  `dhde_id` bigint(20) not null auto_increment primary key,   
  `dhor_id` bigint(20) not null,
  `dia_id` bigint(20) not null,  
  `dhde_fecha_clase` timestamp null default null,
  `dhde_hora_inicio` varchar(10) not null,
  `dhde_hora_fin` varchar(10) not null,  
  `dhde_usuario_ingreso` bigint(20) not null,
  `dhde_usuario_modifica` bigint(20) null,  
  `dhde_estado` varchar(1) not null,
  `dhde_fecha_creacion` timestamp not null default current_timestamp,
  `dhde_fecha_modificacion` timestamp null default null,
  `dhde_estado_logico` varchar(1) not null,
  foreign key (dhor_id) references `distributivo_horario`(dhor_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `marcacion_detalle_horario` 
-- --------------------------------------------------------
create table if not exists `marcacion_detalle_horario` (
  `mdho_id` bigint(20) not null auto_increment primary key,   
  `dhde_id` bigint(20) not null,  
  `mdho_fecha_hora_entrada` timestamp null,    
  `mdho_fecha_hora_salida` timestamp null,  
  `mdho_direccion_ip` varchar(20) not null,
  `mdho_usuario_ingreso` bigint(20) not null,
  `mdho_usuario_modifica` bigint(20) null,  
  `mdho_estado` varchar(1) not null,
  `mdho_fecha_creacion` timestamp not null default current_timestamp,
  `mdho_fecha_modificacion` timestamp null default null,
  `mdho_estado_logico` varchar(1) not null,  
  foreign key (dhde_id) references `distributivo_horario_det`(dhde_id)
);


-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `detalle_matriculacion`
-- 
create table if not exists `detalle_matriculacion` (
  `dmat_id` bigint(20) not null auto_increment primary key, 
  `mat_id` bigint(20) not null,
  `ppla_id` bigint(20) not null,  
  `dmat_usuario_ingreso` bigint(20) not null,
  `dmat_usuario_modifica` bigint(20)  null,  
  `dmat_estado` varchar(1) not null,
  `dmat_fecha_creacion` timestamp not null default current_timestamp,
  `dmat_fecha_modificacion` timestamp null default null,
  `dmat_estado_logico` varchar(1) not null,
  foreign key (mat_id) references `matriculacion`(mat_id),
  foreign key (ppla_id) references `paralelo_planificacion`(ppla_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `documento_aceptacion`
-- --------------------------------------------------------
create table if not exists `documento_aceptacion` (
 `dace_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null, 
 `dadj_id` bigint(20) not null, 
 `dace_archivo` varchar(500) not null, 
 `dace_observacion` varchar(500) null, 
 `dace_fecha_maxima_aprobacion` timestamp null, 
 `dace_estado_aprobacion` varchar(1) not null, 
 `dace_usuario_ingreso` bigint(20) null,
 `dace_usuario_modifica` bigint(20) null,
 `dace_estado` varchar(1) not null, 
 `dace_fecha_creacion` timestamp not null default current_timestamp,
 `dace_fecha_modificacion` timestamp null default null,
 `dace_estado_logico` varchar(1) not null
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `observaciones_documento_aceptacion`
-- --------------------------------------------------------
create table if not exists `observaciones_documento_aceptacion` (
 `odac_id` bigint(20) not null auto_increment primary key,
 `odac_descripcion` varchar(500) not null, 
 `odac_usuario_ingreso` bigint(20) null,
 `odac_usuario_modifica` bigint(20) null,
 `odac_estado` varchar(1) not null, 
 `odac_fecha_creacion` timestamp not null default current_timestamp,
 `odac_fecha_modificacion` timestamp null default null,
 `odac_estado_logico` varchar(1) not null
);

INSERT INTO `observaciones_documento_aceptacion` (`odac_id`, `odac_descripcion`, `odac_usuario_ingreso`, `odac_usuario_modifica`, `odac_estado`, `odac_fecha_creacion`, `odac_fecha_modificacion`, `odac_estado_logico`) VALUES
(1, 'Mal formato del documento', 1, NULL, '1', '2019-05-20 02:20:17', NULL, '1'),
(2, 'Documento borroso', 1, NULL, '1', '2019-05-20 02:20:17', NULL, '1'),
(3, 'No es la carta correspondiente', 1, NULL, '1', '2019-05-20 02:20:17', NULL, '1');

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `observaciones_documento_aceptacion`
-- --------------------------------------------------------
create table if not exists `observaciones_por_documento_aceptacion` (
 `opda_id` bigint(20) not null auto_increment primary key,
 `odac_id` bigint(20) not null, 
 `dace_id` bigint(20) not null, 
 `opda_usuario_ingreso` bigint(20) null,
 `opda_usuario_modifica` bigint(20) null,
 `opda_estado` varchar(1) not null, 
 `opda_fecha_creacion` timestamp not null default current_timestamp,
 `opda_fecha_modificacion` timestamp null default null,
 `opda_estado_logico` varchar(1) not null,
 foreign key (odac_id) references `observaciones_documento_aceptacion`(odac_id),
 foreign key (dace_id) references `documento_aceptacion`(dace_id)
);

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `matriculacion_programa_inscrito`
-- --------------------------------------------------------
create table if not exists `matriculacion_programa_inscrito` (
 `mpin_id` bigint(20) not null auto_increment primary key,
 `ppro_id` bigint(20) not null, 
 `adm_id` bigint(20) not null, 
 `mpin_fecha_matriculacion` timestamp not null,
 `mpin_ficha` varchar(1) null, -- 'S', 'N'  
 `mpin_fecha_registro_ficha` timestamp null,
 `mpin_usuario_ingresa` bigint(20) null,
 `mpin_estado` varchar(1) not null, 
 `mpin_fecha_creacion` timestamp not null default current_timestamp,
 `mpin_usuario_modifica` bigint(20) null,
 `mpin_fecha_modificacion` timestamp null default null,
 `mpin_estado_logico` varchar(1) not null,
 foreign key (ppro_id) references `promocion_programa`(ppro_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `distributivo`
--
create table if not exists `distributivo` (
 `dis_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `asi_id` bigint(20) not null, 
 `dis_descripcion` varchar(100) null,
 `saca_id` bigint(20) not null,
 `ddoc_id` bigint(20) not null,
 `dis_declarado` varchar(1) not null,
 `dis_estado` varchar(1) not null,
 `dis_fecha_creacion` timestamp not null default current_timestamp,
 `dis_fecha_modificacion` timestamp null default null,
 `dis_estado_logico` varchar(1) not null,
 foreign key (saca_id) references `semestre_academico`(saca_id),
 foreign key (pro_id) references `profesor`(pro_id),
 foreign key (asi_id) references `asignatura`(asi_id),
 foreign key (ddoc_id) references `dedicacion_docente`(ddoc_id)
);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_evaluacion`
--
create table if not exists `tipo_evaluacion` (
 `teva_id` bigint(20) not null auto_increment primary key,
 `teva_nombre` varchar(250) default null,
 `teva_estado` varchar(1) not null,
 `teva_fecha_creacion` timestamp not null default current_timestamp,
 `teva_fecha_modificacion` timestamp null default null,
 `teva_estado_logico` varchar(1) not null
);

-- -- ------------------------ ------------------------------
--
-- Volcado de datos para la tabla `tipo_evaluacion`
-- 
INSERT INTO `tipo_evaluacion` (`teva_id`, `teva_nombre`,  `teva_estado`, `teva_estado_logico`) VALUES 

(1, 'Docencia', '1', '1'),
(2, 'Investigación', '1', '1'),
(3, 'Dirección y Gestión Académica', '1', '1');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `resumen_evaluacion_docente`
--
create table if not exists `resumen_evaluacion_docente` (
 `redo_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `saca_id` bigint(20) not null,
 `teva_id` bigint(20) not null,
 `redo_cant_horas` double default null,
 `redo_puntaje_evaluacion` double default null,
 `redo_estado` varchar(1) not null,
 `redo_fecha_creacion` timestamp not null default current_timestamp,
 `redo_fecha_modificacion` timestamp null default null,
 `redo_estado_logico` varchar(1) not null,
 foreign key (saca_id) references `semestre_academico`(saca_id),
 foreign key (pro_id) references `profesor`(pro_id),
 foreign key (teva_id) references `tipo_evaluacion`(teva_id)
);


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `resumen_resultado_evaluacion`
--
create table if not exists `resumen_resultado_evaluacion` (
 `rreva_id` bigint(20) not null auto_increment primary key,
 `pro_id` bigint(20) not null,
 `saca_id` bigint(20) not null,
 `rreva_evaluacion_completa` varchar(1) not null, -- 1 completa o 0 incompleta
 `rreva_total_hora` double default null,
 `rreva_total_evaluacion` double default null,
 `rreva_estado` varchar(1) not null,
 `rreva_fecha_creacion` timestamp not null default current_timestamp,
 `rreva_fecha_modificacion` timestamp null default null,
 `rreva_estado_logico` varchar(1) not null,
 foreign key (saca_id) references `semestre_academico`(saca_id),
 foreign key (pro_id) references `profesor`(pro_id)
);

---------------------------------
-- ELIMINAR ASIGNACION PARALELO
--  ELIMANAR FORENG KEY

ALTER TABLE `db_academico`.`asignacion_paralelo` 
DROP FOREIGN KEY `asignacion_paralelo_ibfk_3`,
DROP FOREIGN KEY `asignacion_paralelo_ibfk_2`,
DROP FOREIGN KEY `asignacion_paralelo_ibfk_1`;
ALTER TABLE `db_academico`.`asignacion_paralelo` 
DROP INDEX `mest_id` ,
DROP INDEX `mat_id` ,
DROP INDEX `par_id` ;

-- BORRAR ASIGNACION PARALELO
DROP TABLE asignacion_paralelo;


ALTER TABLE `db_academico`.`estudio_academico_area_conocimiento` 
RENAME TO  `db_academico`.`estudio_acad_area_con` ;


-- ------------------------------------------------------------
-- BORRAR modalidad_unidad_academico
DROP TABLE modalidad_unidad_academico;


-- ----------------------------------------------

-- BORRAR modulo_estudio_empresa
DROP TABLE modulo_estudio_empresa;	

-- ----------------------------------------------
-- BORRAR paralelo
DROP TABLE paralelo;

-- BORRAR periodo_academico_met_ingreso
DROP TABLE periodo_academico_met_ingreso;

--- BORRAR foreing key  HORARIO_ASIGNATURA_PERIODO
ALTER TABLE `db_academico`.`horario_asignatura_periodo` 
DROP FOREIGN KEY `horario_asignatura_periodo_ibfk_5`,
DROP FOREIGN KEY `horario_asignatura_periodo_ibfk_4`,
DROP FOREIGN KEY `horario_asignatura_periodo_ibfk_3`,
DROP FOREIGN KEY `horario_asignatura_periodo_ibfk_2`,
DROP FOREIGN KEY `horario_asignatura_periodo_ibfk_1`;
ALTER TABLE `db_academico`.`horario_asignatura_periodo` 
DROP INDEX `mod_id` ,
DROP INDEX `uaca_id` ,
DROP INDEX `pro_id` ,
DROP INDEX `paca_id` ,
DROP INDEX `asi_id` ;

-- BORRAR DATA ASIGNATURA
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='1';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='2';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='3';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='4';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='5';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='6';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='7';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='8';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='9';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='10';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='11';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='12';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='13';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='14';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='15';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='16';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='17';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='18';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='19';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='20';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='21';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='22';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='23';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='24';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='25';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='26';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='27';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='28';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='29';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='30';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='31';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='32';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='33';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='34';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='35';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='36';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='37';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='38';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='39';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='40';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='41';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='42';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='43';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='44';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='45';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='46';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='47';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='48';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='49';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='50';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='51';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='52';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='53';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='54';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='55';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='56';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='57';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='58';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='59';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='60';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='61';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='62';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='63';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='64';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='65';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='66';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='67';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='68';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='69';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='70';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='71';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='72';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='73';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='74';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='75';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='76';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='77';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='78';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='79';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='80';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='81';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='82';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='83';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='84';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='85';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='86';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='87';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='88';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='89';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='90';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='91';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='92';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='93';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='94';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='95';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='96';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='97';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='98';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='99';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='100';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='101';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='102';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='103';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='104';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='105';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='106';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='107';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='108';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='109';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='110';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='111';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='112';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='113';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='114';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='115';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='116';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='117';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='118';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='119';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='120';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='121';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='122';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='123';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='124';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='125';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='126';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='127';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='128';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='129';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='130';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='131';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='132';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='133';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='134';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='135';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='136';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='137';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='138';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='139';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='140';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='141';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='142';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='143';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='144';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='145';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='146';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='147';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='148';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='149';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='150';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='151';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='152';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='153';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='154';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='155';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='156';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='157';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='158';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='159';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='160';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='161';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='162';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='163';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='164';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='165';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='166';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='167';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='168';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='169';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='170';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='171';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='172';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='173';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='174';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='175';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='176';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='177';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='178';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='179';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='180';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='181';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='182';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='183';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='184';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='185';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='186';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='187';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='188';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='189';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='190';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='191';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='192';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='193';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='194';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='195';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='196';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='197';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='198';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='199';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='200';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='201';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='202';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='203';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='204';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='205';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='206';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='207';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='208';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='209';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='210';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='211';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='212';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='213';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='214';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='215';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='216';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='217';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='218';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='219';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='220';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='221';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='222';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='223';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='224';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='225';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='226';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='227';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='228';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='229';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='230';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='231';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='232';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='233';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='234';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='235';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='236';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='237';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='238';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='239';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='240';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='241';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='242';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='243';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='244';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='245';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='246';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='247';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='248';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='249';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='250';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='251';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='252';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='253';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='254';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='255';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='256';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='257';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='258';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='259';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='260';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='261';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='262';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='263';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='264';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='265';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='266';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='267';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='268';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='269';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='270';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='271';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='272';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='273';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='274';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='275';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='276';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='277';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='278';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='279';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='280';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='281';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='282';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='283';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='284';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='285';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='286';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='287';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='288';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='289';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='290';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='291';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='292';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='293';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='294';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='295';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='296';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='297';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='298';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='299';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='300';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='301';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='302';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='303';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='304';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='305';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='306';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='307';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='308';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='309';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='310';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='311';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='312';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='313';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='314';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='315';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='316';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='317';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='318';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='319';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='320';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='321';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='322';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='323';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='324';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='325';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='326';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='327';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='328';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='329';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='330';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='331';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='332';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='333';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='334';
DELETE FROM `db_academico`.`asignatura` WHERE `asi_id`='335';

-- AGREGAR COLUMNA
ALTER TABLE db_academico.`asignatura`
ADD COLUMN `uaca_id` bigint(20) AFTER `scon_id`;  

-- AGREGAR FORWING KEY
ALTER TABLE db_academico.`asignatura` ADD FOREIGN KEY (uaca_id) 
REFERENCES `unidad_academica`(uaca_id);


-- ------------------------------------------------------

-- QUE SE VA A HACER CON LAS TABLAS REGISTRO DE MARCACION Y REGISTRO MARCACION GENERADA
-- SOLO SE ESCONDE LA LLAMADA DEL MENU





