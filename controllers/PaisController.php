<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Grupo;
use app\models\Pais;
use app\models\Accion;
use app\models\Lineaventa;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Aplicacion;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;
use app\models\Continente;

class PaisController extends CController {

    public function actionIndex() {
        $pais_model = new Pais();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $pais_model->getAllPaisesGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $pais_model->getAllPaisesGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            return $this->render('view', [
                        'model' => Pais::findOne($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_continents = Continente::findAll(["cont_estado" => 1, "cont_estado_logico" => 1]);
            return $this->render('edit', [
                'model' => Pais::findOne($id),
                'arr_continents' => (empty(ArrayHelper::map($arr_continents, "cont_id", "cont_nombre"))) ? array(Yii::t("pais", "-- Select Cont --")) : (ArrayHelper::map($arr_continents, "cont_id", "cont_nombre"))
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        $arr_continents = Continente::findAll(["cont_estado" => 1, "cont_estado_logico" => 1]);
        return $this->render('new', [
            'arr_continents' => (empty(ArrayHelper::map($arr_continents, "cont_id", "cont_nombre"))) ? array(Yii::t("pais", "-- Select Cont --")) : (ArrayHelper::map($arr_continents, "cont_id", "cont_nombre"))
        ]);        
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $capital = $data["cap"];
                $nacionaldiad = $data["nac"];
                $iso_2 = $data["iso2"];
                $iso_3 = $data["iso3"];
                $code_phone = $data["cod"];
                $cont_id = $data["cont_id"];
                
                $pais_model = new Pais();
                $pais_model->pai_nombre = $nombre;                
                $pais_model->pai_descripcion = $descripcion;
                $pais_model->pai_estado = $estado;
                $pais_model->pai_capital = $capital;
                $pais_model->pai_nacionalidad = $nacionaldiad;
                $pais_model->pai_iso2 = $iso_2;
                $pais_model->pai_iso3 = $iso_3;
                $pais_model->pai_codigo_fono = $code_phone;
                $pais_model->cont_id = $cont_id;
                $pais_model->pai_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($pais_model->save()) {
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
                $capital = $data["cap"];
                $nacionaldiad = $data["nac"];
                $iso_2 = $data["iso2"];
                $iso_3 = $data["iso3"];
                $code_phone = $data["cod"];
                $cont_id = $data["cont_id"];

                $pais_model = Pais::findOne($id);
                $pais_model->pai_nombre = $nombre;                
                $pais_model->pai_descripcion = $descripcion;
                $pais_model->pai_estado = $estado;
                $pais_model->pai_capital = $capital;
                $pais_model->pai_nacionalidad = $nacionaldiad;
                $pais_model->pai_iso2 = $iso_2;
                $pais_model->pai_iso3 = $iso_3;
                $pais_model->pai_codigo_fono = $code_phone;
                $pais_model->cont_id = $cont_id;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($pais_model->update() !== false) {
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
                $pais_model = Pais::findOne($id);
                $pais_model->pai_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($pais_model->update() !== false) {
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
