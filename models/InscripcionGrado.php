<?php

namespace app\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

/**
 * This is the model class for table "inscripcion_grado".
 *
 * @property int $igra_id
 * @property int $per_id
 * @property int $uaca_id
 * @property int $eaca_id
 * @property int $mod_id
 * @property int $paca_id
 * @property string $igra_cedula
 * @property int $igra_financiamiento
 * @property string $igra_institucion_beca
 * @property int $igra_metodo_ingreso
 * @property string $igra_ruta_documento
 * @property string $igra_ruta_doc_titulo
 * @property string $igra_ruta_doc_dni
 * @property string $igra_ruta_doc_certvota
 * @property string $igra_ruta_doc_foto
 * @property string $igra_ruta_doc_comprobantepago
 * @property string $igra_ruta_doc_recordacademico
 * @property string $igra_ruta_doc_certificado
 * @property string $igra_ruta_doc_syllabus
 * @property string $igra_ruta_doc_homologacion
 * @property string $igra_mensaje1
 * @property string $igra_mensaje2
 * @property string $igra_estado
 * @property string $igra_fecha_creacion
 * @property string $igra_fecha_modificacion
 * @property string $igra_estado_logico
 */
class InscripcionGrado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inscripcion_grado';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_inscripcion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_id', 'uaca_id', 'eaca_id', 'mod_id', 'paca_id', 'igra_financiamiento', 'igra_metodo_ingreso'], 'integer'],
            [['uaca_id', 'eaca_id', 'mod_id', 'paca_id', 'igra_cedula', 'igra_financiamiento', 'igra_estado', 'igra_estado_logico'], 'required'],
            [['igra_fecha_creacion', 'igra_fecha_modificacion'], 'safe'],
            [['igra_cedula'], 'string', 'max' => 15],
            [['igra_institucion_beca', 'igra_ruta_documento', 'igra_ruta_doc_titulo', 'igra_ruta_doc_dni', 'igra_ruta_doc_certvota', 'igra_ruta_doc_foto', 'igra_ruta_doc_comprobantepago', 'igra_ruta_doc_recordacademico', 'igra_ruta_doc_certificado', 'igra_ruta_doc_syllabus', 'igra_ruta_doc_homologacion'], 'string', 'max' => 200],
            [['igra_mensaje1', 'igra_mensaje2', 'igra_estado', 'igra_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'igra_id' => 'Igra ID',
            'per_id' => 'Per ID',
            'uaca_id' => 'Uaca ID',
            'eaca_id' => 'Eaca ID',
            'mod_id' => 'Mod ID',
            'paca_id' => 'Paca ID',
            'igra_cedula' => 'Igra Cedula',
            'igra_financiamiento' => 'Igra Financiamiento',
            'igra_institucion_beca' => 'Igra Institucion Beca',
            'igra_metodo_ingreso' => 'Igra Metodo Ingreso',
            'igra_ruta_documento' => 'Igra Ruta Documento',
            'igra_ruta_doc_titulo' => 'Igra Ruta Doc Titulo',
            'igra_ruta_doc_dni' => 'Igra Ruta Doc Dni',
            'igra_ruta_doc_certvota' => 'Igra Ruta Doc Certvota',
            'igra_ruta_doc_foto' => 'Igra Ruta Doc Foto',
            'igra_ruta_doc_comprobantepago' => 'Igra Ruta Doc Comprobantepago',
            'igra_ruta_doc_recordacademico' => 'Igra Ruta Doc Recordacademico',
            'igra_ruta_doc_certificado' => 'Igra Ruta Doc Certificado',
            'igra_ruta_doc_syllabus' => 'Igra Ruta Doc Syllabus',
            'igra_ruta_doc_homologacion' => 'Igra Ruta Doc Homologacion',
            'igra_mensaje1' => 'Igra Mensaje1',
            'igra_mensaje2' => 'Igra Mensaje2',
            'igra_estado' => 'Igra Estado',
            'igra_fecha_creacion' => 'Igra Fecha Creacion',
            'igra_fecha_modificacion' => 'Igra Fecha Modificacion',
            'igra_estado_logico' => 'Igra Estado Logico',
        ];
    }
    /**
     * Function addLabelTimeDocumentos renombra el documento agregando una varible de tiempo
     * @author  Developer Uteg <developer@uteg.edu.ec>
     * @param   int     $sins_id        Id de la solicitud
     * @param   string  $file           Uri del Archivo a modificar
     * @param   int     $timeSt         Parametro a agregar al nombre del archivo
     * @return  $newFile | FALSE (Retorna el nombre del nuevo archivo o false si fue error).
     */
    public static function addLabelTimeDocumentos($igra_id, $file, $timeSt) {
        $arrIm = explode(".", basename($file));
        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
        $baseFile = Yii::$app->basePath;
        $search = ".$typeFile";
        $replace = "$timeSt" . ".$typeFile";
        $newFile = str_replace($search, $replace, $file);
        if (file_exists($baseFile . $file)) {
            if (rename($baseFile . $file, $baseFile . $newFile)) {
                return $newFile;
            }
        } else {
            return $newFile;
        }
    }

    public function consultarper_cedula($per_dni) {
        $con = \Yii::$app->db_asgard;
        $estado = 1;
        \app\models\Utilities::putMessageLogFile('entro con per_cedula : ' .$per_dni);
        $sql = "
                SELECT  per_id
                FROM " . $con->dbname . ".persona
                WHERE per_cedula = :per_dni AND
                     per_estado = :estado AND
                     per_estado_logico = :estado";

        \app\models\Utilities::putMessageLogFile('resultado del query: '.$comando->getRawSql());
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_dni", $per_dni, \PDO::PARAM_INT);
        //$resultData = $comando->queryOne();
        //return $resultData;
        $comando->execute();

        \app\models\Utilities::putMessageLogFile('sacar per_id: '.$sql['per_id']);
        return $sql['per_id'];
    }

    public function consultarInscripcionpersona($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;
        \app\models\Utilities::putMessageLogFile('entro con per_id : ' .$per_id);
        $sql = "
                SELECT  count(*) as per_id
                FROM " . $con->dbname . ".inscripcion_grado
                WHERE per_id = :per_id AND
                     igra_estado = :estado AND
                     igra_estado_logico = :estado";

        \app\models\Utilities::putMessageLogFile('resultado del query: '.$comando->getRawSql());
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function actualizarInscripciongrado($data) {
        $arroout = array();
        $con = \Yii::$app->db_inscripcion;
        $trans = $con->beginTransaction();
        try {

            $igra_id = $this->updateDataInscripciongrado($con, $data["DATA_1"]);
            if (empty($data['opcion'])) {
                $data = $this->consultarDatosInscripciongrado($igra_id);
            } else {
                $data = $this->consultarDatosInscripcionContinuagrado($igra_id);
            }
            $trans->commit();
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["ids"] = $igra_id;
            $arroout["data"] = $data; //$rawData;
            return $arroout;
        } catch (Exception $e) {
            $trans->rollback();
            $arroout["status"] = FALSE;
            $arroout["error"] = $e->getCode();
            $arroout["message"] = $e->getMessage();
            $arroout["data"] = null; //$rawData;
            return $arroout;
        }
    }

    public function insertarDataInscripciongrado($per_id, $unidad, $carrera, $modalidad, $periodo, $per_dni, $data) {
        $con = \Yii::$app->db_inscripcion;
        //\app\models\Utilities::putMessageLogFile('datos de archivo cargados:' . $data['igra_ruta_doc_titulo']);
        //\app\models\Utilities::putMessageLogFile('id de persona:' . $data);
        $igra_financiamiento = $data["financiamiento"];
        $igra_institucion_beca = ucwords(strtolower($data["instituto"]));
        $igra_ruta_documento = $data['igra_ruta_doc_documento'];
        $igra_ruta_doc_titulo = $data['igra_ruta_doc_titulo'];
        $igra_ruta_doc_dni = $data['igra_ruta_doc_dni'];
        $igra_ruta_doc_certvota = $data['igra_ruta_doc_certvota'];
        $igra_ruta_doc_foto = $data['igra_ruta_doc_foto'];
        $igra_ruta_doc_comprobantepago = $data['igra_ruta_doc_comprobantepago'];
        $igra_ruta_doc_record = $data['igra_ruta_doc_record'];
        $igra_ruta_doc_certificado = $data['igra_ruta_doc_certificado'];
        $igra_ruta_doc_syllabus = $data['igra_ruta_doc_syllabus'];
        $igra_ruta_doc_homologacion = $data['igra_ruta_doc_homologacion'];
        $igra_mensaje1 = $data['igra_mensaje1'];
        $igra_mensaje2 = $data['igra_mensaje2'];

        $sql = "INSERT INTO " . $con->dbname . ".inscripcion_grado
            (per_id, uaca_id, eaca_id, mod_id, paca_id, igra_cedula, igra_financiamiento, igra_institucion_beca, igra_metodo_ingreso, igra_ruta_documento, igra_ruta_doc_titulo, igra_ruta_doc_dni, igra_ruta_doc_certvota, igra_ruta_doc_foto, igra_ruta_doc_comprobantepago, igra_ruta_doc_recordacademico, igra_ruta_doc_certificado, igra_ruta_doc_syllabus, igra_ruta_doc_homologacion, igra_mensaje1, igra_mensaje2, igra_estado, igra_fecha_creacion, igra_estado_logico)VALUES
            (:per_id, :uaca_id, :eaca_id, :mod_id, :paca_id, :per_dni, :igra_financiamiento, :igra_institucion_beca, :igra_metodo_ingreso, :igra_ruta_documento, :igra_ruta_doc_titulo, :igra_ruta_doc_dni, :igra_ruta_doc_certvota, :igra_ruta_doc_foto, :igra_ruta_doc_comprobantepago, :igra_ruta_doc_record, :igra_ruta_doc_certificado, :igra_ruta_doc_syllabus, :igra_ruta_doc_homologacion, :igra_mensaje1, :igra_mensaje2, 1, CURRENT_TIMESTAMP(), 1)";

        $met_ing = 0;
        if (empty($data['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data['ming_id'];
        }
        \app\models\Utilities::putMessageLogFile('identificacion:' . $data['cedula']);
        $command = $con->createCommand($sql);
        //$command->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $command->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $command->bindParam(":uaca_id", $unidad, \PDO::PARAM_INT);
        $command->bindParam(":eaca_id", $carrera, \PDO::PARAM_INT);
        $command->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
        $command->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
        $command->bindParam(":per_dni", $per_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":igra_financiamiento", $igra_financiamiento, \PDO::PARAM_INT);
        $command->bindParam(":igra_institucion_beca", $igra_institucion_beca, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_documento", $igra_ruta_documento, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_titulo", $igra_ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_dni", $igra_ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_certvota", $igra_ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_foto", $igra_ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_comprobantepago", $igra_ruta_doc_comprobantepago, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_record", $igra_ruta_doc_record, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_certificado", $igra_ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_syllabus", $igra_ruta_doc_syllabus, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_homologacion", $igra_ruta_doc_homologacion, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje1", $igra_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje2", $igra_mensaje2, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    public function updateDataInscripciongrado($con, $per_id, $unidad , $modalidad , $carrera, $periodo, $per_dni, $igra_financiamiento, $igra_institucion_beca, $igra_ruta_documento, $igra_ruta_doc_titulo, $igra_ruta_doc_dni, $igra_ruta_doc_certvota, $igra_ruta_doc_foto, $igra_ruta_doc_comprobantepago, $igra_ruta_doc_record, $igra_ruta_doc_certificado, $igra_ruta_doc_syllabus, $igra_ruta_doc_homologacion) {
        //\app\models\Utilities::putMessageLogFile('igra_ruta_doc_certvota:  '.$igra_ruta_doc_certvota);
        $imagenes = "";
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        }

        if(!empty($unidad)){
            $academico .= "uaca_id=:uaca_id,";
        }

        if(!empty($modalidad)){
            $academico .= "eaca_id=:eaca_id,";
        }

        if(!empty($carrera)){
            $academico .= "eaca_id=:eaca_id,";
        }

        if(!empty($periodo)){
            $academico .= "paca_id=:paca_id,";
        }

        if(!empty($igra_ruta_documento)){
            $imagenes .= "igra_ruta_documento=:igra_ruta_documento,";
        }
        if(!empty($igra_ruta_doc_titulo)){
            $imagenes .= "igra_ruta_doc_titulo=:igra_ruta_doc_titulo,";
        }
        if(!empty($igra_ruta_doc_dni)){
            $imagenes .= "igra_ruta_doc_dni=:igra_ruta_doc_dni,";
        }
        if(!empty($igra_ruta_doc_certvota)){
            $imagenes .= "igra_ruta_doc_certvota=:igra_ruta_doc_certvota,";
        }
        if(!empty($igra_ruta_doc_foto)){
           $imagenes .= "igra_ruta_doc_foto=:igra_ruta_doc_foto,";
        }
        if(!empty($igra_ruta_doc_comprobantepago)){
           $imagenes .= "igra_ruta_doc_comprobantepago=:igra_ruta_doc_comprobantepago,";
        }
        if(!empty($igra_ruta_doc_record)){
           $imagenes .= "igra_ruta_doc_recordacademico=:igra_ruta_doc_record,";
        }
        if(!empty($igra_ruta_doc_certificado)){
           $imagenes .= "igra_ruta_doc_certificado=:igra_ruta_doc_certificado,";
        }
        if(!empty($igra_ruta_doc_syllabus)){
            $imagenes .= "igra_ruta_doc_syllabus=:igra_ruta_doc_syllabus,";
        }
        if(!empty($igra_ruta_doc_homologacion)){
           $imagenes .= "igra_ruta_doc_homologacion=:igra_ruta_doc_homologacion,";
        }
        try {
            $command = $con->createCommand
                    ("UPDATE " . $con->dbname . ".inscripcion_grado
                        SET igra_cedula=:per_dni,
                        igra_financiamiento=:igra_financiamiento,
                        igra_institucion_beca=:igra_institucion_beca,
                        $imagenes
                        $academico
                        igra_fecha_modificacion=:igra_fecha_modificacion
                        WHERE per_id =:per_id");
            //$command->bindParam(":igra_id", $igra_id, \PDO::PARAM_INT);
            // si vienen nulos no agregarlos
            $command->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            if(!empty($unidad)){
             $command->bindParam(":uaca_id", $unidad, \PDO::PARAM_INT);
            }
            if(!empty($carrera)){
             $command->bindParam(":eaca_id", $carrera, \PDO::PARAM_INT);
            }
            if(!empty($modalidad)){
              $command->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }
            if(!empty($periodo)){
              $command->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            $command->bindParam(":per_dni", $per_dni, \PDO::PARAM_STR);
            $command->bindParam(":igra_financiamiento", $igra_financiamiento, \PDO::PARAM_INT);
            $command->bindParam(":igra_institucion_beca", $igra_institucion_beca, \PDO::PARAM_STR);
            //$command->bindParam(":igra_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
            // si vienen nulos no agregarlos
             if(!empty($igra_ruta_documento)){
                $command->bindParam(":igra_ruta_documento", $igra_ruta_documento, \PDO::PARAM_STR);
                }
            if(!empty($igra_ruta_doc_titulo)){
            $command->bindParam(":igra_ruta_doc_titulo", $igra_ruta_doc_titulo, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_dni)){
            $command->bindParam(":igra_ruta_doc_dni", $igra_ruta_doc_dni, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_certvota)){
            $command->bindParam(":igra_ruta_doc_certvota", $igra_ruta_doc_certvota, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_foto)){
            $command->bindParam(":igra_ruta_doc_foto", $igra_ruta_doc_foto, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_comprobantepago)){
            $command->bindParam(":igra_ruta_doc_comprobantepago", $igra_ruta_doc_comprobantepago, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_record)){
                $command->bindParam(":igra_ruta_doc_record", $igra_ruta_doc_record, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_certificado)){
                $command->bindParam(":igra_ruta_doc_certificado", $igra_ruta_doc_certificado, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_syllabus)){
                $command->bindParam(":igra_ruta_doc_syllabus", $igra_ruta_doc_syllabus, \PDO::PARAM_STR);
            }
            if(!empty($igra_ruta_doc_homologacion)){
                $command->bindParam(":igra_ruta_doc_homologacion", $igra_ruta_doc_homologacion, \PDO::PARAM_STR);
            }
            // si vienen nulos no agragarlos
            $command->bindParam(":igra_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $response = $command->execute();

        if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }


    public function consultarDatosInscripciongrado($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;
        $sql = "
                SELECT
                        count(*) as existe_inscripcion
                FROM " . $con->dbname . ".inscripcion_grado
                WHERE per_id = :per_id AND
                     igra_estado = :estado AND
                     igra_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarDatosInscripcionContinuagrado($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;
        //$estado_precio = 'A';

        $sql = "SELECT  igra_id,
                        per_id,
                        uaca_id,
                        eaca_id,
                        mod_id,
                        paca_id
                FROM " . $con->dbname . ".inscripcion_grado
                WHERE per_id = :per_id AND
                     igra_estado = :estado AND
                     igra_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaRegistroAdmisiongrado($arrFiltro = array(), $reporte){
        $con_inscripcion = \Yii::$app->db_inscripcion;
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "igra.igra_cedula like :search) AND ";

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "igra.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['carreras'] != "" && $arrFiltro['carreras'] > 0) {
                $str_search .= "igra.eaca_id = :carreras AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "igra.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "igra.paca_id = :periodo AND ";
            }
        }

        $sql = "SELECT distinct per.per_id as per_id,
                per.per_cedula as Cedula,
                ifnull(CONCAT(ifnull(per.per_pri_apellido,''), ' ', ifnull(per.per_seg_apellido,''), ' ', ifnull(per.per_pri_nombre,''), ' ', ifnull(per.per_seg_nombre,'')), '') as estudiante,
                CONCAT(baca.baca_nombre, ' ', saca.saca_nombre, ' ', saca.saca_anio) as periodo,
                eaca.eaca_nombre as carrera,
                moda.mod_nombre as modalidad
                FROM " . $con_inscripcion->dbname . ".inscripcion_grado as igra
                Inner Join " . $con_asgard->dbname . ".persona as per on per.per_id = igra.per_id
                Inner Join " . $con_academico->dbname . ".unidad_academica as uaca on uaca.uaca_id = igra.uaca_id
                Inner Join " . $con_academico->dbname . ".estudio_academico as eaca on eaca.eaca_id = igra.eaca_id
                Inner Join " . $con_academico->dbname . ".modalidad as moda on moda.mod_id = igra.mod_id
                Inner Join " . $con_academico->dbname . ".periodo_academico as paca on paca.paca_id = igra.paca_id
                Inner Join " . $con_academico->dbname . ".semestre_academico as saca on saca.saca_id = paca.saca_id
                Inner Join " . $con_academico->dbname . ".bloque_academico as baca on baca.baca_id = paca.baca_id
                WHERE $str_search
                igra.igra_estado = :estado and igra.igra_estado_logico = :estado and
                per.per_estado = :estado and per.per_estado_logico = :estado and
                uaca.uaca_estado = :estado and uaca.uaca_estado_logico = :estado and
                -- eaca.eaca_estado = :estado and eaca.eaca_estado_logico = :estado and
                -- moda.mod_estado = :estado and moda.mod_estado_logico = :estado and
                paca.paca_estado = :estado and paca.paca_estado_logico = :estado";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['carreras'] != "" && $arrFiltro['carreras'] > 0) {
                $search_car = $arrFiltro["carreras"];
                $comando->bindParam(":carreras", $search_car, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $search_per = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $search_per, \PDO::PARAM_INT);
            }
        }
        $res = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile('insertRegBasc: ' . $comando->getRawSql());
        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Cedula", "estudiante","periodo","carrera","modalidad"],
            ],
        ]);

        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $res;
        }
    }

    public function ObtenerdocumentosInscripcionGrado($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "SELECT  per_id,
                igra_ruta_documento,
                igra_ruta_doc_titulo,
                igra_ruta_doc_dni,
                igra_ruta_doc_certvota,
                igra_ruta_doc_foto,
                igra_ruta_doc_comprobantepago,
                igra_ruta_doc_recordacademico,
                igra_ruta_doc_certificado,
                igra_ruta_doc_syllabus,
                igra_ruta_doc_homologacion
                FROM " . $con->dbname . ".inscripcion_grado
                WHERE per_id = :per_id AND
                      igra_estado = :estado AND
                      igra_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }


    public function consultarPdf($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;
        \app\models\Utilities::putMessageLogFile('entro con per_id : ' .$per_id);
        $sql = "
       SELECT distinct
max(maca.maca_id) as mallacorresp, --
maca.maca_codigo as codigo, --
(SELECT max(maca.maca_id) FROM db_academico.modalidad_estudio_unidad meun
inner join  db_academico.malla_unidad_modalidad mumo on meun.meun_id = mumo.meun_id
inner join db_academico.malla_academica maca on maca.maca_id = mumo.maca_id
where meun.eaca_id = igra.eaca_id
and meun.uaca_id = igra.uaca_id
and meun.mod_id = igra.mod_id
and meun.meun_estado = 1 
and meun.meun_estado_logico = 1
and mumo.mumo_estado = 1 
and mumo.mumo_estado_logico = 1
and maca.maca_estado = 1 
and maca.maca_estado_logico = 1)) as malla,
igra.igra_fecha_creacion as registro,
eaca.eaca_nombre as carrera,
moda.mod_nombre as modalidad,
CONCAT(baca.baca_nombre, ' ', saca.saca_nombre, ' ', saca.saca_anio) as periodo,
igra.igra_ruta_documento,
igra.igra_ruta_doc_comprobantepago,
igra.igra_ruta_doc_recordacademico,
igra.igra_ruta_doc_certificado,
igra.igra_ruta_doc_syllabus,
igra.igra_ruta_doc_homologacion,
igra.igra_financiamiento,
igra.igra_institucion_beca,
igra.igra_ruta_doc_foto,
per.per_cedula as cedula,
per.per_pasaporte as pasaporte,
ifnull(CONCAT(ifnull(per.per_pri_nombre,''), ' ', ifnull(per.per_seg_nombre,'')), '') as nombres,
ifnull(CONCAT(ifnull(per.per_pri_apellido,''), ' ', ifnull(per.per_seg_apellido,'')), '') as apellidos,
-- lugar de nacimiento
per.per_fecha_nacimiento,
pais.pai_nombre,
esta.eciv_nombre,
ifnull(CONCAT(ifnull(per.per_domicilio_sector,''), ' ', ifnull(per.per_domicilio_cpri,''),' ',
ifnull(per.per_domicilio_csec,''),' ',ifnull(per.per_domicilio_num,''),' '
,ifnull(per.per_domicilio_ref,''),' '
), '') as domicilio,
per_celular,
per_domicilio_telefono,
per_correo,
ifnull(CONCAT(ifnull(per.per_trabajo_direccion,''), ' ', ifnull(per.per_trabajo_nombre,''),' '), '') as trabajo,
contac.pcon_nombre, 
parente.tpar_nombre, 
contac.pcon_telefono, 
contac.pcon_direccion,
mallagen.maca_id,
mallagen.maca_nombre,
ifnull(estud.est_categoria,'No definida') as categoria
FROM db_inscripcion.inscripcion_grado as igra
Inner Join db_asgard.persona as per on per.per_id = igra.per_id
Inner Join db_asgard.pais as pais on pais.pai_id = per.per_nacionalidad
Inner Join db_asgard.estado_civil as esta on esta.eciv_id = per.eciv_id
Inner join db_academico.estudiante as estud on per.per_id = estud.per_id
Inner Join db_academico.unidad_academica as uaca on uaca.uaca_id = igra.uaca_id
Inner Join db_academico.estudio_academico as eaca on eaca.eaca_id = igra.eaca_id
Inner Join db_academico.modalidad_estudio_unidad as meun on meun.eaca_id = igra.eaca_id -- 
inner join  db_academico.malla_unidad_modalidad mumo on meun.meun_id = mumo.meun_id -- 
inner join db_academico.malla_academica maca on maca.maca_id = mumo.maca_id --
Inner Join db_academico.modalidad as moda on moda.mod_id = igra.mod_id
Inner Join db_academico.periodo_academico as paca on paca.paca_id = igra.paca_id
Inner Join db_academico.semestre_academico as saca on saca.saca_id = paca.saca_id
Inner Join db_academico.bloque_academico as baca on baca.baca_id = paca.baca_id
Inner Join db_asgard.persona_contacto as contac on contac.per_id = igra.per_id
Inner Join db_asgard.tipo_parentesco as parente on parente.tpar_id = contac.tpar_id
Inner Join db_academico.malla_academico_estudiante as mallaes ON mallaes.per_id =  igra.per_id
Inner Join db_academico.malla_academica as mallagen ON mallagen.maca_id =  mallaes.maca_id
WHERE 
igra.uaca_id = meun.uaca_id AND --
igra.mod_id = meun.mod_id AND
igra.per_id = :per_id AND
igra.igra_estado = :estado and igra.igra_estado_logico = :estado and
per.per_estado = :estado and per.per_estado_logico = :estado and
uaca.uaca_estado = :estado and uaca.uaca_estado_logico = :estado and
eaca.eaca_estado = :estado and eaca.eaca_estado_logico = :estado and
moda.mod_estado = :estado and moda.mod_estado_logico = :estado and
paca.paca_estado = :estado and paca.paca_estado_logico = :estado and
meun.meun_estado = :estado and meun.meun_estado_logico = :estado  and   --
mumo.mumo_estado = :estado and mumo.mumo_estado_logico = :estado  and   --
maca.maca_estado = :estado and maca.maca_estado_logico = :estado     --
                    ";


        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();

        return $resultData;
    }
}
