

-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: financiero
-- ------------------------------------------------------
-- Server version	5.6.40

USE `db_gfinanciero` ;

INSERT INTO `cargos` (`carg_id`, `carg_nombre`, `carg_sueldo`, `carg_usuario_ingreso`, `carg_usuario_modifica`, `carg_estado`, `carg_fecha_creacion`, `carg_fecha_modificacion`, `carg_estado_logico`) VALUES 
(1, 'Dirección General', '3500', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Gerente de Departamento', '2500', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Auditor', '1500', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(4, 'Analista', '1500', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');

INSERT INTO `departamentos` (`dep_id`, `dep_nombre`, `dep_usuario_ingreso`, `dep_usuario_modifica`, `dep_estado`, `dep_fecha_creacion`, `dep_fecha_modificacion`, `dep_estado_logico`) VALUES 
(1, 'Dirección General', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Finanzas', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Administración', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(4, 'Recursos Humanos', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');

INSERT INTO `sub_departamento` (`sdep_id`, `sdep_nombre`, `dep_id`, `sdep_usuario_ingreso`, `sdep_usuario_modifica`, `sdep_estado`, `sdep_fecha_creacion`, `sdep_fecha_modificacion`, `sdep_estado_logico`) VALUES 
(1, 'Gerencia', 1, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Contabilidad', 2, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Colecturia', 2, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(4, 'Ventas', 2, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(5, 'Administración', 3, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(6, 'Recursos Humanos', 4, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');

INSERT INTO `tipo_rol` (`trol_id`, `trol_nombre`, `trol_numero_horas`, `trol_porcentaje`, `trol_usuario_ingreso`, `trol_usuario_modifica`, `trol_estado`, `trol_fecha_creacion`, `trol_fecha_modificacion`, `trol_estado_logico`) VALUES 
(1, 'Semanal', 40, 25.00, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Quincenal', 80, 50.00, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Mensual', 160, 100.00, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');

INSERT INTO `tipo_empleado` (`tipe_id`, `tipe_nombre`, `tipe_usuario_ingreso`, `tipe_usuario_modifica`, `tipe_estado`, `tipe_fecha_creacion`, `tipe_fecha_modificacion`, `tipe_estado_logico`) VALUES 
(1, 'Empleado', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Obrero', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Aprendiz', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');

INSERT INTO `tipo_liquidacion` (`tliq_id`, `tliq_nombre`, `tliq_porcentaje`, `tliq_usuario_ingreso`, `tliq_usuario_modifica`, `tliq_estado`, `tliq_fecha_creacion`, `tliq_fecha_modificacion`, `tliq_estado_logico`) VALUES 
(1, 'Renuncia libre y voluntaria', '0', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Desahucio', '0', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Despido intempestivo', '0', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');

INSERT INTO `discapacidad` (`dis_id`, `dis_nombre`, `dis_porcentaje`, `dis_usuario_ingreso`, `dis_usuario_modifica`, `dis_estado`, `dis_fecha_creacion`, `dis_fecha_modificacion`, `dis_estado_logico`) VALUES 
(1, 'Auditiva', NULL, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Física Motora', NULL, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Intelectual', NULL, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(4, 'Lenguaje', NULL, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(5, 'Mental Psicosocial', NULL, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(6, 'Visual', NULL, '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');

INSERT INTO `configuracion_rol` (`crol_id`, `crol_salario_minimo`, `crol_porcentaje_aporte_patronal`, `crol_aporte_mensual_quincena`, `crol_porcentaje_iess`, `crol_iess_mensual_quincena`, `crol_horas_trabajo`, `crol_paga_benenficios`, `crol_transporte`, `crol_transp_mensual_quincena`, `crol_alimentacion`, `crol_alimen_mensul_quincena`, `crol_usuario_ingreso`, `crol_usuario_modifica`, `crol_estado`, `crol_fecha_creacion`, `crol_fecha_modificacion`, `crol_estado_logico`) VALUES 
('1', '450', '12.15', '1', '9.45', '1', '8', '1', '0.00', '0', '0.00', '0', '1', NULL, '1', CURRENT_TIMESTAMP, NULL, '1');

INSERT INTO `tipo_contrato` (`tipc_id`, `tipc_nombre`, `tipc_usuario_ingreso`, `tipc_usuario_modifica`, `tipc_estado`, `tipc_fecha_creacion`, `tipc_fecha_modificacion`, `tipc_estado_logico`) VALUES 
(1, 'Contrato Indefinido', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(2, 'Contrato a Plazo Fijo', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(3, 'Contrato de Prueba', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(4, 'Contrato por Obra Cierta', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(5, 'Contrato por Trabajo', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(6, 'Contrato por Eventual', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(7, 'Contrato por Temporada', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(8, 'Contrato Ocacional', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(9, 'Contrato Parcial Permanente', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'),
(10, 'Contrato por Destajo', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1'), 
(11, 'Contrato Tácito', '1', NULL, '1', '2021-04-14 18:59:06', NULL, '1');