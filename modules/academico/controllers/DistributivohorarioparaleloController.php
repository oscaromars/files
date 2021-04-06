<?php

namespace app\modules\academico\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use app\modules\academico\models\NivelInstruccion;
use yii\base\Exception;
use app\modules\academico\models\DistributivoHorarioParalelo;
use app\modules\academico\models\DistributivoAcademicoHorarioSearch;
use app\modules\academico\models\DistributivoHorarioParaleloSearch;
use app\modules\Academico\Module as Academico;

Academico::registerTranslations();

class DistributivohorarioparaleloController extends \app\components\CController {
/*
    public function actionIndex() {
        $search = NULL;
        $perfil = '0';

        $dis_hor_par_model = new DistributivoHorarioParalelo();

        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $search = $data["search"];              
            $model = $dis_hor_par_model->getAllDistributivoHorarioParaleloGrid($search, $perfil);
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $model,
                ]);
            }
        } else {
         $model = $dis_hor_par_model->getAllDistributivoHorarioParaleloGrid($search, $perfil);

        return $this->render('index', [
                    'model' => $model,
        ]);   
        }

        
    }*/
    
    
    public function actionIndex() {
        $searchModel = new DistributivoHorarioParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    

    public function actionView($id) {
        $searchModel = new DistributivoHorarioParaleloSearch();
        $data = $searchModel->searchByDaho($id);

        return $this->render('view', [
                           'data' => $data,
        ]);
    }


    public function actionNew() {

        $searchModel = new DistributivoAcademicoHorarioSearch();
        $model = new DistributivoHorarioParalelo();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('new',
                        [   'searchModel' => $searchModel,
                            'dataProvider'=> $dataProvider,
                            'model'       => $model,]);
    }

    public function actionCreate() {
        $model = new DistributivoHorarioParalelo();
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $searchModel = new DistributivoHorarioParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
        }
        return $this->render('create',
                        ['model' => $model,]);
    }

    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $searchModel = new DistributivoHorarioParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
        }

        return $this->render('update', ['model' => $model, ]);
    }
    
    /**
     * Finds the Notas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DistributivoHorarioParalelo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    protected function findModelDahoid($id)
    {
        $sql = "select * from db_academico.distributivo_horario_paralelo where daho_id =".$id;
        if (($model = DistributivoHorarioParalelo::findBySql($sql)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}
