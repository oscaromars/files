<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "etnia".
 *
 * @property integer $etn_id
 * @property string $etn_nombre
 * @property string $etn_descripcion
 * @property string $etn_observacion
 * @property string $etn_estado
 * @property string $etn_fecha_creacion
 * @property string $etn_fecha_actualizacion
 * @property string $etn_estado_logico
 *
 * @property Persona[] $personas
 */
class Etnia extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'etnia';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['etn_estado', 'etn_estado_logico'], 'required'],
            [['etn_fecha_creacion', 'etn_fecha_actualizacion'], 'safe'],
            [['etn_nombre', 'etn_descripcion'], 'string', 'max' => 250],
            [['etn_observacion'], 'string', 'max' => 500],
            [['etn_estado', 'etn_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'etn_id' => 'Etn ID',
            'etn_nombre' => 'Etn Nombre',
            'etn_descripcion' => 'Etn Descripcion',
            'etn_observacion' => 'Etn Observacion',
            'etn_estado' => 'Etn Estado',
            'etn_fecha_creacion' => 'Etn Fecha Creacion',
            'etn_fecha_actualizacion' => 'Etn Fecha Actualizacion',
            'etn_estado_logico' => 'Etn Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas() {
        return $this->hasMany(Persona::className(), ['etn_id' => 'etn_id']);
    }

}
