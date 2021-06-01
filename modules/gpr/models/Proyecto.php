<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "proyecto".
 *
 * @property int $pro_id
 * @property int $tpro_id
 * @property int $ugpr_id
 * @property int $oope_id
 * @property string $pro_nombre
 * @property string $pro_descripcion
 * @property string $pro_restricciones
 * @property string $pro_fecha_inicio
 * @property string $pro_fecha_fin
 * @property float $pro_presupuesto
 * @property string|null $pro_cerrado
 * @property string|null $pro_razon_cambio
 * @property int $pro_usuario_ingreso
 * @property int|null $pro_usuario_modifica
 * @property string $pro_estado
 * @property string $pro_fecha_creacion
 * @property string|null $pro_fecha_modificacion
 * @property string $pro_estado_logico
 *
 * @property Hito[] $hitos
 * @property TipoProyecto $tpro
 * @property UnidadGpr $ugpr
 * @property ObjetivoOperativo $oope
 */
class Proyecto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proyecto';
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
            [['tpro_id', 'ugpr_id', 'oope_id', 'pro_nombre', 'pro_descripcion', 'pro_restricciones', 'pro_fecha_inicio', 'pro_fecha_fin', 'pro_presupuesto', 'pro_usuario_ingreso', 'pro_estado', 'pro_estado_logico'], 'required'],
            [['tpro_id', 'ugpr_id', 'oope_id', 'pro_usuario_ingreso', 'pro_usuario_modifica'], 'integer'],
            [['pro_fecha_inicio', 'pro_fecha_fin', 'pro_fecha_creacion', 'pro_fecha_modificacion'], 'safe'],
            [['pro_presupuesto'], 'number'],
            [['pro_razon_cambio'], 'string'],
            [['pro_nombre'], 'string', 'max' => 300],
            [['pro_descripcion', 'pro_restricciones'], 'string', 'max' => 500],
            [['pro_cerrado', 'pro_estado', 'pro_estado_logico'], 'string', 'max' => 1],
            [['tpro_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoProyecto::className(), 'targetAttribute' => ['tpro_id' => 'tpro_id']],
            [['ugpr_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadGpr::className(), 'targetAttribute' => ['ugpr_id' => 'ugpr_id']],
            [['oope_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjetivoOperativo::className(), 'targetAttribute' => ['oope_id' => 'oope_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pro_id' => 'Pro ID',
            'tpro_id' => 'Tpro ID',
            'ugpr_id' => 'Ugpr ID',
            'oope_id' => 'Oope ID',
            'pro_nombre' => 'Pro Nombre',
            'pro_descripcion' => 'Pro Descripcion',
            'pro_restricciones' => 'Pro Restricciones',
            'pro_fecha_inicio' => 'Pro Fecha Inicio',
            'pro_fecha_fin' => 'Pro Fecha Fin',
            'pro_presupuesto' => 'Pro Presupuesto',
            'pro_cerrado' => 'Pro Cerrado',
            'pro_razon_cambio' => 'Pro Razon Cambio',
            'pro_usuario_ingreso' => 'Pro Usuario Ingreso',
            'pro_usuario_modifica' => 'Pro Usuario Modifica',
            'pro_estado' => 'Pro Estado',
            'pro_fecha_creacion' => 'Pro Fecha Creacion',
            'pro_fecha_modificacion' => 'Pro Fecha Modificacion',
            'pro_estado_logico' => 'Pro Estado Logico',
        ];
    }

    /**
     * Gets query for [[Hitos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHitos()
    {
        return $this->hasMany(Hito::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * Gets query for [[Tpro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTpro()
    {
        return $this->hasOne(TipoProyecto::className(), ['tpro_id' => 'tpro_id']);
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
     * Gets query for [[Oope]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOope()
    {
        return $this->hasOne(ObjetivoOperativo::className(), ['oope_id' => 'oope_id']);
    }

    public function getAllProyectoGrid($search = NULL, $tipo = NULL, $objetivo = NULL, $unidad = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search .= "(p.pro_nombre like :search OR ";
            $str_search .= "p.pro_descripcion like :search) AND ";
        }
        if(isset($tipo) && $tipo != 0){
            $str_search .= " tp.tpro_id=:tipo AND ";
        }
        if(isset($objetivo) && $objetivo != 0){
            $str_search .= " op.oope_id=:objetivo AND ";
        }
        if(isset($unidad) && $unidad != 0){
            $str_search .= " u.ugpr_id=:unidad AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND us.usu_id = $user_id AND ";
        }
        $sql = "SELECT
                    p.pro_id as id,
                    p.pro_nombre as Nombre,
                    p.pro_descripcion as Descripcion,
                    p.pro_fecha_inicio as FechaInicio,
                    p.pro_fecha_fin as FechaFin,
                    tp.tpro_nombre as TipoProyecto,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) as Responsable,
                    u.ugpr_nombre as Unidad,
                    u.ugpr_id as IdUni,
                    op.oope_nombre as Objetivo,
                    op.oope_id as IdObj,
                    p.pro_presupuesto as Presupuesto,
                    ifnull(hi.Consumo, '0') as Consumo,
                    ifnull(hi.Avance, '0') as Avance,
                    count(ht.hito_id) as CantHito,
                    p.pro_estado as Estado
                FROM
                    ".$con->dbname.".proyecto AS p
                    INNER JOIN ".$con->dbname.".tipo_proyecto AS tp ON tp.tpro_id = p.tpro_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS u ON u.ugpr_id = p.ugpr_id
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS op ON op.oope_id = p.oope_id
                    INNER JOIN ".$con->dbname.".responsable_unidad as ru ON ru.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ru.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona AS pe ON pe.per_id = us.per_id
                    INNER JOIN ".$con->dbname.".hito as ht ON ht.pro_id = p.pro_id
                    LEFT JOIN 
                    (
                        SELECT 
                            SUM(hs.hseg_presupuesto) AS Consumo,
                            SUM(h.hito_progreso) AS Avance,
                            h.pro_id as pro_id
                        FROM 
                            ".$con->dbname.".hito as h 
                            INNER JOIN ".$con->dbname.".hito_seguimiento AS hs ON h.hito_id = hs.hito_id
                        WHERE
                            h.hito_estado=1 AND
                            h.hito_estado_logico=1 AND
                            hs.hseg_estado=1 AND
                            hs.hseg_estado_logico=1 
                        GROUP BY 
                            h.pro_id
                    ) AS hi ON hi.pro_id = p.pro_id
                WHERE
                    $str_search
                    p.pro_estado_logico=1 AND
                    p.pro_estado=1 AND
                    tp.tpro_estado=1 AND 
                    tp.tpro_estado_logico=1 AND 
                    u.ugpr_estado=1 AND
                    u.ugpr_estado_logico=1 AND
                    op.oope_estado=1 AND
                    op.oope_estado_logico=1 
                GROUP BY
                    p.pro_id,
                    p.pro_nombre,
                    p.pro_descripcion,
                    p.pro_fecha_inicio,
                    p.pro_fecha_fin,
                    tp.tpro_nombre,
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido),
                    u.ugpr_nombre,
                    op.oope_nombre,
                    p.pro_presupuesto,
                    ifnull(hi.Consumo, '0'),
                    ifnull(hi.Avance, '0'),
                    p.pro_estado
                ORDER BY p.pro_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($tipo) && $tipo != 0){
            $comando->bindParam(":tipo",$tipo, \PDO::PARAM_INT);
        }
        if(isset($objetivo) && $objetivo != 0){
            $comando->bindParam(":objetivo",$objetivo, \PDO::PARAM_INT);
        }
        if(isset($unidad) && $unidad != 0){
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
                    'attributes' => ['Nombre', 'Estado', 'Descripcion'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public function getTotalPresupuestoByPoa($poa_id){
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $str_search = "";
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
                    po.ppoa_id AS ppoa_id,
                    YEAR(p.pro_fecha_fin) AS Anio,
                    SUM(p.pro_presupuesto) AS Presupuesto
                FROM 
                    ".$con->dbname.".proyecto AS p
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS o ON o.oope_id = p.oope_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON o.ppoa_id = po.ppoa_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pd ON po.pped_id = pd.pped_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS u ON u.ugpr_id = p.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad as ru ON ru.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ru.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona AS pe ON pe.per_id = us.per_id
                WHERE
                    $str_search
                    po.ppoa_id = :poa_id AND
                    p.pro_estado='1' AND p.pro_estado_logico='1' AND
                    o.oope_estado='1' AND o.oope_estado_logico='1' AND
                    po.ppoa_estado='1' AND po.ppoa_estado_logico='1' AND
                    pd.pped_estado='1' AND pd.pped_estado_logico='1' AND
                    o.oope_estado='1' AND o.oope_estado_logico='1' AND 
                    u.ugpr_estado='1' AND u.ugpr_estado_logico='1'
                GROUP BY
                    po.ppoa_id, YEAR(p.pro_fecha_fin)
                ORDER BY
                    ppoa_id ASC, Anio ASC;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":poa_id",$poa_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res['Presupuesto'];
    }

    public function getTotalPresupuestoByPedi($pedi_id){
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $str_search = "";
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
                    pd.pped_id AS pped_id,
                    YEAR(p.pro_fecha_fin) AS Anio,
                    SUM(p.pro_presupuesto) AS Presupuesto
                FROM 
                    ".$con->dbname.".proyecto AS p
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS o ON o.oope_id = p.oope_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON o.ppoa_id = po.ppoa_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pd ON po.pped_id = pd.pped_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS u ON u.ugpr_id = p.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad as ru ON ru.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ru.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona AS pe ON pe.per_id = us.per_id
                WHERE
                    $str_search
                    pd.pped_id = :pedi_id AND
                    p.pro_estado='1' AND p.pro_estado_logico='1' AND
                    o.oope_estado='1' AND o.oope_estado_logico='1' AND
                    po.ppoa_estado='1' AND po.ppoa_estado_logico='1' AND
                    pd.pped_estado='1' AND pd.pped_estado_logico='1' AND
                    o.oope_estado='1' AND o.oope_estado_logico='1' AND 
                    u.ugpr_estado='1' AND u.ugpr_estado_logico='1'
                GROUP BY
                    pd.pped_id, YEAR(p.pro_fecha_fin)
                ORDER BY
                    pped_id ASC, Anio ASC;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":pedi_id",$pedi_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res['Presupuesto'];
    }

    public function getPresupuestoByPoa($poa_id, $dataProvider = false){
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $str_search = "";
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
                    -- p.pro_id as Id,
                    pro.Anio as Anio,
                    pro.Mes as Mes,
                    SUM(pro.PresupuestoPro) as Programado,
                    SUM(eje.PresupuestoEje) as Ejecutado
                FROM
                    ".$con->dbname.".proyecto AS p
                    INNER JOIN ".$con->dbname.".tipo_proyecto AS tp ON tp.tpro_id = p.tpro_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS u ON u.ugpr_id = p.ugpr_id
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS op ON op.oope_id = p.oope_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS pl ON pl.ppoa_id = op.ppoa_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS ped ON pl.pped_id = ped.pped_id
                    INNER JOIN ".$con->dbname.".responsable_unidad as ru ON ru.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ru.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona AS pe ON pe.per_id = us.per_id
                    INNER JOIN (
                        SELECT 
                            p.pro_id AS Id,
                            YEAR(p.pro_fecha_fin) AS Anio,
                            MONTH(p.pro_fecha_fin) AS Mes,
                            SUM(p.pro_presupuesto) AS PresupuestoPro
                        FROM 
                            ".$con->dbname.".proyecto AS p
                            INNER JOIN ".$con->dbname.".objetivo_operativo AS o ON o.oope_id = p.oope_id
                            INNER JOIN ".$con->dbname.".planificacion_poa AS po ON o.ppoa_id = po.ppoa_id
                            INNER JOIN ".$con->dbname.".planificacion_pedi AS ped ON po.pped_id = ped.pped_id
                        WHERE
                            p.pro_estado='1' AND p.pro_estado_logico='1' AND
                            o.oope_estado='1' AND o.oope_estado_logico='1' AND
                            po.ppoa_estado='1' AND po.ppoa_estado_logico='1' AND
                            ped.pped_estado='1' AND ped.pped_estado_logico='1'
                        GROUP BY
                            p.pro_id, YEAR(p.pro_fecha_fin), MONTH(p.pro_fecha_fin)
                        ORDER BY
                            Id ASC, Anio ASC, Mes ASC
                    ) AS pro ON pro.Id = p.pro_id
                    LEFT JOIN 
                    (
                        SELECT 
                            p.pro_id AS Id,
                            YEAR(hs.hseg_fecha_real) AS Anio,
                            MONTH(hs.hseg_fecha_real) AS Mes,
                            SUM(hs.hseg_presupuesto) AS PresupuestoEje
                        FROM 
                            ".$con->dbname.".hito as h 
                            INNER JOIN ".$con->dbname.".hito_seguimiento AS hs ON h.hito_id = hs.hito_id
                            INNER JOIN ".$con->dbname.".proyecto AS p ON p.pro_id = h.pro_id
                            INNER JOIN ".$con->dbname.".objetivo_operativo AS o ON o.oope_id = p.oope_id
                            INNER JOIN ".$con->dbname.".planificacion_poa AS po ON o.ppoa_id = po.ppoa_id
                            INNER JOIN ".$con->dbname.".planificacion_pedi AS ped ON po.pped_id = ped.pped_id
                        WHERE
                            h.hito_estado='1' AND h.hito_estado_logico='1' AND
                            hs.hseg_estado='1' AND hs.hseg_estado_logico='1' AND
                            p.pro_estado='1' AND p.pro_estado_logico='1' AND
                            o.oope_estado='1' AND o.oope_estado_logico='1' AND
                            po.ppoa_estado='1' AND po.ppoa_estado_logico='1' AND
                            ped.pped_estado='1' AND ped.pped_estado_logico='1'
                        GROUP BY
                            p.pro_id, YEAR(hs.hseg_fecha_real), MONTH(hs.hseg_fecha_real)
                        ORDER BY
                            Id ASC, Anio ASC, Mes ASC
                    ) AS eje ON eje.Id = p.pro_id
                WHERE
                    $str_search
                    pl.ppoa_id = :poa_id AND
                    p.pro_estado_logico=1 AND
                    p.pro_estado=1 AND
                    tp.tpro_estado=1 AND 
                    tp.tpro_estado_logico=1 AND 
                    u.ugpr_estado=1 AND
                    u.ugpr_estado_logico=1 AND
                    op.oope_estado=1 AND
                    op.oope_estado_logico=1 AND
                    ped.pped_estado=1 AND ped.pped_estado_logico=1 AND
                    pl.ppoa_estado=1 AND pl.ppoa_estado_logico=1
                GROUP BY
                    pro.Anio, pro.Mes
                ORDER BY Anio ASC, Mes ASC ;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":poa_id",$poa_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        // proceso de acumulacion
        $prog = 0;
        $eje = 0;
        foreach($res as $key => $value){
            if($key == 0){
                $prog = $value['Programado'];
                $eje = $value['Ejecutado'];
            }else{
                $prog += $value['Programado'];
                $eje += $value['Ejecutado'];
                $res[$key]['Programado'] = $prog;
                $res[$key]['Ejecutado'] = $eje;
            }
        }
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Anio', 'Mes'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public function getPresupuestoByPedi($pedi_id, $dataProvider = false){
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $str_search = "";
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
                    -- p.pro_id as Id,
                    pro.Anio as Anio,
                    pro.Mes as Mes,
                    SUM(pro.PresupuestoPro) as Programado,
                    SUM(eje.PresupuestoEje) as Ejecutado
                FROM
                    ".$con->dbname.".proyecto AS p
                    INNER JOIN ".$con->dbname.".tipo_proyecto AS tp ON tp.tpro_id = p.tpro_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS u ON u.ugpr_id = p.ugpr_id
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS op ON op.oope_id = p.oope_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS pl ON pl.ppoa_id = op.ppoa_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS ped ON pl.pped_id = ped.pped_id
                    INNER JOIN ".$con->dbname.".responsable_unidad as ru ON ru.ugpr_id = u.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ru.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona AS pe ON pe.per_id = us.per_id
                    INNER JOIN (
                        SELECT 
                            p.pro_id as Id,
                            YEAR(p.pro_fecha_fin) AS Anio,
                            MONTH(p.pro_fecha_fin) AS Mes,
                            SUM(p.pro_presupuesto) AS PresupuestoPro
                        FROM 
                            ".$con->dbname.".proyecto AS p
                            INNER JOIN ".$con->dbname.".objetivo_operativo AS o ON o.oope_id = p.oope_id
                            INNER JOIN ".$con->dbname.".planificacion_poa AS po ON o.ppoa_id = po.ppoa_id
                            INNER JOIN ".$con->dbname.".planificacion_pedi AS ped ON po.pped_id = ped.pped_id
                        WHERE
                            p.pro_estado='1' AND p.pro_estado_logico='1' AND
                            o.oope_estado='1' AND o.oope_estado_logico='1' AND
                            po.ppoa_estado='1' AND po.ppoa_estado_logico='1' AND
                            ped.pped_estado='1' AND ped.pped_estado_logico='1'
                        GROUP BY
                            p.pro_id, YEAR(p.pro_fecha_fin), MONTH(p.pro_fecha_fin)
                        ORDER BY
                            Id ASC, Anio ASC, Mes ASC
                    ) AS pro ON pro.Id = p.pro_id
                    LEFT JOIN 
                    (
                        SELECT 
                            p.pro_id as Id,
                            -- YEAR(hs.hseg_fecha_real) AS Anio,
                            -- MONTH(hs.hseg_fecha_real) AS Mes, 
                            YEAR(p.pro_fecha_fin) AS Anio,
			                MONTH(p.pro_fecha_fin) AS Mes,
                            SUM(hs.hseg_presupuesto) AS PresupuestoEje
                        FROM 
                            ".$con->dbname.".hito as h 
                            INNER JOIN ".$con->dbname.".hito_seguimiento AS hs ON h.hito_id = hs.hito_id
                            INNER JOIN ".$con->dbname.".proyecto AS p ON p.pro_id = h.pro_id
                            INNER JOIN ".$con->dbname.".objetivo_operativo AS o ON o.oope_id = p.oope_id
                            INNER JOIN ".$con->dbname.".planificacion_poa AS po ON o.ppoa_id = po.ppoa_id
                            INNER JOIN ".$con->dbname.".planificacion_pedi AS ped ON po.pped_id = ped.pped_id
                        WHERE
                            h.hito_estado='1' AND h.hito_estado_logico='1' AND
                            hs.hseg_estado='1' AND hs.hseg_estado_logico='1' AND
                            p.pro_estado='1' AND p.pro_estado_logico='1' AND
                            o.oope_estado='1' AND o.oope_estado_logico='1' AND
                            po.ppoa_estado='1' AND po.ppoa_estado_logico='1' AND
                            ped.pped_estado='1' AND ped.pped_estado_logico='1'
                        GROUP BY
                            p.pro_id, YEAR(p.pro_fecha_fin), MONTH(p.pro_fecha_fin) -- YEAR(hs.hseg_fecha_real), MONTH(hs.hseg_fecha_real)
                        ORDER BY
                            Id ASC, Anio ASC, Mes ASC
                    ) AS eje ON eje.Id = p.pro_id
                WHERE
                    $str_search
                    ped.pped_id = :pedi_id AND
                    p.pro_estado_logico=1 AND
                    p.pro_estado=1 AND
                    tp.tpro_estado=1 AND 
                    tp.tpro_estado_logico=1 AND 
                    u.ugpr_estado=1 AND
                    u.ugpr_estado_logico=1 AND
                    op.oope_estado=1 AND
                    op.oope_estado_logico=1 AND
                    ped.pped_estado=1 AND ped.pped_estado_logico=1 AND
                    pl.ppoa_estado=1 AND pl.ppoa_estado_logico=1
                GROUP BY
                    pro.Anio, pro.Mes
                ORDER BY Anio ASC, Mes ASC ;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":pedi_id",$pedi_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        // proceso de acumulacion
        $prog = 0;
        $eje = 0;
        foreach($res as $key => $value){
            if($key == 0){
                $prog = $value['Programado'];
                $eje = $value['Ejecutado'];
            }else{
                $prog += $value['Programado'];
                $eje += $value['Ejecutado'];
                $res[$key]['Programado'] = $prog;
                $res[$key]['Ejecutado'] = $eje;
            }
        }
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Anio', 'Mes'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}
