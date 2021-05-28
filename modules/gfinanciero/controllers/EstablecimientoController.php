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
use app\modules\gfinanciero\models\Establecimiento;
use app\modules\gfinanciero\models\Localidad;
use app\models\Canton;
use app\models\Pais;
use app\models\Provincia;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class EstablecimientoController extends CController {

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new Establecimiento();
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getPais"])) {
                $pais_id = $data['pai_id'];
                $provincias = Localidad::getAllStatesByCountry($pais_id);
                $arr_provincias = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a State --')]], $provincias);
                $pro_id = current($provincias);
                $ciudades = Localidad::getAllCitiesByState($pro_id);
                $arr_ciudades = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a City --')]], $ciudades);
                $message = array("provincias" => $arr_provincias, "ciudades" => $arr_ciudades);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getProvincia"])) {
                $pro_id = $data['pro_id'];
                $ciudades = Localidad::getAllCitiesByState($pro_id);
                $arr_ciudades = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a City --')]], $ciudades);
                $message = array("ciudades" => $arr_ciudades);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
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
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Establecimiento::findOne(['COD_PTO' => $id,]);

            $ciudad_def = $model->COD_CIU;
            $can_data = Canton::findOne(['can_id' => $ciudad_def, 'can_estado' => '1', 'can_estado_logico' => '1']);
            $pro_data = Provincia::findOne(['pro_id' => $can_data->pro_id, 'pro_estado' => '1', 'pro_estado_logico' => '1']);
            //$pai_data = Pais::findOne(['pai_id' => $pro_data->pai_id, 'pai_estado' => '1', 'pai_estado_logico' => '1']);
            $pais_def = $pro_data->pai_id;
            $provincia_def = $pro_data->pro_id;

            $arr_pais = Localidad::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'C_I_OCG' => '01',])->orderBy(['NOM_OCG' => SORT_ASC])->all();
            $arr_pais = ['0' => financiero::t('localidad', '-- Select a Country --')] + ArrayHelper::map($arr_pais, "COD_OCG", "NOM_OCG");
            $arr_provincia = Localidad::getAllStatesByCity($ciudad_def);
            $arr_provincia = ['0' => financiero::t('localidad', '-- Select a State --')] + ArrayHelper::map($arr_provincia, "id", "name");
            $arr_ciudad = Localidad::getAllCitiesByState($provincia_def);
            $arr_ciudad = ['0' => financiero::t('localidad', '-- Select a City --')] + ArrayHelper::map($arr_ciudad, "id", "name");

            return $this->render('view', [
                        'model' => $model,
                        'arr_pais' => $arr_pais,
                        'arr_provincia' => $arr_provincia,
                        'arr_ciudad' => $arr_ciudad,
                        'pais_def' => $pais_def,
                        'provincia_def' => $provincia_def,
                        'ciudad_def' => $ciudad_def,
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

        $_SESSION['JSLANG']['Please select a Country.'] = financiero::t('localidad', "Please select a Country.");
        $_SESSION['JSLANG']['Please select a State.'] = financiero::t('localidad', "Please select a State.");
        $_SESSION['JSLANG']['Please select a City.'] = financiero::t('localidad', "Please select a City.");

        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Establecimiento::findOne(['COD_PTO' => $id,]);

            $ciudad_def = $model->COD_CIU;
            $can_data = Canton::findOne(['can_id' => $ciudad_def, 'can_estado' => '1', 'can_estado_logico' => '1']);
            $pro_data = Provincia::findOne(['pro_id' => $can_data->pro_id, 'pro_estado' => '1', 'pro_estado_logico' => '1']);
            //$pai_data = Pais::findOne(['pai_id' => $pro_data->pai_id, 'pai_estado' => '1', 'pai_estado_logico' => '1']);
            $pais_def = $pro_data->pai_id;
            $provincia_def = $pro_data->pro_id;

            $arr_pais = Localidad::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'C_I_OCG' => '01',])->orderBy(['NOM_OCG' => SORT_ASC])->all();
            $arr_pais = ['0' => financiero::t('localidad', '-- Select a Country --')] + ArrayHelper::map($arr_pais, "COD_OCG", "NOM_OCG");
            $arr_provincia = Localidad::getAllStatesByCity($ciudad_def);
            $arr_provincia = ['0' => financiero::t('localidad', '-- Select a State --')] + ArrayHelper::map($arr_provincia, "id", "name");
            $arr_ciudad = Localidad::getAllCitiesByState($provincia_def);
            $arr_ciudad = ['0' => financiero::t('localidad', '-- Select a City --')] + ArrayHelper::map($arr_ciudad, "id", "name");

            return $this->render('edit', [
                        'model' => $model,
                        'arr_pais' => $arr_pais,
                        'arr_provincia' => $arr_provincia,
                        'arr_ciudad' => $arr_ciudad,
                        'pais_def' => $pais_def,
                        'provincia_def' => $provincia_def,
                        'ciudad_def' => $ciudad_def,
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

        $_SESSION['JSLANG']['Please select a Country.'] = financiero::t('localidad', "Please select a Country.");
        $_SESSION['JSLANG']['Please select a State.'] = financiero::t('localidad', "Please select a State.");
        $_SESSION['JSLANG']['Please select a City.'] = financiero::t('localidad', "Please select a City.");

        $pais_def = 1;
        $provincia_def = 10;
        $ciudad_def = 87;
        $arr_pais = Localidad::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'C_I_OCG' => '01',])->orderBy(['NOM_OCG' => SORT_ASC])->all();
        $arr_pais = ['0' => financiero::t('localidad', '-- Select a Country --')] + ArrayHelper::map($arr_pais, "COD_OCG", "NOM_OCG");
        $arr_provincia = Localidad::getAllStatesByCountry($pais_def);
        $arr_provincia = ['0' => financiero::t('localidad', '-- Select a State --')] + ArrayHelper::map($arr_provincia, "id", "name");
        $arr_ciudad = Localidad::getAllCitiesByState($provincia_def);
        $arr_ciudad = ['0' => financiero::t('localidad', '-- Select a City --')] + ArrayHelper::map($arr_ciudad, "id", "name");

        return $this->render('new', [
                    'arr_pais' => $arr_pais,
                    'arr_provincia' => $arr_provincia,
                    'arr_ciudad' => $arr_ciudad,
                    'pais_def' => $pais_def,
                    'provincia_def' => $provincia_def,
                    'ciudad_def' => $ciudad_def,
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
                $codigo = $data["codigo"];
                $nombre = $data["nombre"];
                $pais = $data["pais"];
                $provincia = $data["provincia"];
                $ciudad = $data["ciudad"];
                $direccion = $data["direccion"];
                $telefono1 = $data["telefono1"];
                $telefono2 = $data["telefono2"];
                $telefonofax = $data["telefonofax"];
                $correoct = $data["correoct"];
                $fecha = $data["fecha"];
                $model = new Establecimiento();

                $model->COD_PTO = $codigo;
                $model->NOM_PTO = $nombre;
                $model->COD_PAI = $pais;
                $model->COD_EST = $provincia;
                $model->COD_CIU = $ciudad;
                $model->DIR_PTO = $direccion;
                $model->TEL_N01 = $telefono1;
                $model->TEL_N02 = $telefono2;
                $model->NUM_FAX = $telefonofax;
                $model->CORRE_E = null;
                $model->CORRE_CT = $correoct;
                $model->COD_RES = null;
                $model->REG_ASO = null;
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->FEC_PTO = $fecha;
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
                    "wtmessage" => $msg . $ex->getMessage(),
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
                $id = $data["id"];
                $nombre = $data["nombre"];
                $pais = $data["pais"];
                $provincia = $data["provincia"];
                $ciudad = $data["ciudad"];
                $direccion = $data["direccion"];
                $telefono1 = $data["telefono1"];
                $telefono2 = $data["telefono2"];
                $telefonofax = $data["telefonofax"];
                $correoct = $data["correoct"];
                $fecha = $data["fecha"];

                $model = Establecimiento::findOne(['COD_PTO' => $id,]);

                $model->NOM_PTO = $nombre;
                $model->COD_PAI = $pais;
                $model->COD_EST = $provincia;
                $model->COD_CIU = $ciudad;
                $model->DIR_PTO = $direccion;
                $model->TEL_N01 = $telefono1;
                $model->TEL_N02 = $telefono2;
                $model->NUM_FAX = $telefonofax;
                $model->CORRE_E = null;
                $model->CORRE_CT = $correoct;
                $model->COD_RES = null;
                $model->REG_ASO = null;
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->FEC_PTO = $fecha;
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
                $id = $data["id"];
                $model = Establecimiento::findOne(['COD_PTO' => $id,]);
                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = "0";
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
        $colPosition = array("C", "D", "E", "F", "G", "H", "I");
        $arrHeader = array(
            financiero::t("establecimiento", "Code"),
            financiero::t("establecimiento", "Name"),
            financiero::t("localidad", "Country"),
            financiero::t("localidad", "State"),
            financiero::t("localidad", "City"),
            financiero::t("establecimiento", "Address"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new Establecimiento();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
        }
        $nameReport = financiero::t("establecimiento", "Report Establishments");
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
        $this->view->title = financiero::t("establecimiento", "Report Establishments");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("establecimiento", "Code"),
            financiero::t("establecimiento", "Name"),
            financiero::t("localidad", "Country"),
            financiero::t("localidad", "State"),
            financiero::t("localidad", "City"),
            financiero::t("establecimiento", "Address"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new Establecimiento();
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
