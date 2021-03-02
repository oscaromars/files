<?php

namespace app\modules\admision\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "inscrito_maestria".
 *
 * @property int $imae_id
 * @property int $cemp_id
 * @property int $gint_id
 * @property int $pai_id_
 * @property int $pro_id
 * @property int $can_id
 * @property string $imae_tipo_documento
 * @property string $imae_documento
 * @property string $imae_primer_nombre
 * @property string $imae_segundo_nombre
 * @property string $imae_primer_apellido
 * @property string $imae_segundo_apellido
 * @property string $imae_revisar_urgente
 * @property string $imae_cumple_requisito
 * @property string $imae_agente
 * @property string $imae_fecha_inscripcion
 * @property string $imae_fecha_pago
 * @property double $imae_pago_inscripcion
 * @property double $imae_valor_maestria
 * @property int $fpag_id
 * @property string $imae_estado_pago
 * @property string $imae_convenios
 * @property int $imae_usuario
 * @property int $imae_usuario_modif
 * @property string $imae_estado
 * @property string $imae_fecha_creacion
 * @property string $imae_fecha_modificacion
 * @property string $imae_estado_logico
 *
 * @property GrupoIntroductorio $gint
 */
class InscritoMaestria extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'inscrito_maestria';
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
            [['cemp_id', 'gint_id', 'pai_id_', 'pro_id', 'can_id', 'fpag_id', 'imae_usuario', 'imae_usuario_modif'], 'integer'],
            [['gint_id', 'imae_primer_nombre', 'imae_primer_apellido', 'imae_usuario', 'imae_estado', 'imae_estado_logico'], 'required'],
            [['imae_pago_inscripcion', 'imae_valor_maestria'], 'number'],
            [['imae_fecha_creacion', 'imae_fecha_modificacion'], 'safe'],
            [['imae_tipo_documento', 'imae_cumple_requisito', 'imae_estado_pago'], 'string', 'max' => 2],
            [['imae_documento'], 'string', 'max' => 50],
            [['imae_primer_nombre', 'imae_segundo_nombre', 'imae_primer_apellido', 'imae_segundo_apellido', 'imae_revisar_urgente', 'imae_agente', 'imae_convenios'], 'string', 'max' => 100],
            [['imae_fecha_inscripcion', 'imae_fecha_pago'], 'string', 'max' => 20],
            [['imae_estado', 'imae_estado_logico'], 'string', 'max' => 1],
            [['gint_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoIntroductorio::className(), 'targetAttribute' => ['gint_id' => 'gint_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'imae_id' => 'Imae ID',
            'cemp_id' => 'Cemp ID',
            'gint_id' => 'Gint ID',
            'pai_id_' => 'Pai ID',
            'pro_id' => 'Pro ID',
            'can_id' => 'Can ID',
            'imae_tipo_documento' => 'Imae Tipo Documento',
            'imae_documento' => 'Imae Documento',
            'imae_primer_nombre' => 'Imae Primer Nombre',
            'imae_segundo_nombre' => 'Imae Segundo Nombre',
            'imae_primer_apellido' => 'Imae Primer Apellido',
            'imae_segundo_apellido' => 'Imae Segundo Apellido',
            'imae_revisar_urgente' => 'Imae Revisar Urgente',
            'imae_cumple_requisito' => 'Imae Cumple Requisito',
            'imae_agente' => 'Imae Agente',
            'imae_fecha_inscripcion' => 'Imae Fecha Inscripcion',
            'imae_fecha_pago' => 'Imae Fecha Pago',
            'imae_pago_inscripcion' => 'Imae Pago Inscripcion',
            'imae_valor_maestria' => 'Imae Valor Maestria',
            'fpag_id' => 'Fpag ID',
            'imae_estado_pago' => 'Imae Estado Pago',
            'imae_convenios' => 'Imae Convenios',
            'imae_usuario' => 'Imae Usuario',
            'imae_usuario_modif' => 'Imae Usuario Modif',
            'imae_estado' => 'Imae Estado',
            'imae_fecha_creacion' => 'Imae Fecha Creacion',
            'imae_fecha_modificacion' => 'Imae Fecha Modificacion',
            'imae_estado_logico' => 'Imae Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGint() {
        return $this->hasOne(GrupoIntroductorio::className(), ['gint_id' => 'gint_id']);
    }

    /**
     * Function insertarInscritoMaestria grabar la inserción inscritos.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function insertarInscritoMaestria($cemp_id, $gint_id, $pai_id, $pro_id, $can_id, $uaca_id, $mod_id, $eaca_id, $imae_tipo_documento, 
                                $imae_documento, $imae_primer_nombre, $imae_segundo_nombre, $imae_primer_apellido, $imae_segundo_apellido, 
                                $imae_revisar_urgente, $imae_cumple_requisito, $imae_agente, $imae_fecha_inscripcion, $imae_fecha_pago, 
                                $imae_pago_inscripcion, $imae_valor_maestria, $fpag_id, $imae_estado_pago, $imae_convenios, 
                                $imae_matricula, $imae_titulo, $ins_id, $imae_correo, $imae_celular, $imae_convencional, $imae_ocupacion,
                                $imae_usuario, $imae_fecha_creacion) {
        $con = \Yii::$app->db_crm;

        $param_sql = "imae_estado";
        $bdet_sql = "1";

        $param_sql .= ", imae_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($cemp_id)) {
            $param_sql .= ", cemp_id";
            $bdet_sql .= ", :cemp_id";
        }
        if (isset($gint_id)) {
            $param_sql .= ", gint_id";
            $bdet_sql .= ", :gint_id";
        }
        if (isset($pai_id)) {
            $param_sql .= ", pai_id";
            $bdet_sql .= ", :pai_id";
        }
        if (isset($pro_id)) {
            $param_sql .= ", pro_id";
            $bdet_sql .= ", :pro_id";
        }
        if (isset($can_id)) {
            $param_sql .= ", can_id";
            $bdet_sql .= ", :can_id";
        }
        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bdet_sql .= ", :uaca_id";
        }
        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bdet_sql .= ", :mod_id";
        }
        if (isset($eaca_id)) {
            $param_sql .= ", eaca_id";
            $bdet_sql .= ", :eaca_id";
        }
        if (!empty((isset($imae_tipo_documento)))) {
            $param_sql .= ", imae_tipo_documento";
            $bdet_sql .= ", :imae_tipo_documento";
        }
        if (!empty((isset($imae_documento)))) {
            $param_sql .= ", imae_documento";
            $bdet_sql .= ", :imae_documento";
        }
        if (!empty((isset($imae_primer_nombre)))) {
            $param_sql .= ", imae_primer_nombre";
            $bdet_sql .= ", :imae_primer_nombre";
        }
        if (!empty((isset($imae_segundo_nombre)))) {
            $param_sql .= ", imae_segundo_nombre";
            $bdet_sql .= ", :imae_segundo_nombre";
        }
        if (!empty((isset($imae_primer_apellido)))) {
            $param_sql .= ", imae_primer_apellido";
            $bdet_sql .= ", :imae_primer_apellido";
        }
        if (!empty((isset($imae_segundo_apellido)))) {
            $param_sql .= ", imae_segundo_apellido";
            $bdet_sql .= ", :imae_segundo_apellido";
        }
        if (!empty((isset($imae_revisar_urgente)))) {
            $param_sql .= ", imae_revisar_urgente";
            $bdet_sql .= ", :imae_revisar_urgente";
        }
        if (!empty((isset($imae_cumple_requisito)))) {
            $param_sql .= ", imae_cumple_requisito";
            $bdet_sql .= ", :imae_cumple_requisito";
        }
        if (!empty((isset($imae_agente)))) {
            $param_sql .= ", imae_agente";
            $bdet_sql .= ", :imae_agente";
        }
        if (!empty((isset($imae_fecha_inscripcion)))) {
            $param_sql .= ", imae_fecha_inscripcion";
            $bdet_sql .= ", :imae_fecha_inscripcion";
        }
        if (!empty((isset($imae_fecha_pago)))) {
            $param_sql .= ", imae_fecha_pago";
            $bdet_sql .= ", :imae_fecha_pago";
        }
        if (!empty((isset($imae_pago_inscripcion)))) {
            $param_sql .= ", imae_pago_inscripcion";
            $bdet_sql .= ", :imae_pago_inscripcion";
        }
        if (!empty((isset($imae_valor_maestria)))) {
            $param_sql .= ", imae_valor_maestria";
            $bdet_sql .= ", :imae_valor_maestria";
        }
        if (!empty((isset($fpag_id)))) {
            $param_sql .= ", fpag_id";
            $bdet_sql .= ", :fpag_id";
        }
        if (!empty((isset($imae_estado_pago)))) {
            $param_sql .= ", imae_estado_pago";
            $bdet_sql .= ", :imae_estado_pago";
        }
        if (!empty((isset($imae_convenios)))) {
            $param_sql .= ", imae_convenios";
            $bdet_sql .= ", :imae_convenios";
        }
        if (!empty((isset($imae_matricula)))) {
            $param_sql .= ", imae_matricula";
            $bdet_sql .= ", :imae_matricula";
        }
        if (!empty((isset($imae_titulo)))) {
            $param_sql .= ", imae_titulo";
            $bdet_sql .= ", :imae_titulo";
        }
        if (!empty((isset($ins_id)))) {
            $param_sql .= ", ins_id";
            $bdet_sql .= ", :ins_id";
        }
        if (!empty((isset($imae_correo)))) {
            $param_sql .= ", imae_correo";
            $bdet_sql .= ", :imae_correo";
        }
        if (!empty((isset($imae_celular)))) {
            $param_sql .= ", imae_celular";
            $bdet_sql .= ", :imae_celular";
        }
        if (!empty((isset($imae_convencional)))) {
            $param_sql .= ", imae_convencional";
            $bdet_sql .= ", :imae_convencional";
        }
        if (!empty((isset($imae_ocupacion)))) {
            $param_sql .= ", imae_ocupacion";
            $bdet_sql .= ", :imae_ocupacion";
        }
        if (isset($imae_usuario)) {
            $param_sql .= ", imae_usuario";
            $bdet_sql .= ", :imae_usuario";
        }
        if (isset($imae_fecha_creacion)) {
            $param_sql .= ", imae_fecha_creacion";
            $bdet_sql .= ", :imae_fecha_creacion";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".inscrito_maestria ($param_sql) VALUES($bdet_sql)";

            $comando = $con->createCommand($sql);
            if (isset($cemp_id)) {
                $comando->bindParam(':cemp_id', $cemp_id, \PDO::PARAM_INT);
                \app\models\Utilities::putMessageLogFile('$cemp_id:' . $cemp_id);
            }
            if (isset($gint_id)) {
                $comando->bindParam(':gint_id', $gint_id, \PDO::PARAM_INT);
            }
            if (isset($pai_id)) {
                $comando->bindParam(':pai_id', $pai_id, \PDO::PARAM_INT);
            }
            if (isset($pro_id)) {
                $comando->bindParam(':pro_id', $pro_id, \PDO::PARAM_INT);
            }
            if (isset($can_id)) {
                $comando->bindParam(':can_id', $can_id, \PDO::PARAM_INT);
            }
            if (isset($uaca_id)) {
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);
            }
            if (isset($mod_id)) {
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);
            }
            if (isset($eaca_id)) {
                $comando->bindParam(':eaca_id', $eaca_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($imae_tipo_documento)))) {
                $comando->bindParam(':imae_tipo_documento', $imae_tipo_documento, \PDO::PARAM_STR);
            }
            if (isset($imae_documento)) {
                $comando->bindParam(':imae_documento', $imae_documento, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_primer_nombre)))) {
                $comando->bindParam(':imae_primer_nombre', ucwords(strtolower($imae_primer_nombre)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_segundo_nombre)))) {
                $comando->bindParam(':imae_segundo_nombre', ucwords(strtolower($imae_segundo_nombre)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_primer_apellido)))) {
                $comando->bindParam(':imae_primer_apellido', ucwords(strtolower($imae_primer_apellido)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_segundo_apellido)))) {
                $comando->bindParam(':imae_segundo_apellido', ucwords(strtolower($imae_segundo_apellido)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_revisar_urgente)))) {
                $comando->bindParam(':imae_revisar_urgente', ucwords(strtolower($imae_revisar_urgente)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_cumple_requisito)))) {
                $comando->bindParam(':imae_cumple_requisito', $imae_cumple_requisito, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_agente)))) {
                $comando->bindParam(':imae_agente', $imae_agente, \PDO::PARAM_INT);
            }
            if (!empty((isset($imae_fecha_inscripcion)))) {
                $comando->bindParam(':imae_fecha_inscripcion', $imae_fecha_inscripcion, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_fecha_pago)))) {
                $comando->bindParam(':imae_fecha_pago', $imae_fecha_pago, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_pago_inscripcion)))) {
                $comando->bindParam(':imae_pago_inscripcion', $imae_pago_inscripcion, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_valor_maestria)))) {
                $comando->bindParam(':imae_valor_maestria', $imae_valor_maestria, \PDO::PARAM_STR);
            }
            if (!empty((isset($fpag_id)))) {
                $comando->bindParam(':fpag_id', $fpag_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($imae_estado_pago)))) {
                $comando->bindParam(':imae_estado_pago', $imae_estado_pago, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_convenios)))) {
                $comando->bindParam(':imae_convenios', ucwords(strtolower($imae_convenios)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_matricula)))) {
                $comando->bindParam(':imae_matricula', ucwords(strtolower($imae_matricula)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_titulo)))) {
                $comando->bindParam(':imae_titulo', ucwords(strtolower($imae_titulo)), \PDO::PARAM_STR);
            }
            if (!empty((isset($ins_id)))) {
                $comando->bindParam(':ins_id', $ins_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($imae_correo)))) {
                $comando->bindParam(':imae_correo', ucwords(strtolower($imae_correo)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_celular)))) {
                $comando->bindParam(':imae_celular', $imae_celular, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_convencional)))) {
                $comando->bindParam(':imae_convencional', $imae_convencional, \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_ocupacion)))) {
                $comando->bindParam(':imae_ocupacion', ucwords(strtolower($imae_ocupacion)), \PDO::PARAM_STR);
            }
            if (!empty((isset($imae_usuario)))) {
                $comando->bindParam(':imae_usuario', $imae_usuario, \PDO::PARAM_INT);
            }
            if (!empty((isset($imae_fecha_creacion)))) {
                $comando->bindParam(':imae_fecha_creacion', $imae_fecha_creacion, \PDO::PARAM_STR);
            }
            $result = $comando->execute();
            return $con->getLastInsertID($con->dbname . '.inscrito_maestria');
        } catch (Exception $ex) {
            return FALSE;
        }
    }

    function getAllInscritosGrid($search = NULL, $date_ini = NULL, $date_end = NULL, $agente = NULL, $tipo_convenio = NULL, $grupo = NULL, $dataProvider = false) {
        //$iduser    = Yii::$app->session->get('PB_iduser', FALSE);
        //$idempresa = Yii::$app->session->get('PB_idempresa', FALSE);
        $search_cond = "%" . $search . "%";
        $str_search = "";
        if (isset($search) && $search != "") {
            $str_search .= "(im.imae_primer_nombre like :search OR ";
            $str_search .= "im.imae_segundo_nombre like :search OR ";
            $str_search .= "im.imae_primer_apellido like :search OR ";
            $str_search .= "im.imae_segundo_apellido like :search OR ";
            $str_search .= "pr.pro_nombre like :search OR ";
            $str_search .= "ca.can_nombre like :search OR ";
            $str_search .= "im.imae_documento like :search OR ";
            $str_search .= "ai.aima_nombre like :search) AND ";
        }
        if (isset($date_ini) && $date_ini != "") {
            $str_search .= "im.imae_fecha_inscripcion >= :dateini AND ";
        }
        if (isset($date_end) && $date_end != "") {
            $str_search .= "im.imae_fecha_inscripcion <= :dateend AND ";
        }
        if (isset($grupo) && $grupo > 0){
            $str_search .= "im.gint_id = :grupo AND ";
        }
        if (isset($agente) && $agente > 0){
            $str_search .= "im.imae_agente = :agente AND ";
        }
        if (isset($tipo_convenio) && $tipo_convenio >= 0){
            $str_search .= "im.cemp_id = :convenio AND ";
        }
        $con = \Yii::$app->db_crm;
        $trans = $con->getTransaction();
        if ($trans !== null) {
            $trans = null;
        } else {
            $trans = $con->beginTransaction();
        }
        $sql = "SELECT 
                    imae_id AS id,
                    ifnull (ce.cemp_nombre, ' ') AS convenio,
                    gi.gint_nombre AS grupoIntroductorio,
                    pr.pro_nombre AS provincia,
                    ca.can_nombre AS canton,
                    im.imae_documento AS dni,
                    im.imae_primer_nombre AS pri_nombre,
                    im.imae_segundo_nombre AS seg_nombre,
                    im.imae_primer_apellido AS pri_apellido,
                    im.imae_segundo_apellido AS seg_apellido,
                    im.imae_correo AS correo,
                    im.imae_celular AS celular,
                    im.imae_revisar_urgente AS revision,
                    im.imae_cumple_requisito AS requisito,
                    ai.aima_nombre AS agente,
                    im.imae_fecha_inscripcion AS fecha_inscripcion,
                    im.imae_fecha_pago AS fecha_pago,
                    im.imae_pago_inscripcion AS pago_inscripcion,
                    im.imae_valor_maestria AS valor_maestria,
                    fp.fpag_nombre AS forma_pago,
                    im.imae_estado_pago AS estado_pago,
                    im.imae_convenios AS acuerdos
                FROM 
                    " . $con->dbname . ".inscrito_maestria AS im 
                    INNER JOIN " . Yii::$app->db->dbname . ".pais AS pa ON pa.pai_id = im.pai_id
                    INNER JOIN " . Yii::$app->db->dbname . ".provincia AS pr ON pr.pro_id = im.pro_id
                    INNER JOIN " . Yii::$app->db->dbname . ".canton AS ca ON ca.can_id = im.can_id
                    INNER JOIN " . Yii::$app->db_facturacion->dbname . ".forma_pago AS fp ON fp.fpag_id = im.fpag_id
                    INNER JOIN " . $con->dbname . ".grupo_introductorio AS gi ON gi.gint_id = im.gint_id
                    INNER JOIN " . Yii::$app->db_general->dbname . ".agente_inscrito_maestria AS ai ON ai.aima_id = im.imae_agente
                    LEFT JOIN " . Yii::$app->db_captacion->dbname . ".convenio_empresa AS ce ON ce.cemp_id = im.cemp_id
                WHERE 
                    $str_search
                    im.imae_estado=1 AND
                    im.imae_estado_logico=1 
                ORDER BY im.imae_fecha_inscripcion DESC, im.imae_primer_apellido DESC;";
        $comando = Yii::$app->db->createCommand($sql);
        if (isset($search) && $search != "") {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        if (isset($date_ini) && $date_ini != "") {
            $comando->bindParam(":dateini", $date_ini, \PDO::PARAM_STR);
        }
        if (isset($date_end) && $date_end != "") {
            $comando->bindParam(":dateend", $date_end, \PDO::PARAM_STR);
        }
        if (isset($grupo) && $grupo > 0){
            $comando->bindParam(":grupo", $grupo, \PDO::PARAM_INT);
        }
        if (isset($agente) && $agente > 0){
            $comando->bindParam(":agente", $agente, \PDO::PARAM_INT);
        }
        if (isset($tipo_convenio) && $tipo_convenio >= 0){
            $comando->bindParam(":convenio", $tipo_convenio, \PDO::PARAM_INT);
        }
        $res = $comando->queryAll();
        if ($dataProvider) {
            $dataProvider = new ArrayDataProvider([
                'key' => 'imae_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['pais', 'provincia', 'canton', 'fecha_inscripcion', 'agente', 'estado_pago', 'forma_pago'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    /**
     * Function eliminar logica registro de inscrito maestriar, cambia el estado a 0
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property 
     * @return  
     */
    public function deleteRegistroInscrito($imae_id, $imae_usuario_modif) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $estado_cambio = 0;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("  
                      UPDATE " . $con->dbname . ".inscrito_maestria                      
                      SET imae_estado = :estado_cambio, 
                          imae_estado_logico = :estado_cambio,
                          imae_usuario_modif = :imae_usuario_modif,
                          imae_fecha_modificacion = :fecha_modificacion
                      WHERE imae_id = :imae_id ");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":estado_cambio", $estado_cambio, \PDO::PARAM_STR);
            $comando->bindParam(":imae_id", $imae_id, \PDO::PARAM_INT);
            $comando->bindParam(":imae_usuario_modif", $imae_usuario_modif, \PDO::PARAM_INT);
            $comando->bindParam(":fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
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
     * Function Consultar datos inscrito maestria.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarInscritoMaestria($imae_id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;

        $sql = "SELECT 
                        imae_tipo_documento as tipo_documento,
                        imae_documento as documento,
                        imae_primer_nombre as nombre,
                        imae_segundo_nombre as sgo_nombre,
                        imae_primer_apellido as apellido,
                        imae_segundo_apellido as sgoapellido,
                        pai_id as pais,
                        pro_id as provincia,
                        can_id as canton,
                        imae_matricula as matricula,
                        imae_titulo as titulo,
                        ins_id as institucion,
                        imae_correo as correo,
                        imae_celular as celular,
                        imae_convencional as telefono,
                        imae_ocupacion as ocupacion,
                        uaca_id as unidad,
                        mod_id as modalidad,
                        eaca_id as carrera,
                        cemp_id as convenio_empresa,
                        gint_id as grupo_introductorio,
                        imae_cumple_requisito as cumple_requisito,
                        imae_agente as agente,
                        imae_fecha_inscripcion as fecha_inscripcion,
                        imae_revisar_urgente as revision,
                        imae_pago_inscripcion as pago_inscripcion,
                        imae_valor_maestria as pago_total,
                        imae_fecha_pago as fecha_pago,
                        fpag_id as metodo_pago,
                        imae_estado_pago as estado_pago,
                        imae_convenios as convenio
                FROM db_crm.inscrito_maestria
                WHERE   imae_id = :imae_id AND
                        imae_estado = :estado AND
                        imae_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":imae_id", $imae_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    /**
     * Function modifica un inscritos maestria
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function actualizarInscritoMaestria($con, $id, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $params_sql = "";
        for ($i = 0; $i < (count($parameters) - 1); $i++) {
            if (isset($parameters[$i])) {
                $params_sql .= $keys[$i] . " = '" . $parameters[$i] . "',";
            }
        }
        $params_sql .= $keys[count($parameters) - 1] . " = '" . $parameters[count($parameters) - 1] . "'";
        try {
            $sql = "UPDATE " . $con->dbname . '.' . $name_table .
                    " SET $params_sql" .
                    " WHERE imae_id = $id";
            \app\models\Utilities::putMessageLogFile('update unsc maes: ' . $sql);
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            if ($trans !== null) {
                return true;
            } else {
                $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }
    

}
