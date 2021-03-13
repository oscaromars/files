
-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: db_gfinanciero
-- ------------------------------------------------------
-- Server version	5.6.40

USE `db_gfinanciero` ;


--
-- Definition of procedure `ACT_COSTO_VC`
--

DROP PROCEDURE IF EXISTS `ACT_COSTO_VC`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`root`@`%` PROCEDURE `ACT_COSTO_VC`()
begin

  DECLARE done INT DEFAULT 0;
  DECLARE a VARCHAR(60);
  DECLARE b VARCHAR(60);
  DECLARE c VARCHAR(60);
  DECLARE d VARCHAR(60);
  DECLARE e decimal(12,2);
  DECLARE cur CURSOR FOR SELECT COD_PTO, COD_cAJ, TIP_NOF, NUM_NOF, SUM(T_COSTO) FROM VD010101 WHERE IND_EST<>"A" AND YEAR(FEC_VTA)=2011 GROUP BY COD_PTO, COD_CAJ, TIP_NOF, NUM_NOF;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;



 OPEN cur;

  REPEAT
    FETCH cur INTO a, b, c, d, e;
        UPDATE VC010101 SET VAL_COS= e WHERE COD_PTO=a AND COD_CAJ=b AND TIP_NOF=c AND NUM_NOF=d;


  UNTIL done END REPEAT;

  CLOSE cur;


    end $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of procedure `P_act_inventario`
--

DROP PROCEDURE IF EXISTS `P_act_inventario`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`root`@`%` PROCEDURE `P_act_inventario`(IN id_prod VARCHAR(20),IN cost_pro NUMERIC(12,4),IN exi_total NUMERIC(8,2))
begin

        select P_COSTO,POR_N01,POR_N02,POR_N03,POR_N04 into @p_costo,@por_n01,@por_n02,@por_n03,@por_n04 from ig0020 where Cod_art = id_prod;


        if (cost_pro<@p_costo) then
            SET @cost_promedio=@p_costo;
        else
            SET @cost_promedio=cost_pro;
        END IF;

        set @paux_03=@cost_promedio/((100-@por_n01)/100);
        set @p_venta=@cost_promedio/((100-@por_n02)/100);
        set @paux_01=@cost_promedio/((100-@por_n03)/100);
        set @paux_02=@cost_promedio/((100-@por_n04)/100);
        Update IG0020 set P_COSTO=@cost_promedio,P_LISTA=@cost_promedio,P_PROME=@cost_promedio,paux_03=@paux_03,p_venta=@p_venta,paux_01=@paux_01,paux_02=@paux_02,exi_tot=exi_total where Cod_art = id_prod;



    end $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of procedure `P_CATALOGO_NIIF`
--

DROP PROCEDURE IF EXISTS `P_CATALOGO_NIIF`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`root`@`%` PROCEDURE `P_CATALOGO_NIIF`(IN OP VARCHAR(2))
BEGIN

  DECLARE done1 INT DEFAULT 0;
  DECLARE a1 VARCHAR(12);
  DECLARE b1 VARCHAR(12);
  DECLARE c1 VARCHAR(120);
  DECLARE cur1 CURSOR FOR SELECT COD_CTA,CTA_SEA,NOM_CTA FROM CATALOGO;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done1 = 1;

  OPEN cur1;
  REPEAT
    FETCH cur1 INTO a1, b1, c1;
	IF(OP='01') THEN
            UPDATE REGISTRO SET COD_CTA=a1,NOM_CTA=c1 WHERE COD_CTA=b1;
    ELSE
        UPDATE REGISTRO00 SET COD_CTA=a1,NOM_CTA=c1 WHERE COD_CTA=b1;
    END IF;

  UNTIL done1 END REPEAT;

  CLOSE cur1;
END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of function `F_CARTERA`
--

DROP FUNCTION IF EXISTS `F_CARTERA`;

DELIMITER $$

SET GLOBAL log_bin_trust_function_creators = 1;  -- AND REMOVE NO_AUTO_CREATE_USER
/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ $$
CREATE DEFINER=`root`@`%` FUNCTION `F_CARTERA`(nCOD_CLI VARCHAR(10),nFEC_INI DATE,nFEC_FIN DATE) RETURNS decimal(10,2)
BEGIN
    DECLARE SALDO DECIMAL(10,2);

     SELECT SUM(IFNULL(A.VALOR_D,0))-
              IFNULL((SELECT SUM(IFNULL(D.VALOR_C,0)) FROM CC0011 D
                WHERE D.IND_UPD='L' AND D.TIP_NOF=A.TIP_NOF AND D.NUM_NOF=A.NUM_NOF AND D.COD_CLI=nCOD_CLI
                  AND D.F_BOL_C BETWEEN nFEC_INI AND nFEC_FIN ),0)-A.VAL_DEV INTO SALDO
                FROM CC0002 A
             WHERE A.F_SUS_D BETWEEN nFEC_INI AND nFEC_FIN AND A.COD_CLI=nCOD_CLI;
    RETURN SALDO;

  END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;
