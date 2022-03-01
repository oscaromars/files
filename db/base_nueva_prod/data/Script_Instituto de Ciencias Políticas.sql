/*Insertar Unidad*/
/*Verificar PK en produccion*/
INSERT INTO `db_academico`.`unidad_academica` (`uaca_id`, `uaca_nombre`, `uaca_descripcion`, `uaca_nomenclatura`,`uaca_usuario_ingreso`, `uaca_estado`, `uaca_inscripcion`, `uaca_fecha_creacion`, `uaca_estado_logico`)
VALUES ('10', 'Instituto de Ciencias Políticas', 'Instituto de Ciencias Políticas', 'ICP','1', '1', '1', '2022-02-25 11:15:00', '1');

/** Verificar PK produccion*/
INSERT INTO `db_academico`.`estudio_academico` (`eaca_id`, `teac_id`, `eaca_codigo`, `eaca_nombre`, `eaca_descripcion`, `eaca_alias`, `eaca_alias_resumen`, `eaca_usuario_ingreso`, `eaca_usuario_modifica`, `eaca_estado`, `eaca_fecha_creacion`, `eaca_estado_logico`)
VALUES ('77', '3', 'DFPP', 'Diplomado en Formación y participación política', 'Diplomado en Formación y participación política', 'diplomado_institucion', 'diplomado_institucion', '1', NULL, '1', '2022-02-25 11:31:00', '1');

/*Verificar PK en produccion tanto el PK como el eaca_id que va en la columna 4*/
INSERT INTO `db_academico`.`modalidad_estudio_unidad` (`meun_id`, `uaca_id`, `mod_id`, `eaca_id`, `emp_id`, `meun_usuario_ingreso`, `meun_estado`, `meun_fecha_creacion`, `meun_estado_logico`)
VALUES ('146', '10', '1', '76', '1', '1', '1', '2022-02-25 11:22:00', '1');

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
VALUES ('9', 1, 'Instituto de Ciencias Políticas', 'Instituto de Ciencias Políticas', '1', '1', '2022-02-26 14:48:00', '1');

INSERT INTO `db_facturacion`.`item` (`ite_id`, `scat_id`, `ite_nombre`, `ite_descripcion`, `ite_codigo`, `ite_usu_ingreso`, `ite_estado`, `ite_fecha_creacion`, `ite_estado_logico`)
VALUES ('212', '9', 'Valor Total Dipl. Formación participación política', 'Valor Total Dipl. Formación participación política', '0208', '1', '1', '2022-02-26 15:05:22', '1');

INSERT INTO `db_facturacion`.`item_precio` (`ipre_id`, `ite_id`, `ipre_precio`, `ipre_fecha_inicio`, `ipre_fecha_fin`, `ipre_estado_precio`, `ipre_usu_ingreso`, `ipre_estado`, `ipre_fecha_creacion`, `ipre_estado_logico`)
VALUES ('212', '208', '180', '2022-02-26 00:00:00', '2022-12-31 23:59:59', 'A', '1', '1', '2022-02-26 15:08:00', '1');

INSERT INTO `db_facturacion`.`item_metodo_unidad` (`imni_id`, `ite_id`, `uaca_id`, `mod_id`, `mest_id`, `imni_usu_ingreso`, `imni_estado`, `imni_fecha_creacion`, `imni_estado_logico`)
VALUES ('215', '208', '10', '1', NULL, '1', '1', '2022-02-26 15:22:00', '1');

INSERT INTO `db_facturacion`.`item` (`ite_id`, `scat_id`, `ite_nombre`, `ite_descripcion`, `ite_codigo`, `ite_usu_ingreso`, `ite_estado`, `ite_fecha_creacion`, `ite_estado_logico`)
VALUES ('213', '9', 'Un Módulo Dipl. Formación participación política', 'Un Módulo Dipl. Formación participación política', '0209', '1', '1', '2022-02-26 15:05:22', '1');

INSERT INTO `db_facturacion`.`item_metodo_unidad` (`imni_id`, `ite_id`, `uaca_id`, `mod_id`, `imni_usu_ingreso`, `imni_estado`, `imni_fecha_creacion`, `imni_estado_logico`)
VALUES ('216', '209', '10', '1', '1', '1', '2022-02-26 15:22:00', '1');

INSERT INTO `db_facturacion`.`item_precio` (`ipre_id`, `ite_id`, `ipre_precio`, `ipre_fecha_inicio`, `ipre_fecha_fin`, `ipre_estado_precio`, `ipre_usu_ingreso`, `ipre_estado`, `ipre_fecha_creacion`, `ipre_estado_logico`)
VALUES ('213', '209', '60', '2022-02-26 00:00:00', '2022-12-31 23:59:59', 'A', '1', '1', '2022-02-26 15:08:00', '1');

INSERT INTO `db_facturacion`.`item` (`ite_id`, `scat_id`, `ite_nombre`, `ite_descripcion`, `ite_codigo`, `ite_usu_ingreso`, `ite_estado`, `ite_fecha_creacion`, `ite_estado_logico`)
VALUES ('214', '9', 'Dos Módulos Dipl. Formación participación política', 'Dos Módulos Dipl. Formación participación política', '0210', '1', '1', '2022-02-26 15:05:22', '1');

INSERT INTO `db_facturacion`.`item_metodo_unidad` (`imni_id`, `ite_id`, `uaca_id`, `mod_id`, `imni_usu_ingreso`, `imni_estado`, `imni_fecha_creacion`, `imni_estado_logico`)
VALUES ('217', '210', '10', '1', '1', '1', '2022-02-26 15:22:00', '1');

INSERT INTO `db_facturacion`.`item_precio` (`ipre_id`, `ite_id`, `ipre_precio`, `ipre_fecha_inicio`, `ipre_fecha_fin`, `ipre_estado_precio`, `ipre_usu_ingreso`, `ipre_estado`, `ipre_fecha_creacion`, `ipre_estado_logico`)
VALUES ('214', '210', '120', '2022-02-26 00:00:00', '2022-12-31 23:59:59', 'A', '1', '1', '2022-02-26 15:08:00', '1');

INSERT INTO `db_facturacion`.`historial_item_precio` (hipr_id, ite_id, hipr_precio, hipr_fecha_inicio, hipr_fecha_fin, hipr_usu_transaccion, hipr_estado, hipr_estado_logico) VALUES
(228, 212, 180, '2022-02-26 00:00:00', null, 1, '1', '1'),
(229, 213, 60, '2022-02-26 00:00:00', null, 1, '1', '1'),
(230, 214, 120, '2022-02-26 00:00:00', null, 1, '1', '1');

/************************* insert para secuencial matriucla ICP ********/
CREATE TABLE db_academico.`numero_matricula` (
  `nmat_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nmat_codigo` int(3) NOT NULL,
  `nmat_descripcion` varchar(300) NOT NULL,
  `nmat_anio` char(4) NOT NULL, -- año actual para mascara de numero de matricual
  `nmat_numero` varchar(10) DEFAULT NULL, -- secuencial de 5 digitos
  `nmat_usuario_ingreso` bigint(20) DEFAULT NULL,
  `nmat_usuario_modifica` bigint(20) DEFAULT NULL,
  `nmat_estado` varchar(1) NOT NULL,
  `nmat_fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nmat_fecha_modificacion` timestamp NULL DEFAULT NULL,
  `nmat_estado_logico` varchar(1) NOT NULL,
  PRIMARY KEY (`nmat_id`)
);

INSERT INTO `db_academico`.`numero_matricula` (`nmat_id`, `nmat_codigo`, `nmat_descripcion`, `nmat_anio`, `nmat_numero`, `nmat_usuario_ingreso`, `nmat_usuario_modifica`, `nmat_estado`, `nmat_fecha_creacion`, `nmat_fecha_modificacion`, `nmat_estado_logico`)
VALUES ('2', '2', 'ICP', '2022', '00000', '1', '1', '1', '2022-02-27 11:00:00', NULL, '1');


/*********** Script de tabla persona_otros_datos ***********/
create table if not exists db_asgard.`persona_otros_datos` (
 `poda_id` bigint(20) not null auto_increment primary key,
 `per_id` bigint(20) not null,
 `nins_id` bigint(20) not null,
 `bseg_id` bigint(20) not null,
 `poda_contacto_red_social` varchar(1) default null,
 `poda_estado` varchar(1) not null,
 `poda_usuario_creacion` bigint(20) not null,
 `poda_fecha_creacion` timestamp not null default current_timestamp,
 `poda_fecha_modificacion` timestamp null default null,
 `poda_usuario_modificacion` bigint(20) null,
 `poda_estado_logico` varchar(1) not null,
  foreign key (per_id) references `persona`(per_id)
) ;
