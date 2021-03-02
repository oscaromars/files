ALTER TABLE `db_academico`.`planificacion` 
CHANGE COLUMN `pla_fecha_inicio` `pla_fecha_inicio` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
CHANGE COLUMN `pla_fecha_fin` `pla_fecha_fin` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE `db_academico`.`planificacion` 
CHANGE COLUMN `pla_path` `pla_path` TEXT NULL ;

/**************************************************************************/
/*   planificacion_estudiante                                             */
/**************************************************************************/

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b1_h1` VARCHAR(2) NULL AFTER `pes_mat_b1_h1_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b1_h2` VARCHAR(2) NULL AFTER `pes_mat_b1_h2_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b1_h3` VARCHAR(2) NULL AFTER `pes_mat_b1_h3_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b1_h4` VARCHAR(2) NULL AFTER `pes_mat_b1_h4_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b1_h5` VARCHAR(2) NULL AFTER `pes_mat_b1_h5_cod`;

/****************************************************************************/

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b2_h1` VARCHAR(2) NULL AFTER `pes_mat_b2_h1_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b2_h2` VARCHAR(2) NULL AFTER `pes_mat_b2_h2_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b2_h3` VARCHAR(2) NULL AFTER `pes_mat_b2_h3_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b2_h4` VARCHAR(2) NULL AFTER `pes_mat_b2_h4_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b2_h5` VARCHAR(2) NULL AFTER `pes_mat_b2_h5_cod`;

/*********************************************************************/

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mat_b1_h6_nombre` VARCHAR(100) NULL AFTER `pes_mod_b1_h5`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mat_b1_h6_cod` VARCHAR(20) NULL AFTER `pes_mat_b1_h6_nombre`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b1_h6` VARCHAR(2) NULL AFTER `pes_mat_b1_h6_cod`;

/*********************************************************************/

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mat_b2_h6_nombre` VARCHAR(100) NULL AFTER `pes_mod_b2_h5`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mat_b2_h6_cod` VARCHAR(20) NULL AFTER `pes_mat_b2_h6_nombre`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_mod_b2_h6` VARCHAR(2) NULL AFTER `pes_mat_b2_h6_cod`;

/****************************************************************************/

/***** cambios ultimos pedidos en la reunion para la carga de archivos *****/

/***************************************************************************/
ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_cod_malla` VARCHAR(50) NULL AFTER `pes_tutoria_cod`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b1_h1` VARCHAR(20) NULL AFTER `pes_mod_b1_h1`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b1_h2` VARCHAR(20) NULL AFTER `pes_mod_b1_h2`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b1_h3` VARCHAR(20) NULL AFTER `pes_mod_b1_h3`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b1_h4` VARCHAR(20) NULL AFTER `pes_mod_b1_h4`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b1_h5` VARCHAR(20) NULL AFTER `pes_mod_b1_h5`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b1_h6` VARCHAR(20) NULL AFTER `pes_mod_b1_h6`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b2_h1` VARCHAR(20) NULL AFTER `pes_mod_b2_h1`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b2_h2` VARCHAR(20) NULL AFTER `pes_mod_b2_h2`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b2_h3` VARCHAR(20) NULL AFTER `pes_mod_b2_h3`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b2_h4` VARCHAR(20) NULL AFTER `pes_mod_b2_h4`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b2_h5` VARCHAR(20) NULL AFTER `pes_mod_b2_h5`;

ALTER TABLE `db_academico`.`planificacion_estudiante` 
ADD COLUMN `pes_jor_b2_h6` VARCHAR(20) NULL AFTER `pes_mod_b2_h6`;