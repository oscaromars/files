<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "formulario_estudiante_criterio".
 *
 * @property int $fecr_id
 * @property int $fest_id
 * @property int $crtdet_id
 * @property int $fcpo_id
 * @property string $fecr_fecha_creacion
 * @property string $fecr_fecha_modificacion
 * @property string $fecr_estado
 * @property string $fecr_estado_logico
 */
class FormularioEstudianteCriterio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formulario_estudiante_criterio';
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
            [['fecr_id', 'fest_id', 'crtdet_id', 'fcpo_id', 'fecr_estado', 'fecr_estado_logico'], 'required'],
            [['fecr_id', 'fest_id', 'crtdet_id', 'fcpo_id'], 'integer'],
            [['fecr_fecha_creacion', 'fecr_fecha_modificacion'], 'safe'],
            [['fecr_estado', 'fecr_estado_logico'], 'string', 'max' => 1],
            [['fecr_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fecr_id' => 'Fecr ID',
            'fest_id' => 'Fest ID',
            'crtdet_id' => 'Crtdet ID',
            'fcpo_id' => 'Fcpo ID',
            'fecr_fecha_creacion' => 'Fecr Fecha Creacion',
            'fecr_fecha_modificacion' => 'Fecr Fecha Modificacion',
            'fecr_estado' => 'Fecr Estado',
            'fecr_estado_logico' => 'Fecr Estado Logico',
        ];
    }
}
