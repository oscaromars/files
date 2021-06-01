<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "planificacion_poa".
 *
 * @property int $ppoa_id
 * @property int $pped_id
 * @property string $ppoa_nombre
 * @property string $ppoa_descripcion
 * @property string|null $ppoa_fecha_inicio
 * @property string|null $ppoa_fecha_fin
 * @property string|null $ppoa_fecha_actualizacion
 * @property int $ppoa_usuario_ingreso
 * @property int|null $ppoa_usuario_modifica
 * @property string $ppoa_estado_cierre
 * @property string $ppoa_estado
 * @property string $ppoa_fecha_creacion
 * @property string|null $ppoa_fecha_modificacion
 * @property string $ppoa_estado_logico
 *
 * @property ObjetivoOperativo[] $objetivoOperativos
 * @property PlanificacionPedi $pped
 */
class PlanificacionPoa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_poa';
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
            [['pped_id', 'ppoa_nombre', 'ppoa_descripcion', 'ppoa_usuario_ingreso', 'ppoa_estado', 'ppoa_estado_logico'], 'required'],
            [['pped_id', 'ppoa_usuario_ingreso', 'ppoa_usuario_modifica'], 'integer'],
            [['ppoa_fecha_inicio', 'ppoa_fecha_fin', 'ppoa_fecha_actualizacion', 'ppoa_fecha_creacion', 'ppoa_fecha_modificacion'], 'safe'],
            [['ppoa_nombre'], 'string', 'max' => 300],
            [['ppoa_descripcion'], 'string', 'max' => 500],
            [['ppoa_estado_cierre', 'ppoa_estado', 'ppoa_estado_logico'], 'string', 'max' => 1],
            [['pped_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionPedi::className(), 'targetAttribute' => ['pped_id' => 'pped_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ppoa_id' => 'Ppoa ID',
            'pped_id' => 'Pped ID',
            'ppoa_nombre' => 'Ppoa Nombre',
            'ppoa_descripcion' => 'Ppoa Descripcion',
            'ppoa_fecha_inicio' => 'Ppoa Fecha Inicio',
            'ppoa_fecha_fin' => 'Ppoa Fecha Fin',
            'ppoa_fecha_actualizacion' => 'Ppoa Fecha Actualizacion',
            'ppoa_usuario_ingreso' => 'Ppoa Usuario Ingreso',
            'ppoa_usuario_modifica' => 'Ppoa Usuario Modifica',
            'ppoa_estado_cierre' => 'Ppoa Estado Cierre',
            'ppoa_estado' => 'Ppoa Estado',
            'ppoa_fecha_creacion' => 'Ppoa Fecha Creacion',
            'ppoa_fecha_modificacion' => 'Ppoa Fecha Modificacion',
            'ppoa_estado_logico' => 'Ppoa Estado Logico',
        ];
    }

    /**
     * Gets query for [[ObjetivoOperativos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoOperativos()
    {
        return $this->hasMany(ObjetivoOperativo::className(), ['ppoa_id' => 'ppoa_id']);
    }

    /**
     * Gets query for [[Pped]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPped()
    {
        return $this->hasOne(PlanificacionPedi::className(), ['pped_id' => 'pped_id']);
    }

    public function getAllPlanPoaGrid($search = NULL, $planpedi = NULL, $cierre = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(o.ppoa_nombre like :search OR ";
            $str_search .= "o.ppoa_descripcion like :search OR ";
            $str_search .= "p.pped_nombre like :search) AND ";
        }
        if(isset($planpedi) && $planpedi > 0){
            $str_search .= "p.pped_id = :planpedi AND ";
        }
        if(isset($cierre) && $cierre >= 0){
            $str_search .= "o.ppoa_estado_cierre = :cierre AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        $sql = "SELECT 
                    o.ppoa_id as id,
                    o.ppoa_nombre as Nombre,
                    o.ppoa_descripcion as Descripcion,
                    p.pped_nombre as PlanificacionPedi,
                    o.ppoa_fecha_inicio as FechaInicio,
                    o.ppoa_fecha_fin as FechaFin,
                    o.ppoa_fecha_actualizacion as FechaActualizacion,
                    o.ppoa_estado_cierre as Cierre,
                    o.ppoa_estado as Estado
                FROM 
                    ".$con->dbname.".planificacion_poa as o
                    INNER JOIN ".$con->dbname.".planificacion_pedi as p ON p.pped_id = o.pped_id
                    INNER JOIN ".$con->dbname.".entidad AS en ON en.ent_id = p.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = en.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON en.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                WHERE 
                    $str_search
                    p.pped_estado_logico=1 AND
                    o.ppoa_estado_logico=1
                GROUP BY
                    o.ppoa_id, o.ppoa_nombre, o.ppoa_descripcion, p.pped_nombre, o.ppoa_fecha_inicio, o.ppoa_fecha_fin,
                    o.ppoa_fecha_actualizacion, o.ppoa_estado_cierre, o.ppoa_estado
                ORDER BY o.ppoa_id desc;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($planpedi) && $planpedi > 0){
            $comando->bindParam(":planpedi",$planpedi, \PDO::PARAM_INT);
        }
        if(isset($cierre) && $cierre >= 0){
            $comando->bindParam(":cierre",$cierre, \PDO::PARAM_STR);
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

    public static function getArrayPlanPoa(){
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $search = "";
        if($user_id != 1){
            $search .= " em.emp_id = $emp_id AND ";
        }
        $sql = "SELECT 
                    po.ppoa_id as id,
                    po.ppoa_nombre as name
                FROM 
                    ".$con->dbname.".objetivo_especifico AS oep
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oet ON oet.oest_id = oep.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oet.pped_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON pl.pped_id = po.ppoa_id
                    INNER JOIN ".$con->dbname.".entidad AS en ON en.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = en.ent_id
                    -- INNER JOIN ".$con->dbname.".subunidad_gpr AS su ON su.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON en.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                WHERE 
                    $search
                    oep.oesp_estado_logico=1 AND
                    oet.oest_estado_logico=1 AND
                    oet.oest_estado=1 AND
                    pl.pped_estado_logico=1 AND
                    pl.pped_estado=1 AND
                    pl.pped_estado_cierre=0 AND 
                    po.ppoa_estado_logico=1 AND
                    po.ppoa_estado=1 AND
                    po.ppoa_estado_cierre=0 AND 
                    em.emp_estado_logico=1 AND
                    em.emp_estado=1 AND
                    un.ugpr_estado_logico=1 AND
                    un.ugpr_estado=1 AND
                    -- su.sgpr_estado_logico=1 AND
                    -- su.sgpr_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1
                GROUP BY po.ppoa_id, po.ppoa_nombre
                ORDER BY po.ppoa_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $res = $comando->queryAll();
        return $res;
    }

    public static function getCierrePoaPedi($oope_id){
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $sql = "SELECT 
                    pl.pped_estado_cierre as pedi_cierre,
                    po.ppoa_estado_cierre as poa_cierre
                FROM 
                    ".$con->dbname.".objetivo_operativo AS oop
                    INNER JOIN ".$con->dbname.".objetivo_especifico AS oep ON oep.oesp_id = oop.oesp_id
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oet ON oet.oest_id = oep.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oet.pped_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON pl.pped_id = po.ppoa_id
                    INNER JOIN ".$con->dbname.".entidad AS en ON en.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = en.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON rp.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                WHERE 
                    -- em.emp_id = :emp_id AND 
                    -- u.usu_id = :user_id AND 
                    oop.oope_id = :oope_id AND 
                    oep.oesp_estado_logico=1 AND
                    oet.oest_estado_logico=1 AND
                    oet.oest_estado=1 AND
                    pl.pped_estado_logico=1 AND
                    pl.pped_estado=1 AND
                    po.ppoa_estado_logico=1 AND
                    po.ppoa_estado=1 AND
                    em.emp_estado_logico=1 AND
                    em.emp_estado=1 AND
                    un.ugpr_estado_logico=1 AND
                    un.ugpr_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1";
        $comando = Yii::$app->db->createCommand($sql);
        
        //$comando->bindParam(":emp_id",$emp_id, \PDO::PARAM_INT);
        //$comando->bindParam(":user_id",$user_id, \PDO::PARAM_INT);
        $comando->bindParam(":oope_id",$oope_id, \PDO::PARAM_INT);
        
        $res = $comando->queryOne();
        return $res;
    }
}
