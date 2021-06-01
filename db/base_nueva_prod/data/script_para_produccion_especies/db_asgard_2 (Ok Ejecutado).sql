use db_asgard;
INSERT INTO db_asgard.`grup_rol` (`grol_id`, `gru_id`, `rol_id`, `grol_estado`, `grol_estado_logico`) VALUES
(40, 18, 6, '1', '1'),
(41, 18, 7, '1', '1'),
(42, 18, 10, '1', '1');

INSERT INTO db_asgard.`persona` (`per_id`, `per_pri_nombre`, `per_seg_nombre`, `per_pri_apellido`, `per_seg_apellido`, `per_cedula`, `per_ruc`, `per_pasaporte`, `etn_id`, `eciv_id`, `per_genero`, `per_nacionalidad`, `pai_id_nacimiento`, `pro_id_nacimiento`, `can_id_nacimiento`, `per_nac_ecuatoriano`, `per_fecha_nacimiento`, `per_celular`, `per_correo`, `per_foto`, `tsan_id`, `per_domicilio_sector`, `per_domicilio_cpri`, `per_domicilio_csec`, `per_domicilio_num`, `per_domicilio_ref`, `per_domicilio_telefono`, `per_domicilio_celular2`, `pai_id_domicilio`, `pro_id_domicilio`, `can_id_domicilio`, `per_trabajo_nombre`, `per_trabajo_direccion`, `per_trabajo_telefono`, `per_trabajo_ext`, `pai_id_trabajo`, `pro_id_trabajo`, `can_id_trabajo`, `per_usuario_ingresa`, `per_usuario_modifica`, `per_estado`, `per_estado_logico`) VALUES
(224, 'Magdalena', 'Estefanía', 'Pérez', 'Bucaram', '0925313363', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'secretariaposgrados@uteg.edu.ec', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1');

INSERT INTO db_asgard.`usuario` (`usu_id`, `per_id`, `usu_user`, `usu_sha`, `usu_password`, `usu_time_pass`, `usu_session`, `usu_last_login`, `usu_link_activo`, `usu_estado`,  `usu_estado_logico`) VALUES
(224, 224, 'secretariaposgrados@uteg.edu.ec', 'o9bFfI_turltG-6VZK1dJjNDhZW-BCDK', 'abgzqrRY8MsxOhfXn4+irWY1YTEyZDU1MTlmYjAzY2Y4MWZkNDQ4YTllZGUzNzkxM2NlNzQ2MTQ5YjFjZTQ5NzhjYjUzYThjMjFmY2M0ZTEsQEUDiDKEHH8BLce0DConHe/XLMahcJa0OMeW9x+AbNh5Tmfr/kR49mdqVBJp8V3EPtHd/xyloN+qn51iRW9Y', NULL, NULL, NULL, NULL, '1', '1');

INSERT INTO db_asgard.`empresa_persona` (`eper_id`, `emp_id`, `per_id`, `eper_estado`, `eper_estado_logico`) VALUES
(224, 1, 224, '1', '1');

INSERT INTO db_asgard.`usua_grol_eper` (`ugep_id`, `eper_id`, `usu_id`, `grol_id`, `ugep_estado`, `ugep_estado_logico`) VALUES 
(224, 224, 224, 26, '1', '1');

update db_asgard.usua_grol_eper 
set grol_id = 22
where usu_id in (215,217);

update db_asgard.usua_grol_eper 
set grol_id = 18
where usu_id in (216);

update db_asgard.usua_grol_eper 
set grol_id = 42
where usu_id in (218);

update db_asgard.usua_grol_eper 
set grol_id = 41
where usu_id in (219);