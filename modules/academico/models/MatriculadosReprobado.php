<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\financiero\models\OrdenPago;
use \app\modules\financiero\models\DetalleDescuentoItem;
use app\models\Persona;
use app\models\Utilities;
use app\models\EmpresaPersona;
use \app\modules\admision\models\SolicitudInscripcion;
use app\modules\admision\models\Interesado;
use app\modules\admision\models\InteresadoEmpresa;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use yii\base\Security;
use app\modules\financiero\models\Secuencias;
use app\modules\admision\models\DocumentoAdjuntar;
use yii\base\Exception;

/**
 * This is the model class for table "matriculados_reprobado".
 *
 * @property int $mre_id
 * @property int $adm_id
 * @property int $mre_usuario_ingreso
 * @property int $mreusuario_modifica
 * @property string $mre_estado
 * @property string $mre_fecha_creacion
 * @property string $mre_fecha_modificacion
 * @property string $mre_estado_logico
 *
 * @property Admitido $adm
 */
class MatriculadosReprobado extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'matriculados_reprobado';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['adm_id', 'mre_usuario_ingreso', 'mre_estado', 'mre_estado_logico'], 'required'],
            [['adm_id', 'mre_usuario_ingreso', 'mreusuario_modifica'], 'integer'],
            [['mre_fecha_creacion', 'mre_fecha_modificacion'], 'safe'],
            [['mre_estado', 'mre_estado_logico'], 'string', 'max' => 1],
            [['adm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admitido::className(), 'targetAttribute' => ['adm_id' => 'adm_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'mre_id' => 'Mre ID',
            'adm_id' => 'Adm ID',
            'mre_usuario_ingreso' => 'Mre Usuario Ingreso',
            'mreusuario_modifica' => 'Mreusuario Modifica',
            'mre_estado' => 'Mre Estado',
            'mre_fecha_creacion' => 'Mre Fecha Creacion',
            'mre_fecha_modificacion' => 'Mre Fecha Modificacion',
            'mre_estado_logico' => 'Mre Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdm() {
        return $this->hasOne(Admitido::className(), ['adm_id' => 'adm_id']);
    }

    /**
     * Function getMatriculados
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (información del admitido)
     */
    public static function getMatriculados($search = NULL) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_facturacion;
        $estado = 1;
        $columnsAdd = "";
        $estado_opago = "S";
        $search_cond = $search;
        $str_search = "";

        if (isset($search)) {
            //$str_search .= "per.per_cedula = :search AND ";
            $str_search = "(per.per_pri_nombre like :search OR per.per_seg_nombre like :search  OR per.per_pri_apellido like :search OR per.per_seg_apellido like :search OR per.per_cedula = :search) AND ";
        }

        $sql = " SELECT distinct lpad(ifnull(sins.num_solicitud, sins.sins_id),9,'0') as solicitud,
                        sins.sins_id,
                        sins.int_id,
                        SUBSTRING(sins.sins_fecha_solicitud,1,10) as sins_fecha_solicitud, 
                        per.per_id as persona, 
                        per.per_pri_nombre as per_pri_nombre, 
                        per.per_seg_nombre as per_seg_nombre,
                        per.per_pri_apellido as per_pri_apellido,
                        per.per_seg_apellido as per_seg_apellido, 
                        per.per_cedula as per_cedula, 
                        per.per_correo as per_correo, 
                        per_celular as per_celular,
                        admi.adm_id,  
                        sins.ming_id, 
                        ifnull((select min.ming_alias from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as abr_metodo,
                        ifnull((select min.ming_nombre from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as ming_nombre,
                        ifnull((select uaca.uaca_nombre from " . $con3->dbname . ".unidad_academica uaca where uaca.uaca_id = sins.uaca_id),'N/A') as uaca_nombre,
                        ifnull((select moda.mod_nombre from " . $con3->dbname . ".modalidad moda where moda.mod_id = sins.mod_id),'N/A') as mod_nombre,
                        sins.eaca_id,
                        sins.mest_id,
                        case when (ifnull(sins.eaca_id,0)=0) then
                                (select mest_nombre from " . $con3->dbname . ".modulo_estudio me where me.mest_id = sins.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                                else
                            (select eaca_nombre from " . $con3->dbname . ".estudio_academico ea where ea.eaca_id = sins.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                        end as carrera,                             
                       (case when sins_beca = 1 then 'ICF' else 'No Aplica' end) as beca                       
                FROM " . $con->dbname . ".admitido admi INNER JOIN " . $con->dbname . ".interesado inte on inte.int_id = admi.int_id                     
                     INNER JOIN " . $con2->dbname . ".persona per on inte.per_id = per.per_id
                     INNER JOIN " . $con->dbname . ".solicitud_inscripcion sins on sins.int_id = inte.int_id                                          
                     INNER JOIN " . $con1->dbname . ".orden_pago opag on opag.sins_id = sins.sins_id                       
                WHERE  
                       $str_search 
                       sins.rsin_id = 2 AND
                       opag.opag_estado_pago = :estado_opago AND
                       admi.adm_estado_logico = :estado AND
                       admi.adm_estado = :estado AND 
                       inte.int_estado_logico = :estado AND
                       inte.int_estado = :estado AND     
                       per.per_estado_logico = :estado AND
                       per.per_estado = :estado AND
                       sins.sins_estado = :estado AND
                       sins.sins_estado_logico = :estado                                                    
                ORDER BY SUBSTRING(sins.sins_fecha_solicitud,1,10) desc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_opago", $estado_opago, \PDO::PARAM_STR);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        return $dataProvider;
    }

    /**
     * Function getMatriculadosreprobados
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (información del matriculado no aprobado)
     */
    public static function getMatriculadosreprobados($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_academico;     
        $estado = 1;
        $columnsAdd = "";
        $estado_opago = "S";
        //$esp = "SET lc_time_names = 'es_ES';";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "mre.mre_fecha_creacion >= :fec_ini AND ";
                $str_search .= "mre.mre_fecha_creacion <= :fec_fin AND ";
            }
            if ($arrFiltro['estadomat'] != "" && $arrFiltro['estadomat'] > 0) {
                $str_search .= "mre.mre_estado_matriculado = :estadomat AND ";                
            }
        } else {
            $columnsAdd = "-- sins.sins_id as solicitud_id,
                    per.per_id as persona, 
                    per.per_pri_nombre as per_pri_nombre, 
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,";
        }

        $sql = " 
                
                SELECT 
                    SUBSTRING(mre.mre_fecha_creacion,1,10) as mre_fecha_creacion,
                    per.per_cedula, 
                    per.per_pri_nombre, 
                    per.per_pri_apellido,
                    sins.ming_id, 
                    ifnull((select min.ming_alias from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as abr_metodo,
                    ifnull((select min.ming_nombre from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as ming_nombre,
                    ifnull((select uaca.uaca_nombre from " . $con3->dbname . ".unidad_academica uaca where uaca.uaca_id = mre.uaca_id),'N/A') as uaca_nombre,
                    ifnull((select moda.mod_nombre from " . $con3->dbname . ".modalidad moda where moda.mod_id = mre.mod_id),'N/A') as mod_nombre,
                    sins.eaca_id,
                    sins.mest_id,
                    case when (ifnull(sins.eaca_id,0)=0) then
                               (select mest_nombre from " . $con3->dbname . ".modulo_estudio me where me.mest_id = sins.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                                else
                           (select eaca_nombre from " . $con3->dbname . ".estudio_academico ea where ea.eaca_id = mre.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                    end as carrera,               
                    ifnull((select count(*) from " . $con->dbname . ".materias_matriculados_reprobado mmr where mmr.mre_id = mre.mre_id and mmr.mmr_estado_materia = 1),' ') as aprobada,
                    ifnull((SELECT GROUP_CONCAT(CONCAT(asi.asi_nombre, IF(mmr.mmr_estado_materia =1,'',''))  SEPARATOR ', ') as asignatura_apro 
                            FROM db_captacion.materias_matriculados_reprobado mmr
                            INNER JOIN  db_academico.asignatura asi ON asi.asi_id = mmr.asi_id
                            where mmr.mre_id = mre.mre_id and mmr.mmr_estado_materia =1),'') as asignatura_apro,
                    ifnull((select count(*) from " . $con->dbname . ".materias_matriculados_reprobado mmr where mmr.mre_id = mre.mre_id and mmr.mmr_estado_materia = 2),' ') as reprobada,
                    ifnull((SELECT GROUP_CONCAT(CONCAT(asi.asi_nombre, IF(mmr.mmr_estado_materia =2,'',''))  SEPARATOR ', ') as asignatura_repro 
                            FROM db_captacion.materias_matriculados_reprobado mmr
                            INNER JOIN  db_academico.asignatura asi ON asi.asi_id = mmr.asi_id
                            where mmr.mre_id = mre.mre_id and mmr.mmr_estado_materia =2),'') as asignatura_repro,
                    case mre.mre_estado_matriculado
                         when 1 then 'Contacto'
                         when 2 then 'Cursando'
                    end as estado_matriculado        
                FROM " . $con->dbname . ".matriculados_reprobado mre 
                     INNER JOIN " . $con->dbname . ".admitido adm ON adm.adm_id = mre.adm_id
                     INNER JOIN " . $con->dbname . ".interesado inte on inte.int_id = adm.int_id                     
                     INNER JOIN " . $con2->dbname . ".persona per on inte.per_id = per.per_id 
                     INNER JOIN " . $con->dbname . ".solicitud_inscripcion sins on sins.int_id = inte.int_id                     
                WHERE  
                       $str_search   
                       adm.adm_estado_logico = :estado AND
                       adm.adm_estado = :estado AND 
                       inte.int_estado_logico = :estado AND
                       inte.int_estado = :estado AND     
                       per.per_estado_logico = :estado AND
                       per.per_estado = :estado AND                       
                       mre.mre_estado = :estado AND
                       mre.mre_estado_logico = :estado
                ORDER BY mre.mre_fecha_creacion desc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_opago", $estado_opago, \PDO::PARAM_STR);
        //$comando->bindParam(":esp",$esp, \PDO::PARAM_STR); 
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $estadomat = $arrFiltro["estadomat"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['estadomat'] != "" && $arrFiltro['estadomat'] > 0) {
                $comando->bindParam(":estadomat", $estadomat, \PDO::PARAM_INT);
                
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function consultarMatriculareprueba
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @param   
     * @return  $resultData (información del matriculado no aprobado)
     */
    public function consultarMateriasPorUnidadModalidadCarrera($uaca_id, $moda_id, $car_id, $mes, $anio) {
        //DETERMINAR COMO SE VA A ENVIAR AHORA LA UNIDAD, MODALIDAD Y CARRERA
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_facturacion;
        $estado = 1;
        if ($uaca_id == 1 && $moda_id == 1 && $mes > 1 && $anio > 2017) {
            $str_filtro = 'asig.asi_id < 4 and ';
        }
        $sql = " 
                SELECT 
                    asig.asi_id as id,
                    asig.asi_descripcion
                FROM 
                    " . $con3->dbname . ".malla_academica as maca
                    JOIN " . $con3->dbname . ".malla_academica_detalle as made on made.maca_id=maca.maca_id
                    JOIN " . $con3->dbname . ".asignatura as asig on asig.asi_id=made.asi_id
                WHERE 
                    $str_filtro
                    /* maca.uaca_id=:uaca_id and
                    maca.eaca_id=:car_id and
                    maca.mod_id=:moda_id and */
                    maca.maca_estado =:estado and 
                    maca.maca_estado_logico =:estado and
                    made.made_estado =:estado and 
                    made.made_estado_logico =:estado                    
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":moda_id", $moda_id, \PDO::PARAM_INT);
        $comando->bindParam(":car_id", $car_id, \PDO::PARAM_INT);   
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function consultarMatriculareprueba
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (información del matriculado no aprobado)
     */
    public static function consultarMatriculareprueba($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_facturacion;
        $estado = 1;
        $columnsAdd = "";
        $estado_opago = "S";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "mre.mre_fecha_creacion >= :fec_ini AND ";
                $str_search .= "mre.mre_fecha_creacion <= :fec_fin AND ";
            }
            if ($arrFiltro['estadomat'] != "" && $arrFiltro['estadomat'] > 0) {
                $str_search .= "mre.mre_estado_matriculado = :estadomat AND ";                
            }
        } else {
            $columnsAdd = "-- sins.sins_id as solicitud_id,
                    per.per_id as persona, 
                    per.per_pri_nombre as per_pri_nombre, 
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,";
        }

        $sql = " 
                SELECT                  
                    per.per_cedula, 
                    per.per_pri_nombre, 
                    per.per_seg_nombre,
                    per.per_pri_apellido, 
                    per.per_seg_apellido,
                    per.per_correo,
                    per.per_celular,
                    ifnull((select uaca.uaca_nombre from " . $con3->dbname . ".unidad_academica uaca where uaca.uaca_id = mre.uaca_id),'N/A') as uaca_nombre,
                    ifnull((select moda.mod_nombre from " . $con3->dbname . ".modalidad moda where moda.mod_id = mre.mod_id),'N/A') as mod_nombre, 
                    case when (ifnull(sins.eaca_id,0)=0) then
                               (select mest_nombre from " . $con3->dbname . ".modulo_estudio me where me.mest_id = sins.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                                else
                           (select eaca_nombre from " . $con3->dbname . ".estudio_academico ea where ea.eaca_id = mre.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                    end as carrera,
                    ifnull((select min.ming_nombre from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as ming_nombre,
                    ifnull((SELECT GROUP_CONCAT(CONCAT(asi.asi_nombre, '(', IF(mmr.mmr_estado_materia =1,'Aprobado','Reprobado'), ')')  SEPARATOR ', ') as asignaturas 
                            FROM " . $con->dbname . ".materias_matriculados_reprobado mmr
                            INNER JOIN  " . $con3->dbname . ".asignatura asi ON asi.asi_id = mmr.asi_id
                            where mmr.mre_id = mre.mre_id),'') as asignaturas,
                    case mre.mre_estado_matriculado
                         when 1 then 'Contacto'
                         when 2 then 'Cursando'
                    end as estado_matriculado  
                FROM " . $con->dbname . ".matriculados_reprobado mre 
                     INNER JOIN " . $con->dbname . ".admitido adm ON adm.adm_id = mre.adm_id
                     INNER JOIN " . $con->dbname . ".interesado inte on inte.int_id = adm.int_id                     
                     INNER JOIN " . $con2->dbname . ".persona per on inte.per_id = per.per_id 
                     INNER JOIN " . $con->dbname . ".solicitud_inscripcion sins on sins.int_id = inte.int_id                    
                WHERE  
                       $str_search   
                       adm.adm_estado_logico = :estado AND
                       adm.adm_estado = :estado AND 
                       inte.int_estado_logico = :estado AND
                       inte.int_estado = :estado AND     
                       per.per_estado_logico = :estado AND
                       per.per_estado = :estado AND                       
                       mre.mre_estado = :estado AND
                       mre.mre_estado_logico = :estado
                ORDER BY mre.mre_fecha_creacion desc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_opago", $estado_opago, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $estadomat = $arrFiltro["estadomat"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['estadomat'] != "" && $arrFiltro['estadomat'] > 0) {
                $comando->bindParam(":estadomat", $estadomat, \PDO::PARAM_INT);
                
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function insertarReprobado crea reprobado matriculado.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarMatricureprobado($adm_id, $pami_id, $sins_id, $uaca_id, $mod_id, $eaca_id, $mre_usuario_ingreso, $mre_estado_matriculado, $mre_fecha_creacion) {
        $con = \Yii::$app->db_captacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "mre_estado";
        $bdet_sql = "1";

        $param_sql .= ", mre_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($adm_id)) {
            $param_sql .= ", adm_id";
            $bdet_sql .= ", :adm_id";
        }
        if (isset($pami_id)) {
            $param_sql .= ", pami_id";
            $bdet_sql .= ", :pami_id";
        }
        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bdet_sql .= ", :sins_id";
        }       
        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bdet_sql .= ", :uaca_id";
        }
        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bdet_sql .= ", :mod_id";
        }
        if (isset($eaca_id)) {
            $param_sql .= ", eaca_id";
            $bdet_sql .= ", :eaca_id";
        }        
        if (isset($mre_usuario_ingreso)) {
            $param_sql .= ", mre_usuario_ingreso";
            $bdet_sql .= ", :mre_usuario_ingreso";
        }
        if (isset($mre_estado_matriculado)) {
            $param_sql .= ", mre_estado_matriculado";
            $bdet_sql .= ", :mre_estado_matriculado";
        }
        if (isset($mre_fecha_creacion)) {
            $param_sql .= ", mre_fecha_creacion";
            $bdet_sql .= ", :mre_fecha_creacion";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".matriculados_reprobado ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($adm_id)) {
                $comando->bindParam(':adm_id', $adm_id, \PDO::PARAM_INT);
            }
            if (isset($pami_id)) {
                $comando->bindParam(':pami_id', $pami_id, \PDO::PARAM_INT);
            }
            if (isset($sins_id)) {
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);
            }
            if (isset($uaca_id)) {
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);
            }
            if (isset($mod_id)) {
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);
            }
            if (isset($eaca_id)) {
                $comando->bindParam(':eaca_id', $eaca_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($mre_usuario_ingreso)))) {
                $comando->bindParam(':mre_usuario_ingreso', $mre_usuario_ingreso, \PDO::PARAM_INT);
            }
            if (!empty((isset($mre_estado_matriculado)))) {
                $comando->bindParam(':mre_estado_matriculado', $mre_estado_matriculado, \PDO::PARAM_INT);
            }
            if (!empty((isset($mre_fecha_creacion)))) {
                $comando->bindParam(':mre_fecha_creacion', $mre_fecha_creacion, \PDO::PARAM_STR);
            }

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.matriculados_reprobado');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function insertarReprobado crea reprobado matriculado.
     * @author  Kleber Loayza <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarReprobadoTemp($con, $data) {
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $ruta_doc_titulo = '';
        $ruta_doc_dni = '';
        $ruta_doc_certvota = '';
        $ruta_doc_foto = '';
        $ruta_doc_certificado = '';
        $twin_mensaje1 = 0;
        $twin_mensaje2 = 0;
        try {
            $sql = "INSERT INTO " . $con->dbname . ".temporal_wizard_reprobados
                (twre_nombre,twre_apellido,twre_dni,twre_numero,twre_correo,twre_pais,twre_celular,
                uaca_id, mod_id,car_id,twre_metodo_ingreso,conuteg_id,ruta_doc_titulo, 
                ruta_doc_dni, ruta_doc_certvota, ruta_doc_foto,ruta_doc_certificado, 
                twre_mensaje1,twre_mensaje2, 
                twre_fecha_solicitud, sdes_id, ite_id, 
                twre_precio_item, twre_precio_descuento, twre_observacion_sol,
                twre_estado,twre_fecha_creacion,twre_estado_logico)
                VALUES
                (:twre_nombre,:twre_apellido,:twre_dni,:twre_numero,:twre_correo,:twre_pais,
                :twre_celular,:uaca_id, :mod_id,:car_id,:twre_metodo_ingreso,:conuteg_id,
                :ruta_doc_titulo,:ruta_doc_dni,:ruta_doc_certvota, :ruta_doc_foto,
                :ruta_doc_certificado,:twre_mensaje1,:twre_mensaje2,
                :twre_fecha_solicitud, :sdes_id, :ite_id,
                :twre_precio_item, :twre_precio_descuento, :twre_observacion_sol,
                1,CURRENT_TIMESTAMP(),1)";

            $command = $con->createCommand($sql);
            $command->bindParam(":twre_nombre", $data[0]['pges_pri_nombre'], \PDO::PARAM_STR);
            $command->bindParam(":twre_apellido", $data[0]['pges_pri_apellido'], \PDO::PARAM_STR);
            $command->bindParam(":twre_dni", $data[0]['tipo_dni'], \PDO::PARAM_STR);
            $command->bindParam(":twre_numero", $data[0]['pges_cedula'], \PDO::PARAM_STR);
            $command->bindParam(":twre_correo", $data[0]['pges_correo'], \PDO::PARAM_STR);
            $command->bindParam(":twre_pais", $data[0]['pais'], \PDO::PARAM_STR);
            $command->bindParam(":twre_celular", $data[0]['pges_celular'], \PDO::PARAM_STR);
            $command->bindParam(":uaca_id", $data[0]['unidad_academica'], \PDO::PARAM_STR);
            $command->bindParam(":mod_id", $data[0]['modalidad'], \PDO::PARAM_STR);
            $command->bindParam(":car_id", $data[0]['carrera'], \PDO::PARAM_STR);
            $command->bindParam(":twre_metodo_ingreso", $data[0]['ming_id'], \PDO::PARAM_STR);
            $command->bindParam(":conuteg_id", $data[0]['carrera'], \PDO::PARAM_INT); // COLOCAR EL VALOR CORRECTO
            $command->bindParam(":ruta_doc_titulo", $ruta_doc_titulo, \PDO::PARAM_STR);
            $command->bindParam(":ruta_doc_dni", $ruta_doc_dni, \PDO::PARAM_STR);
            $command->bindParam(":ruta_doc_certvota", $ruta_doc_certvota, \PDO::PARAM_STR);
            $command->bindParam(":ruta_doc_foto", $ruta_doc_foto, \PDO::PARAM_STR);
            $command->bindParam(":ruta_doc_certificado", $ruta_doc_certificado, \PDO::PARAM_STR);
            $command->bindParam(":twre_fecha_solicitud", $data[0]['fecha_solicitud'], \PDO::PARAM_STR);
            $command->bindParam(":sdes_id", $data[0]['sdes_id'], \PDO::PARAM_INT);
            $command->bindParam(":ite_id", $data[0]['ite_id'], \PDO::PARAM_INT);
            $command->bindParam(":twre_precio_item", $data[0]['precio_item'], \PDO::PARAM_STR);
            $command->bindParam(":twre_precio_descuento", $data[0]['precio_item_desc'], \PDO::PARAM_STR);
            $command->bindParam(":twre_observacion_sol", $data[0]['observacionw'], \PDO::PARAM_STR);
            $command->bindParam(":twre_mensaje1", $twin_mensaje1, \PDO::PARAM_STR);
            $command->bindParam(":twre_mensaje2", $twin_mensaje2, \PDO::PARAM_STR);
            $command->execute();
            $id = $con->getLastInsertID($con->dbname . '.temporal_wizard_reprobados');
            if ($trans !== null)
                $trans->commit();
            if ($id)
                return ["status" => true, "twre_id" => $id];
            else
                return ["status" => false, "twre_id" => 0];
        } catch (Exception $ex) {
            \app\models\Utilities::putMessageLogFile($ex->getMessage());            
            if ($trans !== null)
                $trans->rollback();
            return ["status" => false, "twre_id" => 0];
            ;
        }
    }

    public function actualizarReprobadoTemp($con, $id, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $params_sql = "";
        for ($i = 0; $i < (count($parameters) - 1); $i++) {
            if (isset($parameters[$i])) {
                $params_sql .= $keys[$i] . " = '" . $parameters[$i] . "',";
            }
        }
        $params_sql .= $keys[count($parameters) - 1] . " = '" . $parameters[count($parameters) - 1] . "'";
        try {
            $sql = "UPDATE " . $con->dbname . '.' . $name_table .
                    " SET $params_sql" .
                    " WHERE twre_id=$id";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $trans->commit();
            return ["status" => true, "twre_id" => $id];
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return ["status" => false, "twre_id" => $id];
        }
    }

    /**
     * Function addLabelTimeDocumentos renombra el documento agregando una varible de tiempo 
     * @author  Kleber Loayza Analista Desarrollo 3 <analistadesarrollo03@uteg.edu.ec>
     * @param   int     $matre_id        Id del matriculado
     * @param   string  $file           Uri del Archivo a modificar
     * @param   int     $timeSt         Parametro a agregar al nombre del archivo
     * @return  $newFile | FALSE (Retorna el nombre del nuevo archivo o false si fue error).
     */
    public static function addLabelTimeDocumentos($matre_id, $file, $timeSt) {
        $arrIm = explode(".", basename($file));
        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
        $baseFile = Yii::$app->basePath;
        $search = ".$typeFile";
        $replace = "_$timeSt" . ".$typeFile";
        $newFile = str_replace($search, $replace, $file);
        if (rename($baseFile . $file, $baseFile . $newFile)) {
            return $newFile;
        }
        return FALSE;
    }

    /**
     * Function consultarReprobado
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (consultar si esta repetido la solicitud del admitido.)
     */
    public function consultarReprobado($sins_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT count(*) encontrado	
                FROM " . $con->dbname . ".matriculados_reprobado mre
                WHERE mre.sins_id = :sins_id AND                          
                      mre.mre_estado = :estado AND
                      mre.mre_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarMateriareprueba crea materias reprobado matriculado.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarMateriareprueba($mre_id, $asi_id, $mmr_estado_materia, $mmr_usuario_ingreso, $mmr_fecha_creacion) {
        $con = \Yii::$app->db_captacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "mmr_estado";
        $bdet_sql = "1";

        $param_sql .= ", mmr_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($mre_id)) {
            $param_sql .= ", mre_id";
            $bdet_sql .= ", :mre_id";
        }
        if (isset($asi_id)) {
            $param_sql .= ", asi_id";
            $bdet_sql .= ", :asi_id";
        }
        if (isset($mmr_estado_materia)) {
            $param_sql .= ", mmr_estado_materia";
            $bdet_sql .= ", :mmr_estado_materia";
        }
        if (isset($mmr_usuario_ingreso)) {
            $param_sql .= ", mmr_usuario_ingreso";
            $bdet_sql .= ", :mmr_usuario_ingreso";
        }
        if (isset($mmr_fecha_creacion)) {
            $param_sql .= ", mmr_fecha_creacion";
            $bdet_sql .= ", :mmr_fecha_creacion";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".materias_matriculados_reprobado ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($mre_id)) {
                $comando->bindParam(':mre_id', $mre_id, \PDO::PARAM_INT);
            }
            if (isset($asi_id)) {
                $comando->bindParam(':asi_id', $asi_id, \PDO::PARAM_INT);
            }
            if (isset($mmr_estado_materia)) {
                $comando->bindParam(':mmr_estado_materia', $mmr_estado_materia, \PDO::PARAM_STR);
            }
            if (!empty((isset($mmr_usuario_ingreso)))) {
                $comando->bindParam(':mmr_usuario_ingreso', $mmr_usuario_ingreso, \PDO::PARAM_INT);
            }
            if (!empty((isset($mmr_fecha_creacion)))) {
                $comando->bindParam(':mmr_fecha_creacion', $mmr_fecha_creacion, \PDO::PARAM_STR);
            }

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.materias_matriculados_reprobado');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarMateriarep
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (información de materias)
     */
    public function consultarMateriarep($uaca_id, $moda_id, $car_id, $reprobar, $mes, $anio) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        if ($uaca_id == 1 && $moda_id == 1 && $mes > 1 && $anio > 2017) {
            $str_filtro = 'asig.asi_id < 4 and ';
        }
        $sql = " 
                select 
                    asig.asi_id as id 
                from 
                    " . $con->dbname . ".malla_academica as maca
                    join " . $con->dbname . ".malla_academica_detalle as made on made.maca_id=maca.maca_id
                    join " . $con->dbname . ".asignatura as asig on asig.asi_id=made.asi_id
                where
                    $reprobar
                    $str_filtro
                    maca.uaca_id=:uaca_id and
                    maca.eaca_id=:car_id and
                    maca.mod_id=:moda_id and 
                    maca.maca_estado =:estado and 
                    maca.maca_estado_logico =:estado and
                    made.made_estado =:estado and 
                    made.made_estado_logico =:estado
                    
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":moda_id", $moda_id, \PDO::PARAM_INT);
        $comando->bindParam(":car_id", $car_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Function consultarDatosInscripcion
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @param   
     * @return  $resultData (Obtiene los datos de inscripción y el precio de la solicitud.)
     */
    public function consultarDatosInscripcion($twre_id) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_precio = 'A';

        $sql = "
                SELECT  
                        ua.uaca_nombre unidad, 
                        m.mod_nombre modalidad,
                        ea.eaca_nombre carrera,
                        ea.eaca_id as id_carrera,
                        mi.ming_nombre metodo,
                        ip.ipre_precio as precio,
                        twre_nombre,
                        twre_apellido,
                        twre_numero,
                        twre_correo,
                        twre_precio_item,
                        twi.sdes_id,
                        twi.ite_id,
                        twre_precio_descuento,
                        twre_beca,
                        twre_observacion_sol,
                        twre_pais,
                        twre_celular,
                        twi.uaca_id,
                        twi.mod_id,
                        twi.car_id,
                        twre_metodo_ingreso,
                        conuteg_id,
                        ruta_doc_titulo,
                        ruta_doc_dni,
                        96 as ddit_valor,
                        ruta_doc_certvota,
                        ruta_doc_foto,
                        ruta_doc_certificado,                        
                        ruta_doc_hojavida,
                        twre_dni,
                        twre_fecha_solicitud
                FROM " . $con->dbname . ".temporal_wizard_reprobados twi 
                     inner join " . $con1->dbname . ".unidad_academica ua on ua.uaca_id = twi.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = twi.mod_id
                     inner join " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = twi.car_id
                     inner join " . $con->dbname . ".metodo_ingreso mi on mi.ming_id = twi.twre_metodo_ingreso
                     inner join " . $con2->dbname . ".item_metodo_unidad imi on (imi.ming_id =  twi.twre_metodo_ingreso and imi.uaca_id = twi.uaca_id and imi.mod_id = twi.mod_id)
                     left join " . $con2->dbname . ".item_precio ip on ip.ite_id = imi.ite_id
                     left join " . $con2->dbname . ".descuento_item as ditem on ditem.ite_id=imi.ite_id
                     left join " . $con2->dbname . ".detalle_descuento_item as ddit on ddit.dite_id=ditem.dite_id
                WHERE twi.twre_id = :twre_id AND                     
                     ip.ipre_estado_precio = :estado_precio AND
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado AND
                     mi.ming_estado = :estado AND
                     mi.ming_estado_logico = :estado AND
                     imi.imni_estado = :estado AND
                     imi.imni_estado_logico = :estado AND
                     ip.ipre_estado = :estado AND
                     ip.ipre_estado_logico = :estado
                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":twre_id", $twre_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertaOriginal($twreIds) {
        $con = \Yii::$app->db_asgard;
        $con1 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_facturacion;
        $usuario_ingreso = @Yii::$app->session->get("PB_iduser");
        $transaction = $con->beginTransaction();
        $transaction1 = $con1->beginTransaction();
        $transaction2 = $con2->beginTransaction();
        try {
            //Se consulta la información grabada en la tabla temporal.
            $resp_datos = $this->consultarDatosInscripcion($twreIds);
            // He colocado al inicio la informacion para que cargue al principio
            if ($resp_datos) {
                $emp_id = 1;
                $identificacion = '';

                if (isset($resp_datos['twre_numero']) && strlen($resp_datos['twre_numero']) > 0) {
                    $identificacion = $resp_datos['twre_numero'];
                } else {
                    $identificacion = $resp_datos['twre_numero'];
                }
                if (isset($identificacion) && strlen($identificacion) > 0) {
                    $id_persona = 0;                    
                    $mod_persona = new Persona();
                    $keys_per = [
                        'per_pri_nombre', 'per_seg_nombre', 
                        'per_pri_apellido', 'per_seg_apellido',
                        'per_cedula', 'etn_id', 'eciv_id', 'per_genero', 'pai_id_nacimiento',
                        'pro_id_nacimiento', 'can_id_nacimiento', 'per_fecha_nacimiento',
                        'per_celular', 'per_correo', 
                        'tsan_id', 'per_domicilio_sector',
                        'per_domicilio_cpri', 'per_domicilio_csec', 'per_domicilio_num',
                        'per_domicilio_ref', 'per_domicilio_telefono', 'pai_id_domicilio',
                        'pro_id_domicilio', 'can_id_domicilio', 'per_nac_ecuatoriano',
                        'per_nacionalidad', 'per_foto', 'per_usuario_ingresa','per_usuario_modifica', 'per_estado', 'per_estado_logico'
                    ];
                    $parametros_per = [
                        ucwords(strtolower($resp_datos['twre_nombre'])), null,
                        ucwords(strtolower($resp_datos['twre_apellido'])), null,
                        $resp_datos['twre_numero'], null, null, null, $resp_datos['twre_pais'], 
                        null,null, null, $resp_datos['twre_celular'], $resp_datos['twre_correo'],
                        null, null, null, null,
                        null, null, null,
                        null, null, null,
                        null, null, null, $usuario_ingreso, 0,1, 1
                    ];
                    $id_persona = $mod_persona->consultarIdPersona($resp_datos['twre_numero'], $resp_datos['twre_numero'], $resp_datos['twre_correo'], $resp_datos['twre_celular']);
                    if ($id_persona == 0) {
                        $id_persona = $mod_persona->insertarPersona($con, $parametros_per, $keys_per, 'persona');
                    }
                    if ($id_persona > 0) {
                        \app\models\Utilities::putMessageLogFile('Ingreso persona');
                        //Modifificaion para Mover Imagenes de temp a Persona
                        //self::movePersonFiles($twinIds,$id_persona);
                        $concap = \Yii::$app->db_captacion;
                        $mod_emp_persona = new EmpresaPersona();
                        $keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
                        $parametros = [$emp_id, $id_persona, 1, 1];
                        $emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($id_persona, $emp_id);
                        if ($emp_per_id == 0) {
                            $emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con, $parametros, $keys, 'empresa_persona');
                        }
                        if ($emp_per_id > 0) {
                            \app\models\Utilities::putMessageLogFile('Ingreso empresa');
                            $usuario = new Usuario();
                            $usuario_id = $usuario->consultarIdUsuario($id_persona, $resp_datos['twre_correo']);
                            if ($usuario_id == 0) {
                                $security = new Security();
                                $hash = $security->generateRandomString();
                                $passencrypt = base64_encode($security->encryptByPassword($hash, 'Uteg2018'));
                                $keys = ['per_id', 'usu_user', 'usu_sha', 'usu_password', 'usu_estado', 'usu_estado_logico'];
                                $parametros = [$id_persona, $resp_datos['twre_correo'], $hash, $passencrypt, 1, 1];
                                $usuario_id = $usuario->crearUsuarioTemporal($con, $parametros, $keys, 'usuario');
                            }
                            if ($usuario_id > 0) {
                                \app\models\Utilities::putMessageLogFile('Ingreso usuario');
                                $mod_us_gr_ep = new UsuaGrolEper();
                                $grol_id = 30;
                                $keys = ['eper_id', 'usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'];
                                $parametros = [$emp_per_id, $usuario_id, $grol_id, 1, 1];
                                $us_gr_ep_id = $mod_us_gr_ep->consultarIdUsuaGrolEper($emp_per_id, $usuario_id, $grol_id);
                                if ($us_gr_ep_id == 0)
                                    $us_gr_ep_id = $mod_us_gr_ep->insertarUsuaGrolEper($con, $parametros, $keys, 'usua_grol_eper');
                                if ($us_gr_ep_id > 0) {
                                    $mod_interesado = new Interesado(); // se guarda con estado_interesado 1
                                    $interesado_id = $mod_interesado->consultaInteresadoById($id_persona);
                                    $keys = ['per_id', 'int_estado_interesado', 'int_usuario_ingreso', 'int_estado', 'int_estado_logico'];
                                    $parametros = [$id_persona, 1, $usuario_id, 1, 1];
                                    if ($interesado_id == 0) {
                                        $interesado_id = $mod_interesado->insertarInteresado($concap, $parametros, $keys, 'interesado');
                                    }
                                    if ($interesado_id > 0) {
                                        \app\models\Utilities::putMessageLogFile('Ingreso interesado');
                                        $mod_inte_emp = new InteresadoEmpresa(); // se guarda con estado_interesado 1
                                        $iemp_id = $mod_inte_emp->consultaInteresadoEmpresaById($interesado_id, $emp_id);
                                        if ($iemp_id == 0) {
                                            $iemp_id = $mod_inte_emp->crearInteresadoEmpresa($interesado_id, $emp_id, $usuario_id);
                                        }
                                        if ($iemp_id > 0) {
                                            $eaca_id = NULL;
                                            $mest_id = NULL;
                                            if ($emp_id == 1) {//Uteg 
                                                $eaca_id = $resp_datos['car_id'];
                                            } elseif ($emp_id == 2 || $emp_id == 3) {
                                                $mest_id = $resp_datos['car_id'];
                                            }
                                            $num_secuencia = Secuencias::nuevaSecuencia($con, $emp_id, 1, 1, 'SOL');
                                            $sins_fechasol = date(Yii::$app->params["dateTimeByDefault"]);
                                            $rsin_id = 1; //Solicitud pendiente     
                                            $solins_model = new SolicitudInscripcion();
                                            //$mensaje = 'intId: ' . $interesado_id . '/uaca: ' . $pgest['unidad_academica'] . '/modalidad: ' . $pgest['modalidad'] . '/ming: ' . $pgest['ming_id'] . '/eaca: ' . $eaca_id . '/mest: ' . $mest_id . '/empresa: ' . $emp_id . '/secuencia: ' . $num_secuencia . '/rsin_id: ' . $rsin_id . '/sins_fechasol: ' . $sins_fechasol . '/usuario_id: ' . $usuario_id;
                                            \app\models\Utilities::putMessageLogFile('insertar solicitud');
                                            $sins_id = $solins_model->insertarSolicitud($interesado_id, $resp_datos['uaca_id'], $resp_datos['mod_id'], $resp_datos['twre_metodo_ingreso'], $eaca_id, null, $emp_id, $num_secuencia, $rsin_id, $resp_datos['twre_fecha_solicitud'], $usuario_id);
                                            //grabar los documentos
                                            if ($sins_id) {
                                                \app\models\Utilities::putMessageLogFile('solicitud ingresada');
                                                if (($resp_datos['ruta_doc_titulo'] != "") || ($resp_datos['ruta_doc_dni'] != "") || ($resp_datos['ruta_doc_certvota'] != "") || ($resp_datos['ruta_doc_foto'] != "") || ($resp_datos['ruta_doc_certificado'] != "") || ($resp_datos['ruta_doc_hojavida'] != "")) {
                                                    $subidaDocumentos = 1;
                                                } else {
                                                    $subidaDocumentos = 0;
                                                }
                                                if ($resp_datos['ruta_doc_titulo'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_titulo']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_titulo']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaTitulo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_titulo_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc1 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 1, $rutaTitulo, $usuario_id);
                                                    /* if (!($resulDoc1)) {
                                                      throw new Exception('Error doc Titulo no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['ruta_doc_dni'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_dni']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_dni']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaDni = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_dni_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc2 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 2, $rutaDni, $usuario_id);
                                                    /* if (!($resulDoc2)) {
                                                      throw new Exception('Error doc Titulo no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['ruta_doc_certvota'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_certvota']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_certvota']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaCertvota = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_certvota_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc3 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 3, $rutaCertvota, $usuario_id);
                                                    /* if (!($resulDoc3)) {
                                                      throw new Exception('Error doc Cert.Votación no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['ruta_doc_foto'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_foto']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_foto']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaFoto = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_foto_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc4 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 4, $rutaFoto, $usuario_id);
                                                    /* if (!($resulDoc4)) {
                                                      throw new Exception('Error doc Foto no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['twre_metodo_ingreso'] == 4) {
                                                    if ($resp_datos['ruta_doc_certificado'] != "") {
                                                        $arrIm = explode(".", basename($resp_datos['ruta_doc_certificado']));
                                                        $arrTime = explode("_", basename($resp_datos['ruta_doc_certificado']));
                                                        $timeSt = $arrTime[4];
                                                        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                        $rutaCertificado = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_certificado_per_" . $id_persona . "_" . $timeSt;
                                                        $resulDoc5 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 6, $rutaCertificado, $usuario_id);
                                                        /* if (!($resulDoc5)) {
                                                          throw new Exception('Error doc Certificado no creado.');
                                                          } */
                                                    }
                                                    if ($resp_datos['ruta_doc_hojavida'] != "") {
                                                        $arrIm = explode(".", basename($resp_datos['ruta_doc_hojavida']));
                                                        $arrTime = explode("_", basename($resp_datos['ruta_doc_hojavida']));
                                                        $timeSt = $arrTime[4];
                                                        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                        $rutaHojaVida = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_hojavida_per_" . $id_persona . "_" . $timeSt;
                                                        $resulDoc6 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 7, $rutaHojaVida, $usuario_id);
                                                        /* if (!($resulDoc6)) {
                                                          throw new Exception('Error doc Hoja de Vida no creado.');
                                                          } */
                                                    }
                                                }
                                                \app\models\Utilities::putMessageLogFile('va a preguntar si tiene beca');
                                                //Obtener el precio de la solicitud.
                                                $beca=$resp_datos['beca'];                                                
                                                if ($beca == "1") {                                                    
                                                    $precio = 0;
                                                } else {
                                                    $precio = $resp_datos['twre_precio_item'];
                                                }
                                                $mod_ordenpago = new OrdenPago();
                                                //Se verifica si seleccionó descuento.
                                                //descuento para grado online y posgrado no tiene descuento, caso contrario es 96 dol
                                                if ($resp_datos['uaca_id'] == 1) {
                                                    if (($resp_datos['mod_id'] == 2) or ( $resp_datos['mod_id'] == 3) or ( $resp_datos['mod_id'] == 4)) {
                                                        $val_descuento = 96;
                                                    }
                                                }
                                                //Generar la orden de pago con valor correspondiente. Buscar precio para orden de pago.                                                                     
                                                if ($precio == 0) {
                                                    $estadopago = 'S';
                                                } else {
                                                    $estadopago = 'P';
                                                }                                                
                                                if($resp_datos['sdes_id']==0){
                                                    $val_total = $resp_datos['twre_precio_item'];
                                                }else{
                                                    $val_total = $resp_datos['twre_precio_descuento'];
                                                }
                                                \app\models\Utilities::putMessageLogFile('valor orden: '.$val_total);                                                
                                                $resp_opago = $mod_ordenpago->insertarOrdenpago($sins_id, null, $val_total, 0, $val_total, $estadopago, $usuario_id);
                                                if ($resp_opago) {
                                                    \app\models\Utilities::putMessageLogFile('va a insertar la desglose pago');
                                                    //insertar desglose del pago                                    
                                                    $resp_dpago = $mod_ordenpago->insertarDesglosepago($resp_opago,$resp_datos['ite_id'], $val_total, 0, $val_total, $resp_datos['twre_fecha_solicitud'], null, $estadopago, $usuario_id);
                                                    if ($resp_dpago) {
                                                        $exito = 1;
                                                        if ($resp_datos['sdes_id'] > 0) {
                                                            \app\models\Utilities::putMessageLogFile('descuento:'. $resp_datos['sdes_id']);
                                                            \app\models\Utilities::putMessageLogFile('fecha:'. $resp_datos['twre_fecha_solicitud']);
                                                            $detDescitem=new DetalleDescuentoItem();                                                            
                                                            $respDescuento=$detDescitem->consultarHistoricodctoXitem($resp_datos['sdes_id'],$resp_datos['twre_fecha_solicitud']);
                                                            \app\models\Utilities::putMessageLogFile('porcentaje descuento:' . $respDescuento["hdit_porcentaje"]);
                                                            \app\models\Utilities::putMessageLogFile('valor descuento:' . $respDescuento["hdit_valor"]);                                                            
                                                            $resp_SolicDcto = $mod_ordenpago->insertarSolicDscto($sins_id, $resp_datos['sdes_id'], $resp_datos['twre_precio_item'], $respDescuento["hdit_porcentaje"], $respDescuento["hdit_valor"]);
                                                            if ($resp_SolicDcto) {
                                                                $exito = 1;
                                                            } else {
                                                                $exito = 0;
                                                            }
                                                        }                                                        
                                                    }
                                                }
                                            }
                                        } else {
                                            $error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
                                            $error++;
                                        }
                                    } else {
                                        $error_message .= Yii::t("formulario", "The interested person hasn't been saved");
                                        $error++;
                                    }
                                } else {
                                    $error_message .= Yii::t("formulario", "The rol user have not been saved");
                                    $error++;
                                }
                            } else {
                                $error_message .= Yii::t("formulario", "The user have not been saved");
                                $error++;
                            }
                        } else {
                            $error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
                            $error++;
                        }
                    } else {
                        $error++;
                        $error_message .= Yii::t("formulario", "The person have not been saved");
                    }
                } else {
                    $error_message .= Yii::t("formulario", "Update DNI to generate interested");
                    $error++;
                }
            } else {
                $error_message .= Yii::t("formulario", "No existen datos para registrar.");
                $error++;
            }
            if ($exito == 1) {
                //$transaction->commit();
                //$transaction1->commit(); 
                $transaction2->commit();
                $message = array(
                    "wtmessage" => Yii::t("formulario", "The information have been saved and the information has been sent to your email"),
                    "title" => Yii::t('jslang', 'Success'),
                );
                //Modifificaion para Mover Imagenes de temp a Persona
                if ($subidaDocumentos == 1) {
                    \app\models\Utilities::putMessageLogFile('Se esta moviendo los archivos a la nueva ubicacion');
                    self::movePersonFiles($twreIds, $id_persona);
                }
                //return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = $message;
                $arroout["data"] = $resp_datos; //$rawData;
                return $arroout;
            } else {
                //$transaction->rollback();
                //$transaction1->rollback();
                $transaction2->rollback();
                $message = array(
                    "wtmessage" => Yii::t("formulario", "Mensaje1: " . $mensaje), //$error_message
                    "title" => Yii::t('jslang', 'Bad Request'),
                );
                $arroout["status"] = FALSE;
                $arroout["error"] = null;
                $arroout["message"] = $message;
                $arroout["data"] = null;
                return $arroout;
                //return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
            }
        } catch (Exception $ex) {
            //$transaction->rollback();
            //$transaction1->rollback();
            $transaction2->rollback();
            $message = array(
                "wtmessage" => Yii::t("formulario", "Mensaje2: " . $mensaje), //$error_message
                "title" => Yii::t('jslang', 'Bad Request'),
            );
            $arroout["status"] = FALSE;
            $arroout["error"] = $ex->getCode();
            $arroout["message"] = $ex->getMessage();
            $arroout["data"] = null;
            return $arroout;
            //return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
        }
        return;
    }

    public static function movePersonFiles($temp_id, $per_id) {
        $folder = Yii::$app->basePath . "/" . Yii::$app->params["documentFolder"] . "academico/$temp_id/";
        $destinations = Yii::$app->basePath . "/" . Yii::$app->params["documentFolder"] . "solicitudinscripcion/$per_id/";
        if (Utilities::verificarDirectorio($destinations)) {
            $files = scandir($folder);
            foreach ($files as $file) {
                if (trim($file) != "." && trim($file) != "..") {
                    $arrExt = explode(".", $file);
                    $type = $arrExt[count($arrExt) - 1];
                    $newFile = str_replace("_" . $temp_id . "_", "_" . $per_id . "_", $file);
                    \app\models\Utilities::putMessageLogFile('Se va a renombrar los nombres de carpetas');
                    if (!rename($folder . $file, $destinations . $newFile)) {
                        return false;
                    }
                }
            }
            rmdir($folder);
        } else
            return false;
        return true;
    }
}

