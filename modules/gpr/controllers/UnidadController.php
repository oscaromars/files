<?php

namespace app\modules\gpr\controllers;

use app\models\Usuario;
use Yii;
use app\modules\gpr\models\Entidad;
use app\models\Utilities;
use app\modules\gpr\models\Categoria;
use app\modules\gpr\models\ResponsableUnidad;
use app\modules\gpr\models\TipoUnidad;
use app\modules\gpr\models\UnidadGpr;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class UnidadController extends \app\components\CController {

    public function actionIndex() {
        $model = new UnidadGpr();
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllUnidadGprGrid($data["search"], $data["categoria"], $data["entidad"], true)
            ]);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getentidades"])) {
                $cat_id = $data["categoria"];
                $entidades = Entidad::find()->
                                    select("ent_id AS id, ent_nombre AS name")
                                    ->where(['ent_estado' => '1', 'ent_estado_logico' => '1', 'cat_id' => $cat_id])
                                    ->asArray()->all();
                $arr_entidad = array_merge(['0' => ['id' => '0', 'name' => gpr::t('entidad', '-- All Entities --')]], $entidades);
                $message = array("entidad" => $arr_entidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
        $arr_categoria = ['0' => gpr::t('categoria', '-- All Categories --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
        $arr_entidad= Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1']);
        $arr_entidad = ['0' => gpr::t('entidad', '-- All Entities --')] + ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre");
        return $this->render('index', [
            'model' => $model->getAllUnidadGprGrid(NULL, NULL, NULL, true),
            'arr_categoria' => $arr_categoria,
            'arr_entidad' => $arr_entidad,
        ]);
    }

    public function actionNew() {
        $_SESSION['JSLANG']['Please select a Category Name.'] = gpr::t('categoria', 'Please select a Category Name.');
        $_SESSION['JSLANG']['Please select an Entity Name.'] = gpr::t('entidad', 'Please select an Entity Name.');
        $_SESSION['JSLANG']['Please select a Type of Unit.'] = gpr::t('unidad', 'Please select a Type of Unit.');
        $_SESSION['JSLANG']['-- All Entities --'] = gpr::t('entidad', '-- All Entities --');
        $_SESSION['JSLANG']['The Subunit must have at least 1 responsible.'] = gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.');
        $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
        $arr_categoria = ['0' => gpr::t('categoria', '-- Select a Category Name --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
        $arr_entidad = ['0' => gpr::t('entidad', '-- Select a Entity Name --')];
        $arr_t_unidad = TipoUnidad::findAll(['tuni_estado' => '1', 'tuni_estado_logico' => '1']);
        $arr_t_unidad = ['0' => gpr::t('unidad', '-- Select a Type of Unit --')] + ArrayHelper::map($arr_t_unidad, "tuni_id", "tuni_nombre");
        //$dataUsers = Usuario::getListUsers(NULL, true);
        return $this->render('new', [
            'arr_categoria' => $arr_categoria,
            'arr_entidad' => $arr_entidad,
            'arr_tipo_unidad' => $arr_t_unidad,
            //'dataUsers' => $dataUsers,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = UnidadGpr::findOne($id);
            $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
            $arr_categoria = ['0' => gpr::t('categoria', '-- Select a Category Name --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
            $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'ent_id' => $model->ent_id]);
            $cat_id = $arr_entidad[0]->cat_id;
            $arr_entidad = ['0' => gpr::t('categoria', '-- Select a Entity Name --')] + ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre");
            $arr_t_unidad = TipoUnidad::findAll(['tuni_estado' => '1', 'tuni_estado_logico' => '1']);
            $arr_t_unidad = ['0' => gpr::t('unidad', '-- Select a Type of Unit --')] + ArrayHelper::map($arr_t_unidad, "tuni_id", "tuni_nombre");
            //$dataUsers = Usuario::getListUsers(NULL, true);
            /*$usuarios = ResponsableUnidad::find()
            ->select(['usu_id'])
            ->where(['ugpr_id' => $id, 'runi_estado_logico' => '1', 'runi_estado' => '1'])
            ->asArray()
            ->all();*/
            return $this->render('view', [
                'model' => $model,
                'arr_categoria' => $arr_categoria,
                'arr_entidad' => $arr_entidad,
                'cat_id' => $cat_id,
                'arr_tipo_unidad' => $arr_t_unidad,
                //'dataUsers' => $dataUsers,
                //'usuarios' => $usuarios,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $_SESSION['JSLANG']['Please select a Category Name.'] = gpr::t('categoria', 'Please select a Category Name.');
            $_SESSION['JSLANG']['Please select an Entity Name.'] = gpr::t('entidad', 'Please select an Entity Name.');
            $_SESSION['JSLANG']['Please select a Type of Unit.'] = gpr::t('unidad', 'Please select a Type of Unit.');
            $_SESSION['JSLANG']['-- All Entities --'] = gpr::t('entidad', '-- All Entities --');
            $_SESSION['JSLANG']['The Subunit must have at least 1 responsible.'] = gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.');
            $id = $data['id'];
            $model = UnidadGpr::findOne($id);
            $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
            $arr_categoria = ['0' => gpr::t('categoria', '-- Select a Category Name --')] + ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre");
            $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'ent_id' => $model->ent_id]);
            $cat_id = $arr_entidad[0]->cat_id;
            $arr_entidad = ['0' => gpr::t('categoria', '-- Select a Entity Name --')] + ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre");
            $arr_t_unidad = TipoUnidad::findAll(['tuni_estado' => '1', 'tuni_estado_logico' => '1']);
            $arr_t_unidad = ['0' => gpr::t('unidad', '-- Select a Type of Unit --')] + ArrayHelper::map($arr_t_unidad, "tuni_id", "tuni_nombre");
            //$dataUsers = Usuario::getListUsers(NULL, true);
            /*$usuarios = ResponsableUnidad::find()
            ->select(['usu_id'])
            ->where(['ugpr_id' => $id, 'runi_estado_logico' => '1', 'runi_estado' => '1'])
            ->asArray()
            ->all();*/
            return $this->render('edit', [
                'model' => $model,
                'arr_categoria' => $arr_categoria,
                'arr_entidad' => $arr_entidad,
                'cat_id' => $cat_id,
                'arr_tipo_unidad' => $arr_t_unidad,
                //'dataUsers' => $dataUsers,
                //'usuarios' => $usuarios,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $categoria = $data['categoria'];
                $entidad = $data['entidad'];
                $tunidad = $data['tunidad'];
                //$usuarios = $data["usuarios"];
                /*if(!isset($data["usuarios"]) && count($data["usuarios"]) == 0 ){
                    $throwError = true;
                    throw new Exception(gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.'));
                }*/
                $model = new UnidadGpr();
                $model->ugpr_nombre = $nombre;
                $model->ugpr_descripcion = $descripcion;
                $model->ent_id = $entidad;
                $model->tuni_id = $tunidad;
                $model->ugpr_estado = $estado;
                $model->ugpr_usuario_ingreso = $user_id;
                $model->ugpr_estado_logico = "1";
                
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    /*for($i=0; $i<count($usuarios); $i++){
                        $model2 = new ResponsableUnidad();
                        $model2->ugpr_id = $model->ugpr_id;
                        $model2->usu_id = $usuarios[$i];
                        $model2->emp_id = $emp_id;
                        $model2->runi_usuario_ingreso = $user_id;
                        $model2->runi_estado = "1";
                        $model2->runi_estado_logico = "1";
                        if(!$model2->save()){
                            $throwError = true;
                            throw new Exception(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                        }
                    }*/
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no creado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db_gpr->beginTransaction();
            try {
                $id = $data["id"];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $categoria = $data['categoria'];
                $entidad = $data['entidad'];
                $tunidad = $data['tunidad'];
                //$usuarios = $data["usuarios"];
                /*if(!isset($data["usuarios"]) && count($data["usuarios"]) == 0 ){
                    $throwError = true;
                    throw new Exception(gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.'));
                }*/
                $model = UnidadGpr::findOne($id);
                $model->ugpr_nombre = $nombre;
                $model->ugpr_descripcion = $descripcion;
                $model->ent_id = $entidad;
                $model->tuni_id = $tunidad;
                $model->ugpr_usuario_modifica = $user_id;
                $model->ugpr_fecha_modificacion = $fecha_modificacion;
                $model->ugpr_estado = $estado;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    /*if(ResponsableUnidad::deleteAllResponsablesByConf($data["id"]) == false)
                        throw new Exception('Error Borrando Responsables.');
                    for($i=0; $i<count($usuarios); $i++){
                        $model2 = new ResponsableUnidad();
                        $model2->ugpr_id = $model->ugpr_id;
                        $model2->usu_id = $usuarios[$i];
                        $model2->emp_id = $emp_id;
                        $model2->runi_usuario_ingreso = $user_id;
                        $model2->runi_estado = "1";
                        $model2->runi_estado_logico = "1";
                        if(!$model2->save()){
                            $throwError = true;
                            throw new Exception(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                        }
                    }*/
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no actualizado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            try {
                $id = $data["id"];
                $model = UnidadGpr::findOne($id);
                $model->ugpr_estado_logico = '0';
                $model->ugpr_usuario_modifica = $user_id;
                $model->ugpr_fecha_modificacion = $fecha_modificacion;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no ha sido eliminado.');
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
}