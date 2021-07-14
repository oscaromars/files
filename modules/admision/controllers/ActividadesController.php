<?php

namespace app\modules\admision\controllers;

use Yii;
use app\modules\admision\models\Oportunidad;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\TipoOportunidadVenta;
use app\modules\admision\models\EstadoOportunidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\ModuloEstudio;
use app\modules\admision\models\BitacoraSeguimiento;
use app\modules\admision\models\ActividadSeguimiento;
use app\models\Empresa;
use app\models\Persona;
use app\models\Usuario;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use Exception;

class ActividadesController extends \app\components\CController {
    public function actionListaractividadxoportunidad() {
        $modoportunidad = new Oportunidad();
        $pges_id = base64_decode($_GET["pges_id"]);
        $opor_id = base64_decode($_GET["opor_id"]);
        $persges_mod = new PersonaGestion();
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $mod_gestion = $modoportunidad->consultarOportunHist($opor_id);
        $mod_oportu = $modoportunidad->consultarOportunidadById($opor_id);
        return $this->render('listaractividad', [
                    'model' => $mod_gestion,
                    'personalData' => $contactManage,
                    'oportuniData' => $mod_oportu,
        ]);
    }

    public function actionView() {
        $opor_id = base64_decode($_GET["opid"]);
        $act_id = base64_decode($_GET["acid"]);
        $pges_id = base64_decode($_GET["pgid"]);
        $persges_mod = new PersonaGestion();
        $uni_aca_model = new UnidadAcademica();
        $modestudio = new ModuloEstudio();
        $modTipoOportunidad = new TipoOportunidadVenta();
        $modalidad_model = new Modalidad();
        $state_oportunidad_model = new EstadoOportunidad();
        $oport_model = new Oportunidad();
        $empresa_mod = new Empresa();
        $empresa = $empresa_mod->getAllEmpresa();
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $actividad_data = $oport_model->consultarActividadById($act_id);
        $oportunidad_perdidad = $oport_model->consultarOportunidadPerdida();        
        $oport_contac = $oport_model->consultarOportunidadById($opor_id);
        $otro_estudio_data = $modestudio->consultarOtrosEstudiosAcademicos($oport_contac["uaca_id"], $oport_contac["mod_id"]);
        if ($oport_contac["empresa"] > 1) {
            $estudio = $modestudio->consultarEstudioEmpresa($oport_contac["empresa"]);
        } else {
            $estudio = $arr_carrerra1 = $oport_model->consultarCarreraModalidad(1, 1);
        }
        $modalidad_data = $modalidad_model->consultarModalidad($oport_contac["uaca_id"], $oport_contac["empresa"]);
        $unidad_acad_data = UnidadAcademica::find()->select("uaca_id AS id, uaca_nombre AS name")->where(["uaca_usuario_ingreso" => "1"])->asArray()->all();
        $tipo_oportunidad_data = $modTipoOportunidad->consultarOporxUnidad($oport_contac["uaca_id"]);
        $academic_study_data = $oport_model->consultarCarreraModalidad($oport_contac["uaca_id"], $oport_contac["mod_id"]);
        $state_oportunidad_data = $state_oportunidad_model->consultarEstadOportunidad();
        $knowledge_channel_data = $oport_model->consultarConocimientoCanal(1);
        $observacion = $oport_model->consultarObseractividad();
        $modelSegui = BitacoraSeguimiento::findAll(['bseg_estado' => '1', 'bseg_estado_logico' => '1']);
        $arrSeg = array('Todos');
        $arrSeg = array_merge($arrSeg, ArrayHelper::getColumn($modelSegui, "bseg_nombre"));
        unset($arrSeg[0]);
        $arrSegData = ActividadSeguimiento::find()->where(['bact_id' => $act_id, 'aseg_estado' => '1', 'aseg_estado_logico' => '1'])->asArray()->all();
        $arrSegData = ArrayHelper::getColumn($arrSegData, "bseg_id");
        return $this->render('view', [
                    'personalData' => $contactManage,
                    'oportunidad_contacto' => $oport_contac,
                    'actividad_oportunidad' => $actividad_data,
                    'arr_modalidad' => ArrayHelper::map($modalidad_data, "id", "name"),
                    'arr_oportunidad_perdida' => ArrayHelper::map($oportunidad_perdidad, "id", "name"),
                    'arr_linea_servicio' => ArrayHelper::map($unidad_acad_data, "id", "name"),
                    'arr_tipo_oportunidad' => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
                    'arr_state_oportunidad' => ArrayHelper::map($state_oportunidad_data, "id", "name"),
                    'arr_academic_study' => ArrayHelper::map($academic_study_data, "id", "name"),
                    "arr_knowledge_channel" => ArrayHelper::map($knowledge_channel_data, "id", "name"),
                    "arr_otro_estudio" => ArrayHelper::map($otro_estudio_data, "id", "name"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
                    'arr_observacion' => ArrayHelper::map($observacion, "id", "name"),
                    'arr_estudio' => ArrayHelper::map($estudio, "id", "name"),
                    'arr_seguimiento' => $arrSeg,
                    'arr_segData' => $arrSegData,
        ]);
    }

    public function actionEdit() {
        $opor_id = base64_decode($_GET["opid"]);
        $act_id = base64_decode($_GET["acid"]);
        $pges_id = base64_decode($_GET["pgid"]);
        $_SESSION['JSLANG']['Enter a Type Contact.'] = admision::t('crm', 'Enter a Type Contact.');
        $persges_mod = new PersonaGestion();
        $uni_aca_model = new UnidadAcademica();
        $modestudio = new ModuloEstudio();
        $modTipoOportunidad = new TipoOportunidadVenta();
        $modalidad_model = new Modalidad();
        $state_oportunidad_model = new EstadoOportunidad();
        $oport_model = new Oportunidad();
        $empresa_mod = new Empresa();
        $empresa = $empresa_mod->getAllEmpresa();
        $oport_contac = $oport_model->consultarOportunidadById($opor_id);
        $otro_estudio_data = $modestudio->consultarOtrosEstudiosAcademicos($oport_contac["uaca_id"], $oport_contac["mod_id"]);
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $modalidad_data = $modalidad_model->consultarModalidad($oport_contac["uaca_id"], $oport_contac["empresa"]);
        $actividad_data = $oport_model->consultarActividadById($act_id);
        $oportunidad_perdidad = $oport_model->consultarOportunidadPerdida();
        $unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
        $tipo_oportunidad_data = $modTipoOportunidad->consultarOporxUnidad($oport_contac["uaca_id"]);
        $academic_study_data = $oport_model->consultarCarreraModalidad($oport_contac["uaca_id"], $oport_contac["mod_id"]);
        $state_oportunidad_data = $state_oportunidad_model->consultarEstadOportunidad();
        $knowledge_channel_data = $oport_model->consultarConocimientoCanal(1);
        $observacion = $oport_model->consultarObseractividad();
        $modelSegui = BitacoraSeguimiento::findAll(['bseg_estado' => '1', 'bseg_estado_logico' => '1']);
        $arrSeg = array('Todos');
        $arrSeg = array_merge($arrSeg, ArrayHelper::getColumn($modelSegui, "bseg_nombre"));
        unset($arrSeg[0]);
        $arrSegData = ActividadSeguimiento::find()->where(['bact_id' => $act_id, 'aseg_estado' => '1', 'aseg_estado_logico' => '1'])->asArray()->all();
        $arrSegData = ArrayHelper::getColumn($arrSegData, "bseg_id");
        return $this->render('edit', [
                    'personalData' => $contactManage,
                    'oportunidad_contacto' => $oport_contac,
                    'actividad_oportunidad' => $actividad_data,
                    'arr_modalidad' => ArrayHelper::map($modalidad_data, "id", "name"),
                    'arr_oportunidad_perdida' => ArrayHelper::map($oportunidad_perdidad, "id", "name"),
                    'arr_linea_servicio' => ArrayHelper::map($unidad_acad_data, "id", "name"),
                    'arr_tipo_oportunidad' => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
                    'arr_state_oportunidad' => ArrayHelper::map($state_oportunidad_data, "id", "name"),
                    "arr_otro_estudio" => ArrayHelper::map($otro_estudio_data, "id", "name"),
                    'arr_academic_study' => ArrayHelper::map($academic_study_data, "id", "name"),
                    "arr_knowledge_channel" => ArrayHelper::map($knowledge_channel_data, "id", "name"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
                    'arr_observacion' => ArrayHelper::map($observacion, "id", "name"),
                    'arr_seguimiento' => $arrSeg,
                    'arr_segData' => $arrSegData,
        ]);
    }

    public function actionNewactividad() {
        $opor_id = base64_decode($_GET["opid"]);
        $pges_id = base64_decode($_GET["pgid"]);
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $_SESSION['JSLANG']['Please enter a valid dni.'] = admision::t("crm",'Please enter a valid dni.');
        $_SESSION['JSLANG']['Please enter a valid Email.'] = admision::t("crm",'Please enter a valid Email.');
        $_SESSION['JSLANG']['Enter a Type Contact.'] = admision::t('crm', 'Enter a Type Contact.');
        $persges_mod = new PersonaGestion();
        $uni_aca_model = new UnidadAcademica();
        $modestudio = new ModuloEstudio();
        $modTipoOportunidad = new TipoOportunidadVenta();
        $modalidad_model = new Modalidad();
        $state_oportunidad_model = new EstadoOportunidad();
        $oport_model = new Oportunidad();
        $empresa_mod = new Empresa();
        $empresa = $empresa_mod->getAllEmpresa();
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $oport_contac = $oport_model->consultarOportunidadById($opor_id);
        $modalidad_data = $modalidad_model->consultarModalidad($oport_contac["uaca_id"], $oport_contac["empresa"]);
        $oportunidad_perdidad = $oport_model->consultarOportunidadPerdida();
        $unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
        $tipo_oportunidad_data = $modTipoOportunidad->consultarOporxUnidad(1);
        $academic_study_data = $oport_model->consultarCarreraModalidad(1, 1);        
        $state_oportunidad_data = $state_oportunidad_model->consultarEstadOportunidad();
        $knowledge_channel_data = $oport_model->consultarConocimientoCanal(1);
        $otros_estudios_academicos = $modestudio->consultarOtrosEstudiosAcademicos($oport_contac["uaca_id"],$oport_contac["mod_id"]);
        $observacion = $oport_model->consultarObseractividad();
        $modelSegui = BitacoraSeguimiento::findAll(['bseg_estado' => '1', 'bseg_estado_logico' => '1']);
        $arrSeg = array('Todos');
        $arrSeg = array_merge($arrSeg, ArrayHelper::getColumn($modelSegui, "bseg_nombre"));
        unset($arrSeg[0]);
        return $this->render('new', [
                    'personalData' => $contactManage,
                    'oportunidad_contacto' => $oport_contac,
                    'arr_modalidad' => ArrayHelper::map($modalidad_data, "id", "name"),
                    'arr_linea_servicio' => ArrayHelper::map($unidad_acad_data, "id", "name"),
                    'arr_oportunidad_perdida' => ArrayHelper::map($oportunidad_perdidad, "id", "name"),
                    'arr_tipo_oportunidad' => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
                    'arr_state_oportunidad' => ArrayHelper::map($state_oportunidad_data, "id", "name"),
                    'arr_academic_study' => ArrayHelper::map($academic_study_data, "id", "name"),
                    "arr_knowledge_channel" => ArrayHelper::map($knowledge_channel_data, "id", "name"),
                    "arr_otros_estudios" => ArrayHelper::map($otros_estudios_academicos, "id", "name"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
                    'arr_observacion' => ArrayHelper::map($observacion, "id", "name"),
                    'arr_seguimiento' => $arrSeg,
                    'emp_id' => $emp_id,
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
        ]);
    }

    public function actionSave() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usu_id = @Yii::$app->user->identity->usu_id;
        $fecproxima = null;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_gestion = new Oportunidad();
            $fecatiende = $data["fecatencion"] . ' ' . $data["horatencion"];
            $observacion = $data["observacion"];
            $descripcion = ucwords(strtolower($data["descripcion"]));
            if (!empty($data["fecproxima"])) {
                $fecproxima = $data["fecproxima"] . ' ' . $data["horproxima"];
            }
            $modOpor = Oportunidad::findOne(base64_decode($data['oportunidad']));
            $modelPges = PersonaGestion::findOne($modOpor->pges_id);
            if(isset($data["cedula"]) && $data["cedula"] != "" && $data["estado_oportunidad"] == 3){
                $modelPges->pges_cedula = $data["cedula"];
                /*$modelPer = Persona::findOne(['per_cedula' => $data["cedula"], 'per_estado' => '1', 'per_estado_logico' => '1']);
                if($modelPer){
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "Update DNI to generate interested"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                }*/
                $modelPges->save();
            }
            if(isset($data["correo"]) && $data["correo"] != "" && $data["estado_oportunidad"] == 3){
                $modelPges->pges_correo = $data["correo"];
                /*$modelUsu = Usuario::findOne(['usu_user' => $data["correo"], 'usu_estado' => '1', 'usu_estado_logico' => '1']);
                if($modelUsu){
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "Update Email to generate interested"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                }*/
                $modelPges->save();
            }
            // Datos Generales Contacto            
            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                $padm_class = $mod_gestion->consultarAgenteAutenticado($per_id );
                $padm_id = $padm_class['padm_id'];
                if ($padm_id > 0) {
                    $opo_id = base64_decode($data['oportunidad']);
                    $eopo_id = $data['estado_oportunidad'];
                    $actividad_id = $mod_gestion->insertarActividad($opo_id, $usu_id, $padm_id, $eopo_id, $fecatiende, $observacion, $descripcion, $fecproxima);
                    
                    if ($actividad_id) {
                        $oporper = null;
                        $otro_estudio=null;
                        if ($eopo_id == 5) {
                            $oporper = $data['oportunidad_perdida'];
                            if($oporper==13){
                                $otro_estudio = $data['otro_estudio'];
                            }
                        }
                        
                        if(isset($data["seguimiento"])){
                            foreach($data["seguimiento"] as $key => $value){
                                $modelSeguimiento = new ActividadSeguimiento();
                                $modelSeguimiento->bseg_id = $value;
                                $modelSeguimiento->bact_id = $actividad_id;
                                $modelSeguimiento->aseg_estado = '1';
                                $modelSeguimiento->aseg_estado_logico = '1';
                                if(!$modelSeguimiento->save()){
                                    throw new Exception("Error al grabar");
                                }
                            }
                        }
                        $out = $mod_gestion->modificarOportunixId(null, $opo_id, null, null, null, null, null, null, null, null, null, $eopo_id, $usu_id, $oporper,$otro_estudio);
                        if ($out) {
                            $exito = 1;
                        } else {
                            $exito = 0;
                        }
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar, el usuario autenticado no tiene permisos." . $mensaje),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }

    public function actionUpdate() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usu_id = @Yii::$app->user->identity->usu_id;
        $fecproxima = null;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_gestion = new Oportunidad();
            $fecatiende = $data["fecatencion"] . ' ' . $data["horatencion"];
            $observacion = $data["observacion"];
            $descripcion = ucwords(strtolower($data["descripcion"]));
            if (!empty($data["fecproxima"])) {
                $fecproxima = $data["fecproxima"] . ' ' . $data["horproxima"];
            }
            // Datos Generales Contacto            
            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                $padm_class = $mod_gestion->consultarAgenteAutenticado($per_id);
                $padm_id = $padm_class['padm_id'];
                $act_id = base64_decode($data['bact_id']);
                if ($padm_id > 0) {
                    $actividad_id = $mod_gestion->actualizarActividad($act_id, $usu_id, $padm_id, $fecatiende, $observacion, $descripcion, $fecproxima);
                    if ($actividad_id) {
                        $exito = 1;
                    }
                    if(isset($data["seguimiento"])){
                        if(!ActividadSeguimiento::deleteAllActividadSeguimiento($act_id)){
                            throw new Exception("Error al grabar");
                        }
                        foreach($data["seguimiento"] as $key => $value){
                            $modelSeguimiento = new ActividadSeguimiento();
                            $modelSeguimiento->bseg_id = $value;
                            $modelSeguimiento->bact_id = $act_id;
                            $modelSeguimiento->aseg_estado = '1';
                            $modelSeguimiento->aseg_estado_logico = '1';
                            if(!$modelSeguimiento->save()){
                                throw new Exception("Error al grabar");
                            }
                        }
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido actualizada. "),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar, el usuario autenticado no tiene permisos." . $mensaje),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }

}
