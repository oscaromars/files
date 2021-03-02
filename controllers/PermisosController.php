<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Grupo;
use app\models\GrupObmo;
use app\models\Rol;
use app\models\ObjetoModulo;
use app\models\GrupObmoGrupRol;
use app\models\GrupRol;
use app\models\ConfiguracionSeguridad;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class PermisosController extends CController {

    public function actionIndex() {
        $gruObmo_model = new GrupObmo();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $gruObmo_model->getAllGrupObmoGrid($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $gruObmo_model->getAllGrupObmoGrid(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_grupos  = Grupo::findAll(["gru_estado" => 1, "gru_estado_logico" => 1]);
            $arr_roles   = Rol::findAll(["rol_estado" => 1, "rol_estado_logico" => 1]);
            $arr_objMod = ObjetoModulo::find()->select(['omod_id', 'concat(omod_nombre, " (",omod_tipo,")") as omod_nombre'])->where(["omod_estado" => 1, "omod_estado_logico" => 1])->all();
            $grupo_roles = GrupRol::findOne(["grol_estado" => "1", "grol_estado_logico" => "1", "grol_id" => $id]);
            $obmo_model  = new GrupObmo();
            $arr_mod = $obmo_model->getObjModuleByGrol($grupo_roles->grol_id);
            $arr_out = array();
            foreach ($arr_mod as $key => $value) {
                $arr_out[] = $value['omod_id'];
            }
            return $this->render('view', [
                'arr_roles' => (empty(ArrayHelper::map($arr_roles, "rol_id", "rol_nombre"))) ? array(Yii::t("rol", "-- Select Role --")) : (ArrayHelper::map($arr_roles, "rol_id", "rol_nombre")),
                'arr_grupos' => (empty(ArrayHelper::map($arr_grupos, "gru_id", "gru_nombre"))) ? array(Yii::t("grupo", "-- Select Group --")) : (ArrayHelper::map($arr_grupos, "gru_id", "gru_nombre")),
                'arr_objMod' => (empty(ArrayHelper::map($arr_objMod, "omod_id", "omod_nombre"))) ? array(Yii::t("modulo", "-- Select Module --")) : (ArrayHelper::map($arr_objMod, "omod_id", "omod_nombre")),
                'arr_ids' => $arr_out,
                'grol_id' => $id,
                'rol_id' => $grupo_roles->rol_id,
                'gru_id' => $grupo_roles->gru_id,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_grupos  = Grupo::findAll(["gru_estado" => 1, "gru_estado_logico" => 1]);
            $arr_roles   = Rol::findAll(["rol_estado" => 1, "rol_estado_logico" => 1]);
            $arr_objMod = ObjetoModulo::find()->select(['omod_id', 'concat(omod_nombre, " (",omod_tipo,")") as omod_nombre'])->where(["omod_estado" => 1, "omod_estado_logico" => 1])->all();
            $grupo_roles = GrupRol::findOne(["grol_estado" => "1", "grol_estado_logico" => "1", "grol_id" => $id]);
            $obmo_model  = new GrupObmo();
            $arr_mod = $obmo_model->getObjModuleByGrol($grupo_roles->grol_id);
            $arr_out = array();
            foreach ($arr_mod as $key => $value) {
                $arr_out[] = $value['omod_id'];
            }
            return $this->render('edit', [
                'arr_roles' => (empty(ArrayHelper::map($arr_roles, "rol_id", "rol_nombre"))) ? array(Yii::t("rol", "-- Select Role --")) : (ArrayHelper::map($arr_roles, "rol_id", "rol_nombre")),
                'arr_grupos' => (empty(ArrayHelper::map($arr_grupos, "gru_id", "gru_nombre"))) ? array(Yii::t("grupo", "-- Select Group --")) : (ArrayHelper::map($arr_grupos, "gru_id", "gru_nombre")),
                'arr_objMod' => (empty(ArrayHelper::map($arr_objMod, "omod_id", "omod_nombre"))) ? array(Yii::t("modulo", "-- Select Module --")) : (ArrayHelper::map($arr_objMod, "omod_id", "omod_nombre")),
                'arr_ids' => $arr_out,
                'grol_id' => $id,
                'rol_id' => $grupo_roles->rol_id,
                'gru_id' => $grupo_roles->gru_id,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        $arr_grupos = Grupo::findAll(["gru_estado" => 1, "gru_estado_logico" => 1]);
        //$arr_roles  = Rol::findAll(["rol_estado" => 1, "rol_estado_logico" => 1]);
        list($firstgrupo) = $arr_grupos;
        $arr_roles  = GrupRol::find()
            ->select(["rol.rol_id", "rol.rol_nombre"])
            ->innerJoinWith('rol', 'rol.rol_id = grup_rol.rol_id')
            ->andWhere(["rol.rol_estado" => 1, "rol.rol_estado_logico" => 1,
             "grup_rol.grol_estado" => 1, "grup_rol.grol_estado_logico" => 1, 
             "grup_rol.gru_id" => $firstgrupo->gru_id])->asArray()->all();
        $arr_objMod = ObjetoModulo::find()->select(['omod_id', 'concat(omod_nombre, " (",omod_tipo,")") as omod_nombre'])->where(["omod_estado" => 1, "omod_estado_logico" => 1])->all();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["gru_id"])) {
                $model = new GrupRol();
                $arr_roles = $model->getRolesByGroup($data["gru_id"]);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $arr_roles);
            }
        }
        return $this->render('new', [
            'arr_roles' => (empty(ArrayHelper::map($arr_roles, "rol_id", "rol_nombre"))) ? array(Yii::t("rol", "-- Select Role --")) : (ArrayHelper::map($arr_roles, "rol_id", "rol_nombre")),
            'arr_grupos' => (empty(ArrayHelper::map($arr_grupos, "gru_id", "gru_nombre"))) ? array(Yii::t("grupo", "-- Select Group --")) : (ArrayHelper::map($arr_grupos, "gru_id", "gru_nombre")),
            'arr_objMod' => (empty(ArrayHelper::map($arr_objMod, "omod_id", "omod_nombre"))) ? array(Yii::t("modulo", "-- Select Module --")) : (ArrayHelper::map($arr_objMod, "omod_id", "omod_nombre")),
        ]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $gru_id  = $data["grupo"];
                $rol_id  = $data["rol"];
                $objMods = $data["objmod"];
                $estado  = "1";//$data["estado"];
                $model_GruRol = GrupRol::findOne(['grol_estado'=>'1', 'grol_estado_logico'=>'1', 'rol_id' => $rol_id, 'gru_id' => $gru_id]);
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if(is_array($objMods) && count($objMods) > 0){
                    foreach ($objMods as $key => $value) {
                        $model_grupObmo = new GrupObmo();
                        $model_grupObmo->gru_id = $gru_id;
                        $model_grupObmo->omod_id = $value;
                        $model_grupObmo->gmod_estado = $estado;
                        $model_grupObmo->gmod_estado_logico = '1';
                        if (!$model_grupObmo->save()) {
                            throw new Exception('Error Creando GrupObmo.');
                        }else{
                            $model_gogrol = new GrupObmoGrupRol();
                            $model_gogrol->gmod_id = $model_grupObmo->gmod_id;
                            $model_gogrol->grol_id = $model_GruRol->grol_id;
                            $model_gogrol->gogr_estado = $estado;
                            $model_gogrol->gogr_estado_logico = '1';
                            if (!$model_gogrol->save()) {
                                throw new Exception('Error Creando GrupObmoGrupRol.');
                            }
                        }
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }else{
                    throw new Exception('Error Arreglo Vacio.');
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
                $grol_id = $data["id"];
                $gru_id  = $data["grupo"];
                $rol_id  = $data["rol"];
                $objMods = $data["objmod"];
                $estado  = "1";//$data["estado"];
                $model_GruRol = GrupRol::findOne(['grol_estado'=>'1', 'grol_estado_logico'=>'1', 'rol_id' => $rol_id, 'gru_id' => $gru_id]);
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if(is_array($objMods) && count($objMods) > 0){
                    $grupmod_arr = GrupObmo::findAll(['gru_id' => $gru_id, 'gmod_estado'=>'1', 'gmod_estado_logico'=>'1']);
                    foreach ($grupmod_arr as $key => $value) {
                        $mod_omod = GrupObmo::findOne($value["gmod_id"]);
                        $mod_omod->gmod_estado = '0';
                        if (!$mod_omod->save()) {
                            throw new Exception('Error Desactivando GrupObmo.');
                        }else{
                            $gogrmod_arr = GrupObmoGrupRol::findAll(['grol_id' => $grol_id, 'gogr_estado'=>'1', 'gogr_estado_logico'=>'1']);
                            foreach ($gogrmod_arr as $key2 => $value2){
                                $mod_gogr = GrupObmoGrupRol::findOne($value2["gogr_id"]);
                                $mod_gogr->gogr_estado = '0';
                                if (!$mod_gogr->save()) {
                                    throw new Exception('Error Desactivando GrupObmoGrupRol.');
                                }
                            }
                        }
                    }
                    foreach ($objMods as $key3 => $value3) {
                        $mod_gmod = GrupObmo::findOne(["gru_id" => $gru_id, "omod_id" => $value3, 'gmod_estado_logico'=>'1']);
                        if (is_null($mod_gmod)) {
                            $model_grupObmo = new GrupObmo();
                            $model_grupObmo->gru_id = $gru_id;
                            $model_grupObmo->omod_id = $value3;
                            $model_grupObmo->gmod_estado = $estado;
                            $model_grupObmo->gmod_estado_logico = '1';
                            if (!$model_grupObmo->save()) {
                                throw new Exception('Error Creando GrupObmo.');
                            }else{
                                $gogrmod_arr = GrupObmoGrupRol::findOne(["gmod_id" => $model_grupObmo->gmod_id,'grol_id' => $grol_id, 'gogr_estado_logico'=>'1']);
                                if(is_null($gogrmod_arr)){
                                    $model_gogrol = new GrupObmoGrupRol();
                                    $model_gogrol->gmod_id = $model_grupObmo->gmod_id;
                                    $model_gogrol->grol_id = $model_GruRol->grol_id;
                                    $model_gogrol->gogr_estado = $estado;
                                    $model_gogrol->gogr_estado_logico = '1';
                                    if (!$model_gogrol->save()) {
                                        throw new Exception('Error Creando GrupObmoGrupRol.');
                                    }
                                }else{
                                    $gogrmod_arr->gogr_estado = '1';
                                    if (!$gogrmod_arr->save()) {
                                        throw new Exception('Error Actualizando GrupObmoGrupRol.');
                                    }
                                }
                            } 
                        }else{
                            $mod_gmod->gmod_estado = '1';
                            if (!$mod_gmod->save()) {
                                throw new Exception('Error Actualizando GrupObmo.');
                            }else{
                                $gogrmod_arr = GrupObmoGrupRol::findOne(["gmod_id" => $mod_gmod->gmod_id,'grol_id' => $grol_id, 'gogr_estado_logico'=>'1']);
                                if(is_null($gogrmod_arr)){
                                    $model_gogrol = new GrupObmoGrupRol();
                                    $model_gogrol->gmod_id = $mod_gmod->gmod_id;
                                    $model_gogrol->grol_id = $grol_id;
                                    $model_gogrol->gogr_estado = $estado;
                                    $model_gogrol->gogr_estado_logico = '1';
                                    if (!$model_gogrol->save()) {
                                        throw new Exception('Error Creando GrupObmoGrupRol.');
                                    }
                                }else{
                                    $gogrmod_arr->gogr_estado = '1';
                                    if (!$gogrmod_arr->save()) {
                                        throw new Exception('Error Actualizando GrupObmoGrupRol.');
                                    }
                                }
                            }
                        }
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }else{
                    throw new Exception('Error Arreglo Vacio.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). $ex->getMessage(),
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
                $id = $data["id"]; //GROL
                $gruprol_model = GrupRol::findOne($id);
                $gru_id = $gruprol_model->gru_id;
                $rol_id = $gruprol_model->rol_id;
                $arr_ggor = GrupObmoGrupRol::findAll(["grol_id"=>$id, 'gogr_estado_logico'=>'1', 'gogr_estado'=>'1']);
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                foreach ($arr_ggor as $key => $value){
                    $mod_gogr = GrupObmoGrupRol::findOne($value["gogr_id"]);
                    $mod_gogr->gogr_estado = '0';
                    $mod_gogr->gogr_estado_logico = '0';
                    if ($mod_gogr->update() !== false) {
                        $mod_obmo = GrupObmo::findOne($mod_gogr->gmod_id);
                        $mod_obmo->gmod_estado = '0';
                        $mod_obmo->gmod_estado_logico = '0';
                        if(!($mod_obmo->update() !== false)){
                            throw new Exception('Error Gogr no ha sido eliminado.');
                        }
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    } else {
                        throw new Exception('Error obmo no ha sido eliminado.');
                    }
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
