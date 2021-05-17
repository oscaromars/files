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
use app\modules\gfinanciero\models\CabeceraEgresoMercaderia;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\models\Articulo;
use app\modules\gfinanciero\models\Bodega;
use app\modules\gfinanciero\models\CabeceraIngresoMercaderia;
use app\modules\gfinanciero\models\Cliente;
use app\modules\gfinanciero\models\DetalleEgresoMercaderia;
use app\modules\gfinanciero\models\DetalleIngresoMercaderia;
use app\modules\gfinanciero\models\ExistenciaBodega;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class EgresomercaderiaController extends CController {
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new CabeceraEgresoMercaderia();
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
        if($data['ls_query_id'] == "autocomplete-boddest"){
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
        if($data['ls_query_id'] == "autocomplete-cliente"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Cliente::getDataColumnsQueryWidget();
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
            $arr_data = Articulo::getDataColumnsQueryWidget("I", true, true);
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
        $arr_tipo = CabeceraEgresoMercaderia::getEgressTypes();
        $arr_tipo = ['0' => financiero::t('egresomercaderia', '-- All Egress Types --')] + $arr_tipo;
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
            $_SESSION['JSLANG']['Invalidate'] = financiero::t('egresomercaderia', "Invalidate");
            $_SESSION['JSLANG']['Are you sure to cancel this registry?'] = financiero::t('egresomercaderia', "Are you sure to cancel this registry?");
            $codBod = $data['cod'];
            $sec = $data['egr'];
            $tipo = $data['tip'];
            $arr_tipo = CabeceraEgresoMercaderia::getEgressTypes();
            $arr_tipo = ['0' => financiero::t('egresomercaderia', '-- Select an Egress Type --')] + $arr_tipo;
            $model = CabeceraEgresoMercaderia::findOne(['COD_BOD' => $codBod, 'NUM_EGR' => $sec, 'TIP_EGR' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
            $modelBodOrg = Bodega::findOne(['COD_BOD' => $codBod, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $nomBodOrg = $modelBodOrg->NOM_BOD;
            $dirBodOrg = $modelBodOrg->DIR_BOD;
            $modelBodDst = null;
            $nomBodDst = '';
            $dirBodDst = '';
            if(isset($model->COD_B_T) && $model->COD_B_T != ""){
                $modelBodDst = Bodega::findOne(['COD_BOD' => $model->COD_B_T, 'EST_LOG' => '1', 'EST_DEL' => '1']);
                $nomBodDst = $modelBodDst->NOM_BOD;
                $dirBodDst = $modelBodDst->DIR_BOD;
            }
            $modelDetalle = DetalleEgresoMercaderia::getAllArticlesByEgress($codBod, $sec, "EG", $model->IND_UPD, NULL, true);
            return $this->render('view', [
                'model' => $model,
                'modelDetalle' => $modelDetalle,
                'arr_tipo' => $arr_tipo,
                'tipo' => $tipo,
                'nomBodOrg' => $nomBodOrg,
                'dirBodOrg' => $dirBodOrg,
                'nomBodDst' => $nomBodDst,
                'dirBodDst' => $dirBodDst,
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
                $sec = Bodega::newSecuence($data["code"], "EG");
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
            if (isset($data["getSecBodDst"])) {
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
            if (isset($data['getReserveItem'])){
                $codArt = $data['code'];
                $codBod = $data['bod'];
                $action = $data['action'];
                $cant = $data['can'];
                $oldCan = $data['oldCan'];
                $result = false;
                $model = Articulo::findOne(['COD_ART' => $data["code"], 'EST_LOG' => '1', 'EST_DEL' => '1', ]);
                $model_ext = new ExistenciaBodega();
                $data = $model_ext->getExistenciaData($codBod, $codArt);
                if(!isset($data) || count($data) <= 0){
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", 'Invalid request. Please do not repeat this request again. Contact to Administrator.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                $item['cod'] = $data['COD_ART'];
                $item['name'] = $data['DES_COM'];
                $item['pprovider'] = isset($model->P_LISTA)?($model->P_LISTA):0.0000;
                $item['preference'] = isset($model->P_COSTO)?($model->P_COSTO):0.0000;
                $item['exireserved'] = isset($data['EXI_COM'])?($data['EXI_COM']):0.00;
                $item['exitotal'] = isset($data['EXI_TOT'])?($data['EXI_TOT']):0.00;

                if($action == "new"){
                    if($item['exitotal'] - ($item['exireserved'] + $cant) >= 0){
                        $result = ExistenciaBodega::reservaItemBodega($codArt, $codBod, $cant, "1"); // se reserva los items
                    }
                }else if($action == "update"){
                    //$item['exireserved'] -= $oldCan;
                    if($item['exitotal'] - ($item['exireserved'] - $oldCan + $cant) >= 0){
                        $result = ExistenciaBodega::reservaItemBodega($codArt, $codBod, $oldCan, "0"); // se libera los items anteriormente reservados
                        $result2 = ExistenciaBodega::reservaItemBodega($codArt, $codBod, $cant, "1"); // se reserva los nuevos items
                        $result = ($result && $result2);
                    }
                }else if($action == "edit"){
                    $item['exireserved'] -= $cant;
                    $result = true;
                }else if($action == "delete"){
                    $item['exireserved'] -= $cant;
                    $result = ExistenciaBodega::reservaItemBodega($codArt, $codBod, $cant, "0"); // se libera los items anteriormente reservados
                }

                if(!$result){
                    $message = array(
                        "wtmessage" => financiero::t('egresomercaderia',"For Item code '{code}' is not available the {cant} item(s) because there are a {total} total item(s) and {reserved} reserved item(s).",
                            ['code' => "<b>".$item['cod']."</b>", 
                            'cant' => "<b>".$cant."</b>",
                            'total' => "<b>".($item['exitotal'] - $oldCan)."</b>",
                            'reserved' => "<b>".$item['exireserved']."</b>",
                            ]
                        ),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }

                $message = array("item" => $item);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data['validItems'])){
                $arrCodes = $data['codes'];
                $arrCants = $data['cants'];
                $codBod = $data['bod'];
                $msgErrors = "";
                $error = false;
                for($i = 0; $i < count($arrCodes); $i++){
                    $model = Articulo::findOne([['COD_ART' => $arrCodes[$i], 'EST_LOG' => '1', 'EST_DEL' => '1', ]]);
                    $model_ext = new ExistenciaBodega();
                    $data = $model_ext->getExistenciaData($codBod, $arrCodes[$i]);
                    if(!isset($data) || count($data) <= 0){
                        $msgErrors .= Yii::t("notificaciones", 'Invalid request. Please do not repeat this request again. Contact to Administrator.');
                        $error = true;
                        break;
                    }
                    if(!$model){
                        $msgErrors .= financiero::t('egresomercaderia',"Item code '{code}' not exists.", ['code' => "<b>".$arrCodes[$i]."</b>",]);
                        $error = true;
                        break;
                    }else{
                        $cant = $arrCants[$i];
                        $exTot = (isset($data['EXI_TOT']))?($data['EXI_TOT']):0.00;
                        $exCom = (isset($data['EXI_COM']))?($data['EXI_COM']):0.00;
                        $disp = $exTot - ($exCom - $cant);
                        if($disp < 0){
                            $msgErrors .= financiero::t('egresomercaderia',"For Item code '{code}' is not available the {cant} item(s) because there are a {total} total item(s) and {reserved} reserved item(s).", 
                            ['code' => "<b>".$arrCodes[$i]."</b>", 
                             'cant' => "<b>".$cant."</b>",
                             'total' => "<b>".$exTot."</b>",
                             'reserved' => "<b>".$exCom."</b>",
                            ]) 
                            . "<br />";
                            $error = true;
                        }
                    }
                }
                $message = array(
                    "wtmessage" => $msgErrors,
                    "title" => Yii::t('jslang', 'Error'),
                );
                if(!$error){
                    $message['wtmessage'] = financiero::t('egresomercaderia', 'Stock is available for each product.');
                    $message['title'] = Yii::t('jslang', 'Success');
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }else{
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
            }
            if (isset($data["cancelProcess"])){
                $arrItems = $data['items'];
                $codeBod = $data['bod'];
                $arrCants = [];
                for($i = 0; $i < count($arrItems); $i++){
                    $arrCants[$i]['code'] = $arrItems[$i][1];
                    $arrCants[$i]['cant'] = $arrItems[$i][3];
                }
                $result = ExistenciaBodega::removeAllArrayReserveItems($codeBod, $arrCants);
                if($result['error'] == true){
                    $message['wtmessage'] = $result['msg'];
                    $message['title'] = Yii::t('jslang', 'Error');
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }else{
                    $message['wtmessage'] = $result['msg'];
                    $message['title'] = Yii::t('jslang', 'Success');
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }
            }
            if(isset($data['getBodOrgExt'])){
                $codeArt = $data['articulo'];
                $codeBod = $data['bodega'];
                $message = [];
                $message['title'] = Yii::t('jslang', 'Error');

                $modelArticulo = Articulo::findOne(['COD_ART' => $codeArt]);
                if($modelArticulo){
                    $exiteBodega = Bodega::existeItemBodega($codeBod, $codeArt);
                    if($exiteBodega == 0){
                        $message['wtmessage'] = financiero::t('egresomercaderia',"No items for code '{code}' in Cellar code '{cbod}'", 
                        ['code' => "<b>".$codeArt."</b>", 
                         'cbod' => "<b>".$codeBod."</b>",]);
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }else{
                        $model_ext = new ExistenciaBodega();
                        $data = $model_ext->getExistenciaData($codeBod, $codeArt);
                        $dataSent = ['exTot' => $data['EXI_TOT'], 'exCom' => $data['EXI_COM']];
                        $message = array("data" => $dataSent);
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }
                }else{
                    $message['wtmessage'] = financiero::t('egresomercaderia',"Item code '{code}' not exists.", ['code' => "<b>".$codeArt."</b>",]);
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                

            }
        }
        $arr_tipo = CabeceraEgresoMercaderia::getEgressTypes();
        $arr_tipo = ['0' => financiero::t('egresomercaderia', '-- Select an Egress Type --')] + $arr_tipo;
        $_SESSION['JSLANG']['Cellar Destiny must be different to Cellar Origin.'] = financiero::t('egresomercaderia',"Cellar Destiny must be different to Cellar Origin.");
        $_SESSION['JSLANG']['There are no items added. Please enter one.'] = financiero::t('egresomercaderia',"There are no items added. Please enter one.");
        $_SESSION['JSLANG']['There is no stock available. Select another Article.'] = financiero::t('egresomercaderia',"There is no stock available. Select another Article.");
        $_SESSION['JSLANG']['There is no stock available by the number items to add.'] = financiero::t('egresomercaderia',"There is no stock available by the number items to add.");
        $_SESSION['JSLANG']['You must enter an amount of items greater than zero.'] = financiero::t('egresomercaderia',"You must enter an amount of items greater than zero.");
        $_SESSION['JSLANG']['Item already exists.'] = financiero::t('egresomercaderia',"Item already exists.");
        $_SESSION['JSLANG']['Edit'] = Yii::t('accion',"Edit");
        $_SESSION['JSLANG']['New'] = Yii::t('egresomercaderia',"New");
        $_SESSION['JSLANG']['View'] = Yii::t('accion',"View");
        $_SESSION['JSLANG']['Back'] = Yii::t('accion',"Back");
        $_SESSION['JSLANG']['Please select a Cellar Origin.'] = financiero::t('egresomercaderia', "Please select a Cellar Origin.");
        $_SESSION['JSLANG']['Please select a Cellar Destiny.'] = financiero::t('egresomercaderia', "Please select a Cellar Destiny.");
        $_SESSION['JSLANG']['Please select an Egress Types.'] = financiero::t('egresomercaderia', "Please select an Egress Types.");
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
                $boddest = $data["boddest"];
                $boddsec = $data["boddsec"];
                $codcli = $data["codcli"];
                $namcli = $data['namcli'];
                $items = $data["items"];
                $cbulto = $data["cbulto"];
                $recibido = $data["recibido"];
                $revisado = $data["revisado"];
                $kardex = $data["kardex"];
                $observacion = $data["observacion"];
                $numart = $data["numart"];
                $canitem = $data["canitem"];
                $total = $data["total"];

                $model = new CabeceraEgresoMercaderia();
                $model->COD_BOD = $bodorig;
                $model->NUM_EGR = $bodosec;
                $model->NUM_B_T = NULL;
                $model->FEC_EGR = $fecha;
                $model->COD_CLI = $codcli;
                $model->NOM_CLI = $namcli;
                $model->T_I_EGR = $canitem;
                $model->T_P_EGR = $numart;
                $model->TOT_COS = $total;
                $model->LIN_N01 = $observacion;
                $model->LIN_N02 = $cbulto;
                $model->LIN_N03 = $recibido;
                $model->LIN_N04 = $revisado;
                $model->LIN_N05 = $kardex;
                $model->IND_UPD = "L"; // L => Liquidado || A => Anulado
                $model->TIP_EGR = "EG";
                $model->TIP_B_T = NULL;
                $model->COD_B_T = NULL;
                $model->GUI_REM = NULL;
                $model->NUM_ORT = NULL;
                if($tipo == "IN"){
                    $model->COD_B_T = $boddest;
                    $model->NUM_B_T = $boddsec;
                    $model->TIP_B_T = "IN";
                }
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
                    $ppro = $items[$i][4];
                    $pref = $items[$i][5];
                    $tot = $items[$i][6];
                    $data = $model_ext->getExistenciaData($bodorig, $codArt);
                    $itemArt = Articulo::findOne(['COD_ART' => $codArt, 'EST_LOG' => '1', 'EST_DEL' => '1', ]);
                    if(!isset($data) || count($data) <= 0 || (($data['EXI_TOT'] - ($data['EXI_COM'] - $cant)) < 0)){
                        $arr_errs[] = financiero::t('egresomercaderia', "For Item code '{code}' is not available the {cant} item(s) because there are a {total} total item(s) and {reserved} reserved item(s).", 
                        [
                            'code' => "<b>".$codArt."</b>", 
                             'cant' => "<b>".$cant."</b>",
                             'total' => "<b>".$itemArt->EXI_TOT."</b>",
                             'reserved' => "<b>".$itemArt->EXI_COM."</b>",
                        ]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    if(!ExistenciaBodega::reservaItemBodega($codArt, $bodorig, $cant, "0")){ // se libera reserva comprometida
                        $arr_errs = financiero::t('egresomercaderia',"No items for code '{code}' in Cellar code '{cbod}'",
                        ['code' => "<b>".$codArt."</b>", 
                        'cbod' => "<b>".$bodorig."</b>",]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    $modelD = new DetalleEgresoMercaderia();
                    $modelD->COD_BOD = $bodorig;
                    $modelD->NUM_EGR = $bodosec;
                    $modelD->FEC_EGR = $fecha;
                    $modelD->COD_CLI = $codcli;
                    $modelD->COD_ART = $codArt;
                    $modelD->CAN_PED = $cant;
                    $modelD->CAN_DEV = 0;
                    $modelD->CAN_FAC = $cant;
                    $modelD->P_COSTO = $pref;
                    $modelD->P_LISTA = $ppro;
                    $modelD->IND_EST = "L";
                    $modelD->TIP_EGR = "EG";
                    $modelD->COD_B_T = NULL;
                    $modelD->NUM_B_T = NULL;
                    $modelD->TIP_B_T = NULL;
                    $modelD->NUM_ORT = NULL;
                    if($tipo == "IN"){
                        $modelD->COD_B_T = $boddest;
                        $modelD->NUM_B_T = $boddsec;
                        $modelD->TIP_B_T = "IN";
                    }else{ // se debe modificar el stock de articulo
                        $itemArt->EXI_TOT = $itemArt->EXI_TOT - $cant;
                        if(!$itemArt->save()){
                            $arr_errs = $itemArt->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
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
                    // se modifica stock en bodega origen
                    if(!ExistenciaBodega::modificarStockBodega($bodorig, $codArt, $pref, $ppro, $cant, "EG")){
                        $arr_errs[] = financiero::t('egresomercaderia',"Error for code '{code}' with {cant} Item(s) to add/remove cellar {bod}.",
                        ['code' => "<b>".$codArt."</b>", 
                        'cant' => "<b>".$cant."</b>", 
                        'bod' => "<b>".$bodorig."</b>",]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                // Se actualiza la secuencia en la bodega Egreso
                $modelBodega = Bodega::findOne(['COD_BOD' => $bodorig, 'EST_LOG' => '1', 'EST_DEL' => '1',]);
                $modelBodega->NUM_EGR = $bodosec;
                $modelBodega->FEC_SIS = date('Y-m-d');
                $modelBodega->HOR_SIS = date('H:i:s');
                $modelBodega->USUARIO = $username;
                $modelBodega->EQUIPO = Utilities::getClientRealIP();
                if(!$modelBodega->save()){
                    $arr_errs = $modelBodega->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                if($tipo == "IN"){
                    $modelI = new CabeceraIngresoMercaderia();
                    $modelI->COD_BOD = $boddest;
                    $modelI->NUM_ING = $boddsec;
                    $modelI->FEC_ING = $fecha;
                    $modelI->COD_PRO = $codcli;
                    $modelI->NOM_PRO = $namcli;
                    $modelI->T_I_ING = $canitem;
                    $modelI->T_P_ING = $numart;
                    $modelI->TOT_COS = $total;
                    $modelI->IND_UPD = "L";
                    $modelI->TIP_ING = "IN";
                    $modelI->LIN_N01 = $observacion;
                    $modelI->LIN_N02 = $cbulto;
                    $modelI->LIN_N03 = $recibido;
                    $modelI->LIN_N04 = $revisado;
                    $modelI->LIN_N05 = $kardex;
                    $modelI->TIP_B_T = "EG";
                    $modelI->NUM_B_T = $bodosec;
                    $modelI->COD_B_T = $bodorig;
                    $modelI->FEC_SIS = date('Y-m-d');
                    $modelI->HOR_SIS = date('H:i:s');
                    $modelI->USUARIO = $username;
                    $modelI->EQUIPO = Utilities::getClientRealIP();
                    $modelI->EST_LOG = "1";
                    $modelI->EST_DEL = "1";
                    if(!$modelI->save()){
                        $arr_errs = $modelI->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    for($i = 0; $i < count($items); $i++){
                        $codArt = $items[$i][1];
                        $nomArt = $items[$i][2];
                        $cant = $items[$i][3];
                        $ppro = $items[$i][4];
                        $pref = $items[$i][5];
                        $tot = $items[$i][6];
                        // Se pregunta si existe item en existenciabodega para la bodega de destino si no la crea.
                        $arrExiBod = ExistenciaBodega::existeItemExistenciaBodega($boddest, $codArt);
                        if(isset($arrExiBod) && $arrExiBod['cant'] == 0){
                            $modelExBod = new ExistenciaBodega();
                            $modelExBod->COD_BOD = $boddest;
                            $modelExBod->COD_ART = $codArt;
                            $modelExBod->EXI_COM = 0;
                            $modelExBod->EXI_TOT = 0;
                            $modelExBod->P_COSTO = $pref;
                            $modelExBod->P_LISTA = $ppro;
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
                        $modelDI = new DetalleIngresoMercaderia();
                        $modelDI->COD_BOD = $boddest;
                        $modelDI->NUM_ING = $boddsec;
                        $modelDI->FEC_ING = $fecha;
                        $modelDI->COD_PRO = $codcli;
                        $modelDI->COD_ART = $codArt;
                        $modelDI->CAN_ANT = NULL;
                        $modelDI->CAN_PED = $cant;
                        $modelDI->CAN_DEV = $cant;
                        $modelDI->C_ANTER = NULL;
                        $modelDI->P_COSTO = $pref;
                        $modelDI->P_LISTA = $ppro;
                        $modelDI->T_COSTO = $tot;
                        $modelDI->IND_EST = "L";
                        $modelDI->TIP_ING = "IN";
                        $modelDI->TIP_B_T = "EG";
                        $modelDI->NUM_B_T = $bodosec;
                        $modelDI->COD_B_T = $bodorig;
                        $modelDI->FEC_SIS = date('Y-m-d');
                        $modelDI->HOR_SIS = date('H:i:s');
                        $modelDI->USUARIO = $username;
                        $modelDI->EQUIPO = Utilities::getClientRealIP();
                        $modelDI->EST_LOG = "1";
                        $modelDI->EST_DEL = "1";

                        if(!$modelDI->save()){
                            $arr_errs = $modelDI->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }

                        // se modifica stock en bodega destino
                        if(!ExistenciaBodega::modificarStockBodega($boddest, $codArt, $pref, $ppro, $cant, "IN")){
                            $arr_errs[] = financiero::t('egresomercaderia',"Error for code '{code}' with {cant} Item(s) to add/remove cellar {bod}.",
                            ['code' => "<b>".$codArt."</b>", 
                            'cant' => "<b>".$cant."</b>", 
                            'bod' => "<b>".$boddest."</b>",]);
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                    // Se actualiza la secuencia en la bodega Egreso
                    $modelBodega2 = Bodega::findOne(['COD_BOD' => $boddest, 'EST_LOG' => '1', 'EST_DEL' => '1',]);
                    $modelBodega2->NUM_ING = $boddsec;
                    $modelBodega2->FEC_SIS = date('Y-m-d');
                    $modelBodega2->HOR_SIS = date('H:i:s');
                    $modelBodega2->USUARIO = $username;
                    $modelBodega2->EQUIPO = Utilities::getClientRealIP();
                    if(!$modelBodega2->save()){
                        $arr_errs = $modelBodega2->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved.") . " " . 
                                    financiero::t('egresomercaderia',"Do you want to create a new Egress or review the information entered?"),
                    "title" => Yii::t('jslang', 'Success'),
                );

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
                $sec = $data['egr'];
                $tipo = $data['tip'];
                //// Anulacion de Cabecera de Egreso
                $modelCEgr = CabeceraEgresoMercaderia::findOne(['COD_BOD' => $codBod, 'NUM_EGR' => $sec, 'TIP_EGR' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
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
                $modelDEgr = DetalleEgresoMercaderia::findAll(['COD_BOD' => $codBod, 'NUM_EGR' => $sec, 'TIP_EGR' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
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
                    if(!ExistenciaBodega::reversarStockBodega($bod, $art, $pref, $ppro, $cant, "EG")){
                        $arr_errs[] = financiero::t('egresomercaderia',"Error for code '{code}' with {cant} Item(s) to add/remove cellar {bod}.",
                        ['code' => "<b>".$art."</b>", 
                        'cant' => "<b>".$cant."</b>", 
                        'bod' => "<b>".$bod."</b>",]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    //// Reversar Stock de cada articulo ingresado en la tabla maestra si es solo egreso
                    if(!isset($modelCEgr->TIP_B_T) || $modelCEgr->TIP_B_T == ""){
                        $modelArticulo = Articulo::findOne(['COD_ART' => $art, 'EST_LOG' => '1', 'EST_DEL' => '1']);
                        $modelArticulo->EXI_TOT = ($modelArticulo->EXI_TOT) + $cant;
                        if(!$modelArticulo->save()){
                            $arr_errs = $item->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                }
                
                //// Anulacion registro de Ingreso si es transferencia
                if(isset($modelCEgr->TIP_B_T) && $modelCEgr->TIP_B_T == "IN"){
                    //// Anulacion de Cabecera de Ingreso
                    $modelCIgr = CabeceraIngresoMercaderia::findOne(['COD_BOD' => $modelCEgr->COD_B_T, 'NUM_ING' => $modelCEgr->NUM_B_T, 'TIP_ING' => $modelCEgr->TIP_B_T, 'TIP_B_T' => $modelCEgr->TIP_EGR, 'EST_LOG' => '1', 'EST_DEL' => '1']);
                    $modelCIgr->IND_UPD = "A";
                    $modelCIgr->FEC_SIS = date('Y-m-d');
                    $modelCIgr->HOR_SIS = date('H:i:s');
                    $modelCIgr->USUARIO = $username;
                    $modelCIgr->EQUIPO = Utilities::getClientRealIP();
                    //$modelCIgr->EST_LOG = "1";
                    //$modelCIgr->EST_DEL = "1";
                    if(!$modelCIgr->save()){
                        $arr_errs = $modelCIgr->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }

                    //// Anulacion de Detalle de Ingreso
                    $modelDIgr = DetalleIngresoMercaderia::findAll(['COD_BOD' => $modelCEgr->COD_B_T, 'NUM_ING' => $modelCEgr->NUM_B_T, 'TIP_ING' => $modelCEgr->TIP_B_T, 'TIP_B_T' => $modelCEgr->TIP_EGR, 'EST_LOG' => '1', 'EST_DEL' => '1']);
                    foreach($modelDIgr as $key => $itemI){
                        $itemI->IND_EST = "A";
                        if(!$itemI->save()){
                            $arr_errs = $itemI->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                        //// Reversar Stock de cada articulo ingresado a la bodega
                        $cant = $itemI->CAN_PED;
                        $bod  = $itemI->COD_BOD;
                        $art  = $itemI->COD_ART;
                        $ppro = $itemI->P_LISTA;
                        $pref = $itemI->P_COSTO;
                        
                        if(!ExistenciaBodega::reversarStockBodega($bod, $art, $pref, $ppro, $cant, "IN")){
                            $arr_errs[] = financiero::t('egresomercaderia',"Error for code '{code}' with {cant} Item(s) to add/remove cellar {bod}.",
                            ['code' => "<b>".$art."</b>", 
                            'cant' => "<b>".$cant."</b>", 
                            'bod' => "<b>".$bod."</b>",]);
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }    
                    }
                }

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
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

    /**
     * Expexcel Action. Allow to download a Pdf document for Egress Transaction.
     *
     * @return void
     */
    public function actionPrintegress(){
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("egresomercaderia", "Report Merchandise Egress Items");  // Titulo del reporte
        $arrColumns = array(
            financiero::t("egresomercaderia", "Code"),
            financiero::t("egresomercaderia", "Name"),
            financiero::t("egresomercaderia", "Amount"),
        );
        
        $data = Yii::$app->request->get();
        $codBod = $data['cod'];
        $sec = $data['egr'];
        $tipo = $data['tip'];

        $model = CabeceraEgresoMercaderia::findOne(['COD_BOD' => $codBod, 'NUM_EGR' => $sec, 'TIP_EGR' => $tipo,'EST_LOG' => '1', 'EST_DEL' => '1']);
        $modelBodOrg = Bodega::findOne(['COD_BOD' => $codBod, 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $modelBodDst = (isset($model->COD_B_T) && $model->COD_B_T != "")?(Bodega::findOne(['COD_BOD' => $model->COD_B_T, 'EST_LOG' => '1', 'EST_DEL' => '1'])):NULL;
        $arrData = DetalleEgresoMercaderia::getItemsPrintByEgress($codBod, $sec, $tipo);
        $arrHeader = [
            "empresa" => Yii::$app->session->get("PB_empresa"),
            "lblEgreso" => financiero::t("egresomercaderia", "Merchandise Egress"),
            "secuencia" => $model->NUM_EGR,
            "lblDate" => financiero::t("egresomercaderia", "Egress Date"),
            "fecha" => $model->FEC_EGR,
            "lblbodOrg" => financiero::t("egresomercaderia", "Cellar Origin"),
            "lblbodDst" => financiero::t("egresomercaderia", "Cellar Destiny"),
            "lblbodCod" => financiero::t("egresomercaderia", "Code"),
            "lblbodNam" => financiero::t("egresomercaderia", "Name"),
            "lblbodSec" => financiero::t("egresomercaderia", "Secuence"),
            "lblbodAdd" => financiero::t("egresomercaderia", "Address"),
            "lblCliPro" => financiero::t("egresomercaderia", "Client/Provider"),
            "lblTotal" => financiero::t("egresomercaderia", "Total"),
            "lblCaja" => financiero::t("egresomercaderia", "Bulk Boxes"),
            "lblRecibido" => financiero::t("egresomercaderia", "Received By"),
            "lblRevisado" => financiero::t("egresomercaderia", "Reviewed By"),
            "lblKardex" => financiero::t("egresomercaderia", "Annotated Kardex"),
            "lblObservaciones" => financiero::t("egresomercaderia", "Observations"),
            "lblLiquidado" => financiero::t("egresomercaderia", "Liquidated"),
            "lblAnulado" => financiero::t("egresomercaderia", "Invalidated"),
        ];
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical     
        
        $this->layout = false;  
        $report->createReportPdf(
            $this->render('printpdf', [
                'model' => $model,
                'modelBodOrg' => $modelBodOrg,
                'modelBodDst' => $modelBodDst,
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
            financiero::t("egresomercaderia", "Cellar Code Org."),
            financiero::t("egresomercaderia", "Cellar Origin"),
            financiero::t("egresomercaderia", "Egress Type"),
            financiero::t("bodega", "Egress Number"),
            financiero::t("egresomercaderia", "Egress Date"),
            financiero::t("egresomercaderia", "Client Code"),
            financiero::t("egresomercaderia", "Client Name"),
            financiero::t("egresomercaderia", "Total of Egress"),
            financiero::t("egresomercaderia", "Cellar Code Dst."),
            financiero::t("egresomercaderia", "Cellar Destiny"),
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
        $nameReport = financiero::t("egresomercaderia", "Report Merchandise Egress Items");
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
        $this->view->title = financiero::t("egresomercaderia", "Report Merchandise Egress Items");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("egresomercaderia", "Cellar Code Org."),
            financiero::t("egresomercaderia", "Cellar Origin"),
            financiero::t("egresomercaderia", "Egress Type"),
            financiero::t("bodega", "Egress Number"),
            financiero::t("egresomercaderia", "Egress Date"),
            financiero::t("egresomercaderia", "Client Code"),
            financiero::t("egresomercaderia", "Client Name"),
            financiero::t("egresomercaderia", "Total of Egress"),
            financiero::t("egresomercaderia", "Cellar Code Dst."),
            financiero::t("egresomercaderia", "Cellar Destiny"),
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