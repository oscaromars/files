<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_errores".
 *
 * @property integer $loge_id
 * @property string $nombre_modulo
 * @property string $titulo_error
 * @property string $mensaje_error_1
 * @property string $datos
 * @property string $loge_estado
 * @property string $loge_fecha_creacion
 * @property string $loge_fecha_actualizacion
 * @property string $loge_estado_logico
 *
 */


class Log_errores extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'log_errores';
    }

    /**
     * @inheritdoc
     */
    /*
    public function rules() {
        return [
            [['etn_estado', 'etn_estado_logico'], 'required'],
            [['etn_fecha_creacion', 'etn_fecha_actualizacion'], 'safe'],
            [['etn_nombre', 'etn_descripcion'], 'string', 'max' => 250],
            [['etn_observacion'], 'string', 'max' => 500],
            [['etn_estado', 'etn_estado_logico'], 'string', 'max' => 1],
        ];
    }
    */

    /**
     * @inheritdoc
     */
    /*
    public function attributeLabels() {
        return [
            'etn_id' => 'Etn ID',
            'etn_nombre' => 'Etn Nombre',
            'etn_descripcion' => 'Etn Descripcion',
            'etn_observacion' => 'Etn Observacion',
            'etn_estado' => 'Etn Estado',
            'etn_fecha_creacion' => 'Etn Fecha Creacion',
            'etn_fecha_actualizacion' => 'Etn Fecha Actualizacion',
            'etn_estado_logico' => 'Etn Estado Logico',
        ];
    }
    */

    /**
     * Function findIdentity
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
     * @param      
     * @return  
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }
}
