<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dash_item".
 *
 * @property int $dite_id
 * @property int $dash_id
 * @property string $dite_title
 * @property string $dite_detail
 * @property string $dite_link
 * @property string $dite_target
 * @property string $dite_ruta_banner
 * @property string $dite_estado
 * @property string $dite_fecha_creacion
 * @property string $dite_fecha_modificacion
 * @property string $dite_estado_logico
 *
 * @property Dash $dash
 */
class DashItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dash_item';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_asgard');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dash_id', 'dite_title', 'dite_detail', 'dite_estado', 'dite_estado_logico'], 'required'],
            [['dash_id'], 'integer'],
            [['dite_fecha_creacion', 'dite_fecha_modificacion'], 'safe'],
            [['dite_title'], 'string', 'max' => 300],
            [['dite_detail', 'dite_ruta_banner'], 'string', 'max' => 500],
            [['dite_link', 'dite_target'], 'string', 'max' => 250],
            [['dite_estado', 'dite_estado_logico'], 'string', 'max' => 1],
            [['dash_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dash::className(), 'targetAttribute' => ['dash_id' => 'dash_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dite_id' => 'Dite ID',
            'dash_id' => 'Dash ID',
            'dite_title' => 'Dite Title',
            'dite_detail' => 'Dite Detail',
            'dite_link' => 'Dite Link',
            'dite_target' => 'Dite Target',
            'dite_ruta_banner' => 'Dite Ruta Banner',
            'dite_estado' => 'Dite Estado',
            'dite_fecha_creacion' => 'Dite Fecha Creacion',
            'dite_fecha_modificacion' => 'Dite Fecha Modificacion',
            'dite_estado_logico' => 'Dite Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDash()
    {
        return $this->hasOne(Dash::className(), ['dash_id' => 'dash_id']);
    }
}
