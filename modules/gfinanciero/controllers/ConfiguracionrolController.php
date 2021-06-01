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
use app\modules\gfinanciero\models\ConfiguracionRol;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class ConfiguracionrolController extends CController {
    
    /**
     * Edit Action. Allow edit the information from View Action.
     *
     * @return void
     */
    public function actionEdit() {
        $data = Yii::$app->request->get();
        $_SESSION['JSLANG']['Please Hours must be greater than zero.'] = financiero::t('configuracionrol',"Please Hours must be greater than zero.");
        $_SESSION['JSLANG']['Please Salary must be greater than zero.'] = financiero::t('configuracionrol',"Please Salary must be greater than zero.");
        $_SESSION['JSLANG']['Please Transport must be greater or equal than zero.'] = financiero::t('configuracionrol',"Please Transport must be greater or equal than zero.");
        $_SESSION['JSLANG']['Please Feeding must be greater or equal than zero.'] = financiero::t('configuracionrol',"Please Feeding must be greater or equal than zero.");
        $_SESSION['JSLANG']['Please Percentage must be greater than 0 and less than 100.'] = financiero::t('configuracionrol',"Please Percentage must be greater than 0 and less than 100.");
        $model = ConfiguracionRol::findOne('1');
        return $this->render('edit', [
            'model' => $model,
        ]);
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
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $salario_min  = $data["salario_min"];
                $horas = $data["horas"];
                $beneficios = $data["beneficios"];
                $per_aporte = $data["per_aporte"];
                $aporte_mes = $data["aporte_mes"];
                $per_iess = $data["per_iess"];
                $iess_mes = $data["iess_mes"];
                $alimentacion = $data["alimentacion"];
                $alimentacion_mes = $data["alimentacion_mes"];
                $transporte = $data["transporte"];
                $transporte_mes = $data["transporte_mes"];

                $model = ConfiguracionRol::findOne(['crol_id' => '1', 'crol_estado' => '1', 'crol_estado_logico' => '1']);
                $model->crol_salario_minimo = $salario_min;
                $model->crol_porcentaje_aporte_patronal = $per_aporte;
                $model->crol_aporte_mensual_quincena = $aporte_mes;
                $model->crol_porcentaje_iess = $per_iess;
                $model->crol_iess_mensual_quincena = $iess_mes;
                $model->crol_horas_trabajo = $horas;
                $model->crol_paga_benenficios = $beneficios;
                $model->crol_transporte = $transporte;
                $model->crol_transp_mensual_quincena = $transporte_mes;
                $model->crol_alimentacion = $alimentacion;
                $model->crol_alimen_mensul_quincena = $alimentacion_mes;

                $model->crol_usuario_modifica = $usu_id;
                $model->crol_fecha_modificacion = date('Y-m-d H:i:s');
                $model->crol_estado = "1";
                $model->crol_estado_logico = "1";
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
        
}