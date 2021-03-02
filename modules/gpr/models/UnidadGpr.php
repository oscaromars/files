<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "unidad_gpr".
 *
 * @property int $ugpr_id
 * @property int $ent_id
 * @property int $tuni_id
 * @property string $ugpr_nombre
 * @property string $ugpr_descripcion
 * @property int $ugpr_usuario_ingreso
 * @property int|null $ugpr_usuario_modifica
 * @property string $ugpr_estado
 * @property string $ugpr_fecha_creacion
 * @property string|null $ugpr_fecha_modificacion
 * @property string $ugpr_estado_logico
 *
 * @property Indicador[] $indicadors
 * @property ObjetivoEspecifico[] $objetivoEspecificos
 * @property ObjetivoOperativo[] $objetivoOperativos
 * @property Proyecto[] $proyectos
 * @property ResponsableUnidad[] $responsableUnidads
 * @property SubunidadGpr[] $subunidadGprs
 * @property Entidad $ent
 * @property TipoUnidad $tuni
 */
class UnidadGpr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unidad_gpr';
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
            [['ent_id', 'tuni_id', 'ugpr_nombre', 'ugpr_descripcion', 'ugpr_usuario_ingreso', 'ugpr_estado', 'ugpr_estado_logico'], 'required'],
            [['ent_id', 'tuni_id', 'ugpr_usuario_ingreso', 'ugpr_usuario_modifica'], 'integer'],
            [['ugpr_fecha_creacion', 'ugpr_fecha_modificacion'], 'safe'],
            [['ugpr_nombre'], 'string', 'max' => 300],
            [['ugpr_descripcion'], 'string', 'max' => 500],
            [['ugpr_estado', 'ugpr_estado_logico'], 'string', 'max' => 1],
            [['ent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entidad::className(), 'targetAttribute' => ['ent_id' => 'ent_id']],
            [['tuni_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoUnidad::className(), 'targetAttribute' => ['tuni_id' => 'tuni_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ugpr_id' => 'Ugpr ID',
            'ent_id' => 'Ent ID',
            'tuni_id' => 'Tuni ID',
            'ugpr_nombre' => 'Ugpr Nombre',
            'ugpr_descripcion' => 'Ugpr Descripcion',
            'ugpr_usuario_ingreso' => 'Ugpr Usuario Ingreso',
            'ugpr_usuario_modifica' => 'Ugpr Usuario Modifica',
            'ugpr_estado' => 'Ugpr Estado',
            'ugpr_fecha_creacion' => 'Ugpr Fecha Creacion',
            'ugpr_fecha_modificacion' => 'Ugpr Fecha Modificacion',
            'ugpr_estado_logico' => 'Ugpr Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['ugpr_id' => 'ugpr_id']);
    }

    /**
     * Gets query for [[ObjetivoEspecificos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoEspecificos()
    {
        return $this->hasMany(ObjetivoEspecifico::className(), ['ugpr_id' => 'ugpr_id']);
    }

    /**
     * Gets query for [[ObjetivoOperativos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoOperativos()
    {
        return $this->hasMany(ObjetivoOperativo::className(), ['ugpr_id' => 'ugpr_id']);
    }

    /**
     * Gets query for [[Proyectos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProyectos()
    {
        return $this->hasMany(Proyecto::className(), ['ugpr_id' => 'ugpr_id']);
    }

    /**
     * Gets query for [[ResponsableUnidads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsableUnidads()
    {
        return $this->hasMany(ResponsableUnidad::className(), ['ugpr_id' => 'ugpr_id']);
    }

    /**
     * Gets query for [[SubunidadGprs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubunidadGprs()
    {
        return $this->hasMany(SubunidadGpr::className(), ['ugpr_id' => 'ugpr_id']);
    }

    /**
     * Gets query for [[Ent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEnt()
    {
        return $this->hasOne(Entidad::className(), ['ent_id' => 'ent_id']);
    }

    /**
     * Gets query for [[Tuni]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTuni()
    {
        return $this->hasOne(TipoUnidad::className(), ['tuni_id' => 'tuni_id']);
    }

    public function getAllUnidadGprGrid($search = NULL, $categoria = NULL, $entidad = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(u.ugpr_nombre like :search OR ";
            $str_search .= "u.ugpr_descripcion like :search OR ";
            $str_search .= "e.ent_nombre like :search OR ";
            $str_search .= "c.cat_nombre like :search) AND ";
        }
        if(isset($categoria) && $categoria > 0){
            $str_search .= "c.cat_id = :categoria AND ";
        }
        if(isset($entidad) && $entidad > 0){
            $str_search .= "e.ent_id = :entidad AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND ";
            // $str_search .= " em.emp_id = $emp_id AND us.usu_id = $user_id AND ";
        }
        $sql = "SELECT 
                    u.ugpr_id as id,
                    u.ugpr_nombre as Nombre,
                    u.ugpr_descripcion as Descripcion,
                    e.ent_nombre as Entidad,
                    c.cat_nombre as Categoria,
                    u.ugpr_estado as Estado
                FROM 
                    ".$con->dbname.".unidad_gpr as u
                    INNER JOIN ".$con->dbname.".entidad as e ON e.ent_id = u.ent_id
                    INNER JOIN ".$con->dbname.".categoria as c ON c.cat_id = e.cat_id
                    -- INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON e.emp_id = em.emp_id
                    -- INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = rp.usu_id
                WHERE 
                    $str_search
                    u.ugpr_estado_logico=1 AND
                    e.ent_estado_logico=1 AND
                    c.cat_estado_logico=1
                GROUP BY u.ugpr_id, u.ugpr_nombre, u.ugpr_descripcion, e.ent_nombre, c.cat_nombre, u.ugpr_estado
                ORDER BY u.ugpr_id;";
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
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Estado', 'Descripcion', 'Entidad', 'Categoria'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public static function getArrayUnidad($isAdmin = false, $all = false){
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $search = "";
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        if(!$all){
            if($isAdmin){
                $search .= " tu.tuni_alias = 'Adm' AND ";
            }else{
                $search .= " tu.tuni_alias <> 'Adm' AND ";
            }
        }
        $sql = "SELECT 
                    un.ugpr_id as id,
                    un.ugpr_nombre as name
                FROM 
                    -- ".$con->dbname.".subunidad_gpr AS su
                    -- INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ugpr_id = su.ugpr_id
                    ".$con->dbname.".unidad_gpr AS un
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
                    -- su.sgpr_estado_logico=1 AND
                    -- su.sgpr_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1
                GROUP BY un.ugpr_id, un.ugpr_nombre
                ORDER BY un.ugpr_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        return $res;
    }

    public static function getUnidadesByObjOperativo($obj_id){
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $str_search = "";
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND us.usu_id = $user_id AND ";
        }
        $sql = "SELECT 
                    u.ugpr_id as id,
                    u.ugpr_nombre as name
                FROM 
                    ".$con->dbname.".unidad_gpr as u
                    INNER JOIN ".$con->dbname.".objetivo_operativo as op ON op.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con->dbname.".entidad as e ON e.ent_id = u.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON e.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = rp.usu_id
                WHERE 
                    $str_search
                    op.oope_id = :obj_id AND 
                    u.ugpr_estado_logico=1 AND
                    op.oope_estado_logico=1 AND
                    e.ent_estado_logico=1
                GROUP BY u.ugpr_id, u.ugpr_nombre
                ORDER BY u.ugpr_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":obj_id",$obj_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }
}
