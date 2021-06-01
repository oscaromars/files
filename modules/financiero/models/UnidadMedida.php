<?php

namespace app\modules\financiero\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "unidad_medida".
 *
 * @property integer $umed_id
 * @property string $umed_cod
 * @property string $umed_nombre
 * @property string $umed_medida
 * @property string $umed_fecha
 * @property string $umed_estado
 * @property string $umed_usuario_ingreso
 * @property string $umed_usuario_modifica
 * @property string $umed_fecha_creacion
 * @property string $umed_fecha_modificacion
 * @property string $umed_estado_logico
 *
 */

class UnidadMedida extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'unidad_medida';
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
            [['umed_estado', 'umed_estado_logico'], 'required'],
            [['umed_fecha', 'umed_fecha_creacion', 'umed_fecha_actualizacion'], 'safe'],
            [['umed_cod'], 'string', 'max' => 2],
            [['umed_nombre'], 'string', 'max' => 30],
            [['umed_medida'], 'string', 'max' => 20],
            [['umed_estado', 'umed_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'umed_id' => 'UnidadMedida ID',
            'umed_cod' => 'UnidadMedida Codigo',
            'umed_nombre' => 'UnidadMedida Nombre',
            'umed_medida' => 'UnidadMedida Medida',
            'umed_fecha' => 'UnidadMedida Fecha',
            'umed_estado' => 'UnidadMedida Estado',
            'umed_fecha_creacion' => 'UnidadMedida FechaCreacion',
            'umed_fecha_actualizacion' => 'UnidadMedida Fecha Actualizacion',
            'umed_estado_logico' => 'UnidadMedida Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    function getAllUnidadmedidasGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(um.umed_cod like :search OR ";
            $str_search .= "um.umed_nombre like :search) AND ";
        }
        $sql = "SELECT 
                    um.umed_id as id,
                    um.umed_cod as Codigo,
                    um.umed_nombre as Nombre,
                    um.umed_medida as Medida,
                    um.umed_estado as Estado
                FROM 
                    unidad_medida as um
                WHERE 
                    $str_search
                    -- um.umed_estado=1 AND
                    um.umed_estado_logico=1 
                ORDER BY um.umed_id;";
        $comando = Yii::$app->db_financiero->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'umed_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Codigo', 'Nombre', 'Medida', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}