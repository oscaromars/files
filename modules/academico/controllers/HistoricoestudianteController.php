<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use app\modules\academico\models\EstudioAcademico;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\Historicoestudiante;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\Estudiante;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\Especies;
use app\modules\academico\models\PlanificacionEstudiante;
use app\models\Persona;
use app\models\Usuario;
use yii\base\Security;
use yii\base\Exception;
use app\models\EmpresaPersona;
use app\models\UsuaGrolEper;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;



academico::registerTranslations();
admision::registerTranslations();
#financiero::registerTranslations();

class HistoricoestudianteController extends \app\components\CController {
	
	private function estados() {
		return [
		    '2' => Yii::t("formulario", "Reprobado"),
		    'null' => Yii::t("formulario", "Pendiente"),
		    '0' => Yii::t("formulario", "En Curso"),
		    '1' => Yii::t("formulario", "Aprobado"),
		];
	    }

	public function actionIndex() {
        $mod_estudiante = new Historicoestudiante();
        
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
                        
            $arr_estudiante = $mod_estudiante->consultarHistoricoEstudiante($arrSearch);
            return $this->renderPartial('index-grid', [
                        "model" => $arr_estudiante,
            ]);
        } else {
            $arr_estudiante = $mod_estudiante->consultarHistoricoEstudiante();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_estudiante->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $mod_estudiante->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_ninteres = $mod_estudiante->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_estudiante->consultarModalidad($arr_ninteres[0]["id"], 1);
        $arr_carrerra1 = $mod_estudiante->consultarCarreraModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);
        
        return $this->render('index', [
                    'model' => $arr_estudiante,
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_carrerra1), "id", "name"),
        ]);
    }

    public function actionHistorico() {
    	$per_id = base64_decode($_GET['per_id']);
        $mod_estudiante = new Historicoestudiante();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new Oportunidad();
        $especiesADO=new Especies();
        
        $per_id = @Yii::$app->session->get("PB_perid");
        $data = Yii::$app->request->get();
        $arr_EstudianteID = $mod_estudiante->consultarHistoricoID($per_id);
        $data_persona = $especiesADO->consultaDatosEstudiante($per_id); 
        
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);
        return $this->render('historicoestudiante-index', [
                    'model' => $arr_EstudianteID,
                    'arr_persona' => $data_persona,
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_carrerra1), "id", "name"),
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M");

        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Carrera"),
            Yii::t("formulario", "Modalidad"),
            Yii::t("formulario", "Semestre"),
            Yii::t("formulario", "Codigo Asignatura"),
            Yii::t("formulario", "Asignatura"),
            Yii::t("formulario", "Nota"),
            Yii::t("formulario", "Estado"),
            Yii::t("formulario", "Periodo"),
            Yii::t("formulario", "Bloque"),
        );

        $mod_estudiante = new Historicoestudiante();
        
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
            
        }
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_estudiante->consultarHistoricoEstudiante(array(),true);
        } else {
            $arrData = $mod_estudiante->consultarHistoricoEstudiante($arrSearch,true);
        }

        $nameReport = academico::t("Academico", "Récord Académico");

        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
    	$report = new ExportFile();
        $this->view->title = academico::t("Academico", "Reporte academico de materias"); // Titulo del reporte
        $arr_head = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Carrera"),
            Yii::t("formulario", "Modalidad"),
            Yii::t("formulario", "Semestre"),

            Yii::t("formulario", "Codigo Asignatura"),
            Yii::t("formulario", "Asignatura"),
            Yii::t("formulario", "Nota"),
            Yii::t("formulario", "Estado"),
            Yii::t("formulario", "Periodo"),
            Yii::t("formulario", "Bloque"),
        );
        $mod_estudiante = new Historicoestudiante();
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
        }


        if (empty($arrSearch)) {
            $arr_body = $mod_estudiante->consultarHistoricoEstudiante(array(),true);
        } else {
            $arr_body = $mod_estudiante->consultarHistoricoEstudiante($arrSearch,true);
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
    public function actionExpexcelestudiante() {
    	$per_id = base64_decode($_GET['per_id']);
    	$per_id = @Yii::$app->session->get("PB_perid");
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M");

        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Carrera"),
            Yii::t("formulario", "Modalidad"),
            Yii::t("formulario", "Semestre"),
            Yii::t("formulario", "Codigo Asignatura"),
            Yii::t("formulario", "Asignatura"),
            Yii::t("formulario", "Nota"),
            Yii::t("formulario", "Estado"),
            Yii::t("formulario", "Periodo"),
            Yii::t("formulario", "Bloque"),
        );

        $mod_estudiante = new Historicoestudiante();
        
        $data = Yii::$app->request->get();
        $arrData = $mod_estudiante->consultarHistoricoID($per_id,true);
        
        $nameReport = academico::t("Academico", "Récord Académico");

        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfestudiante() {
    	$per_id = base64_decode($_GET['per_id']);
    	$per_id = @Yii::$app->session->get("PB_perid");
    	$report = new ExportFile();
        $this->view->title = academico::t("Academico", "Reporte academico de materias"); // Titulo del reporte
        $arr_head = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Carrera"),
            Yii::t("formulario", "Modalidad"),
            Yii::t("formulario", "Semestre"),
            Yii::t("formulario", "Codigo Asignatura"),
            Yii::t("formulario", "Asignatura"),
            Yii::t("formulario", "Nota"),
            Yii::t("formulario", "Estado"),
            Yii::t("formulario", "Periodo"),
            Yii::t("formulario", "Bloque"),
        );
        $mod_estudiante = new Historicoestudiante();
        
        $data = Yii::$app->request->get();
        $arr_body = $mod_estudiante->consultarHistoricoID($per_id,true);
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
