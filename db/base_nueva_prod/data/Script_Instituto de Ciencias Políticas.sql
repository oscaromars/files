/*Insertar Unidad*/
/*Verificar PK en produccion*/
INSERT INTO `db_academico`.`unidad_academica` (`uaca_id`, `uaca_nombre`, `uaca_descripcion`, `uaca_nomenclatura`,`uaca_usuario_ingreso`, `uaca_estado`, `uaca_inscripcion`, `uaca_fecha_creacion`, `uaca_estado_logico`)
VALUES ('10', 'Instituto de Ciencias Políticas', 'Instituto de Ciencias Políticas', 'ICP','1', '1', '1', '2022-02-25 11:15:00', '1');

/** Verificar PK produccion*/
INSERT INTO `db_academico`.`estudio_academico` (`eaca_id`, `teac_id`, `eaca_codigo`, `eaca_nombre`, `eaca_descripcion`, `eaca_alias`, `eaca_alias_resumen`, `eaca_usuario_ingreso`, `eaca_usuario_modifica`, `eaca_estado`, `eaca_fecha_creacion`, `eaca_estado_logico`)
VALUES ('76', '3', 'DFPP', 'Diplomado en Formación y participación política', 'Diplomado en Formación y participación política', 'diplomado_institucion', 'diplomado_institucion', '1', NULL, '1', '2022-02-25 11:31:00', '1');

/*Verificar PK en produccion tanto el PK como el eaca_id que va en la columna 4*/
INSERT INTO `db_academico`.`modalidad_estudio_unidad` (`meun_id`, `uaca_id`, `mod_id`, `eaca_id`, `emp_id`, `meun_usuario_ingreso`, `meun_estado`, `meun_fecha_creacion`, `meun_estado_logico`)
VALUES ('142', '10', '1', '76', '1', '1', '1', '2022-02-25 11:22:00', '1');

/*****Crear campo en tabla temporal para guardar nivel de instruccion
redes sociales, como te encontramos ****/
ALTER TABLE `db_captacion`.`temporal_wizard_inscripcion`
ADD COLUMN `twin_nivel_instruccion` BIGINT(20) NULL AFTER `twin_mensaje2`;

ALTER TABLE `db_captacion`.`temporal_wizard_inscripcion`
ADD COLUMN `twin_redes_sociales` BIGINT(20) NULL DEFAULT NULL AFTER `twin_nivel_instruccion`;

ALTER TABLE `db_captacion`.`temporal_wizard_inscripcion`
ADD COLUMN `twin_encontramos` VARCHAR(150) NULL DEFAULT NULL AFTER `twin_redes_sociales`;


/**** Verificar PK en produccion Agregar twitter para seguimiento ***/
INSERT INTO `db_crm`.`bitacora_seguimiento` (`bseg_id`, `bseg_nombre`, `bseg_descripcion`, `bseg_estado`, `bseg_fecha_creacion`, `bseg_estado_logico`)
VALUES ('7', 'Twitter', 'Twitter', '1', '2022-02-25 16:28:00', '1');

/********** Verificar PK en produccion, Item, precio, sub categoría para diplomado *****************/
INSERT INTO `db_facturacion`.`sub_categoria` (`scat_id`, `cat_id`, `scat_nombre`, `scat_descripcion`, `scat_usu_ingreso`, `scat_estado`, `scat_fecha_creacion`, `scat_estado_logico`)
VALUES ('9', '2', 'Diplomado formación Política', 'Diplomado formación Política', '1', '1', '2022-02-26 14:48:00', '1');

INSERT INTO `db_facturacion`.`item` (`ite_id`, `scat_id`, `ite_nombre`, `ite_descripcion`, `ite_codigo`, `ite_usu_ingreso`, `ite_estado`, `ite_fecha_creacion`, `ite_estado_logico`)
VALUES ('208', '9', 'Valor Total Dipl. Formación participaciónpolítica', 'Valor Total Dipl. Formación participaciónpolítica', '0208', '1', '1', '2022-02-26 15:05:22', '1');

INSERT INTO `db_facturacion`.`item_precio` (`ipre_id`, `ite_id`, `ipre_precio`, `ipre_fecha_inicio`, `ipre_fecha_fin`, `ipre_estado_precio`, `ipre_usu_ingreso`, `ipre_estado`, `ipre_fecha_creacion`, `ipre_estado_logico`)
VALUES ('208', '208', '180', '2022-02-26 00:00:00', '2022-12-31 23:59:59', 'A', '1', '1', '2022-02-26 15:08:00', '1');

INSERT INTO `db_facturacion`.`item_metodo_unidad` (`imni_id`, `ite_id`, `uaca_id`, `mod_id`, `mest_id`, `imni_usu_ingreso`, `imni_estado`, `imni_fecha_creacion`, `imni_estado_logico`)
VALUES ('186', '208', '10', '1', NULL, '1', '1', '2022-02-26 15:22:00', '1');
