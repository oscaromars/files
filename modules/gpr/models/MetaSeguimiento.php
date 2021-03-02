<?php

namespace app\modules\gpr\models;

use Yii;
use app\modules\gpr\Module as gpr;
use Exception;
use yii\data\ArrayDataProvider;

gpr::registerTranslations();

/**
 * This is the model class for table "meta_seguimiento".
 *
 * @property int $mseg_id
 * @property int $mind_id
 * @property string $mseg_nombre
 * @property string|null $mseg_descripcion
 * @property string|null $mseg_comentario
 * @property string|null $mseg_meta
 * @property string|null $mseg_numerador
 * @property string|null $mseg_denominador
 * @property string|null $mseg_resultado
 * @property string|null $mseg_avance
 * @property string|null $mseg_periodo_cerrado
 * @property string|null $mseg_fecha_inicio
 * @property string|null $mseg_fecha_fin
 * @property int $mseg_usuario_ingreso
 * @property int|null $mseg_usuario_modifica
 * @property string $mseg_estado
 * @property string $mseg_fecha_creacion
 * @property string|null $mseg_fecha_modificacion
 * @property string $mseg_estado_logico
 *
 * @property MetaAnexo[] $metaAnexos
 * @property MetaIndicador $mind
 */
class MetaSeguimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meta_seguimiento';
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
            [['mind_id', 'mseg_nombre', 'mseg_usuario_ingreso', 'mseg_estado', 'mseg_estado_logico'], 'required'],
            [['mind_id', 'mseg_usuario_ingreso', 'mseg_usuario_modifica'], 'integer'],
            [['mseg_comentario'], 'string'],
            [['mseg_fecha_inicio', 'mseg_fecha_fin', 'mseg_fecha_creacion', 'mseg_fecha_modificacion'], 'safe'],
            [['mseg_nombre'], 'string', 'max' => 300],
            [['mseg_descripcion'], 'string', 'max' => 500],
            [['mseg_meta', 'mseg_numerador', 'mseg_denominador', 'mseg_resultado', 'mseg_avance'], 'string', 'max' => 10],
            [['mseg_periodo_cerrado', 'mseg_estado', 'mseg_estado_logico'], 'string', 'max' => 1],
            [['mind_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetaIndicador::className(), 'targetAttribute' => ['mind_id' => 'mind_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mseg_id' => 'Mseg ID',
            'mind_id' => 'Mind ID',
            'mseg_nombre' => 'Mseg Nombre',
            'mseg_descripcion' => 'Mseg Descripcion',
            'mseg_comentario' => 'Mseg Comentario',
            'mseg_meta' => 'Mseg Meta',
            'mseg_numerador' => 'Mseg Numerador',
            'mseg_denominador' => 'Mseg Denominador',
            'mseg_resultado' => 'Mseg Resultado',
            'mseg_avance' => 'Mseg Avance',
            'mseg_periodo_cerrado' => 'Mseg Periodo Cerrado',
            'mseg_fecha_inicio' => 'Mseg Fecha Inicio',
            'mseg_fecha_fin' => 'Mseg Fecha Fin',
            'mseg_usuario_ingreso' => 'Mseg Usuario Ingreso',
            'mseg_usuario_modifica' => 'Mseg Usuario Modifica',
            'mseg_estado' => 'Mseg Estado',
            'mseg_fecha_creacion' => 'Mseg Fecha Creacion',
            'mseg_fecha_modificacion' => 'Mseg Fecha Modificacion',
            'mseg_estado_logico' => 'Mseg Estado Logico',
        ];
    }

    /**
     * Gets query for [[MetaAnexos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetaAnexos()
    {
        return $this->hasMany(MetaAnexo::className(), ['mseg_id' => 'mseg_id']);
    }

    /**
     * Gets query for [[Mind]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMind()
    {
        return $this->hasOne(MetaIndicador::className(), ['mind_id' => 'mind_id']);
    }

    public static function inicializarMetaSeguimiento($id){
        // proceso de creacion de campos meta deacuerdo a la frecuencia.
        $indicador = Indicador::findOne($id);
        $find_id = $indicador->find_id;
        $metas = MetaIndicador::findAll(['mind_estado' => '1', 'mind_estado_logico' => '1', 'ind_id' => $id]);
        $transaction = Yii::$app->db_gpr->beginTransaction();
        $item = "";
        try{
            foreach($metas as $meta){
                $mind_id = $meta->mind_id;
                $metaSeg = new MetaSeguimiento();
                $metaSeg->mind_id = $mind_id;
                $metaSeg->mseg_nombre = $meta->mind_nombre;
                //$metaSeg->mseg_descripcion = "";
                $metaSeg->mseg_meta = $meta->mind_meta;
                //$metaSeg->mseg_numerador = "";
                $metaSeg->mseg_denominador = $meta->mind_denominador;
                //$metaSeg->mseg_comentario = "";
                //$metaSeg->mseg_avance = "";
                //$metaSeg->mseg_resultado = "";
                //$metaSeg->mseg_periodo_cerrado = "";
                //$metaSeg->mseg_fecha_inicio = "";
                //$metaSeg->mseg_usuario_modifica = "";
                //$metaSeg->mseg_fecha_modificacion = "";
                $metaSeg->mseg_usuario_ingreso = Yii::$app->session->get('PB_iduser', FALSE);
                $metaSeg->mseg_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
                $metaSeg->mseg_estado = '1';
                $metaSeg->mseg_estado_logico = '1';
                if(!$metaSeg->save()){
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

    public static function getAllSeguimientoGrid($ind_id, $onlydata = false){
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
                    ms.mseg_id as id,
                    ifnull(ms.mseg_nombre, '') as Periodo,
                    ifnull(ms.mseg_meta, '') as MetaPeriodo,
                    ifnull(ms.mseg_numerador, '') as Numerador,
                    ifnull(ms.mseg_denominador, '') as Denominador,
                    ifnull(ms.mseg_resultado, '') as Resultado,
                    ifnull(ms.mseg_avance, '') as AvancePeriodo,
                    ifnull(ms.mseg_periodo_cerrado, '') as PeriodoCerrado,
                    ifnull(ma.mane_nombre, '') as Anexo,
                    ifnull(ma.mane_ruta, '') as RutaAnexo,
                    ifnull(ms.mseg_comentario, '') as Comentario,
                    ifnull(ma.mane_descripcion, '') as Descripcion,
                    ifnull(i.ind_meta, '') as IndicadorMeta, -- si es 1 todas las metas fueron cerradas
                    ifnull(mi.mind_meta_cerrada, '') as MetaCerrada -- si es 1 esta cerrada
                FROM 
                    ".$con->dbname.".meta_seguimiento AS ms
                    INNER JOIN ".$con->dbname.".meta_indicador AS mi ON mi.mind_id = ms.mind_id
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
                    LEFT JOIN ".$con->dbname.".meta_anexo AS ma ON ma.mseg_id = ms.mseg_id AND ma.mane_estado = 1 
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
                    ms.mseg_estado=1 AND
                    ms.mseg_estado_logico=1 AND
                    mi.mind_estado_logico
                ORDER BY ms.mseg_id;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":ind_id",$ind_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        foreach($res as $key => $value){
            if($value['IndicadorMeta'] == 0 || $value['MetaCerrada'] == 0){
                $res = array();
                break;
            }
        }
        
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
