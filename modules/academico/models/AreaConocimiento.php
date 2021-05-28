<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "area_conocimiento".
 *
 * @property integer $acon_id
 * @property string $acon_nombre
 * @property string $acon_descripcion
 * @property string $acon_usuario_ingreso
 * @property string $acon_usuario_modifica
 * @property string $acon_estado
 * @property string $acon_fecha_creacion
 * @property string $acon_fecha_modificacion
 * @property string $acon_estado_logico
 *
 */

class AreaConocimiento extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'area_conocimiento';
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
            [['acon_estado', 'acon_estado_logico'], 'required'],
            [['acon_fecha_creacion', 'acon_fecha_modificacion'], 'safe'],
            [['acon_nombre'], 'string', 'max' => 300],
            [['acon_descripcion'], 'string', 'max' => 500],
            [['acon_estado', 'acon_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'acon_id' => 'AreaConocimiento ID',
            'acon_nombre' => 'AreaConocimiento Nombre',
            'acon_descripcion' => 'AreaConocimiento Descripcion',
            'acon_estado' => 'AreaConocimiento Estado',
            'acon_fecha_creacion' => 'AreaConocimiento Fecha Creacion',
            'acon_fecha_modificacion' => 'AreaConocimiento Fecha Modificacion',
            'acon_estado_logico' => 'AreaConocimiento Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public static function areaConocimientobySubareaConocimiento($scon_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    acon.acon_id as id,
                    acon.acon_nombre as name
                FROM 
                " . $con->dbname . ".subarea_conocimiento as scon
                   INNER JOIN " . $con->dbname . ".area_conocimiento as acon on scon.acon_id=acon.acon_id
                WHERE
                    scon.scon_id=:scon_id AND
                    scon.scon_estado=:estado AND
                    scon.scon_estado_logico=:estado AND
                    acon.acon_estado=:estado AND
                    acon.acon_estado_logico=:estado";
                //ORDER BY acon.acon_id ASC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":scon_id", $scon_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public static function areaConocimientobyAsignatura($asi_id) {        
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    acon.acon_id,
                    acon.acon_nombre
                FROM 
                " . $con->dbname . ".subarea_conocimiento as scon
                   INNER JOIN " . $con->dbname . ".area_conocimiento as acon on scon.acon_id=acon.acon_id
                   INNER JOIN " . $con->dbname . ".asignatura as asi on scon.scon_id=asi.scon_id
                WHERE
                    asi.asi_id=:asi_id AND                    
                    scon.scon_estado=:estado AND
                    scon.scon_estado_logico=:estado AND
                    acon.acon_estado=:estado AND
                    acon.acon_estado_logico=:estado";
                //ORDER BY acon.acon_id ASC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    // function getAllDivisasGrid($search = NULL, $dataProvider = false){
    //     $iduser = Yii::$app->session->get('PB_iduser', FALSE);
    //     $search_cond = "%".$search."%";
    //     $str_search = "";
    //     if(isset($search)){
    //         $str_search  = "(di.acon_cod like :search OR ";
    //         $str_search .= "di.acon_nombre like :search) AND ";
    //     }
    //     $sql = "SELECT 
    //                 di.acon_id as id,
    //                 di.acon_cod as Codigo,
    //                 di.acon_nombre as Nombre,
    //                 di.acon_cotizacion as Cotizacion,
    //                 di.acon_estado as Estado
    //             FROM 
    //                 area_conocimiento as di
    //             WHERE 
    //                 $str_search
    //                 -- di.acon_estado=1 AND
    //                 di.acon_estado_logico=1 
    //             ORDER BY di.acon_id;";
    //     $comando = Yii::$app->db_financiero->createCommand($sql);
    //     if(isset($search)){
    //         $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
    //     }
    //     $res = $comando->queryAll();
    //     if($dataProvider){
    //         $dataProvider = new ArrayDataProvider([
    //             'key' => 'acon_id',
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