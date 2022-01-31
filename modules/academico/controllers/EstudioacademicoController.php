<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\Utilities;
use app\modules\academico\models\EstudioAcademicoSearch;
use app\modules\academico\models\EstudioAcademico;
use app\modules\Academico\Module as Academico;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;

Academico::registerTranslations();

class EstudioacademicoController extends \app\components\CController {
    /**
     * Lists all Notas models.
     * @return mixed
     */

    /**
      * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Lists all Rol models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new EstudioAcademicoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Alumno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new EstudioAcademico();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Datos guardados correctamente');
            $searchModel = new EstudioAcademicoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
                    'model' => $model,
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
             Yii::$app->session->setFlash('success', 'Datos Modificados Correctamente');
            //return $this->redirect(['create', 'eaca_id' => $model->eaca_id]);
            $searchModel = new EstudioAcademicoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model,]);
    }


    /**
     * Deletes an existing Rol model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
         if($this->findModel($id)->delete())
        {
            Yii::$app->session->setFlash('success', 'Registro eliminado correctamente...');
         }else  {
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
        if (($model = EstudioAcademico::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeletestudio() {
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $estudioaca_model = new EstudioAcademico();
                $estudioaca_model = EstudioAcademico::findOne($data["id"]);
                $estudioaca_model->eaca_usuario_modifica = $usu_autenticado;
                $estudioaca_model->eaca_estado = "0";
                $estudioaca_model->eaca_estado_logico = "0";
                $estudioaca_model->eaca_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Se ha actualizado el Estudio Académico."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($estudioaca_model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Estudio Académico no ha sido actializado.');
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

    public function actionSavestudioacademico() {
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {

                $estudioacad_model = new EstudioAcademico();
                $estudioacad_model->teac_id = $data["teac_id"];
                $estudioacad_model->eaca_nombre = $data["eaca_nombre"];
                $estudioacad_model->eaca_descripcion = $data["eaca_descripcion"];
                $estudioacad_model->eaca_alias_resumen = $data["eaca_alias_resumen"];
                $estudioacad_model->eaca_alias = $data["eaca_alias"];
                $estudioacad_model->eaca_estado = $data["estado"];
                $estudioacad_model->eaca_usuario_ingreso = $usu_autenticado;
                $estudioacad_model->eaca_estado_logico = "1";
                $estudioacad_model->eaca_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($estudioacad_model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Estudio Académico no creado.');
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

    public function actionUpdatestudioacademico() {
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $estudioacad_model = new EstudioAcademico();
                $estudioacad_model = EstudioAcademico::findOne($data["id"]);
                $estudioacad_model->teac_id = $data["teac_id"];
                $estudioacad_model->eaca_nombre = $data["eaca_nombre"];
                $estudioacad_model->eaca_descripcion = $data["eaca_descripcion"];
                $estudioacad_model->eaca_alias_resumen = $data["eaca_alias_resumen"];
                $estudioacad_model->eaca_alias = $data["eaca_alias"];
                $estudioacad_model->eaca_estado = $data["estado"];
                $estudioacad_model->eaca_usuario_modifica = $usu_autenticado;
                $estudioacad_model->eaca_estado_logico = "1";
                $estudioacad_model->eaca_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Se ha actualizado el Estudio Académico."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($estudioacad_model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Estudio Académico no ha sido actializado.');
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
