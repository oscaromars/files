<?php

namespace app\modules\investigacion\models;

use Yii;

/**
 * This is the model class for table "registro_financiamiento".
 *
 * @property int $rfin_id
 * @property string $rfin_nombre
 * @property string $rfin_descripcion
 * @property int $rfin_estado
 * @property string $rfin_fecha_creacion
 * @property int $rfin_usuario_ingreso
 * @property int $rfin_usuario_modifica
 * @property string $rfin_fecha_modificacion
 * @property int $rfin_estado_logico
 *
 * @property RegistroProyecto[] $registroProyectos
 */
class RegistroFinanciamiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_financiamiento';
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
            [['rfin_nombre', 'rfin_descripcion', 'rfin_estado', 'rfin_usuario_ingreso', 'rfin_estado_logico'], 'required'],
            [['rfin_estado', 'rfin_usuario_ingreso', 'rfin_usuario_modifica', 'rfin_estado_logico'], 'integer'],
            [['rfin_fecha_creacion', 'rfin_fecha_modificacion'], 'safe'],
            [['rfin_nombre', 'rfin_descripcion'], 'string', 'max' => 350],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rfin_id' => 'Rfin ID',
            'rfin_nombre' => 'Rfin Nombre',
            'rfin_descripcion' => 'Rfin Descripcion',
            'rfin_estado' => 'Rfin Estado',
            'rfin_fecha_creacion' => 'Rfin Fecha Creacion',
            'rfin_usuario_ingreso' => 'Rfin Usuario Ingreso',
            'rfin_usuario_modifica' => 'Rfin Usuario Modifica',
            'rfin_fecha_modificacion' => 'Rfin Fecha Modificacion',
            'rfin_estado_logico' => 'Rfin Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroProyectos()
    {
        return $this->hasMany(RegistroProyecto::className(), ['rfin_id' => 'rfin_id']);
    }
}
