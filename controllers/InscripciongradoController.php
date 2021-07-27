<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Persona;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademico;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\models\EstadoCivil;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use app\modules\academico\models\MallaAcademica;
use app\models\InscripcionGrado;
use app\modules\admision\models\MetodoIngreso;
use app\modules\admision\models\ConvenioEmpresa;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\models\ExportFile;

academico::registerTranslations();

class InscripciongradoController extends \yii\web\Controller {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    public function actionIndex() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $per_id = Yii::$app->session->get("PB_perid");
        $mod_persona = Persona::findIdentity($per_id);
        $mod_unidad = new UnidadAcademica();
        $mod_carrera = new EstudioAcademico();
        $mod_modalidad = new Modalidad();
        $mod_periodo = new PeriodoAcademico();
        $mod_malla = new MallaAcademica();

        /*$mod_inscripciongrado = new InscripcionGrado();
        $data = Yii::$app->request->get();
        \app\models\Utilities::putMessageLogFile('obteniendo cedula: '.$data['per_cedula']);  
        $per_cedula = $data['per_cedula'];
        if($per_cedula !=0){
            $model = $mod_inscripciongrado->consultarper_cedula($per_cedula);
            return $this->render('index', [
                        "model" => $model,
            ])
        }else{
            $per_cedula=0;
        }*/

        $arr_unidad = $mod_unidad->consultarUnidadAcademicas();
        $arr_carrera = $mod_carrera->consultarCarreraxunidad($arr_unidad[0]['id']);
        $arr_modalidad = $mod_carrera->consultarmodalidadxcarrera($arr_carrera[0]['id']);
        $arr_periodo = $mod_periodo->consultarPeriodosActivos();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data['getmodalidad'])) {
                $modalidad = $mod_carrera->consultarmodalidadxcarrera($data['eaca_id']);
                $message = array('modalidad' => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            /*if (isset($data['getmalla'])) {
                $mallaca = $mod_malla->consultarmallasxcarrera($data['uaca_id'], $data['moda_id'], $data['eaca_id']);
                $message = array('mallaca' => $mallaca);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }*/
        }
        /*if (base64_decode($_GET['ids']) != '') {// tomar el de parametro)
            $per_id = base64_decode($_GET['ids']);
        } else {
            $per_id = Yii::$app->session->get("PB_perid");
        }*/
        //Búsqueda de los datos de persona logueada
        //$mod_persona = new Persona();
        //\app\models\Utilities::putMessageLogFile('obteniendo per_id: '.$model[0]['per_id']); 
        //$respPersona = $mod_persona->consultaPersonaId($model[0]['per_id']);

        $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_provincia = Provincia::provinciaXPais($arr_nacionalidad[0]["id"]);
        $arr_ciudad= Canton::cantonXProvincia($arr_provincia[0]["id"]);
        //$arr_malla = $mod_malla->consultarmallasxcarrera($arr_unidad[0]['id'], $arr_carrera[0]['id'], $arr_modalidad[0]['id']);
        $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $mod_metodo = new MetodoIngreso();
        $arr_metodos = $mod_metodo->consultarMetodoUnidadAca_2($arr_unidad[0]["id"]);
        $mod_conempresa = new ConvenioEmpresa();
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');

        return $this->render('index', [
            "arr_unidad" => ArrayHelper::map($arr_unidad, "id", "name"),
            'arr_carrera' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_carrera), 'id', 'name'),
            'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_modalidad), 'id', 'name'),
            'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'paca_nombre' => 'Seleccionar']], $arr_periodo), 'id', 'paca_nombre'),
            "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
            "tipos_dni2" => array("CED" => Yii::t("formulario", "DNI Document1"), "PASS" => Yii::t("formulario", "Passport1")),
            "arr_nacionalidad" => ArrayHelper::map($arr_nacionalidad, "id", "value"),
            "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
            "arr_pais" => ArrayHelper::map($arr_pais, "id", "value"),
            "arr_provincia" => ArrayHelper::map($arr_provincia, "id", "value"),
            "arr_ciudad" => ArrayHelper::map($arr_ciudad, "id", "value"),
            //'arr_malla' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_malla), 'id', 'name'),
            "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
            "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
            "arr_convenio_empresa" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Ninguna")]], $arr_convempresa), "id", "name"),
            //"model" => $model,
            "resp_datos" => $resp_datos,
        ]);
    }

    public function actionGuardarinscripciongrado() {
        $mod_persona = new Persona();
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            //$accion = isset($data['ACCION']) ? $data['ACCION'] : "";
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            /*if ($_SESSION['persona_ingresa'] != '') {// tomar el de parametro)
                $per_id = $_SESSION['persona_ingresa'];
            } else {
                unset($_SESSION['persona_ingresa']);
                $per_id = Yii::$app->session->get("PB_perid");
            }*/
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                //$inscripgrado_id = $data["inscripgrado_id"];
                $per_dni = $data['cedula'];
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'pdf' || $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg') {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/" . $data["name_file"] . "_per_" . $per_dni . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        //return;
                    }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes pdf, jpg, png.")]);
                }
            } 
        
            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();
            $con1 = \Yii::$app->db_captacion;
            $transaction1 = $con1->beginTransaction();
          
            $timeSt = time();
            try {

                
                \app\models\Utilities::putMessageLogFile(' unidad:  '.$data['unidad']);
                $unidad = $data['unidad'];
                $carrera = $data['carrera'];
                $modalidad = $data['modalidad'];
                $periodo = $data['periodo'];
                $ming_id = $data['ming_id'];

                \app\models\Utilities::putMessageLogFile(' inscripcion grado:  '.$data["igra_id"]);
                $per_id = Yii::$app->session->get("PB_perid");
                $per_dni = $data['cedula'];
                $inscripgrado_id = $data["igra_id"];
                if (isset($data["igra_ruta_doc_titulo"]) && $data["igra_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_titulo_per_" . $per_dni . "." . $typeFile;
                    $titulo_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_archivoOld, $timeSt);
                    $data["igra_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["igra_ruta_doc_dni"]) && $data["igra_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_dni_per_" . $per_dni . "." . $typeFile;
                    $dni_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $dni_archivoOld, $timeSt);
                    $data["igra_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certvota"]) && $data["igra_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_certvota_per_" . $per_dni . "." . $typeFile;
                    $certvota_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certvota_archivoOld, $timeSt);
                    $data["igra_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["igra_ruta_doc_foto"]) && $data["igra_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_foto_per_" . $per_dni . "." . $typeFile;
                    $foto_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $foto_archivoOld, $timeSt);
                    $data["igra_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["igra_ruta_doc_comprobantepago"]) && $data["igra_ruta_doc_comprobantepago"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_comprobantepago"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_comprobantepago_per_" . $per_dni . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $comprobantepago_archivoOld, $timeSt);
                    $data["igra_ruta_doc_comprobantepago"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }
                
                if (isset($data["igra_ruta_doc_record"]) && $data["igra_ruta_doc_record"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_record"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_record_per_" . $per_dni . "." . $typeFile;
                    $record_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $record_archivoOld, $timeSt);
                    $data["igra_ruta_doc_record"] = $record_archivo;
                    if ($record_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                } 
                if (isset($data["igra_ruta_doc_certificado"]) && $data["igra_ruta_doc_certificado"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certificado"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certificado_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_certificado_per_" . $per_dni . "." . $typeFile;
                    $certificado_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certificado_archivoOld, $timeSt);
                    $data["igra_ruta_doc_certificado"] = $certificado_archivo;
                    if ($certificado_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                } 
                if (isset($data["igra_ruta_doc_syllabus"]) && $data["igra_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_syllabus_per_" . $per_dni . "." . $typeFile;
                    $syllabus_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, $timeSt);
                    $data["igra_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_homologacion"]) && $data["igra_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_dni . "/doc_homologacion_per_" . $per_dni . "." . $typeFile;
                    $homologacion_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, $timeSt);
                    $data["igra_ruta_doc_homologacion"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                }

                //datos personales  
                $per_dni = $data['cedula'];          
                $per_pri_nombre = $data["primer_nombre"];
                $per_seg_nombre = $data["segundo_nombre"];
                $per_pri_apellido = $data["primer_apellido"];
                $per_seg_apellido = $data["segundo_nombre"];
                $can_id_nacimiento = $data["cuidad_nac"];
                $per_fecha_nacimiento = $data["fecha_nac"];
                $per_nacionalidad = $data["nacionalidad"]; 
                $eciv_id = $data["estado_civil"];
                
                //datos contacto
                $pai_id_domicilio = $data["pais"];
                $pro_id_domicilio = $data["provincia"];
                $can_id_domicilio = $data["canton"];
                $per_domicilio_csec = $data["parroquia"];
                $per_domicilio_ref = $data["dir_domicilio"];
                $per_celular = $data["celular"];
                $per_domicilio_telefono = $data["telefono"];
                $per_correo = $data["correo"];

                //datos en caso de emergencias
                $per_trabajo_direccion = $data["dir_trabajo"];
                $pcon_nombre = $data["cont_emergencia"];
                $tpar_id = $data["parentesco"];
                $pcon_celular = $data["tel_emergencia"];
                $pcon_direccion = $data["dir_personacontacto"];

                $insc_persona = new Persona();
                \app\models\Utilities::putMessageLogFile(' personauuuuuuuuuuuuuuuuuuuuuu:  '.$per_dni);
                $resp_persona = $insc_persona->ConsultaRegistroExiste( 0,$per_dni, $per_dni);
                if ($resp_persona['existen'] == 0) {
                    //Nuevo Registro
                    \app\models\Utilities::putMessageLogFile(' persona:  '.$resp_inscripcion);
                    //if($resp_inscripcion == 0){ 
                        \app\models\Utilities::putMessageLogFile('datos a enviar:  '.$data);
                        \app\models\Utilities::putMessageLogFile('resultado de la inseercion:  '.$resul);
                    $regPersona = $mod_persona->insertarPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id, $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad);  
                    /*if ($regPersona) {
                        $exito=1;
                    }*/
                }else{

                    $resul = array();
                    $error++;
                    $error_message .= Yii::t("formulario", "The person already exists");    

                    $message = array(
                        "wtmessage" => Yii::t("formulario",  $error_message), //$error_message
                        "title" => Yii::t('jslang', 'Bad Request'),
                    );
                    $resul["status"] = FALSE;
                    $resul["error"] = null;
                    $resul["message"] = $message;
                    $resul["data"] = null;
                    $resul["dataext"] = null;
                

                    //return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Error'), $error_message);
                    return Utilities::ajaxResponse('ERROR_EXIST', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);   
                    //$resul = $model->actualizarInscripciongrado($data);
                    if ($resp_persona['existen'] == 1) {
                    // actualizacion de Persona
                    $respPersona = $mod_persona->modificaPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id,  $can_id_nacimiento, $per_fecha_nacimiento, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_celular, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad);
                    }
                }
                
                // creación de contacto
                $modpersonacontacto = new PersonaContacto();
                //Si existe registro en persona contacto se modifican los datos.
                $resexistecontacto = $modpersonacontacto->consultaPersonaContacto($per_id);
                if ($resexistecontacto) {
                    if ($pcon_nombre != $pcon_apellido) {
                        $contacto = $pcon_nombre . " " . $pcon_apellido;
                    } else {
                        $contacto = $pcon_nombre;
                    }
                    $resp_modcontacto = $modpersonacontacto->modificarPersonacontacto($per_id, $tpar_id, $contacto, $pcon_telefono, $pcon_celular, $pcon_direccion);
                } else {
                    if ($sincontacto != 1) {
                        //Creación de persona de contacto.
                        $modpersonacontacto->crearPersonaContacto($per_id, $tpar_id, $pcon_nombre . " " . $pcon_apellido, $pcon_telefono, $pcon_celular, $pcon_direccion);
                    }
                }
                $model = new InscripcionGrado();
                \app\models\Utilities::putMessageLogFile('consultarrrrr personasssss:  '.$per_dni);
                $resp_inscripcion = $model->consultarDatosInscripciongrado($per_dni);
                \app\models\Utilities::putMessageLogFile(' personaxxxxxxxxxxx:  '.$resp_inscripcion['existe_inscripcion']);
                if ($resp_inscripcion['existe_inscripcion'] == 0){ 
                    $resul = $model->insertarDataInscripciongrado($unidad, $carrera, $modalidad, $periodo, $per_dni, $data);
                    if ($resul) {
                            $exito=1;
                        }
                    if($exito){ 
                    \app\models\Utilities::putMessageLogFile('resultado es ok');
                        //$_SESSION['persona_id'] =  $resul['per_id'];
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have been saved"),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);                                        
                    }             
                    
                    //} 
                    else {
                        \app\models\Utilities::putMessageLogFile('resultado es NOok');
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have not been saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);
                    }
                }
            } catch (Exception $ex) {
                $transaction->rollback();                
                $message = array(
                    "wtmessage" => $ex->getMessage(),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }


    public function actionListarinscripciongrado() {
        $pro_model = new profesor();
        Utilities::putMessageLogFile('saludos1');
        /* Validacion de acceso a vistas por usuario */
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        $user_usermane = Yii::$app->session->get("PB_username");
        $user_perId = Yii::$app->session->get("PB_perid");
        $grupo_model = new Grupo();
        $search = NULL;
        $perfil = '0'; // perfil administrador o talento humano        

        $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
        if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos)) {
            $search = $user_perId;
            $perfil = '1';
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            $search = $data["search"];
            if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos)) {
                $search = $user_perId;
                $perfil = '1';  // perfil profesor o no administrador ni talento humano
            }
            $model = $pro_model->getAllProfesorGrid($search, $perfil);
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $model,
                ]);
            }
        }
        Utilities::putMessageLogFile('search:' . $search);
        $model = $pro_model->getAllProfesorGrid($search, $perfil);
        return $this->render('index', [
                    'model' => $model,
        ]);
    }
}