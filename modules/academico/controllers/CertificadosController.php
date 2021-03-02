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
use app\modules\academico\Module as certificados;

certificados::registerTranslations();

Academico::registerTranslations();
Especie::registerTranslations();

class CertificadosController extends \app\components\CController {

    private function estados() {
        return [
            '0' => Yii::t("formulario", "Todos"),
            '1' => Yii::t("formulario", "Código Generado"),
            '2' => Yii::t("formulario", "Certificado Generado"),
            '3' => Yii::t("formulario", "Certificado Autorizado"),
            '4' => Yii::t("formulario", "Certificado Rechazado"),
        ];
    }
    
     private function estadoAutorizacion() {
        return [
            '0' => Yii::t("formulario", "Seleccione"),
            '3' => Yii::t("formulario", "Certificado Autorizado"),
            '4' => Yii::t("formulario", "Certificado Rechazado"),
        ];
    }
    
    private function estadosPendiente() {
        return [
            '0' => Yii::t("formulario", "Todos"),
            '1' => Yii::t("formulario", "Código Generado"),
            '2' => Yii::t("formulario", "Certificado Generado"),            
            '4' => Yii::t("formulario", "Certificado Rechazado"),
        ];
    }
    public function actionIndex() {
        $mod_certificado = new CertificadosGeneradas();
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
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["estdocerti"] = $data['estdocerti'];
            $resp_cert = $mod_certificado->getCertificadosGeneradas($arrSearch, false);
            return $this->renderPartial('_index-grid', [
                        "model" => $resp_cert,
            ]);
        }
        $model = $mod_certificado->getCertificadosGeneradas(null, false);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('index', [
                    'model' => $model,
                    'arrEstados' => $this->estadosPendiente(),
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                   // 'arr_estadocertificado' => array("0" => "Todos", "1" => "Código Generado", "2" => "Certificado Generado"),
        ]);
    }

    public function actionExpexcelcertificado() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrHeader = array(
            Especie::t("Especies", "Número"),
            Especie::t("Especies", "Número Especie"),
            Especie::t("Especies", "Student"),
            Especie::t("Especies", "Academic unit"),
            academico::t("Academico", "Modality"),
            Especie::t("Especies", "Código Certificado"),
            Especie::t("Especies", "Fecha Genarado"),
            Especie::t("Especies", "Certified Status"),
        );
        $mod_certificado = new CertificadosGeneradas();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];
        $arrSearch["estdocerti"] = $data['estdocerti'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_certificado->getCertificadosGeneradas(array(), true);
        } else {
            $arrData = $mod_certificado->getCertificadosGeneradas($arrSearch, true);
        }
        $nameReport = certificados::t("certificados", "Certificate List");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfcertificado() {
        $report = new ExportFile();
        $arrHeader = array(
            Especie::t("Especies", "Número"),
            Especie::t("Especies", "Número Especie"),
            Especie::t("Especies", "Student"),
            Especie::t("Especies", "Academic unit"),
            academico::t("Academico", "Modality"),
            Especie::t("Especies", "Código Certificado"),
            Especie::t("Especies", "Fecha Genarado"),
            Especie::t("Especies", "Certified Status"),
        );
        $mod_certificado = new CertificadosGeneradas();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];
        $arrSearch["estdocerti"] = $data['estdocerti'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_certificado->getCertificadosGeneradas(array(), true);
        } else {
            $arrData = $mod_certificado->getCertificadosGeneradas($arrSearch, true);
        }

        $this->view->title = certificados::t("certificados", "Certificate List"); // Titulo del reporte                
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

    public function actionSubircertificado() {
        $cgen_id = base64_decode($_GET["cgen_id"]);
        $mod_certificado = new CertificadosGeneradas();
        $respcertificado = $mod_certificado->consultarCertificadosGeneradas($cgen_id);
        return $this->render('subircertificado', [
                    'arr_certificado' => $respcertificado,
        ]);
    }

    public function actionSavecertificado() {
        //online que sube doc capturar asi el id de la persona 
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Parametros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "certificados/" . $data["per_id"] . "/" . $data["name_file"] . "." . $typeFile;
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
            $cgen_id = $data["cgen_id"];
            $observacion = $data["observacion"];
            //$codigocerti = $data["upload_file"];

            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();

            try {
                $usuario_modifica = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.                            
                $mod_certificado = new CertificadosGeneradas();                
                $resp_certifica = $mod_certificado->subirCertificadopdf($cgen_id, $observacion, $imagen, $usuario_modifica);
                if ($resp_certifica) {
                    $exito = 1;
                }
                if ($exito) {
                    $transaction->commit();                    
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar certificado." . $mensaje),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar certificado." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionListadoautorizados() {
        $mod_certificado = new CertificadosGeneradas();
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
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["search"] = $data['search'];
            $resp_cert = $mod_certificado->listarCertificadosAutorizados($arrSearch, false, 1);
            return $this->renderPartial('_index-grid_autorizado', [
                        "model" => $resp_cert,
            ]);
        }
        $model = $mod_certificado->listarCertificadosAutorizados(null, false, 1);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('index_autorizado', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
        ]);
    }

    public function actionExpexcelcertificadogen() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrHeader = array(
            Especie::t("Especies", "Número"),
            Especie::t("Especies", "Número Especie"),
            Especie::t("Especies", "Student"),
            Especie::t("Especies", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("certificados", "Certificate Code"),
            academico::t("certificados", "Authorization date"),
        );
        $mod_certificado = new CertificadosGeneradas();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_certificado->listarCertificadosAutorizados(array(), true, 0);
        } else {
            $arrData = $mod_certificado->listarCertificadosAutorizados($arrSearch, true, 0);
        }
        $nameReport = certificados::t("certificados", "List of authorized certificate");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfcertificadogen() {
        $report = new ExportFile();
        $arrHeader = array(
            Especie::t("Especies", "Número"),
            Especie::t("Especies", "Número Especie"),
            Especie::t("Especies", "Student"),
            Especie::t("Especies", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("certificados", "Certificate Code"),
            academico::t("certificados", "Authorization date"),
        );
        $mod_certificado = new CertificadosGeneradas();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_certificado->listarCertificadosAutorizados(array(), true, 0);
        } else {
            $arrData = $mod_certificado->listarCertificadosAutorizados($arrSearch, true, 0);
        }

        $this->view->title = certificados::t("certificados", "List of authorized certificate"); // Titulo del reporte                
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

    public function actionDownloadcertificado() {
        $data = Yii::$app->request->get();
        if ($data['cod']) {
            ini_set('memory_limit', '256M');
            $content_type = Utilities::mimeContentType("doc");
            $nombarch = "Certificado-" . base64_decode($data['cod']) . "-" . date("YmdHis") . ".docx";
            header("Content-Type: $content_type");
            header("Content-Disposition: attachment;filename=" . $nombarch);
            header('Cache-Control: max-age=0');
            $file_template = Yii::$app->basePath . "/modules/academico/templates/certificados/template_certificado.docx";
            $loadFileValues = ['CODE' => base64_decode($data['cod'])];
            Utilities::generarReportDoc($file_template, null, null, false, true, $loadFileValues);
            exit;
        }
        $this->redirect('index');
    }

    public function actionListadopendientes() {
        $mod_certificado = new CertificadosGeneradas();
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
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["search"] = $data['search'];
            $resp_cert = $mod_certificado->listarCertificadosSubidos($arrSearch, false, 1);
            return $this->renderPartial('_index-grid_pendiente', [
                        "model" => $resp_cert,
            ]);
        }
        $model = $mod_certificado->listarCertificadosSubidos(null, false, 1);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('index_pendiente', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
        ]);
    }
    
    public function actionAutorizarcertificado() {
        $cgen_id = base64_decode($_GET["cgen_id"]);
        $mod_certificado = new CertificadosGeneradas();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();                        
                
        $model = $mod_certificado->consultarCertificadosGeneradas($cgen_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('autorizarcertificado', [
                    'model' => $model,                    
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoAutorizacion(),
                    'arrObservacion' => array("0" => "Seleccione una opción", "Archivo Ilegible" => "Archivo Ilegible", "Archivo sin Firmas" => "Archivo sin Firmas", "Archivo con Errores" => "Archivo con Errores"),
        ]);
    }
    
    public function actionSaveautorizacion() {         
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_certificado = new CertificadosGeneradas();            
            //Utilities::putMessageLogFile('csdfsd' . $pagodia['eppa_estado_pago']);
            $id = $data['cgen_id'];
            $resultado = $data['resultado'];
            $observacion = $data['observacion'];    
            $detobserva = ucfirst(mb_strtolower($data['detobserva'], 'UTF-8'));
            
            if ($resultado != "0") {  
                Utilities::putMessageLogFile('ingresa');
                if (($resultado == "4") && ($observacion=="0")) {                    
                    $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Seleccione una observación"),
                            "title" => Yii::t('jslang', 'Success'),
                            );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    return;
                }                   
                $con = \Yii::$app->db_academico;
                $transaction = $con->beginTransaction();
                try {
                    $respcertificado = $mod_certificado->consultarCertificadosGeneradas($id);
                    $resul = $mod_certificado->grabarAutorizacion($id, $resultado, $observacion, $detobserva);               
                    //Utilities::putMessageLogFile($resul);
                    if ($resul) {
                        $transaction->commit();
                        //Correo enviado al estudiante cuando se ha autorizado el certificado.
                        if ($resultado == "3") { 
                            $correo_estudiante = $respcertificado[0]['per_correo'];                            
                            $user = $respcertificado[0]['Nombres'];
                            $especie = $respcertificado[0]['esp_rubro'];                                           
                            $tituloMensaje = 'Certificado en Línea';
                            $asunto = 'Certificado en Línea';
                            $body = Utilities::getMailMessage("notificarcertificado", array(
                                        "[[user]]" => $user,
                                        "[[link]]" => "https://asgard.uteg.edu.ec/asgard/",
                                        "[[especie]]" => $especie), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                        } else {
                            //Cuando el certificado ha sido rechazado, se envia correo al personal encargado.
                            
                        }
                        Utilities::putMessageLogFile('graba la transaccion');                        
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
                    $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                            "title" => Yii::t('jslang', 'Success'),
                            );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
                return;
                               
            } else {
                $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Seleccione el resultado de la autorización"),
                            "title" => Yii::t('jslang', 'Success'),
                            );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    return;
            }
            return;
        }
    }
    
    public function actionExpexcelcertificadopend() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        $arrHeader = array(
            Especie::t("Especies", "Número"),
            Especie::t("Especies", "Número Especie"),
            Especie::t("Especies", "Student"),
            Especie::t("Especies", "Academic unit"),
            academico::t("Academico", "Modality"),
            Especie::t("certificados", "Certificate Code"),
            academico::t("certificados", "Date Generated"),
            academico::t("certificados", "Rejection date"),
            Especie::t("Especies", "Certified Status"),      
        );
        $mod_certificado = new CertificadosGeneradas();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];

        $arrData = array();
        if (empty($arrSearch)) {            
            $arrData = $mod_certificado->listarCertificadosSubidos(array(), true, 0);
        } else {
            $arrData = $mod_certificado->listarCertificadosSubidos($arrSearch, true, 0);
        }
        $nameReport = certificados::t("certificados", "List of Pending Certificates to Authorize");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfcertificadopend() {
        $report = new ExportFile();
        $arrHeader = array(
            Especie::t("Especies", "Número"),
            Especie::t("Especies", "Número Especie"),
            Especie::t("Especies", "Student"),
            Especie::t("Especies", "Academic unit"),
            academico::t("Academico", "Modality"),
            Especie::t("certificados", "Certificate Code"),
            academico::t("certificados", "Date Generated"),
            academico::t("certificados", "Rejection date"),
            Especie::t("Especies", "Certified Status"),      
        );
        $mod_certificado = new CertificadosGeneradas();
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["search"] = $data['search'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_certificado->listarCertificadosSubidos(array(), true, 0);
        } else {
            $arrData = $mod_certificado->listarCertificadosSubidos($arrSearch, true, 0);
        }

        $this->view->title = certificados::t("certificados", "List of Pending Certificates to Authorize"); // Titulo del reporte                
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
}
