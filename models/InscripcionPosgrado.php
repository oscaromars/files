<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inscripcion_posgrado".
 *
 * @property int $ipos_id
 * @property int $per_id
 * @property int $uaca_id
 * @property int $eaca_id
 * @property int $mod_id
 * @property string $ipos_año
 * @property string $ipos_tipo_finaciamiento
 * @property int $ipos_metodo_ingreso
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
            [['ipos_año'], 'string', 'max' => 50],
            [['ipos_tipo_finaciamiento', 'ipos_ruta_doc_foto', 'ipos_ruta_doc_dni', 'ipos_ruta_doc_certvota', 'ipos_ruta_doc_titulo', 'ipos_ruta_doc_comprobantepago', 'ipos_ruta_doc_recordacademico', 'ipos_ruta_doc_senescyt', 'ipos_ruta_doc_hojadevida', 'ipos_ruta_doc_cartarecomendacion', 'ipos_ruta_doc_certificadolaboral', 'ipos_ruta_doc_certificadoingles', 'ipos_ruta_doc_otrorecord', 'ipos_ruta_doc_certificadonosancion', 'ipos_ruta_doc_syllabus', 'ipos_ruta_doc_homologacion'], 'string', 'max' => 200],
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
            'ipos_año' => 'Ipos Año',
            'ipos_tipo_finaciamiento' => 'Ipos Tipo Finaciamiento',
            'ipos_metodo_ingreso' => 'Ipos Metodo Ingreso',
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
        $replace = "_$timeSt" . ".$typeFile";
        $newFile = str_replace($search, $replace, $file);
        if (file_exists($baseFile . $file)) {
            if (rename($baseFile . $file, $baseFile . $newFile)) {
                return $newFile;
            }
        } else {
            return $newFile;
        }
    }

    /*public function insertarInscripcionposgrado($data, $per_id) {
        $arroout = array();
        $con = \Yii::$app->db_inscripcion;
        $trans = $con->beginTransaction();
        try {
            \app\models\Utilities::putMessageLogFile('archivo cargados:' . $data["DATA_1"]);
            $ipos_id = $this->insertarDataInscripcionposgrado($con, $data["DATA_1"], $per_id);
            if (empty($data['opcion'])) {
                $data = $this->consultarDatosInscripcionposgrado($ipos_id);
            } else {
                $data = $this->consultarDatosInscripcionContinuaposgrado($ipos_id);
            }
            $trans->commit();
            //RETORNA DATOS 
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["ids"] = $ipos_id;
            $arroout["data"] = $data; //$rawData;
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollback();

            $arroout["status"] = FALSE;
            $arroout["error"] = $e->getCode();
            $arroout["message"] = $e->getMessage();
            $arroout["data"] = null; //$rawData;
            return $arroout;
        }
    */

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

    public function insertarDataInscripcionposgrado($unidad, $programa, $modalidad, $año, $per_dni, $tipo_financiamiento, $data) {
        $con = \Yii::$app->db_inscripcion;
        \app\models\Utilities::putMessageLogFile('datos de archivo cargados:' . $data);
        \app\models\Utilities::putMessageLogFile('id de persona:' . $per_id);
        $ipos_ruta_doc_foto = $data['ipos_ruta_doc_foto'];
        $ipos_ruta_doc_dni = $data['ipos_ruta_doc_dni'];
        $ipos_ruta_doc_certvota = $data['ipos_ruta_doc_certvota'];
        $ipos_ruta_doc_titulo = $data['ipos_ruta_doc_titulo'];
        $ipos_ruta_doc_comprobante = $data['ipos_ruta_doc_comprobante'];
        $ipos_ruta_doc_record1 = $data['ipos_ruta_doc_record1'];
        $ipos_ruta_doc_senescyt = $data['ipos_ruta_doc_senescyt'];
        $ipos_ruta_doc_hojavida = $data['ipos_ruta_doc_hojavida'];
        $ipos_ruta_doc_cartarecomendacion = $data['ipos_ruta_doc_cartarecomendacion'];
        $ipos_ruta_doc_certificadolaboral = $data['ipos_ruta_doc_certificadolaboral'];
        $ipos_ruta_doc_certificadoingles = $data['ipos_ruta_doc_certificadoingles'];
        $ipos_ruta_doc_recordacademico = $data['ipos_ruta_doc_recordacademico'];
        $ipos_ruta_doc_certnosancion = $data['ipos_ruta_doc_certnosancion'];
        $ipos_ruta_doc_syllabus = $data['ipos_ruta_doc_syllabus'];
        $ipos_ruta_doc_homologacion = $data['ipos_ruta_doc_homologacion'];
        $ipos_mensaje1 = $data['ipos_mensaje1'];
        $ipos_mensaje2 = $data['ipos_mensaje2'];

        $sql = "INSERT INTO " . $con->dbname . ".inscripcion_posgrado
            (uaca_id, eaca_id, mod_id, ipos_año, ipos_cedula, ipos_tipo_financiamiento, ipos_metodo_ingreso, ipos_ruta_doc_foto, ipos_ruta_doc_dni, ipos_ruta_doc_certvota, ipos_ruta_doc_titulo, ipos_ruta_doc_comprobantepago, ipos_ruta_doc_recordacademico, ipos_ruta_doc_senescyt, ipos_ruta_doc_hojadevida, ipos_ruta_doc_cartarecomendacion, ipos_ruta_doc_certificadolaboral, ipos_ruta_doc_certificadoingles, ipos_ruta_doc_otrorecord, ipos_ruta_doc_certificadonosancion, ipos_ruta_doc_syllabus, ipos_ruta_doc_homologacion, ipos_mensaje1, ipos_mensaje2, ipos_estado, ipos_fecha_modificacion, ipos_estado_logico)VALUES
            (:uaca_id, :eaca_id, :mod_id, :ipos_año, :per_dni, :ipos_tipo_financiamiento, :ipos_metodo_ingreso, :ipos_ruta_doc_foto, :ipos_ruta_doc_dni, :ipos_ruta_doc_certvota, :ipos_ruta_doc_titulo, :ipos_ruta_doc_comprobante, :ipos_ruta_doc_record1, :ipos_ruta_doc_senescyt, :ipos_ruta_doc_hojadevida, :ipos_ruta_doc_cartarecomendacion, :ipos_ruta_doc_certificadolaboral, :ipos_ruta_doc_certificadoingles, :ipos_ruta_doc_recordacademico, :ipos_ruta_doc_certnosancion, :ipos_ruta_doc_syllabus, :ipos_ruta_doc_homologacion, :ipos_mensaje1, :ipos_mensaje2, 1, CURRENT_TIMESTAMP(), 1)";

        $met_ing = 0;
        if (empty($data['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data['ming_id'];
        }
        \app\models\Utilities::putMessageLogFile('identificacion:' . $data['cedula']);
        $command = $con->createCommand($sql);
        $command->bindParam(":uaca_id", $unidad, \PDO::PARAM_STR);
        $command->bindParam(":eaca_id", $programa, \PDO::PARAM_STR);
        $command->bindParam(":mod_id", $modalidad, \PDO::PARAM_STR);
        $command->bindParam(":ipos_año", $año, \PDO::PARAM_STR);
        $command->bindParam(":per_dni", $per_dni, \PDO::PARAM_STR);
        $command->bindParam(":ipos_tipo_financiamiento", $tipo_financiamiento, \PDO::PARAM_STR);
        $command->bindParam(":ipos_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":ipos_ruta_doc_foto", $ipos_ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_dni", $ipos_ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certvota", $ipos_ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_titulo", $ipos_ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_comprobante", $ipos_ruta_doc_comprobante, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_record1", $ipos_ruta_doc_record1, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_senescyt", $ipos_ruta_doc_senescyt, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_hojadevida", $ipos_ruta_doc_hojavida, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_cartarecomendacion", $ipos_ruta_doc_cartarecomendacion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadolaboral", $ipos_ruta_doc_certificadolaboral, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadoingles", $ipos_ruta_doc_certificadoingles, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_recordacademico", $ipos_ruta_doc_recordacademico, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certnosancion", $ipos_ruta_doc_certnosancion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_syllabus", $ipos_ruta_doc_syllabus, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_homologacion", $ipos_ruta_doc_homologacion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_mensaje1", $ipos_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":ipos_mensaje2", $ipos_mensaje2, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    public function updateDataInscripcionposgrado($con, $data) {
        $sql = "UPDATE " . $con->dbname . ".inscripcion_posgrado 
                SET uaca_id=:uaca_id,eaca_id=:eaca_id,mod_id=:mod_id,ipos_año=:ipos_año,ipos_cedula=:per_dni,ipos_tipo_finaciamiento=:ipos_tipo_finaciamiento,ipos_metodo_ingreso=:ipos_metodo_ingreso,ipos_ruta_doc_foto=:ipos_ruta_doc_foto, ipos_ruta_doc_dni=:ipos_ruta_doc_dni, ipos_ruta_doc_certvota=:ipos_ruta_doc_certvota,ipos_ruta_doc_titulo=:ipos_ruta_doc_titulo,ipos_ruta_doc_comprobantepago=:ipos_ruta_doc_comprobantepago,ipos_ruta_doc_recordacademico=:ipos_ruta_doc_recordacademico,ipos_ruta_doc_senescyt=:ipos_ruta_doc_senescyt, ipos_ruta_doc_hojadevida=:ipos_ruta_doc_hojadevida, ipos_ruta_doc_cartarecomendacion=:ipos_ruta_doc_cartarecomendacion, ipos_ruta_doc_certificadolaboral, ipos_ruta_doc_certificadoingles=:ipos_ruta_doc_certificadoingles, ipos_ruta_doc_otrorecord=:ipos_ruta_doc_otrorecord, ipos_ruta_doc_certificadonosancion=:ipos_ruta_doc_certificadonosancion,ipos_ruta_doc_syllabus=:ipos_ruta_doc_syllabus,ipos_ruta_doc_homologacion=:ipos_ruta_doc_homologacion,ipos_mensaje1=:ipos_mensaje1,ipos_mensaje2=:ipos_mensaje2,ipos_fecha_modificacion=CURRENT_TIMESTAMP() 
                 WHERE ipos_id =:ipos_id ";
        $met_ing = 0;
        if (empty($data['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data['ming_id'];
        }
        $command = $con->createCommand($sql);
        $command->bindParam(":uaca_id", $unidad, \PDO::PARAM_STR);
        $command->bindParam(":eaca_id", $carrera, \PDO::PARAM_STR);
        $command->bindParam(":mod_id", $modalidad, \PDO::PARAM_STR);
        $command->bindParam(":año", $año, \PDO::PARAM_STR);
        $command->bindParam(":per_dni", $per_dni, \PDO::PARAM_STR);
        $command->bindParam(":tipo_finaciamiento", $tipo_financiamiento, \PDO::PARAM_STR);
        $command->bindParam(":ipos_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":ipos_ruta_doc_foto", $ipos_ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_dni", $ipos_ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certvota", $ipos_ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_titulo", $ipos_ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_comprobantepago", $ipos_ruta_doc_comprobante, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_recordacademico", $ipos_ruta_doc_record1, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_senescyt", $ipos_ruta_doc_senescyt, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_hojadevida", $ipos_ruta_doc_hojavida, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_cartarecomendacion", $ipos_ruta_doc_cartarecomendacion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadolaboral", $ipos_ruta_doc_certificadolaboral, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadoingles", $ipos_ruta_doc_certificadoingles, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_otrorecord", $ipos_ruta_doc_recordacademico, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_certificadonosancion", $ipos_ruta_doc_certnosancion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_syllabus", $ipos_ruta_doc_syllabus, \PDO::PARAM_STR);
        $command->bindParam(":ipos_ruta_doc_homologacion", $ipos_ruta_doc_homologacion, \PDO::PARAM_STR);
        $command->bindParam(":ipos_mensaje1", $ipos_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":ipos_mensaje2", $ipos_mensaje2, \PDO::PARAM_STR);
        $command->execute();

        return $data['ipos_id'];
    }


    public function consultarDatosInscripcionposgrado($per_dni) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;
        //$estado_precio = 'A';

        $sql = "
                SELECT   
                         count(*) as existe_inscripcionposgrado
                FROM " . $con->dbname . ".inscripcion_posgrado 
                WHERE ipos_cedula = :per_dni AND 
                     ipos_estado = :estado AND
                     ipos_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_dni", $per_dni, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarDatosInscripcionContinuaposgrado($ipos_id) {
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
                        ipos.ipos_año,
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
                WHERE ipos.ipos_id = :ipos_id AND  
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ipos_id", $ipos_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}
