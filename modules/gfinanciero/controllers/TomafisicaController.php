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
use app\modules\gfinanciero\models\Articulo;
use app\modules\gfinanciero\models\Bodega;
use app\modules\gfinanciero\models\ExistenciaBodega;
use app\modules\gfinanciero\models\LineaArticulo;
use app\modules\gfinanciero\models\MarcaArticulo;
use app\modules\gfinanciero\models\TipoArticulo;
use app\modules\gfinanciero\models\TipoItem;
use app\modules\gfinanciero\Module as financiero;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use yii\data\ArrayDataProvider;
use yii\debug\models\timeline\DataProvider;

financiero::registerTranslations();

class TomafisicaController extends CController {

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $data = Yii::$app->request->post();
        if($data['ls_query_id'] == "autocomplete-bodega"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Bodega::getDataColumnsQueryWidget(true);
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
        if($data['getCant']){
            $codBod = $data['cod'];
            $numExi = $data['numEx'];
            $model = ExistenciaBodega::getListaArticulosFisicosBodega($codBod, $numExi, false);
            $message = array("cant" => number_format(((isset($model))?(count($model)):0), 0, '.', ','));
            return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
        }
        $arr_stock = [
            '0' => financiero::t('listaprecio', '-- All --'),
            '1' => '= 0',
            '2' => '> 0',
        ];
        
        $_SESSION['JSLANG']['Export'] = financiero::t('listaprecio', "Export");
        $_SESSION['JSLANG']['Cellar'] = financiero::t('bodega', "Cellar");
        $_SESSION['JSLANG']['Edit Physical Count'] = financiero::t('tomafisica', "Edit Physical Count");
        $_SESSION['JSLANG']['Select the type format to Export.'] = financiero::t('listaprecio', "Select the type format to Export.");
        $_SESSION['JSLANG']['PDF'] = financiero::t('listaprecio', "PDF");
        $_SESSION['JSLANG']['EXCEL'] = financiero::t('listaprecio', "EXCEL");
        $_SESSION['JSLANG']['Please select a Cellar.'] = financiero::t('tomafisica', "Please select a Cellar.");
        $_SESSION['JSLANG']['Code'] = financiero::t('tomafisica', "Code");
        $_SESSION['JSLANG']['Article Name'] = financiero::t("articulo", "Article Name");
        $_SESSION['JSLANG']['Stock'] = financiero::t('tomafisica', "Stock");
        $_SESSION['JSLANG']['Code'] = financiero::t('tomafisica', "Code");
        $_SESSION['JSLANG']['Physical Count'] = financiero::t('tomafisica', "Physical Count");
        $_SESSION['JSLANG']['Status'] = financiero::t('tomafisica', "Status");
        $_SESSION['JSLANG']['Are you sure to wish update Temporal Stock with Current Stock for all Items?'] = financiero::t('tomafisica', "Are you sure to wish update Temporal Stock with Current Stock for all Items?");
        $_SESSION['JSLANG']['Update'] = Yii::t('accion', "Update");
        $_SESSION['JSLANG']['Update Temporal Stock'] = financiero::t('tomafisica', "Update Temporal Stock");
        $_SESSION['JSLANG']['Valued Inventory Print'] = financiero::t('tomafisica', "Valued Inventory Print");
        $_SESSION['JSLANG']['Inventory Print'] = financiero::t('tomafisica', "Inventory Print");
        $_SESSION['JSLANG']['Report Inventory Review List'] = financiero::t('tomafisica', "Report Inventory Review List");
        $_SESSION['JSLANG']['Report Physical Count List'] = financiero::t('tomafisica', "Report Physical Count List");
        $_SESSION['JSLANG']['Report Inventory Valued List'] = financiero::t('tomafisica', "Report Inventory Valued List");
        $_SESSION['JSLANG']['Physical Stock Print'] = financiero::t('tomafisica', "Physical Stock Print");
        $data = Yii::$app->request->get();
        $bodega_id = (isset($data['cod']))?$data['cod']:"";
        $numex_value = (isset($data['numEx']))?$data['numEx']:"0";
        $bodega_name = "";
        if($bodega_id != ""){
            $mod_bod = Bodega::findOne(['COD_BOD' => $bodega_id, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $bodega_name = $mod_bod->NOM_BOD;
        }
        if (isset($data["PBgetFilter"])) {
            $codBod = $data['cod'];
            $numExi = $data['numEx'];
            return $this->render('index', [
                'arr_stock' => $arr_stock,
                "model" => ExistenciaBodega::getListaArticulosFisicosBodega($codBod, $numExi, true), 
                'bodega_id' => $bodega_id,
                'bodega_name' => $bodega_name,
                'numex_value' => $numex_value,
            ]);
        }
        $model = new ArrayDataProvider(array());
        return $this->render('index',[
            'arr_stock' => $arr_stock,
            'model' => $model,
            'bodega_id' => $bodega_id,
            'bodega_name' => $bodega_name,
            'numex_value' => $numex_value,
        ]);
    }

    /**
     * Save Action. Allow save the information from new Form.
     *
     * @return void
     */
    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid"); 
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if($data['saveItem']){
                    $code = $data["code"];
                    $desc = $data["desc"];
                    $conteo = $data["fisica"];
                    $stock = $data["stock"];
                    $estado = $data["estado"];
                    $bodega = $data["bodega"];

                    $model = ExistenciaBodega::findOne(['COD_BOD' => $bodega, 'COD_ART' => $code, 'EST_LOG' => '1', 'EST_DEL' => '1',]);
                    $model->EXI_TEM = $conteo;
                    $model->EST_ACT = "M";
                    if (!$model->save()) {
                        $arr_errs = $model->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                if($data['setTempAct']){
                    $bodega = $data["bodega"];
                    $numExi = $data['numEx'];
                    if(!ExistenciaBodega::setTemporalStockWithCurrentStock($bodega, $numExi)){
                        $arr_errs[] = financiero::t('tomafisica', "Problems to update with Currect Stock to Temporal Stock.");
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                if($data['saveInventario']){
                    $bodega = $data["bodega"];
                    $numExi = $data['numEx'];
                    // Se verifica que no haya items comprometidos
                    $cant = ExistenciaBodega::getCantItemsComprometidosXBodega($bodega, $numExi);
                    if($cant > 0){
                        $arr_errs[] = financiero::t('tomafisica', "There are {cant} item(s) Reserved.",[
                            "cant" => $cant,
                        ]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    // Se obtiene items de bodega con estado de Modificado
                    $model = array();
                    if(isset($numExi) && $numExi === "1"){
                        $model = ExistenciaBodega::find()
                            ->where(['COD_BOD' => $bodega, 'EST_LOG' => '1', 'EST_DEL' => '1', 'EST_ACT' => 'M',])
                            ->andWhere(['=', 'EXI_TOT', 0])->all();
                    }elseif(isset($numExi) && $numExi === "2"){
                        $model = ExistenciaBodega::find()
                            ->where(['COD_BOD' => $bodega, 'EST_LOG' => '1', 'EST_DEL' => '1', 'EST_ACT' => 'M',])
                            ->andWhere(['>', 'EXI_TOT', 0])->all();
                    }else{
                        $model = ExistenciaBodega::find()
                            ->where(['COD_BOD' => $bodega, 'EST_LOG' => '1', 'EST_DEL' => '1', 'EST_ACT' => 'M',])
                            ->all();
                    }
                    
                    foreach($model as $key => $item){
                        $item->EXI_ANT = $item->EXI_TOT; // Existencia anterior
                        $item->I_I_UNI = $item->EXI_TEM;
                        $item->EXI_TOT = $item->EXI_TEM;
                        $item->EXI_TEM = NULL;
                        $item->EST_ACT = '';
                        $item->F_I_FIS = date('Y-m-d');
                        $item->FEC_SIS = date('Y-m-d');
                        $item->HOR_SIS = date('H:i:s');
                        $item->USUARIO = $username;
                        $item->EQUIPO = Utilities::getClientRealIP();
                        if(!$item->save()){
                            $arr_errs = $item->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                        // se suma las EXI_TOT de todas las bodegas excepto la que se esta modificando 
                        $arr_data = ExistenciaBodega::getExistenciasTotalesEnBodegas($item->COD_ART, $numExi, [$bodega]);
                        $newExistencia = $arr_data[0]['Total'] + $item->EXI_TOT;
                        $modelArt = Articulo::findOne(['COD_ART' => $item->COD_ART, 'EST_LOG' => '1', 'EST_DEL' => '1', ]);
                        $modelArt->EXI_TOT = $newExistencia;
                        $modelArt->I_I_UNI = $newExistencia;
                        $modelArt->FEC_SIS = date('Y-m-d');
                        $modelArt->HOR_SIS = date('H:i:s');
                        $modelArt->USUARIO = $username;
                        $modelArt->EQUIPO = Utilities::getClientRealIP();
                        if(!$modelArt->save()){
                            $arr_errs = $modelArt->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                    $cantRegistros = count($model);
                    $message['wtmessage'] .= " " . financiero::t('tomafisica', "Number of updated records: {cant}.",[
                        "cant" => "<b>".$cantRegistros."</b>",
                    ]);
                }
                $trans->commit();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (\Exception $ex) {
                $exMsg = new CMsgException($ex);
                $trans->rollback();
                $msg = (($exMsg->getMsgLang()) != '')?($exMsg->getMsgLang()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
        return $this->redirect('index');
    }

    public function actionPrintvalorizados(){
        $this->view->title = financiero::t("tomafisica", "Report Inventory Valued List");
        $arr_models = Bodega::find()->select(['COD_BOD'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_BOD' => SORT_ASC])->asArray()->all();
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("tomafisica", "Price"),
        );
        for($i=0; $i<count($arr_models); $i++){
            $arrHeader[] = financiero::t("tomafisica", "Stock Bod") . str_pad($i+1, 2, "0", STR_PAD_LEFT);
        }
        $arrHeader[] = financiero::t("tomafisica", "Total");
        $arrHeader[] = financiero::t("tomafisica", "Total Price");
        $model = new ExistenciaBodega();
        $arrData = $model->getInventarioValorizadoXBodega();
        return $this->render('exportpdf', [
            'arr_head' => $arrHeader,
            'arr_body' => $arrData
        ]);
    }

    public function actionPrintinventario(){
        $this->view->title = financiero::t("tomafisica", "Report Inventory Review List");
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("tomafisica", "Stock Bod"),
            financiero::t("tomafisica", "Physical Temp."),
            financiero::t("tomafisica", "Stock Diff"),
            financiero::t("articulo", "Provider Price"),
            financiero::t("tomafisica", "Stock Cost"),
            financiero::t("tomafisica", "Physical Cost"),
            financiero::t("tomafisica", "Difference Cost"),
            financiero::t("tomafisica", "Status"),
        );
        $data = Yii::$app->request->get();
        $codBod = $data['bodega'];
        $numExi = $data['numEx'];
        $model = new ExistenciaBodega();
        $arrData = $model->getArticuloTomaFisicaXBodega($codBod, $numExi);
        return $this->render('exportpdf', [
            'arr_head' => $arrHeader,
            'arr_body' => $arrData
        ]);
    }

    public function actionPrinttomafisica(){
        $this->view->title = financiero::t("tomafisica", "Report Physical Count List");
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("tomafisica", "Stock"),
            financiero::t("tomafisica", "Observation"),
            financiero::t("tomafisica", "Status"),
        );
        $data = Yii::$app->request->get();
        $codBod = $data['bodega'];
        $numExi = $data['numEx'];
        $model = new ExistenciaBodega();
        $arrData = $model->getArticulosExistenciaXBodega($codBod, $numExi);
        return $this->render('exportpdf', [
            'arr_head' => $arrHeader,
            'arr_body' => $arrData
        ]);
    }

    /**
     * Expexcel Action. Allow to download a Excel document from index page.
     *
     * @return void
     */
    public function actionExpexcelvalorizados() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        //$colPosition = array("C", "D", "E", "F", "G");
        $colPosition = array();
        $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $arr_models = Bodega::find()->select(['COD_BOD'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_BOD' => SORT_ASC])->asArray()->all();
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("tomafisica", "Price"),
        );
        for($i=0; $i<count($arr_models); $i++){
            $arrHeader[] = financiero::t("tomafisica", "Stock Bod") . str_pad($i+1, 2, "0", STR_PAD_LEFT);
        }
        $arrHeader[] = financiero::t("tomafisica", "Total");
        $arrHeader[] = financiero::t("tomafisica", "Total Price");
        $j = 2;
        for($i=0; $i<count($arrHeader); $i++){
            if($j<=strlen($letters)){
                $colPosition[$i] = substr($letters, $j, 1);
                $j++;
            }
        }

        $model = new ExistenciaBodega();
        $arrData = $model->getInventarioValorizadoXBodega();
        $nameReport = financiero::t("tomafisica", "Report Inventory Valued List");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    /**
     * Expexcel Action. Allow to download a Excel document from index page.
     *
     * @return void
     */
    public function actionExpexcelinventario() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("tomafisica", "Stock Bod"),
            financiero::t("tomafisica", "Physical Temp."),
            financiero::t("tomafisica", "Stock Diff"),
            financiero::t("articulo", "Provider Price"),
            financiero::t("tomafisica", "Stock Cost"),
            financiero::t("tomafisica", "Physical Cost"),
            financiero::t("tomafisica", "Difference Cost"),
            financiero::t("tomafisica", "Status"),
        );
        $data = Yii::$app->request->get();
        $codBod = $data['bodega'];
        $numExi = $data['numEx'];
        $model = new ExistenciaBodega();
        $arrData = $model->getArticuloTomaFisicaXBodega($codBod, $numExi);
        $nameReport = financiero::t("tomafisica", "Report Inventory Review List");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    /**
     * Expexcel Action. Allow to download a Excel document from index page.
     *
     * @return void
     */
    public function actionExpexceltomafisica() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G",);
        
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("tomafisica", "Stock"),
            financiero::t("tomafisica", "Observation"),
            financiero::t("tomafisica", "Status"),
        );
        
        $data = Yii::$app->request->get();
        $codBod = $data['bodega'];
        $numExi = $data['numEx'];
        $model = new ExistenciaBodega();
        $arrData = $model->getArticulosExistenciaXBodega($codBod, $numExi);
        $nameReport = financiero::t("tomafisica", "Report Physical Count List");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }
}
