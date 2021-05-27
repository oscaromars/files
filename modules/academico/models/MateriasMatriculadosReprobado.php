<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "materias_matriculados_reprobado".
 *
 * @property int $mmr_id
 * @property int $mre_id
 * @property int $asi_id
 * @property int $mmr_usuario_ingreso
 * @property int $mmr_usuario_modifica
 * @property string $mmr_estado
 * @property string $mmr_fecha_creacion
 * @property string $mmr_fecha_modificacion
 * @property string $mmr_estado_logico
 *
 * @property MatriculadosReprobado $mre
 */
class MateriasMatriculadosReprobado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'materias_matriculados_reprobado';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mre_id', 'asi_id', 'mmr_usuario_ingreso', 'mmr_estado', 'mmr_estado_logico'], 'required'],
            [['mre_id', 'asi_id', 'mmr_usuario_ingreso', 'mmr_usuario_modifica'], 'integer'],
            [['mmr_fecha_creacion', 'mmr_fecha_modificacion'], 'safe'],
            [['mmr_estado', 'mmr_estado_logico'], 'string', 'max' => 1],
            [['mre_id'], 'exist', 'skipOnError' => true, 'targetClass' => MatriculadosReprobado::className(), 'targetAttribute' => ['mre_id' => 'mre_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mmr_id' => 'Mmr ID',
            'mre_id' => 'Mre ID',
            'asi_id' => 'Asi ID',
            'mmr_usuario_ingreso' => 'Mmr Usuario Ingreso',
            'mmr_usuario_modifica' => 'Mmr Usuario Modifica',
            'mmr_estado' => 'Mmr Estado',
            'mmr_fecha_creacion' => 'Mmr Fecha Creacion',
            'mmr_fecha_modificacion' => 'Mmr Fecha Modificacion',
            'mmr_estado_logico' => 'Mmr Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMre()
    {
        return $this->hasOne(MatriculadosReprobado::className(), ['mre_id' => 'mre_id']);
    }
}
