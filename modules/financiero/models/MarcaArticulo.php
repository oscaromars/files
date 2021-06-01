<?php

namespace app\modules\financiero\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "marca_articulo".
 *
 * @property integer $mart_id
 * @property string $mart_cod
 * @property string $mart_nombre
 * @property string $mart_fecha
 * @property string $mart_estado
 * @property string $mart_usuario_ingreso
 * @property string $mart_usuario_modifica
 * @property string $mart_fecha_creacion
 * @property string $mart_fecha_modificacion
 * @property string $mart_estado_logico
 *
 */

class MarcaArticulo extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'marca_articulo';
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
            [['mart_estado', 'mart_estado_logico'], 'required'],
            [['mart_fecha', 'mart_fecha_creacion', 'mart_fecha_actualizacion'], 'safe'],
            [['mart_cod'], 'string', 'max' => 3],
            [['mart_nombre'], 'string', 'max' => 60],
            [['mart_estado', 'mart_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mart_id' => 'MarcaArti ID',
            'mart_cod' => 'MarcaArti Codigo',
            'mart_nombre' => 'MarcaArti Nombre',
            'mart_fecha' => 'MarcaArti Fecha',
            'mart_estado' => 'MarcaArti Estado',
            'mart_fecha_creacion' => 'MarcaArti FechaCreacion',
            'mart_fecha_actualizacion' => 'MarcaArti Fecha Actualizacion',
            'mart_estado_logico' => 'MarcaArti Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    function getAllMarcaarticulosGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(ma.mart_cod like :search OR ";
            $str_search .= "ma.mart_nombre like :search) AND ";
        }
        $sql = "SELECT 
                    ma.mart_id as id,
                    ma.mart_cod as Codigo,
                    ma.mart_nombre as Nombre,
                    ma.mart_fecha as Fecha,
                    ma.mart_estado as Estado
                FROM 
                    marca_articulo as ma
                WHERE 
                    $str_search
                    -- ma.mart_estado=1 AND
                    ma.mart_estado_logico=1 
                ORDER BY ma.mart_id;";
        $comando = Yii::$app->db_financiero->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'mart_id',
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