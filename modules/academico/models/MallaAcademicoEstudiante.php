<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "malla_academico_estudiante".
 *
 * @property int $maes_id
 * @property int $per_id
 * @property int $made_id
 * @property int $maca_id
 * @property int $asi_id
 * @property int $maes_usuario_ingreso
 * @property int $maes_usuario_modifica
 * @property string $maes_fecha_creacion
 * @property string $maes_fecha_modificacion
 * @property string $maes_estado
 * @property string $maes_estado_logico
 */
class MallaAcademicoEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'malla_academico_estudiante';
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
            [['per_id', 'made_id', 'maca_id', 'asi_id', 'maes_usuario_ingreso', 'maes_usuario_modifica'], 'integer'],
            [['maes_fecha_creacion', 'maes_fecha_modificacion'], 'safe'],
            [['maes_estado', 'maes_estado_logico'], 'required'],
            [['maes_estado', 'maes_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'maes_id' => 'Maes ID',
            'per_id' => 'Per ID',
            'made_id' => 'Made ID',
            'maca_id' => 'Maca ID',
            'asi_id' => 'Asi ID',
            'maes_usuario_ingreso' => 'Maes Usuario Ingreso',
            'maes_usuario_modifica' => 'Maes Usuario Modifica',
            'maes_fecha_creacion' => 'Maes Fecha Creacion',
            'maes_fecha_modificacion' => 'Maes Fecha Modificacion',
            'maes_estado' => 'Maes Estado',
            'maes_estado_logico' => 'Maes Estado Logico',
        ];
    }
}
