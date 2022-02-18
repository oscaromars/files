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
}
