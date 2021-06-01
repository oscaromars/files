<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "registro_pago_factura".
 *
 * @property int $rpfa_id
 * @property int $fpag_id
 * @property int $sins_id
 * @property string $rpfa_num_solicitud
 * @property double $rpfa_valor_documento
 * @property string $rpfa_fecha_documento
 * @property string $rpfa_numero_documento
 * @property string $rpfa_imagen
 * @property string $rpfa_observacion
 * @property string $rpfa_revisado
 * @property string $rpfa_fecha_transaccion
 * @property int $rpfa_usuario_transaccion
 * @property string $rpfa_codigo_autorizacion
 * @property string $rpfa_estado
 * @property string $rpfa_fecha_creacion
 * @property string $rpfa_fecha_modificacion
 * @property string $rpfa_estado_logico
 *
 * @property FormaPago $fpag
 */
class RegistroPagoFactura extends \app\modules\financiero\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_pago_factura';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fpag_id', 'sins_id', 'rpfa_usuario_transaccion'], 'integer'],
            [['rpfa_valor_documento', 'rpfa_numero_documento', 'rpfa_revisado', 'rpfa_usuario_transaccion', 'rpfa_estado', 'rpfa_estado_logico'], 'required'],
            [['rpfa_valor_documento'], 'number'],
            [['rpfa_fecha_documento', 'rpfa_fecha_transaccion', 'rpfa_fecha_creacion', 'rpfa_fecha_modificacion'], 'safe'],
            [['rpfa_observacion'], 'string'],
            [['rpfa_num_solicitud', 'rpfa_codigo_autorizacion'], 'string', 'max' => 10],
            [['rpfa_numero_documento'], 'string', 'max' => 50],
            [['rpfa_imagen'], 'string', 'max' => 100],
            [['rpfa_revisado'], 'string', 'max' => 2],
            [['rpfa_estado', 'rpfa_estado_logico'], 'string', 'max' => 1],
            [['fpag_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['fpag_id' => 'fpag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpfa_id' => 'Rpfa ID',
            'fpag_id' => 'Fpag ID',
            'sins_id' => 'Sins ID',
            'rpfa_num_solicitud' => 'Rpfa Num Solicitud',
            'rpfa_valor_documento' => 'Rpfa Valor Documento',
            'rpfa_fecha_documento' => 'Rpfa Fecha Documento',
            'rpfa_numero_documento' => 'Rpfa Numero Documento',
            'rpfa_imagen' => 'Rpfa Imagen',
            'rpfa_observacion' => 'Rpfa Observacion',
            'rpfa_revisado' => 'Rpfa Revisado',
            'rpfa_fecha_transaccion' => 'Rpfa Fecha Transaccion',
            'rpfa_usuario_transaccion' => 'Rpfa Usuario Transaccion',
            'rpfa_codigo_autorizacion' => 'Rpfa Codigo Autorizacion',
            'rpfa_estado' => 'Rpfa Estado',
            'rpfa_fecha_creacion' => 'Rpfa Fecha Creacion',
            'rpfa_fecha_modificacion' => 'Rpfa Fecha Modificacion',
            'rpfa_estado_logico' => 'Rpfa Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFpag()
    {
        return $this->hasOne(FormaPago::className(), ['fpag_id' => 'fpag_id']);
    }
}
