<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Grupo;
use app\models\Rol;
use app\models\Modulo;
use app\models\ObmoAcci;
use app\models\Accion;
use app\models\ObjetoModulo;
use app\models\Aplicacion;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class ObjetomodulosController extends CController {

    public function actionIndex() {
        $omodulo_model = new ObjetoModulo();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $omodulo_model->getAllObjModules($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
                    'model' => $omodulo_model->getAllObjModules(NULL, true)
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_modulos = Modulo::findAll(["mod_estado" => 1, "mod_estado_logico" => 1]);
            $arr_ObjetoModulos = ObjetoModulo::findAll(["omod_estado" => 1, "omod_estado_logico" => 1]);
            $arr_typeObjMod = ObjetoModulo::getAllTypesObjModules();
            $arr_typeBtnObjMod = ObjetoModulo::getAllTypesBtnObjModules();
            $arr_Acciones = Accion::findAll(["acc_estado" => 1, "acc_estado_logico" => 1]);
            return $this->render('view', [
                'model' => ObjetoModulo::findIdentity($id),
                'modelooacc' => ObmoAcci::findOne(["omod_id" => $id, "oacc_estado" => 1, "oacc_estado_logico" => 1]),
                'arr_modulos' => (empty(ArrayHelper::map($arr_modulos, "mod_id", "mod_nombre"))) ? array(Yii::t("modulo", "-- Select Module --")) : (ArrayHelper::map($arr_modulos, "mod_id", "mod_nombre")),
                'arr_omodulos' => (empty(ArrayHelper::map($arr_ObjetoModulos, "omod_id", "omod_nombre"))) ? array(Yii::t("modulo", "-- Select SubModule Main --")) : (ArrayHelper::map($arr_ObjetoModulos, "omod_id", "omod_nombre")),
                'arr_typeObjMod' => $arr_typeObjMod,
                'arr_typeBtnObjMod' => $arr_typeBtnObjMod,
                'arr_acciones' => (empty(ArrayHelper::map($arr_Acciones, "acc_id", "acc_nombre"))) ? array(Yii::t("accion", "-- Select Action --")) : (ArrayHelper::map($arr_Acciones, "acc_id", "acc_nombre")),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $arr_modulos = Modulo::findAll(["mod_estado" => 1, "mod_estado_logico" => 1]);
            $arr_ObjetoModulos = ObjetoModulo::find()->select(['omod_id', 'concat(omod_nombre, " (",omod_tipo,")") as omod_nombre'])->andWhere('omod_tipo <> "A" and omod_estado = "1" and omod_estado_logico = "1"')->all();
            //$arr_ObjetoModulos = ObjetoModulo::find(["omod_estado" => 1, "omod_estado_logico" => 1])->andWhere('omod_tipo <> "A"')->all();
            $arr_typeObjMod = ObjetoModulo::getAllTypesObjModules();
            $arr_typeBtnObjMod = ObjetoModulo::getAllTypesBtnObjModules();
            $arr_Acciones = Accion::findAll(["acc_estado" => 1, "acc_estado_logico" => 1]);
            return $this->render('edit', [
                'model' => ObjetoModulo::findIdentity($id),
                'modelooacc' => ObmoAcci::findOne(["omod_id" => $id, "oacc_estado" => 1, "oacc_estado_logico" => 1]),
                'arr_modulos' => (empty(ArrayHelper::map($arr_modulos, "mod_id", "mod_nombre"))) ? array(Yii::t("modulo", "-- Select Module --")) : (ArrayHelper::map($arr_modulos, "mod_id", "mod_nombre")),
                'arr_omodulos' => (empty(ArrayHelper::map($arr_ObjetoModulos, "omod_id", "omod_nombre"))) ? array(Yii::t("modulo", "-- Select SubModule Main --")) : (ArrayHelper::map($arr_ObjetoModulos, "omod_id", "omod_nombre")),
                'arr_typeObjMod' => $arr_typeObjMod,
                'arr_typeBtnObjMod' => $arr_typeBtnObjMod,
                'arr_acciones' => (empty(ArrayHelper::map($arr_Acciones, "acc_id", "acc_nombre"))) ? array(Yii::t("accion", "-- Select Action --")) : (ArrayHelper::map($arr_Acciones, "acc_id", "acc_nombre")),
            ]);
        }
        return $this->redirect('index');
    }

    public function actionNew() {
        $arr_modulos = Modulo::findAll(["mod_estado" => 1, "mod_estado_logico" => 1]);
        $arr_ObjetoModulos = ObjetoModulo::find()->select(['omod_id', 'concat(omod_nombre, " (",omod_tipo,")") as omod_nombre'])->andWhere('omod_tipo <> "A" and omod_estado = "1" and omod_estado_logico = "1"')->all();
        //$arr_ObjetoModulos = ObjetoModulo::find(["omod_estado" => 1, "omod_estado_logico" => 1])->andWhere('omod_tipo <> "A" and omod_estado_logico = "1"')->all();
        $arr_typeObjMod = ObjetoModulo::getAllTypesObjModules();
        $arr_typeBtnObjMod = ObjetoModulo::getAllTypesBtnObjModules();
        $arr_Acciones = Accion::findAll(["acc_estado" => 1, "acc_estado_logico" => 1]);
        return $this->render('new', [
            'arr_modulos' => (empty(ArrayHelper::map($arr_modulos, "mod_id", "mod_nombre"))) ? array(Yii::t("modulo", "-- Select Module --")) : (ArrayHelper::map($arr_modulos, "mod_id", "mod_nombre")),
            'arr_omodulos' => (empty(ArrayHelper::map($arr_ObjetoModulos, "omod_id", "omod_nombre"))) ? array(Yii::t("modulo", "-- Select SubModule Main --")) : (ArrayHelper::map($arr_ObjetoModulos, "omod_id", "omod_nombre")),
            'arr_typeObjMod' => $arr_typeObjMod,
            'arr_typeBtnObjMod' => $arr_typeBtnObjMod,
            'arr_acciones' => (empty(ArrayHelper::map($arr_Acciones, "acc_id", "acc_nombre"))) ? array(Yii::t("accion", "-- Select Action --")) : (ArrayHelper::map($arr_Acciones, "acc_id", "acc_nombre")),
        ]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $nombre = $data["nombre"];
                $mod_id = $data["mod_id"];
                $omod_padre = $data["omod_padre"];
                $btn = $data["btn"];
                $acc = $data["acc"];
                $fn = $data["fn"];
                $tipo = $data["tipo"];
                $icon = $data["icon"];
                $url = $data["url"];
                $orden = $data["orden"];
                $lang = $data["lang"];
                $estado = $data["estado"];
                $visible = $data["visible"];
                $acc_id = $data["acc_type"];
                $type_btn = $data["type_btn"];
                $acc_fn = $data["acc_fn"];
                $acc_lk = $data["acc_lk"];
                $omodulo_model = new ObjetoModulo();
                $omodulo_model->omod_nombre = $nombre;
                $omodulo_model->mod_id = $mod_id;
                $omodulo_model->omod_padre_id = $omod_padre;
                $omodulo_model->omod_tipo_boton = ObjetoModulo::$typeBtnObjMod[$btn];
                $omodulo_model->omod_accion = $acc;
                $omodulo_model->omod_function = $fn;
                $omodulo_model->omod_tipo = ObjetoModulo::$typeObjMod[$tipo];
                $omodulo_model->omod_dir_imagen = $icon;
                $omodulo_model->omod_entidad = $url;
                $omodulo_model->omod_orden = $orden;
                $omodulo_model->omod_lang_file = $lang;
                $omodulo_model->omod_estado = $estado;
                $omodulo_model->omod_estado_visible = $visible;
                $omodulo_model->omod_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($omodulo_model->save()) {
                    if(ObjetoModulo::$typeObjMod[$tipo] == "A"){
                        $obmoAcci_model = new ObmoAcci();
                        $obmoAcci_model->acc_id = $acc_id;
                        $obmoAcci_model->omod_id = $omodulo_model->omod_id;
                        $obmoAcci_model->oacc_tipo_boton = ObjetoModulo::$typeBtnObjMod[$type_btn];
                        $obmoAcci_model->oacc_cont_accion = $acc_lk;
                        $obmoAcci_model->oacc_function = $acc_fn;
                        $obmoAcci_model->oacc_estado_logico = "1";
                        $obmoAcci_model->oacc_estado = "1";
                        if (!$obmoAcci_model->save()) {
                            throw new Exception('Error ObmoAcci no creado.');
                        }
                    }else if(ObjetoModulo::$typeObjMod[$tipo] == "P"){
                        $omodulo_model->omod_padre_id = $omodulo_model->omod_id;
                        if (!$omodulo_model->save()) {
                            throw new Exception('Error ObmoAcci no Principal.');
                        }
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error id Modelo no creado.');
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
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
                $mod_id = $data["mod_id"];
                $omod_padre = $data["omod_padre"];
                $btn = $data["btn"];
                $acc = $data["acc"];
                $fn = $data["fn"];
                $tipo = $data["tipo"];
                $icon = $data["icon"];
                $url = $data["url"];
                $orden = $data["orden"];
                $lang = $data["lang"];
                $estado = $data["estado"];
                $visible = $data["visible"];
                $acc_id = $data["acc_type"];
                $type_btn = $data["type_btn"];
                $acc_fn = $data["acc_fn"];
                $acc_lk = $data["acc_lk"];
                $omodulo_model = ObjetoModulo::findOne($id);
                $omodulo_model->omod_nombre = $nombre;
                $omodulo_model->mod_id = $mod_id;
                $omodulo_model->omod_padre_id = $omod_padre;
                $omodulo_model->omod_tipo_boton = ObjetoModulo::$typeBtnObjMod[$btn];
                $omodulo_model->omod_accion = $acc;
                $omodulo_model->omod_function = $fn;
                $omodulo_model->omod_tipo = ObjetoModulo::$typeObjMod[$tipo];
                $omodulo_model->omod_dir_imagen = $icon;
                $omodulo_model->omod_entidad = $url;
                $omodulo_model->omod_orden = $orden;
                $omodulo_model->omod_lang_file = $lang;
                $omodulo_model->omod_estado = $estado;
                $omodulo_model->omod_estado_visible = $visible;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($omodulo_model->update() !== false) {
                    $obmoAcci_model = ObmoAcci::findOne(["omod_id" => $omodulo_model->omod_id, "oacc_estado_logico" => "1"]);
                    if(ObjetoModulo::$typeObjMod[$tipo] == "A"){
                        $obmoAcci_model = ObmoAcci::findOne(["omod_id" => $omodulo_model->omod_id, "oacc_estado_logico" => "1", "oacc_estado" => "1"]);
                        if(!$obmoAcci_model){
                            $obmoAcci_model = new ObmoAcci();
                        }
                        $obmoAcci_model->acc_id = $acc_id;
                        $obmoAcci_model->omod_id = $omodulo_model->omod_id;
                        $obmoAcci_model->oacc_tipo_boton = ObjetoModulo::$typeBtnObjMod[$type_btn];
                        $obmoAcci_model->oacc_cont_accion = $acc_lk;
                        $obmoAcci_model->oacc_function = $acc_fn;
                        $obmoAcci_model->oacc_estado_logico = "1";
                        $obmoAcci_model->oacc_estado = "1";
                        if (!$obmoAcci_model->save()) {
                            throw new Exception('Error ObmoAcci no actualizado.');
                        }
                    }else{
                        if(isset($obmoAcci_model)){
                            $obmoAcci_model->oacc_estado = "0";
                            if (!$obmoAcci_model->save()) {
                                throw new Exception('Error ObmoAcci no desactivado.');
                            }
                        }
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Modelo no actualizado.');
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
    
    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $id = $data["id"];
                $omodulo_model = ObjetoModulo::findOne($id);
                $omodulo_model->omod_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($omodulo_model->update() !== false) {
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
