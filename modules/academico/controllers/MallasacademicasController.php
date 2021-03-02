<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use app\modules\academico\models\EstudioAcademico;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\MallaAcademica;
use app\models\Persona;
use app\models\Usuario;
use yii\base\Security;
use yii\base\Exception;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

academico::registerTranslations();
admision::registerTranslations();
financiero::registerTranslations();

class MallasacademicasController extends \app\components\CController {
    
    public function actionIndex() {            
        $mod_malla = new MallaAcademica();
        
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            
            $arr_mallas = $mod_malla->consultarMallas($arrSearch);
            return $this->renderPartial('index-grid', [
                        "model" => $arr_mallas,
            ]);
        } else {
            $arr_mallas = $mod_malla->consultarMallas();
        }        
        return $this->render('index', [
                    'model' => $arr_mallas,                   
        ]);
    } 
    
    public function actionIndexdetalle() {    
        $malla_id = base64_decode($_GET['maca_id']);        
        $mod_malla = new MallaAcademica();
        
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            
            $arr_mallas = $mod_malla->consultarDetallemallaXid($malla_id,$arrSearch);
            return $this->renderPartial('indexdetalle-grid', [
                        "model" => $arr_mallas,
            ]);
        } else {
            $arr_mallas = $mod_malla->consultarDetallemallaXid($malla_id);
        }     
        $arr_cabecera_malla = $mod_malla->consultarCabeceraMalla($malla_id);
        return $this->render('indexdetalle', [
                    'model' => $arr_mallas,         
                    'cabecera' => $arr_cabecera_malla,
                    'malla' => $malla_id,
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
            academico::t("Academico", "Subject Code"),
            academico::t("Academico", "Subject"),
            academico::t("Academico", "Semester"),
            academico::t("Academico", "Credits"),
            academico::t("Academico", "Unidad Estudio"),
            academico::t("Academico", "Training"),
            academico::t("Academico", "Subject Requirement"),            
        );            
        $mod_malla = new MallaAcademica();              
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];    
        $malla_id = $data['malla_id'];   
        //Utilities::putMessageLogFile('Malla controlador:'.$malla_id);

        $arrData = array();
        if (empty($arrSearch["search"])) {
            $arrData = $mod_malla->consultarDetallemallaXid($malla_id, array(), true);
        } else {           
            $arrData = $mod_malla->consultarDetallemallaXid($malla_id, $arrSearch, true);
        }       
        $arr_cabecera_malla = $mod_malla->consultarCabeceraMalla($malla_id);        
        $nameReport = academico::t("Academico", "Malla Académica de ". $arr_cabecera_malla[0]["malla"]);
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {        
        $report = new ExportFile();
        $mod_malla = new MallaAcademica();        
      
        $data = Yii::$app->request->get();
        $arr_body = array();
        $arrSearch["search"] = $data['search'];      
        $malla_id = $data['malla_id'];     

        $arr_head = array(
            academico::t("Academico", "Subject Code"),
            academico::t("Academico", "Subject"),
            academico::t("Academico", "Semester"),
            academico::t("Academico", "Credits"),
            academico::t("Academico", "Unidad Estudio"),
            academico::t("Academico", "Training"),
            academico::t("Academico", "Subject Requirement"),     
        );

        if (empty($arrSearch["search"])) {
            $arr_body = $mod_malla->consultarDetallemallaXid($malla_id, array(), true);
        } else {
            $arr_body = $mod_malla->consultarDetallemallaXid($malla_id, $arrSearch, true);
        }
        
        $arr_cabecera_malla = $mod_malla->consultarCabeceraMalla($malla_id);
        $this->view->title = academico::t("Academico", "Malla Académica de ". $arr_cabecera_malla[0]["malla"]); // Titulo del reporte
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
    public function actionMallacarrera() { 
        $per_id = @Yii::$app->session->get("PB_perid");           
        $mod_malla = new MallaAcademica(); 
        $arr_codmalla = $mod_malla->consultarMallaEstudiante($per_id);      
        return $this->render('mallas', [
                    'arr_codmalla' => $arr_codmalla,                   
        ]);
    } 
}
