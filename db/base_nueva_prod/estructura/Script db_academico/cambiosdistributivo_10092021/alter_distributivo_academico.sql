/*alter tabla distributivo academico columna y FK*/
use db_academico;

ALTER TABLE `db_academico`.`distributivo_academico`
CHANGE COLUMN `dhpa_id` `pppr_id` BIGINT(20) NULL DEFAULT NULL ;

ALTER TABLE  db_academico.distributivo_academico
ADD FOREIGN KEY (pppr_id) REFERENCES `paralelo_promocion_programa`(pppr_id);