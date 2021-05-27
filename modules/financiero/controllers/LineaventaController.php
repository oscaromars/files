<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\components\CController;
use app\modules\financiero\models\LineaVenta;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class LineaventaController extends CController {

    public function actionIndex() {
        $lven_model = new lineaVenta();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $lven_model->getAllLineaVentasGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $lven_model->getAllLineaVentasGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                        'model' => LineaVenta::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('edit', [
                'model' => LineaVenta::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        return $this->render('new');
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $nombre = $data["nombre"];
                $codigo = $data["cod"];
                $fecha = $data["fecha"];                
                $estado = $data["estado"];

                $lven_model = new LineaVenta();
                $lven_model->lven_nombre = $nombre;
                $lven_model->lven_cod = $codigo;
                $lven_model->lven_fecha = $fecha;
                $lven_model->lven_estado = $estado;
                $lven_model->lven_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );                
                if ($lven_model->save()) {
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
            $data = Yii::$app->request->post();
            try {
                $id = $data["id"];
                $nombre = $data["nombre"];
                $codigo = $data["cod"];
                $fecha = $data["fecha"];                
                $estado = $data["estado"];

                $lven_model = LineaVenta::findOne($id);
                $lven_model->lven_id = $id;
                $lven_model->lven_nombre = $nombre;
                $lven_model->lven_cod = $codigo;
                $lven_model->lven_fecha = $fecha;
                $lven_model->lven_estado = $estado;

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($lven_model->update() !== false) {
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
    
    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $id = $data["id"];
                $lven_model = LineaVenta::findOne($id);
                $lven_model->lven_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($lven_model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no ha sido eliminado.');
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