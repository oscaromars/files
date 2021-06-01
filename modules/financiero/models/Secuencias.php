<?php

//namespace app\models;
namespace app\modules\financiero\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use PHPUnit\Framework\Constraint\Exception;
use app\models\Utilities;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Secuencias extends \app\modules\financiero\components\CActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'secuencias';
    }
    
     /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function retonra la secuencia segun el tipo de documento 
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property conexion de base,empresa,establecimiento,punto de emision, tipo de documento      
     * @return  
     */
    
    public static function nuevaSecuencia($con,$emp_id = NULL,$estab_id = NULL,$pemis_id = NULL,$secu_tipo_doc = NULL){
        $numero=0;
        try{
            $sql="SELECT IFNULL(CAST(secuencia AS UNSIGNED),0) secuencia FROM " . yii::$app->db_facturacion->dbname . ".secuencias 
                    WHERE secu_estado=1  ";
            if ($emp_id != NULL){$sql.=" AND emp_id=:emp_id ";}
            if ($estab_id != NULL){$sql.=" AND estab_id=:estab_id ";}
            if ($pemis_id != NULL){$sql.=" AND pemis_id=:pemis_id ";}
            if ($secu_tipo_doc != NULL){$sql.=" AND secu_tipo_doc=:secu_tipo_doc ";}
            $sql.=" FOR UPDATE ";                        
            
            $comando = $con->createCommand($sql);
            $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
            $comando->bindParam(":estab_id", $estab_id, \PDO::PARAM_INT);
            $comando->bindParam(":pemis_id", $pemis_id, \PDO::PARAM_INT);
            $comando->bindParam(":secu_tipo_doc", $secu_tipo_doc, \PDO::PARAM_STR);
            //$rawData=$comando->queryAll();     
            $rawData=$comando->queryScalar();   
            //if (count($rawData) > 0) { 
            if ($rawData !== false){
                //$numero=str_pad((int)$rawData[0]["secuencia"]+1, 9, "0", STR_PAD_LEFT);
                $numero=str_pad((int)$rawData + 1, 9, "0", STR_PAD_LEFT);
                //$sql=" UPDATE " . $con->dbname . ".secuencias SET secuencia=:numero "
                $sql=" UPDATE " . yii::$app->db_facturacion->dbname . ".secuencias SET secuencia=:secuencia "
                        . " WHERE secu_estado=1 AND secu_tipo_doc=:secu_tipo_doc ";            
                if ($emp_id != NULL){$sql.=" AND emp_id=:emp_id ";}
                if ($estab_id != NULL){$sql.=" AND estab_id=:estab_id ";}
                if ($pemis_id != NULL){$sql.=" AND pemis_id=:pemis_id ";}
                
                $comando = $con->createCommand($sql);
                $comando->bindParam(":secuencia", $numero, \PDO::PARAM_STR);
                $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
                $comando->bindParam(":estab_id", $estab_id, \PDO::PARAM_INT);
                $comando->bindParam(":pemis_id", $pemis_id, \PDO::PARAM_INT);
                $comando->bindParam(":secu_tipo_doc", $secu_tipo_doc, \PDO::PARAM_STR);
                $rawData=$comando->execute();         
            }
        }catch(Exception $e){
            Utilities::putMessageLogFile($e);
        }
        return $numero;
    }

    public static function initSecuencia($con,$emp_id,$estab_id,$pemis_id,$secu_tipo_doc,$secu_nombre,$edoc_tipo=NULL,$edoc_estado = 0){
        // se debe preguntar si ya existe secuencia sino se lo debe inicializar
        try{
            $sql="SELECT IFNULL(CAST(secuencia AS UNSIGNED),0) secuencia FROM " . yii::$app->db_facturacion->dbname . ".secuencias 
                    WHERE secu_estado=1  ";
            if ($emp_id != NULL){$sql.=" AND emp_id=:emp_id ";}
            if ($estab_id != NULL){$sql.=" AND estab_id=:estab_id ";}
            if ($pemis_id != NULL){$sql.=" AND pemis_id=:pemis_id ";}
            if ($secu_tipo_doc != NULL){$sql.=" AND secu_tipo_doc=:secu_tipo_doc ";}
        
            $comando = $con->createCommand($sql);
            $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
            $comando->bindParam(":estab_id", $estab_id, \PDO::PARAM_INT);
            $comando->bindParam(":pemis_id", $pemis_id, \PDO::PARAM_INT);
            $comando->bindParam(":secu_tipo_doc", $secu_tipo_doc, \PDO::PARAM_STR);
            //$rawData=$comando->queryAll();     
            $rawData=$comando->queryScalar();   
            //if (count($rawData) > 0) { 
            if ($rawData == false){
                $numero=str_pad(0, 9, "0", STR_PAD_LEFT);
                $sql=" INSERT INTO " . yii::$app->db_facturacion->dbname . ".secuencias "
                    . "(secuencia, emp_id, estab_id, pemis_id, secu_tipo_doc, edoc_tipo, secu_nombre, edoc_estado, secu_estado, secu_estado_logico) "
                    . "VALUES(:secuencia, :emp_id, :estab_id, :pemis_id, :secu_tipo_doc, :edoc_tipo, :secu_nombre, :edoc_estado, 1, 1) ";
                $comando = $con->createCommand($sql);
                $comando->bindParam(":secuencia", $numero, \PDO::PARAM_STR);
                $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
                $comando->bindParam(":estab_id", $estab_id, \PDO::PARAM_INT);
                $comando->bindParam(":pemis_id", $pemis_id, \PDO::PARAM_INT);
                $comando->bindParam(":edoc_tipo", $edoc_tipo, \PDO::PARAM_STR);
                $comando->bindParam(":secu_nombre", $secu_nombre, \PDO::PARAM_STR);
                $comando->bindParam(":edoc_estado", $edoc_estado, \PDO::PARAM_INT);
                $comando->bindParam(":secu_tipo_doc", $secu_tipo_doc, \PDO::PARAM_STR);
                $rawData=$comando->execute();         
            }
        }catch(Exception $e){
            Utilities::putMessageLogFile($e);
        }
    }
    
    
}
