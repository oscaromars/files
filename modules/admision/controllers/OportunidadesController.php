<?php

namespace app\modules\admision\controllers;

use Yii;
use app\models\ExportFile;
use app\modules\admision\models\Oportunidad;
use app\modules\admision\models\EstadoOportunidad;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\TipoOportunidadVenta;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\UnidadAcademica;
use app\modules\admision\models\BitacoraSeguimiento;
use app\modules\admision\models\ActividadSeguimiento;
use app\models\Empresa;
use app\models\Persona;
use app\models\Usuario;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\admision\models\BitacoraActividadesTmp;

class OportunidadesController extends \app\components\CController {

    public function actionIndex() {
        //$per_id = @Yii::$app->session->get("PB_iduser");
        $modoportunidad = new Oportunidad();
        $modEstOport = new EstadoOportunidad();
        $empresa_mod = new Empresa();
        $estado_oportunidad = $modEstOport->consultarEstadOportunidad();
        $empresa = $empresa_mod->getAllEmpresa();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["agente"] = $data['agente'];
            $arrSearch["interesado"] = $data['interesado'];
            $arrSearch["empresa"] = $data['empresa'];
            $arrSearch["estado"] = $data['estado'];
            $arrSearch["fec_registro_ini"] = $data['fecregini'];
            $arrSearch["fec_registro_fin"] = $data['fecregfin'];
            $arrSearch["fec_proxima_ini"] = $data['fecproxini'];
            $arrSearch["fec_proxima_fin"] = $data['fecproxfin'];
            $mod_gestion = $modoportunidad->consultarOportunidad($arrSearch, 2);
        } else {
            $mod_gestion = $modoportunidad->consultarOportunidad($arrSearch, 2);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        return $this->render('index', [
                    'model' => $mod_gestion,
                    'arr_estgestion' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $estado_oportunidad), "id", "name"),
                    'arr_empresa' => ArrayHelper::map(array_merge([["id" => "0", "value" => "Todas"]], $empresa), "id", "value"),
        ]);
    }

    public function actionView() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $pges_id = base64_decode($_GET["pges_id"]);
        $opor_id = base64_decode($_GET["opor_id"]);
        $persges_mod = new PersonaGestion();
        $modoportunidad = new Oportunidad();
        $modestudio = new ModuloEstudio();
        $respOportunidad = $modoportunidad->consultarOportunidadById($opor_id);
        $resptipocarrera = $modoportunidad->consultarNombreCarrera($respOportunidad["subcarera_id"]);
        $arr_carrerra1 = $modestudio->consultarEstudioEmpresa($respOportunidad["empresa"]); // tomar id de impresa
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $uni_aca_model = new UnidadAcademica();
        $modalidad_model = new Modalidad();
        $state_oportunidad_model = new EstadoOportunidad();
        $unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
        $modalidad_data = $modalidad_model->consultarModalidad($respOportunidad["uaca_id"], $respOportunidad["empresa"]);
        $modcanal = new Oportunidad();
        $tipo_oportunidad_data = TipoOportunidadVenta::find()->select("tove_id AS id, tove_nombre AS name")->where(["tove_estado_logico" => "1", "tove_estado" => "1"])->asArray()->all();
        $state_oportunidad_data = $state_oportunidad_model->consultarEstadOportunidad();
        $academic_study_data = $modcanal->consultarCarreraModalidad(1, 1);
        $knowledge_channel_data = $modcanal->consultarConocimientoCanal(1);
        $empresa_mod = new Empresa();
        $empresa = $empresa_mod->getAllEmpresa();

        return $this->render('view', [
                    'personalData' => $contactManage,
                    'arr_linea_servicio' => ArrayHelper::map($unidad_acad_data, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($modalidad_data, "id", "name"),
                    'arr_tipo_oportunidad' => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
                    'arr_state_oportunidad' => ArrayHelper::map($state_oportunidad_data, "id", "name"),
                    'arr_academic_study' => ArrayHelper::map($academic_study_data, "id", "name"),
                    "arr_knowledge_channel" => ArrayHelper::map($knowledge_channel_data, "id", "name"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
                    'arr_oportunidad' => $respOportunidad,
                    "arr_modulo_estudio" => ArrayHelper::map($arr_carrerra1, "id", "name"),
                    "opo_id" => $opor_id,
                    "pges_id" => $pges_id,
                    "tipocarrera" => $resptipocarrera,
        ]);
    }

    public function actionEdit() {
        $opor_id = base64_decode($_GET["codigo"]);
        $pges_id = base64_decode($_GET["pgesid"]);
        //$per_id = @Yii::$app->session->get("PB_perid");
        $persges_mod = new PersonaGestion();
        $uni_aca_model = new UnidadAcademica();
        $modalidad_model = new Modalidad();
        $modTipoOportunidad = new TipoOportunidadVenta();
        $state_oportunidad_model = new EstadoOportunidad();
        $modoportunidad = new Oportunidad();
        $empresa_mod = new Empresa();
        $modestudio = new ModuloEstudio();
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $respOportunidad = $modoportunidad->consultarOportunidadById($opor_id);
        $unidad_acad_data = $uni_aca_model->consultarUnidadAcademicasEmpresa($respOportunidad["empresa"]);
        $modalidad_data = $modalidad_model->consultarModalidad($respOportunidad["uaca_id"], $respOportunidad["empresa"]);
        $tipo_oportunidad_data = $modTipoOportunidad->consultarOporxUnidad($respOportunidad["uaca_id"]);
        $state_oportunidad_data = $state_oportunidad_model->consultarEstadOportunidad();
        $academic_study_data = $modoportunidad->consultarCarreraModalidad($respOportunidad["uaca_id"], $respOportunidad["mod_id"]);
        $knowledge_channel_data = $modoportunidad->consultarConocimientoCanal(1);
        $arr_carrerra2 = $modoportunidad->consultarTipoCarrera();
        $arr_subcarrera = $modoportunidad->consultarSubCarrera($respOportunidad["tcar_id"]);
        //$arr_moduloEstudio = $modestudio->consultarEstudioEmpresa($respOportunidad["empresa"]); // tomar id de impresa
        $arr_moduloEstudio = $modestudio->consultarCursoModalidad($respOportunidad["uaca_id"], $respOportunidad["mod_id"], $respOportunidad["empresa"]);
        $respRolPerAutentica = $modoportunidad->consultarAgenteAutenticado($per_id);
        $empresa = $empresa_mod->getAllEmpresa();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $modalidad_model->consultarModalidad($data["ninter_id"], $data["empresa_id"]);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getoportunidad"])) {
                $oportunidad = $modTipoOportunidad->consultarOporxUnidad($data["unidada"]);
                $message = array("oportunidad" => $oportunidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getsubcarrera"])) {
                $subcarrera = $modoportunidad->consultarSubCarrera($data["car_id"]);
                $message = array("subcarrera" => $subcarrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                if ($data["unidada"] < 3) {
                    if (($data["unidada"] == 1 || $data["unidada"] == 2 ) && $data["empresa_id"] == 2) {
                        $carrera = $modestudio->consultarCursoModalidad($data["unidada"], $data["moda_id"], $data["empresa_id"]);
                    } else {
                        $carrera = $modoportunidad->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                    }
                } else {
                    $carrera = $modestudio->consultarCursoModalidad($data["unidada"], $data["moda_id"], $data["empresa_id"]);
                }
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

        //if (($respOportunidad["per_id"] == $per_id) || ($respRolPerAutentica["rol"] == 'SUP')) {
        return $this->render('edit', [
                    'personalData' => $contactManage,
                    'dataOportunidad' => $respOportunidad,
                    'arr_linea_servicio' => ArrayHelper::map($unidad_acad_data, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($modalidad_data, "id", "name"),
                    'arr_tipo_oportunidad' => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
                    'arr_state_oportunidad' => ArrayHelper::map($state_oportunidad_data, "id", "name"),
                    'arr_academic_study' => ArrayHelper::map($academic_study_data, "id", "name"),
                    "arr_knowledge_channel" => ArrayHelper::map($knowledge_channel_data, "id", "name"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "arr_carrerra2" => ArrayHelper::map($arr_carrerra2, "id", "name"),
                    "arr_subcarrerra" => ArrayHelper::map($arr_subcarrera, "id", "name"),
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
                    'arr_moduloEstudio' => ArrayHelper::map($arr_moduloEstudio, "id", "name"),
                    'opo_id' => $opor_id,
                    'pges_id' => $pges_id,
        ]);
    }

    public function actionNewoportunidadxcontacto() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $_SESSION['JSLANG']['Please enter a valid dni.'] = admision::t("crm",'Please enter a valid dni.');
        $_SESSION['JSLANG']['Please enter a valid Email.'] = admision::t("crm",'Please enter a valid Email.');
        $_SESSION['JSLANG']['Enter a Type Contact.'] = admision::t('crm', 'Enter a Type Contact.');
        $pges_id = base64_decode($_GET["pgid"]);
        $persges_mod = new PersonaGestion();
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $uni_aca_model = new UnidadAcademica();
        $modalidad_model = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modTipoOportunidad = new TipoOportunidadVenta();
        $state_oportunidad_model = new EstadoOportunidad();
        $unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
        $modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]["id"], $emp_id);
        $modcanal = new Oportunidad();
        $tipo_oportunidad_data = $modTipoOportunidad->consultarOporxUnidad($unidad_acad_data[0]["id"]);
        $state_oportunidad_data = $state_oportunidad_model->consultarEstadOportunidad();
        $academic_study_data = $modcanal->consultarCarreraModalidad($unidad_acad_data[0]["id"], $modalidad_data[0]["id"]);
        $knowledge_channel_data = $modcanal->consultarConocimientoCanal(1);
        $arr_moduloEstudio = $modestudio->consultarEstudioEmpresa($respOportunidad["empresa"]); // tomar id de impresa
        $empresa_mod = new Empresa();
        $empresa = $empresa_mod->getAllEmpresa();
        $modelSegui = BitacoraSeguimiento::findAll(['bseg_estado' => '1', 'bseg_estado_logico' => '1']);
        $arrSeg = array('Todos');
        $arrSeg = array_merge($arrSeg, ArrayHelper::getColumn($modelSegui, "bseg_nombre"));
        unset($arrSeg[0]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getuacademias"])) {
                //$data_u_acad = $uni_aca_model->consultarUnidadAcademicasEmpresa($data["empresa_id"]);
                $data_u_acad = $uni_aca_model->consultarUnidadAcademicas();
                $message = array("unidad_academica" => $data_u_acad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmodalidad"])) {
                if (($data["nint_id"]==1) or ($data["nint_id"]==2) or ($data["nint_id"]== 10)){
                    $modalidad = $modalidad_model->consultarModalidad($data["nint_id"], $data["empresa_id"]);
                } else {
                    $modalidad = $modestudio->consultarModalidadModestudio();
                }
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getoportunidad"])) {
                $oportunidad = $modTipoOportunidad->consultarOporxUnidad($data["unidada"]);
                $message = array("oportunidad" => $oportunidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getsubcarrera"])) {
                $subcarrera = $modcanal->consultarSubCarrera($data["car_id"]);
                $message = array("subcarrera" => $subcarrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmodalidaemp"])) {
                $modalidaemp = $modestudio->consultarEstudioEmpresa($data["empresa"]);
                $message = array("modalidaemp" => $modalidaemp);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                if (($data["unidada"] ==1) or ($data["unidada"] ==2) or ($data["unidada"]== 10)) {
                    $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                } else {
                    $carrera = $modestudio->consultarCursoModalidad($data["unidada"], $data["moda_id"] ); // tomar id de impresa
                }
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_carrerra2 = $modcanal->consultarTipoCarrera();
        $arr_subcarrera = $modcanal->consultarSubCarrera(1);
        return $this->render('newoportunidadxcontacto', [
                    'personalData' => $contactManage,
                    'arr_linea_servicio' => ArrayHelper::map($unidad_acad_data, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($modalidad_data, "id", "name"),
                    'arr_tipo_oportunidad' => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
                    'arr_state_oportunidad' => ArrayHelper::map($state_oportunidad_data, "id", "name"),
                    'arr_academic_study' => ArrayHelper::map($academic_study_data, "id", "name"),
                    "arr_knowledge_channel" => ArrayHelper::map($knowledge_channel_data, "id", "name"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "arr_carrerra2" => ArrayHelper::map($arr_carrerra2, "id", "name"),
                    "arr_subcarrerra" => ArrayHelper::map($arr_subcarrera, "id", "name"),
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
                    "emp_id" => $emp_id,
                    'arr_seguimiento' => $arrSeg,
        ]);
    }

    public function actionSave() {
        $per_id = @Yii::$app->session->get("PB_perid"); //ESTO DESCOMENTAR AL FINAL
        //$per_id = 6;
        $mod_gestion = new Oportunidad();
        //$scli_id = 2;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $pges_id = base64_decode($data["id_pgest"]);
            $modelPges = PersonaGestion::findOne($pges_id);
            if(isset($data["cedula"]) && $data["cedula"] != "" && $data["id_estado_oportunidad"] == 3){
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
            if(isset($data["correo"]) && $data["correo"] != "" && $data["id_estado_oportunidad"] == 3){
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
            $empresa = $data["empresa"];
            // $modulo_estudio = $data["modulo_estudio"];
            $unidad_academica = $data["id_unidad_academica"];
            $modalidad = $data["id_modalidad"];
            $tipo_oportunidad = $data["id_tipo_oportunidad"];
            $estado_oportunidad = $data["id_estado_oportunidad"];
            if ($unidad_academica < 3) {
                if (($unidad_academica == 1 || $unidad_academica == 2 ) && $empresa == 2) {
                    $estudio_academico = '';
                    $modulo_estudio = $data["id_estudio_academico"];
                } else {
                    $estudio_academico = $data["id_estudio_academico"];
                    $modulo_estudio = '';
                }
            } else {
                $estudio_academico = '';
                $modulo_estudio = $data["id_estudio_academico"];
            }
            $canal_conocimiento = $data["canal_conocimiento"];
            $sub_carrera = ($data["sub_carrera"] != 0) ? $data["sub_carrera"] : null;
            $usuario = @Yii::$app->user->identity->usu_id;
            $con = \Yii::$app->db_crm;
            $agente = $mod_gestion->consultarAgenteAutenticado($per_id); //QUITAR 1 AGENTE ADMIN
            //$nombreoportunidad = $mod_gestion->consultarNombreOportunidad($empresa, $modulo_estudio, $estudio_academico, $unidad_academica, $modalidad, $estado_oportunidad);
            $nombreoportunidad = $mod_gestion->consultarNombreOportunidad($empresa, $modulo_estudio, $estudio_academico, $unidad_academica, $modalidad, $estado_oportunidad);

            $transaction = $con->beginTransaction();
            try {
                $gcrm_codigo = $mod_gestion->consultarUltimoCodcrm();
                //$per_gest = $mod_pergestion->consultarPersonaGestion($pges_id);
                $codportunidad = 1 + $gcrm_codigo;
                $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
                if ($agente['padm_id'] > 0) {
                    //if ($nombreoportunidad["eopo_nombre"] == '' || $nombreoportunidad["eopo_nombre"] == 'Ganada' || $nombreoportunidad["eopo_nombre"] == 'Perdida') {
                    if ($nombreoportunidad["Ids"] == '' || $nombreoportunidad["Ids"] == '4' || $nombreoportunidad["Ids"] == '5') {
                        $res_gestion = $mod_gestion->insertarOportunidad($codportunidad, $empresa, $pges_id, $modulo_estudio, $estudio_academico, $unidad_academica, $modalidad, $tipo_oportunidad, $sub_carrera, $canal_conocimiento, $estado_oportunidad,null, null,  $fecha_registro, $agente['padm_id'], $usuario);
                        if ($res_gestion) {
                            $opo_id = $res_gestion;
                            $padm_id = $agente['padm_id'];
                            $eopo_id = $estado_oportunidad; // En curso por defecto
                            $bact_fecha_registro = $fecha_registro;
                            $bact_fecha_proxima_atencion = $fecha_registro;

                            //$bact_descripcion = (!$nombreoportunidad["Ids"]) ? 'Inicio de Operaciones' : '';
                            $oact_id = 1;
                            $bact_descripcion = "";
                            $res_actividad = $mod_gestion->insertarActividad($opo_id, $usuario, $padm_id, $eopo_id, $bact_fecha_registro, $oact_id, $bact_descripcion, $bact_fecha_proxima_atencion);
                            if ($res_actividad) {
                                if(isset($data["seguimiento"])){
                                    foreach($data["seguimiento"] as $key => $value){
                                        $modelSeguimiento = new ActividadSeguimiento();
                                        $modelSeguimiento->bseg_id = $value;
                                        $modelSeguimiento->bact_id = $res_actividad;
                                        $modelSeguimiento->aseg_estado = '1';
                                        $modelSeguimiento->aseg_estado_logico = '1';
                                        if(!$modelSeguimiento->save()){
                                            throw new \Exception("Error al grabar");
                                        }
                                    }
                                }
                                $transaction->commit();
                                $message = array(
                                    "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                                    "title" => Yii::t('jslang', 'Success'),
                                );
                                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                            } else {
                                $transaction->rollback();
                                $message = array(
                                    "wtmessage" => Yii::t("notificaciones", "Error al grabar"),
                                    "title" => Yii::t('jslang', 'Bad Request'),
                                );
                                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                            }
                        } else {
                            $transaction->rollback();
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Error al grabar"),
                                "title" => Yii::t('jslang', 'Bad Request'),
                            );
                            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                        }
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar, Existe una oportunidad con estos datos."),
                            "title" => Yii::t('jslang', 'Bad Request'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar. Usuario no cuenta con permisos"),
                        "title" => Yii::t('jslang', 'Bad Request'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                }
            } catch (\Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Bad Request'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
            }
            return;
        }
    }

    public function actionUpdate() {
        $mod_oportunidad = new Oportunidad();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $opo_id = base64_decode($data["opo_id"]);
            $pges_id = base64_decode($data["pgid"]);
            $empresa = $data["empresa"];
            $mest_id = null;
            $eaca_id = null;
            $unidad_academica = $data["uaca_id"];
            $modalidad = $data["modalidad"];
            $tipo_oportunidad = $data["tipoOport"];
            $estado_oportunidad = $data["estado"];
            $carrera_estudio = $data["carreraestudio"];
            /*if ($unidad_academica < 3) {
                $eaca_id = $carrera_estudio;
            } else {
                $mest_id = $carrera_estudio;
            }*/
            if ($unidad_academica < 3) {
                if (($unidad_academica == 1 || $unidad_academica == 2 ) && $empresa == 2) {
                    $mest_id = $carrera_estudio;
                } else {
                    $eaca_id = $carrera_estudio;
                }
            } else {
                $mest_id = $carrera_estudio;
            }
            $canal_conocimiento = $data["canal"];
            $sub_carrera = $data["subcarrera"];
            if ($sub_carrera == 0) {
                $sub_carrera = null;
            }
            $usuario = @Yii::$app->user->identity->usu_id;

            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                $nombreoportunidad = $mod_oportunidad->consultarNombreOportunidad($empresa, $mest_id, $eaca_id, $unidad_academica, $modalidad, $estado_oportunidad);
                //if ($nombreoportunidad["eopo_nombre"] == '' || $nombreoportunidad["eopo_nombre"] == 'Ganada' || $nombreoportunidad["eopo_nombre"] == 'Perdida') {
                $respuesta = $mod_oportunidad->modificarOportunixId($empresa, $opo_id, $mest_id, $eaca_id, $unidad_academica, $modalidad, $tipo_oportunidad, $sub_carrera, $canal_conocimiento, null, null, null, $usuario, null);
                if ($respuesta) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La información ha sido modificada. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al modificar." . $mensaje),
                        "title" => Yii::t('jslang', 'Bad Request'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                }
                /* } else {
                  $transaction->rollback();
                  $message = array(
                  "wtmessage" => Yii::t("notificaciones", "En el estado que se encuentra no se puede modiifcar" . $mensaje),
                  "title" => Yii::t('jslang', 'Bad Request'),
                  );
                  return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                  } */
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al modificar." . $mensaje),
                    "title" => Yii::t('jslang', 'Bad Request'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
            }
            return;
        }
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");

        $arrHeader = array(
            admision::t("crm", "No Opportunity"),
            admision::t("crm", "Contact"),
            Yii::t("formulario", "Company"),
            Yii::t("formulario", "Aca. Uni."),
            admision::t("crm", "Career/Program/Course"),
            admision::t("crm", "Moda"),
            Yii::t("formulario", "Status"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Date Next attention"),
            Yii::t("formulario", "Agent"),
            //" ",
        );
        $data = Yii::$app->request->get();
        $arrSearch["interesado"] = $data["contacto"];
        //$arrSearch["agente"] = $data["search"];
        $arrSearch["empresa"] = $data['empresa'];
        $arrSearch["estado"] = $data["f_estado"];
        $arrSearch["fec_registro_ini"] = $data['fecregistroini'];
        $arrSearch["fec_registro_fin"] = $data['fecregistrofin'];
        $arrSearch["fec_proxima_ini"] = $data['fecproximaini'];
        $arrSearch["fec_proxima_fin"] = $data['fecproximafin'];

        $modoportunidad = new Oportunidad();
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $modoportunidad->consultarOportunidadexcel(array(), true);
        } else {
            $arrData = $modoportunidad->consultarOportunidadexcel($arrSearch, true);
        }
        $nameReport = admision::t("crm", "List Oportunity");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfoportunidades() {
        $report = new ExportFile();
        $this->view->title = admision::t("crm", "List Oportunity"); // Titulo del reporte

        $modoportunidad = new Oportunidad();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["interesado"] = $data["contacto"];
        $arrSearch["agente"] = $data["search"];
        $arrSearch["empresa"] = $data['empresa'];
        $arrSearch["estado"] = $data["f_estado"];
        $arrSearch["fec_registro_ini"] = $data['fecregistroini'];
        $arrSearch["fec_registro_fin"] = $data['fecregistrofin'];
        $arrSearch["fec_proxima_ini"] = $data['fecproximaini'];
        $arrSearch["fec_proxima_fin"] = $data['fecproximafin'];

        $arr_head = array(
            admision::t("crm", "No Opportunity"),
            admision::t("crm", "Contact"),
            Yii::t("formulario", "Company"),
            Yii::t("formulario", "Aca. Uni."),
            admision::t("crm", "Career/Program/Course"),
            admision::t("crm", "Moda"),
            Yii::t("formulario", "Status"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Date Next attention"),
            Yii::t("formulario", "Agent"),
        );

        if (empty($arrSearch)) {
            $arr_body = $modoportunidad->consultarOportunidadexcel(array(), true);
        } else {
            $arr_body = $modoportunidad->consultarOportunidadexcel($arrSearch, true);
        }

        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arr_head,
                    'arr_body' => $arr_body
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionCargargestion() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $mod_gestion = new Oportunidad();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "gestion/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                $emp_id = $data["emp_id"];
                $mod_actividadTemp = new BitacoraActividadesTmp();
                $resp_padm = $mod_actividadTemp->consultarIdXPadm($per_id);   //Buscar el Padm_id
                if ($resp_padm) {
                    $carga_archivo = $mod_gestion->CargarArchivoGestion($emp_id, $data["archivo"], $usu_id, $resp_padm["padm_id"]);
                    if ($carga_archivo['status']) {
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente. " . $carga_archivo['message']),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                    } else {
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo. " . $carga_archivo['message']),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                    }
                } else {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo, no es un usuario autorizado para realizar la carga."),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
                return;
            }
        } else {
            return $this->render('cargargestion', []);
        }
    }
}
