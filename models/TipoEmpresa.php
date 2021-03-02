<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_empresa".
 *
 * @property integer $temp_id
 * @property string $temp_nombre
 * @property string $temp_descripcion
 * @property string $temp_estado
 * @property string $temp_fecha_creacion
 * @property string $temp_fecha_modificacion
 * @property string $temp_estado_logico
 */
class TipoEmpresa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_empresa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['temp_estado', 'temp_estado_logico'], 'required'],
            [['temp_fecha_creacion', 'temp_fecha_modificacion'], 'safe'],
            [['temp_nombre'], 'string', 'max' => 250],
            [['temp_descripcion'], 'string', 'max' => 500],
            [['temp_estado', 'temp_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'temp_id' => 'Temp ID',
            'temp_nombre' => 'Temp Nombre',
            'temp_descripcion' => 'Temp Descripcion',
            'temp_estado' => 'Temp Estado',
            'temp_fecha_creacion' => 'Temp Fecha Creacion',
            'temp_fecha_modificacion' => 'Temp Fecha Modificacion',
            'temp_estado_logico' => 'Temp Estado Logico',
        ];
    }
}
