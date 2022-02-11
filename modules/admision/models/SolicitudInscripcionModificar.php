<?php

namespace app\modules\admision\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "solicitud_inscripcion_modificar".
 *
 * @property int $sinmo_id
 * @property int $sins_id
 * @property int $sinmo_contador
 * @property int $sinmo_usuario_ingreso
 * @property int $sinmo_usuario_modifica
 * @property string $sinmo_estado
 * @property string $sinmo_fecha_creacion
 * @property string $sinmo_fecha_modificacion
 * @property string $sinmo_estado_logico
 *
 * @property SolicitudInscripcion $sins
 */
class SolicitudInscripcionModificar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_inscripcion_modificar';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sins_id', 'sinmo_contador', 'sinmo_usuario_ingreso', 'sinmo_estado', 'sinmo_estado_logico'], 'required'],
            [['sins_id', 'sinmo_contador', 'sinmo_usuario_ingreso', 'sinmo_usuario_modifica'], 'integer'],
            [['sinmo_fecha_creacion', 'sinmo_fecha_modificacion'], 'safe'],
            [['sinmo_estado', 'sinmo_estado_logico'], 'string', 'max' => 1],
            [['sins_id'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudInscripcion::className(), 'targetAttribute' => ['sins_id' => 'sins_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sinmo_id' => 'Sinmo ID',
            'sins_id' => 'Sins ID',
            'sinmo_contador' => 'Sinmo Contador',
            'sinmo_usuario_ingreso' => 'Sinmo Usuario Ingreso',
            'sinmo_usuario_modifica' => 'Sinmo Usuario Modifica',
            'sinmo_estado' => 'Sinmo Estado',
            'sinmo_fecha_creacion' => 'Sinmo Fecha Creacion',
            'sinmo_fecha_modificacion' => 'Sinmo Fecha Modificacion',
            'sinmo_estado_logico' => 'Sinmo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSins()
    {
        return $this->hasOne(SolicitudInscripcion::className(), ['sins_id' => 'sins_id']);
    }

    /**
     * Function insertarIncripcionModificar
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return  Id del registro insertado.
     */
    public function insertarIncripcionModificar($sins_id, $sinmo_contador, $sinmo_usuario_ingreso) {
        $con = \Yii::$app->db_captacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual.
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una.
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una.
        }

        $param_sql = "sinmo_estado_logico";
        $bsrec_sql = "1";

        $param_sql .= ", sinmo_estado";
        $bsrec_sql .= ", 1";

        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bsrec_sql .= ", :sins_id";
        }
        if (isset($sinmo_contador)) {
            $param_sql .= ", sinmo_contador";
            $bsrec_sql .= ", :sinmo_contador";
        }
        if (isset($sinmo_usuario_ingreso)) {
            $param_sql .= ", sinmo_usuario_ingreso";
            $bsrec_sql .= ", :sinmo_usuario_ingreso";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".solicitud_inscripcion_modificar ($param_sql) VALUES($bsrec_sql)";
            $comando = $con->createCommand($sql);

            if (isset($sins_id))
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);

            if (isset($sinmo_contador))
                $comando->bindParam(':sinmo_contador', $sinmo_contador, \PDO::PARAM_INT);

            if (isset($sinmo_usuario_ingreso))
                $comando->bindParam(':sinmo_usuario_ingreso', $sinmo_usuario_ingreso, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.solicitud_inscripcion_modificar');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function Desactivarsolicitudinscripcion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizarIncripcionModificar($sins_id, $sinmo_contador, $sinmo_usuario_modifica) {
        $con = \Yii::$app->db_captacion;
        //$estado = 0;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".solicitud_inscripcion_modificar
                SET sinmo_fecha_modificacion = :sinmo_fecha_modificacion,
                    sinmo_contador = :sinmo_contador,
                    sinmo_usuario_modifica = :sinmo_usuario_modifica
                WHERE sins_id = :sins_id ");

        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":sinmo_contador", $sinmo_contador, \PDO::PARAM_INT);
        $comando->bindParam(":sinmo_usuario_modifica", $sinmo_usuario_modifica, \PDO::PARAM_INT);
        $comando->bindParam(":sinmo_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);

        $response = $comando->execute();
        return $response;
    }
}
