<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Persona;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\models\MedioPublicitario;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use yii\helpers\Url;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\Oportunidad;
use app\models\Empresa;
use app\modules\admision\models\EstadoContacto;
use app\modules\academico\models\ModuloEstudio;
use app\modules\admision\models\TipoOportunidadVenta;

class InscripcionulinkController extends \yii\web\Controller {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    public function actionIndex() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $per_id = Yii::$app->session->get("PB_perid");
        $mod_persona = Persona::findIdentity($per_id);
        $mod_pergestion = new PersonaGestion();
        $modestudio = new ModuloEstudio();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modTipoOportunidad = new TipoOportunidadVenta();
       
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"],2);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais
                $mod_areapais = new Pais();
                $area = $mod_areapais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getoportunidad"])) {
                $oportunidad = $modTipoOportunidad->consultarOporxUnidad($data["unidada"]);
                $message = array("oportunidad" => $oportunidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modestudio->consultarCursoModalidad($data["unidada"], $data["moda_id"], 2); 
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $pais_id = 1; //Ecuador
        $arr_prov_dom = Provincia::provinciaXPais($pais_id);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_prov_dom[0]["id"]);
        $arr_medio = MedioPublicitario::find()->select("mpub_id AS id, mpub_nombre AS value")->where(["mpub_estado_logico" => "1", "mpub_estado" => "1"])->asArray()->all();
        $arr_conuteg = $mod_pergestion->consultarConociouteg();   
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(2);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"],2);
        $arr_carrerra1 = $modestudio->consultarCursoModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"],2);        
        $tipo_oportunidad_data = $modTipoOportunidad->consultarOporxUnidad($arr_ninteres[0]["id"]);
        return $this->render('index', [
                    "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "tipos_dni2" => array("CED" => Yii::t("formulario", "DNI Document1"), "PASS" => Yii::t("formulario", "Passport1")),
                    "txth_extranjero" => $mod_persona->per_nac_ecuatoriano,
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    "arr_medio" => ArrayHelper::map($arr_medio, "id", "value"),
                    "arr_conuteg" => ArrayHelper::map($arr_conuteg, "id", "name"),
                    "arr_carrerra1" => ArrayHelper::map($arr_carrerra1, "id", "name"),
                    "arr_ninteres" => ArrayHelper::map($arr_ninteres, "id", "name"),
                    "arr_modalidad" => ArrayHelper::map($arr_modalidad, "id", "name"),
                    "arr_tipo_oportunidad" => ArrayHelper::map($tipo_oportunidad_data, "id", "name"),
        ]);
    }

    public function actionGuardarinscripcion() {
        $mod_empresa = new Empresa();
        $mod_estcontacto = new EstadoContacto();
        $mod_persona = new Persona();       
        $celular = null;
        $celular2 = null;
        $telefono = null;
        $celularbeni = null;
        $celularbeni2 = null;
        $telefonobeni = null;
        $correobeni = null;
        $busqueda = 0;
        $pagina = "";
        $conempresa = $mod_empresa->consultarEmpresaId('ulink'); // 2 ulink
        $emp_id = $conempresa["id"];
        //$gcrm_codigo["id"] = 0;
        $correo = strtolower($data["correo"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $nombre1 = ucwords(strtolower($data["pri_nombre"]));
            $nombre2 = null;
            $apellido1 = ucwords(strtolower($data["pri_apellido"]));
            $apellido2 = null;
            $conestcontacto = $mod_estcontacto->consultarEstadoContacto();
            $econ_id = $conestcontacto[0]["id"];
            $tipo_persona = $mod_persona->consultarTipoPersona('Natural');
            $empresa = "";
            $telefono_empresa = null;
            $direccion = null;
            $cargo = null;
            $contacto_empresa = null;
            $numero_contacto = null;
            $pais = $data["pais"];
            $provincia = null;
            $ciudad = null;
            $celular = $data["celular"];
            $celular2 = null;
            $telefono = null;
            $correo = strtolower($data["correo"]);
            $medio = 1; // codigo de formulatio pagina web     // hacer funcion que traIGa id       
            $celularbeni = $data["celular"];
            $celularbeni2 = null;
            $telefonobeni = null;
            $correobeni = strtolower($data["correo"]);           
            $tipo_dni = null;
            $cedula = null;
            $pasaporte = $data["pasaporte"];
            //$conoce_uteg = $data["conoce"];
            $conoce_uteg = null;           
            switch ($nivelestudio) { // esto cambiarlo hacer funcion que consulte el usaurio y traer el id           
                case "1":
                    $agente = 14;
                    $tipoportunidad = $data["metodo"];
                    $pagina = "registerulink";
                    break;
                case "2":
                    $agente = 14;
                    $tipoportunidad = $data["metodo"];
                    $pagina = "registerulink";
                    break;
                case "3":
                    $agente = 14;
                    $tipoportunidad = 8;
                    $pagina = "registerulink";
                    break;
                case "5":
                    $agente = 14;
                    $tipoportunidad = 10;
                    $pagina = "registerulink";
                    break;
            }            
            $usuario = 1; // 1 equivale al usuario administrador            
            $carrera = null; 
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                $mod_pergestion = new PersonaGestion();
                $mod_gestion = new Oportunidad();
                if (!empty($celular) || !empty($correo) || !empty($telefono) /*|| !empty($cedula) || !empty($pasaporte)*/) {
                    $cons_persona = $mod_pergestion->consultarDatosExiste($celular, $correo, $telefono, $celular2, $cedula, $pasaporte);
                    $busqueda = 1;
                }
                if ($cons_persona["registro"] == 0 || $busqueda = 0) {
                    $resp_consulta = $mod_pergestion->consultarMaxPergest();
                    $pges_codigo = $resp_consulta["maximo"];
                    $resp_persona = $mod_pergestion->insertarPersonaGestion($pges_codigo, $tipo_persona, $conoce_uteg, $carrera, $nombre1, $nombre2, $apellido1, $apellido2, $cedula, null, $pasaporte, null, null, null, null, $pais, $provincia, $ciudad, null, null, $celular, $correo, null, null, null, null, null, null, null, $telefono, $celular2, null, null, null, null, null, null, null, null, null, null, $econ_id, $medio, $empresa, $contacto_empresa, $numero_contacto, $telefono_empresa, $direccion, $cargo, $usuario);
                    if ($resp_persona) {
                        /*$gcrm_codigo = $mod_gestion->consultarUltimoCodcrm();
                        $codigocrm = 1 + $gcrm_codigo;
                        $res_oportunidad = $mod_gestion->insertarOportunidad($codigocrm, $emp_id, $resp_persona, $carrera, null, $nivelestudio, $modalidad, $tipoportunidad, $subcarera, $canal, $estado, $hora_inicio, $hora_fin, $fecha_registro, $agente, $usuario);
                        if ($res_oportunidad) {
                            $oact_id = 1;
                            $descripcion = 'Registro subido desde formulario de inscripción';
                            $res_actividad = $mod_gestion->insertarActividad($res_oportunidad, $usuario, $agente, $estado, $fecha_registro, $oact_id, $descripcion, $fecha_registro);
                            if ($res_actividad) {*/
                                $exito = 1;
                          /*  }
                        }*/
                    }
                    if ($exito) {
                        $transaction->commit();
                        //Aqui antes enviaba correo
                        $tituloMensaje = Yii::t("register", "User Register");
                        $asunto = Yii::t("register", "User Register") . " " . Yii::$app->params["siteName"];
                        $body = Utilities::getMailMessage("registernew_ulink", array(
                                    "[[primer_nombre]]" => $nombre1,
                                    "[[primer_apellido]]" => $apellido1,                                    
                                    "[[celular]]" => $celular,
                                    "[[mail]]" => $correo,
                                    ), Yii::$app->language);
                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["lidercontact"] => "Lider", Yii::$app->params["contact1"] => "contact1", Yii::$app->params["contact2"] => "contact2", Yii::$app->params["contact3"] => "contact3", Yii::$app->params["contact4"] => "contact4", Yii::$app->params["admisiones1"] => "admisiones1", Yii::$app->params["admisiones2"] => "admisiones2", Yii::$app->params["admisiones3"] => "admisiones3", Yii::$app->params["admisiones4"] => "admisiones4", Yii::$app->params["ventasposgrado1"] => "ventasposgrado1", Yii::$app->params["ventasposgrado2"] => "ventasposgrado2",Yii::$app->params["ventasposgrado3"] => "ventasposgrado3"], $asunto, $body);

                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Gracias por tu interés en Ulink. Un asesor lo contactará en las proximas 24 horas."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $este),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }
                } else {
                    $mensaje = 'Sus datos ya se encuentran registrados, nos contactaremos con usted';                       
                    $tituloMensaje = Yii::t("register", "Existing Record");
                    $asunto = Yii::t("register", "Existing Record") . " " . Yii::$app->params["siteName"];
                    $body = Utilities::getMailMessage("registeragain_ulink", array(
                                    "[[primer_nombre]]" => $nombre1,
                                    "[[primer_apellido]]" => $apellido1,                                   
                                    "[[celular]]" => $celular,
                                    "[[mail]]" => $correo,
                                    ), Yii::$app->language);
                    
                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte", Yii::$app->params["adminlider"] => "adminlider", Yii::$app->params["contact1"] => "contact1", Yii::$app->params["contact2"] => "contact2", Yii::$app->params["contact3"] => "contact3", Yii::$app->params["contact4"] => "contact4", Yii::$app->params["admisiones1"] => "admisiones1", Yii::$app->params["admisiones2"] => "admisiones2", Yii::$app->params["admisiones3"] => "admisiones3", Yii::$app->params["admisiones4"] => "admisiones4", Yii::$app->params["ventasposgrado1"] => "ventasposgrado1", Yii::$app->params["ventasposgrado2"] => "ventasposgrado2",Yii::$app->params["ventasposgrado3"] => "ventasposgrado3"], $asunto, $body);

                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", $mensaje),
                        "title" => Yii::t('jslang', 'OK'),
                    );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "OK"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => $ex->getMessage(), Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
        }
    }

}
