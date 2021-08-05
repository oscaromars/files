<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Persona;
use app\models\Empresa;
use app\models\EmpresaPersona;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use app\models\Grupo;
use app\models\Rol;
use app\models\GrupRol;
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
                //return;
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                //return;
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
        $arr_ciudad_nac= Canton::find()->select("can_id AS id, can_nombre AS value")->where(["can_estado_logico" => "1", "can_estado" => "1"])->asArray()->all();
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
            "arr_ciudad_nac" => ArrayHelper::map($arr_ciudad_nac, "id", "value"),
            "arr_nacionalidad" => ArrayHelper::map($arr_nacionalidad, "id", "value"),
            "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
            "arr_pais" => ArrayHelper::map($arr_pais, "id", "value"),
            "arr_provincia" => ArrayHelper::map($arr_provincia, "id", "value"),
            "arr_ciudad" => ArrayHelper::map($arr_ciudad, "id", "value"),
            //'arr_malla' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_malla), 'id', 'name'),
            "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
            "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
            "arr_convenio_empresa" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Ninguna")]], $arr_convempresa), "id", "name"),
            "resp_datos" => $resp_datos,
        ]);
    }

    public function actionGuardarinscripciongrado() {
        $mod_persona = new Persona();
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $mod_persona = new Persona();
                $resp_persona = $mod_persona->consultarUltimoPer_id();
                $persona = $resp_persona["ultimo"];
                $per_id = intval( $persona );
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'pdf' || $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg') {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . "." . $typeFile;
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

                $unidad = $data['unidad'];
                $carrera = $data['carrera'];
                $modalidad = $data['modalidad'];
                $periodo = $data['periodo'];
                $ming_id = $data['ming_id'];

                $mod_persona = new Persona();
                $resp_persona = $mod_persona->consultarUltimoPer_id();
                $persona = $resp_persona["ultimo"];
                $per_id = intval( $persona );

                $per_dni = $data['cedula'];
                $inscripgrado_id = $data["igra_id"];
                if (isset($data["igra_ruta_doc_titulo"]) && $data["igra_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_titulo_per_" . $per_id . "." . $typeFile;
                    $titulo_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_archivoOld, $timeSt);
                    $data["igra_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["igra_ruta_doc_dni"]) && $data["igra_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_dni_per_" . $per_id . "." . $typeFile;
                    $dni_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $dni_archivoOld, $timeSt);
                    $data["igra_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certvota"]) && $data["igra_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_certvota_per_" . $per_id . "." . $typeFile;
                    $certvota_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certvota_archivoOld, $timeSt);
                    $data["igra_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["igra_ruta_doc_foto"]) && $data["igra_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_foto_per_" . $per_id . "." . $typeFile;
                    $foto_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $foto_archivoOld, $timeSt);
                    $data["igra_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["igra_ruta_doc_comprobantepago"]) && $data["igra_ruta_doc_comprobantepago"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_comprobantepago"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_comprobantepago_per_" . $per_id . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $comprobantepago_archivoOld, $timeSt);
                    $data["igra_ruta_doc_comprobantepago"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }
                
                if (isset($data["igra_ruta_doc_record"]) && $data["igra_ruta_doc_record"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_record"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_record_per_" . $per_id . "." . $typeFile;
                    $record_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $record_archivoOld, $timeSt);
                    $data["igra_ruta_doc_record"] = $record_archivo;
                    if ($record_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                } 
                if (isset($data["igra_ruta_doc_certificado"]) && $data["igra_ruta_doc_certificado"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certificado"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certificado_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_certificado_per_" . $per_id . "." . $typeFile;
                    $certificado_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certificado_archivoOld, $timeSt);
                    $data["igra_ruta_doc_certificado"] = $certificado_archivo;
                    if ($certificado_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                } 
                if (isset($data["igra_ruta_doc_syllabus"]) && $data["igra_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_syllabus_per_" . $per_id . "." . $typeFile;
                    $syllabus_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, $timeSt);
                    $data["igra_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_homologacion"]) && $data["igra_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/doc_homologacion_per_" . $per_id . "." . $typeFile;
                    $homologacion_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $homologacion_archivoOld, $timeSt);
                    $data["igra_ruta_doc_homologacion"] = $homologacion_archivo;
                    if ($homologacion_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                }


                \app\models\Utilities::putMessageLogFile(' lugar nacimiento:  '.$data["cuidad_nac"]);
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
                    $regPersona = $mod_persona->insertarPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id, $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad,$per_trabajo_direccion);  
                    
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
                    $respPersona = $mod_persona->modificaPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id,  $can_id_nacimiento, $per_fecha_nacimiento, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_celular, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad, $per_trabajo_direccion);
                    }
                }
                
                // creación de contacto
                    $modpersonacontacto = new PersonaContacto();
                    $mod_persona = new Persona();
                    $resp_persona = $mod_persona->consultPer_id();
                    $persona = $resp_persona["ultimo"];
                    \app\models\Utilities::putMessageLogFile('traer el per_id:  '.$per_id);
                    $per_id = intval( $persona );

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
                \app\models\Utilities::putMessageLogFile('consultarrrrr personasssss:  '.$per_id);
                $resp_inscripcion = $model->consultarDatosInscripciongrado($per_id);
                \app\models\Utilities::putMessageLogFile(' personaxxxxxxxxxxx:  '.$resp_inscripcion['existe_inscripcion']);
                if ($resp_inscripcion['existe_inscripcion'] == 0){ 
                    $resul = $model->insertarDataInscripciongrado($per_id, $unidad, $carrera, $modalidad, $periodo, $per_dni, $data);
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


    public function actionAspirantegrado() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $model_grado = new InscripcionGrado();
        $unidad_model = new UnidadAcademica();
        $carrera_model = new EstudioAcademico();
        $moda_model = new Modalidad();        $periodo_model = new PeriodoAcademico(); 
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"]      = $data['search'];
            $arrSearch["periodo"]     = $data['periodo'];  
            $arrSearch["unidad"]      = $data['unidad'];
            $arrSearch["carrera"]      = $data['carrera'];
            $arrSearch["modalidad"]   = $data['modalidad'];
            $model = $model_grado->consultaRegistroAdmisiongrado($arrSearch, 1);
            return $this->render('_aspirantegradogrid', [
                "model" => $model,
            ]);

        }else {
            $model = $model_grado->consultaRegistroAdmisiongrado(null, 1);
        }

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data['getmodalidades'])) {
                $modalidad = $carrera_model->consultarmodalidadxcarrera($data['carrera']);
                $message = array('modalidades' => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_unidad = $unidad_model->consultarUnidadAcademicas();
        $arr_carrera = $carrera_model->consultarCarreraxunidad($arr_unidad[0]['id']);
        $arr_modalidad = $carrera_model->consultarmodalidadxcarrera($arr_carrera[0]['id']);
        $arr_periodo = $periodo_model->consultarPeriodosActivos();
        return $this->render('aspirantegrado_index', [
            "arr_unidad" => ArrayHelper::map($arr_unidad, "id", "name"),
            'arr_carrera' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_carrera), 'id', 'name'),
            'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_modalidad), 'id', 'name'),
            'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'paca_nombre' => 'Seleccionar']], $arr_periodo), 'id', 'paca_nombre'),
            "model" => $model,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {

            $id = $data['id']; // per_id
            $per_cedula = $data['cedula'];
            \app\models\Utilities::putMessageLogFile('ver el resultado del id:  '.$id);
            $persona_model = Persona::findOne($id);
            
            $usuario_model = Usuario::findOne(["per_id" => $id, "usu_estado" => '1', "usu_estado_logico" => '1']);
            $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $id, "eper_estado" => '1', "eper_estado_logico" => '1']);

            /* Validacion de acceso a vistas por usuario */
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            $user_usermane = Yii::$app->session->get("PB_username");
            $user_perId = Yii::$app->session->get("PB_perid");
            $grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['inscripciongrado/aspirantegrado']);
            }

            /**
             * Inf. Personal
             */
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);
            $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();

            $ViewFormTab1 = $this->renderPartial('ViewFormTab1', [
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. contacto
             */
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $ViewFormTab2 = $this->renderPartial('ViewFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. en caso de emergencia
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

            $ViewFormTab3 = $this->renderPartial('ViewFormTab3', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,

            ]);

            /**
             * Documentación
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

            $ViewFormTab4 = $this->renderPartial('ViewFormTab4', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,

            ]);


            $items = [
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Datos Personales'),
                    'content' => $ViewFormTab1,
                    'active' => true
                ],
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Datos de Contacto'),
                    'content' => $ViewFormTab2,
                ],
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Datos en caso de Emergencia'),
                    'content' => $ViewFormTab3,
                ],
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Documentación'),
                    'content' => $ViewFormTab4,
                ],
            ];
            return $this->render('view', ['items' => $items, 'persona_model' => $persona_model, 'contacto_model' => $contacto_model]);
        }
        return $this->redirect(['inscripciongrado/aspirantegrado']);
    }

    public function actionEdit() {

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {

                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if (($typeFile == 'png') or ( $typeFile == 'jpg') or ( $typeFile == 'jpeg')) {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "expediente/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
                    }
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
                }
            }
        }
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];

            if (Yii::$app->request->isAjax) {
                if (isset($data["pai_id"])) {
                    $model = new Provincia();
                    $arr_pro = $model->provinciabyPais($data["pai_id"]);

                    list($firstpro) = $arr_pro;

                    $arr_can = Canton::find()
                                    ->select(["can_id as id", "can_nombre as name"])
                                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                                        "pro_id" => $firstpro['id']])->asArray()->all();

                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', ['arr_pro' => $arr_pro, 'arr_can' => $arr_can]);
                } else if (isset($data["pro_id"])) {
                    $arr_can = Canton::find()
                                    ->select(["can_id as id", "can_nombre as name"])
                                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                                        "pro_id" => $data["pro_id"]])->asArray()->all();

                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $arr_can);
                }
            }

            $persona_model = Persona::findOne($id);
            Utilities::putMessageLogFile('id:' . $id);
            $usuario_model = Usuario::findOne(["per_id" => $id, "usu_estado" => '1', "usu_estado_logico" => '1']);
            $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $id, "eper_estado" => '1', "eper_estado_logico" => '1']);
            $email = (isset($persona_model->per_correo) && $persona_model->per_correo != "") ? ($persona_model->per_correo) : ($usuario_model->usu_user);

            /* Validacion de acceso a vistas por usuario */
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            $user_usermane = Yii::$app->session->get("PB_username");
            $user_perId = Yii::$app->session->get("PB_perid");
            $grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['profesor/index']);
            }

            /**
             * Inf. Basica
             */
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);
            $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();

            $EditFormTab1 = $this->renderPartial('EditFormTab1', [
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. contacto
             */
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            Utilities::putMessageLogFile('pais:' . $persona_model->pai_id_domicilio);
            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $EditFormTab2 = $this->renderPartial('EditFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                'persona_model' => $persona_model,
                'email' => $email,
            ]);

            /**
             * Inf. caso de emergencia
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

            $EditFormTab3 = $this->renderPartial('EditFormTab3', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,
            ]);


            $items = [
                [
                    'label' => Academico::t('profesor', 'Info. Datos Personales'),
                    'content' => $EditFormTab1,
                    'active' => true
                ],
                [
                    'label' => Academico::t('profesor', 'Info. Datos de contacto'),
                    'content' => $EditFormTab2,
                ],
                [
                    'label' => Academico::t('profesor', 'Info. Datos en caso de Emergencia'),
                    'content' => $EditFormTab3,
                ],
            ];

            return $this->render('edit', [
                        'items' => $items, 
                        'persona_model' => $persona_model, 
                        'contacto_model' => $contacto_model,
            ]);
        }
        return $this->redirect(['inscripciongrado/aspirantegrado']);
    }
    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $per_id = $data["per_id"];

                /* Validacion de acceso a vistas por usuario */
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $user_usermane = Yii::$app->session->get("PB_username");
                $user_perId = Yii::$app->session->get("PB_perid");
                $grupo_model = new Grupo();
                $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
                if ($per_id != $user_perId) {
                    // if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos)  && !in_array(['id' => '15'], $arr_grupos))

                    if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                        return $this->redirect(['profesor/index']);
                }

                /**
                 * Inf. Basica
                 */

                $cedula = $data["cedula"];
                $pasaporte = $data["pasaporte"];
                $pri_nombre = $data["pri_nombre"];
                $seg_nombre = $data["seg_nombre"];
                $pri_apellido = $data["pri_apellido"];
                $seg_apellido = $data["seg_apellido"];
                $can_id_nacimiento = $data["can_id"];
                $fecha_nacimiento = $data["fecha_nacimiento"];
                $correo = strtolower($data["correo"]);
                $nacionalidad = $data["nacionalidad"];
                $celular = $data["celular"];
                $phone = $data["phone"];
                $dedicacion = $data["dedicacion"];
                $pro_num_contrato = $data["pro_num_contrato"];
                $foto = $data['foto'];

                /**
                 * Inf. Domicilio
                 */
                $pai_id_domicilio = $data["pai_id"];
                $pro_id_domicilio = $data["pro_id"];
                $can_id_domicilio = $data["can_id"];
                $sector = ucwords($data["sector"]);
                $calle_pri = ucwords($data["calle_pri"]);
                $calle_sec = ucwords($data["calle_sec"]);
                $numeracion = ucwords($data["numeracion"]);
                $referencia = ucwords($data["referencia"]);

                /**
                 * Inf. Cuenta
                 */
                $usuario = strtolower($data["usuario"]);
                $clave = $data["clave"];
                $gru_id = $data["gru_id"];
                $rol_id = $data["rol_id"];
                $emp_id = $data["emp_id"];

                $persona_model = Persona::findOne($per_id);
                $persona_model->per_pri_nombre = $pri_nombre;
                $persona_model->per_seg_nombre = $seg_nombre;
                $persona_model->per_pri_apellido = $pri_apellido;
                $persona_model->per_seg_apellido = $seg_apellido;
                $persona_model->per_cedula = $cedula;
                if ($ruc != "") {
                    $persona_model->per_ruc = $ruc;
                }
                if ($pasaporte != "") {
                    $persona_model->per_pasaporte = $pasaporte;
                }
                $persona_model->per_correo = $correo;
                $persona_model->per_nacionalidad = $nacionalidad;
                $persona_model->per_celular = $celular;
                $persona_model->per_domicilio_telefono = $phone;
                $persona_model->per_fecha_nacimiento = $fecha_nacimiento;
                $persona_model->pai_id_domicilio = $pai_id_domicilio;
                $persona_model->pro_id_domicilio = $pro_id_domicilio;
                $persona_model->can_id_domicilio = $can_id_domicilio;
                $persona_model->per_domicilio_sector = $sector;
                $persona_model->per_domicilio_cpri = $calle_pri;
                $persona_model->per_domicilio_csec = $calle_sec;
                $persona_model->per_domicilio_num = $numeracion;
                $persona_model->per_domicilio_ref = $referencia;
                $arr_file = explode($foto, '.jpg');
                if (isset($arr_file[0]) && $arr_file[0] != "") {
                    $oldFile = $this->folder_cv . '/' . $foto;
                    $persona_model->per_foto = $this->folder_cv . '/' . $per_id . "_" . $foto;
                    $urlBase = Yii::$app->basePath . Yii::$app->params["documentFolder"];
                    rename($urlBase . $oldFile, $urlBase . $persona_model->per_foto);
                }

                /**
                 * Inf. Session Storages
                 */
                $arr_instuccion = (isset($data["grid_instruccion_list"]) && $data["grid_instruccion_list"] != "") ? $data["grid_instruccion_list"] : NULL;
                $arr_docencia = (isset($data["grid_docencia_list"]) && $data["grid_docencia_list"] != "") ? $data["grid_docencia_list"] : NULL;
                $arr_experiencia = (isset($data["grid_experiencia_list"]) && $data["grid_experiencia_list"] != "") ? $data["grid_experiencia_list"] : NULL;
                $arr_idioma = (isset($data["grid_idioma_list"]) && $data["grid_idioma_list"] != "") ? $data["grid_idioma_list"] : NULL;
                $arr_investigacion = (isset($data["grid_investigacion_list"]) && $data["grid_investigacion_list"] != "") ? $data["grid_investigacion_list"] : NULL;
                $arr_evento = (isset($data["grid_evento_list"]) && $data["grid_evento_list"] != "") ? $data["grid_evento_list"] : NULL;
                $arr_conferencia = (isset($data["grid_conferencia_list"]) && $data["grid_conferencia_list"] != "") ? $data["grid_conferencia_list"] : NULL;
                $arr_publicacion = (isset($data["grid_publicacion_list"]) && $data["grid_publicacion_list"] != "") ? $data["grid_publicacion_list"] : NULL;
                $arr_coordinacion = (isset($data["grid_coordinacion_list"]) && $data["grid_coordinacion_list"] != "") ? $data["grid_coordinacion_list"] : NULL;
                $arr_evaluacion = (isset($data["grid_evaluacion_list"]) && $data["grid_evaluacion_list"] != "") ? $data["grid_evaluacion_list"] : NULL;
                $arr_referencia = (isset($data["grid_referencia_list"]) && $data["grid_referencia_list"] != "") ? $data["grid_referencia_list"] : NULL;

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );

                if ($persona_model->save()) {
                    $usuario_model = Usuario::findOne(["per_id" => $per_id]);

                    /** Se agregan Informacion de Expediente * */
                    $profesor_model = Profesor::findOne(["per_id" => $per_id]);
                    $profesor_model->ddoc_id = $dedicacion;
                    $profesor_model->pro_num_contrato = $pro_num_contrato;
                    $profesor_model->save();
                    //ProfesorInstruccion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_instuccion)) {
                        foreach ($arr_instuccion as $key0 => $value0) {
                            if ($value0[6] == "N") {
                                $instruccion_model = new ProfesorInstruccion();
                                $instruccion_model->nins_id = $value0[1];
                                $instruccion_model->pins_institucion = ucwords($value0[2]);
                                $instruccion_model->pins_especializacion = ucwords($value0[3]);
                                $instruccion_model->pins_titulo = ucwords($value0[4]);
                                $instruccion_model->pins_senescyt = strtoupper($value0[5]);
                                $instruccion_model->pro_id = $profesor_model->pro_id;
                                $instruccion_model->pins_estado = '1';
                                $instruccion_model->pins_estado_logico = '1';
                                $instruccion_model->pins_usuario_ingreso = $user_ingresa;
                                $instruccion_model->save();
                            }
                        }
                    }
                    if (isset($arr_docencia)) {
                        foreach ($arr_docencia as $key1 => $value1) {
                            if ($value1[6] == "N") {
                                $docencia_model = new ProfesorExpDoc();
                                $docencia_model->ins_id = $value1[1];
                                $docencia_model->pedo_fecha_inicio = $value1[2];
                                $docencia_model->pedo_fecha_fin = $value1[3];
                                $docencia_model->pedo_denominacion = ucwords($value1[4]);
                                $docencia_model->pedo_asignaturas = ucwords($value1[5]);
                                $docencia_model->pro_id = $profesor_model->pro_id;
                                $docencia_model->pedo_estado = '1';
                                $docencia_model->pedo_estado_logico = '1';
                                $docencia_model->pedo_usuario_ingreso = $user_ingresa;
                                $docencia_model->save();
                            }
                        }
                    }
                    //ProfesorExpProf::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_experiencia)) {
                        foreach ($arr_experiencia as $key2 => $value2) {
                            if ($value2[6] == "N") {
                                $experiencia_model = new ProfesorExpProf();
                                $experiencia_model->pepr_organizacion = ucwords($value2[1]);
                                $experiencia_model->pepr_fecha_inicio = $value2[2];
                                $experiencia_model->pepr_fecha_fin = $value2[3];
                                $experiencia_model->pepr_denominacion = ucwords($value2[4]);
                                $experiencia_model->pepr_funciones = ucwords($value2[5]);
                                $experiencia_model->pro_id = $profesor_model->pro_id;
                                $experiencia_model->pepr_estado = '1';
                                $experiencia_model->pepr_estado_logico = '1';
                                $experiencia_model->pepr_usuario_ingreso = $user_ingresa;
                                $experiencia_model->save();
                            }
                        }
                    }
                    //ProfesorIdiomas::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_idioma)) {
                        foreach ($arr_idioma as $key3 => $value3) {
                            if ($value3[6] == "N") {
                                $idiomas_model = new ProfesorIdiomas();
                                $idiomas_model->idi_id = $value3[1];
                                $idiomas_model->pidi_nivel_escrito = ucfirst($value3[2]);
                                $idiomas_model->pidi_nivel_oral = ucfirst($value3[3]);
                                $idiomas_model->pidi_certificado = ucfirst($value3[4]);
                                $idiomas_model->pidi_institucion = ucwords($value3[5]);
                                $idiomas_model->pro_id = $profesor_model->pro_id;
                                $idiomas_model->pidi_estado = '1';
                                $idiomas_model->pidi_estado_logico = '1';
                                $idiomas_model->pidi_usuario_ingreso = $user_ingresa;
                                $idiomas_model->save();
                            }
                        }
                    }
                    //ProfesorInvestigacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_investigacion)) {
                        foreach ($arr_investigacion as $key4 => $value4) {
                            if ($value4[7] == "N") {
                                $investigacion_model = new ProfesorInvestigacion();
                                $investigacion_model->pinv_proyecto = ucwords($value4[1]);
                                $investigacion_model->pinv_ambito = ucwords($value4[2]);
                                $investigacion_model->pinv_responsabilidad = ucwords($value4[3]);
                                $investigacion_model->pinv_entidad = ucwords($value4[4]);
                                $investigacion_model->pinv_anio = strtolower($value4[5]);
                                $investigacion_model->pinv_duracion = strtolower($value4[6]);
                                $investigacion_model->pro_id = $profesor_model->pro_id;
                                $investigacion_model->pinv_estado = '1';
                                $investigacion_model->pinv_estado_logico = '1';
                                $investigacion_model->pinv_usuario_ingreso = $user_ingresa;
                                $investigacion_model->save();
                            }
                        }
                    }
                    //ProfesorCapacitacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_evento)) {
                        foreach ($arr_evento as $key5 => $value5) {
                            if ($value5[6] == "N") {
                                $capacitacion_model = new ProfesorCapacitacion();
                                $capacitacion_model->pcap_tipo = strtolower($value5[4]);
                                $capacitacion_model->pcap_evento = ucwords($value5[1]);
                                $capacitacion_model->pcap_institucion = ucwords($value5[2]);
                                $capacitacion_model->pcap_anio = strtolower($value5[3]);
                                $capacitacion_model->pcap_duracion = strtolower($value5[5]);
                                $capacitacion_model->pro_id = $profesor_model->pro_id;
                                $capacitacion_model->pcap_estado = '1';
                                $capacitacion_model->pcap_estado_logico = '1';
                                $capacitacion_model->pcap_usuario_ingreso = $user_ingresa;
                                $capacitacion_model->save();
                            }
                        }
                    }
                    //ProfesorConferencia::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_conferencia)) {
                        foreach ($arr_conferencia as $key6 => $value6) {
                            if ($value6[5] == "N") {
                                $capacitacion_model = new ProfesorConferencia();
                                $capacitacion_model->pcon_evento = ucwords($value6[1]);
                                $capacitacion_model->pcon_institucion = ucwords($value6[2]);
                                $capacitacion_model->pcon_anio = strtolower($value6[3]);
                                $capacitacion_model->pcon_ponencia = ucwords($value6[4]);
                                $capacitacion_model->pro_id = $profesor_model->pro_id;
                                $capacitacion_model->pcon_estado = '1';
                                $capacitacion_model->pcon_estado_logico = '1';
                                $capacitacion_model->pcon_usuario_ingreso = $user_ingresa;
                                $capacitacion_model->save();
                            }
                        }
                    }
                    //ProfesorCoordinacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_coordinacion)) {
                        foreach ($arr_coordinacion as $key7 => $value7) {
                            if ($value7[6] == "N") {
                                $coordinacion_model = new ProfesorCoordinacion();
                                $coordinacion_model->pcoo_alumno = ucwords($value7[1]);
                                $coordinacion_model->pcoo_programa = ucwords($value7[2]);
                                $coordinacion_model->pcoo_academico = ucwords($value7[3]);
                                $coordinacion_model->ins_id = ($value7[4]);
                                $coordinacion_model->pcoo_anio = strtolower($value7[5]);
                                $coordinacion_model->pro_id = $profesor_model->pro_id;
                                $coordinacion_model->pcoo_estado = '1';
                                $coordinacion_model->pcoo_estado_logico = '1';
                                $coordinacion_model->pcoo_usuario_ingreso = $user_ingresa;
                                $coordinacion_model->save();
                            }
                        }
                    }
                    //ProfesorEvaluacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_evaluacion)) {
                        foreach ($arr_evaluacion as $key8 => $value8) {
                            if ($value8[4] == "N") {
                                $evaluacion_model = new ProfesorEvaluacion();
                                $evaluacion_model->peva_periodo = strtolower($value8[1]);
                                $evaluacion_model->peva_institucion = ucwords($value8[2]);
                                $evaluacion_model->peva_evaluacion = ucwords($value8[3]);
                                $evaluacion_model->pro_id = $profesor_model->pro_id;
                                $evaluacion_model->peva_estado = '1';
                                $evaluacion_model->peva_estado_logico = '1';
                                $evaluacion_model->peva_usuario_ingreso = $user_ingresa;
                                $evaluacion_model->save();
                            }
                        }
                    }
                    //ProfesorPublicacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_publicacion)) {
                        foreach ($arr_publicacion as $key9 => $value9) {
                            if ($value9[6] == "N") {
                                $publicacion_model = new ProfesorPublicacion();
                                $publicacion_model->tpub_id = $value9[1];
                                $publicacion_model->ppub_titulo = ucwords($value9[2]);
                                $publicacion_model->ppub_editorial = ucwords($value9[3]);
                                $publicacion_model->ppub_isbn = strtoupper($value9[4]);
                                $publicacion_model->ppub_autoria = ucwords($value9[5]);
                                $publicacion_model->pro_id = $profesor_model->pro_id;
                                $publicacion_model->ppub_estado = '1';
                                $publicacion_model->ppub_estado_logico = '1';
                                $publicacion_model->ppub_usuario_ingreso = $user_ingresa;
                                $publicacion_model->save();
                            }
                        }
                    }
                    //ProfesorReferencia::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_referencia)) {
                        foreach ($arr_referencia as $key10 => $value10) {
                            if ($value10[5] == "N") {
                                $referencia_model = new ProfesorReferencia();
                                $referencia_model->pref_contacto = ucwords($value10[1]);
                                $referencia_model->pref_relacion_cargo = ucwords($value10[2]);
                                $referencia_model->pref_organizacion = ucwords($value10[3]);
                                $referencia_model->pref_numero = strtolower($value10[4]);
                                $referencia_model->pro_id = $profesor_model->pro_id;
                                $referencia_model->pref_estado = '1';
                                $referencia_model->pref_estado_logico = '1';
                                $referencia_model->pref_usuario_ingreso = $user_ingresa;
                                $referencia_model->save();
                            }
                        }
                    }
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no creado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.' . $ex->getMessage()),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
}