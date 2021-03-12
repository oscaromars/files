
-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: db_gfinanciero
-- ------------------------------------------------------
-- Server version	5.6.40

USE `db_gfinanciero` ;

--
-- Definition of trigger `ModificarStockBodegaIng`
--

DROP TRIGGER /*!50030 IF EXISTS */ `ModificarStockBodegaIng`;

DELIMITER $$

CREATE DEFINER = `root`@`%` TRIGGER `ModificarStockBodegaIng` AFTER INSERT ON `IG0026` FOR EACH ROW Update IG0022
set
    T_Ui_Ac=T_Ui_Ac+New.Can_Ped,
    T_Ic_Ac=T_Ic_Ac+(New.P_Costo * New.Can_Ped),
    Exi_Tot=Exi_Tot+New.Can_Ped,
    P_Costo=New.P_Costo,
    P_Lista=New.P_Lista
WHERE Cod_Bod=New.Cod_Bod
AND Cod_Art=New.Cod_Art
AND New.Ind_EST <> 'A' $$

DELIMITER ;


--
-- Definition of trigger `ModificarStockBodegaEgr`
--

DROP TRIGGER /*!50030 IF EXISTS */ `ModificarStockBodegaEgr`;

DELIMITER $$

CREATE DEFINER = `root`@`%` TRIGGER `ModificarStockBodegaEgr` AFTER INSERT ON `IG0028` FOR EACH ROW Update IG0022
set T_Ue_Ac=T_Ue_Ac+New.Can_Ped,
    T_Ec_Ac=T_Ec_Ac+(New.P_Costo * New.Can_Ped),
    Exi_Tot=Exi_Tot-New.Can_Ped  
WHERE Cod_Bod=New.Cod_Bod
AND Cod_Art=New.Cod_Art
AND New.Ind_EST <> 'A' $$

DELIMITER ;

--
-- Definition of trigger `Modificarproveedor`
--

DROP TRIGGER /*!50030 IF EXISTS */ `Modificarproveedor`;

DELIMITER $$

CREATE DEFINER = `root`@`%` TRIGGER `Modificarproveedor` AFTER INSERT ON `IG0050` FOR EACH ROW BEGIN
 Update MG0032 set N_S_PRO=new.N_S_PRO, N_A_PRO=new.N_A_PRO, FEC_CAD=new.FEC_CAD where cod_pro = New.cod_pro;
 Update CB0000 set NUM_S_R=new.NUM_S_R, NUM_A_R=new.NUM_A_R;
END $$

DELIMITER ;


--
-- Definition of trigger `ModificarStockNC`
--

DROP TRIGGER /*!50030 IF EXISTS */ `ModificarStockNC`;

DELIMITER $$

CREATE DEFINER = `root`@`%` TRIGGER `ModificarStockNC` AFTER INSERT ON `ND010101` FOR EACH ROW Update IG0020
set Exi_Com = if (Exi_Com-New.Can_Des<0,0,Exi_Com-New.Can_Des),
    Exi_Tot = Exi_Tot-new.Can_Des
where Cod_art = New.Cod_Art
and NEW.Ind_Est <> 'A' $$

DELIMITER ;


--
-- Definition of trigger `ModificarStockVentas`
--

DROP TRIGGER /*!50030 IF EXISTS */ `ModificarStockVentas`;

DELIMITER $$

CREATE DEFINER = `root`@`%` TRIGGER `ModificarStockVentas` AFTER INSERT ON `VD010101` FOR EACH ROW Update IG0020
set Exi_Com = if (Exi_Com-New.Can_Des<0,0,Exi_Com-New.Can_Des),
    Exi_Tot = Exi_Tot-new.Can_Des
where Cod_art = New.Cod_Art
and NEW.Ind_Est <> 'A' $$

DELIMITER ;
