<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "pais".
 *
 * @property integer $pai_id
 * @property integer $cont_id
 * @property string $pai_nombre
 * @property string $pai_capital
 * @property string $pai_iso2
 * @property string $pai_iso3
 * @property string $pai_codigo_fono
 * @property string $pai_descripcion
 * @property string $pai_nacionalidad
 * @property string $pai_estado
 * @property string $pai_fecha_creacion
 * @property string $pai_fecha_modificacion
 * @property string $pai_estado_logico
 *
 * @property Persona[] $personas
 * @property Persona[] $personas0
 * @property Persona[] $personas1
 * @property Provincia[] $provincias
 */
class Pais extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'pais';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pai_estado', 'pai_estado_logico'], 'required'],
            [['pai_fecha_creacion', 'pai_fecha_modificacion'], 'safe'],
            [['pai_nombre', 'pai_capital', 'pai_nacionalidad'], 'string', 'max' => 50],
            [['pai_iso2'], 'string', 'max' => 2],
            [['pai_iso3'], 'string', 'max' => 3],
            [['pai_codigo_fono'], 'string', 'max' => 10],
            [['pai_descripcion'], 'string', 'max' => 250],
            [['pai_estado', 'pai_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pai_id' => 'Pai ID',
            'pai_nombre' => 'Pai Nombre',
            'pai_capital' => 'Pai Capital',
            'pai_iso2' => 'Pai Iso2',
            'pai_iso3' => 'Pai Iso3',
            'pai_codigo_fono' => 'Pai Codigo Fono',
            'pai_descripcion' => 'Pai Descripcion',
            'pai_nacionalidad' => 'Pai Nacionalidad',
            'pai_estado' => 'Pai Estado',
            'pai_fecha_creacion' => 'Pai Fecha Creacion',
            'pai_fecha_modificacion' => 'Pai Fecha Modificacion',
            'pai_estado_logico' => 'Pai Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas() {
        return $this->hasMany(Persona::className(), ['pai_id_nacimiento' => 'pai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas0() {
        return $this->hasMany(Persona::className(), ['pai_id_domicilio' => 'pai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas1() {
        return $this->hasMany(Persona::className(), ['pai_id_trabajo' => 'pai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvincias() {
        return $this->hasMany(Provincia::className(), ['pai_id' => 'pai_id']);
    }
    
    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $perid       
     * @return  
     */
    public function consultarCodigoArea($pai_id=57) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT 
                   pai_id as id,
                   CONCAT('+',pai_codigo_fono) as name
                FROM 
                   " . $con->dbname . ".pais  
                WHERE 
                   pai_id = :pai_id  AND
                   pai_estado = :estado AND
                   pai_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pai_id", $pai_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    public static function getAllPaises(){
        $con = Yii::$app->db;
        $sql = "SELECT
                    p.pai_id AS id,
                    p.pai_nombre AS name
                FROM
                    " . $con->dbname . ".pais AS p
                WHERE
                    p.pai_estado = 1 AND
                    p.pai_estado_logico = 1";
        $comando = $con->createCommand($sql);
        $result = $comando->queryAll();
        return $result;
    }

    function getAllPaisesGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search = "(p.pai_nombre like :search OR ";
            $str_search .= "p.pai_descripcion like :search) AND";
        }
        $sql = "SELECT
                    p.pai_id AS id,
                    p.pai_nombre AS Nombre,
                    p.pai_descripcion AS Descripcion,
                    p.pai_estado AS Estado
                FROM
                    pais as p
                WHERE
                    $str_search
                    p.pai_estado_logico = 1
                ORDER BY p.pai_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'pai_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Descripcion', 'Estado'],
                ],                
            ]);
            return $dataProvider;            
        }
        return $res;
    }

}
