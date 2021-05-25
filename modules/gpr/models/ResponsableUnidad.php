<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "responsable_unidad".
 *
 * @property int $runi_id
 * @property int $ugpr_id
 * @property int $usu_id
 * @property int $emp_id
 * @property int $niv_id
 * @property string|null $runi_isadmin
 * @property int $runi_usuario_ingreso
 * @property int|null $runi_usuario_modifica
 * @property string $runi_estado
 * @property string $runi_fecha_creacion
 * @property string|null $runi_fecha_modificacion
 * @property string $runi_estado_logico
 *
 * @property UnidadGpr $ugpr
 * @property Nivel $niv
 */
class ResponsableUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responsable_unidad';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gpr');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ugpr_id', 'usu_id', 'emp_id', 'niv_id', 'runi_usuario_ingreso', 'runi_estado', 'runi_estado_logico'], 'required'],
            [['ugpr_id', 'usu_id', 'emp_id', 'niv_id', 'runi_usuario_ingreso', 'runi_usuario_modifica'], 'integer'],
            [['runi_fecha_creacion', 'runi_fecha_modificacion'], 'safe'],
            [['runi_isadmin', 'runi_estado', 'runi_estado_logico'], 'string', 'max' => 1],
            [['ugpr_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadGpr::className(), 'targetAttribute' => ['ugpr_id' => 'ugpr_id']],
            [['niv_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nivel::className(), 'targetAttribute' => ['niv_id' => 'niv_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'runi_id' => 'Runi ID',
            'ugpr_id' => 'Ugpr ID',
            'usu_id' => 'Usu ID',
            'emp_id' => 'Emp ID',
            'niv_id' => 'Niv ID',
            'runi_isadmin' => 'Runi Isadmin',
            'runi_usuario_ingreso' => 'Runi Usuario Ingreso',
            'runi_usuario_modifica' => 'Runi Usuario Modifica',
            'runi_estado' => 'Runi Estado',
            'runi_fecha_creacion' => 'Runi Fecha Creacion',
            'runi_fecha_modificacion' => 'Runi Fecha Modificacion',
            'runi_estado_logico' => 'Runi Estado Logico',
        ];
    }

    /**
     * Gets query for [[Ugpr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUgpr()
    {
        return $this->hasOne(UnidadGpr::className(), ['ugpr_id' => 'ugpr_id']);
    }

    /**
     * Gets query for [[Niv]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNiv()
    {
        return $this->hasOne(Nivel::className(), ['niv_id' => 'niv_id']);
    }

    public function getAllResponsableGprGrid($search = NULL, $empresa = NULL, $unidad = NULL, $nivel = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search .= "(p.per_pri_nombre like :search OR ";
            $str_search .= "p.per_pri_apellido like :search) AND ";
        }
        if(isset($nivel) && $nivel > 0){
            $str_search .= "n.niv_id = :nivel AND ";
        }
        if(isset($empresa) && $empresa > 0){
            $str_search .= "e.emp_id = :empresa AND ";
        }
        if(isset($unidad) && $unidad > 0){
            $str_search .= "u.ugpr_id = :unidad AND ";
        }
        $sql = "SELECT 
                    ru.runi_id as id,
                    CONCAT(p.per_pri_nombre, ' ', p.per_pri_apellido) as Nombre,
                    e.emp_nombre_comercial as Empresa,
                    u.ugpr_nombre as Unidad,
                    n.niv_nombre as Nivel,
                    ru.runi_isadmin as IsAdmin,
                    ru.runi_estado as Estado
                FROM 
                    ".$con->dbname.".responsable_unidad as ru
                    INNER JOIN ".$con->dbname.".unidad_gpr as u ON u.ugpr_id = ru.ugpr_id
                    INNER JOIN ".$con->dbname.".nivel as n ON n.niv_id = ru.niv_id
                    INNER JOIN ".$con2->dbname.".empresa as e ON e.emp_id = ru.emp_id
                    INNER JOIN ".$con2->dbname.".usuario as us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona as p ON p.per_id = us.per_id
                WHERE 
                    $str_search
                    n.niv_estado_logico=1 AND
                    u.ugpr_estado_logico=1 AND
                    e.emp_estado_logico=1 AND
                    us.usu_estado_logico=1 AND
                    ru.runi_estado_logico=1 
                ORDER BY ru.runi_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($empresa) && $empresa > 0){
            $comando->bindParam(":empresa",$empresa, \PDO::PARAM_INT);
        }
        if(isset($unidad) && $unidad > 0){
            $comando->bindParam(":unidad",$unidad, \PDO::PARAM_INT);
        }
        if(isset($nivel) && $nivel > 0){
            $comando->bindParam(":nivel",$nivel, \PDO::PARAM_INT);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Estado', 'Unidad'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public static function deleteAllResponsablesByConf($id){
        $con = \Yii::$app->db_gpr;
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $datemod = date('Y-m-d H:i:s');
        try{
            $sql = "UPDATE responsable_unidad p 
                SET p.runi_estado_logico='0', p.runi_estado='0', p.runi_fecha_modificacion=:datemod, p.runi_usuario_modifica=:iduser
                WHERE p.ugpr_id=:id AND p.runi_estado_logico='1' AND p.runi_estado='1';";

            $comando = $con->createCommand($sql);
            $comando->bindParam(':id' , $id, \PDO::PARAM_INT);
            $comando->bindParam(':iduser' , $iduser, \PDO::PARAM_INT);
            $comando->bindParam(':datemod' , $datemod, \PDO::PARAM_STR);
            $res = $comando->execute();
            if(isset($res)){
                return true;
            }else
                throw new \Exception('Error');
        }catch(\Exception $e){
            return false;
        }
    }

    public static function userIsAdmin($user_id, $emp_id){
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $sql = "SELECT 
                    ru.runi_isadmin as isadmin
                FROM 
                    ".$con->dbname.".responsable_unidad as ru
                    INNER JOIN ".$con->dbname.".unidad_gpr as u ON u.ugpr_id = ru.ugpr_id
                    INNER JOIN ".$con->dbname.".nivel as n ON n.niv_id = ru.niv_id
                    INNER JOIN ".$con2->dbname.".empresa as e ON e.emp_id = ru.emp_id
                    INNER JOIN ".$con2->dbname.".usuario as us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona as p ON p.per_id = us.per_id
                WHERE 
                    us.usu_id = :user_id AND
                    e.emp_id = :emp_id AND
                    n.niv_estado_logico=1 AND
                    u.ugpr_estado_logico=1 AND
                    e.emp_estado_logico=1 AND
                    us.usu_estado_logico=1 AND
                    ru.runi_estado_logico=1 
                ORDER BY ru.runi_id desc;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":user_id", $user_id, \PDO::PARAM_INT);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        if($res['isadmin'] == '1'){
            return true;
        }
        return false;
    }
}
