<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "objetivo_operativo".
 *
 * @property int $oope_id
 * @property int $ppoa_id
 * @property int $oesp_id
 * @property int $ugpr_id
 * @property string $oope_nombre
 * @property string $oope_descripcion
 * @property string|null $oope_fecha_actualizacion
 * @property int $oope_usuario_ingreso
 * @property int|null $oope_usuario_modifica
 * @property string $oope_estado
 * @property string $oope_fecha_creacion
 * @property string|null $oope_fecha_modificacion
 * @property string $oope_estado_logico
 *
 * @property Indicador[] $indicadors
 * @property PlanificacionPoa $ppoa
 * @property UnidadGpr $ugpr
 * @property ObjetivoEspecifico $oesp
 */
class ObjetivoOperativo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'objetivo_operativo';
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
            [['ppoa_id', 'oesp_id', 'ugpr_id', 'oope_nombre', 'oope_descripcion', 'oope_usuario_ingreso', 'oope_estado', 'oope_estado_logico'], 'required'],
            [['ppoa_id', 'oesp_id', 'ugpr_id', 'oope_usuario_ingreso', 'oope_usuario_modifica'], 'integer'],
            [['oope_fecha_actualizacion', 'oope_fecha_creacion', 'oope_fecha_modificacion'], 'safe'],
            [['oope_nombre'], 'string', 'max' => 300],
            [['oope_descripcion'], 'string', 'max' => 500],
            [['oope_estado', 'oope_estado_logico'], 'string', 'max' => 1],
            [['ppoa_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionPoa::className(), 'targetAttribute' => ['ppoa_id' => 'ppoa_id']],
            [['ugpr_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadGpr::className(), 'targetAttribute' => ['ugpr_id' => 'ugpr_id']],
            [['oesp_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjetivoEspecifico::className(), 'targetAttribute' => ['oesp_id' => 'oesp_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oope_id' => 'Oope ID',
            'ppoa_id' => 'Ppoa ID',
            'oesp_id' => 'Oesp ID',
            'ugpr_id' => 'Ugpr ID',
            'oope_nombre' => 'Oope Nombre',
            'oope_descripcion' => 'Oope Descripcion',
            'oope_fecha_actualizacion' => 'Oope Fecha Actualizacion',
            'oope_usuario_ingreso' => 'Oope Usuario Ingreso',
            'oope_usuario_modifica' => 'Oope Usuario Modifica',
            'oope_estado' => 'Oope Estado',
            'oope_fecha_creacion' => 'Oope Fecha Creacion',
            'oope_fecha_modificacion' => 'Oope Fecha Modificacion',
            'oope_estado_logico' => 'Oope Estado Logico',
        ];
    }

    /**
     * Gets query for [[Indicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndicadors()
    {
        return $this->hasMany(Indicador::className(), ['oope_id' => 'oope_id']);
    }

    /**
     * Gets query for [[Ppoa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpoa()
    {
        return $this->hasOne(PlanificacionPoa::className(), ['ppoa_id' => 'ppoa_id']);
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
     * Gets query for [[Oesp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOesp()
    {
        return $this->hasOne(ObjetivoEspecifico::className(), ['oesp_id' => 'oesp_id']);
    }

    public function getAllObjOpeGrid($search = NULL, $objetivo = NULL, $plan = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(op.oope_nombre like :search OR ";
            $str_search .= "op.oope_descripcion like :search) AND ";
        }
        if(isset($objetivo) && $objetivo > 0){
            $str_search .= "oes.oesp_id = :objetivo AND ";
        }
        if(isset($plan) && $plan > 0){
            $str_search .= "po.ppoa_id = :plan AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        $str_search_no_admin = " po.ppoa_estado_cierre = '0' AND pl.pped_estado_cierre = '0' AND ";
        $sql = "SELECT 
                    op.oope_id as id,
                    op.oope_nombre as Nombre,
                    op.oope_descripcion as Descripcion,
                    oe.oest_nombre as Estrategico,
                    oes.oesp_nombre as Especifico,
                    po.ppoa_nombre as PlanificacionPoa,
                    po.ppoa_fecha_inicio as FechaInicio,
                    po.ppoa_fecha_fin as FechaFin,
                    op.oope_fecha_actualizacion as FechaActualizacion,
                    po.ppoa_estado_cierre as CierrePoa,
                    op.oope_estado as Estado
                FROM 
                    ".$con->dbname.".objetivo_operativo AS op
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON po.ppoa_id = op.ppoa_id 
                    INNER JOIN ".$con->dbname.".objetivo_especifico AS oes ON oes.oesp_id = op.oesp_id
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oe ON oe.oest_id = oes.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oe.pped_id 
                    INNER JOIN ".$con->dbname.".entidad AS ent ON ent.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = ent.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ent.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                    INNER JOIN ".$con->dbname.".tipo_unidad AS tu ON tu.tuni_id = un.tuni_id
                WHERE 
                    $str_search_no_admin
                    $str_search
                    oe.oest_estado_logico=1 AND
                    oe.oest_estado=1 AND
                    po.ppoa_estado_logico=1 AND 
                    po.ppoa_estado=1 AND 
                    oes.oesp_estado_logico=1 AND
                    oes.oesp_estado=1 AND
                    pl.pped_estado_logico=1 AND
                    pl.pped_estado=1 AND
                    ent.ent_estado_logico=1 AND
                    ent.ent_estado=1 AND
                    op.oope_estado_logico=1
                GROUP BY
                    op.oope_id, op.oope_nombre, op.oope_descripcion, oe.oest_nombre, oes.oesp_nombre, po.ppoa_nombre, po.ppoa_fecha_inicio, 
                    po.ppoa_fecha_fin, op.oope_fecha_actualizacion, po.ppoa_estado_cierre, op.oope_estado
                ORDER BY op.oope_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($objetivo) && $objetivo > 0){
            $comando->bindParam(":objetivo",$objetivo, \PDO::PARAM_INT);
        }
        if(isset($plan) && $plan > 0){
            $comando->bindParam(":plan",$plan, \PDO::PARAM_INT);
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

    public static function getArrayObjOperativo(){
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
        $str_search_no_admin = " po.ppoa_estado_cierre = '0' AND pl.pped_estado_cierre = '0' AND ";
        $sql = "SELECT 
                    op.oope_id as id,
                    op.oope_nombre as name
                FROM 
                    ".$con->dbname.".objetivo_operativo AS op
                    INNER JOIN ".$con->dbname.".objetivo_especifico AS oep ON oep.oesp_id = op.oesp_id
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oet ON oet.oest_id = oep.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oet.pped_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON po.ppoa_id = op.ppoa_id 
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
                    em.emp_estado_logico=1 AND
                    em.emp_estado=1 AND
                    un.ugpr_estado_logico=1 AND
                    un.ugpr_estado=1 AND
                    en.ent_estado_logico=1 AND
                    en.ent_estado=1
                GROUP BY op.oope_id, op.oope_nombre
                ORDER BY op.oope_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        return $res;
    }
}
