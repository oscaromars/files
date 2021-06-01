<?php

namespace app\modules\financiero\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "linea_venta".
 *
 * @property integer $lven_id
 * @property string $lven_cod
 * @property string $lven_nombre
 * @property string $lven_fecha
 * @property string $lven_estado
 * @property string $lven_usuario_ingreso
 * @property string $lven_usuario_modifica
 * @property string $lven_fecha_creacion
 * @property string $lven_fecha_modificacion
 * @property string $lven_estado_logico
 *
 */

class LineaVenta extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'linea_venta';
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
            [['lven_estado', 'lven_estado_logico'], 'required'],
            [['lven_fecha', 'lven_fecha_creacion', 'lven_fecha_actualizacion'], 'safe'],
            [['lven_cod'], 'string', 'max' => 3],
            [['lven_nombre'], 'string', 'max' => 60],
            [['lven_estado', 'lven_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'lven_id' => 'LineaVenta ID',
            'lven_cod' => 'LineaVenta Codigo',
            'lven_nombre' => 'LineaVenta Nombre',
            'lven_fecha' => 'LineaVenta Fecha',
            'lven_estado' => 'LineaVenta Estado',
            'lven_fecha_creacion' => 'LineaVenta FechaCreacion',
            'lven_fecha_actualizacion' => 'LineaVenta Fecha Actualizacion',
            'lven_estado_logico' => 'LineaVenta Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    function getAllLineaventasGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(li.lven_cod like :search OR ";
            $str_search .= "li.lven_nombre like :search) AND ";
        }
        $sql = "SELECT 
                    li.lven_id as id,
                    li.lven_cod as Codigo,
                    li.lven_nombre as Nombre,
                    li.lven_fecha as Fecha,
                    li.lven_estado as Estado
                FROM 
                    linea_venta as li
                WHERE 
                    $str_search
                    -- li.lven_estado=1 AND
                    li.lven_estado_logico=1 
                ORDER BY li.lven_id;";
        $comando = Yii::$app->db_financiero->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'lven_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Codigo', 'Nombre', 'Fecha', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}