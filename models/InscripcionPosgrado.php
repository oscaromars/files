<?php

namespace app\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

/**
 * This is the model class for table "inscripcion_posgrado".
 *
 * @property int $ipos_id
 * @property int $per_id
 * @property int $uaca_id
 * @property int $eaca_id
 * @property int $mod_id
 * @property string $ipos_anio
 * @property string $ipos_cedula
 * @property string $ipos_tipo_financiamiento
 * @property int $ipos_metodo_ingreso
 * @property string $ipos_ruta_documento
 * @property string $ipos_ruta_doc_foto
 * @property string $ipos_ruta_doc_dni
 * @property string $ipos_ruta_doc_certvota
 * @property string $ipos_ruta_doc_titulo
 * @property string $ipos_ruta_doc_comprobantepago
 * @property string $ipos_ruta_doc_recordacademico
 * @property string $ipos_ruta_doc_senescyt
 * @property string $ipos_ruta_doc_hojadevida
 * @property string $ipos_ruta_doc_cartarecomendacion
 * @property string $ipos_ruta_doc_certificadolaboral
 * @property string $ipos_ruta_doc_certificadoingles
 * @property string $ipos_ruta_doc_otrorecord
 * @property string $ipos_ruta_doc_certificadonosancion
 * @property string $ipos_ruta_doc_syllabus
 * @property string $ipos_ruta_doc_homologacion
 * @property string $ipos_mensaje1
 * @property string $ipos_mensaje2
 * @property string $ipos_estado
 * @property string $ipos_fecha_creacion
 * @property string $ipos_fecha_modificacion
 * @property string $ipos_estado_logico
 */
class InscripcionPosgrado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inscripcion_posgrado';
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
            [['per_id', 'uaca_id', 'eaca_id', 'mod_id', 'ipos_estado', 'ipos_estado_logico'], 'required'],
            [['per_id', 'uaca_id', 'eaca_id', 'mod_id', 'ipos_metodo_ingreso'], 'integer'],
            [['ipos_fecha_creacion', 'ipos_fecha_modificacion'], 'safe'],
            [['ipos_anio', 'ipos_cedula'], 'string', 'max' => 50],
            [['ipos_tipo_financiamiento', 'ipos_ruta_documento', 'ipos_ruta_doc_foto', 'ipos_ruta_doc_dni', 'ipos_ruta_doc_certvota', 'ipos_ruta_doc_titulo', 'ipos_ruta_doc_comprobantepago', 'ipos_ruta_doc_recordacademico', 'ipos_ruta_doc_senescyt', 'ipos_ruta_doc_hojadevida', 'ipos_ruta_doc_cartarecomendacion', 'ipos_ruta_doc_certificadolaboral', 'ipos_ruta_doc_certificadoingles', 'ipos_ruta_doc_otrorecord', 'ipos_ruta_doc_certificadonosancion', 'ipos_ruta_doc_syllabus', 'ipos_ruta_doc_homologacion'], 'string', 'max' => 200],
            [['ipos_mensaje1', 'ipos_mensaje2', 'ipos_estado', 'ipos_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ipos_id' => 'Ipos ID',
            'per_id' => 'Per ID',
            'uaca_id' => 'Uaca ID',
            'eaca_id' => 'Eaca ID',
            'mod_id' => 'Mod ID',
            'ipos_anio' => 'Ipos Anio',
            'ipos_cedula' => 'Ipos Cedula',
            'ipos_tipo_financiamiento' => 'Ipos Tipo Financiamiento',
            'ipos_metodo_ingreso' => 'Ipos Metodo Ingreso',
            'ipos_ruta_documento' => 'Ipos Ruta Documento',
            'ipos_ruta_doc_foto' => 'Ipos Ruta Doc Foto',
            'ipos_ruta_doc_dni' => 'Ipos Ruta Doc Dni',
            'ipos_ruta_doc_certvota' => 'Ipos Ruta Doc Certvota',
            'ipos_ruta_doc_titulo' => 'Ipos Ruta Doc Titulo',
            'ipos_ruta_doc_comprobantepago' => 'Ipos Ruta Doc Comprobantepago',
            'ipos_ruta_doc_recordacademico' => 'Ipos Ruta Doc Recordacademico',
            'ipos_ruta_doc_senescyt' => 'Ipos Ruta Doc Senescyt',
            'ipos_ruta_doc_hojadevida' => 'Ipos Ruta Doc Hojadevida',
            'ipos_ruta_doc_cartarecomendacion' => 'Ipos Ruta Doc Cartarecomendacion',
            'ipos_ruta_doc_certificadolaboral' => 'Ipos Ruta Doc Certificadolaboral',
            'ipos_ruta_doc_certificadoingles' => 'Ipos Ruta Doc Certificadoingles',
            'ipos_ruta_doc_otrorecord' => 'Ipos Ruta Doc Otrorecord',
            'ipos_ruta_doc_certificadonosancion' => 'Ipos Ruta Doc Certificadonosancion',
            'ipos_ruta_doc_syllabus' => 'Ipos Ruta Doc Syllabus',
            'ipos_ruta_doc_homologacion' => 'Ipos Ruta Doc Homologacion',
            'ipos_mensaje1' => 'Ipos Mensaje1',
            'ipos_mensaje2' => 'Ipos Mensaje2',
            'ipos_estado' => 'Ipos Estado',
            'ipos_fecha_creacion' => 'Ipos Fecha Creacion',
            'ipos_fecha_modificacion' => 'Ipos Fecha Modificacion',
            'ipos_estado_logico' => 'Ipos Estado Logico',
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
    public static function addLabelTimeDocumentos($ipos_id, $file, $timeSt) {
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

    public function consultarInscripcionposgradoxpersona($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;
        \app\models\Utilities::putMessageLogFile('entro con per_id : ' .$per_id);
        $sql = "
                SELECT  count(*) as per_id
                FROM " . $con->dbname . ".inscripcion_posgrado
                WHERE per_id = :per_id AND
                     ipos_estado = :estado AND
                     ipos_estado_logico = :estado";

        \app\models\Utilities::putMessageLogFile('resultado del query: '.$comando->getRawSql());
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function actualizarInscripcionposgrado($data) {
        $arroout = array();
        $con = \Yii::$app->db_inscripcion;
        $trans = $con->beginTransaction();
        try {

            $ipos_id = $this->updateDataInscripcionposgrado($con, $data["DATA_1"]);
            if (empty($data['opcion'])) {
                $data = $this->consultarDatosInscripcionposgrado($ipos_id);
            } else {
                $data = $this->consultarDatosInscripcionContinuaposgrado($ipos_id);
            }
            $trans->commit();
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["ids"] = $ipos_id;
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

    public function insertarDataInscripcionposgrado($per_id, $uaca_id, $eaca_id, $mod_id, $ipos_año, $ipos_cedula, $ipos_tipo_financiamiento, $data) {
        $con = \Yii::$app->db_inscripcion;
        //\app\models\Utilities::putMessageLogFile('datos de archivo cargados:' . $data['ipos_ruta_doc_titulo']);
        //\app\models\Utilities::putMessageLogFile('id de persona:' . $data);
        $met_ing = '0';
        /*if (empty($data['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data['ming_id'];
        }*/
        $ipos_ruta_doc_foto = $data['ipos_ruta_doc_foto'];
        $ipos_ruta_doc_dni = $data['ipos_ruta_doc_dni'];
        $ipos_ruta_doc_certvota = $data['ipos_ruta_doc_certvota'];
        $ipos_ruta_doc_titulo = $data['ipos_ruta_doc_titulo'];
        $ipos_ruta_doc_comprobantepago = $data['ipos_ruta_doc_comprobante'];
        $ipos_ruta_doc_recordacademico = $data['ipos_ruta_doc_record1'];
        $ipos_ruta_doc_senescyt = $data['ipos_ruta_doc_senescyt'];
        $ipos_ruta_doc_hojadevida = $data['ipos_ruta_doc_hojavida'];
        $ipos_ruta_doc_cartarecomendacion = $data['ipos_ruta_doc_cartarecomendacion'];
        $ipos_ruta_doc_certificadolaboral = $data['ipos_ruta_doc_certificadolaboral'];
        $ipos_ruta_doc_certificadoingles = $data['ipos_ruta_doc_certificadoingles'];
        $ipos_ruta_doc_otrorecord = null;
        $ipos_ruta_doc_certificadonosancion = null;
        $ipos_ruta_doc_syllabus = null;
        $ipos_ruta_doc_homologacion = null;

        if(!empty($ipos_ruta_doc_otrorecord)){
            $ipos_ruta_doc_otrorecord = $data['ipos_ruta_doc_recordacademico'];
        }
        if(!empty($ipos_ruta_doc_certificadonosancion)){
            $ipos_ruta_doc_certificadonosancion = $data['ipos_ruta_doc_certnosancion'];
        }
        if(!empty($ipos_ruta_doc_syllabus)){
            $ipos_ruta_doc_syllabus = $data['ipos_ruta_doc_syllabus'];
        }
        if(!empty($ipos_ruta_doc_homologacion)){
            $ipos_ruta_doc_homologacion = $data['ipos_ruta_doc_homologacion'];
        }
        $ipos_mensaje1 = $data['ipos_mensaje1'];
        $ipos_mensaje2 = $data['ipos_mensaje2'];

        $sql = "INSERT INTO " . $con->dbname . ".inscripcion_posgrado
            (per_id, uaca_id, eaca_id, mod_id, ipos_anio, ipos_cedula,
             ipos_tipo_financiamiento, ipos_metodo_ingreso, ipos_ruta_doc_foto,
             ipos_ruta_doc_dni, ipos_ruta_doc_certvota, ipos_ruta_doc_titulo,
             ipos_ruta_doc_comprobantepago, ipos_ruta_doc_recordacademico,
             ipos_ruta_doc_senescyt, ipos_ruta_doc_hojadevida,
             ipos_ruta_doc_cartarecomendacion, ipos_ruta_doc_certificadolaboral,
             ipos_ruta_doc_certificadoingles, ipos_ruta_doc_otrorecord,
             ipos_ruta_doc_certificadonosancion, ipos_ruta_doc_syllabus,
             ipos_ruta_doc_homologacion, ipos_mensaje1, ipos_mensaje2,
             ipos_estado, ipos_estado_logico)VALUES
            (:per_id, :uaca_id, :eaca_id, :mod_id, :ipos_anio, :ipos_cedula,
            :ipos_tipo_financiamiento, :ipos_metodo_ingreso, :ipos_ruta_doc_foto,
            :ipos_ruta_doc_dni, :ipos_ruta_doc_certvota, :ipos_ruta_doc_titulo,
            :ipos_ruta_doc_comprobantepago, :ipos_ruta_doc_recordacademico,
            :ipos_ruta_doc_senescyt, :ipos_ruta_doc_hojadevida,
            :ipos_ruta_doc_cartarecomendacion, :ipos_ruta_doc_certificadolaboral,
            :ipos_ruta_doc_certificadoingles,:ipos_ruta_doc_otrorecord,
            :ipos_ruta_doc_certificadonosancion, :ipos_ruta_doc_syllabus,
            :ipos_ruta_doc_homologacion, :ipos_mensaje1, :ipos_mensaje2, 1, 1)";

        //\app\models\Utilities::putMessageLogFile('identificacion:' . $data['cedula']);
        $command = $con->createCommand($sql);
        //$command->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $command->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $command->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $command->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $command->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $command->bindParam(":ipos_anio", $ipos_año, \PDO::PARAM_STR);
        $command->bindParam(":ipos_cedula", $ipos_cedula, \PDO::PARAM_STR);
        $command->bindParam(":ipos_tipo_financiamiento", $ipos_tipo_financiamiento, \PDO::PARAM_STR);
        $command->bindParam(":ipos_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":ipos_ruta_doc_foto", $ipos_ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_dni", $ipos_ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certvota", $ipos_ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_titulo", $ipos_ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_comprobantepago", $ipos_ruta_doc_comprobantepago, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_recordacademico", $ipos_ruta_doc_recordacademico, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_senescyt", $ipos_ruta_doc_senescyt, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_hojadevida", $ipos_ruta_doc_hojadevida, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_cartarecomendacion", $ipos_ruta_doc_cartarecomendacion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadolaboral", $ipos_ruta_doc_certificadolaboral, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadoingles", $ipos_ruta_doc_certificadoingles, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_otrorecord", $ipos_ruta_doc_otrorecord, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadonosancion", $ipos_ruta_doc_certificadonosancion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_syllabus", $ipos_ruta_doc_syllabus, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_homologacion", $ipos_ruta_doc_homologacion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_mensaje1", $ipos_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":ipos_mensaje2", $ipos_mensaje2, \PDO::PARAM_STR);

        $command->execute();

        return $con->getLastInsertID();

    }

    public function updateDataInscripcionposgrado($con, $per_id, $uaca_id, $eaca_id, $mod_id, $ipos_año, $ipos_cedula, $ipos_tipo_finaciamiento, $ipos_metodo_ingreso, $ipos_ruta_documento, $ipos_ruta_doc_foto, $ipos_ruta_doc_dni, $ipos_ruta_doc_certvota, $ipos_ruta_doc_titulo, $ipos_ruta_doc_comprobantepago, $ipos_ruta_doc_recordacademico, $ipos_ruta_doc_senescyt, $ipos_ruta_doc_hojadevida, $ipos_ruta_doc_cartarecomendacion, $ipos_ruta_doc_certificadolaboral, $ipos_ruta_doc_certificadoingles, $ipos_ruta_doc_otrorecord, $ipos_ruta_doc_certificadonosancion, $ipos_ruta_doc_syllabus, $ipos_ruta_doc_homologacion, $ipos_mensaje1, $ipos_mensaje2) {

        $imagenes = "";
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        /*$met_ing = 0;
        if (empty($data['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data['ming_id'];
        }*/
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        }
        if(!empty($ipos_ruta_documento)){
            $imagenes .= "ipos_ruta_documento=:ipos_ruta_documento,";
        }
        if(!empty($ipos_ruta_doc_foto)){
            $imagenes .= "ipos_ruta_doc_foto=:ipos_ruta_doc_foto,";
        }
        if(!empty($ipos_ruta_doc_dni)){
            $imagenes .= "ipos_ruta_doc_dni=:ipos_ruta_doc_dni,";
        }
        if(!empty($ipos_ruta_doc_certvota)){
            $imagenes .= "ipos_ruta_doc_certvota=:ipos_ruta_doc_certvota,";
        }
        if(!empty($ipos_ruta_doc_titulo)){
           $imagenes .= "ipos_ruta_doc_titulo=:ipos_ruta_doc_titulo,";
        }
        if(!empty($ipos_ruta_doc_comprobantepago)){
           $imagenes .= "ipos_ruta_doc_comprobantepago=:ipos_ruta_doc_comprobantepago,";
        }
        if(!empty($ipos_ruta_doc_recordacademico)){
           $imagenes .= "ipos_ruta_doc_recordacademico=:ipos_ruta_doc_recordacademico,";
        }
        if(!empty($ipos_ruta_doc_senescyt)){
           $imagenes .= "ipos_ruta_doc_senescyt=:ipos_ruta_doc_senescyt,";
        }
        if(!empty($ipos_ruta_doc_hojadevida)){
            $imagenes .= "ipos_ruta_doc_hojadevida=:ipos_ruta_doc_hojadevida,";
        }
        if(!empty($ipos_ruta_doc_cartarecomendacion)){
           $imagenes .= "ipos_ruta_doc_cartarecomendacion=:ipos_ruta_doc_cartarecomendacion,";
        }
        if(!empty($ipos_ruta_doc_certificadolaboral)){
            $imagenes .= "ipos_ruta_doc_certificadolaboral=:ipos_ruta_doc_certificadolaboral,";
        }
        if(!empty($ipos_ruta_doc_certificadoingles)){
            $imagenes .= "ipos_ruta_doc_certificadoingles=:ipos_ruta_doc_certificadoingles,";
        }
        if(!empty($ipos_ruta_doc_otrorecord)){
            $imagenes .= "ipos_ruta_doc_otrorecord=:ipos_ruta_doc_otrorecords,";
        }
        if(!empty($ipos_ruta_doc_certificadonosancion)){
           $imagenes .= "ipos_ruta_doc_certificadonosancion=:ipos_ruta_doc_certificadonosancion,";
        }
        if(!empty($ipos_ruta_doc_syllabus)){
           $imagenes .= "ipos_ruta_doc_syllabus=:ipos_ruta_doc_syllabus,";
        }
        if(!empty($ipos_ruta_doc_homologacion)){
           $imagenes .= "ipos_ruta_doc_homologacion=:ipos_ruta_doc_homologacion,";
        }
        if(!empty($ipos_mensaje1)){
           $imagenes .= "ipos_mensaje1=:ipos_mensaje1,";
        }
        if(!empty($ipos_mensaje2)){
            $imagenes .= "ipos_mensaje2=:ipos_mensaje2,";
        }
        try {
            $command = $con->createCommand
                    ("UPDATE " . $con->dbname . ".inscripcion_posgrado
                        SET ipos_cedula=:ipos_cedula,
                        uaca_id=:uaca_id,
                        eaca_id=:eaca_id,
                        mod_id=:mod_id,
                        ipos_anio=:ipos_anio,
                        ipos_tipo_financiamiento=:ipos_tipo_finaciamiento,
                        $imagenes
                        ipos_fecha_modificacion=:ipos_fecha_modificacion
                        WHERE per_id =:per_id");

            $command->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $command->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
            $command->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
            $command->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
            $command->bindParam(":ipos_cedula", $ipos_cedula, \PDO::PARAM_STR);
            $command->bindParam(":ipos_anio", $ipos_año, \PDO::PARAM_STR);
            //$command->bindParam(":ipos_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
            $command->bindParam(":ipos_tipo_finaciamiento", $ipos_tipo_finaciamiento, \PDO::PARAM_STR);
                // si vienen nulos no agragrlos
                if(!empty($ipos_ruta_documento)){
                $command->bindParam(":ipos_ruta_documento", $ipos_ruta_documento, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_foto)){
                $command->bindParam(":ipos_ruta_doc_foto", $ipos_ruta_doc_foto, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_dni)){
                $command->bindParam(":ipos_ruta_doc_dni", $ipos_ruta_doc_dni, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_certvota)){
                $command->bindParam(":ipos_ruta_doc_certvota", $ipos_ruta_doc_certvota, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_titulo)){
                $command->bindParam(":ipos_ruta_doc_titulo", $ipos_ruta_doc_titulo, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_comprobantepago)){
                $command->bindParam(":ipos_ruta_doc_comprobantepago", $ipos_ruta_doc_comprobantepago, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_recordacademico)){
                    $command->bindParam(":ipos_ruta_doc_recordacademico", $ipos_ruta_doc_recordacademico, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_senescyt)){
                    $command->bindParam(":ipos_ruta_doc_senescyt", $ipos_ruta_doc_senescyt, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_hojadevida)){
                    $command->bindParam(":ipos_ruta_doc_hojadevida", $ipos_ruta_doc_hojadevida, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_cartarecomendacion)){
                    $command->bindParam(":ipos_ruta_doc_cartarecomendacion", $ipos_ruta_doc_cartarecomendacion, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_certificadolaboral)){
                    $command->bindParam(":ipos_ruta_doc_certificadolaboral", $ipos_ruta_doc_certificadolaboral, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_certificadoingles)){
                    $command->bindParam(":ipos_ruta_doc_certificadoingles", $ipos_ruta_doc_certificadoingles, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_otrorecord)){
                    $command->bindParam(":ipos_ruta_doc_otrorecord", $ipos_ruta_doc_otrorecord, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_certificadonosancion)){
                    $command->bindParam(":ipos_ruta_doc_certificadonosancion", $ipos_ruta_doc_certificadonosancion, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_syllabus)){
                    $command->bindParam(":ipos_ruta_doc_syllabus", $ipos_ruta_doc_syllabus, \PDO::PARAM_STR);
                }
                if(!empty($ipos_ruta_doc_homologacion)){
                    $command->bindParam(":ipos_ruta_doc_homologacion", $ipos_ruta_doc_homologacion, \PDO::PARAM_STR);
                }
                if(!empty($ipos_mensaje1)){
                    $command->bindParam(":ipos_mensaje1", $ipos_mensaje1, \PDO::PARAM_STR);
                }
                if(!empty($ipos_mensaje2)){
                    $command->bindParam(":ipos_mensaje2", $ipos_mensaje2, \PDO::PARAM_STR);
                }
            // si vienen nulos no agragarlos
            $command->bindParam(":ipos_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
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

    public function consultarDatosInscripcionposgrado($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;
        $sql = "
                SELECT
                         count(*) as existe_inscripcionposgrado
                FROM " . $con->dbname . ".inscripcion_posgrado
                WHERE per_id = :per_id AND
                     ipos_estado = :estado AND
                     ipos_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarDatosInscripcionContinuaposgrado($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $con3 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_asgard;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        //$estado_precio = 'A';

        $sql = "
                SELECT  ua.uaca_nombre unidad,
                        m.mod_nombre modalidad,
                        ea.eaca_nombre programa,
                        ea.eaca_id as id_carrera,
                        mi.ming_nombre metodo,
                        ipos.uaca_id,
                        ipos.mod_id,
                        ipos.eaca_id,
                        ipos.ipos_anio,
                        ipos_cedula,
                        ipos_tipo_financiamiento,
                        ipos_metodo_ingreso,
                        ipos_ruta_doc_foto,
                        ipos_ruta_doc_dni,
                        ipos_ruta_doc_certvota,
                        ipos_ruta_doc_titulo,
                        ipos_ruta_doc_comprobantepago,
                        ipos_ruta_doc_recordacademico,
                        ipos_ruta_doc_senescyt,
                        ipos_ruta_doc_hojadevida,
                        ipos_ruta_doc_cartarecomendacion,
                        ipos_ruta_doc_certificadolaboral,
                        ipos_ruta_doc_certificadoingles,
                        ipos_ruta_doc_otrorecord
                        ipos_ruta_doc_certificadonosancion,
                        ipos_ruta_doc_syllabus,
                        ipos_ruta_doc_homologacion
                FROM " . $con->dbname . ".inscripcion_posgrado ipos
                     inner join " . $con1->dbname . ".unidad_academica ua on ua.uaca_id = ipos.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = ipos.mod_id
                     inner join " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = ipos.eaca_id
                     left join " . $con3->dbname . ".metodo_ingreso mi on mi.ming_id = ipos.ipos_metodo_ingreso
                WHERE ipos.per_id = :per_id AND
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultaRegistroAdmisionposgrado($arrFiltro = array(), $reporte){
        $con_inscripcion = \Yii::$app->db_inscripcion;
        $con_asgard = \Yii::$app->db_asgard;
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            $str_search .= "per.per_seg_apellido like :search OR ";
            $str_search .= "ipos.ipos_cedula like :search) AND ";

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "ipos.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['programa'] != "" && $arrFiltro['programa'] > 0) {
                $str_search .= "ipos.eaca_id = :programa AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "ipos.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['año'] != "" && $arrFiltro['año'] > 0) {
                $str_search .= "ipos.ipos_anio = :año AND ";
            }
        }

        $sql = "SELECT distinct per.per_id as per_id,
                per.per_cedula as Cedula,
                ifnull(CONCAT(ifnull(per.per_pri_apellido,''), ' ', ifnull(per.per_seg_apellido,''), ' ', ifnull(per.per_pri_nombre,''), ' ', ifnull(per.per_seg_nombre,'')), '') as estudiante,
                ipos.ipos_anio as año,
                eaca.eaca_nombre as programa,
                moda.mod_nombre as modalidad
                FROM " . $con_inscripcion->dbname . ".inscripcion_posgrado as ipos
                Inner Join " . $con_asgard->dbname . ".persona as per on per.per_cedula = ipos.ipos_cedula
                Inner Join " . $con_academico->dbname . ".unidad_academica as uaca on uaca.uaca_id = ipos.uaca_id
                Inner Join " . $con_academico->dbname . ".estudio_academico as eaca on eaca.eaca_id = ipos.eaca_id
                Inner Join " . $con_academico->dbname . ".modalidad as moda on moda.mod_id = ipos.mod_id
                WHERE $str_search
                ipos.ipos_estado = :estado and ipos.ipos_estado_logico = :estado and
                per.per_estado = :estado and per.per_estado_logico = :estado and
                uaca.uaca_estado = :estado and uaca.uaca_estado_logico = :estado and
                eaca.eaca_estado = :estado and eaca.eaca_estado_logico = :estado and
                moda.mod_estado = :estado and moda.mod_estado_logico = :estado";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);

            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $search_uni = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $search_uni, \PDO::PARAM_INT);
            }
            if ($arrFiltro['programa'] != "" && $arrFiltro['programa'] > 0) {
                $search_pro = $arrFiltro["programa"];
                $comando->bindParam(":programa", $search_pro, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $search_mod = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $search_mod, \PDO::PARAM_INT);
            }
            if ($arrFiltro['año'] != "" && $arrFiltro['año'] > 0) {
                $search_año = $arrFiltro["año"];
                $comando->bindParam(":año", $search_año, \PDO::PARAM_STR);
            }
        }
        $res = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Cedula', 'estudiante',"año","programa","modalidad"],
            ],
        ]);

        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $res;
        }
    }

    public function ObtenerdocumentosInscripcionPosgrado($per_id) {
        //\app\models\Utilities::putMessageLogFile('ver el resultado del id:  '.$per_id);
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "SELECT  per_id,
                ipos_ruta_documento,
                ipos_ruta_doc_foto,
                ipos_ruta_doc_dni,
                ipos_ruta_doc_certvota,
                ipos_ruta_doc_titulo,
                ipos_ruta_doc_comprobantepago,
                ipos_ruta_doc_recordacademico,
                ipos_ruta_doc_senescyt,
                ipos_ruta_doc_hojadevida,
                ipos_ruta_doc_cartarecomendacion,
                ipos_ruta_doc_certificadolaboral,
                ipos_ruta_doc_certificadoingles,
                ipos_ruta_doc_otrorecord,
                ipos_ruta_doc_certificadonosancion,
                ipos_ruta_doc_syllabus,
                ipos_ruta_doc_homologacion
                FROM " . $con->dbname . ".inscripcion_posgrado
                WHERE per_id = :per_id AND
                      ipos_estado = :estado AND
                      ipos_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }


    public function consultarPdf($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;$uaca = 2;
        \app\models\Utilities::putMessageLogFile('entro con per_id : ' .$per_id);
        $sql = "
                SELECT distinct
                ipos.ipos_fecha_creacion as registro,
                ipos.ipos_anio as anio,
                eaca.eaca_nombre as programa,
                moda.mod_nombre as modalidad,
                ipos.ipos_ruta_doc_foto,
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
                mallagen.maca_nombre,
                ifnull(estud.est_categoria,'No definida') as categoria
                FROM db_inscripcion.inscripcion_posgrado as ipos
                Inner Join db_asgard.persona as per on per.per_id = ipos.per_id
                Inner Join db_asgard.pais as pais on pais.pai_id = per.per_nacionalidad
                Inner Join db_asgard.estado_civil as esta on esta.eciv_id = per.eciv_id
                Inner join db_academico.estudiante as estud on per.per_id = estud.per_id
                Inner Join db_academico.unidad_academica as uaca on uaca.uaca_id = ipos.uaca_id
                Inner Join db_academico.estudio_academico as eaca on eaca.eaca_id = ipos.eaca_id
                Inner Join db_academico.modalidad as moda on moda.mod_id = ipos.mod_id
                Inner Join db_asgard.persona_contacto as contac on contac.per_id = ipos.per_id
                Inner Join db_asgard.tipo_parentesco as parente on parente.tpar_id = contac.tpar_id
                Inner Join db_academico.malla_academico_estudiante as mallaes ON mallaes.per_id =  ipos.per_id
                Inner Join db_academico.malla_academica as mallagen ON mallagen.maca_id =  mallaes.maca_id
                WHERE
                ipos.uaca_id = :uaca_id AND
                ipos.per_id = :per_id AND
                ipos.ipos_estado = :estado and ipos.ipos_estado_logico = :estado and
                per.per_estado = :estado and per.per_estado_logico = :estado and
                uaca.uaca_estado = :estado and uaca.uaca_estado_logico = :estado and
                eaca.eaca_estado = :estado and eaca.eaca_estado_logico = :estado and
                moda.mod_estado = :estado and moda.mod_estado_logico = :estado
               ";


        \app\models\Utilities::putMessageLogFile('resultado del query: '.$comando->getRawSql());
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}
