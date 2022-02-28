ALTER TABLE `db_academico`.`distributivo_academico` 
ADD COLUMN `acca_id` BIGINT(20) NULL AFTER `uaca_id`;
-- Cambio de hora en tipo de distributivo
UPDATE `db_academico`.`tipo_distributivo` SET `tdis_num_semanas` = '3' WHERE (`tdis_id` = '3');
UPDATE `db_academico`.`tipo_distributivo` SET `tdis_num_semanas` = '3' WHERE (`tdis_id` = '4');
