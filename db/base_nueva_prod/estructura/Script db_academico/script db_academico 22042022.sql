SELECT * FROM db_academico.periodo_academico;
-- INSERT INTO `db_academico`.`periodo_academico` (`paca_id`, `saca_id`, `baca_id`, `paca_activo`, `paca_fecha_inicio`, `paca_fecha_fin`, `paca_usuario_ingreso`, `paca_estado`, `paca_fecha_creacion`, `paca_estado_logico`, `paca_semanas_periodo`, `paca_semanas_inv_vinc_tuto`) VALUES ('58', '42', '53', 'A', '2022-05-09 00:00:00', '2020-06-30 00:00:00', '1', '1', '2022-02-03 09:48:28', '1', '10', '12');
-- INSERT INTO `db_academico`.`periodo_academico` (`paca_id`, `saca_id`, `baca_id`, `paca_activo`, `paca_fecha_inicio`, `paca_fecha_fin`, `paca_usuario_ingreso`, `paca_estado`, `paca_fecha_creacion`, `paca_estado_logico`, `paca_semanas_periodo`, `paca_semanas_inv_vinc_tuto`) VALUES ('59', '42', '54', 'A', '2022-07-04 00:00:00', '2022-09-30 00:00:00', '1', '1', '2022-02-03 09:49:32', '1', '10', '12');
SELECT * FROM db_academico.semestre_academico;-- 42
-- INSERT INTO `db_academico`.`semestre_academico` (`saca_id`, `saca_nombre`, `saca_descripcion`, `saca_anio`, `saca_intensivo`, `saca_usuario_ingreso`, `saca_estado`, `saca_fecha_creacion`, `saca_estado_logico`) VALUES ('42', 'Mayo - Septiembre - Int', 'Mayo - Septiembre - Int', '2022', '1', '1', '1', '2020-06-11 21:29:31', '1');
SELECT * FROM db_academico.bloque_academico;-- 53, 54
-- INSERT INTO `db_academico`.`bloque_academico` (`baca_id`, `baca_nombre`, `baca_descripcion`, `baca_anio`, `baca_usuario_ingreso`, `baca_estado`, `baca_fecha_creacion`, `baca_estado_logico`) VALUES ('53', 'B1', 'Mayo - Junio', '2022', '1', '1', '2022-02-03 09:45:54', '1');
-- INSERT INTO `db_academico`.`bloque_academico` (`baca_id`, `baca_nombre`, `baca_descripcion`, `baca_anio`, `baca_usuario_ingreso`, `baca_estado`, `baca_fecha_creacion`, `baca_estado_logico`) VALUES ('54', 'B2', 'Julio - Septiembre', '2022', '1', '1', '2022-02-03 09:46:23', '1');
SELECT * FROM db_academico.planificacion;
-- INSERT INTO `db_academico`.`planificacion` (`pla_id`, `mod_id`, `per_id`, `pla_fecha_inicio`, `pla_fecha_fin`, `pla_periodo_academico`, `pla_path`, `saca_id`, `pla_estado`, `pla_fecha_creacion`, `pla_estado_logico`) VALUES ('44', '1', '1', '2022-05-09 00:00:00', '2022-09-30 23:59:59', 'Abril - Septiembre 2022 - Int.', '1', '42', '1', '2022-03-03 14:08:12', '1');
-- INSERT INTO `db_academico`.`planificacion` (`pla_id`, `mod_id`, `per_id`, `pla_fecha_inicio`, `pla_fecha_fin`, `pla_periodo_academico`, `pla_path`, `saca_id`, `pla_estado`, `pla_fecha_creacion`, `pla_estado_logico`) VALUES ('45', '2', '1', '2022-03-14 00:00:00', '2022-09-30 23:59:59', 'Abril - Septiembre 2022 - Int.', '1', '42', '1', '2022-03-03 14:08:12', '1');
SELECT * FROM db_academico.gasto_administrativo;
/* ALTER TABLE `db_academico`.`gasto_administrativo` 
ADD COLUMN `saca_id` BIGINT(20) NULL AFTER `mod_id`,
ADD INDEX `saca_id` (`saca_id` ASC);
;
ALTER TABLE `db_academico`.`gasto_administrativo` 
ADD CONSTRAINT `gasto_administrativo_ibfk_3`
  FOREIGN KEY (`saca_id`)
  REFERENCES `db_academico`.`semestre_academico` (`saca_id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;
-- Actualización de Gastos administrativos por saca
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '1');
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '2');
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '3');
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '4');
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '5');
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '6');
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '7');
UPDATE `db_academico`.`gasto_administrativo` SET `saca_id` = '16' WHERE (`gadm_id` = '8');
-- Insert de Gastos administrativos por saca intensivo
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('9', '1', '1', '42', 'B1', '20', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('10', '1', '1', '42', 'B2', '20', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('11', '1', '2', '42', 'B1', '125', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('12', '1', '2', '42', 'B2', '125', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('13', '1', '3', '42', 'B1', '125', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('14', '1', '3', '42', 'B2', '125', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('15', '1', '4', '42', 'B1', '125', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
INSERT INTO `db_academico`.`gasto_administrativo` (`gadm_id`, `uaca_id`, `mod_id`, `saca_id`, `gadm_bloque`, `gadm_gastos_varios`, `gadm_asociacion`, `gadm_fecha_inicio`, `gadm_estado_activo`, `gadm_usuario_creacion`, `gadm_estado`, `gadm_fecha_creacion`, `gadm_estado_logico`) VALUES ('16', '1', '4', '42', 'B2', '125', '0', '2022-04-22 13:33:32', '1', '1', '1', '2021-06-23 13:21:25', '1');
 
*/  
SELECT * FROM db_academico.fechas_vencimiento_pago;
/*
-- Insert de fechas vencimientos pagos con respecto a saca intensivo
INSERT INTO `db_academico`.`fechas_vencimiento_pago` (`fvpa_id`, `saca_id`, `fvpa_cuota`, `fvpa_fecha_vencimiento`, `fvpa_estado`, `fvpa_bloque`, `fvpa_fecha_creacion`, `fvpa_estado_logico`) VALUES ('32', '42', '1', '2022-05-05 00:00:00', '1', 'B1', '2022-03-05 00:00:00', '1');
INSERT INTO `db_academico`.`fechas_vencimiento_pago` (`fvpa_id`, `saca_id`, `fvpa_cuota`, `fvpa_fecha_vencimiento`, `fvpa_estado`, `fvpa_bloque`, `fvpa_fecha_creacion`, `fvpa_estado_logico`) VALUES ('33', '42', '2', '2022-06-05 00:00:00', '1', 'B1', '2022-03-05 00:00:00', '1');
INSERT INTO `db_academico`.`fechas_vencimiento_pago` (`fvpa_id`, `saca_id`, `fvpa_cuota`, `fvpa_fecha_vencimiento`, `fvpa_estado`, `fvpa_bloque`, `fvpa_fecha_creacion`, `fvpa_estado_logico`) VALUES ('34', '42', '3', '2022-07-05 00:00:00', '1', 'B1', '2022-03-05 00:00:00', '1');
INSERT INTO `db_academico`.`fechas_vencimiento_pago` (`fvpa_id`, `saca_id`, `fvpa_cuota`, `fvpa_fecha_vencimiento`, `fvpa_estado`, `fvpa_bloque`, `fvpa_fecha_creacion`, `fvpa_estado_logico`) VALUES ('35', '42', '4', '2022-08-05 00:00:00', '1', 'B2', '2022-03-05 00:00:00', '1');
INSERT INTO `db_academico`.`fechas_vencimiento_pago` (`fvpa_id`, `saca_id`, `fvpa_cuota`, `fvpa_fecha_vencimiento`, `fvpa_estado`, `fvpa_bloque`, `fvpa_fecha_creacion`, `fvpa_estado_logico`) VALUES ('36', '42', '5', '2022-09-05 00:00:00', '1', 'B2', '2022-03-05 00:00:00', '1');
*/
SELECT * FROM db_academico.registro_configuracion;
/*
INSERT INTO `db_academico`.`registro_configuracion` (`rco_id`, `pla_id`, `rco_fecha_inicio`, `rco_fecha_fin`, `rco_fecha_ini_aplicacion`, `rco_fecha_fin_aplicacion`, `rco_fecha_ini_registro`, `rco_fecha_fin_registro`, `rco_fecha_ini_periodoextra`, `rco_fecha_fin_periodoextra`, `rco_fecha_ini_clases`, `rco_fecha_fin_clases`, `rco_fecha_ini_examenes`, `rco_fecha_fin_examenes`, `rco_num_bloques`, `rco_estado`, `rco_fecha_creacion`, `rco_estado_logico`) VALUES ('23', '43', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-03-08 19:42:42', '2022-04-21 19:42:42', '2022-03-08 19:42:42', '2022-04-21 19:42:42', '0', '1', '2022-05-22 15:00:00', '1');
INSERT INTO `db_academico`.`registro_configuracion` (`rco_id`, `pla_id`, `rco_fecha_inicio`, `rco_fecha_fin`, `rco_fecha_ini_aplicacion`, `rco_fecha_fin_aplicacion`, `rco_fecha_ini_registro`, `rco_fecha_fin_registro`, `rco_fecha_ini_periodoextra`, `rco_fecha_fin_periodoextra`, `rco_fecha_ini_clases`, `rco_fecha_fin_clases`, `rco_fecha_ini_examenes`, `rco_fecha_fin_examenes`, `rco_num_bloques`, `rco_estado`, `rco_fecha_creacion`, `rco_estado_logico`) VALUES ('24', '44', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-04-25 19:42:42', '2022-05-25 19:42:42', '2022-03-08 19:42:42', '2022-04-21 19:42:42', '2022-03-08 19:42:42', '2022-04-21 19:42:42', '0', '1', '2022-05-22 15:00:00', '1');
*/
