/*** Alter para pagina integracion nuevo campo para iconos 06/07/2021 *****///
ALTER TABLE `db_asgard`.`dash_item`
ADD COLUMN `dite_ruta_banner` VARCHAR(500) NULL DEFAULT NULL AFTER `dite_target`;

/*** Update para pagina integracion ruta de iconos 06/07/2021 *****///
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-11.jpg' WHERE `dite_id`='15';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-04.jpg' WHERE `dite_id`='1';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-05.jpg' WHERE `dite_id`='3';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-06.jpg' WHERE `dite_id`='4';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-07.jpg' WHERE `dite_id`='5';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-08.jpg' WHERE `dite_id`='19';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-09.jpg' WHERE `dite_id`='17';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-10.jpg' WHERE `dite_id`='16';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-12.jpg' WHERE `dite_id`='6';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-13.jpg' WHERE `dite_id`='2';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-15.jpg' WHERE `dite_id`='10';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-16.jpg' WHERE `dite_id`='9';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-17.jpg' WHERE `dite_id`='11';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-19.jpg' WHERE `dite_id`='12';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-21.jpg' WHERE `dite_id`='7';
UPDATE `db_asgard`.`dash_item` SET `dite_ruta_banner`='web/img/bannerasgard/Banner Asgard-22.jpg' WHERE `dite_id`='14';

UPDATE `db_asgard`.`dash_item` SET `dite_link`='/academico/matriculacion/registro' WHERE `dite_id`='1';
UPDATE `db_asgard`.`dash_item` SET `dite_link`='https://www.alphaeditorialcloud.com/library' WHERE `dite_id`='16';




