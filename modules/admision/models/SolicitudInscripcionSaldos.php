<?php

namespace app\modules\admision\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "solicitud_inscripcion_saldos".
 *
 * @property int $sinsa_id
 * @property int $sins_id
 * @property double $sinsa_valor_anterior
 * @property double $sinsa_valor_actual
 * @property double $sinsa_saldo
 * @property string $sinsa_estado_saldofavor
 * @property string $sinsa_estado_saldoconsumido
 * @property int $sinsa_usuario_ingreso
 * @property int $sinsa_usuario_modifica
 * @property string $sinsa_estado
 * @property string $sinsa_fecha_creacion
 * @property string $sinsa_fecha_modificacion
 * @property string $sinsa_estado_logico
 *
 * @property SolicitudInscripcion $sins
 */
class SolicitudInscripcionSaldos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_inscripcion_saldos';
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
            [['sins_id', 'sinsa_valor_anterior', 'sinsa_valor_actual', 'sinsa_saldo', 'sinsa_usuario_ingreso', 'sinsa_estado', 'sinsa_estado_logico'], 'required'],
            [['sins_id', 'sinsa_usuario_ingreso', 'sinsa_usuario_modifica'], 'integer'],
            [['sinsa_valor_anterior', 'sinsa_valor_actual', 'sinsa_saldo'], 'number'],
            [['sinsa_fecha_creacion', 'sinsa_fecha_modificacion'], 'safe'],
            [['sinsa_estado_saldofavor', 'sinsa_estado_saldoconsumido', 'sinsa_estado', 'sinsa_estado_logico'], 'string', 'max' => 1],
            [['sins_id'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudInscripcion::className(), 'targetAttribute' => ['sins_id' => 'sins_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sinsa_id' => 'Sinsa ID',
            'sins_id' => 'Sins ID',
            'sinsa_valor_anterior' => 'Sinsa Valor Anterior',
            'sinsa_valor_actual' => 'Sinsa Valor Actual',
            'sinsa_saldo' => 'Sinsa Saldo',
            'sinsa_estado_saldofavor' => 'Sinsa Estado Saldofavor',
            'sinsa_estado_saldoconsumido' => 'Sinsa Estado Saldoconsumido',
            'sinsa_usuario_ingreso' => 'Sinsa Usuario Ingreso',
            'sinsa_usuario_modifica' => 'Sinsa Usuario Modifica',
            'sinsa_estado' => 'Sinsa Estado',
            'sinsa_fecha_creacion' => 'Sinsa Fecha Creacion',
            'sinsa_fecha_modificacion' => 'Sinsa Fecha Modificacion',
            'sinsa_estado_logico' => 'Sinsa Estado Logico',
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
     * Function insertarIncripcionSaldos
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return  Id insertado.
     */
    public function insertarIncripcionSaldos($sins_id, $sinsa_valor_anterior, $sinsa_valor_actual, $sinsa_saldo, $sinsa_estado_saldofavor, $sinsa_estado_saldoconsumido, $sinsa_usuario_ingreso) {
        $con = \Yii::$app->db_captacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual.
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una.
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una.
        }

        $param_sql = "sinsa_estado_logico";
        $bsrec_sql = "1";

        $param_sql .= ", sinsa_estado";
        $bsrec_sql .= ", 1";

        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bsrec_sql .= ", :sins_id";
        }
        if (isset($sinsa_valor_anterior)) {
            $param_sql .= ", sinsa_valor_anterior";
            $bsrec_sql .= ", :sinsa_valor_anterior";
        }
        if (isset($sinsa_valor_actual)) {
            $param_sql .= ", sinsa_valor_actual";
            $bsrec_sql .= ", :sinsa_valor_actual";
        }
        if (isset($sinsa_saldo)) {
            $param_sql .= ", sinsa_saldo";
            $bsrec_sql .= ", :sinsa_saldo";
        }

        if (isset($sinsa_estado_saldofavor)) {
            $param_sql .= ", sinsa_estado_saldofavor";
            $bsrec_sql .= ", :sinsa_estado_saldofavor";
        }

        if (isset($sinsa_estado_saldoconsumido)) {
            $param_sql .= ", sinsa_estado_saldoconsumido";
            $bsrec_sql .= ", :sinsa_estado_saldoconsumido";
        }

        if (isset($sinsa_usuario_ingreso)) {
            $param_sql .= ", sinsa_usuario_ingreso";
            $bsrec_sql .= ", :sinsa_usuario_ingreso";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".solicitud_inscripcion_saldos ($param_sql) VALUES($bsrec_sql)";
            $comando = $con->createCommand($sql);

            if (isset($sins_id))
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);

            if (isset($sinsa_valor_anterior))
                $comando->bindParam(':sinsa_valor_anterior', $sinsa_valor_anterior, \PDO::PARAM_STR);

            if (isset($sinsa_valor_actual))
                $comando->bindParam(':sinsa_valor_actual', $sinsa_valor_actual, \PDO::PARAM_STR);

            if (isset($sinsa_saldo))
                $comando->bindParam(':sinsa_saldo', $sinsa_saldo, \PDO::PARAM_STR);

            if (isset($sinsa_estado_saldofavor))
                $comando->bindParam(':sinsa_estado_saldofavor', $sinsa_estado_saldofavor, \PDO::PARAM_STR);

            if (isset($sinsa_estado_saldoconsumido))
                $comando->bindParam(':sinsa_estado_saldoconsumido', $sinsa_estado_saldoconsumido, \PDO::PARAM_STR);

            if (isset($sinsa_usuario_ingreso))
                $comando->bindParam(':sinsa_usuario_ingreso', $sinsa_usuario_ingreso, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.solicitud_inscripcion_saldos');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
}
