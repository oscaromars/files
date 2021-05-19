<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\components\CController;
use app\modules\financiero\models\Divisa;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class DivisaController extends CController {

    public function actionIndex() {
        $div_model = new Divisa();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $div_model->getAllDivisasGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $div_model->getAllDivisasGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                        'model' => Divisa::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('edit', [
                'model' => Divisa::findOne($id),
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
                $cotizacion = $data["cot"];
                $fecha = $data["fecha"];                
                $estado = $data["estado"];

                $div_model = new Divisa();
                $div_model->div_nombre = $nombre;
                $div_model->div_cod = $codigo;
                $div_model->div_cotizacion = $cotizacion;
                $div_model->div_fecha = $fecha;
                $div_model->div_estado = $estado;
                $div_model->div_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );                
                if ($div_model->save()) {
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
                $cotizacion = $data["cot"];
                $fecha = $data["fecha"];                
                $estado = $data["estado"];

                $div_model = Divisa::findOne($id);
                $div_model->div_nombre = $nombre;
                $div_model->div_cod = $codigo;
                $div_model->div_cotizacion = $cotizacion;
                $div_model->div_fecha = $fecha;
                $div_model->div_estado = $estado;

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($div_model->update() !== false) {
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
                $div_model = Divisa::findOne($id);
                $div_model->div_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($div_model->update() !== false) {
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