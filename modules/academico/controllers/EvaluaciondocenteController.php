<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\ResumenEvaluacionDocente;
use DateTime;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;

admision::registerTranslations();

class EvaluaciondocenteController extends \app\components\CController {

    public function actionIndex() {
        $mod_evaluacion = new ResumenEvaluacionDocente();
        $semestre = $mod_evaluacion->consultarSemestre();
        $tipoevaluacion = $mod_evaluacion->consultarTipoEvaluacion();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["tipo_evaluacion"] = $data['tipo_evaluacion'];
            $arrSearch["semestre"] = $data['semestre'];
            $arr_evaluacion = $mod_evaluacion->consultarResumenEvaluacion($arrSearch);
            return $this->render('index-grid', [
                        'model' => $arr_evaluacion,
            ]);
        } else {
            $arr_evaluacion = $mod_evaluacion->consultarResumenEvaluacion($arrSearch);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        return $this->render('index', [
                    'model' => $arr_evaluacion,
                    'arr_semetre' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $semestre), "id", "name"),
                    'arr_tipoevaluacion' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $tipoevaluacion), "id", "name"),
        ]);
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
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Semester"),
            academico::t("Academico", "Type Evaluation | Hours | Evaluation"),
            academico::t("Academico", "Evaluation Completed"),
            academico::t("Academico", "Total Hours"),
            academico::t("Academico", "Total Evaluation")
        );
        $mod_evaluacion = new ResumenEvaluacionDocente();
        $data = Yii::$app->request->get();
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["tipo_evaluacion"] = $data['tipo_evaluacion'];
        $arrSearch["semestre"] = $data['semestre'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_evaluacion->consultarResumenEvaluacion(array(), true);
        } else {
            $arrData = $mod_evaluacion->consultarResumenEvaluacion($arrSearch, true);
        }
        $nameReport = academico::t("Academico", "List Evaluation Teacher");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "List Evaluation Teacher"); // Titulo del reporte

        $mod_evaluacion = new ResumenEvaluacionDocente();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["tipo_evaluacion"] = $data['tipo_evaluacion'];
        $arrSearch["semestre"] = $data['semestre'];

        $arr_head = array(
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Semester"),
            academico::t("Academico", "Type Evaluation | Hours | Evaluation"),
            academico::t("Academico", "Evaluation Completed"),
            academico::t("Academico", "Total Hours"),
            academico::t("Academico", "Total Evaluation")
        );

        if (empty($arrSearch)) {
            $arr_body = $mod_evaluacion->consultarResumenEvaluacion(array(), true);
        } else {
            $arr_body = $mod_evaluacion->consultarResumenEvaluacion($arrSearch, true);
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
