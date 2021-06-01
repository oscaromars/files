<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "rol".
 *
 * @property integer $rol_id
 * @property string $rol_nombre
 * @property string $rol_descripcion
 * @property string $rol_estado
 * @property string $rol_fecha_creacion
 * @property string $rol_fecha_actualizacion
 * @property string $rol_estado_logico
 *
 * @property GrupRol[] $grupRols
 */
class Rol extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'rol';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rol_estado', 'rol_estado_logico'], 'required'],
            [['rol_fecha_creacion', 'rol_fecha_actualizacion'], 'safe'],
            [['rol_nombre'], 'string', 'max' => 50],
            [['rol_descripcion'], 'string', 'max' => 45],
            [['rol_estado', 'rol_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rol_id' => 'Rol ID',
            'rol_nombre' => 'Rol Nombre',
            'rol_descripcion' => 'Rol Descripcion',
            'rol_estado' => 'Rol Estado',
            'rol_fecha_creacion' => 'Rol Fecha Creacion',
            'rol_fecha_actualizacion' => 'Rol Fecha Actualizacion',
            'rol_estado_logico' => 'Rol Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupRols() {
        return $this->hasMany(GrupRol::className(), ['rol_id' => 'rol_id']);
    }
    
    public function getRolsByUser($id_user, $id_empresa){
        $sql = "SELECT 
                    r.rol_nombre AS rol
                FROM 
                    usuario AS u 
                    INNER JOIN usua_grol_eper AS ug ON u.usu_id = ug.usu_id
                    INNER JOIN empresa_persona AS ep ON ug.eper_id = ep.eper_id
                    INNER JOIN empresa AS e ON ep.emp_id = e.emp_id
                    INNER JOIN grupo_rol AS gr ON ug.grol_id = gr.grol_id
                    INNER JOIN rol AS r ON gr.rol_id = r.rol_id
                    INNER JOIN grupo AS g ON gr.gru_id = g.gru_id
                WHERE 
                    u.usu_id=:usu_id AND
                    e.emp_id=:emp_id AND
                    gr.grol_estado_logico=1 AND 
                    gr.grol_estado=1 AND 
                    ug.ugep_estado_logico=1 AND 
                    ug.ugep_estado=1 AND 
                    u.usu_estado_logico=1 AND 
                    u.usu_estado=1 AND 
                    ep.eper_estado_logico=1 AND 
                    ep.eper_estado=1 AND 
                    e.emp_estado=1 AND 
                    e.emp_estado_logico=1 AND
                    r.rol_estado=1 AND 
                    r.rol_estado_logico=1 AND
                    g.gru_estado=1 AND 
                    g.gru_estado_logico=1 
                ORDER BY rol ASC";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":usu_id", $id_user, \PDO::PARAM_INT);
        $comando->bindParam(":emp_id", $id_empresa, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
    
    public function getMainRol($username){
        $sql = "SELECT 
                    r.rol_id AS id,
                    r.rol_nombre AS rol
                FROM 
                    usuario AS u 
                    INNER JOIN usua_grol_eper AS ug ON u.usu_id = ug.usu_id
                    INNER JOIN empresa_persona AS ep ON ug.eper_id = ep.eper_id
                    INNER JOIN empresa AS e ON ep.emp_id = e.emp_id
                    INNER JOIN grup_rol AS gr ON ug.grol_id = gr.grol_id
                    INNER JOIN rol AS r ON gr.rol_id = r.rol_id
                    INNER JOIN grupo AS g ON gr.gru_id = g.gru_id
                    INNER JOIN persona AS p ON p.per_id = u.per_id 
                WHERE 
                    u.usu_user = :user AND 
                    p.per_estado_logico=1 AND
                    p.per_estado=1 AND
                    gr.grol_estado_logico=1 AND 
                    gr.grol_estado=1 AND 
                    ug.ugep_estado_logico=1 AND 
                    ug.ugep_estado=1 AND 
                    u.usu_estado_logico=1 AND 
                    u.usu_estado=1 AND 
                    ep.eper_estado_logico=1 AND 
                    ep.eper_estado=1 AND 
                    e.emp_estado=1 AND 
                    e.emp_estado_logico=1 AND
                    r.rol_estado=1 AND 
                    r.rol_estado_logico=1 AND
                    g.gru_estado=1 AND 
                    g.gru_estado_logico=1 
                ORDER BY r.rol_id ASC";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":user", $username, \PDO::PARAM_STR);
        return $comando->queryOne();
    }
    
    public static function getAllRoles(){
        $con = Yii::$app->db;
        $sql = "SELECT 
                    g.rol_id AS id,
                    g.rol_nombre AS name
                FROM 
                    " . $con->dbname . ".rol AS g  
                WHERE 
                    g.rol_estado = 1 AND 
                    g.rol_estado_logico=1";
        $comando = $con->createCommand($sql);
        $result = $comando->queryAll();
        return $result;
    }
    
    function getAllRolesGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(r.rol_nombre like :search OR ";
            $str_search .= "r.rol_descripcion like :search) AND ";
        }
        $sql = "SELECT 
                    r.rol_id as id,
                    r.rol_nombre as Nombre,
                    r.rol_descripcion as Descripcion,
                    r.rol_estado as Estado
                FROM 
                    rol as r 
                WHERE 
                    $str_search
                    -- r.rol_estado=1 AND
                    r.rol_estado_logico=1 
                ORDER BY r.rol_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'rol_id',
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
