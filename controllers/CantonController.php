<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Grupo;
use app\models\Canton;
use app\models\Provincia;
use app\models\Accion;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Aplicacion;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;
use app\models\Continente;

class CantonController extends CController {

    public function actionIndex() {
        $canton_model = new Canton();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $canton_model->getAllCantonesGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $canton_model->getAllCantonesGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                        'model' => Canton::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_provinces = Provincia::findAll(["pro_estado" => 1, "pro_estado_logico" => 1]);
            return $this->render('edit', [
                'model' => Canton::findOne($id),
                'arr_provinces' => (empty(ArrayHelper::map($arr_provinces, "pro_id", "pro_nombre"))) ? array(Yii::t("canton", "-- Select Province --")) : (ArrayHelper::map($arr_provinces, "pro_id", "pro_nombre"))
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        $arr_provinces = Provincia::findAll(["pro_estado" => 1, "pro_estado_logico" => 1]);
        return $this->render('new', [
            'arr_provinces' => (empty(ArrayHelper::map($arr_provinces, "pro_id", "pro_nombre"))) ? array(Yii::t("canton", "-- Select Province --")) : (ArrayHelper::map($arr_provinces, "pro_id", "pro_nombre"))
        ]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];                
                $pro_id = $data["pro_id"];
                
                $canton_model = new Canton();
                $canton_model->can_nombre = $nombre;
                $canton_model->can_descripcion = $descripcion;
                $canton_model->pro_id = $pro_id;
                $canton_model->can_estado = $estado;
                $canton_model->can_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($canton_model->save()) {
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
                $descripcion = $data["desc"];
                $estado = $data["estado"];                
                $pro_id = $data["pro_id"];

                $canton_model = Canton::findOne($id);
                $canton_model->can_nombre = $nombre;
                $canton_model->can_descripcion = $descripcion;
                $canton_model->pro_id = $pro_id;
                $canton_model->can_estado = $estado;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($canton_model->update() !== false) {
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
                $canton_model = Canton::findOne($id);
                $canton_model->can_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($canton_model->update() !== false) {
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


