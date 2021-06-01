<?php

namespace app\modules\academico\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use yii\base\Exception;
use app\modules\academico\models\DistributivoAcademicoHorarioSearch;
use app\modules\academico\models\DistributivoAcademicoHorario;
use app\modules\Academico\Module as Academico;

Academico::registerTranslations();

class DistributivoacademicohorarioController extends \app\components\CController {

    public function actionIndex() {

        $searchModel = new DistributivoAcademicoHorarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index',
                        ['searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Updates an existing Notas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['create', 'eaca_id' => $model->eaca_id]);
            $searchModel = new DistributivoAcademicoHorarioSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('update', ['model' => $model,]);
    }
    
    /**
     * Creates a new Alumno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new DistributivoAcademicoHorario();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $searchModel = new DistributivoAcademicoHorarioSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }
/**
     * Finds the Notas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DistributivoAcademicoHorario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
