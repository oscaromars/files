<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "solicitud_general".
 *
 * @property int $sgen_id
 * @property int $psol_id
 * @property int $per_id
 * @property int $adm_id
 * @property string $psol_fecha_solicitud
 * @property string $sgen_estado
 * @property string $sgen_fecha_creacion
 * @property string $sgen_fecha_modificacion
 * @property string $sgen_estado_logico
 *
 * @property DetalleSolicitudGeneral[] $detalleSolicitudGenerals
 * @property OrdenPago[] $ordenPagos
 * @property PersonaSolicitud $psol
 */
class SolicitudGeneral extends \app\modules\financiero\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_general';
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
            [['psol_id', 'per_id', 'adm_id'], 'integer'],
            [['psol_fecha_solicitud', 'sgen_fecha_creacion', 'sgen_fecha_modificacion'], 'safe'],
            [['sgen_estado', 'sgen_estado_logico'], 'required'],
            [['sgen_estado', 'sgen_estado_logico'], 'string', 'max' => 1],
            [['psol_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonaSolicitud::className(), 'targetAttribute' => ['psol_id' => 'psol_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sgen_id' => 'Sgen ID',
            'psol_id' => 'Psol ID',
            'per_id' => 'Per ID',
            'adm_id' => 'Adm ID',
            'psol_fecha_solicitud' => 'Psol Fecha Solicitud',
            'sgen_estado' => 'Sgen Estado',
            'sgen_fecha_creacion' => 'Sgen Fecha Creacion',
            'sgen_fecha_modificacion' => 'Sgen Fecha Modificacion',
            'sgen_estado_logico' => 'Sgen Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleSolicitudGenerals()
    {
        return $this->hasMany(DetalleSolicitudGeneral::className(), ['sgen_id' => 'sgen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenPagos()
    {
        return $this->hasMany(OrdenPago::className(), ['sgen_id' => 'sgen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPsol()
    {
        return $this->hasOne(PersonaSolicitud::className(), ['psol_id' => 'psol_id']);
    }
}
