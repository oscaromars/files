-- Cambio de doble espacio 
UPDATE `db_academico`.`asignatura` SET `asi_nombre` = 'Bromatología II, Composición de los Alimentos' WHERE (`asi_id` = '1331');
UPDATE `db_academico`.`asignatura` SET `asi_nombre` = 'Microbioma, Probióticos y Prebióticas' WHERE (`asi_id` = '1370');
-- Alter daca sobre carga academica, las materias principales de cada distributivo serán 1 caso contrario 0
ALTER TABLE `db_academico`.`distributivo_academico` ADD COLUMN `daca_carga_academica` BIGINT(20) NULL AFTER `tdis_id`;
ALTER TABLE `db_academico`.`distributivo_academico` ADD COLUMN `daca_asi_relacion` BIGINT(20) NULL AFTER `asi_id`;

