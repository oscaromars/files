<?php

namespace app\modules\academico\models;

use app\modules\academico\models\HorarioAsignaturaPeriodoTmp;
use app\modules\academico\models\HorarioAsignaturaPeriodo;
use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "registro_marcacion".
 *
 * @property int $rmar_id
 * @property string $rmar_tipo
 * @property int $pro_id
 * @property int $hape_id
 * @property string $rmar_fecha_hora_entrada
 * @property string $rmar_fecha_hora_salida
 * @property string $rmar_direccion_ip
 * @property int $usu_id
 * @property string $rmar_estado
 * @property string $rmar_fecha_creacion
 * @property string $rmar_fecha_modificacion
 * @property string $rmar_estado_logico
 *
 * @property Profesor $pro
 * @property HorarioAsignaturaPeriodo $hape
 */
class RegistroMarcacion extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'registro_marcacion';
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
            [['rmar_tipo', 'pro_id', 'hape_id', 'rmar_direccion_ip', 'usu_id', 'rmar_estado', 'rmar_estado_logico'], 'required'],
            [['pro_id', 'hape_id', 'usu_id'], 'integer'],
            [['rmar_fecha_hora_entrada', 'rmar_fecha_hora_salida', 'rmar_fecha_creacion', 'rmar_fecha_modificacion'], 'safe'],
            [['rmar_tipo', 'rmar_estado', 'rmar_estado_logico'], 'string', 'max' => 1],
            [['rmar_direccion_ip'], 'string', 'max' => 20],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
            [['hape_id'], 'exist', 'skipOnError' => true, 'targetClass' => HorarioAsignaturaPeriodo::className(), 'targetAttribute' => ['hape_id' => 'hape_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'rmar_id' => 'Rmar ID',
            'rmar_tipo' => 'Rmar Tipo',
            'pro_id' => 'Pro ID',
            'hape_id' => 'Hape ID',
            'rmar_fecha_hora_entrada' => 'Rmar Fecha Hora Entrada',
            'rmar_fecha_hora_salida' => 'Rmar Fecha Hora Salida',
            'rmar_direccion_ip' => 'Rmar Direccion Ip',
            'usu_id' => 'Usu ID',
            'rmar_estado' => 'Rmar Estado',
            'rmar_fecha_creacion' => 'Rmar Fecha Creacion',
            'rmar_fecha_modificacion' => 'Rmar Fecha Modificacion',
            'rmar_estado_logico' => 'Rmar Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro() {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHape() {
        return $this->hasOne(HorarioAsignaturaPeriodo::className(), ['hape_id' => 'hape_id']);
    }

    /**
     * Function consultarMateriasMarcabyPro
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>    
     * @property integer $userid
     * @return  
     */
    public function consultarMateriasMarcabyPro($per_id, $dia, $hape_fecha_clase, $onlyData = false) {
        $con = \Yii::$app->db_academico;
        if (!empty($hape_fecha_clase)) {
            $filtro = "(DATE_FORMAT(hap.hape_fecha_clase,'%Y-%m-%d') = :hape_fecha_clase or hap.hape_fecha_clase is null) AND ";
        } else {
            $filtro = "DATE_FORMAT(hap.hape_fecha_clase,'%Y-%m-%d') is null AND paca_fecha_fin > now() AND paca_fecha_inicio <= now() AND ";
        }

        $estado = 1;
        $sql = "
                    SELECT
                    hap.hape_id as id,
                    concat(hap.hape_hora_entrada, '-',hap.hape_hora_salida) as horario,
                    hap.dia_id as dia,
                    hap.uaca_id as unidad,
                    hap.mod_id as modalidad,
                    asig.asi_nombre as materia,
                    hap.pro_id as profesor,
                    ifnull(CONCAT(sem.saca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),sem.saca_anio) as periodo,
                    -- ifnull(CONCAT(paca.paca_anio_academico,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),paca.paca_anio_academico) as periodo,
                    ifnull((SELECT DATE_FORMAT(marc.rmar_fecha_hora_entrada, '%H:%i:%s')
                            FROM db_academico.registro_marcacion marc
                            WHERE marc.pro_id = prof.pro_id 
                            AND marc.hape_id = hap.hape_id 
                            -- AND marc.rmar_tipo = 'E'
                            AND SUBSTRING(marc.rmar_fecha_hora_entrada,1,10) = DATE_FORMAT(now(), '%Y-%m-%d')),'') as inicio,
                    ifnull((SELECT DATE_FORMAT(marc.rmar_fecha_hora_salida, '%H:%i:%s')
                            FROM db_academico.registro_marcacion marc
                            WHERE marc.pro_id = prof.pro_id 
                            AND marc.hape_id = hap.hape_id 
                            AND SUBSTRING(marc.rmar_fecha_hora_salida,1,10) = DATE_FORMAT(now(), '%Y-%m-%d')),'') as salida  
                    FROM
                    " . $con->dbname . ".horario_asignatura_periodo hap
                    INNER JOIN " . $con->dbname . ".profesor prof ON prof.pro_id = hap.pro_id
                    INNER JOIN " . $con->dbname . ".asignatura asig ON asig.asi_id = hap.asi_id
                    INNER JOIN " . $con->dbname . ".periodo_academico paca ON paca.paca_id = hap.paca_id 
                    LEFT JOIN " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = paca.saca_id 
                    LEFT JOIN " . $con->dbname . ".bloque_academico blq ON blq.baca_id = paca.baca_id
                    WHERE
                    $filtro
                    hap.dia_id = :dia AND
                    prof.per_id = :per_id AND
                    hap.hape_estado = :estado AND
                    hap.hape_estado_logico = :estado AND
                    prof.pro_estado = :estado AND
                    prof.pro_estado_logico = :estado AND
                    asig.asi_estado = :estado AND
                    asig.asi_estado_logico = :estado  AND
                    paca.paca_activo = 'A'                    
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":dia", $dia, \PDO::PARAM_INT);
        // si parametro de fecha es !empty entonces se crea parametro $fecha
        //if (!empty($hape_fecha_clase)) {
        $fecha_registro = $hape_fecha_clase . ' 00:00:00';
        $comando->bindParam(':hape_fecha_clase', $hape_fecha_clase, \PDO::PARAM_STR); //antes estaba $fecha_registro
        //}
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
     * Function consultarMarcacionExiste
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  Consulta una marcacion.
     */
    public function consultarMarcacionExiste($hape_id, $profesor, $fecha, $rmar_tipo) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha_registro = "%" . $fecha . "%";
        if ($rmar_tipo == 'E') {
            $dato = 'rmar_fecha_hora_entrada';
        } else {
            $dato = 'rmar_fecha_hora_salida';
        }
        $sql = "
                    SELECT $dato as marcacion
                    FROM 
                        " . $con->dbname . ".registro_marcacion rem                    
                    WHERE
                        rem.hape_id= :hape_id AND
                        rem.pro_id= :profesor AND
                        date_format(rem.rmar_fecha_creacion, '%Y-%m-%d') = :fecha AND                        
                        rem.rmar_estado = :estado AND
                        rem.rmar_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":hape_id", $hape_id, \PDO::PARAM_INT);
        $comando->bindParam(":profesor", $profesor, \PDO::PARAM_INT);
        $comando->bindParam(":fecha", $fecha, \PDO::PARAM_STR);
        $comando->bindParam(":rmar_tipo", $rmar_tipo, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarMarcacion crea marcacion.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarMarcacion($rmar_tipo, $pro_id, $hape_id, $rmar_fecha_hora_entrada, $rmar_fecha_hora_salida, $rmar_direccion_ip, $usu_id, $rmar_idingreso) {
        $con = \Yii::$app->db_academico;
        $rmar_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "rmar_estado";
        $bdet_sql = "1";

        $param_sql .= ", rmar_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($rmar_tipo)) {
            $param_sql .= ", rmar_tipo";
            $bdet_sql .= ", :rmar_tipo";
        }
        if (isset($pro_id)) {
            $param_sql .= ", pro_id";
            $bdet_sql .= ", :pro_id";
        }
        if (isset($hape_id)) {
            $param_sql .= ", hape_id";
            $bdet_sql .= ", :hape_id";
        }
        if (isset($rmar_fecha_hora_entrada)) {
            $param_sql .= ", rmar_fecha_hora_entrada";
            $bdet_sql .= ", :rmar_fecha_hora_entrada";
        }
        if (isset($rmar_fecha_hora_salida)) {
            $param_sql .= ", rmar_fecha_hora_salida";
            $bdet_sql .= ", :rmar_fecha_hora_salida";
        }
        if (isset($rmar_direccion_ip)) {
            $param_sql .= ", rmar_direccion_ip";
            $bdet_sql .= ", TO_BASE64(:rmar_direccion_ip)";
        }
        if (isset($usu_id)) {
            $param_sql .= ", usu_id";
            $bdet_sql .= ", :usu_id";
        }
        if (isset($rmar_fecha_creacion)) {
            $param_sql .= ", rmar_fecha_creacion";
            $bdet_sql .= ", :rmar_fecha_creacion";
        }
        if (isset($rmar_idingreso)) {
            $param_sql .= ", rmar_idingreso";
            $bdet_sql .= ", :rmar_idingreso";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".registro_marcacion ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($rmar_tipo)) {
                $comando->bindParam(':rmar_tipo', $rmar_tipo, \PDO::PARAM_STR);
            }
            if (isset($pro_id)) {
                $comando->bindParam(':pro_id', $pro_id, \PDO::PARAM_INT);
            }
            if (isset($hape_id)) {
                $comando->bindParam(':hape_id', $hape_id, \PDO::PARAM_INT);
            }
            if (isset($rmar_fecha_hora_entrada)) {
                $comando->bindParam(':rmar_fecha_hora_entrada', $rmar_fecha_hora_entrada, \PDO::PARAM_STR);
            }
            if (isset($rmar_fecha_hora_salida)) {
                $comando->bindParam(':rmar_fecha_hora_salida', $rmar_fecha_hora_salida, \PDO::PARAM_STR);
            }
            if (isset($rmar_direccion_ip)) {
                $comando->bindParam(':rmar_direccion_ip', $rmar_direccion_ip, \PDO::PARAM_STR);
            }
            if (!empty((isset($usu_id)))) {
                $comando->bindParam(':usu_id', $usu_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($rmar_fecha_creacion)))) {
                $comando->bindParam(':rmar_fecha_creacion', $rmar_fecha_creacion, \PDO::PARAM_STR);
            }
            if (!empty((isset($rmar_idingreso)))) {
                $comando->bindParam(':rmar_idingreso', $rmar_idingreso, \PDO::PARAM_INT);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.registro_marcacion');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarRegistroMarcacion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>    
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>    
     * @property  
     * @return  
     */
    public function consultarRegistroMarcacion($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :profesor OR ";
            $str_search .= "per.per_seg_nombre like :profesor OR ";
            $str_search .= "per.per_pri_apellido like :profesor OR ";
            $str_search .= "per.per_seg_nombre like :profesor )  AND ";
            $str_search .= "asig.asi_nombre like :materia  AND ";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "r.rmtm_fecha_transaccion >= :fec_ini AND ";
                $str_search .= "r.rmtm_fecha_transaccion <= :fec_fin AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= " h.paca_id = :periodo AND ";
            }
            if ($arrFiltro['estado'] != "0") {
                $str_search .= " ifnull(m.rmar_tipo,'N') = :estadoM AND ";
            }
        }
        if ($onlyData == false) {
            $periodoacademico = 'h.paca_id as periodo, ';
            $grupoperi = ',periodo';
        }
        $sql = "SELECT $periodoacademico
                ifnull(CONCAT(sem.saca_anio, ' (',blq.baca_nombre,'-',sem.saca_nombre, ')'),sem.saca_anio) as desc_periodo,
                CONCAT(ifnull(per.per_pri_nombre,' '), ' ', ifnull(per.per_pri_apellido,' ')) as nombres,
		asig.asi_nombre as materia,
                moda.mod_nombre as modalidad,
                date_format(r.rmtm_fecha_transaccion,'%Y-%m-%d') as fecha,
                h.hape_hora_entrada as inicio_esperado,
                h.hape_hora_salida as salida_esperada, 
                ifnull(date_format(m.rmar_fecha_hora_entrada, '%H:%i:%s'),'') hora_inicio,                 
                ifnull(date_format(m.rmar_fecha_hora_salida, '%H:%i:%s'),'') hora_salida,                
                SEC_TO_TIME(TIMESTAMPDIFF(SECOND, m.rmar_fecha_hora_entrada, m.rmar_fecha_hora_salida)) horasMarcadas,
                ifnull(FROM_BASE64(m.rmar_direccion_ip),'') as ip,
                ifnull(FROM_BASE64(m.rmar_direccion_ipsalida),'') as ip_salida,
                case when (ifnull(m.rmar_tipo, 'N')) = 'N' then 'Sin Marcar'
                     when (ifnull(m.rmar_tipo, 'N')) = 'E' then 'Sin Salida'
                     else 'Marcada' end as tipo                
        FROM " . $con->dbname . ".registro_marcacion_generada r left join db_academico.registro_marcacion m
                on (r.hape_id = m.hape_id and date_format(r.rmtm_fecha_transaccion,'%Y-%m-%d') = date_format(m.rmar_fecha_hora_entrada,'%Y-%m-%d'))
                INNER JOIN " . $con->dbname . ".horario_asignatura_periodo h on h.hape_id = r.hape_id
                INNER JOIN " . $con->dbname . ".asignatura asig on asig.asi_id = h.asi_id
                INNER JOIN " . $con->dbname . ".profesor profe on profe.pro_id = h.pro_id
                INNER JOIN " . $con1->dbname . ".persona per on per.per_id = profe.per_id
                INNER JOIN " . $con->dbname . ".periodo_academico peri on peri.paca_id = h.paca_id
                INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id = h.mod_id
                LEFT JOIN " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = peri.saca_id
                LEFT JOIN " . $con->dbname . ".bloque_academico blq ON blq.baca_id = peri.baca_id
        WHERE $str_search              
              ((date_format(r.rmtm_fecha_transaccion, '%Y-%m-%d') <= date_format(curdate(),'%Y-%m-%d')
                  and date_format(r.rmtm_fecha_transaccion, '%Y-%m-%d') between peri.paca_fecha_inicio and peri.paca_fecha_fin))
        ORDER BY r.rmtm_fecha_transaccion, h.hape_hora_entrada asc";

        $comando = $con->createCommand($sql);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["profesor"] . "%";
            $comando->bindParam(":profesor", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $materia = "%" . $arrFiltro["materia"] . "%";
            $comando->bindParam(":materia", $materia, \PDO::PARAM_STR);

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estado'] != "0") {
                $estadoM = $arrFiltro["estado"];
                $comando->bindParam(":estadoM", $estadoM, \PDO::PARAM_STR);
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
     * Function consultarFechaDistancia
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  Consulta una marcacion.
     */
    public function consultarFechaDistancia($hape_fecha_clase, $per_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha_registro = $hape_fecha_clase . " 00:00:00";
        $sql = "
                    SELECT
                        COUNT(*) AS existe_distancia
                    FROM 
                        " . $con->dbname . ".horario_asignatura_periodo hap
                        INNER JOIN " . $con->dbname . ".profesor prof ON prof.pro_id = hap.pro_id    
                    WHERE
                        date_format(hap.hape_fecha_clase, '%Y-%m-%d') = date_format(:fecha,'%Y-%m-%d') AND
                        prof.per_id = :per_id AND
                        ((hap.uaca_id = 1 && hap.mod_id = 4) OR  (hap.uaca_id = 2) OR (hap.uaca_id = 1 && hap.mod_id = 1) or (hap.uaca_id = 1 && hap.mod_id = 3)) AND
                        hap.hape_estado = :estado AND
                        hap.hape_estado_logico = :estado  AND
                        prof.pro_estado = :estado AND
                        prof.pro_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":fecha", $hape_fecha_clase, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarmIdMarcacion
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  Consulta el id de la marcacion de entrada para guardar en la marcacion de salida.
     */
    public function consultarmIdMarcacion($rmar_tipo, $pro_id, $hape_id, $fecha) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "
                    SELECT
                        rmar_id
                    FROM 
                        " . $con->dbname . ".registro_marcacion rma
                        
                    WHERE
                        rma.rmar_tipo = :rmar_tipo AND 
                        rma.pro_id = :pro_id AND
                        rma.hape_id = :hape_id AND
                        DATE_FORMAT(rma.rmar_fecha_creacion, '%Y-%m-%d') = :fecha AND
                        rma.rmar_estado = :estado AND
                        rma.rmar_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":rmar_tipo", $rmar_tipo, \PDO::PARAM_STR);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $comando->bindParam(":hape_id", $hape_id, \PDO::PARAM_INT);
        $comando->bindParam(":fecha", $fecha, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();       
        return $resultData;
    }

    /**
     * Function carga archivo csv o xls, xlsx a base de datos de los horarios.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function CargarArchivoHorario($periodo_id, $fname, $usu_id) {
        $mod_horarioTemp = new HorarioAsignaturaPeriodoTmp();
        $mod_horario = new HorarioAsignaturaPeriodo();
        $path = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "horario/" . $fname;
        \app\models\Utilities::putMessageLogFile('antes de uploadFile');
        $carga_archivo = $mod_horarioTemp->uploadFile($periodo_id, $usu_id, $path);
        if ($carga_archivo['status']) {
            \app\models\Utilities::putMessageLogFile('después de validación');
            $data = $mod_horarioTemp->consultarHorarioTemp($usu_id);
            $cont = 0;
            for ($i = 0; $i < sizeof($data); $i++) {
                if (!empty($data[$i]["hapt_fecha_clase"])) {
                    $fecha = $data[$i]["hapt_fecha_clase"];
                }
                $resultado = $mod_horario->insertarHorario($data[$i]["asi_id"], $data[$i]["paca_id"], $data[$i]["pro_id"], $data[$i]["uaca_id"], $data[$i]["mod_id"], $data[$i]["dia_id"], $data[$i]["hapt_fecha_clase"], $data[$i]["hapt_hora_entrada"], $data[$i]["hapt_hora_salida"]);
                //Modificar estado de la oportunidad.                                        
                $cont++;
            }
            \app\models\Utilities::putMessageLogFile('al finalizar carga de archivo horario');
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = "Se ha procesado $cont registros.";
            $arroout["data"] = null;
            return $arroout;
        } else {
            $arroout["status"] = FALSE;
            $arroout["error"] = null;
            $arroout["message"] = $carga_archivo['message'];
            $arroout["data"] = null;
            return $arroout;
        }
    }

    /**
     * Function consultarHorarioMarcacion
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>    
     * @property  
     * @return  
     */
    public function consultarHorarioMarcacion($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_general;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['profesor'] != "") {
                $str_search .= "(p.per_pri_nombre like :profesor OR ";
                $str_search .= "p.per_seg_nombre like :profesor OR ";
                $str_search .= "p.per_pri_apellido like :profesor OR ";
                $str_search .= "p.per_seg_apellido like :profesor OR ";
                $str_search .= "p.per_cedula like :profesor ) AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= " hap.paca_id = :periodo AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= " hap.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= " hap.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['modalidad'] > 0) {
                if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                    $str_search .= "hap.hape_fecha_clase >= :fec_ini AND ";
                    $str_search .= "hap.hape_fecha_clase <= :fec_fin AND ";
                }
            }
        }
        $sql = "SELECT 	concat(p.per_pri_nombre,' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as profesor,  
                        case when (pera.saca_id > 0) and (pera.baca_id > 0) then 
                           ifnull(CONCAT(sem.saca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),sem.saca_anio)
                            else sem.saca_anio end as periodo,
                        a.asi_descripcion as materia, ua.uaca_descripcion as unidad, 
                        m.mod_descripcion as modalidad, ifnull(hap.hape_fecha_clase,'N/A') as fecha_clase, 
                        d.dia_descripcion, hap.hape_hora_entrada, hap.hape_hora_salida
                FROM    " . $con->dbname . ".horario_asignatura_periodo hap
                        inner join " . $con->dbname . ".profesor pr on pr.pro_id= hap.pro_id
                        inner join " . $con->dbname . ".asignatura a on a.asi_id = hap.asi_id
                        inner join " . $con1->dbname . ".persona p on p.per_id = pr.per_id
                        inner join " . $con->dbname . ".unidad_academica ua on ua.uaca_id = hap.uaca_id
                        inner join " . $con->dbname . ".modalidad m on m.mod_id = hap.mod_id
                        inner join " . $con2->dbname . ".dia d on d.dia_id = hap.dia_id
                        inner join " . $con->dbname . ".periodo_academico pera on pera.paca_id = hap.paca_id
                        left join " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = pera.saca_id
                        left join " . $con->dbname . ".bloque_academico blq ON blq.baca_id = pera.baca_id
                WHERE   $str_search
                        hap.hape_estado = :estado
                        and hap.hape_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['profesor'] != "") {
                $search_cond = "%" . $arrFiltro["profesor"] . "%";
                $comando->bindParam(":profesor", $search_cond, \PDO::PARAM_STR);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
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
     * Function consultarRegistroNoMarcacion
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>    
     * @property  
     * @return  
     */
    public function consultarRegistroNoMarcacion($arrFiltro = array(), $parametros, $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        if ($parametros == '0') {
            $str_search = " hap.uaca_id = 0 AND hap.mod_id = 0 AND ";
            $tipo = "E";
        } else {
            if (isset($arrFiltro) && count($arrFiltro) > 0) {
                $str_search .= "(per.per_pri_nombre like :profesor OR ";
                $str_search .= "per.per_seg_nombre like :profesor OR ";
                $str_search .= "per.per_pri_apellido like :profesor OR ";
                $str_search .= "per.per_seg_nombre like :profesor )  AND ";
                $str_search .= "a.asi_nombre like :materia  AND ";
                if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                    $str_search .= " hap.uaca_id = :unidad AND ";
                }
                if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                    $str_search .= " hap.mod_id = :modalidad AND ";
                }
                if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                    $str_search .= "rmg.rmtm_fecha_transaccion >= :fec_ini AND ";
                    $str_search .= "rmg.rmtm_fecha_transaccion <= :fec_fin AND ";
                }
                if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                    $str_search .= " rmg.paca_id = :periodo AND ";
                }
            }
        }
        $sql = "SELECT ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) as periodo,
                       date_format(rmg.rmtm_fecha_transaccion,'%Y-%m-%d') as fecha, concat(per.per_pri_nombre, ' ', per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,' ')) profesor,
                       a.asi_nombre as materia, hap.hape_hora_entrada as hora_inicio, hap.hape_hora_salida as hora_salida
                FROM " . $con->dbname . ".registro_marcacion_generada rmg
                     inner join " . $con->dbname . ".horario_asignatura_periodo hap on rmg.hape_id = hap.hape_id
                     inner join " . $con->dbname . ".profesor p on p.pro_id = hap.pro_id
                     inner join " . $con1->dbname . ".persona per on per.per_id = p.per_id
                     inner join " . $con->dbname . ".asignatura a on a.asi_id = hap.asi_id
                     inner join " . $con->dbname . ".periodo_academico pa on pa.paca_id = rmg.paca_id
                     left join " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = pa.saca_id
                     left join " . $con->dbname . ".bloque_academico blq ON blq.baca_id = pa.baca_id
                WHERE $str_search
                       hap.hape_estado = :estado
                       and hap.hape_estado_logico = :estado
                       and date_format(rmg.rmtm_fecha_transaccion, '%Y-%m-%d') <= date_format(curdate(),'%Y-%m-%d')
                       and not exists(select null from " . $con->dbname . ".registro_marcacion rm 
                                      where rm.hape_id = rmg.hape_id
                                            and date_format(rmg.rmtm_fecha_transaccion,'%Y-%m-%d') = date_format(rm.rmar_fecha_creacion,'%Y-%m-%d')
                                            and rmar_tipo = :tipo)
                ORDER BY rmg.rmtm_fecha_transaccion";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["profesor"] . "%";
            $comando->bindParam(":profesor", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $materia = "%" . $arrFiltro["materia"] . "%";
            $comando->bindParam(":materia", $materia, \PDO::PARAM_STR);
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
            }
            if (($arrFiltro["tipo"]) != "") {
                $comando->bindParam(":tipo", $arrFiltro["tipo"], \PDO::PARAM_STR);
            } else {
                $comando->bindParam(":tipo", $tipo, \PDO::PARAM_STR);
            }
        } else {
            $comando->bindParam(":tipo", $tipo, \PDO::PARAM_STR);
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
     * Function insertarMarcacion crea marcacion.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarMarcacionsalida($rmar_tipo, $pro_id, $hape_id, $rmar_fecha_hora_salida, $rmar_direccion_ipsalida, $rmar_idingreso) {        
        $con = \Yii::$app->db_academico;
        $rmar_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".registro_marcacion		       
                      SET rmar_tipo = :rmar_tipo,
                        rmar_fecha_hora_salida = :rmar_fecha_hora_salida,
                        rmar_fecha_modificacion = :rmar_fecha_modificacion,
                        rmar_direccion_ipsalida = TO_BASE64(:rmar_direccion_ipsalida)
                      WHERE 
                        rmar_id = :rmar_idingreso AND                        
                        rmar_tipo = 'E' AND
                        hape_id = :hape_id AND
                        pro_id = :pro_id AND
                        rmar_estado = :estado AND
                        rmar_estado_logico = :estado");

            if (isset($rmar_tipo)) {
                $comando->bindParam(':rmar_tipo', $rmar_tipo, \PDO::PARAM_STR);
            }
            if (isset($pro_id)) {
                $comando->bindParam(':pro_id', $pro_id, \PDO::PARAM_INT);
            }
            if (isset($hape_id)) {
                $comando->bindParam(':hape_id', $hape_id, \PDO::PARAM_INT);
            }
            if (isset($rmar_fecha_hora_salida)) {
                $comando->bindParam(':rmar_fecha_hora_salida', $rmar_fecha_hora_salida, \PDO::PARAM_STR);
            }
            if (isset($rmar_direccion_ipsalida)) {
                $comando->bindParam(':rmar_direccion_ipsalida', $rmar_direccion_ipsalida, \PDO::PARAM_STR);
            }
            if (!empty((isset($rmar_fecha_modificacion)))) {
                $comando->bindParam(':rmar_fecha_modificacion', $rmar_fecha_modificacion, \PDO::PARAM_STR);
            }
            if (!empty((isset($rmar_idingreso)))) {
                $comando->bindParam(':rmar_idingreso', $rmar_idingreso, \PDO::PARAM_INT);
            }
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.registro_marcacion');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function insertarMarcacionid.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarMarcacionid($resp_marca) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".registro_marcacion		       
                      SET rmar_idingreso = :resp_marca                        
                      WHERE                     
                        rmar_id = :resp_marca AND
                        rmar_estado = :estado AND
                        rmar_estado_logico = :estado");
            $comando->bindParam(':resp_marca', $resp_marca, \PDO::PARAM_INT);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.registro_marcacion');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

}
