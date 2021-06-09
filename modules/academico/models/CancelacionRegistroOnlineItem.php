<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "cancelacion_registro_online_item".
 *
 * @property int $croi_id
 * @property int $cron_id
 * @property int $roi_id
 * @property string $croi_estado
 * @property string $croi_fecha_creacion
 * @property string $croi_fecha_modificacion
 * @property string $croi_estado_logico
 *
 * @property CancelacionRegistroOnline $cron
 * @property RegistroOnlineItem $roi
 */
class CancelacionRegistroOnlineItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cancelacion_registro_online_item';
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
            [['cron_id', 'roi_id', 'croi_estado', 'croi_estado_logico'], 'required'],
            [['cron_id', 'roi_id'], 'integer'],
            [['croi_fecha_creacion', 'croi_fecha_modificacion'], 'safe'],
            [['croi_estado', 'croi_estado_logico'], 'string', 'max' => 1],
            [['cron_id'], 'exist', 'skipOnError' => true, 'targetClass' => CancelacionRegistroOnline::className(), 'targetAttribute' => ['cron_id' => 'cron_id']],
            [['roi_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnlineItem::className(), 'targetAttribute' => ['roi_id' => 'roi_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'croi_id' => 'Croi ID',
            'cron_id' => 'Cron ID',
            'roi_id' => 'Roi ID',
            'croi_estado' => 'Croi Estado',
            'croi_fecha_creacion' => 'Croi Fecha Creacion',
            'croi_fecha_modificacion' => 'Croi Fecha Modificacion',
            'croi_estado_logico' => 'Croi Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCron()
    {
        return $this->hasOne(CancelacionRegistroOnline::className(), ['cron_id' => 'cron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoi()
    {
        return $this->hasOne(RegistroOnlineItem::className(), ['roi_id' => 'roi_id']);
    }
}
