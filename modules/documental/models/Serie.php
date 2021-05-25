<?php

namespace app\modules\documental\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "serie".
 * 
 * @property integer $ser_id
 * @property integer $cla_id
 * @property string $ser_cod
 * @property string $ser_nombre
 * @property string $ser_estado
 * @property integer $ser_usuario_ingreso
 * @property integer $ser_usuario_modifica
 * @property string $ser_fecha_creacion
 * @property string $ser_fecha_modificacion
 * @property string $ser_estado_logico
 *
 */

class Serie extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'serie';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_documental');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ser_id', 'cla_id', 'ser_cod', 'ser_nombre', 'ser_estado', 'ser_estado_logico'], 'required'],
            [['ser_fecha_creacion', 'ser_fecha_modificacion'], 'safe'],
            [['ser_cod'], 'string', 'max' => 5],
            [['ser_nombre'], 'string', 'max' => 200],
            [['ser_estado_logico', 'ser_estado'], 'string', 'max' => 1],
            [['cla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clase::className(), 'targetAttribute' => ['cla_id' => 'cla_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ser_id' => 'Serie ID',
            'cla_id' => 'Clase ID',
            'ser_cod' => 'Serie Codigo',
            'ser_nombre' => 'Serie Nombre',
            'ser_estado' => 'Serie Estado',
            'ser_usuario_ingreso' => 'Serie Usuario Ingreso',
            'ser_usuario_modifica' => 'Serie Usuario Modifica',
            'ser_fecha_creacion' => 'Serie Fecha Creacion',
            'ser_fecha_modificacion' => 'Serie Fecha Modificacion',
            'ser_estado_logico' => 'Serie Estado Logico'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */    

    public static function obtenerIdSerieByCodCla($ser_cod, $cla_id) {
        $con = \Yii::$app->db_documental;
        $sql = "
                SELECT *
                FROM serie
                WHERE ser_cod='$ser_cod'
                AND cla_id=$cla_id";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if(empty($resultData['ser_id'])){
            return NULL;
        } else if($resultData['ser_estado_logico']=='0') {
            return NULL;
        } else {
            return $resultData['ser_id'];
        }
    }
}