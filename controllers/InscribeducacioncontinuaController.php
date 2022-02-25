<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Persona;
use app\models\EmpresaPersona;
use \app\modules\admision\models\SolicitudInscripcion;
use app\models\Pais;
use app\modules\admision\models\Interesado;
use app\modules\admision\models\InteresadoEmpresa;
use app\models\Usuario;
use yii\base\Security;
use app\models\UsuaGrolEper;
use app\models\Provincia;
use app\modules\financiero\models\OrdenPago;
use app\modules\financiero\models\DetalleDescuentoItem;
use app\models\Canton;
use app\models\MedioPublicitario;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use yii\helpers\Url;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\Oportunidad;
use app\models\Empresa;
use app\modules\admision\models\TipoOportunidadVenta;
use app\modules\admision\models\EstadoContacto;
use app\modules\admision\models\MetodoIngreso;
use app\modules\financiero\models\Secuencias;
use app\models\InscripcionAdmision;
use app\modules\admision\models\ConvenioEmpresa;
use app\modules\academico\models\NivelInstruccion;
use app\modules\admision\models\BitacoraSeguimiento;

class InscribeducacioncontinuaController extends \yii\web\Controller {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    public function actionIndex() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $per_id = Yii::$app->session->get("PB_perid");
        $mod_persona = Persona::findIdentity($per_id);
        $mod_modalidad = new Modalidad();
        $mod_pergestion = new PersonaGestion();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new Oportunidad();
        $mod_metodo = new MetodoIngreso();
        $mod_inscripcion = new InscripcionAdmision();
        $mod_nivelinst = new NivelInstruccion();
        $mod_redes = new BitacoraSeguimiento();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais
                $mod_areapais = new Pais();
                $area = $mod_areapais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmetodo"])) {
                $metodos = $mod_metodo->consultarMetodoUnidadAca_2($data['nint_id']);
                $message = array("metodos" => $metodos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $pais_id = 1; //Ecuador
        $arr_prov_dom = Provincia::provinciaXPais($pais_id);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_prov_dom[0]["id"]);
        $arr_medio = MedioPublicitario::find()->select("mpub_id AS id, mpub_nombre AS value")->where(["mpub_estado_logico" => "1", "mpub_estado" => "1"])->asArray()->all();
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad(10, 1);  //EL 10 PUEDE VARIAR POR EL ID DE LA TABLA UNIDAD ACADEMICO
        $arr_conuteg = $mod_pergestion->consultarConociouteg();
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad(10, $arr_modalidad[0]["id"]); //EL 10 PUEDE VARIAR POR EL ID DE LA TABLA UNIDAD ACADEMIC
        $arr_metodos = $mod_metodo->consultarMetodoUnidadAca_2($arr_ninteres[0]["id"]);
        $mod_conempresa = new ConvenioEmpresa();
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $arr_nivelinst =  $mod_nivelinst->consultarNivelInstruccion();
        $arr_redes =  $mod_redes->consultarRedesusadas();
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');

        return $this->render('index', [
                    "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "tipos_dni2" => array("CED" => Yii::t("formulario", "DNI Document1"), "PASS" => Yii::t("formulario", "Passport1")),
                    "txth_extranjero" => $mod_persona->per_nac_ecuatoriano,
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    "arr_ninteres" => ArrayHelper::map($arr_ninteres, "id", "name"),
                    "arr_medio" => ArrayHelper::map($arr_medio, "id", "value"),
                    "arr_modalidad" => ArrayHelper::map($arr_modalidad, "id", "name"),
                    "arr_conuteg" => ArrayHelper::map($arr_conuteg, "id", "name"),
                    "arr_carrerra1" => ArrayHelper::map($arr_carrerra1, "id", "name"),
                    "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
                    "arr_convenio_empresa" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Ninguna")]], $arr_convempresa), "id", "name"),
                    "resp_datos" => $resp_datos,
                    "arr_nivelinst" =>ArrayHelper::map($arr_nivelinst, "id", "value"),
                    "arr_redes" =>ArrayHelper::map($arr_redes, "id", "name"),
        ]);
    }

    public function actionSaveinscripciontemp() {
        if (Yii::$app->request->isAjax) {
            $model = new InscripcionAdmision();
            $data = Yii::$app->request->post();
            $accion = isset($data['ACCION']) ? $data['ACCION'] : "";
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                $inscripcion_id = $data["inscripcion_id"];
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "solicitudadmision/" . $inscripcion_id . "/" . $data["name_file"] . "_per_" . $inscripcion_id . "." . $typeFile;
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
                $inscripcion_id = $data["inscripcion_id"];
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFilePagoEnd = Yii::$app->params["documentFolder"] . "documentoadmision/" . $inscripcion_id . "/" . $data["name_file"] . "_" . $inscripcion_id . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFilePagoEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
            }
            $timeSt = time();
            try {
                /*$inscripcion_id = $data["DATA_1"][0]["twin_id"];
                if (isset($data["DATA_1"][0]["ruta_doc_titulo"]) && $data["DATA_1"][0]["ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "solicitudadmision/" . $inscripcion_id . "/doc_titulo_per_" . $inscripcion_id . "." . $typeFile;
                    $titulo_archivo = InscripcionAdmision::addLabelTimeDocumentos($inscripcion_id, $titulo_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_dni"]) && $data["DATA_1"][0]["ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "solicitudadmision/" . $inscripcion_id . "/doc_dni_per_" . $inscripcion_id . "." . $typeFile;
                    $dni_archivo = InscripcionAdmision::addLabelTimeDocumentos($inscripcion_id, $dni_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_certvota"]) && $data["DATA_1"][0]["ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "solicitudadmision/" . $inscripcion_id . "/doc_certvota_per_" . $inscripcion_id . "." . $typeFile;
                    $certvota_archivo = InscripcionAdmision::addLabelTimeDocumentos($inscripcion_id, $certvota_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_foto"]) && $data["DATA_1"][0]["ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "solicitudadmision/" . $inscripcion_id . "/doc_foto_per_" . $inscripcion_id . "." . $typeFile;
                    $foto_archivo = InscripcionAdmision::addLabelTimeDocumentos($inscripcion_id, $foto_archivoOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_hojavida"]) && $data["DATA_1"][0]["ruta_doc_hojavida"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_hojavida"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $doc_hojaVidaOld = Yii::$app->params["documentFolder"] . "solicitudadmision/" . $inscripcion_id . "/doc_hojavida_per_" . $inscripcion_id . "." . $typeFile;
                    $doc_hojaVida = InscripcionAdmision::addLabelTimeDocumentos($inscripcion_id, $doc_hojaVidaOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_hojavida"] = $doc_hojaVida;
                    if ($doc_hojaVida === false)
                        throw new Exception('Error doc Hoja de Vida no renombrado.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_aceptacion"]) && $data["DATA_1"][0]["ruta_doc_aceptacion"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_aceptacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $doc_aceptacionOld = Yii::$app->params["documentFolder"] . "solicitudadmision/" . $inscripcion_id . "/doc_aceptacion_per_" . $inscripcion_id . "." . $typeFile;
                    $doc_aceptacion = InscripcionAdmision::addLabelTimeDocumentos($inscripcion_id, $doc_aceptacionOld, $timeSt);
                    $data["DATA_1"][0]["ruta_doc_aceptacion"] = $doc_aceptacion;
                    if ($doc_aceptacion === false)
                        throw new Exception('Error documento aceptación.');
                }
                if (isset($data["DATA_1"][0]["ruta_doc_pago"]) && $data["DATA_1"][0]["ruta_doc_pago"] != "") {
                    $arrIm = explode(".", basename($data["DATA_1"][0]["ruta_doc_pago"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $doc_pagoOld = Yii::$app->params["documentFolder"] . "documentoadmision/" . $inscripcion_id . "/pago_". $inscripcion_id . "." . $typeFile;
                    $doc_pago = InscripcionAdmision::addLabelFechaDocPagos($inscripcion_id, $doc_pagoOld, $fecha_registro);
                    $data["DATA_1"][0]["ruta_doc_pago"] = $doc_pago;
                    if ($doc_pagoOld === false)
                        throw new Exception('Error al cargar documento de pago.');
                }*/
                if ($accion == "create" || $accion == "Create") {
                    //Nuevo Registro
                    $resul = $model->insertarInscripcion($data);
                } else if ($accion == "Update") {
                    //Modificar Registro
                    $resul = $model->actualizarInscripcion($data);
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
                        'nivinstrucion' => $data["nivinstrucion"],
                        'redes' => $data["redes"],
                    );
                    $resul = $model->insertaOriginal($Ids,$dataRegistro);
                } else if ($accion == "UpdateDepTrans") {
                    //Modificar Registro
                    $resul = $model->actualizarInscripcion($data);
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
                $message = array(
                    "wtmessage" => $ex->getMessage(),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }

    public function actionSavepagodinner() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $data = Yii::$app->request->post();
        $dataGet = Yii::$app->request->get();
        $con1 = \Yii::$app->db_facturacion;
        $referenceID = isset($data["referenceID"]) ? $data["referenceID"] : null;
        if (!is_null($referenceID)) {
            try {
                $transaction = $con1->beginTransaction();
                $sins_id = $dataGet["sins_id"]; //base64_decode($dataGet["sins_id"]);
                $solInc_mod = SolicitudInscripcion::findOne($sins_id);
                $opago_mod = OrdenPago::findOne(["sins_id" => $sins_id, "opag_estado_pago" => "P", "opag_estado" => "1", "opag_estado_logico" => "1"]);
                $response = $this->render('btnpago', array(
                    "referenceID" => $data["resp"]["reference"],
                    "requestID" => $data["requestID"],
                    "ordenPago" => $opago_mod->opag_id,
                    "response" => $data["resp"],
                ));
                if ($data["resp"]["status"]["status"] == "APPROVED") {
                    $opago_mod->opag_estado_pago = "S";
                    $opago_mod->opag_valor_pagado = $opago_mod->opag_total;
                    $opago_mod->opag_fecha_pago_total = date("Y-m-d H:i:s");
                    $opago_mod->opag_usu_modifica = @Yii::$app->session->get("PB_iduser");
                    $opago_mod->opag_fecha_modificacion = date("Y-m-d H:i:s");
                    if ($opago_mod->save()) {
                        $dpag_mod = DesglosePago::findOne(["opag_id" => $opago_mod->opag_id, "dpag_estado_pago" => "P", "dpag_estado" => "1", "dpag_estado_logico" => "1"]);
                        $dpag_mod->dpag_estado_pago = "S";
                        $dpag_mod->dpag_usu_modifica = @Yii::$app->session->get("PB_iduser");
                        $dpag_mod->dpag_fecha_modificacion = date("Y-m-d H:i:s");
                        if ($dpag_mod->save()) {
                            $regpag_mod = new RegistroPago();
                            $regpag_mod->dpag_id = $dpag_mod->dpag_id;
                            $regpag_mod->fpag_id = 6; // boton de pagos
                            $regpag_mod->rpag_valor = $opago_mod->opag_total;
                            $regpag_mod->rpag_fecha_pago = $opago_mod->opag_fecha_pago_total;
                            $regpag_mod->rpag_revisado = "RE";
                            $regpag_mod->rpag_resultado = "AP";
                            $regpag_mod->rpag_num_transaccion = $referenceID;
                            $regpag_mod->rpag_fecha_transaccion = $opago_mod->opag_fecha_pago_total;
                            $regpag_mod->rpag_usuario_transaccion = @Yii::$app->session->get("PB_iduser");
                            $regpag_mod->rpag_codigo_autorizacion = "";
                            $regpag_mod->rpag_estado = "1";
                            $regpag_mod->rpag_estado_logico = "1";
                            if ($regpag_mod->save()) {
                                $transaction->commit();
                                // aqui se redirije al boton de pagos
                            } else {
                                Utilities::putMessageLogFile("Boton Pagos: Error al crear Registro Pago. RefId: " . $referenceID . " Error: " . json_encode($regpag_mod->errors));
                                throw new Exception('Error al crear Registro Pago.' . json_encode($regpag_mod->errors));
                            }
                        } else {
                            Utilities::putMessageLogFile("Boton Pagos: Error al actualizar Desglose Pago RefId: " . $referenceID . " Error: " . json_encode($dpag_mod->errors));
                            throw new Exception('Error al actualizar Desglose Pago.' . json_encode($dpag_mod->errors));
                        }
                    } else {
                        Utilities::putMessageLogFile("Boton Pagos: Error al actualizar pago. RefId: " . $referenceID . " Error: " . json_encode($opago_mod->errors));
                        throw new Exception('Error al actualizar pago.' . json_encode($opago_mod->errors));
                    }
                } else {
                    Utilities::putMessageLogFile("Boton Pagos: Error . RefId: " . $referenceID);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Utilities::putMessageLogFile("Boton Pagos: Error . RefId: " . $referenceID . "Error: " . $e->getMessage());
            }
        }
        //Secuencias::initSecuencia($con1, $emp_id, 1, 1, 'BPA',"BOTON DE PAGOS DINERS");
        // Info de Solicitud Inscripcion
        $sins_id = $dataGet["sins_id"]; //base64_decode($dataGet["sins_id"]);
        $solInc_mod = SolicitudInscripcion::findOne($sins_id);
        $int_mod = Interesado::findOne($solInc_mod->int_id);
        $per_mod = Persona::findOne($int_mod->per_id);
        $opago_mod = OrdenPago::findOne(["sins_id" => $sins_id, "opag_estado_pago" => "P", "opag_estado" => 1, "opag_estado_logico" => 1]);
        $obj_sol = $solInc_mod::consultarInteresadoPorSol_id($sins_id);
        $descripcionItem = Yii::t("formulario", "Payment of ") . $obj_sol["carrera"]; //financiero::t("Pagos", "Payment of ") . $obj_sol["carrera"];
        $titleBox = Yii::t("formulario", "Payment Course/Career/Program: ") . $obj_sol["carrera"]; //financiero::t("Pagos", "Payment Course/Career/Program: ") . $obj_sol["carrera"];
        $totalpagar = $opago_mod->opag_total;
        $secuencia = 130; //Secuencias::nuevaSecuencia($con1, $emp_id, 1, 1, 'BPA');
        return $this->render('btnpago', array(
                    "referenceID" => $secuencia, //str_pad(Secuencias::nuevaSecuencia($con1, $emp_id, 1, 1, 'BPA'), 8, "0", STR_PAD_LEFT),
                    "ordenPago" => $opago_mod->opag_id,
                    "nombre_cliente" => $per_mod->per_pri_nombre,
                    "apellido_cliente" => $per_mod->per_pri_apellido,
                    "descripcionItem" => $descripcionItem,
                    "titleBox" => $titleBox,
                    "email_cliente" => $per_mod->per_correo,
                    "total" => $totalpagar,
        ));
    }

}
