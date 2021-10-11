<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\MallaAcademicaDetalle;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\ProgramaCostoCredito;
use app\modules\academico\models\MateriaParaleloPeriodo;
use app\modules\academico\models\DistributivoAcademicoHorario;

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

        \app\models\Utilities::putMessageLogFile('checkToday: '.$comando->getRawSql());
        return $resultData;
    }

    /**
     * Function to check if today is between process inscription dates
     * @author Luis Cajamarca P.
     * @param $date
     * @return $resultData
     */
    public function checkPeriodo($pla_id)
    {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "
            SELECT distinct pa.paca_id/*,sa.saca_id*/
            FROM db_academico.planificacion p
            inner join db_academico.semestre_academico sa on sa.saca_id=p.saca_id
            inner join db_academico.periodo_academico pa on pa.saca_id =sa.saca_id
            inner join db_academico.bloque_academico blq ON blq.baca_id = pa.baca_id
            WHERE pa.paca_activo = 'A'
            -- AND now() >= pa.paca_fecha_inicio and pa.paca_fecha_fin<= now()
            AND p.pla_id=:pla_id
            AND p.pla_estado =:estado AND p.pla_estado_logico =:estado
            AND sa.saca_estado =:estado AND sa.saca_estado_logico =:estado
            AND pa.paca_estado =:estado AND pa.paca_estado_logico =:estado
            AND blq.baca_estado =:estado AND blq.baca_estado_logico =:estado
        ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('checkPeriodo: '.$comando->getRawSql());
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



    /*
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
            SELECT distinct 
                    pa.paca_id,
                    pla.saca_id,
                    pla.pla_periodo_academico,
                    pes.pes_nombres,
                    pes.pes_dni,
                    mo.mod_id,
                    mo.mod_nombre as mod_nombre,
                    ea.eaca_id,
                    ea.eaca_nombre as pes_carrera,
                    ua.uaca_nombre as pes_unidad,
                    per.per_celular,
                    per.per_correo,
                    est.est_categoria,
                    est.est_matricula
                    FROM db_academico.planificacion pla
                    inner join db_academico.semestre_academico sa on sa.saca_id=pla.saca_id
                    inner join db_academico.periodo_academico pa on pa.saca_id =sa.saca_id
                    inner join db_academico.planificacion_estudiante pes on pla.pla_id =pes.pla_id
                    inner join db_academico.estudiante est on est.per_id=pes.per_id
                    inner join db_academico.estudiante_carrera_programa ecp on ecp.est_id=est.est_id
                    inner join db_asgard.persona per on per.per_id=est.per_id
                    inner join db_academico.modalidad_estudio_unidad meu on meu.meun_id= ecp.meun_id
                    inner join db_academico.modalidad mo on mo.mod_id=meu.mod_id
                    inner join db_academico.estudio_academico ea on ea.eaca_id= meu.eaca_id
                    inner join db_academico.unidad_academica ua on ua.uaca_id= meu.uaca_id
                    WHERE per.per_id =$per_id
            AND pla.pla_id =$pla_id
            AND pes.pes_id =$pes_id
            AND pa.paca_activo='A'
            AND pla.pla_estado = 1 AND pla.pla_estado_logico = 1
            AND pa.paca_estado = 1 AND pa.paca_estado_logico = 1
            AND sa.saca_estado = 1 AND sa.saca_estado_logico = 1
            AND pes.pes_estado = 1 AND pes.pes_estado_logico = 1
            AND est.est_estado = 1 AND est.est_estado_logico = 1
            AND per.per_estado = 1 AND per.per_estado_logico = 1
            AND mo.mod_estado = 1  AND mo.mod_estado_logico = 1
            AND ea.eaca_estado = 1 
            AND meu.meun_estado = 1 AND meu.meun_estado_logico = 1
            AND ua.uaca_estado = 1 AND ua.uaca_estado_logico = 1";
                    

      $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        //$comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        //$comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        //\app\models\Utilities::putMessageLogFile('selectEsquemaCalificacionUnidad: '.$comando->getRawSql());
        \app\models\Utilities::putMessageLogFile('getDataStudent: '.$comando->getRawSql());
        return $resultData;
    }

    /**
     * Function to get data from planificacion_estudiante
     * @author - modificado: Luis Cajamarca <analistadesarrollo04>
     * @param $per_id, $pla_id, $rco_num_bloques
     * @return $dataPlanificacion
     */
    public function getAllDataPlanificacionEstudiante($per_id, $pla_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;


        $str_bloques = "pes.pes_mat_b1_h1_cod, pes.pes_mat_b1_h2_cod, pes.pes_mat_b1_h3_cod, pes.pes_mat_b1_h4_cod, pes.pes_mat_b1_h5_cod, pes.pes_mat_b1_h6_cod, pes.pes_mat_b2_h1_cod, pes.pes_mat_b2_h2_cod, pes.pes_mat_b2_h3_cod, pes.pes_mat_b2_h4_cod, pes.pes_mat_b2_h5_cod, pes.pes_mat_b2_h6_cod";
        $str_mpp = "pes.pes_mat_b1_h1_mpp, pes.pes_mat_b1_h2_mpp, pes.pes_mat_b1_h3_mpp, pes.pes_mat_b1_h4_mpp, pes.pes_mat_b1_h5_mpp, pes.pes_mat_b1_h6_mpp, pes.pes_mat_b2_h1_mpp, pes.pes_mat_b2_h2_mpp, pes.pes_mat_b2_h3_mpp, pes.pes_mat_b2_h4_mpp, pes.pes_mat_b2_h5_mpp, pes.pes_mat_b2_h6_mpp";
                
        $sql = "
            SELECT pes.per_id,pes_dni, " . $str_bloques . "," . $str_mpp . "
            FROM " . $con_academico->dbname . ".planificacion_estudiantex as pes            
            WHERE pes.per_id =:per_id
            AND pes.pla_id =:pla_id;
        ";

	// \app\models\Utilities::putMessageLogFile('sql: ' . $sql);

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
	// \app\models\Utilities::putMessageLogFile('raw: ' . $comando->getRawSql());
        $resultData = $comando->queryOne();
        $dataCredits = $this->getInfoMallaEstudiante($per_id);
	// \app\models\Utilities::putMessageLogFile('resultData: ' . $resultData);
        $dataPlanificacion = $this->parseDataSubject($resultData, $dataCredits);

        // \app\models\Utilities::putMessageLogFile('getAllDataPlanificacionEstudiante: '.print_r($dataPlanificacion,true));

        return $dataPlanificacion;
    }

    /**
     * Function to getInfoMallaEstudiante dara la data del estudiante para asociarla con la funcion parse
     * @author Luis Cajamarca
     * @param $dict, $num
     * @return $arrData
     */
    public function getInfoMallaEstudiante($per_id){
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $adm=Yii::$app->session->get("PB_perid");
        $sql = "
        SELECT
            -- a.asi_id,
            e.est_id,pla.pla_id, a.asi_nombre AS Asignatura,
            ma.maca_id AS Malla,
            mad.made_codigo_asignatura AS MallaCodAsig,
            -- CONCAT(p.per_pri_nombre, ' ',p.per_pri_apellido) AS Estudiante,
            -- em.emp_nombre_comercial AS Empresa,
            me.mod_id as modalidad,
            mad.made_credito AS AsigCreditos,
            pcc.pccr_costo_credito as CostoCredito,
            -- mcc.mcco_code AS ModCode
            max(roi.roi_usuario_ingreso) as usuario,
            max(roi.roi_id) as roi_id,
            max(rama.rpm_id) as rama_id,
            $adm as admin
        FROM 
            db_academico.estudiante AS e
            INNER JOIN db_academico.estudiante_carrera_programa AS ec ON e.est_id = ec.est_id
            INNER JOIN db_academico.modalidad_estudio_unidad AS me ON ec.meun_id = me.meun_id
            INNER JOIN db_academico.estudio_academico AS ea ON ea.eaca_id = me.eaca_id
            INNER JOIN db_academico.unidad_academica AS ua ON me.uaca_id = ua.uaca_id
            -- INNER JOIN db_academico.malla_unidad_modalidad AS mum ON mum.meun_id = me.meun_id
            -- INNER JOIN db_academico.tipo_estudio_academico AS tp ON tp.teac_id = ea.teac_id
            INNER JOIN db_asgard.persona AS p ON p.per_id = e.per_id
            -- INNER JOIN db_asgard.empresa AS em ON em.emp_id = me.emp_id
            INNER JOIN db_academico.planificacion_estudiante AS pes ON pes.per_id=p.per_id
            INNER JOIN db_academico.planificacion AS pla ON pla.pla_id = pes.pla_id
            INNER JOIN db_academico.malla_academica AS ma ON pes.pes_cod_carrera = ma.maca_codigo 
            INNER JOIN db_academico.malla_academica_detalle AS mad ON mad.maca_id = ma.maca_id
            INNER JOIN db_academico.asignatura AS a ON mad.asi_id = a.asi_id
            LEFT JOIN db_academico.programa_costo_credito AS pcc 
                ON  me.mod_id = pcc.mod_id 
                AND mad.made_credito = pcc.pccr_creditos
                AND e.est_categoria=pcc.pccr_categoria
                and ea.eaca_id = pcc.eaca_id
                and ma.maca_id = pcc.maca_id 
            LEFT JOIN db_academico.registro_online as ron 
                ON ron.per_id = p.per_id
                AND ron.pes_id = pes.pes_id 
                AND ron.ron_estado =1 AND ron.ron_estado_logico = 1
            LEFT JOIN db_academico.registro_online_item as roi 
                ON roi.ron_id = ron.ron_id
                AND roi.roi_materia_cod = mad.made_codigo_asignatura
                AND roi.roi_estado =1 AND roi.roi_estado_logico = 1
            LEFT JOIN db_academico.registro_adicional_materias as rama
                ON rama.ron_id=roi.ron_id
                AND rama.rama_estado=1 and rama.rama_estado_logico=1
        WHERE
            p.per_id =:per_id AND
            a.asi_estado = 1 AND a.asi_estado_logico = 1 AND 
            ua.uaca_estado = 1 AND ua.uaca_estado_logico = 1 AND 
            me.meun_estado = 1 AND me.meun_estado_logico = 1 AND
            -- mo.mod_estado = 1 AND mo.mod_estado_logico = 1 AND 
            -- mcc.mcco_estado = 1 AND mcc.mcco_estado_logico = 1 AND 
            pcc.pccr_estado =1 AND pcc.pccr_estado_logico = 1 AND 
            ea.eaca_estado = 1 AND  
            -- tp.teac_estado = 1 AND tp.teac_estado_logico = 1 AND 
            ma.maca_estado = 1 AND ma.maca_estado_logico = 1 AND 
            mad.made_estado = 1 AND mad.made_estado_logico = 1 AND
            ec.ecpr_estado = 1 AND ec.ecpr_estado_logico = 1 AND
            e.est_estado = 1 AND e.est_estado_logico = 1 AND
            p.per_estado = 1 AND p.per_estado_logico = 1 AND 
            -- em.emp_estado = 1 AND em.emp_estado_logico = 1 AND
            -- mum.mumo_estado =1 AND mum.mumo_estado_logico =1 AND
            pes.pes_estado=1 and pes.pes_estado_logico=1 and
            pla.pla_estado=1 and pla.pla_estado_logico=1
            group by 1,2,3,4

        UNION


        SELECT e.est_id,pla.pla_id,
            asi.asi_nombre AS Asignatura,
            maca.maca_id as Malla,
            made.made_codigo_asignatura AS MallaCodAsig,
            -- CONCAT(p.per_pri_nombre, ' ',p.per_pri_apellido) AS Estudiante,
            -- em.emp_nombre_comercial AS Empresa,
            pla.mod_id as modalidad,
            made.made_credito AS AsigCreditos,
            pcc.pccr_costo_credito as CostoCredito ,
            -- mcc.mcco_code AS ModCode
            max(roi.roi_usuario_ingreso) as usuario,
            max(roi.roi_id) as roi_id,
            max(rama.rpm_id) as rama_id,
            $adm as admin
        from db_academico.planificacion_estudiante pes
        inner join db_academico.planificacion pla on pla.pla_id=pes.pla_id
        inner join db_academico.estudiante e on e.per_id =pes.per_id
        inner join db_academico.malla_academica maca on maca.maca_id=97
        inner join db_academico.malla_academica_detalle made on made.maca_id=maca.maca_id
        inner join db_academico.asignatura asi on asi.asi_id=made.asi_id
        LEFT JOIN db_academico.programa_costo_credito AS pcc 
                    ON  pla.mod_id = pcc.mod_id 
                    and e.est_categoria=pcc.pccr_categoria
                    AND made.made_credito = pcc.pccr_creditos
                    and pcc.eaca_id = 74
                    and maca.maca_id = pcc.maca_id 
        LEFT JOIN db_academico.registro_online as ron 
                ON ron.per_id = pes.per_id
                AND ron.pes_id = pes.pes_id 
                AND ron.ron_estado =1 AND ron.ron_estado_logico = 1
        LEFT JOIN db_academico.registro_online_item as roi 
            ON roi.ron_id = ron.ron_id
            AND roi.roi_materia_cod = made.made_codigo_asignatura
            AND roi.roi_estado =1 AND roi.roi_estado_logico = 1
        LEFT JOIN db_academico.registro_adicional_materias as rama
            ON rama.ron_id=roi.ron_id
            AND rama.rama_estado=1 and rama.rama_estado_logico=1
            
        where pla.mod_id in (2,3,4) and pes.per_id=:per_id AND
            pes.pes_estado=1 and pes.pes_estado_logico=1 and
            pla.pla_estado=1 and pla.pla_estado_logico=1
            group by 1,2,3,4
             ;

        ";


        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $dataCredits = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getInfoMallaEstudiante: '.$comando->getRawSql());
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
            ea.eaca_estado = 1 AND 
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
     * @author Luis Cajamarca
     * @param $dict, $num
     * @return $arrData
     */
    public function parseDataSubject($dict, $dataCredits = array()){
        $arrData = array();
        for ($j=1; $j <=2 ; $j++) { 
            for ($i=1; $i <=6; $i++) { 
                // bloque + hora 
                //$bloque='B'.$j.'-H'.$i;
                $bloque = 'B'.$j;
                $hora = $i.'H';
                

                if (!is_null($dict['pes_mat_b'.$j.'_h'.$i.'_cod']) && trim($dict['pes_mat_b'.$j.'_h'.$i.'_cod']) != "") {
                    

                    foreach($dataCredits as $key => $value){
                        if($value['MallaCodAsig'] == trim($dict['pes_mat_b'.$j.'_h'.$i.'_cod'])){
                            $modalidad      = $value['modalidad'];
                            $costoCredito   = $value['CostoCredito'];
                            $roi_id         = $value['roi_id'];
                            $rama_id        = $value['rama_id'];
                            $admin          = $value['admin'];
                            $usuario        = $value['usuario'];
                        }
                    }
                    $mod_est =Estudiante::findOne(['per_id'=> trim($dict['per_id']),'est_estado'=>'1','est_estado_logico'=>'1']);
                    //MallaAcademicaDetalle
                    if ($i <= 5) {
                        $modMade = MallaAcademicaDetalle::findOne(['made_codigo_asignatura' => trim($dict['pes_mat_b' . $j . '_h' . $i . '_cod']), 'made_estado_logico' => '1', 'made_estado' => '1']);
                    } else {
                        $modCodMalla = substr($dict['pes_mat_b' . $j . '_h6_cod'], 0, 8); // devuelve "Malla de Idioma"
                        $modMalla = MallaAcademica::findOne(['maca_codigo'=>$modCodMalla,'maca_estado'=>1,'maca_estado_logico'=>1]);
                        $modMade = MallaAcademicaDetalle::findOne(['maca_id' => $modMalla['maca_id'], 'made_codigo_asignatura' => trim($dict['pes_mat_b' . $j . '_h' . $i . '_cod']), 'made_estado_logico' => '1', 'made_estado' => '1']);
                        $modProgCost = ProgramaCostoCredito::findOne(['maca_id' => $modMalla,'mod_id'=>$modalidad,'pccr_categoria'=>$mod_est['est_categoria'],'pccr_creditos'=>$modMade['made_credito'],'pccr_estado'=>1,'pccr_estado_logico'=>1]);
                        $costoCredito = $modProgCost['pccr_costo_credito'];
                        if($j==1){
                            if($modMade['made_codigo_asignatura']=='CID-0097-0326-004'){
                                $hora='1H';
                            }else if($modMade['made_codigo_asignatura']=='CID-0097-0322-005'){
                                $hora='3H';
                            }else if($modMade['made_codigo_asignatura']=='CID-0097-0327-008'){
                                $hora='2H';
                            }else if($modMade['made_codigo_asignatura']=='CID-0097-0325-006'){
                                $hora='5H';
                            }
                        }else{
                            if($modMade['made_codigo_asignatura']=='CID-0097-0322-005'){
                                $hora='1H';
                            }else if($modMade['made_codigo_asignatura']=='CID-0097-0325-006'){
                                $hora='3H';
                            }else if($modMade['made_codigo_asignatura']=='CID-0097-0337-009'){
                                $hora='2H';
                            }else if($modMade['made_codigo_asignatura']=='CID-0097-0324-007'){
                                $hora='5H';
                            }
                        }
                        
                    }
                    //Asignatura
                    $modAsig = Asignatura::findOne(['asi_id'=>$modMade['asi_id'],'asi_estado'=>'1','asi_estado_logico'=>'1']);
                    //MateriaParaleloPeriodo
                    $modMpp  = MateriaParaleloPeriodo::findOne(['mpp_id' => trim($dict['pes_mat_b'.$j.'_h'.$i.'_mpp']), 'mpp_estado' => '1', 'mpp_estado_logico' => '1']);
                    //DistributivHorario
                    $modDaho = DistributivoAcademicoHorario::findOne(['daho_id' => $modMpp['daho_id'], 'daho_estado' => '1', 'daho_estado_logico' => '1']);
                    //ProgramaCostoCreduti
                    
                    


                    $arrRow[$j][$i] = array(
                        "Subject"       => $modAsig['asi_nombre'],//trim($dict['pes_mat_b1_h1_nombre']),
                        "Code"          => $modMade['made_codigo_asignatura'],
                        "Block"         => $bloque,
                        "Hour"          => $hora,
                        "Parallel"      => $modMpp['mpp_num_paralelo'],
                        "Credit"        => $modMade['made_credito'],
                        "modalidad"     => $modalidad,
                        "Cost"          => $costoCredito,
                        "CostSubject"   => $costoCredito,
                        "Roi_id"        => $roi_id,
                        "Rama_id"       => $rama_id,
                        "Admin"         => $admin,
                        "Usuario"       => $usuario
                    );
                    array_push($arrData, $arrRow[$j][$i]);
                }
            }
        }

    \app\models\Utilities::putMessageLogFile('arrData h1: ' . print_r($arrData,true));

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
        \app\models\Utilities::putMessageLogFile('getIdPlanificacionEstudiante: '.$comando->getRawSql());
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

        $sql = "
            SELECT distinct 
            ron.ron_id, 
            pa.paca_id,
            pla.saca_id,
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
            inner join " . $con_academico->dbname . ".semestre_academico sa on sa.saca_id=pla.saca_id
            inner join " . $con_academico->dbname . ".periodo_academico pa on pa.saca_id =sa.saca_id
            inner join " . $con_academico->dbname . ".planificacion_estudiante pes on pla.pla_id = pes.pla_id
            inner join " . $con_academico->dbname . ".estudiante est on est.per_id = pes.per_id
            inner join " . $con_asgard->dbname . ".persona per on per.per_id = est.per_id
            inner join " . $con_academico->dbname . ".registro_online ron on ron.per_id = pes.per_id
            inner join " . $con_academico->dbname . ".modalidad mo on mo.mod_id = ron.ron_modalidad
            inner join " . $con_academico->dbname . ".estudio_academico ea on ea.eaca_id = ron.ron_carrera
            inner join " . $con_academico->dbname . ".modalidad_estudio_unidad meu on ea.eaca_id = meu.eaca_id
            inner join " . $con_academico->dbname . ".unidad_academica ua on ua.uaca_id = meu.uaca_id
            WHERE ron.per_id = :per_id
            AND ron.pes_id = :pes_id
            AND pla.saca_id IS NOT NULL
            AND ron.ron_estado = 1 AND ron.ron_estado_logico = 1
            AND pla.pla_estado = 1 AND pla.pla_estado_logico = 1
            AND pa.paca_estado = 1 AND pa.paca_estado_logico = 1
            AND sa.saca_estado = 1 AND sa.saca_estado_logico = 1
            AND pes.pes_estado = 1 AND pes.pes_estado_logico = 1
            AND est.est_estado = 1 AND est.est_estado_logico = 1
            AND per.per_estado = 1 AND per.per_estado_logico = 1
            AND mo.mod_estado = 1 AND mo.mod_estado_logico = 1
            AND ea.eaca_estado = 1 
            AND meu.meun_estado = 1 AND meu.meun_estado_logico = 1
            AND ua.uaca_estado = 1 AND ua.uaca_estado_logico = 1
            ORDER BY ron.ron_id desc;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();

        // \app\models\Utilities::putMessageLogFile('getDataStudenFromRegistroOnline: '.$comando->getRawSql());
        
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
    public function getCostFromRegistroOnline($ron_id){
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "

            SELECT distinct SUM(roc.roi_costo) AS costo,
            ron.ron_valor_gastos_adm as gastos,
            ron.ron_valor_aso_estudiante as asociacion
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
        \app\models\Utilities::putMessageLogFile('getCostFromRegistroOnline: '.$comando->getRawSql());
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
    public function getPlanificationFromRegistroOnline($ron_id, $rama_id = 0)
    {
        $rama_id = $rama_id?$rama_id:0;
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        /*$sql = "
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
        ";*/

        if($rama_id>0) $str_search = " AND rama.rama_id = $rama_id ";
        $sql = "SELECT roi.roi_id, 
                       roi.roi_materia_nombre as Subject, 
                       roi_creditos as Credit, 
                       roi.roi_materia_cod as Code,
                       roi.roi_materia_cod as CodeAsignatura, 
                       roi.roi_costo as Cost,
                       roi.roi_costo as Price,
                       roi.roi_hora as Hour,
                       roi.roi_bloque as Block
            FROM " . $con_academico->dbname . ".registro_online_item as roi
            inner join " . $con_academico->dbname . ".registro_adicional_materias as rama on roi.roi_id = rama.roi_id_1
                                                                        or roi.roi_id = rama.roi_id_2
                                                                        or roi.roi_id = rama.roi_id_3
                                                                        or roi.roi_id = rama.roi_id_4
                                                                        or roi.roi_id = rama.roi_id_5
                                                                        or roi.roi_id = rama.roi_id_6
                                                                        or roi.roi_id = rama.roi_id_7
                                                                        or roi.roi_id = rama.roi_id_8
            WHERE roi.ron_id =:ron_id
            AND roi.roi_estado =:estado
            AND roi.roi_estado_logico =:estado
            $str_search
            AND rama.rama_estado = 1 
            AND rama.rama_estado_logico = 1";

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
                INNER JOIN db_academico.planificacion as pla ON pla.pla_id = pes.pla_id
                WHERE pes.per_id = :per_id
                AND pes.pes_estado = :estado
                AND pes.pes_estado_logico =:estado
                AND pla.pla_estado= :estado
                AND pla.pla_estado_logico=:estado ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        \app\models\Utilities::putMessageLogFile('getDataPlanStudent: '.$comando->getRawSql());

        return $resultData;
    }

    /**
     * Function to get data student from planificacion_estudiante
     * @author Julio Lopez
     * @param $per_id
     * @return $resultData
     */

    public function getDetalleCuotasRegistroOnline($ron_id, $rpm_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $con_academico2 = \Yii::$app->db_academico;
        $estado = 1;

        $sql_set ="SET lc_time_names = 'es_ES';";
        $comando2 = $con_academico2->createCommand($sql_set);
        $resultData2 = $comando2->execute();

        $sql = "SELECT 
                    roc.roc_num_cuota as NO,
                    CASE
                        WHEN roc.roc_num_cuota = 1 THEN '1er PAGO'
                        WHEN roc.roc_num_cuota = 2 THEN '2do PAGO'
                        WHEN roc.roc_num_cuota = 3 THEN '3er PAGO'
                        WHEN roc.roc_num_cuota = 4 THEN '4to PAGO'
                        WHEN roc.roc_num_cuota = 5 THEN '5to PAGO'
                        WHEN roc.roc_num_cuota = 6 THEN '6to PAGO'
                    END as pago,
                    upper( DATE_FORMAT( roc.roc_vencimiento, '%d %M %Y')) as fecha_vencimiento,
                    format( (roc.roc_costo * (roc.roc_porcentaje/100)) , 2) as valor_cuota,
                    roc.roc_costo as valor_factura,
                    roc_porcentaje as porcentaje    
                FROM db_academico.registro_online_cuota as roc 
                where roc.ron_id = :ron_id
                and roc.rpm_id = :rpm_id
                and roc.roc_estado = :estado
                and roc.roc_estado_logico = :estado;";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":rpm_id", $rpm_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile('getDetalleCuotasRegistroOnline: '.$comando->getRawSql());

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

    public function getNumeroDocumentoRegistroOnline( $rama_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT r.rpm_id as rpm_id, r.rama_fecha_creacion as rama_fecha_creacion
        FROM db_academico.registro_adicional_materias r
        WHERE r.rama_id = $rama_id
          AND r.rama_estado = :estado
          AND r.rama_estado_logico = :estado";            


        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":rama_id", $rama_id, \PDO::PARAM_INT);
        //$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
\app\models\Utilities::putMessageLogFile('getNumeroDocumentoRegistroOnline: '.$comando->getRawSql());
        return $resultData;
    }

    /**
     * Function to get nombre malla academica
     * @author Julio Lopez
     * @param $per_id
     * @return $resultData
     */

    public function getMallaAcademicaRegistroOnline($per_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT distinct maca.maca_id as maca_id, maca.maca_nombre as maca_nombre
                FROM db_academico.malla_academico_estudiante as maes 
                INNER JOIN db_academico.malla_academica as maca 
                WHERE maes.per_id = :per_id
                  AND maes.maca_id = maca.maca_id
                  AND maes.maes_estado = :estado
                  AND maes.maes_estado_logico = :estado
                  AND maca.maca_estado = :estado
                  AND maca.maca_estado_logico = :estado;";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        //\app\models\Utilities::putMessageLogFile('getMallaAcademicaRegistroOnline: '.$comando->getRawSql());
        return $resultData;
    }

    
    public function getEstadoGeneradoRpm($rpm_id, $ron_id)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            SELECT rpm_tipo_pago AS rpm_tipo_pago
            FROM " . $con_academico->dbname . ".registro_pago_matricula
            WHERE rpm_id = :rpm_id 
              AND ron_id = :ron_id
              AND rpm_estado =:estado
              AND rpm_estado_logico =:estado";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":rpm_id", $rpm_id, \PDO::PARAM_INT);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function getValorPagoTC($rama_id)
    {
        $con_facturacion = \Yii::$app->db_facturacion;
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            SELECT pfes_valor_pago
            FROM " . $con_facturacion->dbname . ".pagos_factura_estudiante pfe
            INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias rama on rama.pfes_id = pfe.pfes_id
            WHERE rama.rama_id = :rama_id
              AND pfe.pfes_estado =:estado
              AND pfe.pfes_estado_logico =:estado";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":rama_id", $rama_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData['pfes_valor_pago'];
    }
    /**
     * Function to get nombre malla academica
     * @author Luis Cajamarca
     * @param $
     * @return $resultData
     */

    //Consultar los estudiantes que no tiene registro de pago en la matricula y registro en la matriculación.
    public function consultarEstudiantesPlanificados($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $str_search = "";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {

            if ($arrFiltro['planificacion'] != "" && $arrFiltro['planificacion'] > 0) {
                $str_search .= "rpm.pla_id = :pla_id  AND ";
            }

            if ($arrFiltro['admitido'] != "") {
                $str_search .= " (TRIM(per.per_pri_nombre) like '%" . $arrFiltro['admitido'] . "%' OR ";
                $str_search .= "TRIM(per.per_seg_nombre) like '%" . $arrFiltro['admitido'] . "%' OR ";
                $str_search .= "TRIM(per.per_pri_apellido) like '%" . $arrFiltro['admitido'] . "%' OR ";
                $str_search .= "TRIM(per.per_pasaporte) like '%" . $arrFiltro['admitido'] . "%' OR ";
                $str_search .= "TRIM(per.per_cedula) like '%" . $arrFiltro['admitido'] . "%') AND ";
            }
            
        }

        $sql = "
        SELECT distinct planificacion.pla_id,
            planificacion.pla_periodo_academico as pla_periodo,
            planificacion.pla_fecha_inicio,
            planificacion.pla_fecha_fin,
            planificacion.saca_id,
            est.est_id,
            per.per_id,
            CONCAT(TRIM(per.per_pri_nombre),' ',TRIM(per.per_pri_apellido)) AS Estudiante,
            per.per_cedula AS DNI,
            planificacion.mod_nombre as modalidad,
            eaca.eaca_nombre as carrera
        FROM
            (SELECT distinct
                pes.per_id,
                pes.pes_id,
                pla.pla_periodo_academico,
                pla_fecha_inicio,
                pla_fecha_fin,
                moda.mod_id,
                moda.mod_nombre,
                pla.saca_id,
                pla.pla_id,
                pa.paca_id
            FROM db_academico.planificacion pla
            INNER JOIN db_academico.planificacion_estudiante pes on pla.pla_id = pes.pla_id
            INNER JOIN db_academico.semestre_academico sa on sa.saca_id=pla.saca_id
            INNER JOIN db_academico.periodo_academico pa on sa.saca_id=pa.saca_id
            INNER JOIN db_academico.modalidad moda on moda.mod_id=pla.mod_id
            WHERE
                pla.pla_estado = '1'  AND
                pla.pla_estado_logico = '1'  AND
                pes.pes_estado = '1'  AND
                pes.pes_estado_logico = '1' AND
                Now() >= pla.pla_fecha_inicio and Now()<=pla.pla_fecha_fin
            ) planificacion
        INNER JOIN db_asgard.persona per ON per.per_id = planificacion.per_id
        INNER JOIN db_academico.estudiante est on est.per_id = per.per_id
        INNER JOIN db_academico.estudiante_carrera_programa ecp on ecp.est_id=est.est_id
        INNER JOIN db_academico.modalidad_estudio_unidad meun on meun.meun_id =ecp.meun_id
        INNER JOIN db_academico.estudio_academico eaca on eaca.eaca_id=meun.eaca_id
        INNER JOIN db_academico.semestre_academico sa on sa.saca_id=planificacion.saca_id
        INNER JOIN db_academico.periodo_academico paca on sa.saca_id=paca.saca_id
        INNER JOIN db_academico.registro_pago_matricula rpm on planificacion.per_id=rpm.per_id
        LEFT JOIN db_academico.registro_online ron on ron.per_id=per.per_id and ron.pes_id=planificacion.pes_id
        WHERE
                $str_search
                rpm.rpm_id IS NOT NULL and
                paca.paca_estado = :estado  AND
                per.per_estado = :estado  AND
                est.est_estado = :estado  AND
                paca.paca_estado_logico = :estado  AND
                per.per_estado_logico = :estado  AND
                est.est_estado_logico = :estado  AND
                paca.paca_activo = 'A' AND
                ron.ron_id IS NULL  
        ORDER BY planificacion.pla_id ASC, carrera ASC, Estudiante ASC;";

        $comando = $con->createCommand($sql);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['planificacion'] != "" && $arrFiltro['planificacion'] > 0) {
                $search_per = $arrFiltro["planificacion"];
                $comando->bindParam(":pla_id", $search_per, \PDO::PARAM_INT);
            }

            if ($arrFiltro['admitido'] != "") {
                //$search_adm = $arrFiltro["admitido"];
                $search_adm = "%" . $arrFiltro["admitido"] . "%";
                $comando->bindParam(":search", $search_adm, \PDO::PARAM_INT);
            }
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            \app\models\Utilities::putMessageLogFile('consultarEstudiantesPlanificados: '.$comando->getRawSql());
            $resultData = $comando->queryAll();
        }else{
            $resultData = [];
        }
        if ($onlyData){ return $resultData; }   
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Estudiante", "DNI", "modalidad", "carrera","pla_periodo"],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Function to get nombre malla academica
     * @author Luis Cajamarca
     * @param $
     * @return $resultData
     */

    public function getPlaPeriodoAcademicoActual() {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT pla.pla_id as id,
                concat(pla.pla_periodo_academico,' - ',moda.mod_nombre) as nombre
                FROM db_academico.planificacion pla
                inner join db_academico.modalidad moda on moda.mod_id=pla.mod_id
                where pla.pla_estado= :estado and pla.pla_estado_logico= :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        //print_r($resultData);die();
        return $resultData;
    }

}