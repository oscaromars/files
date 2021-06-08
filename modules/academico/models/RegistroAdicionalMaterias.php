<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "registro_adicional_materias".
 *
 * @property int $rama_id
 * @property int $ron_id
 * @property int $per_id
 * @property int $pla_id
 * @property int $paca_id
 * @property int $rpm_id
 * @property int $roi_id_1
 * @property int $roi_id_2
 * @property int $roi_id_3
 * @property int $roi_id_4
 * @property int $roi_id_5
 * @property int $roi_id_6
 * @property string $rama_estado
 * @property string $rama_fecha_creacion
 * @property string $rama_fecha_modificacion
 * @property string $rama_estado_logico
 *
 * @property RegistroOnline $ron
 */
class RegistroAdicionalMaterias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_adicional_materias';
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
            [['ron_id', 'per_id', 'pla_id', 'paca_id', 'rama_estado', 'rama_estado_logico'], 'required'],
            [['ron_id', 'per_id', 'pla_id', 'paca_id', 'rpm_id', 'roi_id_1', 'roi_id_2', 'roi_id_3', 'roi_id_4', 'roi_id_5', 'roi_id_6'], 'integer'],
            [['rama_fecha_creacion', 'rama_fecha_modificacion'], 'safe'],
            [['rama_estado', 'rama_estado_logico'], 'string', 'max' => 1],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rama_id' => 'Rama ID',
            'ron_id' => 'Ron ID',
            'per_id' => 'Per ID',
            'pla_id' => 'Pla ID',
            'paca_id' => 'Paca ID',
            'rpm_id' => 'Rpm ID',
            'roi_id_1' => 'Roi Id 1',
            'roi_id_2' => 'Roi Id 2',
            'roi_id_3' => 'Roi Id 3',
            'roi_id_4' => 'Roi Id 4',
            'roi_id_5' => 'Roi Id 5',
            'roi_id_6' => 'Roi Id 6',
            'rama_estado' => 'Rama Estado',
            'rama_fecha_creacion' => 'Rama Fecha Creacion',
            'rama_fecha_modificacion' => 'Rama Fecha Modificacion',
            'rama_estado_logico' => 'Rama Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRon()
    {
        return $this->hasOne(RegistroOnline::className(), ['ron_id' => 'ron_id']);
    }
}
