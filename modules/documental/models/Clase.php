<?php

namespace app\modules\documental\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "clase".
 * 
 * @property integer $cla_id
 * @property string $cla_cod
 * @property string $cla_nombre
 * @property string $cla_estado 
 * @property integer $cla_usuario_ingreso
 * @property integer $cla_usuario_modifica
 * @property string $cla_fecha_creacion
 * @property string $cla_fecha_modificacion
 * @property string $cla_estado_logico
 *
 */

class Clase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'clase';
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
            [['cla_id', 'cla_cod', 'cla_nombre', 'cla_estado', 'cla_estado_logico'], 'required'],
            [['cla_fecha_creacion', 'cla_fecha_modificacion'], 'safe'],
            [['cla_cod'], 'string', 'max' => 5],
            [['cla_nombre'], 'string', 'max' => 200],
            [['cla_estado_logico', 'cla_estado'], 'string', 'max' => 1],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cla_id' => 'Clase ID',
            'cla_cod' => 'Clase Codigo',
            'cla_nombre' => 'Clase Nombre',
            'cla_estado' => 'Clase Estado',
            'cla_usuario_ingreso' => 'Clase Usuario Ingreso',
            'cla_usuario_modifica' => 'Clase Usuario Modifica',
            'cla_fecha_creacion' => 'Clase Fecha Creacion',
            'cla_fecha_modificacion' => 'Clase Fecha Modificacion',
            'cla_estado_logico' => 'Clase Estado Logico'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    public static function obtenerIdClasebyNombre($cod) {
        $con = \Yii::$app->db_documental;        
        $sql = "
                SELECT *
                FROM clase
                WHERE cla_cod='$cod'";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if(empty($resultData['cla_id'])){
            return NULL;
        } else if($resultData['cla_estado_logico']=='0') {
            return NULL;
        } else {
            return $resultData['cla_id'];
        }        
    }
}