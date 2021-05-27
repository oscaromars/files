<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_password".
 *
 * @property integer $tpas_id
 * @property string $tpas_descripcion
 * @property string $tpas_validacion
 * @property string $tpas_observacion
 * @property string $tpas_estado
 * @property string $tpas_fecha_creacion
 * @property string $tpas_fecha_actualizacion
 * @property string $tpas_estado_logico
 *
 * @property ConfiguracionSeguridad[] $configuracionSeguridads
 */
class TipoPassword extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tipo_password';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tpas_estado', 'tpas_estado_logico'], 'required'],
            [['tpas_fecha_creacion', 'tpas_fecha_actualizacion'], 'safe'],
            [['tpas_descripcion'], 'string', 'max' => 50],
            [['tpas_validacion', 'tpas_observacion'], 'string', 'max' => 200],
            [['tpas_estado', 'tpas_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tpas_id' => 'Tpas ID',
            'tpas_descripcion' => 'Tpas Descripcion',
            'tpas_validacion' => 'Tpas Validacion',
            'tpas_observacion' => 'Tpas Observacion',
            'tpas_estado' => 'Tpas Estado',
            'tpas_fecha_creacion' => 'Tpas Fecha Creacion',
            'tpas_fecha_actualizacion' => 'Tpas Fecha Actualizacion',
            'tpas_estado_logico' => 'Tpas Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionSeguridads() {
        return $this->hasMany(ConfiguracionSeguridad::className(), ['tpas_id' => 'tpas_id']);
    }

    /**
     * @inheritdoc
     */

    /**
     * Function findIdentity
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

}
