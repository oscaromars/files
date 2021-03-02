<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "conocimiento_canal".
 *
 * @property int $ccan_id
 * @property string $ccan_nombre
 * @property string $ccan_descripcion
 * @property string $ccan_conocimiento
 * @property string $ccan_canal
 * @property string $ccan_estado
 * @property string $ccan_fecha_creacion
 * @property string $ccan_fecha_modificacion
 * @property string $ccan_estado_logico
 *
 * @property Oportunidad[] $oportunidads
 * @property PersonaGestion[] $personaGestions
 */
class ConocimientoCanal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conocimiento_canal';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_crm');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ccan_nombre', 'ccan_descripcion', 'ccan_estado', 'ccan_estado_logico'], 'required'],
            [['ccan_fecha_creacion', 'ccan_fecha_modificacion'], 'safe'],
            [['ccan_nombre'], 'string', 'max' => 300],
            [['ccan_descripcion'], 'string', 'max' => 500],
            [['ccan_conocimiento', 'ccan_canal'], 'string', 'max' => 2],
            [['ccan_estado', 'ccan_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ccan_id' => 'Ccan ID',
            'ccan_nombre' => 'Ccan Nombre',
            'ccan_descripcion' => 'Ccan Descripcion',
            'ccan_conocimiento' => 'Ccan Conocimiento',
            'ccan_canal' => 'Ccan Canal',
            'ccan_estado' => 'Ccan Estado',
            'ccan_fecha_creacion' => 'Ccan Fecha Creacion',
            'ccan_fecha_modificacion' => 'Ccan Fecha Modificacion',
            'ccan_estado_logico' => 'Ccan Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOportunidads()
    {
        return $this->hasMany(Oportunidad::className(), ['ccan_id' => 'ccan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaGestions()
    {
        return $this->hasMany(PersonaGestion::className(), ['ccan_id' => 'ccan_id']);
    }
}
