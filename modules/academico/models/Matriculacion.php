<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\modules\academico\models\MallaAcademicaDetalle;
use app\modules\academico\models\ProgramaCostoCredito;


/**
 * This is the model class for table "matriculacion".
 *
 * @property int $mat_id
 * @property int $daca_id
 * @property int $asp_id
 * @property int $est_id
 * @property int $sins_id
 * @property string $mat_fecha_matriculacion
 * @property int $mat_usuario_ingreso
 * @property int $mat_usuario_modifica
 * @property string $mat_estado
 * @property string $mat_fecha_creacion
 * @property string $mat_fecha_modificacion
 * @property string $mat_estado_logico
 *
 * @property AsignacionParalelo[] $asignacionParalelos
 * @property DistributivoAcademico $daca
 * @property Estudiante $est
 */
class Matriculacion extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'matriculacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['daca_id', 'asp_id', 'est_id', 'sins_id', 'mat_usuario_ingreso', 'mat_usuario_modifica'], 'integer'],
            [['mat_fecha_matriculacion', 'mat_fecha_creacion', 'mat_fecha_modificacion'], 'safe'],
            [['mat_usuario_ingreso', 'mat_estado', 'mat_estado_logico'], 'required'],
            [['mat_estado', 'mat_estado_logico'], 'string', 'max' => 1],
            [['daca_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistributivoAcademico::className(), 'targetAttribute' => ['daca_id' => 'daca_id']],
            [['est_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudiante::className(), 'targetAttribute' => ['est_id' => 'est_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'mat_id' => 'Mat ID',
            'daca_id' => 'Daca ID',
            'asp_id' => 'Asp ID',
            'est_id' => 'Est ID',
            'sins_id' => 'Sins ID',
            'mat_fecha_matriculacion' => 'Mat Fecha Matriculacion',
            'mat_usuario_ingreso' => 'Mat Usuario Ingreso',
            'mat_usuario_modifica' => 'Mat Usuario Modifica',
            'mat_estado' => 'Mat Estado',
            'mat_fecha_creacion' => 'Mat Fecha Creacion',
            'mat_fecha_modificacion' => 'Mat Fecha Modificacion',
            'mat_estado_logico' => 'Mat Estado Logico',
        ];
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacionParalelos() {
        return $this->hasMany(AsignacionParalelo::className(), ['mat_id' => 'mat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDaca() {
        return $this->hasOne(DistributivoAcademico::className(), ['daca_id' => 'daca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst() {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }
    
    public function insertarMatriculacion($peac_id, $adm_id, $est_id, $sins_id, $mat_fecha_matriculacion, $mat_usuario_ingreso) {

        $con = \Yii::$app->db_academico;       
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "mat_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", mat_estado";
        $bsol_sql .= ", 1";
        if (isset($peac_id)) {
            $param_sql .= ", peac_id";
            $bsol_sql .= ", :peac_id";
        }

        if (isset($adm_id)) {
            $param_sql .= ", adm_id";
            $bsol_sql .= ", :adm_id";
        }

        if (isset($est_id)) {
            $param_sql .= ", est_id";
            $bsol_sql .= ", :est_id";
        }

        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bsol_sql .= ", :sins_id";
        }

        if (isset($mat_fecha_matriculacion)) {
            $param_sql .= ", mat_fecha_matriculacion";
            $bsol_sql .= ", :mat_fecha_matriculacion";
        }
        if (isset($mat_usuario_ingreso)) {
            $param_sql .= ", mat_usuario_ingreso";
            $bsol_sql .= ", :mat_usuario_ingreso";
        }        
        
        try {
            $sql = "INSERT INTO " . $con->dbname . ".matriculacion ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($peac_id))
                $comando->bindParam(':peac_id', $peac_id, \PDO::PARAM_INT);

            if (isset($adm_id))
                $comando->bindParam(':adm_id', $adm_id, \PDO::PARAM_INT);

            if (isset($est_id))
                $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);

            if (isset($sins_id))
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);

            if (isset($mat_fecha_matriculacion))
                $comando->bindParam(':mat_fecha_matriculacion', $mat_fecha_matriculacion, \PDO::PARAM_STR);

            if (isset($mat_usuario_ingreso))
                $comando->bindParam(':mat_usuario_ingreso', $mat_usuario_ingreso, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.matriculacion');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
    
    
    public function insertarAsignacionxMeting($par_id, $mat_id, $mest_id, $apar_descripcion, $apar_fecha_asignacion, $apar_usuario_asignacion) {

        $con = \Yii::$app->db_academico;       
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
                
        $param_sql = "apar_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", apar_estado";
        $bsol_sql .= ", 1";
        if (isset($par_id)) {
            $param_sql .= ", par_id";
            $bsol_sql .= ", :par_id";
        }

        if (isset($mat_id)) {
            $param_sql .= ", mat_id";
            $bsol_sql .= ", :mat_id";
        }

        if (isset($mest_id)) {
            $param_sql .= ", mest_id";
            $bsol_sql .= ", :mest_id";
        }

        if (isset($apar_descripcion)) {
            $param_sql .= ", apar_descripcion";
            $bsol_sql .= ", :apar_descripcion";
        }

        if (isset($apar_fecha_asignacion)) {
            $param_sql .= ", apar_fecha_asignacion";
            $bsol_sql .= ", :apar_fecha_asignacion";
        }
        if (isset($apar_usuario_asignacion)) {
            $param_sql .= ", apar_usuario_asignacion";
            $bsol_sql .= ", :apar_usuario_asignacion";
        }        
        
        try {
            $sql = "INSERT INTO " . $con->dbname . ".asignacion_paralelo ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);   
            if (isset($par_id))
                $comando->bindParam(':par_id', $par_id, \PDO::PARAM_INT);

            if (isset($mat_id))
                $comando->bindParam(':mat_id', $mat_id, \PDO::PARAM_INT);

            if (isset($mest_id))
                $comando->bindParam(':mest_id', $mest_id, \PDO::PARAM_INT);

            if (isset($apar_descripcion))
                $comando->bindParam(':apar_descripcion', $apar_descripcion, \PDO::PARAM_STR);

            if (isset($apar_fecha_asignacion))
                $comando->bindParam(':apar_fecha_asignacion', $apar_fecha_asignacion, \PDO::PARAM_STR);

            if (isset($apar_usuario_asignacion))
                $comando->bindParam(':apar_usuario_asignacion', $apar_usuario_asignacion, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.asignacion_paralelo');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
    
     /**
     * Function consultarPeriodoAcadMing
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los períodos académicos de los métodos de ingreso).
     */
    public function consultarPeriodoAcadMing($uaca_id, $mod_id, $ming_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT pmi.pami_id id, pmi.pami_codigo name
                FROM " . $con->dbname . ".periodo_academico_met_ingreso pmi                     
                WHERE pmi.uaca_id = :uaca_id AND
                      pmi.mod_id = :mod_id AND
                      pmi.ming_id = :ming_id AND
                      pmi.pami_estado_logico = :estado AND
                      pmi.pami_estado = :estado                    
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ming_id", $ming_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    /**
     * Function consultaPeriodoAcademico
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los períodos académicos).
     */
    public function consultarParalelo($pami_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT par_id id, par_nombre name
                FROM " . $con->dbname . ".paralelo
                WHERE pami_id = :pami_id AND
                      par_estado = :estado AND
                      par_estado_logico = :estado
                ORDER BY 2 asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);       
        $comando->bindParam(":pami_id", $pami_id, \PDO::PARAM_INT);       
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultaPeriodoAcademico
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los períodos académicos de los métodos de ingreso).
     */
    public function consultarMatriculaxId($adm_id, $sins_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT 'S' existe 
                FROM " . $con->dbname . ".matriculacion m
                WHERE adm_id = :adm_id
                    and sins_id = :sins_id
                    and mat_estado = :estado
                    and mat_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":adm_id", $adm_id, \PDO::PARAM_INT);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    /**
     * Function consultarPlanificacion
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el código de planificación académica.).
     */
    public function consultarPlanificacion($sins_id, $periodo_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT pea.peac_id 
                FROM " . $con1->dbname . ".solicitud_inscripcion si inner join " . $con->dbname . ".malla_academica ma 
                           on (si.uaca_id = ma.uaca_id and si.mod_id = ma.mod_id and si.eaca_id = ma.eaca_id)
                       inner join " . $con->dbname . ".planificacion_estudio_academico pea 
                           on (pea.maca_id = ma.maca_id and pea.pami_id = :periodo_id)
                where si.sins_id = :sins_id
                and si.sins_estado = :estado
                and si.sins_estado_logico = :estado
                and ma.maca_estado = :estado
                and ma.maca_estado_logico = :estado
                and pea.peac_estado = :estado
                and pea.peac_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":periodo_id", $periodo_id, \PDO::PARAM_INT);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**************************************** FUNCIONES AGREGADAS PARA REGISTRO EN LINEA ********************************************/

    /**
     * Function to check if today is between process inscription dates
     * @author -
     * @param $date
     * @return $resultData
     */
    public function checkToday($date)
    {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
            SELECT rco_id, pla_id, rco_num_bloques, rco_fecha_fin 
            FROM " . $con->dbname . ".registro_configuracion as reg_conf
            WHERE :date
            BETWEEN reg_conf.rco_fecha_inicio AND reg_conf.rco_fecha_fin_periodoextra
            AND rco_estado =:estado
            AND rco_estado_logico =:estado
        ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":date", $date, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }


    public function checkTodayisdrop($date)
    {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
            SELECT rco_id, pla_id, rco_num_bloques, rco_fecha_fin_periodoextra  
            FROM " . $con->dbname . ".registro_configuracion as reg_conf
            WHERE :date
            BETWEEN reg_conf.rco_fecha_ini_periodoextra AND reg_conf.rco_fecha_fin_periodoextra
            AND rco_estado =:estado
            AND rco_estado_logico =:estado
        ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":date", $date, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }



    /**
     * Function to get data student from planificacion, planificacion_estudiante and persona
     * @author -
     * @param $per_id, $pla_id, $pes_id
     * @return $resultData
     */

    public function getDataStudent($per_id, $pla_id, $pes_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $con_asgard = \Yii::$app->db_asgard;
        /*$con_utegsea = \Yii::$app->utegsea;*/
        $estado = 1;

        $sql = "
            SELECT pla.pla_periodo_academico, pes.pes_nombres, pes.pes_dni, moda.mod_nombre, pes.pes_carrera, per.per_celular, pes_jornada
            FROM " . $con_academico->dbname . ".planificacion as pla,
            " . $con_academico->dbname . ".planificacion_estudiante as pes,
            " . $con_academico->dbname . ".modalidad as moda,
            " . $con_asgard->dbname . ".persona as per
            WHERE pla.mod_id = moda.mod_id
            AND pes.per_id = per.per_id            
            AND per.per_id =:per_id
            AND pla.pla_id =:pla_id
            AND pes.pes_id =:pes_id;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        //\app\models\Utilities::putMessageLogFile('selectEsquemaCalificacionUnidad: '.$comando->getRawSql());
        return $resultData;
    }

    /**
     * Function to get data from planificacion_estudiante
     * @author -
     * @param $per_id, $pla_id, $rco_num_bloques
     * @return $dataPlanificacion
     */
    public function getAllDataPlanificacionEstudiante($per_id, $pla_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $str_bloques = "pes.pes_mat_b1_h1_cod, pes.pes_mat_b1_h2_cod, pes.pes_mat_b1_h3_cod, pes.pes_mat_b1_h4_cod, pes.pes_mat_b1_h5_cod, pes.pes_mat_b2_h1_cod, pes.pes_mat_b2_h2_cod, pes.pes_mat_b2_h3_cod, pes.pes_mat_b2_h4_cod, pes.pes_mat_b2_h5_cod";
        $str_bloques1 = "pes.pes_mat_b1_h1_nombre, pes.pes_mat_b1_h2_nombre, pes.pes_mat_b1_h3_nombre, pes.pes_mat_b1_h4_nombre, pes.pes_mat_b1_h5_nombre, pes.pes_mat_b2_h1_nombre, pes.pes_mat_b2_h2_nombre, pes.pes_mat_b2_h3_nombre, pes.pes_mat_b2_h4_nombre, pes.pes_mat_b2_h5_nombre";
        
        $sql = "
            SELECT pes_dni, " . $str_bloques . "," . $str_bloques1 . "
            FROM " . $con_academico->dbname . ".planificacion_estudiante as pes            
            WHERE pes.per_id =:per_id
            AND pes.pla_id =:pla_id;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        $dataCredits = $this->getInfoMallaEstudiante($per_id);
        $dataPlanificacion = $this->parseDataSubject($resultData, $dataCredits);

        return $dataPlanificacion;
    }

    public function getInfoMallaEstudiante($per_id){
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
        SELECT
            -- a.asi_id,
            a.asi_nombre AS Asignatura,
            -- mad.made_codigo_asignatura AS MallaCodAsig, 
            -- CONCAT(p.per_pri_nombre, ' ',p.per_pri_apellido) AS Estudiante,
            -- em.emp_nombre_comercial AS Empresa,
            mad.made_credito AS AsigCreditos,
            pcc.pccr_costo_credito as CostoCredito
            -- mcc.mcco_code AS ModCode
        FROM 
            " . $con_academico->dbname . ".estudiante AS e
            INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa AS ec ON e.est_id = ec.est_id
            INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad AS me ON ec.meun_id = me.meun_id
            INNER JOIN " . $con_academico->dbname . ".estudio_academico AS ea ON ea.eaca_id = me.eaca_id
            INNER JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON me.uaca_id = ua.uaca_id
            INNER JOIN " . $con_academico->dbname . ".asignatura AS a ON ua.uaca_id = a.uaca_id
            INNER JOIN " . $con_academico->dbname . ".tipo_estudio_academico AS tp ON tp.teac_id = ea.teac_id
            INNER JOIN ".Yii::$app->db_asgard->dbname.".persona AS p ON p.per_id = e.per_id
            INNER JOIN ".Yii::$app->db_asgard->dbname.".empresa AS em ON em.emp_id = me.emp_id
            INNER JOIN " . $con_academico->dbname . ".planificacion_estudiante AS pes ON pes.pes_dni = p.per_cedula
            INNER JOIN " . $con_academico->dbname . ".planificacion AS pla ON pla.pla_id = pes.pla_id
            INNER JOIN " . $con_academico->dbname . ".malla_academica_detalle AS mad ON mad.asi_id = a.asi_id
            INNER JOIN " . $con_academico->dbname . ".malla_academica AS ma ON mad.maca_id = ma.maca_id 
            INNER JOIN " . $con_academico->dbname . ".malla_unidad_modalidad AS mu ON mu.maca_id = ma.maca_id AND mu.meun_id = me.meun_id
            INNER JOIN " . $con_academico->dbname . ".programa_costo_credito AS pcc ON pcc.eaca_id = ea.eaca_id AND pcc.mod_id = me.mod_id AND pcc.pccr_creditos=mad.made_credito AND pcc.pccr_categoria=e.est_categoria
        WHERE
            p.per_id =:per_id AND
            a.asi_estado = 1 AND a.asi_estado_logico = 1 AND 
            ua.uaca_estado = 1 AND ua.uaca_estado_logico = 1 AND 
            me.meun_estado = 1 AND me.meun_estado_logico = 1 AND
            -- mo.mod_estado = 1 AND mo.mod_estado_logico = 1 AND 
            -- mcc.mcco_estado = 1 AND mcc.mcco_estado_logico = 1 AND 
            pcc.pccr_estado =1 AND pcc.pccr_estado_logico = 1 AND 
            ea.eaca_estado = 1 AND ea.eaca_estado_logico = 1 AND 
            tp.teac_estado = 1 AND tp.teac_estado_logico = 1 AND 
            ma.maca_estado = 1 AND ma.maca_estado_logico = 1 AND 
            mad.made_estado = 1 AND mad.made_estado_logico = 1 AND
            ec.ecpr_estado = 1 AND ec.ecpr_estado_logico = 1 AND
            e.est_estado = 1 AND e.est_estado_logico = 1 AND
            p.per_estado = 1 AND p.per_estado_logico = 1 AND 
            em.emp_estado = 1 AND em.emp_estado_logico = 1 AND
            mu.mumo_estado =1 AND mu.mumo_estado_logico =1 AND
            pes.pes_estado = 1 AND pes.pes_estado_logico = 1 
        ";


        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $dataCredits = $comando->queryAll();
        return $dataCredits;
    }
    public static function getEstudioAcademicoByEstudiante($per_id){
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
        SELECT
            ea.eaca_id as id,
            me.mod_id as mod_id
        FROM 
            estudiante AS e
            INNER JOIN estudiante_carrera_programa AS ec ON e.est_id = ec.est_id
            INNER JOIN modalidad_estudio_unidad AS me ON ec.meun_id = me.meun_id
            INNER JOIN estudio_academico AS ea ON ea.eaca_id = me.eaca_id
            INNER JOIN unidad_academica AS ua ON me.uaca_id = ua.uaca_id
            INNER JOIN asignatura AS a ON ua.uaca_id = a.uaca_id
            INNER JOIN tipo_estudio_academico AS tp ON tp.teac_id = ea.teac_id
            INNER JOIN ".Yii::$app->db_asgard->dbname.".persona AS p ON p.per_id = e.per_id
            INNER JOIN ".Yii::$app->db_asgard->dbname.".empresa AS em ON em.emp_id = me.emp_id
            INNER JOIN planificacion_estudiante AS pes ON pes.pes_dni = p.per_cedula
            INNER JOIN planificacion AS pla ON pla.pla_id = pes.pla_id
            INNER JOIN malla_academica_detalle AS mad ON mad.asi_id = a.asi_id
            INNER JOIN malla_academica AS ma ON mad.maca_id = ma.maca_id 
            INNER JOIN malla_unidad_modalidad AS mu ON mu.maca_id = ma.maca_id AND mu.meun_id = me.meun_id
        WHERE
            pes.per_id =:per_id AND
            a.asi_estado = 1 AND a.asi_estado_logico = 1 AND 
            ua.uaca_estado = 1 AND ua.uaca_estado_logico = 1 AND 
            me.meun_estado = 1 AND me.meun_estado_logico = 1 AND
            ea.eaca_estado = 1 AND ea.eaca_estado_logico = 1 AND 
            tp.teac_estado = 1 AND tp.teac_estado_logico = 1 AND 
            ma.maca_estado = 1 AND ma.maca_estado_logico = 1 AND 
            mad.made_estado = 1 AND mad.made_estado_logico = 1 AND
            ec.ecpr_estado = 1 AND ec.ecpr_estado_logico = 1 AND
            e.est_estado = 1 AND e.est_estado_logico = 1 AND
            p.per_estado = 1 AND p.per_estado_logico = 1 AND 
            em.emp_estado = 1 AND em.emp_estado_logico = 1 AND
            mu.mumo_estado =1 AND mu.mumo_estado_logico =1 AND
            pes.pes_estado = 1 AND pes.pes_estado_logico = 1 
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $result = $comando->queryOne();
        return $result;
    }

    /**
     * Function to get parse into array the information about subjects
     * @author -
     * @param $dict, $num
     * @return $arrData
     */

    public function parseDataSubject($dict, $dataCredits = array())
    {
        $arrData = array();

        if (!is_null($dict['pes_mat_b1_h1_cod']) && trim($dict['pes_mat_b1_h1_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b1_h1_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits  = $costoCredito ="";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b1_h1_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow11 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b1_h1_nombre']),
                "Code" => trim($dict['pes_mat_b1_h1_cod']),
                "Block" => "B1",
                "Hour" => "H1",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito,
            );
            array_push($arrData, $arrRow11);
        }

        if (!is_null($dict['pes_mat_b1_h2_cod']) && trim($dict['pes_mat_b1_h2_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b1_h2_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits =$costoCredito = "";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b1_h2_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow12 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b1_h2_nombre']),
                "Code" => trim($dict['pes_mat_b1_h2_cod']),
                "Block" => "B1",
                "Hour" => "H2",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito,
            );
            array_push($arrData, $arrRow12);
        }

        if (!is_null($dict['pes_mat_b1_h3_cod']) && trim($dict['pes_mat_b1_h3_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b1_h3_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits  =$costoCredito = "";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b1_h3_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow13 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b1_h3_nombre']),
                "Code" => trim($dict['pes_mat_b1_h3_cod']),
                "Block" => "B1",
                "Hour" => "H3",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito,
            );
            array_push($arrData, $arrRow13);
        }

        if (!is_null($dict['pes_mat_b1_h4_cod']) && trim($dict['pes_mat_b1_h4_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b1_h4_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits  = $costoCredito = "";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b1_h4_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow14 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b1_h4_nombre']),
                "Code" => trim($dict['pes_mat_b1_h4_cod']),
                "Block" => "B1",
                "Hour" => "H4",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito,
            );
            array_push($arrData, $arrRow14);
        }

        if (!is_null($dict['pes_mat_b1_h5_cod']) && trim($dict['pes_mat_b1_h5_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b1_h5_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits = $costoCredito ="";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b1_h5_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow15 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b1_h5_nombre']),
                "Code" => trim($dict['pes_mat_b1_h5_cod']),
                "Block" => "B1",
                "Hour" => "H5",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito,
            );
            array_push($arrData, $arrRow15);
        }

        if (!is_null($dict['pes_mat_b2_h1_cod']) && trim($dict['pes_mat_b2_h1_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b2_h1_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits = $costoCredito = "";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b2_h1_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow21 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b2_h1_nombre']),
                "Code" => trim($dict['pes_mat_b2_h1_cod']),
                "Block" => "B2",
                "Hour" => "H1",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito,
            );
            array_push($arrData, $arrRow21);
        }

        
        if (!is_null($dict['pes_mat_b2_h2_cod']) && trim($dict['pes_mat_b2_h2_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b2_h2_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits = $costoCredito = "";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b2_h2_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow22 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b2_h2_nombre']),
                "Code" => trim($dict['pes_mat_b2_h2_cod']),
                "Block" => "B2",
                "Hour" => "H2",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito ,
            );
            array_push($arrData, $arrRow22);
        }

        if (!is_null($dict['pes_mat_b2_h3_cod']) && trim($dict['pes_mat_b2_h3_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b2_h3_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits =/* $costoCredito = */"";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b2_h3_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow23 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b2_h3_nombre']),
                "Code" => trim($dict['pes_mat_b2_h3_cod']),
                "Block" => "B2",
                "Hour" => "H3",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito ,
            );
            array_push($arrData, $arrRow23);
        }

        if (!is_null($dict['pes_mat_b2_h4_cod']) && trim($dict['pes_mat_b2_h4_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b2_h4_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits = $costoCredito = "";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b2_h4_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow24 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b2_h4_nombre']),
                "Code" => trim($dict['pes_mat_b2_h4_cod']),
                "Block" => "B2",
                "Hour" => "H4",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito ,
            );
            array_push($arrData, $arrRow24);
        }

        if (!is_null($dict['pes_mat_b2_h5_cod']) && trim($dict['pes_mat_b2_h5_cod']) != "") {
            $modAsig = Asignatura::findOne(['asi_nombre' => trim($dict['pes_mat_b2_h5_nombre']), 'asi_estado_logico' => '1', 'asi_estado' => '1']);
            $codeAsignatura = $credits = $costoCredito = "";
            foreach($dataCredits as $key => $value){
                if($value['Asignatura'] == trim($dict['pes_mat_b2_h5_nombre'])){
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                    $costoCredito = $value['CostoCredito'];
                }
            }
            $arrRow25 = array(
                "Subject" => trim($modAsig->asi_nombre),//trim($dict['pes_mat_b2_h5_nombre']),
                "Code" => trim($dict['pes_mat_b2_h5_nombre']),
                "Block" => "B2",
                "Hour" => "H5",
                "Credit" => $credits,
                "Cost" => $costoCredito,
                "CostSubject" => $costoCredito ,
            );
            array_push($arrData, $arrRow25);
        }
        return $arrData;
    }


/*    public function parseDataSubject($dict, $dataCredits = array())
    {
        $arrData = array();

        if (!is_null($dict['pes_mat_b1_h1_nombre']) && trim($dict['pes_mat_b1_h1_nombre']) != "") {
            $alias = trim($dict['pes_mat_b1_h1_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow11 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B1",
                "Hour" => "H1",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow11);
        }

        if (!is_null($dict['pes_mat_b1_h2_nombre']) && trim($dict['pes_mat_b1_h2_nombre']) != "") {
            $alias = trim($dict['pes_mat_b1_h2_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow12 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B1",
                "Hour" => "H2",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow12);
        }

        if (!is_null($dict['pes_mat_b1_h3_nombre']) && trim($dict['pes_mat_b1_h3_nombre']) != "") {
            $alias = trim($dict['pes_mat_b1_h3_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow13 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B1",
                "Hour" => "H3",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow13);
        }

        if (!is_null($dict['pes_mat_b1_h4_nombre']) && trim($dict['pes_mat_b1_h4_nombre']) != "") {
            $alias = trim($dict['pes_mat_b1_h4_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow14 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B1",
                "Hour" => "H4",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow14);
        }

        if (!is_null($dict['pes_mat_b1_h5_nombre']) && trim($dict['pes_mat_b1_h5_nombre']) != "") {
            $alias = trim($dict['pes_mat_b1_h5_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow15 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B1",
                "Hour" => "H5",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow15);
        }

        if (!is_null($dict['pes_mat_b2_h1_nombre']) && trim($dict['pes_mat_b2_h1_nombre']) != "") {
            $alias = trim($dict['pes_mat_b2_h1_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow21 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B2",
                "Hour" => "H1",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow21);
        }

        if (!is_null($dict['pes_mat_b2_h2_nombre']) && trim($dict['pes_mat_b2_h2_nombre']) != "") {
            $alias = trim($dict['pes_mat_b2_h2_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow22 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B2",
                "Hour" => "H2",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow22);
        }

        if (!is_null($dict['pes_mat_b2_h3_nombre']) && trim($dict['pes_mat_b2_h3_nombre']) != "") {
            $alias = trim($dict['pes_mat_b2_h3_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow23 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B2",
                "Hour" => "H3",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow23);
        }

        if (!is_null($dict['pes_mat_b2_h4_nombre']) && trim($dict['pes_mat_b2_h4_nombre']) != "") {
            $alias = trim($dict['pes_mat_b2_h4_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow24 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B2",
                "Hour" => "H4",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow24);
        }

        if (!is_null($dict['pes_mat_b2_h5_nombre']) && trim($dict['pes_mat_b2_h5_nombre']) != "") {
            $alias = trim($dict['pes_mat_b2_h5_nombre']);
            $asignatura = $codeAsignatura = $credits = "";
            foreach($dataCredits as $key => $value){
                if($value['AliasAsignatura'] == $alias){
                    $asignatura = $value['Asignatura'];
                    $credits = $value['AsigCreditos'];
                    $codeAsignatura = $value['MallaCodAsig'];
                }
            }
            $arrRow25 = array(
                "Subject" => $asignatura,
                "CodeAsignatura" => $codeAsignatura,
                "Alias" => $alias,
                "Block" => "B2",
                "Hour" => "H5",
                "Credit" => $credits,
            );
            array_push($arrData, $arrRow25);
        }
        return $arrData;
    }

    
    /**
     * Function to get the id from planificacion_estudiante
     * @author -
     * @param $per_id, $pla_id
     * @return $resultData
     */
    public function getIdPlanificacionEstudiante($per_id, $pla_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
            SELECT pes_id, pla_id
            FROM " . $con_academico->dbname . ".planificacion_estudiante as pes
            WHERE pes.per_id=:per_id
            -- AND pes.pla_id=:pla_id
            AND pes.pes_estado=:estado
            AND pes.pes_estado_logico=:estado
            ORDER BY pla_id desc;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Function to check if a exist a planificacion_estudianto in registro_online to /matriculacion/index without import the ron_estado_registro value
     * @author -
     * @param $per_id, $pes_id
     * @return $resultData
     */
    public function checkPlanificacionEstudianteRegisterConfiguracion($per_id, $pes_id, $pla_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
            SELECT ron_id, ron_estado_registro
            FROM " . $con_academico->dbname . ".registro_online as ron,
            " . $con_academico->dbname . ".registro_configuracion as rco
            WHERE ron.ron_fecha_registro
            BETWEEN rco.rco_fecha_inicio AND rco.rco_fecha_fin
            AND rco.pla_id = :pla_id
            AND ron.per_id=:per_id
            AND ron.pes_id=:pes_id
            AND ron.ron_estado=:estado
            AND ron.ron_estado_logico=:estado;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Function to check if a exist a planificacion_estudianto in registro_online to /matriculacion/register with ron_estado_registro equals 1
     * @author -
     * @param $per_id, $pes_id
     * @return $resultData
     */
    public function checkPlanificacionEstudianteRegisterConfiguracionRegistro($per_id, $pes_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
            SELECT ron_id, ron_estado_registro
            FROM " . $con_academico->dbname . ".registro_online as ron,
            " . $con_academico->dbname . ".registro_configuracion as rco
            WHERE ron.ron_fecha_registro
            BETWEEN rco.rco_fecha_inicio AND rco.rco_fecha_fin
            AND ron.per_id=:per_id
            AND ron.pes_id=:pes_id
            AND ron.ron_estado_registro=:estado
            AND ron.ron_estado=:estado
            AND ron.ron_estado_logico=:estado;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Function to check if a exist a planificacion_estudianto in registro_online to /matriculacion/register with ron_estado_registro equals 1
     * @author -
     * @param $per_id, $pes_id
     * @return $resultData
     */
    public function checkPlanificacionEstudianteRegisterConfiguracionSolicitud($per_id, $pes_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $estado_registro = 0;

        $sql = "
            SELECT ron_id, ron_estado_registro
            FROM " . $con_academico->dbname . ".registro_online as ron,
            " . $con_academico->dbname . ".registro_configuracion as rco
            WHERE ron.ron_fecha_registro
            BETWEEN rco.rco_fecha_inicio AND rco.rco_fecha_fin
            AND ron.per_id=:per_id
            AND ron.pes_id=:pes_id
            AND ron.ron_estado_registro=:estado_registro
            AND ron.ron_estado=:estado
            AND ron.ron_estado_logico=:estado;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_registro", $estado_registro, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Function to get data student when exist a register into registro_online
     * @author -
     * @param $ron_id
     * @return $resultData
     */
    public function getDataStudenFromRegistroOnline($per_id, $pes_id)
    {
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            SELECT distinct
            ron.ron_id, 
	    pla.paca_id,
            pla.pla_periodo_academico, 
            pes.pes_nombres, 
            pes.pes_dni, 
            mo.mod_nombre as mod_nombre, 
            ea.eaca_nombre as pes_carrera,
            ua.uaca_nombre as pes_unidad,  
            per.per_celular, 
            per.per_correo, 
            est.est_categoria, 
            est.est_matricula
            FROM " . $con_academico->dbname . ".planificacion pla
            inner join " . $con_academico->dbname . ".planificacion_estudiante pes on pla.pla_id =pes.pla_id
            inner join " . $con_academico->dbname . ".estudiante est on est.per_id=pes.per_id
            inner join " . $con_asgard->dbname . ".persona per on per.per_id=est.per_id
            inner join " . $con_academico->dbname . ".registro_online ron on ron.per_id=pes.per_id
            inner join " . $con_academico->dbname . ".modalidad mo on mo.mod_id=ron.ron_modalidad
            inner join " . $con_academico->dbname . ".estudio_academico ea on ea.eaca_id= ron.ron_carrera
            inner join " . $con_academico->dbname . ".modalidad_estudio_unidad  meu on ea.eaca_id= meu.eaca_id
            inner join " . $con_academico->dbname . ".unidad_academica ua on ua.uaca_id= meu.uaca_id
            WHERE ron.per_id =:per_id
            AND ron.pes_id =:pes_id
            AND ron.ron_estado =:estado
            AND ron.ron_estado_logico =:estado
            ORDER BY ron.ron_id desc;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function getDataStudenbyRonId($ron_id)
    {
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            SELECT pla.pla_periodo_academico, 
            pes.pes_nombres, 
            pes.pes_dni, 
            mo.mod_nombre as mod_nombre, 
            ea.eaca_nombre as pes_carrera,
            per.per_celular,
            per.per_correo, 
            pes_jornada
            FROM " . $con_academico->dbname . ".planificacion as pla,
            " . $con_academico->dbname . ".planificacion_estudiante as pes,
            " . $con_asgard->dbname . ".persona as per,
            " . $con_academico->dbname . ".registro_online as ron,
            " . $con_academico->dbname . ".modalidad as mo,
            " . $con_academico->dbname . ".estudio_academico as ea
            WHERE ron.pes_id = pes.pes_id
            AND ron.per_id = per.per_id
            AND pes.pla_id = pla.pla_id
            AND mo.mod_id=ron.ron_modalidad
            AND ea.eaca_id= ron.ron_carrera  
            AND ron.ron_id =:ron_id
            AND ron.ron_estado =:estado
            AND ron.ron_estado_logico =:estado
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    /**
     * Function to get cost register when exist a register into registro_online
     * @author -
     * @param $ron_id
     * @return $resultData['roc_costo']
     */
    public function getCostFromRegistroOnline($ron_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "

            SELECT distinct SUM(roc.roi_costo) AS costo,
            ron.ron_valor_gastos_adm,
            ron.ron_valor_aso_estudiante
            FROM " . $con_academico->dbname . ".registro_online_item AS roc
            inner join " . $con_academico->dbname . ".registro_online ron on roc.ron_id=ron.ron_id
            
            WHERE ron.ron_id =:ron_id
            AND roc.roi_estado =:estado
            AND roc.roi_estado_logico =:estado
            
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }
    
    /**
     * Function getRegistroAdiciOnline
     * @author  Luis Cajamarca <analista.desarrollo@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el ultimo registro de ron_id o registroadicionalmateria).
     */

    public function getRegistroAdiciOnline($ron_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "

            SELECT ron_id as ron_id, MAX(rama_id) AS id 
            FROM " . $con_academico->dbname . ".registro_adicional_materias 

            WHERE ron_id =:ron_id
            AND rama_estado =:estado
            AND rama_estado_logico =:estado
            GROUP BY ron_id, rama_id
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function getEstadoGenerado($ron_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "

            SELECT ron_id, rpm_estado_generado 
            FROM " . $con_academico->dbname . ".registro_pago_matricula

            WHERE ron_id =:ron_id
            AND rpm_estado =:estado
            AND rpm_estado_logico =:estado
            
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }



    /**
     * Function to get planification data when exist a register into registro_online
     * @author -
     * @param $ron_id
     * @return $resultData
     */
    public function getPlanificationFromRegistroOnline($ron_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            SELECT roi.roi_id, 
                roi.roi_materia_nombre as Subject, 
                roi_creditos as Credit, 
		roi.roi_materia_cod as Code,
                roi.roi_materia_cod as CodeAsignatura, 
		roi.roi_costo as Cost,
                roi.roi_costo as Price,
                roi.roi_hora as Hour,
                roi.roi_bloque as Block
            FROM " . $con_academico->dbname . ".registro_online_item as roi
            WHERE ron_id =:ron_id
            AND roi_estado =:estado
            AND roi_estado_logico =:estado
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Function to get the last registro_online to show in /matriculacion/registro when is non-registering time
     * @author -
     * @param
     * @return $resultData
     */
    public function getLastIdRegistroOnline($per_id = NULL)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $per_id = (isset($per_id))?$per_id:(Yii::$app->session->get("PB_perid"));
        $sql = "
            SELECT ron.ron_id, ron.pes_id
            FROM " . $con_academico->dbname . ".registro_online as ron
            WHERE ron.ron_estado_registro =:estado AND ron.per_id =:per_id 
            ORDER BY ron.ron_fecha_registro DESC;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    //Esta funcion se reemplazo con al de produccion debido a errores de usuario al 
    //momento de manipular el git.
    public static function getPlanificacionPago($mod_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT pla.pla_id, pla.pla_periodo_academico, moda.mod_nombre /*, pes.pes_id */
                       ,imu.ite_id
                       ,(select ip.ipre_precio from db_facturacion.item_precio ip where ip.ite_id = imu.ite_id) as valor
                  FROM " . $con_academico->dbname . ".planificacion as pla 
         /* inner join " . $con_academico->dbname . ".planificacion_estudiante pes on pes.pla_id = pla.pla_id*/
            inner join " . $con_academico->dbname . ".modalidad as moda on moda.mod_id = pla.mod_id
            inner join db_facturacion.item_matricula_unidad as imu on imu.mod_id = pla.mod_id
                 WHERE /* pes.per_id = :per_id
                   AND */ pla.pla_estado =:estado
                   and pla.mod_id = :mod_id
                   AND pla.pla_estado_logico =:estado 
                   and moda.mod_estado = :estado
                   and moda.mod_estado_logico = :estado";
                
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public static function getEstudiantesPagoMatricula($estudiante, $pla_periodo_academico, $mod_id, $aprobacion = -1)
    {
        $filter = "";
        $search = "%" . $estudiante . "%";
        if (!is_null($estudiante) || $estudiante != "") {
            if($mod_id > 0){
                 $filter = 'AND pes.pes_nombres like :search AND pla.mod_id = :mod_id';
            }else{
                $filter = 'AND pes.pes_nombres like :search';
            }
            if($aprobacion > -1){
                $filter .= ' AND rpm.rpm_estado_aprobacion = :aprobacion';
            }
           
        }       
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            Select  ron.per_id,
                    rpm.rpm_id as id,
                    pla.pla_id AS PlaId, 
                    pes.pes_id AS PesId, 
                    pes.pes_nombres AS Estudiante,
                    rpm.rpm_archivo AS Archivo,
                    rpm.rpm_estado_aprobacion AS EstadoAprobacion
            from " . $con_academico->dbname . ".registro_online as ron
                 INNER JOIN " . $con_academico->dbname . ".registro_pago_matricula rpm  ON rpm.per_id = ron.per_id and ron.ron_id = rpm.ron_id
                 INNER JOIN " . $con_academico->dbname . ".planificacion pla ON pla.pla_id = rpm.pla_id
                 INNER JOIN " . $con_academico->dbname . ".planificacion_estudiante pes ON pes.per_id =  ron.per_id
            WHERE pla.pla_estado =:estado
                AND pla.pla_estado_logico =:estado
                AND pes.pes_estado = :estado
                AND pes.pes_estado_logico = :estado
                AND ron.ron_estado = :estado
                AND ron.ron_estado_logico = :estado
                AND rpm.rpm_estado = :estado
                AND rpm.rpm_estado_logico = :estado
            $filter        
        ";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":pla_periodo_academico", $pla_periodo_academico, \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":search", $search, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if($aprobacion > -1){
            $comando->bindParam(":aprobacion", $aprobacion, \PDO::PARAM_INT);
        }
        $resultData = $comando->queryAll();

        return $resultData;
    }



    /**
     * Function to get data student from planificacion_estudiante
     * @author Julio Lopez
     * @param $per_id
     * @return $resultData
     */

    public function getDataPlanStudent($per_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $con_asgard = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT pes.pes_id as pes_id, pes.pla_id as pla_id
                FROM " . $con_academico->dbname . ".planificacion_estudiante as pes
                WHERE pes.per_id = :per_id
                AND pes.pes_estado = :estado
                AND pes.pes_estado_logico =:estado ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        //\app\models\Utilities::putMessageLogFile('getDataPlanStudent: '.$comando->getRawSql());

        return $resultData;
    }

    /**
     * Function to get data student from planificacion_estudiante
     * @author Julio Lopez
     * @param $per_id
     * @return $resultData
     */

    public function getDetalleCuotasRegistroOnline($ccar_numero_documento, $est_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    substring(c.ccar_num_cuota,2,1) as NO,
                    
                    CASE
                        WHEN substring(c.ccar_num_cuota,2,1) = 1 THEN '1er PAGO'
                        WHEN substring(c.ccar_num_cuota,2,1) = 2 THEN '2do PAGO'
                        WHEN substring(c.ccar_num_cuota,2,1) = 3 THEN '3er PAGO'
                        WHEN substring(c.ccar_num_cuota,2,1) = 4 THEN '4to PAGO'
                        WHEN substring(c.ccar_num_cuota,2,1) = 5 THEN '5to PAGO'
                        WHEN substring(c.ccar_num_cuota,2,1) = 6 THEN '6to PAGO'
                    END as pago,
                    upper( DATE_FORMAT( c.ccar_fecha_vencepago, '%d %M %Y') ) as fecha_vencimiento,
                     c.ccar_valor_cuota as valor_cuota, c.ccar_valor_factura as valor_factura,
                     format(((c.ccar_valor_cuota/ c.ccar_valor_factura) * 100),2) as porcentaje
                FROM db_facturacion.carga_cartera c
                where c.ccar_numero_documento = :ccar_numero_documento
                  and c.est_id = :est_id  
                  and c.ccar_estado = :estado
                  and c.ccar_estado_logico = :estado;";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ccar_numero_documento", $ccar_numero_documento, \PDO::PARAM_INT);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('selectEsquemaCalificacionUnidad: '.$comando->getRawSql());

        return $resultData;
    }

    /**
     * Function to get data student from registro_online
     * @author Julio Lopez
     * @param $per_id
     * @return $resultData
     */

    public function getDetvalorRegistroOnline($ron_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $con_asgard = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT ifnull(ron.ron_valor_aso_estudiante,0) as ron_valor_aso_estudiante, 
                       ron.ron_valor_gastos_adm as ron_valor_gastos_adm
                FROM " . $con_academico->dbname . ".registro_online ron
                where ron.ron_id = :ron_id
                and ron.ron_estado = :estado 
                and ron.ron_estado_logico = :estado;";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        //\app\models\Utilities::putMessageLogFile('getDetvalorRegistroOnline: '.$comando->getRawSql());
        return $resultData;
    }


    /**
     * Function to get data student from cuotas_facturacion_cartera
     * @author Julio Lopez
     * @param $rama_id
     * @return $resultData
     */

    public function getNumeroDocumentoRegistroOnline($rama_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = " SELECT cfca.cfca_numero_documento as cfca_numero_documento
                 FROM  db_academico.cuotas_facturacion_cartera as cfca
                INNER JOIN db_facturacion.carga_cartera as ccar ON ccar.ccar_numero_documento = cfca.cfca_numero_documento
                WHERE cfca.cfca_rama_id = :rama_id and
                      cfca.cfca_estado = :estado and 
                      cfca.cfca_estado_logico = :estado and
                      ccar.ccar_estado = :estado and 
                      ccar.ccar_estado_logico = :estado ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":rama_id", $rama_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
\app\models\Utilities::putMessageLogFile('getNumeroDocumentoRegistroOnline: '.$comando->getRawSql());
        return $resultData;
    }

}
