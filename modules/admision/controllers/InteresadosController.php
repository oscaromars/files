<?php

namespace app\modules\admision\controllers;

use Yii;
use app\modules\admision\models\Interesado;
use \app\modules\admision\models\Oportunidad;
use app\modules\admision\models\PersonaGestion;
use app\models\EmpresaPersona;
use app\modules\admision\models\InteresadoEmpresa;
use app\modules\academico\models\UnidadAcademica;
use app\models\Persona;
use app\models\Usuario;
use app\models\Utilities;
use yii\base\Security;
use app\models\UsuaGrolEper;
use app\models\Empresa;
use yii\helpers\ArrayHelper;
use app\models\ExportFile;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
academico::registerTranslations();
financiero::registerTranslations();
class InteresadosController extends \app\components\CController
{
    public function actionIndex()
    {
        $per_id = @Yii::$app->session->get("PB_perid");
        $interesado_model = new Interesado();
        $mod_unidad = new UnidadAcademica();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["company"] = $data['company'];
            $arrSearch["unidad"] = $data['unidad'];
            $model = $interesado_model->consultarInteresados($arrSearch);
        } else {
            $model = $interesado_model->consultarInteresados();
        }
        $empresa_model = new Empresa();
        $arr_unidad = $mod_unidad->consultarUnidadAcademicas();
        $arr_empresas = $empresa_model->getAllEmpresa();
        $arrEmpresa = ArrayHelper::map($arr_empresas, "id", "value");
        return $this->render('index', [
            'model' => $model,
            'arr_empresa' => $arrEmpresa,
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $arr_unidad), "id", "name"),
        ]);
    }

    public function actionGuardarinteresado()
    {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuario_ingreso = @Yii::$app->session->get("PB_iduser");
        $error = 0;
        $error_message = "";
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id_opor = $data["id_pgest"];
            $opor_model = new Oportunidad();
            $pgest = $opor_model->consultarPersonaGestionPorOporId($id_opor);
            $con = \Yii::$app->db_asgard;
            $transaction = $con->beginTransaction();
            try {
                $emp_id = $pgest['emp_id'];
                $identificacion = '';
                if (isset($pgest['pges_cedula']) && strlen($pgest['pges_cedula']) > 0) {
                    $identificacion = $pgest['pges_cedula'];
                } else {
                    $identificacion = $pgest['pges_pasaporte'];
                }
                if (isset($identificacion) && strlen($identificacion) > 0) {
                    $id_persona = 0;
                    $mod_persona = new Persona();
                    $keys_per = [
                        'per_pri_nombre', 'per_seg_nombre', 'per_pri_apellido', 'per_seg_apellido', 'per_cedula', 'etn_id', 'eciv_id', 'per_genero', 'pai_id_nacimiento', 'pro_id_nacimiento', 'can_id_nacimiento', 'per_fecha_nacimiento', 'per_celular', 'per_correo', 'tsan_id', 'per_domicilio_sector', 'per_domicilio_cpri', 'per_domicilio_csec', 'per_domicilio_num', 'per_domicilio_ref', 'per_domicilio_telefono', 'pai_id_domicilio', 'pro_id_domicilio', 'can_id_domicilio', 'per_nac_ecuatoriano', 'per_nacionalidad', 'per_foto', 'per_usuario_ingresa', 'per_estado', 'per_estado_logico'
                    ];
                    $parametros_per = [
                        $pgest['pges_pri_nombre'], null, $pgest['pges_pri_apellido'], null,
                        $identificacion, null, null, null, null, null,
                        null, null, $pgest['pges_celular'], $pgest['pges_correo'],
                        null, null, null, null,
                        null, null, null,
                        null, null, null,
                        null, null, null,$usuario_ingreso, 1, 1
                    ];
                    $id_persona = $mod_persona->consultarIdPersona($pgest['pges_cedula'], $pgest['pges_pasaporte']);
                    if ($id_persona == 0) {
                        $id_persona = $mod_persona->insertarPersona($con, $parametros_per, $keys_per, 'persona');
                    }
                    if ($id_persona > 0) {
                        $concap = \Yii::$app->db_captacion;
                        $mod_emp_persona = new EmpresaPersona();
                        $keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
                        $parametros = [$emp_id, $id_persona, 1, 1];
                        $emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($id_persona, $emp_id);
                        if ($emp_per_id == 0) {
                            $emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con, $parametros, $keys, 'empresa_persona');
                        }
                        if ($emp_per_id > 0) {
                            $usuario = new Usuario();
                            $usuario_id = $usuario->consultarIdUsuario(null, $pgest['pges_correo']);
                            if ($usuario_id == 0) {
                                $security = new Security();
                                $hash = $security->generateRandomString();
                                $passencrypt = base64_encode($security->encryptByPassword($hash, 'Uteg2018'));
                                $keys = ['per_id', 'usu_user', 'usu_sha', 'usu_password', 'usu_estado', 'usu_estado_logico'];
                                $parametros = [$id_persona, $pgest['pges_correo'], $hash, $passencrypt, 1, 1];
                                $usuario_id = $usuario->crearUsuarioTemporal($con, $parametros, $keys, 'usuario');
                            }
                            if ($usuario_id > 0) {
                                $mod_us_gr_ep = new UsuaGrolEper();
                                $grol_id = 30;
                                $keys = ['eper_id', 'usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'];
                                $parametros = [$emp_per_id, $usuario_id, $grol_id, 1, 1];
                                $us_gr_ep_id = $mod_us_gr_ep->consultarIdUsuaGrolEper($emp_per_id, $usuario_id, $grol_id);
                                if ($us_gr_ep_id == 0)
                                    $us_gr_ep_id = $mod_us_gr_ep->insertarUsuaGrolEper($con, $parametros, $keys, 'usua_grol_eper');
                                if ($us_gr_ep_id > 0) {
                                    $mod_interesado = new Interesado(); // se guarda con estado_interesado 1
                                    $interesado_id = $mod_interesado->consultaInteresadoById($id_persona);
                                    $keys = ['per_id', 'int_estado_interesado', 'int_usuario_ingreso', 'int_estado', 'int_estado_logico'];
                                    $usuario_ingreso = ((isset($usuario_ingreso))?$usuario_ingreso:$usuario_id);
                                    $parametros = [$id_persona, 1, $usuario_ingreso, 1, 1];
                                    if ($interesado_id == 0) {
                                        $interesado_id = $mod_interesado->insertarInteresado($concap, $parametros, $keys, 'interesado');
                                    }
                                    if ($interesado_id > 0) {
                                        $mod_inte_emp = new InteresadoEmpresa(); // se guarda con estado_interesado 1
                                        $iemp_id = $mod_inte_emp->consultaInteresadoEmpresaById($interesado_id, $emp_id);
                                        if ($iemp_id == 0) {
                                            $iemp_id = $mod_inte_emp->crearInteresadoEmpresa($interesado_id, $emp_id, $usuario_id);
                                        }
                                        if ($iemp_id > 0) {
                                            $usuarioNew = Usuario::findIdentity($usuario_id);
                                            $link = $usuarioNew->generarLinkActivacion();
                                            $email_info = array(
                                                "nombres" => $pgest['pges_pri_nombre'] . " " . $pgest['pges_seg_nombre'],
                                                "apellidos" => $pgest['pges_pri_apellido'] . " " . $pgest['pges_seg_apellido'],
                                                "correo" => $pgest['pges_correo'],
                                                "telefono" => isset($pgest['pges_celular']) ? $pgest['pges_celular'] : $pgest['pges_domicilio_telefono'],
                                                "identificacion" => isset($pgest['pges_cedula']) ? $pgest['pges_cedula'] : $pgest['pges_pasaporte'],
                                                "link_asgard" => $link,
                                            );
                                            // GVG 27/09/2019 solicitado por Admisiones - Diana Lòpez
                                            /*$outemail = $mod_interesado->enviarCorreoBienvenida($email_info);
                                            if ($outemail == 0) {
                                                $error_message .= Yii::t("formulario", "The email hasn't been sent");
                                                $error++;
                                            }*/
                                        } else {
                                            $error_message .= Yii::t("formulario", "The enterprise interested hasn't been saved");
                                            $error++;
                                        }
                                    } else {
                                        $error_message .= Yii::t("formulario", "The interested person hasn't been saved");
                                        $error++;
                                    }
                                } else {
                                    $error_message .= Yii::t("formulario", "The rol user hasn't been saved");
                                    $error++;
                                }
                            } else {
                                $error_message .= Yii::t("formulario", "The user hasn't been saved");
                                $error++;
                            }
                        } else {
                            $error_message .= Yii::t("formulario", "The enterprise person hasn't been saved");
                            $error++;
                        }
                    } else {
                        $error++;
                        $error_message .= Yii::t("formulario", "The person has not been saved");
                    }
                } else {
                    $error_message .= Yii::t("formulario", "Update DNI to generate interested");
                    $error++;
                }

                if ($error == 0) {
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "The information have been saved and the information has been sent to your email"),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    //$transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "Mensaje: " . $error_message),
                        "title" => Yii::t('jslang', 'Bad Request'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
                }
            } catch (\Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("formulario", "Mensaje: " . $error_message),
                    "title" => Yii::t('jslang', 'Bad Request'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
            }
            return;
        }
    }

    public function actionView()
    {

    }

    public function actionEdit()
    {

    }

    public function actionNew()
    {
        return $this->render('new');
    }

    public function actionSave()
    {

    }

    public function actionUpdate()
    {

    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");

        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Name"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "User login"),
            Yii::t("formulario", "Company"),
            Yii::t("formulario", "Academic unit"),
            academico::t("Academico", "Career/Program/Course"));

        $interesado_model = new Interesado();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["company"] = $data['company'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $interesado_model->consultarReportAspirantes(array(), true);
        } else {
            $arrData = $interesado_model->consultarReportAspirantes($arrSearch, true);
        }

        $nameReport = academico::t("Aspirante", "Aspirants");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfaspirantes() {
        $report = new ExportFile();
        $this->view->title = academico::t("Aspirante", "Aspirants"); // Titulo del reporte

        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Name"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "User login"),
            Yii::t("formulario", "Company"),
            Yii::t("formulario", "Academic unit"),
            academico::t("Academico", "Career/Program/Course"));

        $interesado_model = new Interesado();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["company"] = $data['company'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $interesado_model->consultarReportAspirantes(array(), true);
        } else {
            $arrData = $interesado_model->consultarReportAspirantes($arrSearch, true);
        }

        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
            $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
            ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
}
