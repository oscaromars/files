<?php

$logFile = dirname(__FILE__) . "/../../../runtime/logs/pb.log";
$dataDB = include_once(dirname(__FILE__) . "/../config/mod.php");
$dataDBF = include_once(dirname(__FILE__) . "/../data/db_sea.php");
$payment_gateway = "";
$dbname = 'db_facturacion';
$dbuser = $dataDB["financiero"]["db_facturacion"]["username"];
$dbpass = $dataDB["financiero"]["db_facturacion"]["password"];
$port = "443";
$dbserver = "127.0.0.1"; 
$ipAddress = "127.0.0.1";
$dbport = 3306;
$seed = "";
$nounce = "";
$transKey = "";
$dsn = "mysql:host=$dbserver;dbname=$dbname;port=$dbport";
$dbnamef = 'pruebasea';
$dbuserf = $dataDBF["username"];
$dbpassf = $dataDBF["password"];
$dbserverf = "181.39.139.70"; 
$dsnf = "mysql:host=$dbserverf;dbname=$dbnamef;port=$dbport";
//spl_autoload_register('my_autoloader');
putMessageLogFile('Inicio Proceso cron:'.date('c'));
borrarTablatemporal();
consultarPagospendAsgard();
actualizarEstadoFinanciero();
putMessageLogFile('Fin Proceso cron:'.date('c'));
putMessageLogFile("-------------------------------------------*******-------------------------------------------");
function putMessageLogFile($message) {
    global $logFile;
    if (is_array($message))
        $message = json_encode($message);
    $message = date("Y-m-d H:i:s") . " " . $message . "\r\n";
    if ((filesize($logFile) / pow(1024, 2)) > 100) { // si el log es mayor a 100 MB entonces se debe limpiar el archivo
        file_put_contents($logFile, $message, LOCK_EX);
    } else {
        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
    }
}

/**
* Function Consulta las cuotas pendientes de verificar el estado de pagado en sistema financiero.
* @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
* @param   
* @return  
*/
function consultarPagospendAsgard() { 
    try {
        GLOBAL $dsn, $dbuser, $dbpass; 
        $pdo = new \PDO($dsn, $dbuser, $dbpass);
        $estado = "1";        
        $sql = "SELECT dpfa_id, dpfa_tipo_factura, dpfa_factura, dpfa_num_cuota, trim(r.per_cedula) as identificacion
                FROM db_facturacion.detalle_pagos_factura d
                    inner join db_facturacion.pagos_factura_estudiante p on p.pfes_id = d.pfes_id
                    inner join db_academico.estudiante e on e.est_id = p.est_id
                    inner join db_asgard.persona r on r.per_id = e.per_id
                where dpfa_estado_financiero is null and dpfa_estado_pago = 2
                      and dpfa_estado = " .$estado ." and dpfa_estado_logico = ". $estado
                      ." and p.pfes_estado = " . $estado . " and p.pfes_estado_logico = " . $estado
                      ." and e.est_estado = ". $estado . " and e.est_estado_logico = " . $estado;
        
        $cmd = $pdo->prepare($sql);
        $cmd->execute();    
        $rows = $cmd->fetchAll(\PDO::FETCH_ASSOC);  
        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                putMessageLogFile('en for iteracion:'.$i);
                consultarPagospendFinanciero($rows[$i]["identificacion"], $rows[$i]["dpfa_factura"], $rows[$i]["dpfa_num_cuota"], $rows[$i]["dpfa_id"]);
            }
        }
    } catch (PDOException $e) {
        putMessageLogFile('Error: ' . $e->getMessage());
        exit;
    }    
    
}
    
/**
* Function Consulta el estado de pago en sistema financiero de la factura y cuota
* @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
* @param   
* @return  
*/
 function consultarPagospendFinanciero($cedula, $factura, $cuota, $idDetFact) {
     try {
            GLOBAL $dsn, $dbuser, $dbpass, $dsnf, $dbuserf, $dbpassf; 
            $pdo2 = new \PDO($dsnf, $dbuserf, $dbpassf);            
            $pdo = new \PDO($dsn, $dbuser, $dbpass);
            $sql = "SELECT 
                     A.COD_PTO as punto_emision,
                     A.TIP_NOF as tipofactura,
                     A.NUM_NOF as factura,
                     A.COD_CLI as cliente,
                     A.CANCELA as estadopago,
                     (A.VALOR_D-A.VALOR_C-A.VAL_DEV) as saldo,
                     CASE 
                       WHEN A.NUM_DOC = A.NUM_NOF THEN ' '                                        
                       ELSE A.NUM_DOC
                     END  as numcuota
                   FROM pruebasea.CC0002 A
                   WHERE A.COD_CLI= '". $cedula ."' AND A.COD_PTO='001' AND TIP_NOF='FE' AND A.NUM_NOF = '" . $factura . "' AND A.NUM_DOC = '" . $cuota ."'";           
            //putMessageLogFile('conexion:'.$pdo2);
            $cmd = $pdo2->prepare($sql);        
            $cmd->execute();
            $rows1 = $cmd->fetchAll(\PDO::FETCH_ASSOC);              
            if ((count($rows1) > 0) && ($rows1[0]["estadopago"]=='C')) {            
                // insertar en tabla temporal                
                $id = $idDetFact;
                $tipofact = $rows1[0]["tipofactura"];
                $factura = $rows1[0]["factura"];
                $codcliente = $rows1[0]["cliente"];
                $cuotanum = $rows1[0]["numcuota"];
                $estadopago = $rows1[0]["estadopago"];
                $sql = "INSERT INTO db_facturacion.tmp_facturas_aprobadas
                        (dpfa_id, dpfa_tipo_factura, dpfa_factura, dpfa_num_cuota, identificacion, dpfa_estado_financiero)
                        values ('$id', '$tipofact','$factura', '$cuotanum', '$codcliente', '$estadopago')";                
                $cmd = $pdo->prepare($sql);
                $cmd->execute();          
            }
     } catch (PDOException $e) {
        putMessageLogFile('Error: ' . $e->getMessage());
        exit;
    }    
}

/**
* Function Actualizar el estado de pagado que se obtiene del sistema financiero.
* @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
* @param   
* @return  
*/
function actualizarEstadoFinanciero() { 
    try {
        GLOBAL $dsn, $dbuser, $dbpass; 
        $pdo = new \PDO($dsn, $dbuser, $dbpass);        
        $sql = "update db_facturacion.detalle_pagos_factura a, db_facturacion.tmp_facturas_aprobadas b
                set a.dpfa_estado_financiero = b.dpfa_estado_financiero
                where b.dpfa_id = a.dpfa_id;";
        
        $cmd = $pdo->prepare($sql);        
        $cmd->execute();        
    } catch (PDOException $e) {
        putMessageLogFile('Error: ' . $e->getMessage());
        exit;
    }        
}

/**
* Function Borrar información de la tabla temporal
* @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
* @param   
* @return  
*/
function borrarTablatemporal() { 
    try {
        GLOBAL $dsn, $dbuser, $dbpass; 
        $pdo = new \PDO($dsn, $dbuser, $dbpass);        
        $sql = "delete from db_facturacion.tmp_facturas_aprobadas;";
        
        $cmd = $pdo->prepare($sql);        
        $cmd->execute();        
    } catch (PDOException $e) {
        putMessageLogFile('Error: ' . $e->getMessage());
        exit;
    }        
}
?>
