<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "paralelo".
 *
 * @property int $par_id
 * @property int $pmin_id
 * @property string $par_nombre
 * @property string $par_descripcion
 * @property int $par_usuario_ingreso
 * @property int $par_usuario_modifica
 * @property string $par_estado
 * @property string $par_fecha_creacion
 * @property string $par_fecha_modificacion
 * @property string $par_estado_logico
 *
 * @property AsignacionParalelo[] $asignacionParalelos
 * @property PeriodoMetodoIngreso $pmin
 */
class Paralelo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paralelo';
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
            [['pmin_id', 'par_nombre', 'par_descripcion', 'par_usuario_ingreso', 'par_estado', 'par_estado_logico'], 'required'],
            [['pmin_id', 'par_usuario_ingreso', 'par_usuario_modifica'], 'integer'],
            [['par_fecha_creacion', 'par_fecha_modificacion'], 'safe'],
            [['par_nombre'], 'string', 'max' => 300],
            [['par_descripcion'], 'string', 'max' => 500],
            [['par_estado', 'par_estado_logico'], 'string', 'max' => 1],
            [['pmin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoMetodoIngreso::className(), 'targetAttribute' => ['pmin_id' => 'pmin_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'par_id' => 'Par ID',
            'pmin_id' => 'Pmin ID',
            'par_nombre' => 'Par Nombre',
            'par_descripcion' => 'Par Descripcion',
            'par_usuario_ingreso' => 'Par Usuario Ingreso',
            'par_usuario_modifica' => 'Par Usuario Modifica',
            'par_estado' => 'Par Estado',
            'par_fecha_creacion' => 'Par Fecha Creacion',
            'par_fecha_modificacion' => 'Par Fecha Modificacion',
            'par_estado_logico' => 'Par Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacionParalelos()
    {
        return $this->hasMany(AsignacionParalelo::className(), ['par_id' => 'par_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPmin()
    {
        return $this->hasOne(PeriodoMetodoIngreso::className(), ['pmin_id' => 'pmin_id']);
    }
}
