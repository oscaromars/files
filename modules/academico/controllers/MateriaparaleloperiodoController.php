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
    public function actionIndex()
    {
        $searchModel = new MateriaParaleloPeriodoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
       return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);  
    }
    
      //// action for gridview
       public function actionSave()
   {
          
           
       }
    //// action for gridview
       public function actionEditableupdate()
   {
          \app\models\Utilities::putMessageLogFile("entro: new " );
          
         /* return ArrayHelper::merge(parent::actions(), [
           'editableupdate' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => MateriaParaleloPeriodo::className(),                // the update model class
             'outputValue' => function ($model, $attribute, $key, $index) 
          {                 return  4; } 
               ]
        ]);*/
          
           if (Yii::$app->request->post('hasEditable')) {
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
 
            // Grab existing model
           // $id     = Yii::$app->request->post('id');                // when not using Kartik's GridView. Requires passing 'id' hidden field.
            $id     = Yii::$app->request->post('editableKey');       // built-in to Kartik's GridView
            $attrib = Yii::$app->request->post('editableAttribute');
          //  $model  = $this->findModel($id);
                $model= new MateriaParaleloPeriodo();
                $model->mpp_id=$id;
                $model->paca_id=3;
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
 
            // Save posted model attributes
            //if ($model->load($params) && $model->save()) {
 
                // Pull the first value from the array (there should only be one)
                $value = reset($params)[$attrib];
 
                // Return JSON encoded output in the below format
                //return ['output' => $value , 'message' => 'value=' . print_r($value, true) . ', ' . print_r(Yii::$app->request->post(), true)];
                return ['output' => $value ];
 
          /*  } else {
                // Else if nothing to do always return an empty JSON encoded output.
                // Alternatively, return a validation error.
                //return ['output' => '', 'message' => 'Failed: PARAMS: ' . print_r($params, true) . ', POST: ' . print_r(Yii::$app->request->post(), true)];
                return ['output' => '', 'message' => 'Failed to validate or save'];
            }*/
        }
    
          
        /*return ArrayHelper::merge(parent::actions(), [
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
       ]);*/
   }
    
}