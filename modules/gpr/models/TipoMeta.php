<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "tipo_meta".
 *
 * @property int $tmet_id
 * @property string $tmet_nombre
 * @property string $tmet_descripcion
 * @property int $tmet_usuario_ingreso
 * @property int|null $tmet_usuario_modifica
 * @property string $tmet_estado
 * @property string $tmet_fecha_creacion
 * @property string|null $tmet_fecha_modificacion
 * @property string $tmet_estado_logico
 *
 * @property Indicador[] $indicadors
 */
class TipoMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_meta';
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
            [['tmet_nombre', 'tmet_descripcion', 'tmet_usuario_ingreso', 'tmet_estado', 'tmet_estado_logico'], 'required'],
            [['tmet_usuario_ingreso', 'tmet_usuario_modifica'], 'integer'],
            [['tmet_fecha_creacion', 'tmet_fecha_modificacion'], 'safe'],
            [['tmet_nombre'], 'string', 'max' => 300],
            [['tmet_descripcion'], 'string', 'max' => 500],
            [['tmet_estado', 'tmet_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tmet_id' => 'Tmet ID',
            'tmet_nombre' => 'Tmet Nombre',
            'tmet_descripcion' => 'Tmet Descripcion',
            'tmet_usuario_ingreso' => 'Tmet Usuario Ingreso',
            'tmet_usuario_modifica' => 'Tmet Usuario Modifica',
            'tmet_estado' => 'Tmet Estado',
            'tmet_fecha_creacion' => 'Tmet Fecha Creacion',
            'tmet_fecha_modificacion' => 'Tmet Fecha Modificacion',
            'tmet_estado_logico' => 'Tmet Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['tmet_id' => 'tmet_id']);
    }
}
