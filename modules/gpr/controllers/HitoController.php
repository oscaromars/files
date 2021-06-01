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

class HitoController extends \app\components\CController {

    public function actionIndex($id) {
        $model = new Hito();
        $data = Yii::$app->request->get();
        $modelProyecto = Proyecto::findOne($id);
        $ugpr_id = $modelProyecto->ugpr_id;
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $niv_id = Nivel::getLevel($user_id, $idEmpresa, $ugpr_id);
        $isAdmin = ResponsableUnidad::userIsAdmin($user_id, $idEmpresa);
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                        "model" => $model->getAllHitoGrid($data["search"], $id, true),
                        'niv_id' => $niv_id,
                        'isAdmin' => $isAdmin,
            ]);
        }
        return $this->render('index', [
                    'model' => $model->getAllHitoGrid(NULL, $id, true),
                    'niv_id' => $niv_id,
                    'pro_id' => $id,
                    'isAdmin' => $isAdmin,
        ]);
    }

    public function actionNew($id) {
        $modelProyecto = Proyecto::findOne($id);
        return $this->render('new', [
            'model' => $modelProyecto,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                'model' => Hito::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('edit', [
                'model' => Hito::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $pro_id = $data['id'];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $fechaEstimada = $data["fechaInicio"];
                $peso = $data["peso"];
                $presupuesto = $data['presupuesto'];
                $model = new Hito();
                $model->pro_id = $pro_id;
                $model->hito_nombre = $nombre;
                $model->hito_descripcion = $descripcion;
                $model->hito_fecha_compromiso = $fechaEstimada;
                $model->hito_presupuesto = $presupuesto;
                $model->hito_peso = $peso;
                $model->hito_progreso = '0';
                $model->hito_cumplimiento_poa = '0';
                $model->hito_estado = '1';
                $model->hito_usuario_ingreso = $user_id;
                $model->hito_estado_logico = "1";

                $model_proyecto = Proyecto::findOne($pro_id);
                if($model_proyecto->pro_cerrado == 1){ // si el proyecto esta cerrado no puede ser editado los hitos.
                    $error = true;
                    throw new Exception(gpr::t("hito", "Milestone cannot be updated. Project is Closed. Please report to administrator to Open it."));
                }
                $model_proyecto->pro_cerrado = '0';
                if(!$model_proyecto->save())
                    throw new Exception('Error Registro no creado.');
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no creado.');
                }
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

    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data['id'];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $fechaEstimada = $data["fechaInicio"];
                $peso = $data["peso"];
                $presupuesto = $data['presupuesto'];
                $model = Hito::findOne($id);
                $model->hito_nombre = $nombre;
                $model->hito_descripcion = $descripcion;
                $model->hito_fecha_compromiso = $fechaEstimada;
                $model->hito_presupuesto = $presupuesto;
                $model->hito_peso = $peso;
                $model->hito_usuario_modifica = $user_id;
                $model->hito_fecha_modificacion = $fecha_modificacion;

                $model_proyecto = Proyecto::findOne($model->pro_id);

                if($model_proyecto->pro_cerrado == '1' || $model->hito_cerrado == '1'){ // si el proyecto esta cerrado no puede ser editado los hitos.
                    $error = true;
                    throw new Exception(gpr::t("hito", "Milestone cannot be updated. Project is Closed. Please report to administrator to Open it."));
                }
                if ($model->update() !== false) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no actualizado.');
                }
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

    public function actionDelete() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = Hito::findOne($id);
                $model->hito_estado_logico = '0';
                $model->hito_cerrado = '0';
                $model->hito_usuario_modifica = $user_id;
                $model->hito_fecha_modificacion = $fecha_modificacion;
                if ($model->save()) {
                    // realizar la eliminacion de los hitos_seguimientos
                    if(!HitoSeguimiento::deleteAllHitosSegByIdHito($id)){
                        throw new Exception('Error Registro no ha sido eliminado.');
                    }
                    // se debe actualizar todos los demas hitos con hito_cerrado = 0 para solicitar validar nuevamente
                    $pro_id = $model->pro_id;
                    $arrHitos = Hito::findAll(['pro_id' => $pro_id]);
                    foreach($arrHitos as $key => $value){
                        $value->hito_cerrado = '0';
                        $value->hito_fecha_modificacion = $fecha_modificacion;
                        $value->hito_usuario_modifica = $user_id;
                        if(!$value->save()){
                            throw new Exception('Error Registro no ha sido eliminado.');
                        }
                    }
                    // se debe actualizar el proyecto con pro_cerrado = 0 para solicitar validar nuevamente
                    $model_proyecto = Proyecto::findOne($pro_id);
                    $model_proyecto->pro_cerrado = '0';
                    $model_proyecto->pro_fecha_modificacion = $fecha_modificacion;
                    $model_proyecto->pro_usuario_modifica = $user_id;
                    if(!$model_proyecto->save()){
                        throw new Exception('Error Registro no ha sido eliminado.');
                    }
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no ha sido eliminado.');
                }
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

    public function actionOpenhito(){
        // El usuario admin puede permitir abrir un Hito que ya fue cerrado para ser modificado
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = Hito::findOne($id);
                $model_proyecto = Proyecto::findOne($model->pro_id);
                $ugpr_id = $model_proyecto->ugpr_id;
                //$niv_id = Nivel::getLevel($user_id, $idEmpresa, $ugpr_id);
                //if($niv_id != 1){
                if(!ResponsableUnidad::userIsAdmin($user_id, $idEmpresa)){
                    $error = true;
                    throw new Exception(gpr::t("responsablesubunidad", "User have no privileges N1 to do this transaccion."));
                }
                $model->hito_usuario_modifica = $user_id;
                $model->hito_fecha_modificacion = $fecha_modificacion;
                $model->hito_cerrado = '0';
                if($model->save()){
                    $model_proyecto->pro_cerrado = '0';
                    $model_proyecto->pro_fecha_modificacion = $fecha_modificacion;
                    $model_proyecto->pro_usuario_modifica = $user_id;
                    if($model_proyecto->save()){
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }   
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

    public function actionSaveproyeccion(){
        // el guardar proyeccion lo que hace es que valida de que todas las actividades cumplan con lo siguiente:
        // 1. que las suma de presupuesto de los hitos debe ser igual al presupuesto asignado al proyecto
        // 2. que la suma de peso de las actividades debe sumar el 100%
        // 3. debe marcar de la tabla proyecto el campo pro_cerrado = 1 cuando todo esta validado
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = Proyecto::findOne($id);
                $model->pro_usuario_modifica = $user_id;
                $model->pro_fecha_modificacion = $fecha_modificacion;
                $model->pro_cerrado = '1';
                // validacion de suma presupuesto y validacion de pesos en actividades
                $arrHitos = Hito::findAll(['pro_id' => $id, 'hito_estado' => '1', 'hito_estado_logico' => '1']);
                $sumHitosPesos = 0;
                $sumHitosPresupuesto = 0;
                foreach($arrHitos as $key => $value){
                    $sumHitosPesos += $value->hito_peso;
                    $sumHitosPresupuesto += $value->hito_presupuesto;
                }
                if(round($sumHitosPesos,2) != round(100, 2)){
                    $error = true;
                    throw new Exception(gpr::t("hito", "Please verify Weighing of Milestones. The sum must be 100%."));
                }
                if(round($sumHitosPresupuesto, 2) != round($model->pro_presupuesto, 2)){
                    $error = true;
                    throw new Exception(gpr::t("hito", "Please verify Budget of Milestones. The Sum Budget of Milestones must be equal to Project Budget."));
                }

                if($model->save()){
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }else
                    throw new Exception('Error Registro no ha sido actualizado.');
            }catch (Exception $ex) {
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

    public function actionCloseproyeccion(){
        // Esta funcion lo que hace es marcar el campo hito_cerrado = 1 de todos los hitos de un proyecto siempre que el campo pro_cerrado = 1
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = Proyecto::findOne($id);
                $model->pro_usuario_modifica = $user_id;
                $model->pro_fecha_modificacion = $fecha_modificacion;
                if($model->pro_cerrado == 0){
                    $error = true;
                    throw new Exception(gpr::t('hito', 'Milestone Estimation cannot be closed. Please save the estimation before.'));
                }
                $arrHitos = Hito::findAll(['pro_id' => $id]);
                foreach($arrHitos as $key => $value){
                    $value->hito_cerrado = '1';
                    $value->hito_fecha_modificacion = $fecha_modificacion;
                    $value->hito_usuario_modifica = $user_id;
                    if(!$value->save()){
                        throw new Exception('Error Registro no ha sido actualizado.');
                    }
                }
                $transaction->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
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
