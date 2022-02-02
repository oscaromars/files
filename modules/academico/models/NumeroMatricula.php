<?php

namespace app\models\academico\models;

use Yii;

/**
 * This is the model class for table "numero_matricula".
 *
 * @property int $nmat_id
 * @property int $nmat_codigo
 * @property string $nmat_descripcion
 * @property string $nmat_anio
 * @property string $nmat_numero
 * @property int $nmat_usuario_ingreso
 * @property int $nmat_usuario_modifica
 * @property string $nmat_estado
 * @property string $nmat_fecha_creacion
 * @property string $nmat_fecha_modificacion
 * @property string $nmat_estado_logico
 */
class NumeroMatricula extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'numero_matricula';
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
            [['nmat_codigo', 'nmat_descripcion', 'nmat_anio', 'nmat_estado', 'nmat_estado_logico'], 'required'],
            [['nmat_codigo', 'nmat_usuario_ingreso', 'nmat_usuario_modifica'], 'integer'],
            [['nmat_fecha_creacion', 'nmat_fecha_modificacion'], 'safe'],
            [['nmat_descripcion'], 'string', 'max' => 300],
            [['nmat_anio'], 'string', 'max' => 4],
            [['nmat_numero'], 'string', 'max' => 10],
            [['nmat_estado', 'nmat_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nmat_id' => 'Nmat ID',
            'nmat_codigo' => 'Nmat Codigo',
            'nmat_descripcion' => 'Nmat Descripcion',
            'nmat_anio' => 'Nmat Anio',
            'nmat_numero' => 'Nmat Numero',
            'nmat_usuario_ingreso' => 'Nmat Usuario Ingreso',
            'nmat_usuario_modifica' => 'Nmat Usuario Modifica',
            'nmat_estado' => 'Nmat Estado',
            'nmat_fecha_creacion' => 'Nmat Fecha Creacion',
            'nmat_fecha_modificacion' => 'Nmat Fecha Modificacion',
            'nmat_estado_logico' => 'Nmat Estado Logico',
        ];
    }
}
