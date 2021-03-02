use 'db_asgard';

INSERT INTO `persona` (`per_id`, `per_pri_nombre`, `per_seg_nombre`, `per_pri_apellido`, `per_seg_apellido`, `per_cedula`, `per_ruc`, `per_pasaporte`, `etn_id`, `eciv_id`, `per_genero`, `per_nacionalidad`, `pai_id_nacimiento`, `pro_id_nacimiento`, `can_id_nacimiento`, `per_nac_ecuatoriano`, `per_fecha_nacimiento`, `per_celular`, `per_correo`, `per_foto`, `tsan_id`, `per_domicilio_sector`, `per_domicilio_cpri`, `per_domicilio_csec`, `per_domicilio_num`, `per_domicilio_ref`, `per_domicilio_telefono`, `per_domicilio_celular2`, `pai_id_domicilio`, `pro_id_domicilio`, `can_id_domicilio`, `per_trabajo_nombre`, `per_trabajo_direccion`, `per_trabajo_telefono`, `per_trabajo_ext`, `pai_id_trabajo`, `pro_id_trabajo`, `can_id_trabajo`, `per_usuario_ingresa`, `per_usuario_modifica`, `per_estado`, `per_fecha_creacion`, `per_fecha_modificacion`, `per_estado_logico`) VALUES
(215, 'Ocdrey', 'Carolina', 'Davila', 'Bracho', '0963266895', NULL, NULL, NULL, NULL, 'F', NULL, 1, 10, 87, NULL, NULL, NULL, 'secretariapresencial@uteg.edu.ec', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, 87, NULL, NULL, NULL, NULL, 1, 10, 87, NULL, NULL, '1', '2019-02-12 18:40:00', NULL, '1'),
(216, 'Shirley', 'Elizabeth', 'Serrano', 'Franco', '0923992549', NULL, NULL, NULL, NULL, 'F', NULL, 1, 10, 87, NULL, NULL, NULL, 'secretariaonline@uteg.edu.ec', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, 87, NULL, NULL, NULL, NULL, 1, 10, 87, NULL, NULL, '1', '2019-02-12 18:40:00', NULL, '1'),
(217, 'Mariela', 'Natividad', 'Coello', 'Villamarin', '1204408007', NULL, NULL, NULL, NULL, 'F', NULL, 1, 10, 87, NULL, NULL, NULL, 'secretariasemipresencial@uteg.edu.ec', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, 87, NULL, NULL, NULL, NULL, 1, 10, 87, NULL, NULL, '1', '2019-02-12 18:40:00', NULL, '1');

INSERT INTO `usuario` (`usu_id`, `per_id`, `usu_user`, `usu_sha`, `usu_password`, `usu_time_pass`, `usu_session`, `usu_last_login`, `usu_link_activo`, `usu_estado`, `usu_fecha_creacion`, `usu_fecha_modificacion`, `usu_estado_logico`) VALUES
(215, 215, 'secretariapresencial@uteg.edu.ec', 'J92fAQwRQtgvejocmgyRKk_XNxydOxNs', 'yjUDos8cWYQisOlukVQnkzBhZjE5MWJhMzE3NzE5ZTdjNWVkZjk1MDhkYWYyNDhhMGUwZjRhOGQyOGQzODQ2ZmY4MzFmYWE0MjdlYTJlY2JfjZLiebu2gK4339g0Q+w2NAX7wuGqZ6M3T7JIeaOg1UnkT8xCbIhjCkm/NtnYzvHnQ39Ex7CUaiM/DQSvQG77', NULL, NULL, NULL, NULL, '1', '2020-03-24 09:14:00', NULL, '1'),
(216, 216, 'secretariaonline@uteg.edu.ec', 'J92fAQwRQtgvejocmgyRKk_XNxydOxNs', 'yjUDos8cWYQisOlukVQnkzBhZjE5MWJhMzE3NzE5ZTdjNWVkZjk1MDhkYWYyNDhhMGUwZjRhOGQyOGQzODQ2ZmY4MzFmYWE0MjdlYTJlY2JfjZLiebu2gK4339g0Q+w2NAX7wuGqZ6M3T7JIeaOg1UnkT8xCbIhjCkm/NtnYzvHnQ39Ex7CUaiM/DQSvQG77', NULL, NULL, NULL, NULL, '1', '2020-03-24 09:14:00', NULL, '1'),
(217, 217, 'secretariasemipresencial@uteg.edu.ec', 'J92fAQwRQtgvejocmgyRKk_XNxydOxNs', 'yjUDos8cWYQisOlukVQnkzBhZjE5MWJhMzE3NzE5ZTdjNWVkZjk1MDhkYWYyNDhhMGUwZjRhOGQyOGQzODQ2ZmY4MzFmYWE0MjdlYTJlY2JfjZLiebu2gK4339g0Q+w2NAX7wuGqZ6M3T7JIeaOg1UnkT8xCbIhjCkm/NtnYzvHnQ39Ex7CUaiM/DQSvQG77', NULL, NULL, NULL, NULL, '1', '2020-03-24 09:14:00', NULL, '1');

INSERT INTO `empresa_persona` (`eper_id`, `emp_id`, `per_id`, `eper_estado`, `eper_fecha_creacion`, `eper_fecha_modificacion`, `eper_estado_logico`) VALUES
(215, 1, 215, '1', '2020-03-24 09:14:00', NULL, '1'),
(216, 1, 216, '1', '2020-03-24 09:14:00', NULL, '1'),
(217, 1, 217, '1', '2020-03-24 09:14:00', NULL, '1');

--
-- Volcado de datos para la tabla `usua_grol_eper`
--
INSERT INTO `usua_grol_eper` (`ugep_id`, `eper_id`, `usu_id`, `grol_id`, `ugep_estado`, `ugep_fecha_creacion`, `ugep_fecha_modificacion`, `ugep_estado_logico`) VALUES
(215, 215, 215, 22, '1', '2020-03-24 09:14:00', NULL, '1'),
(216, 216, 216, 18, '1', '2020-03-24 09:14:00', NULL, '1'),
(217, 217, 217, 22, '1', '2020-03-24 09:14:00', NULL, '1');