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
}
