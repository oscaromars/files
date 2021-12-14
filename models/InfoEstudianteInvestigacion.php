<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "info_estudiante_investigacion".
 *
 * @property int $iein_id
 * @property int $per_id
 * @property string $iein_articulos_investigacion
 * @property string $iein_area_investigacion
 * @property string $iein_estado
 * @property string $iein_fecha_creacion
 * @property string $iein_fecha_modificacion
 * @property string $iein_estado_logico
 */
class InfoEstudianteInvestigacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'info_estudiante_investigacion';
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
            [['per_id', 'iein_estado', 'iein_estado_logico'], 'required'],
            [['per_id'], 'integer'],
            [['iein_fecha_creacion', 'iein_fecha_modificacion'], 'safe'],
            [['iein_articulos_investigacion', 'iein_area_investigacion'], 'string', 'max' => 500],
            [['iein_estado', 'iein_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iein_id' => 'Iein ID',
            'per_id' => 'Per ID',
            'iein_articulos_investigacion' => 'Iein Articulos Investigacion',
            'iein_area_investigacion' => 'Iein Area Investigacion',
            'iein_estado' => 'Iein Estado',
            'iein_fecha_creacion' => 'Iein Fecha Creacion',
            'iein_fecha_modificacion' => 'Iein Fecha Modificacion',
            'iein_estado_logico' => 'Iein Estado Logico',
        ];
    }

    /**
     * Function consultaEstudianteinstruccion
     * @author  Lisbeth Gonzalez <analista.desarrollo@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
    public function consultarInfoEstudianteInvestigacion($per_id) {
        \app\models\Utilities::putMessageLogFile('personannnnnnnnnnn:  '.$per_id); 
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "
                SELECT   
                         count(*) as existe_infoinvestigacion
                FROM " . $con->dbname . ".info_estudiante_investigacion 
                WHERE per_id = :per_id AND 
                      iein_estado = :estado AND
                      iein_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertarInfoEstInvestigacion($per_id, $articulos, $area_investigacion) {
        $con = \Yii::$app->db_inscripcion;

        $sql = "INSERT INTO " . $con->dbname . ".info_estudiante_investigacion
            (per_id, iein_articulos_investigacion, iein_area_investigacion, iein_estado, iein_fecha_modificacion, iein_estado_logico) VALUES
            ($per_id, '$articulos', '$area_investigacion', 1, CURRENT_TIMESTAMP(), 1)";

        
        $command = $con->createCommand($sql);
        $command->execute();
        return $con->getLastInsertID($con->dbname . '.info_estudiante_investigacion');
        
    }

    public function modificarInfoEstInvestigacion($per_id, $articulos, $area_investigacion) {
        $con = \Yii::$app->db_inscripcion;
        $iein_fecha_modificacion = date("Y-m-d H:i:s");
        $estado='1';

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_estudiante_investigacion
                      SET
                        per_id =:per_id,
                        iein_articulos_investigacion =:iein_articulos_investigacion,
                        iein_area_investigacion =:iein_area_investigacion,
                        iein_fecha_modificacion =:iein_fecha_modificacion
                      WHERE
                        per_id = :per_id AND
                        iein_estado = :estado AND
                        iein_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":iein_articulos_investigacion", $articulos, \PDO::PARAM_STR);
            $comando->bindParam(":iein_area_investigacion", $area_investigacion, \PDO::PARAM_STR);
            $comando->bindParam(":iein_fecha_modificacion", $iein_fecha_modificacion, \PDO::PARAM_STR);
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
