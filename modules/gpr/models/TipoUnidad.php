<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "tipo_unidad".
 *
 * @property int $tuni_id
 * @property string $tuni_nombre
 * @property string $tuni_descripcion
 * @property string $tuni_alias
 * @property int $tuni_usuario_ingreso
 * @property int|null $tuni_usuario_modifica
 * @property string $tuni_estado
 * @property string $tuni_fecha_creacion
 * @property string|null $tuni_fecha_modificacion
 * @property string $tuni_estado_logico
 *
 * @property UnidadGpr[] $unidadGprs
 */
class TipoUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_unidad';
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
            [['tuni_nombre', 'tuni_descripcion', 'tuni_alias', 'tuni_usuario_ingreso', 'tuni_estado', 'tuni_estado_logico'], 'required'],
            [['tuni_usuario_ingreso', 'tuni_usuario_modifica'], 'integer'],
            [['tuni_fecha_creacion', 'tuni_fecha_modificacion'], 'safe'],
            [['tuni_nombre'], 'string', 'max' => 300],
            [['tuni_descripcion'], 'string', 'max' => 500],
            [['tuni_alias'], 'string', 'max' => 10],
            [['tuni_estado', 'tuni_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tuni_id' => 'Tuni ID',
            'tuni_nombre' => 'Tuni Nombre',
            'tuni_descripcion' => 'Tuni Descripcion',
            'tuni_alias' => 'Tuni Alias',
            'tuni_usuario_ingreso' => 'Tuni Usuario Ingreso',
            'tuni_usuario_modifica' => 'Tuni Usuario Modifica',
            'tuni_estado' => 'Tuni Estado',
            'tuni_fecha_creacion' => 'Tuni Fecha Creacion',
            'tuni_fecha_modificacion' => 'Tuni Fecha Modificacion',
            'tuni_estado_logico' => 'Tuni Estado Logico',
        ];
    }

    /**
     * Gets query for [[UnidadGprs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadGprs()
    {
        return $this->hasMany(UnidadGpr::className(), ['tuni_id' => 'tuni_id']);
    }
}
