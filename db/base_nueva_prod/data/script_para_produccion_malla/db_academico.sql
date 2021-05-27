/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  analistadesarrollo03
 * Created: 05-ago-2020
 */

alter table db_academico.malla_academica drop uaca_id;
alter table db_academico.malla_academica drop mod_id;
alter table db_academico.malla_academica drop eaca_id;
alter table db_academico.malla_academica drop foreign key malla_academica_ibfk_1;
alter table db_academico.malla_academica drop meun_id;
delete from db_academico.malla_unidad_modalidad;
delete from db_academico.malla_academica;
alter table db_academico.malla_academica_detalle add made_semestre int not null after asi_id;
alter table db_academico.malla_academica_detalle add made_asi_requisito bigint(20) null after fmac_id;
alter table db_academico.malla_academica_detalle add made_horas_docencia integer(4) null after made_codigo_asignatura;
alter table db_academico.malla_academica_detalle add made_horas_otros integer(4) null after made_horas_docencia;

-- --------------------------------------------------------
-- 
-- Estructura de tabla para la tabla `malla_unidad_modalidad` 
-- --------------------------------------------------------
create table db_academico.malla_unidad_modalidad
(`mumo_id` bigint(20) not null auto_increment primary key,
 `maca_id` bigint(20) not null,
 `meun_id` bigint(20) not null,  
 `mumo_estado` varchar(1) not null,
 `mumo_fecha_creacion` timestamp not null default current_timestamp,
 `mumo_fecha_modificacion` timestamp null default null,
 `mumo_estado_logico` varchar(1) not null,
  foreign key (maca_id) references `malla_academica`(maca_id), 
  foreign key (meun_id) references `modalidad_estudio_unidad`(meun_id)
);