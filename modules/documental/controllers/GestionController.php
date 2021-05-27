<?php

namespace app\modules\documental\controllers;

use Yii;
use app\models\ExportFile;
use app\components\CController;
use app\modules\documental\models\Documento;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;
use yii\helpers\VarDumper;
use app\modules\documental\Module as documental;

documental::registerTranslations();

class GestionController extends \app\components\CController {

    public function actionIndex() {
        $documento_model = new documento();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $documento_model->getAllDocumentosGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $documento_model->getAllDocumentosGrid(NULL, true)
        ]);
    }

    public function actionUpload() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) .". Try again.")]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "documento/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;                        
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) .". Try again.")]);
                    }
                } else {                    
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) .". Try again.")]);
                }
            }

            if ($data["procesar_file"]) {
                ini_set('memory_limit', '256M');
                $model_documento = new Documento();
                try {
                    $carga_archivo = $model_documento->processFile($data["archivo"]);
                    if ($carga_archivo['status']) {
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente. " . $carga_archivo['message']),
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
                } catch (Exception $ex) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo."),
                        "title" => Yii::t('jslang', 'Error'),                        
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }                                
            }
        } else {
            return $this->render('cargar_documento');
        }        
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";        
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $uriFile = dirname(__DIR__) . "/views/gestion/files/template_empty.xlsx";
        $colPosition = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC");
        $arrHeader = array(            
            documental::t("documento", "UNITY"),
            documental::t("documento", "MACROPROCESS"),
            documental::t("documento", "PROCESS"),
            documental::t("documento", "CLASS"),
            documental::t("documento", "SERIE"),
            documental::t("documento", "SUBSERIE"),
            documental::t("documento", "SEQUENCE"),
            documental::t("documento", "FILE CODE"),
            documental::t("documento", "DOCUMENT CODE"),
            documental::t("documento", "PRODUCTION DATE"),
            documental::t("documento", "DOCUMENTARY PRODUCTION"),
            documental::t("documento", "INFORMATION DESCRIPTION"),
            documental::t("documento", "TYPE OF INFORMATION"),
            documental::t("documento", "LOCATION OF DIGITAL INFORMATION"),
            documental::t("documento", "CLASSIFICATION INFORMATION"),
            documental::t("documento", "TYPE OF PUBLIC INFORMATION"),
            documental::t("documento", "DOCUMENT STATUS"),
            documental::t("documento", "OBSERVATIONS"),
            documental::t("documento", "MANAGEMENT"),
            documental::t("documento", "CENTRAL"),
            documental::t("documento", "INTERMEDIATE"),
            documental::t("documento", "LEGAL BASE THAT SUPPORTS THE CONSERVATION TERM"),
            documental::t("documento", "ELIMINATION"),
            documental::t("documento", "CONSERVATION"),
            documental::t("documento", "SAMPLING"),
            documental::t("documento", "FULL CONSERVATION"),
            documental::t("documento", "MANAGEMENT"),
            documental::t("documento", "CENTRAL"),
        );
        $mod_documento = new Documento();
        $data = Yii::$app->request->get();
       /*  $arrSearch["profesor"] = $data['profesor']; 
        $arrSearch["materia"] = $data['materia'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["periodo"] = $data['periodo']; */
        // $arrData = array();
        /* if (empty($arrSearch)) {
            $arrData = $mod_marcacion->consultarRegistroMarcacion(array(), true);
        } else {
            $arrData = $mod_marcacion->consultarRegistroMarcacion($arrSearch, true);
        } */
        // echo($data["search"]);
        // dd($data["search"]);
        // d($data);
        // print_r($data);
        // echo(gettype($data["search"]));
        
        // if($data["search"]=="adm"){
        
        // }else {
        //     return $this->render('test');            
        // }
        $arrData = $mod_documento->getDataToExcel($data["search"]);
        $sheetName = "PLANT";
        $nameReport = "Documento";        
        // print_r($arrData);
        // Utilities::writeReporteXLS($uriFile, $arrHeader, $arrData, $sheetName);
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;                
        // // // print_r($arrData);
        
        // exit;
        // return $this->render('cargar_documento');
    }
}




