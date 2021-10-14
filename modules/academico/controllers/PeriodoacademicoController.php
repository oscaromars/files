<?php

namespace app\modules\academico\controllers;

use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\PeriodoAcademicoSearch;
use app\modules\Academico\Module as Academico;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\filters\VerbFilter;

Academico::registerTranslations();

class PeriodoacademicoController extends \app\components\CController {
	/**
	 * Lists all Rol models.
	 * @return mixed
	 */

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	public function actionIndex() {
		$searchModel = new PeriodoAcademicoSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
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
		$validar_periodo = PeriodoAcademico::findOne(['paca_id' => $id, 'paca_estado' => 1, 'paca_estado_logico' => 1]);
		if ($validar_periodo['paca_activo'] == 'A' or $validar_periodo['paca_activo'] == 'I') {
			if ($model->load(Yii::$app->request->post()) && $model->save()) {

				//return $this->redirect(['create', 'eaca_id' => $model->eaca_id]);

				Yii::$app->session->setFlash('success', 'Datos Modificados Correctamente');
				$searchModel = new PeriodoAcademicoSearch();
				$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
				return $this->redirect('/asgard/academico/periodoacademico/index');
				/*return $this->render('index', [
					                                        'searchModel' => $searchModel,
					                                        'dataProvider' => $dataProvider,
				*/
			}

		} else {
			return $this->redirect('/asgard/academico/periodoacademico/index');
		}

		return $this->render('update', ['model' => $model]);
	}

	/**
	 * Creates a new Alumno model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new PeriodoAcademico();
		// Yii::$app->session->setFlash('success', 'prueba');
		if ($model->load(Yii::$app->request->post())) {
			// print_r($model);
			if ($model->savePeriodo($model)) {
				//   if ($model->load(Yii::$app->request->post()) && $model->save()) {
				Yii::$app->session->setFlash('success', 'Datos guardados correctamente');

				return $this->redirect('index');
			} else {
				Yii::$app->session->setFlash('error', 'Datos no guardados');
			}
		}
		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Rol model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		if ($this->findModel($id)->delete()) {
			Yii::$app->session->setFlash('success', 'Registro eliminado correctamente...');
		} else {
			Yii::$app->session->setFlash('error', 'No se pudo eliminar el registro');
		}
		return $this->redirect(['index']);
	}

	/**
	 * Finds the Notas model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Notas the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = PeriodoAcademico::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}

}
