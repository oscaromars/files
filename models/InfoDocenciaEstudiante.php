<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "info_docencia_estudiante".
 *
 * @property int $ides_id
 * @property int $per_id
 * @property string $ides_anio_docencia
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
            [['ides_anio_docencia'], 'string', 'max' => 100],
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
            'ides_anio_docencia' => 'Ides Anio Docencia',
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

    public function insertarInfoDocenciaEst($per_id, $a??o_docencia, $area_docencia) {
        $con = \Yii::$app->db_inscripcion;

        $sql = "INSERT INTO " . $con->dbname . ".info_docencia_estudiante
            (per_id, ides_anio_docencia, ides_area_docencia, ides_estado, ides_fecha_modificacion, ides_estado_logico) VALUES
            ($per_id, '$anio_docencia', '$area_docencia', 1, CURRENT_TIMESTAMP(), 1)";


        $command = $con->createCommand($sql);
        $command->execute();
        return $con->getLastInsertID($con->dbname . '.info_docencia_estudiante');

    }

    public function modificarInfoDocenciaEst($per_id, $a??o_docencia, $area_docencia) {
        $con = \Yii::$app->db_inscripcion;
        $ides_fecha_modificacion = date("Y-m-d H:i:s");
        $estado='1';

        if ($trans !== null) {
            $trans = null; // si existe la transacci??n entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".info_docencia_estudiante
                      SET
                        per_id =:per_id,
                        ides_anio_docencia =:ides_anio_docencia,
                        ides_area_docencia =:ides_area_docencia,
                        ides_fecha_modificacion =:ides_fecha_modificacion
                      WHERE
                        per_id = :per_id AND
                        ides_estado = :estado AND
                        ides_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":ides_anio_docencia", $a??o_docencia, \PDO::PARAM_STR);
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

    public function getAllestudiantediscapacidadGrid($per_id, $onlyData=false){
        \app\models\Utilities::putMessageLogFile('traer el per_id: ' .$per_id);
        $con_inscripcion = \Yii::$app->db_inscripcion;
        $con_asgard = \Yii::$app->db_asgard;
        $sql = "SELECT   ides_id, per_id, ides_anio_docencia, ides_area_docencia,
                    ides_id as Ids,
                    per_id,
                    ides_anio_docencia as a??o_docencia,
                    tdis_nombre as discapacidad,
                    ides_area_docencia as area_docencia
                FROM " . $con_inscripcion->dbname . ".info_docencia_estudiante
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
