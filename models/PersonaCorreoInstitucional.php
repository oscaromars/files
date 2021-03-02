<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona_correo_institucional".
 *
 * @property integer $pcin_id
 * @property integer $per_id
 * @property string $pcin_correo
 * @property string $pcin_estado
 * @property string $pcin_fecha_creacion
 * @property string $pcin_fecha_modificacion
 * @property string $pcin_estado_logico
 */
class PersonaCorreoInstitucional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'persona_correo_institucional';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_general');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['per_id', 'pcin_estado', 'pcin_estado_logico'], 'required'],
            [['per_id'], 'integer'],
            [['pcin_fecha_creacion', 'pcin_fecha_modificacion'], 'safe'],
            [['pcin_correo'], 'string', 'max' => 250],
            [['pcin_estado', 'pcin_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pcin_id' => 'Pcin ID',
            'per_id' => 'Per ID',
            'pcin_correo' => 'Pcin Correo',
            'pcin_estado' => 'Pcin Estado',
            'pcin_fecha_creacion' => 'Pcin Fecha Creacion',
            'pcin_fecha_modificacion' => 'Pcin Fecha Modificacion',
            'pcin_estado_logico' => 'Pcin Estado Logico',
        ];
    }
    
    public function consultarCorreoInstitucional($per_id){
        $con = \Yii::$app->db_general;        
        $estado = 1;
        $sql = "SELECT 	pcin_correo             
                FROM  " . $con->dbname . ".persona_correo_institucional 
                WHERE 
                    per_id= :per_id AND
                    pcin_estado_logico=:estado AND
                    pcin_estado=:estado
                order by pcin_fecha_creacion desc limit 1";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    public function crearCorreoInstitucional($per_id, $pcin_correo) {
        $con = \Yii::$app->db_general;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "pcin_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", pcin_estado";
        $bsol_sql .= ", 1";

        if (isset($per_id)) {
            $param_sql .= ", per_id";
            $bsol_sql .= ", :per_id";
        }

        if (isset($pcin_correo)) {
            $param_sql .= ", pcin_correo";
            $bsol_sql .= ", :pcin_correo";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".persona_correo_institucional ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($per_id))
                $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);

            if (isset($pcin_correo))
                $comando->bindParam(':pcin_correo', $pcin_correo, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.persona_correo_institucional');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }


    public function modificaCorInstitucional($per_id, $pcin_correo) {
        $con = \Yii::$app->db_general;
        $pcin_fecha_modificacion = date("Y-m-d H:i:s");
        $estado = 1;
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona_correo_institucional 		       
                      SET 
                        pcin_correo  = :pcin_correo,                        
                        pcin_fecha_modificacion = :pcin_fecha_modificacion
                      WHERE 
                        per_id = :per_id AND
                        pcin_estado = :estado AND
                        pcin_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":pcin_correo", $pcin_correo, \PDO::PARAM_STR);
            $comando->bindParam(":pcin_fecha_modificacion", $pcin_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();
            
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
 
}
