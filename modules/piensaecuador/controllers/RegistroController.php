<?php

namespace app\modules\piensaecuador\controllers;

use Yii;
use app\modules\piensaecuador\models\PersonaExterna;
use app\models\ExportFile;
use app\models\Utilities;
use app\modules\piensaecuador\Module as piensaecuador;
piensaecuador::registerTranslations();

class RegistroController extends \app\components\CController {

    public function actionIndex() {
        $model = new PersonaExterna();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                    "model" => $model->getAllPersonaExtGrid($data["search"], true),
                    'dataInteres' => $model->getAllInteresByPersona(NULL)
                ]);
            }
        }
        return $this->render('index', [
            'model' => $model->getAllPersonaExtGrid(NULL, true),
            'dataInteres' => $model->getAllInteresByPersona(NULL)
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R","S");
        $arrHeader = array(
            'Id',
            Yii::t("formulario", "Names"),
            Yii::t("formulario", 'Last Names'),
            Yii::t("formulario", "Dni"),
            Yii::t("perfil", 'Email'),
            Yii::t("perfil", 'CellPhone'),
            Yii::t("perfil", 'Phone'),
            Yii::t("perfil", 'Sex'),
            Yii::t("perfil", 'Birth Date'),
            Yii::t("general", 'State'),
            Yii::t("general", 'City'),
            //piensaecuador::t("interes", 'Event'),
            piensaecuador::t("interes", 'Instruction Level'),
            piensaecuador::t("interes", 'Activity'),
            piensaecuador::t("interes", 'Occupation'),
            piensaecuador::t("interes",'Registry Date'),
            Yii::t("general", "Status")
        );
        $model = new PersonaExterna();
        $data = Yii::$app->request->get();
        $arrData = $queryData = array();
        $arrSearch["search"] = $data['search'];
        if (empty($arrSearch)) {
            $arrData = $model->getAllPersonaExtGrid(NULL, false);
            $queryData = $model->getAllInteresByPersona(NULL);
        } else {
            $arrData = $model->getAllPersonaExtGrid($data["search"], false);
            $queryData = $model->getAllInteresByPersona($data["search"]);
        }
        
        foreach($arrData as $key => $value){
            $pext_id = $value['id'];
            $keys = array_keys(array_column($queryData, 'id'), $pext_id);
            
            $cont = 0;
            $newValue = "";
            foreach($keys as $key2 => $value2){
                $id = $value2;
                $newValue .= $queryData[$id]['interes'];
                $cont++;
                if(count($keys) > $cont)
                    $newValue .= " | ";
            }
            $arrData[$key]['NivelInteresId'] = $newValue;
        }

        $nameReport = piensaecuador::t("interes", "Registries");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $model = new PersonaExterna();
        $this->view->title = piensaecuador::t("interes", "Registries"); // Titulo del reporte
        $arrHeader = array(
            'Id',
            Yii::t("formulario", "Names"),
            Yii::t("formulario", 'Last Names'),
            Yii::t("formulario", "Dni"),
            Yii::t("perfil", 'Email'),
            Yii::t("perfil", 'CellPhone'),
            Yii::t("perfil", 'Phone'),
            Yii::t("perfil", 'Sex'),
            Yii::t("perfil", 'Birth Date'),
            Yii::t("general", 'State'),
            Yii::t("general", 'City'),
            //piensaecuador::t("interes", 'Event'),
            piensaecuador::t("interes", 'Instruction Level'),
            piensaecuador::t("interes", 'Activity'),
            piensaecuador::t("interes", 'Occupation'),
            piensaecuador::t("interes",'Registry Date'),
            Yii::t("general", "Status")
        );
        $data = Yii::$app->request->get();
        $arrData = $queryData = array();
        $arrSearch["search"] = $data['search'];
        if (empty($arrSearch)) {
            $arrData = $model->getAllPersonaExtGrid(NULL, false);
            $queryData = $model->getAllInteresByPersona(NULL);
        } else {
            $arrData = $model->getAllPersonaExtGrid($data["search"], false);
            $queryData = $model->getAllInteresByPersona($data["search"]);
        }

        foreach($arrData as $key => $value){
            $pext_id = $value['id'];
            $keys = array_keys(array_column($queryData, 'id'), $pext_id);
            
            $cont = 0;
            $newValue = "";
            foreach($keys as $key2 => $value2){
                $id = $value2;
                $newValue .= $queryData[$id]['interes'];
                $cont++;
                if(count($keys) > $cont)
                    $newValue .= " | ";
            }
            $arrData[$key]['NivelInteresId'] = $newValue;
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
