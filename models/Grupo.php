<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "grupo".
 *
 * @property integer $gru_id
 * @property integer $cseg_id
 * @property string $gru_nombre
 * @property string $gru_descripcion
 * @property string $gru_observacion
 * @property string $gru_estado
 * @property string $gru_fecha_creacion
 * @property string $gru_fecha_actualizacion
 * @property string $gru_estado_logico
 *
 * @property GrupObmo[] $grupObmos
 * @property GrupRol[] $grupRols
 */
class Grupo extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'grupo';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cseg_id', 'gru_estado', 'gru_estado_logico'], 'required'],
            [['cseg_id'], 'integer'],
            [['gru_fecha_creacion', 'gru_fecha_actualizacion'], 'safe'],
            [['gru_nombre'], 'string', 'max' => 250],
            [['gru_descripcion', 'gru_observacion'], 'string', 'max' => 500],
            [['gru_estado', 'gru_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'gru_id' => 'Gru ID',
            'cseg_id' => 'Cseg ID',
            'gru_nombre' => 'Gru Nombre',
            'gru_descripcion' => 'Gru Descripcion',
            'gru_observacion' => 'Gru Observacion',
            'gru_estado' => 'Gru Estado',
            'gru_fecha_creacion' => 'Gru Fecha Creacion',
            'gru_fecha_actualizacion' => 'Gru Fecha Actualizacion',
            'gru_estado_logico' => 'Gru Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupObmos() {
        return $this->hasMany(GrupObmo::className(), ['gru_id' => 'gru_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCseg()
    {
        return $this->hasOne(ConfiguracionSeguridad::className(), ['cseg_id' => 'cseg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupRols() {
        return $this->hasMany(GrupRol::className(), ['gru_id' => 'gru_id']);
    }

    /**
     * Function to get array 
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param   string  $username    
     * @return  mixed   $res        New array 
     */
    public function getMainGrupo($username, $all_groups=false) {
        $user = Usuario::findByUsername($username);
        $con = Yii::$app->db;
        $trans = $con->getTransaction();
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction();
        }
        $sql = "SELECT 
                    g.gru_id AS id,
                    p.per_cedula AS dni
                FROM 
                    usuario AS u 
                    INNER JOIN usua_grol_eper AS ug ON u.usu_id = ug.usu_id
                    INNER JOIN empresa_persona AS ep ON ug.eper_id = ep.eper_id
                    INNER JOIN empresa AS e ON ep.emp_id = e.emp_id
                    INNER JOIN grup_rol AS gr ON gr.grol_id = ug.grol_id 
                    INNER JOIN grupo AS g ON g.gru_id = gr.gru_id 
                    INNER JOIN persona AS p ON p.per_id = u.per_id 
                WHERE 
                    u.usu_user = :user AND 
                    ug.ugep_estado_logico=1 AND 
                    ug.ugep_estado=1 AND 
                    ep.eper_estado_logico=1 AND 
                    ep.eper_estado=1 AND
                    e.emp_estado_logico=1 AND 
                    e.emp_estado=1 AND
                    gr.grol_estado_logico=1 AND 
                    gr.grol_estado=1 AND 
                    p.per_estado_logico=1 AND 
                    p.per_estado=1 AND 
                    g.gru_estado = 1 AND 
                    g.gru_estado_logico=1 AND 
                    u.usu_estado=1 AND 
                    u.usu_estado_logico=1";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":user", $username, \PDO::PARAM_STR);
        if($all_groups){
            $result = $comando->queryAll();
            return $result;
        }
        $result = $comando->queryOne();
        return $result;
    }

    public function getAllGruposByUser($username) {
        $user = Usuario::findByUsername($username);
        $con = Yii::$app->db;
        $trans = $con->getTransaction();
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction();
        }
        $sql = "SELECT 
                    g.gru_id AS id
                FROM 
                    usuario AS u 
                    INNER JOIN usua_grol_eper AS ug ON u.usu_id = ug.usu_id
                    INNER JOIN empresa_persona AS ep ON ug.eper_id = ep.eper_id
                    INNER JOIN empresa AS e ON ep.emp_id = e.emp_id
                    INNER JOIN grup_rol AS gr ON gr.grol_id = ug.grol_id 
                    INNER JOIN grupo AS g ON g.gru_id = gr.gru_id 
                    INNER JOIN persona AS p ON p.per_id = u.per_id 
                WHERE 
                    u.usu_user = :user AND 
                    ug.ugep_estado_logico=1 AND 
                    ug.ugep_estado=1 AND 
                    ep.eper_estado_logico=1 AND 
                    ep.eper_estado=1 AND
                    e.emp_estado_logico=1 AND 
                    e.emp_estado=1 AND
                    gr.grol_estado_logico=1 AND 
                    gr.grol_estado=1 AND 
                    p.per_estado_logico=1 AND 
                    p.per_estado=1 AND 
                    g.gru_estado = 1 AND 
                    g.gru_estado_logico=1 AND 
                    u.usu_estado=1 AND 
                    u.usu_estado_logico=1";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":user", $username, \PDO::PARAM_STR);
        $result = $comando->queryAll();
        return $result;
    }
    
    /**
     * Function to get array 
     * @author  Byron Villacress <developer@uteg.edu.ec>
     * @param   string  $username    
     * @return  mixed   $res        New array 
     */
    public static function getAllGrupos() {
        $con = \Yii::$app->db;
        $sql = "SELECT gru_id Ids,gru_nombre Nombre FROM " . $con->dbname . ".grupo "
                . " WHERE gru_estado=1 AND gru_estado_logico=1;";
        $comando = $con->createCommand($sql);
        return $comando->queryAll();
    }
    
    function getAllGruposGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search  = "(g.gru_nombre like :search OR ";
            $str_search .= "g.gru_descripcion like :search OR ";
            $str_search .= "c.cseg_descripcion like :search) AND ";
        }
        $sql = "SELECT 
                    g.gru_id as id,
                    g.gru_nombre as Nombre,
                    c.cseg_descripcion as Seguridad,
                    c.cseg_id as SegID,
                    g.gru_descripcion as Descripcion,
                    p.tpas_descripcion as DescripcionPassword,
                    g.gru_estado as Estado
                FROM 
                    grupo as g 
                    INNER JOIN configuracion_seguridad as c ON g.cseg_id = c.cseg_id
                    INNER JOIN tipo_password as p ON c.tpas_id = p.tpas_id
                WHERE 
                    $str_search
                    -- g.gru_estado=1 AND
                    -- c.cseg_estado=1 AND
                    -- p.tpas_estado=1 AND
                    c.cseg_estado_logico=1 AND 
                    p.tpas_estado_logico=1 AND 
                    g.gru_estado_logico=1 
                ORDER BY g.gru_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'gru_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Seguridad', 'SegID', 'Estado', 'Descripcion', 'DescripcionPassword'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
    
}
