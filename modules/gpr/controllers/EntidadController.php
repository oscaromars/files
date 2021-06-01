<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\Entidad;
use app\models\Utilities;
use app\models\Empresa;
use app\modules\gpr\models\Categoria;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class EntidadController extends \app\components\CController {

    public function actionIndex() {
        $model = new Entidad();
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllPlanPediGrid($data["search"], $data["categoria"], true)
            ]);
        }
        $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
        $arr_categoria = ['0' => gpr::t('categoria', '-- All Categories --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
        $arr_empresa = array();
        if($user_id == 1){
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
        }else{
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $idEmpresa]);
        }
        $arr_empresa = ['0' => gpr::t('entidad', '-- All Companies --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_alias");
        return $this->render('index', [
            'model' => $model->getAllPlanPediGrid(NULL, NULL, true),
            'arr_categoria' => $arr_categoria,
            'arr_empresa' => $arr_empresa,
        ]);
    }

    public function actionNew() {
        $_SESSION['JSLANG']['Please select a Category Name.'] = gpr::t('categoria', 'Please select a Category Name.');
        $_SESSION['JSLANG']['Please select a Company Name.'] = gpr::t('entidad', 'Please select a Company Name.');
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
        $arr_categoria = ['0' => gpr::t('categoria', '-- All Categories --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
        $arr_empresa = array();
        if($user_id == 1){
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
        }else{
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $idEmpresa]);
        }
        $arr_empresa = ['0' => gpr::t('entidad', '-- Select a Company Name --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_alias");
        return $this->render('new', [
            'arr_categoria' => $arr_categoria,
            'arr_empresa' => $arr_empresa,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_empresa = array();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
            $arr_categoria = ['0' => gpr::t('categoria', '-- All Categories --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
            if($user_id == 1){
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
            }else{
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $idEmpresa]);
            }
            $arr_empresa = ['0' => gpr::t('entidad', '-- Select a Company Name --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_alias");
            return $this->render('view', [
                'model' => Entidad::findOne($id),
                'arr_categoria' => $arr_categoria,
                'arr_empresa' => $arr_empresa,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_empresa = array();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $_SESSION['JSLANG']['Please select a Category Name.'] = gpr::t('categoria', 'Please select a Category Name.');
            $_SESSION['JSLANG']['Please select a Company Name.'] = gpr::t('entidad', 'Please select a Company Name.');
            $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
            $arr_categoria = ['0' => gpr::t('categoria', '-- All Categories --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
            if($user_id == 1){
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
            }else{
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $idEmpresa]);
            }
            $arr_empresa = ['0' => gpr::t('entidad', '-- Select a Company Name --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_alias");
            return $this->render('edit', [
                'model' => Entidad::findOne($id),
                'arr_categoria' => $arr_categoria,
                'arr_empresa' => $arr_empresa,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $empresa = (($user_id == 1)?$data["empresa"]:$idEmpresa);
                $categoria = $data['categoria'];
                $model = new Entidad();
                $model->ent_nombre = $nombre;
                $model->ent_descripcion = $descripcion;
                $model->cat_id = $categoria;
                $model->emp_id = $empresa;
                $model->ent_estado = $estado;
                $model->ent_usuario_ingreso = $user_id;
                $model->ent_estado_logico = "1";
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
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $data = Yii::$app->request->post();
            try {
                $id = $data["id"];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $empresa = (($user_id == 1)?$data["empresa"]:$idEmpresa);
                $categoria = $data['categoria'];
                $model = Entidad::findOne($id);
                $model->ent_nombre = $nombre;
                $model->ent_descripcion = $descripcion;
                $model->cat_id = $categoria;
                $model->ent_usuario_modifica = $user_id;
                $model->emp_id = $empresa;
                $model->ent_fecha_modificacion = $fecha_modificacion;
                $model->ent_estado = $estado;
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
                $model = Entidad::findOne($id);
                $model->ent_estado_logico = '0';
                $model->ent_usuario_modifica = $user_id;
                $model->ent_fecha_modificacion = $fecha_modificacion;
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