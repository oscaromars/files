<?php
namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\MateriaParaleloPeriodoSearch;
use app\modules\academico\models\MateriaParaleloPeriodo;
use app\modules\Academico\Module as Academico;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;

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
       public function actionEditparalelo()
   {
          
       return ArrayHelper::merge(parent::actions(), [
           'editparalelo' => [                                       // identifier for your editable column action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => MateriaParaleloPeriodoSearch::className(),                // the model for the record being edited
               'outputValue' => function ($model, $attribute, $key, $index) {
            \app\models\Utilities::putMessageLogFile("EditableColumnAction ".  $model->mpp_num_paralelo ); 
                     return  $model->mpp_num_paralelo;      // return any custom output value if desired
               },
               'outputMessage' => function($model, $attribute, $key, $index) {
                     return 'error';                                  // any custom error to return after model save
               },
               'showModelErrors' => true,                        // show model validation errors after save
               'errorOptions' => ['header' => '']                // error summary HTML options
               // 'postOnly' => true,
               // 'ajaxOnly' => true,
               // 'findModel' => function($id, $action) {},
               // 'checkAccess' => function($action, $model) {}
           ]
       ]);
   }
    
}