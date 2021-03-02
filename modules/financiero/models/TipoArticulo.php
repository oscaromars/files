<?php

namespace app\modules\financiero\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "tipo_articulo".
 *
 * @property integer $tart_id
 * @property string $tart_cod
 * @property string $tart_nombre
 * @property string $tart_fecha
 * @property string $tart_estado
 * @property string $tart_usuario_ingreso
 * @property string $tart_usuario_modifica
 * @property string $tart_fecha_creacion
 * @property string $tart_fecha_modificacion
 * @property string $tart_estado_logico
 *
 */

class TipoArticulo extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tipo_articulo';
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
            [['tart_estado', 'tart_estado_logico'], 'required'],
            [['tart_fecha', 'tart_fecha_creacion', 'tart_fecha_actualizacion'], 'safe'],
            [['tart_cod'], 'string', 'max' => 3],
            [['tart_nombre'], 'string', 'max' => 50],            
            [['tart_estado', 'tart_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tart_id' => 'Tart ID',
            'tart_cod' => 'Tart Codigo',
            'tart_nombre' => 'Tart Nombre',
            'tart_fecha' => 'Tart Fecha',
            'tart_estado' => 'Tart Estado',
            'tart_fecha_creacion' => 'Tart Fecha Creacion',
            'tart_fecha_actualizacion' => 'Tart Fecha Actualizacion',
            'tart_estado_logico' => 'Tart Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    function getAllTatrGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(ta.tart_cod like :search OR ";
            $str_search .= "ta.tart_nombre like :search) AND ";
        }
        $sql = "SELECT 
                    ta.tart_id as id,
                    ta.tart_cod as Codigo,
                    ta.tart_nombre as Nombre,
                    ta.tart_fecha as Fecha,
                    ta.tart_estado as Estado
                FROM 
                    tipo_articulo as ta
                WHERE 
                    $str_search
                    -- ta.tart_estado=1 AND
                    ta.tart_estado_logico=1 
                ORDER BY ta.tart_id;";
        $comando = Yii::$app->db_financiero->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'tart_id',
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