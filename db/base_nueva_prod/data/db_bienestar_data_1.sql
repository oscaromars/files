USE `db_bienestar`;

--
-- Dumping data for table `criterio_cabecera`
--
INSERT INTO `criterio_cabecera` VALUES 
(1,'DATOS ACADÉMICOS','2021-07-12 17:15:00',NULL,'1','1'),
(2,'DATOS SOCIOECONÓMICOS','2021-07-12 17:15:00',NULL,'1','1'),
(3,'SITUACIÓN CATASTRÓFICA','2021-07-12 17:15:00',NULL,'1','1'),
(4,'ACCIÓN AFIRMATIVA','2021-07-12 17:15:00',NULL,'1','1'),
(5,'ACTIVIDADES SOBRESALIENTES','2021-07-12 17:15:00',NULL,'1','1');


--
-- Dumping data for table `criterio_detalle`
--
INSERT INTO `criterio_detalle` VALUES 
(1,1,2,'PROMEDIO DEL COLEGIO/SEMESTRE ANTERIOR','2021-07-12 17:15:00',NULL,'1','1'),
(2,1,2,'PROMEDIO DE ASISTENCIA DEL PERIODO ANTERIOR ','2021-07-12 17:15:00',NULL,'1','1'),
(3,2,3,'SEXO','2021-07-12 17:15:00',NULL,'1','1'),
(4,2,3,'SECTOR DE RESIDENCIA','2021-07-12 17:15:00',NULL,'1','1'),
(5,2,3,'ESTADO CIVIL','2021-07-12 17:15:00',NULL,'1','1'),
(6,2,3,'CARGAS FAMILIARES DEL ESTUDIANTE ','2021-07-12 17:15:00',NULL,'1','1'),
(7,2,3,'FORMA DE FINANCIAMINETO DE ESTUDIO ','2021-07-12 17:15:00',NULL,'1','1'),
(8,2,4,'SUELDO DEL FAMILIAR QUE FINANCIA LOS ESTUDIOS','2021-07-12 17:15:00',NULL,'1','1'),
(9,2,4,'CARGAS FAMILIARES DIRECTAS DE QUIEN  FINANCIA EL ESTUDIO ','2021-07-12 17:15:00',NULL,'1','1'),
(10,2,3,'ESTADO LABORAL DEL ESTUDIANTE','2021-07-12 17:15:00',NULL,'1','1'),
(11,2,3,'SUELDO','2021-07-12 17:15:00',NULL,'1','1'),
(12,2,3,'GASTOS DE SERVICIOS BÁSICOS','2021-07-12 17:15:00',NULL,'1','1'),
(13,2,3,'POSEE VEHÍCULO','2021-07-12 17:15:00',NULL,'1','1'),
(14,2,3,'NIVEL EDUCATIVO DEL PADRE','2021-07-12 17:15:00',NULL,'1','1'),
(15,2,3,'NIVEL EDUCATIVO DE LA MADRE','2021-07-12 17:15:00',NULL,'1','1'),
(16,2,3,'CANTIDAD DE MIEMBROS EN EL HOGAR','2021-07-12 17:15:00',NULL,'1','1'),
(17,2,3,'TIPO DE COLEGIO DE PROVENIENCIA','2021-07-12 17:15:00',NULL,'1','1'),
(18,3,5,'FALLECIMIENTO DE FAMILIAR DIRECTO','2021-07-12 17:15:00',NULL,'1','1'),
(19,3,5,'ENFERMEDAD CATASTRÓFICA','2021-07-12 17:15:00',NULL,'1','1'),
(20,4,7,'GRADO DE DISCAPACIDAD A FAMILIARES','2021-07-12 17:15:00',NULL,'1','1'),
(21,4,6,'GRUPOS MINORITARIOS (ETNIA)','2021-07-12 17:15:00',NULL,'1','1'),
(22,4,6,'GRUPOS MINORITARIOS (IDENTIDAD DE GÉNERO)','2021-07-12 17:15:00',NULL,'1','1'),
(23,4,6,'GRUPOS MINORITARIOS O VULNERABLES','2021-07-12 17:15:00',NULL,'1','1'),
(24,5,8,'MÉRITO ACADÉMICO','2021-07-12 17:15:00',NULL,'1','1'),
(25,5,8,'PERÍODO DE OBTENCIÓN','2021-07-12 17:15:00',NULL,'1','1'),
(26,5,8,'MÉRITO PROFESIONAL','2021-07-12 17:15:00',NULL,'1','1'),
(27,5,8,'PARTCIPACIÓN EN ACTIVIDADES CURRICULARES','2021-07-12 17:15:00',NULL,'1','1'),
(28,5,8,'PARTCIPACIÓN EN ACTIVIDADES EXTRACURRICULARES','2021-07-12 17:15:00',NULL,'1','1');

--
-- Dumping data for table `formulario_condiciones_ponderaciones`
--
INSERT INTO `formulario_condiciones_ponderaciones` VALUES 
(1,1,'20',10,'2021-07-13 00:50:00',NULL,'1','1'),
(2,1,'19',9,'2021-07-13 00:50:00',NULL,'1','1'),
(3,1,'18',8,'2021-07-13 00:50:00',NULL,'1','1'),
(4,1,'17',7,'2021-07-13 00:50:00',NULL,'1','1'),
(5,1,'16',6,'2021-07-13 00:50:00',NULL,'1','1'),
(6,1,'15',6,'2021-07-13 00:50:00',NULL,'1','1'),
(7,1,'14',0,'2021-07-13 00:50:00',NULL,'1','1'),
(8,2,'90 - 100',10,'2021-07-13 00:50:00',NULL,'1','1'),
(9,2,'80 - 89',9,'2021-07-13 00:50:00',NULL,'1','1'),
(10,2,'70 - 79',8,'2021-07-13 00:50:00',NULL,'1','1'),
(11,2,'60 - 69',0,'2021-07-13 00:50:00',NULL,'1','1'),
(12,3,'HOMBRE',1,'2021-07-13 00:50:00',NULL,'1','1'),
(13,3,'MUJER',2,'2021-07-13 00:50:00',NULL,'1','1'),
(14,3,'INTERSEX',2,'2021-07-13 00:50:00',NULL,'1','1'),
(15,4,'NORTE',0.5,'2021-07-10 03:55:00',NULL,'1','1'),
(16,4,'CENTRO',0.5,'2021-07-10 03:55:00',NULL,'1','1'),
(17,4,'SUR',0.5,'2021-07-10 03:55:00',NULL,'1','1'),
(18,4,'VÍA SAMBORONDÓN O VÍA LA COSTA',0,'2021-07-10 03:55:00',NULL,'1','1'),
(19,4,'OTRA CIUDAD',1,'2021-07-10 03:55:00',NULL,'1','1'),
(20,5,'SOLTERO/A',0.5,'2021-07-12 21:30:00',NULL,'1','1'),
(21,5,'CASADO/A',1,'2021-07-12 21:30:00',NULL,'1','1'),
(22,5,'DIVORCIADO/A',0.5,'2021-07-12 21:30:00',NULL,'1','1'),
(23,5,'VIUDO/A',2,'2021-07-12 21:30:00',NULL,'1','1'),
(24,5,'UNIÓN LIBRE',1,'2021-07-12 21:30:00',NULL,'1','1'),
(25,6,'NINGUNA',1,'2021-07-13 00:50:00',NULL,'1','1'),
(26,6,'DE UNA A DOS CARGAS',2,'2021-07-13 00:50:00',NULL,'1','1'),
(27,6,'TRES CARGAS',3,'2021-07-13 00:50:00',NULL,'1','1'),
(28,6,'CUATRO CARGAS',4,'2021-07-13 00:50:00',NULL,'1','1'),
(29,6,'CINCO O MÁS CARGAS',5,'2021-07-13 00:50:00',NULL,'1','1'),
(30,7,'IECE',0,'2021-07-12 21:30:00',NULL,'1','1'),
(31,7,'ISSFA',0,'2021-07-12 21:30:00',NULL,'1','1'),
(32,7,'FAMILIARES',2,'2021-07-12 21:30:00',NULL,'1','1'),
(33,7,'FONDOS PROPIOS',3,'2021-07-12 21:30:00',NULL,'1','1'),
(34,8,'DE $0 A $400',5,'2021-07-10 03:43:00',NULL,'1','1'),
(35,8,'DE $400 A $499',4,'2021-07-10 03:43:00',NULL,'1','1'),
(36,8,'DE $500 A $599',3,'2021-07-10 03:43:00',NULL,'1','1'),
(37,8,'DE $600 A $699',2,'2021-07-10 03:43:00',NULL,'1','1'),
(38,8,'DE $700 A $799',1,'2021-07-10 03:43:00',NULL,'1','1'),
(39,8,'DE $800 A MÁS',0,'2021-07-10 03:43:00',NULL,'1','1'),
(40,9,'NINGUNA',1,'2021-07-13 00:50:00',NULL,'1','1'),
(41,9,'DE UNA A DOS CARGAS',2,'2021-07-13 00:50:00',NULL,'1','1'),
(42,9,'TRES CARGAS',3,'2021-07-13 00:50:00',NULL,'1','1'),
(43,9,'CUATRO CARGAS',4,'2021-07-13 00:50:00',NULL,'1','1'),
(44,9,'CINCO O MÁS CARGAS',5,'2021-07-13 00:50:00',NULL,'1','1'),
(45,10,'TRABAJA',1,'2021-07-13 00:50:00',NULL,'1','1'),
(46,10,'NO TRABAJA',2,'2021-07-13 00:50:00',NULL,'1','1'),
(47,11,'DE $0 A $400',5,'2021-07-10 03:43:00',NULL,'1','1'),
(48,11,'DE $400 A $499',4,'2021-07-10 03:43:00',NULL,'1','1'),
(49,11,'DE $500 A $599',3,'2021-07-10 03:43:00',NULL,'1','1'),
(50,11,'DE $600 A $699',2,'2021-07-10 03:43:00',NULL,'1','1'),
(51,11,'DE $700 A $799',1,'2021-07-10 03:43:00',NULL,'1','1'),
(52,11,'DE $800 A MÁS',0,'2021-07-10 03:43:00',NULL,'1','1'),
(53,12,'$16 O MENOS',2,'2021-07-12 21:30:00',NULL,'1','1'),
(54,12,'$17 - $37',1.5,'2021-07-12 21:30:00',NULL,'1','1'),
(55,12,'$38 - $58',1,'2021-07-12 21:30:00',NULL,'1','1'),
(56,12,'$59 - $79',0.5,'2021-07-12 21:30:00',NULL,'1','1'),
(57,12,'$80 A MÁS',0,'2021-07-12 21:30:00',NULL,'1','1'),
(58,13,'SI',0,'2021-07-13 03:00:00',NULL,'1','1'),
(59,13,'NO',1,'2021-07-13 03:00:00',NULL,'1','1'),
(60,14,'EDUCACIÓN INICIAL',2,'2021-07-10 03:55:00',NULL,'1','1'),
(61,14,'EDUCACIÓN GENERAL BÁSICA',1.5,'2021-07-10 03:55:00',NULL,'1','1'),
(62,14,'BACHILLERATO',1,'2021-07-10 03:55:00',NULL,'1','1'),
(63,14,'UNIVERSIDAD',1,'2021-07-10 03:55:00',NULL,'1','1'),
(64,15,'EDUCACIÓN INICIAL',2,'2021-07-10 03:55:00',NULL,'1','1'),
(65,15,'EDUCACIÓN GENERAL BÁSICA',1.5,'2021-07-10 03:55:00',NULL,'1','1'),
(66,15,'BACHILLERATO',1,'2021-07-10 03:55:00',NULL,'1','1'),
(67,15,'UNIVERSIDAD',1,'2021-07-10 03:55:00',NULL,'1','1'),
(68,16,'UNO',0.5,'2021-07-13 00:50:00',NULL,'1','1'),
(69,16,'DOS',1,'2021-07-13 00:50:00',NULL,'1','1'),
(70,16,'TRES',1,'2021-07-13 00:50:00',NULL,'1','1'),
(71,16,'CUATRO',1.5,'2021-07-13 00:50:00',NULL,'1','1'),
(72,16,'CINCO O MÁS',2,'2021-07-13 00:50:00',NULL,'1','1'),
(73,17,'FISCAL',1,'2021-07-12 21:30:00',NULL,'1','1'),
(74,17,'PARTICULAR',0.5,'2021-07-12 21:30:00',NULL,'1','1'),
(75,18,'SI',10,'2021-07-14 02:00:00',NULL,'1','1'),
(76,18,'NO',0,'2021-07-14 02:00:00',NULL,'1','1'),
(77,19,'SI',10,'2021-07-14 02:00:00',NULL,'1','1'),
(78,19,'NO',0,'2021-07-14 02:00:00',NULL,'1','1'),
(79,20,'1 - 29',2,'2021-07-14 02:00:00',NULL,'1','1'),
(80,20,'30 - 49',3,'2021-07-14 02:00:00',NULL,'1','1'),
(81,20,'50 - 59',4,'2021-07-14 02:00:00',NULL,'1','1'),
(82,20,'60 - 100',5,'2021-07-14 02:00:00',NULL,'1','1'),
(83,21,'AFROECUATORIANO/A',3,'2021-07-12 21:30:00',NULL,'1','1'),
(84,21,'BLANCO/A',2,'2021-07-12 21:30:00',NULL,'1','1'),
(85,21,'INDÍGENA',3,'2021-07-12 21:30:00',NULL,'1','1'),
(86,21,'MESTIZO/A',1,'2021-07-12 21:30:00',NULL,'1','1'),
(87,21,'MONTUBIO/A',3,'2021-07-12 21:30:00',NULL,'1','1'),
(88,21,'MULATO/A',2,'2021-07-12 21:30:00',NULL,'1','1'),
(89,21,'NEGRO/A',3,'2021-07-12 21:30:00',NULL,'1','1'),
(90,21,'OTROS',3,'2021-07-12 21:30:00',NULL,'1','1'),
(91,22,'HETEROSEXUAL',1,'2021-07-12 21:30:00',NULL,'1','1'),
(92,22,'GAY',2,'2021-07-12 21:30:00',NULL,'1','1'),
(93,22,'LESBIANA',2,'2021-07-12 21:30:00',NULL,'1','1'),
(94,22,'BISEXUAL',2,'2021-07-12 21:30:00',NULL,'1','1'),
(95,22,'TRANSGÉNERO FEMENINA',2,'2021-07-12 21:30:00',NULL,'1','1'),
(96,22,'TRANSGÉNERO MASCULINO',2,'2021-07-12 21:30:00',NULL,'1','1'),
(97,22,'TRANSEXUAL',2,'2021-07-12 21:30:00',NULL,'1','1'),
(98,22,'OTROS',2,'2021-07-12 21:30:00',NULL,'1','1'),
(99,23,'MIGRANTE',1,'2021-07-14 02:00:00',NULL,'1','1'),
(100,23,'MUJER EMBARAZADA',1,'2021-07-14 02:00:00',NULL,'1','1'),
(101,23,'OTROS',1,'2021-07-14 02:00:00',NULL,'1','1'),
(102,24,'ABANDERADO/A',3,'2021-07-12 21:30:00',NULL,'1','1'),
(103,24,'ESCOLTA',2,'2021-07-12 21:30:00',NULL,'1','1'),
(104,24,'EXCELENCIA ACADÉMICA',1,'2021-07-12 21:30:00',NULL,'1','1'),
(105,25,'DESDE HACE 1 A 2 AÑOS',2,'2021-07-12 21:30:00',NULL,'1','1'),
(106,25,'DESDE HACE 3 AÑOS',1,'2021-07-12 21:30:00',NULL,'1','1'),
(107,25,'DESDE HACE 4 AÑOS O MÁS',0,'2021-07-12 21:30:00',NULL,'1','1'),
(108,26,'MASTERADO',3,'2021-07-12 21:30:00',NULL,'1','1'),
(109,26,'INGENIERÍAS',2,'2021-07-12 21:30:00',NULL,'1','1'),
(110,26,'LICENCIATURAS',2,'2021-07-12 21:30:00',NULL,'1','1'),
(111,26,'TECNOLÓGICOS',1,'2021-07-12 21:30:00',NULL,'1','1'),
(112,27,'DE 1 A 2',1,'2021-07-12 21:30:00',NULL,'1','1'),
(113,27,'DE 3 O MÁS',2,'2021-07-12 21:30:00',NULL,'1','1'),
(114,27,'NINGUNA',0,'2021-07-12 21:30:00',NULL,'1','1'),
(115,28,'DE 1 A 2',1,'2021-07-12 21:30:00',NULL,'1','1'),
(116,28,'DE 3 O MÁS',2,'2021-07-12 21:30:00',NULL,'1','1'),
(117,28,'NINGUNA',0,'2021-07-12 21:30:00',NULL,'1','1');

--
-- Dumping data for table `formulario_seccion`
--
INSERT INTO `formulario_seccion` VALUES 
(1,'DATOS PERSONALES DEL ESTUDIANTE','2021-07-14 22:15:00',NULL,'1','1'),
(2,'DATOS ACADÉMICOS','2021-07-14 22:15:00',NULL,'1','1'),
(3,'VALORACIÓN DE ASPECTOS SOCIOECONÓMICOS','2021-07-14 22:15:00',NULL,'1','1'),
(4,'DATOS DE LA PERSONA QUE FINANCIA SUS ESTUDIOS','2021-07-14 22:15:00',NULL,'1','1'),
(5,'SITUACIÓN CATASTRÓFICA','2021-07-14 22:15:00',NULL,'1','1'),
(6,'DISCAPACIDAD Y GRUPOS MINORITARIOS','2021-07-14 22:15:00',NULL,'1','1'),
(7,'CARACTERÍSTICAS DE CONDICIÓN DE DISCAPACIDAD','2021-07-14 22:15:00',NULL,'1','1'),
(8,'ACTIVIDADES SOBRESALIENTES','2021-07-14 22:15:00',NULL,'1','1');

--
-- Dumping data for table `formulario_seccion_campo`
--
INSERT INTO `formulario_seccion_campo` VALUES 
(1,2,'NÚMERO DE CRÉDITOS APROBADOS','2021-07-14 22:22:00',NULL,'1','1'),
(2,2,'PROMEDIO ACADÉMICO DEL SEMESTRE ANTERIOR','2021-07-14 22:22:00',NULL,'1','1'),
(3,2,'PROMEDIO DE ASISTENCIA DEL SEMESTRE ANTERIOR','2021-07-14 22:22:00',NULL,'1','1'),
(4,3,'CIUDAD','2021-07-15 17:30:00',NULL,'1','1'),
(5,3,'PROVINCIA','2021-07-15 17:30:00',NULL,'1','1'),
(6,3,'ORGANIZACIÓN DONDE LABORA','2021-07-15 17:30:00',NULL,'1','1'),
(7,3,'CARGO QUE DESEMPEÑA','2021-07-15 17:30:00',NULL,'1','1'),
(8,3,'TELÉFONO DE OFICINA','2021-07-15 17:30:00',NULL,'1','1'),
(9,4,'APELLIDOS','2021-07-15 17:30:00',NULL,'1','1'),
(10,4,'NOMBRES','2021-07-15 17:30:00',NULL,'1','1'),
(11,4,'PARENTESCO','2021-07-15 17:30:00',NULL,'1','1'),
(12,4,'C.I.','2021-07-15 17:30:00',NULL,'1','1'),
(13,4,'DIRECCIÓN DOMICILIARIA','2021-07-15 17:30:00',NULL,'1','1'),
(14,4,'TELÉFONO DOMICILIARIO','2021-07-15 17:30:00',NULL,'1','1'),
(15,4,'TELÉFONO CELULAR','2021-07-15 17:30:00',NULL,'1','1'),
(16,4,'CORREO ELECTRÓNICO','2021-07-15 17:30:00',NULL,'1','1'),
(17,4,'SECTOR DONDE RESIDE','2021-07-15 17:30:00',NULL,'1','1'),
(18,4,'EN EL CASO DE MARCAR OTRA CIUDAD, INDIQUE DONDE','2021-07-15 17:30:00',NULL,'1','1'),
(19,4,'ESTADO CIVIL','2021-07-15 17:30:00',NULL,'1','1'),
(20,4,'ORGANIZACIÓN DONDE LABORA','2021-07-15 17:30:00',NULL,'1','1'),
(21,4,'TELÉFONO DE OFICINA','2021-07-15 17:30:00',NULL,'1','1'),
(22,4,'CARGO QUE DESEMPEÑA','2021-07-15 17:30:00',NULL,'1','1'),
(23,5,'DETALLE BREVEMENTE SOBRE EL FALLECIMIENTO','2021-07-15 17:30:00',NULL,'1','1'),
(24,5,'DETALLE BREVEMENTE SOBRE LA ENFERMEDAD','2021-07-15 17:30:00',NULL,'1','1'),
(25,5,'OTRA SITUACIÓN CATASTRÓFICA - DETALLAR BREVEMENTE','2021-07-15 17:30:00',NULL,'1','1'),
(26,6,'OTRA ETNIA','2021-07-15 17:30:00',NULL,'1','1'),
(27,6,'OTRA IDENTIDAD','2021-07-15 17:30:00',NULL,'1','1');


