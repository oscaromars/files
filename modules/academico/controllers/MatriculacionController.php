<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\admision\models\SolicitudInscripcion;
use app\modules\academico\models\Matriculacion;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\models\ExportFile;
use app\models\Usuario;
use app\models\Persona;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\Planificacion;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\RegistroOnline;
use app\modules\academico\models\RegistroOnlineCuota;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\academico\models\RegistroOnlineItem;
use app\modules\academico\models\RegistroPagoMatricula;
use app\modules\financiero\models\Secuencias;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\modules\academico\Module as Academico;
use app\modules\financiero\Module as financiero;
use app\modules\financiero\models\PagosFacturaEstudiante;
use app\modules\academico\models\Especies;

use app\modules\financiero\models\FormaPago;

Academico::registerTranslations();
financiero::registerTranslations();


class MatriculacionController extends \app\components\CController {

    public $leyenda = '
          <div class="form-group">          
          <div class="alert alert-info">
          <table class="tg">
            <tr>
              <td colspan="2" class="tg-0pky"><span style="font-weight: bold"> Nota: </span>Estimado Estudiante, si tiene alguna observación con la 
              planificación del periodo académico por favor contactar a la secretaría de su facultad, a los siguientes números:</br></br></td>
            </tr>
            <tr>
                <td class="tg-0pky"><span style="font-weight: bold">Datos de Contacto</span></br></br></td>
            </tr>
            <tr>
              <td class="tg-0pky"><span style="font-weight: bold">Facultad Grado Presencial</span></br>
                Correo: secretariapresencial@uteg.edu.ec</br>
                Celular: 0993817458</br></br></td>
              <td class="tg-0pky"><span style="font-weight: bold">Facultad Grado a Distancia y Semipresencial</span></br>
                Correo: secretariasemipresencial@uteg.edu.ec</br>
                Celular: 09895899757</br></br></td>
            </tr>
            <tr>
              <td class="tg-0pky"> <span style="font-weight: bold">Facultad Grado Online</span></br>
                Correo: secretariaonline@uteg.edu.ec</br>
                Celular: 0991534808</br></br></td>
              <td class="tg-0pky"><span style="font-weight: bold">Mesa de Servicio UTEG</span></br>
                Correo: mesaservicio01e@uteg.edu.ec y mesaservicio02@uteg.edu.ec</br></br></td>
            </tr>
          </table>
          </div>     
          </div>';


    public function actionNewhomologacion() {
        return $this->render('newHomologacion', [
        ]);
    }

    public function actionNewmetodoingreso() {
        $sins_id = base64_decode($_GET['sids']);
        $mod_solins = new SolicitudInscripcion();
        $mod_matriculacion = new Matriculacion();
        $pmin_id = 0;
        
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getparalelos"])) {                                
               // \app\models\Utilities::putMessageLogFile('pmin_id ajax: ' . $data["pmin_id"]);
                $resp_Paralelos = $mod_matriculacion->consultarParalelo($data["pmin_id"]);
                $message = array("paralelos" => $resp_Paralelos);               
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);               
            }           
        }               
        $personaData = $mod_solins->consultarInteresadoPorSol_id($sins_id);
        $resp_Periodos = $mod_matriculacion->consultarPeriodoAcadMing($personaData["uaca_id"], $personaData["mod_id"], $personaData["ming_id"]);
        $arr_Paralelos = $mod_matriculacion->consultarParalelo($pmin_id);
        return $this->render('newmetodoingreso', [
                    'personalData' => $personaData,            
                    'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Seleccionar"]],$resp_Periodos),"id","name"), //ArrayHelper::map($resp_Periodos, "id", "name"),
                    'arr_paralelo' => ArrayHelper::map(array_merge(["id" => "0", "name" => "Seleccionar"],$arr_Paralelos),"id","name"), //ArrayHelper::map($arr_Paralelos, "id", "name"),
        ]);
    }


    public function actionSave() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $par_id = $data["par_id"];
            $periodo_id = $data["per_id"];
            $adm_id = base64_decode($data["adm_id"]);
            $sins_id = base64_decode($data["sins_id"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if (($periodo_id!=0) and ($par_id!=0)) {   
                    //Verificar que no tenga una matrícula.
                    $mod_Matriculacion = new Matriculacion();
                    $resp_consMatricula = $mod_Matriculacion->consultarMatriculaxId($adm_id, $sins_id);
                    if (!$resp_consMatricula) {
                        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
                        $descripcion = "Asignación por Matrícula Método Ingreso.";   
                        //Buscar el código de planificación académica según el periodo, unidad, modalidad y carrera.
                        $resp_planificacion = $mod_Matriculacion->consultarPlanificacion($sins_id, $periodo_id);
                        if ($resp_planificacion) { //Si existe código de planificación
                            $resp_matriculacion = $mod_Matriculacion->insertarMatriculacion($resp_planificacion["peac_id"], $adm_id, null, $sins_id, $fecha, $usu_id);
                            if ($resp_matriculacion) {
                                $resp_Asigna = $mod_Matriculacion->insertarAsignacionxMeting($par_id, $resp_matriculacion, null, $descripcion, $fecha, $usu_id);
                                if ($resp_Asigna) {
                                    $exito = '1';
                                }
                            }
                        }  else {
                            $mensaje = "¡No existe código de planificación académica para los datos proporcionados.!";
                        }                      
                    } else {
                        $mensaje = "¡Ya existe matrícula.!";
                    }                   
                } else {
                    $mensaje = "¡Seleccione Período Académico y Paralelo.!";
                }
                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La información ha sido grabada."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    //$paso = 1;
                    $transaction->rollback();
                    if (empty($message)) {
                        $message = array
                            (
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar. " . $mensaje), "title" =>
                            Yii::t('jslang', 'Success'),
                        );
                    }
                    return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }

    /***********************************************  funciones nuevas matriculacion  *****************************************/

    /**
     * Function controller to /matriculacion/index
     * @author Emilio Moran <emiliojmp9@gmail.com>
     * @param
     * @return
     */
    public function actionIndex() {
        $per_id = Yii::$app->session->get("PB_perid");

        if ($per_id < 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                return $this->render('index-out', [
                    "message" => Academico::t("matriculacion", "There is no planning information (Registration time)"),
                ]);
            }
        }

        $mod_est      = new Estudiante();
        $modModalidad = new Modalidad();
        
        $matriculacion_model = new Matriculacion();
        $today = date("Y-m-d H:i:s");
        $result_process = $matriculacion_model->checkToday($today, $per_id);

        $usu_id = Yii::$app->session->get("PB_iduser");
        $mod_usuario = Usuario::findIdentity($usu_id);
        if ($mod_usuario->usu_upreg == 0) {
            if (Yii::$app->session->get("PB_perid") < 1000) {
                return $this->redirect(['perfil/index', 'per_id' => base64_encode($per_id)]);
            }
            return $this->redirect(['perfil/index']);
        }

        if (count($result_process) > 0) {
            /*             * Exist a register process */
            $rco_id = $result_process[0]['rco_id'];
            $rco_num_bloques = $result_process[0]['rco_num_bloques'];
            $pla_id = $result_process[0]['pla_id'];

            /*             * Verificando si el estudiante ha pagado */
            $result_pago = RegistroPagoMatricula::checkPagoEstudiante($per_id, $pla_id);

            if (count($result_pago) > 0) {
                $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
                if (count($resultIdPlanificacionEstudiante) > 0) {
                    /*                     * Exist a register of planificacion_estudiante */
                    $pes_id = $resultIdPlanificacionEstudiante[0]['pes_id'];
                    $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                    $resultRegistroOnline = $matriculacion_model->checkPlanificacionEstudianteRegisterConfiguracion($per_id, $pes_id, $pla_id);
                    $modelPlaEst = PlanificacionEstudiante::findOne($pes_id);
                    $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                    if (count($resultRegistroOnline) > 0) {
                        //Cuando existe un registro en registro_online
                        return $this->redirect(['matriculacion/registro', 'per_id' => Yii::$app->request->get('per_id', base64_encode(Yii::$app->session->get("PB_perid")))]);
                    } else {
                        //Cuando no existe registro en registro_online, eso quiere decir que no ha seleccionado las materias aun y registrado

                        /* $con1 = \Yii::$app->db_facturacion; */

                        /* Secuencias::initSecuencia($con1, 1, 1, 1, "RAC", "PAGO REGISTRO ONLINE"); */
                        /*                         * No exist register into registro_online, so with need saved the data into register_online */
                        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
                        $dataPlanificacion = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id, $rco_num_bloques);
                        $num_min = 0;
                        $num_max = 10;
                        if (count($dataPlanificacion) <= 4) {
                            $num_min = count($dataPlanificacion);
                            $num_max = count($dataPlanificacion);
                        } else {
                            $num_min = 4;
                        }

                        $dataProvider = new ArrayDataProvider([
                            'key' => 'Ids',
                            'allModels' => $dataPlanificacion,
                            'pagination' => [
                                'pageSize' => Yii::$app->params["pageSize"],
                            ],
                            'sort' => [
                                'attributes' => ["Subject"],
                            ],
                        ]);
                        $dataCat = ArrayHelper::map($mod_est->getCategoryCost(), "Cod", "Precio");
                        $modCode = $modModalidad->getCodeCCostoxModalidad($modelPla->mod_id);
                        $dataMat = ArrayHelper::map($mod_est->getGastosMatriculaOtros($modCode['Cod']), "Cod", "Precio");
                        $CatPrecio = $dataCat[$data_student['est_categoria']];

                        return $this->render('index', [
                                    "planificacion" => $dataProvider,
                                    "data_student" => $data_student,
                                    "num_min" => $num_min,
                                    "num_max" => $num_max,
                                    "pes_id" => $pes_id,
                                    "dataCat" => $dataCat,
                                    "CatPrecio" => $CatPrecio,
                                    "dataMat" => $dataMat,
                                    "leyenda" => $this->leyenda,
                                    "per_id" => $per_id,
                                    "loginPer" => Yii::$app->session->get("PB_perid"),
                        ]);
                    }
                } else {
                    return $this->render('index-out', [
                                "message" => Academico::t("matriculacion", "There is no planning information (Registration time)"),
                    ]);
                }
            } else {
                return $this->redirect(['matriculacion/registropago', 'per_id' => Yii::$app->request->get('per_id', base64_encode(Yii::$app->session->get("PB_perid")))]);
            }
        } else {
            return $this->redirect(['matriculacion/registro', 'per_id' => Yii::$app->request->get('per_id', base64_encode(Yii::$app->session->get("PB_perid")))]);
        }
    }

    public function actionRegistropago() { // SUBE PAGA DE MATRICULA
        $usu_id      = Yii::$app->session->get("PB_iduser");
        $mod_usuario = Usuario::findIdentity($usu_id);
        $per_id      = Yii::$app->session->get("PB_perid");

        $mod_estudiante  = new Especies();   
        $datosEstudiante = $mod_estudiante->consultaDatosEstudiante($per_id); 

        $est_id = $datosEstudiante['est_id'];
        $mod_id = $datosEstudiante['mod_id'];

        $data = Yii::$app->request->post();

        $mod_persona = Persona::findOne($per_id);//Persona::find()->select("per_correo")->where(["per_id" => $per_id])->asArray()->all();
        \app\models\Utilities::putMessageLogFile(print_r($mod_persona,true));

        $email       = $mod_persona->per_correo; //$mod_persona[0]['per_correo'];  
        $nombres     = $mod_persona->per_pri_nombre." ". $mod_persona->per_pri_apellido ." ". $mod_persona->per_seg_apellido;
        $cedula      = $mod_persona->per_cedula;
        
        \app\models\Utilities::putMessageLogFile("email: ".$email);
        \app\models\Utilities::putMessageLogFile("nombres: ".$nombres);

        if ($per_id < 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id != 0){
                $mod_usuario = Usuario::findOne(['per_id' => $per_id]);
                $usu_id = $mod_usuario->usu_id;
            }else{
                $per_id = Yii::$app->session->get("PB_perid");
            }
        }

        if ($mod_usuario->usu_upreg == 0) {
            if (Yii::$app->session->get("PB_perid") < 1000) {
                return $this->redirect(['perfil/index', 'per_id' => base64_encode($per_id)]);
            }
            return $this->redirect(['perfil/index']);
        }
        //Este codigo es para la carga del archivo
        if ($data["upload_file"]) {
            if (empty($_FILES)){
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
            }
            
            //Recibe Parámetros
            $files      = $_FILES[key($_FILES)];
            $arrIm      = explode(".", basename($files['name']));
            $typeFile   = strtolower($arrIm[count($arrIm) - 1]);
            $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosfinanciero/" . $data["name_file"] . "." . $typeFile;
            $status     = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);

            if ($status) 
                return true;
            else
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
        }//if

        if (Yii::$app->request->isAjax) {
            try {
            $per_id = $data['per_id'];

            if ($data["procesar_pago"]) {

                $result_pago = RegistroPagoMatricula::checkPagoEstudiante($per_id, $data['pla_id']);
                if (count($result_pago) > 0) {
                    
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Usted ya registro el pago para este periodo."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                }//if

                ini_set('memory_limit', '256M');

                if($data["formapago"]==1){
                    //Si la forma de Pago es 1 significa que es por Tarjeta de credito
                    $statusMsg = '';

                    //Este token es enviada por la libreria de javascript de stripe
                    if(!empty($data['token'])){
                        //Se hace invocacion a libreria de stripe que se encuentra en el vendor
                        \Stripe\Stripe::setApiKey(Yii::$app->params["secret_key"]);
                        
                        //Obtenemos el token y tambien el nombre de la persona que esta cancelando
                        $token  = $data['token']; 

                        //Se crea el usuario para stripe
                        try {  
                            $customer = \Stripe\Customer::create(array( 
                                'email'   => $email, 
                                'source'  => $token 
                            )); 
                        }catch(Exception $e) {  
                            $api_error = $e->getMessage();  
                            return json_encode($api_error);
                        } 
                         
                        //Si se creo el usuario y no hay error 
                        if(empty($api_error) && $customer){  
                            //El valor se multiplica por 100 para convertirlo a centavos
                            $valor_inscripcion = $data['valor']; 
                            $itemPriceCents    = ($valor_inscripcion*100); 
                             
                            //Se crea el cobro
                            try {  
                                $charge = \Stripe\Charge::create(array( 
                                    'customer'    => $customer->id, 
                                    'amount'      => $itemPriceCents, 
                                    'currency'    => "usd", 
                                    'description' => "Pago de Matricula"
                                )); 
                            }catch(Exception $e) {  
                                $api_error = $e->getMessage();  
                                return json_encode($api_error);
                            } 
                             
                            //Si se creo el combo y no hubo error se devuelve el resultado de la transaccion
                            if(empty($api_error) && $charge){ 
                                //Cargamos los datos
                                $chargeJson = $charge->jsonSerialize(); 
                             
                                // Check whether the charge is successful 
                                if( $chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code'])  && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){                                
                                    // Transaction details  
                                    $transactionID  = $chargeJson['balance_transaction']; 
                                    $paidAmount     = $chargeJson['amount']; 
                                    $paidAmount     = ($paidAmount/100); 
                                    $paidCurrency   = $chargeJson['currency']; 
                                    $payment_status = $chargeJson['status']; 
                                     
                                    // Si el pago fue correcto
                                    if($payment_status == 'succeeded'){ 
                                        $ordStatus = 'success'; 

                                        //Estas variables es para indicar que como fue con tarjeta de una vez 
                                        //se actualize y ya no salgan como pendientes
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
                    $mod_fpago     = new FormaPago();
                    $arr_refBancos = $mod_fpago->consultarReferenciaBancos($data["referencia"],$data["banco"]);  

                    if(!empty($arr_refBancos)) {
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La refrencia ya existe para el banco seleccionado."),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                        return;
                    }

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

                    //Como es un pago por deposito o transferencia sus estados son pendiente
                    $dpfa_estado_pago       = 1;
                    $dpfa_estado_financiero = 'N';
                }//fin del else

                $mod_pagos      = new PagosFacturaEstudiante();
                  
            
                //En caso de ser pago por tarjeta entra por if o entra en else si es deposito o transferencia
                if($data["formapago"]==1){
                    $imagen   = "pago_online";
                }else{
                    $arrIm    = explode(".", basename($data["documento"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $imagen   = $arrIm[0] . "." . $typeFile;
                }
                //Obtenemos la informacion enviada por javascript
                $pfes_referencia  = $data["referencia"];
                $pfes_banco       = $data["banco"];
                $fpag_id          = $data["formapago"];
                $pfes_valor_pago  = $data["valor"];
                $pfes_fecha_pago  = $data["fechapago"]==''? date(Yii::$app->params['dateTimeByDefault']) :$data["fechapago"];
                $pfes_observacion = $data["observacion"];
                //$est_id           = $data["estid"];
                //$pagado           = $data["pagado"];
                $personaData      = $mod_estudiante->consultaDatosEstudiante($per_idsession); //$personaData['per_cedula']
                $con              = \Yii::$app->db_facturacion;
                $transaction      = $con->beginTransaction();

                //Variable creada para el bucle de abono de pago
                $valor_pagado     = $data["valor"];

                $resp_pagofactura = $mod_pagos->insertarPagospendientes($est_id, 
                                                                        'MA', 
                                                                        $pfes_referencia, 
                                                                        $pfes_banco, 
                                                                        $fpag_id, 
                                                                        $pfes_valor_pago, 
                                                                        $pfes_fecha_pago, 
                                                                        $pfes_observacion, 
                                                                        $imagen, 
                                                                        $usu_id);
                $pfes_id = $resp_pagofactura;

                if ($resp_pagofactura) {
                    // insertar el detalle
                    $descripciondet      = 'Pago de Matricula con el Valor de '. $valor_pagado;

                    $resp_detpagofactura = $mod_pagos->insertarDetpagospendientes($pfes_id, 
                                                                                  1,//$resp_consfactura['ccar_tipo_documento'], 
                                                                                  1,//$resp_consfactura['NUM_NOF'], 
                                                                                  $descripciondet, 
                                                                                  $valor_pagado,//$parametro[2], 
                                                                                  $pfes_fecha_pago,//$resp_consfactura['F_SUS_D'], 
                                                                                  0, 
                                                                                  1, 
                                                                                  //$resp_consfactura['ccar_valor_cuota'], 
                                                                                  $valor_pagado,//$cargo->ccar_abono,
                                                                                  $pfes_fecha_pago,//$resp_consfactura['F_VEN_D'], 
                                                                                  $dpfa_estado_pago, 
                                                                                  $dpfa_estado_financiero, 
                                                                                  $usu_id);
                    

                    if ($resp_detpagofactura) {
                        $transaction->commit();

                        $tituloMensaje = Yii::t("Matricula", "Pago Recibido UTEG");
                        $asunto        = Yii::t("Matricula", "Pago Recibido UTEG");

                        

                        $bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula ), Yii::$app->language);

                        if($mod_id == 1){ //online
                            $telefono = Yii::$app->params["tlfonline"];
                            $bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula, "[[telefono]]" => $telefono ), Yii::$app->language);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanatoonline"] => "Decanato Online"], $asunto, $bodypmatricula);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariaonline1"] => "Secretaria Online"], $asunto, $bodypmatricula);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariaonline2"] => "Secretaria Online"], $asunto, $bodypmatricula);
                        }else if($mod_id == 2){//presencial
                            $telefono = Yii::$app->params["tlfpresencial"];
                            $bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula, "[[telefono]]" => $telefono ), Yii::$app->language);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanogradopresencial"] => "Decanato Presencial"], $asunto, $bodypmatricula);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariagrado1"] => "Secretaria Presencial"], $asunto, $bodypmatricula);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariagrado2"] => "Secretaria Presencial"], $asunto, $bodypmatricula);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["coordinadorgrado"] => "Coordinador Presencial"], $asunto, $bodypmatricula);
                        }else{
                            $telefono = Yii::$app->params["tlfdistancia"];
                            $bodypmatricula = Utilities::getMailMessage("pagoMatriculaDecano", array("[[user]]" => $nombres, "[[cedula]]" => $cedula, "[[telefono]]" => $telefono ), Yii::$app->language);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["decanogradosemi"] => "Decanato SemiPresencial"], $asunto, $bodypmatricula);
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"], [Yii::$app->params["secretariasemi"]  => "Secretaria SemiPresencial"], $asunto, $bodypmatricula);
                        }

                        if($fpag_id == 1){
                            $body = Utilities::getMailMessage("pagostripe", array("[[user]]" => $nombres, "[[telefono]]" => $telefono  ), Yii::$app->language);    
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["contactoEmail"], [$email => $nombres], $asunto, $body);
                        }else{
                            $body = Utilities::getMailMessage("pago", array("[[user]]" => $nombres, "[[telefono]]" => $telefono ), Yii::$app->language, Yii::$app->basePath . "/modules/financiero");
                            Utilities::sendEmail($tituloMensaje, Yii::$app->params["contactoEmail"], [$email => $nombres], $asunto, $body);
                        }

                        $bodycolec = Utilities::getMailMessage("pagoMatricula", array("[[user]]" => $nombres, "[[cedula]]" => $cedula ), Yii::$app->language);
                       
                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["colecturia"]     , [Yii::$app->params["supercolecturia"] => "Colecturia"], $asunto, $bodycolec);
                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["supercolecturia"], [Yii::$app->params["colecturia"]      => "Supervisor Colecturia"], $asunto, $bodycolec);
                    }else{
                        $transaction->rollback();

                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                    }//else
                }else{
                    $transaction->rollback();

                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Error al registrar el pago'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
                
                try {
                    /*
                    $result_pago = RegistroPagoMatricula::checkPagoEstudiante($per_id, $data['pla_id']);
                    if (count($result_pago) > 0) {
                        $model_registro_pago_matricula = RegistroPagoMatricula::findOne(["rpm_id" => $result_pago[0]["rpm_id"]]);
                        $model_registro_pago_matricula->rpm_archivo = "pagosmatricula/" . $data["archivo"];
                        $model_registro_pago_matricula->rpm_estado_aprobacion = "0";
                        if ($model_registro_pago_matricula->save()) {
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                        } else {
                            $message = array(
                                "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                                "title" => Yii::t('jslang', 'Error'),
                            );
                            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                        }
                    } else {
                        */
                        $model_registro_pago_matricula = new RegistroPagoMatricula();
                        $model_registro_pago_matricula->per_id = $per_id;
                        $model_registro_pago_matricula->pla_id = $data['pla_id'];
                        // $model_registro_pago_matricula->pes_id = $data['pes_id'];
                        $model_registro_pago_matricula->rpm_archivo = "pagosmatricula/" . $data["archivo"];
                        $model_registro_pago_matricula->rpm_estado_aprobacion = "0";
                        $model_registro_pago_matricula->rpm_estado_generado   = "0";
                        $model_registro_pago_matricula->rpm_estado            = "1";
                        $model_registro_pago_matricula->rpm_estado_logico     = "1";

                        if ($model_registro_pago_matricula->save()) {
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                        } else {
                            $message = array(
                                "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                                "title" => Yii::t('jslang', 'Error'),
                            );
                            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                        }
                    //}
                } catch (Exception $ex) {
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
                
            }
        
        }catch (Exception $ex) {
            $transaction->rollback();
            $message = array(
                "wtmessage" => Yii::t("notificaciones", "Error al grabar pago factura." . $ex),
                "title" => Yii::t('jslang', 'Error'),
            );
            echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
        }
        }
        //else {          
            //$matriculacion_model = new Matriculacion();
            //$today = date("Y-m-d H:i:s");
            //$result_process = $matriculacion_model->checkToday($today, $per_id);

            //if (count($result_process) > 0) {                
                /* Exist a register process */
                //$pla_id = $result_process[0]['pla_id'];

                /* Verificando si el estudiante ha pagado */
                //$data_planificacion_pago = Matriculacion::getPlanificacionPago($pla_id);
                /* Se obtiene los datos de planificación del estudiante GVG */ 

        $data_planificacion_pago = Matriculacion::getPlanificacionPago($mod_id);  

        $mod_fpago = new FormaPago();
        $arr_forma_pago = $mod_fpago->consultarFormaPagosaldo();
        $arr_bancos = $mod_fpago->consultarBancos();

        return $this->render('carga-pago', [
                    "data_planificacion_pago" => $data_planificacion_pago,
                    "pla_id" => $data_planificacion_pago['pla_id'],
                    "per_id" => $per_id,
                    "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                    "arr_bancos" => ArrayHelper::map($arr_bancos, "id", "value"),
        ]);
    }//function actionRegistropago

    public function actionList() {
        $model = new RegistroPagoMatricula();
        $arr_status = [-1 => Academico::t("matriculacion", "-- Select Status --"), 0 => Academico::t("matriculacion", "Unregistered Student"), 1 => Academico::t("matriculacion", "Registered Student")];

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            return $this->renderPartial('list-grid', [
                        "model" => $model->getAllRegistroMatriculaxGenerarGrid($data["search"], $data["mod_id"], $data["estado"], $data["carrera"], $data["periodo"], true),
            ]);
        }

        $arr_carrera = PlanificacionEstudiante::getCarreras();
        $arr_pla_per = Planificacion::getPeriodosAcademico();

        $arr_modalidad = Planificacion::find()
                ->select(['m.mod_id', 'm.mod_nombre'])
                ->join('inner join', 'modalidad m')
                ->where('pla_estado_logico = 1 and pla_estado = 1 and m.mod_estado =1 and m.mod_estado_logico = 1')
                ->asArray()
                ->all();

        return $this->render('list', [
                    'model' => $model->getAllRegistroMatriculaxGenerarGrid(NULL, NULL, NULL, NULL, NULL, true),
                    'arr_carrera' => array_merge([0 => Academico::t("matriculacion", "-- Select Career --")], ArrayHelper::map($arr_carrera, "pes_id", "pes_carrera")),
                    'arr_pla_per' => array_merge([0 => Academico::t("matriculacion", "-- Select Academic Period --")], ArrayHelper::map($arr_pla_per, "pla_id", "pla_periodo_academico")),
                    'arr_modalidad' => array_merge([0 => Academico::t("matriculacion", "-- Select Modality --")], ArrayHelper::map($arr_modalidad, "mod_id", "mod_nombre")),
                    'arr_status' => $arr_status,
        ]);
    }

    public function actionRegistry($id) {
        $model = RegistroOnline::findOne($id);
        if ($model) {
            $matriculacion_model = new Matriculacion();
            $model_registroPago = new RegistroPagoMatricula();
            $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($model->per_id, $model->pes_id);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($id);
            $materiasxEstudiante = PlanificacionEstudiante::findOne($model->pes_id);
            $dataModel = $model_registroPago->getRegistroPagoMatriculaByRegistroOnline($id, $model->per_id);

            $arrModel_registroOnlineItem = RegistroOnlineItem::findAll(['ron_id' => $model->ron_id]);
            $model_registroCuota = new RegistroOnlineCuota();
            $costoMaterias = 0;
            foreach($arrModel_registroOnlineItem as $item){
                $costoMaterias += $item->roi_costo;
            }
            $dataProviderCuotas = $model_registroCuota->getDataCuotasRegistroOnline($model->ron_id, true);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($model->ron_id);

            return $this->render('registry', [
                        "materiasxEstudiante" => $materiasxEstudiante,
                        "materias" => $dataPlanificacion,
                        "data_student" => $data_student,
                        "ron_id" => $id,
                        "rpm_id" => $dataModel["Id"],
                        "est_id" => $model->per_id,
                        "matriculacion_model" => RegistroPagoMatricula::findOne($dataModel["Id"]),
                        "model_registroOnline" => $model,
                        "costoMaterias" => $costoMaterias,
                        "cuotas" =>$dataProviderCuotas,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionView($id) {
        $model = RegistroOnline::findOne($id);
        if ($model) {
            $matriculacion_model = new Matriculacion();
            $model_registroPago = new RegistroPagoMatricula();
            $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($model->per_id, $model->pes_id);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($id);
            $materiasxEstudiante = PlanificacionEstudiante::findOne($model->pes_id);
            $dataModel = $model_registroPago->getRegistroPagoMatriculaByRegistroOnline($id, $model->per_id);

            $arrModel_registroOnlineItem = RegistroOnlineItem::findAll(['ron_id' => $model->ron_id]);
            $model_registroCuota = new RegistroOnlineCuota();
            $costoMaterias = 0;
            foreach($arrModel_registroOnlineItem as $item){
                $costoMaterias += $item->roi_costo;
            }
            $dataProviderCuotas = $model_registroCuota->getDataCuotasRegistroOnline($model->ron_id, true);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($model->ron_id);
            return $this->render('view', [
                        "materiasxEstudiante" => $materiasxEstudiante,
                        "materias" => $dataPlanificacion,
                        "data_student" => $data_student,
                        "ron_id" => $id,
                        "rpm_id" => $dataModel["Id"],
                        "matriculacion_model" => RegistroPagoMatricula::findOne($dataModel["Id"]),
                        "model_registroOnline" => $model,
                        "costoMaterias" => $costoMaterias,
                        "cuotas" =>$dataProviderCuotas,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionUpdatepagoregistro() { // ACCION PARA SUBIR PAGO MATRICULA
        $data = Yii::$app->request->get();
        if ($data['filename']) {
            if (Yii::$app->session->get('PB_isuser')) {
                $file = $data['filename'];
                $route = str_replace("../", "", $file);
                $url_file = Yii::$app->basePath . "/uploads/pagosmatricula/" . $route;
                $arrfile = explode(".", $url_file);
                $typeImage = $arrfile[count($arrfile) - 1];
                if (file_exists($url_file)) {
                    if (strtolower($typeImage) == "pdf") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/pdf");
                        header('Content-Disposition: attachment; filename="matriculacion_' . time() . '.pdf";');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_file));
                        readfile($url_file);
                    }
                }
            }
            exit();
        }
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosmatricula/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
                }
            }

            try {
                $model_registro_pago_matricula = RegistroPagoMatricula::findOne($data["id_rpm"]);
                $model_registro_pago_matricula->rpm_estado_generado = '1';
                $model_registro_pago_matricula->rpm_hoja_matriculacion = $data["file"];
                $modelPersona = Persona::findOne($model_registro_pago_matricula->per_id);

                if ($model_registro_pago_matricula->save()) {
                    $matriculacion_model = new Matriculacion();
                    $data_student = $matriculacion_model->getDataStudenbyRonId($data["id_ron"]);
                    $body = Utilities::getMailMessage("generado", array(
                                "[[user]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido,
                                "[[periodo]]" => $data_student["pla_periodo_academico"],
                                "[[modalidad]]" => $data_student["mod_nombre"]
                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    $titulo_mensaje = "Confirmación de Registro de Matriculación en línea";

                    $from = Yii::$app->params["adminEmail"];
                    $to = array(
                        "0" => $data_student["per_correo"],
                    );
                    $files = array(
                        "0" => Yii::$app->basePath . Yii::$app->params["documentFolder"] . "pagosmatricula/" . $data["file"],
                    );
                    $asunto = "Registro en línea";

                    Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);

                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                } else {
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
        }
    }

    /**
     * Function controller to /matriculacion/registro
     * @author Emilio Moran <emiliojmp9@gmail.com>
     * @param
     * @return
     */
    public function actionRegistro() {
        $per_id = Yii::$app->session->get("PB_perid");
        if ($per_id < 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id == 0){
                $per_id = Yii::$app->session->get("PB_perid");
            }
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (Yii::$app->session->get("PB_perid") < 1000) {
                $per_id = $data['per_id'];
            }
            $con = Yii::$app->db_academico;
            $trans = $con->beginTransaction();
            try{
                if (isset($data["pes_id"])) {
                    $modelPlaEst = PlanificacionEstudiante::findOne($data["pes_id"]);
                    $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                    $modelPersona = Persona::findOne($per_id);
                    $matriculacion_model = new Matriculacion();
                    $modModalidad = new Modalidad();
                    $mod_est = Estudiante::findOne(['per_id' => $per_id]);
                    $today = date("Y-m-d H:i:s");
                    $result_process = $matriculacion_model->checkToday($today, $per_id);
                    $rco_id = $result_process[0]['rco_id'];
                    $rco_num_bloques = $result_process[0]['rco_num_bloques'];
                    $pla_id = $result_process[0]['pla_id'];
                    $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
                    $pes_id = $data["pes_id"];
                    $resultRegistroOnline = $matriculacion_model->checkPlanificacionEstudianteRegisterConfiguracion($per_id, $pes_id, $pla_id);
                    if (count($resultRegistroOnline) > 0) {
                        //Cuando existe un registro en registro_online
                        throw new Exception('Error existe Registro Online.');
                    }
                    $modalidad = $data["modalidad"];
                    $carrera = $data["carrera"];
                    $materias = $data["materias"];
                    $dataMaterias = $matriculacion_model->getInfoMallaEstudiante($per_id);
                    $dataCat = ArrayHelper::map($mod_est->getCategoryCost(), "Cod", "Precio"); // Precio de Categoria Estudiante
                    $modCode = $modModalidad->getCodeCCostoxModalidad($modelPla->mod_id);
                    $arrGastos = $mod_est->getGastosMatriculaOtros($modCode['Cod']);
                    $dataMat = ArrayHelper::map($arrGastos, "Cod", "Precio"); // Gastos Administrativos y demas Costos
                    $cuotas = 0;
                    $totalPago = $dataMat['ASOEST'] + $dataMat['VARIOS'];
                    $subTotal = 0;
                    $semestre = 0;
                    $fechaIniReg = "";
                    $fechaFinReg = "";
                    $fechaIniPer = "";
                    $fechaFinPer = "";
                    foreach($arrGastos as $k => $v){
                        if($v['Cod'] == 'VARIOS'){
                            $cuotas = $v['Cuota'];
                            $semestre = $v['Semestre'];
                            $fechaIniReg = $v['FechaIniReg'];
                            $fechaFinReg = $v['FechaFinReg'];
                            $fechaIniPer = $v['FechaIniPer'];
                            $fechaFinPer = $v['FechaFinPer'];
                        }
                    }
                    
                    // Generacion de #Orden
                    $con1 = \Yii::$app->db_facturacion;
                    Secuencias::initSecuencia($con1, 1, 1, 1, "RON", "PAGO REGISTRO ONLINE");
                    $numOrden = str_pad(Secuencias::nuevaSecuencia($con1, 1, 1, 1, 'RON'), 8, "0", STR_PAD_LEFT);
                    $registro_online_model = new RegistroOnline();
                    $registro_online_model->per_id = $per_id;
                    $registro_online_model->pes_id = $pes_id;
                    $registro_online_model->ron_num_orden = $numOrden; 
                    $registro_online_model->ron_anio = date("Y");
                    $registro_online_model->ron_modalidad = $modalidad;
                    $registro_online_model->ron_carrera = $carrera;
                    $registro_online_model->ron_semestre = $semestre;
                    $registro_online_model->ron_categoria_est = $mod_est->est_categoria;
                    $registro_online_model->ron_valor_arancel = $dataCat[$mod_est->est_categoria];
                    $registro_online_model->ron_valor_aso_estudiante = $dataMat['ASOEST'];
                    $registro_online_model->ron_valor_gastos_adm = $dataMat['VARIOS'];
                    $registro_online_model->ron_valor_matricula = $dataMat['MAT-GRAD']; // se asume que se debio haber pagado antes del registro
                    $registro_online_model->ron_estado_registro = "1"; // Igual esta tampoco ya no se usa
                    $registro_online_model->ron_fecha_registro = date(Yii::$app->params['dateTimeByDefault']);
                    $registro_online_model->ron_fecha_creacion = date(Yii::$app->params['dateTimeByDefault']);
                    $registro_online_model->ron_estado = "1";
                    $registro_online_model->ron_estado_logico = "1";
                    if ($registro_online_model->save()) {
                        $ron_id = $registro_online_model->getPrimaryKey();
                        $materias = explode(",", $materias);
                        $costoMaterias = 0;
                        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
                        $dataPlanificacion = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id, $rco_num_bloques);
                        $num_min = 0;
                        $num_max = 10;
                        if (count($dataPlanificacion) <= 4) {
                            $num_min = count($dataPlanificacion);
                            $num_max = count($dataPlanificacion);
                        } else {
                            $num_min = 4;
                        }
                        $CatPrecio = $dataCat[$data_student['est_categoria']];
                        
                        // Se debe buscar la materia por medio del Alias y obtener el codigo de la asignatura, creditos, y codigo de la malla
                        foreach ($materias as $materia) {
                            $costo = 0;
                            $creditos = 0;
                            $codMateria = 0;
                            $asignatura = $materia;
                            $bloque = "";
                            $hora = "";
                            foreach($dataMaterias as $key => $value){
                                if(trim(strtolower($value['AliasAsignatura'])) == trim(strtolower($materia))){
                                    $asignatura = $value['Asignatura'];
                                    $creditos = $value['AsigCreditos'];
                                    $codMateria = $value['MallaCodAsig'];
                                    $costo = $creditos * $CatPrecio;
                                    $totalPago += $costo;
                                    foreach($dataPlanificacion as $ke => $val){
                                        if(trim(strtolower($val['Alias'])) == trim(strtolower($materia))){
                                            $bloque = $val['Block'];
                                            $hora = $val['Hour'];
                                        }
                                    }
                                }
                            }
                            $registro_online_item_model = new RegistroOnlineItem();
                            $registro_online_item_model->ron_id = $ron_id;
                            $registro_online_item_model->roi_materia_cod = $codMateria; // codigo segun malla academica
                            $registro_online_item_model->roi_materia_nombre = $asignatura;
                            $registro_online_item_model->roi_creditos = $creditos; // creditos de la materia segun malla academica
                            $registro_online_item_model->roi_costo = $costo; 
                            $registro_online_item_model->roi_bloque = $bloque;
                            $registro_online_item_model->roi_hora = $hora;
                            $registro_online_item_model->roi_estado = "1";
                            $registro_online_item_model->roi_estado_logico = "1";
                            $registro_online_item_model->roi_fecha_creacion = date(Yii::$app->params['dateTimeByDefault']);
                            if(!$registro_online_item_model->save()){
                                throw new Exception('Error en Registro Online Item.');
                            }
                        }
                        // Se crea registro de cuotas
                        $arrPorcentajes = [
                            '6' => ['initial' => '16.65', 'others' => '16.67'],
                            '5' => ['initial' => '20', 'others' => '20'],
                            '4' => ['initial' => '25', 'others' => '25'],
                            '3' => ['initial' => '33.34', 'others' => '33.33'],
                        ];
                        $fechaFinReg = date('Y-m-d');
                        $initialMonth = date('F', strtotime($fechaFinReg));
                        $initialMonNum = date('m', strtotime($fechaFinReg));
                        $initialDay = '05';//date('d', strtotime("$fechaFinReg +1 day"));
                        $initialYear = date('y', strtotime($fechaFinReg));
                        for($i=0; $i<$cuotas; $i++){
                            $mod_cuotas = new RegistroOnlineCuota();
                            $mod_cuotas->ron_id = $registro_online_model->ron_id;
                            $mod_cuotas->roc_num_cuota = $i + 1;
                            if($i == 0){
                                $mod_cuotas->roc_porcentaje = $arrPorcentajes[$cuotas]['initial'] . "%";
                                $valorCuota = round((($totalPago * $arrPorcentajes[$cuotas]['initial']) / 100), 2);
                                $mod_cuotas->roc_costo = $valorCuota;
                                $subTotal += $valorCuota;
                                if($initialDay >= date('d'))
                                    $mod_cuotas->roc_vencimiento = strtoupper(Academico::t('matriculacion', $initialMonth)) . " " . $initialDay . "/" . $initialYear;
                                else{
                                    $initialMonth = date('F', strtotime("$fechaFinReg +1 months"));
                                    $initialMonNum += 1;
                                    if($initialMonNum >= 13){
                                        $initialMonNum = '01';
                                        $initialYear += 1;
                                    }
                                    $mod_cuotas->roc_vencimiento = strtoupper(Academico::t('matriculacion', $initialMonth)) . " " . $initialDay . "/" . $initialYear;
                                }
                            }else{
                                $mod_cuotas->roc_porcentaje = $arrPorcentajes[$cuotas]['others'] . "%";
                                $valorCuota = round((($totalPago * $arrPorcentajes[$cuotas]['others']) / 100), 2);
                                if(($i + 1) == $cuotas){
                                    $mod_cuotas->roc_costo = $totalPago - $subTotal;
                                }else{
                                    $mod_cuotas->roc_costo = $valorCuota;
                                    $subTotal += $valorCuota;
                                }
                                if(($initialMonNum + $i) > 12){
                                    $initialYear += 1;
                                    $initialMonNum = 1;
                                }
                                if($initialDay >= date('d')){
                                    $initialMonth = date('F', strtotime("$fechaFinReg +$i months"));
                                }else{
                                    $con = $i + 1;
                                    $initialMonth = date('F', strtotime("$fechaFinReg +$con months"));
                                }
                                $mod_cuotas->roc_vencimiento = strtoupper(Academico::t('matriculacion', $initialMonth)) . " " . $initialDay . "/" . $initialYear;
                            }
                            $mod_cuotas->roc_estado = '1';
                            $mod_cuotas->roc_estado_logico = "1";
                            $mod_cuotas->roc_fecha_creacion = date(Yii::$app->params['dateTimeByDefault']);
                            if(!$mod_cuotas->save()){
                                throw new Exception('Error en Registro Online Cuota.');
                            }
                        }
    
                        // Send email
                        $report = new ExportFile();
                        $this->view->title = Academico::t("matriculacion", "Online Registration"); // Titulo del reporte
                        $materiasxEstudiante = PlanificacionEstudiante::findOne($pes_id);
                        $data_student = $matriculacion_model->getDataStudenbyRonId($ron_id);
                        $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id);
                        $dataProvider = new ArrayDataProvider([
                            'key' => 'Ids',
                            'allModels' => $dataPlanificacion,
                            'pagination' => [
                                'pageSize' => Yii::$app->params["pageSize"],
                            ],
                            'sort' => [
                                'attributes' => ["Subject"],
                            ],
                        ]);
                        $model_registroOnline = RegistroOnline::findOne($ron_id);
                        $arrModel_registroOnlineItem = RegistroOnlineItem::findAll(['ron_id' => $ron_id]);
                        $model_registroCuota = new RegistroOnlineCuota();
                        $costoMaterias = 0;
                        foreach($arrModel_registroOnlineItem as $item){
                            $costoMaterias += $item->roi_costo;
                        }
                        $dataProviderCuotas = $model_registroCuota->getDataCuotasRegistroOnline($ron_id, true);
    
                        $path = "Registro_" . date("Ymdhis") . ".pdf";
                        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
                        $report->createReportPdf(
                                $this->render('exportpdf', [
                                    "planificacion" => $dataProvider,
                                    "data_student" => $data_student,
                                    "ron_id" => $ron_id,
                                    "materiasxEstudiante" => $materiasxEstudiante,
                                    "model_registroOnline" => $model_registroOnline,
                                    "costoMaterias" => $costoMaterias,
                                    "cuotas" => $dataProviderCuotas,
                                    "orden" => $numOrden,
                                ])
                        );
    
                        $tmp_path = sys_get_temp_dir() . "/" . $path;
    
                        $report->mpdf->Output($tmp_path, ExportFile::OUTPUT_TO_FILE);
    
                        $from = Yii::$app->params["adminEmail"];
                        $to = array(
                            "0" => $data_student["per_correo"],
                        );
                        $files = array(
                            "0" => $tmp_path,
                        );
                        $asunto = Academico::t("matriculacion", "Online Registration");
                        $base = Yii::$app->basePath;
                        $lang = Yii::$app->language;
                        $body = Utilities::getMailMessage("registro", array("[[user]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido, "[[periodo]]" => $data_student["pla_periodo_academico"], "[[modalidad]]" => $data_student["mod_nombre"]), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                        $titulo_mensaje = "Registro de Matriculación en línea";

                        $trans->commit();
                        Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);
                        Utilities::removeTemporalFile($tmp_path);
    
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }else{
                        throw new Exception('Error en Registro Online.');
                    }
                }
            }catch(Exception $e){
                $trans->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'.$e->getMessage()),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }

        $matriculacion_model = new Matriculacion();
        $today = date("Y-m-d H:i:s");
        $result_process = $matriculacion_model->checkToday($today, $per_id);

        if (count($result_process) > 0) {
            /*             * Exist a register process */
            $pla_id = $result_process[0]['pla_id'];
            $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
            if (count($resultIdPlanificacionEstudiante) > 0) {
                /*                 * Exist a register of planificacion_estudiante */
                $pes_id = $resultIdPlanificacionEstudiante[0]['pes_id'];

                $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $pes_id);
                if ($data_student) {
                    $ron_id = $data_student["ron_id"];
                    $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id);
                    $materiasxEstudiante = PlanificacionEstudiante::findOne($pes_id);
                    $model_registroOnline = RegistroOnline::findOne($ron_id);
                    $arrModel_registroOnlineItem = RegistroOnlineItem::findAll(['ron_id' => $ron_id]);
                    $model_registroCuota = new RegistroOnlineCuota();
                    $costoMaterias = 0;
                    foreach($arrModel_registroOnlineItem as $item){
                        $costoMaterias += $item->roi_costo;
                    }
                    $dataProvider = new ArrayDataProvider([
                        'key' => 'Ids',
                        'allModels' => $dataPlanificacion,
                        'pagination' => [
                            'pageSize' => Yii::$app->params["pageSize"],
                        ],
                        'sort' => [
                            'attributes' => ["Subject"],
                        ],
                    ]);

                    $dataProviderCuotas = $model_registroCuota->getDataCuotasRegistroOnline($ron_id, true);

                    return $this->render('registro', [
                                "planificacion" => $dataProvider,
                                "data_student" => $data_student,
                                "title" => Academico::t("matriculacion", "Register saved (Record Time)"),
                                "ron_id" => $ron_id,
                                "materiasxEstudiante" => $materiasxEstudiante,
                                "leyenda" => $this->leyenda,
                                "model_registroOnline" => $model_registroOnline,
                                "costoMaterias" => $costoMaterias,
                                "cuotas" => $dataProviderCuotas,
                    ]);
                } else {
                    return $this->render('index-out', [
                                "message" => Academico::t("matriculacion", "There is no information on the last registration (Registration time)"),
                    ]);
                }
            } else {
                /*                 * Not exist a planificacion_estudiante */
                return $this->render('index-out', [
                            "message" => Academico::t("matriculacion", "There is no planning information (Registration time)"),
                ]);
            }
        } else {
            $resultData = $matriculacion_model->getLastIdRegistroOnline($per_id);
            if (count($resultData) > 0) {
                $last_ron_id = $resultData[0]['ron_id'];
                $last_pes_id = $resultData[0]['pes_id'];
                $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $last_pes_id);
                $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($last_ron_id);
                $materiasxEstudiante = PlanificacionEstudiante::findOne($last_pes_id);
                $model_registroOnline = RegistroOnline::findOne($last_ron_id);
                $arrModel_registroOnlineItem = RegistroOnlineItem::findAll(['ron_id' => $last_ron_id]);
                $model_registroCuota = new RegistroOnlineCuota();
                    $costoMaterias = 0;
                    foreach($arrModel_registroOnlineItem as $item){
                        $costoMaterias += $item->roi_costo;
                    }
                $dataProviderCuotas = $model_registroCuota->getDataCuotasRegistroOnline($last_ron_id, true);
                $dataProvider = new ArrayDataProvider([
                    'key' => 'Ids',
                    'allModels' => $dataPlanificacion,
                    'pagination' => [
                        'pageSize' => Yii::$app->params["pageSize"],
                    ],
                    'sort' => [
                        'attributes' => ["Subject"],
                    ],
                ]);

                return $this->render('registro', [
                            "planificacion" => $dataProvider,
                            "data_student" => $data_student,
                            "title" => Academico::t("matriculacion", "Last register saved (Non-registration time)"),
                            "ron_id" => $last_ron_id,
                            "materiasxEstudiante" => $materiasxEstudiante,
                            "model_registroOnline" => $model_registroOnline,
                            "costoMaterias" => $costoMaterias,
                            "cuotas" => $dataProviderCuotas,
                ]);
            } else {
                /*                 * If not exist a minimal one register in registro_online */
                return $this->render('index-out', [
                            "message" => Academico::t("matriculacion", "There is no information on the last record (Non-registration time)"),
                ]);
            }
        }
    }

    public function actionExportpdf() {
        $report = new ExportFile();
        $this->view->title = Academico::t("matriculacion", "Registration"); // Titulo del reporte

        $matriculacion_model = new Matriculacion();

        $data = Yii::$app->request->get();

        $ron_id = $data['ron_id'];

        /* return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $ron_id); */

        $data_student = $matriculacion_model->getDataStudenbyRonId($ron_id);
        $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id);
        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $dataPlanificacion,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Subject"],
            ],
        ]);

        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    "planificacion" => $dataProvider,
                    "data_student" => $data_student,
                ])
        );
        $report->mpdf->Output('Registro_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionAprobacionpago() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            if (isset($data["search"])) {
                $search = $data["search"];
                $pla_periodo_academico = $data["pla_periodo_academico"];
                $mod_id = $data["mod_id"];
                $aprobacion = $data["aprobacion"];
                $dataPagos = Matriculacion::getEstudiantesPagoMatricula($search, $pla_periodo_academico, $mod_id, $aprobacion);
                $dataProvider = new ArrayDataProvider([
                    'key' => 'PesId',
                    'allModels' => $dataPagos,
                    'pagination' => [
                        'pageSize' => Yii::$app->params["pageSize"],
                    ],
                    'sort' => [
                        'attributes' => ["Estudiante"],
                    ],
                ]);
                return $this->renderPartial('aprobacion-pago-grid', [
                            "pagos" => $dataProvider,
                ]);
            }
        }
        
        $arr_pla = Planificacion::getPeriodosAcademico();
        $arr_status = [-1 => Academico::t("matriculacion", "-- Select Status --"), 0 => Academico::t("matriculacion", "Not reviewed"), 1 => Academico::t("matriculacion", "Approved"), 2 => Academico::t("matriculacion", "Rejected")];
        /* print_r($arr_pla); */
        if (count($arr_pla) > 0) {
            $arr_modalidad = Modalidad::findAll(["mod_estado" => 1, "mod_estado_logico" => 1]);
            $pla_periodo_academico = $arr_pla[0]["pla_periodo_academico"];
            $mod_id = $arr_modalidad[0]->mod_id;
            $dataPagos = Matriculacion::getEstudiantesPagoMatricula(null, $pla_periodo_academico, $mod_id);
            $dataProvider = new ArrayDataProvider([
                'key' => 'PesId',
                'allModels' => $dataPagos,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ["Estudiante"],
                ],
            ]);
            return $this->render('aprobacion-pago', [
                        'arr_pla' => (empty(ArrayHelper::map($arr_pla, "pla_periodo_academico", "pla_periodo_academico"))) ? array(Yii::t("matriculacion", "-- Select Academic Period --")) : (ArrayHelper::map($arr_pla, "pla_periodo_academico", "pla_periodo_academico")),
                        'arr_modalidad' => (empty(ArrayHelper::map($arr_modalidad, "mod_id", "mod_nombre"))) ? array(Yii::t("matriculacion", "-- Select Modality --")) : (ArrayHelper::map($arr_modalidad, "mod_id", "mod_nombre")),
                        'pagos' => $dataProvider,
                        'pla_periodo_academico' => $pla_periodo_academico,
                        'mod_id' => $mod_id,
                        'arr_status' => $arr_status,
            ]);
        } else {
            return $this->render('index-out', [
                        "message" => Academico::t("matriculacion", "There is no planning data"),
            ]);
        }
    }

    public function actionUpdateestadopago() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $id = $data["id"];
                $state = $data["state"];
                $registro_pago_matricula_model = RegistroPagoMatricula::findOne($id);
                $registro_pago_matricula_model->rpm_estado_aprobacion = $state;
                $registro_pago_matricula_model->rpm_fecha_transaccion = $fecha_transaccion;
                $registro_pago_matricula_model->rpm_usuario_apruebareprueba = $usu_id;

                if ($registro_pago_matricula_model->save()) {
                    if($state == 2){ 
                        $modelPersona = Persona::findOne($registro_pago_matricula_model->per_id);
                        $matriculacion_model = new Matriculacion();
                        $modelRegOnl = RegistroOnline::find()->where(["per_id" => $registro_pago_matricula_model->per_id])->orderBy(['ron_id'=>SORT_DESC])->one();
                        $data_student = $matriculacion_model->getDataStudenbyRonId($modelRegOnl->ron_id);
                        $from = Yii::$app->params["adminEmail"];
                        $to = array(
                            "0" => $modelPersona->per_correo,
                        );
                        $asunto = "Registro en línea";
                        $body = Utilities::getMailMessage("pagonegado", 
                            array("[[user]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido, 
                                  "[[periodo]]" => $data_student["pla_periodo_academico"], 
                                  "[[modalidad]]" => $data_student["mod_nombre"]), 
                            Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                        $titulo_mensaje = "Registro de Matriculación en línea";

                        Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body);
                    }
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionDescargarpago() {
        $report = new ExportFile();

        $data = Yii::$app->request->get();

        $rpm_id = $data['rpm_id'];
        $registro_pago_matricula = RegistroPagoMatricula::findOne(["rpm_id" => $rpm_id]);
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . $registro_pago_matricula->rpm_archivo;

        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        } else {
            /*             * en caso de que no */
        }
        return;
    }

}
