<?php

namespace app\modules\financiero\models;

use yii\data\ArrayDataProvider;
use Yii;
use app\models\Utilities;

/**
 * This is the model class for table "pagos_factura_estudiante".
 *
 * @property int $pfes_id
 * @property int $est_id
 * @property string $pfes_concepto
 * @property int|null $ban_id
 * @property string|null $pfes_referencia
 * @property int $fpag_id
 * @property float $pfes_valor_pago
 * @property string|null $pfes_fecha_pago
 * @property string|null $pfes_observacion
 * @property string $pfes_archivo_pago
 * @property string|null $pfes_fecha_registro
 * @property int $pfes_usu_ingreso
 * @property string $pfes_estado
 * @property string $pfes_fecha_creacion
 * @property string|null $pfes_fecha_modificacion
 * @property string $pfes_estado_logico
 *
 * @property Cruce[] $cruces
 * @property FormaPago $fpag
 */
class PagosFacturaEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagos_factura_estudiante';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['est_id', 'pfes_concepto', 'fpag_id', 'pfes_valor_pago', 'pfes_archivo_pago', 'pfes_usu_ingreso', 'pfes_estado', 'pfes_estado_logico'], 'required'],
            [['est_id', 'ban_id', 'fpag_id', 'pfes_usu_ingreso'], 'integer'],
            [['pfes_valor_pago'], 'number'],
            [['pfes_fecha_pago', 'pfes_fecha_registro', 'pfes_fecha_creacion', 'pfes_fecha_modificacion'], 'safe'],
            [['pfes_concepto'], 'string', 'max' => 3],
            [['pfes_referencia'], 'string', 'max' => 50],
            [['pfes_observacion'], 'string', 'max' => 500],
            [['pfes_archivo_pago'], 'string', 'max' => 200],
            [['pfes_estado', 'pfes_estado_logico'], 'string', 'max' => 1],
            [['fpag_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['fpag_id' => 'fpag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pfes_id' => 'Pfes ID',
            'est_id' => 'Est ID',
            'pfes_concepto' => 'Pfes Concepto',
            'ban_id' => 'Ban ID',
            'pfes_referencia' => 'Pfes Referencia',
            'fpag_id' => 'Fpag ID',
            'pfes_valor_pago' => 'Pfes Valor Pago',
            'pfes_fecha_pago' => 'Pfes Fecha Pago',
            'pfes_observacion' => 'Pfes Observacion',
            'pfes_archivo_pago' => 'Pfes Archivo Pago',
            'pfes_fecha_registro' => 'Pfes Fecha Registro',
            'pfes_usu_ingreso' => 'Pfes Usu Ingreso',
            'pfes_estado' => 'Pfes Estado',
            'pfes_fecha_creacion' => 'Pfes Fecha Creacion',
            'pfes_fecha_modificacion' => 'Pfes Fecha Modificacion',
            'pfes_estado_logico' => 'Pfes Estado Logico',
        ];
    }

    /**
     * Gets query for [[Cruces]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCruces()
    {
        return $this->hasMany(Cruce::className(), ['pfes_id' => 'pfes_id']);
    }

    /**
     * Gets query for [[Fpag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFpag()
    {
        return $this->hasOne(FormaPago::className(), ['fpag_id' => 'fpag_id']);
    }

    /**
     * Function getPagos
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public static function getPagos($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "(p.per_pri_nombre like :estudiante OR ";
                $str_search .= "p.per_pri_apellido like :estudiante OR ";
                $str_search .= "p.per_cedula like :estudiante )  AND ";
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " pfe.pfes_fecha_registro BETWEEN :fec_ini AND :fec_fin AND ";
            }
            if ($arrFiltro['unidad'] > 0) {
                $str_search .= "u.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] > 0) {
                $str_search .= "mo.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['estadopago'] > 0) { // estado de revision
                $str_search .= "d.dpfa_estado_pago = :estadopago AND ";
            }
            if ($arrFiltro['estadofinanciero'] != '0') { // estado financiero
                if ($arrFiltro['estadofinanciero'] == 'N') {
                    $str_search .= "( d.dpfa_estado_financiero IS NULL OR d.dpfa_estado_financiero = :estadofinanciero) AND ";
                } else {
                    $str_search .= "d.dpfa_estado_financiero = :estadofinanciero AND "; // son los pendientes no estan en la tabla
                }
            }  
            if ($arrFiltro['concepto'] != '0') { 
                $str_search .= "pfe.pfes_concepto = :concepto AND ";
            }         
        }
        if ($onlyData == false) {
            $fpag_id = "f.fpag_id, ";
        } 
        $sql = "SELECT 
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        u.uaca_nombre as unidad,
                        mo.mod_nombre as modalidad,
                        ea.eaca_nombre as carrera,
                        f.fpag_nombre as forma_pago,
                        $fpag_id
                        d.dpfa_num_cuota,
                        d.dpfa_factura,
                        pfe.pfes_valor_pago valor_pago,
                        pfe.pfes_fecha_registro,
                        d.dpfa_valor_cuota as abono,";  

        $sql .= " 
                case d.dpfa_estado_pago  
                            when 1 then 'Pendiente'  
                            when 2 then 'Aprobado'                                
                            when 3 then 'Rechazado'   
                        end as estado_pago,                   
                case d.dpfa_estado_financiero  
                            when 'C' then 'Cancelado'  
                            when 'N' then 'Pendiente'                                                              
                            else 'Pendiente'
                        end as estado_financiero,
                dpfa_id,
                pfe.pfes_concepto
                from " . $con2->dbname . ".pagos_factura_estudiante pfe inner join " . $con2->dbname . ".detalle_pagos_factura d on d.pfes_id = pfe.pfes_id
                inner join " . $con->dbname . ".estudiante e on e.est_id = pfe.est_id
                inner join " . $con1->dbname . ".persona p on p.per_id = e.per_id
                inner join " . $con->dbname . ".estudiante_carrera_programa ec on ec.est_id = e.est_id
                inner join " . $con->dbname . ".modalidad_estudio_unidad m on m.meun_id = ec.meun_id
                inner join " . $con->dbname . ".unidad_academica u on u.uaca_id = m.uaca_id
                inner join " . $con->dbname . ".modalidad mo on mo.mod_id = m.mod_id
                inner join " . $con->dbname . ".estudio_academico ea on ea.eaca_id = m.eaca_id
                inner join " . $con2->dbname . ".forma_pago f on f.fpag_id = pfe.fpag_id 
                WHERE $str_search pfe.pfes_estado=:estado AND pfe.pfes_estado_logico=:estado  ORDER BY pfe.pfes_fecha_registro DESC ";

        $comando = $con->createCommand($sql);          
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $unidad = $arrFiltro['unidad'];
            $modalidad = $arrFiltro['modalidad'];
            $estadopago = $arrFiltro['estadopago'];
            $concepto = $arrFiltro['concepto'];
            $estadofinanciero = $arrFiltro['estadofinanciero'];
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['search'] != "") {
                $comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] > 0) {
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estadopago'] > 0) {
                $comando->bindParam(":estadopago", $estadopago, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estadofinanciero'] != '0') {
                $comando->bindParam(":estadofinanciero", $estadofinanciero, \PDO::PARAM_STR);
            }
            if ($arrFiltro['concepto'] != '0') {
                $comando->bindParam(":concepto", $concepto, \PDO::PARAM_STR);
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
                    'egen_id',
                    'fecha_creacion',
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
     * Function getPagospendientexest
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return 
     */
    public static function getPagospendientexest($cedula, $onlyData = false) {
        $con = \Yii::$app->db_sea;
        $sql = "SELECT    
                  (SELECT SUM(A.VALOR_D) FROM " . $con->dbname . ".CC0002 A
                    WHERE A.COD_CLI= :cedula AND A.CANCELA='N' AND A.COD_PTO='001' AND TIP_NOF='FE')  as total_deuda,
                  A.TIP_NOF,
                  A.NUM_NOF,
                  A.COD_CLI,
                  A.C_TRA_E,
                  A.NUM_DOC,
                  A.F_SUS_D,
                  A.F_VEN_D,
                  A.VALOR_D,
                  (A.VALOR_D-A.VALOR_C-A.VAL_DEV) SALDO,
                  CASE 
                    WHEN A.NUM_DOC = A.NUM_NOF THEN '01'                    
                    ELSE SUBSTRING(A.NUM_DOC,1,3)
                  END  as cuota,
                  (SELECT GROUP_CONCAT( NOM_ART) FROM pruebasea.VD010101 B WHERE A.TIP_NOF=B.TIP_NOF AND A.NUM_NOF=B.NUM_NOF AND A.COD_CLI=B.COD_CLI) MOTIVO,               
                  CASE 
                    WHEN A.NUM_DOC = A.NUM_NOF THEN '01'                    
                    ELSE SUBSTRING(A.NUM_DOC,-3)
                  END  as cantidad                  
                FROM " . $con->dbname . ".CC0002 A
                WHERE A.COD_CLI= :cedula AND A.CANCELA='N' AND A.COD_PTO='001' AND TIP_NOF='FE'";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);

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
     * Function consultarPago
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public function consultarPago($dpfa_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT 	p.per_id,
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        p.per_correo,
                        u.uaca_id,
                        mo.mod_id,
                        ea.eaca_nombre as carrera,
                        f.fpag_nombre as forma_pago,
                        d.dpfa_num_cuota,
                        d.dpfa_valor_cuota as valor_cuota,
                        d.dpfa_factura,
                        pfe.pfes_fecha_registro,   
                        pfe.pfes_valor_pago valor_pago,                        
                        case d.dpfa_estado_pago  
                            when 1 then 'Pendiente'  
                            when 2 then 'Aprobado'                                
                            when 3 then 'Rechazado'   
                        end as estado_pago,
                        dpfa_id,
                        pfes_archivo_pago as imagen,
                        pfe.pfes_referencia as referencia,
                        pfe.fpag_id as pago_id,
                        DATE_FORMAT(pfe.pfes_fecha_pago,'%Y-%m-%d') as fecha_pago,
                        pfe.pfes_observacion as observacion,
                        d.dpfa_descripcion_factura as descripcion_factura,
                        d.dpfa_observacion_rechazo,
                        pfe.pfes_id as cabecera_id,
                        d.dpfa_estado_pago as estado,
                        d.dpfa_observacion_rechazo as dpfa_observacion_rechazo,
                        d.dpfa_observacion_reverso,
                        b.ban_nombre ,
                        d.dpfa_valor_cuota as abono
                from " . $con2->dbname . ".pagos_factura_estudiante pfe inner join " . $con2->dbname . ".detalle_pagos_factura d on d.pfes_id = pfe.pfes_id
                    inner join " . $con->dbname . ".estudiante e on e.est_id = pfe.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = e.per_id
                    inner join " . $con->dbname . ".estudiante_carrera_programa ec on ec.est_id = e.est_id
                    inner join " . $con->dbname . ".modalidad_estudio_unidad m on m.meun_id = ec.meun_id
                    inner join " . $con->dbname . ".unidad_academica u on u.uaca_id = m.uaca_id
                    inner join " . $con->dbname . ".modalidad mo on mo.mod_id = m.mod_id
                    inner join " . $con->dbname . ".estudio_academico ea on ea.eaca_id = m.eaca_id
                    inner join " . $con2->dbname . ".forma_pago f on f.fpag_id = pfe.fpag_id   
                    left join " . $con2->dbname . ".bancos b on b.ban_id = pfe.ban_id                        
                where dpfa_id = :dpfa_id
                    and pfes_estado = :estado
                    and pfes_estado_logico = :estado
                    and dpfa_estado = :estado
                    and dpfa_estado_logico = :estado
                    and est_estado = :estado
                    and est_estado_logico = :estado
                    and per_estado = :estado
                    and per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dpfa_id", $dpfa_id, \PDO::PARAM_INT);
        return $comando->queryOne();
    }

    /**
     * Function grabarRechazo (Actualiza el estado del pago)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function grabarRechazo($dpfa_id, $resultado, $observacion) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fecha_rechazo = date(Yii::$app->params["dateTimeByDefault"]);
        $usuario_rechazo = @Yii::$app->user->identity->usu_id;

        if ($resultado==2) 
            $dpfa_estado_financiero = 'C';
        else
            $dpfa_estado_financiero = 'N';


        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".detalle_pagos_factura
                SET dpfa_observacion_rechazo = :observacion,
                    dpfa_fecha_aprueba_rechaza = :fecha_rechazo,                   
                    dpfa_estado_pago = :resultado,
                    dpfa_usu_aprueba_rechaza = :usuario_rechazo,
                    dpfa_fecha_modificacion = :fecha_rechazo,
                    dpfa_estado_financiero = :dpfa_estado_financiero
                WHERE dpfa_id = :dpfa_id AND 
                      dpfa_estado =:estado AND
                      dpfa_estado_logico = :estado");


        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dpfa_id", $dpfa_id, \PDO::PARAM_INT);
        $comando->bindParam(":observacion", $observacion, \PDO::PARAM_STR);
        $comando->bindParam(":resultado", $resultado, \PDO::PARAM_INT);
        $comando->bindParam(":fecha_rechazo", $fecha_rechazo, \PDO::PARAM_STR);
        $comando->bindParam(":usuario_rechazo", $usuario_rechazo, \PDO::PARAM_INT);
        $comando->bindParam(":dpfa_estado_financiero", $dpfa_estado_financiero, \PDO::PARAM_STR);
        $response = $comando->execute();
        return $response;
    }

    /**
     * Function insertarPagospendientes
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return pfes_id
     */
    public function insertarPagospendientes($est_id, $pfes_concepto, $pfes_referencia, $pfes_banco, $fpag_id, $pfes_valor_pago, $pfes_fecha_pago, $pfes_observacion, $pfes_archivo_pago, $pfes_usu_ingreso) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $param_sql = "pfes_estado";
        $bdet_sql = "1";

        $param_sql .= ", pfes_estado_logico";
        $bdet_sql .= ", 1";

        $param_sql .= ", pfes_fecha_registro";
        $bdet_sql .= ", :pfes_fecha_registro";

        if (isset($est_id)) {
            $param_sql .= ", est_id";
            $bdet_sql .= ", :est_id";
        }
        if (isset($pfes_concepto)) {
            $param_sql .= ", pfes_concepto";
            $bdet_sql .= ", :pfes_concepto";
        }
        if (isset($pfes_referencia)) {
            $param_sql .= ", pfes_referencia";
            $bdet_sql .= ", :pfes_referencia";
        }
        if (isset($pfes_banco)) {
            $param_sql .= ", ban_id";
            $bdet_sql .= ", :pfes_banco";
        }
        if (isset($fpag_id)) {
            $param_sql .= ", fpag_id";
            $bdet_sql .= ", :fpag_id";
        }
        if (isset($pfes_valor_pago)) {
            $param_sql .= ", pfes_valor_pago";
            $bdet_sql .= ", :pfes_valor_pago";
        }
        if (isset($pfes_fecha_pago)) {
            $param_sql .= ", pfes_fecha_pago";
            $bdet_sql .= ", :pfes_fecha_pago";
        }
        if (isset($pfes_observacion)) {
            $param_sql .= ", pfes_observacion";
            $bdet_sql .= ", :pfes_observacion";
        }
        if (isset($pfes_archivo_pago)) {
            $param_sql .= ", pfes_archivo_pago";
            $bdet_sql .= ", :pfes_archivo_pago";
        }
        if (isset($pfes_usu_ingreso)) {
            $param_sql .= ", pfes_usu_ingreso";
            $bdet_sql .= ", :pfes_usu_ingreso";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".pagos_factura_estudiante ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);
            if (isset($est_id)) {
                $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);
            }
            if (isset($pfes_pfes_conceptoreferencia)) {
                $comando->bindParam(':pfes_concepto', $pfes_concepto, \PDO::PARAM_STR);
            }
            if (isset($pfes_referencia)) {
                $comando->bindParam(':pfes_referencia', $pfes_referencia, \PDO::PARAM_STR);
            }
            if (isset($pfes_banco)) {
                $comando->bindParam(':pfes_banco', $pfes_banco, \PDO::PARAM_STR);
            }
            if (!empty((isset($fpag_id)))) {
                $comando->bindParam(':fpag_id', $fpag_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($pfes_valor_pago)))) {
                $comando->bindParam(':pfes_valor_pago', $pfes_valor_pago, \PDO::PARAM_STR);
            }
            if (!empty((isset($pfes_fecha_pago)))) {
                $comando->bindParam(':pfes_fecha_pago', $pfes_fecha_pago, \PDO::PARAM_STR);
            }
            if (!empty((isset($pfes_observacion)))) {
                $comando->bindParam(':pfes_observacion', ucfirst(mb_strtolower($pfes_observacion, 'UTF-8')), \PDO::PARAM_STR);
            }
            if (!empty((isset($pfes_archivo_pago)))) {
                $comando->bindParam(':pfes_archivo_pago', $pfes_archivo_pago, \PDO::PARAM_STR);
            }
            if (!empty((isset($pfes_usu_ingreso)))) {
                $comando->bindParam(':pfes_usu_ingreso', $pfes_usu_ingreso, \PDO::PARAM_INT);
            }
            $comando->bindParam(":pfes_fecha_registro", $fecha, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.pagos_factura_estudiante');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return $ex;
        }
    }

    /**
     * Function Consulta las cuotas seleccionada al cargar imagenes de facturas pendientes de pago.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarPagospendientesp($cedula, $factura, $cuota) {
        $con = \Yii::$app->db_sea;
        $sql = "SELECT 
                  A.TIP_NOF as tipofactura,
                  A.NUM_NOF as factura,
                  (SELECT GROUP_CONCAT( NOM_ART) FROM pruebasea.VD010101 B WHERE A.TIP_NOF=B.TIP_NOF AND A.NUM_NOF=B.NUM_NOF AND A.COD_CLI=B.COD_CLI) as descripcion,
                  SUM(A.VALOR_D) as valor,
                  A.F_SUS_D as fecha,
                  (A.VALOR_D-A.VALOR_C-A.VAL_DEV) as saldo,  
                  CASE 
                    WHEN A.NUM_DOC = A.NUM_NOF THEN ' '                    
                    /*ELSE SUBSTRING(A.NUM_DOC,1,3)*/
                    ELSE A.NUM_DOC
                  END  as numcuota,
                  -- A.VALOR_D as valorcuota,
                   CASE 
                    WHEN A.NUM_DOC = A.NUM_NOF THEN ' '                                        
                    ELSE A.VALOR_D
                  END  as valorcuota,
                  A.F_VEN_D as fechavence
                                                               
                FROM " . $con->dbname . ".CC0002 A
                WHERE A.COD_CLI= :cedula AND A.CANCELA='N' AND A.COD_PTO='001' AND TIP_NOF='FE' AND A.NUM_NOF = :factura AND A.NUM_DOC = :cuota";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $comando->bindParam(":factura", $factura, \PDO::PARAM_STR);
        $comando->bindParam(":cuota", $cuota, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarDetpagospendientes
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return dpfa_id
     */
    public function insertarDetpagospendientes($pfes_id, $dpfa_tipo_factura, $dpfa_factura, $dpfa_descripcion_factura, $dpfa_valor_factura, $dpfa_fecha_factura, $dpfa_saldo_factura, $dpfa_num_cuota, $dpfa_valor_cuota, $dpfa_fecha_vence_cuota, $dpfa_estado_pago, $dpfa_estado_financiero, $dpfa_usu_ingreso) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $param_sql = "dpfa_estado";
        $bdet_sql = "1";

        $param_sql .= ", dpfa_estado_logico";
        $bdet_sql .= ", 1";

        $param_sql .= ", dpfa_fecha_registro";
        $bdet_sql .= ", :dpfa_fecha_registro";

        /*
        $param_sql .= ", dpfa_estado_pago";
        $bdet_sql .= ", 1";
        */

        if (isset($pfes_id)) {
            $param_sql .= ", pfes_id";
            $bdet_sql .= ", :pfes_id";
        }
        if (isset($dpfa_tipo_factura)) {
            $param_sql .= ", dpfa_tipo_factura";
            $bdet_sql .= ", :dpfa_tipo_factura";
        }
        if (isset($dpfa_factura)) {
            $param_sql .= ", dpfa_factura";
            $bdet_sql .= ", :dpfa_factura";
        }
        if (isset($dpfa_descripcion_factura)) {
            $param_sql .= ", dpfa_descripcion_factura";
            $bdet_sql .= ", :dpfa_descripcion_factura";
        }
        if (isset($dpfa_valor_factura)) {
            $param_sql .= ", dpfa_valor_factura";
            $bdet_sql .= ", :dpfa_valor_factura";
        }
        if (isset($dpfa_fecha_factura)) {
            $param_sql .= ", dpfa_fecha_factura";
            $bdet_sql .= ", :dpfa_fecha_factura";
        }
        if (isset($dpfa_saldo_factura)) {
            $param_sql .= ", dpfa_saldo_factura";
            $bdet_sql .= ", :dpfa_saldo_factura";
        }
        if (isset($dpfa_num_cuota)) {
            $param_sql .= ", dpfa_num_cuota";
            $bdet_sql .= ", :dpfa_num_cuota";
        }
        /* --- */
        if (isset($dpfa_valor_cuota)) {
            $param_sql .= ", dpfa_valor_cuota";
            $bdet_sql .= ", :dpfa_valor_cuota";
        }
        if (isset($dpfa_fecha_vence_cuota)) {
            $param_sql .= ", dpfa_fecha_vence_cuota";
            $bdet_sql .= ", :dpfa_fecha_vence_cuota";
        }
        if (isset($dpfa_estado_pago)) {
            $param_sql .= ", dpfa_estado_pago";
            $bdet_sql .= ", :dpfa_estado_pago";
        }
        if (isset($dpfa_estado_pago)) {
            $param_sql .= ", dpfa_estado_financiero";
            $bdet_sql .= ", :dpfa_estado_financiero";
        }
        if (isset($dpfa_usu_ingreso)) {
            $param_sql .= ", dpfa_usu_ingreso";
            $bdet_sql .= ", :dpfa_usu_ingreso";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".detalle_pagos_factura ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);
            if (isset($pfes_id)) {
                $comando->bindParam(':pfes_id', $pfes_id, \PDO::PARAM_INT);
            }
            if (isset($dpfa_tipo_factura)) {
                $comando->bindParam(':dpfa_tipo_factura', $dpfa_tipo_factura, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_factura)))) {
                $comando->bindParam(':dpfa_factura', $dpfa_factura, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_descripcion_factura)))) {
                $comando->bindParam(':dpfa_descripcion_factura', ucfirst(mb_strtolower($dpfa_descripcion_factura, 'UTF-8')), \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_valor_factura)))) {
                $comando->bindParam(':dpfa_valor_factura', $dpfa_valor_factura, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_fecha_factura)))) {
                $comando->bindParam(':dpfa_fecha_factura', $dpfa_fecha_factura, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_saldo_factura)))) {
                $comando->bindParam(':dpfa_saldo_factura', $dpfa_saldo_factura, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_num_cuota)))) {
                $comando->bindParam(':dpfa_num_cuota', $dpfa_num_cuota, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_valor_cuota)))) {
                $comando->bindParam(':dpfa_valor_cuota', $dpfa_valor_cuota, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_fecha_vence_cuota)))) {
                $comando->bindParam(':dpfa_fecha_vence_cuota', $dpfa_fecha_vence_cuota, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_usu_ingreso)))) {
                $comando->bindParam(':dpfa_estado_pago', $dpfa_estado_pago, \PDO::PARAM_INT);
            }
            if (!empty((isset($dpfa_usu_ingreso)))) {
                $comando->bindParam(':dpfa_estado_financiero', $dpfa_estado_financiero, \PDO::PARAM_STR);
            }
            if (!empty((isset($dpfa_usu_ingreso)))) {
                $comando->bindParam(':dpfa_usu_ingreso', $dpfa_usu_ingreso, \PDO::PARAM_INT);
            }
            $comando->bindParam(":dpfa_fecha_registro", $fecha, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.detalle_pagos_factura');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function modificarPagosrechazado (Actualiza data cabecera)
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function modificarPagosrechazado($pfes_id, $est_id, $pfes_referencia, $fpag_id, $pfes_valor_pago, $pfes_fecha_pago, $pfes_observacion, $pfes_archivo_pago) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".pagos_factura_estudiante
                SET pfes_referencia = :pfes_referencia,
                    fpag_id = :fpag_id,                   
                    pfes_valor_pago = :pfes_valor_pago,
                    pfes_fecha_pago = :pfes_fecha_pago,
                    pfes_observacion = :pfes_observacion,
                    pfes_archivo_pago = :pfes_archivo_pago,
                    pfes_fecha_modificacion = :fecha_modificacion
                WHERE pfes_id = :pfes_id AND 
                      est_id = :est_id AND
                      pfes_estado =:estado AND
                      pfes_estado_logico = :estado");


            $comando->bindParam(":pfes_id", $pfes_id, \PDO::PARAM_INT);
            $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
            $comando->bindParam(":pfes_referencia", $pfes_referencia, \PDO::PARAM_STR);
            $comando->bindParam(":fpag_id", $fpag_id, \PDO::PARAM_INT);
            $comando->bindParam(":pfes_valor_pago", $pfes_valor_pago, \PDO::PARAM_STR);
            $comando->bindParam(":pfes_fecha_pago", $pfes_fecha_pago, \PDO::PARAM_STR);
            $comando->bindParam(":pfes_observacion", ucfirst(mb_strtolower($pfes_observacion, 'UTF-8')), \PDO::PARAM_STR);
            $comando->bindParam(":pfes_archivo_pago", $pfes_archivo_pago, \PDO::PARAM_STR);
            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();           
            return TRUE;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
    
    /**
     * Function getPagosxestudiante
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public static function getPagosxestudiante($arrFiltro = array(), $onlyData = false, $per_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {            
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " pfe.pfes_fecha_registro BETWEEN :fec_ini AND :fec_fin AND ";
            }           
        }
        $sql = "SELECT  pfe.pfes_id, pfe.est_id,
                        f.fpag_nombre as forma_pago,
                        pfe.pfes_referencia as referencia,
                        pfe.pfes_valor_pago valor_pago,
                        pfe.pfes_fecha_registro fecha_registro,     
                        -- pfe.pfes_fecha_pago as fecha_pago                        
                       ifnull(DATE_FORMAT(pfe.pfes_fecha_pago, '%Y-%m-%d'), ' ') as fecha_pago                        
                from " . $con2->dbname . ".pagos_factura_estudiante pfe
                    inner join " . $con->dbname . ".estudiante e on e.est_id = pfe.est_id
                    inner join " . $con1->dbname . ".persona p on p.per_id = e.per_id                
                    inner join " . $con2->dbname . ".forma_pago f on f.fpag_id = pfe.fpag_id 
                WHERE $str_search p.per_id = :per_id AND pfe.pfes_estado=:estado AND pfe.pfes_estado_logico=:estado
                ORDER BY pfe.pfes_fecha_registro DESC ";

        $comando = $con->createCommand($sql);          
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
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
                    'egen_id',
                    'fecha_creacion',
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
     * Function getPagosDetxestudiante
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public static function getPagosDetxestudiante($arrFiltro = array(), $onlyData = false, $factura) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        
        $estado = 1;
        $str_search = "";       
        $sql = "SELECT                         
                        u.uaca_nombre as unidad,
                        mo.mod_nombre as modalidad,
                        ea.eaca_nombre as carrera,
                        f.fpag_nombre as forma_pago,
                        d.dpfa_num_cuota,
                        d.dpfa_factura,
                        pfe.pfes_valor_pago valor_pago,
                        pfe.pfes_fecha_registro,     
                        case d.dpfa_estado_pago  
                                    when 1 then 'Pendiente'  
                                    when 2 then 'Aprobado'                                
                                    when 3 then 'Rechazado'   
                                end as estado_pago,                   
                        case d.dpfa_estado_financiero  
                                    when 'C' then 'Cancelado'  
                                    when 'N' then 'Pendiente'                                                              
                                    else 'Pendiente'
                                end as estado_financiero,
                        dpfa_id
                from " . $con2->dbname . ".pagos_factura_estudiante pfe inner join " . $con2->dbname . ".detalle_pagos_factura d on d.pfes_id = pfe.pfes_id
                inner join " . $con->dbname . ".estudiante e on e.est_id = pfe.est_id
                inner join " . $con1->dbname . ".persona p on p.per_id = e.per_id
                inner join " . $con->dbname . ".estudiante_carrera_programa ec on ec.est_id = e.est_id
                inner join " . $con->dbname . ".modalidad_estudio_unidad m on m.meun_id = ec.meun_id
                inner join " . $con->dbname . ".unidad_academica u on u.uaca_id = m.uaca_id
                inner join " . $con->dbname . ".modalidad mo on mo.mod_id = m.mod_id
                inner join " . $con->dbname . ".estudio_academico ea on ea.eaca_id = m.eaca_id
                inner join " . $con2->dbname . ".forma_pago f on f.fpag_id = pfe.fpag_id 
                WHERE $str_search pfe.pfes_id = :fac_id AND pfe.pfes_estado=:estado AND pfe.pfes_estado_logico=:estado
                ORDER BY pfe.pfes_fecha_registro DESC ";

        $comando = $con->createCommand($sql);          
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":fac_id", $factura, \PDO::PARAM_INT);
       
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'egen_id',
                    'fecha_creacion',
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
     * Function consultarDatosestudiante
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public function consultarDatosestudiante($per_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT 	p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,                        
                        ea.eaca_nombre as carrera,
                        u.uaca_nombre as unidad,
                        mo.mod_nombre as modalidad
                from " . $con->dbname . ".estudiante e inner join " . $con1->dbname . ".persona p on p.per_id = e.per_id
                    inner join " . $con->dbname . ".estudiante_carrera_programa ec on ec.est_id = e.est_id
                    inner join " . $con->dbname . ".modalidad_estudio_unidad m on m.meun_id = ec.meun_id
                    inner join " . $con->dbname . ".unidad_academica u on u.uaca_id = m.uaca_id
                    inner join " . $con->dbname . ".modalidad mo on mo.mod_id = m.mod_id
                    inner join " . $con->dbname . ".estudio_academico ea on ea.eaca_id = m.eaca_id                                     
                where p.per_id = :per_id                    
                    and est_estado = :estado
                    and est_estado_logico = :estado
                    and per_estado = :estado
                    and per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        return $comando->queryOne();
    }

    /**
     * Function consultarExistepago, consulta si ya cargo imagen de pago para no crear un registro igual
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return 
     */
    public function consultarExistepago($est_id, /* $dpfa_tipo_factura,*/  $dpfa_factura, $dpfa_num_cuota) {
        $con = \Yii::$app->db_facturacion;
        //$estado = 1;
        $sql = "SELECT count(dpf.dpfa_id) as registro
                    FROM " . $con->dbname . ".detalle_pagos_factura dpf
                    INNER JOIN " . $con->dbname . ".pagos_factura_estudiante pfe ON pfe.est_id = :est_id
                    WHERE
                    -- dpf.dpfa_tipo_factura = :dpfa_tipo_factura AND
                    dpf.dpfa_factura = :dpfa_factura AND
                    dpf.dpfa_num_cuota = :dpfa_num_cuota";

        $comando = $con->createCommand($sql);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        //$comando->bindParam(":dpfa_tipo_factura", $dpfa_tipo_factura, \PDO::PARAM_STR);
        $comando->bindParam(":dpfa_factura", $dpfa_factura, \PDO::PARAM_STR);
        $comando->bindParam(":dpfa_num_cuota", $dpfa_num_cuota, \PDO::PARAM_STR);

        return $comando->queryOne();
    }

    /**
     * Function getPagosestudiante pagos para ver en la vista de online
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public static function getPagosestudiante($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;
        
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "(p.per_pri_nombre like :estudiante OR ";
                $str_search .= "p.per_pri_apellido like :estudiante OR ";
                $str_search .= "p.per_cedula like :estudiante )  AND ";
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " d.dpfa_fecha_aprueba_rechaza BETWEEN :fec_ini AND :fec_fin AND ";
            }
            if ($arrFiltro['unidad'] > 0) {
                $str_search .= "u.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] > 0) {
                $str_search .= "mo.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['estadopago'] > 0) { // estado de revision
                $str_search .= "d.dpfa_estado_pago = :estadopago AND ";
            }     
        }
        $sql = "SELECT 
                        p.per_cedula as identificacion, 
                        concat(p.per_pri_nombre, ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,'')) as estudiante,
                        u.uaca_nombre as unidad,
                        mo.mod_nombre as modalidad,
                        ea.eaca_nombre as carrera,
                        f.fpag_nombre as forma_pago,
                        d.dpfa_num_cuota,
                        d.dpfa_factura,
                        pfe.pfes_valor_pago valor_pago,
                        ifnull(d.dpfa_fecha_aprueba_rechaza, ' ') as dpfa_fecha_aprueba_rechaza,";        

        $sql .= " 
                case d.dpfa_estado_pago  
                            when 1 then 'Pendiente'  
                            when 2 then 'Aprobado'                                
                            when 3 then 'Rechazado'   
                        end as estado_pago,            
                dpfa_id
                from " . $con2->dbname . ".pagos_factura_estudiante pfe inner join " . $con2->dbname . ".detalle_pagos_factura d on d.pfes_id = pfe.pfes_id
                inner join " . $con->dbname . ".estudiante e on e.est_id = pfe.est_id
                inner join " . $con1->dbname . ".persona p on p.per_id = e.per_id
                inner join " . $con->dbname . ".estudiante_carrera_programa ec on ec.est_id = e.est_id
                inner join " . $con->dbname . ".modalidad_estudio_unidad m on m.meun_id = ec.meun_id
                inner join " . $con->dbname . ".unidad_academica u on u.uaca_id = m.uaca_id
                inner join " . $con->dbname . ".modalidad mo on mo.mod_id = m.mod_id
                inner join " . $con->dbname . ".estudio_academico ea on ea.eaca_id = m.eaca_id
                inner join " . $con2->dbname . ".forma_pago f on f.fpag_id = pfe.fpag_id 
                WHERE $str_search pfe.pfes_estado=:estado AND pfe.pfes_estado_logico=:estado  ORDER BY pfe.pfes_fecha_registro DESC ";

        $comando = $con->createCommand($sql);          
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_ini = substr($arrFiltro["f_ini"], 0, -1) . ":00";
            $fecha_fin = substr($arrFiltro["f_fin"], 0, -1) . ":59";
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $unidad = $arrFiltro['unidad'];
            $modalidad = $arrFiltro['modalidad'];
            $estadopago = $arrFiltro['estadopago'];
            $estadofinanciero = $arrFiltro['estadofinanciero'];
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['search'] != "") {
                $comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] > 0) {
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estadopago'] > 0) {
                $comando->bindParam(":estadopago", $estadopago, \PDO::PARAM_INT);
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
                    'egen_id',
                    'fecha_creacion',
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /** OJO REVISAR LA FUNCION Y DESARROLLAR EL EVENTO DE REVERSO DE ESTADO
     * Function grabarReverso (Actualiza el estado del pago de aprobado a pendiente nuevamente)
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function grabaReverso($dpfa_id, $resultado, $observacion) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fecha_reverso = date(Yii::$app->params["dateTimeByDefault"]);
        $usuario_reverso = @Yii::$app->user->identity->usu_id;
        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".detalle_pagos_factura
                SET dpfa_observacion_reverso = :observacion,
                    dpfa_fecha_modificacion = :fecha_reverso,                   
                    dpfa_estado_pago = :resultado,
                    dpfa_estado_financiero = null,
                    dpfa_usu_modifica = :usuario_reverso
                    
                WHERE dpfa_id = :dpfa_id AND 
                      dpfa_estado =:estado AND
                      dpfa_estado_logico = :estado");


        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dpfa_id", $dpfa_id, \PDO::PARAM_INT);
        $comando->bindParam(":observacion", $observacion, \PDO::PARAM_STR);
        $comando->bindParam(":resultado", $resultado, \PDO::PARAM_INT);
        $comando->bindParam(":fecha_reverso", $fecha_reverso, \PDO::PARAM_STR);
        $comando->bindParam(":usuario_reverso", $usuario_reverso, \PDO::PARAM_INT);
        $response = $comando->execute();
        return $response;
    }

    public function buscarIdCartera($dpfa_id) {
        $con = \Yii::$app->db_facturacion;

        $sql = "SELECT ccar_id, ccar_numero_documento,ccar_num_cuota 
                  from db_facturacion.carga_cartera,
                       (SELECT dpfa_factura,dpfa_num_cuota 
                          FROM db_facturacion.detalle_pagos_factura 
                         WHERE dpfa_id = :dpfa_id ) as dpf
                 where ccar_numero_documento = dpf.dpfa_factura 
                   and ccar_num_cuota like concat('%',dpf.dpfa_num_cuota,'%')";

        $comando = $con->createCommand($sql); 
        $comando->bindParam(":dpfa_id", $dpfa_id, \PDO::PARAM_STR);
        $response   = $comando->execute();
        $resultData = $comando->queryAll();
        return $resultData;
    }//function actualizarCartera
}