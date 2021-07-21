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
        if (base64_decode($_GET['ids']) != '') {// tomar el de parametro)
            $per_id = base64_decode($_GET['ids']);
        } else {
            $per_id = Yii::$app->session->get("PB_perid");
        }
        //Búsqueda de los datos de persona logueada
        $modperinteresado = new Persona();
        $respPerinteresado = $modperinteresado->consultaPersonaId($per_id);

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
            "respPerinteresado" => $respPerinteresado,
        ]);
    }

    public function actionGuardarinscripciongrado() {
        $mod_persona = new Persona();
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $model = new InscripcionGrado();
            $data = Yii::$app->request->post();
            //$accion = isset($data['ACCION']) ? $data['ACCION'] : "";
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            if ($_SESSION['persona_ingresa'] != '') {// tomar el de parametro)
                $per_id = $_SESSION['persona_ingresa'];
            } else {
                unset($_SESSION['persona_ingresa']);
                $per_id = Yii::$app->session->get("PB_perid");
            }
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                //$inscripgrado_id = $data["inscripgrado_id"];
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    //return;
                }
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
            $pai_id_nacimiento = $data["pais"];
            $pro_id_nacimiento = $data["provincia"];
            $can_id_nacimiento = $data["canton"];
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

            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();
            $con1 = \Yii::$app->db_captacion;
            $transaction1 = $con1->beginTransaction();
          
            $timeSt = time();
            try {

                // actualizacion de Persona
                /*$respPersona = $mod_persona->modificaPersona($per_id, $per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $etn_id, $eciv_id, $per_genero, $pai_id_nacimiento, $pro_id_nacimiento, $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $tsan_id, $per_domicilio_sector, $per_domicilio_cpri, $per_domicilio_csec, $per_domicilio_num, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nac_ecuatoriano, $per_nacionalidad, '');
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
                    }*/

                $unidad = $data['unidad'];
                $carrera = $data['carrera'];
                $modalidad = $data['modalidad'];
                $periodo = $data['periodo'];

                $inscripgrado_id = $data["DATA_1"][0]["igra_id"];
                if (isset($data["DATA_1"][0]["igra_ruta_doc_titulo"]) && $data["DATA_1"][0]["igra_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_titulo_per_" . $inscripgrado_id . "." . $typeFile;
                    $titulo_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["DATA_1"][0]["igra_ruta_doc_dni"]) && $data["DATA_1"][0]["igra_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_dni_per_" . $inscripgrado_id . "." . $typeFile;
                    $dni_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $dni_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["DATA_1"][0]["igra_ruta_doc_certvota"]) && $data["DATA_1"][0]["igra_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_certvota_per_" . $inscripgrado_id . "." . $typeFile;
                    $certvota_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certvota_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["DATA_1"][0]["igra_ruta_doc_foto"]) && $data["DATA_1"][0]["igra_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_foto_per_" . $inscripgrado_id . "." . $typeFile;
                    $foto_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $foto_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["DATA_1"][0]["igra_ruta_doc_comprobantepago"]) && $data["DATA_1"][0]["igra_ruta_doc_comprobantepago"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_comprobantepago"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_comprobantepago_per_" . $inscripgrado_id . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $comprobantepago_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_comprobantepago"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }
                
                if (isset($data["DATA_1"][0]["igra_ruta_doc_record"]) && $data["DATA_1"][0]["igra_ruta_doc_record"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_record"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_record_per_" . $inscripgrado_id . "." . $typeFile;
                    $record_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $record_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_record"] = $record_archivo;
                    if ($record_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                } 
                if (isset($data["DATA_1"][0]["igra_ruta_doc_certificado"]) && $data["DATA_1"][0]["ruta_doc_certificado"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_certificado"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certificado_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_certificado_per_" . $inscripgrado_id . "." . $typeFile;
                    $certificado_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certificado_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_certificado"] = $certificado_archivo;
                    if ($certificado_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                } 
                if (isset($data["DATA_1"][0]["igra_ruta_doc_syllabus"]) && $data["DATA_1"][0]["igra_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_syllabus_per_" . $inscripgrado_id . "." . $typeFile;
                    $syllabus_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["DATA_1"][0]["igra_ruta_doc_homologacion"]) && $data["DATA_1"][0]["igra_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["igra_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_homologacion_per_" . $inscripgrado_id . "." . $typeFile;
                    $homologacion_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, $timeSt);
                    $data["DATA_1"][0]["igra_ruta_doc_homologacion"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                }
                /*if ($accion == "index" || $accion == "Create") {
                    //Nuevo Registro 
                    \app\models\Utilities::putMessageLogFile('insertando data'.$resul);                       
                    $resul = $model->insertarInscripciongrado($data);
                } else if ($accion == "Update") {
                    //Modificar Registro                        
                    $resul = $model->actualizarInscripciongrado($data);                    
                    //$model->insertaOriginal($resul["ids"]);
                } else if ($accion == "Fin") {
                    $Ids = isset($data['codigo']) ? $data['codigo'] : 0;                                                                            
                    //$resul = $model->insertaOriginalgrado($Ids);                    
                //} else if ($accion == "UpdateDepTrans") {*/
                    //Modificar Registro          

                    \app\models\Utilities::putMessageLogFile('insertando data'.$resul);            
                    $resul = $model->insertarInscripciongrado($data, $per_id);                                          
                //}             
                if ($resul['status']) {
                    \app\models\Utilities::putMessageLogFile('resultado es ok');
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "The information have been saved"),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message, $resul);
                } else {
                    \app\models\Utilities::putMessageLogFile('resultado es NOok');
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "The information have not been saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);
                }
                return;
            } catch (Exception $ex) {
                //$transaction->rollback();                
                $message = array(
                    "wtmessage" => $ex->getMessage(),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }
}