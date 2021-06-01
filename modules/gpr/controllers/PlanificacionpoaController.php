<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\PlanificacionPoa;
use app\models\Utilities;
use app\modules\gpr\models\Entidad;
use app\modules\gpr\models\PlanificacionPedi;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class PlanificacionpoaController extends \app\components\CController {

    public function actionIndex() {
        $model = new PlanificacionPoa();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllPlanPoaGrid($data["search"], $data["planpedi"], $data["cierre"], true)
            ]);
        }
        $entidad =  Entidad::findOne(['emp_id' => $emp_id, 'ent_estado' => '1', 'ent_estado_logico' => '1']);
        $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id]);
        $arr_plan = ['0' => gpr::t('planificacionpedi', '-- All Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
        $arr_cierre = ['-1' => gpr::t('planificacionpoa', '-- All Status Poa Planning Closed --'),'0' => gpr::t('planificacionpoa', 'Planning Opened'), '1' => gpr::t('planificacionpoa', 'Planning Closed')];
        return $this->render('index', [
            'model' => $model->getAllPlanPoaGrid(NULL, NULL, NULL, true),
            'arr_plan' => $arr_plan,
            'arr_cierre' => $arr_cierre,
        ]);
    }

    public function actionNew() {
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $_SESSION['JSLANG']['The initial date of registry cannot be greater than end date.'] = gpr::t('planificacionpedi', 'The initial date of registry cannot be greater than end date.');
        $_SESSION['JSLANG']['Please select a Pedi Planning.'] = gpr::t('planificacionpedi', 'Please select a Pedi Planning.');
        $entidad =  Entidad::findOne(['emp_id' => $emp_id, 'ent_estado' => '1', 'ent_estado_logico' => '1']);
        $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id, 'pped_estado_cierre' => '0']);
        $arr_plan = ['0' => gpr::t('planificacionpedi', '-- Select a Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
        return $this->render('new',[
            'arr_plan' => $arr_plan,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
            $id = $data['id'];
            $arr_cierre = ['0' => gpr::t('planificacionpedi', 'Planning Opened'), '1' => gpr::t('planificacionpedi', 'Planning Closed')];
            $entidad =  Entidad::findOne(['emp_id' => $emp_id, 'ent_estado' => '1', 'ent_estado_logico' => '1']);
            $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id, 'pped_estado_cierre' => '0']);
            $arr_plan = ['0' => gpr::t('planificacionpedi', '-- Select a Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
            return $this->render('view', [
                'model' => PlanificacionPoa::findOne($id),
                'arr_cierre' => $arr_cierre,
                'arr_plan' => $arr_plan,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
            $_SESSION['JSLANG']['The initial date of registry cannot be greater than end date.'] = gpr::t('planificacionpedi', 'The initial date of registry cannot be greater than end date.');
            $_SESSION['JSLANG']['Please select a Pedi Planning.'] = gpr::t('planificacionpedi', 'Please select a Pedi Planning.');
            $id = $data['id'];
            $arr_cierre = ['0' => gpr::t('planificacionpedi', 'Planning Opened'), '1' => gpr::t('planificacionpedi', 'Planning Closed')];
            $entidad =  Entidad::findOne(['emp_id' => $emp_id, 'ent_estado' => '1', 'ent_estado_logico' => '1']);
            $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id, 'pped_estado_cierre' => '0']);
            $arr_plan = ['0' => gpr::t('planificacionpedi', '-- Select a Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
            return $this->render('edit', [
                'model' => PlanificacionPoa::findOne($id),
                'arr_cierre' => $arr_cierre,
                'arr_plan' => $arr_plan,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $fini = $data["fini"];
                $fend = $data["fend"];
                $plan = $data["plan"];
                $model = new PlanificacionPoa();
                $model->ppoa_nombre = $nombre;
                $model->ppoa_fecha_inicio =  $fini . " 00:00:00";
                $model->ppoa_fecha_fin = $fend . " 00:00:00";
                $model->ppoa_fecha_actualizacion = $fecha_modificacion;
                $model->ppoa_descripcion = $descripcion;
                $model->pped_id = $plan;
                $model->ppoa_estado = $estado;
                $model->ppoa_usuario_ingreso = $user_id;
                $model->ppoa_estado_logico = "1";
                $model->ppoa_estado_cierre = "0";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no creado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
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
            try {
                $id = $data["id"];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $fini = $data["fini"];
                $fend = $data["fend"];
                $plan = $data["plan"];
                $cierre = $data["cierre"];
                $model = PlanificacionPoa::findOne($id);
                $model->ppoa_nombre = $nombre;
                $model->ppoa_descripcion = $descripcion;
                $model->ppoa_fecha_inicio =  $fini . " 00:00:00";
                $model->ppoa_fecha_fin = $fend . " 00:00:00";
                $model->ppoa_usuario_modifica = $user_id;
                $model->pped_id = $plan;
                $model->ppoa_fecha_modificacion = $fecha_modificacion;
                $model->ppoa_estado = $estado;
                $model->ppoa_estado_cierre = $cierre;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no actualizado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
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
            try {
                $id = $data["id"];
                $model = PlanificacionPoa::findOne($id);
                $model->ppoa_estado_logico = '0';
                $model->ppoa_usuario_modifica = $user_id;
                $model->ppoa_fecha_modificacion = $fecha_modificacion;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no ha sido eliminado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
}