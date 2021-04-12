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
use app\modules\gfinanciero\models\Bodega;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;
use app\modules\gfinanciero\models\Establecimiento;

financiero::registerTranslations();

class BodegaController extends CController {
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        //echo "llego";
        $model = new Bodega();
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"], true),
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL, true),
        ]);
    }
    
    /**
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $objDat = new Establecimiento();
        $Punto = $objDat->getEstablecimiento();
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Bodega::findOne($id);
            return $this->render('view', [
                'model' => $model,
                'punto' => ArrayHelper::map(array_merge($Punto), "Ids", "Nombre"),
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
        $_SESSION['JSLANG']['Please select an Item.'] = financiero::t('bodega', 'Please select an Item.');
        $objDat = new Establecimiento();
        $Punto = $objDat->getEstablecimiento();
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Bodega::findOne($id);
            return $this->render('edit', [
                'model' => $model,
                'punto' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('bodega', 'Please select an Item.')]], $Punto), "Ids", "Nombre"),
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
        $_SESSION['JSLANG']['Please select an Item.'] = financiero::t('bodega', 'Please select an Item.');
        $objDat = new Establecimiento();
        $Punto = $objDat->getEstablecimiento();
        $new_id = Bodega::getLastId();
        return $this->render('new', [
            'new_id' => Utilities::add_ceros($new_id, 2),
            'punto' => ArrayHelper::map(array_merge([["Ids" => "0", "Nombre" => financiero::t('bodega', 'Please select an Item.')]], $Punto), "Ids", "Nombre"),
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
                $nombre = $data["nombre"];
                $direccion = $data["direccion"];
                $pais = $data["pais"];
                $ciudad = $data["ciudad"];
                $telefono = $data["telefono"];
                $correo = $data["correo"];
                $responsable = $data["responsable"];
                $punto = $data["punto"];
                $num_ing = $data["num_ing"];
                $num_egr = $data["num_egr"];
                $fecha = $data["fecha"];

                $model = new Bodega();
                $model->COD_BOD = $id;
                $model->NOM_BOD = $nombre;
                $model->NUM_ING = $num_ing;
                $model->NUM_EGR = $num_egr;
                $model->COD_PTO = $punto;
                $model->DIR_BOD = $direccion;
                $model->COD_PAI = $pais;
                $model->COD_CIU = $ciudad;
                $model->TEL_N01 = $telefono;
                $model->TEL_N02 = NULL;
                $model->NUM_FAX = NULL;
                $model->CORRE_E = $correo;
                $model->COD_RES = $responsable;         
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
                $nombre = $data["nombre"];
                $direccion = $data["direccion"];
                $pais = $data["pais"];
                $ciudad = $data["ciudad"];
                $telefono = $data["telefono"];
                $correo = $data["correo"];
                $responsable = $data["responsable"];
                $punto = $data["punto"];
                $num_ing = $data["num_ing"];
                $num_egr = $data["num_egr"];
                $fecha = $data["fecha"];

                $model = Bodega::findOne(['COD_BOD' => $id]);                              
                //$model->COD_BOD = $id;
                $model->NOM_BOD = $nombre;
                $model->NUM_ING = $num_ing;
                $model->NUM_EGR = $num_egr;
                $model->COD_PTO = $punto;
                $model->DIR_BOD = $direccion;
                $model->COD_PAI = $pais;
                $model->COD_CIU = $ciudad;
                $model->TEL_N01 = $telefono;
                $model->TEL_N02 = NULL;
                $model->NUM_FAX = NULL;
                $model->CORRE_E = $correo;
                $model->COD_RES = $responsable;         
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
                $model = Bodega::findOne(['COD_BOD' => $id]);
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
        $colPosition = array("C", "D", "E", "F","G", "H", "I");
        //COD_BOD,NOM_BOD,DIR_BOD,TEL_N01,CORRE_E,NUM_ING,NUM_EGR
        $arrHeader = array(
            financiero::t("bodega", "Code"),
            financiero::t("bodega", "Cellar"),
            financiero::t("bodega", "Address"),
            financiero::t("bodega", "Phone"),
            financiero::t("bodega", "Mail"),
            financiero::t("bodega", "Income Number"),
            financiero::t("bodega", "Egress Number"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new Bodega();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
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
        //COD_BOD,NOM_BOD,DIR_BOD,TEL_N01,CORRE_E,NUM_ING,NUM_EGR
        $arrHeader = array(
            financiero::t("bodega", "Code"),
            financiero::t("bodega", "Cellar"),
            financiero::t("bodega", "Address"),
            financiero::t("bodega", "Phone"),
            financiero::t("bodega", "Mail"),
            financiero::t("bodega", "Income Number"),
            financiero::t("bodega", "Egress Number"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new Bodega();
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