<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\MateriaParaleloPeriodoSearch;
use app\modules\academico\models\MateriaParaleloPeriodo;
use app\modules\Academico\Module as Academico;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;
use app\models\Utilities;

Academico::registerTranslations();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MateriaparaleloperiodoController extends \app\components\CController {

    /**
     * Lists all SemestreAcademicoSearch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MateriaParaleloPeriodoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all SemestreAcademicoSearch models.
     * @return mixed
     */
    public function actionNew() {
        $searchModel = new MateriaParaleloPeriodoSearch();
        $dataProvider = $searchModel->searchAsinaturas(Yii::$app->request->queryParams);

        return $this->render('new', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Lists all SemestreAcademicoSearch models.
     * @return mixed
     */
    public function actionUpdate($mod_id,$paca_id,$asi_id)
    {//asi_id', 'mod_id', 'paca_id','mpp_num_paralelo'
       $model = MateriaParaleloPeriodo::find()->where(" asi_id=:asi_id and mod_id=:mod_id and paca_id=:paca_id ",[":asi_id"=>$asi_id,":mod_id"=>$mod_id,":paca_id"=>$paca_id])->orderBy("mpp_num_paralelo DESC")->one();

        return $this->render('update', [
            'model' => $model,

        ]);
    }

    public function actionActualizar(){
            \app\models\Utilities::putMessageLogFile("Actualizar MateriaparaleloperiodoController: ".$data['num_paralelos']);
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $mes = 0;
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $con = \Yii::$app->db_academico;

            $transaction = $con->beginTransaction();
           // $datos = $data["data"];
            // $datos = json_decode($dts);

             //   \app\models\Utilities::putMessageLogFile($datos[$i]['asig_id']);
                for ($j = $data['mpp_num_paralelo']; $j <= $data['num_paralelos']; $j++) {
                    $model = new MateriaParaleloPeriodo();
                    $model->paca_id = $data['paca_id'];
                    $model->asi_id = $data['asig_id'];
                    $model->mod_id = $data['mod_id'];
                    $model->mpp_num_paralelo = $j;
                    $model->mpp_usuario_ingreso = $usu_id;
                    $model->mpp_estado = '1';
                    $model->mpp_fecha_creacion = $fecha_transaccion;
                    $model->mpp_estado_logico = '1';
                    if ($model->save()) {
                        $mes++;
                    }
                }


            if ($mes != 0) {
                $transaction->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            } else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Su información no ha sido grabada. Por favor intente nuevamente o contacte al área de DesarrolloXXX.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
      }
    /**
     * Finds the Rol model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rol the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MateriaParaleloPeriodo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //// action for gridview
    public function actionSave() {
   //     \app\models\Utilities::putMessageLogFile("Save MateriaparaleloperiodoController");
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $mes = 0;
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $con = \Yii::$app->db_academico;

            $transaction = $con->beginTransaction();
            $datos = $data["data"];
            // $datos = json_decode($dts);
            for ($i = 0; $i < sizeof($datos); $i++) {
             //   \app\models\Utilities::putMessageLogFile($datos[$i]['asig_id']);
                if($datos[$i]['numero_paralelo']>0){
                for ($j = 1; $j <= $datos[$i]['numero_paralelo']; $j++) {
                    $model = new MateriaParaleloPeriodo();
                    $model->paca_id = $datos[$i]['paca_id'];
                    $model->asi_id = $datos[$i]['asig_id'];
                    $model->mod_id = $datos[$i]['mod_id'];
                    $model->mpp_num_paralelo = $j;
                    $model->mpp_usuario_ingreso = $usu_id;
                    $model->mpp_estado = '1';
                    $model->mpp_fecha_creacion = $fecha_transaccion;
                    $model->mpp_estado_logico = '1';
                    if ($model->save()) {
                        $mes++;
                    }
                }
            }
            }
            if ($mes != 0) {
                $transaction->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            } else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Su información no ha sido grabada. Por favor intente nuevamente o contacte al área de DesarrolloXXX.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    //// action for gridview
    public function actionEditableupdate() {


        /* return ArrayHelper::merge(parent::actions(), [
          'editableupdate' => [                                       // identifier for your editable action
          'class' => EditableColumnAction::className(),     // action class name
          'modelClass' => MateriaParaleloPeriodo::className(),                // the update model class
          'outputValue' => function ($model, $attribute, $key, $index)
          {                 return  4; }
          ]
          ]); */

        if (Yii::$app->request->post('hasEditable')) {
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // Grab existing model
            // $id     = Yii::$app->request->post('id');                // when not using Kartik's GridView. Requires passing 'id' hidden field.
            $id = Yii::$app->request->post('editableKey');       // built-in to Kartik's GridView
            $attrib = Yii::$app->request->post('editableAttribute');
            //  $model  = $this->findModel($id);
            $model = new MateriaParaleloPeriodo();
            $model->mpp_id = $id;
            //$model->paca_id = 3;
            // $model->mpp_num_paralelo=0;
            // Grab the post parameters array (as attribute => value)
            // $_POST == [
            //     [_csrf]             => $csrf
            //     [hasEditable]       => 1
            //     [editableIndex]     => 0
            //     [editableKey]       => 1      // recid
            //     [editableAttribute] => tags   // fieldname
            //     [id]                => 1
            //     [Resource]          => [[0] => [[tags] => sermón]]
            // ]
            $modelClassName = \yii\helpers\StringHelper::basename(get_class($model));
            $params[$modelClassName] = Yii::$app->request->post($modelClassName)[0];
            $model->load($params);
            // Save posted model attributes
            //if ($model->load($params) && $model->save()) {
            //\app\models\Utilities::putMessageLogFile($params);
            // Pull the first value from the array (there should only be one)
            $value = reset($params)[$attrib];

            // Return JSON encoded output in the below format
            //return ['output' => $value , 'message' => 'value=' . print_r($value, true) . ', ' . print_r(Yii::$app->request->post(), true)];
            return ['output' => $value];

            /*  } else {
              // Else if nothing to do always return an empty JSON encoded output.
              // Alternatively, return a validation error.
              //return ['output' => '', 'message' => 'Failed: PARAMS: ' . print_r($params, true) . ', POST: ' . print_r(Yii::$app->request->post(), true)];
              return ['output' => '', 'message' => 'Failed to validate or save'];
              } */
        }


        /* return ArrayHelper::merge(parent::actions(), [
          'editableupdate' => [                                       // identifier for your editable column action
          'class' => EditableColumnAction::className(),     // action class name
          'modelClass' => MateriaParaleloPeriodo::className(),                // the model for the record being edited
          'outputValue' => function ($model, $attribute, $key, $index) {
          \app\models\Utilities::putMessageLogFile("EditableColumnAction ".  $attribute );
          $value = $model->$attribute;


          // selective validation by attribute
          //return $fmt->asDecimal($value, 2);     // return formatted value if desired
          return $value;
          // empty is same as $value
          },
          'outputMessage' => function($model, $attribute, $key, $index) {
          return 'error';                                  // any custom error to return after model save
          },
          'showModelErrors' => true,                        // show model validation errors after save
          'errorOptions' => ['header' => 'Error de campos']                // error summary HTML options
          // 'postOnly' => true,
          // 'ajaxOnly' => true,
          // 'findModel' => function($id, $action) {},
          // 'checkAccess' => function($action, $model) {}
          ]
          ]); */
    }

    public function actionUpdateschedule($mod_id,$paca_id,$asi_id)
    {//asi_id', 'mod_id', 'paca_id','mpp_num_paralelo'
       $model = MateriaParaleloPeriodo::find()->where(" asi_id=:asi_id and mod_id=:mod_id and paca_id=:paca_id ",[":asi_id"=>$asi_id,":mod_id"=>$mod_id,":paca_id"=>$paca_id])->orderBy("mpp_num_paralelo DESC")->one();

        return $this->render('updateschedule', [
            'model' => $model,

        ]);
    }

}
