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
use app\modules\gfinanciero\models\Localidad;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Canton;
use app\models\Pais;
use app\models\Provincia;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class LocalidadController extends CController {

    private $codes = ["0" => "-- Select a Location Type --", "01" => 'Country', "02" => "State", "03" => "City"];

    private function getCodesArray(){
        $arr_codes = $this->codes;
        foreach($arr_codes as $key => $value){
            $arr_codes[$key] = financiero::t('localidad', $value);
        }
        return $arr_codes;
    }
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new Localidad();
        $data = Yii::$app->request->get();
        $arr_tipo = $this->getCodesArray();
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"], $data["tipo"], true),
                'codes' => $this->codes,
                'arr_tipo' => $arr_tipo,
            ]);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getPais"])) {
                $pais_id = $data['pai_id'];
                $provincias = Provincia::find()->
                                    select("pro_id AS id, pro_nombre AS name")
                                    ->where(['pro_estado' => '1', 'pro_estado_logico' => '1', 'pai_id' => $pais_id])
                                    ->asArray()->all();
                $arr_provincias = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a State --')]], $provincias);
                $pro_id = current($provincias);
                $ciudades = Canton::find()->
                                    select("can_id AS id, can_nombre AS name")
                                    ->where(['can_estado' => '1', 'can_estado_logico' => '1', 'pro_id' => $pro_id['id'],])
                                    ->asArray()->all();
                $arr_ciudades = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a City --')]], $ciudades);
                $message = array("provincias" => $arr_provincias, "ciudades" => $arr_ciudades);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getProvincia"])) {
                $pro_id = $data['pro_id'];
                $ciudades = Canton::find()->
                                    select("can_id AS id, can_nombre AS name")
                                    ->where(['can_estado' => '1', 'can_estado_logico' => '1', 'pro_id' => $pro_id,])
                                    ->asArray()->all();
                $arr_ciudades = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a City --')]], $ciudades);
                $message = array("ciudades" => $arr_ciudades);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL, NULL, true),
            'codes' => $this->codes,
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
        if (isset($data['id'])) {
            $id = $data['id'];
            $cod = $data['cod'];
            $arr_tipo = $this->getCodesArray();
            $model = Localidad::findOne(['C_I_OCG' => $id, 'COD_OCG' => $cod]);
            return $this->render('view', [
                'model' => $model,
                'arr_tipo' => $arr_tipo,
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
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $cod = $data['cod'];
            $arr_tipo = $this->getCodesArray();
            $model = Localidad::findOne(['C_I_OCG' => $id, 'COD_OCG' => $cod]);
            return $this->render('edit', [
                'model' => $model,
                'arr_tipo' => $arr_tipo,
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
        //$new_id = TipoArticulo::getNextIdItemRecord();
        $arr_pais = Pais::find()->
            select("pai_id AS id, pai_nombre AS name")
            ->where(['pai_estado' => '1', 'pai_estado_logico' => '1'])
            ->asArray()->all();
        $arr_pais = ['0' => financiero::t('localidad', '-- Select a Country --')] + ArrayHelper::map($arr_pais, "id", "name");
        $arr_estado = ['0' => financiero::t('localidad', '-- Select a State --')];
        $arr_ciudad = ['0' => financiero::t('localidad', '-- Select a City --')];
        return $this->render('new', [
            //'new_id' => $new_id,
            'arr_pais' => $arr_pais,
            'arr_estado' => $arr_estado,
            'arr_ciudad' => $arr_ciudad,
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
                //// body logic begin
                $pai_id = $data["pai_id"];
                $pro_id = $data['pro_id'];
                $can_id = $data["can_id"];

                $modPai = Pais::findOne($pai_id);
                $modPro = Provincia::findOne($pro_id);
                $modCan = Canton::findOne($can_id);

                // se crea el pais
                $modLocalidad = Localidad::findOne(['C_I_OCG' => '01', 'COD_OCG' => $modPai->pai_id, 'EST_LOG' => '1', 'EST_DEL' => '1',]);
                if(!$modLocalidad){
                    $model = new Localidad();
                    $model->C_I_OCG = '01';
                    $model->COD_OCG = "$modPai->pai_id";
                    $model->NOM_OCG = $modPai->pai_nombre;
                    $model->FEC_SIS = date('Y-m-d');
                    $model->HOR_SIS = date('H:i:s');
                    $model->USUARIO = $username;
                    $model->EQUIPO = Utilities::getClientRealIP();
                    $model->EST_LOG = "1";
                    $model->EST_DEL = "1";
                    if (!$model->save()) {
                        $arr_errs = $model->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                // se crea la provincia
                $modLocalidad = Localidad::findOne(['C_I_OCG' => '02', 'COD_OCG' => $modPro->pro_id, 'EST_LOG' => '1', 'EST_DEL' => '1',]);
                if(!$modLocalidad){
                    $model = new Localidad();
                    $model->C_I_OCG = '02';
                    $model->COD_OCG = "$modPro->pro_id";
                    $model->NOM_OCG = $modPro->pro_nombre;
                    $model->FEC_SIS = date('Y-m-d');
                    $model->HOR_SIS = date('H:i:s');
                    $model->USUARIO = $username;
                    $model->EQUIPO = Utilities::getClientRealIP();
                    $model->EST_LOG = "1";
                    $model->EST_DEL = "1";
                    if (!$model->save()) {
                        $arr_errs = $model->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                // se crea la ciudad
                $modLocalidad = Localidad::findOne(['C_I_OCG' => '03', 'COD_OCG' => $modCan->can_id, 'EST_LOG' => '1', 'EST_DEL' => '1',]);
                if(!$modLocalidad){
                    $model = new Localidad();
                    $model->C_I_OCG = '03';
                    $model->COD_OCG = "$modCan->can_id";
                    $model->NOM_OCG = $modCan->can_nombre;
                    $model->FEC_SIS = date('Y-m-d');
                    $model->HOR_SIS = date('H:i:s');
                    $model->USUARIO = $username;
                    $model->EQUIPO = Utilities::getClientRealIP();
                    $model->EST_LOG = "1";
                    $model->EST_DEL = "1";
                    if (!$model->save()) {
                        $arr_errs = $model->getFirstErrors();
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
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id  = $data["id"];
                $cod = $data['cod'];
                $nombre = $data["nombre"];

                $model = Localidad::findOne(['C_I_OCG' => $id, 'COD_OCG' => $cod]);
                //$model->C_I_OCG = $id;
                //$model->COD_OCG = $cod;
                $model->NOM_OCG = $nombre;
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO  = Utilities::getClientRealIP();
                $model->EST_LOG = "1";
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
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = (strlen($data["id"]) == 1)?("0".$data["id"]):($data["id"]);
                $cod = (strlen($data["cod"]) == 1)?("0".$data["cod"]):($data["cod"]);
                $model = Localidad::findOne(['C_I_OCG' => $id, 'COD_OCG' => $cod]);
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = '0';
                $model->EST_DEL = '0';
                //// body logic begin

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->delete()) {
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
        $colPosition = array("C", "D");
        $arrHeader = array(
            financiero::t("localidad", "Location Type"),
            financiero::t("localidad", "Location Name"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["tipo"] = $data['tipo'];
        }
        $arrData = array();
        $model = new Localidad();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["tipo"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, false, true);
        }
        $codes = $this->codes;
        foreach($arrData as $key => $value){
            $arrData[$key]['Id'] = financiero::t('localidad', $codes[$value['Id']]);
        }
        $nameReport = financiero::t("localidad", "Report Location Items");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    /**
     * Exppdf Action. Allow to download a Pdf document from index page.
     *
     * @return void
     */
    public function actionExppdf() {
        //ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("localidad", "Report Location Items");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("localidad", "Location Type"),
            financiero::t("localidad", "Location Name"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["tipo"] = $data['tipo'];
        }
        $arrData = array();
        $model = new Localidad();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["tipo"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, false, true);
        }
        $codes = $this->codes;
        foreach($arrData as $key => $value){
            $arrData[$key]['Id'] = financiero::t('localidad', $codes[$value['Id']]);
        }
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
}