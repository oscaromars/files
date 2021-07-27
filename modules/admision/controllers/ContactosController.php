<?php

namespace app\modules\admision\controllers;

use Yii;
use app\modules\admision\models\EstadoContacto;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\Oportunidad;
use app\modules\admision\models\PersonalAdmision;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\TipoOportunidadVenta;
use app\modules\admision\models\EstadoOportunidad;
use app\modules\admision\models\BitacoraSeguimiento;
use app\modules\admision\models\ActividadSeguimiento;
use app\models\Empresa;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\models\ExportFile;

academico::registerTranslations();
admision::registerTranslations();
financiero::registerTranslations();

class ContactosController extends \app\components\CController {

    public function actionIndex() {
        $per_id = @Yii::$app->session->get("PB_iduser");
        $modcanal = new Oportunidad();
        $empresa_mod = new Empresa();
        $mod_unidad = new UnidadAcademica();
        $estado_contacto = EstadoContacto::find()->select("econ_id AS id, econ_nombre AS name")->where(["econ_estado_logico" => "1", "econ_estado" => "1"])->orderBy("name asc")->asArray()->all();
        $modPersonaGestion = new PersonaGestion();
        $modagente = new PersonalAdmision();
        $empresa = $empresa_mod->getAllEmpresa();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["estado"] = $data['estado'];
            $arrSearch["fase"] = $data['fase'];
            $arrSearch["medio"] = $data['medio'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["agente"] = $data['agente'];
            $arrSearch["correo"] = $data['correo'];
            $arrSearch["telefono"] = $data['telefono'];
            $arrSearch["empresa"] = $data['empresa'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["gestion"] = $data['gestion'];
            $mod_gestion = $modPersonaGestion->consultarClienteCont($arrSearch);
            return $this->render('index-grid', [
                        "model" => $mod_gestion,
            ]);
        } else {
            $mod_gestion = $modPersonaGestion->consultarClienteCont();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $arra_agente = $modagente->consultarAgenteconta();
        $canalconta = $modcanal->consultarConocimientoCanal('1');
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(0);
        $arrEstados = ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Grid")], ["id" => "1", "value" => Yii::t("formulario", "Pendiente Gestionar")], ["id" => "2", "value" => Yii::t("formulario", "Gestionado")]]), "id", "value");
        return $this->render('index', [
                    'model' => $mod_gestion,
                    'arr_contacto' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $estado_contacto), "id", "name"),
                    'arr_canalconta' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $canalconta), "id", "name"),
                    'arra_agente' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"], ["id" => "1", "name" => "Admin UTEG"]], $arra_agente), "id", "name"),
                    'arr_empresa' => ArrayHelper::map(array_merge([["id" => "0", "value" => "Todas"]], $empresa), "id", "value"),
                    'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $arr_unidad), "id", "name"),
                    'arr_estado_gestion' => $arrEstados,
        ]);
    }

    public function actionNew() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $modcanal = new Oportunidad();
        $mod_pais = new Pais();
        $pais_id = 1; //Ecuador
        $con_agente = $modcanal->consultarAgenteAutenticado($per_id);
        $empresa_mod = new Empresa();
        $empresa = $empresa_mod->getAllEmpresa();
        $uni_aca_model = new UnidadAcademica();
        $modalidad_model = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modTipoOportunidad = new TipoOportunidadVenta();
        $state_oportunidad_model = new EstadoOportunidad();
        $canalconta = $modcanal->consultarConocimientoCanal('1');
        $unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
        $modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]["id"], $emp_id);
        $_SESSION['JSLANG']['Enter a Type Contact.'] = admision::t('crm', 'Enter a Type Contact.');

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getarea"])) {
                $area = $mod_pais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getuacademias"])) {
                //$data_u_acad = $uni_aca_model->consultarUnidadAcademicasEmpresa($data["empresa_id"]);
                $data_u_acad = $uni_aca_model->consultarUnidadAcademicas();
                $message = array("unidad_academica" => $data_u_acad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmodalidad"])) {
                if (($data["nint_id"]==1) or ($data["nint_id"]==2)){
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
                if (($data["unidada"] ==1) or ($data["unidada"] ==2)) {
                    $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                } else {
                    $carrera = $modestudio->consultarCursoModalidad($data["unidada"], $data["moda_id"] ); // tomar id de impresa
                }
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value, pai_codigo_fono AS code")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov = Provincia::provinciaXPais($pais_id);
        $arr_ciu = Canton::cantonXProvincia($arr_prov[0]["id"]);
        $area = $mod_pais->consultarCodigoArea($pais_id);

        $modcanal = new Oportunidad();
        $tipo_oportunidad_data = $modTipoOportunidad->consultarOporxUnidad($unidad_acad_data[0]["id"]);
        $state_oportunidad_data = $state_oportunidad_model->consultarEstadOportunidad();
        $academic_study_data = $modcanal->consultarCarreraModalidad($unidad_acad_data[0]["id"], $modalidad_data[0]["id"]);
        $knowledge_channel_data = $modcanal->consultarConocimientoCanal(1);
        $modelSegui = BitacoraSeguimiento::findAll(['bseg_estado' => '1', 'bseg_estado_logico' => '1']);
        $arrSeg = array('Todos');
        $arrSeg = array_merge($arrSeg, ArrayHelper::getColumn($modelSegui, "bseg_nombre"));
        unset($arrSeg[0]);
        $arr_carrerra2 = $modcanal->consultarTipoCarrera();
        $arr_subcarrera = $modcanal->consultarSubCarrera(1);
        return $this->render('new', [
                    "arr_pais" => $arr_pais,
                    "arr_prov" => ArrayHelper::map($arr_prov, "id", "value"),
                    "arr_ciu" => ArrayHelper::map($arr_ciu, "id", "value"),
                    "area" => $area['name'],
                    "arr_canalconta" => ArrayHelper::map($canalconta, "id", "name"),
                    //"agente_autentica" => $cargo["car_id"],
                    "agente_cargo" => $con_agente["padm_id"],
                    "persona_autentica" => $per_id,
                    "emp_id" => $emp_id,
                    'arr_empresa' => ArrayHelper::map($empresa, "id", "value"),
                    'arr_linea_servicio' => ArrayHelper::map($unidad_acad_data, "id", "name"),
                    'unidad_acad_data' => $unidad_acad_data,
                    'arr_modalidad' => ArrayHelper::map($modalidad_data, "id", "name"),
                    'arr_tipo_oportunidad' => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
                    'arr_state_oportunidad' => ArrayHelper::map($state_oportunidad_data, "id", "name"),
                    'arr_academic_study' => ArrayHelper::map($academic_study_data, "id", "name"),
                    "arr_knowledge_channel" => ArrayHelper::map($knowledge_channel_data, "id", "name"),
                    'arr_seguimiento' => $arrSeg,
                    "arr_carrerra2" => ArrayHelper::map($arr_carrerra2, "id", "name"),
                    "arr_subcarrerra" => ArrayHelper::map($arr_subcarrera, "id", "name"),
        ]);
    }

    public function actionView() {
        $modcanal = new Oportunidad();
        $mod_pais = new Pais();
        $pges_id = base64_decode($_GET["codigo"]);
        $tper = base64_decode($_GET["tper"]);

        $arra_contacto = $modcanal->ConsultarPersonaxGestion($pges_id);
        $arra_para_contacto = $modcanal->consultarDatoContact($pges_id);
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value, pai_codigo_fono AS code")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov = Provincia::provinciaXPais($arra_contacto['pai_id_nacimiento']);
        if (empty($arra_contacto['pro_id_nacimiento']) || $arra_contacto['pro_id_nacimiento'] == '') {
            $arr_ciu = Canton::cantonXProvincia($arr_prov[0]['id']);
        } else {
            $arr_ciu = Canton::cantonXProvincia($arra_contacto['pro_id_nacimiento']);
        }
        $area = $mod_pais->consultarCodigoArea($arra_contacto['pai_id_nacimiento']);
        $conocimiento = $modcanal->consultarConocimientoCanal(1);
        $modestCli = new EstadoContacto();
        $estado_cliente = $modestCli->consultarEstadoContacto();

        return $this->render('view', [
                    "arr_pais" => ArrayHelper::map($arr_pais, "id", "value"),
                    "arr_prov" => ArrayHelper::map($arr_prov, "id", "value"),
                    "arr_ciu" => ArrayHelper::map($arr_ciu, "id", "value"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "celular" => $arra_contacto['celular'],
                    "cedula" => $arra_contacto['cedula'],
                    "area" => $area['name'],
                    "convenc" => $arra_contacto['pges_domicilio_telefono'],
                    "telf" => $arra_contacto['pges_domicilio_celular2'],
                    "correo" => $arra_contacto['correo'],
                    "arr_conocimiento" => ArrayHelper::map($conocimiento, "id", "name"),
                    "mcon_id" => $arra_contacto['cod_medio_contacto'],
                    "tper_id" => $tper,
                    "arr_estcliente" => ArrayHelper::map($estado_cliente, "id", "name"),
                    "ecli_id" => $arra_contacto['cod_estado'],
                    "arra_pnomb_con" => $arra_contacto['primer_nombre'],
                    "arra_snomb_con" => $arra_contacto['segundo_nombre'],
                    "arra_papellido_con" => $arra_contacto['primer_apellido'],
                    "arra_sapellido_con" => $arra_contacto['segundo_apellido'],
                    "arra_nombre_paracont" => $arra_para_contacto['pgco_nombres'],
                    "pais" => $arra_contacto['pai_id_nacimiento'],
                    "provincia" => $arra_contacto['pro_id_nacimiento'],
                    "ciudad" => $arra_contacto['can_id_nacimiento'],
                    "arr_datosc" => $arra_contacto,
                    "arr_datosb" => $arra_para_contacto,
                    "pges_id" => $pges_id,
        ]);
    }

    public function actionEdit() {
        $modcanal = new Oportunidad();
        $mod_pais = new Pais();
        $pges_id = base64_decode($_GET["codigo"]);
        $tper = base64_decode($_GET["tper_id"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

        $arra_contacto = $modcanal->ConsultarPersonaxGestion($pges_id);
        $arra_para_contacto = $modcanal->consultarDatoContact($pges_id);
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value, pai_codigo_fono AS code")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov = Provincia::provinciaXPais($arra_contacto['pai_id_nacimiento']);
        if (empty($arra_contacto['pro_id_nacimiento']) || $arra_contacto['pro_id_nacimiento'] == '') {
            $arr_ciu = Canton::cantonXProvincia($arr_prov[0]['id']);
        } else {
            $arr_ciu = Canton::cantonXProvincia($arra_contacto['pro_id_nacimiento']);
        }
        $area = $mod_pais->consultarCodigoArea($arra_contacto['pai_id_nacimiento']);
        $conocimiento = $modcanal->consultarConocimientoCanal(1);
        $modEstCli = new EstadoContacto();
        $estado_cliente = $modEstCli->consultarEstadoContacto();

        return $this->render('edit', [
                    "arr_pais" => ArrayHelper::map($arr_pais, "id", "value"),
                    "arr_prov" => ArrayHelper::map($arr_prov, "id", "value"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "arr_ciu" => ArrayHelper::map($arr_ciu, "id", "value"),
                    "cedula" => $arra_contacto['cedula'],
                    "celular" => $arra_contacto['celular'],
                    "area" => $area['name'],
                    "convenc" => $arra_contacto['pges_domicilio_telefono'],
                    "telf" => $arra_contacto['pges_domicilio_celular2'],
                    "correo" => $arra_contacto['correo'],
                    "arr_conocimiento" => ArrayHelper::map($conocimiento, "id", "name"),
                    "mcon_id" => $arra_contacto['cod_medio_contacto'],
                    "pges_id" => $arra_contacto['pges_id'],
                    "tper_id" => $tper,
                    "arr_estcliente" => ArrayHelper::map($estado_cliente, "id", "name"),
                    "ecli_id" => $arra_contacto['cod_estado'],
                    "arra_pnomb_con" => $arra_contacto['primer_nombre'],
                    "arra_snomb_con" => $arra_contacto['segundo_nombre'],
                    "arra_papellido_con" => $arra_contacto['primer_apellido'],
                    "arra_sapellido_con" => $arra_contacto['segundo_apellido'],
                    "pais" => $arra_contacto['pai_id_nacimiento'],
                    "provincia" => $arra_contacto['pro_id_nacimiento'],
                    "ciudad" => $arra_contacto['can_id_nacimiento'],
                    "arr_datosc" => $arra_contacto,
                    "arr_datosb" => $arra_para_contacto,
        ]);
    }

    public function actionSave() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuario_ingreso = @Yii::$app->session->get("PB_iduser");
        $celular = null;
        $celular2 = null;
        $telefono = null;
        $celularcontacto = null;
        $telefonocontacto = null;
        $correocontacto = null;
        $paiscontacto = null;
        $busqueda = 0;
        $conoce_uteg = null;
        $carrera = null;
        $correo = strtolower($data["correo"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $nombre1 = ucwords(strtolower($data["txt_nombre1"]));
            $nombre2 = ucwords(strtolower($data["txt_nombre2"]));
            $apellido1 = ucwords(strtolower($data["txt_apellido1"]));
            $apellido2 = ucwords(strtolower($data["txt_apellido2"]));

            $tipo_persona = $data["tipo_persona"];
            $empresa = ucwords(strtolower($data["empresa"]));
            $telefono_empresa = $data["telefono_empresa"];
            $direccion = ucwords(strtolower($data["direccion"]));
            $cargo = ucwords(strtolower($data["cargo"]));
            $contacto_empresa = ucwords(strtolower($data["contacto_empresa"]));
            $numero_contacto = ucwords(strtolower($data["numero_contacto"]));

            $pais = $data["pais"];
            $provincia = $data["provincia"];
            $ciudad = $data["ciudad"];
            if (!empty($data["celular"])) {
                $celular = $data["celular"];
            }
            if (!empty($data["correo"])) {
                $correo = strtolower($data["correo"]);
            }
            if (!empty($data["celular2"])) {
                $celular2 = $data["celular2"];
            }
            if (!empty($data["telefono"])) {
                $telefono = $data["telefono"];
            }
            $medio = $data["medio"];
            $contacto = $data["contacto"];
            $nombrebeni1 = ucwords(strtolower($data["txt_nombrebeni1"]));
            $nombrebeni2 = ucwords(strtolower($data["txt_nombrebeni2"]));
            $apellidobeni1 = ucwords(strtolower($data["txt_apellidobeni1"]));
            $apellidobeni2 = ucwords(strtolower($data["txt_apellidobeni2"]));

            if (!empty($data["celularbeni"])) {
                $celularcontacto = $data["celularbeni"];
            }
            if (!empty($data["telefonobeni"])) {
                $telefonocontacto = $data["telefonobeni"];
            }
            if (!empty($data["correobeni"])) {
                $correocontacto = strtolower($data["correobeni"]);
            }
            if (!empty($data["paisContacto"])) {
                $paiscontacto = strtolower($data["paisContacto"]);
            }
            $per_nac_ecuatoriano = $data["nacecuador"];
            $agente = $data["agente"];
            $usuario = @Yii::$app->user->identity->usu_id;

            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                if (!Utilities::validateTypeField($correo, "correo") && !Utilities::validateTypeField($celular, "number")) {
                    $message = array(
                        "wtmessage" => admision::t("crm", "Please enter at least one valid email or a cell phone."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
                $mod_pergestion = new PersonaGestion();
                $mod_gestion = new Oportunidad();
                if (!empty($celular) || !empty($correo) || !empty($telefono) || !empty($celular2)) {
                    $cons_persona = $mod_pergestion->consultarDatosExiste($celular, $correo, $telefono, $celular2, null, null);
                    if ($cons_persona["registro"] > 0) {
                        $busqueda = 1;
                    }
                }
                if ($busqueda == 0) {
                    //Obtener el número máximo de persona_gestion.
                    $resp_consulta = $mod_pergestion->consultarMaxPergest();
                    $resp_persona = $mod_pergestion->insertarPersonaGestion($resp_consulta["maximo"], $tipo_persona, $conoce_uteg, $carrera, $nombre1, $nombre2, $apellido1, $apellido2, null, null, null, null, null, null, null, $pais, $provincia, $ciudad, $per_nac_ecuatoriano, null, $celular, $correo, null, null, null, null, null, null, null, $telefono, $celular2, null, null, null, null, null, null, null, null, null, null, 1, $medio, $empresa, $contacto_empresa, $numero_contacto, $telefono_empresa, $direccion, $cargo, $usuario_ingreso);
                    if ($resp_persona) {
                        if ($contacto == '2') {  //Cuando tiene datos para contactar.
                            $resp_contacto = $mod_pergestion->insertarPersGestionContac($resp_persona, $nombrebeni1, $nombrebeni2, $apellidobeni1, $apellidobeni2, $correocontacto, $telefonocontacto, $celularcontacto, $paiscontacto);
                            if ($resp_contacto) {
                                $exito = 1;
                            }
                        } else {
                            $exito = 1;
                        }
                        if($data["genAspirante"] == "true"){
                            $pges_id = $resp_persona;
                            $modelPges = PersonaGestion::findOne($pges_id);
                            if(isset($data["cedula"]) && $data["cedula"] != ""){
                                $modelPges->pges_cedula = $data["cedula"];
                                $modelPges->save();
                            }
                            $empresa = $data["empresaid"];
                            // $modulo_estudio = $data["modulo_estudio"];
                            $unidad_academica = $data["id_unidad_academica"];
                            $modalidad = $data["id_modalidad"];
                            $tipo_oportunidad = $data["id_tipo_oportunidad"];
                            $estado_oportunidad = $data["id_estado_oportunidad"];
                            if ($unidad_academica < 3) {
                                if (($unidad_academica == 1 || $unidad_academica == 2 ) && $empresa == 2) {
                                    $estudio_academico = 0;
                                    $modulo_estudio = $data["id_estudio_academico"];
                                } else {
                                    $estudio_academico = $data["id_estudio_academico"];
                                    $modulo_estudio = 0;
                                }
                            } else {
                                $estudio_academico = 0;
                                $modulo_estudio = $data["id_estudio_academico"];
                            }
                            $canal_conocimiento = $data["canal_conocimiento"];
                            $sub_carrera = ($data["sub_carrera"] != 0) ? $data["sub_carrera"] : null;
                            $usuario = @Yii::$app->user->identity->usu_id;
                            $con = \Yii::$app->db_crm;
                            $agente = $mod_gestion->consultarAgenteAutenticado($per_id); //QUITAR 1 AGENTE ADMIN
                            //$nombreoportunidad = $mod_gestion->consultarNombreOportunidad($empresa, $modulo_estudio, $estudio_academico, $unidad_academica, $modalidad, $estado_oportunidad);
                            $nombreoportunidad = $mod_gestion->consultarNombreOportunidad($empresa, $modulo_estudio, $estudio_academico, $unidad_academica, $modalidad, $estado_oportunidad);
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
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar. " . $mensaje),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), true, $message);
                    }
                } else {
                    $mensaje = 'Registro ya existente';
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "No se puede guardar el contacto " . $mensaje),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), true, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
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
            Yii::t("crm", "Contact"),
            Yii::t("formulario", "Country"),
            Yii::t("formulario", "Email"),
            Yii::t("formulario", "CellPhone"),
            Yii::t("formulario", "Phone"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Academic unit"),
            admision::t("crm", "Channel"),
            Yii::t("formulario", "User login"),
            Yii::t("formulario", "Open Opportunities"),
            Yii::t("formulario", "Close Opportunities"),
            Yii::t("formulario", "Management State")
        );
        $modPersonaGestion = new PersonaGestion();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] =  $data['f_ini'];
        $arrSearch["f_fin"] =  $data['f_fin'];
        $arrSearch["medio"] =  $data['medio'];
        $arrSearch["agente"] = $data['agente'];
        $arrSearch["correo"] = $data['correo'];
        $arrSearch["telefono"] = $data['telefono'];
        $arrSearch["empresa"] = $data['empresa'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["gestion"] = $data['gestion'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $modPersonaGestion->consultarReportContactos(array(), true);
        } else {
            $arrData = $modPersonaGestion->consultarReportContactos($arrSearch, true);
        }
        $nameReport = admision::t("crm", "Contacts");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = admision::t("crm", "Contacts"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("crm", "Contact"),
            Yii::t("formulario", "Country"),
            Yii::t("formulario", "Email"),
            Yii::t("formulario", "CellPhone"),
            Yii::t("formulario", "Phone"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Academic unit"),
            admision::t("crm", "Channel"),
            Yii::t("formulario", "User login"),
            Yii::t("formulario", "Open Opportunities"),
            Yii::t("formulario", "Close Opportunities"),
            Yii::t("formulario", "Management State")
        );
        $modPersonaGestion = new PersonaGestion();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["medio"] = $data['medio'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["agente"] = $data['agente'];
        $arrSearch["correo"] = $data['correo'];
        $arrSearch["telefono"] = $data['telefono'];
        $arrSearch["empresa"] = $data['empresa'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["gestion"] = $data['gestion'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $modPersonaGestion->consultarReportContactos(array(), true);
        } else {
            $arrData = $modPersonaGestion->consultarReportContactos($arrSearch, true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionUpdate() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $celular = null;
        $celular2 = null;
        $telefono = null;
        $celular_pcontacto = null;
        $telefono_pcontacto = null;
        $correo_pcontacto = null;
        $pais_pcontacto = null;
        $nombre1_pcontacto = null;
        $nombre2_pcontacto = null;
        $apellido1_pcontacto = null;
        $apellido2_pcontacto = null;
        $conoce_uteg = null;
        $carrera = null;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $nombre1 = ucwords(strtolower($data["txt_nombre1"]));
            $nombre2 = ucwords(strtolower($data["txt_nombre2"]));
            $apellido1 = ucwords(strtolower($data["txt_apellido1"]));
            $apellido2 = ucwords(strtolower($data["txt_apellido2"]));
            if ($data["cmb_tipo_dni"] == 'CED') {
                $pasaporte = '';
                $cedula = $data["cedula"];
            } else {
                $cedula = '';
                $pasaporte = $data["cedula"];
            }
            $tipo_persona = base64_decode($data["tipo_persona"]);
            $empresa = ucwords(strtolower($data["empresa"]));
            $telefono_empresa = $data["telefono_empresa"];
            $direccion = ucwords(strtolower($data["direccion"]));

            $pais = $data["pais"];
            $pges_id = base64_decode($data["pges_id"]);
            $provincia = $data["provincia"];
            $ciudad = $data["ciudad"];
            if (!empty($data["celular"])) {
                $celular = $data["celular"];
            }
            if (!empty($data["celular2"])) {
                $celular2 = $data["celular2"];
            }
            if (!empty($data["telefono"])) {
                $telefono = $data["telefono"];
            }
            if (!empty($data["correo"])) {
                $correo = strtolower($data["correo"]);
            }
            $medio = $data["medio"];
            //Información de contacto.
            $pgco_id = base64_decode($data["perges_contacto"]);
            if ($pgco_id > 0) {
                $nombre1_pcontacto = ucwords(strtolower($data["txt_nombre1contacto"]));
                $nombre2_pcontacto = ucwords(strtolower($data["txt_nombre2contacto"]));
                $apellido1_pcontacto = ucwords(strtolower($data["txt_apellido1contacto"]));
                $apellido2_pcontacto = ucwords(strtolower($data["txt_apellido2contacto"]));
                if (!empty($data["txt_celularcontacto"])) {
                    $celular_pcontacto = $data["txt_celularcontacto"];
                }
                if (!empty($data["txt_telefonocontacto"])) {
                    $telefono_pcontacto = $data["txt_telefonocontacto"];
                }
                if (!empty($data["txt_correocontacto"])) {
                    $correo_pcontacto = strtolower($data["txt_correocontacto"]);
                }
                if (!empty($data["txt_paiscontacto"])) {
                    $pais_pcontacto = strtolower($data["txt_paiscontacto"]);
                }
            }  //
            $per_nac_ecuatoriano = $data["nacecuador"];
            $agente = $data["agente"];

            $usuario = @Yii::$app->user->identity->usu_id;
            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                $mod_pergestion = new PersonaGestion();
                $keys_act = [
                    'tper_id', 'cser_id', 'car_id', 'pges_pri_nombre', 'pges_seg_nombre'
                    , 'pges_pri_apellido', 'pges_seg_apellido', 'pges_cedula', 'pges_pasaporte'
                    , 'pai_id_nacimiento', 'pro_id_nacimiento', 'can_id_nacimiento'
                    , 'pges_celular', 'pges_correo', 'pges_domicilio_telefono'
                    , 'pges_domicilio_celular2', 'ccan_id', 'pges_razon_social'
                    , 'pges_contacto_empresa', 'pges_num_empleados', 'pges_telefono_empresa'
                    , 'pges_direccion_empresa', 'pges_cargo', 'pges_nac_ecuatoriano'
                ];
                $values_act = [
                    $tipo_persona, $conoce_uteg, $carrera, $nombre1, $nombre2,
                    $apellido1, $apellido2, $cedula, $pasaporte,
                    $pais, $provincia, $ciudad,
                    $celular, $correo, $telefono,
                    $celular2, $medio, $empresa,
                    $contacto_empresa, $numero_contacto, $telefono_empresa,
                    $direccion, $cargo, $per_nac_ecuatoriano
                ];
                $respPergestion = $mod_pergestion->actualizarPersonaGestion($con, $pges_id, $values_act, $keys_act, 'persona_gestion');
                if ($respPergestion) {
                    if ($pgco_id > 0) {  //Existe información para contactar.
                        //$mensaje = 'Id:'.$pgco_id. ' Nombre1:'.$nombre1_pcontacto. ' Nombre2:'. $nombre2_pcontacto . ' Apellido1:'. $apellido1_pcontacto. ' Apellido2:'.$apellido2_pcontacto . ' Correo:' .$correo_pcontacto . ' Telefono:'. $telefono_pcontacto . ' Celular:' . $celular_pcontacto . ' País:' .$pais_pcontacto;
                        $respPerconta = $mod_pergestion->modificarPercontXid($pgco_id, $nombre1_pcontacto, $nombre2_pcontacto, $apellido1_pcontacto, $apellido2_pcontacto, $correo_pcontacto, $telefono_pcontacto, $celular_pcontacto, $pais_pcontacto);
                        $exito = 1;
                    } else {
                        $exito = 1;
                    }
                }
                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido actualizada."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }

    public function actionListaroportunidad() {
        $per_id = @Yii::$app->session->get("PB_iduser");
        $pges_id = base64_decode(empty($_GET["pgid"]) ? base64_encode(0) : $_GET["pgid"]);
        $modGestionCrm = new Oportunidad();
        $data = Yii::$app->request->get();
        $persges_mod = new PersonaGestion();
        if ($pges_id == 0) {
            header('Location: ' . 'index');
            die();
        }
        $contactManage = $persges_mod->consultarPersonaGestion($pges_id);
        $ListOportXContact = $modGestionCrm->consultarOportunidadesByContact(array(), $pges_id, 2);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        return $this->render('listarOportXContact', [
                    'model' => $ListOportXContact,
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    'personalData' => $contactManage,
        ]);
    }

    public function actionCargarleads() {
        $per_id = @Yii::$app->session->get("PB_perid");
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
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "leads/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                $carga_archivo = $mod_gestion->CargarArchivo($data["archivo"], $data["emp_id"], $data["tipo_proceso"]);
                if ($carga_archivo['status']) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data']),
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
                return;
            }
        } else {
            return $this->render('cargarleads', []);
        }
    }

    public function actionListarcontactos() {
        $per_id = @Yii::$app->session->get("PB_iduser");
        $estado_contacto = EstadoContacto::find()->select("econ_id AS id, econ_nombre AS name")->where(["econ_estado_logico" => "1", "econ_estado" => "1"])->asArray()->all();
        $modPersonaGestion = new PersonaGestion();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["estado"] = $data['estado'];
            $arrSearch["fase"] = $data['fase'];
            $mod_gestion = $modPersonaGestion->consultarClienteCont($arrSearch);
            return $this->render('_listarContactosGrid', [
                        "model" => $mod_gestion,
            ]);
        } else {
            $mod_gestion = $modPersonaGestion->consultarClienteCont();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        return $this->render('listarContactos', [
                    'model' => $mod_gestion,
                    'arr_contacto' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $estado_contacto), "id", "name"),
        ]);
    }

    // estado_contacto     ->    Estado del Contacto
    // estado_oportunidad  ->    Estado de Oportunidad
    // oportunidad_perdida ->    Estado de Oportunidad Perdida
    // modalidad           ->    Modalidad Academica

    public function actionExport() {
        $mod_oportunidad = new Oportunidad();
        //$op= base64_decode($_GET["op"]);
        $tipoRep = $_GET["op"];
        if ($tipoRep == 1) {//oportunidad por unidad
            $Data = $mod_oportunidad->consultarOportUnidadAcademica();
            $dataIds = 'eopo_id';
            $dataName = 'eopo_nombre';
        } else {//oportunidad perdida
            $Data = $mod_oportunidad->consultarOportPerdida();
            $dataIds = 'oper_id';
            $dataName = 'oper_nombre';
        }
        $arrayIdsCols = array();
        for ($i = 0; $i < sizeof($Data); $i++) {
            if (!in_array($Data[$i][$dataIds], $arrayIdsCols)) {
                $arrayIdsCols[] = $Data[$i][$dataIds];
                $arrDataCols[] = $Data[$i][$dataName];
            }
        }
        $aux = "";
        $fil = -1;
        $sumafila = 0;
        //$arrayData=array();
        for ($i = 0; $i < sizeof($Data); $i++) {
            $uaca_id = $Data[$i]['uaca_id'];
            $CantUnidad = $Data[$i]['CantUnidad'];
            if ($Data[$i][$dataIds] != $aux) {
                $fil++;
                $sumafila = 0;
                $sumafila += $CantUnidad;
                $aux = $Data[$i][$dataIds];
                $arrayData[$fil][0] = $Data[$i][$dataName];
                $this->retonaMatrix($arrayData, $uaca_id, $fil, $CantUnidad, $sumafila);
            } else {
                $sumafila += $CantUnidad;
                $this->retonaMatrix($arrayData, $uaca_id, $fil, $CantUnidad, $sumafila);
            }
        }

        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');

        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K");
        $arrHeader = array("#", "Grado Lead", "Posgrado Lead", "Online Lead", "Base Grado", "Base Posgrado", "Base Online", "Suma", "Promedio");
        $arrData = array();
        if (is_array($arrDataCols) && count($arrDataCols) > 0) {
            for ($i = 0; $i < count($arrDataCols); $i++) {
                $j = 0;
                for ($j = 0; $j < count($arrHeader); $j++) {
                    if ($j == 0) {
                        $arrData[$i][$j] = $arrDataCols[$i];
                    } else {
                        $arrData[$i][$j] = $arrayData[$i][$j]; //"data $i $j";
                    }
                }
            }
        }
        $nameReport = admision::t("crm", "Contacts");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function retonaMatrix(&$arrayData, $uaca_id, $fil, $CantUnidad, $sumafila) {
        switch ($uaca_id) {
            case '1'://GRADO
                $arrayData[$fil][1] = $CantUnidad;
                $col1 = 1;
                break;
            case '2'://POSGRADO
                $arrayData[$fil][2] = $CantUnidad;
                $col2 = 1;
                break;
            case '3'://EDUCACION CONTINUA
                $arrayData[$fil][3] = $CantUnidad;
                $col3 = 1;
                break;
            case '4'://Base Grado
                $arrayData[$fil][3] = $CantUnidad;
                break;
                $col4 = 1;
            case '5'://Base Posgrado
                $arrayData[$fil][3] = $CantUnidad;
                $col5 = 1;
                break;
            case '6'://Base Online
                $arrayData[$fil][3] = $CantUnidad;
                $col6 = 1;
                break;
        }
        $numPro = $col1 + $col2 + $col3 + $col4 + $col5 + $col6;
        $arrayData[$fil][7] = $sumafila; //SUMA
        $arrayData[$fil][8] = $sumafila / $numPro; //PROMEDIO
    }

    public function actionCargarotroscanales () {
        $per_id = @Yii::$app->session->get("PB_perid");
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
                $carga_archivo = $mod_gestion->CargarArchivoOtroscanales($data["archivo"], $data["emp_id"], $data["tipo_proceso"]);
                if ($carga_archivo['status']) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data']),
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
                return;
            }
        } else {
            return $this->render('cargarotroscanales', []);
        }
    }

}
