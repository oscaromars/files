<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "unidad_medida".
 *
 * @property int $umed_id
 * @property string $umed_nombre
 * @property string $umed_descripcion
 * @property int $umed_usuario_ingreso
 * @property int|null $umed_usuario_modifica
 * @property string $umed_estado
 * @property string $umed_fecha_creacion
 * @property string|null $umed_fecha_modificacion
 * @property string $umed_estado_logico
 *
 * @property Indicador[] $indicadors
 */
class UnidadMedida extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unidad_medida';
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
            [['umed_nombre', 'umed_descripcion', 'umed_usuario_ingreso', 'umed_estado', 'umed_estado_logico'], 'required'],
            [['umed_usuario_ingreso', 'umed_usuario_modifica'], 'integer'],
            [['umed_fecha_creacion', 'umed_fecha_modificacion'], 'safe'],
            [['umed_nombre'], 'string', 'max' => 300],
            [['umed_descripcion'], 'string', 'max' => 500],
            [['umed_estado', 'umed_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'umed_id' => 'Umed ID',
            'umed_nombre' => 'Umed Nombre',
            'umed_descripcion' => 'Umed Descripcion',
            'umed_usuario_ingreso' => 'Umed Usuario Ingreso',
            'umed_usuario_modifica' => 'Umed Usuario Modifica',
            'umed_estado' => 'Umed Estado',
            'umed_fecha_creacion' => 'Umed Fecha Creacion',
            'umed_fecha_modificacion' => 'Umed Fecha Modificacion',
            'umed_estado_logico' => 'Umed Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['umed_id' => 'umed_id']);
    }
}
