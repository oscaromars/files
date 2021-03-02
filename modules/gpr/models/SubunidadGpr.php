<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "subunidad_gpr".
 *
 * @property int $sgpr_id
 * @property int $ugpr_id
 * @property string $sgpr_nombre
 * @property string $sgpr_descripcion
 * @property int $sgpr_usuario_ingreso
 * @property int|null $sgpr_usuario_modifica
 * @property string $sgpr_estado
 * @property string $sgpr_fecha_creacion
 * @property string|null $sgpr_fecha_modificacion
 * @property string $sgpr_estado_logico
 *
 * @property UnidadGpr $ugpr
 */
class SubunidadGpr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subunidad_gpr';
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
            [['ugpr_id', 'sgpr_nombre', 'sgpr_descripcion', 'sgpr_usuario_ingreso', 'sgpr_estado', 'sgpr_estado_logico'], 'required'],
            [['ugpr_id', 'sgpr_usuario_ingreso', 'sgpr_usuario_modifica'], 'integer'],
            [['sgpr_fecha_creacion', 'sgpr_fecha_modificacion'], 'safe'],
            [['sgpr_nombre'], 'string', 'max' => 300],
            [['sgpr_descripcion'], 'string', 'max' => 500],
            [['sgpr_estado', 'sgpr_estado_logico'], 'string', 'max' => 1],
            [['ugpr_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadGpr::className(), 'targetAttribute' => ['ugpr_id' => 'ugpr_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sgpr_id' => 'Sgpr ID',
            'ugpr_id' => 'Ugpr ID',
            'sgpr_nombre' => 'Sgpr Nombre',
            'sgpr_descripcion' => 'Sgpr Descripcion',
            'sgpr_usuario_ingreso' => 'Sgpr Usuario Ingreso',
            'sgpr_usuario_modifica' => 'Sgpr Usuario Modifica',
            'sgpr_estado' => 'Sgpr Estado',
            'sgpr_fecha_creacion' => 'Sgpr Fecha Creacion',
            'sgpr_fecha_modificacion' => 'Sgpr Fecha Modificacion',
            'sgpr_estado_logico' => 'Sgpr Estado Logico',
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

    public function getAllSubUnidadGprGrid($search = NULL, $categoria = NULL, $entidad = NULL, $unidad = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        if(isset($search)){
            $str_search  = "(s.sgpr_nombre like :search OR ";
            $str_search .= "s.sgpr_descripcion like :search OR ";
            $str_search .= "e.ent_nombre like :search OR ";
            $str_search .= "u.ugpr_nombre like :search OR ";
            $str_search .= "c.cat_nombre like :search) AND ";
        }
        if(isset($categoria) && $categoria > 0){
            $str_search .= "c.cat_id = :categoria AND ";
        }
        if(isset($entidad) && $entidad > 0){
            $str_search .= "e.ent_id = :entidad AND ";
        }
        if(isset($unidad) && $unidad > 0){
            $str_search .= "u.ugpr_id = :unidad AND ";
        }
        $sql = "SELECT 
                    s.sgpr_id as id,
                    s.sgpr_nombre as Nombre,
                    s.sgpr_descripcion as Descripcion,
                    e.ent_nombre as Entidad,
                    c.cat_nombre as Categoria,
                    u.ugpr_nombre as Unidad,
                    s.sgpr_estado as Estado
                FROM 
                    ".$con->dbname.".subunidad_gpr as s
                    INNER JOIN ".$con->dbname.".unidad_gpr as u ON u.ugpr_id = s.ugpr_id
                    INNER JOIN ".$con->dbname.".entidad as e ON e.ent_id = u.ent_id
                    INNER JOIN ".$con->dbname.".categoria as c ON c.cat_id = e.cat_id
                WHERE 
                    $str_search
                    s.sgpr_estado_logico=1 AND
                    u.ugpr_estado_logico=1 AND
                    e.ent_estado_logico=1 AND
                    c.cat_estado_logico=1 
                ORDER BY s.sgpr_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($categoria) && $categoria > 0){
            $comando->bindParam(":categoria",$categoria, \PDO::PARAM_INT);
        }
        if(isset($entidad) && $entidad > 0){
            $comando->bindParam(":entidad",$entidad, \PDO::PARAM_INT);
        }
        if(isset($unidad) && $unidad > 0){
            $comando->bindParam(":unidad",$unidad, \PDO::PARAM_INT);
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
                    'attributes' => ['Nombre', 'Estado', 'Descripcion', 'Entidad', 'Categoria', 'Unidad'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public static function getArraySubUnidad(){
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $search = "";
        if($user_id != 1){
            $search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        $sql = "SELECT 
                    su.sgpr_id as id,
                    su.sgpr_nombre as name
                FROM 
                    ".$con->dbname.".subunidad_gpr AS su
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ugpr_id = su.ugpr_id
                    INNER JOIN ".$con->dbname.".entidad AS en ON en.ent_id = un.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON en.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                    INNER JOIN ".$con->dbname.".tipo_unidad AS tu ON tu.tuni_id = un.tuni_id
                WHERE 
                    $search
                    em.emp_estado_logico=1 AND
                    em.emp_estado=1 AND
                    un.ugpr_estado_logico=1 AND
                    un.ugpr_estado=1 AND
                    su.sgpr_estado_logico=1 AND
                    su.sgpr_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1
                GROUP BY su.sgpr_id, su.sgpr_nombre
                ORDER BY su.sgpr_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        return $res;
    }
}
