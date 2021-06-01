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
use app\modules\gfinanciero\models\PuntoEmision;
use app\modules\gfinanciero\models\Establecimiento;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;
use yii\helpers\Url;

financiero::registerTranslations();

class PuntoemisionController extends CController {

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new PuntoEmision();
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                        "model" => $model->getAllItemsGrid($data["search"], true),
            ]);
        }
        /* if (Yii::$app->request->isAjax) { } */
        return $this->render('index', [
                    'model' => $model->getAllItemsGrid(NULL, true),
        ]);
    }

    /**
     * SetPunto Action. List the items from a Model
     *
     * @return void
     */
    public function actionSetpunto() {
        $model = new PuntoEmision();
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isAjax) { 
            if($data['getEmision']){
                $pEstablecimiento = $data['pEstablecimiento'];
                $arr_pemision = PuntoEmision::find()
                    ->select(["COD_CAJ AS id", "CONCAT(COD_CAJ, ' - ', NOM_CAJ) AS name"])
                    ->where(["EST_LOG" => "1", "EST_DEL" => "1", 'COD_PTO' => $pEstablecimiento,])
                    ->asArray()->all();
                $message = array("pemision" => $arr_pemision);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if($data['setPunto']){
                $pEstablecimiento = $data['pEstablecimiento'];
                $pEmision = $data['pEmision'];
                Yii::$app->session->set("PB_p_establecimiento", $pEstablecimiento);
                Yii::$app->session->set("PB_p_emision", $pEmision);
                $url = Url::previous();
                //$this->redirect(Url::to($url));
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                $addicionalData['returnUrl'] = $url;
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message, $addicionalData);
            }
        }
        $pEstablecimiento = Yii::$app->session->get("PB_p_establecimiento");
        $pEmision = Yii::$app->session->get("PB_p_emision");
        $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
        $codpto = $arr_establecimiento[0]['id'];
        $arr_pemision = PuntoEmision::find()
                    ->select(["COD_CAJ AS id", "CONCAT(COD_CAJ, ' - ', NOM_CAJ) AS name"])
                    ->where(["EST_LOG" => "1", "EST_DEL" => "1", 'COD_PTO' => $codpto,])
                    ->asArray()->all();
        return $this->render('set-punto', [
                'arr_establecimiento' => ArrayHelper::map($arr_establecimiento, "id", "name"),
                'arr_pemision' => ArrayHelper::map($arr_pemision, "id", "name"),
                'pEstablecimiento' => (isset($pEstablecimiento) && $pEstablecimiento != "")?$pEstablecimiento:0,
                'pEmision' => (isset($pEmision) && $pEmision != "")?$pEmision:0,
        ]);
    }

    /**
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['codpto'])) {
            $codpto = $data['codpto'];
            $codcaj = $data['codcaj'];
            $model = PuntoEmision::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaj,]);
            $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
            $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- Select a Establishment --')], ArrayHelper::map($arr_establecimiento, "id", "name"));

            return $this->render('view', [
                        'model' => $model,
                        'arr_establecimiento' => $arr_establecimiento,
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
        $_SESSION['JSLANG']['Please select an Establishment.'] = financiero::t('establecimiento', "Please select an Establishment.");

        $data = Yii::$app->request->get();
        if (isset($data['codpto'])) {
            $codpto = $data['codpto'];
            $codcaj = $data['codcaj'];
            $model = PuntoEmision::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaj,]);
            $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
            $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- Select a Establishment --')], ArrayHelper::map($arr_establecimiento, "id", "name"));

            return $this->render('edit', [
                        'model' => $model,
                        'arr_establecimiento' => $arr_establecimiento,
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
        $_SESSION['JSLANG']['Please select an Establishment.'] = financiero::t('establecimiento', "Please select an Establishment.");

        $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
        $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- Select a Establishment --')], ArrayHelper::map($arr_establecimiento, "id", "name"));

        return $this->render('new', [
                    'arr_establecimiento' => $arr_establecimiento,
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
                $codcaja = $data["codcaja"];
                $codpto = $data["codpunto"];
                $nomcaj = $data["nomcaj"];
                $ubicaj = $data["ubicaj"];
                $autcaj = $data["autcaj"];
                $cajfec = $data["cajfec"];
                $codres = $data["codres"];

                $model = new PuntoEmision();
                $model->COD_PTO = $codpto;
                $model->COD_CAJ = $codcaja;
                $model->NOM_CAJ = $nomcaj;
                $model->UBI_CAJ = $ubicaj;
                $model->COD_RES = $codres;
                $model->AUT_CAJ = $autcaj;
                $model->REG_ASO = null;
                $model->COB_CAJ = null;
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->CAJ_FEC = $cajfec;
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
                $msg = (($exMsg->getMsgLang()) != '') ? ($exMsg->getMsgLang()) : (Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
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
                $codcaja = $data["codcaja"];
                $codpto = $data["codpunto"];
                $nomcaj = $data["nomcaj"];
                $ubicaj = $data["ubicaj"];
                $autcaj = $data["autcaj"];
                $cajfec = $data["cajfec"];
                $codres = $data["codres"];

                $model = PuntoEmision::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaja,]);

                $model->NOM_CAJ = $nomcaj;
                $model->UBI_CAJ = $ubicaj;
                $model->COD_RES = $codres;
                $model->AUT_CAJ = $autcaj;
                $model->REG_ASO = null;
                $model->COB_CAJ = null;
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->CAJ_FEC = $cajfec;
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
                $msg = (($exMsg->getMsgLang()) != '') ? ($exMsg->getMsgLang()) : (Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
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
    public function actionDelete() {
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
                $codpto = $data["codpunto"];
                $codcaja = $data["codcaja"];
                $model = PuntoEmision::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaja,]);

                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = '0';
                $model->EST_DEL = '0';
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
                $msg = (($exMsg->getMsgLang()) != '') ? ($exMsg->getMsgLang()) : (Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
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
        $colPosition = array("C", "D", "E", "F", "G", "H");
        $arrHeader = array(
            financiero::t("puntoemision", "Emission Point Code"),
            financiero::t("establecimiento", "Establishment"),
            financiero::t("puntoemision", "Name"),
            financiero::t("puntoemision", "Location"),
            financiero::t("puntoemision", "Date"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new PuntoEmision();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
        }
        $nameReport = financiero::t("puntoemision", "Report Emission Points");
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
        $this->view->title = financiero::t("puntoemision", "Report Emission Points");  // Titulo del reporte
        $arrHeader = array(
             financiero::t("puntoemision", "Emission Point Code"),
            financiero::t("establecimiento", "Establishment"),
            financiero::t("puntoemision", "Name"),
            financiero::t("puntoemision", "Location"),
            financiero::t("puntoemision", "Date"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new PuntoEmision();
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
