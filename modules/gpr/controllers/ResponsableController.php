<?php

namespace app\modules\gpr\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Usuario;
use app\models\Empresa;
use app\models\Utilities;
use app\modules\gpr\models\Nivel;
use app\modules\gpr\models\ResponsableUnidad;
use app\modules\gpr\models\UnidadGpr;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class ResponsableController extends \app\components\CController {

    public function actionIndex(){
        $model = new ResponsableUnidad();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllResponsableGprGrid($data["search"], $data["empresa"], $data["unidad"], $data["nivel"], true),
            ]);
        }
        $arr_empresa = array();
        $arr_unidad = UnidadGpr::findAll(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1']);
        $arr_unidad = ['0' => gpr::t('unidad', '-- All Unities --')] + ArrayHelper::map($arr_unidad, "ugpr_id", "ugpr_nombre");
        if($user_id == 1){
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
        }else{
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $emp_id]);
        }
        $arr_empresa = ['0' => gpr::t('responsablesubunidad', '-- All Companies --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_nombre_comercial");
        $arr_nivel = Nivel::findAll(['niv_estado' => '1', 'niv_estado_logico' => '1']);
        $arr_nivel = ['0' => gpr::t('responsablesubunidad', '-- All Levels --')] + ArrayHelper::map($arr_nivel, "niv_id", "niv_nombre");
        return $this->render('index', [
            'model' => $model->getAllResponsableGprGrid(NULL, NULL, NULL, NULL, true),
            'arr_unidad' => $arr_unidad,
            'arr_empresa' => $arr_empresa,
            'arr_nivel' => $arr_nivel,
        ]);
    }

    public function actionNew(){
        $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
        $_SESSION['JSLANG']['Please select a Company Name.'] = gpr::t('responsablesubunidad', 'Please select a Company Name.');
        $_SESSION['JSLANG']['Please select a Person Name.'] = gpr::t('responsablesubunidad', 'Please select a Person Name.');
        $_SESSION['JSLANG']['Please select a Level.'] = gpr::t('responsablesubunidad', 'Please select a Level.');
        $_SESSION['JSLANG']['Auditor requires N1 privileges.'] = gpr::t('responsablesubunidad', 'Auditor requires N1 privileges.');
        $arr_empresa = array();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
        $arr_unidad = UnidadGpr::findAll(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1']);
        $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "ugpr_id", "ugpr_nombre");
        if($user_id == 1){
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
        }else{
            $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $emp_id]);
        }
        $arr_empresa = ['0' => gpr::t('responsablesubunidad', '-- Select a Company Name --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_nombre_comercial");
        $arr_nivel = Nivel::findAll(['niv_estado' => '1', 'niv_estado_logico' => '1']);
        $arr_nivel = ['0' => gpr::t('responsablesubunidad', '-- Select a Level --')] + ArrayHelper::map($arr_nivel, "niv_id", "niv_nombre");
        $arr_responsable = Usuario::getListUsers(NULL, $emp_id, true);
        $arr_responsable = ['0' => gpr::t('responsablesubunidad', '-- Select a Person --')] + ArrayHelper::map($arr_responsable, "id", "Persona");
        $arr_auditor = ['0' => gpr::t('responsablesubunidad', 'No Auditor'), '1' => gpr::t('responsablesubunidad', 'Auditor')];
        return $this->render('new', [
            'arr_unidad' => $arr_unidad,
            'arr_empresa' => $arr_empresa,
            'arr_nivel' => $arr_nivel,
            'arr_responsable' => $arr_responsable,
            'arr_auditor' => $arr_auditor,
        ]);
    }

    public function actionView(){
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $model = ResponsableUnidad::findOne($data['id']);
            $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
            $_SESSION['JSLANG']['Please select a Company Name.'] = gpr::t('responsablesubunidad', 'Please select a Company Name.');
            $_SESSION['JSLANG']['Please select a Person Name.'] = gpr::t('responsablesubunidad', 'Please select a Person Name.');
            $_SESSION['JSLANG']['Please select a Level.'] = gpr::t('responsablesubunidad', 'Please select a Level.');
            $arr_empresa = array();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
            $arr_unidad = UnidadGpr::findAll(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1']);
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "ugpr_id", "ugpr_nombre");
            if($user_id == 1){
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
            }else{
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $emp_id]);
            }
            $arr_empresa = ['0' => gpr::t('responsablesubunidad', '-- Select a Company Name --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_nombre_comercial");
            $arr_nivel = Nivel::findAll(['niv_estado' => '1', 'niv_estado_logico' => '1']);
            $arr_nivel = ['0' => gpr::t('responsablesubunidad', '-- Select a Level --')] + ArrayHelper::map($arr_nivel, "niv_id", "niv_nombre");
            $arr_responsable = Usuario::getListUsers(NULL, $emp_id, true);
            $arr_responsable = ['0' => gpr::t('responsablesubunidad', '-- Select a Person --')] + ArrayHelper::map($arr_responsable, "id", "Persona");
            $arr_auditor = ['0' => gpr::t('responsablesubunidad', 'No Auditor'), '1' => gpr::t('responsablesubunidad', 'Auditor')];
            return $this->render('view', [
                'model' => $model,
                'arr_unidad' => $arr_unidad,
                'arr_empresa' => $arr_empresa,
                'arr_nivel' => $arr_nivel,
                'arr_responsable' => $arr_responsable,
                'arr_auditor' => $arr_auditor,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit(){
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $model = ResponsableUnidad::findOne($data['id']);
            $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
            $_SESSION['JSLANG']['Please select a Company Name.'] = gpr::t('responsablesubunidad', 'Please select a Company Name.');
            $_SESSION['JSLANG']['Please select a Person Name.'] = gpr::t('responsablesubunidad', 'Please select a Person Name.');
            $_SESSION['JSLANG']['Please select a Level.'] = gpr::t('responsablesubunidad', 'Please select a Level.');
            $_SESSION['JSLANG']['Auditor requires N1 privileges.'] = gpr::t('responsablesubunidad', 'Auditor requires N1 privileges.');
            $arr_empresa = array();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
            $arr_unidad = UnidadGpr::findAll(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1']);
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "ugpr_id", "ugpr_nombre");
            if($user_id == 1){
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1']);
            }else{
                $arr_empresa = Empresa::findAll(['emp_estado' => '1', 'emp_estado_logico' => '1', 'emp_id' => $emp_id]);
            }
            $arr_empresa = ['0' => gpr::t('responsablesubunidad', '-- Select a Company Name --')] + ArrayHelper::map($arr_empresa, "emp_id", "emp_nombre_comercial");
            $arr_nivel = Nivel::findAll(['niv_estado' => '1', 'niv_estado_logico' => '1']);
            $arr_nivel = ['0' => gpr::t('responsablesubunidad', '-- Select a Level --')] + ArrayHelper::map($arr_nivel, "niv_id", "niv_nombre");
            $arr_responsable = Usuario::getListUsers(NULL, $emp_id, true);
            $arr_responsable = ['0' => gpr::t('responsablesubunidad', '-- Select a Person --')] + ArrayHelper::map($arr_responsable, "id", "Persona");
            $arr_auditor = ['0' => gpr::t('responsablesubunidad', 'No Auditor'), '1' => gpr::t('responsablesubunidad', 'Auditor')];
            return $this->render('edit', [
                'model' => $model,
                'arr_unidad' => $arr_unidad,
                'arr_empresa' => $arr_empresa,
                'arr_nivel' => $arr_nivel,
                'arr_responsable' => $arr_responsable,
                'arr_auditor' => $arr_auditor,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionUpdate(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $emp_id = $data["empresa"];
                $usu_id = $data["usuario"];
                $niv_id = $data["nivel"];
                $ugpr_id = $data['unidad'];
                $estado = $data['estado'];
                $auditor = $data['auditor'];
                $model = ResponsableUnidad::findOne($id);
                $model2 = ResponsableUnidad::findOne(['emp_id' => $emp_id, 'usu_id' => $usu_id, 'runi_estado' => '1', 'runi_estado_logico' => '1']);
                if($model2 && $model2->runi_id != $model->runi_id){
                    $error = true;
                    throw new Exception(gpr::t("responsablesubunidad", "Responsible already exists in System to assigned another Unit."));
                }
                $model->emp_id = $emp_id;
                $model->usu_id = $usu_id;
                $model->niv_id = $niv_id;
                $model->ugpr_id = $ugpr_id;
                $model->runi_isadmin = $auditor;
                $model->runi_estado = $estado;
                $model->runi_usuario_modifica = $user_id;
                $model->runi_fecha_modificacion = $fecha_modificacion;
                $model->runi_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no actualizado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionSave(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $emp_id = $data["empresa"];
                $usu_id = $data["usuario"];
                $niv_id = $data["nivel"];
                $ugpr_id = $data['unidad'];
                $estado = $data['estado'];
                $auditor = $data['auditor'];
                $model = ResponsableUnidad::findOne(['emp_id' => $emp_id, 'usu_id' => $usu_id, 'runi_estado' => '1', 'runi_estado_logico' => '1']);
                if($model){
                    $error = true;
                    throw new Exception(gpr::t("responsablesubunidad", "Responsible already exists in System to assigned another Unit."));
                }
                $model = new ResponsableUnidad();
                $model->emp_id = $emp_id;
                $model->usu_id = $usu_id;
                $model->niv_id = $niv_id;
                $model->ugpr_id = $ugpr_id;
                $model->runi_isadmin = $auditor;
                $model->runi_estado = $estado;
                $model->runi_usuario_ingreso = $user_id;
                $model->runi_estado_logico = "1";
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no creado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            try {
                $id = $data["id"];
                $model = ResponsableUnidad::findOne($id);
                $model->runi_estado_logico = '0';
                $model->runi_usuario_modifica = $user_id;
                $model->runi_fecha_modificacion = $fecha_modificacion;
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