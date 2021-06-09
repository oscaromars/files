<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "fechas_vencimiento_pago".
 *
 * @property int $fvpa_id
 * @property int $fvpa_paca_id
 * @property int $fvpa_cuota
 * @property string $fvpa_fecha_vencimiento
 * @property int $fvpa_estado
 * @property int $fvpa_periodo_academico
 * @property int $fvpa_bloque
 * @property string $fvpa_fecha_creacion
 * @property string $fvpa_usuario_modificacion
 * @property string $fvpa_fecha_modificacion
 * @property int $fvpa_estado_logico
 */
class FechasVencimientoPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fechas_vencimiento_pago';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fvpa_paca_id', 'fvpa_cuota', 'fvpa_estado', 'fvpa_periodo_academico', 'fvpa_bloque', 'fvpa_estado_logico'], 'integer'],
            [['fvpa_fecha_vencimiento', 'fvpa_fecha_creacion', 'fvpa_fecha_modificacion'], 'safe'],
            [['fvpa_usuario_modificacion'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fvpa_id' => 'Fvpa ID',
            'fvpa_paca_id' => 'Fvpa Paca ID',
            'fvpa_cuota' => 'Fvpa Cuota',
            'fvpa_fecha_vencimiento' => 'Fvpa Fecha Vencimiento',
            'fvpa_estado' => 'Fvpa Estado',
            'fvpa_periodo_academico' => 'Fvpa Periodo Academico',
            'fvpa_bloque' => 'Fvpa Bloque',
            'fvpa_fecha_creacion' => 'Fvpa Fecha Creacion',
            'fvpa_usuario_modificacion' => 'Fvpa Usuario Modificacion',
            'fvpa_fecha_modificacion' => 'Fvpa Fecha Modificacion',
            'fvpa_estado_logico' => 'Fvpa Estado Logico',
        ];
    }
}
