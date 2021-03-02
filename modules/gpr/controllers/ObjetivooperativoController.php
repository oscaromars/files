<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\ObjetivoEstrategico;
use app\models\Utilities;
use app\modules\gpr\models\ObjetivoEspecifico;
use app\modules\gpr\models\ObjetivoOperativo;
use app\modules\gpr\models\PlanificacionPedi;
use app\modules\gpr\models\PlanificacionPoa;
use app\modules\gpr\models\Entidad;
use app\modules\gpr\models\SubunidadGpr;
use app\modules\gpr\models\UnidadGpr;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class ObjetivooperativoController extends \app\components\CController {

    public function actionIndex() {
        $model = new ObjetivoOperativo();
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllObjOpeGrid($data["search"], $data["objetivo"], $data["plan"], true)
            ]);
        }
        $arr_objesp = ObjetivoEspecifico::getArrayObjEspecifico();
        $arr_objesp = ['0' => gpr::t('objetivoespecifico', '-- All Specific Objective --')] + ArrayHelper::map($arr_objesp, "id", "name");
        $arr_plan = PlanificacionPoa::findAll(['ppoa_estado' => '1', 'ppoa_estado_logico' => '1']);
        $arr_plan = ['0' => gpr::t('planificacionpoa', '-- All Poa Planning --')] + ArrayHelper::map($arr_plan, "ppoa_id", "ppoa_nombre");
        return $this->render('index', [
            'model' => $model->getAllObjOpeGrid(NULL, NULL, NULL, true),
            'arr_objesp' => $arr_objesp,
            'arr_plan' => $arr_plan,
        ]);
    }

    public function actionNew() {
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        
        $arr_objesp = ObjetivoEspecifico::getArrayObjEspecifico();
        $arr_objesp = ['0' => gpr::t('objetivoespecifico', '-- Select a Specific Objective --')] + ArrayHelper::map($arr_objesp, "id", "name");
        $arr_unidad = UnidadGpr::getArrayUnidad();
        $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
        $arr_poa = PlanificacionPoa::getArrayPlanPoa();
        $arr_poa = ['0' => gpr::t('planificacionpoa', '-- Select a Poa Planning --')] + ArrayHelper::map($arr_poa, "id", "name");

        $_SESSION['JSLANG']['Please select a Subunit Name.'] = gpr::t('subunidad', 'Please select a Subunit Name.');
        $_SESSION['JSLANG']['Please select a Specific Objective.'] = gpr::t('objetivoespecifico', 'Please select a Specific Objective.');
        $_SESSION['JSLANG']['Please select a Poa Planning.'] = gpr::t('planificacionpoa', 'Please select a Poa Planning.');
        return $this->render('new', [
            'arr_poa' => $arr_poa,
            'arr_objesp' => $arr_objesp,
            'arr_unidad' => $arr_unidad,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            
            $arr_objesp = ObjetivoEspecifico::getArrayObjEspecifico();
            $arr_objesp = ['0' => gpr::t('objetivoespecifico', '-- Select a Specific Objective --')] + ArrayHelper::map($arr_objesp, "id", "name");
            $arr_unidad = UnidadGpr::getArrayUnidad();
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
            $arr_poa = PlanificacionPoa::getArrayPlanPoa();
            $arr_poa = ['0' => gpr::t('planificacionpoa', '-- Select a Poa Planning --')] + ArrayHelper::map($arr_poa, "id", "name");

            $_SESSION['JSLANG']['Please select a Subunit Name.'] = gpr::t('subunidad', 'Please select a Subunit Name.');
            $_SESSION['JSLANG']['Please select a Specific Objective.'] = gpr::t('objetivoespecifico', 'Please select a Specific Objective.');
            $_SESSION['JSLANG']['Please select a Poa Planning.'] = gpr::t('planificacionpoa', 'Please select a Poa Planning.');
            return $this->render('view', [
                'model' => ObjetivoOperativo::findOne($id),
                'arr_poa' => $arr_poa,
                'arr_objesp' => $arr_objesp,
                'arr_unidad' => $arr_unidad,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            $arr_objesp = ObjetivoEspecifico::getArrayObjEspecifico();
            $arr_objesp = ['0' => gpr::t('objetivoespecifico', '-- Select a Specific Objective --')] + ArrayHelper::map($arr_objesp, "id", "name");
            $arr_unidad = UnidadGpr::getArrayUnidad();
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
            $arr_poa = PlanificacionPoa::getArrayPlanPoa();
            $arr_poa = ['0' => gpr::t('planificacionpoa', '-- Select a Poa Planning --')] + ArrayHelper::map($arr_poa, "id", "name");

            $_SESSION['JSLANG']['Please select a Subunit Name.'] = gpr::t('subunidad', 'Please select a Subunit Name.');
            $_SESSION['JSLANG']['Please select a Specific Objective.'] = gpr::t('objetivoespecifico', 'Please select a Specific Objective.');
            $_SESSION['JSLANG']['Please select a Poa Planning.'] = gpr::t('planificacionpoa', 'Please select a Poa Planning.');
            return $this->render('edit', [
                'model' => ObjetivoOperativo::findOne($id),
                'arr_poa' => $arr_poa,
                'arr_objesp' => $arr_objesp,
                'arr_unidad' => $arr_unidad,
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
                $especifico = $data["especifico"];
                $unidad = $data["unidad"];
                $plan = $data["poa"];
                $model = new ObjetivoOperativo();
                $model->oope_nombre = $nombre;
                $model->oope_descripcion = $descripcion;
                $model->ppoa_id = $plan;
                $model->oesp_id = $especifico;
                $model->ugpr_id = $unidad;
                $model->oope_fecha_actualizacion = $fecha_modificacion;
                $model->oope_descripcion = $descripcion;
                $model->oope_estado = $estado;
                $model->oope_usuario_ingreso = $user_id;
                $model->oope_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no creado.');
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
                $especifico = $data["especifico"];
                $unidad = $data["unidad"];
                $plan = $data["poa"];
                $model = ObjetivoOperativo::findOne($id);
                $model->oope_nombre = $nombre;
                $model->oope_descripcion = $descripcion;
                $model->ppoa_id = $plan;
                $model->oesp_id = $especifico;
                $model->ugpr_id = $unidad;
                $model->oope_usuario_modifica = $user_id;
                $model->oope_fecha_modificacion = $fecha_modificacion;
                $model->oope_estado = $estado;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no actualizado.');
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
                $model = ObjetivoOperativo::findOne($id);
                $model->oope_estado_logico = '0';
                $model->oope_usuario_modifica = $user_id;
                $model->oope_fecha_modificacion = $fecha_modificacion;
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