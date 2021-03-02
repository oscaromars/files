<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\Umbral;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class UmbralController extends \app\components\CController {

    public function actionIndex() {
        $model = new Umbral();
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllUmbralesGrid($data["search"], true)
            ]);
        }
        return $this->render('index', [
                'model' => $model->getAllUmbralesGrid(NULL, true)
        ]);
    }

    public function actionNew() {
        $_SESSION['JSLANG']['Please the Init Value of Threshold must be less than End Value.'] = gpr::t('umbral', 'Please the Init Value of Threshold must be less than End Value.');
        return $this->render('new');
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                'model' => Umbral::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $_SESSION['JSLANG']['Please the Init Value of Threshold must be less than End Value.'] = gpr::t('umbral', 'Please the Init Value of Threshold must be less than End Value.');
            return $this->render('edit', [
                'model' => Umbral::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $color = $data["color"];
                $inicio = $data["inicio"];
                $fin = $data["fin"];
                $model = new Umbral();
                $model->umb_nombre = $nombre;
                $model->umb_descripcion = $descripcion;
                $model->umb_color = $color;
                $model->umb_per_inicio = $inicio;
                $model->umb_per_fin = $fin;
                $model->umb_estado = $estado;
                $model->umb_usuario_ingreso = $user_id;
                $model->umb_estado_logico = "1";
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
                $color = $data["color"];
                $inicio = $data["inicio"];
                $fin = $data["fin"];
                $model = Umbral::findOne($id);
                $model->umb_nombre = $nombre;
                $model->umb_descripcion = $descripcion;
                $model->umb_color = $color;
                $model->umb_per_inicio = $inicio;
                $model->umb_per_fin = $fin;
                $model->umb_usuario_modifica = $user_id;
                $model->umb_fecha_modificacion = $fecha_modificacion;
                $model->umb_estado = $estado;
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
                $model = Umbral::findOne($id);
                $model->umb_estado_logico = '0';
                $model->umb_usuario_modifica = $user_id;
                $model->umb_fecha_modificacion = $fecha_modificacion;
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