CREATE TABLE `periodo_academico_mensualizado` (
  `pame_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uaca_id` bigint(20) NOT NULL,
  `pame_mes` varchar(300) NOT NULL,
  `paca_id` bigint(20) NOT NULL,
  `pame_descripcion` varchar(500) DEFAULT NULL,
  `pame_usuario_ingreso` bigint(20) NOT NULL,
  `pame_usuario_modifica` bigint(20) DEFAULT NULL,
  `pame_estado` varchar(1) NOT NULL,
  `pame_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pame_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `pame_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`pame_id`),
  KEY `paca_id` (`paca_id`),
  KEY `uaca_id` (`uaca_id`),
  CONSTRAINT `periodo_academico_mensualizado_ibfk_1` FOREIGN KEY (`paca_id`) REFERENCES `periodo_academico` (`paca_id`),
  CONSTRAINT `periodo_academico_mensualizado_ibfk_2` FOREIGN KEY (`uaca_id`) REFERENCES `unidad_academica` (`uaca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1443 DEFAULT CHARSET=utf8;


/* Data de periodo academico mensualizado */
INSERT INTO `db_academico`.`periodo_academico_mensualizado` (`pame_id`, `uaca_id`, `pame_mes`, `paca_id`, `pame_descripcion`, `pame_usuario_ingreso`, `pame_estado`, `pame_fecha_creacion`, `pame_estado_logico`) VALUES ('1', '2', 'Enero', '29', 'Enero', '1', '1', '2022-01-18 00:00:00', '1');
INSERT INTO `db_academico`.`periodo_academico_mensualizado` (`pame_id`, `uaca_id`, `pame_mes`, `paca_id`, `pame_descripcion`, `pame_usuario_ingreso`, `pame_estado`, `pame_fecha_creacion`, `pame_estado_logico`) VALUES ('2', '2', 'Febrero', '29', 'Febrero', '1', '1', '2022-01-18 00:00:00', '1');
INSERT INTO `db_academico`.`periodo_academico_mensualizado` (`pame_id`, `uaca_id`, `pame_mes`, `paca_id`, `pame_descripcion`, `pame_usuario_ingreso`, `pame_estado`, `pame_fecha_creacion`, `pame_estado_logico`) VALUES ('3', '2', 'Marzo', '29', 'Marzo', '1', '1', '2022-01-18 00:00:00', '1');

/* Alter en distributivo Academcio */
ALTER TABLE `db_academico`.`distributivo_academico` ADD COLUMN `pame_id` BIGINT(20) NULL AFTER `paca_id`;
