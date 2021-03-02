<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\Utilities;
use app\models\ExportFile;
use app\models\Persona;
use yii\helpers\Url;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\modules\financiero\models\FormaPago;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\Module as academico;
use app\modules\academico\models\Especies;
use app\models\Empresa;
use app\modules\academico\Module as Especie;
use app\modules\academico\models\CertificadosGeneradas;

Academico::registerTranslations();
Especie::registerTranslations();

class EspeciesController extends \app\components\CController {

    /**
     * Function controller to /especies/index
     * @author Byron Villacreses <developer@gmail.com>
     * @param
     * @return
     */
    public $pdf_cla_acceso = "";

    private function estadoPagos() {
        return [
            '0' => Yii::t("formulario", "Todos"),
            '1' => Yii::t("formulario", "Pendiente"),
            '2' => Yii::t("formulario", "Pago Solicitud - Rechazado"),
            '3' => Yii::t("formulario", "Generado"),
        ];
    }

    private function estadoPagosColecturia() {
        return [
            '0' => Yii::t("formulario", "Todos"),
            '2' => Yii::t("formulario", "Pago Solicitud - Rechazado"),
            '3' => Yii::t("formulario", "Generar"),
        ];
    }

    public function actionRevisarpago() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
        $mod_fpago = new FormaPago();
        $est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $arr_forma_pago = $especiesADO->getFormaPago();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["f_estado"] = $data['f_estado'];
            $arrSearch["f_pago"] = $data['f_pago'];
            $arrSearch["search"] = $data['search'];
            $resp_pago = $especiesADO->getSolicitudesAlumnos($est_id, $arrSearch, false);
            return $this->renderPartial('_revisar-grid', [
                        "model" => $resp_pago,
            ]);
        } else {
            
        }

        $personaData = $especiesADO->consultaDatosEstudiante($per_id);
        $model = $especiesADO->getSolicitudesAlumnos($est_id, null, false);

        return $this->render('revisarpago', [
                    'model' => $model,
                    'personalData' => $personaData,
                    'arrEstados' => $this->estadoPagos(),
                    'arr_forma_pago' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => "Todos"]], $arr_forma_pago), "Ids", "Nombre"),
        ]);
    }

    public function actionSolicitudalumno() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
        $mod_fpago = new FormaPago();
        $est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $arr_forma_pago = $especiesADO->getFormaPago();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["f_estado"] = $data['f_estado'];
            $arrSearch["f_pago"] = $data['f_pago'];
            //$arrSearch["search"] = $data['search'];
            $resp_pago = $especiesADO->getSolicitudesAlumnos($est_id, $arrSearch, false);
            return $this->renderPartial('_solicitudalumnoGrid', [
                        "model" => $resp_pago,
            ]);
        } else {
            //$resp_pago = $especiesADO->getSolicitudesAlumnos(null, $resp_gruporol["grol_id"]);
        }

        $personaData = $especiesADO->consultaDatosEstudiante($per_id);
        $model = $especiesADO->getSolicitudesAlumnos($est_id, null, false);

        return $this->render('solicitudalumno', [
                    'model' => $model,
                    'personalData' => $personaData,
                    'arrEstados' => $this->estadoPagos(),
                    'arr_forma_pago' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => "Todos"]], $arr_forma_pago), "Ids", "Nombre"),
        ]);
    }

    public function actionNew() {
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
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
            if (isset($data["gettramite"])) {
                $tramite = $especiesADO->getTramite($data["unidad"]);
                $message = [
                    "tramite" => $tramite,
                ];
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getespecie"])) {
                $especies = $especiesADO::getTramiteEspecie($data['tra_id']);
                $message = [
                    "especies" => $especies,
                ];
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getDataespecie"])) {
                $especies = $especiesADO->getDataEspecie($data["esp_id"]);
                $message = array("especies" => $especies);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        $arr_tramite = $especiesADO->getTramite($personaData["uaca_id"]);
        $arr_especies = $especiesADO->getTramiteEspecie($arr_tramite[0]["Ids"]);
        return $this->render('new', [
                    'arr_persona' => $personaData,
                    'arr_tramite' => ArrayHelper::map($arr_tramite, "id", "name"),
                    'arr_especies' => ArrayHelper::map($arr_especies, "id", "name"),
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
        ]);
    }

    //PEDIDOS REALIZADOS
    public function actionSave() {
        $per_id = @Yii::$app->session->get("PB_perid");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            //Utilities::putMessageLogFile($data['DTS_CAB']['est_id']);
            $especiesADO = new Especies();
            $periodo_academico = $especiesADO->consultarPeriodoactivo();
            Utilities::putMessageLogFile('dasd' . $periodo_academico['paca_id']);
            $pagodia = $especiesADO->consultarPagodia($periodo_academico['paca_id'], $data['DTS_CAB']['est_id']);
            Utilities::putMessageLogFile('csdfsd' . $pagodia['eppa_estado_pago']);
            if ($pagodia['eppa_estado_pago'] > "0") {
                $dts_Cab = isset($data['DTS_CAB']) ? $data['DTS_CAB'] : array();
                $dts_Det = isset($data['DTS_DET']) ? $data['DTS_DET'] : array();
                $accion = isset($data['ACCION']) ? $data['ACCION'] : "";
                if ($accion == "Create") {
                    $resul = $especiesADO->insertarLista($dts_Cab, $dts_Det);
                } else {
                    //Opcion para actualizar
                    //$PedId = isset($_POST['PED_ID']) ? $_POST['PED_ID'] : 0;
                    //$arroout = $model->actualizarLista($PedId,$tieId,$total,$dts_Lista);
                }
                //Utilities::putMessageLogFile($resul);
                if ($resul['status']) {
                    $message = ["info" => Yii::t('exception', 'La infomación ha sido grabada. ')];
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message, $resul);
                } else {
                    $message = ["info" => Yii::t('exception', 'Error al grabar.')];
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
                }
                return;
            } else {
                $message = ["info" => Yii::t('exception', 'No puede crear solicitud porque no esta al dia en los pagos.')];
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
            }
            return;
        }
    }

    public function actionCargarpago() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        //Utilities::putMessageLogFile($ids);
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
                //Utilities::putMessageLogFile($data["name_file"]);
                //if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
                if ($typeFile == 'jpg' || $typeFile == 'png' || $typeFile == 'pdf') {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "especies/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                $carga_archivo = $especiesADO->CargarArchivo($data["archivo"], $data["csol_id"]);
                $data_especie = $especiesADO->consultaSolicitudexrubro($data["csol_id"]);
                $especie_tramite = explode(",", $data_especie["especies"]);
                for ($a = 0; $a < count($especie_tramite); $a++) {
                    $datos_especie .= $especie_tramite[$a] . ', ';
                }
                if ($carga_archivo['status']) {
                    // enviar correo estudiante
                    $correo = $data_persona["per_correo"];
                    $user = $data_persona["per_pri_nombre"] . " " . $data_persona["per_pri_apellido"];
                    $tituloMensaje = 'Adquisición de Especie Valorada en Línea';
                    $asunto = 'Adquisición de Especie Valorada en Línea';
                    $body = Utilities::getMailMessage("cargapagoalumno", array(
                                "[[user]]" => $user,
                                "[[tipo_especie]]" => substr($datos_especie, 0, -2)), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    Utilities::sendEmail(
                            $tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $user], $asunto, $body);
                    // enviar correo colecturia
                    //$user = $data_persona["per_pri_nombre"] . " ". $data_persona["per_pri_apellido"];
                    //$tituloMensaje = 'Adquisición de Especie Valorada en Línea';
                    //$asunto = 'Adquisición de Especie Valorada en Línea';

                    $bodies = Utilities::getMailMessage("cargapagocolecturia", array(
                                "[[user]]" => $user,
                                "[[tipo_especie]]" => substr($datos_especie, 0, -2)), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    Utilities::sendEmail(
                            $tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["colecturia"] => "Colecturia"], $asunto, $bodies);

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
        }


        $personaData = $especiesADO->consultaDatosEstudiante($per_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        $model = $especiesADO->getSolicitudesAlumnos($est_id, null, false);
        return $this->render('cargarpago', [
                    'model' => $model,
                    'arr_persona' => $personaData,
                    'cab_solicitud' => $especiesADO->consultarCabSolicitud($ids),
                    'det_solicitud' => json_encode($especiesADO->consultarDetSolicitud($ids)),
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoPagos(),
        ]);
    }

    public function actionAutorizarpago() {
        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        //Utilities::putMessageLogFile($ids);
        $est_id = base64_decode($_GET['est_id']);
        $especiesADO = new Especies();
        //$est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            //$especiesADO = new Especies();
            $csol_id = isset($data['csol_id']) ? $data['csol_id'] : 0;
            $estado = isset($data['estado']) ? $data['estado'] : 0;
            $accion = isset($data['accion']) ? $data['accion'] : "";
            $observacion = isset($data['observacion']) ? $data['observacion'] : "";
            $estud_id = $data['est_id'];
            if ($estado==2 and $observacion==""){
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Seleccione una observación."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);                
            }
            if ($accion == "AutorizaPago") {
                // \app\models\Utilities::putMessageLogFile('observacion:' . $observacion);                
                $resul = $especiesADO->autorizarSolicitud($csol_id, $estado, $observacion);
            } else {
                //Opcion para actualizar
                //$PedId = isset($_POST['PED_ID']) ? $_POST['PED_ID'] : 0;
                //$arroout = $model->actualizarLista($PedId,$tieId,$total,$dts_Lista);
            }
            Utilities::putMessageLogFile($resul);
            if ($resul['status']) {
                $correos = null;
                $especiesADO = new Especies();
                $datasolicitud = $especiesADO->consultarCabSolicitud($csol_id);
                $persona = $especiesADO->consultaPeridxestid($estud_id);
                $data_persona = $especiesADO->consultaDatosEstudiante($persona["per_id"]); //aqui enviar per_id
                $correo = $data_persona["per_correo"];
                $user = $data_persona["per_pri_nombre"] . " " . $data_persona["per_pri_apellido"];
                $tituloMensaje = 'Adquisición de Especie Valorada en Línea';
                $asunto = 'Adquisición de Especie Valorada en Línea';
                if ($data['estado'] == '3') { //si aprueba un correo.
                    $body = Utilities::getMailMessage("aprobarpagoalumno", array(
                                "[[user]]" => $user,
                                "[[link]]" => "https://asgard.uteg.edu.ec/asgard/"), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $user], $asunto, $body);
                    // si la especie generar certiifcado otro correo
                    $solicitud_correo = $especiesADO->consultarSolicitudXcorreo($csol_id);                    
                    //recorrer en un for pueden venir varias con si y cada uno enviar un correo
                    for ($a = 0; $a < count($solicitud_correo); $a++) {                        
                        $tituloMensaje = 'Especie genera certificado';
                        $asunto = 'Especie genera certificado';
                        $body = Utilities::getMailMessage("especiegencertificado", array(
                                    "[[alumno]]" => $solicitud_correo[$a]["nombres"],
                                    "[[rubro]]" => $solicitud_correo[$a]["esp_rubro"]), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                        if ($solicitud_correo[$a]["esp_emision_certificado"] == 'SI') {
                            // preguntar por el tramite                        
                            switch ($solicitud_correo[$a]["tra_id"]) {
                                case "1": //Academicos
                                    if ($solicitud_correo[$a]["uaca_id"] == "1") {
                                        switch ($solicitud_correo[$a]["mod_id"]) {                                            
                                            case "1": //online  
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanato_online@uteg.edu.ec" => "decano", "secretariaonline@uteg.edu.ec" => "secretaria", "ahernandez@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                
                                                //Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["analistadesarrollo01@uteg.edu.ec" => "decano", "analistadesarrollo01@uteg.edu.ec" => "secretaria", "analistadesarrollo01@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                
                                                break;
                                            case "2": //presencial
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanopresencial@uteg.edu.ec" => "decano", "secretariapresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                    //Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["analistadesarrollo01@uteg.edu.ec" => "decano", "analistadesarrollo01@uteg.edu.ec" => "secretaria", "analistadesarrollo01@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                    
                                                break;
                                            case "3": //semipresencial                                        
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "secretariasemipresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                //Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["analistadesarrollo01@uteg.edu.ec" => "decano", "analistadesarrollo01@uteg.edu.ec" => "secretaria", "analistadesarrollo01@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                break;
                                            case "4": //distancia
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador", "secretariasemipresencial@uteg.edu.ec" => "secretaria"], $asunto, $body);
                                                //Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["analistadesarrollo01@uteg.edu.ec" => "decano", "analistadesarrollo01@uteg.edu.ec" => "coordinador", "analistadesarrollo01@uteg.edu.ec" => "secretaria"], $asunto, $body);
                                                break;
                                        }
                                    } else {
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["olmedo.farfan@uteg.edu.ec" => "decano", "secretariaposgrado@uteg.edu.ec" => "secretaria", "coordinacionposgrado@uteg.edu.ec" => "coordinador", "coordinacionposgradosonline@uteg.edu.ec" => "coordinador1"], $asunto, $body);
                                        //Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["analistadesarrollo01@uteg.edu.ec" => "decano", "analistadesarrollo01@uteg.edu.ec" => "secretaria", "analistadesarrollo01@uteg.edu.ec" => "coordinador", "analistadesarrollo01@uteg.edu.ec" => "coordinador1"], $asunto, $body);
                                        break;
                                    }
                                    break;
                                case "2": //Graduacion
                                    if ($solicitud_correo[$a]["uaca_id"] == "1") {
                                        switch ($solicitud_correo[$a]["mod_id"]) {
                                            case "1": //online  
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanato_online@uteg.edu.ec" => "decano", "secretariaonline@uteg.edu.ec" => "secretaria", "ahernandez@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                                                                
                                                break;
                                            case "2": //presencial
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanopresencial@uteg.edu.ec" => "decano", "secretariapresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                                                                
                                                break;
                                            case "3": //semipresencial                                        
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "secretariasemipresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                
                                                break;
                                            case "4": //distancia
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador", "secretariasemipresencial@uteg.edu.ec" => "secretaria"], $asunto, $body);
                                                break;
                                        }
                                    } else {
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["olmedo.farfan@uteg.edu.ec" => "decano", "secretariaposgrado@uteg.edu.ec" => "secretaria", "coordinacionposgrado@uteg.edu.ec" => "coordinador", "coordinacionposgradosonline@uteg.edu.ec" => "coordinador1"], $asunto, $body);
                                        break;
                                    }
                                    break;
                                case "3": //Secretaria General -- Grado
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["secretariogeneral@yopmail" => "secretario", "coordinacionsecretaria@uteg.edu.ec" => "coordinador", "yazar@uteg.edu.ec" => "yazar"], $asunto, $body);
                                    break;
                                case "4": //Financiero -- Grado
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["colecturia@yopmail" => "colecturia", "supervisorcolecturia@uteg.edu.ec" => "supervisor"], $asunto, $body);
                                    break;
                                case "5": //Decanato
                                    if ($solicitud_correo[$a]["uaca_id"] == "1") {
                                        switch ($solicitud_correo[$a]["mod_id"]) {
                                            case "1": //online  
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanato_online@uteg.edu.ec" => "decano", "secretariaonline@uteg.edu.ec" => "secretaria", "ahernandez@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                //\app\models\Utilities::putMessageLogFile('Decanato 1');   
                                                break;
                                            case "2": //presencial
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanopresencial@uteg.edu.ec" => "decano", "secretariapresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                
                                                break;
                                            case "3": //semipresencial                                        
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "secretariasemipresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                break;
                                            case "4": //distancia
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador", "secretariasemipresencial@uteg.edu.ec" => "secretaria"], $asunto, $body);
                                                break;
                                        }
                                    } else {
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["olmedo.farfan@uteg.edu.ec" => "decano", "secretariaposgrado@uteg.edu.ec" => "secretaria", "coordinacionposgrado@uteg.edu.ec" => "coordinador", "coordinacionposgradosonline@uteg.edu.ec" => "coordinador1"], $asunto, $body);
                                        break;
                                    }
                                    break;
                                case "6": //Graduacion
                                    if ($solicitud_correo[$a]["uaca_id"] == "1") {
                                        switch ($solicitud_correo[$a]["mod_id"]) {
                                            case "1": //online  
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanato_online@uteg.edu.ec" => "decano", "secretariaonline@uteg.edu.ec" => "secretaria", "ahernandez@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                
                                                break;
                                            case "2": //presencial
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanopresencial@uteg.edu.ec" => "decano", "secretariapresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                              
                                                break;
                                            case "3": //semipresencial                                        
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "secretariasemipresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                break;
                                            case "4": //distancia
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador", "secretariasemipresencial@uteg.edu.ec" => "secretaria"], $asunto, $body);
                                                break;
                                        }
                                    } else {
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["olmedo.farfan@uteg.edu.ec" => "decano", "secretariaposgrado@uteg.edu.ec" => "secretaria", "coordinacionposgrado@uteg.edu.ec" => "coordinador", "coordinacionposgradosonline@uteg.edu.ec" => "coordinador1"], $asunto, $body);
                                        break;
                                    }
                                    break;
                                case "7": //Examen complexivo
                                    if ($solicitud_correo[$a]["uaca_id"] == "1") {
                                        switch ($solicitud_correo[$a]["mod_id"]) {
                                            case "1": //online  
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanato_online@uteg.edu.ec" => "decano", "secretariaonline@uteg.edu.ec" => "secretaria", "ahernandez@uteg.edu.ec" => "coordinador"], $asunto, $body);                                                
                                                break;
                                            case "2": //presencial
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["decanopresencial@uteg.edu.ec" => "decano", "secretariapresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                \app\models\Utilities::putMessageLogFile('Examen Complexivo');                                                                
                                                break;
                                            case "3": //semipresencial                                        
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "secretariasemipresencial@uteg.edu.ec" => "secretaria", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador"], $asunto, $body);
                                                break;
                                            case "4": //distancia
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["subdecanogrado@uteg.edu.ec" => "decano", "coordinadoracademicogrado@uteg.edu.ec" => "coordinador", "secretariasemipresencial@uteg.edu.ec" => "secretaria"], $asunto, $body);
                                                break;
                                        }
                                    } else {
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["olmedo.farfan@uteg.edu.ec" => "decano", "secretariaposgrado@uteg.edu.ec" => "secretaria", "coordinacionposgrado@uteg.edu.ec" => "coordinador", "coordinacionposgradosonline@uteg.edu.ec" => "coordinador1"], $asunto, $body);
                                        break;
                                    }
                                    break;
                                case "8": //Secretaria General -- Posgrado
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["secretariogeneral@yopmail" => "secretario", "coordinacionsecretaria@uteg.edu.ec" => "coordinador", "yazar@uteg.edu.ec" => "yazar"], $asunto, $body);
                                    break;
                                case "9": //Financiero -- Posgrado
                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], ["colecturia@yopmail" => "colecturia", "supervisorcolecturia@uteg.edu.ec" => "supervisor"], $asunto, $body);
                                    break;
                            }
                        }
                    }

                    // peguntar por unidad academica y modalidad de acuerdo a ello enviar el correo al area respectiva
                } else if ($data['estado'] == '2') {
                    $bodies = Utilities::getMailMessage("reprobarpagoalumno", array(//si reprueba otro correo
                                "[[user]]" => $user,
                                "[[link]]" => "https://asgard.uteg.edu.ec/asgard/",
                                "[[motivo]]" => $datasolicitud[0]["csol_observacion"]), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    Utilities::sendEmail(
                            $tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $user], $asunto, $bodies);
                }
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            } else {
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar. " . $carga_archivo['message']),
                    "title" => Yii::t('jslang', 'Error'),
                );

                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
            return;
        }
        $per_id = $especiesADO->consultaPeridxestid($est_id);
        $personaData = $especiesADO->consultaDatosEstudiante($per_id["per_id"]); //aqui enviar per_id
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        $cabSol = $especiesADO->consultarCabSolicitud($ids);
        $model = $especiesADO->getSolicitudesAlumnos($est_id, null, false);
        $img_pago = $cabSol[0]["csol_ruta_archivo_pago"];
        return $this->render('autorizarpago', [
                    'model' => $model,
                    'img_pago' => $img_pago,
                    'arr_persona' => $personaData,
                    'cab_solicitud' => $cabSol,
                    'det_solicitud' => json_encode($especiesADO->consultarDetSolicitud($ids)),
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoPagosColecturia(),
                    'arrObservacion' => array("" => "Seleccione una opción", "Pago Ilegible" => "Pago Ilegible", "Pago Duplicado" => "Pago Duplicado", "Tipo de archivo incorrecto" => "Tipo de archivo incorrecto", "No se encuentra al dia en pagos" => "No se encuentra al dia en pagos"),
        ]);
    }

    public function actionEspeciesgeneradas() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        $especiesADO = new Especies();
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
            if (isset($data["gettramite"])) {
                $tramite = $especiesADO->getTramite($data["unidad"]);
                $message = [
                    "tramite" => $tramite,
                ];
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
        }
        //$est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["tramite"] = $data['tramite'];
            $arrSearch["estdocerti"] = $data['estdocerti'];
            $resp_pago = $especiesADO->getSolicitudesGeneradas($est_id, $arrSearch, false);
            return $this->renderPartial('_especies-grid', [
                        "model" => $resp_pago,
            ]);
        } else {
            
        }
        $personaData = $especiesADO->consultaDatosEstudiante($per_id);
        $model = $especiesADO->getSolicitudesGeneradas($est_id, null, false);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        $arr_tramite = $especiesADO->getTramite(1);
        $cabFact = $especiesADO->consultarEspecieGenerada(7);
        //Utilities::putMessageLogFile('xxccv'. $cabFact["imagen"]);
        return $this->render('especiesgeneradas', [
                    'model' => $model,
                    'imagen' => $cabFact["imagen"],
                    'arrEstados' => $this->estadoPagos(),
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_tramite' => ArrayHelper::map($arr_tramite, "id", "name"),
                    'arr_estadocertificado' => array("-1" => "Seleccionar", "0" => "Pendiente", "1" => "Generado"),
        ]);
    }

    public function actionGenerarespeciespdf($ids) {//ok
        try {
            $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
            $rep = new ExportFile();
            //$this->layout = false;
            $this->layout = '@modules/academico/views/tpl_especies/main';
            //$this->view->title = "Invoices";
            $especiesADO = new Especies();
            $cabFact = $especiesADO->consultarEspecieGenerada($ids);
            if ($cabFact['uaca_id'] == '1') {
                $carrera = 'facultad/carrera';
                $facultaded = 'Facultad de Grado';
            }
            if ($cabFact['uaca_id'] == '2') {
                $carrera = 'maestría';
                $facultaded = 'Facultad de Posgrado';
            }
            $objEsp = $especiesADO->getDataEspecie($cabFact['esp_id']);
            $codigo = $objEsp[0]['codigo'] . '-' . $cabFact['egen_numero_solicitud'];
            //setlocale(LC_TIME,"es_ES");//strftime("%A, %d de %B de %Y", date("d-m-Y"));
            setlocale(LC_TIME, 'es_CO.UTF-8');

            //$cabFact['FechaDia'] = strftime("%A %d de %B %G", strtotime(date("d-m-Y"))); 
            $cabFact['FechaDia'] = strftime("%A %d de %B %G", strtotime($cabFact['fecha_aprobacion']));
            $this->pdf_cla_acceso = $codigo;
            $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical   
            $rep->createReportPdf(
                    $this->render('@modules/academico/views/tpl_especies/especie', [
                        'cabFact' => $cabFact,
                        'carrera' => $carrera,
                        'facultaded' => $facultaded,
                    ])
            );
            $rep->mpdf->Output('ESPECIE_' . $codigo . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
            //exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionGenerarcertificado($ids) {//ok
        try {
            $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
            $rep = new ExportFile();
            //$this->layout = false;
            $this->layout = '@modules/academico/views/tpl_especies/main';
            //$this->view->title = "Invoices";
            $especiesADO = new Especies();
            $cabFact = $especiesADO->consultarEspecieGenerada($ids);
            $objEsp = $especiesADO->getDataEspecie($cabFact['esp_id']);
            $codigo = $objEsp[0]['codigo'] . '-' . $cabFact['egen_numero_solicitud'] . '-' . $cabFact['per_cedula'];
            setlocale(LC_TIME, 'es_CO.UTF-8');
            $cabFact['FechaDia'] = strftime("%A %d de %B %G", strtotime(date("d-m-Y"))); //date("j F de Y");      
            $this->pdf_cla_acceso = $codigo;
            $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical   
            $rep->createReportPdf(
                    $this->render('@modules/academico/views/tpl_especies/solicitud', [
                        'cabFact' => $cabFact,
                    ])
            );
            $rep->mpdf->Output('CERTIFICADO_' . $codigo . ".doc", ExportFile::OUTPUT_TO_DOWNLOAD);
            //exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionExpexcelespecies() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
        $arrHeader = array(
            Yii::t("formulario", "Number"),
            Especie::t("Especies", "Procedure"),
            Especie::t("Especies", "Tipo Especies"),
            Especie::t("Especies", "Student"),
            Yii::t("formulario", "DNI Document"),
            Especie::t("Especies", "Unidad Academica"),
            Especie::t("Academico", "Modalidad"),
            Especie::t("Especies", "Approval date"),
            Especie::t("Especies", "Date validity"),
            Especie::t("Especies", "Generate Code"),
            Especie::t("Especies", "Certified Code"),
                //" ",
        );
        $especiesADO = new Especies();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];
        $arrSearch["tramite"] = $data['tramite'];
        $arrSearch["estdocerti"] = $data['estdocerti'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $especiesADO->getSolicitudesGeneradas(null, array(), true);
        } else {
            $arrData = $especiesADO->getSolicitudesGeneradas(null, $arrSearch, true);
        }
        $nameReport = Especie::t("Especies", "List of generated species");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionVerpago() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        $especiesADO = new Especies();
        $est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $mod_persona = new Persona();
        $data_persona = $mod_persona->consultaPersonaId($per_id);
        /* if (Yii::$app->request->isAjax) {
          $data = Yii::$app->request->post();
          } */
        $cabSol = $especiesADO->consultarCabSolicitud($ids);
        $img_pago = $cabSol[0]["csol_ruta_archivo_pago"];
        $personaData = $especiesADO->consultaDatosEstudiante($per_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        $model = $especiesADO->getSolicitudesAlumnos($est_id, null, false);
        return $this->render('verpago', [
                    'model' => $model,
                    'arr_persona' => $personaData,
                    'cab_solicitud' => $especiesADO->consultarCabSolicitud($ids),
                    'det_solicitud' => json_encode($especiesADO->consultarDetSolicitud($ids)),
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoPagos(),
                    'img_pago' => $img_pago,
        ]);
    }

    public function actionVerautorizarpago() {
        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        //Utilities::putMessageLogFile($ids);
        $est_id = base64_decode($_GET['est_id']);
        $especiesADO = new Especies();
        //$est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $per_id = $especiesADO->consultaPeridxestid($est_id);
        $personaData = $especiesADO->consultaDatosEstudiante($per_id["per_id"]); //aqui enviar per_id
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        $cabSol = $especiesADO->consultarCabSolicitud($ids);
        $model = $especiesADO->getSolicitudesAlumnos($est_id, null, false);
        $img_pago = $cabSol[0]["csol_ruta_archivo_pago"];
        return $this->render('ver_autorizarpago', [
                    'model' => $model,
                    'img_pago' => $img_pago,
                    'arr_persona' => $personaData,
                    'cab_solicitud' => $cabSol,
                    'det_solicitud' => json_encode($especiesADO->consultarDetSolicitud($ids)),
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoPagosColecturia(),
                    'arrObservacion' => array("" => "Seleccione una opción", "Pago Ilegible" => "Pago Ilegible", "Pago Duplicado" => "Pago Duplicado", "Tipo de archivo incorrecto" => "Tipo de archivo incorrecto"),
        ]);
    }

    public function actionExppdfespecies() {
        $report = new ExportFile();
        $arrHeader = array(
            Yii::t("formulario", "Number"),
            Especie::t("Especies", "Procedure"),
            Especie::t("Especies", "Tipo Especies"),
            Especie::t("Especies", "Student"),
            Yii::t("formulario", "DNI Document"),
            Especie::t("Especies", "Unidad Academica"),
            Especie::t("Academico", "Modalidad"),
            Especie::t("Especies", "Approval date"),
            Especie::t("Especies", "Date validity"),
            Especie::t("Especies", "Generate Code"),
            Especie::t("Especies", "Certified Code"),
        );
        $especiesADO = new Especies();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];
        $arrSearch["tramite"] = $data['tramite'];
        $arrSearch["estdocerti"] = $data['estdocerti'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $especiesADO->getSolicitudesGeneradas(null, array(), true);
        } else {
            $arrData = $especiesADO->getSolicitudesGeneradas(null, $arrSearch, true);
        }

        $this->view->title = Especie::t("Especies", "List of generated species"); // Titulo del reporte                
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

    public function actionEspeciesgeneradasxest() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $cab_ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $personaData = $especiesADO->consultaDatosEstudiante($per_id);
        $cabSol = $especiesADO->consultarCabSolicitud($cab_ids);
        $model = $especiesADO->getSolicitudesGeneradasxest($cab_ids, false);
        $arr_unidad = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        return $this->render('especiesgeneradas_est', [
                    'model' => $model,
                    'arr_persona' => $personaData,
                    'cabsolicitud' => $cabSol,
                    'arr_unidad' => ArrayHelper::map($arr_unidad, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
        ]);
    }

    public function actionCargarimagen() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        //Utilities::putMessageLogFile($ids);
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
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "imagenespecie/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
        }
    }

    public function actionDescargarimagen() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $gen_id = isset($_GET['espgen_id']) ? base64_decode($_GET['espgen_id']) : NULL;

        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $det_ids = $especiesADO->consultarEspecieGenerada($gen_id);
        $cab_ids = $especiesADO->consultarcabeceraxdetalle($det_ids["dsol_id"]);
        $personaData = $especiesADO->consultaDatosEstudiante($det_ids["per_id"]);
        $cabSol = $especiesADO->consultarCabSolicitud($cab_ids["csol_id"]);
        $model = $especiesADO->getSolicitudesGeneradasxdet($cab_ids["csol_id"], $det_ids["dsol_id"], false);
        $arr_unidad = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        return $this->render('descargarimagen', [
                    'model' => $model,
                    'arr_persona' => $personaData,
                    'cabsolicitud' => $cabSol,
                    'arr_unidad' => ArrayHelper::map($arr_unidad, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'imagen' => $det_ids["imagen"],
        ]);
    }

    public function actionGeneracetificodigo() {
        $mod_certificado = new CertificadosGeneradas();
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $egen_id = $data['egen_id'];
            $egen_numero_solicitud = $data['egen_numero_solicitud'];
            $per_cedula = $data['per_cedula'];
            $cgen_codigo = $egen_numero_solicitud . "-" . $per_cedula;
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $resul = $mod_certificado->insertarCodigocertificado($egen_id, $cgen_codigo, $fecha, 1, $usuario);
                if ($resul) {
                    $exito = 1;
                }
                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Código de certificado ha sido generado. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al generar código de certificado." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'Error', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => $ex->getMessage(), Yii::t("notificaciones", "Error al generar código de certificado."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
        }
    }
    
    

}
