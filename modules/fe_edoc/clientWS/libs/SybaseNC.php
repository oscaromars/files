<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SybaseFactura
 *
 * @author Byron
 */
include_once "libs/http.php";
include_once "libs/Global.php";
include_once "libs/cls_BaseSybase.php";
class SybaseNC {
   private $tipoDoc = '04'; //Tipo Doc SRI

    //put your code here

    public function consultarSybCabFacturas() {//OK
        GLOBAL $limit, $WS_URI, $WS_PORT, $WS_HOST,$timeWait;;
        $obj_con = new cls_BaseSybase();
        $pdo = $obj_con->conexionSybase();
        try {
            $sql = "SELECT TOP $limit * FROM DBA.TCIDE_FACTURANC_TEMP WHERE estado_proceso=0 "
                    . " AND TIPOCOMPROBANTE='04'";
            $comando = $pdo->prepare($sql);
            $comando->execute();
            $rows = $comando->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) > 0) {
                for ($i = 0; $i < sizeof($rows); $i++) {
                    //putMessageLogFile($rows[$i]['SYS_FACTURANC_ID']);
                    $tipEdoc = $this->tipoDoc;//"01";
                    $cabEdoc = $rows[$i];//Cabecera de Factura
                    $detEdoc = $this->consultarSybDetFacturas($pdo, $cabEdoc['SYS_FACTURANC_ID']);
                    $dadcEdoc = $this->consultarSybDatAdiFacturas($pdo, $cabEdoc['SYS_FACTURANC_ID']);
                    $fpagEdoc = null;//$this->consultarSybForPagFacturas($pdo, $cabEdoc['SYS_FACTURANC_ID']);

                    $response = Http::connect($WS_HOST, $WS_PORT)->doPost($WS_URI, 
                            array('tipoEdoc' => $tipEdoc, 'cabEdoc' => json_encode($cabEdoc), 'detEdoc' => json_encode($detEdoc), 
                                  'dadcEdoc' => json_encode($dadcEdoc), 'fpagEdoc' => json_encode($fpagEdoc)));
                    //putMessageLogFile($response);
                    $arr_response = json_decode($response, true);
                    if ($arr_response["state"] == 200 && $arr_response["error"] == 'false') {
                        //putMessageLogFile("OK");
                        $estado = $arr_response["message"];
                        //putMessageLogFile($estado);
                        $rows[$i]['ESTADO'] = $estado["status"];                        
                        // actualizar registro en sysbase
                    } else {
                        //putMessageLogFile("ERROR");
                        $rows[$i]['ESTADO']='NO_OK';
                        // no actualizar registro en sysbase y enviar mail de error a sysadmin
                    }
                    sleep($timeWait);
                }
                //putMessageLogFile($rows);
                for ($i = 0; $i < sizeof($rows); $i++) {
                    if($rows[$i]['ESTADO']=='OK'){
                        $this->actualizarEstadoDoc($rows[$i]['SYS_FACTURANC_ID'],1);
                    }else{
                        $this->actualizarEstadoDoc($rows[$i]['SYS_FACTURANC_ID'],2);
                    }
                }
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            putMessageLogFile("Error: " . $e);
            return FALSE;
        }
    }

    private function consultarSybDetFacturas($pdo, $Ids) {//OK
        $rawData = array();
        $sql = "SELECT * FROM DBA.TCIDE_FACTURANC_DET WHERE SYS_FACTURANC_ID=:id ORDER BY ID_SECUENCIA ";
        $comando = $pdo->prepare($sql);
        $comando->bindParam(":id", $Ids, PDO::PARAM_INT);
        $comando->execute();
        $rows = $comando->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                //putMessageLogFile($row['CODIGOPRINCIPAL'] . "<br>");
                $rawData[] = $row;
            }
        }
        return $rawData;
    }

    private function consultarSybDatAdiFacturas($pdo, $Ids) {//OK
        $rawData = array();
        $sql = "SELECT * FROM DBA.TCIDE_FACTURANC_DATADD WHERE SYS_FACTURANC_ID=:id ORDER BY SECUENCIA ";
        $comando = $pdo->prepare($sql);
        $comando->bindParam(":id", $Ids, PDO::PARAM_INT);
        $comando->execute();
        $rows = $comando->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $rawData[] = $row;
            }
        } 
        return $rawData;
    }
    
    private function consultarSybForPagFacturas($pdo, $Ids) {//OK
        $rawData = array();
        $sql = "SELECT * FROM DBA.TCIDE_FACTURANC_FPAG WHERE SYS_FACTURANC_ID=:id ORDER BY SECUENCIA ";
        $comando = $pdo->prepare($sql);
        $comando->bindParam(":id", $Ids, PDO::PARAM_INT);
        $comando->execute();
        $rows = $comando->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $rawData[] = $row;
            }
        } 
        return $rawData;
    }
    
    private function actualizarEstadoDoc($id_docElectronico,$EstPro) { //OK
        $obj_con = new cls_BaseSybase();
        $pdo = $obj_con->conexionSybase();
        $pdo->beginTransaction();
        try {
            $sql = "UPDATE DBA.TCIDE_FACTURANC_TEMP SET estado_proceso=:estado
                        WHERE SYS_FACTURANC_ID=:id ";
            $comando = $pdo->prepare($sql);
            $comando->bindParam(":id", $id_docElectronico, PDO::PARAM_INT);
            $comando->bindParam(":estado", $EstPro, PDO::PARAM_INT);
            $resultado = $comando->execute();
            if ($resultado) {
                $pdo->commit();
                return TRUE;
            } else {
                $pdo->rollBack();
                putMessageLogFile("No se pudo actualizar el DOCUMENTO en la Base de datos. Datos recibidos -> docId: $id_docElectronico");
                return FALSE;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            putMessageLogFile("Error: No se pudo registrar el ESTADO en la Base de datos. Datos recibidos -> docId: $id_docElectronico");
        }
        return FALSE;
    }


}
