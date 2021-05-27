<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estado_civil".
 *
 * @property integer $eciv_id
 * @property string $eciv_nombre
 * @property string $eciv_descripcion
 * @property string $eciv_observacion
 * @property string $eciv_estado
 * @property string $eciv_fecha_creacion
 * @property string $eciv_fecha_actualizacion
 * @property string $eciv_estado_logico
 */
class EstadoCivil extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estado_civil';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_asgard');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eciv_estado', 'eciv_estado_logico'], 'required'],
            [['eciv_fecha_creacion', 'eciv_fecha_actualizacion'], 'safe'],
            [['eciv_nombre', 'eciv_descripcion'], 'string', 'max' => 250],
            [['eciv_observacion'], 'string', 'max' => 500],
            [['eciv_estado', 'eciv_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'eciv_id' => 'Eciv ID',
            'eciv_nombre' => 'Eciv Nombre',
            'eciv_descripcion' => 'Eciv Descripcion',
            'eciv_observacion' => 'Eciv Observacion',
            'eciv_estado' => 'Eciv Estado',
            'eciv_fecha_creacion' => 'Eciv Fecha Creacion',
            'eciv_fecha_actualizacion' => 'Eciv Fecha Actualizacion',
            'eciv_estado_logico' => 'Eciv Estado Logico',
        ];
    }
}
