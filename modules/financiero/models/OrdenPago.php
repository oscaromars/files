<?php

namespace app\modules\financiero\models;

use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "orden_pago".
 *
 * @property integer $opag_id
 * @property integer $sins_id
 * @property integer $sgen_id
 * @property integer $com_id
 * @property double $opag_subtotal
 * @property double $opag_iva
 * @property double $opag_total
 * @property string $opag_fecha_generacion
 * @property string $opag_estado_pago
 * @property string $opag_observacion
 * @property integer $opag_usu_ingreso
 * @property integer $opag_usu_modifica
 * @property string $opag_estado
 * @property string $opag_fecha_creacion
 * @property string $opag_fecha_modificacion
 * @property string $opag_estado_logico
 *
 * @property DesglosePago[] $desglosePagos
 * @property InfoCargaPrepago[] $infoCargaPrepagos
 * @property InfoFactura[] $infoFacturas
 * @property SolicitudGeneral $sgen
 */
class OrdenPago extends \app\modules\financiero\components\CActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'orden_pago';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sins_id', 'sgen_id', 'com_id', 'opag_usu_ingreso', 'opag_usu_modifica'], 'integer'],
            [['opag_subtotal', 'opag_iva', 'opag_total', 'opag_usu_ingreso', 'opag_estado', 'opag_estado_logico'], 'required'],
            [['opag_subtotal', 'opag_iva', 'opag_total'], 'number'],
            [['opag_fecha_generacion', 'opag_fecha_creacion', 'opag_fecha_modificacion'], 'safe'],
            [['opag_estado_pago', 'opag_estado', 'opag_estado_logico'], 'string', 'max' => 1],
            [['opag_observacion'], 'string', 'max' => 200],
            [['sgen_id'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudGeneral::className(), 'targetAttribute' => ['sgen_id' => 'sgen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'opag_id' => 'Opag ID',
            'sins_id' => 'Sins ID',
            'sgen_id' => 'Sgen ID',
            'com_id' => 'Com ID',
            'opag_subtotal' => 'Opag Subtotal',
            'opag_iva' => 'Opag Iva',
            'opag_total' => 'Opag Total',
            'opag_fecha_generacion' => 'Opag Fecha Generacion',
            'opag_estado_pago' => 'Opag Estado Pago',
            'opag_observacion' => 'Opag Observacion',
            'opag_usu_ingreso' => 'Opag Usu Ingreso',
            'opag_usu_modifica' => 'Opag Usu Modifica',
            'opag_estado' => 'Opag Estado',
            'opag_fecha_creacion' => 'Opag Fecha Creacion',
            'opag_fecha_modificacion' => 'Opag Fecha Modificacion',
            'opag_estado_logico' => 'Opag Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesglosePagos() {
        return $this->hasMany(DesglosePago::className(), ['opag_id' => 'opag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfoCargaPrepagos() {
        return $this->hasMany(InfoCargaPrepago::className(), ['opag_id' => 'opag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfoFacturas() {
        return $this->hasMany(InfoFactura::className(), ['opag_id' => 'opag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSgen() {
        return $this->hasOne(SolicitudGeneral::className(), ['sgen_id' => 'sgen_id']);
    }

    /**
     * Function listarSolicitudesadm
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (información de las solicitudes con/sin orden de pago.)
     */
    public function listarSolicitudesadm($arrFiltro = array(), $resp_gruporol, $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_pago = 'P';
        $rolgrupo = '';
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search) AND ";
            //$str_search .= "per.per_pri_apellido like :search OR ";
            //$str_search .= "ming.ming_descripcion like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
        } else {
            $columnsAdd = "sins.sins_id as solicitud_id,
          per.per_id as persona,
          per.per_pri_nombre as per_pri_nombre,
          per.per_seg_nombre as per_seg_nombre,
          per.per_pri_apellido as per_pri_apellido,
          per.per_seg_apellido as per_seg_apellido,";
        }
        if (!empty($resp_gruporol)) {
            $rolgrupo = ", " . $resp_gruporol . " as rol";
        }
        $sql = "SELECT  distinct ifnull(sins.num_solicitud, lpad(sins.sins_id,9,'0')) as solicitud,
                        sins.sins_id,
                        sins.sins_fecha_solicitud,
                        per.per_id,
                        per.per_cedula identificacion,
                        concat(per.per_pri_apellido) apellidos,
                        concat(per.per_pri_nombre) nombres,
                        uaca_descripcion nivel,
                        ifnull((select ming.ming_alias
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as metodo,
                        ifnull(opag.opag_id,'') orden,
                        opag_estado_pago estado,
                        per.per_correo as correo,
                        sins.emp_id,
                        $columnsAdd
                        (case ifnull((select max(icpr_id)
                                      from " . $con2->dbname . ".info_carga_prepago icp
                                      where icp.opag_id = opag.opag_id
                                            and icp.icpr_estado = :estado
                                            and icp.icpr_estado_logico = :estado ),'P') when 'P' then 'Pendiente' else 'No Aprobada' end) as estado_desc_pago

                $rolgrupo
                FROM " . $con->dbname . ".solicitud_inscripcion sins INNER JOIN " . $con3->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                     INNER JOIN " . $con->dbname . ".interesado inte on sins.int_id = inte.int_id
                     INNER JOIN " . $con1->dbname . ".persona per on inte.per_id = per.per_id
                     INNER JOIN " . $con2->dbname . ".orden_pago opag on sins.sins_id = opag.sins_id
                WHERE
                      $str_search
                      ( sins.rsin_id <> 4 and opag_estado_pago = 'P') AND
                      sins.sins_estado = :estado AND
                      sins.sins_estado_logico = :estado AND
                      uaca.uaca_estado = :estado AND
                      uaca.uaca_estado_logico = :estado AND
                      inte.int_estado_logico = :estado AND
                      inte.int_estado = :estado AND
                      per.per_estado = :estado AND
                      per.per_estado_logico = :estado AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado AND
                      (opag.opag_estado_pago = :estado_pago and
                      not exists(select icpr_id from " . $con2->dbname . ".info_carga_prepago icp
                                           where icp.opag_id = opag.opag_id
                                                 and icp.icpr_estado = :estado
                                                 and icp.icpr_estado_logico = :estado)) OR
                      (opag.opag_estado_pago = :estado_pago and exists
                                        (select icpr_resultado
                                         from " . $con2->dbname . ".info_carga_prepago
                                         where icpr_id = (select max(icpr_id)
                                                          from " . $con2->dbname . ".info_carga_prepago icp
                                                          where icp.opag_id = opag.opag_id
                                                                and icp.icpr_estado = :estado
                                                                and icp.icpr_estado_logico = :estado)
                                            and icpr_resultado = 'RE'))
                ORDER BY sins.sins_fecha_solicitud desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_pago", $estado_pago, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
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
     * Function  listarSolicitud
     * @author   Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @modified Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @param
     * @return   $resultData (información de las solicitudes pendientes .)
     */
    public function listarSolicitud($sol_id, $per_id, $opag_id, $rol, $arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_captacion;
        $estado = 1;
        $estado_precio = 'A';
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "ite.ite_nombre like :search AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
        }

        $sql = "SELECT
                    orp.opag_id,
                    inte.int_id,
                    ite.ite_nombre,
                    orp.opag_total as pag_total,
                    format(orp.opag_total,2) as ipre_precio,
                    format((round(orp.opag_total,2) - ifnull(orp.opag_valor_pagado,0)),2) as pendiente,
                    orp.opag_estado_pago as statusPago,
                    (case orp.opag_estado_pago when 'P' then 'Pendiente' when 'R' then 'Revisando' when 'S' then 'Pagado' end) as estado,
                    orp.opag_id as orden_id,
                    (case when ifnull((select sum(icpr.icpr_valor)
                                       from " . $con->dbname . ".info_carga_prepago icpr
                                       where icpr.opag_id = orp.opag_id AND
                                             icpr.icpr_estado = :estado  AND
                                             icpr.icpr_estado_logico = :estado),0) = 0 then '0'
                          else
                                    (select sum(icpr.icpr_valor)
                                    from " . $con->dbname . ".info_carga_prepago icpr
                                    where icpr.opag_id = orp.opag_id AND
                                          icpr.icpr_estado = :estado  AND
                                          icpr.icpr_estado_logico = :estado) end) as valor_cargado,
                    lpad(ifnull(sins.num_solicitud,sins.sins_id),'9','0') as solicitud,
                    sins.sins_id,
                    sins.sins_fecha_solicitud,
                    :rol as rol, per.per_id
                FROM " . $con1->dbname . ".persona per
                    INNER JOIN " . $con2->dbname . ".interesado inte on inte.per_id = per.per_id
                    INNER JOIN " . $con2->dbname . ".solicitud_inscripcion sins on sins.int_id = inte.int_id
                    INNER JOIN  " . $con->dbname . ".orden_pago orp on sins.sins_id = orp.sins_id
                    INNER JOIN " . $con->dbname . ".desglose_pago dp on dp.opag_id = orp.opag_id
                    INNER JOIN " . $con->dbname . ".item_precio itp ON itp.ite_id = dp.ite_id
                    INNER JOIN " . $con->dbname . ".item ite ON ite.ite_id = itp.ite_id ";
        if (!empty($sol_id)) {
            $sql .=  "WHERE $str_search sins.sins_id = " . $sol_id . " AND ";
        } else {
             $sql .=  "WHERE inte.per_id = :per_id AND sins.int_id = inte.int_id AND ";
        }
        if (!empty($opag_id)) {
            $sql .= "orp.opag_id = ifnull(" . $opag_id . ",orp.opag_id) AND ";
        }
        $sql .= "itp.ipre_estado_precio = :estado_precio AND
                orp.opag_estado_logico = :estado AND
                itp.ipre_estado_logico = :estado AND
                -- ite.ite_estado_logico = :estado AND
                inte.int_estado_logico = :estado AND
                sins.sins_estado_logico = :estado AND
                orp.opag_estado = :estado AND
                itp.ipre_estado = :estado AND
                -- ite.ite_estado = :estado AND
                inte.int_estado = :estado AND
                sins.sins_estado = :estado AND
                dp.dpag_estado = :estado AND
                dp.dpag_estado_logico = :estado
           ORDER BY sins.sins_fecha_solicitud desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":rol", $rol, \PDO::PARAM_STR);
        $comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);


        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
        }
        if (isset($opag_id)) {
            $resultData = $comando->queryOne();
            return $resultData;
        } else {
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
    }

    /**
     * Function listarPagosadmxsolicitud
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (información de los pagos realizados de una solicitud.)
     */
    public function listarPagosadmxsolicitud($resp_gruporol, $opag, $persona_pago) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT  icpr.opag_id orden, icpr.icpr_id id,
                        format(icpr.icpr_valor,2) valor,
                        fpag.fpag_nombre formapago,
                        ifnull(icpr_fecha_pago,'') as fechapago,
                        icpr_fecha_registro fechacargo,
                        icpr.icpr_imagen imagen,
                        (case when icpr_resultado = 'AP' then 'Aprobado'
                             when icpr_resultado = 'RE' then 'No Aprobado' else 'Pendiente' end)  as estado,
                        ifnull(icpr.icpr_valor_pagado,0) as valorpagado,
                        (select ifnull(opag.opag_total,0)
                         from " . $con->dbname . ".orden_pago opag
			 where opag.opag_id = :opag AND
			       opag.opag_estado = :estado AND
			       opag.opag_estado_logico = :estado) as valortotal,
                         $resp_gruporol as rol,
                         :persona_pago as per_id
                FROM " . $con->dbname . ".info_carga_prepago icpr INNER JOIN " . $con->dbname . ".forma_pago fpag on icpr.fpag_id = fpag.fpag_id
                WHERE icpr.opag_id = :opag AND
                      icpr.icpr_estado = :estado AND
                      icpr.icpr_estado_logico = :estado AND
                      fpag.fpag_estado = :estado AND
                      fpag.fpag_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag", $opag, \PDO::PARAM_INT);
        $comando->bindParam(":resp_gruporol", $resp_gruporol, \PDO::PARAM_INT);
        $comando->bindParam(":persona_pago", $persona_pago, \PDO::PARAM_INT);

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
        return $dataProvider;
    }

    /**
     * Function insertarRegistropago (Registro del pago)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarRegistropago($dpag_id, $fpag_id, $rpag_valor, $rpag_fecha_pago, $rpag_imagen, $rpag_num_transaccion, $rpag_fecha_transaccion, $rpag_observacion, $rpag_resultado, $rpag_usuario_transaccion, $rpag_revisado) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una.
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una.
        }

        $param_sql = "rpag_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", rpag_estado";
        $bsol_sql .= ", 1";

        if (isset($dpag_id)) {
            $param_sql .= ", dpag_id";
            $bsol_sql .= ", :dpag_id";
        }

        if (isset($fpag_id)) {
            $param_sql .= ", fpag_id";
            $bsol_sql .= ", :fpag_id";
        }

        if (isset($rpag_valor)) {
            $param_sql .= ", rpag_valor";
            $bsol_sql .= ", :rpag_valor";
        }

        if (isset($rpag_fecha_pago)) {
            $param_sql .= ", rpag_fecha_pago";
            $bsol_sql .= ", :rpag_fecha_pago";
        }

        if (isset($rpag_imagen)) {
            $param_sql .= ", rpag_imagen";
            $bsol_sql .= ", :rpag_imagen";
        }

        if (isset($rpag_num_transaccion)) {
            $param_sql .= ", rpag_num_transaccion";
            $bsol_sql .= ", :rpag_num_transaccion";
        }

        if (isset($rpag_fecha_transaccion)) {
            $param_sql .= ", rpag_fecha_transaccion";
            $bsol_sql .= ", :rpag_fecha_transaccion";
        }

        if (isset($rpag_observacion)) {
            $param_sql .= ", rpag_observacion";
            $bsol_sql .= ", :rpag_observacion";
        }

        if (isset($rpag_resultado)) {
            $param_sql .= ", rpag_resultado";
            $bsol_sql .= ", :rpag_resultado";
        }

        if (isset($rpag_usuario_transaccion)) {
            $param_sql .= ", rpag_usuario_transaccion";
            $bsol_sql .= ", :rpag_usuario_transaccion";
        }

        if (isset($rpag_revisado)) {
            $param_sql .= ", rpag_revisado";
            $bsol_sql .= ", :rpag_revisado";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".registro_pago ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($dpag_id))
                $comando->bindParam(':dpag_id', $dpag_id, \PDO::PARAM_INT);

            if (isset($fpag_id))
                $comando->bindParam(':fpag_id', $fpag_id, \PDO::PARAM_STR);

            if (isset($rpag_valor))
                $comando->bindParam(':rpag_valor', $rpag_valor, \PDO::PARAM_STR);

            if (isset($rpag_fecha_pago))
                $comando->bindParam(':rpag_fecha_pago', $rpag_fecha_pago, \PDO::PARAM_STR);

            if (isset($rpag_imagen))
                $comando->bindParam(':rpag_imagen', $rpag_imagen, \PDO::PARAM_STR);

            if (isset($rpag_num_transaccion))
                $comando->bindParam(':rpag_num_transaccion', $rpag_num_transaccion, \PDO::PARAM_STR);

            if (isset($rpag_fecha_transaccion))
                $comando->bindParam(':rpag_fecha_transaccion', $rpag_fecha_transaccion, \PDO::PARAM_STR);

            if (isset($rpag_observacion))
                $comando->bindParam(':rpag_observacion', $rpag_observacion, \PDO::PARAM_STR);

            if (isset($rpag_resultado))
                $comando->bindParam(':rpag_resultado', $rpag_resultado, \PDO::PARAM_STR);

            if (isset($rpag_usuario_transaccion))
                $comando->bindParam(':rpag_usuario_transaccion', $rpag_usuario_transaccion, \PDO::PARAM_INT);

            if (isset($rpag_revisado))
                $comando->bindParam(':rpag_revisado', $rpag_revisado, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.registro_pago');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarInfoOrdenPagoPorPerId ()
     * Me permite consultar el id de la cargar de orden de pago, dado el id de la persona.
     * @author  Kleber Loayza <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarInfoOrdenPagoPorPerId($per_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "
                Select opag.opag_id as opag_id
                From db_captacion.interesado as inte
                    join db_captacion.solicitud_inscripcion as sin on sin.int_id=inte.int_id
                    join db_facturacion.orden_pago as opag on opag.sins_id=sin.sins_id
                Where inte.per_id=:per_id
                    and inte.int_estado=:estado and inte.int_estado_logico=:estado
                    and sin.sins_estado=:estado and sin.sins_estado_logico=:estado
                    and opag.opag_estado=:estado and opag.opag_estado_logico=:estado;
                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData['opag_id'];
    }

    /**
     * Function consultarCargo (Se obtiene información del pago aprobado en tablas temporales)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarCargo($icpr_id, $opag_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT  'S' existe, icpr.opag_id orden, icpr.icpr_id id,
                        icpr.icpr_valor_pagado valorpagado, icpr.icpr_valor valor, icpr.fpag_id,
                        icpr_fecha_pago fechapago, icpr.icpr_imagen imagen,
                        opag.opag_total valortotal, opag.opag_fecha_pago_total fechapagotot,
                        icpr.icpr_num_transaccion, icpr.icpr_fecha_transaccion,
                        opag.opag_valor_pagado total_pagado
                FROM " . $con->dbname . ".info_carga_prepago icpr INNER JOIN " . $con->dbname . ".orden_pago opag on opag.opag_id = icpr.opag_id
                WHERE icpr.icpr_id = :icpr_id AND
                      icpr.opag_id = :opag_id AND
                      icpr.icpr_estado = :estado AND
                      icpr.icpr_estado_logico = :estado AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":icpr_id", $icpr_id, \PDO::PARAM_INT);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function obtenerDesglosepagopend (Obtención de todos los desgloses(cuotas) pendientes de pagar.)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function obtenerDesglosepagopend($opag_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $estado_pago = 'P';

        $sql = "SELECT  dpag_id id, dpag_total valor_desglose
                FROM " . $con->dbname . ".desglose_pago dpag
                WHERE dpag.opag_id = :opag_id AND
                      dpag.dpag_estado_pago = :estado_pago AND
                      dpag.dpag_estado = :estado AND
                      dpag.dpag_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_pago", $estado_pago, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function actualizaDetallecargo (Actualiza el pago a revisado en la tabla temporal)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaDetallecargo($dcar_id, $dcar_revisado, $dcar_resultado, $dcar_observacion, $dcar_usuario, $valor_pagado) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $dcar_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($dcar_resultado == "AP") {
            $fechapago = date(Yii::$app->params["dateTimeByDefault"]);
        }

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".info_carga_prepago
                SET icpr_revisado = :icpr_revisado,
                    icpr_resultado = :icpr_resultado,
                    icpr_usuario_transaccion = :icpr_usuario,
                    icpr_fecha_modificacion = :icpr_fecha_modificacion,
                    icpr_observacion = :icpr_observacion,
                    icpr_fecha_pago = :icpr_fecha_pago,
                    icpr_valor_pagado = :icpr_valor_pagado
                WHERE icpr_id = :icpr_id and icpr_estado =:estado AND
                      icpr_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":icpr_id", $dcar_id, \PDO::PARAM_INT);
        $comando->bindParam(":icpr_fecha_pago", $fechapago, \PDO::PARAM_STR);
        $comando->bindParam(":icpr_observacion", $dcar_observacion, \PDO::PARAM_STR);
        $comando->bindParam(":icpr_fecha_modificacion", $dcar_fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":icpr_resultado", $dcar_resultado, \PDO::PARAM_STR);
        $comando->bindParam(":icpr_revisado", $dcar_revisado, \PDO::PARAM_STR);
        $comando->bindParam(":icpr_usuario", $dcar_usuario, \PDO::PARAM_INT);
        $comando->bindParam(":icpr_valor_pagado", $valor_pagado, \PDO::PARAM_INT);
        $response = $comando->execute();
        return $response;
    }

    /**
     * Function actualizaDesglosepago (Actualiza el estado del o los registros de la tabla desglose y campos de auditoría.)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaDesglosepago($dpag_id, $estado_pago, $usuario) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".desglose_pago
                SET dpag_estado_pago = :estado_pago,
                    dpag_usu_modifica = :usuario,
                    dpag_fecha_modificacion = :fecha_modificacion
                WHERE dpag_id = :dpag_id AND
                      dpag_estado =:estado AND
                      dpag_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dpag_id", $dpag_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_pago", $estado_pago, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":usuario", $usuario, \PDO::PARAM_STR);
        $response = $comando->execute();

        return $response;
    }

    /**
     * Function formas de pago
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function formaPago($activo_carga) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT fp.fpag_id AS id, fp.fpag_nombre AS value
                FROM " . $con->dbname . ".forma_pago fp
                WHERE  fp.fpag_estado_logico = :estado AND
                       fp.fpag_estado = :estado AND
                       fp.fpag_distintivo = '" . $activo_carga . "'";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryall();
        return $resultData;
    }

    /**
     * Function grabar insertarCargaprepago
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>; Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarCargaprepago($opag_id, $fpag_id, $icpr_valor, $icpr_imagen, $icpr_revisado, $icpr_resultado, $icpr_observacion, $icpr_num_transaccion, $icpr_fecha_transaccion, $fecha_registro) {
        $con = \Yii::$app->db_facturacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "icpr_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", icpr_estado";
        $bsol_sql .= ", 1";

        if (isset($opag_id)) {
            $param_sql .= ", opag_id";
            $bsol_sql .= ", :opag_id";
        }

        if (isset($icpr_valor)) {
            $param_sql .= ", icpr_valor";
            $bsol_sql .= ", :icpr_valor";
        }

        if (isset($fecha_registro)) {
            $param_sql .= ", icpr_fecha_registro";
            $bsol_sql .= ", :icpr_fecha_registro";
        }

        if (isset($icpr_imagen)) {
            $param_sql .= ", icpr_imagen";
            $bsol_sql .= ", :icpr_imagen";
        }

        if (isset($icpr_revisado)) {
            $param_sql .= ", icpr_revisado";
            $bsol_sql .= ", :icpr_revisado";
        }

        if (isset($icpr_resultado)) {
            $param_sql .= ", icpr_resultado";
            $bsol_sql .= ", :icpr_resultado";
        }

        if (isset($icpr_observacion)) {
            $param_sql .= ", icpr_observacion";
            $bsol_sql .= ", :icpr_observacion";
        }

        if (isset($fpag_id)) {
            $param_sql .= ", fpag_id";
            $bsol_sql .= ", :fpag_id";
        }

        if (isset($icpr_num_transaccion)) {
            $param_sql .= ", icpr_num_transaccion";
            $bsol_sql .= ", :icpr_num_transaccion";
        }

        if (isset($icpr_fecha_transaccion)) {
            $param_sql .= ", icpr_fecha_transaccion";
            $bsol_sql .= ", :icpr_fecha_transaccion";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".info_carga_prepago ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($opag_id))
                $comando->bindParam(':opag_id', $opag_id, \PDO::PARAM_INT);

            if (isset($icpr_valor))
                $comando->bindParam(':icpr_valor', $icpr_valor, \PDO::PARAM_STR);

            if (isset($fecha_registro))
                $comando->bindParam(':icpr_fecha_registro', $fecha_registro, \PDO::PARAM_STR);

            if (isset($icpr_imagen))
                $comando->bindParam(':icpr_imagen', $icpr_imagen, \PDO::PARAM_STR);

            if (isset($icpr_revisado))
                $comando->bindParam(':icpr_revisado', $icpr_revisado, \PDO::PARAM_STR);

            if (isset($icpr_resultado))
                $comando->bindParam(':icpr_resultado', $icpr_resultado, \PDO::PARAM_STR);

            if (isset($icpr_observacion))
                $comando->bindParam(':icpr_observacion', $icpr_observacion, \PDO::PARAM_STR);

            if (isset($fpag_id))
                $comando->bindParam(':fpag_id', $fpag_id, \PDO::PARAM_INT);

            if (isset($icpr_num_transaccion))
                $comando->bindParam(':icpr_num_transaccion', $icpr_num_transaccion, \PDO::PARAM_STR);

            if (isset($icpr_fecha_transaccion))
                $comando->bindParam(':icpr_fecha_transaccion', $icpr_fecha_transaccion, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.info_carga_prepago');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarOrdenpago (Se obtiene información del cliente de la orden de pago)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarOrdenpago($resp_gruporol, $opag_id, $idd) {
        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT  lpad(ifnull(sins.num_solicitud,sins.sins_id),9,'0') as sser_id,
                        per.per_id,
                        inte.int_id,
                        concat(per_pri_nombre, ' ', ifnull(per_seg_nombre,'')) nombres,
                        concat(per_pri_apellido, ' ',ifnull(per_seg_apellido,'')) apellidos,
                        per_cedula,
                        format(opag.opag_total,2) valortotal,
                        format(ifnull(opag_valor_pagado,0),2) valoraplicado,

                        format((select ifnull(icpr_valor,0)
                         from " . $con->dbname . ".info_carga_prepago icpr
                         where icpr.icpr_id = :idd AND
                               icpr.icpr_estado = :estado AND
                               icpr.icpr_estado_logico = :estado),2) valorsubido,

                        (select ifnull(icpr_imagen,'')
                         from " . $con->dbname . ".info_carga_prepago icpr
                         where icpr.icpr_id = :idd AND
                               icpr.icpr_estado = :estado AND
                               icpr.icpr_estado_logico = :estado)  imagen,

                        (select (case when ifnull(icpr_resultado,' ') = ' ' then 'PE' else icpr_resultado end)
                         from " . $con->dbname . ".info_carga_prepago icpr
                         where icpr.icpr_id = :idd AND
                               icpr.icpr_estado = :estado AND
                               icpr.icpr_estado_logico = :estado) estado,

                        (select ifnull(icpr_observacion,'')
                         from " . $con->dbname . ".info_carga_prepago icpr
                         where icpr.icpr_id = :idd AND
                               icpr.icpr_estado = :estado AND
                               icpr.icpr_estado_logico = :estado) observacion,
                        $resp_gruporol as rol
                FROM " . $con->dbname . ".orden_pago opag INNER JOIN " . $con1->dbname . ".solicitud_inscripcion sins on opag.sins_id = sins.sins_id
                        INNER JOIN " . $con1->dbname . ".interesado inte on inte.int_id = sins.int_id
                        INNER JOIN " . $con2->dbname . ".persona per on per.per_id = inte.per_id
                WHERE   opag.opag_id = :opag_id AND
                        opag.opag_estado = :estado AND
                        opag.opag_estado_logico = :estado AND
                        sins.sins_estado = :estado AND
                        sins.sins_estado_logico = :estado AND
                        inte.int_estado_logico = :estado AND
                        per.per_estado = :estado AND
                        per.per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);
        $comando->bindParam(":idd", $idd, \PDO::PARAM_INT);
        $comando->bindParam(":resp_gruporol", $resp_gruporol, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function actualizaOrdenpago (Actualiza el estado a pagado a la orden de pago.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaOrdenpago($opag_id, $opag_estado_pagado, $opag_valor_pagado, $opag_fecha_pago_total, $usuario) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $opag_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".orden_pago
                SET opag_estado_pago = :opag_estado_pagado,
                    opag_valor_pagado = :opag_valor_pagado,
                    opag_fecha_pago_total = :opag_fecha_pago_total,
                    opag_fecha_modificacion = :opag_fecha_modificacion,
                    opag_usu_modifica = :usuario
                WHERE opag_id = :opag_id AND
                      opag_estado =:estado AND
                      opag_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);
        $comando->bindParam(":opag_estado_pagado", $opag_estado_pagado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_fecha_modificacion", $opag_fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":opag_valor_pagado", $opag_valor_pagado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_fecha_pago_total", $opag_fecha_pago_total, \PDO::PARAM_STR);
        $comando->bindParam(":usuario", $usuario, \PDO::PARAM_STR);
        $response = $comando->execute();

        return $response;
    }

    /**
     * Function consulta de orden pago por id (Actualiza el estado a pagado a la orden de pago.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultaOrdenpagoid($opag_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT icpr_id ccar_id, icpr_valor_pagado ccar_pagado
                FROM " . $con->dbname . ".info_carga_prepago
                WHERE  opag_id = " . $opag_id . " AND icpr_estado_logico = :estado AND
                       icpr_estado = :estado ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function listarDocumento pagados por interesado
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return  $resultData
     */
    public function listarDocumento($opag_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT  icpr.fpag_id,
                        fp.fpag_nombre,
                        format(icpr.icpr_valor,2) as dcar_valor,
                        ifnull(icpr.icpr_imagen,'') as dcar_imagen,
                        (case icpr.icpr_revisado when 'PE' then 'Pendiente' when 'RE' then 'Revisado' end) as dcar_revisado,
                        (case icpr.icpr_resultado when 'AP' then 'Aprobado' when 'RE' then 'Reprobado'  else '' end) as dcar_resultado,
                         ifnull(icpr.icpr_observacion,'') as icpr_observacion,
                        icpr.icpr_num_transaccion,icpr.icpr_fecha_transaccion
                FROM " . $con->dbname . ".info_carga_prepago icpr "
                . "INNER JOIN " . $con->dbname . ".forma_pago fp ON fp.fpag_id = icpr.fpag_id
                WHERE icpr.opag_id = " . $opag_id . " AND
                      icpr.icpr_estado_logico = :estado AND
                      icpr.icpr_estado = :estado ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
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
        return $dataProvider;
    }

    /**
     * Function insertarAdmitido (Crea aspirante)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarAdmitido($int_id, $sins_id) {
        $con = \Yii::$app->db_captacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "adm_estado_logico";
        $basp_sql = "1";

        $param_sql .= ", adm_estado";
        $basp_sql .= ", 1";

        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $basp_sql .= ", :int_id";
        }
        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $basp_sql .= ", :sins_id";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".admitido ($param_sql) VALUES($basp_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);
            if (isset($sins_id))
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.admitido');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function actualizaEstadointeresado (Actualiza el estado de inactivo del interesado, en el caso cuando pasa a aspirante)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaEstadointeresado($int_id, $usu_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $estado_interesado = 0;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".interesado
                SET int_fecha_modificacion = :fecha_modificacion,
                    int_estado_interesado = :estado_interesado,
                    int_usuario_modifica = :usu_id
                WHERE int_id = :int_id and int_estado =:estado AND
                      int_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
        $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":estado_interesado", $estado_interesado, \PDO::PARAM_STR);

        $response = $comando->execute();
        return $response;
    }

    /**
     * Function datosBotonpago (Se obtienen los datos para el formulario del botón de pagos)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function datosBotonpago($opag_id, $emp_id) {
        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_asgard;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_pago = 'P';

        if ($emp_id == 1) {  //Cuando se trata de una SI= solicitud de inscripción, a carrera de UTEG.
            $sql = "SELECT
                            i.ite_nombre as curso,
                            opag.opag_subtotal,
                            opag.opag_iva,
                            opag.opag_total as precio,
                            (select usu.usu_user from " . $con2->dbname . ".usuario usu where usu.per_id = per.per_id and usu.usu_estado_logico = '1' and ((usu.usu_estado = '0' and usu.usu_link_activo is not null) or (usu.usu_estado = '1' and ifnull(usu.usu_link_activo,'')=''))) as email,
                            concat(ifnull(per.per_pri_nombre,''), ' ', ifnull(per.per_seg_nombre,'')) as nombres,
                            concat(ifnull(per.per_pri_apellido,''),' ', ifnull(per.per_seg_apellido,'')) as apellidos,
                            per.per_cedula as identificacion,
                            ifnull(per.per_domicilio_telefono, per.per_celular) as telefono,
                            concat(per.per_domicilio_cpri, ' ', per.per_domicilio_csec, ' ', per.per_domicilio_num) as domicilio,
                            lpad(opag.sins_id,4,'0') as solicitud,
                            eaca_nombre as carrera
                    FROM " . $con->dbname . ".orden_pago opag INNER JOIN " . $con1->dbname . ".solicitud_inscripcion sins on sins.sins_id = opag.sins_id
                          INNER JOIN " . $con->dbname . ".desglose_pago dp on dp.opag_id = opag.opag_id
                          INNER JOIN " . $con->dbname . ".item i on i.ite_id = dp.ite_id
                          INNER JOIN " . $con1->dbname . ".interesado inte on inte.int_id = sins.int_id
                          INNER JOIN " . $con2->dbname . ".persona per on per.per_id = inte.per_id
                          INNER JOIN " . $con3->dbname . ".estudio_academico ea on ea.eaca_id = sins.eaca_id
                    WHERE opag.opag_id = :opag_id
                          AND opag.opag_estado_pago = :estado_pago
                          AND opag.opag_estado = :estado
                          AND sins.sins_estado = :estado
                          AND sins.sins_estado_logico = :estado
                          AND opag.opag_estado_logico = :estado
                          AND inte.int_estado = :estado
                          AND per.per_estado = :estado
                          AND ea.eaca_estado = :estado
                          AND inte.int_estado_logico = :estado
                          AND per.per_estado_logico = :estado
                          AND ea.eaca_estado_logico = :estado
                          AND dp.dpag_estado = :estado
                          AND dp.dpag_estado_logico = :estado
                          AND i.ite_estado = :estado
                          AND i.ite_estado_logico = :estado";
        }  else {
            $sql = "SELECT
                            (select ite.ite_nombre from db_facturacion.item ite inner join db_facturacion.item_metodo_unidad imni on ite.ite_id = imni.ite_id
                                         where imni.uaca_id = sins.uaca_id and imni.mod_id = sins.mod_id and imni.mest_id = sins.mest_id
                                                        and imni.imni_estado = :estado and imni.imni_estado_logico = :estado and ite.ite_estado = :estado and ite.ite_estado_logico = :estado) as curso,
                                opag.opag_subtotal,
                                opag.opag_iva,
                                opag.opag_total as precio,
                                (select usu.usu_user from db_asgard.usuario usu where usu.per_id = per.per_id and usu.usu_estado_logico = '1' and ((usu.usu_estado = '0' and usu.usu_link_activo is not null) or (usu.usu_estado = '1' and ifnull(usu.usu_link_activo,'')=''))) as email,
                                concat(ifnull(per.per_pri_nombre,''), ' ', ifnull(per.per_seg_nombre,'')) as nombres,
                                concat(ifnull(per.per_pri_apellido,''),' ', ifnull(per.per_seg_apellido,'')) as apellidos,
                                per.per_cedula as identificacion,
                                ifnull(per.per_domicilio_telefono, per.per_celular) as telefono,
                                concat(per.per_domicilio_cpri, ' ', per.per_domicilio_csec, ' ', per.per_domicilio_num) as domicilio,
                                lpad(opag.sins_id,4,'0') as solicitud,
                                 mest_nombre as carrera
                    FROM " . $con->dbname . ".orden_pago opag INNER JOIN db_captacion.solicitud_inscripcion sins on sins.sins_id = opag.sins_id
                              INNER JOIN " . $con1->dbname . ".interesado inte on inte.int_id = sins.int_id
                              INNER JOIN " . $con2->dbname . ".persona per on per.per_id = inte.per_id
                              INNER JOIN " . $con3->dbname . ".modulo_estudio me on me.mest_id = sins.mest_id
                    WHERE opag.opag_id = :opag_id
                              AND opag.opag_estado_pago = :estado_pago
                              AND opag.opag_estado = :estado
                              AND sins.sins_estado = :estado
                              AND sins.sins_estado_logico = :estado
                              AND opag.opag_estado_logico = :estado
                              AND inte.int_estado = :estado
                              AND per.per_estado = :estado
                              AND me.mest_estado = :estado
                              AND inte.int_estado_logico = :estado
                              AND per.per_estado_logico = :estado
                              AND me.mest_estado_logico = :estado";
        }
        \app\models\Utilities::putMessageLogFile('sql:'.$sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_pago", $estado_pago, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarTransaccionbtnpago (Generar movimiento de transacción para botón de pago)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarTransaccionbtnpago($opag_id) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "tbbp_estado_logico";
        $btbpa_sql = "1";

        $param_sql .= ", tbbp_estado";
        $btbpa_sql .= ", 1";

        if (isset($opag_id)) {
            $param_sql .= ", opag_id";
            $btbpa_sql .= ", :opag_id";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".transaccion_botonpago_BP ($param_sql) VALUES($btbpa_sql)";
            $comando = $con->createCommand($sql);

            if (isset($opag_id))
                $comando->bindParam(':opag_id', $opag_id, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.transaccion_botonpago_BP');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultaCurso consulta de curso por nivel de interés y método de ingreso.
     * @author  Grace Viteri <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultaCurso($ming_id, $nint_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $estado_vigencia = 'A';

        $sql = "SELECT cur.cur_id, DATE_FORMAT(plec.plec_fecha_desde, '%d/%m/%Y') as fecha,
                       DATE_FORMAT(plec.plec_fecha_maxima_pago, '%d/%m/%Y') as fechapago
                FROM " . $con->dbname . ".curso cur INNER JOIN " . $con->dbname . ".periodo_lectivo plec on plec.plec_id = cur.plec_id
                WHERE  nint_id = :nint_id AND
                       ming_id = :ming_id AND
                       cur.cur_estado_vigencia = :estado_vigencia AND
                       cur.cur_estado_logico = :estado AND
                       cur.cur_estado = :estado AND
                       plec.plec_estado = :estado AND
                       plec.plec_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_vigencia", $estado_vigencia, \PDO::PARAM_STR);
        $comando->bindParam(":nint_id", $nint_id, \PDO::PARAM_INT);
        $comando->bindParam(":ming_id", $ming_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function encuentraAdmitido Verifica si ya está registrado como aspirante.
     * @author  Grace Viteri <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function encuentraAdmitido($int_id, $sins_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT asp.adm_id
                FROM " . $con->dbname . ".admitido asp
                WHERE asp.int_id = :int_id AND
                      asp.sins_id = :sins_id AND
                      asp.adm_estado = :estado AND
                      asp.adm_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function encuentraRol Verifica rol de la persona que está en la sesión.
     * @author  Grace Viteri <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function encuentraRol($per_id) {
        $con = \Yii::$app->db;
        $estado = 1;
        $rol_1 = 3;
        $rol_2 = 4;
        $rol_3 = 5;

        $sql = "SELECT
                     rol_id
                FROM " . $con->dbname . ".usuario usu
                INNER JOIN " . $con->dbname . ".usua_grol_eper ugrol on usu.usu_id = ugrol.usu_id
                INNER JOIN " . $con->dbname . ".grup_rol grol on ugrol.grol_id = grol.grol_id
                WHERE usu.per_id = :per_id AND
                      grol.rol_id in (:rol1,:rol2,:rol3) AND
                      usu.usu_estado_logico = :estado AND
                      ugrol.ugep_estado_logico = :estado AND
                      grol.grol_estado_logico = :estado AND
                      usu.usu_estado = :estado AND
                      ugrol.ugep_estado = :estado AND
                      grol.grol_estado = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":rol1", $rol_1, \PDO::PARAM_INT);
        $comando->bindParam(":rol2", $rol_2, \PDO::PARAM_INT);
        $comando->bindParam(":rol3", $rol_3, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarTransacxId (Botón de pago).
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarTransacxId($num_transaccion) {
        $con = \Yii::$app->db;
        $con1 = \Yii::$app->db_facturacion;
        $con2 = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT  opag.opag_id as orden,
                        opag.opag_total,
                        concat(per.per_pri_nombre, ' ',  per.per_seg_nombre, ' ', per.per_pri_apellido, ' ', per.per_seg_apellido) as nombres,
                        per.per_correo,
                        per.per_id,
                        inte.int_id,
                        opag.sins_id,
                        opag.opag_estado_pago,
                        ite.ite_descripcion as item
                FROM " . $con1->dbname . ".transaccion_botonpago_BP tbpa INNER JOIN " . $con1->dbname . ".orden_pago opag on opag.opag_id = tbpa.opag_id
                      INNER JOIN " . $con2->dbname . ".solicitud_inscripcion sins on sins.sins_id = opag.sins_id
                      INNER JOIN " . $con1->dbname . ".item_metodo_unidad imni on (imni.ming_id = sins.ming_id and imni.nint_id = sins.nint_id)
                      INNER JOIN " . $con1->dbname . ".item ite on ite.ite_id = imni.ite_id
                      INNER JOIN " . $con2->dbname . ".interesado inte on inte.int_id = sins.int_id
                      INNER JOIN " . $con2->dbname . ".pre_interesado pint on pint.pint_id = inte.pint_id
                      INNER JOIN " . $con->dbname . ".persona per on per.per_id = pint.per_id
                WHERE tbpa.tbbp_id = :num_transaccion AND
                      tbpa.tbbp_estado_logico = :estado AND
                      opag.opag_estado_logico = :estado AND
                      ite.ite_estado_logico = :estado AND
                      imni.imni_estado_logico = :estado AND
                      sins.sins_estado_logico = :estado AND
                      inte.int_estado_logico = :estado AND
                      pint.pint_estado_logico = :estado AND
                      per.per_estado_logico = :estado AND
                      tbpa.tbbp_estado = :estado AND
                      opag.opag_estado = :estado AND
                      ite.ite_estado = :estado AND
                      imni.imni_estado = :estado AND
                      sins.sins_estado = :estado AND
                      inte.int_estado = :estado AND
                      pint.pint_estado = :estado AND
                      per.per_estado = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":num_transaccion", $num_transaccion, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function actualizaTransaccionbtnpago (Actualiza información del pago en línea retornado del banco)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaTransaccionbtnpago($tbpa_id, $tbpa_num_tarjeta, $tbpa_codigo_autorizacion, $tbpa_resultado_autorizacion, $tbpa_codigo_error, $tbpa_error_mensaje) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".transaccion_botonpago_BP
                SET tbbp_num_tarjeta = :tbpa_num_tarjeta,
                    tbbp_codigo_autorizacion = :tbpa_codigo_autorizacion,
                    tbbp_resultado_autorizacion = :tbpa_resultado_autorizacion,
                    tbbp_codigo_error = :tbpa_codigo_error,
                    tbbp_error_mensaje = :tbpa_error_mensaje,
                    tbbp_fecha_modificacion = :fecha_modificacion
                WHERE tbbp_id = :tbpa_id AND
                      tbbp_estado =:estado AND
                      tbbp_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":tbpa_id", $tbpa_id, \PDO::PARAM_INT);
        $comando->bindParam(":tbpa_num_tarjeta", $tbpa_num_tarjeta, \PDO::PARAM_STR);
        $comando->bindParam(":tbpa_codigo_autorizacion", $tbpa_codigo_autorizacion, \PDO::PARAM_STR);
        $comando->bindParam(":tbpa_resultado_autorizacion", $tbpa_resultado_autorizacion, \PDO::PARAM_STR);
        $comando->bindParam(":tbpa_codigo_error", $tbpa_codigo_error, \PDO::PARAM_STR);
        $comando->bindParam(":tbpa_error_mensaje", $tbpa_error_mensaje, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
        $response = $comando->execute();

        return $response;
    }

    /**
     * Function consultarSolicitxOrd (Se verifica el estado de la orden de pago)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarSolicitxOrd($opag_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $estado_pago = 'P';

        $sql = "SELECT opag.sins_id as solicitud
                FROM " . $con->dbname . ".orden_pago opag
                WHERE opag.opag_id = :opag_id AND
                      opag.opag_estado_pago = :estado_pago AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_pago", $estado_pago, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarImagenpago (Se obtiene nombre de la imagen de pago mediante el id de solicitud)
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   sser_id (id de solicitud)
     * @return  dreg_imagen (nombre de la imagen)
     */
    public function consultarImagenpago($sser_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT orp.opag_id as id_pago,
                icpr_imagen as imagen_pago
                FROM  " . $con->dbname . ".orden_pago orp
                INNER JOIN " . $con->dbname . ".info_carga_prepago icp on orp.opag_id = icp.opag_id
                WHERE
                      orp.sins_id = :sser_id AND
                      icp.icpr_resultado = 'AP' AND
                      orp.opag_estado =:estado AND
                      orp.opag_estado_logico = :estado AND
                      icp.icpr_estado =:estado AND
                      icp.icpr_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sser_id", $sser_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function listarPagoscargados
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (información de las solicitudes con orden de pago y pagados.)
     */
    public function listarPagoscargados($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search ) AND ";
            // $str_search .= "metodo like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
            if ($arrFiltro['f_estado'] != "T") {
                $str_search .= "opag.opag_estado_pago = :f_estado AND ";
            }
        } else {
            $columnsAdd = "sins.sins_id as solicitud_id,
            per.per_id as persona,
            per.per_pri_nombre as per_pri_nombre,
            per.per_seg_nombre as per_seg_nombre,
            per.per_pri_apellido as per_pri_apellido,
            per.per_seg_apellido as per_seg_apellido,";
        }
        $sql = "SELECT  lpad(ifnull(sins.num_solicitud,sins.sins_id),9,'0') as solicitud,
                        sins.sins_fecha_solicitud,
                        per.per_id ,
                        per.per_cedula identificacion,
                        concat(per.per_pri_apellido) apellidos,
                        concat(per.per_pri_nombre) nombres,
                        uaca_descripcion nivel,
                        ifnull((select ming.ming_alias
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as metodo,
                        ifnull(opag.opag_id,'') orden,
                        opag.opag_estado_pago estado,
                        per.per_correo as correo,
                        $columnsAdd
                        (case opag.opag_estado_pago when 'P' then 'Pendiente' when 'S' then 'Pagada' end) as estado_desc_pago,
                        (case opag.opag_estado_pago when 'P' then
                            ifnull((SELECT icp.icpr_imagen
                                    FROM " . $con2->dbname . ".info_carga_prepago icp
                                    where icp.icpr_id = (select max(icpr_id) from " . $con2->dbname . ".info_carga_prepago icp
                                                        where icp.opag_id = opag.opag_id and icp.icpr_estado = :estado
                                                        and icp.icpr_estado_logico = :estado)), 'N/I')
                            when 'S' then (SELECT icp.icpr_imagen
                                            FROM " . $con2->dbname . ".info_carga_prepago icp
                                            where icp.opag_id = opag.opag_id and icp.icpr_resultado = 'AP'
                                                and icp.icpr_estado = :estado and icp.icpr_estado_logico = :estado) end) as imagen_pago
                FROM " . $con->dbname . ".solicitud_inscripcion sins INNER JOIN " . $con3->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                     INNER JOIN " . $con->dbname . ".interesado inte on sins.int_id = inte.int_id
                     INNER JOIN " . $con1->dbname . ".persona per on inte.per_id = per.per_id
                     LEFT JOIN " . $con2->dbname . ".orden_pago opag on sins.sins_id = opag.sins_id
                WHERE $str_search
                      exists (select icpr.opag_id
                              from " . $con2->dbname . ".info_carga_prepago icpr
                              where icpr.opag_id = opag.opag_id
                                    and icpr.fpag_id in (4,5)
                                    and icpr.icpr_estado = :estado
                                    and icpr.icpr_estado_logico = :estado) AND
                      sins.sins_estado = :estado AND
                      sins.sins_estado_logico = :estado AND
                      uaca.uaca_estado = :estado AND
                      uaca.uaca_estado_logico = :estado AND
                      inte.int_estado_logico = :estado AND
                      inte.int_estado = :estado AND
                      per.per_estado = :estado AND
                      per.per_estado_logico = :estado AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado
                ORDER BY sins.sins_fecha_solicitud desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $f_estado = $arrFiltro["f_estado"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['f_estado'] != "T") {
                $comando->bindParam(":f_estado", $f_estado, \PDO::PARAM_STR);
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
     * Function insertarOrdenpago (Crea la orden de pago)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarOrdenpago($sins_id, $sgen_id, $opag_subtotal, $opag_iva, $opag_total, $opag_estado_pago, $usuario_ingreso, $opag_fecha_pago_total = null, $valor_pago_total = null) {
        $con = \Yii::$app->db_facturacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $fecha_generacion = date(Yii::$app->params["dateTimeByDefault"]);
        if (empty($valor_pago_total)) {
            $valor_pagado = 0;
        } else {
            $valor_pagado = $valor_pago_total;
        }

        $param_sql = "opag_estado_logico";
        $bopago_sql = "1";

        $param_sql .= ", opag_estado";
        $bopago_sql .= ", 1";


        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bopago_sql .= ", :sins_id";
        }

        if (isset($sgen_id)) {
            $param_sql .= ", sbpa_id";
            $bopago_sql .= ", :sbpa_id";
        }

        if (isset($fecha_generacion)) {
            $param_sql .= ", opag_fecha_generacion";
            $bopago_sql .= ", :opag_fecha_generacion";
        }

        if (isset($opag_subtotal)) {
            $param_sql .= ", opag_subtotal";
            $bopago_sql .= ", :opag_subtotal";
        }

        if (isset($opag_iva)) {
            $param_sql .= ", opag_iva";
            $bopago_sql .= ", :opag_iva";
        }

        if (isset($opag_total)) {
            $param_sql .= ", opag_total";
            $bopago_sql .= ", :opag_total";
        }

        if (isset($valor_pagado)) {
            $param_sql .= ", opag_valor_pagado";
            $bopago_sql .= ", :opag_valor_pagado";
        }

        if (isset($opag_estado_pago)) {
            $param_sql .= ", opag_estado_pago";
            $bopago_sql .= ", :opag_estado_pago";
        }

        if (isset($usuario_ingreso)) {
            $param_sql .= ", opag_usu_ingreso";
            $bopago_sql .= ", :opag_usu_ingreso";
        }
        if (isset($opag_fecha_pago_total)) {
            $param_sql .= ", opag_fecha_pago_total";
            $bopago_sql .= ", :opag_fecha_pago_total";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".orden_pago ($param_sql) VALUES($bopago_sql)";
            $comando = $con->createCommand($sql);

            if (!empty($sins_id)) {
                if (isset($sins_id))
                    $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);
            }

            if (!empty($sgen_id)) {
                if (isset($sgen_id))
                    $comando->bindParam(':sbpa_id', $sgen_id, \PDO::PARAM_INT);
            }

            if (isset($fecha_generacion))
                $comando->bindParam(':opag_fecha_generacion', $fecha_generacion, \PDO::PARAM_STR);

            if (isset($opag_subtotal))
                $comando->bindParam(':opag_subtotal', $opag_subtotal, \PDO::PARAM_STR);

            if (isset($opag_iva))
                $comando->bindParam(':opag_iva', $opag_iva, \PDO::PARAM_STR);

            if (isset($opag_total))
                $comando->bindParam(':opag_total', $opag_total, \PDO::PARAM_STR);

            if (isset($valor_pagado))
                $comando->bindParam(':opag_valor_pagado', $valor_pagado, \PDO::PARAM_STR);

            if (isset($opag_estado_pago))
                $comando->bindParam(':opag_estado_pago', $opag_estado_pago, \PDO::PARAM_STR);

            if (isset($usuario_ingreso))
                $comando->bindParam(':opag_usu_ingreso', $usuario_ingreso, \PDO::PARAM_INT);

            if (!empty($opag_fecha_pago_total)) {
                if (isset($opag_fecha_pago_total))
                    $comando->bindParam(':opag_fecha_pago_total', $opag_fecha_pago_total, \PDO::PARAM_STR);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.orden_pago');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function insertarDesglosepago (Crea el desglose de la orden de pago, en cuantas cuotas se pagaría)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarDesglosepago($opag_id, $ite_id, $dpag_subtotal, $dpag_iva, $dpag_total, $dpag_fecha_inicio, $dpag_fecha_final, $dpag_estado_pago, $usuario_ingreso) {
        $con = \Yii::$app->db_facturacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "dpag_estado_logico";
        $bdpago_sql = "1";

        $param_sql .= ", dpag_estado";
        $bdpago_sql .= ", 1";

        if (isset($opag_id)) {
            $param_sql .= ", opag_id";
            $bdpago_sql .= ", :opag_id";
        }

        if (isset($dpag_subtotal)) {
            $param_sql .= ", dpag_subtotal";
            $bdpago_sql .= ", :dpag_subtotal";
        }

        if (isset($dpag_iva)) {
            $param_sql .= ", dpag_iva";
            $bdpago_sql .= ", :dpag_iva";
        }

        if (isset($dpag_total)) {
            $param_sql .= ", dpag_total";
            $bdpago_sql .= ", :dpag_total";
        }

        if (isset($dpag_fecha_inicio)) {
            $param_sql .= ", dpag_fecha_ini_vigencia";
            $bdpago_sql .= ", :dpag_fecha_ini_vigencia";
        }

        if (isset($dpag_fecha_final)) {
            $param_sql .= ", dpag_fecha_fin_vigencia";
            $bdpago_sql .= ", :dpag_fecha_fin_vigencia";
        }

        if (isset($dpag_estado_pago)) {
            $param_sql .= ", dpag_estado_pago";
            $bdpago_sql .= ", :dpag_estado_pago";
        }

        if (isset($usuario_ingreso)) {
            $param_sql .= ", dpag_usu_ingreso";
            $bdpago_sql .= ", :dpag_usu_ingreso";
        }

        if (isset($ite_id)) {
            $param_sql .= ", ite_id";
            $bdpago_sql .= ", :ite_id";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".desglose_pago ($param_sql) VALUES($bdpago_sql)";
            $comando = $con->createCommand($sql);


            if (isset($opag_id))
                $comando->bindParam(':opag_id', $opag_id, \PDO::PARAM_INT);

            if (isset($dpag_subtotal))
                $comando->bindParam(':dpag_subtotal', $dpag_subtotal, \PDO::PARAM_STR);

            if (isset($dpag_iva))
                $comando->bindParam(':dpag_iva', $dpag_iva, \PDO::PARAM_STR);

            if (isset($dpag_total))
                $comando->bindParam(':dpag_total', $dpag_total, \PDO::PARAM_STR);

            if (isset($dpag_fecha_inicio))
                $comando->bindParam(':dpag_fecha_ini_vigencia', $dpag_fecha_inicio, \PDO::PARAM_STR);

            if (!empty($dpag_fecha_final)) {
                if (isset($dpag_fecha_final))
                    $comando->bindParam(':dpag_fecha_fin_vigencia', $dpag_fecha_final, \PDO::PARAM_STR);
            }

            if (isset($dpag_estado_pago)){
                $comando->bindParam(':dpag_estado_pago', $dpag_estado_pago, \PDO::PARAM_STR);
            }

            if (isset($usuario_ingreso)) {
                $comando->bindParam(':dpag_usu_ingreso', $usuario_ingreso, \PDO::PARAM_INT);
            }

            if (isset($ite_id)) {
                $comando->bindParam(':ite_id', $ite_id, \PDO::PARAM_INT);
            }
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.desglose_pago');
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            return 0;
        }
    }

    /**
     * Function listarPagosolicitud
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (información de las órdenes de pago pendientes y pagadas.)
     */
    public function listarPagosolicitud($arrFiltro = array(), $resp_gruporol, $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search ) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
            if ($arrFiltro['f_estado'] != "T") {
                $str_search .= "opag.opag_estado_pago = :f_estado AND ";
            }
        } else {
            $columnsAdd = "sins.sins_id as solicitud_id,
          per.per_id as persona,
          per.per_pri_nombre as per_pri_nombre,
          per.per_seg_nombre as per_seg_nombre,
          per.per_pri_apellido as per_pri_apellido,
          per.per_seg_apellido as per_seg_apellido,";
        }
        $sql = "SELECT  lpad(ifnull(sins.num_solicitud,sins.sins_id),9,'0') as solicitud, sins.sins_id,
                        sins.sins_fecha_solicitud,
                        per.per_id,
                        per.per_cedula identificacion,
                        concat(per.per_pri_apellido) apellidos,
                        concat(per.per_pri_nombre) nombres,
                        uaca_nombre nivel,
                        ifnull((select ming.ming_alias
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as metodo,
                        ifnull(opag.opag_id,'') orden,
                        opag.opag_estado_pago estado,
                        per.per_correo as correo,
                        $columnsAdd
                        (case opag.opag_estado_pago when 'P' then 'Pendiente' when 'S' then 'Pagada' end) as estado_desc_pago";
        if ($resp_gruporol != "") {//rpfa_imagen
            $sql .= ", $resp_gruporol   as rol";
        }

        $sql .= " FROM " . $con->dbname . ".solicitud_inscripcion sins
                     INNER JOIN " . $con3->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                     INNER JOIN " . $con->dbname . ".interesado inte on sins.int_id = inte.int_id
                     INNER JOIN " . $con1->dbname . ".persona per on inte.per_id = per.per_id
                     INNER JOIN " . $con2->dbname . ".orden_pago opag on sins.sins_id = opag.sins_id
                WHERE
                      $str_search
                      sins.sins_estado = :estado AND
                      sins.sins_estado_logico = :estado AND
                      uaca.uaca_estado = :estado AND
                      uaca.uaca_estado_logico = :estado AND
                      inte.int_estado_logico = :estado AND
                      inte.int_estado = :estado AND
                      per.per_estado = :estado AND
                      per.per_estado_logico = :estado AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado AND
                      opag.opag_estado = :estado
                ORDER BY sins.sins_fecha_solicitud desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $f_estado = $arrFiltro["f_estado"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['f_estado'] != "T") {
                $comando->bindParam(":f_estado", $f_estado, \PDO::PARAM_STR);
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
     * Function consultaOrdenPago consulta de orden de pago por solicitud.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultaOrdenPago($sins_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT opag.opag_id, opag_estado_pago, dpag_id
                FROM " . $con->dbname . ".orden_pago opag INNER JOIN " . $con->dbname . ".desglose_pago dpag
                    ON dpag.opag_id = opag.opag_id
                WHERE  opag.sins_id = :sins_id AND
                       opag_estado_logico = :estado AND
                       opag_estado = :estado AND
                       dpag_estado_logico = :estado AND
                       dpag_estado = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarOrdenAnulada (Graba la orden de pago y solicitud anulada y detalle de anulación.)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarOrdenAnulada($opag_id, $sins_id, $observacion, $usuario) {
        $con = \Yii::$app->db_facturacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "opan_estado_logico";
        $bdpago_sql = "1";

        $param_sql .= ", opan_estado";
        $bdpago_sql .= ", 1";

        if (isset($opag_id)) {
            $param_sql .= ", opag_id";
            $bdpago_sql .= ", :opag_id";
        }

        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bdpago_sql .= ", :sins_id";
        }

        if (isset($observacion)) {
            $param_sql .= ", opan_observacion";
            $bdpago_sql .= ", :opan_observacion";
        }

        if (isset($usuario)) {
            $param_sql .= ", opan_usu_anula";
            $bdpago_sql .= ", :opan_usu_anula";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".orden_pago_anulada ($param_sql) VALUES($bdpago_sql)";
            $comando = $con->createCommand($sql);

            if (isset($opag_id)) {
                $comando->bindParam(':opag_id', $opag_id, \PDO::PARAM_INT);
            }
            if (isset($sins_id)) {
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);
            }
            if (isset($observacion)) {
                $comando->bindParam(':opan_observacion', $observacion, \PDO::PARAM_STR);
            }
            if (isset($usuario)) {
                $comando->bindParam(':opan_usu_anula', $usuario, \PDO::PARAM_INT);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.orden_pago_anulada');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function insertarSolicDscto (Crea la solicitud descuento)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function insertarSolicDscto($sins_id, $ddit_id, $sdes_precio, $sdes_porcentaje, $sdes_valor) {
        $con = \Yii::$app->db_facturacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "sdes_estado_logico";
        $bdsoldes_sql = "1";

        $param_sql .= ", sdes_estado";
        $bdsoldes_sql .= ", 1";

        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bdsoldes_sql .= ", :sins_id";
        }

        if (isset($ddit_id)) {
            $param_sql .= ", ddit_id";
            $bdsoldes_sql .= ", :ddit_id";
        }

        if (isset($sdes_precio)) {
            $param_sql .= ", sdes_precio";
            $bdsoldes_sql .= ", :sdes_precio";
        }

        if (isset($sdes_porcentaje)) {
            $param_sql .= ", sdes_porcentaje";
            $bdsoldes_sql .= ", :sdes_porcentaje";
        }

        if (isset($sdes_valor)) {
            $param_sql .= ", sdes_valor";
            $bdsoldes_sql .= ", :sdes_valor";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".solicitud_descuento ($param_sql) VALUES($bdsoldes_sql)";
            $comando = $con->createCommand($sql);
            if (isset($sins_id))
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);

            if (isset($ddit_id))
                $comando->bindParam(':ddit_id', $ddit_id, \PDO::PARAM_INT);

            if (isset($sdes_precio))
                $comando->bindParam(':sdes_precio', $sdes_precio, \PDO::PARAM_INT);

            if (isset($sdes_porcentaje))
                $comando->bindParam(':sdes_porcentaje', $sdes_porcentaje, \PDO::PARAM_INT);

            if (isset($sdes_valor))
                $comando->bindParam(':sdes_valor', $sdes_valor, \PDO::PARAM_INT);

            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.solicitud_descuento');
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            return 0;
        }
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarDtosFactDoct($data) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $usuingreso = @Yii::$app->session->get("PB_iduser");
            $rpfa_revisado = 1;
            $rpfa_fecha_documento = ($data['rpfa_fecha_documento'] != '') ? date(Yii::$app->params["dateTimeByDefault"], strtotime($data['rpfa_fecha_documento'])) : NULL;

            $sql = "INSERT INTO " . $con->dbname . ".registro_pago_factura
                (sins_id,rpfa_num_solicitud,rpfa_valor_documento,rpfa_fecha_documento,
                rpfa_numero_documento,rpfa_imagen,rpfa_revisado,rpfa_fecha_transaccion,
                rpfa_estado,rpfa_estado_logico,rpfa_usuario_transaccion) VALUES
                (:sins_id,:rpfa_num_solicitud,:rpfa_valor_documento,:rpfa_fecha_documento,
                :rpfa_numero_documento,:rpfa_imagen,:rpfa_revisado,CURRENT_TIMESTAMP(),1,1,:rpfa_usuario_transaccion)";

            $command = $con->createCommand($sql);
            $command->bindParam(":sins_id", $data["sins_id"], \PDO::PARAM_INT);
            $command->bindParam(":rpfa_num_solicitud", $data["rpfa_num_solicitud"], \PDO::PARAM_STR);
            $command->bindParam(":rpfa_valor_documento", $data["rpfa_valor_documento"], \PDO::PARAM_STR);
            $command->bindParam(":rpfa_fecha_documento", $data["rpfa_fecha_documento"], \PDO::PARAM_STR);
            $command->bindParam(":rpfa_numero_documento", $data["rpfa_numero_documento"], \PDO::PARAM_STR);
            $command->bindParam(":rpfa_imagen", $data["documento"], \PDO::PARAM_STR);
            $command->bindParam(":rpfa_revisado", $rpfa_revisado, \PDO::PARAM_STR);
            $command->bindParam(":rpfa_usuario_transaccion", $usuingreso, \PDO::PARAM_STR);

            $command->execute();

            if ($trans !== null)
                $trans->commit();

            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["data"] = $rawData;
            return $arroout;
        } catch (Exception $ex) {
            fclose($handle);
            if ($trans !== null)
                $trans->rollback();

            $arroout["status"] = FALSE;
            $arroout["error"] = $ex->getCode();
            $arroout["message"] = $ex->getMessage();
            $arroout["data"] = $rawData;
            return $arroout;
        }
    }

    /**     * **
     * Function Obtiene grol_id a partir de Id Persona y Empresa
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function consultarInteresadoPersona($sins_id) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_facturacion;
        $sql = "SELECT B.per_id,C.opag_total
                    FROM " . $con->dbname . ".solicitud_inscripcion A
                            INNER JOIN " . $con->dbname . ".interesado B ON A.int_id=B.int_id
                            INNER JOIN " . $con2->dbname . ".orden_pago C ON A.sins_id=C.sins_id
                WHERE A.sins_id=:sins_id AND A.sins_estado=1;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    public static function consultarRutaFile($sins_id) {
        $con = \Yii::$app->db_facturacion;
        $sql = "SELECT rpfa_imagen Ruta "
                . " FROM " . $con->dbname . ".registro_pago_factura "
                . " WHERE sins_id=:sins_id AND rpfa_estado=1;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //Falso si no Existe
        return $rawData; //Si Existe en la Tabla
    }

    /**
     * Function listarPagoscargados
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (información de las solicitudes con orden de pago y pagados.)
     */
    public function listarPagoscargadosexcel($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search ) AND ";
            //$str_search .= "ming.ming_descripcion like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
            if ($arrFiltro['f_estado'] != "T") {
                $str_search .= "opag.opag_estado_pago = :f_estado AND ";
            }
        } else {
            $columnsAdd = "sins.sins_id as solicitud_id,
            per.per_id as persona,
            per.per_pri_nombre as per_pri_nombre,
            per.per_seg_nombre as per_seg_nombre,
            per.per_pri_apellido as per_pri_apellido,
            per.per_seg_apellido as per_seg_apellido,";
        }
        $sql = "SELECT  lpad(sins.sins_id,4,'0') as solicitud,
                        sins.sins_fecha_solicitud,
                        per.per_cedula identificacion,
                        concat(per.per_pri_apellido) apellidos,
                        concat(per.per_pri_nombre) nombres,
                        uaca_descripcion nivel,
                        -- ming_descripcion metodo,
                        ifnull((select ming.ming_descripcion
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as metodo,
                        (case opag.opag_estado_pago when 'P' then 'Pendiente' when 'S' then 'Pagada' end) as estado_desc_pago
                FROM " . $con->dbname . ".solicitud_inscripcion sins INNER JOIN " . $con3->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                     -- INNER JOIN " . $con->dbname . ".metodo_ingreso ming on ming.ming_id = sins.ming_id
                     INNER JOIN " . $con->dbname . ".interesado inte on sins.int_id = inte.int_id
                     INNER JOIN " . $con1->dbname . ".persona per on inte.per_id = per.per_id
                     LEFT JOIN " . $con2->dbname . ".orden_pago opag on sins.sins_id = opag.sins_id
                WHERE $str_search
                      exists (select icpr.opag_id
                              from " . $con2->dbname . ".info_carga_prepago icpr
                              where icpr.opag_id = opag.opag_id
                                    and icpr.fpag_id in (4,5)
                                    and icpr.icpr_estado = :estado
                                    and icpr.icpr_estado_logico = :estado) AND
                      sins.sins_estado = :estado AND
                      sins.sins_estado_logico = :estado AND
                      uaca.uaca_estado = :estado AND
                      uaca.uaca_estado_logico = :estado AND
                      -- ming.ming_estado = :estado AND
                      -- ming.ming_estado_logico = :estado AND
                      inte.int_estado_logico = :estado AND
                      inte.int_estado = :estado AND
                      per.per_estado = :estado AND
                      per.per_estado_logico = :estado AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado
                ORDER BY sins.sins_fecha_solicitud desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $f_estado = $arrFiltro["f_estado"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['f_estado'] != "T") {
                $comando->bindParam(":f_estado", $f_estado, \PDO::PARAM_STR);
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
     * Function listarPagosolicitudExcel
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (información de las órdenes de pago pendientes y pagadas.)
     */
    public function listarPagosolicitudExcel($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
       // $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search ) AND ";
            //$str_search .= "ming.ming_descripcion like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
            if ($arrFiltro['f_estado'] != "T") {
                $str_search .= "opag.opag_estado_pago = :f_estado AND ";
            }
        }
        $sql = "SELECT  lpad(sins.sins_id,4,'0') as solicitud,
                        sins.sins_fecha_solicitud,
                        per.per_cedula identificacion,
                        concat(per.per_pri_apellido) apellidos,
                        concat(per.per_pri_nombre) nombres,
                        uaca_nombre nivel,
                        -- ming_descripcion metodo,
                        ifnull((select ming.ming_descripcion
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as metodo,
                        (case opag.opag_estado_pago when 'P' then 'Pendiente' when 'S' then 'Pagada' end) as estado_desc_pago
                FROM " . $con->dbname . ".solicitud_inscripcion sins
                     INNER JOIN " . $con3->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                     -- INNER JOIN " . $con->dbname . ".metodo_ingreso ming on ming.ming_id = sins.ming_id
                     INNER JOIN " . $con->dbname . ".interesado inte on sins.int_id = inte.int_id
                     INNER JOIN " . $con1->dbname . ".persona per on inte.per_id = per.per_id
                     INNER JOIN " . $con2->dbname . ".orden_pago opag on sins.sins_id = opag.sins_id
                WHERE
                      $str_search
                      sins.sins_estado = :estado AND
                      sins.sins_estado_logico = :estado AND
                      uaca.uaca_estado = :estado AND
                      uaca.uaca_estado_logico = :estado AND
                      -- ming.ming_estado = :estado AND
                      -- ming.ming_estado_logico = :estado AND
                      inte.int_estado_logico = :estado AND
                      inte.int_estado = :estado AND
                      per.per_estado = :estado AND
                      per.per_estado_logico = :estado AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado AND
                      opag.opag_estado = :estado
                ORDER BY sins.sins_fecha_solicitud desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $f_estado = $arrFiltro["f_estado"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['f_estado'] != "T") {
                $comando->bindParam(":f_estado", $f_estado, \PDO::PARAM_STR);
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
     * Function listarSolicitudesadm
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (información de las solicitudes con/sin orden de pago.)
     */
    public function listarSolicitudesadmexcel($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_pago = 'P';
        $columnsAdd = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search ) AND ";
            //$str_search .= "ming.ming_descripcion like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
        } else {
            $columnsAdd = "sins.sins_id as solicitud_id,
          per.per_id as persona,
          per.per_pri_nombre as per_pri_nombre,
          per.per_seg_nombre as per_seg_nombre,
          per.per_pri_apellido as per_pri_apellido,
          per.per_seg_apellido as per_seg_apellido,";
        }
        $sql = "SELECT  lpad(sins.sins_id,4,'0') as solicitud,
                        sins.sins_fecha_solicitud,
                        per.per_cedula identificacion,
                        concat(per.per_pri_apellido) apellidos,
                        concat(per.per_pri_nombre) nombres,
                        uaca_descripcion nivel,
                        -- ming_descripcion metodo,
                        ifnull((select ming.ming_descripcion
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as metodo,
                        (case ifnull((select icpr_id
                                      from " . $con2->dbname . ".info_carga_prepago icp
                                      where icp.opag_id = opag.opag_id
                                            and icp.icpr_estado = :estado
                                            and icp.icpr_estado_logico = :estado),'P') when 'P' then 'Pendiente' else 'No Aprobada' end) as estado_desc_pago
                FROM " . $con->dbname . ".solicitud_inscripcion sins INNER JOIN " . $con3->dbname . ".unidad_academica uaca on uaca.uaca_id = sins.uaca_id
                     -- INNER JOIN " . $con->dbname . ".metodo_ingreso ming on ming.ming_id = sins.ming_id
                     INNER JOIN " . $con->dbname . ".interesado inte on sins.int_id = inte.int_id
                     INNER JOIN " . $con1->dbname . ".persona per on inte.per_id = per.per_id
                     INNER JOIN " . $con2->dbname . ".orden_pago opag on sins.sins_id = opag.sins_id
                WHERE
                      $str_search
                      ( sins.rsin_id <> 4 and opag_estado_pago = 'P') AND
                      sins.sins_estado = :estado AND
                      sins.sins_estado_logico = :estado AND
                      uaca.uaca_estado = :estado AND
                      uaca.uaca_estado_logico = :estado AND
                      -- ming.ming_estado = :estado AND
                      -- ming.ming_estado_logico = :estado AND
                      inte.int_estado_logico = :estado AND
                      inte.int_estado = :estado AND
                      per.per_estado = :estado AND
                      per.per_estado_logico = :estado AND
                      opag.opag_estado = :estado AND
                      opag.opag_estado_logico = :estado AND
                      (opag.opag_estado_pago = :estado_pago and
                      not exists(select icpr_id from " . $con2->dbname . ".info_carga_prepago icp
                                           where icp.opag_id = opag.opag_id
                                                 and icp.icpr_estado = :estado
                                                 and icp.icpr_estado_logico = :estado)) OR
                      (opag.opag_estado_pago = :estado_pago and exists
                                        (select icpr_resultado
                                         from " . $con2->dbname . ".info_carga_prepago
                                         where icpr_id = (select max(icpr_id)
                                                          from " . $con2->dbname . ".info_carga_prepago icp
                                                          where icp.opag_id = opag.opag_id
                                                                and icp.icpr_estado = :estado
                                                                and icp.icpr_estado_logico = :estado)
                                            and icpr_resultado = 'RE'))
                ORDER BY sins.sins_fecha_solicitud desc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":estado_pago", $estado_pago, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
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
     * Function consultarPrecioXotroItem consulta de precio de otros items como saldos.
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarOpagIdByCedula($cedula = null){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql=  "
                SELECT
                    opag.opag_id as id
                FROM
                    db_facturacion.orden_pago as opag
                    join db_captacion.solicitud_inscripcion as sins on sins.sins_id = opag.sins_id
                    join db_captacion.interesado as inte on inte.int_id = sins.int_id
                    join db_asgard.persona as per on per.per_id = inte.per_id
                WHERE
                    per.per_cedula = :cedula and
                    sins.sins_estado = :estado and
                    sins.sins_estado_logico = :estado and
                    inte.int_estado = :estado and
                    inte.int_estado_logico =:estado and
                    per.per_estado = :estado and
                    per.per_estado_logico = :estado
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData['id'];
    }
    /**
     * Function consultarPrecioXotroItem consulta de precio de otros items como saldos.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarPrecioXotroItem($uaca_id, $mod_id, $ming_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT i.ite_id id, i.ite_nombre name
                FROM " . $con->dbname . ".otros_item_metodo_nivel oi inner join " . $con->dbname . ".item i
                on i.ite_id = oi.ite_id
                where uaca_id = :uaca_id
                        and mod_id = :mod_id
                        and ifnull(ming_id,0) = :ming_id
                        and oimn_estado = :estado
                        and oimn_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":ming_id", $ming_id, \PDO::PARAM_INT);

        \app\models\Utilities::putMessageLogFile('sql:'.$sql);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function actualizaOrdenpagoadmision (Actualiza el total de una orden segun su solicitud.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaOrdenpagoadmision($sins_id, $opag_subtotal, $opag_total, $opag_usu_modifica) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $opag_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".orden_pago
                SET opag_subtotal = :opag_subtotal,
                    opag_total = :opag_total,
                    opag_fecha_pago_total = :opag_fecha_pago_total,
                    opag_fecha_modificacion = :opag_fecha_modificacion,
                    opag_usu_modifica = :opag_usu_modifica
                WHERE sins_id = :sins_id AND
                      opag_estado =:estado AND
                      opag_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":opag_subtotal", $opag_subtotal, \PDO::PARAM_STR);
        $comando->bindParam(":opag_total", $opag_total, \PDO::PARAM_STR);
        $comando->bindParam(":opag_fecha_modificacion", $opag_fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":opag_usu_modifica", $opag_usu_modifica, \PDO::PARAM_STR);
        $response = $comando->execute();

        return $response;
    }

    /**
     * Function consultarImagenpagoexiste
     * @author  Giovanni Vergara <analista.desarrollo02@uteg.edu.ec>
     * @property integer
     * @return
     */


    public function consultarImagenpagoexiste($opag_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "
                SELECT
                         count(*) as existe_imagen
                FROM " . $con->dbname . ".info_carga_prepago
                WHERE opag_id = :opag_id AND
                     icpr_estado = :estado AND
                     icpr_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}
