-- SELECT * FROM db_academico.componente;
UPDATE `db_academico`.`componente` SET `com_nombre` = 'Evaluación', `com_descripcion` = 'Evaluación' WHERE (`com_id` = '3');
UPDATE `db_academico`.`componente` SET `com_nombre` = 'Evaluación en línea', `com_descripcion` = 'Evaluación en línea' WHERE (`com_id` = '5');
UPDATE `db_academico`.`componente_unidad` SET `com_id` = '6' WHERE (`cuni_id` = '5');
UPDATE `db_academico`.`componente_unidad` SET `com_id` = '7' WHERE (`cuni_id` = '11');
UPDATE `db_academico`.`componente_unidad` SET `com_id` = '7' WHERE (`cuni_id` = '14');
