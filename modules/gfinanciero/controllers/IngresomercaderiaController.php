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
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\models\Articulo;
use app\modules\gfinanciero\models\Bodega;
use app\modules\gfinanciero\models\Proveedor;
use app\modules\gfinanciero\models\CabeceraIngresoMercaderia;
use app\modules\gfinanciero\models\DetalleIngresoMercaderia;
use app\modules\gfinanciero\models\ExistenciaBodega;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class IngresomercaderiaController extends CController {
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new CabeceraIngresoMercaderia();
        $data = Yii::$app->request->post();
        if($data['ls_query_id'] == "autocomplete-bodorig"){
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
        
        if($data['ls_query_id'] == "autocomplete-proveedor"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Proveedor::getDataColumnsQueryWidget();
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
            //$arr_data = Articulo::getDataColumnsQueryWidget("I", true, true);
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
        $data = Yii::$app->request->get();
        $arr_tipo = CabeceraIngresoMercaderia::getEgressTypes();
        $arr_tipo = ['0' => financiero::t('ingresomercaderia', '-- All Egress Types --')] + $arr_tipo;
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"], $data["tipo"], true),
                'arr_tipo' => $arr_tipo,
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL, NULL, true),
            'arr_tipo' => $arr_tipo,
        ]);
    }
    
    /**
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['cod'])) {
            $_SESSION['JSLANG']['Invalidate'] = financiero::t('ingresomercaderia', "Invalidate");
            $_SESSION['JSLANG']['Are you sure to cancel this registry?'] = financiero::t('ingresomercaderia', "Are you sure to cancel this registry?");
            $codBod = $data['cod'];//codigo de la bodega
            $sec = $data['ing'];//numro de ingreso
            $tipo = $data['tip'];// tipo ingrso
            $arr_tipo = CabeceraIngresoMercaderia::getEgressTypes();
            $arr_tipo = ['0' => financiero::t('ingresomercaderia', '-- Select an Entry Type --')] + $arr_tipo;
            $model = CabeceraIngresoMercaderia::findOne(['COD_BOD' => $codBod, 'NUM_ING' => $sec, 'TIP_ING' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
            $modelBodOrg = Bodega::findOne(['COD_BOD' => $codBod, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $nomBodOrg = $modelBodOrg->NOM_BOD;
            $dirBodOrg = $modelBodOrg->DIR_BOD;
            
            
            $modelDetalle = DetalleIngresoMercaderia::getAllArticlesByEntry($codBod, $sec, "IN", $model->IND_UPD, NULL, true);
            return $this->render('view', [
                'model' => $model,
                'modelDetalle' => $modelDetalle,
                'arr_tipo' => $arr_tipo,
                'tipo' => $tipo,
                'nomBodOrg' => $nomBodOrg,
                'dirBodOrg' => $dirBodOrg,                
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
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getSecBodOrg"])) {
                $sec = Bodega::newSecuence($data["code"], "IN");
                $message = array("secuence" => $sec);
                if($sec === 0){
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", 'Invalid request. Please do not repeat this request again. Contact to Administrator.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            
           
         
            if(isset($data['getBodOrgExt'])){
                $codeArt = $data['articulo'];
                $codeBod = $data['bodega'];
                $message = [];
                $message['title'] = Yii::t('jslang', 'Error');

                $modelArticulo = Articulo::findOne(['COD_ART' => $codeArt]);
                if($modelArticulo){
                    $plista=(isset($modelArticulo['P_LISTA']))?($modelArticulo['P_LISTA']):0.00;
                    $pcosto=(isset($modelArticulo['P_COSTO']))?($modelArticulo['P_COSTO']):0.00;
                    $dataSent = ['p_lista' => $plista, 'p_costo' => $pcosto];
                    $message = array("data" => $dataSent);
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    
                }else{
                    $message['wtmessage'] = financiero::t('ingresomercaderia',"Item code '{code}' not exists.", ['code' => "<b>".$codeArt."</b>",]);
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                

            }
        }
        $arr_tipo = CabeceraIngresoMercaderia::getEgressTypes();
        $arr_tipo = ['0' => financiero::t('ingresomercaderia', '-- Select an Entry Type --')] + $arr_tipo;
        $_SESSION['JSLANG']['Cellar Destiny must be different to Cellar Origin.'] = financiero::t('ingresomercaderia',"Cellar Destiny must be different to Cellar Origin.");
        $_SESSION['JSLANG']['There are no items added. Please enter one.'] = financiero::t('ingresomercaderia',"There are no items added. Please enter one.");
        $_SESSION['JSLANG']['There is no stock available. Select another Article.'] = financiero::t('ingresomercaderia',"There is no stock available. Select another Article.");
        $_SESSION['JSLANG']['There is no stock available by the number items to add.'] = financiero::t('ingresomercaderia',"There is no stock available by the number items to add.");
        $_SESSION['JSLANG']['You must enter an amount of items greater than zero.'] = financiero::t('ingresomercaderia',"You must enter an amount of items greater than zero.");
        $_SESSION['JSLANG']['Item already exists.'] = financiero::t('ingresomercaderia',"Item already exists.");
        $_SESSION['JSLANG']['Edit'] = Yii::t('accion',"Edit");
        $_SESSION['JSLANG']['New'] = Yii::t('ingresomercaderia',"New");
        $_SESSION['JSLANG']['View'] = Yii::t('accion',"View");
        $_SESSION['JSLANG']['Back'] = Yii::t('accion',"Back");
        $_SESSION['JSLANG']['Please select a Cellar Origin.'] = financiero::t('ingresomercaderia', "Please select a Cellar Origin.");
        $_SESSION['JSLANG']['Please select a Cellar Destiny.'] = financiero::t('ingresomercaderia', "Please select a Cellar Destiny.");
        $_SESSION['JSLANG']['Please select an Egress Types.'] = financiero::t('ingresomercaderia', "Please select an Egress Types.");
        return $this->render('new', [
            'arr_tipo' => $arr_tipo,
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
                $tipo = $data["tipo"];
                $fecha = $data["fecha"];
                $bodorig = $data["bodorig"];
                $bodosec = $data["bodosec"];
                $codpro = $data["codpro"];
                $nompro = $data['nompro'];
                $items = $data["items"];
                $cbulto = $data["cbulto"];
                $recibido = $data["recibido"];
                $revisado = $data["revisado"];
                $kardex = $data["kardex"];
                $observacion = $data["observacion"];
                $numart = $data["numart"];
                $canitem = $data["canitem"];
                $total = $data["total"];
                
                //Insertar Cabecera de Datos
                $model = new CabeceraIngresoMercaderia();
                $model->COD_BOD = $bodorig;
                $model->TIP_ING = $tipo;//"IN";
                $model->NUM_ING = $bodosec;                
                $model->FEC_ING = $fecha;
                $model->COD_PRO = $codpro;
                $model->NOM_PRO = $nompro;
                $model->T_I_ING = $canitem;
                $model->T_P_ING = $numart;
                $model->TOT_COS = $total;
                $model->LIN_N01 = $observacion;
                $model->LIN_N02 = $cbulto;
                $model->LIN_N03 = $recibido;
                $model->LIN_N04 = $revisado;
                $model->LIN_N05 = $kardex;
                $model->IND_UPD = "L"; // L => Liquidado || A => Anulado
                
                $model->TIP_B_T = NULL;
                $model->NUM_B_T = NULL;
                $model->COD_B_T = NULL;
                //$model->GUI_REM = NULL;
                //$model->NUM_ORT = NULL;
                
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = "1";
                $model->EST_DEL = "1";

                if(!$model->save()){
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                $model_ext = new ExistenciaBodega();
                for($i = 0; $i < count($items); $i++){
                    $codArt = $items[$i][1];
                    $nomArt = $items[$i][2];
                    $cant = $items[$i][3];
                    $p_lista = $items[$i][4];
                    $p_costo = $items[$i][5];
                    $t_costo = $items[$i][6];
                  
                     // Se pregunta si existe item en existenciabodega para la bodega de destino si no la crea.
                    $arrExiBod = ExistenciaBodega::existeItemExistenciaBodega($bodorig, $codArt);
                    if(isset($arrExiBod) && $arrExiBod['cant'] == 0){
                        $modelExBod = new ExistenciaBodega();
                        $modelExBod->COD_BOD = $bodorig;
                        $modelExBod->COD_ART = $codArt;
                        $modelExBod->EXI_COM = 0;
                        $modelExBod->EXI_TOT = 0;
                        $modelExBod->P_COSTO = $p_costo;
                        $modelExBod->P_LISTA = $p_lista;
                        $modelExBod->EXI_M01 = 0;
                        $modelExBod->EXI_M02 = 0;
                        $modelExBod->EXI_M03 = 0;
                        $modelExBod->EXI_M04 = 0;
                        $modelExBod->EXI_M05 = 0;
                        $modelExBod->EXI_M06 = 0;
                        $modelExBod->EXI_M07 = 0;
                        $modelExBod->EXI_M08 = 0;
                        $modelExBod->EXI_M09 = 0;
                        $modelExBod->EXI_M10 = 0;
                        $modelExBod->EXI_M11 = 0;
                        $modelExBod->EXI_M12 = 0;
                        $modelExBod->FEC_SIS = date('Y-m-d');
                        $modelExBod->HOR_SIS = date('H:i:s');
                        $modelExBod->USUARIO = $username;
                        $modelExBod->EQUIPO = Utilities::getClientRealIP();
                        $modelExBod->EST_LOG = "1";
                        $modelExBod->EST_DEL = "1";
                        if(!$modelExBod->save()){
                            $arr_errs = $modelExBod->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                   
                    
                    $modelD = new DetalleIngresoMercaderia();
                    $modelD->COD_BOD = $bodorig;
                    $modelD->TIP_ING = $tipo;//"IN";
                    $modelD->NUM_ING = $bodosec;
                    $modelD->FEC_ING = $fecha;
                    $modelD->COD_PRO = $codpro;
                    $modelD->COD_ART = $codArt;
                    $modelD->CAN_PED = $cant;
                    $modelD->CAN_DEV = 0;
                    $modelD->C_ANTER = 0;
                    $modelD->P_COSTO = $p_costo;
                    $modelD->P_LISTA = $p_lista;
                    $modelD->T_COSTO = $t_costo;
                    $modelD->IND_EST = "L";                    
                    $modelD->COD_B_T = NULL;
                    $modelD->NUM_B_T = NULL;
                    $modelD->TIP_B_T = NULL;               
                    $modelD->FEC_SIS = date('Y-m-d');
                    $modelD->HOR_SIS = date('H:i:s');
                    $modelD->USUARIO = $username;
                    $modelD->EQUIPO = Utilities::getClientRealIP();
                    $modelD->EST_LOG = "1";
                    $modelD->EST_DEL = "1";
                    if(!$modelD->save()){
                        $arr_errs = $modelD->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    
                    // se debe modificar el stock de articulo tabla maestra
                    $itemArt = Articulo::findOne(['COD_ART' => $codArt, 'EST_LOG' => '1', 'EST_DEL' => '1', ]);
                    $itemArt->EXI_TOT = $itemArt->EXI_TOT + $cant;
                    if (!$itemArt->save()) {
                        $arr_errs = $itemArt->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    
                    // se modifica stock en bodega origen 
                    if(!ExistenciaBodega::modificarStockBodega($bodorig, $codArt, $p_costo, $p_lista, $cant, "IN")){
                        $arr_errs[] = financiero::t('ingresomercaderia',"Error for code '{code}' with {cant} Item(s) to add/remove cellar {bod}.",
                        ['code' => "<b>".$codArt."</b>", 
                        'cant' => "<b>".$cant."</b>", 
                        'bod' => "<b>".$bodorig."</b>",]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                // Se actualiza la secuencia en la bodega Ingreso
                $modelBodega = Bodega::findOne(['COD_BOD' => $bodorig, 'EST_LOG' => '1', 'EST_DEL' => '1',]);
                $modelBodega->NUM_ING = $bodosec;
                $modelBodega->FEC_SIS = date('Y-m-d');
                $modelBodega->HOR_SIS = date('H:i:s');
                $modelBodega->USUARIO = $username;
                $modelBodega->EQUIPO = Utilities::getClientRealIP();
                if(!$modelBodega->save()){
                    $arr_errs = $modelBodega->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved.") . " " . 
                                    financiero::t('ingresomercaderia',"Do you want to create a new Entry or review the information entered?"),
                    "title" => Yii::t('jslang', 'Success'),
                );
                $trans->commit();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
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
    public function actionAnular(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid"); 
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                $codBod = $data['cod'];
                $sec = $data['ing'];
                $tipo = $data['tip'];
                //// Anulacion de Cabecera de Egreso
                $modelCEgr = CabeceraIngresoMercaderia::findOne(['COD_BOD' => $codBod, 'NUM_ING' => $sec, 'TIP_ING' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
                if($modelCEgr->IND_UPD == "A"){
                    $arr_errs[] = Yii::t("notificaciones", 'Invalid request. Please do not repeat this request again. Contact to Administrator.');
                        throw new \Exception(json_encode($arr_errs), '9999999');
                }
                $modelCEgr->IND_UPD = "A";
                $modelCEgr->FEC_SIS = date('Y-m-d');
                $modelCEgr->HOR_SIS = date('H:i:s');
                $modelCEgr->USUARIO = $username;
                $modelCEgr->EQUIPO = Utilities::getClientRealIP();
                //$modelCEgr->EST_LOG = "1";
                //$modelCEgr->EST_DEL = "1";
                if(!$modelCEgr->save()){
                    $arr_errs = $modelCEgr->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                //// Anulacion de Detalle de Egreso
                $modelDEgr = DetalleIngresoMercaderia::findAll(['COD_BOD' => $codBod, 'NUM_ING' => $sec, 'TIP_ING' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
                foreach($modelDEgr as $key => $item){
                    $item->IND_EST = "A";
                    if(!$item->save()){
                        $arr_errs = $item->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    //// Reversar Stock de cada articulo ingresado a la bodega
                    $cant = $item->CAN_PED;
                    $bod  = $item->COD_BOD;
                    $art  = $item->COD_ART;
                    $ppro = $item->P_LISTA;
                    $pref = $item->P_COSTO;
                    if(!ExistenciaBodega::reversarStockBodega($bod, $art, $pref, $ppro, $cant, "IN")){
                        $arr_errs[] = financiero::t('ingresomercaderia',"Error for code '{code}' with {cant} Item(s) to add/remove cellar {bod}.",
                        ['code' => "<b>".$art."</b>", 
                        'cant' => "<b>".$cant."</b>", 
                        'bod' => "<b>".$bod."</b>",]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    //// Reversar Stock de cada articulo ingresado en la tabla maestra si es solo egreso
                    $modelArticulo = Articulo::findOne(['COD_ART' => $art, 'EST_LOG' => '1', 'EST_DEL' => '1']);
                    $modelArticulo->EXI_TOT = ($modelArticulo->EXI_TOT) - $cant;
                    if(!$modelArticulo->save()){
                        $arr_errs = $item->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    
                }
                
                

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                $trans->commit();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
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
     * Expexcel Action. Allow to download a Pdf document for Egress Transaction.
     *
     * @return void
     */
    public function actionPrintentry(){
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("ingresomercaderia", "Report Merchandise Entry Items");  // Titulo del reporte
        $arrColumns = array(
            financiero::t("ingresomercaderia", "Code"),
            financiero::t("ingresomercaderia", "Name"),
            financiero::t("ingresomercaderia", "Amount"),
        );
        
        $data = Yii::$app->request->get();
        $codBod = $data['cod'];
        $sec = $data['ing'];
        $tipo = $data['tip'];

        $model = CabeceraIngresoMercaderia::findOne(['COD_BOD' => $codBod, 'NUM_ING' => $sec, 'TIP_ING' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
        $modelBodOrg = Bodega::findOne(['COD_BOD' => $codBod, 'EST_LOG' => '1', 'EST_DEL' => '1']);
        //$modelBodDst = (isset($model->COD_B_T) && $model->COD_B_T != "")?(Bodega::findOne(['COD_BOD' => $model->COD_B_T, 'EST_LOG' => '1', 'EST_DEL' => '1'])):NULL;
        $arrData = DetalleIngresoMercaderia::getItemsPrintByEntry($codBod, $sec, $tipo);
        $arrHeader = [
            "empresa" => Yii::$app->session->get("PB_empresa"),
            "lblEgreso" => financiero::t("ingresomercaderia", "Merchandise Entry"),
            "secuencia" => $model->NUM_ING,
            "lblDate" => financiero::t("ingresomercaderia", "Egress Date"),
            "fecha" => $model->FEC_ING,
            "lblbodOrg" => financiero::t("ingresomercaderia", "Cellar Origin"),
            "lblbodDst" => financiero::t("ingresomercaderia", "Cellar Destiny"),
            "lblbodCod" => financiero::t("ingresomercaderia", "Code"),
            "lblbodNam" => financiero::t("ingresomercaderia", "Name"),
            "lblbodSec" => financiero::t("ingresomercaderia", "Secuence"),
            "lblbodAdd" => financiero::t("ingresomercaderia", "Address"),
            "lblCliPro" => financiero::t("ingresomercaderia", "Client/Provider"),
            "lblTotal" => financiero::t("ingresomercaderia", "Total"),
            "lblCaja" => financiero::t("ingresomercaderia", "Bulk Boxes"),
            "lblRecibido" => financiero::t("ingresomercaderia", "Received By"),
            "lblRevisado" => financiero::t("ingresomercaderia", "Reviewed By"),
            "lblKardex" => financiero::t("ingresomercaderia", "Annotated Kardex"),
            "lblObservaciones" => financiero::t("ingresomercaderia", "Observations"),
            "lblLiquidado" => financiero::t("ingresomercaderia", "Liquidated"),
            "lblAnulado" => financiero::t("ingresomercaderia", "Invalidated"),
        ];
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical     
        
        $this->layout = false;  
        $report->createReportPdf(
            $this->render('printpdf', [
                'model' => $model,
                'modelBodOrg' => $modelBodOrg,
                //'modelBodDst' => $modelBodDst,
                'arr_head' => $arrHeader,
                'arr_cols' => $arrColumns,
                'arr_body' => $arrData,
            ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
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
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M");
        $arrHeader = array(
            financiero::t("ingresomercaderia", "Cellar Code Org."),
            financiero::t("ingresomercaderia", "Cellar Origin"),
            financiero::t("ingresomercaderia", "Egress Type"),
            financiero::t("bodega", "Egress Number"),
            financiero::t("ingresomercaderia", "Egress Date"),
            financiero::t("ingresomercaderia", "Client Code"),
            financiero::t("ingresomercaderia", "Client Name"),
            financiero::t("ingresomercaderia", "Total of Egress"),
            financiero::t("ingresomercaderia", "Cellar Code Dst."),
            financiero::t("ingresomercaderia", "Cellar Destiny"),
            financiero::t("bodega", "Income Number"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["type"] = $data['type'];
        }
        $arrData = array();
        $model = new CabeceraEgresoMercaderia();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $data['type'], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, false, true);
        }
        $nameReport = financiero::t("ingresomercaderia", "Report Merchandise Egress Items");
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
        $this->view->title = financiero::t("ingresomercaderia", "Report Merchandise Egress Items");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("ingresomercaderia", "Cellar Code Org."),
            financiero::t("ingresomercaderia", "Cellar Origin"),
            financiero::t("ingresomercaderia", "Egress Type"),
            financiero::t("bodega", "Egress Number"),
            financiero::t("ingresomercaderia", "Egress Date"),
            financiero::t("ingresomercaderia", "Client Code"),
            financiero::t("ingresomercaderia", "Client Name"),
            financiero::t("ingresomercaderia", "Total of Egress"),
            financiero::t("ingresomercaderia", "Cellar Code Dst."),
            financiero::t("ingresomercaderia", "Cellar Destiny"),
            financiero::t("bodega", "Income Number"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["type"] = $data['type'];
        }
        $arrData = array();
        $model = new CabeceraEgresoMercaderia();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $data['type'], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, false, true);
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