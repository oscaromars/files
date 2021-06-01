INSERT INTO db_asgard.`persona` (`per_id`, `per_pri_nombre`, `per_seg_nombre`, `per_pri_apellido`, `per_seg_apellido`, `per_cedula`, `per_ruc`, `per_pasaporte`, `etn_id`, `eciv_id`, `per_genero`, `per_nacionalidad`, `pai_id_nacimiento`, `pro_id_nacimiento`, `can_id_nacimiento`, `per_nac_ecuatoriano`, `per_fecha_nacimiento`, `per_celular`, `per_correo`, `per_foto`, `tsan_id`, `per_domicilio_sector`, `per_domicilio_cpri`, `per_domicilio_csec`, `per_domicilio_num`, `per_domicilio_ref`, `per_domicilio_telefono`, `per_domicilio_celular2`, `pai_id_domicilio`, `pro_id_domicilio`, `can_id_domicilio`, `per_trabajo_nombre`, `per_trabajo_direccion`, `per_trabajo_telefono`, `per_trabajo_ext`, `pai_id_trabajo`, `pro_id_trabajo`, `can_id_trabajo`, `per_usuario_ingresa`, `per_usuario_modifica`, `per_estado`, `per_estado_logico`) VALUES
(225, 'Stefanie', 'Jazmín', 'Zambrano', 'Celi', '0925495988', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'stefanie.zambranoceli@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1'),
(226, 'Pablo', 'Andrés', 'Proaño', 'Durán', '1714821541', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pabloapd@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1'),
(227, 'Karina', 'Marisol', 'Alvarado', 'Quito', '0916864366', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'alvaradokarina66@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1'),
(228, 'Diana', 'Ekatherine', 'González', 'Guseva', '0704907765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'diana.gonzalez.g@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1'),
(229, 'Francisco', '', 'Landeta', 'Alava', '0915004246', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'francisco.landeta.alava@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1'),
(230, 'Jéssica', '', 'Toussaint', '', '0932846561', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jess-009@hotmail.fr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1');				
	

INSERT INTO db_asgard.`usuario` (`usu_id`, `per_id`, `usu_user`, `usu_sha`, `usu_password`, `usu_time_pass`, `usu_session`, `usu_last_login`, `usu_link_activo`, `usu_estado`,  `usu_estado_logico`) VALUES
(225, 225, 'stefanie.zambranoceli@hotmail.com', 'F5HMxplcaMPYdJu5pgOySJqBvEo2NE2Q', 'mcp/4dJyobq+23qOzwZi22Q1ZmI5OWM0MzYxY2E3MzY4NDE3YzUwNTMxYWYzZWRmOWZiNGJjOWVlNGIzNjFlYzRhZWUyZmQ1ZTlmMjljOWYwoLym58ISY05zvX1BNgtSkPFluH2qZtqxnd+PhcGIEaZUTGLRn0MD2nTDhpJeOvcBblN7kStkhdd1nOK4oct/', NULL, NULL, NULL, NULL, '1', '1'),
(226, 226, 'pabloapd@hotmail.com', '3F2nogQoxYEQdI7-TpanmNcMjfLA5e1R', '4eOFtFR1G6VQJiKNMyAVWDI1Y2E5M2UyODI1ZGE5NjVhYjEwMjg1YTExNmJmODk5MjcwYjhiODU1MDlmMmU4YTVlNGZhOGQ2YjdlZmFhODDKobg3MjJsApRgHSgQUfWQEXU4xXkE/gyjDCJwmBzrJNxf/qOqCIPTkITSehgiAgnPEXiQFkqdptA0wU7ZHEqr', NULL, NULL, NULL, NULL, '1', '1'),
(227, 227, 'alvaradokarina66@gmail.com', '0qk_VwcXAXCBFIOZiLb6sjmN2hgAuGHd', '1p1IG/HRX0PvJq5JmNbgSzEyNTZmMzI0M2ZjNWY2MTNlZmMwOGE2MmVkMDI3NjBiNGFjNWNiNTFjN2NmYjRkN2M3MDdlYzJlNzAyOTA5ZGMXt92zvFL8NSNQE8H7peiKVbchE5MWG9WSX51hQQYiC+DmWjs/Af8GLNaTxzvJyQ/Gc082yaO9DKX7UFdT17Cd', NULL, NULL, NULL, NULL, '1', '1'),
(228, 228, 'diana.gonzalez.g@hotmail.com', '-KULWkqIhOauqKNmM4TSZza-CTjroeVc', 'MKu2ZM7uxqX0FSMsxUrAjjhmZTVlOWEzZGE0NDFmNzFhOWQ3MzE2MjFmNmQ3OTAwYmJkY2RlNDQ5Yjg5ZTAyNzYxNmE4MzA4OTYzZmQwODYAjS3ZGqgRCkUM5WvDzX/1PvWX8BWAc0ev78g6aqqVX+58GdP1BUowvpkvSdZ+4Mvspv9dNY8ZtWEsEcrKW2AM', NULL, NULL, NULL, NULL, '1', '1'),
(229, 229, 'francisco.landeta.alava@hotmail.com', 'xF7UHSS4W2mn370fpzUdjprDSGAwPK7J', 'd7mwfY0N7pShcBbDg0nAN2ViZjU4MWVlYTUyMDE2OTlhYTI3ZTljMzZmZWQ2MTllNTA0Njk0ZTQ5NDQwMWU5MjhhYTVjOWY5ZWM0MjhlM2HAZXYdCFAC8e1WraV2lh2RpnMXRMifMQ84qTRWgpx0EaJ/dBN9uJjS2HdIWH2Apwcrz7SMY/d1CDE53H4Gosyf', NULL, NULL, NULL, NULL, '1', '1'),
(230, 230, 'Jess-009@hotmail.fr', '2o9jWIZdF7DiCUksoUGpQ7PBlN-ha8hb', '2+zlJrw73jxZtk3dea283mNkYzVhYzg2YmUxYTQ4NGZhZDliZjNhODZlNjA1ODAwNDlhYTdkMDYzOTM3ZmY2ZTk5NzViMzI4Y2I5OWZmNjLwaqjznWZkNkulzZJ4aXexRxjkayHn5qrXjkw340TxywuJaRWJ+UmetYkgaSAUx4wH9KFathNL4pV8vqqPFYYV', NULL, NULL, NULL, NULL, '1', '1');


INSERT INTO db_asgard.`empresa_persona` (`eper_id`, `emp_id`, `per_id`, `eper_estado`, `eper_estado_logico`) VALUES
(225, 1, 225, '1', '1'),
(226, 1, 226, '1', '1'),
(227, 1, 227, '1', '1'),
(228, 1, 228, '1', '1'),
(229, 1, 229, '1', '1'),
(230, 1, 230, '1', '1');


INSERT INTO db_asgard.`usua_grol_eper` (`ugep_id`, `eper_id`, `usu_id`, `grol_id`, `ugep_estado`, `ugep_estado_logico`) VALUES 
(225, 225, 225, 33, '1', '1'),
(226, 226, 226, 33, '1', '1'),
(227, 227, 227, 33, '1', '1'),
(228, 228, 228, 33, '1', '1'),
(229, 229, 229, 33, '1', '1'),
(230, 230, 230, 33, '1', '1');
