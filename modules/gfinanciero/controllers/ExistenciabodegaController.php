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
use app\modules\gfinanciero\models\ExistenciaBodega;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;
use app\modules\gfinanciero\models\Establecimiento;
use app\modules\gfinanciero\models\Bodega;
use app\modules\gfinanciero\models\Articulo;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;

use app\modules\gfinanciero\models\MarcaArticulo;
use app\modules\gfinanciero\models\TipoArticulo;
use app\modules\gfinanciero\models\LineaArticulo;
use app\modules\gfinanciero\models\TipoItem;

financiero::registerTranslations();

class ExistenciabodegaController extends CController {
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new ExistenciaBodega();
        $data = Yii::$app->request->post();
        if($data['ls_query_id'] == "autocomplete-bodega"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Bodega::getDataColumnsQueryWidget();
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
        
        
        //opciones para Get
        $data = Yii::$app->request->get();
        $bodega=Bodega::getBodegas();
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"],$data["codBod"], true),
                'bodega' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('bodega', '-- Select an Item Name --')]], $bodega), "Ids", "Nombre"),
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL,NULL, true),
            'bodega' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('bodega', '-- Select an Item Name --')]], $bodega), "Ids", "Nombre"),
        ]);
    }
    
    /**
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $objDat = new ExistenciaBodega();
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $CodArt = $data['id2'];
            $model = $objDat->getExistenciaData($id,$CodArt);
            return $this->render('view', [
                'model' => $model,
            ]);
        }
        return $this->redirect('index');
    }
    
    /**
     * Edit Action. Allow edit the information from View Action.
     *
     * @return void
     */
    public function actionEdit() {
        //$_SESSION['JSLANG']['Please select an Item.'] = financiero::t('bodega', 'Please select an Item.');
        $objDat = new ExistenciaBodega();
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $CodArt = $data['id2'];
            $model = $objDat->getExistenciaData($id,$CodArt);
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
        return $this->redirect('index');
    }
    
    /**
     * New Action. Allow show the form to create a new item or Object y Data Model.
     *
     * @return void
     */
    public function actionNew() {
        $bodega = Bodega::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_BOD' => SORT_ASC])->all();
        $bodega = ['0' => financiero::t('bodega', '-- Select an Article Name --')] + ArrayHelper::map($bodega, "COD_BOD", "NOM_BOD");
        return $this->render('new', [
            //'new_id' => Utilities::add_ceros($new_id, 2),
            'bodega' => $bodega,            
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
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                
                //// body logic begin   
                //$id = $data["id"];
                //$nombre = $data["nombre"];
                $cod_bod = $data["cod_bod"];
                $cod_art = $data["cod_art"];
                $ubi_fis = $data["ubi_fis"];
                $p_costo = $data["p_costo"];                
                //$fecha = $data["fecha"];

                $model = new ExistenciaBodega();
                //$model->COD_BOD = $id;
                //$model->NOM_BOD = $nombre;
                $model->COD_BOD = $cod_bod;
                $model->COD_ART = $cod_art;
                $model->UBI_FIS = $ubi_fis;
                $model->P_COSTO = $p_costo;
                $model->I_I_UNI = 0;
                $model->I_I_COS = 0;
                $model->T_UI_AC = 0;
                $model->T_IC_AC = 0;
                $model->T_UE_AC = 0;
                $model->T_EC_AC = 0;
                $model->T_UR_AC = 0;
                $model->T_RC_AC = 0;
                $model->S_I_FIS = NULL;
                $model->F_I_FIS = NULL;
                $model->EXI_COM = 0;
                $model->EXI_TOT = 0;
                $model->EXI_M01 = 0;
                $model->EXI_M02 = 0;
                $model->EXI_M03 = 0;
                $model->EXI_M04 = 0;
                $model->EXI_M05 = 0;
                $model->EXI_M06 = 0;
                $model->EXI_M07 = 0;
                $model->EXI_M08 = 0;
                $model->EXI_M09 = 0;
                $model->EXI_M10 = 0;
                $model->EXI_M11 = 0;
                $model->EXI_M12 = 0;
                $model->USU_DES = NULL;
                $model->P_LISTA = 0;
                $model->DET_COM = NULL;
                $model->EXI_TEM = 0;
                $model->EST_ACT = NULL;
                $model->EXI_ANT = 0;
                $model->EXI_HIS = 0;          
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = "1";
                $model->EST_DEL = "1";
                //// body logic End               
       
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                ); 
                if ($model->save()) {
                    $trans->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
            } catch (\Exception $ex) {
                //Utilities::putMessageLogFile($ex->getMessage());
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
    
    /**
     * Update Action. Allow to Update information from Edit form.
     *
     * @return void
     */
    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid"); 
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $error = false;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $cod_bod = $data["cod_bod"];
                $cod_art = $data["cod_art"];
                $ubi_fis = $data["ubi_fis"];
                $p_costo = $data["p_costo"];                
                //$fecha = $data["fecha"];

                $model = ExistenciaBodega::findOne(['COD_BOD' => $cod_bod, 'COD_ART' => $cod_art]);
                //model->COD_BOD = $cod_bod;
                //$model->COD_ART = $cod_art;
                $model->UBI_FIS = $ubi_fis;
                $model->P_COSTO = $p_costo;       
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = "1";
                $model->EST_DEL = "1";
                //// body logic End

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    $trans->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
            } catch (\Exception $ex) {
                //Utilities::putMessageLogFile($ex->getMessage());
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
        
    /**
     * Delete Action. Allow delete an item from Index form.
     *
     * @return void
     */
    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid"); 
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $error = false;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = $data["id"];
                $id2 = $data["id2"];
                $model = ExistenciaBodega::findOne(['COD_BOD' => $id, 'COD_ART' => $id2]);
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = '0';
                $model->EST_DEL = "0";
                //// body logic begin

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    $trans->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
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
        $colPosition = array("C", "D", "E", "F","G");
        $arrHeader = array(
            financiero::t("bodega", "Cellar"),
            financiero::t("bodega", "Code"),
            financiero::t("bodega", "Item Name"),
            financiero::t("bodega", "Total Existence"),
            financiero::t("bodega", "Price"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["bodega"] = $data['codBod'];
            
        }
        $arrData = array();
        $model = new ExistenciaBodega();
     
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"],$arrSearch["bodega"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL,NULL, false, true);
        }
        $nameReport = financiero::t("bodega", "Report Cellar");
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
        $this->view->title = financiero::t("bodega", "Report Cellar");  // Titulo del reporte
         $arrHeader = array(
            financiero::t("bodega", "Cellar"),
            financiero::t("bodega", "Code"),
            financiero::t("bodega", "Item Name"),
            financiero::t("bodega", "Total Existence"),
            financiero::t("bodega", "Price"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["bodega"] = $data['codBod'];
            
        }
        $arrData = array();
        $model = new ExistenciaBodega();
           
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"],$arrSearch["bodega"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL,NULL, false, true);
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
    
    
     /**
     * View Action. Allow Closed the information about item from Index Action
     *
     * @return void
     */
    public function actionCierremensual() {
        $rawData = array();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid"); 
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $CodArt = isset($data['cod_art']) ? $data['cod_art'] : "";
                $EstAct = isset($data['est_act']) ? $data['est_act'] : "0";
                //$EstAct = $data["est_act"];
                $fec_fin = $data["fecha"];
                //date("Y-m-d", strtotime($fecha)
                //$fecha=date("m", strtotime($fecha));
                $con = Yii::$app->db_gfinanciero;
                $bodegas = Bodega::getBodegas();
                $rawData= ExistenciaBodega::getMostrarItems($CodArt);//Recuperar todos los Items 
                //Utilities::putMessageLogFile($rawData);
                for ($b = 0; $b < sizeof($bodegas); $b++) {
                    $CodBod=$bodegas[$b]['Ids'];//Recupera Bodegas
                    for ($i = 0; $i < sizeof($rawData); $i++) {
                        $CodArt=$rawData[$i]['COD_ART'];//Recupera Cod Items
                        $TieneBodega= Bodega::existeItemBodega($CodBod,$CodArt);                        
                        if($TieneBodega<>0){//Si Retorna Verdadero Calcula Exsistencias
                            $fec_ini = isset($TieneBodega['F_I_FIS']) ? $TieneBodega['F_I_FIS'] : date('Y')."-01-01";
                            $Conteo=ExistenciaBodega::getContarExistncias($CodBod, $CodArt,$fec_ini,$fec_fin);
                            $ExiMes=floatval($Conteo['CAN_ING'])-floatval($Conteo['CAN_EGR']);
                            $Mes=date("m", strtotime($fec_fin)); //Retorna 01-02 antepone 0 
                            //----------------------------------------------------
                            //Actualizar Existencias Mensuales por bodega 
                            //----------------------------------------------------
                            ExistenciaBodega::actExisteciaBodega($con, $CodBod, $CodArt, $ExiMes, $Mes, $EstAct);
                            //-----------------------------------------
                            //Actualizar Existencias Mensuales Tabla Maestra
                            //------------------------------------------
                            $ExiMes= ExistenciaBodega::existeIMesBodega($CodArt, $Mes);
                            ExistenciaBodega::actExistMesMaestra($con, $CodArt, $ExiMes, $Mes, $EstAct);
                        }

                    }
                    
                }
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                $trans->commit();
                $con->close();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                
            } catch (\Exception $ex) {
                //Utilities::putMessageLogFile($ex->getMessage());
                $exMsg = new CMsgException($ex);
                $trans->rollback();
                $con->close();
                $msg = (($exMsg->getMsgLang()) != '')?($exMsg->getMsgLang()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
        return $this->render('cierremensual');
    }
    
    
    
     /**
     * View Action. Allow Closed the information about item from Index Action
     *
     * @return void
     */
    public function actionExistenciacosto() {
        
        $arr_marca = MarcaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_MAR' => SORT_ASC])->all();
        $arr_marca = ['0' => financiero::t('marcaarticulo', '-- All Marks --')] + ArrayHelper::map($arr_marca, "COD_MAR", "NOM_MAR");
        $arr_linea = LineaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_LIN' => SORT_ASC])->all();
        $arr_linea = ['0' => financiero::t('lineaarticulo', '-- All Lines --')] + ArrayHelper::map($arr_linea, "COD_LIN", "NOM_LIN");
        $arr_tipo = TipoArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_TIP' => SORT_ASC])->all();
        $arr_tipo = ['0' => financiero::t('tipoarticulo', '-- All Types --')] + ArrayHelper::map($arr_tipo, "COD_TIP", "NOM_TIP");
        $arr_tpro = TipoItem::find()->where(['TITE_ESTADO' => '1', 'TITE_ESTADO_LOGICO' => '1'])->orderBy(['TITE_NOMBRE' => SORT_ASC])->all();
        $arr_tpro = ['0' => financiero::t('tipoitem', '-- All Type Items --')] + ArrayHelper::map($arr_tpro, "TITE_PREFIX", "TITE_NOMBRE");
        $bodega=Bodega::getBodegas();
        $bodega = ['0' => financiero::t('bodega', '-- Select an Cellar --')] + ArrayHelper::map($bodega, "Ids", "Nombre");
        
        
        return $this->render('existenciacosto', [
                    'linea_art' => $arr_linea,
                    'tipo_art' => $arr_tipo,
                    'marca_art' => $arr_marca,
                    'tprod_art' => $arr_tpro,
                    'bodega' => $bodega,
            
            ]);
    }
    
    /**
     * Exppdf Action. Allow to download a Pdf document from index page.
     *
     * @return void
     */
    public function actionExicostpdf() {
        $_SESSION['JSLANG']['Select an Cellar.'] = financiero::t('bodega', '-- Select an Cellar --');
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("bodega", "Report Cellar");  // Titulo del reporte
         $arrHeader = array(
            financiero::t("bodega", "Code"),
            financiero::t("bodega", "Item Name"),
            financiero::t("bodega", "Existence"),
            financiero::t("bodega", "Average price"),
            financiero::t("bodega", "Price Reference"),
            financiero::t("bodega", "%"),
            financiero::t("bodega", "Total Cost"),
        );
        $data = Yii::$app->request->get();
        /*$arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["bodega"] = $data['codBod'];
            
        }*/
        $CodBod=isset($data['bodega']) ? $data['bodega'] : "";
        $CodArt=isset($data['articulo']) ? $data['articulo'] : "";
        $CodLin=isset($data['linea']) ? $data['linea'] : "";
        $CodTip=isset($data['tipo']) ? $data['tipo'] : "";
        $CodMar=isset($data['marca']) ? $data['marca'] : "";
        $TipPro=isset($data['tipro']) ? $data['tipro'] : "";
        $arrData = array();
        $model = new ExistenciaBodega();
        $arrData=$model->getExistenciaCosto($CodBod, $CodArt, $CodLin, $CodTip, $CodMar, $TipPro);
        /*if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"],$arrSearch["bodega"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL,NULL, false, true);
        }*/
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exicostpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
    
     /**
     * View Action. Allow Closed the information about item from Index Action
     *
     * @return void
     */
    public function actionExistenciabodega() {
        
        $arr_marca = MarcaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_MAR' => SORT_ASC])->all();
        $arr_marca = ['0' => financiero::t('marcaarticulo', '-- All Marks --')] + ArrayHelper::map($arr_marca, "COD_MAR", "NOM_MAR");
        $arr_linea = LineaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_LIN' => SORT_ASC])->all();
        $arr_linea = ['0' => financiero::t('lineaarticulo', '-- All Lines --')] + ArrayHelper::map($arr_linea, "COD_LIN", "NOM_LIN");
        $arr_tipo = TipoArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_TIP' => SORT_ASC])->all();
        $arr_tipo = ['0' => financiero::t('tipoarticulo', '-- All Types --')] + ArrayHelper::map($arr_tipo, "COD_TIP", "NOM_TIP");
        $arr_tpro = TipoItem::find()->where(['TITE_ESTADO' => '1', 'TITE_ESTADO_LOGICO' => '1'])->orderBy(['TITE_NOMBRE' => SORT_ASC])->all();
        $arr_tpro = ['0' => financiero::t('tipoitem', '-- All Type Items --')] + ArrayHelper::map($arr_tpro, "TITE_PREFIX", "TITE_NOMBRE");
        $bodega=Bodega::getBodegas();
        $bodega = ['0' => financiero::t('bodega', '-- Select an Cellar --')] + ArrayHelper::map($bodega, "Ids", "Nombre");
        
        
        return $this->render('existenciabodega', [
                    'linea_art' => $arr_linea,
                    'tipo_art' => $arr_tipo,
                    'marca_art' => $arr_marca,
                    'tprod_art' => $arr_tpro,
                    'bodega' => $bodega,
            
            ]);
    }
    
    /**
     * Exppdf Action. Allow to download a Pdf document from index page.
     *
     * @return void
     */
    public function actionRepexistenciapdf() {
        $_SESSION['JSLANG']['Select an Cellar.'] = financiero::t('bodega', '-- Select an Cellar --');
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("bodega", "Report Cellar");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("bodega", "Code"),
            financiero::t("bodega", "Item Name"),
        );
        $objBod=Bodega::getBodegas(); 
        $data = Yii::$app->request->get();
   
        $CodBod=isset($data['bodega']) ? $data['bodega'] : "";
        $CodArt=isset($data['articulo']) ? $data['articulo'] : "";
        $CodLin=isset($data['linea']) ? $data['linea'] : "";
        $CodTip=isset($data['tipo']) ? $data['tipo'] : "";
        $CodMar=isset($data['marca']) ? $data['marca'] : "";
        $TipPro=isset($data['tipro']) ? $data['tipro'] : "";
        
        if($CodBod=="0"){            
            for ($i = 0; $i < sizeof($objBod); $i++)  {
                $CodCol = isset($objBod[$i]['Ids']) ? trim($objBod[$i]['Ids']) : "";
                $arrHeader[$i+2]="EXI_B".$CodCol;
            }
            $arrHeader[$i+2]=financiero::t("bodega", "Total Existence");
        }else{
            $arrHeader[3]="EXI_B".$CodBod;
            $arrHeader[4]=financiero::t("bodega", "Total Existence");
        }
              
        $arrData = array();
        $model = new ExistenciaBodega();
        $arrData=$model->getRepExistenciaBodega($CodBod, $CodArt, $CodLin, $CodTip, $CodMar, $TipPro);
        //Utilities::putMessageLogFile($arrData);
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('repexistepdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                    'objBod'=> $objBod,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
    

    
}