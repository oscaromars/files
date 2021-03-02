<?php

namespace app\modules\financiero\models;

use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "pagos_contrato_programa".
 *
 * @property int $pcpr_id
 * @property int $adm_id
 * @property int $cemp_id
 * @property string $pcpr_archivo
 * @property int $pcpr_usu_ingreso
 * @property int $pcpr_usu_modifica
 * @property string $pcpr_estado
 * @property string $pcpr_fecha_creacion
 * @property string $pcpr_fecha_modificacion
 * @property string $pcpr_estado_logico
 */
class PagosContratoPrograma extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'pagos_contrato_programa';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pcpr_id', 'adm_id', 'pcpr_archivo', 'pcpr_usu_ingreso', 'pcpr_estado', 'pcpr_estado_logico'], 'required'],
            [['pcpr_id', 'adm_id', 'cemp_id', 'pcpr_usu_ingreso', 'pcpr_usu_modifica'], 'integer'],
            [['pcpr_fecha_creacion', 'pcpr_fecha_modificacion'], 'safe'],
            [['pcpr_archivo'], 'string', 'max' => 100],
            [['pcpr_estado', 'pcpr_estado_logico'], 'string', 'max' => 1],
            [['pcpr_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pcpr_id' => 'Pcpr ID',
            'adm_id' => 'Adm ID',
            'cemp_id' => 'Cemp ID',
            'pcpr_archivo' => 'Pcpr Archivo',
            'pcpr_usu_ingreso' => 'Pcpr Usu Ingreso',
            'pcpr_usu_modifica' => 'Pcpr Usu Modifica',
            'pcpr_estado' => 'Pcpr Estado',
            'pcpr_fecha_creacion' => 'Pcpr Fecha Creacion',
            'pcpr_fecha_modificacion' => 'Pcpr Fecha Modificacion',
            'pcpr_estado_logico' => 'Pcpr Estado Logico',
        ];
    }

    /**
     * Function consultarDatos pago contrato
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos del admitido).
     */
    public function consultarDatosadmitido($adm_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_asgard;
        $con3 = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT * FROM (
                    SELECT distinct lpad(ifnull(sins.num_solicitud, sins.sins_id),9,'0') as solicitud,
                    sins.sins_id,
                    sins.int_id,
                    per.per_id as per_id,
                    sins.eaca_id,
                    sins.mest_id,
                    sins.mod_id,
                    moda.mod_nombre,
                    uaca.uaca_nombre,
                    sins.uaca_id,
                    case when (ifnull(sins.eaca_id,0)=0) then
                    (select mest_nombre from " . $con->dbname . ".modulo_estudio me where me.mest_id = sins.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                    else
                    (select eaca_nombre from " . $con->dbname . ".estudio_academico ea where ea.eaca_id = sins.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                    end as carrera,
                    per.per_pri_nombre as per_pri_nombre,
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,
                    per.per_cedula,
                    admi.adm_id,
                    sins.emp_id
                    FROM " . $con1->dbname . ".admitido admi INNER JOIN db_captacion.solicitud_inscripcion sins on sins.sins_id = admi.sins_id
                    INNER JOIN " . $con1->dbname . ".interesado inte on sins.int_id = inte.int_id
                    INNER JOIN " . $con2->dbname . ".persona per on inte.per_id = per.per_id
                    INNER JOIN " . $con->dbname . ".modalidad moda on moda.mod_id=sins.mod_id
                    INNER JOIN " . $con->dbname . ".unidad_academica uaca on uaca.uaca_id=sins.uaca_id
                    INNER JOIN " . $con3->dbname . ".orden_pago opag on opag.sins_id = sins.sins_id
                    WHERE
                    admi.adm_estado_logico = :estado AND
                    admi.adm_estado = :estado AND
                    inte.int_estado_logico = :estado AND
                    inte.int_estado = :estado AND
                    per.per_estado_logico = :estado AND
                    per.per_estado = :estado AND
                    sins.sins_estado = :estado AND
                    sins.sins_estado_logico = :estado AND
                    opag.opag_estado = :estado AND
                    opag.opag_estado_logico = :estado) a
                    WHERE a.uaca_id = '2' AND 
                    a.adm_id = :adm_id AND
                    a.sins_id = a.sins_id";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":adm_id", $adm_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertar datos pago contrato
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function insertarcontrato($con, $data) {
        $estado = '1';

        $sql = "INSERT INTO " . $con->dbname . ".pagos_contrato_programa
            (adm_id,cemp_id,pcpr_archivo,pcpr_usu_ingreso,pcpr_estado,pcpr_estado_logico) VALUES
            (:adm_id,:cemp_id,:pcpr_archivo,:pcpr_usu_ingreso,:pcpr_estado,:pcpr_estado)";
        $command = $con->createCommand($sql);
        $command->bindParam(":adm_id", $data['adm_id'], \PDO::PARAM_INT);
        $command->bindParam(":cemp_id", $data['cemp_id'], \PDO::PARAM_INT);
        $command->bindParam(":pcpr_archivo", $data['pcpr_archivo'], \PDO::PARAM_STR);
        $command->bindParam(":pcpr_usu_ingreso", $data['pcpr_usu_ingreso'], \PDO::PARAM_INT);
        $command->bindParam(":pcpr_estado", $estado, \PDO::PARAM_STR);
        $command->execute();
        $idtable = $con->getLastInsertID($con->dbname . '.pagos_contrato_programa');
        return $idtable;
    }

    /**
     * Function getMatriculadoPosgrado
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (información del matriculado a posgrado)
     */
    public static function consultarMatriculaPosgrado($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_facturacion;
        $estado = 1;
        $columnsAdd = "";
        $estado_opago = "S";
        $arrFiltro['unidad'] = "2";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search = "(a.per_pri_nombre like :search OR ";
                $str_search .= "a.per_seg_nombre like :search OR ";
                $str_search .= "a.per_pri_apellido like :search OR ";
                $str_search .= "a.per_cedula like :search) AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "a.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "a.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $str_search .= "a.eaca_id = :carrera AND ";
            }
        } else {
            $columnsAdd = "sins.sins_id as solicitud_id,
                    per.per_id as persona, 
                    per.per_pri_nombre as per_pri_nombre, 
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,";
        }
        $sql = "SELECT * FROM (
                SELECT  distinct lpad(ifnull(sins.num_solicitud, sins.sins_id),9,'0') as solicitud,
                        sins.sins_id,
                        sins.int_id,
                        SUBSTRING(sins.sins_fecha_solicitud,1,10) as sins_fecha_solicitud, 
                        per.per_id as per_id,
                        per.per_cedula as per_dni,
                        per.per_pri_nombre as per_nombres,
                        per.per_pri_apellido as per_apellidos,
                        sins.ming_id, 
                        ifnull((select min.ming_alias from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as abr_metodo,
                        ifnull((select min.ming_nombre from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as ming_nombre,
                        sins.eaca_id,
                        sins.mest_id,
                        sins.mod_id,
                        moda.mod_nombre,
                        uaca.uaca_nombre,
                        sins.uaca_id,
                        case when (ifnull(sins.eaca_id,0)=0) then
                                (select mest_nombre from " . $con3->dbname . ".modulo_estudio me where me.mest_id = sins.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                                else
                            (select eaca_nombre from " . $con3->dbname . ".estudio_academico ea where ea.eaca_id = sins.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                        end as carrera,
                        per.per_pri_nombre as per_pri_nombre, 
                        per.per_seg_nombre as per_seg_nombre,
                        per.per_pri_apellido as per_pri_apellido,
                        per.per_seg_apellido as per_seg_apellido,   
                        per.per_cedula,
                        admi.adm_id,                                               
                       (case when sins_beca = 1 then 'ICF' else 'No Aplica' end) as beca,                       
                        sins.emp_id,
                        (select count(*) from " . $con1->dbname . ".pagos_contrato_programa pcp where pcp.adm_id = admi.adm_id and pcp.pcpr_estado = :estado and pcp.pcpr_estado_logico = :estado) as documento,
                        ifnull((select pcp.pcpr_archivo from " . $con1->dbname . ".pagos_contrato_programa pcp where pcp.adm_id = admi.adm_id),' ') as ruta_contrato                            
                   FROM " . $con->dbname . ".admitido admi INNER JOIN " . $con->dbname . ".solicitud_inscripcion sins on sins.sins_id = admi.sins_id                 
                     INNER JOIN " . $con->dbname . ".interesado inte on sins.int_id = inte.int_id 
                     INNER JOIN " . $con2->dbname . ".persona per on inte.per_id = per.per_id                     
                     INNER JOIN " . $con3->dbname . ".modalidad moda on moda.mod_id=sins.mod_id
                     INNER JOIN " . $con3->dbname . ".unidad_academica uaca on uaca.uaca_id=sins.uaca_id
                     INNER JOIN " . $con1->dbname . ".orden_pago opag on opag.sins_id = sins.sins_id    
                WHERE                          
                       sins.rsin_id = 2 AND
                       opag.opag_estado_pago = :estado_opago AND
                       admi.adm_estado_logico = :estado AND
                       admi.adm_estado = :estado AND 
                       inte.int_estado_logico = :estado AND
                       inte.int_estado = :estado AND     
                       per.per_estado_logico = :estado AND
                       per.per_estado = :estado AND
                       sins.sins_estado = :estado AND
                       sins.sins_estado_logico = :estado  AND
                       opag.opag_estado = :estado AND
                       opag.opag_estado_logico = :estado                  
                ORDER BY SUBSTRING(sins.sins_fecha_solicitud,1,10) desc) a
                WHERE $str_search  
                      a.sins_id = a.sins_id";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_opago", $estado_opago, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            }
            $unidad = $arrFiltro["unidad"];
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            $modalidad = $arrFiltro["modalidad"];
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            $carrera = $arrFiltro["carrera"];
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $comando->bindParam(":carrera", $carrera, \PDO::PARAM_INT);
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
                    'per_dni',
                    'per_nombres',
                    'per_apellidos',
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

}
