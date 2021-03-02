<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aplicacion".
 *
 * @property int $apl_id
 * @property string $apl_nombre
 * @property string $apl_tipo
 * @property string $apl_estado_activo
 * @property string $apl_fecha_creacion
 * @property string $apl_fecha_modificacion
 * @property string $apl_estado_logico
 *
 * @property Modulo[] $modulos
 */
class Aplicacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'aplicacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apl_estado_activo', 'apl_estado_logico'], 'required'],
            [['apl_fecha_creacion', 'apl_fecha_modificacion'], 'safe'],
            [['apl_nombre', 'apl_tipo'], 'string', 'max' => 45],
            [['apl_estado_activo', 'apl_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'apl_id' => 'Apl ID',
            'apl_nombre' => 'Apl Nombre',
            'apl_tipo' => 'Apl Tipo',
            'apl_estado_activo' => 'Apl Estado Activo',
            'apl_fecha_creacion' => 'Apl Fecha Creacion',
            'apl_fecha_modificacion' => 'Apl Fecha Modificacion',
            'apl_estado_logico' => 'Apl Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModulos()
    {
        return $this->hasMany(Modulo::className(), ['apl_id' => 'apl_id']);
    }
}
