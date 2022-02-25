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
redes sociales ****/
ALTER TABLE `db_captacion`.`temporal_wizard_inscripcion`
ADD COLUMN `twin_nivel_instruccion` BIGINT(20) NULL AFTER `twin_mensaje2`;

ALTER TABLE `db_captacion`.`temporal_wizard_inscripcion`
ADD COLUMN `twin_redes_sociales` BIGINT(20) NULL DEFAULT NULL AFTER `twin_nivel_instruccion`;


/**** Verificar PK en produccion Agregar twitter para seguimiento ***/
INSERT INTO `db_crm`.`bitacora_seguimiento` (`bseg_id`, `bseg_nombre`, `bseg_descripcion`, `bseg_estado`, `bseg_fecha_creacion`, `bseg_estado_logico`)
VALUES ('7', 'Twitter', 'Twitter', '1', '2022-02-25 16:28:00', '1');
