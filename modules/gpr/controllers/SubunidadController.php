<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\Entidad;
use app\models\Utilities;
use app\models\Usuario;
use app\modules\gpr\models\Categoria;
use app\modules\gpr\models\ResponsableSubunidad;
use app\modules\gpr\models\UnidadGpr;
use app\modules\gpr\models\SubunidadGpr;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class SubunidadController extends \app\components\CController {

    public function actionIndex() {
        $model = new SubunidadGpr();
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllSubUnidadGprGrid($data["search"], $data["categoria"], $data["entidad"], $data['unidad'], true)
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
                //reset($entidades);
                $ent_id = current($entidades);
                $unidades = UnidadGpr::find()
                                    ->select("ugpr_id AS id, ugpr_nombre AS name")
                                    ->where(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1', 'ent_id' => $ent_id['id']])
                                    ->asArray()->all();
                $arr_unidad = array_merge(['0' => ['id' => '0', 'name' => gpr::t('unidad', '-- All Unities --')]], $unidades);
                $message = array("entidad" => $arr_entidad, "unidad" => $arr_unidad);

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getunidades"])){
                $ent_id = $data["entidad"];
                $unidades = UnidadGpr::find()
                                    ->select("ugpr_id AS id, ugpr_nombre AS name")
                                    ->where(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1', 'ent_id' => $ent_id])
                                    ->asArray()->all();
                $arr_unidad = array_merge(['0' => ['id' => '0', 'name' => gpr::t('unidad', '-- All Unities --')]], $unidades);
                $message = array("unidad" => $arr_unidad);

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
        $arr_categoria = array_merge(['0' => gpr::t('categoria', '-- All Categories --')],ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre"));
        $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1']);
        $arr_entidad = array_merge(['0' => gpr::t('entidad', '-- All Entities --')],ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre"));
        $arr_unidad = UnidadGpr::findAll(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1']);
        $arr_unidad = array_merge(['0' => gpr::t('unidad', '-- All Unities --')],ArrayHelper::map($arr_unidad, "ugpr_id", "ugpr_nombre"));
        return $this->render('index', [
            'model' => $model->getAllSubUnidadGprGrid(NULL, NULL, NULL, NULL, true),
            'arr_categoria' => $arr_categoria,
            'arr_entidad' => $arr_entidad,
            'arr_unidad' => $arr_unidad,
        ]);
    }

    public function actionNew() {
        $_SESSION['JSLANG']['Please select a Category Name.'] = gpr::t('categoria', 'Please select a Category Name.');
        $_SESSION['JSLANG']['Please select an Entity Name.'] = gpr::t('entidad', 'Please select an Entity Name.');
        $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
        $_SESSION['JSLANG']['-- All Entities --'] = gpr::t('entidad', '-- All Entities --');
        $_SESSION['JSLANG']['The Subunit must have at least 1 responsible.'] = gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.');
        $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
        $arr_categoria = array_merge(['0' => gpr::t('categoria', '-- All Categories --')],ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre"));
        $arr_entidad = ['0' => gpr::t('entidad', '-- All Entities --')];
        $arr_unidad = ['0' => gpr::t('unidad', '-- All Unities --')];
        $dataUsers = Usuario::getListUsers(NULL, true);
        return $this->render('new', [
            'arr_categoria' => $arr_categoria,
            'arr_entidad' => $arr_entidad,
            'arr_unidad' => $arr_unidad,
            'dataUsers' => $dataUsers,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = SubunidadGpr::findOne($id);
            $uni_id = $model->ugpr_id;
            $modelUnidad = UnidadGpr::findOne($uni_id);
            $ent_id = $modelUnidad->ent_id;
            $modelEntidad = Entidad::findOne($ent_id);
            $cat_id = $modelEntidad->cat_id;

            $arr_unidad = UnidadGpr::findAll(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1', 'ent_id' => $ent_id]);
            $arr_unidad = array_merge(['0' => gpr::t('unidad', '-- All Unities --')],ArrayHelper::map($arr_unidad, "ugpr_id", "ugpr_nombre"));

            $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'cat_id' => $cat_id]);
            $arr_entidad = array_merge(['0' => gpr::t('categoria', '-- All Entities --')],ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre"));

            $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
            $arr_categoria = array_merge(['0' => gpr::t('categoria', '-- All Categories --')],ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre"));

            $dataUsers = Usuario::getListUsers(NULL, true);
            $usuarios = ResponsableSubunidad::find()
            ->select(['usu_id'])
            ->where(['sgpr_id' => $id, 'rsub_estado_logico' => '1', 'rsub_estado' => '1'])
            ->asArray()
            ->all();

            return $this->render('view', [
                'model' => $model,
                'arr_categoria' => $arr_categoria,
                'arr_entidad' => $arr_entidad,
                'arr_unidad' => $arr_unidad,
                'cat_id' => $cat_id,
                'ent_id' => $ent_id,
                'uni_id' => $uni_id,
                'dataUsers' => $dataUsers,
                'usuarios' => $usuarios,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $_SESSION['JSLANG']['Please select a Category Name.'] = gpr::t('categoria', 'Please select a Category Name.');
            $_SESSION['JSLANG']['Please select an Entity Name.'] = gpr::t('entidad', 'Please select an Entity Name.');
            $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
            $_SESSION['JSLANG']['-- All Entities --'] = gpr::t('entidad', '-- All Entities --');
            $_SESSION['JSLANG']['The Subunit must have at least 1 responsible.'] = gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.');
            $id = $data['id'];
            $model = SubunidadGpr::findOne($id);
            $uni_id = $model->ugpr_id;
            $modelUnidad = UnidadGpr::findOne($uni_id);
            $ent_id = $modelUnidad->ent_id;
            $modelEntidad = Entidad::findOne($ent_id);
            $cat_id = $modelEntidad->cat_id;

            $arr_unidad = UnidadGpr::findAll(['ugpr_estado' => '1', 'ugpr_estado_logico' => '1', 'ent_id' => $ent_id]);
            $arr_unidad = array_merge(['0' => gpr::t('unidad', '-- All Unities --')],ArrayHelper::map($arr_unidad, "ugpr_id", "ugpr_nombre"));

            $arr_entidad = Entidad::findAll(['ent_estado' => '1', 'ent_estado_logico' => '1', 'cat_id' => $cat_id]);
            $arr_entidad = array_merge(['0' => gpr::t('categoria', '-- All Entities --')],ArrayHelper::map($arr_entidad, "ent_id", "ent_nombre"));

            $arr_categoria = Categoria::findAll(['cat_estado' => '1', 'cat_estado_logico' => '1']);
            $arr_categoria = array_merge(['0' => gpr::t('categoria', '-- All Categories --')],ArrayHelper::map($arr_categoria, "cat_id", "cat_nombre"));

            $dataUsers = Usuario::getListUsers(NULL, true);
            $usuarios = ResponsableSubunidad::find()
            ->select(['usu_id'])
            ->where(['sgpr_id' => $id, 'rsub_estado_logico' => '1', 'rsub_estado' => '1'])
            ->asArray()
            ->all();

            return $this->render('edit', [
                'model' => $model,
                'arr_categoria' => $arr_categoria,
                'arr_entidad' => $arr_entidad,
                'arr_unidad' => $arr_unidad,
                'cat_id' => $cat_id,
                'ent_id' => $ent_id,
                'uni_id' => $uni_id,
                'dataUsers' => $dataUsers,
                'usuarios' => $usuarios,
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
                $unidad = $data['unidad'];
                $usuarios = $data["usuarios"];
                if(!isset($data["usuarios"]) && count($data["usuarios"]) == 0 ){
                    $throwError = true;
                    throw new Exception(gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.'));
                }
                $model = new SubunidadGpr();
                $model->sgpr_nombre = $nombre;
                $model->sgpr_descripcion = $descripcion;
                $model->ugpr_id = $unidad;
                $model->sgpr_estado = $estado;
                $model->sgpr_usuario_ingreso = $user_id;
                $model->sgpr_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    for($i=0; $i<count($usuarios); $i++){
                        $model2 = new ResponsableSubunidad();
                        $model2->sgpr_id = $model->sgpr_id;
                        $model2->usu_id = $usuarios[$i];
                        $model2->emp_id = $emp_id;
                        $model2->rsub_usuario_ingreso = $user_id;
                        $model2->rsub_estado = "1";
                        $model2->rsub_estado_logico = "1";
                        if(!$model2->save()){
                            $throwError = true;
                            throw new Exception(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                        }
                    }
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
            $emp_id = Yii::$app->session->get('PB_idempresa', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db_gpr->beginTransaction();
            try {
                $id = $data["id"];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $estado = $data["estado"];
                $categoria = $data['categoria'];
                $entidad = $data['entidad'];
                $unidad = $data['unidad'];
                $usuarios = $data["usuarios"];
                if(!isset($data["usuarios"]) && count($data["usuarios"]) == 0 ){
                    $throwError = true;
                    throw new Exception(gpr::t('responsablesubunidad', 'The Subunit must have at least 1 responsible.'));
                }
                $model = SubunidadGpr::findOne($id);
                $model->sgpr_nombre = $nombre;
                $model->sgpr_descripcion = $descripcion;
                $model->ugpr_id = $unidad;
                $model->sgpr_usuario_modifica = $user_id;
                $model->sgpr_fecha_modificacion = $fecha_modificacion;
                $model->sgpr_estado = $estado;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    if(ResponsableSubunidad::deleteAllResponsablesByConf($data["id"]) == false)
                        throw new Exception('Error Borrando Responsables.');
                    for($i=0; $i<count($usuarios); $i++){
                        $model2 = new ResponsableSubunidad();
                        $model2->sgpr_id = $model->sgpr_id;
                        $model2->usu_id = $usuarios[$i];
                        $model2->emp_id = $emp_id;
                        $model2->rsub_usuario_ingreso = $user_id;
                        $model2->rsub_estado = "1";
                        $model2->rsub_estado_logico = "1";
                        if(!$model2->save()){
                            $throwError = true;
                            throw new Exception(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                        }
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no actualizado.');
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
            $transaction = Yii::$app->db_gpr->beginTransaction();
            try {
                $id = $data["id"];
                $model = SubunidadGpr::findOne($id);
                $model->sgpr_estado_logico = '0';
                $model->sgpr_usuario_modifica = $user_id;
                $model->sgpr_fecha_modificacion = $fecha_modificacion;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    if(ResponsableSubunidad::deleteAllResponsablesByConf($id) == false)
                        throw new Exception('Error Borrando Responsables.');
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no ha sido eliminado.');
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
}