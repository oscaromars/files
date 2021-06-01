update db_academico.distributivo_academico 
set daca_jornada = 3
where paca_id = 10 and mod_id = 3;

create table if not exists db_academico.`configuracion_tipo_distributivo` (
 `ctdi_id` bigint(20) not null auto_increment primary key,
 `tdis_id` bigint(20) not null,
 `uaca_id` bigint(20) null,
 `mod_id` bigint(20) null,
 `ctdi_horas_inicio` integer(3) null,
 `ctdi_horas_fin` integer(3) null,
 `ctdi_estado_vigencia` varchar(1) not null,
 `ctdi_horas_semanal` integer(3) not null,
 `ctdi_estado` varchar(1) not null,
 `ctdi_fecha_creacion` timestamp not null default current_timestamp,
 `ctdi_fecha_modificacion` timestamp null default null,
 `ctdi_estado_logico` varchar(1) not null,
 foreign key (tdis_id) references `tipo_distributivo`(tdis_id),
 foreign key (uaca_id) references `unidad_academica`(uaca_id),
 foreign key (mod_id) references `modalidad`(mod_id)
);

INSERT INTO db_academico.`configuracion_tipo_distributivo` (`ctdi_id`, `tdis_id`, `uaca_id`, `mod_id`, `ctdi_horas_inicio`, `ctdi_horas_fin`, `ctdi_estado_vigencia`, `ctdi_horas_semanal`, `ctdi_estado`, `ctdi_estado_logico`) VALUES
(1, 2, null, null, null, null, '1', 2, '1', '1'),
(2, 3, null, null, null, null, '1', 2, '1', '1'),
(3, 4, null, null, null, null, '1', 2, '1', '1'),
(4, 1, 1, 1, 0, 10, '1', 2, '1', '1'),
(5, 1, 1, 1, 11, 20, '1', 3, '1', '1'),
(6, 1, 1, 1, 21, 30, '1', 4, '1', '1'),
(7, 1, 1, 1, 31, 50, '1', 5, '1', '1'),
(8, 1, 1, 1, 51, 100, '1', 7, '1', '1');

alter table db_academico.distributivo_academico add daho_id bigint(20) after mod_id;
alter table db_academico.distributivo_academico add tdis_id bigint(20) after paca_id;  
alter table db_academico.distributivo_academico add dhpa_id bigint(20) after daho_id;  
Alter table db_academico.distributivo_academico drop foreign key distributivo_academico_ibfk_6;
Alter table db_academico.distributivo_academico add foreign key (tdis_id) references tipo_distributivo (tdis_id);
Alter table db_academico.distributivo_academico add daca_num_estudiantes_online integer(3) after dhpa_id;

update db_academico.distributivo_academico a
set a.daho_id = (select daho_id from db_academico.distributivo_academico_horario where uaca_id = a.uaca_id and mod_id = a.mod_id and daho_jornada = a.daca_jornada and daho_horario = a.daca_horario)
where a.daho_id is null

-- Colocar daca_horario y daca_jornada que permitan nulos y eliminarlos despuès.
-- Colocar asi_id, uaca_id, mod_id que permitan valores nulos.

create table if not exists db_academico.`distributivo_cabecera` (
  `dcab_id` bigint(20) not null auto_increment primary key, 
  `paca_id` bigint(20) null,
  `pro_id` bigint(20) not null,
  `dcab_estado_revision` varchar(1) null,
  `dcab_observacion_revision` varchar(1000) null,
  `dcab_fecha_revision` timestamp null default null,
  `dcab_usuario_revision` bigint(20) null,
  `dcab_fecha_registro` timestamp null default null,
  `dcab_usuario_ingreso` bigint(20) not null,
  `dcab_usuario_modifica` bigint(20) null,
  `dcab_estado` varchar(1) not null,
  `dcab_fecha_creacion` timestamp not null default current_timestamp,
  `dcab_fecha_modificacion` timestamp null default null,
  `dcab_estado_logico` varchar(1) not null,
  foreign key (pro_id) references `profesor`(pro_id),
  foreign key (paca_id) references `periodo_academico`(paca_id)
);

/* verificar que la tabla distributivo_academico_horario se pase asì */
-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `distributivo_academico_horario` 
-- --------------------------------------------------------
create table if not exists `distributivo_academico_horario` (
  `daho_id` bigint(20) not null auto_increment primary key,   
  `uaca_id` bigint(20) not null,
  `mod_id` bigint(20) not null,
  `eaca_id` bigint(20) null,
  `daho_jornada` varchar(1) not null,
  `daho_descripcion` varchar(1000) null,
  `daho_horario` varchar(10) null,  
  `daho_total_horas` integer(2) null,
  `daho_estado` varchar(1) not null,
  `daho_fecha_creacion` timestamp not null default current_timestamp,
  `daho_fecha_modificacion` timestamp null default null,
  `daho_estado_logico` varchar(1) not null,  
  foreign key (uaca_id) references `unidad_academica`(uaca_id), 
  foreign key (mod_id) references `modalidad`(mod_id),
  foreign key (eaca_id) references `estudio_academico`(eaca_id)
);

-- Verificar que daho_horario estè en nulo.
alter table db_academico.distributivo_academico_horario add daho_descripcion varchar(1000) after daho_jornada;
alter table db_academico.distributivo_academico_horario add daho_total_horas integer(2) after daho_horario;
alter table db_academico.distributivo_academico_horario add eaca_id bigint(20) after mod_id;

/* Verificar que no estè en producciòn, y si està serìa de eliminar el campo fecha de clase y 
   eliminar el foreign key y crear uno nuevo */
create table if not exists `distributivo_horario_det` (
  `dhde_id` bigint(20) not null auto_increment primary key,   
  `daho_id` bigint(20) not null,
  `dia_id` bigint(20) not null,    
  `dhde_hora_inicio` varchar(10) not null,
  `dhde_hora_fin` varchar(10) not null,  
  `dhde_usuario_ingreso` bigint(20) not null,
  `dhde_usuario_modifica` bigint(20) null,  
  `dhde_estado` varchar(1) not null,
  `dhde_fecha_creacion` timestamp not null default current_timestamp,
  `dhde_fecha_modificacion` timestamp null default null,
  `dhde_estado_logico` varchar(1) not null,
  foreign key (daho_id) references `distributivo_academico_horario`(daho_id)
);

ALTER TABLE `db_academico`.`distributivo_horario_det` 
ADD CONSTRAINT `fk_distributivo_horario_detalle`
  FOREIGN KEY (`daho_id`)
  REFERENCES `db_academico`.`distributivo_academico_horario` (`daho_id`);

create table if not exists `distributivo_horario_paralelo` (
  `dhpa_id` bigint(20) not null auto_increment primary key,   
  `daho_id` bigint(20) not null, 
  `dhpa_paralelo` varchar(2) not null,  
  `dhpa_grupo` varchar(2) null,
  `dhpa_usuario_ingreso` bigint(20) not null,
  `dhpa_usuario_modifica` bigint(20) null,
  `dhpa_estado` varchar(1) not null,
  `dhpa_fecha_creacion` timestamp not null default current_timestamp,
  `dhpa_fecha_modificacion` timestamp null default null,
  `dhpa_estado_logico` varchar(1) not null,
  foreign key (daho_id) references `distributivo_academico_horario`(daho_id)
);


/*
-- Query: select * from db_academico.distributivo_academico_horario
LIMIT 0, 50000
user: vfernandez
-- Date: 2021-01-18 15:28
*/


ALTER TABLE db_academico.periodo_academico 
ADD paca_semanas_periodo int(11)  NULL
 AFTER  paca_estado_logico,
add  paca_semanas_posgrado int(11)  NULL
 AFTER paca_semanas_periodo


UPDATE `db_academico`.`periodo_academico` SET `paca_semanas_periodo` = '11', `paca_semanas_posgrado` = '4' WHERE (`paca_id` = '2');


INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (1,1,2,NULL,'1','L-M-J (09:00-11:00)','1H',6,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (2,1,2,NULL,'1','L-M-J (11:00-13:00)','2H',6,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (3,1,2,NULL,'1','L-M-J (13:30-15:30)','3H',6,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (4,1,2,NULL,'1','MIE (09:00-12:00)','4H',3,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (5,1,2,NULL,'1','VIE (09:00-12:00)','5H',3,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (6,1,2,NULL,'2','L-M-J (18:20-20:20)','1H',6,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (7,1,2,NULL,'2','L-M-J (20:20-22:20)','2H',6,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (8,1,2,NULL,'2','MIE-VIE (18:20-21:20)','3H',6,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (9,1,2,NULL,'2','MIE (18:20-21:20)','4H',3,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (10,1,2,NULL,'2','VIE (18:20-21:20)','5H',3,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (11,1,2,NULL,'2','SÁB (07:15-09:15)','6H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (12,1,3,NULL,'3','SÁB (07:15-10:15)','1H',3,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (13,1,3,NULL,'3','SÁB (10:30-13:30)','2H',3,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (14,1,3,NULL,'3','SÁB (14:30-17:30)','3H',3,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (15,1,4,NULL,'4','SÁB (08:15-10:15)','1H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (16,1,4,NULL,'4','SÁB (10:30-12:30)','2H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (17,1,4,NULL,'4','SÁB (13:30-15:30)','3H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (18,1,1,NULL,'2','LU1H (19:00-20:00)','LU1H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (19,1,1,NULL,'2','LU2H (20:00-21:00)','LU2H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (20,1,1,NULL,'2','LU3H (21:00-22:00)','LU3H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (21,1,1,NULL,'2','LU4H (19:00-20:30)','LU4H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (22,1,1,NULL,'2','LU5H (20:00-21:30)','LU5H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (23,1,1,NULL,'2','MA1H (19:00-20:00)','MA1H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (24,1,1,NULL,'2','MA2H (20:00-21:00)','MA2H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (25,1,1,NULL,'2','MA3H (21:00-22:00)','MA3H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (26,1,1,NULL,'2','MA4H (19:00-20:30)','MA4H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (27,1,1,NULL,'2','MA5H (20:00-21:30)','MA5H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (28,1,1,NULL,'2','MI1H (19:00-20:00)','MI1H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (29,1,1,NULL,'2','MI2H (20:00-21:00)','MI2H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (30,1,1,NULL,'2','MI3H (21:00-22:00)','MI3H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (31,1,1,NULL,'2','MI4H (19:00-20:30)','MI4H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (32,1,1,NULL,'2','MI5H (20:00-21:30)','MI5H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (33,1,1,NULL,'2','JU1H (19:00-20:00)','JU1H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (34,1,1,NULL,'2','JU2H (20:00-21:00)','JU2H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (35,1,1,NULL,'2','JU3H (21:00-22:00)','JU3H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (36,1,1,NULL,'2','JU4H (19:00-20:30)','JU4H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (37,1,1,NULL,'2','JU5H (20:00-21:30)','JU5H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (38,1,1,NULL,'2','VI1H (19:00-20:00)','VI1H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (39,1,1,NULL,'2','VI2H (20:00-21:00)','VI2H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (40,1,1,NULL,'2','VI3H (21:00-22:00)','VI3H',1,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (41,1,1,NULL,'2','VI4H (19:00-20:30)','VI4H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (42,1,1,NULL,'2','VI5H (20:00-21:30)','VI5H',2,'1','2020-06-05 09:48:35',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (43,2,1,15,'2','Viernes de 19:00 a 20:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (44,2,1,43,'2','Viernes de 20:00 a 21:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (45,2,1,42,'2','Viernes de 19:00 a 20:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (46,2,2,42,'2','Sábado y Domingo de 8:30 a 16:30 durante 2 fines de semana',NULL,16,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (47,2,2,46,'2','Sábado y Domingo de 8:30 a 16:30 durante 2 fines de semana',NULL,16,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (48,2,2,26,'2','Sábado y Domingo de 8:30 a 16:30 durante 2 fines de semana',NULL,16,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (49,2,1,25,'2','Lunes de 20:00 a 21:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (50,2,2,25,'2','Sábado y Domingo de 8:30 a 16:30 durante 2 fines de semana',NULL,16,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (51,2,1,47,'2','Viernes de 19:00 a 20:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (52,2,1,18,'2','Viernes de 19:00 a 20:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (53,2,2,18,'2','Sábado y Domingo de 8:30 a 16:30 durante 2 fines de semana',NULL,16,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (54,2,1,20,'2','Viernes de 20:00 a 21:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (55,2,2,15,'2','Sábado y Domingo de 8:30 a 16:30 durante 2 fines de semana',NULL,16,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (56,2,2,15,'2','Viernes de 18:00 a 22:00 - Sábado de 9:00 a 16:00 (durante 2 semanas) Viernes de 18:00 a 22:00 - Sábado de 9:00 a 13:00(tercera semana)',NULL,11,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (57,2,2,16,'2','Viernes de 18:00 a 22:00 - Sábado de 9:00 a 16:00 (durante 2 semanas) Viernes de 18:00 a 22:00 - Sábado de 9:00 a 13:00(tercera semana)',NULL,11,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (58,2,2,20,'2','Viernes de 18:00 a 22:00 - Sábado de 9:00 a 16:00 (durante 2 semanas) Viernes de 18:00 a 22:00 - Sábado de 9:00 a 13:00(tercera semana)',NULL,11,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (59,2,2,23,'2','Viernes de 18:00 a 22:00 Sábado de 9:00 a 16:00 durante 2 fines de semana',NULL,11,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (60,2,2,17,'2','Viernes de 18:00 a 22:00 - Sábado de 9:00 a 16:00 (durante 2 semanas) Viernes de 18:00 a 22:00 - Sábado de 9:00 a 13:00(tercera semana)',NULL,11,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (61,2,1,49,'2','Viernes de 19:00 a 20:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (62,2,1,48,'2','Viernes de 19:00 a 20:00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (63,2,1,24,'2','Jueves 19h00 - 20h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (64,2,1,24,'2','Jueves 20h00 - 21h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (65,2,1,24,'2','Martes 19h00 - 20h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (66,2,1,24,'2','Martes 20h00 - 21h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (67,2,1,24,'2','Miercoles 19h00 - 20h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (68,2,1,24,'2','Miercoles 20h00 - 21h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (69,2,1,24,'2','Lunes 19h00 - 20h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');
INSERT INTO `distributivo_academico_horario` (`daho_id`,`uaca_id`,`mod_id`,`eaca_id`,`daho_jornada`,`daho_descripcion`,`daho_horario`,`daho_total_horas`,`daho_estado`,`daho_fecha_creacion`,`daho_fecha_modificacion`,`daho_estado_logico`) VALUES (70,2,1,24,'2','Lunes 20h00 - 21h00',NULL,1,'1','2020-12-25 17:02:56',NULL,'1');


insert into db_academico.`distributivo_horario_det` (dhde_id, daho_id, dia_id, dhde_hora_inicio, dhde_hora_fin, dhde_usuario_ingreso, dhde_estado, dhde_estado_logico) values
(1, 1, 1, '09:00:00', '11:00:00', 1, 1, 1),
(2, 1, 2, '09:00:00', '11:00:00', 1, 1, 1),
(3, 1, 4, '09:00:00', '11:00:00', 1, 1, 1),
(4, 2, 1, '11:00:00', '13:00:00', 1, 1, 1),
(5, 2, 2, '11:00:00', '13:00:00', 1, 1, 1),
(6, 2, 4, '11:00:00', '13:00:00', 1, 1, 1),
(7, 3, 1, '13:30:00', '15:30:00', 1, 1, 1),
(8, 3, 2, '13:30:00', '15:30:00', 1, 1, 1),
(9, 3, 4, '13:30:00', '15:30:00', 1, 1, 1),
(10, 4, 3, '09:00:00', '12:00:00', 1, 1, 1),
(11, 5, 5, '09:00:00', '12:00:00', 1, 1, 1),
(12, 6, 1, '18:20:00', '20:20:00', 1, 1, 1),
(13, 6, 2, '18:20:00', '20:20:00', 1, 1, 1),
(14, 6, 4, '18:20:00', '20:20:00', 1, 1, 1),
(15, 7, 1, '20:20:00', '22:20:00', 1, 1, 1),
(16, 7, 2, '20:20:00', '22:20:00', 1, 1, 1),
(17, 7, 4, '20:20:00', '22:20:00', 1, 1, 1),
(18, 8, 3, '18:20:00', '21:20:00', 1, 1, 1),
(19, 8, 5, '18:20:00', '21:20:00', 1, 1, 1),
(20, 9, 3, '18:20:00', '21:20:00', 1, 1, 1),
(21, 10, 5, '18:20:00', '21:20:00', 1, 1, 1),
(22, 11, 6, '07:15:00', '09:15:00', 1, 1, 1), --
(23, 12, 6, '07:15:00', '10:15:00', 1, 1, 1), 
(24, 13, 6, '10:30:00', '13:30:00', 1, 1, 1), 
(25, 14, 6, '14:30:00', '17:30:00', 1, 1, 1), 
(26, 15, 6, '08:15:00', '10:15:00', 1, 1, 1), 
(27, 16, 6, '10:30:00', '12:30:00', 1, 1, 1), 
(28, 17, 6, '13:30:00', '15:30:00', 1, 1, 1),
(29, 18, 1, '19:00:00', '20:00:00', 1, 1, 1),
(30, 19, 1, '20:00:00', '21:00:00', 1, 1, 1),
(31, 20, 1, '21:00:00', '22:00:00', 1, 1, 1),
(32, 21, 1, '19:00:00', '20:30:00', 1, 1, 1),
(33, 22, 1, '20:00:00', '21:30:00', 1, 1, 1),
(34, 23, 2, '19:00:00', '20:00:00', 1, 1, 1),
(35, 24, 2, '20:00:00', '21:00:00', 1, 1, 1),
(36, 25, 2, '21:00:00', '22:00:00', 1, 1, 1),
(37, 26, 2, '19:00:00', '20:30:00', 1, 1, 1),
(38, 27, 2, '20:00:00', '21:30:00', 1, 1, 1),
(39, 28, 3, '19:00:00', '20:00:00', 1, 1, 1),
(40, 29, 3, '20:00:00', '21:00:00', 1, 1, 1),
(41, 30, 3, '21:00:00', '22:00:00', 1, 1, 1),
(42, 31, 3, '19:00:00', '20:30:00', 1, 1, 1),
(43, 32, 3, '20:00:00', '21:30:00', 1, 1, 1),
(44, 33, 4, '19:00:00', '20:00:00', 1, 1, 1),
(45, 34, 4, '20:00:00', '21:00:00', 1, 1, 1),
(46, 35, 4, '21:00:00', '22:00:00', 1, 1, 1),
(47, 36, 4, '19:00:00', '20:30:00', 1, 1, 1),
(48, 37, 4, '20:00:00', '21:30:00', 1, 1, 1),
(49, 38, 5, '19:00:00', '20:00:00', 1, 1, 1),
(50, 39, 5, '20:00:00', '21:00:00', 1, 1, 1),
(51, 40, 5, '21:00:00', '22:00:00', 1, 1, 1),
(52, 41, 5, '19:00:00', '20:30:00', 1, 1, 1),
(53, 42, 5, '20:00:00', '21:30:00', 1, 1, 1);
-- Posgrado
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (54,43,5,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (55,44,5,'20:00:00','21:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (56,45,5,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (57,46,6,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (58,46,7,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (59,47,6,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (60,47,7,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (61,48,6,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (62,48,7,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (63,49,1,'20:00:00','21:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (64,50,6,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (65,50,7,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (66,51,5,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (67,52,5,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (68,53,6,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (69,53,7,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (70,54,5,'20:00:00','21:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (71,55,6,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (72,55,7,'08:30:00','16:30:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (73,56,5,'18:00:00','22:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (74,56,6,'09:00:00','16:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (75,56,5,'09:00:00','13:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (76,57,5,'18:00:00','22:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (77,57,6,'09:00:00','16:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (78,57,5,'09:00:00','13:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (79,58,5,'18:00:00','22:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (80,58,6,'09:00:00','16:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (81,58,5,'09:00:00','13:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (82,59,5,'18:00:00','22:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (83,59,6,'09:00:00','16:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (84,60,5,'18:00:00','22:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (85,60,6,'09:00:00','16:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (86,60,5,'09:00:00','13:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (87,61,5,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (88,62,5,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (89,63,4,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (90,64,4,'20:00:00','21:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (91,65,2,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (92,66,2,'20:00:00','21:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (93,67,3,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (94,68,3,'20:00:00','21:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (95,69,1,'19:00:00','20:00:00',1,1,1);
INSERT INTO db_academico.distributivo_horario_det (`dhde_id`,`daho_id`,`dia_id`,`dhde_hora_inicio`,`dhde_hora_fin`,`dhde_usuario_ingreso`,`dhde_estado`,`dhde_estado_logico`) VALUES (96,70,1,'20:00:00','21:00:00',1,1,1);

--distributivo_horario_paralelo
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (1,1,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (2,1,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (3,2,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (4,2,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (5,3,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (6,2,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (7,4,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (8,4,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (9,5,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (10,5,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (11,6,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (12,6,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (13,7,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (14,7,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (15,8,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (16,8,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (17,9,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (18,9,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (19,10,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (20,10,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (21,11,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (22,11,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (23,12,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (24,12,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (25,13,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (26,13,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (27,14,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (28,14,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (29,15,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (30,15,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (31,16,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (32,16,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (33,17,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (34,17,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (35,18,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (36,18,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (37,19,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (38,19,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (39,20,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (40,20,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (41,21,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (42,21,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (43,22,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (44,22,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (45,23,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (46,23,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (47,24,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (48,24,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (49,25,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (50,25,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (51,26,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (52,26,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (53,27,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (54,27,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (55,28,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (56,28,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (57,29,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (58,29,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (59,30,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (60,30,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (61,31,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (62,31,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (63,32,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (64,32,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (65,33,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (66,33,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (67,34,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (68,34,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (69,35,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (70,35,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (71,36,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (72,36,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (73,37,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (74,37,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (75,38,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (76,38,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (77,39,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (78,39,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (79,40,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (80,40,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (81,41,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (82,41,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (83,42,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (84,42,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (85,43,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (86,43,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (87,44,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (88,45,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (89,46,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (90,47,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (91,48,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (92,48,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (93,49,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (94,49,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (95,50,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (96,51,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (97,52,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (98,53,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (99,54,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (100,55,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (101,55,2,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (102,56,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (103,57,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (104,58,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (105,59,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (106,60,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (107,61,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (108,62,1,'',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (109,63,1,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (110,63,2,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (111,63,3,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (112,63,4,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (113,64,5,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (114,64,6,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (115,64,7,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (116,64,8,'1',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (117,65,1,'2',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (118,65,2,'2',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (119,65,3,'2',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (120,66,4,'2',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (121,66,5,'2',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (122,66,6,'2',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (123,67,1,'3',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (124,67,2,'3',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (125,68,3,'3',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (126,68,4,'3',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (127,68,1,'4',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (128,68,1,'5',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (129,69,1,'6',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (130,69,1,'7',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (131,69,2,'7',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (132,69,1,'10',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (133,69,2,'11',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (134,70,1,'8',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (135,70,1,'9',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (136,70,1,'11',1,1,1);
INSERT INTO db_academico.distributivo_horario_paralelo (`dhpa_id`,`daho_id`,`dhpa_paralelo`,`dhpa_grupo`,`dhpa_usuario_ingreso`,`dhpa_estado`,`dhpa_estado_logico`) VALUES (137,70,1,'12',1,1,1);


ALTER TABLE `db_academico`.`periodo_academico` 
ADD COLUMN `paca_semanas_periodo` VARCHAR(45) NULL AFTER `paca_estado_logico`;

