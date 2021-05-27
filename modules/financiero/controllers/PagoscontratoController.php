<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\modules\academico\models\Admitido;
use app\modules\academico\models\EstudioAcademico;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\admision\models\Oportunidad;
use app\modules\admision\models\ConvenioEmpresa;
use app\modules\financiero\models\PagosContratoPrograma;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\models\ExportFile;
use app\models\InscripcionAdmision;

academico::registerTranslations();
admision::registerTranslations();
financiero::registerTranslations();

class PagoscontratoController extends \app\components\CController {

    public $folder_cv = 'contratos';

    public function actionIndex() {
        $mod_carrera = new EstudioAcademico();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new Oportunidad();
        $mod_contrato = new PagosContratoPrograma();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
            $arrSearch["periodo"] = $data['periodo'];
            $mod_aspirante = $mod_contrato->consultarMatriculaPosgrado($arrSearch);
            //$mod_aspirante = Admitido::getMatriculadoPosgrado($arrSearch);
            return $this->renderPartial('index-grid', [
                        "model" => $mod_aspirante,
            ]);
        } else {
            //$mod_aspirante = Admitido::getMatriculadoPosgrado();
            $mod_aspirante = $mod_contrato->consultarMatriculaPosgrado($arrSearch);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["op"]) && $data["op"] == '1') {
                
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
        }
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[1]["id"], 1);
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad($arr_ninteres[1]["id"], $arr_modalidad[0]["id"]);
        $arrCarreras = ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Grid")]], $mod_carrera->consultarCarrera()), "id", "value");
        return $this->render('index', [
                    'model' => $mod_aspirante,
                    'arrCarreras' => $arrCarreras,
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_carrerra1), "id", "name"),
        ]);
    }

    public function actionCargarcontrato() {
        $adm_id = base64_decode($_GET['adm_id']);
        $mod_conempresa = new ConvenioEmpresa();
        $mod_datosadmitido = new PagosContratoPrograma();
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $arr_datoadmitido = $mod_datosadmitido->consultarDatosadmitido($adm_id);
        return $this->render('cargarcontrato', [
                    'arr_convenio_empresa' => ArrayHelper::map($arr_convempresa, "id", "name"),
                    'arr_datoadmitido' => $arr_datoadmitido
        ]);
    }

    public function actionSavecontrato() {
        $mod_documento = new PagosContratoPrograma();
        $per_id = base64_decode($_SESSION['peradmitido']);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $usr_id = @Yii::$app->session->get("PB_iduser");
            $adm_id = $data["adm_id"];
            $convenio = $data["convenio"];
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                \app\models\Utilities::putMessageLogFile('per..... ' . $per_id);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "contratos/" . $per_id . "/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $contrato_archivo = "";
                if (isset($data["arc_doc_contrato"]) && $data["arc_doc_contrato"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_contrato"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $contrato_archivo = Yii::$app->params["documentFolder"] . "contratos/" . $per_id . "/pagocontrato." . $typeFile;
                }
            }
        }
        $con = \Yii::$app->db_facturacion;
        $transaction = $con->beginTransaction();
        //$timeSt = time();
        $timeSt = date(Yii::$app->params["dateByDefault"]);
        try {
            if (isset($data["arc_doc_contrato"]) && $data["arc_doc_contrato"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_contrato"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $contrato_archivo = Yii::$app->params["documentFolder"] . "contratos/" . $per_id . "/pagocontrato." . $typeFile;
                $contrato_archivo = InscripcionAdmision::addLabelFechaDocPagos($per_id, $contrato_archivo, $timeSt);
                if ($contrato_archivo === FALSE)
                    throw new Exception('Error doc Contrato no renombrado.');
            }
            /* $mod_documento = new DocumentoAceptacion();
              $resexiste = $mod_documento->consultarXperid($per_id);
              if ($resexiste["dace_estado_aprobacion"] == '3' or empty($resexiste["dace_estado_aprobacion"])) { */
            $datos = array(
                //'per_id' => $per_id,
                'adm_id' => $adm_id,
                'cemp_id' => $convenio,
                'pcpr_archivo' => $contrato_archivo,
                'pcpr_usu_ingreso' => $usr_id,
            );
            if ($contrato_archivo != "") {
                $respuesta = $mod_documento->insertarcontrato($con, $datos);
                if ($respuesta) {
                    $exito = 1;
                }
            }
            if ($exito) {
                $transaction->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "El documento ha sido grabado."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            } else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", 'Error'),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
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

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K");
        $arrHeader = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"), //ingles
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            academico::t("Academico", "Income Method"), //ingles
            academico::t("Academico", "Career/Program"),
            admision::t("Solicitudes", "Scholarship")
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["f_ini"] = $data['fecha_ini'];
            $arrSearch["f_fin"] = $data['fecha_fin'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
            $arrSearch["periodo"] = $data['periodo'];
        }
        $arrData = array();
        $admitido_model = new Admitido();
        if (count($arrSearch) > 0) {
            $arrData = $admitido_model->consultarReportAdmitidos($arrSearch, true);
        } else {
            $arrData = $admitido_model->consultarReportAdmitidos(array(), true);
        }
        $nameReport = academico::t("Academico", "Enrolled Postgraduate");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("Enrolled Postgraduate", "Admitted");  // Titulo del reporte
        $arrHeader = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"), //ingles
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            academico::t("Academico", "Income Method"), //ingles
            academico::t("Academico", "Career/Program"),
            admision::t("Solicitudes", "Scholarship")
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["f_ini"] = $data['fecha_ini'];
            $arrSearch["f_fin"] = $data['fecha_fin'];
            $arrSearch["carrera"] = $data['carrera'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
            $arrSearch["periodo"] = $data['periodo'];
        }
        $arrData = array();
        $admitido_model = new Admitido();
        if (count($arrSearch) > 0) {
            $arrData = $admitido_model->consultarReportAdmitidos($arrSearch, true);
        } else {
            $arrData = $admitido_model->consultarReportAdmitidos(array(), true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionDownload($route, $type) {
        //$grupo = new Grupo();
        
        if (Yii::$app->session->get('PB_isuser')) {
           
            $route = str_replace("../", "", $route);
            if (preg_match("/^\/uploads\/" . $this->folder_cv . "\/\d*\/.*\.pdf/", $route)) {
                 
                $url_image = Yii::$app->basePath . $route;
                $arrIm = explode(".", $url_image);
                \app\models\Utilities::putMessageLogFile('ewwe ' . $url_image);
                
                $typeImage = $arrIm[count($arrIm) - 1];
                exit($url_image);
                if (file_exists($url_image)) {
                    exit($route);
                    if (strtolower($typeImage) == "pdf") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/pdf");
                        if ($type == "view") {
                            header('Content-Disposition: inline; filename="contrato_' . time() . '.pdf";');
                        } else {
                            header('Content-Disposition: attachment; filename="contrato_' . time() . '.pdf";');
                        }
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_image));
                        readfile($url_image);
                        //return file_get_contents($url_image);
                    }
                }
            }
        }
        exit();
    }

}
