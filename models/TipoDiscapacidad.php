<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_discapacidad".
 *
 * @property integer $tdis_id
 * @property string $tdis_nombre
 * @property string $tdis_descripcion
 * @property string $tdis_estado
 * @property string $tdis_fecha_creacion
 * @property string $tdis_fecha_modificacion
 * @property string $tdis_estado_logico
 */
class TipoDiscapacidad extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tipo_discapacidad';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tdis_estado', 'tdis_estado_logico'], 'required'],
            [['tdis_fecha_creacion', 'tdis_fecha_modificacion'], 'safe'],
            [['tdis_nombre'], 'string', 'max' => 250],
            [['tdis_descripcion'], 'string', 'max' => 500],
            [['tdis_estado', 'tdis_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tdis_id' => 'Tdis ID',
            'tdis_nombre' => 'Tdis Nombre',
            'tdis_descripcion' => 'Tdis Descripcion',
            'tdis_estado' => 'Tdis Estado',
            'tdis_fecha_creacion' => 'Tdis Fecha Creacion',
            'tdis_fecha_modificacion' => 'Tdis Fecha Modificacion',
            'tdis_estado_logico' => 'Tdis Estado Logico',
        ];
    }
}
