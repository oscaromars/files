<?php

namespace app\modules\documental\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "subserie".
 * 
 * @property integer $sub_id
 * @property integer $ser_id
 * @property string $sub_cod
 * @property string $sub_cod_total
 * @property string $sub_nombre
 * @property string $sub_estado
 * @property integer $sub_usuario_ingreso
 * @property integer $sub_usuario_modifica
 * @property string $sub_fecha_creacion
 * @property string $sub_fecha_modificacion
 * @property string $sub_estado_logico
 *
 */

class SubSerie extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'subserie';
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
            [['sub_id', 'ser_id', 'sub_cod', 'sub_cod_total', 'sub_nombre', 'sub_estado', 'sub_estado_logico'], 'required'],
            [['sub_fecha_creacion', 'sub_fecha_modificacion'], 'safe'],
            [['sub_cod'], 'string', 'max' => 5],
            [['sub_cod_total'], 'string', 'max' => 20],
            [['sub_nombre'], 'string', 'max' => 200],
            [['sub_estado_logico', 'sub_estado'], 'string', 'max' => 1],
            [['ser_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubSerie::className(), 'targetAttribute' => ['ser_id' => 'ser_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sub_id' => 'SubSerie ID',
            'ser_id' => 'Serie ID',
            'sub_cod' => 'SubSerie Codigo',
            'sub_cod_total' => 'SubSerie Codigo Total',
            'sub_nombre' => 'SubSerie Nombre',
            'sub_estado' => 'SubSerie Estado',
            'sub_usuario_ingreso' => 'SubSerie Usuario Ingreso',
            'sub_usuario_modifica' => 'SubSerie Usuario Modifica',
            'sub_fecha_creacion' => 'SubSerie Fecha Creacion',
            'sub_fecha_modificacion' => 'SubSerie Fecha Modificacion',
            'sub_estado_logico' => 'SubSerie Estado Logico'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */    

    public static function obtenerIdSubSerieByNombreSer($sub_cod, $ser_id) {
        $con = \Yii::$app->db_documental;        
        $sql = "
                SELECT *
                FROM subserie
                WHERE sub_cod='$sub_cod'
                AND ser_id=$ser_id";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if(empty($resultData['sub_id'])){
            return NULL;
        } else if($resultData['sub_estado_logico']=='0') {
            return NULL;
        } else {
            return $resultData['sub_id'];
        }
    }
}
