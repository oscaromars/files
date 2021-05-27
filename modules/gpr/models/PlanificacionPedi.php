<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "planificacion_pedi".
 *
 * @property int $pped_id
 * @property int $ent_id
 * @property string $pped_nombre
 * @property string $pped_descripcion
 * @property string|null $pped_fecha_inicio
 * @property string|null $pped_fecha_fin
 * @property string|null $pped_fecha_actualizacion
 * @property string $pped_estado_cierre
 * @property int $pped_usuario_ingreso
 * @property int|null $pped_usuario_modifica
 * @property string $pped_estado
 * @property string $pped_fecha_creacion
 * @property string|null $pped_fecha_modificacion
 * @property string $pped_estado_logico
 *
 * @property ObjetivoEstrategico[] $objetivoEstrategicos
 * @property Entidad $ent
 * @property PlanificacionPoa[] $planificacionPoas
 */
class PlanificacionPedi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_pedi';
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
            [['ent_id', 'pped_nombre', 'pped_descripcion', 'pped_usuario_ingreso', 'pped_estado', 'pped_estado_logico'], 'required'],
            [['ent_id', 'pped_usuario_ingreso', 'pped_usuario_modifica'], 'integer'],
            [['pped_fecha_inicio', 'pped_fecha_fin', 'pped_fecha_actualizacion', 'pped_fecha_creacion', 'pped_fecha_modificacion'], 'safe'],
            [['pped_nombre'], 'string', 'max' => 300],
            [['pped_descripcion'], 'string', 'max' => 500],
            [['pped_estado_cierre', 'pped_estado', 'pped_estado_logico'], 'string', 'max' => 1],
            [['ent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entidad::className(), 'targetAttribute' => ['ent_id' => 'ent_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pped_id' => 'Pped ID',
            'ent_id' => 'Ent ID',
            'pped_nombre' => 'Pped Nombre',
            'pped_descripcion' => 'Pped Descripcion',
            'pped_fecha_inicio' => 'Pped Fecha Inicio',
            'pped_fecha_fin' => 'Pped Fecha Fin',
            'pped_fecha_actualizacion' => 'Pped Fecha Actualizacion',
            'pped_estado_cierre' => 'Pped Estado Cierre',
            'pped_usuario_ingreso' => 'Pped Usuario Ingreso',
            'pped_usuario_modifica' => 'Pped Usuario Modifica',
            'pped_estado' => 'Pped Estado',
            'pped_fecha_creacion' => 'Pped Fecha Creacion',
            'pped_fecha_modificacion' => 'Pped Fecha Modificacion',
            'pped_estado_logico' => 'Pped Estado Logico',
        ];
    }

    /**
     * Gets query for [[ObjetivoEstrategicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoEstrategicos()
    {
        return $this->hasMany(ObjetivoEstrategico::className(), ['pped_id' => 'pped_id']);
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
     * Gets query for [[PlanificacionPoas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionPoas()
    {
        return $this->hasMany(PlanificacionPoa::className(), ['pped_id' => 'pped_id']);
    }

    public function getAllPlanPediGrid($search = NULL, $entidad = NULL, $cierre = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(p.pped_nombre like :search OR ";
            $str_search .= "p.pped_descripcion like :search OR ";
            $str_search .= "e.ent_nombre like :search) AND ";
        }
        if(isset($entidad) && $entidad > 0){
            $str_search .= "e.ent_id = :entidad AND ";
        }
        if(isset($cierre) && $cierre >= 0){
            $str_search .= "p.pped_estado_cierre = :cierre AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND us.usu_id = $user_id AND ";
        }
        $sql = "SELECT 
                    p.pped_id as id,
                    p.pped_nombre as Nombre,
                    p.pped_descripcion as Descripcion,
                    e.ent_nombre as Entidad,
                    p.pped_fecha_inicio as FechaInicio,
                    p.pped_fecha_fin as FechaFin,
                    p.pped_fecha_actualizacion as FechaActualizacion,
                    p.pped_estado_cierre as Cierre,
                    p.pped_estado as Estado
                FROM 
                    ".$con->dbname.".planificacion_pedi as p
                    INNER JOIN ".$con->dbname.".entidad as e ON e.ent_id = p.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr as u ON u.ent_id = e.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON e.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = rp.usu_id
                WHERE 
                    $str_search
                    p.pped_estado_logico=1 
                GROUP BY p.pped_id, p.pped_nombre, p.pped_descripcion, e.ent_nombre, p.pped_fecha_inicio, 
                         p.pped_fecha_fin, p.pped_fecha_actualizacion, p.pped_estado_cierre, p.pped_estado
                ORDER BY p.pped_id desc;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($entidad) && $entidad > 0){
            $comando->bindParam(":entidad",$entidad, \PDO::PARAM_INT);
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

    public static function getArrayPlanPedi(){
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        $search = "";
        if($user_id != 1){
            $search .= " em.emp_id = $emp_id AND ";
        }
        $sql = "SELECT 
                    pl.pped_id as id,
                    pl.pped_nombre as name
                FROM 
                    ".$con->dbname.".objetivo_operativo AS op
                    INNER JOIN ".$con->dbname.".objetivo_especifico AS oep ON oep.oesp_id = op.oesp_id
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oet ON oet.oest_id = oep.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oet.pped_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON po.ppoa_id = op.ppoa_id 
                    INNER JOIN ".$con->dbname.".entidad AS en ON en.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = en.ent_id
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
                    em.emp_estado_logico=1 AND
                    em.emp_estado=1 AND
                    un.ugpr_estado_logico=1 AND
                    un.ugpr_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1
                GROUP BY pl.pped_id, pl.pped_nombre
                ORDER BY pl.pped_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $res = $comando->queryAll();
        return $res;
    }
}
