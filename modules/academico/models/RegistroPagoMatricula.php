<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
use \yii\data\ActiveDataProvider;
use app\modules\academico\Module as Academico;

Academico::registerTranslations();

/**
 * This is the model class for table "registro_pago_matricula".
 *
 * @property int $rpm_id
 * @property int $per_id
 * @property int $pla_id
 * @property int $ron_id
 * @property int $fpag_id
 * @property string $rpm_archivo
 * @property string $rpm_estado_aprobacion
 * @property string $rpm_estado_generado
 * @property string $rpm_acepta_terminos
 * @property string $rpm_tipo_pago
 * @property string $rpm_hoja_matriculacion
 * @property int $rpm_usuario_apruebareprueba
 * @property string $rpm_fecha_transaccion
 * @property string $rpm_total
 * @property string $rpm_interes
 * @property string $rpm_financiamiento
 * @property string $rpm_observacion
 * @property string $rpm_estado
 * @property string $rpm_fecha_creacion
 * @property int $rpm_usuario_modifica
 * @property string $rpm_fecha_modificacion
 * @property string $rpm_estado_logico
 *
 * @property EnrolamientoAgreement[] $enrolamientoAgreements
 * @property RegistroOnlineCuota[] $registroOnlineCuotas
 * @property Planificacion $pla
 * @property RegistroOnline $ron
 */
class RegistroPagoMatricula extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_pago_matricula';
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
    /*
    public function rules()
    {
        return [
            [['per_id', 'pla_id', 'ron_id', 'rpm_archivo', 'rpm_estado', 'rpm_estado_logico'], 'required'],
            [['per_id', 'pla_id', 'ron_id', 'fpag_id', 'rpm_usuario_apruebareprueba', 'rpm_usuario_modifica'], 'integer'],
            [['rpm_archivo'], 'string'],
            [['rpm_fecha_transaccion', 'rpm_fecha_creacion', 'rpm_fecha_modificacion'], 'safe'],
            [['rpm_total', 'rpm_interes', 'rpm_financiamiento'], 'number'],
            [['rpm_estado_aprobacion', 'rpm_estado_generado', 'rpm_acepta_terminos', 'rpm_tipo_pago', 'rpm_estado', 'rpm_estado_logico'], 'string', 'max' => 1],
            [['rpm_hoja_matriculacion'], 'string', 'max' => 200],
            [['rpm_observacion'], 'string', 'max' => 1000],
            [['pla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Planificacion::className(), 'targetAttribute' => ['pla_id' => 'pla_id']],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
        ];
    }
    */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpm_id' => 'Rpm ID',
            'per_id' => 'Per ID',
            'pla_id' => 'Pla ID',
            'ron_id' => 'Ron ID',
            'fpag_id' => 'Fpag ID',
            'rpm_archivo' => 'Rpm Archivo',
            'rpm_estado_aprobacion' => 'Rpm Estado Aprobacion',
            'rpm_estado_generado' => 'Rpm Estado Generado',
            'rpm_acepta_terminos' => 'Rpm Acepta Terminos',
            'rpm_tipo_pago' => 'Rpm Tipo Pago',
            'rpm_hoja_matriculacion' => 'Rpm Hoja Matriculacion',
            'rpm_usuario_apruebareprueba' => 'Rpm Usuario Apruebareprueba',
            'rpm_fecha_transaccion' => 'Rpm Fecha Transaccion',
            'rpm_total' => 'Rpm Total',
            'rpm_interes' => 'Rpm Interes',
            'rpm_financiamiento' => 'Rpm Financiamiento',
            'rpm_observacion' => 'Rpm Observacion',
            'rpm_estado' => 'Rpm Estado',
            'rpm_fecha_creacion' => 'Rpm Fecha Creacion',
            'rpm_usuario_modifica' => 'Rpm Usuario Modifica',
            'rpm_fecha_modificacion' => 'Rpm Fecha Modificacion',
            'rpm_estado_logico' => 'Rpm Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnrolamientoAgreements()
    {
        return $this->hasMany(EnrolamientoAgreement::className(), ['rpm_id' => 'rpm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroOnlineCuotas()
    {
        return $this->hasMany(RegistroOnlineCuota::className(), ['rpm_id' => 'rpm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPla()
    {
        return $this->hasOne(Planificacion::className(), ['pla_id' => 'pla_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRon()
    {
        return $this->hasOne(RegistroOnline::className(), ['ron_id' => 'ron_id']);
    }

    public static function checkPagoEstudiante($per_id, $pla_id) {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $estado_registro = 0;

        $sql = "
            SELECT rpm_id
            FROM " . $con_academico->dbname . ".registro_pago_matricula as rpm            
            WHERE rpm.per_id=:per_id
            -- AND rpm.pla_id=:pla_id            
            AND rpm.rpm_estado=:estado
            AND rpm.rpm_estado_logico=:estado;
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    function getAllRegistroMatriculaxGenerarGridold($search = NULL, $mod_id = NULL, $estado = NULL, $carrera = NULL, $periodo = NULL, $fechaini = NULL, $fechafin = NULL, $dataProvider = false){
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != ""){
            $condition .= "m.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != ""){
            //$estado = ($estado == 1)?1:0;
            $condition .= "reg.rpm_estado_aprobacion = :estado AND ";
        }
        if(isset($carrera) && $carrera != ""){
            $carrera = "%" . $carrera . "%";
            $condition .= "pe.pes_carrera like :carrera AND ";
        }
        if(isset($periodo) && $periodo != ""){
            $periodo = "%" . $periodo . "%";
            $condition .= "p.pla_periodo_academico like :periodo AND ";
        }
        
        $sql = "SELECT
                    r.ron_fecha_registro as ron_fecha_registro,
                    r.ron_id as Id, 
                    pe.per_id as per_id,
                    ram.rama_id as rama_id,
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    m.mod_nombre as Modalidad,
                    p.pla_periodo_academico as Periodo,
                    reg.rpm_estado_aprobacion as Estado,
                    case when ifnull(reg.rpm_estado_aprobacion,-1)= -1 then 'Payment Pending' 
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 0 then 'Payment Processing'
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 1 then 'Payment Processed'
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 2 then 'Payment not Approved'
                        end as DesEstado
                FROM " . $con_academico->dbname . ".planificacion_estudiante AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad as m on m.mod_id = p.mod_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias AS ram on ram.ron_id = r.ron_id
                    -- LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.per_id = pe.per_id and r.ron_id = reg.ron_id and reg.rpm_estado = 1 and reg.rpm_estado_logico = 1
                    INNER JOIN (
                        SELECT 
                            r.pes_id as pes_id,
                            rm.rama_id as rama_id,
                            COUNT(*) as Cant,
                            SUM(it.roi_costo) as Costo, 
                            SUM(it.roi_creditos) as Creditos
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS it ON it.ron_id = r.ron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias AS rm ON rm.ron_id = r.ron_id 
                                AND (
                                    rm.roi_id_1 = it.roi_id OR 
                                    rm.roi_id_2 = it.roi_id OR 
                                    rm.roi_id_3 = it.roi_id OR
                                    rm.roi_id_4 = it.roi_id OR
                                    rm.roi_id_5 = it.roi_id OR
                                    rm.roi_id_6 = it.roi_id
                                )
                        WHERE
                            r.ron_estado = '1' AND r.ron_estado_logico = '1' AND
                            it.roi_estado = '1' AND it.roi_estado_logico = '1' AND
                            rm.rama_estado = '1' AND rm.rama_estado_logico = '1'
                        GROUP BY
                            r.pes_id, rm.rama_id
                    ) AS tmp ON tmp.rama_id = ram.rama_id -- tmp.pes_id = pe.pes_id
                    LEFT JOIN (
                        SELECT 
                            MIN(r.ron_id) as ron_id,
                            r.per_id as per_id
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                        WHERE 
                            r.ron_estado =1 and r.ron_estado_logico =1
                        GROUP BY
                            per_id
                    ) AS mi ON mi.ron_id = r.ron_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.per_id = pe.per_id 
                        and r.ron_id = reg.ron_id and reg.rpm_estado = 1 and reg.rpm_estado_logico =1 and reg.rpm_id = ram.rpm_id
                    LEFT JOIN " . $con_academico->dbname . ".enrolamiento_agreement AS enr on enr.ron_id = r.ron_id 
                        and reg.rpm_estado = 1 and reg.rpm_estado_logico =1 and enr.rpm_id = ram.rpm_id and enr.eagr_estado = 1 and enr.eagr_estado_logico = 1
                    LEFT JOIN (
                        SELECT
                            ro.ron_id as ron_id,
                            ro.rpm_id as rpm_id,
                            SUM(ri.roi_costo) as Refund
                        FROM
                            " . $con_academico->dbname . ".cancelacion_registro_online_item AS it
                            INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online AS ro ON it.cron_id = ro.cron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS ri ON ri.roi_id = it.roi_id
                        WHERE
                            ro.cron_estado_cancelacion = 2 AND
                            it.croi_estado = 1 AND it.croi_estado_logico = 1 AND
                            ro.cron_estado = 1 AND ro.cron_estado_logico = 1 AND
                            ri.roi_estado = 1 AND ri.roi_estado_logico = 1
                        GROUP BY
                            ro.ron_id, ro.rpm_id
                    ) AS rf ON rf.ron_id = r.ron_id AND rf.rpm_id = reg.rpm_id
                WHERE 
                    $str_search 
                    $condition
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    ram.rama_estado = 1 and ram.rama_estado_logico = 1 and 
                    r.ron_estado =1 and
                    r.ron_estado_logico =1";
        $comando = $con_academico->createCommand($sql);
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "")  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "")  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($carrera) && $carrera != "") $comando->bindParam(":carrera",$carrera, \PDO::PARAM_STR);
        if(isset($periodo) && $periodo != "") $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }


        
    function getAllRegistroMatriculaxGenerarGrid($search = NULL, $mod_id = NULL, $estado = NULL, $carrera = NULL, $periodo = NULL, $fechaini = NULL, $fechafin = NULL, $dataProvider = false){
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != ""){
            $condition .= "m.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != ""){
            //$estado = ($estado == 1)?1:0;
            $condition .= "reg.rpm_estado_aprobacion = :estado AND ";
        }
        if(isset($carrera) && $carrera != ""){
            $carrera = "%" . $carrera . "%";
            $condition .= "pe.pes_carrera like :carrera AND ";
        }
        if(isset($periodo) && $periodo != ""){
            $periodo = "%" . $periodo . "%";
            $condition .= "p.pla_periodo_academico like :periodo AND ";
        }

        //if(isset($fechaini) && $fechaini != ""){
        //  $condition .= "r.ron_fecha_registro > :fec_ini AND";
        //}

       //if(isset($fechafin) && $fechafin != ""){
       //    $condition .= "r.ron_fecha_registro < :fec_fin AND";
       // }

        if ($fechaini != "" && $fechafin != "") {
            $str_search .= "r.ron_fecha_registro >= :fec_ini AND ";
            $str_search .= "r.ron_fecha_registro <= :fec_fin AND ";
        }

       
         
        // if(isset($fechafin) && $fechafin != ""){
        //    if(isset($fechaini) && $fechaini != ""){
        //        $condition .= "(r.ron_fecha_registro between :fec_ini AND fec_fin) AND";
        //     }
        //             }

       
         

        
        $sql = "SELECT
                    r.ron_fecha_registro as ron_fecha_registro,
                    r.ron_id as Id, 
                    pe.per_id as per_id,
                    ram.rama_id as rama_id,
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    m.mod_nombre as Modalidad,
                    p.pla_periodo_academico as Periodo,
                    reg.rpm_estado_aprobacion as Estado,
                    case when ifnull(reg.rpm_estado_aprobacion,-1)= -1 then 'Payment Pending' 
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 0 then 'Payment Processing'
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 1 then 'Payment Processed'
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 2 then 'Payment not Approved'
                        end as DesEstado
                FROM " . $con_academico->dbname . ".planificacion_estudiante AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad as m on m.mod_id = p.mod_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias AS ram on ram.ron_id = r.ron_id
                    -- LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.per_id = pe.per_id and r.ron_id = reg.ron_id and reg.rpm_estado = 1 and reg.rpm_estado_logico = 1
                    INNER JOIN (
                        SELECT 
                            r.pes_id as pes_id,
                            rm.rama_id as rama_id,
                            COUNT(*) as Cant,
                            SUM(it.roi_costo) as Costo, 
                            SUM(it.roi_creditos) as Creditos
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS it ON it.ron_id = r.ron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias AS rm ON rm.ron_id = r.ron_id 
                                AND (
                                    rm.roi_id_1 = it.roi_id OR 
                                    rm.roi_id_2 = it.roi_id OR 
                                    rm.roi_id_3 = it.roi_id OR
                                    rm.roi_id_4 = it.roi_id OR
                                    rm.roi_id_5 = it.roi_id OR
                                    rm.roi_id_6 = it.roi_id
                                )
                        WHERE
                            r.ron_estado = '1' AND r.ron_estado_logico = '1' AND
                            it.roi_estado = '1' AND it.roi_estado_logico = '1' AND
                            rm.rama_estado = '1' AND rm.rama_estado_logico = '1'
                        GROUP BY
                            r.pes_id, rm.rama_id
                    ) AS tmp ON tmp.rama_id = ram.rama_id -- tmp.pes_id = pe.pes_id
                    LEFT JOIN (
                        SELECT 
                            MIN(r.ron_id) as ron_id,
                            r.per_id as per_id
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                        WHERE 
                            r.ron_estado =1 and r.ron_estado_logico =1
                        GROUP BY
                            per_id
                    ) AS mi ON mi.ron_id = r.ron_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.per_id = pe.per_id 
                        and r.ron_id = reg.ron_id and reg.rpm_estado = 1 and reg.rpm_estado_logico =1 and reg.rpm_id = ram.rpm_id
                    LEFT JOIN " . $con_academico->dbname . ".enrolamiento_agreement AS enr on enr.ron_id = r.ron_id 
                        and reg.rpm_estado = 1 and reg.rpm_estado_logico =1 and enr.rpm_id = ram.rpm_id and enr.eagr_estado = 1 and enr.eagr_estado_logico = 1
                    LEFT JOIN (
                        SELECT
                            ro.ron_id as ron_id,
                            ro.rpm_id as rpm_id,
                            SUM(ri.roi_costo) as Refund
                        FROM
                            " . $con_academico->dbname . ".cancelacion_registro_online_item AS it
                            INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online AS ro ON it.cron_id = ro.cron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS ri ON ri.roi_id = it.roi_id
                        WHERE
                            ro.cron_estado_cancelacion = 2 AND
                            it.croi_estado = 1 AND it.croi_estado_logico = 1 AND
                            ro.cron_estado = 1 AND ro.cron_estado_logico = 1 AND
                            ri.roi_estado = 1 AND ri.roi_estado_logico = 1
                        GROUP BY
                            ro.ron_id, ro.rpm_id
                    ) AS rf ON rf.ron_id = r.ron_id AND rf.rpm_id = reg.rpm_id
                WHERE 
                    $str_search 
                    $condition
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    ram.rama_estado = 1 and ram.rama_estado_logico = 1 and 
                    r.ron_estado =1 and
                    r.ron_estado_logico =1";
        $comando = $con_academico->createCommand($sql);
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "")  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "")  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($carrera) && $carrera != "") $comando->bindParam(":carrera",$carrera, \PDO::PARAM_STR);
        if(isset($periodo) && $periodo != "") $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);


       // if(isset($fechaini) && $fechaini != "") {
       // $fecha_ini = $fechaini . " 00:00:00";
       // $comando->bindParam(":fec_ini",$fecha_ini, \PDO::PARAM_STR);
       // }
        
        //if(isset($fechafin) && $fechafin != "") {
        //$fecha_fin = $fechafin . " 23:59:59";
       // $comando->bindParam(":fec_fin",$fecha_fin, \PDO::PARAM_STR);
       // }
         

        if ($fechaini != "" && $fechafin != "") {
            $fecha_ini = $fechaini . " 00:00:00";
            $fecha_fin = $fechafin . " 23:59:59";
            $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
            $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);

        }
        
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }


    function getAllRegistroMatriculaxGenerarGridexcel($search = NULL, $mod_id = NULL, $estado = NULL, $carrera = NULL, $periodo = NULL, $fechaini = NULL, $fechafin = NULL, $dataProvider = false){
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != ""){
            $condition .= "m.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != ""){
            //$estado = ($estado == 1)?1:0;
            $condition .= "reg.rpm_estado_aprobacion = :estado AND ";
        }
        if(isset($carrera) && $carrera != ""){
            $carrera = "%" . $carrera . "%";
            $condition .= "pe.pes_carrera like :carrera AND ";
        }
        if(isset($periodo) && $periodo != ""){
            $periodo = "%" . $periodo . "%";
            $condition .= "p.pla_periodo_academico like :periodo AND ";
        }

        //if(isset($fechaini) && $fechaini != ""){
        //  $condition .= "r.ron_fecha_registro > :fec_ini AND";
        //}

       //if(isset($fechafin) && $fechafin != ""){
       //    $condition .= "r.ron_fecha_registro < :fec_fin AND";
       // }

        if ($fechaini != "" && $fechafin != "") {
            $str_search .= "r.ron_fecha_registro >= :fec_ini AND ";
            $str_search .= "r.ron_fecha_registro <= :fec_fin AND ";
        }

       
         
        // if(isset($fechafin) && $fechafin != ""){
        //    if(isset($fechaini) && $fechaini != ""){
        //        $condition .= "(r.ron_fecha_registro between :fec_ini AND fec_fin) AND";
        //     }
        //             }

       
         

        
        $sql = "SELECT
                    r.ron_fecha_registro as ron_fecha_registro,
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    m.mod_nombre as Modalidad,
                    p.pla_periodo_academico as Periodo,
                    reg.rpm_estado_aprobacion as Estado,
                    case when ifnull(reg.rpm_estado_aprobacion,-1)= -1 then 'Payment Pending' 
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 0 then 'Payment Processing'
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 1 then 'Payment Processed'
                         when ifnull(reg.rpm_estado_aprobacion,-1)= 2 then 'Payment not Approved'
                        end as DesEstado
                FROM " . $con_academico->dbname . ".planificacion_estudiante AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad as m on m.mod_id = p.mod_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias AS ram on ram.ron_id = r.ron_id
                    -- LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.per_id = pe.per_id and r.ron_id = reg.ron_id and reg.rpm_estado = 1 and reg.rpm_estado_logico = 1
                    INNER JOIN (
                        SELECT 
                            r.pes_id as pes_id,
                            rm.rama_id as rama_id,
                            COUNT(*) as Cant,
                            SUM(it.roi_costo) as Costo, 
                            SUM(it.roi_creditos) as Creditos
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS it ON it.ron_id = r.ron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_adicional_materias AS rm ON rm.ron_id = r.ron_id 
                                AND (
                                    rm.roi_id_1 = it.roi_id OR 
                                    rm.roi_id_2 = it.roi_id OR 
                                    rm.roi_id_3 = it.roi_id OR
                                    rm.roi_id_4 = it.roi_id OR
                                    rm.roi_id_5 = it.roi_id OR
                                    rm.roi_id_6 = it.roi_id
                                )
                        WHERE
                            r.ron_estado = '1' AND r.ron_estado_logico = '1' AND
                            it.roi_estado = '1' AND it.roi_estado_logico = '1' AND
                            rm.rama_estado = '1' AND rm.rama_estado_logico = '1'
                        GROUP BY
                            r.pes_id, rm.rama_id
                    ) AS tmp ON tmp.rama_id = ram.rama_id -- tmp.pes_id = pe.pes_id
                    LEFT JOIN (
                        SELECT 
                            MIN(r.ron_id) as ron_id,
                            r.per_id as per_id
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                        WHERE 
                            r.ron_estado =1 and r.ron_estado_logico =1
                        GROUP BY
                            per_id
                    ) AS mi ON mi.ron_id = r.ron_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.per_id = pe.per_id 
                        and r.ron_id = reg.ron_id and reg.rpm_estado = 1 and reg.rpm_estado_logico =1 and reg.rpm_id = ram.rpm_id
                    LEFT JOIN " . $con_academico->dbname . ".enrolamiento_agreement AS enr on enr.ron_id = r.ron_id 
                        and reg.rpm_estado = 1 and reg.rpm_estado_logico =1 and enr.rpm_id = ram.rpm_id and enr.eagr_estado = 1 and enr.eagr_estado_logico = 1
                    LEFT JOIN (
                        SELECT
                            ro.ron_id as ron_id,
                            ro.rpm_id as rpm_id,
                            SUM(ri.roi_costo) as Refund
                        FROM
                            " . $con_academico->dbname . ".cancelacion_registro_online_item AS it
                            INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online AS ro ON it.cron_id = ro.cron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS ri ON ri.roi_id = it.roi_id
                        WHERE
                            ro.cron_estado_cancelacion = 2 AND
                            it.croi_estado = 1 AND it.croi_estado_logico = 1 AND
                            ro.cron_estado = 1 AND ro.cron_estado_logico = 1 AND
                            ri.roi_estado = 1 AND ri.roi_estado_logico = 1
                        GROUP BY
                            ro.ron_id, ro.rpm_id
                    ) AS rf ON rf.ron_id = r.ron_id AND rf.rpm_id = reg.rpm_id
                WHERE 
                    $str_search 
                    $condition
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    ram.rama_estado = 1 and ram.rama_estado_logico = 1 and 
                    r.ron_estado =1 and
                    r.ron_estado_logico =1";
        $comando = $con_academico->createCommand($sql);
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "")  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "")  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($carrera) && $carrera != "") $comando->bindParam(":carrera",$carrera, \PDO::PARAM_STR);
        if(isset($periodo) && $periodo != "") $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);


       // if(isset($fechaini) && $fechaini != "") {
       // $fecha_ini = $fechaini . " 00:00:00";
       // $comando->bindParam(":fec_ini",$fecha_ini, \PDO::PARAM_STR);
       // }
        
        //if(isset($fechafin) && $fechafin != "") {
        //$fecha_fin = $fechafin . " 23:59:59";
       // $comando->bindParam(":fec_fin",$fecha_fin, \PDO::PARAM_STR);
       // }
         

        if ($fechaini != "" && $fechafin != "") {
            $fecha_ini = $fechaini . " 00:00:00";
            $fecha_fin = $fechafin . " 23:59:59";
            $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
            $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);

        }
        
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }

    

    function getAllListRegistryPaymentGrid($search = NULL, $isEstud, $mod_id = NULL, $estado = NULL, $periodo = NULL, $dataProvider = false, $per_id, $grupo_id){
        $con_academico = \Yii::$app->db_academico;
        $con = \Yii::$app->db;
         if ($per_id==Null) { $per_id = Yii::$app->session->get("PB_perid"); } 
        //$per_id = Yii::$app->session->get("PB_perid"); 
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != "" && $mod_id != 0){
            $condition .= "me.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != "" && $estado != -1){
            $condition .= "reg.rpm_estado_generado = :estado AND ";
        }
        \app\models\Utilities::putMessageLogFile('A1' .$periodo);
        if(isset($periodo) && $periodo != "" && $periodo != 0){
            //$periodo = "%" . $periodo . "%";
            $condition .= "p.pla_id = :periodo AND ";
            //$condition .= "p.pla_periodo_academico like :periodo AND ";
        }

        if ($grupo_id == 12){
            \app\models\Utilities::putMessageLogFile('ENTRO getAllListRegistryPaymentGrid' .$isEstud);
            //if($isEstud) {
                \app\models\Utilities::putMessageLogFile('getAllListRegistryPaymentGrid' .$isEstud);
                $condition .= "per.per_id = :per_id AND ";
            //}    
        }
        
        
        $sql = "SELECT distinct
                    r.ron_id as Id, 
                    reg.rpm_id as rpm_id, 
                    ram.rama_id as rama_id,
                    pe.pes_nombres as Estudiante,
                    pe.per_id as per_id,
                    pe.pes_dni as Cedula,
                    mo.mod_nombre as Modalidad,
                    p.pla_periodo_academico as Periodo,
                    p.pla_id as pla_id,
                    tmp.Cant as Cant,
                    tmp.Costo as Costo,
                    rf.Refund as Refund,
                    tmp.Creditos as Creditos,
                    --mi.ron_id as Enroll,
                    enr.ron_id as Enroll,
                    ifnull(reg.rpm_estado_aprobacion,2) as Aprobacion,
                    ifnull(reg.rpm_estado_generado,0) as Estado
                FROM " . $con_academico->dbname . ".planificacion_estudiante AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con->dbname . ".persona as per on per.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante as est on est.per_id = per.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa AS ec ON est.est_id = ec.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad AS me ON ec.meun_id = me.meun_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico AS ea ON ea.eaca_id = me.eaca_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica AS ua ON me.uaca_id = ua.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad AS mo ON mo.mod_id = me.mod_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_adicional_materias AS ram on ram.ron_id = r.ron_id
                    LEFT JOIN (
                        SELECT 
                            r.pes_id as pes_id,
                            rm.rama_id as rama_id,
                            COUNT(*) as Cant,
                            SUM(it.roi_costo) as Costo, 
                            SUM(it.roi_creditos) as Creditos
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS it ON it.ron_id = r.ron_id
                            LEFT JOIN " . $con_academico->dbname . ".registro_adicional_materias AS rm ON rm.ron_id = r.ron_id 
                            AND (
                            rm.roi_id_1 = it.roi_id OR
                            rm.roi_id_2 = it.roi_id OR
                            rm.roi_id_3 = it.roi_id OR
                            rm.roi_id_4 = it.roi_id OR
                            rm.roi_id_5 = it.roi_id OR
                            rm.roi_id_6 = it.roi_id
                            )
                        WHERE
                            r.ron_estado = '1' AND r.ron_estado_logico = '1' AND
                            it.roi_estado = '1' AND it.roi_estado_logico = '1' AND
                            rm.rama_estado = '1' AND rm.rama_estado_logico = '1'
                        GROUP BY
                            r.pes_id, rm.rama_id
                    ) AS tmp ON tmp.rama_id = ram.rama_id -- tmp.pes_id = pe.pes_id
                    LEFT JOIN (
                        SELECT 
                            MIN(r.ron_id) as ron_id,
                            r.per_id as per_id
                        FROM
                            " . $con_academico->dbname . ".registro_online AS r
                        WHERE 
                            r.ron_estado =1 and r.ron_estado_logico =1
                        GROUP BY
                            per_id
                    ) AS mi ON mi.ron_id = r.ron_id
                    LEFT JOIN " . $con_academico->dbname . ".registro_pago_matricula AS reg on reg.per_id = per.per_id 
                        and r.ron_id = reg.ron_id and reg.rpm_estado = 1 and reg.rpm_estado_logico =1
                    LEFT JOIN " . $con_academico->dbname . ".enrolamiento_agreement AS enr on enr.ron_id = r.ron_id 
                        and reg.rpm_estado = 1 and reg.rpm_estado_logico =1 and enr.rpm_id = ram.rpm_id and enr.eagr_estado = 1 and enr.eagr_estado_logico = 1
                    LEFT JOIN (
                        SELECT
                            ro.ron_id as ron_id,
                            ro.rpm_id as rpm_id,
                            SUM(ri.roi_costo) as Refund
                        FROM
                            " . $con_academico->dbname . ".cancelacion_registro_online_item AS it
                            INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online AS ro ON it.cron_id = ro.cron_id
                            INNER JOIN " . $con_academico->dbname . ".registro_online_item AS ri ON ri.roi_id = it.roi_id
                        WHERE
                            ro.cron_estado_cancelacion = 2 AND
                            it.croi_estado = 1 AND it.croi_estado_logico = 1 AND
                            ro.cron_estado = 1 AND ro.cron_estado_logico = 1 AND
                            ri.roi_estado = 1 AND ri.roi_estado_logico = 1
                        GROUP BY
                            ro.ron_id, ro.rpm_id
                    ) AS rf ON rf.ron_id = r.ron_id AND rf.rpm_id = reg.rpm_id
                WHERE 
                    $str_search 
                    $condition
                    pe.pes_estado =1 and pe.pes_estado_logico =1 and
                    p.pla_estado =1 and p.pla_estado_logico =1 and
                    per.per_estado = 1 and per.per_estado_logico = 1 and 
                    est.est_estado =1 and est.est_estado_logico = 1 and
                    r.ron_estado =1 and r.ron_estado_logico =1";

      
        $comando = $con_academico->createCommand($sql);
        
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "" && $mod_id != 0)  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "" && $estado != -1)  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($periodo) && $periodo != "" && $periodo != 0) $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);
        if($isEstud)    $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);

        $res = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile($comando->getRawSql());

        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }

    function getRegistroPagoMatriculaByRegistroOnline($reg_id, $per_id){
        $con_academico = \Yii::$app->db_academico;
        
        $sql = "SELECT
                    reg.rpm_id as Id, 
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    p.pla_periodo_academico as Periodo,
                    reg.rpm_estado_generado as Estado
                FROM " . $con_academico->dbname . ".registro_pago_matricula AS reg
                    INNER JOIN " . $con_academico->dbname . ".planificacion_estudiante as pe on reg.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = reg.pla_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id and r.ron_id = reg.ron_id
                WHERE 
                    reg.rpm_estado = 1 and 
                    reg.rpm_estado_logico = 1 and 
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    r.ron_estado =1 and
                    r.ron_estado_logico =1 and
                    reg.per_id = :per_id and 
                    r.ron_id = :ron_id;";   
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id",$reg_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res;
    }
    /* Grace Viteri
     * Para obtener el estado de generado.
    */
    function getRegistroPagoMatriculaById($id_rpm){
        $con_academico = \Yii::$app->db_academico;
        
        $sql = "SELECT                     
                    rpm_estado_generado as estado
                FROM " . $con_academico->dbname . ".registro_pago_matricula AS reg                    
                WHERE 
                    reg.rpm_estado = 1 and 
                    reg.rpm_estado_logico = 1 and                     
                    reg.rpm_id = :rpm_id";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":rpm_id",$id_rpm, \PDO::PARAM_INT);        
        $res = $comando->queryOne();
        return $res;
    }

    public static function getCarreraByPersona($per_id, $emp_id){
        $con = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db;
        $sql = "SELECT                     
                    p.per_pri_nombre as Nombre,
                    p.per_pri_apellido as Apellido,
                    ea.eaca_nombre as Carrera,
                    ea.eaca_id as eaca_id,
                    ua.uaca_id as uaca_id,
                    meu.mod_id as mod_id,
                    e.est_id as est_id
                FROM 
                    " . $con->dbname . ".estudiante AS e
                    INNER JOIN " . $con->dbname . ".estudiante_carrera_programa AS ecp ON ecp.est_id = e.est_id
                    INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad AS meu ON meu.meun_id = ecp.meun_id
                    INNER JOIN " . $con->dbname . ".estudio_academico AS ea ON ea.eaca_id = meu.eaca_id
                    INNER JOIN " . $con->dbname . ".unidad_academica AS ua ON ua.uaca_id = meu.uaca_id
                    INNER JOIN " . $con2->dbname . ".persona AS p ON p.per_id = e.per_id
                    INNER JOIN " . $con2->dbname . ".empresa as em ON em.emp_id = meu.emp_id
                WHERE 
                    e.per_id = :per_id AND em.emp_id = :emp_id AND
                    e.est_estado = 1 AND e.est_estado_logico = 1 AND
                    meu.meun_estado = 1 AND meu.meun_estado_logico = 1 AND 
                    ecp.ecpr_estado = 1 AND ecp.ecpr_estado_logico = 1 AND
                    ea.eaca_estado = 1 AND ea.eaca_estado_logico = 1 AND
                    p.per_estado = 1 AND p.per_estado_logico = 1 AND
                    em.emp_estado = 1 AND em.emp_estado_logico = 1";
        $comando = $con->createCommand($sql);
      
        \app\models\Utilities::putMessageLogFile('Em ID  Didimo: :emp_id  '. $sql);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);      
        $comando->bindParam(":emp_id",$emp_id, \PDO::PARAM_INT);     
        $res = $comando->queryOne();
        return $res;
    }

    public static function getItemsPrice($scat_id = null){
        $con = \Yii::$app->db_facturacion;
        $condition = "";
        if(isset($scat_id)){
            $condition .= "sc.scat_id = :scat_id AND ";
        }
        $sql = "SELECT           
                    ip.ipre_id as Id,          
                    sc.scat_nombre as Nombre,
                    ip.ipre_precio as Precio
                FROM 
                    " . $con->dbname . ".item_precio AS ip
                    INNER JOIN " . $con->dbname . ".item AS i ON i.ite_id = ip.ite_id
                    INNER JOIN " . $con->dbname . ".sub_categoria AS sc ON sc.scat_id = i.scat_id
                WHERE 
                    $condition 
                    sc.scat_estado = 1 AND sc.scat_estado_logico = 1 AND
                    i.ite_estado = 1 AND i.ite_estado_logico = 1 AND 
                    ip.ipre_estado = 1 AND ip.ipre_estado = 1 ";
        $comando = $con->createCommand($sql);
        if(isset($scat_id))
            $comando->bindParam(":scat_id",$scat_id, \PDO::PARAM_INT);   
        $res = $comando->queryAll();
        return $res;
    }

    public static function getFechasVencimiento($paca_fecha_inicio){
        $numcuotas = 5;
        $arr_vencimiento = array();
        $fechaFinReg = $paca_fecha_inicio;
        $fechaFinPay = date('Y-m-d', strtotime("$fechaFinReg +5 day"));
        $initialMonth = date('F', strtotime("$fechaFinReg +5 day"));
        $initialMonNum = date('m', strtotime("$fechaFinReg +5 day"));
        $initialDay = date('d', strtotime("$fechaFinReg +5 day"));
        $initialYear = date('y', strtotime("$fechaFinReg +5 day"));


        $initialDay05 = date('d', strtotime("$fechaFinReg -12 day"));

        for($i=0; $i < $numcuotas; $i++){
            if($i == 0){
                $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', date('F'))) . " " . date('d') . "/" . date('y');
            }else{
                if(($initialMonNum + $i - 1) > 12){
                    $initialYear += 1;
                }
                if($i == 1){
                    $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', $initialMonth)) . " " . $initialDay . "/" . $initialYear;
                }else{
                    $j = $i - 1;
                    $band = checkdate($initialMonNum, $initialDay05, $initialYear);
                    if($band){
                        $initialMonth = date('F', strtotime("$initialYear-$initialMonNum-$initialDay05"));
                        $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', $initialMonth)) . " " . $initialDay05 . "/" . $initialYear;
                    }else{
                        $newDate = date('Y-m-d', strtotime("$fechaFinPay +$j months"));
                        $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', date('F', strtotime($newDate)))) . " " . date('d', strtotime($newDate)) . "/" . date('y', strtotime($newDate));
                    }
                }
                $initialMonNum ++;
            }
        }
        return $arr_vencimiento;
    }

    
    
     function getFacturasPendientesCuotas($reg_id, $per_id){
        $con_academico = \Yii::$app->db_academico;
        $con_facturacion = \Yii::$app->db_facturacion;
        
        $sql = "SELECT
                    fpe.fpe_id,
                    fpe.fpe_num_cuota,
                    roc.roc_id, 
                    roc.roc_vencimiento, 
                    roc.roc_num_cuota,
                    roc.roc_estado_logico,
                    roc.roc_estado,
                    roc.roc_costo,
                    roc.roc_estado_pago,
                    reg.rpm_id as Id, 
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    p.pla_periodo_academico as Periodo,
                    reg.rpm_estado_generado as Estado
                FROM " . $con_academico->dbname . ".registro_pago_matricula AS reg
                    INNER JOIN " . $con_academico->dbname . ".planificacion_estudiante as pe on reg.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = reg.pla_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id and r.ron_id = reg.ron_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online_cuota  roc ON roc.rpm_id =reg.rpm_id
                    INNER JOIN " . $con_facturacion->dbname . ".facturas_pendientes_estudiante  fpe ON fpe.fpe_id =roc.fpe_id
                WHERE 
                    reg.rpm_estado = 1 and 
                    reg.rpm_estado_logico = 1 and 
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    r.ron_estado =1 and
                    r.ron_estado_logico =1 and
                    reg.per_id = :per_id and 
                    r.ron_id = :ron_id and 
                    roc.roc_estado = 1 and 
                    roc.roc_estado_logico = 1
                    and roc.roc_estado_pago ='P'
                    and fpe.fpe_estado_logico = 1
                    and fpe.fpe_estado = 1";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id",$reg_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }


    function getRegistroPagoMatriculaCuotas($reg_id, $per_id){
        $con_academico = \Yii::$app->db_academico;
        
        $sql = "SELECT
                    roc.roc_id, 
                    roc.roc_vencimiento, 
                    roc.roc_num_cuota,
                    roc.roc_estado_logico,
                    roc.roc_estado,
                    roc.roc_costo,
                    roc.roc_estado_pago,
                    reg.rpm_id as Id, 
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.pes_carrera as Carrera,
                    p.pla_periodo_academico as Periodo,
                    reg.rpm_estado_generado as Estado
                FROM " . $con_academico->dbname . ".registro_pago_matricula AS reg
                    INNER JOIN " . $con_academico->dbname . ".planificacion_estudiante as pe on reg.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = reg.pla_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id and r.ron_id = reg.ron_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online_cuota  roc ON roc.rpm_id =reg.rpm_id
                WHERE 
                    reg.rpm_estado = 1 and 
                    reg.rpm_estado_logico = 1 and 
                    pe.pes_estado =1 and
                    pe.pes_estado_logico =1 and
                    p.pla_estado =1 and
                    p.pla_estado_logico =1 and
                    r.ron_estado =1 and
                    r.ron_estado_logico =1 and
                    reg.per_id = :per_id and 
                    r.ron_id = :ron_id and 
                    roc.roc_estado = 1 and 
                    roc.roc_estado_logico = 1
                    and roc.roc_estado_pago ='P'";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id",$reg_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        return $res;
    }
    //Consulta alor pendiente de persona 
    function getFacturasPendientesCuotasBycuota($num_cuota, $per_id){
        $con_facturacion = \Yii::$app->db_facturacion;
        
        $sql = "SELECT  fpe.fpe_id,
                        fpe.fpe_num_cuota 
                FROM  db_facturacion_mbtu.facturas_pendientes_estudiante  fpe
                WHERE 
                    fpe.fpe_estado = 1 and 
                    fpe.fpe_estado_logico = 1  and
                    fpe.per_id = ".$per_id." and 
                    (fpe.fpe_valor -fpe.fpe_abono) > 0 and
                    fpe.fpe_num_cuota = '".$num_cuota."'";

        $comando = $con_facturacion->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('mensaje: ' .$comando->getRawSql());
        $comando->bindParam(":num_cuota",$num_cuota, \PDO::PARAM_STR);
        $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res;
    }

    
    public static function getFechasVencimiento2($paca_fecha_inicio){
        $numcuotas = 6;
        $arr_vencimiento = array();
        $fechaFinReg = $paca_fecha_inicio;
        $fechaFinPay = date('Y-m-d', strtotime("$fechaFinReg +5 day"));
        $initialMonth = date('F', strtotime("$fechaFinReg +5 day"));
        $initialMonNum = date('m', strtotime("$fechaFinReg +5 day"));
        $initialDay = date('d', strtotime("$fechaFinReg +5 day"));
        $initialYear = date('y', strtotime("$fechaFinReg +5 day"));


        $initialDay05 = date('d', strtotime("$fechaFinReg -12 day"));

        for($i=0; $i < $numcuotas; $i++){
            if($i == 0){
                $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', date('F'))) . " " . date('d') . "/" . date('y');
            }else{
                if(($initialMonNum + $i - 1) > 12){
                    $initialYear += 1;
                }
                if($i == 1){
                    $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', $initialMonth)) . " " . $initialDay . "/" . $initialYear;
                }else{
                    $j = $i - 1;
                    $band = checkdate($initialMonNum, $initialDay05, $initialYear);
                    if($band){
                        $initialMonth = date('F', strtotime("$initialYear-$initialMonNum-$initialDay05"));
                        $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', $initialMonth)) . " " . $initialDay05 . "/" . $initialYear;
                    }else{
                        $newDate = date('Y-m-d', strtotime("$fechaFinPay +$j months"));
                        $arr_vencimiento[] = strtoupper(Academico::t('matriculacion', date('F', strtotime($newDate)))) . " " . date('d', strtotime($newDate)) . "/" . date('y', strtotime($newDate));
                    }
                }
                $initialMonNum ++;
            }
        }
        return $arr_vencimiento;
    }

    public static function fechasVencimientoPeriodo($pla_id){
        $con = \Yii::$app->db_academico;

        for($i = 1; $i <= 6; $i++){
            $sql = "SELECT fvpa_fecha_vencimiento as fecha
                    from " . $con->dbname . ".fechas_vencimiento_pago 
                    WHERE fvpa_estado_logico = '1'
                    and fvpa_paca_id = $pla_id
                    and fvpa_cuota = $i;";
            $comando = $con->createCommand($sql);
            \app\models\Utilities::putMessageLogFile('mensaje: ' .$comando->getRawSql());
            
            $resultData = $comando->queryOne();

            $fechaFin = $resultData['fecha'];
            //$fechaFinPay = date('Y-m-d', strtotime("$fechaFinReg"));
            $initialM = date('F', strtotime("$fechaFin"));
           // $initialMonNum = date('m', strtotime("$fechaFinReg"));
            $initialD = date('d', strtotime("$fechaFin"));
            $initialY = date('y', strtotime("$fechaFin"));    
            $arr_vencimiento2[] = strtoupper(Academico::t('matriculacion', $initialM)) . " " . $initialD . "/" . $initialY;
                                             
        }
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Periodo', 'Modalidad'],
            ],
        ]);
        return $arr_vencimiento2;
    }

    public function getModalidadEstudiante($per_id){
        $con = \Yii::$app->db_academico;
        $sql = "SELECT md.mod_id as id, md.mod_nombre as name 
                from " . $con->dbname . ".planificacion pla 
                inner join " . $con->dbname . ".planificacion_estudiante as pest on pla.pla_id = pest.pla_id
                inner join " . $con->dbname . ".modalidad md on pla.mod_id = md.mod_id
                where pest.per_id = $per_id
                and pla.pla_estado = 1 and pla.pla_estado_logico=1
                and pest.pes_estado = 1 and pest.pes_estado_logico=1
                and md.mod_estado=1 and md.mod_estado_logico=1
                 limit 0,1;";

        $comando = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('mensaje: ' .$comando->getRawSql());
        
        $resultData = $comando->queryAll();

        return $resultData;
    }

    public function getCuotasPeriodo($rama_id){
        $con = \Yii::$app->db_academico;

        $sql = "SELECT CASE count(distinct roi.roi_bloque) 
                        when 1 then 3
                        when 2 then 6
                        when 3 then 2
                        when 4 then 5
                        end as 'cuota'
        from  " . $con->dbname . ".registro_adicional_materias ram
        inner join  " . $con->dbname . ".registro_configuracion rc on rc.pla_id = ram.pla_id
        inner join  " . $con->dbname . ".registro_online ro on ram.ron_id = ro.ron_id
        inner join  " . $con->dbname . ".registro_online_item roi on ro.ron_id = roi.ron_id
        where ram.rama_id = $rama_id;";
         $comando = $con->createCommand($sql);
         //\app\models\Utilities::putMessageLogFile('mensaje: ' .$comando->getRawSql());
         
         $resultData = $comando->queryOne();
 
         return $resultData;
    }

 public function getPerfilSearchListPago($usu_id){
    $con = \Yii::$app->db_academico;
    $sql = "SELECT 
                g.gru_id as gru_id, u.usu_id as usu_id, u.usu_user as usu_user
                FROM 
                    db_asgard.usuario AS u 
                    INNER JOIN db_asgard.usua_grol_eper AS ug ON u.usu_id = ug.usu_id
                    INNER JOIN db_asgard.empresa_persona AS ep ON ug.eper_id = ep.eper_id
                    INNER JOIN db_asgard.empresa AS e ON ep.emp_id = e.emp_id
                    INNER JOIN db_asgard.grup_rol AS gr ON gr.grol_id = ug.grol_id 
                    INNER JOIN db_asgard.grupo AS g ON g.gru_id = gr.gru_id  -- gru_id: 12 Estudiante,  5: colecturia, 4:financiero
                    INNER JOIN db_asgard.persona AS p ON p.per_id = u.per_id 
                WHERE 
                    u.usu_id = :usu_id AND  -- 1511 
                    g.gru_id in(12,5) AND
                    ug.ugep_estado_logico=1 AND 
                    ug.ugep_estado=1 AND 
                    ep.eper_estado_logico=1 AND 
                    ep.eper_estado=1 AND
                    e.emp_estado_logico=1 AND 
                    e.emp_estado=1 AND
                    gr.grol_estado_logico=1 AND 
                    gr.grol_estado=1 AND 
                    p.per_estado_logico=1 AND 
                    p.per_estado=1 AND 
                    g.gru_estado = 1 AND 
                    g.gru_estado_logico=1 AND 
                    u.usu_estado=1 AND 
                    u.usu_estado_logico=1;";

    $comando = $con->createCommand($sql);
    $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
    $resultData = $comando->queryOne();
    \app\models\Utilities::putMessageLogFile('getPerfilSearchListPago: '.$comando->getRawSql());
    return $resultData;            

    }
}

