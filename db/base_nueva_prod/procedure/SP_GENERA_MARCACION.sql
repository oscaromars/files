-- drop procedure db_academico.SP_GENERA_MARCACION;
-- CALL db_academico.SP_GENERA_MARCACION (10)
DELIMITER $$
CREATE PROCEDURE db_academico.SP_GENERA_MARCACION 
	(IN pi_periodo BIGINT)
BEGIN	
	-- Variable para controlar el fin del bucle
	DECLARE fin INTEGER DEFAULT 0;
	DECLARE	v_hape_id INTEGER;
    DECLARE v_uaca_id INTEGER;
    DECLARE v_mod_id  INTEGER; 
    DECLARE v_dia_id  INTEGER;
	DECLARE v_fec_ini  DATE;
	DECLARE v_fec_fin  DATE;
    DECLARE v_fecha	DATE;
    DECLARE v_fecha_clase DATE;
    
	-- Declaración de Cursor
    DECLARE C_Horario CURSOR FOR
		SELECT h.hape_id, h.uaca_id, h.mod_id, dia_id, h.hape_fecha_clase
        FROM db_academico.horario_asignatura_periodo h
        WHERE h.paca_id = pi_periodo
              and h.hape_estado = '1' 
              and h.hape_estado_logico = '1';
             
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin = 1;
    select paca_fecha_inicio, paca_fecha_fin
    into v_fec_ini, v_fec_fin
    from db_academico.periodo_academico 
    where paca_id = pi_periodo;
    
    OPEN C_Horario;
    getHorario: LOOP
		FETCH C_Horario INTO v_hape_id, v_uaca_id, v_mod_id, v_dia_id, v_fecha_clase;
        IF fin = 1 THEN 
            LEAVE getHorario;
        END IF;
        
        -- Determina la fecha inicial
        -- if (v_mod_id=2 or v_mod_id=3) then
        if (v_fecha_clase is null) then
			if v_dia_id=1 then
				set v_fecha = v_fec_ini;
			elseif v_dia_id=2 then
				set v_fecha = ADDDATE(v_fec_ini, INTERVAL 1 DAY);
			elseif v_dia_id=3 then
				set v_fecha = ADDDATE(v_fec_ini, INTERVAL 2 DAY);
			elseif v_dia_id=4 then
				set v_fecha = ADDDATE(v_fec_ini, INTERVAL 3 DAY);
			elseif v_dia_id=5 then
				set v_fecha = ADDDATE(v_fec_ini, INTERVAL 4 DAY);
			else
				set v_fecha = ADDDATE(v_fec_ini, INTERVAL 5 DAY);
			end if;        
        
			WHILE v_fecha <= v_fec_fin DO
				INSERT INTO db_academico.`registro_marcacion_generada` (`hape_id`, `paca_id`, `uaca_id`, `mod_id`, `rmtm_fecha_transaccion`)
				VALUES (v_hape_id, pi_periodo, v_uaca_id, v_mod_id, v_fecha);
				SET v_fecha = ADDDATE(v_fecha, INTERVAL 7 DAY);
			END WHILE;
		else
			INSERT INTO db_academico.`registro_marcacion_generada` (`hape_id`, `paca_id`, `uaca_id`, `mod_id`, `rmtm_fecha_transaccion`)
			VALUES (v_hape_id, pi_periodo, v_uaca_id, v_mod_id, v_fecha_clase);
		end if;
        
	END LOOP getHorario;
	CLOSE C_Horario;
END$$;
DELIMITER ;