/* Alter campo año en db_inscripcion */

ALTER TABLE `db_inscripcion`.`estudiante_instruccion`
CHANGE COLUMN `eins_añogrado3ernivel` `eins_aniogrado3ernivel` VARCHAR(50) NULL DEFAULT NULL ,
CHANGE COLUMN `eins_añogrado4tonivel` `eins_aniogrado4tonivel` VARCHAR(50) NULL DEFAULT NULL ;

ALTER TABLE `db_inscripcion`.`info_docencia_estudiante`
CHANGE COLUMN `ides_año_docencia` `ides_anio_docencia` VARCHAR(100) NULL DEFAULT NULL ;

ALTER TABLE `db_inscripcion`.`informacion_laboral`
CHANGE COLUMN `ilab_anioingreso_emp` `ilab_anioingreso_emp` VARCHAR(200) NULL DEFAULT NULL ;

ALTER TABLE `db_inscripcion`.`inscripcion_posgrado`
CHANGE COLUMN `ipos_anio` `ipos_anio` VARCHAR(50) NULL DEFAULT NULL ;

