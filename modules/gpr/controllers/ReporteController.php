<?php

namespace app\modules\gpr\controllers;

use app\models\ExportFile;
use Yii;
use app\models\Utilities;
use app\modules\gpr\models\Entidad;
use app\modules\gpr\models\Hito;
use app\modules\gpr\models\Indicador;
use app\modules\gpr\models\ObjetivoOperativo;
use app\modules\gpr\models\PlanificacionPedi;
use app\modules\gpr\models\PlanificacionPoa;
use app\modules\gpr\models\Proyecto;
use app\modules\gpr\models\ResponsableUnidad;
use app\modules\gpr\models\UnidadGpr;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class ReporteController extends \app\components\CController {
    public function actionIndex(){
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $isAdmin = ResponsableUnidad::userIsAdmin($user_id, $idEmpresa);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getPlan"])) {
                $tplan = $data['tplan'];
                $planes = array();
                if($tplan == 1){ // PEDI
                    $planes = PlanificacionPedi::getArrayPlanPedi();
                }else{ // POA
                    $planes = PlanificacionPoa::getArrayPlanPoa();
                }
                $arr_planes = array_merge(['0' => ['id' => '0', 'name' => gpr::t('proyecto', '-- Select an Option --')]], $planes);
                $message = array("planes" => $arr_planes);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_tplan = [
            '0' => gpr::t('proyecto', '-- Select a type for search by --'), 
            '1' => gpr::t('planificacionpedi', 'Pedi Planning'), 
            '2' => gpr::t('planificacionpoa', 'Poa Planning'),
        ];
        $arr_filter = ['0' => gpr::t('proyecto', '-- Select an Option --')];
        $arr_reporte = [
            '0' => gpr::t('proyecto', '-- Select an Option --'), 
            '1' => gpr::t('reporte', 'GPR Report'), 
            '2' => gpr::t('reporte', 'Indicators Report'), 
            '3' => gpr::t('reporte', 'Detail Indicator Report'),
            '4' => gpr::t('reporte', 'Project Report'),
        ];
        return $this->render('index', [
            'model' => new ArrayDataProvider(array()),
            'arr_tplan' => $arr_tplan,
            'arr_filter' => $arr_filter,
            'arr_reporte' => $arr_reporte,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function actionDownload(){
        $data = Yii::$app->request->get();
        $tplan = $data['tplan'];
        $filterId = $data['filter'];
        $tipo = $data['tipo'];
        $temp = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $isAdmin = ResponsableUnidad::userIsAdmin($user_id, $idEmpresa);
        $entidad_model = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id', $idEmpresa]);

        $report = new ExportFile();
        
        $arrData1 = array();  
        $arrData2 = array();
        $arrData3 = array();
        $arrData4 = array();
        $arrHeader = array();

        if($tipo == 1){
            $this->view->title = gpr::t("reporte", "GPR Report"); // Titulo del reporte
            $temp =  "exportpdfGPR";
            $arrData3 = UnidadGpr::getArrayUnidad(false, true);
            $modelProyecto = new Proyecto();
            $arrData4 = $modelProyecto->getAllProyectoGrid();
            if($tplan == 1){ // PEDI
                $arrData1 = Indicador::getReporteIndicadores($filterId, NULL);
                $arrData2 = Indicador::getReportMetasIndicador($filterId, NULL);
            }else{ // POA
                $arrData1 = Indicador::getReporteIndicadores(NULL, $filterId);
                $arrData2 = Indicador::getReportMetasIndicador(NULL, $filterId);
            }
        }elseif($tipo == 2){
            $this->view->title = gpr::t("reporte", "Indicators Report"); // Titulo del reporte
            $temp =  "exportpdfIndicadores";
            $arrData3 = UnidadGpr::getArrayUnidad(false, true);
            if($tplan == 1){ // PEDI
                $arrData1 = Indicador::getReporteIndicadores($filterId, NULL);
                $arrData2 = Indicador::getReportMetasIndicador($filterId, NULL);
            }else{ // POA
                $arrData1 = Indicador::getReporteIndicadores(NULL, $filterId);
                $arrData2 = Indicador::getReportMetasIndicador(NULL, $filterId);
            }
        }elseif($tipo == 3){
            $this->view->title = gpr::t("reporte", "Detail Indicator Report"); // Titulo del reporte
            $temp =  "exportpdfIndDetalle";
            $arrData3 = ObjetivoOperativo::getArrayObjOperativo();
            if($tplan == 1){ // PEDI
                $arrData1 = Indicador::getReporteIndicadores($filterId, NULL);
                $arrData2 = Indicador::getReportMetasIndicador($filterId, NULL);
                $arrHeader['TipoLabel'] = gpr::t('planificacionpedi', 'Pedi Planning');
                $modelPedi = PlanificacionPedi::findOne([$filterId]);
                $arrHeader['Tipo'] = $modelPedi->pped_nombre;
            }else{ // POA
                $arrData1 = Indicador::getReporteIndicadores(NULL, $filterId);
                $arrData2 = Indicador::getReportMetasIndicador(NULL, $filterId);
                $arrHeader['TipoLabel'] = gpr::t('planificacionpoa', 'Poa Planning');
                $modelPoa = PlanificacionPoa::findOne([$filterId]);
                $arrHeader['Tipo'] = $modelPoa->ppoa_nombre;
            }
        }elseif($tipo == 4){
            $this->view->title = gpr::t("reporte", "Project Report"); // Titulo del reporte
            $temp =  "exportpdfProyectos";
            $modelHito = new Hito();
            $modelProyecto = new Proyecto();
            $arrData1 = $modelProyecto->getAllProyectoGrid();
            $arrData2 = $modelHito->getAllHitoGrid();
        }

        if(empty($arrData1) || empty($arrData2)){
            $temp = "emptyReport";
        }

        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical    
        $report->createReportPdf(
            $this->render($temp, [
                    'arr_body' => $arrData1,
                    'arr_body2' => $arrData2,
                    'arr_body3' => $arrData3,
                    'arr_body4' => $arrData4,
                    'arrHeader' => $arrHeader,
            ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);       
    }
}