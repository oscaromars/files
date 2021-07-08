<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dash".
 *
 * @property int $dash_id
 * @property string $dash_title
 * @property string $dash_detail
 * @property string $dash_link
 * @property string $dash_target
 * @property string $dash_estado
 * @property string $dash_fecha_creacion
 * @property string $dash_fecha_modificacion
 * @property string $dash_estado_logico
 */
class Dash extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dash';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dash_title', 'dash_detail', 'dash_estado', 'dash_estado_logico'], 'required'],
            [['dash_fecha_creacion', 'dash_fecha_modificacion'], 'safe'],
            [['dash_title'], 'string', 'max' => 300],
            [['dash_detail'], 'string', 'max' => 500],
            [['dash_link', 'dash_target'], 'string', 'max' => 250],
            [['dash_estado', 'dash_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dash_id' => 'Dash ID',
            'dash_title' => 'Dash Title',
            'dash_detail' => 'Dash Detail',
            'dash_link' => 'Dash Link',
            'dash_target' => 'Dash Target',
            'dash_estado' => 'Dash Estado',
            'dash_fecha_creacion' => 'Dash Fecha Creacion',
            'dash_fecha_modificacion' => 'Dash Fecha Modificacion',
            'dash_estado_logico' => 'Dash Estado Logico',
        ];
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findByCondition($condition) {
        return parent::findByCondition($condition);
    }
}
