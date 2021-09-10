<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "periodo_academico_met_ingreso".
 *
 * @property int $pami_id
 * @property int $pami_anio
 * @property int $pami_mes
 * @property int $ming_id
 * @property string $pami_fecha_inicio
 * @property string $pami_fecha_fin
 * @property string $pami_codigo
 * @property int $pami_usuario_ingreso
 * @property int $pami_usuario_modifica
 * @property string $pami_estado
 * @property string $pami_fecha_creacion
 * @property string $pami_fecha_modificacion
 * @property string $pami_estado_logico
 *
 * @property Paralelo[] $paralelos
 * @property PlanificacionEstudioAcademico[] $planificacionEstudioAcademicos
 */
class PeriodoAcademicoMetIngreso extends  \app\modules\academico\components\CActiveRecord  {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'periodo_academico_met_ingreso';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pami_anio', 'pami_mes', 'ming_id', 'pami_usuario_ingreso', 'pami_usuario_modifica'], 'integer'],
            [['ming_id', 'pami_codigo', 'pami_usuario_ingreso', 'pami_estado', 'pami_estado_logico'], 'required'],
            [['pami_fecha_inicio', 'pami_fecha_fin', 'pami_fecha_creacion', 'pami_fecha_modificacion'], 'safe'],
            [['pami_codigo'], 'string', 'max' => 10],
            [['pami_estado', 'pami_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pami_id' => 'Pami ID',
            'pami_anio' => 'Pami Anio',
            'pami_mes' => 'Pami Mes',
            'ming_id' => 'Ming ID',
            'pami_fecha_inicio' => 'Pami Fecha Inicio',
            'pami_fecha_fin' => 'Pami Fecha Fin',
            'pami_codigo' => 'Pami Codigo',
            'pami_usuario_ingreso' => 'Pami Usuario Ingreso',
            'pami_usuario_modifica' => 'Pami Usuario Modifica',
            'pami_estado' => 'Pami Estado',
            'pami_fecha_creacion' => 'Pami Fecha Creacion',
            'pami_fecha_modificacion' => 'Pami Fecha Modificacion',
            'pami_estado_logico' => 'Pami Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParalelos() {
        return $this->hasMany(Paralelo::className(), ['pami_id' => 'pami_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionEstudioAcademicos() {
        return $this->hasMany(PlanificacionEstudioAcademico::className(), ['pami_id' => 'pami_id']);
    }

    /**
     * Function listarPeriodos
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (información de los períodos por método de ingreso en online.)
     */
    public function listarPeriodos($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_captacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(pami_codigo like :search OR ";
            $str_search .= "pami_anio like :search) AND";
            if ($arrFiltro['mes'] != "" && $arrFiltro['mes'] > 0) {
                $str_search .= " pami_mes = :mes AND";
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "  pami_fecha_inicio >= :fec_ini AND";
                $str_search .= "  pami_fecha_fin <= :fec_fin AND";
            }
        }

        $sql = "SELECT  pami_id, 
                        pami_anio anio, 
                        pami_mes mes, 
                        pami_codigo codigo,
                        ming.ming_descripcion metodo, 	   
                        Date_format(pami_fecha_inicio, '%Y-%m-%d') fecha_inicial, 
                        Date_format(pami_fecha_fin, '%Y-%m-%d') fecha_final,
                        ifnull((select count(*) FROM db_academico.paralelo par WHERE par.pami_id = pami.pami_id),0) paralelos
                FROM " . $con->dbname . ".periodo_academico_met_ingreso pami inner join " . $con1->dbname . ".metodo_ingreso ming on ming.ming_id = pami.ming_id
                WHERE 
                           $str_search
                           pami_estado = :estado AND 
                           pami_estado_logico = :estado AND
                           ming_estado = :estado AND 
                           ming_estado_logico = :estado
                ORDER BY pami_fecha_inicio DESC";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $mes = $arrFiltro["mes"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['mes'] != "" && $arrFiltro['mes'] > 0) {
                $comando->bindParam(":mes", $mes, \PDO::PARAM_INT);
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
        }
        $resultData = $comando->queryall();
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
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function insertarPeriodo (Registro de los períodos por método de ingreso)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function insertarPeriodo($anio, $mes, $uaca_id, $mod_id, $ming, $codigo, $fec_inicial, $fec_final, $usu_ingreso) {
        $con = \Yii::$app->db_academico;

        $param_sql = "pami_estado_logico";
        $bper_sql = "1";

        $param_sql .= ", pami_estado";
        $bper_sql .= ", 1";

        if (isset($anio)) {
            $param_sql .= ", pami_anio";
            $bper_sql .= ", :pami_anio";
        }

        if (isset($mes)) {
            $param_sql .= ", pami_mes";
            $bper_sql .= ", :pami_mes";
        }

        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bper_sql .= ", :uaca_id";
        }

        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bper_sql .= ", :mod_id";
        }

        if (isset($ming)) {
            $param_sql .= ", ming_id";
            $bper_sql .= ", :ming_id";
        }

        if (isset($codigo)) {
            $param_sql .= ", pami_codigo";
            $bper_sql .= ", :pami_codigo";
        }

        if (isset($fec_inicial)) {
            $param_sql .= ", pami_fecha_inicio";
            $bper_sql .= ", :pami_fecha_inicio";
        }

        if (isset($fec_final)) {
            $param_sql .= ", pami_fecha_fin";
            $bper_sql .= ", :pami_fecha_fin";
        }

        if (isset($usu_ingreso)) {
            $param_sql .= ", pami_usuario_ingreso";
            $bper_sql .= ", :pami_usuario_ingreso";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".periodo_academico_met_ingreso ($param_sql) VALUES($bper_sql)";
            $comando = $con->createCommand($sql);

            if (isset($anio))
                $comando->bindParam(':pami_anio', $anio, \PDO::PARAM_INT);

            if (isset($mes))
                $comando->bindParam(':pami_mes', $mes, \PDO::PARAM_INT);

            if (isset($uaca_id))
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);

            if (isset($mod_id))
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);

            if (isset($ming))
                $comando->bindParam(':ming_id', $ming, \PDO::PARAM_INT);

            if (isset($codigo))
                $comando->bindParam(':pami_codigo', $codigo, \PDO::PARAM_STR);

            if (isset($fec_inicial))
                $comando->bindParam(':pami_fecha_inicio', $fec_inicial, \PDO::PARAM_STR);

            if (isset($fec_final))
                $comando->bindParam(':pami_fecha_fin', $fec_final, \PDO::PARAM_STR);

            if (isset($usu_ingreso))
                $comando->bindParam(':pami_usuario_ingreso', $usu_ingreso, \PDO::PARAM_INT);

            $result = $comando->execute();
            return $con->getLastInsertID($con->dbname . '.periodo_academico_met_ingreso');
        } catch (Exception $ex) {
            return FALSE;
        }
    }

    /**
     * Function VerificarPeriodo
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Verificar que no se repita los datos principales de período por método ingreso.)
     */
    public function VerificarPeriodo($anio, $mes, $uaca, $mod, $ming) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT pami_id
                FROM " . $con->dbname . ".periodo_academico_met_ingreso pmin
                WHERE pmin.pami_anio = :anio
                and pmin.pami_mes = :mes
                and pmin.uaca_id = :uaca
                and pmin.mod_id = :mod
                and pmin.ming_id = :ming                
                and pmin.pami_estado_logico = :estado
                and pmin.pami_estado = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":anio", $anio, \PDO::PARAM_INT);
        $comando->bindParam(":mes", $mes, \PDO::PARAM_INT);
        $comando->bindParam(":uaca", $uaca, \PDO::PARAM_INT);
        $comando->bindParam(":mod", $mod, \PDO::PARAM_INT);
        $comando->bindParam(":ming", $ming, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function listarParalelos
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (información de los paralelos por período.)
     */
    public function listarParalelos($pmin_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;        
        $sql = "SELECT 	par.par_nombre nombre,
                        par.par_descripcion descripcion, 
                        par.par_num_cupo cupo                 
                FROM " . $con->dbname . ".paralelo par
                WHERE par.pami_id = :pmin_id
                      and par.par_estado = :estado
                      and par.par_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pmin_id", $pmin_id, \PDO::PARAM_INT);

        $resultData = $comando->queryall();
        \app\models\Utilities::putMessageLogFile($resultData);     
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
     * Function insertarParalelo (Registro de los paralelos)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function insertarParalelo($pmin_id, $nombre, $descripcion, $cupo, $usu_id) {
        $con = \Yii::$app->db_academico;        

        $param_sql = "par_estado_logico";
        $bcur_sql = "1";

        $param_sql .= ", par_estado";
        $bcur_sql .= ", 1";

        if (isset($pmin_id)) {
            $param_sql .= ", pami_id";
            $bcur_sql .= ", :pami_id";
        }
        if (isset($nombre)) {
            $param_sql .= ", par_nombre";
            $bcur_sql .= ", :par_nombre";
        }
        if (isset($descripcion)) {
            $param_sql .= ", par_descripcion";
            $bcur_sql .= ", :par_descripcion";
        }
        if (isset($cupo)) {
            $param_sql .= ", par_num_cupo";
            $bcur_sql .= ", :par_num_cupo";
        }
        if (isset($usu_id)) {
            $param_sql .= ", par_usuario_ingreso";
            $bcur_sql .= ", :par_usuario_ingreso";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".paralelo ($param_sql) VALUES($bcur_sql)";
            $comando = $con->createCommand($sql);

            if (isset($pmin_id))
                $comando->bindParam(':pami_id', $pmin_id, \PDO::PARAM_INT);
            
            if (isset($nombre))
                $comando->bindParam(':par_nombre', $nombre, \PDO::PARAM_STR);

            if (isset($descripcion))
                $comando->bindParam(':par_descripcion', $descripcion, \PDO::PARAM_STR);

            if (isset($cupo))
                $comando->bindParam(':par_num_cupo', $cupo, \PDO::PARAM_INT);

            if (isset($usu_id))
                $comando->bindParam(':par_usuario_ingreso', $usu_id, \PDO::PARAM_INT);

            $result = $comando->execute();                       
            return $con->getLastInsertID($con->dbname . '.paralelo');
        } catch (Exception $ex) {            
            return FALSE;
        }
    }  


    /**
     * Function consulta los periodos. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodo() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                   pera.pami_id as id,
                   pera.pami_codigo as name
                FROM 
                   " . $con->dbname . ".periodo_academico_met_ingreso  pera WHERE ";
        $sql .= "  pera.pami_estado = :estado AND
                   pera.pami_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    /**
     * Function consulta los periodos. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodoanterior($periodo) {
        $con = \Yii::$app->db_academico;
        $estado = 1;        
        $sql = "SELECT 
                   pera.pami_mes as mes,
                   pera.pami_anio as anio
                FROM 
                   " . $con->dbname . ".periodo_academico_met_ingreso  pera 
                WHERE
                   pera.pami_id =:periodo AND  
                   pera.pami_estado = :estado AND
                   pera.pami_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    
    /**
     * Function consultarPeriodoId
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $perid       
     * @return  
     */
    public function consultarPeriodoId($pmin_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT pami_anio, 
                    pami_mes, 
                    uaca_id, 
                    mod_id,
                    ming_id,
                    pami_codigo, 
                    DATE(pami_fecha_inicio) as fecha_desde, 
                    DATE(pami_fecha_fin) as fecha_hasta 
                FROM " . $con->dbname . ".periodo_academico_met_ingreso 
                WHERE   pami_id = :pmin_id AND
                        pami_estado = :estado AND
                        pami_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pmin_id", $pmin_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

     /**
     * Function modificarPeriodo
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     *          Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
    public function modificarPeriodo($pmin_id, $anio, $mes, $uaca_id, $mod, $ming, $codigo, $fec_desde, $fec_hasta, $usuario_modifica) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $pmin_fecha_modificacion = date("Y-m-d H:i:s");
        
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".periodo_academico_met_ingreso
                      SET 
                        pami_anio = :anio,
                        pami_mes = :mes,
                        uaca_id = :uaca_id,
                        mod_id = :mod,
                        ming_id = :ming,
                        pami_codigo = :codigo,                         
                        pami_fecha_inicio = :fec_desde,
                        pami_fecha_fin = :fec_hasta,
                        pami_usuario_modifica = :usuario_modifica,
                        pami_fecha_modificacion = :pmin_fecha_modificacion
                      WHERE 
                        pami_id = :pmin_id AND 
                        pami_estado = :estado AND
                        pami_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":anio", $anio, \PDO::PARAM_INT);
            $comando->bindParam(":mes", $mes, \PDO::PARAM_INT);
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
            $comando->bindParam(":mod", $mod, \PDO::PARAM_INT);
            $comando->bindParam(":ming", $ming, \PDO::PARAM_INT);
            $comando->bindParam(":codigo", $codigo, \PDO::PARAM_STR);            
            $comando->bindParam(":fec_desde", $fec_desde, \PDO::PARAM_STR);
            $comando->bindParam(":fec_hasta", $fec_hasta, \PDO::PARAM_STR);
            $comando->bindParam(":usuario_modifica", $usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":pmin_fecha_modificacion", $pmin_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":pmin_id", $pmin_id, \PDO::PARAM_INT);
            $response = $comando->execute();
            
            return $response;
        } catch (Exception $ex) {            
            return FALSE;
        }
    }
    
    /**
     * Function insertarPlanificacionMeting (Registro de la planificación académica por método de ingreso.)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function insertarPlanificacionMeting($uaca_id, $mod_id, $paca_id, $pami_id, $maca_id, $usu_id) {
        $con = \Yii::$app->db_academico;        

        $param_sql = "peac_estado_logico";
        $bcur_sql = "1";

        $param_sql .= ", peac_estado";
        $bcur_sql .= ", 1";

        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bcur_sql .= ", :uaca_id";
        }
        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bcur_sql .= ", :mod_id";
        }
        if (isset($paca_id)) {
            $param_sql .= ", paca_id";
            $bcur_sql .= ", :paca_id";
        }
        if (isset($pami_id)) {
            $param_sql .= ", pami_id";
            $bcur_sql .= ", :pami_id";
        }
        if (isset($maca_id)) {
            $param_sql .= ", maca_id";
            $bcur_sql .= ", :maca_id";
        }     
        if (isset($usu_id)) {
            $param_sql .= ", peac_usuario_ingreso";
            $bcur_sql .= ", :peac_usuario_ingreso";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".planificacion_estudio_academico ($param_sql) VALUES($bcur_sql)";
            $comando = $con->createCommand($sql);

            if (isset($uaca_id))
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);            
            if (isset($mod_id))
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);
            if (isset($paca_id))
                $comando->bindParam(':paca_id', $paca_id, \PDO::PARAM_INT);
            if (isset($pami_id))
                $comando->bindParam(':pami_id', $pami_id, \PDO::PARAM_INT);
            if (isset($maca_id))
                $comando->bindParam(':maca_id', $maca_id, \PDO::PARAM_INT);
            if (isset($usu_id))
                $comando->bindParam(':peac_usuario_ingreso', $usu_id, \PDO::PARAM_INT);

            $result = $comando->execute();                       
            return $con->getLastInsertID($con->dbname . '.planificacion_estudio_academico');
        } catch (Exception $ex) {            
            return FALSE;
        }
    }  
    
    /**
     * Function consulta las mallas. 
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarMallas($uaca_id, $mod_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT maca_id 
                FROM ". $con->dbname. ".malla_academica 
                WHERE uaca_id = :uaca_id and mod_id = :mod_id
                      and maca_tipo = '1'
                      and maca_estado = :estado
                      and maca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    /**
     * Function consulta los periodos academcicos. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodoAcademico($paca_id = null) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $condition = "";
        if(isset($paca_id) && $paca_id > 0){
            $condition .= "pera.paca_id = :id AND ";
        }
        $sql = "SELECT
                   pera.paca_id as id,
                   ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),sem.saca_anio) as name
                FROM 
                   " . $con->dbname . ".periodo_academico pera "
                . "LEFT JOIN " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = pera.saca_id "
                . "LEFT JOIN " . $con->dbname . ".bloque_academico blq ON blq.baca_id = pera.baca_id";
        $sql .= "  WHERE $condition pera.paca_activo = 'A' AND
                   pera.paca_estado = :estado AND
                   pera.paca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if(isset($paca_id) && $paca_id > 0){
            $comando->bindParam(":id", $paca_id, \PDO::PARAM_INT);
        }
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta los periodos academicos incluso con estado inactivo, para listarestudiantespago. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodoAcademicotodos($paca_id = null) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $condition = "";
        if(isset($paca_id) && $paca_id > 0){
            $condition .= "pera.paca_id = :id AND ";
        }
        $sql = "SELECT
                   pera.paca_id as id,
                   ifnull(CONCAT(sem.saca_anio, ' (',blq.baca_nombre,'-',sem.saca_nombre, ')'),sem.saca_anio) as name
                FROM 
                   " . $con->dbname . ".periodo_academico pera "
                . "LEFT JOIN " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = pera.saca_id "
                . "LEFT JOIN " . $con->dbname . ".bloque_academico blq ON blq.baca_id = pera.baca_id"
                . " ORDER BY 1 ";
                /*$sql .= "  WHERE $condition pera.paca_activo = 'A' AND
                   pera.paca_estado = :estado AND
                   pera.paca_estado_logico = :estado";*/

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if(isset($paca_id) && $paca_id > 0){
            $comando->bindParam(":id", $paca_id, \PDO::PARAM_INT);
        }
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta los periodos academicos incluso con estado inactivo, para listarestudiantespago.
     * @author Luis Cajamarca <analistadesarrollo04@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodoActivos() {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT  paca.paca_id as id,
                    ifnull(CONCAT(blq.baca_nombre,'-',sem.saca_nombre,' ',sem.saca_anio),'') as nombre
                    -- , blq.baca_nombre as bloque
                FROM db_academico.periodo_academico paca
                inner join db_academico.semestre_academico sem  ON sem.saca_id = paca.saca_id
                inner join db_academico.bloque_academico blq ON blq.baca_id = paca.baca_id
                WHERE paca.paca_activo = 'A' AND
                    -- now() >= pera.paca_fecha_inicio and pera.paca_fecha_fin<= now() and
                    paca.paca_estado = :estado AND paca.paca_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    
}
