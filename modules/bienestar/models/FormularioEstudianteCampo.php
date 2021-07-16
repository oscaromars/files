<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "formulario_estudiante_campo".
 *
 * @property int $feca_id
 * @property int $fest_id
 * @property int $fscam_id
 * @property string $feca_campo_valor
 * @property string $feca_fecha_creacion
 * @property string $feca_fecha_modificacion
 * @property string $feca_estado
 * @property string $feca_estado_logico
 */
class FormularioEstudianteCampo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formulario_estudiante_campo';
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
            [['feca_id', 'fest_id', 'fscam_id', 'feca_campo_valor', 'feca_estado', 'feca_estado_logico'], 'required'],
            [['feca_id', 'fest_id', 'fscam_id'], 'integer'],
            [['feca_fecha_creacion', 'feca_fecha_modificacion'], 'safe'],
            [['feca_campo_valor'], 'string', 'max' => 100],
            [['feca_estado', 'feca_estado_logico'], 'string', 'max' => 1],
            [['feca_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'feca_id' => 'Feca ID',
            'fest_id' => 'Fest ID',
            'fscam_id' => 'Fscam ID',
            'feca_campo_valor' => 'Feca Campo Valor',
            'feca_fecha_creacion' => 'Feca Fecha Creacion',
            'feca_fecha_modificacion' => 'Feca Fecha Modificacion',
            'feca_estado' => 'Feca Estado',
            'feca_estado_logico' => 'Feca Estado Logico',
        ];
    }
}
