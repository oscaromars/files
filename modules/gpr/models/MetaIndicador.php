<?php

namespace app\modules\gpr\models;

use Yii;
use app\modules\gpr\Module as gpr;
use Exception;
use yii\data\ArrayDataProvider;

gpr::registerTranslations();
/**
 * This is the model class for table "meta_indicador".
 *
 * @property int $mind_id
 * @property int $ind_id
 * @property string $mind_nombre
 * @property string|null $mind_descripcion
 * @property string|null $mind_meta
 * @property string|null $mind_numerador
 * @property string|null $mind_denominador
 * @property string|null $mind_resultado
 * @property string|null $mind_avance
 * @property string|null $mind_meta_cerrada
 * @property string|null $mind_fecha_inicio
 * @property string|null $mind_fecha_fin
 * @property int $mind_usuario_ingreso
 * @property int|null $mind_usuario_modifica
 * @property string $mind_estado
 * @property string $mind_fecha_creacion
 * @property string|null $mind_fecha_modificacion
 * @property string $mind_estado_logico
 *
 * @property Indicador $ind
 * @property MetaSeguimiento[] $metaSeguimientos
 */
class MetaIndicador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meta_indicador';
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
            [['ind_id', 'mind_nombre', 'mind_usuario_ingreso', 'mind_estado', 'mind_estado_logico'], 'required'],
            [['ind_id', 'mind_usuario_ingreso', 'mind_usuario_modifica'], 'integer'],
            [['mind_fecha_inicio', 'mind_fecha_fin', 'mind_fecha_creacion', 'mind_fecha_modificacion'], 'safe'],
            [['mind_nombre'], 'string', 'max' => 300],
            [['mind_descripcion'], 'string', 'max' => 500],
            [['mind_meta', 'mind_numerador', 'mind_denominador', 'mind_resultado', 'mind_avance'], 'string', 'max' => 10],
            [['mind_meta_cerrada', 'mind_estado', 'mind_estado_logico'], 'string', 'max' => 1],
            [['ind_id'], 'exist', 'skipOnError' => true, 'targetClass' => Indicador::className(), 'targetAttribute' => ['ind_id' => 'ind_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mind_id' => 'Mind ID',
            'ind_id' => 'Ind ID',
            'mind_nombre' => 'Mind Nombre',
            'mind_descripcion' => 'Mind Descripcion',
            'mind_meta' => 'Mind Meta',
            'mind_numerador' => 'Mind Numerador',
            'mind_denominador' => 'Mind Denominador',
            'mind_resultado' => 'Mind Resultado',
            'mind_avance' => 'Mind Avance',
            'mind_meta_cerrada' => 'Mind Meta Cerrada',
            'mind_fecha_inicio' => 'Mind Fecha Inicio',
            'mind_fecha_fin' => 'Mind Fecha Fin',
            'mind_usuario_ingreso' => 'Mind Usuario Ingreso',
            'mind_usuario_modifica' => 'Mind Usuario Modifica',
            'mind_estado' => 'Mind Estado',
            'mind_fecha_creacion' => 'Mind Fecha Creacion',
            'mind_fecha_modificacion' => 'Mind Fecha Modificacion',
            'mind_estado_logico' => 'Mind Estado Logico',
        ];
    }

    /**
     * Gets query for [[Ind]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInd()
    {
        return $this->hasOne(Indicador::className(), ['ind_id' => 'ind_id']);
    }

    /**
     * Gets query for [[MetaSeguimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetaSeguimientos()
    {
        return $this->hasMany(MetaSeguimiento::className(), ['mind_id' => 'mind_id']);
    }

    public static function inicializarMeta($id){
        // proceso de creacion de campos meta deacuerdo a la frecuencia.
        $indicador = Indicador::findOne($id);
        $find_id = $indicador->find_id;
        $frecuencia = FrecuenciaIndicador::findOne($find_id);
        $periodo = json_decode($frecuencia->find_items, true);
        $transaction = Yii::$app->db_gpr->beginTransaction();
        $item = "";
        try{
            for($i=0; $i<count($periodo); $i++){
                $item = $periodo[$i];//gpr::t('meta', $periodo[$i]);
                $meta = new MetaIndicador();
                $meta->ind_id = $id;
                $meta->mind_nombre = $item;
                //$meta->mind_descripcion = "";
                //$meta->mind_meta = "";
                //$meta->mind_numerador = "";
                //$meta->mind_denominador = "";
                //$meta->mind_avance = "";
                //$meta->mind_resultado = "";
                //$meta->mind_meta_cerrada = "";
                //$meta->mind_fecha_inicio = "";
                //$meta->mind_usuario_modifica = "";
                //$meta->mind_fecha_modificacion = "";
                $meta->mind_usuario_ingreso = Yii::$app->session->get('PB_iduser', FALSE);
                $meta->mind_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
                $meta->mind_estado = '1';
                $meta->mind_estado_logico = '1';
                if(!$meta->save()){
                    throw new Exception('Error creando Registro.'.json_encode($meta->getErrors()));
                }
            }
            $transaction->commit();

        }catch(Exception $e){
            //Utilities::putMessageLogFile($e->getMessage());
            $transaction->rollback();
            return false;
        }

        return true;
        
    }

    public static function getAllIndicadorGrid($ind_id, $onlydata = false){
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $str_search = "";
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND u.usu_id = $user_id AND ";
        }
        $str_search_no_admin = " po.ppoa_estado_cierre = '0' AND pl.pped_estado_cierre = '0' AND ";
        $sql = "SELECT 
                    mi.mind_id as id,
                    ifnull(mi.mind_nombre, '') as Periodo,
                    ifnull(mi.mind_meta, '') as MetaPeriodo,
                    ifnull(mi.mind_numerador, '') as Numerador,
                    ifnull(mi.mind_denominador, '') as Denominador,
                    ifnull(mi.mind_resultado, '') as Resultado,
                    ifnull(mi.mind_avance, '') as AvancePeriodo,
                    ifnull(mi.mind_meta_cerrada, '') as MetaCerrada
                FROM 
                    ".$con->dbname.".meta_indicador AS mi
                    INNER JOIN ".$con->dbname.".indicador AS i ON i.ind_id = mi.ind_id
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
                WHERE 
                    $str_search_no_admin
                    $str_search
                    mi.ind_id=:ind_id AND
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
                    op.oope_estado_logico=1 AND
                    mi.mind_estado=1 AND
                    mi.mind_estado_logico
                ORDER BY mi.mind_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":ind_id",$ind_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        if(!$onlydata){
            $dataProvider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Periodo'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}
