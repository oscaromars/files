<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use yii\base\Exception;

/**
 * This is the model class for table "info_discapacidad_est".
 *
 * @property int $ides_id
 * @property int $per_id
 * @property int $tdis_id
 * @property string $ides_porcentaje
 * @property string $ides_estado
 * @property string $ides_fecha_creacion
 * @property string $ides_fecha_modificacion
 * @property string $ides_estado_logico
 */
class InfoDiscapacidadEst extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'info_discapacidad_est';
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
            [['per_id', 'tdis_id', 'ides_estado', 'ides_estado_logico'], 'required'],
            [['per_id', 'tdis_id'], 'integer'],
            [['ides_fecha_creacion', 'ides_fecha_modificacion'], 'safe'],
            [['ides_porcentaje'], 'string', 'max' => 3],
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
            'tdis_id' => 'Tdis ID',
            'ides_porcentaje' => 'Ides Porcentaje',
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
    public function consultarInfoDiscapacidadest($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "
                SELECT   
                         count(*) as existe_infodiscapacidad
                FROM " . $con->dbname . ".info_discapacidad_est 
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

    public function insertarInfoDiscapacidad($per_id, $tipo_discap, $porcentaje_discap) {
        $con = \Yii::$app->db_inscripcion;

        $sql = "INSERT INTO " . $con->dbname . ".info_discapacidad_est
            (per_id, tdis_id, ides_porcentaje, ides_estado, ides_fecha_modificacion, ides_estado_logico) VALUES
            ($per_id, $tipo_discap, '$porcentaje_discap', 1, CURRENT_TIMESTAMP(), 1)";

        
        $command = $con->createCommand($sql);
        $command->execute();
        return $con->getLastInsertID($con->dbname . '.info_discapacidad_est');
        
    }

    public function modificarInfoDiscapacidad($per_id, $tipo_discap, $porcentaje_discap) {
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
                    ("UPDATE " . $con->dbname . ".info_discapacidad_est             
                      SET 
                        per_id =:per_id, 
                        tdis_id =:tdis_id, 
                        ides_porcentaje =:ides_porcentaje, 
                        ides_fecha_modificacion =:ides_fecha_modificacion
                      WHERE 
                        per_id = :per_id AND
                        ides_estado = :estado AND
                        ides_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":tdis_id", $tipo_discap, \PDO::PARAM_INT);
            $comando->bindParam(":ides_porcentaje", $porcentaje_discap, \PDO::PARAM_STR);
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

    public function getAllestudiantediscapacidadGrid($per_id, $onlyData=false){
        \app\models\Utilities::putMessageLogFile('traer el per_id: ' .$per_id); 
        $con_inscripcion = \Yii::$app->db_inscripcion;
        $con_asgard = \Yii::$app->db_asgard;
        $sql = "SELECT   
                    ides_id as Ids,
                    ides.per_id,
                    ides.tdis_id as tdis,
                    tdis.tdis_nombre as discapacidad,
                    ides.ides_porcentaje as porcentaje
                FROM " . $con_inscripcion->dbname . ".info_discapacidad_est ides
                     inner join " . $con_asgard->dbname . ".tipo_discapacidad tdis on tdis.tdis_id = ides.tdis_id
                WHERE ides.per_id = :per_id and
                      ides.ides_estado_logico = 1 and 
                      ides.ides_estado = 1";
        $comando = $con_inscripcion->createCommand($sql);
        $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        if($onlyData)   return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['discapacidad', 'porcentaje'],
            ],
        ]);

        return $dataProvider;
    }

}
