<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use app\models\Grupo;
use app\models\Rol;
use app\models\Modulo;
use app\models\Aplicacion;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Utilities;

class ModulosController extends CController
{
    public function actionIndex() {
        $modulo_model = new Modulo();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid',[
                    "model" => $modulo_model->getAllModules($data["search"], true)
                ]);
            }
        }
        return $this->render('index', [
            'model' => $modulo_model->getAllModules(NULL, true)
        ]);
    }
    
    public function actionView()
    {
        $data = Yii::$app->request->get();
        if(isset($data['id'])){
            $id = $data['id'];
            return $this->render('view', [
                'model' => Modulo::findIdentity($id)
            ]);
        }
        return $this->redirect('index');
    }
    
     public function actionEdit()
    {
        $data = Yii::$app->request->get();
        if(isset($data['id'])){
            $id = $data['id'];
            $arr_aplications = Aplicacion::findAll(["apl_estado" => 1, "apl_estado_logico" => 1]);
            return $this->render('edit', [
                'model' => Modulo::findIdentity($id),
                'arr_aplications' => (empty(ArrayHelper::map($arr_aplications, "apl_id", "apl_nombre"))) ? array(Yii::t("aplicacion", "-- Select Application --")) : (ArrayHelper::map($arr_aplications, "apl_id", "apl_nombre"))
            ]);
        }
        return $this->redirect('index');
    }
    
    public function actionNew() {
        $arr_aplications = Aplicacion::findAll(["apl_estado" => 1, "apl_estado_logico" => 1]);
        return $this->render('new', [
            'arr_aplications' => (empty(ArrayHelper::map($arr_aplications, "apl_id", "apl_nombre"))) ? array(Yii::t("aplicacion", "-- Select Application --")) : (ArrayHelper::map($arr_aplications, "apl_id", "apl_nombre"))
        ]);
    }
    
    public function actionSave()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try{
                $nombre = $data["nombre"];
                $apl_id = $data["apl_id"];
                $tipo   = $data["tipo"];
                $icon   = $data["icon"];
                $url    = $data["url"];
                $orden  = $data["orden"];
                $lang   = $data["lang"];
                $estado = $data["estado"];
                $modulo_model = new Modulo();
                $modulo_model->mod_nombre = $nombre;
                $modulo_model->apl_id = $apl_id;
                $modulo_model->mod_tipo = $tipo;
                $modulo_model->mod_dir_imagen = $icon;
                $modulo_model->mod_url = $url;
                $modulo_model->mod_orden = $orden;
                $modulo_model->mod_lang_file = $lang;
                $modulo_model->mod_estado = $estado;
                $modulo_model->mod_estado_visible = $estado;
                $modulo_model->mod_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if($modulo_model->save()){
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }else{
                    throw new Exception('Error id Modelo no creado.');
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
    
    public function actionUpdate()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            
            try{
                $id     = $data["id"];
                $nombre = $data["nombre"];
                $apl_id = $data["apl_id"];
                $tipo   = $data["tipo"];
                $icon   = $data["icon"];
                $url    = $data["url"];
                $orden  = $data["orden"];
                $lang   = $data["lang"];
                $estado = $data["estado"];
                $modulo_model = Modulo::findOne($id);
                $modulo_model->mod_nombre = $nombre;
                $modulo_model->apl_id = $apl_id;
                $modulo_model->mod_tipo = $tipo;
                $modulo_model->mod_dir_imagen = $icon;
                $modulo_model->mod_url = $url;
                $modulo_model->mod_orden = $orden;
                $modulo_model->mod_lang_file = $lang;
                $modulo_model->mod_estado = $estado;
                $modulo_model->mod_estado_visible = $estado;
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if($modulo_model->update() !== false){
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }else{
                    throw new Exception('Error Modelo no actualizado.');
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
                $modulo_model = Modulo::findOne($id);
                $modulo_model->mod_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($modulo_model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Modelo no ha sido eliminado.');
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
