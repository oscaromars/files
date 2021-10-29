use db_academico;
-- Agregar campo a la tabla periodo_academico
ALTER TABLE `db_academico`.`periodo_academico`
ADD COLUMN `paca_semanas_inv_vinc_tuto` VARCHAR(45) NULL
AFTER `paca_semanas_periodo`;

/*** ESTOS ACTIVOS DESARROLLOS, VER LOS DE PRODUCCION *******/

UPDATE `db_academico`.`periodo_academico`
SET `paca_semanas_inv_vinc_tuto`='12' WHERE `paca_id`='15';

UPDATE `db_academico`.`periodo_academico`
SET `paca_semanas_inv_vinc_tuto`='12' WHERE `paca_id`='17';

UPDATE `db_academico`.`periodo_academico`
SET `paca_semanas_inv_vinc_tuto`='12' WHERE `paca_id`='27';

UPDATE `db_academico`.`periodo_academico`
SET `paca_semanas_inv_vinc_tuto`='12' WHERE `paca_id`='28';

