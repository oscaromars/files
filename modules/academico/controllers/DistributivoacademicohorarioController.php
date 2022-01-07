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

    public function actionSavedistributivohorario() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {

                $disthorario_model = new DistributivoAcademicoHorario();
                $disthorario_model->uaca_id = $data["unidad"];
                $disthorario_model->mod_id = $data["modalidad"];
                $disthorario_model->eaca_id = $data["estudio"];
                $disthorario_model->daho_descripcion = $data["descripcion"];
                $disthorario_model->daho_jornada = $data["jornada"];
                $disthorario_model->daho_estado = $data["estado"];
                $disthorario_model->daho_horario = $data["horario"];
                $disthorario_model->daho_total_horas = $data["totalhora"];
                $disthorario_model->daho_estado_logico = "1";
                $disthorario_model->daho_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($disthorario_model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Horario no creado.');
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

    public function actionUpdatedistributivohorario() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $disthorario_model = new DistributivoAcademicoHorario();
                $disthorario_model = DistributivoAcademicoHorario::findOne($data["id"]);
                $disthorario_model->uaca_id = $data["unidad"];
                $disthorario_model->mod_id = $data["modalidad"];
                $disthorario_model->eaca_id = $data["estudio"];
                $disthorario_model->daho_descripcion = $data["descripcion"];
                $disthorario_model->daho_jornada = $data["jornada"];
                $disthorario_model->daho_estado = $data["estado"];
                $disthorario_model->daho_horario = $data["horario"];
                $disthorario_model->daho_total_horas = $data["totalhora"];
                $disthorario_model->daho_estado_logico = "1";
                $disthorario_model->daho_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Se ha actualizado el Semestre Académico."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($disthorario_model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Horario no ha sido actializado.');
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
