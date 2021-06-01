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
use app\modules\gfinanciero\models\Establecimiento;
use app\modules\gfinanciero\models\Emision;
use app\modules\gfinanciero\models\TipoDocumento;
use app\modules\gfinanciero\models\TipoEdoc;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class TipodocumentoController extends CController {

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new TipoDocumento();
        $data = Yii::$app->request->post();
        if ($data['ls_query_id'] == "autocomplete-ctaiva") {
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

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getemision"])) {
                $est_id = $data["establecimiento"];
                $list_emision = Emision::find()->
                                select(["COD_CAJ AS id", "CONCAT(COD_CAJ,' - ',NOM_CAJ) AS name"])
                                ->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'COD_PTO' => $est_id])
                                ->asArray()->all();
                $arr_emision = array_merge(['0' => ['id' => '0', 'name' => financiero::t('emision', '-- All Types of Issue --')]], $list_emision);

                $message = array("arr_emision" => $arr_emision);

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

        $data = Yii::$app->request->get();
        $arr_establecimiento = Establecimiento::getTypeEstablecimiento();

        $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- All of Establishments --')], ArrayHelper::map($arr_establecimiento, "id", "name"));

        $arr_emision = ['0' => financiero::t('emision', '-- All Types of Issue --')];

        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                        "model" => $model->getAllItemsGrid($data["search"], $data['type_est'], $data['type_emi'], true),
                        'arr_establecimiento' => $arr_establecimiento,
                        'arr_emision' => $arr_emision,
            ]);
        }

        return $this->render('index', [
                    "model" => $model->getAllItemsGrid($data["search"], $data['type_est'], $data['type_emi'], true),
                    'arr_establecimiento' => $arr_establecimiento,
                    'arr_emision' => $arr_emision,
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
            $tipnof = $data['tipnof'];
            $model = TipoDocumento::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaj, 'TIP_NOF' => $tipnof,]);
            $arr_tipo_sec = TipoDocumento::getTypesSequence();
            $inventario_ctaiva = Catalogo::findOne(['COD_CTA' => $model->CTA_IVA, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $arr_tipo_doc = TipoDocumento::getListTypeDoc();
            $tip_trans = "O";

            $arr_tipo_trans = TipoDocumento::getListTypeDoc();

            $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
            $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- Select a Establishment --')], ArrayHelper::map($arr_establecimiento, "id", "name"));

            $arr_tipo_edoc = TipoEdoc::getListEdoc();
            $arr_tipo_edoc = array_merge(['0' => financiero::t('tipoedoc', '-- None --')], ArrayHelper::map($arr_tipo_edoc, "id", "name"));

            if ($model->TIP_DOC == "V")
                $tip_trans = "V";
            if ($model->TIP_DOC == "C")
                $tip_trans = "C";
            $modelTipEdoc = TipoEdoc::findOne(['TipoDocumento' => $model->EDOC_TIP_DOC]);

            $list_emision = Emision::find()->
                            select(["COD_CAJ AS id", "CONCAT(COD_CAJ,' - ',NOM_CAJ) AS name"])
                            ->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'COD_PTO' => $model->COD_PTO])
                            ->asArray()->all();
            $arr_emision = array_merge(['0' => financiero::t('emision', '-- Select a type of Issue --')], ArrayHelper::map($list_emision, "id", "name"));

            return $this->render('view', [
                        'model' => $model,
                        'arr_tipo_sec' => $arr_tipo_sec,
                        'arr_tipo_doc' => $arr_tipo_doc,
                        'ctaiva' => $inventario_ctaiva->NOM_CTA,
                        'arr_establecimiento' => $arr_establecimiento,
                        'arr_tipo_trans' => $arr_tipo_trans,
                        'arr_emision' => $arr_emision,
                        'arr_tipo_edoc' => $arr_tipo_edoc,
                        'tip_trans' => $tip_trans,
                        'modelTipEdoc' => $modelTipEdoc,
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
        $_SESSION['JSLANG']['Please select only Movement Accounts.'] = financiero::t('catalogo', "Please select only Movement Accounts.");
        $_SESSION['JSLANG']['Please select an Establishment.'] = financiero::t('establecimiento', "Please select an Establishment.");
        $_SESSION['JSLANG']['Please select an Issue.'] = financiero::t('emision', "Please select an Issue.");
        $data = Yii::$app->request->get();
        if (isset($data['codpto'])) {
            $codpto = $data['codpto'];
            $codcaj = $data['codcaj'];
            $tipnof = $data['tipnof'];
            $model = TipoDocumento::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaj, 'TIP_NOF' => $tipnof,]);
            $arr_tipo_sec = TipoDocumento::getTypesSequence();
            $inventario_ctaiva = Catalogo::findOne(['COD_CTA' => $model->CTA_IVA, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $arr_tipo_doc = TipoDocumento::getListTypeDoc();
            $tip_trans = "O";

            $arr_tipo_trans = TipoDocumento::getListTypeDoc();

            $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
            $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- Select a Establishment --')], ArrayHelper::map($arr_establecimiento, "id", "name"));

            $arr_tipo_edoc = TipoEdoc::getListEdoc();
            $arr_tipo_edoc = array_merge(['0' => financiero::t('tipoedoc', '-- None --')], ArrayHelper::map($arr_tipo_edoc, "id", "name"));

            if ($model->TIP_DOC == "V")
                $tip_trans = "V";
            if ($model->TIP_DOC == "C")
                $tip_trans = "C";
            $modelTipEdoc = TipoEdoc::findOne(['TipoDocumento' => $model->EDOC_TIP_DOC]);

            $list_emision = Emision::find()->
                            select(["COD_CAJ AS id", "CONCAT(COD_CAJ,' - ',NOM_CAJ) AS name"])
                            ->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'COD_PTO' => $model->COD_PTO])
                            ->asArray()->all();
            $arr_emision = array_merge(['0' => financiero::t('emision', '-- Select a type of Issue --')], ArrayHelper::map($list_emision, "id", "name"));

            return $this->render('edit', [
                        'model' => $model,
                        'arr_tipo_sec' => $arr_tipo_sec,
                        'arr_tipo_doc' => $arr_tipo_doc,
                        'ctaiva' => $inventario_ctaiva->NOM_CTA,
                        'arr_establecimiento' => $arr_establecimiento,
                        'arr_tipo_trans' => $arr_tipo_trans,
                        'arr_emision' => $arr_emision,
                        'arr_tipo_edoc' => $arr_tipo_edoc,
                        'tip_trans' => $tip_trans,
                        'modelTipEdoc' => $modelTipEdoc,
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
        $_SESSION['JSLANG']['Please select only Movement Accounts.'] = financiero::t('catalogo', "Please select only Movement Accounts.");
        $_SESSION['JSLANG']['Please select an Establishment.'] = financiero::t('establecimiento', "Please select an Establishment.");
        $_SESSION['JSLANG']['Please select an Issue.'] = financiero::t('emision', "Please select an Issue.");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getemision"])) {
                $est_id = $data["establecimiento"];
                $list_emision = Emision::find()->
                                select(["COD_CAJ AS id", "CONCAT(COD_CAJ,' - ',NOM_CAJ) AS name"])
                                ->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'COD_PTO' => $est_id])
                                ->asArray()->all();
                $arr_emision = array_merge(['0' => ['id' => '0', 'name' => financiero::t('emision', '-- Select a type of Issue --')]], $list_emision);

                $message = array("arr_emision" => $arr_emision);

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

        $arr_tipo_sec = TipoDocumento::getTypesSequence();
        $arr_tipo_trans = TipoDocumento::getListTypeDoc();

        $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
        $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- Select a Establishment --')], ArrayHelper::map($arr_establecimiento, "id", "name"));
        $arr_emision = ['0' => financiero::t('emision', '-- Select a type of Issue --')];

        $arr_tipo_edoc = TipoEdoc::getListEdoc();
        $arr_tipo_edoc = array_merge(['0' => financiero::t('tipoedoc', '-- None --')], ArrayHelper::map($arr_tipo_edoc, "id", "name"));

        return $this->render('new', [
                    'arr_tipo_sec' => $arr_tipo_sec,
                    'arr_tipo_trans' => $arr_tipo_trans,
                    'arr_establecimiento' => $arr_establecimiento,
                    'arr_emision' => $arr_emision,
                    'arr_tipo_edoc' => $arr_tipo_edoc,
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

                $codpto = $data["codpunto"];
                $codcaja = $data["codcaja"];
                $tipnof = $data["tipnof"];
                $numdocumento = "0000000000"; //$data["numdocumento"];
                $nombredocumento = $data["nombredocumento"];
                $fechadocumento = $data["fechadocumento"];
                $iva = $data["iva"];
                $ctaiva = $data["ctaiva"];
                $edoctipo = $data["edoctipo"];
                $tipdoc = $data["tipdoc"];
                $cantitems = $data["cantitems"];
                $secuencia = $data["secuencia"];
                $sedoc = $data['sedoc'];
                $doc = $data['doc'];

                $model = new TipoDocumento();
                $modelVsEDOC = TipoEdoc::findOne(['IdDirectorio' => $edoctipo]);
                $model->COD_PTO = $codpto;
                $model->COD_CAJ = $codcaja;
                $model->TIP_NOF = $tipnof;
                $model->NUM_NOF = $numdocumento;
                $model->NOM_NOF = $nombredocumento;
                $model->EDOC_TIP_DOC = ($edoctipo == 0) ? "" : $modelVsEDOC->TipoDocumento;
                $model->EDOC_EST = ($edoctipo == 0) ? "0" : "$sedoc";
                $model->POR_IVA = $iva;
                $model->FEC_NOF = $fechadocumento;
                $model->HOR_NOF = date('H:i:s');
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->TIP_DOC = $doc;
                $model->C_ITEMS = $cantitems;
                $model->CTA_IVA = $ctaiva;
                $model->NUM_INI = null;
                $model->NUM_FIN = null;
                $model->SEC_AUT = $secuencia;
                $model->NUM_SER = null;
                $model->NUM_AUT = null;
                $model->FEC_CAD = null;
                $model->INC_IVA = "0";
                $model->DOC_AUT = null;
                $model->FEC_AUT = null;
                $model->EST_LOG = "1";
                $model->EST_DEL = "1";

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

                $codpto = $data["codpunto"];
                $codcaja = $data["codcaja"];
                $tipnof = $data["tipnof"];
                $numdocumento = $data["numdocumento"];
                $nombredocumento = $data["nombredocumento"];
                $fechadocumento = $data["fechadocumento"];
                $iva = $data["iva"];
                $ctaiva = $data["ctaiva"];
                $edoctipo = $data["edoctipo"];
                $tipdoc = $data["tipdoc"];
                $cantitems = $data["cantitems"];
                $secuencia = $data["secuencia"];
                $sedoc = $data['sedoc'];
                $doc = $data['doc'];

                $model = TipoDocumento::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaja, 'TIP_NOF' => $tipnof,]);

                $modelVsEDOC = TipoEdoc::findOne(['IdDirectorio' => $edoctipo]);
                $model->COD_PTO = $codpto;
                $model->COD_CAJ = $codcaja;
                $model->TIP_NOF = $tipnof;
                $model->NUM_NOF = $numdocumento;
                $model->NOM_NOF = $nombredocumento;
                $model->EDOC_TIP_DOC = ($edoctipo == 0) ? "" : $modelVsEDOC->TipoDocumento;
                $model->EDOC_EST = ($edoctipo == 0) ? "0" : "$sedoc";
                $model->POR_IVA = $iva;
                $model->FEC_NOF = $fechadocumento;
                $model->HOR_NOF = date('H:i:s');
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->TIP_DOC = $doc;
                $model->C_ITEMS = $cantitems;
                $model->CTA_IVA = $ctaiva;
                $model->NUM_INI = null;
                $model->NUM_FIN = null;
                $model->SEC_AUT = $secuencia;
                $model->NUM_SER = null;
                $model->NUM_AUT = null;
                $model->FEC_CAD = null;
                $model->INC_IVA = "0";
                $model->DOC_AUT = null;
                $model->FEC_AUT = null;
                $model->EST_LOG = "1";
                $model->EST_DEL = "1";

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
                $tipnof = $data["tipnof"];

                $model = TipoDocumento::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaja, 'TIP_NOF' => $tipnof,]);

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
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrHeader = array(
            financiero::t("tipodocumento", "Code"),
            financiero::t("tipodocumento", "Point Code"),
            financiero::t("tipodocumento", "Box Code"),
            financiero::t("tipodocumento", "Document Number"),
            financiero::t("tipodocumento", "Document Name"),
            financiero::t("tipodocumento", "Vat %"),
            financiero::t("tipodocumento", "Acc. Iva"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["type_est"] = $data['type_est'];
            $arrSearch["type_emi"] = $data['type_emi'];
        }
        $arrData = array();
        $model = new TipoDocumento();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["type_est"], $arrSearch["type_emi"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, NULL, false, true);
        }
        $nameReport = financiero::t("tipodocumento", "Report Type Documents");
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
        $this->view->title = financiero::t("tipodocumento", "Report Type Documents");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("tipodocumento", "Code"),
            financiero::t("tipodocumento", "Point Code"),
            financiero::t("tipodocumento", "Box Code"),
            financiero::t("tipodocumento", "Document Number"),
            financiero::t("tipodocumento", "Document Name"),
            financiero::t("tipodocumento", "Vat %"),
            financiero::t("tipodocumento", "Acc. Iva")
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["type_est"] = $data['type_est'];
            $arrSearch["type_emi"] = $data['type_emi'];
        }
        $arrData = array();
        $model = new TipoDocumento();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["type_est"], $arrSearch["type_emi"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, NULL, false, true);
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
