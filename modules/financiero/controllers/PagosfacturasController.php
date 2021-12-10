<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\ExportFile;
use app\models\Persona;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use yii\helpers\Url;
use yii\base\Exception;
use yii\base\Security;
use app\modules\academico\models\Especies;
use app\modules\financiero\models\FormaPago;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\RegistroPagoMatricula;
use app\modules\financiero\models\PagosFacturaEstudiante;
use app\modules\academico\models\Matriculacion;
use app\modules\financiero\models\CargaCartera;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\financiero\models\Cruce;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();

class PagosfacturasController extends \app\components\CController {

    private function estados() {
        return [
            '0' => Yii::t("formulario", "All"),
            '1' => Yii::t("formulario", "Pendiente"),
            '2' => Yii::t("formulario", "Aprobado"),
            '3' => Yii::t("formulario", "Rechazado"),
        ];
    }

    private function estadoRechazo() {
        return [
            '0' => Yii::t("formulario", "Seleccione"),
            '2' => Yii::t("formulario", "Aprobado"),
            '3' => Yii::t("formulario", "Rechazado"),
        ];
    }

    private function estadoFinanciero() {
        return [
            '0' => Yii::t("formulario", "All"),
            'N' => Yii::t("formulario", "Pendiente"),
            'C' => Yii::t("formulario", "Cancelado"),
        ];
    }

    private function estadoReverso() {
        return [
            '0' => Yii::t("formulario", "Seleccione"),
            '1' => Yii::t("formulario", "Pendiente"),
        ];
    }

    private function Concepto() {
        return [
            '0' => Yii::t("formulario", "All"),
            'MA' => Yii::t("formulario", "Enrollment"),
            'ME' => financiero::t("Pagos", "Monthly payment"),
        ];
    }

    public function actionRevisionpagos() {
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                if (($data["unidad"] == 1) or ( $data["unidad"] == 2)) {
                    $modalidad = $mod_modalidad->consultarModalidad($data["unidad"], 1);
                } else {
                    $modalidad = $modestudio->consultarModalidadModestudio();
                }
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["estadopago"] = $data['estadopago'];
            $arrSearch["estadofinanciero"] = $data['estadofinanciero'];
            $arrSearch["concepto"] = $data['concepto'];
            $resp_pago = $mod_pagos->getPagos($arrSearch, false);
            return $this->renderPartial('_index-grid_revisionpago', [
                        "model" => $resp_pago,
            ]);
        }
        $model = $mod_pagos->getPagos(null, false);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);

        return $this->render('index_revisionpago', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_estado' => $this->estados(),
                    'arr_estado_financiero' => $this->estadoFinanciero(),
                    'arr_concepto' => $this->Concepto(),
        ]);
    }

    public function actionViewsaldo() {
        $perids = base64_decode($_GET["per_ids"]);
        $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
        $per_idsession = @Yii::$app->session->get("PB_perid");
        //\app\models\Utilities::putMessageLogFile('perids...: ' . $perids);
        if (!empty($perids)) {
            $per_idsession = $perids;
            // Si se quiere obtener el usuario id del estudiante
            /*$mod_persona = new Persona();
            $data_persona = $mod_persona->consultaPersonaId($per_idsession);
            $usuario = $data_persona['usu_id'];*/
        }
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modcanal = new Oportunidad();
        $mod_grupo = new UsuaGrolEper();
        //$mod_pagos = new PagosFacturaEstudiante();
        $mod_cartera = new CargaCartera();
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($personaData['uaca_id'], 1);
        if (($personaData['uaca_id'] == 1) or ( $personaData['uaca_id'] == 2)) {
            $carrera = $modcanal->consultarCarreraModalidad($personaData['uaca_id'], $personaData['mod_id']);
        } else {
            $carrera = $modestudio->consultarCursoModalidad($personaData['uaca_id'], $personaData['mod_id']); // tomar id de impresa
        }
        //$pagospendientesea = $mod_pagos->getPagospendientexest($personaData['per_cedula'], false);
        $pagospendientesea = $mod_cartera->getPagospendientexestcar($personaData['per_cedula'], false);
        $nombregrupo = $mod_grupo->consultarGruponame($usuario);
        return $this->render('viewsaldo', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    'model' => $pagospendientesea,
                    'nombregrupo' => $nombregrupo,
        ]);
    }

    public function actionRevisar() {
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $model = $mod_pagos->consultarPago($dpfa_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('revisar', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoRechazo(),
                    'arrObservacion' => array("0" => "Seleccione", "Archivo Ilegible" => "Archivo Ilegible", "Archivo no corresponde al pago" => "Archivo no corresponde al pago", "Archivo con Error" => "Archivo con Error", "Valor pagado no cubre factura" => "Valor pagado no cubre factura", "Archivo Duplicado" => "Archivo Duplicado"),
        ]);
    }

    public function actionSubirpago() {
        $perids = base64_decode($_GET["per_ids"]);
        $per_idsession = @Yii::$app->session->get("PB_perid");
        //\app\models\Utilities::putMessageLogFile('perids...: ' . $perids);
        if (!empty($perids)) {
            $per_idsession = $perids;
        }
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modcanal = new Oportunidad();
        $mod_fpago = new FormaPago();
        //$mod_pagos = new PagosFacturaEstudiante();
        $mod_cartera = new CargaCartera();
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($personaData['uaca_id'], 1);
        if (($personaData['uaca_id'] == 1) or ( $personaData['uaca_id'] == 2)) {
            $carrera = $modcanal->consultarCarreraModalidad($personaData['uaca_id'], $personaData['mod_id']);
        } else {
            $carrera = $modestudio->consultarCursoModalidad($personaData['uaca_id'], $personaData['mod_id']); // tomar id de impresa
        }
        $arr_forma_pago = $mod_fpago->consultarFormaPagosaldo();
        $arr_bancos = $mod_fpago->consultarBancos();
        //$pagospendientesea = $mod_pagos->getPagospendientexest($personaData['per_cedula'], false);
        $pagospendientesea = $mod_cartera->getPagospendientexestcar($personaData['per_cedula'], false);
        return $this->render('subirpago', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    "arr_bancos" => ArrayHelper::map($arr_bancos, "id", "value"),
                    'model' => $pagospendientesea,
        ]);
    }

    public function actionCargarpago() {
        $per_id = @Yii::$app->session->get("PB_perid");
        //$ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        $especiesADO = new Especies();
        $est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $mod_persona = new Persona();
        $data_persona = $mod_persona->consultaPersonaId($per_id);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'jpg' || $typeFile == 'png' || $typeFile == 'pdf') {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosfinanciero/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data']),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
            } else {
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo. " . $carga_archivo['message']),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
            return;
        }
        return $this->render('subirpago', [
        ]);
    }

    public function actionSaverechazo() {

        Utilities::putMessageLogFile("saverechazo");

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $id          = $data['dpfa_id'];
            $resultado   = $data['resultado'];
            $observacion = $data['observacion'];
            if (($resultado == "0") /* or ( $observacion == "0") */) {
                //Utilities::putMessageLogFile('ingresa');
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Seleccione un valor para los campos de 'Resultado' "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                return;
            } else {
                if (($resultado == "3" && $observacion != "0") or $resultado == "2") {
                    $con = \Yii::$app->db_facturacion;
                    $transaction = $con->beginTransaction();
                    $con1 = \Yii::$app->db_academico;
                    $transaction1 = $con1->beginTransaction();
                    try {
                        $datos = $mod_pagos->consultarPago($id);

                        $mod_id  = $datos['mod_id'];
                        $nombres = $datos['estudiante'];
                        $cedula  = $datos['identificacion'];
                        //Utilities::putMessageLogFile('$cuota:' . $datos['dpfa_num_cuota']);
                        if ($observacion == 0 && $resultado == "2") {
                            $observacion = null;
                        }
                        $respago = $mod_pagos->grabarRechazo($id, $resultado, $observacion);

                        if($datos['pfes_concepto'] == 'ME'){
                            $cartera = $mod_pagos->buscarIdCartera($id);
                            $id_cartera = $cartera[0]['ccar_id'];
                        }

                        if ($respago) {
                            $transaction->commit();
                            $transaction1->commit();
                            $correo_estudiante = $datos['per_correo'];
                            $user = $datos['estudiante'];
                            $tituloMensaje = 'Pagos en Línea';
                            $asunto = 'Pagos en Línea';
                            if ($resultado != "2") {
                                if($datos['pfes_concepto'] == 'ME'){
                                    $cargo = CargaCartera::findOne($id_cartera);
                                    $cargo->ccar_estado_cancela = 'N';
                                    $cargo->ccar_abono = $cargo->ccar_abono  - $data['abono'];
                                    $cargo->ccar_fecha_modificacion = $fecha;
                                    $cargo->ccar_usu_modifica = $usuario;
                                    $cargo->save();
                                }

                                if (!empty($datos['dpfa_num_cuota'])) {
                                    //Utilities::putMessageLogFile('$cuota:' . $datos['dpfa_num_cuota']);
                                    $body = Utilities::getMailMessage("pagonegadocuota", array(
                                                "[[user]]" => $user,
                                                "[[link]]" => "https://asgard.uteg.edu.ec/asgard/",
                                                "[[motivo]]" => $observacion,
                                                "[[factura]]" => $datos['dpfa_factura'],
                                                "[[cuota]]" => $datos['dpfa_num_cuota'],
                                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                } else {
                                    $body = Utilities::getMailMessage("pagonegado", array(
                                                "[[user]]" => $user,
                                                "[[link]]" => "https://asgard.uteg.edu.ec/asgard/",
                                                "[[motivo]]" => $observacion,
                                                "[[factura]]" => $datos['dpfa_factura'],
                                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                }
                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                            } else {
                                // Ya al subir la imagen pone el estado de cartera Cancelado
                                /*if($datos['pfes_concepto'] == 'ME'){
                                    $cargo = CargaCartera::findOne($id_cartera);
                                    $cargo->ccar_estado_cancela = 'C';
                                    $cargo->ccar_fecha_modificacion = $fecha;
                                    $cargo->ccar_usu_modifica = $usuario;
                                    $cargo->save();
                                }*/
                                //Utilities::putMessageLogFile('entro_envio vorreo');
                                //Utilities::putMessageLogFile('correo..' . $correo_estudiante);
                                $body = Utilities::getMailMessage("pagoaprobado", array(
                                            "[[user]]" => $user,
                                            "[[factura]]" => $datos['dpfa_factura'],
                                                ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                            }

                            // actualizar estados y data en registro_pago_matricula
                            $mod_pagosmat = new RegistroPagoMatricula();                          
                            $data_planificacion_pago = Matriculacion::getPlanificacionPago($datos['per_id']);                            
                            /*//\app\models\Utilities::putMessageLogFile('pfes_concepto: ' . $datos['pfes_concepto']);
                            //\app\models\Utilities::putMessageLogFile('per_id: ' . $datos['per_id']);
                            //\app\models\Utilities::putMessageLogFile('pla_id: ' . $data_planificacion_pago['pla_id']);*/
                            if ($datos['pfes_concepto'] == "MA") {

                                if ($resultado == "2") {
                                    $rpm_estado_aprobacion = 1;
                                }else{
                                    $rpm_estado_aprobacion = 2;
                                }
                                //\app\models\Utilities::putMessageLogFile('rpm_estado_aprobacion: ' . $rpm_estado_aprobacion);
                                $regpagomatricula = $mod_pagosmat->Modificarregsitropagomatricula($datos['per_id'], $data_planificacion_pago['pla_id'], $rpm_estado_aprobacion);

                                $bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula ), Yii::$app->language);

                                if($mod_id == 1){ //online
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanatoonline"] => "Decanato Online"], $asunto, $bodypmatricula);
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariaonline1"] => "Secretaria Online"], $asunto, $bodypmatricula);
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariaonline2"] => "Secretaria Online"], $asunto, $bodypmatricula);
                                }else if($mod_id == 2){//presencial
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanogradopresencial"] => "Decanato Presencial"], $asunto, $bodypmatricula);
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariagrado1"] => "Secretaria Presencial"], $asunto, $bodypmatricula);
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariagrado2"] => "Secretaria Presencial"], $asunto, $bodypmatricula);
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["coordinadorgrado"] => "Coordinador Presencial"], $asunto, $bodypmatricula);
                                }else{
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanogradosemi"] => "Decanato SemiPresencial"], $asunto, $bodypmatricula);
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariasemi"]  => "Secretaria SemiPresencial"], $asunto, $bodypmatricula);
                                }
                            }
                             //Utilities::putMessageLogFile('graba la transaccion');
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada"),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        } else {
                            $message = ["info" => Yii::t('exception', 'Error al grabar.')];
                            echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
                        }
                    } catch (Exception $ex) {
                        $transaction->rollback();
                        $transaction1->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    }
                    return;
                } else {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Seleccione un valor para los campos de 'Observación' "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    return;
                }
            }
            return;
        }
    }

    public function actionSavepagopendiente() {
        //DESCOMENTAR $perids AL PROBAR DESDE COLECTURIA LUEGO DE BOTONES
        //Obtenemo el id de la persona
        $per_idsession = @Yii::$app->session->get("PB_perid");
        //Obtenemos la fecha de hoy y le damos formato
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        //Revisamos si la peticion fue por ajax
        if (Yii::$app->request->isAjax) {
            //Obtenemo la data enviada
            $data           = Yii::$app->request->post();
            //Preguntamos por la forma de pago
            $name   = $data['nombres'];
            //Obtenemos su email ya que estos datos son solicitados por stripe
            $mod_usuario = Persona::find()->select("per_correo")->where(["per_id" => $data['per_id']])->asArray()->all();
            $email       = $mod_usuario[0]['per_correo'];
            //DESCOMENTAR IF AL PROBAR DESDE COLECTURIA LUEGO DE BOTONES
            $perids = base64_decode($data['perisest']);
            \app\models\Utilities::putMessageLogFile('perids en save...: ' . $perids);
            if (!empty($perids)) {
                $per_idsession = $perids;
            }
            \app\models\Utilities::putMessageLogFile('per_idsession en save...: ' . $per_idsession);
            if($data["formapago"]==1){
                //Si la forma de Pago es 1 significa que es por Tarjeta de credito
                $statusMsg = '';

                //Este token es enviada por la libreria de javascript de stripe
                if(!empty($data['token'])){
                    //Obtenemos el token y tambien el nombre de la persona que esta cancelando
                    $token  = $data['token'];

                    /******************************************************************/
                    /********** Clave de Conexión de Stripe ***************************/
                    /******************************************************************/
                    $stripe = array(
                        'secret_key'      => Yii::$app->params["secret_key"],
                    );

                    //Se hace invocacion a libreria de stripe que se encuentra en el vendor
                    \Stripe\Stripe::setApiKey($stripe['secret_key']);

                    $mensaje_error = '';
                    $mensaje_cod   = '';
                    try {
                        //Se crea el usuario para stripe
                        $customer = \Stripe\Customer::create(array(
                            'email'   => $email,
                            'source'  => $token
                        ));
                    }catch(\Stripe\Exception\CardException $e) {
                        //$api_error = $e->getMessage();
                        // Since it's a decline, \Stripe\Exception\CardException will be caught
                        /*
                        $mensaje = '';
                        $mensaje .= 'Status is:' . $e->getHttpStatus() . '\n';
                        $mensaje .= 'Type is:' . $e->getError()->type . '\n';
                        $mensaje .= 'Code is:' . $e->getError()->code . '\n';
                         // param is '' in this case
                        $mensaje .= 'Param is:' . $e->getError()->param . '\n';
                        */
                        $mensaje_error = $e->getError()->message . "(".$e->getError()->code.")";
                        $mensaje_cod   = $e->getError()->code;
                        $bandera = 1;
                    } catch (\Stripe\Exception\RateLimitException $e) {
                      // Too many requests made to the API too quickly
                        $mensaje_error = $e->getError()->message;
                        $mensaje_cod   = $e->getError()->code;
                        $bandera = 2;
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                      // Invalid parameters were supplied to Stripe's API
                        $mensaje_error = $e->getError()->message;
                        $mensaje_cod   = $e->getError()->code;
                        $bandera = 3;
                    } catch (\Stripe\Exception\AuthenticationException $e) {
                      // Authentication with Stripe's API failed
                      // (maybe you changed API keys recently)
                        $mensaje_error = $e->getError()->message;
                        $mensaje_cod   = $e->getError()->code;
                        //print_r($e->getError());die();
                        $bandera = 4;
                    } catch (\Stripe\Exception\ApiConnectionException $e) {
                      // Network communication with Stripe failed
                        $mensaje_error = $e->getError()->message;
                        $mensaje_cod   = $e->getError()->code;
                        $bandera = 5;
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                      // Display a very generic error to the user, and maybe send
                      // yourself an email
                        $mensaje_error = $e->getError()->message;
                        $mensaje_cod   = $e->getError()->code;
                        $bandera = 6;
                    } catch (\Stripe\Error\Base $e) {
                        $mensaje_error = $e->getError()->message;
                        $mensaje_cod   = $e->getError()->code;
                        $bandera = 7;
                    } catch (Exception $e) {
                      // Something else happened, completely unrelated to Stripe
                        $mensaje_error = $e->getError()->message;
                        $mensaje_cod   = $e->getError()->code;
                        $bandera = 8;
                    }

                    if($mensaje_cod != '' || $mensaje_error != ''){
                        if($mensaje_cod=='')
                            $mensaje_cod = $mensaje_error;
                        $message = array(
                            "wtmessage" => Yii::t("facturacion", $mensaje_cod),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                        return;
                    }//if

                    //Si se creo el usuario y no hay error
                    //print_r($mensaje_cod);die();
                    if(empty($mensaje_cod) && $customer){
                        //El valor se multiplica por 100 para convertirlo a centavos
                        $valor_inscripcion = $data['valor'];
                        $itemPriceCents    = ($valor_inscripcion*100);

                        //Se crea el cobro
                        try {
                            $charge = \Stripe\Charge::create(array(
                                'customer'    => $customer->id,
                                'amount'      => $itemPriceCents,
                                'currency'    => "usd",
                                'description' => "Pago de Cuotas desde el sistema Asgard/UTEG"
                            ));
                        }catch(Exception $e) {
                            $api_error = $e->getMessage();
                            //return json_encode("GALO".$api_error);
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", $mensaje_cod),
                                "title" => Yii::t('jslang', 'Error'),
                            );
                            echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                            return;
                        }

                        //Si se creo el combo y no hubo error se devuelve el resultado de la transaccion
                        if(empty($api_error) && $charge){
                            //Cargamos los datos
                            $chargeJson = $charge->jsonSerialize();

                            // Check whether the charge is successful
                            if( $chargeJson['amount_refunded'] == 0 &&
                                 empty($chargeJson['failure_code'])  &&
                                 $chargeJson['paid'] == 1 &&
                                 $chargeJson['captured'] == 1)
                            {
                                // Transaction details
                                $transactionID  = $chargeJson['balance_transaction'];
                                $paidAmount     = $chargeJson['amount'];
                                $paidAmount     = ($paidAmount/100);
                                $paidCurrency   = $chargeJson['currency'];
                                $payment_status = $chargeJson['status'];
                                //print_r($chargeJson); die();
                                $tarjeta        = $chargeJson['payment_method_details']['card']['brand'];
                                $tipo           = $chargeJson['payment_method_details']['card']['funding'];
                                $digito         = $chargeJson['payment_method_details']['card']['last4'];
                                $recibo         = $chargeJson['receipt_url'];

                                // Si el pago fue correcto
                                if($payment_status == 'succeeded'){
                                    $ordStatus = 'success';

                                    //Estas variables es para indicar que como fue con tarjeta de una vez
                                    //se actualize y ya no salgan como pendientes
                                    $dpfa_estado_pago       = 2;
                                    $dpfa_estado_financiero = 'C';

                                    $certificado = $chargeJson['receipt_url'];
                                }else{
                                    $statusMsg = "Your Payment has Failed!";
                                }
                            }else{
                                $statusMsg = "Transaction has been failed!";
                            }
                        }else{
                            $statusMsg = "Charge creation failed! $mensaje_cod";
                        }
                    }else{
                        $statusMsg = "Invalid card details! bandera = $bandera";
                    }
                }else{
                    $statusMsg = "Error on form submission.";
                }
                if($statusMsg != ''){
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al pagar online: " . $statusMsg),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    return;
                }
            }else{
                $mod_fpago     = new FormaPago();
                $arr_refBancos = $mod_fpago->consultarReferenciaBancos($data["referencia"],$data["banco"]);

                if(!empty($arr_refBancos)) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La refrencia ya existe para el banco seleccionado."),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    return;
                }

                //Pago por Deposito o Tranferencia
                if ($data["upload_file"]) {
                    if (empty($_FILES)) {
                        echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        return;
                    }//if

                    //Recibe Parametros
                    $files      = $_FILES[key($_FILES)];
                    $arrIm      = explode(".", basename($files['name']));
                    $typeFile   = strtolower($arrIm[count($arrIm) - 1]);
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosfinanciero/" . $data["per_id"] . "/" . $data["name_file"] . "." . $typeFile;
                    $status     = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        return;
                    }//else
                }//if

                //Como es un pago por deposito o transferencia sus estados son pendiente
                $dpfa_estado_pago       = 1;
                $dpfa_estado_financiero = 'N';
            }//fin del else

            $mod_pagos      = new PagosFacturaEstudiante();
            $mod_estudiante = new Especies();

            //En caso de ser pago por tarjeta entra por if o entra en else si es deposito o transferencia
            if($data["formapago"]==1){
                $imagen   = $certificado;//"pago_online";
            }else{
                $arrIm    = explode(".", basename($data["documento"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $imagen   = $arrIm[0] . "." . $typeFile;
            }

            //Obtenemos la informacion enviada por javascript
            $pfes_referencia  = $data["referencia"];
            $pfes_banco       = $data["banco"];
            $fpag_id          = $data["formapago"];
            $pfes_valor_pago  = $data["valor"];
            $pfes_fecha_pago  = $data["fechapago"];
            $pfes_observacion = $data["observacion"];
            $est_id           = $data["estid"];
            $pagado           = $data["pagado"];
            $personaData      = $mod_estudiante->consultaDatosEstudiante($per_idsession); //$personaData['per_cedula']
            $con              = \Yii::$app->db_facturacion;
            $transaction      = $con->beginTransaction();

            //Variable creada para el bucle de abono de pago
            $valor_pagado     = $data["valor"];

            try {
                //Se obtiene el id del usuario.
                $usuario = @Yii::$app->user->identity->usu_id;

                //gap
                //Este codigo de revisar si existe la cuota lo dejo pendiente por el tema del ABONO
                /*
                // consultar en detalle si ya no existe un registro con el mismo
                $pagadose = explode("*", $pagado); //PAGADOS
                $y = 0;
                foreach ($pagadose as $datose) {
                    //consultar la informacion seleccionada de cuota factura
                    $parametros = explode(";", $pagadose[$y]);
                    $resp_consregistro = $mod_pagos->consultarExistepago($est_id, $parametros[0], $parametros[1]);
                    $cuota_pagada .= $parametros[1] . ', ' ;
                    $y++;
                }
                */
                $mod_ccartera     = new CargaCartera();
                //if ($resp_consregistro['registro'] == '0') {
                if(true){
                    $resp_pagofactura = $mod_pagos->insertarPagospendientes($est_id, 'ME', $pfes_referencia, $pfes_banco, $fpag_id, $pfes_valor_pago, $pfes_fecha_pago, $pfes_observacion, $imagen, $usuario);

                    $pfes_id = $resp_pagofactura;

                    $tituloMensaje = Yii::t("interesado", "Pago Recibido UTEG");
                    $asunto        = Yii::t("interesado", "Pago Recibido UTEG");

                    /*
                    echo($data["valor"]);echo("********");
                    echo($data["valor_check"]);echo("********");
                    echo($data["contador_cuotas"]);echo("********");
                    echo($data["cuotas_check"]);echo("********");
                    */

                    if($data["valor"] > $data["valor_check"]){
                        if($data["contador_cuotas"] == 1 || $data["contador_cuotas"] == $data["cuotas_check"]){
                            //if($fpag_id == 1){
                                $mod_cruce = new Cruce();

                                $resp_mod_cruce  = $mod_cruce->insertarCruce($est_id,  //si
                                                                             $pfes_id, //si
                                                                             "", //
                                                                             $pfes_fecha_pago,
                                                                             $data["valor"] - $data["valor_check"],
                                                                             $data["valor"] - $data["valor_check"],
                                                                             "A",
                                                                             $usuario);

                            //}
                        }
                    }

                    if($mod_id == 1){ //online
                        $telefono = Yii::$app->params["tlfonline"];
                        //$bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula, "[[telefono]]" => $telefono ), Yii::$app->language);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanatoonline"] => "Decanato Online"], $asunto, $bodypmatricula);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariaonline1"] => "Secretaria Online"], $asunto, $bodypmatricula);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariaonline2"] => "Secretaria Online"], $asunto, $bodypmatricula);
                    }else if($mod_id == 2){//presencial
                        $telefono = Yii::$app->params["tlfpresencial"];
                        //$bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula, "[[telefono]]" => $telefono ), Yii::$app->language);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanogradopresencial"] => "Decanato Presencial"], $asunto, $bodypmatricula);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariagrado1"] => "Secretaria Presencial"], $asunto, $bodypmatricula);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariagrado2"] => "Secretaria Presencial"], $asunto, $bodypmatricula);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["coordinadorgrado"] => "Coordinador Presencial"], $asunto, $bodypmatricula);
                    }else{
                        $telefono = Yii::$app->params["tlfdistancia"];
                        //$bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula, "[[telefono]]" => $telefono ), Yii::$app->language);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanogradosemi"] => "Decanato SemiPresencial"], $asunto, $bodypmatricula);
                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariasemi"]  => "Secretaria SemiPresencial"], $asunto, $bodypmatricula);
                    }
                    $errorCorreo = '';
                    try{
                        if($fpag_id == 1){
                            $body = Utilities::getMailMessage("pagostripe",
                                                              array("[[user]]"     => $name,
                                                                    "[[telefono]]" => $telefono),
                                                              Yii::$app->language);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["contactoEmail"], [$email => $name], $asunto, $body);
                            $bodycolec = Utilities::getMailMessage("colecturiatc",
                                                                   array("[[ident]]"   => $data['txt_cedula'],
                                                                         "[[user]]"    => $name,
                                                                         "[[email]]"   => $email,
                                                                         "[[valor]]"   => $paidAmount,
                                                                         "[[tarjeta]]" => $tarjeta,
                                                                         "[[tipo]]"    => $tipo,
                                                                         "[[digitos]]" => $digito,
                                                                         "[[recibo]]"  => $recibo,),
                                                                   Yii::$app->language);
                        }else{
                            $body = Utilities::getMailMessage("pago",
                                                              array("[[user]]"     => $name,
                                                                    "[[telefono]]" => $telefono),
                                                              Yii::$app->language);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["contactoEmail"], [$email => $name], $asunto, $body);
                            $bodycolec = Utilities::getMailMessage("colecturia", array("[[user]]" => $name), Yii::$app->language);
                        }

                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"]     , [Yii::$app->params["supercolecturia"] => "Colecturia"], $asunto, $bodycolec);
                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["supercolecturia"], [Yii::$app->params["colecturia"]      => "Supervisor Colecturia"], $asunto, $bodycolec);

                    }catch (Exception $ex2) {
                        $errorCorreo = $ex2;
                    }

                    if ($resp_pagofactura) {

                        // se graba el detalle
                        $pagados = explode("*", $pagado); //PAGADOS
                        $x = 0;
                        foreach ($pagados as $datos) {
                            //  consultar la informacion seleccionada de cuota factura
                            $parametro = explode(";", $pagados[$x]);
                            //$resp_consfactura = $mod_pagos->consultarPagospendientesp($personaData['per_cedula'], $parametro[0], $parametro[1]);
                            // insertar el detalle
                            //$resp_detpagofactura = $mod_pagos->insertarDetpagospendientes($resp_pagofactura, $resp_consfactura['tipofactura'], $resp_consfactura['factura'], $resp_consfactura['descripcion'], $parametro[2], $resp_consfactura['fecha'], $resp_consfactura['saldo'], $resp_consfactura['numcuota'], $resp_consfactura['valorcuota'], $resp_consfactura['fechavence'], $usuario);

                            //CODIGO TEMPORAL
                            //$mod_ccartera     = new CargaCartera();
                            $resp_consfactura = $mod_ccartera->consultarPagospendientesp($personaData['per_cedula'], $parametro[0], $parametro[1]);

                            //\app\models\Utilities::putMessageLogFile('resp_consfactura ...: ' . print_r($resp_consfactura,true));
                            //\app\models\Utilities::putMessageLogFile('valor_pagado ...: ' . print_r($valor_pagado,true));
                            //Pregunto si el valor pagado es mayor a cero
                            if ($valor_pagado > 0) {
                                $cargo = CargaCartera::findOne($resp_consfactura['ccar_id']);

                                $cuota = $resp_consfactura['ccar_valor_cuota'];
                                $abono = $resp_consfactura['abono'];
                                $saldo = $cuota - $abono;

                                //\app\models\Utilities::putMessageLogFile('cuota ...: ' . print_r($cuota,true));
                                //\app\models\Utilities::putMessageLogFile('abono ...: ' . print_r($abono,true));
                                //\app\models\Utilities::putMessageLogFile('saldo ...: ' . print_r($saldo,true));

                                //si el valor pagado es mayor al saldo
                                if ($valor_pagado >= $saldo ) {
                                     //\app\models\Utilities::putMessageLogFile('if ($valor_pagado >= $saldo )');
                                    $cargo->ccar_abono = $cargo->ccar_abono + $saldo;
                                    $valor_pagado      = $valor_pagado - $saldo;
                                    //\app\models\Utilities::putMessageLogFile('cargo->ccar_abono ...: ' . print_r($cargo->ccar_abono,true));
                                    //\app\models\Utilities::putMessageLogFile('valor_pagado ...: ' . print_r($valor_pagado,true));
                                    //// ini gap - cambio solicitado para q ponga en estado cancelado...
                                    //// carga cartera cuando se haga transferencias o deposito
                                    $valor_cuota_cancelada = $cuota - ($saldo + $abono);

                                    //\app\models\Utilities::putMessageLogFile('valor_cuota_cancelada ...: ' . print_r($valor_cuota_cancelada,true));

                                    if($valor_cuota_cancelada <= 0)
                                        $cargo->ccar_estado_cancela = 'C';
                                    /////////////////////
                                    /*if($fpag_id == 1){
                                        $valor_cuota_cancelada = $cuota - ($saldo + $abono);

                                        //\app\models\Utilities::putMessageLogFile('valor_cuota_cancelada ...: ' . print_r($valor_cuota_cancelada,true));

                                        if($valor_cuota_cancelada <= 0)
                                            $cargo->ccar_estado_cancela = 'C';
                                    }//if
                                    */

                                    $cargo->ccar_fecha_modificacion = $fecha;
                                    $cargo->ccar_usu_modifica       = $usuario;
                                }else {
                                    //\app\models\Utilities::putMessageLogFile('else');
                                    //\app\models\Utilities::putMessageLogFile('cargo->ccar_abono ...: ' . print_r($cargo->ccar_abono,true));
                                    //\app\models\Utilities::putMessageLogFile('valor_pagado...: ' . print_r($valor_pagado,true));
                                    //if($cuota != $abono){
                                    $cargo->ccar_abono = $cargo->ccar_abono + $valor_pagado;
                                    $valor_pagado      = $valor_pagado - $cargo->ccar_abono;

                                    //// ini gap - cambio solicitado para q ponga en estado cancelado...
                                    //// carga cartera cuando se haga transferencias o deposito

                                    if($cargo->ccar_abono == $cuota)
                                            $cargo->ccar_estado_cancela  = 'C';

                                    /*
                                    if($fpag_id == 1){
                                        if($cargo->ccar_abono == $cuota)
                                            $cargo->ccar_estado_cancela  = 'C';
                                    }else{
                                        $cargo->ccar_estado_cancela     = 'N';

                                    }
                                    */
                                    $cargo->ccar_fecha_modificacion = $fecha;
                                    $cargo->ccar_usu_modifica       = $usuario;

                                }//else

                                if($valor_pagado < 0)
                                    $valor_pagado = 0;

                                $cargo->save();
                                // SI ES USUARIO COLECTURIA DEBE GUARDAR ESTADO COMO 'C', SI EL PAGO
                                // DE LA CUOTA ES COMPLETO
                                if (!empty($perids)) {
                                    if($cargo->ccar_abono == $cuota){

                                        $dpfa_estado_pago = 2;
                                        $dpfa_estado_financiero = 'C';

                                    }else{
                                            // Si los es abono queda pendiente
                                            //$dpfa_estado_pago = 1;
                                            //$dpfa_estado_financiero = 'N';
                                            //Martes 19/11/2021 se pidio aunque sea abono este
                                            // estados revisión y financiero, aprobado y cancelado
                                            $dpfa_estado_pago = 2;
                                            $dpfa_estado_financiero = 'C';
                                        }
                                } /*else{
                                $dpfa_estado_pago       = 1;
                                $dpfa_estado_financiero = 'N';
                                }*/
                                // insertar el detalle
                                $descripciondet      = 'Cuota '. str_replace('/',' ',$resp_consfactura['cuota']) . '- Abono con el valor de ' .$cargo->ccar_abono ;
                                $resp_detpagofactura = $mod_pagos->insertarDetpagospendientes($resp_pagofactura,
                                                                                              $resp_consfactura['ccar_tipo_documento'],
                                                                                              $resp_consfactura['NUM_NOF'],
                                                                                              $descripciondet,
                                                                                              $parametro[2],
                                                                                              $resp_consfactura['F_SUS_D'],
                                                                                              is_null($resp_consfactura['SALDO'])?0:round($resp_consfactura['SALDO'], 2),
                                                                                              str_replace('/',' ',$resp_consfactura['cuota']),
                                                                                              //$resp_consfactura['ccar_valor_cuota'],
                                                                                              $cargo->ccar_abono,
                                                                                              $resp_consfactura['F_VEN_D'],
                                                                                              $dpfa_estado_pago,
                                                                                              $dpfa_estado_financiero,
                                                                                              $usuario);
                            }//if


                            /*

                            if($data["formapago"]==6){
                                $cargo->ccar_estado_cancela = 'C';
                                $cargo->ccar_fecha_modificacion = $fecha;
                                $cargo->ccar_usu_modifica = $usuario;
                                $cargo->save();
                            }
                            */

                            $x++;

                             //\app\models\Utilities::putMessageLogFile('****************************************');
                        }

                        //if ($resp_detpagofactura) {
                            // AQUI OBTENER EL VALOR QUE DEVUELVE $resp_detpagofactura GVZ SI ES COLECTURIA Y DEJAR YA REVISADO
                            \app\models\Utilities::putMessageLogFile('resp_detpagofactura ' . $resp_detpagofactura);
                            \app\models\Utilities::putMessageLogFile('perids dentro detpago ' . $perids);
                            $transaction->commit();
                            $mod_pagos = new PagosFacturaEstudiante();
                            if (!empty($perids)) { // es usuario colecturia
                            //Consultar los datos del pago
                            \app\models\Utilities::putMessageLogFile('entre if detpago ' . $perids);
                                $datos = $mod_pagos->consultarPago($resp_detpagofactura);
                                $mod_id  = $datos['mod_id'];
                                $nombres = $datos['estudiante'];
                                $cedula  = $datos['identificacion'];
                                $resultado = 2; // Aprobado
                                $observacion = '';
                                //\app\models\Utilities::putMessageLogFile('datos ' . $datos['mod_id'] . ' '. $datos['estudiante'] . ' '.  $datos['identificacion']);
                                $cartera = $mod_pagos->buscarIdCartera($resp_detpagofactura);
                                $id_cartera = $cartera[0]['ccar_id'];
                                //\app\models\Utilities::putMessageLogFile('id_cartera ' . $cartera[0]['ccar_id']);
                                //if ($respago) {
                                    //\app\models\Utilities::putMessageLogFile('respago 2 ' . $respago);
                                    $correo_estudiante = $datos['per_correo'];
                                    $user = $datos['estudiante'];
                                    $tituloMensaje = 'Pagos en Línea';
                                    $asunto = 'Pagos en Línea';
                                    $body = Utilities::getMailMessage("pagoaprobado", array(
                                                                   "[[user]]" => $user,
                                                                   "[[factura]]" => $datos['dpfa_factura'],
                                                                       ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                                                       Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                                    $message = array(
                                    "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                                    "title" => Yii::t('jslang', 'Success'),
                                    );
                                /*}else {
                                        $message = ["info" => Yii::t('exception', 'Error al grabar.')];
                                        echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
                                      }*/
                            }

                            /********** INI - CODIGO EDUCATIVA PARA DESPUES DEL PAGO ACTIVAR EL EXAMEN *************************/
                            //Aqui trae al estudiante y sus codigos
                            $estudiantes = $mod_pagos->traerestudianteautorizados($est_id);

                            if (count($estudiantes) > 0) {
                                $clientWS = Utilities::getWseducativa();

                                if($clientWS != 0){
                                    //El siguiente for es para revisar examen uno a uno
                                    for ($i = 0; $i < count($estudiantes); $i++) {
                                        $est_id                  = $estudiantes[$i]['est_id'];
                                        //Usuario Educativa
                                        $uedu_usuario            = $estudiantes[$i]['uedu_usuario'];
                                        //Curso Educativa
                                        $cedu_id                 = $estudiantes[$i]['cedu_id'];
                                        //Materia
                                        $id_grupo                = $estudiantes[$i]['cedu_asi_id']; 
                                        //Estado si esta autorizado o no en cartera
                                        $pago                    = $estudiantes[$i]['pago']; 
                                        //Curso Educativa Estudiante
                                        $ceest_id                = $estudiantes[$i]['ceest_id']; 
                                        $ceest_estado_bloqueo    = $estudiantes[$i]['ceest_estado_bloqueo']; 
                                        $ceest_codigo_evaluacion = $estudiantes[$i]['ceest_codigo_evaluacion'];
                                        $unidad                  = $estudiantes[$i]['ceest_codigo_evaluacion'];

                                        //Si el estudiante esta autorizado en cartera y tiene evaluacion procedo con la activacion
                                        //a travez del web service
                                        if($pago == 'Autorizado' && $ceest_codigo_evaluacion != ''){
                                            $method = 'asignar_usuarios_alcance_prg_items';

                                            $args   = Array('asignar_usuario_item' => Array('id_usuario'  => $uedu_usuario, 
                                                                                            'id_prg_item' => $ceest_codigo_evaluacion));

                                            $result = $client->__call( $method, Array( $args ) );

                                            $mod_pagos->modificarEstadobloqueo($ceest_id, 'A', 1);

                                            if($ceest_estado_bloqueo == 'B')
                                                 $mod_pagos->registrarcambiohistorial($ceest_id, $pago, $ceest_estado_bloqueo, "A", $unidad);

                                        }else{
                                            $mod_pagos->modificarEstadobloqueo($ceest_id, 'B', 1);
                                            if($ceest_estado_bloqueo == 'A')
                                                $mod_pagos->registrarcambiohistorial($ceest_id, $pago, $ceest_estado_bloqueo, "B", '');
                                        }
                                        //array_push($noactualizados,$est_id);
                                    }//for por estudiante
                                }else{
                                    $message = array(
                                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. Pero no se actualizo Educativa por erro de conexion con webservice"),
                                        "title" => Yii::t('jslang', 'Success'),
                                    );
                                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                                    return;
                                }
                                //Si no entra por el if hubo error
                                //de conexion con el webservice,
                                //revisar la tabla de log de errores.
                            }//if
                            /********** FIN - CODIGO EDUCATIVA PARA DESPUES DEL PAGO ACTIVAR EL EXAMEN *************************/
                            $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                            "title" => Yii::t('jslang', 'Success'),
                             );
                            echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);

                        //}//if
                    }else{
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura 1." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                            "error" => $resp_pagofactura,
                            "errorCorreo" => $errorCorreo
                        );
                        echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }
                }else{
                    Utilities::putMessageLogFile('dentro else:'. $cuota_pagada);
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La cuota " . $cuota_pagada . "  ya esta cargado el pago, espere porfavor su revisión"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }//else
            }catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura." . $ex),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }//if
    }//function actionSavepagopendiente

    public function actionExpexcelfacpendiente() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "L", "O", "P");
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Bill"),
            financiero::t("Pagos", "Monthly fee"),
            Yii::t("formulario", "Registration Date"),
            financiero::t("Pagos", "Pass"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Financial Status"),
            financiero::t("Pagos", "Payment id"),
            financiero::t("Pagos", "Concept"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];
        $arrSearch["estadofinanciero"] = $data['estadofinanciero'];
        $arrSearch["concepto"] = $data['concepto'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagos(array(), true);
        } else {
            $arrData = $mod_pagos->getPagos($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "List Payment Pending");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdffacpendiente() {
        $report = new ExportFile();
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Bill"),
            financiero::t("Pagos", "Monthly fee"),
            Yii::t("formulario", "Registration Date"),
            financiero::t("Pagos", "Pass"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Financial Status"),
            financiero::t("Pagos", "Payment id"),
            financiero::t("Pagos", "Concept"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];
        $arrSearch["estadofinanciero"] = $data['estadofinanciero'];
        $arrSearch["concepto"] = $data['concepto'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagos(array(), true);
        } else {
            $arrData = $mod_pagos->getPagos($arrSearch, true);
        }

        $this->view->title = financiero::t("Pagos", "List Payment Pending");
        ; // Titulo del reporte
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionUpdatepago() {
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modcanal = new Oportunidad();
        $mod_fpago = new FormaPago();
        $mod_pagos = new PagosFacturaEstudiante();
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($personaData['uaca_id'], 1);
        if (($personaData['uaca_id'] == 1) or ( $personaData['uaca_id'] == 2)) {
            $carrera = $modcanal->consultarCarreraModalidad($personaData['uaca_id'], $personaData['mod_id']);
        } else {
            $carrera = $modestudio->consultarCursoModalidad($personaData['uaca_id'], $personaData['mod_id']); // tomar id de impresa
        }
        $arr_forma_pago = $mod_fpago->consultarFormaPagosaldo();
        // se debe capturar desde url borrar al hacer las pruebas
        $pagodetalle = $mod_pagos->consultarPago($dpfa_id);
        return $this->render('updatepago', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    'pagodetalle' => $pagodetalle,
        ]);
    }

    public function actionModificarpagopendiente() {
        //online que sube doc capturar asi el id de la persona
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Parametros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosfinanciero/" . $data["per_id"] . "/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }
            $arrIm = explode(".", basename($data["documento"]));
            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
            $imagen = $arrIm[0] . "." . $typeFile;
            $pfes_referencia = $data["referencia"];
            $fpag_id = $data["formapago"];
            $pfes_valor_pago = $data["valor"];
            $pfes_fecha_pago = $data["fechapago"];
            $pfes_observacion = $data["observacion"];
            $est_id = $data["estid"];
            $pfes_id = $data["pfes_id"];
            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            try {
                //$usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
                //Utilities::putMessageLogFile('usuario:'. $usuario);
                $resp_pagofactura = $mod_pagos->modificarPagosrechazado($pfes_id, $est_id, $pfes_referencia, $fpag_id, $pfes_valor_pago, $pfes_fecha_pago, $pfes_observacion, $imagen);
                if ($resp_pagofactura) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido modificada. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al modificar pago factura1." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al modificar pago factura2." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionConsultarevision() {
        $dpfa_id       = base64_decode($_GET["dpfa_id"]);
        $mod_pagos     = new PagosFacturaEstudiante();
        $mod_unidad    = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $model         = $mod_pagos->consultarPago($dpfa_id);
        $arr_unidadac  = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('viewrevisionpago', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoRechazo(),
                    'arrObservacion' => array("0" => "Seleccione", "Archivo Ilegible" => "Archivo Ilegible", "Archivo no corresponde al pago" => "Archivo no corresponde al pago", "Archivo con Error" => "Archivo con Error", "Valor pagado no cubre factura" => "Valor pagado no cubre factura", "Archivo duplicado" => "Archivo duplicado"),
        ]);
    }//function actionConsultarevision

    public function actionPagos() {
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $mod_pagos = new PagosFacturaEstudiante();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $resp_pago = $mod_pagos->getPagosxestudiante($arrSearch, false, $per_idsession);
            return $this->renderPartial('_index-grid_pagosfacturas', [
                        "model" => $resp_pago,
            ]);
        }
        $model = $mod_pagos->getPagosxestudiante(null, false, $per_idsession);
        return $this->render('index_pagosfacturas', [
                    'model' => $model,
        ]);
    }

    public function actionDetallepagosfactura() {
        $factura = base64_decode($_GET["pfes_id"]);
        $per_id = @Yii::$app->session->get("PB_perid");
        $mod_pagos = new PagosFacturaEstudiante();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $data = Yii::$app->request->get();
        $dataEstudiante = $mod_pagos->consultarDatosestudiante($per_id);
        $model = $mod_pagos->getPagosDetxestudiante(null, false, $factura);

        return $this->render('index_pagos', [
                    'model' => $model,
                    'data' => $dataEstudiante,
        ]);
    }

    public function actionPagosfactura() {
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                if (($data["unidada"] == 1) or ( $data["unidada"] == 2)) {
                    $modalidad = $mod_modalidad->consultarModalidad($data["unidada"], 1);
                } else {
                    $modalidad = $modestudio->consultarModalidadModestudio();
                }
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["estadopago"] = $data['estadopago'];
            $resp_pago = $mod_pagos->getPagosestudiante($arrSearch, false);
            return $this->renderPartial('_index-grid_pagofactura', [
                        "model" => $resp_pago,
            ]);
        }
        $model = $mod_pagos->getPagosestudiante(null, false);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('index_pagofactura', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_estado' => $this->estados(),
                    'arr_estado_financiero' => $this->estadoFinanciero(),
        ]);
    }

    public function actionExpexcelpagfactura() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "L");
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            financiero::t("Pagos", "Amount Paid"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Payment id"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagosestudiante(array(), true);
        } else {
            $arrData = $mod_pagos->getPagosestudiante($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "List Payment") . ' ' . financiero::t("Pagos", "Bill");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfpagfactura() {
        $report = new ExportFile();
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            financiero::t("Pagos", "Amount Paid"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Payment id"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagosestudiante(array(), true);
        } else {
            $arrData = $mod_pagos->getPagosestudiante($arrSearch, true);
        }

        $this->view->title = financiero::t("Pagos", "List Payment") . ' ' . financiero::t("Pagos", "Bill");
        ; // Titulo del reporte
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionReversar() {
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $model = $mod_pagos->consultarPago($dpfa_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('reversar', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoReverso(),
        ]);
    }

    public function actionCargarcartera() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        //$usu_id = Yii::$app->session->get('PB_iduser');
        $mod_cartera = new CargaCartera();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "cartera/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                try {
                ini_set('memory_limit', '256M');
                //\app\models\Utilities::putMessageLogFile('Files ...: ' . $data["archivo"]);
                $carga_archivo = $mod_cartera->CargarArchivocartera($data["archivo"]);
                if ($carga_archivo['status']) {
                    //\app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $arroout['noalumno']);
                    if (!empty($carga_archivo['noalumno'])){
                    $noalumno = ' Se encontró las cédulas '. $carga_archivo['noalumno'] . ' que no pertencen a estudiantes por ende no se cargaron. ';
                    }
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data'] .  $noalumno),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                } else {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo. " . $carga_archivo['message']),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
                return;
            } catch (Exception $ex) {
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Error al procesar el archivo.'),
                    'title' => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), true, $message);
            }
        }
        } else {
            return $this->render('cargarcartera', []);
        }
    }
    public function actionDownloadplantilla() {
        $file = 'plantillaCargacartera.xlsx';
                $route = str_replace("../", "", $file);
                $url_file = Yii::$app->basePath . "/uploads/cartera/" . $route;
                $arrfile = explode(".", $url_file);
                $typeImage = $arrfile[count($arrfile) - 1];
                if (file_exists($url_file)) {
                    if (strtolower($typeImage) == "xlsx") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/xlsx");
                        header('Content-Disposition: attachment; filename="plantillacartera_' . time() . '.xlsx";');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_file));
                        readfile($url_file);
                    }
                }
    }

    public function actionReversarestado() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            $dfac_id = $data["dpfa_id"];
            $estado_pago = $data["resultado"];
            $observacion = ucfirst(mb_strtolower($data['observacion'], 'UTF-8'));
            $estado_financiero = null;
            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            try {
                $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
                // enviar a modificar estados
                $resp_pagofactura = $mod_pagos->grabaReverso($dfac_id, $estado_pago, $observacion);
               if ($resp_pagofactura) {
                    $fecha = date(Yii::$app->params["dateTimeByDefault"]);
                    $cartera = $mod_pagos->buscarIdCartera($dfac_id);
                    $id_cartera = $cartera[0]['ccar_id'];
                    $cargo = CargaCartera::findOne($id_cartera);
                    $cargo->ccar_estado_cancela = 'N';
                    $cargo->ccar_fecha_modificacion = $fecha;
                    $cargo->ccar_usu_modifica = $usuario;
                    $cargo->save();

                if ($cargo) {
                    $datos = $mod_pagos->consultarPago($dfac_id);
                    // \app\models\Utilities::putMessageLogFile('obsere: ' . $datos['dpfa_observacion_reverso']);
                    $correo_estudiante = $datos['per_correo'];
                    $tituloMensaje = 'Pagos en Línea';
                    $asunto = 'Pagos en Línea';
                    $body = Utilities::getMailMessage("pagoreversado", array(
                    "[[user]]" =>  $datos['estudiante'],
                    "[[factura]]" => $datos['dpfa_factura'],
                    "[[cuota]]" => $datos['dpfa_num_cuota'],
                    "[[valor]]" => $datos['valor_cuota'],
                    "[[observacion]]" => $observacion,
                    ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                }
                    $transaction->commit();
                    $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);

                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al reversar pago factura." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al reversar pago factura." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionIndex() {
        //$dpfa_id = base64_decode($_GET["dpfa_id"]);
        $mod_cartera = new CargaCartera();
        $mod_periodo = new PlanificacionEstudiante();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $model = $mod_cartera->consultarCarteraEstudiantes($arrSearch);
            return $this->render('index-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $mod_cartera->consultarCarteraEstudiantes(null);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $busquedalumno = $mod_periodo->busquedaEstudianteplanificacion();
        return $this->render('index', [
            'model' => $model,
            'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),
        ]);
    }
    public function actionExpexcelestcartera() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "L");
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Names"),
            Yii::t("formulario", "Email"),
            academico::t("Academico", "Enrollment Number"),
        );
        $mod_cartera = new CargaCartera();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_cartera->consultarCarteraEstudiantes(array(), true);
        } else {
            $arrData = $mod_cartera->consultarCarteraEstudiantes($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "Estudiantes Pagos Cartera");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfestcartera() {
        $report = new ExportFile();
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Names"),
            Yii::t("formulario", "Email"),
            academico::t("Academico", "Enrollment Number"),
        );
        $mod_cartera = new CargaCartera();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_cartera->consultarCarteraEstudiantes(array(), true);
        } else {
            $arrData = $mod_cartera->consultarCarteraEstudiantes($arrSearch, true);
        }

        $this->view->title = financiero::t("Pagos", "Estudiantes Pagos Cartera");
        ; // Titulo del reporte
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }
    public function actionDeletecargacartera() {
        $mod_cartera = new CargaCartera();
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $ccar_id = $data["ccar_id"];
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            try {
                //\app\models\Utilities::putMessageLogFile('cartera id..: ' . $ccar_id);
                $resp_estado = $mod_cartera->eliminarCargaCartera($ccar_id, $usu_autenticado, $fecha);
                if ($resp_estado) {
                    $exito = '1';
                }
                if ($exito) {
                    //Realizar accion
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha eliminado el registro de cartera."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al eliminar. "),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al realizar la acción. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }
}
