<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\components\CController;
use app\modules\financiero\models\UnidadMedida;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class UnidadmedidaController extends CController {

    public function actionIndex() {
        $umed_model = new UnidadMedida();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $umed_model->getAllUnidadmedidasGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $umed_model->getAllUnidadmedidasGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                        'model' => UnidadMedida::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('edit', [
                'model' => UnidadMedida::findOne($id),
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
                $medida = $data["medida"];                
                $estado = $data["estado"];
                $fecha  = $data["fecha"];

                $umed_model = new UnidadMedida();
                $umed_model->umed_nombre = $nombre;
                $umed_model->umed_cod = $codigo;
                $umed_model->umed_medida = $medida;
                $umed_model->umed_estado = $estado;
                $umed_model->umed_estado_logico = "1";
                $umed_model->umed_fecha = $fecha;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );                
                if ($umed_model->save()) {
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
                $medida = $data["medida"];                
                $estado = $data["estado"];
                $fecha  = $data["fecha"];

                $umed_model = UnidadMedida::findOne($id);
                $umed_model->umed_id = $id;
                $umed_model->umed_nombre = $nombre;
                $umed_model->umed_cod = $codigo;
                $umed_model->umed_medida = $medida;
                $umed_model->umed_estado = $estado;
                $umed_model->umed_fecha = $fecha;

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($umed_model->update() !== false) {
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
                $umed_model = UnidadMedida::findOne($id);
                $umed_model->umed_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($umed_model->update() !== false) {
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