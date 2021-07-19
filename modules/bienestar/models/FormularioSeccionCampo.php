<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "formulario_seccion_campo".
 *
 * @property int $fscam_id
 * @property int $fsec_id
 * @property string $fscam_nombre
 * @property string $fscam_fecha_creacion
 * @property string $fscam_fecha_modificacion
 * @property string $fscam_estado
 * @property string $fscam_estado_logico
 *
 * @property FormularioSeccion $fsec
 */
class FormularioSeccionCampo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formulario_seccion_campo';
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
            [['fscam_id', 'fsec_id', 'fscam_nombre', 'fscam_estado', 'fscam_estado_logico'], 'required'],
            [['fscam_id', 'fsec_id'], 'integer'],
            [['fscam_fecha_creacion', 'fscam_fecha_modificacion'], 'safe'],
            [['fscam_nombre'], 'string', 'max' => 80],
            [['fscam_estado', 'fscam_estado_logico'], 'string', 'max' => 1],
            [['fscam_id'], 'unique'],
            [['fsec_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormularioSeccion::className(), 'targetAttribute' => ['fsec_id' => 'fsec_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fscam_id' => 'Fscam ID',
            'fsec_id' => 'Fsec ID',
            'fscam_nombre' => 'Fscam Nombre',
            'fscam_fecha_creacion' => 'Fscam Fecha Creacion',
            'fscam_fecha_modificacion' => 'Fscam Fecha Modificacion',
            'fscam_estado' => 'Fscam Estado',
            'fscam_estado_logico' => 'Fscam Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsec()
    {
        return $this->hasOne(FormularioSeccion::className(), ['fsec_id' => 'fsec_id']);
    }
}
