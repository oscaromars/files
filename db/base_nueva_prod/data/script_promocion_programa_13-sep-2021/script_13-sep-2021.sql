
alter table db_academico.promocion_programa add ppro_grupo varchar(10) after ppro_mes;

/*
-- Query: SELECT * FROM db_academico.promocion_programa
-- Date: 2021-09-13 12:25
*/
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (9,'2019','5','G8','MAE-P-201905-G8',2,2,15,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (10,'2019','10','G1','MEDU-O-201910-G1',2,1,24,8,100,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (11,'2019','11','G2','MEDU-O-201911-G2',2,1,24,6,100,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (12,'2019','11','G3','MEDU-O-201911-G3',2,1,24,4,100,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (13,'2020','1','G4','MEDU-O-202001-G4',2,1,24,1,50,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (14,'2020','1','G5','MEDU-O-202001-G5',2,1,24,1,50,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (15,'2020','3','G6','MEDU-O-202003-G6',2,1,24,1,110,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (16,'2020','4','G7','MEDU-O-202004-G7',2,1,24,2,50,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (17,'2020','6','G8','MEDU-O-202006-G8',2,1,24,1,100,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (18,'2020','6','G9','MEDU-O-202006-G9',2,1,24,1,100,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (19,'2020','8','G10','MEDU-O-202008-G10',2,1,24,1,100,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (20,'2020','9','G11','MEDU-O-202009-G11',2,1,24,2,50,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (21,'2020','10','G12','MEDU-O-202010-G12',2,1,24,1,100,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (22,'2020','11','G13','MEDU-O-202011-G13',2,1,24,2,50,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (23,'2021','1','G14','MEDU-O-202101-G14',2,1,24,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (24,'2021','3','G15','MEDU-O-202103-G15',2,1,24,2,50,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (25,'2021','4','G16','MEDU-O-202104-G16',2,1,24,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (26,'2021','4','G17','MEDU-O-202104-G17',2,1,24,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (27,'2021','5','G18','MEDU-O-202105-G18',2,1,24,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (28,'2021','6','G19','MEDU-O-202106-G19',2,1,24,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (29,'2021','8','G20','MEDU-O-202108-G20',2,1,24,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (30,'2021','9','G21','MEDU-S-202109-G21',2,1,24,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (31,'2020','10','G1','MAE-S-202010-G1',2,3,15,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (32,'2021','2','G2','MAE-S-202102-G2',2,3,15,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (33,'2021','7','G3','MAE-S-202107-G3',2,3,15,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (34,'2020','10','G1','MDEM-S-202010-G1',2,3,26,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (35,'2021','4','G1','MDEM-O-202104-G1',2,1,26,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (36,'2019','2','G5','MGET-P-201902-G5',2,2,19,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (37,'2019','11','G6','MGET-P-201911-G6',2,2,19,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (38,'2020','11','G1','MAPUB-O-202011-G1',2,1,43,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (39,'2021','7','G2','MAPUB-O-202107-G2',2,1,43,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (40,'2021','9','G3','MAPUB-O-202109-G3',2,1,43,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (41,'2021','7','G1','MTUR-O-202107-G1',2,1,65,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (42,'2021','2','G1','MDCO-O-202102-G1',2,1,42,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (43,'2021','6','G2','MDCO-O-202106-G2',2,1,42,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (44,'2021','9','G2','MDCO-O-202109-G2',2,1,42,2,50,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (45,'2020','10','G1','MGTH-O-202010-G1',2,1,20,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (46,'2021','3','G2','MGTH-O-202103-G2',2,1,20,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (47,'2019','5','G2','MGTH-P-201905-G2',2,2,20,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (48,'2020','8','G1','MMER-O-202008-G1',2,1,25,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (49,'2020','10','G2','MMER-O-202010-G2',2,1,25,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (50,'2019','8','G9','MSIG-S-201908-G9',2,3,18,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (51,'2019','5','G7','MFIT-S-201905-G7',2,3,16,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (52,'2019','5','G1','MNIN-S-201905-G1',2,3,23,1,25,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (53,'2021','9','G3','MMER-O-202109-G3',2,1,25,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (54,'2021','4','G1','MGPR-O-202104-G1',2,1,46,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (55,'2021','4','G1','MAE-O-202104-G1',2,1,15,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (56,'2021','2','G2','MAE-O-202102-G2',2,1,15,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (57,'2021','7','G3','MAE-O-202107-G3',2,1,15,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (58,'2021','9','G1','MSSOC-O-202109-G1',2,1,70,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (59,'2021','9','G1','MSIG-O-202109-G1',2,1,18,1,60,1,1,1);
INSERT INTO db_academico.promocion_programa (`ppro_id`,`ppro_anio`,`ppro_mes`,`ppro_grupo`,`ppro_codigo`,`uaca_id`,`mod_id`,`eaca_id`,`ppro_num_paralelo`,`ppro_cupo`,`ppro_usuario_ingresa`,`ppro_estado`,`ppro_estado_logico`) VALUES (60,'2021','9','G1','MBDCD-O-202109-G1',2,1,67,1,60,1,1,1);


/*
-- Query: SELECT * FROM db_academico.paralelo_promocion_programa
-- Date: 2021-09-13 12:24
*/
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (63,9,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (64,10,100,100,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (65,10,100,100,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (66,10,100,100,'P3',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (67,10,100,100,'P4',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (68,10,100,100,'P5',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (69,10,100,100,'P6',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (70,10,100,100,'P7',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (71,10,100,100,'P8',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (72,11,100,100,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (73,11,100,100,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (74,11,100,100,'P3',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (75,11,100,100,'P4',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (76,11,100,100,'P5',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (77,11,100,100,'P6',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (78,12,100,100,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (79,12,100,100,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (80,12,100,100,'P3',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (81,12,100,100,'P4',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (82,13,50,50,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (83,14,50,50,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (84,15,110,110,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (85,16,50,50,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (86,16,50,50,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (87,17,100,100,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (88,18,100,100,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (89,19,100,100,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (90,20,50,50,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (91,20,50,50,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (92,21,100,100,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (93,22,50,50,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (94,22,50,50,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (95,23,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (96,24,50,50,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (97,24,50,50,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (98,25,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (99,26,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (100,27,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (101,28,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (102,29,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (103,30,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (104,31,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (105,32,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (106,33,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (107,34,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (108,35,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (109,36,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (110,37,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (111,38,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (112,39,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (113,40,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (114,41,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (115,42,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (116,43,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (117,44,50,50,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (118,44,50,50,'P2',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (119,45,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (120,46,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (121,47,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (122,48,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (123,49,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (124,50,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (125,51,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (126,52,25,25,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (127,53,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (128,54,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (129,55,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (130,56,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (131,57,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (132,58,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (133,59,60,60,'P1',1,1,1);
INSERT INTO db_academico.paralelo_promocion_programa (`pppr_id`,`ppro_id`,`pppr_cupo`,`pppr_cupo_actual`,`pppr_descripcion`,`pppr_usuario_ingresa`,`pppr_estado`,`pppr_estado_logico`) VALUES (134,60,60,60,'P1',1,1,1);

update db_academico.promocion_programa 
set ppro_estado = 0,
    ppro_estado_logico = 0
where ppro_id between 1 and 8;