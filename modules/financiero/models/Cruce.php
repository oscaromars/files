<?php

namespace app\modules\models\academico;

use Yii;

/**
 * This is the model class for table "cruce".
 *
 * @property int $cru_id
 * @property int $est_id
 * @property int $pfes_id
 * @property string $cru_comprobante
 * @property string|null $cru_fecha_comprobante
 * @property float $cru_saldo_favor_inicial
 * @property float $cru_saldo_favor
 * @property string $cru_estado_cruce
 * @property int $cru_usu_ingreso
 * @property int|null $cru_usu_modifica
 * @property string $cru_estado
 * @property string $cru_fecha_creacion
 * @property string|null $cru_fecha_modificacion
 * @property string $cru_estado_logico
 *
 * @property PagosFacturaEstudiante $pfes
 * @property DetalleCruce[] $detalleCruces
 */
class Cruce extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruce';
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
            [['est_id', 'pfes_id', 'cru_comprobante', 'cru_saldo_favor_inicial', 'cru_saldo_favor', 'cru_estado_cruce', 'cru_usu_ingreso', 'cru_estado', 'cru_estado_logico'], 'required'],
            [['est_id', 'pfes_id', 'cru_usu_ingreso', 'cru_usu_modifica'], 'integer'],
            [['cru_fecha_comprobante', 'cru_fecha_creacion', 'cru_fecha_modificacion'], 'safe'],
            [['cru_saldo_favor_inicial', 'cru_saldo_favor'], 'number'],
            [['cru_comprobante'], 'string', 'max' => 30],
            [['cru_estado_cruce'], 'string', 'max' => 3],
            [['cru_estado', 'cru_estado_logico'], 'string', 'max' => 1],
            [['pfes_id'], 'exist', 'skipOnError' => true, 'targetClass' => PagosFacturaEstudiante::className(), 'targetAttribute' => ['pfes_id' => 'pfes_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cru_id' => 'Cru ID',
            'est_id' => 'Est ID',
            'pfes_id' => 'Pfes ID',
            'cru_comprobante' => 'Cru Comprobante',
            'cru_fecha_comprobante' => 'Cru Fecha Comprobante',
            'cru_saldo_favor_inicial' => 'Cru Saldo Favor Inicial',
            'cru_saldo_favor' => 'Cru Saldo Favor',
            'cru_estado_cruce' => 'Cru Estado Cruce',
            'cru_usu_ingreso' => 'Cru Usu Ingreso',
            'cru_usu_modifica' => 'Cru Usu Modifica',
            'cru_estado' => 'Cru Estado',
            'cru_fecha_creacion' => 'Cru Fecha Creacion',
            'cru_fecha_modificacion' => 'Cru Fecha Modificacion',
            'cru_estado_logico' => 'Cru Estado Logico',
        ];
    }

    /**
     * Gets query for [[Pfes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPfes()
    {
        return $this->hasOne(PagosFacturaEstudiante::className(), ['pfes_id' => 'pfes_id']);
    }

    /**
     * Gets query for [[DetalleCruces]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCruces()
    {
        return $this->hasMany(DetalleCruce::className(), ['cru_id' => 'cru_id']);
    }
}
