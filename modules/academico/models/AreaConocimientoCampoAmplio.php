<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "area_conocimiento_campo_amplio".
 *
 * @property int $acca_id
 * @property string $acca_codigo
 * @property string $acca_nombre
 * @property string $acca_descripcion
 * @property int $acca_usuario_ingreso
 * @property int $acca_usuario_modifica
 * @property string $acca_estado
 * @property string $acca_fecha_creacion
 * @property string $acca_fecha_modificacion
 * @property string $acca_estado_logico
 *
 * @property SubareaConocimientoCampoEspecifico[] $subareaConocimientoCampoEspecificos
 */
class AreaConocimientoCampoAmplio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_conocimiento_campo_amplio';
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
            [['acca_nombre', 'acca_descripcion', 'acca_usuario_ingreso', 'acca_estado', 'acca_estado_logico'], 'required'],
            [['acca_usuario_ingreso', 'acca_usuario_modifica'], 'integer'],
            [['acca_fecha_creacion', 'acca_fecha_modificacion'], 'safe'],
            [['acca_codigo'], 'string', 'max' => 50],
            [['acca_nombre'], 'string', 'max' => 300],
            [['acca_descripcion'], 'string', 'max' => 500],
            [['acca_estado', 'acca_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acca_id' => 'Acca ID',
            'acca_codigo' => 'Acca Codigo',
            'acca_nombre' => 'Acca Nombre',
            'acca_descripcion' => 'Acca Descripcion',
            'acca_usuario_ingreso' => 'Acca Usuario Ingreso',
            'acca_usuario_modifica' => 'Acca Usuario Modifica',
            'acca_estado' => 'Acca Estado',
            'acca_fecha_creacion' => 'Acca Fecha Creacion',
            'acca_fecha_modificacion' => 'Acca Fecha Modificacion',
            'acca_estado_logico' => 'Acca Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubareaConocimientoCampoEspecificos()
    {
        return $this->hasMany(SubareaConocimientoCampoEspecifico::className(), ['acca_id' => 'acca_id']);
    }
}
