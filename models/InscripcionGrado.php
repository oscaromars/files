<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inscripcion_grado".
 *
 * @property int $igra_id
 * @property int $per_id
 * @property int $uaca_id
 * @property int $eaca_id
 * @property int $mod_id
 * @property int $paca_id
 * @property int $igra_metodo_ingreso
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
 * @property string $igra_tipo_pago
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
            [['per_id', 'uaca_id', 'eaca_id', 'mod_id', 'paca_id', 'igra_estado', 'igra_estado_logico'], 'required'],
            [['per_id', 'uaca_id', 'eaca_id', 'mod_id', 'paca_id', 'igra_metodo_ingreso'], 'integer'],
            [['igra_fecha_creacion', 'igra_fecha_modificacion'], 'safe'],
            [['igra_ruta_doc_titulo', 'igra_ruta_doc_dni', 'igra_ruta_doc_certvota', 'igra_ruta_doc_foto', 'igra_ruta_doc_comprobantepago', 'igra_ruta_doc_recordacademico', 'igra_ruta_doc_certificado', 'igra_ruta_doc_syllabus', 'igra_ruta_doc_homologacion'], 'string', 'max' => 200],
            [['igra_mensaje1', 'igra_tipo_pago', 'igra_mensaje2', 'igra_estado', 'igra_estado_logico'], 'string', 'max' => 1],
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
            'igra_metodo_ingreso' => 'Igra Metodo Ingreso',
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
            'igra_tipo_pago' => 'Igra Tipo Pago',
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
    public static function addLabelTimeDocumentos($sins_id, $file, $timeSt) {
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

    public function insertarInscripciongrado($data, $per_id) {
        $arroout = array();
        $con = \Yii::$app->db_inscripcion;
        $trans = $con->beginTransaction();
        try {
            \app\models\Utilities::putMessageLogFile('archivo cargados:' . $data["DATA_1"]);
            $igra_id = $this->insertarDataInscripciongrado($con, $data["DATA_1"], $per_id);
            if (empty($data['opcion'])) {
                $data = $this->consultarDatosInscripciongrado($igra_id);
            } else {
                $data = $this->consultarDatosInscripcionContinuagrado($igra_id);
            }
            $trans->commit();
            //RETORNA DATOS 
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["ids"] = $igra_id;
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

    private function insertarDataInscripciongrado($con, $data, $per_id) {
        \app\models\Utilities::putMessageLogFile('datos de archivo cargados:' . $data);
        \app\models\Utilities::putMessageLogFile('id de persona:' . $data);
        $igra_ruta_doc_titulo = '';
        $igra_ruta_doc_dni = '';
        $igra_ruta_doc_certvota = '';
        $igra_ruta_doc_foto = '';
        $igra_ruta_doc_comprobantepago = '';
        $igra_ruta_doc_record = '';
        $igra_ruta_doc_certificado = '';
        $igra_ruta_doc_syllabus = '';
        $igra_ruta_doc_homologacion = '';
        $igra_mensaje1 = 0;
        $igra_mensaje2 = 0;

        $sql = "INSERT INTO " . $con->dbname . ".inscripcion_grado
            (per_id, uaca_id, eaca_id, mod_id, paca_id, igra_metodo_ingreso, igra_ruta_doc_titulo, igra_ruta_doc_dni, igra_ruta_doc_certvota, igra_ruta_doc_foto, igra_ruta_doc_comprobantepago, igra_ruta_doc_recordacademico, igra_ruta_doc_certificado, igra_ruta_doc_syllabus, igra_ruta_doc_homologacion, igra_mensaje1, igra_mensaje2, igra_estado, igra_fecha_modificacion, igra_estado_logico)VALUES
            (:per_id, :uaca_id, :eaca_id, :mod_id, :paca_id, :igra_metodo_ingreso, :igra_ruta_doc_titulo, :igra_ruta_doc_dni, :igra_ruta_doc_certvota, :igra_ruta_doc_foto, :igra_ruta_doc_comprobantepago, :igra_ruta_doc_record, :igra_ruta_doc_certificado, :igra_ruta_doc_syllabus, :igra_ruta_doc_homologacion, :igra_mensaje1, :igra_mensaje2, 1, CURRENT_TIMESTAMP(), 1)";

        $met_ing = 0;
        if (empty($data[0]['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data[0]['ming_id'];
        }
        \app\models\Utilities::putMessageLogFile('identificacion:' . $data[0]['cedula']);
        $command = $con->createCommand($sql);
        $command->bindParam(":per_id", $data[0]['per_id'], \PDO::PARAM_INT);
        $command->bindParam(":uaca_id", $data[0]['unidad'], \PDO::PARAM_STR);
        $command->bindParam(":eaca_id", $data[0]['carrera'], \PDO::PARAM_STR);
        $command->bindParam(":mod_id", $data[0]['modalidad'], \PDO::PARAM_STR);
        $command->bindParam(":paca_id", $data[0]['periodo'], \PDO::PARAM_STR);
        $command->bindParam(":igra_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":igra_ruta_doc_titulo", $igra_ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_dni", $igra_ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_certvota", $igra_ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_foto", $igra_ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_comprobantepago", $igra_ruta_doc_comprobantepago, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_record", $igra_ruta_doc_recordacademico, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_certificado", $igra_ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_syllabus", $igra_ruta_doc_syllabus, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_homologacion", $igra_ruta_doc_homologacion, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje1", $igra_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje2", $igra_mensaje2, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    private function updateDataInscripciongrado($con, $data) {
        $sql = "UPDATE " . $con->dbname . ".inscripcion_grado 
                SET per_id=:per_id,uaca_id=:uaca_id,eaca_id=:eaca_id,mod_id=:mod_id,paca_id=:paca_id,
                    igra_metodo_ingreso=:igra_metodo_ingreso,igra_ruta_doc_titulo=:igra_ruta_doc_titulo, igra_ruta_doc_dni=:igra_ruta_doc_dni, igra_ruta_doc_certvota=:igra_ruta_doc_certvota,igra_ruta_doc_foto=:igra_ruta_doc_foto,igra_ruta_doc_comprobantepago=:igra_ruta_doc_comprobantepago,igra_ruta_doc_recordacademico=:igra_ruta_doc_record,igra_ruta_doc_certificado=:igra_ruta_doc_certificado,igra_ruta_doc_syllabus=:igra_ruta_doc_syllabus,igra_ruta_doc_homologacion=:igra_ruta_doc_homologacion,igra_mensaje1=:igra_mensaje1,igra_mensaje2=:igra_mensaje2,igra_fecha_modificacion=CURRENT_TIMESTAMP() 
                 WHERE igra_id =:igra_id ";
        $met_ing = 0;
        if (empty($data[0]['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data[0]['ming_id'];
        }
        $command = $con->createCommand($sql);
        $command->bindParam(":igra_id", $data[0]['igra_id'], \PDO::PARAM_STR);
        $command->bindParam(":uaca_id", $data[0]['unidad_academica'], \PDO::PARAM_STR);
        $command->bindParam(":eaca_id", $data[0]['carrera'], \PDO::PARAM_STR);
        $command->bindParam(":mod_id", $data[0]['modalidad'], \PDO::PARAM_STR);
        $command->bindParam(":paca_id", $data[0]['periodo'], \PDO::PARAM_STR);
        $command->bindParam(":igra_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":igra_ruta_doc_titulo", $igra_ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_dni", $igra_ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_certvota", $igra_ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_foto", $igra_ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_comprobantepago", $igra_ruta_doc_comprobantepago, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_record", $igra_ruta_doc_recordacademico, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_certificado", $igra_ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_syllabus", $igra_ruta_doc_syllabus, \PDO::PARAM_STR);
        $command->bindParam(":igra_ruta_doc_homologacion", $igra_ruta_doc_homologacion, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje1", $igra_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje2", $igra_mensaje2, \PDO::PARAM_STR);
        $command->execute();

        return $data[0]['igra_id'];
    }


    public function consultarDatosInscripciongrado($igra_id) {
        $con = \Yii::$app->db_inscripcion;
        $con3 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_asgard;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        //$estado_precio = 'A';

        $sql = "
                SELECT  igra.per_id persona,
                        ua.uaca_nombre unidad, 
                        m.mod_nombre modalidad,
                        ea.eaca_nombre carrera,
                        ea.eaca_id as id_carrera,
                        mi.ming_nombre metodo,
                        igra.uaca_id,
                        igra.mod_id,
                        igra.eaca_id,
                        igra_metodo_ingreso,
                        igra_ruta_doc_titulo,
                        igra_ruta_doc_dni,
                        igra_ruta_doc_certvota,
                        igra_ruta_doc_foto,  
                        igra_ruta_doc_comprobantepago,
                        igra_ruta_doc_recordacademico, 
                        igra_ruta_doc_certificado, 
                        igra_ruta_doc_syllabus, 
                        igra_ruta_doc_homologacion
                FROM " . $con->dbname . ".inscripcion_grado igra 
                     inner join " . $con2->dbname . ".persona per on per.per_id = igra.per_id
                     inner join " . $con1->dbname . ".unidad_academica ua on ua.uaca_id = igra.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = igra.mod_id
                     inner join " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = igra.eaca_id
                     left join " . $con3->dbname . ".metodo_ingreso mi on mi.ming_id = igra.igra_metodo_ingreso
                WHERE igra.igra_id = :igra_id AND  
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":igra_id", $igra_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertarInscripcion($data) {
        $con = \Yii::$app->db_asgard;
        $con1 = \Yii::$app->db_inscripcion;
        $usuario_ingreso = @Yii::$app->session->get("PB_iduser");
        $trans = $con->beginTransaction();
        $trans1 = $con1->beginTransaction();
        try {
            $identificacion = $data[0]['cedula'];
            if (isset($identificacion) && strlen($identificacion) > 0) {
                    $id_persona = 0;
                    $mod_persona = new Persona();
                    $keys_per = [
                        'per_pri_nombre', 'per_seg_nombre', 'per_pri_apellido', 'per_seg_apellido',
                        'per_cedula', 'etn_id', 'eciv_id', 'per_genero', 'pai_id_nacimiento',
                        'pro_id_nacimiento', 'can_id_nacimiento', 'per_fecha_nacimiento',
                        'per_celular', 'per_correo', 'tsan_id', 'per_domicilio_sector',
                        'per_domicilio_cpri', 'per_domicilio_csec', 'per_domicilio_num',
                        'per_domicilio_ref', 'per_domicilio_telefono', 'per_trabajo_nombre', 'pai_id_domicilio',
                        'pro_id_domicilio', 'can_id_domicilio', 'per_nac_ecuatoriano',
                        'per_nacionalidad', 'per_foto', 'per_usuario_ingresa', 'per_estado', 'per_estado_logico'
                    ];
                    /*$parametros_per = [
                        ucwords(strtolower($resp_datos['twin_nombre'])), null,
                        ucwords(strtolower($resp_datos['twin_apellido'])), null,
                        $resp_datos['twin_numero'], null, null, null, null, null,
                        null, null, $resp_datos['twin_celular'], $resp_datos['twin_correo'],
                        null, null, null, null,
                        null, null, null,$resp_datos['twin_empresa'],
                        null, null, null,
                        null, null, null, $usuario_ingreso, 1, 1
                    ];*/
                    $id_persona = $mod_persona->consultarIdPersona($data[0]['cedula'], $data[0]['cedula'], $data[0]['correo'], $data[0]['celular']);
                    if ($id_persona == 0) {
                        $id_persona = $mod_persona->insertarPersona($con, $parametros_per, $keys_per, 'persona');
                    }
                    if ($id_persona > 0) {
                        \app\models\Utilities::putMessageLogFile('se crea persona.');
                        //Modifificaion para Mover Imagenes de temp a Persona
                        //self::movePersonFiles($twinIds,$id_persona);
                        $concap = \Yii::$app->db_captacion;
                        $mod_emp_persona = new EmpresaPersona();
                        if (!empty($dataReg["empresa"])) {
                            $emp_id = $dataReg["empresa"];
                        } else {
                            $emp_id = 1;
                        }
                        $keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
                        $parametros = [$emp_id, $id_persona, 1, 1];
                        $emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($id_persona, $emp_id);
                        if ($emp_per_id == 0) {
                            $emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con, $parametros, $keys, 'empresa_persona');
                        }
                        if ($emp_per_id > 0) {
                            $usuario = new Usuario();
                            $usuario_id = $usuario->consultarIdUsuario($id_persona, $resp_datos['twin_correo']);
                            if ($usuario_id == 0) {
                                $security = new Security();
                                $hash = $security->generateRandomString();
                                $passencrypt = base64_encode($security->encryptByPassword($hash, $resp_datos['twin_numero']));
                                $keys = ['per_id', 'usu_user', 'usu_sha', 'usu_password', 'usu_estado', 'usu_estado_logico'];
                                $parametros = [$id_persona, $resp_datos['twin_correo'], $hash, $passencrypt, 1, 1];
                                $usuario_id = $usuario->crearUsuarioTemporal($con, $parametros, $keys, 'usuario');
                            }
                            if ($usuario_id > 0) {
                                $mod_us_gr_ep = new UsuaGrolEper();
                                $grol_id = 30;
                                $keys = ['eper_id', 'usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'];
                                $parametros = [$emp_per_id, $usuario_id, $grol_id, 1, 1];
                                $us_gr_ep_id = $mod_us_gr_ep->consultarIdUsuaGrolEper($emp_per_id, $usuario_id, $grol_id);
                                if ($us_gr_ep_id == 0)
                                    $us_gr_ep_id = $mod_us_gr_ep->insertarUsuaGrolEper($con, $parametros, $keys, 'usua_grol_eper');
                                if ($us_gr_ep_id > 0) {
                                    $mod_interesado = new Interesado(); // se guarda con estado_interesado 1
                                    $interesado_id = $mod_interesado->consultaInteresadoById($id_persona);
                                    $keys = ['per_id', 'int_estado_interesado', 'int_usuario_ingreso', 'int_estado', 'int_estado_logico'];
                                    $parametros = [$id_persona, 1, $usuario_id, 1, 1];
                                    if ($interesado_id == 0) {
                                        $interesado_id = $mod_interesado->insertarInteresado($concap, $parametros, $keys, 'interesado');
                                    }
                                    if ($interesado_id > 0) {
                                        $mod_inte_emp = new InteresadoEmpresa(); // se guarda con estado_interesado 1
                                        $iemp_id = $mod_inte_emp->consultaInteresadoEmpresaById($interesado_id, $emp_id);
                                        if ($iemp_id == 0) {
                                            $iemp_id = $mod_inte_emp->crearInteresadoEmpresa($interesado_id, $emp_id, $usuario_id);
                                        }
                                        if ($iemp_id > 0) {
                                            $eaca_id = NULL;
                                            $mest_id = NULL;
                                            if ($emp_id == 1) {//Uteg 
                                                $eaca_id = $resp_datos['car_id'];
                                            } elseif ($emp_id == 2 || $emp_id == 3) {
                                                $mest_id = $resp_datos['car_id'];
                                            }
                                            $num_secuencia = Secuencias::nuevaSecuencia($con, $emp_id, 1, 1, 'SOL');
                                            $sins_fechasol = date(Yii::$app->params["dateTimeByDefault"]);
                                            $rsin_id = 1; //Solicitud pendiente     
                                            $solins_model = new SolicitudInscripcion();
                                            //$mensaje = 'intId: ' . $interesado_id . '/uaca: ' . $pgest['unidad_academica'] . '/modalidad: ' . $pgest['modalidad'] . '/ming: ' . $pgest['ming_id'] . '/eaca: ' . $eaca_id . '/mest: ' . $mest_id . '/empresa: ' . $emp_id . '/secuencia: ' . $num_secuencia . '/rsin_id: ' . $rsin_id . '/sins_fechasol: ' . $sins_fechasol . '/usuario_id: ' . $usuario_id;
                                            $ming = null;
                                            if ($resp_datos['uaca_id'] == 1) {
                                                $ming = null;
                                            } else {
                                                if ($resp_datos['twin_metodo_ingreso'] == 0) {
                                                    $ming = null;
                                                } else {
                                                    $ming = $resp_datos['twin_metodo_ingreso'];
                                                }
                                            }
                                            if ($resp_datos['cemp_id'] == 0) {
                                                $cemp = null;
                                            } else {
                                                $cemp = $resp_datos['cemp_id'];
                                            }
                                            $sins_id = $solins_model->insertarSolicitud($interesado_id, $resp_datos['uaca_id'], $resp_datos['mod_id'], $resp_datos['twin_metodo_ingreso'], $eaca_id, $mest_id, $emp_id, $num_secuencia, $rsin_id, $sins_fechasol, $usuario_id, $cemp);
                                            //grabar los documentos                                            
                                            if ($sins_id) {
                                                if (($resp_datos['ruta_doc_titulo'] != "") || ($resp_datos['ruta_doc_dni'] != "") || ($resp_datos['ruta_doc_certvota'] != "") || ($resp_datos['ruta_doc_foto'] != "") || ($resp_datos['ruta_doc_certificado'] != "") || ($resp_datos['ruta_doc_hojavida'] != "")) {
                                                    $subidaDocumentos = 1;
                                                } else {
                                                    $subidaDocumentos = 0;
                                                }
                                                if ($resp_datos['ruta_doc_titulo'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_titulo']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_titulo']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaTitulo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_titulo_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc1 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 1, $rutaTitulo, $usuario_id);
                                                    /* if (!($resulDoc1)) {
                                                      throw new Exception('Error doc Titulo no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['ruta_doc_dni'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_dni']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_dni']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaDni = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_dni_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc2 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 2, $rutaDni, $usuario_id);
                                                    /* if (!($resulDoc2)) {
                                                      throw new Exception('Error doc Titulo no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['ruta_doc_certvota'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_certvota']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_certvota']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaCertvota = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_certvota_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc3 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 3, $rutaCertvota, $usuario_id);
                                                    /* if (!($resulDoc3)) {
                                                      throw new Exception('Error doc Cert.Votación no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['ruta_doc_foto'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_foto']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_foto']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaFoto = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_foto_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc4 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 4, $rutaFoto, $usuario_id);
                                                    /* if (!($resulDoc4)) {
                                                      throw new Exception('Error doc Foto no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['twin_metodo_ingreso'] == 4) {
                                                    if ($resp_datos['ruta_doc_certificado'] != "") {
                                                        $arrIm = explode(".", basename($resp_datos['ruta_doc_certificado']));
                                                        $arrTime = explode("_", basename($resp_datos['ruta_doc_certificado']));
                                                        $timeSt = $arrTime[4];
                                                        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                        $rutaCertificado = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_certificado_per_" . $id_persona . "_" . $timeSt;
                                                        $resulDoc5 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 6, $rutaCertificado, $usuario_id);
                                                        /* if (!($resulDoc5)) {
                                                          throw new Exception('Error doc Certificado no creado.');
                                                          } */
                                                    }
                                                    if ($resp_datos['ruta_doc_hojavida'] != "") {
                                                        $arrIm = explode(".", basename($resp_datos['ruta_doc_hojavida']));
                                                        $arrTime = explode("_", basename($resp_datos['ruta_doc_hojavida']));
                                                        $timeSt = $arrTime[4];
                                                        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                        $rutaHojaVida = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_hojavida_per_" . $id_persona . "_" . $timeSt;
                                                        $resulDoc6 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 7, $rutaHojaVida, $usuario_id);
                                                        /* if (!($resulDoc6)) {
                                                          throw new Exception('Error doc Hoja de Vida no creado.');
                                                          } */
                                                    }
                                                }
                                                if ($resp_datos['ruta_doc_aceptacion'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_aceptacion']));
                                                    $arrTime = explode("_", basename($resp_datos['ruta_doc_aceptacion']));
                                                    $timeSt = $arrTime[4];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $rutaDocAceptacion = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $id_persona . "/doc_aceptacion_per_" . $id_persona . "_" . $timeSt;
                                                    $resulDoc7 = $solins_model->insertarDocumentosSolic($sins_id, $interesado_id, 8, $rutaDocAceptacion, $usuario_id);
                                                    /* if (!($resulDoc6)) {
                                                      throw new Exception('Error doc Hoja de Vida no creado.');
                                                      } */
                                                }
                                                if ($resp_datos['ruta_doc_pago'] != "") {
                                                    $arrIm = explode(".", basename($resp_datos['ruta_doc_pago']));
                                                    $arrTime = explode(" ", basename($resp_datos['ruta_doc_pago']));
                                                    $timeSt = $arrTime[1];
                                                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                                                    $fecha = date(Yii::$app->params["dateByDefault"]);
                                                    $rutaDocPago = Yii::$app->params["documentFolder"] . "documento/" . $id_persona . "/pago_" . $id_persona . "-" . $fecha . " " . $timeSt;
                                                    $archivo = basename($rutaDocPago);
                                                }
                                                //Obtener el precio de la solicitud.                                                
                                                if ($beca == "1") {
                                                    $precio = 0;
                                                } else {
                                                    $resp_precio = $solins_model->ObtenerPrecio($resp_datos['twin_metodo_ingreso'], $resp_datos['uaca_id'], $resp_datos['mod_id'], $eaca_id);
                                                    if ($resp_precio) {
                                                        /* if ($resp_datos['uaca_id'] == 2) {
                                                          $ite_id = 10;
                                                          } else { */
                                                        $ite_id = $resp_precio['ite_id'];
                                                        //}
                                                        $precio = $resp_precio['precio'];
                                                    } else {
                                                        $mensaje = 'No existe registrado ningún precio para la unidad, modalidad y método de ingreso seleccionada.';
                                                    }
                                                }
                                                $mod_ordenpago = new OrdenPago();
                                                $val_descuento = 0;
                                                //Se verifica si seleccionó descuento.
                                                //descuento para grado online y posgrado no tiene descuento, caso contrario es 96 dol
                                                /* if ($resp_datos['uaca_id'] == 1) {
                                                  if (($resp_datos['mod_id'] == 2) or ( $resp_datos['mod_id'] == 3) or ( $resp_datos['mod_id'] == 4)) {
                                                  $val_descuento = 96;
                                                  }
                                                  } */
                                                //Generar la orden de pago con valor correspondiente. Buscar precio para orden de pago.                                                                     
                                                if ($precio == 0) {
                                                    $estadopago = 'S';
                                                } else {
                                                    $estadopago = 'P';
                                                }
                                                $val_total = $precio - $val_descuento;
                                                $resp_opago = $mod_ordenpago->insertarOrdenpago($sins_id, null, $val_total, 0, $val_total, $estadopago, $usuario_id);
                                                if ($resp_opago) {
                                                    //insertar desglose del pago                                                         
                                                    $fecha_ini = date(Yii::$app->params["dateByDefault"]);
                                                    $resp_dpago = $mod_ordenpago->insertarDesglosepago($resp_opago, $ite_id, $val_total, 0, $val_total, $fecha_ini, null, $estadopago, $usuario_id);
                                                    if ($resp_dpago) {
                                                        //Grabar documento de registro de pago por depósito o transferencia.                                                        
                                                        if (($dataReg["forma_pago"] == 3) or ( $dataReg["forma_pago"] == 4)) { //(($resp_datos['twin_tipo_pago'] == 3) or ( $resp_datos['twin_tipo_pago'] == 4)) {
                                                            if ($dataReg["forma_pago"] == 3) { //depósito
                                                                $fpag_id = 5;   //depósito
                                                            } else {
                                                                $fpag_id = 4;  //transferencia
                                                            }
                                                            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
                                                            $creadetalle = $mod_ordenpago->insertarCargaprepago($resp_opago, $fpag_id, $val_total, $archivo, 'PE', '', $dataReg["observacion"], $dataReg["num_transaccion"], $dataReg["fecha_transaccion"], $fecha_registro);
                                                            if ($creadetalle) {
                                                                //\app\models\Utilities::putMessageLogFile('despues de insertar Cargar pago');
                                                                $detalle = 'S';
                                                            }
                                                        } else {
                                                            $detalle = 'S';
                                                        }
                                                        //Grabar datos de factura                                                                                                                   
                                                        if ($detalle == 'S') {
                                                            $resdatosFact = $solins_model->crearDatosFacturaSolicitud($sins_id, $dataReg["nombres_fact"], $dataReg["apellidos_fact"], $dataReg["tipo_dni_fac"], $dataReg["dni"], $dataReg["direccion_fact"], $dataReg["telefono_fac"], $dataReg["correo"]);
                                                            if ($resdatosFact) {
                                                                $exito = 1;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
                                            $error++;
                                        }
                                    } else {
                                        $error_message .= Yii::t("formulario", "The interested person hasn't been saved");
                                        $error++;
                                    }
                                } else {
                                    $error_message .= Yii::t("formulario", "The rol user have not been saved");
                                    $error++;
                                }
                            } else {
                                $error_message .= Yii::t("formulario", "The user have not been saved");
                                $error++;
                            }
                        } else {
                            $error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
                            $error++;
                        }
                    } else {
                        $error++;
                        $error_message .= Yii::t("formulario", "The person have not been saved");
                    }
                } else {
                    $error_message .= Yii::t("formulario", "Update DNI to generate interested");
                    $error++;
                }
            /*} else {
                $error_message .= Yii::t("formulario", "No existen datos para registrar.");
                $error++;
            }*/

            $igra_id = $this->insertarDataInscripciongrado($con, $data["DATA_1"], $per_id);
            if (empty($data['opcion'])) {
                $data = $this->consultarDatosInscripciongrado($igra_id);
            } else {
                $data = $this->consultarDatosInscripcionContinuagrado($igra_id);
            }
            $trans->commit();
            //RETORNA DATOS 
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["ids"] = $igra_id;
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
    }

    public function consultarDatosInscripcionContinuagrado($igra_id) {
        $con = \Yii::$app->db_inscripcion;
        $con3 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_asgard;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        //$estado_precio = 'A';

        $sql = "
                SELECT  igra.per_id persona,
                        ua.uaca_nombre unidad, 
                        m.mod_nombre modalidad,
                        ea.eaca_nombre carrera,
                        ea.eaca_id as id_carrera,
                        mi.ming_nombre metodo,
                        igra.uaca_id,
                        igra.mod_id,
                        igra.eaca_id,
                        igra_metodo_ingreso,
                        igra_ruta_doc_titulo,
                        igra_ruta_doc_dni,
                        igra_ruta_doc_certvota,
                        igra_ruta_doc_foto,  
                        igra_ruta_doc_comprobantepago,
                        igra_ruta_doc_recordacademico, 
                        igra_ruta_doc_certificado, 
                        igra_ruta_doc_syllabus, 
                        igra_ruta_doc_homologacion
                FROM " . $con->dbname . ".inscripcion_grado igra 
                     inner join " . $con2->dbname . ".persona per on per.per_id = igra.per_id
                     inner join " . $con1->dbname . ".unidad_academica ua on ua.uaca_id = igra.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = igra.mod_id
                     inner join " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = igra.eaca_id
                     left join " . $con3->dbname . ".metodo_ingreso mi on mi.ming_id = igra.igra_metodo_ingreso
                WHERE igra.igra_id = :igra_id AND  
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":igra_id", $igra_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}
