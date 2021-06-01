<?php

/* 
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions 
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by 
 * Yii Software LLC (http://www.yiisoft.com) Copyright © 2008
 *
 */

namespace app\modules\gfinanciero\controllers;

use Yii;
use app\components\CController;
use app\models\ExportFile;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;
use app\modules\gfinanciero\models\Bodega;
use app\modules\gfinanciero\models\Articulo;
use app\modules\gfinanciero\models\ExistenciaBodega;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;

financiero::registerTranslations();

class MovimientoitemController extends CController {
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $_SESSION['JSLANG']['No_data'] = financiero::t('bodega',"No data");
        $_SESSION['JSLANG']['Select_an_Cellar'] = financiero::t('bodega',"Select an Cellar");
        $_SESSION['JSLANG']['Please_select_an_Item_'] = financiero::t('bodega',"Please select an Item.");
        $varRef['TOT_ING']=0;
        $varRef['TOT_EGR']=0;
        $model = new Articulo();      
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if($data['ls_query_id'] == "autocomplete-articulo"){
                $query = $data['ls_query'];
                $currentPage = $data['ls_current_page'];
                $perPage = $data['ls_items_per_page'];
                $arr_data = Articulo::getDataColumnsQueryWidget();
                return SearchAutocomplete::renderView($query, 
                    $arr_data['con'], 
                    $arr_data['cols'], 
                    $arr_data['aliasCols'],
                    $arr_data['colVisible'],
                    $arr_data['table'], 
                    $arr_data['where'], 
                    $arr_data['order'], 
                    $arr_data['limitPages'], 
                    $currentPage, 
                    $perPage);
            }
            if (isset($data["saldoBodega"])) {            
                $result =  $model->getMovimientotemsGrid($data, false,false,true);
                if($result["status"]){
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $result);
                }else{
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Success'), 'false', NULL);
                }
            }
        }

        $arr_default = ["Ids" => "0", "Nombre" => financiero::t('bodega', '-- Select an Item Name --')];
        $data = Yii::$app->request->get();
        $fecIni="";
        $bodega=Bodega::getBodegas();
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                'model' => $model->getMovimientotemsGrid($data, true,false,false),
                'bodega' => ArrayHelper::map(array_merge([$arr_default], $bodega), "Ids", "Nombre"),
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getMovimientotemsGrid(NULL, true,false,false),
            'bodega' => ArrayHelper::map(array_merge([$arr_default], $bodega), "Ids", "Nombre"),
        ]);
    }
    
    
    
    
    
    /**
     * Expexcel Action. Allow to download a Excel document from index page.
     *
     * @return void
     */
    public function actionExpexcel() {
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F","G", "H", "I");
        $arrHeader = array(
            financiero::t("bodega", "DATE"),
            financiero::t("bodega", "ENTRY"),
            financiero::t("bodega", "EGRESS"),
            financiero::t("bodega", "AMOUNT"),
            financiero::t("bodega", "BALANCE"),
            financiero::t("bodega", "STATUS"),
            financiero::t("bodega", "REFERENCE"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["CodBod"] = $data['codbod'];
            $arrSearch["CodArt"] = $data['codart'];
            $arrSearch["FecHas"] = $data['fechas'];            
        }
        $arrData = array();
        $model = new Articulo();
        if (count($arrSearch) > 0) {
            $arrData = $model->getMovimientotemsGrid($arrSearch, false,true,false);
        } else {
            $arrData = $model->getMovimientotemsGrid(NULL, false,false,false);
        }
        $nameReport = financiero::t("bodega", "Movement of items");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    /**
     * Exppdf Action. Allow to download a Pdf document from index page.
     *
     * @return void
     */
    public function actionExppdf() {
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("bodega", "Movement of items");  // Titulo del reporte
                //COD_BOD,NOM_BOD,DIR_BOD,TEL_N01,CORRE_E,NUM_ING,NUM_EGR
        $arrColumns = array(
            financiero::t("bodega", "DATE"),
            financiero::t("bodega", "ENTRY"),
            financiero::t("bodega", "EGRESS"),
            financiero::t("bodega", "AMOUNT"),
            financiero::t("bodega", "BALANCE"),
            financiero::t("bodega", "STATUS"),
            financiero::t("bodega", "REFERENCE"),
        );
       
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["CodBod"] = $data['codbod'];
            $arrSearch["CodArt"] = $data['codart'];
            $arrSearch["FecHas"] = $data['fechas']; 
        }
        $arrData = array();
        $model = new Articulo();
        $ExiBodItem=new ExistenciaBodega();
        $dataItem=$ExiBodItem->getExistenciaData($data['codbod'],$data['codart']);
        $arrHeader = [
            "empresa" => Yii::$app->session->get("PB_empresa"),
            "lbl_titulo" => financiero::t("bodega", "Movement of items"),
            "lbl_bodega" => financiero::t("bodega", "Cellar"),
            "lbl_articulo" => financiero::t("bodega", "Article Name"),
            "lbl_fecdes" => financiero::t("bodega", "Date from"),
            "lbl_fechas" => financiero::t("bodega", "Date Until"),
            "txt_bodega" => $dataItem['COD_BOD']." - ".$dataItem['NOM_BOD'],
            "txt_articulo" => $dataItem['COD_ART']." - ".$dataItem['DES_COM'],
            "txt_fecdes" =>  $dataItem['F_I_FIS'],
            "txt_fechas" =>  $data['fechas'],//fecha hasta
            
        ];

        if (count($arrSearch) > 0) {
            $arrData = $model->getMovimientotemsGrid($arrSearch, false,true,false);
        } else {
            $arrData = $model->getMovimientotemsGrid(NULL, false, false,false);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                    'arr_cols' => $arrColumns,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
}