<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "modalidad_estudio_unidad".
 *
 * @property int $meun_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $eaca_id
 * @property int $emp_id
 * @property int $meun_usuario_ingreso
 * @property int $meun_usuario_modifica
 * @property string $meun_estado
 * @property string $meun_fecha_creacion
 * @property string $meun_fecha_modificacion
 * @property string $meun_estado_logico
 *
 * @property EstudianteCarreraPrograma[] $estudianteCarreraProgramas
 * @property MallaUnidadModalidad[] $mallaUnidadModalidads
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 * @property EstudioAcademico $eaca
 */
class ModalidadEstudioUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modalidad_estudio_unidad';
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
            [['uaca_id', 'mod_id', 'eaca_id', 'emp_id', 'meun_usuario_ingreso', 'meun_estado', 'meun_estado_logico'], 'required'],
            [['uaca_id', 'mod_id', 'eaca_id', 'emp_id', 'meun_usuario_ingreso', 'meun_usuario_modifica'], 'integer'],
            [['meun_fecha_creacion', 'meun_fecha_modificacion'], 'safe'],
            [['meun_estado', 'meun_estado_logico'], 'string', 'max' => 1],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
            [['eaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstudioAcademico::className(), 'targetAttribute' => ['eaca_id' => 'eaca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'meun_id' => 'Meun ID',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'eaca_id' => 'Eaca ID',
            'emp_id' => 'Emp ID',
            'meun_usuario_ingreso' => 'Meun Usuario Ingreso',
            'meun_usuario_modifica' => 'Meun Usuario Modifica',
            'meun_estado' => 'Meun Estado',
            'meun_fecha_creacion' => 'Meun Fecha Creacion',
            'meun_fecha_modificacion' => 'Meun Fecha Modificacion',
            'meun_estado_logico' => 'Meun Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudianteCarreraProgramas()
    {
        return $this->hasMany(EstudianteCarreraPrograma::className(), ['meun_id' => 'meun_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaUnidadModalidads()
    {
        return $this->hasMany(MallaUnidadModalidad::className(), ['meun_id' => 'meun_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca()
    {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMod()
    {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEaca()
    {
        return $this->hasOne(EstudioAcademico::className(), ['eaca_id' => 'eaca_id']);
    }
}
