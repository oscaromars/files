<?php

namespace app\modules\academico\models;

use yii\base\Exception;
use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

/**
 * This is the model class for table "certificados_generadas".
 *
 * @property int $cgen_id
 * @property int $egen_id
 * @property string $cgen_codigo
 * @property string $cgen_observacion
 * @property string $cgen_fecha_codigo_generado
 * @property string $cgen_fecha_certificado_subido
 * @property string $cgen_fecha_caducidad
 * @property string $cgen_ruta_archivo_pdf
 * @property int $cgen_usuario_ingreso
 * @property int $cgen_usuario_modifica
 * @property string $cgen_estado
 * @property string $cgen_fecha_creacion
 * @property string $cgen_fecha_modificacion
 * @property string $cgen_estado_logico
 *
 * @property EspeciesGeneradas $egen
 */
class CertificadosGeneradas extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'certificados_generadas';
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
            [['egen_id', 'cgen_codigo', 'cgen_estado_logico'], 'required'],
            [['egen_id', 'cgen_usuario_ingreso', 'cgen_usuario_modifica'], 'integer'],
            [['cgen_observacion'], 'string'],
            [['cgen_fecha_codigo_generado', 'cgen_fecha_certificado_subido', 'cgen_fecha_caducidad', 'cgen_fecha_creacion', 'cgen_fecha_modificacion'], 'safe'],
            [['cgen_codigo'], 'string', 'max' => 100],
            [['cgen_ruta_archivo_pdf'], 'string', 'max' => 500],
            [['cgen_estado', 'cgen_estado_logico'], 'string', 'max' => 1],
            [['cgen_codigo'], 'unique'],
            [['egen_id'], 'exist', 'skipOnError' => true, 'targetClass' => EspeciesGeneradas::className(), 'targetAttribute' => ['egen_id' => 'egen_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'cgen_id' => 'Cgen ID',
            'egen_id' => 'Egen ID',
            'cgen_codigo' => 'Cgen Codigo',
            'cgen_observacion' => 'Cgen Observacion',
            'cgen_fecha_codigo_generado' => 'Cgen Fecha Codigo Generado',
            'cgen_fecha_certificado_subido' => 'Cgen Fecha Certificado Subido',
            'cgen_fecha_caducidad' => 'Cgen Fecha Caducidad',
            'cgen_ruta_archivo_pdf' => 'Cgen Ruta Archivo Pdf',
            'cgen_usuario_ingreso' => 'Cgen Usuario Ingreso',
            'cgen_usuario_modifica' => 'Cgen Usuario Modifica',
            'cgen_estado' => 'Cgen Estado',
            'cgen_fecha_creacion' => 'Cgen Fecha Creacion',
            'cgen_fecha_modificacion' => 'Cgen Fecha Modificacion',
            'cgen_estado_logico' => 'Cgen Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgen() {
        return $this->hasOne(EspeciesGeneradas::className(), ['egen_id' => 'egen_id']);
    }

    /**
     * Function insertarCodigocertificado
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return cgen_id
     */
    public function insertarCodigocertificado($egen_id, $cgen_codigo, $cgen_fecha_codigo_generado, $cgen_estado_certificado, $cgen_usuario_ingreso) {
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        $param_sql = "cgen_estado";
        $bdet_sql = "1";

        $param_sql .= ", cgen_estado_logico";
        $bdet_sql .= ", 1";

        if (isset($egen_id)) {
            $param_sql .= ", egen_id";
            $bdet_sql .= ", :egen_id";
        }
        if (isset($cgen_codigo)) {
            $param_sql .= ", cgen_codigo";
            $bdet_sql .= ", :cgen_codigo";
        }
        if (isset($cgen_fecha_codigo_generado)) {
            $param_sql .= ", cgen_fecha_codigo_generado";
            $bdet_sql .= ", :cgen_fecha_codigo_generado";
        }
        if (isset($cgen_estado_certificado)) {
            $param_sql .= ", cgen_estado_certificado";
            $bdet_sql .= ", :cgen_estado_certificado";
        }
        if (isset($cgen_usuario_ingreso)) {
            $param_sql .= ", cgen_usuario_ingreso";
            $bdet_sql .= ", :cgen_usuario_ingreso";
        }
        try {
            $sql = "INSERT INTO " . $con->dbname . ".certificados_generadas ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($egen_id)) {
                $comando->bindParam(':egen_id', $egen_id, \PDO::PARAM_INT);
            }
            if (isset($cgen_codigo)) {
                $comando->bindParam(':cgen_codigo', $cgen_codigo, \PDO::PARAM_STR);
            }
            if (!empty((isset($cgen_fecha_codigo_generado)))) {
                $comando->bindParam(':cgen_fecha_codigo_generado', $cgen_fecha_codigo_generado, \PDO::PARAM_STR);
            }
            if (!empty((isset($cgen_estado_certificado)))) {
                $comando->bindParam(':cgen_estado_certificado', $cgen_estado_certificado, \PDO::PARAM_STR);
            }
            if (!empty((isset($cgen_usuario_ingreso)))) {
                $comando->bindParam(':cgen_usuario_ingreso', $cgen_usuario_ingreso, \PDO::PARAM_INT);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.certificados_generadas');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function getCertificadosGeneradas
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return cgen_id
     */
    public static function getCertificadosGeneradas($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " ceg.cgen_fecha_codigo_generado BETWEEN :fec_ini AND :fec_fin AND ";
            }
            if ($arrFiltro['search'] != "") {
                $str_search .= "(D.per_pri_nombre like :estudiante OR ";
                $str_search .= "D.per_pri_apellido like :estudiante OR ";
                $str_search .= "D.per_cedula like :estudiante )  AND ";
            }
            if ($arrFiltro['unidad'] > 0) {
                $str_search .= "esg.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] > 0) {
                $str_search .= "esg.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['estdocerti'] > 0) {
                $str_search .= "ceg.cgen_estado_certificado = :estdocerti AND"; // son los pendientes no estan en la tabla
            }
        }

        $sql = "SELECT cgen_id,
                    concat(F.uaca_nomenclatura,T.tra_nomenclatura,lpad(ifnull(C.esp_codigo,0),3,'0'),'-',esg.egen_numero_solicitud) as egen_numero_solicitud,
                    concat(D.per_pri_nombre,' ',D.per_pri_apellido) Nombres,
                    F.uaca_nombre,
                    G.mod_nombre,
                    ceg.cgen_codigo,
                    ceg.cgen_fecha_codigo_generado,
                    case ceg.cgen_estado_certificado  
                      when 1 then 'Código Generado'  
                      when 2 then 'Certificado Generado'    
                      when 3 then 'Certificado Autorizado'   
                      when 4 then 'Certificado Rechazado'   
                    end as cgen_estado_certificado
                FROM db_academico.certificados_generadas ceg
                INNER JOIN " . $con->dbname . ".especies_generadas esg on esg.egen_id = ceg.egen_id
                INNER JOIN " . $con->dbname . ".especies C ON esg.esp_id=C.esp_id
                INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=esg.uaca_id
                INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=esg.mod_id
                INNER JOIN " . $con->dbname . ".tramite T ON T.tra_id = esg.tra_id
                INNER JOIN (" . $con->dbname . ".estudiante B 
                INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id) ON esg.est_id=B.est_id
                WHERE $str_search
                    ceg.cgen_estado_certificado in (1,2,4) AND
                    ceg.cgen_estado = :estado AND 
                    ceg.cgen_estado_logico = :estado AND
                    esg.egen_estado = :estado AND 
                    esg.egen_estado_logico = :estado  
                ORDER BY ceg.cgen_fecha_codigo_generado DESC; ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $unidad = $arrFiltro['unidad'];
            $modalidad = $arrFiltro['modalidad'];
            $estadocerti = $arrFiltro['estdocerti'];
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
            if ($arrFiltro['estdocerti'] > 0) {
                $comando->bindParam(":estdocerti", $estadocerti, \PDO::PARAM_INT);
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
     * Function consultarCertificadosGeneradas
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return 
     */
    
    public function consultarCertificadosGeneradas($cgen_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT cgen_id,
                    D.per_id,
                    D.per_correo,
                    C.esp_rubro,
                    concat(F.uaca_nomenclatura,T.tra_nomenclatura,lpad(ifnull(C.esp_codigo,0),3,'0'),'-',esg.egen_numero_solicitud) as egen_numero_solicitud,
                    concat(D.per_pri_nombre,' ',ifnull(D.per_seg_nombre,''),' ', D.per_pri_apellido, ' ', ifnull(D.per_seg_apellido,'')) Nombres,
                    D.per_cedula identificacion,
                    F.uaca_nombre,
                    esg.uaca_id,
                    esg.mod_id,
                    G.mod_nombre,
                    ceg.cgen_codigo,
                    ceg.cgen_fecha_codigo_generado,
                    case ceg.cgen_estado_certificado  
                      when 1 then 'Código Generado'  
                      when 2 then 'Certificado Generado' 
                      when 3 then 'Certificado Autorizado' 
                      when 4 then 'Certificado Rechazado' 
                    end as cgen_estado_certificado,
                    ceg.cgen_ruta_archivo_pdf as imagen
                FROM db_academico.certificados_generadas ceg
                INNER JOIN " . $con->dbname . ".especies_generadas esg on esg.egen_id = ceg.egen_id
                INNER JOIN " . $con->dbname . ".especies C ON esg.esp_id=C.esp_id
                INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=esg.uaca_id
                INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=esg.mod_id
                INNER JOIN " . $con->dbname . ".tramite T ON T.tra_id = esg.tra_id
                INNER JOIN (" . $con->dbname . ".estudiante B 
                INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id) ON esg.est_id=B.est_id
                WHERE 
                    cgen_id = :cgen_id AND
                    ceg.cgen_estado = :estado AND 
                    ceg.cgen_estado_logico = :estado AND
                    esg.egen_estado = :estado AND 
                    esg.egen_estado_logico = :estado  
                ORDER BY ceg.cgen_fecha_codigo_generado DESC;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cgen_id", $cgen_id, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    /**
     * Function subirCertificadopdf (Actualiza el certiifcado generado una vez subido el archivo pdf)
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function subirCertificadopdf($cgen_id, $cgen_observacion, $cgen_ruta_archivo_pdf, $cgen_usuario_modifica) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".certificados_generadas
                SET cgen_observacion = :cgen_observacion,
                    cgen_fecha_certificado_subido = :cgen_fecha_certificado_subido,
                    cgen_fecha_caducidad = date(date_add(:cgen_fecha_certificado_subido, interval 1 month)),
                    cgen_ruta_archivo_pdf = :cgen_ruta_archivo_pdf,
                    cgen_estado_certificado = 2,
                    cgen_usuario_modifica = :cgen_usuario_modifica,
                    cgen_fecha_modificacion = :cgen_fecha_modificacion
                WHERE cgen_id = :cgen_id AND 
                      cgen_estado =:estado AND
                      cgen_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cgen_id", $cgen_id, \PDO::PARAM_INT);
        $comando->bindParam(":cgen_observacion", ucfirst(mb_strtolower($cgen_observacion, 'UTF-8')), \PDO::PARAM_STR);
        $comando->bindParam(":cgen_fecha_certificado_subido", $fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":cgen_ruta_archivo_pdf", $cgen_ruta_archivo_pdf, \PDO::PARAM_STR);
        $comando->bindParam(":cgen_usuario_modifica", $cgen_usuario_modifica, \PDO::PARAM_INT);
        $comando->bindParam(":cgen_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
        $response = $comando->execute();

        return $response;
    }

    /**
     * Function listarCertificadosGeneradas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public static function listarCertificadosAutorizados($arrFiltro = array(), $onlyData = false, $opcion) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " ceg.cgen_fecha_autorizacion BETWEEN :fec_ini AND :fec_fin AND ";
            }
            if ($arrFiltro['search'] != "") {
                $str_search .= "(D.per_pri_nombre like :estudiante OR ";
                $str_search .= "D.per_pri_apellido like :estudiante OR ";
                $str_search .= "D.per_cedula like :estudiante )  AND ";
            }
            if ($arrFiltro['unidad'] > 0) {
                $str_search .= "esg.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] > 0) {
                $str_search .= "esg.mod_id = :modalidad AND ";
            }
        }
        if ($opcion == 1) {
            $imagen = ", cgen_ruta_archivo_pdf as imagen";
        } else {
            $imagen = "";
        }
        $sql = "SELECT cgen_id,
                    concat(F.uaca_nomenclatura,T.tra_nomenclatura,lpad(ifnull(C.esp_codigo,0),3,'0'),'-',esg.egen_numero_solicitud) as egen_numero_solicitud,
                    concat(D.per_pri_nombre,' ',D.per_pri_apellido) Nombres,
                    F.uaca_nombre,
                    G.mod_nombre,
                    ceg.cgen_codigo,
                    ceg.cgen_fecha_autorizacion
                    $imagen
                FROM db_academico.certificados_generadas ceg
                INNER JOIN " . $con->dbname . ".especies_generadas esg on esg.egen_id = ceg.egen_id
                INNER JOIN " . $con->dbname . ".especies C ON esg.esp_id=C.esp_id
                INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=esg.uaca_id
                INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=esg.mod_id
                INNER JOIN " . $con->dbname . ".tramite T ON T.tra_id = esg.tra_id
                INNER JOIN (" . $con->dbname . ".estudiante B 
                INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id) ON esg.est_id=B.est_id
                WHERE $str_search
                    cgen_estado_certificado = 3 AND
                    ceg.cgen_estado = :estado AND 
                    ceg.cgen_estado_logico = :estado AND
                    esg.egen_estado = :estado AND 
                    esg.egen_estado_logico = :estado  
                ORDER BY ceg.cgen_fecha_certificado_subido DESC";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $unidad = $arrFiltro['unidad'];
            $modalidad = $arrFiltro['modalidad'];
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
     * Function listarCertificadosSubidos
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return 
     */
    public static function listarCertificadosSubidos($arrFiltro = array(), $onlyData = false, $opcion) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " ceg.cgen_fecha_certificado_subido BETWEEN :fec_ini AND :fec_fin AND ";
            }
            if ($arrFiltro['search'] != "") {
                $str_search .= "(D.per_pri_nombre like :estudiante OR ";
                $str_search .= "D.per_pri_apellido like :estudiante OR ";
                $str_search .= "D.per_cedula like :estudiante )  AND ";
            }
            if ($arrFiltro['unidad'] > 0) {
                $str_search .= "esg.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] > 0) {
                $str_search .= "esg.mod_id = :modalidad AND ";
            }
        }
        if ($opcion == 1) {
            $imagen = ", cgen_ruta_archivo_pdf as imagen";
        } else {
            $imagen = "";
        }
        $sql = "SELECT cgen_id,
                    concat(F.uaca_nomenclatura,T.tra_nomenclatura,lpad(ifnull(C.esp_codigo,0),3,'0'),'-',esg.egen_numero_solicitud) as egen_numero_solicitud,
                    concat(D.per_pri_nombre,' ',D.per_pri_apellido) Nombres,
                    F.uaca_nombre,
                    G.mod_nombre,
                    ceg.cgen_codigo,
                    ceg.cgen_fecha_certificado_subido,
                    ifnull(ceg.cgen_fecha_autorizacion,'') cgen_fecha_autorizacion,
                    case ceg.cgen_estado_certificado                        
                      when 2 then 'Certificado Generado'                       
                      when 4 then 'Certificado Rechazado' 
                    end as cgen_estado_certificado
                    $imagen
                FROM " . $con->dbname . ".certificados_generadas ceg
                INNER JOIN " . $con->dbname . ".especies_generadas esg on esg.egen_id = ceg.egen_id
                INNER JOIN " . $con->dbname . ".especies C ON esg.esp_id=C.esp_id
                INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=esg.uaca_id
                INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=esg.mod_id
                INNER JOIN " . $con->dbname . ".tramite T ON T.tra_id = esg.tra_id
                INNER JOIN (" . $con->dbname . ".estudiante B 
                INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id) ON esg.est_id=B.est_id
                WHERE $str_search
                    cgen_estado_certificado in (2,4) AND
                    ceg.cgen_estado = :estado AND 
                    ceg.cgen_estado_logico = :estado AND
                    esg.egen_estado = :estado AND 
                    esg.egen_estado_logico = :estado  
                ORDER BY ceg.cgen_fecha_certificado_subido DESC";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $unidad = $arrFiltro['unidad'];
            $modalidad = $arrFiltro['modalidad'];
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
     * Function grabarAutorizacion (Actualiza el certiifcado generado una vez subido el archivo pdf)
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function grabarAutorizacion($cgen_id, $resultado, $observacion, $detobserva) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha_autorizacion = date(Yii::$app->params["dateTimeByDefault"]);
        $usuario_autoriza = @Yii::$app->user->identity->usu_id; 
        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".certificados_generadas
                SET cgen_observacion_autorizacion = :cgen_observacion,
                    cgen_fecha_autorizacion = :cgen_fecha_autoriza,                   
                    cgen_estado_certificado = :cgen_resultado,
                    cgen_usuario_autorizacion = :cgen_usuario_autoriza,
                    cgen_fecha_modificacion = :cgen_fecha_autoriza,
                    cgen_observacion_detalle_aut = :cgen_detobservacion
                WHERE cgen_id = :cgen_id AND 
                      cgen_estado =:estado AND
                      cgen_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cgen_id", $cgen_id, \PDO::PARAM_INT);
        $comando->bindParam(":cgen_observacion", $observacion, \PDO::PARAM_STR);
        $comando->bindParam(":cgen_resultado", $resultado, \PDO::PARAM_INT);
        $comando->bindParam(":cgen_fecha_autoriza", $fecha_autorizacion, \PDO::PARAM_STR);        
        $comando->bindParam(":cgen_usuario_autoriza", $usuario_autoriza, \PDO::PARAM_INT);        
        $comando->bindParam(":cgen_detobservacion", $detobserva, \PDO::PARAM_STR);
        $response = $comando->execute();
        return $response;
    }
}
