<?php

namespace app\modules\admision\models;

use app\modules\academico\models\EstudioAcademico;
use app\modules\admision\models\PersonaGestionTmp;
use app\modules\admision\models\BitacoraActividadesTmp;
use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "oportunidad".
 *
 * @property int $opo_id
 * @property string $opo_codigo
 * @property int $emp_id
 * @property int $pcon_id
 * @property int $pben_id
 * @property int $mest_id
 * @property int $eaca_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $tove_id
 * @property int $tsca_id
 * @property int $ccan_id
 * @property int $oper_id
 * @property int $ins_id
 * @property int $padm_id
 * @property int $eopo_id
 * @property string $opo_fecha_registro
 * @property string $opo_estado_cierre
 * @property string $opo_fecha_ult_estado
 * @property int $opo_usuario
 * @property int $opo_usuario_modif
 * @property string $opo_estado
 * @property string $opo_fecha_creacion
 * @property string $opo_fecha_modificacion
 * @property string $opo_estado_logico
 *
 * @property BitacoraActividades[] $bitacoraActividades
 * @property PersonaContratante $pcon
 * @property PersonaBeneficiario $pben
 * @property TipoOportunidadVenta $tove
 * @property TipoSubCarrera $tsca
 * @property ConocimientoCanal $ccan
 * @property OportunidadPerdida $oper
 * @property PersonalAdmision $padm
 * @property EstadoOportunidad $eopo
 */
class Oportunidad extends \app\modules\admision\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'oportunidad';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_crm');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['emp_id', 'pcon_id', 'pben_id', 'uaca_id', 'mod_id', 'tove_id', 'ccan_id', 'eopo_id', 'opo_usuario', 'opo_estado', 'opo_estado_logico'], 'required'],
            [['emp_id', 'pcon_id', 'pben_id', 'mest_id', 'eaca_id', 'uaca_id', 'mod_id', 'tove_id', 'tsca_id', 'ccan_id', 'oper_id', 'ins_id', 'padm_id', 'eopo_id', 'opo_usuario', 'opo_usuario_modif'], 'integer'],
            [['opo_fecha_registro', 'opo_fecha_ult_estado', 'opo_fecha_creacion', 'opo_fecha_modificacion'], 'safe'],
            [['opo_codigo'], 'string', 'max' => 250],
            [['opo_estado_cierre', 'opo_estado', 'opo_estado_logico'], 'string', 'max' => 1],
            [['pcon_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonaContratante::className(), 'targetAttribute' => ['pcon_id' => 'pcon_id']],
            [['pben_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonaBeneficiario::className(), 'targetAttribute' => ['pben_id' => 'pben_id']],
            [['tove_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoOportunidadVenta::className(), 'targetAttribute' => ['tove_id' => 'tove_id']],
            [['tsca_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSubCarrera::className(), 'targetAttribute' => ['tsca_id' => 'tsca_id']],
            [['ccan_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConocimientoCanal::className(), 'targetAttribute' => ['ccan_id' => 'ccan_id']],
            [['oper_id'], 'exist', 'skipOnError' => true, 'targetClass' => OportunidadPerdida::className(), 'targetAttribute' => ['oper_id' => 'oper_id']],
            [['padm_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalAdmision::className(), 'targetAttribute' => ['padm_id' => 'padm_id']],
            [['eopo_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstadoOportunidad::className(), 'targetAttribute' => ['eopo_id' => 'eopo_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'opo_id' => 'Opo ID',
            'opo_codigo' => 'Opo Codigo',
            'emp_id' => 'Emp ID',
            'pcon_id' => 'Pcon ID',
            'pben_id' => 'Pben ID',
            'mest_id' => 'Mest ID',
            'eaca_id' => 'Eaca ID',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'tove_id' => 'Tove ID',
            'tsca_id' => 'Tsca ID',
            'ccan_id' => 'Ccan ID',
            'oper_id' => 'Oper ID',
            'ins_id' => 'Ins ID',
            'padm_id' => 'Padm ID',
            'eopo_id' => 'Eopo ID',
            'opo_fecha_registro' => 'Opo Fecha Registro',
            'opo_estado_cierre' => 'Opo Estado Cierre',
            'opo_fecha_ult_estado' => 'Opo Fecha Ult Estado',
            'opo_usuario' => 'Opo Usuario',
            'opo_usuario_modif' => 'Opo Usuario Modif',
            'opo_estado' => 'Opo Estado',
            'opo_fecha_creacion' => 'Opo Fecha Creacion',
            'opo_fecha_modificacion' => 'Opo Fecha Modificacion',
            'opo_estado_logico' => 'Opo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBitacoraActividades() {
        return $this->hasMany(BitacoraActividades::className(), ['opo_id' => 'opo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPcon() {
        return $this->hasOne(PersonaContratante::className(), ['pcon_id' => 'pcon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPben() {
        return $this->hasOne(PersonaBeneficiario::className(), ['pben_id' => 'pben_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTove() {
        return $this->hasOne(TipoOportunidadVenta::className(), ['tove_id' => 'tove_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTsca() {
        return $this->hasOne(TipoSubCarrera::className(), ['tsca_id' => 'tsca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCcan() {
        return $this->hasOne(ConocimientoCanal::className(), ['ccan_id' => 'ccan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOper() {
        return $this->hasOne(OportunidadPerdida::className(), ['oper_id' => 'oper_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPadm() {
        return $this->hasOne(PersonalAdmision::className(), ['padm_id' => 'padm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEopo() {
        return $this->hasOne(EstadoOportunidad::className(), ['eopo_id' => 'eopo_id']);
    }

    /**
     * Function consulta los medios de conocimiento y canal.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarConocimientoCanal($opcion) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                   ccan.ccan_id as id,
                   ccan.ccan_nombre as name
                FROM
                   " . $con->dbname . ".conocimiento_canal  ccan ";
        $sql .= "
                WHERE
                   ccan.ccan_estado = :estado AND
                   ccan.ccan_estado_logico = :estado
                ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opcion", $opcion, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta los medios de conocimiento y canal.
     * @author kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOportunidadesByContact($arrFiltro = array(), $pges_id, $tipo) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['interesado'] != "") {
                $str_search = "(concat(pges_pri_nombre, ' ',  ' ', pges_pri_apellido, ' ') like :interesado or pges_contacto_empresa like :interesado) AND ";
            }
            if ($arrFiltro['agente'] != "") {
                $str_search .= " (concat(per_pri_nombre, ' ', ifnull(per_seg_nombre,' '), ' ', per_pri_apellido, ' ', ifnull(per_seg_apellido,' ')) like :agente OR padm_codigo like :agente)  AND";
            }
            if ($arrFiltro['f_atencion'] != "") {
                $str_search .= " (fecha_atencion >= :fec_atencion_ini and fecha_atencion <= :fec_atencion_fin)  AND";
            }
            if ($arrFiltro['estado'] > 0) {
                $str_search .= "  op.eopo_id = :estado_ate AND ";
            }
        }
        $sql = "
                SELECT
                    op.pges_id as pges_id,
                    op.opo_id as id,
                    emp.emp_nombre_comercial as empresa,
                    op.opo_codigo as codigo,
                    concat(ifnull(agent.per_pri_nombre,''), ' ', ifnull(agent.per_pri_apellido,'')) as agente,
                    uac.uaca_nombre as linea_servicio,
                    moda.mod_nombre as modalidad,
                    pges.pges_cedula as identificacion,
                    pges.pges_pasaporte as pasaporte,
                    eo.eopo_id as estado_oportunidad_id,
                    eo.eopo_nombre as estado_oportunidad,
                    -- tov.tove_nombre as tipo_oportunidad,
                    ifnull((SELECT topo.tove_nombre
                    FROM db_crm.oportunidad as opo
                    INNER JOIN db_crm.tipo_oportunidad_venta as topo on topo.tove_id=opo.tove_id
                    WHERE opo_id = op.opo_id),'N/A') as tipo_oportunidad,
                    case emp.emp_id
                    when 1 then (select eaca.eaca_nombre from " . $con2->dbname . ".estudio_academico eaca where eaca.eaca_id = op.eaca_id)
                    when 2 then (select mes.mest_nombre from " . $con2->dbname . ".modulo_estudio mes where mes.mest_id = op.mest_id)
                    when 3 then (select mes.mest_nombre from " . $con2->dbname . ".modulo_estudio mes where mes.mest_id = op.mest_id)
                    else null
                      end as 'curso',
                    ifnull((SELECT oac.oact_nombre
                    FROM db_crm.bitacora_actividades bac
                    INNER JOIN db_crm.observacion_actividades as oac on oac.oact_id=bac.oact_id
                    WHERE opo_id = op.opo_id
                    order by bact_fecha_creacion desc
                    LIMIT 1),'') as observa
                FROM  " . $con->dbname . ".oportunidad op
                    inner join " . $con1->dbname . ".empresa as emp on emp.emp_id=op.emp_id
                    inner join " . $con->dbname . ".persona_gestion pges on pges.pges_id = op.pges_id
                    inner join " . $con1->dbname . ".tipo_persona tp on tp.tper_id = pges.tper_id
                    inner join " . $con->dbname . ".estado_oportunidad eo on eo.eopo_id = op.eopo_id
                    inner join " . $con->dbname . ".personal_admision padm on padm.padm_id = op.padm_id
                    inner join " . $con1->dbname . ".persona agent on agent.per_id = padm.per_id
                    inner join " . $con2->dbname . ".modalidad moda on moda.mod_id = op.mod_id
                    inner join " . $con2->dbname . ".unidad_academica uac on uac.uaca_id = op.uaca_id
                    -- inner join " . $con->dbname . ".tipo_oportunidad_venta tov on tov.tove_id = op.tove_id

                WHERE ";
        if (!empty($str_search)) {
            $sql .= "$str_search "
                    . " op.opo_id = opo_id AND ";
        }
        $sql .= "       op.pges_id = $pges_id and
                        op.opo_estado = 1
                        and op.opo_estado_logico = 1
                        and pges.pges_estado = 1
                        and pges.pges_estado_logico = 1
                        and padm.padm_estado = 1
                        and padm.padm_estado_logico = 1
                        and agent.per_estado = 1
                        and agent.per_estado_logico = 1
                        -- and tov.tove_estado = 1
                        -- and tov.tove_estado_logico = 1
                        order by op.opo_id desc
               ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["interesado"] . "%";
            $fecha_atencion_ini = $arrFiltro["f_atencion"] . " 00:00:00";
            $fecha_atencion_fin = $arrFiltro["f_atencion"] . " 23:59:59";
            $agente = "%" . $arrFiltro["agente"] . "%";
            $estado_ate = $arrFiltro["estado"];

            if ($arrFiltro['interesado'] != "") {
                $comando->bindParam(":interesado", $search_cond, \PDO::PARAM_STR);
            }
            if ($arrFiltro['agente'] != "") {
                $comando->bindParam(":agente", $agente, \PDO::PARAM_STR);
            }
            if ($arrFiltro['f_atencion'] != "") {
                $comando->bindParam(":fec_atencion_ini", $fecha_atencion_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_atencion_fin", $fecha_atencion_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['estado'] > 0) {
                $comando->bindParam(":estado_ate", $estado_ate, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        if ($tipo == 1) {
            return $resultData;
        } else {
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
            return $dataProvider;
        }
    }
    /**
     * Function consultar otros estudios
     * @author Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOtroEstudio() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                   oper.oper_id as id,
                   oper.oper_nombre as name
                FROM
                   " . $con->dbname . ".otro_estudio_academico oeac ";
        $sql .= "  WHERE
                   oper.oper_estado = :estado AND
                   oper.oper_estado_logico = :estado
                ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    /**
     * Function consulta los medios de conocimiento y canal.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOportunidadPerdida() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                   oper.oper_id as id,
                   oper.oper_nombre as name
                FROM
                   " . $con->dbname . ".oportunidad_perdida  oper ";
        $sql .= "  WHERE
                   oper.oper_estado = :estado AND
                   oper.oper_estado_logico = :estado
                ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta los medios de conocimiento y canal.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPersonaGestionPorOporId($id_opor) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                SELECT pges.*, pai.pai_nombre as pais,
                    opor.emp_id
                FROM  " . $con->dbname . ".oportunidad as opor
                    JOIN " . $con->dbname . ".persona_gestion pges on pges.pges_id=opor.pges_id
                    JOIN " . $con1->dbname . ".pais pai on pai.pai_id = pges.pai_id_nacimiento
                WHERE
                    opor.opo_id = :opo_id
                    AND opor.opo_estado = :estado
                    AND opor.opo_estado_logico=:estado
                    AND pges.pges_estado = :estado
                    AND pges.pges_estado_logico=:estado
              ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opo_id", $id_opor, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultar las oportunidades de venta.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOportunidad($arrFiltro = array(), $resultado, $onlyData = false) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['interesado'] != "") {
                $str_search = " a.contacto like :interesado AND ";
            }
            if ($arrFiltro['agente'] != "") {
                $str_search .= " (a.agente like :agente OR padm_codigo like :agente)  AND";
            }
            if ($arrFiltro['empresa'] > "0") {
                $str_search .= " a.emp_id = :empresa  AND";
            }
            if ($arrFiltro['estado'] != "" && $arrFiltro['estado'] > 0) {
                $str_search .= "  a.eopo_id = :estado_ate AND ";
            }
            if ($arrFiltro['fec_registro_ini'] != "" && $arrFiltro['fec_registro_fin'] != "") {
                $str_search .= "  a.fecha_registro >= :fec_registro_ini AND ";
                $str_search .= "  a.fecha_registro <= :fec_registro_fin AND ";
            }
            if ($arrFiltro['fec_proxima_ini'] != "" && $arrFiltro['fec_proxima_fin'] != "") {
                $str_search .= "  a.fecha_proxima >= :fec_proxima_ini AND ";
                $str_search .= "  a.fecha_proxima <= :fec_proxima_fin AND ";
            }
        }
        $sql = "
                SELECT * FROM (
                    SELECT o.opo_id,
                            lpad(ifnull(o.opo_codigo,0),7,'0') as opo_codigo,
                            o.pges_id,
                            (case when pg.tper_id = 1 then
                                    concat(ifnull(pg.pges_pri_nombre,''), ' ', ifnull(pg.pges_seg_nombre,''), ' ', ifnull(pg.pges_pri_apellido,''), ' ', ifnull(pg.pges_seg_apellido,''))
                                    else pg.pges_razon_social end) as contacto,
                            o.emp_id,
                            e.emp_razon_social as des_empresa,
                            o.uaca_id,
                            ua.uaca_descripcion as des_unidad,
                            (case when (o.emp_id = 1) then
                                (case when (o.uaca_id < 3) then
                                    (select ea.eaca_descripcion from " . $con2->dbname . ".estudio_academico ea where ea.eaca_id = o.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                                else
                                    (select me.mest_descripcion from " . $con2->dbname . ".modulo_estudio me where me.mest_id = o.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                                end)
                            else
                                (select me.mest_descripcion from " . $con2->dbname . ".modulo_estudio me where me.mest_id = o.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                            end)
                            as des_estudio,
                            o.mest_id,
                            o.eaca_id,
                            o.eopo_id,
                            eo.eopo_descripcion as des_estado,
                            o.mod_id,
                            m.mod_descripcion as des_modalidad,
                            -- o.opo_fecha_registro as fecha,
                            (select max(bact_fecha_registro) from db_crm.bitacora_actividades b
                                    where b.opo_id = o.opo_id and b.bact_estado = 1 and bact_estado_logico = 1) as fecha_registro,
                            case when o.eopo_id = '3' then
                                    '' else
                                    (select max(bact_fecha_proxima_atencion) from db_crm.bitacora_actividades b
                                     where b.opo_id = o.opo_id and b.bact_estado = 1 and bact_estado_logico = 1) end as fecha_proxima,
                            o.padm_id,
                            pa.padm_codigo,
                            concat(p.per_pri_nombre, ' ', ifnull(p.per_seg_nombre,' '), ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,' ')) as agente,
                            o.tsca_id,
                            (case when (o.tsca_id > 0) then
                                (select tcar_id from " . $con->dbname . ".tipo_sub_carrera tsc where tsc.tsca_id = o.tsca_id and tsc.tsca_estado = :estado and tsc.tsca_estado_logico = :estado)
                                else 0 end) as tcar_id
                    FROM " . $con->dbname . ".oportunidad o inner join " . $con->dbname . ".persona_gestion pg on pg.pges_id = o.pges_id
                         inner join " . $con1->dbname . ".empresa e on e.emp_id = o.emp_id
                         inner join " . $con2->dbname . ".unidad_academica ua on ua.uaca_id = o.uaca_id
                         inner join " . $con->dbname . ".estado_oportunidad eo on eo.eopo_id = o.eopo_id
                         inner join " . $con2->dbname . ".modalidad m on m.mod_id = o.mod_id
                         inner join " . $con->dbname . ".personal_admision pa on o.padm_id = pa.padm_id
                         inner join " . $con1->dbname . ".persona p on pa.per_id = p.per_id
                    WHERE  date(o.opo_fecha_creacion) =  DATE(now()) and
                           o.opo_estado = :estado
                           and o.opo_estado_logico = :estado
                           and pg.pges_estado = :estado
                           and pg.pges_estado_logico = :estado
                           and e.emp_estado = :estado
                           and e.emp_estado_logico = :estado
                           and ua.uaca_estado = :estado
                           and ua.uaca_estado_logico = :estado
                           and eo.eopo_estado = :estado
                           and eo.eopo_estado_logico = :estado
                           and m.mod_estado = :estado
                           and m.mod_estado_logico = :estado
                           and pa.padm_estado = :estado
                           and pa.padm_estado_logico = :estado
                           and p.per_estado = :estado
                           and p.per_estado_logico = :estado ) a ";

        if (!empty($str_search)) {
            $sql .= "WHERE $str_search a.opo_id = a.opo_id
                                 ORDER BY a.opo_id desc ";
        } else {
            $sql .= "ORDER BY a.opo_id desc";
        }

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["interesado"] . "%";
            $agente = "%" . $arrFiltro["agente"] . "%";
            $estado_ate = $arrFiltro["estado"];
            $empresa = $arrFiltro["empresa"];

            if ($arrFiltro['interesado'] != "") {
                $comando->bindParam(":interesado", $search_cond, \PDO::PARAM_STR);
            }
            if ($arrFiltro['agente'] != "") {
                $comando->bindParam(":agente", $agente, \PDO::PARAM_STR);
            }
            if ($arrFiltro['empresa'] > "0") {
                $comando->bindParam(":empresa", $empresa, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estado'] != "" && $arrFiltro['estado'] > 0) {
                $comando->bindParam(":estado_ate", $estado_ate, \PDO::PARAM_INT);
            }
            $fec_registro_ini = $arrFiltro["fec_registro_ini"] . " 00:00:00";
            $fec_registro_fin = $arrFiltro["fec_registro_fin"] . " 23:59:59";

            if ($arrFiltro['fec_registro_ini'] != "" && $arrFiltro['fec_registro_fin'] != "") {
                $comando->bindParam(":fec_registro_ini", $fec_registro_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_registro_fin", $fec_registro_fin, \PDO::PARAM_STR);
            }
            $fec_proxima_ini = $arrFiltro["fec_proxima_ini"] . " 00:00:00";
            $fec_proxima_fin = $arrFiltro["fec_proxima_fin"] . " 23:59:59";
            if ($arrFiltro['fec_proxima_ini'] != "" && $arrFiltro['fec_proxima_fin'] != "") {
                $comando->bindParam(":fec_proxima_ini", $fec_proxima_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_proxima_fin", $fec_proxima_fin, \PDO::PARAM_STR);
            }
        }
        $resultData = $comando->queryAll();
        if ($resultado == 1) {
            return $resultData;
        } else {
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
            return $dataProvider;
        }
    }

    /**
     * Function insertarPersonaContrat
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return
     */
    public function insertarPersonaContrat($con, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0] . "'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i] . "'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . '.' . $name_table . " ($param_sql) VALUES($bdet_sql);";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null) {
                $trans->commit();
                return $idtable;
            }
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**     * **
     * Function insertarPersonaContratante grabar a personas contratantes.
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarPersonaGestionContactoLeads($con, $pges_id, $data) {
        //pgco_id
        $pgco_primer_apellido = "";
        $sql = "INSERT INTO " . $con->dbname . ".persona_gestion_contacto
                (pges_id,pgco_primer_nombre,pgco_primer_apellido,pgco_correo,pgco_celular,pai_id_contacto,pgco_estado,pgco_estado_logico)VALUES
                (:pges_id,:pgco_primer_nombre,:pgco_primer_apellido,:pgco_correo,:pgco_celular,57,1,1)";
        $command = $con->createCommand($sql);
        $command->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $command->bindParam(":pgco_primer_nombre", $data['pgest_nombre'], \PDO::PARAM_STR);
        $command->bindParam(":pgco_primer_apellido", $pgco_primer_apellido, \PDO::PARAM_STR);
        $command->bindParam(":pgco_correo", $data['pgest_correo'], \PDO::PARAM_STR);
        $command->bindParam(":pgco_celular", $data['pgest_numero'], \PDO::PARAM_STR);

        $command->execute();
        return $con->getLastInsertID();
    }

    /**
     * Function insertarPersonaContrat
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return
     */
    public function insertarPersonaBene($con, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $param_sql .= "" . $keys[0];
        $bdet_sql .= "'" . $parameters[0] . "'";
        for ($i = 1; $i < count($parameters); $i++) {
            if (isset($parameters[$i])) {
                $param_sql .= ", " . $keys[$i];
                $bdet_sql .= ", '" . $parameters[$i] . "'";
            }
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . '.' . $name_table . " ($param_sql) VALUES($bdet_sql);";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.' . $name_table);
            if ($trans !== null) {
                $trans->commit();
                return $idtable;
            }
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**     * **
     * Function insertarPersonaContratante grabar a personas contratantes.
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarPersonaBeneficiariaLeads($con, $pges_id, $pcon_id) {
        //pben_id
        $pben_observacion = "";
        $sql = "INSERT INTO " . $con->dbname . ".persona_beneficiario
                (pges_id,pcon_id,pben_observacion,pben_estado,pben_estado_logico)VALUES
                (:pges_id,:pcon_id,:pben_observacion,1,1)";
        $command = $con->createCommand($sql);
        $command->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $command->bindParam(":pcon_id", $pcon_id, \PDO::PARAM_INT);
        $command->bindParam(":pben_observacion", $pben_observacion, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    /** Function consulta los agentes segun unidad academcica.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarAgente($nivel, $modalidad, $agenteaun, $per_id) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $cargo = 3;
        $sql = "SELECT
                   pa.padm_id as id,
                   CONCAT (per.per_pri_nombre, ' ', per.per_pri_apellido) as name
                FROM
                   " . $con->dbname . ".personal_nivel_modalidad psm "
                . "INNER JOIN " . $con->dbname . ".personal_admision_cargo pac ON pac.paca_id = psm.paca_id "
                . "INNER JOIN " . $con->dbname . ".personal_admision pa ON pa.padm_id = pac.padm_id "
                . "INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = pa.per_id ";
        $sql .= "  WHERE ";

        if ($agenteaun == 1 || $agenteaun == 2 || $per_id == 1) {
            $sql .= "  psm.nint_id = :nivel AND
                   psm.mod_id = :modalidad AND
                   -- pac.car_id = :cargo AND ";
        }
        $sql .= "  pnmo_estado = :estado AND
                   pnmo_estado_logico = :estado AND
                   paca_estado = :estado AND
                   paca_estado_logico = :estado AND
                   padm_estado = :estado AND
                   padm_estado_logico = :estado AND
                   per_estado = :estado AND
                   per_estado_logico = :estado
                ORDER BY name asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":nivel", $nivel, \PDO::PARAM_INT);
        $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        $comando->bindParam(":cargo", $cargo, \PDO::PARAM_INT);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /** Function consulta los agentes segun unidad academica.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarTipoCarrera() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                   tcar.tcar_id as id,
                   tcar.tcar_nombre as name
                FROM
                   " . $con->dbname . ".tipo_carrera tcar
                WHERE
                   tcar.tcar_estado = :estado AND
                   tcar.tcar_estado_logico = :estado
                ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /** Function consulta las subcarreras segun su carrera.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarSubCarrera($tcar_id) {
        $ninguno [] = Array('id' => '0', 'name' => Yii::t('formulario', 'Any'));
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                   tscar.tsca_id as id,
                   tscar.tsca_nombre as name
                FROM
                   " . $con->dbname . ".tipo_sub_carrera tscar
                WHERE
                   tscar.tcar_id = :tcar_id AND
                   tscar.tsca_estado = :estado AND
                   tscar.tsca_estado_logico = :estado
                ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":tcar_id", $tcar_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        $resultData = array_merge($ninguno, $resultData);
        return $resultData;
    }

    /**
     * Function consultarIdPersonaGestion
     * @author  Kleber Loayza
     * @property
     * @return
     */
    public function consultarIdPersonaContratante($pergestion_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                    SELECT
                    ifnull(pcon_id,0) as pcon_id
                    FROM   db_crm.persona_contratante
                    WHERE
                    pges_id = $pergestion_id
                    AND pcon_estado = $estado
                    AND pcon_estado_logico=$estado
               ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['pcon_id']))
            return 0;
        else {
            return $resultData['pcon_id'];
        }
    }

    /**
     * Function consultarIdPersonaGestion
     * @author  Kleber Loayza
     * @property
     * @return
     */
    public function consultarIdPersonaBeneficiario($pergestion_id) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "
                    SELECT
                    ifnull(pben_id,0) as pben_id
                    FROM   db_crm.persona_beneficiario
                    WHERE
                    pges_id = $pergestion_id
                    AND pben_estado = $estado
                    AND pben_estado_logico=$estado
               ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();
        if (empty($resultData['pben_id']))
            return 0;
        else {
            return $resultData['pben_id'];
        }
    }

    /**
     * Function obtener carreras segun unidad academica y modalidad
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarCarreraModalidad($unidad, $modalidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
                SELECT
                        eac.eaca_id as id,
                        eac.eaca_nombre as name
                    FROM
                        " . $con->dbname . ".modalidad_estudio_unidad as mcn
                        INNER JOIN " . $con->dbname . ".estudio_academico as eac on eac.eaca_id = mcn.eaca_id
                    WHERE
                        mcn.uaca_id =:unidad AND
                        mcn.mod_id =:modalidad AND
                        eac.eaca_estado_logico=:estado AND
                        eac.eaca_estado=:estado AND
                        mcn.meun_estado_logico = :estado AND
                        mcn.meun_estado = :estado
                        ORDER BY name asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
        $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function insertarOportunidad crea oportunidades de venta.
     * @author  Jefferson Conde <analistadesarrollo03@uteg.edu.ec>;
     *          Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     *          Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarOportunidad($opo_codigo, $emp_id, $pges_id, $mest_id, $eaca_id, $uaca_id, $mod_id, $tove_id, $tsca_id, $ccan_id, $eopo_id, $opo_hora_ini_contacto, $opo_hora_fin_contacto, $opo_fecha_registro, $padm_id, $opo_usuario) {
        $con = \Yii::$app->db_crm;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "opo_estado";
        $bdet_sql = "1";

        $param_sql .= ", opo_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($opo_codigo)) {
            $param_sql .= ", opo_codigo";
            $bdet_sql .= ", :opo_codigo";
        }
        if (isset($emp_id)) {
            $param_sql .= ", emp_id";
            $bdet_sql .= ", :emp_id";
        }
        if (isset($pges_id)) {
            $param_sql .= ", pges_id";
            $bdet_sql .= ", :pges_id";
        }
        if (isset($mest_id)) {
            $param_sql .= ", mest_id";
            $bdet_sql .= ", :mest_id";
        }
        if (isset($eaca_id)) {
            $param_sql .= ", eaca_id";
            $bdet_sql .= ", :eaca_id";
        }
        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bdet_sql .= ", :uaca_id";
        }
        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bdet_sql .= ", :mod_id";
        }
        if (isset($tove_id)) {
            $param_sql .= ", tove_id";
            $bdet_sql .= ", :tove_id";
        }
        if (isset($tsca_id)) {
            $param_sql .= ", tsca_id";
            $bdet_sql .= ", :tsca_id";
        }
        if (isset($ccan_id)) {
            $param_sql .= ", ccan_id";
            $bdet_sql .= ", :ccan_id";
        }
        if (isset($eopo_id)) {
            $param_sql .= ", eopo_id";
            $bdet_sql .= ", :eopo_id";
        }
        if (isset($opo_hora_ini_contacto)) {
            $param_sql .= ", opo_hora_ini_contacto";
            $bdet_sql .= ", :opo_hora_ini_contacto";
        }
        if (isset($opo_hora_fin_contacto)) {
            $param_sql .= ", opo_hora_fin_contacto";
            $bdet_sql .= ", :opo_hora_fin_contacto";
        }

        if (isset($opo_fecha_registro)) {
            $param_sql .= ", opo_fecha_registro";
            $bdet_sql .= ", :opo_fecha_registro";
        }
        if (isset($padm_id)) {
            $param_sql .= ", padm_id";
            $bdet_sql .= ", :padm_id";
        }
        if (isset($opo_usuario)) {
            $param_sql .= ", opo_usuario";
            $bdet_sql .= ", :opo_usuario";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".oportunidad ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($opo_codigo)) {
                $comando->bindParam(':opo_codigo', $opo_codigo, \PDO::PARAM_STR);
            }
            if (isset($emp_id)) {
                $comando->bindParam(':emp_id', $emp_id, \PDO::PARAM_INT);
            }
            if (isset($pges_id)) {
                $comando->bindParam(':pges_id', $pges_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($mest_id)))) {
                $comando->bindParam(':mest_id', $mest_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($eaca_id)))) {
                $comando->bindParam(':eaca_id', $eaca_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($uaca_id)))) {
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($mod_id)))) {
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($tove_id)))) {
                $comando->bindParam(':tove_id', $tove_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($tsca_id)))) {
                $comando->bindParam(':tsca_id', $tsca_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($ccan_id)))) {
                $comando->bindParam(':ccan_id', $ccan_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($eopo_id)))) {
                $comando->bindParam(':eopo_id', $eopo_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($opo_hora_ini_contacto)))) {
                $comando->bindParam(':opo_hora_ini_contacto', $opo_hora_ini_contacto, \PDO::PARAM_STR);
            }
            if (!empty((isset($opo_hora_fin_contacto)))) {
                $comando->bindParam(':opo_hora_fin_contacto', $opo_hora_fin_contacto, \PDO::PARAM_STR);
            }
            if (!empty((isset($opo_fecha_registro)))) {
                $comando->bindParam(':opo_fecha_registro', $opo_fecha_registro, \PDO::PARAM_STR);
            }
            if (!empty((isset($padm_id)))) {
                $comando->bindParam(':padm_id', $padm_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($opo_usuario)))) {
                $comando->bindParam(':opo_usuario', $opo_usuario, \PDO::PARAM_INT);
            }

            $result = $comando->execute();
            $idtable= $con->getLastInsertID($con->dbname . '.oportunidad');
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return 0;
        }
    }

    /**
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function consultarOportVentas($uaca_id) {
        $con = \Yii::$app->db_crm;
        $sql = "SELECT tove_id FROM " . $con->dbname . ".tipo_oportunidad_venta "
                . " WHERE tove_estado=1 AND uaca_id=:uaca_id LIMIT 1 ;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        //$comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //Falso si no Existe
        return $rawData; //Si Existe en la Tabla
    }

    /**     * **
     * Function insertarPersonaContratante grabar a personas contratantes.
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarOportunidadLeads($con, $opo_codigo, $emp_id, $pges_id, $padm_id, $data) {
        //opo_id,oper_id,ins_id,
        $mest_id = NULL;
        $eaca_id = NULL;
        $tove_id = NULL;
        if ($emp_id == "1") {
            $eaca_id = $data['pgest_carr_nombre'];
        } elseif ($emp_id == "2" || $emp_id == "3") {
            $mest_id = $data['pgest_carr_nombre'];
        } else {

        }

        $uaca_id = $data['pgest_unidad_academica'];
        if ($uaca_id > 1) {
            $tove_id = $this->consultarOportVentas($uaca_id); //se puede obtener a partir d ela unidad academica
        }
        $mod_id = $data['pgest_modalidad'];
        $ccan_id = 2; //Redes sociales (Facebook) o ->$data['pgest_contacto'];
        $eopo_id = 1; //estado oportunidad => En Curso
        $hora_atiende = explode("_", $data['pgest_horario']); //Obtiene 3 Arreglos
        $opo_hora_ini_contacto = $hora_atiende[0]; //date('h:i',strtotime($hora_atiende[0]));
        $opo_hora_fin_contacto = $hora_atiende[2]; //date('h:i',strtotime($hora_atiende[2]));
        $opo_usuario = @Yii::$app->session->get("PB_iduser"); // 1 equivale al usuario administrador
        //$fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
        //$mest_id;
        $sql = "INSERT INTO " . $con->dbname . ".oportunidad
            (opo_codigo,emp_id,pges_id,eaca_id,uaca_id,mod_id,tove_id,ccan_id,mest_id,
             padm_id,eopo_id,opo_hora_ini_contacto,opo_hora_fin_contacto,opo_fecha_registro,opo_usuario,opo_estado,opo_estado_logico)VALUES
            (:opo_codigo,:emp_id,:pges_id,:eaca_id,:uaca_id,:mod_id,:tove_id,:ccan_id,:mest_id,
             :padm_id,:eopo_id,:opo_hora_ini_contacto,:opo_hora_fin_contacto,CURRENT_TIMESTAMP(),:opo_usuario,1,1)";

        $command = $con->createCommand($sql);
        $command->bindParam(":opo_codigo", $opo_codigo, \PDO::PARAM_STR);
        $command->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $command->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $command->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $command->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $command->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $command->bindParam(":tove_id", $tove_id, \PDO::PARAM_INT);
        //$command->bindParam(":tsca_id", $subcarera, \PDO::PARAM_INT);
        $command->bindParam(":ccan_id", $ccan_id, \PDO::PARAM_INT);
        $command->bindParam(":mest_id", $mest_id, \PDO::PARAM_INT);
        $command->bindParam(":padm_id", $padm_id, \PDO::PARAM_INT);
        $command->bindParam(":eopo_id", $eopo_id, \PDO::PARAM_INT);
        $command->bindParam(":opo_hora_ini_contacto", $opo_hora_ini_contacto, \PDO::PARAM_STR);
        $command->bindParam(":opo_hora_fin_contacto", $opo_hora_fin_contacto, \PDO::PARAM_STR);
        //$command->bindParam(":gcrm_fecha_registro", $fecha_registro, \PDO::PARAM_STR);
        $command->bindParam(":opo_usuario", $opo_usuario, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    /**     * **
     * Function insertarPersonaContratante grabar a personas contratantes.
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarOportunidadLotes($con, $opo_codigo, $emp_id, $pges_id, $padm_id, $eopo_id, $tove_id, $uaca_id, $mod_id, $data) {


        //INSERT INTO `db_crm`.`temporal_contactos`
        //(`id`,`id_contacto`,`fecha_registro`,`unidad_academica`,`canal_contacto`,`ultima_modalidad_interes`,`ultima_carrera_interes`,`medio_contacto_solicitado`,
        //`horario_contacto_solicitado`,`nombre`,`telefono`,`telefono_ext`,`correo`,`ciudad`,`pais`,`ultimo_estado`,`carrera_interes`,`modalidad`,`tipo_cliente`,
        //`agente_atencion`,`fecha_atencion`,`tipo_oportunidad`,`estado_contacto`,`estado_oportunidad`,`fecha_siguiente_atencion`,`hora_siguiente_atencion`,
        //`fecha_tentativa_pago`,`observacion`,`motivo_oportunidad_perdida`,`otra_universidad`,`tipo_observacion`)
        //opo_id,oper_id,ins_id,mest_id
        $eaca_id = EstudioAcademico::consultarIdsEstudioAca($data['ultima_carrera_interes']);
        //$uaca_id=UnidadAcademica::consultarIdsUnid_Academica($data['unidad_academica']);
        //$mod_id=Modalidad::consultarIdsModalidad($data['ultima_modalidad_interes']);
        $ccan_id = PersonaGestionTmp::consultarIdsConocimientoCanal($data['medio_contacto_solicitado']);
        ; //Redes sociales (Facebook) o ->$data['pgest_contacto'];
        $hora_atiende = explode("_", $data['horario_contacto_solicitado']); //Obtiene 3 Arreglos
        //$dateHi = new DateTime($hora_atiende[0]);
        //$dateHf = new DateTime($hora_atiende[2]);
        $opo_hora_ini_contacto = $hora_atiende[0]; //date('h:i',strtotime($hora_atiende[0]));
        $opo_hora_fin_contacto = $hora_atiende[2]; //date('h:i',strtotime($hora_atiende[2]));
        $opo_usuario = @Yii::$app->session->get("PB_iduser"); // 1 equivale al usuario administrador
        //$fecha_registro = date(Yii::$app->params["dateTimeByDefault"], strtotime($data['fecha_registro']));
        $fecha_registro = ($data['fecha_registro'] != '') ? date(Yii::$app->params["dateTimeByDefault"], strtotime($data['fecha_registro'])) : NULL;

        $sql = "INSERT INTO " . $con->dbname . ".oportunidad
            (opo_codigo,emp_id,pges_id,eaca_id,uaca_id,mod_id,tove_id,ccan_id,
             padm_id,eopo_id,opo_hora_ini_contacto,opo_hora_fin_contacto,opo_fecha_registro,opo_usuario,opo_estado,opo_estado_logico)VALUES
            (:opo_codigo,:emp_id,:pges_id,:eaca_id,:uaca_id,:mod_id,:tove_id,:ccan_id,
             :padm_id,:eopo_id,:opo_hora_ini_contacto,:opo_hora_fin_contacto,:opo_fecha_registro,:opo_usuario,1,1)";

        $command = $con->createCommand($sql);
        $command->bindParam(":opo_codigo", $opo_codigo, \PDO::PARAM_STR);
        $command->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $command->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $command->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $command->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $command->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $command->bindParam(":tove_id", $tove_id, \PDO::PARAM_INT);
        //$command->bindParam(":tsca_id", $subcarera, \PDO::PARAM_INT);
        $command->bindParam(":ccan_id", $ccan_id, \PDO::PARAM_INT);
        $command->bindParam(":padm_id", $padm_id, \PDO::PARAM_INT);
        $command->bindParam(":eopo_id", $eopo_id, \PDO::PARAM_INT);
        $command->bindParam(":opo_hora_ini_contacto", $opo_hora_ini_contacto, \PDO::PARAM_STR);
        $command->bindParam(":opo_hora_fin_contacto", $opo_hora_fin_contacto, \PDO::PARAM_STR);
        $command->bindParam(":opo_fecha_registro", $fecha_registro, \PDO::PARAM_STR);
        $command->bindParam(":opo_usuario", $opo_usuario, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    /**     * **
     * Function insertarPersonaContratante grabar a personas contratantes.
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarActividadLeads($con, $opo_id, $padm_id, $bact_descripcion) {
        //bact_id
        $usuario = @Yii::$app->session->get("PB_iduser");
        $eopo_id = 1; //???? En curso por defecto
        $oact_id = 1; //Observacion de Actividades
        $bact_usuario = $usuario;

        if (!empty($bact_descripcion)) {
            $sql = "INSERT INTO " . $con->dbname . ".bitacora_actividades
                    (opo_id,usu_id,padm_id,eopo_id,bact_fecha_registro,bact_fecha_proxima_atencion,oact_id,
                     bact_usuario,bact_descripcion, bact_estado,bact_estado_logico)VALUES
                    (:opo_id,:usu_id,:padm_id,:eopo_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),
                     :oact_id,:bact_usuario, :bact_descripcion, 1,1)";
        } else {
            $sql = "INSERT INTO " . $con->dbname . ".bitacora_actividades
                    (opo_id,usu_id,padm_id,eopo_id,bact_fecha_registro,bact_fecha_proxima_atencion,oact_id,
                     bact_usuario, bact_estado,bact_estado_logico)VALUES
                    (:opo_id,:usu_id,:padm_id,:eopo_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),
                     :oact_id,:bact_usuario, 1,1)";
        }
        $command = $con->createCommand($sql);
        $command->bindParam(":opo_id", $opo_id, \PDO::PARAM_INT);
        $command->bindParam(":usu_id", $usuario, \PDO::PARAM_INT);
        $command->bindParam(":padm_id", $padm_id, \PDO::PARAM_INT);
        $command->bindParam(":eopo_id", $eopo_id, \PDO::PARAM_INT);
        $command->bindParam(":oact_id", $oact_id, \PDO::PARAM_INT);
        $command->bindParam(":bact_usuario", $bact_usuario, \PDO::PARAM_INT);
        if (!empty($bact_descripcion)) {
            $command->bindParam(":bact_descripcion", $bact_descripcion, \PDO::PARAM_STR);
        }
        $command->execute();
        return $con->getLastInsertID();
    }

    /**     * **
     * Function insertarPersonaContratante grabar a personas contratantes.
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function insertarActividadLotes($con, $opo_id, $padm_id, $eopo_id, $bact_descripcion) {
        //bact_id
        $usuario = @Yii::$app->session->get("PB_iduser");
        $oact_id = 1; //Observacion de Actividades
        $bact_usuario = $usuario;
        $sql = "INSERT INTO " . $con->dbname . ".bitacora_actividades
                (opo_id,usu_id,padm_id,eopo_id,bact_fecha_registro,bact_descripcion,oact_id,
                 bact_usuario,bact_estado,bact_estado_logico)VALUES
                (:opo_id,:usu_id,:padm_id,:eopo_id,CURRENT_TIMESTAMP(),:bact_descripcion,:oact_id,:bact_usuario,1,1); ";

        $command = $con->createCommand($sql);
        $command->bindParam(":opo_id", $opo_id, \PDO::PARAM_INT);
        $command->bindParam(":usu_id", $usuario, \PDO::PARAM_INT);
        $command->bindParam(":padm_id", $padm_id, \PDO::PARAM_INT);
        $command->bindParam(":eopo_id", $eopo_id, \PDO::PARAM_INT);
        $command->bindParam(":oact_id", $oact_id, \PDO::PARAM_INT);
        $command->bindParam(":bact_usuario", $bact_usuario, \PDO::PARAM_INT);
        $command->bindParam(":bact_descripcion", $bact_descripcion, \PDO::PARAM_STR);

        $command->execute();
        return $con->getLastInsertID();
    }

    /**
     * Function insertarActividad grabar actividades de una oportunidad.
     * @author Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarActividad($opo_id, $usu_id, $padm_id, $eopo_id, $bact_fecha_registro, $oact_id, $bact_descripcion, $bact_fecha_proxima_atencion) {
        $con = \Yii::$app->db_crm;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "bact_estado";
        $bdet_sql = "1";

        $param_sql .= ", bact_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($opo_id)) {
            $param_sql .= ", opo_id";
            $bdet_sql .= ", :opo_id";
        }
        if (isset($usu_id)) {
            $param_sql .= ", usu_id";
            $bdet_sql .= ", :usu_id";
        }
        if (isset($padm_id)) {
            $param_sql .= ", padm_id";
            $bdet_sql .= ", :padm_id";
        }
        if (isset($eopo_id)) {
            $param_sql .= ", eopo_id";
            $bdet_sql .= ", :eopo_id";
        }
        if (isset($bact_fecha_registro)) {
            $param_sql .= ", bact_fecha_registro";
            $bdet_sql .= ", :bact_fecha_registro";
        }
        if (isset($usu_id)) {
            $param_sql .= ", bact_usuario";
            $bdet_sql .= ", :usu_id";
        }
        if (isset($oact_id)) {
            $param_sql .= ", oact_id";
            $bdet_sql .= ", :oact_id";
        }
        if (isset($bact_descripcion)) {
            $param_sql .= ", bact_descripcion";
            $bdet_sql .= ", :bact_descripcion";
        }
        if (isset($bact_fecha_proxima_atencion)) {
            $param_sql .= ", bact_fecha_proxima_atencion";
            $bdet_sql .= ", :bact_fecha_proxima_atencion";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".bitacora_actividades ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($opo_id)) {
                $comando->bindParam(':opo_id', $opo_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($usu_id)))) {
                $comando->bindParam(':usu_id', $usu_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($padm_id)))) {
                $comando->bindParam(':padm_id', $padm_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($eopo_id)))) {
                $comando->bindParam(':eopo_id', $eopo_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($bact_fecha_registro)))) {
                $comando->bindParam(':bact_fecha_registro', $bact_fecha_registro, \PDO::PARAM_STR);
            }
            if (!empty((isset($oact_id)))) {
                $comando->bindParam(':oact_id', $oact_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($bact_descripcion)))) {
                $comando->bindParam(':bact_descripcion', $bact_descripcion, \PDO::PARAM_STR);
            }
            if (!empty((isset($bact_fecha_proxima_atencion)))) {
                $comando->bindParam(':bact_fecha_proxima_atencion', $bact_fecha_proxima_atencion, \PDO::PARAM_STR);
            }
            $result = $comando->execute();
            $idtable = $con->getLastInsertID($con->dbname . '.bitacora_actividades');
            if ($trans !== null)
                $trans->commit();
            return $idtable;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return 0;
        }
    }

    /**
     * Function insertarActividad grabar actividades de una oportunidad.
     * @author Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function actualizarActividad($act_id, $usu_id, $padm_id, $fecatiende, $oact_id, $bact_descripcion, $fecproxima) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    (
                    "UPDATE " . $con->dbname . ".bitacora_actividades
                      SET
                          padm_id = ifnull(:padm_id, padm_id),
                          bact_fecha_modificacion = :bact_fecha_modificacion,
                          bact_fecha_registro = :bact_fecha_registro,
                          oact_id = :oact_id,
                          bact_descripcion = :bact_descripcion,
                          bact_fecha_proxima_atencion = :bact_fecha_proxima_atencion,
                          -- bact_usuario_modif = :usu_id,
                          usu_id = :usu_id
                      WHERE bact_id = :bact_id AND
                            bact_estado = :estado AND
                            bact_estado_logico = :estado"
            );
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":bact_fecha_registro", $fecatiende, \PDO::PARAM_STR);
            $comando->bindParam(":oact_id", $oact_id, \PDO::PARAM_INT);
            $comando->bindParam(":bact_fecha_proxima_atencion", $fecproxima, \PDO::PARAM_STR);
            $comando->bindParam(":bact_id", $act_id, \PDO::PARAM_INT);
            $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
            $comando->bindParam(":padm_id", $padm_id, \PDO::PARAM_INT);
            $comando->bindParam(":bact_descripcion", $bact_descripcion, \PDO::PARAM_STR);
            $comando->bindParam(":bact_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
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
     * Function consultarOportunHist consultar historial de las oportunidades por Id.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOportunHist($opo_id) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db;
        $estado = 1;
        $sql = "
                SELECT  ba.bact_id,
                        opo.pges_id,
                        concat(ifnull(per_pri_nombre,''), ' ', ifnull(per_pri_apellido,'')) as agente,
                        bact_fecha_registro as fecha_atencion,
                        ifnull(bact_fecha_proxima_atencion, '') as proxima_atencion,
                        ba.padm_id agente_id,
                        opo.opo_id,
                        pges.pges_cedula,
                        pges.pges_pasaporte,
                        eopo.eopo_id as estado_oportunidad_id,
                        eopo.eopo_nombre as estado_oportunidad,
                        ba.oact_id as id_observacion,
                        ifnull(oac.oact_nombre, '') as observacion,
                        ba.bact_descripcion,
                        ifnull((select concat(pers.per_pri_nombre, ' ', ifnull(pers.per_pri_apellido,' '))
                                  from " . $con1->dbname . ".usuario usu
                                  inner join " . $con1->dbname . ".persona pers on pers.per_id = usu.per_id
                                  where usu.usu_id = ba.usu_id),'') as usuario_ing
                FROM " . $con->dbname . ".oportunidad opo
                         inner join " . $con->dbname . ".persona_gestion pges on opo.pges_id = pges.pges_id
                         inner join " . $con->dbname . ".bitacora_actividades ba on opo.opo_id = ba.opo_id
                         inner join " . $con->dbname . ".estado_oportunidad eopo on eopo.eopo_id = ba.eopo_id
                         inner join " . $con->dbname . ".personal_admision pa on pa.padm_id = ba.padm_id
                         inner join " . $con1->dbname . ".persona per on per.per_id = pa.per_id
                         inner join " . $con->dbname . ".observacion_actividades oac on oac.oact_id = ba.oact_id
                WHERE 	opo.opo_id = :opo_id
                        and opo.opo_estado = :estado
                        and opo.opo_estado_logico = :estado
                        and ba.bact_estado = :estado
                        and ba.bact_estado_logico = :estado
                        and pa.padm_estado = :estado
                        and pa.padm_estado_logico = :estado
                        and per.per_estado = :estado
                        and per.per_estado_logico = :estado
                ORDER BY ba.bact_id desc

                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":opo_id", $opo_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

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
     * Function consulta id de agente autenticado segun per_id.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarAgenteAutenticado($per_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                    padm_id,
                    (case when ((grol_id=6) or (grol_id=14) or (grol_id=18) or (grol_id=9)) then
                           'SUP' else 'AGE' end) as rol
                FROM
                   " . $con->dbname . ".personal_admision ";
        $sql .= "  WHERE
                   per_id = :per_id AND
                   padm_estado = :estado AND
                   padm_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta el ultimo codigo de gestion generado.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarUltimoCodcrm() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT ifnull(MAX(CAST(opo_codigo AS UNSIGNED)),0) AS id
                FROM
                   " . $con->dbname . ".oportunidad ";
        $sql .= "  WHERE
                   opo_estado = :estado AND
                   opo_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryScalar(); //$comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta los datos generales del benificiario.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDatosBeni($opo_id) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT
                    pges.pges_id,
                    pges_pri_nombre,
                    pges_seg_nombre,
                    pges_pri_apellido,
                    pges_seg_apellido,
                    pges.pai_id_nacimiento,
                    pges.pges_celular,
                    pges.pges_correo,
                    pges.pges_domicilio_telefono,
                    pges.pges_domicilio_celular2,
                    top.tove_nombre as tipo_oventa,
                    opo.opo_fecha_registro as fecha_registro,
                    pges.tper_id,
                    pges.pges_razon_social,
                    pges.pges_contacto_empresa,
                    pges.pges_telefono_empresa,
                    pges.pges_direccion_empresa,
                    pges.pges_cargo,
                    pai.pai_nombre
                FROM   " . $con->dbname . ".oportunidad opo
                       INNER JOIN " . $con->dbname . ".persona_beneficiario pben on pben.pben_id = opo.pben_id
                       INNER JOIN " . $con->dbname . ".persona_gestion pges on pges.pges_id = pben.pges_id
                       INNER JOIN " . $con->dbname . ".tipo_oportunidad_venta top on top.tove_id = opo.tove_id
                       INNER JOIN " . $con1->dbname . ".pais pai on pai.pai_id = pges.pai_id_nacimiento
                WHERE
                   opo.opo_id = :opo_id AND
                   opo.opo_estado_logico = :estado AND
                   opo.opo_estado = :estado AND
                   pges.pges_estado_logico = :estado AND
                   pges.pges_estado = :estado AND
                   pben.pben_estado_logico = :estado AND
                   pben.pben_estado = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opo_id", $opo_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta el ultimo codigo de gestion generado.
     * @author Jefferson Conde <analistadesarrollo033@uteg.edu.ec>;
     * @param
     * @return
     */
    public function ConsultarPersonaxGestion($pges_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;

        $sql = "SELECT pg.pges_pri_nombre AS primer_nombre,
                   pg.pges_seg_nombre AS segundo_nombre,
                   pg.pges_pri_apellido AS primer_apellido,
                   pg.pges_seg_apellido AS segundo_apellido,
                   pg.pges_razon_social AS razon_social,
                   pg.pges_direccion_empresa AS direccion_empresa,
                   pg.pges_contacto_empresa AS contacto_empresa,
                   pg.pges_cargo AS cargo,
                   pg.pges_telefono_empresa AS telefono_empresa,
                   pg.pges_num_empleados AS num_empleados,
                   pg.pai_id_nacimiento AS pai_id_nacimiento,
                   pg.pro_id_nacimiento AS pro_id_nacimiento,
                   pg.can_id_nacimiento AS can_id_nacimiento,
                   pg.pges_celular AS celular,
                   pg.pges_cedula AS cedula,
                   pg.pges_pasaporte AS pasaporte,
                   pg.pges_domicilio_telefono AS pges_domicilio_telefono,
                   pg.pges_domicilio_celular2 AS pges_domicilio_celular2,
                   pg.pges_correo AS correo,
                   pg.econ_id AS cod_estado,
                   pg.pges_id AS pges_id,
                   ccan_id AS cod_medio_contacto,
                   pg.pges_nac_ecuatoriano
                FROM
                   " . $con->dbname . ". persona_gestion pg
                WHERE pg.pges_id = :pges_id
                      and pg.pges_estado = :estado
                      and pg.pges_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function modifica los datos de una oportunidad de venta.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     *          Grace Viteri   <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarOportunixId($emp_id, $opo_id, $mest_id, $eaca_id, $uaca_id, $mod_id, $tove_id, $tsca_id, $ccan_id, $opo_estado_cierre, $opo_fecha_ult_estado, $eopo_id, $usu_id, $oporper,$otro_estudio=null) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".oportunidad
                      SET emp_id = ifnull(:emp_id, emp_id),
                          mest_id = ifnull(:mest_id, mest_id),
                          eaca_id = ifnull(:eaca_id, eaca_id),
                          uaca_id = ifnull(:uaca_id, uaca_id),
                          mod_id = ifnull(:mod_id, mod_id),
                          tove_id = ifnull(:tove_id, tove_id),
                          oper_id = ifnull(:oper_id, oper_id),
                          tsca_id = ifnull(:tsca_id, tsca_id),
                          ccan_id = ifnull(:ccan_id, ccan_id),
                          eopo_id = ifnull(:eopo_id, eopo_id),
                          oeac_id = ifnull(:oeac_id, oeac_id),
                          opo_estado_cierre = ifnull(:opo_estado_cierre, opo_estado_cierre),
                          opo_fecha_ult_estado = ifnull(:opo_fecha_ult_estado, opo_fecha_ult_estado),
                          opo_fecha_modificacion = :opo_fecha_modificacion,
                          opo_usuario_modif = :usu_id
                      WHERE opo_id = :opo_id AND
                            opo_estado = :estado AND
                            opo_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":opo_id", $opo_id, \PDO::PARAM_INT);
            $comando->bindParam(":mest_id", $mest_id, \PDO::PARAM_INT);
            $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
            $comando->bindParam(":oper_id", $oporper, \PDO::PARAM_INT);
            $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
            $comando->bindParam(":tove_id", $tove_id, \PDO::PARAM_INT);
            $comando->bindParam(":tsca_id", $tsca_id, \PDO::PARAM_INT);
            $comando->bindParam(":ccan_id", $ccan_id, \PDO::PARAM_INT);
            $comando->bindParam(":eopo_id", $eopo_id, \PDO::PARAM_INT);
            $comando->bindParam(":oeac_id", $otro_estudio, \PDO::PARAM_INT);
            $comando->bindParam(":opo_estado_cierre", $opo_estado_cierre, \PDO::PARAM_STR);
            $comando->bindParam(":opo_fecha_ult_estado", $opo_fecha_ult_estado, \PDO::PARAM_STR);
            $comando->bindParam(":opo_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":usu_id", $usu_id, \PDO::PARAM_INT);
            $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
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
     * Function carga archivo csv a base de datos
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function CargarArchivo($fname, $emp_id, $tipoProceso) {
        $mod_perTemp = new PersonaGestionTmp();
        $mod_pergestion = new PersonaGestion();
        if ($tipoProceso == "LEADS") {
            $path = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "leads/" . $fname;
            //return $mod_pergestion->insertarDtosPersonaGestion($emp_id, $tipoProceso);
            $carga_archivo = $mod_perTemp->uploadFile($emp_id, $path);
            if ($carga_archivo['status']) {
                return $mod_pergestion->insertarDtosPersonaGestion($emp_id, $tipoProceso);
            } else {
                return $carga_archivo;
            }
        } else {
            //PROCESO PARA SUBIR EN LOTES LEADS COLOMBIA
            return $mod_pergestion->insertarDtosPersonaGestionLotes($emp_id, $tipoProceso);
        }
    }

    /**
     * Function Consultar datos para contactar del contacto registrado.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarDatoContact($pges_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;

        $sql = "SELECT 	pgco_primer_nombre,
                        pgco_segundo_nombre,
                        pgco_primer_apellido,
                        pgco_segundo_apellido,
                        pgco_correo,
                        pgco_telefono,
                        pgco_celular,
                        pai_id_contacto,
                        pgco_id
                FROM " . $con->dbname . ".persona_gestion_contacto pgc
                WHERE pgc.pges_id = :pges_id
                      and pgc.pgco_estado = :estado
                      and pgc.pgco_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta la data de la oportunidad seguns su id.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOportunidadById($opo_id) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT
                        concat(ifnull(per_pri_nombre,''), ' ', ifnull(per_pri_apellido,'')) as agente,
                        opo.uaca_id,
                        opo.emp_id as empresa,
                        uaca.uaca_nombre as unidad_academica,
                        opo.mod_id,
                        moda.mod_nombre as modalidad,
                        -- opo.tove_id,
                        -- top.tove_nombre as tipo_oportunidad,
                        opo.eopo_id,
                        eop.eopo_nombre as estado_oportunidad,
                        opo.oper_id as oportunidad_perdida,
                        opo.oeac_id as otro_estudio,
                        ifnull((select esa.eaca_nombre from " . $con2->dbname . ".estudio_academico esa
                        where esa.eaca_id = opo.eaca_id
                        and esa.eaca_estado = :estado
                        and esa.eaca_estado_logico = :estado),'') as esacademico,
                        opo.mest_id,
                        ifnull((select mest.mest_nombre from " . $con2->dbname . ".modulo_estudio mest
                        where mest.mest_id = opo.mest_id
                        and mest.mest_estado = :estado
                        and mest.mest_estado_logico = :estado),'') as moestudio,
                        opo.eaca_id,
                        opo.ccan_id,
                        opo.opo_id,
                        opo.tsca_id as subcarera_id,
                        ifnull((select tsc.tsca_nombre from " . $con->dbname . ".tipo_sub_carrera tsc
                        where tsc.tsca_id = opo.tsca_id
                        and tsc.tsca_estado = :estado
                        and tsc.tsca_estado_logico = :estado),'') as sub_carrera,
                        opo.mest_id as mest_id,
                        eop.eopo_nombre as estado_oportunidad,
                        (case when (opo.tsca_id > 0) then
                                (select tcar_id from " . $con->dbname . ".tipo_sub_carrera tsc where tsc.tsca_id = opo.tsca_id and tsc.tsca_estado = :estado and tsc.tsca_estado_logico = :estado)
                                else 0 end) as tcar_id,
                        per.per_id,
                        opo.pges_id,
                        opo.padm_id
                FROM " . $con->dbname . ".oportunidad opo
                         inner join " . $con->dbname . ".personal_admision pa on pa.padm_id = opo.padm_id
                         inner join " . $con1->dbname . ".persona per on per.per_id = pa.per_id
                         inner join " . $con2->dbname . ".unidad_academica uaca on uaca.uaca_id = opo.uaca_id
                         inner join " . $con2->dbname . ".modalidad moda on moda.mod_id = opo.mod_id
                         -- inner join " . $con->dbname . ".tipo_oportunidad_venta top on top.tove_id = opo.tove_id
                         inner join " . $con->dbname . ".estado_oportunidad eop on eop.eopo_id = opo.eopo_id
                WHERE 	opo.opo_id = :opo_id
                        and opo.opo_estado = :estado
                        and opo.opo_estado_logico = :estado
                        and pa.padm_estado = :estado
                        and pa.padm_estado_logico = :estado
                        and per.per_estado = :estado
                        and per.per_estado_logico = :estado
                        -- and top.tove_estado = :estado
                        -- and top.tove_estado_logico = :estado
                        and eop.eopo_estado = :estado
                        and eop.eopo_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":opo_id", $opo_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta la data de la actividad segun su identificacion <id>.
     * @author Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarActividadById($act_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "
                    select
                    act.*
                    from " . $con->dbname . ".bitacora_actividades as act
                    where act.bact_id=:act_id
                    and act.bact_estado=:estado
                    and act.bact_estado_logico=:estado;
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":act_id", $act_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**     * **
     * Function Existe
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public static function existeOportunidad_Unidad_Modalidad($pges_id, $uaca_id, $mod_id) {
        //Retorna las Max oportunidad sin considerar las GANADAS O PERDIDAS
        $con = \Yii::$app->db_crm;
        $sql = "SELECT ifnull(MAX(CAST(opo_id AS UNSIGNED)),0) Ids
                    FROM " . $con->dbname . ".oportunidad
                WHERE opo_estado = 1 AND opo_estado_logico = 1 AND pges_id =:pges_id AND
                    uaca_id =:uaca_id AND mod_id =:mod_id AND eopo_id NOT IN(5, 4)";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0;
        return $rawData; //Si Existe en la Tabla
    }

    /**
     * Function Consultar id de los agentes.
     * @author  Giovanni Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarAgentebyCod($uaca_id, $mod_id, $padm_tipo_asignacion) {
        $con = \Yii::$app->db_crm;
        $estado = 1;

        $sql = "SELECT 	pa.padm_id as agente_id

                FROM " . $con->dbname . ".personal_nivel_modalidad pnm
                INNER JOIN " . $con->dbname . ".personal_admision pa on pa.padm_id = pnm.padm_id
                WHERE
                      pnm.uaca_id = :uaca_id
                      AND pnm.mod_id = :mod_id
                      AND pa.padm_tipo_asignacion = :padm_tipo_asignacion
                      AND pnm.pnmo_estado = :estado
                      AND pnm.pnmo_estado_logico = :estado
                      AND pa.padm_estado = :estado
                      AND pa.padm_estado_logico = :estado
                ORDER BY RAND() LIMIT 1 ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":padm_tipo_asignacion", $padm_tipo_asignacion, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta id de agente autenticado segun per_id.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarNombreOportunidad($emp_id, $mest_id, $eaca_id, $uaca_id, $mod_id, $eopo_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        if (!empty((isset($mest_id)))) {
            $filtro .= "opo.mest_id = :mest_id AND ";
        }
        if (!empty((isset($eaca_id)))) {
            $filtro .= "opo.eaca_id = :eaca_id AND ";
        }
        $sql = "SELECT
                    eopo_nombre
                FROM
                   " . $con->dbname . ".oportunidad opo "
                . " INNER JOIN " . $con->dbname . ".estado_oportunidad eopo ON eopo.eopo_id = opo.eopo_id ";

        $sql .= "  WHERE

                   opo.emp_id = :emp_id AND ";
        $sql .= $filtro;
        $sql .= "  opo.uaca_id = :uaca_id AND
                   opo.mod_id = :mod_id AND
                   opo.eopo_id = :eopo_id AND
                   eopo.eopo_estado = :estado AND
                   eopo.eopo_estado_logico = :estado AND
                   opo.opo_estado = :estado AND
                   opo.opo_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        if (!empty((isset($mest_id)))) {
            $comando->bindParam(":mest_id", $mest_id, \PDO::PARAM_INT);
        }
        if (!empty((isset($eaca_id)))) {
            $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        }
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":eopo_id", $eopo_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consulta nombre de tipo carrera segun el id de tipo_sub_carrera.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarNombreCarrera($tsca_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                    tca.tcar_nombre
                FROM
                   " . $con->dbname . ".tipo_sub_carrera tsc "
                . " INNER JOIN " . $con->dbname . ".tipo_carrera tca ON tca.tcar_id = tsc.tcar_id ";

        $sql .= "  WHERE
                   tsc.tsca_id = :tsca_id AND
                   tca.tcar_estado = :estado AND
                   tca.tcar_estado_logico = :estado AND
                   tsc.tsca_estado = :estado AND
                   tsc.tsca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":tsca_id", $tsca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function consultarOportUnidadAcademica() {
        $con = \Yii::$app->db_crm;
        $sql = "SELECT A.eopo_id,B.eopo_nombre,A.uaca_id,C.uaca_nombre,COUNT(A.uaca_id) CantUnidad
                        FROM " . $con->dbname . ".oportunidad A
                                INNER JOIN " . $con->dbname . ".estado_oportunidad B ON B.eopo_id=A.eopo_id
                                INNER JOIN db_academico.unidad_academica C ON A.uaca_id=C.uaca_id
                WHERE A.opo_estado_logico=1 GROUP BY A.uaca_id,A.eopo_id ORDER BY A.eopo_id; ";
        $comando = $con->createCommand($sql);

        return $comando->queryAll();
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarCodigoArea
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id
     * @return
     */
    public function consultarOportPerdida() {
        $con = \Yii::$app->db_crm;
        $sql = "SELECT A.oper_id,B.oper_nombre,A.uaca_id,C.uaca_nombre,COUNT(A.uaca_id) CantUnidad
                        FROM " . $con->dbname . ".oportunidad A
                                INNER JOIN " . $con->dbname . ".oportunidad_perdida B ON B.oper_id=A.oper_id
                                INNER JOIN db_academico.unidad_academica C ON A.uaca_id=C.uaca_id
                WHERE A.opo_estado_logico=1 GROUP BY A.uaca_id,A.oper_id ORDER BY A.oper_id; ";
        $comando = $con->createCommand($sql);
        return $comando->queryAll();
    }

    /** Function consulta las observaciones de la actividad
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarObseractividad() {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "SELECT
                   oact.oact_id as id,
                   oact.oact_nombre as name
                FROM
                   " . $con->dbname . ".observacion_actividades oact
                WHERE
                   oact.oact_estado = :estado AND
                   oact.oact_estado_logico = :estado
                ORDER BY name";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultar las oportunidades de venta.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOportunidadexcel($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_crm;
        $con1 = \Yii::$app->db;
        $con2 = \Yii::$app->db_academico;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['interesado'] != "") {
                $str_search = " a.contacto like :interesado AND ";
            }
            /*if ($arrFiltro['agente'] != "") {
                $str_search .= " (padm_codigo like :agente)  AND";
            }*/
            if ($arrFiltro['empresa'] > "0") {
                $str_search .= " a.emp_id = :empresa  AND";
            }
            if ($arrFiltro['estado'] != "" && $arrFiltro['estado'] > 0) {
                $str_search .= "  a.eopo_id = :estado_ate AND ";
            }
            if ($arrFiltro['fec_registro_ini'] != "" && $arrFiltro['fec_registro_fin'] != "") {
                $str_search .= "  a.fecha_registro >= :fec_registro_ini AND ";
                $str_search .= "  a.fecha_registro <= :fec_registro_fin AND ";
            }
            if ($arrFiltro['fec_proxima_ini'] != "" && $arrFiltro['fec_proxima_fin'] != "") {
                $str_search .= "  a.fecha_proxima >= :fec_proxima_ini AND ";
                $str_search .= "  a.fecha_proxima <= :fec_proxima_fin AND ";
            }
        }
        $sql = "SELECT opo_codigo,
                    contacto,
                    des_empresa,
                    des_unidad,
                    des_estudio,
                    des_modalidad,
                    des_estado,
                    fecha_registro,
                    fecha_proxima,
                    agente
                FROM (
                    SELECT
                            lpad(ifnull(o.opo_codigo,0),7,'0') as opo_codigo,
                            (case when pg.tper_id = 1 then
                                    concat(ifnull(pg.pges_pri_nombre,''), ' ', ifnull(pg.pges_seg_nombre,''), ' ', ifnull(pg.pges_pri_apellido,''), ' ', ifnull(pg.pges_seg_apellido,''))
                                    else pg.pges_razon_social end) as contacto,
                            e.emp_razon_social as des_empresa,
                            ua.uaca_descripcion as des_unidad,
                            (case when (o.uaca_id < 3) then
                                            (select ea.eaca_descripcion from " . $con2->dbname . ".estudio_academico ea where ea.eaca_id = o.eaca_id and ea.eaca_estado = '1' and ea.eaca_estado_logico = '1')
                                            else (select me.mest_descripcion from " . $con2->dbname . ".modulo_estudio me where me.mest_id = o.mest_id and me.mest_estado = '1' and me.mest_estado_logico = '1')
                                        end) as des_estudio,
                             m.mod_descripcion as des_modalidad,
                            eo.eopo_descripcion as des_estado,
                            -- o.opo_fecha_registro as fecha
                            (select max(bact_fecha_registro) from db_crm.bitacora_actividades b
                                    where b.opo_id = o.opo_id and b.bact_estado = 1 and bact_estado_logico = 1) as fecha_registro,
                            case when o.eopo_id = '3' then
                                    '' else
                                    (select max(bact_fecha_proxima_atencion) from db_crm.bitacora_actividades b
                                     where b.opo_id = o.opo_id and b.bact_estado = 1 and bact_estado_logico = 1) end as fecha_proxima,

                            concat(p.per_pri_nombre, ' ', ifnull(p.per_seg_nombre,' '), ' ', p.per_pri_apellido, ' ', ifnull(p.per_seg_apellido,' ')) as agente,
                            o.emp_id,
                            o.eopo_id,
                            o.opo_id
                    FROM " . $con->dbname . ".oportunidad o inner join " . $con->dbname . ".persona_gestion pg on pg.pges_id = o.pges_id
                         inner join " . $con1->dbname . ".empresa e on e.emp_id = o.emp_id
                         inner join " . $con2->dbname . ".unidad_academica ua on ua.uaca_id = o.uaca_id
                         inner join " . $con->dbname . ".estado_oportunidad eo on eo.eopo_id = o.eopo_id
                         inner join " . $con2->dbname . ".modalidad m on m.mod_id = o.mod_id
                         inner join " . $con->dbname . ".personal_admision pa on o.padm_id = pa.padm_id
                         inner join " . $con1->dbname . ".persona p on pa.per_id = p.per_id
                     WHERE o.opo_estado = :estado
                           and o.opo_estado_logico = :estado
                           and pg.pges_estado = :estado
                           and pg.pges_estado_logico = :estado
                           and e.emp_estado = :estado
                           and e.emp_estado_logico = :estado
                           and ua.uaca_estado = :estado
                           and ua.uaca_estado_logico = :estado
                           and eo.eopo_estado = :estado
                           and eo.eopo_estado_logico = :estado
                           and m.mod_estado = :estado
                           and m.mod_estado_logico = :estado
                           and pa.padm_estado = :estado
                           and pa.padm_estado_logico = :estado
                           and p.per_estado = :estado
                           and p.per_estado_logico = :estado ) a ";

        if (!empty($str_search)) {
            $sql .= "WHERE $str_search a.opo_codigo = a.opo_codigo
                                 ORDER BY a.opo_id desc ";
        } else {
            $sql .= "ORDER BY a.opo_codigo desc";
        }

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["interesado"] . "%";
           // $agente = "%" . $arrFiltro["agente"] . "%";
            $estado_ate = $arrFiltro["estado"];
            $empresa = $arrFiltro["empresa"];

            if ($arrFiltro['interesado'] != "") {
                $comando->bindParam(":interesado", $search_cond, \PDO::PARAM_STR);
            }
            /*if ($arrFiltro['agente'] != "") {
                $comando->bindParam(":agente", $agente, \PDO::PARAM_STR);
            }*/
            if ($arrFiltro['empresa'] > "0") {
                $comando->bindParam(":empresa", $empresa, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estado'] != "" && $arrFiltro['estado'] > 0) {
                $comando->bindParam(":estado_ate", $estado_ate, \PDO::PARAM_INT);
            }
            $fec_registro_ini = $arrFiltro["fec_registro_ini"] . " 00:00:00";
            $fec_registro_fin = $arrFiltro["fec_registro_fin"] . " 23:59:59";

            if ($arrFiltro['fec_registro_ini'] != "" && $arrFiltro['fec_registro_fin'] != "") {
                $comando->bindParam(":fec_registro_ini", $fec_registro_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_registro_fin", $fec_registro_fin, \PDO::PARAM_STR);
            }
            $fec_proxima_ini = $arrFiltro["fec_proxima_ini"] . " 00:00:00";
            $fec_proxima_fin = $arrFiltro["fec_proxima_fin"] . " 23:59:59";
            if ($arrFiltro['fec_proxima_ini'] != "" && $arrFiltro['fec_proxima_fin'] != "") {
                $comando->bindParam(":fec_proxima_ini", $fec_proxima_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_proxima_fin", $fec_proxima_fin, \PDO::PARAM_STR);
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
     * Function obtener carreras.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarCarreras() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
                SELECT
                        eac.eaca_id as id,
                        eac.eaca_nombre as name
                    FROM " . $con->dbname . ".estudio_academico as eac
                    WHERE
                        eac.eaca_estado_logico=:estado AND
                        eac.eaca_estado=:estado
                        ORDER BY 2 asc";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function carga archivo csv o xls, xlsx a base de datos de las gestiones.
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function CargarArchivoGestion($emp_id, $fname, $usu_id, $padm_id) {
        $mod_actividadTemp = new BitacoraActividadesTmp();
        $mod_actividad = new Oportunidad();
        $path = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "gestion/" . $fname;
        $carga_archivo = $mod_actividadTemp->uploadFile($emp_id, $usu_id, $padm_id, $path);
        if ($carga_archivo['status']) {
            $data = $mod_actividadTemp->consultarBitacoraTemp($usu_id);
            $cont = 0;
            for ($i = 0; $i < sizeof($data); $i++) {
                $resultado = $mod_actividad->insertarActividad($data[$i]["opo_id"], $data[$i]["usu_id"], $data[$i]["padm_id"], $data[$i]["eopo_id"], $data[$i]["bact_fecha_registro"], $data[$i]["oact_id"],  $data[$i]["bact_descripcion"], $data[$i]["bact_fecha_proxima_atencion"]);
                //Modificar estado de la oportunidad.
                $respOport = $mod_actividad->modificarOportunixId(null, $data[$i]["opo_id"], null, null, null, null, null, null, null, null, null, $data[$i]["eopo_id"], $usu_id, $data[$i]["oper_id"]);
                $cont++;
            }
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
     * Function carga archivo csv a base de datos
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function CargarArchivoOtroscanales($fname, $emp_id, $tipoProceso) {
        $mod_perTemp = new PersonaGestionTmp();
        $mod_pergestion = new PersonaGestion();

        $path = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "gestion/" . $fname;
        $carga_archivo = $mod_perTemp->uploadFile($emp_id, $path);
        if ($carga_archivo['status']) {
            return $mod_pergestion->insertarDtosPersonaGestion($emp_id, $tipoProceso);
        } else {
            return $carga_archivo;
        }
    }

    /**
     * Function consulta id de oportunidad por carrera, unidad, modalidad y empresa
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarOportunidadxUnidModCarrera($emp_id, $uaca_id, $mod_id, $eaca_id, $pges_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;

        $sql = "SELECT
                    opo_id
                FROM
                   " . $con->dbname . ".oportunidad opo
                WHERE  opo.emp_id = :emp_id AND
                   opo.eaca_id = :eaca_id AND
                   opo.uaca_id = :uaca_id AND
                   opo.mod_id = :mod_id AND
                   opo.eopo_id in (1,3) AND
                   opo.pges_id = :pges_id AND
                   opo.opo_estado = :estado AND
                   opo.opo_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":pges_id", $pges_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}

