<?php

namespace app\modules\bienestar\models;

use Yii;

/**
 * This is the model class for table "formulario_seccion".
 *
 * @property int $fsec_id
 * @property string $fsec_descripcion
 * @property string $fsec_fecha_creacion
 * @property string $fsec_fecha_modificacion
 * @property string $fsec_estado
 * @property string $fsec_estado_logico
 *
 * @property CriterioDetalle[] $criterioDetalles
 * @property FormularioSeccionCampo[] $formularioSeccionCampos
 */
class FormularioSeccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formulario_seccion';
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
            [['fsec_id', 'fsec_descripcion', 'fsec_estado', 'fsec_estado_logico'], 'required'],
            [['fsec_id'], 'integer'],
            [['fsec_fecha_creacion', 'fsec_fecha_modificacion'], 'safe'],
            [['fsec_descripcion'], 'string', 'max' => 45],
            [['fsec_estado', 'fsec_estado_logico'], 'string', 'max' => 1],
            [['fsec_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fsec_id' => 'Fsec ID',
            'fsec_descripcion' => 'Fsec Descripcion',
            'fsec_fecha_creacion' => 'Fsec Fecha Creacion',
            'fsec_fecha_modificacion' => 'Fsec Fecha Modificacion',
            'fsec_estado' => 'Fsec Estado',
            'fsec_estado_logico' => 'Fsec Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriterioDetalles()
    {
        return $this->hasMany(CriterioDetalle::className(), ['fsec_id' => 'fsec_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormularioSeccionCampos()
    {
        return $this->hasMany(FormularioSeccionCampo::className(), ['fsec_id' => 'fsec_id']);
    }
}
