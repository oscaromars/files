<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "formulario_condiciones_ponderaciones".
 *
 * @property int $fcpo_id
 * @property int $crtdet_id
 * @property string $fcpo_condicion
 * @property double $fcpo_ponderacion
 * @property string $fcpo_fecha_creacion
 * @property string $fcpo_fecha_modificacion
 * @property string $fcpo_estado
 * @property string $fcpo_estado_logico
 *
 * @property CriterioDetalle $crtdet
 * @property FormularioFamiliaresDiscapacitados[] $formularioFamiliaresDiscapacitados
 */
class FormularioCondicionesPonderaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formulario_condiciones_ponderaciones';
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
            [['fcpo_id', 'crtdet_id', 'fcpo_condicion', 'fcpo_ponderacion', 'fcpo_estado', 'fcpo_estado_logico'], 'required'],
            [['fcpo_id', 'crtdet_id'], 'integer'],
            [['fcpo_ponderacion'], 'number'],
            [['fcpo_fecha_creacion', 'fcpo_fecha_modificacion'], 'safe'],
            [['fcpo_condicion'], 'string', 'max' => 100],
            [['fcpo_estado', 'fcpo_estado_logico'], 'string', 'max' => 1],
            [['fcpo_id'], 'unique'],
            [['crtdet_id'], 'exist', 'skipOnError' => true, 'targetClass' => CriterioDetalle::className(), 'targetAttribute' => ['crtdet_id' => 'crtdet_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fcpo_id' => 'Fcpo ID',
            'crtdet_id' => 'Crtdet ID',
            'fcpo_condicion' => 'Fcpo Condicion',
            'fcpo_ponderacion' => 'Fcpo Ponderacion',
            'fcpo_fecha_creacion' => 'Fcpo Fecha Creacion',
            'fcpo_fecha_modificacion' => 'Fcpo Fecha Modificacion',
            'fcpo_estado' => 'Fcpo Estado',
            'fcpo_estado_logico' => 'Fcpo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrtdet()
    {
        return $this->hasOne(CriterioDetalle::className(), ['crtdet_id' => 'crtdet_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormularioFamiliaresDiscapacitados()
    {
        return $this->hasMany(FormularioFamiliaresDiscapacitados::className(), ['fcpo_id' => 'fcpo_id']);
    }
}
