<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "patron_indicador".
 *
 * @property int $pind_id
 * @property string $pind_nombre
 * @property string $pind_descripcion
 * @property int $pind_usuario_ingreso
 * @property int|null $pind_usuario_modifica
 * @property string $pind_estado
 * @property string $pind_fecha_creacion
 * @property string|null $pind_fecha_modificacion
 * @property string $pind_estado_logico
 *
 * @property Indicador[] $indicadors
 */
class PatronIndicador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patron_indicador';
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
            [['pind_nombre', 'pind_descripcion', 'pind_usuario_ingreso', 'pind_estado', 'pind_estado_logico'], 'required'],
            [['pind_usuario_ingreso', 'pind_usuario_modifica'], 'integer'],
            [['pind_fecha_creacion', 'pind_fecha_modificacion'], 'safe'],
            [['pind_nombre'], 'string', 'max' => 300],
            [['pind_descripcion'], 'string', 'max' => 500],
            [['pind_estado', 'pind_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pind_id' => 'Pind ID',
            'pind_nombre' => 'Pind Nombre',
            'pind_descripcion' => 'Pind Descripcion',
            'pind_usuario_ingreso' => 'Pind Usuario Ingreso',
            'pind_usuario_modifica' => 'Pind Usuario Modifica',
            'pind_estado' => 'Pind Estado',
            'pind_fecha_creacion' => 'Pind Fecha Creacion',
            'pind_fecha_modificacion' => 'Pind Fecha Modificacion',
            'pind_estado_logico' => 'Pind Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['pind_id' => 'pind_id']);
    }
}
