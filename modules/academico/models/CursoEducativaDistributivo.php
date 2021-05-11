<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "curso_educativa_distributivo".
 *
 * @property int $cedi_id
 * @property int $cedu_id
 * @property int $daca_id
 * @property int $cedi_usuario_ingreso
 * @property int $cedi_usuario_modifica
 * @property string $cedi_estado
 * @property string $cedi_fecha_creacion
 * @property string $cedi_fecha_modificacion
 * @property string $cedi_estado_logico
 *
 * @property CursoEducativa $cedu
 * @property DistributivoAcademico $daca
 */
class CursoEducativaDistributivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa_distributivo';
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
            [['cedu_id', 'daca_id', 'cedi_usuario_ingreso', 'cedi_estado', 'cedi_estado_logico'], 'required'],
            [['cedu_id', 'daca_id', 'cedi_usuario_ingreso', 'cedi_usuario_modifica'], 'integer'],
            [['cedi_fecha_creacion', 'cedi_fecha_modificacion'], 'safe'],
            [['cedi_estado', 'cedi_estado_logico'], 'string', 'max' => 1],
            [['cedu_id'], 'exist', 'skipOnError' => true, 'targetClass' => CursoEducativa::className(), 'targetAttribute' => ['cedu_id' => 'cedu_id']],
            [['daca_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistributivoAcademico::className(), 'targetAttribute' => ['daca_id' => 'daca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cedi_id' => 'Cedi ID',
            'cedu_id' => 'Cedu ID',
            'daca_id' => 'Daca ID',
            'cedi_usuario_ingreso' => 'Cedi Usuario Ingreso',
            'cedi_usuario_modifica' => 'Cedi Usuario Modifica',
            'cedi_estado' => 'Cedi Estado',
            'cedi_fecha_creacion' => 'Cedi Fecha Creacion',
            'cedi_fecha_modificacion' => 'Cedi Fecha Modificacion',
            'cedi_estado_logico' => 'Cedi Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCedu()
    {
        return $this->hasOne(CursoEducativa::className(), ['cedu_id' => 'cedu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDaca()
    {
        return $this->hasOne(DistributivoAcademico::className(), ['daca_id' => 'daca_id']);
    }
}
