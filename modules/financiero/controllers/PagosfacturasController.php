<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\ExportFile;
use app\models\Persona;
use app\models\Usuario;
use yii\helpers\Url;
use yii\base\Exception;
use yii\base\Security;
use app\modules\academico\models\Especies;
use app\modules\financiero\models\FormaPago;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\ModuloEstudio;
use app\modules\financiero\models\PagosFacturaEstudiante;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();

class PagosfacturasController extends \app\components\CController {

    private function estados() {
        return [
            '0' => Yii::t("formulario", "Todos"),
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
            '0' => Yii::t("formulario", "Todos"),
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
        ]);
    }

    public function actionViewsaldo() {
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modcanal = new Oportunidad();
        $mod_pagos = new PagosFacturaEstudiante();
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($personaData['uaca_id'], 1);
        if (($personaData['uaca_id'] == 1) or ( $personaData['uaca_id'] == 2)) {
            $carrera = $modcanal->consultarCarreraModalidad($personaData['uaca_id'], $personaData['mod_id']);
        } else {
            $carrera = $modestudio->consultarCursoModalidad($personaData['uaca_id'], $personaData['mod_id']); // tomar id de impresa
        }
        $pagospendientesea = $mod_pagos->getPagospendientexest($personaData['per_cedula'], false);
        return $this->render('viewsaldo', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    'model' => $pagospendientesea,
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
                    'arrObservacion' => array("0" => "Seleccione", "Archivo Ilegible" => "Archivo Ilegible", "Archivo no corresponde al pago" => "Archivo no corresponde al pago", "Archivo con Error" => "Archivo con Error", "Valor pagado no cubre factura" => "Valor pagado no cubre factura"),
        ]);
    }

    public function actionSubirpago() {
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
        $pagospendientesea = $mod_pagos->getPagospendientexest($personaData['per_cedula'], false);
        return $this->render('subirpago', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
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
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            $id = $data['dpfa_id'];
            $resultado = $data['resultado'];
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
                    try {
                        $datos = $mod_pagos->consultarPago($id);
                        //Utilities::putMessageLogFile('$cuota:' . $datos['dpfa_num_cuota']);
                        if ($observacion == 0 && $resultado == "2") {
                            $observacion = null;
                        }
                        $respago = $mod_pagos->grabarRechazo($id, $resultado, $observacion);
                        if ($respago) {
                            $transaction->commit();

                            $correo_estudiante = $datos['per_correo'];
                            $user = $datos['estudiante'];
                            $tituloMensaje = 'Pagos en Línea';
                            $asunto = 'Pagos en Línea';
                            if ($resultado != "2") {
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
                                Utilities::putMessageLogFile('entro_envio vorreo');
                                Utilities::putMessageLogFile('correo..' . $correo_estudiante);
                                $body = Utilities::getMailMessage("pagoaprobado", array(
                                            "[[user]]" => $user,   
                                            "[[factura]]" => $datos['dpfa_factura'],
                                                ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                            }
                             //Utilities::putMessageLogFile('graba la transaccion');
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada"),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        } else {
                            $message = ["info" => Yii::t('exception', 'Error al grabar 0.')];
                            echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
                        }
                    } catch (Exception $ex) {
                        $transaction->rollback();
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
        //online que sube doc capturar asi el id de la persona   
        $per_idsession = @Yii::$app->session->get("PB_perid");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            $mod_estudiante = new Especies();
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
            $pagado = $data["pagado"];
            $personaData = $mod_estudiante->consultaDatosEstudiante($per_idsession); //$personaData['per_cedula']
            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            try {
                $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
                //Utilities::putMessageLogFile('usuario:'. $usuario);
                // consultar en detalle si ya no existe un registro con el mismo              
                $pagadose = explode("*", $pagado); //PAGADOS
                    $y = 0;
                    foreach ($pagadose as $datose) {
                        //  consultar la informacion seleccionada de cuota factura
                        $parametros = explode(";", $pagadose[$y]);                   
                        $resp_consregistro = $mod_pagos->consultarExistepago($est_id, $parametros[0], $parametros[1]);
                        $cuota_pagada .= $parametros[1] . ', ' ;
                 $y++;
                }
                if ($resp_consregistro['registro'] == '0') {      
                    $resp_pagofactura = $mod_pagos->insertarPagospendientes($est_id, $pfes_referencia, $fpag_id, $pfes_valor_pago, $pfes_fecha_pago, $pfes_observacion, $imagen, $usuario);
                if ($resp_pagofactura) {
                    // se graba el detalle
                    $pagados = explode("*", $pagado); //PAGADOS
                    $x = 0;
                    foreach ($pagados as $datos) {
                        //  consultar la informacion seleccionada de cuota factura
                        $parametro = explode(";", $pagados[$x]);
                        $resp_consfactura = $mod_pagos->consultarPagospendientesp($personaData['per_cedula'], $parametro[0], $parametro[1]);
                        // insertar el detalle                                                
                        $resp_detpagofactura = $mod_pagos->insertarDetpagospendientes($resp_pagofactura, $resp_consfactura['tipofactura'], $resp_consfactura['factura'], $resp_consfactura['descripcion'], $parametro[2], $resp_consfactura['fecha'], $resp_consfactura['saldo'], $resp_consfactura['numcuota'], $resp_consfactura['valorcuota'], $resp_consfactura['fechavence'], $usuario);
                        $x++;
                    }
                    if ($resp_detpagofactura) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
             }  else {
                Utilities::putMessageLogFile('dentro else:'. $cuota_pagada);
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "La cuota " . $cuota_pagada . "  ya esta cargado el pago, espere porfavor su revisión"),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }

            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionExpexcelfacpendiente() {
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
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Financial Status"),
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
        $arrSearch["estadofinanciero"] = $data['estadofinanciero'];

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
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Financial Status"),
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
        $arrSearch["estadofinanciero"] = $data['estadofinanciero'];
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
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $model = $mod_pagos->consultarPago($dpfa_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('viewrevisionpago', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoRechazo(),
                    'arrObservacion' => array("0" => "Seleccione", "Archivo Ilegible" => "Archivo Ilegible", "Archivo no corresponde al pago" => "Archivo no corresponde al pago", "Archivo con Error" => "Archivo con Error", "Valor pagado no cubre factura" => "Valor pagado no cubre factura"),
        ]);
    }
    
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
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
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
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
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
}
