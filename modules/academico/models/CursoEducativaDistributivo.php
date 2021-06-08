<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use yii\data\ArrayDataProvider;
use app\modules\academico\models\CursoEducativa;
use Yii;

/**
 * This is the model class for table "curso_educativa_distributivo".
 *
 * @property int $cedi_id
 * @property int $cedu_id
 * @property int $daca_id
 * @property int $cedi_usuario_ingreso
 * @property int $cedi_usuario_modifica
 * @property string $cedi_estado
 * @property string $cedi_fecha_creacion
 * @property string $cedi_fecha_modificacion
 * @property string $cedi_estado_logico
 *
 * @property CursoEducativa $cedu
 * @property DistributivoAcademico $daca
 */
class CursoEducativaDistributivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa_distributivo';
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
            [['cedu_id', 'daca_id', 'cedi_usuario_ingreso', 'cedi_estado', 'cedi_estado_logico'], 'required'],
            [['cedu_id', 'daca_id', 'cedi_usuario_ingreso', 'cedi_usuario_modifica'], 'integer'],
            [['cedi_fecha_creacion', 'cedi_fecha_modificacion'], 'safe'],
            [['cedi_estado', 'cedi_estado_logico'], 'string', 'max' => 1],
            [['cedu_id'], 'exist', 'skipOnError' => true, 'targetClass' => CursoEducativa::className(), 'targetAttribute' => ['cedu_id' => 'cedu_id']],
            [['daca_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistributivoAcademico::className(), 'targetAttribute' => ['daca_id' => 'daca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cedi_id' => 'Cedi ID',
            'cedu_id' => 'Cedu ID',
            'daca_id' => 'Daca ID',
            'cedi_usuario_ingreso' => 'Cedi Usuario Ingreso',
            'cedi_usuario_modifica' => 'Cedi Usuario Modifica',
            'cedi_estado' => 'Cedi Estado',
            'cedi_fecha_creacion' => 'Cedi Fecha Creacion',
            'cedi_fecha_modificacion' => 'Cedi Fecha Modificacion',
            'cedi_estado_logico' => 'Cedi Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCedu()
    {
        return $this->hasOne(CursoEducativa::className(), ['cedu_id' => 'cedu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDaca()
    {
        return $this->hasOne(DistributivoAcademico::className(), ['daca_id' => 'daca_id']);
    }

    public function getListadoDistributivoedu($search = NULL, $modalidad = NULL, $asignatura = NULL, $jornada = NULL, $unidadAcademico = NULL, $periodoAcademico = NULL, $onlyData = false, $reporte = NULL) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $search_cond = "%" . $search . "%";
        $estado = "1";
        $str_search = "";
        $str_unidad = "";
        $str_periodo = "";
        $str_modalidad = "";
        $str_jornada = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.per_pri_nombre like :search OR ";
            $str_search .= "pe.per_pri_apellido like :search OR ";
            $str_search .= "pe.per_cedula like :search) AND ";
        }
        if (isset($modalidad) && $modalidad > 0) {
            $str_modalidad = "m.mod_id = :modalidad AND ";
        }
        if (isset($asignatura) && $asignatura > 0) {
            $str_asignatura = "a.asi_id = :asignatura AND ";
        }
        if (isset($unidadAcademico) && $unidadAcademico > 0) {
            $str_unidad = "ua.uaca_id = :unidad AND ";
        }
        if (isset($periodoAcademico) && $periodoAcademico > 0) {
            $str_periodo = "pa.paca_id = :periodo AND ";
        }
        if (isset($jornada) && $jornada > 0) {
            $str_jornada = "dh.daho_jornada = :jornada AND ";
        }

        if ($onlyData == false) {
            $pro_id = "da.pro_id, ";
        }
        $sql = "SELECT 
                    $pro_id
                    da.daca_id AS Id, 
                    CONCAT(pe.per_pri_nombre, ' ', pe.per_pri_apellido) AS Nombres,
                    pe.per_cedula AS Cedula,
                    ua.uaca_nombre AS UnidadAcademica,
                    m.mod_nombre AS Modalidad,
                    ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) AS Periodo,
                    a.asi_nombre AS Asignatura,
                    CASE
                        WHEN dh.daho_jornada = 1 THEN '(M) Matutino'
                        WHEN dh.daho_jornada = 2 THEN '(N) Nocturno'
                        WHEN dh.daho_jornada = 3 THEN '(S) Semipresencial'
                        WHEN dh.daho_jornada = 4 THEN '(D) Distancia'
                        ELSE ''
                    END AS Jornada
                FROM 
                    " . $con_academico->dbname . ".distributivo_academico AS da 
                    LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario AS dh ON da.daho_id = dh.daho_id
                    INNER JOIN " . $con_academico->dbname . ".profesor AS p ON da.pro_id = p.pro_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON da.uaca_id = ua.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".asignatura AS a ON da.asi_id = a.asi_id
                    INNER JOIN " . $con_academico->dbname . ".periodo_academico AS pa ON da.paca_id = pa.paca_id
                    INNER JOIN " . $con_db->dbname . ".persona AS pe ON p.per_id = pe.per_id
                    LEFT JOIN " . $con_academico->dbname . ".semestre_academico sem  ON sem.saca_id = pa.saca_id 
                    LEFT JOIN " . $con_academico->dbname . ".bloque_academico blq ON blq.baca_id = pa.baca_id
                WHERE 
                    $str_search 
                    $str_modalidad 
                    $str_asignatura
                    $str_unidad
                    $str_periodo
                    $str_jornada
                    pa.paca_activo = 'A' AND
                    pa.paca_estado = :estado AND
                    da.daca_estado_logico = :estado AND 
                    da.daca_estado = :estado AND
                    p.pro_estado_logico = :estado AND 
                    p.pro_estado = :estado AND
                    m.mod_estado_logico = :estado AND 
                    m.mod_estado = :estado AND
                    ua.uaca_estado_logico = :estado AND 
                    ua.uaca_estado = :estado AND
                    pa.paca_estado_logico = :estado";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($search) && $search != "") {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if (isset($modalidad) && $modalidad != "") {
            $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        }
        if (isset($asignatura) && $asignatura != "") {
            $comando->bindParam(":asignatura", $asignatura, \PDO::PARAM_INT);
        }
        if (isset($unidadAcademico) && $unidadAcademico != "") {
            $comando->bindParam(":unidad", $unidadAcademico, \PDO::PARAM_INT);
        }
        if (isset($periodoAcademico) && $periodoAcademico != "") {
            $comando->bindParam(":periodo", $periodoAcademico, \PDO::PARAM_INT);
        }
        if (isset($jornada) && $jornada > 0) {
            $comando->bindParam(":jornada", $jornada, \PDO::PARAM_INT);            
        }

        $res = $comando->queryAll();

        if (empty($reporte))
        {
            $mod_educativa = new CursoEducativa();
            //$arr_curso = $mod_educativa->consultarCursostodos();  
            $arr_curso = $mod_educativa->consultarCursosxpacaid($periodoAcademico);
            $arr_curso = array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_curso);
            
            //print_r($arr_curso);
            foreach ($res as $key => $value) {
                $value['cursos'] = $arr_curso;
                $res[$key] =  $value;
            }    
        
        }
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Nombres', "Cedula", "UnidadAcademica", "Modalidad", "Periodo"],
            ],
        ]);

       // return $dataProvider;
       if ($onlyData) {
        return $res;
    } else {
        return $dataProvider;
    }
    }
    
     /**
     * Function guarda asignacion de estudiantes a cursos en integracion educativa 
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el código).
     */
    public function insertarEstudiantecurso($cedu_id, $daca_id, $cedi_usuario_ingreso) {
       /* \app\models\Utilities::putMessageLogFile('cedu_id...: ' . $cedu_id ); 
        \app\models\Utilities::putMessageLogFile('daca_id...: ' . $daca_id ); 
        \app\models\Utilities::putMessageLogFile('cedi_usuario_ingreso...: ' . $cedi_usuario_ingreso );*/
        $con = \Yii::$app->db_academico;        
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
                       
        $param_sql = "cedi_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", cedi_estado";
        $bsol_sql .= ", 1";
       
        if (isset($cedu_id)) {
            $param_sql .= ", cedu_id";
            $bsol_sql .= ", :cedu_id";
        }

        if (isset($daca_id)) {
            $param_sql .= ", daca_id";
            $bsol_sql .= ", :daca_id";
        }
        
        if (isset($cedi_usuario_ingreso)) {
            $param_sql .= ", cedi_usuario_ingreso";
            $bsol_sql .= ", :cedi_usuario_ingreso";
        }

        if (isset($fecha_transaccion)) {
            $param_sql .= ",cedi_fecha_creacion";
            $bsol_sql .= ", :cedi_fecha_creacion";
        }   

        try {
            $sql = "INSERT INTO " . $con->dbname . ".curso_educativa_distributivo ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);            
            // \app\models\Utilities::putMessageLogFile('sql...: ' .$sql); 
         
            if (isset($cedu_id)) {
                $comando->bindParam(':cedu_id', $cedu_id, \PDO::PARAM_INT);
            }

            if (isset($daca_id)) {
                $comando->bindParam(':daca_id', $daca_id, \PDO::PARAM_INT);
            }            

            if (isset($cedi_usuario_ingreso)) {
                $comando->bindParam(':cedi_usuario_ingreso', $cedi_usuario_ingreso, \PDO::PARAM_INT);
            }

            if (isset($fecha_transaccion)) {
                $comando->bindParam(':cedi_fecha_creacion', $fecha_transaccion, \PDO::PARAM_STR);
            }
            
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.curso_educativa_distributivo;');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    } 

    /**
     * Function Consultar si existe educativa distributivo.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarEdudistributivoexiste($cedu_id, $daca_id) {
        $con = \Yii::$app->db_academico;     
        $estado = 1;   
        $sql = "SELECT 	
                        count(*) as exitedistributivo                       
                        
                FROM " . $con->dbname . ".curso_educativa_distributivo                 
                WHERE 
                cedu_id = :cedu_id AND
                daca_id = :daca_id AND
                cedi_estado = :estado AND
                cedi_estado_logico = :estado ";        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT); 
        $comando->bindParam(":daca_id", $daca_id, \PDO::PARAM_INT);       
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Consultar daca_id segun paca_id, asi_id, pro_id, uaca_id, mod_id 
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarDistribuAca($paca_id, $asi_id, $pro_id, $uaca_id, $mod_id) {
        $con = \Yii::$app->db_academico;        
        $estado = 1;
        /*\app\models\Utilities::putMessageLogFile('estoy en consulta paca_id' . $paca_id);
        \app\models\Utilities::putMessageLogFile('estoy en consulta asi_id' . $asi_id);
        \app\models\Utilities::putMessageLogFile('estoy en consulta pro_id' . $pro_id);
        \app\models\Utilities::putMessageLogFile('estoy en consulta uaca_id' . $uaca_id);
        \app\models\Utilities::putMessageLogFile('estoy en consulta mod_id' . $mod_id);
        */$sql = "SELECT 
                daca_id
                FROM " . $con->dbname . ".distributivo_academico                 
                WHERE 
                    paca_id = :paca_id AND
                    asi_id = :asi_id AND
                    pro_id = :pro_id AND
                    uaca_id = :uaca_id AND
                    mod_id = :mod_id AND
                    daca_estado = :estado AND
                    daca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT); 
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_STR); 
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryOne();
                
        return $resultData;
    }

    /**
     * Function Obtiene información de unidades en educatica.
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDistEducativa($arrFiltro = array(), $reporte, $ids) {
        $con = \Yii::$app->db_academico;  
        $con1 = \Yii::$app->db_asgard;      
        $estado = 1;
        if ($ids == 1) {
            $campos = "
            ced.cedi_id,
            ced.cedu_id,
            ced.daca_id,  ";
        }    
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(pers.per_pri_nombre like :search OR ";           
            $str_search .= "pers.per_pri_apellido like :search) AND  ";  

            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "dia.paca_id = :paca_id AND ";
            }

            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $str_search .= "ced.cedu_id = :cedu_id AND ";
            }
                    
        }
        $sql = "SELECT 
                    $campos
                    cue.cedu_asi_nombre,
                    uaca.uaca_nombre,
                    moda.mod_nombre,
                    asig.asi_nombre,
                    concat(pers.per_pri_nombre, ' ', pers.per_pri_apellido) as profesor
                    FROM " . $con->dbname . ".curso_educativa_distributivo ced
                    INNER JOIN " . $con->dbname . ".curso_educativa cue ON cue.cedu_id = ced.cedu_id
                    INNER JOIN " . $con->dbname . ".distributivo_academico dia ON dia.daca_id = ced.daca_id
                    INNER JOIN " . $con->dbname . ".unidad_academica uaca ON uaca.uaca_id = dia.uaca_id
                    INNER JOIN " . $con->dbname . ".modalidad moda ON moda.mod_id = dia.mod_id
                    INNER JOIN " . $con->dbname . ".asignatura asig ON asig.asi_id = dia.asi_id
                    INNER JOIN " . $con->dbname . ".profesor pro ON pro.pro_id = dia.pro_id
                    INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = pro.per_id
                    WHERE 
                    $str_search 
                    ced.cedi_estado = :estado AND
                    ced.cedi_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $curso = $arrFiltro["curso"];
                $comando->bindParam(":cedu_id", $curso, \PDO::PARAM_INT);
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
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData;
        }
    }

    /**
     * Function eliminar el distributivo estados en 0.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function eliminarDistributivo($cedi_id, $cedi_usuario_modifica, $cedi_fecha_modificacion) {
        $estado = 0;
        $con = \Yii::$app->db_academico;
        
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".curso_educativa_distributivo		       
                      SET cedi_estado = :cedi_estado,
                          cedi_usuario_modifica = :cedi_usuario_modifica,
                          cedi_fecha_modificacion = :cedi_fecha_modificacion                          
                      WHERE 
                      cedi_id = :cedi_id ");
            $comando->bindParam(":cedi_id", $cedi_id, \PDO::PARAM_INT);          
            $comando->bindParam(":cedi_usuario_modifica", $cedi_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":cedi_fecha_modificacion", $cedi_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":cedi_estado", $estado, \PDO::PARAM_STR);
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
}
