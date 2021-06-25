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
use app\modules\academico\models\CancelacionRegistroOnline;
use app\modules\academico\models\CancelacionRegistroOnlineItem;
use app\modules\academico\models\RegistroOnlineCuota;
use app\modules\academico\models\GastoAdministrativo;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\academico\models\RegistroAdicionalMaterias;
use app\modules\academico\models\RegistroOnlineItem;
use app\modules\academico\models\RegistroPagoMatricula;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\financiero\models\Secuencias;
use app\modules\academico\models\Asignatura;
use app\modules\financiero\models\FacturasPendientesEstudiante;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\base\Security;
use app\modules\financiero\models\PagosFacturaEstudiante;
use app\modules\academico\models\Especies;
use app\modules\financiero\models\FormaPago;
use app\modules\academico\models\FechasVencimientoPago;


use app\modules\academico\Module as Academico;
use app\modules\financiero\Module as financiero;

Academico::registerTranslations();
financiero::registerTranslations();


class MatriculacionController extends \app\components\CController {

    public $limitSubject = [

        'asociado' => ["min" => 2, "max" => 6], //Asocciate
        'licenciatura' => ["min" => 2, "max" => 5], //Bachelor
        'maestría' => ["min" => 2, "max" => 4], //Masters
    ];

    public $limitCancel = [
        'asociado' => ["min" => 2], //Asocciate
        'licenciatura' => ["min" => 2], //Bachelor
        'master' => ["min" => 1], //Masters
    ];

    public $arrCredito = [
        '1' => 'Total Payment', 
        '2' => 'Payment by Period', 
        '3' => 'Direct Credit', 
    ];

    public $estadoAprobar = [
        '2' => 'Rejected',
        '1' => 'Approved',
    ];

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
                    // \app\models\Utilities::putMessageLogFile("resultRegistroOnline: " . $resultRegistroOnline);
                    // \app\models\Utilities::putMessageLogFile("resultRegistroOnline: " . empty($resultRegistroOnline));
                    if (!empty($resultRegistroOnline)) {
                        //Cuando existe un registro en registro_online
                        return $this->redirect(['matriculacion/registro', 'per_id' => Yii::$app->request->get('per_id', base64_encode(Yii::$app->session->get("PB_perid")))]);
                    } else {
                        //Cuando no existe registro en registro_online, eso quiere decir que no ha seleccionado las materias aun y registrado

                        /* $con1 = \Yii::$app->db_facturacion; */

                        /* Secuencias::initSecuencia($con1, 1, 1, 1, "RAC", "PAGO REGISTRO ONLINE"); */
                        /*                         * No exist register into registro_online, so with need saved the data into register_online */
                        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
                        $dataPlanificacion = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);
                        $num_min = 0;
                        $num_max = 10;
                        if (count($dataPlanificacion) <= 2) {
                            $num_min = count($dataPlanificacion);
                            $num_max = count($dataPlanificacion);
                        } else {
                            $num_min = 2;
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

                        $ron = RegistroOnline::find()->where(['per_id' => $per_id])->asArray()->one();

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
                                    "ron_id" => $ron['ron_id'],
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
                                    'description' => "Pago de Matricula desde el sistema Asgard/UTEG"
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
                        //print_r($data);die();
                        $model_registro_pago_matricula = new RegistroPagoMatricula();

                        $model_registro_pago_matricula->per_id = $per_id;
                        $model_registro_pago_matricula->pla_id = $data['pla_id'];
                        // $model_registro_pago_matricula->pes_id = $data['pes_id'];
                        if($data['pla_id']!=1)
                            $model_registro_pago_matricula->rpm_archivo = "pagosmatricula/" . $data["archivo"];
                        else
                            $model_registro_pago_matricula->rpm_archivo = "pagosmatricula por stripe";

                        //$model_registro_pago_matricula->ron_id = null;
                        $model_registro_pago_matricula->rpm_estado_aprobacion = "0";
                        $model_registro_pago_matricula->rpm_estado_generado   = "0";
                        $model_registro_pago_matricula->rpm_acepta_terminos   = "0";
                        $model_registro_pago_matricula->rpm_estado            = "1";
                        $model_registro_pago_matricula->rpm_estado_logico     = "1";
                        $model_registro_pago_matricula->rpm_fecha_creacion    = date(Yii::$app->params['dateTimeByDefault']) ; 

                        $bandera = $model_registro_pago_matricula->save();
                        if ($bandera) {
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                        } else {
                            //Utilities::putMessageLogFile();
                            $message = array(
                                "wtmessage" => Yii::t('notificaciones', '1Your information has not been saved. Please try again.'),
                                "title" => Yii::t('jslang', 'Error'),
                                "error" => $bandera,
                            );
                            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                        }
                    //}
                } catch (Exception $ex) {
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', '2Your information has not been saved. Please try again.'),
                        "title" => Yii::t('jslang', 'Error'),
                        "error" => $ex,
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

    public function actionList() { // listar estudiantes por aprobar matriculacion
        $model = new RegistroPagoMatricula();
        $arr_status = [-2 => Academico::t("matriculacion", "-- Select Status --"), -1 => Academico::t("matriculacion", "Payment Pending"), 0 => Academico::t("matriculacion", "Payment Processing"), 1 => Academico::t("matriculacion", "Payment Processed"), 2 => Academico::t("matriculacion", "Payment not Approved")];

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            return $this->renderPartial('new-grid', [
                      //  "model" => $model->getAllRegistroMatriculaxGenerarGrid($data["search"], $data["mod_id"], $data["estado"], $data["carrera"], $data["periodo"], true),
                        "model" => $model->getAllRegistroMatriculaxGenerarGrid($data["search"], $data["mod_id"], $data["estado"], $data["carrera"], $data["periodo"],$data["fechaini"],$data["fechafin"], true),
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

        return $this->render('new', [
                    'model' => $model->getAllRegistroMatriculaxGenerarGrid(NULL, NULL, NULL, NULL, NULL,NULL,NULL, true),
                    'arr_carrera' => array_merge([0 => Academico::t("matriculacion", "-- Select Program --")], ArrayHelper::map($arr_carrera, "pes_id", "pes_carrera")),
                    'arr_pla_per' => array_merge([0 => Academico::t("matriculacion", "-- Select Academic Period --")], ArrayHelper::map($arr_pla_per, "pla_id", "pla_periodo_academico")),
                    'arr_modalidad' => array_merge([0 => Academico::t("matriculacion", "-- Select Modality --")], ArrayHelper::map($arr_modalidad, "mod_id", "mod_nombre")),
                    'arr_status' => $arr_status,
        ]);
    }//funtion actionList

    public function actionRegistry($id) {
        $model = RegistroOnline::findOne($id);
        if ($model) {
            $matriculacion_model = new Matriculacion();
            $model_registroPago = new RegistroPagoMatricula();
            $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($model->per_id, $model->pes_id);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($id,0);
            $materiasxEstudiante = PlanificacionEstudiante::findOne($model->pes_id);
            $dataModel = $model_registroPago->getRegistroPagoMatriculaByRegistroOnline($id, $model->per_id);

            $arrModel_registroOnlineItem = RegistroOnlineItem::findAll(['ron_id' => $model->ron_id]);
            $model_registroCuota = new RegistroOnlineCuota();
            $costoMaterias = 0;
            foreach($arrModel_registroOnlineItem as $item){
                $costoMaterias += $item->roi_costo;
            }
            $dataProviderCuotas = $model_registroCuota->getDataCuotasRegistroOnline($model->ron_id, true);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($model->ron_id,0);

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
        return $this->redirect('registro');
    }

    public function actionView($id) {
        $model = RegistroOnline::findOne($id);
        if ($model) {
            $matriculacion_model = new Matriculacion();
            $model_registroPago = new RegistroPagoMatricula();
            $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($model->per_id, $model->pes_id);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($id,0);
            $materiasxEstudiante = PlanificacionEstudiante::findOne($model->pes_id);
            $dataModel = $model_registroPago->getRegistroPagoMatriculaByRegistroOnline($id, $model->per_id);

            $arrModel_registroOnlineItem = RegistroOnlineItem::findAll(['ron_id' => $model->ron_id]);
            $model_registroCuota = new RegistroOnlineCuota();
            $costoMaterias = 0;
            foreach($arrModel_registroOnlineItem as $item){
                $costoMaterias += $item->roi_costo;
            }
            $dataProviderCuotas = $model_registroCuota->getDataCuotasRegistroOnline($model->ron_id, true);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($model->ron_id,0);
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
        return $this->redirect('registro');
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
    public function actionRegistro() { // pantalla para que el estudiante seleccione las materias a registrarse
       //\app\models\Utilities::putMessageLogFile('>>>>PER 1 :'.$per_id);       
       // if ($per_id==Null) { $per_id = Yii::$app->session->get("PB_perid"); } 
      // $userper_id = Yii::$app->session->get("PB_perid");
      // if ($userperid != $per_id) {$noAdd=2; } else {$noAdd=1; } 
        $_SESSION['JSLANG']['You must choose at least one Subject to Cancel Registration'] = Academico::t('matriculacion', 'You must choose at least one Subject to Cancel Registration');
        $_SESSION['JSLANG']['You must choose at least a number or subjects '] = Academico::t('matriculacion', 'You must choose at least a number or subjects ');
        $_SESSION['JSLANG']['You must choose at least two'] = Academico::t('matriculacion', 'You must choose at least two');
        $_SESSION['JSLANG']['You must choose at least subject'] = Academico::t('matriculacion', 'You must choose at least subjects');
        $_SESSION['JSLANG']['The number of subject that you can cancel is '] = Academico::t('matriculacion', 'The number of subject that you can cancel is ');

        $per_id = Yii::$app->session->get("PB_perid");

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            //print_r($data);die();
            
            if (Yii::$app->session->get("PB_perid") < 1000) {
                $per_id = $data['per_id'];
            }
            

            try{
                if (isset($data["pes_id"])) {
                    $modelPersona = Persona::findOne($per_id);
                                        \app\models\Utilities::putMessageLogFile(1);
                    $modelPlaEst = PlanificacionEstudiante::findOne($data["pes_id"]);

                    $pla_id_est = $modelPlaEst->pla_id;

                    \app\models\Utilities::putMessageLogFile('pla_id_est: '.$pla_id_est);
                    //echo($pla_id_est);die();

                    $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                    $matriculacion_model = new Matriculacion();
                    $modModalidad = new Modalidad();
                    $mod_est = Estudiante::findOne(['per_id' => $per_id]);
                    $today = date("Y-m-d H:i:s");
                    $result_process = $matriculacion_model->checkToday($today, $per_id);
                    $rco_id = $result_process[0]['rco_id'];
                    $rco_num_bloques = $result_process[0]['rco_num_bloques'];
                    $pla_id = $result_process[0]['pla_id'];
                    $matricula=$matriculacion_model->getPlanificacionPago($data['modalidad']);
                    //\app\models\Utilities::putMessageLogFile("matricula: " . $matricula);
                    $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
                    $pes_id = $data["pes_id"];

                    /*$resultRegistroOnline = $matriculacion_model->checkPlanificacionEstudianteRegisterConfiguracion($per_id, $pes_id, $pla_id);
                    if (count($resultRegistroOnline) > 0) {
                        //Cuando existe un registro en registro_online
                        throw new Exception('Error existe Registro Online.');
                    }*/
           // $dataPlanificacion   = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);

                    $modalidad = $data["modalidad"];
                    $carrera = $data["carrera"];
                    $materias = $data["materias"];
                    $matStudent=$matricula['valor'];
                    

                    $dataMaterias = $matriculacion_model->getInfoMallaEstudiante($per_id);
                    //$dataCat = ArrayHelper::map($mod_est->getCategoryCost(), "Cod", "Precio"); // Precio de Categoria Estudiante
                    $modCode = $modModalidad->getCodeCCostoxModalidad($modelPla->mod_id);
                    //$arrGastos = $mod_est->getGastosMatriculaOtros($modCode['Cod']);
                    //$dataMat = ArrayHelper::map($arrGastos, "Cod", "Precio"); // Gastos Administrativos y demas Costos
                    $cuotas = 0;
                    $totalPago = 0;//$dataMat['ASOEST'] + $dataMat['VARIOS'];
                    $subTotal = 0;
                    $semestre = 0;
                    $fechaIniReg = "";
                    $fechaFinReg = "";
                    $fechaIniPer = "";
                    $fechaFinPer = "";

                    /*foreach($arrGastos as $k => $v){
                        if($v['Cod'] == 'VARIOS'){
                            $cuotas = $v['Cuota'];
                            $semestre = $v['Semestre'];
                            $fechaIniReg = $v['FechaIniReg'];
                            $fechaFinReg = $v['FechaFinReg'];
                            $fechaIniPer = $v['FechaIniPer'];
                            $fechaFinPer = $v['FechaFinPer'];
                        }
                    }
                    */
                    // Generacion de #Orden
                    $con1 = \Yii::$app->db_facturacion;
                    Secuencias::initSecuencia($con1, 1, 1, 1, "RON", "PAGO REGISTRO ONLINE");
                    $numOrden = str_pad(Secuencias::nuevaSecuencia($con1, 1, 1, 1, 'RON'), 8, "0", STR_PAD_LEFT);
                    
                    //$registro_online_model = new RegistroOnline();
                    
                    $RegistroOnline = RegistroOnline::find()->select("ron_id")->where(["per_id" => $per_id, "pes_id" => $pes_id ])->asArray()->all();
                    
                    if(empty($RegistroOnline)){
                        $registro_online_model = new RegistroOnline();

                        $ron_id = 0;
                    }
                    else
                        $ron_id = $RegistroOnline[0]['ron_id']; 
                    /*\app\models\Utilities::putMessageLogFile("pes_id: " . $pes_id);
                    \app\models\Utilities::putMessageLogFile("numOrden: " . $numOrden);
                    \app\models\Utilities::putMessageLogFile("modalidad: " . $modalidad);
                    \app\models\Utilities::putMessageLogFile("carrera: " . $carrera);
                    \app\models\Utilities::putMessageLogFile("semestre: " . $semestre);
                    \app\models\Utilities::putMessageLogFile("mod_est->est_categoria: " . $mod_est->est_categoria);
                    \app\models\Utilities::putMessageLogFile("dataCat[$mod_est->est_categoria]: " . $dataCat[$mod_est->est_categoria]);
                    \app\models\Utilities::putMessageLogFile("dataMat['VARIOS']: " . $dataMat['VARIOS']);
                    \app\models\Utilities::putMessageLogFile("dataMat['MAT-GRAD']: " . $dataMat['MAT-GRAD']);
                    \app\models\Utilities::putMessageLogFile(date(Yii::$app->params['dateTimeByDefault']));*/

                    /*$registro_online_model->per_id = $per_id;
                    $registro_online_model->pes_id = $pes_id;
                    $registro_online_model->ron_num_orden = strval($numOrden); 
                    $registro_online_model->ron_anio = strval(date("Y"));
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
                    $registro_online_model->ron_estado_logico = "1";*/

                        
                        if($data['modalidad']=='1'){
                            //$gastoAdm=50;
                            $cobMat=65;
                            //$dataMat['VARIOS']=$gastoAdm;
                            $dataMat['MAT-GRAD']=$cobMat;
                            
                        } else if ($data['modalidad']=='2') {
                            // code...
                            //$gastoAdm=300;
                            $cobMat=200;
                            //$dataMat['VARIOS']=$gastoAdm;
                            $dataMat['MAT-GRAD']=$cobMat;
                            
                        } else if ($data['modalidad']=='3') {
                            // code...
                            //$gastoAdm=300;
                            $cobMat=200;
                            //$dataMat['VARIOS']=$gastoAdm;
                            $dataMat['MAT-GRAD']=$cobMat;
                        } else if ($data['modalidad']=='4') {
                            // code...
                            //$gastoAdm=300;
                            $cobMat=115;
                            //$dataMat['VARIOS']=$gastoAdm;
                            $dataMat['MAT-GRAD']=$cobMat;
                        } else {
                            // code...
                            //$gastoAdm=0;
                            $cobMat=0;
                            //$dataMat['VARIOS']=$gastoAdm;
                            $dataMat['MAT-GRAD']=$cobMat;
                        }  
                        
                        
                    if($ron_id == 0){
                        $id = $registro_online_model->insertRegistroOnline(
                                $per_id, 
                                $pes_id, 
                                strval($numOrden), 
                                strval($modalidad), 
                                strval($carrera), 
                                strval($semestre), 
                                strval($mod_est->est_categoria),
                                //$dataCat[$mod_est->est_categoria], 
                                0,/**$dataMat['ASOEST'], -*/
                                0,//$dataMat['VARIOS'],
                                0,// gastos pendientes
                                $dataMat['MAT-GRAD'],
                                1//CAMBIAR ESTE VALOR OJO!!!!!!!!!!!
                            );
                    }else{
                        $id = $ron_id;
                    }
                    

                        \app\models\Utilities::putMessageLogFile($id);

                    if ($id > 0) {
                        $ron_id = $id;//;$registro_online_model->getPrimaryKey();
                        $RegistroOnline=RegistroOnline::find()->select("ron_valor_gastos_adm")->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one();
                        \app\models\Utilities::putMessageLogFile($id);
                        $costoMaterias = 0;
                        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
                        $dataPlanificacion = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);
                        \app\models\Utilities::putMessageLogFile($dataPlanificacion['Asignatura']);
                        $num_min = 0;
                        $num_max = 10;
                        if (count($dataPlanificacion) <= 2) {
                            $num_min = count($dataPlanificacion);
                            $num_max = count($dataPlanificacion);
                        } else {
                            $num_min = 2;
                        }
                        // $CatPrecio = $data[$data_student['est_categoria']];
                        
                        // Se debe buscar la materia por medio del Alias y obtener el codigo de la asignatura, creditos, y codigo de la malla
                        $contMateria = 0;
                        $bloques = $data["bloque"];
                        //print_r($data);die();
                        $horas   = $data["hora"];
                        
                        $rois_insertados = [];

                        foreach ($materias as $materia) {
                            $costo      = 0;
                            $creditos   = 0;
                            $codMateria = 0;
                            $asignatura = $materia;
                            $bloque     = $bloques[$contMateria];
                            $hora       = $horas[$contMateria];
                            foreach($dataMaterias as $key => $value){
                                if(trim(strtolower($value['Asignatura'])) == trim(strtolower($materia))){
                                    $asignatura = $value['Asignatura'];
                                    $creditos   = $value['AsigCreditos'];
                                    $codMateria = $value['MallaCodAsig'];
                                    $costo      = $creditos * $value['CostoCredito'];
                                    $totalPago += $costo;
                                    /*
                                    foreach($dataPlanificacion as $ke => $val){
                                        //\app\models\Utilities::putMessageLogFile("A2". $materia);
                                        //\app\models\Utilities::putMessageLogFile("A2". $val['Asignatura']);
                                        if(trim(strtolower($val['Asignatura'])) == trim(strtolower($materia))){
                                            $bloque = $val['Block'];
                                            $hora   = $val['Hour'];
                                        }
                                    }
                                    */
                                }
                            }//foreach

                            $registro_online_item_model = new RegistroOnlineItem();

                            /*$registro_online_item_model->ron_id = $ron_id;
                            \app\models\Utilities::putMessageLogFile("ron_id: " . $ron_id);
                            $registro_online_item_model->roi_materia_cod = $codMateria; // codigo segun malla academica
                            \app\models\Utilities::putMessageLogFile("codMateria: " . $codMateria);
                            $registro_online_item_model->roi_materia_nombre = $asignatura;
                            \app\models\Utilities::putMessageLogFile("asignatura: " . $asignatura);
                            $registro_online_item_model->roi_creditos = $creditos; // creditos de la materia segun malla academica
                            \app\models\Utilities::putMessageLogFile("creditos: " . $creditos);
                            $registro_online_item_model->roi_costo = $costo; 
                            \app\models\Utilities::putMessageLogFile("creditos: " . $costo);
                            $registro_online_item_model->roi_bloque = $bloque;
                            \app\models\Utilities::putMessageLogFile("creditos: " . $bloque);
                            $registro_online_item_model->roi_hora = $hora;
                            \app\models\Utilities::putMessageLogFile("creditos: " . $horas);
                            $registro_online_item_model->roi_estado = "1";
                            $registro_online_item_model->roi_estado_logico = "1";
                            $registro_online_item_model->roi_fecha_creacion = date(Yii::$app->params['dateTimeByDefault']);
                            \app\models\Utilities::putMessageLogFile("A3");*/

                            $id_roi = $registro_online_item_model->insertRegistroOnlineItem(
                                $id, 
                                strval($codMateria), 
                                strval($asignatura), 
                                strval($creditos), 
                                $costo, 
                                strval($bloque), 
                                strval($hora)
      
                            );



                            $rois_insertados[] = $id_roi;


                            /*if(!$registro_online_item_model->save()){
                                throw new Exception('Error en Registro Online Item.');
                            }*/

                            
                            $contMateria++;


                        }//foreach
                        if($RegistroOnline['ron_valor_gastos_adm']<=0){
                            $roi_bloque = RegistroOnlineItem::find()->select("roi_bloque")->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                            \app\models\Utilities::putMessageLogFile("roi_id: " . print_r($roi_bloque,true));
                            // Tomar el valor actual de gastos administrativos
                            $block_roi= RegistroOnlineItem::find()->where(['ron_id' => $id])->asArray()->one()['roi_bloque'];
                            $gastos_administrativos_valor = GastoAdministrativo::find()->where(['mod_id' => $modalidad])->asArray()->one()['gadm_gastos_varios'];
                            \app\models\Utilities::putMessageLogFile("roi_id: " . print_r($gastos_administrativos_valor,true));
                            $bloques = $bloque[0]; // Tomar el primer bloque
                            $mitad = 1; // Empezar asumiendo que se toma 1 solo bloque
                            \app\models\Utilities::putMessageLogFile("Bloquea: " . $bloques,true);
                            \app\models\Utilities::putMessageLogFile("Block: " . $bloque);
                        /*if(count($bloques) == 2){
                            // Si sólo son 2 materias, los gastos administrativos son completos
                            $mitad = 1;
                        }*/ // queda descartado 
                        
                            foreach ($roi_bloque as $key => $value) { // recorrer la lista de bloques
                                if($value != $bloques ){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                                    $mitad = 2; // Así que se divide a la mitad                                    
                                    break; // Salir del foreach
                                }
                                // Si nunca entra al condicional, quiere decir que todas las materias son del mismo bloque y se mantiene el valor de gatos administrativos
                            } //end foreach
                                
                                if($block_roi==$bloque){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                                     // Así que se divide a la mitad
                                    $gastos_pendientes=$gastos_administrativos_valor;
                                    $gastos_administrativos_valor = $gastos_administrativos_valor * 1;
                                    $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                                    //break; // Salir del foreach
                                }else{
                                    $gastos_pendientes=0;
                                    $gastos_administrativos_valor = $gastos_administrativos_valor * $mitad;
                                    $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                                }
                        
                            /*if(!$update){
                                throw new Exception('Error al Registrar las Materias adicionales.');
                            }*/

                        
                        }else{
                            $RegistroOnline=RegistroOnline::find()->select("ron_valor_gastos_pendientes")->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one();
                            if($RegistroOnline['ron_valor_gastos_pendientes']>0){
                                $roi_bloque = RegistroOnlineItem::find()->select("roi_bloque")->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                                $gastos_administrativos_valor = GastoAdministrativo::find()->where(['mod_id' => $modalidad])->asArray()->one()['gadm_gastos_varios'];
                                $gastos_registro = RegistroOnline::find()->where(['ron_id' => $id])->asArray()->one()['ron_valor_gastos_adm'];
                                if($gastos_administrativos_valor==$gastos_registro){
                                    $gastos_pendientes=0;
                                    $gastos_administrativos_valor = $gastos_administrativos_valor * 2;
                                    $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);

                                }

                            }
                        }

                        $RegistroAdiconal=RegistroAdicionalMaterias::find()->select("rama_id")->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->all();
                        if (empty($RegistroAdiconal)){

                                $roi_id = RegistroOnlineItem::find()->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                                \app\models\Utilities::putMessageLogFile("registro online " . $id);
                                //\app\models\Utilities::putMessageLogFile("registro pago " . $model_rpm['rpm_id']);
                                $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                                $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $modelPla['pla_id']);
                                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                                $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $modelPla['pla_id']);
                                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                                $paca_id=$modelPla['paca_id'];
                                \app\models\Utilities::putMessageLogFile("periodo" . $modelPla['paca_id']);
                                $registro_adicional_materias_model  = new RegistroAdicionalMaterias();
                                $RegistroAdd=RegistroAdicionalMaterias::find()->select("rpm_id")->where(["per_id" => $per_id, "ron_id" => $id ])->asArray()->all();
                                //rpm_rama=Matriculacion::getRegistroAddicionalM($id);
                                /*if(empty($roi_id['0']['roi_id'])){
                                    $roi_id['0']['roi_id']="NULL";
                                }elseif(empty($roi_id['1']['roi_id'])){
                                    $roi_id['1']['roi_id']="NULL";
                                }elseif(empty($roi_id['2']['roi_id'])){
                                    $roi_id['2']['roi_id']="NULL";
                                }elseif(empty($roi_id['3']['roi_id'])){
                                    $roi_id['3']['roi_id']="NULL";
                                }elseif(empty($roi_id['4']['roi_id'])){
                                    $roi_id['4']['roi_id']="NULL";
                                }elseif(empty($roi_id['5']['roi_id'])){
                                    $roi_id['5']['roi_id']="NULL";
                                }*/

                                 \app\models\Utilities::putMessageLogFile("asignacion de roi 1: " . $roi_id['0']['roi_id']);
                                 \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['1']['roi_id']);
                                 \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['2']['roi_id']);
                                 \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['3']['roi_id']);
                                 \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['4']['roi_id']);
                                 \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['5']['roi_id']);
                                if(empty($RegistroAdd['rpm_id'])){
                                    $id_rama = $registro_adicional_materias_model->insertRegistroAdicionalMaterias(
                                        $id, 
                                        $per_id,
                                        $pla_id,
                                        $paca_id,
                                        
                                        $roi_id['0']['roi_id'],
                                        $roi_id['1']['roi_id'],
                                        $roi_id['2']['roi_id'],
                                        $roi_id['3']['roi_id'],
                                        $roi_id['4']['roi_id'],
                                        $roi_id['5']['roi_id']

              
                                    );
                                }/*else{
                                    //$rpm_rama=Matriculacion::getRegistroAdicionalM($id);
                                }*/
                            } else{
                                $RegistroAdicionalMaterias = RegistroAdicionalMaterias::find()->where(['ron_id' => $id, 'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();

                                \app\models\Utilities::putMessageLogFile('RegistroAdicionalMaterias FUERA:' . print_r($RegistroAdicionalMaterias, true));
                                // \app\models\Utilities::putMessageLogFile('roi_arr FUERA:' . print_r($roi_arr, true));

                                if(isset($RegistroAdicionalMaterias)){
                                    $i = 0;
                                    for ($x=0; $x < 6; $x++) { 
                                        $roi_id_temp = $RegistroAdicionalMaterias['roi_id_' . ($x+1)];
                                        if($roi_id_temp == NULL){
                                            break;
                                        }
                                        $i++;
                                    }
                                    for ($j = 0; $j < count($rois_insertados); $j++) {
                                        $roi_id_nuevo = $rois_insertados[$j];
                                        // actualizar el registro con este roi_id_nuevo
                                        $update = (new RegistroAdicionalMaterias())->insertarActualizacionRegistroAdicional($RegistroAdicionalMaterias['rama_id'], $id, $roi_id_nuevo, $i);
                                        $i++;
                                        if(!$update){
                                            throw new Exception('Error al Registrar las Materias adicionales.');
                                        }
                                        
                                    }
                                }
                                else{
                                    // insertar nuevo registro con los nuevos roi ids
                                    $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                                    $paca_id = $modelPla['paca_id'];

                                    \app\models\Utilities::putMessageLogFile('id: ' . $id);
                                    \app\models\Utilities::putMessageLogFile('per_id: ' . $per_id);
                                    \app\models\Utilities::putMessageLogFile('pla_id: ' . $pla_id);
                                    \app\models\Utilities::putMessageLogFile('paca_id: ' . $paca_id);
                                    \app\models\Utilities::putMessageLogFile('roi_id: ' . print_r($roi_id, true));
                                    
                                    $id_rama = (new RegistroAdicionalMaterias())->insertRegistroAdicionalMaterias(
                                        $id, 
                                        $per_id,
                                        $pla_id,
                                        $paca_id,
                                        isset($rois_insertados[0]) ? $rois_insertados[0]: NULL,
                                        isset($rois_insertados[1]) ? $rois_insertados[1] : NULL,
                                        isset($rois_insertados[2]) ? $rois_insertados[2] : NULL,
                                        isset($rois_insertados[3]) ? $rois_insertados[3] : NULL,
                                        isset($rois_insertados[4]) ? $rois_insertados[4] : NULL,
                                        isset($rois_insertados[5]) ? $rois_insertados[5] : NULL
                                    );

                                    // \app\models\Utilities::putMessageLogFile('id_rama: ' . $id_rama);

                                    if(!$id_rama){
                                        throw new Exception('Error al Registrar las Materias adicionales.');
                                    }
                                }

                                

                                /*
                                if(empty($roi_id['0']['roi_id'])){
                                    $roi_id['0']['roi_id']="NULL";
                                }elseif(empty($roi_id['1']['roi_id'])){
                                    $roi_id['1']['roi_id']="NULL";
                                }elseif(empty($roi_id['2']['roi_id'])){
                                    $roi_id['2']['roi_id']="NULL";
                                }elseif(empty($roi_id['3']['roi_id'])){
                                    $roi_id['3']['roi_id']="NULL";
                                }elseif(empty($roi_id['4']['roi_id'])){
                                    $roi_id['4']['roi_id']="NULL";
                                }elseif(empty($roi_id['5']['roi_id'])){
                                    $roi_id['5']['roi_id']="NULL";*/
                            }


            /*}
                    
                        
                    if ($id_roi>0){

                        $roi_id = RegistroOnlineItem::find()->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                        \app\models\Utilities::putMessageLogFile("registro online " . $id);
                        
                        //\app\models\Utilities::putMessageLogFile("registro pago " . $model_rpm['rpm_id']);
                        $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                        $paca_id=$modelPla['paca_id'];
                        \app\models\Utilities::putMessageLogFile("periodo" . $modelPla['paca_id']);
                        $registro_adicional_materias_model  = new RegistroAdicionalMaterias();
                        $RegistroAdd=RegistroAdicionalMaterias::find()->select("rpm_id")->where(["per_id" => $per_id, "ron_id" => $id ])->asArray()->all();
                        //rpm_rama=Matriculacion::getRegistroAddicionalM($id);
                        /*if(empty($roi_id['0']['roi_id'])){
                            $roi_id['0']['roi_id']="NULL";
                        }elseif(empty($roi_id['1']['roi_id'])){
                            $roi_id['1']['roi_id']="NULL";
                        }elseif(empty($roi_id['2']['roi_id'])){
                            $roi_id['2']['roi_id']="NULL";
                        }elseif(empty($roi_id['3']['roi_id'])){
                            $roi_id['3']['roi_id']="NULL";
                        }elseif(empty($roi_id['4']['roi_id'])){
                            $roi_id['4']['roi_id']="NULL";
                        }elseif(empty($roi_id['5']['roi_id'])){
                            $roi_id['5']['roi_id']="NULL";
                        }*/

                        /* \app\models\Utilities::putMessageLogFile("asignacion de roi 1: " . $roi_id['0']['roi_id']);
                         \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['1']['roi_id']);
                         \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['2']['roi_id']);
                         \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['3']['roi_id']);
                         \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['4']['roi_id']);
                         \app\models\Utilities::putMessageLogFile("asignacion de roi : " . $roi_id['5']['roi_id']);
                        if(empty($RegistroAdd['rpm_id'])){
                            $id_rama = $registro_adicional_materias_model->insertRegistroAdicionalMaterias(
                                $id, 
                                $per_id,
                                $pla_id,
                                $paca_id,
                                
                                $roi_id['0']['roi_id'],
                                $roi_id['1']['roi_id'],
                                $roi_id['2']['roi_id'],
                                $roi_id['3']['roi_id'],
                                $roi_id['4']['roi_id'],
                                $roi_id['5']['roi_id']

      
                            );
                        }else{
                            $id_rama = $registro_adicional_materias_model->insertRegistroAdicionalMaterias(
                                $id, 
                                $per_id,
                                $pla_id,
                                $paca_id,
                                
                                $roi_id['0']['roi_id'],
                                $roi_id['1']['roi_id'],
                                $roi_id['2']['roi_id'],
                                $roi_id['3']['roi_id'],
                                $roi_id['4']['roi_id'],
                                $roi_id['5']['roi_id']

      
                            );
                        }*/
                        

                                            
                        \app\models\Utilities::putMessageLogFile("gastos ". $gastos_administrativos_valor);
                        // Se crea registro de cuotas
                        /*$arrPorcentajes = [
                            '6' => ['initial' => '16.65', 'others' => '16.67'],
                            '5' => ['initial' => '20', 'others' => '20'],
                            '4' => ['initial' => '25', 'others' => '25'],
                            '3' => ['initial' => '33.34', 'others' => '33.33'],
                        ];
                                            \app\models\Utilities::putMessageLogFile(1);
                        $fechaFinReg = date('Y-m-d');
                        $initialMonth = date('F', strtotime($fechaFinReg));
                        $initialMonNum = date('m', strtotime($fechaFinReg));
                        $initialDay = '05';//date('d', strtotime("$fechaFinReg +1 day"));
                        $initialYear = date('y', strtotime($fechaFinReg));
                                            \app\models\Utilities::putMessageLogFile(1);
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
                                \app\models\Utilities::putMessageLogFile(2);
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
                         */
                        /*Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);
                        Utilities::removeTemporalFile($tmp_path);*/
    
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }else{
                        \app\models\Utilities::putMessageLogFile("ERR");
                        throw new Exception('Error en Registro Online.');
                    }
                }
            }catch(Exception $e){
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'.$e->getMessage()),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }//fin de ajax

        $matriculacion_model = new Matriculacion();
        $today          = date("Y-m-d H:i:s");
        $result_process = $matriculacion_model->checkToday($today);
        $isdroptime     = $matriculacion_model->checkTodayisdrop($today);

        if (count($result_process) > 0) {
            /* Exist a register process */
            $pla_id = $result_process[0]['pla_id'];
            
            $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
            \app\models\Utilities::putMessageLogFile("getId". $resultIdPlanificacionEstudiante);
            if (count($resultIdPlanificacionEstudiante) > 0) {
                /*                 * Exist a register of planificacion_estudiante */
                $pes_id = $resultIdPlanificacionEstudiante[0]['pes_id'];
                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
		\app\models\Utilities::putMessageLogFile("getId". $resultIdPlanificacionEstudiante);
                $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
		\app\models\Utilities::putMessageLogFile("getId". $data_student);
                //$data_student = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $pes_id);
                if ($data_student) {
                    $RegistroOnline = RegistroOnline::find()->select("ron_id")->where(["per_id" => $per_id, "pes_id" => $pes_id ])->asArray()->all();
                    
                    if(empty($RegistroOnline))
                        $ron_id = 0;
                    else
                        $ron_id = $RegistroOnline[0]['ron_id']; 

                    //$ron_id              = $data_student["ron_id"];
                    $modelRonOn          = RegistroOnline::findOne($ron_id);
                    $dataRegistration    = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id,0);
                    $dataRegRs           = ArrayHelper::map($dataRegistration, "roi_id", "Code");
                    $dataPlanificacion   = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);
                    $materiasxEstudiante = PlanificacionEstudiante::findOne($pes_id);
                
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
                    $hasSubject = (count($dataPlanificacion) == count($dataRegRs))?false:true;
                    $howmuchSubject = count($dataRegRs);
                    $costo       = $matriculacion_model->getCostFromRegistroOnline($ron_id);
                    //$costo['costo'] = $gastoAdm;
                    $registro_add= $matriculacion_model->getRegistroAdiciOnline($ron_id);
                    //$costo=$dataplanificacion[0]['CostSubject'];
                    // if($modelRonOn->ron_estado_cancelacion == '1')
                     //   Yii::$app->session->setFlash('warning',"<h4>".Yii::t('jslang', 'Warning')."</h4>". Academico::t('matriculacion', 'There is a pending cancellation process.'));
                    
                    $unidadAcade = strtolower($data_student['pes_jornada']); 
                    $min_cancel = $this->limitCancel[$unidadAcade]['min'];

                    $estudiante_model = new Estudiante();
                    $periodo_model    = new PeriodoAcademico();
                    $est_array        = $estudiante_model-> getEstudiantexperid($per_id);
                    $paca_array       = $periodo_model-> getPeriodoAcademicoActual();
                    $est_id           = $est_array['est_id'];
                    $paca_id          = $paca_array[0]['id'];
                    $scholarship      = $estudiante_model->isScholarship($est_id,$paca_id);
                    $isscholar        = $scholarship['bec_id'];     

                    \app\models\Utilities::putMessageLogFile("gastos ". $gastos_administrativos_valor);
                     //per_id pla_id , pes_id
                     
                     
                     
                    $registro_model = new RegistroOnline();
                    $ronned    = $registro_model-> getcurrentRon($per_id);
                    $isschedule        = $ronned[0]['ronid']; 
                    /*    
                        $bloques = $data["bloque"];
                        $bloque = $bloques[0]; // Tomar el primer bloque
                        $mitad = 1; // Empezar asumiendo que se toma 1 solo bloque

                        // Tomar el valor actual de gastos administrativos
                        $gastos_administrativos_valor = RegistroOnline::find()->where(['ron_id' => $ron_id])->asArray()->one()['ron_valor_gastos_adm'];

                        if(count($bloques) == 2){
                            // Si sólo son 2 materias, los gastos administrativos son completos
                            $mitad = 1;
                        }
                        else{
                            foreach ($bloques as $key => $value) { // recorrer la lista de bloques
                                if($value != $bloque){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                                    $mitad = 2; // Así que se divide a la mitad
                                    break; // Salir del foreach
                                }
                                // Si nunca entra al condicional, quiere decir que todas las materias son del mismo bloque y se mantiene el valor de gatos administrativos
                            }
                        }
                    $gastos_administrativos_valor = $gastos_administrativos_valor / $mitad;*/
                    \app\models\Utilities::putMessageLogFile("gastos". $gastos_administrativos_valor);
                    if($data_student['mod_id']=='1'){
                        $gastoAdm=50;
                        $cobMat=65;
                        $dataMat['VARIOS']=$gastoAdm;
                        $dataMat['MAT-GRAD']=$cobMat;
                    } else if ($data_student['mod_id']=='2') {
                        // code...
                        $gastoAdm=300;
                        $cobMat=200;
                        $dataMat['VARIOS']=$gastoAdm;
                        $dataMat['MAT-GRAD']=$cobMat;
                    } else if ($data_student['mod_id']=='3') {
                        // code...
                        $gastoAdm=300;
                         $cobMat=200;
                        $dataMat['VARIOS']=$gastoAdm;
                        $dataMat['MAT-GRAD']=$cobMat;
                    } else if ($data_student['mod_id']=='4') {
                        // code...
                        $gastoAdm=300;
                         $cobMat=115;
                        $dataMat['VARIOS']=$gastoAdm;
                        $dataMat['MAT-GRAD']=$cobMat;
                    } else {
                        // code...
                        $gastoAdm=0;
                        $cobMat=0;
                        $dataMat['VARIOS']=$gastoAdm;
                        $dataMat['MAT-GRAD']=$cobMat;
                    } 
                     
                    return $this->render('registro', [
                                "pes_id" => $pes_id,
                                 "per_id" => $per_id,
                                "hasSubject" => $hasSubject,
                                "howmuchSubject" => $howmuchSubject,
                                "registredSuject" => $dataRegRs,
                                "planificacion" => $dataProvider,
                                "data_student" => $data_student,
                                "title" => Academico::t("matriculacion", "Register saved (Record Time)"),
                                "ron_id" => $ron_id,
                                "materiasxEstudiante" => $materiasxEstudiante,
                                "cancelStatus" => $modelRonOn->ron_estado_cancelacion,
                                "anularRegistro" => true,
                                "min_cancel" => $min_cancel,
                                "isdrop" => $isdroptime,
                                "isreg" => $result_process, 
                                "isadd" => $noAdd, 
                                "costo" => $costo, 
                                "registro_add"=>$registro_add,
                                "gastoAdm" => $gastoAdm,
                                
                                
                    ]);
                } else {
                    return $this->render('index-out', [
                                "message" => Academico::t("matriculacion", "1There is no information on the last registration (Registration time)"),
                    ]);
                }
            } else {
                /*                 * Not exist a planificacion_estudiante */
                
                 if ($userperid == $per_id) { 
                  return $this->render('index-out', [
                            "message" => Academico::t("matriculacion", "2There is no planning information (Registration time)"),
                             ]);
                            }else {
                             return $this->render('index-out', [
                             "message" => Academico::t("matriculacion", "Usted ya registro planificacion de este estudiante"),
                              ]);
                            }
               
            }
        } else {
            $resultData = $matriculacion_model->getLastIdRegistroOnline();
            if (count($resultData) > 0) {
                $last_ron_id = $resultData[0]['ron_id'];
                $last_pes_id = $resultData[0]['pes_id'];
                $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $last_pes_id);
                $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($last_ron_id,0);
                $materiasxEstudiante = PlanificacionEstudiante::findOne($last_pes_id);
                $modelRonOn = RegistroOnline::findOne($last_ron_id);
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
                //if($modelRonOn->ron_estado_cancelacion == '1')
                  //      Yii::$app->session->setFlash('warning',"<h4>".Yii::t('jslang', 'Warning')."</h4>". Academico::t('matriculacion', 'There is a pending cancellation process.'));

                return $this->render('registro', [
                            "planificacion" => $dataProvider,
                            "data_student" => $data_student,
                            "title" => Academico::t("matriculacion", "Last register saved (Non-registration time)"),
                            "ron_id" => $last_ron_id,
                            "cancelStatus" => $modelRonOn->ron_estado_cancelacion,
                            "materiasxEstudiante" => $materiasxEstudiante,
                ]);
            } else {
                /*  If not exist a minimal one register in registro_online */
                return $this->render('index-out', [
                            "message" => Academico::t("matriculacion", "There is no information on the last record (Non-registration time)"),
                ]);
            }
        }
    }//function actionRegistro


    public function actionExportpdf() {
        $report = new ExportFile();
        $this->view->title = Academico::t("matriculacion", "Registration"); // Titulo del reporte

        $matriculacion_model = new Matriculacion();

        $data = Yii::$app->request->get();

        $ron_id = $data['ron_id'];

        /* return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $ron_id); */

        $data_student = $matriculacion_model->getDataStudenbyRonId($ron_id);
        $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id,0);
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

    public function actionRegistrodetalle(){
        $per_id = Yii::$app->session->get("PB_perid");
        $usu_id = Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        
        $matriculacion_model = new Matriculacion();

        $ron = RegistroOnline::find()->where(['per_id' => $per_id, 'ron_estado' => 1, 'ron_estado_logico' => 1])->asArray()->one();

        $today = date("Y-m-d H:i:s");
        $result_process = $matriculacion_model->checkToday($today);
        $pla_id = $result_process[0]['pla_id'];
        $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
        $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
        // Por cosas de cóóóóóóóóóóóóódigos se necesita hacer todo eso para agarrar el verdadero pla_id

        $dataPlanificacion = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);

        // \app\models\Utilities::putMessageLogFile("dataPlanificacion: " . $dataPlanificacion);

        $roi = RegistroOnlineItem::find()->where(['ron_id' => $ron['ron_id'], 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
        $valor_total = 0;

        $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $ron['pes_id']);

        // Colocar sólo aquellas materias seleccionadas
        $materias_data_arr = [];
        $materias_roi = [];

        // Si se encuentran datos en registro_adicional_materias se debe realizar el cálculo sólo tomando en cuenta esas materias y debe aparecer el botón Pagar
        $rama = RegistroAdicionalMaterias::find()->where(['ron_id' => $ron['ron_id'], 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' => $data_student['paca_id'], 'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();

        // Las siguientes acciones se realizan sólo si hay registros en la tabla de registro_adicional_materias, pues todas usan del arreglo materias_roi
        if(isset($rama)){
            $roi_IDs = [$rama['roi_id_1'], $rama['roi_id_2'], $rama['roi_id_3'], $rama['roi_id_4'], $rama['roi_id_5'], $rama['roi_id_6']];

            foreach ($roi as $key => $value) {
                // Si hay registro en RegistroAdicionalMaterias
                if(in_array($value['roi_id'], $roi_IDs)){
                    $materias_roi[] = $value['roi_materia_nombre'];
                }
            }

            for ($i = 0; $i < count($dataPlanificacion); $i++) {
                if(in_array($dataPlanificacion[$i]['Subject'], $materias_roi)){
                    $valor = number_format($dataPlanificacion[$i]['Cost'] * $dataPlanificacion[$i]['Credit'], 2);
                    $dataPlanificacion[$i]['Cost'] = $valor;
                    $materias_data_arr[] = $dataPlanificacion[$i];
                    $valor_total += $dataPlanificacion[$i]['Cost'];
                }
            }
        }

        // \app\models\Utilities::putMessageLogFile("rama: " . print_r($rama, true));
        // \app\models\Utilities::putMessageLogFile("roi_IDs: " . print_r($roi_IDs, true));
        // \app\models\Utilities::putMessageLogFile("roi: " . print_r($roi, true));
        // \app\models\Utilities::putMessageLogFile("materias_roi: " . print_r($materias_roi, true));
        // \app\models\Utilities::putMessageLogFile("materias_roi: " . print_r($materias_roi, true));

        // Incluír los gastos administrativos
        $gastos_administrativos = $ron['ron_valor_gastos_adm'];
        $valor_total += $gastos_administrativos;
        // Llenar con campos vacíos las olumnas que no tengan datos para que no aparezcan como "(no definido)"
        $materias_data_arr[] = [
                                "Subject" => "Gastos Administrativos", 
                                "Cost" => $gastos_administrativos,
                                "Code" => "",
                                "Block" => "",
                                "Hour" => "",
                                ];

        // \app\models\Utilities::putMessageLogFile("materias_roi: " . print_r($materias_roi, true));

        $persona = Persona::find()->where(['per_id' => $per_id])->asArray()->one();

        $periodo = (new PeriodoAcademico())->consultarPeriodo($data_student['paca_id'], true)[0];
        $tipo_semestre = 0;
        $cuotas = 0;

        // Calcular cuál semestre es
        if(str_contains($periodo['saca_nombre'], '(Intensivo)')){ // Instensivo
            $tempBlock = [];
            foreach ($materias_data_arr as $key => $value) {
                $tempBlock[] = $value['Block'];
            }

            $bloque = $tempBlock[0]; // Tomar el primer bloque
            $cuotas = 0; // Poner las cuotas como 0 para comparar luego
            foreach ($tempBlock as $key => $value) { // recorrer la lista de bloques
                if($value != $bloque){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                    $cuotas = 5; // Así que las cuotas son 5
                    break; // Salir del foreach
                }
                // Si nunca entra al condicional, quiere decir que todas las materias son del mismo bloque
            }

            // Si luego del foreach las cuotas son 0 aún, el arreglo contiene sólo 1 bloque
            if($cuotas == 0){
                if($bloque == "B1"){ // Si el bloque es B1
                    $cuotas = 2; // Son 2 cuotas
                }
                else{ // Si no, el bloque es B2
                    $cuotas = 3; // Y las cuotas son 3
                }
            }
        }
        else{ // No intensivo
            $tempBlock = []; // Colocar todos los bloques en un arreglo aparte
            for ($x=0; $x < count($materias_data_arr) - 1; $x++) { 
                $tempBlock[] = $materias_data_arr[$x]['Block'];
            }

            // \app\models\Utilities::putMessageLogFile("tempBlock: " . print_r($tempBlock, true));

            $bloque = $tempBlock[0]; // Tomar el primer bloque
            $cuotas = 3; // Empezar con 3 cuotas

            foreach ($tempBlock as $key => $value) { // recorrer la lista de bloques
                // \app\models\Utilities::putMessageLogFile("IF: " . ($value != $bloque));
                if($value != $bloque){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                    $cuotas = 6; // Así que las cuotas son 6
                    break; // Salir del foreach
                }
                // Si nunca entra al condicional, quiere decir que todas las materias son del mismo bloque
            }

            // \app\models\Utilities::putMessageLogFile("cuotas: " . $cuotas);
        }
        // \app\models\Utilities::putMessageLogFile($cuotas);

        $valor_unitario = $valor_total / $cuotas;
        $porcentaje = $valor_unitario / $valor_total * 100;

        $porc_mayor = round($porcentaje, 2);
        $por_menor = 100 - ($porc_mayor * ($cuotas - 1));

        $fechas_vencimiento = FechasVencimientoPago::find()->where(['fvpa_paca_id' => $data_student['paca_id'], 'fvpa_estado' => 1, 'fvpa_estado_logico' => 1])->asArray()->all();
        $arr_pagos = [];
        for ($i=0; $i < $cuotas; $i++) { 
            if($i == 0){
                $arr_pagos[] = [
                                    "0" => "PAGO N° " . strval($i + 1), 
                                    "1" => explode(" ", $fechas_vencimiento[$i]['fvpa_fecha_vencimiento'])[0], 
                                    "2" => strval($por_menor) . "%",
                                    "3" => round($valor_total * $por_menor / 100, 2)
                                ];
            }
            else{
                $arr_pagos[] = [
                                    "0" => "PAGO N° " . strval($i + 1), 
                                    "1" => explode(" ", $fechas_vencimiento[$i]['fvpa_fecha_vencimiento'])[0], 
                                    "2" => strval($porc_mayor) . "%",
                                    "3" => round($valor_total * $porc_mayor / 100, 2)
                                ];
            }
        }

        $matDataProvider = new ArrayDataProvider([
            'key' => '',
            'allModels' => $materias_data_arr,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        $pagosDataProvider = new ArrayDataProvider([
            'key' => '',
            'allModels' => $arr_pagos,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        // Revisar si ya se ha pagado, para que no muestre el botón de pago si ya está hecho
        $rpm = RegistroPagoMatricula::find()->where(['per_id' => $per_id, 'pla_id' => $pla_id, 'rpm_estado' => 1, 'rpm_estado_logico' => 1])->asArray()->one();
        $pagado = $rpm['rpm_estado_generado'];

        /*\app\models\Utilities::putMessageLogFile($data_student);
        \app\models\Utilities::putMessageLogFile($persona);
        \app\models\Utilities::putMessageLogFile($materias_data_arr);
        \app\models\Utilities::putMessageLogFile("valor_total: " . $valor_total);
        \app\models\Utilities::putMessageLogFile($cuotas);
        \app\models\Utilities::putMessageLogFile($valor_unitario);
        \app\models\Utilities::putMessageLogFile($porcentaje);
        \app\models\Utilities::putMessageLogFile($arr_pagos);*/

        return $this->render('registrodetalle', [
            "data_student" => $data_student,
            "persona" => $persona,
            "valor_total" => $valor_total,
            "matDataProvider" => $matDataProvider,
            "pagosDataProvider" => $pagosDataProvider,
            "pla_id" => $pla_id,
            "ron_id" => $ron['ron_id'],
            "pagado" => $pagado,
            "rama" => $rama,
            "cuotas" => $cuotas,
        ]);
    }

    public function actionAnularregistro(){
        $data = Yii::$app->request->get();
        $transaction = Yii::$app->db_academico->beginTransaction();
        $per_id = (isset($data['per_id']) && $data['per_id'] != "")?($data['per_id']):(Yii::$app->session->get("PB_perid"));
        $usu_id = Yii::$app->session->get("PB_iduser");
        $template = "cancelregister";
        $templateEst = "cancelregisterest";
        $error = false;
        try{
            $matriculacion_model = new Matriculacion();
            $modelPersona        = Persona::findOne($per_id);
            $today = date("Y-m-d H:i:s");
            $result_process = $matriculacion_model->checkToday($today);
            if (count($result_process) > 0) {
                $ron_id = $data['ron_id'];
                $modelRegOn = RegistroOnline::findOne(['ron_id' => $ron_id, 'per_id' => $per_id]);
                $pes_id = $modelRegOn->pes_id;
                $modelPes = PlanificacionEstudiante::findOne($pes_id);
                $pla_id = $modelPes->pla_id;
                // Se verifica si hay algun pago realizado o por verificar
                $modelRegPag = RegistroPagoMatricula::findOne(['per_id' => $per_id, 'pla_id' => $pla_id, 'ron_id' => $ron_id, 'rpm_estado' => '1', 'rpm_estado_logico'=>'1',]);
                if(isset($data['cancelSubject']) && $data['cancelSubject'] = '1'){ // si hay un registro de pago se debe tomar en consideracion la anulacion por materias
                    $arr_sub_cancel = explode(";", $data['codes']);

                        
                          \app\models\Utilities::putMessageLogFile('Inicio Proceso cron:'.$arr_sub_cancel[0]);
                           $cod= $arr_sub_cancel[0];


                     //calcula cantidad de materias
                      $tm = count($arr_sub_cancel)-1; 

                    //Obtener cuotas y totales
                    $roc_model = new RegistroOnlineItem();
                      $facturas_model = new FacturasPendientesEstudiante();
                      $cuotas_model = new RegistroOnlineCuota();
                      $today = date("Y-m-d H:i:s");
                      $recalculo=$cuotas_model->recalcCuotas($ron_id);  
                      $pago=$cuotas_model->totalCuotas($ron_id);  
                     // $costo_mat = $roc_model->getCostobyRon($ron_id,$cod);
                      // $tm = $tm * $costo_mat[0]['roi_costo'] ;
                       
                       
                       $i=0; 
                      $costo_mat = 0;
                      $existrpm = 0;
                      \app\models\Utilities::putMessageLogFile('Codigos de materias');
                       \app\models\Utilities::putMessageLogFile(print_r($arr_sub_cancel,true));
                     //foreach($arr_sub_cancel as $arr_sub_cancel ){  
                      for($i=0; $i<count($arr_sub_cancel)-1; $i++){
                            $cod= $arr_sub_cancel[$i];
                            \app\models\Utilities::putMessageLogFile('codigo  MAT: '.$cod);
                            \app\models\Utilities::putMessageLogFile('codigo RON_ID: '.$ron_id);

                            $mat = $roc_model->getCostobyRon($ron_id,$cod);
                            $costo_mat += $mat[0]['roi_costo'];
                             // $i++;
                           //Validar que si tiene pago la matria a eliminar no recalcular  
                            $res_rpm_item =   $roc_model->verificarPagoMateriaByRegistroOnLine($ron_id,$cod);
                            \app\models\Utilities::putMessageLogFile('Resultado de contar pago de materia: '.$res_rpm_item['exist_rpm']);  
                            if ($res_rpm_item['exist_rpm'] >0){
                                $existrpm++;
                            }
                      } 
                    \app\models\Utilities::putMessageLogFile('totaldesc:'.$costo_mat);   
                    \app\models\Utilities::putMessageLogFile('recalculo:'.count($recalculo));


                      \app\models\Utilities::putMessageLogFile('Existen registros de pago matricula: '.$existrpm);



                      /**
                        DZM analista desarrollo03 proceso  recalculo de  las cuotas pendientes
                      */
                        //Obtenemos los saldos pendientes del estudiantes.
                        if  ($recalculo[0]['Cant'] > 0  && $existrpm > 0) {
                            $total_anul_costo = $costo_mat; // costo total de anulacion
                              $fpe_saldo_pendiente = $cuotas_model->getcurrentSaldoPersona($per_id);
                              //recorrer las facturas pendientes 
                              $cant_fpe =  count($fpe_saldo_pendiente); // cantidad de fpe
                              $val_desc = $total_anul_costo / $cant_fpe;
                              \app\models\Utilities::putMessageLogFile('1 - Anulacion materia  Cantidad de fpe:'.$val_desc);   
                              \app\models\Utilities::putMessageLogFile('1 - Anulacion materia  valor desc fpe:'.$val_desc);   
                              $i=0;
                              foreach($fpe_saldo_pendiente as $key => $value){
                                //restar el valor 
                                if(!$facturas_model->updateSaldosPendientesByAnulMaterias(
                                    $fpe_saldo_pendiente[$i]['fpe_id'],$val_desc,$usu_id)
                                )
                                {
                                    //error
                                }
                                 \app\models\Utilities::putMessageLogFile('1 - Anulacion materia Actualiza fpe:'.$fpe_saldo_pendiente[$i]['fpe_id']);
                                
                                $i++;

                              }

                        }





                        $prueba = false;


                        //$resp_persona['existen'] == 0) 
                      //Cambiar a saldo pendientes   
                      if  ($recalculo[0]['Cant'] > 0  && $existrpm > 0 && $prueba)  {
                      //recalcular
                          $tm= $costo_mat;
                          $cantcuotas = $recalculo[0]['Cant'] ;
                          $costcuotas = $recalculo[0]['Costo'];   
                          $pagadas = $pago[0]['Cant'];  
                          $pagadastot = $pago[0]['Costo']; 
                           //actualizar cuotas
                           //actualizar cuotas
                          $change_cuotas = $cuotas_model->updatecancelCuotas($pagadas,$tm,$cantcuotas,$costcuotas,$ron_id);
                          //$facturas_pendientes = $cuotas_model->getcurrentCuotas($ron_id);
                          $facturas_pendientesnew = $facturas_model->getcurrentCuotas($per_id);  
                          $fpe_pendientesnew = $facturas_model->getcurrentId($per_id);

                          // foreach ($facturas_pendientes as $key => $value) {
                                     //$facturas_model = new FacturasPendientesEstudiante();
                                  //$inserta_f = $facturas_model->insertarFacturacancel($promocion, $asi_id);
                                                                    

                            //  }
                            $cantcuotasf = $facturas_pendientesnew[0]['Cantf'] ;
                             
                            \app\models\Utilities::putMessageLogFile('valor pendiente:'.$facturas_pendientesnew[0]['Costof']);
                            \app\models\Utilities::putMessageLogFile('cantidad cuotas:'.$cantcuotasf);

                            $costcuotasf = $facturas_pendientesnew[0]['Costof'] - $tm; 
                            
                            \app\models\Utilities::putMessageLogFile('costo cuotas:'.$costcuotasf);
                            \app\models\Utilities::putMessageLogFile('fpeid:'.$fpe_pendientesnew[0]['fpeid']);
                            \app\models\Utilities::putMessageLogFile('fpeid:'.$fpe_pendientesnew[1]['fpeid']);
                            \app\models\Utilities::putMessageLogFile('fpeid:'.$fpe_pendientesnew[2]['fpeid']);
                            \app\models\Utilities::putMessageLogFile('fpeid:'.$fpe_pendientesnew[3]['fpeid']);
                            // for($i=0; $i<count($facturas_pendientesnew); $i++){
                             $i=0;
                              foreach($fpe_pendientesnew as $key => $value){
                                    //$facturas_model = new FacturasPendientesEstudiante();
                                    //$datafpeid= $datafpeid+1;  
                                     $datafpeid = $fpe_pendientesnew[$i]['fpeid'];  //registro online cuota
                                    // $existefactura = $facturas_model->getcurrentFacturas($dataid);
                                    //  if  (count($existefactura) > 0) {
                                    //$inserta_f = $facturas_model->insertarFacturacancel($datafpeid, $dataid, $datacuota, $datacosto); 
                                    $inserta_fact = $facturas_model->insertarnewFacturacancel($datafpeid,$cantcuotasf,$costcuotasf);          
                                    //} Else {
                                    // $inserta_f = $facturas_model->insertarnewFacturacancel($perid, $dataid, $datacuota, $datacosto);
                                    //} 
                                    $i++;
                             }  
                        // } 
                      }




                    $modelRegItem = RegistroOnlineItem::findAll(['ron_id' => $ron_id, 'roi_estado' => '1', 'roi_estado_logico' => '1']);
                    $modelPlanif = Planificacion::findOne(['pla_id' => $pla_id, 'pla_estado' => '1', 'pla_estado_logico' => '1',]);
                    $i = 0;
                    
                    $subReg = count($arr_sub_cancel) - 1; // cantidad de materias a cancelar
                     $minime = count($modelRegItem) - $subReg;
                    if( $minime < 2 ){
                        throw new Exception('Error to Update Online Register.');
                    }
                    foreach($modelRegItem as $key => $item){ $i++; } // cantidad de materias registradas
                    if($modelRegPag){// existe un pago de registro por lo que debe realizar el proceso     
                        $template = "removeSubjects";
                        $templateEst = "removeSubjectsEst";           
                        $modelRegOn->ron_estado_cancelacion = '1'; 
                        if(!$modelRegOn->save()){
                            throw new Exception('Error to Update Online Register.');
                        }
                        $modelCancelReg = new CancelacionRegistroOnline();
                        $modelCancelReg->ron_id = $ron_id;
                        $modelCancelReg->per_id = $per_id;
                        $modelCancelReg->pla_id = $pla_id;
                        $modelCancelReg->paca_id = $modelPlanif->paca_id;
                        $modelCancelReg->rpm_id = $modelRegPag->rpm_id;
                        $modelCancelReg->cron_fecha_creacion = $today;
                        $modelCancelReg->cron_estado_cancelacion = '3';
                        $modelCancelReg->cron_estado = '1';
                        $modelCancelReg->cron_estado_logico = '1';
                        if(!$modelCancelReg->save()){
                            throw new Exception('Error to Create Cancel Register.');
                        }
                        for($j = 0; $j < (count($arr_sub_cancel) - 1); $j++){
                        $asigeliminada[$j]=$arr_sub_cancel[$j];
                            $modelRegItem = RegistroOnlineItem::findOne(['ron_id' => $ron_id, 'roi_estado' => '1', 'roi_estado_logico' => '1', 'roi_materia_cod' => $arr_sub_cancel[$j]]);
                            $modelCancelIt = new CancelacionRegistroOnlineItem();
                            $modelCancelIt->roi_id = $modelRegItem->roi_id;
                            $modelCancelIt->cron_id = $modelCancelReg->cron_id;
                            $modelCancelIt->croi_estado = '1';
                            $modelCancelIt->croi_estado_logico = '1';
                            $modelCancelIt->croi_fecha_creacion = $today;
                            if(!$modelCancelIt->save()){
                                throw new Exception('Error to Create Items Cancel Register.');
                            }
                        }
                    }

                    //else{ // no existe pago, solo se recalcula todo nuevamente
                        if($i == $subReg){ // Se debe continuar con la cancelacion normal
                            $template = "cancelregister";
                            // cambiar estados en registro_online
                            $modelRegOn->ron_estado = '0';
                            $modelRegOn->ron_estado_logico = '0';
                            $modelRegOn->ron_estado_registro = '0';
                            $modelRegOn->ron_fecha_modificacion = $today;
                            $modelRegOn->ron_usuario_modifica = $usu_id;
                            if(!$modelRegOn->save()){
                                throw new Exception('Error to Update Online Register.');
                            }
                            // cambiar estados en registro_online_item
                            $modelRegItem = RegistroOnlineItem::findAll(['ron_id' => $ron_id]);
                            if($modelRegItem){
                                foreach($modelRegItem as $key => $item){
                                    $item->roi_estado = '0';
                                    $item->roi_estado_logico = '0';
                                    $item->roi_fecha_modificacion = $today;
                                    $item->roi_usuario_modifica = $usu_id;
                                    if(!$item->save()){
                                        throw new Exception('Error to Update Online Item Register.');
                                    }
                                }
                            }
                            // cambiar estados en registro_online_cuotas
                            $modelRegCuo = RegistroOnlineCuota::findAll(['ron_id' => $ron_id]);
                            if($modelRegCuo){
                                foreach($modelRegCuo as $key2 => $cuota){
                                    $cuota->roc_estado = '0';
                                    $cuota->roc_estado_logico = '0';
                                    $cuota->roc_fecha_modificacion = $today;
                                    $cuota->roc_usuario_modifica = $usu_id;
                                    if(!$cuota->save()){
                                        throw new Exception('Error to Update Online Item Register.');
                                    }
                                }
                            }
                            // cambiar estados en registro_pago_matricula
                                
                            if($modelRegPag){
                                foreach($modelRegPag as $key => $reg){
                                    $reg->rpm_estado = '0';
                                    $reg->rpm_estado_logico = '0';
                                    $reg->rpm_fecha_modificacion = $today;
                                    $reg->rpm_usuario_modifica = $usu_id;
                                    if(!$reg->save()){
                                        throw new Exception('Error to Update Payment Register.');
                                    }
                                }
                            } 
                            // cambiar estados en enrolamiento_agreement
                            $modelEnroll = EnrolamientoAgreement::findAll(['ron_id' => $ron_id, 'per_id' => $per_id]);
                            if($modelEnroll){
                                foreach($modelEnroll as $key2 => $item){
                                    $item->eagr_estado = '0';
                                    $item->eagr_estado_logico = '0';
                                    $item->eagr_fecha_modificacion = $today;
                                    if(!$item->save()){
                                        throw new Exception('Error to Update Enroll Agreement.');
                                    }
                                }
                            }
                            // cambiar estados en registro adicional materias
                            $modelAdMat = RegistroAdicionalMaterias::findAll(['ron_id' => $ron_id, 'per_id' => $per_id]);
                            if($modelAdMat){
                                foreach($modelAdMat as $key4 => $mat){
                                    $mat->rama_estado_logico = '0';
                                    $mat->rama_estado = '0';
                                    $mat->rama_fecha_modificacion = $today;
                                    if(!$mat->save()){
                                        throw new Exception('Error to Update Aditional Subjects.');
                                    }
                                }
                            }
                            // cambiar estados en registro estudiante_pago_carrera
                            $modelPagCarr = EstudiantePagoCarrera::findOne(['ron_id' => $ron_id, 'per_id' => $per_id, 'epca_estado' => '1', 'epca_estado_logico' => '1']);
                            if($modelPagCarr){
                                $modelPagCarr->epca_estado = '0';
                                $modelPagCarr->epca_estado_logico = '0';
                                $modelPagCarr->epca_fecha_modificacion = $today;
                                if(!$modelPagCarr->save()){
                                    throw new Exception('Error to Update Career Payment.');
                                }
                            }
                        }else{ // como no es igual entonces se hace eliminacion de la materias seleccionadas
                            $modelRegItem = RegistroOnlineItem::findAll(['ron_id' => $ron_id]);
                            $sumMatr = 0;
                            foreach($modelRegItem as $key => $item){
                                if(in_array($item->roi_materia_cod, $arr_sub_cancel)){
                                    $item->roi_estado = '0';
                                    $item->roi_estado_logico = '0';
                                    $item->roi_fecha_modificacion = $today;
                                    $item->roi_usuario_modifica = $usu_id;
                                    if(!$item->save()){
                                        throw new Exception('Error to Update Online Item Register.');
                                    }
                                }else
                                    $sumMatr += $item->roi_costo;
                            }
                            $modelRegOn->ron_valor_matricula = $sumMatr;
                            if(!$modelRegOn->save()){
                                throw new Exception('Error to Update Online Register.');
                            }
                        }
                    //}







                }
                // envio de mail a colecturia del proceso de anulacion
                $data_student = $matriculacion_model->getDataStudenbyRonId($ron_id);
                $user_names_est = $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido;
                //if(isset($data['admin']) && $data['admin'] == '1'){
                $receiveMail = Yii::$app->params["colecturia"];
                
                $user_names = "Lords";
                //}

                $body = Utilities::getMailMessage($template, array(
                            "[[user]]" => $user_names,
                            "[[nombres]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido,
                            "[[dni]]" => (($modelPersona->per_cedula != "")?$modelPersona->per_cedula:$modelPersona->per_pasaporte),
                            "[[periodo]]" => $data_student["pla_periodo_academico"],
                            "[[modalidad]]" => $data_student["mod_nombre"]
                                ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                $titulo_mensaje = Academico::t("matriculacion", "Online Registration Cancellation");

                
                ///PDF
                $report = new ExportFile();
                $this->view->title = Academico::t("matriculacion", "Registration"); // Titulo del reporte
                $matriculacion_model = new Matriculacion();
                $materiasxEstudiante = PlanificacionEstudiante::findOne($pes_id);
                $data_student = $matriculacion_model->getDataStudenbyRonId($ron_id);
                $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id,0);
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
                
                
                  $path = "Registro_" . date("Ymdhis") . ".pdf";
                $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
                $report->createReportPdf(
                        $this->render('exportpdf', [
                            "planificacion" => $dataProvider,
                            "data_student" => $data_student,
                            "materiasxEstudiante" => $materiasxEstudiante,
                        ])
               );

              $tmp_path = sys_get_temp_dir() . "/" . $path;
              $report->mpdf->Output($tmp_path, ExportFile::OUTPUT_TO_FILE);
                
                
                $from = Yii::$app->params["adminEmail"];

                $to = array(
                    "0" => $receiveMail,
                );
                /*$files = array(
                    "0" => Yii::$app->basePath . Yii::$app->params["documentFolder"] . "pagosmatricula/" . $data["file"],
                );*/
                $toest = $modelPersona->per_correo;
                $files = array();
                  $files = array( "0" => $tmp_path,);
                $asunto = Academico::t("matriculacion", "Cancellation Registration");
                
               $asignatura_model = new Asignatura();
              $asignatura1 = $asignatura_model->getAsignaturaname($asigeliminada[0]);
              $asignatura2 = $asignatura_model->getAsignaturaname($asigeliminada[1]); 
                
                $bodyest = Utilities::getMailMessage($templateEst, array(
                            "[[user]]" => $user_names_est,
                            "[[periodo]]" => $data_student["pla_periodo_academico"],
                            "[[modalidad]]" => $data_student["mod_nombre"],
                             "[[asig1]]" =>  $asignatura1["asignatura"],
                             "[[asig2]]" =>  $asignatura2["asignatura"]
                                ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");

                $transaction->commit();
                Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);
                Utilities::sendEmail($titulo_mensaje, $from, $toest, $asunto, $bodyest, $files);
             Utilities::removeTemporalFile($tmp_path);
                Yii::$app->session->setFlash('success',"<h4>".academico::t('jslang', 'Exito')."</h4>".academico::t("registro","The cancellation of the registration was successful."));
            }
            if(isset($data['admin']) && $data['admin'] == '1')
                return $this->redirect('list');
            return $this->redirect('registro');
        } catch (Exception $ex) {
            $transaction->rollback();
            //$msg = ($error)?($ex->getMessage()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
            $msg = $ex;
            Yii::$app->session->setFlash('success',"<h4>".Yii::t('jslang', 'Error')."</h4>". $msg);
            if(isset($data['admin']) && $data['admin'] == '1')
                return $this->redirect('list');
            return $this->redirect('registro');
        }
    }//function actionAnularregistro
}
