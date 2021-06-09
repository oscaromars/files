<?php

namespace app\modules\academico\models;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "asignacion_becas_estudiante".
 *
 * @property int $abe_id
 * @property int $est_id
 * @property int $paca_id
 * @property string $abe_estado
 * @property string $abe_fecha_creacion
 * @property int $abe_usuario_modifica
 * @property int $abe_usuario_creacion
 * @property string $abe_fecha_modificacion
 * @property string $abe_estado_logico
 *
 * @property PeriodoAcademico $paca
 * @property Estudiante $est
 */
class AsignacionBecasEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asignacion_becas_estudiante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['est_id', 'paca_id', 'abe_estado', 'abe_usuario_creacion', 'abe_estado_logico'], 'required'],
            [['est_id', 'paca_id', 'abe_usuario_modifica', 'abe_usuario_creacion'], 'integer'],
            [['abe_fecha_creacion', 'abe_fecha_modificacion'], 'safe'],
            [['abe_estado', 'abe_estado_logico'], 'string', 'max' => 1],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'abe_id' => 'Abe ID',
            'est_id' => 'Est ID',
            'paca_id' => 'Paca ID',
            'abe_estado' => 'Abe Estado',
            'abe_fecha_creacion' => 'Abe Fecha Creacion',
            'abe_usuario_modifica' => 'Abe Usuario Modifica',
            'abe_usuario_creacion' => 'Abe Usuario Creacion',
            'abe_fecha_modificacion' => 'Abe Fecha Modificacion',
            'abe_estado_logico' => 'Abe Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }

     /**
     * Function Añadir asignatura a periodo
     * @author  
     * @param
     * @return
     */
    public function insertarAsignacionBecasEstudiante($paca_id, $est_id,$usuario) {
        $con = \Yii::$app->db_academico;
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "abe_estado";
        $bdet_sql = "1";

        $param_sql .= ", abe_estado_logico";
        $bdet_sql .= ", 1";


        if (isset($paca_id)) {
            $param_sql .= ", paca_id";
            $bdet_sql .= ", :paca_id";
        }
        if (isset($est_id)) {
            $param_sql .= ", est_id";
            $bdet_sql .= ", :est_id";
        }
        /*if (isset($fecha)) {
            $param_sql .= ", aspe_fecha_modificacion";
            $bdet_sql .= ", :aspe_fecha_modificacion";
        }*/
        if (isset($fecha)) {
            $param_sql .= ", abe_fecha_creacion";
            $bdet_sql .= ", :abe_fecha_creacion";
        }
         if (isset($usuario)) {
            $param_sql .= ", abe_usuario_creacion";
            $bdet_sql .= ", :abe_usuario_creacion";
        }
        try {
            \app\models\Utilities::putMessageLogFile('entro sdesdf...: ');
            $sql = "INSERT INTO " . $con->dbname . ".asignacion_becas_estudiante ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($paca_id)) {
                $comando->bindParam(':paca_id', $paca_id, \PDO::PARAM_INT);
            }
            if (isset($est_id)) {
                $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);
            }
            /*if (!empty((isset($fecha)))) {
                $comando->bindParam(':aspe_fecha_modificacion', $fecha, \PDO::PARAM_STR);
            }*/
            if (!empty((isset($fecha)))) {
                $comando->bindParam(':abe_fecha_creacion', $fecha, \PDO::PARAM_STR);
            }
             if (isset($usuario)) {
                $comando->bindParam(':abe_usuario_creacion', $usuario, \PDO::PARAM_INT);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.asignacion_becas_estudiante');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

     /**
     * Function Consultar id estudiante segun per_id.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function getEstudiantesBecasPorPeriodoAcademico($paca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT  
                        paca.paca_fecha_inicio,
                        paca.paca_fecha_fin,
                        abe.abe_id,
                        p.pla_periodo_academico,
                        est.est_id,
                        per.per_pri_nombre,
                        p.pla_id, p.paca_id, per.per_pri_nombre,per_pri_apellido,per.per_cedula, x.pes_carrera
                FROM  db_academico_mbtu.planificacion p 
                INNER JOIN db_academico_mbtu.periodo_academico paca on p.paca_id = paca.paca_id
                INNER JOIN db_academico_mbtu.planificacion_estudiante x on p.pla_id = x.pla_id 
                INNER JOIN db_asgard_mbtu.persona per on per.per_id = x.per_id  
                INNER JOIN db_academico_mbtu.estudiante est on est.per_id = per.per_id 
                INNER JOIN db_academico_mbtu.asignacion_becas_estudiante abe on abe.est_id = est.est_id                      
                WHERE paca.paca_id = :paca_id  AND                    
                    paca.paca_id = :estado AND
                    p.pla_estado = :estado AND 
                    x.pes_estado = :estado AND 
                    paca.paca_estado = :estado AND
                    per.per_estado = :estado AND 
                    est.est_estado = :estado AND 
                    p.pla_estado_logico = :estado AND 
                    x.pes_estado_logico = :estado AND 
                    paca.paca_estado_logico = :estado AND
                    per.per_estado_logico = :estado AND 
                    est.est_estado_logico = :estado AND
                    paca.paca_activo = 'A' AND
                    abe.abe_estado = :estado  AND 
                    abe.abe_estado_logico = :estado" ;

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);

        
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
     * Function Consultar id estudiante segun per_id.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function getEstudiantesBecasPorPeriodoAcademico2($paca_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT 
                    paca.paca_fecha_inicio,
                    paca.paca_fecha_fin,
                    planificacion.per_id, 
                    planificacion.paca_id,
                    est.est_id,
                    per.per_pri_nombre,
                    per.per_pri_nombre,
                    per.per_pri_apellido,
                    per.per_cedula
                FROM 
                    (SELECT distinct pes.per_id,pla.paca_id 
                        FROM 
                            " . $con->dbname . ".planificacion pla 
                            INNER JOIN " . $con->dbname . ".planificacion_estudiante pes on pla.pla_id = pes.pla_id
                        WHERE 
                            pla.pla_estado = :estado  AND 
                            pla.pla_estado_logico = :estado  AND 
                            pes.pes_estado = :estado  AND 
                            pes.pes_estado_logico = :estado  
                    ) planificacion 
                        INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = planificacion.per_id
                        INNER JOIN " . $con->dbname . ".estudiante est on est.per_id = per.per_id
                        INNER JOIN " . $con->dbname . ".periodo_academico paca on planificacion.paca_id = paca.paca_id
                 WHERE  paca.paca_id = :paca_id  AND
                        paca.paca_estado = :estado  AND
                        per.per_estado = :estado  AND
                        est.est_estado = :estado  AND
                        paca.paca_estado_logico = :estado  AND
                        per.per_estado_logico = :estado  AND
                        est.est_estado_logico = :estado  AND
                        paca.paca_activo = 'A' AND
                        est.est_activo = :estado  AND
                        est.est_id not in (
                                SELECT est_id 
                                FROM " . $con->dbname . ".asignacion_becas_estudiante abe 
                                WHERE paca_id = paca.paca_id and est_id = est.est_id and abe.abe_estado = :estado  and abe.abe_estado_logico = :estado 
                            )
                        ORDER BY per.per_pri_apellido ASC";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);

        
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

      public function consultarBecados($arrFiltro = array()) {
                $con = \Yii::$app->db_academico;
                $con1 = \Yii::$app->db_asgard;
                $estado = 1;
                $str_search= "";

                if (isset($arrFiltro) && count($arrFiltro) > 0) {
                    
                     if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                        $str_search .= "paca.paca_id = :paca_id  AND ";
                     }
                   
                   if ($arrFiltro['admitido'] != "") {
                        $str_search .= " (TRIM(per.per_pri_nombre) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_seg_nombre) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_pri_apellido) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_pasaporte) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_cedula) like '%".$arrFiltro['admitido'] ."%') AND ";
                    }   
                   //  \app\models\Utilities::putMessageLogFile('Busqueda Model admitido:  '.$arrFiltro['periodo']);
                    //$str_search .= "TRIM(per.per_pri_apellido) LIKE '%".$arrFiltro['admitido'] ."%' AND ";
                }

                $sql = "SELECT 
                    paca.paca_fecha_inicio,
                    paca.paca_fecha_fin,
                    planificacion.per_id, 
                    planificacion.paca_id,
                    est.est_id,
                    per.per_pri_nombre,
                    per.per_pri_nombre,
                    per.per_pri_apellido,
                    per.per_cedula,
                    est.est_matricula
                FROM 
                    (SELECT distinct pes.per_id,pla.paca_id 
                        FROM 
                            " . $con->dbname . ".planificacion pla 
                            INNER JOIN " . $con->dbname . ".planificacion_estudiante pes on pla.pla_id = pes.pla_id
                        WHERE 
                            pla.pla_estado = :estado  AND 
                            pla.pla_estado_logico = :estado  AND 
                            pes.pes_estado = :estado  AND 
                            pes.pes_estado_logico = :estado  
                    ) planificacion 
                        INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = planificacion.per_id
                        INNER JOIN " . $con->dbname . ".estudiante est on est.per_id = per.per_id
                        INNER JOIN " . $con->dbname . ".periodo_academico paca on planificacion.paca_id = paca.paca_id
                        INNER JOIN " . $con->dbname . ".asignacion_becas_estudiante abe on abe.est_id = est.est_id   
                 WHERE  
                        $str_search 
                        paca.paca_estado = :estado  AND
                        paca.paca_estado_logico = :estado  AND
                        per.per_estado = :estado  AND
                        per.per_estado_logico = :estado  AND
                        est.est_estado_logico = :estado  AND
                        est.est_estado = :estado  AND
                        paca.paca_activo = 'A' AND
                        est.est_activo = :estado AND
                        abe.abe_estado = :estado AND
                        abe.abe_estado_logico = :estado
                        ORDER BY per.per_pri_apellido ASC";

                $comando = $con->createCommand($sql);
                
                if (isset($arrFiltro) && count($arrFiltro) > 0) {

                
                    if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                        $search_per = $arrFiltro["periodo"];
                        $comando->bindParam(":paca_id", $search_per, \PDO::PARAM_INT);
                    }
                    if ($arrFiltro['admitido'] != "") {
                        //$search_adm = $arrFiltro["admitido"];
                         $search_adm = "%" . $arrFiltro["admitido"] . "%";
                        $comando->bindParam(":search", $search_adm, \PDO::PARAM_INT);
                    }
                     
                     
                }
                $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

                $resultData = $comando->queryAll();

                \app\models\Utilities::putMessageLogFile(print_r($resultData,true));
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

        //Consultar los estudiantes que no tienen becas y que  no tienn pago de matricula en el periodo actual
        public function consultarPorBecarEstudiantes($arrFiltro = array()) {
                $con = \Yii::$app->db_academico;
                $con1 = \Yii::$app->db_asgard;
                $estado = 1;
                $str_search= "";

                if (isset($arrFiltro) && count($arrFiltro) > 0) {
                    
                     if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                        $str_search .= "paca.paca_id = :paca_id  AND ";
                     }
                   
                   if ($arrFiltro['admitido'] != "") {
                        $str_search .= " (TRIM(per.per_pri_nombre) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_seg_nombre) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_pri_apellido) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_pasaporte) like '%".$arrFiltro['admitido'] ."%' OR ";
                        $str_search .= "TRIM(per.per_cedula) like '%".$arrFiltro['admitido'] ."%') AND ";
                    }   
                   //  \app\models\Utilities::putMessageLogFile('Busqueda Model admitido:  '.$arrFiltro['periodo']);
                    //$str_search .= "TRIM(per.per_pri_apellido) LIKE '%".$arrFiltro['admitido'] ."%' AND ";
                }

                $sql = "
                SELECT 
                    paca.paca_fecha_inicio,
                    paca.paca_fecha_fin,
                    planificacion.per_id, 
                    planificacion.paca_id,
                    est.est_id,
                    per.per_id,
                    per.per_pri_nombre,
                    per.per_pri_nombre,
                    per.per_pri_apellido,
                    per.per_cedula
                FROM 
                    (SELECT distinct pes.per_id,pla.paca_id 
                        FROM 
                            " . $con->dbname . ".planificacion pla 
                            INNER JOIN " . $con->dbname . ".planificacion_estudiante pes on pla.pla_id = pes.pla_id
                            Inner join " . $con->dbname . ".semestre_academico sa on sa.pla_id=pla.pla_id
                            Inner join " . $con->dbname . ".periodo_academico pa on sa.saca_id=pa.saca_id
    
                        WHERE 
                            pla.pla_estado = :estado  AND 
                            pla.pla_estado_logico = :estado  AND 
                            pes.pes_estado = :estado  AND 
                            pes.pes_estado_logico = :estado  
                    ) planificacion 
                        INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = planificacion.per_id
                        INNER JOIN " . $con->dbname . ".estudiante est on est.per_id = per.per_id
                        INNER JOIN " . $con->dbname . ".periodo_academico paca on planificacion.paca_id = paca.paca_id
                WHERE  
                        $str_search 
                        paca.paca_estado = :estado  AND
                        per.per_estado = :estado  AND
                        est.est_estado = :estado  AND
                        paca.paca_estado_logico = :estado  AND
                        per.per_estado_logico = :estado  AND
                        est.est_estado_logico = :estado  AND
                        paca.paca_activo = 'A' AND
                        est.est_activo = :estado  AND
                        est.est_id not in (
                                SELECT est_id 
                                FROM " . $con->dbname . ".asignacion_becas_estudiante abe 
                                WHERE paca_id = paca.paca_id and est_id = est.est_id and abe.abe_estado = :estado  and abe.abe_estado_logico = :estado 
                            ) AND 
                        per.per_id  not in (
                                SELECT pm.per_id 
                                FROM " . $con->dbname . ".registro_pago_matricula pm
                                            INNER JOIN " . $con->dbname . ".planificacion pla on pm.pla_id = pla.pla_id
                                            INNER JOIN " . $con->dbname . ".semestre_academico sa on  pla.pla_id=sa.pla_id
                                            INNER JOIN " . $con->dbname . ".periodo_academico paca on sa.saca_id = paca.saca_id
                                             
                                WHERE  
                                            paca.paca_id = :paca_id AND pm.per_id = per.per_id AND 
                                            paca.paca_estado = :estado AND
                                            paca.paca_estado_logico = :estado AND
                                            pm.rpm_estado = :estado AND
                                            pm.rpm_estado_logico = :estado AND
                                            pla.pla_estado = :estado AND
                                            paca.paca_activo = 'A' AND
                                            pla.pla_estado_logico = :estado )   
                ORDER BY per.per_pri_apellido ASC";

                $comando = $con->createCommand($sql);
                
                if (isset($arrFiltro) && count($arrFiltro) > 0) {

                
                    if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                        $search_per = $arrFiltro["periodo"];
                        $comando->bindParam(":paca_id", $search_per, \PDO::PARAM_INT);
                    }
                    if ($arrFiltro['admitido'] != "") {
                        //$search_adm = $arrFiltro["admitido"];
                         $search_adm = "%" . $arrFiltro["admitido"] . "%";
                        $comando->bindParam(":search", $search_adm, \PDO::PARAM_INT);
                    }
                     
                     
                }
                $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

                $resultData = $comando->queryAll();

                \app\models\Utilities::putMessageLogFile(print_r($resultData,true));
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






    


}
