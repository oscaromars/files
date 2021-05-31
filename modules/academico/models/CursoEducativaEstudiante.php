<?php

namespace app\modules\academico\models;
use yii\data\ArrayDataProvider;
use app\modules\academico\models\PeriodoAcademico;
use Yii;
use app\models\Utilities;

/**
 * This is the model class for table "curso_educativa_estudiante".
 *
 * @property int $ceest_id
 * @property int $cedu_id
 * @property int $est_id
 * @property string $ceest_estado_bloqueo
 * @property int $ceest_usuario_ingreso
 * @property int $ceest_usuario_modifica
 * @property string $ceest_estado
 * @property string $ceest_fecha_creacion
 * @property string $ceest_fecha_modificacion
 * @property string $ceest_estado_logico
 *
 * @property Estudiante $est
 * @property CursoEducativa $cedu
 */
class CursoEducativaEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa_estudiante';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cedu_id', 'est_id', 'ceest_estado_bloqueo', 'ceest_usuario_ingreso', 'ceest_estado', 'ceest_estado_logico'], 'required'],
            [['cedu_id', 'est_id', 'ceest_usuario_ingreso', 'ceest_usuario_modifica'], 'integer'],
            [['ceest_fecha_creacion', 'ceest_fecha_modificacion'], 'safe'],
            [['ceest_estado_bloqueo', 'ceest_estado', 'ceest_estado_logico'], 'string', 'max' => 1],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
            [['cedu_id'], 'exist', 'skipOnError' => true, 'targetClass' => CursoEducativa::className(), 'targetAttribute' => ['cedu_id' => 'cedu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ceest_id' => 'Ceest ID',
            'cedu_id' => 'Cedu ID',
            'est_id' => 'Est ID',
            'ceest_estado_bloqueo' => 'Ceest Estado Bloqueo',
            'ceest_usuario_ingreso' => 'Ceest Usuario Ingreso',
            'ceest_usuario_modifica' => 'Ceest Usuario Modifica',
            'ceest_estado' => 'Ceest Estado',
            'ceest_fecha_creacion' => 'Ceest Fecha Creacion',
            'ceest_fecha_modificacion' => 'Ceest Fecha Modificacion',
            'ceest_estado_logico' => 'Ceest Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCedu()
    {
        return $this->hasOne(CursoEducativa::className(), ['cedu_id' => 'cedu_id']);
    }
    /**
     * Function Obtiene información de distributivo todos los estudiantes.
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivoasigest($arrFiltro = array(), $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;
        
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            /* $str_search .= "(p.per_pri_nombre like :search OR ";
            $str_search .= "p.per_seg_nombre like :search OR ";
            $str_search .= "p.per_pri_apellido like :search OR ";
            $str_search .= "p.per_seg_apellido like :search OR ";
            $str_search .= "p.per_cedula like :search) AND "; */

            if (!empty($arrFiltro['profesor'])) {
                $str_search .= "(pe.per_pri_nombre like :profesor OR ";
                $str_search .= "pe.per_seg_nombre like :profesor OR ";
                $str_search .= "pe.per_pri_apellido like :profesor OR ";
                $str_search .= "pe.per_seg_apellido like :profesor) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "a.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "a.paca_id = :periodo AND ";
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "a.asi_id = :asignatura AND ";
            }             
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $str_search .= "cur.cedu_id = :curso AND ";
            }
            if ($arrFiltro['estado'] != "-1" ) {
                if ($arrFiltro['estado'] == "0" ) {
                $asignado = "inner join db_academico.curso_educativa_estudiante cures on cures.est_id = h.est_id  and cures.cedu_id = cur.cedu_id" ; // asignados
                $noasigna = "";
                }else{
                $asignado = " ";
                $noasigna = " and
                NOT EXISTS (SELECT NULL
                                     FROM db_academico.curso_educativa_estudiante cures
                                    WHERE cures.est_id = h.est_id 
                                          and cures.cedu_id = cur.cedu_id) "; //no asignados
                }         
            }
   
        }else{
          $mod_paca        = new PeriodoAcademico(); 
          $paca_actual_id  = $mod_paca->getPeriodoAcademicoActual();
          if (empty($paca_actual_id['id']))
          {
            $paca_actual_id['id'] = 0;
          }      
          $str_search      = "a.paca_id = ".$paca_actual_id['id']." AND ";
        }        

            $sql = "SELECT  distinct h.est_id, 
                        d.uaca_nombre as unidad, 
                        e.mod_nombre as modalidad,
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        concat(saca_nombre, '-', baca_nombre,'-',baca_anio) as periodo,
                        z.asi_nombre as asignatura,
                        cur.cedu_asi_nombre as curso "; 
        if ($reporte == 1 ) {
            $sql .=   " , (select ifnull(cee.ceest_id,' ')
                         from " . $con->dbname . ".curso_educativa_estudiante cee
                         where cee.cedu_id = cur.cedu_id and 
                               cee.est_id = h.est_id and                               
                               cee.ceest_estado = :estado and
                               cee.ceest_estado_logico = :estado)  estado_asignado ";
                            }               
            $sql .=   "   FROM " . $con->dbname . ".distributivo_academico a inner join " . $con->dbname . ".profesor b
                    on b.pro_id = a.pro_id 
                    inner join " . $con1->dbname . ".persona c on c.per_id = b.per_id
                    inner join " . $con1->dbname . ".persona pe on pe.per_id = b.per_id
                    inner join " . $con->dbname . ".unidad_academica d on d.uaca_id = a.uaca_id
                    inner join " . $con->dbname . ".modalidad e on e.mod_id = a.mod_id
                    inner join " . $con->dbname . ".periodo_academico f on f.paca_id = a.paca_id
                    inner join " . $con->dbname . ".distributivo_academico_estudiante g on g.daca_id = a.daca_id
                    inner join " . $con->dbname . ".estudiante h on h.est_id = g.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = h.per_id
                    inner join " . $con->dbname . ".semestre_academico s on s.saca_id = f.saca_id
                    inner join " . $con->dbname . ".bloque_academico t on t.baca_id = f.baca_id
                    inner join " . $con->dbname . ".asignatura z on a.asi_id = z.asi_id
                    inner join " . $con->dbname . ".curso_educativa cur on cur.paca_id = a.paca_id
                    $asignado
                    WHERE $str_search 
                    a.daca_estado = :estado
                    and a.daca_estado_logico = :estado
                    and g.daes_estado = :estado
                    and g.daes_estado_logico = :estado
                    $noasigna ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            /*$search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);*/

            if (!empty($arrFiltro['profesor'])) {
                $search_profe = "%" . $arrFiltro["profesor"] . "%";
                $comando->bindParam(":profesor", $search_profe, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $search_per = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $search_per, \PDO::PARAM_INT);
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $search_asi = $arrFiltro["asignatura"];
                $comando->bindParam(":asignatura", $search_asi, \PDO::PARAM_INT);
            }
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $search_cur = $arrFiltro["curso"];
                $comando->bindParam(":curso", $search_cur, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();

        $resultData2 = array();

        foreach ($resultData as $key => $value) {
            $band = 1;

            if(empty($resultData2))
              $resultData2[] = $value;

            foreach ($resultData2 as $key2 => $value2) {

                if ($resultData2[$key2]['est_id'] == $value['est_id']) {
                    if($resultData2[$key2]['asignatura'] == $value['asignatura']){
                        $band = 0;
                    }
                }
            }

            if($band == 1)
                $resultData2[] = $value;
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData2,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData2;
        }
    }

   /**
     * Function guarda asignacion de estudiantes a cursos en integracion educativa 
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el código).
     */
    public function insertarEstudiantecurso($cedu_id, $est_id, $ceest_usuario_ingreso) {
        /*\app\models\Utilities::putMessageLogFile('cedu_id...: ' . $cedu_id ); 
        \app\models\Utilities::putMessageLogFile('est_id...: ' . $est_id ); 
        \app\models\Utilities::putMessageLogFile('ceest_usuario_ingreso...: ' . $ceest_usuario_ingreso ); */
        $con = \Yii::$app->db_academico;
        $ceest_estado_bloqueo = 'B';
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
                       
        $param_sql = "ceest_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", ceest_estado";
        $bsol_sql .= ", 1";

        $param_sql .= ", ceest_estado_bloqueo";
        $bsol_sql .= ", '" . $ceest_estado_bloqueo . "'";

        if (isset($cedu_id)) {
            $param_sql .= ", cedu_id";
            $bsol_sql .= ", :cedu_id";
        }

        if (isset($est_id)) {
            $param_sql .= ", est_id";
            $bsol_sql .= ", :est_id";
        }
        
        if (isset($ceest_usuario_ingreso)) {
            $param_sql .= ", ceest_usuario_ingreso";
            $bsol_sql .= ", :ceest_usuario_ingreso";
        }

        if (isset($fecha_transaccion)) {
            $param_sql .= ",ceest_fecha_creacion";
            $bsol_sql .= ", :ceest_fecha_creacion";
        }   

        try {
            $sql = "INSERT INTO " . $con->dbname . ".curso_educativa_estudiante ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);            
            \app\models\Utilities::putMessageLogFile('sql...: ' .$sql); 

            // $comando->bindParam(':ceest_estado_bloqueo', $ceest_estado_bloqueo, \PDO::PARAM_STR); 

            if (isset($cedu_id)) {
                $comando->bindParam(':cedu_id', $cedu_id, \PDO::PARAM_INT);
            }

            if (isset($est_id)) {
                $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);
            }            

            if (isset($ceest_usuario_ingreso)) {
                $comando->bindParam(':ceest_usuario_ingreso', $ceest_usuario_ingreso, \PDO::PARAM_INT);
            }

            if (isset($fecha_transaccion)) {
                $comando->bindParam(':ceest_fecha_creacion', $fecha_transaccion, \PDO::PARAM_STR);
            }
            
            $result = $comando->execute();

            $id = $con->getLastInsertID($con->dbname . '.curso_educativa_estudiante;');

            if ($trans !== null)
                $trans->commit();
            
            return $id;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    } 

    /**
     * Function modificar curso_educativa_estudiante.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarEstudiantecurso($ceest_id, $cedu_id, $est_id, $cees_usuario_modifica) {
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $con = \Yii::$app->db_academico;
        $estado = 1; 
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".curso_educativa_estudiante		       
                      SET cedu_id = :cedu_id,                        
                          est_id = :est_id,
                          cees_usuario_modifica = :cees_usuario_modifica,
                          cees_fecha_modificacion = :cees_fecha_modificacion                          
                      WHERE 
                      ceest_id = :ceest_id AND
                      ceest_estado = :estado AND
                      ceest_estado_logico = :estado");
            $comando->bindParam(":ceest_id", $ceest_id, \PDO::PARAM_INT);  
            $comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT); 
            $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);              
            $comando->bindParam(":cees_usuario_modifica", $cees_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":cees_fecha_modificacion", $fecha_transaccion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function Consultar si existe asignacion curso.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAsignacionexiste($cedu_id, $est_id) {
        $con = \Yii::$app->db_academico;     
        $estado = 1;   
        $sql = "SELECT 	
                        count(*) as exiteasigna                       
                        
                FROM " . $con->dbname . ".curso_educativa_estudiante                 
                WHERE 
                cedu_id = :cedu_id AND
                est_id = :est_id AND
                ceest_estado = :estado AND
                ceest_estado_logico = :estado ";        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT); 
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);       
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function eliminar el asignacion curso estados en 0.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function eliminarAsignacioncurso($ceest_id, $cees_usuario_modifica, $cees_fecha_modificacion) {
        $estado = 0;
        $con = \Yii::$app->db_academico;
        
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".curso_educativa_estudiante		       
                      SET cees_estado = :cees_estado,
                          cees_usuario_modifica = :cees_usuario_modifica,
                          cees_fecha_modificacion = :cees_fecha_modificacion                          
                      WHERE 
                      ceest_id = :ceest_id ");
            $comando->bindParam(":ceest_id", $ceest_id, \PDO::PARAM_INT);          
            $comando->bindParam(":cees_usuario_modifica", $cees_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":cees_fecha_modificacion", $cees_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":cees_estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function Obtiene información de distributivo todos los estudiantes, cursos y estado de bloqueo.
     * en integración educativa
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistributivoxEducativa($arrFiltro = array(), $reporte) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;
        // $curso = 0;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(p.per_pri_nombre like :search OR ";
            $str_search .= "p.per_seg_nombre like :search OR ";
            $str_search .= "p.per_pri_apellido like :search OR ";
            $str_search .= "p.per_seg_apellido like :search OR ";
            $str_search .= "p.per_cedula like :search) AND ";

            if (!empty($arrFiltro['profesor'])) {
                $str_search .= "(pe.per_pri_nombre like :profesor OR ";
                $str_search .= "pe.per_seg_nombre like :profesor OR ";
                $str_search .= "pe.per_pri_apellido like :profesor OR ";
                $str_search .= "pe.per_seg_apellido like :profesor) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "a.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "a.paca_id = :periodo AND ";
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "a.asi_id = :asignatura AND ";
            }           
            if ($arrFiltro['estado_pago'] == "0" or $arrFiltro['estado_pago'] == "1") {            
                if ($arrFiltro['estado_pago'] == "0") {            
                $str_search .= " ((m.ccar_estado_cancela is null OR m.ccar_estado_cancela = :estado_pago) AND NOW() > m.ccar_fecha_vencepago ) AND ";
            }else{
                $str_search .= " (m.ccar_estado_cancela = :estado_pago OR NOW() < m.ccar_fecha_vencepago) AND ";
            } 
        } 
            /**************************************************************  **/ 
            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $str_search .= "a.daca_jornada = :jornada AND ";
            }
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $str_search .= "cur.cedu_id = :curso AND ";
            }
        }else{
          $mod_paca        = new PeriodoAcademico(); 
          $paca_actual_id  = $mod_paca->getPeriodoAcademicoActual();
          //$str_search      = "a.paca_id = ".$paca_actual_id['id']." AND ";
          $str_search      = "a.paca_id = 0 AND ";
        }

        if ($reporte == 1 ) {
          $estuid = " h.est_id, 
                      cur.cedu_id, ";
        }          

        $sql .= "SELECT  distinct 
                        $estuid 
                        d.uaca_nombre as unidad, 
                        e.mod_nombre as modalidad,
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        concat(saca_nombre, '-', baca_nombre,'-',baca_anio) as periodo,
                        z.asi_nombre as asignatura,                                       
                          case 
                                when m.ccar_fecha_vencepago <= NOW() then  ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela = 'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id = g.est_id and mi.ccar_fecha_vencepago <= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago desc
                                            LIMIT 1),'No Autorizado')
                                when m.ccar_fecha_vencepago >= NOW() then ifnull((SELECT
                                            CASE WHEN mi.ccar_estado_cancela = 'N' or mi.ccar_estado_cancela = 'C'
                                            THEN 'Autorizado'
                                            ELSE 'No Autorizado' END AS pago
                                            FROM db_facturacion.carga_cartera mi
                                            WHERE mi.est_id = g.est_id and mi.ccar_fecha_vencepago >= NOW()
                                            ORDER BY mi.ccar_fecha_vencepago asc
                                            LIMIT 1),'No Autorizado')						 
                                else 'No Autorizado'
                                end as pago " ;                    
                    $sql .= "    
                    -- ifnull(DATE_FORMAT(m.eppa_fecha_registro, '%Y-%m-%d'), ' ') as fecha_pago 
                    , cur.ceest_estado_bloqueo as estado_bloqueo
                    FROM " . $con->dbname . ".distributivo_academico a inner join " . $con->dbname . ".profesor b
                    on b.pro_id = a.pro_id 
                    inner join " . $con1->dbname . ".persona c on c.per_id = b.per_id
                    inner join " . $con1->dbname . ".persona pe on pe.per_id = b.per_id
                    inner join " . $con->dbname . ".unidad_academica d on d.uaca_id = a.uaca_id
                    inner join " . $con->dbname . ".modalidad e on e.mod_id = a.mod_id
                    inner join " . $con->dbname . ".periodo_academico f on f.paca_id = a.paca_id
                    inner join " . $con->dbname . ".distributivo_academico_estudiante g on g.daca_id = a.daca_id
                    inner join " . $con->dbname . ".estudiante h on h.est_id = g.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = h.per_id
                    inner join " . $con->dbname . ".semestre_academico s on s.saca_id = f.saca_id
                    inner join " . $con->dbname . ".bloque_academico t on t.baca_id = f.baca_id
                    inner join " . $con->dbname . ".asignatura z on a.asi_id = z.asi_id
                    -- left join " . $con->dbname . ".estudiante_periodo_pago m on (m.est_id = g.est_id and m.paca_id = f.paca_id)
                    left join " . $con2->dbname . ".carga_cartera m on (m.est_id = g.est_id /* and m.paca_id = f.paca_id */)
                    inner join " . $con->dbname . ".curso_educativa_estudiante cur on cur.est_id = h.est_id
                    WHERE $str_search 
                    a.daca_estado = :estado
                    and a.daca_estado_logico = :estado
                    and g.daes_estado = :estado
                    and g.daes_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if (!empty($arrFiltro['profesor'])) {
                $search_profe = "%" . $arrFiltro["profesor"] . "%";
                $comando->bindParam(":profesor", $search_profe, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $search_per = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $search_per, \PDO::PARAM_INT);
            }
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $search_asi = $arrFiltro["asignatura"];
                $comando->bindParam(":asignatura", $search_asi, \PDO::PARAM_INT);
            }            
            if ($arrFiltro['estado_pago'] != '-1') {
                if ($arrFiltro['estado_pago'] == '0') {
                    $filestado = 'N';
                } else {
                    $filestado = 'C';
              } 
                $comando->bindParam(":estado_pago", $filestado, \PDO::PARAM_STR);
            }
            /***************************************************************** */

            if ($arrFiltro['jornada'] != "" && $arrFiltro['jornada'] > 0) {
                $search_jor = $arrFiltro["jornada"];
                $comando->bindParam(":jornada", $search_jor, \PDO::PARAM_INT);
            }
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $curso = $arrFiltro["curso"];
                $comando->bindParam(":curso", $curso, \PDO::PARAM_INT);
            }
        }
        Utilities::putMessageLogFile($comando->getRawSql());
        
        $resultData = $comando->queryAll();

        

        $resultData2 = array();

        foreach ($resultData as $key => $value) {
            $band = 1;

            if(empty($resultData2))
              $resultData2[] = $value;

            foreach ($resultData2 as $key2 => $value2) {

                if ($resultData2[$key2]['est_id'] == $value['est_id']) {
                    if($resultData2[$key2]['asignatura'] == $value['asignatura']){
                        $band = 0;
                    }
                }
            }

            if($band == 1)
                $resultData2[] = $value;
        }//foreach
        //\app\models\Utilities::putMessageLogFile(print_r($resultData2,true));


        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData2,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData2;
        }
    }
   /**
     * Function modificar curso_educativa_estudiante.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarEstadobloqueo($cedu_id, $est_id, $ceest_estado_bloqueo, $ceest_usuario_modifica) {
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $con = \Yii::$app->db_academico;
        $estado = 1; 
        /*\app\models\Utilities::putMessageLogFile('cedu_id bloqueo '. $cedu_id);
        \app\models\Utilities::putMessageLogFile('est_id bloqueo '. $est_id);
        \app\models\Utilities::putMessageLogFile('ceest_estado_bloqueo '. $ceest_estado_bloqueo);
        \app\models\Utilities::putMessageLogFile('cees_usuario_modifica bloqueo '. $ceest_usuario_modifica);*/
 
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".curso_educativa_estudiante		       
                      SET ceest_estado_bloqueo = :ceest_estado_bloqueo,  
                          ceest_usuario_modifica = :ceest_usuario_modifica,
                          ceest_fecha_modificacion = :ceest_fecha_modificacion                          
                      WHERE 
                      cedu_id = :cedu_id AND
                      est_id = :est_id AND
                      ceest_estado = :estado AND
                      ceest_estado_logico = :estado");
            $comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT);  
            $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);              
            $comando->bindParam(":ceest_estado_bloqueo", $ceest_estado_bloqueo, \PDO::PARAM_STR); 
            $comando->bindParam(":ceest_usuario_modifica", $cees_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":ceest_fecha_modificacion", $fecha_transaccion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    } 

    /**
     * Retorna los est_id, daca_id y cedu_id de la tabla curso_educativa que sean del período académico actual, que sean FK de la tabla curso_educativa_distributivo, y cuyo daca_id sea FK de la tabla distributivo_academico_estudiante
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarCursoEducativaDistributivoPeriodoActual(){
        $con = Yii::$app->db_academico;

        $sql = "SELECT DISTINCT daes.est_id, daca.daca_id, cedu.cedu_id
                FROM " . $con->dbname . ".curso_educativa_distributivo AS cedi
                INNER JOIN " . $con->dbname . ".curso_educativa AS cedu ON cedu.cedu_id = cedi.cedu_id
                INNER JOIN " . $con->dbname . ".periodo_academico AS paca ON paca.paca_id = cedu.paca_id
                INNER JOIN " . $con->dbname . ".distributivo_academico AS daca ON daca.daca_id = cedi.daca_id
                INNER JOIN " . $con->dbname . ".distributivo_academico_estudiante AS daes ON daes.daca_id = daca.daca_id
                WHERE ((now() between paca.paca_fecha_inicio and paca.paca_fecha_fin) OR (now() < paca.paca_fecha_fin)) AND paca.paca_activo = 'A'";

        $comando = $con->createCommand($sql);

        // Utilities::putMessageLogFile($comando->getRawSql());


        $resultData = $comando->queryAll();

        return $resultData;
    }
}
