<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use app\modules\academico\models\RegistroOnline;

/**
 * This is the model class for table "registro_online_item".
 *
 * @property integer $roi_id
 * @property integer $ron_id
 * @property string $roi_materia_cod
 * @property string $roi_materia_nombre
 * @property string $roi_creditos
 * @property string $roi_costo
 * @property string $roi_bloque
 * @property string $roi_hora
 * @property string $roi_estado
 * @property string $roi_fecha_creacion
 * @property string $roi_usuario_modifica
 * @property string $roi_fecha_modifcacion
 * @property string $roi_estado_logico
 * 
 */

class RegistroOnlineItem extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'registro_online_item';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ron_id','roi_estado','roi_estado_logico'],'required'],
            [['roi_fecha_creacion','roi_fecha_modificacion'], 'safe'],
            [['roi_bloque','roi_hora'], 'string', 'max' => 4],
            [['roi_estado_logico','roi_estado'], 'string', 'max' => 1],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
            /* [['pes_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionEstudiante::className(), 'targetAttribute' => ['pes_id' => 'pes_id']], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'roi_id' => 'Roi ID',
            'ron_id' => 'Ron ID',
            'roi_materia_cod' => 'Roi Materia Codigo',
            'roi_materia_nombre' => 'Roi Materia Nombre',
            'roi_creditos' => 'Roi Creditos',
            'roi_costo' => 'Roi Costo',
            'roi_bloque' => 'Bloque',
            'roi_hora' => 'Hora',
            'roi_estado' => 'Roi Estado',
            'roi_fecha_creacion' => 'Roi Fecha Creacion',
            'roi_usuario_modifica' => 'Roi Usuario Modifica',
            'roi_fecha_modifcacion' => 'Roi Fecha Modificacion',
            'roi_estado_logico' => 'Roi Estado Logico',
        ];
    }
}