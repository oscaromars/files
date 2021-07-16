<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "criterio_detalle".
 *
 * @property int $crtdet_id
 * @property int $crtcab_id
 * @property int $fsec_id
 * @property string $crtdet_descripcion
 * @property string $crtdet_fecha_creacion
 * @property string $crtdet_fecha_modificacion
 * @property string $crtdet_estado
 * @property string $crtdet_estado_logico
 *
 * @property CriterioCabecera $crtcab
 * @property FormularioSeccion $fsec
 * @property FormularioCondicionesPonderaciones[] $formularioCondicionesPonderaciones
 */
class CriterioDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'criterio_detalle';
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
            [['crtdet_id', 'crtcab_id', 'fsec_id', 'crtdet_descripcion', 'crtdet_estado', 'crtdet_estado_logico'], 'required'],
            [['crtdet_id', 'crtcab_id', 'fsec_id'], 'integer'],
            [['crtdet_fecha_creacion', 'crtdet_fecha_modificacion'], 'safe'],
            [['crtdet_descripcion'], 'string', 'max' => 80],
            [['crtdet_estado', 'crtdet_estado_logico'], 'string', 'max' => 1],
            [['crtdet_id'], 'unique'],
            [['crtcab_id'], 'exist', 'skipOnError' => true, 'targetClass' => CriterioCabecera::className(), 'targetAttribute' => ['crtcab_id' => 'crtcab_id']],
            [['fsec_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormularioSeccion::className(), 'targetAttribute' => ['fsec_id' => 'fsec_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'crtdet_id' => 'Crtdet ID',
            'crtcab_id' => 'Crtcab ID',
            'fsec_id' => 'Fsec ID',
            'crtdet_descripcion' => 'Crtdet Descripcion',
            'crtdet_fecha_creacion' => 'Crtdet Fecha Creacion',
            'crtdet_fecha_modificacion' => 'Crtdet Fecha Modificacion',
            'crtdet_estado' => 'Crtdet Estado',
            'crtdet_estado_logico' => 'Crtdet Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrtcab()
    {
        return $this->hasOne(CriterioCabecera::className(), ['crtcab_id' => 'crtcab_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsec()
    {
        return $this->hasOne(FormularioSeccion::className(), ['fsec_id' => 'fsec_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormularioCondicionesPonderaciones()
    {
        return $this->hasMany(FormularioCondicionesPonderaciones::className(), ['crtdet_id' => 'crtdet_id']);
    }
}
