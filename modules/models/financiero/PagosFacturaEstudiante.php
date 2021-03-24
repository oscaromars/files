<?php

namespace app\modules\models\financiero;

use Yii;

/**
 * This is the model class for table "pagos_factura_estudiante".
 *
 * @property int $pfes_id
 * @property int $est_id
 * @property string $pfes_concepto
 * @property int|null $ban_id
 * @property string|null $pfes_referencia
 * @property int $fpag_id
 * @property float $pfes_valor_pago
 * @property string|null $pfes_fecha_pago
 * @property string|null $pfes_observacion
 * @property string $pfes_archivo_pago
 * @property string|null $pfes_fecha_registro
 * @property int $pfes_usu_ingreso
 * @property string $pfes_estado
 * @property string $pfes_fecha_creacion
 * @property string|null $pfes_fecha_modificacion
 * @property string $pfes_estado_logico
 *
 * @property Cruce[] $cruces
 * @property FormaPago $fpag
 */
class PagosFacturaEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagos_factura_estudiante';
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
            [['est_id', 'pfes_concepto', 'fpag_id', 'pfes_valor_pago', 'pfes_archivo_pago', 'pfes_usu_ingreso', 'pfes_estado', 'pfes_estado_logico'], 'required'],
            [['est_id', 'ban_id', 'fpag_id', 'pfes_usu_ingreso'], 'integer'],
            [['pfes_valor_pago'], 'number'],
            [['pfes_fecha_pago', 'pfes_fecha_registro', 'pfes_fecha_creacion', 'pfes_fecha_modificacion'], 'safe'],
            [['pfes_concepto'], 'string', 'max' => 3],
            [['pfes_referencia'], 'string', 'max' => 50],
            [['pfes_observacion'], 'string', 'max' => 500],
            [['pfes_archivo_pago'], 'string', 'max' => 200],
            [['pfes_estado', 'pfes_estado_logico'], 'string', 'max' => 1],
            [['fpag_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['fpag_id' => 'fpag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pfes_id' => 'Pfes ID',
            'est_id' => 'Est ID',
            'pfes_concepto' => 'Pfes Concepto',
            'ban_id' => 'Ban ID',
            'pfes_referencia' => 'Pfes Referencia',
            'fpag_id' => 'Fpag ID',
            'pfes_valor_pago' => 'Pfes Valor Pago',
            'pfes_fecha_pago' => 'Pfes Fecha Pago',
            'pfes_observacion' => 'Pfes Observacion',
            'pfes_archivo_pago' => 'Pfes Archivo Pago',
            'pfes_fecha_registro' => 'Pfes Fecha Registro',
            'pfes_usu_ingreso' => 'Pfes Usu Ingreso',
            'pfes_estado' => 'Pfes Estado',
            'pfes_fecha_creacion' => 'Pfes Fecha Creacion',
            'pfes_fecha_modificacion' => 'Pfes Fecha Modificacion',
            'pfes_estado_logico' => 'Pfes Estado Logico',
        ];
    }

    /**
     * Gets query for [[Cruces]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruces()
    {
        return $this->hasMany(Cruce::className(), ['pfes_id' => 'pfes_id']);
    }

    /**
     * Gets query for [[Fpag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFpag()
    {
        return $this->hasOne(FormaPago::className(), ['fpag_id' => 'fpag_id']);
    }
}
