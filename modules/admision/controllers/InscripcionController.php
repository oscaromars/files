<?php

namespace app\modules\admision\controllers;

use app\models\ContactoGeneral;
use app\modules\admision\models\ConvenioEmpresa;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\repositorio\Module as repositorio;
use app\modules\admision\Module as crm;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\modules\financiero\models\FormaPago;
use app\modules\admision\models\GrupoIntroductorio;
use app\modules\admision\models\InscritoMaestria;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\admision\models\Oportunidad;
use app\modules\admision\models\Institucion;
use Yii;
use yii\helpers\Url;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\models\Persona;
use app\models\EmpresaPersona;
use app\models\UsuaGrolEper;
use app\models\Usuario;
use app\modules\financiero\models\OrdenPago;
use app\modules\financiero\models\DesglosePago;
use app\modules\admision\models\SolicitudInscripcion;
use app\modules\admision\models\Interesado;
use app\modules\financiero\models\Secuencias;
use app\modules\admision\models\InteresadoEmpresa;
use yii\base\Security;
use app\modules\admision\models\PersonaGestion;

repositorio::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();
crm::registerTranslations();

class InscripcionController extends \app\components\CController {

    public function actionIndex() {
        $model = new InscritoMaestria();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $model->getAllInscritosGrid(
                                $data["search"], 
                                $data["txt_fecha_ini"],
                                $data["txt_fecha_fin"], 
                                $data["cmb_agente"], 
                                $data["cmb_tipo_convenio"], 
                                $data["cmb_grupo_introductorio"], 
                                true)
                ]);
            }
        }
        $modgeneral = new ContactoGeneral();
        $mod_grupo = new GrupoIntroductorio();
        $mod_conempresa = new ConvenioEmpresa();
        $arr_agente = $modgeneral->getAgenteInscrito();
        $arr_grupo = $mod_grupo->consultarGrupoIntroductorio();
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        return $this->render('index', [
                    'model' => $model->getAllInscritosGrid(NULL, NULL, NULL, NULL, NULL, NULL, true),
                    "arr_agente" => ArrayHelper::map(array_merge([["id" => "0", "value" => "Seleccionar"]], $arr_agente), "id", "value"),
                    "arr_convenio_empresa" => ArrayHelper::map($arr_convempresa, "id", "name"),
                    "arr_grupo" => ArrayHelper::map($arr_grupo, "id", "value"),
        ]);
    }

    public function actionNew() {
        $mod_conempresa = new ConvenioEmpresa();
        $mod_fpago = new FormaPago();
        $mod_grupo = new GrupoIntroductorio();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modcanal = new Oportunidad();
        $modgeneral = new ContactoGeneral();
        $modinstitucion = new Institucion();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["existDni"])) {
                $model = InscritoMaestria::findOne(['imae_documento' => $data["dni"], 'imae_estado' => 1]);
                $result = false;
                if (isset($model->imae_id))
                    $result = true;
                $message = array("existe" => $result);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
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
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["unidad"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                //if ($data["empresa_id"] == 1) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidad"], $data["moda_id"]);
                //} 
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $pais_id = 1; //Ecuador
        $arr_prov_dom = Provincia::provinciaXPais($pais_id);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_prov_dom[0]["id"]);
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $arr_forma_pago = $mod_fpago->consultarFormaPago();
        $arr_grupo = $mod_grupo->consultarGrupoIntroductorio();
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad(2, 1);
        $arr_carrera = $modcanal->consultarCarreraModalidad(2, 1);
        $arr_agente = $modgeneral->getAgenteInscrito();
        $arr_institucion = $modinstitucion->consultarInstituciones($pais_id);

        return $this->render('new', [
                    //"arr_item" => ArrayHelper::map(array_merge(["id" => "0", "name" => "Seleccionar"], $resp_item), "id", "name"), //ArrayHelper::map($resp_item, "id", "name"),                   
                    "arr_convenio_empresa" => ArrayHelper::map($arr_convempresa, "id", "name"),
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    "arr_tipos_dni" => array("1" => Yii::t("formulario", "DNI Document"), "2" => Yii::t("formulario", "RUC"), "3" => Yii::t("formulario", "Passport")),
                    "arr_cumple_requisito" => array("1" => Yii::t("formulario", "Si"), "2" => Yii::t("formulario", "No")),
                    "arr_estado_pago" => array("1" => Yii::t("formulario", "Pagado"), "2" => Yii::t("formulario", "No Pagado"), "3" => Yii::t("formulario", "Pagado Totalidad Maestria")),
                    //"arr_agente" => array("1" => Yii::t("formulario", "Aabad"), "2" => Yii::t("formulario", "Caguilar"), "3" => Yii::t("formulario", "Cmacias"), "4" => Yii::t("formulario", "Ebayona"), "5" => Yii::t("formulario", "Jmora"), "6" => Yii::t("formulario", "Sholguin")),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    "arr_grupo" => ArrayHelper::map($arr_grupo, "id", "value"),
                    "arr_unidad" => ArrayHelper::map($arr_unidad, "id", "name"),
                    "arr_carrera" => ArrayHelper::map($arr_carrera, "id", "name"),
                    "arr_modalidad" => ArrayHelper::map($arr_modalidad, "id", "name"),
                    "arr_agente" => ArrayHelper::map(array_merge([["id" => "0", "value" => "Seleccionar"]], $arr_agente), "id", "value"),
                    "arr_institucion" => ArrayHelper::map($arr_institucion, "id", "name"),
        ]);
    }

    public function actionSave() {
        $mod_inscrito = new InscritoMaestria();
        $user_id = @Yii::$app->session->get("PB_iduser");
        $fecha_ingreso = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $con = \Yii::$app->db_crm;
            $data = Yii::$app->request->post();
            $items = json_decode($data["dataItems"]); // variable que toma todo lo del grid.-
            $transaction = $con->beginTransaction();
            try {                
                if (!empty($items)) {                    
                    for ($i = 0; $i < count($items); $i++) {                                                
                        $item_ingreso = $mod_inscrito->insertarInscritoMaestria($items[$i]->cemp_id, $items[$i]->gint_id, $items[$i]->pai_id, 
                                        $items[$i]->pro_id, $items[$i]->can_id, $items[$i]->uaca_id, $items[$i]->mod_id, $items[$i]->eaca_id, 
                                        $items[$i]->imae_tipo_documento, $items[$i]->imae_documento, $items[$i]->imae_primer_nombre, 
                                        $items[$i]->imae_segundo_nombre, $items[$i]->imae_primer_apellido, $items[$i]->imae_segundo_apellido, 
                                        $items[$i]->imae_revisar_urgente, $items[$i]->imae_cumple_requisito, $items[$i]->imae_agente, 
                                        $items[$i]->imae_fecha_inscripcion, $items[$i]->imae_fecha_pago, $items[$i]->imae_pago_inscripcion, 
                                        $items[$i]->imae_valor_maestria, $items[$i]->fpag_id, $items[$i]->imae_estado_pago, $items[$i]->imae_convenios, 
                                        $items[$i]->imae_matricula, $items[$i]->imae_titulo, $items[$i]->ins_id, $items[$i]->imae_correo,
                                        $items[$i]->imae_celular, $items[$i]->imae_convencional, $items[$i]->imae_ocupacion, $user_id, $fecha_ingreso);            
                        if ($item_ingreso > 0) {
                            $exito = 1;
                        } else {
                            $exito = 0;
                        }
                    }
                } else {
                    $mensaje = "No ha ingresado ningún item al grid.";
                }
                if ($exito) {
                    \app\models\Utilities::putMessageLogFile('ingresa por exito:' . $exito);
                    $transaction->commit();
                    $mensaje = "Se ha guardado exitosamente sus registros.";
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", $mensaje),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    \app\models\Utilities::putMessageLogFile('ingresa por no exito:' . $exito);
                    $transaction->rollBack();
                    $message = array(
                        "wtmessage" => $ex->getMessage(), Yii::t("notificaciones", $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                $message = array(
                    "wtmessage" => $ex->getMessage(), Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                    "estado" => 0,
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
        }
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $uriFile = dirname(__DIR__) . "/views/inscripcion/files/template_inscritos_maestria.xlsx";

        $arrHeader = array(
            "ID",
            strtoupper(repositorio::t("repositorio", "Tipo Convenio")),
            strtoupper(repositorio::t("repositorio", "Grupo Introductorio")),
            strtoupper(repositorio::t("repositorio", "Provincia")),
            strtoupper(repositorio::t("repositorio", "Cantón")),
            strtoupper(financiero::t("Pagos", "Documento")),
            strtoupper(Yii::t("formulario", "First Name")),
            strtoupper(Yii::t("formulario", "Middle Name")),
            strtoupper(Yii::t("formulario", "Last Name")),
            strtoupper(Yii::t("formulario", "Last Second Name")),
            strtoupper(Yii::t("formulario", "Email")),
            strtoupper(Yii::t("formulario", "CellPhone")),
            strtoupper(crm::t("crm", "Revision")),
            strtoupper(crm::t("crm", "Meets Requirement")),
            strtoupper(Yii::t("formulario", "Executive")),
            strtoupper(crm::t("crm", "Registration Date")),
            strtoupper(Yii::t("formulario", "Payment date")),
            strtoupper(crm::t("crm", "Payment Registration")),
            strtoupper(Yii::t("formulario", "Pay Total")),
            strtoupper(crm::t("crm", "Payment Method")),
            strtoupper(Yii::t("formulario", "Payment Status")),
            strtoupper(crm::t("crm", "Ready Agreement")));

        $mod_inscrito = new InscritoMaestria();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"]  = $data['fecha_ini'];
        $arrSearch["f_end"]  = $data['fecha_end'];
        $arrSearch["agente"] = $data["agente"];
        $arrSearch["convenio"] = $data["convenio"];
        $arrSearch["grupo"]    = $data["grupo"];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_inscrito->getAllInscritosGrid(NULL, NULL, NULL, NULL, NULL, NULL, false);
        } else {
            $arrData = $mod_inscrito->getAllInscritosGrid($arrSearch["search"], 
                                                        $arrSearch["f_ini"],
                                                        $arrSearch["f_end"], 
                                                        $arrSearch["agente"], 
                                                        $arrSearch["convenio"], 
                                                        $arrSearch["grupo"], 
                                                        false);
        }
        $sheetName = "DATA";
        return Utilities::writeReporteXLS($uriFile, $arrHeader, $arrData, $sheetName);
    }

    public function actionDelete() {
        $user_id = @Yii::$app->session->get("PB_iduser");
        $model_inscrito = new InscritoMaestria();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $reg_id = $data["reg_id"];
            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                $registro = $model_inscrito->deleteRegistroInscrito($reg_id, $user_id);
                if ($registro) {
                    //Eliminar en registro                                        
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha eliminado el registro."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al eliminar el registro. "),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al eliminar el registro. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }

    public function actionView() {
        $imae_id = base64_decode($_GET["codigo"]);
        $mod_inscrito = new InscritoMaestria();
        $mod_conempresa = new ConvenioEmpresa();
        $mod_fpago = new FormaPago();
        $mod_grupo = new GrupoIntroductorio();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modcanal = new Oportunidad();
        $modgeneral = new ContactoGeneral();
        $modinstitucion = new Institucion();
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
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["unidad"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidad"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_consinscrito = $mod_inscrito->consultarInscritoMaestria($imae_id);
        $pais_id = 1; //Ecuador
        $arr_prov_dom = Provincia::provinciaXPais($pais_id);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_consinscrito['provincia']);
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $arr_forma_pago = $mod_fpago->consultarFormaPago();
        $arr_grupo = $mod_grupo->consultarGrupoIntroductorio();
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_consinscrito['unidad'], 1);
        $arr_carrera = $modcanal->consultarCarreraModalidad($arr_consinscrito['unidad'], $arr_consinscrito['modalidad']);
        $arr_agente = $modgeneral->getAgenteInscrito();
        $arr_institucion = $modinstitucion->consultarInstituciones($pais_id);

        return $this->render('view', [
                    "arr_convenio_empresa" => ArrayHelper::map($arr_convempresa, "id", "name"),
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    "arr_tipos_dni" => array("1" => Yii::t("formulario", "DNI Document"), "2" => Yii::t("formulario", "RUC"), "3" => Yii::t("formulario", "Passport")),
                    "arr_cumple_requisito" => array("1" => Yii::t("formulario", "Si"), "2" => Yii::t("formulario", "No")),
                    "arr_estado_pago" => array("1" => Yii::t("formulario", "Pagado"), "2" => Yii::t("formulario", "No Pagado"), "3" => Yii::t("formulario", "Pagado Totalidad Maestria")),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    "arr_grupo" => ArrayHelper::map($arr_grupo, "id", "value"),
                    "arr_unidad" => ArrayHelper::map($arr_unidad, "id", "name"),
                    "arr_carrera" => ArrayHelper::map($arr_carrera, "id", "name"),
                    "arr_modalidad" => ArrayHelper::map($arr_modalidad, "id", "name"),
                    "arr_agente" => ArrayHelper::map(array_merge([["id" => "0", "value" => "Seleccionar"]], $arr_agente), "id", "value"),
                    "arr_institucion" => ArrayHelper::map($arr_institucion, "id", "name"),
                    "arr_consinscrito" => $arr_consinscrito,
                    "imae_id" => $imae_id,
        ]);
    }

    public function actionEdit() {
        $imae_id = base64_decode($_GET["codigo"]);
        $mod_inscrito = new InscritoMaestria();
        $mod_conempresa = new ConvenioEmpresa();
        $mod_fpago = new FormaPago();
        $mod_grupo = new GrupoIntroductorio();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modcanal = new Oportunidad();
        $modgeneral = new ContactoGeneral();
        $modinstitucion = new Institucion();
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
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["unidad"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidad"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_consinscrito = $mod_inscrito->consultarInscritoMaestria($imae_id);
        $pais_id = 1; //Ecuador
        $arr_prov_dom = Provincia::provinciaXPais($pais_id);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_consinscrito['provincia']);
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $arr_forma_pago = $mod_fpago->consultarFormaPago();
        $arr_grupo = $mod_grupo->consultarGrupoIntroductorio();
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_consinscrito['unidad'], 1);
        $arr_carrera = $modcanal->consultarCarreraModalidad($arr_consinscrito['unidad'], $arr_consinscrito['modalidad']);
        $arr_agente = $modgeneral->getAgenteInscrito();
        $arr_institucion = $modinstitucion->consultarInstituciones($pais_id);

        return $this->render('edit', [
                    "arr_convenio_empresa" => ArrayHelper::map($arr_convempresa, "id", "name"),
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    "arr_tipos_dni" => array("1" => Yii::t("formulario", "DNI Document"), "2" => Yii::t("formulario", "RUC"), "3" => Yii::t("formulario", "Passport")),
                    "arr_cumple_requisito" => array("1" => Yii::t("formulario", "Si"), "2" => Yii::t("formulario", "No")),
                    "arr_estado_pago" => array("1" => Yii::t("formulario", "Pagado"), "2" => Yii::t("formulario", "No Pagado"), "3" => Yii::t("formulario", "Pagado Totalidad Maestria")),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    "arr_grupo" => ArrayHelper::map($arr_grupo, "id", "value"),
                    "arr_unidad" => ArrayHelper::map($arr_unidad, "id", "name"),
                    "arr_carrera" => ArrayHelper::map($arr_carrera, "id", "name"),
                    "arr_modalidad" => ArrayHelper::map($arr_modalidad, "id", "name"),
                    "arr_agente" => ArrayHelper::map(array_merge([["id" => "0", "value" => "Seleccionar"]], $arr_agente), "id", "value"),
                    "arr_institucion" => ArrayHelper::map($arr_institucion, "id", "name"),
                    "arr_consinscrito" => $arr_consinscrito,
        ]);
    }

    public function actionUpdate() {
        $user_id = @Yii::$app->session->get("PB_iduser");        
        $mod_inscrito = new InscritoMaestria();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $imae_id = $data["imae_id"];
            $cemp = $data["convenio"]; 
            $gint_id = $data["grupo_introductorio"];  
            $pai_id = $data["pais"];  
            $pro_id = $data["provincia"];  
            $can_id = $data["canton"];  
            $uaca_id = $data["unidad"];  
            $mod_id = $data["modalidad"];  
            $eaca_id = $data["carrera"];  
            $imae_tipo_documento = $data["tipo_documento"];  
            $imae_documento = $data["documento"];  
            $imae_pri_nombre = ucwords(strtolower($data["primer_nombre"]));  
            $imae_segundo_nombre = ucwords(strtolower($data["segundo_nombre"]));  
            $imae_pri_apellido = ucwords(strtolower($data["primer_apellido"]));  
            $imae_segundo_apellido = ucwords(strtolower($data["segundo_apellido"]));  
            $imae_revisar_urgente = ucwords(strtolower($data["revisar_urgente"]));  
            $imae_cumple_requisito = $data["cumple_requisito"];  
            $imae_agente = $data["agente"];  
            $imae_fecha_inscripcion = $data["fecha_inscripcion"];  
            $imae_fecha_pago = $data["fecha_pago"];  
            $imae_pago_inscripcion = $data["pago_inscripcion"];  
            $imae_valor_maestria = $data["valor_maestria"];  
            $fpag_id = $data["forma_pago"];  
            $imae_estado_pago = $data["estado_pago"];  
            $imae_convenios = ucwords(strtolower($data["convenios"]));  
            $imae_matricula = $data["matricula"];  
            $imae_titulo = ucwords(strtolower($data["titulo"]));  
            $ins_id = $data["institucion"];  
            $imae_correo = strtolower($data["correo"]);  
            $imae_celular = $data["celular"];  
            $imae_convencional = $data["convencional"];  
            $imae_ocupacion = $data["ocupacion"];             
            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {                
                $keys_act = [
                    'cemp_id', 'gint_id', 'pai_id', 'pro_id', 'can_id', 'uaca_id', 'mod_id', 'eaca_id', 'imae_tipo_documento'
                    , 'imae_documento', 'imae_primer_nombre', 'imae_segundo_nombre', 'imae_primer_apellido', 'imae_segundo_apellido'
                    , 'imae_revisar_urgente', 'imae_cumple_requisito', 'imae_agente', 'imae_fecha_inscripcion', 'imae_fecha_pago'
                    , 'imae_pago_inscripcion', 'imae_valor_maestria', 'fpag_id', 'imae_estado_pago', 'imae_convenios', 'imae_matricula'
                    , 'imae_titulo', 'ins_id', 'imae_correo', 'imae_celular', 'imae_convencional', 'imae_ocupacion', 'imae_usuario_modif'
                ];
                $values_act = [
                    $cemp, $gint_id, $pai_id, $pro_id, $can_id, $uaca_id, $mod_id, $eaca_id, $imae_tipo_documento
                    , $imae_documento, $imae_pri_nombre, $imae_segundo_nombre, $imae_pri_apellido, $imae_segundo_apellido
                    , $imae_revisar_urgente, $imae_cumple_requisito, $imae_agente, $imae_fecha_inscripcion, $imae_fecha_pago
                    , $imae_pago_inscripcion, $imae_valor_maestria, $fpag_id, $imae_estado_pago, $imae_convenios, $imae_matricula
                    , $imae_titulo, $ins_id, $imae_correo, $imae_celular, $imae_convencional, $imae_ocupacion, $user_id
                ];
                $respModifica = $mod_inscrito->actualizarInscritoMaestria($con, $imae_id, $values_act, $keys_act, 'inscrito_maestria');
                if ($respModifica) {
                    $exito = 1;
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
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
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

    public function actionNewaspirante(){

        // verificar si ya es aspirante

        // verificar si existe como contacto

        // crear una actividad

        // crear una oportunidad

        // generar Interesado - Aspirante
        
        $mod_pergestion = new PersonaGestion();
        $mod_gestion = new Oportunidad();
        if (!empty($celular) || !empty($correo) || !empty($telefono) || !empty($celular2)) {
            $cons_persona = $mod_pergestion->consultarDatosExiste($celular, $correo, $telefono, $celular2, null, null);
            if ($cons_persona["registro"] > 0) {
                $busqueda = 1;
            }
        }
    }
    
    public function actionGenerarsolicitud() {
        $con = \Yii::$app->db_asgard;        
        $con2 = \Yii::$app->db_facturacion;
        $con3 = \Yii::$app->db_captacion;
        $con4 = \Yii::$app->db_crm;
        $usuario_ingreso = @Yii::$app->session->get("PB_iduser");
        $per_usuario = @Yii::$app->session->get("PB_perid");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id = $data["id"];
        }
        try {
            /* $transaction = $con->beginTransaction();
              $transaction1 = $con1->beginTransaction(); */
            $transaction2 = $con2->beginTransaction();
            //Se consulta la información.
            $emp_id = 1;
            //Verificar código de empleado. 
            $mod_gestion = new Oportunidad();
            $padm_id = $mod_gestion->consultarAgenteAutenticado($per_usuario);
            if (!empty($padm_id)) {
                $mod_inscripcion = new InscritoMaestria();            
                $resp_datos = $mod_inscripcion->consultarInscritoMaestria($id);
                if ($resp_datos) {  //Si se encontró
                    $identificacion = $resp_datos['documento'];                
                    //Registro de Contacto.
                    $mod_pergestion = new PersonaGestion();
                    if (!empty($resp_datos["celular"]) || !empty($resp_datos["correo"]) || !empty($resp_datos["telefono"])) {                        
                        $cons_persona = $mod_pergestion->consultarDatosExiste($resp_datos["celular"], $resp_datos["correo"], $resp_datos["telefono"], $resp_datos["celular"], null, null, 1);
                        \app\models\Utilities::putMessageLogFile('consulta si existe:' . $cons_persona["registro"] );
                        if ($cons_persona["registro"] > 0) {
                            $busqueda = '1';
                            $resp_persona = $cons_persona["registro"];
                            \app\models\Utilities::putMessageLogFile('$busqueda:' . $busqueda);
                        } else {
                            $busqueda = '0';
                        }
                    }
                    if ($busqueda=='0') {
                        \app\models\Utilities::putMessageLogFile('preparar ingreso persona gestion');
                        //Obtener el número máximo de persona_gestion.                    
                        $resp_consulta = $mod_pergestion->consultarMaxPergest();
                        $tipo_persona = 1;
                        $conoce_uteg = null;
                        $per_nac_ecuatoriano = 1;
                        $medio = 6; //whatsapp                  
                        $cedula = null;
                        $ruc = null;
                        $pasaporte = null;
                        if ($resp_datos["tipo_documento"] == 1) {
                            $cedula = $resp_datos["documento"];
                        } else {
                            if ($resp_datos["tipo_documento"] == 2) {
                                $ruc = $resp_datos["documento"];
                            } else {
                                $pasaporte = $resp_datos["documento"];
                            }
                        }
                        \app\models\Utilities::putMessageLogFile('antes de insertar pgestion');
                        $resp_persona = $mod_pergestion->insertarPersonaGestion($resp_consulta["maximo"], $tipo_persona, $conoce_uteg, $resp_datos["carrera"], $resp_datos["nombre"], $resp_datos["sgo_nombre"], $resp_datos["apellido"], $resp_datos["sgoapellido"], $cedula, $ruc, $pasaporte, null, null, null, null, $resp_datos["pais"], $resp_datos["provincia"], $resp_datos["canton"], $per_nac_ecuatoriano, null, $resp_datos["celular"], strtolower($resp_datos["correo"]), null, null, null, null, null, null, null, $resp_datos["telefono"], null, null, null, null, null, null, null, null, null, null, null, 1, $medio, $emp_id, null, null, null, null, null, $usuario_ingreso);
                        if ($resp_persona) {
                            $contacto = '1';
                            \app\models\Utilities::putMessageLogFile('Después de Registro de persona gestión.');  
                        }
                    }
                    if ($busqueda == '1' or $contacto == '1') {
                        \app\models\Utilities::putMessageLogFile('Preparar para oportunidad.');                        
                        //Registro de oportunidad.
                        $mod_gestion = new Oportunidad();
                        $resp_oportunidad = $mod_gestion->consultarOportunidadxUnidModCarrera($emp_id, $resp_datos["unidad"], $resp_datos["modalidad"], $resp_datos["carrera"], $resp_persona);
                        if (empty($resp_oportunidad)) {
                            \app\models\Utilities::putMessageLogFile('oportunidad id:'.$resp_oportunidad["opo_id"]);                        
                            $gcrm_codigo = $mod_gestion->consultarUltimoCodcrm();                            
                            $codportunidad = 1 + $gcrm_codigo;
                            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);                             
                            $canal_conocimiento = 6; //Whatsapp
                            $tipo_oportunidad = 5; //Ingreso al propedeutico
                            $subcarrera = null; //Ninguno
                            $estado_oportunidad = 3; //Genera aspirante                        
                            \app\models\Utilities::putMessageLogFile('antes de Registro de oportunidad.');                             
                            $res_gestion = $mod_gestion->insertarOportunidad($codportunidad, $emp_id, $resp_persona, null, $resp_datos["carrera"], $resp_datos["unidad"], $resp_datos["modalidad"], $tipo_oportunidad, $subcarrera, $canal_conocimiento, $estado_oportunidad, null, null,  $fecha_registro, $padm_id, $usuario_ingreso);                        
                            if ($res_gestion>0) {
                                \app\models\Utilities::putMessageLogFile('Después de insertar oportunidad');                        
                                $opo_id = $res_gestion;                           
                                $eopo_id = $estado_oportunidad; // En curso por defecto
                                $bact_fecha_registro = $fecha_registro;
                                $bact_fecha_proxima_atencion = null;                                    
                                $oact_id = 1;
                                $bact_descripcion = "Inscrito Maestría.";
                                \app\models\Utilities::putMessageLogFile('antes de Registro de actividad.');                              
                                $res_actividad = $mod_gestion->insertarActividad($opo_id, $usuario_ingreso, $padm_id, $eopo_id, $bact_fecha_registro, $oact_id, $bact_descripcion, $bact_fecha_proxima_atencion);                            
                                if ($res_actividad>0) {
                                    \app\models\Utilities::putMessageLogFile('Después de insertar la actividad.');                        
                                    $crm = '1';
                                }
                            }                                                       
                        } else {
                            $crm = '1';
                            \app\models\Utilities::putMessageLogFile('Ya existe oportunidad');  
                        }
                    }                 
                    //Registro de personas
                    if (isset($identificacion) && strlen($identificacion) > 0 && $crm == '1')  {
                        \app\models\Utilities::putMessageLogFile('$identificacion:' . $identificacion);
                        $mod_persona = new Persona();
                        $keys_per = [
                            'per_pri_nombre', 'per_seg_nombre', 'per_pri_apellido', 'per_seg_apellido',
                            'per_cedula', 'etn_id', 'eciv_id', 'per_genero', 'pai_id_nacimiento',
                            'pro_id_nacimiento', 'can_id_nacimiento', 'per_fecha_nacimiento',
                            'per_celular', 'per_correo', 'tsan_id', 'per_domicilio_sector',
                            'per_domicilio_cpri', 'per_domicilio_csec', 'per_domicilio_num',
                            'per_domicilio_ref', 'per_domicilio_telefono', 'pai_id_domicilio',
                            'pro_id_domicilio', 'can_id_domicilio', 'per_nac_ecuatoriano',
                            'per_nacionalidad', 'per_foto', 'per_usuario_ingresa', 'per_estado', 'per_estado_logico'
                        ];                    
                        $parametros_per = [
                            ucwords(strtolower($resp_datos['nombre'])), ucwords(strtolower($resp_datos['sgo_nombre'])),
                            ucwords(strtolower($resp_datos['apellido'])), ucwords(strtolower($resp_datos['sgoapellido'])),
                            $resp_datos['documento'], null, null, null, $resp_datos['pais'], $resp_datos['provincia'],
                            $resp_datos['canton'], null, $resp_datos['celular'], strtolower($resp_datos['correo']),
                            null, null, null, null,
                            null, null, null,
                            null, null, null,
                            null, null, null, $usuario_ingreso, 1, 1
                        ];
                        $id_persona = $mod_persona->consultarIdPersona($resp_datos['documento'], $resp_datos['documento'], $resp_datos['pben_correo'], $resp_datos['celular']);
                        if ($id_persona == 0) {                        
                            $id_persona = $mod_persona->insertarPersona($con, $parametros_per, $keys_per, 'persona');  
                            \app\models\Utilities::putMessageLogFile('despues de crear persona:'.$id_persona);
                            if ($id_persona) {                            
                                $mod_emp_persona = new EmpresaPersona();                            
                                $keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
                                $parametros = [$emp_id, $id_persona, 1, 1];
                                $emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($id_persona, $emp_id);
                                if ($emp_per_id == 0) {
                                    $emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con, $parametros, $keys, 'empresa_persona');
                                    if ($emp_per_id > 0) {
                                        \app\models\Utilities::putMessageLogFile('se crea empresa persona.');
                                        $usuario = new Usuario();
                                        $usuario_id = $usuario->consultarIdUsuario($id_persona, strtolower($resp_datos['correo']));
                                        if ($usuario_id == 0) {
                                            $security = new Security();
                                            $hash = $security->generateRandomString();
                                            $passencrypt = base64_encode($security->encryptByPassword($hash, $resp_datos['documento']));
                                            $keys = ['per_id', 'usu_user', 'usu_sha', 'usu_password', 'usu_estado', 'usu_estado_logico'];
                                            $parametros = [$id_persona, strtolower($resp_datos['correo']), $hash, $passencrypt, 1, 1];
                                            $usuario_id = $usuario->crearUsuarioTemporal($con, $parametros, $keys, 'usuario');
                                        }
                                        if ($usuario_id > 0) {
                                            \app\models\Utilities::putMessageLogFile('se crea usuario.');
                                            $mod_us_gr_ep = new UsuaGrolEper();
                                            $grol_id = 30;
                                            $keys = ['eper_id', 'usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'];
                                            $parametros = [$emp_per_id, $usuario_id, $grol_id, 1, 1];
                                            $us_gr_ep_id = $mod_us_gr_ep->consultarIdUsuaGrolEper($emp_per_id, $usuario_id, $grol_id);
                                            if ($us_gr_ep_id == 0) {
                                                $us_gr_ep_id = $mod_us_gr_ep->insertarUsuaGrolEper($con, $parametros, $keys, 'usua_grol_eper');
                                                if ($us_gr_ep_id > 0) {
                                                    \app\models\Utilities::putMessageLogFile('se crea usuario persona.');
                                                    $mod_interesado = new Interesado(); // se guarda con estado_interesado 1
                                                    $interesado_id = $mod_interesado->consultaInteresadoById($id_persona);
                                                    $keys = ['per_id', 'int_estado_interesado', 'int_usuario_ingreso', 'int_estado', 'int_estado_logico'];
                                                    $parametros = [$id_persona, 1, $usuario_id, 1, 1];
                                                    if ($interesado_id == 0) {
                                                        $interesado_id = $mod_interesado->insertarInteresado($con3, $parametros, $keys, 'interesado');
                                                    }
                                                    if ($interesado_id > 0) {
                                                        \app\models\Utilities::putMessageLogFile('se crea interesado.');                                                    
                                                        $mod_inte_emp = new InteresadoEmpresa(); // se guarda con estado_interesado 1
                                                        $iemp_id = $mod_inte_emp->consultaInteresadoEmpresaById($interesado_id, $emp_id);
                                                        if ($iemp_id == 0) {                                                                                                             
                                                            $iemp_id = $mod_inte_emp->crearInteresadoEmpresa($interesado_id, $emp_id, $usuario_id);
                                                            if ($iemp_id > 0) {
                                                                $crea_persona = "S";
                                                                \app\models\Utilities::putMessageLogFile('se crea interesado empresa.');                                                    
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //Cuando ya está creada la persona, se crea la solicitud y orden de pago.  
                        if (($crea_persona == "S") or ($id_persona > 0)) {                        
                            $sins_fechasol = $resp_datos["fecha_inscripcion"];
                            $rsin_id = 1; //Solicitud pendiente  
                            $usuario = new Usuario();
                            $usuario_id = $usuario->consultarIdUsuario($id_persona, strtolower($resp_datos['correo']));
                            $solins_model = new SolicitudInscripcion();
                            $resp_detalle = $solins_model->ConsultarXUnidadModalPrecio($resp_datos['unidad'], $resp_datos['modalidad']);
                            \app\models\Utilities::putMessageLogFile('detalle de precios:'.$resp_detalle["precio_ins"]);
                            if ($resp_detalle) {
                                \app\models\Utilities::putMessageLogFile('despues de consultar detalle de precios.');
                                $mod_interesado = new Interesado();                            
                                $interesado_id = $mod_interesado->consultaInteresadoById($id_persona);                            
                                \app\models\Utilities::putMessageLogFile('despues de consultar $interesado_id:'.$interesado_id);
                                if ($interesado_id) {                                
                                    \app\models\Utilities::putMessageLogFile('Encontró $interesado_id.');
                                    $num_secuencia = Secuencias::nuevaSecuencia($con2, $emp_id, 1, 1, 'SOL');
                                    if ($resp_datos['convenio_empresa']==0){
                                        $convenio = null;
                                    } else {
                                        $convenio = $resp_datos['convenio_empresa'];
                                    }
                                    $sins_id = $solins_model->insertarSolicitud($interesado_id, $resp_datos['unidad'], $resp_datos['modalidad'], 4, $resp_datos['carrera'], null, $emp_id, $num_secuencia, $rsin_id, $sins_fechasol, $usuario_id, $convenio);
                                    if ($sins_id) {
                                        \app\models\Utilities::putMessageLogFile('despues insertar sol.');
                                        $mod_ordenpago = new OrdenPago();
                                        //Generar la orden de pago con valor correspondiente. Buscar precio para orden de pago.                                                                                                                         
                                        $estadopago = 'P';                                              
                                        $resp_opago = $mod_ordenpago->insertarOrdenpago($sins_id, null, $resp_detalle["precio_ins"], 0, $resp_detalle["precio_ins"], $estadopago, $usuario_id, null, null);
                                        if ($resp_opago) {
                                            \app\models\Utilities::putMessageLogFile('despues insertar o/p.');
                                            //insertar desglose del pago                                                         
                                            $fecha_ini = date(Yii::$app->params["dateByDefault"]);
                                            $resp_dpago = $mod_ordenpago->insertarDesglosepago($resp_opago, $resp_detalle["ite_id"], $resp_detalle["precio_ins"], 0, $resp_detalle["precio_ins"], $fecha_ini, null, $estadopago, $usuario_id);
                                            if ($resp_dpago) {
                                                $detalle = 'S';
                                            }
                                            //Grabar datos de factura                                                                                                                   
                                            if ($detalle == 'S') {
                                                if ($resp_datos["tipo_documento"] == 1) {                                                
                                                    $tipo_ide = "CED";
                                                } elseif ($resp_datos["tdoc_id"] == 2) {                                                
                                                    $tipo_ide = "RUC";
                                                } else {                                                
                                                    $tipo_ide = "PAS";
                                                }
                                                $resdatosFact = $solins_model->crearDatosFacturaSolicitud($sins_id, $resp_datos["nombre"], $resp_datos["apellido"], $tipo_ide, $resp_datos["documento"], "N/A", $resp_datos["telefono"], strtolower($resp_datos["correo"]));
                                                if ($resdatosFact) {
                                                    \app\models\Utilities::putMessageLogFile('despues de insertar datos factura');
                                                    $exito = 1;
                                                }
                                            }
                                        }
                                    }                                
                                }
                            }
                        }
                    }
                } else {
                    $mensaje = "No existen datos de inscripción.";
                }
            } else {
                $mensaje = "No permitida la acción, porque el usuario no es agente de admisión. Comunicar al departamento de Desarrollo.";
            }
            if ($exito == 1) {
                //$transaction->commit();
                //$transaction1->commit(); 
                $transaction2->commit();
                //Envío de correo. COMENTARLO PARA QUE NO SE ENVIE
               /* $tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                $asunto = Yii::t("interesado", "UTEG - Registration Online");
                $link = "https://www.asgard.uteg.edu.ec/asgard";
                $body = Utilities::getMailMessage("credentials", array("[[usuario]]" => $resp_datos['nombre'] . " " . $resp_datos['apellido'], "[[username]]" => strtolower($resp_datos['correo']), "[[clave]]" => $resp_datos['documento'], "[[link]]" => $link), Yii::$app->language);
                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [strtolower($resp_datos['correo']) => $resp_datos['apellido'] . " " . $resp_datos['nombre']], $asunto, $body);
                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisiones"] => "Jefe"], $asunto, $body);
                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $body);
               */ $message = array(
                    "wtmessage" => Yii::t("formulario", "The information have been saved and the information has been sent to your email"),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            } else {
                /* $transaction->rollback();
                  $transaction1->rollback(); */
                $transaction2->rollback();
                $message = array(
                    "wtmessage" => Yii::t("formulario", "Mensaje1: " . $mensaje), //$error_message
                    "title" => Yii::t('jslang', 'Bad Request'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
            }
        } catch (Exception $ex) {
            /* $transaction->rollback();
              $transaction1->rollback(); */
            $transaction2->rollback();
            $message = array(
                "wtmessage" => Yii::t("formulario", "Error en el proceso de generar usuario y solicitud."), //$error_message
                "title" => Yii::t('jslang', 'Bad Request'),
            );
            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
        }
    }

}
