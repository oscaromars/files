<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "subarea_conocimiento".
 *
 * @property integer $scon_id
 * @property string $acon_id
 * @property string $scon_nombre
 * @property string $scon_descripcion
 * @property string $scon_usuario_ingreso
 * @property string $scon_usuario_modifica
 * @property string $scon_estado
 * @property string $scon_fecha_creacion
 * @property string $scon_fecha_modificacion
 * @property string $scon_estado_logico
 *
 */

class SubareaConocimiento extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'subarea_conocimiento';
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
            [['acon_id','scon_estado', 'scon_estado_logico'], 'required'],
            [['acon_id'], 'integer'],
            [['scon_fecha_creacion', 'scon_fecha_modificacion'], 'safe'],
            [['scon_nombre'], 'string', 'max' => 300],
            [['scon_descripcion'], 'string', 'max' => 500],
            [['scon_estado', 'scon_estado_logico'], 'string', 'max' => 1],
            [['acon_id'], 'exist', 'skipOnError' => true, 'targetClass' => AreaConocimiento::className(), 'targetAttribute' => ['acon_id' => 'acon_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scon_id' => 'SubareaConocimiento ID',
            'acon_id' => 'AreaConocimiento ID',
            'scon_nombre' => 'SubareaConocimiento Nombre',
            'scon_descripcion' => 'SubareaConocimiento Descripcion',            
            'scon_estado' => 'SubareaConocimiento Estado',
            'scon_fecha_creacion' => 'SubareaConocimiento Fecha Creacion',
            'scon_fecha_modificacion' => 'SubareaConocimiento Fecha Modificacion',
            'scon_estado_logico' => 'SubareaConocimiento Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    // function getAllDivisasGrid($search = NULL, $dataProvider = false){
    //     $iduser = Yii::$app->session->get('PB_iduser', FALSE);
    //     $search_cond = "%".$search."%";
    //     $str_search = "";
    //     if(isset($search)){
    //         $str_search  = "(di.scon_cod like :search OR ";
    //         $str_search .= "di.scon_nombre like :search) AND ";
    //     }
    //     $sql = "SELECT 
    //                 di.scon_id as id,
    //                 di.scon_cod as Codigo,
    //                 di.scon_nombre as Nombre,
    //                 di.scon_cotizacion as Cotizacion,
    //                 di.scon_estado as Estado
    //             FROM 
    //                 subarea_conocimiento as di
    //             WHERE 
    //                 $str_search
    //                 -- di.scon_estado=1 AND
    //                 di.scon_estado_logico=1 
    //             ORDER BY di.scon_id;";
    //     $comando = Yii::$app->db_financiero->createCommand($sql);
    //     if(isset($search)){
    //         $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
    //     }
    //     $res = $comando->queryAll();
    //     if($dataProvider){
    //         $dataProvider = new ArrayDataProvider([
    //             'key' => 'scon_id',
    //             'allModels' => $res,
    //             'pagination' => [
    //                 'pageSize' => Yii::$app->params["pageSize"],
    //             ],
    //             'sort' => [
    //                 'attributes' => ['Codigo', 'Nombre', 'Cotizacion', 'Estado'],
    //             ],
    //         ]);
    //         return $dataProvider;
    //     }
    //     return $res;
    // }
}