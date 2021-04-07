<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use Yii;
use yii\data\ArrayDataProvider;


/**
 * This is the model class for table "usuario_educativa".
 *
 * @property int $uedu_id
 * @property int $per_id
 * @property int $est_id
 * @property string $uedu_nombre
 * @property string $uedu_cedula
 * @property string $uedu_matricula
 * @property string $uedu_correo
 * @property int $uedu_usuario_ingreso
 * @property int $uedu_usuario_modifica
 * @property string $uedu_estado
 * @property string $uedu_fecha_creacion
 * @property string $uedu_fecha_modificacion
 * @property string $uedu_estado_logico
 *
 * @property Estudiante $est
 */
class UsuarioEducativa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_educativa';
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
            [['per_id', 'est_id', 'uedu_nombre', 'uedu_cedula', 'uedu_usuario_ingreso', 'uedu_estado', 'uedu_estado_logico'], 'required'],
            [['per_id', 'est_id', 'uedu_usuario_ingreso', 'uedu_usuario_modifica'], 'integer'],
            [['uedu_fecha_creacion', 'uedu_fecha_modificacion'], 'safe'],
            [['uedu_nombre'], 'string', 'max' => 500],
            [['uedu_cedula'], 'string', 'max' => 15],
            [['uedu_matricula'], 'string', 'max' => 20],
            [['uedu_correo'], 'string', 'max' => 250],
            [['uedu_estado', 'uedu_estado_logico'], 'string', 'max' => 1],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uedu_id' => 'Uedu ID',
            'per_id' => 'Per ID',
            'est_id' => 'Est ID',
            'uedu_nombre' => 'Uedu Nombre',
            'uedu_cedula' => 'Uedu Cedula',
            'uedu_matricula' => 'Uedu Matricula',
            'uedu_correo' => 'Uedu Correo',
            'uedu_usuario_ingreso' => 'Uedu Usuario Ingreso',
            'uedu_usuario_modifica' => 'Uedu Usuario Modifica',
            'uedu_estado' => 'Uedu Estado',
            'uedu_fecha_creacion' => 'Uedu Fecha Creacion',
            'uedu_fecha_modificacion' => 'Uedu Fecha Modificacion',
            'uedu_estado_logico' => 'Uedu Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }
}
