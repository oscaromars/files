<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_seguridad".
 *
 * @property int $cseg_id
 * @property int $tpas_id
 * @property string $cseg_long_pass
 * @property int $cseg_expiracion
 * @property string $cseg_descripcion
 * @property string $cseg_estado_activo
 * @property string $cseg_fecha_creacion
 * @property string $cseg_fecha_modificacion
 * @property string $cseg_estado_logico
 *
 * @property TipoPassword $tpas
 * @property Grupo[] $grupos
 */
class ConfiguracionSeguridad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_seguridad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tpas_id', 'cseg_estado_activo', 'cseg_estado_logico'], 'required'],
            [['tpas_id', 'cseg_expiracion'], 'integer'],
            [['cseg_fecha_creacion', 'cseg_fecha_modificacion'], 'safe'],
            [['cseg_long_pass'], 'string', 'max' => 50],
            [['cseg_descripcion'], 'string', 'max' => 200],
            [['cseg_estado_activo', 'cseg_estado_logico'], 'string', 'max' => 1],
            [['tpas_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoPassword::className(), 'targetAttribute' => ['tpas_id' => 'tpas_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cseg_id' => 'Cseg ID',
            'tpas_id' => 'Tpas ID',
            'cseg_long_pass' => 'Cseg Long Pass',
            'cseg_expiracion' => 'Cseg Expiracion',
            'cseg_descripcion' => 'Cseg Descripcion',
            'cseg_estado_activo' => 'Cseg Estado Activo',
            'cseg_fecha_creacion' => 'Cseg Fecha Creacion',
            'cseg_fecha_modificacion' => 'Cseg Fecha Modificacion',
            'cseg_estado_logico' => 'Cseg Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTpas()
    {
        return $this->hasOne(TipoPassword::className(), ['tpas_id' => 'tpas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupos()
    {
        return $this->hasMany(Grupo::className(), ['cseg_id' => 'cseg_id']);
    }
}
