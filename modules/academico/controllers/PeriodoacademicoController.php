<?php

namespace app\modules\academico\controllers;

use app\models\Utilities;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\PeriodoAcademicoSearch;
use app\modules\Academico\Module as Academico;
use Yii;
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

	public function actionSaveperiodo() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$usu_id = @Yii::$app->session->get("PB_iduser");
			try {

				$periodo_model = new PeriodoAcademico();
				$periodo_model->saca_id = $data["saca_id"];
				$periodo_model->baca_id = $data["baca_id"];
				$periodo_model->paca_activo = $data["paca_activo"];
				$periodo_model->paca_fecha_inicio = $data["paca_fecha_inicio"];
				$periodo_model->paca_fecha_fin = $data["paca_fecha_fin"];
				$periodo_model->paca_fecha_cierre_ini = $data["paca_fecha_cierre_ini"];
				if ($data["paca_activo"] != "C") {
					$periodo_model->paca_fecha_cierre_fin = $data["paca_fecha_cierre_fin"];
				} else {
					$periodo_model->paca_fecha_cierre_fin = date(Yii::$app->params["dateTimeByDefault"]);
				}
				$periodo_model->paca_semanas_periodo = $data["paca_semanas_periodo"];
				$periodo_model->paca_semanas_inv_vinc_tuto = $data["paca_semanas_inv_vinc_tuto"];
				$periodo_model->paca_usuario_ingreso = $usu_id;
				$periodo_model->paca_estado = "1";
				$periodo_model->paca_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
				$periodo_model->paca_estado_logico = "1";

				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
					"title" => Yii::t('jslang', 'Success'),
				);
				if ($periodo_model->save()) {
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
				} else {
					throw new Exception('Error Bloque Académico no creado.');
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

	public function actionUpdateperiodo() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$usu_id = @Yii::$app->session->get("PB_iduser");
			try {
				$periodo_model = new PeriodoAcademico();
				$periodo_model = PeriodoAcademico::findOne(['paca_id' => $data['id'], 'paca_estado' => 1, 'paca_estado_logico' => 1]);
				$periodo_model->saca_id = $data["saca_id"];
				$periodo_model->baca_id = $data["baca_id"];
				$periodo_model->paca_activo = $data["paca_activo"];
				$periodo_model->paca_fecha_inicio = $data["paca_fecha_inicio"];
				$periodo_model->paca_fecha_fin = $data["paca_fecha_fin"];
				$periodo_model->paca_fecha_cierre_ini = $data["paca_fecha_cierre_ini"];
				if ($data["paca_activo"] != "C") {
					$periodo_model->paca_fecha_cierre_fin = $data["paca_fecha_cierre_fin"];
				} else {
					$periodo_model->paca_fecha_cierre_fin = date(Yii::$app->params["dateTimeByDefault"]);
				}
				$periodo_model->paca_semanas_periodo = $data["paca_semanas_periodo"];
				$periodo_model->paca_semanas_inv_vinc_tuto = $data["paca_semanas_inv_vinc_tuto"];
				$periodo_model->paca_usuario_modifica = $usu_id;
				$periodo_model->paca_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Se ha actualizado el Bloque Académico."),
					"title" => Yii::t('jslang', 'Success'),
				);
				if ($periodo_model->update() !== false) {
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
				} else {
					throw new Exception('Error Bloque Académico no ha sido actializado.');
				}
			} catch (Exception $ex) {
				$message = array(
					"wtmessage" => Yii::t('notificaciones', 'Error al Actualizar. Please try again.'),
					"title" => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

}
