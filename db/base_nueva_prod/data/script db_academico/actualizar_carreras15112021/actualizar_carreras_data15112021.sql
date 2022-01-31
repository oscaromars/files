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

/******** este grupo de carreras se agrega al nombre y descripción
la frase licenciatura en **********/

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`=' Licenciatura en Gestión Social y Desarrollo',
`eaca_descripcion`='Licenciatura en Gestión Social y Desarrollo'
WHERE `eaca_id`='49';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Gestión de riesgos y desastres',
`eaca_descripcion`='Licenciatura en Gestión de riesgos y desastres'
WHERE `eaca_id`='50';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Multimedia y Producción audiovisual',
`eaca_descripcion`='Licenciatura en Multimedia y Producción audiovisual'
WHERE `eaca_id`='54';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Nutrición y Dietética',
`eaca_descripcion`='Licenciatura en Nutrición y Dietética'
WHERE `eaca_id`='55';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Pedagogía en lengua y literatura',
`eaca_descripcion`='Licenciatura en Pedagogía en lengua y literatura'
WHERE `eaca_id`='56';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Pedagogía Técnica de la Mecatrónica',
`eaca_descripcion`='Licenciatura en Pedagogía Técnica de la Mecatrónica'
WHERE `eaca_id`='57';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Psicología',
`eaca_descripcion`='Licenciatura en Psicología'
WHERE `eaca_id`='58';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Puertos y Aduanas',
`eaca_descripcion`='Licenciatura en Puertos y Aduanas'
WHERE `eaca_id`='59';

UPDATE `db_academico`.`estudio_academico`
SET `eaca_nombre`='Licenciatura en Relaciones Internacionales',
`eaca_descripcion`='Licenciatura en Relaciones Internacionales'
WHERE `eaca_id`='60';

