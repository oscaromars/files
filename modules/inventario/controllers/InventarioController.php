<?php

namespace app\modules\inventario\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use app\models\ExportFile;
use yii\base\Exception;
use app\modules\inventario\models\ActivoFijo;
use app\modules\inventario\models\EmpresaInventario;
use app\modules\inventario\models\TipoBien;
use app\modules\inventario\models\Categoria;
use app\models\Departamento;
use app\models\Area;
use app\modules\inventario\Module as inventario;

class InventarioController extends \app\components\CController {

    public function actionIndex() {
        $mod_inventario = new ActivoFijo();   
        $mod_empinv = new EmpresaInventario();
        $mod_tipobien = new TipoBien();
        $mod_categoria = new Categoria();
        $mod_departamento = new Departamento();
        $mod_area = new Area();
        $data = Yii::$app->request->get();
                
        if ($data['PBgetFilter']) {              
            $arrSearch["search"] = $data['codigo'];
            $arrSearch["tipobien_id"] = $data['tipo_bien'];
            $arrSearch["categoria_id"] = $data['categoria'];            
            $arrSearch["departamento_id"] = $data['departamento'];            
            $arrSearch["area_id"] = $data['area'];            
            $resp_listado = $mod_inventario->consultarInventario($arrSearch);
        } else {
            $resp_listado = $mod_inventario->consultarInventario();
        }               
        if (Yii::$app->request->isAjax) {          
            $data = Yii::$app->request->post();            
            if (isset($data["getcategoria"])) {
                $resp_categoria = $mod_categoria->consultarCategoria($data["tipobien_id"]);
                $message = array("categorias" => $resp_categoria);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getarea"])) {
                \app\models\Utilities::putMessageLogFile('dpto:'.$data["dpto_id"]);   
                $resp_areas = $mod_area->consultarAreas($data["dpto_id"],1);
                $message = array("areas" => $resp_areas);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }    
        $arr_empresa_inv = $mod_empinv->consultarEmpresaInv();
        $arr_tipo_bien = $mod_tipobien->consultarTipoBien();        
        $arr_categoria = $mod_categoria->consultarCategoria(1);
        $arr_departamento = $mod_departamento->consultarDepartamento(1);
        $arr_area = $mod_area->consultarAreas(1,1);
        return $this->render('index', [
                    'arr_empresa' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todos"]], $arr_empresa_inv), "id", "name"),
                    'arr_tipo_bien' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todos"]], $arr_tipo_bien), "id", "name"),
                    'arr_categoria' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todos"]], $arr_categoria), "id", "name"),
                    'arr_departamento' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todos"]], $arr_departamento), "id", "name"),
                    'arr_area' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todos"]], $arr_area), "id", "name"),
                    'model' => $resp_listado,
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T");
                     
        $arrHeader = array(
            inventario::t("inventario", "Code"),
            inventario::t("inventario", "Department"),
            inventario::t("inventario", "Work area"),
            inventario::t("inventario", "Space"),
            inventario::t("inventario", "Edificio"),
            inventario::t("inventario", "Custodian"),
            inventario::t("inventario", "Type Good"),
            inventario::t("inventario", "Category"),
            inventario::t("inventario", "Sequential"),            
            inventario::t("inventario", "Quantity"),
            Yii::t("formulario", "Description"),
            inventario::t("inventario", "Brand"),
            inventario::t("inventario", "Model"),
            inventario::t("inventario", "Serie"),
            inventario::t("inventario", "RAM"),
            inventario::t("inventario", "Disk HDD"),
            inventario::t("inventario", "Disk SDD"),
            inventario::t("inventario", "Processor"),
        );
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["tipobien_id"] = $data['tipo_bien'];
        $arrSearch["categoria_id"] = $data['categoria'];            
        $arrSearch["departamento_id"] = $data['departamento'];            
        $arrSearch["area_id"] = $data['area'];  

        $mod_inventario = new ActivoFijo();  
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_inventario->consultarInventarioExcel(array(), true);            
        } else {
            $arrData = $mod_inventario->consultarInventarioExcel($arrSearch, true);            
        }
        $nameReport = inventario::t("inventario", "UTEG Inventory List");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = inventario::t("inventario", "UTEG Inventory List"); // Titulo del reporte

        $mod_inventario = new ActivoFijo(); 
        $data = Yii::$app->request->get();
        $arr_body = array();
        $arrSearch["search"] = $data['search'];
        $arrSearch["tipobien_id"] = $data['tipo_bien'];
        $arrSearch["categoria_id"] = $data['categoria'];            
        $arrSearch["departamento_id"] = $data['departamento'];            
        $arrSearch["area_id"] = $data['area']; 

        $arr_head = array(
            inventario::t("inventario", "Department"),
            inventario::t("inventario", "Work area"),
            inventario::t("inventario", "Category"),
            inventario::t("inventario", "Code"),
            inventario::t("inventario", "Custodian"),
            inventario::t("inventario", "Quantity"),
        );
        if (empty($arrSearch)) {
            $arr_body = $mod_inventario->consultarInventario(array(), true);
        } else {
            $arr_body = $mod_inventario->consultarInventario($arrSearch, true);
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
