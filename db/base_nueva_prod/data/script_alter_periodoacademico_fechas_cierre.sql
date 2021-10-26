ALTER TABLE `db_academico`.`periodo_academico` 
ADD COLUMN `paca_fecha_cierre_ini` TIMESTAMP NULL DEFAULT NULL AFTER `paca_fecha_fin`,
ADD COLUMN `paca_fecha_cierre_fin` TIMESTAMP NULL DEFAULT NULL AFTER `paca_fecha_cierre_ini`;


