<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use yii\base\Exception;

/**
 * This is the model class for table "estudiante_idiomas".
 *
 * @property int $eidi_id
 * @property int $per_id
 * @property int $idi_id
 * @property int $nidi_id
 * @property string $eidi_nombre_idioma
 * @property string $eidi_estado
 * @property string $eidi_fecha_creacion
 * @property string $eidi_fecha_modificacion
 * @property string $eidi_estado_logico
 */
class EstudianteIdiomas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudiante_idiomas';
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
            [['per_id', 'idi_id', 'nidi_id', 'eidi_estado', 'eidi_estado_logico'], 'required'],
            [['per_id', 'idi_id', 'nidi_id'], 'integer'],
            [['eidi_fecha_creacion', 'eidi_fecha_modificacion'], 'safe'],
            [['eidi_nombre_idioma'], 'string', 'max' => 200],
            [['eidi_estado', 'eidi_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eidi_id' => 'Eidi ID',
            'per_id' => 'Per ID',
            'idi_id' => 'Idi ID',
            'nidi_id' => 'Nidi ID',
            'eidi_nombre_idioma' => 'Eidi Nombre Idioma',
            'eidi_estado' => 'Eidi Estado',
            'eidi_fecha_creacion' => 'Eidi Fecha Creacion',
            'eidi_fecha_modificacion' => 'Eidi Fecha Modificacion',
            'eidi_estado_logico' => 'Eidi Estado Logico',
        ];
    }

    /**
     * Function consultaEstudianteinstruccion
     * @author  Lisbeth Gonzalez <analista.desarrollo@uteg.edu.ec>
     * @property integer $userid
     * @return
     */
    public function consultarInfoIdiomasEst($per_id, $idi_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "
                SELECT
                         count(*) as existe_idioma
                FROM " . $con->dbname . ".estudiante_idiomas
                WHERE per_id = :per_id AND
                      idi_id = :idi_id AND
                      eidi_estado = :estado AND
                      eidi_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":idi_id", $idi_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertarInfoIdiomaEst($per_id, $idi_id, $nidi_id, $eidi_nombre_idioma) {
        $con = \Yii::$app->db_inscripcion;

        $sql = "INSERT INTO " . $con->dbname . ".estudiante_idiomas
            (per_id, idi_id, nidi_id, eidi_nombre_idioma, eidi_estado, eidi_fecha_modificacion, eidi_estado_logico) VALUES
            ($per_id, $idi_id, $nidi_id, '$eidi_nombre_idioma', 1, CURRENT_TIMESTAMP(), 1)";


        $command = $con->createCommand($sql);
        $command->execute();
        return $con->getLastInsertID($con->dbname . '.estudiante_idiomas');

    }

    public function modificarInfoIdiomaEst($per_id, $idi_id, $nidi_id, $eidi_nombre_idioma) {
        $con = \Yii::$app->db_inscripcion;
        $eidi_fecha_modificacion = date("Y-m-d H:i:s");
        $estado='1';

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".estudiante_idiomas
                      SET
                        per_id =:per_id,
                        idi_id =:idi_id,
                        nidi_id =:nidi_id,
                        eidi_nombre_idioma =:eidi_nombre_idioma,
                        eidi_fecha_modificacion =:eidi_fecha_modificacion
                      WHERE
                        per_id = :per_id AND
                        eidi_estado = :estado AND
                        eidi_estado_logico = :estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":idi_id", $idi_id, \PDO::PARAM_INT);
            $comando->bindParam(":nidi_id", $nidi_id, \PDO::PARAM_INT);
            $comando->bindParam(":eidi_nombre_idioma", $eidi_nombre_idioma, \PDO::PARAM_STR);
            $comando->bindParam(":eidi_fecha_modificacion", $eidi_fecha_modificacion, \PDO::PARAM_STR);
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

    public function getAllestudianteidiomasGrid($per_id, $onlyData=false){
        \app\models\Utilities::putMessageLogFile('traer el per_id: ' .$per_id);
        $con_inscripcion = \Yii::$app->db_inscripcion;
        $sql = "SELECT
                    eidi_id as Ids,
                    eidi.per_id,
                    eidi.idi_id as idi,
                    idi.idi_nombre as nombre_idioma,
                    nidi.nidi_descripcion as nivel_idioma,
                    eidi.eidi_nombre_idioma as idioma
                FROM db_inscripcion.estudiante_idiomas eidi
                     inner join db_general.idioma idi on idi.idi_id = eidi.idi_id
                     inner join db_general.nivel_idioma nidi on nidi.nidi_id = eidi.nidi_id
                WHERE eidi.per_id = :per_id and
                      eidi.eidi_estado_logico = 1 and
                      eidi.eidi_estado = 1";
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
                'attributes' => ['idioma', 'nivel_idioma', 'nombre_idioma'],
            ],
        ]);

        return $dataProvider;
    }


}
