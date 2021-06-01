<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Provincia;
use app\models\Pais;
use app\models\GrupRol;
use app\models\Rol;
use app\models\Accion;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Aplicacion;
use app\models\GrupoRol;
use app\models\ConfiguracionSeguridad;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class ProvinciaController extends CController {

    public function actionIndex() {
        $provincia_model = new Provincia();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $provincia_model->getAllProvinciasGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $provincia_model->getAllProvinciasGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                        'model' => Provincia::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            return $this->render('edit', [
                'model' => Provincia::findOne($id),
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("provincia", "-- Select Province --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
        return $this->render('new', [
            'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("provincia", "-- Select Province --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))
        ]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $nombre = $data["nombre"];
                $capital = $data["capital"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];                
                $pai_id = $data["pai_id"];
                
                $provincia_model = new Provincia();
                $provincia_model->pro_nombre = $nombre;
                $provincia_model->pro_capital = $capital;
                $provincia_model->pro_descripcion = $descripcion;
                $provincia_model->pai_id = $pai_id;
                $provincia_model->pro_estado = $estado;
                $provincia_model->pro_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($provincia_model->save()) {
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
                $capital = $data["capital"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];                
                $pai_id = $data["pai_id"];

                $provincia_model = Provincia::findOne($id);
                $provincia_model->pro_nombre = $nombre;
                $provincia_model->pro_capital = $capital;
                $provincia_model->pro_descripcion = $descripcion;
                $provincia_model->pai_id = $pai_id;
                $provincia_model->pro_estado = $estado;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($provincia_model->update() !== false) {
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
                $provincia_model = Provincia::findOne($id);
                $provincia_model->pro_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($provincia_model->update() !== false) {
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
