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

        $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_provincia = Provincia::provinciaXPais($arr_nacionalidad[0]["id"]);
        $arr_ciudad= Canton::cantonXProvincia($arr_provincia[0]["id"]);
        $arr_malla = $mod_malla->consultarmallasxcarrera($arr_unidad[0]['id'], $arr_carrera[0]['id'], $arr_modalidad[0]['id']);
        $mod_metodo = new MetodoIngreso();
        $arr_metodos = $mod_metodo->consultarMetodoUnidadAca_2($arr_unidad[0]["id"]);
        $mod_conempresa = new ConvenioEmpresa();
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');

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
            if (isset($data['getmalla'])) {
                $mallaca = $mod_malla->consultarmallasxcarrera($data['uaca_id'], $data['moda_id'], $data['eaca_id']);
                $message = array('mallaca' => $mallaca);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

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
            'arr_malla' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_malla), 'id', 'name'),
            "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
            "arr_convenio_empresa" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Ninguna")]], $arr_convempresa), "id", "name"),
        ]);
    }

    public function actionSaveinscripciongrado() {
        if (Yii::$app->request->isAjax) {
            $model = new InscripcionGrado();
            $data = Yii::$app->request->post();
            $accion = isset($data['ACCION']) ? $data['ACCION'] : "";
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                $inscripgrado_id = $data["inscripgrado_id"];
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/" . $data["name_file"] . "_per_" . $inscripgrado_id . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
            }
            if ($data["upload_filepago"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                $inscripgrado_id = $data["inscripgrado_id"];
                $files = $_FILES[key($_FILES)];                
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);                
                $dirFilePagoEnd = Yii::$app->params["documentFolder"] . "documentoadmision/" . $inscripgrado_id . "/" . $data["name_file"] . "_" . $inscripgrado_id . "." . $typeFile;                
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFilePagoEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
            }
            $timeSt = time();
            try {
                $inscripgrado_id = $data["DATA_1"][0]["twin_id"];
                if (isset($data["DATA_1"][0]["ruta_doc_titulo"]) && $data["DATA_1"][0]["ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_titulo_per_" . $inscripgrado_id . "." . $typeFile;
                    $titulo_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_dni"]) && $data["DATA_1"][0]["ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_dni_per_" . $inscripgrado_id . "." . $typeFile;
                    $dni_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $dni_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_certvota"]) && $data["DATA_1"][0]["ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_certvota_per_" . $inscripgrado_id . "." . $typeFile;
                    $certvota_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certvota_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_foto"]) && $data["DATA_1"][0]["ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_foto_per_" . $inscripgrado_id . "." . $typeFile;
                    $foto_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $foto_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                /*if (isset($data["DATA_1"][0]["ruta_doc_certificado"]) && $data["DATA_1"][0]["ruta_doc_certificado"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_certificado"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $doc_certificadoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_certificado_per_" . $inscripgrado_id . "." . $typeFile;
                    $doc_certificado = InscripcionAdmision::addLabelTimeDocumentos($inscripgrado_id, $doc_certificadoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_certificado"] = $doc_certificado;
                    if ($doc_certificado === false)
                        throw new Exception('Error doc Certificado no renombrado.');
                }*/
                if (isset($data["DATA_1"][0]["ruta_doc_hojavida"]) && $data["DATA_1"][0]["ruta_doc_hojavida"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_hojavida"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $doc_hojaVidaOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_hojavida_per_" . $inscripgrado_id . "." . $typeFile;
                    $doc_hojaVida = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $doc_hojaVidaOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_hojavida"] = $doc_hojaVida;
                    if ($doc_hojaVida === false)
                        throw new Exception('Error doc Hoja de Vida no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_aceptacion"]) && $data["DATA_1"][0]["ruta_doc_aceptacion"] != "") {                    
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_aceptacion"]));                    
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);                    
                    $doc_aceptacionOld = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $inscripgrado_id . "/doc_aceptacion_per_" . $inscripgrado_id . "." . $typeFile;                    
                    $doc_aceptacion = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $doc_aceptacionOld, $timeSt);                    
                    $data["DATA_1"][0]["ruta_doc_aceptacion"] = $doc_aceptacion;                    
                    if ($doc_aceptacion === false)
                        throw new Exception('Error documento aceptación.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_pago"]) && $data["DATA_1"][0]["ruta_doc_pago"] != "") {                    
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_pago"]));                    
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);                                                            
                    $doc_pagoOld = Yii::$app->params["documentFolder"] . "documentoadmision/" . $inscripgrado_id . "/pago_". $inscripgrado_id . "." . $typeFile;                                         
                    $doc_pago = InscripcionGrado::addLabelFechaDocPagos($inscripgrado_id, $doc_pagoOld, $fecha_registro);                      
                    $data["DATA_1"][0]["ruta_doc_pago"] = $doc_pago;                      
                    if ($doc_pagoOld === false)
                        throw new Exception('Error al cargar documento de pago.');
                }                                
                if ($accion == "create" || $accion == "Create") {
                    //Nuevo Registro                        
                    $resul = $model->insertarInscripciongrado($data);
                } else if ($accion == "Update") {
                    //Modificar Registro                        
                    $resul = $model->actualizarInscripciongrado($data);                    
                    //$model->insertaOriginal($resul["ids"]);
                } else if ($accion == "Fin") {
                    $Ids = isset($data['codigo']) ? $data['codigo'] : 0;                                                         
                    $dataRegistro = array(                        
                        'nombres_fact'  => ucwords(strtolower($data["nombres_fact"])),
                        'apellidos_fact'  => ucwords(strtolower($data["apellidos_fact"])),
                        'direccion_fact'  => ucwords(strtolower($data["direccion_fact"])),
                        'telefono_fac'  => $data["telefono_fac"], 
                        'tipo_dni_fac'  => $data["tipo_dni_fac"], 
                        'dni'  => $data["dni"],
                        'empresa' => ucfirst(mb_strtolower($data["empresa"],'UTF-8')), 
                        'correo' => strtolower($data["correo"]),
                        'num_transaccion' => $data["num_transaccion"],
                        'observacion' => strtolower($data["observacion"]),
                        'fecha_transaccion' => $data["fecha_transaccion"],  
                        'doc_pago' => $data["doc_pago"], 
                        'forma_pago' => $data["forma_pago"], 
                    );                      
                    $resul = $model->insertaOriginalgrado($Ids,$dataRegistro);                    
                } else if ($accion == "UpdateDepTrans") {
                    //Modificar Registro                    
                    $resul = $model->actualizarInscripciongrado($data);                                          
                }             
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