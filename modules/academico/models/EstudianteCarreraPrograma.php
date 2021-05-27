<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "estudiante_carrera_programa".
 *
 * @property int $ecpr_id
 * @property int $est_id
 * @property int $meun_id
 * @property string $ecpr_fecha_registro
 * @property int $ecpr_usuario_ingreso
 * @property int $ecpr_usuario_modifica
 * @property string $ecpr_estado
 * @property string $ecpr_fecha_creacion
 * @property string $ecpr_fecha_modificacion
 * @property string $ecpr_estado_logico
 *
 * @property Estudiante $est
 * @property ModalidadEstudioUnidad $meun
 */
class EstudianteCarreraPrograma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudiante_carrera_programa';
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
            [['est_id', 'meun_id', 'ecpr_usuario_ingreso', 'ecpr_estado', 'ecpr_estado_logico'], 'required'],
            [['est_id', 'meun_id', 'ecpr_usuario_ingreso', 'ecpr_usuario_modifica'], 'integer'],
            [['ecpr_fecha_registro', 'ecpr_fecha_creacion', 'ecpr_fecha_modificacion'], 'safe'],
            [['ecpr_estado', 'ecpr_estado_logico'], 'string', 'max' => 1],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
            [['meun_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModalidadEstudioUnidad::className(), 'targetAttribute' => ['meun_id' => 'meun_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ecpr_id' => 'Ecpr ID',
            'est_id' => 'Est ID',
            'meun_id' => 'Meun ID',
            'ecpr_fecha_registro' => 'Ecpr Fecha Registro',
            'ecpr_usuario_ingreso' => 'Ecpr Usuario Ingreso',
            'ecpr_usuario_modifica' => 'Ecpr Usuario Modifica',
            'ecpr_estado' => 'Ecpr Estado',
            'ecpr_fecha_creacion' => 'Ecpr Fecha Creacion',
            'ecpr_fecha_modificacion' => 'Ecpr Fecha Modificacion',
            'ecpr_estado_logico' => 'Ecpr Estado Logico',
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
    public function getMeun()
    {
        return $this->hasOne(ModalidadEstudioUnidad::className(), ['meun_id' => 'meun_id']);
    }
}
