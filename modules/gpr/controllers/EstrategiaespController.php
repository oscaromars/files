<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\ObjetivoEspecifico;
use app\modules\gpr\models\EstrategiaObjEsp;
use app\models\Utilities;
use app\modules\gpr\models\Entidad;
use app\modules\gpr\models\PlanificacionPedi;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class EstrategiaespController extends \app\components\CController {

    public function actionIndex() {
        $model = new EstrategiaObjEsp();
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllEstrategiasEspGrid($data["search"], $data["objetivo"], true)
            ]);
        }
        //$arr_obj = ObjetivoEspecifico::findAll(['oesp_estado' => '1', 'oesp_estado_logico' => '1']);
        $arr_obj = ObjetivoEspecifico::getArrayObjEspecifico();
        $arr_obj = ['0' => gpr::t('objetivoespecifico', "-- All Specific Objective --")] + ArrayHelper::map($arr_obj, "id", "name");
        return $this->render('index', [
            'model' => $model->getAllEstrategiasEspGrid(NULL, NULL, true),
            'arr_obj' => $arr_obj,
        ]);
    }

    public function actionNew() {
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        //$arr_obj = ObjetivoEspecifico::findAll(['oesp_estado' => '1', 'oesp_estado_logico' => '1']);
        $arr_obj = ObjetivoEspecifico::getArrayObjEspecifico();
        $arr_obj = ['0' => gpr::t('objetivoespecifico', "-- Select a Specific Objective --")] + ArrayHelper::map($arr_obj, "id", "name");
        $_SESSION['JSLANG']['Please select a Specific Objective.'] = gpr::t('objetivoestrategico', 'Please select a Specific Objective.');
        return $this->render('new', [
            'arr_obj' => $arr_obj,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            //$arr_obj = ObjetivoEspecifico::findAll(['oesp_estado' => '1', 'oesp_estado_logico' => '1']);
            $arr_obj = ObjetivoEspecifico::getArrayObjEspecifico();
            $arr_obj = ['0' => gpr::t('objetivoespecifico', "-- Select a Specific Objective --")] + ArrayHelper::map($arr_obj, "id", "name");
            return $this->render('view', [
                'model' => EstrategiaObjEsp::findOne($id),
                'arr_obj' => $arr_obj,
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
            //$arr_obj = ObjetivoEspecifico::findAll(['oesp_estado' => '1', 'oesp_estado_logico' => '1']);
            $arr_obj = ObjetivoEspecifico::getArrayObjEspecifico();
            $arr_obj = ['0' => gpr::t('objetivoespecifico', "-- Select a Specific Objective --")] + ArrayHelper::map($arr_obj, "id", "name");
            $_SESSION['JSLANG']['Please select a Specific Objective.'] = gpr::t('objetivoespecifico', 'Please select a Specific Objective.');
            return $this->render('edit', [
                'model' => EstrategiaObjEsp::findOne($id),
                'arr_obj' => $arr_obj,
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
                $oesp_id = $data["objetivo"];
                $model = new EstrategiaObjEsp();
                $model->eoep_nombre = $nombre;
                $model->eoep_descripcion = $descripcion;
                $model->oesp_id = $oesp_id;
                $model->eoep_usuario_ingreso = $user_id;
                $model->eoep_estado = $estado;
                $model->eoep_estado_logico = "1";
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
                $oesp_id = $data["objetivo"];
                $model = EstrategiaObjEsp::findOne($id);
                $model->eoep_nombre = $nombre;
                $model->eoep_descripcion = $descripcion;
                $model->oesp_id = $oesp_id;
                $model->eoep_usuario_modifica = $user_id;
                $model->eoep_fecha_modificacion = $fecha_modificacion;
                $model->eoep_estado = $estado;
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
                $model = EstrategiaObjEsp::findOne($id);
                $model->eoep_estado_logico = '0';
                $model->eoep_usuario_modifica = $user_id;
                $model->eoep_fecha_modificacion = $fecha_modificacion;
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