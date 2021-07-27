<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "nivel_idioma".
 *
 * @property integer $nidi_id
 * @property string $nidi_descripcion
 * @property string $nidi_estado
 * @property string $nidi_fecha_creacion
 * @property string $nidi_fecha_modificacion
 * @property string $nidi_estado_logico
 *
 */
class NivelIdioma extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'nivel_idioma';
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
            [['nidi_estado', 'nidi_estado_logico'], 'required'],
            [['nidi_fecha_creacion', 'nidi_fecha_modificacion'], 'safe'],
            [['nidi_descripcion'], 'string', 'max' => 250],
            [['nidi_estado', 'nidi_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'nidi_id' => 'Nidi ID',
            'nidi_descripcion' => 'Nidi Descripcion',
            'nidi_estado' => 'Nidi Estado',
            'nidi_fecha_creacion' => 'Nidi Fecha Creacion',
            'nidi_fecha_modificacion' => 'Nidi Fecha Modificacion',
            'nidi_estado_logico' => 'Nidi Estado Logico',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNidi() {
        return $this->hasOne(NivelIdioma::className(), ['nidi_id' => 'nidi_id']);
    }
    

}
