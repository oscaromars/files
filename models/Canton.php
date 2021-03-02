<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "canton".
 *
 * @property integer $can_id
 * @property integer $pro_id
 * @property string $can_nombre
 * @property string $can_descripcion
 * @property string $can_estado
 * @property string $can_fecha_creacion
 * @property string $can_fecha_modificacion
 * @property string $can_estado_logico
 *
 * @property Provincia $pro
 * @property Persona[] $personas
 * @property Persona[] $personas0
 */
class Canton extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'canton';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pro_id', 'can_estado', 'can_estado_logico'], 'required'],
            [['pro_id'], 'integer'],
            [['can_fecha_creacion', 'can_fecha_modificacion'], 'safe'],
            [['can_nombre'], 'string', 'max' => 250],
            [['can_descripcion'], 'string', 'max' => 500],
            [['can_estado', 'can_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'can_id' => 'Can ID',
            'pro_id' => 'Pro ID',
            'can_nombre' => 'Can Nombre',
            'can_descripcion' => 'Can Descripcion',
            'can_estado' => 'Can Estado',
            'can_fecha_creacion' => 'Can Fecha Creacion',
            'can_fecha_modificacion' => 'Can Fecha Modificacion',
            'can_estado_logico' => 'Can Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro() {
        return $this->hasOne(Provincia::className(), ['pro_id' => 'pro_id']);
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
    public static function cantonXProvincia($id_provincia) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT 
                   can.can_id AS id,
                   can.can_nombre AS value
                FROM 
                   " . $con->dbname . ".canton as can
                   INNER JOIN " . $con->dbname . ".provincia as pro on pro.pro_id=can.pro_id
                WHERE 
                   can.pro_id=:id_provincia AND
                   pro.pro_estado=:estado AND
                   pro.pro_estado_logico=:estado AND
                   can.can_estado=:estado AND
                   can.can_estado_logico=:estado                  
                ORDER BY can_nombre ASC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":id_provincia", $id_provincia, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    function getAllCantonesGrid($search = NULL, $dataProvider = false) {
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%" . $search . "%";
        $str_search = "";
        if (isset($search)) {
            $str_search = "(can.can_nombre like :search OR ";
            $str_search .= "pro.pro_nombre like :search) AND";
        }
        $sql = "SELECT
                    can.can_id AS id,
                    can.can_nombre AS Nombre,
                    pro.pro_nombre AS Provincia,
                    can.can_estado AS Estado
                FROM
                    canton as can
                    INNER JOIN provincia as pro on pro.pro_id=can.pro_id
                WHERE
                    $str_search
                    can.can_estado_logico = 1
                    AND pro.pro_estado_logico = 1
                ORDER BY can.can_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if ($dataProvider) {
            $dataProvider = new ArrayDataProvider([
                'key' => 'can_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Provincia', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    /**
     * Función 
     *
     * @author Giovanni Vergara
     * @param  $model
     */
    public static function NombrecantonXid($id_canton) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT                    
                   can_nombre as ciudad
                FROM 
                   " . $con->dbname . ".canton                   
                WHERE 
                   can_id=:id_canton AND                   
                   can_estado=:estado AND
                   can_estado_logico=:estado  ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":id_canton", $id_canton, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

}
