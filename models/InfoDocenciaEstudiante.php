<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "info_docencia_estudiante".
 *
 * @property int $ides_id
 * @property int $per_id
 * @property string $ides_año_docencia
 * @property string $ides_area_docencia
 * @property string $ides_estado
 * @property string $ides_fecha_creacion
 * @property string $ides_fecha_modificacion
 * @property string $ides_estado_logico
 */
class InfoDocenciaEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'info_docencia_estudiante';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_inscripcion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_id', 'ides_estado', 'ides_estado_logico'], 'required'],
            [['per_id'], 'integer'],
            [['ides_fecha_creacion', 'ides_fecha_modificacion'], 'safe'],
            [['ides_año_docencia'], 'string', 'max' => 100],
            [['ides_area_docencia'], 'string', 'max' => 300],
            [['ides_estado', 'ides_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ides_id' => 'Ides ID',
            'per_id' => 'Per ID',
            'ides_año_docencia' => 'Ides Año Docencia',
            'ides_area_docencia' => 'Ides Area Docencia',
            'ides_estado' => 'Ides Estado',
            'ides_fecha_creacion' => 'Ides Fecha Creacion',
            'ides_fecha_modificacion' => 'Ides Fecha Modificacion',
            'ides_estado_logico' => 'Ides Estado Logico',
        ];
    }


    /**
     * Function consultaEstudianteinstruccion
     * @author  Lisbeth Gonzalez <analista.desarrollo@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
    public function consultarInfoDocenciaEstudiante($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "
                SELECT   
                         count(*) as existe_infodocente
                FROM " . $con->dbname . ".info_docencia_estudiante 
                WHERE per_id = :per_id AND 
                      ides_estado = :estado AND
                      ides_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertarInfoDocenciaEst($per_id, $año_docencia, $area_docencia) {
        $con = \Yii::$app->db_inscripcion;

        $sql = "INSERT INTO " . $con->dbname . ".info_docencia_estudiante
            (per_id, ides_año_docencia, ides_area_docencia, ides_estado, ides_fecha_modificacion, ides_estado_logico) VALUES
            ($per_id, '$año_docencia', '$area_docencia', 1, CURRENT_TIMESTAMP(), 1)";

        
        $command = $con->createCommand($sql);
        $command->execute();
        return $con->getLastInsertID($con->dbname . '.info_docencia_estudiante');
        
    }

    public function modificarInfoDocenciaEst($per_id, $año_docencia, $area_docencia) {
        $con = \Yii::$app->db_inscripcion;
        $ides_fecha_modificacion = date("Y-m-d H:i:s");
        $estado='1';
        
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_docencia_estudiante             
                      SET 
                        per_id =:per_id, 
                        ides_año_docencia =:ides_año_docencia, 
                        ides_area_docencia =:ides_area_docencia,
                        ides_fecha_modificacion =:ides_fecha_modificacion
                      WHERE 
                        per_id = :per_id AND
                        ides_estado = :estado AND
                        ides_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":ides_año_docencia", $año_docencia, \PDO::PARAM_STR);
            $comando->bindParam(":ides_area_docencia", $area_docencia, \PDO::PARAM_STR);
            $comando->bindParam(":ides_fecha_modificacion", $ides_fecha_modificacion, \PDO::PARAM_STR);
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
