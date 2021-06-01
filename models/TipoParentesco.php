<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_parentesco".
 *
 * @property integer $tpar_id
 * @property string $tpar_nombre
 * @property string $tpar_descripcion
 * @property string $tpar_observacion
 * @property string $tpar_estado
 * @property string $tpar_fecha_creacion
 * @property string $tpar_fecha_actualizacion
 * @property string $tpar_estado_logico
 *
 * @property PersonaContacto[] $personaContactos
 */
class TipoParentesco extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tipo_parentesco';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tpar_estado', 'tpar_estado_logico'], 'required'],
            [['tpar_fecha_creacion', 'tpar_fecha_actualizacion'], 'safe'],
            [['tpar_nombre'], 'string', 'max' => 200],
            [['tpar_descripcion', 'tpar_observacion'], 'string', 'max' => 500],
            [['tpar_estado', 'tpar_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tpar_id' => 'Tpar ID',
            'tpar_nombre' => 'Tpar Nombre',
            'tpar_descripcion' => 'Tpar Descripcion',
            'tpar_observacion' => 'Tpar Observacion',
            'tpar_estado' => 'Tpar Estado',
            'tpar_fecha_creacion' => 'Tpar Fecha Creacion',
            'tpar_fecha_actualizacion' => 'Tpar Fecha Actualizacion',
            'tpar_estado_logico' => 'Tpar Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaContactos() {
        return $this->hasMany(PersonaContacto::className(), ['tpar_id' => 'tpar_id']);
    }

}
