<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "tipo_institucion_aca".
 *
 * @property integer $tiac_id
 * @property string $tiac_nombre
 * @property string $tiac_descripcion
 * @property string $tiac_estado
 * @property string $tiac_fecha_creacion
 * @property string $tiac_fecha_modificacion
 * @property string $tiac_estado_logico
 */
class TipoInstitucionAca extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return \Yii::$app->db_academico->dbname . '.tipo_institucion_aca';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tiac_estado', 'tiac_estado_logico'], 'required'],
                [['tiac_fecha_creacion', 'tiac_fecha_modificacion'], 'safe'],
                [['tiac_nombre'], 'string', 'max' => 250],
                [['tiac_descripcion'], 'string', 'max' => 500],
                [['tiac_estado', 'tiac_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tiac_id' => 'Tiac ID',
            'tiac_nombre' => 'Tiac Nombre',
            'tiac_descripcion' => 'Tiac Descripcion',
            'tiac_estado' => 'Tiac Estado',
            'tiac_fecha_creacion' => 'Tiac Fecha Creacion',
            'tiac_fecha_modificacion' => 'Tiac Fecha Modificacion',
            'tiac_estado_logico' => 'Tiac Estado Logico',
        ];
    }

}
