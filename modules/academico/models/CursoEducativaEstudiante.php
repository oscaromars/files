<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "curso_educativa_estudiante".
 *
 * @property int $ceest_id
 * @property int $cedu_id
 * @property int $est_id
 * @property string $ceest_estado_bloqueo
 * @property int $ceest_usuario_ingreso
 * @property int $ceest_usuario_modifica
 * @property string $ceest_estado
 * @property string $ceest_fecha_creacion
 * @property string $ceest_fecha_modificacion
 * @property string $ceest_estado_logico
 *
 * @property Estudiante $est
 * @property CursoEducativa $cedu
 */
class CursoEducativaEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa_estudiante';
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
            [['cedu_id', 'est_id', 'ceest_estado_bloqueo', 'ceest_usuario_ingreso', 'ceest_estado', 'ceest_estado_logico'], 'required'],
            [['cedu_id', 'est_id', 'ceest_usuario_ingreso', 'ceest_usuario_modifica'], 'integer'],
            [['ceest_fecha_creacion', 'ceest_fecha_modificacion'], 'safe'],
            [['ceest_estado_bloqueo', 'ceest_estado', 'ceest_estado_logico'], 'string', 'max' => 1],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
            [['cedu_id'], 'exist', 'skipOnError' => true, 'targetClass' => CursoEducativa::className(), 'targetAttribute' => ['cedu_id' => 'cedu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ceest_id' => 'Ceest ID',
            'cedu_id' => 'Cedu ID',
            'est_id' => 'Est ID',
            'ceest_estado_bloqueo' => 'Ceest Estado Bloqueo',
            'ceest_usuario_ingreso' => 'Ceest Usuario Ingreso',
            'ceest_usuario_modifica' => 'Ceest Usuario Modifica',
            'ceest_estado' => 'Ceest Estado',
            'ceest_fecha_creacion' => 'Ceest Fecha Creacion',
            'ceest_fecha_modificacion' => 'Ceest Fecha Modificacion',
            'ceest_estado_logico' => 'Ceest Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCedu()
    {
        return $this->hasOne(CursoEducativa::className(), ['cedu_id' => 'cedu_id']);
    }
}
