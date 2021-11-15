use db_academico;

/* verificar id en producción antes de correrlos */
-- Tecnologías de la Información
UPDATE `db_academico`.`estudio_academico`
SET `eaca_estado`='0', `eaca_estado_logico`='0'
WHERE `eaca_id`='62';

-- se llamaba anteriomente Educación Básica, se cambia nombre a Licenciatura en Educación Básica
UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Educación Básica',
`eaca_descripcion`='Licenciatura en Educación Básica'
WHERE `eaca_id`='48';

-- Educación
UPDATE `db_academico`.`estudio_academico`
SET `eaca_estado`='0', `eaca_estado_logico`='0'
WHERE `eaca_id`='61';


