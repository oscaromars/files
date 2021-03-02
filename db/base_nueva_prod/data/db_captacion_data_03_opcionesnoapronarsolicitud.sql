use db_captacion;

/******************************/
INSERT INTO `db_captacion`.`solicitud_noaprobada` (`snoa_id`, `snoa_nombre`, `snoa_descripcion`, `snoa_estado`, `snoa_fecha_creacion`, `snoa_estado_logico`) 
VALUES ('15', 'Nitidez', 'Nitidez', '1', '2020-10-29 13:00:57', '1');
INSERT INTO `db_captacion`.`solicitud_noaprobada` (`snoa_id`, `snoa_nombre`, `snoa_descripcion`, `snoa_estado`, `snoa_fecha_creacion`, `snoa_estado_logico`) 
VALUES ('16', 'Fondo Blanco tipo pasaporte ', 'Fondo Blanco tipo pasaporte ', '1', '2020-10-29 13:00:57', '1');

/********************************/
UPDATE `db_captacion`.`solicitud_noaprobada_documento` SET `snoa_id`='15', `sndo_fecha_modificacion`='2020-10-29 13:15:00' WHERE `sndo_id`='12';
UPDATE `db_captacion`.`solicitud_noaprobada_documento` SET `snoa_id`='16', `sndo_fecha_modificacion`='2020-10-29 13:15:00' WHERE `sndo_id`='13';


/********************************/
INSERT INTO `db_captacion`.`solicitud_noaprobada_documento` (`sndo_id`, `snoa_id`, `dadj_id`, `sndo_tiponacext`, `sndo_estado`, `sndo_fecha_creacion`, `sndo_estado_logico`) 
VALUES ('25', '4', '7', 'N', '1', '2020-10-30 12:44:57', '1');
