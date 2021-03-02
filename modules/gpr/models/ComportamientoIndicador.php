<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "comportamiento_indicador".
 *
 * @property int $cind_id
 * @property string $cind_nombre
 * @property string $cind_descripcion
 * @property int $cind_usuario_ingreso
 * @property int|null $cind_usuario_modifica
 * @property string $cind_estado
 * @property string $cind_fecha_creacion
 * @property string|null $cind_fecha_modificacion
 * @property string $cind_estado_logico
 *
 * @property Indicador[] $indicadors
 */
class ComportamientoIndicador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comportamiento_indicador';
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
            [['cind_nombre', 'cind_descripcion', 'cind_usuario_ingreso', 'cind_estado', 'cind_estado_logico'], 'required'],
            [['cind_usuario_ingreso', 'cind_usuario_modifica'], 'integer'],
            [['cind_fecha_creacion', 'cind_fecha_modificacion'], 'safe'],
            [['cind_nombre'], 'string', 'max' => 300],
            [['cind_descripcion'], 'string', 'max' => 500],
            [['cind_estado', 'cind_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cind_id' => 'Cind ID',
            'cind_nombre' => 'Cind Nombre',
            'cind_descripcion' => 'Cind Descripcion',
            'cind_usuario_ingreso' => 'Cind Usuario Ingreso',
            'cind_usuario_modifica' => 'Cind Usuario Modifica',
            'cind_estado' => 'Cind Estado',
            'cind_fecha_creacion' => 'Cind Fecha Creacion',
            'cind_fecha_modificacion' => 'Cind Fecha Modificacion',
            'cind_estado_logico' => 'Cind Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['cind_id' => 'cind_id']);
    }
}
