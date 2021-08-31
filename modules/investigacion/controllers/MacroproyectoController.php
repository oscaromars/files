<?php

namespace app\modules\investigacion\controllers;

use Yii;
use app\modules\investigacion\models\LineaInvestigacion;
use app\modules\investigacion\models\Macroproyecto;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\models\Utilities;
use yii\base\Security;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();

/**
 * MacroproyectoController implements the CRUD actions for Macroproyecto model.
 */
class MacroproyectoController extends \app\components\CController
{
    

    /**
     * Macroproyecto index.
     * @autor Luis Cajamarca
     * 
     */
    public function actionIndex()
    {
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id > 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->render('index-out', [
                    "message" => investigacion::t("lineainvestigacion", "Access denied"),
                ]);
            }
        }
        $model_macro = new Macroproyecto();
        $mode_linea = new LineaInvestigacion();
        $data = Yii::$app->request->get();
        
        if ($data['PBgetFilter']) {
            
            $investigacion = $data['linv_id'];
            \app\models\Utilities::putMessageLogFile("investigacion: ".$investigacion);
            $model = $model_macro->consultarMacroproyecto($investigacion);
            return $this->renderPartial('index-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $model_macro->consultarMacroproyecto();
        }

        $arr_linv = LineaInvestigacion::findAll(['linv_estado' => 1, 'linv_estado_logico' => 1]);   
            
        return $this->render('index', [
                    'model' => $model,
                    'arr_linv' => ArrayHelper::map(array_merge([["linv_id" => "0", "linv_nombre_investigacion" => Yii::t("formulario", "Grid")]], $arr_linv), "linv_id", "linv_nombre_investigacion"),


        ]);
    }

    /**
     * Macroproyecto new.
     * @autor Luis Cajamarca
     * 
     */
     public function actionNew() {
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id > 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->redirect('/asgard/investigacion/macroproyecto/index');
            }
        }
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
         $arr_linv = LineaInvestigacion::findAll(['linv_estado' => 1, 'linv_estado_logico' => 1]); 
            return $this->render('new', [
                'arr_linv' => ArrayHelper::map(array_merge([["linv_id" => "0", "linv_nombre_investigacion" => Yii::t("formulario", "Grid")]], $arr_linv), "linv_id", "linv_nombre_investigacion"),
                ]);
    }

    /**
     * Macroproyecto save.
     * @autor Luis Cajamarca
     * 
     */

    public function actionSavereg() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $linv_id = $data['linv_id'];
                $descripcion = $data['descripcion'];
                
                $modelmacro = new Macroproyecto();
                $insert_macro = $modelmacro->insertMacro($linv_id,$descripcion);


                if(!$insert_macro){
                    throw new Exception('Error al Registrar el Macroproyecto');
                }
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (Exception $ex) {
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    'title' => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    /**
     * Macroproyecto view.
     * @autor Luis Cajamarca
     * 
     */
    public function actionView(){
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id > 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->redirect('/asgard/investigacion/macroproyecto/index');
            }
        }
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Macroproyecto::findOne(['mpro_id' => $id, 'mpro_estado' => '1', 'mpro_estado_logico' => '1']);
            $model_linv =LineaInvestigacion::findOne(['linv_id' => $model['linv_id'], 'linv_estado' => '1', 'linv_estado_logico' => '1']);
            return $this->render('view', [
                    'model' => $model,
                    'model_linv' => $model_linv,
    
            ]);
        }
        return $this->redirect('index');
    }

    /**
     * Macroproyecto edit.
     * @autor Luis Cajamarca
     * 
     */

    public function actionEdit() {
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id > 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->redirect('/asgard/investigacion/macroproyecto/index');
            }
        }
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Macroproyecto::findOne(['mpro_id' => $id, 'mpro_estado' => '1', 'mpro_estado_logico' => '1']);
            $model_linv =LineaInvestigacion::findOne(['linv_id' => $model['linv_id'], 'linv_estado' => '1', 'linv_estado_logico' => '1']);
            return $this->render('edit', [
                    'model' => $model,
                    'model_linv' => $model_linv,
    
            ]);
        }
        return $this->redirect('index');
    }



     /**
     * Macroproyecto update.
     * @autor Luis Cajamarca
     * 
     */
    public function actionUpdate()
    {

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $id = $data['id'];
                $nombre_investigacion = $data['nombre_investigacion'];
                $modelpro = new Macroproyecto();
                $model = $modelpro->updateNombreMacroproyecto($id,$nombre_investigacion);
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Your information was successfully saved.'),
                    'title' => Yii::t('jslang', 'Success'),
                );
                if(!$model){
                    throw new Exception('Error al Registrar la línea de investigación');
                }
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (Exception $ex) {
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    'title' => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    /**
     * Macroproyecto eliminar.
     * @autor Luis Cajamarca
     * 
     */
    public function actionDeletereg() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $id = $data['id'];
                $model = Macroproyecto::findOne($id);
                $model->mpro_estado = '0';
                $model->mpro_estado_logico = '0';
                $model->mpro_usuario_modifica = Yii::$app->session->get('PB_iduser');
                $model->mpro_fecha_modificacion = date(Yii::$app->params['dateTimeByDefault']);
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Your information was successfully saved.'),
                    'title' => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error registro no ha sido eliminado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    'title' => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    /**
     * Finds the LineaInvestigacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LineaInvestigacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LineaInvestigacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
