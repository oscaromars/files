<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\ObjetivoEstrategico;
use app\modules\gpr\models\Enfoque;
use app\modules\gpr\models\CategoriaBsc;
use app\models\Utilities;
use app\modules\gpr\models\Entidad;
use app\modules\gpr\models\PlanificacionPedi;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class ObjetivoestrategicoController extends \app\components\CController {

    public function actionIndex() {
        $model = new ObjetivoEstrategico();
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllObjEstGrid($data["search"], $data["cbsc"], $data["enfoque"], $data["plan"], true)
            ]);
        }
        $arr_enfoque = Enfoque::findAll(['enf_estado' => '1', 'enf_estado_logico' => '1']);
        $arr_enfoque = ['0' => gpr::t('enfoque', "-- All Focus --")] + ArrayHelper::map($arr_enfoque, "enf_id", "enf_nombre");
        $arr_bsc = CategoriaBsc::findAll(['cbsc_estado' => '1', 'cbsc_estado_logico' => '1']);
        $arr_bsc = ['0' => gpr::t('categoriabsc', '-- All Category BSC --')] + ArrayHelper::map($arr_bsc, "cbsc_id", "cbsc_nombre");
        $entidad = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
        $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'ent_id' => $entidad->ent_id]);
        $arr_plan = ['0' => gpr::t('planificacionpedi', '-- All Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
        return $this->render('index', [
            'model' => $model->getAllObjEstGrid(NULL, NULL, NULL, NULL, true),
            'arr_bsc' => $arr_bsc,
            'arr_enfoque' => $arr_enfoque,
            'arr_plan' => $arr_plan,
        ]);
    }

    public function actionNew() {
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
        $arr_enfoque = Enfoque::findAll(['enf_estado' => '1', 'enf_estado_logico' => '1']);
        $arr_enfoque = ['0' => gpr::t('enfoque', '-- Select a Focus Name --')] + ArrayHelper::map($arr_enfoque, "enf_id", "enf_nombre");
        $arr_bsc = CategoriaBsc::findAll(['cbsc_estado' => '1', 'cbsc_estado_logico' => '1']);
        $arr_bsc = ['0' => gpr::t('categoriabsc', '-- Select a Category BSC --')] + ArrayHelper::map($arr_bsc, "cbsc_id", "cbsc_nombre");
        $entidad = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
        $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'pped_estado_cierre' => 0, 'ent_id' => $entidad->ent_id]);
        $arr_plan = ['0' => gpr::t('planificacionpedi', '-- Select a Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
        $_SESSION['JSLANG']['Please select a Category BSC.'] = gpr::t('objetivoestrategico', 'Please select a Category BSC.');
        $_SESSION['JSLANG']['Please select a Focus.'] = gpr::t('objetivoestrategico', 'Please select a Focus.');
        $_SESSION['JSLANG']['Please select a Pedi Planning.'] = gpr::t('planificacionpedi', 'Please select a Pedi Planning.');
        return $this->render('new', [
            'arr_bsc' => $arr_bsc,
            'arr_enfoque' => $arr_enfoque,
            'arr_plan' => $arr_plan,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            $entidad = Entidad::findOne(['ent_estado' => '1', 'ent_estado_logico' => '1', 'emp_id' => $emp_id]);
            $arr_enfoque = Enfoque::findAll(['enf_estado' => '1', 'enf_estado_logico' => '1']);
            $arr_enfoque = ['0' => gpr::t('enfoque', '-- Select a Focus Name --')] + ArrayHelper::map($arr_enfoque, "enf_id", "enf_nombre");
            $arr_bsc = CategoriaBsc::findAll(['cbsc_estado' => '1', 'cbsc_estado_logico' => '1']);
            $arr_bsc = ['0' => gpr::t('categoriabsc', '-- Select a Category BSC --')] + ArrayHelper::map($arr_bsc, "cbsc_id", "cbsc_nombre");
            $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'pped_estado_cierre' => 0, 'ent_id' => $entidad->ent_id]);
            $arr_plan = ['0' => gpr::t('planificacionpedi', '-- Select a Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
            return $this->render('view', [
                'model' => ObjetivoEstrategico::findOne($id),
                'arr_enfoque' => $arr_enfoque,
                'arr_bsc' => $arr_bsc,
                'arr_plan' => $arr_plan,
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
            $arr_enfoque = Enfoque::findAll(['enf_estado' => '1', 'enf_estado_logico' => '1']);
            $arr_enfoque = ['0' => gpr::t('enfoque', '-- Select a Focus Name --')] + ArrayHelper::map($arr_enfoque, "enf_id", "enf_nombre");
            $arr_bsc = CategoriaBsc::findAll(['cbsc_estado' => '1', 'cbsc_estado_logico' => '1']);
            $arr_bsc = ['0' => gpr::t('categoriabsc', '-- Select a Category BSC --')] + ArrayHelper::map($arr_bsc, "cbsc_id", "cbsc_nombre");
            $arr_plan = PlanificacionPedi::findAll(['pped_estado' => '1', 'pped_estado_logico' => '1', 'pped_estado_cierre' => 0, 'ent_id' => $entidad->ent_id]);
            $arr_plan = ['0' => gpr::t('planificacionpedi', '-- Select a Pedi Planning --')] + ArrayHelper::map($arr_plan, "pped_id", "pped_nombre");
            $_SESSION['JSLANG']['Please select a Category BSC.'] = gpr::t('objetivoestrategico', 'Please select a Category BSC.');
            $_SESSION['JSLANG']['Please select a Focus.'] = gpr::t('objetivoestrategico', 'Please select a Focus.');
            $_SESSION['JSLANG']['Please select a Pedi Planning.'] = gpr::t('planificacionpedi', 'Please select a Pedi Planning.');
            return $this->render('edit', [
                'model' => ObjetivoEstrategico::findOne($id),
                'arr_enfoque' => $arr_enfoque,
                'arr_bsc' => $arr_bsc,
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
                $cbsc_id = $data["catbsc"];
                $pped_id = $data["plan"];
                $enf_id = $data["enfoque"];
                $model = new ObjetivoEstrategico();
                $model->oest_nombre = $nombre;
                $model->cbsc_id = $cbsc_id;
                $model->enf_id = $enf_id;
                $model->pped_id = $pped_id;
                $model->oest_fecha_actualizacion = $fecha_modificacion;
                $model->oest_descripcion = $descripcion;
                $model->oest_estado = $estado;
                $model->oest_usuario_ingreso = $user_id;
                $model->oest_estado_logico = "1";
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
                $cbsc_id = $data["catbsc"];
                $enf_id = $data["enfoque"];
                $pped_id = $data["plan"];
                $model = ObjetivoEstrategico::findOne($id);
                $model->oest_nombre = $nombre;
                $model->oest_descripcion = $descripcion;
                $model->cbsc_id = $cbsc_id;
                $model->enf_id = $enf_id;
                $model->pped_id = $pped_id;
                $model->oest_usuario_modifica = $user_id;
                $model->oest_fecha_modificacion = $fecha_modificacion;
                $model->oest_estado = $estado;
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
                $model = ObjetivoEstrategico::findOne($id);
                $model->oest_estado_logico = '0';
                $model->oest_usuario_modifica = $user_id;
                $model->oest_fecha_modificacion = $fecha_modificacion;
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