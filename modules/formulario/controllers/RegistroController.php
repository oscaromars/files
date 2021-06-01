<?php

namespace app\modules\formulario\controllers;

use Yii;
use app\modules\formulario\models\PersonaFormulario;
use yii\helpers\ArrayHelper;
use app\models\ExportFile;
use app\models\Utilities;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\Module as academico;
Academico::registerTranslations();

class RegistroController extends \app\components\CController {

    public function actionIndex() {
        $mod_PersForm = new PersonaFormulario();
        $mod_unidad = new UnidadAcademica();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getcarrera"])) {
                $uaca_id = $data["uaca_id"];
                $carrera_programa = $mod_PersForm->consultarCarreraProgXUnidad($uaca_id);   
                $message = array("carr_prog" => $carrera_programa);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);                
            }                               
        }        
            
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["carrera"] = $data['carrera'];
            $arrSearch["search"] = $data['search'];            
            $respForm = $mod_PersForm->getAllPersonaFormGrid($arrSearch, false);
            return $this->renderPartial('index-grid', [
                        "model" => $respForm,
            ]);
        }
        
        $arr_unidad_Uteg = $mod_unidad->consultarUnidadAcademicasxUteg();   
        $arr_carrera_prog = $mod_PersForm->consultarCarreraProgXUnidad(1); 
        return $this->render('index', [
            'model' => $mod_PersForm->getAllPersonaFormGrid(NULL, false),   
            "arr_unidad" => ArrayHelper::map($arr_unidad_Uteg, "id", "name"),
            "arr_carrera_prog" => ArrayHelper::map($arr_carrera_prog, "id", "name"),
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
        $arrHeader = array(           
            Yii::t("formulario", "Names"),
            Yii::t("formulario", 'Last Names'),
            Yii::t("formulario", "Dni"),
            Yii::t("perfil", 'Email'),
            Yii::t("perfil", 'CellPhone')."/".Yii::t("formulario", 'Phone'),
            Yii::t("formulario", 'Institution'),
            Yii::t("general", 'State'),
            Yii::t("general", 'City'),
            Yii::t("formulario",'Academic unit'),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Registration Date"),           
        );
        $mod_PersForm = new PersonaFormulario();
        $data = Yii::$app->request->get();    
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["carrera"] = $data['carrera'];
        $arrSearch["search"] = $data['search'];      
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_PersForm->getAllPersonaFormGrid(array(), true);
        } else {
            $arrData = $mod_PersForm->getAllPersonaFormGrid($arrSearch, true);
        }
        $nameReport = Yii::t("formulario", "Listar Registros de Ficha");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();        
        $this->view->title = Yii::t("formulario", "Listar Registros de Ficha"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "Names"),
            Yii::t("formulario", 'Last Names'),
            Yii::t("formulario", "Dni"),
            Yii::t("perfil", 'Email'),
            Yii::t("perfil", 'CellPhone')."/".Yii::t("formulario", 'Phone'),
            Yii::t("formulario", 'Institution'),
            Yii::t("general", 'State'),
            Yii::t("general", 'City'),
            Yii::t("formulario",'Academic unit'),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Registration Date"),   
        );
        $mod_PersForm = new PersonaFormulario();
        $data = Yii::$app->request->get();    
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["carrera"] = $data['carrera'];
        $arrSearch["search"] = $data['search'];      
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_PersForm->getAllPersonaFormGrid(array(), true);
        } else {
            $arrData = $mod_PersForm->getAllPersonaFormGrid($arrSearch, true);
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
}
