<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\ExportFile;
use app\modules\financiero\models\OrdenPago;
use app\modules\financiero\models\DesglosePago;
use app\modules\financiero\models\RegistroPago;
use app\modules\admision\models\SolicitudInscripcion;
use app\models\Persona;
use app\models\Usuario;
use app\modules\financiero\models\SolicitudBotonPago;
use app\modules\admision\models\Interesado;
use yii\helpers\Url;
use yii\base\Exception;
use \app\modules\financiero\models\Documento;
use yii\base\Security;
use app\modules\financiero\models\Secuencias;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\models\EmpresaPersona;
use app\models\UsuaGrolEper;
use app\modules\admision\models\InteresadoEmpresa;
use app\modules\admision\models\SolicitudInscripcionSaldos;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\Estudiante;
admision::registerTranslations();
academico::registerTranslations();

class PagosController extends \app\components\CController {

    public function actionIndex() {
        $per_id = @Yii::$app->session->get("PB_iduser");
        $model_interesado = new Interesado();
        $resp_gruporol = $model_interesado->consultagruporol($per_id);
        $mod_pago = new OrdenPago();
        $data = null;
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["f_estado"] = $data['f_estado'];
            $arrSearch["search"] = $data['search'];
            $resp_pago = $mod_pago->listarPagosolicitud($arrSearch, $resp_gruporol["grol_id"]);
            return $this->renderPartial('index-grid', [
                        "model" => $resp_pago,
            ]);
        } else {
            $resp_pago = $mod_pago->listarPagosolicitud(null, $resp_gruporol["grol_id"]);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        $arrEstados = ArrayHelper::map([["id" => "T", "value" => "Todos"], ["id" => "S", "value" => "Pagada"], ["id" => "P", "value" => "Pendiente"]], "id", "value");
        return $this->render('index', [
                    'model' => $resp_pago,
                    'arrEstados' => $arrEstados
        ]);
    }

    public function actionCargardocpagos() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $ccar_id = isset($_GET['ids']) ? base64_decode($_GET['ids']) : 0; //NULL
        $model_pag = new OrdenPago();
        if ($ccar_id == 0) {
            $ccar_id = $model_pag->consultarInfoOrdenPagoPorPerId($per_id);
            if (!isset($ccar_id) || empty($ccar_id)) {
                header('Location: ' . 'listarpagoscargados');
                die();
            }
        }
        $arr_forma_pago = $model_pag->formaPago('2');
        $resp_doc = $model_pag->listarDocumento($ccar_id);
        $data = null;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        return $this->render('cargardocpagos', [
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"), 'model' => $resp_doc,
                    "opago" => $ccar_id,
                    "vista" => $_GET["vista"],
        ]);
    }

    public function actionValidarpagocarga() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $model_interesado = new Interesado();
        $opag_id = $_GET["ido"]; //empty($_GET["ido"])?0:$_GET["ido"];
        $mod_cliord = new OrdenPago();
        /* if($opag_id>0){
          $opag_id=$mod_cliord->consultarInfoOrdenPagoPorPerId($per_id);
          } */
        $resp_gruporol = $model_interesado->consultagruporol($per_id);
        $gruporol = empty($resp_gruporol["grol_id"]) ? 27 : $resp_gruporol["grol_id"];
        $resp_cliord = $mod_cliord->consultarOrdenpago($gruporol, $opag_id, 0);
        if ($resp_cliord) {
            $persona_pago = $resp_cliord["per_id"];
            $sins_id = $resp_cliord["sser_id"];
            $nombres = $resp_cliord["nombres"];
            $apellidos = $resp_cliord["apellidos"];
            $valortotal = $resp_cliord["valortotal"];
            $valoraplicado = $resp_cliord["valoraplicado"];
            $rol = $resp_cliord["rol"];
        }
        $mod_pago = new OrdenPago();
        $resp_pago = $mod_pago->listarPagosadmxsolicitud($gruporol, $opag_id, $persona_pago);
        $data = null;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        return $this->render('validarpagocarga', [
                    'model' => $resp_pago,
                    'persona_pago' => $persona_pago,
                    'sins_id' => $sins_id,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'valortotal' => $valortotal,
                    'valoraplicado' => $valoraplicado,
                    'opag_id' => $opag_id,
                    'rol' => $rol,
                    'respCliente' => $resp_cliord,
        ]);
    }

    public function actionGenerarsolicitud() {
        $con = \Yii::$app->db_asgard;
        $con1 = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_facturacion;
        $concap = \Yii::$app->db_captacion;
        $usuario_ingreso = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $doc_id = $data["doc_id"];
        }
        try {
            /* $transaction = $con->beginTransaction();
              $transaction1 = $con1->beginTransaction(); */
            $transaction2 = $con2->beginTransaction();
            //Se consulta la información.
            $emp_id = 1;
            $mod_documento = new Documento();
            $resp_datos = $mod_documento->consultarDatosxId($con2, $doc_id);
            if ($resp_datos["doc_pagado"] == 1) {
                $identificacion = $resp_datos['pben_cedula'];
                if (isset($identificacion) && strlen($identificacion) > 0) {
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
                        ucwords(strtolower($resp_datos['pben_nombre'])), null,
                        ucwords(strtolower($resp_datos['pben_apellido'])), null,
                        $resp_datos['pben_cedula'], null, null, null, null, null,
                        null, null, $resp_datos['pben_celular'], $resp_datos['pben_correo'],
                        null, null, null, null,
                        null, null, null,
                        null, null, null,
                        null, null, null, $usuario_ingreso, 1, 1
                    ];
                    $id_persona = $mod_persona->consultarIdPersona($resp_datos['pben_cedula'], $resp_datos['pben_cedula'], $resp_datos['pben_correo'], $resp_datos['pben_celular']);
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
                                    $usuario_id = $usuario->consultarIdUsuario($id_persona, $resp_datos['pben_correo']);
                                    if ($usuario_id == 0) {
                                        $security = new Security();
                                        $hash = $security->generateRandomString();
                                        $passencrypt = base64_encode($security->encryptByPassword($hash, $resp_datos['pben_cedula']));
                                        $keys = ['per_id', 'usu_user', 'usu_sha', 'usu_password', 'usu_estado', 'usu_estado_logico'];
                                        $parametros = [$id_persona, $resp_datos['pben_correo'], $hash, $passencrypt, 1, 1];
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
                                                    $interesado_id = $mod_interesado->insertarInteresado($concap, $parametros, $keys, 'interesado');
                                                }
                                                if ($interesado_id > 0) {
                                                    \app\models\Utilities::putMessageLogFile('se crea interesado.');
                                                    $mod_inte_emp = new InteresadoEmpresa(); // se guarda con estado_interesado 1
                                                    $iemp_id = $mod_inte_emp->consultaInteresadoEmpresaById($interesado_id, $emp_id);
                                                    if ($iemp_id == 0) {
                                                        $iemp_id = $mod_inte_emp->crearInteresadoEmpresa($interesado_id, $emp_id, $usuario_id);
                                                        if ($iemp_id > 0) {
                                                            $crea_persona = "S";
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
                    //Cuando ya está creada la persona.-
                    if (($crea_persona == "S") or ( $id_persona > 0)) {
                        $sins_fechasol = $resp_datos["sbpa_fecha_solicitud"];
                        $rsin_id = 2; //Solicitud aprobada
                        $usuario = new Usuario();
                        $usuario_id = $usuario->consultarIdUsuario($id_persona, $resp_datos['pben_correo']);
                        $solins_model = new SolicitudInscripcion();
                        $resp_detalle = $mod_documento->consultarDetalledocumentoById($doc_id, 1);
                        if ($resp_detalle) {
                            $mod_interesado = new Interesado();
                            $res_Interesado = $mod_interesado->getInteresadoxIdPersona($id_persona);
                            if ($res_Interesado) {
                                for ($a = 0; $a < count($resp_detalle); $a++) {
                                    $num_secuencia = Secuencias::nuevaSecuencia($con2, $emp_id, 1, 1, 'SOL');
                                    $sins_id = $solins_model->insertarSolicitud($res_Interesado, $resp_detalle[$a]["unidad"], $resp_detalle[$a]["modalidad"], $resp_detalle[$a]["metodo"], null, null, $emp_id, $num_secuencia, $rsin_id, $sins_fechasol, $usuario_id, null);
                                    if ($sins_id) {
                                        \app\models\Utilities::putMessageLogFile('despues insertar sol.');
                                        $mod_ordenpago = new OrdenPago();
                                        //Generar la orden de pago con valor correspondiente. Buscar precio para orden de pago.
                                        $estadopago = 'S';
                                        $resp_opago = $mod_ordenpago->insertarOrdenpago($sins_id, $resp_datos["sbpa_id"], $resp_detalle[$a]["total"], 0, $resp_detalle[$a]["total"], $estadopago, $usuario_id, $resp_datos["doc_fecha_pago"], $resp_detalle[$a]["total"]);
                                        if ($resp_opago) {
                                            \app\models\Utilities::putMessageLogFile('despues insertar o/p.');
                                            //insertar desglose del pago
                                            $fecha_ini = date(Yii::$app->params["dateByDefault"]);
                                            $resp_dpago = $mod_ordenpago->insertarDesglosepago($resp_opago, $resp_detalle[$a]["ite_id"], $resp_detalle[$a]["total"], 0, $resp_detalle[$a]["total"], $fecha_ini, null, $estadopago, $usuario_id);
                                            if ($resp_dpago) {
                                                \app\models\Utilities::putMessageLogFile('despues insertar desg/p.');
                                                //Grabar documento de registro de pago por botón de pagos.
                                                $fpag_id = 6;  //botón de pagos.
                                                $num_transaccion = 1;
                                                $creadetalle = $mod_ordenpago->insertarRegistropago($resp_dpago, $fpag_id, $resp_detalle[$a]["total"], $resp_datos["doc_fecha_pago"], null, $num_transaccion, $resp_datos["doc_fecha_pago"], "Botón Pagos", "AP", $usuario_id, "RE");
                                                if ($creadetalle) {
                                                    \app\models\Utilities::putMessageLogFile('despues de insertar registro pago');
                                                    $detalle = 'S';
                                                }
                                            } else {
                                                $detalle = 'S';
                                            }
                                            //Grabar datos de factura
                                            if ($detalle == 'S') {
                                                if ($resp_datos["tdoc_id"] == 1) {
                                                    $num_ident = $resp_datos["doc_cedula"];
                                                    $tipo_ide = "CED";
                                                } elseif ($resp_datos["tdoc_id"] == 2) {
                                                    $num_ident = $resp_datos["doc_pasaporte"];
                                                    $tipo_ide = "PAS";
                                                } else {
                                                    $num_ident = $resp_datos["doc_ruc"];
                                                    $tipo_ide = "RUC";
                                                }
                                                $resdatosFact = $solins_model->crearDatosFacturaSolicitud($sins_id, $resp_datos["doc_nombres_cliente"], $resp_datos["doc_nombres_cliente"], $tipo_ide, $num_ident, $resp_datos["doc_direccion"], $resp_datos["doc_telefono"], $resp_datos["doc_correo"]);
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
                }
            } else {
                $mensaje = "El pago se encuentra en estado pendiente en botón de pagos.";
            }
            if ($exito == 1) {
                //$transaction->commit();
                //$transaction1->commit();
                $transaction2->commit();
                //Envío de correo.
                $tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                $asunto = Yii::t("interesado", "UTEG - Registration Online");
                $link = "https://www.asgard.uteg.edu.ec/asgard";
                $body = Utilities::getMailMessage("credentials", array("[[usuario]]" => $resp_datos['pben_nombre'] . " " . $resp_datos['pben_apellido'], "[[username]]" => $resp_datos['pben_correo'], "[[clave]]" => $resp_datos['pben_cedula'], "[[link]]" => $link), Yii::$app->language);
                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$resp_datos['pben_correo'] => $resp_datos['pben_apellido'] . " " . $resp_datos['pben_nombre']], $asunto, $body);
                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisiones"] => "Jefe"], $asunto, $body);
                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $body);
                $message = array(
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
                "wtmessage" => Yii::t("formulario", "Mensaje2: " . $mensaje), //$error_message
                "title" => Yii::t('jslang', 'Bad Request'),
            );
            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Bad Request"), false, $message);
        }
    }

    public function actionViewpagacarga() {
        $per_id = @Yii::$app->session->get("PB_iduser");
        $model_interesado = new Interesado();
        $resp_gruporol = $model_interesado->consultagruporol($per_id);

        $opag_id = base64_decode($_GET["ido"]);
        $idd = base64_decode($_GET["idd"]);
        $val = base64_decode($_GET["valor"]);
        $valtotal = base64_decode($_GET["valortotal"]);
        $valpagado = base64_decode($_GET["valorpagado"]);

        $mod_cliord = new OrdenPago();
        $resp_cliord = $mod_cliord->consultarOrdenpago($resp_gruporol["grol_id"], $opag_id, $idd);

        if ($resp_cliord) {
            $sins_id = $resp_cliord["sser_id"];
            $per_id = $resp_cliord["per_id"];
            $nombres = $resp_cliord["nombres"];
            $apellidos = $resp_cliord["apellidos"];
            $valorsubido = $resp_cliord["valorsubido"];
            $estado = $resp_cliord["estado"];
            $observacion = $resp_cliord["observacion"];
            $imagen = $resp_cliord["imagen"];
            $int_id = $resp_cliord["int_id"];
        }

        return $this->render('viewpagacarga', [
                    "revision" => array("AP" => Yii::t("formulario", "APPROVED"), "RE" => Yii::t("formulario", "Rejected")),
                    'opag_id' => $opag_id,
                    'idd' => $idd,
                    'sins_id' => $sins_id,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'valor' => $valorsubido,
                    'estado' => $estado,
                    'observacion' => $observacion,
                    'per_id' => $per_id,
                    'imagen' => $imagen,
                    'int_id' => $int_id,
                    'cmb_observacion' => array("" => "Seleccione una opción", "Pago Ilegible" => "Pago Ilegible", "Pago Duplicado" => "Pago Duplicado", "Pago Sin Verificar" => "Pago Sin Verificar"),
                    'val' => $val,
                    'valtotal' => $valtotal,
                    'valpagado' => $valpagado,
        ]);
    }

    public function actionViewpagoexterno() {
        $per_id = @Yii::$app->session->get("PB_iduser");
        $model_interesado = new Interesado();
        $resp_gruporol = $model_interesado->consultagruporol($per_id);
        $opag_id = base64_decode($_GET["ido"]);
        $idd = base64_decode($_GET["idd"]);
        $val = base64_decode($_GET["valor"]);
        $valtotal = base64_decode($_GET["valortotal"]);
        $valpagado = base64_decode($_GET["valorpagado"]);
        $mod_cliord = new OrdenPago();
        $resp_cliord = $mod_cliord->consultarOrdenpago($resp_gruporol["grol_id"], $opag_id, $idd);
        if ($resp_cliord) {
            $sins_id = $resp_cliord["sser_id"];
            $per_id = $resp_cliord["per_id"];
            $nombres = $resp_cliord["nombres"];
            $apellidos = $resp_cliord["apellidos"];
            $valorsubido = $resp_cliord["valorsubido"];
            $estado = $resp_cliord["estado"];
            $observacion = $resp_cliord["observacion"];
            $imagen = $resp_cliord["imagen"];
            $int_id = $resp_cliord["int_id"];
        }
        return $this->render('viewpagoexterno', [
                    "revision" => array("AP" => Yii::t("formulario", "APPROVED"), "RE" => Yii::t("formulario", "Rejected")),
                    'opag_id' => $opag_id,
                    'idd' => $idd,
                    'sins_id' => $sins_id,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'valor' => $valorsubido,
                    'estado' => $estado,
                    'observacion' => $observacion,
                    'per_id' => $per_id,
                    'imagen' => $imagen,
                    'int_id' => $int_id,
                    'cmb_observacion' => array("" => "Seleccione una opción", "Pago Ilegible" => "Pago Ilegible", "Pago Duplicado" => "Pago Duplicado", "Pago Sin Verificar" => "Pago Sin Verificar"),
                    'val' => $val,
                    'valtotal' => $valtotal,
                    'valpagado' => $valpagado,
        ]);
    }

    public function actionSavepago() {
        $usuario = new Usuario();
        $security = new Security();
        $usergrol = new UsuaGrolEper();
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        $modsinsaldos = new SolicitudInscripcionSaldos();
        //online que sube doc capturar asi el id de la persona
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = $_SESSION['persona_solicita'];
            //$per_id = $data["per_id"];
            \app\models\Utilities::putMessageLogFile('perId en savepago: ' . $per_id);
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Parametros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "documento/" . $per_id . "/" . $data["name_file"] . "." . $typeFile;
                \app\models\Utilities::putMessageLogFile('dirFileEnd: ' . $dirFileEnd);
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }
            $arrIm = explode(".", basename($data["documento"]));
            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
            $imagen = $arrIm[0] . "." . $typeFile;
            $opag_id = $data["opag_id"];
            $estado_revision = $data["estado_revision"];
            $observacion = ucwords(strtolower($data["observacion"]));
            $banderacrea = $data["banderacrea"];
            $idd = $data["idd"];
            $dcar_estado_revisa = 'RE';
            $opag_estado_pagado = 'P';
            $controladm = $data["controladm"];
            $ccar_total = $data["totpago"];
            $dcar_valor = $data["valor"];
            $fpag_id = $data["forma_pago"];
            $resultadobtn = "";
            $codautorizacion = "";
            $numero_transaccion = $data["numero_transaccion"];
            $fecha_transaccion = $data["fecha_transaccion"];
            $int_id = $data["int_id"];
            $sins_id = $data["sins_id"]; // solicitud de inscripcion id, esta enviar para crear estudiante
            $per_id = $data["per_id"];

            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            $con2 = \Yii::$app->db_captacion;
            $transaction2 = $con2->beginTransaction();

            try {
                $mod_Estudiante = new Estudiante();
                $mod_Modestuni = new ModuloEstudio();
                $mod_solins = new SolicitudInscripcion();
                $usuario_aprueba = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
                $mod_ordpago = new OrdenPago();
                if ($controladm == '1') {
                    //Insertar en tabla info_carga_prepago
                    $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
                    $creadetalle = $mod_ordpago->insertarCargaprepago($opag_id, $fpag_id, $dcar_valor, $imagen, 'PE', null, null, $numero_transaccion, $fecha_transaccion, $fecha_registro);
                    $idd = $creadetalle;
                }
                //Obtener datos grabados en tablas temporales
                $resp_cargo = $mod_ordpago->consultarCargo($idd, $opag_id);
                if ($resp_cargo['existe'] == 'S') {
                    $valor_det = $resp_cargo['valor'];
                    $fpag_id = $resp_cargo['fpag_id'];
                    $fechapago = $resp_cargo['fechapago'];
                    $imagen = $resp_cargo['imagen'];
                    $valortotal = $resp_cargo['valortotal'];
                    $fechapagtotal = $resp_cargo['fechapagotot'];
                    $ccar_id = $resp_cargo['id'];
                    $valorpagado = $resp_cargo['valorpagado'];
                    $num_transaccion = $resp_cargo['icpr_num_transaccion'];
                    $fecha_transaccion = $resp_cargo['icpr_fecha_transaccion'];
                    $total_pagado = $resp_cargo['total_pagado'];
                    // AQUI SE APRUEBA EL PAGO EN ESTE IF, AQUI DEBE YA DESPUES DE GUARDAR
                    // CREAR COMO ESTUDIANTE EN TODAS LAS TABLAS
                    if ($banderacrea == 1) {
                        //AQUI APRUEBA EL PAGO, REVISAR BIEN DONDE SE DEBE
                        //CAMBIAR LOS ESTADOS DE SALDOS
                        $creg_total = $valortotal;
                        $creg_resultado = 'OK';
                        $creg_fecha_pago_total = $fechapagtotal;
                        $creg_pagado = $total_pagado + $valor_det;
                        if ($valortotal > $creg_pagado) {
                            $creg_estado_pago = 'NP'; //Pago Revisado
                        } else {
                            $creg_estado_pago = 'SP'; //Pago Realizado en su totalidad.
                        }
                        //Obtención de las cuotas pendientes de pago.
                        $resp_desglose = $mod_ordpago->obtenerDesglosepagopend($opag_id);
                        if ($resp_desglose) {
                            for ($i = 0; $i < count($resp_desglose); $i++) {
                                $valor_sobra = $valor_det;
                                if (($resp_desglose[$i]['valor_desglose'] <= $valor_sobra) and ( $valor_sobra > 0)) {
                                    $valor = ($resp_desglose[$i]['valor_desglose']);
                                    $valor_sobra = $valor_sobra - $valor;
                                } else {
                                    $valor = $valor_sobra;
                                }
                                $fechapago = date(Yii::$app->params["dateTimeByDefault"]);
                                $resp_inspago = $mod_ordpago->insertarRegistropago($resp_desglose[$i]['id'], $fpag_id, $valor, $fechapago, $imagen, $num_transaccion, $fecha_transaccion, $observacion, $estado_revision, $usuario_aprueba, $dcar_estado_revisa);
                                if ($resp_inspago) {
                                    if ($resp_desglose[$i]['valor_desglose'] = $valor_det) {
                                        $resp_actcabcar = $mod_ordpago->actualizaDesglosepago($resp_desglose[$i]['id'], 'S', $usuario_aprueba);
                                    }
                                }
                            }
                            //actualizar en detalle_cargo la gestión realizada.
                            $resp_actcar = $mod_ordpago->actualizaDetallecargo($idd, $dcar_estado_revisa, $estado_revision, $observacion, $usuario_aprueba, $valor_det);
                            if ($resp_actcar) {
                                $totalpagado = ($valorpagado + $valor_det);
                                if ($creg_estado_pago == 'SP') {
                                    $ccar_estado_pagado = 'S';
                                    $opag_estado_pagado = 'S';
                                    $fecha_pago_total = date(Yii::$app->params["dateTimeByDefault"]);
                                } else {
                                    $ccar_estado_pagado = 'P';
                                }
                                //actualiza datos de la orden de pago.
                                $resp_opag = $mod_ordpago->actualizaOrdenpago($opag_id, $opag_estado_pagado, $creg_pagado, $fecha_pago_total, $usuario_aprueba);
                                if ($resp_opag) {
                                    $exito = 1;
                                    // CREAR COMO ESTUDIANTE EN TODAS LAS TABLAS if existo es 1
                                    //Se obtienen el método de ingreso y el nivel de interés según la solicitud.
                                    //\app\models\Utilities::putMessageLogFile('solictud de inscripcion: ' . $sins_id);
                                    // Obtener el id de la solicitud a partir del num_solicitud $sins_id
                                    $resp_idsol = $mod_solins->Obtenersinsidxnumsolicitud($sins_id);
                                    $resp_sol = $mod_solins->Obtenerdatosolicitud($resp_idsol["sins_id"]);
                                    //\app\models\Utilities::putMessageLogFile('resp solicitud: ' . $resp_idsol["sins_id"]);
                                    if ($resp_sol) {
                                        //\app\models\Utilities::putMessageLogFile('entro 1: ');
                                        $mod_persona = new Persona();
                                       //\app\models\Utilities::putMessageLogFile('per_id: ' . $per_id);
                                        $resp_persona = $mod_persona->consultaPersonaId($per_id);
                                        //Modificar y activar clave de usuario con numero de cedula
                                        if ($resp_sol["emp_id"] == 1) {
                                            //\app\models\Utilities::putMessageLogFile('entro 2: ');
                                            $usu_sha = $security->generateRandomString();
                                            $usu_pass = base64_encode($security->encryptByPassword($usu_sha, $resp_persona["per_cedula"]));
                                            $respUsu = $usuario->actualizarDataUsuario($usu_sha, $usu_pass, $resp_persona["usu_id"]);
                                            // YA TIENE USUARIO GROL PERO CON ROL 30 DEBERIA MODIFICARSE A 37 SE DEBE ENVIAR EL USU_ID
                                            if ($respUsu) {
                                              //\app\models\Utilities::putMessageLogFile('entro 3: ');
                                                $respUsugrol = $usergrol->actualizarRolEstudiante($resp_persona["usu_id"]);
                                                if ($respUsugrol) {
                                                    //\app\models\Utilities::putMessageLogFile('entro 4: ');
                                                    // Guardar en tabla esdudiante
                                                    $fecha = date(Yii::$app->params["dateTimeByDefault"]);
                                                    // Consultar el estudiante si no ha sido creado
                                                    $resp_estudianteid = $mod_Estudiante->getEstudiantexperid($per_id);
                                                    if ($resp_estudianteid["est_id"] == "") {
                                                        //\app\models\Utilities::putMessageLogFile('entro 5: ');
                                                        //\app\models\Utilities::putMessageLogFile('per_id en 5: ' . $per_id);
                                                        $resp_estudiante = $mod_Estudiante->insertarEstudiante($per_id, /*null,*/ null, $usu_autenticado, null, $fecha, null);
                                                    } else {
                                                        //\app\models\Utilities::putMessageLogFile('entro 6: ');
                                                        $resp_estudiante = $resp_estudianteid["est_id"];
                                                    }
                                                    //consultar el id del etudiante segun el per_id
                                                     $resp_alumno = $mod_Estudiante->getEstudiantexperid($per_id);
                                                    if (!empty($resp_alumno["est_id"])) {
                                                        //\app\models\Utilities::putMessageLogFile('resp_estudiante: '. $resp_alumno["est_id"]);
                                                        //\app\models\Utilities::putMessageLogFile('entro 7: ');
                                                        // Si el est_id ya esta en la tabla estudiante_carrera_programa
                                                        // no se inserta
                                                        // consultar si ya esta en la tabla estudiante_carrera_programa
                                                         $resp_estcarreraprograma = $mod_Estudiante->consultarEstcarreraprogrma($resp_alumno["est_id"]);
                                                         if ($resp_estcarreraprograma["idestcarrera"] == "") {
                                                        //\app\models\Utilities::putMessageLogFile('entro 8: ');
                                                        // Obtener el meun_id con lo con el uaca_id, mod_id y eaca_id, el est_id
                                                        $resp_mestuni = $mod_Modestuni->consultarModalidadestudiouni($resp_sol["nivel_interes"], $resp_sol["mod_id"], $resp_sol["eaca_id"]);
                                                        if ($resp_mestuni) {
                                                            //\app\models\Utilities::putMessageLogFile('entro 9: ');
                                                            //consultar si no esta guardado en estudiante_carrera_programa
                                                            $resp_estucarrera = $mod_Estudiante->consultarEstcarreraprogrma($resp_alumno["est_id"]);
                                                            if ($resp_estucarrera["idestcarrera"] == "") {
                                                                //\app\models\Utilities::putMessageLogFile('entro 10: ');
                                                                // Guardar en tabla estudiante_carrera_programa
                                                                \app\models\Utilities::putMessageLogFile('meun_id: '.$resp_mestuni["meun_id"]);
                                                                $maca_id = $mod_Estudiante->consultarEstMalla($resp_mestuni["meun_id"]);
                                                                \app\models\Utilities::putMessageLogFile('maca_id: '.$maca_id['maca_id']);
                                                                $resp_estudcarreprog = $mod_Estudiante->insertarEstcarreraprog($resp_alumno["est_id"], $resp_mestuni["meun_id"], $fecha, $usu_autenticado, $fecha);
                                                                if ($maca_id) {
                                                                    $resp_malla_academico_estudiante = $mod_Estudiante->insertMallaAcademicoEst($maca_id['maca_id'],$per_id);
                                                                    $pmac_id=$mod_Estudiante->insertPromedioMallaAcademicoEst($per_id);
                                                                //AQUI ACTUAIZAR ESTADOS estado_de_uso, solicitud_inscripcion_saldos
                                                                \app\models\Utilities::putMessageLogFile('solinscr_id: '.$sins_id);
                                                                \app\models\Utilities::putMessageLogFile('ordenpagoid: '.$opag_id);
                                                                $respsolinsaldo = $modsinsaldos->consultaIncripcionSaldos($sins_id, $opag_id);
                                                                    if ($respsolinsaldo["sinsa_id"] > 0) {
                                                                        if ($respsolinsaldo["sinsa_saldo"] > 0) {
                                                                            $sinsa_estado_saldofavor = 'E';
                                                                            $sinsa_estado_saldoconsumido = 'P';
                                                                        }else {
                                                                            $sinsa_estado_saldofavor = 'U';
                                                                            $sinsa_estado_saldoconsumido = 'C';
                                                                        }
                                                                        $respactsolinsaldo = $modsinsaldos->actualizarEstadosSaldos($respsolinsaldo["sinsa_id"], $sinsa_estado_saldofavor, $sinsa_estado_saldoconsumido, $usuario);
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
                                }
                            } //fin de actualizar detalle cargo
                        } //fin de obtener registros de desglose
                    } //fin  $banderacrea
                    else { //En el caso cuando no aprueba el pago.
                        //actualizar en detalle_cargo la gestión realizada.
                        $resp_actdetcar = $mod_ordpago->actualizaDetallecargo($idd, $dcar_estado_revisa, $estado_revision, $observacion, $usuario_aprueba, $valor_det);
                        if ($resp_actdetcar) {
                            $exito = 1;
                        }
                    }
                }  //fin cuando aprueba el pago.
                if ($exito) {
                    $transaction->commit();
                    $transaction2->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $transaction2->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $transaction2->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }

    public function actionEdit() {

    }

    public function actionNew() {
        return $this->render('new');
    }

    public function actionSavecarga() {
        //$per_id = Yii::$app->session->get("PB_perid");
        $modcargapago = new OrdenPago();
        $modsinsaldos = new SolicitudInscripcionSaldos();
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            if ($_SESSION['persona_solicita'] != '') {// tomar el de parametro)
                $per_id = base64_decode($_SESSION['persona_solicita']);
            } else {
                unset($_SESSION['persona_solicita']);
                $per_id = Yii::$app->session->get("PB_perid");
            }
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Parametros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "documento/" . $per_id . "/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }
            $arrIm = explode(".", basename($data["documento"]));
            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
            $imagen = $arrIm[0] . "." . $typeFile;
            $idsol = $data["sinsid"];
            $opag_id = $_GET["txth_ids"];
            $opag_id = $data["idpago"];
            $ccar_total = $data["totpago"];
            $empresa = $data["empresa"];
            $dcar_observacion = ucwords(mb_strtolower($data["observacion"]));

            if (empty($ccar_total)) {
                $ccar_total = $data["pago"];
            }
            $dcar_valor = $data["pago"];
            $fpag_id = $data["metodopago"];
            $dcar_num_transaccion = $data["numtransaccion"];
            $dcar_fecha_transaccion = $data["fechatransaccion"];
            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            try {
                $dcar_revisado = 'PE';
                $dcar_resultado = '';
                $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
                $creadetalle = $modcargapago->insertarCargaprepago($opag_id, $fpag_id, $dcar_valor, $imagen, $dcar_revisado, $dcar_resultado, $dcar_observacion, $dcar_num_transaccion, $dcar_fecha_transaccion, $fecha_registro);
                if ($creadetalle) {
                    //REVISAR BIEN, SI EL PAGO SE CARGA, AQUI ACTUALIZAR  ESTADOS SALDOS
                    //\app\models\Utilities::putMessageLogFile('idsolvvv:' . $idsol);
                    //\app\models\Utilities::putMessageLogFile('opag_idvv:' . $opag_id);
                    $respsolinsaldo = $modsinsaldos->consultaIncripcionSaldos($idsol, $opag_id);
                    if ($respsolinsaldo["sinsa_id"] > 0) {
                        if ($respsolinsaldo["sinsa_saldo"] > 0) {
                            $sinsa_estado_saldofavor = 'E';
                            $sinsa_estado_saldoconsumido = null;
                        }else {
                            $sinsa_estado_saldofavor = 'U';
                            $sinsa_estado_saldoconsumido = null;
                        }
                        $respactsolinsaldo = $modsinsaldos->actualizarEstadosSaldos($respsolinsaldo["sinsa_id"], $sinsa_estado_saldofavor, $sinsa_estado_saldoconsumido, $usuario);
                    }
                    //Envío de correo a colecturia.
                    //\app\models\Utilities::putMessageLogFile('Orden Pago:' . $opag_id);
                    //\app\models\Utilities::putMessageLogFile('Empresa:' . $empresa);
                    $informacion_interesado = $modcargapago->datosBotonpago($opag_id, $empresa);
                    $pri_nombre = $informacion_interesado["nombres"];
                    $pri_apellido = $informacion_interesado["apellidos"];
                    $nombres = $pri_nombre . " " . $pri_apellido;
                    $metodo = $informacion_interesado["curso"];
                    $tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                    $asunto = Yii::t("interesado", "UTEG - Registration Online");
                    \app\models\Utilities::putMessageLogFile('Nombres y apellidos:' . $nombres);
                    \app\models\Utilities::putMessageLogFile('Método:' . $metodo);
                    \app\models\Utilities::putMessageLogFile('Titulo:' . $tituloMensaje);
                    \app\models\Utilities::putMessageLogFile('Asunto:' . $asunto);
                    $bodycolecturia = Utilities::getMailMessage("Paymentraisedcollect", array("[[nombres_completos]]" => $nombres, "[[metodo]]" => $metodo), Yii::$app->language);
                    //Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["colecturia"] => "Colecturia"], $asunto, $bodycolecturia);
                    $exito = 1;
                }
                if ($exito == 1) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Documento ha sido cargado."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error: Documento No ha sido cargado."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                echo \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }

    public function actionListarpagoscargados() {
        $mod_pago = new OrdenPago();

        $data = null;
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["f_estado"] = $data['f_estado'];
            $arrSearch["search"] = $data['search'];
            $resp_pago = $mod_pago->listarPagoscargados($arrSearch);
            return $this->renderPartial('_listarpagoscargados_grid', [
                        "model" => $resp_pago,
            ]);
        } else {
            $resp_pago = $mod_pago->listarPagoscargados();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        $arrEstados = ArrayHelper::map([["id" => "T", "value" => "Todos"], ["id" => "S", "value" => "Pagada"], ["id" => "P", "value" => "Pendiente"]/* , ["id" => "NA", "value" => "No Disponible"] */], "id", "value");
        return $this->render('listarpagoscargados', [
                    'model' => $resp_pago,
                    'arrEstados' => $arrEstados
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrData = array();
        $arrHeader = array(
            Yii::t("formulario", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("solicitud_ins", "Income Method"),
            Yii::t("formulario", "Status"),
        );
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data["search"];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];
        $arrSearch["f_estado"] = $data["f_estado"];
        //$arrData = array();
        $model_pag = new OrdenPago();
        if (empty($arrSearch)) {
            $arrData = $model_pag->listarPagoscargadosexcel(array(), true);
        } else {
            $arrData = $model_pag->listarPagoscargadosexcel($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "List Payment");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionIndexadm() {
        $per_id = @Yii::$app->session->get("PB_perid"); //@Yii::$app->session->get("PB_iduser");
        \app\models\Utilities::putMessageLogFile('perId en Indexadm: ' . $per_id);
        $model_interesado = new Interesado();
        $resp_gruporol = $model_interesado->consultagruporol($per_id);
        $mod_pago = new OrdenPago();
        $data = null;
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["f_estado"] = $data['f_estado'];
            $arrSearch["search"] = $data['search'];
            $resp_pago = $mod_pago->listarSolicitudesadm($arrSearch, $resp_gruporol["grol_id"]);
            return $this->renderPartial('_indexadm_grid', [
                        "model" => $resp_pago,
            ]);
        } else {
            $resp_pago = $mod_pago->listarSolicitudesadm(null, $resp_gruporol["grol_id"]);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        return $this->render('indexadm', [
                    'model' => $resp_pago,
        ]);
    }

    public function actionRegistrarpagoadm() {
        $opag_id = $_GET["ido"];
        $per_id = $_GET["per_id"];
        $sol_id = $_GET["sins_id"];
        $emp_id = $_GET["emp_id"];

        $mod_opago = new OrdenPago();
        $arr_forma_pago = $mod_opago->formaPago("1");

        $resp_orden = $mod_opago->listarSolicitud($sol_id, null, $opag_id, 0);
        $valor_total = $resp_orden['ipre_precio'];
        $saldo_pendiente = $resp_orden['pendiente'];
        $int_id = $resp_orden['int_id'];
        $sins_id = $resp_orden['solicitud'];

        $resp_doc = $mod_opago->listarDocumento($opag_id);
        $data = null;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        \app\models\Utilities::putMessageLogFile('perId en Registrarpagoadm: ' . $per_id);
        return $this->render('registrarpagoadm', [
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    'model' => $resp_doc,
                    'saldo_pendiente' => $saldo_pendiente,
                    'valor_total' => $valor_total,
                    'opag_id' => $opag_id,
                    'int_id' => $int_id,
                    'sins_id' => $sins_id,
                    'per_id' => $per_id,
                    'emp_id' => $emp_id,
        ]);
    }

    public function actionListarpagosolicitud() {
        $per_id = Yii::$app->session->get("PB_perid");
        $model_interesado = new Interesado();
        $resp_gruporol = $model_interesado->consultagruporol($per_id);
        \app\models\Utilities::putMessageLogFile('rol:' . $resp_gruporol[0]);

        $per_ids = base64_decode($_GET['perid']);
        $sol_id = base64_decode($_GET['id_sol']);
        $model_pag = new OrdenPago();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["search"] = $data['search'];
            //if (empty($per_ids)) {  //vista para el interesado
            $rol = 1;
            $resp_pago = $model_pag->listarSolicitud($sol_id, $per_id, null, $resp_gruporol["grol_id"], $arrSearch);

            return $this->renderPartial('_listarpagosolicitud_grid', [
                        "model" => $resp_pago,
            ]);
        } else {
            // if (empty($per_ids)) {  //vista para el interesado
            $rol = 1;
            $resp_pago = $model_pag->listarSolicitud($sol_id, $per_id, null, $resp_gruporol["grol_id"]);
        }
        //verificar rol de la persona que esta en sesión
        //$resp_rol = $model_pag->encuentraRol($per_id);
        //$data = null;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        return $this->render('listarpagosolicitud', [
                    'model' => $resp_pago,
        ]);
    }

    public function actionHistorialtransacciones() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $model_sbpag = new SolicitudBotonPago();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $data_transacciones = $model_sbpag->consultarHistoralTransacciones($per_id, $arrSearch);
            return $this->renderPartial('_historialtransaccion_grid', [
                    "model" => $data_transacciones,
            ]);
        } else {
            $data_transacciones = $model_sbpag->consultarHistoralTransacciones($per_id);
        }
        $data_transacciones = $model_sbpag->consultarHistoralTransacciones($per_id);
        return $this->render('historialtransaccion', [
                    'model' => $data_transacciones,
        ]);
    }

    public function actionActualizarpago() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $doc_id = $data['doc_idd'];
            $model_documento = new Documento();
            \app\models\Utilities::putMessageLogFile("doc_id:" . $doc_id);
            $data_vpos = $model_documento->consultarVposByDocid($doc_id);
            $response = $this->render('btnpago', array(
                "referenceID" => $data_vpos["reference"],
                "requestID" => $data_vpos["requestID"],
                "ordenPago" => $doc_id,
                "tipo_orden" => 2,
                "response" => $data_vpos["resp"]
            ));
        }
    }

    public function actionVerificarpagoexterno() {
        $model_sbpag = new SolicitudBotonPago();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $data_pago_ext = $model_sbpag->consultarPagoExterno($arrSearch);
            return $this->renderPartial('_verificarpagoexterno_grid', [
                        "model" => $data_pago_ext,
            ]);
        } else {

        }
        $data_pago_ext = $model_sbpag->consultarPagoExterno($arrSearch);
        return $this->render('verificarpagoexterno', [
                    'model' => $data_pago_ext,
        ]);
    }

    public function actionDetallepagoexterno() {
        $data = Yii::$app->request->get();
        $model_documento = new Documento();
        $data_pago_ext = $model_documento->consultarDetalledocumentoById($data['doc_id']);
        return $this->render('detallepagoexterno', [
                    'model' => $data_pago_ext,
        ]);
    }

    public function actionUpdate() {

    }

    public function actionCargardocfact() {
        $mod_cargapago = new OrdenPago();
        $sins_id = isset($_GET['ids']) ? base64_decode($_GET['ids']) : 1; //NULL
        $data = null;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {

            }
        }
        $rowData = $mod_cargapago->consultarInteresadoPersona($sins_id);
        return $this->render('cargardocfact', [
                    "sins_id" => $sins_id,
                    "per_id" => isset($rowData[0]['per_id']) ? $rowData[0]['per_id'] : 0,
                    "opag_total" => isset($rowData[0]['opag_total']) ? $rowData[0]['opag_total'] : 0,
        ]);
    }

    public function actionSavefactura() {
        $modcargapago = new OrdenPago();
        $rowData = $modcargapago->consultarInteresadoPersona($sins_id);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Parametros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "facturas/" . $data["name_perid"] . "/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }
            if ($data["procesar_file"]) {
                $carga_archivo = $modcargapago->insertarDtosFactDoct($data);
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

            return;
        } else {
            return $this->render('cargarleads', []);
        }
    }

    public function actionDescargafactura() {
        $nombreZip = "facturas_" . time();
        $content_type = Utilities::mimeContentType($nombreZip . ".zip");
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombreZip . ".zip");
        header('Cache-Control: max-age=0');
        $sins_id = isset($_GET['ids']) ? base64_decode($_GET['ids']) : 1; //NULL
        $ruta = OrdenPago::consultarRutaFile($sins_id);

        $Path = Yii::$app->basePath . "/uploads/" . $ruta;
        Utilities::putMessageLogFile($Path);

        // se deben zippear 2 files el xml y el pdf
        /* $arr_files = array(
          array("ruta" => Yii::$app->basePath . "/uploads/ficha/silueta_default.png",
          "name" => basename(Yii::$app->basePath . "/uploads/ficha/silueta_default.png")),
          array("ruta" => Yii::$app->basePath . "/uploads/ficha/Silueta-opc-4.png",
          "name" => basename(Yii::$app->basePath . "/uploads/ficha/Silueta-opc-4.png")),
          ); */
        $arr_files = array(
            array("ruta" => Yii::$app->basePath . "/uploads/" . $ruta,
                "name" => basename(Yii::$app->basePath . "/uploads/" . $ruta)),
        );
        $tmpDir = Utilities::zipFiles($nombreZip, $arr_files);
        $file = file_get_contents($tmpDir);
        Utilities::removeTemporalFile($tmpDir);
        echo $file;
        exit();
    }

    public function actionExpexcelpagos() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K");

        $arrHeader = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "First Names"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Income Method"),
            Yii::t("formulario", "Status"),
        );
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data["search"];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];
        $arrSearch["f_estado"] = $data["f_estado"];

        $arrData = array();
        $model_pag = new OrdenPago();
        if (empty($arrSearch)) {
            $arrData = $model_pag->listarPagosolicitudExcel(array(), true);
        } else {
            $arrData = $model_pag->listarPagosolicitudExcel($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "List Payment");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExpexcelcolec() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K");

        $arrHeader = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Income Method"),
            Yii::t("formulario", "Status"),
        );
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data["search"];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];

        $arrData = array();
        $model_pag = new OrdenPago();
        if (empty($arrSearch)) { //listarSolicitudesadm
            $arrData = $model_pag->listarSolicitudesadmexcel(array(), true);
        } else {
            $arrData = $model_pag->listarSolicitudesadmexcel($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "Registration Payments for Collections");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfpagosestud() {
        $report = new ExportFile();
        $this->view->title = financiero::t("Pagos", "Payments charged by Student"); // Titulo del reporte

        $model_pag = new OrdenPago();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["search"] = $data["search"];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];
        $arrSearch["f_estado"] = $data["f_estado"];

        $arr_head = array(
            Yii::t("formulario", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("solicitud_ins", "Income Method"),
            Yii::t("formulario", "Status"),
        );

        if (empty($arrSearch)) {
            $arr_body = $model_pag->listarPagoscargadosexcel(array(), true);
        } else {
            $arr_body = $model_pag->listarPagoscargadosexcel($arrSearch, true);
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

    public function actionExppdfpagos() {
        $report = new ExportFile();
        $this->view->title = financiero::t("Pagos", "List Payment"); // Titulo del reporte

        $model_pag = new OrdenPago();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["search"] = $data["search"];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];
        $arrSearch["f_estado"] = $data["f_estado"];

        $arr_head = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "First Names"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Income Method"),
            Yii::t("formulario", "Status"),
        );

        if (empty($arrSearch)) {
            $arr_body = $model_pag->listarPagosolicitudExcel(array(), true);
        } else {
            $arr_body = $model_pag->listarPagosolicitudExcel($arrSearch, true);
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

    public function actionExppdfcolec() {
        $report = new ExportFile();
        $this->view->title = financiero::t("Pagos", "Registration Payments for Collections"); // Titulo del reporte

        $arrHeader = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Income Method"),
            Yii::t("formulario", "Status"),
        );
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data["search"];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];

        $arrData = array();
        $model_pag = new OrdenPago();
        if (empty($arrSearch)) { //listarSolicitudesadm
            $arrData = $model_pag->listarSolicitudesadmexcel(array(), true);
        } else {
            $arrData = $model_pag->listarSolicitudesadmexcel($arrSearch, true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionBotonpago() {
        $data = Yii::$app->request->post();
        $dataGet = Yii::$app->request->get();
        $con1 = \Yii::$app->db_facturacion;
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $referenceID = isset($data["referenceID"]) ? $data["referenceID"] : null;
        if (!is_null($referenceID)) {
            try {
                $transaction = $con1->beginTransaction();
                $sins_id = base64_decode($dataGet["sins_id"]);
                $solInc_mod = SolicitudInscripcion::findOne($sins_id);
                $opago_mod = OrdenPago::findOne(["sins_id" => $sins_id, "opag_estado_pago" => "P", "opag_estado" => "1", "opag_estado_logico" => "1"]);
                $response = $this->render('btnpago', array(
                    "referenceID" => $data["resp"]["reference"],
                    "requestID" => $data["requestID"],
                    "ordenPago" => $opago_mod->opag_id,
                    "tipo_orden" => 1,
                    "response" => $data["resp"],
                ));
                if ($data["resp"]["status"]["status"] == "APPROVED") {
                    $opago_mod->opag_estado_pago = "S";
                    $opago_mod->opag_valor_pagado = $opago_mod->opag_total;
                    $opago_mod->opag_fecha_pago_total = date("Y-m-d H:i:s");
                    $opago_mod->opag_usu_modifica = @Yii::$app->session->get("PB_iduser");
                    $opago_mod->opag_fecha_modificacion = date("Y-m-d H:i:s");
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                            //"data" => $response,
                    );
                    if ($opago_mod->save()) {
                        $dpag_mod = DesglosePago::findOne(["opag_id" => $opago_mod->opag_id, "dpag_estado_pago" => "P", "dpag_estado" => "1", "dpag_estado_logico" => "1"]);
                        $dpag_mod->dpag_estado_pago = "S";
                        $dpag_mod->dpag_usu_modifica = @Yii::$app->session->get("PB_iduser");
                        $dpag_mod->dpag_fecha_modificacion = date("Y-m-d H:i:s");
                        if ($dpag_mod->save()) {
                            $regpag_mod = new RegistroPago();
                            $regpag_mod->dpag_id = $dpag_mod->dpag_id;
                            $regpag_mod->fpag_id = 6; // boton de pagos
                            $regpag_mod->rpag_valor = $opago_mod->opag_total;
                            $regpag_mod->rpag_fecha_pago = $opago_mod->opag_fecha_pago_total;
                            $regpag_mod->rpag_revisado = "RE";
                            $regpag_mod->rpag_resultado = "AP";
                            $regpag_mod->rpag_num_transaccion = $referenceID;
                            $regpag_mod->rpag_fecha_transaccion = $opago_mod->opag_fecha_pago_total;
                            $regpag_mod->rpag_usuario_transaccion = @Yii::$app->session->get("PB_iduser");
                            $regpag_mod->rpag_codigo_autorizacion = "";
                            $regpag_mod->rpag_estado = "1";
                            $regpag_mod->rpag_estado_logico = "1";
                            if ($regpag_mod->save()) {
                                $transaction->commit();
                                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                            } else {
                                Utilities::putMessageLogFile("Boton Pagos: Error al crear Registro Pago. RefId: " . $referenceID . " Error: " . json_encode($regpag_mod->errors));
                                throw new Exception('Error al crear Registro Pago.' . json_encode($regpag_mod->errors));
                            }
                        } else {
                            Utilities::putMessageLogFile("Boton Pagos: Error al actualizar Desglose Pago RefId: " . $referenceID . " Error: " . json_encode($dpag_mod->errors));
                            throw new Exception('Error al actualizar Desglose Pago.' . json_encode($dpag_mod->errors));
                        }
                    } else {
                        Utilities::putMessageLogFile("Boton Pagos: Error al actualizar pago. RefId: " . $referenceID . " Error: " . json_encode($opago_mod->errors));
                        throw new Exception('Error al actualizar pago.' . json_encode($opago_mod->errors));
                    }
                } else {
                    $message = array(
                        "wtmessage" => $data["resp"]["status"]["message"],
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Utilities::putMessageLogFile("Boton Pagos: Error . RefId: " . $referenceID . "Error: " . $e->getMessage());
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Invalid request. Please do not repeat this request again. Contact to Administrator.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
        Secuencias::initSecuencia($con1, $emp_id, 1, 1, 'BPA', "BOTON DE PAGOS DINERS");
        // Info de Solicitud Inscripcion
        $sins_id = base64_decode($dataGet["sins_id"]);
        $solInc_mod = SolicitudInscripcion::findOne($sins_id);
        $int_mod = Interesado::findOne($solInc_mod->int_id);
        $per_mod = Persona::findOne($int_mod->per_id);
        $opago_mod = OrdenPago::findOne(["sins_id" => $sins_id, "opag_estado_pago" => "P", "opag_estado" => 1, "opag_estado_logico" => 1]);
        $obj_sol = $solInc_mod::consultarInteresadoPorSol_id($sins_id);
        $descripcionItem = financiero::t("Pagos", "Payment of ") . $obj_sol["carrera"];
        $titleBox = financiero::t("Pagos", "Payment Course/Career/Program: ") . $obj_sol["carrera"];
        $totalpagar = $opago_mod->opag_total;
        return $this->render('btnpago', array(
                    "referenceID" => str_pad(Secuencias::nuevaSecuencia($con1, $emp_id, 1, 1, 'BPA'), 8, "0", STR_PAD_LEFT),
                    "ordenPago" => $opago_mod->opag_id,
                    "tipo_orden" => 1,
                    "nombre_cliente" => $per_mod->per_pri_nombre,
                    "apellido_cliente" => $per_mod->per_pri_apellido,
                    "descripcionItem" => $descripcionItem,
                    "titleBox" => $titleBox,
                    "email_cliente" => $per_mod->per_correo,
                    "total" => $totalpagar,
        ));
    }

    public function actionExpexcelhis() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrData = array();
        $arrHeader = array(
            Yii::t("formulario", "Code"),
            Yii::t("formulario", "Reference"),
            Yii::t("formulario", "Student"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Pago"),
            Yii::t("formulario", "Status"),
        );
        $per_id = Yii::$app->session->get("PB_perid");
        $model_persona = new Persona();
        $model_documento = new Documento();
        $model_ordenpago = new OrdenPago();
        $data_persona = $model_persona->consultaPersonaId($per_id);
        $cedula = $data_persona['per_cedula'];
        $doc_id = $model_documento->consultarDocIdByCedulaBen($cedula);
        $opag_id = $model_ordenpago->consultarOpagIdByCedula($cedula);
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];

        $model_sbpag = new SolicitudBotonPago();
        if (empty($arrSearch)) {
            $arrData = $model_sbpag->consultarHistoralTransacciones($doc_id, $opag_id, array(), true);
        } else {
            $arrData = $model_sbpag->consultarHistoralTransacciones($doc_id, $opag_id, $arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "Transaction History");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfhis() {
        $report = new ExportFile();
        $this->view->title = financiero::t("Pagos", "Transaction History"); // Titulo del reporte

        $arrHeader = array(
            Yii::t("formulario", "Code"),
            Yii::t("formulario", "Reference"),
            Yii::t("formulario", "Student"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Pago"),
            Yii::t("formulario", "Status"),
        );
        $per_id = Yii::$app->session->get("PB_perid");
        $model_persona = new Persona();
        $model_documento = new Documento();
        $model_ordenpago = new OrdenPago();
        $data_persona = $model_persona->consultaPersonaId($per_id);
        $cedula = $data_persona['per_cedula'];
        $doc_id = $model_documento->consultarDocIdByCedulaBen($cedula);
        $opag_id = $model_ordenpago->consultarOpagIdByCedula($cedula);
        $data = Yii::$app->request->get();
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];
        $arrData = array();
        $model_sbpag = new SolicitudBotonPago();
        if (empty($arrSearch)) {
            $arrData = $model_sbpag->consultarHistoralTransacciones($doc_id, $opag_id, array(), true);
        } else {
            $arrData = $model_sbpag->consultarHistoralTransacciones($doc_id, $opag_id, $arrSearch, true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionExpexcelpagosext() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrData = array();
        $arrHeader = array(
            Yii::t("formulario", "Reference"),
            Yii::t("formulario", "Student"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Total value"),
            financiero::t("Pagos", "Boton Payment status"),
        );
        // $per_id = Yii::$app->session->get("PB_perid");
        //VERIFICAR SI LOS PARAMETROS $doc_id y $opag_id SE VAN USAR SINO BORRAR
        /* $model_persona = new Persona();
          $model_documento = new Documento();
          $model_ordenpago = new OrdenPago();
          $data_persona=$model_persona->consultaPersonaId($per_id);
          $cedula=$data_persona['per_cedula'];
          $doc_id=$model_documento->consultarDocIdByCedulaBen($cedula);
          $opag_id=$model_ordenpago->consultarOpagIdByCedula($cedula); */
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];

        $model_sbpag = new SolicitudBotonPago();
        if (empty($arrSearch)) {
            $arrData = $model_sbpag->consultarPagoExterno(/* $doc_id,$opag_id, */array(), true);
        } else {
            $arrData = $model_sbpag->consultarPagoExterno(/* $doc_id,$opag_id, */$arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "Check External Payments");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfpagosext() {
        $report = new ExportFile();
        $this->view->title = financiero::t("Pagos", "Check External Payments"); // Titulo del reporte

        $arrHeader = array(
            Yii::t("formulario", "Reference"),
            Yii::t("formulario", "Student"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Total value"),
            financiero::t("Pagos", "Boton Payment status"),
        );
        // $per_id = Yii::$app->session->get("PB_perid");
        //VERIFICAR SI LOS PARAMETROS $doc_id y $opag_id SE VAN USAR SINO BORRAR
        /* $model_persona = new Persona();
          $model_documento = new Documento();
          $model_ordenpago = new OrdenPago();
          $data_persona=$model_persona->consultaPersonaId($per_id);
          $cedula=$data_persona['per_cedula'];
          $doc_id=$model_documento->consultarDocIdByCedulaBen($cedula);
          $opag_id=$model_ordenpago->consultarOpagIdByCedula($cedula); */
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data["f_ini"];
        $arrSearch["f_fin"] = $data["f_fin"];
        $model_sbpag = new SolicitudBotonPago();
        if (empty($arrSearch)) {
            $arrData = $model_sbpag->consultarPagoExterno(/* $doc_id,$opag_id, */array(), true);
        } else {
            $arrData = $model_sbpag->consultarPagoExterno(/* $doc_id,$opag_id, */$arrSearch, true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

}
