<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "entidad".
 *
 * @property int $ent_id
 * @property int $cat_id
 * @property int $emp_id
 * @property string $ent_nombre
 * @property string $ent_descripcion
 * @property int $ent_usuario_ingreso
 * @property int|null $ent_usuario_modifica
 * @property string $ent_estado
 * @property string $ent_fecha_creacion
 * @property string|null $ent_fecha_modificacion
 * @property string $ent_estado_logico
 *
 * @property Categoria $cat
 * @property PlanificacionPedi[] $planificacionPedis
 * @property UnidadGpr[] $unidadGprs
 */
class Entidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad';
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
            [['cat_id', 'emp_id', 'ent_nombre', 'ent_descripcion', 'ent_usuario_ingreso', 'ent_estado', 'ent_estado_logico'], 'required'],
            [['cat_id', 'emp_id', 'ent_usuario_ingreso', 'ent_usuario_modifica'], 'integer'],
            [['ent_fecha_creacion', 'ent_fecha_modificacion'], 'safe'],
            [['ent_nombre'], 'string', 'max' => 300],
            [['ent_descripcion'], 'string', 'max' => 500],
            [['ent_estado', 'ent_estado_logico'], 'string', 'max' => 1],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ent_id' => 'Ent ID',
            'cat_id' => 'Cat ID',
            'emp_id' => 'Emp ID',
            'ent_nombre' => 'Ent Nombre',
            'ent_descripcion' => 'Ent Descripcion',
            'ent_usuario_ingreso' => 'Ent Usuario Ingreso',
            'ent_usuario_modifica' => 'Ent Usuario Modifica',
            'ent_estado' => 'Ent Estado',
            'ent_fecha_creacion' => 'Ent Fecha Creacion',
            'ent_fecha_modificacion' => 'Ent Fecha Modificacion',
            'ent_estado_logico' => 'Ent Estado Logico',
        ];
    }

    /**
     * Gets query for [[Cat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Categoria::className(), ['cat_id' => 'cat_id']);
    }

    /**
     * Gets query for [[PlanificacionPedis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionPedis()
    {
        return $this->hasMany(PlanificacionPedi::className(), ['ent_id' => 'ent_id']);
    }

    /**
     * Gets query for [[UnidadGprs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadGprs()
    {
        return $this->hasMany(UnidadGpr::className(), ['ent_id' => 'ent_id']);
    }

    public function getAllPlanPediGrid($search = NULL, $categoria = NULL, $dataProvider = false){
        $idUser = Yii::$app->session->get("PB_iduser");
        $idEmpresa = Yii::$app->session->get("PB_idempresa");
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(e.ent_nombre like :search OR ";
            $str_search .= "e.ent_descripcion like :search OR ";
            $str_search .= "c.cat_nombre like :search) AND ";
        }
        if(isset($categoria) && $categoria > 0){
            $str_search .= "c.cat_id = :categoria AND ";
        }
        if(isset($idUser) && $idUser != 1){
            $str_search .= "e.emp_id = $idEmpresa AND ";
        }
        $sql = "SELECT 
                    e.ent_id as id,
                    e.ent_nombre as Nombre,
                    e.ent_descripcion as Descripcion,
                    c.cat_nombre as Categoria,
                    em.emp_alias as Empresa,
                    e.ent_estado as Estado
                FROM 
                    ".$con->dbname.".entidad as e
                    INNER JOIN ".$con->dbname.".categoria as c ON c.cat_id = e.cat_id
                    INNER JOIN ".$con2->dbname.".empresa as em ON em.emp_id = e.emp_id
                WHERE 
                    $str_search
                    em.emp_estado_logico=1 AND
                    e.ent_estado_logico=1 AND
                    c.cat_estado_logico=1
                ORDER BY c.cat_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($categoria) && $categoria > 0){
            $comando->bindParam(":categoria",$categoria, \PDO::PARAM_INT);
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
                    'attributes' => ['Nombre', 'Estado', 'Descripcion'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}
