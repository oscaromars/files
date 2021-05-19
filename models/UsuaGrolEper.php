<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usua_grol_eper".
 *
 * @property int $ugep_id
 * @property int $eper_id
 * @property int $usu_id
 * @property int $grol_id
 * @property string $ugep_estado
 * @property string $ugep_fecha_creacion
 * @property string $ugep_fecha_modificacion
 * @property string $ugep_estado_logico
 *
 * @property EmpresaPersona $eper
 * @property Usuario $usu
 * @property GrupRol $grol
 */
class UsuaGrolEper extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'usua_grol_eper';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['eper_id', 'usu_id', 'grol_id'], 'integer'],
            [['usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'], 'required'],
            [['ugep_fecha_creacion', 'ugep_fecha_modificacion'], 'safe'],
            [['ugep_estado', 'ugep_estado_logico'], 'string', 'max' => 1],
            [['eper_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmpresaPersona::className(), 'targetAttribute' => ['eper_id' => 'eper_id']],
            [['usu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usu_id' => 'usu_id']],
            [['grol_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrupRol::className(), 'targetAttribute' => ['grol_id' => 'grol_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'ugep_id' => 'Ugep ID',
            'eper_id' => 'Eper ID',
            'usu_id' => 'Usu ID',
            'grol_id' => 'Grol ID',
            'ugep_estado' => 'Ugep Estado',
            'ugep_fecha_creacion' => 'Ugep Fecha Creacion',
            'ugep_fecha_modificacion' => 'Ugep Fecha Modificacion',
            'ugep_estado_logico' => 'Ugep Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEper() {
        return $this->hasOne(EmpresaPersona::className(), ['eper_id' => 'eper_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsu() {
        return $this->hasOne(Usuario::className(), ['usu_id' => 'usu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrol() {
        return $this->hasOne(GrupRol::className(), ['grol_id' => 'grol_id']);
    }

    /**
     * Function insertarUsuaGrolEper
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function insertarUsuaGrolEper($con, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0] . "'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i] . "'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname.'.'.$name_table . " ($param_sql) VALUES($bdet_sql);";                        
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable=$con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null){
                $trans->rollback();            
            }
            return 0;
        }
    }

    /**
     * Function consultarIdUsuaGrolEper 
     * @author  Kleber Loayza
     * @property      
     * @return  
     */
    public function consultarIdUsuaGrolEper($eper_id = null, $usu_id = null, $grol_id = null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT ifnull(ugep_id,0) as ugep_id
                FROM usua_grol_eper
                where
                eper_id=$eper_id
                and usu_id = $usu_id 
                and grol_id = $grol_id
                and ugep_estado=$estado
                and ugep_estado_logico=$estado
              ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['ugep_id']))
            return 0;
        else {
            return $resultData['ugep_id'];
        }
    }
    
    /**
     * Function consultarIdUsuaGrolEper 
     * @author  Kleber Loayza
     * @property      
     * @return  
     */
    public function consultarIdGrolByUsId($usu_id = null) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT ifnull(ugep_id,0) as ugep_id
                FROM usua_grol_eper
                where
                eper_id=$eper_id
                and usu_id = $usu_id 
                and grol_id = $grol_id
                and ugep_estado=$estado
                and ugep_estado_logico=$estado
              ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['ugep_id']))
            return 0;
        else {
            return $resultData['ugep_id'];
        }
    }
    
    /**
     * Function listadoUsuariosP
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function insertarDataUsuaGrolEper($con,$data) {
        //ugep_id
        $sql = "INSERT INTO " . $con->dbname . ".usua_grol_eper
            (eper_id,usu_id,grol_id,ugep_fecha_creacion,ugep_estado,ugep_estado_logico)VALUES
            (:eper_id,:usu_id,:grol_id,CURRENT_TIMESTAMP(),1,1) ";

        $command = $con->createCommand($sql);
        $command->bindParam(":eper_id",$data[0]['eper_id'], \PDO::PARAM_INT);
        $command->bindParam(":usu_id",$data[0]['usu_id'], \PDO::PARAM_INT);
        $command->bindParam(":grol_id",$data[0]['grol_id'], \PDO::PARAM_INT);    
        $command->execute();
        //return $con->getLastInsertID();
    }
    
     /**
     * Function actualizarRolEstudiante para estudiante.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param      
     * @return  
     */    
    public function actualizarRolEstudiante($usu_id) {
        $con = \Yii::$app->db;
        $grol_id = 37;
        $trans = $con->beginTransaction();
        try {            
            
            $sql = "UPDATE " . $con->dbname . ".usua_grol_eper 
                    SET grol_id = :grol_id
                    WHERE usu_id=:usu_id; ";
            $command = $con->createCommand($sql);
            $command->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
            $command->bindParam(":grol_id", $grol_id, \PDO::PARAM_INT);
            $command->execute();
            $trans->commit();
            $con->close();
            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();            
            return false;
        }
    }    

}
