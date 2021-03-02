<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\SubareaConocimiento;
use app\modules\academico\models\AreaConocimiento;
use app\modules\academico\models\UnidadAcademica;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class AsignaturaController extends \app\components\CController {

    public function actionIndex() {
        $asignatura_model = new Asignatura();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $asignatura_model->getAllAsignaturasGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $asignatura_model->getAllAsignaturasGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];

            $area_conocimiento_model = new AreaConocimiento();

            return $this->render('view', [
                        'model' => Asignatura::findOne($id),
                        'area_conocimiento_model' => $area_conocimiento_model->areaConocimientobyAsignatura($id),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];            
        
            $arr_scon = SubareaConocimiento::findAll(["scon_estado" => 1, "scon_estado_logico" => 1]);
            
            $model = new AreaConocimiento();
            $arr_acon = $model->areaConocimientobyAsignatura($id);

            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();
                if (isset($data["scon_id"])) {                    
                    $arr_acon = $model->areaConocimientobySubareaConocimiento($data["scon_id"]);
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $arr_acon);
                }
            }

            $acon_id = $arr_acon[0]['acon_id'];
                
            return $this->render('edit', [
                'model' => Asignatura::findOne($id),
                'acon_id' => $acon_id,
                'arr_acon' => (empty(ArrayHelper::map($arr_acon, "acon_id", "acon_nombre"))) ? array(Yii::t("areaconocimiento", "-- Select Area --")) : (ArrayHelper::map($arr_acon, "acon_id", "acon_nombre")),
                'arr_scon' => (empty(ArrayHelper::map($arr_scon, "scon_id", "scon_nombre"))) ? array(Yii::t("subareaconocimiento", "-- Select Area --")) : (ArrayHelper::map($arr_scon, "scon_id", "scon_nombre")),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        $arr_scon = SubareaConocimiento::findAll(["scon_estado" => 1, "scon_estado_logico" => 1]);        
        list($firstscon) = $arr_scon;        

        $arr_acon  = AreaConocimiento::find()
            ->select(["acon_id", "acon_nombre"])            
            ->andWhere(["acon_estado" => 1, "acon_estado_logico" => 1,
            "acon_id" => $firstscon->acon_id])->asArray()->all();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["scon_id"])) {
                $model = new AreaConocimiento();
                $arr_acon = $model->areaConocimientobySubareaConocimiento($data["scon_id"]);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $arr_acon);
            }
        }
        $mod_unidad = new UnidadAcademica();
        $arr_unidad = $mod_unidad->consultarUnidadAcademicas();
        return $this->render('new', [
            'arr_acon' => (empty(ArrayHelper::map($arr_acon, "acon_id", "acon_nombre"))) ? array(Yii::t("areaconocimiento", "-- Select Area --")) : (ArrayHelper::map($arr_acon, "acon_id", "acon_nombre")),
            'arr_scon' => (empty(ArrayHelper::map($arr_scon, "scon_id", "scon_nombre"))) ? array(Yii::t("subareaconocimiento", "-- Select Area --")) : (ArrayHelper::map($arr_scon, "scon_id", "scon_nombre")),
            'arr_unidad' => ArrayHelper::map($arr_unidad, "id", "name"),
        ]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["descripcion"];
                $scon_id = $data["scon_id"];
                $acon_id = $data["acon_id"];
                $estado = $data["estado"];
                $unidad = $data["uaca_id"];
                
                $asignatura_model = new Asignatura();
                $asignatura_model->asi_nombre = $nombre;
                $asignatura_model->asi_descripcion = $descripcion;
                $asignatura_model->scon_id = $scon_id;
                $asignatura_model->asi_estado = $estado;
                $asignatura_model->uaca_id = $unidad;
                $asignatura_model->asi_estado_logico = "1";
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($asignatura_model->save()) {
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
                $descripcion = $data["descripcion"];
                $scon_id = $data["scon_id"];
                $acon_id = $data["acon_id"];
                $estado = $data["estado"];

                $asignatura_model = Asignatura::findOne($id);
                $asignatura_model->asi_nombre = $nombre;
                $asignatura_model->asi_descripcion = $descripcion;
                $asignatura_model->scon_id = $scon_id;
                $asignatura_model->asi_estado = $estado;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($asignatura_model->update() !== false) {
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
                $asignatura_model = Asignatura::findOne($id);
                $asignatura_model->asi_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($asignatura_model->update() !== false) {
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



