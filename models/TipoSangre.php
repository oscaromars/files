<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_sangre".
 *
 * @property integer $tsan_id
 * @property string $tsan_nombre
 * @property string $tsan_descripcion
 * @property string $tsan_observacion
 * @property string $tsan_estado
 * @property string $tsan_fecha_creacion
 * @property string $tsan_fecha_actualizacion
 * @property string $tsan_estado_logico
 *
 * @property Persona[] $personas
 */
class TipoSangre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_sangre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tsan_estado', 'tsan_estado_logico'], 'required'],
            [['tsan_fecha_creacion', 'tsan_fecha_actualizacion'], 'safe'],
            [['tsan_nombre'], 'string', 'max' => 50],
            [['tsan_descripcion', 'tsan_observacion'], 'string', 'max' => 200],
            [['tsan_estado', 'tsan_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tsan_id' => Yii::t('tipo_sangre', 'Tsan ID'),
            'tsan_nombre' => Yii::t('tipo_sangre', 'Tsan Nombre'),
            'tsan_descripcion' => Yii::t('tipo_sangre', 'Tsan Descripcion'),
            'tsan_observacion' => Yii::t('tipo_sangre', 'Tsan Observacion'),
            'tsan_estado' => Yii::t('tipo_sangre', 'Tsan Estado'),
            'tsan_fecha_creacion' => Yii::t('tipo_sangre', 'Tsan Fecha Creacion'),
            'tsan_fecha_actualizacion' => Yii::t('tipo_sangre', 'Tsan Fecha Actualizacion'),
            'tsan_estado_logico' => Yii::t('tipo_sangre', 'Tsan Estado Logico'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Persona::className(), ['tsan_id' => 'tsan_id']);
    }
}
