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
use app\modules\gfinanciero\models\Departamentos;
use app\modules\gfinanciero\models\SubDepartamento;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class SubdepartamentoController extends CController {
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $objDep = new Departamentos();
        $model = new SubDepartamento();
        $data = Yii::$app->request->get();
        $Depart = $objDep->getDepartamentos();
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                'model' => $model->getAllItemsGrid($data["search"],$data['departamento'], true),
                'depart' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('departamento', '-- Select an Department Name --')]], $Depart), "Ids", "Nombre"),
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL,NULL, true),
            'depart' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('departamento', '-- Select an Department Name --')]], $Depart), "Ids", "Nombre"),
        ]);
    }
    
    /**
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $objDep = new Departamentos();
        $data = Yii::$app->request->get();
        $Depart = $objDep->getDepartamentos();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = SubDepartamento::findOne($id);
            return $this->render('view', [
                'model' => $model,
                'depart' => ArrayHelper::map(array_merge($Depart), "Ids", "Nombre"),
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
        $_SESSION['JSLANG']['Please select an Department.'] = financiero::t('departamento', 'Please select an Department.');
        //echo $new_id;
        $objDep = new Departamentos();
        $data = Yii::$app->request->get();
        $Depart = $objDep->getDepartamentos();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = SubDepartamento::findOne($id);
            return $this->render('edit', [
                'model' => $model,
                //'depart' => ArrayHelper::map(array_merge($Depart), "Ids", "Nombre"),
                'depart' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('departamento', '-- Select an Department Name --')]], $Depart), "Ids", "Nombre"),
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
        $objDep = new Departamentos();
        $new_id = SubDepartamento::getLastId();
        $Depart = $objDep->getDepartamentos();
        $_SESSION['JSLANG']['Please select an Department.'] = financiero::t('departamento', 'Please select an Department.');
        //echo $new_id;
        //Utilities::putMessageLogFile($new_id);
        return $this->render('new', [
            'new_id' => $new_id,
            //'depart' => ArrayHelper::map(array_merge($Depart), "Ids", "Nombre"),
            'depart' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('departamento', '-- Select an Department Name --')]], $Depart), "Ids", "Nombre"),
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
                $id = $data["id"];
                $idDep = $data["idDep"];
                $nombre = $data["nombre"];
                $fecha = $data["fecha"];

                $model = new SubDepartamento();
                $model->sdep_id = $id;
                $model->dep_id = $idDep;
                $model->sdep_nombre = $nombre;
                $model->sdep_fecha_creacion = date('Y-m-d H:i:s') ;
                $model->sdep_usuario_ingreso = $usu_id;
                $model->sdep_usuario_modifica = $usu_id;
                //$model->EQUIPO = Utilities::getClientRealIP();
                $model->sdep_estado = "1";
                $model->sdep_estado_logico = "1";
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
            $error = false;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = $data["id"];
                $idDep = $data["idDep"];
                $nombre = $data["nombre"];
                $fecha = $data["fecha"];
                
                $model = SubDepartamento::findOne(['sdep_id' => $id]);
                $model->dep_id = $idDep;
                $model->sdep_nombre = $nombre;
                $model->sdep_fecha_modificacion = date('Y-m-d H:i:s') ;
                $model->sdep_usuario_modifica = $usu_id;

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
            $error = false;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = $data["id"];
                $model = SubDepartamento::findOne(['sdep_id' => $id]);
                $model->sdep_fecha_modificacion = date('Y-m-d H:i:s') ;
                $model->sdep_usuario_modifica = $usu_id;
                $model->sdep_estado = "0";
                $model->sdep_estado_logico = "0";                
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
            financiero::t("departamento", "Code"),
            financiero::t("departamento", "Department"),
            financiero::t("departamento", "Sub Department"),            
            financiero::t("departamento", "Creation Date"),
            //financiero::t("departamento", "State"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        $model = new SubDepartamento();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
        }
        $nameReport = financiero::t("departamento", "Report Sub Department");
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
        $this->view->title = financiero::t("departamento", "Report Sub Department");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("departamento", "Code"),
            financiero::t("departamento", "Department"),
            financiero::t("departamento", "Sub Department"),            
            financiero::t("departamento", "Creation Date"),
            //financiero::t("departamento", "State"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new SubDepartamento();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
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