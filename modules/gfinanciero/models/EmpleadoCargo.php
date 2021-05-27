<?php

namespace app\modules\gfinanciero\models;

use Yii;

/**
 * This is the model class for table "empleado_cargo".
 *
 * @property int $ecarg_id
 * @property string $empl_codigo
 * @property int $carg_id
 * @property int $sdep_id
 * @property float|null $ecarg_sueldo
 * @property string|null $ecarg_fecha_inicio
 * @property string|null $ecarg_fecha_fin
 * @property string|null $ecarg_observacion
 * @property int|null $ecarg_usuario_ingreso
 * @property int|null $ecarg_usuario_modifica
 * @property string|null $ecarg_estado
 * @property string|null $ecarg_fecha_creacion
 * @property string|null $ecarg_fecha_modificacion
 * @property string|null $ecarg_estado_logico
 *
 * @property Cargos $carg
 * @property SubDepartamento $sdep
 */
class EmpleadoCargo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empleado_cargo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gfinanciero');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empl_codigo', 'carg_id', 'sdep_id'], 'required'],
            [['carg_id', 'sdep_id', 'ecarg_usuario_ingreso', 'ecarg_usuario_modifica'], 'integer'],
            [['ecarg_sueldo'], 'number'],
            [['ecarg_fecha_inicio', 'ecarg_fecha_fin', 'ecarg_fecha_creacion', 'ecarg_fecha_modificacion'], 'safe'],
            [['ecarg_observacion'], 'string'],
            [['empl_codigo'], 'string', 'max' => 10],
            [['ecarg_estado', 'ecarg_estado_logico'], 'string', 'max' => 1],
            [['carg_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cargo::className(), 'targetAttribute' => ['carg_id' => 'carg_id']],
            [['sdep_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubDepartamento::className(), 'targetAttribute' => ['sdep_id' => 'sdep_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ecarg_id' => 'Ecarg ID',
            'empl_codigo' => 'Empl Codigo',
            'carg_id' => 'Carg ID',
            'sdep_id' => 'Sdep ID',
            'ecarg_sueldo' => 'Ecarg Sueldo',
            'ecarg_fecha_inicio' => 'Ecarg Fecha Inicio',
            'ecarg_fecha_fin' => 'Ecarg Fecha Fin',
            'ecarg_observacion' => 'Ecarg Observacion',
            'ecarg_usuario_ingreso' => 'Ecarg Usuario Ingreso',
            'ecarg_usuario_modifica' => 'Ecarg Usuario Modifica',
            'ecarg_estado' => 'Ecarg Estado',
            'ecarg_fecha_creacion' => 'Ecarg Fecha Creacion',
            'ecarg_fecha_modificacion' => 'Ecarg Fecha Modificacion',
            'ecarg_estado_logico' => 'Ecarg Estado Logico',
        ];
    }

    /**
     * Gets query for [[Carg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarg()
    {
        return $this->hasOne(Cargo::className(), ['carg_id' => 'carg_id']);
    }

    /**
     * Gets query for [[Sdep]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdep()
    {
        return $this->hasOne(SubDepartamento::className(), ['sdep_id' => 'sdep_id']);
    }
}
