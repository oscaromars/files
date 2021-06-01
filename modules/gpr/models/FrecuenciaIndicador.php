<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "frecuencia_indicador".
 *
 * @property int $find_id
 * @property string $find_nombre
 * @property int $find_denominador
 * @property string|null $find_items
 * @property string $find_descripcion
 * @property int $find_usuario_ingreso
 * @property int|null $find_usuario_modifica
 * @property string $find_estado
 * @property string $find_fecha_creacion
 * @property string|null $find_fecha_modificacion
 * @property string $find_estado_logico
 *
 * @property Indicador[] $indicadors
 */
class FrecuenciaIndicador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'frecuencia_indicador';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gpr');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['find_nombre', 'find_denominador', 'find_descripcion', 'find_usuario_ingreso', 'find_estado', 'find_estado_logico'], 'required'],
            [['find_denominador', 'find_usuario_ingreso', 'find_usuario_modifica'], 'integer'],
            [['find_fecha_creacion', 'find_fecha_modificacion'], 'safe'],
            [['find_nombre', 'find_items'], 'string', 'max' => 300],
            [['find_descripcion'], 'string', 'max' => 500],
            [['find_estado', 'find_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'find_id' => 'Find ID',
            'find_nombre' => 'Find Nombre',
            'find_denominador' => 'Find Denominador',
            'find_items' => 'Find Items',
            'find_descripcion' => 'Find Descripcion',
            'find_usuario_ingreso' => 'Find Usuario Ingreso',
            'find_usuario_modifica' => 'Find Usuario Modifica',
            'find_estado' => 'Find Estado',
            'find_fecha_creacion' => 'Find Fecha Creacion',
            'find_fecha_modificacion' => 'Find Fecha Modificacion',
            'find_estado_logico' => 'Find Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['find_id' => 'find_id']);
    }
}
