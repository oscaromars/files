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
use app\models\EmpresaPersona;
use app\models\ExportFile;
use app\models\UsuaGrolEper;
use app\models\Usuario;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\models\Empleado;
use app\modules\gfinanciero\models\NovedadesMultas;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;
use yii\helpers\Url;

financiero::registerTranslations();

class NovedadesmultasController extends CController {
 
    /**
     * Validation if PB_p_establecimiento and PB_p_emision are in session
     *
     * @return void
     */
    /*public function beforeAction($action){
        if (parent::beforeAction($action)) {
            $pEstablecimiento = Yii::$app->session->get("PB_p_establecimiento");
            $pEmision = Yii::$app->session->get("PB_p_emision");
            Url::remember();
            if($pEstablecimiento == "" && $pEmision == ""){
                //$url = Url::previous();
                $this->redirect(Url::to(['/'.Yii::$app->controller->module->id.'/puntoemision/setpunto']));
            }
            return true;
        }
        return false;
    }*/

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new NovedadesMultas();
        $data = Yii::$app->request->post();
        if($data['ls_query_id'] == "autocomplete-empleado"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Empleado::getDataColumnsQueryWidget();
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
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $con = Yii::$app->db_gfinanciero;
            $model = NovedadesMultas::findOne(['nmul_id' => $id,]);
            $arr_estados = NovedadesMultas::getStatusValues();
            $modelEmpleado = Empleado::findOne(['empl_codigo' => $model->empl_codigo, ]);
            $nombres = $modelEmpleado->empl_apellido . " " . $modelEmpleado->empl_nombre;
            $modelUsuario = Usuario::findOne(['usu_id' => $model->nmul_usuario_autoriza, ]);
            $modelEmpleadoAut = Empleado::findOne(['per_id' => $modelUsuario->per_id,]);

            $arr_Empleados = Empleado::find()->select(["empl_codigo AS id", "CONCAT(empl_nombre, SPACE(1), empl_apellido, ' - ', d.dep_nombre) AS value"])
                                        ->join('INNER JOIN', $con->dbname . '.sub_departamento s', 's.sdep_id = '. $con->dbname .'.empleado.sdep_id')
                                        ->join('INNER JOIN', $con->dbname . '.departamentos d', 'd.dep_id = s.dep_id')
                                        ->where(["empl_estado_logico" => "1", "empl_estado" => "1", ])->asArray()->all();
                                        
            return $this->render('view', [
                'model' => $model,
                'arr_estados' => $arr_estados,
                'code' => $model->empl_codigo,
                'nombres' => $nombres,
                'arr_Empleados' => ArrayHelper::map($arr_Empleados, "id", "value"),
                'autoriza' => (isset($modelEmpleadoAut->empl_codigo)?$modelEmpleadoAut->empl_codigo:0),
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
            $_SESSION['JSLANG']["Value must be greater than zero."] = financiero::t('novedadesmultas', "Value must be greater than zero.");
            $id = $data['id'];
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $con = Yii::$app->db_gfinanciero;
            $model = NovedadesMultas::findOne(['nmul_id' => $id,]);
            $arr_estados = NovedadesMultas::getStatusValues();
            $modelEmpleado = Empleado::findOne(['empl_codigo' => $model->empl_codigo, ]);
            $nombres = $modelEmpleado->empl_apellido . " " . $modelEmpleado->empl_nombre;
            $modelUsuario = Usuario::findOne(['usu_id' => $model->nmul_usuario_autoriza, ]);
            $modelEmpleadoAut = Empleado::findOne(['per_id' => $modelUsuario->per_id,]);

            $arr_Empleados = Empleado::find()->select(["empl_codigo AS id", "CONCAT(empl_nombre, SPACE(1), empl_apellido, ' - ', d.dep_nombre) AS value"])
                                        ->join('INNER JOIN', $con->dbname . '.sub_departamento s', 's.sdep_id = '. $con->dbname .'.empleado.sdep_id')
                                        ->join('INNER JOIN', $con->dbname . '.departamentos d', 'd.dep_id = s.dep_id')
                                        ->where(["empl_estado_logico" => "1", "empl_estado" => "1", ])->asArray()->all();
            
            return $this->render('edit', [
                'model' => $model,
                'arr_estados' => $arr_estados,
                'code' => $model->empl_codigo,
                'nombres' => $nombres,
                'arr_Empleados' => ArrayHelper::map($arr_Empleados, "id", "value"),
                'autoriza' => (isset($modelEmpleadoAut->empl_codigo)?$modelEmpleadoAut->empl_codigo:0),
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
        $_SESSION['JSLANG']["Value must be greater than zero."] = financiero::t('novedadesmultas', "Value must be greater than zero.");
        return $this->render('new', [
            //'new_id' => $new_id,
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
                $code = $data["code"];
                $valor = $data["valor"];
                $observacion = $data['observacion'];

                $model = new NovedadesMultas();
                $model->empl_codigo = $code;
                $model->nmul_valor = $valor;
                $model->nmul_observacion = $observacion;
                $model->nmul_fecha_creacion = date('Y-m-d H:i:s');
                $model->nmul_usuario_ingreso = $usu_id;
                $model->nmul_fecha_pago = NULL;
                $model->nmul_usuario_autoriza = NULL;
                $model->nmul_estado_cancelado = '0';
                $model->nmul_estado = "1";
                $model->nmul_estado_logico = "1";
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
                $code = $data["code"];
                $valor = $data["valor"];
                $observacion = $data['observacion'];
                $fpago = $data['fpago'];
                $estado = $data['estado'];
                $autoriza = $data['autoriza'];

                $modelEmpleado = Empleado::findOne(['empl_codigo' => $autoriza, ]);
                $modelEPer = EmpresaPersona::findOne(['per_id' => $modelEmpleado->per_id, 'emp_id' => $emp_id]);
                $modelUGrol = UsuaGrolEper::findOne(['eper_id' => $modelEPer->eper_id, ]);

                $model = NovedadesMultas::findOne(['nmul_id' => $id,]);
                $model->nmul_valor = $valor;
                $model->nmul_observacion = $observacion;
                $model->nmul_fecha_modificacion = date('Y-m-d H:i:s');
                $model->nmul_usuario_modifica = $usu_id;
                $model->nmul_estado_cancelado = $estado;
                $model->nmul_fecha_pago = ($estado == "1")?$fpago:NULL;
                $model->nmul_usuario_autoriza = ($estado == "1")?($modelUGrol->usu_id):NULL;

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
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = $data["id"];
                $model = NovedadesMultas::findOne(['nmul_id' => $id,]);
                $model->nmul_fecha_modificacion = date('Y-m-d H:i:s');
                $model->nmul_usuario_modifica = $usu_id;
                $model->nmul_estado = '0';
                $model->nmul_estado_logico = '0';
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
        $colPosition = array("C", "D", "E", "F", "G", "H", "I");
        $arrHeader = array(
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            financiero::t("empleado", "Department"),
            financiero::t("empleado", "SubDepartment"),
            financiero::t("novedadesmultas", "Penalty"),
            financiero::t("novedadesmultas", "Payment Date"),
            financiero::t("novedadesmultas", "Status"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new NovedadesMultas();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
        }
        $nameReport = financiero::t("novedadesmultas", "Report Penalty Items");
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
        $this->view->title = financiero::t("novedadesmultas", "Report Penalty Items");  // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            financiero::t("empleado", "Department"),
            financiero::t("empleado", "SubDepartment"),
            financiero::t("novedadesmultas", "Penalty"),
            financiero::t("novedadesmultas", "Payment Date"),
            financiero::t("novedadesmultas", "Status"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new NovedadesMultas();
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