<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "registro_pago".
 *
 * @property int $rpag_id
 * @property int $dpag_id
 * @property int $fpag_id
 * @property double $rpag_valor
 * @property string $rpag_fecha_pago
 * @property string $rpag_imagen
 * @property string $rpag_observacion
 * @property string $rpag_revisado
 * @property string $rpag_resultado
 * @property string $rpag_num_transaccion
 * @property string $rpag_fecha_transaccion
 * @property int $rpag_usuario_transaccion
 * @property string $rpag_codigo_autorizacion
 * @property string $rpag_estado
 * @property string $rpag_fecha_creacion
 * @property string $rpag_fecha_modificacion
 * @property string $rpag_estado_logico
 *
 * @property DesglosePago $dpag
 * @property FormaPago $fpag
 */

class RegistroPago extends \app\modules\financiero\components\CActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_pago';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dpag_id', 'fpag_id', 'rpag_valor', 'rpag_revisado', 'rpag_num_transaccion', 'rpag_usuario_transaccion', 'rpag_estado', 'rpag_estado_logico'], 'required'],
            [['dpag_id', 'fpag_id', 'rpag_usuario_transaccion'], 'integer'],
            [['rpag_valor'], 'number'],
            [['rpag_fecha_pago', 'rpag_fecha_transaccion', 'rpag_fecha_creacion', 'rpag_fecha_modificacion'], 'safe'],
            [['rpag_observacion'], 'string'],
            [['rpag_imagen'], 'string', 'max' => 100],
            [['rpag_revisado', 'rpag_resultado'], 'string', 'max' => 2],
            [['rpag_num_transaccion'], 'string', 'max' => 50],
            [['rpag_codigo_autorizacion'], 'string', 'max' => 10],
            [['rpag_estado', 'rpag_estado_logico'], 'string', 'max' => 1],
            [['dpag_id'], 'exist', 'skipOnError' => true, 'targetClass' => DesglosePago::className(), 'targetAttribute' => ['dpag_id' => 'dpag_id']],
            [['fpag_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['fpag_id' => 'fpag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpag_id' => 'Rpag ID',
            'dpag_id' => 'Dpag ID',
            'fpag_id' => 'Fpag ID',
            'rpag_valor' => 'Rpag Valor',
            'rpag_fecha_pago' => 'Rpag Fecha Pago',
            'rpag_imagen' => 'Rpag Imagen',
            'rpag_observacion' => 'Rpag Observacion',
            'rpag_revisado' => 'Rpag Revisado',
            'rpag_resultado' => 'Rpag Resultado',
            'rpag_num_transaccion' => 'Rpag Num Transaccion',
            'rpag_fecha_transaccion' => 'Rpag Fecha Transaccion',
            'rpag_usuario_transaccion' => 'Rpag Usuario Transaccion',
            'rpag_codigo_autorizacion' => 'Rpag Codigo Autorizacion',
            'rpag_estado' => 'Rpag Estado',
            'rpag_fecha_creacion' => 'Rpag Fecha Creacion',
            'rpag_fecha_modificacion' => 'Rpag Fecha Modificacion',
            'rpag_estado_logico' => 'Rpag Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDpag()
    {
        return $this->hasOne(DesglosePago::className(), ['dpag_id' => 'dpag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFpag()
    {
        return $this->hasOne(FormaPago::className(), ['fpag_id' => 'fpag_id']);
    }
}
