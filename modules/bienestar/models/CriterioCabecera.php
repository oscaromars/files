<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "criterio_cabecera".
 *
 * @property int $crtcab_id
 * @property string $crtcab_descripcion
 * @property string $crtcab_fecha_creacion
 * @property string $crtcab_fecha_modificacion
 * @property string $crtcab_estado
 * @property string $crtcab_estado_logico
 *
 * @property CriterioDetalle[] $criterioDetalles
 */
class CriterioCabecera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'criterio_cabecera';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_bienestar');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['crtcab_id', 'crtcab_descripcion', 'crtcab_estado', 'crtcab_estado_logico'], 'required'],
            [['crtcab_id'], 'integer'],
            [['crtcab_fecha_creacion', 'crtcab_fecha_modificacion'], 'safe'],
            [['crtcab_descripcion'], 'string', 'max' => 45],
            [['crtcab_estado', 'crtcab_estado_logico'], 'string', 'max' => 1],
            [['crtcab_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'crtcab_id' => 'Crtcab ID',
            'crtcab_descripcion' => 'Crtcab Descripcion',
            'crtcab_fecha_creacion' => 'Crtcab Fecha Creacion',
            'crtcab_fecha_modificacion' => 'Crtcab Fecha Modificacion',
            'crtcab_estado' => 'Crtcab Estado',
            'crtcab_estado_logico' => 'Crtcab Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriterioDetalles()
    {
        return $this->hasMany(CriterioDetalle::className(), ['crtcab_id' => 'crtcab_id']);
    }
}
