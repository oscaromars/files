<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "formulario_familiares_discapacitados".
 *
 * @property int $fdis_id
 * @property int $fest_id
 * @property int $fcpo_id
 * @property string $fdis_cedula
 * @property string $fdis_nombres
 * @property string $fdis_apellidos
 * @property string $fdis_numero_conadis
 * @property string $fdis_tipo_discapacidad
 * @property double $fdis_porcentaje_discapacidad
 * @property string $fdis_descripcion_discapacidad
 * @property string $fdis_fecha_creacion
 * @property string $fdis_fecha_modificacion
 * @property string $fdis_estado
 * @property string $fdis_estado_logico
 *
 * @property FormularioEstudiante $fest
 * @property FormularioCondicionesPonderaciones $fcpo
 */
class FormularioFamiliaresDiscapacitados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formulario_familiares_discapacitados';
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
            [['fdis_id', 'fest_id', 'fcpo_id', 'fdis_cedula', 'fdis_nombres', 'fdis_apellidos', 'fdis_numero_conadis', 'fdis_tipo_discapacidad', 'fdis_porcentaje_discapacidad', 'fdis_descripcion_discapacidad', 'fdis_estado', 'fdis_estado_logico'], 'required'],
            [['fdis_id', 'fest_id', 'fcpo_id'], 'integer'],
            [['fdis_porcentaje_discapacidad'], 'number'],
            [['fdis_fecha_creacion', 'fdis_fecha_modificacion'], 'safe'],
            [['fdis_cedula'], 'string', 'max' => 10],
            [['fdis_nombres', 'fdis_apellidos'], 'string', 'max' => 80],
            [['fdis_numero_conadis', 'fdis_tipo_discapacidad'], 'string', 'max' => 45],
            [['fdis_descripcion_discapacidad'], 'string', 'max' => 200],
            [['fdis_estado', 'fdis_estado_logico'], 'string', 'max' => 1],
            [['fdis_id'], 'unique'],
            [['fest_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormularioEstudiante::className(), 'targetAttribute' => ['fest_id' => 'fest_id']],
            [['fcpo_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormularioCondicionesPonderaciones::className(), 'targetAttribute' => ['fcpo_id' => 'fcpo_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fdis_id' => 'Fdis ID',
            'fest_id' => 'Fest ID',
            'fcpo_id' => 'Fcpo ID',
            'fdis_cedula' => 'Fdis Cedula',
            'fdis_nombres' => 'Fdis Nombres',
            'fdis_apellidos' => 'Fdis Apellidos',
            'fdis_numero_conadis' => 'Fdis Numero Conadis',
            'fdis_tipo_discapacidad' => 'Fdis Tipo Discapacidad',
            'fdis_porcentaje_discapacidad' => 'Fdis Porcentaje Discapacidad',
            'fdis_descripcion_discapacidad' => 'Fdis Descripcion Discapacidad',
            'fdis_fecha_creacion' => 'Fdis Fecha Creacion',
            'fdis_fecha_modificacion' => 'Fdis Fecha Modificacion',
            'fdis_estado' => 'Fdis Estado',
            'fdis_estado_logico' => 'Fdis Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFest()
    {
        return $this->hasOne(FormularioEstudiante::className(), ['fest_id' => 'fest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFcpo()
    {
        return $this->hasOne(FormularioCondicionesPonderaciones::className(), ['fcpo_id' => 'fcpo_id']);
    }
}
