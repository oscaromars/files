<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "malla_unidad_modalidad".
 *
 * @property int $mumo_id
 * @property int $maca_id
 * @property int $meun_id
 * @property string $mumo_estado
 * @property string $mumo_fecha_creacion
 * @property string $mumo_fecha_modificacion
 * @property string $mumo_estado_logico
 *
 * @property MallaAcademica $maca
 * @property ModalidadEstudioUnidad $meun
 */
class MallaUnidadModalidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'malla_unidad_modalidad';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['maca_id', 'meun_id', 'mumo_estado', 'mumo_estado_logico'], 'required'],
            [['maca_id', 'meun_id'], 'integer'],
            [['mumo_fecha_creacion', 'mumo_fecha_modificacion'], 'safe'],
            [['mumo_estado', 'mumo_estado_logico'], 'string', 'max' => 1],
            [['maca_id'], 'exist', 'skipOnError' => true, 'targetClass' => MallaAcademica::className(), 'targetAttribute' => ['maca_id' => 'maca_id']],
            [['meun_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModalidadEstudioUnidad::className(), 'targetAttribute' => ['meun_id' => 'meun_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mumo_id' => 'Mumo ID',
            'maca_id' => 'Maca ID',
            'meun_id' => 'Meun ID',
            'mumo_estado' => 'Mumo Estado',
            'mumo_fecha_creacion' => 'Mumo Fecha Creacion',
            'mumo_fecha_modificacion' => 'Mumo Fecha Modificacion',
            'mumo_estado_logico' => 'Mumo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaca()
    {
        return $this->hasOne(MallaAcademica::className(), ['maca_id' => 'maca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeun()
    {
        return $this->hasOne(ModalidadEstudioUnidad::className(), ['meun_id' => 'meun_id']);
    }
}
