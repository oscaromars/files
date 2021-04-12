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
use app\modules\gfinanciero\models\Catalogo;
use app\modules\gfinanciero\models\Rubro;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class RubroController extends CController {

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new Rubro();
        $data = Yii::$app->request->post();
        
        if($data['ls_query_id'] == "autocomplete-cuentaprincipal"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Catalogo::getDataColumnsQueryWidget();
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
        if($data['ls_query_id'] == "autocomplete-cuentaprovisional"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Catalogo::getDataColumnsQueryWidget();
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
        $arr_tipo = Rubro::getTypesRubros();
        $arr_tipo = ['0' => financiero::t('rubro', '-- All Types of Headings --'),] + $arr_tipo;
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"], $data['type'], true),
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
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Rubro::findOne(['rub_id' => $id,]);
            
            $inventario_cprin = Catalogo::findOne(['COD_CTA' => $model->rub_cuenta_principal, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $inventario_cprov = Catalogo::findOne(['COD_CTA' => $model->rub_cuenta_provisional, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            
            $arr_tipo = Rubro::getTypesRubros();
            return $this->render('view', [
                'cprincipal'=> $inventario_cprin->NOM_CTA,
                'cprovisional'=> $inventario_cprov->NOM_CTA,
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
        
        $_SESSION['JSLANG']['Please select only Movement Accounts.'] = financiero::t('catalogo',"Please select only Movement Accounts.");
        
        $data = Yii::$app->request->get();
        
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Rubro::findOne(['rub_id' => $id,]);
            
            $inventario_cprin = Catalogo::findOne(['COD_CTA' => $model->rub_cuenta_principal, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $inventario_cprov = Catalogo::findOne(['COD_CTA' => $model->rub_cuenta_provisional, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            
            $arr_tipo = Rubro::getTypesRubros();
            return $this->render('edit', [
                
                'cprincipal'=> $inventario_cprin->NOM_CTA,
                'cprovisional'=> $inventario_cprov->NOM_CTA,
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
        $_SESSION['JSLANG']['Please select only Movement Accounts.'] = financiero::t('catalogo',"Please select only Movement Accounts.");
        $arr_tipo = Rubro::getTypesRubros();
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
                //// body logic begin
                $nombre = $data["nombre"];
                $type = $data['type'];
                $cprincipal = $data["cprincipal"];
                $cprovisional = $data["cprovisional"];
                
                  
                $model = new Rubro();
                
                $model->rub_nombre = $nombre;
                $model->rub_tipo = $type;
                $model->rub_cuenta_principal = $cprincipal;
                $model->rub_cuenta_provisional = $cprovisional;
                $model->rub_fecha_creacion = date('Y-m-d H:i:s');
                $model->rub_usuario_ingreso = $usu_id;//$username
                
                $model->rub_estado_logico = "1";
                $model->rub_estado = "1";
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
                $nombre = $data["nombre"];
                $type = $data['type'];
                $cprincipal = $data["cprincipal"];
                $cprovisional = $data["cprovisional"];
                
                $model = Rubro::findOne(['rub_id' => $id,]);
                
                $model->rub_nombre = $nombre;
                $model->rub_tipo = $type;
                $model->rub_cuenta_principal = $cprincipal;
                $model->rub_cuenta_provisional = $cprovisional;
                
                $model->rub_fecha_modificacion = date('Y-m-d H:i:s');
                $model->rub_usuario_ingreso = $usu_id;
               
                $model->rub_estado = "1";
                $model->rub_estado_logico = "1";
                
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
                $id = $data["id"];
                $model = Rubro::findOne(['rub_id' => $id,]);
                $model->rub_fecha_modificacion = date('Y-m-d H:i:s');
                $model->rub_usuario_modifica = $usu_id;
              
                $model->rub_estado_logico = '0';
                $model->rub_estado = '0';
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
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G");
        $arrHeader = array(
            financiero::t("rubro", "Heading Name"),
            financiero::t("rubro", "Type of  Heading"),
            financiero::t("rubro", "Main Account"),
            financiero::t("rubro", "Provisional Account"), 
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["type"] = $data['type'];
        }
        $arrData = array();
        $model = new Rubro();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["type"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, false, true);
        }
        $nameReport = financiero::t("rubro", "Report Headings");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    /**
     * Exppdf Action. Allow to download a Pdf document from index page.
     *
     * @return void
     */
    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = financiero::t("rubro", "Report Headings");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("rubro", "Heading Name"),
            financiero::t("rubro", "Type of  Heading"),
            financiero::t("rubro", "Main Account"),
            financiero::t("rubro", "Provisional Account"),                  
            
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["type"] = $data['type'];
        }
        $arrData = array();
        $model = new Rubro();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["type"], false, true);
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
