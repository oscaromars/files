<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "hito".
 *
 * @property int $hito_id
 * @property int $pro_id
 * @property string $hito_nombre
 * @property string $hito_descripcion
 * @property string $hito_fecha_compromiso
 * @property string|null $hito_fecha_real
 * @property float $hito_presupuesto
 * @property string $hito_cumplido
 * @property string $hito_peso
 * @property string $hito_progreso
 * @property string $hito_cumplimiento_poa
 * @property string|null $hito_cerrado
 * @property int $hito_usuario_ingreso
 * @property int|null $hito_usuario_modifica
 * @property string $hito_estado
 * @property string $hito_fecha_creacion
 * @property string|null $hito_fecha_modificacion
 * @property string $hito_estado_logico
 *
 * @property Proyecto $pro
 * @property HitoSeguimiento[] $hitoSeguimientos
 */
class Hito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hito';
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
            [['pro_id', 'hito_nombre', 'hito_descripcion', 'hito_fecha_compromiso', 'hito_presupuesto', 'hito_peso', 'hito_progreso', 'hito_cumplimiento_poa', 'hito_usuario_ingreso', 'hito_estado', 'hito_estado_logico'], 'required'],
            [['pro_id', 'hito_usuario_ingreso', 'hito_usuario_modifica'], 'integer'],
            [['hito_fecha_compromiso', 'hito_fecha_real', 'hito_fecha_creacion', 'hito_fecha_modificacion'], 'safe'],
            [['hito_presupuesto'], 'number'],
            [['hito_nombre'], 'string', 'max' => 300],
            [['hito_descripcion'], 'string', 'max' => 500],
            [['hito_cumplido', 'hito_cerrado', 'hito_estado', 'hito_estado_logico'], 'string', 'max' => 1],
            [['hito_peso', 'hito_progreso', 'hito_cumplimiento_poa'], 'string', 'max' => 10],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proyecto::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hito_id' => 'Hito ID',
            'pro_id' => 'Pro ID',
            'hito_nombre' => 'Hito Nombre',
            'hito_descripcion' => 'Hito Descripcion',
            'hito_fecha_compromiso' => 'Hito Fecha Compromiso',
            'hito_fecha_real' => 'Hito Fecha Real',
            'hito_presupuesto' => 'Hito Presupuesto',
            'hito_cumplido' => 'Hito Cumplido',
            'hito_peso' => 'Hito Peso',
            'hito_progreso' => 'Hito Progreso',
            'hito_cumplimiento_poa' => 'Hito Cumplimiento Poa',
            'hito_cerrado' => 'Hito Cerrado',
            'hito_usuario_ingreso' => 'Hito Usuario Ingreso',
            'hito_usuario_modifica' => 'Hito Usuario Modifica',
            'hito_estado' => 'Hito Estado',
            'hito_fecha_creacion' => 'Hito Fecha Creacion',
            'hito_fecha_modificacion' => 'Hito Fecha Modificacion',
            'hito_estado_logico' => 'Hito Estado Logico',
        ];
    }

    /**
     * Gets query for [[Pro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Proyecto::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * Gets query for [[HitoSeguimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHitoSeguimientos()
    {
        return $this->hasMany(HitoSeguimiento::className(), ['hito_id' => 'hito_id']);
    }

    public function getAllHitoGrid($search = NULL, $pro_id = NULL, $dataProvider = false){
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        $str_search_no_admin = " po.ppoa_estado_cierre = '0' AND pl.pped_estado_cierre = '0' AND ";
        if(isset($search)){
            $str_search .= "(hito_nombre like :search OR ";
            $str_search .= "hito_descripcion like :search) AND ";
        }
        if(isset($pro_id)){
            $str_search .= " p.pro_id = :pro_id AND ";
        }
        $sql = "SELECT
                    h.hito_id as id,
                    h.hito_nombre as Nombre,
                    h.hito_fecha_compromiso as FechaCompromiso,
                    ifnull(hs.hseg_fecha_real, '') as FechaReal,
                    ifnull(hs.hseg_cumplido, '0') as HitoCumplido,
                    h.hito_peso as Peso,
                    ifnull(hs.hseg_progreso, '0') as Avance,
                    h.hito_presupuesto as Presupuesto,
                    ifnull(hs.hseg_presupuesto, '0') as Consumo,
                    p.pro_fecha_inicio as ProyectoInicio,
                    p.pro_fecha_fin as ProyectoFin,
                    p.pro_id as IdPro,
                    p.pro_nombre as Proyecto,
                    tp.tpro_nombre as TipoProyecto
                FROM
                    ".$con->dbname.".hito as h
                    INNER JOIN ".$con->dbname.".proyecto as p ON p.pro_id = h.pro_id
                    INNER JOIN ".$con->dbname.".tipo_proyecto AS tp ON tp.tpro_id = p.tpro_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS uni ON uni.ugpr_id = p.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = uni.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON rp.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                    LEFT JOIN ".$con->dbname.".hito_seguimiento as hs ON hs.hito_id = h.hito_id AND hs.hseg_estado =1 AND hs.hseg_estado_logico=1 
                WHERE
                    -- $str_search_no_admin
                    $str_search
                    p.pro_estado=1 AND 
                    p.pro_estado_logico=1 AND 
                    h.hito_estado=1 AND 
                    h.hito_estado_logico=1
                ORDER BY h.hito_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($pro_id)){
            $comando->bindParam(":pro_id",$pro_id, \PDO::PARAM_INT);
        }
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
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
                    'attributes' => ['Nombre', 'FechaCompromiso', 'HitoCumplido'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public static function deleteAllHitosById($id){
        $con = \Yii::$app->db_gpr;
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $datemod = date('Y-m-d H:i:s');
        try{
            $sql = "UPDATE hito h
                SET h.hito_estado_logico='0', h.hito_estado='0', h.hito_fecha_modificacion=:datemod, h.hito_usuario_modifica=:iduser
                WHERE h.hito_id=:id AND h.hito_estado_logico='1'";

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

    public static function deleteAllHitosByProjectId($id){
        $con = \Yii::$app->db_gpr;
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $datemod = date('Y-m-d H:i:s');
        try{
            $sql = "UPDATE hito h
                SET h.hito_estado_logico='0', h.hito_estado='0', h.hito_fecha_modificacion=:datemod, h.hito_usuario_modifica=:iduser
                WHERE h.pro_id=:id AND h.hito_estado_logico='1'";

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

}
