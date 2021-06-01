<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\Hito;
use app\models\Utilities;
use app\modules\gpr\models\HitoSeguimiento;
use app\modules\gpr\models\Nivel;
use app\modules\gpr\models\Proyecto;
use app\modules\gpr\models\ResponsableUnidad;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class HitoresultadoController extends \app\components\CController {

    public function actionView(){
        // se busca el registro que tenga como hito_id si no existe se crea el registro
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $model_hito = Hito::findOne($id);
            $error = false;
            $model_proyecto = Proyecto::findOne($model_hito->pro_id);
            $model = HitoSeguimiento::findOne(['hito_id' => $id, 'hseg_estado' => '1', 'hseg_estado_logico' => '1']);
            if($model_proyecto->pro_cerrado == 0){ // si el proyecto no esta cerrado no puede ser editado los hitos seguimiento.
                $error = true;
                Yii::$app->session->setFlash('error',"<h4>".Yii::t('notificaciones', 'Error')."</h4>".gpr::t("hito", "Result Milestone cannot be updated. Project is Opened. Please Save and Closed Milestones."));
            }
            if(!$model){
                $model = new HitoSeguimiento();
                $model->hito_id = $id;
                $model->hseg_nombre = $model_hito->hito_nombre;
                $model->hseg_descripcion = $model_hito->hito_descripcion;
                $model->hseg_fecha_compromiso = $model_hito->hito_fecha_compromiso;
                $model->hseg_presupuesto = '0';
                $model->hseg_cumplido = '0';
                $model->hseg_peso = $model_hito->hito_peso;
                $model->hseg_progreso = '0';
                $model->hseg_cumplimiento_poa = $model_hito->hito_cumplimiento_poa;
                $model->hseg_cerrado = '0';
                $model->hseg_usuario_ingreso = $user_id;
                $model->hseg_estado = '1';
                $model->hseg_estado_logico = '1';
                if(!$model->save())
                    Yii::$app->session->setFlash('error',"<h4>".Yii::t('notificaciones', 'Error')."</h4>".gpr::t("meta","Results cannot be showed. Report to Administrator."));
            }
            $arr_cumplimiento = array("0" => gpr::t("hito", "No"), "1" => gpr::t("hito", "Yes"));
            return $this->render('view', [
                'model' => $model,
                'error' => $error,
                'modelHito' => $model_hito,
                'arr_cumplimiento' => $arr_cumplimiento,
            ]);
        }
    }

    public function actionEdit(){
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $_SESSION['JSLANG']['Please Advance must be more than zero.'] = gpr::t('hito', 'Please Advance must be more than zero.');
            $id = $data['id'];
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $model_hito = Hito::findOne($id);
            $error = false;
            $model_proyecto = Proyecto::findOne($model_hito->pro_id);
            $model = HitoSeguimiento::findOne(['hito_id' => $id, 'hseg_estado' => '1', 'hseg_estado_logico' => '1']);
            if($model_proyecto->pro_cerrado == 0){ // si el proyecto no esta cerrado no puede ser editado los hitos seguimiento.
                $error = true;
                Yii::$app->session->setFlash('error',"<h4>".Yii::t('notificaciones', 'Error')."</h4>".gpr::t("hito", "Result Milestone cannot be updated. Project is Opened. Please Save and Closed Milestones."));
            }
            $arr_cumplimiento = array("0" => gpr::t("hito", "No"), "1" => gpr::t("hito", "Yes"));
            return $this->render('edit', [
                'model' => $model,
                'error' => $error,
                'modelHito' => $model_hito,
                'arr_cumplimiento' => $arr_cumplimiento,
            ]);
        }
    }

    public function actionSave(){
        // cuando se guarda el hito seguimiento se debe cambiar a hseg_cerrado = 1 y actualizar en hito los valores
        // se debe actualizar avance en el hito cada vez q se guarde el hitoseguimiento
        // se debe calcular el valor de avance de proyecto entre peso y el progreso 
        if (Yii::$app->request->isAjax) {
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try{
                $id = $data['id'];
                $fechaReal = $data['fecha'];
                $presupuesto = $data['gasto'];
                $progreso = $data['avance'];
                $cumplido = $data['cumplido'];
                $model = HitoSeguimiento::findOne($id);
                if($model->hseg_cerrado == '1'){
                    $error = true;
                    throw new Exception(gpr::t("hito", "Result Milestone cannot be updated. Result Milestone is Closed. Please report to administrator to Open it."));
                }
                $modelHito = Hito::findOne($model->hito_id);
                $model->hseg_fecha_real = $fechaReal;
                $model->hseg_progreso = $progreso;
                $model->hseg_presupuesto = $presupuesto;
                $model->hseg_cumplido = $cumplido;
                $model->hseg_cerrado = '0';
                $model->hseg_usuario_modifica = $user_id;
                $model->hseg_fecha_modificacion = $fecha_modificacion;
                if($cumplido == 1)  $model->hseg_cerrado = '1';
                if($model->save()){
                    // calculo del avance
                    $peso = $modelHito->hito_peso;
                    $progreso_hito = ($progreso * $peso) / 100;
                    $modelHito->hito_progreso = "".round($progreso_hito,2);
                    $modelHito->hito_cumplido = $cumplido;
                    if($modelHito->save()){
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }
                }
                throw new Exception('Error Registro no actualizado.');
            }catch(Exception $ex){
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionOpenresultado(){
        // solo cambia el estado de hseg_cerrado = 0 
        // solo lo puede hacer usuarios N1
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = HitoSeguimiento::findOne($id);
                $modelHito = Hito::findOne($model->hito_id);
                $model_proyecto = Proyecto::findOne($modelHito->pro_id);
                $ugpr_id = $model_proyecto->ugpr_id;
                $niv_id = Nivel::getLevel($user_id, $idEmpresa, $ugpr_id);
                //if($niv_id != 1){
                if(!ResponsableUnidad::userIsAdmin($user_id, $idEmpresa)){
                    $error = true;
                    throw new Exception(gpr::t("responsablesubunidad", "User have no privileges N1 to do this transaccion."));
                }
                $model->hseg_usuario_modifica = $user_id;
                $model->hseg_fecha_modificacion = $fecha_modificacion;
                $model->hseg_cerrado = '0';
                if($model->save()){
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }
                throw new Exception('Error Registro no ha sido actualizado.');
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
}