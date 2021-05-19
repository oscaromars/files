<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Grupo;
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

class GrupoController extends CController {

    public function actionIndex() {
        $grupo_model = new Grupo();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $grupo_model->getAllGruposGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $grupo_model->getAllGruposGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $grupo_model = Grupo::findOne($id);
            $grupo_roles = GrupRol::find()
                    ->joinWith('rol r', false, 'INNER JOIN')
                    ->select('r.rol_id') //->select('r.rol_id, r.rol_nombre')
                    ->where(['gru_id' => $id, "grol_estado" => 1, "grol_estado_logico" => 1, "r.rol_estado" => 1, "r.rol_estado_logico" => 1])
                    ->asArray()
                    ->all();
            $arr_out = array();
            foreach ($grupo_roles as $key => $value) {
                $arr_out[] = $value['rol_id'];
            }
            $arr_roles = Rol::findAll(["rol_estado" => 1, "rol_estado_logico" => 1]);
            return $this->render('view', [
                        'cseg_nombre' => ConfiguracionSeguridad::findOne($grupo_model->cseg_id)->cseg_descripcion,
                        'model' => $grupo_model,
                        'arr_roles' => (empty(ArrayHelper::map($arr_roles, "rol_id", "rol_nombre"))) ? array(Yii::t("rol", "-- Select Role --")) : (ArrayHelper::map($arr_roles, "rol_id", "rol_nombre")),
                        'arr_ids' => $arr_out,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_seguridad = ConfiguracionSeguridad::findAll(["cseg_estado" => 1, "cseg_estado" => 1]);
            $grupo_roles = GrupRol::find()
                    ->joinWith('rol r', false, 'INNER JOIN')
                    ->select('r.rol_id') //->select('r.rol_id, r.rol_nombre')
                    ->where(['gru_id' => $id, "grol_estado" => 1, "grol_estado_logico" => 1, "r.rol_estado" => 1, "r.rol_estado_logico" => 1])
                    ->asArray()
                    ->all();
            $arr_out = array();
            foreach ($grupo_roles as $key => $value) {
                $arr_out[] = $value['rol_id'];
            }
            $arr_roles = Rol::findAll(["rol_estado" => 1, "rol_estado_logico" => 1]);
            return $this->render('edit', [
                        'model' => Grupo::findOne($id),
                        'arr_seguridad' => (empty(ArrayHelper::map($arr_seguridad, "cseg_id", "cseg_descripcion"))) ? array(Yii::t("grupo", "-- Select Security --")) : (ArrayHelper::map($arr_seguridad, "cseg_id", "cseg_descripcion")),
                        'arr_roles' => (empty(ArrayHelper::map($arr_roles, "rol_id", "rol_nombre"))) ? array(Yii::t("rol", "-- Select Role --")) : (ArrayHelper::map($arr_roles, "rol_id", "rol_nombre")),
                        'arr_ids' => $arr_out,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        $arr_seguridad = ConfiguracionSeguridad::findAll(["cseg_estado" => 1, "cseg_estado_logico" => 1]);
        $arr_roles = Rol::findAll(["rol_estado" => 1, "rol_estado_logico" => 1]);
        return $this->render('new',[
            'arr_seguridad' => (empty(ArrayHelper::map($arr_seguridad, "cseg_id", "cseg_descripcion"))) ? array(Yii::t("grupo", "-- Select Security --")) : (ArrayHelper::map($arr_seguridad, "cseg_id", "cseg_descripcion")),
            'arr_roles' => (empty(ArrayHelper::map($arr_roles, "rol_id", "rol_nombre"))) ? array(Yii::t("rol", "-- Select Role --")) : (ArrayHelper::map($arr_roles, "rol_id", "rol_nombre")),
        ]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $color = $data["color"];
                $seguridad = $data["seg"];
                $estado = $data["estado"];
                $roles = $data["roles"];
                $grupo_model = new Grupo();
                $grupo_model->gru_nombre = $nombre;
                $grupo_model->gru_descripcion = $descripcion;
                $grupo_model->cseg_id = $seguridad;
                $grupo_model->gru_estado = $estado;
                $grupo_model->gru_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($grupo_model->save()) {
                    foreach ($roles as $key => $value) {
                        $grupoRol_model = new GrupRol();
                        $grupoRol_model->grol_estado = '1';
                        $grupoRol_model->grol_estado_logico = '1';
                        $grupoRol_model->rol_id = $value;
                        $grupoRol_model->gru_id = $grupo_model->gru_id;
                        if (!$grupoRol_model->save()) {
                            throw new Exception('Error Creanto vinculo entre Grupo y Grupo_Rol.');
                        }
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no creado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
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
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $id = $data["id"];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $color = $data["color"];
                $seguridad = $data["seg"];
                $estado = $data["estado"];
                $roles = $data["roles"];
                $grupo_model = Grupo::findOne($id);
                $grupo_model->gru_nombre = $nombre;
                $grupo_model->gru_descripcion = $descripcion;
                $grupo_model->cseg_id = $seguridad;
                $grupo_model->gru_estado = $estado;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($grupo_model->update() !== false) {
                    if (GrupRol::updateAll(["grol_estado" => '0'], "gru_id = $id AND grol_estado_logico = 1") !== FALSE) {
                        foreach ($roles as $key => $value) {
                            $statusGRol = GrupRol::findOne(["gru_id" => $id, "rol_id" => $value, "grol_estado_logico" => '1']);
                            if (is_null($statusGRol)) {
                                $grupoRol_model = new GrupRol();
                                $grupoRol_model->grol_estado = '1';
                                $grupoRol_model->grol_estado_logico = '1';
                                $grupoRol_model->rol_id = $value;
                                $grupoRol_model->gru_id = $id;
                                if (!$grupoRol_model->save()) {
                                    throw new Exception('Error Creanto vinculo entre Grupo y Grupo_Rol.');
                                }
                            } else {
                                $statusGRol->grol_estado = '1';
                                if (!$statusGRol->save()) {
                                    throw new Exception('Error al Actualizar el Grupo_Rol.');
                                }
                            }
                        }
                    }else{
                        throw new Exception('Error al Actualizar el Grupo_Rol estado inactivo.');
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no actualizado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
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
            try {
                $id = $data["id"];
                $grupo_model = Grupo::findOne($id);
                $grupo_model->gru_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($grupo_model->update() !== false) {
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
