<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\PlanificacionPedi;
use app\models\Utilities;
use app\modules\gpr\models\Entidad;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class PlanificacionpediController extends \app\components\CController {

    public function actionIndex() {
        $model = new PlanificacionPedi();
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllPlanPediGrid($data["search"], $data["entidad"], $data["cierre"], true)
            ]);
        }
        $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
        $arr_entidad = ['0' => gpr::t('entidad', '-- All Entities --')] + ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre");
        $arr_cierre = ['-1' => gpr::t('planificacionpedi', '-- All Status Pedi Planning Closed --'),'0' => gpr::t('planificacionpedi', 'Planning Opened'), '1' => gpr::t('planificacionpedi', 'Planning Closed')];
        return $this->render('index', [
                    'model' => $model->getAllPlanPediGrid(NULL, NULL, NULL, true),
                    'arr_entidad' => $arr_entidad,
                    'arr_cierre' => $arr_cierre,
        ]);
    }

    public function actionNew() {
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $_SESSION['JSLANG']['The initial date of registry cannot be greater than end date.'] = gpr::t('planificacionpedi', 'The initial date of registry cannot be greater than end date.');
        $_SESSION['JSLANG']['Please select an Entity Name.'] = gpr::t('entidad', 'Please select an Entity Name.');
        $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
        $arr_entidad = ['0' => gpr::t('entidad', '-- Select a Entity Name --')] + ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre");
        return $this->render('new',[
            'arr_entidad' => $arr_entidad,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
            $id = $data['id'];
            $arr_cierre = ['0' => gpr::t('planificacionpedi', 'Planning Opened'), '1' => gpr::t('planificacionpedi', 'Planning Closed')];
            $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
            $arr_entidad = ['0' => gpr::t('entidad', '-- Select a Entity Name --')] + ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre");
            return $this->render('view', [
                'model' => PlanificacionPedi::findOne($id),
                'arr_cierre' => $arr_cierre,
                'arr_entidad' => $arr_entidad,
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
            $_SESSION['JSLANG']['Please select an Entity Name.'] = gpr::t('entidad', 'Please select an Entity Name.');
            $id = $data['id'];
            $arr_cierre = ['0' => gpr::t('planificacionpedi', 'Planning Opened'), '1' => gpr::t('planificacionpedi', 'Planning Closed')];
            $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
            $arr_entidad = ['0' => gpr::t('entidad', '-- Select a Entity Name --')] + ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre");
            return $this->render('edit', [
                'model' => PlanificacionPedi::findOne($id),
                'arr_cierre' => $arr_cierre,
                'arr_entidad' => $arr_entidad,
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
                $entidad = $data["entidad"];
                $model = new PlanificacionPedi();
                $model->pped_nombre = $nombre;
                $model->pped_fecha_inicio =  $fini . " 00:00:00";
                $model->pped_fecha_fin = $fend . " 00:00:00";
                $model->pped_fecha_actualizacion = $fecha_modificacion;
                $model->pped_descripcion = $descripcion;
                $model->ent_id = $entidad;
                $model->pped_estado = $estado;
                $model->pped_usuario_ingreso = $user_id;
                $model->pped_estado_logico = "1";
                $model->pped_estado_cierre = "0";
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
                $entidad = $data["entidad"];
                $cierre = $data["cierre"];
                $model = PlanificacionPedi::findOne($id);
                $model->pped_nombre = $nombre;
                $model->pped_descripcion = $descripcion;
                $model->pped_fecha_inicio =  $fini . " 00:00:00";
                $model->pped_fecha_fin = $fend . " 00:00:00";
                $model->pped_usuario_modifica = $user_id;
                $model->ent_id = $entidad;
                $model->pped_fecha_modificacion = $fecha_modificacion;
                $model->pped_estado = $estado;
                $model->pped_estado_cierre = $cierre;
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
                $model = PlanificacionPedi::findOne($id);
                $model->enf_estado_logico = '0';
                $model->enf_usuario_modifica = $user_id;
                $model->enf_fecha_modificacion = $fecha_modificacion;
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