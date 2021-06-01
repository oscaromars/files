<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "objetivo_especifico".
 *
 * @property int $oesp_id
 * @property int $oest_id
 * @property int $ugpr_id
 * @property string $oesp_nombre
 * @property string $oesp_descripcion
 * @property int $oesp_usuario_ingreso
 * @property int|null $oesp_usuario_modifica
 * @property string $oesp_estado
 * @property string $oesp_fecha_creacion
 * @property string|null $oesp_fecha_modificacion
 * @property string $oesp_estado_logico
 *
 * @property EstrategiaObjespe[] $estrategiaObjespes
 * @property ObjetivoEstrategico $oest
 * @property UnidadGpr $ugpr
 * @property ObjetivoOperativo[] $objetivoOperativos
 */
class ObjetivoEspecifico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'objetivo_especifico';
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
            [['oest_id', 'ugpr_id', 'oesp_nombre', 'oesp_descripcion', 'oesp_usuario_ingreso', 'oesp_estado', 'oesp_estado_logico'], 'required'],
            [['oest_id', 'ugpr_id', 'oesp_usuario_ingreso', 'oesp_usuario_modifica'], 'integer'],
            [['oesp_fecha_creacion', 'oesp_fecha_modificacion'], 'safe'],
            [['oesp_nombre'], 'string', 'max' => 300],
            [['oesp_descripcion'], 'string', 'max' => 500],
            [['oesp_estado', 'oesp_estado_logico'], 'string', 'max' => 1],
            [['oest_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjetivoEstrategico::className(), 'targetAttribute' => ['oest_id' => 'oest_id']],
            [['ugpr_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadGpr::className(), 'targetAttribute' => ['ugpr_id' => 'ugpr_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oesp_id' => 'Oesp ID',
            'oest_id' => 'Oest ID',
            'ugpr_id' => 'Ugpr ID',
            'oesp_nombre' => 'Oesp Nombre',
            'oesp_descripcion' => 'Oesp Descripcion',
            'oesp_usuario_ingreso' => 'Oesp Usuario Ingreso',
            'oesp_usuario_modifica' => 'Oesp Usuario Modifica',
            'oesp_estado' => 'Oesp Estado',
            'oesp_fecha_creacion' => 'Oesp Fecha Creacion',
            'oesp_fecha_modificacion' => 'Oesp Fecha Modificacion',
            'oesp_estado_logico' => 'Oesp Estado Logico',
        ];
    }

    /**
     * Gets query for [[EstrategiaObjespes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstrategiaObjespes()
    {
        return $this->hasMany(EstrategiaObjEsp::className(), ['oesp_id' => 'oesp_id']);
    }

    /**
     * Gets query for [[Oest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOest()
    {
        return $this->hasOne(ObjetivoEstrategico::className(), ['oest_id' => 'oest_id']);
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
     * Gets query for [[ObjetivoOperativos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoOperativos()
    {
        return $this->hasMany(ObjetivoOperativo::className(), ['oesp_id' => 'oesp_id']);
    }

    public function getAllObjEspGrid($search = NULL, $objetivo = NULL, $unidad = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        if(isset($search)){
            $str_search  = "(oep.oesp_nombre like :search OR ";
            $str_search .= "oep.oesp_descripcion like :search) AND ";
        }
        if(isset($objetivo) && $objetivo > 0){
            $str_search .= "oet.oest_id = :objetivo AND ";
        }
        if(isset($unidad) && $unidad > 0){
            $str_search .= "ua.ugpr_id = :unidad AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        $str_search_no_admin = " pl.pped_estado_cierre = '0' AND ";
        $sql = "SELECT 
                    oep.oesp_id as id,
                    oep.oesp_nombre as Nombre,
                    oep.oesp_descripcion as Descripcion,
                    oet.oest_nombre as Objetivo,
                    ua.ugpr_nombre as Unidad,
                    pl.pped_nombre as PlanificacionPedi,
                    pl.pped_estado_cierre as CierrePedi,
                    oep.oesp_estado as Estado
                FROM 
                    ".$con->dbname.".objetivo_especifico AS oep
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oet ON oet.oest_id = oep.oest_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS ua ON ua.ugpr_id = oep.ugpr_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oet.pped_id
                    INNER JOIN ".$con->dbname.".entidad AS en ON en.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".tipo_unidad AS tu ON tu.tuni_id = ua.tuni_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = ua.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON en.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                WHERE 
                    $str_search_no_admin
                    $str_search
                    tu.tuni_id = 1 AND 
                    oep.oesp_estado_logico=1 AND
                    oet.oest_estado_logico=1 AND
                    oet.oest_estado=1 AND
                    pl.pped_estado_logico=1 AND
                    pl.pped_estado=1 AND
                    ua.ugpr_estado_logico=1 AND
                    ua.ugpr_estado=1 AND
                    tu.tuni_estado_logico=1 AND
                    tu.tuni_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1
                GROUP BY  
                    oep.oesp_id, oep.oesp_nombre, oep.oesp_descripcion, oet.oest_nombre, 
                    ua.ugpr_nombre, pl.pped_nombre, pl.pped_estado_cierre, oep.oesp_estado
                ORDER BY oep.oesp_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($objetivo) && $objetivo > 0){
            $comando->bindParam(":objetivo",$objetivo, \PDO::PARAM_INT);
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
                    'attributes' => ['Nombre', 'Estado', 'Descripcion',],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public static function getArrayObjEspecifico(){
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
        $str_search_no_admin = " pl.pped_estado_cierre = '0' AND ";
        $sql = "SELECT 
                    oep.oesp_id as id,
                    oep.oesp_nombre as name
                FROM 
                    ".$con->dbname.".objetivo_especifico AS oep
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oet ON oet.oest_id = oep.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oet.pped_id
                    INNER JOIN ".$con->dbname.".entidad AS en ON en.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = en.ent_id
                    -- INNER JOIN ".$con->dbname.".subunidad_gpr AS su ON su.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON en.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                    INNER JOIN ".$con->dbname.".tipo_unidad AS tu ON tu.tuni_id = un.tuni_id
                WHERE 
                    $str_search_no_admin 
                    $search
                    tu.tuni_alias <> 'Adm' AND 
                    oep.oesp_estado_logico=1 AND
                    oet.oest_estado_logico=1 AND
                    oet.oest_estado=1 AND
                    pl.pped_estado_logico=1 AND
                    pl.pped_estado=1 AND
                    pl.pped_estado_cierre=0 AND 
                    em.emp_estado_logico=1 AND
                    em.emp_estado=1 AND
                    un.ugpr_estado_logico=1 AND
                    un.ugpr_estado=1 AND
                    -- su.sgpr_estado_logico=1 AND
                    -- su.sgpr_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1
                GROUP BY oep.oesp_id, oep.oesp_nombre
                ORDER BY oep.oesp_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        return $res;
    }
}
