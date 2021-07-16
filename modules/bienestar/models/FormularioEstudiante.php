<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "formulario_estudiante".
 *
 * @property int $fest_id
 * @property int $est_id
 * @property int $per_id
 * @property string $fest_fecha_creacion
 * @property string $fest_fecha_modificacion
 * @property string $fest_estado
 * @property string $fest_estado_logico
 *
 * @property DocumentosFormularioEstudiante[] $documentosFormularioEstudiantes
 * @property FormularioFamiliaresDiscapacitados[] $formularioFamiliaresDiscapacitados
 */
class FormularioEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formulario_estudiante';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_bienestar');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fest_id', 'est_id', 'per_id', 'fest_estado', 'fest_estado_logico'], 'required'],
            [['fest_id', 'est_id', 'per_id'], 'integer'],
            [['fest_fecha_creacion', 'fest_fecha_modificacion'], 'safe'],
            [['fest_estado', 'fest_estado_logico'], 'string', 'max' => 1],
            [['fest_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fest_id' => 'Fest ID',
            'est_id' => 'Est ID',
            'per_id' => 'Per ID',
            'fest_fecha_creacion' => 'Fest Fecha Creacion',
            'fest_fecha_modificacion' => 'Fest Fecha Modificacion',
            'fest_estado' => 'Fest Estado',
            'fest_estado_logico' => 'Fest Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentosFormularioEstudiantes()
    {
        return $this->hasMany(DocumentosFormularioEstudiante::className(), ['fest_id' => 'fest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormularioFamiliaresDiscapacitados()
    {
        return $this->hasMany(FormularioFamiliaresDiscapacitados::className(), ['fest_id' => 'fest_id']);
    }
}
