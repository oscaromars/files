<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "tipo_agrupacion".
 *
 * @property int $tagr_id
 * @property string $tagr_nombre
 * @property string $tagr_descripcion
 * @property int $tagr_usuario_ingreso
 * @property int|null $tagr_usuario_modifica
 * @property string $tagr_estado
 * @property string $tagr_fecha_creacion
 * @property string|null $tagr_fecha_modificacion
 * @property string $tagr_estado_logico
 *
 * @property Indicador[] $indicadors
 */
class TipoAgrupacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_agrupacion';
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
            [['tagr_nombre', 'tagr_descripcion', 'tagr_usuario_ingreso', 'tagr_estado', 'tagr_estado_logico'], 'required'],
            [['tagr_usuario_ingreso', 'tagr_usuario_modifica'], 'integer'],
            [['tagr_fecha_creacion', 'tagr_fecha_modificacion'], 'safe'],
            [['tagr_nombre'], 'string', 'max' => 300],
            [['tagr_descripcion'], 'string', 'max' => 500],
            [['tagr_estado', 'tagr_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tagr_id' => 'Tagr ID',
            'tagr_nombre' => 'Tagr Nombre',
            'tagr_descripcion' => 'Tagr Descripcion',
            'tagr_usuario_ingreso' => 'Tagr Usuario Ingreso',
            'tagr_usuario_modifica' => 'Tagr Usuario Modifica',
            'tagr_estado' => 'Tagr Estado',
            'tagr_fecha_creacion' => 'Tagr Fecha Creacion',
            'tagr_fecha_modificacion' => 'Tagr Fecha Modificacion',
            'tagr_estado_logico' => 'Tagr Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['tagr_id' => 'tagr_id']);
    }
}
