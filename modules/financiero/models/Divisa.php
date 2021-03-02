<?php

namespace app\modules\financiero\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "divisa".
 *
 * @property integer $div_id
 * @property string $div_cod
 * @property string $div_nombre
 * @property string $div_cotizacion
 * @property string $div_fecha
 * @property string $div_estado
 * @property string $div_usuario_ingreso
 * @property string $div_usuario_modifica
 * @property string $div_fecha_creacion
 * @property string $div_fecha_modificacion
 * @property string $div_estado_logico
 *
 */

class Divisa extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'divisa';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_financiero');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['div_estado', 'div_estado_logico'], 'required'],
            [['div_fecha', 'div_fecha_creacion', 'div_fecha_actualizacion'], 'safe'],
            [['div_cod'], 'string', 'max' => 2],
            [['div_nombre'], 'string', 'max' => 30],
            // [['div_cotizacion'], 'float', 'min' => 0],
            [['div_cotizacion'], 'double'],
            // [['div_cotizacion'], 'numerical', 'intergerOnly' => false, 'min' => 0],
            [['div_estado', 'div_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'div_id' => 'Divisa ID',
            'div_cod' => 'Divisa Codigo',
            'div_nombre' => 'Divisa Nombre',
            'div_cotizacion' => 'Divisa Cotizacion',
            'div_fecha' => 'Divisa Fecha',
            'div_estado' => 'Divisa Estado',
            'div_fecha_creacion' => 'Divisa Fecha Creacion',
            'div_fecha_actualizacion' => 'Divisa Fecha Actualizacion',
            'div_estado_logico' => 'Divisa Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    function getAllDivisasGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(di.div_cod like :search OR ";
            $str_search .= "di.div_nombre like :search) AND ";
        }
        $sql = "SELECT 
                    di.div_id as id,
                    di.div_cod as Codigo,
                    di.div_nombre as Nombre,
                    di.div_cotizacion as Cotizacion,
                    di.div_estado as Estado
                FROM 
                    divisa as di
                WHERE 
                    $str_search
                    -- di.div_estado=1 AND
                    di.div_estado_logico=1 
                ORDER BY di.div_id;";
        $comando = Yii::$app->db_financiero->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'div_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Codigo', 'Nombre', 'Cotizacion', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}