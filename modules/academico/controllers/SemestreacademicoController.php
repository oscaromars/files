<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\SemestreAcademicoSearch;
use app\modules\academico\models\SemestreAcademico;
use app\modules\Academico\Module as Academico;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\base\Exception;
use app\models\Utilities;

Academico::registerTranslations();


/**
 * RolController implements the CRUD actions for Rol model.
 */
class SemestreacademicoController extends \app\components\CController 
{
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
     * Lists all SemestreAcademicoSearch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SemestreAcademicoSearch();
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Rol model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SemestreAcademico();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           // return $this->redirect(['view', 'saca_id' => $model->saca_id]);
        
        $searchModel = new SemestreAcademicoSearch();
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

    public function actionSavesemestre() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                \app\models\Utilities::putMessageLogFile('nombre semestre...: ' . $data["nombre"]);
                \app\models\Utilities::putMessageLogFile('intensivo semestre...: ' . $data["intensivo"]);
                \app\models\Utilities::putMessageLogFile('descripción semestre...: ' . $data["descripcion"]);
                \app\models\Utilities::putMessageLogFile('año semestre...: ' . $data["ano"]);
                \app\models\Utilities::putMessageLogFile('estado semestre...: ' . $data["estado"]);
                $nombre = $data["nombre"];
                $intensivo = $data["intensivo"];
                $descripcion = $data["descripcion"];
                $ano = $data["ano"];
                $estado = $data["estado"];
                
                $semestre_model = new SemestreAcademico();
                $semestre_model->saca_nombre = $nombre;
                $semestre_model->saca_intensivo = $intensivo;
                $semestre_model->saca_descripcion = $descripcion;
                $semestre_model->saca_anio = $ano;
                $semestre_model->saca_estado = $estado;
                $semestre_model->saca_estado_logico = "1";
                $semestre_model->saca_estado = "1";
                $semestre_model->saca_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
                $semestre_model->saca_usuario_ingreso=@Yii::$app->session->get("PB_iduser");
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($semestre_model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no creado.');
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
    public function actionUpdatesemestre() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                \app\models\Utilities::putMessageLogFile('nombre semestre...: ' . $data["nombre"]);
                \app\models\Utilities::putMessageLogFile('intensivo semestre...: ' . $data["intensivo"]);
                \app\models\Utilities::putMessageLogFile('descripción semestre...: ' . $data["descripcion"]);
                \app\models\Utilities::putMessageLogFile('año semestre...: ' . $data["ano"]);
                \app\models\Utilities::putMessageLogFile('estado semestre...: ' . $data["estado"]);
                $id = $data["id"];
                $nombre = $data["nombre"];
                $intensivo = $data["intensivo"];
                $descripcion = $data["descripcion"];
                $ano = $data["ano"];
                $estado = $data["estado"];
                
                $semestre_model = SemestreAcademico::findOne($id);
                $semestre_model->saca_nombre = $nombre;
                $semestre_model->saca_intensivo = $intensivo;
                $semestre_model->saca_descripcion = $descripcion;
                $semestre_model->saca_anio = $ano;
                $semestre_model->saca_estado = $estado;
                /*$semestre_model->saca_estado_logico = "1";
                $semestre_model->saca_estado = "1";
                $semestre_model->saca_fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
                $semestre_model->saca_usuario_ingreso=@Yii::$app->session->get("PB_iduser");*/
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Se ha actualizado el Semestre Académico."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($semestre_model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no ha sido actializado.');
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

    /**
     * Updates an existing Rol model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
    
     Yii::$app->session->setFlash('success','Datos actualizados...!');
        $searchModel = new SemestreAcademicoSearch();
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
    public function actionDelete($id)
    {
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
    protected function findModel($id)
    {
        if (($model = SemestreAcademico::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
