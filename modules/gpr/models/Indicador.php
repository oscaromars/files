<?php

namespace app\modules\gpr\models;

use PhpOffice\PhpSpreadsheet\Calculation\Token\Stack;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "indicador".
 *
 * @property int $ind_id
 * @property int $oope_id
 * @property int $tcon_id
 * @property int $pind_id
 * @property int $umed_id
 * @property int $ugpr_id
 * @property int $jind_id
 * @property int $cind_id
 * @property int $tmet_id
 * @property int $find_id
 * @property int|null $tagr_id
 * @property string $ind_nombre
 * @property string $ind_descripcion
 * @property string|null $ind_linea_base
 * @property string|null $ind_meta
 * @property string $ind_fuente_informe
 * @property string $ind_metodo_calculo
 * @property string $ind_fraccional
 * @property string $ind_agrupamiento
 * @property string|null $ind_fecha_inicio
 * @property string|null $ind_fecha_fin
 * @property int $ind_usuario_ingreso
 * @property int|null $ind_usuario_modifica
 * @property string $ind_estado
 * @property string $ind_fecha_creacion
 * @property string|null $ind_fecha_modificacion
 * @property string $ind_estado_logico
 *
 * @property ObjetivoOperativo $oope
 * @property TipoAgrupacion $tagr
 * @property TipoConfiguracion $tcon
 * @property PatronIndicador $pind
 * @property UnidadMedida $umed
 * @property UnidadGpr $ugpr
 * @property JerarquiaIndicador $jind
 * @property ComportamientoIndicador $cind
 * @property TipoMeta $tmet
 * @property FrecuenciaIndicador $find
 * @property MetaIndicador[] $metaIndicadors
 */
class Indicador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'indicador';
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
            [['oope_id', 'tcon_id', 'pind_id', 'umed_id', 'ugpr_id', 'jind_id', 'cind_id', 'tmet_id', 'find_id', 'ind_nombre', 'ind_descripcion', 'ind_fuente_informe', 'ind_metodo_calculo', 'ind_usuario_ingreso', 'ind_estado', 'ind_estado_logico'], 'required'],
            [['oope_id', 'tcon_id', 'pind_id', 'umed_id', 'ugpr_id', 'jind_id', 'cind_id', 'tmet_id', 'find_id', 'tagr_id', 'ind_usuario_ingreso', 'ind_usuario_modifica'], 'integer'],
            [['ind_descripcion'], 'string'],
            [['ind_fecha_inicio', 'ind_fecha_fin', 'ind_fecha_creacion', 'ind_fecha_modificacion'], 'safe'],
            [['ind_nombre'], 'string', 'max' => 300],
            [['ind_linea_base', 'ind_meta'], 'string', 'max' => 10],
            [['ind_fuente_informe', 'ind_metodo_calculo'], 'string', 'max' => 200],
            [['ind_fraccional', 'ind_agrupamiento', 'ind_estado', 'ind_estado_logico'], 'string', 'max' => 1],
            [['oope_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjetivoOperativo::className(), 'targetAttribute' => ['oope_id' => 'oope_id']],
            [['tagr_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoAgrupacion::className(), 'targetAttribute' => ['tagr_id' => 'tagr_id']],
            [['tcon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoConfiguracion::className(), 'targetAttribute' => ['tcon_id' => 'tcon_id']],
            [['pind_id'], 'exist', 'skipOnError' => true, 'targetClass' => PatronIndicador::className(), 'targetAttribute' => ['pind_id' => 'pind_id']],
            [['umed_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadMedida::className(), 'targetAttribute' => ['umed_id' => 'umed_id']],
            [['ugpr_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadGpr::className(), 'targetAttribute' => ['ugpr_id' => 'ugpr_id']],
            [['jind_id'], 'exist', 'skipOnError' => true, 'targetClass' => JerarquiaIndicador::className(), 'targetAttribute' => ['jind_id' => 'jind_id']],
            [['cind_id'], 'exist', 'skipOnError' => true, 'targetClass' => ComportamientoIndicador::className(), 'targetAttribute' => ['cind_id' => 'cind_id']],
            [['tmet_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoMeta::className(), 'targetAttribute' => ['tmet_id' => 'tmet_id']],
            [['find_id'], 'exist', 'skipOnError' => true, 'targetClass' => FrecuenciaIndicador::className(), 'targetAttribute' => ['find_id' => 'find_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ind_id' => 'Ind ID',
            'oope_id' => 'Oope ID',
            'tcon_id' => 'Tcon ID',
            'pind_id' => 'Pind ID',
            'umed_id' => 'Umed ID',
            'ugpr_id' => 'Ugpr ID',
            'jind_id' => 'Jind ID',
            'cind_id' => 'Cind ID',
            'tmet_id' => 'Tmet ID',
            'find_id' => 'Find ID',
            'tagr_id' => 'Tagr ID',
            'ind_nombre' => 'Ind Nombre',
            'ind_descripcion' => 'Ind Descripcion',
            'ind_linea_base' => 'Ind Linea Base',
            'ind_meta' => 'Ind Meta',
            'ind_fuente_informe' => 'Ind Fuente Informe',
            'ind_metodo_calculo' => 'Ind Metodo Calculo',
            'ind_fraccional' => 'Ind Fraccional',
            'ind_agrupamiento' => 'Ind Agrupamiento',
            'ind_fecha_inicio' => 'Ind Fecha Inicio',
            'ind_fecha_fin' => 'Ind Fecha Fin',
            'ind_usuario_ingreso' => 'Ind Usuario Ingreso',
            'ind_usuario_modifica' => 'Ind Usuario Modifica',
            'ind_estado' => 'Ind Estado',
            'ind_fecha_creacion' => 'Ind Fecha Creacion',
            'ind_fecha_modificacion' => 'Ind Fecha Modificacion',
            'ind_estado_logico' => 'Ind Estado Logico',
        ];
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

    /**
     * Gets query for [[Tagr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagr()
    {
        return $this->hasOne(TipoAgrupacion::className(), ['tagr_id' => 'tagr_id']);
    }

    /**
     * Gets query for [[Tcon]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTcon()
    {
        return $this->hasOne(TipoConfiguracion::className(), ['tcon_id' => 'tcon_id']);
    }

    /**
     * Gets query for [[Pind]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPind()
    {
        return $this->hasOne(PatronIndicador::className(), ['pind_id' => 'pind_id']);
    }

    /**
     * Gets query for [[Umed]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUmed()
    {
        return $this->hasOne(UnidadMedida::className(), ['umed_id' => 'umed_id']);
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
     * Gets query for [[Jind]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJind()
    {
        return $this->hasOne(JerarquiaIndicador::className(), ['jind_id' => 'jind_id']);
    }

    /**
     * Gets query for [[Cind]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCind()
    {
        return $this->hasOne(ComportamientoIndicador::className(), ['cind_id' => 'cind_id']);
    }

    /**
     * Gets query for [[Tmet]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTmet()
    {
        return $this->hasOne(TipoMeta::className(), ['tmet_id' => 'tmet_id']);
    }

    /**
     * Gets query for [[Find]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFind()
    {
        return $this->hasOne(FrecuenciaIndicador::className(), ['find_id' => 'find_id']);
    }

    /**
     * Gets query for [[MetaIndicadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetaIndicadors()
    {
        return $this->hasMany(MetaIndicador::className(), ['ind_id' => 'ind_id']);
    }

    public function getAllIndicadorGrid($search = NULL, $objetivo = NULL, $plan = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(i.ind_nombre like :search OR ";
            $str_search .= "i.ind_descripcion like :search) AND ";
        }
        if(isset($objetivo) && $objetivo > 0){
            $str_search .= "op.oope_id = :objetivo AND ";
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
                    i.ind_id as id,
                    i.ind_nombre as Nombre,
                    i.ind_linea_base as LineaBase,
                    i.ind_meta as Meta,
                    i.ind_fecha_inicio as FechaInicio, 
                    i.ind_fecha_fin as FechaFin,
                    i.ind_fraccional as Fraccional,
                    i.ind_agrupamiento as Agrupamiento,
                    f.find_nombre as Frecuencia,
                    tm.tmet_nombre as TipoMeta,
                    ta.tagr_nombre as TipoAgrupacion,
                    jq.jind_nombre as Jerarquia,
                    pi.pind_nombre as Patron,
                    ci.cind_nombre as Comportamiento,
                    tc.tcon_nombre as TipoConfiguracion,
                    um.umed_nombre as UnidadMedida,
                    op.oope_nombre as ObjetivoOperativo,
                    oes.oesp_nombre as ObjetivoEspecifico,
                    ent.ent_nombre as Entidad,
                    uni.ugpr_nombre as Unidad,
                    mid.SumMeta as SumMeta,
                    mid.SumResultado as SumResultado,
                    ifnull(temp.Meta, '0') as Meta,
                    ifnull(temp.Resultado, '0') as Resultado,
                    ifnull(temp.Bracket, '') as Bracket,
                    i.ind_estado as Estado
                FROM 
                    ".$con->dbname.".indicador AS i
                    INNER JOIN ".$con->dbname.".frecuencia_indicador AS f ON f.find_id = i.find_id
                    INNER JOIN ".$con->dbname.".tipo_meta AS tm ON tm.tmet_id = i.tmet_id
                    INNER JOIN ".$con->dbname.".jerarquia_indicador AS jq ON jq.jind_id = i.jind_id
                    INNER JOIN ".$con->dbname.".patron_indicador AS pi ON pi.pind_id = i.pind_id
                    INNER JOIN ".$con->dbname.".comportamiento_indicador AS ci ON ci.cind_id = i.cind_id
                    INNER JOIN ".$con->dbname.".tipo_configuracion AS tc ON tc.tcon_id = i.tcon_id
                    INNER JOIN ".$con->dbname.".unidad_medida AS um ON um.umed_id = i.umed_id
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS op ON op.oope_id = i.oope_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON po.ppoa_id = op.ppoa_id
                    INNER JOIN ".$con->dbname.".objetivo_especifico AS oes ON oes.oesp_id = op.oesp_id
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oe ON oe.oest_id = oes.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oe.pped_id
                    INNER JOIN ".$con->dbname.".entidad AS ent ON ent.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS uni ON uni.ugpr_id = i.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = uni.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ent.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                    LEFT JOIN ".$con->dbname.".tipo_agrupacion AS ta ON ta.tagr_id = i.tagr_id
                    LEFT JOIN (
                        SELECT 
                            mi.ind_id as ind_id,
                            ifnull(SUM(mi.mind_meta), '0') AS SumMeta,
                            ifnull(SUM(ms.mseg_resultado), '0') AS SumResultado
                        FROM 
                            ".$con->dbname.".meta_indicador as mi
                            LEFT JOIN ".$con->dbname.".meta_seguimiento AS ms ON mi.mind_id = ms.mind_id
                        WHERE
                            mi.mind_estado=1 AND mi.mind_estado_logico=1 AND
                            ms.mseg_estado=1 AND ms.mseg_estado_logico=1 
                        GROUP BY 
                            mi.ind_id
                    ) AS mid ON mid.ind_id = i.ind_id
                    LEFT JOIN (
                        SELECT 
                            mi.ind_id AS ind_id,
                            mi.mind_id AS mind_id,
                            ifnull(mi.mind_meta, '0') AS Meta,
                            ifnull(ms.mseg_resultado, '0') AS Resultado,
                            ms.mseg_nombre AS Bracket
                        FROM 
                            ".$con->dbname.".meta_indicador as mi
                            INNER JOIN ".$con->dbname.".meta_seguimiento AS ms ON mi.mind_id = ms.mind_id
                            INNER JOIN
                            (SELECT 
                                mi.ind_id AS ind_id,
                                MAX(mi.mind_id) AS mind_id
                            FROM 
                                ".$con->dbname.".meta_indicador as mi
                                INNER JOIN ".$con->dbname.".meta_seguimiento AS ms ON mi.mind_id = ms.mind_id
                            WHERE
                                ms.mseg_resultado >= 0 AND
                                mi.mind_estado=1 AND mi.mind_estado_logico=1 AND
                                ms.mseg_estado=1 AND ms.mseg_estado_logico=1 
                            GROUP BY 
                                mi.ind_id) AS t ON t.mind_id = mi.mind_id
                        WHERE
                            ms.mseg_resultado >= 0 AND
                            mi.mind_estado=1 AND mi.mind_estado_logico=1 AND
                            ms.mseg_estado=1 AND ms.mseg_estado_logico=1 
                        GROUP BY 
                            mi.ind_id, ifnull(mi.mind_meta, '0'), ifnull(ms.mseg_resultado, '0'), ms.mseg_nombre, mi.mind_id
                    ) AS temp ON temp.ind_id = i.ind_id
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
                    uni.ugpr_estado_logico=1 AND
                    uni.ugpr_estado=1 AND
                    i.ind_estado=1 AND
                    i.ind_estado_logico=1 AND 
                    op.oope_estado_logico=1
                GROUP BY
                    i.ind_id, i.ind_nombre, i.ind_linea_base, i.ind_meta, i.ind_fecha_inicio, i.ind_fecha_fin, i.ind_fraccional,
                    i.ind_agrupamiento, f.find_nombre, tm.tmet_nombre, ta.tagr_nombre, jq.jind_nombre, pi.pind_nombre,
                    ci.cind_nombre, tc.tcon_nombre, um.umed_nombre, op.oope_nombre, oes.oesp_nombre, ent.ent_nombre,
                    uni.ugpr_nombre, i.ind_estado, mid.SumMeta, mid.SumResultado, 
                    ifnull(temp.Meta, '0'), ifnull(temp.Resultado, '0'), ifnull(temp.Bracket, '')
                ORDER BY i.ind_id;";
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

    public static function getReporteIndicadores($pedi_id = NULL, $poa_id = NULL){
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        if($pedi_id != NULL){
            $str_search .= " pl.pped_id = :pedi_id AND ";
        }
        if($poa_id != NULL){
            $str_search .= " po.ppoa_id = :poa_id AND ";
        }

        $sql = "SELECT 
                    i.ind_id AS Id,
                    uni.ugpr_id AS UniId,
                    op.oope_id AS OOpId,
                    ent.ent_nombre AS Entidad,
                    uni.ugpr_nombre AS Unidad,
                    op.oope_nombre AS ObjetivoOperativo,
                    concat(p.per_pri_nombre, ' ', per_pri_apellido) AS Responsable,
                    YEAR(po.ppoa_fecha_inicio) AS Anio,
                    i.ind_nombre AS Indicador,
                    f.find_nombre AS Frecuencia,
                    f.find_denominador AS FrecuenciaDen,
                    ci.cind_nombre AS Comportamiento,
                    jq.jind_nombre AS Jerarquia,
                    i.ind_agrupamiento AS Agrupado,
                    i.ind_linea_base AS LineBase,
                    i.ind_descripcion AS Descripcion,
                    i.ind_metodo_calculo AS MCalculo,
                    i.ind_fraccional AS Fraccional
                FROM 
                    ".$con->dbname.".indicador AS i
                    INNER JOIN ".$con->dbname.".frecuencia_indicador AS f ON f.find_id = i.find_id
                    INNER JOIN ".$con->dbname.".tipo_meta AS tm ON tm.tmet_id = i.tmet_id
                    INNER JOIN ".$con->dbname.".jerarquia_indicador AS jq ON jq.jind_id = i.jind_id
                    INNER JOIN ".$con->dbname.".patron_indicador AS pi ON pi.pind_id = i.pind_id
                    INNER JOIN ".$con->dbname.".comportamiento_indicador AS ci ON ci.cind_id = i.cind_id
                    INNER JOIN ".$con->dbname.".tipo_configuracion AS tc ON tc.tcon_id = i.tcon_id
                    INNER JOIN ".$con->dbname.".unidad_medida AS um ON um.umed_id = i.umed_id
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS op ON op.oope_id = i.oope_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON po.ppoa_id = op.ppoa_id
                    INNER JOIN ".$con->dbname.".objetivo_especifico AS oes ON oes.oesp_id = op.oesp_id
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oe ON oe.oest_id = oes.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oe.pped_id
                    INNER JOIN ".$con->dbname.".entidad AS ent ON ent.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS uni ON uni.ugpr_id = i.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = uni.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ent.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                    INNER JOIN ".$con2->dbname.".persona AS p ON p.per_id = u.per_id
                WHERE 
                    $str_search
                    i.ind_estado='1' AND i.ind_estado_logico='1' AND
                    oe.oest_estado_logico='1' AND oe.oest_estado='1' AND
                    po.ppoa_estado_logico='1' AND po.ppoa_estado='1' AND 
                    oes.oesp_estado_logico='1' AND oes.oesp_estado='1' AND
                    pl.pped_estado_logico='1' AND pl.pped_estado='1' AND
                    ent.ent_estado_logico='1' AND ent.ent_estado='1' AND
                    uni.ugpr_estado_logico='1' AND uni.ugpr_estado='1' AND
                    op.oope_estado_logico='1' AND op.oope_estado='1'
                ORDER BY
                    UniId, Id;";
        $comando = Yii::$app->db->createCommand($sql);
        if($pedi_id != NULL){
            $comando->bindParam(":pedi_id",$pedi_id, \PDO::PARAM_INT);
        }
        if($poa_id != NULL){
            $comando->bindParam(":poa_id",$poa_id, \PDO::PARAM_INT);
        }
        $res = $comando->queryAll();
        return $res;
    }

    public static function getReportMetasIndicador($pedi_id = NULL, $poa_id = NULL){
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        $sql = "SELECT 
                    i.ind_id AS Id,
                    ms.mseg_id AS MId,
                    ms.mseg_nombre AS Bracket,
                    mi.mind_resultado AS Meta,
                    ms.mseg_resultado AS Resultado
                FROM 
                    ".$con->dbname.".indicador AS i
                    INNER JOIN ".$con->dbname.".frecuencia_indicador AS f ON f.find_id = i.find_id
                    INNER JOIN ".$con->dbname.".tipo_meta AS tm ON tm.tmet_id = i.tmet_id
                    INNER JOIN ".$con->dbname.".jerarquia_indicador AS jq ON jq.jind_id = i.jind_id
                    INNER JOIN ".$con->dbname.".patron_indicador AS pi ON pi.pind_id = i.pind_id
                    INNER JOIN ".$con->dbname.".comportamiento_indicador AS ci ON ci.cind_id = i.cind_id
                    INNER JOIN ".$con->dbname.".tipo_configuracion AS tc ON tc.tcon_id = i.tcon_id
                    INNER JOIN ".$con->dbname.".unidad_medida AS um ON um.umed_id = i.umed_id
                    INNER JOIN ".$con->dbname.".objetivo_operativo AS op ON op.oope_id = i.oope_id
                    INNER JOIN ".$con->dbname.".planificacion_poa AS po ON po.ppoa_id = op.ppoa_id
                    INNER JOIN ".$con->dbname.".objetivo_especifico AS oes ON oes.oesp_id = op.oesp_id
                    INNER JOIN ".$con->dbname.".objetivo_estrategico AS oe ON oe.oest_id = oes.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oe.pped_id
                    INNER JOIN ".$con->dbname.".entidad AS ent ON ent.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS uni ON uni.ugpr_id = i.ugpr_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = uni.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ent.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS u ON u.usu_id = rp.usu_id
                    INNER JOIN ".$con2->dbname.".persona AS p ON p.per_id = u.per_id
                    INNER JOIN ".$con->dbname.".meta_indicador AS mi ON mi.ind_id = i.ind_id
                    INNER JOIN ".$con->dbname.".meta_seguimiento AS ms ON ms.mind_id = mi.mind_id
                WHERE 
                    $str_search
                    i.ind_estado='1' AND i.ind_estado_logico='1' AND
                    oe.oest_estado_logico='1' AND oe.oest_estado='1' AND
                    po.ppoa_estado_logico='1' AND po.ppoa_estado='1' AND 
                    oes.oesp_estado_logico='1' AND oes.oesp_estado='1' AND
                    pl.pped_estado_logico='1' AND pl.pped_estado='1' AND
                    ent.ent_estado_logico='1' AND ent.ent_estado='1' AND
                    uni.ugpr_estado_logico='1' AND uni.ugpr_estado='1' AND
                    op.oope_estado_logico='1' AND op.oope_estado='1' AND
                    mi.mind_estado_logico='1' AND mi.mind_estado='1' AND
                    ms.mseg_estado_logico='1' AND ms.mseg_estado='1'
                ORDER BY
                    Id, MId;";
        $comando = Yii::$app->db->createCommand($sql);
        $res = $comando->queryAll();
        if($pedi_id != NULL){
            $comando->bindParam(":pedi_id",$pedi_id, \PDO::PARAM_INT);
        }
        if($poa_id != NULL){
            $comando->bindParam(":poa_id",$poa_id, \PDO::PARAM_INT);
        }
        return $res;
    }
}
