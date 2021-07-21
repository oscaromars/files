<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "idioma".
 *
 * @property integer $idi_id
 * @property string $idi_nombre
 * @property string $idi_descripcion
 * @property string $idi_estado
 * @property string $idi_fecha_creacion
 * @property string $idi_fecha_modificacion
 * @property string $idi_estado_logico
 *
 */
class Idioma extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'idioma';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_general');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['idi_estado', 'idi_estado_logico'], 'required'],
            [['idi_fecha_creacion', 'idi_fecha_modificacion'], 'safe'],
            [['idi_nombre'], 'string', 'max' => 50],
            [['idi_descripcion'], 'string', 'max' => 250],
            [['idi_estado', 'idi_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idi_id' => 'Idi ID',
            'idi_nombre' => 'Idi Nombre',
            'idi_descripcion' => 'Idi Descripcion',
            'idi_estado' => 'Idi Estado',
            'idi_fecha_creacion' => 'Idi Fecha Creacion',
            'idi_fecha_modificacion' => 'Idi Fecha Modificacion',
            'idi_estado_logico' => 'Idi Estado Logico',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdi() {
        return $this->hasOne(Idioma::className(), ['idi_id' => 'idi_id']);
    }
    


}
