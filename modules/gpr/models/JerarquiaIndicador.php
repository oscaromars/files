<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "jerarquia_indicador".
 *
 * @property int $jind_id
 * @property string $jind_nombre
 * @property string $jind_descripcion
 * @property int $jind_usuario_ingreso
 * @property int|null $jind_usuario_modifica
 * @property string $jind_estado
 * @property string $jind_fecha_creacion
 * @property string|null $jind_fecha_modificacion
 * @property string $jind_estado_logico
 *
 * @property Indicador[] $indicadors
 */
class JerarquiaIndicador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jerarquia_indicador';
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
            [['jind_nombre', 'jind_descripcion', 'jind_usuario_ingreso', 'jind_estado', 'jind_estado_logico'], 'required'],
            [['jind_usuario_ingreso', 'jind_usuario_modifica'], 'integer'],
            [['jind_fecha_creacion', 'jind_fecha_modificacion'], 'safe'],
            [['jind_nombre'], 'string', 'max' => 300],
            [['jind_descripcion'], 'string', 'max' => 500],
            [['jind_estado', 'jind_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'jind_id' => 'Jind ID',
            'jind_nombre' => 'Jind Nombre',
            'jind_descripcion' => 'Jind Descripcion',
            'jind_usuario_ingreso' => 'Jind Usuario Ingreso',
            'jind_usuario_modifica' => 'Jind Usuario Modifica',
            'jind_estado' => 'Jind Estado',
            'jind_fecha_creacion' => 'Jind Fecha Creacion',
            'jind_fecha_modificacion' => 'Jind Fecha Modificacion',
            'jind_estado_logico' => 'Jind Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['jind_id' => 'jind_id']);
    }
}
