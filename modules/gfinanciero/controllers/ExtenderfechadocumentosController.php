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
use app\modules\gfinanciero\models\TipoDocumento;
use app\modules\gfinanciero\models\Establecimiento;
use app\modules\gfinanciero\models\Emision;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class ExtenderfechadocumentosController extends CController {
 
    
     /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $_SESSION['JSLANG']['Are_you_sure_you_want_to_update_the_documents_'] = financiero::t('tipodocumento',"Are you sure you want to update the documents.");
        $_SESSION['JSLANG']['Select_an_item_to_process_the_request_'] = financiero::t('tipodocumento',"Select an item to process the request.");
        $model = new TipoDocumento();
        $data = Yii::$app->request->post();      

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

        $arr_establecimiento = Establecimiento::getTypeEstablecimiento();
        $establecimiento = "001";//Yii::$app->session->get("PB_p_establecimiento"); // establecimiento        
        //$emision = Yii::$app->session->get("PB_p_emision"); // punto emision
        $arr_establecimiento = array_merge(['0' => financiero::t('establecimiento', '-- All of Establishments --')], ArrayHelper::map($arr_establecimiento, "id", "name"));
        $list_emision = Emision::find()->
                select(["COD_CAJ AS id", "CONCAT(COD_CAJ,' - ',NOM_CAJ) AS name"])
                ->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'COD_PTO' => $establecimiento])
                ->asArray()->all();
        $arr_emision = array_merge(ArrayHelper::map($list_emision, "id", "name"));
        
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {            
            return $this->render('index', [
                        "model" => $model->getAllItemsGridFechas($data["search"],$establecimiento,$data['type_emi'], true,true),
                        'arr_establecimiento' => $arr_establecimiento,
                        'arr_emision' => $arr_emision,
            ]);
        }

        return $this->render('index', [
                    "model" => $model->getAllItemsGridFechas($data["search"], $establecimiento, null, true,true),
                    'arr_establecimiento' => $arr_establecimiento,
                    'cod_est' =>$establecimiento,
                    'arr_emision' => $arr_emision,
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
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $codpto = "001";//Yii::$app->session->get("PB_p_establecimiento"); // establecimiento  
                $ids = isset($_POST['ids']) ? base64_decode($_POST['ids']) : NULL;
                $fechadocumento = isset($_POST['fecha']) ? $_POST['fecha'] : NULL;
                $ArrayIds = explode(",", $ids);//convierte en Array
                for($i = 0; $i < count($ArrayIds); $i++){
                    $ArrayDoc = explode("-", $ArrayIds[$i]);//Separa los Documentos
                    $codcaja= $ArrayDoc[0];
                    $tipnof=$ArrayDoc[1];
                    $model = TipoDocumento::findOne(['COD_PTO' => $codpto, 'COD_CAJ' => $codcaja, 'TIP_NOF' => $tipnof,'DOC_AUT' => 1]);
                    $model->FEC_AUT = $fechadocumento;
                    $model->FEC_SIS = date('Y-m-d');
                    $model->HOR_SIS = date('H:i:s');
                    $model->USUARIO = $username;
                    $model->EQUIPO = Utilities::getClientRealIP();
                    $model->update();                    
                }

                $trans->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
               
            } catch (\Exception $ex) {
                //Utilities::putMessageLogFile($ex->getMessage());
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
        //return $this->redirect('index');
    }
        
    
   

   

     
}