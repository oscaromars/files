<?php
namespace app\modules\academico\models;
use app\models\Utilities;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\Asignatura;
use yii\base\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "curso_educativa".
 *
 * @property int $cedu_id
 * @property int $paca_id
 * @property int $cedu_asi_id
 * @property string $cedu_asi_nombre
 * @property int $cedu_usuario_ingreso
 * @property int $cedu_usuario_modifica
 * @property string $cedu_estado
 * @property string $cedu_fecha_creacion
 * @property string $cedu_fecha_modificacion
 * @property string $cedu_estado_logico
 *
 * @property PeriodoAcademico $paca
 * @property CursoEducativaEstudiante[] $cursoEducativaEstudiantes
 * @property CursoEducativaUnidad[] $cursoEducativaUnidads
 */
class CursoEducativa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * Function findIdentity
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paca_id', 'cedu_asi_id', 'cedu_asi_nombre', 'cedu_usuario_ingreso', 'cedu_estado', 'cedu_estado_logico'], 'required'],
            [['paca_id', 'cedu_asi_id', 'cedu_usuario_ingreso', 'cedu_usuario_modifica'], 'integer'],
            [['cedu_fecha_creacion', 'cedu_fecha_modificacion'], 'safe'],
            [['cedu_asi_nombre'], 'string', 'max' => 500],
            [['cedu_estado', 'cedu_estado_logico'], 'string', 'max' => 1],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cedu_id' => 'Cedu ID',
            'paca_id' => 'Paca ID',
            'cedu_asi_id' => 'Cedu Asi ID',
            'cedu_asi_nombre' => 'Cedu Asi Nombre',
            'cedu_usuario_ingreso' => 'Cedu Usuario Ingreso',
            'cedu_usuario_modifica' => 'Cedu Usuario Modifica',
            'cedu_estado' => 'Cedu Estado',
            'cedu_fecha_creacion' => 'Cedu Fecha Creacion',
            'cedu_fecha_modificacion' => 'Cedu Fecha Modificacion',
            'cedu_estado_logico' => 'Cedu Estado Logico',
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
    public function getCursoEducativaEstudiantes()
    {
        return $this->hasMany(CursoEducativaEstudiante::className(), ['cedu_id' => 'cedu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursoEducativaUnidads()
    {
        return $this->hasMany(CursoEducativaUnidad::className(), ['cedu_id' => 'cedu_id']);
    }
    public function CargarArchivocursoeducativa($fname, $paca_id) {
        \app\models\Utilities::putMessageLogFile('Files modelo ...: ' . $fname);
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "educativa/" . $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacci??n actual
        $model_asignatura = new Asignatura();
        $mod_educativa = new CursoEducativa();
        /* print("pasa cargar"); */
        if ($trans !== null) {
            $trans = null; // si existe la transacci??n entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
        }   
        if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {
            //Creacion de PHPExcel object
            try {
                $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $dataArr = array();
                $validacion = false;
                $row_global = 0;

                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10 
                    $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'                    
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                    for ($row = 2; $row <= $highestRow; ++$row) {
                        $row_global = $row_global + 1;
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $dataArr[$row_global][$col] = $cell->getValue();
                        }
                    }                   
                }
                $fila = 0;
                foreach ($dataArr as $val) {
                    //\app\models\Utilities::putMessageLogFile('cedula ...: ' .$val[2]);
                    if (!is_null($val[1]) || $val[1]) {
                        $val[1] = strval($val[1]);
                        $val[3] = strval($val[3]);
                        // YA NO SE CONSULTA ALIAS, SE PONE UN VALOR POR DEECTO GUARDE HASTA ACTUALIZAR EL MODELO
                        // YA QUE ASI_ID VA SER NULL
                        //$asi_id = $model_asignatura->consultarAsindxalias($val[3]);
                        //\app\models\Utilities::putMessageLogFile('asi_id consulta ...: ' .$asi_id['asi_id']);
                        $fila++;         
                        //if (!empty($asi_id['asi_id'])) {
                        $existe = $mod_educativa->consultarcursoeducativaexi($paca_id, $val[1], $val[2]);
                        \app\models\Utilities::putMessageLogFile('existe consulta ...: ' . $existe['existe_curso']);
                        if ($existe['existe_curso'] == 0) {
                        // ESTE BORRAR DESPUES YA QUE DEBE SER NULL
                        //$asi_id['asi_id'] = 1;
                        $save_documento = $this->saveDocumentoDB($val, $paca_id/*, $asi_id['asi_id']*/);
                        if (!$save_documento) {                   
                            $arroout["status"] = FALSE;
                            $arroout["error"] = null;
                            $arroout["message"] = "Error al guardar el registro de la Fila => N??$fila Asignatura => $val[2].";
                            $arroout["data"] = null;
                            $arroout["validate"] = $val;
                            \app\models\Utilities::putMessageLogFile('error fila ' . $fila);
                            return $arroout;
                        }
                      }else{
                        $ingresadoant .= $val[1] . ", ";
                    }
                   /* }
                    else{
                        $noasignaturas .= $val[1] . ", ";
                    }*/
                  }
                }
                //\app\models\Utilities::putMessageLogFile('anterio ...: ' . $ingresadoant);
                //\app\models\Utilities::putMessageLogFile('no asignatura ...: ' . $noasignaturas);
                if ($trans !== null)
                    $trans->commit();
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                $arroout["validate"] = $fila;
                $arroout["repetido"] = substr($ingresadoant, 0, -2);
                $arroout["noasignatura"] = substr($noestudiantes, 0, -2);
                //\app\models\Utilities::putMessageLogFile('no asignatura array...: ' . $arroout["noasignatura"]);
                return $arroout;
            } catch (\Exception $ex) {
                if ($trans !== null)
                    $trans->rollback();
                $arroout["status"] = FALSE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                return $arroout;
            }
        }
    }

    public function saveDocumentoDB($val, $paca_id/*, $asi_id*/){
        $usu_id = Yii::$app->session->get("PB_iduser"); ;
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $mod_educativacurso = new CursoEducativa();
        $mod_educativacurso->paca_id = $paca_id;
        // $mod_educativacurso->asi_id = $asi_id;
        $mod_educativacurso->cedu_asi_id = $val[1];
        $mod_educativacurso->cedu_asi_nombre = $val[2];
        $mod_educativacurso->cedu_usuario_ingreso = $usu_id;
        $mod_educativacurso->cedu_estado = "1";
        $mod_educativacurso->cedu_fecha_creacion = $fecha_transaccion;
        $mod_educativacurso->cedu_estado_logico = "1";

        \app\models\Utilities::putMessageLogFile('paca_id ' .$paca_id);
        \app\models\Utilities::putMessageLogFile('asi_id '. $asi_id);
        \app\models\Utilities::putMessageLogFile('1: ' .$val[1]);
        \app\models\Utilities::putMessageLogFile('2: ' .$val[2]);
        \app\models\Utilities::putMessageLogFile('3: ' .$val[3]);
        \app\models\Utilities::putMessageLogFile('fecha: ' .$fecha_transaccion);
        \app\models\Utilities::putMessageLogFile('usu_id: ' .$usu_id);        

        return $mod_educativacurso->save();
    }

    /**
     * Function Consultar si ya se ha cargado la informacion anteriormente en curso educativa.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarcursoeducativaexi($paca_id, $cedu_asi_id, $cedu_asi_nombre) {
        $con = \Yii::$app->db_academico;     
        $estado = 1;         
       /*\app\models\Utilities::putMessageLogFile('entro 2 : ' .$paca_id);  
       \app\models\Utilities::putMessageLogFile('entro 3 : ' .$cedu_asi_id);  
       \app\models\Utilities::putMessageLogFile('entro 4 : ' .$cedu_asi_nombre);  */
        $sql = "SELECT 	
                        count(*) as existe_curso                       
                        
                FROM " . $con->dbname . ".curso_educativa                 
                WHERE 
                paca_id = :paca_id AND
                cedu_asi_id = :cedu_asi_id AND                
                cedu_asi_nombre = :cedu_asi_nombre AND
                cedu_estado = :estado AND
                cedu_estado_logico = :estado ";
        // \app\models\Utilities::putMessageLogFile('entro: ' .$sql); 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":cedu_asi_id", $cedu_asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":cedu_asi_nombre", $cedu_asi_nombre, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Obtiene informaci??n de cursos en educatica.
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarCursoEducativa($arrFiltro = array(), $reporte, $ids) {
        $con = \Yii::$app->db_academico;        
        $estado = 1;
        if ($ids == 1) {
            $campos = "
            cur.cedu_id,
            cur.paca_id, 
            /* cur.asi_id, */ ";
        }    
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(cur.cedu_asi_nombre like :search) AND ";
   
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "cur.paca_id = :paca_id AND ";
            }
            /*if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "cur.asi_id = :asi_id AND ";
            } */           
        }
        $sql = "SELECT  $campos 
                        ifnull(CONCAT(sem.saca_anio, ' (',blq.baca_nombre,'-',sem.saca_nombre, ')'),sem.saca_anio) as periodo,
                        -- asi.asi_nombre,
                        cur.cedu_asi_id,
                        cur.cedu_asi_nombre
                FROM " . $con->dbname . ".curso_educativa cur 
                -- INNER JOIN " . $con->dbname . ".asignatura asi on asi.asi_id = cur.asi_id
                INNER JOIN " . $con->dbname . ".periodo_academico pera ON pera.paca_id = cur.paca_id
                LEFT JOIN " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = pera.saca_id
                LEFT JOIN " . $con->dbname . ".bloque_academico blq ON blq.baca_id = pera.baca_id          
                WHERE $str_search  cur.cedu_estado = :estado
                AND cur.cedu_estado_logico = :estado
                /* AND asi.asi_estado = :estado
                AND asi.asi_estado_logico = :estado */";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            /* if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $asignatura = $arrFiltro["asignatura"];
                $comando->bindParam(":asi_id", $asignatura, \PDO::PARAM_INT);
            }  */        
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
     * Function guardar curso 
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el c??digo de curso).
     */
    public function insertarCursoeducativa($paca_id, /*$asi_id,*/ $cedu_asi_id, $cedu_asi_nombre, $cedu_usuario_ingreso) {
        //\app\models\Utilities::putMessageLogFile('entro insercurso...: ' ); 
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacci??n actual
        if ($trans !== null) {
            $trans = null; // si existe la transacci??n entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
        }
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        
        /*$param_sql .= ", cedu_fecha_creacion";
        $bsol_sql .= ", 1";*/
        
        $param_sql = "cedu_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", cedu_estado";
        $bsol_sql .= ", 1";
        if (isset($paca_id)) {
            $param_sql .= ", paca_id";
            $bsol_sql .= ", :paca_id";
        }

        /*if (isset($asi_id)) {
            $param_sql .= ", asi_id";
            $bsol_sql .= ", :asi_id";
        }*/

        if (isset($cedu_asi_id)) {
            $param_sql .= ", cedu_asi_id";
            $bsol_sql .= ", :cedu_asi_id";
        }

        if (isset($cedu_asi_nombre)) {
            $param_sql .= ", cedu_asi_nombre";
            $bsol_sql .= ", :cedu_asi_nombre";
        }

        if (isset($cedu_usuario_ingreso)) {
            $param_sql .= ", cedu_usuario_ingreso";
            $bsol_sql .= ", :cedu_usuario_ingreso";
        }

        if (isset($fecha_transaccion)) {
            $param_sql .= ",cedu_fecha_creacion";
            $bsol_sql .= ", :cedu_fecha_creacion";
        }   

        try {
            $sql = "INSERT INTO " . $con->dbname . ".curso_educativa ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

             \app\models\Utilities::putMessageLogFile('sql...: ' .$sql); 
            if (isset($paca_id)) {
                $comando->bindParam(':paca_id', $paca_id, \PDO::PARAM_INT);
            }

            /*if (isset($asi_id)) {
                $comando->bindParam(':asi_id', $asi_id, \PDO::PARAM_INT);
            }*/

            if (isset($cedu_asi_id)) {
                $comando->bindParam(':cedu_asi_id', $cedu_asi_id, \PDO::PARAM_INT);
            }

            if (isset($cedu_asi_nombre)) {
                $comando->bindParam(':cedu_asi_nombre', $cedu_asi_nombre, \PDO::PARAM_STR);
            }

            if (isset($cedu_usuario_ingreso)) {
                $comando->bindParam(':cedu_usuario_ingreso', $cedu_usuario_ingreso, \PDO::PARAM_INT);
            }

            if (isset($fecha_transaccion)) {
                $comando->bindParam(':cedu_fecha_creacion', $fecha_transaccion, \PDO::PARAM_STR);
            }
            
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.curso_educativa');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function Consultar datos x id en curso educativa.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarCursoxid($cedu_id) {
        $con = \Yii::$app->db_academico;     
        $estado = 1; 
        $sql = "SELECT 	
                        cedu_id,
                        paca_id,
                        -- asi_id,
                        cedu_asi_id,
                        cedu_asi_nombre
                        
                FROM " . $con->dbname . ".curso_educativa                 
                WHERE 
                cedu_id = :cedu_id AND               
                cedu_estado = :estado AND
                cedu_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT);  
        $resultData = $comando->queryOne();      
        return $resultData;
    }

    /**
     * Function modificar cursos educativa.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarCursoeducativa($cedu_id, $paca_id, $cedu_asi_id, $cedu_asi_nombre, $cedu_usuario_modifica) {
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $con = \Yii::$app->db_academico;
        $estado = 1; 
        if ($trans !== null) {
            $trans = null; // si existe la transacci??n entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".curso_educativa		       
                      SET paca_id = :paca_id,
                          cedu_asi_id = :cedu_asi_id,
                          cedu_asi_nombre = :cedu_asi_nombre,
                          cedu_usuario_modifica = :cedu_usuario_modifica,
                          cedu_fecha_modificacion = :cedu_fecha_modificacion                          
                      WHERE 
                      cedu_id = :cedu_id AND
                      cedu_estado = :estado AND
                      cedu_estado_logico = :estado");
            $comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT);  
            $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);  
            $comando->bindParam(":cedu_asi_id", $cedu_asi_id, \PDO::PARAM_INT); 
            $comando->bindParam(":cedu_asi_nombre", $cedu_asi_nombre, \PDO::PARAM_STR);                    
            $comando->bindParam(":cedu_usuario_modifica", $cedu_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":cedu_fecha_modificacion", $fecha_transaccion, \PDO::PARAM_STR);
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
     * Function eliminar el curso estados en 0.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function eliminarCurso($cedu_id, $cedu_usuario_modifica, $cedu_fecha_modificacion) {
        $estado = 0;
        $con = \Yii::$app->db_academico;
        
        if ($trans !== null) {
            $trans = null; // si existe la transacci??n entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacci??n entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".curso_educativa		       
                      SET cedu_estado = :cedu_estado,
                          cedu_usuario_modifica = :cedu_usuario_modifica,
                          cedu_fecha_modificacion = :cedu_fecha_modificacion                          
                      WHERE 
                      cedu_id = :cedu_id ");
            $comando->bindParam(":cedu_id", $cedu_id, \PDO::PARAM_INT);          
            $comando->bindParam(":cedu_usuario_modifica", $cedu_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":cedu_fecha_modificacion", $cedu_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":cedu_estado", $estado, \PDO::PARAM_STR);
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
     * Function Consultar cursos por paca_id.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarCursosxpacaid($paca_id) {
        $con = \Yii::$app->db_academico;      
        $estado = 1;

        $sql = "SELECT 	Distinct
                        cedu_id as id,
                        cedu_asi_nombre as name               
                        
                FROM " . $con->dbname . ".curso_educativa              
                WHERE 
                paca_id = :paca_id AND               
                cedu_estado = :estado AND
                cedu_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Consultar si existe curso educativa.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarCursoexiste($cedu_asi_id) {
        $con = \Yii::$app->db_academico;     
        $estado = 1;         
       /*\app\models\Utilities::putMessageLogFile('entro 2 : ' .$paca_id);  
       \app\models\Utilities::putMessageLogFile('entro 3 : ' .$cedu_asi_id);  
       \app\models\Utilities::putMessageLogFile('entro 4 : ' .$cedu_asi_nombre);  */
        $sql = "SELECT 	
                        cedu_id                       
                        
                FROM " . $con->dbname . ".curso_educativa                 
                WHERE 
                cedu_asi_id = :cedu_asi_id AND
                cedu_estado = :estado AND
                cedu_estado_logico = :estado ";
        // \app\models\Utilities::putMessageLogFile('entro: ' .$sql); 
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedu_asi_id", $cedu_asi_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Consultar todos los cursos.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarCursostodos() {
        $con = \Yii::$app->db_academico;      
        $estado = 1;

        $sql = "SELECT 	Distinct
                        cedu_id as id,
                        cedu_asi_nombre as name               
                        
                FROM " . $con->dbname . ".curso_educativa              
                WHERE                           
                cedu_estado = :estado AND
                cedu_estado_logico = :estado";

        $comando = $con->createCommand($sql);       
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Consultar cursos de distributivo por paca_id.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarCursosdistributivoxpacaid($paca_id) {
        $con = \Yii::$app->db_academico;      
        $estado = 1;

        $sql = "SELECT 	Distinct
                        cedu.cedu_id as id,
                        cedu.cedu_asi_nombre as name               
                        
                FROM " . $con->dbname . ".curso_educativa_distributivo cedi
                INNER JOIN " . $con->dbname . ".curso_educativa cedu  ON cedu.cedu_id = cedi.cedu_id       
                WHERE 
                cedu.paca_id = :paca_id AND               
                cedu.cedu_estado = :estado AND
                cedu.cedu_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
