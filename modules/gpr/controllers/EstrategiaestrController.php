<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\ObjetivoEstrategico;
use app\modules\gpr\models\EstrategiaObjEstr;
use app\models\Utilities;
use app\modules\gpr\models\Entidad;
use app\modules\gpr\models\PlanificacionPedi;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class EstrategiaestrController extends \app\components\CController {

    public function actionIndex() {
        $model = new EstrategiaObjEstr();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllEstrategiasEstrGrid($data["search"], $data["objetivo"], true)
            ]);
        }
        $entidad = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
        $planPedi = PlanificacionPedi::findOne(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id]);
        $arr_objestr = ObjetivoEstrategico::findAll(['oest_estado' => '1', 'oest_estado_logico' => '1', 'pped_id'=> $planPedi->pped_id]);
        $arr_objestr = ['0' => gpr::t('objetivoestrategico', "-- All Strategic Objective --")] + ArrayHelper::map($arr_objestr, "oest_id", "oest_nombre");
        return $this->render('index', [
            'model' => $model->getAllEstrategiasEstrGrid(NULL, NULL, true),
            'arr_objestr' => $arr_objestr,
        ]);
    }

    public function actionNew() {
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        $entidad = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
        $planPedi = PlanificacionPedi::findOne(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id]);
        $arr_objestr = ObjetivoEstrategico::findAll(['oest_estado' => '1', 'oest_estado_logico' => '1', 'pped_id'=> $planPedi->pped_id]);
        $arr_objestr = ['0' => gpr::t('objetivoestrategico', "-- Select a Strategic Objective --")] + ArrayHelper::map($arr_objestr, "oest_id", "oest_nombre");
        $_SESSION['JSLANG']['Please select a Strategic Objective.'] = gpr::t('objetivoestrategico', 'Please select a Strategic Objective.');
        return $this->render('new', [
            'arr_objestr' => $arr_objestr,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            $entidad = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
            $planPedi = PlanificacionPedi::findOne(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id]);
            $arr_objestr = ObjetivoEstrategico::findAll(['oest_estado' => '1', 'oest_estado_logico' => '1', 'pped_id'=> $planPedi->pped_id]);
            $arr_objestr = ['0' => gpr::t('objetivoestrategico', "-- Select a Strategic Objective --")] + ArrayHelper::map($arr_objestr, "oest_id", "oest_nombre");
            return $this->render('view', [
                'model' => EstrategiaObjEstr::findOne($id),
                'arr_objestr' => $arr_objestr,
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
            $entidad = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
            $planPedi = PlanificacionPedi::findOne(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id]);
            $arr_objestr = ObjetivoEstrategico::findAll(['oest_estado' => '1', 'oest_estado_logico' => '1', 'pped_id'=> $planPedi->pped_id]);
            $arr_objestr = ['0' => gpr::t('objetivoestrategico', "-- Select a Strategic Objective --")] + ArrayHelper::map($arr_objestr, "oest_id", "oest_nombre");
            $_SESSION['JSLANG']['Please select a Strategic Objective.'] = gpr::t('objetivoestrategico', 'Please select a Strategic Objective.');
            return $this->render('edit', [
                'model' => EstrategiaObjEstr::findOne($id),
                'arr_objestr' => $arr_objestr,
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
                $oest_id = $data["objetivo"];
                $model = new EstrategiaObjEstr();
                $model->eoet_nombre = $nombre;
                $model->eoet_descripcion = $descripcion;
                $model->oest_id = $oest_id;
                $model->eoet_usuario_ingreso = $user_id;
                $model->eoet_estado = $estado;
                $model->eoet_estado_logico = "1";
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
                $oest_id = $data["objetivo"];
                $model = EstrategiaObjEstr::findOne($id);
                $model->eoet_nombre = $nombre;
                $model->eoet_descripcion = $descripcion;
                $model->oest_id = $oest_id;
                $model->eoet_usuario_modifica = $user_id;
                $model->eoet_fecha_modificacion = $fecha_modificacion;
                $model->eoet_estado = $estado;
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
                $model = EstrategiaObjEstr::findOne($id);
                $model->eoet_estado_logico = '0';
                $model->eoet_usuario_modifica = $user_id;
                $model->eoet_fecha_modificacion = $fecha_modificacion;
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