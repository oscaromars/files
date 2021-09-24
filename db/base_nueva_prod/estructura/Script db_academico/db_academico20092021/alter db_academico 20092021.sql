ALTER TABLE `db_academico`.`registro_online` 
ADD COLUMN `ron_usuario_ingreso` BIGINT(20) NULL AFTER `ron_fecha_creacion`;
-- Roi
ALTER TABLE `db_academico`.`registro_online_item` 
ADD COLUMN `roi_usuario_ingreso` VARCHAR(45) NULL AFTER `roi_fecha_creacion`;
-- Rama
ALTER TABLE `db_academico`.`registro_adicional_materias` 
ADD COLUMN `roi_id_7` BIGINT(20) NULL DEFAULT NULL AFTER `roi_id_6`,
ADD COLUMN `roi_id_8` BIGINT(20) NULL DEFAULT NULL AFTER `roi_id_7`,
ADD COLUMN `rama_usuario_ingreso` BIGINT(20) NULL DEFAULT NULL AFTER `pfes_id`,
ADD COLUMN `rama_usuario_modifica` BIGINT(20) NULL DEFAULT NULL AFTER `rama_usuario_ingreso`;
-- Semestre intensivo
ALTER TABLE `db_academico`.`semestre_academico` 
ADD COLUMN `saca_intensivo` BIGINT(20) NULL DEFAULT NULL AFTER `saca_anio`;
UPDATE `db_academico`.`semestre_academico` SET `saca_intensivo` = '1' WHERE (`saca_id` = '8'); -- Mayo - Agosto - Ext
UPDATE `db_academico`.`semestre_academico` SET `saca_intensivo` = '1' WHERE (`saca_id` = '10');-- Marzo (Intensivo)
UPDATE `db_academico`.`semestre_academico` SET `saca_intensivo` = '1' WHERE (`saca_id` = '12'); -- Mayo  (Intensivo)

ALTER TABLE `db_academico`.`fechas_vencimiento_pago` 
CHANGE COLUMN `fvpa_paca_id` `saca_id` BIGINT(20) NULL DEFAULT NULL ;


