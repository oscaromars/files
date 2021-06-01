<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\models\Utilities;
use app\models\Empresa;
use app\modules\gpr\models\ComportamientoIndicador;
use app\modules\gpr\models\FrecuenciaIndicador;
use app\modules\gpr\models\Indicador;
use app\modules\gpr\models\MetaIndicador;
use app\modules\gpr\models\Nivel;
use app\modules\gpr\models\PlanificacionPoa;
use app\modules\gpr\models\ResponsableUnidad;
use app\modules\gpr\models\TipoMeta;
use app\modules\gpr\models\Umbral;
use app\modules\gpr\models\UnidadMedida;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;

gpr::registerTranslations();

class MetaController extends \app\components\CController {

    public function actionIndex($id) {
        $indicador = Indicador::findOne($id);
        $data = Yii::$app->request->get();
        $tipoMeta = TipoMeta::findOne($indicador->tmet_id);
        $comportamiento = ComportamientoIndicador::findOne($indicador->cind_id);
        $unidadMedida = UnidadMedida::findOne($indicador->umed_id);
        $modelUmbrales = Umbral::find(['umb_estado' => '1', 'umb_estado_logico' => '1'])->asArray()->all();
        $ugpr_id = $indicador->ugpr_id;
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $niv_id = Nivel::getLevel($user_id, $idEmpresa, $ugpr_id);
        /*if (isset($data["PBgetFilter"])) {
            $arr_data = MetaIndicador::getAllIndicadorGrid($id);
            return $this->renderPartial('index', [
                'model' => new ArrayDataProvider($arr_data),
                'ind_fraccional' => $indicador->ind_fraccional,
                'indicador' => $indicador,
                'tipometa' => $tipoMeta->tmet_nombre,
                'comportamiento' => $comportamiento->cind_nombre,
                'modelUmbrales' => $modelUmbrales,
                'niv_id' => $niv_id,
            ]);
        }*/
        $_SESSION['JSLANG']['Edit Goal'] = gpr::t('meta', 'Edit Goal');
        $_SESSION['JSLANG']['Period'] = gpr::t('meta', 'Period');
        $_SESSION['JSLANG']['Period Goal'] = gpr::t('meta', 'Period Goal');
        $_SESSION['JSLANG']['Numerator'] = gpr::t('meta', 'Numerator');
        $_SESSION['JSLANG']['Denominator'] = gpr::t('meta', 'Denominator');
        $_SESSION['JSLANG']['Result'] = gpr::t('meta', 'Result');
        $_SESSION['JSLANG']['Advance Period'] = gpr::t('meta', 'Advance Period');
        $model = MetaIndicador::findOne(['ind_id' => $id, 'mind_estado' => '1', 'mind_estado_logico' => '1']);
        if(!$model){
            if(!MetaIndicador::inicializarMeta($id))
                Yii::$app->session->setFlash('error',"<h4>".Yii::t('notificaciones', 'Error')."</h4>".gpr::t("meta","Goals cannot be showed. Report to Administrator."));
        }
        $arr_data = MetaIndicador::getAllIndicadorGrid($id);
        $isAdmin = ResponsableUnidad::userIsAdmin($user_id, $idEmpresa);
        return $this->render('index', [
            'model' => new ArrayDataProvider($arr_data),
            'ind_fraccional' => $indicador->ind_fraccional,
            'indicador' => $indicador,
            'tipometa' => $tipoMeta->tmet_nombre,
            'comportamiento' => $comportamiento->cind_nombre,
            'unidadmedida' => $unidadMedida->umed_nombre,
            'modelUmbrales' => $modelUmbrales,
            'niv_id' => $niv_id,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $meta = $data["meta"];
                $resultado = $data["resultado"];
                $avance = $data["avance"];
                $numerador = (isset($data['numerador']) && $data['numerador'] != "")?$data['numerador']:NULL;
                $denominador = (isset($data['denominador']) && $data['denominador'] != "")?$data['denominador']:NULL;

                $model = MetaIndicador::findOne($id);
                $model->mind_meta = $meta;
                $model->mind_denominador = $denominador;
                $model->mind_numerador = $numerador;
                $model->mind_avance = $avance;
                $model->mind_resultado = $resultado;
                $model->mind_usuario_modifica = $user_id;
                $model->mind_fecha_modificacion = $fecha_modificacion;
                $model->mind_fecha_inicio = $fecha_modificacion;
                //$model->mind_meta_cerrada = "";
                //$model->mind_fecha_inicio = "";
                //$model->mind_usuario_modifica = "";
                //$model->mind_fecha_modificacion = "";

                $indicador = Indicador::findOne($model->ind_id);
                $planCierre = PlanificacionPoa::getCierrePoaPedi($indicador->oope_id);
                if($planCierre['pedi_cierre'] == '1' || $planCierre['poa_cierre'] == '1'){
                    $error = true;
                    throw new Exception(gpr::t('planificacionpoa', 'Transaction is invalid. Planning Pedi/Poa is closed.'));
                }

                if ($model->save()) {
                    $indicador->ind_meta = '0';
                    $indicador->ind_usuario_modifica = $user_id;
                    $indicador->ind_fecha_modificacion = $fecha_modificacion;
                    if($indicador->save()){
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }
                }
                throw new Exception('Error Registro no actualizado.');
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionOpenmeta(){
        // se recibe el id de la meta y se debe actualizar el registro MetaIndicador en mind_meta_cerrada=0
        // cuando se abre la meta se cambia el valor de ind_meta a 0 para que cargue nuevamente la meta
        if (Yii::$app->request->isAjax) {
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            $data = Yii::$app->request->post();
            $mind_id = $data['id'];
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try{
                if(!ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
                    $error = true;
                    throw new Exception(gpr::t("responsablesubunidad", "User have no privileges N1 to do this transaccion."));
                }
                $meta = MetaIndicador::findOne($mind_id);
                $meta->mind_meta_cerrada = '0';
                $indicador = Indicador::findOne($meta->ind_id);

                $planCierre = PlanificacionPoa::getCierrePoaPedi($indicador->oope_id);
                if($planCierre['pedi_cierre'] == '1' || $planCierre['poa_cierre'] == '1'){
                    $error = true;
                    throw new Exception(gpr::t('planificacionpoa', 'Transaction is invalid. Planning Pedi/Poa is closed.'));
                }
                if($meta->save()){
                    $indicador->ind_meta = '0';
                    $indicador->ind_usuario_modifica = $user_id;
                    $indicador->ind_fecha_modificacion = $fecha_modificacion;
                    if(!$indicador->save()){
                        throw new Exception('Error Registro no actualizado.');
                    }
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }else{
                    throw new Exception('Error Registro no actualizado.');
                }
            }catch(Exception $e){
                $transaction->rollback();
                $msg = ($error)?($e->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.') . " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionSaveproyeccion(){
        // se hace el proceso de validacion segun si es continuo/acumulado o discreto/periodo

        // validacion (Meta) continuo/acumulado fraccional (porcentaje): 
        /**
         * No puede haber mas de un item con un valor de 100
         * Siempre el ultimo item debe tener un valor de 100
         * Cada item debe ser mayor al anterior
         */
        // validacion (Meta) discreto/periodo fraccional (porcentaje): 
        /**
         * No puede haber mas de un item con un valor de 100
         * Siempre la suma de todos los items debe ser 100
         */
        // validacion (Meta) continuo/acumulado no fraccional (numero): 
        /**
         * Cada item debe ser mayor al anterior
         */
        // validacion (Meta) discreto/periodo no fraccional (numero): 
        /**
         * Cada item debe ser mayor al anterior
         */
        // se debe validar que haya registros en cada uno de los periodos
        // accion que realiza es cambiar el parametro meta del indicador a 1 con eso se determina que la proyeccion de meta ha sido cargada 
        if (Yii::$app->request->isAjax) {
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $data = Yii::$app->request->post();
            $ind_id = $data['id'];
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try{
                $indicador = Indicador::findOne($ind_id);
                $planCierre = PlanificacionPoa::getCierrePoaPedi($indicador->oope_id);
                if($planCierre['pedi_cierre'] == '1' || $planCierre['poa_cierre'] == '1'){
                    $error = true;
                    throw new Exception(gpr::t('planificacionpoa', 'Transaction is invalid. Planning Pedi/Poa is closed.'));
                }
                $indicador->ind_meta = '1';
                if(!$indicador->save()){
                    throw new Exception('Error Registro no actualizado.');
                }
                $transaction->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }catch(Exception $e){
                $transaction->rollback();
                $msg = ($error)?($e->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.')." ".$msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionCloseproyeccion(){
        if (Yii::$app->request->isAjax) { // accion que realiza es cambiar el parametro meta_cerrada de las metas para indicar que estan cerradas 
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $data = Yii::$app->request->post();
            $ind_id = $data['id'];
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try{
                $indicador = Indicador::findOne($ind_id);
                $planCierre = PlanificacionPoa::getCierrePoaPedi($indicador->oope_id);
                if($planCierre['pedi_cierre'] == '1' || $planCierre['poa_cierre'] == '1'){
                    $error = true;
                    throw new Exception(gpr::t('planificacionpoa', 'Transaction is invalid. Planning Pedi/Poa is closed.'));
                }
                if($indicador->ind_meta == 0){
                    $error = true;
                    throw new Exception(gpr::t('meta', 'Proyection cannot be closed. Please save the proyection before.'));
                }
                $metas = MetaIndicador::findAll(['mind_estado' => '1', 'mind_estado_logico' => '1', 'ind_id' => $ind_id]);
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                foreach($metas as $meta){
                    $meta->mind_meta_cerrada = '1'; // se marca todas la metas como cerradas
                    $meta->mind_fecha_fin = $fecha_modificacion;
                    $meta->mind_fecha_modificacion = $fecha_modificacion;
                    if(!$meta->save()){
                        throw new Exception('Error Registro no actualizado.');
                    }
                }
                $transaction->commit();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }catch(Exception $e){
                $transaction->rollback();
                $msg = ($error)?($e->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.')." ".$msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

}