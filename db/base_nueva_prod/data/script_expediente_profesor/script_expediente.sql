insert into db_general.nivel_idioma (nidi_id, nidi_descripcion, nidi_estado, nidi_estado_logico) values (4, 'Nativo', 1, 1);
alter table db_academico.profesor_coordinacion add ins_id bigint after pcoo_academico;
alter table db_academico.profesor_coordinacion drop pcoo_institucion; 
delete FROM db_general.tipo_publicacion;

INSERT INTO db_general.`tipo_publicacion` (`tpub_id`, `tpub_nombre`, `tpub_descripcion`, `tpub_estado`, `tpub_estado_logico`) VALUES
(1, 'Artículo', 'Artículo', '1', '1'),
(2, 'Ponencias', 'Ponencias', '1', '1'),
(3, 'Libro', 'Libro', '1', '1'),
(4, 'Capítulo de Libro', 'Capítulo de Libro', '1', '1');

alter table db_academico.profesor_publicacion add tpub_id bigint after pro_id;
alter table db_academico.profesor_publicacion drop ppub_produccion;