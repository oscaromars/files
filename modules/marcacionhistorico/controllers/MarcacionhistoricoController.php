<?php

namespace app\modules\marcacionhistorico\controllers;

use Yii;
use app\models\ExportFile;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\RegistroMarcacion;
use DateTime;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\marcacionhistorico\models\RegistroMarcacionHistorial;
use app\modules\marcacionhistorico\Module as marcacion;

admision::registerTranslations();
marcacion::registerTranslations();
academico::registerTranslations();

class MarcacionhistoricoController extends \app\components\CController {

    public function actionIndex() {
        $mod_marcacion = new RegistroMarcacionHistorial();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademico();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["materia"] = $data['materia'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            //$arrSearch["periodo"] = $data['periodo'];
            $arr_historico = $mod_marcacion->consultarMarcacionHistorica($arrSearch);
            return $this->render('index-grid', [
                        'model' => $arr_historico,
            ]);
        } else {
            $arr_historico = $mod_marcacion->consultarMarcacionHistorica($arrSearch);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        return $this->render('index', [
                    'model' => $arr_historico,
                    'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $periodo), "id", "name"),
        ]);
    }

    public function actionCargarmarcaciones() {
        ini_set('memory_limit', '256M');
        $per_id = @Yii::$app->session->get("PB_perid");
        $mod_registro = new RegistroMarcacionHistorial();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                //$filenames = $files['name']; //Nombre Archivo

                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $filenames = $data["name_file"] . "." . $typeFile;
                $folder_path = Yii::$app->params["documentFolder"] . "marcacion/";
                //Utilities::putMessageLogFile($folder_path);
                if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
                    $dirFileEnd = $folder_path . $filenames;
                    //Utilities::putMessageLogFile($dirFileEnd);
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        //return true;
                        $arroout["status"] = true;
                        $arroout["ruta"] = $folder_path;
                        $arroout["nombre"] = $filenames;
                        return json_encode($arroout);
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                //$carga_archivo = $mod_registro->CargarArchivo($data["nombre"], $data["ruta"]);
               
                $carga_archivo = $mod_registro->uploadFileMarcacion($data["nombre"], $data["ruta"]);
                if ($carga_archivo['status']) {
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
        } else {
            return $this->render('cargarmarcaciones', []);
        }
        //return $this->render('cargarmarcaciones', []);
    }
    
    public function actionCargarhorarios() {
        ini_set('memory_limit', '256M');
        $per_id = @Yii::$app->session->get("PB_perid");
        $mod_registro = new RegistroMarcacionHistorial();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                //$filenames = $files['name']; //Nombre Archivo

                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $filenames = $data["name_file"] . "." . $typeFile;
                $folder_path = Yii::$app->params["documentFolder"] . "horarios/";
                //Utilities::putMessageLogFile($folder_path);
                if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
                    $dirFileEnd = $folder_path . $filenames;
                    //Utilities::putMessageLogFile($dirFileEnd);
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        //return true;
                        $arroout["status"] = true;
                        $arroout["ruta"] = $folder_path;
                        $arroout["nombre"] = $filenames;
                        return json_encode($arroout);
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                $carga_archivo = $mod_registro->uploadFileHorario($data["nombre"], $data["ruta"]);
                if ($carga_archivo['status']) {
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
        } else {
            return $this->render('cargarhorarios', []);
        }
        //return $this->render('cargarmarcaciones', []);
    }


    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H");
        $arrHeader = array(
            Yii::t("formulario", "Code"),
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Matter"),
            Yii::t("formulario", "Date"),
            academico::t("Academico", "Hour start date"),
            academico::t("Academico", "Hour end date")
        );
        $mod_marcacion = new RegistroMarcacionHistorial();
        $data = Yii::$app->request->get();
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["materia"] = $data['materia'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        //$arrSearch["periodo"] = $data['periodo'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_marcacion->consultarMarcacionHistorica(array(), true);
        } else {
            $arrData = $mod_marcacion->consultarMarcacionHistorica($arrSearch, true);
        }
        $nameReport = marcacion::t("Academico", "List Bearings History");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "List Bearings"); // Titulo del reporte

        $mod_marcacion = new RegistroMarcacionHistorial();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["materia"] = $data['materia'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        //$arrSearch["periodo"] = $data['periodo'];

        $arr_head = array(
           Yii::t("formulario", "Code"),
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Matter"),
            Yii::t("formulario", "Date"),
            academico::t("Academico", "Hour start date"),
            academico::t("Academico", "Hour end date")
        );

        if (empty($arrSearch)) {
            $arr_body = $mod_marcacion->consultarMarcacionHistorica(array(), true);
        } else {
            $arr_body = $mod_marcacion->consultarMarcacionHistorica($arrSearch, true);
        }

        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arr_head,
                    'arr_body' => $arr_body
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

}
