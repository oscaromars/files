<?php

namespace app\modules\financiero\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\ExportFile;
use app\models\Persona;
use app\models\Usuario;
use yii\helpers\Url;
use yii\base\Exception;
use yii\base\Security;
use app\modules\academico\models\Especies;
use app\modules\financiero\models\FormaPago;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\ModuloEstudio;
use app\modules\financiero\models\PagosFacturaEstudiante;
use app\modules\financiero\models\CargaCartera;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();

class PagosfacturasController extends \app\components\CController {

    private function estados() {
        return [
            '0' => Yii::t("formulario", "Todos"),
            '1' => Yii::t("formulario", "Pendiente"),
            '2' => Yii::t("formulario", "Aprobado"),
            '3' => Yii::t("formulario", "Rechazado"),
        ];
    }

    private function estadoRechazo() {
        return [
            '0' => Yii::t("formulario", "Seleccione"),
            '2' => Yii::t("formulario", "Aprobado"),
            '3' => Yii::t("formulario", "Rechazado"),
        ];
    }

    private function estadoFinanciero() {
        return [
            '0' => Yii::t("formulario", "Todos"),
            'N' => Yii::t("formulario", "Pendiente"),
            'C' => Yii::t("formulario", "Cancelado"),
        ];
    }

    private function estadoReverso() {
        return [
            '0' => Yii::t("formulario", "Seleccione"),
            '1' => Yii::t("formulario", "Pendiente"),            
        ];
    }

    public function actionRevisionpagos() {
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                if (($data["unidad"] == 1) or ( $data["unidad"] == 2)) {
                    $modalidad = $mod_modalidad->consultarModalidad($data["unidad"], 1);
                } else {
                    $modalidad = $modestudio->consultarModalidadModestudio();
                }
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["estadopago"] = $data['estadopago'];
            $arrSearch["estadofinanciero"] = $data['estadofinanciero'];
            $resp_pago = $mod_pagos->getPagos($arrSearch, false);
            return $this->renderPartial('_index-grid_revisionpago', [
                        "model" => $resp_pago,
            ]);
        }
        $model = $mod_pagos->getPagos(null, false);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);

        return $this->render('index_revisionpago', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_estado' => $this->estados(),
                    'arr_estado_financiero' => $this->estadoFinanciero(),
        ]);
    }

    public function actionViewsaldo() {
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modcanal = new Oportunidad();
        //$mod_pagos = new PagosFacturaEstudiante();
        $mod_cartera = new CargaCartera();
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($personaData['uaca_id'], 1);
        if (($personaData['uaca_id'] == 1) or ( $personaData['uaca_id'] == 2)) {
            $carrera = $modcanal->consultarCarreraModalidad($personaData['uaca_id'], $personaData['mod_id']);
        } else {
            $carrera = $modestudio->consultarCursoModalidad($personaData['uaca_id'], $personaData['mod_id']); // tomar id de impresa
        }
        //$pagospendientesea = $mod_pagos->getPagospendientexest($personaData['per_cedula'], false);
        $pagospendientesea = $mod_cartera->getPagospendientexestcar($personaData['per_cedula'], false);
        return $this->render('viewsaldo', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    'model' => $pagospendientesea,
        ]);
    }

    public function actionRevisar() { 
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $model = $mod_pagos->consultarPago($dpfa_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('revisar', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoRechazo(),
                    'arrObservacion' => array("0" => "Seleccione", "Archivo Ilegible" => "Archivo Ilegible", "Archivo no corresponde al pago" => "Archivo no corresponde al pago", "Archivo con Error" => "Archivo con Error", "Valor pagado no cubre factura" => "Valor pagado no cubre factura", "Archivo Duplicado" => "Archivo Duplicado"),
        ]);
    }

    public function actionSubirpago() {
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modcanal = new Oportunidad();
        $mod_fpago = new FormaPago();
        //$mod_pagos = new PagosFacturaEstudiante();
        $mod_cartera = new CargaCartera();
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($personaData['uaca_id'], 1);
        if (($personaData['uaca_id'] == 1) or ( $personaData['uaca_id'] == 2)) {
            $carrera = $modcanal->consultarCarreraModalidad($personaData['uaca_id'], $personaData['mod_id']);
        } else {
            $carrera = $modestudio->consultarCursoModalidad($personaData['uaca_id'], $personaData['mod_id']); // tomar id de impresa
        }
        $arr_forma_pago = $mod_fpago->consultarFormaPagosaldo();
        //$pagospendientesea = $mod_pagos->getPagospendientexest($personaData['per_cedula'], false);
        $pagospendientesea = $mod_cartera->getPagospendientexestcar($personaData['per_cedula'], false);
        return $this->render('subirpago', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    'model' => $pagospendientesea,
        ]);
    }

    public function actionCargarpago() {
        $per_id = @Yii::$app->session->get("PB_perid");
        //$ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        $especiesADO = new Especies();
        $est_id = $especiesADO->recuperarIdsEstudiente($per_id);
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $mod_persona = new Persona();
        $data_persona = $mod_persona->consultaPersonaId($per_id);
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
                if ($typeFile == 'jpg' || $typeFile == 'png' || $typeFile == 'pdf') {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosfinanciero/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
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
        return $this->render('subirpago', [
        ]);
    }

    public function actionSaverechazo() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $id = $data['dpfa_id'];
            $resultado = $data['resultado'];
            $observacion = $data['observacion'];
            if (($resultado == "0") /* or ( $observacion == "0") */) {
                //Utilities::putMessageLogFile('ingresa');
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Seleccione un valor para los campos de 'Resultado' "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                return;
            } else {
                if (($resultado == "3" && $observacion != "0") or $resultado == "2") {
                    $con = \Yii::$app->db_facturacion;
                    $transaction = $con->beginTransaction();
                    try {
                        $datos = $mod_pagos->consultarPago($id);
                        //Utilities::putMessageLogFile('$cuota:' . $datos['dpfa_num_cuota']);
                        if ($observacion == 0 && $resultado == "2") {
                            $observacion = null;
                        }
                        $respago = $mod_pagos->grabarRechazo($id, $resultado, $observacion);

                        if($resultado == "2"){
                            $cartera = $mod_pagos->buscarIdCartera($id);
                            $id_cartera = $cartera[0]['ccar_id'];

                            $cargo = CargaCartera::findOne($id_cartera);
                            $cargo->ccar_estado_cancela = 'C';
                            $cargo->ccar_fecha_modificacion = $fecha;
                            $cargo->ccar_usu_modifica = $usuario;
                            $cargo->save();
                            //echo(print_r($cartera,true));
                        }//if

                        if ($respago) {
                            $transaction->commit();

                            $correo_estudiante = $datos['per_correo'];
                            $user = $datos['estudiante'];
                            $tituloMensaje = 'Pagos en Línea';
                            $asunto = 'Pagos en Línea';
                            if ($resultado != "2") {
                                if (!empty($datos['dpfa_num_cuota'])) {
                                    //Utilities::putMessageLogFile('$cuota:' . $datos['dpfa_num_cuota']);
                                    $body = Utilities::getMailMessage("pagonegadocuota", array(
                                                "[[user]]" => $user,
                                                "[[link]]" => "https://asgard.uteg.edu.ec/asgard/",
                                                "[[motivo]]" => $observacion,
                                                "[[factura]]" => $datos['dpfa_factura'],
                                                "[[cuota]]" => $datos['dpfa_num_cuota'],
                                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                } else {
                                    $body = Utilities::getMailMessage("pagonegado", array(
                                                "[[user]]" => $user,
                                                "[[link]]" => "https://asgard.uteg.edu.ec/asgard/",
                                                "[[motivo]]" => $observacion,
                                                "[[factura]]" => $datos['dpfa_factura'],
                                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                }
                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                            } else {
                                //Utilities::putMessageLogFile('entro_envio vorreo');
                                //Utilities::putMessageLogFile('correo..' . $correo_estudiante);
                                $body = Utilities::getMailMessage("pagoaprobado", array(
                                            "[[user]]" => $user,   
                                            "[[factura]]" => $datos['dpfa_factura'],
                                                ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                                                Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                            }
                             //Utilities::putMessageLogFile('graba la transaccion');
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada"),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        } else {
                            $message = ["info" => Yii::t('exception', 'Error al grabar 0.')];
                            echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
                        }
                    } catch (Exception $ex) {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    }
                    return;
                } else {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Seleccione un valor para los campos de 'Observación' "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    return;
                }
            }
            return;
        }
    }

    public function actionSavepagopendiente() {
        //online que sube doc capturar asi el id de la persona   
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        //$cartera = $mod_pagos->buscarIdCartera($dfac_id);
        if (Yii::$app->request->isAjax) {
            $data           = Yii::$app->request->post();

            //Preguntamos por la forma de pago
            if($data["formapago"]==6){
                //Pago Online
                $statusMsg = '';

                if(!empty($data['token'])){
                    // Retrieve stripe token, card and user info from the submitted form data 
                    $token  = $data['token']; 
                    $name   = $data['nombres']; 

                    $mod_usuario = Persona::find()->select("per_correo")->where(["per_id" => $data['per_id']])->asArray()->all();
                    $email       = $mod_usuario[0]['per_correo'];   

                    /******************************************************************/
                    /********** PARA DESARROLLO  **************************************/
                    /******************************************************************/
                    $stripe = array(
                        'secret_key'      => 'sk_test_51HrVkKC4VyMkdPFRrDhbuQLABtvVq3tfZ8c3E3fm55Q7kg5anz6fqO5qrlPBVu7fDc9XVWGTb55M6TiIq4hwHz8J00rVFgisaj',
                        'publishable_key' => 'pk_test_51HrVkKC4VyMkdPFRZ5aImiv4UNRIm1N7qh2VWG5YMcXJMufmwqvCVYAKSZVxvsjpP6PbjW4sSrc8OKrgfNsrmswt00OezUqkuN',
                    );
                    
                    /******************************************************************/
                    /********** PARA PRODUCCION  **************************************/
                    /******************************************************************/
                    /*
                    $stripe = array(
                        'secret_key'      => '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',
                        'publishable_key' => 'pk_live_51HrVkKC4VyMkdPFRjqnwytVZZb552sp7TNEmQanSA78wA1awVHIDp94YcNKfa66Qxs6z2E73UGJwUjWN2pcy9nWl008QHsVt3Q',
                    );
                    */

                   \Stripe\Stripe::setApiKey($stripe['secret_key']);
                     
                    // Add customer to stripe 
                    try {  
                        $customer = \Stripe\Customer::create(array( 
                            'email'   => $email, 
                            'source'  => $token 
                        )); 
                    }catch(Exception $e) {  
                        $api_error = $e->getMessage();  
                        return json_encode($api_error);
                    } 
                     
                    if(empty($api_error) && $customer){  
                         
                        // AQUI SE PARAMETRIZA EL VALOR DE LA INSCRIPCION
                        // ESTE VALOR A FUTURO DEBERA SER LLAMADO DE LA BASE
                        // Convert price to cents 
                        $valor_inscripcion = $data['valor']; 
                        $itemPriceCents    = ($valor_inscripcion*100); 
                         
                        // Charge a credit or a debit card 
                        try {  
                            $charge = \Stripe\Charge::create(array( 
                                'customer'    => $customer->id, 
                                'amount'      => $itemPriceCents, 
                                'currency'    => "usd", 
                                'description' => "Pago de Cuotas"
                            )); 
                        }catch(Exception $e) {  
                            $api_error = $e->getMessage();  
                            return json_encode($api_error);
                        } 
                         
                        if(empty($api_error) && $charge){ 
                            // Retrieve charge details 
                            $chargeJson = $charge->jsonSerialize(); 
                         
                            // Check whether the charge is successful 
                            if( $chargeJson['amount_refunded'] == 0 && 
                                empty($chargeJson['failure_code']) && 
                                $chargeJson['paid'] == 1 && 
                                $chargeJson['captured'] == 1){ 
                                
                                // Transaction details  
                                $transactionID  = $chargeJson['balance_transaction']; 
                                $paidAmount     = $chargeJson['amount']; 
                                $paidAmount     = ($paidAmount/100); 
                                $paidCurrency   = $chargeJson['currency']; 
                                $payment_status = $chargeJson['status']; 
                                 
                                // If the order is successful 
                                if($payment_status == 'succeeded'){ 
                                    $ordStatus = 'success'; 
                                    //$statusMsg = 'Your Payment has been Successful!'; 
                                    $dpfa_estado_pago = 2;
                                    $dpfa_estado_financiero = 'C';
                                }else{ 
                                    $statusMsg = "Your Payment has Failed!"; 
                                } 
                            }else{ 
                                $statusMsg = "Transaction has been failed!"; 
                            } 
                        }else{ 
                            $statusMsg = "Charge creation failed! $api_error";  
                        } 
                    }else{  
                        $statusMsg = "Invalid card details! $api_error";  
                    } 
                }else{ 
                    $statusMsg = "Error on form submission."; 
                }
                if($statusMsg != ''){
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al pagar online: " . $statusMsg),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            }else{
                //Pago por Deposito o Tranferencia
                if ($data["upload_file"]) {
                    if (empty($_FILES)) {
                        echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        return;
                    }//if

                    //Recibe Parametros
                    $files      = $_FILES[key($_FILES)];
                    $arrIm      = explode(".", basename($files['name']));
                    $typeFile   = strtolower($arrIm[count($arrIm) - 1]);
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosfinanciero/" . $data["per_id"] . "/" . $data["name_file"] . "." . $typeFile;
                    $status     = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        return;
                    }//else
                }//if
                $dpfa_estado_pago = 1;
                $dpfa_estado_financiero = 'N';
            }//else

            $mod_pagos        = new PagosFacturaEstudiante();
            $mod_estudiante   = new Especies();           
            
            $arrIm            = explode(".", basename($data["documento"]));
            $typeFile         = strtolower($arrIm[count($arrIm) - 1]);

            if($data["formapago"]==6){
                $imagen = "pago_online";
            }
            else{
                $imagen = $arrIm[0] . "." . $typeFile;
            }

            $pfes_referencia  = $data["referencia"];
            $fpag_id          = $data["formapago"];
            $pfes_valor_pago  = $data["valor"];
            $pfes_fecha_pago  = $data["fechapago"];
            $pfes_observacion = $data["observacion"];
            $est_id           = $data["estid"];
            $pagado           = $data["pagado"];
            $personaData      = $mod_estudiante->consultaDatosEstudiante($per_idsession); //$personaData['per_cedula']
            $con              = \Yii::$app->db_facturacion;
            $transaction      = $con->beginTransaction();

            try {
                $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
                //Utilities::putMessageLogFile('usuario:'. $usuario);
                // consultar en detalle si ya no existe un registro con el mismo              
                $pagadose = explode("*", $pagado); //PAGADOS
                $y = 0;
                foreach ($pagadose as $datose) {
                    //consultar la informacion seleccionada de cuota factura
                    $parametros = explode(";", $pagadose[$y]);                   
                    $resp_consregistro = $mod_pagos->consultarExistepago($est_id, $parametros[0], $parametros[1]);
                    $cuota_pagada .= $parametros[1] . ', ' ;
                    $y++;
                }

                if ($resp_consregistro['registro'] == '0') {

                    $resp_pagofactura = $mod_pagos->insertarPagospendientes($est_id, $pfes_referencia, $fpag_id, $pfes_valor_pago, $pfes_fecha_pago, $pfes_observacion, $imagen, $usuario);
                   //echo (print_r($resp_pagofactura,true)) ;die();
                    if ($resp_pagofactura) {
                        // se graba el detalle
                        $pagados = explode("*", $pagado); //PAGADOS
                        $x = 0;
                        foreach ($pagados as $datos) {
                            //  consultar la informacion seleccionada de cuota factura
                            $parametro = explode(";", $pagados[$x]);
                            //$resp_consfactura = $mod_pagos->consultarPagospendientesp($personaData['per_cedula'], $parametro[0], $parametro[1]);
                            // insertar el detalle  
                            //$resp_detpagofactura = $mod_pagos->insertarDetpagospendientes($resp_pagofactura, $resp_consfactura['tipofactura'], $resp_consfactura['factura'], $resp_consfactura['descripcion'], $parametro[2], $resp_consfactura['fecha'], $resp_consfactura['saldo'], $resp_consfactura['numcuota'], $resp_consfactura['valorcuota'], $resp_consfactura['fechavence'], $usuario);

                            //CODIGO TEMPORAL
                            $mod_ccartera     = new CargaCartera();
                            $resp_consfactura = $mod_ccartera->consultarPagospendientesp($personaData['per_cedula'], $parametro[0], $parametro[1]);

                            //Solo si es stripe
                            if($data["formapago"]==6){
                                $cargo = CargaCartera::findOne($resp_consfactura['ccar_id']);
                                $cargo->ccar_estado_cancela = 'C';
                                $cargo->ccar_fecha_modificacion = $fecha;
                                $cargo->ccar_usu_modifica = $usuario;
                                $cargo->save();
                            }

                            // insertar el detalle    
                            $descripciondet = 'Cuota '. $resp_consfactura['cuota'] . 'con el valor de ' .$resp_consfactura['ccar_valor_cuota'];
                            $resp_detpagofactura = $mod_pagos->insertarDetpagospendientes($resp_pagofactura, $resp_consfactura['ccar_tipo_documento'], $resp_consfactura['NUM_NOF'], $descripciondet     , $parametro[2], $resp_consfactura['F_SUS_D'], is_null($resp_consfactura['SALDO'])?0:round($resp_consfactura['SALDO'], 2), $resp_consfactura['cuota'], $resp_consfactura['ccar_valor_cuota'], $resp_consfactura['F_VEN_D'], $dpfa_estado_pago, $dpfa_estado_financiero, $usuario);
                            $x++;
                        }
                        
                        if ($resp_detpagofactura) {
                            $transaction->commit();
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        }//if
                    }else{
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura 1." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }
                }else{
                    Utilities::putMessageLogFile('dentro else:'. $cuota_pagada);
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La cuota " . $cuota_pagada . "  ya esta cargado el pago, espere porfavor su revisión"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }//else
            }catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura 2." . $ex),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }//if
    }//function actionSavepagopendiente

    public function actionExpexcelfacpendiente() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "L");
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Financial Status"),
            financiero::t("Pagos", "Payment id"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];
        $arrSearch["estadofinanciero"] = $data['estadofinanciero'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagos(array(), true);
        } else {
            $arrData = $mod_pagos->getPagos($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "List Payment Pending");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdffacpendiente() {
        $report = new ExportFile();
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),
            financiero::t("Pagos", "Financial Status"),
            financiero::t("Pagos", "Payment id"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];
        $arrSearch["estadofinanciero"] = $data['estadofinanciero'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagos(array(), true);
        } else {
            $arrData = $mod_pagos->getPagos($arrSearch, true);
        }

        $this->view->title = financiero::t("Pagos", "List Payment Pending");
        ; // Titulo del reporte                
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

    public function actionUpdatepago() {        
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $especiesADO = new Especies();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        $modcanal = new Oportunidad();
        $mod_fpago = new FormaPago();
        $mod_pagos = new PagosFacturaEstudiante();
        $personaData = $especiesADO->consultaDatosEstudiante($per_idsession);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($personaData['uaca_id'], 1);
        if (($personaData['uaca_id'] == 1) or ( $personaData['uaca_id'] == 2)) {
            $carrera = $modcanal->consultarCarreraModalidad($personaData['uaca_id'], $personaData['mod_id']);
        } else {
            $carrera = $modestudio->consultarCursoModalidad($personaData['uaca_id'], $personaData['mod_id']); // tomar id de impresa
        }
        $arr_forma_pago = $mod_fpago->consultarFormaPagosaldo();
        // se debe capturar desde url borrar al hacer las pruebas       
        $pagodetalle = $mod_pagos->consultarPago($dpfa_id);
        return $this->render('updatepago', [
                    'arr_persona' => $personaData,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_carrera' => ArrayHelper::map($carrera, "id", "name"),
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    'pagodetalle' => $pagodetalle,
        ]);
    }

    public function actionModificarpagopendiente() {
        //online que sube doc capturar asi el id de la persona 
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Parametros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosfinanciero/" . $data["per_id"] . "/" . $data["name_file"] . "." . $typeFile;
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
            $pfes_referencia = $data["referencia"];
            $fpag_id = $data["formapago"];
            $pfes_valor_pago = $data["valor"];
            $pfes_fecha_pago = $data["fechapago"];
            $pfes_observacion = $data["observacion"];
            $est_id = $data["estid"];
            $pfes_id = $data["pfes_id"];
            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            try {
                //$usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
                //Utilities::putMessageLogFile('usuario:'. $usuario);
                $resp_pagofactura = $mod_pagos->modificarPagosrechazado($pfes_id, $est_id, $pfes_referencia, $fpag_id, $pfes_valor_pago, $pfes_fecha_pago, $pfes_observacion, $imagen);
                if ($resp_pagofactura) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido modificada. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al modificar pago factura1." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al modificar pago factura2." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionConsultarevision() {
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $model = $mod_pagos->consultarPago($dpfa_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('viewrevisionpago', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoRechazo(),
                    'arrObservacion' => array("0" => "Seleccione", "Archivo Ilegible" => "Archivo Ilegible", "Archivo no corresponde al pago" => "Archivo no corresponde al pago", "Archivo con Error" => "Archivo con Error", "Valor pagado no cubre factura" => "Valor pagado no cubre factura", "Archivo duplicado" => "Archivo duplicado"),
        ]);
    }
    
    public function actionPagos() {
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $mod_pagos = new PagosFacturaEstudiante();        
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {            
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];            
            $resp_pago = $mod_pagos->getPagosxestudiante($arrSearch, false, $per_idsession);
            return $this->renderPartial('_index-grid_pagosfacturas', [
                        "model" => $resp_pago,
            ]);
        }
        $model = $mod_pagos->getPagosxestudiante(null, false, $per_idsession);        
        return $this->render('index_pagosfacturas', [
                    'model' => $model,                   
        ]);
    }
    
    public function actionDetallepagosfactura() {
        $factura = base64_decode($_GET["pfes_id"]);
        $per_id = @Yii::$app->session->get("PB_perid");
        $mod_pagos = new PagosFacturaEstudiante();        
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }
        $data = Yii::$app->request->get();        
        $dataEstudiante = $mod_pagos->consultarDatosestudiante($per_id);
        $model = $mod_pagos->getPagosDetxestudiante(null, false, $factura);   

        return $this->render('index_pagos', [
                    'model' => $model,     
                    'data' => $dataEstudiante,
        ]);
    }

    public function actionPagosfactura() {
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modestudio = new ModuloEstudio();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                if (($data["unidada"] == 1) or ( $data["unidada"] == 2)) {
                    $modalidad = $mod_modalidad->consultarModalidad($data["unidada"], 1);
                } else {
                    $modalidad = $modestudio->consultarModalidadModestudio();
                }
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["estadopago"] = $data['estadopago'];         
            $resp_pago = $mod_pagos->getPagosestudiante($arrSearch, false);
            return $this->renderPartial('_index-grid_pagofactura', [
                        "model" => $resp_pago,
            ]);
        }
        $model = $mod_pagos->getPagosestudiante(null, false);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('index_pagofactura', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arr_estado' => $this->estados(),
                    'arr_estado_financiero' => $this->estadoFinanciero(),
        ]);
    }

    public function actionExpexcelpagfactura() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "L");
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),           
            financiero::t("Pagos", "Payment id"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];        

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagosestudiante(array(), true);
        } else {
            $arrData = $mod_pagos->getPagosestudiante($arrSearch, true);
        }
        $nameReport = financiero::t("Pagos", "List Payment") . ' ' . financiero::t("Pagos", "Bill");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfpagfactura() {
        $report = new ExportFile();
        $arrHeader = array(
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Student"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Paid form"),
            financiero::t("Pagos", "Amount Paid"),
            financiero::t("Pagos", "Monthly fee"),
            financiero::t("Pagos", "Bill"),
            Yii::t("formulario", "Registration Date"),
            Yii::t("formulario", "Review Status"),           
            financiero::t("Pagos", "Payment id"),
        );
        $mod_pagos = new PagosFacturaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["estadopago"] = $data['estadopago'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_pagos->getPagosestudiante(array(), true);
        } else {
            $arrData = $mod_pagos->getPagosestudiante($arrSearch, true);
        }

        $this->view->title = financiero::t("Pagos", "List Payment") . ' ' . financiero::t("Pagos", "Bill");
        ; // Titulo del reporte                
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

    public function actionReversar() {
        $dpfa_id = base64_decode($_GET["dpfa_id"]);
        $mod_pagos = new PagosFacturaEstudiante();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();

        $model = $mod_pagos->consultarPago($dpfa_id);
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidadac[0]["id"], 1);
        return $this->render('reversar', [
                    'model' => $model,
                    'arr_unidad' => ArrayHelper::map($arr_unidadac, "id", "name"),
                    'arr_modalidad' => ArrayHelper::map($arr_modalidad, "id", "name"),
                    'arrEstados' => $this->estadoReverso(),                    
        ]);
    }

    public function actionCargarcartera() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        //$usu_id = Yii::$app->session->get('PB_iduser');
        $mod_cartera = new CargaCartera();
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
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "cartera/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                try {
                ini_set('memory_limit', '256M');
                \app\models\Utilities::putMessageLogFile('Files ...: ' . $data["archivo"]);
                $carga_archivo = $mod_cartera->CargarArchivocartera($data["archivo"]);
                if ($carga_archivo['status']) {
                    \app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $arroout['noalumno']);
                    if (!empty($carga_archivo['noalumno'])){                        
                    $noalumno = ' Se encontró las cédulas '. $carga_archivo['noalumno'] . ' que no pertencen a estudiantes por ende no se cargaron. ';
                    }
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data'] .  $noalumno),
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
            } catch (Exception $ex) {      
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Error al procesar el archivo.'),
                    'title' => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), true, $message);
            }
        }
        } else {
            return $this->render('cargarcartera', []);
        }
    }
    public function actionDownloadplantilla() {
        $file = 'plantillaCargacartera.xlsx';
                $route = str_replace("../", "", $file);
                $url_file = Yii::$app->basePath . "/uploads/cartera/" . $route;
                $arrfile = explode(".", $url_file);
                $typeImage = $arrfile[count($arrfile) - 1];
                if (file_exists($url_file)) {
                    if (strtolower($typeImage) == "xlsx") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/xlsx");
                        header('Content-Disposition: attachment; filename="plantillacartera_' . time() . '.xlsx";');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_file));
                        readfile($url_file);
                    }
                }
    }

    public function actionReversarestado() {        
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mod_pagos = new PagosFacturaEstudiante();
            $dfac_id = $data["dpfa_id"];
            $estado_pago = $data["resultado"];     
            $observacion = ucfirst(mb_strtolower($data['observacion'], 'UTF-8'));       
            $estado_financiero = null;
            $con = \Yii::$app->db_facturacion;
            $transaction = $con->beginTransaction();
            try {
                $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
                // enviar a modificar estados
                $resp_pagofactura = $mod_pagos->grabaReverso($dfac_id, $estado_pago, $observacion);
               if ($resp_pagofactura) { 
                    $fecha = date(Yii::$app->params["dateTimeByDefault"]);
                    $cartera = $mod_pagos->buscarIdCartera($dfac_id);
                    $id_cartera = $cartera[0]['ccar_id'];
                    $cargo = CargaCartera::findOne($id_cartera);
                    $cargo->ccar_estado_cancela = 'N';
                    $cargo->ccar_fecha_modificacion = $fecha;
                    $cargo->ccar_usu_modifica = $usuario;
                    $cargo->save(); 
                    
                if ($cargo) {
                    $datos = $mod_pagos->consultarPago($dfac_id);
                    // \app\models\Utilities::putMessageLogFile('obsere: ' . $datos['dpfa_observacion_reverso']);
                    $correo_estudiante = $datos['per_correo'];
                    $tituloMensaje = 'Pagos en Línea';
                    $asunto = 'Pagos en Línea';
                    $body = Utilities::getMailMessage("pagoreversado", array(
                    "[[user]]" =>  $datos['estudiante'],   
                    "[[factura]]" => $datos['dpfa_factura'],
                    "[[cuota]]" => $datos['dpfa_num_cuota'],
                    "[[valor]]" => $datos['valor_cuota'],
                    "[[observacion]]" => $observacion,
                    ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo_estudiante => $user], $asunto, $body);
                } 
                    $transaction->commit();
                    $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                    echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al reversar pago factura." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }          
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al reversar pago factura." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }
}
