use db_academico;

/* verificar id en produccion antes de correrlos */
UPDATE `db_academico`.`estudio_academico`
SET `eaca_estado`='0', `eaca_estado_logico`='0'
WHERE `eaca_id`='62';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Educación Báscica',
`eaca_descripcion`='Licenciatura en Educación Báscica'
WHERE `eaca_id`='48';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_estado`='0', `eaca_estado_logico`='0'
WHERE `eaca_id`='61';


