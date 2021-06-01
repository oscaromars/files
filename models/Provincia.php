<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "provincia".
 *
 * @property integer $pro_id
 * @property integer $pai_id
 * @property string $pro_nombre
 * @property string $pro_capital
 * @property string $pro_descripcion
 * @property string $pro_estado
 * @property string $pro_fecha_creacion
 * @property string $pro_fecha_modificacion
 * @property string $pro_estado_logico
 *
 * @property Pais $pai
 * @property Persona[] $personas
 * @property Persona[] $personas0
 */
class Provincia extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'provincia';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pai_id', 'pro_estado', 'pro_estado_logico'], 'required'],
            [['pai_id'], 'integer'],
            [['pro_fecha_creacion', 'pro_fecha_modificacion'], 'safe'],
            [['pro_nombre'], 'string', 'max' => 250],
            [['pro_capital'], 'string', 'max' => 250],
            [['pro_descripcion'], 'string', 'max' => 500],
            [['pro_estado', 'pro_estado_logico'], 'string', 'max' => 1],
            [['pai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::className(), 'targetAttribute' => ['pai_id' => 'pai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pro_id' => 'Pro ID',
            'pai_id' => 'Pai ID',
            'pro_nombre' => 'Pro Nombre',
            'pro_capital' => 'Pro Capital',
            'pro_descripcion' => 'Pro Descripcion',
            'pro_estado' => 'Pro Estado',
            'pro_fecha_creacion' => 'Pro Fecha Creacion',
            'pro_fecha_modificacion' => 'Pro Fecha Modificacion',
            'pro_estado_logico' => 'Pro Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPai() {
        return $this->hasOne(Pais::className(), ['pai_id' => 'pai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas() {
        return $this->hasMany(Persona::className(), ['can_id_domicilio' => 'can_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas0() {
        return $this->hasMany(Persona::className(), ['can_id_trabajo' => 'can_id']);
    }

    /**
     * Función 
     *
     * @author Diana López
     * @param  $model
     */
    public static function provinciaXPais($id_pai) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT 
                   pro.pro_id AS id,
                   pro.pro_nombre AS value
                FROM 
                   " . $con->dbname . ".provincia as pro
                   INNER JOIN " . $con->dbname . ".pais as pai on pai.pai_id=pro.pai_id
                WHERE 
                   pro.pai_id=:id_pais AND
                   pai.pai_estado=:estado AND
                   pai.pai_estado_logico=:estado AND
                   pro.pro_estado=:estado AND
                   pro.pro_estado_logico=:estado                  
                ORDER BY pro_nombre ASC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":id_pais", $id_pai, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public static function provinciabyPais($pai_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT 
                   pro.pro_id AS id,
                   pro.pro_nombre AS name
                FROM 
                   " . $con->dbname . ".provincia as pro
                   INNER JOIN " . $con->dbname . ".pais as pai on pai.pai_id=pro.pai_id
                WHERE 
                   pro.pai_id=:pai_id AND
                   pai.pai_estado=:estado AND
                   pai.pai_estado_logico=:estado AND
                   pro.pro_estado=:estado AND
                   pro.pro_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pai_id", $pai_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    function getAllProvinciasGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search = "(pro.pro_nombre like :search OR ";
            $str_search .= "pai.pai_nombre like :search) AND";
        }
        $sql = "SELECT
                    pro.pro_id AS id,
                    pro.pro_nombre AS Nombre,
                    pai.pai_nombre AS Pais,
                    pro.pro_estado AS Estado
                FROM
                    provincia as pro
                    INNER JOIN pais as pai on pai.pai_id=pro.pai_id
                WHERE
                    $str_search
                    pro.pro_estado_logico = 1
                    AND pai.pai_estado_logico = 1
                ORDER BY pro.pro_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'pro_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Pais', 'Estado'],
                ],                
            ]);
            return $dataProvider;            
        }
        return $res;
    }

}
