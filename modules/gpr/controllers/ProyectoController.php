<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\modules\gpr\models\Proyecto;
use app\models\Utilities;
use app\modules\gpr\models\Hito;
use app\modules\gpr\models\HitoSeguimiento;
use app\modules\gpr\models\Nivel;
use app\modules\gpr\models\ObjetivoOperativo;
use app\modules\gpr\models\ResponsableUnidad;
use app\modules\gpr\models\TipoProyecto;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\models\UnidadGpr;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class ProyectoController extends \app\components\CController {

    public function actionIndex() {
        $model = new Proyecto();
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $isAdmin = ResponsableUnidad::userIsAdmin($user_id, $idEmpresa);
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllProyectoGrid($data["search"], $data["tpro"], $data["objetivo"], $data["unidad"], true),
                'isAdmin' => $isAdmin,
            ]);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getobjetivos"])) {
                $obj_id = $data["objetivo"];
                $unidades = UnidadGpr::getUnidadesByObjOperativo($obj_id);
                $arr_unidad = array_merge(['0' => ['id' => '0', 'name' => gpr::t('unidad', '-- Select an Unity Name --')]], $unidades);
                $message = array("unidad" => $arr_unidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_objope = ObjetivoOperativo::getArrayObjOperativo();
        $arr_objope = ['0' => gpr::t('objetivooperativo', '-- All Operative Objective --')] + ArrayHelper::map($arr_objope, "id", "name");
        $arr_tippro = TipoProyecto::findAll(['tpro_estado' => '1', 'tpro_estado_logico' => '1']);
        $arr_tippro = ['0' => gpr::t('tipoproyecto', "-- All Type Proyect --")] + ArrayHelper::map($arr_tippro, "tpro_id", "tpro_nombre");
        $arr_unidad = UnidadGpr::getArrayUnidad();
        $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
        return $this->render('index', [
            'model' => $model->getAllProyectoGrid(NULL, NULL, NULL, NULL, true),
            'arr_objope' => $arr_objope,
            'arr_tippro' => $arr_tippro,
            'arr_unidad' => $arr_unidad,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function actionNew() {
        $_SESSION['JSLANG']['Please select a Project Type.'] = gpr::t('tipoproyecto', 'Please select a Project Type.');
        $_SESSION['JSLANG']['Please select an Operative Objective.'] = gpr::t('objetivooperativo', 'Please select an Operative Objective.');
        $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
        $_SESSION['JSLANG']['Initial Date must be less than to End Date.'] = gpr::t('proyecto', 'Initial Date must be less than to End Date.');
        $arr_objope = ObjetivoOperativo::getArrayObjOperativo();
        $arr_objope = ['0' => gpr::t('objetivooperativo', '-- Select an Operative Objective --')] + ArrayHelper::map($arr_objope, "id", "name");
        $arr_tippro = TipoProyecto::findAll(['tpro_estado' => '1', 'tpro_estado_logico' => '1']);
        $arr_tippro = ['0' => gpr::t('tipoproyecto', "-- Select a Type Proyect --")] + ArrayHelper::map($arr_tippro, "tpro_id", "tpro_nombre");
        $arr_unidad = UnidadGpr::getArrayUnidad();
        $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')]; //+ ArrayHelper::map($arr_unidad, "id", "name");
        return $this->render('new', [
            'arr_tippro' => $arr_tippro,
            'arr_objope' => $arr_objope,
            'arr_unidad' => $arr_unidad,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $model = Proyecto::findOne($data['id']);
            $arr_objope = ObjetivoOperativo::getArrayObjOperativo();
            $arr_objope = ['0' => gpr::t('objetivooperativo', '-- Select an Operative Objective --')] + ArrayHelper::map($arr_objope, "id", "name");
            $arr_tippro = TipoProyecto::findAll(['tpro_estado' => '1', 'tpro_estado_logico' => '1']);
            $arr_tippro = ['0' => gpr::t('tipoproyecto', "-- Select a Type Proyect --")] + ArrayHelper::map($arr_tippro, "tpro_id", "tpro_nombre");
            $arr_unidad = UnidadGpr::getUnidadesByObjOperativo($model->oope_id);
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
            return $this->render('view', [
                'model' => $model,
                'arr_tippro' => $arr_tippro,
                'arr_objope' => $arr_objope,
                'arr_unidad' => $arr_unidad,
            ]);
        }
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $model = Proyecto::findOne($data['id']);
            $_SESSION['JSLANG']['Please select a Project Type.'] = gpr::t('tipoproyecto', 'Please select a Project Type.');
            $_SESSION['JSLANG']['Please select an Operative Objective.'] = gpr::t('objetivooperativo', 'Please select an Operative Objective.');
            $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
            $_SESSION['JSLANG']['Initial Date must be less than to End Date.'] = gpr::t('proyecto', 'Initial Date must be less than to End Date.');
            $arr_objope = ObjetivoOperativo::getArrayObjOperativo();
            $arr_objope = ['0' => gpr::t('objetivooperativo', '-- Select an Operative Objective --')] + ArrayHelper::map($arr_objope, "id", "name");
            $arr_tippro = TipoProyecto::findAll(['tpro_estado' => '1', 'tpro_estado_logico' => '1']);
            $arr_tippro = ['0' => gpr::t('tipoproyecto', "-- Select a Type Proyect --")] + ArrayHelper::map($arr_tippro, "tpro_id", "tpro_nombre");
            $arr_unidad = UnidadGpr::getUnidadesByObjOperativo($model->oope_id);
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
            return $this->render('edit', [
                'model' => $model,
                'arr_tippro' => $arr_tippro,
                'arr_objope' => $arr_objope,
                'arr_unidad' => $arr_unidad,
            ]);
        }
    }

    public function actionSave() {
        // al guardar se debe colocar pro_cerrado=1
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $error = false;
            try {
                $nombre = $data['nombre'];
                $desc = $data['desc'];
                $tipo = $data['tipo'];
                $objetivo = $data['objetivo'];
                $unidad = $data['unidad'];
                $presupuesto = $data['presupuesto'];
                $fini = $data['fini'];
                $fend = $data['fend'];
                $restricciones = $data['restricciones'];

                $model = new Proyecto();
                $model->pro_nombre = $nombre;
                $model->pro_descripcion = $desc;
                $model->tpro_id = $tipo;
                $model->oope_id = $objetivo;
                $model->ugpr_id = $unidad;
                $model->pro_presupuesto = $presupuesto;
                $model->pro_fecha_inicio = $fini;
                $model->pro_fecha_fin = $fend;
                $model->pro_restricciones = $restricciones;
                $model->pro_usuario_ingreso = $user_id;
                $model->pro_estado = '1';
                $model->pro_estado_logico = '1';
                $model->pro_cerrado = '0';

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no creado.');
                }
            } catch (Exception $ex) {
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionUpdate() {
        // si el proyecto esta cerrado no se podra actualizar se debe pedir autorizacion
        // al guardar se debe colocar pro_cerrado=1
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $error = false;
            try {
                $id = $data['id'];
                $nombre = $data['nombre'];
                $desc = $data['desc'];
                $tipo = $data['tipo'];
                $objetivo = $data['objetivo'];
                $unidad = $data['unidad'];
                $presupuesto = $data['presupuesto'];
                $fini = $data['fini'];
                $fend = $data['fend'];
                $restricciones = $data['restricciones'];
                $razon = $data['razon'];

                $model = Proyecto::findOne($id);
                if($model->pro_cerrado == "1"){
                    $error = true;
                    throw new Exception(gpr::t("proyecto", "Project cannot be updated. Project is closed. Report to Administrator."));
                }
                $model->pro_nombre = $nombre;
                $model->pro_descripcion = $desc;
                $model->tpro_id = $tipo;
                $model->oope_id = $objetivo;
                $model->ugpr_id = $unidad;
                $model->pro_presupuesto = $presupuesto;
                $model->pro_fecha_inicio = $fini;
                $model->pro_fecha_fin = $fend;
                $model->pro_restricciones = $restricciones;
                $model->pro_usuario_modifica = $user_id;
                $model->pro_fecha_modificacion = $fecha_modificacion;
                $model->pro_razon_cambio = $razon;
                //$model->pro_cerrado = '0'; // el proyecto se cierra cuando se guarda proyeccion en hitos por validacion

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no creado.');
                }
            } catch (Exception $ex) {
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionDelete() {
        // se debe actualizar todos los demas hitos y hitoseguimiento estado eliminado
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = Proyecto::findOne($id);
                $model->pro_estado_logico = '0';
                $model->pro_estado = '0';
                $model->pro_usuario_modifica = $user_id;
                $model->pro_fecha_modificacion = $fecha_modificacion;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    if(!Hito::deleteAllHitosByProjectId($id)){
                        throw new Exception('Error Registro no ha sido eliminado.');
                    }
                    if(!HitoSeguimiento::deleteAllHitosSegByProId($id)){
                        throw new Exception('Error Registro no ha sido eliminado.');
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no ha sido eliminado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                if($error)  $msg = $ex->getMessage();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.') . " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionOpenproject(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = Proyecto::findOne($id);
                $ugpr_id = $model->ugpr_id;
                //$niv_id = Nivel::getLevel($user_id, $idEmpresa, $ugpr_id);
                //if($niv_id != 1){
                if(!ResponsableUnidad::userIsAdmin($user_id, $idEmpresa)){
                    $error = true;
                    throw new Exception(gpr::t("responsablesubunidad", "User have no privileges N1 to do this transaccion."));
                }
                $model->pro_usuario_modifica = $user_id;
                $model->pro_fecha_modificacion = $fecha_modificacion;
                $model->pro_cerrado = '0';
                $arrHitos = Hito::findAll(['pro_id' => $id, 'hito_estado' => '1', 'hito_estado_logico' => '1']);
                foreach($arrHitos as $key => $value){
                    $value->hito_cerrado = '0';
                    $value->hito_fecha_modificacion = $fecha_modificacion;
                    $value->hito_usuario_modifica = $user_id;
                    if(!$value->save()){
                        throw new Exception('Error Registro no ha sido actualizado.');
                    }
                }
                $transaction->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
}
