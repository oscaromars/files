<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "db_academico_mbtu.detalle_asistencia".
 *
 * @property int $dasi_id
 * @property int $casi_id
 * @property int $ecal_id
 * @property string $dasi_tipo  
 * @property int $dasi_cantidad
 * @property int $dasi_usuario_creacion
 * @property int $dasi_usuario_modificacion
 * @property string $dasi_estado
 * @property string $dasi_fecha_creacion
 * @property string $dasi_fecha_modificacion
 * @property string $dasi_estado_logico
 */
class DetalleAsistencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_academico_mbtu.detalle_asistencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['casi_id', 'ecal_id', 'dasi_tipo' 'dasi_cantidad', 'dasi_usuario_creacion', 'dasi_estado', 'dasi_estado_logico'], 'required'],
            [['casi_id', 'dasi_cantidad', 'dasi_usuario_creacion', 'dasi_usuario_modificacion'], 'integer'],
            [['dasi_fecha_creacion', 'dasi_fecha_modificacion'], 'safe'],
            [['dasi_estado', 'dasi_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dasi_id' => 'Dasi ID',
            'casi_id' => 'Casi ID',
            'ecal_id' => 'Ecal ID',
            'dasi_tipo' => 'Dasi Tipo',
            'dasi_cantidad' => 'Dasi Cantidad',
            'dasi_usuario_creacion' => 'Dasi Usuario Creacion',
            'dasi_usuario_modificacion' => 'Dasi Usuario Modificacion',
            'dasi_estado' => 'Dasi Estado',
            'dasi_fecha_creacion' => 'Dasi Fecha Creacion',
            'dasi_fecha_modificacion' => 'Dasi Fecha Modificacion',
            'dasi_estado_logico' => 'Dasi Estado Logico',
        ];
    }
}
