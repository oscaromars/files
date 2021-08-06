<?php

namespace app\modules\investigacion\controllers;

use Yii;
use app\modules\investigacion\models\LineaInvestigacion;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\models\Utilities;
use yii\base\Security;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();

/**
 * LineainvestigacionController implements the CRUD actions for LineaInvestigacion model.
 */
class LineainvestigacionController extends \app\components\CController
{
    

    /**
     * LineaInvestigacion index.
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
        $modelLinv = new LineaInvestigacion();
        $model = $modelLinv->consultarLineaInvestigacion();
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    /**
     * LineaInvestigacion new.
     * @autor Luis Cajamarca
     * 
     */
     public function actionNew() {
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id > 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->redirect('/asgard/investigacion/lineainvestigacion/index');
            }
        }
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
            return $this->render('new', [
                ]);
    }

    /**
     * LineaInvestigacion save.
     * @autor Luis Cajamarca
     * 
     */

    public function actionSavereg() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $nombre_investigacion = $data['nombre_investigacion'];
                
                $modellinv = new LineaInvestigacion();
                $insert_linea = $modellinv->insertLineaInv($nombre_investigacion);


                if(!$insert_linea){
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
     * LineaInvestigacion view.
     * @autor Luis Cajamarca
     * 
     */
    public function actionView(){
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id > 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->redirect('/asgard/investigacion/lineainvestigacion/index');
            }
        }
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = LineaInvestigacion::findOne(['linv_id' => $id, 'linv_estado' => '1', 'linv_estado_logico' => '1',]);
            return $this->render('view', [
                    'model' => $model,
                        
            ]);
        }
        return $this->redirect('index');
    }

    /**
     * LineaInvestigacion edit.
     * @autor Luis Cajamarca
     * 
     */

    public function actionEdit() {
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id > 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->redirect('/asgard/investigacion/lineainvestigacion/index');
            }
        }
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = LineaInvestigacion::findOne(['linv_id' => $id, 'linv_estado' => '1', 'linv_estado_logico' => '1',]);
            return $this->render('edit', [
                    'model' => $model,
    
            ]);
        }
        return $this->redirect('index');
    }


    
     /**
     * LineaInvestigacion update.
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
                $modellinv = new LineaInvestigacion();
                $model = $modellinv->updateNombreLineaInv($id,$nombre_investigacion);
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
     * LineaInvestigacion eliminar.
     * @autor Luis Cajamarca
     * 
     */
    public function actionDeletereg() {
       
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $id = $data['id'];
                $model = LineaInvestigacion::findOne($id);
                $model->linv_estado = '0';
                $model->linv_estado_logico = '0';
                $model->linv_usuario_modifica = Yii::$app->session->get('PB_iduser');
                $model->linv_fecha_modificacion = date(Yii::$app->params['dateTimeByDefault']);
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

    
}
