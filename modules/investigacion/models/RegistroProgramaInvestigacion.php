<?php

namespace app\modules\investigacion\models;

use Yii;

/**
 * This is the model class for table "registro_programa_investigacion".
 *
 * @property int $rpin_id
 * @property string $rpin_nombre
 * @property string $rpin_descripcion
 * @property int $rpin_estado
 * @property string $rpin_fecha_creacion
 * @property int $rpin_usuario_ingreso
 * @property int $rpin_usuario_modifica
 * @property string $rpin_fecha_modificacion
 * @property int $rpin_estado_logico
 *
 * @property RegistroProyecto[] $registroProyectos
 */
class RegistroProgramaInvestigacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_programa_investigacion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_investigacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rpin_nombre', 'rpin_descripcion', 'rpin_estado', 'rpin_usuario_ingreso', 'rpin_estado_logico'], 'required'],
            [['rpin_estado', 'rpin_usuario_ingreso', 'rpin_usuario_modifica', 'rpin_estado_logico'], 'integer'],
            [['rpin_fecha_creacion', 'rpin_fecha_modificacion'], 'safe'],
            [['rpin_nombre', 'rpin_descripcion'], 'string', 'max' => 350],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpin_id' => 'Rpin ID',
            'rpin_nombre' => 'Rpin Nombre',
            'rpin_descripcion' => 'Rpin Descripcion',
            'rpin_estado' => 'Rpin Estado',
            'rpin_fecha_creacion' => 'Rpin Fecha Creacion',
            'rpin_usuario_ingreso' => 'Rpin Usuario Ingreso',
            'rpin_usuario_modifica' => 'Rpin Usuario Modifica',
            'rpin_fecha_modificacion' => 'Rpin Fecha Modificacion',
            'rpin_estado_logico' => 'Rpin Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroProyectos()
    {
        return $this->hasMany(RegistroProyecto::className(), ['rpin_id' => 'rpin_id']);
    }
}
