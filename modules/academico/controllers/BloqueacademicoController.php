<?php

namespace app\modules\academico\controllers;

use app\modules\academico\models\BloqueAcademico;
use app\modules\academico\models\BloqueAcademicoSearch;
use app\modules\Academico\Module as Academico;
use Yii;
use yii\filters\VerbFilter;

Academico::registerTranslations();

/**
 * RolController implements the CRUD actions for Rol model.
 */
class BloqueacademicoController extends \app\components\CController {
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

	/**
	 * Lists all BloqueAcademicoSearch models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new BloqueAcademicoSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Rol model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Rol model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new BloqueAcademico();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			// return $this->redirect(['view', 'saca_id' => $model->saca_id]);

			$searchModel = new BloqueAcademicoSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			Yii::$app->session->setFlash('success', 'Datos guardados correctamente');
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
	 * Updates an existing Rol model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			Yii::$app->session->setFlash('success', 'Datos actualizados...!');
			$searchModel = new BloqueAcademicoSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
			// return $this->redirect(['view', 'saca_id' => $model->saca_id]);
		}

		return $this->render('update', [
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
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Rol model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Rol the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = BloqueAcademico::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}

	public function actionSavebloque() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$usu_id = @Yii::$app->session->get("PB_iduser");
			try {

				$bloque_model = new BloqueAcademico();
				$bloque_model->baca_nombre = $data["unidad"];
				$bloque_model->baca_descripcion = $data["modalidad"];
				$bloque_model->baca_anio = $data["estudio"];
				$bloque_model->baca_usuario_ingreso = $usu_id;
				$bloque_model->baca_estado = $data["estado"];
				$bloque_model->baca_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
				$bloque_model->baca_estado_logico = "1";

				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
					"title" => Yii::t('jslang', 'Success'),
				);
				if ($bloque_model->save()) {
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

	public function actionUpdateBloque() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			try {
				$bloque_model->baca_nombre = $data["unidad"];
				$bloque_model->baca_descripcion = $data["modalidad"];
				$bloque_model->baca_anio = $data["estudio"];
				$bloque_model->baca_usuario_modifica = $usu_id;
				$bloque_model->baca_estado = $data["estado"];
				$bloque_model->baca_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
				$bloque_model->baca_estado_logico = "1";

				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Se ha actualizado el Semestre Académico."),
					"title" => Yii::t('jslang', 'Success'),
				);
				if ($disthorario_model->save()) {
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
