/*** Alter para pagina integracion nuevo campo para iconos 06/07/2021 *****///
ALTER TABLE `db_asgard`.`dash_item`
ADD COLUMN `dite_ruta_banner` VARCHAR(500) NULL DEFAULT NULL AFTER `dite_target`;

/*** Update para pagina integracion ruta de iconos 06/07/2021 *****///
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='/uploads/bannerasgard/Banner Asgard-11.jpg' WHERE `dite_id`='15';
