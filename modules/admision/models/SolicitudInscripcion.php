<?php

namespace app\modules\admision\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "solicitud_inscripcion".
 *
 * @property int $sins_id
 * @property int $int_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $ming_id
 * @property int $eaca_id
 * @property int $mest_id
 * @property int $emp_id
 * @property string $num_solicitud
 * @property int $rsin_id
 * @property string $sins_fecha_solicitud
 * @property string $sins_fecha_preaprobacion
 * @property string $sins_fecha_aprobacion
 * @property string $sins_fecha_reprobacion
 * @property string $sins_fecha_prenoprobacion
 * @property string $sins_preobservacion
 * @property string $sins_observacion
 * @property string $sins_observacion_creasolicitud
 * @property string $sins_observacion_revisa
 * @property string $sins_beca
 * @property int $cemp_id
 * @property int $sins_usuario_preaprueba
 * @property int $sins_usuario_aprueba
 * @property int $sins_usuario_ingreso
 * @property int $sins_usuario_modifica
 * @property string $sins_estado
 * @property string $sins_fecha_creacion
 * @property string $sins_fecha_modificacion
 * @property string $sins_estado_logico
 *
 * @property MatriculadosReprobado[] $matriculadosReprobados
 * @property SolicitudDatosFactura[] $solicitudDatosFacturas
 * @property ConvenioEmpresa $cemp
 * @property Interesado $int
 * @property ResSolInscripcion $rsin
 * @property SolicitudRechazada[] $solicitudRechazadas
 * @property SolicitudinsDocumento[] $solicitudinsDocumentos
 */
class Solicitudinscripcion extends \yii\db\ActiveRecord {

    public static $arr_DNI = array("1" => "CED", "2" => "RUC", "3" => "PASS");

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'solicitud_inscripcion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['int_id', 'rsin_id', 'sins_estado', 'sins_estado_logico'], 'required'],
            [['int_id', 'uaca_id', 'mod_id', 'ming_id', 'eaca_id', 'mest_id', 'emp_id', 'rsin_id', 'cemp_id', 'sins_usuario_preaprueba', 'sins_usuario_aprueba', 'sins_usuario_ingreso', 'sins_usuario_modifica'], 'integer'],
            [['sins_fecha_solicitud', 'sins_fecha_preaprobacion', 'sins_fecha_aprobacion', 'sins_fecha_reprobacion', 'sins_fecha_prenoprobacion', 'sins_fecha_creacion', 'sins_fecha_modificacion'], 'safe'],
            [['num_solicitud'], 'string', 'max' => 10],
            [['sins_preobservacion', 'sins_observacion', 'sins_observacion_creasolicitud'], 'string', 'max' => 1000],
            [['sins_observacion_revisa'], 'string', 'max' => 500],
            [['sins_beca', 'sins_estado', 'sins_estado_logico'], 'string', 'max' => 1],
            [['cemp_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConvenioEmpresa::className(), 'targetAttribute' => ['cemp_id' => 'cemp_id']],
            [['int_id'], 'exist', 'skipOnError' => true, 'targetClass' => Interesado::className(), 'targetAttribute' => ['int_id' => 'int_id']],
            [['rsin_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResSolInscripcion::className(), 'targetAttribute' => ['rsin_id' => 'rsin_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'sins_id' => 'Sins ID',
            'int_id' => 'Int ID',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'ming_id' => 'Ming ID',
            'eaca_id' => 'Eaca ID',
            'mest_id' => 'Mest ID',
            'emp_id' => 'Emp ID',
            'num_solicitud' => 'Num Solicitud',
            'rsin_id' => 'Rsin ID',
            'sins_fecha_solicitud' => 'Sins Fecha Solicitud',
            'sins_fecha_preaprobacion' => 'Sins Fecha Preaprobacion',
            'sins_fecha_aprobacion' => 'Sins Fecha Aprobacion',
            'sins_fecha_reprobacion' => 'Sins Fecha Reprobacion',
            'sins_fecha_prenoprobacion' => 'Sins Fecha Prenoprobacion',
            'sins_preobservacion' => 'Sins Preobservacion',
            'sins_observacion' => 'Sins Observacion',
            'sins_observacion_creasolicitud' => 'Sins Observacion Creasolicitud',
            'sins_observacion_revisa' => 'Sins Observacion Revisa',
            'sins_beca' => 'Sins Beca',
            'cemp_id' => 'Cemp ID',
            'sins_usuario_preaprueba' => 'Sins Usuario Preaprueba',
            'sins_usuario_aprueba' => 'Sins Usuario Aprueba',
            'sins_usuario_ingreso' => 'Sins Usuario Ingreso',
            'sins_usuario_modifica' => 'Sins Usuario Modifica',
            'sins_estado' => 'Sins Estado',
            'sins_fecha_creacion' => 'Sins Fecha Creacion',
            'sins_fecha_modificacion' => 'Sins Fecha Modificacion',
            'sins_estado_logico' => 'Sins Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmitidos() {
        return $this->hasMany(Admitido::className(), ['sins_id' => 'sins_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculadosReprobados() {
        return $this->hasMany(MatriculadosReprobado::className(), ['sins_id' => 'sins_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudDatosFacturas() {
        return $this->hasMany(SolicitudDatosFactura::className(), ['sins_id' => 'sins_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCemp() {
        return $this->hasOne(ConvenioEmpresa::className(), ['cemp_id' => 'cemp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInt() {
        return $this->hasOne(Interesado::className(), ['int_id' => 'int_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMing() {
        return $this->hasOne(MetodoIngreso::className(), ['ming_id' => 'ming_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRsin() {
        return $this->hasOne(ResSolInscripcion::className(), ['rsin_id' => 'rsin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudRechazadas() {
        return $this->hasMany(SolicitudRechazada::className(), ['sins_id' => 'sins_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudinsDocumentos() {
        return $this->hasMany(SolicitudinsDocumento::className(), ['sins_id' => 'sins_id']);
    }

    public static function getSolicitudes($estado_inscripcion1, $estado_inscripcion2, $per_id, $resp_gruporol, $arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_facturacion;
        $estado = 1;
        $columnsAdd = "";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";
            if ($arrFiltro['ejecutivo'] != "" && $arrFiltro['ejecutivo'] > 0) {
                $str_search .= "intej.per_id  = :ejecutivo AND ";
            }
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

        $sql = "SELECT
                    lpad(sins_id,4,'0') as num_solicitud,
                    sins_fecha_solicitud as fecha_solicitud,
                    per.per_id as persona,
                    inte.int_id as int_id,
                    per.per_cedula as per_dni,
                    per.per_pri_nombre as per_pri_nombre,
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,
                    uaca.uaca_nombre as nint_nombre,
                    ifnull((select ming.ming_nombre
                            from " . $con->dbname . ".metodo_ingreso as ming
                            where sins.ming_id = ming.ming_id AND
                                  ming.ming_estado = :estado AND
                                  ming.ming_estado_logico = :estado),'NA') as ming_nombre,
                    eac.eaca_nombre car_nombre,
                    concat(per.per_pri_nombre ,' ', ifnull(per.per_seg_nombre,' ')) as per_nombres,
                    concat(per.per_pri_apellido ,' ', ifnull(per.per_seg_apellido,' ')) as per_apellidos,
                    sins.sins_fecha_solicitud as fecha_solicitud,
                    rsol.rsin_nombre as estado,
                    sins.sins_id,
                    intej.per_id as ejecutivo,
                    (Select concat(pers.per_pri_nombre ,' ', pers.per_pri_apellido) as ejecutivo_asignado
                     from " . $con2->dbname . ".persona pers where pers.per_id = ejecutivo) as ejecutivo_asignado,
                    sins_fecha_preaprobacion,
                    sins_fecha_aprobacion,
                    sins_fecha_reprobacion,
                    sins_fecha_prenoprobacion,
                    sins_observacion,
                    $columnsAdd
                    :resp_gruporol as roladmin,
                    sins.sins_usuario_preaprueba as usu_preaprueba,
                    $per_id as 'usu_autenticado',
                    case when ifnull((select opag_estado_pago
                               from " . $con3->dbname . ".orden_pago op
                               where op.sins_id = sins.sins_id),'N') = 'N' then 'No generado'
                         when (select opag_estado_pago
                               from " . $con3->dbname . ".orden_pago op
                               where op.sins_id = sins.sins_id) = 'P' then 'Pendiente'
                         else 'Pagado' end as pago,
                    ifnull((select fp.fpag_nombre
                            from " . $con3->dbname . ".registro_pago rp inner join " . $con3->dbname . ".desglose_pago dp
                                on dp.dpag_id = rp.dpag_id inner join " . $con3->dbname . ".orden_pago op on op.opag_id = dp.opag_id
                                inner join " . $con3->dbname . ".forma_pago fp on fp.fpag_id = rp.fpag_id
                            where sins.sins_id = op.sins_id),'') as forma_pago, sins.uaca_id,
                    ifnull((select count(*) from " . $con->dbname . ".solicitudins_documento sd
                            where sd.sins_id = sins.sins_id and sd.sdoc_estado = :estado and sd.sdoc_estado_logico = :estado),0) as numDocumento
                FROM
                    " . $con->dbname . ".solicitud_inscripcion as sins
                    INNER JOIN " . $con->dbname . ".interesado as inte on sins.int_id = inte.int_id
                    INNER JOIN " . $con2->dbname . ".persona as per on inte.per_id = per.per_id
                    INNER JOIN " . $con1->dbname . ".unidad_academica as uaca on sins.uaca_id = uaca.uaca_id
                    INNER JOIN " . $con1->dbname . ".modalidad as m on sins.mod_id = m.mod_id
                    INNER JOIN " . $con->dbname . ".res_sol_inscripcion as rsol on rsol.rsin_id = sins.rsin_id
                    INNER JOIN " . $con->dbname . ".interesado_ejecutivo as intej on intej.int_id = inte.int_id
                    INNER JOIN " . $con1->dbname . ".estudio_academico as eac on eac.eaca_id = sins.eaca_id
                WHERE
                    $str_search
                    sins.rsin_id in(:pendiente, :noaprobado) AND
                    sins.sins_estado_logico=:estado AND
                    sins.sins_estado=:estado AND
                    inte.int_estado_logico=:estado AND
                    inte.int_estado=:estado AND
                    per.per_estado_logico=:estado AND
                    per.per_estado=:estado AND
                    uaca.uaca_estado = :estado AND
                    uaca.uaca_estado_logico = :estado AND
                    m.mod_estado = :estado AND
                    m.mod_estado_logico = :estado AND
                    rsol.rsin_estado = :estado AND
                    rsol.rsin_estado_logico = :estado AND
                    intej.ieje_estado = :estado AND
                    intej.ieje_estado_logico = :estado AND
                    eac.eaca_estado=:estado AND
                    eac.eaca_estado_logico=:estado ";

        if (($estado_inscripcion1 != 1) and ( !empty($resp_gruporol))) {
            $sql .= " AND intej.per_id <> :per_id";
        }

        if (($estado_inscripcion1 == 1) and ( !empty($resp_gruporol)) and ( $resp_gruporol != 61)) {
            $sql .= "AND intej.per_id = :per_id";
        }
        $sql .= " ORDER BY fecha_solicitud DESC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pendiente", $estado_inscripcion1, \PDO::PARAM_INT);
        $comando->bindParam(":noaprobado", $estado_inscripcion2, \PDO::PARAM_INT);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":resp_gruporol", $resp_gruporol, \PDO::PARAM_INT);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
            $ejecutivo = $arrFiltro["ejecutivo"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['ejecutivo'] != "" && $arrFiltro['ejecutivo'] > 0) {
                $comando->bindParam(":ejecutivo", $ejecutivo, \PDO::PARAM_STR);
            }
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
                    'num_solicitud',
                    'fecha_solicitud',
                    'per_dni',
                    'per_pri_nombre',
                    'per_seg_nombre',
                    'per_pri_apellido',
                    'per_seg_apellido',
                    'nint_nombre',
                    'ming_nombre',
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

    public static function getSolicitudesXInteresado($int_id, $arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_facturacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
        }
        $sql = "
                    SELECT
                        inte.per_id,
                        op.opag_id,
                        lpad(ifnull(sins.num_solicitud,sins.sins_id),9,'0') as num_solicitud,
                        sins.sins_id,
                        sins.sins_fecha_solicitud as fecha_solicitud,
                        uaca.uaca_id,
                        uaca.uaca_nombre as nint_nombre,
                        ifnull((select min.ming_nombre from " . $con->dbname . ".metodo_ingreso min where min.ming_id = sins.ming_id),'N/A') as metodo_ingreso,
                        sins.eaca_id,
                        (case when (sins.emp_id = 1) then
                                (case when (sins.uaca_id < 3) then
                                    (select ea.eaca_descripcion from " . $con1->dbname . ".estudio_academico ea where ea.eaca_id = sins.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                                else
                                    (select me.mest_descripcion from " . $con1->dbname . ".modulo_estudio me where me.mest_id = sins.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                                end)
                            else
                                (select me.mest_descripcion from " . $con1->dbname . ".modulo_estudio me where me.mest_id = sins.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                            end)
                            as carrera,
                        rsol.rsin_nombre as estado,
                        (select count(*) numdocumentos from " . $con->dbname . ".solicitudins_documento sid where sid.sins_id = sins.sins_id) as numDocumentos,
                        (case when op.opag_estado_pago = 'S' then 'Pagado' else 'Pendiente' end) as estado_pago
                    FROM
                        " . $con->dbname . ".interesado as inte
                        JOIN " . $con->dbname . ".solicitud_inscripcion as sins on sins.int_id = inte.int_id
                        JOIN " . $con1->dbname . ".unidad_academica as uaca on sins.uaca_id = uaca.uaca_id
                        JOIN " . $con->dbname . ".res_sol_inscripcion as rsol on rsol.rsin_id = sins.rsin_id
                        JOIN " . $con3->dbname . ".orden_pago as op on op.sins_id = sins.sins_id
                    WHERE
                        $str_search
                        inte.int_id = :int_id AND
                        sins.sins_estado_logico=:estado AND
                        inte.int_estado_logico=:estado AND
                        rsol.rsin_estado=:estado AND
                        uaca.uaca_estado = :estado AND
                        sins.sins_estado=:estado AND
                        inte.int_estado=:estado AND
                        rsol.rsin_estado_logico=:estado AND
                        uaca.uaca_estado_logico = :estado AND
                        op.opag_estado = :estado AND
                        op.opag_estado_logico = :estado
                    ORDER BY fecha_solicitud DESC
               "
        ;

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_ini = $arrFiltro["f_ini"];
            $fecha_fin = $arrFiltro["f_fin"];
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
                    'num_solicitud',
                    'fecha_solicitud',
                    'nint_nombre',
                    'metodo_ingreso',
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    public static function consultarInteresadoPorSol_id($sins_id) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
                SELECT
                    per.per_cedula as per_dni,
                    per.per_id,
                    concat(ifnull(per.per_pri_nombre,'') ,' ', ifnull(per.per_seg_nombre,'')) as per_nombres,
                    concat(ifnull(per.per_pri_apellido,'') ,' ', ifnull(per.per_seg_apellido,'')) as per_apellidos,
                    sins.sins_id,
                    inte.int_id,
                    sins.sins_beca,
                    sins.sins_fecha_solicitud as fecha_solicitud,
                    lpad(ifnull(sins.num_solicitud, sins.sins_id),9,'0') as num_solicitud,
                    per.per_nac_ecuatoriano,
                    sins.uaca_id,
                    sins.mod_id,
                    sins.eaca_id,
                    ifnull(cemp_id,0) as cemp_id,
                    uaca.uaca_nombre,
                    case sins.uaca_id
                     when 1 then (select eaca.eaca_nombre from " . $con1->dbname . ".estudio_academico eaca where eaca.eaca_id = sins.eaca_id)
                     when 2 then (select eaca.eaca_nombre from " . $con1->dbname . ".estudio_academico eaca where eaca.eaca_id = sins.eaca_id)
                     when 3 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                     when 4 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                     when 5 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                     when 6 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                    else null
                      end as 'carrera',
                    sins.sins_fecha_reprobacion,
                    sins.sins_observacion,
                    sins.rsin_id,
                    m.mod_nombre,
                    per.per_correo,
                    per.per_celular,
                    sins.ming_id,
                    sins.emp_id,
                    sins.sins_observacion_creasolicitud,
                    sins.sins_observacion_revisa,
                    sins.cemp_id
                FROM
                    " . $con->dbname . ".solicitud_inscripcion as sins
                    INNER JOIN " . $con->dbname . ".interesado as inte on sins.int_id = inte.int_id
                    INNER JOIN " . $con2->dbname . ".persona as per on inte.per_id = per.per_id
                    INNER JOIN " . $con1->dbname . ".unidad_academica as uaca on uaca.uaca_id = sins.uaca_id
                    INNER JOIN " . $con1->dbname . ".modalidad as m on m.mod_id = sins.mod_id
                WHERE
                    sins.sins_estado_logico=:estado AND
                    inte.int_estado_logico=:estado AND
                    per.per_estado_logico=:estado AND
                    sins.sins_estado=:estado AND
                    inte.int_estado=:estado AND
                    per.per_estado=:estado AND
                    sins.sins_id=:sins_id AND
                    m.mod_estado=:estado AND
                    m.mod_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public static function obtenerSolicitudXInteresado($int_id) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db;
        $estado = 1;
        $sql = "SELECT
                    per.per_cedula as per_dni,
                    per.per_pri_nombre as per_pri_nombre,
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,
                    nint.nint_nombre as nint_nombre,
                    -- ming.ming_nombre as ming_nombre,
                    ifnull((select ming.ming_nombre
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as ming_nombre,
                    concat(per.per_pri_nombre ,' ', per.per_seg_nombre) as per_nombres,
                    concat(per.per_pri_apellido ,' ', per.per_seg_apellido) as per_apellidos,
                    sins_fecha_solicitud as fecha_solicitud

                FROM
                    " . $con->dbname . ".solicitud_inscripcion as sins
                    INNER JOIN " . $con->dbname . ".interesado as inte on sins.int_id = inte.int_id
                    INNER JOIN " . $con->dbname . ".pre_interesado as pint on inte.pint_id = pint.pint_id
                    INNER JOIN " . $con2->dbname . ".persona as per on pint.per_id = per.per_id
                    INNER JOIN " . $con->dbname . ".nivel_interes as nint on sins.nint_id = nint.nint_id
                    -- INNER JOIN " . $con->dbname . ".metodo_ingreso as ming on sins.ming_id = ming.ming_id
                WHERE
                    sins.sins_estado_logico=:estado AND
                    inte.int_estado_logico=:estado AND
                    pint.pint_estado_logico=:estado AND
                    per.per_estado_logico=:estado AND
                    sins.sins_estado=:estado AND
                    inte.int_estado=:estado AND
                    pint.pint_estado=:estado AND
                    per.per_estado=:estado AND
                    inte.int_id=:int_id";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Obtenerdocumentosxsolicitud
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (nombres de archivos adjuntos por solicitud)
     */
    public function Obtenerdocumentosxsolicitud($sins_id, $dadj_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT dadj_id, sdoc_archivo
                FROM " . $con->dbname . ".solicitudins_documento sdoc
                WHERE sdoc.sins_id = :sins_id AND
                      sdoc.dadj_id = :dadj_id AND
                      sdoc.sdoc_estado = :estado AND
                      sdoc.sdoc_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":dadj_id", $dadj_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function apruebaSolicitud (PreAprueba y Aprueba Solicitud)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function apruebaSolicitud($sins_id, $rsin_id, $observacion, $observarevisa, $banderapreaprueba, $usuario) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]); //date("Y-m-d H:i:s");//$hoy = date("Y-m-d H:i:s");

        if ($banderapreaprueba == 1) {
            $usu_preaprueba = $usuario;
        } else {
            $usu_aprueba = $usuario;
        }

        if ($rsin_id == 3) {  //Pre-aprobacion
            $fecha_preaprobacion = date(Yii::$app->params["dateTimeByDefault"]);
            error_log("entro2" . date("Y-m-d H:i:s"));
            $obs_pre = $observacion;
        }
        if ($rsin_id == 4) { //No aprueba
            if ($banderapreaprueba == 1) {
                $fecha_prenoprobacion = date(Yii::$app->params["dateTimeByDefault"]);
                $obs_pre = $observacion;
            } else {
                $fecha_reprobacion = date(Yii::$app->params["dateTimeByDefault"]);
                $obs_apro = $observacion;
            }
        }
        if ($rsin_id == 2) {   //Aprueba
            $fecha_aprobacion = date(Yii::$app->params["dateTimeByDefault"]);
            error_log("entro3" . date("Y-m-d H:i:s"));
            $obs_apro = $observacion;
        }
        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".solicitud_inscripcion
                SET sins_fecha_preaprobacion = ifnull(:fecha_preaprobacion,sins_fecha_preaprobacion),
                    sins_fecha_aprobacion = ifnull(:fecha_aprobacion,sins_fecha_aprobacion),
                    sins_fecha_reprobacion = ifnull(:fecha_reprobacion,sins_fecha_reprobacion),
                    sins_fecha_prenoprobacion = ifnull(:fecha_prenoprobacion,sins_fecha_prenoprobacion),
                    sins_observacion = ifnull(:observacion,sins_observacion),
                    sins_observacion_revisa = :observarevisa,
                    sins_preobservacion = ifnull(:preobservacion,sins_preobservacion),
                    sins_usuario_preaprueba = ifnull(:usu_preaprueba,sins_usuario_preaprueba),
                    sins_usuario_aprueba = ifnull(:usu_aprueba,sins_usuario_aprueba),
                    rsin_id = :rsin_id,
                    sins_fecha_modificacion = :fecha_modificacion
                WHERE sins_id = :sins_id AND
                      sins_estado =:estado AND
                      sins_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":rsin_id", $rsin_id, \PDO::PARAM_INT);
        $comando->bindParam(":fecha_preaprobacion", $fecha_preaprobacion, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_aprobacion", $fecha_aprobacion, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_reprobacion", $fecha_reprobacion, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_prenoprobacion", $fecha_prenoprobacion, \PDO::PARAM_STR);
        $comando->bindParam(":observacion", $obs_apro, \PDO::PARAM_STR);
        $comando->bindParam(":preobservacion", $obs_pre, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":usu_preaprueba", $usu_preaprueba, \PDO::PARAM_INT);
        $comando->bindParam(":usu_aprueba", $usu_aprueba, \PDO::PARAM_INT);
        $comando->bindParam(":observarevisa", $observarevisa, \PDO::PARAM_STR);

        $response = $comando->execute();
        return $response;
    }

    /**
     * Function Obtenerdatosolicitud
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (el precio del curso por metodo de ingreso y nivel de interes y otros datos de solicitud)
     */
    public function Obtenerdatosolicitud($sins_id) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_precio = 'A';

        $sql = "SELECT  imni.imni_id,
                        (case when sins.sins_beca = '1' then 0
                              else format(ipre.ipre_precio+(ipre.ipre_precio*ifnull(ipre.ipre_porcentaje_iva,0)),2) end) as precio,
                        sins.uaca_id as nivel_interes,
                        sins.ming_id as metodo_ingreso,
                        sins.mod_id,
                        sins.eaca_id,
                        sins.emp_id,
                        (select ming.ming_nombre from " . $con->dbname . ".metodo_ingreso ming where ming.ming_id = sins.ming_id and ming.ming_estado = :estado AND ming.ming_estado_logico = :estado) as nombre_metodo_ingreso,
                        (select uaca.uaca_nombre from " . $con3->dbname . ".unidad_academica uaca where uaca.uaca_id = sins.uaca_id and uaca.uaca_estado = :estado AND uaca.uaca_estado_logico = :estado) as nombre_nivel_interes,
                        (select m.mod_nombre from " . $con3->dbname . ".modalidad m where  m.mod_id = sins.mod_id and m.mod_estado = :estado AND m.mod_estado_logico = :estado) as nombre_modalidad
                FROM " . $con->dbname . ".solicitud_inscripcion sins INNER JOIN " . $con2->dbname . ".item_metodo_unidad imni
                     on ((ifnull(sins.ming_id,0) = ifnull(imni.ming_id,0) and sins.uaca_id = imni.uaca_id and sins.mod_id = imni.mod_id) or (sins.uaca_id = imni.uaca_id and sins.mod_id = imni.mod_id and sins.mest_id = imni.mest_id))
                     INNER JOIN " . $con2->dbname . ".item_precio ipre on imni.ite_id = ipre.ite_id
                WHERE ipre.ipre_estado_precio =:estado_precio AND
                       sins.sins_id = :sins_id AND
                       sins.sins_estado = :estado AND
                       sins.sins_estado_logico = :estado AND
                       imni.imni_estado =:estado AND
                       imni.imni_estado_logico = :estado AND
                       ipre.ipre_estado = :estado AND
                       ipre.ipre_estado_logico = :estado
                limit 1";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Validarsolicitud
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Verificar si existe una solicitud igual a la que se quiere crear siempre y cuando
     *          no tenga estado "No Aprobado").
     */
    public function Validarsolicitud($int_id, $nint_id, $ming_id, $car_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        if ($nint_id != 3) {
            $sql = "SELECT 'S' existe
                    FROM " . $con->dbname . ".solicitud_inscripcion sins
                    WHERE sins.int_id = :int_id AND
                          sins.uaca_id = :uaca_id AND ";
            if ($ming_id != null) {
                $sql .= "ifnull(sins.ming_id,0) = :ming_id AND ";
            }
            $sql .= "sins.eaca_id = :eaca_id AND
                     sins.rsin_id <> 4 AND
                     sins.sins_estado = :estado AND
                     sins.sins_estado_logico = :estado";
        } else {
            if ($nint_id == 3) {
                $sql = "SELECT 'S' existe
                        FROM " . $con->dbname . ".solicitud_inscripcion sins
                        WHERE sins.int_id = :int_id AND
                              sins.uaca_id = :uaca_id AND
                              sins.eaca_id = :eaca_id AND
                              sins.rsin_id <> 4 AND
                              sins.sins_estado = :estado AND
                              sins.sins_estado_logico = :estado";
            } else {
                $sql = "SELECT 'S' existe
                        FROM " . $con->dbname . ".solicitud_inscripcion sins
                        WHERE sins.int_id = :int_id AND
                              sins.eaca_id = :eaca_id AND
                              sins.rsin_id <> 4 AND
                              sins.sins_estado = :estado AND
                              sins.sins_estado_logico = :estado";
            }
        }
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $nint_id, \PDO::PARAM_INT);
        if ($ming_id != null) {
            $comando->bindParam(":ming_id", $ming_id, \PDO::PARAM_INT);
        }
        $comando->bindParam(":eaca_id", $car_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarSolnoaprobada
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar los criterios a tomar en cuenta en la revisión de documentos según
     *                      el documento y la nacionalidad cuando no se aprueba una solicitud).
     */
    public function consultarSolnoaprobada($dadj_id, $tiponacext) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT sndo.snoa_id id, snoa.snoa_nombre name
                FROM " . $con->dbname . ".solicitud_noaprobada_documento sndo INNER JOIN " . $con->dbname . ".solicitud_noaprobada snoa on sndo.snoa_id = snoa.snoa_id
                WHERE sndo.dadj_id = :dadj_id AND
                      sndo.sndo_tiponacext = :tiponacext AND
                      sndo.sndo_estado_logico = :estado AND
                      sndo.sndo_estado = :estado AND
                      snoa.snoa_estado_logico = :estado AND
                      snoa.snoa_estado = :estado
                ORDER BY sndo.snoa_id";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dadj_id", $dadj_id, \PDO::PARAM_INT);
        $comando->bindParam(":tiponacext", $tiponacext, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultaDatosusuario
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (usuario id de la persona con logon).
     */
    public function consultaDatosusuario($per_id) {
        $con = \Yii::$app->db;
        $estado = 1;

        $sql = "select  usu.usu_id
                from " . $con->dbname . ".usuario usu
                where usu.per_id = :per_id
                      and usu.usu_estado = :estado
                      and usu.usu_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Insertarsolicitudrechazada
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  Id del registro insertado.
     */
    public function Insertarsolicitudrechazada($sins_id, $dadj_id, $con_id, $srec_etapa, $srec_observacion, $usu_id) {
        $con = \Yii::$app->db_captacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual.
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una.
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una.
        }

        $param_sql = "srec_estado_logico";
        $bsrec_sql = "1";

        $param_sql .= ", srec_estado";
        $bsrec_sql .= ", 1";

        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bsrec_sql .= ", :sins_id";
        }

        if (isset($dadj_id)) {
            $param_sql .= ", dadj_id";
            $bsrec_sql .= ", :dadj_id";
        }

        if (isset($con_id)) {
            $param_sql .= ", snoa_id";
            $bsrec_sql .= ", :snoa_id";
        }

        if (isset($srec_etapa)) {
            $param_sql .= ", srec_etapa";
            $bsrec_sql .= ", :srec_etapa";
        }

        if (isset($srec_observacion)) {
            $param_sql .= ", srec_observacion";
            $bsrec_sql .= ", :srec_observacion";
        }

        if (isset($usu_id)) {
            $param_sql .= ", usu_id";
            $bsrec_sql .= ", :usu_id";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".solicitud_rechazada ($param_sql) VALUES($bsrec_sql)";
            $comando = $con->createCommand($sql);

            if (isset($sins_id))
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);

            if (isset($dadj_id))
                $comando->bindParam(':dadj_id', $dadj_id, \PDO::PARAM_INT);

            if (isset($con_id))
                $comando->bindParam(':snoa_id', $con_id, \PDO::PARAM_INT);

            if (isset($srec_etapa))
                $comando->bindParam(':srec_etapa', $srec_etapa, \PDO::PARAM_STR);

            if (isset($srec_observacion))
                $comando->bindParam(':srec_observacion', $srec_observacion, \PDO::PARAM_STR);

            if (isset($usu_id))
                $comando->bindParam(':usu_id', $usu_id, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.solicitud_rechazada');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultaSolicitudRechazada
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData Datos de solicitud rechazada.
     */
    public function consultaSolicitudRechazada($sins_id, $srec_etapa) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT srec_observacion observacion,
                       snoa.snoa_nombre condicion,
                       srec_fecha_creacion
                FROM " . $con->dbname . ".solicitud_rechazada srec "
                . "INNER JOIN " . $con->dbname . ".solicitud_noaprobada snoa on snoa.snoa_id = srec.snoa_id
                WHERE   srec.sins_id = :sins_id AND
                        srec_fecha_creacion = (SELECT max(srec_fecha_creacion)
                        FROM db_captacion.solicitud_rechazada where sins_id = :sins_id) AND
                        srec.srec_etapa = :etapa AND
                        srec.srec_estado = :estado AND
                        snoa.snoa_estado = :estado AND
                        srec.srec_estado_logico = :estado AND
                        snoa.snoa_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":etapa", $srec_etapa, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Consultaestadosolicitud
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar los estados de las solicitudes).
     */
    public function Consultaestadosolicitud() {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT rsin.rsin_id id, rsin.rsin_nombre value
                FROM " . $con->dbname . ".res_sol_inscripcion rsin
                WHERE rsin.rsin_estado_logico = :estado AND
                      rsin.rsin_estado = :estado
                ORDER BY rsin.rsin_nombre";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Solicitudesaprobadas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Retornar las solicitudes aprobadas).
     */
    public function Solicitudesaprobadas($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db;
        $estado = 1;
        $estadoaprobada = 2;
        $columnsAdd = "";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";
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

        $sql = "SELECT
                    lpad(sins_id,4,'0') as num_solicitud,
                    sins_fecha_solicitud as fecha_solicitud,
                    per.per_id as persona,
                    inte.int_id as int_id,
                    per.per_cedula as per_dni,
                    per.per_pri_nombre as per_pri_nombre,
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,
                    uaca.uaca_nombre as nint_nombre,
                    -- ming.ming_nombre as ming_nombre,
                    ifnull((select ming.ming_nombre
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as ming_nombre,
                    eaca.eaca_nombre as car_nombre,
                    concat(per.per_pri_nombre ,' ', ifnull(per.per_seg_nombre,' ')) as per_nombres,
                    concat(per.per_pri_apellido ,' ', ifnull(per.per_seg_apellido,' ')) as per_apellidos,
                    sins.sins_fecha_solicitud as fecha_solicitud,
                    rsol.rsin_nombre as estado,
                    sins.sins_id,
                    intej.per_id as ejecutivo,
                    (Select concat(pers.per_pri_nombre ,' ', pers.per_pri_apellido) as ejecutivo_asignado
                     from " . $con2->dbname . ".persona pers where pers.per_id = ejecutivo) as ejecutivo_asignado,
                    sins_fecha_preaprobacion,
                    sins_fecha_aprobacion,
                    sins_fecha_reprobacion,
                    sins_fecha_prenoprobacion,
                    sins_observacion,
                    $columnsAdd
                    sins.sins_usuario_preaprueba as usu_preaprueba
                FROM
                    " . $con->dbname . ".solicitud_inscripcion as sins
                    INNER JOIN " . $con->dbname . ".interesado as inte on sins.int_id = inte.int_id
                    INNER JOIN " . $con2->dbname . ".persona as per on inte.per_id = per.per_id
                    INNER JOIN " . $con1->dbname . ".unidad_academica as uaca on sins.uaca_id = uaca.uaca_id
                    -- INNER JOIN " . $con->dbname . ".metodo_ingreso as ming on sins.ming_id = ming.ming_id
                    INNER JOIN " . $con->dbname . ".res_sol_inscripcion as rsol on rsol.rsin_id = sins.rsin_id
                    INNER JOIN " . $con->dbname . ".interesado_ejecutivo as intej on intej.int_id = sins.int_id
                    INNER JOIN " . $con1->dbname . ".estudio_academico as eaca on eaca.eaca_id = sins.eaca_id
                WHERE
                    $str_search
                    sins.rsin_id = :aprobada AND
                    sins.sins_estado_logico=:estado AND
                    inte.int_estado_logico=:estado AND
                    per.per_estado_logico=:estado AND
                    sins.sins_estado=:estado AND
                    inte.int_estado=:estado AND
                    per.per_estado=:estado AND
                    intej.ieje_estado = :estado AND
                    intej.ieje_estado_logico = :estado AND
                    uaca.uaca_estado = :estado AND
                    uaca.uaca_estado_logico = :estado AND
                    eaca.eaca_estado=:estado AND
                    eaca.eaca_estado_logico=:estado
                ORDER BY fecha_solicitud DESC";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":aprobada", $estadoaprobada, \PDO::PARAM_INT);

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
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'num_solicitud',
                    'fecha_solicitud',
                    'per_dni',
                    'per_pri_nombre',
                    'per_seg_nombre',
                    'per_pri_apellido',
                    'per_seg_apellido',
                    'nint_nombre',
                    'ming_nombre',
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

    /**
     * Function ObtenerPrecio
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (el precio del curso por metodo de ingreso y nivel de interes y otros datos de solicitud)
     */
    public function ObtenerPrecio($ming_id, $nint_id, $mod_id, $car_id) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_precio = 'A';
        if ($nint_id < 3) {
            $sql = "SELECT  imni.imni_id,
                            ipre.ipre_precio+(ipre.ipre_precio*ifnull(ipre.ipre_porcentaje_iva,0)) as precio,
                            ming.ming_nombre as nombre_metodo_ingreso,
                            ua.uaca_nombre as nombre_nivel_interes,
                            imni.ite_id
                    FROM " . $con2->dbname . ".item_metodo_unidad imni LEFT JOIN " . $con2->dbname . ".item_precio ipre on imni.ite_id = ipre.ite_id
                         LEFT JOIN " . $con->dbname . ".metodo_ingreso ming on ming.ming_id = imni.ming_id
                         LEFT JOIN " . $con1->dbname . ".unidad_academica ua on ua.uaca_id = imni.uaca_id
                    WHERE ";
            if ((empty($ming_id)) or ( $ming_id == 0)) {
                $sql .= "ifnull(imni.ming_id,0) = 0 AND ";
            } else {
                $sql .= "imni.ming_id = :ming_id AND ";
            }
            $sql .= "imni.uaca_id = :nint_id AND
                          imni.mod_id = :mod_id AND
                          ipre.ipre_estado_precio = :estado_precio AND
                          now() between ipre.ipre_fecha_inicio and ipre.ipre_fecha_fin AND
                          imni.imni_estado = :estado AND
                          imni.imni_estado_logico = :estado AND
                          -- imni.ite_id  in (158,159,160,161,166) and
                          imni.ite_id  in (204, 205, 206, 207) and
                          ua.uaca_estado = :estado AND
                          ua.uaca_estado_logico = :estado";
        } else {
            $sql = "
                    SELECT  imni.imni_id,
                            ipre.ipre_precio+(ipre.ipre_precio*ifnull(ipre.ipre_porcentaje_iva,0)) as precio,
                            ming.ming_nombre as nombre_metodo_ingreso,
                            ua.uaca_nombre as nombre_nivel_interes,
                            imni.ite_id
                    FROM db_facturacion.item_metodo_unidad imni LEFT JOIN db_facturacion.item_precio ipre on imni.ite_id = ipre.ite_id
                         LEFT JOIN db_captacion.metodo_ingreso ming on ming.ming_id = imni.ming_id
                         LEFT JOIN db_academico.unidad_academica ua on ua.uaca_id = imni.uaca_id
                    WHERE
                          imni.uaca_id = :nint_id AND
                          imni.mod_id = :mod_id AND
                          imni.mest_id = :car_id AND
                          ipre.ipre_estado_precio = :estado_precio AND
                          now() between ipre.ipre_fecha_inicio and ipre.ipre_fecha_fin AND
                          imni.imni_estado = :estado AND
                          imni.imni_estado_logico = :estado AND
                          -- imni.ite_id  in (158,159,160,161) and
                          imni.ite_id  in (204, 205, 206, 207) and
                          ua.uaca_estado = :estado AND
                          ua.uaca_estado_logico = :estado";
        }
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ming_id", $ming_id, \PDO::PARAM_INT);
        $comando->bindParam(":nint_id", $nint_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":car_id", $car_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarDocumxSolic
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (número de documentos adjuntos)
     */
    public function consultarDocumxSolic($sins_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT ifnull(count(*),0) numDocumentos
                FROM " . $con->dbname . ".solicitudins_documento sdoc
                WHERE sdoc.sins_id = :sins_id AND
                      sdoc.sdoc_estado = :estado AND
                      sdoc.sdoc_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":dadj_id", $dadj_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarSolicitudes. Muestra todas las solicitudes.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarSolicitudes($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_facturacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_cedula like :search) AND ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
            if ($arrFiltro['estadoSol'] != "" && $arrFiltro['estadoSol'] > 0) {
                $str_search .= "sins.rsin_id = :estadosol AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "sins.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "sins.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $str_search .= "sins.eaca_id = :carrera AND ";
            }
        }

        $sql = "SELECT
                    distinct ifnull(sins.num_solicitud, lpad(sins.sins_id,9,'0')) as num_solicitud,
                    sins_fecha_solicitud as fecha_solicitud,
                    per.per_id as persona,
                    inte.int_id as int_id,
                    per.per_cedula as per_dni,
                    per.per_pri_nombre as per_pri_nombre,
                    per.per_seg_nombre as per_seg_nombre,
                    per.per_pri_apellido as per_pri_apellido,
                    per.per_seg_apellido as per_seg_apellido,
                    sins.uaca_id,
                    uaca.uaca_nombre,
                    sins.eaca_id,
                    case uaca.uaca_id
                        when 1 then (select eaca.eaca_nombre from " . $con1->dbname . ".estudio_academico eaca where eaca.eaca_id = sins.eaca_id)
                        when 2 then (select eaca.eaca_nombre from " . $con1->dbname . ".estudio_academico eaca where eaca.eaca_id = sins.eaca_id)
                        when 3 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                        when 4 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                        when 5 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                        when 6 then (select mes.mest_nombre from " . $con1->dbname . ".modulo_estudio mes where mes.mest_id = sins.mest_id)
                        else null
                         end as 'carrera',
                    ifnull((select ming.ming_alias
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as ming_nombre,

                    concat(per.per_pri_nombre ,' ', ifnull(per.per_seg_nombre,' ')) as per_nombres,
                    concat(per.per_pri_apellido ,' ', ifnull(per.per_seg_apellido,' ')) as per_apellidos,
                    sins.sins_fecha_solicitud as fecha_solicitud,
                    sins.rsin_id,
                    rsol.rsin_nombre as estado,
                    sins.sins_id,
                    sins_fecha_preaprobacion,
                    sins_fecha_aprobacion,
                    sins_fecha_reprobacion,
                    sins_fecha_prenoprobacion,
                    sins_observacion,
                    sins.sins_usuario_preaprueba as usu_preaprueba,
                    sins.sins_usuario_ingreso as agente,
                    case when ifnull((select opag_estado_pago
                                            from " . $con3->dbname . ".orden_pago op
                                            where op.sins_id = sins.sins_id
                                            and op.opag_estado = :estado and op.opag_estado_logico = :estado),'N') = 'N' then 'No generado'
                     when (select opag_estado_pago
                               from " . $con3->dbname . ".orden_pago op
                               where op.sins_id = sins.sins_id
                                     and op.opag_estado = :estado and op.opag_estado_logico = :estado) = 'P' then 'Pendiente'
                    else 'Pagado' end as pago,
                    ifnull((select count(*) from " . $con->dbname . ".solicitudins_documento sd
                            where sd.sins_id = sins.sins_id and sd.sdoc_estado = :estado and sd.sdoc_estado_logico = :estado),0) as numDocumentos,
                    sins.emp_id,
                    concat(pges.per_pri_nombre, ' ', pges.per_pri_apellido) as Agente
                FROM
                    " . $con->dbname . ".solicitud_inscripcion as sins
                    INNER JOIN " . $con->dbname . ".interesado as inte on sins.int_id = inte.int_id
                    INNER JOIN " . $con2->dbname . ".persona as per on inte.per_id = per.per_id
                    INNER JOIN " . $con1->dbname . ".unidad_academica as uaca on sins.uaca_id = uaca.uaca_id
                    INNER JOIN " . $con1->dbname . ".modalidad as m on sins.mod_id = m.mod_id
                    INNER JOIN " . $con->dbname . ".res_sol_inscripcion as rsol on rsol.rsin_id = sins.rsin_id
                    -- INNER JOIN " . $con1->dbname . ".estudio_academico as eac on eac.eaca_id = sins.eaca_id
                    LEFT JOIN " . $con2->dbname . ".usuario as uges on uges.usu_id = sins.sins_usuario_ingreso
                    LEFT JOIN " . $con2->dbname . ".persona as pges on pges.per_id = uges.per_id
                WHERE
                    $str_search
                    sins.sins_estado_logico=:estado AND
                    sins.sins_estado=:estado AND
                    inte.int_estado_logico=:estado AND
                    inte.int_estado=:estado AND
                    per.per_estado_logico=:estado AND
                    per.per_estado=:estado AND
                    uaca.uaca_estado = :estado AND
                    uaca.uaca_estado_logico = :estado AND
                    m.mod_estado = :estado AND
                    m.mod_estado_logico = :estado AND
                    rsol.rsin_estado = :estado AND
                    rsol.rsin_estado_logico = :estado -- AND
                    ";

        $sql .= " ORDER BY fecha_solicitud DESC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            $estadoSol = $arrFiltro["estadoSol"];
            if ($arrFiltro['estadoSol'] != "" && $arrFiltro['estadoSol'] > 0) {
                $comando->bindParam(":estadosol", $estadoSol, \PDO::PARAM_INT);
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
                    'num_solicitud',
                    'fecha_solicitud',
                    'per_dni',
                    'per_pri_nombre',
                    'per_seg_nombre',
                    'per_pri_apellido',
                    'per_seg_apellido',
                    'nint_nombre',
                    'ming_nombre',
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

    /**
     * Function crearDatosFacturaSolicitud crea la informacion de facturacion de una solicitud
     * @author  Developer Uteg <developer@uteg.edu.ec>
     * @param   int     $sins_id        Id de la solicitud
     * @param   string  $dataNombres    Nombres de la persona a Facturar
     * @param   string  $dataApellidos  Apellidos de la persona a Facturar
     * @param   string  $dataTipDNI     Tipo de DNI: 1->CED 2->RUC
     * @param   string  $dataDNI        Valor del DNI
     * @param   string  $dataDireccion  Direccion de la persona a Facturar
     * @param   string  $dataTelefono   Telefono de la persona a Facturar
     * @return  $resultData (Retorna true si se realizo la operacion o false si fue error).
     */
    public function crearDatosFacturaSolicitud($sins_id, $dataNombres, $dataApellidos, $dataTipDNI, $dataDNI, $dataDireccion, $dataTelefono, $dataCorreo/* = null */) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $tipo = ((self::$arr_DNI[$dataTipDNI]) ? self::$arr_DNI[$dataTipDNI] : self::$arr_DNI["3"]);

        $sql = "INSERT INTO solicitud_datos_factura
                (sins_id, sdfa_nombres, sdfa_apellidos, sdfa_tipo_dni, sdfa_dni, sdfa_direccion, sdfa_telefono, sdfa_correo, sdfa_estado, sdfa_estado_logico) VALUES
                (:id, :nombres, :apellidos, :tipo_dni, :dni, :direccion, :telefono, :correo, :estado, :estado);";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":nombres", $dataNombres, \PDO::PARAM_STR);
        $comando->bindParam(":apellidos", $dataApellidos, \PDO::PARAM_STR);
        $comando->bindParam(":tipo_dni", $tipo, \PDO::PARAM_STR);
        $comando->bindParam(":dni", $dataDNI, \PDO::PARAM_STR);
        $comando->bindParam(":direccion", $dataDireccion, \PDO::PARAM_STR);
        $comando->bindParam(":telefono", $dataTelefono, \PDO::PARAM_STR);
        $comando->bindParam(":correo", $dataCorreo, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->execute();
        return $resultData;
    }

    /**
     * Function consultarDatosfacturaxIdsol. Muestra todas las solicitudes.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarDatosfacturaxIdsol($sins_id) {
        $con2 = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT sdfa_nombres, sdfa_apellidos, sdfa_direccion, sdfa_dni, sdfa_tipo_dni, sdfa_telefono
                FROM " . $con2->dbname . ".solicitud_datos_factura
                WHERE sins_id = :sins_id
                and sdfa_estado = :estado
                and sdfa_estado_logico = :estado";
        $comando = $con2->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    /**
     * Function consultarSolicitudesReporte. Muestra todas las solicitudes.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarSolicitudesReporte($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db;
        $con3 = \Yii::$app->db_facturacion;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search = "(per.per_pri_nombre like :search OR ";
                $str_search .= "per.per_seg_nombre like :search OR ";
                $str_search .= "per.per_pri_apellido like :search OR ";
                $str_search .= "per.per_cedula like :search) AND ";
            }

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
            if ($arrFiltro['estadoSol'] != "" && $arrFiltro['estadoSol'] > 0) {
                $str_search .= "sins.rsin_id = :estadosol AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "sins.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "sins.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $str_search .= "sins.eaca_id = :carrera AND ";
            }
        }

        $sql = "SELECT
                    lpad(sins_id,4,'0') as num_solicitud,
                    sins_fecha_solicitud as fecha_solicitud,
                    per.per_cedula as per_dni,
                    concat(per.per_pri_nombre ,' ', ifnull(per.per_seg_nombre,' ')) as per_nombres,
                    concat(per.per_pri_apellido ,' ', ifnull(per.per_seg_apellido,' ')) as per_apellidos,
                    concat(pges.per_pri_nombre, ' ', pges.per_pri_apellido) as Agente,
                    uaca.uaca_nombre,
                    ifnull((select ming.ming_alias
                                    from " . $con->dbname . ".metodo_ingreso as ming
                                    where sins.ming_id = ming.ming_id AND
                                    ming.ming_estado = :estado AND
                                    ming.ming_estado_logico = :estado),'NA') as ming_nombre,
                    eac.eaca_nombre as carrera,
                    rsol.rsin_nombre as estado,
                    case when ifnull((select opag_estado_pago
                                            from " . $con3->dbname . ".orden_pago op
                                            where op.sins_id = sins.sins_id
                                            and op.opag_estado = :estado and op.opag_estado_logico = :estado),'N') = 'N' then 'No generado'
                     when (select opag_estado_pago
                               from " . $con3->dbname . ".orden_pago op
                               where op.sins_id = sins.sins_id
                                     and op.opag_estado = :estado and op.opag_estado_logico = :estado) = 'P' then 'Pendiente'
                    else 'Pagado' end as pago
                FROM
                    " . $con->dbname . ".solicitud_inscripcion as sins
                    INNER JOIN " . $con->dbname . ".interesado as inte on sins.int_id = inte.int_id
                    INNER JOIN " . $con2->dbname . ".persona as per on inte.per_id = per.per_id
                    INNER JOIN " . $con1->dbname . ".unidad_academica as uaca on sins.uaca_id = uaca.uaca_id
                    INNER JOIN " . $con1->dbname . ".modalidad as m on sins.mod_id = m.mod_id
                    INNER JOIN " . $con->dbname . ".res_sol_inscripcion as rsol on rsol.rsin_id = sins.rsin_id
                    INNER JOIN " . $con1->dbname . ".estudio_academico as eac on eac.eaca_id = sins.eaca_id
                    LEFT JOIN " . $con2->dbname . ".usuario as uges on uges.usu_id = sins.sins_usuario_ingreso
                    LEFT JOIN " . $con2->dbname . ".persona as pges on pges.per_id = uges.per_id
                WHERE
                    $str_search
                    sins.sins_estado_logico=:estado AND
                    sins.sins_estado=:estado AND
                    inte.int_estado_logico=:estado AND
                    inte.int_estado=:estado AND
                    per.per_estado_logico=:estado AND
                    per.per_estado=:estado AND
                    uaca.uaca_estado = :estado AND
                    uaca.uaca_estado_logico = :estado AND
                    m.mod_estado = :estado AND
                    m.mod_estado_logico = :estado AND
                    rsol.rsin_estado = :estado AND
                    rsol.rsin_estado_logico = :estado AND
                    eac.eaca_estado=:estado AND
                    eac.eaca_estado_logico=:estado ";
        $sql .= " ORDER BY fecha_solicitud DESC";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            $estadoSol = $arrFiltro["estadoSol"];
            if ($arrFiltro['estadoSol'] != "" && $arrFiltro['estadoSol'] > 0) {
                $comando->bindParam(":estadosol", $estadoSol, \PDO::PARAM_INT);
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
                    'num_solicitud',
                    'fecha_solicitud',
                    'per_dni',
                    'per_pri_nombre',
                    'per_seg_nombre',
                    'per_pri_apellido',
                    'per_seg_apellido',
                    'nint_nombre',
                    'ming_nombre',
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

    /**
     * Function consultarDocumentostosxSol
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (obtener la cantidad de documentos subidos.)
     */
    public function consultarDocumentostosxSol($sins_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT count(*) numDocumentos
                FROM " . $con->dbname . ".solicitudins_documento sdoc
                WHERE sdoc.sins_id = :sins_id AND
                      sdoc.sdoc_estado = :estado AND
                      sdoc.sdoc_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function insertarSolicitud
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  Id del registro insertado.
     */
    public function insertarSolicitud($int_id, $uaca_id, $mod_id, $ming_id, $eaca_id, $mest_id, $emp_id, $num_solicitud, $rsin_id, $sins_fecha_solicitud, $sins_usuario_ingreso, $cemp_id = null) {
        $con = \Yii::$app->db_captacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual.
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una.
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una.
        }

        $param_sql = "sins_estado_logico";
        $bsrec_sql = "1";

        $param_sql .= ", sins_estado";
        $bsrec_sql .= ", 1";

        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsrec_sql .= ", :int_id";
        }
        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bsrec_sql .= ", :uaca_id";
        }
        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bsrec_sql .= ", :mod_id";
        }
        if (isset($ming_id)) {
            $param_sql .= ", ming_id";
            $bsrec_sql .= ", :ming_id";
        }
        if (isset($eaca_id)) {
            $param_sql .= ", eaca_id";
            $bsrec_sql .= ", :eaca_id";
        }
        if (isset($mest_id)) {
            $param_sql .= ", mest_id";
            $bsrec_sql .= ", :mest_id";
        }
        if (isset($emp_id)) {
            $param_sql .= ", emp_id";
            $bsrec_sql .= ", :emp_id";
        }
        if (isset($num_solicitud)) {
            $param_sql .= ", num_solicitud";
            $bsrec_sql .= ", :num_solicitud";
        }
        if (isset($rsin_id)) {
            $param_sql .= ", rsin_id";
            $bsrec_sql .= ", :rsin_id";
        }
        if (isset($sins_fecha_solicitud)) {
            $param_sql .= ", sins_fecha_solicitud";
            $bsrec_sql .= ", :sins_fecha_solicitud";
        }
        if (isset($sins_usuario_ingreso)) {
            $param_sql .= ", sins_usuario_ingreso";
            $bsrec_sql .= ", :sins_usuario_ingreso";
        }
        if (isset($cemp_id)) {
            $param_sql .= ", cemp_id";
            $bsrec_sql .= ", :cemp_id";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".solicitud_inscripcion ($param_sql) VALUES($bsrec_sql)";
            $comando = $con->createCommand($sql);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($uaca_id))
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);

            if (isset($mod_id))
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);

            if (isset($ming_id))
                $comando->bindParam(':ming_id', $ming_id, \PDO::PARAM_INT);

            if (isset($eaca_id))
                $comando->bindParam(':eaca_id', $eaca_id, \PDO::PARAM_INT);

            if (isset($mest_id))
                $comando->bindParam(':mest_id', $mest_id, \PDO::PARAM_INT);

            if (isset($emp_id))
                $comando->bindParam(':emp_id', $emp_id, \PDO::PARAM_INT);

            if (isset($num_solicitud))
                $comando->bindParam(':num_solicitud', $num_solicitud, \PDO::PARAM_STR);

            if (isset($rsin_id))
                $comando->bindParam(':rsin_id', $rsin_id, \PDO::PARAM_INT);

            if (isset($sins_fecha_solicitud))
                $comando->bindParam(':sins_fecha_solicitud', $sins_fecha_solicitud, \PDO::PARAM_STR);

            if (isset($sins_usuario_ingreso))
                $comando->bindParam(':sins_usuario_ingreso', $sins_usuario_ingreso, \PDO::PARAM_INT);

            if (isset($sins_usuario_ingreso))
                $comando->bindParam(':sins_usuario_ingreso', $sins_usuario_ingreso, \PDO::PARAM_INT);

            if (!empty($cemp_id)) {
                if (isset($cemp_id)) {
                    $comando->bindParam(':cemp_id', $cemp_id, \PDO::PARAM_INT);
                }
            }
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.solicitud_inscripcion');
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function insertarDocumentosSolic
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  Id del registro insertado.
     */
    public function insertarDocumentosSolic($sins_id, $int_id, $dadj_id, $sdoc_archivo, $sdoc_usuario_ingreso) {
        $con = \Yii::$app->db_captacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual.
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una.
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una.
        }

        $param_sql = "sdoc_estado_logico";
        $bsrec_sql = "1";

        $param_sql .= ", sdoc_estado";
        $bsrec_sql .= ", 1";

        if (isset($sins_id)) {
            $param_sql .= ", sins_id";
            $bsrec_sql .= ", :sins_id";
        }
        if (isset($int_id)) {
            $param_sql .= ", int_id";
            $bsrec_sql .= ", :int_id";
        }
        if (isset($dadj_id)) {
            $param_sql .= ", dadj_id";
            $bsrec_sql .= ", :dadj_id";
        }
        if (isset($sdoc_archivo)) {
            $param_sql .= ", sdoc_archivo";
            $bsrec_sql .= ", :sdoc_archivo";
        }
        if (isset($sdoc_usuario_ingreso)) {
            $param_sql .= ", sdoc_usuario_ingreso";
            $bsrec_sql .= ", :sdoc_usuario_ingreso";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".solicitudins_documento ($param_sql) VALUES($bsrec_sql)";
            $comando = $con->createCommand($sql);

            if (isset($sins_id))
                $comando->bindParam(':sins_id', $sins_id, \PDO::PARAM_INT);

            if (isset($int_id))
                $comando->bindParam(':int_id', $int_id, \PDO::PARAM_INT);

            if (isset($dadj_id))
                $comando->bindParam(':dadj_id', $dadj_id, \PDO::PARAM_INT);

            if (isset($sdoc_archivo))
                $comando->bindParam(':sdoc_archivo', $sdoc_archivo, \PDO::PARAM_STR);

            if (isset($sdoc_usuario_ingreso))
                $comando->bindParam(':sdoc_usuario_ingreso', $sdoc_usuario_ingreso, \PDO::PARAM_INT);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.solicitudins_documento');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function consultarFechadmitido
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     *          Grace Viteri  <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarFechadmitido($int_id, $sins_id) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT
                        ifnull((select max(icp.icpr_fecha_registro) FROM " . $con1->dbname . ".info_carga_prepago icp where icp.opag_id = opa.opag_id and icpr_estado = :estado and icpr_estado_logico = :estado limit 1),'') as fecha_subio,
                        ifnull((select adm.adm_fecha_creacion from " . $con->dbname . ".admitido adm where si.int_id = adm.int_id and si.sins_id = adm.sins_id and adm.adm_estado = :estado and adm_estado_logico = :estado),'') as fecha_admitido,
                        ifnull(opa.opag_fecha_pago_total,'') as fecha_aprobacion_pago,
                        ifnull((select icp.icpr_observacion
                               from " . $con1->dbname . ".info_carga_prepago icp
                               where icp.opag_id = opa.opag_id order by icpr_fecha_registro desc limit 1  ),' ') as icpr_observacion
                FROM " . $con1->dbname . ".orden_pago opa inner join " . $con->dbname . ".solicitud_inscripcion si on si.sins_id = opa.sins_id
                WHERE opa.sins_id = :sins_id AND
                      opa.opag_estado_logico = :estado AND
                      opa.opag_estado = :estado AND
                      si.sins_estado_logico = :estado AND
                      si.sins_estado = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":int_id", $int_id, \PDO::PARAM_INT);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Obtenerobservadocumentos
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return  $resultData (ultima observacion al subir docuemntos de la solicitud)
     */
    public function Obtenerobservadocumentos($sins_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT sdo.sdoc_observacion
                FROM " . $con->dbname . ".solicitudins_documento sdo
                WHERE sdo.sins_id = :sins_id AND
                      sdo.sdoc_estado = :estado AND
                      sdo.sdoc_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function ObtenerPrecioXitem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Precio del item)
     */
    public function ObtenerPrecioXitem($ite_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT ipre_precio precio
                FROM " . $con->dbname . ".item_precio
                WHERE ite_id = :ite_id
                    and ipre_estado_precio = 'A'
                    and (now() between ipre_fecha_inicio and ipre_fecha_fin)
                    and ipre_estado = :estado
                    and ipre_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function ObtenerPreciohistoricoXitem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Precio del item)
     */
    public function ObtenerPreciohistoricoXitem($ite_id, $fecha) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT hipr_precio precio
                FROM " . $con->dbname . ".historial_item_precio
                WHERE ite_id = :ite_id
                    and (:fecha between hipr_fecha_inicio and hipr_fecha_fin)
                    and hipr_estado = :estado
                    and hipr_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        $comando->bindParam(":fecha", $fecha, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function ValidarPrecioXitem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Precio del item)
     */
    public function ValidarPrecioXitem($ite_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT ip.ipre_precio precio_ins, ip1.ipre_precio precio_mat
                FROM " . $con->dbname . ".item_precio ip inner join " . $con->dbname . ".item_parametro im on ip.ite_id = im.ipar_ite_inscripcion
                     inner join " . $con->dbname . ".item_precio ip1 on im.ipar_ite_matriculacion = ip1.ite_id
                WHERE ip.ite_id = :ite_id
                        and ip.ipre_estado_precio = 'A'
                        and (now() between ip.ipre_fecha_inicio and ip.ipre_fecha_fin)
                    and ip1.ipre_estado_precio = 'A'
                        and (now() between ip1.ipre_fecha_inicio and ip1.ipre_fecha_fin)
                        and ip.ipre_estado = :estado
                        and ip.ipre_estado_logico = :estado
                    and ip1.ipre_estado = :estado
                        and ip1.ipre_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function ConsultarXUnidadModalPrecio
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Precio del item)
     */
    public function ConsultarXUnidadModalPrecio($uaca_id, $mod_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT ip.ipre_precio precio_ins, ip.ite_id
                FROM db_facturacion.item_parametro im inner join " . $con->dbname . ".item_precio ip on ip.ite_id = im.ipar_ite_inscripcion
                WHERE im.uaca_id = :uaca_id AND
                      im.mod_id = :mod_id AND
                      ip.ipre_estado_precio = 'A' AND
                      (now() between ip.ipre_fecha_inicio and ifnull(ip.ipre_fecha_fin,now())) AND
                      im.ipar_estado = :estado AND
                      im.ipar_estado_logico = :estado AND
                      ip.ipre_estado = :estado AND
                      ip.ipre_estado_logico = :estado";
        \app\models\Utilities::putMessageLogFile('$sql precio:' . $sql);
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Obtenersinsidxnumsolicitud
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return  $resultData  obtener el sins_id
     */
    public function Obtenersinsidxnumsolicitud($num_solicitud) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;

        $sql = "SELECT  sins.sins_id
                FROM " . $con->dbname . ".solicitud_inscripcion sins
                WHERE
                       sins.num_solicitud = :num_solicitud AND
                       sins.sins_estado = :estado AND
                       sins.sins_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":num_solicitud", $num_solicitud, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function actualizaSolicitudInscripcion (Actualiza unidad, carrera y modalidad)
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaSolicitudInscripcion($sins_id, $uaca_id, $mod_id, $eaca_id, $sins_beca, $sins_usuario_modifica) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".solicitud_inscripcion
                SET sins_fecha_modificacion = :sins_fecha_modificacion,
                    uaca_id = :uaca_id,
                    mod_id = :mod_id,
                    eaca_id = :eaca_id,
                    sins_beca = :sins_beca,
                    sins_usuario_modifica = :sins_usuario_modifica
                WHERE sins_id = :sins_id AND
                      sins_estado =:estado AND
                      sins_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":sins_beca", $sins_beca, \PDO::PARAM_STR);
        $comando->bindParam(":sins_usuario_modifica", $sins_usuario_modifica, \PDO::PARAM_INT);
        $comando->bindParam(":sins_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);

        $response = $comando->execute();
        return $response;
    }
    /**
     * Function Consultarsolicitudxid
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return  $resultData
     */
    public function Consultarsolicitudxid($sins_id) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT
                    sins.sins_id,
                    sins.int_id,
                    sins.uaca_id,
                    sins.mod_id,
                    sins.eaca_id,
                    sins.mest_id,
                    sins.emp_id,
                    sins.rsin_id, -- res_sol_inscripcion
                    orp.opag_id,
                    orp.opag_subtotal,
                    orp.opag_total,
                    orp.opag_estado_pago,
                    desg.ite_id,
                    desg.dpag_subtotal,
                    desg.dpag_total,
                    desg.dpag_estado_pago,
                    sdfa.sdfa_nombres,
                    sdfa.sdfa_apellidos,
                    sdfa.sdfa_tipo_dni,
                    sdfa.sdfa_dni,
                    sdfa.sdfa_direccion,
                    sdfa.sdfa_telefono,
                    sdfa.sdfa_correo
                    FROM " . $con->dbname . ".solicitud_inscripcion sins
                    INNER JOIN " . $con1->dbname . ".orden_pago orp ON orp.sins_id = sins.sins_id
                    INNER JOIN " . $con1->dbname . ".desglose_pago desg ON desg.opag_id = orp.opag_id
                    INNER JOIN " . $con->dbname . ".solicitud_datos_factura sdfa ON sdfa.sins_id = sins.sins_id
                    WHERE
                    sins.sins_id = :sins_id AND
                    sins.sins_estado = :estado AND
                    sins.sins_estado_logico = :estado AND
                    orp.opag_estado = :estado AND
                    orp.opag_estado_logico = :estado AND
                    desg.dpag_estado = :estado AND
                    desg.dpag_estado_logico = :estado AND
                    sdfa.sdfa_estado = :estado AND
                    sdfa.sdfa_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    /**
     * Function Consultarsolicitudescuento
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return  $resultData
     */
    public function Consultarsolicitudescuento($sins_id) {
        $con = \Yii::$app->db_facturacion;
        //$estado = 1;

        $sql = "SELECT
                    sdes_id
                WHERE
                    sins.sins_id = :sins_id ";

        $comando = $con->createCommand($sql);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sins_id", $sins_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}
